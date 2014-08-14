<?php
/*
Plugin Name: Stripe Payment Gateway
Plugin URI: http://www.getshopped.org
Version: 1.01
Author: Chris Ensell, GetShopped.org
Author URI:  http://www.getshopped.org
*/

function add_strpe_gateway($nzshpcrt_gateways){
	$num = count($nzshpcrt_gateways)+1;
	$nzshpcrt_gateways[$num]['name'] = 'Stripe';
	$nzshpcrt_gateways[$num]['admin_name'] = 'Stripe';
	$nzshpcrt_gateways[$num]['internalname'] = 'stripe';
	$nzshpcrt_gateways[$num]['function'] = 'stripe_merchant';
	$nzshpcrt_gateways[$num]['form'] = "form_stripe";
	$nzshpcrt_gateways[$num]['submit_function'] = "submit_stripe";
	$nzshpcrt_gateways[$num]['payment_type'] = "stripe";
	return $nzshpcrt_gateways; 
}

add_filter('wpsc_merchants_modules','add_strpe_gateway',100);

global $gateway_checkout_form_fields;
if(in_array('stripe',(array)get_option('custom_gateway_options'))) {
	$gateway_checkout_form_fields['stripe'] = '
		<div class="stripe-wrap">
			<div class="brands"></div><div class="exp">Exp.</div>
			<div class="saved-card"><span class="mini-card"></span><span class="stripe-last4"></span></div>
			<input type="hidden" value="" name="stripe_amount" class="stripe-amount">
			<input type="hidden" value="" name="stripe_id" class="stripe-id">
			<input type="hidden" value="" name="stripe_bar" class="stripe-barid">
			<input type="hidden" value="" name="stripe_name" class="stripe-name">
			<input type="hidden" value="" name="stripe_email" class="stripe-email">
			<input type="hidden" value="" name="stripe_token" class="stripe-token">
			<input type="hidden" value="" name="stripe_last4" class="stripe-last4">
			<input type="hidden" value="" name="wpec_subtotal" class="wpec-subtotal">
			<input type="hidden" value="" name="wpec_shipping" class="wpec-shipping">
			<input type="hidden" value="" name="wpec_tax" class="wpec-tax">
			<input type="hidden" value="" name="wpec_promo" class="wpec-promo">
			<input type="hidden" value="" name="wpec_discount" class="wpec-discount">
			<input type="hidden" value="" name="wpec_total" class="wpec-total">
			<input type="hidden" value="" name="wpec_seating" class="wpec-seating">
			<input type="tel" placeholder="Card Number" maxlength="16" name="stripe_card_number" id="cc-input" class="cc-input secure">
			<input type="tel" placeholder="MM" size="2" maxlength="2" name="stripe_exp_date_m" class="month-input secure">
			<input type="tel" placeholder="YYYY" size="4" maxlength="4" name="stripe_exp_date_y" class="year-input secure">
			<input type="hidden" placeholder="CVV" size="4" maxlength="4" name="stripe_cvc" class="cvv-input secure">
			<div class="ssl-text">SSL encrypted <span class="ssl-icon"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(AES-256)</div>
		</div><!--/stripe-wrap-->
	';
}

require_once('stripe/Stripe.php');	
if(get_option('permalink_structure') != '') {
	$seperator ="?";
} else {
	$seperator ="&";	
}
function stripe_merchant($seperator,$sessionid) {

	global $wpsc_cart, $wpdb;
	$_SESSION['wpsc_sessionid'] = $sessionid;
	$desc = $_POST['collected_data'][8]."$sessionid";
	Stripe::setApiKey( get_option('sgw_api_key') );
	$id = $_POST['stripe_id'];
	$amount = $_POST['stripe_amount'];
	$name = $_POST['stripe_name'];
	$token = $_POST['stripe_token'];
	$brand = $_POST['stripe_brand'];
	$email = $_POST['stripe_email'];
	$response = "approved";
	try {
		$charge = Stripe_Charge::create(array(
			"currency"	=> "usd",
			"amount"	=> $amount,
			"card"		=> array(
				'name'			=> $_POST['stripe_name'],
				'number'		=> $_POST['stripe_card_number'], 
				'exp_month'		=> $_POST['stripe_exp_date_m'], 
				'exp_year'		=> $_POST['stripe_exp_date_y'],
				'cvc'			=> $_POST['stripe_cvc']
			),
			"description" => $desc
		));
	} catch (Stripe_Error $e) {
		$response = "fail";
		$error = $e->getMessage();
	}
	if($response != "approved") {
		echo 0;
    } else {
		$customer = Stripe_Customer::create(array(
		  "card" => $token,
		  "description" => $email)
		);
		Stripe_Charge::create(array(
		  "currency" => "usd",
		  "amount" => $amount,
		  "customer" => $customer->id)
		);
		$sql_erase = "delete from wp_usermeta where meta_key='stripe_token' or meta_key='stripe_brand' and user_id='$id' limit 2";
		mysql_query($sql_erase);
	    $wpdb->insert('wp_usermeta', array(
	        'user_id' => $id,
	        'meta_key' => "stripe_token",
	        'meta_value' => $customer->id
	    ));
	    $wpdb->insert('wp_usermeta', array(
	        'user_id' => $id,
	        'meta_key' => "stripe_brand",
	        'meta_value' => $brand
	    ));
	    echo 1;
	}
	exit();
}

function submit_stripe() {
	$options = array(
		'api_key'
	);
	foreach ( $options as $option ) {
		$field = "sgw_{$option}";	
		if ( ! empty( $_POST[$field] ) )
			update_option( $field, $_POST[$field] );
	}
	return true;
}

function form_stripe() {
	if(get_option('sgw_api_key')!='')
		$sgw_api_key = get_option('sgw_api_key');
	else
		$sgw_api_key = '';
	$output = "<tr>\n\r";
	$output .= "<td>\n\r<label for='sgw_api_key'>".__('API Key','wpsc')."</label></td>";
	$output .= "<td><input type='text' id='sgw_api_key' value='".$sgw_api_key."' name='sgw_api_key' /></td>";
	$output .= "</tr>\n\r";
	return $output;
}
?>
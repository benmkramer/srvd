<?php
/*
Plugin Name: Flowtab API (App)
Plugin URI: http://nick.hesling.com:81
Description: A nifty API plugin for Flowtab's mobile app.
Version: 1.02
Author: Alex Kouznetsov & Kyle Hill
Author URI: http://nick.hesling.com:81
License: Flowtab
*/

// Login

add_action("wp_ajax_loginajax", "loginajax_do");
add_action("wp_ajax_nopriv_loginajax", "loginajax_do");
function loginajax_do() {
    //error_reporting(1);
    //error_log("error log test11\n");
    //error_reporting(0);
    $uname = $_REQUEST["uname"];
    $upass = $_REQUEST["upass"];
    $creds = array();
    $creds['user_login'] = $uname;
    $creds['user_password'] = $upass;
    $creds['remember'] = true;
    $success = user_pass_ok($uname, $upass);
    if ($success) {
        wp_set_auth_cookie($uname, true);
        wp_signon($creds, false);
        echo "1";
    } else {
        echo "0";
    }
    die();
}

// Logout

add_action("wp_ajax_logout", "logout_do");
function logout_do() {
    wp_clear_auth_cookie();
    die();
}

// Register

add_action("wp_ajax_registerajax", "registerajax_do");
add_action("wp_ajax_nopriv_registerajax", "registerajax_do");
function registerajax_do() {
	global $wpdb;
    $fname = $_REQUEST["first_name"];
    $lname = $_REQUEST["last_name"];
    $email = $_REQUEST["email"];
    $uname = $_REQUEST["user_name"];
    $upass = $_REQUEST["pass"];
    $email_given = 1;
    // Checks if email provided (optional)
    if (empty($email)) {
    	$email = $uname;
        $email_given = 0;
    }
    // Missing fields
    if (empty($fname) || empty($lname) || empty($uname) || empty($upass)) {
        echo -1;
        die();
    }
    // User exists
    if (username_exists($uname)) {
        echo -2;
        die();
    }
    // Email exists
    if ($email_given == 1) {
	    if (email_exists($email) != false) {
	        echo -3;
	        die();
	    }    
    }
    // Create user
    $user_id = wp_create_user($uname, $upass, $email);
    if ($user_id > 0) {
        update_user_meta($user_id, 'first_name', $fname);
        update_user_meta($user_id, 'last_name', $lname);
        if ($email_given == 0) {
			$wpdb->update($wpdb->users, array('user_email' => ''), array('ID' => $user_id));
        }
        // Log user in
        $creds = array();
        $creds['user_login'] = $uname;
        $creds['user_password'] = $upass;
        $creds['remember'] = true;
        wp_set_auth_cookie($uname, true);
        wp_signon($creds, false);
        echo 1;
	    if ($email_given == 1) {
	        // Send Welcome email
	        $subject = 'Welcome to Flowtab';
	        $message1 = '<p>Hello ' . $fname . ',</p>';
	        $message2 = '<p>Thanks for joining us, we look forward to welcoming you into our community. Feel free to <a target="_blank" href="http://twitter.com/flowtab">follow us on Twitter</a> or <a target="_blank" href="http://facebook.com/flowtab">like us on Facebook</a> to stay up to date on what\'s happening in your area.</p>';
	        $message3 = '<p>Flowtab Team</p>';
	        wp_mail($email, $subject, $message1 . $message2 . $message3);	
    	}
    } else {
        echo 0;
    }
    die();
}

// Update user

add_action("wp_ajax_updateuser", "updateuser_do");
add_action("wp_ajax_updateuser", "updateuser_do");

function updateuser_do() {
    global $wpdb, $current_user;
    $user_id = $current_user->ID;
    $first_name = $_REQUEST['first_name'];
    $last_name = $_REQUEST['last_name'];
    $user_email = $_REQUEST['user_email'];
    $user_login = $_REQUEST['user_login'];
    if ((empty($first_name)) || (empty($last_name)) || (empty($user_login))) {
        echo -1; die();
    }
    $duplicate = $wpdb->get_row("select user_login from wp_users where user_login = '$user_login' and not ID = '$user_id'");
    if ($duplicate > 0) {
        echo -2; die();
    }
    update_user_meta($user_id, 'first_name', $first_name);
    update_user_meta($user_id, 'last_name', $last_name);
    $wpdb->update($wpdb->users, array('user_login' => $user_login), array('ID' => $user_id));
    $wpdb->update($wpdb->users, array('user_email' => $user_email), array('ID' => $user_id));
    $wpdb->update($wpdb->users, array('user_nicename' => $user_login), array('ID' => $user_id));
    wp_clear_auth_cookie();
    echo 1;
    die();
}

// Reset password

add_action("wp_ajax_password", "password_do");
add_action("wp_ajax_nopriv_password", "password_do");

function password_do() {
    $user_login = $_REQUEST["uname"];
    global $wpdb, $current_site;
    $site_url = get_site_url();
    $user_info = get_userdatabylogin($user_login);
    $user_email = $user_info->user_email;
    $user_fname = $user_info->first_name;
    $nice_phone = preg_replace('~(\d{3})[^\d]*(\d{3})[^\d]*(\d{4})$~', '$1-$2-$3', $user_login);
    do_action('retrieve_password', $user_login);
    $key = $wpdb->get_var($wpdb->prepare("select user_activation_key from wp_users where user_login = '$user_login'"));
    if (empty($key)) {
        // Generate something random for a key...
        $key = wp_generate_password(20, false);
        do_action('retrieve_password_key', $user_login, $key);
        // Now insert the new md5 key into the db
        $wpdb->update($wpdb->users, array('user_activation_key' => $key), array('user_login' => $user_login));
    }
    $message .= __('<p>Hi ' . $user_fname . ',</p>');
    $message .= __('<p>To reset your PIN number for <strong>' . $nice_phone . '</strong>, please click the following link:</p>');
    $message .= network_site_url("wp-login.php?action=rp&key=$key&login=".rawurlencode($user_login),'login');
    $message .= __('<p>If this was a mistake, just ignore this email and nothing will happen.</p>');
    $message .= __('<p>Flowtab Team</p>');
    $title = 'Flowtab Password Reset';
    $message = apply_filters('retrieve_password_message', $message, $key);
    if ($message && !wp_mail($user_email, $title, $message)) {
        echo "0"; die();
    } else {
        wp_mail($user_email, $title, $message);
	    $direction = "$site_url/wp-login.php?action=rp&key=$key&login=$user_login";
		function bitly_shorten($url){
			$query = array(
			    "version" => "2.0.1",
			    "longUrl" => $url,
			    "login" => 'flowtab',
			    "apiKey" => 'R_8a16f491e322e27c87507ddcb130f40a'
			);
			$query = http_build_query($query);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "http://api.bitly.com/v3/shorten?".$query);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$response = curl_exec($ch);
			curl_close($ch);
			$response = json_decode($response);
			return $response->data->url;
		}
		$bitly_link = bitly_shorten($direction);
		$url = 'http://www.itduzzit.com/duzz/api/twilio-send-sms.json?token=onz2gr9i9khj0qx&Mobile+Number+to+Call='.$user_login.'&Send+from+Mobile+Number=6466993569&Text=To+reset+your+Flowtab+PIN+number+please+click+this+link:+'.$bitly_link.'&?callback=?';
		$options = array('http' => array('method'  => 'POST','content' => http_build_query()));
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);        
        echo "1"; die();
    }
   
}

// Get profile info and bar locations

add_action("wp_ajax_getprofile", "getprofile_do");
function getprofile_do(){
    global $wpdb, $current_user;
    if (is_user_logged_in()) { $login = 1; } else {	die(); }    
    echo '<span id="profile-storage" class="hidden">';
	    echo '<span id="get-login">'.$login.'</span>';
	    echo '<span id="get-userid">'.$current_user->ID.'</span>';
	    echo '<span id="get-phone">'.$current_user->user_login.'</span>';
	    echo '<span id="get-email">'.$current_user->user_email.'</span>';
	    echo '<span id="get-fname">'.$current_user->user_firstname.'</span>';
	    echo '<span id="get-lname">'.$current_user->user_lastname.'</span>';
	    echo '<span id="get-points">'.$current_user->rich_editing.'</span>';
	    echo '<span id="get-token">'.$current_user->stripe_token.'</span>';
	    echo '<span id="get-last4">'.$current_user->stripe_last4.'</span>';
	    echo '<span id="get-code">'.$current_user->affiliate_code.'</span>';
	    echo '<span id="get-orders">'.$current_user->affiliate_orders.'</span>';
	    echo '<span id="get-earnings">'.$current_user->affiliate_earnings.'</span>';
	    echo '<span id="get-url">'.site_url().'</span>';
	    echo '<span id="get-time">'.date().'</span>';
    echo '</span><!--/hidden-->';
	if (get_user_meta(2,'jabber',true)==0) { $live_2 = 'closed'; } else { $live_2 = 'open'; };
	if (get_user_meta(3,'jabber',true)==0) { $live_3 = 'closed'; } else { $live_3 = 'open'; };
	?>
	<div id="locations-list">
		<div class="spacer">Marina HQ, CA</div>
		<!-- just hacked this so i could see more site navigation -->
		<li barid="8" barname="Shady Grove" class="open">
			<p class="title">Shady Grove</p>
			<p class="addy">5500 Walnut St, Pittsburgh, PA 15232</p>
		</li>
		<!--<li barid="2" barname="Basement" class="open<?php /*echo $live_2; */?>">
			<p class="title">Basement</p>
			<p class="addy">2640 Main St, Santa Monica</p>
		</li>
		<li barid="3" barname="Apple Bar" class="<?php /*echo $live_3;*/ ?>">
			<p class="title">Apple Bar</p>
			<p class="addy">1326 Francisco St, Santa Monica</p>
		</li>-->
	</div>
		
	<?php ; die();
}

// Send mobile confirm

add_action("wp_ajax_sendmobile", "sendmobile_do");
add_action("wp_ajax_nopriv_sendmobile", "sendmobile_do");

function sendmobile_do() {
    global $current_user;
    $id = $current_user->ID;
    $fname = $current_user->first_name;
    $phone = $current_user->user_login;
    $site_url = get_site_url();
    $confirm_url = $site_url.'/wp-admin/admin-ajax.php?action=mobileconfirm&id='.$id;
	function bitly_shorten($url){
		$query = array(
		    "version" => "2.0.1",
		    "longUrl" => $url,
		    "login" => 'flowtab',
		    "apiKey" => 'R_8a16f491e322e27c87507ddcb130f40a'
		);
		$query = http_build_query($query);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://api.bitly.com/v3/shorten?".$query);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec($ch);
		curl_close($ch);
		$response = json_decode($response);
		return $response->data->url;
	}
	$bitly_link = bitly_shorten($confirm_url);
	$options = array('http' => array('method'  => 'POST','content' => http_build_query()));
	$context  = stream_context_create($options);
	$text_url = 'http://www.itduzzit.com/duzz/api/twilio-send-sms.json?token=onz2gr9i9khj0qx&Mobile+Number+to+Call='.$phone.'&Send+from+Mobile+Number=6466993569&Text=Flowtab+would+like+to+verify+this+cell+number%2C+please+click+this+link:+'.$bitly_link.'&?callback=?';
	$result = file_get_contents($text_url, false, $context);
	echo $bitly_link;
    die();
}

// Mobile confirm

add_action("wp_ajax_mobileconfirm", "mobileconfirm_do");
add_action("wp_ajax_nopriv_mobileconfirm", "mobileconfirm_do");

function mobileconfirm_do() {
    global $wpdb;
    $id = $_GET['id'];
    update_user_meta($id,'mobile_confirm',1);
    echo '<script type="text/javascript">window.location.href="/confirmed";</script>';
    die();
}

// Get menu items

add_action("wp_ajax_barmenus", "barmenus_do");
add_action("wp_ajax_nopriv_barmenus", "barmenus_do");

function barmenus_do() {
	global $wpdb,$wpsc_cart;
	$wpsc_cart->empty_cart(false);
	$id = $_GET["id"];
    function getMenu($cat) {
    	global $wpdb;
		$id = $_GET["id"];
    	$cat_id = $cat.'-'.$id;
		$products = $wpdb->get_results("select * from wp_posts where category = '$cat_id' order by post_title");
		echo '<div id="'.$cat.'-cat">';
		foreach ($products as $product) {
			if (!empty($product)) {
				$obj_id = $product->ID;
				$item = $wpdb->get_row("select post_title,post_content,post_name,price,sale_price from wp_posts where ID = '$obj_id' limit 1");
				echo '<span id="p-'.$obj_id.'" class="p-item">';
					echo '<form class="product-form">';
						echo '<div class="p-title">'.$item->post_title.'</div>';
						echo '<div class="p-desc">'.$item->post_content.'</div>';
						echo '<div class="p-price">$'.number_format($item->price,2).'</div>';
						echo '<div class="p-sale">$'.number_format($item->sale_price,2).' (Happy Hour!)</div>';
						echo '<div type="submit" value="" class="buy-button touch" key="'.$obj_id.'" cat="'.$cat.'"></div>';
					echo '</form><!--/product-form-->';
				echo '</span><!--/p-item-->';
			};
		} echo '</div><!--/cat-->';
	};
	function getVars($cat) {
    	global $wpdb;
    	$id = $_GET["id"];
    	$cat_id = $cat.'-'.$id;
		$products = $wpdb->get_results("select * from wp_posts where category = '$cat_id'");
		echo '<div id="'.$cat.'-cat">';
		foreach ($products as $product) {
			if (!empty($product)) {
				$obj_id = $product->ID;
				$item = $wpdb->get_row("select post_title from wp_posts where ID = '$obj_id' limit 1");
				echo '<div class="mixer-item swiper-slide" mixer="'.$item->post_title.'">'.$item->post_title.'</div>';
			};
		} echo '</div><!--/cat-->';
	}
	getMenu('b');	// Beer
	getMenu('w');	// Wine
	getMenu('cf');	// Cocktails (Featured) 
	getMenu('cw');	// Cocktails (Well)
	getMenu('cp');	// Cocktails (Premium)
	getMenu('cc');	// Cocktails (Custom)
	getMenu('sv');	// Shooters (Vodka)
	getMenu('sr');	// Shooters (Rum)
	getMenu('sw');	// Shooters (Whiskey)
	getMenu('st');	// Shooters (Tequila)
	getMenu('so');	// Shooters (Other)
	getMenu('sd');	// Soft Drinks
	getMenu('f');	// Food
	getVars('cm');	// Variations*
		
	$pickup = get_user_meta( $id, 'comment_shortcuts', true );
	$open = get_user_meta( $id, 'jabber', true );
	$happy = get_user_meta( $id, 'yim', true );
	$tables = get_user_meta( $id, 'aim', true );
	$wifi = get_user_meta( $id, 'wifi', true );
	$stripe_client = $wpdb->get_row("select meta_value from wp_usermeta where meta_key='stripe_client' and user_id='$id' limit 1");
	echo '<div id="pickup" class="hidden">'.$pickup.'</div>';
	echo '<div id="open" class="hidden">'.$open.'</div>';
	echo '<div id="happy" class="hidden">'.$happy.'</div>';
	echo '<div id="tables" class="hidden">'.$tables.'</div>';
	echo '<div id="wifi" class="hidden">'.$wifi.'</div>';
	echo '<div id="client" class="hidden">'.$stripe_client->meta_value.'</div>';
	die();
}

// Check happy hour

add_action("wp_ajax_checkhappy", "checkhappy_do");
add_action("wp_ajax_nopriv_checkhappy", "checkhappy_do");
function checkhappy_do() {
	global $wpdb;
	$id = $_GET["barid"];
	$happy = get_user_meta( $id, 'yim', true );
	echo $happy;
	die();
}

// Empty cart

add_action("wp_ajax_empty", "empty_do");
add_action("wp_ajax_nopriv_empty", "empty_do");
function empty_do() {
	global $wpsc_cart;
	$wpsc_cart->empty_cart(false);
	die();
}

// Add to cart

add_action("wp_ajax_addcart", "addcart_do");
add_action("wp_ajax_nopriv_addcart", "addcart_do");
function addcart_do() {
	global $wpdb, $wpsc_cart, $wpsc_coupons;
	$id = $_GET["id"];
	$key = $_GET["key"];
	$count = $_GET["count"];
	$mixer = $_GET["mixer"];
	$category = $_GET["category"];
	if (isset($key)){
		if ($count == 0){
			$wpsc_cart->remove_item($key);
		} else {
			$default_parameters['quantity'] = $count;
			$default_parameters['custom_message'] = $mixer;
			$default_parameters['category'] = $category;
			$parameters = array_merge( $default_parameters );
			$wpsc_cart->edit_item($key, $parameters);
		}
	} else {
		$default_parameters['quantity'] = $count;
		$default_parameters['custom_message'] = $mixer;
		$default_parameters['category'] = $category;
		$parameters = array_merge( $default_parameters );	
		$wpsc_cart->set_item( $id, $parameters );
	}	
	while (wpsc_have_cart_items()):wpsc_the_cart_item();
		$name = $wpsc_cart->cart_item->product_name;
		$key = $wpsc_cart->current_cart_item;
		$qty = $wpsc_cart->cart_item->quantity;
		$mix = $wpsc_cart->cart_item->custom_message;
		$qty_minus = $qty - 1;
		$qty_plus = $qty + 1;
		$price = number_format((($wpsc_cart->cart_item->total_price) / ($wpsc_cart->cart_item->quantity)),2);
		$group = number_format($price*$qty,2);
		echo '<div class="product_row" key='.$id.'>';
			echo '<div class="cart-qty">'.$qty.'</div>';
			echo '<div class="cart-name">'.$name.'</div>';
			echo '<div class="cart-price">'.$price.'</div>';
			echo '<form id="minus-'.$key.'" class="qty-minus">';
				echo '<div class="update-minus" count="'.$qty_minus.'" key="'.$key.'"></div>';
			echo '</form>';
			echo '<form id="plus-'.$key.'" class="qty-plus">';
				echo '<div class="update-plus" count="'.$qty_plus.'" key="'.$key.'"></div>';
			echo '</form>';
			echo '<span class="group-price">$'.$group.'</span>';
		echo '</div><!--/product_row-->';
	endwhile;
	echo '<span class="hidden">';
		$subtotal = $wpsc_cart->calculate_subtotal();
		$tax = .10;
		echo '<div id="cart-subt">'.number_format($subtotal,2).'</div>';
		echo '<div id="cart-tax">'.number_format($tax,2).'</div>';
		echo '<div id="cart-disc">'.number_format(wpsc_coupon_amount(false),2).'</div>';
	echo '</span><!--/hidden-->';	
	die();
}

// Checkout page

add_action("wp_ajax_checkout", "checkout_do");
function checkout_do() {
	global $wpsc_cart, $wpdb, $wpsc_checkout, $wpsc_gateway;
	$wpsc_checkout = new wpsc_checkout();
	$wpsc_gateway = new wpsc_gateways(); ?>
	<form class="checkout-form" class="wpsc_checkout_forms">
		<div colspan="2" class="wpsc_gateway_container">
			<?php while (wpsc_have_gateways()) : wpsc_the_gateway();?>
   				<input type="hidden" value="<?php echo wpsc_gateway_internal_name();?>" name="custom_gateway" id="stripe">
				<?php if(wpsc_gateway_form_fields()):?>
					<?php echo wpsc_gateway_form_fields();?>
      			<?php endif; ?>
			<?php endwhile; ?>
		</div><!--/wpsc_gateway_container-->
		<div class="checkout-wrap">
			<input type="hidden" value="submit_checkout" name="wpsc_action">
			<input type="hidden" name="engravetext" id="engravetext">
			<input type="hidden" name="base_shipping" id="base_shipping">
			<input type="submit" value="Save Card" name="submit" class="make-purchase">
		</div><!--checkout-wrap-->
		<div class="powered"></div>
	</form><!--/checkout-form-->
	<?php
	die();
}

// Save Card

add_action("wp_ajax_savecard", "savecard_do");
add_action("wp_ajax_nopriv_savecard", "savecard_do");
function savecard_do() {
	global $wpdb, $current_user;
	$id = $current_user->ID;
	$email = $current_user->user_email;
	$fname = $current_user->first_name;
	$lname = $current_user->last_name;
	Stripe::setApiKey('sk_live_qcFFf5ISf79edaZLT2Yt586Y');

	$token = $_GET['stripe_token'];
	$last4 = $_GET['stripe_last4'];
	$name = $fname.' '.$lname;
	//error_log("token: $token last4: $last4 name: $name");
	try {
		$customer = Stripe_Customer::create(array(
			"card" => $token,
			"description" => $name,
			"email" => $email
		));
	    $response = 1;
	} catch (Exception $e) {    
	    $response = $e->getMessage();
	}

	update_user_meta($id,'stripe_last4',$last4);
	update_user_meta($id,'stripe_0',$customer->id);
	echo $response;
	die();
}

// Delete card

add_action("wp_ajax_deletecard", "deletecard_do");
add_action("wp_ajax_nopriv_deletecard", "deletecard_do");
function deletecard_do() {
	global $wpdb, $current_user;
	$id = $current_user->ID;
	$sql_erase = "delete from wp_usermeta where (meta_key='stripe_0' or meta_key='stripe_last4') and user_id='$id'";
	mysql_query($sql_erase);
	die();
}

// Charge Stripe user

add_action("wp_ajax_chargeuser", "chargeuser_do");
add_action("wp_ajax_nopriv_chargeuser", "chargeuser_do");
function chargeuser_do() {
	global $wpdb, $current_user, $wpsc_cart;
	$id = $current_user->ID;
	$email = $current_user->user_email;
	$fname = $current_user->first_name;
	$lname = $current_user->last_name;
	$name = $fname.' '.$lname;
	Stripe::setApiKey('sk_live_qcFFf5ISf79edaZLT2Yt586Y');

	$barid = $_GET['stripe_bar'];
	$token = $_GET['stripe_token'];
	$last4 = $_GET['stripe_last4'];
	$amount = $_GET['stripe_amount'];
	$name = $_GET['stripe_name'];
	$stripe_num = 'stripe_'.$barid;
	if (get_user_meta($barid,'jabber',true)==0) { echo -1; die(); };
	//if (($current_user->mobile_confirm)==0) { echo -2; die(); };
	$secret = $wpdb->get_row("select meta_value from wp_usermeta where user_id='$barid' and meta_key='stripe_secret' limit 1");
	$check_0 = $wpdb->get_row("select meta_value from wp_usermeta where user_id='$id' and meta_key='stripe_0' limit 1");
	$check_1 = $wpdb->get_row("select meta_value from wp_usermeta where user_id='$id' and meta_key='$stripe_num' limit 1");
	error_log("barid: $barid token: $token last4: $last4 amount: $amount name: $name stripe_num: $stripe_num secret: ".$secret->meta_key." check_0: ".$check_0->meta_key." check_1: ".$check_1->meta_key);
	if ($check_1 > 0) { // Does user have stripe account for this bar?
		try {
			Stripe_Charge::create(array(
				"amount" => $amount,
				"currency" => "usd",
				"customer" => $check_1->meta_value,
				"description" => $email
			),$secret->meta_value);
		    $response = 1;
		} catch (Exception $e) {    
		    $response = $e->getMessage();
		}
	} else {
		if ($check_0 > 0) { // Does user have a stripe account?
			if ($secret > 0) { // Does this bar have a stripe account?
				$cus = $wpdb->get_row("select meta_value from wp_usermeta where user_id='$id' and meta_key='stripe_0' limit 1");
				$customer = Stripe_Token::create(array(
					"customer" => $cus->meta_value
				),$secret->meta_value);
				$new_cus = Stripe_Customer::create(array(
					"card" => $customer->id,
					"description" => $name,
					"email" => $email
				),$secret->meta_value);
				update_user_meta($id,$stripe_num,$new_cus->id);
				try {
					Stripe_Charge::create(array(
						"amount" => $amount,
						"currency" => "usd",
						"customer" => $new_cus->id,
						"description" => $email
					),$secret->meta_value);
				    $response = 2;
				} catch (Exception $e) {    
				    $response = $e->getMessage();
				    $response = "on stripe_charge::create1";
				}
			} else {
				try {
					Stripe_Charge::create(array(
						"amount" => $amount,
						"currency" => "usd",
						"customer" => $check_0->meta_value,
						"description" => $email
					));
				    $response = 3;
				} catch (Exception $e) {    
				    $error = $e->getMessage();
				    $response = "on stripe_charge::create2";
				}
			}
		} else {
			$customer = Stripe_Customer::create(array(
				"card" => $token,
				"description" => $name,
				"email" => $email
			));
			update_user_meta($id,'stripe_last4',$last4);
			update_user_meta($id,'stripe_0',$customer->id);
		    $check_0 = $wpdb->get_row("select meta_value from wp_usermeta where user_id='$id' and meta_key='stripe_0' limit 1");
		    if ($secret > 0) {
				$cus = $wpdb->get_row("select meta_value from wp_usermeta where user_id='$id' and meta_key='stripe_0' limit 1");
				$customer = Stripe_Token::create(array(
					"customer" => $cus->meta_value
				),$secret->meta_value);
				$new_cus = Stripe_Customer::create(array(
					"card" => $customer->id,
					"description" => $name,
					"email" => $email
				),$secret->meta_value);
				update_user_meta($id,$stripe_num,$new_cus->id);
				try {
					Stripe_Charge::create(array(
						"amount" => $amount,
						"currency" => "usd",
						"customer" => $check_0->meta_value,
						"description" => $email
					),$secret->meta_value);
				    $response = 4;
				} catch (Exception $e) {    
				    $response = $e->getMessage();
				    $response = "on strip_charge::create 3";
				}		    
		    } else {
				try {
					Stripe_Charge::create(array(
						"amount" => $amount,
						"currency" => "usd",
						"customer" => $check_0->meta_value,
						"description" => $email
					));
				    $response = 5;
				} catch (Exception $e) {    
				    $response = $e->getMessage();
				    $response = "on strip_charge::create 4";
				}
		    }    
		}; 
	};
	echo $response;
	if ($response > 0) {
		$wpsc_checkout = new wpsc_checkout();
		$subtotal = $_GET['wpec_subtotal'];	
		$shipping = $_GET['wpec_shipping'];
		$tax = .10; $tables = 0;
		$promo = strtolower($_GET['wpec_promo']);
		if (empty($promo)) { $promo = ''; };
		$discount = $_GET['wpec_discount'];
		if (empty($discount)) {$discount = '';};
		$total = $_GET['wpec_total'];
		$seating = $_GET['wpec_seating'];
		if (empty($seating)) { $seating = 0; }
		$session = (mt_rand(100,999).time());
		$transid = $trans->id;
		$created = $trans->created;
		$fee = ($trans->fee)/100;
		$wpdb->insert('wp_wpsc_purchase_logs', array(
			'engravetext' => $barid,
			'user_ID' => $id,
			'totalprice' => $total,
			'base_shipping' => $shipping,
			'wpec_taxes_total' => $tax,
			'statusno' => '0',
			'sessionid' => $session,
			'transactid' => $transid,
			'authcode' => $created,
			'processed' => 3,
			'date' => time(),
			'gateway' => 'stripe',
			'track_id' => $fee,
			'shipping_region' => $seating,
			'discount_value' => $discount,
			'discount_data' => $promo
		));
		$purchase_id = $wpdb->get_row("select id from wp_wpsc_purchase_logs where user_id='$id' order by id desc limit 1");
		$order_id = $purchase_id->id;
		while (wpsc_have_cart_items()):wpsc_the_cart_item();
			$name = $wpsc_cart->cart_item->product_name;
			$qty = $wpsc_cart->cart_item->quantity;
			$price = number_format((($wpsc_cart->cart_item->total_price) / ($wpsc_cart->cart_item->quantity)),2);
			$mix = $wpsc_cart->cart_item->custom_message;
			$category = $wpsc_cart->cart_item->category;
			if (substr($category,0,2) == 'cc'){
				$name = $name.' ('.$mix.')';
			}
			$wpdb->insert('wp_wpsc_cart_contents', array(
				'name' => $name,
				'category' => $category,
				'quantity' => $qty,
				'price' => $price,
				'prodid' => $wpsc_cart->cart_item->product_id,
				'purchaseid' => $order_id
			));
		endwhile;
	};
	die();
}

// After purchase

add_action("wp_ajax_afterpurchase", "afterpurchase_do");
add_action("wp_ajax_nopriv_afterpurchase", "afterpurchase_do");
function afterpurchase_do($id) {
	global $wpdb;
	$barid = $_GET['id'];
    $website_url = get_site_url();
    function post_to_url($url, $data) {
        $fields = '';
        foreach ($data as $key => $value) {
            $fields .= $key . '=' . $value . '&';
        }
        rtrim($fields, '&');
        $post = curl_init();
        curl_setopt($post, CURLOPT_URL, $url);
        curl_setopt($post, CURLOPT_POST, count($data));
        curl_setopt($post, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($post, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($post);
        curl_close($post);
        return $result;
    }
    $piggy = post_to_url($website_url."/wp-admin/admin-ajax.php?action=getorders&barid=".$barid, array());
    $data = array(
        'message' => $piggy,
        'id' => $barid
    );
    post_to_url("http://srvd-node.herokuapp.com", $data);

	$date = date('F jS, Y',time());
	$email = $current_user->user_email;	
	$bar_phone = get_the_author_meta('user_login',$barid);
	$bar_fname = get_usermeta($barid,'first_name');
	$bar_addy = get_usermeta($barid,'description');
	$bar_phone = preg_replace('~(\d{3})[^\d]*(\d{3})[^\d]*(\d{4})$~','$1-$2-$3',$bar_phone);
	
	$message='<link type="text/css" rel="stylesheet" href="http://cdn.flowtab.mobi/css/my-receipt.css">';
	
	$message.='<div id="info-left">';
		$message.='<p class="bar-info name">'.$bar_fname.'</p>';
		$message.='<p class="bar-info">'.str_replace('>','</p><p class="bar-info">',$bar_addy).'</p>';
		$message.='<p class="bar-info">'.$bar_phone.'</p>';
	$message.='</div>';
	
	$order_details = $wpdb->get_results("select * from wp_wpsc_cart_contents where purchaseid = '$order_id'");
	
	$order_message='<div id="info-right">';
		$order_message='<p class="bar-info date">'.$date.'</p>';
		$order_message='<p class="bar-info order-id">Order #'.$order_id.'</p>';
	$order_message.='</div>';
	
	$order_message.='<div id="item-wrap">';
		$order_message.='<div class="item-title qty">Qty</div>';
		$order_message.='<div class="item-title product">Product</div>';
		$order_message.='<div class="item-title price">Price</div>';
		foreach ($order_details as $key => $order) {
			$sub_total += ($order->quantity * $order->price);
			$order_message.='<div class="item-row">';
				$order_message.='<div class="item-list qty">'.$order->quantity.'</div>';
				$order_message.='<div class="item-list product">'.$order->name.'</div>';
				$order_message.='<div class="item-list price">$'.money_format('%.2n',$order->price).'</div>';
			$order_message.='</div>';
		}
		$gratuity=($order->totalprice+$order->discount_value)-($sub_total+$order->wpec_taxes_total);
	$order_message.='</div>';
	
	$order_message.='<div id="totals-wrap">';
	
		$order_message.='<div class="item-totals">';
			$order_message.='<div class="total-left">Subtotal:</div>';
			$order_message.='<div class="total-right">$'.money_format('%.2n',$subtotal).'</div>';
		$order_message.='</div>';
	
		$order_message.='<div class="item-totals">';
			$order_message.='<div class="total-left">Processing:</div>';
			$order_message.='<div class="total-right">$'.money_format('%.2n',$tax).'</div>';
		$order_message.='</div>';

		if ($discount > 0) {
			$order_message.='<div class="item-totals">';
				$order_message.='<div class="total-left green">Discount:</div>';
				$order_message.='<div class="total-right green">($'.money_format('%.2n',$discount).')</div>';
			$order_message.='</div>';
		};
	
		$order_message.='<div class="item-totals">';
			$order_message.='<div class="total-left">Gratuity:</div>';
			$order_message.='<div class="total-right">$'.money_format('%.2n',$shipping).'</div>';
		$order_message.='</div>';
	
		$order_message.='<div class="item-totals">';
			$order_message.='<div class="total-left total">Total:</div>';
			$order_message.='<div class="total-right total">$'.money_format('%.2n',$total).'</div>';
		$order_message.='</div><br/>';
	
	$order_message.='</div>';

	//echo $message.$order_message;	
	//wp_mail($email,'Flowtab Sales Receipt',$message.$order_message);
	//wp_mail('kyle@nick.hesling.com:81','Flowtab Sales Receipt',$message.$order_message);

	die();
}
?>

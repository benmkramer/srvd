<?php
/*
Plugin Name: Flowtab API (Admin)
Plugin URI: http://nick.hesling.com:81
Description: A nifty API plugin for Flowtab's admin functions.
Version: 1.02
Author: Alex Kouznetsov & Kyle Hill
Author URI: http://nick.hesling.com:81
License: Flowtab
*/

// Add Google Keys

add_action("wp_ajax_googlekey", "googlekey_do");
add_action("wp_ajax_nopriv_googlekey", "googlekey_do");
function googlekey_do() {
	global $wpdb;
	$id = $_GET["id"];
	$key = $_GET["key"];
	$sql_erase = "delete from wp_usermeta where meta_key='google_key' and user_id='$id'";
	mysql_query($sql_erase);
	$wpdb->insert('wp_usermeta', array(
		'user_id' => $id,
    	'meta_key' => 'google_key',
    	'meta_value' => $key
    ));
    die();
}

// Add WiFi

add_action("wp_ajax_wifi", "wifi_do");
add_action("wp_ajax_nopriv_wifi", "wifi_do");
function wifi_do() {
	global $wpdb;
	$id = $_GET["id"];
	$wifi = 1;
	$sql_erase = "delete from wp_usermeta where meta_key='wifi' and user_id='$id'";
	mysql_query($sql_erase);
	$wpdb->insert('wp_usermeta', array(
		'user_id' => $id,
    	'meta_key' => 'wifi',
    	'meta_value' => $wifi
    ));
    die();
}

// Add Pickup Area

add_action("wp_ajax_pickuparea", "pickuparea_do");
add_action("wp_ajax_nopriv_pickuparea", "pickuparea_do");
function pickuparea_do() {
	global $wpdb;
	$id = $_GET["id"];
	$area = $_GET["area"];
	$sql_erase = "delete from wp_usermeta where meta_key='pickup_area'";
	mysql_query($sql_erase);
	$wpdb->insert('wp_usermeta', array(
		'user_id' => $id,
    	'meta_key' => 'pickup_area',
    	'meta_value' => $area
    ));
    die();
}

// Stripe webhook

add_action("wp_ajax_webhook", "webhook_do");
add_action("wp_ajax_nopriv_webhook", "webhook_do");
function webhook_do() {
	if ((get_site_url() == 'http://beta.flowtab.mobi') || (get_site_url() == 'http://nick.hesling.com:81'))  {	
		Stripe::setApiKey('sk_test_CXDxDY628jQSPCT98bCKJDRu');
	} else {
		Stripe::setApiKey('sk_test_CXDxDY628jQSPCT98bCKJDRu');
	}
	$body = @file_get_contents('php://input');
	wp_mail("npascull@gmail.com","Stripe",$body);
	die();
}

// Add Stripe merchant

add_action("wp_ajax_addmerchant", "addmerchant_do");
add_action("wp_ajax_nopriv_addmerchant", "addmerchant_do");
function addmerchant_do() {
	if(isset($_SERVER['HTTPS'])){
	    $redirect = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	    header("Location: $redirect");
	}
	global $wpdb;
	if ((get_site_url() == 'http://beta.flowtab.mobi') || (get_site_url() == 'http://nick.hesling.com:81')) {	
		$client_id = 'ca_0sP2jxt5P1pIKUQXiCd2qEqvSqEDGrt8';
		//$api_key = 'sk_0DqYGNuhl6leulVBoXJ4iEYFFJTOu';
		//$api_key = 'sk_live_qcFFf5ISf79edaZLT2Yt586Y';
		$api_key = 'sk_test_CXDxDY628jQSPCT98bCKJDRu';
	} else {
		$client_id = 'ca_0sP278wdXCOTkFYBub5uNQZC9ZPqdAkq';
		//$api_key = 'sk_test_ZGW1zywBukd7NLy0MQa7t5IE';
		$api_key = 'sk_test_CXDxDY628jQSPCT98bCKJDRu';
	}
	$token_uri = 'https://connect.stripe.com/oauth/token';
	$authorize_uri = 'https://connect.stripe.com/oauth/authorize';
	$code = $_GET['code'];
	$id = $_GET['state'];
	$auth_header = array(
		'Authorization: Bearer ' . $api_key
	);
	$token_request_body = array(
		'grant_type' => 'authorization_code',
		'client_id' => $client_id,
		'code' => $code,
	);
	$req = curl_init($token_uri);
	curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($req, CURLOPT_POST, true );
	curl_setopt($req, CURLOPT_HTTPHEADER, $auth_header);
	curl_setopt($req, CURLOPT_POSTFIELDS, http_build_query($token_request_body));
	$respCode = curl_getinfo($req, CURLINFO_HTTP_CODE);
	$resp = json_decode(curl_exec($req), true);
	curl_close($req);
	$stripe_user = $resp['access_token'];
	$stripe_client = $resp['stripe_user_id'];
	$stripe_key = $resp['stripe_publishable_key'];
	echo $stripe_user.'<br/>';
	echo $stripe_client.'<br/>';
	echo $stripe_key.'<br/>';
	// TODO: Prevent from re-writing data more than once...
	$sql_erase = "delete from wp_usermeta where meta_key='stripe_secret' or meta_key='stripe_client' or meta_key='stripe_publish' or meta_key='stripe_state' and user_id='$id'";
	mysql_query($sql_erase);
	update_user_meta($id, 'stripe_secret', $stripe_user);
	update_user_meta($id, 'stripe_client', $stripe_client);
	update_user_meta($id, 'stripe_publish', $stripe_key);
    echo '<script type="text/javascript">window.location = "http://nick.hesling.com:81/"</script>';
	die();
}

// Twilio Response

add_action("wp_ajax_twilio", "twilio_do");
add_action("wp_ajax_nopriv_twilio", "twilio_do");
function twilio_do() {
    header("content-type: text/xml");
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	?>
		<Response>
		    <Sms>Click for your photos: www.fb.com/flowtab
Click for a free drink: nick.hesling.com:81</Sms>		    
		</Response>
	<?php
    die();
}

// Sync Menu

add_action("wp_ajax_syncmenu", "syncmenu_do");
add_action("wp_ajax_nopriv_syncmenu", "syncmenu_do");
function syncmenu_do() {
	global $wpdb, $current_user;
	$id = $_GET["id"];
	$sql_erase = "delete from wp_posts where post_author='$id'";
	mysql_query($sql_erase);
    $key = get_usermeta($id,'google_key');
    $url = "http://spreadsheets.google.com/feeds/cells/$key/1/public/values";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $google_sheet = curl_exec($ch);
    curl_close($ch);
	$doc = new DOMDocument();
	$doc->loadXML($google_sheet);
	$cells = $doc->getElementsByTagName("cell");
	$col = 0;
	foreach ($cells as $cell) {
		$col = $cell->getAttribute("col");
		$row = $cell->getAttribute("row");
		if ($col == 1) {
			$post_author = $cell->nodeValue;
		}
		if ($col == 2) {
			$category = $cell->nodeValue;
		}
		if ($col == 3) {
			$post_title = $cell->nodeValue;
		}
		if ($col == 4) {
			$post_content = $cell->nodeValue;
		}
		if ($col == 5) {
			$price = $cell->nodeValue;
		}
		if ($col == 6) {
			$sale_price = $cell->nodeValue;
		}
		if (($col == 6) && ($row > 1)) {
			$wpdb->insert('wp_posts', array(
		    	'post_author' => $id,
		    	'category' => $category,
		    	'post_title' => $post_title,
		    	'post_content' => $post_content,
		    	'price' => $price,
		    	'sale_price' => $sale_price
		    ));
		}
	}
	$sql_erase = "delete from wp_postmeta where meta_key='_wpsc_special_price' or meta_key='_wpsc_price' and post_id='$id'";
	mysql_query($sql_erase);
	$products = $wpdb->get_results("select * from wp_posts where post_author='$id'");
	foreach ($products as $product) {
		if (!empty($product)) {
 			$obj_id = $product->ID;
			$item_price = $wpdb->get_row("select price from wp_posts where ID = '$obj_id'");
			$item_sale = $wpdb->get_row("select sale_price from wp_posts where ID = '$obj_id'");
			$wpsc_price = $item_price->price;
			$wpsc_sale = $item_sale->sale_price;
			$wpdb->insert('wp_postmeta', array(
				'post_id' => $obj_id,
		        'meta_key' => "_wpsc_price",
		        'meta_value' => $wpsc_price
		    ));
			$wpdb->insert('wp_postmeta', array(
				'post_id' => $obj_id,
		        'meta_key' => "_wpsc_special_price",
		        'meta_value' => $wpsc_sale
		    ));
   
		};
	}
    die();
}

// Sync Menus (All)

add_action("wp_ajax_syncmenus", "syncmenus_do");
add_action("wp_ajax_nopriv_syncmenus", "syncmenus_do");
function syncmenus_do() {
	global $wpdb, $current_user;
	$id = $_GET["id"];
	$menus = $wpdb->get_results("select user_id from wp_usermeta where meta_key='google_key'");
	foreach ($menus as $menu) {
		$id = $menu->user_id;	
		$sql_erase = "delete from wp_posts where post_author='$id'";
		mysql_query($sql_erase);
	    $key = get_usermeta($id,'google_key');
	    $url = "http://spreadsheets.google.com/feeds/cells/$key/1/public/values";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	    $google_sheet = curl_exec($ch);
	    curl_close($ch);
		$doc = new DOMDocument();
		$doc->loadXML($google_sheet); 
		$cells = $doc->getElementsByTagName("cell");
		$col = 0;
		foreach ($cells as $cell) {
			$col = $cell->getAttribute("col");
			$row = $cell->getAttribute("row");
			if ($col == 1) {
				$post_author = $cell->nodeValue;
			}
			if ($col == 2) {
				$category = $cell->nodeValue;
			}
			if ($col == 3) {
				$post_title = $cell->nodeValue;
			}
			if ($col == 4) {
				$post_content = $cell->nodeValue;
			}
			if ($col == 5) {
				$price = $cell->nodeValue;
			}
			if ($col == 6) {
				$sale_price = $cell->nodeValue;
			}
			if (($col == 6) && ($row > 1)) {
				$wpdb->insert('wp_posts', array(
			    	'post_author' => $id,
			    	'category' => $category,
			    	'post_title' => $post_title,
			    	'post_content' => $post_content,
			    	'price' => $price,
			    	'sale_price' => $sale_price
			    ));
			}
		}
		$sql_erase = "delete from wp_postmeta where meta_key='_wpsc_special_price' or meta_key='_wpsc_price' and post_id='$id'";
		mysql_query($sql_erase);
		$products = $wpdb->get_results("select * from wp_posts where post_author='$id'");
		foreach ($products as $product) {
			if (!empty($product)) {
	 			$obj_id = $product->ID;
				$item_price = $wpdb->get_row("select price from wp_posts where ID = '$obj_id'");
				$item_sale = $wpdb->get_row("select sale_price from wp_posts where ID = '$obj_id'");
				$wpsc_price = $item_price->price;
				$wpsc_sale = $item_sale->sale_price;
				$wpdb->insert('wp_postmeta', array(
					'post_id' => $obj_id,
			        'meta_key' => "_wpsc_price",
			        'meta_value' => $wpsc_price
			    ));
				$wpdb->insert('wp_postmeta', array(
					'post_id' => $obj_id,
			        'meta_key' => "_wpsc_special_price",
			        'meta_value' => $wpsc_sale
			    ));
	   
			};
		}
	}
    die();
}

// Download Menu as CSV

add_action("wp_ajax_excel", "excel_do");
function excel_do() {
	global $wpdb;
	//$cat_id = $_GET["category"];
	$cat_id = 'b-3';
	$products = $wpdb->get_results("select * from wp_posts where category = '$cat_id'");	
	$columns = mysql_query("show columns from wp_posts");
	$i = 0;
	if (mysql_num_rows($columns) > 0) {
		while ($row = mysql_fetch_assoc($columns)) {
			$csv_output .= $row['Field'].",";
			$i++;
		};
	};
	$csv_output .= "\n";
	$values = mysql_query("select * from wp_posts where category = '$cat_id'");
	while ($rowr = mysql_fetch_row($values)) {
		for ($j=0;$j<$i;$j++) {
			$csv_output .= $rowr[$j].", ";
		}
		$csv_output .= "\n";
	};
	$filename = $cat_id;
	header("Content-type: application/vnd.ms-excel");
	header("Content-disposition: csv" . date("Y-m-d") . ".csv");
	header("Content-disposition: filename=".$filename.".csv");
	echo $csv_output;
	print $csv_output;
    die();
}

// Add Affiliate

add_action("wp_ajax_affiliate", "affiliate_do");
function affiliate_do() {
    global $wpdb, $current_user;
    // Todo: Validate this person is Mike/Kyle
    $id = $_GET["id"];
    $code = $_GET["code"];
    $code = strtolower($code);
    $orders = $wpdb->get_results("select * from wp_wpsc_purchase_logs where discount_data='$code'");
    $earnings = count($orders)*5 + .25;
	$sql_erase = "delete from wp_usermeta where meta_key='affiliate_code' or meta_key='affiliate_orders'  or meta_key='affiliate_earnings' and user_id='$id'";
	mysql_query($sql_erase);
    $wpdb->insert('wp_usermeta', array(
        'user_id' => $id,
        'meta_key' => "affiliate_code",
        'meta_value' => $code
    ));
    $wpdb->insert('wp_usermeta', array(
        'user_id' => $id,
        'meta_key' => "affiliate_orders",
        'meta_value' => count($orders)
    ));
    $wpdb->insert('wp_usermeta', array(
        'user_id' => $id,
        'meta_key' => "affiliate_earnings",
        'meta_value' => $earnings
    ));
    echo 'Code: '.$code.'<br/>';
    echo 'Orders: '.count($orders).'<br/>';
    echo 'Earnings: '.$earnings.'<br/>';
	die();
}

// Send Bug

add_action("wp_ajax_bugsuggestion", "bugsuggestion_do");
function bugsuggestion_do() {
	global $wpdb, $current_user;
	$email = $current_user->user_email;
	$fname = $current_user->first_name;
	$lname = $current_user->last_name;
	$phone = $current_user->user_login;
	$message = $_GET["message"];
	$body = ('Name: '.$fname.' '.$lname.'<br/>Email: '.$email.'<br/>Phone: '.$phone.'<br/><br/>'.$message);
	wp_mail('support@nick.hesling.com:81','Flowtab Bug/inSuggestion',$body);
    die();
}

// Send Email

add_action("wp_ajax_newaffiliate", "newaffiliate_do");
function newaffiliate_do() {
	global $wpdb, $current_user;
	$email = $current_user->user_email;
	$fname = $current_user->first_name;
	$lname = $current_user->last_name;
	$phone = $current_user->user_login;
	$body = ('Name: '.$fname.' '.$lname.'<br/>Email: '.$email.'<br/>Phone: '.$phone);
	wp_mail('affiliates@nick.hesling.com:81','New Flowtab Affiliate',$body);
    die();
}

// Daily Report

add_action("wp_ajax_dailyreport", "dailyreport_do");
add_action("wp_ajax_nopriv_dailyreport", "dailyreport_do");

function dailyreport_do() {
	// Usage: /wp-admin/admin-ajax.php?action=dailyreport
    date_default_timezone_set('America/Los_Angeles');
    global $wpdb;
    $end_time = strtotime(date('Y-m-d').'04:00:00');
    $start_time = strtotime('-1 day', $end_time);
    // ('.date('l, M jS Y',$start_time).')
    $lastest_order = $wpdb->get_row("select * from wp_wpsc_purchase_logs order by id desc limit 1");
    $message = '';
    $bars = $wpdb->get_results("select distinct engravetext from wp_wpsc_purchase_logs");
    $bars = array_filter($bars);
    $yesterday = $wpdb->get_row("select option_value from wp_options where option_name='user_count'");
    $yes = $yesterday->option_value;
    $users = $wpdb->get_row("select ID from wp_users order by ID desc limit 1");
    $count = $users->ID;
	$sql_erase = "delete from wp_options where option_name='user_count'";
	mysql_query($sql_erase);
	$wpdb->insert('wp_options', array(
		'option_name' => 'user_count',
		'option_value' => $count
	));
	$today = $count - $yes;
	$sales = $wpdb->get_results("select distinct ID from wp_wpsc_purchase_logs");	
	$amount = $wpdb->get_row("select sum(totalprice) as total from wp_wpsc_purchase_logs");
	$order_yesterday = $wpdb->get_results("select * from wp_wpsc_purchase_logs where processed in (3,5) and date >=$start_time");
	$average = ($amount->total) / (count($sales));
    $message_head .= '<p>We had <strong>'.$today.'</strong> new users yesterday, giving us <strong>'.$count.'</strong> total users.</p><p>We had <strong>'.count($order_yesterday).'</strong> new orders yesterday, giving us <strong>'.count($sales).'</strong> total orders.</p>';
    echo $message_head;
    $sales = 0;
    foreach ($bars as $bar) {
        if (!empty($bar->engravetext)) {
            $user = get_user_meta($bar->engravetext, 'first_name', true);
            $total = 0;
            $tax = 0;
            $gratuity = 0;
            $discount = 0;
            $order_in_day = $wpdb->get_results("select * from wp_wpsc_purchase_logs where processed in (3,5) and engravetext='{$bar->engravetext}' and date >=$start_time and date <=$end_time");
            $orders = count($order_in_day);
            foreach ($order_in_day as $order) {
                $total += $order->totalprice;
                $tax += $order->wpec_taxes_total;
                $gratuity += $order->base_shipping;
                $discount += $order->discount_value;              
                $total = number_format($total,2);
                $tax = number_format($tax,2);
                $gratuity = number_format($gratuity,2);
                $discount = number_format($discount,2);
                $total_sales = number_format(($total - $gratuity - $tax + $discount),2);
                $total_deposit = number_format(( $total_sales + $gratuity),2);
            }
            $total = number_format($total + $discount, 2);
            $barid = $bar->engravetext;
            if ($total > 0) {
            	$sales = $sales + 1;
                $sale = number_format($total - ($tax + $gratuity), 2);
                $ven = number_format($total * 0.025, 2);
                $final = number_format($total - $ven, 2);
                $message .= '<li><strong>'.$user.' (#'.$barid.'): </strong>'.$orders.' orders | $'.$total_sales.' sales | $'.$gratuity.' tips | $'.$total_deposit.' deposit</li>';
            } else {
                //$message .= "<p>&#149 ".$user." (".$barid."): <em>No Sales</em></p>";
            }
        }
    }
	$message .= '<p>We\'ve processed <strong>$'.number_format($amount->total,2).'</strong> in total sales, with <strong>$'.number_format($average,2).'</strong> average.</p>';
    $subject = 'Flowtab Metrics ('.date('l').')';
    echo $message;
    $email = 'marinahq@nick.hesling.com:81';
    wp_mail($email, $subject, $header . $message_head . $message);
    die();
}

?>

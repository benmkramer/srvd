<?php
/*
Plugin Name: Srvd API (iPad)
Plugin URI: http://srvdme.com
Description: A nifty API plugin for Srvd's ipad app.
Version: 1.01
Author: Nick Pascullo
Author URI: http://srvdme.com
License: Srvd
*/

// Batchout

add_action("wp_ajax_batchout", "batchout_do");
add_action("wp_ajax_nopriv_batchout", "batchout_do");
function batchout_do() {
    global $wpdb;
    $bar_id = $_GET['bar'];
    $wpdb->query("update wp_wpsc_purchase_logs set processed=5 where processed in (2,3) and engravetext=$bar_id");
	// Now refresh orders
    global $current_user;
    $barid = $bar_id;
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
    $piggy = post_to_url($website_url."/wp-admin/admin-ajax.php?action=piggyorders1&bar=".$barid, array());
    $data = array(
        'message' => $piggy,
        'id' => $barid
    );
    post_to_url("http://srvd-node.herokuapp.com", $data);
    echo 'Sending order notification to bar #'.$barid;
    die();
}

// Check password

add_action("wp_ajax_checkpassword", "checkpassword_do");
add_action("wp_ajax_nopriv_checkpassword", "checkpassword_do");

function checkpassword_do() {
    global $wpdb;
    $bar = $_GET['bar'];
    $pass = $_GET['pswd'];
    $user = $wpdb->get_row("select * from wp_users where id=$bar");
    //var_dump($user);
    if (user_pass_ok($user->user_login, $pass))
    	echo 1;
    else
        echo 0;
    //user_pass_ok
    die();
}

// PMX report

add_action("wp_ajax_pmxreport", "pmxreport_do");
add_action("wp_ajax_nopriv_pmxreport", "pmxreport_do");

function pmxreport_do() {
    global $wpdb, $current_user;
    $id = $current_user->ID;
    $email = $current_user->user_email;
    $admin = $_GET['admin'];
    if ($admin == 1) {
    	$email = 'kyle@srvdme.com';
    }
    $name = $current_user->first_name;
    $orders = $wpdb->get_results("select * from wp_wpsc_purchase_logs where engravetext='$id' and (processed=3 or processed=5) order by id desc");
    $header = array('Date', 'Time', 'Bar', 'Order', 'Status', 'User', 'Qty', 'Category', 'Product', 'Price', 'Sub-Total', 'Gratuity', 'Total');
    $body[] = $header;
    date_default_timezone_set('America/Los_Angeles');    
    foreach ($orders as $order) {
    	$order_id = $order->id;
		$items = $wpdb->get_results("select * from wp_wpsc_cart_contents where purchaseid='$order_id'");
	    $categories = array(
	        'b' => 'Beer',
	        'w' => 'Wine',
	        'cf' => 'Cocktails (Featured)',
	        'cw' => 'Cocktails (Well)',
	        'cp' => 'Cocktails (Premium)',
	        'cc' => 'Cocktails (Custom)',
			'sv' => 'Shooters (Vodka)',
			'sr' => 'Shooters (Rum)',
			'sw' => 'Shooters (Whiskey)',
			'st' => 'Shooters (Tequila)',
			'so' => 'Shooters (Other)',
			'sd' => 'Soft Drinks',
			'f' => 'Food'
	    );
		foreach ($items as $item) {	
			$category = str_replace('-'.$id,'',$item->category);
			$category = $categories[$category];
			$subtotal = ($item->price) * ($item->quantity);
			$total = ($order->totalprice) + ($order->discount_value) - ($order->wpec_taxes_total);
	        $row = array(
	            date('m/d/Y', $order->date),
	            date('g:ia', $order->date),
	            $order->engravetext,
	            $order_id,
	            'Closed',
	           	$order->user_ID,
	            $item->quantity,
	            $category,
	            $item->name,
	            number_format($item->price,2),
	            number_format($subtotal,2),
	            number_format($order->base_shipping,2),
	            number_format($total,2),
	        );
	        $body[] = $row;
		}
    }
    $objPHPExcel = new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->getActiveSheet()->fromArray($body, null, 'A1');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$file_name = $name.'('.$id.').xls';
	$objWriter->save(dirname(__FILE__).'/excel/'.$file_name);
    $subject = 'Srvd Sales Report';
    $message = '<p>Attached is a Srvd Sales Report for <strong>'.$name.'</strong>.</p><p>The Srvd Team</p>';
	wp_mail($email,$subject,$message,NULL,dirname(__FILE__).'/excel/'.$file_name);
    die();
}

// PMX page

add_action("wp_ajax_pmxpage", "pmxpage_do");
add_action("wp_ajax_nopriv_pmxpage", "pmxpage_do");

function pmxpage_do() {
    global $wpdb, $current_user;
    $id = $current_user->ID;
    $email =  $current_user->user_email;
    $name = $current_user->first_name;
	$end_date = time();
	$start_date = strtotime(date('Y-m-d') . '12:00:00'); // Shift starts at 4AM
	if ( $end_date < $start_date ) {
		$pastMidnight = true;
    	$start_date = strtotime('-1 day', $start_date ); // Shift starts at 4AM yesterday
    }
    $orders = $wpdb->get_results("select * from wp_wpsc_purchase_logs where engravetext='$id' and (processed=3 or processed=5) and date > '$start_date' order by id desc");
	if (count($orders) == 0) {
		echo '<table id="pmx-box">';
			echo '<div class="pmx-blank">No orders yet today…</div>';
		echo '</table>';
		die();
	}
    $header = array('Time', 'Order', 'Qty', 'Product', 'Price', 'Gratuity', 'Total');
    $body[] = $header;
    date_default_timezone_set('America/New_York');
    foreach ($orders as $order) {
    	$order_id = $order->id;
		$items = $wpdb->get_results("select * from wp_wpsc_cart_contents where purchaseid='$order_id'");
		foreach ($items as $item) {
			$subtotal = ($item->price) * ($item->quantity);
			$total = ($order->totalprice) + ($order->discount_value) - ($order->wpec_taxes_total);
	        $row = array(
	            date('g:ia', $order->date),
	            $order_id,
	            $item->quantity,
	            $item->name,
	            '$'.number_format($item->price,2),
	            '$'.number_format($order->base_shipping,2),
	            '$'.number_format($total,2)
	        );
	        $body[] = $row;
		}
    }
    $count = 0;
	echo '<table id="pmx-box">';
		echo '<thead>';
	    	echo '<tr id="pmx-head">';
	        	foreach ($header as $head) {
	        		echo '<th>'.$head.'</th>';
	       		};
	    	echo '</tr>';
	    echo '</thead><tbody>';
	        foreach ($body as $row) {
	        $count = $count + 1;
		        if ($count > 1) {
		    		echo '<tr>';
		        		foreach ($row as $key => $val) {
		        			echo '<td>';
		        				echo $val;
		        			echo '</td>';
		        		};
		        	echo '</tr>';
		        };
	    	};
	    echo '</tbody>';
	echo '</table>';
    die();
}

// Status

add_action("wp_ajax_status", "status_do");
add_action("wp_ajax_nopriv_status", "status_do");

function status_do() {
    $id = $_GET["id"];
    $status = $_GET["status"];
    $etime = $_GET["etime"];
    if ($etime) {
        $sql = "UPDATE wp_wpsc_purchase_logs SET processed= '" . $status . "', track_id='" . $etime . "' WHERE id='" . $id . "'";
    } else {
        $sql = "UPDATE wp_wpsc_purchase_logs SET processed= '" . $status . "', track_id = ROUND( (UNIX_TIMESTAMP() - date )/60, 2) WHERE id='" . $id . "'";
    }
    echo $status;
    mysql_query($sql);
    die();
}

// Check coupon

add_action("wp_ajax_checkcoupon", "checkcoupon_do");
add_action("wp_ajax_nopriv_checkcoupon", "checkcoupon_do");

function checkcoupon_do() {
    // Usage: /wp-admin/admin-ajax.php?action=checkcoupon
    global $wpdb;
    global $current_user;
    $coupon = $_GET["coupon"];
    if ( $coupon == '$woop' ) {
		echo 1; die();
    }
    $user_id = $current_user->ID;
    $codes = $wpdb->get_row("select * from wp_wpsc_coupon_codes where coupon_code = '$coupon' ");
    $usage = $wpdb->get_results("select * from wp_wpsc_purchase_logs where discount_data = '$coupon' and user_ID = '$user_id' and processed in (2,3,5) ");    
    if ( (count($codes) > 0) && (count($usage) == 0) ) {
		echo 1;
    } else {
    	echo 0;
    }
    die();
}

// Auth confirm

add_action("wp_ajax_authconfirm", "authconfirm_do");
add_action("wp_ajax_nopriv_authconfirm", "authconfirm_do");

function authconfirm_do() {
    // Usage: /wp-admin/admin-ajax.php?action=authconfirm&auth=1
    global $wpdb;
    global $current_user;
    $id = $current_user->ID;
    $auth = $_GET["auth"];
    $check = $wpdb->get_row("select * from wp_usermeta where user_id = $id and meta_key= 'auth_confirm'");
    if (count($check) == 0) {
        $wpdb->insert('wp_usermeta', array(
            'user_id' => $id,
            'meta_key' => "auth_confirm",
            'meta_value' => $auth
        ));
    } else {
    	echo "Hello";
    	$sql = "update wp_usermeta set meta_value= $auth where user_id = $id and meta_key= 'auth_confirm' ";
    	mysql_query($sql); 
    }
    die();
}

// Order made

add_action("wp_ajax_ordermade", "ordermade_do");
add_action("wp_ajax_nopriv_ordermade", "ordermade_do");

function ordermade_do() {
    $id = $_GET["id"];
    $status = $_GET["status"];
    $sql = "UPDATE wp_wpsc_purchase_logs SET statusno= '" . $status . "' WHERE id='" . $id . "'";
    echo $status;
    mysql_query($sql);
    die();
}

// Order printed

add_action("wp_ajax_orderprinted", "orderprinted_do");
add_action("wp_ajax_nopriv_orderprinted", "orderprinted_do");

function orderprinted_do() {
    $id = $_GET["id"];
    $status = $_GET["status"];
    $sql = "UPDATE wp_wpsc_purchase_logs SET find_us= '" . $status . "' WHERE id='" . $id . "'";
    echo $status;
    mysql_query($sql);
    die();
}

// Node orders

add_action("wp_ajax_nodeorders", "nodeorders_do");
add_action("wp_ajax_nopriv_nodeorders", "nodeorders_do");

function nodeorders_do() {
    $barid = $_GET['barid'];
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
    echo $piggy;
    die();
}

// Add coupon

add_action("wp_ajax_addcoupon", "addcoupon_do");
add_action("wp_ajax_nopriv_addcoupon", "addcoupon_do");

function addcoupon_do() {
    global $wpdb;
    //Declare the default
    $is_percentage = 0;
    $use_once = 1;
    $is_used = 0;
    $active = 1;
    $every_product = 0;
    $start = date('Y-m-d H:i:s', time());
    $end = date('Y-m-d H:i:s', strtotime('+1 year'));
    $condition = 'a:1:{i:0;a:3:{s:8:"property";s:15:"subtotal_amount";s:5:"logic";s:7:"greater";s:5:"value";s:4:"4.99";}}';
    //Get the $_GET params
    $name = $_GET['name'];
    $amount = $_GET['amount'];
    $wpdb->insert('wp_wpsc_coupon_codes', array(
        'coupon_code' => $name,
        'value' => $amount,
        'is-percentage' => $is_percentage,
        'use-once' => $use_once,
        'is-used' => $is_used,
        'active' => $active,
        'every_product' => $every_product,
        'start' => $start,
        'expiry' => $end,
        'condition' => $condition
    ));
    die();
}

// Promos

add_action("wp_ajax_promos", "promos_do");
add_action("wp_ajax_nopriv_promos", "promos_do");

function promos_do() {
    $bar_id = $_GET["barid"];
    $bar_cat = $_GET["barcat"];
    echo do_shortcode('[wpsc_products category_url_name="promo" sort_order="name"]');
    die();
}

// Install tax

add_action("wp_ajax_installtax", "installtax_do");
add_action("wp_ajax_nopriv_installtax", "installtax_do");

function installtax_do() {
    // Usage: /wp-admin/admin-ajax.php?action=installtax&id=47
    $barid = $_GET['id'];?>
	<script type="text/javascript" src="http://srvdme.com/wp-content/themes/Starkers/js/zepto.min.js"></script>
	<script type="text/javascript">
	    barid = "<?php echo $barid; ?>";
	    $(function () {
	        $.post("/wp-admin/admin-ajax.php?action=changetax&id="+barid+"&cat=b&tax=8.5");
	        $.post("/wp-admin/admin-ajax.php?action=changetax&id="+barid+"&cat=w&tax=8.5");
	        $.post("/wp-admin/admin-ajax.php?action=changetax&id="+barid+"&cat=c&tax=8.5");
	        $.post("/wp-admin/admin-ajax.php?action=changetax&id="+barid+"&cat=s&tax=8.5");
	        $.post("/wp-admin/admin-ajax.php?action=changetax&id="+barid+"&cat=sd&tax=8.5");
	        $.post("/wp-admin/admin-ajax.php?action=changetax&id="+barid+"&cat=f&tax=8.5");
	    });
	</script>
<?php
    die();
}

// Change tax

add_action("wp_ajax_changetax", "changetax_do");
add_action("wp_ajax_nopriv_changetax", "changetax_do");
function changetax_do() {
    // Usage: /wp-admin/admin-ajax.php?action=changetax&id=47&cat=b&tax=8.5
    global $wpdb;
    $id = $_GET['id'];
    $cat = $_GET['cat'];
    $tax = $_GET['tax'];
    $tax = $tax / 100;
    //Check does the row exist
    $check = $wpdb->get_row("select * from wp_usermeta where user_id=$id and meta_key='tax_$cat'");
    if (count($check) == 0) {
        $wpdb->insert('wp_usermeta', array(
            'user_id' => $id,
            'meta_key' => "tax_$cat",
            'meta_value' => $tax
        ));

    } else {
        $wpdb->update('wp_usermeta',
            array(
                'meta_value' => $tax
            ),
            array(
                'user_id' => $id,
                'meta_key' => "tax_$cat"
            ));
    }
    $effected = $wpdb->get_row("select * from wp_usermeta where user_id=$id and meta_key='tax_$cat'");
    //echo json_encode($effected);
    die();
}

// Piggy cats

add_action("wp_ajax_piggycats", "piggycats_do");
add_action("wp_ajax_nopriv_piggycats", "piggycats_do");
function piggycats_do() {
    echo '<div id="cats-box">';
    global $piggy;
    $piggy->setup_date_time();
    echo '<div class="bar-name">';
    global $current_user;
    get_currentuserinfo();
    echo ucfirst($current_user->user_firstname);
    echo ' #' . $current_user->ID . '<br/>';
    $time = strtotime('-8 hour',time());
    echo '<div id="date-small">Night of ' . date('D, M jS Y', $time) . '</div>';
    echo '</div><!--/bar-name-->';
    $categories = array(
        'b-' . $current_user->ID => '',
        'w-' . $current_user->ID => '',
        'c-' . $current_user->ID => '',
        's-' . $current_user->ID => '',
        'sd-' . $current_user->ID => '',
        'f-' . $current_user->ID => ''
    );
    global $wpdb;
    $totalprice = 0;
    $tax_total = 0;
    foreach ($categories as $key => $value) {
        $sql = "select e.`name`,SUM(b.price * b.quantity) as total,sum(tax_charged) as tax from wp_wpsc_purchase_logs a left join wp_wpsc_cart_contents b on a.id = b.purchaseid
				left join wp_term_relationships c on b.prodid = c.object_id
				left join wp_term_taxonomy d on c.term_taxonomy_id = d.term_taxonomy_id
				left join wp_terms e on d.term_id=e.term_id 
				where e.`name` = '$key' and date >={$piggy->date_time_helper[today][0]} and date <={$piggy->date_time_helper[today][1]} and a.engravetext = {$current_user->ID}
				and a.processed in (2,3,5)
				 group BY e.`name`";
        $categories[$key] = $wpdb->get_results($sql);
        $tax_name = explode('-', $key);
        $tax_name = 'tax_' . $tax_name[0];
        $tax = $wpdb->get_results("select meta_value from wp_usermeta where meta_key = '$tax_name' and user_id={$current_user->ID}");
        if (!empty($categories[$key])) {

            //$categories[$key][0]->total = $categories[$key][0]->total + $categories[$key][0]->total*$tax[0]->meta_value;
            $categories[$key][0]->tax = ($categories[$key][0]->total * $tax[0]->meta_value);
            $categories[$key][0]->totalsum = $categories[$key][0]->total + $categories[$key][0]->tax;

            // Kyle 9/1/12
            $totalprice += $categories[$key][0]->total;
            $tax_total += $categories[$key][0]->tax;

            if (empty($tax[0]->meta_value))
                $tax[0]->meta_value = 0;
            else
                $tax[0]->meta_value = $tax[0]->meta_value * 100;
            $categories[$key][0]->tax_rate = $tax[0]->meta_value;
        } else {
            $categories[$key][0]->total = 0;
            $categories[$key][0]->tax = 0;
            $categories[$key][0]->tax_rate = 0;
        }
    }
    $kh_gratuity = 0;
    if (alex_get_gratuity()) {
        $kh_gratuity = alex_get_gratuity();
    }
    ;
    $kh_grandtotal = $totalprice + $kh_gratuity + $tax_total;
    
    $beer = $categories['b-'.$current_user->ID][0]->totalsum;
    $wine = $categories['w-'.$current_user->ID][0]->totalsum;
    $cocktails = $categories['c-'.$current_user->ID][0]->totalsum;
    $shooters = $categories['s-'.$current_user->ID][0]->totalsum;
    $soft_drinks = $categories['sd-'.$current_user->ID][0]->totalsum;
    $food = $categories['f-'.$current_user->ID][0]->totalsum;

    echo '<div id="pos-wrap">';
    echo '<p class="pos-data">Beer: <span class="green">$'.number_format($beer,2).'</span></p>';
    echo '<p class="pos-data">Wine: <span class="green">$'.number_format($wine,2).'</span></p>';
    echo '<p class="pos-data">Cocktails: <span class="green">$'.number_format($cocktails,2).'</span></p>';
    echo '<p class="pos-data">Shooters: <span class="green">$'.number_format($shooters,2).'</span></p>';
    echo '<p class="pos-data">Soft Drinks: <span class="green">$'.number_format($soft_drinks,2).'</span></p>';
    echo '<p class="pos-data">Food: <span class="green">$'.number_format($food,2).'</span></p>';
    //echo '<p class="pos-data pos-sub">Sub-Total: <span class="green">$' . number_format($totalprice, 2) . '</span></p>';
    //echo '<p class="pos-data pos-tax">Total Tax: <span class="green">$' . number_format($tax_total, 2) . '</span></p>';
    //echo '<p class="pos-data pos-grat">Total Gratuity: <span class="green">$' . number_format($kh_gratuity, 2) . '</span></p>';
    //echo '<p class="pos-data pos-total">Grand Total: <span class="green">$' . number_format($kh_grandtotal, 2) . '</span></p>';
    echo '</div>';
    echo '</div><!--/cats-box-->';
    die();
}

// Piggy day

add_action("wp_ajax_piggyday", "piggyday_do");
add_action("wp_ajax_nopriv_piggyday", "piggyday_do");

function piggyday_do() {
    global $piggy;
    $piggy->setup_date_time(); ?>
	<div id="stats-box">
    <?php piggy_populate_overview_data(); ?>
    <?php while (piggy_has_data()) { ?>
    <?php piggy_the_data(); ?>
    <div class="totals <?php piggy_the_data_title() ?>">
        <div class="bar-name">
            <?php global $current_user; get_currentuserinfo();
            echo ucfirst($current_user->user_firstname);
            echo ' #' . $current_user->ID;
            $time = strtotime('-8 hour',time());
            echo '<div id="date-small">Night of ' . date('D, M jS Y', $time) . '</div>';?>
        </div>
        <!--/bar-name-->
        <?php
        $ak_all_seating = alex_get_data_all_seating();
        if ($ak_all_seating != false) {
            $ak_sales_seating = $ak_all_seating->count;
            $ak_value_seating = $ak_all_seating->total;
            $ak_gratuity_seating = alex_get_gratuity_seating();
            $ak_avesize_seating = ($ak_sales_seating) ? ($ak_value_seating) / $ak_sales_seating : 0;
            $ak_aveclosed_seating = $ak_all_seating->aveclosed;
        } else {
            $ak_all_seating = 0;
            $ak_sales_seating = 0;
            $ak_value_seating = 0;
            $ak_gratuity_seating = 0;
            $ak_avesize_seating = 0;
            $ak_aveclosed_seating = 0;
        }
        if (piggy_get_data_sales()) {
            $ak_sales = piggy_get_data_sales() - $ak_sales_seating;
            $ak_value = piggy_get_data_value() - $ak_value_seating;
            $ak_gratuity = alex_get_gratuity() - $ak_gratuity_seating;
            $ak_avesize = ($ak_sales) ? ($ak_value) / $ak_sales : 0;
        } else { // Alex 051412
            $ak_sales = 0;
            $ak_value = 0;
            $ak_gratuity = 0;
            $ak_avesize = 0;
        }
        $kh_aveclosed = ak_aveclosed_tmp();
        $kh_sales_total = $ak_sales + $ak_sales_seating;
        $kh_avesize_total = ($ak_avesize + $ak_avesize_seating);
        if ($ak_avesize == 0) {
        }
        $kh_aveclosed_total = ($kh_aveclosed + $ak_aveclosed_seating) / 2;
        $kh_value_total = $ak_value + $ak_value_seating;
        $kh_gratuity_total = $ak_gratuity + $ak_gratuity_seating;
        ?>
        <div id="bar-wrapper">
            <div id="bar-title">Bar Orders</div>
            <?php echo '<span class="green">' . $ak_sales . '</span> orders';?></br>
            <?php echo '<span class="green">$' . number_format($ak_value, 2) . '</span> sales';?><br/>
            <?php echo '<span class="green">$' . number_format($ak_avesize, 2) . '</span> ave size';?><br/>
            <!-- <?php echo '<span class="green">' . number_format($kh_aveclosed, 2) . '</span> ave wait';?><br/> -->
            <?php echo '<span class="green">$' . number_format($ak_gratuity, 2) . '</span> gratuity';?><br/>
        </div>
        <!--/bar-wrapper-->
        <div id="table-wrapper">
            <div id="table-title">Table Orders</div>
            <?php echo '<span class="green">' . $ak_sales_seating . '</span> orders';?></br>
            <?php echo '<span class="green">$' . number_format($ak_value_seating, 2) . '</span> sales';?><br/>
            <?php echo '<span class="green">$' . number_format($ak_avesize_seating, 2) . '</span> ave size';?><br/>
            <!-- <?php echo '<span class="green">' . number_format($ak_aveclosed_seating, 2) . '</span> ave wait';?><br/> -->
            <?php echo '<span class="green">$' . number_format($ak_gratuity_seating, 2) . '</span> gratuity';?><br/>
        </div>
        <!--/table-wrapper-->
        <div id="total-wrapper">
            <div id="table-title">Grand Totals</div>
            <?php echo '<span class="green">' . $kh_sales_total . '</span> orders';?></br>
            <?php echo '<span class="green">$' . number_format($kh_value_total, 2) . '</span> sales'; ?><br/>
            <?php echo '<span class="green">$' . number_format($kh_avesize_total, 2) . '</span> ave size';?><br/> 
            <!-- <?php echo '<span class="green">' . number_format($kh_aveclosed_total, 2) . '</span> ave wait';?><br/> -->
            <?php echo '<span class="green">$' . number_format($kh_gratuity_total, 2) . '</span> gratuity';?><br/>
        </div>
        <!--/total-wrapper-->
        <?php echo '<span class="small">Does not include orders from active shifts.</span>';?>
    </div><!--/totals-->
    <?php die();
} ?>
</div><!--/stats-box--><?php
}

// Piggy week

add_action("wp_ajax_piggyweek", "piggyweek_do");
add_action("wp_ajax_nopriv_piggyweek", "piggyweek_do");

function piggyweek_do() {

    global $wpdb;
    global $current_user;
    $bar_id = $current_user->ID;
	if( !$bar_id ) { die(); }

    $week_start = strtotime('last Monday', time());
    $week_end = strtotime('next Monday', time());
    
    $month_start = strtotime('first day of '.date("M"), time());
    $month_end = strtotime('last day of '.date("M"), time());
    
    $year_start = strtotime('first day of January', time());
    $year_end = strtotime('last day of December', time());
    
/*
    echo date('D, M jS Y H:i:s', $week_start).'<br/>';
    echo date('D, M jS Y H:i:s', $week_end).'<br/>';
    
    echo date('D, M jS Y H:i:s', $month_start).'<br/>';
    echo date('D, M jS Y H:i:s', $month_end).'<br/>';
    
    echo date('D, M jS Y H:i:s', $year_start).'<br/>';
    echo date('D, M jS Y H:i:s', $year_end).'<br/>';
*/

    $piggy_week = $wpdb->get_row("select count(*) as count, sum(totalprice) as total, sum(wpec_taxes_total) as tax, sum(base_shipping) as gratuity, sum(discount_value) as discount from wp_wpsc_purchase_logs where date >= $week_start and engravetext = $bar_id and processed in (2,3,5)");
    
    $piggy_month = $wpdb->get_row("select count(*) as count, sum(totalprice) as total, sum(wpec_taxes_total) as tax, sum(base_shipping) as gratuity, sum(discount_value) as discount from wp_wpsc_purchase_logs where date >= $month_start and engravetext = $bar_id and processed in (2,3,5)");
    
    $piggy_year = $wpdb->get_row("select count(*) as count, sum(totalprice) as total, sum(wpec_taxes_total) as tax, sum(base_shipping) as gratuity, sum(discount_value) as discount from wp_wpsc_purchase_logs where date >= $year_start and engravetext = $bar_id and processed in (2,3,5)");
    
    $sales_week = $piggy_week->total + $piggy_week->discount - $piggy_week->tax;
    $sales_month = $piggy_month->total + $piggy_month->discount - $piggy_month->tax;
    $sales_year = $piggy_year->total + $piggy_year->discount - $piggy_year->tax;

    echo '<div id="summary-box">';
    echo '<div id="widget-left">';
    echo '<p class="widget-header">Current Week</p>';
    echo '<p class="widget-data"><span class="green">'.$piggy_week->count.'</span> orders</p>';
    echo '<p class="widget-data"><span class="green">$'.number_format($sales_week, 2).'</span> sales</p>';
    echo '</div>';

    echo '<div id="widget-mid">';
    echo '<p class="widget-header">Current Month</p>';
    echo '<p class="widget-data"><span class="green">'.$piggy_month->count . '</span> orders</p>';
    echo '<p class="widget-data"><span class="green">$'.number_format($sales_month, 2).'</span> sales</p>';
    echo '</div>';

    echo '<div id="widget-right">';
    echo '<p class="widget-header">Current Year</p>';
    echo '<p class="widget-data"><span class="green">'.$piggy_year->count . '</span> orders</p>';
    echo '<p class="widget-data"><span class="green">$'.number_format($sales_year, 2).'</span> sales</p>';
    echo '</div>';
    echo '</div>';

    die();
}

// Happy

add_action("wp_ajax_happy", "happy_do");
add_action("wp_ajax_nopriv_happy", "happy_do");

function happy_do() {

    $ak_barID = get_current_user_id();
    if (!empty ($ak_barID)) {
        $my_where = ' AND user_id=' . $ak_barID . ' ';
    } else {
        //	 echo -1;
        exit;
    }
    $status = $_REQUEST["status"];
    $novalue = '';
    if (isset($status)) {
        switch ($status) {
            case '1':
                //$sql = "UPDATE wp_usermeta SET meta_value='1' WHERE meta_key='yim'" . $my_where;
		update_user_meta($ak_barID, 'yim', 1);
                break;
            case 'toggle':
                //$sql = "UPDATE wp_usermeta SET meta_value= IF(meta_value='1', '0','1') WHERE meta_key='yim'";
		if (get_user_meta($ak_barID, 'yim', true) == 1) { update_user_meta($ak_barID, 'yim', 0); }
		else {update_user_meta($ak_barID, 'yim', 1); }
                break;
            case '0':
            default:
                //$sql = "UPDATE wp_usermeta SET meta_value='0' WHERE meta_key='yim'" . $my_where;
		update_user_meta($ak_barID, 'yim', 0);
                break;
        }
        //$result = mysql_query($sql);
    } else {
        $sql = "SELECT * FROM wp_usermeta WHERE meta_key='yim' " . $my_where . " LIMIT 1";
        $result = mysql_query($sql);
        $row = mysql_fetch_assoc($result);
        $result = $row['meta_value'];
        if (empty($result)) $result = 0;
        echo $result;
    }
    die();
}

// Open

add_action("wp_ajax_open", "open_do");
add_action("wp_ajax_nopriv_open", "open_do");

function open_do() {
    $ak_barID = get_current_user_id();
    if (!empty ($ak_barID)) {
        $my_where = ' AND user_id=' . $ak_barID . ' ';
    } else {
        //	 echo -1;
        exit;
    }
    $status = $_REQUEST["status"];
    $novalue = '';
    // Alex 032012 - changing table to wp_usermeta
    if (isset($status)) {
        switch ($status) {
            case '0':
            default:
                //	$sql="UPDATE wp_options SET option_value='0' WHERE option_name='show_avatars'";
                //$sql = "UPDATE wp_usermeta SET meta_value='0' WHERE meta_key='jabber'" . $my_where;
		update_user_meta($ak_barID, 'jabber', 0);
                break;
            case '1':
                //	$sql = "UPDATE wp_options SET option_value='1' WHERE option_name='show_avatars'";
                //$sql = "UPDATE wp_usermeta SET meta_value='1' WHERE meta_key='jabber'" . $my_where;
		update_user_meta($ak_barID, 'jabber', 1);
                break;
        }
        $result = mysql_query($sql);
    } else {
        //	$sql="SELECT * FROM wp_options WHERE option_name='show_avatars' LIMIT 1";
        $sql = "SELECT * FROM wp_usermeta WHERE meta_key='jabber' " . $my_where . " LIMIT 1";
        $result = mysql_query($sql);
        $row = mysql_fetch_assoc($result);
        //	$result = $row['options_value'];
        $result = $row['meta_value'];
        if (empty($result)) $result = 0;
        echo $result;
    }
    die();
}

// Empty cart

add_action("wp_ajax_emptycart", "emptycart_do");
add_action("wp_ajax_nopriv_emptycart", "emptycart_do");

function emptycart_do() {
    wpsc_empty_cart();
    die();
}

// Tables

add_action("wp_ajax_tables", "tables_do");
add_action("wp_ajax_nopriv_tables", "tables_do");

function tables_do() {
    $ak_barID = get_current_user_id();
    if (!empty ($ak_barID)) {
        $my_where = ' AND user_id=' . $ak_barID . ' ';
    } else {
        exit;
    }
    $status = $_REQUEST["status"];
    $novalue = '';
    if (isset($status)) {
        switch ($status) {
            case '1':
                $sql = "UPDATE wp_usermeta SET meta_value='1' WHERE meta_key='aim'" . $my_where;
                break;
            case 'toggle':
                $sql = "UPDATE wp_usermeta SET meta_value= IF(meta_value='1', '0','1') WHERE meta_key='aim'";
                break;
            case '0':
            default:
                $sql = "UPDATE wp_usermeta SET meta_value='0' WHERE meta_key='aim'" . $my_where;
                break;
        }
        $result = mysql_query($sql);
    } else {
        $sql = "SELECT * FROM wp_usermeta WHERE meta_key='aim' " . $my_where . " LIMIT 1";
        $result = mysql_query($sql);
        $row = mysql_fetch_assoc($result);
        $result = $row['meta_value'];
        if (empty($result)) $result = 0;
        echo $result;
    }
    die();
}

// Printing

add_action("wp_ajax_printing", "printing_do");
add_action("wp_ajax_nopriv_printing", "printing_do");

function printing_do() {
    $ak_barID = get_current_user_id();
    if (!empty ($ak_barID)) {
        $my_where = ' AND user_id=' . $ak_barID . ' ';
    } else {
        exit;
    }
    $status = $_REQUEST["status"];
    $novalue = '';
    if (isset($status)) {
        switch ($status) {
            case '1':
                $sql = "UPDATE wp_usermeta SET meta_value='1' WHERE meta_key='show_admin_bar_front'" . $my_where;
                break;
            case 'toggle':
                $sql = "UPDATE wp_usermeta SET meta_value= IF(meta_value='1', '0','1') WHERE meta_key='show_admin_bar_front'";
                break;
            case '0':
            default:
                $sql = "UPDATE wp_usermeta SET meta_value='0' WHERE meta_key='show_admin_bar_front'" . $my_where;
                break;
        }
        $result = mysql_query($sql);
    } else {
        $sql = "SELECT * FROM wp_usermeta WHERE meta_key='show_admin_bar_front' " . $my_where . " LIMIT 1";
        $result = mysql_query($sql);
        $row = mysql_fetch_assoc($result);
        $result = $row['meta_value'];
        if (empty($result)) $result = 0;
        echo $result;
    }
    die();
}

// Get orders

add_action("wp_ajax_getorders", "getorders_do");
add_action("wp_ajax_nopriv_getorders", "getorders_do");

function getorders_do() {

    //date_default_timezone_set('America/Los_Angeles');
    global $wpdb;
	$bar_id = $_GET['barid'];
	$end_date = time();
	$start_date = strtotime(date('Y-m-d') . '12:00:00'); // Shift starts at 4AM
	if ( $end_date < $start_date ) {
		$pastMidnight = true;
    	$start_date = strtotime('-1 day', $start_date ); // Shift starts at 4AM yesterday
    }  
    //$start_date = date('D, M jS Y h:i:s A',$start_date);
    //$end_date = date('D, M jS Y h:i:s A',$end_date);
    //echo '<strong>Start: '.$start_date.'</strong><br/>';
    //echo '<strong>End: '.$end_date.'</strong><br/>';
	$orders = $wpdb->get_results("select * from wp_wpsc_purchase_logs where engravetext = '$bar_id' and processed in (2,3) and date > $start_date order by id asc");
	$numbered = 0;
    foreach ($orders as $order) {
        if (!empty($order)) {
            $numbered = $numbered + 1;
            $order_num = $numbered;
            $order_iid = $order->id;
           	$order_iid_short = (int)substr($order_iid, -3);
           	$user_info = get_userdata($order->user_ID);
            $order_login = $user_info->user_login;
            $order_fname = get_user_meta($order->user_ID, 'first_name', true);
            $order_lname = get_user_meta($order->user_ID, 'last_name', true);
            $order_time = $order->date;
            $order_discount = $order->discount_value;
            $order_seating = $order->shipping_region;
            $order_gratuity = number_format($order->base_shipping, 2);
            $order_gtotal = number_format($order->totalprice - $order->wpec_taxes_total + $order_discount, 2);
            $elapsed_mins = time() - $order_time;
            $order_status = $order->statusno;
            if ($order_status > 0) {
                $order_status = "ready";
            } else {
                $order_status = "waiting";
            };
            echo '<div id="line'.$order_num.'" ordernum="'.$order_num.'" class="order-line line'.$order_num.' flash '.$order_status.'" onclick="showInfo('.$order_num.')">';
            echo '<div class="order-id">#' . $order_iid_short . '</div>';
            echo '<div class="order-name">' . $order_fname . ' ' . substr($order_lname,0,1) . '. </div>';
            echo '<div class="waiting-tag">is waiting…</div><div class="ready-tag">is ready!</div>';
            if ($order_status == "waiting") {
                echo '<div class="order-time" id="time' . $order_num . '">' . $elapsed_mins . '</div>';
            }
            ?>
	        <script type="text/javascript">
	            startClock('<?php echo $elapsed_mins ?>', '<?php echo $order_num ?>');
	        </script><?php
            if ($order_seating) {
                echo '<div class="table-order ' . $order_status . '">Deliver to table <span class="table-num">#' . $order_seating . '</span></div>';
            }
            echo '</div><!--/order-line-->';
            if ($order_seating) {
                echo '<div class="spacer spacer' . $order_num . ' ' . $order_status . '"></div>';
            }
            echo '<span class="dead">';
            echo '<div id="order-num' . $order_num . '">' . $order_num . '</div>';
            echo '<div id="order-num' . $order_num . '">' . $order_num . '</div>';
            echo '<div id="order-iid' . $order_num . '">' . $order_iid . '</div>';
            echo '<div id="order-login' . $order_num . '">' . $order_login . '</div>';
            echo '<div id="order-fname' . $order_num . '">' . $order_fname . '</div>';
            echo '<div id="order-lname' . $order_num . '">' . substr($order_lname,0,1) . '.' . '</div>';
            echo '<div id="order-time' . $order_num . '">' . $order_time . '</div>';
            echo '<div id="order-discount' . $order_num . '">' . $order_discount . '</div>';
            echo '<div id="order-status' . $order_num . '">' . $order_status . '</div>';
            echo '<div id="order-seating' . $order_num . '">' . $order_seating . '</div>';
            echo '<div id="order-gratuity' . $order_num . '">' . $order_gratuity . '</div>';
            echo '<div id="order-total' . $order_num . '">$' . $order_total . '</div>';

            echo '<div id="receipt-middle' . $order_num . '">';
            echo '<div id="receipt-lines' . $order_num . '">';
            $items = $wpdb->get_results("select * from wp_wpsc_cart_contents where purchaseid = $order_iid ");
            $order_subtotal = 0;
            foreach ($items as $item) {
            	$order_subtotal = $item->price + $order_subtotal;
                echo '<div class="line-wrap">';
                echo '<div class="line-qty">' . $item->quantity . '</div>';
                echo '<div class="line-name">' . substr($item->name,0,23) . '</div>';
                echo '<div class="line-price">$' . $item->price . '</div>';
                echo '</div><!--/line-wrap-->';
            }
            $order_total = number_format($order_subtotal + $order_gratuity, 2);
            echo '</div><!--/receipt-lines-->';
            echo '<div id="gratuity-line">';
            echo '<div id="info-grat1">Gratuity</div>';
            echo '<div id="info-grat2">$' . $order_gratuity . '</div>';
            echo '</div><!--/gratuity-line-->';
            echo '</div><!--/receipt-middle-->';
            echo '<div id="receipt-bottom' . $order_num . '">';
            echo '<div id="info-total1">Total</div>';
            echo '<div id="info-total2">$' . $order_gtotal . '</div>';
            echo '</div><!--/receipt-bottom-->';
            echo '</span><!--/dead-->';
        }
    }
    die();
}

// Void order

add_action("wp_ajax_voidorder", "voidorder_do");
add_action("wp_ajax_nopriv_voidorder", "voidorder_do");

function voidorder_do() {	
	global $wpdb, $current_user;
	$orderid = $_GET['orderid'];
	$barid = $current_user->ID;
	$secret = $wpdb->get_row("select meta_value from wp_usermeta where user_id='$barid' and meta_key='stripe_secret' limit 1");
	Stripe::setApiKey('sk_live_qcFFf5ISf79edaZLT2Yt586Y');
	$order = $wpdb->get_row("select transactid from wp_wpsc_purchase_logs where id = '$orderid' limit 1");
	$trans = $order->transactid;
	$sql = "update wp_wpsc_purchase_logs set processed = 7 where id = '$orderid' limit 1";
    mysql_query($sql);
    if ($secret > 0) {
    	$charge = Stripe_Charge::retrieve($trans,$secret->meta_value);
    } else {
    	$charge = Stripe_Charge::retrieve($trans);
    }
	$response = $charge->refund();
	echo $response->amount;
	die();
}

// Auth void

add_action("wp_ajax_authvoid", "authvoid_do");
add_action("wp_ajax_nopriv_authvoid", "authvoid_do");

function authvoid_do() {

	// Usage: /wp-admin/admin-ajax.php?action=authvoid
	
	global $wpdb;
    global $current_user;
    $id = $current_user->ID;
	$orders = $wpdb->get_row("select * from wp_wpsc_purchase_logs where user_id = $id and processed = 2 limit 1");
	$order_id = $orders->id;
	$sql = "update wp_wpsc_purchase_logs set processed = 0 where user_id = $id and processed = 2 limit 1";
    mysql_query($sql);
	$order_row = $wpdb->get_row("select * from wp_wpsc_purchase_logs where id = $order_id limit 1");
	$trans_id = $order_row->transactid;
	
	$auth_login = '6J43TtRhu';
	$auth_key = '45a3753HxvBZf5UG';
	
	$post_url = 'https://secure.authorize.net/gateway/transact.dll';
	$post_values = array(
		"x_login"			=> $auth_login,
		"x_tran_key"		=> $auth_key,
		"x_version"			=> "3.1",
		"x_delim_data"		=> "TRUE",
		"x_delim_char"		=> ",",
		"x_relay_response"	=> "FALSE",
		"x_email_customer "	=> "FALSE",
		"x_type"			=> "VOID",
		"x_trans_id"		=> $trans_id
	);
	
	$post_string = "";
	foreach( $post_values as $key => $value ) { 
		$post_string .= "$key=" . urlencode( $value ) . "&";
	}
	$post_string = rtrim( $post_string, "& " );
	
	$request = curl_init($post_url); 
		curl_setopt($request, CURLOPT_HEADER, 0); 
		curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($request, CURLOPT_POSTFIELDS, $post_string); 
		curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE); 
		$post_response = curl_exec($request); 
	curl_close ($request); 
	
	$response_array = explode($post_values["x_delim_char"],$post_response);
	
	if($response_array[0] == 1){
		echo 'Success!';  
	} else if($response_array[0] == 2){
		echo 'Failed!';
		echo $response_array[3];
	} else if($response_array[0] == 3){
		echo 'Error!';
		echo $response_array[3];
	} else if($response_array[0] == 4){
		echo 'Pending.';
		echo $response_array[3];
	} else {
		echo 'There was an error.';
	}

	die();

}

// Last visited

add_action("wp_ajax_lastvisited", "lastvisited");
add_action("wp_ajax_nopriv_lastvisited", "lastvisited");

function lastvisited() {
    global $current_user;
    $id = $current_user->ID;
    $status = wpsc_cart_item_product_author();
    $sql = "UPDATE wp_usermeta SET meta_value='".$status."' WHERE user_id='".$id."' AND meta_key='admin_color'";
    mysql_query($sql);
    echo $status;
    die();
}

// Cart author

add_action("wp_ajax_cartauthor", "cartauthor_do");
add_action("wp_ajax_nopriv_cartauthor", "cartauthor_do");

function cartauthor_do(){
    global $wpdb;
    global $current_user;
    echo wpsc_cart_item_product_author();
    die();
}

// Piggy tables

//add_action("piggytables_do", "piggytables_do");
add_action("wp_ajax_piggytables", "piggytables_do");
add_action("wp_ajax_nopriv_piggytables", "piggytables_do");

function piggytables_do(){
    global $piggy;
    global $wpdb;
    global $current_user;
    $piggy->setup_date_time();
    get_currentuserinfo();
    $tables = $wpdb->get_results("select a.shipping_region,sum(b.price * b.quantity) as sale from wp_wpsc_purchase_logs a LEFT JOIN wp_wpsc_cart_contents b
	on a.id = b.purchaseid
	where date >={$piggy->date_time_helper[today][0]} and date <={$piggy->date_time_helper[today][1]} and engravetext = {$current_user->ID} and TRIM(a.shipping_region) <> \"\"
	GROUP BY a.shipping_region");
    foreach ($tables as $key => $value) {
        $gratuity = $wpdb->get_row("select count(*) as count, sum(totalprice) as total,sum(wpec_taxes_total) as tax,sum(discount_value) as discount_value from wp_wpsc_purchase_logs where shipping_region = {$value->shipping_region} and date >={$piggy->date_time_helper[today][0]} and date <={$piggy->date_time_helper[today][1]} and engravetext = {$current_user->ID}");
        $tables[$key]->gratuity = $gratuity->total - $value->sale - $gratuity->tax + $gratuity->discount_value;
        $tables[$key]->tax = $gratuity->tax;
        $tables[$key]->orders = $gratuity->count; 
        $tables[$key]->total = $value->sale + $value->gratuity;
    }
    ?>
	<div id="tables-box">
	    <?php if (count($tables) > 0): ?>
	    <table id="tables-table">
	        <tr class="totals table-header">
	            <td class="table1">Table</td>
	            <td class="table2">Orders</td>
	            <td class="table2">Sales</td>
	            <td class="table3">Gratuity</td>
	            <td class="table3">Total</td>
	        </tr>
	        <?php foreach ($tables as $table): ?>
	        <tr class="totals">
	            <td class="table1">#<?php echo $table->shipping_region?></td>
	            <td class="table2 green"><?php echo number_format($table->orders, 0) ?></td>
	            <td class="table2 green">$<?php echo number_format($table->sale, 2) ?></td>
	            <td class="table3 green">$<?php echo number_format($table->gratuity, 2) ?></td>
	            <td class="table3 green">$<?php echo number_format($table->total, 2) ?></td>
	        </tr>
	        <?php endforeach; ?>
	    </table><!--/tables-table-->
	    <?php else: ?>
	    <p id="no-table">No table orders today…</p>
	    <?php endif; ?>
	</div><!--/tables-box-->
<?php
    die();
}

// Affiliates

add_action("wp_ajax_affiliates", "affiliates_do");
add_action("wp_ajax_nopriv_affiliates", "affiliates_do");

function affiliates_do() {

    // Usage: /wp-admin/admin-ajax.php?action=affiliates
   
    global $wpdb;   

	$curr_time = time();
	$shift_end = $curr_time;
	
	$day_start = strtotime(date('Y-m-d').'12:00:00'); // DST
	$shift_start = strtotime('-7 day', $day_start);
 
    //$codes = $wpdb->get_results("select distinct(discount_data) from wp_wpsc_purchase_logs");
    $codes = $wpdb->get_results("select distinct(coupon_code) from wp_wpsc_coupon_codes");
    $codes = array_filter($codes);
    foreach ($codes as $code) {
        if (!empty($code->coupon_code)) {
        	$name = $code->coupon_code;
            $codes_in_year = $wpdb->get_row("select count(*) as count, sum(discount_value) as total from wp_wpsc_purchase_logs where discount_data = '$name' and date > '$shift_start' and processed in (2,3,5) order by '$name' desc");
            $discount = $codes_in_year->total;
            $message .= '<strong>'.$name.'</strong>: $'.number_format($discount,2).'<br/>';
            echo $name.': $'.number_format($discount,2).'<br/>';
        }
    }
    $header = '<p>Srvd affiliate payouts since '.date('g:iA \o\n D, M jS Y',$shift_start).':</p>';
    $subject = 'Affiliate Deposits (' . date('l') . ')';
    $email = 'support@srvdme.com';
    wp_mail($email, $subject, $header . $message);
    die();
}

// Active shift

add_action("wp_ajax_activeshift", "activeshift_do");
add_action("wp_ajax_nopriv_activeshift", "activeshift_do");

function activeshift_do() {

    // Usage: /wp-admin/admin-ajax.php?action=activeshift&barid=2
    
    global $wpdb;
    $bar_id = trim($_GET['barid']);
	if( !$bar_id ) { die(); }
	
	$curr_time = time();
	$shift_end = $curr_time;
	
	$day_start = strtotime(date('Y-m-d').'12:00:00'); // DST
	$shift_start = $day_start;
	
	if ( $curr_time < $day_start ) {
		$pastMidnight = true;
    	$shift_start = strtotime('-1 day', $shift_start);
    }
    
    $checks = $wpdb->get_row("select * from ft_shifts where bar_id = $bar_id and shift_end > $shift_start order by id desc limit 1");
	
	if ($checks != null ) {
		foreach ($checks as $check) {
	     	$shift_start = $checks->shift_end + 1;
	    }
	}

    //echo 'Start: '.date('D, M jS Y h:i:s A',$shift_start).'<br/>';
    //echo 'End: '.date('D, M jS Y h:i:s A',$shift_end).'<br/>';

	$orders = 0;
	$subtotal = 0;
	$tax = 0;
	$gratuity = 0;
	$total = 0;

    $sneakpeak = $wpdb->get_row("select count(*) as count, sum(totalprice) as total, sum(wpec_taxes_total) as tax, sum(base_shipping) as gratuity, sum(discount_value) as discount_value from wp_wpsc_purchase_logs where date >= $shift_start and engravetext = $bar_id and processed in (2,3,5)");

	echo '<div id="shifts-wrapper">';

		if ($sneakpeak != null ) {
			$name = '<em>Unknown</em>';
			$orders = $sneakpeak->count;
			$tax = $sneakpeak->tax;
			$discount = $sneakpeak->discount_value;
			$gratuity = $sneakpeak->gratuity;
			$total = $sneakpeak->total - $tax - $gratuity + $discount;
		}
		echo '<div class="shifts-box active">';
			echo '<div class="shift-h1">Name</div>';
			echo '<div class="shift-h3">Start</div>';
			echo '<div class="shift-h4">End</div>';
			echo '<div class="shift-h2">Orders</div>';
			echo '<div class="shift-h5">Sales</div>';
			echo '<div class="shift-h6">Gratuity</div>';
			echo '<div class="shift-name">'.$name.'</div>';
			echo '<div class="shift-start">'.date("g:ia",strtotime('-8 hour', $shift_start)).'</div>'; // DST
			echo '<div class="shift-end">Present</div>';
			echo '<div class="shift-orders">'.$orders.'</div>';
			echo '<div class="shift-sales green">$'.number_format($total,2).'</div>';
			echo '<div class="shift-gratuity green">$'.number_format($gratuity,2).'</div>';
		echo '</div><!--/shifts-box-->';

	die();
}

// End shift

add_action("wp_ajax_endshift", "endshift_do");
add_action("wp_ajax_nopriv_endshift", "endshift_do");

function endshift_do() {
    
    // Usage: /wp-admin/admin-ajax.php?action=endshift&barid=2&name=Alex
    	
    global $wpdb;
    $bar_id = trim($_GET['barid']);
    $user_name = trim($_GET['name']);
	if( !$bar_id ) { die(); }
	
	$wpdb->query("update wp_wpsc_purchase_logs set processed=5 where processed in (2,3) and engravetext=$bar_id and statusno=1");
	
	$curr_time = time();
	$shift_end = $curr_time;
	
	$day_start = strtotime(date('Y-m-d').'12:00:00'); // DST
	$shift_start = $day_start;
	
	if ( $curr_time < $day_start ) {
		$pastMidnight = true;
    	$shift_start = strtotime('-1 day', $shift_start);
    }
    
    $checks = $wpdb->get_row("select * from ft_shifts where bar_id = $bar_id and shift_start >= $shift_start order by shift_end desc limit 1");
	
	if ($checks != null ) {
		foreach ($checks as $check) {
	     	$shift_start = $checks->shift_end + 1;
	    }
	}

    //echo 'Start: '.date('D, M jS Y h:i:s A',$shift_start).'<br/>';
    //echo 'End: '.date('D, M jS Y h:i:s A',$shift_end).'<br/>';

	$orders = 0;
	$subtotal = 0;
	$tax = 0;
	$gratuity = 0;
	$total = 0;

    $batch = $wpdb->get_row("select count(*) as count, sum(totalprice) as total, sum(wpec_taxes_total) as tax, sum(base_shipping) as gratuity, sum(discount_value) as discount_value from wp_wpsc_purchase_logs where date >= $shift_start and engravetext = $bar_id and processed in (2,3,5)");

	if ($batch != null ) {
		$orders = $batch->count;
		$tax = $batch->tax;
		$discount = $batch->discount_value;
		$gratuity = $batch->gratuity;
		$subtotal = $batch->total - $tax - $gratuity + $discount;
		$total = $subtotal + $gratuity;
	}

    $wpdb->insert('ft_shifts', array(
        'bar_id' => $bar_id,
        'user_name' => $user_name,
        'orders' => $orders,
		'shift_start' => $shift_start, 
		'shift_end' => $shift_end, 
		'subtotal' => $subtotal, 
		'tax' => $tax, 
		'gratuity' => $gratuity,
		//'discount' => $gratuity, 
		'total' => $total
    ));

	die();
}

// Get shifts

add_action("wp_ajax_getshifts", "getshifts_do");
add_action("wp_ajax_nopriv_getshifts", "getshifts_do");

function getshifts_do() {

    // Usage: /wp-admin/admin-ajax.php?action=getshifts&barid=2
    
    global $wpdb;
    $bar_id = trim($_GET['barid']);
	if( !$bar_id ) { die(); }
	
	$curr_time = time();
	
	$day_start = strtotime(date('Y-m-d').'12:00:00'); // DST
	$shift_start = $day_start;
	
	if ( $curr_time < $day_start ) {
		$pastMidnight = true;
    	$shift_start = strtotime('-1 day', $shift_start);
    }
    
    $checkall = $wpdb->get_results("select * from ft_shifts where bar_id = $bar_id and shift_end > $shift_start order by id desc");

	foreach ( $checkall as $check ) {
		echo '<div class="shifts-box">';
			echo '<div class="shift-h1">Name</div>';
			echo '<div class="shift-h3">Start</div>';
			echo '<div class="shift-h4">End</div>';
			echo '<div class="shift-h2">Orders</div>';
			echo '<div class="shift-h5">Sales</div>';
			echo '<div class="shift-h6">Gratuity</div>';
			echo '<div class="shift-name">'.$check->user_name.'</div>';
			echo '<div class="shift-start">'.date("g:ia",strtotime('-8 hour', $check->shift_start)).'</div>'; // DST
			echo '<div class="shift-end">'.date("g:ia",strtotime('-8 hour', $check->shift_end)).'</div>'; // DST
			echo '<div class="shift-orders">'.$check->orders.'</div>';
			echo '<div class="shift-sales green">$'.number_format($check->subtotal,2).'</div>';
			echo '<div class="shift-gratuity green">$'.number_format($check->gratuity,2).'</div>';
		echo '</div><!--/shifts-box-->';
	}
	echo '</div><!--/shifts-wrapper-->';
	die();
}
?>

<?php

require_once( 'base-helper.php' );

// Alex 030812

// $ak_superadmins = [1, 15, 124, 125];  // These UserID can see everything

function ak_where() {
	if(!isset($_GET['bar']))
 		$ak_barID = get_current_user_id();
 	else
 		$ak_barID=$_GET['bar'];
 
 
 // this is a hack - hard coded IDs for Super Admins - Kyle and Alex
 if (  $ak_barID == 1 
 	|| $ak_barID == 15 
 	|| $ak_barID == 124 
 	|| $ak_barID == 125	) $ak_barID = "";

 if (!empty ($ak_barID ) ) {
    $my_where = ' AND engravetext='. $ak_barID . ' ' ; 
	}
 else {
	$my_where = "";
	}
	
return $my_where;
	
} // ak_where

class WPECHelper extends BaseCommerceHelper {
	function WPECHelper() {
		parent::BaseCommerceHelper( 'WPEC' );
	}
	
	function is_detected() {
		global $wpdb;
		
		$result = @$wpdb->get_results( 'DESCRIBE ' . $wpdb->prefix . 'wpsc_purchase_logs' );
		if ( $result ) {
			if ( is_array( $result ) ) {
				return true;
			}
		}
		
		return false;
	}	

	// This is used for the "Current" summary sections, near the top	
	// This is used for Quick Stats?	
	function get_summary_between_dates( $start_date, $end_date ) {
		global $wpdb;
// Alex 030812
		$actual_start_date = $wpdb->get_row( 'SELECT date FROM ' . $wpdb->prefix . 'wpsc_purchase_logs WHERE date >= ' . $start_date . ak_where() .' ORDER BY date ASC LIMIT 1' );
		if ( $actual_start_date ) {
			$start_date = $actual_start_date->date;
		}

// Alex 030812	
		$actual_end_date = $wpdb->get_row( 'SELECT date FROM ' . $wpdb->prefix . 'wpsc_purchase_logs WHERE date <= ' . $end_date . ak_where() . ' ORDER BY date DESC LIMIT 1' );
		if ( $actual_end_date ) {
			$end_date = $actual_end_date->date;
		}
					
		$purchase_info = new stdClass;

// Alex 030812 - filter ak_where()
// Alex 031112 - remove base_shipping from SUM
		$sales = $wpdb->get_row( 'SELECT count(*) AS count,SUM(totalprice - base_shipping + discount_value + wpec_taxes_total) AS total FROM ' . $wpdb->prefix . 'wpsc_purchase_logs WHERE processed IN (2,3,4) AND date >= ' . $start_date . ' AND date <=' . $end_date . ak_where() );

// Alex 12/28/11
// Kyle changed 2,3,4 to 5. Looks like it's working.. 12/28/11

// Alex 030812
// Alex 031112 " - base_shipping"
// Alex 050412 " + discount_value"
// Alex 070512 - + average_closed
		$sales = $wpdb->get_row( 'SELECT count(*) AS count,SUM(totalprice - base_shipping + discount_value - wpec_taxes_total) AS total, SUM(discount_value) AS discount, AVG(track_id) AS average_closed FROM ' . $wpdb->prefix . 'wpsc_purchase_logs WHERE processed IN (5) AND date >= ' . $start_date . ' AND date <=' . $end_date . ak_where() );

// AK 051012 - same as above, but tables only
		$sales_seating = $wpdb->get_row( 'SELECT count(*) AS count,SUM(totalprice - base_shipping + discount_value - wpec_taxes_total) AS total, SUM(discount_value) AS discount FROM ' . $wpdb->prefix . 'wpsc_purchase_logs WHERE processed IN (5) AND date >= ' . $start_date . ' AND date <=' . $end_date . ' AND shipping_region !=""' . ak_where() );
// Alex 031812 " - gratuity
	
//		$sales_gratuity = $wpdb->get_row( 'SELECT SUM(price*quantity) AS gratuity FROM ' . $wpdb->prefix . 'wpsc_cart_contents AS a LEFT JOIN ' . $wpdb->prefix . 'wpsc_purchase_logs AS b ON a.purchaseid = b.id WHERE b.processed IN (5) '. ak_where2('b.'). ' AND name="Gratuity" AND b.date >= ' . $start_date . ' AND b.date <=' . $end_date );
		
		
		if ( $sales ) {
			$purchase_info->total = $sales->total;
			$purchase_info->count = $sales->count;
			$purchase_info->discount = $sales->discount;
			
			// Alex 070412
			$purchase_info->average_closed = $sales->average_closed;

//			$purchase_info->gratuity = $sales_gratuity->gratuity;
						
			if ( $purchase_info->count ) {
				$total_days = ( $end_date - $start_date ) / 86400;	
				if ( $total_days ) {
					$purchase_info->amount_per_day = $purchase_info->total / $total_days;
				}
			}
		} else {
			$purchase_info->total = 0;
			$purchase_info->count = 0;	
			$purchase_info->discount = 0;
		}
		
		// AK 051012 - Tables only
		if($sales_seating) {
			$purchase_info->total_seating = $sales_seating->total;
			$purchase_info->count_seating = $sales_seating->count;
			$purchase_info->discount_seating = $sales_seating->discount;

			if ( $purchase_info->count_seating ) {
				$total_days = ( $end_date - $start_date ) / 86400;	
				if ( $total_days ) {
					$purchase_info->amount_per_day_seating = $purchase_info->total_seating / $total_days;
				}
			}
		} else {
			$purchase_info->total_seating = 0;
			$purchase_info->count_seating = 0;	
			$purchase_info->discount_seating = 0;
		}
		
			
// Alex 122711
/*		$sales = $wpdb->get_row( 'SELECT count(*) AS count,SUM(totalprice) AS total, AVG(track_id) AS aver FROM ' . $wpdb->prefix . 'wpsc_purchase_logs WHERE processed IN (5) AND date >= ' . $start_date . ' AND date <=' . $end_date );
	
		
		if ( $sales ) {
			$purchase_info->total_closed = $sales->total;
			$purchase_info->count_closed = $sales->count;
			$purchase_info->average_closed = $sales->aver;
			
			if ( $purchase_info->count_closed ) {
				$total_days = ( $end_date - $start_date ) / 86400;	
				if ( $total_days ) {
					$purchase_info->amount_per_day_closed = $purchase_info->total_closed / $total_days;
				}
			}
		} else {
			$purchase_info->total_closed = 0;
			$purchase_info->count_closed = 0;	
			$purchase_info->average_closed = 0;	
				
		}
*/
// Alex 122711 - end

		return $purchase_info;		
		
	}		
	
	// This is used to populate current orders on Piggy 		
	function get_sales_between_dates( $start_date, $end_date, $name = false ) {
		global $wpdb;

// Alex 030812
		$actual_start_date = $wpdb->get_row( 'SELECT date FROM ' . $wpdb->prefix . 'wpsc_purchase_logs WHERE date >= ' . $start_date . ak_where(). ' ORDER BY date ASC LIMIT 1' );
		if ( $actual_start_date ) {
			$start_date = $actual_start_date->date;
		}

// Alex 030812	
		$actual_end_date = $wpdb->get_row( 'SELECT date FROM ' . $wpdb->prefix . 'wpsc_purchase_logs WHERE date <= ' . $end_date . ak_where() .' ORDER BY date DESC LIMIT 1' );
		if ( $actual_end_date ) {
			$end_date = $actual_end_date->date;
		}
					
		$purchase_info = array();
	

//Alex 122711		
//		$sql = 'SELECT * FROM ' . $wpdb->prefix . 'wpsc_purchase_logs WHERE processed IN (2,3,4,5) AND date >= ' . $start_date . ' AND date <= ' . $end_date . ' ORDER BY date ASC'; // Alex: 121911 - DESC

// Alex 122811
//		$sql = 'SELECT * FROM ' . $wpdb->prefix . 'wpsc_purchase_logs WHERE processed IN (5) AND date >= ' . $start_date . ' AND date <= ' . $end_date . ' ORDER BY date ASC'; // Alex: 121911 - DESC

// Alex 030812
		$sql = 'SELECT * FROM ' . $wpdb->prefix . 'wpsc_purchase_logs WHERE processed IN (2,3,4) AND date >= ' . $start_date . ' AND date <= ' . $end_date . ak_where() .' ORDER BY date ASC'; // Alex: 121911 - DESC
		
		// Alex:
		//echo "DB Name: ". $wpdb->prefix . 'wpsc_purchase_logs';
		
// echo "\n".$sql."\n";		
		
		$sales = $wpdb->get_results( $sql );

		if ( $sales ) {
			foreach( $sales as $sale ) {			
				$info = new stdClass;
			
				$info->date = $sale->date;
				$info->total_price = 0;				
				$info->id = $sale->id;
				$info->sales = array();
				$info->processed = $sale->processed;
				$info->statusno = $sale->statusno;
//				$info->engravetext = $sale->engravetext; // 030612 - engravetexts stores barID (admin userID)
				$info->seating = $sale->shipping_region;	// Alex 050912 - Table number
				
				// Alex 032612
				$info->discount = $sale->discount_data;
				$info->notes = $sale->notes;
				
				// Alex 070112
				$info->gratuity = $sale->base_shipping;


// Alex 122711

// Alex 122711 - end
			
		// Alex:
		// Table: Users or usermeta
		// 011912 - added user_login

	// Alex 030812 - no changes here. This is USERS table	
		$sql_ak1 = 'SELECT user_login, display_name, user_email FROM '. $wpdb->prefix .'users WHERE ID ='. $sale->user_ID;		
		$user_display_name = $wpdb->get_row( $sql_ak1 );
//		echo " // ID:" . $sale->user_ID . " ";
//		echo "display_name: ". $user_display_name->display_name;
		$info->user_ID = $user_display_name->display_name;
		$info->user_email = $user_display_name->user_email;
		$info->user_login = $user_display_name->user_login;
	
///		$sql_ak2 = 'SELECT meta_key, meta_value FROM '. $wpdb->prefix .'usermeta WHERE user_id ='. $sale->user_ID . ' AND meta_key = \'custom_field_2\'';			
//		echo "\n". $sql_ak2 . "\n";
//		$info->user_ID = $user_meta_name->first_name . $user_meta_name->last_name;

///		$user_meta_name = $wpdb->get_row( $sql_ak2 );
///		$info->user_phone = $user_meta_name->meta_value;

	// Alex 030812 - no changes here. This is USERMETA data		
		$sql_ak3 = 'SELECT * FROM '. $wpdb->prefix .'usermeta WHERE user_id ='. $sale->user_ID;			

// echo "\n".$sql_ak3."\n";
		
		$user_meta_data = $wpdb->get_results( $sql_ak3 );
		
		foreach($user_meta_data as $user_meta_pair) {
			
			switch ($user_meta_pair->meta_key) {
				
			case ('custom_field_2'):
					$info->user_phone = $user_meta_pair->meta_value;
					break;
			case ('first_name'):
					$info->user_first_name = $user_meta_pair->meta_value;
					break;
			case ('last_name'):
					$info->user_last_name = $user_meta_pair->meta_value;
					break;
			case ('nickname'):
					$info->user_nickname = $user_meta_pair->meta_value;
					break;
												
			} // switch
			
			
		} // foreach
		// Alex: end
	
		// Alex 030812 - no changes here. This is cart contents
				$sql = 'SELECT * FROM ' . $wpdb->prefix . 'wpsc_cart_contents WHERE purchaseid = ' . $sale->id;
				$these_sales = $wpdb->get_results( $sql );
				
				if ( $these_sales ) {
					foreach( $these_sales as $this_sale ) {
						$one_sale = new stdClass;
						$one_sale->product = $this_sale->name;
						$one_sale->value = $this_sale->price;
						$one_sale->quantity = $this_sale->quantity;
						$info->total_price = $info->total_price + $one_sale->value*$one_sale->quantity;  // 121911 Alex: * quantity
						
						$info->sales[] = $one_sale;
					}
				}
				
				$purchase_info[] = $info;
			}
		} 

		return $purchase_info;		
	}	

	// This is used to populate the 'Best Sellers' area
	function get_product_summary_between_dates( $start_date, $end_date, $name = false ) {
		global $wpdb;
		$purchases = array();

// Alex 030812		
		$actual_start_date = $wpdb->get_row( 'SELECT date FROM ' . $wpdb->prefix . 'wpsc_purchase_logs WHERE date >= ' . $start_date . ak_where().' ORDER BY date ASC LIMIT 1' );
		if ( $actual_start_date ) {
			$start_date = $actual_start_date->date;
		}

// Alex 030812		
		$actual_end_date = $wpdb->get_row( 'SELECT date FROM ' . $wpdb->prefix . 'wpsc_purchase_logs WHERE date <= ' . $end_date . ak_where().' ORDER BY date DESC LIMIT 1' );
		if ( $actual_end_date ) {
			$end_date = $actual_end_date->date;
		}
		
		$purchase_info = new stdClass;
		
		$extra_where = '';
		if ( $name ) {
			$extra_where = ' AND b.name = \'' . $name . '\'';	
		}                

// Alex 030812 - ToDo: double-check this ak_where() condition
// Alex 031112 - Question: should change to "totalprice - base_shipping" here?
	// Alex 050812 - Changed to: totalprice- base_shipping + discount_value
// Alex 031812 - added ak_exclude as a place holder for later use
		$ak_exclude = " ";	

		$sales = $wpdb->get_results( 'SELECT SUM(totalprice- base_shipping + discount_value) AS p,count(*) AS c,name FROM ' . $wpdb->prefix . 'wpsc_purchase_logs AS a INNER JOIN ' . $wpdb->prefix . 'wpsc_cart_contents AS b ON a.id = b.purchaseid WHERE processed IN (2,3,4) AND date >= ' . $start_date . ' AND date <=' . $end_date . ak_where() . $ak_exclude . $extra_where . ' GROUP BY name ORDER BY p DESC LIMIT 10' ); 

// Alex 122711 - ignore non-closed sales		
//		$sales = $wpdb->get_results( 'SELECT SUM(totalprice) AS p,count(*) AS c,name FROM ' . $wpdb->prefix . 'wpsc_purchase_logs AS a INNER JOIN ' . $wpdb->prefix . 'wpsc_cart_contents AS b ON a.id = b.purchaseid WHERE processed IN (5) AND date >= ' . $start_date . ' AND date <=' . $end_date . $extra_where . ' GROUP BY name ORDER BY p DESC LIMIT 10' ); 

		
		if ( $sales ) {
			foreach( $sales as $sale ) {
				$purchase_info = new stdClass;
		
				$purchase_info->total = $sale->p;
				$purchase_info->count = $sale->c;
		
				$purchases[ $sale->name ] = $purchase_info;
			}	
		}
		
		return $purchases;
	}
	
	function get_last_purchase_hash() {
		global $wpdb;
		
	// Alex 030812	
		$result = $wpdb->get_row( 'SELECT date FROM ' . $wpdb->prefix . 'wpsc_purchase_logs WHERE processed = 2'.ak_where().' ORDER BY date DESC LIMIT 1' );		
		if ( $result ) {
			return $result->date;	
		} else {
			return 0;
		}
	}
}

		
add_action( 'wpsc_transaction_result_cart_item', 'piggy_wpec_txn_result' );

function piggy_wpec_txn_result( $cart_data ) {
	$settings = piggy_get_settings();
	
	if ( $cart_data['purchase_log']['email_sent'] == 0 ) {
		piggy_send_notification_message( '$' . $cart_data['cart_item']['price'] . ' - ' . $cart_data['cart_item']['name'] );
	}
}

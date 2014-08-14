<?php

require_once( 'base-helper.php' );

class ShoppHelper extends BaseCommerceHelper {
	function ShoppHelper() {
		parent::BaseCommerceHelper( 'Shopp' );
	}
	
	function is_detected() {
		global $wpdb;
		
		$result = @$wpdb->get_results( 'DESCRIBE ' . $wpdb->prefix . 'shopp_purchase' );
		if ( $result ) {
			if ( is_array( $result ) ) {
				return true;
			}
		}
		
		return false;
	}	

	// This is used for the "Current" summary sections, near the top	
	function get_summary_between_dates( $start_date, $end_date ) {
		global $wpdb;
		
		$actual_start_date = $wpdb->get_row( "SELECT created FROM " . $wpdb->prefix . "shopp_purchase WHERE date >= '" . piggy_mysql_time_from_gmt_timestamp( $start_date ) . "' ORDER BY created ASC LIMIT 1" );
		if ( $actual_start_date ) {
			$start_date = $actual_start_date->created;
		}
	
		$actual_end_date = $wpdb->get_row( "SELECT created FROM " . $wpdb->prefix . "shopp_purchase WHERE date <= '" . piggy_mysql_time_from_gmt_timestamp( $end_date ) . "' ORDER BY created DESC LIMIT 1" );
		if ( $actual_end_date ) {
			$end_date = $actual_end_date->created;
		}	
		
		$purchase_info = new stdClass;
		$purchase_info->total = 0;
		$purchase_info->count = 0;

		$result = $wpdb->get_row( "SELECT SUM(subtotal) AS total, SUM(discount) as discount, COUNT(*) AS count FROM " . $wpdb->prefix . "shopp_purchase WHERE created >= '" . piggy_mysql_time_from_gmt_timestamp( $start_date ) . "' AND created <= '" . piggy_mysql_time_from_gmt_timestamp( $end_date ) . "'" );
		if ( $result ) {
			if ( $result->total ) {
				$purchase_info->total = $result->total;
			}
			
			if ( $result->count ) {
				$purchase_info->count = $result->count;
			}
			
			if ( $purchase_info->count ) {
				$total_days = ( $end_date - $start_date ) / 86400;	
				if ( $total_days ) {
					$purchase_info->amount_per_day = $purchase_info->total / $total_days;
				}
			}			
		}
		
		return $purchase_info;
	}			
		
	// This is used to popular the 'Today' information area	
	function get_sales_between_dates( $start_date, $end_date, $name = false ) {		
		global $wpdb;
		$purchases = array();
		
		$actual_start_date = $wpdb->get_row( "SELECT created FROM " . $wpdb->prefix . "shopp_purchase WHERE date >= '" . piggy_mysql_time_from_gmt_timestamp( $start_date ) . "' ORDER BY created ASC LIMIT 1" );
		if ( $actual_start_date ) {
			$start_date = $actual_start_date->created;
		}
	
		$actual_end_date = $wpdb->get_row( "SELECT created FROM " . $wpdb->prefix . "shopp_purchase WHERE date <= '" . piggy_mysql_time_from_gmt_timestamp( $end_date ) . "' ORDER BY created DESC LIMIT 1" );
		if ( $actual_end_date ) {
			$end_date = $actual_end_date->created;
		}		

		$sql = "SELECT id,total,subtotal,tax,created FROM " . $wpdb->prefix . "shopp_purchase WHERE created >= '" . piggy_mysql_time_from_gmt_timestamp( $start_date ) . "' AND created <= '" . piggy_mysql_time_from_gmt_timestamp( $end_date ) . "'";
		
		$sales = $wpdb->get_results( $sql );	
		if ( $sales ) {
			foreach( $sales as $sale ) {
				$purchase_info = new stdClass;
		
				$purchase_info->total_price = $sale->subtotal;
				$purchase_info->date = strtotime( $sale->created . ' GMT' );
				$purchase_info->sales = array();
				$purchase_info->id = $sale->id;
				
				$where_clause = '';	
				if ( $name ) {
					if ( preg_match( '#(.*) \((.*)\)#', $name, $matches ) ) {
						$where_clause = " AND optionlabel = ' . $matches[2] . '";
					}
				}
										
				$cart_items = $wpdb->get_results( $wpdb->prepare( "SELECT total,quantity,price,name,optionlabel,created FROM " . $wpdb->prefix . "shopp_purchased WHERE purchase = %d" . $where_clause, $sale->id ) );
				if ( $cart_items ) {
					foreach( $cart_items as $cart_item ) {
						$one_sale = new stdClass;
						$one_sale->product = $cart_item->name;
						if ( $cart_item->optionlabel ) {
							$one_sale->product = $one_sale->product . ' (' . $cart_item->optionlabel . ')';
						}
						
						$one_sale->value = $cart_item->total;
						$one_sale->quantity = $cart_item->quantity;
						
						$purchase_info->sales[] = $one_sale;
					}
				}
				

				$purchases[] = $purchase_info;
			}		
		}
	
		return $purchases;
	}	
	
	// This is used to populate the 'Best Sellers' area
	function get_product_summary_between_dates( $start_date, $end_date, $name = false ) {
		global $wpdb;
		$purchases = array();
		
		$actual_start_date = $wpdb->get_row( "SELECT created FROM " . $wpdb->prefix . "shopp_purchased WHERE date >= '" . piggy_mysql_time_from_gmt_timestamp( $start_date ) . "' ORDER BY created ASC LIMIT 1" );
		if ( $actual_start_date ) {
			$start_date = $actual_start_date->created;
		}
	
		$actual_end_date = $wpdb->get_row( "SELECT created FROM " . $wpdb->prefix . "shopp_purchased WHERE date <= '" . piggy_mysql_time_from_gmt_timestamp( $end_date ) . "' ORDER BY created DESC LIMIT 1" );
		if ( $actual_end_date ) {
			$end_date = $actual_end_date->created;
		}	
		
		$where_clause = '';	
		if ( $name ) {
			if ( preg_match( '#(.*) \((.*)\)#', $name, $matches ) ) {
				$where_clause = " WHERE optionlabel = ' . $matches[2] . '";
			}
		}
		
		$sales = $wpdb->get_results( "SELECT SUM(total) as total, SUM(quantity) AS count,name,optionlabel FROM " . $wpdb->prefix . "shopp_purchased WHERE created >= '" . piggy_mysql_time_from_gmt_timestamp( $start_date ) . "' AND created <= '" . piggy_mysql_time_from_gmt_timestamp( $end_date ) . $where_clause . "' GROUP by name, optionlabel ORDER BY total DESC LIMIT 10" );
		
		if ( $sales ) {
			foreach( $sales as $sale ) {
				$purchase_info = new stdClass;
		
				$purchase_info->total = $sale->total;
				$purchase_info->count = $sale->count;
		
				if ( $sale->optionlabel ) {
					$name = $sale->name . ' (' . $sale->optionlabel . ')';
				} else {
					$name = $sale->name;	
				}
				$purchases[ $name ] = $purchase_info;
			}		
		}

		return $purchases;
	}
}

// For real time notifications
add_action( 'shopp_order_success', 'piggy_shopp_payment_order_success' );

function piggy_shopp_payment_order_success( $purchase ) {
	if ( isset( $purchase->purchased ) ) {
		foreach( $purchase->purchased as $purchase ) {
			$product_name = $purchase->name;
		
			if ( isset( $purchase->optionlabel ) && strlen( $purchase->optionlabel ) ) {
				$product_name = $product_name . ' (' . $purchase->optionlabel . ')';
			}

			piggy_send_notification_message( '$' . number_format( $purchase->total, 2 ) . ' - ' . $product_name );
		}	
	}
}

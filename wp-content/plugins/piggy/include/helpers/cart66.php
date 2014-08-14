<?php

require_once( 'base-helper.php' );

class Cart66Helper extends BaseCommerceHelper {
	function Cart66Helper() {
		parent::BaseCommerceHelper( 'Cart66' );
	}
	
	function is_detected() {
		global $wpdb;
		
		$result = @$wpdb->get_results( 'DESCRIBE ' . $wpdb->prefix . 'cart66_orders' );
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
		
		$actual_start_date = $wpdb->get_row( "SELECT ordered_on FROM " . $wpdb->prefix . "cart66_orders WHERE ordered_on >= '" . piggy_mysql_time_from_gmt_timestamp( $start_date ) . "' ORDER BY ordered_on ASC LIMIT 1" );
		if ( $actual_start_date ) {
			$start_date = $actual_start_date->ordered_on;
		} else {
			$start_date = piggy_mysql_time_from_gmt_timestamp( $start_date );
		} 	
		
		$actual_end_date = $wpdb->get_row( "SELECT ordered_on FROM " . $wpdb->prefix . "cart66_orders WHERE ordered_on <= '" . piggy_mysql_time_from_gmt_timestamp( $end_date ) . "' ORDER BY ordered_on DESC LIMIT 1" );
		if ( $actual_end_date ) {
			$end_date = $actual_end_date->ordered_on;
		} else {
			$end_date = piggy_mysql_time_from_gmt_timestamp( $end_date );
		}
		
		$purchase_info = new stdClass;
		$purchase_info->total = 0;
		$purchase_info->count = 0;
		
		$result = $wpdb->get_row( "SELECT SUM(total) AS total, COUNT(*) AS count FROM " . $wpdb->prefix . "cart66_orders WHERE ordered_on >= '" . $start_date . "' AND ordered_on <= '" . $end_date . "'" );
		
		if ( $result ) {
			$purchase_info->count = $result->count;
				
			if ( $result->total ) {
				$purchase_info->total = $result->total;
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
		
		$actual_start_date = $wpdb->get_row( "SELECT ordered_on FROM " . $wpdb->prefix . "cart66_orders WHERE ordered_on >= '" . piggy_mysql_time_from_gmt_timestamp( $start_date ) . "' ORDER BY ordered_on ASC LIMIT 1" );
		if ( $actual_start_date ) {
			$start_date = $actual_start_date->ordered_on;
		} else {
			$start_date = piggy_mysql_time_from_gmt_timestamp( $start_date );
		} 	
		
		$actual_end_date = $wpdb->get_row( "SELECT ordered_on FROM " . $wpdb->prefix . "cart66_orders WHERE ordered_on <= '" . piggy_mysql_time_from_gmt_timestamp( $end_date ) . "' ORDER BY ordered_on DESC LIMIT 1" );
		if ( $actual_end_date ) {
			$end_date = $actual_end_date->ordered_on;
		} else {
			$end_date = piggy_mysql_time_from_gmt_timestamp( $end_date );
		}
	
		// Alex: adding user_ID 	
		$sql = "SELECT id,total,ordered_on FROM " . $wpdb->prefix . "cart66_orders WHERE ordered_on >= '" . $start_date . "' AND ordered_on <= '" . $end_date . "' ORDER BY ordered_on DESC" ;
		$sales = $wpdb->get_results( $sql );
		
		if ( $sales ) {
			foreach( $sales as $sale ) {
				$purchase_info = new stdClass;
		
				$purchase_info->total_price = $sale->total;
				$purchase_info->date = strtotime( $sale->ordered_on . ' GMT' );
				$purchase_info->sales = array();
				$purchase_info->id = $sale->id;
				
			// Alex: adding user_ID
			//$purchase_info->user_ID	= 11; // $sale->user_ID;
	
				$cart_items = $wpdb->get_results( $wpdb->prepare( "SELECT quantity,product_price,description FROM " . $wpdb->prefix . "cart66_order_items WHERE order_id = %d", $sale->id ) );
				if ( $cart_items ) {
					foreach( $cart_items as $cart_item ) {
						$one_sale = new stdClass;
						$one_sale->product = $cart_item->description;
						$one_sale->value = $cart_item->product_price;
						$one_sale->quantity = $cart_item->quantity;
						
						$purchase_info->sales[] = $one_sale;
					}
				}

				$purchases[] = $purchase_info;
			}		
		}
		
		return $purchases;
	}	

	// This is used for the Product 'Best Seller's area
	function get_product_summary_between_dates( $start_date, $end_date, $name = false ) {
		global $wpdb;
		$purchases = array();
		
		$actual_start_date = $wpdb->get_row( "SELECT ordered_on FROM " . $wpdb->prefix . "cart66_orders WHERE ordered_on >= '" . piggy_mysql_time_from_gmt_timestamp( $start_date ) . "' ORDER BY ordered_on ASC LIMIT 1" );
		if ( $actual_start_date ) {
			$start_date = $actual_start_date->ordered_on;
		} else {
			$start_date = piggy_mysql_time_from_gmt_timestamp( $start_date );
		} 	
		
		$actual_end_date = $wpdb->get_row( "SELECT ordered_on FROM " . $wpdb->prefix . "cart66_orders WHERE ordered_on <= '" . piggy_mysql_time_from_gmt_timestamp( $end_date ) . "' ORDER BY ordered_on DESC LIMIT 1" );
		if ( $actual_end_date ) {
			$end_date = $actual_end_date->ordered_on;
		} else {
			$end_date = piggy_mysql_time_from_gmt_timestamp( $end_date );
		}
		
		if ( $name ) {
			$where_clause = $wpdb->prepare( " AND description = %s", $name );
		} else {
			$where_clause = '';	
		}
		
		$result = $wpdb->get_results( "SELECT product_id, description, SUM(product_price) as total, SUM(quantity) as count FROM " . $wpdb->prefix . "cart66_order_items WHERE order_id IN (SELECT id FROM " . $wpdb->prefix . "cart66_orders WHERE ordered_on >= '" . $start_date . "' AND ordered_on <= '" . $end_date . "') " . $where_clause . " GROUP BY description ORDER BY total DESC" );
		
		if ( $result ) {
			foreach( $result as $product ) {
				$purchase_info = new stdClass;
		
				$purchase_info->total = $product->total;
				$purchase_info->count = $product->count;
				
				$purchases[ $product->description ] = $purchase_info;
			}
		}

		return $purchases;
	}
}

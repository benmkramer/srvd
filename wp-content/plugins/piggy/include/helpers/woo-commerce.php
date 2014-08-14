<?php

require_once( 'base-helper.php' );

class WooCommerceHelper extends BaseCommerceHelper {
	function WooCommerceHelper() {
		parent::BaseCommerceHelper( 'WooCommerce' );
	}
	
	function is_detected() {
		return function_exists( 'woocommerce_init' );
	}		
	
	function get_items_for_post( $post_id ) {
		$items = get_post_meta( $post_id, '_order_items', true );
		
		return $items;
	}
	
	function get_total_amount_of_order( $post_id ) {
		$amount = get_post_meta( $post_id, '_order_total', true );
		
		return $amount;
	}
		
	// This is used for the "Current" summary sections, near the top		
	function get_summary_between_dates( $start_date, $end_date ) {
		global $wpdb;
		
		$actual_start_date = $wpdb->get_row( "SELECT post_date_gmt FROM " . $wpdb->prefix . "posts WHERE post_type = 'shop_order' AND post_date_gmt >= '" . piggy_mysql_time_from_gmt_timestamp( $start_date ) . "' ORDER BY post_date_gmt ASC LIMIT 1" );
		
		if ( $actual_start_date ) {
			$start_date = $actual_start_date->post_date_gmt;
		} else {
			$start_date = piggy_mysql_time_from_gmt_timestamp( $start_date );
		}
		
		$actual_end_date = $wpdb->get_row( "SELECT post_date_gmt FROM " . $wpdb->prefix . "posts WHERE post_type = 'shop_order' AND post_date_gmt <= FROM_UNIXTIME(" . $end_date . ") ORDER BY post_date_gmt DESC LIMIT 1" );
		if ( $actual_end_date ) {
			$end_date = $actual_end_date->post_date_gmt;
		} else {
			$end_date = piggy_mysql_time_from_gmt_timestamp( $end_date );
		}
		
		$purchase_info = new stdClass;
		$purchase_info->total = 0;
		$purchase_info->count = 0;
		
		$results = $wpdb->get_results( "SELECT ID FROM " . $wpdb->prefix . "posts WHERE post_type = 'shop_order' AND post_date_gmt >= '" . $start_date . "' AND post_date_gmt <= '" . $end_date . "'" );
		if ( $results ) {
			foreach( $results as $post ) {
				$purchase_info->count++;
				$purchase_info->total = $purchase_info->total + $this->get_total_amount_of_order( $post->ID );
			}
			
			if ( $purchase_info->count ) {
				$total_days = ( strtotime( $end_date ) - strtotime( $start_date ) ) / 86400;	
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
		
		$actual_start_date = $wpdb->get_row( "SELECT post_date_gmt FROM " . $wpdb->prefix . "posts WHERE post_type = 'shop_order' AND post_date_gmt >= '" . piggy_mysql_time_from_gmt_timestamp( $start_date ) . "' ORDER BY post_date_gmt ASC LIMIT 1" );
		
		if ( $actual_start_date ) {
			$start_date = $actual_start_date->post_date_gmt;
		} else {
			$start_date = piggy_mysql_time_from_gmt_timestamp( $start_date );
		}
		
		$actual_end_date = $wpdb->get_row( "SELECT post_date_gmt FROM " . $wpdb->prefix . "posts WHERE post_type = 'shop_order' AND post_date_gmt <= FROM_UNIXTIME(" . $end_date . ") ORDER BY post_date_gmt DESC LIMIT 1" );
		if ( $actual_end_date ) {
			$end_date = $actual_end_date->post_date_gmt;
		} else {
			$end_date = piggy_mysql_time_from_gmt_timestamp( $end_date );
		}
		
		$results = $wpdb->get_results( "SELECT ID,post_date_gmt FROM " . $wpdb->prefix . "posts WHERE post_type = 'shop_order' AND post_date_gmt >= '" . $start_date . "' AND post_date_gmt <= '" . $end_date . "'" );
		if ( $results ) {
			foreach( $results as $post ) {
				$purchase_info = new stdClass;
		
				$purchase_info->total_price = 99; //$this->get_total_amount_of_order( $post->ID );
				$purchase_info->date = strtotime( $post->post_date_gmt . ' GMT' );
				$purchase_info->sales = array();
				$purchase_info->id = $post->ID;	
				
				$items = $this->get_items_for_post( $post->ID );
				if ( $items ) {
					print_r( $items );
					foreach( $items as $item ) {
						$one_sale = new stdClass;
						$one_sale->product = $item['name'];
						
						if ( isset( $item['item_meta'] ) && count( $item['item_meta'] ) ) {
							$meta_array = array();
							foreach( $item['item_meta'] as $key => $value ) {
								$meta_array[] = $value;
							}
							
							$one_sale->product = $one_sale->product . ' (' . implode( ', ', $meta_array ) . ')';
						}
						
						$one_sale->value = $item['cost'];
						$one_sale->quantity = $item['qty'];
						
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
		
		$actual_start_date = $wpdb->get_row( "SELECT post_date_gmt FROM " . $wpdb->prefix . "posts WHERE post_type = 'shop_order' AND post_date_gmt >= '" . piggy_mysql_time_from_gmt_timestamp( $start_date ) . "' ORDER BY post_date_gmt ASC LIMIT 1" );
		
		if ( $actual_start_date ) {
			$start_date = $actual_start_date->post_date_gmt;
		} else {
			$start_date = piggy_mysql_time_from_gmt_timestamp( $start_date );
		}
		
		$actual_end_date = $wpdb->get_row( "SELECT post_date_gmt FROM " . $wpdb->prefix . "posts WHERE post_type = 'shop_order' AND post_date_gmt <= FROM_UNIXTIME(" . $end_date . ") ORDER BY post_date_gmt DESC LIMIT 1" );
		if ( $actual_end_date ) {
			$end_date = $actual_end_date->post_date_gmt;
		} else {
			$end_date = piggy_mysql_time_from_gmt_timestamp( $end_date );
		}
		
		$results = $wpdb->get_results( "SELECT ID FROM " . $wpdb->prefix . "posts WHERE post_type = 'shop_order' AND post_date_gmt >= '" . $start_date . "' AND post_date_gmt <= '" . $end_date . "'" );
		
		$products = array();
		if ( $results ) {
			foreach( $results as $result ) {
				$items = $this->get_items_for_post( $result->ID );
				
				if ( $items ) {
					foreach( $items as $item ) {
						if ( $name ) {
							if ( $item['name' ] != $name ) {
								continue;
							}
						}
						
						if ( !isset( $products[ $item['name'] ] ) ) {
							$products[ $item['name'] ] = new stdClass;
						}
						
						$products[ $item['name' ] ]->count++;
						$products[ $item['name' ] ]->total += $item['cost'];
					}
				}
			}
		}
		
		return ( $products );
	}
}

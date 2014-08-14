<?php

define( 'PIGGY_SECONDS_IN_ONE_WEEK', 60*60*24*7 );
define( 'PIGGY_SKIN_COOKIE_NAME', 'piggy_skin' );

function piggy_time() {
	$settings = piggy_get_settings();
	if ( $settings->override_current_time ) {
		return $settings->override_current_time;	
	} else {
		return time();	
	}
}

function piggy_mktime() {
	$settings = piggy_get_settings();
	
	$args = array();
	for ( $i = 0; $i < func_num_args(); $i++ ) {
		$args[ $i ] = func_get_arg( $i );	
	} 
	
	if ( $settings->override_current_time ) {
		switch( func_num_args() ) {
			case 6:
				return mktime( 	
					$args[0],
					$args[1],
					$args[2],
					$args[3],
					$args[4],
					$args[5]
				);
			case 5:
				return mktime(	
					$args[0],
					$args[1],
					$args[2],
					$args[3],
					$args[4],
					date( "Y", $settings->override_current_time ) 
				);
			case 4:
				return mktime(	
					$args[0],
					$args[1],
					$args[2],
					$args[3], 
					date( "j", $settings->override_current_time ), 
					date( "Y", $settings->override_current_time )
				);
			case 3:
				return mktime(	
					$args[0],
					$args[1],
					$args[2],
					date( "n", $settings->override_current_time ), 
					date( "j", $settings->override_current_time ), 
					date( "Y", $settings->override_current_time )
				);
			case 2:
				return mktime( 	
					$args[0],
					$args[1],
					date( "s", $settings->override_current_time ), 
					date( "n", $settings->override_current_time ), 
					date( "j", $settings->override_current_time ), 
					date( "Y", $settings->override_current_time ) 
				);					
			case 1:
				return mktime( 
					$args[0],
					date( "i", $settings->override_current_time ), 
					date( "s", $settings->override_current_time ), 
					date( "n", $settings->override_current_time ), 
					date( "j", $settings->override_current_time ), 
					date( "Y", $settings->override_current_time ) 
				);				
			default:
				return mktime( 	
					date( "H", $settings->override_current_time ), 
					date( "i", $settings->override_current_time ), 
					date( "s", $settings->override_current_time ), 
					date( "n", $settings->override_current_time ), 
					date( "j", $settings->override_current_time ), 
					date( "Y", $settings->override_current_time )
				);
		}		
	} else {
		switch( func_num_args() ) {
			case 6:
				return mktime( 
					$args[0],
					$args[1],
					$args[2],
					$args[3],
					$args[4],
					$args[5]
				);
			case 5:
				return mktime( 
					$args[0],
					$args[1],
					$args[2],
					$args[3],
					$args[4]
				);
			case 4:
				return mktime( 
					$args[0],
					$args[1],
					$args[2],
					$args[3]
				);
			case 3:
				return mktime( 
					$args[0],
					$args[1],
					$args[2]
				);
			case 2:
				return mktime( 
					$args[0],
					$args[1]
				);
			case 1:
				return mktime( 
					$args[0]
				);
			default:
				return mktime();	
		}
	}
}

function piggy_date( $param ) {
	$settings = piggy_get_settings();
	if ( $settings->override_current_time ) {
		return date( $param, $settings->override_current_time );
	} else {
		return date( $param );	
	}	
}

function piggy_get_settings() {
	global $piggy;
	
	return $piggy->get_settings();	
}

function piggy_string_to_class( $string ) {
	return strtolower( str_replace( '--', '-', str_replace( '+', '', str_replace( ' ', '-', $string ) ) ) );
}

function piggy_the_stylesheet_url() {
	echo piggy_get_stylesheet_url();
}

function piggy_get_stylesheet_url() {
	$minfile = PIGGY_DIR . '/css/style.min.css';
	if ( file_exists( $minfile ) ) {
		return apply_filters( 'piggy_stylesheet_url', PIGGY_URL . '/css/style.min.css' );
	} else {
		return apply_filters( 'piggy_stylesheet_url', PIGGY_URL . '/css/style.css?' . time() );	
	}
}

function piggy_get_jqtouch_stylesheet_url() {
	$minfile = PIGGY_DIR . '/css/jqtouch.min.css';
	if ( file_exists( $minfile ) ) {
		return apply_filters( 'piggy_jqtouch_stylesheet_url', PIGGY_URL . '/css/jqtouch.min.css' );
	} else {
		return apply_filters( 'piggy_jqtouch_stylesheet_url', PIGGY_URL . '/css/jqtouch.css?' . time() );	
	}
}

function piggy_get_add2home_stylesheet_url() {
	$minfile = PIGGY_DIR . '/css/add2home.min.css';
	if ( file_exists( $minfile ) ) {
		return apply_filters( 'piggy_add2home_stylesheet_url', PIGGY_URL . '/css/add2home.min.css' );
	} else {
		return apply_filters( 'piggy_add2home_stylesheet_url', PIGGY_URL . '/css/add2home.css?' . time() );	
	}
}


function piggy_get_framework_js_url() {
	$minfile = PIGGY_DIR . '/css/framework.min.css';
	if ( file_exists( $minfile ) ) {
		return apply_filters( 'piggy_framework_js_url', PIGGY_URL . '/js/framework.min.js' );
	} else {
		return apply_filters( 'piggy_framework_js_url', PIGGY_URL . '/js/framework.js?' . time() );
	}
}

function piggy_get_piggy_js_url() {
	$minfile = PIGGY_DIR . '/js/piggy.min.js';
	if ( file_exists( $minfile ) ) {
		return apply_filters( 'piggy_js_url', PIGGY_URL . '/js/piggy.min.js' );
	} else {
		return apply_filters( 'piggy_js_url', PIGGY_URL . '/js/piggy.js?' . time() );	
	}
}

function piggy_can_view_stats() {
	global $piggy;
	
	return true;
	
	$settings = $piggy->get_settings();
	
	if ( $settings->show_for_admins ) {
		return current_user_can( 'manage_options' );
	} else {
		global $current_user;
		get_currentuserinfo();
		
		$user_logins = explode( ',', $settings->show_for_users );
		if ( count( $user_logins ) ) {
			foreach( $user_logins as $user ) {
				if ( $current_user->user_login == trim( $user ) ) {
					return true;	
				}	
			}	
		}
	}
	
	return false;
}

function piggy_gmt_to_current_time( $timestamp ) {
	$t = gmdate( 'Y-m-d H:i:s', ( $timestamp - ( get_option( 'gmt_offset' ) * 3600 ) ) );
	return strtotime( $t );	
}

function piggy_current_time_to_gmt( $timestamp ) {
	$t = gmdate( 'Y-m-d H:i:s', ( $timestamp + ( get_option( 'gmt_offset' ) * 3600 ) ) );
	return strtotime( $t );	
}

function piggy_current_time_fixed() {
	$t = gmdate( 'Y-m-d H:i:s', ( piggy_time() + ( get_option( 'gmt_offset' ) * 3600 ) ) );
	return strtotime( $t );
}

function piggy_mysql_time_from_gmt_timestamp( $timestamp ) {
	return gmdate( 'Y-m-d H:i:s', $timestamp );	
}

global $piggy_data;
global $piggy_data_iterator;
global $piggy_data_item;
global $piggy_data_items_iterator;
global $piggy_data_items_item;

function piggy_populate_day_data() {
	global $piggy_data;
	
	$piggy_data = array();
	
	$total_data = array();
	$count_data = array();
	
	global $piggy;
	$wpec = piggy_get_ecommerce_helper(); 
	$year = date( 'Y', piggy_current_time_fixed() );
	$month = date( 'm', piggy_current_time_fixed() );
	for ( $i = date( 'j', piggy_current_time_fixed() ) ; $i >= 1; $i-- ) {
		$start_date = piggy_mktime( 0, 0, 0, $month, $i, $year );
		$end_date = piggy_mktime( 23, 59, 59, $month, $i, $year );
		
		$results = $wpec->get_summary_between_dates( $start_date, $end_date );
		
		$cool_day = date( 'jS', strtotime( $year . '-' . $month . '-' . $i ) );
		
		if ( $results ) {
			$total_data[ $cool_day ] = $results->total;
			$count_data[ $cool_day ] = $results->count;
		} else {
			$total_data[ $cool_day ] = 0;
			$count_data[ $cool_day ] = 0;
		}
	}
	
	$piggy_data[ __( 'Revenue', 'piggy' ) ] = $total_data;
	$piggy_data[ __( 'Sales', 'piggy' ) ] = $count_data;

	global $piggy_data_iterator;	
	global $piggy_data_items_iterator;	
	$piggy_data_iterator = $piggy_data_items_iterator = false;
} 

function piggy_populate_this_day_data() {
	global $piggy;
	global $piggy_data;
	$wpec = piggy_get_ecommerce_helper(); 
	
	$piggy_data = array();
	
	$results = $wpec->get_sales_between_dates( $piggy->date_time_helper['today'][0], $piggy->date_time_helper['today'][1] );	
	if ( $results ) {
		$piggy_data = $results;	
	}
	global $piggy_data_iterator;	
	global $piggy_data_items_iterator;	
	$piggy_data_iterator = $piggy_data_items_iterator = false;		
}

function piggy_populate_this_month_data() {
	global $piggy;
	global $piggy_data;
	$wpec = piggy_get_ecommerce_helper(); 
	
	$piggy_data = array();
	
	$month = date( 'n', piggy_mktime() );
	$day = date( 'j', piggy_mktime() );
	
	$month_breaks = array( 1, 8, 15, 22, 29 );
	$closest_seven = floor( ( $day - 1 ) / 7 );
	
	while( $closest_seven >= 0 ) {
		$start_day = 1 + $closest_seven * 7;
		$end_day = $closest_seven * 7 + 7;
		
		if ( $end_day > $day ) {
			$end_day = $day;	
		}
		
		if ( $end_day > $piggy->days_in_each_month( $month ) ) {
			$end_day = $piggy->days_in_each_month( $month );	
		}
		
		$start_date = mktime( 0, 0, 0, $month, $start_day );
		$end_date = mktime( 23, 59, 59, $month, $end_day );
		
		$results = $wpec->get_summary_between_dates( $start_date, $end_date );
		$piggy_data[ sprintf( '%s - %s', date( 'M jS', $start_date ), date( 'M jS', $end_date ) ) ] = piggy_populate_sales_data( $results->count, $results->total, 'this-week' );
		
		$closest_seven--;
	}	

	global $piggy_data_iterator;	
	global $piggy_data_items_iterator;	
	$piggy_data_iterator = $piggy_data_items_iterator = false;
} 

function piggy_populate_this_week_data() {
	global $piggy;
	global $piggy_data;
	$wpec = piggy_get_ecommerce_helper(); 
	
	$piggy_data = array();
	
	$results = $wpec->get_summary_between_dates( $piggy->date_time_helper['today'][0], $piggy->date_time_helper['today'][1] );
	$piggy_data[ __( 'Today', 'piggy' ) ] = piggy_populate_sales_data( $results->count, $results->total, 'today' );

	$day_of_week = date( 'N', $piggy->date_time_helper['today'][0] );
	$count = 1;	

	while ( $day_of_week > 1 ) {
		$start_date = $piggy->date_time_helper['today'][0] - $count*PIGGY_SECONDS_PER_DAY;
		$end_date = $piggy->date_time_helper['today'][1] - $count*PIGGY_SECONDS_PER_DAY;
		
		$results = $wpec->get_summary_between_dates( $start_date, $end_date );
		
		$name = date( 'l', $start_date );
		
		$piggy_data[ $name ] = piggy_populate_sales_data( $results->count, $results->total, 'previousdayweek-' . ( $count + 1 ) );	
		
		$day_of_week--;	
		$count++;
	}

	global $piggy_data_iterator;	
	global $piggy_data_items_iterator;	
	$piggy_data_iterator = $piggy_data_items_iterator = false;
} 

function piggy_populate_this_year_data() {
	global $piggy;
	global $piggy_data;
	$wpec = piggy_get_ecommerce_helper(); 
	
	$piggy_data = array();
	
	$results = $wpec->get_summary_between_dates( $piggy->date_time_helper['this-month'][0], $piggy->date_time_helper['this-month'][1] );
	$piggy_data[ __( 'Month', 'piggy' ) ] = piggy_populate_sales_data( $results->count, $results->total, 'this-month' );

	$month = date( 'n', $piggy->date_time_helper['this-month'][0] );
	$year = date( 'Y', $piggy->date_time_helper['this-month'][0] );
	$count = 1;	

	while ( $month > 0 ) {
		$month--;	
				
		$start_date = mktime( 0, 0, 0, $month, 1, $year );
		$end_date = mktime( 23, 59, 59, $month, $piggy->days_in_each_month( $month, $year ), $year );		
		
		$results = $wpec->get_summary_between_dates( $start_date, $end_date );
		if ( !$results || $results->count == 0 ) {
			break;	
		}
		
		$name = date( 'F Y', $start_date );
		
		$piggy_data[ $name ] = piggy_populate_sales_data( $results->count, $results->total, 'previous-month-' . ( $count + 1 ) );	
		

		$count++;
	}

	global $piggy_data_iterator;	
	global $piggy_data_items_iterator;	
	$piggy_data_iterator = $piggy_data_items_iterator = false;
}

function piggy_populate_this_all_time_data() {
	global $piggy;
	global $piggy_data;
	$wpec = piggy_get_ecommerce_helper(); 
	
	$piggy_data = array();
	
	$results = $wpec->get_summary_between_dates( $piggy->date_time_helper['this-year'][0], $piggy->date_time_helper['this-year'][1] );
	$piggy_data[ __( 'Year', 'piggy' ) ] = piggy_populate_sales_data( $results->count, $results->total, 'this-year' );

	$year = date( 'Y', $piggy->date_time_helper['this-month'][0] );
	
	$count = 1;	
	while ( true ) {
		$year--;
		
		$start_date = mktime( 0, 0, 0, 1, 1, $year );
		$end_date = mktime( 23, 59, 59, 12, 31, $year );		
		
		$results = $wpec->get_summary_between_dates( $start_date, $end_date );
		
		if ( !$results || $results->count == 0 ) {
			break;
		}
		
		$name = date( 'Y', $start_date );
		
		$piggy_data[ $name ] = piggy_populate_sales_data( $results->count, $results->total, 'previous-month-' . ( $count + 1 ) );	
			
		$count++;
	}
	
	$settings = piggy_get_settings();
	if ( $settings->offset_all_time_sales_total || $settings->offset_all_time_sales_count ) {
		$piggy_data[ __( 'Other', 'piggy' ) ] = piggy_populate_sales_data( $settings->offset_all_time_sales_count, $settings->offset_all_time_sales_total, 'other' );	
	}

	global $piggy_data_iterator;	
	global $piggy_data_items_iterator;	
	$piggy_data_iterator = $piggy_data_items_iterator = false;
}

function piggy_populate_month_data() {
	global $piggy;
	global $piggy_data;
	$wpec = piggy_get_ecommerce_helper(); 
	
	$piggy_data = array();
	
	$total_data = array();
	$count_data = array();
	
	$results = $wpec->get_summary_between_dates( $piggy->date_time_helper['this-month'][0], $piggy->date_time_helper['this-month'][1] );
	$total_data[ __( 'Month', 'piggy' ) ] = $results->total;
	$count_data[ __( 'Month', 'piggy' ) ] = $results->count;	
	
	$year = date( 'Y', piggy_current_time_fixed() );
	$month = date( 'm', piggy_current_time_fixed() );	
	
	for ( $i = 1 ; $i < 8; $i++) {
		$month = $month - 1;
		if ( $month == 0 ) {
			$month = 12;
			$year = $year - 1;
		}
		
		$start_date = mktime( 0, 0, 0, $month, 1, $year );
		$end_date = mktime( 23, 59, 59, $month, $piggy->days_in_each_month( $month, $year ), $year );
		
		$results = $wpec->get_summary_between_dates( $start_date, $end_date );
		
		$name = date( 'F Y', $start_date );
		
		$total_data[ $name ] = $results->total;
		$count_data[ $name ] = $results->count;			
	}
	
	$piggy_data[ __( 'Revenue', 'piggy' ) ] = $total_data;
	$piggy_data[ __( 'Sales', 'piggy' ) ] = $count_data;

	global $piggy_data_iterator;	
	global $piggy_data_items_iterator;	
	$piggy_data_iterator = $piggy_data_items_iterator = false;
}

function piggy_populate_sales_data( $sales, $value, $class_name ) {	
	$this_data = new stdClass;
	$this_data->sales = $sales;
	$this_data->value = $value;
	if ( $sales ) {
		$this_data->average_sale_price = $value / $sales;
	} else {
		$this_data->average_sale_price = 0;
	}
	
	$this_data->class_name = $class_name;
	
	return $this_data;	
}

function piggy_populate_overview_data() {
	global $piggy;
	global $piggy_data;
	$wpec = piggy_get_ecommerce_helper(); 
	
	$piggy_data = array();
	
	/* Data for 'Today' */
	$results = $wpec->get_summary_between_dates( $piggy->date_time_helper['today'][0], $piggy->date_time_helper['today'][1] );
	$piggy_data[ __( 'Today', 'piggy' ) ] = piggy_populate_sales_data( $results->count, $results->total, 'today' );
	
	/* Data for 'Week' */
	$results = $wpec->get_summary_between_dates( $piggy->date_time_helper['this-week'][0], $piggy->date_time_helper['this-week'][1] );
	$piggy_data[ __( 'Week', 'piggy' ) ] = piggy_populate_sales_data( $results->count, $results->total, 'this-week' );
	
	/* Data for 'Month' */
	$results = $wpec->get_summary_between_dates( $piggy->date_time_helper['this-month'][0], $piggy->date_time_helper['this-month'][1] );
	$piggy_data[ __( 'Month', 'piggy' ) ] = piggy_populate_sales_data( $results->count, $results->total, 'this-month' );
	
	/* Data for 'Year' */
	$results = $wpec->get_summary_between_dates( $piggy->date_time_helper['this-year'][0], $piggy->date_time_helper['this-year'][1] );
	$piggy_data[ __( 'Year', 'piggy' ) ] = piggy_populate_sales_data( $results->count, $results->total, 'this-year' );	
	
	/* Data for 'All Time' */
	$results = $wpec->get_summary_between_dates( 0, $piggy->date_time_helper['today'][1] );
	
	$settings = piggy_get_settings();
	
	if ( $settings->offset_all_time_sales_total || $settings->offset_all_time_sales_count ) {
		$piggy_data[ __( 'All Time', 'piggy' ) ] = piggy_populate_sales_data( $results->count + $settings->offset_all_time_sales_count, $results->total + $settings->offset_all_time_sales_total, 'all-time' );			
	} else {
		$piggy_data[ __( 'All Time', 'piggy' ) ] = piggy_populate_sales_data( $results->count, $results->total, 'all-time' );	
	}
	
	global $piggy_data_iterator;	
	global $piggy_data_items_iterator;	
	$piggy_data_iterator = $piggy_data_items_iterator = false;
} 


function piggy_populate_projected_data() {
	global $piggy;
	global $piggy_data;
	$wpec = piggy_get_ecommerce_helper(); 
	
	$piggy_data = array();
	
	$now = piggy_mktime();
	
	/* Data for 'Today' */
	$results = $wpec->get_summary_between_dates( $piggy->date_time_helper['today'][0], $piggy->date_time_helper['today'][1] );
	$projected_ratio = ( $piggy->date_time_helper['today'][1] - $piggy->date_time_helper['today'][0] ) / ( $now - $piggy->date_time_helper['today'][0] );
	$sales_data = piggy_populate_sales_data( floor( $results->count * $projected_ratio + 0.5 ), $results->total * $projected_ratio, 'today' );
	$old_results = $wpec->get_summary_between_dates( $piggy->date_time_helper['yesterday'][0], $piggy->date_time_helper['yesterday'][1] );
	if ( ( $results->total * $projected_ratio ) > $old_results->total  ) {
		$sales_data->inc = true;
	} else {
		$sales_data->inc = false;
	}
	$piggy_data[ __( 'Today', 'piggy' ) ] = $sales_data;
	
	
	
	
	/* Data for 'Week' */
	$results = $wpec->get_summary_between_dates( $piggy->date_time_helper['this-week'][0], $piggy->date_time_helper['this-week'][1] );
	$projected_ratio = ( $piggy->date_time_helper['this-week'][1] - $piggy->date_time_helper['this-week'][0] ) / ( $now - $piggy->date_time_helper['this-week'][0] );	
	$sales_data = piggy_populate_sales_data( floor( $results->count * $projected_ratio + 0.5 ), $results->total * $projected_ratio, 'this-week' );
	$old_results = $wpec->get_summary_between_dates( $piggy->date_time_helper['last-week'][0], $piggy->date_time_helper['last-week'][1] );
	if ( ( $results->total * $projected_ratio ) > $old_results->total  ) {
		$sales_data->inc = true;
	} else {
		$sales_data->inc = false;
	}
			
	$piggy_data[ __( 'Week', 'piggy' ) ] = $sales_data;
	
	/* Data for 'Month' */
	$results = $wpec->get_summary_between_dates( $piggy->date_time_helper['this-month'][0], $piggy->date_time_helper['this-month'][1] );
	$projected_ratio = ( $piggy->date_time_helper['this-month'][1] - $piggy->date_time_helper['this-month'][0] ) / ( $now - $piggy->date_time_helper['this-month'][0] );	
	$sales_data = piggy_populate_sales_data( floor( $results->count * $projected_ratio + 0.5 ), $results->total * $projected_ratio, 'this-month' );
	$old_results = $wpec->get_summary_between_dates( $piggy->date_time_helper['last-month'][0], $piggy->date_time_helper['last-month'][1] );
	if ( ( $results->total * $projected_ratio ) > $old_results->total  ) {
		$sales_data->inc = true;
	} else {
		$sales_data->inc = false;
	}
		
	$piggy_data[ __( 'Month', 'piggy' ) ] = $sales_data;
	
	/* Data for 'Year' */
	$results = $wpec->get_summary_between_dates( $piggy->date_time_helper['this-year'][0], $piggy->date_time_helper['this-year'][1] );
	$projected_ratio = ( $piggy->date_time_helper['this-year'][1] - $piggy->date_time_helper['this-year'][0] ) / ( $now - $piggy->date_time_helper['this-year'][0] );		
	$sales_data = piggy_populate_sales_data( floor( $results->count * $projected_ratio + 0.5 ), $results->total * $projected_ratio, 'this-year' );	
	$old_results = $wpec->get_summary_between_dates( $piggy->date_time_helper['last-year'][0], $piggy->date_time_helper['last-year'][1] );
	if ( ( $results->total * $projected_ratio ) > $old_results->total  ) {
		$sales_data->inc = true;
	} else {
		$sales_data->inc = false;
	}
		
	$piggy_data[ __( 'Year', 'piggy' ) ] = $sales_data;
	
	global $piggy_data_iterator;	
	global $piggy_data_items_iterator;	
	$piggy_data_iterator = $piggy_data_items_iterator = false;
} 

function piggy_the_data_increase_or_decrease_class() {
	global $piggy_data_item;	
	
	if ( isset( $piggy_data_item->inc ) ) {
		if ( $piggy_data_item->inc ) {
			$class_name = 'pos';
		} else {
			$class_name = 'neg';	
		}
		
		echo apply_filters( 'piggy_data_increase_or_decrease_class', $class_name );	
	} 
}

function piggy_populate_projected_sales_data() {
	global $piggy;
	global $piggy_data;
	$wpec = piggy_get_ecommerce_helper(); 
	
	$piggy_data = array();
	
	$now = piggy_mktime();
	
	$results = $wpec->get_product_summary_between_dates( $piggy->date_time_helper['this-month'][0], $piggy->date_time_helper['this-month'][1] );	
	if ( $results ) {
		$product = new stdClass;	
		$projected_ratio = ( $piggy->date_time_helper['this-month'][1] - $piggy->date_time_helper['this-month'][0] ) / ( $now - $piggy->date_time_helper['this-month'][0] ); 
		
		foreach( $results as $name => $result ) {
			$product->name = $name;
			$product->value = $result->total * $projected_ratio;
			$product->count = $result->count * $projected_ratio;
			$product->class_name = 'top-month';
			
			break;
		}
		
		$old_results = $wpec->get_product_summary_between_dates( $piggy->date_time_helper['last-month'][0], $piggy->date_time_helper['last-month'][1], $product->name  );
	
		if ( $old_results ) {	
			if ( $product->value > $old_results[0]->total  ) {
				$product->inc = true;
			} else {
				$product->inc = false;
			}		
		}
		
		$piggy_data[ __( 'Month', 'piggy' ) ] = $product;
	}
	
	$results = $wpec->get_product_summary_between_dates( $piggy->date_time_helper['this-year'][0], $piggy->date_time_helper['this-year'][1] );
	if ( $results ) {
		$product = new stdClass;	
		$projected_ratio = ( $piggy->date_time_helper['this-year'][1] - $piggy->date_time_helper['this-year'][0] ) / ( $now - $piggy->date_time_helper['this-year'][0] ); 
				
		foreach( $results as $name => $result ) {
			$product->name = $name;
			$product->value = $result->total * $projected_ratio;
			$product->count = $result->count * $projected_ratio;
			$product->class_name = 'top-year';
			
			break;
		}
		
		$old_results = $wpec->get_product_summary_between_dates( $piggy->date_time_helper['last-year'][0], $piggy->date_time_helper['last-year'][1], $product->name );
		if ( $old_results ) {
			if ( $product->value > $old_results[0]->total  ) {
				$product->inc = true;
			} else {
				$product->inc = false;
			}	
		}	
			
		$piggy_data[ __( 'Year', 'piggy' ) ] = $product;
	}
		
	global $piggy_data_iterator;	
	global $piggy_data_items_iterator;	
	$piggy_data_iterator = $piggy_data_items_iterator = false;	
}

function piggy_get_data_product_name() {
	global $piggy_data_item;	
			
	return apply_filters( 'piggy_data_product_name', $piggy_data_item->name );	
}

function piggy_the_data_product_name() {
	echo piggy_get_data_product_name();
}

function piggy_populate_overview_product_data() {
	global $piggy;
	global $piggy_data;
	$wpec = piggy_get_ecommerce_helper(); 
	
	$piggy_data = array();	
	
	$results = $wpec->get_product_summary_between_dates( $piggy->date_time_helper['this-month'][0], $piggy->date_time_helper['this-month'][1] );	
	if ( $results ) {
		$product = new stdClass;	
		foreach( $results as $name => $result ) {
			$product->name = $name;
			$product->value = $result->total;
			$product->count = $result->count;
			$product->class_name = 'top-month';
			
			break;
		}
		
		$piggy_data[ __( 'Month', 'piggy' ) ] = $product;
	}
	
	$results = $wpec->get_product_summary_between_dates( $piggy->date_time_helper['this-year'][0], $piggy->date_time_helper['this-year'][1] );
	if ( $results ) {
		$product = new stdClass;	
		foreach( $results as $name => $result ) {
			$product->name = $name;
			$product->value = $result->total;
			$product->count = $result->count;
			$product->class_name = 'top-year';
			
			break;
		}
			
		$piggy_data[ __( 'Year', 'piggy' ) ] = $product;
	}
	
	
	$results = $wpec->get_product_summary_between_dates( 0, $piggy->date_time_helper['today'][1] );
	if ( $results ) {
		$product = new stdClass;	
		foreach( $results as $name => $result ) {
			$product->name = $name;
			$product->value = $result->total;
			$product->count = $result->count;
			$product->class_name = 'all-time-products';
			
			break;
		}
			
		$piggy_data[ __( 'All Time', 'piggy' ) ] = $product;
	}	

	global $piggy_data_iterator;	
	global $piggy_data_items_iterator;	
	$piggy_data_iterator = $piggy_data_items_iterator = false;	
}

function piggy_populate_this_product_data_month() {
	global $piggy;
	global $piggy_data;
	$wpec = piggy_get_ecommerce_helper(); 
	
	$piggy_data = array();	
	$settings = piggy_get_settings();
	
	$results = $wpec->get_product_summary_between_dates( $piggy->date_time_helper['this-month'][0], $piggy->date_time_helper['this-month'][1] );	
	
	if ( $results ) {	
		$count = 0;
		foreach( $results as $name => $result ) {
			$product = new stdClass;
			
			$product->name = $name;
			$product->value = $result->total;
			$product->sales = $result->count;
			$product->class_name = '';
			
			$piggy_data[ $name ] = $product;
			
			$count++;
			
			if ( $count == $settings->max_top_sellers ) {
				break;	
			}
		}	
	}

	global $piggy_data_iterator;	
	global $piggy_data_items_iterator;	
	$piggy_data_iterator = $piggy_data_items_iterator = false;	
}


function piggy_populate_this_product_data_year() {
	global $piggy;
	global $piggy_data;
	$wpec = piggy_get_ecommerce_helper(); 
	
	$piggy_data = array();	
	$settings = piggy_get_settings();
	
	$results = $wpec->get_product_summary_between_dates( $piggy->date_time_helper['this-year'][0], $piggy->date_time_helper['this-year'][1] );	
	
	if ( $results ) {	
		$count = 0;
		foreach( $results as $name => $result ) {
			$product = new stdClass;
			
			$product->name = $name;
			$product->value = $result->total;
			$product->sales = $result->count;
			$product->class_name = '';
			
			$piggy_data[ $name ] = $product;
			
			$count++;
			
			if ( $count == $settings->max_top_sellers ) {
				break;	
			}			
		}	
	}

	global $piggy_data_iterator;	
	global $piggy_data_items_iterator;	
	$piggy_data_iterator = $piggy_data_items_iterator = false;	
}


function piggy_populate_this_product_all_time() {
	global $piggy;
	global $piggy_data;
	$wpec = piggy_get_ecommerce_helper(); 
	
	$piggy_data = array();	
	$settings = piggy_get_settings();
	
	$results = $wpec->get_product_summary_between_dates( 0, $piggy->date_time_helper['today'][1] );	
	
	if ( $results ) {	
		$count = 0;
		foreach( $results as $name => $result ) {
			$product = new stdClass;
			
			$product->name = $name;
			$product->value = $result->total;
			$product->sales = $result->count;
			$product->class_name = '';
			
			$piggy_data[ $name ] = $product;
			
			$count++;
			
			if ( $count == $settings->max_top_sellers ) {
				break;	
			}			
		}	
	}

	global $piggy_data_iterator;	
	global $piggy_data_items_iterator;	
	$piggy_data_iterator = $piggy_data_items_iterator = false;	
}

function piggy_populate_product_data() {
	global $piggy;
	global $piggy_data;
	$wpec = piggy_get_ecommerce_helper(); 
	
	$piggy_data = array();	
	$total_data = array();
	$count_data = array();	
	$week_data = array();
	$week_count = array();

	$results = $wpec->get_product_summary_between_dates( $piggy->date_time_helper['this-week'][0], $piggy->date_time_helper['this-week'][1] );	
	foreach( $results as $name => $result ) {
		$week_data[ $name ] = $result->total;
		$week_count[ $name ] = $result->count;	
	}
		
	$results = $wpec->get_product_summary_between_dates( $piggy->date_time_helper['this-month'][0], $piggy->date_time_helper['this-month'][1] );	
	foreach( $results as $name => $result ) {
		$total_data[ $name ] = $result->total;
		$count_data[ $name ] = $result->count;	
	}
	
	$piggy_data[ __( 'Products This Week', 'piggy' ) ] = $week_data;
	$piggy_data[ __( 'Sales This Week', 'piggy' ) ] = $week_count;			
	$piggy_data[ __( 'Products This Month', 'piggy' ) ] = $total_data;
	$piggy_data[ __( 'Sales This Month', 'piggy' ) ] = $count_data;		
	
	global $piggy_data_iterator;	
	global $piggy_data_items_iterator;	
	$piggy_data_iterator = $piggy_data_items_iterator = false;	
}

function piggy_has_data() {
	global $piggy_data;
	global $piggy_data_iterator;
	global $piggy_count;
	
	if ( !$piggy_data_iterator ) {
		$piggy_data_iterator = new PiggyArrayIterator( $piggy_data );
		$piggy_count = 0;
	}	
	
	return $piggy_data_iterator->have_items();
}

function piggy_get_today_data_count() {
	global $piggy_data;
	
	return count( $piggy_data );
}

function piggy_the_data() {
	global $piggy_count;
	global $piggy_data_item;
	global $piggy_data_iterator;

	
	$piggy_data_item = $piggy_data_iterator->the_item();
	$piggy_count++;
	
	global $piggy_data_items_iterator;	
	$piggy_data_items_iterator = false;
	
	global $piggy_data_sales_iterator;	
	$piggy_data_sales_iterator = false;
}

function piggy_get_sales_count() {
	global $piggy_data_item;
	
	if ( $piggy_data_item ) {
		return count( $piggy_data_item->sales );
	} 
	
	return 0;
}

function piggy_get_total_sale_price() {
	global $piggy_data_item;
	
	if ( $piggy_data_item && isset( $piggy_data_item->total_price ) ) {
		return $piggy_data_item->total_price;
	} 
	
	return 0;
}

// Kyle added this, attempting to retrieve buyer's name from WPEC

function piggy_get_buyers_name() {
	global $piggy_data_item;
	
	if ( $piggy_data_item && isset( $piggy_data_item->user_ID ) ) {
		return $piggy_data_item->user_ID;
	} 
	
	return 0;
	
}

// Alex added these functions to retrieve buyer's email and phone number, etc
function piggy_get_buyers_email() {
	global $piggy_data_item;
	
	if ( $piggy_data_item && isset( $piggy_data_item->user_email ) ) {
		return $piggy_data_item->user_email;
	} 	
	return 0;
}

function piggy_get_buyers_phone() {
	global $piggy_data_item;
	
	if ( $piggy_data_item && isset( $piggy_data_item->user_phone ) ) {
		return $piggy_data_item->user_phone;
	} 
	return 0;
}

function piggy_get_buyers_login() {
	global $piggy_data_item;
	
	if ( $piggy_data_item && isset( $piggy_data_item->user_login ) ) {
		return $piggy_data_item->user_login;
	} 
	return 0;
}

function piggy_get_buyers_first_name() {
	global $piggy_data_item;
	
	if ( $piggy_data_item && isset( $piggy_data_item->user_first_name ) ) {
		return $piggy_data_item->user_first_name;
	} 
	return 0;
}

function piggy_get_buyers_last_name() {
	global $piggy_data_item;
	
	if ( $piggy_data_item && isset( $piggy_data_item->user_last_name ) ) {
		return $piggy_data_item->user_last_name;
	} 
	return 0;
}

function piggy_get_buyers_nickname() {
	global $piggy_data_item;
	
	if ( $piggy_data_item && isset( $piggy_data_item->user_nickname ) ) {
		return $piggy_data_item->user_nickname;
	} 
	return 0;
}

function piggy_get_sales_processed() {
	global $piggy_data_item;
	
	if ( $piggy_data_item && isset( $piggy_data_item->processed ) ) {
		return $piggy_data_item->processed;
	} 
	return 0;
}

function piggy_get_sales_value() {
	global $piggy_data_item;
	
	if ( $piggy_data_item && isset( $piggy_data_item->value ) ) {
		return $piggy_data_item->value;
	} 
	return 0;
}

function piggy_get_sales_id() {
	global $piggy_data_item;
	
	if ( $piggy_data_item && isset( $piggy_data_item->id ) ) {
		return $piggy_data_item->id;
	} 
	return 0;
}

function piggy_get_data_average_closed() {
	global $piggy_data_item;
	
	if ( $piggy_data_item && isset( $piggy_data_item->average_closed ) ) {
		return $piggy_data_item->average_closed;
	} 
	return 0;
}

function piggy_get_statusno() {
	global $piggy_data_item;
/*
	if ( $piggy_data_item && isset( $piggy_data_item->statusno ) ) {
		return $piggy_data_item->statusno;
	} 
	return 0;
*/
	return $piggy_data_item->statusno;
}

// Alex 050912 -  seating: table 
function piggy_get_seating() {
	global $piggy_data_item;
	
	if ( $piggy_data_item && isset( $piggy_data_item->seating ) ) {
		return $piggy_data_item->seating;
	} 
	return 0;
}

// Alex 032612
function piggy_get_discount() {
	global $piggy_data_item;
	
	if ( $piggy_data_item && isset( $piggy_data_item->discount ) ) {
		return $piggy_data_item->discount;
	} 
	return 0;
}

function piggy_get_notes() {
	global $piggy_data_item;
	
	if ( $piggy_data_item && isset( $piggy_data_item->notes ) ) {
		return $piggy_data_item->notes;
	} 
	return 0;
}

// Alex 070112 -  new location for gratuity 
function piggy_get_gratuity() {
	global $piggy_data_item;
	
	if ( $piggy_data_item && isset( $piggy_data_item->gratuity ) ) {
		return $piggy_data_item->gratuity;
	} 
	return 0;
}

// Alex: end

function piggy_data_while_has_sales_data() {
	global $piggy_data_item;
	global $piggy_data_sales_iterator;
	
	if ( !$piggy_data_sales_iterator ) {
		$piggy_data_sales_iterator = new PiggyArrayIterator( $piggy_data_item->sales );
	}	
	
	return $piggy_data_sales_iterator->have_items();
}

function piggy_data_the_sales_data() {
	global $piggy_data_sales_item;
	global $piggy_data_sales_iterator;
	
	$piggy_data_sales_item = $piggy_data_sales_iterator->the_item();
}

function piggy_data_get_sales_data_quantity() {
	global $piggy_data_sales_item;
	
	return $piggy_data_sales_item->quantity;
}
// Alex
function piggy_data_get_sales_data_price() {
	global $piggy_data_sales_item;
	
	return $piggy_data_sales_item->value;
}

// Alex end



function piggy_data_get_sales_data_name() {
	global $piggy_data_sales_item;
	
	return $piggy_data_sales_item->product;
}

function piggy_get_data_count() {
	global $piggy_count;
	
	return $piggy_count;	
}

function piggy_the_data_count() {
	echo piggy_get_data_count();
}

function piggy_the_data_title() {
	echo piggy_get_data_title();
}

function piggy_get_data_title() {
	global $piggy_data_iterator;
	
	return apply_filters( 'piggy_data_title', $piggy_data_iterator->the_key() );		
}



function piggy_get_data_class_name() {
	global $piggy_data_item;	
	
	if ( isset( $piggy_data_item->class_name ) ) {
		return apply_filters( 'piggy_data_class_name', $piggy_data_item->class_name );	
	} else {
		return false;	
	}
}

function piggy_the_data_class_name() {
	echo piggy_get_data_class_name();
}



function piggy_get_data_value() {
	global $piggy_data_item;	

	if ( isset( $piggy_data_item->value ) ) {
		return apply_filters( 'piggy_data_value', $piggy_data_item->value );	
	} else {
		return false;	
	}
}
// Alex 051012
function piggy_get_data_value_seating() {
	global $piggy_data_item;	
	
	if ( isset( $piggy_data_item->value_seating ) ) {
		return apply_filters( 'piggy_data_value', $piggy_data_item->value_seating );	
	} else {
		return false;	
	}
}


// Alex 050412
function piggy_get_data_discount() {
	global $piggy_data_item;	
	
	if ( isset( $piggy_data_item->discount ) ) {
		return apply_filters( 'piggy_data_discount', $piggy_data_item->discount );	
	} else {
		return false;	
	}
}

function piggy_the_data_value() {
	echo piggy_get_data_value();
}

function piggy_get_data_sales() {
	global $piggy_data_item;	
	
	if ( isset( $piggy_data_item->sales ) ) {
		return apply_filters( 'piggy_data_sales', $piggy_data_item->sales );	
	} else {
		return false;	
	}
}

// Alex 051012
function piggy_get_data_sales_seating() {
	global $piggy_data_item;	
	
	if ( isset( $piggy_data_item->count_seating ) ) {
		return apply_filters( 'piggy_data_sales', $piggy_data_item->count_seating );	
	} else {
		return 0;	
	}
}


// Alex 031812
function alex_get_gratuity() {
//	global $piggy_data_item;	
	global $wpdb;
	global $piggy;
	
// Alex 031812 " - gratuity

	$start_date_ak 	= $piggy->date_time_helper['today'][0];
	$end_date_ak	= $piggy->date_time_helper['today'][1];

// Alex 070112 - replacing with new gratuity location	
//	$sales_gratuity = $wpdb->get_row( 'SELECT SUM(price*quantity) AS gratuity FROM ' . $wpdb->prefix . 'wpsc_cart_contents AS a LEFT JOIN ' . $wpdb->prefix . 'wpsc_purchase_logs AS b ON a.purchaseid = b.id WHERE b.processed IN (5) '. ak_where2('b.'). ' AND name="Gratuity" AND b.date >= ' . $start_date_ak . ' AND b.date <=' . $end_date_ak );

// Ales 082112 - added processed=2,3,5 (was only 5)
	$sales_gratuity = $wpdb->get_row( 'SELECT SUM(base_shipping) AS gratuity FROM ' . $wpdb->prefix . 'wpsc_purchase_logs WHERE processed IN (2,3,5) '. ak_where2(''). ' AND date >= ' . $start_date_ak . ' AND date <=' . $end_date_ak );
		
		
//	return $sales_gratuity->gratuity;
	
	if ( isset( $sales_gratuity->gratuity ) ) {
		return $sales_gratuity->gratuity;	
	} else {
		return false;	
	}
	
} // alex_get_gratuity()

// Alex 051012 - gratuity for seating
function alex_get_gratuity_seating() {
//	global $piggy_data_item;	
	global $wpdb;
	global $piggy;

	$start_date_ak 	= $piggy->date_time_helper['today'][0];
	$end_date_ak	= $piggy->date_time_helper['today'][1];
	
// Alex 070112 - replacing with new gratuity location	
//	$sales_gratuity = $wpdb->get_row( 'SELECT SUM(price*quantity) AS gratuity FROM ' . $wpdb->prefix . 'wpsc_cart_contents AS a LEFT JOIN ' . $wpdb->prefix . 'wpsc_purchase_logs AS b ON a.purchaseid = b.id WHERE b.processed IN (5) '. ak_where2('b.'). ' AND name="Gratuity" AND b.date >= ' . $start_date_ak . ' AND b.date <=' . $end_date_ak . ' AND b.shipping_region !=""');

// Ales 082112 - added processed=2,3,5 (was only 5)		
	$sales_gratuity = $wpdb->get_row( 'SELECT SUM(base_shipping) AS gratuity FROM ' . $wpdb->prefix . 'wpsc_purchase_logs WHERE processed IN (2,3,5) '. ak_where2(''). ' AND date >= ' . $start_date_ak . ' AND date <=' . $end_date_ak . ' AND shipping_region !=""');
			
	
//	return $sales_gratuity->gratuity;
	
	if ( isset( $sales_gratuity->gratuity ) ) {
		return $sales_gratuity->gratuity;	
	} else {
		return false;	
	}

	
} // alex_get_gratuity_seating()

// Alex 051012 -  seating data: count, total, discounts
function alex_get_data_all_seating() {
//	global $piggy_data_item;	
	global $wpdb;
	global $piggy;

	$start_date_ak 	= $piggy->date_time_helper['today'][0];
	$end_date_ak	= $piggy->date_time_helper['today'][1];
		
		// Alex 070412 - added average wait time (track_id)
		$sales_seating = $wpdb->get_row( 'SELECT count(*) AS count, SUM(totalprice - base_shipping +discount_value) AS total, SUM(discount_value) AS discount, AVG(track_id) AS aveclosed FROM ' . $wpdb->prefix . 'wpsc_purchase_logs WHERE processed IN (2,3,5) '. ak_where2(''). ' AND date >= ' . $start_date_ak . ' AND date <=' . $end_date_ak . ' AND shipping_region !=""' );		
	
	if ( isset( $sales_seating->count ) ) {
		return $sales_seating;	
	} else {
		return false;	
	}

} // alex_get_data_sales_seating()


// Alex 070612 -  Temporary solution for average wait time
function ak_aveclosed_tmp() {
//	global $piggy_data_item;	
	global $wpdb;
	global $piggy;

	$start_date_ak 	= $piggy->date_time_helper['today'][0];
	$end_date_ak	= $piggy->date_time_helper['today'][1];
		
		$sales = $wpdb->get_row( 'SELECT AVG(track_id) AS aveclosed FROM ' . $wpdb->prefix . 'wpsc_purchase_logs WHERE processed IN (2,3,5) '. ak_where2(''). ' AND date >= ' . $start_date_ak . ' AND date <=' . $end_date_ak . ' AND shipping_region =""' );		
	
	if ( isset( $sales->aveclosed ) ) {
		return $sales->aveclosed;	
	} else {
		return 0;	
	}

} // ak_aveclosed_tmp()


// Alex 122711
/*
function piggy_get_data_sales_closed() {
	global $piggy_data_item;	
	
	if ( isset( $piggy_data_item->sales) &&  $piggy_data_item->processed == 5  ) {
		return apply_filters( 'piggy_data_sales', $piggy_data_item->sales );	
	} else {
		return false;	
	}
}
*/
// Alex 122711 - end

function piggy_the_data_sales() {
	echo piggy_get_data_sales();
}

function piggy_get_data_date() {
	global $piggy_data_item;	
	
	if ( isset( $piggy_data_item->date ) ) {
		return apply_filters( 'piggy_data_date', $piggy_data_item->date );	
	} else {
		return false;	
	}
}

function piggy_the_data_date() {
	echo piggy_get_data_date();
}

function piggy_get_data_product() {
	global $piggy_data_item;	
	
	if ( isset( $piggy_data_item->product ) ) {
		return apply_filters( 'piggy_data_product', $piggy_data_item->product );	
	} else {
		return false;	
	}
}

function piggy_the_data_product() {
	echo piggy_get_data_product();
}

function piggy_get_data_average_price() {
	global $piggy_data_item;	
	
	if ( isset( $piggy_data_item->average_sale_price ) ) {
		return apply_filters( 'piggy_data_average_sale_price', $piggy_data_item->average_sale_price );	
	} else {
		return false;	
	}
}

function piggy_the_data_average_price() {
	echo piggy_get_data_average_price();
}

function piggy_has_data_items() {
	global $piggy_data_item;
	global $piggy_data_items_iterator;
	
	if ( !$piggy_data_items_iterator ) {
		$piggy_data_items_iterator = new PiggyArrayIterator( $piggy_data_item );
	}	
	
	return $piggy_data_items_iterator->have_items();	
}

function piggy_the_data_items() {
	global $piggy_data_items_item;	
	global $piggy_data_items_iterator;	
	
	$piggy_data_items_item = $piggy_data_items_iterator->the_item(); 
}

function piggy_get_data_items_desc() {
	global $piggy_data_items_iterator;	
	
	return apply_filters( 'piggy_data_items_desc', $piggy_data_items_iterator->the_key() );	
}

function piggy_the_data_items_desc() {
	echo piggy_get_data_items_desc();
}

function piggy_get_data_items_value() {
	global $piggy_data_items_item;	
	
	$item = $piggy_data_items_item;
	if ( isset( $item->value ) ) {
		$item = $item->value;	
	}
	
	return apply_filters( 'piggy_data_items_value', $item );	
}

function piggy_the_data_items_value() {
	echo piggy_get_data_items_value();
}

function piggy_get_data_items_sales() {
	global $piggy_data_items_item;	
	
	$item = $piggy_data_items_item;
	if ( isset( $item->sales ) ) {
		$item = $item->sales;	
	}
	
	return apply_filters( 'piggy_data_items_sales', $item );	
}

function piggy_the_data_items_sales() {
	echo piggy_get_data_items_sales();
}

function piggy_get_data_items_average_price() {
	global $piggy_data_items_item;	
	
	$item = $piggy_data_items_item;
	if ( isset( $item->average_sale_price ) ) {
		$item = $item->average_sale_price;	
	}
	
	return apply_filters( 'piggy_data_items_average_price', $item );	
}

function piggy_the_data_items_average_price() {
	echo piggy_get_data_items_average_price();
}

function piggy_get_data_items_class( $the_class ) {
	global $piggy_data_items_item;	
	
	$classes = array( $the_class );
	if ( isset( $piggy_data_items_item->value ) ) {
		if ( $piggy_data_items_item->increasing ) {
			$classes[] = 'pos';	
		} else {
			$classes[] = 'neg';				
		}
	}		
	
	return apply_filters( 'piggy_data_items_class', $classes );
}

function piggy_the_data_items_class( $the_class ) {
	echo implode( ' ', piggy_get_data_items_class( $the_class ) );
}

function piggy_prowl_has_api_keys() {
	global $settings;
	global $piggy;
	
	$settings = $piggy->get_settings();	
	
	return ( isset( $settings->prowl_api_keys ) && is_array( $settings->prowl_api_keys ) && count( $settings->prowl_api_keys ) );
}

function piggy_howl_has_info() {
	global $settings;
	global $piggy;
	
	$settings = $piggy->get_settings();	
	
	return ( isset( $settings->howl_usernames ) && is_array( $settings->howl_usernames ) && count( $settings->howl_usernames ) );
}

// Supported devices functions

function piggy_get_detected_device() {
	if ( strripos( $_SERVER['HTTP_USER_AGENT'], 'ipad' ) !== false ) {
		return 'ipad';	
//	} else if ( strripos( $_SERVER['HTTP_USER_AGENT'], 'android' ) !== false ) {
//		return 'android';
	} else return 'unknown';
}

function piggy_unsupported_message() {
	switch( piggy_get_detected_device() ) {
		case 'ipad':
			return sprintf( __( 'Sorry, Piggy is not yet compatible with %s', 'piggy' ), 'iPad' );
//		case 'android':
//			return sprintf( __( 'Sorry, Piggy is not compatible with %s', 'piggy' ), 'Android' );
		default:
			return sprintf( __( 'Sorry, Piggy is not yet viewable in browsers', 'piggy' ) );
	}	
}

function development_location() {
	$development_location = false;
	if ( ( $_SERVER['SERVER_ADDR'] === '127.0.0.1' || $_SERVER['SERVER_ADDR'] === '10.0.1.77' ) && strripos( $_SERVER['HTTP_USER_AGENT'], '533.20.27' ) != true ) {
		return true;
	}
	return $development_location;
}
 
function piggy_is_supported_device() {
	return ( strripos( $_SERVER['HTTP_USER_AGENT'], 'iphone' ) == true || strripos( $_SERVER['HTTP_USER_AGENT'], 'ipod' ) == true || strripos( $_SERVER['HTTP_USER_AGENT'], 'android' ) == true || development_location() );	
//return true;
}

function piggy_is_webapp_mode() {
		
	return ( strripos( $_SERVER['HTTP_USER_AGENT'], 'safari' ) === false || strripos( $_SERVER['HTTP_USER_AGENT'], 'android' ) || development_location() );
}

function piggy_get_body_classes() {
	global $settings;
	global $piggy;

	$settings = $piggy->get_settings();	
	$body_classes = array();
	
	if ( piggy_is_user_logged_in() ) {
		$body_classes[] = 'logged-in';	
	}
	
	if ( strripos( $_SERVER['HTTP_USER_AGENT'], 'android' ) ) {
		$body_classes[] = 'android';		
	}
	
	if ( $settings->use_startup_image ) {
		$body_classes[] = 'use-startup';			
	}
	
	return apply_filters( 'piggy_body_classes', $body_classes );
}

function piggy_the_body_classes() {
	echo implode( ' ', piggy_get_body_classes() );	
}

function piggy_get_manifest_url() {
	global $piggy;
	
	return apply_filters( 'piggy_manifest_url', $piggy->get_absolute_manifest_url() );	
}

function piggy_the_manifest_url() {
	echo piggy_get_manifest_url();	
}

function piggy_is_user_logged_in() {
	if ( isset( $_COOKIE[ 'piggy_data'] ) ) {
		$ip_hash = $_COOKIE['piggy_data'];
		
		$settings = piggy_get_settings();
		// the IP address in the cookie matches the one the user is connecting from
		$password_hash = md5( $ip_hash . $settings->passcode );
		if ( $password_hash == $_COOKIE['piggy_hash'] ) {
			// Correct password
			return true;	
		}	
	}
	
	return false;
}

function piggy_get_bloginfo( $param ) {
	global $piggy;

	$setting = false;
	
	switch( $param ) {
		case 'sales-all-time':
			$wpec = piggy_get_ecommerce_helper(); 
			$results = $wpec->get_summary_between_dates( 0, $piggy->date_time_helper['this-year'][1] );	
			if ( $results ) {
				$setting = $results->total;	
			}	
			break;
		case 'sales-this-year':
			$wpec = piggy_get_ecommerce_helper(); 
			$results = $wpec->get_summary_between_dates( $piggy->date_time_helper['this-year'][0], $piggy->date_time_helper['this-year'][1] );	
			if ( $results ) {
				$setting = $results->total;	
			}	
			break;
		case 'sales-this-month':
			$wpec = piggy_get_ecommerce_helper(); 
			$results = $wpec->get_summary_between_dates( $piggy->date_time_helper['this-month'][0], $piggy->date_time_helper['this-month'][1] );
			if ( $results ) {
				$setting = $results->total;
			}	
			break;
		case 'sales-this-week':
			$wpec = piggy_get_ecommerce_helper(); 
			$results = $wpec->get_summary_between_dates( $piggy->date_time_helper['this-week'][0], $piggy->date_time_helper['this-week'][1] );
			if ( $results ) {
				$setting = $results->total;
			}	
			break;			
		default:
			break;	
	}	
	
	return apply_filters( 'piggy_bloginfo', $setting );
}

function piggy_bloginfo( $param ) {
	echo piggy_get_bloginfo( $param );
}

function piggy_the_display_url() {
	global $piggy;
	echo $piggy->get_absolute_piggy_url();
}

function piggy_should_be_shown() {
	$settings = piggy_get_settings();

	if ( isset( $_SERVER[ 'HTTPS' ] ) && strtolower( $_SERVER['HTTPS'] ) == 'on' ) {
		$full_url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	} else {
		$full_url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	}	

	$modified_url = str_replace( get_bloginfo( 'home' ), '', $full_url );
	if ( ( rtrim( $modified_url, '/' ) === rtrim( $settings->display_url, '/' ) ) || isset( $_GET['piggy_ajax'] ) ) {
		return true;	
	}
	
	return false;
}

function piggy_get_skins() {
	$skins = array( 
		'default' => __( 'Green Moolah', 'piggy' ),
		'ios-linen' => __( 'iOS Linen', 'piggy' ),
		'blue-skies' => __( 'Blue Skies', 'piggy' ),
		'black-skies' => __( 'Black Skies', 'piggy' ),
		'ruby-red' => __( 'Ruby Red', 'piggy' ),
		'pink-pop' => __( 'Pink Pop', 'piggy' )
	);

	return apply_filters( 'piggy_skins', $skins );
}

function piggy_get_current_skin() {
	if ( isset( $_COOKIE[ PIGGY_SKIN_COOKIE_NAME ] ) ) {
		return $_COOKIE[ PIGGY_SKIN_COOKIE_NAME ];	
	} else {
		$settings = piggy_get_settings();
		return $settings->colour_scheme;
	}	
}

function piggy_get_last_purchase_hash() {
	$wpec = piggy_get_ecommerce_helper(); 
	return $wpec->get_last_purchase_hash();
}

function piggy_is_wpec_detected() {
	require_once( PIGGY_DIR . '/include/helpers/wpec.php' );
	
	$wpec = new WPECHelper;
	return ( $wpec->is_detected() );	
}

function piggy_is_shopp_detected() {
	require_once( PIGGY_DIR . '/include/helpers/shopp.php' );
	
	$shopp = new ShoppHelper;
	return ( $shopp->is_detected() );	
}

function piggy_is_cart66_detected() {
	require_once( PIGGY_DIR . '/include/helpers/cart66.php' );
	
	$cart66 = new Cart66Helper;
	return ( $cart66->is_detected() );	
}

function piggy_is_woo_commerce_detected() {
	require_once( PIGGY_DIR . '/include/helpers/woo-commerce.php' );
	
	$woo = new WooCommerceHelper;
	return ( $woo->is_detected() );	
}

function piggy_get_ecommerce_helper() {
	$settings = piggy_get_settings();
	
	if ( $settings->supported_platform == 'wpec' && piggy_is_wpec_detected() ) {
		$wpec = new WPECHelper;
		return $wpec;
	} else if ( $settings->supported_platform == 'shopp' && piggy_is_shopp_detected() ) {
		$shopp = new ShoppHelper;
		return $shopp;	
	} else if ( $settings->supported_platform == 'woo-commerce' && piggy_is_woo_commerce_detected() ) {
		$woo = new WooCommerceHelper;
		return $woo;			
	} else if ( $settings->supported_platform == 'cart66' && piggy_is_cart66_detected() ) {
		$cart66 = new Cart66Helper;
		return $cart66;	
	} else {
		$wpec = new WPECHelper;
		return $wpec;
	}
}

function piggy_send_notification_message( $message ) {
	$settings = piggy_get_settings();
	
	if ( $settings->notification_service == 'howl' ) {
		for( $i = 0; $i < count( $settings->howl_usernames ); $i++ ) {						
			$howl = new Howl;
							
			$howl->set_username( $settings->howl_usernames[$i] );
			$howl->set_password( $settings->howl_passwords[$i] );
			
			$howl->send_message( 
				$settings->notification_section, 
				$message, 
				$settings->colour_scheme . '-howl.png' 
			);		
		
		}		
	} else if ( $settings->notification_service == 'prowl' ) {
		if ( count( $settings->prowl_api_keys ) ) {
			foreach( $settings->prowl_api_keys as $api_key ) {				
				$p = new PiggyProwl;
				$p->set_api_key( $api_key );
				$p->send_message( $settings->notification_section, $message );	
			}
		}
	}	
}

function piggy_get_currency_symbol() {
	$currency = '$';
	$settings = piggy_get_settings();
	if ( $settings->currency_symbol == 'euro' ) {
		$currency = '€';
	}
	
	return apply_filters( 'piggy_currency_symbol', $currency );
}

function piggy_the_currency_symbol() {
	echo piggy_get_currency_symbol();
}


// Alex: added phone number formatting (Web)

/**
 * FORMATPHONE
 * Converts phone numbers to the formatting standard
 * 
 * @param   String   $num   A unformatted phone number
 * @return  String   Returns the formatted phone number
 */
function formatPhone($num)
{
    $num = ereg_replace('[^0-9]', '', $num);

    $len = strlen($num);
    if($len == 7)
        $num = preg_replace('/([0-9]{3})([0-9]{4})/', '$1-$2', $num);
    elseif($len == 10)
        $num = preg_replace('/([0-9]{3})([0-9]{3})([0-9]{4})/', '($1) $2-$3', $num);

    return $num;
}

// echo formatPhone('1 208 - 386 2934');
// will print: (208) 386-2934
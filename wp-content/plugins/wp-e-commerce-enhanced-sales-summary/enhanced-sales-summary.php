<?php

// Alex 030812

// $ak_superadmins = [1, 15, 124, 125];  // These UserID can see everything

// This is identical to ak_where() but for some reason we can't use it here, so duplicating :( 
function ak_where2( $pfix="") {
 $ak_barID = get_current_user_id();
 
 
 // this is a hack - hard coded IDs for Super Admins - Kyle and Alex
 if (  $ak_barID == 1 
 	|| $ak_barID == 15 
 	|| $ak_barID == 124 
 	|| $ak_barID == 125	) $ak_barID = "";

 if (!empty ($ak_barID ) ) {
    $my_where = ' AND '.$pfix.'engravetext='. $ak_barID . ' ' ; 
	}
 else {
	$my_where = "";
	}
	
return $my_where;
	
} // ak_where2

/*
Plugin Name: WPEC Sales Summary
Plugin URI: http://www.visser.com.au/wp-ecommerce/plugins/enhanced-sales-summary/
Description: Improved Sales Summary Dashboard widget for WP e-Commerce.
Version: 1.2.3
Author: Visser Labs
Author URI: http://www.visser.com.au/about/
License: GPL2
*/

load_plugin_textdomain( 'wpsc_ss', null, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

include_once( 'includes/common.php' );

switch( wpsc_get_major_version() ) {

	case '3.7':
		include_once( 'includes/release-3_7.php' );
		break;

}

$wpsc_ss = array(
	'filename' => basename( __FILE__ ),
	'dirname' => basename( dirname( __FILE__ ) ),
	'abspath' => dirname( __FILE__ ),
	'relpath' => basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ )
);

$wpsc_ss['prefix'] = 'wpsc_ss';
$wpsc_ss['name'] = __( 'Enhanced Sales Summary for WP e-Commerce', 'wpsc_ss' );

// Alex 031012
$wpsc_ss['menu'] = __( 'Sales Summary', 'wpsc_ss' );
// $wpsc_ss['menu'] = __( 'Sales Summary: ' . get_current_user_id() , 'wpsc_ss' );


if( is_admin() ) {

	function wpsc_ss_dashboard_setup() {

		if( current_user_can( 'manage_options' ) ) {
			wp_add_dashboard_widget( 'wpsc_ss_dashboard_widget', __( 'Sales Summary' , 'wpsc_ss' ), 'wpsc_ss_dashboard_widget' );
			wp_enqueue_style( 'wpsc_ss_styles', plugins_url( '/style.css', __FILE__ ) );
		}

	}
	add_action('wp_dashboard_setup', 'wpsc_ss_dashboard_setup');

	function wpsc_ss_dashboard_widget() {

		global $wpdb;

		$year = date( 'Y' );
		$month = date( 'm' );

		$start_timestamp = strtotime( 'last Monday', time() );
		$end_timestamp = strtotime( 'next Sunday', time() ) + 86400;
		
		// Alex 031012
		$sql = "SELECT COUNT(*) FROM `" . WPSC_TABLE_PURCHASE_LOGS . "` WHERE `date` BETWEEN '$start_timestamp' AND '$end_timestamp' AND `processed` IN (5) ".ak_where2(). "ORDER BY `date` DESC";

		// Alex
		$sql_week = date("m.d.y", $start_timestamp ) . ' - '. date("m.d.y", $end_timestamp ) ;
	
		$currentWeekOrders = $wpdb->get_var( $sql );
		$currentWeekSales = wpsc_currency_display( admin_display_total_price( $start_timestamp, $end_timestamp ) );
		if ( $currentWeekOrders > 0 )
			$weeksAverage = ( (int)admin_display_total_price( $start_timestamp, $end_timestamp ) / (int)$currentWeekOrders );

		$start_timestamp = mktime( 0, 0, 0, $month, 1, $year );
		$end_timestamp = mktime( 0, 0, 0, ( $month + 1 ), 0, $year );
		
		// Alex 031012
		$sql = "SELECT COUNT(*) FROM `" . WPSC_TABLE_PURCHASE_LOGS . "` WHERE `date` BETWEEN '$start_timestamp' AND '$end_timestamp' AND `processed` IN (5) ".ak_where2(). "ORDER BY `date` DESC";
		
		// Alex
		$sql_month = date("m.d.y", $start_timestamp ) . ' - '. date("m.d.y", $end_timestamp );
		
		$currentMonthOrders = $wpdb->get_var( $sql );
		$currentMonthSales = wpsc_currency_display( admin_display_total_price( $start_timestamp, $end_timestamp ) );
		if ( $currentMonthOrders > 0 )
			$monthsAverage = ( (int)admin_display_total_price( $start_timestamp, $end_timestamp ) / (int)$currentMonthOrders );

//		$start_timestamp = mktime( 0, 0, 0, $month, 1, $year );
//		$end_timestamp = mktime( 0, 0, 0, $month, 0, ( $year + 1 ) );

		$start_timestamp = mktime( 0, 0, 0, 1, 1, $year );
		$end_timestamp = mktime( );
		
		// Alex 031012
		$sql = "SELECT COUNT(*) FROM `" . WPSC_TABLE_PURCHASE_LOGS . "` WHERE `date` BETWEEN '$start_timestamp' AND '$end_timestamp' AND `processed` IN (5) ".ak_where2(). "ORDER BY `date` DESC";
	
		// Alex
		$sql_year = date("m.d.y", $start_timestamp ) . ' - '. date("m.d.y", $end_timestamp ) ;
	
		$currentYearOrders = $wpdb->get_var( $sql );
		$currentYearSales = wpsc_currency_display( admin_display_total_price( $start_timestamp, $end_timestamp ) );
		if ( $currentYearOrders > 0 )
			$yearsAverage = ( (int)admin_display_total_price( $start_timestamp, $end_timestamp ) / (int)$currentYearOrders );

		switch( wpsc_get_major_version() ) {

			case '3.7':
				echo '<div class="wpsc-3_7">';
				break;

		}
		echo '<div id="leftColumn">';
		// Alex
//		echo '<strong class="dashboardHeading">' . __( 'Current Week: '. $sql_week, 'wpsc' ) . '</strong><br />';
		echo '<strong class="dashboardHeading">' . __( 'Current Week', 'wpsc' ) . '</strong><br />';
		echo '<p class="dashboardWidgetSpecial">';
		echo $currentWeekSales;
		echo '<span class="dashboardWidget">' . _x( 'Sales', 'the total value of sales in dashboard widget', 'wpsc' ) . '</span>';
		echo '</p>';
		echo '<p class="dashboardWidgetSpecial">';
		
		echo '<span class="pricedisplay">' . $currentWeekOrders . '</span>';
		echo '<span class="dashboardWidget">' . _n( 'Order', 'Orders', $currentWeekOrders, 'wpsc' ) . '</span>';
		echo '</p>';
		echo '<p class="dashboardWidgetSpecial">';
		if( $weeksAverage )
			echo wpsc_currency_display( $weeksAverage );
		else
			echo '0';
		echo '<span class="dashboardWidget">' . __( 'Avg Size', 'wpsc' ) . '</span>';
		echo '</p>';
		echo '</div>';

		echo '<div id="middleColumn">';
	
		// Alex
//		echo '<strong class="dashboardHeading">' . __( 'Current Month: '. $sql_month, 'wpsc' ) . '</strong><br />';
		echo '<strong class="dashboardHeading">' . __( 'Current Month', 'wpsc' ) . '</strong><br />';
		echo '<p class="dashboardWidgetSpecial">';
		echo $currentMonthSales;
		echo '<span class="dashboardWidget">' . _x( 'Sales', 'the total value of sales in dashboard widget', 'wpsc' ) . '</span>';
		echo '</p>';
		echo '<p class="dashboardWidgetSpecial">';

		echo '<span class="pricedisplay">' . $currentMonthOrders . '</span>';

		echo '<span class="dashboardWidget">' . _n( 'Order', 'Orders', $currentMonthOrders, 'wpsc' ) . '</span>';
		echo '</p>';
		echo '<p class="dashboardWidgetSpecial">';
		//calculates average sales amount per order for the month
		if( $monthsAverage )
			echo wpsc_currency_display( $monthsAverage );
		else
			echo '0';
		echo '<span class="dashboardWidget">' . __( 'Avg Size', 'wpsc' ) . '</span>';
		echo '</p>';
		echo '</div>';

		echo '<div id="rightColumn">';

		// Alex
//		echo '<strong class="dashboardHeading">' . __( 'Current Year: '. $sql_year, 'wpsc' ) . '</strong><br />';
		echo '<strong class="dashboardHeading">' . __( 'Current Year', 'wpsc' ) . '</strong><br />';
		echo '<p class="dashboardWidgetSpecial">';
		echo $currentYearSales;
		echo '<span class="dashboardWidget">' . _x( 'Sales', '', 'wpsc' ) . '</span>';
		echo '</p>';
		echo '<p class="dashboardWidgetSpecial">';

		echo '<span class="pricedisplay">' . $currentYearOrders . '</span>';
		echo '<span class="dashboardWidget">' . _n( 'Order', 'Orders', $currentYearOrders, 'wpsc' ) . '</span>';
		echo '</p>';
		echo '<p class="dashboardWidgetSpecial">';
		if( $yearsAverage )
			echo wpsc_currency_display( $yearsAverage );
		else
			echo '0';
		echo '<span class="dashboardWidget">' . __( 'Avg Size', 'wpsc' ) . '</span>';
		echo '</p>';
		echo '</div>';
		echo '<div style="clear:both;"></div>';
		switch( wpsc_get_major_version() ) {

			case '3.7':
				echo '</div>';
				break;

		}

	}

}
?>
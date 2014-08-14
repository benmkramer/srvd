<?php

class PiggySettings extends stdClass {	};

class PiggyDefaultSettings extends PiggySettings {
	
	function PiggyDefaultSettings() {
		$this->display_url = '/piggy/';
		$this->colour_scheme = 'default';
		$this->use_startup_image = true;
		$this->piggy_language = 'en_EN';
		
		// Notifications
		$this->notification_section = __( 'New eCommerce Sale!', 'piggy' );
		$this->notification_service = 'none';
				
		$this->prowl_api_keys = array();
		$this->howl_usernames = array();
		$this->howl_passwords = array();
		
		// Permissions
		$this->show_for_admins = true;
		$this->show_for_users = '';
		if ( defined( AUTH_KEY ) ) {
			$this->passcode = sprintf( '%05d', crc32( md5( AUTH_KEY ) ) % 99999 );
		} else {
			$this->passcode = sprintf( '%05d', crc32( md5( DB_PASSWORD ) ) % 99999 );
		}
		
		$this->passcode_length = 5;
		$this->always_require_passcode = false;
		
		// BNCID and Licensing
		$this->bncid = '';
		$this->license_key = '';
		
		$this->timezone = 'UTC';
		
		// Top Sellers
		$this->max_top_sellers = 5;
		
		$this->override_current_time = 0;
		$this->offset_all_time_sales_total = 0.00;
		$this->offset_all_time_sales_count = 0;

		// Dummy settings
		$this->send_test_prowl_msg = false;
		$this->send_test_howl_msg = false;
		
		$this->supported_platform = 'none';
		if ( piggy_is_wpec_detected() ) {
			$this->supported_platform = 'wpec';
		} else if ( piggy_is_shopp_detected() ) {
			$this->supported_platform = 'shopp';
		} else if ( piggy_is_cart66_detected() ) {
			$this->supported_platform = 'cart66';
		}
		
		$this->currency_symbol = 'dollar';
	}
}

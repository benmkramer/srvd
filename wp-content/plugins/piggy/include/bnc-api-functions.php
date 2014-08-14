<?php

function piggy_has_license() {
	// Move this internally
	global $piggy;
	$settings = $piggy->get_settings();
	
	if ( time() > ( $settings->last_bncid_time + PIGGY_BNCID_CACHE_TIME ) ) {
		$result = $piggy->bnc_api->internal_check_token();	
		if ( $result ) {
			$settings->last_bncid_time = time();
			$settings->last_bncid_result = $piggy->bnc_api->verify_site_license();
			$settings->last_bncid_licenses = $piggy->bnc_api->get_total_licenses();
			
			if ( $settings->last_bncid_result ) {
				$setting->bncid_had_license = true;	
			}
		} else {
			$settings->last_bncid_time = 0;
			$settings->last_bncid_result = false;			
			$settings->last_bncid_licenses = 0;
		}		
			
		$piggy->save_settings( $settings );		
	}
	
	return $settings->last_bncid_result;
}

function piggy_clear_bnc_api_cache() {
	global $piggy;
	
	$settings = $piggy->get_settings();
	
	$settings->last_bncid_time = 0;
	$settings->last_bncid_result = false;			
	$settings->last_bncid_licenses = 0;	
	
	$piggy->save_settings( $settings );		
	
	return $settings;
}

function piggy_was_username_invalid() {
	global $piggy;
	
	return ( $piggy->bnc_api->get_response_code() == 408 );
}

function piggy_user_has_no_license() {
	global $piggy;
	
	return ( $piggy->bnc_api->get_response_code() == 412 );	
}

function piggy_credentials_invalid() {
	global $piggy;
	return $piggy->bnc_api->credentials_invalid;
}

function piggy_api_server_down() {
	global $piggy;
	
	$piggy->bnc_api->verify_site_license();	
	return $piggy->bnc_api->server_down;
}

function piggy_has_proper_auth() {
	piggy_has_license();
	
	$settings = piggy_get_settings();
	return $settings->last_bncid_licenses;
}

function piggy_is_upgrade_available() {
	global $piggy;
	
	if ( PIGGY_BETA ) {
		$latest_info = $piggy->bnc_api->get_product_version( true );
	} else {
		$latest_info = $piggy->bnc_api->get_product_version();	
	}
    
	if ( $latest_info ) {
		return ( $latest_info['version'] != PIGGY_VERSION );
	} else {
		return false;	
	}
}

global $piggy_site_license;
global $piggy_site_license_info;
global $piggy_site_license_iterator;
$piggy_site_license_iterator = false;

function piggy_has_site_licenses() {
	global $piggy;
	global $piggy_site_license_info;	
	global $piggy_site_license_iterator;
	
	if ( !$piggy_site_license_iterator ) {
		$piggy_site_license_info = $piggy->bnc_api->user_list_licenses();
		$piggy_site_license_iterator = new PiggyArrayIterator( $piggy_site_license_info['licenses'] );
	}	
	
	return $piggy_site_license_iterator->have_items();
}

function piggy_the_site_license() {
	global $piggy_site_license;
	global $piggy_site_license_iterator;
	
	$piggy_site_license = $piggy_site_license_iterator->the_item();
}

function piggy_the_site_licenses_remaining() {
	echo piggy_get_site_licenses_remaining();
}

function piggy_get_site_licenses_remaining() {
	global $piggy_site_license_info;	
		
	if ( $piggy_site_license_info && isset( $piggy_site_license_info['remaining'] ) ) {
		return $piggy_site_license_info['remaining'];
	}
	
	return 0;
}

function piggy_get_site_licenses_in_use() {
	global $piggy_site_license_info;	
	
	if ( $piggy_site_license_info && isset( $piggy_site_license_info['licenses'] ) && is_array( $piggy_site_license_info['licenses'] ) ) {
		return count( $piggy_site_license_info['remaining'] );
	}
	
	return 0;	
}

function piggy_the_site_license_name() {
	echo piggy_get_site_license_name();
}

function piggy_get_site_license_name() {
	global $piggy_site_license;
	return $piggy_site_license;
}

function piggy_is_licensed_site() {
	global $piggy;
	return $piggy->has_site_license();
}

function piggy_get_site_license_number() {
	global $piggy_site_license_iterator;
	return $piggy_site_license_iterator->current_position();
}

function piggy_can_delete_site_license() {
	return ( piggy_get_site_license_number() > 1 );	
}

$piggy_license_reset_info = false;

function piggy_can_do_license_reset() {
	global $piggy_license_reset_info;
	global $piggy;
	
	$piggy_license_reset_info = $piggy->bnc_api->get_license_reset_info( 'piggy' );
	if ( isset( $piggy_license_reset_info['can_reset_licenses'] ) ) {
		return $piggy_license_reset_info['can_reset_licenses'];	
	} else {
		return false;	
	}
}

function piggy_get_license_reset_days() {
	global $piggy_license_reset_info;
	
	if ( $piggy_license_reset_info && isset( $piggy_license_reset_info['reset_duration_days'] ) ) {
		return $piggy_license_reset_info['reset_duration_days'];
	}	
	
	return 0;
}

function piggy_get_license_reset_days_until() {
	global $piggy_license_reset_info;
	
	if ( $piggy_license_reset_info && isset( $piggy_license_reset_info['can_reset_in'] ) ) {
		return $piggy_license_reset_info['can_reset_in'];
	}	
	
	return 0;	
}


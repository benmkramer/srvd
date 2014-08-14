<?php

function piggy_do_check_for_update( $piggy_plugin ) {
	$plugin_name = "piggy/piggy.php";
	$latest_info = $piggy_plugin->bnc_api->get_product_version();
	
    // Check for WordPress 3.0 function
	if ( function_exists( 'is_super_admin' ) ) {
		$option = get_site_transient( 'update_plugins' );
	} else {
		$option = function_exists( 'get_transient' ) ? get_transient( 'update_plugins' ) : get_option( 'update_plugins' );
	}
	
	if ( $latest_info && $latest_info['version'] != PIGGY_VERSION && isset( $latest_info['upgrade_url'] ) ) {    	  		   		
        $piggy_option = $option->response[ $plugin_name ];

        if( empty( $piggy_option ) ) {
            $option->response[ $plugin_name ] = new stdClass();
        }

		$option->response[ $plugin_name ]->url = "https://www.bravenewcode.com/store/plugins/piggy/";
		
		$option->response[ $plugin_name ]->package = $latest_info['upgrade_url'];
		$option->response[ $plugin_name ]->new_version = $latest_info['version'];
		$option->response[ $plugin_name ]->id = "0";
		
		$option->response[ $plugin_name ]->slug = "piggy";

        $piggy_plugin->set_latest_version_info( $latest_info );
	} else { 
		unset( $option->response[ $plugin_name ] );	
	}
		
    if ( !$piggy_plugin->transient_set ) {      
    	// WordPress 3.0 changed some stuff, so we check for a WP 3.0 function
		if ( function_exists( 'is_super_admin' ) ) {
			$piggy_plugin->set_transient();
			set_site_transient( 'update_plugins', $option );
		} else {
			if ( function_exists( 'set_transient' ) ) {
				$piggy_plugin->set_transient();
				set_transient( 'update_plugins', $option );
			}
		}
    }
}
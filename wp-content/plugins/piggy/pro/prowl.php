<?php

define( 'PIGGY_PROWL_APPLICATION', 'Piggy' );

class PiggyProwl {
	var $api_key;
	
	function PiggyProwl() {
		if ( !class_exists( 'WP_Http' ) ) {
			include_once( ABSPATH . WPINC. '/class-http.php' );	
		}
		
		$this->api_key = false;
	}
	
	function set_api_key( $api_key ) {
		$this->api_key = $api_key;	
	}
	
	function send_message( $title, $message ) {
		$request = new WP_Http;
		
		$body = array(
			'apikey' => $this->api_key,
			'priority' => 1,
			'application' => PIGGY_PROWL_APPLICATION,
			'event' => $title,
			'description' => $message
		);
		
		$headers = array();

		$result = $request->request( 'https://api.prowlapp.com/publicapi/add', array( 'method' => 'POST', 'body' => $body, 'headers' => $headers, 'sslverify' => false ) );
		if ( !is_wp_error( $result ) ) {
			if ( $result && $result['headers']['status'] == 200 ) {
				// Check icon
				$decoded_body = json_decode( $result['body'] );
				if ( $decoded_body && $decoded_body->status = 'ok' ) {
	
				}
			} 
		}
	}	
}

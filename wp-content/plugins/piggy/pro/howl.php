<?php

class Howl {
	var $username;
	var $password;
	
	function Howl() {
		if ( !class_exists( 'WP_Http' ) ) {
			include_once( ABSPATH . WPINC. '/class-http.php' );	
		}
		
		$this->username = false;
		$this->password = false;
	}
	
	function set_password( $password ) {
		$this->password = $password;	
	}
	
	function set_username( $username ) {
		$this->username = $username;
	}	
	
	function load_icon( $icon ) {
		$icon_name = PIGGY_DIR . '/images/howl/' . $icon;
		$contents = file_get_contents( $icon_name );
		return $contents;
	}
	
	function send_message( $title, $message, $icon ) {
		$request = new WP_Http;
		
		$icon_contents = $this->load_icon( $icon );
		
		$body = array(
			'name' => 'test',
			'title' => $title,
			'description' => $message,
			'application' => 'piggy',
			'icon-md5' => md5( $icon_contents ),
			'icon-sha1' => sha1( $icon_contents )
		);

		$headers = array( 'Authorization' => 'Basic ' . base64_encode( $this->username . ":" . $this->password ) );

		$result = $request->request( 'https://howlapp.com/public/api/notification', array( 'method' => 'POST', 'body' => $body, 'headers' => $headers, 'sslverify' => false ) );
		if ( !is_wp_error( $result ) ) {
			if ( $result && $result['headers']['status'] == 200 ) {
				// Check icon
				$decoded_body = json_decode( $result['body'] );
				if ( $decoded_body && $decoded_body->status = 'ok' ) {
					if ( $decoded_body->payload->needs_icon ) {
						$icon_ticket = $decoded_body->payload->icon_ticket;
		
						$cmd = "curl -X PUT --form icon=@\"" . PIGGY_DIR . "/images/howl/" . $icon . "\" --form ticket=" . $icon_ticket . " -u '" . $this->username . ":" . $this->password . "' https://howlapp.com/public/api/icon";
						
						$result = exec( $cmd );
					}
				}
			}
		}
	}	
}
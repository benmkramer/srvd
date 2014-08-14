<?php

function piggy_add_notification_settings( $general_array ) {
	$general_array[ __( 'Notifications', 'piggy' ) ] = array( 'notifications', 
		array(
			array( 'section-start', 'notifications-section', __( 'Push Notifications', 'piggy' ) ),
			array( 
				'list', 
				'notification_service', 
				__( 'Notification service', 'piggy' ),
				__( 'Configure a push-notification service to deliver real-time push updates of e-Commerce sales to your Mac or iOS device. Requires Prowl or Howl iOS apps installed and configured on each device.', 'piggy' ),
				array(
					'none' => __( 'None', 'piggy' ),
					'prowl' => __( 'Prowl', 'piggy' ),
					'howl' => __( 'Howl', 'piggy' )
				)
			),
			array( 'text', 'notification_section', __( 'Notification message title', 'piggy' ) ),		
			array( 'section-end' ),
			array( 'section-start', 'prowl-section', __( 'Prowl Settings', 'piggy' ) ),
			array( 'prowl-api-keys', 'prowl_api_keys', __( 'API Keys', 'piggy' ) ),
			array( 'checkbox', 'send_test_prowl_msg', __( 'Send test notification message', 'piggy' ) ),		
			array( 'section-end' ),
			array( 'section-start', 'howl-section', __( 'Howl Settings', 'piggy' ) ),
			array( 'howl-settings', 'howl_settings', __( 'Howl Username' ) ),
			array( 'checkbox', 'send_test_howl_msg', __( 'Send test notification message', 'piggy' ) ),
			array( 'section-end' )
		)
	);	

	return $general_array;
}
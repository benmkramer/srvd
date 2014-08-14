<?php

function piggy_get_license_section_info() {
	$info = array(
		__( 'Account', 'piggy' ) => array ( 'bncid',
			array(
				array( 'section-start', 'account-information', __( 'Account Information', 'piggy' ) ),
				array( 'copytext', 'bncid-info', __( 'Your Account E-Mail and License Key are required to enable site licenses for support and auto-upgrades with Piggy.', 'piggy' ) ),
				array( 'text', 'bncid', __( 'Account E-Mail', 'piggy' ) ),			
				array( 'key', 'license_key', __( 'License Key', 'piggy' ) ),
				array( 'license-check', 'license-check' ),
				array( 'section-end' )
			)	
		)
	);
	
	if ( piggy_has_proper_auth() ) {
		$info[ __( 'Manage Licenses', 'piggy' ) ] = array( 'manage-licenses-section',
			array(
				array( 'section-start', 'manage-license-info', __( 'Manage Licenses', 'piggy' ) ),
				array( 'manage-licenses', 'manage-license' ),
				array( 'section-end' )
			)	
		);				
	}
	
	return $info;
}

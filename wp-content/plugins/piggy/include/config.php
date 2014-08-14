<?php

function piggy_is_pro_version() {
	return ( file_exists( PIGGY_ROOT_PATH . '/pro' ) && is_dir( PIGGY_ROOT_PATH . '/pro' ) );
}

if ( piggy_is_pro_version() ) {
	define( 'PIGGY_ROOT_DIR', 'piggy' );
	define( 'PIGGY_SETTING_NAME', 'piggy' );
} else {
	define( 'PIGGY_ROOT_DIR', 'piggy-lite' );
	define( 'PIGGY_SETTING_NAME', 'piggy-lite' );
}

define( 'PIGGY_DIR', WP_PLUGIN_DIR . '/' . PIGGY_ROOT_DIR );
define( 'PIGGY_URL', WP_PLUGIN_URL . '/' . PIGGY_ROOT_DIR );

define( 'PIGGY_BNCID_CACHE_TIME', 3600 );

define( 'PIGGY_TEMP_DIR', WP_CONTENT_DIR . '/' . PIGGY_ROOT_DIR );
define( 'PIGGY_TEMP_URL', WP_CONTENT_URL . '/' . PIGGY_ROOT_DIR );
define( 'PIGGY_TEMP_STYLE_NAME', PIGGY_TEMP_DIR . '/skin.css' );
define( 'PIGGY_TEMP_STYLE_URL', PIGGY_TEMP_URL . '/skin.css' );

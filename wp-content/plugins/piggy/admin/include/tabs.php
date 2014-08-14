<?php

global $piggy_tab_iterator;
global $piggy_tab;
global $piggy_tab_id;

global $piggy_tab_section_iterator;
global $piggy_tab_section;

global $piggy_tab_section_settings_iterator;
global $piggy_tab_section_setting;

global $piggy_tab_options_iterator;
global $piggy_tab_option;

$piggy_tab_iterator = false;

function piggy_has_tabs() {
	global $piggy_tab_iterator;
	global $piggy;
	global $piggy_tab_id;
	
	if ( !$piggy_tab_iterator ) {
		$piggy_tab_iterator = new PiggyArrayIterator( $piggy->tabs );
		$piggy_tab_id = 0;
	}
	
	return $piggy_tab_iterator->have_items();	
}

function piggy_rewind_tab_settings() {
	global $piggy_tab_section_iterator;
	$piggy_tab_section_iterator = false;
}

function piggy_the_tab() {
	global $piggy_tab;
	global $piggy_tab_iterator;
	global $piggy_tab_id;
	global $piggy_tab_section_iterator;
	
	$piggy_tab = apply_filters( 'piggy_tab', $piggy_tab_iterator->the_item() );
	$piggy_tab_section_iterator = false;
	$piggy_tab_id++;
}

function piggy_the_tab_id() {
	echo piggy_get_tab_id();
}

function piggy_get_tab_id() {
	global $piggy_tab_id;
	return apply_filters( 'piggy_tab_id', $piggy_tab_id );	
}

function piggy_has_tab_sections() {
	global $piggy_tab;	
	global $piggy_tab_section_iterator;
	
	if ( !$piggy_tab_section_iterator ) {
		$piggy_tab_section_iterator = new PiggyArrayIterator( $piggy_tab['settings'] );
	}
	
	return $piggy_tab_section_iterator->have_items();
}

function piggy_the_tab_section() {
	global $piggy_tab_section;
	global $piggy_tab_section_iterator;
	global $piggy_tab_section_settings_iterator;
		
	$piggy_tab_section = apply_filters( 'piggy_tab_section', $piggy_tab_section_iterator->the_item() );
	$piggy_tab_section_settings_iterator = false;
}

function piggy_the_tab_name() {
	echo piggy_get_tab_name();
}

function piggy_get_tab_name() {
	global $piggy_tab_section_iterator;
		
	return apply_filters( 'piggy_tab_name', $piggy_tab_section_iterator->the_key() );
}

function piggy_the_tab_class_name() {
	echo piggy_get_tab_class_name();
}

function piggy_get_tab_class_name() {
	return piggy_string_to_class( piggy_get_tab_name() );	
}


function piggy_has_tab_section_settings() {
	global $piggy_tab_section;
	global $piggy_tab_section_settings_iterator;
	
	if ( !$piggy_tab_section_settings_iterator ) {
		$piggy_tab_section_settings_iterator = new PiggyArrayIterator( $piggy_tab_section[1] );
	}
	
	return $piggy_tab_section_settings_iterator->have_items();
}

function piggy_the_tab_section_setting() {
	global $piggy_tab_section_setting;
	global $piggy_tab_section_settings_iterator;
	global $piggy_tab_options_iterator;
		
	$piggy_tab_section_setting = apply_filters( 'piggy_tab_section_setting', $piggy_tab_section_settings_iterator->the_item() );
	$piggy_tab_options_iterator = false;
}

function piggy_the_tab_section_class_name() {
	echo piggy_get_tab_section_class_name();
}

function piggy_get_tab_section_class_name() {
	global $piggy_tab_section;
	
	return $piggy_tab_section[0];
}

function piggy_the_tab_setting_type() {
	echo piggy_get_tab_setting_type();
}

function piggy_get_tab_setting_type() {
	global $piggy_tab_section_setting;
	return apply_filters( 'piggy_tab_setting_type', $piggy_tab_section_setting[0] );
}

function piggy_the_tab_setting_name() {
	echo piggy_get_tab_setting_name();
}

function piggy_get_tab_setting_name() {
	global $piggy_tab_section_setting;
	
	return apply_filters( 'piggy_tab_setting_name', $piggy_tab_section_setting[1] );		
}

function piggy_the_tab_setting_class_name() {
	echo piggy_get_tab_setting_class_name();
}

function piggy_get_tab_setting_class_name() {
	global $piggy_tab_section_setting;
	
	if ( isset( $piggy_tab_section_setting[1] ) ) {
		return apply_filters( 'piggy_tab_setting_class_name', piggy_string_to_class( $piggy_tab_section_setting[1] ) );	
	} else {
		return false;	
	}	
}

function piggy_the_tab_setting_has_tooltip() {
	return ( strlen( piggy_get_tab_setting_tooltip() ) > 0 );
}

function piggy_the_tab_setting_tooltip() {
	echo piggy_get_tab_setting_tooltip();
}

function piggy_get_tab_setting_tooltip() {
	global $piggy_tab_section_setting;
	
	if ( isset( $piggy_tab_section_setting[3] ) ) {
		return htmlspecialchars( apply_filters( 'piggy_tab_setting_tooltip', $piggy_tab_section_setting[3] ), ENT_COMPAT, 'UTF-8' );	
	} else {
		return false;	
	}	
}


function piggy_the_tab_setting_desc() {
	echo piggy_get_tab_setting_desc();
}

function piggy_get_tab_setting_desc() {
	global $piggy_tab_section_setting;
	return apply_filters( 'piggy_tab_setting_desc', $piggy_tab_section_setting[2] );		
}

function piggy_the_tab_setting_value() {
	echo piggy_get_tab_setting_value();
}

function piggy_get_tab_setting_value() {
	$settings = piggy_get_settings();
	$name = piggy_get_tab_setting_name();
	if ( isset( $settings->$name ) ) {
		return $settings->$name;	
	} else {
		return false;	
	}
}

function piggy_the_tab_setting_is_checked() {
	return piggy_get_tab_setting_value();
}

function piggy_tab_setting_has_options() {
	global $piggy_tab_options_iterator;
	global $piggy_tab_section_setting;
	
	if ( isset( $piggy_tab_section_setting[4] ) ) {			
		if ( !$piggy_tab_options_iterator ) {
			$piggy_tab_options_iterator = new PiggyArrayIterator( $piggy_tab_section_setting[4] );	
		}
		
		return $piggy_tab_options_iterator->have_items();
	} else {
		return false;	
	}
}

function piggy_tab_setting_the_option() {
	global $piggy_tab_options_iterator;
	global $piggy_tab_option;	
	
	$piggy_tab_option = apply_filters( 'piggy_tab_setting_option', $piggy_tab_options_iterator->the_item() );
}

function piggy_tab_setting_has_tags() {
	global $piggy_tab_section_setting;
	
	$has_tag = false;
	
	switch( piggy_get_tab_setting_type() ) {
		case 'checkbox':
		case 'text':
		case 'textarea':
			$has_tag =  isset( $piggy_tab_section_setting[4] );
			break;
		case 'list':
			$has_tag = isset( $piggy_tab_section_setting[5] );
			break;
	}
	
	return apply_filters( 'piggy_tab_setting_tags', $has_tag );
}

function piggy_tab_setting_get_tags() {
	global $piggy_tab_section_setting;
	
	$tags = array();
	
	switch( piggy_get_tab_setting_type() ) {
		case 'checkbox':
		case 'text':
		case 'textarea':
			$tags = $piggy_tab_section_setting[4];
			break;		
		case 'list':
			$tags = $piggy_tab_section_setting[5];
			break;
	}	
	
	return apply_filters( 'piggy_tab_setting_tags', $tags );
}

function piggy_tab_setting_the_tags() {
	return @implode( '', piggy_tab_setting_get_tags() );	
}

function piggy_tab_setting_the_option_desc() {
	echo piggy_tab_setting_get_option_desc();
}	

function piggy_tab_setting_get_option_desc() {
	global $piggy_tab_option;		
	return apply_filters( 'piggy_tab_setting_option_desc', $piggy_tab_option );
}	

function piggy_tab_setting_the_option_key() {
	echo piggy_tab_setting_get_option_key();
}

function piggy_tab_setting_get_option_key() {
	global $piggy_tab_options_iterator;
	return apply_filters( 'piggy_tab_setting_option_key', $piggy_tab_options_iterator->the_key() );	
}

function piggy_tab_setting_is_selected() {
	return ( piggy_tab_setting_get_option_key() == piggy_get_tab_setting_value() );
}

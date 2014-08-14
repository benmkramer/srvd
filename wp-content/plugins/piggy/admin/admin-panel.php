<?php

add_action( 'admin_menu', 'piggy_setup_admin_panel' );

function piggy_setup_admin_panel() {
	if ( piggy_is_pro_version() ) {
		$plugin_name = 'Piggy Pro';
	} else {
		$plugin_name = 'Piggy Lite';
	}
	
	if ( defined( 'WPSC_VERSION' ) && WPSC_VERSION == '3.7' ) {
		add_submenu_page( 
			'wpsc-sales-logs', 
			$plugin_name, 
			$plugin_name, 
			'manage_options', 
			__FILE__, 
			'piggy_admin_panel' 
		);			
	} else {
		add_menu_page( 
			$plugin_name, 
			$plugin_name, 
			'manage_options', 
			__FILE__, 
			'', 
			WP_PLUGIN_URL . '/piggy/admin/images/piggy-admin-icon.png' 
		);	
		
		add_submenu_page( 
			__FILE__, 
			$plugin_name, 
			$plugin_name, 
			'manage_options', 
			__FILE__, 
			'piggy_admin_panel' 
		);		
	}
}

function piggy_add_tab( $tab_name, $class_name, $settings, $custom_page = false ) {
	global $piggy;
	
	$piggy->tabs[ $tab_name ] = array(
		'page' => $custom_page,
		'settings' => $settings,
		'class_name' => $class_name
	);
}

function piggy_get_language_list() {
	$languages = array(
		'en_EN' => 'English',
		'da_DK' => 'Dansk',
		'de_DE' => 'Deutsch',
		'es_ES' => 'Español',
		'fr_FR' => 'Français',
		'it_IT' => 'Italiano',
		'ja_JP' => '日本語',
		'nl_NL' => 'Nederlands',
		'pt_PT' => 'Português',
		'ru_RU' => 'Русский язык',
		'sv_SE' => 'Svenska',
		'zh_CN' => '简体字',
		'zh_TW' => '簡體字'
	);
	
	return $languages;	
}

function piggy_get_platform_list() {
	$platform_list = array( 'none' => __( 'None', 'piggy' ) );
	
	if ( piggy_is_wpec_detected() ) {
		$platform_list[ 'wpec' ] = 'WP e-Commerce';	
	}
	
	if ( piggy_is_shopp_detected() ) {
		$platform_list[ 'shopp' ] = 'Shopp';	
	}			
	
	if ( piggy_is_cart66_detected() ) {
		$platform_list[ 'cart66' ] = 'Cart66';
	}
	
	if ( piggy_is_woo_commerce_detected() ) {
		$platform_list[ 'woo-commerce' ] = 'WooCommerce';
	}
	
	return $platform_list;	
}

function piggy_admin_panel() {
	// Load admin panel specific code
	require_once( PIGGY_DIR . '/admin/include/tabs.php' );	
	
	// Generate admin panel here
	$general_array = array(
		__( 'Overview', 'piggy' ) => array ( 'overview',
			array(
				array( 'section-start', 'oinkboard', __( 'OinkBoard', "piggy" ) ),
				array( 'oinkboard' ),
				array( 'section-end' )
			)	
		), 
		__( 'Settings', 'piggy' ) => array( 'settings',
			array(
				array( 'section-start', 'general-settings', __( 'General', 'piggy' ) ),
				array( 'setup', 'setup_url' ),
				array( 'text', 'display_url', __( 'Piggy URL', 'piggy' ), __( 'URL fragment that Piggy should use to be accessed on your website, e.g. "/stats/" or "/piggy/".', 'piggy' ) ),
				array( 
					'list', 
					'supported_platform', 
					__( 'eCommerce Platform', 'piggy' ), 
					'', 
					piggy_get_platform_list()
				),
				array( 'list', 'timezone', __( 'Timezone', 'piggy' ), __( 'Piggy will respect this zone for its date/time statistics counting.', 'piggy' ), piggy_get_timezones() ),
				array( 'list', 'piggy_language', __( 'Language', 'piggy' ), '', piggy_get_language_list() ),
				array( 
					'list', 
					'colour_scheme', 
					__( 'Piggy Theme', 'piggy' ), 
					__( 'Modify the appearance of Piggy and Home Screen bookmark icon.', 'piggy' ), 
					piggy_get_skins()
				),
				array( 'list', 'currency_symbol', __( 'Currency symbol', 'piggy' ), '', 
					array(
						'dollar' => __( 'Dollar ($)', 'piggy' ),
						'euro' => __( 'Euro (€)', 'euro' )
					)
				),				
				array( 'checkbox', 'use_startup_image', __( 'Use startup image', 'piggy' ), __( 'Piggy will use a startup background while loading, matching the Piggy Theme chosen above.', 'piggy' ) ),
				array( 'section-end' ),
				array( 'section-start', 'permission-settings', __( 'Security', 'piggy' ) ),
				array( 
					'list', 
					'passcode_length', 
					__( 'Number of digits for the passcode', 'piggy' ), 
					'',
					array(
						'4' => sprintf( __( '%d digits', 'piggy' ), 4 ),
						'5' => sprintf( __( '%d digits', 'piggy' ), 5 )	
					)
				),
				array( 'numeric', 'passcode', __( 'Numeric passcode to access Piggy', 'piggy' ) ),
				array( 'checkbox', 'always_require_passcode', __( 'Require passcode on each launch', 'piggy' ), __( 'When checked the passcode will always be required on launch. If unchecked, the passcode will be valid until the user logs out.', 'piggy' ) ),
				array( 'section-end' ),
				array( 'section-start', 'advanced-settings', __( 'Advanced', 'piggy' ) ),
			//	array( 'text', 'override_current_time', __( 'Current Time Override', 'piggy' ), __( 'Enter the unix timestamp that Piggy will use for the current time', 'piggy' ) ),
				array( 'text', 'offset_all_time_sales_total', __( 'Adjust All-Time Sales Total', 'piggy' ), __( 'Enter a number here to adjust the all-time sales total. This can be used to account for sales from another e-Commerce engine.', 'piggy' ) ),
				array( 'text', 'offset_all_time_sales_count', __( 'Adjust All-Time Sales Count', 'piggy' ), __( 'Enter a number here to adjust the all-time sales count. This can be used to account for sales from another e-Commerce engine.', 'piggy' ) ),
				array( 'section-end' )
			)
		),
		__( 'Panels', 'piggy' ) => array( 'content',
			array(
				array( 'section-start', 'content-section', __( 'Overview', 'piggy' ) ),
				array( 'text-short', 'max_top_sellers', __( 'Maximum number of \'Best Sellers\' to display', 'piggy' ), '' ),
				array( 'section-end' )	
			)
		)
	);
	
	if ( piggy_is_pro_version() ) {
		require_once( PIGGY_DIR . '/pro/notifications.php' );
		
		$settings = piggy_get_settings();
		if ( $settings->supported_platform != 'cart66' ) {
			$general_array = piggy_add_notification_settings( $general_array );
		}
	}

	piggy_add_tab( __( 'General', 'piggy' ), 'general', $general_array );	
	
	if ( piggy_is_pro_version() ) {
		require_once( PIGGY_DIR . '/pro/admin.php' );
		
		piggy_add_tab( __( 'Account + Licenses', 'piggy' ), 'general',
			piggy_get_license_section_info()
		);	

	} else {	
		piggy_add_tab( __( 'Go Pro', 'piggy' ), 'go-pro' ,
			array(
				__( 'Information', 'piggy' ) => array ( 'information',
					array(
						array( 'section-start', 'go-pro-info', __( 'Piggy Pro', "piggy" ) ),
						array( 'pro-info' ),
						array( 'section-end' )
					)
				)
			)
		);		
	}
	
	include( 'html/admin-form.php' );
}

function piggy_show_tab_settings() {
	include( 'html/tabs.php' );
}

function piggy_get_timezones() {
	$tz = array (
		'Pacific/Apia' => 'West Samoa Time (MIT)',
		'Pacific/Niue' => 'Niue Time (Pacific / Niue)',
		'Pacific/Tahiti' => 'Tahiti Time (Pacific / Tahiti)',
		'HST' => 'Hawaii Standard Time (HST)',
		'America/Adak' => 'Hawaii-Aleutian Standard Time (America / Adak)',
		'Pacific/Fakaofo' => 'Tokelau Time (Pacific / Fakaofo)',
		'Pacific/Rarotonga' => 'Cook Is. Time (Pacific / Rarotonga)',
		'Pacific/Marquesas' => 'Marquesas Time (Pacific / Marquesas)',
		'Pacific/Gambier' => 'Gambier Time (Pacific / Gambier)',
		'America/Anchorage' => 'Alaska Standard Time (AST)',
		'Pacific/Pitcairn' => 'Pitcairn Standard Time (Pacific / Pitcairn)',
		'America/Los_Angeles' => 'Pacific Standard Time (US & Canada)',
		'America/Phoenix' => 'Mountain Standard Time (US / Arizona)',
		'MST7MDT' => 'Mountain Standard Time (US & Canada)',
		'America/Regina' => 'Central Standard Time (Canada / Saskatchewan)',
		'America/Chicago' => 'Central Standard Time (US & Canada)',
		'Pacific/Easter' => 'Easter Is. Time (Pacific / Easter)',
		'Pacific/Galapagos' => 'Galapagos Time (Pacific / Galapagos)',
		'America/El_Salvador' => 'Central Standard Time (America / El Salvador)',
		'EST' => 'Eastern Standard Time (US & Canada)',
		'America/Porto_Acre' => 'Acre Time (America / Porto Acre)',
		'America/Guayaquil' => 'Ecuador Time (America / Guayaquil)',
		'America/Lima' => 'Peru Time (America / Lima)',
		'America/Bogota' => 'Colombia Time (America / Bogota)',
		'America/Jamaica' => 'Eastern Standard Time (America / Jamaica)',
		'America/Havana' => 'Central Standard Time (America / Havana)',
		'America/Indianapolis' => ' Eastern Standard Time (US / East-Indiana)',
		'America/Glace_Bay' => 'Atlantic Standard Time (America / Glace Bay)',
		'America/Santiago' => 'Chile Time (America / Santiago)',
		'America/Caracas' => 'Venezuela Time (America / Caracas)',
		'Atlantic/Bermuda' => 'Atlantic Standard Time (Atlantic / Bermuda)',
		'America/Asuncion' => 'Paraguay Time (America / Asuncion)',
		'America/Cuiaba' => 'Amazon Standard Time (America / Cuiaba)',
		'America/La_Paz' => 'Bolivia Time (America / La Paz)',
		'Brazil/West' => 'Amazon Standard Time (Brazil / West)',
		'PRT' => 'Atlantic Standard Time (PRT)',
		'America/Guyana' => 'Guyana Time (America / Guyana)',
		'Atlantic/Stanley' => 'Falkland Is. Time (Atlantic / Stanley)',
		'America/St_Johns' => 'Newfoundland Standard Time (America / St Johns)',
		'America/Sao_Paulo' => 'Brazil Time (BET)',
		'America/Cayenne' => 'French Guiana Time (America / Cayenne)',
		'America/Belem' => 'Brazil Time (America / Belem)',
		'America/Argentina/Buenos_Aires' => '[Argentine Time (AGT)',
		'America/Paramaribo' => 'Suriname Time (America / Paramaribo)',
		'America/New_York' => 'New York (America / New York)',
		'America/Miquelon' => 'Pierre & Miquelon Standard Time (America / Miquelon)',
		'America/Godthab' => 'Western Greenland Time (America / Godthab)',
		'Antarctica/Rothera' => 'Rothera Time (Antarctica / Rothera)',
		'America/Montevideo' => 'Uruguay Time (America / Montevideo)',
		'America/Noronha' => 'Fernando de Noronha Time (America / Noronha)',
		'Atlantic/South_Georgia' => 'South Georgia Standard Time (Atlantic / South Georgia)',
		'America/Scoresbysund' => 'Eastern Greenland Time (America / Scoresbysund)',
		'Atlantic/Azores' => 'Azores Time (Atlantic / Azores)',
		'Atlantic/Cape_Verde' => 'Cape Verde Time (Atlantic / Cape Verde)',
		'Europe/Lisbon' => 'Western European Time (Europe / Lisbon)',
		'UTC' => 'Coordinated Universal Time (UTC)',
		'Africa/Casablanca' => 'Western European Time (Africa / Casablanca)',
		'GMT' => 'Greenwich Mean Time (London / Dublin)',
		'CET' => 'Central European Time (Brussels, Paris, Stockholm, Prague)',
		'Africa/Algiers' => 'Central European Time (Africa / Algiers)',
		'Atlantic/Jan_Mayen' => '[Eastern Greenland Time (Atlantic / Jan Mayen)',
		'Africa/Bangui' => 'Western African Time (Africa / Bangui)',
		'Africa/Windhoek' => ' Western African Time (Africa / Windhoek)',
		'Asia/Jerusalem' => 'Israel Standard Time (Asia / Jerusalem)',
		'Africa/Johannesburg' => 'Central African Time (CAT)',
		'EET' => 'Eastern European Time (Athens, Beirut, Minsk, Istanbul)',
		'Africa/Tripoli' => 'Eastern European Time (Africa / Tripoli)',
		'Africa/Johannesburg' => 'South Africa Standard Time (Africa / Johannesburg)',
		'Europe/Moscow' => 'Moscow Standard Time (Europe / Moscow)',
		'Asia/Baghdad' => 'Arabia Standard Time (Asia / Baghdad)',
		'Antarctica/Syowa' => 'Syowa Time (Antarctica / Syowa)',
		'Africa/Dar_es_Salaam' => 'Eastern African Time (EAT)',
		'Asia/Kuwait' => 'Arabia Standard Time (Asia / Kuwait)',
		'Asia/Tehran' => 'Iran Standard Time (Asia / Tehran)',
		'Indian/Reunion' => 'Reunion Time (Indian / Reunion)',
		'Asia/Tbilisi' => 'Georgia Time (Asia / Tbilisi)',
		'Asia/Dubai' => 'Gulf Standard Time (Asia / Dubai)',
		'Asia/Baku' => 'Azerbaijan Time (Asia / Baku)',
		'Asia/Oral' => 'Oral Time (Asia / Oral)',
		'Indian/Mahe' => 'Seychelles Time (Indian / Mahe)',
		'Europe/Samara' => 'Samara Time (Europe / Samara)',
		'Asia/Yerevan' => 'Armenia Time (NET)',
		'Asia/Aqtau' => 'Aqtau Time (Asia / Aqtau)',
		'Indian/Mauritius' => 'Mauritius Time (Indian / Mauritius)',
		'Asia/Kabul' => 'Afghanistan Time (Asia / Kabul)',
		'Asia/Karachi' => 'Pakistan Time (PLT)',
		'Indian/Kerguelen' => 'French Southern & Antarctic Lands Time (Indian / Kerguelen)',
		'Asia/Aqtobe' => 'Aqtobe Time (Asia / Aqtobe)',
		'Asia/Ashgabat' => 'Turkmenistan Time (Asia / Ashgabat)',
		'Asia/Tashkent' => 'Uzbekistan Time (Asia / Tashkent)',
		'Indian/Maldives' => 'Maldives Time (Indian / Maldives)',
		'Asia/Yekaterinburg' => 'Yekaterinburg Time (Asia / Yekaterinburg)',
		'Asia/Dushanbe' => 'Tajikistan Time (Asia / Dushanbe)',
		'Asia/Bishkek' => 'Kirgizstan Time (Asia / Bishkek)',
		'IST' => 'India Standard Time (IST)',
		'Asia/Katmandu' => 'Nepal Time (Asia / Katmandu)',
		'Asia/Qyzylorda' => 'Qyzylorda Time (Asia / Qyzylorda)',
		'Asia/Novosibirsk' => 'Novosibirsk Time (Asia / Novosibirsk)',
		'BST' => 'Bangladesh Time (BST)',
		'Asia/Omsk' => 'Omsk Time (Asia / Omsk)',
		'Asia/Thimbu' => 'Bhutan Time (Asia / Thimbu)',
		'Asia/Almaty' => 'Alma-Ata Time (Asia / Almaty)',
		'Antarctica/Vostok' => 'Vostok Time (Antarctica / Vostok)',
		'Indian/Chagos' => 'Indian Ocean Territory Time (Indian / Chagos)',
		'Asia/Colombo' => 'Sri Lanka Time (Asia / Colombo)',
		'Antarctica/Mawson' => 'Mawson Time (Antarctica / Mawson)',
		'Indian/Cocos' => 'Cocos Islands Time (Indian / Cocos)',
		'Asia/Rangoon' => 'Myanmar Time (Asia / Rangoon)',
		'Asia/Hovd' => 'Hovd Time (Asia / Hovd)',
		'VST' => 'Indochina Time (VST)',
		'Indian/Christmas' => 'Christmas Island Time (Indian / Christmas)',
		'Asia/Jakarta' => 'West Indonesia Time (Asia / Jakarta)',
		'Antarctica/Davis' => 'Davis Time (Antarctica / Davis)',
		'Asia/Krasnoyarsk' => 'Krasnoyarsk Time (Asia / Krasnoyarsk)',
		'Asia/Kuala_Lumpur' => ' Malaysia Time (Asia / Kuala Lumpur)',
		'Asia/Makassar' => 'Central Indonesia Time (Asia / Makassar)',
		'Asia/Taipei' => 'Taipei Time (Asia / Taipei)',
		'Asia/Shanghai' => 'Shanghai Time (Asia / Shanghai)',
		'Asia/Singapore' => 'Singapore Time (Asia / Singapore)',
		'Asia/Brunei' => 'Brunei Time (Asia / Brunei)',
		'Asia/Irkutsk' => 'Irkutsk Time (Asia / Irkutsk)',
		'Australia/Perth' => 'Western Standard Time (Australia) (Australia / Perth)',
		'Asia/Manila' => 'Philippines Time (Asia / Manila)',
		'Asia/Ulaanbaatar' => 'Ulaanbaatar Time (Asia / Ulaanbaatar)',
		'Asia/Hong_Kong' => 'Hong Kong Time (Asia / Hong Kong)',
		'Asia/Choibalsan' => ' Choibalsan Time (Asia / Choibalsan)',
		'Asia/Dili' => 'East Timor Time (Asia / Dili)',
		'Pacific/Palau' => 'Palau Time (Pacific / Palau)',
		'Asia/Jayapura' => 'East Indonesia Time (Asia / Jayapura)',
		'Asia/Yakutsk' => 'Yakutsk Time (Asia / Yakutsk)',
		'Asia/Tokyo' => 'Japan Standard Time (JST)',
		'Asia/Seoul' => 'Korea Standard Time (Asia / Seoul)',
		'Australia/Adelaide' => 'Central Standard Time (South Australia) (Australia / Adelaide)',
		'Australia/Broken_Hill' => ' Central Standard Time (Australia / Broken Hill)',
		'Australia/Darwin' => 'Central Standard Time (Northern Territory) (ACT)',
		'Australia/Hobart' => 'Eastern Standard Time (Tasmania) (Australia / Hobart)',
		'Australia/Brisbane' => 'Eastern Standard Time (Queensland) (Australia / Brisbane)',
		'Pacific/Port_Moresby' => 'Papua New Guinea Time (Pacific / Port Moresby)',
		'Australia/Sydney' => 'Eastern Standard Time (New South Wales) (Australia / Sydney)',
		'Asia/Vladivostok' => 'Vladivostok Time (Asia / Vladivostok)',
		'Australia/Melbourne' => 'Eastern Standard Time (Victoria) (Australia / Melbourne)',
		'Asia/Sakhalin' => 'Sakhalin Time (Asia / Sakhalin)',
		'Pacific/Guam' => 'Chamorro Standard Time (Pacific / Guam)',
		'Pacific/Truk' => 'Truk Time (Pacific / Truk)',
		'Pacific/Yap' => 'Yap Time (Pacific / Yap)',
		'Antarctica/DumontDUrville' => 'Dumont-d\'Urville Time (Antarctica / DumontDUrville)',
		'Australia/Lord_Howe' => 'Load Howe Standard Time (Australia / Lord Howe)',
		'Pacific/Ponape' => 'Ponape Time (Pacific / Ponape)',
		'Pacific/Efate' => 'Vanuatu Time (Pacific / Efate)',
		'Pacific/Noumea' => 'New Caledonia Time (Pacific / Noumea)',
		'Pacific/Kosrae' => 'Kosrae Time (Pacific / Kosrae)',
		'SST' => 'Solomon Is. Time (SST)',
		'Asia/Magadan' => 'Magadan Time (Asia / Magadan)',
		'Pacific/Norfolk' => 'Norfolk Time (Pacific / Norfolk)',
		'Pacific/Tarawa' => 'Gilbert Is. Time (Pacific / Tarawa)',
		'Pacific/Fiji' => 'Fiji Time (Pacific / Fiji)',
		'Pacific/Majuro' => 'Marshall Islands Time (Pacific / Majuro)',
		'Asia/Kamchatka' => 'Petropavlovsk-Kamchatski Time (Asia / Kamchatka)',
		'Pacific/Auckland' => 'New Zealand Standard Time (Pacific / Auckland)',
		'Pacific/Wake' => 'Wake Time (Pacific / Wake)',
		'Pacific/Funafuti' => 'Tuvalu Time (Pacific / Funafuti)',
		'Pacific/Nauru' => ' Nauru Time (Pacific / Nauru)',
		'Pacific/Wallis' => 'Wallis & Futuna Time (Pacific / Wallis)',
		'Asia/Anadyr' => 'Anadyr Time (Asia / Anadyr)',
		'Pacific/Chatham' => 'Chatham Standard Time (Pacific / Chatham)',
		'Pacific/Tongatapu' => 'Tonga Time (Pacific / Tongatapu)',
		'Pacific/Enderbury' => 'Phoenix Is. Time (Pacific / Enderbury)',
		'Pacific/Kiritimati' => 'Line Is. Time (Pacific / Kiritimati)',
		'America/Toronto' => 'Toronto area, Canada (America / Toronto )'
	);	

	$ntz = array();
	$current_time = mktime();
	foreach( $tz as $name => $zone ) {
		date_default_timezone_set( $name );
		$ntz[ $name ] = date( 'M jS, H:i' ) . ' - ' . $zone;
	}
	
	asort( $ntz );
	return $ntz;
}

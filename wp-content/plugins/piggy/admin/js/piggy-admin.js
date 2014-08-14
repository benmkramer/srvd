var pigQuery = jQuery;

function piggyDoReady() {
	piggySetupGlobals();
	piggyLicenseAddOns();
	piggySetupTabs();
	piggySetupTooltips();
	piggySetupAdminPanel();
}

function piggySetupGlobals() {
	// Disable caching of AJAX responses */
	pigQuery.ajaxSetup ({
	    cache: false
	});

}

function piggyLicenseAddOns() {
	/* Add styling for valid credentials & site activation */
	if ( pigQuery( '#setting-bncid p.license-valid' ).length ) {
		pigQuery( '#setting-bncid input#bncid.text' ).addClass( 'valid' );
		pigQuery( '#setting-bncid input#license_key.text' ).addClass( 'valid' );	
	}
	
	/* Add styling for valid credentials only */
	if ( pigQuery( '#setting-bncid p.license-partial' ).length ) {
		pigQuery( '#setting-bncid input#bncid.text' ).addClass( 'partial' );
		pigQuery( '#setting-bncid input#license_key.text' ).addClass( 'partial' );	
	}
	
	/* Bind Click handler for target Manage License pane */
		pigQuery( 'a.configure-licenses' ).click( function( e ) {
			pigQuery( 'a#tab-section-manage-licenses-section' ).click();
			e.preventDefault();
		});
}

function piggySetupTabs() {
	// Top menu tabs
	pigQuery( '#piggy-top-menu li a' ).unbind( 'click' ).click( function( e ) {
		var tabId = pigQuery( this ).attr( 'id' );
		
		pigQuery.cookie( 'piggy-tab', tabId );
		
		pigQuery( '.pane-content' ).hide();
		pigQuery( '#pane-content-' + tabId ).show();
		
		pigQuery( '#pane-content-' + tabId + ' .left-area li a:first' ).click();
		
		pigQuery( '#piggy-top-menu li a' ).removeClass( 'active' );
		pigQuery( '#piggy-top-menu li a' ).removeClass( 'round-top-6' );
		
		pigQuery( this ).addClass( 'active' );
		pigQuery( this ).addClass( 'round-top-6' );

		e.preventDefault();
	});

	// Left menu tabs
	pigQuery( '#piggy-admin-form .left-area li a' ).unbind( 'click' ).click( function( e ) {
		var relAttr = pigQuery( this ).attr( 'rel' );
		
		pigQuery.cookie( 'piggy-list', relAttr );
			
		pigQuery( '.setting-right-section' ).hide();
		pigQuery( '#setting-' + relAttr ).show();
		
		pigQuery( '#piggy-admin-form .left-area li a' ).removeClass( 'active' );
		
		pigQuery( this ).addClass( 'active' );
		
		e.preventDefault();
	});
	
	// Cookie saving for tabs
	var tabCookie = pigQuery.cookie( 'piggy-tab' );
	if ( tabCookie ) {
		var tabLink = pigQuery( "#piggy-top-menu li a[id='" + tabCookie + "']" ); 
		pigQuery( '.pane-content' ).hide();
		pigQuery( '#pane-content-' + tabCookie ).show();	
		tabLink.addClass( 'active' );
		tabLink.addClass( 'round-top-6' );
		
		var listCookie = pigQuery.cookie( 'piggy-list' );
		if ( listCookie ) {
			var menuLink = pigQuery( "#piggy-admin-form .left-area li a[rel='" + listCookie + "']");
			pigQuery( '.setting-right-section' ).hide();
			pigQuery( '#setting-' + listCookie ).show();	
			pigQuery( '#piggy-admin-form .left-area li a' ).removeClass( 'active' );	
			menuLink.click();			
		} else {
			pigQuery( '#piggy-admin-form .left-area li a:first' ).click();
		}
	} else {
		pigQuery( '#piggy-top-menu li a:first' ).click();
	}	
}

function piggySetupTooltips() {
	// Admin Tooltips	
	doBncTooltip( 'a.piggy-tooltip', '#piggy-tooltip', 8, -40, 'left' );

	// Admin Tooltips for items on the far right of the page
	doBncTooltip( 'a.piggy-tooltip-left', '#piggy-tooltip-left', 8, -40, 'right' );
}

function piggyCheckForInt( evt ) {
	var charCode = ( evt.which ) ? evt.which : event.keyCode;
	return ( charCode >= 48 && charCode <= 57 );
}

function piggySetupAdminPanel() {
	/*
	pigQuery( '#setting_show_for_admins input.checkbox' ).change( function() {
		if ( pigQuery( this ).attr( 'checked' ) ) {
			pigQuery( '#setting_show_for_users' ).hide();
		} else {
			pigQuery( '#setting_show_for_users' ).fadeIn();
		}
	});
	
	pigQuery( '#setting_show_for_admins input' ).change();
	*/
	
	pigQuery( 'input.numeric' ).keypress( function( event ) { return piggyCheckForInt( pigQuery( event ).get(0) ); } );
	
	
	var ajax_params = {};
	piggyAdminAjax( 'profile', ajax_params, function( result ) { 
		pigQuery( '#setting_manage-license' ).html( result );
	});
	
	pigQuery( 'a.piggy-add-license' ).live( 'click', function( e ) {
		pigQuery( this ).animate( { opacity: 0.5 } ).text( PiggyCustom.activate_message );

		var ajax_params = {};
		piggyAdminAjax( 'activate-site-license', ajax_params, function( result ) { 
			window.location.href = window.location.href
		});		
		
		e.preventDefault();
	});
	
	pigQuery( 'a.piggy-remove-license' ).live( 'click', function( e ) {
		var ajax_params = {
			site: pigQuery( this ).attr( 'rel' )
		};
		
		piggyAdminAjax( 'deactivate-site-license', ajax_params, function( result ) { 
			window.location.href = window.location.href
		});		
		
		e.preventDefault();		
	});
	
	pigQuery( '#notification_service' ).live( 'change', function( e ) { 
		var currentValue = pigQuery( '#notification_service' ).val();
		
		if ( currentValue == 'prowl' ) {
			pigQuery( '#setting_prowl-section' ).show();
			pigQuery( '#setting_howl-section' ).hide();
			pigQuery( '#setting_notification_section' ).show();
		} else if ( currentValue == 'howl' ) {
			pigQuery( '#setting_prowl-section' ).hide();
			pigQuery( '#setting_howl-section' ).show();
			pigQuery( '#setting_notification_section' ).show();
		} else {
			pigQuery( '#setting_prowl-section' ).hide();
			pigQuery( '#setting_howl-section' ).hide();		
			pigQuery( '#setting_notification_section' ).hide();	
		}
	});
	
	pigQuery( '#notification_service' ).change();
	
	pigQuery( 'a.remove-prowl' ).live( 'click', function( e ) { 
		var currentItems = pigQuery( 'input.prowl' );
		if ( currentItems.length > 1 ) {
			pigQuery( this ).parent().remove();
		} else {
			pigQuery( this ).parent().find( 'input' ).attr( 'value', '' );	
		}
		
		e.preventDefault();	
	});
	
	pigQuery( 'a.add-prowl' ).live( 'click', function( e ) {
		var currentItems = pigQuery( 'input.prowl' );
		if ( currentItems ) {
			var nextItem = currentItems.length + 1;
			
			pigQuery( '#add-prowl-setting-area' ).append( '<div class="prowl-setting"><input type="text" autocomplete="off" class="text prowl" id="prowl_api_keys_' + nextItem + '" name="prowl_api_keys_' + nextItem + '" value="" /> <label for="prowl_api_keys_' + nextItem + '">' + PiggyCustom.prowl_api_text + '</label> <a href="#" class="add-prowl">+</a> <a href="#" class="remove-prowl">-</a><br /></div>' );
		}
			
		e.preventDefault();
	});
	
	pigQuery( 'a.remove-howl' ).live( 'click', function( e ) { 
		var currentItems = pigQuery( 'input.howl' );
		if ( currentItems.length > 1 ) {
			pigQuery( this ).parent().remove();
		} else {
			pigQuery( this ).parent().find( 'input' ).attr( 'value', '' );	
		}
		
		e.preventDefault();	
	});
	
	pigQuery( 'a.add-howl' ).live( 'click', function( e ) {
		var currentItems = pigQuery( 'input.howl' );
		if ( currentItems ) {
			var nextItem = currentItems.length + 1;
			
			pigQuery( '#add-howl-setting-area' ).append( '<div class="howl-setting"><input type="text" autocomplete="off" class="text howl-user" id="howl_username_' + nextItem + '" name="howl_username_' + nextItem + '" value="" /> <label class="text" for="howl_username_' + nextItem + '">Username</label> <input type="password" autocomplete="off" class="text howl" id="howl_password_" name="howl_password_' + nextItem + '" value="" /> <label class="text" for="howl_password_' + nextItem + '">Password</label> <a href="#" class="add-howl">+</a> <a href="#" class="remove-howl">-</a><br /></div>' );
		}
			
		e.preventDefault();
	});	
	
	/* Reset confirmation */
	pigQuery( '#piggy-submit-reset' ).click( function() {
		var answer = confirm( PiggyCustom.reset_settings_message );
		if ( answer ) {
			pigQuery.cookie( 'piggy-tab', '' );
			pigQuery.cookie( 'piggy-list', '' );
		} else {
			return false;	
		}
	});
	
	pigQuery( 'select#passcode_length' ).change( function() {
		var result = pigQuery( 'select#passcode_length' ).val();
		if ( result ) {
			pigQuery( '#passcode' ).attr( 'maxlength', result );
			
			var passcode = pigQuery( '#passcode' ).val();
			if ( passcode.length > result ) {
				pigQuery( '#passcode' ).val( passcode.substring( 1, passcode.length ) );	
			}
		}
	});
	
	pigQuery( 'select#passcode_length' ).change();
	
	// Do Oinkboard News
	var oinkBoardNews = pigQuery( '#blog-news-box-ajax' );
	if ( oinkBoardNews.length ) {
		piggyAdminAjax( 'oink-news', {}, function( response ) {
			oinkBoardNews.html( response );
			pigQuery( '#blog-news-box' ).removeClass( 'loading' );
		});
	}
}

function piggyAdminAjax( actionName, actionParams, callback ) {	
	var ajaxData = {
		action: 'piggy_ajax',
		piggy_action: actionName,
		piggy_nonce: PiggyCustom.admin_nonce
	};
	
	for ( name in actionParams ) { ajaxData[name] = actionParams[name]; }

	pigQuery.post( ajaxurl, ajaxData, function( result ) {
		callback( result );	
	});	
}

pigQuery( document ).ready( function() { piggyDoReady(); } );

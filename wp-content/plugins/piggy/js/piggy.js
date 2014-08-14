/* Piggy JS */
/* This file holds all the good sauce for Piggy */
/* Description: JavaScript for the Piggy Web-App */
/* Expected jQuery: v1.5.x */

var jQT = false;

/* Dynamic Startup Image function for jQTouch */
function getPiggyStartupImage() {
	if ( jQuery( 'body.use-startup' ).length ) { 
		return imagesUrl+'startup/startup-'+skinName+'.png';
	} else {
		return '';
	}	
}

/* Work-around to play html5 audio without the quicktime window */
function playSnort() {
	var snortSound = jQuery( '#snort' ).get(0);
	snortSound.play();
}

/* Setup Add To Homescreen */
if ( isPiggyAppleDevice() ) {
	var addToHomeConfig = {
		animationIn: 'bubble',
		animationOut: 'fade',
		startDelay: 400,
		lifespan: 1000 * 60 * 24,	// 24 hours!
		expire: 0,							// Don't expire, it should always be shown
		touchIcon: true,
		message: piggy_install_message
	};
}

/* When we're on a supported iDevice, we're adding secret sauce */
function isPiggyAppleDevice() {
	// dev:
	//	return true;
	return ( 
		( 
		navigator.platform == 'iPhone Simulator' || 
		navigator.platform == 'iPhone' || 
		navigator.platform == 'iPod' 
		) 
		&& typeof orientation != 'undefined' 
		);
}

if ( isPiggyAppleDevice() ) { 
	var touchStartOrClick = 'touchstart'; 
	var touchEndOrClick = 'touchend'; 
} else {
	var touchStartOrClick = 'click'; 
	var touchEndOrClick = 'click'; 
};

/* Not complete, this will do reload of data on new sales */

function newPurchaseCheck() {
	var currentTime = new Date();
//	var month = currentTime.getMonth() + 1;
//	var day = currentTime.getDate();
//	var year = currentTime.getFullYear();
	var militaryHours = currentTime.getHours();
	var hours = currentTime.getHours();
	var minutes = currentTime.getMinutes();
	if ( hours > 12 ) { 
		hours = hours - 12 
	}
	if ( minutes < 10 ){ 
		minutes = "0" + minutes 
	}
	
	if( militaryHours > 11 ) {
		var meridian = 'pm';
	} else {
		var meridian = 'am';
	}
	var updatedTime = hours + ':' + minutes + ' ' + meridian;

	jQuery.get( piggyWordPressURL + '/?piggy_purchase_hash=1', function( response, status, xhr ) {
		if ( status == 'error' ) {
			console.log( xhr.status + ' ' + xhr.statusText );	
		}
		
		if ( response != purchaseHash ) {
			console.log( 'New purchase(s) found, Cleared purchase check interval. Refreshing Piggy.' );
			refreshPiggy();
			purchaseHash = response;			
		} else {
			jQuery( '.last-updated' ).html( piggy_last_checked + '<br/>' + updatedTime );
			console.log( 'No new purchases.' );
		}
	});
}

var purchaseChecker = '';
function piggyStartInterval() {
	if ( purchaseChecker == '' ) {
		purchaseChecker = window.setInterval( 'newPurchaseCheck()', 1000 * 30 );  // check for new purchases every 30 seconds
		console.log( 'Puchase check interval set (30 seconds).' )
	} else {
		console.log( 'Puchase check interval already started.' )
	}
}

/* Let's dyanmically set scroll pane heights, less CSS work */
function updatePaneHeight() {
	if ( isPiggyAppleDevice() ) {
		jQuery( '.scrollwrap' ).each( function() {
			var windowHeight = jQuery( window ).height();
			var headerHeight = jQuery( '.toolbar' ).height();
			if ( jQuery( this ).attr( 'id' ) === 'home-pane' ) {
				var filterHeight = ( jQuery( '.filterbar' ).height() - 10 );
			} else {
				var filterHeight = 0;		
			}
			var currentHeight = windowHeight - headerHeight - filterHeight;
			jQuery( this ).css( 'height', currentHeight );
		});
	} else {
		jQuery( '.scrollwrap' ).each( function() {
			jQuery( this ).css( 'height', 'auto' );
		});
	}
}

function setup_iScrolls() {
	if ( isPiggyAppleDevice() ) {
		jQuery( '.scrollwrap' ).each( function(){
			var scroller = jQuery( this ).attr( 'id' );
			currentScroller = new iScroll( scroller, { 
			//	bounceLock: true,
				allowPropagation: true,
				checkDOMChanges: false,
				desktopCompatibility: false
			});
			jQuery( this ).data( 'scroller', currentScroller );
		});
	}
}

function destroy_iScrolls() {
	if ( isPiggyAppleDevice() ) {
		jQuery( '.scrollwrap' ).each( function(){
			scroller = jQuery( this ).data( 'scroller' );
			delete scroller;
			scroller = null;
			jQuery( this ).data( 'scroller', '' );
		});
	}	
}

function refreshPanes() {
	updatePaneHeight();
	if ( isPiggyAppleDevice() && window.navigator.standalone ) {
		var currentPane = jQuery( '.current .scrollwrap' ).data( 'scroller' );
		setTimeout( function() { currentPane.refresh(); }, 0 );
	}
}

function refreshPiggy() {
	jQuery( '#refresh' ).addClass( 'active' );

	if ( isPiggyAppleDevice() ) {
					
		jQuery( 'body' ).load( piggyAjaxUrl + 'body > *', function( response, status, xhr ) {
			if ( status == "error" ) {
				console.log( xhr.status + " " + xhr.statusText );	
			}

			//	jQuery( 'body' ).removeClass( 'shake' );  --(not needed for now)--

			destroy_iScrolls();
			jQuery( document ).unbind().die();
			delete jQT;
			jQT = null;
			
			/* Create new jQTouch object */
			jQT = new jQuery.jQTouch({
				slideSelector: 'a.slide',
				submitSelector: '.button-primary',
				cacheGetRequests: false,
				useFastTouch: true
			});
		
			doPiggyReady( false );		
		});
	} else {
		/* Android: */
		window.location.reload();
	}
}

	if ( isPiggyAppleDevice() ) {
		/* No touchmove, please */
		document.addEventListener( 'touchmove', function( e ){ e.preventDefault(); }, false );
		
		/* Add the orientation pane height and iscroll refreshes */	
		window.addEventListener( 'orientationchange', function( e ) { refreshPanes(); } );
	}
	
/* New cache manifest functions for console status updates */
/*
var webappCache = window.applicationCache;

function cacheCheck() {
	var message = 'Checking cache manifest for changes...';
//	alert( message );
	console.log( message );
	jQuery( '#cache-status' ).html( message );
}

function cacheNoUpdate() {
	var message = 'Cache is up to date.';
//	alert( message );
	console.log( message );
	setTimeout( function() { newPurchaseCheck(); }, 1000);
//	jQuery( '#cache-status' ).html( message );
}

function cacheDownload() {
	var message = 'Cache changed. Fetching list of new cache items...';
//	alert( message );
	console.log( message );
	jQuery( '#cache-status' ).html( message );
}
 
  function cacheDownloadProgress() {
	var message = 'Downloading manifest item...';
	console.log( message );
	jQuery( '#cache-status' ).html( message );
  }
 
function cacheUpdate() {
	var message1 = 'Update completed, swapping cache...';
	var message2 = 'Cache swapped.';
	var message3 = 'Refreshing Piggy...';

	jQuery( '#cache-status' ).html( message1 );
	webappCache.swapCache();
	console.log( message1 );
	console.log( message2 );
	console.log( message3 );
	jQuery( '#cache-status' ).html( message3);
	refreshPiggy();
}

function cacheComplete() {
	var message = 'Cache completed.';
	console.log( message );
	jQuery( '#cache-status' ).html( message );
}

function cacheObsolete() {
	var message = 'The manifest was found to have become a 404 or 410 page, so the application cache is being deleted.';
	alert( message );
	console.log( message );
	jQuery( '#cache-status' ).html( message );
}

function cacheError() {
	var message = 'Error. Cache failed to update.';
	alert( message );
	console.log( message );
	jQuery( '#cache-status' ).html( message );
}

webappCache.addEventListener("checking", cacheCheck, false);
webappCache.addEventListener("noupdate", cacheNoUpdate, false);
webappCache.addEventListener("downloading", cacheDownload, false);
webappCache.addEventListener("progress", cacheDownloadProgress, false);
webappCache.addEventListener("updateready", cacheUpdate, false);
webappCache.addEventListener("cached", cacheComplete, false);
webappCache.addEventListener("obsolete", cacheObsolete, false);
webappCache.addEventListener("error", cacheError, false);
*/


function disclosureToggles() {
	jQuery( 'li.today' ).bind( 'click', function(){
		jQuery( '.disclosure', this ).toggleClass( 'open' );
		jQuery( 'div', this ).toggle();
	});
}


/*  On Document Ready */	
function doPiggyReady( createJQT ) {
	if ( createJQT ) {
		/* Setup JQTouch */	
		jQT = new jQuery.jQTouch({
			statusBar: 'black',
			slideSelector: 'a.slide',
			startupScreen: getPiggyStartupImage(),
			submitSelector: '.button-primary',
			cacheGetRequests: false,
			useFastTouch: true,
			preloadImages: [
				imagesUrl+"back-buttons/"+skinName+".png",
				imagesUrl+"back-buttons/"+skinName+"-active.png",
				imagesUrl+"spinners/spinner-"+skinName+".gif",
				imagesUrl+"arrow.png",
				imagesUrl+"arrow@2x.png",
				imagesUrl+"piggy-metal.png"
			]
		});	
	}

	/* Add an animationEnd Refresh */	
	jQuery( document ).bind('pageAnimationEnd', function( e ) {
		jQuery( '.toolbar a.back, .toolbar a.goback' ).removeClass( 'active' );
		if ( isPiggyAppleDevice() ) {
			refreshPanes();
		}
	});
	
	/* Make the toolbar scroll the current pane to the top when tapped */
	if ( isPiggyAppleDevice() ) {
		jQuery( '.toolbar h1' ).bind( touchStartOrClick, function( e ) {
			var currentPane = jQuery( '.current .scrollwrap' ).data( 'scroller' );
			currentPane.scrollTo( 0, 0, 100 );
		});
	}

	/* Start the purchase check interval */
	piggyStartInterval();

	/*  Setup dynamic iScrolls */
	setup_iScrolls();
	refreshPanes();

	/*  Refresh button code */
	jQuery( '#refresh' ).bind( touchStartOrClick, function(){
		refreshPiggy();
	});
	
	/* Load the Disclosure Toggle function for Today stats (v1.3) */
	disclosureToggles();

	/* Prompt for _blank external links */
	jQuery( 'a[target="_blank"]' ).click( function( e ) {
	    if ( confirm( piggy_external_link_msg ) ) {
	        return true;
	    } else {
	        jQuery( this ).removeClass( 'active' );
	        e.preventDefault();
	        e.stopImmediatePropagation();
	    }
	});
	
	/* Trap logout link */
	jQuery( 'a.logout' ).tap( function( e ) {
		var logoutLink = jQuery( this ).attr( 'href' );
		jQuery.cookie( 'piggy_data', null, { path: '/' } );
		jQuery.cookie( 'piggy_hash', null, { path: '/' } );
		if ( isPiggyAppleDevice() ) {
			setTimeout(function(){ refreshPiggy(); }, 550);
		} else {
			setTimeout(function(){ window.location.reload(); }, 550);					
		}		
		e.preventDefault();
	});
	
	/* Have to do this because CSS doesn't support parent selectors */
	jQuery( 'ul.main-list li:first-child a' ).addClass( 'top-borders' );
	jQuery( 'ul.main-list li:last-child a' ).addClass( 'bottom-borders' );

	/* Trigger the snort when the info button is tapped */
	jQuery( "a#info" ).tap( function() {
		playSnort();
	});
	
	var metalPig = jQuery( 'a#metal-piggy' );

	metalPig.tap( function() {
		playSnort();
		metalPig.addClass( 'snort' );
		setTimeout(function(){ metalPig.removeClass( 'snort' ); }, 250);					
	});

	jQuery( '#passcode input' ).keyup( function() {		
		var inputField = jQuery( this );
		if ( inputField.val().length == passKeyNumber ) {
			var passkey = inputField.val();

			inputField.blur();

			var currentTimeout = 1;
			jQuery.post( piggyAjaxUrl, { piggyPassKey: passkey }, function( response ) {
				var jsonResponse = eval('(' + response + ')');
				if ( jsonResponse.result == 'pass' ) {
					//pass			
					
					if ( requirePasscode ) {
						// Use session cookies only
						jQuery.cookie( 'piggy_data', jsonResponse.ip );
						jQuery.cookie( 'piggy_hash', jsonResponse.hash );		
					} else {
						// Use permanent cookies
						jQuery.cookie( 'piggy_data', jsonResponse.ip, { path: '/', expires: 365 } );
						jQuery.cookie( 'piggy_hash', jsonResponse.hash, { path: '/', expires: 365 } );						
					}
					inputField.addClass( 'correct' ).val( '' );
					setTimeout(function(){ jQuery( '#passcode' ).toggleClass( 'ok' ); }, 330);
					if ( isPiggyAppleDevice() ) {
						setTimeout(function(){ refreshPiggy(); }, 550);
					} else {
						setTimeout(function(){ window.location.reload(); }, 550);					
					}
				} else {
					// slight delay to make password guessing impractical
					setTimeout( function() {
							// failure
							inputField.addClass( 'incorrect' );
							setTimeout(function(){ inputField.removeClass( 'incorrect' ).val( '' ); }, 1250 );
							
							// Remove cookies for good measure
							jQuery.cookie( 'piggy_data', null, { path: '/' } );
							jQuery.cookie( 'piggy_hash', null, { path: '/' } );
							
							currentTimeout = currentTimeout + 1;
						},
						currentTimeout * 550
					);
				}
			});
		}
	});
	
	/* Header Filter Tabs */	
	var tabContainers = jQuery( 'div.tab-wrap' );	
    jQuery( '.filterbar li' ).bind( touchStartOrClick, function( e ) {
    	var thisID = jQuery( this ).attr( 'id' );
    	var thisRel = jQuery( this ).attr( 'rel' );

        tabContainers.hide().filter( thisRel ).show();
    	
    	jQuery( '.filterbar li' ).removeClass( 'selected' );
   		jQuery( this ).addClass( 'selected' );

		if ( isPiggyAppleDevice() ) {
			var currentPane = jQuery( '.current .scrollwrap' ).data( 'scroller' );
			setTimeout( function() { currentPane.refresh(); }, 100 );
		}

    	jQuery.cookie( 'piggy_tab_id', thisID, { path: '/', expires: 365} );

    });	
	
	/* Active tab cookie */
	var currentTab = jQuery.cookie( 'piggy_tab_id' );
	if ( currentTab && currentTab.length ) {
		jQuery( '#' + currentTab ).trigger( touchStartOrClick );
	} else {
		jQuery( '.filterbar li:first' ).trigger( touchStartOrClick );
	}
	
	/* Skin change cookie */	
	var themeSelect = jQuery( 'select.theme-color' );
	themeSelect.change( function() {
		var selectedSkin = jQuery( this ).val();
		if( selectedSkin ) {
			jQuery.cookie( 'piggy_skin', selectedSkin, { path: '/', expires: 365} );
			setTimeout( function() { alert( piggy_restart_msg ); }, 0 );
		}
	});
	

} 
/* End Document Ready */

jQuery( document ).ready( function() { doPiggyReady( true ); });
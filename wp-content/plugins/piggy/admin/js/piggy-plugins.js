//Cookie
jQuery.cookie = function(name, value, options) {
if (typeof value != 'undefined') {
options = options || {};
if (value === null) {
value = '';
options.expires = -1;
}
var expires = '';
if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
var date;
if (typeof options.expires == 'number') {
date = new Date();
date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
} else {
date = options.expires;
}
expires = '; expires=' + date.toUTCString();
}
var path = options.path ? '; path=' + (options.path) : '';
var domain = options.domain ? '; domain=' + (options.domain) : '';
var secure = options.secure ? '; secure' : '';
document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
} else {
var cookieValue = null;
if (document.cookie && document.cookie != '') {
var cookies = document.cookie.split(';');
for (var i = 0; i < cookies.length; i++) {
var cookie = jQuery.trim(cookies[i]);
if (cookie.substring(0, name.length + 1) == (name + '=')) {
cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
break;
}
}
}
return cookieValue;
}
};

function doBncTooltip( selector, tooltip_id, x_offset, y_offset, x_pos_word ) {
	jQuery( selector ).live( 'mouseover', function() { 
		var tooltipText = jQuery( this ).attr( 'title' );
		jQuery( this ).attr( 'title', '' );
		
		var offset = jQuery( this ).offset();
		
		jQuery( tooltip_id ).html( tooltipText );
		
		var h = jQuery( tooltip_id ).height();
		var w = 0;
		if ( x_pos_word == 'right' ) {
			w = -jQuery( tooltip_id ).width() - x_offset;	
		} else {
			w = x_offset;
		}
	
		jQuery( tooltip_id ).css( 'left', ( offset.left + w ) + 'px' ).css( 'top', ( offset.top + y_offset - h ) + 'px' ).fadeIn( 250 );
		jQuery( tooltip_id ).css( 'position', 'absolute' );
	} ).live( 'mouseout', function() { 
		var tooltipText = jQuery( tooltip_id ).html();
		jQuery( this ).attr( 'title', tooltipText );
		jQuery( tooltip_id ).fadeOut( 250 );
	} );
}
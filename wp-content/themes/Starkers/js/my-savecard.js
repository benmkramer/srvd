// IMMEDIATE

var WPSC_GoldCart = {
	"displayMode":"default",
	"productListClass":"wpsc_default_product_list"
};

var gratuity_value = 0;
var cart_total = 0;
var cart_subtotal = 0;
var gra = 0, gra_new = 0;
var gra_formatted = 0;
var cart_discount = 0;
var tax_total=0;

Number.prototype.toMoney = function() {
	return "$" + this.toFixed(2);
}

var myScroll;
function startScroll() {
	myScroll = new iScroll('checkout-w', {
		useTransform: false,
		onBeforeScrollStart: function (e) {
			var target = e.target;
			while (target.nodeType != 1) target = target.parentNode;
			if (target.tagName != 'SELECT' && target.tagName != 'INPUT' && target.tagName != 'TEXTAREA')
				e.preventDefault();
		}
	});
}

document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
document.addEventListener('DOMContentLoaded', startScroll, false);

// FUNCTIONS

function checkOld(id) {
	$("#check-new,.check-old,#check-cash").removeClass("checked-down");
	$("#check-old-"+id).addClass("checked-down");
	$(".saved-card-block,#creditCardNew").css("opacity",".5");
	$("#"+id).css("opacity","1");
	$(".authNetPaymentInput").attr("disabled",true);
	$(".authNetPreSelect,#input_wpsc_merchant_testmode").attr('checked',false);
	$("#saved-visa-"+id).attr('checked',true);
	$("#payType").val('preset');
};

function hideTables() {
	$("#delivery-wrapper").hide();
}

function gratuityStop() {
	$("#gratuity-minus,#gratuity-plus").unbind("click");
}

function gratuityPlusOne() {
	$("#gratuity-plus").click(function(){
		gratuityStop();
		gratuityPlus();
		gratuityPlusTwo();
	});
}

function gratuityPlusTwo() {
	$("#gratuity-minus").click(function(){
		gratuityMinusOne();
	});
	$("#gratuity-plus").click(function(){
		gratuityPlusOne();
	});
}

function gratuityMinusOne() {
	$("#gratuity-minus").click(function(){
		gratuityStop();
		gratuityMinus();
		gratuityMinusTwo();
	});
}

function gratuityMinusTwo() {
	$("#gratuity-minus").click(function(){
			gratuityMinusOne();
	});
	$("#gratuity-plus").click(function(){
			gratuityPlusOne();
	});
}

function gratuityPlus() {
	gra = 1*$("#gratuity-value").text();
	gra_new = gra + 5;
	gra_formatted = cart_subtotal * gra_new/100;
	if ( gra < 100 ) {
		$("#gratuity-value").text( gra_new ); 
		// $("#gratuity-amount span").text ( "$"+gra_formatted.toFixed(2) );		
		$("#gratuity-amount span").text ( gra_formatted.toMoney() );
		// $("#total-amount span").text( (cart_subtotal + gra_formatted - cart_discount).toMoney() );
		var cart_subtotal_discount = Math.max(0, cart_subtotal-cart_discount); 
		$("#total-amount span").text( (cart_subtotal_discount + gra_formatted + tax_total).toMoney() );
		$("#base_shipping").val(gra_formatted);	
	}
}

function gratuityMinus(event) {
	gra = 1*$("#gratuity-value").text();
	gra_new = gra - 5;
	gra_formatted = cart_subtotal * gra_new/100;
	if ( gra >= 5 ) {
		$("#gratuity-value").text( gra_new ); 
		// $("#gratuity-amount span").text ( "$"+gra_formatted.toFixed(2) );		
		$("#gratuity-amount span").text ( gra_formatted.toMoney());
		var cart_subtotal_discount = Math.max(0, cart_subtotal-cart_discount); 
		$("#total-amount span").text( (cart_subtotal_discount + gra_formatted + tax_total).toMoney() );
		// alert( $("#base_shipping").val() );
		$("#base_shipping").val(gra_formatted);
	}
}

// EVENT LISTENERS

$(function() {

	$("#gratuity-value, #gratuity-amount .pricedisplay, #tax-amount .pricedisplay").html(0);
	$("#total-amount .pricedisplay").html("$1.00");

	setTimeout(function() {
		$("#jqt").css("opacity","1");
		setTimeout(function (){myScroll.refresh()},0);
	},300);
		
	$(".check-old").trigger('click');
	
	if ($(".mini-card").length > 0){
		$("#saveCreditCard,#check-new").css("display","inline-block");
		$("#creditCardNew").css("height","175px");
	};
	
	$("#gratuity-plus").click(function(){
		gratuityPlus();
	});

	$("#gratuity-minus").click(function(){
		gratuityMinus();
	});

	$("#check-new").click(function() {
		$("#check-new").addClass('checked-down');
		$(".check-old,#check-cash").removeClass('checked-down');
		$(".saved-card-block").css('opacity','.5');
		$("#creditCardNew").css('opacity','1');
		$(".authNetPreSelect,#input_wpsc_merchant_testmode").attr('checked', false);
		$(".authNetPaymentInput").removeAttr('disabled');
		$("#payType").val('creditCardForms');
	});

	$(".wpsc_merchant_testmode #check-cash").click(function() {
		$("#check-new,.check-old,#check-cash").removeClass("checked-down");
		$(".wpsc_merchant_testmode #check-cash").addClass("checked-down"); 
		$(".saved-card-block,#creditCardNew").css('opacity','.5');
		$(".wpsc_merchant_testmode").css('opacity','1');
		$(".authNetPreSelect").attr('checked',false);
		$(".authNetPaymentInput").attr('disabled',true);
			$("#input_wpsc_merchant_testmode").attr('checked',false);		
		$("#payType").val('none');
	});

	$("#purchase-it").click(function(){
		$("#purchase-it").addClass("disabled");
	});

	$(".x-btn,#purchase-it,.update,.update-plus,.update-minus").click(function(){
		setTimeout("$('#page-wrap').show()",1200);
		window.parent.startSpin();
		setTimeout("window.parent.stopSpin()",7000);
	});

	$("#bar-btn").click(function(){
		$("#bar-btn").addClass('bar-clicked');
		$("#table-btn").removeClass('table-clicked');
		$("#table-area").css('opacity','.25');
		$("#seating").val('');
		$("#seating").attr('disabled','disabled');
	});

	$("#table-btn").click(function(){
		$("#table-btn").addClass('table-clicked');
		$("#bar-btn").removeClass('bar-clicked');
		$("#table-area").css('opacity','1');
		$("#seating").removeAttr('disabled');
	});
	
	setTimeout("window.parent.stopSpin()",700);

});


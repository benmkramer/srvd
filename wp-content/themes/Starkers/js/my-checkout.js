// IMMEDIATE

document.addEventListener('touchmove', function(e){ e.preventDefault();},false);

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

function cardDashes(f) {
	//f.value = f.value.replace(/[^0-9]/g,'');
	//f.value = f.value.slice(0,4)+"-"+f.value.slice(4,10)+"-"+f.value.slice(10,16);
}

function authOnly() {
	$("#total-wrap,.saved-cards,.or-line1,.or-line2,#check-new,#saveCreditCard").hide();
	$("#no-cards").show();
	$("#purchase-it").val("Save Card");
}

// EVENT LISTENERS

$(function() {

	evname = window.Touch ? 'touchstart' : 'mousedown';
	authonly = window.parent.authonly;

	if (authonly == 1) {
		grandSubtotal = 0;
		grandDiscount = 0;
		grandTax = 0;
		grandGratuity = 0;
		grandTotal = 0.01;
		authOnly();
		$.post("/wp-admin/admin-ajax.php?action=authconfirm&auth=1");
	} else {
		grandSubtotal = (window.parent.cart_subtotal).toFixed(2);
		grandDiscount = (window.parent.cart_discount).toFixed(2);
		grandTax = (window.parent.cart_tax).toFixed(2);
		grandGratuity = (window.parent.cart_gratuity).toFixed(2);
		grandTotal = (window.parent.cart_total).toFixed(2);
		$(".check-old").trigger('click');
		if ($(".mini-card").length > 0){
			$("#saveCreditCard,#check-new").css("display","inline-block");
			$("#creditCardNew").css("height","175px");
		};
		$.post("/wp-admin/admin-ajax.php?action=authconfirm&auth=0");
	}

	$(".checkout-total #total-amount").text("$"+grandTotal);
	$("#base_shipping").val(grandGratuity);
	
	if (window.parent.drinkMax == true) {
		$(".update-plus").addClass("disabled").prop('disabled', true);
	} else {
		$(".update-plus").removeClass("disabled").removeAttr('disabled');
	}

	$("#gratuity-plus").bind(evname, function(){
		new_percent = cart_percent + .05;
		new_gratuity = (cart_subtotal*new_percent).toFixed(2);
		new_total = (cart_subtotal + cart_tax - cart_discount + (cart_subtotal*new_percent)).toFixed(2);
		if ( new_percent <= 1.05 ) {
			$("#gratuity-percent").text((new_percent*100).toFixed());
			$("#gratuity-amount").text(new_gratuity);
			$("#total-amount").text(new_total);
			cart_percent = new_percent;
			cart_gratuity = new_gratuity;
			cart_total = new_total;
			window.parent.saveCart(cart_subtotal,cart_discount,cart_tax,cart_percent,cart_gratuity,cart_total);
		}
	});

	$("#gratuity-minus").bind(evname, function(){
		new_percent = cart_percent - .05;
		new_gratuity = (cart_subtotal*new_percent).toFixed(2);
		new_total = (cart_subtotal + cart_tax - cart_discount + (cart_subtotal*new_percent)).toFixed(2);
		if ( new_percent >= .10 ) {
			$("#gratuity-percent").text((new_percent*100).toFixed());
			$("#gratuity-amount").text(new_gratuity);
			$("#total-amount").text(new_total);
			cart_percent = new_percent;
			cart_gratuity = new_gratuity;
			cart_total = new_total;
			window.parent.saveCart(cart_subtotal,cart_discount,cart_tax,cart_percent,cart_gratuity,cart_total);
		}
	});

	$("#promo-update").click(function() {
		window.parent.startSpin();
		couponfield = $("#coupon_num").val();
		$.post("/wp-admin/admin-ajax.php?action=checkcoupon&coupon="+couponfield, function(data) {
			if (data==1) {
				window.parent.goodPromo();
			    document.getElementById("promo-submit").click();
			} else {
				window.parent.badPromo();
				setTimeout("window.parent.stopSpin()",700);
			}
		});
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

	$(".x-btn,#purchase-it").click(function(){
		window.parent.startSpin();
	});

	$(".update-minus").click(function(){
		window.parent.minusCount();
		window.parent.startSpin();
	});

	$(".update-plus").click(function(){
		window.parent.addCount();
		window.parent.startSpin();
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

	$.post("/wp-admin/admin-ajax.php?action=lastvisited", function(lastbar) {
		engravetext = $.trim(lastbar);
		$("#engravetext").val(engravetext);
	});

	setTimeout(function() {
		setTimeout("window.parent.$('.checkout').css('top','5px');",300);
		setTimeout("$('#jqt').css('opacity','1');",300);
		setTimeout("window.parent.stopSpin()",300);
	},300);

});


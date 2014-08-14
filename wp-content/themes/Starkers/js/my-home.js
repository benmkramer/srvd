// JQTOUCH

var jQT = new $.jQTouch({
    preloadImages: [
		'http://cdn.flowtab.mobi/img/sprite-home14.png',
		'http://cdn.flowtab.mobi/img/chicago9.jpg',
		'http://cdn.flowtab.mobi/img/dropbar.jpg',
		'http://cdn.flowtab.mobi/img/pattern.png',
		'http://cdn.flowtab.mobi/img/topbar.png',
		//'http://cdn.flowtab.mobi/img/taps.jpg'
		'http://nick.hesling.com:81/wp-content/themes/Starkers/img/srvd-logo.png'
    ]
});

document.addEventListener('touchmove', function(ev) { ev.preventDefault(); }, false);

// VARIABLES

var viewport = {width:$(window).width(),height:$(window).height()};
var number, token, last4, code, orders, earnings, wifi, hasCard, barOpen, barTables, barHappy, pickupArea, myHist, tempCat, prodName, cartPromo, registerResponse, myScroll, pullDownEl, pullDownOffset, pullUpEl, pullUpOffset, bugReport, feedbackReport, prodKey, cartKey, cartCount, cartSubt, cartDisc, cartTax, cartPercent, cartGrat, cartTotal, myAbout, myRewards, mySecurity, myLocations, myCategories, myBeer, myWine, myCocktails, myShooters, mySoftDrinks, myFood, newUser, timer, cartCount, newCount, drinkMax, minApplied, href, barID, barName, username, beerMenu, wineMenu, featuredMenu, wellMenu, premiumMenu, customMenu, vodkaMenu, rumMenu, whiskeyMenu, tequilaMenu, otherMenu, softDrinksMenu, foodMenu, mixersMenu, mixer, category;
var newRegister = 0, acctUpdated = 0, tablesActive = 0, tableNumber = 0, allowAction = 0, realCheckout = 0, loginDisabled = 0, clickedAffiliate = 0, mikeWait = 0, customWait = 0;

// STRIPE

if ( (document.domain == 'beta.flowtab.mobi') || (document.domain == 'nick.hesling.com:81') ) {
	Stripe.setPublishableKey("pk_test_WzcGjaLEaFqJueYvv5IUic1l");
} else { Stripe.setPublishableKey("pk_test_WzcGjaLEaFqJueYvv5IUic1l");}

// FUNCTIONS

function resetSwipe() {
	$("#mixers,.mixer-item").css("width",viewport.width);
	var swiperCar = $("#mixers").swiper({
		slidesPerSlide : 3
	});
	$(".mixer-item").tap(function(){
		if (newCount < 4) {
			mixer = $(this).attr("mixer"); customWait = 0;
			$(".mixer-item").css("background","#F9F9F9");
			$(this).css("background","lightcyan");
			$(".p-mixer").remove();
			$(".p-item").removeClass("disabled");		
			$("#custom .p-desc").html("Mixed with "+mixer+".");
		};
	});
}

function pullDownAction() {
	$.get("/wp-admin/admin-ajax.php?action=getprofile", function(data) {
		$("#locations-div").html(data);
		setTimeout(function(){
			$("#locations li").addClass("arrow categories location tapleft").attr("href","#categories");
			$("#locations .title").before("<div class='bar-img'></div>");
			$("#locations .addy").after("<div class='cat-arrow'></div>");
			$(".tapleft").tap(function(){
				href = $(this).attr("href");
				jQT.goTo($(href),"slideleft");
			}); setLocations(); stopSpin(); //resetPullDown();
		},100);
	});
}

function startScroll() {
	myAbout = new iScroll('about-w');
	myLocations = new iScroll('locations-w');
	myCategories = new iScroll('categories-w');
	myBeer = new iScroll('beer-w');
	myWine = new iScroll('wine-w');
	myCocktails = new iScroll('cocktails-w');
	myFeatured = new iScroll('featured-w');
	myWell = new iScroll('well-w');
	myPremium = new iScroll('premium-w');
	myCustom = new iScroll('custom-w');
	myShooters = new iScroll('shooters-w');
	myVodka = new iScroll('vodka-w');
	myRum = new iScroll('rum-w');
	myWhiskey = new iScroll('whiskey-w');
	myTequila = new iScroll('tequila-w');
	myOther = new iScroll('other-w');
	mySoftDrinks = new iScroll('soft-drinks-w');
	myFood = new iScroll('food-w');
	mySecurity = new iScroll('security-w');
	myAffiliate = new iScroll('affiliate-w');	
}

function stopSpin(){
	clearTimeout(timer); timer = 0;
	$("#spinner-wrap,#spinner-box,#stuck-loading").hide();
	$(".secure").removeClass("disabled").removeAttr("disabled");
	$(".make-purchase").removeClass("disabled").removeAttr("disabled");
}

function startSpin(){
	$("#spinner-wrap,#spinner-box").show();
	timer = setTimeout("$('#stuck-loading').show();",12000);
}

function checkLogin() {
	setTimeout("$('#logout,#homepage').css('height','0')",300);
	$.get("/wp-admin/admin-ajax.php?action=getprofile", function(data) {
		$("#locations-div").html(data);
		setTimeout(function(){
			$("#locations li").addClass("arrow categories location tapleft").attr("href","#categories");
			$("#locations .title").before("<div class='bar-img'></div>");
			$("#locations .addy").after("<div class='cat-arrow'></div>");
			$(".tapleft").tap(function(){
				href = $(this).attr("href");
				jQT.goTo($(href),"slideleft");
			}); setLocations();
		},75);
		setTimeout(function(){
			login = $("#get-login").text();
			if (login==1 ) {
				url = $("#get-url").text();
				userid = $("#get-userid").text();
				fname = $("#get-fname").text();
				lname = $("#get-lname").text();
				email = $("#get-email").text();
				phone = $("#get-phone").text();
				token = $("#get-token").text();
				last4 = $("#get-last4").text();
				code = $("#get-code").text();
				orders = $("#get-orders").text();
				earnings = $("#get-earnings").text();
				if (last4 > 0) { hasCard = 1; };
				callCheckout();
			};
			if (newRegister == 1){
				firstAuthorize();
			};
		},100);
		setTimeout(function(){		
	 		if (login==1 ) {
	 			$("#logout").hide();
				$("#homepage").show();
				$("#big-name").html(fname);
				$("#first_name2").val(fname);
				$("#last_name2").val(lname);
				$("#email2").val(email);
				$("#user_phone2").val(phone);
				$("#aff-code").html(code);
				$("#aff-orders").html(orders);
				$("#aff-earnings").html("$"+earnings);
				if ((acctUpdated == 0) && (newRegister == 0)) {
					jQT.goTo($('#locations'),'flipleft');
					setTimeout(function(){myLocations.refresh()},0);
					acctUpdated = 0;
				}
				setTimeout("stopSpin()",700);
				setTimeout( function(){
					$(".secure").val('');
					$("#homepage").css("height","100%");
				},1000);
	 		} else {
				$("#homepage").hide(); $("#logout").show();
				setTimeout("$('#logout').css('height','100%');",300);
				setTimeout("$('#home-out').css('height','90px');",700);
				setTimeout("$('#home-out').css('height','75px');",1100);
				setTimeout("$('#smally').css('opacity','1');",1300);
				setTimeout("picSwap()",300);
				setTimeout("stopSpin()",700);
			}
		},125);
	});
};

function picSwap(){
	// Checkers
	$('#checkers-box').css('opacity','1');
	$('#checkers').css('-webkit-transform','scale(1.5)');
	// Barpad
	setTimeout("$('#checkers-box').css('opacity','0')",5000);
	setTimeout("$('#barpad-box').css('opacity','1')",5000);
	setTimeout("$('#barpad').css('-webkit-transform','scale(1.5)')",5000);
	setTimeout("$('#checkers').css('-webkit-transform','scale(1.1)')",5000);
	// Taps
	setTimeout("$('#barpad-box').css('opacity','0')",10000);
	setTimeout("$('#taps-box').css('opacity','1')",10000);
	setTimeout("$('#taps').css('-webkit-transform','scale(1.5)')",10000);
	setTimeout("$('#barpad').css('-webkit-transform','scale(1.1)')",10000);
	// Restart
	setTimeout("$('#taps-box').css('opacity','0')",15000);
	setTimeout("$('#taps').css('-webkit-transform','scale(1.1)')",15000);
	setTimeout("picSwap()",15000);
}

function hideCount(){
	$(".count").css("top","-20px");
	$(".cart").css("top","-44px");
	setTimeout("$('.count').text(0)",300);
	$(".buy-button").removeClass("disabled").removeAttr('disabled');
	cartCount = 0;
	newCount = 0;
}

function emptyCart(){
	$.post("/wp-admin/admin-ajax.php?action=empty");
	hideCount();
}

function cartEmpty(){
	hideCount();
	jQT.goTo($("#"+myHist),"slideright");
	setTimeout("stopSpin()",700);
	setTimeout("$('.clear-back').show();",300);
}

function setLocations() {
	$(".location").tap(function(){
		startSpin(); //freezePullDown(); 
		$("#categories .arrow, #cocktails .arrow, #shooters .arrow").hide();
		barID = $(this).attr("barid");
		barName = $(this).attr("barname");
		href = $(this).attr("href");
		$("#menu-storage").load("/wp-admin/admin-ajax.php?action=barmenus&id="+barID, function(){
			beerMenu = $("#b-cat").html();
			wineMenu = $("#w-cat").html();
			featuredMenu = $("#cf-cat").html();
			wellMenu = $("#cw-cat").html();
			premiumMenu = $("#cp-cat").html();
			customMenu = $("#cc-cat").html();
			vodkaMenu = $("#sv-cat").html();
			rumMenu = $("#sr-cat").html();
			whiskeyMenu = $("#sw-cat").html();
			tequilaMenu = $("#st-cat").html();
			otherMenu = $("#so-cat").html();
			softDrinksMenu = $("#sd-cat").html();
			foodMenu = $("#f-cat").html();
			mixersMenu = $("#cm-cat").html();
			
			barHappy = $("#happy").html();
			barOpen = $("#open").html();
			barTables = $("#tables").html();
			barWifi = $("#wifi").html();

			$("#beer .menus").html(beerMenu);
			$("#wine .menus").html(wineMenu);
			$("#featured .menus").html(featuredMenu);
			$("#well .menus").html(wellMenu);
			$("#premium .menus").html(premiumMenu);
			$("#custom .menus").html(customMenu);
			$("#vodka .menus").html(vodkaMenu);
			$("#rum .menus").html(rumMenu);
			$("#whiskey .menus").html(whiskeyMenu);
			$("#tequila .menus").html(tequilaMenu);
			$("#other .menus").html(otherMenu);
			$("#soft-drinks .menus").html(softDrinksMenu);
			$("#food .menus").html(foodMenu);
			$("#custom .swiper-wrapper").html(mixersMenu);

			if (beerMenu) { $("#categories .beer").show(); };
			if (wineMenu) { $("#categories .wine").show(); };
			if (featuredMenu || wellMenu || premiumMenu || customMenu) { $("#categories .cocktails").show(); };
			if (featuredMenu) { $("#cocktails .featured").show(); };
			if (wellMenu) { $("#cocktails .well").show(); };
			if (premiumMenu) { $("#cocktails .premium").show(); };
			if (customMenu) { $("#cocktails .custom").show(); };
			if (vodkaMenu || rumMenu || whiskeyMenu || tequilaMenu || otherMenu) { $("#categories .shooters").show(); };
			if (vodkaMenu) { $("#shooters .vodka").show(); };
			if (rumMenu) { $("#shooters .rum").show(); };
			if (whiskeyMenu) { $("#shooters .whiskey").show(); };
			if (tequilaMenu) { $("#shooters .tequila").show(); };
			if (otherMenu) { $("#shooters .other").show(); };
			if (softDrinksMenu) { $("#categories .soft-drinks").show(); };
			if (foodMenu) { $("#categories .food").show(); };

			$(".p-sale").after("<div class='imagecol'></div>");
			if (barHappy == 1) {
				$(".p-price").hide(); $(".p-sale").show();
			} else {
				$(".p-price").show(); $(".p-sale").hide();
			}
			if (barTables == 1) {
				$("#table-wrap").show();
			} else {
				$("#table-wrap").hide();
			}
			if (barWifi == 1) {
				$(".wifi-alert").show().css("top","44px");
				setTimeout(function(){
					$(".wifi-alert").css("top","0");
				},2400);
			}
			stopSpin(); refreshMenu(); resetSwipe();
			newCount = 0; mikeWait = 0;
			$(".buy-button").bind(evname, function(){
				//alert(mikeWait);
				//alert(customWait);
				//alert(newCount);
				if ((mikeWait == 0) && (customWait == 0) && (newCount < 4)) {					
					mikeWait = 1;
					prodKey = $(this).attr("key");
					category = $(this).attr("cat");
					category = category+"-"+barID;
					addDrink(prodKey);
					return false;
				}
			});
		});
		$("#bar-name").html(barName);
		jQT.goTo($(href),"slideleft");
	});
	$("li").bind("touchstart", function(){
		$(this).css("background","#EFEFEF");
	});
	$("li").bind("touchend", function(){
		$(this).css("background","none");
	});
}

function addCount(){
	$(".cart").css("top","5px");
	$(".count").css("top","19px");
	setTimeout("cartCount = parseInt($('.count').text(),10);",100);
	setTimeout("newCount = parseInt(parseFloat(cartCount)) + 1;",125);
	setTimeout(function(){
		if (newCount < 4) {
			$('.count').text(newCount);
			$(".order-confirm").show().css("top","44px");
			setTimeout(function(){
				$(".order-confirm").css("top","0");
			},1200);
		}
		if (newCount == 4) {
			drinkMax = 1;
			$(".buy-button,#mixers").addClass("disabled");
			$('.count').text(newCount);
			$(".max-drinks").show().css("top","44px");
			setTimeout(function(){
				$(".max-drinks").css("top","0");
			},1200);
		};
	},150);
}

function minusCount(){
	drinkMax = 0;
	$(".buy-button,#mixers").removeClass("disabled").removeAttr('disabled');
	$(".cart").css("top","5px");
	$(".count").css("top","19px");
	setTimeout("cartCount = parseInt($('.count').text(),10);",100);
	setTimeout("newCount = parseInt(parseFloat(cartCount)) - 1;",125);
	setTimeout(function(){
		$(".count").text(newCount);
		if (newCount == 0) {
			jQT.goTo($("#"+myHist),"slideright");
			cartEmpty();
		}
	},150);
}

function resetCustom(){
	$("#custom .mixer-item").css("background","#F9F9F9");
	$("#custom .p-item").addClass("disabled");
	$("#custom .p-desc").html("Please choose a mixer...");
	if (myHist == 'custom') {
		customWait = 1;
	};
}

function addDrink(item){
	$("#cart-div").load("/wp-admin/admin-ajax.php?action=addcart&id="+item+"&count=1&mixer="+mixer+"&category="+category, function(){
		callCart(); setTimeout("mikeWait = 0",1200);
	});
	$("#p-"+item+" .buy-button").addClass("buy-rotate");
	$(".clear-back,.clear").show(); addCount();
	resetCustom();
	setTimeout(function(){
		$("#p-"+item+" .buy-button").removeClass("buy-rotate");
	},1200);
}

function goodPromo(){
	spinOverride = 1;
	$(".promo-valid").show().css("top","44px");
	setTimeout(function(){
		$(".promo-valid").css("top","0");
	},2400);
}

function badPromo(){
	$(".promo-invalid").show().css("top","44px");
	setTimeout(function(){
		$(".promo-invalid").css("top","0");
	},2400);
}

function verifyMobile(){
	$(".mobile-invalid").show().css("top","44px");
	setTimeout(function(){
		$(".mobile-invalid").css("top","0");
	},2400);
	$.post("/wp-admin/admin-ajax.php?action=sendmobile");
}

function sendSMS(id) {
	startSpin();setTimeout("stopSpin()",700);
		$.post(id);
}

function isNumberKey(evt) {
	var charCode = (evt.which) ? evt.which : event.keyCode;
	if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
		return false;
	} else {
		return true;
	}      
}

function offerDone(){
	$("#savecard .promo").hide();
	$("#savecard .start").show();
}

function refreshMenu() {
	loadedHappy = false;
	loadedBeer = false;
	loadedWine = false;
	loadedCocktails = false;
	loadedShooters = false;
	loadedSoftDrinks = false;
	loadedFood = false;
}

function loginDashes(f) {
	if ($(f).val().length > 9) {
		//f.value = f.value.replace(/[^0-9]/g,'');
		//f.value = f.value.slice(0,3)+"-"+f.value.slice(3,6)+"-"+f.value.slice(6,12);
		$(f).css("box-shadow","none");
		$("#login-btn,#register-btn").removeClass("disabled");
		loginDisabled = 0;
	} else {
		$(f).css("box-shadow","inset #900 0 0 5px 0;");
		$("#login-btn,#register-btn").addClass("disabled");
		loginDisabled = 1;
	}
}

function callCart(){
	cartSubt = $("#cart-subt").text();
	cartDisc = $("#cart-disc").text();
	cartTax = $("#cart-tax").text();
	cartPercent = 20;
	cartGrat = (cartSubt*.20).toFixed(2);
	cartTotal = (1*cartSubt - 1*cartDisc + 1*cartTax + 1*cartGrat).toFixed(2);
	if (1*cartDisc > 0) {
		$("#discount-wrap").show();
	} else {
		$("#discount-wrap").hide();
	}
	if (1*cartTotal < 1*cartGrat) {
		minApplied = 1;
		minTotal = cartGrat;
	} else {
		minApplied = 0;
	}
	setTimeout(function(){
		$("#subtotal-amount").text("$"+cartSubt);
		$("#discount-amount").text("$"+cartDisc);
		$("#tax-amount").text("$"+cartTax);
		$("#gratuity-percent").text(cartPercent+"%");
		$("#gratuity-amount").text("$"+cartGrat);
		if (minApplied == 1) {
			$(".total-amount").text("$"+minTotal);
			minTotalCents = Math.round(minTotal*100);
			$("#checkout .stripe-amount").val(totalCents);
		} else {
			$(".total-amount").text("$"+cartTotal);
			totalCents = Math.round(cartTotal*100);
			$("#checkout .stripe-amount").val(totalCents);
		}
	},100);
	if (drinkMax == 1) {
		$(".update-plus").addClass("disabled").prop('disabled', true);
	} else {
		$(".update-plus").removeClass("disabled").removeAttr('disabled');
	}
	$(".update-minus").bind(evname, function(){
		cartKey = $(this).attr("key");
		cartCount = $(this).attr("count");
		startSpin(); minusCount();
		$("#cart-div").load("/wp-admin/admin-ajax.php?action=addcart&key="+cartKey+"&count="+cartCount, function(){
			callCart(); stopSpin();
		});	return false;
	});
	$(".update-plus").bind(evname, function(){
		cartKey = $(this).attr("key");
		cartCount = $(this).attr("count");
		startSpin(); addCount();
		$("#cart-div").load("/wp-admin/admin-ajax.php?action=addcart&key="+cartKey+"&count="+cartCount, function(){
			callCart(); stopSpin();
		});	return false;
	});
}

function stripeAuthorize(status, response) {
    if (response.error) {
		$("#authorize .card-declined").show().css("top","44px");
		setTimeout(function(){
			$("#authorize .card-declined").css("top","0");
		},2400); stopSpin();
		$(".secure").removeClass("disabled").removeAttr("disabled");
		$(".make-purchase").removeClass("disabled").removeAttr("disabled");
    } else {
		token = response['id'];
		number = $("#authorize .cc-input").val();
    	last4 = number.substr(number.length-4);
		$("#authorize .stripe-token").val(token);
		$("#authorize .stripe-last4").val(last4);
        $.ajax({
            type: "GET",
            url: "/wp-admin/admin-ajax.php?action=savecard",  
            data: $("#authorize .checkout-form").serialize(),
            dataType: "json",
	   		error: function(){
				$("#authorize .card-declined").show().css("top","44px");
				setTimeout(function(){
					$("#authorize .card-declined").css("top","0");
				},2400); stopSpin();
				$(".secure").removeClass("disabled").removeAttr("disabled");
				$(".make-purchase").removeClass("disabled").removeAttr("disabled");
			},
            success: function(){
				$("#authorize .brands").hide();
				$("#authorize .saved-card").show();
				$("#authorize .card-accepted").show().css("top","44px");
				$("#authorize .delete").css("top","5px");
		    	$("#authorize .stripe-last4").text("•••• •••• •••• "+last4);
		    	setTimeout("stopSpin()",300);
				setTimeout(function(){
					$("#authorize .card-accepted").css("top","0");
				},2400); stopSpin();
				hasCard = 1;
				if (newRegister == 1) {
					setTimeout(function(){
						jQT.goTo($("#locations"),"flipleft");
						newRegister = 0; $(".secure").val("");
					},1500);
				} else {
					$("#authorize .make-purchase").val("Update Card");
					$("#authorize h1").text("Update Card");
					$(".secure").val("");
				}
				$(".secure").val("").removeClass("disabled").removeAttr("disabled");
				$(".make-purchase").removeClass("disabled").removeAttr("disabled");
            }
        });
    }
}

function stripeCheckout(status, response) {
    if (response.error) {
		$("#checkout .card-declined").show().css("top","44px");
		setTimeout(function(){
			$("#checkout .card-declined").css("top","0");
		},2400); stopSpin();
		$(".secure").removeClass("disabled").removeAttr("disabled");
		$(".make-purchase").removeClass("disabled").removeAttr("disabled");
    } else {
		token = response['id'];
		number = $("#checkout .cc-input").val();
    	last4 = number.substr(number.length-4);
    	$("#checkout .stripe-token").val(token);
    	$("#checkout .stripe-last4").val(last4);
        $.ajax({
            type: "GET",
            url: "/wp-admin/admin-ajax.php?action=chargeuser",  
            data: $("#checkout .checkout-form").serialize(),
            dataType: "json",
	   		error: function(response){
				$("#checkout .card-declined").show().css("top","44px");
				setTimeout(function(){
					$("#checkout .card-declined").css("top","0");
				},2400); stopSpin();
				$(".secure").removeClass("disabled").removeAttr("disabled");
				$(".make-purchase").removeClass("disabled").removeAttr("disabled");
			},
            success: function(response){
	        	if (response == -1) {
					$("#checkout .bar-closed").show().css("top","44px");
					setTimeout(function(){
						$("#checkout .bar-closed").css("top","0");
					},2400); stopSpin();
					$(".secure").val("").removeClass("disabled").removeAttr("disabled");
					$(".make-purchase").removeClass("disabled").removeAttr("disabled");
					return false;
	        	}
	        	if (response == -2) {
					verifyMobile(); stopSpin();
					return false;
	        	}
		    	pickupArea = $("#pickup").text();
		    	$("#location").html(pickupArea);
		    	jQT.goTo($("#success"),"slideleft");
		    	setTimeout("stopSpin()",300);
				pickupArea = $("#pickup").text();
				$("#location").html(pickupArea);
            	$.post("/wp-admin/admin-ajax.php?action=afterpurchase&id="+barID);
				$(".secure").val("").removeClass("disabled").removeAttr("disabled");
				$(".make-purchase").removeClass("disabled").removeAttr("disabled");
				hasCard = 1;
            }
        });
    };
}

function callCheckout(){
	$("#checkout-div,#authorize-div").load("/wp-admin/admin-ajax.php?action=checkout", function(){
		//$(".checkout-form").submit(function(){
		$(".make-purchase").bind(evname, function(){
			hideKeyboard(); startSpin();
			$(".secure").addClass("disabled").prop('disabled', true);
			$(".make-purchase").addClass("disabled").attr('disabled', true);
			if (realCheckout == 1) { // Checkout page
				if (hasCard == 1) {
			        $.ajax({
			            type: "GET",
			            url: "/wp-admin/admin-ajax.php?action=chargeuser",  
			            data: $("#checkout .checkout-form").serialize(),
			            dataType: "json",
			       		error: function(response){
							console.log('realcheckout and hascard both 1');
							console.log(response);
							$("#checkout .card-declined").show().css("top","44px");
							setTimeout(function(){
								$("#checkout .card-declined").css("top","0");
							},2400); stopSpin();
							$(".secure").removeClass("disabled").removeAttr("disabled");
							$(".make-purchase").removeClass("disabled").removeAttr("disabled");
						},
			            success: function(response){
			            	if (response == -1) {
								$("#checkout .bar-closed").show().css("top","44px");
								setTimeout(function(){
									$("#checkout .bar-closed").css("top","0");
								},2400); stopSpin();
								$(".secure").val("").removeClass("disabled").removeAttr("disabled");
								$(".make-purchase").removeClass("disabled").removeAttr("disabled");
								return false;
			            	}
			            	if (response == -2) {
								verifyMobile(); stopSpin();
								return false;
			            	}
			            	if (response > 0) {
				            	pickupArea = $("#pickup").text();
				            	$("#location").html(pickupArea);
				            	$("#checkout .secure").val("");
				            	jQT.goTo($("#success"),"slideleft");
				            	setTimeout("stopSpin()",300);
								$(".secure").val("").removeClass("disabled").removeAttr("disabled");
								$(".make-purchase").removeClass("disabled").removeAttr("disabled");
								$.post("/wp-admin/admin-ajax.php?action=afterpurchase&id="+barID);
			            	} else {
								console.log(response);
								console.log('response negative but not handled');
								$("#checkout .card-declined").show().css("top","44px");
								setTimeout(function(){
									$("#checkout .card-declined").css("top","0");
								},2400); stopSpin();
								$(".secure").val("").removeClass("disabled").removeAttr("disabled");
								$(".make-purchase").removeClass("disabled").removeAttr("disabled");
			            	};
			            }
			        });
				} else {
					console.log('stripeCheckout');
					Stripe.card.createToken({
				        number: $("#checkout .cc-input").val(),
				        exp_month: $("#checkout .month-input").val(),
				        exp_year: $("#checkout .year-input").val(),
				 		//cvc: $("#checkout .cvv-input").val()
				    }, stripeCheckout);
				}
			} else {
				console.log('stripeAuthorize');
				Stripe.card.createToken({
			        number: $("#authorize .cc-input").val(),
			        exp_month: $("#authorize .month-input").val(),
			        exp_year: $("#authorize .year-input").val(),
			 		//cvc: $("#authorize .cvv-input").val()
			    }, stripeAuthorize);
			}; return false;
		}); 
	});
};

function firstAuthorize() {
	$(".brands,.stripe-wrap,.later,.auth-text").show();
	$("#authorize .saved-card, #authorize .back").hide();
	$("#authorize .make-purchase").val("Save Card");
	$("#authorize h1").text("Save Card");
	realCheckout = 0;
	setTimeout(function(){
		jQT.goTo($("#authorize"),"flipleft");
		$("#authorize .delete").css("top","-44px");
		setTimeout(function(){
			//$(".text-remind").show().css("top","44px");
		},500);
		setTimeout(function(){
			//$(".text-remind").css("top","0");
		},2900);
	},700);
}

function hideKeyboard() {
	document.activeElement.blur();
	$("input").blur();
};

// EVENT LISTENERS

$(function() {
	
	// iSCROLL

	$(".about,.works").tap(function(){
		setTimeout(function(){myAbout.refresh()},0); 
	});

	$(".security").tap(function(){
		setTimeout(function(){mySecurity.refresh()},0); 
	});
	
	$(".works").tap(function(){
		setTimeout(function(){myAbout.refresh()},0); 
	});

	$(".locations").tap(function(){
		setTimeout(function(){myLocations.refresh()},0);
	});

	$(".rewards").tap(function(){
		setTimeout(function(){myRewards.refresh()},0);
	});	

	$(".affiliate").tap(function(){
		setTimeout(function(){myAffiliate.refresh()},0);
		if (code != '') {
			$("#aff-true").show();$("#aff-false").hide();
		} else {
			$("#aff-true").hide();$("#aff-false").show();
		}
	});

	// TOPBAR BUTTONS

	$(".back-home").bind(evname, function(){
		jQT.goTo($("#home"),"slideright");
	});

	$(".back-hist").bind(evname, function(){
		jQT.goTo($("#"+myHist),"slideright");
		if (myHist == 'custom') {
			resetCustom();
		};
	});

	$(".logout").bind(evname, function(){
		setTimeout("startSpin()",100);
		$("#biggy2,#smally2").hide();
		jQT.goTo($("#home"),"slideright");		
		$.post("/wp-admin/admin-ajax.php?action=logout", function(){
			setTimeout("checkLogin();",300);
		});
	});

	$(".clear,.finish").bind(evname, function(){
		emptyCart(); callCheckout();
		$.get("/wp-admin/admin-ajax.php?action=checkhappy&barid="+barID, function(data){
			if (data == 1) {
				barHappy = 1; $(".p-price").hide(); $(".p-sale").show();	
			} else {
				barHappy = 0; $(".p-price").show(); $(".p-sale").hide();
			};
		});
	});

	$(".auth-clear").bind(evname, function(){
		setTimeout("$('.secure').val('');",700);
	});
	
	$(".cart").bind(evname, function(){
		customWait = 0;
	});

	$(".categories").bind(evname, function(){
		myHist = 'categories';
	});

	$(".refresh").bind(evname, function(){
		startSpin(); pullDownAction();
	});

	$(".delete").bind(evname, function(){
		startSpin();
		$.post("/wp-admin/admin-ajax.php?action=deletecard", function(data) {
			stopSpin();
			$("#authorize .card-delete").css("top","0");
			$("#authorize .brands, #authorize .stripe-wrap, #authorize .back").show();
			$("#authorize .saved-card, #authorize .auth-text").hide();
			$("#authorize .make-purchase").val("Save Card");
			$("#authorize h1").text("Save Card");
			$("#authorize .card-delete").show().css("top","44px");
			$("#authorize .delete").css("top","-44px");
			setTimeout(function(){
				$("#authorize .card-delete").css("top","0");
			},2400); hasCard = 0;
		});
	});

	// TOUCH EVENTS
	
	$("#home,#login,#register,#settings,.toolbar,#cart,#checkout,#support,#cards,#account,#social,#terms,#offer,#spinner-wrap").bind("touchmove",function(e){
    	e.preventDefault();
	});
	
	$("#big-reward").bind(evname, function(){
		$("#big-reward").addClass("reward-rotate");	
		setTimeout(function(){
			$("#big-reward").removeClass("reward-rotate");
		},700);
	});

	$(".locations").bind(evname, function(){
		hideCount();
	});

	$(".later").bind(evname, function(){
		firstAuthorize = 0; hasCard = 0;
	});

	$(".promo").bind(evname, function(){
		callPromo();
	});

	$("#pickup-btn").bind(evname, function(){
		$("#table-btn").removeClass("table-clicked");
		$("#pickup-btn").addClass("pickup-clicked");
		$("#table-text,#table-num").css("opacity","0").prop('disabled', true);
		tablesActive = 0;
	});

	$("#table-btn").bind(evname, function(){
		$("#table-btn").addClass("table-clicked");
		$("#pickup-btn").removeClass("pickup-clicked");
		$("#table-text,#table-num").css("opacity","1").removeAttr("disabled");
		tablesActive = 1; $("#table-num").focus(); return false;
	});

	$("#stuck-loading").bind(evname, function(){
		jQT.goBack();stopSpin();
	});

	$(".cards").bind(evname, function(){
		setTimeout("startSpin()",200);
		$("#cards-div").load("/wp-admin/admin-ajax.php?action=showcards&userid="+userid, function(){
			setTimeout("stopSpin()",300);
			$(".cim-delete").tap( function(){
				startSpin();
				var cardid = $(this).attr("cardid");
				$.post("/wp-admin/admin-ajax.php?action=showcards&userid="+userid+"&a=delete&id="+cardid, function() {
					$("#cards-div").load("/wp-admin/admin-ajax.php?action=showcards&userid="+userid, function(){
						setTimeout("stopSpin()",700);
					});
				});
			});
		});
	});

	$(".login").tap(function(){
		setTimeout("$('.text-input').val('');",700);
	});

	$(".about").tap(function(){
		$("#about .back").hide();
		$("#about .cancel").show();
	});

	$(".works").tap(function(){
		$("#about .back").show();
		$("#about .cancel").hide();
	});
	
	$("#submit-suggestion").tap(function(){
		hideKeyboard(); startSpin();
		suggestionReport = $("#suggestion-textarea").val();
		$.post("/wp-admin/admin-ajax.php?action=bugsuggestion&message="+suggestionReport);
		setTimeout(function(){
			stopSpin();
			$("#suggestion .msg-sent").show().css("top","44px");
			setTimeout(function(){
				$("#suggestion .msg-sent").css("top","0");
				$(".secure").val('');
			},2400);
		},1200);
	});
	
	$("#submit-bug").tap(function(){
		hideKeyboard(); startSpin();
		bugReport = $("#bug-textarea").val();
		$.post("/wp-admin/admin-ajax.php?action=bugsuggestion&message="+bugReport);
		setTimeout(function(){
			stopSpin();
			$("#bug .msg-sent").show().css("top","44px");
			setTimeout(function(){
				$("#bug .msg-sent").css("top","0");
				$(".secure").val('');
			},2400);
		},1200);
	});

	$("#submit-affilite").tap(function(){
		if (clickedAffiliate == 0) {
			startSpin(); $("#submit-affilite").addClass("disabled");
			$.post("/wp-admin/admin-ajax.php?action=newaffiliate");
			setTimeout(function(){
				stopSpin();
				$("#affiliate .msg-sent").show().css("top","44px");
				setTimeout(function(){
					$("#affiliate .msg-sent").css("top","0");
				},2400);
			},1200); clickedAffiliate = 1;
		}
	});	

	$(".authorize").bind(evname, function(){
		if (hasCard == 1) {
			$("#authorize .brands, #authorize .auth-text").hide();
			$("#authorize .saved-card, #authorize .stripe-wrap, #authorize .back").show();
			$("#authorize .make-purchase").val("Update Card");
			$("#authorize h1").text("Update Card");
			$("#authorize .delete").css("top","5px");
		} else {
			$("#authorize .brands, #authorize .stripe-wrap, #authorize .back").show();
			$("#authorize .saved-card, #authorize .auth-text, .delete").hide();
			$("#authorize .make-purchase").val("Save Card");
			$("#authorize h1").text("Save Card");
			$("#authorize .delete").css("top","-44px");
		}
		$("#authorize .stripe-id").val(userid);
		$("#checkout .stripe-barid").val(barID);
		$("#authorize .stripe-name").val(fname+" "+lname);
		$("#authorize .stripe-email").val(email);
		$("#authorize .stripe-token").val(token);
		$("#authorize .stripe-last4").val(last4);
		$("#authorize .stripe-last4").text("•••• •••• •••• "+last4);
		realCheckout = 0;
	});

	$(".checkout").bind(evname, function(){
		cartPromo = $("#coupon_num").val();
		tableNumber = $("#table-num").val();
		if ((tablesActive == 1) && !(tableNumber > 0)) {
			$("#cart .table-invalid").show().css("top","44px");
			setTimeout(function(){
				$("#cart .table-invalid").css("top","0");
			},2400);
			return false;
		};
		if (hasCard == 1) {
			$("#checkout .stripe-wrap, #checkout .brands, #checkout .auth-text").hide();
			$("#checkout .saved-card").show();
		} else {
			$("#checkout .brands, #checkout .stripe-wrap").show();
			$("#checkout .saved-card, #checkout .auth-text").hide();
		}
		$("#checkout .make-purchase").val("Place Order");
		$("#checkout .stripe-id").val(userid);
		$("#checkout .stripe-barid").val(barID);
		$("#checkout .stripe-name").val(fname+" "+lname);
		$("#checkout .stripe-email").val(email);
    	$("#checkout .stripe-token").val(token);
    	$("#checkout .stripe-last4").val(last4);
		$("#checkout .stripe-last4").text("•••• •••• •••• "+last4);
		$("#checkout .wpec-subtotal").val(cartSubt);
		$("#checkout .wpec-shipping").val(cartGrat);
		$("#checkout .wpec-tax").val(cartTax);
		$("#checkout .wpec-promo").val(cartPromo);
		$("#checkout .wpec-discount").val(cartDisc);
		$("#checkout .wpec-total").val(cartTotal);
		$("#checkout .wpec-seating").val(tableNumber);			
		jQT.goTo($("#checkout"),"slideleft");
		realCheckout = 1;
	});

	// CATEGORIES

	$(".happy").tap(function(){
		setTimeout(function(){
			setTimeout(function (){myHappy.refresh()},0);
		},50);
		myHist = 'happy'; tempCat = 'h';
	});

	$(".beer").tap(function(){
		setTimeout(function(){
			setTimeout(function (){myBeer.refresh()},0);
		},50);
		myHist = 'beer'; tempCat = 'b';
	});
	
	$(".wine").tap(function(){
		setTimeout(function(){
			setTimeout(function (){myWine.refresh()},0);
		},50);
		myHist = 'wine'; tempCat = 'w';
	});

	$(".cocktails").tap(function(){
		setTimeout(function(){
			setTimeout(function (){myCocktails.refresh()},0);
		},50);
		setTimeout(function(){
			$(".p-mixer").remove();
			$(".mixer-item").css("background","#F9F9F9");
		},100);
		myHist = 'cocktails'; tempCat = 'c'; customWait = 0;
	});

		$(".featured").tap(function(){
			setTimeout(function(){
				setTimeout(function (){myFeatured.refresh()},0);
			},50);
			setTimeout(function(){
				$(".p-mixer").remove();
				$(".mixer-item").css("background","#F9F9F9");
			},100);
			myHist = 'featured'; tempCat = 'cf';
		});

		$(".well").tap(function(){
			setTimeout(function(){
				setTimeout(function (){myWell.refresh()},0);
			},50);
			setTimeout(function(){
				$(".p-mixer").remove();
				$(".mixer-item").css("background","#F9F9F9");
			},100);
			myHist = 'well'; tempCat = 'cw';
		});

		$(".premium").tap(function(){
			setTimeout(function(){
				setTimeout(function (){myPremium.refresh()},0);
			},50);
			setTimeout(function(){
				$(".p-mixer").remove();
				$(".mixer-item").css("background","#F9F9F9");
			},100);
			myHist = 'premium'; tempCat = 'cp';
		});

		$(".custom").tap(function(){
			setTimeout(function(){
				setTimeout(function (){myCustom.refresh()},0);
			},50);
			setTimeout(function(){
				$(".p-mixer").remove();
				$(".mixer-item").css("background","#F9F9F9");
			},100); 
			myHist = 'custom'; tempCat = 'cc';
			resetCustom();
		});

	$(".shooters").tap(function(){
		setTimeout(function(){
			setTimeout(function (){myShooters.refresh()},0);
		},50);
		myHist = 'shooters'; tempCat = 's';
	});

	$(".soft-drinks").tap(function(){
		setTimeout(function(){
			setTimeout(function (){mySoftDrinks.refresh()},0);
		},50);
		myHist = 'soft-drinks'; tempCat = 'sd';
	});
	
	// TRANSITIONS
	
	$(".touchup").bind(evname, function(){
		href = $(this).attr("href");
		jQT.goTo($(href),"slideup");
	});

	$(".touchdown").bind(evname, function(){
		href = $(this).attr("href");
		jQT.goTo($(href),"slidedown");
	});

	$(".tapleft").tap(function(){
		href = $(this).attr("href");
		jQT.goTo($(href),"slideleft");
	});

	$(".touchleft").bind(evname, function(){
		href = $(this).attr("href");
		jQT.goTo($(href),"slideleft");
	});

	$(".touchright").bind(evname, function(){
		href = $(this).attr("href");
		jQT.goTo($(href),"slideright");
	});

	$(".flipleft").tap(function(){
		href = $(this).attr("href");
		jQT.goTo($(href),"flipleft");
	});
	
	// FORMS
	
	$("#login-btn").tap( function(){
		if (loginDisabled==1) { return false };
		hideKeyboard(); startSpin();
		username = $("#user-name").val().replace(/[^0-9]/g,'');
		$.post("/wp-admin/admin-ajax.php?action=loginajax", { uname:username, upass:$("#password").val() }, function(data) {
			stopSpin();
			if (data==1) {
				$(".login-valid").show().css("top","44px");
				setTimeout(function(){
					checkLogin(); startSpin();
				},1000);
				setTimeout(function(){
					$(".login-valid").css("top","0");
				},2400);
			} else {
				$("#password").val('');
				$(".login-invalid").show().css("top","44px");
				setTimeout(function(){
					$(".login-invalid").css("top","0");
				},2400);
			}
		});
	});

	$("#forgot-btn").tap( function(){
		if (loginDisabled==1) { return false };
		hideKeyboard(); startSpin();
		$.post("/wp-admin/admin-ajax.php?action=password", { uname:$("#lost-pswd").val() }, function(data) {
			stopSpin();
			if (data==1) {
				$(".forgot-valid").show().css("top","44px");
				setTimeout(function(){
					$(".forgot-valid").css("top","0");
				},2400);	
			} else {
				$(".forgot-invalid").show().css("top","44px");
				setTimeout(function(){
					$(".forgot-invalid").css("top","0");
				},2400);	
			}
		});
	});

	$("#register-btn").tap( function(){
		if (loginDisabled==1) { return false };
		hideKeyboard(); startSpin();
		username = $("#user_name").val().replace(/[^0-9]/g,'');
		$.post("/wp-admin/admin-ajax.php?action=registerajax", { first_name:$("#first_name").val(), last_name:$("#last_name").val(), user_name:username, pass:$("#pass").val(), email:$("#email").val() }, function(data) {
			stopSpin();
			if (data==1) {
				$(".register-valid").show().css("top","44px");
				setTimeout(function(){
					newRegister = 1; checkLogin(); startSpin();
				},1000);
				setTimeout(function(){
					$(".register-valid").css("top","0");
				},2400);
				//$.post("/wp-admin/admin-ajax.php?action=sendmobile");
			} else {
				if (data==0) {
					registerResponse = "Your credentials were invalid!";
				}
				if (data==-1) {
					registerResponse = "Please complete required fields!"; 
				}
				if (data==-2) {
					registerResponse = "Cell number already exists!";
				}
				if (data==-3) {
					registerResponse = "Email already exists!";
				}
				$("#register-msg").text(registerResponse);
				$(".register-invalid").show().css("top","44px");
				setTimeout(function(){
					$(".register-invalid").css("top","0");
				},2400);
			}
		});
	});
	
	$("#account-btn").tap( function(){
		if (loginDisabled==1) { return false };
		hideKeyboard(); startSpin();
		$.post("/wp-admin/admin-ajax.php?action=updateuser", { first_name:$("#first_name2").val(), last_name:$("#last_name2").val(), user_email:$("#email2").val(), user_login:$("#user_phone2").val() }, function(data) {
			if (data == -1) {
				stopSpin();
				$(".account-invalid").show().css("top","44px");
				setTimeout(function(){
					$(".account-invalid").css("top","0");
				},2400);			
			}
			if (data == -2) {
				stopSpin();
				$(".exists-invalid").show().css("top","44px");
				setTimeout(function(){
					$(".exists-invalid").css("top","0");
				},2400);			
			}
			if (data == 1) {
				stopSpin();
				$(".account-valid").show().css("top","44px");
				setTimeout("startSpin()",1200);
				setTimeout(function(){
					$(".account-valid").css("top","0");
					// acctUpdated = 1;
					$("#biggy2,#smally2").hide();
					jQT.goTo($("#home"),"slideright");		
					$.post("/wp-admin/admin-ajax.php?action=logout", function(){
						setTimeout("checkLogin();",300);
					});
				},2400);
			}
		});
	});

	$("#gratuity-minus").bind(evname, function(){
		newPercent = (1*cartPercent - 5).toFixed()
		newGrat = (cartSubt*(newPercent/100)).toFixed(2);
		newTotal = (1*cartSubt + 1*cartTax - 1*cartDisc + 1*newGrat).toFixed(2);
		if (1*newTotal < 1*newGrat) {
			minApplied = 1;
			minTotal = newGrat;
		} else {
			minApplied = 0;
		}
		if ( newPercent >= 10 ) {
			$("#gratuity-percent").text(newPercent+"%");
			$("#gratuity-amount").text("$"+newGrat);
			$("#total-amount").text("$"+newTotal);
			cartPercent = newPercent;
			cartGrat = newGrat;
			cartTotal = newTotal;
			if (minApplied == 1) {
				$(".total-amount").text("$"+minTotal);
				minTotalCents = Math.round(minTotal*100);
				$("#checkout .stripe-amount").val(minTotalCents);
			} else {
				$(".total-amount").text("$"+newTotal);
				newTotalCents = Math.round(newTotal*100);
				$("#checkout .stripe-amount").val(newTotalCents);
			}
		};
	});

	$("#gratuity-plus").bind(evname, function(){
		newPercent = (1*cartPercent + 5).toFixed()
		newGrat = (cartSubt*(newPercent/100)).toFixed(2);
		newTotal = (1*cartSubt + 1*cartTax - 1*cartDisc + 1*newGrat).toFixed(2);
		if (1*newTotal < 1*newGrat) {
			minApplied = 1;
			minTotal = newGrat;
		} else {
			minApplied = 0;
		}
		if ( newPercent <= 200 ) {
			$("#gratuity-percent").text(newPercent+"%");
			$("#gratuity-amount").text("$"+newGrat);
			$("#total-amount").text("$"+newTotal);
			cartPercent = newPercent;
			cartGrat = newGrat;
			cartTotal = newTotal;			
			if (minApplied == 1) {
				$(".total-amount").text("$"+minTotal);
				minTotalCents = Math.round(minTotal*100);
				$("#checkout .stripe-amount").val(minTotalCents);
			} else {
				$(".total-amount").text("$"+newTotal);
				newTotalCents = Math.round(newTotal*100);
				$("#checkout .stripe-amount").val(newTotalCents);
			}
		}; return false;
	});

	$("#promo-update").bind(evname, function(){
		hideKeyboard(); startSpin();
		couponfield = $("#coupon_num").val();		
		$.post("/wp-admin/admin-ajax.php?action=checkcoupon&coupon="+couponfield, function(data) {
			if (data==1) {
				goodPromo();
			    $.ajax({
			        type: "POST",
			        data: $("#coupon-form").serialize(),
			       	url: "/wp-admin/admin-ajax.php?action=addcart",
			        success: function(){
						$("#cart-div").load("/wp-admin/admin-ajax.php?action=addcart", function(){
							callCart();stopSpin();
						});
			        }
			    }); 
			} else {
				badPromo();
				setTimeout("stopSpin()",700);
			}
		}); return false;
	});
	startScroll();
	checkLogin();
	refreshMenu();
});

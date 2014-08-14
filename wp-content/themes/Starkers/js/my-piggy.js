// FUNCTIONS

function stopSpin(){
	$(".spinner,#spinner-box,#spinner-wrap").fadeOut();
}

function startSpin(){
	$(".spinner,#spinner-box,#spinner-wrap").css("display","inline-block");
}

function showInfo(num){

	killFlash = 1;
	$("#order-info,.void-alert-wrap,.close-alert-wrap,#new-wrap").hide();
	$("#crown,#sofa,#table-num").hide();

	$(".order-line").css("font-weight","normal");
	$(".order-line").css("background","#272727");
	$(".table-order").css("background","#AAA");
	$(".table-num").css("text-shadow","2px 2px #777");
	$(".line"+num).css("font-weight","bold");
	$(".line"+num).css("color","white");
	$(".line"+num).css("background","-webkit-gradient( linear,left top,left bottom,color-stop(0.05,#79bbff),color-stop(1,#4197ee))");
	$(".line"+num+" .table-order").css("background","#777");
	$(".line"+num+" .table-num").css("text-shadow","2px 2px #555");
	
	ordernum = $("#order-num"+num).text();
	orderiid = $("#order-iid"+num).text();
	orderiid_short = orderiid.substr(orderiid.length - 3);
	orderlogin = $("#order-login"+num).text();
	orderfname = $("#order-fname"+num).text();
	orderlname = $("#order-lname"+num).text();
	ordertime = $("#order-time"+num).text();
	orderdiscount = $("#order-discount"+num).text();
	orderstatus = $("#order-status"+num).text();
	orderseating = $("#order-seating"+num).text();
	ordergratuity = $("#order-gratuity"+num).text();
	ordertotal = $("#order-total"+num).text();
	
	receiptmiddle = $("#receipt-middle"+num).html();
	receiptbottom = $("#receipt-bottom"+num).html();
	
	$("#info-num").html("#"+orderiid_short);
	$("#fname").html(orderfname);
	$("#lname").html(orderlname);
	$("#info-time").html(ordertime);
	$("#receipt-middle").html(receiptmiddle);
	$("#receipt-bottom").html(receiptbottom);
	if (orderseating>0){
		$("#sofa,#table-num").css("display","inline-block");
		$("#table-num").html(orderseating);
	}
	if (orderdiscount){
		$("#crown").css("display","inline-block");
	}
	if (orderstatus==="waiting"){
		$("#ready-btn").show();$("#remind-btn").hide();
		if (printorders===1) {
			$("#print-arrow").removeClass("hidden");
		} else {
			$("#print-arrow").addClass("dead");
		}
	} else {
		$("#ready-btn").hide();$("#remind-btn").show();
		$("#print-arrow").addClass("hidden");
	}
	setTimeout("$('#order-info').fadeIn(50);",100);

}

function checkNew() {
	if ($(".waiting").length > 0){
		$("#no-orders").hide();
		$("#logout-btn").addClass("hidden");
		approvelogout = 0;
	} else {
		$("#no-orders").show();
		$("#logout-btn").removeClass("hidden");
		approvelogout = 1;
	}
}

function checkOrders() {
	if ($(".ready").length > 0){
		$("#no-orders").hide();
	} else {
		$("#no-orders").show();
	}
}

function isNumberKey(evt) {
	var charCode = (evt.which) ? evt.which : event.keyCode;
	if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
		return false;
	} else {
		return true;
	}      
}

function flashBox(){
	$("#right-box").css("background","#272727");
	if (killFlash==0){
		$("#order-info").hide();
		$("#new-wrap").fadeIn();
		setTimeout("$('#right-box').css('background','-webkit-gradient(linear,left top,left bottom,color-stop(0.05,#79bbff),color-stop(1,#4197ee))')",800);
		setTimeout("flashBox()",900);
	}
}

function startClock(time,id) {
	var myDate = new Date();
	myDate.setSeconds(myDate.getSeconds() - time);
	var prettyDate = ('0' + myDate.getDate()).slice(-2) + '/' + ('0' + (myDate.getMonth()+1)).slice(-2) + '/' + myDate.getFullYear() + '-' + ('0' + myDate.getHours()).slice(-2) + ':' + ('0' + myDate.getMinutes()).slice(-2) + ':' + ('0' + myDate.getSeconds()).slice(-2);
	$("#time"+id).countUp({'lang':'en', 'format':'full', 'sinceDate': ''+prettyDate+''});
	setTimeout("$('.order-time').css('opacity','1');",1500);
}

function cleanUp() {
	setTimeout("$('.void-alert-wrap,.close-alert-wrap').fadeOut()",300);
	setTimeout("$('.info-name,.info-id,.receipt-top,.receipt-middle,.receipt-bottom,.btn-wrap').css('opacity','1')",300);
	showall = 0; imlooking = 0;
}

function disablePrint() {
	$(".disabled").bind('click', function(){
		return false;
	});
}

function hiddenBtns(){
	$(".alert-inner,.hidden").bind(evname, function(ev){
		ev.stopPropagation();
	});
}

function shiftBorder() {
	$("#activeshift-div .shifts-box").bind(evname, function(ev){
		$(".shifts-box").removeClass("active");
		$(this).addClass("active");
		canprint = 0; canendshift = 1;
		$("#end-btn").removeClass("hidden");
		$("#shprint-btn").addClass("hidden");
	});
	$("#piggyshifts-div .shifts-box").bind(evname, function(ev){
		$(".shifts-box").removeClass("active");
		$(this).addClass("active");
		canprint = 1; canendshift = 0;
		$("#end-btn").addClass("hidden");
		$("#shprint-btn").removeClass("hidden");
	});
}

function checkTables(){
	if (tableservice==1){
		$("#tables-btn,#servers-btn").removeClass("hidden");
		$("#notables-btn").addClass("hidden");	
	} else {
		$("#tables-btn,#servers-btn").addClass("hidden");
		$("#notables-btn").removeClass("hidden");	
	}
}

// VARIABLES

var showall = 0;
var imlooking = 0;
var autoTimer = 0;
var killFlash = 1;
var voidItem = 0;
var stockItem = 0;
var batchItem = 0;
var oktoClick = 1;
var longestWait = 0;
//var socket = io.connect("http://flowtab.jit.su");
var socket = io.connect("http://srvd-node.herokuapp.com");
var evname = window.Touch ? 'touchstart' : 'mousedown';
var hiddenbunch = "#queue,#totals-wrap,#shifts-wrap,#stats-box,#cats-box,#order-info,#queue-w,#tax-rates,#new-wrap,#all-wrap,#tables-box,#summary-box,#shifts-wrapper,.shifts-box,#pmx-box,#servers-btn,#orders-btn,.fax-btn,.fax-faux-btn,#cats-btn,#end-btn,#batch-btn,#shifts-btn,#totals-btn,#email-btn,#no-orders,.alert-wrap";

startSpin();

// EVENT LISTENERS

$(function() {

	if (isLogin === 0) {	
		socket.on('connect', function () {
			socket.emit('subscribe',barid);
			console.log('just sent subscribe method');
			$.post("/wp-admin/admin-ajax.php?action=nodeorders&barid="+barid, function() {
				if (showall == 1) {checkOrders()} else {checkNew()};
				$("#jqt,#tb,#content").css("opacity","1"); checkTables();
				setTimeout("stopSpin();",500);
			});
		});
		socket.on('message', function (data) {
			$("#getorders-div").html(data.value);
			if (showall==1) {
				$(".waiting, .waiting .ready-tag").hide();
				$(".ready, .ready .ready-tag").css("display","inline-block");
			}; $(".order-time").css("opacity","0");
			setTimeout("$('#node-check').fadeIn()",500);
			setTimeout("$('#node-check').fadeOut()",2200);		
			if ( ($(".waiting").length > 0) && (imlooking === 0) ) {
				killFlash = 0;flashBox();checkNew();
			};
		});
	} else {
		$("#jqt,#tb,#content").css("opacity","1");
		setTimeout("stopSpin();",500);
	}
	
	$("#bar-name").text(barname+" (#"+barid+")");
	
	$("#login-form1").submit(function(){
		startSpin();
		event.preventDefault();
		$.post("/wp-admin/admin-ajax.php?action=loginajax",
			{uname:$("#user-name").val(),upass:$("#password").val()}, function(data) {
			stopSpin();
			if (data==1) {
				$('#login-success, #fail-success').css('height','30px').css('border','1px solid #BBB').css('margin-top','14px');
				setTimeout("$('#login-success, #fail-success').css('height','0').css('border','0').css('margin-top','0');",2000);
				setTimeout("$('#jqt,#tb,#content').css('opacity','0')",2500);
				setTimeout("self.location='/piggy';",2500);
			} else {
				$('#login-fail, #reg-fail1, #reg-fail2, #reg-fail3').css('height','30px').css('border','1px solid #BBB').css('margin-top','14px');
				setTimeout("$('#login-fail, #reg-fail1, #reg-fail2, #reg-fail3').css('height','0').css('border','0').css('margin-top','0');",3000);
			}
		});
	});

	$("#login-form2").submit(function(){
		startSpin();
		event.preventDefault();
		$.post("/wp-admin/admin-ajax.php?action=loginajax",
			{uname:$("#user-name").val(),upass:$("#password").val()}, function(data) {
			stopSpin();
			if (data==1) {
				$('#login-success, #fail-success').css('height','30px').css('border','1px solid #BBB').css('margin-top','14px');
				setTimeout("$('#login-success, #fail-success').css('height','0').css('border','0').css('margin-top','0');",2000);
				setTimeout("$('#jqt,#tb,#content').css('opacity','0')",2500);
				setTimeout("self.location='/wp-admin/';",2500);
			} else {
				$('#login-fail, #reg-fail1, #reg-fail2, #reg-fail3').css('height','30px').css('border','1px solid #BBB').css('margin-top','14px');
				setTimeout("$('#login-fail, #reg-fail1, #reg-fail2, #reg-fail3').css('height','0').css('border','0').css('margin-top','0');",3000);
			}
		});
	});

	$("#open-btn").bind(evname, function(){
		alert("Your store is now closed. You are no longer accepting Flowtab orders!");
		$("#open-btn").addClass("hidden");
		$("#closed-btn").removeClass("hidden");
		$.post("/wp-admin/admin-ajax.php?action=open&status=0");
	});

	$("#closed-btn").bind(evname, function(){
		alert("Your store is now open. You are accepting Flowtab orders!");
		$("#open-btn").removeClass("hidden");
		$("#closed-btn").addClass("hidden");
		$.post("/wp-admin/admin-ajax.php?action=open&status=1");
	});

	$("#happy-btn").bind(evname, function(){
		alert("You have successfully de-activated Happy Hour pricing!");
		$("#happy-btn").addClass("hidden");
		$("#unhappy-btn").removeClass("hidden");
		$.post("/wp-admin/admin-ajax.php?action=happy&status=0");
	});

	$("#unhappy-btn").bind(evname, function(){
		alert("You have successfully activated Happy Hour pricing!");
		$("#happy-btn").removeClass("hidden");
		$("#unhappy-btn").addClass("hidden");
		$.post("/wp-admin/admin-ajax.php?action=happy&status=1");
	});

	$("#tables-btn").bind(evname, function(){
		alert("You have successfully de-activated table ordering!");
		$("#tables-btn,#servers-btn").addClass("hidden");
		$("#notables-btn").removeClass("hidden");
		tableservice=0;
		$.post("/wp-admin/admin-ajax.php?action=tables&status=0");
	});

	$("#notables-btn").bind(evname, function(){
		alert("You have successfully activated table ordering!");
		$("#tables-btn,#servers-btn").removeClass("hidden");
		$("#notables-btn").addClass("hidden");
		tableservice=1;
		$.post("/wp-admin/admin-ajax.php?action=tables&status=1");
	});

	$("#print-btn").bind(evname, function(){
		alert("You have successfully de-activated automatic order printing!");
		$("#print-btn").addClass("hidden");
		$("#noprint-btn").removeClass("hidden");
		$.post("/wp-admin/admin-ajax.php?action=printing&status=0");
		$("#print-arrow").addClass("dead");
		printorders=0;
	});

	$("#noprint-btn").bind(evname, function(){
		alert("You have successfully activated automatic order printing!");
		$("#print-btn").removeClass("hidden");
		$("#noprint-btn").addClass("hidden");
		$.post("/wp-admin/admin-ajax.php?action=printing&status=1");
		$("#print-arrow").removeClass("hidden dead");
		printorders=1;
	});
	
	$("#logout-btn").bind(evname, function(){
		if (approvelogout == 1) {
			setTimeout("startSpin()",100);
			$.post("/wp-admin/admin-ajax.php?action=logout", function(){
				$('#jqt,#tb,#content').css('opacity','0');
				location.reload();
			});
		}
	});

	$("#email-btn").bind(evname, function(){
		startSpin();
		$.post("/wp-admin/admin-ajax.php?action=pmxreport&bar="+barid+"&admin=0");
		setTimeout("stopSpin()",500);
		setTimeout("alert('We have emailed you the Flowtab Sales Report.')",800);
	});

	$("#emergency").bind(evname, function(){
		startSpin();
		$.post("/wp-admin/admin-ajax.php?action=pmxreport&bar="+barid+"&admin=1");
		setTimeout("stopSpin()",500);
	});

	$("#show-new,#new-wrap").bind(evname, function(){
		$(hiddenbunch).hide();
		$("#queue,#queue-w").fadeIn();
		$(".ready, .ready .ready-tag").hide();
		$(".waiting, .waiting .waiting-tag").css("display","inline-block");
		$("#show-all").removeClass("show-all-clicked");
		$("#show-shifts").removeClass("show-shifts-clicked");
		$("#show-totals").removeClass("show-totals-clicked");
		$("#show-new").addClass("show-new-clicked");
		$(".order-line").css("background","#272727");
		$(".order-line").css("font-weight","normal");
		$(".table-order").css("background","#AAA");
		$(".table-num").css("text-shadow","2px 2px #777");
		setTimeout("$('.void-alert-wrap,.close-alert-wrap').fadeOut()",300);
		setTimeout("$('.info-name,.info-id,.receipt-top,.receipt-middle,.receipt-bottom,.btn-wrap').css('opacity','1')",300);
		checkNew();
		showall = 0; imlooking = 0;
	});

	$("#right-box").bind(evname, function(){
		if (killFlash==0) {
			killFlash = 1;
			$(hiddenbunch).hide();
			$("#queue,#queue-w").fadeIn();
			$(".ready, .ready .ready-tag").hide();
			$(".waiting, .waiting .waiting-tag").css("display","inline-block");
			$(".waiting").first().addClass("longest-wait");
			longestWait = $(".longest-wait").attr("ordernum");
			setTimeout("showInfo(longestWait)",100);
			$("#show-all").removeClass("show-all-clicked");
			$("#show-shifts").removeClass("show-shifts-clicked");
			$("#show-totals").removeClass("show-totals-clicked");
			$("#show-new").addClass("show-new-clicked");
			$(".order-line").css("background","#272727");
			$(".order-line").css("font-weight","normal");
			$(".table-order").css("background","#AAA");
			$(".table-num").css("text-shadow","2px 2px #777");
			setTimeout("$('.void-alert-wrap,.close-alert-wrap').fadeOut()",300);
			setTimeout("$('.info-name,.info-id,.receipt-top,.receipt-middle,.receipt-bottom,.btn-wrap').css('opacity','1')",300);
			checkNew(); // XXX	
			showall = 0; imlooking = 0;
		}	killFlash = 1;
	});

	$("#show-all").bind(evname, function(){
		$(hiddenbunch).hide();
		$("#queue,#queue-w").fadeIn();
		$(".waiting, .waiting .ready-tag").hide();
		$(".ready, .ready .ready-tag").css("display","inline-block");
		$("#show-new").removeClass("show-new-clicked");
		$("#show-shifts").removeClass("show-shifts-clicked");
		$("#show-totals").removeClass("show-totals-clicked");
		$("#show-all").addClass("show-all-clicked");
		$(".order-line").css("background","#272727");
		$(".order-line").css("font-weight","normal");
		$(".table-order").css("background","#AAA");
		$(".table-num").css("text-shadow","2px 2px #777");
		setTimeout("$('.void-alert-wrap,.close-alert-wrap').fadeOut()",300);
		setTimeout("$('.info-name,.info-id,.receipt-top,.receipt-middle,.receipt-bottom,.btn-wrap').css('opacity','1')",300);
		checkOrders();
		showall = 1; imlooking = 0;
	});

	$("#show-shifts,#shifts-btn").bind(evname, function(){
		setTimeout("startSpin()",200);
		$(hiddenbunch).hide();
		$("#shifts,#all-wrap").fadeIn();
		$("#show-new").removeClass("show-new-clicked");
		$("#show-all").removeClass("show-all-clicked");
		$("#show-totals").removeClass("show-totals-clicked");
		$("#show-shifts").addClass("show-shifts-clicked");
		$("#activeshift-div").load("/wp-admin/admin-ajax.php?action=activeshift&barid="+barid);
		$("#piggyshifts-div").load("/wp-admin/admin-ajax.php?action=getshifts&barid="+barid, function(){
			setTimeout( function() {
				$("#shifts-wrap,.shifts-box,#servers-btn,#orders-btn,#end-btn").show();
				shiftBorder();
				$(".shifts-box").removeClass("active");
				$("#activeshift-div .shifts-box").addClass("active");
				canprint = 0; canendshift = 1;
				$("#end-btn").removeClass("hidden");
				$("#shprint-btn").addClass("hidden");
				setTimeout("stopSpin()",700);	
			},1200);
		});
		cleanUp();
	});

	$("#servers-btn").bind(evname, function(){
		if (tableservice==1) {
			setTimeout("startSpin()",200);
			$(hiddenbunch).hide();
			$("#shifts,#all-wrap").fadeIn();
			$("#show-new").removeClass("show-new-clicked");
			$("#show-all").removeClass("show-all-clicked");
			$("#show-totals").removeClass("show-totals-clicked");
			$("#show-shifts").addClass("show-shifts-clicked");
			$("#servertables-div").load("/wp-admin/admin-ajax.php?action=piggytables", function(){
				setTimeout( function() {
					$("#shifts-wrap,#tables-box,#shifts-btn").show();
					setTimeout("stopSpin()",700);
				},1200);
			});
		}
	});
	
	$("#show-totals,#totals-btn").bind(evname, function(){
		setTimeout("startSpin()",200);
		$(hiddenbunch).hide();
		$("#totals,#all-wrap,#orders-btn").fadeIn();
		$("#show-new").removeClass("show-new-clicked");
		$("#show-all").removeClass("show-all-clicked");
		$("#show-shifts").removeClass("show-shifts-clicked");
		$("#show-totals").addClass("show-totals-clicked");
		$("#piggyweek-div").load("/wp-admin/admin-ajax.php?action=piggyweek");
		$("#piggyday-div").load("/wp-admin/admin-ajax.php?action=piggyday", function(){
			setTimeout( function() {
				$("#totals-wrap,#stats-box,#email-btn,#cats-btn,#orders-btn").show();
				setTimeout("stopSpin()",700);
			},1200);
		});
		cleanUp();
	});

	$("#cats-btn").bind(evname, function(){
		setTimeout("startSpin()",200);
		$(hiddenbunch).hide();
		$("#totals,#all-wrap").fadeIn();
		$("#show-new").removeClass("show-new-clicked");
		$("#show-all").removeClass("show-all-clicked");
		$("#show-shifts").removeClass("show-shifts-clicked");
		$("#show-totals").addClass("show-totals-clicked");
		$("#piggyweek-div").load("/wp-admin/admin-ajax.php?action=piggyweek");
		$("#piggycats-div").load("/wp-admin/admin-ajax.php?action=piggycats", function(){
			setTimeout( function() {
				$("#totals-wrap,#cats-box,#summary-box,#totals-btn").show();
				setTimeout("stopSpin()",700);
			},1200);
		});
		cleanUp();
	});

	$("#orders-btn").bind(evname, function(){
		$(hiddenbunch).hide();
		$("#totals,#all-wrap").fadeIn();
		setTimeout("startSpin()",200);
		$("#orderhistory-div").load("/wp-admin/admin-ajax.php?action=pmxpage&barid="+barid, function(){
			setTimeout( function() {
				$("#totals-wrap,#pmx-box,#totals-btn").show();
				setTimeout("stopSpin()",700);
			},1200);
		});
		cleanUp();
	});

	$("#ready-btn").bind(evname, function(){
		startSpin();
		$("#ready-btn").hide();
		$("#remind-btn").show();
		$("#order-info").hide();
		$("#order-status"+ordernum).html("ready");
		$(".line"+ordernum+" .waiting-tag").hide();$(".line"+ordernum+" .ready-tag").css("display","inline-block");
		$(".line"+ordernum).removeClass("waiting");$(".line"+ordernum+", .spacer"+ordernum).addClass("ready");
		$(".line"+ordernum+" .table-order").removeClass("waiting");$(".line"+ordernum+" .table-order").addClass("ready");
		$(".line"+ordernum+" .order-time").addClass("dead");
		if (showall===1){
			$(".ready,.ready .ready-tag").css("display","inline-block");
		} else {
			checkNew();
			$(".ready,.ready .ready-tag").hide();
		}
		if (orderseating>0){
			$.post("https://www.itduzzit.com/duzz/api/twilio-send-sms.json?token=onz2gr9i9khj0qx&Mobile+Number+to+Call="+orderlogin+"&Send+from+Mobile+Number=6466993569&Text=Hi+"+orderfname+"%2C+your+Flowtab+order+is+ready!+The+server+will+come+soon+with+your+drinks.+%23"+orderiid_short+"&?callback=?");
		} else {
			$.post("https://www.itduzzit.com/duzz/api/twilio-send-sms.json?token=onz2gr9i9khj0qx&Mobile+Number+to+Call="+orderlogin+"&Send+from+Mobile+Number=6466993569&Text=Hi+"+orderfname+"%2C+your+Flowtab+order+is+ready!+Pickup+drinks+from+"+pickup+".+Order+%23"+orderiid_short+"&?callback=?");
		}
		$.post("/wp-admin/admin-ajax.php?action=ordermade&id="+orderiid+"&status=1");
		$.post("/wp-admin/admin-ajax.php?action=orderprinted&id="+orderiid+"&status=1");
		$("#print-arrow").addClass("hidden");
		if (printorders===1){ window.print() };
		setTimeout("stopSpin()",500);
	});

	$("#remind-btn").bind(evname, function(){
		if (oktoClick==1) {
			startSpin();
			$(".line"+ordernum+" .order-time").addClass("dead");
			oktoClick = 0; $('#remind-btn').addClass('hidden'); clickTimer = setTimeout("oktoClick=1;$('#remind-btn').removeClass('hidden');",5000);
			$.post("https://www.it6148044000it.com/6148044000/api/twilio-send-sms.json?token=onz2gr9i9khj0qx&Mobile+Number+to+Call="+orderlogin+"&Send+from+Mobile+Number=6466993569&Text=Hi+"+orderfname+"%2C+come+grab+your+drinks!+%23"+orderiid_short+".&?callback=?");
			if (printorders===1){ window.print() };
			setTimeout("stopSpin()",500);
		}
	});

	$("#manager-verify").submit(function(){
		startSpin();
		$("#manager-verify-wrap").hide();
		event.preventDefault();
		$.get("/wp-admin/admin-ajax.php?action=checkpassword",
			{bar:$("#bar-verify").val(), pswd:$("#pswd-verify").val()}, function(data) {
			stopSpin();
			if (data==1) {
				if (voidItem==1) {
					$("#void-alert-wrap").show();
				}
				if (stockItem==1) {
					$("#stock-alert-wrap").show();
				}
				if (batchItem==1) {
					$("#batch-out-wrap").show();
				}
				$(".text-input").val('');
			} else {
				$(".text-input").val('');
				$("#manager-verify-wrap").show();
			}
		});
	});

	$("#close-btn").bind(evname, function(){
		$("#close-alert-wrap").show();
	});

	$("#batch-btn").bind(evname, function(){
		$("#manager-verify-wrap").show();
		voidItem = 0;
		stockItem = 0;
		batchItem = 1;
		endShift = 0;
	});

	$("#end-btn").bind(evname, function(){
		if (canendshift == 1) {
			$("#name-verify-wrap").show();
		}
	});
	
	$("#end-shift").submit(function(){
		event.preventDefault(); startSpin();
		blength = $("#name-verify").val().length;		
		if ( blength > 1 ) {
			bname = $("#name-verify").val();
			function endShift(){
				$(".alert-wrap").hide();
				$.post("/wp-admin/admin-ajax.php?action=endshift&barid="+barid+"&name="+bname, function() {
					$(hiddenbunch).hide();
					$("#shifts,#all-wrap").fadeIn();
					$("#show-new").removeClass("show-new-clicked");
					$("#show-all").removeClass("show-all-clicked");
					$("#show-totals").removeClass("show-totals-clicked");
					$("#show-shifts").addClass("show-shifts-clicked");
					$("#activeshift-div").load("/wp-admin/admin-ajax.php?action=activeshift&barid="+barid);
					$("#piggyshifts-div").load("/wp-admin/admin-ajax.php?action=getshifts&barid="+barid, function(){
						$.post("/wp-admin/admin-ajax.php?action=nodesuccess");
						setTimeout( function() {
							$("#shifts-wrap,.shifts-box,#servers-btn,#orders-btn,#end-btn,#print-alert-wrap").show();
							setTimeout("stopSpin()",300);
							$("#name-verify").val("");
							shiftBorder();
							$(".shifts-box").removeClass("active");
							$("#activeshift-div .shifts-box").addClass("active");
							canprint = 0; canendshift = 1;
							$("#end-btn").removeClass("hidden");
							$("#shprint-btn").addClass("hidden");
						},1200);
					});
					cleanUp();
				});
			};
			endShift();
		} else {
			stopSpin();
			alert("Please enter your name we can identify this shift.");
		}
	});		

	$("#print-shift-btn,#shprint-btn").bind(evname, function(){
		if (canprint ==1) {
			$("#print-alert-wrap").hide();
			window.print();
			setTimeout("stopSpin()",500);
		}
	});

	$("#real-close-btn").bind(evname, function(){
		startSpin();
		$("#close-alert-wrap,#order-info").hide();
		$(".line"+ordernum+",.spacer"+ordernum).addClass("dead");
		$.post("/wp-admin/admin-ajax.php?action=status&id="+orderiid+"&status=5");
		setTimeout("stopSpin()",500);
	});

	$("#void-btn").bind(evname, function(){
		//$("#manager-verify-wrap").show();
		$("#void-alert-wrap").show();
		voidItem = 1;
		stockItem = 0;
		batchItem = 0;
		endShift = 0;
	});

	$("#stock-btn").bind(evname, function(){
		//$("#manager-verify-wrap").show();
		$("#stock-alert-wrap").show();
		voidItem = 0;
		stockItem = 1;
		batchItem = 0;
		endShift = 0;
	});

	$("#real-void-btn").bind(evname, function(){
		startSpin();
		$("#void-alert-wrap,#order-info").hide();
		$(".line"+ordernum+",.spacer"+ordernum).addClass("dead").removeClass("ready").removeClass("waiting");
		$.post("https://www.it6148044000it.com/6148044000/api/twilio-send-sms.json?token=onz2gr9i9khj0qx&Mobile+Number+to+Call="+orderlogin+"&Send+from+Mobile+Number=6466993569&Text=Hey+"+orderfname+"%2C+your+Flowtab+drink+order+has+been+voided!+%23"+orderiid_short+".&?callback=?");
		$.post("/wp-admin/admin-ajax.php?action=status&id="+orderiid+"&status=7");
		//$.post("https://www.it6148044000it.com/6148044000/api/twilio-send-sms.json?token=onz2gr9i9khj0qx&Mobile+Number+to+Call=6148044000&Send+from+Mobile+Number=6466993569&Text=Hey+Kyle%2C+the+Flowtab+drink+order+%23"+orderiid+"+has+been+voided!&?callback=?");
		$.post("/wp-admin/admin-ajax.php?action=voidorder&orderid="+orderiid);
		if (showall == 1) {checkOrders()} else {checkNew()};
		setTimeout("stopSpin()",500);
	});

	$("#real-stock-btn").bind(evname, function(){
		startSpin();
		$("#stock-alert-wrap,#order-info").hide();
		$(".line"+ordernum+",.spacer"+ordernum).addClass("dead").removeClass("ready").removeClass("waiting");
		$.post("https://www.it6148044000it.com/6148044000/api/twilio-send-sms.json?token=onz2gr9i9khj0qx&Mobile+Number+to+Call="+orderlogin+"&Send+from+Mobile+Number=6466993569&Text=Hey+"+orderfname+"%2C+the+bar+is+out+of+stock+on+one+of+your+drink+items.+Sorry!+%23"+orderiid_short+".&?callback=?");
		$.post("/wp-admin/admin-ajax.php?action=status&id="+orderiid+"&status=7");
		$.post("https://www.itduzzit.com/duzz/api/twilio-send-sms.json?token=onz2gr9i9khj0qx&Mobile+Number+to+Call=6148044000&Send+from+Mobile+Number=6466993569&Text=Hey+Kyle%2C+the+Flowtab+drink+order+%23"+orderiid+"+has+been+voided!&?callback=?");
		setTimeout("stopSpin()",500);
	});

	$("#real-batch-btn").bind(evname, function(){
		startSpin();
		$("#batch-out-wrap,#order-info").hide();
		$(".ready").addClass("dead");
		setTimeout("stopSpin()",2000);
		setTimeout("alert('You Have Successfully Batched Out. All Orders Are Now Closed.');",2300);
		$.post("/wp-admin/admin-ajax.php?action=batchout&bar="+barid);	
	});

	$("#real-fax-btn").bind(evname, function(){
		startSpin();
		event.preventDefault();
		$("#print-alert-wrap").hide();
		fax = '+18553367195';
		message = 'Testing, testing 123… hello there Kyle!';
		$.post("/wp-admin/admin-ajax.php?action=sendfax&fax="+fax+"&message="+message);
		setTimeout("stopSpin()",500);
	});

	$(".alert-wrap,.cancel-btn,#cancel-print-btn").bind(evname, function(){
		$(".alert-wrap").hide();
		setTimeout("stopSpin()",300);
	});
	
	$(".alert-inner,.hidden").bind(evname, function(ev){
		ev.stopPropagation();
	});

	$(".logo").bind(evname, function(){
		//$('head').append('<link type="text/css" rel="stylesheet" href="/wp-content/themes/Starkers/css/my-print.css" />');
	});
	
});

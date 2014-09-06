<!DOCTYPE html>
<html><head>

<!-- META -->
<title>Srvd</title>
<meta http-equiv="content-type" content="text/html;charset=UTF-8">
<meta name="viewport" content="initial-scale=1.0; maximum-scale=1.0; user-scalable=0">
<meta name="format-detection" content="telephone=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<link rel="apple-touch-icon-precomposed" href="/wp-content/themes/Starkers/img/srvd-logo.png">
<link rel="icon" type="image/x-icon" href="http://srvdme.com/wp-content/themes/Starkers/img/favicon.ico">
<link rel="apple-touch-startup-image" sizes="1024x748" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape) and (-webkit-min-device-pixel-ratio: 1)" href="http://srvdme.com/wp-content/themes/Starkers/img/splash2.jpg" />

<!-- CSS -->
<link type="text/css" rel="stylesheet" media="screen" href="/wp-content/themes/Starkers/css/my-piggy.css?4747" />
<link type="text/css" rel="stylesheet" media="print" href="/wp-content/themes/Starkers/css/my-print.css" />
<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400,700">

<!-- SPIN -->

<div id="spinner-wrap">
	<div id="spinner-box">
		<div class="spinner">
			<div class="bar1 spin-bars"></div>
			<div class="bar2 spin-bars"></div>
			<div class="bar3 spin-bars"></div>
			<div class="bar4 spin-bars"></div>
			<div class="bar5 spin-bars"></div>
			<div class="bar6 spin-bars"></div>
			<div class="bar7 spin-bars"></div>
			<div class="bar8 spin-bars"></div>
			<div class="bar9 spin-bars"></div>
			<div class="bar10 spin-bars"></div>
			<div class="bar11 spin-bars"></div>
			<div class="bar12 spin-bars"></div>
		</div><!--/spinner-->
	</div><!--/spinner-box-->
</div><!--/spinner-wrap-->

<!-- PHP -->

<?php global $current_user;
	get_currentuserinfo();
	$barid = $current_user->ID;
	$user_fname = $current_user->user_firstname;
	$user_pickup = $current_user->comment_shortcuts;
	$user_addy = str_replace('>','<br/>',$current_user->user_description);
	$user_phone = preg_replace('~(\d{3})[^\d]*(\d{3})[^\d]*(\d{4})$~','$1-$2-$3',($current_user->user_login));
	$user_website = ltrim($current_user->user_url, "http://");
	$todays_date = date('F jS, Y',time() - 28800);
	$todays_day = date('l F jS',time() - 28800);
?>

<script type="text/javascript">
	barid = "<?php echo $barid; ?>";
	barname = "<?php echo $user_fname; ?>";
	baremail = "<?php echo $current_user->user_email; ?>";
	startdate = <?php echo strtotime("today, 4:00"); ?>;
	enddate = "<?php echo time(); ?>";
	todaysdate = "<?php echo $todays_date; ?>";
	todaysdate = "<?php echo $todays_day . ' Night'; ?>";
	pickup = "<?php echo $user_pickup; ?>";
	URL = "<?php get_site_url() ?>";
</script>

<!-- BODY -->

</head><body>

<div id="jqt">
	<?php if(!(is_user_logged_in())) { ?>
		<script type="text/javascript">isLogin = 1;</script>
		<div id="tb">
			<div id="tab-wrapper">
				<div id="logo-wrap">
					<div class="logo"></div>
				</div>
			</div><!--/tab-wrapper-->
		</div><!--/tb-->
	    <div id="login">
	        <div class="form">
	            <div class="f-line"></div>
				<form action="" method="post" class="sign-in" id="login-form1">
					<input placeholder="Manager Number" type="tel" name="user-name" id="user-name" maxlength="10" class="text-input" autocomplete="off" onkeypress="return isNumberKey(event)">
					<input  placeholder="4-Digit PIN" type="tel" name="password" id="password" maxlength="4" class="text-input" autocomplete="off" onkeypress="return isNumberKey(event)">
					<div id="login-success"><span class="green-check"></span><p>You have logged in successfully.</p></div>
					<div id="login-fail"><span class="red-x"></span><p>Your login credentials were invalid.</p></div>
					<input type="submit" name="submit" id="login-btn" value="Sign In">
				</form><!--/sign-in-->
				<div class="terms-wrap">Forgot your password? 24/7 Emergency Support 1-888-335-9804</div>
			</div><!--/scroll-->
	    </div><!--/login-->
	<?php } else { ?>
		<?php if(!(current_user_can('administrator'))) { header("Location: http://ec2-54-191-220-0.us-west-2.compute.amazonaws.com/"); } ?>
		<script type="text/javascript">isLogin = 0;</script>
		<div id="tb">
			<div id="tab-wrapper">
				<div id="emergency">24/7 Emergency Support:<br/>1-888-335-9804</div>
				<div id="bar-name"></div>
				<div id="logo-wrap">
					<div class="logo"></div>
				</div><!--/logo-wrap-->
				<div id="options-group">
					<div id="node-check"></div>
					<?php if ( get_user_meta( get_current_user_id(), 'jabber', true ) == 1 ) { ?>
						<div id="open-btn"></div>
						<div id="closed-btn" class="hidden"></div>
					<?php } else { ?>
						<div id="open-btn" class="hidden"></div>
						<div id="closed-btn"></div>
					<?php } ?>
					<?php if ( get_user_meta( get_current_user_id(), 'yim', true ) == 1 ) { ?>
						<div id="happy-btn"></div>
						<div id="unhappy-btn" class="hidden"></div>					
					<?php } else { ?>
						<div id="happy-btn" class="hidden"></div>
						<div id="unhappy-btn"></div>	
					<?php } ?>
					<?php if ( get_user_meta( get_current_user_id(), 'aim', true ) == 1 ) { ?>
						<div id="tables-btn"></div>
						<div id="notables-btn" class="hidden"></div>
						<script type="text/javascript">tableservice = 1;</script>		
					<?php } else { ?>
						<div id="tables-btn" class="hidden"></div>
						<div id="notables-btn"></div>
						<script type="text/javascript">tableservice = 0;</script>
					<?php } ?>
					<?php if ( get_user_meta( get_current_user_id(), 'show_admin_bar_front', true ) == 1 ) { ?>
						<div id="print-btn"></div>
						<div id="noprint-btn" class="hidden"></div>
						<script type="text/javascript">printorders=1</script>		
					<?php } else { ?>
						<div id="print-btn" class="hidden"></div>
						<div id="noprint-btn"></div>
						<script type="text/javascript">printorders=0;</script>
					<?php } ?>
					<div id="logout-btn"></div>	
				</div><!--/options-group-->
			</div><!--/tab-wrapper-->
		</div><!--/tb-->	
		<div id="content">
			<div id="left-box">
				<div class="addy-wrap">
					<img id="ft-print" src="http://srvdme.com/wp-content/themes/Starkers/img/ft-print.jpg">
					<div class="addy-print">
						<p class="addy-line" id="addy1"><?php echo $user_fname?></p>
						<p class="addy-line" id="addy2"><?php echo $user_addy?></p>
						<p class="addy-line" id="addy3"><?php echo $user_city;?></p>
						<p class="addy-line" id="addy4"><?php echo $user_phone;?></p>
						<p class="addy-line" id="addy5"><?php echo $user_website;?></p>
						<p class="addy-line" id="addy6"><?php echo $todays_date;?></p>
					</div><!--/addy-print-->
				</div><!--/addy-wrap-->	
				<div id="top-wrap">
					<div id="show-wrap">
						<div id="show-new" class="show-new-clicked"></div>
						<div id="show-all"></div>
						<div id="show-shifts"></div>
						<div id="show-totals"></div>	
					</div><!--/show-wrap-->
				</div><!--/top-wrap-->
				<div id="shifts">
					<div id="shifts-wrap">
						<div id="activeshift-div"></div>
						<div id="piggyshifts-div"></div>
						<div id="servertables-div"></div>
						<div class="bottom-wrap">
							<div id="shifts-btns">
								<div id="end-btn" class="blue-btn red-btn">End Shift</div>
								<div id="shifts-btn" class="blue-btn">&larr; Back</div>
								<div id="servers-btn" class="blue-btn hidden">Tables</div>
								<div id="shprint-btn" class="blue-btn">Print</div>
							</div><!--/shifts-btns-->
						</div><!--/bottom-wrap-->
					</div><!--/shifts-wrap-->
				</div><!--/shifts-->
				<div id="totals">
					<div id="totals-wrap">
						<div id="orderhistory-div"></div>
						<div id="piggycats-div"></div>
						<div id="piggyday-div"></div>
						<div id="piggyweek-div"></div>
						<div class="bottom-wrap">
							<div id="totals-btns">
								<div id="email-btn" class="blue-btn red-btn">Email</div>
								<div id="totals-btn" class="blue-btn">&larr; Back</div>
								<div id="cats-btn" class="blue-btn">Categories</div>
								<div id="orders-btn" class="blue-btn">Orders</div>
							</div><!--/totals-btns-->
						</div><!--/bottom-wrap-->
					</div><!--/totals-wrap-->
				</div><!--/totals-->
				<div id="queue">
					<div id="queue-w"><div id="scroller"><div id="pullDown"></div><ul id="thelist">
						<div id="getorders-div"></div>
					</ul><!--/thelist--><div id="pullUp"></div></div><!--/scroller--></div><!--/queue-w-->
				</div><!--/queue-->
				<div id="no-orders"></div>
				<div id="manager-verify-wrap" class="alert-wrap">
					<div id="manager-alert" class="alert-inner">
						<div id="manager-text">Please enter your PIN.</div>
						<form action="" method="post" class="manager-verify" id="manager-verify">
							<input type="hidden" id="bar-verify" value="<?php echo $barid; ?>">
							<input placeholder="Manager PIN" type="tel" name="pswd-verify" id="pswd-verify" maxlength="4" class="text-input" autocomplete="off" onkeypress="return isNumberKey(event)">
							<input type="submit" name="submit" id="verify-btn" value="Verify">
						</form><!--/manager-verify-->
					</div><!--/manager-alert-->
				</div><!--/manager-verify-wrap-->
				<div id="name-verify-wrap" class="alert-wrap">
					<div id="name-alert" class="alert-inner">
						<div id="name-text">Please enter your name.</div>
						<form action="" method="post" class="end-shift" id="end-shift">
							<input name="name-verify" id="name-verify" maxlength="20" class="text-input" autocomplete="off">
							<input type="submit" name="submit" id="name-btn" value="Confirm">
						</form><!--/name-verify-->
					</div><!--/name-alert-->
				</div><!--/name-verify-wrap-->
				<div id="void-alert-wrap" class="alert-wrap">
					<div id="void-alert" class="alert-inner">
						<div id="void-text">Are you sure you want to void this order?</div>
						<div id="real-void-btn"></div>
						<div class="cancel-btn"></div>
					</div><!--/void-alert-->
				</div><!--/void-alert-wrap-->
				<div id="stock-alert-wrap" class="alert-wrap">
					<div id="stock-alert" class="alert-inner">
						<div id="stock-text">Are you sure you want to void this order due to stock limitations?</div>
						<div id="real-stock-btn"></div>
						<div class="cancel-btn"></div>
					</div><!--/stock-alert-->
				</div><!--/stock-alert-wrap-->
				<div id="close-alert-wrap" class="alert-wrap">
					<div id="close-alert" class="alert-inner">
						<div id="close-text">Are you sure you want to close out this order?</div>
						<div id="real-close-btn"></div>
						<div class="cancel-btn"></div>
					</div><!--/close-alert-->
				</div><!--/close-alert-wrap-->
				<div id="print-alert-wrap" class="alert-wrap">
					<div id="print-alert" class="alert-inner">
						<div id="print-text">Would you like to print this shift?</div>
						<div id="print-shift-btn">Yes</div>
						<div id="cancel-print-btn">No</div>
					</div><!--/close-alert-->
				</div><!--/print-alert-wrap-->
				<div class="addy-wrap" class="alert-wrap">
					<div id="footer-print" class="alert-inner">
						<p id="merchant-record">***MERCHANT RECORD***</p>
						<p class="addy-line" id="footer1">© 2012 Srvd, Inc.</p>
						<p class="addy-line" id="footer5">http://ec2-54-191-220-0.us-west-2.compute.amazonaws.com</p>
					</div><!--/footer-print-->
				</div><!--/addy-wrap-->
			</div><!--/left-box-->
			<div id="right-box">
				<div id="order-info">	
					<div id="info-name">
						<div id="crown"></div>
						<div id="sofa"></div>
						<span id="table-num"></span>
						<span id="fname"></span>
						<span id="lname"></span>
						<span id="info-num"></span>
					</div><!--/info-name-->
					<div id="btn-wrap">
							<div id="print-arrow"></div>
							<div id="remind-btn"></div>
							<div id="ready-btn"></div>
						<div id="small-btn-wrap">
							<div id="void-btn"></div>
							<div id="stock-btn"></div>
							<div id="close-btn"></div>
							<div id="stock-top"></div>
							<div id="stock-msg"></div>
						</div><!--/small-btn-wrap-->
					</div><!--/btn-wrap-->
					<div id="receipt-top">
						<div id="info-header1">#</div>
						<div id="info-header2">Product</div>
						<div id="info-header3">Price</div>
					</div><!--/receipt-top-->
					<div id="receipt-middle"></div>
					<div id="receipt-bottom"></div>
				</div><!--/order-info-->
				<div id="batch-out-wrap">
					<div id="batch-out-alert">
						<div id="batch-out-text">Are you sure you want to batch out all of tonight's orders?</div>
						<div id="real-batch-btn"></div>
						<div class="cancel-btn"></div>
					</div><!--/close-alert-->
				</div><!--/batch-out-wrap-->
				<div id="all-wrap">
					<!-- <div id="end-shift-btn"></div> -->
					<!-- <div id="batch-btn"></div> -->
				</div><!--/all-wrap-->		
				<div id="new-wrap">
					<p id="new-text"></p>
					<div id="new-arrow"></div>
					<p id="new-ad">Download <span id="new-logo"></span> to order drinks from your phone.<br/><strong>First drink free!</strong><br/><span id="first">Available for iPhone and Android</span></p><!--/new-ad-->
				</div><!--/new-wrap-->
			</div><!--/right-box-->
		</div><!--/content-->
	<?php } ?><!--/login-->
</div><!--/jqt2-->


</body>

<!-- JS -->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="http://srvdme.com/wp-content/themes/Starkers/js/countup.js"></script>
<script type="text/javascript" src="http://srvd-node.herokuapp.com/socket.io/socket.io.js"></script>  
<script type="text/javascript" src="/wp-content/themes/Starkers/js/my-piggy.js?911198"></script>
<script type="text/javascript" src="http://srvdme.com/wp-content/themes/Starkers/js/iscroll.js"></script>
<script type="text/javascript" src="http://srvdme.com/wp-content/themes/Starkers/js/i-piggy.js?"></script>

</html>

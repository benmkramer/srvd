<?php /** Template Name: Home */ ?>
<!DOCTYPE html>
<html><head>

<?php $iphone = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
if ($iphone) { ?><script type="text/javascript">var evname = 'touchstart';</script>
<?php } else { ?><script type="text/javascript">var evname = 'click';</script><?php } ?>

<!-- META -->
<title>Srvd : Drink Easy</title>
<meta http-equiv="content-type" content="text/html;charset=UTF-8">
<meta name="viewport" content="initial-scale=1.0; maximum-scale=1.0; user-scalable=0;">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<link rel="apple-touch-icon-precomposed" href="http://srvdme.com/wp-content/themes/Starkers/img/icon.jpg">
<link rel="icon" type="image/x-icon" href="https://pbs.twimg.com/profile_images/450077642251706368/_7dKkVV__bigger.png">
<link href="http://srvdme.com/wp-content/themes/Starkers/img/splash4.jpg" media="(device-width: 320px) and (device-height: 480px) and (-webkit-device-pixel-ratio: 1)" rel="apple-touch-startup-image">
<link href="http://srvdme.com/wp-content/themes/Starkers/img/splash6.jpg" media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image">

<!-- CSS -->
<link type="text/css" rel="stylesheet" href="/wp-content/themes/Starkers/css/my-home.css">

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
		<div id="spinner-gif">
			<div id="spinner-block1" class="spinner-block"></div>
			<div id="spinner-block2" class="spinner-block"></div>
			<div id="spinner-block3" class="spinner-block"></div>
		</div>
		<p id="stuck-loading">Try again...</p>
	</div><!--/spinner-box-->
</div><!--/spinner-wrap-->

<!-- DATA -->
<div id="menu-storage" class="hidden"></div>

<!-- BODY -->
</head><body>
    <div id="jqt">
        <div id="home" class="current">
	        <div id="logout">
	        	<!--<div class="logo-wrap"><div id="logo"></div></div> -->
	        	<div id="img-box">
	        		<span id="checkers-box"><img id="taps" src="/wp-content/themes/Starkers/img/srvd-logo.png"></span>
	        		<span id="barpad-box"><img id="taps" src="/wp-content/themes/Starkers/img/srvd-logo.png"></span>
	        		<span id="taps-box"><img id="taps" src="/wp-content/themes/Starkers/img/srvd-logo.png"></span>
	        	</div>
	        	<p id="smally">Order and pay from the comfort of your phone.</p>
	        	<div id="home-out">
	        		<div id="home-block2" class="touchup" href="#register">
	        			<span class="line1">Sign Up</span>
	        			<span class="line2">Create an account</span>
	        		</div><!--/home-block2--> 
					<div id="home-block1" class="touchup" href="#login">
						<span class="line1">Login</span>
						<span class="line2">Welcome back</span>
					</div><!--/home-block1--> 
				</div><!--/home-out-->                   
	        </div><!--/logout-->
	        <div id="homepage">
				<!--<div class="logo-wrap"><div id="logo"></div></div> -->
				<div id="info-btn"></div>
				<div id="backdrop">
					<span class="tapleft locations" href="#locations">
						<div id="thirsty-btn">
							<div id="thirsty-icon"></div>
							<div class="home-text">I'm Thirsty!</div>
							<div class="white-arrow"></div>
						</div><!--/btn--></span>
					<span class="tapleft settings" href="#settings">
						<div id="settings-btn">
							<div id="settings-icon"></div>
							<div class="home-text">Settings</div>
							<div class="white-arrow"></div>
						</div><!--/btn--></span>
					<span class="tapleft support" href="#feedback">
						<div id="coupons-btn">
							<div id="coupons-icon"></div>
							<div class="home-text">Feedback</div>
							<div class="white-arrow"></div>
						</div><!--/btn--></span>
					<div id="big-reward">0</div>
				</div><!--/backdrop-->
		    </div><!--/homepage-->
    	</div><!--/home-->

        <div id="login" class="pattern">
            <div class="toolbar">
                <h1>Login</h1>
                <span class="cancel touchdown" href="#home">Close</span>
            </div><!--/toolbar-->
            <div class="login-valid">
				<span class="green-check"></span><p>Logged in successfully!</p>
			</div><!--/login-valid-->
            <div class="login-invalid">
				<span class="red-x"></span><p>Your credentials are invalid!</p>
			</div><!--/login-invalid-->
            <div class="form">
            	<div class="f-line"></div>
				<form action="" method="post" class="sign-in" id="login-form">
					<input placeholder="Cell Number" type="tel" name="user-name" id="user-name" maxlength="10" class="text-input secure" autocomplete="off" onkeypress="return isNumberKey(event)" onBlur="loginDashes(this)">
					<input  placeholder="4-Digit PIN" type="tel" name="password" id="password" maxlength="4" class="text-input secure" autocomplete="off" onkeypress="return isNumberKey(event)">
					<div id="login-btn">Sign In</div>
				</form><!--/sign-in-->
				<div class="terms-wrap"><span class="touchleft terms-btn" href="#forgot">Did you forget your PIN?</span></div>
			</div><!--/form-->
        </div><!--/login-->

        <div id="forgot" class="pattern">
            <div class="toolbar">
                <h1>Forgot</h1>
                <span class="back touchright" href="#login">Login</span>
            </div><!--/toolbar-->
            <div class="forgot-valid">
				<span class="green-check"></span><p>We texted you a new PIN!</p>
			</div><!--/forgot-valid-->
            <div class="forgot-invalid">
				<span class="red-x"></span><p>Please enter a valid number!</p>
			</div><!--/forgot-invalid-->
            <div class="form">
	            <div class="f-line"></div>
				<form action="" method="post" class="password-form" id="password-form">
					<input placeholder="Cell Number" type="tel" name="lost-pswd" id="lost-pswd" maxlength="10" class="text-input" autocomplete="off" onkeypress="return isNumberKey(event)" onBlur="loginDashes(this)">
					<div id="forgot-btn">Reset PIN</div>
				</form><!--/lost-pswd-->
			</div><!--/form-->
        </div><!--/forgot-->

        <div id="register" class="pattern">
            <div class="toolbar">
                <h1>Sign Up</h1>
                <span class="cancel touchdown" href="#home">Close</span>
            </div><!--/toolbar-->
            <div class="register-valid">
				<span class="green-check"></span><p>Welcome to Srvd!</p>
			</div><!--/register-valid-->
            <div class="register-invalid">
				<span class="red-x"></span><p id="register-msg"></p>
			</div><!--/register-invalid-->
            <div class="form">
				<div class="f-line"></div>
				<form enctype="multipart/form-data" method="post" class="user-forms secure" id="register-form" action="">
					<input placeholder="First Name" class="text-input secure" name="first_name" type="text" id="first_name" value="">
					<input placeholder="Last Name" class="text-input secure" name="last_name" type="text" id="last_name" value="">
					<input placeholder="Cell Number" class="text-input secure" name="user_name" type="tel" maxlength="10" id="user_name" onkeypress="return isNumberKey(event)" onBlur="loginDashes(this)">
					<input placeholder="Email" class="text-input secure" name="email" type="email" id="email" value="">
					<input placeholder="Create 4-Digit PIN" class="text-input secure" name="pass1" type="tel" maxlength="4" id="pass" onkeypress="return isNumberKey(event)">
					<div id="register-btn">Sign Up</div>
				</form><!--/register-form-->
				<div class="terms-wrap">Srvd uses your cell number to text you when your drinks are ready.</div>
			</div><!--/form-->
        </div><!--/register-->

	    <div id="about" class="pattern">
	        <div class="toolbar">
	            <h1>How it Works</h1>
	            <span class="cancel touchdown" href="#home">Done</span>
	            <span class="back touchright" href="#settings">Settings</span>
	        </div><!--/toolbar-->
			<div id="about-w"><div class="scroller">
	        	<div class="f-line"></div>
	        	<div class="text">
					<p>Srvd lets you order and pay for your drinks, from anywhere within a bar, right from the comfort of your phone. No more waiting, no more wallets and no more open tabs.</p>
					<h2>Step 1: Browse</h2>
					<p>Choose from a  selection of the bar's most popular drink items, and add them to your cart.</p>
					<h2>Step 2: Pay</h2>
					<p>Enter your credit card details, adjust the gratuity amount and place your order.</p>
					<h2>Step 3. Pick Up</h2>
					<p>Your order will be sent to an iPad behind the bar and you will receive a text when your drinks are ready. Just go to the Srvd pick-up area and grab your drinks, or for table orders just relax and wait for your server.</p><br/>
					<p><center><em>The fastest way to order and pay.</em></center></p>
				</div><!--/text-->
			</div><!--/scroller--></div><!--/wrapper-->
	    </div><!--/about-->

        <div id="locations" class="pattern">
            <div class="toolbar">
                <h1>Locations</h1>
                <span class="back-home" href="#home">Home</span>
                <span class="cancel refresh">Refresh</span>
            </div><!--/toolbar-->
            <ul id="locations-w" class="edgetoedge"><div class="scroller">
                <div id="locations-div"></div>          	
            </div><!--/scroller--></ul><!--/wrapper-->
        </div><!--/locations-->

        <div id="categories" class="pattern">
            <div class="toolbar">
                <h1 id="bar-name">Categories</h1>
                <span class="back touchright locations" href="#locations">Locations</span>
                <span class="button touchleft cart" href="#cart">Review</span>
                <div class="count">0</div>
            </div><!--/toolbar-->
            <div class="wifi-alert">
				<span class="green-wifi"></span><p>Connect to Srvd's free WiFi!</p>
			</div><!--/wifi-alert-->
            <ul id="categories-w" class="edgetoedge"><div class="scroller">
	            <li class="arrow beer tapleft" href="#beer">
	            	<div class="imagecol"></div>
	            	<p class="title">Beer</p>
	            	<p class="addy">Cold and crisp beers</p>
	            	<div class="cat-arrow"></div>
	            </li><!--/arrow-->
	            <li class="arrow wine tapleft" href="#wine">
	            	<div class="imagecol"></div>
	            	<p class="title">Wine</p>
	            	<p class="addy">Whites, reds and bubbly</p>
	            	<div class="cat-arrow"></div>
	            </li><!--/arrow-->
	            <li class="arrow cocktails tapleft" href="#cocktails">
	            	<div class="imagecol"></div>
	            	<p class="title">Cocktails</p>
	            	<p class="addy">Spirits and mixed drinks</p>
	            	<div class="cat-arrow"></div>
	            </li><!--/arrow-->
	            <li class="arrow shooters tapleft" href="#shooters">
	            	<div class="imagecol"></div>
	            	<p class="title">Shots</p>
	            	<p class="addy">Shots and shooters</p>
	            	<div class="cat-arrow"></div>
	            </li><!--/arrow-->
	            <li class="arrow soft-drinks tapleft" href="#soft-drinks">
	            	<div class="imagecol"></div>
	            	<p class="title">Soft Drinks</p>
	            	<p class="addy">Non-alcoholic beverages</p>
	            	<div class="cat-arrow"></div>
	            </li><!--/arrow-->
	            <li class="arrow food tapleft" href="#food">
	            	<div class="imagecol"></div>
	            	<p class="title">Food</p>
	            	<p class="addy">Basic appetizers</p>
	            	<div class="cat-arrow"></div>
	            </li><!--/arrow-->
            </div><!--/scroller--></ul><!--/wrapper-->
        </div><!--/categories-->

        <div id="beer" class="pattern">
            <div class="toolbar">
                <h1>Beer</h1>
                <span class="back touchright categories" href="#categories">Categories</span>
                <span class="button touchleft cart" href="#cart">Review</span>
                <div class="count">0</div>
            </div><!--/toolbar-->
            <div class="order-confirm">
				<span class="green-check"></span><p>Your drink has been added!</p>
			</div><!--/order-confirm-->
            <div class="max-drinks">
				<span class="green-check"></span><p>You have reached max drinks!</p>
			</div><!--/max-drinks-->
			<div id="beer-w"><div class="scroller">
				<div id="beer-menu" class="menus"></div>
			</div><!--/scroller--></div><!--/wrapper-->
        </div><!--/beer-->

        <div id="wine" class="pattern">
            <div class="toolbar">
                <h1>Wine</h1>
                <span class="back touchright categories" href="#categories">Categories</span>
                <span class="button touchleft cart" href="#cart">Review</span>
                <div class="count">0</div>
            </div><!--/toolbar-->
            <div class="order-confirm">
				<span class="green-check"></span><p>Your drink has been added!</p>
			</div><!--/order-confirm-->
            <div class="max-drinks">
				<span class="green-check"></span><p>You have reached max drinks!</p>
			</div><!--/max-drinks-->
            <div id="wine-w"><div class="scroller">
               <div id="wine-menu" class="menus"></div>
            </div><!--/scroller--></div><!--/wrapper-->
        </div><!--/wine-->
    
        <div id="cocktails" class="pattern">
            <div class="toolbar">
                <h1>Cocktails</h1>
                <span class="back touchright categories" href="#categories">Categories</span>
                <span class="button touchleft cart" href="#cart">Review</span>
                <div class="count">0</div>
            </div><!--/toolbar-->
            <ul id="cocktails-w" class="edgetoedge"><div class="scroller">
	            <li class="arrow featured tapleft" href="#featured">
	            	<div class="imagecol"></div>
	            	<p class="title">Featured</p>
	            	<p class="addy">Speciality cocktails</p>
	            	<div class="cat-arrow"></div>
	            </li><!--/arrow-->
	            <li class="arrow well tapleft" href="#well">
	            	<div class="imagecol"></div>
	            	<p class="title">Well</p>
	            	<p class="addy">Basic well cocktails</p>
	            	<div class="cat-arrow"></div>
	            </li><!--/arrow-->
	            <li class="arrow premium tapleft" href="#premium">
	            	<div class="imagecol"></div>
	            	<p class="title">Premium</p>
	            	<p class="addy">Top-shelf, premium cocktails</p>
	            	<div class="cat-arrow"></div>
	            </li><!--/arrow-->
	            <li class="arrow custom tapleft" href="#custom">
	            	<div class="imagecol"></div>
	            	<p class="title">Custom</p>
	            	<p class="addy">Build your own!</p>
	            	<div class="cat-arrow"></div>
	            </li><!--/arrow-->
            </div><!--/scroller--></ul><!--/wrapper-->
        </div><!--/cocktails-->

	        <div id="featured" class="pattern">
	            <div class="toolbar">
	                <h1>Featured</h1>
	                <span class="back cocktails touchright" href="#cocktails">Cocktails</span>
	                <span class="button touchleft cart" href="#cart">Review</span>
	                <div class="count">0</div>
	            </div><!--/toolbar-->
	            <div class="order-confirm">
					<span class="green-check"></span><p>Your drink has been added!</p>
				</div><!--/order-confirm-->
	            <div class="max-drinks">
					<span class="green-check"></span><p>You have reached max drinks!</p>
				</div><!--/max-drinks-->
				<div id="featured-w"><div class="scroller">
					<div id="featured-menu" class="menus"></div>
				</div><!--/scroller--></div><!--/wrapper-->
	        </div><!--/featured-->
	
	        <div id="well" class="pattern">
	            <div class="toolbar">
	                <h1>Well</h1>
	                <span class="back cocktails touchright" href="#cocktails">Cocktails</span>
	                <span class="button touchleft cart" href="#cart">Review</span>
	                <div class="count">0</div>
	            </div><!--/toolbar-->
	            <div class="order-confirm">
					<span class="green-check"></span><p>Your drink has been added!</p>
				</div><!--/order-confirm-->
	            <div class="max-drinks">
					<span class="green-check"></span><p>You have reached max drinks!</p>
				</div><!--/max-drinks-->
				<div id="well-w"><div class="scroller">
					<div id="well-menu" class="menus"></div>
				</div><!--/scroller--></div><!--/wrapper-->
	        </div><!--/well-->
	
	        <div id="premium" class="pattern">
	            <div class="toolbar">
	                <h1>Premium</h1>
	                <span class="back cocktails touchright" href="#cocktails">Cocktails</span>
	                <span class="button touchleft cart" href="#cart">Review</span>
	                <div class="count">0</div>
	            </div><!--/toolbar-->
	            <div class="order-confirm">
					<span class="green-check"></span><p>Your drink has been added!</p>
				</div><!--/order-confirm-->
	            <div class="max-drinks">
					<span class="green-check"></span><p>You have reached max drinks!</p>
				</div><!--/max-drinks-->
				<div id="premium-w"><div class="scroller">
					<div id="premium-menu" class="menus"></div>
				</div><!--/scroller--></div><!--/wrapper-->
	        </div><!--/premium-->
	
	        <div id="custom" class="pattern">
	            <div class="toolbar">
	                <h1>Custom</h1>
	                <span class="back cocktails touchright" href="#cocktails">Cocktails</span>
	                <span class="button touchleft cart" href="#cart">Review</span>
	                <div class="count">0</div>
	            </div><!--/toolbar-->
	            <div class="order-confirm">
					<span class="green-check"></span><p>Your drink has been added!</p>
				</div><!--/order-confirm-->
	            <div class="max-drinks">
					<span class="green-check"></span><p>You have reached max drinks!</p>
				</div><!--/max-drinks-->
				<div id="mixers-wrap">
					<div id="mixers" class="swiper-container swiper-loop">
						<div id="mixer-title">&#8592;&nbsp;&nbsp;Choose a mixer, then a liquor…&nbsp;&nbsp;&#8594;</div>
						<div class="swiper-wrapper">

							<div class="mixer-item swiper-slide" mixer="Coke">Coke</div>
							<div class="mixer-item swiper-slide" mixer="Diet Coke">Diet Coke</div>
							<div class="mixer-item swiper-slide" mixer="Tonic">Tonic</div>
							<div class="mixer-item swiper-slide" mixer="Club Soda">Club Soda</div>
							<div class="mixer-item swiper-slide" mixer="Cranberry">Cranberry</div>
							<div class="mixer-item swiper-slide" mixer="Pineapple">Pineapple</div>
							<div class="mixer-item swiper-slide" mixer="Rocks">Rocks</div>
						
						</div>
					</div>
				</div>
				<div id="custom-w"><div class="scroller">
					<div id="custom-menu" class="menus"></div>
				</div><!--/scroller--></div><!--/wrapper-->
	        </div><!--/custom-->

        <div id="shooters" class="pattern">
            <div class="toolbar">
                <h1>Shots</h1>
                <span class="back touchright categories" href="#categories">Categories</span>
                <span class="button touchleft cart" href="#cart">Review</span>
                <div class="count">0</div>
            </div><!--/toolbar-->
            <ul id="shooters-w" class="edgetoedge"><div class="scroller">
	            <li class="arrow vodka tapleft" href="#vodka">
	            	<div class="imagecol"></div>
	            	<p class="title">Vodka</p>
	            	<p class="addy">Pure, clean and smooth</p>
	            	<div class="cat-arrow"></div>
	            </li><!--/arrow-->
	            <li class="arrow rum tapleft" href="#rum">
	            	<div class="imagecol"></div>
	            	<p class="title">Rum</p>
	            	<p class="addy">Light, dark and spiced rums</p>
	            	<div class="cat-arrow"></div>
	            </li><!--/arrow-->
	            <li class="arrow whiskey tapleft" href="#whiskey">
	            	<div class="imagecol"></div>
	            	<p class="title">Whiskey</p>
	            	<p class="addy">Distinct bourbon whiskey</p>
	            	<div class="cat-arrow"></div>
	            </li><!--/arrow-->
	            <li class="arrow tequila tapleft" href="#tequila">
	            	<div class="imagecol"></div>
	            	<p class="title">Tequila</p>
	            	<p class="addy">Basic and premium tequilas</p>
	            	<div class="cat-arrow"></div>
	            </li><!--/arrow-->
	            <li class="arrow other tapleft" href="#other">
	            	<div class="imagecol"></div>
	            	<p class="title">Other</p>
	            	<p class="addy">Speciality liquors</p>
	            	<div class="cat-arrow"></div>
	            </li><!--/arrow-->
            </div><!--/scroller--></ul><!--/wrapper-->
        </div><!--/shooters-->

	        <div id="vodka" class="pattern">
	            <div class="toolbar">
	                <h1>Vodka</h1>
	                <span class="back touchright shots" href="#shooters">Shots</span>
	                <span class="button touchleft cart" href="#cart">Review</span>
	                <div class="count">0</div>
	            </div><!--/toolbar-->
	            <div class="order-confirm">
					<span class="green-check"></span><p>Your drink has been added!</p>
				</div><!--/order-confirm-->
	            <div class="max-drinks">
					<span class="green-check"></span><p>You have reached max drinks!</p>
				</div><!--/max-drinks-->
				<div id="vodka-w"><div class="scroller">
					<div id="vodka-menu" class="menus"></div>
				</div><!--/scroller--></div><!--/wrapper-->
	        </div><!--/vodka-->
	
	        <div id="rum" class="pattern">
	            <div class="toolbar">
	                <h1>Rum</h1>
	                <span class="back touchright shots" href="#shooters">Shots</span>
	                <span class="button touchleft cart" href="#cart">Review</span>
	                <div class="count">0</div>
	            </div><!--/toolbar-->
	            <div class="order-confirm">
					<span class="green-check"></span><p>Your drink has been added!</p>
				</div><!--/order-confirm-->
	            <div class="max-drinks">
					<span class="green-check"></span><p>You have reached max drinks!</p>
				</div><!--/max-drinks-->
				<div id="rum-w"><div class="scroller">
					<div id="rum-menu" class="menus"></div>
				</div><!--/scroller--></div><!--/wrapper-->
	        </div><!--/rum-->
	        
	        <div id="whiskey" class="pattern">
	            <div class="toolbar">
	                <h1>Whiskey</h1>
	                <span class="back touchright shots" href="#shooters">Shots</span>
	                <span class="button touchleft cart" href="#cart">Review</span>
	                <div class="count">0</div>
	            </div><!--/toolbar-->
	            <div class="order-confirm">
					<span class="green-check"></span><p>Your drink has been added!</p>
				</div><!--/order-confirm-->
	            <div class="max-drinks">
					<span class="green-check"></span><p>You have reached max drinks!</p>
				</div><!--/max-drinks-->
				<div id="whiskey-w"><div class="scroller">
					<div id="whiskey-menu" class="menus"></div>
				</div><!--/scroller--></div><!--/wrapper-->
	        </div><!--/whiskey-->
	
	        <div id="tequila" class="pattern">
	            <div class="toolbar">
	                <h1>Tequila</h1>
	                <span class="back touchright shots" href="#shooters">Shots</span>
	                <span class="button touchleft cart" href="#cart">Review</span>
	                <div class="count">0</div>
	            </div><!--/toolbar-->
	            <div class="order-confirm">
					<span class="green-check"></span><p>Your drink has been added!</p>
				</div><!--/order-confirm-->
	            <div class="max-drinks">
					<span class="green-check"></span><p>You have reached max drinks!</p>
				</div><!--/max-drinks-->
				<div id="tequila-w"><div class="scroller">
					<div id="tequila-menu" class="menus"></div>
				</div><!--/scroller--></div><!--/wrapper-->
	        </div><!--/tequila-->
	
	        <div id="other" class="pattern">
	            <div class="toolbar">
	                <h1>Other</h1>
	               	<span class="back touchright shots" href="#shooters">Shots</span>
	                <span class="button touchleft cart" href="#cart">Review</span>
	                <div class="count">0</div>
	            </div><!--/toolbar-->
	            <div class="order-confirm">
					<span class="green-check"></span><p>Your drink has been added!</p>
				</div><!--/order-confirm-->
	            <div class="max-drinks">
					<span class="green-check"></span><p>You have reached max drinks!</p>
				</div><!--/max-drinks-->
				<div id="other-w"><div class="scroller">
					<div id="other-menu" class="menus"></div>
				</div><!--/scroller--></div><!--/wrapper-->
	        </div><!--/other-->

        <div id="soft-drinks" class="pattern">
            <div class="toolbar">
                <h1>Soft Drinks</h1>
                <span class="back touchright categories" href="#categories">Categories</span>
                <span class="button touchleft cart" href="#cart">Review</span>
                <div class="count">0</div>
            </div><!--/toolbar-->
            <div class="order-confirm">
				<span class="green-check"></span><p>Your drink has been added!</p>
			</div><!--/order-confirm-->
            <div class="max-drinks">
				<span class="green-check"></span><p>You have reached max drinks!</p>
			</div><!--/max-drinks-->
            <div id="soft-drinks-w"><div class="scroller">
               <div id="soft-drinks-menu" class="menus"></div>
            </div><!--/scroller--></div><!--/wrapper-->
        </div><!--/soft-drinks-->
        
        <div id="food" class="pattern">
            <div class="toolbar">
                <h1>Food</h1>
                <span class="back touchright categories" href="#categories">Categories</span>
                <span class="button touchleft cart" href="#cart">Review</span>
                <div class="count">0</div>
            </div><!--/toolbar-->
            <div class="order-confirm">
				<span class="green-check"></span><p>Your drink has been added!</p>
			</div><!--/order-confirm-->
            <div class="max-drinks">
				<span class="green-check"></span><p>You have reached max drinks!</p>
			</div><!--/max-drinks-->
            <div id="food-w"><div class="scroller">
               <div id="food-menu" class="menus"></div>
            </div><!--/scroller--></div><!--/wrapper-->
        </div><!--/food-->		        

	    <div id="cart" class="pattern">
            <div class="toolbar">
                <h1>Review</h1>
                <span class="back-hist" href="#">Back</span>
                <span class="button checkout" href="#checkout">Checkout</span>
            </div><!--/toolbar-->
            <div class="f-line"></div>
            <div class="promo-valid">
				<span class="green-check"></span><p>Promo code accepted!</p>
			</div><!--/promo-valid-->
            <div class="promo-invalid">
				<span class="red-x"></span><p>That promo code is invalid!</p>
			</div><!--/promo-invalid-->
            <div class="table-invalid">
				<span class="red-x"></span><p>Enter valid table number!</p>
			</div><!--/table-invalid-->
			<div id="header">
				<div class="checkout-header1">Qty</div>
				<div class="checkout-header2">Product</div>
				<div class="checkout-header3">Price</div>
				<div class="checkout-header4">Total</div>
			</div><!--/header-->
			<div id="cart-div">			
				<div class="product_row">
					<div class="cart-qty">0</div>
					<div class="cart-name">Product Name</div>
					<div class="cart-price">$9.00</div>
					<form action="/wp-admin/admin-ajax.php?action=cart" method="post" class="adjustform qty-minus">
						<input type="hidden" name="quantity" value="1">
						<input type="hidden" name="key" value="0">
						<input type="hidden" name="wpsc_update_quantity" value="true">
						<input class="update-minus" type="submit" value="" name="submit-minus">
					</form><!--/adjustform-->
					<form action="/wp-admin/admin-ajax.php?action=cart" method="post" class="adjustform qty-plus">
						<input type="hidden" name="quantity" value="3">
						<input type="hidden" name="key" value="0">
						<input type="hidden" name="wpsc_update_quantity" value="true">
						<input class="update-plus" type="submit" value="" name="submit">
					</form><!--/adjustform-->
					<div class="group-price">$18.00</div>
				</div><!--/product-row-->
			</div><!--/cart-div-->
			<div id="promo-row">
				<form id="coupon-form">
					<input type="text" name="coupon_num" placeholder="Promotional Code" id="coupon_num" class="secure" autocomplete="off">
					<input id="promo-submit" type="submit" value="Redeem" />
					<div id="promo-update">Redeem</div>
				</form><!--/form-->
			</div><!--/promo-row-->
			<div id="subtotal-wrap">
				<div id="subtotal-label">Sub-Total:</div>
				<div id="subtotal-amount">$0.00</div>
			</div><!--/subtotal-wrap-->
			<div id="tax-wrap">
				<div id="gratuity-label">Processing:</div>
				<div id="tax-amount">$0.00</div>
			</div><!--/tax-wrap-->
			<div id="discount-wrap">
				<div id="discount-label">Discount:</div>
				<div id="discount-amount">$0.00</div>
			</div><!--/discount-wrap-->
			<div id="gratuity-wrap">
				<div id="gratuity-label">Tip:<div id="gratuity-minus"></div>
					<span id="gratuity-percent">20</span><div id="gratuity-plus"></div>
				</div><!--/gratuity-label-->
				<div id="gratuity-amount">$0.00</div>
			</div><!--/gratuity-wrap-->
			<div class="line"></div>
			<div class="total-wrap">
				<div class="total-label">Total:</div>
				<div class="total-amount">$0.00</div>
			</div><!--/total-wrap-->
			<div id="table-wrap">
				<div id="table-block">
					<div id="pickup-btn" class="pickup-clicked"></div>
					<div id="table-btn"></div>
					<div id="table-text">What is your table number?
						<input id="table-num" type="tel" name="table_num"  placeholder="##" size="2" maxlength="2" onkeypress="return isNumberKey(event)" autocomplete="off"></div>
				</div><!--/table-block-->
			</div><!--/table-wrap-->
	    </div><!--/cart-->

	    <div id="checkout" class="pattern">
            <div class="toolbar">
                <h1>Checkout</h1>
                <span class="back touchright clear-back" href="#cart">Review</span>
                <span class="touchright clear" href="#categories">Clear</span>
            </div><!--/toolbar-->
            <div class="card-declined">
				<span class="red-x"></span><p>Please check your card!</p>
			</div><!--/card-declined-->
            <div class="bar-closed">
				<span class="red-x"></span><p>This bar is now closed!</p>
			</div><!--/bar-closed-->
            <div class="mobile-invalid">
				<span class="red-x"></span><p>Please verify cell number!</p>
			</div><!--/mobile-confirm-->
            <div class="f-line"></div>
        	<div class="total-wrap" class="checkout-total">
				<div class="total-label">Total:</div>
				<div class="total-amount">$0.00</div>
			</div><!--/total-wrap-->
			<div class="saved-card"><span class="mini-card"></span><span class="stripe-last4"></span></div>
		    <div id="checkout-div"></div>
	    </div><!--/checkout-->

	    <div id="success" class="pattern">
            <div class="toolbar">
                <h1>Success</h1>
                <span class="button touchright finish" href="#categories">Gotcha</span>
            </div><!--/toolbar-->
            <div class="f-line"></div>
			<div class="text-box">
				<p id="green">Order Approved!</p>
				<p id="directions">We will text you when your drinks are ready. Pick up drinks at <span id="location"></span>!</p>
				<img id="responsibly" src="http://srvdme.com/wp-content/themes/Starkers/img/responsibly.png">
			</div><!--/text-box-->
	    </div><!--/success-->

    	<div id="settings" class="pattern">
            <div class="toolbar">
                <h1>Settings</h1>
                <span class="back-home" href="#">Home</span>
            </div><!--/toolbar-->
			<div class="scroll">
                <ul class="rounded">
					<li class="arrow authorize tapleft top-box" href="#authorize">
                    	<span class="settings">Credit Cards</span>
                    	<div class="black-arrow"></div>
                    </li><!--/arrow-->
                    <li class="arrow account tapleft" href="#account">
                    	<span class="settings">Account Settings</span>
                    	<div class="black-arrow"></div>
                    </li><!--/arrow-->
                    <li class="arrow affiliate tapleft bottom-box" href="#affiliate">
                    	<span class="settings">Affiliate Program</span>
                    	<div class="black-arrow"></div>
                    </li><!--/arrow-->
                </ul><!--/rounded-->
                <ul class="rounded">
                    <li class="arrow works tapleft top-box" href="#about">
                    	<span class="settings">How it Works</span>
                    	<div class="black-arrow"></div>
                    </li><!--/arrow-->
                    <li class="arrow security tapleft" href="#security">
                    	<span class="settings">Security Information</span>
                    	<div class="black-arrow"></div>
                    <li class="arrow security tapleft bottom-box" href="#share">
                    	<span class="settings">Share the Love</span>
                    	<div class="black-arrow"></div>
                    </li><!--/arrow-->
                </ul><!--/rounded-->
                <div class="welcome">Welcome, <span id="big-name"></span><br/>© 2014 Srvd, Inc. | v2.0</div>
			</div><!--/scroll-->
        </div><!--/settings-->

	    <div id="security" class="pattern">
	        <div class="toolbar">
	            <h1>Security</h1>
	            <span class="back touchright" href="#settings">Settings</span>
	        </div><!--/toolbar-->
	        <div id="security-w"><div class="scroller">
	        	<div class="f-line"></div>
	        	<div class="text">
					<h2>PCI Compliance</h2>
					<p>Our payment processing system has been audited by a PCI-certified auditor, and is certified to PCI Service Provider Level 1. This the most stringent level of certification available.</p>
					<h2>SSL and Encryption</h2>
					<p>Srvd features several layers of high-level encryption and authentication to process orders securely. We pass information only through http connections, and all card numbers are encrypted on disk with AES-256. Our infrastructure for storing, decrypting, and transmitting card numbers runs in a separate datacenter, and doesn't share any credentials with our primary app or services.</p>
					<h2>Lost Phone</h2>
					<p>We rely on a robust <em>tokenization service</em> to safely store your credit card details on a reliable 3rd-party site. Tokenization is a neat way of saying "data substitution". It is the act of using a substitute value, or <em>token</em>, which has no inherent value, in the place of data that does have value.<br/><br/>Therefore, your personal information is never stored on this mobile device. If your phone were to be lost or stolen, only your tokens would be compromised, not your actual card data.</p>
					<h2>Terms of Use</h2>
					<p>The creation and use of a Srvd account indicates your agreement to comply with our Terms of Use as well as any applicable local laws and regulations. Please visit srvdme.com/terms for details.</p>
				</div><!--/text-->
	        </div><!--/scroller--></div><!--/wrapper-->
	    </div><!--/security-->

	    <div id="authorize" class="pattern">
            <div class="toolbar">
                <h1>Save Card</h1>
                <span class="back auth-clear touchright" href="#settings">Settings</span>
                <span class="delete">Remove</span>
            </div><!--/toolbar-->
            <div class="f-line"></div>
            <div class="card-delete">
				<span class="green-check"></span><p>Card removed successfully!</p>
			</div><!--/card-delete-->
            <div class="card-accepted">
				<span class="green-check"></span><p>Card saved successfully!</p>
			</div><!--/card-accepted-->
            <div class="card-declined">
				<span class="red-x"></span><p>Oops, please check your card!</p>
			</div><!--/card-declined-->
            <div class="text-remind">
				<span class="green-check"></span><p>Check your text messages!</p>
			</div><!--/text-remind-->
		    <div id="authorize-div"></div>
		    <div class="auth-text"><span class="flipleft later" href="#locations">I'll enter it later…</span></div>
	    </div><!--/authorize-->

        <div id="account" class="pattern">
            <div class="toolbar">
                <h1>Account</h1>
                <span class="back touchright" href="#settings">Settings</span>
                <span class="touchright logout" href="#home">Logout</span>
            </div><!--/toolbar-->
            <div class="account-valid">
				<span class="green-check"></span><p>Success, please login again!</p>
			</div><!--/account-valid-->
            <div class="account-invalid">
				<span class="red-x"></span><p>Please fill in missing fields!</p>
			</div><!--/account-invalid-->
            <div class="exists-invalid">
				<span class="red-x"></span><p>This cell number already exists!</p>
			</div><!--/exists-invalid-->
            <div class="form">
				<div class="f-line"></div>
				<form enctype="multipart/form-data" method="post" id="account-form" class="user-forms" action="">
					<input placeholder="First Name" class="text-input" name="first_name2" type="text" id="first_name2" value="">
					<input placeholder="Last Name" class="text-input" name="last_name2" type="text" id="last_name2" value="">
					<input placeholder="Cell Number" class="text-input" name="user_name2" type="tel" maxlength="10" id="user_phone2">
					<input placeholder="Email Address" class="text-input" name="email2" type="email" id="email2" value="">
					<div id="account-btn">Update</div>
				</form><!--/register-form-->
				<div class="terms-wrap">To receive sales receipts, please enter a valid email. If you change your cell number, you will be asked to login again.</div>
			</div><!--/form-->
        </div><!--/account-->

	    <div id="affiliate" class="pattern">
	        <div class="toolbar">
	            <h1>Affiliate</h1>
                <span class="back touchright" href="#settings">Settings</span>
	        </div><!--/toolbar-->
            <div class="msg-sent">
				<span class="green-check"></span><p>We will contact you shortly!</p>
			</div><!--/msg-sent-->
        	<div id="affiliate-w"><div class="scroller">
        		<div class="f-line"></div>
	        	<div class="text">
	        		<div id="aff-true">
		        		<p>Whenever your balance reaches $25, we will send you a deposit via PayPal.<br/><br/><strong>Please verify the email you have on file with Srvd matches one of your addresses at PayPal.</strong></p>
		        		<div class="aff-header">Code</div>
		        		<div class="aff-header">Orders</div>
		        		<div class="aff-header">Earnings</div>
		        		<div id="aff-code" class="aff-block"></div>
		        		<div id="aff-orders" class="aff-block"></div>
		        		<div id="aff-earnings" class="aff-block"></div>    		
	        		</div><!--/aff-true-->
	        		<div id="aff-false">
		        		<h2>The Opportunity</h2>
		        		<p>As a Srvd affiliate, you will receive $5 for each new user who enters your discount promo code at checkout, then 25&#162; for each order after that (for up to one year).<br/><br/>If you refer a new affiliate to Srvd, you will earn 20% of all the money they make as well.<br/><br/>Click below and one of us will reach out to you.</p>
		        		<div id="submit-affilite">I'm Interested</div>
					</div><!--/aff-false-->
				</div><!--/text-->
			</div><!--/scroller--></div><!--/wrapper-->
	    </div><!--/rewards-->

	    <div id="share" class="pattern">
	        <div class="toolbar">
	            <h1>Share</h1>
                <span class="back touchright" href="#settings">Settings</span>
	        </div><!--/toolbar-->
        	<div class="f-line"></div>
        	<div class="text">
        		<h2>Send a Free Drink</h2>
        		<p>Have a friend who you think might like Srvd? Email them a free download link:</p>
        		<a href="mailto:?subject=Check%20out%20Srvd&body=Srvd%20lets%20you%20order%20and%20pay%20for%20drinks%20in%20crowded%20nightclubs%20and%20bars%20right%20from%20your%20phone.%0D%0A%0D%0ADownload%20the%20free%20iPhone/Android%20app%20at%20http://flwtb.co/Dwnload%20or%20play%20with%20the%20HTML5%20web%20app%20at%20http://srvdme.com."><div id="send-text">Send Message</div></a>
	        </div><!--/text-->
	    </div><!--/share-->

    	<div id="feedback" class="pattern">
	        <div class="toolbar">
	            <h1>Feedback</h1>
	            <span class="back touchright" href="#home">Home</span>
	        </div><!--/toolbar-->
	        <div class="scroll">
	            <ul class="rounded">
	                <li class="arrow tapleft top-box" href="#suggestion">
                    	<span class="settings">Suggestions</span>
                    	<div class="black-arrow"></div>    
	                </li><!--/arrow-->
	                <li class="arrow tapleft bottom-box" href="#bug">
                    	<span class="settings">Report a Bug</span>
                    	<div class="black-arrow"></div>
	                </li><!--/arrow-->
	            </ul><!--/rounded-->
            </div><!--/scroll-->
        </div><!--/feedback-->

	    <div id="suggestion" class="pattern">
	        <div class="toolbar">
	            <h1>Suggestions</h1>
                <span class="back touchright" href="#feedback">Feedback</span>
	        </div><!--/toolbar-->
            <div class="msg-sent">
				<span class="green-check"></span><p>Your message was sent!</p>
			</div><!--/msg-sent-->
	        <div class="scroll">
	        	<div class="f-line"></div>
	        	<div class="text">
	        		<p>Have a friendly suggestion for us? We'd love to hear about it…</p>
					<textarea id="suggestion-textarea" class="area-input secure" placeholder="Remember, be nice!" cols="30" rows="5"></textarea>
					<div id="submit-suggestion">Submit Suggestion</div>
		        </div><!--/text-->
		    </div><!--/scroll-->
	    </div><!--/suggestion-->

	    <div id="bug" class="pattern">
	        <div class="toolbar">
	            <h1>Bug Report</h1>
                <span class="back touchright" href="#feedback">Feedback</span>
	        </div><!--/toolbar-->
            <div class="msg-sent">
				<span class="green-check"></span><p>Your message was sent!</p>
			</div><!--/msg-sent-->
	        <div class="scroll">
	        	<div class="f-line"></div>
	        	<div class="text">
	        		<p>Did you find a glitch? That happens from time to time. Please explain and we will address it ASAP…</p>
	        		<textarea id="bug-textarea" class="area-input secure" placeholder="Remember, be nice!" cols="30" rows="5"></textarea>
	        		<div id="submit-bug">Report a Bug</div>
		        </div><!--/text-->
		    </div><!--/scroll-->
	    </div><!--/bug-->

    </div><!--/jqt-->
</body>

<!-- JS -->
<script type="text/javascript" src="http://srvdme.com/wp-content/themes/Starkers/js/zepto.min.js"></script>
<script type="text/javascript" src="http://srvdme.com/wp-content/themes/Starkers/js/jqt.min.js"></script>
<script type="text/javascript" src="http://srvdme.com/wp-content/themes/Starkers/js/iscroll.min.js"></script>
<script type="text/javascript" src="http://srvdme.com/wp-content/themes/Starkers/js/swiper.min.js"></script>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript" src="http://srvdme.com/wp-content/themes/Starkers/js/my-home.js?999345"></script>

</html>

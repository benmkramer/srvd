<?php /* Template Name: Success */ ?>

<?php
	global $current_user;
	//$last_visited = (get_user_meta(get_current_user_id(),'admin_color',true));
	$pickup_area = get_user_meta( $last_visited, 'comment_shortcuts', true );
	//$phone = $current_user->user_login;
	//$fname = $current_user->user_firstname;
?>

<div id="success">
	<div class="text-box">
		<p id="green">Order Approved!</p>
		<p id="directions">We will text you when your order is ready. Pick up your drinks at <span id="location"><?php echo $pickup_area ?></span>!</p>
		<img id="responsibly" src="http://cdn.flowtab.mobi/img/responsibly.png">
	</div><!--/text-box-->
</div><!--/success-->

<div id="auth">
	<div class="text-box">
		<p id="green">Card Saved!</p>
		<p id="directions">Your new card has been successfully saved.</p>
	</div><!--/text-box-->
</div><!--/auth-->

<script type="text/javascript">successPage();</script>
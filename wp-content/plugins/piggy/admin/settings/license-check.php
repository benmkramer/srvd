<?php if ( piggy_has_proper_auth() ) { ?>
	<?php if ( piggy_has_license() ) { ?>
	<p class="license-valid round-3"><span><?php _e( 'License accepted, thank you for supporting Piggy!', 'piggy' ); ?></span></p>	
	<?php } else { ?>
	<p class="license-partial round-3"><span><?php echo sprintf( __( 'Your Account and License Key have been accepted. <br />Next, %sconnect a site license%s to this domain to enable support and automatic upgrades.', 'piggy' ), '<a href="#pane-5" class="configure-licenses">', '</a>' ); ?></span></p>
	<?php } ?>
<?php } else { ?>
	<?php if ( piggy_credentials_invalid() ) { ?>
		<?php if ( piggy_was_username_invalid() ) { ?>
		<p class="license-invalid bncid-failed round-3"><span><?php echo __( 'The Account E-Mail you have entered is invalid. Please try again.', 'piggy' ); ?></span></p>	
		<?php } else if ( piggy_user_has_no_license() ) { ?>
		<p class="license-invalid bncid-failed round-3"><span><?php echo __( 'The Account E-Mail you have entered is not associated with a valid license.  Please check your Account E-Mail and try again.', 'piggy' ); ?></span></p>			
		<?php } else { ?>
		<p class="license-invalid bncid-failed round-3"><span><?php echo __( 'This Account/License Key combination you have entered was rejected by the BraveNewCode server. Please try again.', 'piggy' ); ?></span></p>	
		<?php } ?>
	<?php } else { ?>
		<p class="license-invalid round-3"><span><?php echo sprintf( __( 'Please enter your Account E-Mail and License Key to begin the license activation process, or %spurchase a license &raquo;%s', 'piggy' ), '<a href="http://www.bravenewcode.com/products/piggy/">', '</a>' ); ?></span></p>
	<?php } ?>
<?php } ?>

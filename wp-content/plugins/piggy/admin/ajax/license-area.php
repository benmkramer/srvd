<div id="piggy-admin-profile">
	<?php if ( piggy_has_site_licenses() ) { ?>
		<p><?php _e( "You have activated these sites for automatic upgrades & support:", "piggy" ); ?></p>
		<ol class="round-3">
			<?php while ( piggy_has_site_licenses() ) { ?>
				<?php piggy_the_site_license(); ?>
				<li <?php if ( piggy_can_delete_site_license() ) { echo 'class="green-text"'; } ?>>
					<?php piggy_the_site_license_name(); ?> <?php if ( piggy_can_delete_site_license() ) { ?><a class="piggy-remove-license" href="#" rel="<?php piggy_the_site_license_name(); ?>" title="<?php _e( "Remove license?", "piggy" ); ?>">(x)</a><?php } ?></li>
			<?php } ?>
		</ol>
	<?php } ?>
	<!-- end site licenses -->
		
	<?php if ( piggy_get_site_licenses_remaining() != BNC_WPTOUCH_UNLIMITED ) { ?>
		<?php echo sprintf( __( "%s%d%s license(s) remaining.", "piggy" ), '<strong>', piggy_get_site_licenses_remaining(), '</strong>' ); ?>
		
		<?php if ( !piggy_get_site_licenses_remaining() ) { ?>
		<br />
		 	<p class="inline-button">
		 	<a href="http://www.bravenewcode.com/store/plugins/piggy/?utm_source=piggy&utm_medium=web&utm_campaign=admin-upgrades" id="upgrade-license" class="button round-24" target="_blank"><?php _e( "Purchase More Licenses", "piggy" ); ?></a>
		 	</p>
		<?php } ?>
	<?php } ?>

	<?php if ( piggy_get_site_licenses_remaining() ) { ?>
		<?php if ( !piggy_is_licensed_site() ) { ?>
			<?php _e( "You have not activated a license for this WordPress installation.", "piggy" ); ?><br />
			<p class="inline-button">
				<a class="piggy-add-license round-24 button" id="partial-activation" href="#"><?php _e( "Activate This WordPress installation &raquo;", "piggy" ); ?></a>
			</p>
		<?php } ?>
	<?php } ?>

	<?php if ( piggy_get_site_licenses_in_use() ) { ?>
		<?php if ( piggy_can_do_license_reset() && false ) { ?>
			<p class="inline-button">
				<a href="#" id="reset-licenses" class="button"><?php _e( "Reset Licenses Now", "piggy" ); ?></a>
			</p>
			<?php if ( false ) { ?>
			<br /><br />
			<small>
				<?php echo sprintf( __( "* You can reset all support and auto-upgrade licenses once every %d days.", "piggy" ), piggy_get_license_reset_days() ); ?>
			</small>
			<?php } ?>
		<?php } else { ?>
			<?php if ( false ) { ?>
			<br /><br />
			<small>
				<?php echo sprintf( __( "You will be able to reset licenses again in %d day(s).", "piggy" ), piggy_get_license_reset_days_until() ); ?>
			</small>
			<?php } ?>
		<?php } ?>	
	<?php } ?>

	<br class="clearer" />
</div>

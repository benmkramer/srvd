<?php $keys = 0; ?>
<?php $settings = piggy_get_settings(); ?>

<div class="howl-api-key-settings">
	<?php if ( piggy_howl_has_info() ) { ?>
		<?php for ( $i = 0; $i < count( $settings->howl_usernames ); $i++ ) { ?>
		<div class="howl-setting">
			<input type="text" autocomplete="off" class="text howl-user" id="<?php echo 'howl_username_' . $keys; ?>" name="<?php echo 'howl_username_' . $keys; ?>" value="<?php echo $settings->howl_usernames[ $i ]; ?>" />
			<label class="text" for="<?php echo 'howl_username_' . $keys; ?>"><?php _e( 'Username', 'piggy' ); ?></label>	
			<input type="password" autocomplete="off" class="text howl" id="<?php echo 'howl_password_' . $keys; ?>" name="<?php echo 'howl_password_' . $keys; ?>" value="<?php echo $settings->howl_passwords[ $i ]; ?>" />
			<label class="text" for="<?php echo 'howl_password_' . $keys; ?>"><?php _e( 'Password', 'piggy' ); ?></label>
			
			<a href="#" class="add-howl">+</a>
			<a href="#" class="remove-howl">-</a>
			<br />		
			<?php $keys++; ?>
		</div>
		<?php } ?>
	<?php } else { ?>
		<div class="howl-setting">
			<input type="text" autocomplete="off" class="text howl-user" id="<?php echo 'howl_username_' . $keys; ?>" name="<?php echo 'howl_username_' . $keys; ?>" value="" />
			<label class="text" for="<?php echo 'howl_username_' . $keys; ?>"><?php _e( 'Username', 'piggy' ); ?></label>	
			<input type="password" autocomplete="off" class="text howl" id="<?php echo 'howl_password_' . $keys; ?>" name="<?php echo 'howl_password_' . $keys; ?>" value="" />
			<label class="text" for="<?php echo 'howl_password_' . $keys; ?>"><?php _e( 'Password', 'piggy' ); ?></label>			
			
			<a href="#" class="add-howl">+</a>
			<a href="#" class="remove-howl">-</a>
			<br />		
			<?php $keys++; ?>
		</div>	
	<?php } ?>
	
	<div id="add-howl-setting-area">
	</div>
</div>
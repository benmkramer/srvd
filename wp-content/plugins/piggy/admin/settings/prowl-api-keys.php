<?php $keys = 0; ?>
<?php $settings = piggy_get_settings(); ?>

<div class="prowl-api-key-settings">
	<?php if ( piggy_prowl_has_api_keys() ) { ?>
		<?php foreach( $settings->prowl_api_keys as $key => $value ) { ?>
		<div class="prowl-setting">
			<input type="text" autocomplete="off" class="text prowl" id="<?php echo piggy_get_tab_setting_name() . '_' . $keys; ?>" name="<?php echo piggy_get_tab_setting_name() . '_' . $keys; ?>" value="<?php echo $settings->prowl_api_keys[ $key ]; ?>" />
			<label class="text" for="<?php echo piggy_the_tab_setting_name() . '_' . $keys; ?>"><?php _e( 'Prowl API Key', 'piggy' ); ?></label>	
			<a href="#" class="add-prowl">+</a>
			<a href="#" class="remove-prowl">-</a>
			<br />		
			<?php $keys++; ?>
		</div>
		<?php } ?>
	<?php } else { ?>
		<div class="prowl-setting">
			<input type="text" autocomplete="off" class="text prowl" id="<?php echo piggy_get_tab_setting_name() . '_' . $keys; ?>" name="<?php echo piggy_get_tab_setting_name() . '_' . $keys; ?>" value="" />
			<label class="text" for="<?php echo piggy_the_tab_setting_name() . '_' . $keys; ?>"><?php _e( 'Prowl API Key', 'piggy' ); ?></label>	
			<a href="#" class="add-prowl">+</a>
			<a href="#" class="remove-prowl">-</a>
			<br />		
			<?php $keys++; ?>
		</div>	
	<?php } ?>
	
	<div id="add-prowl-setting-area">
	</div>
</div>
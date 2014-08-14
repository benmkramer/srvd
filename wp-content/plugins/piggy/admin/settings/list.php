<select name="<?php piggy_the_tab_setting_name(); ?>" id="<?php piggy_the_tab_setting_name(); ?>" class="list">
	<?php while ( piggy_tab_setting_has_options() ) { ?>
		<?php piggy_tab_setting_the_option(); ?>
		
		<option value="<?php piggy_tab_setting_the_option_key(); ?>"<?php if ( piggy_tab_setting_is_selected() ) echo " selected"; ?>><?php piggy_tab_setting_the_option_desc(); ?></option>
	<?php } ?>
</select>

<label class="list" for="<?php piggy_the_tab_setting_name(); ?>">
	<?php piggy_the_tab_setting_desc(); ?>	
</label>
<?php if ( piggy_the_tab_setting_has_tooltip() ) { ?>
<a href="#" class="piggy-tooltip" title="<?php piggy_the_tab_setting_tooltip(); ?>">?</a>	
<?php } ?>
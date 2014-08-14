<input type="password" autocomplete="off" class="text" id="<?php piggy_the_tab_setting_name(); ?>" name="<?php piggy_the_tab_setting_name(); ?>" value="<?php piggy_the_tab_setting_value(); ?>" />
<label class="text" for="<?php piggy_the_tab_setting_name(); ?>">
	<?php piggy_the_tab_setting_desc(); ?>
</label>			
<?php if ( piggy_the_tab_setting_has_tooltip() ) { ?>
<a href="#" class="piggy-tooltip" title="<?php piggy_the_tab_setting_tooltip(); ?>">?</a> 
<?php } ?>
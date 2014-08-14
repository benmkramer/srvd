<input type="checkbox" class="checkbox" name="<?php piggy_the_tab_setting_name(); ?>" id="<?php piggy_the_tab_setting_name(); ?>"<?php if ( piggy_the_tab_setting_is_checked() ) echo " checked"; ?> />	
<label class="checkbox" for="<?php piggy_the_tab_setting_name(); ?>">
	<?php piggy_the_tab_setting_desc(); ?>
	
	<?php if ( piggy_the_tab_setting_has_tooltip() ) { ?>
	<a href="#" class="piggy-tooltip" title="<?php piggy_the_tab_setting_tooltip(); ?>">?</a>
	<?php } ?>
</label>			
<input type="hidden" name="<?php piggy_the_tab_setting_name(); ?>-hidden" />

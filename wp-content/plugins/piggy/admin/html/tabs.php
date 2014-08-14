<?php while ( piggy_has_tabs() ) { ?>
	<?php piggy_the_tab(); ?>
	
	<div id="pane-content-pane-<?php piggy_the_tab_id(); ?>" class="pane-content" style="display: none;">
		<div class="left-area">
			<ul>
				<?php while ( piggy_has_tab_sections() ) { ?>
					<?php piggy_the_tab_section(); ?>
					<li><a id="tab-section-<?php piggy_the_tab_section_class_name(); ?>" rel="<?php piggy_the_tab_section_class_name(); ?>" href="#"><?php piggy_the_tab_name(); ?></a></li>
				<?php } ?>
			</ul>
		</div>
		<div class="right-area">
			<?php piggy_rewind_tab_settings(); ?>

			
			<?php while ( piggy_has_tab_sections() ) { ?>
				<?php piggy_the_tab_section(); ?>

				<div style="display: none;" class="setting-right-section" id="setting-<?php piggy_the_tab_section_class_name(); ?>">
					<?php while ( piggy_has_tab_section_settings() ) { ?>
						<?php piggy_the_tab_section_setting(); ?>

						<div class="piggy-setting type-<?php piggy_the_tab_setting_type(); ?><?php if ( piggy_tab_setting_has_tags() ) echo ' ' . piggy_tab_setting_the_tags(); ?>"<?php if ( piggy_get_tab_setting_class_name() ) echo ' id="setting_' .  piggy_get_tab_setting_class_name() . '"'; ?>>
							
							<?php if ( file_exists( PIGGY_DIR . '/admin/settings/' . piggy_get_tab_setting_type() . '.php' ) ) { ?>
								<?php include( PIGGY_DIR . '/admin/settings/' . piggy_get_tab_setting_type() . '.php' ); ?>
							<?php } else { ?>
								<?php do_action( 'piggy_show_custom_setting', piggy_get_tab_setting_type() ); ?>
							<?php } ?>
						</div>
					<?php } ?>
				</div>				
			<?php } ?>	
			
			
			<br class="clearer" />		
		</div>
		<br class="clearer" />
	</div>
	
<?php } ?>

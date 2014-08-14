<?php 
	global $piggy; 
	$piggy->setup_date_time();
	$settings = piggy_get_settings();
?>

<div class='piggy-setting' id='touchboard'>
	<?php if ( !get_option( 'permalink_structure' ) ) { ?>
	<div id="permalink-warning" class="round-3">
		<p><strong><?php _e( "Warning", "piggy" ); ?>:</strong> <?php _e( "Pretty Permalinks have not been set for this website.", "piggy" ); ?></p>
		<p><?php _e( "They must be enabled to access Piggy.", "piggy" ); ?> <a target="_blank" href="http://codex.wordpress.org/Introduction_to_Blogging#Pretty_Permalinks"><?php _e( "Learn More", "piggy" ); ?> &raquo;</a></p>
	</div>
	<?php } ?>

	<div class="box-holder round-3" id="right-now-box">

		<h3><?php _e( "Right Now", "piggy" ); ?></h3>

		<p class="sub"><?php _e( "Sales at a Glance", "piggy" ); ?></p>

		<table class="fonty">
			<tbody>
				<tr>
					<td class="box-table-text"><?php _e( "This Week", "piggy" ); ?></td>
					<td class="box-table-number">$<?php echo number_format( piggy_get_bloginfo( 'sales-this-week' ), 2 ); ?></td>
				</tr>	
				<tr>
					<td class="box-table-text"><?php _e( "This Month", "piggy" ); ?></td>
					<td class="box-table-number">$<?php echo number_format( piggy_get_bloginfo( 'sales-this-month' ), 2 ); ?></td>
				</tr>			
				<tr>
					<td class="box-table-text"><?php _e( "This Year", "piggy" ); ?></td>
					<td class="box-table-number">$<?php echo number_format( piggy_get_bloginfo( 'sales-this-year' ), 2 ); ?></td>
				</tr>								
				<tr>
					<td class="box-table-text"><?php _e( "All Time", "piggy" ); ?></td>
					<td class="box-table-number">$<?php echo number_format( piggy_get_bloginfo( 'sales-all-time' ), 2 ); ?></td>
				</tr>	
			</tbody>
		</table>

		<div id="touchboard-ajax"></div>
		
	</div><!-- box-holder -->

	<div class="box-holder loading round-3" id="blog-news-box">
		<h3><?php _e( "Piggy News", "piggy" ); ?></h3>

		<p class="sub"><?php _e( "From the BraveNewCode Blog", "piggy" ); ?></p>

		<div id="blog-news-box-ajax"></div>

	</div><!-- box-holder -->

</div><!-- piggy-setting -->
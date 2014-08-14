<ul class="main-list">
	<?php piggy_populate_projected_data(); ?>
	<?php while ( piggy_has_data() ) { ?>
		<?php piggy_the_data(); ?>		
		<li class="<?php piggy_the_data_increase_or_decrease_class(); ?>">
			<?php piggy_the_data_title(); ?>
			<span class="stat-info"><?php echo sprintf( __( '%s sales', 'piggy' ), number_format( piggy_get_data_sales() ) ); ?></span>
			<span class="stat-total"><?php piggy_the_currency_symbol(); ?><?php echo number_format( piggy_get_data_value(), 2 ); ?></span>
		</li>		
	<?php } ?>					
</ul>

<?php piggy_populate_projected_sales_data(); ?>
<?php if ( piggy_has_data() ) { ?>
	<p class="list-title"><?php _e( 'Best Sellers', "piggy" ); ?></p>
	<ul class="main-list">			
	<?php while ( piggy_has_data() ) { ?>
		<?php piggy_the_data(); ?>		
		<li class="<?php piggy_the_data_increase_or_decrease_class(); ?>">
			<?php piggy_the_data_title(); ?>
			<span class="stat-info"><?php piggy_the_data_product_name(); ?></span>
			<span class="stat-total"><?php piggy_the_currency_symbol(); ?><?php echo number_format( piggy_get_data_value(), 2 ); ?></span>
		</li>		
	<?php } ?>								
	</ul>
<?php } ?>
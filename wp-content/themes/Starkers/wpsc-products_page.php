<?php
global $wp_query;
?>
<div id="default_products_page_container" class="wrap wpsc_container">
	<?php if(wpsc_display_products()): ?>
		<div class="wpsc_default_product_list">
			<?php while (wpsc_have_products()) :  wpsc_the_product(); ?>	
				<div class="default_product_display product_view_<?php echo wpsc_the_product_id(); ?> <?php echo wpsc_category_class(); ?> group">   
					<div class="prodtitle entry-title">
						<div class="wpsc_product_title">
							<?php
								$prod_name = wpsc_the_product_title();
								$limit = 25;
								if ( strlen($prod_name) > $limit ) :
									echo trim(substr($prod_name, 0, $limit)) . '…';
								else :
									echo $prod_name;
								endif;
							?>
						</div>
					</div>
					<div class="imagecol" id="imagecol_<?php echo wpsc_the_product_id(); ?>"></div>
						<div class="productcol">
							<?php					
								do_action('wpsc_product_before_description', wpsc_the_product_id(), $wp_query->post);
								do_action('wpsc_product_addons', wpsc_the_product_id());
							?>
							<?php if(wpsc_the_product_additional_description()) : ?>
							<div class="additional_description_container">
								<div class="additional_description">
									<p><?php echo wpsc_the_product_additional_description(); ?></p>
								</div><!--close additional_description-->
							</div><!--close additional_description_container-->
							<?php endif; ?>
							<?php if(wpsc_product_external_link(wpsc_the_product_id()) != '') : ?>
								<?php $action =  wpsc_product_external_link(wpsc_the_product_id()); ?>
							<?php else: ?>
							<?php $action = htmlentities(wpsc_this_page_url(), ENT_QUOTES, 'UTF-8' ); ?>					
							<?php endif; ?>					
							<form class="product_form"  enctype="multipart/form-data" action="<?php echo $action; ?>" method="post" name="product_<?php echo wpsc_the_product_id(); ?>" id="product_<?php echo wpsc_the_product_id(); ?>" >
							<?php do_action ( 'wpsc_product_form_fields_begin' ); ?>
	                        <?php if (wpsc_have_variation_groups()) { ?>
								<div id="variations" class="wpsc_variation_forms">
									<?php while (wpsc_have_variation_groups()) : wpsc_the_variation_group(); ?>
										<select id="variations-select" name="variation[<?php echo wpsc_vargrp_id();?>]" class="select" id="<?php echo wpsc_vargrp_form_id();?>">								<?php while (wpsc_have_variations()) : wpsc_the_variation(); ?>
											<?php if (wpsc_the_variation_id() != 0) { ?>
												<option value="<?php echo wpsc_the_variation_id();?>">
													<?php // echo wpsc_the_variation_name(); ?>
													<?php echo wpsc_the_variation_name() ?>											
												</option>
											<?php } ?>	
										<?php endwhile; ?>
										</select>						
									<?php endwhile; ?>
								</div><!--/variations-->
							<?php } ?>
								<div class="wpsc_product_price">
										<!--Price Display-->
										<p class="pricedisplay product_<?php echo wpsc_the_product_id(); ?>">
											<?php if ( wpsc_product_on_special() ) : ?>
												<span class="happy-price">
											<?php endif;?>
											<?php if ( wpsc_product_on_special() ) { ?>
												<span id="product_price_<?php echo wpsc_the_product_id();?>" class="currentprice pricedisplay">
													<?php echo wpsc_the_product_price() . ' (Happy Hour!)';?>
												</span>
											<?php } else { ?>
												<span id="product_price_<?php echo wpsc_the_product_id();?>" class="currentprice pricedisplay">
													<?php echo wpsc_product_normal_price(); ?>
												</span>
											<?php } ?>
											<?php if ( wpsc_product_on_special() ) : ?>
												<?php // echo '(' . number_format( wpsc_you_save(), 0 ) . '% Off)' ?>
												</span>
											<?php endif;?>
										</p><!--/pricedisplay-->
								</div>
							<div class="wpsc_description">
								<?php echo wpsc_the_product_description(); ?>
	                        </div>
								<input type="hidden" value="add_to_cart" name="wpsc_ajax_action"/>
								<input type="hidden" value="<?php echo wpsc_the_product_id(); ?>" name="product_id"/>
								<!-- END OF QUANTITY OPTION -->
								<?php if((get_option('hide_addtocart_button') == 0) &&  (get_option('addtocart_or_buynow') !='1')) : ?>
									<?php if(wpsc_product_has_stock()) : ?>
										<div class="wpsc_buy_button_container">	
											<input onclick="addDrink('<?php echo '.product_view_' . wpsc_the_product_id(); ?>')" type="submit" value="<?php _e('', 'wpsc'); ?>" name="Buy" class="wpsc_buy_button touch" id="product_<?php echo wpsc_the_product_id(); ?>_submit_button"/>
										</div>
									<?php endif ; ?>
								<?php endif ; ?>
								<?php do_action ( 'wpsc_product_form_fields_end' ); ?>
							</form>
							<?php if((get_option('hide_addtocart_button') == 0) && (get_option('addtocart_or_buynow')=='1')) : ?>
								<?php echo wpsc_buy_now_button(wpsc_the_product_id()); ?>
							<?php endif ; ?>
							<?php echo wpsc_product_rater(); ?>
						<?php // */ ?>
					</div><!--close productcol-->
			</div><!--close default_product_display-->
			<?php endwhile; ?>
		</div><!--/wpsc_default_product_list-->
		<?php if(wpsc_product_count() == 0):?>
			<div class="oops-wrap">
				<div class="oops-img"></div>
			</div><!--oops-wrap-->
			<p class="oops-text">This category is empty.</p>
		<?php endif ; ?>
	<?php endif; ?>
</div><!--close default_products_page_container-->

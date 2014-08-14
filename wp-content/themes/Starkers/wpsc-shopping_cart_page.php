<?php
	global $wpsc_cart, $wpdb, $wpsc_checkout, $wpsc_gateway, $wpsc_coupons;
	$wpsc_checkout = new wpsc_checkout();
	$wpsc_gateway = new wpsc_gateways();
	$alt = 0;
	if(isset($_SESSION['coupon_numbers']))
	$wpsc_coupons = new wpsc_coupons($_SESSION['coupon_numbers']);
	if(wpsc_cart_item_count() < 1) : _e('<div id="empty-wrap"><div id="empty-img"></div></div><p id="empty-text">Your cart is empty</p>', 'wpsc')."<a href=".get_option("product_list_url").">" . __('', 'wpsc') . "</a>";
	return;
	endif;
?>

<div id="checkout_cart_holder">
<div id="checkout-div"></div>
<div id="checkout_page_container">
	<table class="checkout_cart">
		<div class="header">
			<div class="checkout-header1">Qty</div>
			<div class="checkout-header2">Product</div>
			<div class="checkout-header3">Price</div>
			<div class="checkout-header4">Total</div>
		</div>
		<?php $set_prod_auth = false; ?>
		<?php while (wpsc_have_cart_items()) : wpsc_the_cart_item(); ?>
			<?php $alt++; if ($alt %2 == 1) $alt_class = 'alt'; else $alt_class = ''; ?>
			<tr class="product_row product_row_<?php echo wpsc_the_cart_item_key(); ?><?php echo $alt_class;?>">
				<td class="wpsc_product_name wpsc_product_name_<?php echo wpsc_the_cart_item_key(); ?>">	
					
					<?php 

					$menu_item = wpsc_cart_item_name();
					$limit = 22;
					if ( strlen($menu_item) > $limit ) :
						echo trim(substr($menu_item, 0, $limit)) . '…';
					else :
						echo $menu_item;
					endif;
					
					?>
					
					<?php //echo "(". wpsc_cart_item_product_id() . ")" ;
					if ( ! $set_prod_auth ) {
						$get_prod_auth = wpsc_cart_item_product_author();
						if ($get_prod_auth) {
							$set_prod_auth = true;
							// 	echo "(". $get_prod_auth . ")";   // just for testing
						}
					}
					?>
				</td>
				<td class="wpsc_product_quantity wpsc_product_quantity_<?php echo wpsc_the_cart_item_key(); ?>">
					<form action="/checkout" method="post" class="adjustform qty">      
						<input type="hidden" name="quantity" value="<?php echo wpsc_cart_item_quantity()-1; ?>" />
						<input type="hidden" name="key" value="<?php echo wpsc_the_cart_item_key(); ?>" />
						<input type="hidden" name="wpsc_update_quantity" value="true" />
						<input class="update-minus" type="submit" value="" name="submit-minus"  <?php if (wpsc_cart_item_quantity() <1) echo 'disabled="disabled"'; ?> />
					</form>
				</td>
				<td class="wpsc_product_quantity wpsc_product_quantity_<?php echo wpsc_the_cart_item_key(); ?>">
					<form action="/checkout" method="post" class="adjustform qty">    
						<span class="qty"><?php echo wpsc_cart_item_quantity(); ?></span>
						<input type="hidden" name="quantity" value="<?php echo wpsc_cart_item_quantity()+1; ?>" />
						<input type="hidden" name="key" value="<?php echo wpsc_the_cart_item_key(); ?>" />
						<input type="hidden" name="wpsc_update_quantity" value="true" />
						<input class="update-plus" type="submit" value=""   name="submit" />
					</form>
				</td>
				<td><span class="single-price"><?php echo wpsc_cart_single_item_price(); ?></span></td>
				<td><span class="group-price"><?php echo wpsc_cart_item_price(); ?></span></td>
				<div class="remove">
					<td class="wpsc_product_remove wpsc_product_remove_<?php echo wpsc_the_cart_item_key(); ?>">		
						<form action="/checkout" method="post" class="adjustform remove">
							<input type="hidden" name="quantity" value="0" />
							<input type="hidden" name="key" value="<?php echo wpsc_the_cart_item_key(); ?>" />
							<input type="hidden" name="wpsc_update_quantity" value="true" />
							<input class="x-btn" type="submit" value="<?php _e('', 'wpsc'); ?>" name="submit" />
						</form>
					</td>
				</div><!--/remove-->
			</tr><!--/product_row-->
		<?php endwhile; ?>
		<?php if(wpsc_uses_coupons()): ?>
			<?php if(wpsc_coupons_error()): ?>
				<!--<tr class="wpsc_coupon_row wpsc_coupon_error_row">
					<td><?php // _e('<script>alert("Promotion Code is Invalid.")</script>', 'wpsc'); ?></td>
				</tr>-->
			<?php endif; ?>
			<tr class="wpsc_coupon_row">
				<td  colspan="4" class="coupon_code">
					<form  method="post" action="/checkout">
						<input type="text" name="coupon_num" placeholder="Promotional Code" id="coupon_num" value="<?php echo $wpsc_cart->coupons_name; ?>" />
						<input class="update" type="submit" value="<?php _e('', 'wpsc') ?>" />
					</form>
				</td>
			</tr>
			<!-- <input type="text" name="seating" placeholder="Table Number" id="seating" value="" width="30"/> -->
			<tr class="wpsc_total_before_shipping">
				<td colspan="3" class="wpsc_total_amount_before_shipping"><?php echo wpsc_cart_total_widget(false,false,false);?></td>
			</tr>
		<?php endif; ?>
	</table>

	<?php if(wpsc_uses_shipping()): ?>
		<p class="wpsc_cost_before"></p>
	<?php endif; ?>
	<?php if(wpsc_has_category_and_country_conflict()): ?>
		<p class='validation-error'><?php echo $_SESSION['categoryAndShippingCountryConflict']; ?></p>
	<?php unset($_SESSION['categoryAndShippingCountryConflict']);
	endif;
	if(isset($_SESSION['WpscGatewayErrorMessage']) && $_SESSION['WpscGatewayErrorMessage'] != '') :?>
		<p class="validation-error"><?php echo $_SESSION['WpscGatewayErrorMessage']; ?></p>
	<?php endif; ?>
	<?php do_action('wpsc_before_shipping_of_shopping_cart'); ?>
	<div id="wpsc_shopping_cart_container">
		<div id="discount-wrap">
			<div id="subtotal-label">Sub-Total:</div>
			<div id="subtotal-amount"><?php echo '$' . number_format($wpsc_cart->calculate_subtotal(), 2); ?></div>
		</div>
	<?php if(wpsc_uses_coupons() && (wpsc_coupon_amount(false) > 0)): ?> 
		<div id="discount-wrap">
			<div id="discount-label">Discount:</div>
			<div id="discount-amount"><?php echo wpsc_coupon_amount(); ?></div>
		</div>
	<?php endif ?>
	<div id="tax-wrap">
		<div id="gratuity-label">Tax:</div>
		<div id="tax-amount"><?php echo wpsc_cart_tax(); ?></div>
	</div><!--/tax-wrap-->
	<div id="gratuity-wrap">
		<div id="gratuity-label"> Gratuity: 
			<div id="gratuity-minus"></div>
			<span id="gratuity-value">20</span>%
			<div id="gratuity-plus"></div>
		</div><!--/gratuity-label-->
		<div id="gratuity-amount">
			<?php $gratuity_percent = 0.2;  // 20% default value
				$gratuity_value = round( $gratuity_percent * $wpsc_cart->calculate_subtotal(), 2); 	// formatting to x.yy
				echo wpsc_currency_display($gratuity_value);	//"$". number_format($gratuity_value, 2);
				echo "<script type='text/javascript'> gratuity_value=". $gratuity_value . "; cart_subtotal=".$wpsc_cart->calculate_subtotal() . "; cart_discount=". wpsc_coupon_amount(false) .";tax_total=".wpsc_cart_tax(false)."</script>";?>
		</div><!--/gratuity-amount-->
	</div><!--/gratuity-wrap-->
	<?php do_action('wpsc_before_form_of_shopping_cart'); ?>
	<?php if(!empty($_SESSION['wpsc_checkout_user_error_messages'])): ?>
		<p class="validation-error">
		<?php foreach($_SESSION['wpsc_checkout_user_error_messages'] as $user_error ) echo $user_error."<br />\n";
			$_SESSION['wpsc_checkout_user_error_messages'] = array();
		?>
	<?php endif; ?>
	<form class="wpsc_checkout_forms" action="/checkout" method="post" enctype="multipart/form-data">
		<?php if(!empty($_SESSION['wpsc_checkout_misc_error_messages'])): ?>
			<div class="login_error">
				<?php foreach((array)$_SESSION['wpsc_checkout_misc_error_messages'] as $user_error ){?>
					<script>alert('Oops, there appears to be a problem with your card.')</script>
				<?php } ?>
			</div>
		<?php endif;
		$_SESSION['wpsc_checkout_misc_error_messages'] = array(); ?><?php ob_start(); ?>
		<?php if(wpsc_uses_shipping()) : ?> 
		<div id="processing-wrap">
			<div id="processing-label">Processing:</div>
			<div id="processing-amount"><?php echo wpsc_cart_shipping(); ?></div>
		</div>
		<?php endif; ?> 
		<div class="line"></div>
		<div id="total-wrap">
			<div id="total-label">Total:</div>
			<?php $tax_value = wpsc_cart_tax(false) ?>
			<div id="total-amount"><?php echo wpsc_currency_display( wpsc_cart_total(false)+$gratuity_value);?></div>
		</div>
		<div id="delivery-wrapper">
			<div id="table-area">
				<span id="seating-text">I am sitting at table number</span>
				<input id="seating" name="seating" placeholder="##" maxlength="2" value="" type="tel" size="2"/>
			</div>
			<div id="bar-btn" class="bar-clicked"></div>
			<div id="table-btn" class="contact"></div>
		</div><!--/delivery-wrapper-->
		
		<table class="wpsc_checkout_table table-1">
			<?php $i = 0; while (wpsc_have_checkout_items()) : wpsc_the_checkout_item(); ?>
				<?php if(wpsc_checkout_form_is_header() == true){ $i++;?>
					<?php if($i > 1):?>
				<?php endif; ?><!-- This might move down two spaces -->
		</table>
		<table class="wpsc_checkout_table table-<?php echo $i; ?>">  
			<tr <?php echo wpsc_the_checkout_item_error_class();?>>
			  <td <?php wpsc_the_checkout_details_class(); ?> colspan='2'></td>
			</tr>
			<?php if(wpsc_is_shipping_details()):?>
			<?php endif; } elseif(wpsc_disregard_shipping_state_fields()){ ?>
				<tr class='wpsc_hidden'>
					<td class='<?php echo wpsc_checkout_form_element_id(); ?>'>
						<label for='<?php echo wpsc_checkout_form_element_id(); ?>'>
							<?php echo wpsc_checkout_form_name();?>
						</label>
					</td>
					<td>
						<?php echo wpsc_checkout_form_field();?>
						<?php if(wpsc_the_checkout_item_error() != ''): ?>
							<p class='validation-error'><?php echo wpsc_the_checkout_item_error(); ?></p>
						<?php endif; ?>
					</td>
				</tr>
            <?php } elseif(wpsc_disregard_billing_state_fields()){ ?>
				<tr class='wpsc_hidden'>
					<td class='<?php echo wpsc_checkout_form_element_id(); ?>'>
						<label for='<?php echo wpsc_checkout_form_element_id(); ?>'>
							<?php echo wpsc_checkout_form_name();?>
						</label>
					</td>
					<td>
						<?php echo wpsc_checkout_form_field();?>
						<?php if(wpsc_the_checkout_item_error() != ''): ?>
							<p class='validation-error'><?php echo wpsc_the_checkout_item_error(); ?></p>
						<?php endif; ?>
					</td>
				</tr>
            <?php } elseif( $wpsc_checkout->checkout_item->unique_name == 'billingemail'){ ?>
				<?php $email_markup = "<div class='wpsc_email_address'>
				<p class='" . wpsc_checkout_form_element_id() . "'>
				<label class='wpsc_email_address' for='" . wpsc_checkout_form_element_id() . "'>
					" . __('Enter your email address', 'wpsc') . "
				</label>
				<p class='wpsc_email_address_p'>" . wpsc_checkout_form_field();
				if(wpsc_the_checkout_item_error() != '')
					$email_markup .= "<p class='validation-error'>" . wpsc_the_checkout_item_error() . "</p>";
					$email_markup .= "</div>";
             	} else { ?>             
					<tr class="extra-fields">
						<td class='<?php echo wpsc_checkout_form_element_id(); ?>'>
							<label for='<?php echo wpsc_checkout_form_element_id(); ?>'>
								<?php echo wpsc_checkout_form_name();?>
							</label>
						</td>
						<td>
							<?php echo wpsc_checkout_form_field();?>
							<?php if(wpsc_the_checkout_item_error() != ''): ?>
								<p class='validation-error'><?php echo wpsc_the_checkout_item_error(); ?></p>
							<?php endif; ?>
						</td>
					</tr>
         		<?php } ?>
      		<?php endwhile; ?> 
			<?php $buffer_contents = ob_get_contents();
				ob_end_clean();
				if(isset($email_markup))
					echo $email_markup;
				echo $buffer_contents;
			?>
			<?php do_action('wpsc_inside_shopping_cart'); ?>
			<?php if(wpsc_gateway_count() > 1): // if we have more than one gateway enabled, offer the user a choice ?>
         		<tr>
         			<td colspan='2' class='wpsc_gateway_container'>
						<?php while (wpsc_have_gateways()) : wpsc_the_gateway(); ?>
							<div class="custom_gateway <?php echo wpsc_gateway_internal_name();?>">
								<div class="option-box">
                 					<label>
                 						<input id="input_<?php echo wpsc_gateway_internal_name();?>"type="checkbox" value="<?php echo wpsc_gateway_internal_name();?>" <?php echo wpsc_gateway_is_checked(); ?> name="custom_gateway" class="custom_gateway"/><?php echo wpsc_gateway_name(); ?>
                 						<div id="check-cash" class=""></div>
									</label>
                    			</div><!--/option-box-->
								<?php if(wpsc_gateway_form_fields()): ?>                  
									<table class='wpsc_checkout_table <?php echo wpsc_gateway_form_field_style();?>'>
										<?php echo wpsc_gateway_form_fields();?>
									</table>
                  				<?php endif; ?>
							</div><!--/custom_gateway-->
						<?php endwhile; ?>
					</td>
				</tr>
			<?php else: // otherwise, stick in a hidden form ?>
            	<tr>
            		<td colspan="2" class='wpsc_gateway_container'>
            			<?php while (wpsc_have_gateways()) : wpsc_the_gateway(); ?>
               				<input id="poop" name='custom_gateway' value='<?php echo wpsc_gateway_internal_name();?>' type='hidden' />
							<?php if(wpsc_gateway_form_fields()): ?>
                     			<table class='wpsc_checkout_table <?php echo wpsc_gateway_form_field_style();?>'>
									<?php echo wpsc_gateway_form_fields();?>
								</table>
                  			<?php endif; ?>
            			<?php endwhile; ?>
         			</td>
         		</tr>
         	<?php endif; ?>
		</table>
		
		<!--/wpsc_checkout_table-->
		<div class="wpsc_make_purchase">
			<?php if(!wpsc_has_tnc()) : ?>
				<input type="hidden" value="yes" name="agree" />
			<?php endif; ?>
			<input type="hidden" value="submit_checkout" name="wpsc_action" />
			<input type="hidden" value="<?php echo $get_prod_auth; ?>" name="engravetext">
			<input type="submit" value="Place Order" id="purchase-it" name="submit" class="make_purchase wpsc_buy_button" />
		</div><!-- wpsc_make_purchase -->   
		<input type="hidden" value="<?php echo $gratuity_value; ?>" name="base_shipping" id="base_shipping"/>    
	</form><!-- wpsc_checkout_forms -->

</div><!--close checkout_page_container-->
</div>

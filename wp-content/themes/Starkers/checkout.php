<?php /* Template Name: Checkout */ ?>
<!DOCTYPE html><html><head>

<?php
	global $wpsc_cart, $wpdb, $wpsc_checkout, $wpsc_gateway, $wpsc_coupons;
	$wpsc_checkout = new wpsc_checkout();
	$wpsc_gateway = new wpsc_gateways();
?>
<div id="checkout_page_container">
	<?php if(wpsc_uses_shipping()): ?>
		<p class="wpsc_cost_before"></p>
	<?php endif; ?>
	<?php if(isset($_SESSION['WpscGatewayErrorMessage']) && $_SESSION['WpscGatewayErrorMessage'] != '') :?>
		<p class="validation-error"><?php echo $_SESSION['WpscGatewayErrorMessage']; ?></p>
	<?php endif; ?>
	<?php do_action('wpsc_before_shipping_of_shopping_cart'); ?>
	<?php if(!empty($_SESSION['wpsc_checkout_user_error_messages'])): ?>
		<p class="validation-error">
		<?php foreach($_SESSION['wpsc_checkout_user_error_messages'] as $user_error ) echo $user_error."<br />\n";
			$_SESSION['wpsc_checkout_user_error_messages'] = array();
		?>
	<?php endif; ?>
	<form id="checkout-form" class="wpsc_checkout_forms" action="/wp-admin/admin-ajax.php?action=checkout" method="post" enctype="multipart/form-data">
		<?php if(!empty($_SESSION['wpsc_checkout_misc_error_messages'])): ?>
			<div class="login_error">
				<?php foreach((array)$_SESSION['wpsc_checkout_misc_error_messages'] as $user_error ){?>
					<script>alert('Oops, there appears to be a problem with your card.')</script>
				<?php } ?>
			</div>
		<?php endif;
		$_SESSION['wpsc_checkout_misc_error_messages'] = array(); ?><?php ob_start(); ?>
		<div id="total-wrap" class="checkout-total">
			<div id="total-label">Total:</div>
			<?php $tax_value = wpsc_cart_tax(false) ?>
			<!-- <div id="total-amount"><?php //echo wpsc_currency_display( wpsc_cart_total(false)+$gratuity_value);?></div> -->
			<div id="total-amount">0.00</div>
		</div><!--/total-wrap-->
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
				<?php endif; ?>
		</table><!--/wpsc_checkout_table-->
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
							<p class="validation-error"><?php echo wpsc_the_checkout_item_error(); ?></p>
						<?php endif; ?>
					</td>
				</tr>
            <?php } elseif( $wpsc_checkout->checkout_item->unique_name == 'billingemail'){ ?>
				<?php $email_markup = "<div class='wpsc_email_address'>
				<p class='".wpsc_checkout_form_element_id()."'>
				<p class='wpsc_email_address_p'>".wpsc_checkout_form_field();
				if(wpsc_the_checkout_item_error() != '')
					$email_markup .= "<p class='validation-error'>" . wpsc_the_checkout_item_error() . "</p>";
					$email_markup .= "</div>";
             	}
             	endwhile; 
				$buffer_contents = ob_get_contents();
				ob_end_clean();
				if(isset($email_markup))
					echo $email_markup;
				echo $buffer_contents;
			?>
			<?php do_action('wpsc_inside_shopping_cart'); ?>
			<?php if(wpsc_gateway_count() > 1): ?>
         		<tr>
         			<td colspan="2" class="wpsc_gateway_container">
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
			<?php else: ?>
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
		</table><!--/wpsc_checkout_table-->
		<div class="wpsc_make_purchase">
			<?php if(!wpsc_has_tnc()) : ?>
				<input type="hidden" value="yes" name="agree" />
			<?php endif; ?>
			<input type="hidden" value="submit_checkout" name="wpsc_action" />
			<input type="hidden" value="" name="engravetext" id="engravetext">
			<input type="hidden" value="" name="base_shipping" id="base_shipping"/>
			<input type="submit" value="Place Order" id="purchase-it" name="submit" class="make_purchase wpsc_buy_button" />
		</div><!-- wpsc_make_purchase -->
	</form><!--/wpsc_checkout_forms -->
</div><!--/checkout_page_container-->

</html>
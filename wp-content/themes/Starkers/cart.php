<?php /* Template Name: Cart */ ?>
<!DOCTYPE html><html><head>

<?php // if (get_user_meta(wpsc_cart_item_product_author(),'aim',true)==0) {} ?>

<?php
	global $wpsc_cart, $wpdb, $wpsc_checkout, $wpsc_gateway, $wpsc_coupons;
	$wpsc_checkout = new wpsc_checkout();
	$wpsc_gateway = new wpsc_gateways();
	$alt = 0;
	if(isset($_SESSION['coupon_numbers']))
	$wpsc_coupons = new wpsc_coupons($_SESSION['coupon_numbers']);
	if(wpsc_cart_item_count() < 1) : _e('<div id="empty-wrap"><div id="empty-img"></div></div><p id="empty-text">Your cart is empty</p><script type="text/javascript">window.parent.cartEmpty();</script>', 'wpsc')."<a href=".get_option("product_list_url").">" . __('', 'wpsc') . "</a>";
	return;
	endif;
?>

<!-- CALL CSS -->
<link type="text/css" rel="stylesheet" href="http://srvdme.com/wp-content/themes/Starkers/css/my-checkout3.css">
<link type="text/css" rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400,700">

</head><body>
	<div id="jqt">
		<div id="cart">
			<div class="f-line"></div>
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
								<?php
									//echo "(". wpsc_cart_item_product_id() . ")" ;
									if ( ! $set_prod_auth ) {
										$get_prod_auth = wpsc_cart_item_product_author();
										if ($get_prod_auth) {
											$set_prod_auth = true;
											// echo "(". $get_prod_auth . ")";
										}
									}
								?>
							</td><!-- wpsc_product_name -->
							<td class="wpsc_product_quantity wpsc_product_quantity_<?php echo wpsc_the_cart_item_key(); ?>">
								<form action="/cart" method="post" class="adjustform qty">      
									<input type="hidden" name="quantity" value="<?php echo wpsc_cart_item_quantity()-1; ?>">
									<input type="hidden" name="key" value="<?php echo wpsc_the_cart_item_key(); ?>">
									<input type="hidden" name="wpsc_update_quantity" value="true">
									<input class="update-minus" type="submit" value="" name="submit-minus" <?php if (wpsc_cart_item_quantity() <1) echo 'disabled="disabled"'; ?>>
								</form><!--/adjustform-->
							</td><!--/wpsc_product_quantity-->
							<span class="qty"><?php echo wpsc_cart_item_quantity(); ?></span>
							<td class="wpsc_product_quantity wpsc_product_quantity_<?php echo wpsc_the_cart_item_key(); ?>">
								<form action="/cart" method="post" class="adjustform qty">
									<input type="hidden" name="quantity" value="<?php echo wpsc_cart_item_quantity()+1; ?>">
									<input type="hidden" name="key" value="<?php echo wpsc_the_cart_item_key(); ?>">
									<input type="hidden" name="wpsc_update_quantity" value="true">
									<input class="update-plus" type="submit" value="" name="submit">
								</form><!--/adjustform-->
							</td><!--/wpsc_product_quantity-->
							<td><span class="single-price"><?php echo wpsc_cart_single_item_price(); ?></span></td>
							<td><span class="group-price"><?php echo wpsc_cart_item_price(); ?></span></td>
							<div class="remove">
								<td class="wpsc_product_remove wpsc_product_remove_<?php echo wpsc_the_cart_item_key(); ?>">		
									<form action="/cart" method="post" class="adjustform remove">
										<input type="hidden" name="quantity" value="0">
										<input type="hidden" name="key" value="<?php echo wpsc_the_cart_item_key(); ?>">
										<input type="hidden" name="wpsc_update_quantity" value="true" />
										<input class="x-btn" type="submit" value="<?php _e('', 'wpsc'); ?>" name="submit" />
									</form><!--/adjustform-->
								</td><!--/wpsc_product_quantity-->
							</div><!--/remove-->
						</tr><!--/product_row-->
					<?php endwhile; ?>
					
					<!-- Invalid Promo Code -->
					
					<?php if(wpsc_uses_coupons()): ?>
						<?php if(wpsc_coupons_error()): ?>
						
							<script type="text/javascript">
								//window.parent.badPromo(); // This needs fixing, shows up every time!!
								//alert("Bad Promo");
							</script>

						<?php endif; ?>
						<tr class="wpsc_coupon_row">
							<td colspan="4" class="coupon_code">
								<form id="coupon-form" method="post" action="/cart">
									<input type="text" name="coupon_num" placeholder="Promotional Code" id="coupon_num">
									<input id="promo-submit" type="submit" value="Redeem" />
									<div id="promo-update">Redeem</div>
								</form><!--/form-->
							</td><!--/coupon-code-->
						</tr><!--/wpsc_coupon_row-->
					<?php endif; ?><!--/uses-coupons-->
					
				</table><!--/checkout-cart-->
				
				<div id="wpsc_shopping_cart_container">
				
					<!-- Sub-Total -->
				
					<div id="subtotal-wrap">
						<div id="subtotal-label">Sub-Total:</div>
						<div id="subtotal-amount"><?php echo '$' . number_format($wpsc_cart->calculate_subtotal(), 2); ?></div>
					</div><!--/subtotal-wrap-->
					
					<!-- Discount -->
					
					<?php if(wpsc_uses_coupons() && (wpsc_coupon_amount(false) > 0)): ?> 
						<div id="discount-wrap">
							<div id="discount-label">Discount:</div>
							<div id="discount-amount"><?php echo wpsc_coupon_amount(); ?></div>
						</div><!--/discount-wrap-->
					<?php endif ?>
					
					<!-- Taxes -->
					
					<div id="tax-wrap">
						<div id="gratuity-label">Service Fee:</div>
						<div id="tax-amount"><?php echo wpsc_cart_tax(); ?></div>
					</div><!--/tax-wrap-->
					
					<!-- Gratuity -->
					
					<div id="gratuity-wrap">
						<div id="gratuity-label"> Gratuity: 
							<div id="gratuity-minus"></div>
							<span id="gratuity-percent">20</span>%
							<div id="gratuity-plus"></div>
						</div><!--/gratuity-label-->
						<div id="gratuity-amount">
							<?php 
								$gratuity_percent = 0.2;  // 20% default value
								$gratuity_value = round( $gratuity_percent * $wpsc_cart->calculate_subtotal(), 2);
								echo wpsc_currency_display($gratuity_value);
							?>
						</div><!--/gratuity-amount-->
					</div><!--/gratuity-wrap-->
				
				<form class="wpsc_checkout_forms" action="/cart" method="post" enctype="multipart/form-data">
					<div class="line"></div>
					
					<!-- Total -->
					
					<div id="total-wrap">
						<div id="total-label">Total:</div>
						<?php $tax_value = wpsc_cart_tax(false) ?>
						<div id="total-amount"><?php echo wpsc_currency_display( wpsc_cart_total(false)+$gratuity_value);?></div>
					</div><!--/total-wrap-->
					
				</form><!--/wpsc_checkout_forms-->

					<!-- Send to JS -->	
					
					<script type="text/javascript">
						var cart_subtotal = <?php echo $wpsc_cart->calculate_subtotal() ?>;
						var cart_discount = <?php echo wpsc_coupon_amount(false) ?>;
						var cart_tax = <?php echo wpsc_cart_tax(false) ?>;
						var cart_percent = <?php echo $gratuity_percent ?>;
						var cart_gratuity = <?php echo $gratuity_value ?>;
						var cart_total = <?php echo (wpsc_cart_total(false)+$gratuity_value);?>;
						window.parent.saveCart(cart_subtotal,cart_discount,cart_tax,cart_percent,cart_gratuity,cart_total);
					</script>
					
					<!-- Tables -->
					
					<div id="delivery-wrapper">
						<div id="table-area">
							<span id="seating-text">I am sitting at table number</span>
							<input id="seating" name="seating" placeholder="##" maxlength="2" value="" type="tel" size="2"/>
						</div><!--/table-area-->
						<div id="bar-btn" class="bar-clicked"></div>
						<div id="table-btn" class="contact"></div>
					</div><!--/delivery-wrapper-->
				
		</div><!--/cart-->
	</div><!--jqt-->
</body>

<!-- CALL JS -->
<script type="text/javascript" src="http://srvdme.com/wp-content/themes/Starkers/js/zepto.min.js"></script>
<script type="text/javascript" src="http://srvdme.com/wp-content/themes/Starkers/js/my-checkout5.js"></script>

</html>

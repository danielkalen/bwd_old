<?php
/**
 * Checkout coupon form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! WC()->cart->coupons_enabled() ) {
	return;
}

$info_message = apply_filters( 'woocommerce_checkout_coupon_message', __( 'Have a coupon?', 'woocommerce' ) . ' <a href="#" class="showcoupon">' . __( 'Click here to enter your code', 'woocommerce' ) . '</a>' );
wc_print_notice( $info_message, 'notice' );
?>

<form class="apply_coupon checkout_coupon" method="post" style="display:none">

	<div class="apply_coupon-fieldset input">
		<label class="apply_coupon-fieldset-label" for="coupon_code">Coupon Code</label>
		<input class="apply_coupon-fieldset-input" type="text" name="coupon_code" class="input-text" placeholder="<?php _e( 'Coupon Code', 'woocommerce' ); ?>" id="coupon_code" value="" />
	</div>

	<div class="apply_coupon-button">
		<div class="apply_coupon-button-text">Apply Coupon</div>
	</div>

	<input type="submit" class="apply_coupon-button-hidden button" name="apply_coupon" value="<?php _e( 'Apply Coupon', 'woocommerce' ); ?>" />
</form>

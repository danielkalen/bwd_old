<?php
/**
 * Checkout shipping information form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
	<?php if ( WC()->cart->needs_shipping_address() === true ) : ?>

		<h3 class="checkout-section-title"><div class="checkout-section-title-text"><?php _e( 'Shipping Address', 'woocommerce' ); ?></div></h3>

		<div class="checkout-section-wrap">

			<?php do_action( 'woocommerce_before_checkout_shipping_form', $checkout ); ?>

			<?php foreach ( $checkout->checkout_fields['shipping'] as $key => $field ) : ?>

				<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>

			<?php endforeach; ?>

			<?php do_action( 'woocommerce_after_checkout_shipping_form', $checkout ); ?>

			<div class="checkout-section-buttons">
				<div class="checkout-section-buttons-item button back"><div class="checkout-section-buttons-item-text">Previous Step</div></div>
				<div class="checkout-section-buttons-item button next"><div class="checkout-section-buttons-item-text">Next Step</div></div>
			</div>

			<div class="clearfix"></div>
		</div>

	<?php endif; ?>



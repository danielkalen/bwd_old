<?php
/**
 * Checkout billing information form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/** @global WC_Checkout $checkout */
?>
	<?php if ( WC()->cart->ship_to_billing_address_only() && WC()->cart->needs_shipping() ) : ?>

		<h3 class="checkout-section-title"><div class="checkout-section-title-text"><?php _e( 'Billing &amp; Shipping', 'woocommerce' ); ?></div></h3>

	<?php else : ?>

		<h3 class="checkout-section-title"><div class="checkout-section-title-text"><?php _e( 'Billing Details', 'woocommerce' ); ?></div></h3>

	<?php endif; ?>

	<div class="checkout-section-wrap">

		<?php do_action( 'woocommerce_before_checkout_billing_form', $checkout ); ?>

		<?php foreach ( $checkout->checkout_fields['billing'] as $key => $field ) : ?>
			<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>

		<?php endforeach; ?>

		<?php do_action('woocommerce_after_checkout_billing_form', $checkout ); ?>

		<?php if ( ! is_user_logged_in() && $checkout->enable_signup ) : ?>

			<?php if ( $checkout->enable_guest_checkout ) : ?>

				<p class="checkout-section-fieldset">
					<input class="input-checkbox" id="createaccount" <?php checked( ( true === $checkout->get_value( 'createaccount' ) || ( true === apply_filters( 'woocommerce_create_account_default_checked', false ) ) ), true) ?> type="checkbox" name="createaccount" value="1" /> <label for="createaccount" class="checkbox"><?php _e( 'Create an account?', 'woocommerce' ); ?></label>
				</p>

			<?php endif; ?>

			<?php do_action( 'woocommerce_before_checkout_registration_form', $checkout ); ?>

			<?php if ( ! empty( $checkout->checkout_fields['account'] ) ) : ?>

				<div class="create-account">

					<p><?php _e( 'Create an account by entering the information below. If you are a returning customer please login at the top of the page.', 'woocommerce' ); ?></p>

					<?php foreach ( $checkout->checkout_fields['account'] as $key => $field ) : ?>

						<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>

					<?php endforeach; ?>

					<div class="clear"></div>

				</div>

			<?php endif; ?>

			<?php do_action( 'woocommerce_after_checkout_registration_form', $checkout ); ?>

		<?php endif; ?>


		<?php
			if ( empty( $_POST ) ) {

				$ship_to_different_address = get_option( 'woocommerce_ship_to_destination' ) === 'shipping' ? 1 : 0;
				$ship_to_different_address = apply_filters( 'woocommerce_ship_to_different_address_checked', $ship_to_different_address );

			} else {

				$ship_to_different_address = $checkout->get_value( 'ship_to_different_address' );

			}
		?>

		<div class="clearfix"></div>

		<div class="checkout-section-fieldset checkbox fieldset" id="ship-to-different-address">
			<div class="checkout-section-fieldset-checkbox checked input-button">
				<div class="checkout-section-fieldset-checkbox-box"></div>
				<label for="ship-to-different-address-checkbox" class="checkout-section-fieldset-checkbox-label">My billing &amp; shipping addresses <span class="checkout-section-fieldset-checkbox-label-highlight">are the same</span>.</label>
				<input id="ship-to-different-address-checkbox" class="input input-checkbox" <?php checked( $ship_to_different_address, 1 ); ?> type="checkbox" name="ship_to_different_address" value="1" />
			</div>
		</div>
		<div class="checkout-section-buttons">
			<div class="checkout-section-buttons-item button next"><div class="checkout-section-buttons-item-text">Next Step</div></div>
		</div>

		<div class="clearfix"></div>

	</div>




<?php
/**
 * Checkout Payment Section
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<?php if ( ! is_ajax() ) : ?>
	<?php do_action( 'woocommerce_review_order_before_payment' ); ?>
<?php endif; ?>

<div id="payment" class="checkout-payment woocommerce-checkout-payment">
	<?php if ( WC()->cart->needs_payment() ) : ?>
	<div class="checkout-payment-methods payment_methods methods">
		<?php
			if ( ! empty( $available_gateways ) ) {
				foreach ( $available_gateways as $gateway ) {
					wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
				}
			} else {
				if ( ! WC()->customer->get_country() ) {
					$no_gateways_message = __( 'Please fill in your details above to see available payment methods.', 'woocommerce' );
				} else {
					$no_gateways_message = __( 'Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' );
				}

				echo '<p>' . apply_filters( 'woocommerce_no_available_payment_methods_message', $no_gateways_message ) . '</p>';
			}
		?>

		<div class="card-wrapper"></div>
	</div>
	<?php endif;
	

	// ==== ORDER NOTES =================================================================================
	do_action( 'woocommerce_checkout_before_order_review' );

			do_action( 'woocommerce_before_order_notes', $checkout ); 

			if ( apply_filters( 'woocommerce_enable_order_notes_field', get_option( 'woocommerce_enable_order_comments', 'yes' ) === 'yes' ) ) : 

				foreach ( $checkout->checkout_fields['order'] as $key => $field ) : 

					woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); 

				endforeach; 

			endif; 

			do_action( 'woocommerce_after_order_notes', $checkout ); 

	/* =================================================================================================== */
	?>

	<div class="form-row place-order">

		<noscript><?php _e( 'Since your browser does not support JavaScript, or it is disabled, please ensure you click the <em>Update Totals</em> button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'woocommerce' ); ?><br/><input type="submit" class="button alt" name="woocommerce_checkout_update_totals" value="<?php _e( 'Update totals', 'woocommerce' ); ?>" /></noscript>

		<?php wp_nonce_field( 'woocommerce-process_checkout' ); ?>

		<?php do_action( 'woocommerce_review_order_before_submit' ); ?>

		<?php if ( wc_get_page_id( 'terms' ) > 0 && apply_filters( 'woocommerce_checkout_show_terms', true ) ) : ?>
		<div class="checkout-section-fieldset checkbox required fieldset">
			<div class="checkout-section-fieldset-checkbox input-button">
				<div class="checkout-section-fieldset-checkbox-box"></div>
				<label for="terms" class="checkout-section-fieldset-checkbox-label">I&rsquo;ve read and accept the 
					<span class="checkout-section-fieldset-checkbox-label-highlight popup-trigger terms">terms &amp; conditions</span>.</label>
				<input id="terms" class="input input-checkbox" <?php checked( apply_filters( 'woocommerce_terms_is_checked_default', isset( $_POST['terms'] ) ), true ); ?> type="checkbox" name="terms" />
			</div>
		</div>
		<?php endif; ?>


		<?php echo apply_filters( 'woocommerce_order_button_html', '<input type="submit" class="button alt" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '" />' ); ?>

		<div class="checkout-section-buttons">
			<div class="checkout-section-buttons-item submit">
				<div class="checkout-section-buttons-item-text">Place Order</div>
			</div>
		</div>


		<?php do_action( 'woocommerce_review_order_after_submit' ); ?>

	</div>

	<div class="clear"></div>
</div>

<?php if ( ! is_ajax() ) : ?>
	<?php do_action( 'woocommerce_review_order_after_payment' ); ?>
<?php endif; ?>

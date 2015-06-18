<?php
/**
 * Output a single payment method
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
echo
'<div class="checkout-payment-stripe payment_method_' . $gateway->id. '">';
	// echo '
	// <input id="payment_method_'. $gateway->id. '" type="radio" class="checkout-payment-stripe-radio input-radio" name="payment_method" value="'. esc_attr( $gateway->id ). '"'. checked( $gateway->chosen, true ) . ' data-order_button_text="'. esc_attr( $gateway->order_button_text ) .'" />';

	// echo '
	// <label for="payment_method_'. $gateway->id .'">
	// 	'. $gateway->get_title() .' '. $gateway->get_icon() .'
	// </label>';
	?>
	<?php if ( $gateway->has_fields() || $gateway->get_description() ) : ?>
		<div class="payment_box payment_method_<?php echo $gateway->id; ?>" <?php if ( ! $gateway->chosen ) : ?><?php endif; ?>>
			<?php $gateway->payment_fields(); ?>
		</div>
	<?php endif; ?>
</div>

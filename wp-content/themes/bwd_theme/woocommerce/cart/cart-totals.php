<?php
/**
 * Cart totals
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="cart-totals<?php if ( WC()->customer->has_calculated_shipping() ) echo ' calculated_shipping'; ?> cart_totals">

	<?php do_action( 'woocommerce_before_cart_totals' ); ?>

	<table class="cart-totals-table" cellspacing="0">

		<tr class="cart-totals-table-row subtotal">
			<th class="cart-totals-table-row-heading"><?php _e( 'Subtotal:', 'woocommerce' ); ?></th>
			<td class="cart-totals-table-row-value"><?php wc_cart_totals_subtotal_html(); ?></td>
		</tr>

		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<tr class="cart-totals-table-row discount coupon-<?php echo esc_attr( $code ) . ':'; ?>">
				<th class="cart-totals-table-row-heading"><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
				<td class="cart-totals-table-row-value"><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
			<?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>

			<?php wc_cart_totals_shipping_html(); ?>

			<?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>
		<?php elseif ( WC()->cart->needs_shipping() ) : ?>

			<tr class="cart-totals-table-row shipping">
				<th class="cart-totals-table-row-heading"><?php _e( 'Shipping:', 'woocommerce' ); ?></th>
				<td class="cart-totals-table-row-value">Not Calculated</td>
			</tr>

		<?php endif; ?>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<tr class="cart-totals-table-row fee">
				<th class="cart-totals-table-row-heading"><?php echo esc_html( $fee->name ) . ':'; ?></th>
				<td class="cart-totals-table-row-value"><?php wc_cart_totals_fee_html( $fee ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php if ( WC()->cart->tax_display_cart == 'excl' ) : ?>
			<?php if ( get_option( 'woocommerce_tax_total_display' ) == 'itemized' ) : ?>
				<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
					<tr class="tax-rate tax-rate-<?php echo sanitize_title( $code ); ?>">
						<th class="cart-totals-table-row-heading"><?php echo esc_html( $tax->label ).':'; ?></th>
						<td class="cart-totals-table-row-value"><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
					</tr>
				<?php endforeach; ?>
			<?php else : ?>
				<tr class="cart-totals-table-row tax">
					<th class="cart-totals-table-row-heading"><?php echo esc_html( WC()->countries->tax_or_vat() ).':'; ?></th>
					<td class="cart-totals-table-row-value"><?php echo wc_cart_totals_taxes_total_html(); ?></td>
				</tr>
			<?php endif; ?>
		<?php endif; ?>

		<?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

		<tr class="cart-totals-table-row total">
			<th class="cart-totals-table-row-heading"><?php _e( 'Total:', 'woocommerce' ); ?></th>
			<td class="cart-totals-table-row-value"><?php wc_cart_totals_order_total_html(); ?></td>
		</tr>

		<?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>

	</table>

	<div class="cart-totals-actions">

		<div class="cart-totals-actions-button update"><span class="cart-totals-actions-button-text">Update Totals</span></a></div>
		<a href="<?php echo WC()->cart->get_checkout_url(); ?>" class="cart-totals-actions-button checkout"><span class="cart-totals-actions-button-text">Checkout</span></a>

		<?php //do_action( 'woocommerce_proceed_to_checkout' ); ?>

	</div>

	<?php do_action( 'woocommerce_after_cart_totals' ); ?>

</div>

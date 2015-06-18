<?php
/**
 * Checkout Form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wc_print_notices();

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout
if ( ! $checkout->enable_signup && ! $checkout->enable_guest_checkout && ! is_user_logged_in() ) {
	echo apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) );
	return;
}

// filter hook for include new pages inside the payment method
$get_checkout_url = apply_filters( 'woocommerce_get_checkout_url', WC()->cart->get_checkout_url() ); ?>

<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( $get_checkout_url ); ?>" enctype="multipart/form-data">


	<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>
		<div class="checkout-section billing">
			<?php do_action( 'woocommerce_checkout_billing' ); ?>
		</div>

		<div class="checkout-section shipping">
			<?php do_action( 'woocommerce_checkout_shipping' ); ?>
		</div>

	<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
	
	<div class="checkout-section payment">
		<h3 class="checkout-section-title"><div class="checkout-section-title-text"><?php _e( 'Payment &amp; Confirmation', 'woocommerce' ); ?></div></h3>

		<div class="checkout-section-wrap">

			<div id="order_review" class="woocommerce-checkout-review-order">
				<?php //do_action( 'woocommerce_checkout_order_review' ); ?>
				<?php woocommerce_order_review() ?>
				<?php woocommerce_checkout_payment() ?>
			</div>


			<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
			<div class="clearfix"></div>
		</div>
	</div>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>

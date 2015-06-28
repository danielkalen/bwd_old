<?php
/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<?php do_action( 'woocommerce_before_mini_cart' ); ?>

<ul class="cart-list cart_list product_list_widget">

	<?php if ( sizeof( WC()->cart->get_cart() ) > 0 ) : ?>

		<?php
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {

					$product_name  = apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key );
					$thumbnail     = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
					$product_price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
					?>
					<li class="cart-list-item">
						<?php echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf( '<a href="%s" class="cart-list-item-remove remove" title="%s">&times;</a>', esc_url( WC()->cart->get_remove_url( $cart_item_key ) ), __( 'Remove this item', 'woocommerce' ) ), $cart_item_key ); ?>
						<?php if ( ! $_product->is_visible() ) : ?>
							<?php echo '<div class="cart-list-item-img">' . str_replace( array( 'http:', 'https:' ), '', $thumbnail ) . '</div>'; ?>
							<?php echo '<div class="cart-list-item-content">'. $product_name ?>
						<?php else : ?>
							<a href="<?php echo esc_url( $_product->get_permalink( $cart_item ) ); ?>">
								<?php echo '<div class="cart-list-item-img">' . str_replace( array( 'http:', 'https:' ), '', $thumbnail ) . '</div>'; ?>
								<?php echo '<div class="cart-list-item-content"><div class="cart-list-item-content-title">'. $product_name .'</div>' ?>
						<?php endif; ?>
						<?php echo WC()->cart->get_item_data( $cart_item ); ?>

						<?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="cart-list-item-content-quantity quantity">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key ); ?>
						</div> <!-- .cart-list-item-content -->
						<?php if ( $_product->is_visible() ) : ?>
							</a>
						<?php endif; ?>
					</li>
					<?php
				}
			}
		?>

	<?php else : ?>

		<li class="cart-list-empty"><?php _e( 'No products in the cart.', 'woocommerce' ); ?></li>

	<?php endif; ?>

</ul><!-- end product list -->

<?php if ( sizeof( WC()->cart->get_cart() ) > 0 ) : ?>

	<div class="cart-list-total total"><strong><?php _e( 'Subtotal', 'woocommerce' ); ?>:</strong> <?php echo WC()->cart->get_cart_subtotal(); ?></div>

	<?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>

	<div class="cart-list-actions buttons">
		<a href="<?php echo WC()->cart->get_cart_url(); ?>" class="cart-list-actions-button button view_cart wc-forward"><span class="cart-list-actions-button-text button-text"><?php _e( 'View Cart', 'woocommerce' ); ?></span></a>
		<a href="<?php echo WC()->cart->get_checkout_url(); ?>" class="cart-list-actions-button button checkout wc-forward"><span class="cart-list-actions-button-text button-text"><?php _e( 'Checkout', 'woocommerce' ); ?></span></a>
	</div>

<?php endif; ?>

<?php do_action( 'woocommerce_after_mini_cart' ); ?>

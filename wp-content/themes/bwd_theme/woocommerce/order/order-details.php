<?php
/**
 * Order details
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$order = wc_get_order( $order_id );

?>
<table class="view-order shop_table order_details">
	<thead class="view-order-head">
		<tr>
			<th class="view-order-head-item name"></th>
			<th class="view-order-head-item name"><?php _e( 'Product', 'woocommerce' ); ?></th>
			<th class="view-order-head-item price"><?php _e( 'Price', 'woocommerce' ); ?></th>
			<th class="view-order-head-item quantity"><?php _e( 'Quantity', 'woocommerce' ); ?></th>
			<th class="view-order-head-item total"><?php _e( 'Total', 'woocommerce' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php
		if ( sizeof( $order->get_items() ) > 0 ) {

			foreach( $order->get_items() as $item_id => $item ) {
				$_product  = apply_filters( 'woocommerce_order_item_product', $order->get_product_from_item( $item ), $item );
				$item_meta = new WC_Order_Item_Meta( $item['item_meta'], $_product );

				if ( apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
					?>
					<tr class="view-order-item <?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'order_item', $item, $order ) ); ?>">
						<td class="view-order-item-cell thumb">
							<?php 
								$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

								echo $thumbnail;
							?>
						</td>

						<td class="view-order-item-cell name">
							<?php
								if ( $_product && ! $_product->is_visible() ) {
									echo apply_filters( 'woocommerce_order_item_name', $item['name'], $item );
								} else {
									echo apply_filters( 'woocommerce_order_item_name', sprintf( '<a class="view-order-item-cell-name" href="%s">%s</a>', get_permalink( $item['product_id'] ), $item['name'] ), $item );
								}

								// Allow other plugins to add additional product information here
								do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order );

								$item_meta->display();

								if ( $_product && $_product->exists() && $_product->is_downloadable() && $order->is_download_permitted() ) {

									$download_files = $order->get_item_downloads( $item );
									$i              = 0;
									$links          = array();

									foreach ( $download_files as $download_id => $file ) {
										$i++;

										$links[] = '<small><a href="' . esc_url( $file['download_url'] ) . '">' . sprintf( __( 'Download file%s', 'woocommerce' ), ( count( $download_files ) > 1 ? ' ' . $i . ': ' : ': ' ) ) . esc_html( $file['name'] ) . '</a></small>';
									}

									echo '<br/>' . implode( '<br/>', $links );
								}

								// Allow other plugins to add additional product information here
								do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $order );
							?>
						</td>


						<td class="view-order-item-cell price">
							<div class="view-order-item-cell-price">
								<?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); ?>
							</div>
						</td>


						<td class="view-order-item-cell quantity">
							<div class="view-order-item-cell-quantity">
								<?php echo apply_filters( 'woocommerce_order_item_quantity_html', sprintf( '%s', $item['qty'] ) , $item ); ?>
							</div>
						</td>


						<td class="view-order-item-cell total">
							<div class="view-order-item-cell-total">
								<?php echo $order->get_formatted_line_subtotal( $item ); ?>
							</div>
						</td>
					</tr>
					<?php
				}

				if ( $order->has_status( array( 'completed', 'processing' ) ) && ( $purchase_note = get_post_meta( $_product->id, '_purchase_note', true ) ) ) {
					?>
					<tr class="product-purchase-note">
						<td colspan="3"><?php echo wpautop( do_shortcode( wp_kses_post( $purchase_note ) ) ); ?></td>
					</tr>
					<?php
				}
			}
		}

		do_action( 'woocommerce_order_items_table', $order );
		?>
	</tbody>
	<tfoot class="view-order-summary">
	<?php
		$has_refund = false;

		if ( $total_refunded = $order->get_total_refunded() ) {
			$has_refund = true;
		}

		if ( $totals = $order->get_order_item_totals() ) {
			foreach ( $totals as $key => $total ) {
				$value = $total['value'];

				// Check for refund
				if ( $has_refund && $key === 'order_total' ) {
					$refunded_tax_del = '';
					$refunded_tax_ins = '';

					// Tax for inclusive prices
					if ( wc_tax_enabled() && 'incl' == $order->tax_display_cart ) {

						$tax_del_array = array();
						$tax_ins_array = array();

						if ( 'itemized' == get_option( 'woocommerce_tax_total_display' ) ) {

							foreach ( $order->get_tax_totals() as $code => $tax ) {
								$tax_del_array[] = sprintf( '%s %s', $tax->formatted_amount, $tax->label );
								$tax_ins_array[] = sprintf( '%s %s', wc_price( $tax->amount - $order->get_total_tax_refunded_by_rate_id( $tax->rate_id ), array( 'currency' => $order->get_order_currency() ) ), $tax->label );
							}

						} else {
							$tax_del_array[] = sprintf( '%s %s', wc_price( $order->get_total_tax(), array( 'currency' => $order->get_order_currency() ) ), WC()->countries->tax_or_vat() );
							$tax_ins_array[] = sprintf( '%s %s', wc_price( $order->get_total_tax() - $order->get_total_tax_refunded(), array( 'currency' => $order->get_order_currency() ) ), WC()->countries->tax_or_vat() );
						}

						if ( ! empty( $tax_del_array ) ) {
							$refunded_tax_del .= ' ' . sprintf( __( '(Includes %s)', 'woocommerce' ), implode( ', ', $tax_del_array ) );
						}

						if ( ! empty( $tax_ins_array ) ) {
							$refunded_tax_ins .= ' ' . sprintf( __( '(Includes %s)', 'woocommerce' ), implode( ', ', $tax_ins_array ) );
						}
					}

					$value = '<del>' . strip_tags( $order->get_formatted_order_total() ) . $refunded_tax_del . '</del> <ins>' . wc_price( $order->get_total() - $total_refunded, array( 'currency' => $order->get_order_currency() ) ) . $refunded_tax_ins . '</ins>';
				}
				?>
				<tr class="view-order-summary-item">
					<td class="view-order-summary-item-empty"></td>
					<td class="view-order-summary-item-empty"></td>
					<th class="view-order-summary-item-label" colspan="2" scope="row"><?php echo $total['label']; ?></th>
					<td class="view-order-summary-item-value"><?php echo $value; ?></td>
				</tr>
				<?php
			}
		}

		// Check for refund
		if ( $has_refund ) { ?>
			<tr class="view-order-summary-item">
				<th class="view-order-summary-item-label" colspan="4" scope="row"><?php _e( 'Refunded:', 'woocommerce' ); ?></th>
				<td class="view-order-summary-item-value">-<?php echo wc_price( $total_refunded, array( 'currency' => $order->get_order_currency() ) ); ?></td>
			</tr>
		<?php
		}

		// Check for customer note
		if ( '' != $order->customer_note ) { ?>
			<tr class="view-order-summary-item">
				<th class="view-order-summary-item-label" colspan="4" scope="row"><?php _e( 'Note:', 'woocommerce' ); ?></th>
				<td class="view-order-summary-item-value"><?php echo wptexturize( $order->customer_note ); ?></td>
			</tr>
		<?php } ?>
	</tfoot>
</table>

<?php do_action( 'woocommerce_order_details_after_order_table', $order ); ?>

<h2 class="page-title">Customer Details</h1>

<div class="customer_details">

	<?php if ($order->billing_email || $order->billing_phone) { ?>
		<div class="customer_details-item">
			<div class="customer_details-item-title">Contact Information</div>

			<?php if ($order->billing_email) { ?>
				<div class="customer_details-item-row email">
					<span class="customer_details-item-row-label">Email: </span>
					<?php echo $order->billing_email ?>
				</div>
			<?php } ?>
			
			<?php if ($order->billing_phone) { ?>
				<div class="customer_details-item-row phone">
					<span class="customer_details-item-row-label">Phone: </span>
					<?php echo $order->billing_phone ?>
				</div>
			<?php } ?>

		</div>
	<?php } ?>


		<div class="customer_details-item">
			<div class="customer_details-item-title">Billing Address</div>

			<div class="customer_details-item-row billing">
				<?php
					if ( ! $order->get_formatted_billing_address() ) {
						_e( 'N/A', 'woocommerce' );
					} else {
						echo $order->get_formatted_billing_address();
					}
				?>
			</div>

		</div>

	<?php if ( ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() && get_option( 'woocommerce_calc_shipping' ) !== 'no' ) : ?>

		<div class="customer_details-item">
			<div class="customer_details-item-title">Shipping Address</div>

			<div class="customer_details-item-row shipping">
				<?php
					if ( ! $order->get_formatted_shipping_address() ) {
						_e( 'N/A', 'woocommerce' );
					} else {
						echo $order->get_formatted_shipping_address();
					}
				?>
			</div>

		</div>


	<?php endif; ?>
</div>

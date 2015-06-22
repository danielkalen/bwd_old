<?php
/**
 * My Orders
 *
 * Shows recent orders on the account page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$customer_orders = get_posts( apply_filters( 'woocommerce_my_account_my_orders_query', array(
	'numberposts' => $order_count,
	'meta_key'    => '_customer_user',
	'meta_value'  => get_current_user_id(),
	'post_type'   => wc_get_order_types( 'view-orders' ),
	'post_status' => array_keys( wc_get_order_statuses() )
) ) );

if ( $customer_orders ) : ?>

	<table class="orders-table shop_table shop_table_responsive my_account_orders">

		<thead class="orders-table-head">
			<tr>
				<th class="orders-table-head-item number">Order</th>
				<th class="orders-table-head-item date">Date</th>
				<th class="orders-table-head-item status">Status</th>
				<th class="orders-table-head-item total">Total</th>
				<th class="orders-table-head-item actions">&nbsp;</th>
			</tr>
		</thead>

		<tbody><?php
			foreach ( $customer_orders as $customer_order ) {
				$order      = wc_get_order();
				$order->populate( $customer_order );
				$item_count = $order->get_item_count();

				?><tr class="orders-table-item">
						<td class="orders-table-item-cell number" data-title="<?php _e( 'Order Number', 'woocommerce' ); ?>">
							<a class="orders-table-item-cell-number" href="<?php echo $order->get_view_order_url(); ?>">
								#<?php echo $order->get_order_number(); ?>
							</a>
						</td>
						<td class="orders-table-item-cell date" data-title="<?php _e( 'Date', 'woocommerce' ); ?>">
							<time datetime="<?php echo date( 'Y-m-d', strtotime( $order->order_date ) ); ?>" title="<?php echo esc_attr( strtotime( $order->order_date ) ); ?>"><?php echo date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ); ?></time>
						</td>
						<td class="orders-table-item-cell status" data-title="<?php _e( 'Status', 'woocommerce' ); ?>">
							<?php echo wc_get_order_status_name( $order->get_status() ); ?>
						</td>
						<td class="orders-table-item-cell total" data-title="<?php _e( 'Total', 'woocommerce' ); ?>">
							<div class="orders-table-item-cell-price"><?php echo $order->get_formatted_order_total() ?></div>
							<div class="orders-table-item-cell-item_count">(<?php echo $item_count ?> items)</div>
						</td>
						<td class="orders-table-item-cell actions">
							<?php
								$actions = array();

								if ( in_array( $order->get_status(), apply_filters( 'woocommerce_valid_order_statuses_for_payment', array( 'pending', 'failed' ), $order ) ) ) {
									$actions['pay'] = array(
										'url'  => $order->get_checkout_payment_url(),
										'name' => __( 'Pay', 'woocommerce' )
									);
								}

								if ( in_array( $order->get_status(), apply_filters( 'woocommerce_valid_order_statuses_for_cancel', array( 'pending', 'failed' ), $order ) ) ) {
									$actions['cancel'] = array(
										'url'  => $order->get_cancel_order_url( wc_get_page_permalink( 'myaccount' ) ),
										'name' => __( 'Cancel', 'woocommerce' )
									);
								}

								$actions['view'] = array(
									'url'  => $order->get_view_order_url(),
									'name' => __( 'View', 'woocommerce' )
								);

								$actions = apply_filters( 'woocommerce_my_account_my_orders_actions', $actions, $order );

								if ($actions) {
									foreach ( $actions as $key => $action ) {
										echo '<a href="' . esc_url( $action['url'] ) . '" class="orders-table-item-cell-button ' . sanitize_html_class( $key ) . '">' . 
											'<span class="orders-table-item-cell-button-text">' .
											esc_html( $action['name'] ) . 
											'</span>' .
										'</a>';
									}
								}
							?>
						</td>
				</tr><?php
			}
		?></tbody>

	</table>

<?php endif; ?>

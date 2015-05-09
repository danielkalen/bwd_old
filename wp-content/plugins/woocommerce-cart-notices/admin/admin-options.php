<?php
/**
 * WooCommerce Cart Notices
 *
 * This source file is subject to the GNU General Public License v3.0
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@skyverge.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade WooCommerce Cart Notices to newer
 * versions in the future. If you wish to customize WooCommerce Cart Notices for your
 * needs please refer to http://docs.woothemes.com/document/woocommerce-cart-notices/ for more information.
 *
 * @package     WC-Cart-Notices/Admin
 * @author      SkyVerge
 * @copyright   Copyright (c) 2012-2014, SkyVerge, Inc.
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * The Admin UI for the WooCommerce Cart Notices plugin.  This renders the
 * two screens: the main list of cart notices, and the create/update page.
 * The following globals and variables are expected:
 *
 * @global WC_Cart_Notices $wc_cart_notices the cart notices main class
 *
 * @var string $tab current tab, one of 'list', 'new' or 'edit
 * @var array $notices array of notice objects, if $tab is 'list'
 * @var object $notice notice object, if the tab is 'new' or 'edit'
 */

global $wc_cart_notices;

/* show any error messages */
$wc_cart_notices->admin->message_handler->show_messages(); ?>
<style type="text/css">
	tr.inactive { background-color: #F4F4F4; color:#555555; }
	.chzn-choices .search-field { min-width:200px; }
	.chzn-choices .search-field input { min-width:100%; }
	.chzn-container-multi { width: 350px !important; }
	p.note {
		border: 1px solid #DDDDDD;
		float: left;
		margin-top: 0;
		padding: 8px;
	}
</style>
<div class="wrap woocommerce">
	<div id="icon-edit-comments" class="icon32"><br></div>
	<h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
		<a href="admin.php?page=<?php echo $wc_cart_notices->id ?>&tab=list" class="nav-tab <?php echo ( 'list' == $tab ) ? 'nav-tab-active' : ''; ?>"><?php _e( 'Cart Notices', WC_Cart_Notices::TEXT_DOMAIN ); ?></a>
		<a href="admin.php?page=<?php echo $wc_cart_notices->id ?>&tab=new" class="nav-tab <?php echo ( 'new' == $tab ) ? 'nav-tab-active' : ''; ?>"><?php _e( 'New Notice', WC_Cart_Notices::TEXT_DOMAIN ); ?></a>
	</h2>

	<?php if ( isset( $_GET['result'] ) ) : /* show any action messages */ ?>
	<div id="message" class="updated"><p><strong><?php _e( 'Cart Notice ' . $_GET['result'], WC_Cart_Notices::TEXT_DOMAIN ); ?></strong></p></div>
	<?php endif; ?>

	<?php if ( 'list' == $tab ) : ?>
	<h3><?php _e( 'Cart Notices', WC_Cart_Notices::TEXT_DOMAIN ); ?></h3>

	<table class="wp-list-table widefat fixed posts">
		<thead>
			<tr>
				<th scope="col" id="name" class="manage-column column-type" style=""><?php _e( 'Name', WC_Cart_Notices::TEXT_DOMAIN ); ?></th>
				<th scope="col" id="type" class="manage-column column-amount" style=""><?php _e( 'Type', WC_Cart_Notices::TEXT_DOMAIN ); ?></th>
				<th scope="col" id="message" class="manage-column column-products" style=""><?php _e( 'Message', WC_Cart_Notices::TEXT_DOMAIN ); ?></th>
				<th scope="col" id="action" class="manage-column column-usage_count" style=""><?php _e( 'Call to Action', WC_Cart_Notices::TEXT_DOMAIN ); ?></th>
				<th scope="col" id="action_url" class="manage-column column-usage_count" style=""><?php _e( 'Call to Action URL', WC_Cart_Notices::TEXT_DOMAIN ); ?></th>
				<th scope="col" id="data" class="manage-column column-usage_count" style=""><?php _e( 'Other', WC_Cart_Notices::TEXT_DOMAIN ); ?></th>
			</tr>
		</thead>
		<tbody id="the_list">
			<?php if ( empty( $notices ) ) : ?>
			<tr scope="row">
				<th colspan="6"><?php _e( 'No notices configured', WC_Cart_Notices::TEXT_DOMAIN ); ?></th>
			</tr>
			<?php
			else:
				foreach ( $notices as $notice ) :
			?>
			<tr scope="row" class="<?php echo $notice->enabled ? 'active' : 'inactive' ?>">
				<td class="post-title column-title">
					<strong><a class="row-title" href="admin.php?page=<?php echo $wc_cart_notices->id ?>&tab=edit&id=<?php echo $notice->id; ?>"><?php echo stripslashes( $notice->name ); ?></a></strong>
					<div class="row-actions">
						<span class="edit"><a href="admin.php?page=<?php echo $wc_cart_notices->id ?>&tab=edit&id=<?php echo $notice->id; ?>"><?php _e( 'Edit', WC_Cart_Notices::TEXT_DOMAIN ); ?></a></span>
						|
						<span class="enable"><a href="admin.php?page=<?php echo $wc_cart_notices->id ?>&tab=<?php echo $notice->enabled ? 'disable' : 'enable' ?>&id=<?php echo $notice->id; ?>"><?php echo $notice->enabled ? __( 'Disable', WC_Cart_Notices::TEXT_DOMAIN ) : __( 'Enable', WC_Cart_Notices::TEXT_DOMAIN ); ?></a></span>
						|
						<span class="trash"><a onclick="return confirm( 'Really delete this entry?' );" href="admin.php?page=<?php echo $wc_cart_notices->id ?>&tab=delete&id=<?php echo $notice->id; ?>"><?php _e( 'Delete', WC_Cart_Notices::TEXT_DOMAIN ); ?></a></span>
					</div>
				</td>
				<td>
					<?php _e( $notice->type, WC_Cart_Notices::TEXT_DOMAIN ); ?>
				</td>
				<td>
					<?php echo htmlspecialchars( $notice->message ); ?>
				</td>
				<td>
					<?php _e( $notice->action, WC_Cart_Notices::TEXT_DOMAIN ); ?>
				</td>
				<td>
					<?php echo $notice->action_url; ?>
				</td>
				<td>
					<?php
					switch ( $notice->type ) {
						case 'minimum_amount':
							echo sprintf( __( "Minimum order amount: %s", WC_Cart_Notices::TEXT_DOMAIN ), $wc_cart_notices->get_minimum_order_amount( $notice ) ? get_woocommerce_currency_symbol() . $wc_cart_notices->get_minimum_order_amount( $notice ) : "none configured" ) . '<br/>';
							echo sprintf( __( "Threshold amount: %s", WC_Cart_Notices::TEXT_DOMAIN ), isset( $notice->data['threshold_order_amount'] ) ? get_woocommerce_currency_symbol() . $notice->data['threshold_order_amount'] : "none configured" );
						break;
						case 'deadline':
							echo sprintf( __( "Deadline Hour: %s", WC_Cart_Notices::TEXT_DOMAIN ), $notice->data['deadline_hour'] ? $notice->data['deadline_hour'] : '<em>none</em>' ) . '<br/>';
							echo sprintf( __( "Active Days: %s", WC_Cart_Notices::TEXT_DOMAIN ), $notice->data['deadline_days_names'] ? implode( ', ', $notice->data['deadline_days_names'] ) : '<em>none</em>' );
						break;
						case 'referer':
							echo sprintf( __( "Referring Site: %s", WC_Cart_Notices::TEXT_DOMAIN ), $notice->data['referer'] ? $notice->data['referer'] : '<em>none</em>' );
						break;
						case 'products':
							echo sprintf( __( "Products: %s", WC_Cart_Notices::TEXT_DOMAIN ), $notice->data['products'] ? implode( ', ', $notice->data['products'] ) : '<em>none</em>' );
							if ( isset( $notice->data['minimum_quantity'] ) && '' !== $notice->data['minimum_quantity'] ) echo '<br/>' . sprintf( __( "Minimum quantity: %s", WC_Cart_Notices::TEXT_DOMAIN ), $notice->data['minimum_quantity'] );
							if ( isset( $notice->data['maximum_quantity'] ) && '' !== $notice->data['maximum_quantity'] ) echo '<br/>' . sprintf( __( "Maximum quantity: %s", WC_Cart_Notices::TEXT_DOMAIN ), $notice->data['maximum_quantity'] );
						break;
						case 'categories':
							echo sprintf( __( "Categories: %s", WC_Cart_Notices::TEXT_DOMAIN ), $notice->data['categories'] ? implode( ', ', $notice->data['categories'] ) : '<em>none</em>' );
						break;
					}
					?>
				</td>
			</tr>
			<?php
				endforeach;
			?>
			<?php endif; ?>
		</tbody>
		<tfoot>
			<tr>
				<th scope="col" id="name" class="manage-column column-type" style=""><?php _e( 'Name', WC_Cart_Notices::TEXT_DOMAIN ); ?></th>
				<th scope="col" id="type" class="manage-column column-amount" style=""><?php _e( 'Type', WC_Cart_Notices::TEXT_DOMAIN ); ?></th>
				<th scope="col" id="message" class="manage-column column-products" style=""><?php _e( 'Message', WC_Cart_Notices::TEXT_DOMAIN ); ?></th>
				<th scope="col" id="action" class="manage-column column-usage_count" style=""><?php _e( 'Call to Action', WC_Cart_Notices::TEXT_DOMAIN ); ?></th>
				<th scope="col" id="action_url" class="manage-column column-usage_count" style=""><?php _e( 'Call to Action URL', WC_Cart_Notices::TEXT_DOMAIN ); ?></th>
				<th scope="col" id="data" class="manage-column column-usage_count" style=""><?php _e( 'Other', WC_Cart_Notices::TEXT_DOMAIN ); ?></th>
			</tr>
		</tfoot>
	</table>

	<br/>
	<h3>Shortcode Reference</h3>
	<p><?php _e( 'In addition to the default placement on the cart/checkout pages, you can embed one or all of the notices anywhere on the site with the following shortcodes:', WC_Cart_Notices::TEXT_DOMAIN ) ?></p>
	<ul>
		<li><?php printf( __( '%s will embed all notices',                         WC_Cart_Notices::TEXT_DOMAIN ), '<code>[woocommerce_cart_notice]</code>' ) ?></li>
		<li><?php printf( __( "%s will embed just the notice named XXX",           WC_Cart_Notices::TEXT_DOMAIN ), "<code>[woocommerce_cart_notice name='XXX']</code>" ) ?></li>
		<li><?php printf( __( "%s will embed just the minimum amount notices",     WC_Cart_Notices::TEXT_DOMAIN ), "<code>[woocommerce_cart_notice type='minimum_amount']</code>" ) ?></li>
		<li><?php printf( __( "%s will embed just the deadline notices",           WC_Cart_Notices::TEXT_DOMAIN ), "<code>[woocommerce_cart_notice type='deadline']</code>" ) ?></li>
		<li><?php printf( __( "%s will embed just the referer notices",            WC_Cart_Notices::TEXT_DOMAIN ), "<code>[woocommerce_cart_notice type='referer']</code>" ) ?></li>
		<li><?php printf( __( "%s will embed just the products in cart notices",   WC_Cart_Notices::TEXT_DOMAIN ), "<code>[woocommerce_cart_notice type='products']</code>" ) ?></li>
		<li><?php printf( __( "%s will embed just the categories in cart notices", WC_Cart_Notices::TEXT_DOMAIN ), "<code>[woocommerce_cart_notice type='categories']</code>" ) ?></li>
	</ul>

	<?php elseif ( 'new' == $tab || 'edit' == $tab ) : ?>

	<form action="admin-post.php" method="post">
		<h3><?php echo 'new' == $tab ? __( 'Create a New Cart Notice', WC_Cart_Notices::TEXT_DOMAIN ) : __( 'Update Cart Notice', WC_Cart_Notices::TEXT_DOMAIN ); ?></h3>

		<table class="form-table">
			<tbody>

				<tr valign="top">
					<th scope="row">
						<label for="notice_type"><?php _e( 'Type', WC_Cart_Notices::TEXT_DOMAIN ); ?></label>
					</th>
					<td>
						<?php if ( 'new' == $tab ) : ?>
						<select name="notice_type" id="notice_type">
							<option value=""><?php _e( 'Choose One', WC_Cart_Notices::TEXT_DOMAIN ); ?></option>
							<?php foreach ( $wc_cart_notices->admin->notice_types as $value => $name ) : ?>
							<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $notice->type, $value ); ?>><?php echo esc_html( $name ); ?></option>
							<?php endforeach; ?>
						</select>
						<?php elseif ( 'edit' == $tab ) : ?>
							<p><?php _e( $notice->type ); /* read-only */ ?></p>
						<?php endif; ?>
						<div class="description minimum_amount_notice_data notice_data" style="<?php echo 'minimum_amount' != $notice->type ? 'display:none;' : ''; ?>">
							<?php _e( 'This notice will appear on the cart/checkout pages only when the order total is less than the Minimum Order Amount, and/or is greater than or equal to the Threshold Amount and is convenient for encouraging customers to increase their order to qualify for free shipping.', WC_Cart_Notices::TEXT_DOMAIN ); ?>
						</div>
						<div class="description deadline_notice_data notice_data" style="<?php echo 'deadline' != $notice->type ? 'display:none;' : ''; ?>">
							<?php _e( 'This notice will appear on the cart/checkout pages only on the Active Days, and up to the Deadline Hour, based on your WordPress timezone.', WC_Cart_Notices::TEXT_DOMAIN ); ?>
						</div>
						<div class="description referer_notice_data notice_data" style="<?php echo 'referer' != $notice->type ? 'display:none;' : ''; ?>">
							<?php _e( 'This notice will appear on the cart/checkout pages only when the customer originated from the configured site.', WC_Cart_Notices::TEXT_DOMAIN ); ?>
						</div>
						<div class="description products_notice_data notice_data" style="<?php echo 'products' != $notice->type ? 'display:none;' : ''; ?>">
							<?php _e( 'This notice will appear on the cart/checkout pages when any of the configured products appear within the cart.', WC_Cart_Notices::TEXT_DOMAIN ); ?>
						</div>
						<div class="description categories_notice_data notice_data" style="<?php echo 'categories' != $notice->type ? 'display:none;' : ''; ?>">
							<?php _e( 'This notice will appear on the cart/checkout pages when any of the cart products belong to any of the categories configured below.', WC_Cart_Notices::TEXT_DOMAIN ); ?>
						</div>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">
						<label for="notice_name"><?php _e( 'Name', WC_Cart_Notices::TEXT_DOMAIN ); ?></label>
					</th>
					<td>
						<input type="text" name="notice_name" id="notice_name" value="<?php echo esc_attr( $notice->name ); ?>" class="regular-text" />
						<span class="description">
							<?php _e( 'Provide a name so you can easily recognize this notice within the admin.', WC_Cart_Notices::TEXT_DOMAIN ); ?>
						</span>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">
						<label for="notice_enabled"><?php _e( 'Enabled', WC_Cart_Notices::TEXT_DOMAIN ); ?></label>
					</th>
					<td>
						<input type="checkbox" name="notice_enabled" id="notice_enabled" value="1" <?php checked( 'new' == $tab ? 1 : $notice->enabled, 1 ); ?>/>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">
						<label for="notice_message"><?php _e( 'Notice Message', WC_Cart_Notices::TEXT_DOMAIN ); ?></label>
						<br />
						<span class="description">
							<?php _e( 'Depending on the notice type you may use the following variables:', WC_Cart_Notices::TEXT_DOMAIN ); ?>
							<ul>
								<li><strong>{amount_under}</strong> <img class="help_tip" width="16" height="16" title="<?php _e( "With type 'Minimum Amount' this is the amount required to meet the minimum order amount.", WC_Cart_Notices::TEXT_DOMAIN ); ?>" src="<?php echo SV_WC_Plugin_Compatibility::WC()->plugin_url(); ?>/assets/images/help.png" /></li>
								<li><strong>{time}</strong> <img class="help_tip" width="16" height="16" title="<?php _e( "With type 'Deadline' this is the amount of time remaining, ie '1 hour 15 minutes' or '25 minutes', etc.", WC_Cart_Notices::TEXT_DOMAIN ); ?>" src="<?php echo SV_WC_Plugin_Compatibility::WC()->plugin_url(); ?>/assets/images/help.png" /></li>
								<li><strong>{products}</strong> <img class="help_tip" width="16" height="16" title="<?php _e( "With type 'Products in Cart' or 'Categories in Cart' these are the matching product names.", WC_Cart_Notices::TEXT_DOMAIN ); ?>" src="<?php echo SV_WC_Plugin_Compatibility::WC()->plugin_url(); ?>/assets/images/help.png" /></li>
								<li><strong>{quantity}</strong> <img class="help_tip" width="16" height="16" title="<?php _e( "With type 'Products in Cart' this is the product quantity.", WC_Cart_Notices::TEXT_DOMAIN ); ?>" src="<?php echo SV_WC_Plugin_Compatibility::WC()->plugin_url(); ?>/assets/images/help.png" /></li>
								<li><strong>{quantity_under}</strong> <img class="help_tip" width="16" height="16" title="<?php _e( "With type 'Products in Cart' and 'Maximum Quantity for Notice' configured this is the product quantity less than the maximum.", WC_Cart_Notices::TEXT_DOMAIN ); ?>" src="<?php echo SV_WC_Plugin_Compatibility::WC()->plugin_url(); ?>/assets/images/help.png" /></li>
								<li><strong>{quantity_over}</strong> <img class="help_tip" width="16" height="16" title="<?php _e( "With type 'Products in Cart' and 'Minimum Quantity for Notice' configured this is the product quantity over the minimum.", WC_Cart_Notices::TEXT_DOMAIN ); ?>" src="<?php echo SV_WC_Plugin_Compatibility::WC()->plugin_url(); ?>/assets/images/help.png" /></li>
								<li><strong>{categories}</strong> <img class="help_tip" width="16" height="16" title="<?php _e( "With type 'Categories in Cart' these are the matching category names.", WC_Cart_Notices::TEXT_DOMAIN ); ?>" src="<?php echo SV_WC_Plugin_Compatibility::WC()->plugin_url(); ?>/assets/images/help.png" /></li>
							</ul>
						</span>
					</th>
					<td>
						<textarea name="notice_message" id="notice_message" rows="6" cols="80"><?php echo esc_textarea( $notice->message ); ?></textarea>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">
						<label for="call_to_action"><?php _e( 'Call to Action', WC_Cart_Notices::TEXT_DOMAIN ); ?></label>
					</th>
					<td>
						<input type="text" name="call_to_action" id="call_to_action" value="<?php echo esc_attr( $notice->action ); ?>" class="regular-text" />
						<span class="description">
							<?php _e( 'Optional call to action button text, rendered next to the cart notice', WC_Cart_Notices::TEXT_DOMAIN ); ?>
						</span>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">
						<label for="call_to_action_url"><?php _e( 'Call to Action URL', WC_Cart_Notices::TEXT_DOMAIN ); ?></label>
					</th>
					<td>
						<input type="text" name="call_to_action_url" id="call_to_action_url" value="<?php echo esc_attr( $notice->action_url ); ?>" class="regular-text" />
						<span class="description">
							<?php _e( 'Optional call to action url, this is where the user will go upon clicking the Call to Action button', WC_Cart_Notices::TEXT_DOMAIN ); ?>
						</span>
					</td>
				</tr>

				<tr valign="top" class="minimum_amount_notice_data notice_data" style="<?php echo 'minimum_amount' != $notice->type ? 'display:none;' : ''; ?>">
					<th scope="row">
						<label for="minimum_order_amount"><?php _e( 'Minimum Order Amount', WC_Cart_Notices::TEXT_DOMAIN ); ?></label>
					</th>
					<td>
						<input type="text" name="minimum_order_amount" id="minimum_order_amount" value="<?php echo isset( $notice->data['minimum_order_amount'] ) ? esc_attr( $notice->data['minimum_order_amount'] ) : ''; ?>" class="regular-text" />
						<span class="description">
							<?php _e( 'Optional minimum order amount to activate the notice, the cart total must be <em>less than</em> this amount for the notice to be displayed.  If not set, and the <a href="' . admin_url( 'admin.php?page=woocommerce_settings&tab=shipping&section=WC_Free_Shipping' ) . '">Free Shipping shipment method</a> is enabled, the Minimum Order Amount from the shipping method will be used.', WC_Cart_Notices::TEXT_DOMAIN ); ?>
						</span>
					</td>
				</tr>

				<tr valign="top" class="minimum_amount_notice_data notice_data" style="<?php echo 'minimum_amount' != $notice->type ? 'display:none;' : ''; ?>">
					<th scope="row">
						<label for="threshold_order_amount"><?php _e( 'Threshold Amount', WC_Cart_Notices::TEXT_DOMAIN ); ?></label>
					</th>
					<td>
						<input type="text" name="threshold_order_amount" id="threshold_order_amount" value="<?php echo isset( $notice->data['threshold_order_amount'] ) ? esc_attr( $notice->data['threshold_order_amount'] ) : ''; ?>" class="regular-text" />
						<span class="description">
							<?php _e( 'Optional threshold amount to activate the notice.  If set, the cart must contain <em>at least</em> this total amount for the notice to be displayed.', WC_Cart_Notices::TEXT_DOMAIN ); ?>
						</span>
					</td>
				</tr>

				<tr valign="top" class="deadline_notice_data notice_data" style="<?php echo 'deadline' != $notice->type ? 'display:none;' : ''; ?>">
					<th scope="row">
						<label for="deadline_hour"><?php _e( 'Deadline Hour', WC_Cart_Notices::TEXT_DOMAIN ); ?></label>
					</th>
					<td>
						<input type="text" name="deadline_hour" id="deadline_hour" value="<?php echo isset( $notice->data['deadline_hour'] ) ? esc_attr( $notice->data['deadline_hour'] ) : ''; ?>" class="regular-text" />
						<span class="description">
							<?php _e( 'Deadline hour in 24-hour format, this can be 1 to 24.', WC_Cart_Notices::TEXT_DOMAIN ); ?>
						</span>
					</td>
				</tr>

				<?php $days = array( 'Sun', 'Mon', 'Tue', 'Wed', 'Thur', 'Fri', 'Sat' ); ?>
				<tr valign="top" class="deadline_notice_data notice_data" style="<?php echo 'deadline' != $notice->type ? 'display:none;' : ''; ?>">
					<th scope="row">
						<label><?php _e( 'Active Days', WC_Cart_Notices::TEXT_DOMAIN ); ?></label>
					</th>
					<td>
						<?php
						foreach( $days as $key => $name ) {
							$input_name = 'deadline_' . strtolower( $name );
							$value = isset( $notice->data['deadline_days'][ $key ] ) ? $notice->data['deadline_days'][ $key ] : 0;

							echo '<input id="' . esc_attr( $input_name ) . '" name="deadline_days[' . $key . ']" type="checkbox" value="1" ' . checked( $value, 1, false ) . ' />';
							echo ' <label for="' . esc_attr( $input_name ) . '">' . esc_html( __( $name, WC_Cart_Notices::TEXT_DOMAIN ) ) . '</label>';
						}
						?>
						<div class="description">
							<?php _e( 'Select the days on which you want this notice to be active.', WC_Cart_Notices::TEXT_DOMAIN ); ?>
						</div>
					</td>
				</tr>

				<tr valign="top" class="referer_notice_data notice_data" style="<?php echo 'referer' != $notice->type ? 'display:none;' : ''; ?>">
					<th scope="row">
						<label for="referer"><?php _e( 'Referring Site', WC_Cart_Notices::TEXT_DOMAIN ); ?></label>
					</th>
					<td>
						<input type="text" name="referer" id="referer" value="<?php echo isset( $notice->data['referer'] ) ? esc_attr( $notice->data['referer'] ) : ''; ?>" class="regular-text" />
						<span class="description">
							<?php _e( 'When the visitor originates from this server, they will be shown the referer cart notice. Example: www.google.com.', WC_Cart_Notices::TEXT_DOMAIN ); ?>
						</span>
					</td>
				</tr>

				<tr valign="top" class="products_notice_data notice_data" style="<?php echo 'products' != $notice->type ? 'display:none;' : ''; ?>">
					<th scope="row">
						<label for="product_ids"><?php _e( 'Products', WC_Cart_Notices::TEXT_DOMAIN ); ?></label>
					</th>
					<td>
						<select id="product_ids" name="product_ids[]" class="ajax_chosen_select_products_and_variations" multiple="multiple" data-placeholder="<?php _e( 'Search for a product&hellip;', WC_Cart_Notices::TEXT_DOMAIN ) ?>" style="min-width: 300px;">
						<?php
							if ( isset( $notice->data['products'] ) ) {
								foreach ( $notice->data['products'] as $value => $title ) {
									echo '<option value="' . esc_attr( $value ) . '" selected="selected">'. esc_html( $title ) .'</option>';
								}
							}
						?>
						</select>
					</td>
				</tr>

				<tr valign="top" class="products_notice_data notice_data" style="<?php echo 'products' != $notice->type ? 'display:none;' : ''; ?>">
					<th scope="row">
						<label for="minimum_product_quantity"><?php _e( 'Minimum Quantity for Notice', WC_Cart_Notices::TEXT_DOMAIN ); ?></label>
					</th>
					<td>
						<input type="text" name="minimum_quantity" id="minimum_product_quantity" value="<?php echo isset( $notice->data['minimum_quantity'] ) ? esc_attr( $notice->data['minimum_quantity'] ) : ''; ?>" class="regular-text" />
						<span class="description">
							<?php _e( 'Optional minimum product quantity required to activate the notice.  If set, the quantity of the products selected above must be greater than or equal to this amount.', WC_Cart_Notices::TEXT_DOMAIN ); ?>
						</span>
					</td>
				</tr>

				<tr valign="top" class="products_notice_data notice_data" style="<?php echo 'products' != $notice->type ? 'display:none;' : ''; ?>">
					<th scope="row">
						<label for="maximum_product_quantity"><?php _e( 'Maximum Quantity for Notice', WC_Cart_Notices::TEXT_DOMAIN ); ?></label>
					</th>
					<td>
						<input type="text" name="maximum_quantity" id="maximum_product_quantity" value="<?php echo isset( $notice->data['maximum_quantity'] ) ? esc_attr( $notice->data['maximum_quantity'] ) : ''; ?>" class="regular-text" />
						<span class="description">
							<?php _e( 'Optional maximum product quantity allowed to activate the notice.  If set, the quantity of the products selected above must be less than or equal to this amount.', WC_Cart_Notices::TEXT_DOMAIN ); ?>
						</span>
					</td>
				</tr>

				<tr valign="top" class="products_notice_data notice_data" style="<?php echo 'products' != $notice->type ? 'display:none;' : ''; ?>">
					<th scope="row">
						<label for="shipping_countries"><?php _e( 'Shipping Countries', WC_Cart_Notices::TEXT_DOMAIN ); ?></label>
					</th>
					<td>
						<select id="shipping_countries" name="shipping_countries[]" class="chosen_select" multiple="multiple" data-placeholder="<?php _e( 'Choose Countries&hellip;', WC_Cart_Notices::TEXT_DOMAIN ) ?>">
						<?php
							foreach ( SV_WC_Plugin_Compatibility::WC()->countries->countries as $code => $name ) :
								$selected = isset( $notice->data['shipping_countries'] ) && is_array( $notice->data['shipping_countries'] ) && in_array( $code, $notice->data['shipping_countries'] );
								echo '<option value="' . esc_attr( $code ) . '"' . selected( $selected, true, false ) . '>' . esc_html( $name ) . '</option>';
							endforeach;
						?>
						</select>
						<span class="description">
							<?php _e( 'Optional list of countries used to trigger the message when the shipping country is available and matches one of the countries selected here.', WC_Cart_Notices::TEXT_DOMAIN ); ?>
						</span>
					</td>
				</tr>

				<tr valign="top" class="categories_notice_data notice_data" style="<?php echo 'categories' != $notice->type ? 'display:none;' : ''; ?>">
					<th scope="row">
						<label for="category_ids"><?php _e( 'Categories', WC_Cart_Notices::TEXT_DOMAIN ); ?></label>
					</th>
					<td>
						<select id="category_ids" name="category_ids[]" class="ajax_chosen_select_product_categories" multiple="multiple" data-placeholder="<?php _e( 'Search for a category&hellip;', WC_Cart_Notices::TEXT_DOMAIN ) ?>" style="min-width: 300px;">
						<?php
							if ( isset( $notice->data['categories'] ) ) {
								foreach ( $notice->data['categories'] as $value => $title ) {
									echo '<option value="' . esc_attr( $value ) . '" selected="selected">'. esc_html( $title ) .'</option>';
								}
							}
						?>
						</select>
					</td>
				</tr>

			</tbody>
		</table>
		<p class="submit">
			<?php if ( 'new' == $tab ) : ?>
			<input type="hidden" name="action" value="cart_notice_new" />
			<input type="submit" name="save" value="<?php _e( 'Create Cart Notice', WC_Cart_Notices::TEXT_DOMAIN ); ?>" class="button-primary" />
			<?php elseif ( 'edit' == $tab ) : ?>
			<input type="hidden" name="action" value="cart_notice_edit" />
			<input type="hidden" name="id" value="<?php echo $notice->id ?>" />
			<input type="submit" name="save" value="<?php _e( 'Update Cart Notice', WC_Cart_Notices::TEXT_DOMAIN ); ?>" class="button-primary" />
		   	<?php endif; ?>
		</p>
	</form>

	<?php
	if ( 'edit' == $tab ) :
		// display an example notice, when possible.  No real good way of doing this for the products/categories notices since they rely on the cart
		switch ( $notice->type ) {
			case 'minimum_amount':
				$minimum_order_amount = $wc_cart_notices->get_minimum_order_amount( $notice );
				$threshold_order_amount = isset( $notice->data['threshold_order_amount'] ) ? $notice->data['threshold_order_amount'] : null;

				// determine a cart contents total that is most likely to cause a notice to be displayed
				$cart_contents_total = 0;
				if ( is_numeric( $minimum_order_amount ) ) $cart_contents_total = $minimum_order_amount - 1;
				elseif ( is_numeric( $threshold_order_amount ) ) $cart_contents_total = $threshold_order_amount + 1;

				$example_notice = $wc_cart_notices->get_minimum_amount_notice( $notice, array( 'cart_contents_total' => $cart_contents_total ) );
			break;
			case 'deadline':       $example_notice = $wc_cart_notices->get_deadline_notice( $notice ); break;
			case 'referer':        $example_notice = $wc_cart_notices->get_referer_notice( $notice ); break;
		}
		if ( isset( $example_notice) ) :
			?>
			<h3><?php _e( 'Example Notice', WC_Cart_Notices::TEXT_DOMAIN ); ?></h3>
			<p style="float:left;padding-top:8px;margin-right:8px;margin-top:0;">
				<?php
					if ( 'minimum_amount' == $notice->type ) {

						if ( is_numeric( $minimum_order_amount ) && ! is_numeric( $threshold_order_amount ) ) {
							printf( __( 'With the current configuration your cart notice will display when the order total is less than <strong>%s</strong> and will resemble:', WC_Cart_Notices::TEXT_DOMAIN ), get_woocommerce_currency_symbol() . $minimum_order_amount );
						} elseif ( ! is_numeric( $minimum_order_amount ) && is_numeric( $threshold_order_amount ) ) {
							printf( __( 'With the current configuration your cart notice will display when the order total is greter than or equal to <strong>%s</strong> and will resemble:', WC_Cart_Notices::TEXT_DOMAIN ), get_woocommerce_currency_symbol() . $threshold_order_amount );
						} elseif ( is_numeric( $minimum_order_amount ) && is_numeric( $threshold_order_amount ) ) {
							printf( __( 'With the current configuration your cart notice will display when the order total is between <strong>%s</strong> and <strong>%s</strong> and will resemble:', WC_Cart_Notices::TEXT_DOMAIN ), get_woocommerce_currency_symbol() . $threshold_order_amount, get_woocommerce_currency_symbol() . $minimum_order_amount );
						}
					} else {
						_e( 'With the current configuration your cart notice will resemble: ', WC_Cart_Notices::TEXT_DOMAIN );
					}
				?>
			</p>
			<?php if ( $example_notice ) echo $example_notice;
				  else echo '<p style="float:left;padding-top:8px;margin-top:0;"><em>' . __( 'No notice', WC_Cart_Notices::TEXT_DOMAIN ) . '</em></p>';
			?>
			<div style="clear:left;"></div>
		<?php endif; ?>
	<?php endif; ?>

	<script type="text/javascript">
	var default_messages = {
		'minimum_amount' : '<?php _e( 'Add <strong>{amount_under}</strong> to your cart in order to receive free shipping!', WC_Cart_Notices::TEXT_DOMAIN ); ?>',
		'deadline' : '<?php _e( 'Order within the next <strong>{time}</strong> and your order ships today!', WC_Cart_Notices::TEXT_DOMAIN ) ?>'
	};
	jQuery( 'select#notice_type' ).change( function() {
		// show/hide descriptions and inputs based on the currently selected notice type
		jQuery( '.notice_data' ).hide();
		var notice_type = jQuery( 'select#notice_type option:selected' ).val();
		if ( notice_type ) jQuery( '.' + notice_type + '_notice_data' ).show();

		<?php if ( 'new' == $tab ) : /* Set some helpful defaults for the notice message field */ ?>
		var notice_message = jQuery( '#notice_message' );
		if ( notice_type == 'minimum_amount' ) {
			if ( ! notice_message.val() || notice_message.val() == default_messages['deadline'] )
				notice_message.val( default_messages['minimum_amount'] );
		} else if ( 'deadline' == notice_type ) {
			if ( ! notice_message.val() || notice_message.val() == default_messages['minimum_amount'] )
				notice_message.val( default_messages['deadline'] );
		} else if ( notice_message.val() == default_messages['minimum_amount'] || notice_message.val() == default_messages['deadline'] ) {
			notice_message.val( '' );
		}
		<?php endif; ?>

		jQuery( '.chosen-container' ).css( 'width', '300px' );
	});

	// Ajax Chosen Product Selector
	jQuery( function() {
		jQuery( "select.ajax_chosen_select_products_and_variations" ).ajaxChosen( {
			method:   'GET',
			url:      '<?php echo admin_url( 'admin-ajax.php' ); ?>',
			dataType: 'json',
			afterTypeDelay: 100,
			data: {
				action:   'woocommerce_json_search_products_and_variations',
				security: '<?php echo wp_create_nonce( "search-products" ); ?>'
			}
		}, function( data ) {

			var terms = {};

			jQuery.each( data, function( i, val ) {
				terms[ i ] = val;
			} );

			return terms;
		} );
	} );

	// Chosen selects
	jQuery( "select.chosen_select" ).chosen();

	// Ajax Chosen Product Category Selector
	jQuery( function() {
		jQuery("select.ajax_chosen_select_product_categories").ajaxChosen({
			method:   'GET',
			url:      '<?php echo admin_url( 'admin-ajax.php' ); ?>',
			dataType: 'json',
			afterTypeDelay: 100,
			data: {
				action:   'wc_cart_notices_json_search_product_categories',
				security: '<?php echo wp_create_nonce( "search-product-categories" ); ?>'
			}
		}, function( data ) {

			var terms = {};

			jQuery.each( data, function( i, val ) {
				terms[ i ] = val;
			} );

			return terms;
		} );
	} );

	// Edit prompt
	jQuery( function() {
		var changed = false;

		jQuery( 'input, textarea, select, checkbox' ).change( function() {
			changed = true;
		} );

		window.onbeforeunload = function() {
			if ( changed )
				return 'The changes you made will be lost if you navigate away from this page.';
			return null;
		}

		jQuery( 'input[type=submit]' ).click( function() {
			window.onbeforeunload = '';
		});
	});

	// help tip handler
	jQuery( ".help_tip" ).tipTip();
	</script>
	<?php endif; ?>
</div>

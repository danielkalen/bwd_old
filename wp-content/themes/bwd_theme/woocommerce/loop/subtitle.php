<?php
/**
 * Loop Subtitle
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;
$product_size = get_post_meta( get_the_ID(), '_size', true ) . ' size, ';
$product_pack = get_post_meta( get_the_ID(), '_pack', true ) . ' per pack';

?>
<div class="products-item-content-subtitle"><?php echo $product_size . $product_pack; ?></div>
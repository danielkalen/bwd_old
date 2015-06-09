<?php
/**
 * Single Product title
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$product_size = get_post_meta( get_the_ID(), '_size', true ) . ' size, ';
$product_pack = get_post_meta( get_the_ID(), '_pack', true ) . ' per pack';

?>
<h1 itemprop="name" class="single-product-content-summary-title">
	<?php 
		the_title();
		echo '<span class="product_content"> (' . $product_size . $product_pack . ')</span>'; 
	?>
</h1>
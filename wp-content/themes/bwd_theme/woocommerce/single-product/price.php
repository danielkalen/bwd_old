<?php
/**
 * Single Product Price, including microdata for SEO
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;
$product_price_pack	 = substr($product->get_price_html(), 26);
$product_price_unit	 = intval($product_price_pack) / get_post_meta( get_the_ID(), '_pack', true );
$product_price_unit	 = '$' . round($product_price_unit, 2);
$product_price_pack	 = $product->get_price_html();
$onsale = false;
if ( strstr($product->get_price_html(), 'del') ) {
	$onsale = true;
}

?>
<div class="single-product-content-summary-actions">

		<div class="single-product-content-summary-actions-price<?php echo ($onsale ? ' onsale' : '') ?>" itemprop="offers" itemscope itemtype="http://schema.org/Offer">

			<p class="single-product-content-summary-actions-price-number pack"><?php echo $product_price_pack; ?> <span class="per">/pack</span></p>
			
			<?php if ( 'bwd' === site_version() ) { ?>
				<p class="single-product-content-summary-actions-price-number unit"><?php echo $product_price_unit; ?> <span class="per">/unit</span></p>
			<?php } ?>


			<meta itemprop="price" content="<?php echo $product->get_price(); ?>" />
			<meta itemprop="priceCurrency" content="<?php echo get_woocommerce_currency(); ?>" />
			<link itemprop="availability" href="http://schema.org/<?php echo $product->is_in_stock() ? 'InStock' : 'OutOfStock'; ?>" />

		</div>

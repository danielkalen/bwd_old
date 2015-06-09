<?php
/**
 * Product Loop Start
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

$list_class = ( is_product_category() || is_shop() ? 'category-list' : 'products' );
?>

<ul class="<?php echo $list_class; ?>">
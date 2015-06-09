<?php
/**
 * The template for displaying product category thumbnails within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product_cat.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $woocommerce_loop;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) )
	$woocommerce_loop['loop'] = 0;

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) )
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );

// Increase loop count
$woocommerce_loop['loop']++;
?>
<li class="category-list-item">

	<?php do_action( 'woocommerce_before_subcategory', $category ); ?>

	<a class="category-list-item-wrap" href="<?php echo get_term_link( $category->slug, 'product_cat' ); ?>">

		<div class="category-list-item-img">

				<?php
					/**
					 * woocommerce_before_subcategory_title hook
					 *
					 * @hooked woocommerce_subcategory_thumbnail - 10
					 */
					do_action( 'woocommerce_before_subcategory_title', $category );
				?>
		</div>

		<div class="category-list-item-content">
			<h3 class="category-list-item-content-title">
				<?php
					echo $category->name;
				?>
			</h3>
			<div class="category-list-item-content-count">
				<?php echo apply_filters( 'woocommerce_subcategory_count_html', '' . $category->count . ' Products', $category ); ?>
			</div>
			<div class="category-list-item-content-arrow"></div>
		</div>


		<?php
			/**
			 * woocommerce_after_subcategory_title hook
			 */
			do_action( 'woocommerce_after_subcategory_title', $category );
		?>

	</a>

	<?php do_action( 'woocommerce_after_subcategory', $category ); ?>

</li>

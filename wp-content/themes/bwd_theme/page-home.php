<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Snapmagnet
 */

get_header(); ?>

<div class="content-wrap left">
	<main id="main" class="content" role="main">

		<?php 
		// $loop = new WP_Query(array( 'post_type' => 'banner'));
		// echo get_post_thumbnail_id(642);
		$attachment_id = get_post_thumbnail_id(642);
		$url = wp_get_attachment_url( $attachment_id, 'large' );
		?>

		<img src="<?php echo $url ?>" alt="Featured" class="featured-img" width="786" height="296">
		<section class="product-slider featured">
			<h3 class="product-slider-title">Featured Products</h3>
			<div class="product-slider-controls">
				<div class="product-slider-controls-arrow left"></div>
				<div class="product-slider-controls-arrow right"></div>
			</div>
			<div class="product-slider-wrap">
				<div class="product-slider-wrap-wrapper">
					<?php echo do_shortcode('[featured_products per_page="8" columns="0"]'); ?>
				</div>
			</div>
		</section>
		<section class="product-slider recent">
			<h3 class="product-slider-title">Recent Products</h3>
			<div class="product-slider-controls">
				<div class="product-slider-controls-arrow left"></div>
				<div class="product-slider-controls-arrow right"></div>
			</div>
			<div class="product-slider-wrap">
				<div class="product-slider-wrap-wrapper">
					<?php echo do_shortcode('[recent_products per_page="8" columns="0"]'); ?>
				</div>
			</div>
		</section>

	</main><!-- #main -->
	<div class="sidebar-wrap">
		<div class="sidebar">
			<div class="sidebar-title">Shop</div>
			<?php get_sidebar(); ?>
		</div>
	</div>
</div>
<?php get_footer(); ?>

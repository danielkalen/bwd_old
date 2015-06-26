<?php
/**
 * The template for displaying the cart page.
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

			<?php do_action('account_menu'); ?>


			<?php while ( have_posts() ) : the_post(); ?>

				<?php the_content(); ?>

			<?php endwhile; // end of the loop. ?>

			<?php wc_get_template( 'myaccount/my-orders.php', array( 'order_count' => $order_count ) );
			?>

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


	</main><!-- #main -->

	<div class="sidebar-wrap">
		<div class="sidebar">
			<div class="sidebar-title">My Account</div>
			<?php get_sidebar(); ?>
		</div>
	</div>
</div>
<?php get_footer(); ?>

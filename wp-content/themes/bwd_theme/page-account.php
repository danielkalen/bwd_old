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

			<div class="mobile_menu-trigger">
				<div class="mobile_menu-trigger-text">Account Menu</div>
			</div>
			<div id="mobile_menu" class="mobile_menu">
				<div class="mobile_menu-close"></div>
				<h6 class="mobile_menu-title">Account Menu</h6>
				<ul class="mobile_menu-list">
					<?php //the_content();
						$menu_items = wp_get_nav_menu_items(48);
						foreach ($menu_items as $item) {
							if (isset($item->title)) {
								$title = $item->title;
							} else {
								$title = $item->post_title;
							}
							echo '<li class="mobile_menu-list-item">
									<a href="'.$item->url.'" class="mobile_menu-list-item-text">'.$title.'</a>
								 </li>';
						}
					?>
				</ul>
			</div>


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

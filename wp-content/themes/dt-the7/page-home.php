<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package presscore
 * @since presscore 1.0
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

$config = Presscore_Config::get_instance();
$config->set('template', 'page');
$config->base_init();

get_header(); ?>

		<?php if ( presscore_is_content_visible() ): ?>	

			<div id="content" class="content" role="main">


	<?php 
		echo get_the_post_thumbnail( 642, 'full' );
	?>

	<div class="scroller-title featured-dk">
		<h3 class="wpb_area_title">Featured Products</h3>
		<div class="owl-controls clickable"><div class="owl-buttons"><div class="owl-prev"><i class="fa fa-chevron-left"></i></div><div class="owl-next"><i class="fa fa-chevron-right"></i> </div></div></div>
	</div>

	<div class="home-scroller featured-scroller">
		<?php
			echo do_shortcode('[featured_products per_page="8" columns="4" orderby="date" order="desc"]');
		?>
	</div>

	
	<div class="promotion-divider first-divider">
		<div class="promotion_specials">a</div>
		<div class="promotion-link">View our <a href="/specials"><span class="highlight">monthly specials</span><i class="fa fa-chevron-right"></i></a></div>
	</div>

	<div class="promotion-divider second-divider">
		<div class="promotion_pdf">a</div>
		<div class="promotion-link">Download PDF Catalog <a href="#pdf_catalog"><span class="highlight">here</span><i class="fa fa-chevron-right"></i></a></div>
	</div>

	<div class="scroller-title recent-dk">
		<h3 class="wpb_area_title">Latest Products</h3>
		<div class="owl-controls clickable"><div class="owl-buttons"><div class="owl-prev"><i class="fa fa-chevron-left"></i></div><div class="owl-next"><i class="fa fa-chevron-right"></i> </div></div></div>
	</div>

	<div class="home-scroller recent-scroller">
		<?php
			echo do_shortcode('[recent_products per_page="8" columns="4" orderby="date" order="desc"]');
		?>
	</div>
	<a style="display:none;" href="https://plus.google.com/117146399947600227370" rel="publisher">Google+</a>
			</div><!-- #content -->

			<?php get_sidebar(); ?>

		<?php endif; // if content visible ?>

<?php get_footer(); ?>
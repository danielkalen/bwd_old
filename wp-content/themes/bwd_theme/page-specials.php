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

<div class="content-wrap right">
	<main id="main" class="content" role="main">

		<?php 

			// WP_Query arguments
			$args = array (
				'post_type'              => 'shop_coupon',
			);

			// The Query
			$query = new WP_Query( $args );



			echo '<ul class="specials">';

			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
				$query->the_post();
				echo '<li class="specials-item">
						<div class="specials-item-img">
							<div class="specials-item-img-text">
								'. get_the_excerpt() .'
							</div>
						</div>
						<div class="specials-item-content">
							<div class="specials-item-content-title">Promotion Code</div>
							<div class="specials-item-content-code">
								'. get_the_title() .'
							</div>
						</div>
					</li>';
				}
			} else {
				echo "We're sorry, there currently aren't any available specials. Please try visiting this page again at a later time.";
			}
			echo '</ul>';


		?>




	</main><!-- #main -->

	<div class="sidebar-wrap">
		<div class="sidebar">
			<div class="sidebar-title">Shop</div>
			<?php get_sidebar(); ?>
		</div>
	</div>
</div>
<?php get_footer(); ?>

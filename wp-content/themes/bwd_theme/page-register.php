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

<div class="content-wrap">
	<main id="main" class="content" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php the_content(); ?>

		<?php endwhile; // end of the loop. ?>

	</main><!-- #main -->
</div>
<?php get_footer(); ?>

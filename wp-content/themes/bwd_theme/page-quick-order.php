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

			<h1 class="page-title">Quick Order</h1>
			<p class="page-intro">Order products quickly by SKU, Description, or Product name.</p>

			<?php echo do_shortcode('[wcbulkorder]') ?>


	</main><!-- #main -->

	<div class="sidebar-wrap">
		<div class="sidebar">
			<div class="sidebar-title">My Account</div>
			<?php get_sidebar(); ?>
		</div>
	</div>
</div>
<?php get_footer(); ?>

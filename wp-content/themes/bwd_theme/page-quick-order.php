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

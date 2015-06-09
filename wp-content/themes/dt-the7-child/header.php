<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div class="wf-container wf-clearfix">
 *
 * @package presscore
 * @since presscore 0.1
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

?><!DOCTYPE html>

<html class="no-js" <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
	<title><?php echo presscore_blog_title(); ?></title>
	<?php if ( get_bloginfo('name') === 'OnlyOneStopShop' ) { ?>
		<link rel="icon" href="/wp-content/themes/dt-the7-child/img/favicon2.ico" type="image/x-icon">
	<?php } else { ?>
		<link rel="icon" href="/wp-content/themes/dt-the7-child/img/favicon1.ico" type="image/x-icon">
	<?php } ?>
	<!--[if IE]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<?php
	wp_head();
	?>

</head>

<body <?php body_class(); ?>>

<?php do_action( 'presscore_body_top' ); ?>

	<?php get_template_part( 'templates/header/top-bar', of_get_option('top_bar-content_alignment', 'side') ); ?>

<div class="page-container">
	<div class="mp-pusher" id="mp-pusher">

	<div id="mp-menu" class="mp-menu">
		<div class="mp-level">
			<h6 class="icon">Shop Categories</h6>
			<ul>
				<?php 
				$menu_object = wp_get_nav_menu_object( 'product-categories' );
				$menu_items = wp_get_nav_menu_items($menu_object->term_id);
				
				foreach ($menu_items as $key => $item) {
					if ( $item->post_parent === 0 ) {
						if ( $item->title !== 'Household' ) {
							echo '</ul></div></li>';
						}
						echo '
						<li class="icon icon-arrow-left">
							<a href="#" class="icon">'. $item->title .'</a>
							<div class="mp-level">
								<h6 class="icon icon-display">'. $item->title .'</h6>' .
								'<a class="mp-back" href="#">back</a>' . 
									'<ul>';
					} else {
										echo '<li><a href="' . $item->url . '">' . $item->title . '</a></li>';
					}
				}

				?>
				</ul></div></li>
			</ul>
		</div>
	</div>
<div class="scroller">
<div class="scroller-inner">
<div id="page"<?php if ( 'boxed' == of_get_option('general-layout', 'wide') ) echo ' class="boxed"'; ?>>


<?php if ( apply_filters( 'presscore_show_header', true ) ) : ?>

	<?php get_template_part( 'templates/header/header', of_get_option( 'header-layout', 'left' ) ); ?>

<?php endif; // show header ?>

	<?php do_action( 'presscore_before_main_container' ); ?>

	<div id="main" <?php presscore_main_container_classes(); ?>><!-- class="sidebar-none", class="sidebar-left", class="sidebar-right" -->

<?php if ( presscore_is_content_visible() ): ?>

		<div class="main-gradient"></div>

		<div class="wf-wrap">
			<div class="wf-container-main">

				<?php do_action( 'presscore_before_content' ); ?>

<?php endif; ?>

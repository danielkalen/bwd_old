<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Snapmagnet
 */
?><!DOCTYPE html>
<html itemscope="" itemtype="http://schema.org/WebPage" dir="ltr" <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<title><?php echo bwd_blog_title(); ?></title>
<?php if ( get_bloginfo('name') === 'OnlyOneStopShop' ) { ?>
	<link rel="icon" href="/wp-content/themes/bwd_theme/img/favicon2.ico" type="image/x-icon">
<?php } else { ?>
	<link rel="icon" href="/wp-content/themes/bwd_theme/img/favicon1.ico" type="image/x-icon">
<?php } ?>

<?php wp_head(); ?>

     <script type="text/javascript" src="http://localhost:48626/takana.js"></script>
     <script type="text/javascript">
       takanaClient.run({host: 'localhost:48626'});
     </script>

</head>

<body <?php body_class(); ?>>
<div class="top-bar">
	<div class="top-bar-wrap">
		<div class="top-bar-support">Customer Support - (800) 480-5887</div>
		<div class="top-bar-shipping">
			<div class="top-bar-shipping-amount amountremain">$120</div>
			<div class="top-bar-shipping-text">Remaining to Qualify for Free Shipping</div>
		</div>
		<div class="top-bar-links">
			<div class="top-bar-links-social">
				<a title="Twitter" target="_blank" href="http://twitter.com/shopperbarn" id="twitter-icon" class="top-bar-links-social-item twitter"></a>
				<a title="Facebook" target="_blank" href="http://facebook.com/shopperbarn" id="facebook-icon" class="top-bar-links-social-item facebook"></a>
				<a title="Pinterest" target="_blank" href="http://pinterest.com/shopperbarn" id="pinterest-icon" class="top-bar-links-social-item pinterest"></a>
				<a title="Google+" target="_blank" href="http://plus.google.com/shopperbarn" id="googleplus-icon" class="top-bar-links-social-item gplus"></a>
			</div>
			<div class="top-bar-links-account hover-trigger">
				<a class="top-bar-links-account-link" href="/account">My Account</a>
			    <div class="top-bar-links-account-box hover-box">
				    <?php echo do_shortcode("[userpro_loggedout]<a class='top-bar-links-account-box-item' href='/account/login'><span>Login</span></a><a class='top-bar-links-account-box-item' href='/account/register'><span>Register</span></a>[/userpro_loggedout][userpro_loggedin]<a class='top-bar-links-account-box-item' href='/account'><span>View Account</span></a><a class='top-bar-links-account-box-item' href='". wp_logout_url( home_url() ) ."'><span>Logout</span></a>[/userpro_loggedin]");?>
			    </div>
			</div>
			<div class="top-bar-links-cart hover-trigger">
				<a href="/cart" class="top-bar-links-cart-text">Cart <span class="top-bar-links-cart-text-icon"></span></a>
				<div class="top-bar-links-cart-box hover-box">
					<?php include('woocommerce/cart/mini-cart.php'); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="page" class="hfeed site">
	<div class="hover-overlay_mobile"></div>
	<header id="masthead" class="header" role="banner">
		<div class="header-wrap">
			<a href="/" class="header-logo">
			</a><!-- .site-branding -->
			<div class="header-search">
				<div class="header-search-mobile_menu_trigger"><div class="header-search-mobile_menu_trigger-text">Shop</div></div>
				<?php get_product_search_form() ?>
			</div>
		</div>

		<nav id="site-navigation" class="header-nav" role="navigation">
			<?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'menu_class' => 'header-nav-list'  ) ); ?>
		</nav><!-- #site-navigation -->
	</header><!-- #masthead -->
	<div id="menu-mega" class="menu-mega">
		<div class="menu-mega-close"></div>
		<h6 class="menu-mega-title">Shop Categories</h6>
		<ul class="menu-mega-list">
			<?php 
			$menu_object = wp_get_nav_menu_object( 'product-categories' );
			$menu_items = wp_get_nav_menu_items($menu_object->term_id);
			$menu_items = get_terms('product_cat', array('hide_empty' => 1, 'orderby' => 'ASC',  'parent' =>0));
			
			foreach ($menu_items as $item) {
					echo '
					<li class="menu-mega-list-item">
						<a href="'. get_term_link($item->slug, $item->taxonomy) .'" class="menu-mega-list-item-title"><span class="menu-mega-list-item-title-text">'. $item->name .'</span></a>
						<div class="menu-mega-list-item-level">
							<div class="menu-mega-list-item-level-back">Back</div>
							<a href="'.get_term_link($item->slug, $item->taxonomy).'" class="menu-mega-list-item-level-title">'. $item->name .'</a>' .
								'<ul class="menu-mega-list-item-level-submenu">';
								$menu_items_sub = array(
								   'hierarchical' => 1,
								   'show_option_none' => '',
								   'hide_empty' => 1,
								   'parent' => $item->term_id,
								   'taxonomy' => 'product_cat'
								);
								$menu_items_sub = get_categories($menu_items_sub);

								foreach ($menu_items_sub as $item_sub) {
									echo '<li class="menu-mega-list-item-level-submenu-item"><a class="menu-mega-list-item-level-submenu-item-link" href="' . get_term_link($item_sub->slug, $item_sub->taxonomy) . '">' . $item_sub->name . '</a></li>';
								}
						echo '
								</ul>
						</div>
					</li>';
				// }
			}

			?>
		</ul>
	</div>
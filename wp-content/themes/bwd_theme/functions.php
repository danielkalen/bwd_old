<?php
/**
 * Snapmagnet functions and definitions
 *
 * @package Snapmagnet
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}

if ( ! function_exists( 'bwd_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function bwd_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Snapmagnet, use a find and replace
	 * to change 'snapmagnet' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'snapmagnet', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'snapmagnet' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'snapmagnet_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif; // bwd_setup
add_action( 'after_setup_theme', 'bwd_setup' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */

require_once('inc/widget-cart.php');
require_once('inc/widget-checkout.php');
require_once('inc/widget-account.php');

function snapmagnet_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'snapmagnet' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'snapmagnet_widgets_init' );


function site_version() {
		$site = get_bloginfo('name');
		$version = 'sb';

		switch ( $site ) {
			case 'ShopperBarn':
				$siteurl = 'shopperbarn.com';
				$version = 'sb';
				break;

			case 'OnlyOneStopShop':
				$siteurl = 'onlyonestopshop.com';
				$version = 'oss';
				break;

			case 'BWD Wholesale':
				$siteurl = 'bwdny.com';
				$version = 'bwd';
				break;
		}

		return $version;
}

/**
 * Enqueue scripts and styles.
 */
function snapmagnet_scripts() {

	// website version css folder
		$site = get_bloginfo('name');
		$folder = '../bwd_theme/css/';
		$maincss = '/wp-content/themes/bwd_theme/style.css';

		switch ( $site ) {
			case 'ShopperBarn':
				$folder = '/wp-content/themes/bwd_theme/css/';
				$maincss = '/wp-content/themes/bwd_theme/style.css';
				$siteurl = 'shopperbarn.com';
				break;

			case 'OnlyOneStopShop':
				$folder = '/wp-content/themes/bwd_theme/css-oss/';
				$maincss = '/wp-content/themes/bwd_theme/style-oss.css';
				$siteurl = 'onlyonestopshop.com';
				break;

			case 'BWD Wholesale':
				$folder = '/wp-content/themes/bwd_theme/css/';
				$maincss = '/wp-content/themes/bwd_theme/style.css';
				$siteurl = 'bwdny.com';
				break;
		}


		/* ==========================================================================
		   Stylesheets
		   ========================================================================== */
		
		wp_enqueue_style( 'main', $maincss );

		if ( is_front_page() ) {
			wp_enqueue_style( 'home', $folder . 'home.css' );
		}

		if ( is_page('about')) {
			wp_enqueue_style( 'about', $folder . 'about.css' );
		}

		if ( is_page('contact')) {
			wp_enqueue_style( 'contact', $folder . 'contact.css' );
		}

		if ( is_page('specials')) {
			wp_enqueue_style( 'specials', $folder . 'specials.css' );
		}

		if ( is_cart() ) {
			wp_enqueue_style( 'cart', $folder . 'cart.css' );
		}

		if ( is_checkout() ) {
			wp_enqueue_style( 'checkout', $folder . 'checkout.css' );
		}
		if ( is_page('login') || is_page('register') ) {
			wp_enqueue_style( 'login-bwd', $folder . 'login.css' );
		}

		if ( is_page('account') || is_page('quick-order') || is_page('view-order') || is_page('request-return-form')|| is_page('wholesale-apply')|| is_page('edit') ) {
			wp_enqueue_style( 'account', $folder . 'account.css' );
		}

		if ( is_page('request-return-form') ) {
			wp_enqueue_style( 'account-return', $folder . 'account-return.css' );
		}

		if ( is_page('wholesale-apply') ) {
			wp_enqueue_style( 'account-wholesale', $folder . 'account-wholesale.css' );
		}


		// if ( is_checkout() || is_page('wholesale-apply') || is_page('request-return-form')) {
		// 	wp_enqueue_style( 'dk-checkout', $folder . 'checkout' );
		// }

		// if ( is_page('contact-us') || is_page(263) ) {
		// 	wp_enqueue_style( 'dk-contact', $folder . 'contact' );
		// 	wp_enqueue_style( 'dk-contact-form', $folder . 'contact-form' );
		// }

		// if ( is_404() || is_search() || is_product_category()) {
		// 	wp_enqueue_style( 'dk-error404', $folder . 'error404' );
		// }

		// if ( is_page('login') || is_page('register') || is_page('edit')) {
		// 	wp_enqueue_style( 'dk-login', $folder . 'login' );
		// }

		// if ( is_page('specials')) {
		// 	wp_enqueue_style( 'dk-specials', $folder . 'specials' );
		// }

		// if ( is_page('account') || is_page('quick-order') || is_page('view-order') || is_cart() || is_page('checkout-2')) {
		// 	wp_enqueue_style( 'dk-specials', $folder . 'table' );
		// }



		/* ==========================================================================
		   JS Files
		   ========================================================================== */
		
		wp_enqueue_script( 'global', '/wp-content/themes/bwd_theme/js/main.min.js', array('jquery'), false, true );

		if ( is_front_page() ) {
			wp_enqueue_script( 'home', '/wp-content/themes/bwd_theme/js/home.min.js', array('jquery'), false, true );
		}

		if ( is_shop() || is_product_category() ) {
			wp_enqueue_script( 'products-archive', '/wp-content/themes/bwd_theme/js/products-archive.min.js', array('jquery'), false, true );
		}

		if ( is_woocommerce() || is_cart() ) {
			wp_enqueue_script( 'products-global', '/wp-content/themes/bwd_theme/js/products-global.min.js', array('jquery'), false, true );
		}

		if ( is_cart() ) {
			wp_enqueue_script( 'cart', '/wp-content/themes/bwd_theme/js/cart.min.js', array('jquery'), false, true );
		}

		if ( is_checkout() ) {
			wp_enqueue_script( 'checkout', '/wp-content/themes/bwd_theme/js/checkout.min.js', array('jquery'), false, true );
		}

		if ( is_page('login') || is_page('register') ) {
			wp_enqueue_script( 'login-bwd', '/wp-content/themes/bwd_theme/js/login.min.js', array('jquery'), false, true );
		}

		if ( is_page('account') || is_page('quick-order') || is_page('view-order') || is_page('request-return-form')|| is_page('wholesale-apply')|| is_page('edit') ) {
			wp_enqueue_script( 'login-bwd', '/wp-content/themes/bwd_theme/js/account.min.js', array('jquery'), false, true );
		}

		if ( is_page('about')) {
			wp_enqueue_script( 'about', '/wp-content/themes/bwd_theme/js/about.min.js', array('jquery'), false, true );
		}

		if ( is_page('contact')) {
			wp_enqueue_script( 'contact', '/wp-content/themes/bwd_theme/js/contact.min.js', array('jquery'), false, true );
		}

		if ( is_page('edit')) {
			wp_enqueue_script( 'account-edit', '/wp-content/themes/bwd_theme/js/account-edit.min.js', array('jquery'), false, true );
		}

		if ( is_page('request-return-form')) {
			wp_enqueue_script( 'account-return', '/wp-content/themes/bwd_theme/js/account-return.min.js', array('jquery'), false, true );
		}

		if ( is_page('wholesale-apply')) {
			wp_enqueue_script( 'account-wholesale', '/wp-content/themes/bwd_theme/js/account-wholesale.min.js', array('jquery'), false, true );
		}

		// Remove stupid Emoji
			wp_dequeue_script( 'emoji' );
			remove_action( 'wp_print_styles', 'print_emoji_styles' );
			remove_action( 'wp_print_scripts', 'print_emoji_detection_script' );
			remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
			remove_action( 'admin_print_styles', 'print_emoji_styles');
			remove_action( 'admin_print_scripts','print_emoji_detection_script');

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'snapmagnet_scripts', 0 );


if ( ! function_exists( 'bwd_blog_title' ) ) :

	/**
	 * Display blog title.
	 *
	 */
	function bwd_blog_title() {
		$wp_title = wp_title('', false);
		$title = get_bloginfo('name') . ' | ';
		$title .= (is_front_page()) ? get_bloginfo('description') : $wp_title;

		return apply_filters( 'bwd_blog_title', $title, $wp_title );
	}

endif; // bwd_blog_title



if ( ! function_exists( 'bwd_body_class' ) ) :

	/**
	 * Add theme speciffik classes to body.
	 *
	 * @since bwd 1.0
	 */
	function bwd_body_class( $classes ) {
		global $post;
		$site = get_bloginfo('name');

		// website version classname
		switch ( $site ) {
			case 'ShopperBarn':
				$classes[] = 'sb';
				break;

			case 'OnlyOneStopShop':
				$classes[] = 'oss';
				break;

			case 'BWD Wholesale':
				$classes[] = 'bwd';
				break;
		}

		return array_values( array_unique( $classes ) );
	}

endif; // bwd_body_class

add_filter( 'body_class', 'bwd_body_class' );



/**
* Optimize WooCommerce Scripts
* Remove WooCommerce Generator tag, styles, and scripts from non WooCommerce pages.
*/
add_action( 'wp_enqueue_scripts', 'child_manage_woocommerce_styles', 99 );
 
function child_manage_woocommerce_styles() {
	//remove generator meta tag
	remove_action( 'wp_head', array( $GLOBALS['woocommerce'], 'generator' ) );
 
	//first check that woo exists to prevent fatal errors
	if ( function_exists( 'is_woocommerce' ) ) {
	//dequeue scripts and styles
		wp_dequeue_style( 'woocommerce-general' );
		wp_dequeue_style( 'woocommerce-layout' );
		wp_dequeue_style( 'woocommerce-smallscreen' );
		wp_dequeue_style( 'woocommerce_frontend_styles' );
		wp_dequeue_style( 'woocommerce_fancybox_styles' );
		wp_dequeue_style( 'woocommerce_chosen_styles' );
		wp_dequeue_style( 'select2' );
		wp_dequeue_script( 'select2' );
		wp_dequeue_script( 'wc-chosen' );
		if ( ! is_woocommerce() && ! is_cart() && ! is_checkout() ) {
			wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
			wp_dequeue_script( 'wc_price_slider' );
			// wp_dequeue_script( 'wc-single-product' );
			// wp_dequeue_script( 'wc-add-to-cart' );
			// wp_dequeue_script( 'wc-cart-fragments' );
			wp_dequeue_script( 'wc-checkout' );
			wp_dequeue_script( 'wc-add-to-cart-variation' );
			wp_dequeue_script( 'wc-single-product' );
			// wp_dequeue_script( 'wc-cart' );
			// wp_dequeue_script( 'woocommerce' );
			wp_dequeue_script( 'prettyPhoto' );
			wp_dequeue_script( 'prettyPhoto-init' );
			// wp_dequeue_script( 'jquery-blockui' );
			wp_dequeue_script( 'jquery-placeholder' );
			wp_dequeue_script( 'fancybox' );
			wp_dequeue_script( 'jqueryui' );
		}
	}
 
}

// WooCommerce Display Empty Categories
add_filter('woocommerce_product_subcategories_args', 'woocommerce_show_empty_categories');
function woocommerce_show_empty_categories($cat_args){
$cat_args['hide_empty']=0;
return $cat_args;
}

// Display cateogry title in loop archives
function wc_category_title_archive_products(){

    $product_cats = wp_get_post_terms( get_the_ID(), 'product_cat' );

    if ( $product_cats && is_product_category() && !is_search() && ! is_wp_error ( $product_cats ) ){

     //   $single_cat = array_shift( $product_cats ); ?>

<?php }

	//elseif ( is_search() ) { 
	//	echo '<h2 class="wpb_area_title category-title">Search Results</h2>';
	//}
}
add_action( 'woocommerce_before_shop_loop', 'wc_category_title_archive_products', 5 );


remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );
 
function woo_remove_product_tabs( $tabs ) {
 
    unset( $tabs['description'] );      	// Remove the description tab
    //unset( $tabs['reviews'] ); 			// Remove the reviews tab
    unset( $tabs['additional_information'] );  	// Remove the additional information tab
 
    return $tabs;
 
}


// Remove WooCommerce Updater 
remove_action('admin_notices', 'woothemes_updater_notice'); 
// Remove IgniteWoo Updater 
remove_action('admin_notices', 'ignitewoo_updater_notice');


function wholesale_role_style() {
	
	$current_user = new WP_User(wp_get_current_user()->ID);
	$user_roles = $current_user->roles;
	$current_role = get_option('wwo_wholesale_role');
	foreach ($user_roles as $roles) {
		if ($roles == $current_role ){
			body_class( 'is-wholesale' ); } else { body_class();
		}
	}
}
add_filter( 'install_wholesale_styling', 'wholesale_role_style' );

add_filter( 'loop_shop_per_page', create_function( '$cols', 'return 12;' ), 20 );

add_filter( 'woocommerce_enqueue_styles', '__return_false' );



function GetCertainPostTypes($query) {
    if ($query->is_search) {
        $query->set('post_type',array('product'));
    }
return $query;
}
add_filter('pre_get_posts','GetCertainPostTypes');







// Display Fields
add_action( 'woocommerce_product_options_general_product_data', 'woo_add_custom_general_fields' );

// Save Fields
add_action( 'woocommerce_process_product_meta', 'woo_add_custom_general_fields_save' );

function woo_add_custom_general_fields() {
 
  global $woocommerce, $post;
  
  echo '<div class="options_group">';
  
	// Text Field
	woocommerce_wp_text_input( 
		array( 
			'id'          => '_size', 
			'label'       => __( 'Size', 'woocommerce' ), 
			'placeholder' => 'http://',
			'desc_tip'    => 'false',
			'description' => __( 'Enter the custom value here.', 'woocommerce' ) 
		)
	);
	// Text Field
	woocommerce_wp_text_input( 
		array( 
			'id'          => '_pack', 
			'label'       => __( 'Pack', 'woocommerce' ), 
			'placeholder' => 'http://',
			'desc_tip'    => 'false',
			'description' => __( 'Enter the custom value here.', 'woocommerce' ) 
		)
	);
  
  echo '</div>';
	
}




function woo_add_custom_general_fields_save( $post_id ){
	
	// Size
	$woocommerce_size = $_POST['_size'];
	if( !empty( $woocommerce_size ) )
		update_post_meta( $post_id, '_size', esc_attr( $woocommerce_size ) );
	
	// Pack
	$woocommerce_pack = $_POST['_pack'];
	if( !empty( $woocommerce_pack ) )
		update_post_meta( $post_id, '_pack', esc_attr( $woocommerce_pack ) );
	
}

function account_mobile_menu(){
	echo '
			<div class="mobile_menu-trigger">
				<div class="mobile_menu-trigger-text">Account Menu</div>
			</div>

			<div id="mobile_menu" class="mobile_menu">
				<div class="mobile_menu-close"></div>
				<h6 class="mobile_menu-title">Account Menu</h6>
				<ul class="mobile_menu-list">';
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
	echo		'</ul>
			</div>';
}
add_action('account_menu', 'account_mobile_menu');

require_once('inc/checkout_fields.php');

/**
 * Implement the Custom Header feature.
 */
//require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

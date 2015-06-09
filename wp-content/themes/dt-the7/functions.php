<?php
/**
 * presscore functions and definitions.
 *
 * @package presscore
 * @since presscore 0.1
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since presscore 0.1
 */
if ( ! isset( $content_width ) ) {
	$content_width = 1200; /* pixels */
}

/**
 * Theme init file.
 *
 */
require( get_template_directory() . '/inc/init.php' );

if ( ! function_exists( 'presscore_load_text_domain' ) ) :

	function presscore_load_text_domain() {
		/**
		 * Make theme available for translation
		 * Translations can be filed in the /languages/ directory
		 * If you're building a theme based on presscore, use a find and replace
		 * to change LANGUAGE_ZONE to the name of your theme in all the template files
		 */
		load_theme_textdomain( LANGUAGE_ZONE, get_template_directory() . '/languages' );
	}

endif;

add_action( 'after_setup_theme', 'presscore_load_text_domain', 15 );

if ( ! function_exists( 'presscore_setup' ) ) :

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which runs
	 * before the init hook. The init hook is too late for some features, such as indicating
	 * support post thumbnails.
	 *
	 * @since presscore 1.0
	 */
	function presscore_setup() {

		/**
		 * Editor style.
		 */
		add_editor_style();

		/**
		 * Add default posts and comments RSS feed links to head
		 */
		// add_theme_support( 'automatic-feed-links' );

		/**
		 * Enable support for Post Thumbnails
		 */
		add_theme_support( 'post-thumbnails' );

		/**
		 * This theme uses wp_nav_menu() in one location.
		 */
		register_nav_menus( array(
			'primary' 	=> __( 'Primary Menu', LANGUAGE_ZONE ),
			'top'		=> __( 'Top Menu', LANGUAGE_ZONE ),
			'bottom'	=> __( 'Bottom Menu', LANGUAGE_ZONE ),
		) );

		/**
		 * Enable support for Post Formats
		 */
		add_theme_support( 'post-formats', array( 'aside', 'image', 'link', 'gallery', 'status' ) );

		/**
		 * Allow shortcodes in widgets.
		 *
		 */
		add_filter( 'widget_text', 'do_shortcode' );

		// create upload dir
		wp_upload_dir();

		/**
		 * Include core functions.
		 *
		 */
		require_once( PRESSCORE_EXTENSIONS_DIR . '/core-functions.php' );

		/**
		 * Include stylesheet related functions.
		 *
		 */
		require_once( PRESSCORE_EXTENSIONS_DIR . '/stylesheet-functions.php' );

		/**
		 * Include options framework if it is not installed like plugin.
		 *
		 */
		if ( !defined('OPTIONS_FRAMEWORK_VERSION') ) {

			// Base
			require_once( PRESSCORE_EXTENSIONS_DIR . '/options-framework/options-framework.php' );

			if ( current_user_can( 'edit_theme_options' ) ) {

				// add theme options
				add_filter( 'options_framework_location', 'presscore_add_theme_options' );
			}
		}

		/**
		 * Include custom post typest.
		 *
		 */
		require_once( PRESSCORE_DIR . '/post-types.php' );

		if ( !class_exists('Mobile_Detect') ) {

			/**
			 * Mobile detection library.
			 *
			 */
			require_once( PRESSCORE_EXTENSIONS_DIR . '/mobile-detect.php' );

		}

		/**
		 * Some additional classes ( remove in future ).
		 *
		 */
		require_once( PRESSCORE_CLASSES_DIR . '/tags.class.php' );

		/**
		 * Include helpers.
		 *
		 */
		require_once( PRESSCORE_DIR . '/helpers.php' );

		$current_page_name = dt_get_current_page_name();
		$is_backend = is_admin() || dt_is_login_page();

		if ( function_exists('vc_is_inline') && vc_is_inline() ) {
			$is_backend = false;
		}

		/**
		 * Include admin functions.
		 *
		 */
		if ( $is_backend && is_admin() ) {

			/**
			 * Include the TGM_Plugin_Activation class.
			 */
			require_once( PRESSCORE_EXTENSIONS_DIR . '/class-tgm-plugin-activation.php' );

			// include only for theme update page
			if ( 'admin.php' == $current_page_name && !empty($_GET['page']) && 'of-themeupdate-menu' == $_GET['page'] ) {

				/**
				 * Update library.
				 *
				 */
				require_once( PRESSCORE_EXTENSIONS_DIR . '/envato-wordpress-toolkit-library/class-envato-wordpress-theme-upgrader.php' );
			}

			/**
			 * Attach metaboxes.
			 *
			 */
			// require_once( PRESSCORE_EXTENSIONS_DIR . '/meta-box.php' );
			if ( $located_file = locate_template( 'inc/admin/metaboxes.php' ) ) {
				// include_once( $located_file );
			}

			require_once( PRESSCORE_ADMIN_DIR . '/admin-functions.php' );

		} else if ( !$is_backend ) {

			/**
			 * Include AQResizer.
			 *
			 */
			require_once( PRESSCORE_EXTENSIONS_DIR . '/aq_resizer.php' );

			/**
			 * Include custom menu.
			 *
			 */
			require_once( PRESSCORE_EXTENSIONS_DIR . '/core-menu.php' );

			/**
			 * Include template actions and filters.
			 *
			 */
			require_once( PRESSCORE_DIR . '/template-tags.php' );

			/**
			 * Include paginator.
			 *
			 */
			require_once( PRESSCORE_EXTENSIONS_DIR . '/dt-pagination.php' );

		}

		/**
		 * Include widgets.
		 *
		 */

		/* Widgets list */
		$presscore_widgets = array(
			'custom-menu-1.php',
			'custom-menu-2.php',
			'blog-categories.php',
			'blog-posts.php',
		);

		$presscore_widgets = apply_filters( 'presscore_widgets', $presscore_widgets );

		// include widgets only for frontend and widgets admin page
		if ( $presscore_widgets && ( in_array($current_page_name, array('widgets.php', 'admin-ajax.php', 'themes.php')) || !$is_backend ) ) {

			foreach ( $presscore_widgets as $presscore_widget ) {

				if ( $file_path = locate_template( 'inc/widgets/' . $presscore_widget ) ) {
					require_once( $file_path );
				}
			}

		}

		// List of shortcodes folders to include
		// All folders located in /include
		$presscore_shortcodes = array(
			'gallery'
		);
		$presscore_shortcodes = apply_filters( 'presscore_shortcodes', $presscore_shortcodes );


		// include shortcodes only for frontend and post admin pages
		if ( $presscore_shortcodes && ( in_array( $current_page_name, array('post.php', 'post-new.php', 'admin-ajax.php') ) || !$is_backend ) ) {

			/**
			 * Setup shortcodes.
			 *
			 */
			require_once( PRESSCORE_SHORTCODES_DIR . '/setup.php' );

			foreach ( $presscore_shortcodes as $shortcode_dirname ) {
				include_once( PRESSCORE_SHORTCODES_INCLUDES_DIR . '/' . $shortcode_dirname . '/functions.php' );
			}
		}

		// include only for nav menu page
		include( PRESSCORE_CLASSES_DIR . '/mega-menu.class.php' );
		$mega_menu = new Dt_Mega_menu();

		/**
		 * Add woocommerce support.
		 *
		 */
		if ( class_exists( 'Woocommerce' ) && $file_path = locate_template( 'inc/mod-woocommerce/mod-woocommerce.php' ) ) {
			require_once( $file_path );
		}


		if ( defined('W3TC') && W3TC && defined('W3TC_DYNAMIC_SECURITY') && W3TC_DYNAMIC_SECURITY ) {

			/**
			 * Total Cache settings.
			 *
			 */
			require_once( PRESSCORE_DIR . '/mod-totalcache/mod-totalcache.php' );
		}

		if ( function_exists('wp_cache_is_enabled') && wp_cache_is_enabled() && function_exists('add_cacheaction') ) {

			/**
			 * Super cache settings.
			 *
			 */
			require_once( PRESSCORE_DIR . '/mod-supercache/mod-supercache.php' );

		}

		if ( class_exists('PG_Walker_Nav_Menu_Edit_Custom') ) {

			require_once( PRESSCORE_DIR . '/mod-private-content/mod-private-content.php' );
		}

		/////////////
		// Jetpack //
		/////////////

		if ( class_exists( 'Jetpack', false ) ) {
			include_once locate_template( 'inc/mod-jetpack/mod-jetpack.php' );
		}

	}

endif; // presscore_setup

add_action( 'after_setup_theme', 'presscore_setup', 15 );


/**
 * Set theme options path.
 *
 */
function presscore_add_theme_options() {
	return array( 'inc/admin/options.php' );
}


if ( ! function_exists( 'presscore_add_presets' ) ) :

	/**
	 * Add theme options presets.
	 *
	 */
	function presscore_add_presets( $presets = array() ) {
		// noimage - /images/noimage_small.jpg

		$theme_presets = array(
			'new6'			=> array( 'src' => '/inc/presets/icons/new6.jpg', 'title' => '' ), // iOS minimal
			'skin1'			=> array( 'src' => '/inc/presets/icons/skin1.jpg', 'title' => '' ), // Light
			'skin3'			=> array( 'src' => '/inc/presets/icons/skin3.jpg', 'title' => '' ), // Dark
			'skin5'			=> array( 'src' => '/inc/presets/icons/skin5.jpg', 'title' => '' ), // Polygonal
			'skin6'			=> array( 'src' => '/inc/presets/icons/skin6.jpg', 'title' => '' ), // Sepia
			'skin4'			=> array( 'src' => '/inc/presets/icons/skin4.jpg', 'title' => '' ), // Jeans
			'new1'			=> array( 'src' => '/inc/presets/icons/new1.jpg', 'title' => '' ), // Minimal
			'new3'			=> array( 'src' => '/inc/presets/icons/new3.jpg', 'title' => '' ), // Spring
			'new2'			=> array( 'src' => '/inc/presets/icons/new2.jpg', 'title' => '' ), // Aquamarine
			'skin2'			=> array( 'src' => '/inc/presets/icons/skin2.jpg', 'title' => '' ), // Striped
			'new4'			=> array( 'src' => '/inc/presets/icons/new4.jpg', 'title' => '' ), // Purple-Peak
			'new5'			=> array( 'src' => '/inc/presets/icons/new5.jpg', 'title' => '' ), // Сobalt
		);

		return array_merge( $presets, $theme_presets );
	}

endif;

add_filter( 'optionsframework_get_presets_list', 'presscore_add_presets', 15 );


if ( ! function_exists('presscore_set_first_run_skin') ) :

	/**
	 * Set first run skin.
	 *
	 */
	function presscore_set_first_run_skin( $skin_name = '' ) {
		return 'skin1';
	}

endif; // presscore_set_first_run_skin

add_filter( 'options_framework_first_run_skin', 'presscore_set_first_run_skin' );


function presscore_options_black_list( $fields = array() ) {

	$fields_black_list = array(

		// general
		'general-hd_images',
		'general-next_prev_in_blog',
		'general-next_prev_in_portfolio',
		'general-show_author_in_blog',
		'general-tracking_code',

		'general-favicon',
		'general-favicon_hd',

		'general-handheld_icon-old_iphone',
		'general-handheld_icon-old_ipad',
		'general-handheld_icon-retina_iphone',
		'general-handheld_icon-retina_ipad',

		'general-show_rel_posts',
		'general-rel_posts_head_title',
		'general-rel_posts_max',
		'general-show_rel_projects',
		'general-rel_projects_head_title',
		'general-rel_projects_max',
		'general-rel_projects_meta',
		'general-rel_projects_title',
		'general-rel_projects_excerpt',
		'general-rel_projects_link',
		'general-rel_projects_details',
		'general-rel_projects_fullwidth_height',
		'general-rel_projects_fullwidth_width_style',
		'general-rel_projects_fullwidth_width',
		'general-rel_projects_height',
		'general-rel_projects_width_style',
		'general-rel_projects_width',
		'general-blog_meta_on',
		'general-blog_meta_postformat',
		'general-blog_meta_date',
		'general-blog_meta_author',
		'general-blog_meta_categories',
		'general-blog_meta_comments',
		'general-blog_meta_tags',
		'general-portfolio_meta_on',
		'general-portfolio_meta_date',
		'general-portfolio_meta_author',
		'general-portfolio_meta_categories',
		'general-portfolio_meta_comments',
		'general-smooth_scroll',
		'general-woocommerce_show_mini_cart_in_top_bar',

		'general-title_align',
		'general-contact_form_send_mail_to',
		'general-post_type_portfolio_slug',

		// top bar
		'top_bar-show',
		'top_bar-contact_show',
		'top_bar-contact_address',
		'top_bar-contact_phone',
		'top_bar-contact_email',
		'top_bar-contact_skype',
		'top_bar-contact_clock',
		'top_bar-contact_info',
		'top_bar-text',
		'header-soc_icons',

		// header
		'header-show_floating_menu',
		'header-search_show',
		'header-contentarea',
		'header-submenu_parent_clickable',

		// bottom bar
		'bottom_bar-copyrights',
		'bottom_bar-credits',
		'bottom_bar-text',

		// Share buttons
		'social_buttons-post',
		'social_buttons-portfolio_post',
		'social_buttons-photo',
		'social_buttons-page',

		// widgetareas
		'widgetareas',

		// export & import
		'import_export',

		// update
		'theme_update-user_name',
		'theme_update-api_key'

	);

	return array_unique( array_merge( $fields, $fields_black_list ) );
}
add_filter( 'optionsframework_fields_black_list', 'presscore_options_black_list' );
add_filter( 'optionsframework_validate_preserve_fields', 'presscore_options_black_list', 14 );

if ( ! function_exists( 'presscore_themeoption_preserved_fields' ) ) :

	function presscore_themeoption_preserved_fields( $fields = array() ) {

		$preserved_fields = array(

			// header logo
			'header-logo_regular',
			'header-logo_hd',

			// bottom logo
			'bottom_bar-logo_regular',
			'bottom_bar-logo_hd',

			// floating logo
			'general-floating_menu_show_logo',
			'general-floating_menu_logo_regular',
			'general-floating_menu_logo_hd',

			// page title
			'general-show_breadcrumbs',

			// menu icons dimentions
			'header-icons_size',
			'header-submenu_icons_size',
			'header-submenu_next_level_indicator',
			'header-next_level_indicator',
		);

		return array_unique( array_merge( $fields, $preserved_fields ) );
	}

endif; // presscore_themeoption_preserved_fields

add_filter( 'optionsframework_validate_preserve_fields', 'presscore_themeoption_preserved_fields', 15 );


/**
 * Flush your rewrite rules.
 *
 */
function presscore_flush_rewrite_rules() {

	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'presscore_flush_rewrite_rules' );




if ( ! function_exists('presscore_widgets_init') ) :

	/**
	 * Register widgetized area and
	 *
	 * @since presscore 0.1
	 */
	function presscore_widgets_init() {

		if ( function_exists('of_get_option') ) {

			$w_params = array(
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget' 	=> '</section>',
				'before_title' 	=> '<div class="widget-title">',
				'after_title'	=> '</div>'
			);

			$w_areas = apply_filters( 'presscore_widgets_init-sidebars', of_get_option( 'widgetareas', false ) );

			if ( !empty( $w_areas ) && is_array( $w_areas ) ) {

				$prefix = 'sidebar_';

				foreach( $w_areas as $sidebar_id=>$sidebar ) {

					$sidebar_args = array(
						'name' 			=> isset( $sidebar['sidebar_name'] ) ? $sidebar['sidebar_name'] : '',
						'id' 			=> $prefix . $sidebar_id,
						'description' 	=> isset( $sidebar['sidebar_desc'] ) ? $sidebar['sidebar_desc'] : '',
						'before_widget' => $w_params['before_widget'],
						'after_widget' 	=> $w_params['after_widget'],
						'before_title' 	=> $w_params['before_title'],
						'after_title'	=> $w_params['after_title'] 
					);

					$sidebar_args = apply_filters( 'presscore_widgets_init-sidebar_args', $sidebar_args, $sidebar_id, $sidebar );

					register_sidebar( $sidebar_args );
				}

			}

		}
	}

endif; // presscore_widgets_init

add_action( 'widgets_init', 'presscore_widgets_init' );


if ( ! function_exists( 'presscore_enqueue_scripts' ) ) :

	/**
	 * Enqueue scripts and styles.
	 */
	function presscore_enqueue_scripts() {

		// website version css folder
		$site = get_bloginfo('name');
		$folder = '../dt-the7-child/css/css/';
		$maincss = '/wp-content/themes/dt-the7-child/style.css';

		switch ( $site ) {
			case 'ShopperBarn':
				$folder = '../dt-the7-child/css/css/';
				$maincss = '/wp-content/themes/dt-the7-child/style.css';
				break;

			case 'OnlyOneStopShop':
				$folder = '../dt-the7-child/css/css-oss/';
				$maincss = '/wp-content/themes/dt-the7-child/style-oss.css';
				break;

			case 'BWD Wholesale':
				$folder = '../dt-the7-child/css/css/';
				$maincss = '/wp-content/themes/dt-the7-child/style.css';
				break;
		}

		// if (is_woocommerce() || is_page('checkout') || is_page('cart') || is_page('account') || is_page('quick-order') || is_page('view-order')|| is_search()  ) {
		if ( !is_front_page() ) {
			presscore_enqueue_theme_stylesheet( 'dk-brands', $folder . 'brands-styles' );
			presscore_enqueue_theme_stylesheet( 'dk-woocommerce', $folder . 'woocommerce' );
			presscore_enqueue_theme_stylesheet( 'dk-dt-custom', $folder . 'wc-dt-custom' );
		}

		if ( is_page(32) ) {
			presscore_enqueue_theme_stylesheet( 'card', 'css/card' );
			presscore_enqueue_theme_script( 'card', 'js/card' );
			presscore_enqueue_theme_script( 'accordion-checkout', 'js/accordion-checkout' );
		}

		if ( is_page('wholesale-apply') ) {
			presscore_enqueue_theme_script( 'accordion-form', 'js/accordion-form' );
		}
	
		if ( is_page('us')) {
			presscore_enqueue_theme_script( 'dk-gallery', 'js/gallery.min' );
		}

		if ( is_page('login') || is_page('register') || is_page('wholesale-apply') || is_checkout()) {
			presscore_enqueue_theme_script( 'dk-login', 'js/login' );
			presscore_enqueue_theme_stylesheet( 'dk-modal', $folder . 'login-modal' );
		}

		if ( is_page('account') || is_page('quick-order') || is_page('edit') || is_page('request-return-form') || is_page('wholesale-apply') || is_page('view-order') || is_page('checkout-2')) {
			presscore_enqueue_theme_stylesheet( 'dk-account', $folder . 'account' );
		}

		if ( is_cart() || is_checkout() ) {
			presscore_enqueue_theme_stylesheet( 'dk-cart', $folder . 'cart' );
			presscore_enqueue_theme_stylesheet( 'dk-cart-empty', $folder . 'cart-empty' );
		}

		if ( is_checkout() || is_page('wholesale-apply') || is_page('request-return-form')) {
			presscore_enqueue_theme_stylesheet( 'dk-checkout', $folder . 'checkout' );
		}

		if ( is_page('contact-us') || is_page(263) ) {
			presscore_enqueue_theme_stylesheet( 'dk-contact', $folder . 'contact' );
			presscore_enqueue_theme_stylesheet( 'dk-contact-form', $folder . 'contact-form' );
		}

		if ( is_404() || is_search() || is_product_category()) {
			presscore_enqueue_theme_stylesheet( 'dk-error404', $folder . 'error404' );
		}

		if ( is_page('login') || is_page('register') || is_page('edit')) {
			presscore_enqueue_theme_stylesheet( 'dk-login', $folder . 'login' );
		}

		if ( is_page('specials')) {
			presscore_enqueue_theme_stylesheet( 'dk-specials', $folder . 'specials' );
		}

		if ( is_page('account') || is_page('quick-order') || is_page('view-order') || is_cart() || is_page('checkout-2')) {
			presscore_enqueue_theme_stylesheet( 'dk-specials', $folder . 'table' );
		}

		// theme stylesheet
		// wp_enqueue_style( 'style', get_stylesheet_uri(), array(), wp_get_theme()->get( 'Version' ) );
		wp_enqueue_style( 'style', $maincss, array(), wp_get_theme()->get( 'Version' ) );


		// main.js
		presscore_enqueue_theme_script( 'dt-main', 'js/main.min' );


		/////////////////////
		// Localize data //
		/////////////////////

		$config = Presscore_Config::get_instance();
		// $config->set( 'device_type', $device_type );

		if ( is_page() ) {
			$page_data = array(
				'type' => 'page',
				'template' => $config->get('template'),
				'layout' => $config->get('justified_grid') ? 'jgrid' : $config->get('layout')
			);
		} else if ( is_archive() ) {
			$page_data = array(
				'type' => 'archive',
				'template' => $config->get('template'),
				'layout' => $config->get('justified_grid') ? 'jgrid' : $config->get('layout')
			);
		} else if ( is_search() ) {
			$page_data = array(
				'type' => 'search',
				'template' => $config->get('template'),
				'layout' => $config->get('justified_grid') ? 'jgrid' : $config->get('layout')
			);
		} else {
			$page_data = false;
		}

		global $post;


	}

endif; // presscore_enqueue_scripts

add_action( 'wp_enqueue_scripts', 'presscore_enqueue_scripts', 15 );




if ( ! function_exists( 'presscore_admin_scripts' ) ) :

	/**
	 * Add metaboxes scripts and styles.
	 */
	function presscore_admin_scripts( $hook ) {
		if ( !in_array( $hook, array( 'post-new.php', 'post.php' ) ) ) {
			return;
		}

		$template_uri = get_template_directory_uri();

		wp_enqueue_style( 'dt-mb-magick', $template_uri . '/inc/admin/assets/admin_mbox_magick.css' );

		wp_enqueue_script( 'dt-metaboxses-scripts', $template_uri . '/inc/admin/assets/custom-metaboxes.js', array('jquery'), false, true );
		wp_enqueue_script( 'dt-mb-magick', $template_uri . '/inc/admin/assets/admin_mbox_magick.js', array('jquery'), false, true );
		wp_enqueue_script( 'dt-mb-switcher', $template_uri . '/inc/admin/assets/admin_mbox_switcher.js', array('jquery'), false, true );

		// for proportion ratio metabox field
		$proportions = presscore_meta_boxes_get_images_proportions();
		$proportions['length'] = count( $proportions );
		wp_localize_script( 'dt-metaboxses-scripts', 'rwmbImageRatios', $proportions );
	}

endif; // presscore_admin_scripts

add_action( 'admin_enqueue_scripts', 'presscore_admin_scripts', 11 );


if ( ! function_exists( 'presscore_setup_admin_scripts' ) ) :

	/**
	 * Add widgets scripts. Enqueued only for widgets.php.
	 */
	function presscore_setup_admin_scripts( $hook ) {

		if ( 'widgets.php' != $hook ) {
			return;
		}

		if ( function_exists( 'wp_enqueue_media' ) ) {
			wp_enqueue_media();
		}

		// enqueue wp colorpicker
		wp_enqueue_style( 'wp-color-picker' );

		// presscore stuff
		wp_enqueue_style( 'dt-admin-widgets', PRESSCORE_ADMIN_URI . '/assets/admin-widgets.css' );
		wp_enqueue_script( 'dt-admin-widgets', PRESSCORE_ADMIN_URI . '/assets/admin_widgets_page.js', array('jquery', 'wp-color-picker'), false, true );

		wp_localize_script( 'dt-admin-widgets', 'dtWidgtes', array(
			'title'			=> _x( 'Title', 'widget', LANGUAGE_ZONE ),
			'content'		=> _x( 'Content', 'widget', LANGUAGE_ZONE ),
			'percent'		=> _x( 'Percent', 'widget', LANGUAGE_ZONE ),
			'showPercent'	=> _x( 'Show', 'widget', LANGUAGE_ZONE ),
		) );

	}

endif; // presscore_setup_admin_scripts

add_action( 'admin_enqueue_scripts', 'presscore_setup_admin_scripts', 15 );


if ( ! function_exists( 'presscore_themeoptions_add_share_buttons' ) ) :

	/**
	 * Add some share buttons to theme options.
	 */
	function presscore_themeoptions_add_share_buttons( $buttons ) {
		$theme_soc_buttons = presscore_themeoptions_get_social_buttons_list();
		if ( $theme_soc_buttons && is_array( $theme_soc_buttons ) ) {
			$buttons = array_merge( $buttons, $theme_soc_buttons );
		}
		return $buttons;
	}

endif; // presscore_themeoptions_add_share_buttons

add_filter( 'optionsframework_interface-social_buttons', 'presscore_themeoptions_add_share_buttons', 15 );


if ( ! function_exists( 'presscore_dt_paginator_args_filter' ) ) :

	/**
	 * PressCore dt_paginator args filter.
	 *
	 * @param array $args Paginator args.
	 * @return array Filtered $args.
	 */
	function presscore_dt_paginator_args_filter( $args ) {
		global $post;
		$config = Presscore_Config::get_instance();

		// show all pages in paginator
		$show_all_pages = '0';

		if ( is_page() ) {
			$show_all_pages = $config->get( 'show_all_pages' );
		}

		if ( '0' != $show_all_pages ) {
			$args['num_pages'] = 9999;
		} else {
			$args['num_pages'] = 5;
		}

		$args['wrap'] = '
		<div class="%CLASS%" role="navigation">
			<div class="page-links">%LIST%
		';
		$args['pages_wrap'] = '
			</div>
			<div class="page-nav">
				%PREV%%NEXT%
			</div>
		</div>
		';
		$args['item_wrap'] = '<a href="%HREF%" %CLASS_ACT% data-page-num="%PAGE_NUM%">%TEXT%</a>';
		$args['first_wrap'] = '<a href="%HREF%" %CLASS_ACT% data-page-num="%PAGE_NUM%">%FIRST_PAGE%</a>';
		$args['last_wrap'] = '<a href="%HREF%" %CLASS_ACT% data-page-num="%PAGE_NUM%">%LAST_PAGE%</a>';
		$args['dotleft_wrap'] = '<a href="javascript: void(0);" class="dots">%TEXT%</a>'; 
		$args['dotright_wrap'] = '<a href="javascript: void(0);" class="dots">%TEXT%</a>';// %TEXT%
		$args['pages_prev_class'] = 'nav-prev';
		$args['pages_next_class'] = 'nav-next';
		$args['act_class'] = 'act';
		$args['next_text'] = _x( 'Next page', 'paginator', LANGUAGE_ZONE );
		$args['prev_text'] = _x( 'Prev page', 'paginator', LANGUAGE_ZONE );
		$args['no_next'] = '';
		$args['no_prev'] = '';
		$args['first_is_first_mode'] = true;

		return $args;
	}

endif; // presscore_dt_paginator_args_filter

add_filter( 'dt_paginator_args', 'presscore_dt_paginator_args_filter' );


if ( ! function_exists( 'presscore_comment_id_fields_filter' ) ) :

	/**
	 * PressCore comments fields filter. Add Post Comment and clear links before hudden fields.
	 *
	 * @since presscore 0.1
	 */
	function presscore_comment_id_fields_filter( $result ) {

		$comment_buttons = presscore_get_button_html( array( 'href' => 'javascript: void(0);', 'title' => __( 'clear form', LANGUAGE_ZONE ), 'class' => 'clear-form' ) );
		$comment_buttons .= presscore_get_button_html( array( 'href' => 'javascript: void(0);', 'title' => __( 'Submit', LANGUAGE_ZONE ), 'class' => 'dt-btn dt-btn-m' ) );

		return $comment_buttons . $result;
	}

endif; // presscore_comment_id_fields_filter

add_filter( 'comment_id_fields', 'presscore_comment_id_fields_filter' );


if ( ! function_exists( 'presscore_comment' ) ) :

	/**
	 * Template for comments and pingbacks.
	 *
	 * Used as a callback by wp_list_comments() for displaying the comments.
	 *
	 * @since presscore 1.0
	 */
	function presscore_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;

		switch ( $comment->comment_type ) :
			case 'pingback' :
			case 'trackback' :
		?>
		<li class="pingback">
			<div class="pingback-content">
				<span><?php _e( 'Pingback:', LANGUAGE_ZONE ); ?></span>
				<?php comment_author_link(); ?>
				<?php edit_comment_link( __( '(Edit)', LANGUAGE_ZONE ), ' ' ); ?>
			</div>
		<?php
				break;
			default :
		?>
		<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">

			<article id="div-comment-<?php comment_ID(); ?>">

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->

			<div class="comment-meta">
				<time datetime="<?php comment_time( 'c' ); ?>">
				<?php
					/* translators: 1: date, 2: time */
					// TODO: add date/time format (for qTranslate)
					printf( __( '%1$s at %2$s', LANGUAGE_ZONE ), get_comment_date(), get_comment_time() ); ?>
				</time>
				<?php edit_comment_link( __( '(Edit)', LANGUAGE_ZONE ), ' ' ); ?>
			</div><!-- .comment-meta -->

			<div class="comment-author vcard">
				<?php if ( dt_validate_gravatar( $comment->comment_author_email ) ) :	?>
					<?php echo get_avatar( $comment, 60 ); ?>
				<?php else : ?>
					<span class="avatar no-avatar"></span>
				<?php endif; ?>
				<?php printf( '<cite class="fn">%s</cite>', str_replace( 'href', 'target="_blank" href', get_comment_author_link() ) ); ?>
			</div><!-- .comment-author .vcard -->

			<?php if ( $comment->comment_approved == '0' ) : ?>
				<em><?php _e( 'Your comment is awaiting moderation.', LANGUAGE_ZONE ); ?></em>
				<br />
			<?php endif; ?>

			<div class="comment-content"><?php comment_text(); ?></div>

			</article>

		<?php
				break;
		endswitch;
	}

endif; // presscore_comment


if ( ! function_exists( 'presscore_body_class' ) ) :

	/**
	 * Add theme speciffik classes to body.
	 *
	 * @since presscore 1.0
	 */
	function presscore_body_class( $classes ) {
		global $post;
		$config = Presscore_Config::get_instance();

		$desc_on_hoover = ( 'under_image' != $config->get('description') );
		$template = $config->get('template');
		$site = get_bloginfo('name');
		$layout = $config->get('layout');

		// website version classname
		switch ( $site ) {
			case 'ShopperBarn':
				$classes[] = 'shopperbarn';
				break;

			case 'OnlyOneStopShop':
				$classes[] = 'onlyonestopshop';
				break;

			case 'BWD Wholesale':
				$classes[] = 'bwd';
				break;
		}

		// template classes
		switch ( $template ) {
			case 'blog':
				$classes[] = 'blog';

				if ( !of_get_option( 'general-blog_meta_postformat', 1 ) ) {
					$classes[] = 'post-format-icons-disabled';
				}

				break;
			case 'portfolio': $classes[] = 'portfolio'; break;
			case 'albums': $classes[] = 'albums'; break;
		}

		// layout classes
		switch ( $layout ) {
			case 'masonry':
				if ( $desc_on_hoover ) {
					$classes[] = 'layout-masonry-grid';
				} else {
					$classes[] = 'layout-masonry';
				}
				break;
			case 'grid':
				$classes[] = 'layout-grid';
				if ( $desc_on_hoover ) {
					$classes[] = 'grid-text-hovers';
				}
				break;
			case 'checkerboard':
			case 'list': $classes[] = 'layout-list'; break;
		}

		// hover classes
		if ( in_array($layout, array('masonry', 'grid')) && !in_array($template, array('testimonials', 'team')) ) {
			$classes[] = $desc_on_hoover ? 'description-on-hover' : 'description-under-image';
		}

		// hide dividers if content is off
		if ( in_array($config->get('template'), array('albums', 'portfolio')) && 'masonry' == $config->get('layout') ) {
			$show_dividers = $config->get('show_titles') || $config->get('show_details') || $config->get('show_excerpts') || $config->get('show_terms') || $config->get('show_links');
			if ( !$show_dividers ) $classes[] = 'description-off';
		}

		if ( is_single() ) {
			$post_type = get_post_type();
			if ( 'dt_portfolio' == $post_type && ( post_password_required() || ( !comments_open() && '0' == get_comments_number() ) ) ) {
				$classes[] = 'no-comments';
			} else if ( 'post' == $post_type && !of_get_option( 'general-blog_meta_postformat', 1 ) ) {
				$classes[] = 'post-format-icons-disabled';
			}
		}

		if ( in_array('single-dt_portfolio', $classes) ) {
			$key = array_search('single-dt_portfolio', $classes);
			$classes[ $key ] = 'single-portfolio';
		}

		switch ( $config->get('header_background') ) {
			case 'overlap': $classes['header_background'] = 'overlap'; break;
			case 'transparent': $classes['header_background'] = 'transparent';
		}

		if ( 'fancy' == $config->get( 'header_title' ) ) {
			$classes[] = 'fancy-header-on';
		} elseif ( 'slideshow' == $config->get( 'header_title' ) ) {
			$classes[] = 'slideshow-on';

			if ( '3d' == $config->get( 'slideshow_mode' ) && 'fullscreen-content' == $config->get( 'slideshow_3d_layout' ) ) {
				$classes[] = 'threed-fullscreen';
			}

			if ( dt_get_paged_var() > 1 && isset($classes['header_background']) ) {
				unset($classes['header_background']);
			}

		} elseif ( is_single() && 'disabled' == $config->get( 'header_title' ) ) {
			$classes[] = 'title-off';
		}

		// hoover style
		switch( of_get_option('hoover-style', 'none') ) {
			case 'grayscale': $classes[] = 'filter-grayscale-static'; break;
			case 'gray+color': $classes[] = 'filter-grayscale'; break;
			case 'blur' : $classes[] = 'image-blur'; break;
		}

		// add boxed-class to body
		if ( 'boxed' == of_get_option('general-layout', 'wide') ) {
			$classes[] = 'boxed-layout';
		}

		if ( !presscore_responsive() ) {
			$classes[] = 'responsive-off';
		}

		// justified grid
		if ( $config->get( 'justified_grid' ) ) {
			$classes[] = 'justified-grid';
		}

		// general style
		if ( 'minimalistic' == of_get_option('general-style') ) {
			$classes[] = 'style-minimal';
		}

		// buttons style
		switch ( of_get_option('buttons-style', 'ios7') ) {
			case 'flat': $classes[] = 'btn-flat'; break;
			case '3d': $classes[] = 'btn-3d'; break;
			case 'ios7':
			default: $classes[] = 'btn-ios'; break;
		}

		return array_values( array_unique( $classes ) );
	}

endif; // presscore_body_class

add_filter( 'body_class', 'presscore_body_class' );


if ( ! function_exists( 'presscore_post_types_author_archives' ) ) :

	/**
	 * Add Custom Post Types to Author Archives
	 */
	function presscore_post_types_author_archives( $query ) {

		// Add 'videos' post type to author archives
		if ( $query->is_main_query() && $query->is_author ) {
			$post_type = $query->get( 'post_type' );
			$query->set( 'post_type', array_merge( (array) $post_type, array('dt_portfolio', 'post') ) );
		}

	}

endif; // presscore_post_types_author_archives

add_action( 'pre_get_posts', 'presscore_post_types_author_archives' );


if ( ! function_exists( 'presscore_get_blank_image' ) ) :

	/**
	 * Get blank image.
	 *
	 */
	function presscore_get_blank_image() {
		return PRESSCORE_THEME_URI . '/images/1px.gif';
	}

endif; // presscore_get_blank_image


if ( ! function_exists( 'presscore_get_default_avatar' ) ) :

	/**
	 * Get default avatar.
	 *
	 * @return string.
	 */
	function presscore_get_default_avatar() {
		return PRESSCORE_THEME_URI . '/images/no-avatar.gif';
	}

endif; // presscore_get_default_avatar


if ( !function_exists('presscore_get_default_image') ) :

	/**
	 * Get default image.
	 *
	 * Return array( 'url', 'width', 'height' );
	 *
	 * @return array.
	 */
	function presscore_get_default_image() {
		return array( PRESSCORE_THEME_URI . '/images/noimage.jpg', 1000, 1000 );
	}

endif;


if ( ! function_exists( 'presscore_get_widgetareas_options' ) ) :

	/**
	 * Prepare array with widgetareas options.
	 *
	 */
	function presscore_get_widgetareas_options() {
		$widgetareas_list = array();
		$widgetareas_stored = of_get_option('widgetareas', false);
		if ( is_array($widgetareas_stored) ) {
			foreach ( $widgetareas_stored as $index=>$desc ) {
				$widgetareas_list[ 'sidebar_' . $index ] = $desc['sidebar_name'];
			}
		}

		return $widgetareas_list;
	}

endif; // presscore_get_widgetareas_options


if ( ! function_exists( 'presscore_meta_boxes_get_images_proportions' ) ) :

	/**
	 * Image proportions array.
	 *
	 * @return array.
	 */
	function presscore_meta_boxes_get_images_proportions( $prop = false ) {

		$ratios = array(
			'1'		=> array( 'ratio' => 0.33, 'desc' => '1:3' ),
			'2'		=> array( 'ratio' => 0.3636, 'desc' => '4:11' ),
			'3'		=> array( 'ratio' => 0.45, 'desc' => '9:20' ),
			'4'		=> array( 'ratio' => 0.5625, 'desc' => '9:16' ),
			'5'		=> array( 'ratio' => 0.6, 'desc' => '3:5' ),
			'6'		=> array( 'ratio' => 0.6666, 'desc' => '2:3' ),
			'7'		=> array( 'ratio' => 0.75, 'desc' => '3:4' ),
			'8'		=> array( 'ratio' => 1, 'desc' => '1:1' ),
			'9'		=> array( 'ratio' => 1.33, 'desc' => '4:3' ),
			'10'	=> array( 'ratio' => 1.5, 'desc' => '3:2' ),
			'11'	=> array( 'ratio' => 1.66, 'desc' => '5:3' ),
			'12'	=> array( 'ratio' => 1.77, 'desc' => '16:9' ),
			'13'	=> array( 'ratio' => 2.22, 'desc' => '20:9' ),
			'14'	=> array( 'ratio' => 2.75, 'desc' => '11:4' ),
			'15'	=> array( 'ratio' => 3, 'desc' => '3:1' ),
		);

		if ( false === $prop ) return $ratios;

		if ( isset($ratios[ $prop ]) ) return $ratios[ $prop ]['ratio'];

		return false;
	}

endif; // presscore_meta_boxes_get_images_proportions

if ( ! function_exists( 'presscore_get_social_icons_data' ) ) :

	/**
	 * Return social icons array( 'class', 'title' ).
	 *
	 */
	function presscore_get_social_icons_data() {
		return array(
			'facebook'		=> __('Facebook', LANGUAGE_ZONE),
			'twitter'		=> __('Twitter', LANGUAGE_ZONE),
			'google'		=> __('Google+', LANGUAGE_ZONE),
			'dribbble'		=> __('Dribbble', LANGUAGE_ZONE),
			'you-tube'		=> __('YouTube', LANGUAGE_ZONE),
			'rss'			=> __('Rss', LANGUAGE_ZONE),
			'delicious'		=> __('Delicious', LANGUAGE_ZONE),
			'flickr'		=> __('Flickr', LANGUAGE_ZONE),
			'forrst'		=> __('Forrst', LANGUAGE_ZONE),
			'lastfm'		=> __('Lastfm', LANGUAGE_ZONE),
			'linkedin'		=> __('Linkedin', LANGUAGE_ZONE),
			'vimeo'			=> __('Vimeo', LANGUAGE_ZONE),
			'tumbler'		=> __('Tumblr', LANGUAGE_ZONE),
			'pinterest'		=> __('Pinterest', LANGUAGE_ZONE),
			'devian'		=> __('Deviantart', LANGUAGE_ZONE),
			'skype'			=> __('Skype', LANGUAGE_ZONE),
			'github'		=> __('Github', LANGUAGE_ZONE),
			'instagram'		=> __('Instagram', LANGUAGE_ZONE),
			'stumbleupon'	=> __('Stumbleupon', LANGUAGE_ZONE),
			'behance'		=> __('Behance', LANGUAGE_ZONE),
			'mail'			=> __('Mail', LANGUAGE_ZONE),
			'website'		=> __('Website', LANGUAGE_ZONE),
			'px-500'		=> __('500px', LANGUAGE_ZONE),
			'tripedvisor'	=> __('TripAdvisor', LANGUAGE_ZONE),
			'vk'			=> __('VK', LANGUAGE_ZONE),
			'foursquare'	=> __('Foursquare', LANGUAGE_ZONE),
			'xing'			=> __('XING', LANGUAGE_ZONE),
			'weibo'			=> __('Weibo', LANGUAGE_ZONE),
		);
	}

endif; // presscore_get_social_icons_data



if ( ! function_exists( 'presscore_themeoptions_get_buttons_defaults' ) ) :

	/**
	 * Buttons defaults array.
	 */
	function presscore_themeoptions_get_buttons_defaults() {
		return array(
			's'		=> array(
				'desc'	=> _x('Small buttons', 'theme-options', LANGUAGE_ZONE),
				'ff'	=> '',
				'fs'	=> 12,
				'uc'	=> 0,
				'lh'	=> 21,
				'border_radius' => '4'
				),
			'm'	=> array(
				'desc'	=> _x('Medium buttons', 'theme-options', LANGUAGE_ZONE),
				'ff'	=> '',
				'fs'	=> 12,
				'uc'	=> 0,
				'lh'	=> 23,
				'border_radius' => '4'
				),
			'l'	=> array(
				'desc'	=> _x('Big buttons', 'theme-options', LANGUAGE_ZONE),
				'ff'	=> '',
				'fs'	=> 14,
				'uc'	=> 0,
				'lh'	=> 32,
				'border_radius' => '4'
				)
		);
	}

endif; // presscore_themeoptions_get_buttons_defaults


if ( ! function_exists( 'presscore_themeoptions_get_hoover_options' ) ) :

	/**
	 * Hoover options.
	 */
	function presscore_themeoptions_get_hoover_options() {
		return array(
			'none' => _x('None', 'theme-options', LANGUAGE_ZONE),
			'grayscale' => _x('Grayscale', 'theme-options', LANGUAGE_ZONE),
			'gray+color' => _x('Grayscale with color hovers', 'theme-options', LANGUAGE_ZONE),
			'blur' => _x('Blur', 'theme-options', LANGUAGE_ZONE),
		);
	}

endif; // presscore_themeoptions_get_hoover_options


if ( ! function_exists( 'presscore_themeoptions_get_general_layout_options' ) ) :

	/**
	 * General layout.
	 */
	function presscore_themeoptions_get_general_layout_options() {
		return array(
			'wide'	=> _x('Wide', 'theme-options', LANGUAGE_ZONE),
			'boxed'	=> _x('Boxed', 'theme-options', LANGUAGE_ZONE)
		);
	}

endif; // presscore_themeoptions_get_general_layout_options



if ( ! function_exists( 'presscore_themeoptions_get_template_list' ) ) :

	/**
	 * Templates list.
	 */
	function presscore_themeoptions_get_template_list(){
		return array(
			'post' 				=> _x('Social buttons in blog posts', 'theme-options', LANGUAGE_ZONE),
			'portfolio_post' 	=> _x('Social buttons in portfolio projects', 'theme-options', LANGUAGE_ZONE),
			'photo' 			=> _x('Social buttons in media (photos and videos)', 'theme-options', LANGUAGE_ZONE),
			'page' 				=> _x('Social buttons on page', 'theme-options', LANGUAGE_ZONE),
		);
	}

endif; // presscore_themeoptions_get_template_list


if ( ! function_exists( 'presscore_get_team_links_array' ) ) :

	/**
	 * Return links list for team post meta box.
	 *
	 * @return array.
	 */
	function presscore_get_team_links_array() {
		$team_links =  array(
			'website'		=> array( 'desc' => _x( 'Personal blog / website', 'team link', LANGUAGE_ZONE ) ),
			'mail'			=> array( 'desc' => _x( 'E-mail', 'team link', LANGUAGE_ZONE ) ),
		);

		$common_links = presscore_get_social_icons_data();
		if ( $common_links ) {

			foreach ( $common_links as $key=>$value ) {

				if ( isset($team_links[ $key ]) ) {
					continue;
				}

				$team_links[ $key ] = array( 'desc' => $value );
			}
		}

		return $team_links;
	}

endif; // presscore_get_team_links_array


/****************************************************************
// AJAX PAGINATION
*****************************************************************

/**
 * Ajax pagination controller.
 *
 */
function presscore_ajax_pagination_controller() {

	$ajax_data = array(
		'nonce' => isset($_POST['nonce']) ? $_POST['nonce'] : false,
		'post_id' => isset($_POST['postID']) ? absint($_POST['postID']) : false,
		'post_paged' => isset($_POST['paged']) ? absint($_POST['paged']) : false,
		'target_page' => isset($_POST['targetPage']) ? absint($_POST['targetPage']) : false,
		'page_data' => isset($_POST['pageData']) ? $_POST['pageData'] : false,
		'term' => isset($_POST['term']) ? $_POST['term'] : '',
		'orderby' => isset($_POST['orderby']) ? $_POST['orderby'] : '',
		'order' => isset($_POST['order']) ? $_POST['order'] : '',
		'loaded_items' => isset($_POST['visibleItems']) ? array_map('absint', $_POST['visibleItems']) : array(),
		'sender' => isset($_POST['sender']) ? $_POST['sender'] : ''
	);

	if ( $ajax_data['post_id'] && 'page' == get_post_type($ajax_data['post_id']) ) {
		$template = dt_get_template_name( $ajax_data['post_id'], true );
	} else if ( is_array($ajax_data['page_data']) ) {

		switch ( $ajax_data['page_data'][0] ) {
			case 'archive' : $template = 'archive'; break;
			case 'search' : $template = 'search';
		}
	}

	$responce = array( 'success' => false, 'reason' => 'undefined template' );

	switch( $template ) {
		case 'template-albums-jgrid.php':
		case 'template-albums.php':
			$responce = Presscore_Inc_Albums_Post_Type::get_albums_masonry_content( $ajax_data ); break;

		case 'template-portfolio-masonry.php':
		case 'template-portfolio-jgrid.php':
			$responce = Presscore_Inc_Portfolio_Post_Type::get_masonry_content( $ajax_data ); break;

		case 'template-media.php':
		case 'template-media-jgrid.php':
			$responce = Presscore_Inc_Albums_Post_Type::get_media_masonry_content( $ajax_data ); break;

	}

	$responce = json_encode( $responce );

	// responce output
	header( "Content-Type: application/json" );
	echo $responce;

	// IMPORTANT: don't forget to "exit"
	exit;
}
add_action( 'wp_ajax_nopriv_presscore_template_ajax', 'presscore_ajax_pagination_controller' );
add_action( 'wp_ajax_presscore_template_ajax', 'presscore_ajax_pagination_controller' );


if ( ! function_exists( 'presscore_change_dt_potfolio_post_type_args' ) ) :

	/**
	 * Change portfolio custom post type slug
	 *
	 * @since  4.1.0
	 * @param  array  $args Custom post type registration arguments
	 * @return array        Changed arguments
	 */
	function presscore_change_dt_potfolio_post_type_args( $args = array() ) {

		if ( array_key_exists('rewrite', $args) && array_key_exists('slug', $args['rewrite']) ) {

			$new_slug = of_get_option( 'general-post_type_portfolio_slug', '' );
			if ( $new_slug && is_string($new_slug) ) {
				$args['rewrite']['slug'] = trim( strtolower( $new_slug ) );
			}
		}

		return $args;
	}

endif;
add_filter( 'presscore_post_type_dt_portfolio_args', 'presscore_change_dt_potfolio_post_type_args' );


if ( ! function_exists( 'presscore_flush_rewrite_rules_after_post_type_slug_change' ) ) :

	/**
	 * Flush rewrite rules on change slug setting in theme options.
	 *
	 * @since 4.1.0
	 * @param  array  $options Sanitized theme options in their way to database
	 */
	function presscore_flush_rewrite_rules_after_post_type_slug_change( $options = array() ) {

		$old_portfolio_slug = of_get_option( 'general-post_type_portfolio_slug', 'project' );
		$new_portfolio_slug = $options['general-post_type_portfolio_slug'];

		// check if new slug really new
		if ( $old_portfolio_slug != $new_portfolio_slug ) {
			wp_schedule_single_event( time(), 'presscore_onetime_after_post_type_slug_changing' );
		}
	}

endif;
add_action( 'optionsframework_after_validate', 'presscore_flush_rewrite_rules_after_post_type_slug_change' );


if ( ! function_exists( 'presscore_onetime_scheduled_rewrite_rules_flush' ) ) :

	/**
	 * Run onetime scheduled code
	 *
	 * @since 4.1.0
	 */
	function presscore_onetime_scheduled_rewrite_rules_flush() {
		presscore_flush_rewrite_rules();
	}

endif;
add_action( 'presscore_onetime_after_post_type_slug_changing', 'presscore_onetime_scheduled_rewrite_rules_flush' );


if ( ! function_exists( 'presscore_set_default_contact_form_email' ) ) :

	/**
	 * Set default email for contact forms if it's not empty
	 * See theme options General->Advanced
	 * 
	 * @since 4.1.0
	 * @param  string $email Original email
	 * @return string        Modified email
	 */
	function presscore_set_default_contact_form_email( $email = '' ) {

		$default_email = of_get_option( 'general-contact_form_send_mail_to', '' );
		if ( $default_email ) {
			$email = $default_email;
		}

		return $email;
	}

endif;
add_filter( 'dt_core_send_mail-to', 'presscore_set_default_contact_form_email' );

if ( ! function_exists( 'presscore_add_compat_header' ) ) {

	add_filter( 'wp_headers', 'presscore_add_compat_header' );

	/**
	 * [presscore_add_compat_header description]
	 * 
	 * @param  array $headers
	 * @return array
	 */
	function presscore_add_compat_header( $headers ) {
		if ( isset( $_SERVER['HTTP_USER_AGENT'] ) && strpos( $_SERVER['HTTP_USER_AGENT'], 'MSIE' ) !== false) {
			$headers['X-UA-Compatible'] = 'IE=EmulateIE10';
		}
		return $headers;
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
		if ( ! is_woocommerce() && ! is_cart() && ! is_checkout() ) {
			wp_dequeue_style( 'woocommerce_frontend_styles' );
			wp_dequeue_style( 'woocommerce_fancybox_styles' );
			wp_dequeue_style( 'woocommerce_chosen_styles' );
			wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
			wp_dequeue_script( 'wc_price_slider' );
			wp_dequeue_script( 'wc-single-product' );
			// wp_dequeue_script( 'wc-add-to-cart' );
			wp_dequeue_script( 'wc-cart-fragments' );
			wp_dequeue_script( 'wc-checkout' );
			wp_dequeue_script( 'wc-add-to-cart-variation' );
			wp_dequeue_script( 'wc-single-product' );
			wp_dequeue_script( 'wc-cart' );
			wp_dequeue_script( 'wc-chosen' );
			wp_dequeue_script( 'woocommerce' );
			wp_dequeue_script( 'prettyPhoto' );
			wp_dequeue_script( 'prettyPhoto-init' );
			wp_dequeue_script( 'jquery-blockui' );
			wp_dequeue_script( 'jquery-placeholder' );
			wp_dequeue_script( 'fancybox' );
			wp_dequeue_script( 'jqueryui' );
		}
	}
 
}

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
<?php
/*
Plugin Name: Ultra WordPress Admin
Plugin URI: http://jaybabani.in/ultra-admin/wp-admin
Description: Advanced controlled Admin Theme for WordPress.
Author: themepassion
Version: 1.1
Author URI: http://jaybabani.com/ultra-admin/wp-admin
*/

/* --------------- Ultra CSS based on WP Version ---------------- */
require_once( trailingslashit(dirname( __FILE__ )) . 'lib/ultra-css-version.php' );

/* --------------- Custom colors ---------------- */
require_once( trailingslashit(dirname( __FILE__ )) . 'lib/ultra-custom-colors.php' );

/* --------------- Color Library ---------------- */
require_once( trailingslashit(dirname( __FILE__ )) . 'lib/ultra-color-lib.php' );

/* --------------- Ultra Fonts ---------------- */
require_once( trailingslashit(dirname( __FILE__ )) . 'lib/ultra-fonts.php' );

/* --------------- CSS Library ---------------- */
require_once( trailingslashit(dirname( __FILE__ )) . 'lib/ultra-css-lib.php' );

/* --------------- Logo and Favicon Settings ---------------- */
require_once( trailingslashit(dirname( __FILE__ )) . 'lib/ultra-logo.php' );

/* --------------- Login  ---------------- */
require_once( trailingslashit(dirname( __FILE__ )) . 'lib/ultra-login.php' );

/* --------------- Top Bar ---------------- */
require_once( trailingslashit(dirname( __FILE__ )) . 'lib/ultra-topbar.php' );

/* --------------- Page Loader ---------------- */
require_once( trailingslashit(dirname( __FILE__ )) . 'lib/ultra-pageloader.php' );


/* --------------- Load  framework ---------------- */

function ultra_load_framework(){
    

	if ( !class_exists( 'ReduxFramework' ) && file_exists( dirname( __FILE__ ) . '/framework/core/framework.php' ) ) {
	    require_once( dirname( __FILE__ ) . '/framework/core/framework.php' );
	}
	if (!isset( $ultra_demo ) && file_exists( dirname( __FILE__ ) . '/framework/options/ultra-config.php')) {
	    require_once( dirname( __FILE__ ) . '/framework/options/ultra-config.php' );
	}
}

ultra_load_framework();


/* --------------- Load Custom functions ---------------- */
require_once( trailingslashit(dirname( __FILE__ )) . 'lib/ultra-functions.php' );


/* ---------------- Dynamic CSS - after plugins loaded ------------------ */
add_action('plugins_loaded', 'ultra_core', 12);

/* ------------------------------------------------
Regenerate All Color Files again - 
Uncommenting this might affect the speed depending on server
Don't Uncomment it.
------------------------------------------------- */
//add_action('plugins_loaded', 'ultra_regenerate_all_dynamic_css_file', 12);







/* ------------------------------------------------
Load Settings Panel only if demo_settings is present. Only for demo purpose
Don't Uncomment it.
------------------------------------------------- */
//add_action('admin_footer', 'ultra_admin_footer_function');

function ultra_admin_footer_function() {
/* --------------- Settings Panel ----------------- */
if(!has_action('plugins_loaded', 'ultra_regenerate_all_dynamic_css_file')){
    if (file_exists(plugin_dir_path(__FILE__) . 'demo-settings/ultra-settings-panel.php')) {
        require_once( trailingslashit(dirname( __FILE__ )) . 'demo-settings/ultra-settings-panel.php' );
    }
}}


?>
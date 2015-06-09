<?php
/**
 * Shortcodes setup.
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

// if we have wp version 3.9.0 or greater
if ( version_compare(get_bloginfo('version'), '3.9', '>=') ) {

	// TinyMCE button class
	require_once( trailingslashit( PRESSCORE_SHORTCODES_INCLUDES_DIR ) . 'class-register-button-wp-3.9.php' );

} else {

	// TinyMCE button class
	require_once( trailingslashit( PRESSCORE_SHORTCODES_INCLUDES_DIR ) . 'class-register-button.php' );

}

// Shortcode class
require_once( trailingslashit( PRESSCORE_SHORTCODES_INCLUDES_DIR ) . 'class-shortcode.php' );

/**
 * Some shortcodes triks.
 * From: http://www.viper007bond.com/2009/11/22/wordpress-code-earlier-shortcodes/
 */
function dt_get_puny_shortcodes() {
	$puny_shortcodes = array(
	);

	if ( function_exists('vc_is_inline') && vc_is_inline() ) {
		$puny_shortcodes = array();
	}

	return apply_filters( 'dt_get_puny_shortcodes', $puny_shortcodes );
}

/**
 * Actual processing of the shortcode happens here.
 */
function dt_run_puny_shortcode( $content ) {
	global $shortcode_tags;

	// Backup current registered shortcodes and clear them all out
	$orig_shortcode_tags = $shortcode_tags;
	remove_all_shortcodes();

	foreach ( dt_get_puny_shortcodes() as $shortcode=>$callback ) {
		add_shortcode( $shortcode, $callback );
	}

	// Do the shortcode (only the one above is registered)
	$content = do_shortcode( shortcode_unautop($content) );

	// Put the original shortcodes back
	$shortcode_tags = $orig_shortcode_tags;

	return $content;
}
add_filter( 'the_content', 'dt_run_puny_shortcode', 7 );

// some new stuff from https://gist.github.com/bitfade/4555047
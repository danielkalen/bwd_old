<?php
/*
	Plugin Name: WooCommerce UPS Shipping
	Plugin URI: http://woothemes.com/woocommerce
	Description: WooCommerce UPS Shipping allows a store to obtain shipping rates for your orders dynamically via the UPS Shipping API.
	Version: 3.0.0
	Author: WooThemes
	Author URI: http://woothemes.com

	Copyright: 2009-2015 WooThemes.
	License: GNU General Public License v3.0
	License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Required functions
 */
if ( ! function_exists( 'woothemes_queue_update' ) ) {
	require_once( 'woo-includes/woo-functions.php' );
}

/**
 * Plugin updates
 */
woothemes_queue_update( plugin_basename( __FILE__ ), '8dae58502913bac0fbcdcaba515ea998', '18665' );

/**
 * Plugin activation check
 */
function wc_ups_activation_check(){
	if ( ! function_exists( 'simplexml_load_string' ) ) {
        deactivate_plugins( basename( __FILE__ ) );
        wp_die( "Sorry, but you can't run this plugin, it requires the SimpleXML library installed on your server/hosting to function." );
	}
}

register_activation_hook( __FILE__, 'wc_ups_activation_check' );

if ( ! class_exists( 'WC_Shipping_UPS_Init' ) ) :

class WC_Shipping_UPS_Init {

	/**
	 * Plugin version.
	 *
	 * var string
	 */

	const VERSION = '3.0.0';

	/**
	 * Instance of this class.
	 *
	 * var object
	 */

	protected static $instance = null;

	/**
	 * Initialize the plugin's public actions
	 */

	private function __construct() {

		if ( class_exists( 'WC_Shipping_Method' ) ) {

			add_action( 'init', array( $this, 'load_textdomain' ) );
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_links' ) );


			add_action( 'woocommerce_shipping_init', array( $this, 'includes' ) );

			add_filter( 'woocommerce_shipping_methods', array( $this, 'add_method' ) );

		} else {

			add_action( 'admin_notices', array( $this, 'wc_deactivated' ) );
		
		}

	}

	/**
	 * Return an instance of this class.
	 *
	 * @return object - The single instance of this class.
	 */

	public static function get_instance() {

	 	// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
	 	}

	 	return self::$instance;

	}

	/**
	 * Include needed files
	 */

	public function includes() {

		include_once( 'classes/class-wc-shipping-ups.php' );

	}

	/**
	 * wc_ups_add_method function.
	 *
	 * @access public
	 * @param mixed $methods
	 * @return void
	 */
	public function add_method( $methods ) {

		$methods[] = 'WC_Shipping_UPS';
		return $methods;

	}

	/**
	 * Localisation
	 */

	public function load_textdomain() {

		load_plugin_textdomain( 'woocommerce-shipping-ups', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

	}

	/**
	 * Plugin page links
	 */

	function plugin_links( $links ) {

		$plugin_links = array(
			'<a href="http://support.woothemes.com/">' . __( 'Support', 'woocommerce-shipping-ups' ) . '</a>',
			'<a href="http://wcdocs.woothemes.com/user-guide/ups/">' . __( 'Docs', 'woocommerce-shipping-ups' ) . '</a>',
		);

		return array_merge( $plugin_links, $links );
	}


	public function wc_deactivated() {

		echo '<div class="error"><p>' . sprintf( __( 'WooCommerce UPS Shipping requires %s to be installed and active.', 'woocommerce-shipping-ups' ), '<a href="http://www.woothemes.com/woocommerce/" target="_blank">WooCommerce</a>' ) . '</p></div>';

	}

}

add_action( 'plugins_loaded' , array( 'WC_Shipping_UPS_Init' , 'get_instance' ), 0 );

endif;
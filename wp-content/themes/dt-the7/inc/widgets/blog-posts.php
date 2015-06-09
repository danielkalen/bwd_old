<?php
/**
 * Cart Actions widget.
 *
 * @package presscore.
 * @since presscore 1.0
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* Load the widget */
add_action( 'widgets_init', array( 'Presscore_Inc_Widgets_BlogPosts', 'presscore_register_widget' ) );

class Presscore_Inc_Widgets_BlogPosts extends WP_Widget {
    
    /* Widget defaults */
    public static $widget_defaults = array( 
		'title'     	=> '',
		'order'     	=> 'DESC',
		'orderby'   	=> 'date',
		'select'		=> 'all',
		'show'      	=> 6,
		'cats'      	=> array(),
		'thumbnails'	=> true,
    );

	/* Widget setup  */
	function __construct() {  
        /* Widget settings. */
		$widget_ops = array( 'description' => _x( 'Cart Actions', 'widget', LANGUAGE_ZONE ) );

		/* Create the widget. */
        parent::__construct(
            'presscore-blog-posts',
            DT_WIDGET_PREFIX . _x( 'Cart Actions', 'widget', LANGUAGE_ZONE ),
            $widget_ops
        );
	}

	/* Display the widget  */
	function widget( $args, $instance ) {

		extract( $args );

        $instance = wp_parse_args( (array) $instance, self::$widget_defaults );

		// echo $before_widget ;
		echo '<section id="execphp-4" class="widget widget_execphp">';

		// title
		echo '<div class="execphpwidget">';


		echo '<div class="cart-collaterals-dk">';

			do_action( 'woocommerce_cart_collaterals' );

			woocommerce_cart_totals();


		echo  '<input type="submit" class="button" name="update_cart_alias" id="update_cart_alias" value="'; 
			_e( 'Update Cart', 'woocommerce' ); 
		echo  '" /> <a href="';
		echo WC()->cart->get_checkout_url();
		echo  '"><button class="checkout-button button alt wc-forward">';
			_e( 'Checkout', 'woocommerce' );
		echo '</button></a>';

			do_action( 'woocommerce_proceed_to_checkout' );

			wp_nonce_field( 'woocommerce-cart' );


		echo '</div>';


 

		// echo $after_widget;
		 echo '</section>';
	}

	/* Update the widget settings  */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
        
		$instance['title'] 		= strip_tags($new_instance['title']);
        $instance['order']    	= esc_attr($new_instance['order']);
		$instance['orderby']   	= esc_attr($new_instance['orderby']);
		$instance['show']     	= intval($new_instance['show']);
		
		$instance['select']   	= in_array( $new_instance['select'], array('all', 'only', 'except') ) ? $new_instance['select'] : 'all';
		$instance['cats']    	= (array) $new_instance['cats'];
		if ( empty($instance['cats']) ) { $instance['select'] = 'all'; }

		$instance['thumbnails'] = absint($new_instance['thumbnails']);

		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
        $instance = wp_parse_args( (array) $instance, self::$widget_defaults );

	}

	public static function presscore_register_widget() {
		register_widget( get_class() );
	}
}

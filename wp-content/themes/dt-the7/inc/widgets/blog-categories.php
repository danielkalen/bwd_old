<?php
/**
 * Shop categories widget.
 *
 * @package presscore.
 * @since presscore 1.0
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* Load the widget */
add_action( 'widgets_init', array( 'Presscore_Inc_Widgets_BlogCategories', 'presscore_register_widget' ) );

class Presscore_Inc_Widgets_BlogCategories extends WP_Widget {
    
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
		$widget_ops = array( 'description' => _x( 'Shop categories', 'widget', LANGUAGE_ZONE ) );

		/* Create the widget. */
        parent::__construct(
            'presscore-blog-categories',
            DT_WIDGET_PREFIX . _x( 'Shop categories', 'widget', LANGUAGE_ZONE ),
            $widget_ops
        );
	}

	/* Display the widget  */
	function widget( $args, $instance ) {

		extract( $args );

        $instance = wp_parse_args( (array) $instance, self::$widget_defaults );

		
		// echo $before_widget ;
		echo '<section id="execphp-3" class="widget widget_execphp">';

		// title
		echo '<div class="widget-title">Shop Categories</div>';
		echo '<div class="execphpwidget">';


		$args = array(
		    // 'number'     => $number,
		    // 'orderby'    => $orderby,
		    // 'order'      => $order,
		    'hide_empty' => '0',
		    // 'include'    => $ids,
		    'parent'    => 0,
		);

		$product_categories = get_terms( 'product_cat', $args );

		$count = count($product_categories);
		 if ( $count > 0) {
		     echo "<ul>";
		     foreach ( $product_categories as $category ) {
		       echo '<li><a href="' . get_term_link( $category ) . '">' . $category->name . '</a></li>';
		     }
		     echo "</ul></div>";
		 }


 

		// echo $after_widget;
		 echo '</section>';
	}

	/* Update the widget settings  */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
        
		$instance['title'] = strip_tags($new_instance['title']);

		$instance['select'] = in_array( $new_instance['select'], array('all', 'only', 'except') ) ? $new_instance['select'] : 'all';
		$instance['cats'] = (array) $new_instance['cats'];
		if ( empty($instance['cats']) ) { $instance['select'] = 'all'; }

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

        $title = strip_tags( $instance['title'] );
		$terms = get_terms( 'category', array(
            'hide_empty'    => 1,
            'hierarchical'  => false 
        ) );
        ?>

	<?php
	}

	public static function presscore_register_widget() {
		register_widget( get_class() );
	}
}

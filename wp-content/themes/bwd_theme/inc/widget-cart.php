<?php 
// Creating the widget 
class cart_totals extends WP_Widget {

function __construct() {
parent::__construct(
// Base ID of your widget
'cart_totals', 

// Widget name will appear in UI
__('Cart Totals', 'cart_totals_domain'), 

// Widget description
array( 'description' => __( 'Cart Totals Widget', 'cart_totals_domain' ), ) 
);
}

// Creating widget front-end
// This is where the action happens
public function widget( $args, $instance ) {
	$title = apply_filters( 'widget_title', $instance['title'] );

	// before and after widget arguments are defined by themes
	echo $args['before_widget'];

	if ( ! empty( $title ) )
		// echo $args['before_title'] . $title . $args['after_title'];

	// This is where you run the code and display the output
	do_action( 'woocommerce_cart_collaterals' );
	echo $args['after_widget'];
}
		
// Widget Backend 
public function form( $instance ) {
	if ( isset( $instance[ 'title' ] ) ) {
		$title = $instance[ 'title' ];
	}
	else {
		$title = __( 'New title', 'cart_totals_domain' );
	}
	// Widget admin form
	?>

	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
	</p>
	<?php 
}
	
// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
	$instance = array();
	$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}
} // Class cart_totals ends here

// Register and load the widget
function cart_totals_load_widget() {
	register_widget( 'cart_totals' );
}
add_action( 'widgets_init', 'cart_totals_load_widget' );


?>
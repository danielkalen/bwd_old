<?php 
// Creating the widget 
class checkout_totals extends WP_Widget {

function __construct() {
parent::__construct(
// Base ID of your widget
'checkout_totals', 

// Widget name will appear in UI
__('Cart Totals (checkout)', 'checkout_totals_domain'), 

// Widget description
array( 'description' => __( 'Cart Totals Widget (checkout)', 'checkout_totals_domain' ), ) 
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

	// echo '<div id="order_review" class="woocommerce-checkout-review-order">';
	//do_action( 'woocommerce_checkout_order_review' );
	woocommerce_order_review();
	// echo '</div>';
	echo $args['after_widget'];
}
		
// Widget Backend 
public function form( $instance ) {
	if ( isset( $instance[ 'title' ] ) ) {
		$title = $instance[ 'title' ];
	}
	else {
		$title = __( 'New title', 'checkout_totals_domain' );
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
} // Class checkout_totals ends here

// Register and load the widget
function checkout_totals_load_widget() {
	register_widget( 'checkout_totals' );
}
add_action( 'widgets_init', 'checkout_totals_load_widget' );


?>
<?php 
class PDFWidget extends WP_Widget {


	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
// widget actual processes
		parent::__construct( $id_base = 'pdfcat_', $name = 'PDF Catalog Download Button' );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {


		if ( PDFCatalog::canViewCatalog() ) {


			$wpml = defined( 'ICL_LANGUAGE_CODE' );

			//var_dump($roles);
			$link_type = ( isset( $instance['link_type'] ) ) ? $instance['link_type'] : 'both';
			$categoryButtonText = ( isset( $instance['categoryText'] ) ) ? $instance['categoryText'] : 'Category Catalog';
			$storeButtonText = ( isset( $instance['fullText'] ) ) ? $instance['fullText'] : 'Complete Store Catalog';


			$showFullCatalogButton = true;
			$showCategoryCatalogButton = true;

			if ( $link_type == 'category' ) {
				$showFullCatalogButton = false;
			}
			if ( $link_type == 'full' ) {
				$showCategoryCatalogButton = false;
			}

			global $wp_query;

			$cat = $wp_query->get_queried_object();

			$class = get_class( $cat );

			if ( 'stdClass' == $class ) {
				if ( isset( $cat->label ) && ( 'Products' == $cat->label ) ) {
					$showCategoryCatalogButton = false;
				}
			}
			if ( 'WP_Post' == $class ) {
				$post = $cat;
				if ( $post->post_type == 'product' ) {

					$cats = get_the_terms( $post->ID, 'product_cat' );
					if ( count( $cats ) > 0 ) {
						$cat = end( $cats );
					}


				} else {
					$showCategoryCatalogButton = false;
				}
			}

			if ( $showCategoryCatalogButton || $showFullCatalogButton ) {
				$title = apply_filters( 'widget_title', $instance['title'] );

				echo $args['before_widget'];
				if ( strlen( $title ) > 0 ) {
					echo $args['before_title'] . $title . $args['after_title'];
				}

				if ( $cat ) {
					if ( (int) $cat->term_id > 0 ) {
						$url =get_site_url() . '?pdfcat&c=' . $cat->term_id;
					} else {
						$url = '';
						$showCategoryCatalogButton = false;
					}
				} else {
					$url = '';
					$showCategoryCatalogButton = false;
				}
				$urlall = get_site_url() . '?pdfcat&all';

				if ( $wpml ) {
					$url .= '&lang=' . ICL_LANGUAGE_CODE;
					$urlall .= '&lang=' . ICL_LANGUAGE_CODE;
				}
				include dirname( __FILE__ ) . "/templates/widget/downloadbutton.php";


				echo $args['after_widget'];
			}
		}

	}

	/**
	 * Ouputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $d ) {


		$link_type = ( isset( $d['link_type'] ) ) ? $d['link_type'] : 'both';
		$title = ( isset( $d['title'] ) ) ? $d['title'] : 'PDF Catalog';
		$categoryText = ( isset( $d['categoryText'] ) ) ? $d['categoryText'] : 'Category Catalog';
		$fullText = ( isset( $d['fullText'] ) ) ? $d['fullText'] : 'Complete Store Catalog';


		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
			       name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
			       value="<?php echo esc_attr( $title ); ?>">
		</p>
		<h3>Download Buttons to show</h3>
		<p>
			<input id="<?php echo $this->get_field_id( 'link_type' ); ?>1"
			       name="<?php echo $this->get_field_name( 'link_type' ); ?>" type="radio"
			       value="category" <?php if ( $link_type == 'category' ) {
				echo 'checked';
			} ?>>
			<label for="<?php echo $this->get_field_id( 'link_type' ); ?>1">Per Category Catalog</label>
		</p>
		<p>
			<input id="<?php echo $this->get_field_id( 'link_type' ); ?>2"
			       name="<?php echo $this->get_field_name( 'link_type' ); ?>" type="radio"
			       value="full" <?php if ( $link_type == 'full' ) {
				echo 'checked';
			} ?>>
			<label for="<?php echo $this->get_field_id( 'link_type' ); ?>2">Full Store Catalog</label>
		</p>
		<p>
			<input id="<?php echo $this->get_field_id( 'link_type' ); ?>3"
			       name="<?php echo $this->get_field_name( 'link_type' ); ?>" type="radio"
			       value="both" <?php if ( $link_type == 'both' ) {
				echo 'checked';
			} ?>>
			<label for="<?php echo $this->get_field_id( 'link_type' ); ?>3">Both</label>
		</p>
		<h3>Button Captions</h3>
		<p>
			<label for="<?php echo $this->get_field_id( 'categoryText' ); ?>"><?php _e( 'Category Button:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'categoryText' ); ?>"
			       name="<?php echo $this->get_field_name( 'categoryText' ); ?>" type="text"
			       value="<?php echo esc_attr( $categoryText ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'fullText' ); ?>"><?php _e( 'Full Store Button:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'fullText' ); ?>"
			       name="<?php echo $this->get_field_name( 'fullText' ); ?>" type="text"
			       value="<?php echo esc_attr( $fullText ); ?>">
		</p>




	<?php 	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		return $new_instance;
	}
}
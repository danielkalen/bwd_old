<?php /*
Plugin Name: PDF Product Catalog 2 (BETA4) for WooCommerce
Plugin URI: http://www.brainvial.com/pdfcatalog
Description: Customizable and flexible PDF catalog generator for WooCommerce stores.
Author: Kyriakos Ktorides
Version: 2.0.0 BETA-5
Author URI: http://www.brainvial.com
*/

define( 'PDFCATALOG_VERSION', '2.0.0B5' );

include_once "widget.php";
include_once 'classes/admin/PDFCatalogAdminOptions.php';

class PDFCatalog {


	/**
	 * @var PDFCatalogAdminOptions
	 */
	public $options;

	function __construct() {
		$this->options = new PDFCatalogAdminOptions();
	}

	static function  canViewCatalog() {


		$hiddenRoles = explode( ',', get_option( 'pdfcat_hiddenroles' ) );


		if ( count( $hiddenRoles ) == 0 ) {
			return true;
		} else {
			global $current_user;

			$roles = $current_user->roles;

			if ( ( count( $roles ) == 0 ) && ( array_search( 'PDF_SIGNED_OUT', $hiddenRoles ) !== false ) ) {
				return false;
			}

			foreach ( $roles as $role ) {
				if ( array_search( $role, $hiddenRoles ) !== false ) {
					return false;
				}
			}
		}

		return true;

	}

	static function getCatalog() {
		if ( isset( $_GET['pdfcat'] ) ) {

			define( 'PDFCATALOG', true );
			include dirname( __FILE__ ) . '/getcatalogex.php';
			exit;
		}

	}

	static function  enqueue_buttons_css() {
		wp_register_style( 'pdf-buttons', plugins_url( 'pdfcatalog/buttons.css' ) );
	}

	static function enqueue_buttons_css2() {
		wp_enqueue_style( 'pdf-buttons' );
	}

	static function widget_init() {
		register_widget( 'PDFWidget' );
	}

	static function add_options_page_imp() {

		$tabs = array( 'template' => 0, 'colors' => 0, 'headfoot' => 0, 'cache' => 0, 'categories' => 0 );

		if ( isset( $_GET['tab'] ) ) {
			$tab = $_GET['tab'];
		} else {
			$tab = 'template';
		}

		if ( ! isset( $tabs[ $tab ] ) ) { // validate before appending to filename
			$tab = 'template';
		}

		include dirname( __FILE__ ) . '/adminpages/options_' . $tab . '.php';
	}

	static function admin_menu() {
		add_options_page( 'PDF Catalog Generator Settings', 'PDF Catalog', 'manage_options', 'pdfgensettings', array(
			'PDFCatalog',
			'add_options_page_imp'
		) );
	}

	public function init() {

		include_once 'PDFCatalogGenerator.php';

		PDFCatalogGenerator::$templates = PDFCatalogGenerator::getTemplates();

		$plugin = plugin_basename( __FILE__ );
		$imageSize = get_option( 'pdfcat_imagesize' );

		if ( $imageSize != 'default' ) {
			add_image_size( 'pdf_catalog', PDFCatalogGenerator::$imageSizes[ $imageSize ][1], PDFCatalogGenerator::$imageSizes[ $imageSize ][2], true );
		}
		// SET JPEG QUALITY FOR TCPDF TOO!!!

		add_action( 'init', array( 'PDFCatalog', 'getCatalog' ) );

		// add_action( 'wp_enqueue_scripts', array( 'PDFCatalog', 'enqueue_buttons_css' ) );

		add_action( 'widgets_init', array( 'PDFCatalog', 'widget_init' ) );

		// add_action( 'wp_enqueue_scripts', array( 'PDFCatalog', 'enqueue_buttons_css2' ) );

		add_shortcode( 'pdfcatalog', array( 'PDFCatalog', 'shortCode' ) );

		add_action( 'admin_menu', array( 'PDFCatalog', 'admin_menu' ) );

		add_action( 'admin_enqueue_scripts', array( "PDFCatalog", 'load_admin_style' ) );


		add_action( 'wp_ajax_pdf_delete_cache_file', array( "PDFCatalog", 'pdf_delete_cache_file' ) );

		$this->options->initOptions();

	}

	function pdf_delete_cache_file() {


		$file = $_POST['file'];
		$file = basename( $file );

		$g = new PDFCatalogGenerator();
		echo $g->getCachePath() . "categories" . DIRECTORY_SEPARATOR . $file . '.pdf';
		unlink( $g->getCachePath() . "categories" . DIRECTORY_SEPARATOR . $file . '.pdf' );
		unlink( $g->getCachePath() . 'html' . DIRECTORY_SEPARATOR . $file . '.html' );

		wp_die(); // this is required to terminate immediately and return a proper response
	}


	function getLogoURL() {
		$src = wp_get_attachment_image_src( get_option( 'pdfcat_logo' ), 'full' );
		if ( count( $src ) > 1 ) {
			$url = $src[0];
		} else {
			$url = '';
		}

		return $url;

	}

	function getLogoFilePath() {


		return get_attached_file( get_option( 'pdfcat_logo' ) );
	}


	static function load_admin_style() {
		wp_enqueue_media();
	}

	static function getCurrentCategoryID() {
		global $wp_query;
		$cat = $wp_query->get_queried_object();
		$class = get_class( $cat );

		if ( 'WP_Post' == $class ) {
			$post = $cat;
			if ( $post->post_type == 'product' ) {

				$cats = get_the_terms( $post->ID, 'product_cat' );
				if ( count( $cats ) > 0 ) {
					return end( $cats )->term_id;
				}
			}
		} else {
			if ( ( isset( $cat->taxonomy ) ) && ( $cat->taxonomy == 'product_cat' ) ) {
				return $cat->term_id;
			}
		}

		return null;
	}


	static function shortCode( $a, $title = null ) {
		if ( ! PDFCatalog::canViewCatalog() ) {
			return;
		}
		$a = shortcode_atts( array(
			'slug'     => null,
			'catids'   => null,
			'catid'    => 'all',
			'class'    => '',
			'children' => false,
			'template' => ''
		), $a );

		$c = new stdClass();
		$c->name = '';

		if ( $a['catids'] != null ) {
			$catIDs = PDFCatalogGenerator::sanitizeCatIDs( explode( ',', $a['catids'] ) );
			$categoryID = 'multiple';
		}
		if ( $a['slug'] != null ) {
			// check if slug exists
			$c = get_term_by( 'slug', $a['slug'], 'product_cat' );
			if ( $c !== false ) {
				$categoryID = $c->term_id;
			}
		} else if ( $a['catid'] != 'all' ) {


			if ( $a['catid'] == 'current' ) {
				$categoryID = static::getCurrentCategoryID();
				$c = get_term_by( 'id', $categoryID, 'product_cat' );
				if ( $categoryID == null ) {
					$categoryID = 'none';
				}
			} else {
				$c = get_term_by( 'id', $a['catid'], 'product_cat' );
				$categoryID = $a['catid'];
			}


			if ( $a['children'] && ( $categoryID != 'none' ) ) {

				$catIDs = get_term_children( (int) $categoryID, 'product_cat' );
				$catIDs = array_merge( array( (int) $categoryID ), $catIDs );
				$categoryID = 'multiple';
				$c = get_term_by( 'id', $a['catid'], 'product_cat' );
			}

		} else {
			$c->name = 'Full Catalog';
			$categoryID = 'all';
		}

		$template = '';
		if ($a['template']!='') {
			$template = '&template='.$a['template'];
		}

		if ( $categoryID != 'none' ) {
			ob_start();
			switch ( $categoryID ) {

				case 'all':
					$url = get_site_url() . '?pdfcat&all';
					break;
				case 'multiple':
					$url = get_site_url() . '?pdfcat&cm=' . implode( '-', $catIDs );
					break;
				default:
					$url = get_site_url() . '?pdfcat&c=' . $categoryID;
					break;
			}

			$url .= $template;

			?>
			<span class="PDFCatalogButtons<?php echo ' ' . $a['class']; ?>">
			<a href="<?php echo $url; ?>">
				<?php echo ( $title == null ) ? 'Download PDF Catalog' : str_replace( '%name%', $c->name, $title ); ?>
			</a>
		</span>

			<?php return ob_get_clean();
		} else {
			return '';
		}
	}


	static function headersettings_section() {
		{
			echo 'Customize your Catalog\'s header. The following variables can be used in your text:';
			echo "<ul><li>#store# - The site title.</li>
<li>#dategenerated# - The formatted date when the catalog was generated.</li>
    <li>
        #timegenerated# - The formatted time when the catalog was generated.
    </li>
</ul>";
		}
	}


	static function colors_section() {
		echo '<div style="max-width: 60%">You can use the following settings to change the PDF catalog colors. Keep in mind that some ';
		echo 'of your store\'s users might want to print your catalog, therefore you should ensure there is';
		echo 'enough contrast between background and text.</div>';
	}

	static function images_section() {
		echo '<div style="max-width: 60%">Use the following settings to tweak the quality and size of product images in the PDF output. ';
		echo 'Keep in mind that better quality means larger PDF files, more memory needed to render, and more processing time. ';
		echo 'If your store is running on shared hosting with limited resources you might want to avoid increasing the values of these settings.';
		echo '</div>';
	}


	static function invalidate_Cache( $inp ) {
		$cachePath = dirname( __FILE__ ) . '/cache/categories/';

		$files = glob( $cachePath . '/*.pdf' );

		if ( is_array( $files ) ) {
			foreach ( $files as $file ) {
				if ( is_file( $file ) ) {
					unlink( $file );
				}
			}
		}

		return $inp;

	}

	static function field_logo() {
		$imgid = get_option( 'pdfcat_logo' );
		$pdf = new PDFCatalog();
		$url = $pdf->getLogoURL();
		?>
		<input type="hidden" name="pdfcat_logo" id="pdfcat_logo" value="<?php echo get_option( 'pdfcat_logo' ); ?>"/>
		<img src="<?php echo $url; ?>"
		     style="max-width: 150px;max-height:150px; min-width: 80px; min-height: 80px; border:1px solid black; background: <?php echo get_option( 'pdfcat_headerColor' ); ?>"
		     id="pdfcat_logo_preview">
		<input class="button" id="fld_pdflogo_button" type="button" value="Choose Image"/>
	<?php }


	static function field_footer_text() {
		?>
		<p>
			The following text will appear at the end of each PDF file. Enter here copyright information, contact
			details etc.
		</p>
		<textarea id="headSubtitle" name="pdfcat_footerText"
		          style="width: 400px"><?php echo get_option( 'pdfcat_footerText' ); ?></textarea>

	<?php }

	static
	function field_headsubtitle_text() {
		?>
		<input type="text" id="headSubtitle" name="pdfcat_headSubtitle"
		       value="<?php echo get_option( 'pdfcat_headSubtitle' ); ?>" style="width: 400px">

	<?php }


	static function field_headtitle_text() {
		?>
		<input type="text" id="pdfcat_headTitle" name="pdfcat_headTitle"
		       value="<?php echo get_option( 'pdfcat_headTitle' ); ?>" style="width: 400px">
		<label for="pdfcat_headTitle">The main header title.</label>
	<?php }


	static function field_header_title_color() {
		?>
		<input type="color" id="pdfcat_headerTitle" name="pdfcat_headerTitle"
		       value="<?php echo get_option( 'pdfcat_headerTitle' ); ?>">
		<label for="pdfcat_headerTitle">used in header title.</label>
	<?php }


	static function field_category_color() {
		?>
		<input type="color" id="pdfcat_categoryColor" name="pdfcat_categoryColor"
		       value="<?php echo get_option( 'pdfcat_categoryColor' ); ?>">
		<label for="pdfcat_categoryColor">used in category titles.</label>
	<?php }


	static function field_header_lines_color() {
		?>
		<input type="color" id="pdfcat_headLinesColor" name="pdfcat_headLinesColor"
		       value="<?php echo get_option( 'pdfcat_headLinesColor' ); ?>">
		<label for="pdfcat_headLinesColor">used header & footer separator lines.</label>
	<?php }


	static function field_header_subtitle_color() {
		?>
		<input type="color" id="pdfcat_headerSubTitle" name="pdfcat_headerSubTitle"
		       value="<?php echo get_option( 'pdfcat_headerSubTitle' ); ?>">
		<label for="pdfcat_headerSubTitle">used header subtitle and page numbers.</label>
	<?php }

	static function field_price_color() {
		?>
		<input type="color" id="pdfcat_priceColor" name="pdfcat_priceColor"
		       value="<?php echo get_option( 'pdfcat_priceColor' ); ?>">
		<label for="pdfcat_priceColor">used in prices</label>
	<?php }

	static function field_light_color() {
		?>
		<input type="color" id="pdfcat_lightText" name="pdfcat_lightText"
		       value="<?php echo get_option( 'pdfcat_lightText' ); ?>">
		<label for="pdfcat_lightText">used in product descriptions</label>
	<?php }

	static function field_header_color() {
		?>
		<input type="color" id="pdfcat_headerColor" name="pdfcat_headerColor"
		       value="<?php echo get_option( 'pdfcat_headerColor' ); ?>">
		<label for="pdfcat_headerColor">header background color. Usually the same a paper color.</label>
	<?php }

	static function field_image_resolution() {
		//pdfcat_imagesize
		?>
		<select id="pdfcat_imagesize" name="pdfcat_imagesize">
			<?php foreach ( PDFCatalogGenerator::$imageSizes as $key => $size ) {
				?>
				<option value="<?php echo $key; ?>"<?php if ( $key == get_option( 'pdfcat_imagesize' ) ) {
					echo ' selected';
				} ?>>
					<?php echo $size[0]; ?>
				</option>
			<?php } ?>
		</select>

		<p style="max-width: 800px">
			By default PDF catalogs are using the same image resolution used by your current theme for product images.
			This sometimes can be too low for PDF (especially if you want to pint out the catalog). You can use this
			option to set it to a higher resolution. Keep in mind that, the higher the resolution the slower the
			process, the larger the PDF output files will be, and the more memory it will require to generate them, so
			be careful with this option.<br>
		</p>
		<p style="max-width: 800px">
			WordPress does not automatically resize old images, it only resizes them when they are firest uploaded, so
			if you change this option you should also install an image rebuilding plugin. There are several free plugins
			that do this job out there but we recommend <a href="https://wordpress.org/plugins/ajax-thumbnail-rebuild/">AJAX
				Thumbnail Rebuild</a> since we tested it and works well.
		</p>


	<?php }

	static function field_jpeg_quality() {
		?>
		<input type="range" id="pdfcat_jpegquality" name="pdfcat_jpegquality"
		       style="vertical-align: middle; margin-right: 1em" min="30" max="100" step="1"
		       value="<?php echo get_option( 'pdfcat_jpegquality' ); ?>">
		<span id="view_jpegquality"><?php echo get_option( 'pdfcat_jpegquality' ); ?>%</span>

		<p style="max-width: 800px">
			This option affects the way JPEG images are compressed. The lower the value the less emphasis is given on
			the image quality.
			Higher values means better quality but larger file size.
		</p>
		<script>
			jQuery(function () {
				var $ = jQuery;
				var el = $('#view_jpegquality');
				var inp = $('#pdfcat_jpegquality');
				inp.change(function (e) {
					el.text(inp.val() + '%');
				});
			});
		</script>
	<?php }

	static function field_paper_color() {
		?>
		<input type="color" id="paper_color" name="pdfcat_paperColor"
		       value="<?php echo get_option( 'pdfcat_paperColor' ); ?>">
		<label for="paper_color">catalog background color</label>
	<?php }


	static function field_text_color() {
		?>
		<input type="color" id="mainTextColor" name="pdfcat_mainText"
		       value="<?php echo get_option( 'pdfcat_mainText' ); ?>">
		<label for="mainTextColor">used in titles and headings</label>
	<?php }


	static function field_order_select() {
		?>
		<select name="pdfcat_order">
			<option value="desc">New to Old Products</option>
			<option value="asc">Old to New Products</option>
		</select>
	<?php }

	static function field_orderby_select() {
		$orderby = get_option( 'pdfcat_orderby' );
		?>
		<select name="pdfcat_orderby">
			<option value="date">Date product was created</option>
			<option value="price">Price</option>
		</select>
	<?php }


	public function getCategoryTree( $parent, $level = 0 ) {
		$cats = get_categories( array(
			'taxonomy'     => 'product_cat',
			'hierarchical' => 1,
			'parent'       => $parent
		) );

		foreach ( $cats as $k => $cat ) {
			$cats[ $k ]->level = $level;
			$cats[ $k ]->children = $this->getCategoryTree( $cat->term_id, $level + 1 );
		}

		return $cats;
	}

	public function renderCategoryTree( $cats, $preselected ) {
		echo '<ul>';
		foreach ( $cats as $cat ) {
			echo '<li>';

			echo '<input ';
			if ( array_search( $cat->term_id, $preselected ) !== false ) {
				echo 'checked ';
			}
			echo 'type="checkbox" id="fchk' . $cat->term_id . '" value="' . $cat->term_id . '"><label for="fchk' . $cat->term_id . '">' . $cat->name . '</label>';
			if ( count( $cat->children ) > 0 ) {
				$this->renderCategoryTree( $cat->children, $preselected );
			}
			echo '</li>';
		}
		echo '</ul>';
	}


	/**
	 * @return PDFFileListEntry[]
	 */
	static function getCachedFilesList() {
		$g = new PDFCatalogGenerator();
		$path = $g->getCachePath() . DIRECTORY_SEPARATOR . 'categories' . DIRECTORY_SEPARATOR;
		$files = scandir( $path );
		$out = array();

		foreach ( $files as $file ) {
			if ( ( $file != '.' ) && ( $file != '..' ) ) {
				$out[] = new PDFFileListEntry( $file, $path );
			}
		}

		return $out;
	}

}

$pdf = new PDFCatalog();
$pdf->init();
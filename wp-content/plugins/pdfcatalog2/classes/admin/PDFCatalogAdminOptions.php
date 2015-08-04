<?php 
/**
 * Created by PhpStorm.
 * User: Kyriakos
 * Date: 3/31/2015
 * Time: 19:19 PM
 */
class PDFCatalogSettingsPage {

	public $option_group;
	public $page;

	public function setupOptions() { }

	public function setupFields() { }

	public function setupSections() { }


	public function addSection( $sectionID, $title, $methodName ) {
		return new PDFCatalogSettingsSection( $sectionID, $title, $methodName, $this );
	}

	function __construct() {

		$this->setupOptions();
		$this->setupSections();
		$this->setupFields();

	}

}

class PDFCatalogSettingsSection {

	public $sectionID, $title, $methodName, $className, $pageName, $option_group;

	/**
	 * @param $sectionID
	 * @param $title
	 * @param $methodName
	 * @param $OptionsPage PDFTemplateOptionsPage
	 */
	function __construct( $sectionID, $title, $methodName, $OptionsPage ) {


		$this->option_group = $OptionsPage->option_group;
		$this->sectionID = $sectionID;
		$this->title = $title;
		$this->className = get_class( $OptionsPage );
		$this->pageName = $OptionsPage->page;

		add_settings_section(
			$sectionID, // ID
			$title, // Title
			array( $this->className, $methodName ), // Callback
			$this->pageName // Page
		);
	}

	function addField( $fieldID, $fieldTitle, $defaultValue, $method, $saveCallback = '' ) {
		add_option( $fieldID, $defaultValue );

		add_settings_field(
			$fieldID, // ID
			$fieldTitle, // Title
			array( $this->className, $method ),
			$this->pageName, // Page
			$this->sectionID // Section
		);

		if ( $saveCallback == '' ) {
			register_setting( $this->option_group, $fieldID );
		} else {
			if ( is_array( $saveCallback ) ) {
				register_setting( $this->option_group, $fieldID, $saveCallback );
			} else {
				register_setting( $this->option_group, $fieldID, array( $this->className, $saveCallback ) );
			}
		}

	}

}

class PDFCatalogAdminOptions {

	public function __construct() {
		include_once "TemplateOptionsPage.php";
		include_once "CacheOptionsPage.php";
		include_once "CategoryOptionsPage.php";
	}

	public function initOptions() {


		add_option( 'pdfcat_cache', '1' );
		// Color Options
		add_option( 'pdfcat_paperColor', '#ffffff' );
		add_option( 'pdfcat_headerColor', '#ffffff' );
		add_option( 'pdfcat_mainText', '#333333' );
		add_option( 'pdfcat_lightText', '#555555' );
		add_option( 'pdfcat_priceColor', '#555555' );
		add_option( 'pdfcat_categoryColor', '#333333' );
		add_option( 'pdfcat_headerTitle', '#333333' );
		add_option( 'pdfcat_headerSubTitle', '#777777' );
		add_option( 'pdfcat_headLinesColor', '#777777' );
		add_option( 'pdfcat_imagesize', 'default' );
		add_option( 'pdfcat_jpegquality', '70' );

		// Header+Footer Options
		add_option( 'pdfcat_headTitle', '#store# catalog' );
		add_option( 'pdfcat_headSubtitle', 'This catalog was generated on #dategenerated#' );
		add_option( 'pdfcat_logo', '' );
		add_option( 'pdfcat_footerText', '' );


		add_option( 'pdfcat_externalengine', '1' );
		//pdfheadsettings

		add_action( 'admin_init', array( 'PDFCatalogAdminOptions', 'admin_init' ) );
	}


	static function admin_init() {
		{

			if ( function_exists( 'icl_register_string' ) ) {
				icl_register_string( 'PDF Catalog for WooCommerce', 'Header Title', get_option( 'pdfcat_headTitle' ) );
				icl_register_string( 'PDF Catalog for WooCommerce', 'Sub Heading', get_option( 'pdfcat_headSubtitle' ) );
				icl_register_string( 'PDF Catalog for WooCommerce', 'Bottom Text', get_option( 'pdfcat_footerText' ) );
				icl_register_string( 'PDF Catalog for WooCommerce', 'total products', 'total products.' );
			}


			new PDFTemplateOptionsPage();
			new PDFCacheOptionsPage();
			new PDFCategoryOptionsPage();


			// ---- Header/Footer -----------------------
			add_settings_section(
				'headersettings_section', // ID
				'Header Settings', // Title
				array( 'PDFCatalog', 'headersettings_section' ), // Callback
				'pdfheadsettings' // Page
			);

			register_setting( 'pdfgen-page3', 'pdfcat_header', array( "PDFCatalog", 'invalidate_Cache' ) );


			add_settings_field(
				'pdfcat_logo', // ID
				'Logo', // Title
				array( "PDFCatalog", 'field_logo' ),
				'pdfheadsettings', // Page
				'headersettings_section' // Section
			);

			register_setting( 'pdfgen-page3', 'pdfcat_logo' );

			add_settings_field(
				'pdfcat_headTitle', // ID
				'Title', // Title
				array( "PDFCatalog", 'field_headtitle_text' ),
				'pdfheadsettings', // Page
				'headersettings_section' // Section
			);

			register_setting( 'pdfgen-page3', 'pdfcat_headTitle' );


			add_settings_field(
				'pdfcat_headSubtitle', // ID
				'Sub heading', // Title
				array( "PDFCatalog", 'field_headsubtitle_text' ),
				'pdfheadsettings', // Page
				'headersettings_section' // Section
			);

			register_setting( 'pdfgen-page3', 'pdfcat_headSubtitle' );


			add_settings_field(
				'pdfcat_footerText', // ID
				'Bottom Text', // Title
				array( "PDFCatalog", 'field_footer_text' ),
				'pdfheadsettings', // Page
				'headersettings_section' // Section
			);

			register_setting( 'pdfgen-page3', 'pdfcat_footerText' );


			// ---- Template ----------------------------


			/*
						add_settings_field(
							'pdfcat_orderby', // ID
							'Sort products by', // Title
							array($this, 'field_orderby_select'),
							'pdfgensettings', // Page
							'theme_section' // Section
						);

						register_setting('pdfgen-page1', 'pdfcat_orderby');

						add_settings_field(
							'pdfcat_order', // ID
							'Sort direction', // Title
							array($this, 'field_order_select'),
							'pdfgensettings', // Page
							'theme_section' // Section
						);

						register_setting('pdfgen-page1', 'pdfcat_order');
			*/


			// --- COLORS ----------------------------------------------------------

			add_settings_section(
				'colors_section', // ID
				'Colors', // Title
				array( 'PDFCatalog', 'colors_section' ), // Callback
				'pdfcolsettings' // Page
			);


			add_settings_section(
				'images_section', // ID
				'Image Settings', // Title
				array( 'PDFCatalog', 'images_section' ), // Callback
				'pdfcolsettings' // Page
			);


			add_settings_field(
				'pdfcat_imagesize', // ID
				'Image Resolution', // Title
				array( "PDFCatalog", 'field_image_resolution' ),
				'pdfcolsettings', // Page
				'images_section' // Section
			);

			register_setting( 'pdfgen-page2', 'pdfcat_imagesize', array( "PDFCatalog", 'invalidate_Cache' ) );


			add_settings_field(
				'pdfcat_jpegquality', // ID
				'JPEG Quality', // Title
				array( "PDFCatalog", 'field_jpeg_quality' ),
				'pdfcolsettings', // Page
				'images_section' // Section
			);

			register_setting( 'pdfgen-page2', 'pdfcat_jpegquality', array( "PDFCatalog", 'invalidate_Cache' ) );

			/*
			add_settings_field(
				'pdfcat_paperColor', // ID
				'Paper (Background)', // Title
				array( "PDFCatalog", 'field_paper_color' ),
				'pdfcolsettings', // Page
				'colors_section' // Section
			);

			*/

			register_setting( 'pdfgen-page2', 'pdfcat_paperColor', array( "PDFCatalog", 'invalidate_Cache' ) );

			add_settings_field(
				'pdfcat_headerColor', // ID
				'Header Background', // Title
				array( "PDFCatalog", 'field_header_color' ),
				'pdfcolsettings', // Page
				'colors_section' // Section
			);

			register_setting( 'pdfgen-page2', 'pdfcat_headerColor' );


			add_settings_field(
				'pdfcat_mainText', // ID
				'Product Title', // Title
				array( "PDFCatalog", 'field_text_color' ),
				'pdfcolsettings', // Page
				'colors_section' // Section
			);

			register_setting( 'pdfgen-page2', 'pdfcat_mainText' );

			add_settings_field(
				'pdfcat_lightText', // ID
				'Product Description', // Title
				array( "PDFCatalog", 'field_light_color' ),
				'pdfcolsettings', // Page
				'colors_section' // Section
			);

			register_setting( 'pdfgen-page2', 'pdfcat_lightText' );

			add_settings_field(
				'pdfcat_priceColor', // ID
				'Prices', // Title
				array( "PDFCatalog", 'field_price_color' ),
				'pdfcolsettings', // Page
				'colors_section' // Section
			);

			register_setting( 'pdfgen-page2', 'pdfcat_priceColor' );

			add_settings_field(
				'pdfcat_categoryColor', // ID
				'Category Title', // Title
				array( "PDFCatalog", 'field_category_color' ),
				'pdfcolsettings', // Page
				'colors_section' // Section
			);

			register_setting( 'pdfgen-page2', 'pdfcat_categoryColor' );

			add_settings_field(
				'pdfcat_headerTitle', // ID
				'Header Title Color', // Title
				array( "PDFCatalog", 'field_header_title_color' ),
				'pdfcolsettings', // Page
				'colors_section' // Section
			);

			register_setting( 'pdfgen-page2', 'pdfcat_headerTitle' );

			add_settings_field(
				'pdfcat_headerSubtitle', // ID
				'Header Subtitle Color', // Title
				array( "PDFCatalog", 'field_header_subtitle_color' ),
				'pdfcolsettings', // Page
				'colors_section' // Section
			);

			register_setting( 'pdfgen-page2', 'pdfcat_headerSubTitle' );

			add_settings_field(
				'pdfcat_headerSubtitle', // ID
				'Header Subtitle Color', // Title
				array( "PDFCatalog", 'field_header_subtitle_color' ),
				'pdfcolsettings', // Page
				'colors_section' // Section
			);

			register_setting( 'pdfgen-page2', 'pdfcat_headerSubTitle' );

			add_settings_field(
				'pdfcat_headLinesColor', // ID
				'Header/Footer Line Color', // Title
				array( "PDFCatalog", 'field_header_lines_color' ),
				'pdfcolsettings', // Page
				'colors_section' // Section
			);

			register_setting( 'pdfgen-page2', 'pdfcat_headLinesColor' );


		}
	}

}
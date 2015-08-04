<?php
use GuzzleHttp\Client;

/**
 * Class PDFCatalogGenerator
 *
 * @param Renderer $pdf
 */
class PDFCatalogGenerator {


	private $pdfCatalog;

	static $temp = 0;
	static $pdfOut = false;
	static $pdf;

	private $products = array();
	private $currentProduct = - 1;
	private $productCount = 0;
	private $category = null;

	public $options = null;
	public $logoURL = '', $hasLogo = false;


	public $skipHeaderFooter = false;
	public $author = '';
	public $logo = '';
	public $title = '';
	public $subtitle = '';
	public $subject = '';
	public $keywords = "";
	private $lastCategoryType = null;

	static $config = null;
	static $lastProductUpdateTime = 0;
	static $imageSizes = array(
		'default' => array( 'Default', - 1, - 1 ),
		'low'     => array( 'Low', 80, 80 ),
		'medium'  => array( 'Medium', 320, 320 ),
		'high'    => array( 'High', 640, 640 ),
		'super'   => array( 'Super High', 1280, 1280 )
	);

	static $template = 'basiclist';
	static $templates = array();

	function prepare( $s ) {

		return $this->processShortcodes( $this->limit( $s ) );
	}

	static function toLog( $msg ) {
		if ( PDF_LOG ) {
			$logfile = dirname( __FILE__ ) . '/logs/' . date( 'Y-m-d' ) . '.log';
			file_put_contents( $logfile, date( 'Y-m-d H:i:s' ) . ' ' . $_SERVER['REMOTE_ADDR'] . ' ' . $msg . "\n\r", FILE_APPEND );
		}
	}


	function processShortcodes( $s ) {
		if ( $this->options->renderShortcodes == 0 ) {
			return strip_shortcodes( $s );
		} else {
			return do_shortcode( $s );
		}
	}

	function limit( $s ) {
		if ( $this->options->characterLimit == 0 ) {
			return $s;
		} else {
			return $this->htmlStringCut( $s, $this->options->characterLimit, '...', false, true );
		}
	}


	function __construct() {

		$pdfC = new PDFCatalog();
		$this->pdfCatalog = $pdfC;

		PDFCatalogGenerator::$template = get_option( 'pdfcat_template' );


		$this->logo = $pdfC->getLogoURL();
		$this->options = new stdClass();
		$this->options->characterLimit = get_option( 'pdfcat_characterLimit' );
		$this->options->renderShortcodes = get_option( 'pdfcat_renderShortcodes' );
		$this->options->showCategoryTitle = ( get_option( 'pdfcat_showCategoryTitle' ) );
		$this->options->showCategoryProductCount = ( get_option( 'pdfcat_showCategoryProductCount' ) == 1 );
		$this->options->showSKU = ( get_option( 'pdfcat_showSKU' ) == 1 );
		$this->options->showDescription = ( get_option( 'pdfcat_showDescription' ) == 1 );
		$this->options->showPrice = ( get_option( 'pdfcat_showPrice' ) == 1 );
		PDFCatalogGenerator::$pdfOut = ( get_option( 'pdfcat_html' ) == 0 );


	}

	static function replaceTextVars( $t ) {
		$vars = array( '#store#', '#dategenerated#', '#timegenerated#' );
		$replace = array(
			get_bloginfo(),
			date( get_option( 'date_format' ) ),
			date( 'H:i' )
		);

		return str_replace( $vars, $replace, $t );
	}

	static function hex2rgb( $hex ) {
		$hex = str_replace( "#", "", $hex );

		if ( strlen( $hex ) == 3 ) {
			$r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
			$g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
			$b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
		} else {
			$r = hexdec( substr( $hex, 0, 2 ) );
			$g = hexdec( substr( $hex, 2, 2 ) );
			$b = hexdec( substr( $hex, 4, 2 ) );
		}
		$rgb = array( $r, $g, $b );

		return $rgb;
	}

	static function getChildTemplatesURL() {
		return get_stylesheet_directory_uri() . '/pdf/';
	}

	static function getChildTemplatesPath() {
		return get_stylesheet_directory() . DIRECTORY_SEPARATOR . 'pdf' . DIRECTORY_SEPARATOR;
	}

	static function getTemplates() {

		$childPDFThemeDir = static::getChildTemplatesPath();

		require dirname( __FILE__ ) . '/templates/pdf/templates.php';

		if ( file_exists( $childPDFThemeDir ) ) {

			$dirs = glob( $childPDFThemeDir . '*', GLOB_ONLYDIR );

			foreach ( $dirs as $dir ) {
				if ( file_exists( $dir . DIRECTORY_SEPARATOR . 'template.php' ) ) {
					include $dir . DIRECTORY_SEPARATOR . 'template.php';
					$t = end( $templates );
					$key = key( $templates );
					$t['child'] = true;
					$templates[ $key ] = $t;
				}
			}

		}

		return $templates;
	}


	private function htmlStringCut( $text, $length = 100, $ending = '...', $exact = false, $considerHtml = true ) {
		$open_tags = array();
		if ( $considerHtml ) {

			if ( strlen( preg_replace( '/<.*?>/', '', $text ) ) <= $length ) {
				return $text;
			}

			preg_match_all( '/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER );
			$total_length = strlen( $ending );

			$truncate = '';
			foreach ( $lines as $line_matchings ) {
				if ( ! empty( $line_matchings[1] ) ) {
					if ( preg_match( '/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1] ) ) {
					} else if ( preg_match( '/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings ) ) {
						$pos = array_search( $tag_matchings[1], $open_tags );
						if ( $pos !== false ) {
							unset( $open_tags[ $pos ] );
						}
					} else if ( preg_match( '/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings ) ) {
						array_unshift( $open_tags, strtolower( $tag_matchings[1] ) );
					}
					$truncate .= $line_matchings[1];
				}
				$content_length = strlen( preg_replace( '/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2] ) );
				if ( $total_length + $content_length > $length ) {
					$left = $length - $total_length;
					$entities_length = 0;
					if ( preg_match_all( '/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE ) ) {
						foreach ( $entities[0] as $entity ) {
							if ( $entity[1] + 1 - $entities_length <= $left ) {
								$left --;
								$entities_length += strlen( $entity[0] );
							} else {
								break;
							}
						}
					}
					$truncate .= substr( $line_matchings[2], 0, $left + $entities_length );
					break;
				} else {
					$truncate .= $line_matchings[2];
					$total_length += $content_length;
				}
				if ( $total_length >= $length ) {
					break;
				}
			}
		} else {
			if ( strlen( $text ) <= $length ) {
				return $text;
			} else {
				$truncate = substr( $text, 0, $length - strlen( $ending ) );
			}
		}
		if ( ! $exact ) {
			$spacepos = strrpos( $truncate, ' ' );
			if ( isset( $spacepos ) ) {
				$truncate = substr( $truncate, 0, $spacepos );
			}
		}
		$truncate .= $ending;
		if ( $considerHtml ) {
			foreach ( $open_tags as $tag ) {
				$truncate .= '</' . $tag . '>';
			}
		}

		return $truncate;
	}


	function hasCustomHeader() {
		return file_exists( $this->getTemplatePath() . 'header.php' );
	}

	function hasCustomFooter() {
		return file_exists( $this->getTemplatePath() . 'footer.php' );
	}

	function getCustomFooter() {
		if ( $this->hasCustomFooter() ) {
			/* @var $pdf K_PDFRenderer */
			$pdf = PDFCatalogGenerator::$pdf;
			$subtitle = $pdf->k_headerSubTitle;
			$title = $pdf->k_headerTitle;
			$logo = $this->logo;

			ob_start();
			include $this->getTemplatePath() . 'footer.php';

			return ob_get_clean();
		} else {
			return '';
		}
	}


	function getCustomHeader() {
		if ( $this->hasCustomHeader() ) {
			/* @var $pdf K_PDFRenderer */
			$pdf = PDFCatalogGenerator::$pdf;
			$subtitle = $pdf->k_headerSubTitle;
			$title = $pdf->k_headerTitle;
			$logo = $this->logo;

			ob_start();
			include $this->getTemplatePath() . 'header.php';

			return ob_get_clean();
		} else {
			return '';
		}
	}


	function getCategoryItems( $category, $includeExtras = false, $nochildren = true ) {


		$order = get_option( 'pdfcat_order' );
		$orderby = get_option( 'pdfcat_orderby' );

		//$order = 'title';
		//$orderby ='asc';
		$showHidden = get_option( 'pdfcat_showhidden' );
		$hideOutOfStock = get_option( 'pdfcat_hideoutofstock' );

		$args = array(
			'post_type'      => 'product',
			'posts_per_page' => - 1,
			'product_cat'    => $category,
			'order'          => $order,
			'orderby'        => $orderby,
		);

		if ( $nochildren ) {
			$args['tax_query'] = array(
				array(
					'taxonomy'         => 'product_cat',
					'field'            => 'slug',
					'terms'            => $category,
					'include_children' => false
				)
			);
		}

		if ( $showHidden != 1 ) {
			$args['meta_query'][] =
				array(
					'key'     => '_visibility',
					'value'   => array( 'catalog', 'visible' ),
					'compare' => 'IN'
				);
		}

		if ( $hideOutOfStock == 1 ) {
			$args['meta_query'][] =
				array(
					'key'     => '_stock_status',
					'value'   => 'instock',
					'compare' => '='
				);
		}


		$loop = new WP_Query( $args );
		//	var_dump($args,$loop);
		$items = array();

		$posts = $loop->posts;

		$products = array();


		if ( get_option( 'pdfcat_imagesize' ) == 'default' ) {
			$addImageSize = 'thumbnail';
		} else {
			$addImageSize = 'pdf_catalog';
		}

		if ( $includeExtras ) {
			foreach ( $posts as $post ) {

				$product = get_product( $post->ID );

				$terms = get_the_terms( $post->ID, 'product_cat' );


				$product->post->thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), $addImageSize );

				if ( isset( $product->post->thumbnail[0] ) ) {
					$product->post->thumbnail = $product->post->thumbnail[0];
				} else {
					// no image found
				}


				$products[] = $product;


			}
		}

		return $products;
	}

	function row() {
		$pdf = $this;
		$productCount = $this->productCount;
		$count = $this->currentProduct + 1;
		$category = $this->category;
		$templatePath = PDFCatalogGenerator::getTemplatePath();

		ob_start();
		include $templatePath . 'row.php';
		$html = ob_get_clean();

		echo $html;


	}

	function hasMoreProducts() {
		return ! ( $this->currentProduct + 1 >= $this->productCount );

	}

	function product() {


		$templatePath = PDFCatalogGenerator::getTemplatePath();
		$this->currentProduct ++;
		if ( $this->currentProduct > $this->productCount - 1 ) {
			return;
		}

		$product = $this->products[ $this->currentProduct ];
		$post = $product->post;

		$paperColor = get_option( 'pdfcat_paperColor' );
		$titleColor = get_option( 'pdfcat_mainText' );
		$textColor = get_option( 'pdfcat_lightText' );
		$priceColor = get_option( 'pdfcat_priceColor' );
		$categoryTitleColor = get_option( 'pdfcat_categoryColor' );


		$hasVariations = false;

		$variations = array();
		if ( $product->product_type == 'variable' ) {
			$hasVariations = true;
			$attributes = $product->get_variation_attributes();
			foreach ( $attributes as $k => $a ) {
				$variations [ wc_attribute_label( $k ) ] = $a;
			}

		}

		include $templatePath . 'beforeProduct.php';
		include $templatePath . 'product.php';
		include $templatePath . 'afterProduct.php';


	}


	static function getTemplatePath() {
		$t = PDFCatalogGenerator::$templates[ PDFCatalogGenerator::$template ];
		if ( isset( $t['child'] ) ) {
			return static::getChildTemplatesPath() . PDFCatalogGenerator::$template . DIRECTORY_SEPARATOR;
		} else {
			return dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'pdf' . DIRECTORY_SEPARATOR . PDFCatalogGenerator::$template . DIRECTORY_SEPARATOR;
		}
	}

	static function getTemplateURL() {
		$t = PDFCatalogGenerator::$templates[ PDFCatalogGenerator::$template ];
		if ( isset( $t['child'] ) ) {
			return static::getChildTemplatesURL() . PDFCatalogGenerator::$template . '/';
		} else {
			return plugins_url() . '/pdfcatalog2/templates/pdf/' . PDFCatalogGenerator::$template . '/';
		}
	}

	function getProductCategoriesFromList( $catIDs ) {
		$args = array(
			'taxonomy'     => 'product_cat',
			'orderby'      => 'term_group',
			'pad_counts'   => 0,
			'hierarchical' => 1,
			'title_li'     => '',
			'hide_empty'   => (int) get_option( 'pdfcat_hideemptycategories' ),
			'include'      => implode( ',', $catIDs )
		);

		$categories = get_categories( $args );

		return $categories;
	}

	function getProductCategories() {

		$args = array(
			'taxonomy'     => 'product_cat',
			'orderby'      => 'name',
			'pad_counts'   => 0,
			'hierarchical' => 0,
			'title_li'     => '',
			'hide_empty'   => (int) get_option( 'pdfcat_hideemptycategories' )
		);

		$categories = get_categories( $args );
		$selected = get_option( 'pdfcat_categories' );

		$all = ( $selected == '' );

		if ( ! $all ) {
			$selected = array_flip( explode( ',', $selected ) );
		}

		$out = array();

		if ( $all ) {
			$out = $categories;
		} else {
			foreach ( $categories as $key => $category ) {
				if ( isset( $selected[ $category->term_id ] ) ) {
					$out[] = $category;
				}
			}
		}

		return $out;


	}

	static function sanitizeCatIDs( $catIDs ) {
		for ( $i = 0; $i < count( $catIDs ); $i ++ ) {
			$catIDs[ $i ] = (int) $catIDs[ $i ];
		}

		$catIDs2 = array_unique( $catIDs );
		sort( $catIDs2, SORT_NUMERIC );

		return $catIDs2;
	}


	function generateCategoryPDF( $category, $lang ) {

		$templatePath = $this->getTemplatePath();
		$templateURL = $this->getTemplateURL();
		if ( ! $this->skipHeaderFooter ) {
			include $templatePath . 'beforeCatalog.php';
		}
		$this->generateCategory( $category );
		if ( ! $this->skipHeaderFooter ) {
			include $templatePath . 'afterCatalog.php';
		}
	}

	private function categoryHasChildren( $category ) {

		$children = get_terms( $category->taxonomy, array(
			'parent'     => $category->term_id,
			'hide_empty' => false
		) );

		return ( count( $children ) > 0 );

	}

	private function  generateCategory( $category, $products = null ) {

		if ( $products == null ) {
			$this->products = $this->getCategoryItems( $category->slug, true );
		} else {
			$this->products = $products;
		}
		$templatePath = PDFCatalogGenerator::getTemplatePath();

		$this->currentProduct = - 1;
		$this->productCount = count( $this->products );
		$this->category = $category;

		$paperColor = get_option( 'pdfcat_paperColor' );
		$titleColor = get_option( 'pdfcat_mainText' );
		$textColor = get_option( 'pdfcat_lightText' );
		$priceColor = get_option( 'pdfcat_priceColor' );
		$categoryTitleColor = get_option( 'pdfcat_categoryColor' );


		$hasChildCategories = $this->categoryHasChildren( $category );

		$hideProducts = ( get_option( 'pdfcat_hideparentcategories' ) == 1 ) && $hasChildCategories;
		$startOnNewPage = ( get_option( 'pdfcat_startOnNewPage' ) == '1' );


		$mustBreak = false;
		if ( $startOnNewPage ) {
			if ( $this->lastCategoryType == 'hadProducts' ) {
				$mustBreak = true;
			}
		}

		ob_start();
		include $templatePath . 'beforeList.php';
		$html = ob_get_clean();

		echo $html;

		$this->lastCategoryType = 'noProducts';

		if ( ! $hideProducts ) {

			while ( $this->hasMoreProducts() ) {
				$this->row();
			}

			if ( $this->productCount > 0 ) {
				$this->lastCategoryType = 'hadProducts';
			}
		}
		ob_start();
		include $templatePath . 'afterList.php';
		$html = ob_get_clean();
		echo $html;

	}

	static function getCacheURL( $filename = '' ) {
		return plugins_url() . '/pdfcatalog2/cache/' . $filename;
	}

	static function getHTMLCacheURL( $filename = '' ) {
		return plugins_url() . '/pdfcatalog2/cache/html/' . $filename;
	}


	static function downloadTo( $url, $destination ) {
		try {
			include __DIR__ . '/CurlHelper.php';

			CurlHelper::downloadFile( $url, $destination );

			return true;

		} catch ( Exception $e ) {
			echo 'Error Receiving PDF File' . $e->getMessage() . ' (' . $e->getCode() . ')';

			return false;
		}
	}


	function generate( $cat, $lang ) {
		ob_start();
		$this->generateCategoryPDF( $cat, $lang );

		return ob_get_clean();
	}


	function flattenTree( $tree ) {
		$res = array();

		foreach ( $tree as $entry ) {
			$children = $entry->children;
			unset( $entry->children );
			$res[] = $entry;
			$res = array_merge( $res, $this->flattenTree( $children ) );
		}

		return $res;
	}

	function generateFull( $lang, $catIDs = null ) {

		if ( $catIDs != null ) {
			$categories = $this->getProductCategoriesFromList( $catIDs );
		} else {
			if ( get_option( 'pdfcat_category_hierarchy' ) == 'flat' ) {
				$categories = $this->getProductCategories();
			} else {
				$categories = $this->flattenTree( $this->pdfCatalog->getCategoryTree( 0, 0 ) );
			}
		}
		$html = '';

		$notFirst = false;
		$startOnNew = ( get_option( 'pdfcat_startOnNewPage' ) == 1 );
		$templateURL = $this->getTemplateURL();
		$paperColor = get_option( 'pdfcat_paperColor' );
		$titleColor = get_option( 'pdfcat_mainText' );
		$textColor = get_option( 'pdfcat_lightText' );
		$priceColor = get_option( 'pdfcat_priceColor' );
		$categoryTitleColor = get_option( 'pdfcat_categoryColor' );

		$this->skipHeaderFooter = true;


		$templatePath = $this->getTemplatePath();

		ob_start();
		include $templatePath . 'beforeCatalog.php';
		$html = ob_get_clean();

		foreach ( $categories as $category ) {
			$html .= $this->generate( $category, $lang ) . "\n\r\n\r";
		}

		ob_start();
		include $templatePath . 'afterCatalog.php';
		$html .= ob_get_clean();


		return $html;

	}


	static function getHeaderHTML() {
		ob_start();
		$pdfC = new PDFCatalog();
		$logo = $pdfC->getLogoURL();

		$headerColor = get_option( 'pdfcat_paperColor' );
		$headerBackgroundColor = get_option( 'pdfcat_headerColor' );
		$headerTitleColor = get_option( 'pdfcat_headerTitle' );
		$headerSubTitleColor = get_option( 'pdfcat_headerSubTitle' );

		$showLines = ( get_option( 'pdfcat_headLines' ) == 1 );
		$lineColor = get_option( 'pdfcat_headLinesColor' );

		if ( function_exists( 'icl_t' ) ) {
			$headerTitle = static::replaceTextVars( icl_t( 'PDF Catalog for WooCommerce', 'Header Title', '#store# catalog' ) );
			$headerSubTitle = static::replaceTextVars( icl_t( 'PDF Catalog for WooCommerce', 'Sub Heading', 'This catalog was generated on #dategenerated#' ) );
		} else {
			$headerTitle = static::replaceTextVars( get_option( 'pdfcat_headTitle' ) );
			$headerSubTitle = static::replaceTextVars( get_option( 'pdfcat_headSubtitle' ) );
		}

		$templateURL = PDFCatalogGenerator::getTemplateURL();

		include PDFCatalogGenerator::getTemplatePath() . 'header.php';

		return ob_get_clean();
	}

	static function send( $html, $slug ) {


		$cachePath = dirname( __FILE__ ) . '/cache/html/';
		$cachePathPDF = dirname( __FILE__ ) . '/cache/categories/';

		$filename = $slug . '.html';
		$destination = $cachePathPDF . $slug . '.pdf';

		if ( file_put_contents( $cachePath . $filename, $html ) === false ) {
			echo 'PDFCatalog: Failed to write HTML';
		} else {

			$headerHTML = static::getHeaderHTML();

			file_put_contents( $cachePath . 'header.html', $headerHTML );

			include "settings.php";


			if ( version_compare( PHP_VERSION, '5.4.0', '>=' ) ) {
				$c = new Client();
				$request = $c->createRequest( 'POST', $apiEndPoint, array( 'future' => false ) );

				$body = $request->getBody();
				$body->setField( 'htmlURL', PDFCatalogGenerator::getHTMLCacheURL( $filename ) );
				$body->setField( 'headerURL', PDFCatalogGenerator::getHTMLCacheURL( 'header.html' ) );
				$body->setField( 'apiversion', 0.1 );
				$body->setField( 'e', trim( get_option( 'pdfcat_purchasecode' ) ) );
				$body->setField( 'domain', $_SERVER['HTTP_HOST'] );
				$body->setField( 'jpegquality', (int) get_option( 'pdfcat_jpegquality' ) );
				$body->setField( 'plugin_version', PDFCATALOG_VERSION );
				$body->setField( 'php_version', phpversion() );

				$r = $c->send( $request );

				PDFCatalogGenerator::toLog( print_r( $r->getBody()->__toString(), true ) );
				// @TODO need to catch possible exception here
				$result = (object) $r->json();

				//echo($r->getBody());
			} else {
				$data = array(
					'htmlURL'        => PDFCatalogGenerator::getHTMLCacheURL( $filename ),
					'headerURL'      => PDFCatalogGenerator::getHTMLCacheURL( 'header.html' ),
					'apiversion'     => 0.1,
					'e'              => trim( get_option( 'pdfcat_purchasecode' ) ),
					'domain'         => $_SERVER['HTTP_HOST'],
					'jpegquality'    => (int) get_option( 'pdfcat_jpegquality' ),
					'plugin_version' => PDFCATALOG_VERSION,
					'php_version'    => phpversion()
				);

				$res = static::http_post( $apiEndPoint, $data );
				$result = json_decode( $res['content'] );
			}

			return $result;

		}

	}

	static function http_post( $url, $data ) {
		$data_url = http_build_query( $data );
		$data_len = strlen( $data_url );

		return array(
			'content' => file_get_contents( $url, false, stream_context_create( array(
				'http' => array(
					'method'  => 'POST'
					,
					'header'  => "Connection: close\r\nContent-Length: $data_len\r\n"
					,
					'content' => $data_url
				)
			) ) )
			,
			'headers' => $http_response_header
		);

	}


	static function getLastProductUpdateTime( $category = null ) {
		if ( static::$lastProductUpdateTime == 0 ) {
			if ( $category != null ) {
				$args = array(
					'post_type'      => 'product',
					'posts_per_page' => 1,
					'product_cat'    => $category->slug,
					'orderby'        => 'modified',
					'order'          => 'DESC'
				);
			} else {
				$args = array(
					'post_type'      => 'product',
					'posts_per_page' => 1,
					'orderby'        => 'modified',
					'order'          => 'DESC'
				);
			}

			$loop = new WP_Query( $args );
			$posts = $loop->posts;

			if ( count( $posts ) > 0 ) {
				static::$lastProductUpdateTime = strtotime( $posts[0]->post_modified );
			} else {
				static::$lastProductUpdateTime = PHP_INT_MAX;
			}
		}

		return static::$lastProductUpdateTime;
	}

	function mustRegenerate( $slug, $lang, $lastProductUpdateTime, $useCache ) {

		if ( ! $useCache ) {
			return true;
		} else {

			$cachePDF = $this->getCacheFilePathForSlug( $slug, $lang, 'pdf' );
			$cacheHTML = $this->getCacheFilePathForSlug( $slug, $lang, 'html' );

			if ( ! file_exists( $cachePDF ) || ( ! file_exists( $cacheHTML ) ) ) {
				return true;
			} else {
				$lastPDFGeneratedTime = filemtime( $cachePDF );
				$lastHTMLGeneratedTime = filemtime( $cacheHTML );

				return ( ( $lastProductUpdateTime > $lastPDFGeneratedTime ) || ( $lastProductUpdateTime > $lastHTMLGeneratedTime ) );
			}
		}
	}

	function allCategories( $lang, $useCache, $catIDs = null ) {
		PDFCatalogGenerator::toLog( 'allCategories: ENTRY' );

		$cacheFile = dirname( __FILE__ ) . '/cache/categories/full' . $lang . '.pdf';

		if ( $catIDs != null ) {
			$slug = implode( '-', $catIDs );
			$cacheFile = dirname( __FILE__ ) . '/cache/categories/' . $slug . $lang . '.pdf';
		} else {
			$slug = 'full';
			$cacheFile = dirname( __FILE__ ) . '/cache/categories/full' . $lang . '.pdf';
		}

		if ( $this->mustRegenerate( $slug, $lang, static::getLastProductUpdateTime(), $useCache ) ) {
			PDFCatalogGenerator::toLog( 'Cache Hit:FALSE' );
			$html = $this->generateFull( $lang, $catIDs );
			$cacheHit = false;
		} else {
			PDFCatalogGenerator::toLog( 'Cache Hit:TRUE' );
			$html = file_get_contents( $this->getCacheFilePathForSlug( $slug, $lang, 'html' ) );
			$cacheHit = true;
		}

		PDFCatalogGenerator::toLog( 'allCategories: EXIT' );

		return array( $html, $slug, $cacheFile, $cacheHit );
	}


	function getSlugForCatalog( $slug ) {
		return $slug;
	}

	function getCachePath() {
		return dirname( __FILE__ ) . '/cache/';
	}

	function getCacheFilePathForSlug( $slug, $lang, $pdfOrHTML ) {
		$subDir = ( $pdfOrHTML == 'pdf' ) ? 'categories' : 'html';
		$ext = ( $pdfOrHTML == 'pdf' ) ? 'pdf' : 'html';

		return $this->getCachePath() . $subDir . DIRECTORY_SEPARATOR . $slug . $lang . '.' . $ext;
	}

	function singleCategory( $catID, $lang, $useCache ) {
		PDFCatalogGenerator::toLog( 'getCatalogEx: catID: ' . $catID );
		$cat = get_term_by( 'id', $catID, 'product_cat' );

		$cacheHit = false;

		if ( $cat ) {
			$slug = $cat->slug;

			$cacheFile = $this->getCacheFilePathForSlug( $slug, $lang, 'pdf' );

			if ( $this->mustRegenerate( $slug, $lang, static::getLastProductUpdateTime( $cat ), $useCache ) ) {
				$html = $this->generate( $cat, $lang );
				$cacheHit = false;
			} else {
				$html = file_get_contents( $this->getCacheFilePathForSlug( $slug, $lang, 'html' ) );
				$cacheHit = true;
			}

		}

		return array( $html, $slug, $cacheFile, $cacheHit );
	}
}


class PDFFileListEntry {
	public $filename;
	public $size;
	public $fileDate;

	function __construct( $filename, $path ) {
		$this->filename = $filename;
		$this->size = filesize( $path . $filename );
		$this->fileDate = filemtime( $path . $filename );
	}

	function getSize() {
		return $this->size;
	}

	function getDate() {
		$date = new DateTime();
		$date->setTimestamp( (int) $this->fileDate );

		return $date->format( 'd M Y H:i' );
	}

	function getURL() {
		return PDFCatalogGenerator::getCacheURL( 'categories/' . $this->filename );
	}

	function getFileNoExt() {
		return basename( $this->filename, '.pdf' );
	}
}
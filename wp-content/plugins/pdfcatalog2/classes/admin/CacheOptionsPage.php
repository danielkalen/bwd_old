<?php 
/**
 * Created by PhpStorm.
 * User: Kyriakos
 * Date: 3/31/2015
 * Time: 23:37 PM
 */
class PDFCacheOptionsPage extends PDFCatalogSettingsPage {

	public $option_group = 'pdfgen-page4';
	public $page = 'pdfcachesettings';


	public function setupSections() {

		// CACHE SETTINGS ========================================================
		$s = $this->addSection( 'cachesettings_section', 'Download Handling', 'cachesettings_section' );
/*
		$s->addField(
			'pdfcat_cache', // Field ID
			'Use Cache', // Title
			1, // Default Value
			'field_cache_checkbox', // Method
			array( "PDFCatalog", 'invalidate_Cache' )
		);
*/
		$s->addField(
			'pdfcat_downloadfile', // Field ID
			'Force file download', // Title
			0, // Default Value
			'field_download_checkbox' // Method
		);
		$s->addField(
			'pdfcat_redirectdownload', // Field ID
			'Redirect to PDF File', // Title
			0, // Default Value
			'field_redirect_checkbox' // Method
		);


	}


	static function field_cache_checkbox() {
		?>
		<input type="checkbox" id="pdfcat_cache" name="pdfcat_cache"
		       value="1" <?php echo ( get_option( 'pdfcat_cache' ) == 1 ) ? 'checked' : ''; ?>>
		<label for="pdfcat_cache">keep catalogs for reuse if no changes were made to products. Disabling will slow down
			PDF delivery considerably.</label>
		<?php 	}


	static function field_redirect_checkbox() {
		?>
		<input type="checkbox" id="pdfcat_redirectdownload" name="pdfcat_redirectdownload"
		       value="1" <?php echo ( get_option( 'pdfcat_redirectdownload' ) == 1 ) ? 'checked' : ''; ?>>
		<label for="pdfcat_redirectdownload">redirect browser to PDF file and have the web server send it instead of the
		<?php 	}

	static function field_download_checkbox() {
		?>
			<input type="checkbox" id="pdfcat_downloadfile" name="pdfcat_downloadfile"
			       value="1" <?php echo ( get_option( 'pdfcat_downloadfile' ) == 1 ) ? 'checked' : ''; ?>>
			<label for="pdfcat_downloadfile">force user browser to download PDF file (instead of viewing in
				browser).</label>
		<?php 	}

	static function cachesettings_section() {
		/*
		echo 'The following settings affect the performance of PDF Catalog plugin. Unless you know what you are doing better not change anything. ';
		echo 'Generating PDF files is a CPU intensive task therefore we cache the generated PDF files ';
		echo 'in order to save CPU on your web host and send PDFs to your users instantly. ';
		echo 'If you are developing your own theme, you may want to disable caching during development time and ';
		echo 're-enable it once you are done.';
		*/
	}

}
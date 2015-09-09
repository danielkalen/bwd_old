<?php

/**
 * Created by PhpStorm.
 * User: Kyriakos
 * Date: 3/31/2015
 * Time: 23:37 PM
 */
class PDFTemplateOptionsPage extends PDFCatalogSettingsPage {

	public $option_group = 'pdfgen-page1';
	public $page = 'pdfgensettings';


	public function setupOptions() {
		add_option( 'pdfcat_order', 'desc' );
		add_option( 'pdfcat_orderby', 'date' );
	}

	public function setupSections() {

		// PURCHASE SECTION ================================================
		$s = $this->addSection( 'purchase_section', 'Envato Purchase Code', 'purchase_section' );

		$s->addField(
			'pdfcat_purchasecode', // Field ID
			'Purchase Code', // Title
			'', // Default Value
			'field_purchasecode' // Method
		);

		// THEME SECTION ===================================================

		$s = $this->addSection( 'theme_section', 'PDF Catalog Template', 'theme_section' );

		$s->addField(
			'pdfcat_template', // Field ID
			'Template', // Title
			'thumbnaillist', // Default Value
			'field_template_select', // Method
			array( // OnSave Callback
			       "PDFCatalog",
			       'invalidate_Cache'
			) );

		$s->addField(
			'pdfcat_html', // Field ID
			'Render HTML', // Title
			0, // Default Value
			'field_renderhtml_checkbox' );


		// VISIBILITY SECTION ===================================================

		$s = $this->addSection( 'visibility_section', 'Catalog Elements', 'visibility_section' );

		$s->addField(
			'pdfcat_showSKU', // Field ID
			'Show Product SKU', // Title
			0, // Default Value
			'field_sku_checkbox', // Method
			array( // OnSave Callback
			       "PDFCatalog",
			       'invalidate_Cache'
			) );

		$s->addField(
			'pdfcat_showPrice', // Field ID
			'Show Prices', // Title
			1, // Default Value
			'field_price_checkbox', // Method
			array( // OnSave Callback
			       "PDFCatalog",
			       'invalidate_Cache'
			)  );

		$s->addField(
			'pdfcat_showDescription', // Field ID
			'Show Description', // Title
			1, // Default Value
			'field_description_checkbox', // Method
			array( // OnSave Callback
			       "PDFCatalog",
			       'invalidate_Cache'
			)  );

		$s->addField(
			'pdfcat_renderShortcodes', // Field ID
			'Render Shortcodes', // Title
			0, // Default Value
			'field_render_shortcodes', // Method
			'' );

		$s->addField(
			'pdfcat_showVariations', // Field ID
			'Show Variations', // Title
			0, // Default Value
			'field_variations_checkbox', // Method
			'' );

		$s->addField(
			'pdfcat_showCategoryTitle', // Field ID
			'Show Category Title', // Title
			1, // Default Value
			'field_categorytitle_checkbox', // Method
			array( // OnSave Callback
			       "PDFCatalog",
			       'invalidate_Cache'
			)  );

		$s->addField(
			'pdfcat_showCategoryProductCount', // Field ID
			'Show Product Counts', // Title
			0, // Default Value
			'field_productcount_checkbox', // Method
			array( // OnSave Callback
			       "PDFCatalog",
			       'invalidate_Cache'
			)  );

		$s->addField(
			'pdfcat_headLines', // Field ID
			'Header Separator Line', // Title
			1, // Default Value
			'field_headlines_checkbox', // Method
			array( // OnSave Callback
			       "PDFCatalog",
			       'invalidate_Cache'
			)  );


		// PAGING SECTION ===================================================

		$s = $this->addSection( 'paging_section', 'Paging Settings', 'paging_section' );

		$s->addField(
			'pdfcat_characterLimit', // Field ID
			'Description Character Limit', // Title
			0, // Default Value
			'field_characterlimit', // Method
			array( // OnSave Callback
			       "PDFCatalog",
			       'invalidate_Cache'
			) );


		$s->addField(
			'pdfcat_useShortDescription',
			'Use Short Product Description',
			0,
			'field_useShortDescription',
			array( // OnSave Callback
			       "PDFCatalog",
			       'invalidate_Cache'
			)
		);

		register_setting( 'pdfgen-page1', 'pdfcat_useShortDescription' );

		$s->addField(
			'pdfcat_startOnNewPage', // Field ID
			'Start Category on New Page', // Title
			0, // Default Value
			'field_startonnewpage_checkbox', // Method
			'' );

	}


	public function setupFields() {

	}

	static function purchase_section() {
		echo 'In order to use the PDF Catalog plugin renderer you need to enter your Purchase Code from Envato. You can find instructions on how to get your purchase code';
		echo ' <a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-can-I-find-my-Purchase-Code-">here</a>';
	}

	static function field_purchasecode() {
		?>
		<input type="text" id="pdfcat_purchasecode" name="pdfcat_purchasecode" size="24"
		       value="<?php echo get_option( 'pdfcat_purchasecode' ); ?>">
		<br><label for="pdfcat_purchasecode"></label>
		<?php
	}


	// ================================================================================================================

	static function theme_section() {
		echo 'Choose which template you would like to use for PDF Catalogs.';
	}


	static function field_template_select() {
		$templates = PDFCatalogGenerator::getTemplates();
		$current = get_option( 'pdfcat_template' );
		foreach ( $templates as $id => $t ) {
			?>
			<p>
				<input type="radio" name="pdfcat_template" id="<?php echo $id; ?>"
				       value="<?php echo $id ?>" <?php if ( $current == $id ) {
					echo 'checked';
				} ?>>
				<label for="<?php echo $id ?>">
					<?php
					if ( isset( $t['child'] ) ) {
						echo '<em>';
					}
					echo $t[0];
					if ( isset( $t['child'] ) ) {
						echo '</em>';
					}
					?>
					<br>
					<?php echo $t[1] ?>
				</label>
			</p>
		<?php } ?>
		<?php
	}

	static function field_renderhtml_checkbox() {
		?>
		<input type="checkbox" id="pdfcat_html" name="pdfcat_html"
		       value="1" <?php echo ( get_option( 'pdfcat_html' ) == 1 ) ? 'checked' : ''; ?>>
		<label for="pdfcat_html">output HTML instead of PDF (enable to debug your own templates)</label>
	<?php }


	// ================================================================================================================

	static function visibility_section() {
		echo 'Use this section to hide/show different elements of the generated product catalog. Not all elements are supported by every template.';
	}

	static function field_sku_checkbox() {
		?>
		<input type="checkbox" id="show_sku" name="pdfcat_showSKU"
		       value="1" <?php echo ( get_option( 'pdfcat_showSKU' ) == 1 ) ? 'checked' : ''; ?>>
		<label for="show_sku">include product SKUs in PDF catalog.</label>
	<?php }


	static function field_characterlimit() {
		?>
		<input type="text" id="pdfcat_characterLimit" name="pdfcat_characterLimit" size="4"
		       value="<?php echo (int) get_option( 'pdfcat_characterLimit' ); ?>">
		<br><label for="pdfcat_characterLimit">Truncate description to the specified number of characters (enter 0
			for
			the full description).</label>
		<?php
	}


	static function field_startonnewpage_checkbox() {
		?>
		<input type="checkbox" id="pdfcat_startOnNewPage" name="pdfcat_startOnNewPage"
		       value="1" <?php echo ( get_option( 'pdfcat_startOnNewPage' ) == 1 ) ? 'checked' : ''; ?>>
		<label for="pdfcat_startOnNewPage">start categories on a fresh page in PDFs with multiple categories.</label>
		<?php
	}

	static function field_useShortDescription() {
		?>
		<input type="checkbox" id="pdfcat_useShortDescription" name="pdfcat_useShortDescription"
		       value="1" <?php echo ( get_option( 'pdfcat_useShortDescription' ) == 1 ) ? 'checked' : ''; ?>>
		<label for="pdfcat_useShortDescription">use Short Product Description instead of the default long
			description.</label>
		<?php
	}


	static function field_headlines_checkbox() {
		?>
		<input type="checkbox" id="pdfcat_headLines" name="pdfcat_headLines"
		       value="1" <?php echo ( get_option( 'pdfcat_headLines' ) == 1 ) ? 'checked' : ''; ?>>
		<label for="pdfcat_headLines">show separator lines between header/footer.</label>
	<?php }

	static function field_description_checkbox() {
		?>
		<input type="checkbox" id="pdfcat_showDescription" name="pdfcat_showDescription"
		       value="1" <?php echo ( get_option( 'pdfcat_showDescription' ) == 1 ) ? 'checked' : ''; ?>>
		<label for="pdfcat_showDescription">show full product description..</label>
	<?php }

	static function field_render_shortcodes() {
		?>
		<input type="checkbox" id="pdfcat_renderShortcodes" name="pdfcat_renderShortcodes"
		       value="1" <?php echo ( get_option( 'pdfcat_renderShortcodes' ) == 1 ) ? 'checked' : ''; ?>>
		<label for="pdfcat_renderShortcodes"> process shortcodes (will strip shortcodes if disabled).</label>
	<?php }


	static function field_price_checkbox() {
		?>
		<input type="checkbox" id="show_price" name="pdfcat_showPrice"
		       value="1" <?php echo ( get_option( 'pdfcat_showPrice' ) == 1 ) ? 'checked' : ''; ?>>
		<label for="show_price">include product prices in PDF catalog.</label>
	<?php }

	static function field_variations_checkbox() {
		?>
		<input type="checkbox" id="show_variations" name="pdfcat_showVariations"
		       value="1" <?php echo ( get_option( 'pdfcat_showVariations' ) == 1 ) ? 'checked' : ''; ?>>
		<label for="show_variations">show variation attributes for each product.</label>
	<?php }


	static function field_productcount_checkbox() {
		?>
		<input type="checkbox" id="pdfcat_showCategoryProductCount" name="pdfcat_showCategoryProductCount"
		       value="1" <?php echo ( get_option( 'pdfcat_showCategoryProductCount' ) == 1 ) ? 'checked' : ''; ?>>
		<label for="pdfcat_showCategoryProductCount">show total number of products found under each
			category.</label>
	<?php }


	static function field_categorytitle_checkbox() {
		?>
		<input type="checkbox" id="pdfcat_showCategoryTitle" name="pdfcat_showCategoryTitle"
		       value="1" <?php echo ( get_option( 'pdfcat_showCategoryTitle' ) == 1 ) ? 'checked' : ''; ?>>
		<label for="pdfcat_showCategoryTitle">show category title in category-specific PDFs.</label>
	<?php }

	static function paging_section() {

	}
}
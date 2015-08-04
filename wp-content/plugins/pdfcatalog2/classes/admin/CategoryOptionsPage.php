<?php 

class PDFCategoryOptionsPage extends PDFCatalogSettingsPage {

	public $option_group = 'pdfgen-page4';
	public $page = 'pdfcachesettings';

	public function setupOptions() {
		add_option( 'pdfcat_categories', '' );
		add_option( 'pdfcat_category_hierarchy', 'flat' );
		add_option( 'pdfcat_showhidden', 1 );
		add_option( 'pdfcat_hideoutofstock', 0 );
		add_option( 'pdfcat_hideemptycategories', 1 );
		add_option( 'pdfcat_hiddenroles', '' );
		add_option( 'pdfcat_hideparentcategories', 0 );
	}


	public function setupSections() {

		add_settings_section(
			'categorysettings_section', // ID
			'Category Settings', // Title
			array( "PDFCategoryOptionsPage", 'categorysettings_section' )
			, // Callback
			'pdfcategorysettings' // Page
		);

		add_settings_field(
			'pdfcat_categories', // ID
			'Included Categories', // Title
			array( "PDFCategoryOptionsPage", 'field_categories' ),
			'pdfcategorysettings', // Page
			'categorysettings_section' // Section
		);

		add_settings_field(
			'pdfcat_category_hierarchy', // ID
			'Full Catalog Style', // Title
			array( "PDFCategoryOptionsPage", 'field_catalog_style_select' ),
			'pdfcategorysettings', // Page
			'categorysettings_section' // Section
		);


		add_settings_section(
			'visibilitysettings_section', // ID
			'Visibility Settings', // Title
			array( "PDFCategoryOptionsPage", 'visibilitysettings_section' )
			, // Callback
			'pdfcategorysettings' // Page
		);


		add_settings_field(
			'pdfcat_showhidden', // ID
			'Product Visibility', // Title
			array( "PDFCategoryOptionsPage", 'fld_showhidden' ),
			'pdfcategorysettings', // Page
			'visibilitysettings_section' // Section
		);

		add_settings_field(
			'pdfcat_hideemptycategories', // ID
			'Hide Empty Categories', // Title
			array( "PDFCategoryOptionsPage", 'fld_hideemptycategories' ),
			'pdfcategorysettings', // Page
			'visibilitysettings_section' // Section
		);

		add_settings_field(
			'pdfcat_hideparentcategories', // ID
			'Hide Parent Categories Products', // Title
			array( "PDFCategoryOptionsPage", 'fld_hideparentcategories' ),
			'pdfcategorysettings', // Page
			'visibilitysettings_section' // Section
		);

		add_settings_field(
			'pdfcat_hideoutofstock', // ID
			'Hide Out-of-stock Products', // Title
			array( "PDFCategoryOptionsPage", 'fld_hideoutofstock' ),
			'pdfcategorysettings', // Page
			'visibilitysettings_section' // Section
		);


		add_settings_field(
			'pdfcat_hiddenroles', // ID
			'Hide from roles', // Title
			array( "PDFCategoryOptionsPage", 'fld_hiddenroles' ),
			'pdfcategorysettings', // Page
			'visibilitysettings_section' // Section
		);

		register_setting( 'pdfgen-page5', 'pdfcat_showhidden', array( "PDFCatalog", 'invalidate_Cache' ) );
		register_setting( 'pdfgen-page5', 'pdfcat_hideparentcategories', array( "PDFCatalog", 'invalidate_Cache' ) );

		register_setting( 'pdfgen-page5', 'pdfcat_hideemptycategories', array( "PDFCatalog", 'invalidate_Cache' ) );

		register_setting( 'pdfgen-page5', 'pdfcat_hideoutofstock', array(
			"PDFCatalog",
			'invalidate_Cache'
		) );
		register_setting( 'pdfgen-page5', 'pdfcat_categories', array( "PDFCatalog", 'invalidate_Cache' ) );
		register_setting( 'pdfgen-page5', 'pdfcat_category_hierarchy', array(
			"PDFCatalog",
			'invalidate_Cache'
		) );
		register_setting( 'pdfgen-page5', 'pdfcat_hiddenroles' );


	}


	static function fld_hideoutofstock() {
		?>
		<input type="checkbox" id="pdfcat_hideoutofstock" name="pdfcat_hideoutofstock"
		       value="1" <?php echo ( get_option( 'pdfcat_hideoutofstock' ) == 1 ) ? 'checked' : ''; ?>>
		<label for="pdfcat_hideoutofstock">Enable this option to hide out-of-stock items from PDF output.</label>
		<?php 	}

	static function fld_showhidden() {
		?>
		<input type="checkbox" id="pdfcat_showhidden" name="pdfcat_showhidden"
		       value="1" <?php echo ( get_option( 'pdfcat_showhidden' ) == 1 ) ? 'checked' : ''; ?>>
		<label for="pdfcat_showhidden">Disable this option if you want products which have their catalog visibility set
			as Hidden to be excluded from PDF output.</label>
		<?php 	}

	static function fld_hideemptycategories() {
		?>
		<input type="checkbox" id="pdfcat_hideemptycategories" name="pdfcat_hideemptycategories"
		       value="1" <?php echo ( get_option( 'pdfcat_hideemptycategories' ) == 1 ) ? 'checked' : ''; ?>>
		<label for="pdfcat_hideemptycategories">Enable to hide categories which contain now products in Full Store
			Catalogs and
			Multiple Category Catalogs. Hiding empty categories might be undesirable in case you are using Hierarchical
			Catalog Style.</label>
		<?php 	}

	static function fld_hideparentcategories() {
		?>
		<input type="checkbox" id="pdfcat_hideparentcategories" name="pdfcat_hideparentcategories"
		       value="1" <?php echo ( get_option( 'pdfcat_hideparentcategories' ) == 1 ) ? 'checked' : ''; ?>>
		<label for="pdfcat_hideparentcategories">When this option is enabled products of categories which contain
			sub-categories will not be listed. This is especially useful for avoiding duplicated products in stores
			where products from sub-categories also exist in their parent category. To be used in conjunction with
			Hierarchical Full Catalog Style.</label>
		<?php 	}

	static function fld_hiddenroles() {
		$roles = get_option( 'pdfcat_hiddenroles' );
		$rolesArr = array_flip( explode( ',', $roles ) );

		?>
		By default all user roles can download PDF catalogs from your store. The user roles that are selected here will not be able to view catalogs in PDF
		<br>and will not be able to see the PDF Catalog Widget or shortcode output.
		<?php 		global $wp_roles;

		echo '<ul id="pdf_roles">';
		?>
		<li style="margin-bottom: 1em"><input <?php if ( isset( $rolesArr['PDF_SIGNED_OUT'] ) ) {
				echo 'checked ';
			} ?>type="checkbox" value="PDF_SIGNED_OUT" id="rolePDF_SIGNED_OUT">
			<label for="rolePDF_SIGNED_OUT">Unregistered Users</label>
		</li>

		<?php 		foreach ( $wp_roles->roles as $key => $role ) {
			?>

			<li><input <?php if ( isset( $rolesArr[ $key ] ) ) {
					echo 'checked ';
				} ?>type="checkbox" value="<?php echo $key; ?>" id="role<?php echo $key; ?>">
				<label for="role<?php echo $key; ?>"><?php echo $role['name']; ?></label>
			</li>
			<?php 		}
		echo '</ul>';
		?>
		<input type="hidden" name="pdfcat_hiddenroles" id="pdfcat_hiddenroles" value="<?php echo $roles; ?>">
		<script>
			jQuery(function () {
				var $ = jQuery;
				pdc_updateRoles();
				$('#pdf_roles').on('click', 'input[type=checkbox]', function (e) {
					pdc_updateRoles();
				});

				function pdc_updateRoles() {
					var checked = $('#pdf_roles input[type=checkbox]').filter(function () {
						return $(this).is(':checked');
					}).map(function () {
						return $(this).val();
					});

					$('#pdfcat_hiddenroles').val(checked.get().join(','));

				}

			});
		</script>
		<?php 	}


	static function field_categories() {
		/*        $c = get_categories(array(
					'taxonomy' => 'product_cat',
					'hierarchical' => 1,
					'parent' =>0
				));
				var_dump($c);
		*/
		$pdf = new PDFCatalog();
		$cats = $pdf->getCategoryTree( 0 );

		?>
		<style>
			#pdfcat_cp {
				background: #fff;
				padding: 1em;
				display: inline-block;
			}

			#pdfcat_cp input[type=checkbox] {
				margin-right: .8em;
			}

			#pdfcat_cp ul {
				padding-left: 0;
				margin-left: 1.5em;
				margin-bottom: 5px;
			}

			#pdfcat_cp > ul {
				margin-left: 0;

			}

			#pdfcat_cp > ul > li {
				margin-bottom: 10px;
			}


		</style>
		<div id="pdfcat_cp">
			<?php 			$preselected = get_option( 'pdfcat_categories' );
			$preselected = explode( ',', $preselected );
			$pdf->renderCategoryTree( $cats, $preselected );
			?>
		</div>
		<div style="clear: left;margin-top: 10px">
			<input class="button" type="button" id="btn_all" value="All"><input type="button" id="btn_none"
			                                                                    class="button" value="None">

			<p>Note: Deselecting all categories is the same as selecting all categories.</p>
		</div>
		<input id="pdfcat_categories" type="hidden" name="pdfcat_categories"
		       value="<?php echo get_option( 'pdfcat_categories' ); ?>">

		<script>
			jQuery(function () {
				var $ = jQuery;
				$('#btn_all').click(function (e) {
					e.preventDefault();
					$('#pdfcat_cp li input[type=checkbox]').attr('checked', 'checked');
					pdc_updateCategories();
				});

				$('#btn_none').click(function (e) {
					e.preventDefault();
					$('#pdfcat_cp li input[type=checkbox]').removeAttr('checked');
					pdc_updateCategories();
				});

				$('#pdfcat_cp').on('click', 'input', function (e) {
					e.stopPropagation();
					pdc_updateCategories();
				});

				function pdc_updateCategories() {
					var checked = $('#pdfcat_cp li input[type=checkbox]').filter(function () {
						return $(this).is(':checked');
					}).map(function () {
						return $(this).val();
					});

					$('#pdfcat_categories').val(checked.get().join(','));

				}


			});
		</script>

		<?php 	}


	static function field_catalog_style_select() {

		$current = get_option( 'pdfcat_category_hierarchy' );
		?>
		<p>
			<input type="radio" name="pdfcat_category_hierarchy" id="<?php echo $current; ?>"
			       value="flat" <?php if ( $current == 'flat' ) {
				echo 'checked';
			} ?>>
			<label for="<?php echo $current ?>">
				Flat - Categories will be displayed in flat list in alphabetical order. Empty categories will be hidden.
			</label>
		</p>

		<p>
			<input type="radio" name="pdfcat_category_hierarchy" id="<?php echo $current; ?>"
			       value="hierarchical" <?php if ( $current == 'hierarchical' ) {
				echo 'checked';
			} ?>>
			<label for="<?php echo $current ?>">
				Hierarchical - Categories will be grouped with their children categories in a hierarchy. Empty
				categories containing other categories will remain visible.
			</label>
		</p>

		<?php 
	}


	static function visibilitysettings_section() {
		echo 'The following settings affect which products are included in PDF catalogs and who can download / view them.';
	}

	static function categorysettings_section() {
		echo 'Check which product categories you would like to be included in the full store PDF product catalog.';
		echo 'By default all categories are included.';
	}

}
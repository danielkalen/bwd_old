<?php require dirname( __FILE__ ) . "/options_header.php";

$files = PDFCatalog::getCachedFilesList();
add_action( 'admin_footer', 'pdf_cache_delete' ); // Write our JS below here

function pdf_cache_delete() {
	?>
	<script type="text/javascript">
		jQuery(document).ready(function ($) {

			var $ = jQuery;

			$('#pdf_files').on('click', '.delete', function (e) {
				e.preventDefault();
				var el = $(this);
				var file = el.data('file');

				$.post(ajaxurl, {
					'action': 'pdf_delete_cache_file',
					'file': file
				}, function (response) {
					el.parents('tr').remove();
				});

			})


		});
	</script> <?php 
}

?>
<style>
	.delete {
		font-size: 20px;
		cursor: pointer;
		display: inline-block;
	}

	.delete:hover {
		color: black;
	}

	#pdf_files tbody tr:nth-child(odd) {
		background-color: #f9f9f9;
	}
</style>


<form method="post" action="options.php">
	<?php settings_fields( 'pdfgen-page4' );
	do_settings_sections( 'pdfcachesettings' );
	?>
	<?php submit_button(); ?>
</form>

<h3>Cached Files</h3>
<?php if ( count( $files ) > 0 ) {
	?>
	<p>The following files are PDF files which were requested and generated, they are bring kept in order to serve users
		PDF files as fast as possible. When product details are changed they are automatically regenerated on-demand so
		there is no real need to delete them manually.
	</p>
	<div id="pdf_files">
	<table class="widefat fixed" cellspacing="0">
	<thead>
	<tr>
		<th class="manage-column">Filename</th>
		<th class="manage-column num">Size</th>
		<th class="manage-column">Date</th>
		<th>Delete</th>
	</tr>
	<tbody>
	<?php 	foreach ( $files as $f ) {

		?>
		<tr>
			<td><a target="_blank" href="<?php echo  $f->getURL(); ?>"><?php echo  $f->filename ?></a></td>
			<td class="num"><?php echo  $f->getSize(); ?></td>
			<td><?php echo  $f->getDate(); ?></td>
			<td><span class="delete" data-file="<?php echo  $f->getFileNoExt(); ?>">&times;</span></td>
		</tr>
		<?php 	}
} else {
	?>
	<h4>No files in cache.</h4>
	</tbody>
	</table>


	</div>
	<?php }

?>

<hr>

</div>
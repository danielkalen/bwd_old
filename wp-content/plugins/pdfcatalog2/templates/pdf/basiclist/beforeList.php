<div class="category" <?php if ($mustBreak) echo 'style="page-break-before: always;"'; ?>>
	<?php 	if ( $this->options->showCategoryTitle ) {
		?>
		<div class="category_title <?php if (isset($category->level)) echo 'level-'.$category->level; ?>">
			<h1><?php echo $category->name; ?></h1>
		</div>
		<?php 	}

	if (!$hideProducts) {
	if ($this->productCount > 0) {
	?>


	<table class="product table table-striped table-bordered">
		<tr>
			<?php if ( $this->options->showSKU ) { ?>
				<th class="sku">
					SKU
				</th>
			<?php } ?>
			<th class="title">
				Title
			</th>
			<?php 			if ( $this->options->showPrice ) {
				?>
				<th class="price">
					Price
				</th>
			<?php } ?>

		</tr>

<?php }  } ?>
<?php 
$product_size = get_post_meta( get_the_ID(), '_size', true ) . ' size, ';
$product_pack = get_post_meta( get_the_ID(), '_pack', true ) . ' per pack';
 ?>
<h1 class="product_title entry-title">
<?php echo get_the_title($_REQUEST['pid']) . 
			'<span class="product_content"> (' . $product_size . $product_pack . ')</span>';
?>
</h1>
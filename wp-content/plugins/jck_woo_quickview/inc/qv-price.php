<?php global $post, $product, $woocommerce; ?>


<div class="price-box clearfix-dk">
	<div class="price-box-wrap">
		<div class="price-box-wrapper">
			<div class="price-wrap">
				<?php echo '<p class="price">'. $product->get_price_html() .'<span class="per-unit">/pack</span></p></div>'; ?>
			</div>
		<!-- </div> -->
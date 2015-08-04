<!-------------------------------------------------------------------->

	<tr>
		<?php if ( $this->options->showSKU ) { ?>
			<td class="sku">
				<span style="color:<?php echo $titleColor ?>"><?php echo $product->get_sku(); ?></span>
			</td>
		<?php } ?>
		<td class="title">
			<a style="color:<?php echo $titleColor ?>;"
			   href="<?php echo get_permalink( $post->ID ); ?>"><?php echo $post->post_title ?></a>
		</td>
		<?php 		if ( $this->options->showPrice ) {
			?>
			<td class="price">
				<span style="color:<?php echo $priceColor ?>"><?php echo $product->get_price_html(); ?></span>
			</td>
		<?php } ?>

	</tr>

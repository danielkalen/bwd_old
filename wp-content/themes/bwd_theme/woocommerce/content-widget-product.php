<?php
global $post, $product; 
$product_size = get_post_meta( get_the_ID(), '_size', true ) . ' size, ';
$product_pack = get_post_meta( get_the_ID(), '_pack', true ) . ' per pack';

 ?>
<li class="sidebar-product-list-item">
	<a href="<?php echo esc_url( get_permalink( $product->id ) ); ?>" title="<?php echo esc_attr( $product->get_title() ); ?>">
		<div class="sidebar-product-list-item-img">
			<?php echo $product->get_image(); ?>
		</div>

		<div class="sidebar-product-list-item-content">
			<div class="sidebar-product-list-item-content-title"><?php echo $product->get_title(); ?></div>
			<div class="sidebar-product-list-item-content-subtitle">
				<?php echo $product_size . $product_pack; ?>
			</div>
			<div class="sidebar-product-list-item-content-rating">
				<?php if ( ! empty( $show_rating ) ) echo $product->get_rating_html(); ?>
			</div>
		</div>
	</a>
	<?php // echo $product->get_price_html(); ?>
</li>
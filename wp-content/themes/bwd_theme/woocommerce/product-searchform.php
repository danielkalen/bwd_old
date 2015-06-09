<form role="search" method="get" class="product-search" action="<?php echo esc_url( home_url( '/'  ) ); ?>">
	<input type="search" class="product-search-field" placeholder="<?php echo esc_attr_x( 'Search Products&hellip;', 'placeholder', 'woocommerce' ); ?>" value="<?php echo get_search_query(); ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label', 'woocommerce' ); ?>" />
	<div class="product-search-submit"></div>
	<input type="hidden" name="post_type" value="product" />
</form>

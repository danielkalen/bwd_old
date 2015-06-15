// ==== Mouse Wheel Event =================================================================================
$$('.product-slider.featured .product-slider-wrap-wrapper').on('mousewheel', function(e){
	e.preventDefault();
	if ( $productScrollOne <= 0 && -($productScrollOne) < $productWidth*$countFeatured - $scrollerWidth ) {
		$productScrollOne += e.deltaY;
	} 
	if ( $productScrollOne > 0 ) {
		$productScrollOne = 0;
	}
	if ( -($productScrollOne) > $productWidth*$countFeatured - ($scrollerWidth + 21) ) {
		$productScrollOne = -($productWidth*$countFeatured - ($scrollerWidth + 20));
	}

	$$('.product-slider.featured .product-slider-wrap-wrapper').css('left', $productScrollOne)
});

$$('.product-slider.recent .product-slider-wrap-wrapper').on('mousewheel', function(e){
	e.preventDefault();
	if ( $productScrollTwo <= 0 && -($productScrollTwo) < $productWidth*$countRecent - $scrollerWidth ) {
		$productScrollTwo += e.deltaY;
	} 
	if ( $productScrollTwo > 0 ) {
		$productScrollTwo = 0;
	}
	if ( -($productScrollTwo) > $productWidth*$countRecent - ($scrollerWidth + 21) ) {
		$productScrollTwo = -($productWidth*$countRecent - ($scrollerWidth + 20));
	}

	$$('.product-slider.recent .product-slider-wrap-wrapper').css('left', $productScrollTwo)
});

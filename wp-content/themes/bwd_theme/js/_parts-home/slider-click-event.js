// ==== Click arrow event =================================================================================

$$('.product-slider.featured').on('click', '.product-slider-controls-arrow', function(){
	$this = $(this);
	if ( $this.hasClass('left') ) {

		if ( $productScrollOne + 325 < 0 ) {
			$productScrollOne += 325;
		} else {
			$productScrollOne = 0;
		}
	}
	if ( $this.hasClass('right') ) {

		if ( $productScrollOne - 325 > -($productWidth * $countFeatured - ($scrollerWidth + 20)) ) {
			$productScrollOne -= 325;
		} else {
			$productScrollOne = -($productWidth * $countFeatured - ($scrollerWidth + 20));
		}
	}


	$$('.product-slider.featured .product-slider-wrap-wrapper').animate({
		left: $productScrollOne
	}, 350, function(x, t, b, c, d){
		return c * Math.sqrt(1 - (t=t/d-1)*t) + b;
	} );
});

$$('.product-slider.recent').on('click', '.product-slider-controls-arrow', function(){
	$this = $(this);
	if ( $this.hasClass('left') ) {

		if ( $productScrollTwo + 325 < 0 ) {
			$productScrollTwo += 325;
		} else {
			$productScrollTwo = 0;
		}
	}
	if ( $this.hasClass('right') ) {

		if ( $productScrollTwo - 325 > -($productWidth * $countRecent - ($scrollerWidth + 20)) ) {
			$productScrollTwo -= 325;
		} else {
			$productScrollTwo = -($productWidth * $countRecent - ($scrollerWidth + 20));
		}
	}


	$$('.product-slider.recent .product-slider-wrap-wrapper').animate({
		left: $productScrollTwo
	}, 350, function(x, t, b, c, d){
		return c * Math.sqrt(1 - (t=t/d-1)*t) + b;
	} );
});

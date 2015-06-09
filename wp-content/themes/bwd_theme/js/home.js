// @codekit-prepend '_plugins/jquery-mousewheel.js'

var $productWidth,
	$scrollerWidth,
	$countFeatured,
	$countRecent,
	$count,
	$sibling,
	$productScrollOne,
	$productScrollTwo
;

/* ==========================================================================
   Sidebar Home
   ========================================================================== */

// ==== Accordion Categories =================================================================================

$$('.sidebar .sub-menu').hide(200);

$$('.sidebar .menu-item-has-children').filter(function(index){
	return index === 0 || index === 1 || index === 2;
	}).each(function(){
		jQuery(this).addClass('open');
		jQuery(this).find('.sub-menu').show(0);
		jQuery(this).find('a').data('clicks', true);
});

$$('.sidebar .menu-item-has-children > a').on('click', function(e){
	e.preventDefault();
	var clicks = jQuery(this).data('clicks');

	if (clicks) {
		$$(this).parent().removeClass('open');
		$$(this).siblings().hide(200);
	} else {
		$$(this).parent().addClass('open');
		$$(this).siblings().show(200);
	}

	$$(this).data('clicks', !clicks);
	// updateSidebarHeight();
});



// ==== View All links =================================================================================

$$('#secondary').find('.menu')
			.children('.menu-item')
				.each(function(){
					$link = $(this).children('a').attr('href');
					// $menu = $(this).children('.sub-menu');

					$(this).append('<a href="'+ $link +'" class="view-all"><span class="view-all-text">All</span></a>');
				});
























/* ==========================================================================
   Prodcut Slider
   ========================================================================== */


// ==== Inner box width =================================================================================
$productWidth = $$('.products-item').width() + 22;
$scrollerWidth = $$('.product-slider').width();
$countFeatured = $$('.product-slider.featured').find('.products-item').length;
$countRecent = $$('.product-slider.recent').find('.products-item').length;
$productScrollOne = 0;
$productScrollTwo = 0;

$window.on('resize', function(){
	$productWidth = $$('.products-item').width() + 22;
	$scrollerWidth = $$('.product-slider').width();
	setSliderWidth('featured');
	setSliderWidth('recent');
});

setSliderWidth('featured');
setSliderWidth('recent');


function setSliderWidth($type) {
	if ( $type === 'featured' ) $count = $countFeatured;
	if ( $type === 'recent' ) $count = $countRecent;
	$$('.product-slider.' + $type).find('.product-slider-wrap-wrapper').width( ($productWidth * $count) - 20 );
}








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

















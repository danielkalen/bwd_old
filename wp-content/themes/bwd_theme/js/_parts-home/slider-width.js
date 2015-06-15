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
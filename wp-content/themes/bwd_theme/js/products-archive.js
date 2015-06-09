/* ==========================================================================
   Price Fitler Slider
   ========================================================================== */

var isDragging = false;
var hasAmount = false;

$$('.price_slider').on('mousedown', '.ui-slider-handle', function(){

	if ( !hasAmount ) {
		$('.ui-slider-handle').eq(0).append('<span class="ui-slider-handle-amount from-amount"></span>');
		$('.ui-slider-handle').eq(1).append('<span class="ui-slider-handle-amount to-amount"></span>');
		hasAmount = true;
	}
	var $this = $(this);
	var $thisAmount = $this.children('.ui-slider-handle-amount');
	var $label;

	$window.mousemove(function(){
		isDragging = true;
		// $window.unbind('mousemove');

		if ( $this.is(':first-of-type') ) {
			$label = $('.price_label .from').html();
		} else {
			$label = $('.price_label .to').html();
		}

		$thisAmount.html($label);
		
	});
});

$$('.price_slider').on('mouseup', '.ui-slider-handle', function(){
	var wasDragging = isDragging;
	isDragging = false;
	$window.unbind('mousemove');

});
















/* ==========================================================================
   Archive list blank append
   ========================================================================== */


var pathName = window.location.pathname;

var categoryPage = false,
	productsPage = false;

if (pathName.split('/').length - 1 <= 3)		var categoryPage = true;
if (pathName.split('/').length - 1 >= 4)		var productsPage = true;
if ( $$('body').hasClass('single-product') ) 	var productsPage = false;


if (categoryPage) {
	appendBlanks('category-list');
}
if (productsPage) {
	appendBlanks('products');
}

function appendBlanks(listType) {
	var itemCount = $$('.category-list').find('.' + listType + '-item').length,
		divideBy,
		remainder,
		blankCount,
		i = 0;

	(window.innerWidth > 920 ? divideBy = 3 : divideBy = 2);
	
	remainder = itemCount % divideBy;
	blankCount = divideBy - remainder;
	if (blankCount !== 3) {
		while ( i < blankCount ) {
			$$('.category-list').append('<li class="' + listType + '-item blank"></li>');
			i++;
		}
	}
}
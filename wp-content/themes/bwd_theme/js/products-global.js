/* ==========================================================================
   Quantity buttons
   ========================================================================== */
$$('.quantity-button').on('click', function(){
	var $this = $(this);
	var quantityVal = $this.siblings('.quantity-input').val();

	if ( $this.hasClass('minus') ) {

		if (quantityVal > 1) {
			--quantityVal;
			$this.siblings('.quantity-input').val(quantityVal);
		}

	} else {
		++quantityVal;
		$this.siblings('.quantity-input').val(quantityVal);
	}
}); 





















/* ==========================================================================
   Top rated list conditional removal
   ========================================================================== */
var recentlyViewedExists = $$('.widget_recently_viewed_products').find('.sidebar-product-list-item').length;

if ( recentlyViewedExists ) {
	$$('.widget_top_rated_products').remove();
}






















/* ==========================================================================
   Review button click
   ========================================================================== */

$$('.reviews-button').on('click', function(){
	$$('.reviews-form').addClass('show');
	$('.reviews-list-empty').addClass('hide');
	$(this).addClass('hide');
});
























/* ==========================================================================
   Quickview
   ========================================================================== */

// ==== Popup Call =================================================================================

$$('body').on('click', '.products-item-img-hover-actions-quickview', function(e){
	e.preventDefault();
	$this = $(this);
	$productHREF = $this.parents('.products-item').find('.products-item-content-wrap').attr('href');
	$productID = $this.siblings().data('product_id');

	$.fn.popup('quick', $productHREF, $productID);
});
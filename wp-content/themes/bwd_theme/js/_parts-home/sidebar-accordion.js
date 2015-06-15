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

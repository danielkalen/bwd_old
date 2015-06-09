/* ==========================================================================
   Top Bar
   ========================================================================== */

// ==== Top Bar Hover =================================================================================

$$('.hover-trigger').on('mouseenter tap',function(e){
	if(e.type == "tap") e.stopPropagation();
	$this = $(this);
	$this.addClass('hover');
	clearTimeout(menuTimeoutShow);
	clearTimeout(menuTimeoutHide);


	menuTimeoutShow = setTimeout(function() {
		if ( $this.hasClass('hover') ) {
			hideOthers($this);
			$this.find('.hover-box').stop().css('visibility', 'visible').animate({'opacity': 1}, 150);
		}
	}, 300);
});


$$('.hover-trigger').on('mouseleave',function(e){
	$this = $(this);
	$this.removeClass('hover');
	clearTimeout(menuTimeoutShow);
	clearTimeout(menuTimeoutHide);


	menuTimeoutHide = setTimeout(function() {
		if ( !$this.hasClass('hover') ) {
			$this.find('.hover-box').stop().animate({
				'opacity': 0
			}, 100, function(){
				$(this).css('visibility', 'hidden');
			});
		}
	}, 200);
});



function hideOthers($this){
	$sibling = $this.siblings('.hover-trigger');
	visible = $sibling.find('.hover-box').css('visibility') === 'visible';

	if (visible) {
		$sibling.find('.hover-box').stop().animate({
				'opacity': 0
			}, 100, function(){
				$(this).css('visibility', 'hidden');
		});
	}
}
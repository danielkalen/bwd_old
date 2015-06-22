(function(){
	$$('.mobile_menu-trigger').on('click',function(e){
		e.stopPropagation();
		e.preventDefault();
		$this = $(this);
		$$('#mobile_menu').addClass('hover');
		$$('body').addClass('account-mobile-menu-open');

		showChildMenu($this);
	});

	$$('.mobile_menu-close').on('click', function(e){ // Close click
		e.stopPropagation();
		$this = $(this).parent().parent();
		$$('#mobile_menu').removeClass('hover');
		$$('body').removeClass('account-mobile-menu-open');

		hideChildMenu($this);
	});


	$$('.mobile_menu-list-item-level-submenu-item-link, .mobile_menu-list-item-level-title').on('click', function(e){
		e.stopPropagation();
	});




	$$('.mobile_menu-trigger').on('click', function(){ // Button Trigger
		var $menu = $$('#mobile_menu');
		$menu.addClass('hover');
		showChildMenu($menu);
	});




				// ==== Helper functions =================================================================================
				function showChildMenu($this){
					clearTimeout(menuTimeoutShow);
					clearTimeout(menuTimeoutHide);

					menuTimeoutShow = setTimeout(function() {
						if ( $this.hasClass('hover') ) {
							hideOtherMenus($this);
							$this.children('.hover-box_menu').stop().css('visibility', 'visible').animate({'opacity': 1}, 150);
						}
					}, 0);
				}

				function hideChildMenu($this){
					clearTimeout(menuTimeoutShow);
					// clearTimeout(menuTimeoutHide);

					menuTimeoutHide = setTimeout(function() {
						if ( !$this.hasClass('hover') ) {
							$this.children('.hover-box_menu').stop().animate({
								'opacity': 0
							}, 100, function(){
								$(this).css('visibility', 'hidden');
							});
						}
					}, 200);
				}


				function hideOtherMenus($this){
					$sibling = $this.siblings('.mobile_menu-trigger');
					visible = $sibling.children('.hover-box_menu').filter(function(){return $(this).css('visibility') === 'visible'}).length;

					if (visible) {
						$sibling.children('.hover-box_menu').stop().animate({
								'opacity': 0
							}, 100, function(){
								$(this).css('visibility', 'hidden');
						});
					}
				}
}());
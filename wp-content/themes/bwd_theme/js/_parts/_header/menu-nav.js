/* ==========================================================================
   Mega Menu + Menu Bar
   ========================================================================== */


// ==== Menu Bar Links =================================================================================
$$('#menu-main-nav').children('.menu-item')
			.on('click', function(){
				$this = $(this);
				$href = $this.children('a').attr('href');

				window.location = $href;
			});


// ==== Class Appension to enable hover triggers =================================================================================
$$('#menu-main-nav').children('.menu-item-has-children')
			.addClass('hover-trigger')
			.children('.sub-menu')
				.addClass('hover-box');


// ==== View more links appension =================================================================================
$$('#menu-main-nav').children('.menu-item-has-children')
			.children('.sub-menu')
				.children('.menu-item')
					.each(function(){
						$link = $(this).children('a').attr('href');
						// $menu = $(this).children('.sub-menu');

						$(this).append('<a href="'+ $link +'" class="view-all"><span class="view-all-text">All</span></a>');
					});


// ==== Highlight Woo Pages as current shop items =================================================================================

if ( $$('body').hasClass('woocommerce') || $$('body').hasClass('woocommerce-page') ) {
	$$('#menu-item-208').addClass('current-menu-item');
}


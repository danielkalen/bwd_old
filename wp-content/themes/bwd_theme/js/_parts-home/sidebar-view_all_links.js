// ==== View All links =================================================================================

$$('#secondary').find('.menu')
			.children('.menu-item')
				.each(function(){
					$link = $(this).children('a').attr('href');
					// $menu = $(this).children('.sub-menu');

					$(this).append('<a href="'+ $link +'" class="view-all"><span class="view-all-text">All</span></a>');
				});

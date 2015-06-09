// additional scripts
/*!
 * Functions Ordering:
 *	- !-Misc
 *	- !-jQuery extensions
 *	- !-Main navigation
 *	- !-Navigation widget
 *	- !-SLIDERS
 *	-  --Metro slider
 *	-  --Scroller
 *	-  --Royal Slider
 *	-  --Revolution slider
 *	- !-Instagram style photos
 * 	- !-Fullwidth map & scroller
 * 	- !-Filter
 * 	- !-Magnific popup gallery
 * 	- !- Fancy grid
 * 	- !- Justified Gallery
 *	- !-Misc-2
 *	-  --Accordion Toggle Tooltip
 *	-  --Fancy header
 *	-  --Share links
 *	-  --Fullwidth wrap for shortcodes & templates
 *	-  --Custom resize function
 *	-  --Scroll to Top
 *	-  --Shopping cart top bar
 *	- !-Onepage template
 *	- !-Floating menu
 *	- !-Item's description on hover
 *	- !-New rollovers
 *	- !-Blur
 */


jQuery(document).ready(function($) {
/*!-Misc*/

	$(".nav-li a").click(function(){
		$("#section-testimonials").animatescroll();
	});

	/*--Append element </i> to preloader*/
	$(".tp-loader, .ls-defaultskin .ls-loading-indicator").not(".loading-label").append('<svg class="fa-spinner" viewBox="0 0 48 48" ><path d="M23.98,0.04c-13.055,0-23.673,10.434-23.973,23.417C0.284,12.128,8.898,3.038,19.484,3.038c10.76,0,19.484,9.395,19.484,20.982c0,2.483,2.013,4.497,4.496,4.497c2.482,0,4.496-2.014,4.496-4.497C47.96,10.776,37.224,0.04,23.98,0.04z M23.98,48c13.055,0,23.673-10.434,23.972-23.417c-0.276,11.328-8.89,20.42-19.476,20.42	c-10.76,0-19.484-9.396-19.484-20.983c0-2.482-2.014-4.496-4.497-4.496C2.014,19.524,0,21.537,0,24.02C0,37.264,10.736,48,23.98,48z"/></svg>');

	/*--Set variable for floating menu*/

	/*--old ie remove csstransforms3d*/
	if ($.browser.msie) $("html").removeClass("csstransforms3d");



	$(document).on("tap", function(e) {
		$(".dt-hovered").trigger("mouseout");
	});

	/* Custom touch events:end */



/* !-jQuery extensions */

	/* !- Check if element exists */
	$.fn.exists = function() {
		if ($(this).length > 0) {
			return true;
		} else {
			return false;
		}
	}

	/* !- Check if element is loaded */
	$.fn.loaded = function(callback, jointCallback, ensureCallback){
		var len	= this.length;
		if (len > 0) {
			return this.each(function() {
				var	el		= this,
					$el		= $(el),
					blank	= "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";

				$el.on("load.dt", function(event) {
					$(this).off("load.dt");
					if (typeof callback == "function") {
						callback.call(this);
					}
					if (--len <= 0 && (typeof jointCallback == "function")){
						jointCallback.call(this);
					}
				});

				if (!el.complete || el.complete === undefined) {
					el.src = el.src;
				} else {
					$el.trigger("load.dt")
				}
			});
		} else if (ensureCallback) {
			if (typeof jointCallback == "function") {
				jointCallback.call(this);
			}
			return this;
		}
	};

/* jQuery extensions: end */

/* !-Main navigation */
/* We need to fine-tune timings and do something about the usage of jQuery "animate" function */ 



var $mainNav = $("#main-nav, .mini-nav"),
	isDemo = $(".demo-panel").exists();


$(".act", $mainNav).parents("li").addClass("act");

var	$mobileNav = $mainNav.clone();
var	$mobileTopNav = $(".mini-nav").clone();
var backCap = $("#mobile-menu > .menu-back").html();


if(dtGlobals.isWindowsPhone){
	$("body").addClass("windows-phone");
}

$(".mini-nav select").change(function() {
	window.location.href = $(this).val();
});


dtGlobals.isHovering = false;

$(".sub-nav", $mainNav).parent().each(function() {
	var $this = $(this);
	if(dtGlobals.isMobile || dtGlobals.isWindowsPhone){
		$this.find("> a").on("click tap", function(e) {
			if (!$(this).hasClass("dt-clicked")) {
				e.preventDefault();
				$mainNav.find(".dt-clicked").removeClass("dt-clicked");
				$(this).addClass("dt-clicked");
			} else {
				e.stopPropagation();
			}

		});
	};

	var menuTimeoutShow,
		menuTimeoutHide;

	if($this.hasClass("dt-mega-menu")){
		
		$this.on("mouseenter tap", function(e) {
			if(e.type == "tap") e.stopPropagation();

			var $this = $(this);
			$this.addClass("dt-hovered");

			dtGlobals.isHovering = true;


			var $_this = $(this),
				$_this_h = $this.height();

			var $_this_ofs_top = $this.position().top;
				$this.find("> .sub-nav").css({
					top: $_this_ofs_top+$_this_h
				});

			
			if($this.hasClass("mega-auto-width")){
				var $_this = $(this),
					$_this_sub = $_this.find(" > .sub-nav > li"),
					coll_width = $("#main .wf-wrap").width()/5,
					$_this_par_width = $_this.parent().width(),
					$_this_parents_ofs = $_this.offset().left - $this.parents("#header .wf-table, .ph-wrap-inner, .logo-center #navigation, .logo-classic #navigation, .logo-classic-centered #navigation").offset().left;

				$_this.find(" > .sub-nav").css({
					left: $_this_parents_ofs,
					"marginLeft": -($_this.find(" > .sub-nav").width()/2 - $_this.width()/2)
				});
			}
			if($this.is(':first-child') && $this.hasClass("mega-auto-width")){
				$this.find(" > .sub-nav").css({
					left: $_this.offset().left - $this.parents("#header .wf-table, .ph-wrap-inner, .logo-center #navigation, .logo-classic #navigation, .logo-classic-centered #navigation").offset().left,
					"marginLeft": 0
				});
			}else if($this.is(':last-child') && $this.hasClass("mega-auto-width")){
				$this.find(" > .sub-nav").css({
					left: "auto",
					right: $this.parents("#header .wf-table, .ph-wrap-inner, .logo-center #navigation, .logo-classic #navigation, .logo-classic-centered #navigation").width() - ( $this.position().left + $this.width() ),
					"marginLeft": 0
				});
			};

			if ($("#page").width() - ($this.children("ul").offset().left - $("#page").offset().left) - $this.children("ul").width() < 0) {
				$this.children("ul").addClass("right-overflow");
			};
			if($this.position().left < ($this.children("ul").width()/2)) {
				$this.children("ul").addClass("left-overflow");
			}

			clearTimeout(menuTimeoutShow);
			clearTimeout(menuTimeoutHide);

			menuTimeoutShow = setTimeout(function() {
				if($this.hasClass("dt-hovered")){
					$this.find("ul").stop().css("visibility", "visible").animate({
						"opacity": 1
					}, 150);
				}
			}, 100);
		});

		$this.on("mouseleave", function(e) {
			var $this = $(this);
			$this.removeClass("dt-hovered");

			dtGlobals.isHovering = false;
			clearTimeout(menuTimeoutShow);
			clearTimeout(menuTimeoutHide);

			menuTimeoutHide = setTimeout(function() {
				if(!$this.hasClass("dt-hovered")){
					$this.children("ul").stop().animate({
						"opacity": 0
					}, 150, function() {
						$(this).css("visibility", "hidden");

						$(this).find("ul").stop().css("visibility", "hidden").animate({
							"opacity": 0
						}, 10);
					});
					
					setTimeout(function() {
						if(!$this.hasClass("dt-hovered")){
							$this.children("ul").removeClass("right-overflow");
							$this.children("ul").removeClass("left-overflow");
						}
					}, 400);
					
				}
			}, 150);

			$this.find("> a").removeClass("dt-clicked");
		});
	}else{
		$this.on("mouseenter tap", function(e) {
			if(e.type == "tap") e.stopPropagation();

			var $this = $(this);
			$this.addClass("dt-hovered");

			if ($("#page").width() - ($this.children("ul").offset().left - $("#page").offset().left) - 240 < 0) {
				$this.children("ul").addClass("right-overflow");
			}
			dtGlobals.isHovering = true;
			clearTimeout(menuTimeoutShow);
			clearTimeout(menuTimeoutHide);

			menuTimeoutShow = setTimeout(function() {
				if($this.hasClass("dt-hovered")){
					$this.children('ul').stop().css("visibility", "visible").animate({
						"opacity": 1
					}, 150);
				}
			}, 100);
		});

		$this.on("mouseleave", function(e) {
			var $this = $(this);
			$this.removeClass("dt-hovered");

			dtGlobals.isHovering = false;
			clearTimeout(menuTimeoutShow);
			clearTimeout(menuTimeoutHide);

			menuTimeoutHide = setTimeout(function() {
				if(!$this.hasClass("dt-hovered")){
					if(!$this.parents().hasClass("dt-mega-menu")){
						$this.children("ul").stop().animate({
							"opacity": 0
						}, 150, function() {
							$(this).css("visibility", "hidden");
						});
					}
					
					setTimeout(function() {
						if(!$this.hasClass("dt-hovered")){
							$this.children("ul").removeClass("right-overflow");
						}
					}, 400);
				}
			}, 150);

			$this.find("> a").removeClass("dt-clicked");
		});
	};

});

/* Main navigation: end */

/* !-Navigation widget */

	var itemMenuOne = $("#presscore-custom-menu-2-2 .custom-nav > li:nth-child(1) > a").parent();
	var myThisOne = $("#presscore-custom-menu-2-2 .custom-nav > li:nth-child(1) > a");
	var myCustomMenu = $("#presscore-custom-menu-2-2 li:nth-child(1) .custom-menu");
	if (itemMenuOne.hasClass("has-children")) {
			itemMenuOne.addClass('act');
			myThisOne.addClass('active');
			myCustomMenu.css('display', 'block');
	}

	var itemMenuTwo = $("#presscore-custom-menu-2-2 .custom-nav > li:nth-child(2) > a").parent();
	var myThisTwo = $("#presscore-custom-menu-2-2 .custom-nav > li:nth-child(2) > a");
	var myCustomMenu = $("#presscore-custom-menu-2-2 li:nth-child(2) .custom-menu");
	if (itemMenuTwo.hasClass("has-children")) {
			itemMenuTwo.addClass('act');
			myThisTwo.addClass('active');
			myCustomMenu.css('display', 'block');
	}

	var itemMenuThree = $("#presscore-custom-menu-2-2 .custom-nav > li:nth-child(3) > a").parent();
	var myThisThree = $("#presscore-custom-menu-2-2 .custom-nav > li:nth-child(3) > a");
	var myCustomMenu = $("#presscore-custom-menu-2-2 li:nth-child(3) .custom-menu");
	if (itemMenuThree.hasClass("has-children")) {
			itemMenuThree.addClass('act');
			myThisThree.addClass('active');
			myCustomMenu.css('display', 'block');
	}


var customTimeoutShow
$(".custom-nav > li > a").click(function(e){
	$menuItem = $(this).parent();
	if ($menuItem.hasClass("has-children")) e.preventDefault();
	
	
		if ($(this).attr("class") != "active"){
				//$(".custom-nav > li > ul").stop(true, true).slideUp(400);
				$(this).next().stop(true, true).slideDown(500);
				//$(".custom-nav > li > a").removeClass("active");
				$(this).addClass('active');
		}else{
				$(this).next().stop(true, true).slideUp(500);
				$(this).removeClass("active");
		}

		//$menuItem.siblings().removeClass("act");
		$menuItem.addClass("act");
});
$(".custom-nav > li > ul").each(function(){
	clearTimeout(customTimeoutShow);
	$this = $(this);
	$thisChildren = $this.find("li");
	if($thisChildren.hasClass("act")){
		$this.prev().addClass("active");
		//$this.parent().siblings().removeClass("act");
		$this.parent().addClass("act");
		$(this).slideDown(500);
	}
});

/* Navigation widget: end */

/*!-Misc-2*/
	$("#parent-element a").live("touchstart",function(e){
		var $link_id = $(this).attr("id");
		if ($(this).parent().data("clicked") == $link_id) {
			// element has been tapped (hovered), reset 'clicked' data flag on parent element and return true (activating the link)
			$(this).parent().data("clicked", null);
			return true;
		} else {
			$(this).trigger("mouseenter").siblings().trigger("mouseout"); //triggers the hover state on the tapped link (because preventDefault(); breaks this) and untriggers the hover state for all other links in the container.
			// element has not been tapped (hovered) yet, set 'clicked' data flag on parent element to id of clicked link, and prevent click
			e.preventDefault(); // return false; on the end of this else statement would do the same
			$(this).parent().data("clicked", $link_id); //set this link's ID as the last tapped link ('clicked')
		}
	});

	/* !Fullwidth wrap for shortcodes & templates */

	
	function fullWidthWrap(){
		if( $(".full-width-wrap").length ){
			$(".full-width-wrap").each(function(){
				var $_this = $(this),
					offset_wrap = $_this.position().left;

					var $offset_fs,
						$width_fs;
					var $scrollBar = 0;
				 
					if($('.boxed').length){
						$offset_fs = ((parseInt($('#main').width()) - parseInt($('.content').width())) / 2);
					} else {
							var $windowWidth = ($(window).width() <= parseInt($('.content').width())) ? parseInt($('.content').width()) : $(window).width();
							$offset_fs = Math.ceil( (($windowWidth + $scrollBar - parseInt($('.content').width())) / 2) );
					};
					if($('.sidebar-left').length || $('.sidebar-right').length){
						$width_fs = $(".content").width();
						$offset_fs = 0;
					}else{
						$width_fs = $("#main").width();
					}
					$_this.css({
						width: $width_fs,
						"margin-left": -$offset_fs
					});
			});
		};
	};

	if( $(".full-width-wrap").length && !dtGlobals.isiPhone ){
		if(dtGlobals.isMobile && !dtGlobals.isWindowsPhone){
			$(window).bind("orientationchange", function() {
				fullWidthWrap();
			}).trigger( "orientationchange" );
		}else{
			$(window).on("resize", function(){
				fullWidthWrap();
			}).trigger("resize");
		}
	};
	/* Fullwidth wrap for shortcodes & templates:end */
	/*!-Mobile top bar*/
	if(!$(".responsive-off").length){
		var topBar = $("#top-bar");
		topBar.append($("<span class='act'></span>"));
		var topSpan = $("> span", topBar);
	}
	/*$("#top-bar.top-bar-hide").css({
		"margin-top": -$("#top-bar").height()
	})*/
	if(!$(".responsive-off").length){
		$(" > span", topBar).on("click", function(){
			var $_this = $(this);
			if($_this.hasClass("act")){
				$_this.removeClass("act");
				topBar.removeClass("top-bar-hide");
				topBar.animate({
					"margin-top": 0
				}, 200);
				$.cookie('top-hide', 'false', {expires: 1, path: '/'});
			}else{
				$_this.addClass("act");
				topBar.addClass("top-bar-hide");
				topBar.animate({
					"margin-top": -$("#top-bar").height()
				}, 200);
				$.cookie('top-hide', 'true', {expires: 1, path: '/'});
			}
		});
		if(topSpan.hasClass("act")){
			topBar.addClass("top-bar-hide");
		}else{
			topBar.removeClass("top-bar-hide");
		}
	}
	function mobileTopBar(){
		if(!$(".responsive-off").length){
			if($(window).width() < 760){
				if(topSpan.hasClass("act")){
					topBar.animate({
						"margin-top": -$("#top-bar").height()
					}, 200, function() {
						topBar.css({"visibility": "visible", "opacity": "1"});
					});
				}else{
					topBar.animate({
						"margin-top": 0
					}, 200, function() {
						topBar.css({"visibility": "visible", "opacity": "1"});
					});
				}
			}
		}
	};
	mobileTopBar();
	if(!$(".responsive-off").length){
		if($(window).width() < 760){
			if ($.cookie('top-hide') == "false"){
				topBar.removeClass("top-bar-hide");
				topSpan.removeClass("act");
				topBar.animate({
					"margin-top": 0
				}, 200, function() {
					topBar.css({"visibility": "visible", "opacity": "1"});
				});
			}
			if ($.cookie('top-hide') == "true"){
				topBar.animate({
					"margin-top": -$("#top-bar").height()
				}, 200, function() {
					topBar.css({"visibility": "visible", "opacity": "1"});
				});
			};
		}
	}
	/*Mobile top bar:end*/


	/*!Shopping cart top bar*/
	var menuTimeoutShow,
		menuTimeoutHide;

	$(".shopping-cart").on("mouseenter tap", function(e) {
		if(e.type == "tap") e.stopPropagation();

		var $this = $(this);
		$this.addClass("dt-hovered");
		if ($("#page").width() - ($this.children('.shopping-cart-inner').offset().left - $("#page").offset().left) - 230 < 0) {
			$this.children('.shopping-cart-inner').addClass("right-overflow");
		}

		clearTimeout(menuTimeoutShow);
		clearTimeout(menuTimeoutHide);

		menuTimeoutShow = setTimeout(function() {
			if($this.hasClass("dt-hovered")){
				$this.children('.shopping-cart-inner').stop().css("visibility", "visible").animate({
					"opacity": 1
				}, 200);
			}
		}, 350);
	});

	$(".shopping-cart").on("mouseleave", function(e) {
		var $this = $(this);
		$this.removeClass("dt-hovered");

		clearTimeout(menuTimeoutShow);
		clearTimeout(menuTimeoutHide);

		menuTimeoutHide = setTimeout(function() {
			if(!$this.hasClass("dt-hovered")){
				$this.children('.shopping-cart-inner').stop().animate({
					"opacity": 0
				}, 150, function() {
					$(this).css("visibility", "hidden");
				});
				setTimeout(function() {
					if(!$this.hasClass("dt-hovered")){
						$this.children('.shopping-cart-inner').removeClass("right-overflow");
					}
				}, 400);
				
			}
		}, 200);

	});
	/*Shopping cart top bar:end*/

	/* !- Skills */
	$.fn.animateSkills = function() {
		$(".skill-value", this).each(function () {
			var $this = $(this),
				$this_data = $this.data("width");

			$this.css({
				width: $this_data + '%'
			});
		});
	};

	// !- Animation "onScroll" loop
	function doAnimation() {
		
		if(dtGlobals.isMobile){
			$(".skills").animateSkills();
		}
		if($("html").hasClass("old-ie")){
			$(".skills").animateSkills();
		};
	};
	// !- Fire animation
	doAnimation();
	/* Skills:end */
	// Create the dropdown base 12.02.14
	$("<select />").prependTo(".mini-nav .menu-select");

	// Create default option "Select"
	$("<option />", {
		"selected"  :  "selected",
		"value"     :  "",
		"text"      :  "———"
	}).appendTo(".mini-nav .menu-select select");

	// Populate dropdown with menu items
	$(".mini-nav").each(function() {
		var elPar = $(this),
			thisSelect = elPar.find("select");
		$("a", elPar).each(function() {
			var el = $(this);
			$("<option />", {
				"value"   : el.attr("href"),
				"text"    : el.text(),
				"data-level": el.attr("data-level")
			}).appendTo(thisSelect);
		});
	});
	

	$(".mini-nav select").change(function() {
		window.location = $(this).find("option:selected").val();
	});
	$(".mini-nav select option").each(function(){
		var $this = $(this),
			winHref = window.location.href;
		 if($this.attr('value') == winHref){
			$this.attr('selected','selected');
		};
	})
	/*!-Appearance for custom select*/
	$(".menu-select select, .mini-nav .customSelect1, .vc_pie_chart .vc_pie_wrapper").css("visibility", "visible");

	$(".mini-nav option").each(function(){
		var $this	= $(this),
			text	= $this.text(),
			prefix	= "";

		switch ( parseInt($this.attr("data-level"))) {
			case 1:
				prefix = "";
			break;
			case 2:
				prefix = "— ";
			break;
			case 3:
				prefix = "—— ";
			break;
			case 4:
				prefix = "——— ";
			break;
			case 5:
				prefix = "———— ";
			break;
		}
		$this.text(prefix+text);
	});
	/*Appearance for custom select:end 12.02.14*/
/*Misc-2:end*/
	
/*New rollovers:end*/
});




/**********************************************************************/
/* DK SCRIPT
/**********************************************************************/

jQuery(function($){


	//review_button

	$ ('.review-reveal').click(function() {
		
		$ ('#review_form_wrapper').css('display','block');

	});

	$('.google-map-dk').insertBefore( $('#main') );

	$('form[data-action="register"] .userpro-field-mailchimp_subscribe').prependTo( $('form[data-action="register"] .userpro-submit') );






	if ( $('#woocommerce_recently_viewed_products-2').length ) {
		$('#woocommerce_top_rated_products-2').remove();
	}



	if ($('body').hasClass('woocommerce') || $('body').hasClass('woocommerce-page')) {
		$('.menu-item-208').addClass('current-menu-item act')
	}




	$ ('body').prepend('<div id="notify-box" class="notification-shape shape-progress"><svg width="70px" height="70px"><path d="m35,2.5c17.955803,0 32.5,14.544199 32.5,32.5c0,17.955803 -14.544197,32.5 -32.5,32.5c-17.955803,0 -32.5,-14.544197 -32.5,-32.5c0,-17.955801 14.544197,-32.5 32.5,-32.5z"/></svg></div>');
	$ ('.notify-box .ns-close').click(function(){ 
		$('.notify-box').fadeOut(500, function() { $(this).remove(); });
	 });

	function autoRemoveNotification() {
		$('.notify-box').fadeOut(500, function() { $(this).remove(); });
	}

	setTimeout(autoRemoveNotification, 15000);







	$('.mac .sidebar-content, .win .sidebar-content, .linux .sidebar-content').not($('.woocommerce-cart .sidebar-content') ).sticky({
		topSpacing: 10, // Space between element and top of the viewport
		zIndex: 100, // z-index
		stopper: "#footer" // Id, class, or number value
	});




	$('tr.order .order-actions .button:nth-child(2)').html('Invoice');

	$('#update_cart_alias').click(function(){
		$('#update_cart').click();
	});




	$('.proinput form .orig').addClass('placeholder-dk');

	$('.proinput form .orig').click(function(){
		$('.proinput form .orig').removeClass('placeholder-dk');
	});








	$('#main-nav > li.mega-auto-width > ul.sub-nav > li.menu-item').each(function(){
		var $categoryLink = $(this).children('a').attr('href');
		var $viewAllHtml = '<div class="view-all"><a class="view-all-link" href="' + $categoryLink + '"><div class="view-all-content"></div></a></div>';
		$(this).append($viewAllHtml);
	});

	$('.custom-nav > li.menu-item').each(function(){
		var $categoryLinkSide = $(this).children('a').attr('href');
		var $viewAllHtmlSide = '<div class="view-all"><a class="view-all-link" href="' + $categoryLinkSide + '"><div class="view-all-content"></div></a></div>';
		$(this).children('.custom-menu').append($viewAllHtmlSide);
	});
	





		var menuTimeoutShow,
		menuTimeoutHide;

	$(".my-account").on("mouseenter tap", function(e) {
		if(e.type == "tap") e.stopPropagation();

		var $this = $(this);
		$this.addClass("dt-hovered");

		clearTimeout(menuTimeoutShow);
		clearTimeout(menuTimeoutHide);

		menuTimeoutShow = setTimeout(function() {
			if($this.hasClass("dt-hovered")){
				$this.children('.logout-box').stop().css("visibility", "visible").animate({
					"opacity": 1
				}, 200);
			}
		}, 350);
	});

	$(".my-account").on("mouseleave", function(e) {
		var $this = $(this);
		$this.removeClass("dt-hovered");

		clearTimeout(menuTimeoutShow);
		clearTimeout(menuTimeoutHide);

		menuTimeoutHide = setTimeout(function() {
			if(!$this.hasClass("dt-hovered")){
				$this.children('.logout-box').stop().animate({
					"opacity": 0
				}, 150, function() {
					$(this).css("visibility", "hidden");
				});
				setTimeout(function() {
					if(!$this.hasClass("dt-hovered")){
						$this.children('.logout-box').removeClass("right-overflow");
					}
				}, 400);
				
			}
		}, 200);

	});


	new mlPushMenu( document.getElementById( 'mp-menu' ), document.getElementById( 'trigger' ), {
    type : 'cover'
	} );





	$('.coupon-list li.coupon').each(function(){


		var $couponContents = 
            $(this).contents().eq(1).text();
		var $couponContents2 = 
            $(this).contents().eq(2).text();
		var $couponContents3 = 
            $(this).contents().eq(3).text();

		var $couponImage = '<div class="coupon-image"><span>' + $couponContents + $couponContents2 + $couponContents3 + '</span></div>';

		$(this).prepend($couponImage);
        $(this).children('span.coupon').prepend('<span class="pre-code">Promotion Code</span>');

	});

	$('.coupon-list li.coupon').hover(
		function(){
			$(this).addClass('dk-hover');
		},
		function(){
			$(this).removeClass('dk-hover');
		}

	)
	
	$('#loading-ajax').hide();
// Bind the submit event for your form
$('.search-header #searchform').submit(function( e ){ 

    // Stop the form from submitting
    e.preventDefault();

    // Get the search term
    var term = $('#s').val();

    // Make sure the user searched for something
    if ( term ){
    	$('#loading-ajax').show();

        $.get( '/', { s: term }, function( data ){

            // Place the fetched results inside the #content element
            $('.search-results-dk').html( $(data).find('#content') );  
        });

	$('.search-results-dk').ajaxStop ( function(){
		$('#loading-ajax').hide();
		$('.search-results-dk').addClass('is-visible');
	});

	$(document).click(function(){
		if ( $('.search-results-dk').hasClass('is-visible') ) {
			$('.search-results-dk').removeClass('is-visible');
		}
	});

    }

});


$('.mobile-false .featured-dk .owl-next').click(function(){
	var $leftDistance = parseInt($('.featured-scroller ul.products').css('left'));

	if ( $leftDistance > -760) {
		$leftDistance = $leftDistance - 273;
		$('.featured-scroller ul.products').css('left', $leftDistance);
	}
});

$('.mobile-false .featured-dk .owl-prev').click(function(){
	var $leftDistance = parseInt($('.featured-scroller ul.products').css('left'));

	if ( $leftDistance < 0) {
		$leftDistance = $leftDistance + 273;
		$('.featured-scroller ul.products').css('left', $leftDistance);
	}
});
$('.mobile-false .recent-dk .owl-next').click(function(){
	var $leftDistance = parseInt($('.recent-scroller ul.products').css('left'));

	if ( $leftDistance > -760) {
		$leftDistance = $leftDistance - 273;
		$('.recent-scroller ul.products').css('left', $leftDistance);
	}
});

$('.mobile-false .recent-dk .owl-prev').click(function(){
	var $leftDistance = parseInt($('.recent-scroller ul.products').css('left'));

	if ( $leftDistance < 0) {
		$leftDistance = $leftDistance + 273;
		$('.recent-scroller ul.products').css('left', $leftDistance);
	}
});


$('.mobile-true .featured-dk .owl-next').click(function(){

		$('.featured-scroller .woocommerce').animate( {scrollLeft: '+=273'}, 450 );

});

$('.mobile-true .featured-dk .owl-prev').click(function(){

		$('.featured-scroller .woocommerce').animate( {scrollLeft: '-=273'}, 450 );

});
$('.mobile-true .recent-dk .owl-next').click(function(){

		$('.recent-scroller .woocommerce').animate( {scrollLeft: '+=273'}, 450 );

});

$('.mobile-true .recent-dk .owl-prev').click(function(){

		$('.recent-scroller .woocommerce').animate( {scrollLeft: '-=273'}, 450 );

});


		var $cartTotal = $('#top-bar .shopping-cart .total .amount').text().replace("$","");
		var $remainingInt = 120 - parseInt($cartTotal);
		var $remaining = '$' + $remainingInt;
		var $remainingFree = 'FREE';
	if ($remainingInt > 0) {
		$(".amountremain").html($remaining);
	} else {
		$('.amountremain').html($remainingFree);
		$('.remain-text').html("Shipping with Current Cart!");
	}




	var $dlmenu = $('.dl-menu');
	$('.dl-trigger').addClass('menu-closed');

	$('.dl-trigger').click( function(){
		if ( $(this).hasClass('menu-closed') ) {
		 	$dlmenu.addClass('dl-menuopen');
		 	$dlmenu.addClass('dl-menu-toggle');
		 	$(this).removeClass('menu-closed');
		} else {
		 	$dlmenu.removeClass('dl-menuopen');
		 	$dlmenu.removeClass('dl-menu-toggle');
		 	$(this).addClass('menu-closed');
		}
	});

	$('.reason-select').on('change', function(){
		if ( $('option[value="Other (describe below)"]').prop('selected') ) {
			$('.describe textarea').attr('aria-required', 'true');
			$('.field-set.describe .field-title').html('Describe the return reason');
		} else {
			$('.describe textarea').attr('aria-required', 'false');
			$('.field-set.describe .field-title').html('Optional note');
		}
	});





	if ( $('#page').height() < $(window).height() ) {
		var $top = $('#bottom-bar').offset().top;
		var $height = $('#bottom-bar').height();
		var $windowHeight = $(window).height();
		var $bottom = $windowHeight - $top - $height;

		$('#footer').css('margin-top', $bottom - 5 + 'px');
	}


	$('body').bind('added_to_cart', dt_update_cart_dropdown);
	
	// Dupliacte Count Reset for notifications
	$('#notify-box').data('count', 0);

//DK SCRIPT ########
});

function dt_update_cart_dropdown(event, parts, hash) {
	var miniCart = jQuery('.shopping-cart');

	if ( parts['div.widget_shopping_cart_content'] ) {

		var $cartContent = jQuery(parts['div.widget_shopping_cart_content']),
			$itemsList = $cartContent .find('.cart_list'),
			$total = $cartContent .find('.total'),
			$buttons = miniCart.find('.buttons');

		miniCart.find('.shopping-cart-inner').html('').append($itemsList, $total, $buttons);
	}
}

//Classie
!function(s){"use strict";function e(s){return new RegExp("(^|\\s+)"+s+"(\\s+|$)")}function n(s,e){var n=a(s,e)?c:t;n(s,e)}var a,t,c;"classList"in document.documentElement?(a=function(s,e){return s.classList.contains(e)},t=function(s,e){s.classList.add(e)},c=function(s,e){s.classList.remove(e)}):(a=function(s,n){return e(n).test(s.className)},t=function(s,e){a(s,e)||(s.className=s.className+" "+e)},c=function(s,n){s.className=s.className.replace(e(n)," ")});var i={hasClass:a,addClass:t,removeClass:c,toggleClass:n,has:a,add:t,remove:c,toggle:n};"function"==typeof define&&define.amd?define(i):s.classie=i}(window);
//Classie

//Accordion
!function(e){function n(n,i){var s=e.extend({},e.fn.accordion.defaults,i),u="";return n.each(function(){var n=e(this);o(n,s),"mouseenter"==s.bind&&n.bind("mouseenter",function(e){e.preventDefault(),c(n,s)}),"mouseover"==s.bind&&n.bind("mouseover",function(e){e.preventDefault(),c(n,s)}),"click"==s.bind&&n.bind("click",function(e){e.preventDefault(),c(n,s)}),"dblclick"==s.bind&&n.bind("dblclick",function(e){e.preventDefault(),c(n,s)}),id=n.attr("id"),t(s)&&l(s)?a(id,s)===!1?(n.addClass(s.cssClose),s.loadClose(n,s)):(n.addClass(s.cssOpen),s.loadOpen(n,s),u=id):id!=s.defaultOpen?(n.addClass(s.cssClose),s.loadClose(n,s)):(n.addClass(s.cssOpen),s.loadOpen(n,s),u=id)}),u.length>0&&t(s)?d(u,s):d("",s),n}function o(e,n){return e.data("accordion-opts",n)}function i(n){opened=e(document).find("."+n.cssOpen),e.each(opened,function(){e(this).addClass(n.cssClose).removeClass(n.cssOpen),n.animateClose(e(this),n)})}function s(e,n){i(n),e.removeClass(n.cssClose).addClass(n.cssOpen),n.animateOpen(e,n),t(n)&&(id=e.attr("id"),d(id,n))}function c(e,n){return e.hasClass(n.cssOpen)?(i(n),t(n)&&d("",n),!1):(i(n),s(e,n),!1)}function t(n){return e.cookie&&""!=n.cookieName?!0:!1}function d(n,o){return t(o)?void e.cookie(o.cookieName,n,o.cookieOptions):!1}function a(n,o){return t(o)&&l(o)?(cookie=unescape(e.cookie(o.cookieName)),cookie!=n?!1:!0):!1}function l(n){return t(n)?null==e.cookie(n.cookieName)?!1:!0:!1}e.fn.accordion=function(e){return!this||this.length<1?this:void n(this,e)},e.fn.accordion.defaults={cssClose:"accordion-close",cssOpen:"accordion-open",cookieName:"accordion",cookieOptions:{path:"/",expires:7,domain:"",secure:""},defaultOpen:"",speed:"slow",bind:"click",animateOpen:function(e,n){e.next().stop(!0,!0).slideDown(n.speed)},animateClose:function(e,n){e.next().stop(!0,!0).slideUp(n.speed)},loadOpen:function(e){e.next().show()},loadClose:function(e){e.next().hide()}}}(jQuery);
//Accordion






//ML Push menu
!function(e){"use strict";function t(e,t){for(var i in t)t.hasOwnProperty(i)&&(e[i]=t[i]);return e}function i(e,t){if(!e)return!1;for(var i=e.target||e.srcElement||e||!1;i&&i.id!=t;)i=i.parentNode||!1;return i!==!1}function s(e,t,i,l){return l=l||0,e.id.indexOf(t)>=0?l:(classie.has(e,i)&&++l,e.parentNode&&s(e.parentNode,t,i,l))}function l(){var t=!1;return function(e){(/(android|ipad|playbook|silk|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(e)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(e.substr(0,4)))&&(t=!0)}(navigator.userAgent||navigator.vendor||e.opera),t}function o(e,t){return classie.has(e,t)?e:e.parentNode&&o(e.parentNode,t)}function a(e,i,s){this.el=e,this.trigger=i,this.options=t(this.defaults,s),this.support=Modernizr.csstransforms3d,this.support&&this._init()}a.prototype={defaults:{type:"overlap",levelSpacing:40,backClass:"mp-back"},_init:function(){this.open=!1,this.level=0,this.wrapper=document.getElementById("mp-pusher"),this.levels=Array.prototype.slice.call(this.el.querySelectorAll(".mp-level"));var e=this;this.levels.forEach(function(t){t.setAttribute("data-level",s(t,e.el.id,"mp-level"))}),this.menuItems=Array.prototype.slice.call(this.el.querySelectorAll("li")),this.levelBack=Array.prototype.slice.call(this.el.querySelectorAll("."+this.options.backClass)),this.eventtype=l()?"click":"click",this.eventtypetwo=l()?"touchstart":"click",classie.add(this.el,"mp-"+this.options.type),this._initEvents()},_initEvents:function(){var e=this,t=function(i){e._resetMenu(),i.removeEventListener(e.eventtypetwo,t)};this.trigger.addEventListener(this.eventtype,function(s){s.stopPropagation(),s.preventDefault(),e.open?e._resetMenu():(e._openMenu(),document.addEventListener(e.eventtypetwo,function(s){e.open&&!i(s.target,e.el.id)&&t(this)}))}),this.menuItems.forEach(function(t){var i=t.querySelector(".mp-level");i&&t.querySelector("a").addEventListener(e.eventtype,function(s){s.preventDefault();var l=o(t,"mp-level").getAttribute("data-level");e.level<=l&&(s.stopPropagation(),classie.add(o(t,"mp-level"),"mp-level-overlay"),e._openMenu(i))})}),this.levels.forEach(function(t){t.addEventListener(e.eventtype,function(i){i.stopPropagation();var s=t.getAttribute("data-level");e.level>s&&(e.level=s,e._closeMenu())})}),this.levelBack.forEach(function(t){t.addEventListener(e.eventtype,function(i){i.preventDefault();var s=o(t,"mp-level").getAttribute("data-level");e.level<=s&&(i.stopPropagation(),e.level=o(t,"mp-level").getAttribute("data-level")-1,0===e.level?e._resetMenu():e._closeMenu())})})},_openMenu:function(e){++this.level;var t=(this.level-1)*this.options.levelSpacing,i="overlap"===this.options.type?this.el.offsetWidth+t:this.el.offsetWidth;if(this._setTransform("translate3d("+i+"px,0,0)"),e){this._setTransform("",e);for(var s=0,l=this.levels.length;l>s;++s){var o=this.levels[s];o==e||classie.has(o,"mp-level-open")||this._setTransform("translate3d(-100%,0,0) translate3d("+-1*t+"px,0,0)",o)}}1===this.level&&(classie.add(this.wrapper,"mp-pushed"),this.open=!0),classie.add(e||this.levels[0],"mp-level-open")},_resetMenu:function(){this._setTransform("translate3d(0,0,0)"),this.level=0,classie.remove(this.wrapper,"mp-pushed"),this._toggleLevels(),this.open=!1},_closeMenu:function(){var e="overlap"===this.options.type?this.el.offsetWidth+(this.level-1)*this.options.levelSpacing:this.el.offsetWidth;this._setTransform("translate3d("+e+"px,0,0)"),this._toggleLevels()},_setTransform:function(e,t){t=t||this.wrapper,t.style.WebkitTransform=e,t.style.MozTransform=e,t.style.transform=e},_toggleLevels:function(){for(var e=0,t=this.levels.length;t>e;++e){var i=this.levels[e];i.getAttribute("data-level")>=this.level+1?(classie.remove(i,"mp-level-open"),classie.remove(i,"mp-level-overlay")):Number(i.getAttribute("data-level"))==this.level&&classie.remove(i,"mp-level-overlay")}}},e.mlPushMenu=a}(window);
//ML Push menu




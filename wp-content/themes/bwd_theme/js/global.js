var $ = jQuery.noConflict();

// @codekit-prepend '_plugins/jquery-cache.js'

// @codekit-prepend '_plugins/fastclick.js'

// @codekit-prepend '_plugins/css_browser_selectors.js'


$window = $$(window);

var	$this,
	$parent,
	$resultsBox,
	$viewMore,
	$productHREF,
	$productID,
	$sidebarTop,
	$sidebarWidth,
	$sidebarHeight,
	$footerTop,
	$miniCart,
	$cartContent,
	$cartItems,
	$cartTotal,
	$cartActions,
	$remaining,
	$link,
	$menu,
	$menuMega,
	visible,
	wasClicked,
	firstClick,
	menuTimeoutShow,
	menuTimeoutHide,
	isMobileWidth = window.innerWidth <= 736,
	isMobile = $$('html').hasClass('mobile')
;

// @codekit-append '_parts/_header/menu-nav.js'

// @codekit-append '_parts/_header/hover-boxes.js'

// @codekit-append '_parts/_header/mini-cart.js'

// @codekit-append '_parts/_header/free-shipping.js'

// @codekit-append '_parts/_header/search-ajax.js'

// @codekit-append '_parts/notices.js'

// @codekit-append '_parts/sidebar.js'

// @codekit-append '_parts/popup.js'

// @codekit-append '_parts/popup-quickview.js'









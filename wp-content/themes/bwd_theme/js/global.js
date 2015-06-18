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
	$addedProduct,
	addingInProgress = false,
	visible,
	wasClicked,
	firstClick,
	menuTimeoutShow,
	menuTimeoutHide,
	isMobileWidth = window.innerWidth <= 736,
	isMobile = $$('html').hasClass('mobile')
;

function normalKeycodes(event){
	if (   event.keyCode === 8                              // backspace
	    || event.keyCode === 9 								// tab
	    || event.keyCode === 16 							// shift
	    || event.keyCode === 17 							// ctrl
	    || event.keyCode === 18 							// alt
	    || event.keyCode === 46                             // delete
	    || (event.keyCode >= 35 && event.keyCode <= 40)     // arrow keys/home/end

	    || (event.keyCode >= 48 && event.keyCode <= 57)     // numbers on keyboard
	    || (event.keyCode >= 96 && event.keyCode <= 105)    // number on keypad
	  
	    || (event.keyCode === 32 || event.keyCode === 189 || event.keyCode === 190 || event.keyCode === 173)    // space, dash, dot
	 ) {
	  	return true;
	} else {
		return false;
	}
}

// @codekit-append '_parts/_header/menu-nav.js'

// @codekit-append '_parts/_header/hover-boxes.js'

// @codekit-append '_parts/_header/add-to-cart.js'

// @codekit-append '_parts/_header/mini-cart-update.js'

// @codekit-append '_parts/_header/free-shipping.js'

// @codekit-append '_parts/_header/search-ajax.js'

// @codekit-append '_parts/notices.js'

// @codekit-append '_parts/sidebar.js'

// @codekit-append '_parts/popup.js'

// @codekit-append '_parts/popup-quickview.js'









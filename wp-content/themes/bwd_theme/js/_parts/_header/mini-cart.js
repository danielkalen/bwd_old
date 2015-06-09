// ==== Mini Cart =================================================================================

$$('body').on('added_to_cart', function(event, fragments, hash){
	$miniCart = $$('.top-bar-links-cart-box');
	$cartContent = $$(fragments['div.widget_shopping_cart_content']);
	$cartItems = $cartContent.find('.cart-list');
	$cartTotal = $cartContent.find('.cart-list-total');
	$cartActions = $cartContent.find('.cart-list-actions');

	$miniCart.empty().append($cartItems, $cartTotal, $cartActions);
});
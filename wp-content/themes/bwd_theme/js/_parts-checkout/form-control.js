

$$('form.checkout').formPrepare();

$$('form.checkout .next').on('click', function(){
	$$('form.checkout').formNext();
});
$('form.checkout .submit').on('click', function(){
	$('form.checkout').formSubmit();
});
$$('form.checkout .back').on('click', function(){
	$$('form.checkout').formBack();
});

$$('.checkout-section-title').on('click', function(){
	$(this).parent().formOpen();
});

setTimeout(function(){
	$$('.checkout-section-fieldset-label').find('abbr').remove();
}, 1000);


$$('body').on('updated_checkout', function(){
	initCard();
	attachStripeFieldEvents();
	popupAddEventListener(document.querySelectorAll( '.popup-trigger.terms' )[0]);
});



// ==== Shipping select change sync =================================================================================

$$('.widget_checkout_totals').on('change', 'select', function(){
	$('form.checkout .shipping_method').val($(this).val()).change();
});

/* ========================================================================== */








// ==== Form Login =================================================================================

attachEvents($$('#username'));
attachEvents($$('#password'));
attachEvents($$('#coupon_code'));

$$('.login-button').on('click', function(){
	$$('.login-button-hidden').click();
});

$$('.apply_coupon-button').on('click', function(){
	$$('.apply_coupon-button-hidden').click();
});

/* ========================================================================== */






// ==== Notices =================================================================================

$$('body').on('checkout_error', function(){
	$('.woocommerce .notice').each(function(){
		addNotice($(this), true);
	});q
});

/* ========================================================================== */



function initCard(){
	$('#payment').card({
		container: $('.card-wrapper'),
	    numberInput: '#stripe-card-number',
	    expiryInput: '#stripe-card-expiry',
	    cvcInput: '#stripe-card-cvc',
	    nameInput: '#stripe-card-name'
	});
}

function attachStripeFieldEvents(){
	$.fn.formPrepare.inputField($('.checkout-section-fieldset.cardnumber'));
	$.fn.formPrepare.numberField($('.checkout-section-fieldset.cardexpire'));
	$.fn.formPrepare.numberField($('.checkout-section-fieldset.cardcvc'));
	attachEvents($('#stripe-card-number'));
	attachEvents($('#stripe-card-expiry'));
	attachEvents($('#stripe-card-cvc'));
	attachEvents($('input[name="cardname"]'));


	$.fn.formPrepare.checkboxField($('.payment .checkout-section-fieldset.checkbox'));

	$('form.checkout .submit').on('click', function(){
		$('form.checkout').formSubmit();
	});
}
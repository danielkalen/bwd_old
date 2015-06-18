

$$('form.checkout').formPrepare();

$$('form.checkout .next').on('click', function(){
	$$('form.checkout').formNext();
});
$$('form.checkout .submit').on('click', function(){
	$$('form.checkout').formSubmit();
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
});


function initCard(){
	$('#payment').card({
		container: $('.card-wrapper'),
	    numberInput: '#stripe-card-number',
	    expiryInput: '#stripe-card-expiry',
	    cvcInput: '#stripe-card-cvc',
	    nameInput: '#stripe-card-name'
	});
}
var proceed,
	problem,
	$required,
	$currentStep,
	$previousStep,
	$lastField,
	billingSame,
	isOne,
	isTwo,
	isThree,
	billingHeight = $$('.checkout-section.billing').height(),
	shippingHeight = $$('.checkout-section.shipping').height(),
	paymentHeight = $$('.checkout-section.payment').height()
	;

$window.on('resize', function(){
	if ( window.innerWidth <= 920 ) {
		billingHeight = $$('.checkout-section.billing').find('.checkout-section-wrap').height() + 80;
		shippingHeight = $$('.checkout-section.shipping').find('.checkout-section-wrap').height() + 80;
		paymentHeight = $$('.checkout-section.payment').find('.checkout-section-wrap').height() + 80;

		setSectionHeights();
	}
});

$.fn.form = function($arg, $url) {}











$.fn.formPrepare = function() {

	var a = $$('form.checkout').find('.checkout-section.billing'),
		b = $$('form.checkout').find('.checkout-section.shipping'),
		c = $$('form.checkout').find('.checkout-section.payment');
	
	// $$('form.checkout').addClass('form-open');
	a.addClass('show').height(billingHeight);
					b.height(39);
					c.height(39);



	$$('form.checkout').find('.fieldset').not('.radio, .checkbox').each(function(){
		$.fn.formPrepare.inputField($(this));
	});

	$$('form.checkout').find('.fieldset.checkbox').each(function(){
		$.fn.formPrepare.checkboxField($(this));
	});


	$$('form.checkout').find('.phone, .zip, .cardnumber, .cardcvc, .carddate').each(function(){
		$.fn.formPrepare.numberField($(this));
	});

	$$('form.checkout').find('.email').each(function(){
		$.fn.formPrepare.emailField($(this));
	});

	$$('.checkout-section').each(function(){
		$lastField = $(this).find('.fieldset').not('.checkbox').last();

		$lastField.on('keydown', function(e){
			if ( e.keyCode === 9 ) e.preventDefault();
		});
	});

	$$('form.checkout .input').each(function(){
		$this = $(this);
		attachEvents($this);
	});
}




$.fn.formValidate = function() {
	proceed = false;
	problem = false;
	$required = $$('form.checkout').find('.checkout-section.show').find('.fieldset.required');
	billingSame = $$('form.checkout').find('.billing .fieldset.checkbox .input-button').hasClass('checked');

	$required.each(function(){

		$this = $(this);

		if ( !$this.hasClass('valid') ) {
			problem = true;
			$this.addClass('error');

		} else {
			$this.removeClass('error');
		}
	});

	if ( problem ) {
		proceed = false;
	} else {
		proceed = true;
	}
}






$.fn.formNext = function() {
	$currentStep = $$('form.checkout').find('.checkout-section.show')

	if ( !$currentStep.hasClass('payment') ) {
		$$('form.checkout').formValidate();
	} else {
		proceed = true;
	}

	if ( proceed ) {
		var isOne = $$('form.checkout').find('.checkout-section.billing').hasClass('show'),
			$nextStep;

		if ( isOne ) {
			if ( billingSame ) {
				$nextStep = $currentStep.next().next();
			} else {
				$nextStep = $currentStep.next();
			}
		} else {
			$nextStep = $currentStep.next();
		}

		$currentStep.removeClass('show').height(39);
		$nextStep.addClass('show');
		setSectionHeight($nextStep);
		scrollUpIfNeeded($nextStep);
	}
}





$.fn.formOpen = function() {
	if ( !this.hasClass('show') ) {
		var $currentStep = $$('form.checkout').find('.checkout-section.show');

		if ( !$currentStep.hasClass('payment') ) {
			$$('form.checkout').formValidate();
		} else {
			proceed = true;
		}

		if ( !proceed ) {

			$currentStep.addClass('incomplete');
		}
		if (this.hasClass('shipping')) {
			if (billingSame) {
				if ( !$currentStep.hasClass('billing') ) {
					$currentStep.removeClass('show').height(39);
					$$('.checkout-section.billing').addClass('show');
					setSectionHeight($$('.checkout-section.billing'));
				}

				$$('#ship-to-different-address').addClass('highlight');

				setTimeout(function(){
					$$('#ship-to-different-address').removeClass('highlight');
				}, 2000);

			} else {
				$currentStep.removeClass('show').height(39);
				this.addClass('show');
				setSectionHeight(this);
				scrollUpIfNeeded(this);
			}

		} else {
			$currentStep.removeClass('show').height(39);
			this.addClass('show');
			setSectionHeight(this);

			scrollUpIfNeeded(this);
		}
	}
}










$.fn.formSubmit = function() {
	$$('form.checkout').formValidate();

		if ( proceed ) {
			$$('#place_order').click();
		}

}









$.fn.formBack = function() {
	$currentStep = $$('form.checkout').find('.checkout-section.show');
	$previousStep = $currentStep.prev();

	if ( $currentStep.hasClass('payment') ) {
		if (billingSame) {
			var $previousStep = $currentStep.prev().prev();
		}
	}

	$currentStep.removeClass('show').height(39);
	$previousStep.addClass('show');
	setSectionHeight($previousStep);

	scrollUpIfNeeded($previousStep);
}
















function attachEvents($field){
	$field.focus(function(){
		$(this).parent().addClass('focus');
	});
	$field.blur(function(){
		$(this).parent().removeClass('focus');
	});

	$field.keyup(function(){
		if ( $(this).val() === '' ) {
			$(this).parent().removeClass('filled');
		} else {
			$(this).parent().addClass('filled');
		}
	});

	if ( $field.parent().hasClass('select') ) {
		$field.change(function(){
			if ( $(this).val() !== '' ) {
				$(this).parent().addClass('filled');
			} else {
				$(this).parent().removeClass('filled');
			}
		});
	}
	if ( $field.attr('type') !== 'checkbox' ){
		if ( $field.val() !== '' ) {
			$field.parent().addClass('filled valid');
		}
	}
}

function makeValid($subject) {
	$subject.addClass('valid').removeClass('invalid error');
}
function makeInvalid($subject, error) {
	$subject.addClass('invalid').removeClass('valid');

	if (error) {
		$subject.addClass('invalid error');
	}
}

function scrollUpIfNeeded($openSection){
	if ( window.pageYOffset > $openSection.offset().top ) {
		$("html, body").animate({ scrollTop: ($openSection.offset().top - 70) }, 300);
	}
}

function setSectionHeight($section){

	if ($section.hasClass('billing')) {
		$section.height(billingHeight);
		// setTimeout(function(){billingHeight = $section.height();},1000);
		billingHeight = $$('.checkout-section.billing').find('.checkout-section-wrap').height() + 80;
	}
	if ($section.hasClass('shipping')) {
		$section.height(shippingHeight);
		// setTimeout(function(){shippingHeight = $section.height();},1000);
		shippingHeight = $$('.checkout-section.shipping').find('.checkout-section-wrap').height() + 80;
	}
	if ($section.hasClass('payment')) {
		$section.height(paymentHeight);
		// setTimeout(function(){paymentHeight = $section.height();},1000);
		paymentHeight = $$('.checkout-section.payment').find('.checkout-section-wrap').height() + 80;
	}
}

function billingFill(){
	var firstname = $$('#s-firstname').val();
	var lastname = $$('#s-lastname').val();
	var address = $$('#s-address').val();
	var apt = $$('#s-apt').val();
	var country = $$('#s-country').val();
	var state = $$('#s-state').val();
	var city = $$('#s-city').val();
	var zip = $$('#s-zip').val();

	$$('#b-firstname').val(firstname);
	$$('#b-lastname').val(lastname);
	$$('#b-address').val(address);
	$$('#b-apt').val(apt);
	$$('#b-country').val(country);
	$$('#b-state').val(state);
	$$('#b-city').val(city);
	$$('#b-zip').val(zip);

	// Make fields valid
	$$('form.checkout').find('.checkout-section.shipping .fieldset').removeClass('invalid error').addClass('filled');

	// Trigger change on fields
	$$('#b-firstname, #b-lastname, #b-address, #b-apt, #b-country, #b-state, #b-city, #b-zip').change();
}

function billingEmpty(){
	$$('#b-firstname').val('');
	$$('#b-lastname').val('');
	$$('#b-address').val('');
	$$('#b-apt').val('');
	$$('#b-country').val('');
	$$('#b-state').val('');
	$$('#b-city').val('');
	$$('#b-zip').val('');

	// Make fields valid
	$$('form.checkout').find('.checkout-section.shipping .fieldset').removeClass('invalid error filled');
}



function cardError(type, text, field) {
	// console.log(field);
	makeInvalid(field, true);
	buttonError(text);

	field.one('keydown', function(){
		buttonClear();
	});
}


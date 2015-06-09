jQuery(document).ready(function($) {
	$('.accordion').accordion({defaultOpen: 'section1'}); //some_id section1 in demo

	$('#next-step-1').click(function(){
		if ($('#ship-to-different-address-checkbox').prop('checked')) {

			$('#section1').removeClass('accordion-open');
			$('#section1').addClass('accordion-close');
			$('#billing-section').slideUp('slow');


			$('#section2').removeClass('accordion-close');
			$('#section2').addClass('accordion-open');
			$('#shipping-section').slideDown('slow');
		}

		else {
			$('#section1').removeClass('accordion-open');
			$('#section1').addClass('accordion-close');
			$('#billing-section').slideUp('slow');

			$('#section3').removeClass('accordion-close');
			$('#section3').addClass('accordion-open');
			$('.payment-section').slideDown('slow');
		}
	});

	$('#next-step-2').click(function(){
		$('#section2').removeClass('accordion-open');
		$('#section2').addClass('accordion-close');
		$('#shipping-section').slideUp('slow');

		$('#section3').removeClass('accordion-close');
		$('#section3').addClass('accordion-open');
		$('.payment-section').slideDown('slow');
	});

	// $('#section3').on('click', '.terms a', function(e){
	// 	e.preventDefault();
	// });

	$(document).ajaxComplete(function(){
		if ( !$('#stripe-card-name').length ) {

			$('#stripe-cc-form').append('<p class="form-row form-row-wide"><label for="stripe-card-name">Card Holder\'s name <span class="required">*</span></label><input id="stripe-card-name" class="input-text wc-credit-card-form-card-name" type="text" autocomplete="off" placeholder="Card Holder\'s name" name=""></p>');

			$('#stripe-cc-form').card({
				container: $('.card-wrapper'),
			    numberInput: 'input#stripe-card-number',
			    expiryInput: 'input#stripe-card-expiry',
			    cvcInput: 'input#stripe-card-cvc',
			    nameInput: 'input#stripe-card-name',
			    //width: 47%,
			});

			var billingName = $('#billing_first_name').val() + $('#billing_last_name').val();

		}


		
	});
});
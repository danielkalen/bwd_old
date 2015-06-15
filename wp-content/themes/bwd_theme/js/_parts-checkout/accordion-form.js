jQuery(document).ready(function($) {

	$('.billing-same-checkbox').prop('checked', true);
	$('.billing-address-field').hide();

	$('.billing-same-checkbox').on('change', function(){
		if ( !$(this).prop('checked') ) {
			$('.billing-address-field').show('slow');
		} else {
			$('.billing-address-field').hide('slow', function(){
				if ( $('.shipping-address').val().length > 0 ) {
					$('.billing-address').val( $('.shipping-address').val() );
				}
			});
		}
	});

	$('.accordion').accordion({defaultOpen: 'section1'}); //some_id section1 in demo

	$('#next-step-1').click(function(){

		$('#section1').removeClass('accordion-open');
		$('#section1').addClass('accordion-close');
		$('.section-details').slideUp('slow');


		$('#section2').removeClass('accordion-close');
		$('#section2').addClass('accordion-open');
		$('.section-contacts').slideDown('slow');

	});

	$('#next-step-2').click(function(){
		$('#section2').removeClass('accordion-open');
		$('#section2').addClass('accordion-close');
		$('.section-contacts').slideUp('slow');

		$('#section3').removeClass('accordion-close');
		$('#section3').addClass('accordion-open');
		$('.section-reference-bank').slideDown('slow');
	});

	$('#next-step-3').click(function(){
		$('#section2').removeClass('accordion-open');
		$('#section2').addClass('accordion-close');
		$('.section-reference-bank').slideUp('slow');

		$('#section3').removeClass('accordion-close');
		$('#section3').addClass('accordion-open');
		$('.section-reference-trade').slideDown('slow');
	});

	$('.owner-two, .owner-three, .bank-two, .bank-three, .trade-two, .trade-three').hide();

	$('.add-owner').click(function(){
		var $this = $(this);

		if ( $this.hasClass('first-add') ) {
			$this.hide();
			$('.owner-two').show('slow');
		}
		if ( $this.hasClass('second-add') ) {
			$this.hide();
			$('.owner-three').show('slow');
		}
	});
	
	$('.add-bank').click(function(){
		var $this = $(this);

		if ( $this.hasClass('first-add') ) {
			$this.hide();
			$('.bank-two').show('slow');
		}
		if ( $this.hasClass('second-add') ) {
			$this.hide();
			$('.bank-three').show('slow');
		}
	});

	$('.add-trade').click(function(){
		var $this = $(this);

		if ( $this.hasClass('first-add') ) {
			$this.hide();
			$('.trade-two').show('slow');
		}
		if ( $this.hasClass('second-add') ) {
			$this.hide();
			$('.trade-three').show('slow');
		}
	});

});
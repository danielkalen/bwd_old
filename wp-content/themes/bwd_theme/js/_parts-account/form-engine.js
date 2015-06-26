var proceed,
	problem,
	$required,
	$currentStep,
	$previousStep,
	$lastField	
	;

$window.on('resize', function(){
	if ( window.innerWidth <= 920 ) {
		saveSectionHeights();
	}
});

$.fn.form = function($arg, $url) {}











$.fn.formPrepare = function() {	
	
	saveSectionHeights();

	// Initial height set
	$$('#general').addClass('show').each(function(){
		$this = $(this);
		$this.height( $this.data('height') );

		$this.siblings().height(39);
	});



	$$('.wpcf7-form').find('.fieldset').not('.radio, .checkbox').each(function(){
		$.fn.formPrepare.inputField($(this));
	});

	$$('.wpcf7-form').find('.fieldset.checkbox').each(function(){
		$.fn.formPrepare.checkboxField($(this));
	});


	$$('.wpcf7-form').find('.phone, .zip, .cardnumber, .cardcvc, .carddate').each(function(){
		$.fn.formPrepare.numberField($(this));
	});

	$$('.wpcf7-form').find('.email').each(function(){
		$.fn.formPrepare.emailField($(this));
	});

	$$('.wpcf7-form-section').each(function(){
		$lastField = $(this).find('.fieldset').not('.checkbox').last();

		$lastField.on('keydown', function(e){
			if ( e.keyCode === 9 ) e.preventDefault();
		});
	});

	$$('.wpcf7-form .input').each(function(){
		$this = $(this);
		attachEvents($this);
	});
}




$.fn.formValidate = function() {
	proceed = false;
	problem = false;
	$required = $$('.wpcf7-form').find('.wpcf7-form-section.show').find('.fieldset.required');
	billingSame = $$('.wpcf7-form').find('.billing .fieldset.checkbox .input-button').hasClass('checked');

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
	$currentStep = $$('.wpcf7-form').find('.wpcf7-form-section.show');

	$$('.wpcf7-form').formValidate();

	if ( proceed ) {
		var $nextStep = $currentStep.next();

		revealSection($nextStep);
		scrollUpIfNeeded($nextStep);
	}
}





$.fn.formOpen = function() {
	if ( !this.hasClass('show') ) {
		var $currentStep = $$('.wpcf7-form').find('.wpcf7-form-section.show');

		$$('.wpcf7-form').formValidate();

		if ( !proceed ) {
			$currentStep.addClass('incomplete');
		}
		revealSection(this);

		scrollUpIfNeeded(this);
	}
}










$.fn.formBack = function() {
	$currentStep = $$('.wpcf7-form').find('.wpcf7-form-section.show');
	$previousStep = $currentStep.prev();

	revealSection($previousStep);
	scrollUpIfNeeded($previousStep);
}









$.fn.formSubmit = function() {
	$$('.wpcf7-form').formValidate();

		if ( proceed ) {
			$$('.wpcf7-submit').click();
		}

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

function saveSectionHeights(){

	$$('.wpcf7-form-section').each(function(){
			$this = $(this);
		var $thisHeight = $this.find('.wpcf7-form-section-wrap').height() + 90;

		$this.data('height', $thisHeight);
	});
}

function revealSection($section){
	showSection($section);
	$section.addClass('show')
	$section.siblings('.show').height(39).removeClass('show');
}

function showSection($section){
	$section.height( $section.data('height') );
}


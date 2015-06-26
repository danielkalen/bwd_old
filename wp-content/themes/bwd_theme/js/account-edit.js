// @codekit-append '_parts-checkout/form-engine.js'
// @codekit-append '_parts-checkout/form-engine-fields.js'


fieldEvents();

function fieldEvents() {
	$$('.userpro-form-fieldset').each(function(){
		$this = $(this);

		if ( !$this.hasClass('checkbox') ) {
			attachEvents($this.find('input'));
		} else {
			$.fn.formPrepare.checkboxField($this);
		}
	});

	$$('.userpro-form-actions-button').on('click',function(){
		$(this).siblings('.userpro-form-actions-button-hidden').click();
	});
}


function resetFormButtonsEvents(){
	$$('.userpro-form-actions-button.change').on('click',function(){
		$(this).siblings('.userpro-form-actions-button-hidden.change').click();
	});
	$$('.userpro-form-actions-button.request').on('click',function(){
		$(this).siblings('.userpro-form-actions-button-hidden.request').click();
	});
}
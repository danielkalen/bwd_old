// @codekit-prepend '_plugins/popup-md.js'
// @codekit-prepend '_parts-checkout/form-engine.js'
// @codekit-prepend '_parts-checkout/form-engine-fields.js'

setTimeout(
	function(){loginFieldEvents()},
	200
)

$$('body').on('reset_form', function(){
	loginFieldEvents();
	resetFormButtonsEvents();
});




function loginFieldEvents() {
	$('.userpro-form-fieldset').each(function(){
		$this = $(this);
		// $this.find('input[type="text"]').unwrap();

		if ( !$this.hasClass('checkbox') ) {
			attachEvents($this.find('input'));
		} else {
			$.fn.formPrepare.checkboxField($this);
		}
	});

	$('.userpro-form-actions-button').on('click',function(){
		$(this).siblings('.userpro-form-actions-button-hidden').click();
	});
}


function resetFormButtonsEvents(){
	$('.userpro-form-actions-button.change').on('click',function(){
		$(this).siblings('.userpro-form-actions-button-hidden.change').click();
	});
	$('.userpro-form-actions-button.request').on('click',function(){
		$(this).siblings('.userpro-form-actions-button-hidden.request').click();
	});
}
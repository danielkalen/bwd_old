// @codekit-prepend '_plugins/popup-md.js'
// @codekit-prepend '_parts-checkout/form-engine.js'
// @codekit-prepend '_parts-checkout/form-engine-fields.js'

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
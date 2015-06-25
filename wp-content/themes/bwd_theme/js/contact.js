// @codekit-append '_parts-checkout/form-engine.js'
// @codekit-append '_parts-checkout/form-engine-fields.js'


$$('.wpcf7-form-fieldset').each(function(){
	$this = $(this);

	if ( !$this.hasClass('checkbox') ) {
		attachEvents($this.find('input'));
	} else {
		$.fn.formPrepare.checkboxField($this);
	}
});


$$('.wpcf7-form-button').on('click',function(){
	$(this).siblings('.wpcf7-submit').click();
});
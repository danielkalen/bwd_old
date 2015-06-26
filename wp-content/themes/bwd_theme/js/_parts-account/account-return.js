if ( $$('.wpcf7-response-output').length && $$('.wpcf7-response-output').html() !== '' ) {

	if ( $$('.wpcf7-response-output').hasClass('wpcf7-mail-sent-ok') ) {
		notify( 'success', $$('.wpcf7-response-output').html() )
	} else {
		notify( 'error', $$('.wpcf7-response-output').html() )
	}
}


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

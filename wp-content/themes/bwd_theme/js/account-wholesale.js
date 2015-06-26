// @codekit-prepend '_parts-account/form-engine.js'
// @codekit-prepend '_parts-account/form-engine-fields.js'


$$('.wpcf7-form').formPrepare();

$$('.wpcf7-form .next').on('click', function(){
	$$('.wpcf7-form').formNext();
});
$('.wpcf7-form .submit').on('click', function(){
	$('.wpcf7-form').formSubmit();
});
$$('.wpcf7-form .back').on('click', function(){
	$$('.wpcf7-form').formBack();
});

$$('.wpcf7-form-section-title').on('click', function(){
	$(this).parent().formOpen();
});





// ==== Show Billing Address =================================================================================

$$('#general .fieldset.checkbox').on('click', function(){
	$(this).find('input').change();
});
$$('#general .fieldset.checkbox input').on('change', function(){
	if ( !$(this).prop('checked') ) {
		$$('.shipping-address.fieldset').show(300);
	} else {
		$$('.shipping-address.fieldset').hide(300);
	}
	setTimeout(function(){
		saveSectionHeights();
		showSection( $$('#general') );
	},300);
})






// ==== Add Field Set =================================================================================

$$('.wpcf7-form-buttons-item.add').on('click', function(){
	$this = $(this).parent();
	$thisSection = $(this).parents('.wpcf7-form-section');
	var someoneIsOpen = $this.siblings('.wpcf7-form-section-additional').hasClass('open');

	if (someoneIsOpen) {
		var $toBeOpened = $this.siblings('.wpcf7-form-section-additional.open').last().next();
	} else {
		var $toBeOpened = $this.siblings('.wpcf7-form-section-additional').first();
	}

	$toBeOpened.addClass('open');

	// Check if any others are open, and if not, hide the button.
	var numberOfOpen = $this.siblings('.wpcf7-form-section-additional.open').length,
		numberOfTotal = $this.siblings('.wpcf7-form-section-additional').length;
	if ( numberOfOpen === numberOfTotal ) {
		$(this).remove();
	}

	saveSectionHeights();
	showSection( $thisSection );
});





// ==== Notifications =================================================================================
if ( $$('.wpcf7-response-output').length && $$('.wpcf7-response-output').html() !== '' ) {

	if ( $$('.wpcf7-response-output').hasClass('wpcf7-mail-sent-ok') ) {
		notify( 'success', $$('.wpcf7-response-output').html() )
	} else {
		notify( 'error', $$('.wpcf7-response-output').html() )
	}
}

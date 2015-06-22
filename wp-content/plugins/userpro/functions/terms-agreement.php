<?php

	// add custom message to a user profile
	add_action('userpro_before_form_submit', 'userpro_before_form_submit', 10);
	function userpro_before_form_submit($args){
		global $userpro;
		extract( $args );
		
		if ($args['template'] == 'register' && userpro_get_option('terms_agree') == 1 ) {
		
			?>
			

				<div class="userpro-form-fieldset fieldset checkbox userpro-field userpro-maxwidth" data-required="1" data-required_msg="<?php _e('You must accept our terms and conditions','userpro'); ?>">
					<div class="userpro-form-fieldset-checkbox input-button userpro-input">
						<div class="userpro-form-fieldset-checkbox-box"></div>
						<label for="terms" class="userpro-form-fieldset-checkbox-label">
							I agree to the <span class="userpro-form-fieldset-checkbox-label-highlight popup-trigger terms">terms &amp; conditions.</span>
						</label>
						<input class="input input-checkbox" type='checkbox' name='terms' id="terms" />
						
					</div>
				</div>

			
			<?php
		
		}
	}
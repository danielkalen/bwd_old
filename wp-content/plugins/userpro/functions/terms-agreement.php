<?php

	// add custom message to a user profile
	add_action('userpro_before_form_submit', 'userpro_before_form_submit', 10);
	function userpro_before_form_submit($args){
		global $userpro;
		extract( $args );
		
		if ($args['template'] == 'register' && userpro_get_option('terms_agree') == 1 ) {
		
			?>
			

				<div class="userpro-field userpro-maxwidth" data-required="1" data-required_msg="<?php _e('You must accept our terms and conditions','userpro'); ?>">
					<div class="userpro-input">
					
						<div class='userpro-checkbox-wrap'>
							<label class='userpro-checkbox'>
								<span></span>
								<input type='checkbox' name='terms' id="terms" /><?php echo 'I agree to the ' . '<a class="md-trigger" data-modal="modal-5">terms and conditions</a>'; ?>
							</label>
						</div>
						
					</div>
				</div>

			
			<?php
		
		}
	}
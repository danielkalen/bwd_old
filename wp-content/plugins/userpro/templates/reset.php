<div class="reset userpro userpro-<?php echo $i; ?> userpro-<?php echo $layout; ?>" <?php userpro_args_to_data( $args ); ?>>
	
	<div class="userpro-title userpro-head">
		<div class="userpro-title-text">
			<div class="userpro-title-text-left">
				<?php echo $args["{$template}_heading"]; ?>
			</div>
			<a href="#" data-template="<?php echo $args["{$template}_side_action"]; ?>" class="userpro-title-text-right">
				<?php echo $args["{$template}_side"]; ?>
			</a>
		</div>
	</div>
	
	<div class="userpro-wrap userpro-body">
	

		<div class="userpro-intro">We'll email you a secret key. Once you obtain the key, you can use it to Change your Password.</div>

		<?php do_action('userpro_pre_form_message'); ?>

		<form class="userpro-form" action="" method="post" data-action="<?php echo $template; ?>">
		
			<?php // Hook into fields $args, $user_id
			if (!isset($user_id)) $user_id = 0;
			$hook_args = array_merge($args, array('user_id' => $user_id, 'unique_id' => $i));
			do_action('userpro_before_fields', $hook_args);
			?>
		
			<!-- fields -->
			<div class='userpro-form-fieldset fieldset userpro-field userpro-field-user_email' data-key='username_or_email'>
				<label for="username_or_email-<?php echo $i; ?>" class="userpro-form-fieldset-label">Email Address</label>
				<input class="userpro-form-fieldset-input" type='text' placeholder="Email Address" name='username_or_email-<?php echo $i; ?>' id='username_or_email-<?php echo $i; ?>' />
			</div>
			
			<?php  $key = 'antispam'; $array = $userpro->fields[$key];
				if (isset($array) && is_array($array)) echo userpro_edit_field( $key, $array, $i, $args ); ?>
			
			<?php // Hook into fields $args, $user_id
			if (!isset($user_id)) $user_id = 0;
			$hook_args = array_merge($args, array('user_id' => $user_id, 'unique_id' => $i));
			do_action('userpro_after_fields', $hook_args);
			?>
						
			<?php // Hook into fields $args, $user_id
			if (!isset($user_id)) $user_id = 0;
			$hook_args = array_merge($args, array('user_id' => $user_id, 'unique_id' => $i));
			do_action('userpro_before_form_submit', $hook_args);
			?>
			
			<?php if ($args["{$template}_button_primary"] ||  $args["{$template}_button_secondary"] ) { ?>
			<div class="userpro-form-actions userpro-field userpro-submit userpro-column">

				
				<?php if ($args["{$template}_button_primary"]) { ?>

					<div class="userpro-form-actions-button request">
						<div class="userpro-form-actions-button-text">
							Request Secret Key
						</div>
					</div>

					<input type="submit" value="<?php echo $args["{$template}_button_primary"]; ?>" class="userpro-form-actions-button-hidden request userpro-button" />
				
				<?php } ?>
				
				<?php if ($args["{$template}_button_secondary"]) { ?>

					<div class="userpro-form-actions-button change">
						<div class="userpro-form-actions-button-text">
							Change Your Password
						</div>
					</div>

					<input type="button" value="<?php echo $args["{$template}_button_secondary"]; ?>" class="userpro-form-actions-button-hidden change userpro-button secondary" data-template="<?php echo $args["{$template}_button_action"]; ?>" />
				
				<?php } ?>

				<img src="<?php echo $userpro->skin_url(); ?>loading.gif" alt="" class="userpro-loading" />
				<div class="userpro-clear"></div>
				
			</div>
			<?php } ?>
		
		</form>
	
	</div>

</div>
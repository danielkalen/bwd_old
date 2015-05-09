<div class="userpro userpro-<?php echo $i; ?> userpro-<?php echo $layout; ?>" <?php userpro_args_to_data( $args ); ?>>

	<a href="#" class="userpro-close-popup"><?php _e('Close','userpro'); ?></a>
	
	<div class="userpro-head">
		<div class="userpro-heading">
			<div class="userpro-left"><?php echo $args["{$template}_heading"]; ?></div>
			<?php if ($args["{$template}_side"]) { ?>
			<div class="userpro-right"><a href="#" data-template="<?php echo $args["{$template}_side_action"]; ?>"><?php echo $args["{$template}_side"]; ?></a></div>
			<?php } ?>
			<div class="dk-clearfix"></div>
		</div>
	</div>
	
	<div class="userpro-body">
	
		<?php do_action('userpro_pre_form_message'); ?>

		<form action="" method="post" data-action="<?php echo $template; ?>">
		
			<?php // Hook into fields $args, $user_id
			if (!isset($user_id)) $user_id = 0;
			$hook_args = array_merge($args, array('user_id' => $user_id, 'unique_id' => $i));
			do_action('userpro_before_fields', $hook_args);
			?>
		
			<!-- fields -->
			<div class='userpro-field' data-key='secretkey'>
				<div class='userpro-input'>
					<input type='text' placeholder="Your Secret Key" name='secretkey-<?php echo $i; ?>' id='secretkey-<?php echo $i; ?>' data-required="1" data-ajaxcheck="validatesecretkey" />
					<div class='userpro-help'><?php _e('You need a secret key to change your account password. Do not have one? Click <a href="#" data-template="reset">here</a> to obtain a new key.','userpro'); ?></div>
					<div class='userpro-clear'></div>
				</div>
			</div><div class='userpro-clear'></div>

			<div class="userpro-field userpro-field-user_pass" data-key="user_pass">
				<div class="userpro-input">
					<input name="user_pass-<?php echo $i; ?>" id="user_pass-<?php echo $i; ?>" placeholder="New Password" autocomplete="off" data-_builtin="1" data-type="password" data-label="Password" data-help="Your password must be 8 characters long at least." type="password">
				</div>
			</div>

			<div class="userpro-field userpro-field-user_pass_confirm" data-key="user_pass">
				<div class="userpro-input">
					<input name="user_pass_confirm-<?php echo $i; ?>" id="user_pass_confirm-<?php echo $i; ?>" placeholder="Confirm New Password" autocomplete="off" data-_builtin="1" data-type="password" data-label="Confirm your Password" type="password">
				</div>
			</div>

			<div class="dk-clearfix"></div>

			
			
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
			<div class="userpro-field userpro-submit userpro-column">
				
				<?php if ($args["{$template}_button_primary"]) { ?>
				<input type="submit" value="Change Password" class="userpro-button" />
				<?php } ?>
				
				<?php if ($args["{$template}_button_secondary"]) { ?>
				<input type="button" value="Request Secret Key" class="userpro-button secondary" data-template="<?php echo $args["{$template}_button_action"]; ?>" />
				<?php } ?>

				<img src="<?php echo $userpro->skin_url(); ?>loading.gif" alt="" class="userpro-loading" />
				<div class="userpro-clear"></div>
				
			</div>
			<?php } ?>
		
		</form>
	
	</div>

</div>
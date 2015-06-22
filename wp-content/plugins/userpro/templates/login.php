<div class="login userpro userpro-<?php echo $i; ?> userpro-<?php echo $layout; ?>" <?php userpro_args_to_data( $args ); ?>>
	
	<div class="userpro-title userpro-head">
		<div class="userpro-title-text"><?php echo $args["{$template}_heading"]; ?></div>
	</div>
	
	<div class="userpro-wrap userpro-body">
	
		<?php do_action('userpro_pre_form_message'); ?>

		<div class="userpro-intro">Sign in to view your order history, track orders, and edit default account details.</div>
		
		<form class="userpro-form" action="" method="post" data-action="<?php echo $template; ?>">
		
			<?php do_action('userpro_super_get_redirect', $i); ?>
			
			<input type="hidden" name="force_redirect_uri-<?php echo $i; ?>" id="force_redirect_uri-<?php echo $i; ?>" value="<?php if (isset( $args["force_redirect_uri"] ) ) echo $args["force_redirect_uri"]; ?>" />
			<input type="hidden" name="redirect_uri-<?php echo $i; ?>" id="redirect_uri-<?php echo $i; ?>" value="<?php if (isset( $args["{$template}_redirect"] ) ) echo $args["{$template}_redirect"]; ?>" />
			
			<?php // Hook into fields $args, $user_id
			if (!isset($user_id)) $user_id = 0;
			$hook_args = array_merge($args, array('user_id' => $user_id, 'unique_id' => $i));
			do_action('userpro_before_fields', $hook_args);
			?>
		
			<?php foreach( userpro_fields_group_by_template( $template, $args["{$template}_group"] ) as $key => $array ) { ?>
				
				<?php  if ($array) echo userpro_edit_field( $key, $array, $i, $args ) ?>
				
			<?php } ?>
			
			<?php // Hook into fields $args, $user_id
			if (!isset($user_id)) $user_id = 0;
			$hook_args = array_merge($args, array('user_id' => $user_id, 'unique_id' => $i));
			do_action('userpro_after_fields', $hook_args);
			?>
									
			<?php if ($args["{$template}_button_primary"] ||  $args["{$template}_button_secondary"] ) { ?>
			<div class="userpro-form-actions userpro-field userpro-submit userpro-column">

				<?php // Hook into fields $args, $user_id
				if (!isset($user_id)) $user_id = 0;
				$hook_args = array_merge($args, array('user_id' => $user_id, 'unique_id' => $i));
				do_action('userpro_before_form_submit', $hook_args);
				?>
				
				<div class="userpro-form-actions-button">
					<div class="userpro-form-actions-button-text">
						Sign In
					</div>
				</div>

				<?php if ($args["{$template}_button_primary"]) { ?>
				<input type="submit" value="<?php echo $args["{$template}_button_primary"]; ?>" class="userpro-form-actions-button-hidden userpro-button" />
				<?php } ?>
				
							
				<?php // Hook into fields $args, $user_id
				if (!isset($user_id)) $user_id = 0;
				$hook_args = array_merge($args, array('user_id' => $user_id, 'unique_id' => $i));
				do_action('userpro_inside_form_submit', $hook_args);
				?>


				<img src="<?php echo $userpro->skin_url(); ?>loading.gif" alt="" class="userpro-loading" />
				
			</div>

			<?php if ($args["{$template}_side"]) { ?>
				<div class="userpro-form-forget userpro-forget"><a class="userpro-form-forget-link" href="#" data-template="<?php echo $args["{$template}_side_action"]; ?>"><?php echo $args["{$template}_side"]; ?></a></div>
			<?php } ?>

			<?php } ?>
		
		</form>
	
	</div>

</div>
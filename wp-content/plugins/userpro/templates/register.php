<div class="register userpro userpro-<?php echo $i; ?> userpro-<?php echo $layout; ?>" <?php userpro_args_to_data( $args ); ?>>

	<div class="userpro-title userpro-head">
		<div class="userpro-title-text"><?php echo $args["{$template}_heading"]; ?></div>
	</div>


	<div class="userpro-wrap userpro-body">
	
		<?php do_action('userpro_pre_form_message'); ?>

		<div class="userpro-intro">Register an account with Shopperbarn manually or via social logins to receive exclusive perks.</div>

		<form class="userpro-form" action="" method="post" data-action="<?php echo $template; ?>">
		
			<input type="hidden" name="redirect_uri-<?php echo $i; ?>" id="redirect_uri-<?php echo $i; ?>" value="<?php if (isset( $args["{$template}_redirect"] ) ) echo $args["{$template}_redirect"]; ?>" />
			
			<?php // Hook into fields $args, $user_id
			if (!isset($user_id)) $user_id = 0;
			$hook_args = array_merge($args, array('user_id' => $user_id, 'unique_id' => $i));
			do_action('userpro_before_fields', $hook_args);
			?>
			
			<?php
			// Multiple Registration Forms Support
			if (isset($args['type']) && $userpro->multi_type_exists($args['type'])) {
				$group = array_intersect_key( userpro_fields_group_by_template( $template, $args["{$template}_group"] ), array_flip($userpro->multi_type_get_array($args['type'])) );
			} else {
				$group = userpro_fields_group_by_template( $template, $args["{$template}_group"] );
			}
			?>
		
			<?php foreach( $group as $key => $array ) { ?>
				
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
						Create Account
					</div>
				</div>

				<?php // Hook into fields $args, $user_id
				if (!isset($user_id)) $user_id = 0;
				$hook_args = array_merge($args, array('user_id' => $user_id, 'unique_id' => $i));
				do_action('userpro_inside_form_submit', $hook_args);
				?>
				
				<?php if ($args["{$template}_button_primary"]) { ?>
				<input type="submit" value="<?php echo $args["{$template}_button_primary"]; ?>" class="userpro-form-actions-button-hidden userpro-button" />
				<?php } ?>
				

				<img src="<?php echo $userpro->skin_url(); ?>loading.gif" alt="" class="userpro-loading" />
				
			</div>
			<?php } ?>
		
		</form>
	
	</div>

</div>
<?php
/**
 * Login form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( is_user_logged_in() ) {
	return;
}

?>
<form method="post" class="login" <?php if ( $hidden ) echo 'style="display:none;"'; ?>>
	<h3 class="login-title">
		<div class="login-title-text">Sign in</div>
	</h3>
	<div class="login-wrap">
		<?php do_action( 'woocommerce_login_form_start' ); ?>

		<?php if ( $message ) echo wpautop( wptexturize( $message ) ); ?>

		<div class="login-fieldset fieldset input">
			<label class="login-fieldset-label" for="username">
				<?php _e( 'Email or Username', 'woocommerce' ); ?>
			</label>
			<input type="text" class="login-fieldset-input input input-text" name="username" id="username" placeholder="Email or Username" />
		</div>


		<div class="login-fieldset fieldset input">
			<label class="login-fieldset-label" for="password">
				<?php _e( 'Password', 'woocommerce' ); ?>
			</label>
			<input class="login-fieldset-input input input-text" type="password" name="password" id="password" placeholder="Password" />
		</div>


		<div class="clearfix"></div>

		<?php do_action( 'woocommerce_login_form' ); ?>

		<div class="login-button">
			<div class="login-button-text">Login</div>
		</div>

		<?php wp_nonce_field( 'woocommerce-login' ); ?>
		<input type="submit" class="login-button-hidden" name="login" value="<?php _e( 'Login', 'woocommerce' ); ?>" />
		<input type="hidden" name="redirect" value="<?php echo esc_url( $redirect ) ?>" />

		<div class="login-fieldset fieldset checkbox">
			<div class="login-fieldset-checkbox input-button">
				<div class="login-fieldset-checkbox-box"></div>
				<label for="rememberme" class="login-fieldset-checkbox-label"><?php _e( 'Remember me', 'woocommerce' ); ?></label>
				<input name="rememberme" type="checkbox" id="rememberme" value="forever" /> 
			</div>
		</div>

		<div class="login-fieldset forgot link">
			<a class="login-fieldset-link" href="<?php echo esc_url( wc_lostpassword_url() ); ?>"><?php _e( 'Lost your password?', 'woocommerce' ); ?></a>
		</div>



		<div class="clear"></div>

		<?php do_action( 'woocommerce_login_form_end' ); ?>
	</div>

</form>

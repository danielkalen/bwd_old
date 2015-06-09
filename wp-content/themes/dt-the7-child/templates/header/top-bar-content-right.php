<?php
// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }


////////////////////
// Social icons //
////////////////////

echo '<div class="social-icons">
			<a title="Twitter Icon" target="_blank" href="http://twitter.com/shopperbarn" id="twitter-icon" class="social-icon t-twitter"></a>
			<a title="Facebook Icon" target="_blank" href="http://facebook.com/shopperbarn" id="facebook-icon" class="social-icon t-facebook"></a>
			<a title="Pinterest Icon" target="_blank" href="http://pinterest.com/shopperbarn" id="pinterest-icon" class="social-icon t-pinterest"></a>
			<a title="Google+ Icon" target="_blank" href="http://plus.google.com/shopperbarn" id="googleplus-icon" class="social-icon t-gplus"></a>
			</div>';

////////////////////////
// My Account //
////////////////////////

echo '<div class="my-account"><a id="my-account" href="/account">My Account</a><div class="logout-box">'.do_shortcode("[userpro_loggedout]<a href='/account/login'><span>Login</span></a><a href='/account/register'><span>Register</span></a>[/userpro_loggedout][userpro_loggedin]<a href='/account'><span>View Account</span></a><a href='". wp_logout_url( home_url() ) ."'><span>Logout</span></a>[/userpro_loggedin]").'</div></div>';
////////////////////////
// Woocommerce cart //
////////////////////////

if ( dt_is_woocommerce_enabled() && of_get_option( 'general-woocommerce_show_mini_cart_in_top_bar', true ) ) {
	get_template_part('inc/mod-woocommerce/mod-woocommerce', 'mini-cart');
}


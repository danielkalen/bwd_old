<?php
// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }


////////////////////
// Social icons //
////////////////////

echo '<div class="soc-ico"><a title="Google+-icon" target="_blank" href="#" class="google-icon" style="visibility: visible;"><svg class="icon" viewBox="0 0 25 25"><use xlink:href="#google"></use></svg></a><a title="Pinterest-icon" target="_blank" href="#" class="pinterest-icon" style="visibility: visible;"><svg class="icon" viewBox="0 0 25 25"><use xlink:href="#pinterest"></use></svg></a><a title="Facebook-icon" target="_blank" href="#" class="facebook-icon" style="visibility: visible;"><svg class="icon" viewBox="0 0 25 25"><use xlink:href="#facebook"></use></svg></a><a title="Twitter-icon" target="_blank" href="#" class="twitter-icon" style="visibility: visible;"><svg class="icon" viewBox="0 0 25 25"><use xlink:href="#twitter"></use></svg></a></div>';

////////////////////////
// My Account //
////////////////////////

echo '<a id="my-account" href="/account">My Account</a>';

////////////////////////
// Woocommerce cart //
////////////////////////

if ( dt_is_woocommerce_enabled() && of_get_option( 'general-woocommerce_show_mini_cart_in_top_bar', true ) ) {
	get_template_part('inc/mod-woocommerce/mod-woocommerce', 'mini-cart');
}


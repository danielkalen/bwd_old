<?php
/**
 * Show messages
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! $messages ){
	return;
}

?>

<?php foreach ( $messages as $message ) : ?>
	<div class="notice notice_success">
		<div class="notice-text"><?php echo wp_kses_post( $message ); ?></div>
		<div class="notice-close"></div>
	</div>
<?php endforeach; ?>
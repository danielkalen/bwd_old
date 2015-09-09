<?php  if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Debug_Bar_Rewrite_Rules' ) ) :

class Debug_Bar_Rewrite_Rules extends Debug_Bar_Panel {
	public function init() {
		$this->title( __( 'Rewrite Rules', 'debug-bar' ) );
	}

	public function prerender() {
		$this->set_visible( true );
	}

	public function render() {
	$rules = get_option( 'rewrite_rules' );
?>
<div id="debug-bar-rewrite-rules">
<h3>Rewrite rules</h3>
<p><pre><?php var_dump($rules); ?></pre></p>
</div>
<?php
	}
}

endif;
?>

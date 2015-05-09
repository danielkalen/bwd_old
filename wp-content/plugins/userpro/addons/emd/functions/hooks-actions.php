<?php

	/* Enqueue Scripts */
	add_action('wp_enqueue_scripts', 'userpro_ed_enqueue_scripts', 99);
	function userpro_ed_enqueue_scripts(){
		if ( !is_front_page() && !is_cart() && !is_checkout() && !is_shop() && !is_product() && !is_product_category() ) {
		wp_register_script('userpro_ed', userpro_ed_url . 'scripts/userpro-emd.js');
		wp_enqueue_script('userpro_ed');
		}
	}
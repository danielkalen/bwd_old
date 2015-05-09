<?php

/**
 * A helper function used in multiple recipe templates
 *
 * @param string  $template template name
 */

	get_header( 'intense' );

	global $intense_custom_post, $intense_visions_options, $intense_post_types, $post;

	$padding_top = $intense_visions_options['intense_layout_row_default_padding']['padding-top'];
	$padding_bottom = $intense_visions_options['intense_layout_row_default_padding']['padding-bottom'];
	$margin_top = $intense_visions_options['intense_layout_row_default_margin']['margin-top'];
	$margin_bottom = $intense_visions_options['intense_layout_row_default_margin']['margin-bottom'];

	$layout_style = '';

	if ( !empty( $padding_top ) ) {
		$layout_style .= 'padding-top: ' . $padding_top . '; ';
	}

	if ( !empty( $padding_bottom ) ) {
		$layout_style .= 'padding-bottom: ' . $padding_bottom . '; ';
	}

	if ( !empty( $margin_top ) ) {
		$layout_style .= 'margin-top: ' . $margin_top . '; ';
	}

	if ( !empty( $margin_bottom ) ) {
		$layout_style .= 'margin-bottom: ' . $margin_bottom . '; ';
	}

	$no_layout_style = "padding-top: 0px; padding-bottom: 0px; margin-top: 0px; margin-bottom: 0px;";

	$intense_post_type = $intense_post_types->get_post_type( 'intense_portfolio' );

	$intense_custom_post = array(
		'plugin_layout_style' => $layout_style,
		'cancel_plugin_layout_style' => $no_layout_style,
		'post_type' => $intense_post_type,
		'taxonomy' => '',
		'template' => '',
		'categories' => '',
		'exclude_categories' => '',
		'exclude_post_ids' => '',
		'authors' => '',
		'order_by' => '',
		'order' => '',
		'posts_per_page' => '',
		'image_size' => 'Full',
		'image_shadow' => '0',
		'hover_effect' => '',
		'show_all' => '',
		'show_meta' => '0',
		'show_author' => '0',
		'infinite_scroll' => '',
		'show_filter' => '',
		'show_images' => '1',
		'show_missing_image' => '0',
		'show_social_sharing' => '1',
		'timeline_mode' => '',
		'timeline_showyear' => '',
		'timeline_readmore' => '',
		'timeline_color' => '',
		'filter_easing' => '',
		'filter_effects' => '',
		'hover_effect_color' => '',
		'hover_effect_opacity' => '',
		'sticky_mode' => '',
		'image_border_radius' => '',
		'timeline_border_radius' => '',
		'animation_type' => '',
		'animation_trigger' => null ,
		'animation_scroll_percent' => null,
		'animation_delay' => null,
		'animation_wrapper_start' => null,
		'animation_wrapper_end' => null,
		'is_slider' => '',
		'rtl' => $intense_visions_options['intense_rtl']
	);

	$i = 0;

	do_action( 'intense_before_main_content' );

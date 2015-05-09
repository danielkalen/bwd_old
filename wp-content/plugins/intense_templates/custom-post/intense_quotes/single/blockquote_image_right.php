<?php
/*
Intense Template Name: Blockquote (Image Right)
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

$intense_post_type = $intense_post_types->get_post_type( 'intense_quotes' );

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
	'image_size' => 'medium800',
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

while ( have_posts() ) : the_post();
	$item_classes = '';
	$intense_custom_post['image_shadow'] = '';
	$intense_custom_post['hover_effect'] = '';
	$intense_custom_post['hover_effect_color'] = '';
	$intense_custom_post['hover_effect_opacity'] = '';
	$intense_custom_post['index'] = $i;

	if ( has_post_thumbnail() ) {
		if ( get_field( 'intense_image_shadow' ) != '' ) {
			$intense_custom_post['image_shadow'] = get_field( 'intense_image_shadow' );
		}

		if ( get_field( 'intense_hover_effect' ) != '' ) {
			$intense_custom_post['hover_effect'] = get_field( 'intense_hover_effect' );
		}

		if ( get_field( 'intense_effect_color' ) != '' ) {
			$intense_custom_post['hover_effect_color'] = get_field( 'intense_effect_color' );
		}

		if ( get_field( 'intense_effect_opacity' ) != '' ) {
			$intense_custom_post['hover_effect_opacity'] = get_field( 'intense_effect_opacity' );
		}
	}

	$intense_custom_post['post_classes'] = $item_classes;
?>

<article class='intense col-lg-12 col-md-12 col-sm-12 col-xs-12 <?php echo $intense_custom_post['post_classes']; ?> intense_post nogutter' style='<?php echo $intense_custom_post['plugin_layout_style']; ?>' id='post-<?php echo $post->ID; ?>'>
	<!-- Head -->
	<?php echo $intense_custom_post['animation_wrapper_start']; ?>

	<div class='intense row' style='<?php echo $intense_custom_post['cancel_plugin_layout_style']; ?>'>
		<div class='intense col-lg-7 col-md-7 col-sm-7 col-xs-12' style='padding:5px;'>
			<?php echo intense_run_shortcode( 'intense_blockquote', array( 
			          'author' => get_field( 'intense_quote_author' )
			        ), get_the_content() ); ?>
		</div>
		<div class='intense col-lg-5 col-md-5 col-sm-5 col-xs-12' style='padding:5px;'>
			<?php if ( $intense_custom_post['show_images'] ) { 
				echo intense_get_post_thumbnails( 
					( isset( $intense_custom_post['image_size'] ) ? $intense_custom_post['image_size'] : 'medium800' ), 
					null, 
					false, 
					( $intense_custom_post['show_missing_image'] == 0 ? false : true ), 
					$intense_custom_post['image_shadow'], 
					$intense_custom_post['hover_effect'], 
					$intense_custom_post['hover_effect_color'], 
					$intense_custom_post['hover_effect_opacity'],
					$intense_custom_post['image_border_radius'],
					true );
			} ?>
		</div>
	</div>

	<!-- Footer -->
	<footer style='padding-top: 5px;'>
		<div class='intense row' style='<?php echo $intense_custom_post['cancel_plugin_layout_style']; ?> padding-top: 5px;'>

		</div>
	</footer>
	<?php echo $intense_custom_post['animation_wrapper_end']; ?>
</article>

<?php

	$i++;
endwhile;

do_action( 'intense_after_main_content' );

get_footer( 'intense' );

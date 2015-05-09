<?php
/*
Intense Template Name: One Column
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

$intense_post_type = $intense_post_types->get_post_type( 'intense_team' );

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
	'image_size' => 'postExtraWide',
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
	$intense_custom_post['post_classes'] = $item_classes;
?>

<article class='intense col-lg-12 col-md-12 col-sm-12 col-xs-12 <?php echo $intense_custom_post['post_classes']; ?> intense_post nogutter' style='<?php echo $intense_custom_post['plugin_layout_style']; ?>' id='post-<?php echo $post->ID; ?>'>
	<!-- Head -->
	<?php echo $intense_custom_post['animation_wrapper_start']; ?>

	<div class='intense row' style='<?php echo $intense_custom_post['cancel_plugin_layout_style']; ?>'>
		<div class='intense col-lg-12 col-md-12 col-sm-12 col-xs-12'>
			<?php 
				$imagesrc = intense_get_post_thumbnail_src( 'large1600', true, false );
   
  				if ( isset( $imagesrc ) ) {
					echo intense_run_shortcode( 'intense_content_section', array(
						'background_type' => 'image',
						'image' => $imagesrc[0],
						'imagesize' => 'large1600',
						'imagemode' => 'fixed',
						'height' => '250'
					) );
				}
			?>
		</div>
		<div class='intense col-lg-12 col-md-12 col-sm-12 col-xs-12' style='text-align:center; margin-top:-75px;'>
			<?php
				$image = get_field( 'intense_member_photo' );

				if( !empty( $image ) ) {

					echo intense_run_shortcode( 'intense_image', array( 
						'image' => $image["id"],
			        	'size' => 'square150',
			        	'border_radius' => '50%'
			        ) );
			    }
			?>
		</div>
		<div class='intense col-lg-12 col-md-12 col-sm-12 col-xs-12' style='padding-top:15px;'>
			<div style='text-align: center;'><h1><?php echo the_title_attribute( 'echo=0' ); ?></h1></div>
			<div style='text-align: center;'><h4><?php echo get_field( 'intense_member_title' ); ?></h4></div>
			<div style='text-align: center;'>
				
				<?php
					if( get_field( 'intense_member_facebook' ) ) {
						echo intense_run_shortcode( 'intense_social_icon', array( 
							'mode' => 'fontawesome',
							'type' => 'facebook',
							'link' => get_field( 'intense_member_facebook' ),
							'link_target' => '_blank',
							'size' => '20',
							'color' => 'primary'
						) );
					}

					if( get_field( 'intense_member_googleplus' ) ) {
						echo intense_run_shortcode( 'intense_social_icon', array( 
							'mode' => 'fontawesome',
							'type' => 'google-plus',
							'link' => get_field( 'intense_member_googleplus' ),
							'link_target' => '_blank',
							'size' => '20',
							'color' => 'primary'
						) );
					}

					if( get_field( 'intense_member_twitter' ) ) {
						echo intense_run_shortcode( 'intense_social_icon', array( 
							'mode' => 'fontawesome',
							'type' => 'twitter',
							'link' => get_field( 'intense_member_twitter' ),
							'link_target' => '_blank',
							'size' => '20',
							'color' => 'primary'
						) );
					}

					if( get_field( 'intense_member_dribbble' ) ) {
						echo intense_run_shortcode( 'intense_social_icon', array( 
							'mode' => 'fontawesome',
							'type' => 'dribbble',
							'link' => get_field( 'intense_member_dribbble' ),
							'link_target' => '_blank',
							'size' => '20',
							'color' => 'primary'
						) );
					}

					if( get_field( 'intense_member_linkedin' ) ) {
						echo intense_run_shortcode( 'intense_social_icon', array( 
							'mode' => 'fontawesome',
							'type' => 'linkedin',
							'link' => get_field( 'intense_member_linkedin' ),
							'link_target' => '_blank',
							'size' => '20',
							'color' => 'primary'
						) );
					}

					$socialImage = get_field( 'intense_member_custom_social_icon' );

					if( !empty( $socialImage ) ) {
						echo intense_run_shortcode( 'intense_social_icon', array( 
							'mode' => 'custom',
							'image' => $socialImage["id"],
							'link' => get_field( 'intense_member_custom_social_link' ),
							'link_target' => '_blank',
							'size' => '20',
							'color' => 'primary'
						) );
				    }

					if( get_field( 'intense_member_custom_social_icon' ) ) {
						echo intense_run_shortcode( 'intense_social_icon', array( 
							'mode' => 'custom',
							'image' => get_field( 'intense_member_facebook' ),
							'link' => get_field( 'intense_member_custom_social_link' ),
							'link_target' => '_blank',
							'size' => '20',
							'color' => 'primary'
						) );
					}
				?>

			</div>
			<div style='padding-top:30px;'>
				<?php echo do_shortcode( get_the_content() ); ?>
			</div>
			<?php

			?>
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

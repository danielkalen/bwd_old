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

$intense_post_type = $intense_post_types->get_post_type( 'intense_jobs' );

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
	'image_size' => 'medium600',
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
		<div class='intense col-lg-12 col-md-12 col-sm-12 col-xs-12'>
			<div class='post-header' style='padding-bottom:10px;'>
				<h1 class='entry-title'>
					<?php echo the_title_attribute( 'echo=0' ); ?>
				</h1>
			</div>
		</div>
	</div>

	<div class='intense row' style='<?php echo $intense_custom_post['cancel_plugin_layout_style']; ?>'>
		<div class='intense col-lg-8 col-md-8 col-sm-8 col-xs-12'>
			<?php echo do_shortcode( get_the_content() ); 

			if ( get_field( 'intense_job_qualifications' ) != '' ) { ?>
				<div class='intense row' style='<?php echo $intense_custom_post['cancel_plugin_layout_style']; ?>'>
					<div class='intense col-lg-12 col-md-12 col-sm-12 col-xs-12'>
					<?php echo '<h3 style="margin:5px 0;">' . __( 'Qualifications', 'intense' ) . '</h3>' . get_field( 'intense_job_qualifications' ); ?>
					</div>
				</div>
			<?php }

			if ( get_field( 'intense_job_responsibilities' ) != '' ) { ?>
				<div class='intense row' style='<?php echo $intense_custom_post['cancel_plugin_layout_style']; ?>'>
					<div class='intense col-lg-12 col-md-12 col-sm-12 col-xs-12'>
					<?php echo '<h3 style="margin:5px 0;">' . __( 'Responsibilities', 'intense' ) . '</h3>' . get_field( 'intense_job_responsibilities' ); ?>
					</div>
				</div>
			<?php }

			if ( get_field( 'intense_job_competencies' ) != '' ) { ?>
				<div class='intense row' style='<?php echo $intense_custom_post['cancel_plugin_layout_style']; ?>'>
					<div class='intense col-lg-12 col-md-12 col-sm-12 col-xs-12'>
					<?php echo '<h3 style="margin:5px 0;">' . __( 'Competencies', 'intense' ) . '</h3>' . get_field( 'intense_job_competencies' ); ?>
					</div>
				</div>
			<?php }

			if ( get_field( 'intense_job_compensation' ) != '' ) { ?>
				<div class='intense row' style='<?php echo $intense_custom_post['cancel_plugin_layout_style']; ?>'>
					<div class='intense col-lg-12 col-md-12 col-sm-12 col-xs-12'>
					<?php echo '<h3 style="margin:5px 0;">' . __( 'Compensation', 'intense' ) . '</h3>' . get_field( 'intense_job_compensation' ); ?>
					</div>
				</div>
			<?php }


			?>
		</div>
		<div class='intense col-lg-4 col-md-4 col-sm-4 col-xs-12'>
			<?php if ( $intense_custom_post['show_images'] ) { 
				echo intense_get_post_thumbnails( 
					( isset( $intense_custom_post['image_size'] ) ? $intense_custom_post['image_size'] : 'postWide' ), 
					null, 
					false, 
					( $intense_custom_post['show_missing_image'] == 0 ? false : true ), 
					$intense_custom_post['image_shadow'], 
					$intense_custom_post['hover_effect'], 
					$intense_custom_post['hover_effect_color'], 
					$intense_custom_post['hover_effect_opacity'],
					$intense_custom_post['image_border_radius'],
					true );
			} 

			if ( get_field( 'intense_job_post_date' ) != '' ) {
				$postdate = date("M d, Y", strtotime( get_field( 'intense_job_post_date' ) ) );

				echo '<h5 style="margin:5px 0;">Post Date: ' . $postdate . '</h5>';
			}

			if ( get_field( 'intense_job_expire_date' ) != '' ) {
				$expiredate = date( "M d, Y", strtotime( get_field( 'intense_job_expire_date' ) ) );

				echo '<h5 style="margin:5px 0;">Expire Date: ' . $expiredate . '</h5>';
			}

			if ( get_field( 'intense_job_status' ) != '' ) {
				echo '<h5 style="margin:3px 0;">Job Status: ' . get_field( 'intense_job_status' ) . '</h5>';
			}

			if ( get_field( 'intense_job_link' ) != '' ) {
				echo '<h5 style="margin:5px 0;">Job Link: ' . get_field( 'intense_job_link' ) . '</h5>';
			}

			echo '<hr>';
			
			$image = get_field( 'intense_job_company_logo' );

			if( !empty( $image ) ) {
				echo "<img style='max-width:250px; padding-top:20px;' src='" .  $image["url"] . "' title='" . the_title_attribute( 'echo=0' ) . "' alt='' />";
			}

			if ( get_field( 'intense_job_company' ) != '' ) {
				if ( get_field( 'intense_job_company_website' ) != '' ) {
					echo '<a href="' . get_field( 'intense_job_company_website' ) . '" target="_blank">';
				}

				echo '<h4 style="margin:5px 0;">' . get_field( 'intense_job_company' ) . '</h4>';

				if ( get_field( 'intense_job_company_website' ) != '' ) {
					echo '</a>';
				}
			}

			if ( get_field( 'intense_job_status' ) != '' ) {
				echo '<h5 style="margin:5px 0;"><em>' . get_field( 'intense_job_tagline' ) . '</em></h5>';
			}

			if ( get_field( 'intense_job_location' ) != '' ) {
				echo get_field( 'intense_job_location' ) . '<br />';
			}

			if ( get_field( 'intense_job_contact' ) != '' ) {
				echo '<hr><h4 style="margin:5px 0;">' . get_field( 'intense_job_contact' ) . '</h4>';
			}

			if ( get_field( 'intense_job_contact_email' ) != '' ) {
				echo '<a href="mailto:' . get_field( 'intense_job_contact_email' ) . '">' . get_field( 'intense_job_contact_email' ) . '</a><br />';
			}

			if ( get_field( 'intense_job_contact_phone' ) != '' ) {
				echo get_field( 'intense_job_contact_phone' );
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

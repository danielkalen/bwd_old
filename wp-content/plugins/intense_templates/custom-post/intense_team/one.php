<?php
/*
Intense Template Name: One Column
*/

global $post, $intense_custom_post, $intense_visions_options;

$intense_post_type = $intense_custom_post['post_type'];
?>
<article class='intense col-lg-12 col-md-12 col-sm-12 col-xs-12 <?php echo $intense_custom_post['post_classes']; ?> intense_post nogutter' style='<?php echo $intense_custom_post['plugin_layout_style']; ?>' id='post-<?php echo $post->ID; ?>'>
	<!-- Head -->
	<?php echo $intense_custom_post['animation_wrapper_start']; ?>
<div class='intense row' style='<?php echo $intense_custom_post['cancel_plugin_layout_style']; ?>'>
		<div class='intense col-lg-12 col-md-12 col-sm-12 col-xs-12' style="min-height:75px;">
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
				<?php echo $intense_post_type->get_excerpt( 120 ); ?>
			</div>
			<?php

			?>
		</div>
	</div>
	<?php echo $intense_custom_post['animation_wrapper_end']; ?>
</article>

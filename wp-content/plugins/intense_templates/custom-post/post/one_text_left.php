<?php
/*
Intense Template Name: One Column (text left)
*/

global $post, $intense_custom_post, $intense_visions_options;

$intense_post_type = $intense_custom_post['post_type'];
?>
<article class='intense row <?php echo $intense_custom_post['post_classes']; ?> intense_post nogutter' style='<?php echo $intense_custom_post['plugin_layout_style']; ?>' id='post-<?php echo $post->ID; ?>'>
	<!-- Image -->
	<?php echo $intense_custom_post['animation_wrapper_start']; ?>
	<div class='intense col-lg-7 col-md-7 col-sm-7 col-xs-7 pull-right' style="margin-left: 10px;">
		<a href='<?php echo get_permalink(); ?>' title='<?php  _e( "Permalink to", "intense" ); ?>  <?php echo the_title_attribute( 'echo=0' ); ?>' rel='bookmark'>
			<?php echo intense_get_post_thumbnails( 
				( isset( $intense_custom_post['image_size'] ) ? $intense_custom_post['image_size'] : 'medium640' ), 
				null, 
				false, 
				( $intense_custom_post['show_missing_image'] == 0 ? false : true ), 
				$intense_custom_post['image_shadow'], 
				$intense_custom_post['hover_effect'], 
				$intense_custom_post['hover_effect_color'], 
				$intense_custom_post['hover_effect_opacity'],
				$intense_custom_post['image_border_radius'],
				true ); ?>	
		</a>
	</div>

	<!-- Head -->
	<div class='post-header'>
		<h2 class='entry-title'>
			<a href='<?php echo get_permalink(); ?>' title='<?php  _e( "Permalink to", "intense" ); ?>  <?php echo the_title_attribute( 'echo=0' ); ?>' rel='bookmark'>
				<?php echo the_title_attribute( 'echo=0' ); ?>
			</a> <?php echo $intense_custom_post['edit_link']; ?>
		</h2>
		<?php
			if ( $intense_post_type->get_subtitle() != '' ) {
				echo '<h4>' . $intense_post_type->get_subtitle() . '</h4>';
			}
		?>
	</div>
	<?php if ( $intense_custom_post['show_meta'] ) { ?>
		<div class='entry-meta'>
			<?php 
			if ( !$intense_custom_post['rtl'] ) {
				echo intense_return_posted_on();
			} else {
				echo intense_return_posted_on_rtl();
			}
			?>
		</div>
	<?php } ?>

	<!-- Content -->	
	<div class='entry-content'>
		<?php 
			if ( is_object( $intense_post_type ) ) {
				echo $intense_post_type->get_content( 45 );
			} else {
				echo intense_content( 45 );
			}
		?>
	</div>
	<div class="clearfix"></div>

	<!-- Footer -->
	<footer style='padding-top: 5px;'>
		<?php if ( $intense_custom_post['show_social_sharing'] ) { ?>
			<span class='pull-left'>
				<?php 
					$output_shortcode = '';
					$output_shortcode .= '[intense_social_share share_url="http://intensevisions.com" ';
					$output_shortcode .= ( $intense_visions_options['intense_social_facebook_use'] == 1 ? 'show_facebook="' . $intense_visions_options['intense_social_facebook_use'] . '" facebook_button="' . $intense_visions_options['intense_social_facebook_layout'] . '" facebook_faces="' . $intense_visions_options['intense_social_facebook_faces'] . '" facebook_share="' . $intense_visions_options['intense_social_facebook_share'] . '" ' : '' );
					$output_shortcode .= ( $intense_visions_options['intense_social_google_plus_use'] == 1 ? 'show_googleplus="' . $intense_visions_options['intense_social_google_plus_use'] . '" googleplus_button="' . $intense_visions_options['intense_social_google_plus_layout'] . '" ' : '' );
					$output_shortcode .= ( $intense_visions_options['intense_social_linkedin_use'] == 1 ? 'show_linkedin="' . $intense_visions_options['intense_social_linkedin_use'] . '" linkedin_button="' . $intense_visions_options['intense_social_linkedin_layout'] . '" ' : '' );
					$output_shortcode .= ( $intense_visions_options['intense_social_stumbleupon_use'] == 1 ? 'show_stumbleupon="' . $intense_visions_options['intense_social_stumbleupon_use'] . '" stumbleupon_button="' . $intense_visions_options['intense_social_stumbleupon_layout'] . '" ' : '' );
					$output_shortcode .= ( $intense_visions_options['intense_social_twitter_use'] == 1 ? 'show_twitter="' . $intense_visions_options['intense_social_twitter_use'] . '" twitter_button="' . $intense_visions_options['intense_social_twitter_layout'] . '" ' : '' );
					$output_shortcode .= ( $intense_visions_options['intense_social_pinterest_use'] == 1 ? 'show_pinterest="' . $intense_visions_options['intense_social_pinterest_use'] . '" pinterest_image="' . get_permalink() . '" pinterest_button="' . $intense_visions_options['intense_social_pinterest_layout'] . '"' : '' );
					$output_shortcode .= '[/intense_social_share]';
					echo do_shortcode( $output_shortcode );
				 ?>
			</span>
		<?php } ?>
		<span class='read-more pull-right'>
		<?php echo intense_run_shortcode( 'intense_button', array( 'size' => 'mini', 'color' => 'primary', 'link' => get_permalink(), 'icon' => 'angle-right', 'icon_position' => 'right' ), $intense_custom_post['timeline_readmore'] ); ?>			
		</span>
	</footer>
	<?php echo $intense_custom_post['animation_wrapper_end']; ?>
</article>

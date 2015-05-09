<?php
/*
Intense Template Name: Two Columns (text)
*/

global $post, $intense_custom_post, $intense_visions_options;

$inline = ( is_sticky() && 'inline' == $intense_custom_post['sticky_mode'] );

if ( $inline ) {
	$span = "12";
	$size = "postWide";
	$header_tag = "h3";
} else {
	$span = "6";
	$size = ( isset( $intense_custom_post['image_size'] ) ? $intense_custom_post['image_size'] : 'medium800' );
	$header_tag = "h3";
}

$padding_bottom = $intense_visions_options['intense_layout_row_default_padding']['padding-top'];
$intense_post_type = $intense_custom_post['post_type'];
?>
<div class='intense col-lg-<?php echo $span; ?> col-md-<?php echo $span; ?> col-sm-12 col-xs-12 <?php echo $intense_custom_post['post_classes']; ?> intense_post nogutter' style='margin-left: 0px; float: none; padding: 0 10px; padding-bottom: <?php echo $padding_bottom ?>; display:inline-block; vertical-align: top;'>
	<article id='post-<?php echo $post->ID; ?>' class='<?php echo ( $inline ?  'featured ' : '' ); ?>' style='position:relative;'>
	<?php echo $intense_custom_post['animation_wrapper_start']; ?>
		<?php if ( $intense_custom_post['show_images'] ) { ?>
			<div class='intense row' style='<?php echo $intense_custom_post['cancel_plugin_layout_style']; ?>'>


		<div class='intense row col-lg-12 col-md-12 col-sm-12 col-xs-12' style='margin:0; margin-left: 15px; padding: 10px 0 0 0; background-color: rgba(0,0,0,0.6); color: #e8e8e8; box-shadow: 0 5px 2px rgba(0,0,0,0.2); position:absolute; z-index:1; text-align: center;'>
			<div class='entry-content intense col-lg-4 col-md-4 col-sm-12 col-xs-12' style='color: #e8e8e8;'>
				<label><strong><?php echo __('Prep', 'intense' ) ?></strong><h4 style='color: #e8e8e8; margin: 5px;'><?php echo intense_convert_minutes_to_hours( get_field('intense_recipe_prep_time') ); ?></h4></label>
			</div>
			<div class='entry-content intense col-lg-4 col-md-4 col-sm-12 col-xs-12' style='color: #e8e8e8;'>
				<label><strong><?php echo __('Cook', 'intense' ) ?></strong><h4 style='color: #e8e8e8; margin: 5px;'><?php echo intense_convert_minutes_to_hours( get_field('intense_recipe_cook_time') ); ?></h4></label>
			</div>
			<div class='entry-content intense col-lg-4 col-md-4 col-sm-12 col-xs-12' style='color: #e8e8e8;'>
				<label><strong><?php echo __('Ready', 'intense' ) ?></strong><h4 style='color: #e8e8e8; margin: 5px;'><?php echo intense_convert_minutes_to_hours( ( get_field('intense_recipe_prep_time') + get_field('intense_recipe_cook_time') ) ); ?></h4></label>
			</div>
		</div>				

				<div class='intense col-lg-12 col-md-12 col-sm-12 col-xs-12' style='position: relative;'>
					 <a href='<?php echo get_permalink(); ?>' title='<?php  _e( "Permalink to", "intense" ); ?>  <?php echo the_title_attribute( 'echo=0' ); ?>' rel='bookmark'><?php echo intense_get_post_thumbnails( 
						$size, 
						null, 
						false, 
						( $intense_custom_post['show_missing_image'] == 0 ? false : true ), 
						$intense_custom_post['image_shadow'], 
						$intense_custom_post['hover_effect'], 
						$intense_custom_post['hover_effect_color'], 
						$intense_custom_post['hover_effect_opacity'],
						$intense_custom_post['image_border_radius'],
						true ); 
					?></a>
				</div>
			</div>
		<?php } ?>	

		<<?php echo $header_tag; ?> class='entry-title'><a href='<?php echo get_permalink(); ?>' title='<?php  _e( "Permalink to", "intense" ); ?>  <?php echo the_title_attribute( 'echo=0' ); ?>' rel='bookmark'><?php echo the_title_attribute( 'echo=0' )?></a></<?php echo $header_tag; ?>>
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
		<div class='entry-content'>
			<?php echo $intense_post_type->get_excerpt( 40 ); ?>

			<?php
			$cuisine = get_the_term_list( $post->ID, 'intense_recipes_cuisines', '', ', ', '' );
			$course = get_the_term_list( $post->ID, 'intense_recipes_courses', '', ', ', '' );
			$skill_level = get_the_term_list( $post->ID, 'intense_recipes_skill_levels', '', ', ', '' );
			?>
			<!-- Footer -->
			<footer style='padding-top: 5px;'>
				<div class='intense row' style='<?php echo $intense_custom_post['cancel_plugin_layout_style']; ?> padding-top: 5px;'>
					<div class='entry-content intense col-lg-4 col-md-4 col-sm-12 col-xs-12'>
						<label><strong><?php echo __('Cuisine', 'intense' ) ?>:</strong> <?php echo $cuisine; ?></label>
					</div>
					<div class='entry-content intense col-lg-4 col-md-4 col-sm-12 col-xs-12'>
						<label><strong><?php echo __('Course', 'intense' ) ?>: </strong><?php echo $course; ?></label>
					</div>
					<div class='entry-content intense col-lg-4 col-md-4 col-sm-12 col-xs-12'>
						<label><strong><?php echo __('Skill Level', 'intense' ) ?>: </strong><?php echo $skill_level; ?></label>
					</div>
				</div>
			</footer>


			<?php if ( $intense_custom_post['show_social_sharing'] ) { ?>
				<br /><span style="padding-top:10px;">
					<?php 
						$output_shortcode = '';
						$output_shortcode .= '[intense_social_share share_url="http://intensevisions.com" ';
						$output_shortcode .= ( $intense_visions_options['intense_social_facebook_use'] == 1 ? 'show_facebook="' . $intense_visions_options['intense_social_facebook_use'] . '" facebook_button="' . $intense_visions_options['intense_social_facebook_layout'] . '" facebook_faces="0" facebook_share="' . $intense_visions_options['intense_social_facebook_share'] . '" ' : '' );
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
		</div>
	<?php echo $intense_custom_post['animation_wrapper_end']; ?>
	</article>
</div>
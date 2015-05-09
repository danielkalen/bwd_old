<?php
/*
Intense Template Name: One Column (text right)
*/

global $post, $intense_custom_post;

$intense_post_type = $intense_custom_post['post_type'];
?>

<article class='intense col-lg-12 col-md-12 col-sm-12 col-xs-12 <?php echo $intense_custom_post['post_classes']; ?> intense_post nogutter' style='<?php echo $intense_custom_post['plugin_layout_style']; ?>' id='post-<?php echo $post->ID; ?>'>
	<!-- Head -->
	<?php echo $intense_custom_post['animation_wrapper_start']; ?>

	<div class='intense row' style='<?php echo $intense_custom_post['cancel_plugin_layout_style']; ?> text-align: center;'>
		<div class='intense col-lg-5 col-md-5 col-sm-12 col-xs-12' style='padding:7px; -webkit-box-shadow: 0 0 8px rgba(50,50,50,0.51); -moz-box-shadow: 0 0 8px rgba(50,50,50,0.51); box-shadow: 0 0 8px rgba(50,50,50,0.51);'>
			<a href='<?php echo get_permalink(); ?>' title='<?php  _e( "Permalink to", "intense" ); ?>  <?php echo the_title_attribute( 'echo=0' ); ?>' rel='bookmark'>
				<?php 
				if ( $intense_custom_post['show_images'] ) {
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
				}
				?>
			</a>
		</div>
		<div class='intense col-lg-7 col-md-7 col-sm-12 col-xs-12'>
			<div class='post-header'>
				<a href='<?php echo get_permalink(); ?>' title='<?php  _e( "Permalink to", "intense" ); ?>  <?php echo the_title_attribute( 'echo=0' ); ?>' rel='bookmark'>
					<h2 class='entry-title' style='padding-bottom:5px;'><?php echo the_title_attribute( 'echo=0' ); ?></h2>
				</a>
				<?php if ( $intense_custom_post['show_meta'] ) { ?>
					<div class='entry-meta' style='margin-bottom: 0;'>
						<?php 
						if ( !$intense_custom_post['rtl'] ) {
							echo intense_return_posted_on();
						} else {
							echo intense_return_posted_on_rtl();
						}
						?>
					</div>
				<?php } ?>
			</div>
			<?php
			$prep = get_field('intense_recipe_prep_time' );
			$cook = get_field('intense_recipe_cook_time' );
			$total_time = $prep + $cook;
			$prep = intense_convert_minutes_to_hours( $prep );
			$cook = intense_convert_minutes_to_hours( $cook );
			$total_time = intense_convert_minutes_to_hours( $total_time );

			?>
			<div class='intense row' style='margin: 0; padding-top: 10px;'>
				<div class='intense col-lg-4 col-md-4 col-sm-12 col-xs-12' style="padding: 0;">
					<div class='entry-content' style='margin: 10px 0; border-right: 1px solid #444;'>
						<label><strong><?php echo __('Prep', 'intense' ) ?></strong><h4><?php echo $prep; ?></h4></label>
					</div>
				</div>
				<div class='intense col-lg-4 col-md-4 col-sm-12 col-xs-12' style="padding: 0;">
					<div class='entry-content' style='margin: 10px 0; border-right: 1px solid #444;'>
						<label><strong><?php echo __('Cook', 'intense' ) ?></strong><h4><?php echo $cook; ?></h4></label>
					</div>
				</div>
				<div class='intense col-lg-4 col-md-4 col-sm-12 col-xs-12' style="padding: 0;">
					<div class='entry-content' style='margin: 10px 0;'>
						<label><strong><?php echo __('Ready', 'intense' ) ?></strong><h4><?php echo $total_time; ?></h4></label>
					</div>
				</div>
			</div>
			<div class='post-header' style='padding: 15px 10px 0 10px;'>
				<div class='entry-content intense col-lg-12 col-md-12 col-sm-12 col-xs-12' style="text-align:left;">
					<?php echo $intense_post_type->get_content( 50 ); ?>
				</div>
			</div>
		</div>
	</div>
	<?php
	$cuisine = get_the_term_list( $post->ID, 'intense_recipes_cuisines', '', ', ', '' );
	$course = get_the_term_list( $post->ID, 'intense_recipes_courses', '', ', ', '' );
	$skill_level = get_the_term_list( $post->ID, 'intense_recipes_skill_levels', '', ', ', '' );
	?>
	<div class='intense row' style='<?php echo $intense_custom_post['cancel_plugin_layout_style']; ?> padding-top: 15px; text-align: center;'>
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
	<hr style="margin: 10px 0;">

	<!-- Footer -->
	<footer style='padding: 10px 0;'>

	</footer>
	<?php echo $intense_custom_post['animation_wrapper_end']; ?>
</article>
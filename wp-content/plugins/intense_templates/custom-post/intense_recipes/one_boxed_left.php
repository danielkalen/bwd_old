<?php
/*
Intense Template Name: One Column Boxed (text left)
*/

global $post, $intense_custom_post;

$intense_post_type = $intense_custom_post['post_type'];
?>

<article class='intense col-lg-12 col-md-12 col-sm-12 col-xs-12 <?php echo $intense_custom_post['post_classes']; ?> intense_post nogutter' style='<?php echo $intense_custom_post['plugin_layout_style']; ?> color:#736861;' id='post-<?php echo $post->ID; ?>'>
	<!-- Head -->
	<?php echo $intense_custom_post['animation_wrapper_start']; ?>

	<div><div style='margin-bottom: 0;'>
	<div class='intense row' style='<?php echo $intense_custom_post['cancel_plugin_layout_style']; ?> background-color: #e8e8e8;'>
		<div class='intense col-lg-6 col-md-6 col-sm-12 col-xs-12 nogutter' style="text-align: center;">
			<?php
			$prep = get_field('intense_recipe_prep_time' );
			$cook = get_field('intense_recipe_cook_time' );
			$total_time = $prep + $cook;
			$prep = intense_convert_minutes_to_hours( $prep );
			$cook = intense_convert_minutes_to_hours( $cook );
			$total_time = intense_convert_minutes_to_hours( $total_time );

			?>
			<div class='intense row recipe-header' style='margin: 0; background-color: #DB532B; color: #e8e8e8; box-shadow: 0 5px 2px rgba(0,0,0,0.1);'>
				<div class='intense col-lg-4 col-md-4 col-sm-12 col-xs-12' style="padding: 0;">
					<div class='entry-content' style='margin: 5px 0; border-right: 1px solid #e8e8e8;'>
						<label><strong><?php echo __('Prep', 'intense' ) ?></strong><h4 style='color: #e8e8e8;'><?php echo $prep; ?></h4></label>
					</div>
				</div>
				<div class='intense col-lg-4 col-md-4 col-sm-12 col-xs-12' style="padding: 0;">
					<div class='entry-content' style='margin: 5px 0; border-right: 1px solid #e8e8e8;'>
						<label><strong><?php echo __('Cook', 'intense' ) ?></strong><h4 style='color: #e8e8e8;'><?php echo $cook; ?></h4></label>
					</div>
				</div>
				<div class='intense col-lg-4 col-md-4 col-sm-12 col-xs-12' style="padding: 0;">
					<div class='entry-content' style='margin: 5px 0;'>
						<label><strong><?php echo __('Ready', 'intense' ) ?></strong><h4 style='color: #e8e8e8;'><?php echo $total_time; ?></h4></label>
					</div>
				</div>
			</div>
			<div class='post-header' style='padding:20px;'>
				<a href='<?php echo get_permalink(); ?>' title='<?php  _e( "Permalink to", "intense" ); ?>  <?php echo the_title_attribute( 'echo=0' ); ?>' rel='bookmark'>
					<h2 class='entry-title' style='color:#736861; text-shadow: 0 1px rgba(255,255,255,0.5);'><?php echo the_title_attribute( 'echo=0' ); ?></h2>
				</a>
				<?php if ( $intense_custom_post['show_meta'] ) { ?>
					<div class='entry-meta' style='padding-top:10px; margin-bottom: 0;'>
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
			<div class='post-header' style='padding: 0 10px;'>
				<div class='entry-content intense col-lg-12 col-md-12 col-sm-12 col-xs-12' style="text-align:left;">
					<?php echo $intense_post_type->get_content( 60 ); ?>
				</div>
			</div>
		</div>
		<div class='intense col-lg-6 col-md-6 col-sm-12 col-xs-12 nogutter'>
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
	</div>
	</div><img src='<?php echo INTENSE_PLUGIN_URL ?>/assets/img/shadow10.png' class='intense shadow' style='vertical-align: top;' alt='' /></div>
	<?php
	$cuisine = get_the_term_list( $post->ID, 'intense_recipes_cuisines', '', ', ', '' );
	$course = get_the_term_list( $post->ID, 'intense_recipes_courses', '', ', ', '' );
	$skill_level = get_the_term_list( $post->ID, 'intense_recipes_skill_levels', '', ', ', '' );
	?>
	<div class='intense row' style='<?php echo $intense_custom_post['cancel_plugin_layout_style']; ?> padding-top: 5px; text-align: center;'>
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

	<!-- Footer -->
	<footer style='padding: 10px 0;'>

	</footer>
	<?php echo $intense_custom_post['animation_wrapper_end']; ?>
</article>
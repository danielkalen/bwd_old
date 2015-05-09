<?php
/*
Intense Template Name: One Column
*/

global $post, $intense_custom_post;

$intense_post_type = $intense_custom_post['post_type'];
?>

<article class='intense col-lg-12 col-md-12 col-sm-12 col-xs-12 <?php echo $intense_custom_post['post_classes']; ?> intense_post nogutter' style='<?php echo $intense_custom_post['plugin_layout_style']; ?>' id='post-<?php echo $post->ID; ?>'>
	<!-- Head -->
	<?php echo $intense_custom_post['animation_wrapper_start']; ?>

		
	<?php if ( $intense_custom_post['show_images'] ) { ?>
	<!-- Image -->
	<div class='intense row' style='<?php echo $intense_custom_post['cancel_plugin_layout_style']; ?>'>
		<div class='intense row col-lg-12 col-md-12 col-sm-12 col-xs-12' style='margin:0; margin-left: 15px; padding: 10px 0 0 0; background-color: rgba(0,0,0,0.6); color: #e8e8e8; box-shadow: 0 5px 2px rgba(0,0,0,0.2); position:absolute; z-index:1; text-align: center;'>
			<div class='entry-content intense col-lg-4 col-md-4 col-sm-12 col-xs-12'>
				<label><strong><?php echo __('Prep Time', 'intense' ) ?>:</strong> <?php echo intense_convert_minutes_to_hours( get_field('intense_recipe_prep_time') ); ?></label>
			</div>
			<div class='entry-content intense col-lg-4 col-md-4 col-sm-12 col-xs-12'>
				<label><strong><?php echo __('Cook Time', 'intense' ) ?>: </strong><?php echo intense_convert_minutes_to_hours( get_field('intense_recipe_cook_time') ); ?></label>
			</div>
			<div class='entry-content intense col-lg-4 col-md-4 col-sm-12 col-xs-12'>
				<label><strong><?php echo __('Ready In', 'intense' ) ?>: </strong><?php echo intense_convert_minutes_to_hours( ( get_field('intense_recipe_prep_time') + get_field('intense_recipe_cook_time') ) ); ?></label>
			</div>
		</div>
		<div class='intense col-lg-12 col-md-12 col-sm-12 col-xs-12' style='position: relative;'>
			<?php echo intense_get_post_thumbnails( 
				( isset( $intense_custom_post['image_size'] ) ? $intense_custom_post['image_size'] : 'postWide' ), 
				null, 
				false, 
				( $intense_custom_post['show_missing_image'] == 0 ? false : true ), 
				$intense_custom_post['image_shadow'], 
				$intense_custom_post['hover_effect'], 
				$intense_custom_post['hover_effect_color'], 
				$intense_custom_post['hover_effect_opacity'],
				$intense_custom_post['image_border_radius'],
				true ); ?>	
		</div>
	</div>
	<?php } ?>

	<div class='intense row' style='<?php echo $intense_custom_post['cancel_plugin_layout_style']; ?>'>
		<div class='intense col-lg-12 col-md-12 col-sm-12 col-xs-12'>
			<div class='post-header'>
				<h1 class='entry-title'>
					<a href='<?php echo get_permalink(); ?>' title='<?php  _e( "Permalink to", "intense" ); ?>  <?php echo the_title_attribute( 'echo=0' ); ?>' rel='bookmark'>
						<?php echo the_title_attribute( 'echo=0' ); ?>
					</a> <!-- <?php echo $intense_custom_post['edit_link']; ?> -->
				</h1>
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
			</div>	
		</div>
	</div>

	<!-- Content -->
	<div class='intense row' style='<?php echo $intense_custom_post['cancel_plugin_layout_style']; ?> padding-top: 10px;'>
		<div class='entry-content intense col-lg-12 col-md-12 col-sm-12 col-xs-12'>
			<?php echo $intense_post_type->get_content( 100 ); ?>
		</div>
	</div>
	<?php
	$cuisine = get_the_term_list( $post->ID, 'intense_recipes_cuisines', '', ', ', '' );
	$course = get_the_term_list( $post->ID, 'intense_recipes_courses', '', ', ', '' );
	$skill_level = get_the_term_list( $post->ID, 'intense_recipes_skill_levels', '', ', ', '' );
	?>
	<!-- Footer -->
	<footer style='padding: 5px 0 10px 0;'>
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
	<?php echo $intense_custom_post['animation_wrapper_end']; ?>
</article>

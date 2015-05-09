<?php
/*
Intense Template Name: One Column
*/

require_once 'helper/helper_top.php';

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

		
	<?php if ( $intense_custom_post['show_images'] ) { ?>
	<!-- Image -->
	<div class='intense row' style='<?php echo $intense_custom_post['cancel_plugin_layout_style']; ?>'>
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
			</div>	
		</div>
	</div>

	<!-- Content -->
	<div class='intense row' style='<?php echo $intense_custom_post['cancel_plugin_layout_style']; ?> padding-top: 10px;'>
		<div class='entry-content intense col-lg-12 col-md-12 col-sm-12 col-xs-12'>
			<label><strong><?php echo __('Yield', 'intense' ) ?>: </strong> <?php the_field('intense_recipe_yield'); ?></label>
		</div>
	</div>
	<div class='intense row' style='<?php echo $intense_custom_post['cancel_plugin_layout_style']; ?> padding-top: 10px;'>
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
	<div class='intense row' style='<?php echo $intense_custom_post['cancel_plugin_layout_style']; ?> padding-top: 10px;'>
		<div class='entry-content intense col-lg-12 col-md-12 col-sm-12 col-xs-12'>
			<?php echo do_shortcode( get_the_content() ); ?>
		</div>
	</div>
	<div class='intense row' style='<?php echo $intense_custom_post['cancel_plugin_layout_style']; ?> padding-top: 10px;'>
		<div class='entry-content intense col-lg-12 col-md-12 col-sm-12 col-xs-12'>
		<?php			

			$rows = get_field('intense_recipe_ingredients');
			if( $rows )
			{
				echo '<label><strong>' . __('Ingredients', 'intense' ) . ':</strong></label>';

				echo '<ul>';
			 
				foreach( $rows as $row )
				{
					$ingredient = $row['intense_recipe_ingredient'];
					echo '<li>' . $row['intense_recipe_amount'] . ' ' . $row['intense_recipe_measurement'] . ' ' . $ingredient->name . ' ' . $row['intense_recipe_note'] . '</li>';
				}
			 
				echo '</ul>';
			}
		?>
		</div>
	</div>
	<div class='intense row' style='<?php echo $intense_custom_post['cancel_plugin_layout_style']; ?> padding-top: 10px;'>
		<div class='entry-content intense col-lg-12 col-md-12 col-sm-12 col-xs-12'>
		<?php			
			$rows = get_field('intense_recipe_instructions');
			$rowcount = 1;

			if( $rows )
			{
				$shortcode = '[intense_row padding_top="0"]';
				$shortcode .= '[intense_column size="12" medium_size="12"]<label><strong>' . __('Instructions', 'intense' ) . ':</strong></label>[/intense_column]';
				$shortcode .= '[/intense_row]';
				$shortcode .= '<ol>';

				foreach( $rows as $row )
				{
					$instructions = $row['intense_recipe_description'];
					$shortcode .= '<li>';

					if ( $row['intense_recipe_image'] ) {
						$image = $row['intense_recipe_image'];
						$shortcode .= '<div class="pull-right" style="margin-left: 20px; margin-bottom:20px;">[intense_image image="' . $image['url'] . '" size="square150"]</div>';
						$shortcode .= $instructions;
						$shortcode .= '<div style="clear:both;"> </div>';

					} else {
						$shortcode .= $instructions;					
					}

					$shortcode .= '</li>';
					$rowcount++;
				}
			 
			 	$shortcode .= '</ol>';

				echo do_shortcode( $shortcode );
			}
		?>
		</div>
	</div>
	<?php
	$cuisine = get_the_term_list( $post->ID, 'intense_recipes_cuisines', '', ', ', '' );
	$course = get_the_term_list( $post->ID, 'intense_recipes_courses', '', ', ', '' );
	$skill_level = get_the_term_list( $post->ID, 'intense_recipes_skill_levels', '', ', ', '' );
	?>
	<!-- Footer -->
	<footer style='padding-top: 5px;'>
		<hr style="margin: 10px 0;">
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

<?php

	$i++;
endwhile;

require_once 'helper/helper_bottom.php';

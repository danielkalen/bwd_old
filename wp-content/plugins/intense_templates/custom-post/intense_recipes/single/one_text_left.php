<?php
/*
Intense Template Name: One Column (text left)
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

	if ( current_user_can( 'manage_options' ) ) {
		$intense_custom_post['edit_link'] = "&nbsp;" . intense_run_shortcode( 'intense_button', array( 'size' => 'mini', 'color' => 'inverse', 'class' => 'post-edit-link' , 'link' => get_edit_post_link( $post->ID ), 'title' => __( "Edit Post", "intense" ) ), __( "Edit", "intense" ) );
	} else {
		$intense_custom_post['edit_link'] = '';
	}

	$intense_custom_post['post_classes'] = $item_classes;
?>

<article class='intense col-lg-12 col-md-12 col-sm-12 col-xs-12 <?php echo $intense_custom_post['post_classes']; ?> intense_post nogutter' style='<?php echo $intense_custom_post['plugin_layout_style']; ?>' id='post-<?php echo $post->ID; ?>'>
	<!-- Head -->
	<?php echo $intense_custom_post['animation_wrapper_start']; ?>

	<div class='intense row' style='<?php echo $intense_custom_post['cancel_plugin_layout_style']; ?> text-align: center;'>
		<div class='intense col-lg-7 col-md-7 col-sm-12 col-xs-12'>
			<div class='post-header'>
				<h1 class='entry-title'><?php echo the_title_attribute( 'echo=0' ); ?></h1>
			</div>
			<?php
			$prep = get_field('intense_recipe_prep_time' );
			$cook = get_field('intense_recipe_cook_time' );
			$total_time = $prep + $cook;
			$prep = intense_convert_minutes_to_hours( $prep );
			$cook = intense_convert_minutes_to_hours( $cook );
			$total_time = intense_convert_minutes_to_hours( $total_time );

			?>
			<div class='intense row' style='margin: 0; padding-top: 30px;'>
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
		</div>
		<div class='intense col-lg-5 col-md-5 col-sm-12 col-xs-12' style='padding:7px; -webkit-box-shadow: 0 0 8px rgba(50,50,50,0.51); -moz-box-shadow: 0 0 8px rgba(50,50,50,0.51); box-shadow: 0 0 8px rgba(50,50,50,0.51);'>
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
		</div>
	</div>
	<?php
	$cuisine = get_the_term_list( $post->ID, 'intense_recipes_cuisines', '', ', ', '' );
	$course = get_the_term_list( $post->ID, 'intense_recipes_courses', '', ', ', '' );
	$skill_level = get_the_term_list( $post->ID, 'intense_recipes_skill_levels', '', ', ', '' );
	?>
	<div class='intense row' style='<?php echo $intense_custom_post['cancel_plugin_layout_style']; ?> padding-top: 15px;'>
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
			{ ?>
				<h2><?php echo  __('Ingredients', 'intense' ) ?>:</h2>
				<label style="font-style: italic;"><?php echo __('Recipe yields', 'intense' ) ?> <?php the_field('intense_recipe_yield'); ?></label>
				<ul>
			 	
			 	<?php foreach( $rows as $row ) { 
					$ingredient = $row['intense_recipe_ingredient'];
			 	?>
					<li><?php echo $row['intense_recipe_amount'] . ' ' . $row['intense_recipe_measurement'] . ' ' . $ingredient->name . ' ' . $row['intense_recipe_note'] ?></li>
				<?php } ?>
			 
				</ul>
			<?php }
		?>
		</div>
	</div>
	<div class='intense row' style='<?php echo $intense_custom_post['cancel_plugin_layout_style']; ?> padding-top: 10px;'>
		<div class='entry-content intense col-lg-12 col-md-12 col-sm-12 col-xs-12'>
		<?php			
			$rows = get_field('intense_recipe_instructions');

			if( $rows )
			{ ?>
				<h2><?php echo  __('Instructions', 'intense' ) ?>:</h2>
				<ol>
			 	
			 	<?php foreach( $rows as $row ) { 
					$instructions = $row['intense_recipe_description'];
			 	?>
					<li><?php echo $instructions ?></li>
				<?php } ?>
			 
				</ol>
			<?php } ?>
		</div>
	</div>

	<!-- Footer -->
	<footer style='padding-top: 5px;'>

	</footer>
	<?php echo $intense_custom_post['animation_wrapper_end']; ?>
</article>

<?php
	$i++;
	endwhile;

require_once 'helper/helper_bottom.php';

<?php
/*
Intense Template Name: One Column (text right)
*/

global $post, $intense_custom_post;

$intense_post_type = $intense_custom_post['post_type'];
?>
<article class='intense row <?php echo $intense_custom_post['post_classes']; ?> intense_post nogutter' style='<?php echo $intense_custom_post['plugin_layout_style']; ?>' id='post-<?php echo $post->ID; ?>'>
	<?php echo $intense_custom_post['animation_wrapper_start']; ?>
	<!-- Image -->
	<div class='intense col-lg-7 col-md-7 col-sm-7 col-xs-7' style="margin-left: 0px; margin-right: 10px;">
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
	</div>
	<div class='post-category'>
		<h6><?php 
		$categories = explode(", ", $intense_custom_post['categories']);

		foreach ($categories as $category) {
			echo intense_run_shortcode( 'intense_badge', null, $category ) . ' '; 
		}
		?></h6>
	</div>

	<!-- Content -->	
	<div class='entry-content'>
		<?php echo $intense_post_type->get_content( 45 ); ?>
	</div>
	<div class="clearfix"></div>

	<!-- Footer -->
	<footer style='padding-top: 5px;'>
		<span class='read-more pull-right'>
		<?php echo intense_run_shortcode( 'intense_button', array( 'size' => 'mini', 'color' => 'primary', 'link' => get_permalink(), 'icon' => 'angle-right', 'icon_position' => 'right' ), $intense_custom_post['timeline_readmore'] ); ?>			
		</span>
	</footer>
	<?php echo $intense_custom_post['animation_wrapper_end']; ?>
</article>

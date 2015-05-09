<?php
/*
Intense Template Name: One Column (image only)
*/

global $post, $intense_custom_post, $intense_visions_options;

?>

<article class='intense col-lg-12 col-md-12 col-sm-12 col-xs-12 <?php echo $intense_custom_post['post_classes']; ?> intense_post nogutter' style='<?php echo $intense_custom_post['plugin_layout_style']; ?>' id='post-<?php echo $post->ID; ?>'>
	<!-- Head -->
	<?php echo $intense_custom_post['animation_wrapper_start']; ?>
	
	<?php if ( $intense_custom_post['show_images'] ) { ?>
	<a href='<?php echo get_permalink(); ?>' title='<?php  _e( "Permalink to", "intense" ); ?>  <?php echo the_title_attribute( 'echo=0' ); ?>' rel='bookmark'>
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
	</a>
	<?php } ?>
	
	<?php echo $intense_custom_post['animation_wrapper_end']; ?>
</article>

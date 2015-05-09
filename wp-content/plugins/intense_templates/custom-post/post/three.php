<?php
/*
Intense Template Name: Three Columns (no text)
*/

global $post, $intense_custom_post;

$inline = ( is_sticky() && 'inline' == $intense_custom_post['sticky_mode'] );

if ( $inline ) {
	$span = "12";
	$size = "postWide";
} else {
	$span = "4";
	$size = ( isset( $intense_custom_post['image_size'] ) ? $intense_custom_post['image_size'] : 'medium640' );
}
?>
<div class='intense col-lg-<?php echo $span; ?> col-md-<?php echo $span; ?> col-sm-12 col-xs-12 <?php echo $intense_custom_post['post_classes']; ?> intense_post nogutter' style='display:inline-block; vertical-align: top;'>
	<?php echo $intense_custom_post['animation_wrapper_start']; ?>
	<article id='post-<?php echo $post->ID; ?>' class='<?php echo ( $inline ?  'featured ' : '' ); ?>'>
		<div class='image'>
			<a href='<?php echo get_permalink(); ?>' title='<?php  _e( "Permalink to", "intense" ); ?>  <?php echo the_title_attribute( 'echo=0' ); ?>' rel='bookmark'>
			<?php echo intense_get_post_thumbnails( 
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
			?>
			</a>
		</div>
	</article>
	<?php echo $intense_custom_post['animation_wrapper_end']; ?>
</div>
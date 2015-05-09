<?php
/*
Intense Template Name: Four Columns (text)
*/

global $post, $intense_custom_post, $intense_visions_options;

$inline = ( is_sticky() && 'inline' == $intense_custom_post['sticky_mode'] );

if ( $inline ) {
	$span = "12";
	$size = "postWide";
	$header_tag = "h3";
} else {
	$span = "3";
	$size = ( isset( $intense_custom_post['image_size'] ) ? $intense_custom_post['image_size'] : 'medium640' );
	$header_tag = "h5";
}

$padding_bottom = $intense_visions_options['intense_layout_row_default_padding']['padding-top'];
$intense_post_type = $intense_custom_post['post_type'];
?>
<div class='intense col-lg-<?php echo $span; ?> col-md-<?php echo $span; ?> col-sm-6 col-xs-12  <?php echo $intense_custom_post['post_classes']; ?> intense_post nogutter' style='margin-left: 0px; float: none; padding: 0 10px; padding-bottom: <?php echo $padding_bottom ?>; display:inline-block; vertical-align: top;'>
	<article id='post-<?php echo $post->ID; ?>' class='<?php echo ( $inline ?  'featured ' : '' ); ?>'>
	<?php echo $intense_custom_post['animation_wrapper_start']; ?>
		<?php if ( $intense_custom_post['show_images'] ) { ?>
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
		<?php } ?>
		<<?php echo $header_tag; ?> class='entry-title'><a href='<?php echo get_permalink(); ?>' title='<?php  _e( "Permalink to", "intense" ); ?>  <?php echo the_title_attribute( 'echo=0' ); ?>' rel='bookmark'><?php echo the_title_attribute( 'echo=0' )?></a> <?php echo $intense_custom_post['edit_link']; ?></<?php echo $header_tag; ?>>
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
			<?php 
				if ( is_object( $intense_post_type ) ) {
					echo $intense_post_type->get_excerpt( 20 );
				} else {
					echo intense_excerpt( 20 );
				}
			?>
		</div>
	<?php echo $intense_custom_post['animation_wrapper_end']; ?>
	</article>
</div>
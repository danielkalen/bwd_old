<?php
/*
Intense Template Name: Three Columns (text)
*/

global $post, $intense_custom_post, $intense_visions_options;

$inline = ( is_sticky() && 'inline' == $intense_custom_post['sticky_mode'] );

if ( $inline ) {
	$span = "12";
	$size = "postWide";
	$header_tag = "h3";
} else {
	$span = "4";
	$size = ( isset( $intense_custom_post['image_size'] ) ? $intense_custom_post['image_size'] : 'medium640' );
	$header_tag = "h4";
}

$padding_bottom = $intense_visions_options['intense_layout_row_default_padding']['padding-top'];
$intense_post_type = $intense_custom_post['post_type'];
?>
<div class='intense col-lg-<?php echo $span; ?> col-md-<?php echo $span; ?> col-sm-12 col-xs-12 <?php echo $intense_custom_post['post_classes']; ?> intense_post' style='margin-left: 0px; float: none; padding: 0 10px; padding-bottom: <?php echo $padding_bottom ?>; display:inline-block; vertical-align: top;'>
	<?php echo $intense_custom_post['animation_wrapper_start']; ?>
	<article id='post-<?php echo $post->ID; ?>' class='<?php echo ( $inline ?  'featured ' : '' ); ?>'>
		<div class='image'>
			<?php $image = get_field( 'intense_movie_image' );

			if( !empty( $image ) ) {
			?>
			 <a href='<?php echo get_permalink(); ?>' title='<?php  _e( "Permalink to", "intense" ); ?>  <?php echo the_title_attribute( 'echo=0' ); ?>' rel='bookmark'>
			 	<img src='<?php echo $image["url"];?>' title='<?php echo the_title_attribute( 'echo=0' ); ?>' alt='' style='padding:10px 0;' />
			 </a>
			<?php } ?>
		</div>
		<<?php echo $header_tag; ?> class='entry-title'><a href='<?php echo get_permalink(); ?>' title='<?php  _e( "Permalink to", "intense" ); ?>  <?php echo the_title_attribute( 'echo=0' ); ?>' rel='bookmark'><?php echo the_title_attribute( 'echo=0' )?></a> <?php echo $intense_custom_post['edit_link']; ?></<?php echo $header_tag; ?>>
		<div class='entry-content'>
			<?php echo $intense_post_type->get_excerpt( 20 ); ?>
		</div>
	</article>
	<?php echo $intense_custom_post['animation_wrapper_end']; ?>
</div>
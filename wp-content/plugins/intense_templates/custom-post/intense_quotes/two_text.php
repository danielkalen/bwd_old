<?php
/*
Intense Template Name: Two Columns (text)
*/

global $post, $intense_custom_post, $intense_visions_options;

$inline = ( is_sticky() && 'inline' == $intense_custom_post['sticky_mode'] );

if ( $inline ) {
	$span = "12";
	$size = "postWide";
	$header_tag = "h3";
} else {
	$span = "6";
	$size = ( isset( $intense_custom_post['image_size'] ) ? $intense_custom_post['image_size'] : 'medium640' );
	$header_tag = "h3";
}

$padding_bottom = $intense_visions_options['intense_layout_row_default_padding']['padding-top'];
$intense_post_type = $intense_custom_post['post_type'];
?>
<div class='intense col-lg-<?php echo $span; ?> col-md-<?php echo $span; ?> col-sm-6 col-xs-12  <?php echo $intense_custom_post['post_classes']; ?> intense_post nogutter' style='margin-top:10px; margin-left: 0px; float: none; padding: 0 10px; padding-bottom: <?php echo $padding_bottom ?>; display:inline-block; vertical-align: top;'>
	<article id='post-<?php echo $post->ID; ?>' class='<?php echo ( $inline ?  'featured ' : '' ); ?>'>
	<?php echo $intense_custom_post['animation_wrapper_start']; ?>
	<?php echo intense_run_shortcode( 'intense_blockquote', array( 
	          'width' => '100%',
	          'author' => get_field( 'intense_quote_author' )
	        ), get_the_content() ); ?>
	<?php echo $intense_custom_post['animation_wrapper_end']; ?>
	</article>
</div>
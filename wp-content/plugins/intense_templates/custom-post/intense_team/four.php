<?php
/*
Intense Template Name: Four Columns (no text)
*/

global $post, $intense_custom_post;

$inline = ( is_sticky() && 'inline' == $intense_custom_post['sticky_mode'] );

if ( $inline ) {
	$span = "12";
	$size = "postWide";
} else {
	$span = "3";
	$size = ( isset( $intense_custom_post['image_size'] ) ? $intense_custom_post['image_size'] : 'medium640' );
}
?>

<div class='intense col-lg-<?php echo $span; ?> col-md-<?php echo $span; ?> col-sm-6 col-xs-12 <?php echo $intense_custom_post['post_classes']; ?> intense_post nogutter' style='display:inline-block; vertical-align: top;'>
	<article id='post-<?php echo $post->ID; ?>' class='<?php echo ( $inline ?  'featured ' : '' ); ?>'>
	<?php echo $intense_custom_post['animation_wrapper_start']; ?>
		<div class='image'>
			<?php $image = get_field( 'intense_member_photo' );

			if( !empty( $image ) ) {
			?>
			 <a href='<?php echo get_permalink(); ?>' title='<?php  _e( "Permalink to", "intense" ); ?>  <?php echo the_title_attribute( 'echo=0' ); ?>' rel='bookmark'>
			 	<img src='<?php echo $image["url"];?>' title='<?php echo the_title_attribute( 'echo=0' ); ?>' alt='' style='padding:10px 0;' />
			 </a>
			<?php } ?>
		</div>
	<?php echo $intense_custom_post['animation_wrapper_end']; ?>
	</article>
</div>
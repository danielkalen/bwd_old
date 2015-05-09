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
<div class='intense col-lg-<?php echo $span; ?> col-md-<?php echo $span; ?> col-sm-12 col-xs-12 <?php echo $intense_custom_post['post_classes']; ?> intense_post nogutter' style='margin-left: 0px; float: none; padding: 0 10px; padding-bottom: <?php echo $padding_bottom ?>; display:inline-block; vertical-align: top;'>
	<article id='post-<?php echo $post->ID; ?>' class='<?php echo ( $inline ?  'featured ' : '' ); ?>'>
	<?php echo $intense_custom_post['animation_wrapper_start']; ?>
		<?php if ( $intense_custom_post['show_images'] ) { ?>
			<div class='image'>
				<?php $image = get_field( 'intense_member_photo' );

				if( !empty( $image ) ) {
				?>
				 <a href='<?php echo get_permalink(); ?>' title='<?php  _e( "Permalink to", "intense" ); ?>  <?php echo the_title_attribute( 'echo=0' ); ?>' rel='bookmark'>
				 	<img src='<?php echo $image["url"];?>' title='<?php echo the_title_attribute( 'echo=0' ); ?>' alt='' style='padding:10px 0;' />
				 </a>
				<?php } ?>
			</div>
		<?php } ?>
		<<?php echo $header_tag; ?> class='entry-title' style='margin:10px 0;'><a href='<?php echo get_permalink(); ?>' title='<?php  _e( "Permalink to", "intense" ); ?>  <?php echo the_title_attribute( 'echo=0' ); ?>' rel='bookmark'><?php echo the_title_attribute( 'echo=0' )?></a> <?php echo $intense_custom_post['edit_link']; ?></<?php echo $header_tag; ?>>
		<div class='entry-content'>
			<?php
				if ( get_field( 'intense_member_title' ) != '' ) {
					echo '<h4>' . get_field( 'intense_member_title' ) . '</h4>';
				}
			?>
			<?php echo $intense_post_type->get_excerpt( 40 ); ?>
		</div>
	<?php echo $intense_custom_post['animation_wrapper_end']; ?>
	</article>
</div>
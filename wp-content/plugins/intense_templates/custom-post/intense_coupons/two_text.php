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
		<div style="border: 3px dashed #cccccc; padding:10px;">
			<<?php echo $header_tag; ?> class='entry-title' style='margin:5px 0px;'><a href='<?php echo get_permalink(); ?>' title='<?php  _e( "Permalink to", "intense" ); ?>  <?php echo the_title_attribute( 'echo=0' ); ?>' rel='bookmark'><?php echo the_title_attribute( 'echo=0' )?></a> <?php echo $intense_custom_post['edit_link']; ?></<?php echo $header_tag; ?>>
			
			<div class='entry-content'>
				<?php echo do_shortcode( get_the_content() ); ?>
			</div>
			<div style='color:#333333;font-size:10px; padding-top:20px;'>
			<?php
				if ( get_field( 'intense_coupon_start_date' ) != '' && get_field( 'intense_coupon_end_date' ) != '' ) {
					echo __( 'Valid from', 'intense' ) . ' ' . date("M d, Y", strtotime( get_field( 'intense_coupon_start_date' ) ) ) . ' ' . __( 'to', 'intense' ) . ' ' . date("M d, Y", strtotime( get_field( 'intense_coupon_end_date' ) ) );
				} elseif ( get_field( 'intense_coupon_start_date' ) != '' && get_field( 'intense_coupon_end_date' ) === '' ) {
					echo __( 'Valid after', 'intense' ) . ' ' . date("M d, Y", strtotime( get_field( 'intense_coupon_start_date' ) ) );
				} elseif ( get_field( 'intense_coupon_start_date' ) === '' && get_field( 'intense_coupon_end_date' ) != '' ) {
					echo __( 'Valid until', 'intense' ) . ' ' . date("M d, Y", strtotime( get_field( 'intense_coupon_end_date' ) ) );
				}
			?>
			</div>
		</div>
	<?php echo $intense_custom_post['animation_wrapper_end']; ?>
	</article>
</div>
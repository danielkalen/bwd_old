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
			<?php
				if ( get_field( 'intense_movie_subtitle' ) != '' ) {
					echo '<h4>' . get_field( 'intense_movie_subtitle' ) . '</h4>';
				}
			?>
			<?php echo $intense_post_type->get_excerpt( 40 ); ?>
			<?php if ( $intense_custom_post['show_social_sharing'] ) { ?>
				<br /><span style="padding-top:10px;">
					<?php 
						$output_shortcode = '';
						$output_shortcode .= '[intense_social_share share_url="http://intensevisions.com" ';
						$output_shortcode .= ( $intense_visions_options['intense_social_facebook_use'] == 1 ? 'show_facebook="' . $intense_visions_options['intense_social_facebook_use'] . '" facebook_button="' . $intense_visions_options['intense_social_facebook_layout'] . '" facebook_faces="0" facebook_share="' . $intense_visions_options['intense_social_facebook_share'] . '" ' : '' );
						$output_shortcode .= ( $intense_visions_options['intense_social_google_plus_use'] == 1 ? 'show_googleplus="' . $intense_visions_options['intense_social_google_plus_use'] . '" googleplus_button="' . $intense_visions_options['intense_social_google_plus_layout'] . '" ' : '' );
						$output_shortcode .= ( $intense_visions_options['intense_social_linkedin_use'] == 1 ? 'show_linkedin="' . $intense_visions_options['intense_social_linkedin_use'] . '" linkedin_button="' . $intense_visions_options['intense_social_linkedin_layout'] . '" ' : '' );
						$output_shortcode .= ( $intense_visions_options['intense_social_stumbleupon_use'] == 1 ? 'show_stumbleupon="' . $intense_visions_options['intense_social_stumbleupon_use'] . '" stumbleupon_button="' . $intense_visions_options['intense_social_stumbleupon_layout'] . '" ' : '' );
						$output_shortcode .= ( $intense_visions_options['intense_social_twitter_use'] == 1 ? 'show_twitter="' . $intense_visions_options['intense_social_twitter_use'] . '" twitter_button="' . $intense_visions_options['intense_social_twitter_layout'] . '" ' : '' );
						$output_shortcode .= ( $intense_visions_options['intense_social_pinterest_use'] == 1 ? 'show_pinterest="' . $intense_visions_options['intense_social_pinterest_use'] . '" pinterest_image="' . get_permalink() . '" pinterest_button="' . $intense_visions_options['intense_social_pinterest_layout'] . '"' : '' );
						$output_shortcode .= '[/intense_social_share]';
						echo do_shortcode( $output_shortcode );
					 ?>
				</span>
			<?php } ?>
		</div>
	<?php echo $intense_custom_post['animation_wrapper_end']; ?>
	</article>
</div>
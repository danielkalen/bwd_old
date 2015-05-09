<?php
/*
Intense Template Name: Timeline (text right)
*/

global $post, $intense_custom_post;

$intense_post_type = $intense_custom_post['post_type'];
?>
<article class='intense row <?php echo $intense_custom_post['post_classes']; ?> nogutter' style='<?php echo $intense_custom_post['plugin_layout_style']; ?>' id='post-<?php echo $post->ID; ?>'>
	<!-- Image -->
	<div class='intense col-lg-7 col-md-12 col-sm-7 col-xs-7' style="margin-left: 0px; margin-right: 10px;">
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

	<!-- Content -->	
	<div class='entry-content'>
		<?php 
			if ( is_object( $intense_post_type ) ) {
				echo $intense_post_type->get_content( 30 );
			} else {
				echo intense_content( 30 );
			}
		?>
	</div>
	<div class="clearfix"></div>

	<!-- Footer -->
	<footer style='padding-top: 5px;'>
		<span class='read-more pull-right'>
		<?php echo intense_run_shortcode( 'intense_button', array( 'size' => 'mini', 'color' => 'primary', 'link' => get_permalink(), 'icon' => 'angle-right', 'icon_position' => 'right' ), $intense_custom_post['timeline_readmore'] ); ?>			
		</span>
	</footer>
</article>

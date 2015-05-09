<?php
/*
Intense Template Name: Timeline (text only)
*/

global $post, $intense_custom_post;

$intense_post_type = $intense_custom_post['post_type'];
?>
<article class='intense row <?php echo $intense_custom_post['post_classes']; ?> intense_post nogutter' style='<?php echo $intense_custom_post['cancel_plugin_layout_style']; ?>' id='post-<?php echo $post->ID; ?>'>
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<!-- Head -->
		<div class='intense row' style='<?php echo $intense_custom_post['cancel_plugin_layout_style']; ?>'>		
			<div class='intense col-lg-12 col-md-12 col-sm-12 col-xs-12'>		
				<div class='post-header'>
					<h1 class='entry-title'>
						<a href='<?php echo get_permalink(); ?>' title='<?php  _e( "Permalink to", "intense" ); ?>  <?php echo the_title_attribute( 'echo=0' ); ?>' rel='bookmark'>
							<?php echo the_title_attribute( 'echo=0' ); ?>
						</a> <?php echo $intense_custom_post['edit_link']; ?>
					</h1>
					<h6><?php 
						$categories = explode(", ", $intense_custom_post['categories']);

						foreach ($categories as $category) {
							echo intense_run_shortcode( 'intense_badge', null, $category ) . ' ';
						}
					?></h6>
				</div>	
			</div>
		</div>
		
		<!-- Content -->
		<div class='intense row' style='<?php echo $intense_custom_post['cancel_plugin_layout_style']; ?>'>
			<div class='entry-content intense col-lg-12 col-md-12 col-sm-12 col-xs-12'>
				<?php echo $intense_post_type->get_content( 50 ); ?>
			</div>
		</div>

		<!-- Footer -->
		<footer style='padding-top: 5px;'>
			<span class='read-more pull-right'>
			<?php echo intense_run_shortcode( 'intense_button', array( 'size' => 'mini', 'color' => 'primary', 'link' => get_permalink(), 'icon' => 'angle-right', 'icon_position' => 'right' ), $intense_custom_post['timeline_readmore'] ); ?>			
			</span>
		</footer>
	</div>
</article>

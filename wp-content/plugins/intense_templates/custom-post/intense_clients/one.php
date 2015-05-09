<?php
/*
Intense Template Name: One Column
*/

global $post, $intense_custom_post, $intense_visions_options;

$intense_post_type = $intense_custom_post['post_type'];
?>
<article class='intense col-lg-12 col-md-12 col-sm-12 col-xs-12 <?php echo $intense_custom_post['post_classes']; ?> intense_post nogutter' style='<?php echo $intense_custom_post['plugin_layout_style']; ?>' id='post-<?php echo $post->ID; ?>'>
	<!-- Head -->
	<?php echo $intense_custom_post['animation_wrapper_start']; ?>
	<div class='intense row' style='<?php echo $intense_custom_post['cancel_plugin_layout_style']; ?>'>
		<div class='intense col-lg-12 col-md-12 col-sm-12 col-xs-12' style='<?php echo $intense_custom_post['cancel_plugin_layout_style']; ?> padding-top:20px;'>
			<div class='intense col-lg-4 col-md-4 col-sm-12 col-xs-12' style='padding-left:0;'>
				<div class='post-header'>
					<h1 class='entry-title' style='line-height: inherit;'>
						<a href='<?php echo get_permalink(); ?>' title='<?php  _e( "Permalink to", "intense" ); ?>  <?php echo the_title_attribute( 'echo=0' ); ?>' rel='bookmark'>
						<?php echo the_title_attribute( 'echo=0' ); ?>
						</a>
					</h1>
					<?php
						if ( $intense_post_type->get_subtitle() != '' ) {
							echo '<h4>' . $intense_post_type->get_subtitle() . '</h4>';
						}
					?>
				</div>
			</div>
			<div class='intense col-lg-4 col-md-4 col-sm-12 col-xs-12' style='text-align:center;'>
				<?php $image = get_field( 'intense_client_logo' );
					if( !empty( $image ) ) { ?>
						<a href='<?php echo get_permalink(); ?>' title='<?php  _e( "Permalink to", "intense" ); ?>  <?php echo the_title_attribute( 'echo=0' ); ?>' rel='bookmark'>
						<img style='max-width:250px;max-height:150px; padding-top:10px;' src='<?php echo $image["url"];?>' title='<?php echo the_title_attribute( 'echo=0' ); ?>' alt='' />
						</a>
				<?php } ?>
			</div>
			<div class='intense col-lg-4 col-md-4 col-sm-12 col-xs-12'>
				<div class='pull-right'>
					<?php echo get_field( 'intense_client_address' ) . '<br /><a href="' . get_field( 'intense_client_website' ) . '" target="_blank">' . get_field( 'intense_client_website' ) . '</a>' ?>
				</div>
			</div>
		</div>
	</div>
		
	<?php if ( $intense_custom_post['show_images'] ) { ?>
	<!-- Image -->
	<div class='intense row' style='<?php echo $intense_custom_post['cancel_plugin_layout_style']; ?>'>
		<div class='intense col-lg-12 col-md-12 col-sm-12 col-xs-12' style='position: relative; padding-top:10px;'>
			<?php
				$imagesrc = intense_get_post_thumbnail_src( 'postWide', true, false );

				if ( isset( $imagesrc ) ) {
					echo intense_run_shortcode( 'intense_content_section', array( 
			          'background_type' => 'image',
			          'image' => $imagesrc[0],
			          'height' => '250'
			        ) );
				}
			?>	
		</div>
	</div>
	<?php } ?>
	<?php echo $intense_custom_post['animation_wrapper_end']; ?>
</article>

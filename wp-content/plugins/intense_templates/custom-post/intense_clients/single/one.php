<?php
/*
Intense Template Name: One Column
*/

	get_header( 'intense' );

	global $intense_custom_post, $intense_visions_options, $intense_post_types, $post;

	$padding_top = $intense_visions_options['intense_layout_row_default_padding']['padding-top'];
	$padding_bottom = $intense_visions_options['intense_layout_row_default_padding']['padding-bottom'];
	$margin_top = $intense_visions_options['intense_layout_row_default_margin']['margin-top'];
	$margin_bottom = $intense_visions_options['intense_layout_row_default_margin']['margin-bottom'];

	$layout_style = '';

	if ( !empty( $padding_top ) ) {
		$layout_style .= 'padding-top: ' . $padding_top . '; ';
	}

	if ( !empty( $padding_bottom ) ) {
		$layout_style .= 'padding-bottom: ' . $padding_bottom . '; ';
	}

	if ( !empty( $margin_top ) ) {
		$layout_style .= 'margin-top: ' . $margin_top . '; ';
	}

	if ( !empty( $margin_bottom ) ) {
		$layout_style .= 'margin-bottom: ' . $margin_bottom . '; ';
	}

	$no_layout_style = "padding-top: 0px; padding-bottom: 0px; margin-top: 0px; margin-bottom: 0px;";

	$intense_post_type = $intense_post_types->get_post_type( 'intense_recipes' );

	$intense_custom_post = array(
		'plugin_layout_style' => $layout_style,
		'cancel_plugin_layout_style' => $no_layout_style,
		'post_type' => $intense_post_type,
		'taxonomy' => '',
		'template' => '',
		'categories' => '',
		'exclude_categories' => '',
		'exclude_post_ids' => '',
		'authors' => '',
		'order_by' => '',
		'order' => '',
		'posts_per_page' => '',
		'image_size' => 'Full',
		'image_shadow' => '0',
		'hover_effect' => '',
		'show_all' => '',
		'show_meta' => '0',
		'show_author' => '0',
		'infinite_scroll' => '',
		'show_filter' => '',
		'show_images' => '1',
		'show_missing_image' => '0',
		'show_social_sharing' => '1',
		'timeline_mode' => '',
		'timeline_showyear' => '',
		'timeline_readmore' => '',
		'timeline_color' => '',
		'filter_easing' => '',
		'filter_effects' => '',
		'hover_effect_color' => '',
		'hover_effect_opacity' => '',
		'sticky_mode' => '',
		'image_border_radius' => '',
		'timeline_border_radius' => '',
		'animation_type' => '',
		'animation_trigger' => null ,
		'animation_scroll_percent' => null,
		'animation_delay' => null,
		'animation_wrapper_start' => null,
		'animation_wrapper_end' => null,
		'is_slider' => '',
		'rtl' => $intense_visions_options['intense_rtl']
	);

	$i = 0;

	do_action( 'intense_before_main_content' );

	while ( have_posts() ) : the_post();
	$item_classes = '';
	$intense_custom_post['image_shadow'] = '';
	$intense_custom_post['hover_effect'] = '';
	$intense_custom_post['hover_effect_color'] = '';
	$intense_custom_post['hover_effect_opacity'] = '';
	$intense_custom_post['index'] = $i;

	if ( has_post_thumbnail() ) {
		if ( get_field( 'intense_image_shadow' ) != '' ) {
			$intense_custom_post['image_shadow'] = get_field( 'intense_image_shadow' );
		}

		if ( get_field( 'intense_hover_effect' ) != '' ) {
			$intense_custom_post['hover_effect'] = get_field( 'intense_hover_effect' );
		}

		if ( get_field( 'intense_effect_color' ) != '' ) {
			$intense_custom_post['hover_effect_color'] = get_field( 'intense_effect_color' );
		}

		if ( get_field( 'intense_effect_opacity' ) != '' ) {
			$intense_custom_post['hover_effect_opacity'] = get_field( 'intense_effect_opacity' );
		}
	}

	if ( current_user_can( 'manage_options' ) ) {
		$intense_custom_post['edit_link'] = "&nbsp;" . intense_run_shortcode( 'intense_button', array( 'size' => 'mini', 'color' => 'inverse', 'class' => 'post-edit-link' , 'link' => get_edit_post_link( $post->ID ), 'title' => __( "Edit Post", "intense" ) ), __( "Edit", "intense" ) );
	} else {
		$intense_custom_post['edit_link'] = '';
	}

	$intense_custom_post['post_classes'] = $item_classes;
?>
<article class='intense col-lg-12 col-md-12 col-sm-12 col-xs-12 <?php echo $intense_custom_post['post_classes']; ?> intense_post nogutter' style='<?php echo $intense_custom_post['plugin_layout_style']; ?>' id='post-<?php echo $post->ID; ?>'>
	<!-- Head -->
	<?php echo $intense_custom_post['animation_wrapper_start']; ?>

	<div class='intense row' style='<?php echo $intense_custom_post['cancel_plugin_layout_style']; ?>'>
		<div class='intense col-lg-12 col-md-12 col-sm-12 col-xs-12' style='<?php echo $intense_custom_post['cancel_plugin_layout_style']; ?> padding-top:20px;'>
			<div class='intense col-lg-4 col-md-4 col-sm-12 col-xs-12' style='padding-left:0;'>
				<div class='post-header'>
					<h1 class='entry-title' style='line-height: inherit;'>
						<?php echo the_title_attribute( 'echo=0' ); ?>
					</h1>
					<?php
						if ( $intense_post_type->get_subtitle() != '' ) {
							echo '<h4>' . $intense_post_type->get_subtitle() . '</h4>';
						}
					?>			
				</div>
			</div>
			<div class='intense col-lg-4 col-md-4 col-sm-12 col-xs-12' style='text-align:center;'>
				<?php
					$image = get_field( 'intense_client_logo' );

					if( !empty( $image ) ) {
					?>
					 <img style='max-width:250px;' src='<?php echo $image["url"];?>' title='<?php echo the_title_attribute( 'echo=0' ); ?>' alt='' />
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
			<?php echo intense_get_post_thumbnails( 
				( isset( $intense_custom_post['image_size'] ) ? $intense_custom_post['image_size'] : 'postWide' ), 
				null, 
				false, 
				( $intense_custom_post['show_missing_image'] == 0 ? false : true ), 
				$intense_custom_post['image_shadow'], 
				$intense_custom_post['hover_effect'], 
				$intense_custom_post['hover_effect_color'], 
				$intense_custom_post['hover_effect_opacity'],
				$intense_custom_post['image_border_radius'],
				true ); ?>	
		</div>
	</div>
	<?php } ?>

	<!-- Content -->
	<div class='intense row' style='<?php echo $intense_custom_post['cancel_plugin_layout_style']; ?> padding-top: 10px;'>
		<div class='entry-content intense col-lg-12 col-md-12 col-sm-12 col-xs-12'>
			<?php echo do_shortcode( get_the_content() ); ?>
		</div>
	</div>

	<!-- Footer -->
	<footer style='padding-top: 5px;'>
		<div class='intense row' style='<?php echo $intense_custom_post['cancel_plugin_layout_style']; ?>'>
			<div class='intense col-lg-12 col-md-12 col-sm-12 col-xs-12' style='<?php echo $intense_custom_post['cancel_plugin_layout_style']; ?>'>
				<div class='intense col-lg-6 col-md-6 col-sm-12 col-xs-12' style='padding-left:0;'>
					<div class='entry-content intense col-lg-12 col-md-12 col-sm-12 col-xs-12'>
						<?php echo '<h3>' . __('Business Information', 'intense' ) . '</h3>'; ?>
					</div>
					<div class='entry-content intense col-lg-12 col-md-12 col-sm-12 col-xs-12'>
						<?php echo __('Business Sector', 'intense' ) ?>: <?php echo get_field( 'intense_client_sector' ); ?>
					</div>
					<div class='entry-content intense col-lg-12 col-md-12 col-sm-12 col-xs-12' style='padding-bottom:10px;'>
						<?php echo __('Business Size', 'intense' ) ?>: <?php echo get_field( 'intense_client_employees' ); ?>
					</div>
				</div>
				<div class='intense col-lg-6 col-md-6 col-sm-12 col-xs-12' style='padding-left:0;'>
					<div class='entry-content intense col-lg-12 col-md-12 col-sm-12 col-xs-12'>
						<?php echo '<h3>' . __('Contact Information', 'intense' ) . '</h3>'; ?>
					</div>
					<div class='entry-content intense col-lg-12 col-md-12 col-sm-12 col-xs-12'>
						<?php echo get_field( 'intense_client_contact' ); ?>
					</div>
					<div class='entry-content intense col-lg-12 col-md-12 col-sm-12 col-xs-12'>
						<?php echo get_field( 'intense_client_contact_email' ); ?>
					</div>
					<div class='entry-content intense col-lg-12 col-md-12 col-sm-12 col-xs-12'>
						<?php echo get_field( 'intense_client_contact_phone' ); ?>
					</div>
				</div>
			</div>
		</div>
	</footer>
	<?php echo $intense_custom_post['animation_wrapper_end']; ?>
</article>

<?php
	$i++;
	endwhile;

	do_action( 'intense_after_main_content' );

	get_footer( 'intense' );
?>

<?php
/*
Intense Template Name: One Column (text right)
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

$intense_post_type = $intense_post_types->get_post_type( 'intense_movies' );

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
		<div class='intense col-lg-3 col-md-3 col-sm-12 col-xs-12' style='padding:7px; '>
			<div style="padding-bottom:10px;">
				<?php
					$image = get_field( 'intense_movie_image' );

					if( !empty( $image ) ) {
					?>
					 <img style='width:100%;-webkit-box-shadow: 0 0 8px rgba(50,50,50,0.51); -moz-box-shadow: 0 0 8px rgba(50,50,50,0.51); box-shadow: 0 0 8px rgba(50,50,50,0.51);' src='<?php echo $image["url"];?>' title='<?php echo the_title_attribute( 'echo=0' ); ?>' alt='' />
				<?php } ?>
			</div>
			<div>
				<?php
				if ( get_field('intense_movie_trailer') != '' ) {
					$type = 'youtube';

				    if ( strpos( get_field('intense_movie_trailer'), 'youtube' ) > 0 ) {
				        $type = 'youtube';
				    } elseif ( strpos( get_field('intense_movie_trailer'), 'vimeo' ) > 0 ) {
				        $type = 'vimeo';
				    } elseif ( preg_match('/[A-Za-z0-9_]+/', get_field('intense_movie_trailer') ) ) {
				    	$typeid = preg_match('/[A-Z]+[a-z]+[0-9]+/', get_field('intense_movie_trailer') );

				    	if ( strlen( $typeid ) == 11 ) {
				        	$type = 'youtube';
				        } elseif ( is_numeric( get_field('intense_movie_trailer') ) ) {
				        	$type = 'vimeo';
				        }
				    }

					$video = intense_run_shortcode( 'intense_video', array( 
						'video_type' => $type,
			        	'video_url' => get_field('intense_movie_trailer')
			        ) );

			        echo intense_run_shortcode( 'intense_lightbox', array(
			        	'type' => 'magnificpopup',
			        	'content_type' => 'html',
			        	'html_element' => 'video_trailer' 
			        ), '<div class="btn"><i class="intense icon-play icon-2"></i> Play Trailer</div>' );

			        echo '<div id="video_trailer" style="width:800px; height:450px;">' . $video . '</div>';
				}
				?>
			</div>
		</div>
		<div class='intense col-lg-9 col-md-9 col-sm-12 col-xs-12'>
			<div class='post-header'>
				<h1 class='entry-title'><?php echo the_title_attribute( 'echo=0' ); ?></h1>
				<?php
					if ( get_field( 'intense_movie_subtitle' ) != '' ) {
						echo '<h3>' . get_field( 'intense_movie_subtitle' ) . '</h3>';
					}
				?>
			</div>
			<div class='post-header' style='padding-top:10px;'>
				<div style='display:inline;'>
				<?php
					if ( get_field( 'intense_movie_release_date' ) != '' ) {
						echo date("Y", strtotime( get_field( 'intense_movie_release_date' ) ) );
					}
					if ( get_field( 'intense_movie_rating' ) != '' ) {
						echo '<span style="background-color:#ccc; padding:2px 3px; margin: 0 10px;">' . get_field( 'intense_movie_rating' ) . '</span>';
					}
					if ( get_field( 'intense_movie_runtime' ) != '' ) {
						echo intense_convert_minutes_to_hours( get_field( 'intense_movie_runtime' ) );
					}
				?>
				</div>
			</div>
			<div class='intense row' style='margin: 0; padding-top: 10px;'>
				<?php
					$cast = get_the_term_list( $post->ID, 'intense_movie_cast', '', ', ', '' );
					
					if ( $cast != '' ) {
						echo "<h3 style='margin-bottom:5px; margin-top:5px;'>" . __( 'Cast', 'intense' ) . "</h3>";
						echo $cast;
					}

					$directors = get_the_term_list( $post->ID, 'intense_movie_director', '', ', ', '' );
					
					if ( $directors != '' ) {
						echo "<h3 style='margin-bottom:5px; margin-top:10px;'>" . __( 'Director', 'intense' ) . "</h3>";
						echo $directors;
					}

					$genres = get_the_term_list( $post->ID, 'intense_movie_genre', '', ', ', '' );
					
					if ( $genres != '' ) {
						echo "<h3 style='margin-bottom:5px; margin-top:10px;'>" . __( 'Genre', 'intense' ) . "</h3>";
						echo $genres;
					}
				?>
			</div>
			<div class='intense row' style='margin: 0; padding-top: 10px;'>
				<div class='entry-content'>
					<?php echo __('Theatrical Release Date', 'intense' ) ?>: <?php echo date("M d, Y", strtotime( get_field( 'intense_movie_release_date' ) ) ); ?>
				</div>
				<div class='entry-content'>
					<?php echo __('DVD Release Date', 'intense' ) ?>: <?php echo date("M d, Y", strtotime( get_field( 'intense_movie_dvd_release_date' ) ) ); ?>
				</div>
				<?php
					if ( get_field( 'intense_movie_website' ) != '' ) { ?>
						<div class='entry-content'>
							<?php echo __('Website', 'intense' ) ?>: <?php echo '<a href="' . get_field( 'intense_movie_website' ) . '" target="_blank">' . the_title_attribute( 'echo=0' ) . '</a>'; ?>
						</div>
				<?php } ?>
				<?php
					if ( get_field( 'intense_movie_purchase_link' ) != '' ) { ?>
						<div class='entry-content' style='padding-top:10px;'>
							<?php echo intense_run_shortcode( 'intense_button', array( 'size' => 'small', 'color' => 'primary', 'link' => get_field( 'intense_movie_purchase_link' ), 'icon' => 'angle-right', 'icon_position' => 'right' ), __( 'Purchase', 'intense' ) ); ?>
						</div>
				<?php } ?>
			</div>
		</div>
	</div>
 	<div class='intense row' style='<?php echo $intense_custom_post['cancel_plugin_layout_style']; ?> padding-top: 10px;'>
		<div class='entry-content intense col-lg-12 col-md-12 col-sm-12 col-xs-12' style='margin: 0; padding-top: 10px;'>
			<?php echo do_shortcode( get_the_content() ); ?>
		</div>
	</div>
 	<div class='intense row' style='<?php echo $intense_custom_post['cancel_plugin_layout_style']; ?> padding-top: 10px;'>
		<div class='entry-content intense col-lg-12 col-md-12 col-sm-12 col-xs-12'>
		<?php			
			$rows = get_field('intense_movie_reviews');

			if( $rows )
			{ ?>
				<h2><?php echo  __('Reviews', 'intense' ) ?>:</h2>
				<ol>
			 	
			 	<?php foreach( $rows as $row ) { 
					$review = $row['intense_movie_review'];
			 	?>
					<li><?php echo $review ?></li>
				<?php } ?>
			 
				</ol>
			<?php } ?>
		</div>
	</div>

	<!-- Footer -->
	<footer style='padding-top: 5px;'>

	</footer>
	<?php echo $intense_custom_post['animation_wrapper_end']; ?>
</article>

<?php
	$i++;
	endwhile;

do_action( 'intense_after_main_content' );

get_footer( 'intense' );

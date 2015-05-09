<?php
/*
Intense Template Name: One Column (text right)
*/

require_once 'helper/helper_top.php';

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

	<div class='intense row' style='padding-top:20px; <?php echo $intense_custom_post['cancel_plugin_layout_style']; ?>'>
		<div class='intense col-lg-3 col-md-3 col-sm-12 col-xs-12'>
			<div style="padding-bottom:10px;">
				<?php
					$image = get_field( 'intense_book_image' );

					if( !empty( $image ) ) {
					?>
					 <img style='width:100%; -webkit-box-shadow: 0 0 8px rgba(50,50,50,0.51); -moz-box-shadow: 0 0 8px rgba(50,50,50,0.51); box-shadow: 0 0 8px rgba(50,50,50,0.51);' src='<?php echo $image["url"];?>' title='<?php echo the_title_attribute( 'echo=0' ); ?>' alt='' />
				<?php } ?>
			</div>
			<div>
				<?php
				if ( get_field('intense_book_audio_clip') != '' ) {
					echo intense_run_shortcode( 'intense_audio', array( 
			          'url' => get_field('intense_book_audio_clip')
			        ) );
				}
				?>
			</div>
		</div>
		<div class='intense col-lg-9 col-md-9 col-sm-12 col-xs-12'>
			<div class='post-header'>
				<h1 class='entry-title' style='margin:0px;'><?php echo the_title_attribute( 'echo=0' ); ?></h1>
				<?php
					if ( get_field( 'intense_book_subtitle' ) != '' ) {
						echo '<h3 style="margin:0px;">' . get_field( 'intense_book_subtitle' ) . '</h3>';
					}
				?>
				<h4 style="margin:0px; padding-top:5px;"><?php echo __('by', 'intense' ) ?>: <?php echo get_the_term_list( $post->ID, 'intense_book_author', '', ', ', '' ); ?></h4>
			</div>
			<div class='intense row' style='margin: 0; padding-top: 20px;'>
				<div class='entry-content'>
					<?php echo __('Published By', 'intense' ) ?>: <?php echo get_the_term_list( $post->ID, 'intense_book_publisher', '', ', ', '' ); ?>
				</div>
				<div class='entry-content'>
					<?php echo __('Category(s)', 'intense' ) ?>: <?php echo get_the_term_list( $post->ID, 'intense_book_category', '', ', ', '' ); ?>
				</div>
				<div class='entry-content'>
					<?php echo __('Release Date', 'intense' ) ?>: <?php echo date("M d, Y", strtotime( get_field( 'intense_book_release_date' ) ) ); ?>
				</div>
				<div class='entry-content'>
					<?php echo __('Recommended Age', 'intense' ) ?>: <?php echo get_field( 'intense_book_recommended_age' ); ?>
				</div>
				<div class='entry-content'>
					<?php echo __('Type(s)', 'intense' ) ?>: <?php echo implode( ', ', get_field( 'intense_book_types' ) ); ?>
				</div>
				<?php
					if ( get_field( 'intense_book_website' ) != '' ) { ?>
						<div class='entry-content'>
							<?php echo __('Website', 'intense' ) ?>: <?php echo '<a href="' . get_field( 'intense_book_website' ) . '" target="_blank">' . the_title_attribute( 'echo=0' ) . '</a>'; ?>
						</div>
				<?php } ?>
				<?php
					if ( get_field( 'intense_book_purchase_link' ) != '' ) { ?>
						<div class='entry-content' style='padding-top:10px;'>
							<?php echo intense_run_shortcode( 'intense_button', array( 'size' => 'small', 'color' => 'primary', 'link' => get_field( 'intense_book_purchase_link' ), 'icon' => 'angle-right', 'icon_position' => 'right' ), __( 'Purchase', 'intense' ) ); ?>
						</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<div class='intense row' style='<?php echo $intense_custom_post['cancel_plugin_layout_style']; ?> padding-top: 20px;'>
		<div class='entry-content intense col-lg-12 col-md-12 col-sm-12 col-xs-12'>
			<?php echo do_shortcode( get_the_content() ); ?>
		</div>
	</div>
 	<div class='intense row' style='<?php echo $intense_custom_post['cancel_plugin_layout_style']; ?> padding-top: 10px;'>
		<div class='entry-content intense col-lg-12 col-md-12 col-sm-12 col-xs-12'>
			<?php
			if ( get_field('intense_book_excerpt') != '' ) {
				echo '<h3>' . __('Excerpt', 'intense' ) . '</h3>' . get_field('intense_book_excerpt');
			}
			?>
		</div>
	</div>
	<div class='intense row' style='<?php echo $intense_custom_post['cancel_plugin_layout_style']; ?> padding-top: 10px;'>
		<div class='entry-content intense col-lg-12 col-md-12 col-sm-12 col-xs-12'>
			<?php			

			$rows = get_field('intense_book_awards');

			if( $rows )
			{ ?>
				<h2><?php echo  __('Awards', 'intense' ) ?>:</h2>

			 	<?php foreach( $rows as $row ) { ?>
					<?php echo '<b>' . $row['intense_book_award_name'] . '</b><br />' . $row['intense_book_award_description'] . '<br /><br />'; ?>
				<?php } ?>
			 
			<?php }
		?>
		</div>
	</div>
 	<div class='intense row' style='<?php echo $intense_custom_post['cancel_plugin_layout_style']; ?> padding-top: 10px;'>
		<div class='entry-content intense col-lg-12 col-md-12 col-sm-12 col-xs-12'>
			<?php
			if ( get_field('intense_book_back_cover') != '' ) {
				echo '<h3>' . __('Back Cover Text', 'intense' ) . '</h3>' . get_field('intense_book_back_cover');
			}
			?>
		</div>
	</div>
 	<div class='intense row' style='<?php echo $intense_custom_post['cancel_plugin_layout_style']; ?> padding-top: 10px;'>
		<div class='entry-content intense col-lg-12 col-md-12 col-sm-12 col-xs-12'>
		<?php			
			$rows = get_field('intense_book_reviews');

			if( $rows )
			{ ?>
				<h2><?php echo  __('Reviews', 'intense' ) ?>:</h2>
				<ol>
			 	
			 	<?php foreach( $rows as $row ) { 
					$review = $row['intense_book_review'];
			 	?>
					<li><?php echo $review ?></li>
				<?php } ?>
			 
				</ol>
			<?php } ?>
		</div>
	</div>

	<?php echo $intense_custom_post['animation_wrapper_end']; ?>
</article>

<?php
	$i++;
	endwhile;

require_once 'helper/helper_bottom.php';

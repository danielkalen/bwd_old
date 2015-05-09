<?php
/*
Intense Template Name: One Column (text left)
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

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-content">
		<h1 class="entry-title"><?php echo (!$additional_title ? the_title() : $additional_title); ?> <?php edit_post_link( __( 'Edit', 'intense' ), '<span class="btn btn-mini btn-inverse">', '</span>' ); ?></h1>
		<?php
			if ( get_field( 'intense_portfolio_subtitle' ) != '' ) {
				echo "<h4 style='font-style:italic;'>" . get_field( 'intense_portfolio_subtitle' ) . "</h4>";
			}
		?>
		<div class="intense row" style="padding-top: 0;">
			<div class="intense col-lg-4 col-xs-12 col-sm-12 col-md-4">
				<?php
					$categories = wp_get_post_terms( $post->ID, 'portfolio_category', array("fields" => "all") );
					
					if ( $categories ) {
						echo "<h3>" . _n( 'Category', 'Categories', count( $categories ), 'intense' ) . "</h3>";

						$category_list = array();

						foreach ( $categories as $category ) {
							$category_list[] = $category->name;
						}

						echo join( ', ', $category_list );
					}

					$skills = wp_get_post_terms( $post->ID, 'portfolio_skills', array("fields" => "all") );
					
					if ( $skills ) {
						echo "<h3>" . _n( 'Skill', 'Skills', count( $skills ), 'intense' ) . "</h3>";

						$skill_list = array();

						foreach ( $skills as $skill ) {
							$skill_list[] = $skill->name;
						}

						echo join( ', ', $skill_list );
					}

					echo "<hr>";

					if ( get_field( 'intense_designed_by' ) != '' ) {
						echo __( 'Designed By', 'intense' ) . ": " . get_field( 'intense_designed_by' ) . "<br />";
					}

					if ( get_field( 'intense_built_by' ) != '' ) {
						echo __( 'Built By', 'intense' ) . ": " . get_field( 'intense_built_by' ) . "<br />";
					}

					if ( get_field( 'intense_produced_by' ) != '' ) {
						echo __( 'Produced By', 'intense' ) . ": " . get_field( 'intense_produced_by' ) . "<br />";						
					}

					// if ( get_field( 'intense_portfolio_date' ) != '' ) {
					// 	echo __( 'Completion Date', 'intense' ) . ": " . date("M d, Y", strtotime( get_field( 'intense_portfolio_date' ) ) );
					// }

					if ( get_field( 'intense_year_completed' ) != '' ) {
						echo __( 'Year Completed', 'intense' ) . ": " . get_field( 'intense_year_completed' ) . "<br />";						
					}
				?>
			</div>
			<div class="intense col-lg-8 col-xs-12 col-sm-12 col-md-8">
				<?php echo intense_get_post_thumbnails('medium800'); ?>
			</div>			
		</div>		
		<hr />
		<?php echo do_shortcode( get_the_content() ); ?>
	</div>
</article>

<?php
	$i++;
	endwhile;

require_once 'helper/helper_bottom.php';

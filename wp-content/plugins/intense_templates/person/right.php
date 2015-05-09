<?php
/*
Intense Template Name: Image on Right
*/

global $intense_person;

?>
<div class="intense person <?php echo ( $intense_person['rtl'] ? 'rtl ' : '' ); ?> ">
	<div style="width: 25%;" class="pull-right">
		<?php 
		echo intense_run_shortcode( 'intense_image', array( 
				'imageid' => $intense_person['imageid'],
				'imageurl' => $intense_person['imageurl'],
				'class' => 'person_img',
				'border_radius' => $intense_person['border_radius'],
				'alt' => $intense_person['name'],
				'size' => $intense_person['size'],
				'shadow' => $intense_person['imageshadow']
			) );
		?>
	</div>
	<div class="person_desc pull-left" style="width: 73%;">
		<div class="person_author clearfix">
			<div class="pull-<?php echo ( $intense_person['rtl'] ? 'left' : 'right' ); ?>" style="height: 28px; text-align: right; padding-top: 10px;">
			<?php
				if ( $intense_person['facebook'] != '' ) {
					echo intense_run_shortcode( 'intense_social_icon', array(
				        'type' => "facebook",
				        'label' => "Facebook",
				        'mode' => "fontawesome",
				        'link' => $intense_person['facebook'],
				        'size' => $intense_person['social_size'],
				        'link_target' => $intense_person['social_target'] 
				      ) );					
				}
				if ( $intense_person['googleplus'] != '' ) {
					echo intense_run_shortcode( 'intense_social_icon', array(
				        'type' => "google-plus",
				        'label' => "Google+",
				        'mode' => "fontawesome",
				        'link' => $intense_person['googleplus'],
				        'size' => $intense_person['social_size'],
				        'link_target' => $intense_person['social_target'] 
				      ) );					
				}
				if ( $intense_person['twitter'] != '' ) {
					echo intense_run_shortcode( 'intense_social_icon', array(
				        'type' => "twitter",
				        'label' => "Twitter",
				        'mode' => "fontawesome",
				        'link' => $intense_person['twitter'],
				        'size' => $intense_person['social_size'],
				        'link_target' => $intense_person['social_target'] 
				      ) );					
				}
				if ( $intense_person['linkedin'] != '' ) {
					echo intense_run_shortcode( 'intense_social_icon', array(
				        'type' => "linkedin",
				        'label' => "LinkedIn",
				        'mode' => "fontawesome",
				        'link' => $intense_person['linkedin'],
				        'size' => $intense_person['social_size'],
				        'link_target' => $intense_person['social_target'] 
				      ) );
				}
				if ( $intense_person['dribbble'] != '' ) {
					echo intense_run_shortcode( 'intense_social_icon', array(
				        'type' => "dribbble",
				        'label' => "Dribbble",
				        'mode' => "fontawesome",
				        'link' => $intense_person['dribbble'],
				        'size' => $intense_person['social_size'],
				        'link_target' => $intense_person['social_target'] 
				      ) );
				}
				if ( !empty( $intense_person['custom_social'] ) ) {
					foreach ( $intense_person['custom_social'] as $key => $value ) {
						if ( !empty( $value['image'] ) && !empty( $value['title'] ) && !empty( $value['link'] ) ) {
							echo intense_run_shortcode( 'intense_social_icon', array(
						        'imageurl' => $value['image'],
						        'label' => $value['title'],
						        'mode' => "custom",
						        'link' => $value['link'],
						        'size' => $intense_person['social_size'],
						        'link_target' => $intense_person['social_target'] 
						      ) );
						}
					}
				}
			?>
			</div>
		
			<div class="person_author_wrapper">
				<h3 class="person-title"><?php echo  $intense_person['name']; ?></h3>
				<div class="entry-content"><?php echo  $intense_person['title']; ?></div>
			</div>
		</div>
		<div class="entry_content">
			<?php echo $intense_person['content']; ?>
		</div>
	</div>
	<div class="clearfix"></div>
</div>

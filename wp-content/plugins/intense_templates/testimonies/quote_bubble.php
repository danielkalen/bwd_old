<?php
/*
Intense Template Name: Quote Bubble
*/

global $intense_testimony;

$random_ID = rand();

if ( !empty( $intense_testimony['image'] ) ) {
	$image = intense_run_shortcode( 'intense_image', array( 
		'image' => $intense_testimony['image'],
		'size' => 'square75',
		'alt' => ( !empty( $intense_testimony['company'] ) ? esc_attr( $intense_testimony['company'] ) : '' )
	) );

	$intense_testimony['image'] = $image;	
} else {
	$intense_testimony['image'] = intense_run_shortcode( 'intense_icon', array( 'type' => 'user', 'size' => '2' ) );
}

?>
<div class="testimony">
<?php
if ( isset( $intense_testimony['background'] ) || isset( $intense_testimony['font_color'] ) ) {
	echo "<style>";

	if ( isset( $intense_testimony['background'] ) ) {
	  echo "#testimonial_" . $random_ID . " { background-color:" . $intense_testimony['background']  . " !important; } #testimonial_" . $random_ID . ":after { border-right-color: " . $intense_testimony['background']  . " !important; }";
	}

	if ( isset( $intense_testimony['font_color'] ) ) {
	  echo "#testimonial_" . $random_ID . " { color:" . $intense_testimony['font_color'] . " !important; } #testimonial_" . $random_ID . " .quotes { color:" . intense_get_rgb_color( $intense_testimony['font_color'], 50) . " !important; }";
	}

	echo "</style>";
}

?>
	<div class="content" id="testimonial_<?php echo $random_ID ?>">
		<?php echo do_shortcode( $intense_testimony['content'] ); ?>
	</div>
	<div class="author">
		<?php echo $intense_testimony['image']; ?>
		
		<?php if ( !empty( $intense_testimony['company'] ) ) { ?>
				<?php echo $intense_testimony['author']; ?><span class="company">,
				<?php if ( !empty( $intense_testimony['link'] ) ) { ?>
					<a href="<?php echo $intense_testimony['link']; ?>" target='<?php echo $intense_testimony['link_target']; ?>'>
				<?php } ?>
				<?php echo $intense_testimony['company']; ?>
				<?php if ( !empty( $intense_testimony['link'] ) ) { ?>
					</a>
				<?php } ?>
				</span>
		<?php } else { ?>
			<?php echo $intense_testimony['author']; ?>
		<?php } ?>
	</div>
</div>

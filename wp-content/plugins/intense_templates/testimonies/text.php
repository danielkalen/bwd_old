<?php
/*
Intense Template Name: Text 
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
} 

?>
<div class="testimony">
<?php
echo "<style>";

if ( isset( $intense_testimony['background'] ) || isset( $intense_testimony['font_color'] ) ) {
	if ( isset( $intense_testimony['background'] ) ) {
	  echo "#testimonial_" . $random_ID . " { padding: 10px; background-color:" . $intense_testimony['background']  . " !important; } #testimonial_" . $random_ID . ":after { border-right-color: " . $intense_testimony['background']  . " !important; }";
	}

	if ( isset( $intense_testimony['font_color'] ) ) {
	  echo "#testimonial_" . $random_ID . " { color:" . $intense_testimony['font_color'] . " !important; } #testimonial_" . $random_ID . " .quotes { color:" . intense_get_rgb_color( $intense_testimony['font_color'], 50) . " !important; }";
	}	
}
echo "#testimonial_" . $random_ID . " img { border-radius: 50%; } ";
echo "</style>";

$quoted_content = trim( $intense_testimony['content'] );
$quoted_content = rtrim( ltrim( $quoted_content, "<p>" ), '</p>' );
$quoted_content = '<p style="margin-top: 0;"><sup>' . intense_run_shortcode( 'intense_icon', array( 'type' => 'quote-left') ) . '</sup> ' . $quoted_content . ' <sup>' . intense_run_shortcode( 'intense_icon', array( 'type' => 'quote-right') ) . '</sup></p>';

?>
	<div class="text-content" id="testimonial_<?php echo $random_ID ?>">
		<?php if ( !empty( $intense_testimony['image'] ) ) { ?>
		<div class="pull-left" style="border-radius: 50%; width: 50px; height: 50px; display: inline-block; margin: 0 10px 0 0;">
			<?php echo $intense_testimony['image']; ?>
		</div>
		<?php } ?>		
		<?php echo do_shortcode( $quoted_content ); ?>		
		<div class="clearfix"></div>
		<div class="pull-right" style="font-size: smaller; padding-top: 5px;">
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
		<div class="clearfix"></div>
	</div>	
</div>

<?php
/*
Intense Template Name: Text No Photo
*/

global $intense_testimony;

$random_ID = rand();

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

echo "</style>";

?>
	<div class="text-content" id="testimonial_<?php echo $random_ID ?>">		
		<small><?php echo intense_run_shortcode( 'intense_icon', array( 'type' => 'quote-left') ); ?></small>
		<?php echo do_shortcode( $intense_testimony['content'] ); ?>
		<small><?php echo intense_run_shortcode( 'intense_icon', array( 'type' => 'quote-right') ); ?></small>
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

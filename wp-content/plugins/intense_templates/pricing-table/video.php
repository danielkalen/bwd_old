<?php
/*
Intense Template Name: Video
*/

global $intense_pricing_section;

$font_color_css = $intense_pricing_section['font_color_css'];
$background_color_css = $intense_pricing_section['background_color_css'];

if ( empty( $intense_pricing_section['border_css'] ) ) {
	$borders = ' border-left: 1px solid #EBEBEB; border-right: 1px solid #EBEBEB;';
} else {
	$borders = $intense_pricing_section['border_css'];
}

echo '<style>.pricing-table-video > div[id^=\'video-container\'] { padding-top: 0 !important;  }</style>';
?>

<div class="intense pricing-table-section pricing-table-video" style="<?php echo $background_color_css . $font_color_css . $borders; ?> text-align: center;">
	<?php echo do_shortcode( $intense_pricing_section['content'] ); ?>
</div>
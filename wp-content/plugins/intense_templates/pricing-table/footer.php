<?php
/*
Intense Template Name: Footer
*/

global $intense_pricing_section;

$font_color_css = $intense_pricing_section['font_color_css'];
$background_color_css = $intense_pricing_section['background_color_css'];
$background_color = $intense_pricing_section['background_color'];
$padding = ' padding: 10px;';

if ( empty( $intense_pricing_section['border_css'] ) ) {
	$borders = ' border-left: 1px solid #EBEBEB; border-right: 1px solid #EBEBEB; border-bottom: 1px solid #EBEBEB;';
} else {
	$borders = $intense_pricing_section['border_css'];
}

if ( $intense_pricing_section['is_featured'])  $padding = ' padding-bottom: 25px; padding-top: 25px;';

?>

<div class="intense pricing-table-section pricing-table-footer" style="<?php echo $background_color_css . $font_color_css . $borders . $padding; ?> text-align: center;">
	<?php echo do_shortcode( $intense_pricing_section['content'] ); ?>
</div>
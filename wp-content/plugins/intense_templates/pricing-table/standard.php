<?php
/*
Intense Template Name: Standard
*/

global $intense_pricing_section;

$frequency = $intense_pricing_section['frequency'];
$amount = $intense_pricing_section['amount'];
$sale_amount = $intense_pricing_section['sale_amount'];
$font_color_css = $intense_pricing_section['font_color_css'];
$background_color_css = $intense_pricing_section['background_color_css'];

if ( empty( $intense_pricing_section['border_css'] ) ) {
	$borders = ' border-left: 1px solid #EBEBEB; border-right: 1px solid #EBEBEB; border-top: 1px solid #fff;';
} else {
	$borders = $intense_pricing_section['border_css'];
}

$frequency = ( !empty( $frequency ) ? '/' . $frequency : '' );

if ( !empty( $sale_amount ) && !empty( $amount ) ) {
    $amount = '<strike>'. $amount . $frequency . '</strike> ' . $sale_amount . $frequency;
} else {
    $amount = $amount . $frequency;
}

$padding = ' padding: 7px;';

?>

<div class="intense pricing-table-section pricing-table-standard" style="<?php echo $background_color_css . $font_color_css . $borders . $padding; ?> text-align: center;">
	<span style="<?php echo $font_color_css; ?>"><?php echo $intense_pricing_section['title'] . $amount; ?></span>
	<?php echo do_shortcode( $intense_pricing_section['content'] ); ?>
</div>
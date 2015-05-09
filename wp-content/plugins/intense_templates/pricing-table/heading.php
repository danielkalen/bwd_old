<?php
/*
Intense Template Name: Heading
*/

global $intense_pricing_section;

$frequency = $intense_pricing_section['frequency'];
$amount = $intense_pricing_section['amount'];
$sale_amount = $intense_pricing_section['sale_amount'];
$font_color_css = $intense_pricing_section['font_color_css'];
$background_color_css = $intense_pricing_section['background_color_css'];
$background_color = $intense_pricing_section['background_color'];

$content = $intense_pricing_section['content'];

if ( empty( $intense_pricing_section['border_css'] ) ) {
	$borders = ' border-left: 1px solid ' . $background_color  . '; border-right: 1px solid ' . $background_color  . ';';
} else {
	$borders = $intense_pricing_section['border_css'];
}

$frequency = ( !empty( $frequency ) ? '/' . $frequency : '' );

if ( !empty( $sale_amount ) && !empty( $amount ) ) {
    $amount = '<strike>'. $amount . $frequency . '</strike> ' . $sale_amount . $frequency;
} else {
    $amount = $amount . $frequency;
}

?>

<div class="intense pricing-table-section pricing-table-heading" style="<?php echo $background_color_css . $font_color_css . $borders; ?> padding:20px; text-align: center;">
	<h2 style="<?php echo $font_color_css; ?>"><?php echo $intense_pricing_section['title']; ?></h2>
	<?php echo $amount; ?>
	<?php echo do_shortcode( $content ); ?>
</div>
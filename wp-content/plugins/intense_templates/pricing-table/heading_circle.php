<?php
/*
Intense Template Name: Heading (circle)
*/

global $intense_pricing_section;

$amount = $intense_pricing_section['amount'];
$sale_amount = $intense_pricing_section['sale_amount'];
$font_color_css = $intense_pricing_section['font_color_css'];
$background_color_css = $intense_pricing_section['background_color_css'];
$background_color = $intense_pricing_section['background_color'];
$circle_color = $intense_pricing_section['font_color'];
$circle_font_color = intense_get_contract_color( $circle_color );

if ( empty( $intense_pricing_section['border_css'] ) ) {
	$borders = ' border-left: 1px solid ' . $background_color  . '; border-right: 1px solid ' . $background_color  . ';';
} else {
	$borders = $intense_pricing_section['border_css'];
}

$under_circle_color = intense_adjust_color_brightness( $background_color, -30 );

$amount_padding_top = "35px";

if ( !empty( $sale_amount ) && !empty( $amount ) ) {
     $amount = '<strike style="font-size: smaller; font-weight: 300;">'. $amount . '</strike> ' . $sale_amount;
     $amount_padding_top = "20px";
 }

?>

<div class="intense pricing-table-section pricing-table-heading_circle" style="<?php echo $background_color_css . $font_color_css . $borders; ?> padding:20px; text-align: center;">
	<h2 style="<?php echo $font_color_css; ?>"><?php echo $intense_pricing_section['title']; ?></h2>
	<div style="width: 100px; height: 100px; border-radius: 50%; background: <?php echo $circle_color; ?>; margin: 0 auto; position: relative; box-shadow: 2px 2px 5px #666 inset;">
		<h2 style="font-size: 20px; line-height: 22px; padding-top: <?php echo $amount_padding_top; ?>; line-height: auto; font-weight: bold; padding-bottom: 0; margin-bottom: 0; color: <?php echo $circle_font_color; ?>"><?php echo $amount; ?></h2>
		<h5 style="font-size: 12px; padding-top: 0; margin-top: 0; line-height: auto; color: <?php echo $circle_font_color; ?>"><?php echo $intense_pricing_section['frequency']; ?></h5>
	</div>
	<div style="background: <?php echo $under_circle_color; ?>; height: 70px; margin: -50px -21px -20px;">
	</div>
	<?php echo do_shortcode( $intense_pricing_section['content'] ); ?>
</div>
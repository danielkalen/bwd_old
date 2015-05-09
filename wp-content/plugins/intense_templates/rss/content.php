<?php
/*
Intense Template Name: Content
*/

global $rss_item; //see http://simplepie.org/wiki/reference/start#simplepie_item

$output = '<a href="' . esc_url( $rss_item->get_permalink() ) . '" title="' . sprintf( __( 'Posted %s', 'intense' ), $rss_item->get_date('j F Y | g:i a') ) . '">';
$output .= $rss_item->get_content();               
$output .= '</a>';

$output .= intense_run_shortcode( 'intense_spacer', array( 'height' => '25' ) );

echo $output;
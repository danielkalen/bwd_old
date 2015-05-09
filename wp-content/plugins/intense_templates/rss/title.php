<?php
/*
Intense Template Name: Title Only
*/

global $rss_item; //see http://simplepie.org/wiki/reference/start#simplepie_item

$output = '<a href="' . esc_url( $rss_item->get_permalink() ) . '" title="' . sprintf( __( 'Posted %s', 'intense' ), $rss_item->get_date('j F Y | g:i a') ) . '">';
$output .= esc_html( $rss_item->get_title() );                
$output .= '</a>';

echo $output;
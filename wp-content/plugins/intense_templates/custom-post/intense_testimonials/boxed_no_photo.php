<?php
/*
Intense Template Name: Boxed - No Photo
*/

$intense_testimony = array(
	'author' => get_field( 'intense_testimonial_author' ),
	'company' => get_field( 'intense_testimonial_company' ),
	'link' => get_field( 'intense_testimonial_link' ),
	'link_target' => get_field( 'intense_testimonial_link_target' ),
	'image' => get_field( 'intense_testimonial_author_image' ),
	'background' => get_field( 'intense_testimonial_background' ),
	'font_color' => get_field( 'intense_testimonial_font_color' ),
	'content' => intense_content( 35 ),
);

echo do_shortcode('[intense_testimonies source="manual" template="boxed_no_photo"]
	[intense_testimony 
	author="' . $intense_testimony['author'] . '" 
	company="' . $intense_testimony['company'] . '" 
	link="' . $intense_testimony['link'] . '" 
	link_target="' . $intense_testimony['link_target'] . '" 
	image="' . $intense_testimony['image'] . '" 
	background="' . $intense_testimony['background'] . '" 
	font_color="' . $intense_testimony['font_color'] . '"]
	' . $intense_testimony['content'] . '
	[/intense_testimony]
	[/intense_testimonies]');
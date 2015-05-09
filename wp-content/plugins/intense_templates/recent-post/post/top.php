<?php
/*
Intense Template Name: Image on Top
*/

global $intense_recent_post, $post;

$intense_post_type = $intense_recent_post['post_type'];
?>

<article class='recent-post'>

<?php
if ( $intense_recent_post['show_thumbnail'] ) { 
?>
    <a href="<?php echo get_permalink( get_the_ID() ) ?>"><?php echo intense_get_post_thumbnails( $intense_recent_post['image_size'], null, $intense_recent_post['layout'] == 'slider' , $intense_recent_post['show_missing_image'], null, null, null, null, $intense_recent_post['border_radius'] ) ?></a>
<?php
}

if ( $intense_recent_post['show_title'] ) {
?>
    <h5><a href="<?php echo get_permalink( get_the_ID() ) ?>"><?php echo get_the_title() ?></a></h5>
<?php
}

if ( $intense_recent_post['show_meta'] ) {
?>
    <div class='entry-meta'>
	<?php
    echo sprintf( '<time class="entry-date" datetime="%1$s">%2$s</time>', esc_attr( get_the_date( 'c' ) ), esc_html( get_the_date() ) );

    if ( get_comments_number( get_the_ID() ) >= 1 ) {
    ?>
         | <a href="<?php echo get_permalink( get_the_ID() ) ?>"><?php echo get_comments_number( get_the_ID() ) . ' ' . __( 'Comments', 'intense' ) ?></a>
	<?php
    } else {
	?>
         | 0 <?php echo __( 'Comments', 'intense' ) ?>
	<?php
    }
	?>
    </div>
<?php
}

if ( $intense_recent_post['show_excerpt'] ) {
?>
    <p class="excerpt"><?php echo $intense_post_type->get_excerpt( $intense_recent_post['excerpt_length'] ) ?></p>
<?php
}
?>
</article>

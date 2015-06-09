<?php
/**
 * The template part for displaying results in search pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Snapmagnet
 */
global $product;
?>

<article id="post-<?php the_ID(); ?>" class="results-item">
	<div class="results-item-img">
		<?php
			if ( has_post_thumbnail() ) {
				the_post_thumbnail('thumbnail');
			} else {
				echo wc_placeholder_img('thumbnail');
			}
		?>
	</div>
	<div class="results-item-content">
		<a href="<?php esc_url(get_permalink()) ?>" class="results-item-content-title"><?php the_title(); ?></a>
		<div class="results-item-content-price"><?php echo $product->get_price_html() ?></div>
	</div>
</article><!-- #post-## -->

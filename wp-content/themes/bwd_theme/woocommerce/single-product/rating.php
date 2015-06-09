<?php
/**
 * Single Product Rating
 *
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     2.3.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

if ( get_option( 'woocommerce_enable_review_rating' ) === 'no' ) {
	return;
}

$rating_count = $product->get_rating_count();
$review_count = $product->get_review_count();
$average      = $product->get_average_rating();

// if ( $rating_count > 0 ) : ?>

	<div class="single-product-content-summary-rating" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
		<div class="star-rating" title="<?php printf( __( 'Rated %s out of 5', 'woocommerce' ), $average ); ?>">
			<span class="star-rating-active" style="width:<?php echo ( ( $average / 5 ) * 100 ); ?>%">
				<strong itemprop="ratingValue" class="rating-text"><?php echo esc_html( $average ); ?></strong> <?php _e( 'out of 5', 'woocommerce' ); ?>
			</span>
		</div>
		<a href="#reviews" class="single-product-content-summary-rating-link" rel="nofollow"><?php printf( _n( '%s Review', '%s Reviews', $rating_count, 'woocommerce' ), '<span itemprop="ratingCount" class="count">' . $rating_count . '</span>' ); ?></a>
	</div>

	<div class="single-product-content-summary-social">
			<span class="single-product-content-summary-social-text">Share</span>
			<a class="single-product-content-summary-social-item" title="Twitter Icon" target="_blank" href="https://twitter.com/home?status=<?php the_title(); ?>, <?php the_content(); ?>  -  <?php echo get_permalink(); ?>" id="twitter"></a>
			<a class="single-product-content-summary-social-item" title="Facebook Icon" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo get_permalink(); ?>" id="facebook"></a>
			<a class="single-product-content-summary-social-item" title="Pinterest Icon" target="_blank" href="https://pinterest.com/pin/create/button/?url=<?php echo get_permalink(); ?>&media=<?php echo $image_link; ?>&description=<?php the_title(); ?>, <?php the_content(); ?>" id="pinterest"></a>
			<a class="single-product-content-summary-social-item" title="Google+ Icon" target="_blank" href="https://plus.google.com/share?url=<?php echo get_permalink(); ?>" id="googleplus"></a>
	</div>

	<div class="clearfix"></div>
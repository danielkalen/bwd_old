<?php global $post, $product, $woocommerce; ?>

<?php
if ( get_option( 'woocommerce_enable_review_rating' ) === 'no' )
	return;

$count   = $product->get_rating_count();
$average = $product->get_average_rating();

//if ( $count > 0 ) : ?>

	<div class="woocommerce-product-rating" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
		<div class="star-rating" title="<?php printf( __( 'Rated %s out of 5', 'woocommerce' ), $average ); ?>">
			<span style="width:<?php echo ( ( $average / 5 ) * 100 ); ?>%">
				<strong itemprop="ratingValue" class="rating"><?php echo esc_html( $average ); ?></strong> <?php _e( 'out of 5', 'woocommerce' ); ?>
			</span>
		</div>
		<a href="#" class="woocommerce-review-link" rel="nofollow"><?php printf( _n( '%s review', '%s reviews', $count, 'woocommerce' ), '<span itemprop="ratingCount" class="count">' . $count . '</span>' ); ?></a>
	</div>

	<div class="social-icons-product">
			<p class="share-text">Share</p>
			<a title="Twitter Icon" target="_blank" href="https://twitter.com/home?status=<?php the_title(); ?>, <?php the_content(); ?>  -  <?php echo get_permalink(); ?>" id="twitter-icon" class="social-icon-2 twitter"></a>
			<a title="Facebook Icon" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo get_permalink(); ?>" id="facebook-icon" class="social-icon-2 facebook"></a>
			<a title="Pinterest Icon" target="_blank" href="https://pinterest.com/pin/create/button/?url=<?php echo get_permalink(); ?>&media=<?php echo $image_link; ?>&description=<?php the_title(); ?>, <?php the_content(); ?>" id="pinterest-icon" class="social-icon-2 pinterest"></a>
			<a title="Google+ Icon" target="_blank" href="https://plus.google.com/share?url=<?php echo get_permalink(); ?>" id="googleplus-icon" class="social-icon-2 gplus"></a>
			</div>

			<p class="dk-clearfix"></p>

<?php //endif; ?>
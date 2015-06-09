<?php
/**
 * Review Comments Template
 *
 * Closing li is left out on purpose!
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$rating = intval( get_comment_meta( $comment->comment_ID, 'rating', true ) );
$new_rating = intval(get_comment_meta( $comment->comment_ID, 'rating', true ));
$decimal_rating = $english_format_number = number_format($new_rating, 1, '.', '');
$comment = substr(get_comment_text(), 0, 46);
if ( strlen(get_comment_text()) > 46 ) {
	$comment .= '...';
}

?>
<li itemprop="review" itemscope itemtype="http://schema.org/Review" class="reviews-list-item" id="li-comment-<?php comment_ID() ?>">

	<div class="reviews-list-item-author">
		<?php echo get_avatar( $comment, apply_filters( 'woocommerce_review_gravatar_size', '60' ), '', get_comment_author_email( $comment->comment_ID ) ); ?>
		<div class="reviews-list-item-author-name">
			<strong itemprop="author"><?php comment_author() ?></strong>
		</div>
	</div>

	<div class="reviews-list-item-content">

		<?php if ( $rating && get_option( 'woocommerce_enable_review_rating' ) == 'yes' || get_option( 'woocommerce_enable_review_rating' ) == 'no' ) : ?>
			<div class="reviews-list-item-content-reviews">
				<div class="reviews-list-item-content-reviews-wrap">
					<span class="reviews-list-item-content-reviews-text">Score</span>
					<div class="reviews-list-item-content-reviews-rating"><?php echo $decimal_rating; ?></div>
					<div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating" class="star-rating" title="<?php echo sprintf( __( 'Rated %d out of 5', 'woocommerce' ), $rating ) ?>">
						<span class="star-rating-active" style="width:<?php echo ( $rating / 5 ) * 100; ?>%"><strong class="rating-text" itemprop="ratingValue"><?php echo $rating; ?></strong> <?php _e( 'out of 5', 'woocommerce' ); ?></span>
					</div>
				</div>
			</div>

		<?php endif; ?>

		<div class="reviews-list-item-content-wrap">

			<div class="reviews-list-item-content-title"><?php echo $comment ?></div>

			<?php if ( $comment->comment_approved == '0' ) : ?>

				<p class="reviews-list-item-content-meta"><em><?php _e( 'Your comment is awaiting approval', 'woocommerce' ); ?></em></p>

			<?php else : ?>

				<p class="reviews-list-item-content-meta">
					<?php
						if ( get_option( 'woocommerce_review_rating_verification_label' ) === 'yes' )
							if ( wc_customer_bought_product( $comment->comment_author_email, $comment->user_id, $comment->comment_post_ID ) )
								echo '<em class="verified">(' . __( 'verified owner', 'woocommerce' ) . ')</em> ';

					?><time itemprop="datePublished" datetime="<?php echo get_comment_date( 'c' ); ?>"><?php echo get_comment_date( __( get_option( 'date_format' ), 'woocommerce' ) ); ?></time>
				</p>

			<?php endif; ?>

			<div itemprop="description" class="reviews-list-item-content-description"><?php comment_text(); ?></div>
		</div>
	</div>
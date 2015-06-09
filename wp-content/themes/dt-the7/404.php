<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package presscore
 * @since presscore 0.1
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

get_header(); ?>

			<!-- Content -->
			<div id="content" class="content" role="main" style="min-height: 500px; text-align:center">

				<article id="post-0" class="post error404 not-found">
				<div class="error-message">
					<h1 class="large-oops">Oops!</h1>
					<h1 class="entry-title"><?php _e( 'It seems like we can&rsquo;t find the page you&rsquo;re looking for.', LANGUAGE_ZONE ); ?></h1>
					
					<div class="links-offer">
						<p><?php _e( 'Here are some helpful links:', LANGUAGE_ZONE ); ?></p>
						<ul>
							<li><a href="home" title="Home">Home</a></li>
							<li><a href="shop" title="Shop">Shop Categories</a></li>
							<li><a href="contact" title="Contact Us">Contact Us</a></li>
							<li><a href="us" title="About Us">About Us</a></li>
							<li><a href="specials" title="Specials">Specials</a></li>
						</ul>
					</div>

					<div class="search-offer">
						<p><?php _e( 'Or maybe try to use a search?', LANGUAGE_ZONE ); ?></p>

						<?php get_product_search_form(); ?>
					</div>
				</div>

				<div class="error-image-container"><div class="error-image"></div></div>
				</article><!-- #post-0 .post .error404 .not-found -->

			</div><!-- #content .site-content -->

			<?php get_sidebar(); ?>

<?php get_footer(); ?>
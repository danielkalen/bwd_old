<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Snapmagnet
 */

		$site = get_bloginfo('name');

		switch ( $site ) {
			case 'ShopperBarn':
				$siteurl = 'shopperbarn.com';
				break;

			case 'OnlyOneStopShop':
				$siteurl = 'onlyonestopshop.com';
				break;

			case 'BWD Wholesale':
				$siteurl = 'bwdny.com';
				break;
		}
?>
	</main><!-- #content -->
	<footer id="colophon" class="footer" role="contentinfo">
		<div class="footer-wrap">
			<div class="footer-cat">
				<div class="footer-title">Shop Categories</div>
				<?php 
					$args = array(
					    // 'number'     => $number,
					    // 'orderby'    => $orderby,
					    // 'order'      => $order,
					    'hide_empty' => '0',
					    // 'include'    => $ids,
					    'parent'    => 0,
					);

					$product_categories = get_terms( 'product_cat', $args );

					$count = count($product_categories);
					 if ( $count > 0) {
					     echo "<ul class='footer-cat-list'>";
					     foreach ( $product_categories as $category ) {
					       echo '<li class="footer-cat-list-item"><a href="' . get_term_link( $category ) . '">' . $category->name . '</a></li>';
					     }
					     echo "</ul>";
					 }
				 ?>
			</div>
			<div class="footer-company">
				<div class="footer-company-support">
					<div class="footer-title">Customer Support</div>
					<div class="footer-company-support-item phone">
						<a href="tel:(800)-480-5887" class="footer-company-support-item-link">(800) 480-5887</a>
					</div>
					<div class="footer-company-support-item email">
						<a href="mailto:support@shopperbarn.com" class="footer-company-support-item-link">support@<?php echo $siteurl; ?></a>
					</div>
				</div>
				<div class="footer-company-social">
					<div class="footer-title">Social Links</div>
					<div class="footer-company-social-item facebook"><a href="http://facebook.com/shopperbarn" target="_blank"><span class="mobile-hide">Like us on </span>Facebook</a></div>
					<div class="footer-company-social-item twitter"><a href="http://twitter.com/shopperbarn" target="_blank"><span class="mobile-hide">Tweet us on </span>Twitter</a></div>
					<div class="footer-company-social-item pinterest"><a href="http://pinterest.com/shopperbarn" target="_blank"><span class="mobile-hide">Pin us on </span>Pinterest</a></div>
				</div>
			</div>
		</div>
		<div class="footer-payments"></div>
		<div class="footer-bottom">
			<div class="footer-bottom-branding">
				<div class="footer-bottom-branding-logo"></div>
				<div class="footer-bottom-branding-copyright">© 2014 ShopperBarn by BWD. All Rights Reserved.</div>
			</div>
			<ul class="footer-bottom-links">
			<?php 
				$menu_object = wp_get_nav_menu_object( 'bottom-menu' );
				$menu_items = wp_get_nav_menu_items($menu_object->term_id);
				
				foreach ($menu_items as $key => $item) {
					echo '<li class="footer-bottom-links-item"><a href="' . $item->url . '">' . $item->title . '</a></li>';
				}

			?>
			</ul>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->
<div class="notices"></div>
<?php wp_footer(); ?>

</body>
</html>

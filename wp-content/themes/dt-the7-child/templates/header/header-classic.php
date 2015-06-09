<?php

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

$header_classes = apply_filters( 'presscore_header_classes', array( 'logo-classic' ) );
?>
	<header id="header" class="<?php echo esc_attr(implode(' ', $header_classes )); ?>" role="banner">
		<div class="wf-wrap">
			<div class="wf-table">

				<?php get_template_part( 'templates/header/branding' ); ?>

				<?php
				$info = of_get_option('header-contentarea', false);

				if ( $info ) :
				?>

					<div class="wf-td assistive-info" role="complementary"><?php echo $info; ?></div>

				<?php endif; // info ?>
				<div class="search-menu">
					<div class="search-header">
						<?php get_product_search_form() ?>
						<div class="search-results-dk"></div>
						 <div id='loading-ajax'>
						    <img src='/wp-content/themes/dt-the7-child/img/91.gif' />
						 </div> 
					</div>

					<div class="mobile-nav-trigger"><a href="#" id="trigger" class="menu-trigger">Shop</a></div>
				</div>


			</div>
		</div>
		<div class="navigation-holder">
			<div>

				<?php do_action( 'presscore_primary_navigation' ); ?>

			</div>
		</div>
	</header>
<?php
/**
 * The template for displaying the cart page.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Snapmagnet
 */

get_header(); ?>

<div class="contact-header"></div>
<div class="content-wrap right">
	<main id="main" class="content" role="main">

		<?php 
		// while ( have_posts() ) : the_post(); 

		// 	the_content();

		// endwhile; // end of the loop. 
		?>

		<div class="contact-section form">
			<h2 class="contact-section-title page-title">Send us a message</h2>
			<?php echo do_shortcode('[contact-form-7 id="149" title="CT Us"]') ?>
		</div>

		<div class="contact-section info">
			<h2 class="contact-section-title page-title">Contact Information</h2>
			<ul class="contact-section-info">
				<li class="contact-section-info-item phone">
					<div class="contact-section-info-item-title">Phone</div>
					<div class="contact-section-info-item-text">
						(800) 480-5887 <br />
						(718) 425-8949
					</div>
				</li>
				<li class="contact-section-info-item email">
					<div class="contact-section-info-item-title">Email</div>
					<div class="contact-section-info-item-text">
						support@shoppertbarn.com
					</div>
				</li>
				<li class="contact-section-info-item address">
					<div class="contact-section-info-item-title">Address</div>
					<div class="contact-section-info-item-text">
						<span class="contact-section-info-item-text-highlight">Warehouse</span> – 9 Portal Street Brooklyn NY 11233 <br />
						<span class="contact-section-info-item-text-highlight">Office</span> – 2078 E 22nd St Brooklyn NY 11229
					</div>
				</li>
				<li class="contact-section-info-item hours">
					<div class="contact-section-info-item-title">Office Hours</div>
					<div class="contact-section-info-item-text">
						<span class="contact-section-info-item-text-highlight">Mon-Fri</span> – 8AM - 8PM <br />
						<span class="contact-section-info-item-text-highlight">Sat-Sun</span> – CLOSED
					</div>
				</li>
			</ul>
		</div>

	</main><!-- #main -->

	<div class="sidebar-wrap">
		<div class="sidebar">
			<div class="sidebar-title">Shop</div>
			<?php get_sidebar(); ?>
		</div>
	</div>
</div>
<?php get_footer(); ?>

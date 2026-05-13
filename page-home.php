<?php
/**
 * Template Name: Homepage
 *
 * This is the template for the home page.
 *
 * @package The Dough Shack
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<div class="section page-intro">
					<div class="section feature-slider">
						<?php 
						$images = get_field('page_intro_gallery');
						$imagesAlign = get_field('page_intro_gallery_alignment');
						$imagesAlignClass = strtolower($imagesAlign);
						if( $images ) { ?>
						<ul class="bx-slider clear">
							<?php foreach( $images as $image ) {
								$imageAlt = "Pizza image";
								if ($image['alt'] !== "") {
									$imageAlt = $image['alt'];
								} ?>
						        <li>
									<div class="feature-slider-item">
										<div class="feature-slider-image" style="background-image: url(<?php echo $image['url']; ?>);"></div>
									</div>
						        </li>
							<?php } ?>
						</ul>
						<?php } ?>
					</div><!-- .section -->
					<div class="section-inner align-<?php echo $imagesAlignClass; ?>">
						<?php if (get_field('page_intro_header')) { ?>
							<h1><?php echo get_field('page_intro_header'); ?></h1>
						<?php } ?>
						<?php if (get_field('page_intro_sub_header')) { ?>
							<h2><?php echo get_field('page_intro_sub_header'); ?></h2>
						<?php } ?>
					</div><!-- .section-inner -->
				</div><!-- .section -->
					
				<div class="section feature-find-us">
					
					<div class="map-canvas-container map-canvas-closed"><div id="map-canvas"></div></div>
					<div class="section-inner">
						<div class="find-us">
							<div class="find-us-title">
								<?php if (get_field('events_header_one')) { ?>
									<h2><?php echo get_field('events_header_one'); ?></h2>
								<?php } ?>
								<?php if (get_field('events_sub_header_one')) { ?>
									<p><?php echo get_field('events_sub_header_one'); ?></p>
								<?php } ?>
							</div>
							<?php
								if ( shortcode_exists( 'calendar' ) ) {
									// Get Today's Events feed
									$todayEvents = do_shortcode('[calendar id="508"]');
									if ($todayEvents != "") {
										echo '<div class="show-todays-events">'.$todayEvents.'</div>';
									}
								}
							?>
							<div class="find-us-title">
								<a href="<?php echo get_permalink(6); ?>#pizzas-menu-1" class="pizza-menu-anchor-link">Van Menu</a>
								<?php if (get_field('events_sub_header_three')) { ?>
									<p><?php echo get_field('events_sub_header_three'); ?></p>
								<?php } ?>
								<?php if (get_field('events_header_three')) { ?>
									<h4><a href=""><?php echo get_field('events_header_three'); ?></a></h4>
								<?php } ?>
								<ul class="social-links inverted clear">
									<?php if (get_field('twitter_url')) { ?>
									<li class="social-links-twitter"><a href="<?php echo get_field('twitter_url'); ?>" title="Our Twitter page"><span class="screen-reader-text">Twitter</span></a></li>
									<?php } ?>
									<?php if (get_field('facebook_url')) { ?>
									<li class="social-links-facebook"><a href="<?php echo get_field('facebook_url'); ?>" title="Our Facebook page"><span class="screen-reader-text">Facebook</span></a></li>
									<?php } ?>
									<?php if (get_field('instagram_url')) { ?>
									<li class="social-links-instagram"><a href="<?php echo get_field('instagram_url'); ?>" title="Our Instagram page"><span class="screen-reader-text">Instagram</span></a></li>
									<?php } ?>
								</ul>
							</div>
						</div>
						<div class="find-us-takeaway">
							<div class="find-us-takeaway-inner">
								<div class="find-us-takeaway-content">
									<?php if (get_field('takeaway_header')) { ?>
										<h2><?php echo get_field('takeaway_header'); ?></h2>
									<?php } ?>
									<?php if (get_field('takeaway_subheader')) { ?>
										<h3><?php echo get_field('takeaway_subheader'); ?></h3>
									<?php } ?>
									<?php if (get_field('takeaway_content')) { ?>
										<?php echo get_field('takeaway_content'); ?>
									<?php } ?>
									<?php if (get_field('takeaway_deliveroo')) { ?>
										<a href="<?php echo get_field('takeaway_deliveroo'); ?>" rel="external" target="_blank" class="link-deliveroo"><span class="screen-reader-text">Deliveroo</span></a>
									<?php } ?>
								</div>
		
								<div class="find-us-takeaway-img">
									<img src="<?php echo wp_get_attachment_image_src(384, 'large')[0]; ?>">
									<a href="<?php echo get_permalink(6); ?>#pizzas-menu-2" class="pizza-menu-anchor-link">Pop-up Pizzeria Menu</a>
								</div>
							</div>
						</div>
						<div class="follow-us clear">
							<?php if (get_field('charity_button')) { ?>
								<p class="charity-button"><?php echo get_field('charity_button'); ?></p>
							<?php } ?>
							<?php if (get_field('newsletter')) { ?>
								<p><?php echo do_shortcode('[mc4wp_form id="594"]'); ?></p>
							<?php } ?>
							<?php if (get_field('follow_us_header_one') || get_field('follow_us_sub_header_one')) { ?>
							<div class="follow-us-social">
								<?php if (get_field('follow_us_header_one')) { ?>
									<h2><?php echo get_field('follow_us_header_one'); ?></h2>
								<?php } ?>
								<?php if (get_field('follow_us_sub_header_one')) { ?>
									<p><?php echo get_field('follow_us_sub_header_one'); ?></p>
								<?php } ?>
								<ul class="social-links clear">
									<?php if (get_field('twitter_url')) { ?>
									<li class="social-links-twitter"><a href="<?php echo get_field('twitter_url'); ?>" title="Our Twitter page"><span class="screen-reader-text">Twitter</span></a></li>
									<?php } ?>
									<?php if (get_field('facebook_url')) { ?>
									<li class="social-links-facebook"><a href="<?php echo get_field('facebook_url'); ?>" title="Our Facebook page"><span class="screen-reader-text">Facebook</span></a></li>
									<?php } ?>
									<?php if (get_field('twitter_url')) { ?>
									<li class="social-links-instagram"><a href="<?php echo get_field('twitter_url'); ?>" title="Our Instagram page"><span class="screen-reader-text">Instagram</span></a></li>
									<?php } ?>
								</ul>
							</div>
							<?php } ?>
							<div class="follow-us-hire">
								<?php if (get_field('follow_us_header_two')) { ?>
									<h2><?php echo get_field('follow_us_header_two'); ?></h2>
								<?php } ?>
								<?php if (get_field('follow_us_sub_header_two') || get_field('email_address') || get_field('telephone_no')) { ?>
								<p>
									<?php if (get_field('follow_us_sub_header_two')) { ?>
										<?php echo get_field('follow_us_sub_header_two'); ?>
									<?php } ?>
									<?php if (get_field('email_address')) { ?>
										<?php if (get_field('follow_us_email_link_text')) {
											$linkText = get_field('follow_us_email_link_text');
										} else {
											$linkText = get_field('email_address');
										} ?>
										 <br /><a href="mailto:<?php echo get_field('email_address'); ?>" class="link-email"><?php echo $linkText; ?></a>
									<?php } ?>
									<?php if (get_field('telephone_no')) { ?>
										<?php if (get_field('follow_us_telephone_link_text')) {
											$linkText = get_field('follow_us_telephone_link_text');
										} else {
											$linkText = get_field('telephone_no');
										} ?>
										 <br /><span class="link-telephone"><?php echo $linkText; ?></span>
									<?php } ?>
								</p>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
				
				
				
				<div class="feature-story">
					<div class="section feature-slider">
						<div class="section feature-story-1 clear">
							<?php 
							$images = get_field('footer_gallery');
							if( $images ) { ?>
							<ul class="bx-slider clear">
								<?php foreach( $images as $image ) {
									$imageAlt = "Pizza image";
									if ($image['alt'] !== "") {
										$imageAlt = $image['alt'];
									} ?>
							        <li>
										<div class="feature-slider-item">
											<div class="feature-slider-image" style="background-image: url(<?php echo $image['url']; ?>);"></div>
										</div>
							        </li>
								<?php } ?>
							</ul>
							<?php } ?>
							<div class="section-inner">
								<div class="feature-captions">
									<?php if (get_field('story_text_one')) { ?>
										<p class="feature-caption clear"><?php echo get_field('story_text_one'); ?></p>
									<?php } ?>
									<?php if (get_field('story_text_two')) { ?>
										<p class="feature-caption"><?php echo get_field('story_text_two'); ?></p>
									<?php } ?>
									<?php if (get_field('story_text_three')) { ?>
										<p class="feature-caption"><?php echo get_field('story_text_three'); ?></p>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
					<?php if (get_field('van_image')) { ?>
					<div class="section feature-story-2 clear">
						<div class="section-inner">
							<img src="<?php echo get_field('van_image'); ?>" alt="Van image" />
						</div>
					</div>
					<?php } ?>
				</div>

			<?php endwhile; // End of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
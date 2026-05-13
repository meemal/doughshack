<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package The Dough Shack
 */

get_header(); ?>

	<div id="primary" class="content-area parallaxparallax-image" data-speed="-4">
		<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>
				
				<?php
				// Check for Advanced Custom Fields plugin function
				if(function_exists('get_field')) {
					// If there is page intro content, output
					if(have_rows('page_intro')) {
					     // loop through the rows of data
					    while ( have_rows('page_intro') ) {
						    the_row();
							get_template_part( '/components/component', 'page_intro' );
					    }
					}
				}
				?>
				
				<?php
				if(is_page(10)) { ?>
				<div class="section feature-slider feature-slider-single">
					<div class="section-inner">
						<div class="feature-aaron-conor">
							<figure>
								<figcaption><cite>Conor</cite> Business manager</figcaption>
							</figure>
							<ul class="clear">
						        <li>
									<div class="feature-slider-image" style="background-image: url(<?php echo site_url(); ?>/wp-content/uploads/person-x2-e1469139976179.jpg);"></div>
						        </li>
							</ul>
							<figure>
								<figcaption><cite>Aaron</cite> Pizza chef</figcaption>
							</figure>
						</div><!-- .feature-aaron-conor -->
					</div><!-- .section-inner -->
				</div><!-- .section -->
				<?php /* ?>
				<div class="section with-button">
					<div class="section-inner">
						<div class="cols">
							<div class="col">
								<img src="<?php echo site_url(); ?>/wp-content/uploads/logo_AMF_Square.jpg" alt="Against Malaria Foundation Logo" />
							</div><!-- .col -->
							<div class="col">
								<p class="charity-button">We give 5% of our profits to charity :)</p>
							</div><!-- .col -->
						</div><!-- .cols -->
					</div><!-- .section-inner -->
				</div><!-- .section -->
				<?php */ ?>
				<?php } ?>
				<?php
				// check if the flexible content field has rows of data
				if( have_rows('page_content') ) {
				     // loop through the rows of data
				    while ( have_rows('page_content') ) {
					    the_row();
						$component = get_row_layout();
						if (get_sub_field('section_title')) { ?>
						<div class="section page-content with-section-title">
							<div class="section-inner">
								<h2 class="section-title"><?php echo get_sub_field('section_title'); ?></h2>
							</div>
						</div>
						<?php }
						switch ($component) {
						    case 'feature_image_grid':
								get_template_part( '/components/component', 'feature_image_grid' );
						        break;
						    case 'feature_embeds':
								get_template_part( '/components/component', 'feature_embeds' );
						        break;
						}
				    }
				}
				?>
				
				<?php
				if(is_page(10) && current_user_can('manage_options')) {
					global $wp_embed;
					$video_url = 'https://www.youtube.com/watch?v=a3ICNMQW7Ok';
					echo '<div class="embed-video-container">';
					echo '<div class="embed-video-container-inner">';
					echo $wp_embed->run_shortcode( '[embed]' . $video_url . '[/embed]' );
					echo '</div>';
					echo '</div>';
				} ?>
				<?php
				if(function_exists('get_field') && is_page(6)) {
					if( have_rows('price_section') ) {
						get_template_part( '/components/component', 'price_section' );
					}
				} ?>
			<?php endwhile; // End of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
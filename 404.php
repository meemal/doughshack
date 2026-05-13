<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package The Dough Shack
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<section class="error-404 not-found section">
				<div class="section-inner">
					<header class="page-header">
						<h1 class="page-title"><?php esc_html_e( 'That page can&rsquo;t be found.', 'thedoughshack' ); ?></h1>
					</header><!-- .page-header -->
	
					<div class="page-content">
						<p><?php esc_html_e( 'Sorry, nothing was found at this location.', 'thedoughshack' ); ?></p>
					</div><!-- .page-content -->
				</div>
			</section><!-- .error-404 -->

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>
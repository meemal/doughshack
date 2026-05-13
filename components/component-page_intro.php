<?php
// Check for Advanced Custom Fields plugin function
if( function_exists('get_field') ) {
	$title = get_the_title();
	// Check for page intro text (sub)field
	$text = get_sub_field('page_intro_text');
	// Check for background-image, get URL
	$bg_image = get_sub_field('page_intro_background_image');
	$bg_image_url = $bg_image['url']; ?>
	<div class="section page-intro">
		<div class="feature-parallax-image">
			<?php if ($bg_image) { ?>
			<div class="section feature-full-width feature-full-width-narrow parallax parallax-image" style="background-image: url(<?php echo $bg_image_url; ?>);" data-speed="4">
			</div><!-- .section -->
			<?php } ?>
		</div><!-- .section -->
		<div class="section-inner">
			<h1><?php echo $title; ?></h1>
			<?php if ($text) {
				echo $text;
			} ?>
		</div><!-- .feature-parallax-image -->
	</div><!-- .section -->
	<?php
} // End check for ACF ?>
		
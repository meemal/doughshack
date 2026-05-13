<?php 
// Check for Advanced Custom Fields plugin function
if( function_exists('get_field') ) {
	// Look for, output Twiiter and Instagram embeds
	if( have_rows('instagram_embeds') || have_rows('twitter_embeds') ) {
	global $wp_embed;
	?>
	<div class="section page-content feature-embeds">
		<div class="section-inner">
			<div class="cols clear">
				<div class="col embed-instagram">
					<?php 
					if( have_rows('instagram_embeds') ) { ?>
					<h3>...on Instagram</h3>
					<?php 
						while( have_rows('instagram_embeds') ) {
							the_row();
							if (get_sub_field('instagram_url')) {
								$url = rtrim(get_sub_field('instagram_url'),"/");
								echo '<div class="embed-item">';
								echo $wp_embed->run_shortcode('[embed]'.$url.'[/embed]');
								echo '</div>';
							}
						}
					}
					?>
				</div>
				<div class="col embed-twitter">
					<?php 
					if( have_rows('twitter_embeds') ) { ?>
						<h3>...on Twitter</h3>
						<?php 
						while( have_rows('twitter_embeds') ) {
							the_row();
							if (get_sub_field('twitter_url')) { 
								$url = rtrim(get_sub_field('twitter_url'),"/");
								echo '<div class="embed-item">';
								echo $wp_embed->run_shortcode('[embed]'.$url.'[/embed]');
								echo '</div>';
							}
						}
					}
					?>
				</div>
			</div>
		</div>
	</div>
	<?php
	//wp_enqueue_script( 'thedoughshack-embed-twitter', '//platform.instagram.com/en_US/embeds.js', array(), '', true );
	//wp_enqueue_script( 'thedoughshack-embed-instagram', '//platform.twitter.com/widgets.js', array(), '', true );
	} ?>
	<?php 
	if( have_rows('manual_quote_embeds') ) { ?>
	<div class="section page-content feature-embeds clear">
		<div class="section-inner">
			<h3>...and elsewhere</h3>
			<?php while( have_rows('manual_quote_embeds') ) { 
				the_row();
				$quoteContent = get_sub_field('quote_embed_content');
				$quoteReference = get_sub_field('quote_embed_reference');
				if ($quoteContent) {
					echo '<figure class="quote">';
					echo '<blockquote>'.$quoteContent.'</blockquote>';
					if ($quoteReference) {
						echo '<figcaption><cite>'.$quoteReference.'</cite></figcaption>';
					}
					echo '</figure>';
				}
			} ?>
		</div>
	</div>
	<?php }
} ?>
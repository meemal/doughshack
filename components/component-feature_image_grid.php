<?php
// Check for Advanced Custom Fields plugin function
if( function_exists('get_field') ) { ?>
	<div class="section feature-image-grid cols col-1-4 clear">
	<?php 
	$images = get_sub_field('images');
	if( $images ) {
		foreach( $images as $image ) {
			$imageAlt = "Pizza image";
			if ($image['alt'] !== "") {
				$imageAlt = $image['alt'];
			} 
			?>
			<div class="col">
				<img src="<?php echo $image['sizes']['midsize']; ?>" alt="<?php echo $imageAlt; ?>" width="<?php echo $image['sizes']['midsize-width']; ?>" height="<?php echo $image['sizes']['midsize-height']; ?>" />
			</div>
		<?php }
	} ?>
</div>
<?php } ?>
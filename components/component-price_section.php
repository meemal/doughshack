<?php 
$i = 1;
$numOfTabs = count( get_field('price_section') );
if ($numOfTabs > 1) :
	echo '<div class="section feature-alternating-grid feature-tabbed-prices">';
	echo '<div class="section-inner">';
else :
	echo '<div class="section feature-alternating-grid">';
	echo '<div class="section-inner">';
endif;
?>
	
	<?php 
	// loop through rows, get content
	while( have_rows('price_section') ): the_row();
	if (get_sub_field('section_title')) { 
		$priceSectionTitle = get_sub_field('section_title');
	}
	if (get_sub_field('section_description')) { 
		$priceSectionDescription = get_sub_field('section_description');
	}
	if (get_sub_field('section_description_after')) { 
		$priceSectionDescriptionAfter = get_sub_field('section_description_after');
	}
	if (get_sub_field('section_caption')) { 
		$priceSectionCaption = get_sub_field('section_caption');
	}
	if (get_sub_field('section_image')) { 
		$priceSectionImage = get_sub_field('section_image');
	}
	?>
		<div class="cols clear">
			<div class="col with-bg-image" style="background-image: url(<?php echo $priceSectionImage; ?>);">
				<?php if ($priceSectionCaption) { 
				echo '<p class="image-caption">'.$priceSectionCaption.'</p>';
				} ?>
			</div>
			<div class="col pizzas-menu-<?php echo preg_replace('/\W+/','',strtolower(strip_tags($priceSectionTitle))); ?>" id="pizzas-menu-<?php echo $i; ?>">
				<?php
				if ($priceSectionTitle) { 
					echo '<h2>'.$priceSectionTitle.'</h2>';
				}
				if ($priceSectionDescription) { 
					echo '<p>'.$priceSectionDescription.'</p>';
				}
				if (get_field('takeaway_deliveroo', 2) && $priceSectionTitle == "Pop-up Pizzeria") { ?>
					<a href="<?php echo get_field('takeaway_deliveroo'); ?>" rel="external" target="_blank" class="link-deliveroo"><span class="screen-reader-text">Deliveroo</span></a>
				<?php }
				// check for rows (sub repeater)
				if( have_rows('section_items') ): ?>
					<dl class="price-list">
					<?php 
					while( have_rows('section_items') ): the_row();
						// output content
						$priceItemTitle = get_sub_field('item_title');
						$priceItemDescription = get_sub_field('item_description');
						$priceItemPrice = get_sub_field('item_price');
						if ($priceItemTitle && $priceItemPrice) {
							if ($priceItemTitle) {
								echo '<dt>'.$priceItemTitle.'<span>'.$priceItemPrice.'</span></dt>';
							}
							if ($priceItemDescription) {
								echo '<dd>'.$priceItemDescription.'<dd>';
							}
						}
					endwhile; ?>
					</dl>
				<?php endif; ?>
				<?php 
				if ($priceSectionDescriptionAfter) { 
					echo '<p>'.$priceSectionDescriptionAfter.'</p>';
				} ?>
			</div>
		</div>

	<?php 
	$i++;
	endwhile;  ?>
	</div>
</div>
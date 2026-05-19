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
	$menu_seperator_image = get_sub_field( 'menu_seperator_image' );
	$menu_seperator_url    = '';
	if ( is_array( $menu_seperator_image ) && ! empty( $menu_seperator_image['url'] ) ) {
		$menu_seperator_url = $menu_seperator_image['url'];
	} elseif ( is_string( $menu_seperator_image ) && $menu_seperator_image ) {
		$menu_seperator_url = $menu_seperator_image;
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
				$section_items = get_sub_field( 'section_items' );
				if ( ! empty( $section_items ) && is_array( $section_items ) ) :
					echo '<dl class="price-list">';
					$dl_open         = true;
					$section_count   = count( $section_items );
					foreach ( $section_items as $idx => $row ) {
						$priceItemTitle       = isset( $row['item_title'] ) ? $row['item_title'] : '';
						$priceItemDescription = isset( $row['item_description'] ) ? $row['item_description'] : '';
						$priceItemPrice       = isset( $row['item_price'] ) ? $row['item_price'] : '';
						$separator_after      = ! empty( $row['add_menu_seperator_underneath'] );

						if ( $priceItemTitle && $priceItemPrice ) {
							echo '<dt>' . $priceItemTitle . '<span>' . $priceItemPrice . '</span></dt>';
							if ( $priceItemDescription ) {
								echo '<dd>' . $priceItemDescription . '</dd>';
							}
						}

						if ( $separator_after && $menu_seperator_url ) {
							echo '</dl>';
							$dl_open = false;
							$sep_alt = '';
							if ( is_array( $menu_seperator_image ) && ! empty( $menu_seperator_image['alt'] ) ) {
								$sep_alt = $menu_seperator_image['alt'];
							}
							$img_alt = $sep_alt ? esc_attr( $sep_alt ) : '';
							$aria    = $sep_alt ? '' : ' aria-hidden="true"';
							echo '<div class="menu-sep-wrap"><img src="' . esc_url( $menu_seperator_url ) . '" alt="' . $img_alt . '" class="menu-sep-img"' . $aria . ' /></div>';
							if ( $idx < $section_count - 1 ) {
								echo '<dl class="price-list">';
								$dl_open = true;
							}
						}
					}
					if ( $dl_open ) {
						echo '</dl>';
					}
				endif; ?>
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
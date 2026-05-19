<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package The Dough Shack
 */

?>

	</div><!-- #content -->

	<?php
	if ( shortcode_exists( 'calendar' ) && is_page(6) ) {
		// Get Today's Events feed
		$todayEvents = do_shortcode('[calendar id="508"]');
		if ($todayEvents != "") {
			echo '<div class="show-todays-events" style="display: none;">'.$todayEvents.'</div>';
		}
	}
	?>

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-footer-inner clear">

			<nav class="main-navigation" role="navigation">
				<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'footer-menu', 'menu_class' => 'inline-menu', 'depth' => '1' ) ); ?>
			</nav><!-- .main-navigation -->
			
			<ul class="social-links inverted">
				<?php if (get_field('twitter_url', 2)) { ?>
				<li class="social-links-twitter"><a href="<?php echo get_field('twitter_url', 2); ?>" title="Our Twitter page"><span class="screen-reader-text">Twitter</span></a></li>
				<?php } ?>
				<?php if (get_field('facebook_url', 2)) { ?>
				<li class="social-links-facebook"><a href="<?php echo get_field('facebook_url', 2); ?>" title="Our Facebook page"><span class="screen-reader-text">Facebook</span></a></li>
				<?php } ?>
				<?php if (get_field('instagram_url', 2)) { ?>
				<li class="social-links-instagram"><a href="<?php echo get_field('instagram_url', 2); ?>" title="Our Instagram page"><span class="screen-reader-text">Instagram</span></a></li>
				<?php } ?>
			</ul>
			<p class="back-to-top"><a href="#masthead" class="link-scrollto"><span>Back to top&hellip;</span></a></p>
			<p class="site-info">
				&copy; <?php echo date('Y'); ?> <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" title="<?php bloginfo( 'name' ); ?> home page"><?php bloginfo( 'name' ); ?></a>
				
				<?php if (get_field('email_address', 2)) { ?>
					<?php if (get_field('follow_us_email_link_text', 2)) {
						$linkText = get_field('follow_us_email_link_text', 2);
					} else {
						$linkText = get_field('email_address', 2);
					} ?>
				<span class="sep"> | </span><a href="mailto:<?php echo get_field('email_address', 2); ?>"><?php echo $linkText; ?></a>
				<?php } ?>
			</p><!-- .site-info -->
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->
<?php wp_footer(); ?>

	<?php 
	// If is home page, load map script
	if (is_front_page()) { ?>
	<script type="text/javascript" src="https://maps.google.com/maps/api/js?key=AIzaSyD9ywL0EfMO-_eWFlzDFRNmDR2MmjchCoc"></script>
	<?php } ?>
	
	<script>
		<?php 
		if (is_front_page()) { ?>
		$('.page-intro .bx-slider').bxSlider({
			auto: true,
			controls: false,
			pager: false,
			mode: 'fade'
		});
		<?php } else { ?>
		$('.feature-story .bx-slider').bxSlider({
			auto: true,
			controls: false,
			pager: false,
			mode: 'fade',
			speed: 0
		});
		<?php } ?>
		
	    // Animated scroll anchor links
		$('.link-scrollto').click(function(e){
			e.preventDefault();
			var target = $(this).attr('href');
			$('html, body').animate({
			    scrollTop: $(target).offset().top
			}, 500);
		});
		
		// Parallax Images
		$window = $(window);

		$('.parallax').each(function(){
			var $bgobj = $(this);
			$(window).scroll(function() {							
				var yPos = -($window.scrollTop() / $bgobj.data('speed'));
				if ($bgobj.hasClass('parallax-image')) {
					var coords = '50% '+ yPos + 'px';
					$bgobj.css({ backgroundPosition: coords });
				}
			});
		});
	
		<?php if (is_front_page() || is_page(6)) { ?>

			<?php
			if( have_rows('vans') ):
				while( have_rows('vans') ) : the_row();
					$van = get_sub_field('van_number');
					if ($van == '2' || $van == '3') {
						$icon = $van;
					} else {
						$icon = '1';
					}
					?>
					$('.simcal-events-list-container .simcal-event-title').html(function(index,str){
							return str.replace(/Van <?php echo $van; ?>: /gi, '<span class="simcal-event-van<?php echo $icon; ?> simcal-event-van-tel-<?php echo $van; ?>"><span class="screen-reader-text">Van <?php echo $van; ?>: </span></span>');
					});
				<?php endwhile;
			endif; ?>
			
			var daysStore = [];
			var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
			var output = "";
			var today = new Date();
			var todayNum = today.getDay();
			for (i = todayNum; i <= (todayNum+6); i++) {
				if (i > 6) {
					j = i - 7;
				} else {
					j = i;
				}
				var currentWeekday = $('.simcal-events-list-container .simcal-weekday-'+j);
				if (currentWeekday.length < 1) {
					output += '<div class="simcal-weekday-'+j+' simcal-day simcal-day-empty"><ul class="simcal-events"><li class="simcal-event"><div class="simcal-event-details"><p><span class="simcal-event-start simcal-event-start-date">'+days[j]+'</span></p><h3>No party today.</h3><h4>Please try another day.</h4></div></li></ul></div>';
				} else {
					output += currentWeekday.get(0).outerHTML;
				}
			}
			$('.simcal-events-list-container').html(output);
			$('.simcal-events-list-container').before('<div class="events-prev-next"><span class="events-prev">&#8249;&nbsp;Prev</span> <span class="events-today">'+days[todayNum]+'</span> <span class="events-next">Next&nbsp;&#8250;</span></div>');
			$('.simcal-events-list-container .simcal-day, .events-prev-next .events-prev').addClass('simcal-disabled');
			$('.simcal-events-list-container .simcal-day:first-child').removeClass('simcal-disabled').addClass('simcal-enabled');
			
			$('.events-next').on( 'click', function() {
				var weekday = $('.simcal-events-list-container .simcal-day.simcal-enabled');
				var weekdayNext = weekday.next('.simcal-day');
				if (weekdayNext.length > 0) {
					var weekdayNextClass = weekdayNext.attr('class').split(' ')[0];
					var weekdayNumber = weekdayNextClass.slice(-1);
					
					$('.events-prev-next .events-today').text(days[weekdayNumber]);
					weekday.slideUp().removeClass('simcal-enabled').next('.simcal-day').slideDown().addClass('simcal-enabled');
					if (weekdayNext.next('.simcal-day').length == 0) {
						$('.events-prev-next .events-next').removeClass('simcal-enabled').addClass('simcal-disabled');
					}
					if (weekday.prev('.simcal-day').length == 0) {
						$('.events-prev-next .events-prev').removeClass('simcal-disabled').addClass('simcal-enabled');
					}
				}
			});
			$('.events-prev').on( 'click', function() {
				var weekday = $('.simcal-events-list-container .simcal-day.simcal-enabled');
				var weekdayPrev = weekday.prev('.simcal-day');
				if (weekdayPrev.length > 0) {
					var currentDate = weekdayPrev.find('.simcal-event-start').text();
					var currentDay = currentDate.substr(0, currentDate.indexOf(','));
					if (currentDay == "") {
						currentDay = currentDate;
					}
					$('.events-prev-next .events-today').text(currentDay);
					weekday.slideUp().removeClass('simcal-enabled').prev('.simcal-day').slideDown().addClass('simcal-enabled');
					if (weekdayPrev.prev('.simcal-day').length == 0) {
						$('.events-prev-next .events-prev').removeClass('simcal-enabled').addClass('simcal-disabled');
					}
					if (weekday.next('.simcal-day').length == 0) {
						$('.events-prev-next .events-next').removeClass('simcal-disabled').addClass('simcal-enabled');
					}
				}
			});
			
		<?php 
		}
		// If is home page, load map scripts
		if (is_front_page()) { ?>
			// Add close button on map
			$(".map-canvas-container").append('<span class="map-close-canvas-container">x close</span>').on('click', function() {
				$('.map-canvas-open').addClass('map-canvas-closed').removeClass('map-canvas-open');
			});
			
			// Chech for no events, hide map
			if ($('.show-todays-events .no-events').length > 0 || $('.simcal-events-list-container .simcal-day:first-child').hasClass('simcal-day-empty')) {
				$(".map-canvas-container").addClass('map-no-events');
			}
			thedoughshackSplitTelFromEventTitles();
			$('.find-us-title h2 + p').append('<small>Click any item to view on the map&hellip;</small>');
			$('.show-weekly-events').after('<div class="events-show" tabindex="0"><p>Click to view rest of week&hellip;</p></div>');
			$(".events-show").on( "click", function() {
				$(".show-weekly-events").slideToggle(500, function() {
					$(".events-show").html($(this).is(':visible') ? "[x] Close" : "<p>Click to view rest of week&hellip;</p>");
				});
			});
		
		<?php }	?>
		
		<?php if (is_front_page() || is_page(6)) { ?>
			function thedoughshackSplitTelFromEventTitles($context) {
				var $targets = ($context && $context.length)
					? $context.find('.simcal-event-details')
					: $('.show-todays-events .simcal-event-details, .show-weekly-events .simcal-event-details');
				$targets.each(function() {
					var $details = $(this);
					var $h3 = $details.find('h3').first();
					if (!$h3.length || $h3.next('.simcal-event-phone-sub').length) return;
					var $span = $h3.children('span').first();
					if (!$span.length) return;

					var $tel = $span.find('a[href]').filter(function() {
						return /^tel:/i.test(($(this).attr('href') || '').trim());
					}).first();

					if ($tel.length) {
						var href = ($tel.attr('href') || '').trim();
						var displayText = $.trim($tel.text()) || href.replace(/^tel:\s*/i, '');
						$tel.detach();
						var $sub = $('<p class="simcal-event-phone-sub"></p>').insertAfter($h3);
						$sub.append($('<a></a>').attr('href', href).text(displayText));
						return;
					}

					var html = $span.html();
					if (!html) return;
					var m = html.match(/(?:<br\s*\/?>|\s|&nbsp;)*tel:\s*([+\d\s\u00a0().\-—]+)/i);
					if (m) {
						var rawPhone = m[1].replace(/[\s\u00a0]+/g, ' ').trim();
						if (!rawPhone) return;
						var digits = rawPhone.replace(/[^\d+]/g, '');
						var telHref = 'tel:' + (digits || rawPhone.replace(/\s+/g, ''));
						$span.html(html.replace(m[0], ' ').replace(/\s{2,}/g, ' ').trim());
						var $sub2 = $('<p class="simcal-event-phone-sub"></p>').insertAfter($h3);
						$sub2.append($('<a></a>').attr('href', telHref).text(rawPhone));
					}
				});
			}

        	var isDraggable = !('ontouchstart' in document.documentElement);
			if (!isDraggable) {
				$('.page-intro .section-inner').append('<a href="tel:02034881064" class="call-ahead lightbox">Call ahead</a>');
				//if ($('.simcal-events-list-container > div:nth-child(1) .simcal-event-van3').length > 0) {
				//}
				
				<?php if (get_field('takeaway_phone', 2)) { ?>
					$('.find-us-takeaway h3').after('<a href="tel:<?php echo get_field('takeaway_phone', 2); ?>" class="call-ahead"><?php echo get_field('takeaway_phone', 2); ?></a>');
				<?php } ?>
				
				$('.lightbox').on( 'click', function(e) {
					var lightboxContent = $('.simcal-calendar').html().replace(/@ /g, "");
					<?php if (get_field('takeaway_phone', 2)) { ?>
					lightboxContent += '<ul class="simcal-events"><li class="simcal-event"><a href="tel:<?php echo get_field('takeaway_phone', 2); ?>" class="simcal-event-call"><div class="simcal-event-details"><p><?php echo get_field('takeaway_header', 2); ?></p><h3>Call Now: <?php echo get_field('takeaway_phone', 2); ?><span class="simcal-event-van3"><span class="screen-reader-text">Van 3: </span></span></h3><h4>&nbsp;</h4></div></a></li></ul>';
					<?php } ?>
					
					var lightbox = '<div id="lightbox"><p class="lightbox-close">Close</p><div id="lightbox-content" class="simcal-calendar">'+lightboxContent+'</div></div>';
					$('body').append(lightbox);
					$("#lightbox .lightbox-close").on("click", function() {
						$(this).parent().hide();
					});
<?php
	if( have_rows('vans') ):
		while( have_rows('vans') ) : the_row();
			$van = get_sub_field('van_number');
			$vanTel = get_sub_field('van_telephone_number');
			if ($van && $vanTel) { ?>
				$('#lightbox .simcal-event-van-tel-<?php echo $van; ?>').closest('.simcal-event').wrapInner('<a href="tel:<?php echo $vanTel; ?>" class="simcal-event-call"></a>');
			<?php }
		endwhile;
	endif; 
?>
					if (typeof thedoughshackSplitTelFromEventTitles === 'function') {
						thedoughshackSplitTelFromEventTitles($('#lightbox'));
					}
					e.preventDefault();
				});
				
			}		
		<?php } ?>  
		
		<?php if (is_front_page()) { ?>
			// Map Init
			var ShackMap = {
			    // HTML Nodes
			    mapContainer: document.getElementById('map-canvas'),
			    marker: new google.maps.Marker(),
			    geocoder: null,
			    map: null,
			    marker: null,
			    init: function() {
			        var latLng = new google.maps.LatLng(51.507351, -0.127758);
			        ShackMap.map = new google.maps.Map(ShackMap.mapContainer, {
			            zoom: 17,
			            center: latLng,
			            mapTypeId: google.maps.MapTypeId.ROADMAP,
						scrollwheel: false,
					    zoomControl: true,
					    scaleControl: false,
						draggable: isDraggable,
						mapTypeControl: false,
						streetViewControl: false,
						styles: [
							{
							stylers: [
							{ hue: "##C6DB90" },
							{ saturation: -20 }
							]
							},{
							featureType: "road",
							elementType: "geometry",
							stylers: [
							{ lightness: 100 },
							{ visibility: "simplified" }
							]
							}
						]
			        });
			        var initMarker = $(".show-todays-events .simcal-events li:first .simcal-event-details");
			        ShackMap.loadMarker(initMarker);
			    },
			
			    codeMarker: function(title, address, time, eventvan, phoneHtml) {
			        phoneHtml = phoneHtml || '';
			        geocoder = new google.maps.Geocoder();
		            if (eventvan == 'simcal-event-van3') {
			            var pointerImage =  '<?php echo get_stylesheet_directory_uri(); ?>/img/pointer-3.png';
		            } else if (eventvan == 'simcal-event-van2') {
			            var pointerImage =  '<?php echo get_stylesheet_directory_uri(); ?>/img/pointer-2.png';
		            } else {
			            var pointerImage =  '<?php echo get_stylesheet_directory_uri(); ?>/img/pointer-1.png';
		            }
			        geocoder.geocode( { 'address': address}, function(results, status) {
			            if (status == google.maps.GeocoderStatus.OK) {
				            var pinIcon = new google.maps.MarkerImage(
							   pointerImage,null,null,null,new google.maps.Size(63, 41)
							);
			                ShackMap.map.setCenter(results[0].geometry.location);
			                if (ShackMap.marker) ShackMap.marker.setMap(null);
			                if (ShackMap.marker) delete ShackMap.marker;
			                ShackMap.marker = new google.maps.Marker({
								icon: pinIcon,
			                    map: ShackMap.map, 
			                    position: results[0].geometry.location
			                });
							ShackMap.infowindow = new google.maps.InfoWindow({
								content: "<strong>" + title + '<br />' + time + "</strong>" + (phoneHtml || '') + "<br />" + address,
								maxWidth: 260
							});
							ShackMap.infowindow.open(ShackMap.map,ShackMap.marker);
			            } else {
			                //alert("Geocode was not successful for the following reason: " + status);
			            }
			        });
			    },
			    
			    loadMarker: function(item) {
					$('.simcal-event-details').removeClass("view-on-map-active");
					item.addClass('view-on-map-active');
					var event = item.find('h3 > span');
					var eventvan = event.find("span").attr('class');
					var eventtitle = event.html();
					var $phoneSub = item.find('.simcal-event-phone-sub').first();
					var phoneHtml = $phoneSub.length ? '<div class="map-infowindow-phone">' + $phoneSub.html() + '</div>' : '';
					var eventlocation = item.find('h4 span').text();
					var eventtime = item.find('p:first-of-type').text();
					ShackMap.codeMarker(eventtitle, eventlocation, eventtime, eventvan, phoneHtml);
			    }
			    
			};
			
			// Onload handler to fire off the app.
			google.maps.event.addDomListener(window, 'load', ShackMap.init);
			google.maps.event.addDomListener(window, "resize", function() {
				var center = ShackMap.map.getCenter();
				google.maps.event.trigger(ShackMap.map, "resize");
				ShackMap.map.setCenter(center);
			});
			
			$(".find-us").on( "click", ".simcal-day:not(.simcal-day-empty) .simcal-event-details", function(e) {
				
				$('.map-canvas-closed').removeClass('map-canvas-closed').addClass('map-canvas-open').slideDown();
				ShackMap.loadMarker($(this));
				$('html, body').animate({
				    scrollTop: $(".find-us-title h2").offset().top
				}, 500);
				if ($(".map-canvas-container").height() == '0') {
					//$(".map-canvas-container").height(mapHeight);
					$(".map-canvas-container").removeClass('map-no-events').removeClass('map-canvas-closed');
				}
			});
			
		<?php } ?>
		
		<?php 
		// If is pizzas page
		if (is_page(6)) { ?>
		var priceSectionTabs = $(".feature-tabbed-prices");
		if (priceSectionTabs.length > 0) {
			var priceSectionNav = priceSectionTabs.find('.section-inner').prepend('<ul class="tabbed-prices-nav"></ul>').find('.tabbed-prices-nav');
			$(priceSectionTabs).find('.col').each(function() {
				if($(this).attr('id')) {
					var priceSectionID = $(this).attr('id');
					var priceSectionTitle = $(this).find('h2').text();
					priceSectionNav.append('<li class="'+priceSectionID+'"><span>'+priceSectionTitle+'</span></li>');
				}
			});
			$(priceSectionTabs).find('.cols:first-of-type').addClass('tabbed-active');
			priceSectionNav.find('li:first-of-type').addClass('tabbed-prices-active');
			$(priceSectionNav).on('click', 'li', function() {
				$(priceSectionTabs).find('.cols').removeClass('tabbed-active');
				priceSectionNav.find('li').removeClass('tabbed-prices-active');
				var target = $(this).attr('class');
				$(this).addClass('tabbed-prices-active');
				$(priceSectionTabs).find('.cols .col#'+target).parent().addClass('tabbed-active');
			});
		}
		
		var anchor = window.location.hash;
		if(anchor) {
				
			$(priceSectionTabs).find('.cols').removeClass('tabbed-active');
			$('.tabbed-prices-nav li').removeClass('tabbed-prices-active');
			
			var anchorID = anchor.substr(anchor.length - 1);
			
			$('.tabbed-prices-nav').find('li:nth-of-type('+anchorID+')').addClass('tabbed-prices-active');
			$(priceSectionTabs).find('.cols:nth-of-type('+anchorID+')').addClass('tabbed-active');
			
		    var offset = -100;
		    $('html, body').animate({
		        scrollTop: ($(window.location.hash).offset().top + offset) + 'px'
		    }, 1000, 'swing');
		}  
		
		<?php } ?>

	</script>
	
</body>
</html>
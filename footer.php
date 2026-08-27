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
	if ( shortcode_exists( 'calendar' ) && ( is_front_page() || is_page( 6 ) ) ) {
		get_template_part( 'components/component', 'call_ahead_source' );
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
			<?php $thedoughshack_vans_post_id = function_exists( 'thedoughshack_get_vans_acf_post_id' ) ? thedoughshack_get_vans_acf_post_id() : 2; ?>

			var thedoughshackWeekDays = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
			var thedoughshackVanConfig = <?php echo wp_json_encode( function_exists( 'thedoughshack_get_vans_config' ) ? thedoughshack_get_vans_config() : array() ); ?>;
			var isDraggable = !('ontouchstart' in document.documentElement);

			function thedoughshackIsMapNearestVanMode() {
				return typeof ShackMap !== 'undefined' && ShackMap.loadMarker;
			}

			function thedoughshackGetVanNumberFromEvent($event) {
				var $vanIcon = $event.find('[class*="simcal-event-van-tel-"]').first();
				if (!$vanIcon.length) {
					return '';
				}
				var match = ($vanIcon.attr('class') || '').match(/simcal-event-van-tel-(\d+)/);
				return match ? match[1] : '';
			}

			function thedoughshackEventIsTodayContext($event) {
				if ($event.closest('#lightbox, .thedoughshack-call-ahead-lightbox').length) {
					return true;
				}

				var $day = $event.closest('.simcal-day');
				var $listContainer = $event.closest('.simcal-events-list-container');

				// Week view (Prev/Next): only the stamped calendar-today row counts — not Fri when browsing ahead.
				if ($listContainer.length && $listContainer.data('thedoughshackWeekNav')) {
					if (!$day.length || !$day.hasClass('simcal-enabled')) {
						return false;
					}
					return $day.attr('data-thedoughshack-calendar-today') === '1';
				}

				// Today-only SimCal feed (no week navigation on this list).
				if ($event.closest('.show-todays-events').length) {
					return true;
				}

				if ($event.closest('#thedoughshack-call-ahead-source').length) {
					return true;
				}

				if ($day.length && $day.hasClass('simcal-enabled') && $day.attr('data-thedoughshack-calendar-today') === '1') {
					return true;
				}

				return false;
			}

			function thedoughshackUnwrapEventCall($event) {
				var $link = $event.children('a.simcal-event-call').first();
				if (!$link.length) {
					return;
				}
				if (!$link.data('thedoughshackTelHref')) {
					$link.data('thedoughshackTelHref', ($link.attr('href') || '').trim());
				}
				$link.replaceWith($link.contents());
			}

			function thedoughshackRestoreEventCall($event, vanConfig) {
				if ($event.children('a.simcal-event-call').length) {
					return;
				}
				var telHref = vanConfig.tel ? 'tel:' + vanConfig.tel : '';
				var $phoneSub = $event.find('.simcal-event-phone-sub').first();
				if ($phoneSub.length) {
					var fromPhone = 'tel:' + $.trim($phoneSub.text()).replace(/[^\d+]/g, '');
					if (fromPhone !== 'tel:') {
						telHref = fromPhone;
					}
				}
				if (telHref && telHref !== 'tel:') {
					$event.wrapInner('<a href="' + telHref + '" class="simcal-event-call"></a>');
				}
			}

			function thedoughshackNormalizeLocationMatchText(text) {
				return $.trim(String(text || '')).toLowerCase().replace(/\s+/g, ' ');
			}

			function thedoughshackEventTitleText($event) {
				var $h3 = $event.find('.simcal-event-details h3').first().clone();
				$h3.find('[class*="simcal-event-van"]').remove();
				return $.trim($h3.text()).replace(/\s+/g, ' ');
			}

			function thedoughshackPartialTitleMatch(needle, haystack) {
				needle = thedoughshackNormalizeLocationMatchText(needle);
				haystack = thedoughshackNormalizeLocationMatchText(haystack);
				if (needle === '' || haystack === '') {
					return false;
				}
				return haystack.indexOf(needle) !== -1 || needle.indexOf(haystack) !== -1;
			}

			function thedoughshackGetTodayEventsScope($fromEvent) {
				var $roots = $fromEvent.closest('#lightbox, .thedoughshack-call-ahead-lightbox, .show-todays-events, .simcal-calendar, #thedoughshack-call-ahead-source');
				if (!$roots.length) {
					$roots = $(document);
				}
				return $roots.first().find('.simcal-event').filter(function() {
					return thedoughshackEventIsTodayContext($(this));
				});
			}

			function thedoughshackFindMatchingTodayEvent(nearestVanText, $currentEvent) {
				var match = null;
				thedoughshackGetTodayEventsScope($currentEvent).each(function() {
					var $candidate = $(this);
					if ($candidate.is($currentEvent) || $candidate.hasClass('simcal-event-out-of-service')) {
						return;
					}
					if (thedoughshackPartialTitleMatch(nearestVanText, thedoughshackEventTitleText($candidate))) {
						match = $candidate;
						return false;
					}
				});
				return match;
			}

			function thedoughshackGetEventTelHref($event) {
				var $call = $event.children('a.simcal-event-call').first();
				if ($call.length) {
					var href = ($call.attr('href') || '').trim();
					if (/^tel:/i.test(href)) {
						return href;
					}
				}
				var $phone = $event.find('.simcal-event-phone-sub').first();
				if ($phone.length) {
					var rawPhone = $.trim($phone.text());
					if (rawPhone !== '') {
						var telHref = 'tel:' + rawPhone.replace(/[^\d+]/g, '');
						if (telHref !== 'tel:') {
							return telHref;
						}
					}
				}
				var vanNum = thedoughshackGetVanNumberFromEvent($event);
				if (vanNum && thedoughshackVanConfig[vanNum] && thedoughshackVanConfig[vanNum].tel) {
					return 'tel:' + thedoughshackVanConfig[vanNum].tel;
				}
				return '';
			}

			function thedoughshackUpdateNearestVanLine($alert, nearestVan, $currentEvent) {
				var text = $.trim(nearestVan || '');
				var $nearest = $alert.find('.simcal-event-nearest-van').first();
				if (text === '') {
					$nearest.remove();
					return;
				}
				if (!$nearest.length) {
					$nearest = $('<p class="simcal-event-nearest-van"><span class="simcal-event-nearest-van-label">Nearest van:</span> </p>');
					$alert.append($nearest);
				}
				$nearest.find('.simcal-event-nearest-van-text, .simcal-event-nearest-van-link').remove();

				var $matched = $currentEvent && $currentEvent.length
					? thedoughshackFindMatchingTodayEvent(text, $currentEvent)
					: null;
				var telHref = $matched ? thedoughshackGetEventTelHref($matched) : '';

				if ($matched) {
					var $matchedDetails = $matched.find('.simcal-event-details').first();
					var $link = $('<a class="simcal-event-nearest-van-link"></a>').text(text);

					if (telHref && !thedoughshackIsMapNearestVanMode()) {
						$link.attr('href', telHref).addClass('simcal-event-call');
					} else {
						$link.attr('href', '#').addClass('simcal-event-nearest-van-link--map');
					}

					$link.on('click', function(e) {
						if (thedoughshackIsMapNearestVanMode() && $matchedDetails.length) {
							e.preventDefault();
							if (typeof thedoughshackOpenMapForEventDetails === 'function') {
								thedoughshackOpenMapForEventDetails($matchedDetails);
							}
							return false;
						}
						if (!telHref) {
							e.preventDefault();
						}
					});

					$nearest.append($link);
				} else {
					$nearest.append($('<span class="simcal-event-nearest-van-text"></span>').text(text));
				}
			}

			function thedoughshackBuildOutOfServiceAlert(vanConfig, $currentEvent) {
				var $alert = $('<div class="simcal-event-out-of-service-alert" role="status"></div>');
				$alert.append($('<span class="simcal-event-out-of-service-message"></span>').text(vanConfig.message));
				thedoughshackUpdateNearestVanLine($alert, vanConfig.nearest_van, $currentEvent);
				return $alert;
			}

			function thedoughshackEnsureOutOfServiceAlert($details, vanConfig) {
				var $currentEvent = $details.closest('.simcal-event');
				$details.find('.simcal-event-out-of-service-alert, .simcal-event-out-of-service-message, .simcal-event-nearest-van').remove();

				var $alert = thedoughshackBuildOutOfServiceAlert(vanConfig, $currentEvent);
				if ($details.find('h4').length) {
					$details.find('h4').first().before($alert);
				} else {
					$details.find('h3').first().after($alert);
				}
				return $alert;
			}

			function thedoughshackSyncVanOutOfService($scope) {
				$scope = $scope || $(document);

				$scope.find('.simcal-event').each(function() {
					var $event = $(this);
					var vanNum = thedoughshackGetVanNumberFromEvent($event);
					if (!vanNum || !thedoughshackVanConfig[vanNum]) {
						return;
					}

					var vanConfig = thedoughshackVanConfig[vanNum];
					var shouldDisable = vanConfig.out_of_service && thedoughshackEventIsTodayContext($event);
					var $details = $event.find('.simcal-event-details').first();

					if (shouldDisable) {
						thedoughshackUnwrapEventCall($event);
						$event.addClass('simcal-event-out-of-service');
						$details.addClass('simcal-event-details--out-of-service');
						$details.removeClass('view-on-map-active');

						var $phoneSub = $details.find('.simcal-event-phone-sub').first();
						if ($phoneSub.length) {
							$phoneSub.hide();
						}

						thedoughshackEnsureOutOfServiceAlert($details, vanConfig);
						return;
					}

					$event.removeClass('simcal-event-out-of-service');
					$details.removeClass('simcal-event-details--out-of-service');
					$details.find('.simcal-event-out-of-service-alert').remove();
					$details.find('.simcal-event-out-of-service-message').filter(function() {
						return !$(this).closest('.simcal-event-out-of-service-alert').length;
					}).remove();

					var $phoneSub = $details.find('.simcal-event-phone-sub').first();
					if ($phoneSub.length) {
						$phoneSub.show();
					} else if (vanConfig.tel_display || vanConfig.tel) {
						$phoneSub = $('<p class="simcal-event-phone-sub"></p>').text(vanConfig.tel_display || vanConfig.tel);
						$details.find('h3').first().after($phoneSub);
					}

					thedoughshackRestoreEventCall($event, vanConfig);
				});
			}

			function thedoughshackApplyVanIcons($scope) {
				$scope = $scope || $(document);
				$scope.find('.simcal-event-details').each(function() {
					var $details = $(this);
					var $targets = $details.find('.simcal-event-title');
					if (!$targets.length) {
						$targets = $details.find('h3').first();
					}
					if (!$targets.length) {
						return;
					}
					$targets.each(function() {
						var $el = $(this);
						var html = $el.html();
						if (!html || /simcal-event-van\d/.test(html)) {
							return;
						}
						<?php
						if ( have_rows( 'vans', $thedoughshack_vans_post_id ) ) :
							while ( have_rows( 'vans', $thedoughshack_vans_post_id ) ) :
								the_row();
								$van = get_sub_field( 'van_number' );
								if ( '2' === $van || '3' === $van ) {
									$icon = $van;
								} else {
									$icon = '1';
								}
								?>
						html = html.replace(/Van\s*<?php echo esc_js( $van ); ?>\s*:\s*/gi, '<span class="simcal-event-van<?php echo esc_js( $icon ); ?> simcal-event-van-tel-<?php echo esc_js( $van ); ?>" aria-label="Van <?php echo esc_js( $van ); ?>"><span class="screen-reader-text">Van <?php echo esc_js( $van ); ?></span></span>');
								<?php
							endwhile;
						endif;
						?>
						html = html.replace(/Van\s*\d+\s*:\s*/gi, '');
						$el.html(html);
					});
				});
			}

			function thedoughshackStoreCallAheadWeekHtml($scope) {
				$scope = $scope || $('#thedoughshack-call-ahead-source');
				$scope.find('.simcal-events-list-container').each(function() {
					var $container = $(this);
					if ($container.data('thedoughshackWeekHtml')) {
						return;
					}
					var html = $container.html();
					if (html && $.trim(html) !== '') {
						$container.data('thedoughshackWeekHtml', html);
					}
				});
			}

			function thedoughshackBuildCallAheadTodayBlock(weekHtml) {
				var todayNum = new Date().getDay();
				var $scratch = $('<div class="simcal-events-list-container"/>').html(weekHtml);
				var $day = $scratch.find('.simcal-weekday-' + todayNum).first();
				if (!$day.length) {
					return {
						header: thedoughshackWeekDays[todayNum],
						dayHtml: '<div class="simcal-weekday-' + todayNum + ' simcal-day simcal-day-empty simcal-enabled" data-thedoughshack-calendar-today="1"><ul class="simcal-events"><li class="simcal-event"><div class="simcal-event-details"><p><span class="simcal-event-start simcal-event-start-date">' + thedoughshackWeekDays[todayNum] + '</span></p><h3>No party today.</h3><h4>Please try another day.</h4></div></li></ul></div>'
					};
				}
				var $dayClone = $($day.get(0).outerHTML);
				$dayClone.addClass('simcal-enabled').removeClass('simcal-disabled').attr('data-thedoughshack-calendar-today', '1');
				return {
					header: thedoughshackGetDayHeaderText($dayClone),
					dayHtml: $dayClone.get(0).outerHTML
				};
			}

			function thedoughshackInitCallAheadSourceTodayOnly($scope) {
				$scope = $scope || $(document);
				var todayNum = new Date().getDay();
				var j = todayNum;

				$scope.find('.simcal-events-list-container').each(function() {
					var $container = $(this);
					if ($container.data('thedoughshackTodayOnly')) {
						return;
					}
					$container.data('thedoughshackTodayOnly', true);
					$container.prev('.events-prev-next').remove();

					var $weekday = $container.find('.simcal-weekday-' + j).first();
					var output;
					if ($weekday.length < 1) {
						output = '<div class="simcal-weekday-' + j + ' simcal-day simcal-day-empty simcal-enabled" data-thedoughshack-calendar-today="1"><ul class="simcal-events"><li class="simcal-event"><div class="simcal-event-details"><p><span class="simcal-event-start simcal-event-start-date">' + thedoughshackWeekDays[j] + '</span></p><h3>No party today.</h3><h4>Please try another day.</h4></div></li></ul></div>';
					} else {
						output = $weekday.get(0).outerHTML;
					}
					$container.html(output);
					$container.find('.simcal-day').attr('data-thedoughshack-calendar-today', '1');
				});
			}

			function thedoughshackInitCalendarWeekNav($scope) {
				$scope = $scope || $(document);
				var todayNum = new Date().getDay();

				$scope.find('.simcal-events-list-container').each(function() {
					var $container = $(this);
					if ($container.data('thedoughshackWeekNav')) {
						return;
					}
					$container.data('thedoughshackWeekNav', true);

					var output = '';
					var i, j;
					for (i = todayNum; i <= (todayNum + 6); i++) {
						j = i > 6 ? i - 7 : i;
						var $weekday = $container.find('.simcal-weekday-' + j).first();
						if ($weekday.length < 1) {
							output += '<div class="simcal-weekday-' + j + ' simcal-day simcal-day-empty"><ul class="simcal-events"><li class="simcal-event"><div class="simcal-event-details"><p><span class="simcal-event-start simcal-event-start-date">' + thedoughshackWeekDays[j] + '</span></p><h3>No party today.</h3><h4>Please try another day.</h4></div></li></ul></div>';
						} else {
							output += $weekday.get(0).outerHTML;
						}
					}
					$container.html(output);

					var $nav = $container.prev('.events-prev-next');
					if (!$nav.length) {
						$nav = $('<div class="events-prev-next"><span class="events-prev">&#8249;&nbsp;Prev</span> <span class="events-today"></span> <span class="events-next">Next&nbsp;&#8250;</span></div>');
						$container.before($nav);
					}
					$nav.find('.events-today').text(thedoughshackGetDayHeaderText($container.find('.simcal-day:first-child')));
					$container.find('.simcal-day').addClass('simcal-disabled');
					$nav.find('.events-prev').addClass('simcal-disabled');
					$container.find('.simcal-day:first-child').removeClass('simcal-disabled').addClass('simcal-enabled').attr('data-thedoughshack-calendar-today', '1');
				});
			}

			function thedoughshackWrapVanTelLinks($scope) {
				$scope = $scope || $(document);
				<?php
				if ( have_rows( 'vans', $thedoughshack_vans_post_id ) ) :
					while ( have_rows( 'vans', $thedoughshack_vans_post_id ) ) :
						the_row();
						$van    = get_sub_field( 'van_number' );
						$vanTel = get_sub_field( 'van_telephone_number' );
						if ( $van && $vanTel ) {
							$van_tel_href = preg_replace( '/\s+/', '', $vanTel );
							?>
				$scope.find('.simcal-event-van-tel-<?php echo esc_js( $van ); ?>').each(function() {
					var $event = $(this).closest('.simcal-event');
					if (!$event.length || $event.children('a.simcal-event-call').length) {
						return;
					}
					$event.wrapInner('<a href="tel:<?php echo esc_js( $van_tel_href ); ?>" class="simcal-event-call"></a>');
				});
							<?php
						}
					endwhile;
				endif;
				?>
			}

			function thedoughshackInitCallAheadCalendars() {
				var $source = $('#thedoughshack-call-ahead-source');
				thedoughshackStoreCallAheadWeekHtml($source);
				thedoughshackApplyVanIcons($source);
				thedoughshackInitCallAheadSourceTodayOnly($source);
				if (typeof thedoughshackSplitTelFromEventTitles === 'function') {
					thedoughshackSplitTelFromEventTitles($source);
				}
				<?php if ( is_page( 6 ) ) { ?>
				thedoughshackWrapVanTelLinks($source);
				<?php } ?>
				thedoughshackSyncVanOutOfService($source);

				var $calendars = $('.feature-find-us, .page-id-6 .simcal-calendar');
				if ($calendars.length) {
					thedoughshackApplyVanIcons($calendars);
					thedoughshackInitCalendarWeekNav($calendars);
					if (typeof thedoughshackSplitTelFromEventTitles === 'function') {
						thedoughshackSplitTelFromEventTitles($calendars);
					}
					<?php if ( is_page( 6 ) ) { ?>
					thedoughshackWrapVanTelLinks($('.page-id-6 .simcal-calendar'));
					<?php } ?>
					thedoughshackSyncVanOutOfService($calendars);
				}
			}

			function thedoughshackGetDayHeaderText($day) {
				var fullDate = $.trim($day.find('.simcal-event-start-date').first().text());
				if (fullDate !== '') {
					return fullDate;
				}
				return $.trim($day.find('.simcal-event-start').first().text());
			}

			var thedoughshackCallAheadTakeaway = <?php echo wp_json_encode( function_exists( 'thedoughshack_get_call_ahead_takeaway_markup' ) ? thedoughshack_get_call_ahead_takeaway_markup() : '' ); ?>;

			$(document).on('click', '.events-next', function() {
				var $nav = $(this).closest('.events-prev-next');
				var $container = $nav.next('.simcal-events-list-container');
				var $weekday = $container.find('.simcal-day.simcal-enabled');
				var $weekdayNext = $weekday.next('.simcal-day');
				if ($weekdayNext.length > 0) {
					$nav.find('.events-today').text(thedoughshackGetDayHeaderText($weekdayNext));
					$weekday.slideUp().removeClass('simcal-enabled');
					$weekdayNext.slideDown().addClass('simcal-enabled');
					if ($weekdayNext.next('.simcal-day').length === 0) {
						$nav.find('.events-next').removeClass('simcal-enabled').addClass('simcal-disabled');
					}
					if ($weekday.prev('.simcal-day').length === 0) {
						$nav.find('.events-prev').removeClass('simcal-disabled').addClass('simcal-enabled');
					}
					thedoughshackSyncVanOutOfService($container);
				}
			});

			$(document).on('click', '.events-prev', function() {
				var $nav = $(this).closest('.events-prev-next');
				var $container = $nav.next('.simcal-events-list-container');
				var $weekday = $container.find('.simcal-day.simcal-enabled');
				var $weekdayPrev = $weekday.prev('.simcal-day');
				if ($weekdayPrev.length > 0) {
					$nav.find('.events-today').text(thedoughshackGetDayHeaderText($weekdayPrev));
					$weekday.slideUp().removeClass('simcal-enabled');
					$weekdayPrev.slideDown().addClass('simcal-enabled');
					if ($weekdayPrev.prev('.simcal-day').length === 0) {
						$nav.find('.events-prev').removeClass('simcal-enabled').addClass('simcal-disabled');
					}
					if ($weekday.next('.simcal-day').length === 0) {
						$nav.find('.events-next').removeClass('simcal-disabled').addClass('simcal-enabled');
					}
					thedoughshackSyncVanOutOfService($container);
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
			$('.find-us-title h2 + p').append('<small>Click any item to view on the map&hellip;</small>');
			$('.show-weekly-events').after('<div class="events-show" tabindex="0"><p>Click to view rest of week&hellip;</p></div>');
			$(".events-show").on( "click", function() {
				$(".show-weekly-events").slideToggle(500, function() {
					$(".events-show").html($(this).is(':visible') ? "[x] Close" : "<p>Click to view rest of week&hellip;</p>");
					if ($(this).is(':visible')) {
						thedoughshackSyncVanOutOfService($('.show-weekly-events'));
					}
				});
			});
		
		<?php }	?>
		
		<?php if (is_front_page() || is_page(6)) { ?>
			function thedoughshackSplitTelFromEventTitles($context) {
				var $targets = ($context && $context.length)
					? $context.find('.simcal-event-details')
					: $('.show-todays-events .simcal-event-details, .show-weekly-events .simcal-event-details, #thedoughshack-call-ahead-source .simcal-event-details');
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
						$sub.text(displayText);
					} else {
						var html = $span.html();
						if (html) {
							var m = html.match(/(?:<br\s*\/?>|\s|&nbsp;)*tel:\s*([+\d\s\u00a0().\-—]+)/i);
							if (m) {
								var rawPhone = m[1].replace(/[\s\u00a0]+/g, ' ').trim();
								if (rawPhone) {
									$span.html(html.replace(m[0], ' ').replace(/\s{2,}/g, ' ').trim());
									var $sub2 = $('<p class="simcal-event-phone-sub"></p>').insertAfter($h3);
									$sub2.text(rawPhone);
								}
							}
						}
					}

					// Plain text only — row-level simcal-event-call handles dialling; nested tel: links break that.
					$details.find('a[href]').filter(function() {
						if ($(this).hasClass('simcal-event-call')) {
							return false;
						}
						return /^tel:/i.test(($(this).attr('href') || '').trim());
					}).each(function() {
						var $link = $(this);
						var linkText = $.trim($link.text()) || ($link.attr('href') || '').replace(/^tel:\s*/i, '');
						$link.replaceWith(document.createTextNode(linkText));
					});
				});
			}

			function thedoughshackWrapRowsFromPhoneSub($scope) {
				$scope = $scope || $(document);
				$scope.find('.simcal-event').each(function() {
					var $event = $(this);
					if ($event.children('a.simcal-event-call').length) {
						return;
					}
					var $phone = $event.find('.simcal-event-phone-sub').first();
					if (!$phone.length) {
						return;
					}
					var rawPhone = $.trim($phone.text());
					if (rawPhone === '') {
						return;
					}
					var telHref = 'tel:' + rawPhone.replace(/[^\d+]/g, '');
					if (telHref === 'tel:') {
						return;
					}
					$event.wrapInner('<a href="' + telHref + '" class="simcal-event-call"></a>');
				});
			}

			thedoughshackInitCallAheadCalendars();

			if (!isDraggable) {
				<?php if ( is_front_page() || is_page( 6 ) ) { ?>
				$('.page-intro .section-inner').append('<a href="#" class="call-ahead lightbox">Call ahead</a>');
				<?php } ?>

				<?php if ( get_field( 'takeaway_phone', 2 ) && is_front_page() ) { ?>
					$('.find-us-takeaway h3').after('<a href="tel:<?php echo esc_js( get_field( 'takeaway_phone', 2 ) ); ?>" class="call-ahead"><?php echo esc_js( get_field( 'takeaway_phone', 2 ) ); ?></a>');
				<?php } ?>

				function thedoughshackOpenCallAheadLightbox(e) {
					e.preventDefault();
					$('#lightbox').remove();

					var $source = $('#thedoughshack-call-ahead-source');
					var $container = $source.find('.simcal-events-list-container').first();
					if (!$container.length) {
						return;
					}

					if (!$container.data('thedoughshackWeekHtml')) {
						thedoughshackStoreCallAheadWeekHtml($source);
					}
					var weekHtml = $container.data('thedoughshackWeekHtml');
					if (!weekHtml || $.trim(weekHtml) === '') {
						return;
					}

					var todayBlock = thedoughshackBuildCallAheadTodayBlock(weekHtml);
					var takeawayHtml = thedoughshackCallAheadTakeaway || '';

					var lightbox = '<div id="lightbox" class="thedoughshack-call-ahead-lightbox">' +
						'<p class="lightbox-close">Close</p>' +
						'<div class="feature-find-us feature-find-us--lightbox">' +
						'<div class="section-inner">' +
						'<div class="find-us">' +
						'<div class="show-weekly-events">' +
						'<div class="call-ahead-popup-header"><span class="call-ahead-popup-date">' + todayBlock.header + '</span></div>' +
						'<div class="simcal-calendar"><div class="simcal-events-list-container">' + todayBlock.dayHtml + '</div></div>' +
						'</div></div>' +
						takeawayHtml +
						'</div></div></div>';

					$('body').append(lightbox);
					$('#lightbox .lightbox-close').on('click', function() {
						$(this).closest('#lightbox').remove();
					});
					$('#lightbox .view-on-map-active').removeClass('view-on-map-active');
					thedoughshackApplyVanIcons($('#lightbox'));
					if (typeof thedoughshackSplitTelFromEventTitles === 'function') {
						thedoughshackSplitTelFromEventTitles($('#lightbox'));
					}
					thedoughshackWrapRowsFromPhoneSub($('#lightbox'));
					if ($('body').hasClass('page-id-6')) {
						thedoughshackWrapVanTelLinks($('#lightbox'));
					}
					thedoughshackSyncVanOutOfService($('#lightbox'));
				}

				$('.call-ahead.lightbox').on('click', thedoughshackOpenCallAheadLightbox);
			}
		<?php } ?>  
		
		<?php if (is_front_page()) { ?>
			function thedoughshackOpenMapForEventDetails($details) {
				if (!$details || !$details.length || typeof ShackMap === 'undefined' || !ShackMap.loadMarker) {
					return;
				}
				$('#lightbox.thedoughshack-call-ahead-lightbox').remove();
				$('.map-canvas-closed').removeClass('map-canvas-closed').addClass('map-canvas-open').slideDown();
				ShackMap.loadMarker($details);
				$('html, body').animate({
					scrollTop: $('.find-us-title h2').offset().top
				}, 500);
				if ($('.map-canvas-container').height() === 0) {
					$('.map-canvas-container').removeClass('map-no-events').removeClass('map-canvas-closed');
				}
			}

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
			
			$(".find-us").on( "click", ".simcal-day:not(.simcal-day-empty) .simcal-event-details, .show-todays-events .simcal-event-details", function(e) {
				if ($(this).closest('.simcal-event-out-of-service').length) {
					e.preventDefault();
					return;
				}

				thedoughshackOpenMapForEventDetails($(this));
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
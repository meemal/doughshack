<?php
/**
 * Hidden weekly calendar — single source for the mobile Call ahead lightbox (home + pizzas).
 *
 * @package The Dough Shack
 */

if ( ! function_exists( 'thedoughshack_get_weekly_calendar_shortcode' ) || ! shortcode_exists( 'calendar' ) ) {
	return;
}

$weekly = do_shortcode( thedoughshack_get_weekly_calendar_shortcode() );
if ( '' === trim( $weekly ) ) {
	return;
}
?>
<div id="thedoughshack-call-ahead-source" class="thedoughshack-call-ahead-source" hidden aria-hidden="true">
	<div class="find-us find-us--call-ahead-source">
		<div class="show-weekly-events">
			<?php echo $weekly; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SimCal shortcode HTML ?>
		</div>
	</div>
</div>

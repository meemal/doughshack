<?php
/**
 * The Dough Shack functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package The Dough Shack
 */

if ( ! function_exists( 'thedoughshack_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function thedoughshack_setup() {

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', 'thedoughshack' ),
	) );

	// Add custom image size
	add_image_size( 'midsize', 625, 450, true );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );
}
endif; // thedoughshack_setup
add_action( 'after_setup_theme', 'thedoughshack_setup' );

/**
 * ACF Local JSON: field group definitions live in the theme (acf-json/) for version control and deploys.
 *
 * @link https://www.advancedcustomfields.com/resources/local-json/
 */
function thedoughshack_acf_json_save_path( $path ) {
	return get_stylesheet_directory() . '/acf-json';
}

function thedoughshack_acf_json_load_paths( $paths ) {
	if ( isset( $paths[0] ) ) {
		unset( $paths[0] );
	}
	$paths[] = get_stylesheet_directory() . '/acf-json';
	return $paths;
}

if ( defined( 'ACF_VERSION' ) ) {
	add_filter( 'acf/settings/save_json', 'thedoughshack_acf_json_save_path' );
	add_filter( 'acf/settings/load_json', 'thedoughshack_acf_json_load_paths' );
}

/**
 * Enqueue scripts and styles.
 */
function thedoughshack_scripts() {
	// Soft cache buster - Set style.css to theme version
	$themeVersion = wp_get_theme()->get('Version');

	// Hard cache buster - time() when developing locally or site is not public.
	// Prefer WP_ENVIRONMENT_TYPE=local (wp_get_environment_type), or define THEDOUGHSHACK_DEV in wp-config.php.
	$hard_cache_bust = ( 0 === (int) get_option( 'blog_public' ) );
	if ( defined( 'THEDOUGHSHACK_DEV' ) && THEDOUGHSHACK_DEV ) {
		$hard_cache_bust = true;
	} elseif ( function_exists( 'wp_get_environment_type' ) && 'local' === wp_get_environment_type() ) {
		$hard_cache_bust = true;
	} elseif ( ! empty( $_SERVER['SERVER_ADDR'] ) && in_array( $_SERVER['SERVER_ADDR'], array( '127.0.0.1', '::1' ), true ) ) {
		$hard_cache_bust = true;
	}
	if ( $hard_cache_bust ) {
		$themeVersion = (string) time();
	}
	wp_enqueue_style( 'thedoughshack-style', get_stylesheet_uri(), array(), $themeVersion );

	wp_enqueue_script( 'thedoughshack-tools', get_template_directory_uri() . '/js/min/tools-min.js', array(), '', true );

	if (!is_admin()) {
		wp_deregister_script('jquery');
		wp_register_script('jquery', "http" . ($_SERVER['SERVER_PORT'] == 443 ? "s" : "") . "://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js", false, null);
		wp_enqueue_script('jquery');
	}

	wp_enqueue_script( 'thedoughshack-plugins', get_template_directory_uri() . '/js/min/plugins-min.js', array('jquery'), '', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'thedoughshack_scripts' );

/**
 * Weekly SimCal shortcode for Call ahead lightbox (shared home + pizzas).
 * Filter with thedoughshack_weekly_calendar_shortcode to override.
 * Otherwise resolves from the front page content (first [calendar] id other than 508).
 *
 * @return string Shortcode tag, e.g. [calendar id="123"].
 */
function thedoughshack_get_weekly_calendar_shortcode() {
	$filtered = apply_filters( 'thedoughshack_weekly_calendar_shortcode', '' );
	if ( is_string( $filtered ) && '' !== trim( $filtered ) ) {
		return trim( $filtered );
	}

	static $resolved = null;
	if ( null !== $resolved ) {
		return $resolved;
	}

	$resolved        = '[calendar id="508"]';
	$today_calendar  = 508;
	$home_id         = (int) get_option( 'page_on_front' );

	if ( $home_id > 0 ) {
		$home = get_post( $home_id );
		if ( $home instanceof WP_Post && preg_match_all( '/\[calendar\s+id=["\']?(\d+)/i', $home->post_content, $matches ) ) {
			$ids = array_map( 'intval', $matches[1] );
			foreach ( $ids as $id ) {
				if ( $today_calendar !== $id ) {
					$resolved = '[calendar id="' . $id . '"]';
					return $resolved;
				}
			}
		}
	}

	return $resolved;
}

/**
 * Post ID where the Home Page Content ACF vans repeater is stored.
 *
 * @return int
 */
function thedoughshack_get_vans_acf_post_id() {
	static $post_id = null;
	if ( null !== $post_id ) {
		return $post_id;
	}
	$post_id = (int) get_option( 'page_on_front' );
	if ( $post_id < 1 ) {
		$post_id = 2;
	}
	return $post_id;
}

/**
 * Post meta key storing the calendar date (Y-m-d) a van was marked out of service.
 *
 * @param string $van_key Normalized van number.
 * @return string
 */
function thedoughshack_get_van_oos_date_meta_key( $van_key ) {
	return '_thedoughshack_van_oos_date_' . $van_key;
}

/**
 * Whether a van marked out of service in ACF applies for today (site timezone).
 *
 * @param int    $post_id    Post storing the vans repeater.
 * @param string $van_key    Normalized van number.
 * @param bool   $is_checked Raw ACF van_out_of_service value.
 * @return bool
 */
function thedoughshack_is_van_out_of_service_today( $post_id, $van_key, $is_checked ) {
	if ( ! $is_checked ) {
		return false;
	}

	$meta_key    = thedoughshack_get_van_oos_date_meta_key( $van_key );
	$stored_date = get_post_meta( $post_id, $meta_key, true );
	$today       = wp_date( 'Y-m-d' );

	if ( ! is_string( $stored_date ) || '' === $stored_date ) {
		// Checkbox is on but no date yet — stamp today so today works; won't roll to tomorrow.
		update_post_meta( $post_id, $meta_key, $today );
		return true;
	}

	return $stored_date === $today;
}

/**
 * Record the out-of-service date when vans are saved (today only; does not roll over).
 *
 * @param int $post_id Saved post ID.
 */
function thedoughshack_sync_van_oos_dates( $post_id ) {
	$vans_post_id = thedoughshack_get_vans_acf_post_id();
	if ( (int) $post_id !== $vans_post_id || ! function_exists( 'have_rows' ) ) {
		return;
	}

	$today = wp_date( 'Y-m-d' );

	if ( ! have_rows( 'vans', $post_id ) ) {
		return;
	}

	while ( have_rows( 'vans', $post_id ) ) {
		the_row();
		$van_number = get_sub_field( 'van_number' );
		if ( null === $van_number || '' === $van_number ) {
			continue;
		}

		$van_key  = (string) (int) $van_number;
		$meta_key = thedoughshack_get_van_oos_date_meta_key( $van_key );

		if ( get_sub_field( 'van_out_of_service' ) ) {
			$stored_date = get_post_meta( $post_id, $meta_key, true );
			if ( ! is_string( $stored_date ) || '' === $stored_date ) {
				update_post_meta( $post_id, $meta_key, $today );
			}
		} else {
			delete_post_meta( $post_id, $meta_key );
		}
	}
}
add_action( 'acf/save_post', 'thedoughshack_sync_van_oos_dates', 20 );

/**
 * Van rows from ACF (number, tel, out-of-service flag, message) keyed by van number.
 *
 * @return array<string, array{tel: string, tel_display: string, out_of_service: bool, message: string, nearest_van: string}>
 */
function thedoughshack_get_vans_config() {
	static $config = null;
	if ( null !== $config ) {
		return $config;
	}

	$config  = array();
	$post_id = thedoughshack_get_vans_acf_post_id();

	if ( ! function_exists( 'have_rows' ) || ! have_rows( 'vans', $post_id ) ) {
		return $config;
	}

	while ( have_rows( 'vans', $post_id ) ) {
		the_row();
		$van_number = get_sub_field( 'van_number' );
		if ( null === $van_number || '' === $van_number ) {
			continue;
		}

		$van_key     = (string) (int) $van_number;
		$tel_display = trim( (string) get_sub_field( 'van_telephone_number' ) );
		$message     = trim( (string) get_sub_field( 'custom_message' ) );

		if ( '' === $message ) {
			$message = __( 'Van out of service', 'thedoughshack' );
		}

		$config[ $van_key ] = array(
			'tel'            => preg_replace( '/\s+/', '', $tel_display ),
			'tel_display'    => $tel_display,
			'out_of_service' => thedoughshack_is_van_out_of_service_today( $post_id, $van_key, (bool) get_sub_field( 'van_out_of_service' ) ),
			'message'        => $message,
			'nearest_van'    => trim( (string) get_sub_field( 'nearest_van' ) ),
		);
	}

	return $config;
}

/**
 * Takeaway row HTML appended inside the Call ahead lightbox (shared home + pizzas).
 *
 * @return string Markup or empty string.
 */
function thedoughshack_get_call_ahead_takeaway_markup() {
	if ( ! function_exists( 'get_field' ) || ! get_field( 'takeaway_phone', 2 ) ) {
		return '';
	}

	$phone   = get_field( 'takeaway_phone', 2 );
	$header  = get_field( 'takeaway_header', 2 );
	$tel     = preg_replace( '/\s+/', '', $phone );

	ob_start();
	?>
	<div class="find-us-takeaway find-us-takeaway--lightbox">
		<div class="find-us-takeaway-inner">
			<div class="find-us-takeaway-content">
				<ul class="simcal-events">
					<li class="simcal-event">
						<a href="tel:<?php echo esc_attr( $tel ); ?>" class="simcal-event-call">
							<div class="simcal-event-details">
								<?php if ( $header ) { ?>
									<p><?php echo esc_html( $header ); ?></p>
								<?php } ?>
								<h3>Call Now: <?php echo esc_html( $phone ); ?><span class="simcal-event-van3" aria-label="Van 3"><span class="screen-reader-text">Van 3</span></span></h3>
								<h4>&nbsp;</h4>
							</div>
						</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Custom template tags for this theme.

function add_async_attribute_twitter($tag, $handle) {
    if ( 'thedoughshack-embed-twitter' !== $handle )
        return $tag;
    return str_replace( ' src', ' async="async" src', $tag );
}
add_filter('script_loader_tag', 'add_async_attribute_twitter', 10, 2);

function add_async_defer_attribute_instagram($tag, $handle) {
    if ( 'thedoughshack-embed-instagram' !== $handle )
        return $tag;
    return str_replace( ' src', ' async="async" defer="defer" src', $tag );
}
add_filter('script_loader_tag', 'add_async_defer_attribute_instagram', 10, 2); */
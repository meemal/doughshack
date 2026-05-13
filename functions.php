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
 * Enqueue scripts and styles.
 */
function thedoughshack_scripts() {
	// Soft cache buster - Set style.css to theme version
	$themeVersion = wp_get_theme()->get('Version');
	// Hard cache buster - Set style.css to current time if on localhost or site is not public
	if ( $_SERVER["SERVER_ADDR"] == '127.0.0.1' || 0 == get_option( 'blog_public' )) {
		$themeVersion = time();
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
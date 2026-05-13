<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package The Dough Shack
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="format-detection" content="telephone=no">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/min/modernizr.custom-min.js"></script>
<script src="https://use.typekit.net/fbj0xhz.js"></script>
<script>try{Typekit.load({ async: true });}catch(e){}</script>
<?php wp_head(); ?>
<!--[if (gte IE 7)&(lte IE 8)]>
    <script src="<?php echo get_stylesheet_directory_uri(); ?>/js/min/respond-min.js"></script>
    <script src="<?php echo get_stylesheet_directory_uri(); ?>/js/min/selectivizr-min.js"></script>
<![endif]-->
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_home_url(); ?>/apple-touch-icon.png">
<link rel="icon" type="image/png" href="<?php echo get_home_url(); ?>/favicon-32x32.png" sizes="32x32">
<link rel="manifest" href="<?php echo get_home_url(); ?>/manifest.json">
<link rel="mask-icon" href="<?php echo get_home_url(); ?>/safari-pinned-tab.svg" color="#5bbad5">
<meta name="theme-color" content="#ffffff">
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
 
  ga('create', 'UA-82063392-1', 'auto');
  ga('send', 'pageview');
 
</script>
	<meta name="google-site-verification" content="gMo8v_CSA5ftKSI0kv4SnSq6K6aPJGEXOyUbGgIvius" />
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'thedoughshack' ); ?></a>

	<header id="masthead" class="section site-header" role="banner">
		<div class="section-inner">
			<div class="site-branding">
				<?php if ( is_front_page() && is_home() ) : ?>
					<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" title="<?php bloginfo( 'name' ); ?> home page"><span class="screen-reader-text"><?php bloginfo( 'name' ); ?></span></a></h1>
				<?php else : ?>
					<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" title="<?php bloginfo( 'name' ); ?> home page"><span class="screen-reader-text"><?php bloginfo( 'name' ); ?></span></a></p>
				<?php endif; ?>
				<?php /* <p class="site-description"><?php bloginfo( 'description' ); ?></p> */ ?>
			</div><!-- .site-branding -->
	
			<nav class="main-navigation" role="navigation">
				<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-menu', 'depth' => '1' ) ); ?>
			</nav><!-- .main-navigation -->
		</div><!-- .section-inner -->
	</header><!-- .site-header -->

	<div id="content" class="site-content">

<?php
/**
 * Theme supports, menus and assets.
 *
 * @package AVA_Alliance
 */

defined( 'ABSPATH' ) || exit;

add_action( 'after_setup_theme', 'ava_setup' );
/**
 * Register theme features.
 */
function ava_setup() {
	load_theme_textdomain( 'ava-alliance', AVA_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/editor.css' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 123,
			'width'       => 266,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	add_theme_support(
		'editor-color-palette',
		array(
			array( 'name' => __( 'AVA Orange', 'ava-alliance' ), 'slug' => 'ava-orange', 'color' => '#FE9910' ),
			array( 'name' => __( 'AVA Amber', 'ava-alliance' ), 'slug' => 'ava-amber', 'color' => '#FEB228' ),
			array( 'name' => __( 'AVA Flame', 'ava-alliance' ), 'slug' => 'ava-flame', 'color' => '#EA5606' ),
			array( 'name' => __( 'AVA Brown', 'ava-alliance' ), 'slug' => 'ava-brown', 'color' => '#58362B' ),
			array( 'name' => __( 'AVA Espresso', 'ava-alliance' ), 'slug' => 'ava-espresso', 'color' => '#2E1B14' ),
			array( 'name' => __( 'AVA Cream', 'ava-alliance' ), 'slug' => 'ava-cream', 'color' => '#FFF6EC' ),
			array( 'name' => __( 'White', 'ava-alliance' ), 'slug' => 'white', 'color' => '#FFFFFF' ),
		)
	);

	register_nav_menus(
		array(
			'primary' => __( 'Main menu (header)', 'ava-alliance' ),
			'footer'  => __( 'Footer links', 'ava-alliance' ),
		)
	);

	add_image_size( 'ava-card', 720, 450, true );

	// Page excerpts feed the subtitle under the page title and the SEO description.
	add_post_type_support( 'page', 'excerpt' );
}

add_action( 'wp_enqueue_scripts', 'ava_assets' );
/**
 * Front-end CSS/JS. Everything is local: no external requests.
 */
function ava_assets() {
	wp_enqueue_style( 'ava-style', get_stylesheet_uri(), array(), AVA_VERSION );

	wp_enqueue_script( 'ava-theme', AVA_URI . '/assets/js/theme.js', array(), AVA_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );
	wp_localize_script(
		'ava-theme',
		'AVA',
		array(
			'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
			'errorMsg' => __( 'Sorry, something went wrong. Please try again.', 'ava-alliance' ),
		)
	);

	// Classic themes get global block styles; drop the classic-theme duplicate.
	wp_dequeue_style( 'classic-theme-styles' );
}

add_action( 'wp_head', 'ava_preload_fonts', 1 );
/**
 * Preload the two fonts used above the fold.
 */
function ava_preload_fonts() {
	foreach ( array( 'montserrat-latin-900-normal.woff2', 'nunito-sans-latin-400-normal.woff2' ) as $font ) {
		printf( '<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n", esc_url( AVA_URI . '/assets/fonts/' . $font ) );
	}
}

add_action( 'wp_head', 'ava_fallback_icons', 2 );
/**
 * Bundled favicon until a Site Icon is chosen in Customize > Site Identity.
 */
function ava_fallback_icons() {
	if ( has_site_icon() ) {
		return;
	}
	printf( '<link rel="icon" type="image/png" href="%s">' . "\n", esc_url( AVA_URI . '/assets/images/favicon.png' ) );
	printf( '<link rel="apple-touch-icon" href="%s">' . "\n", esc_url( AVA_URI . '/assets/images/apple-touch-icon.png' ) );
	echo '<meta name="theme-color" content="#FE9910">' . "\n";
}

add_filter( 'document_title_separator', 'ava_title_separator' );
/**
 * "Anti-Vaping Alliance | Student Founded & Led".
 *
 * @return string
 */
function ava_title_separator() {
	return '|';
}

add_filter( 'body_class', 'ava_body_classes' );
/**
 * Homepage scroll options for theme.js.
 *
 * @param array $classes Body classes.
 * @return array
 */
function ava_body_classes( $classes ) {
	if ( is_front_page() && ava_mod( 'stack_scroll' ) ) {
		$classes[] = 'ava-stack';
	}
	if ( ava_mod( 'reveal_motion' ) ) {
		$classes[] = 'ava-reveal';
	}
	return $classes;
}

add_filter( 'excerpt_length', 'ava_excerpt_length' );
/**
 * Shorter excerpts for cards.
 *
 * @return int
 */
function ava_excerpt_length() {
	return 28;
}

/*
 * Remove front-end bloat that a small site doesn't need.
 */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );

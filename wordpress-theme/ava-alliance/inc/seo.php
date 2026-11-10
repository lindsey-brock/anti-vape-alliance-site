<?php
/**
 * Basic SEO: meta description, canonical, Open Graph, Twitter card, JSON-LD.
 * Automatically disabled when a dedicated SEO plugin is active.
 *
 * @package AVA_Alliance
 */

defined( 'ABSPATH' ) || exit;

/**
 * Is an SEO plugin handling meta tags?
 *
 * @return bool
 */
function ava_seo_plugin_active() {
	$active = defined( 'WPSEO_VERSION' ) || class_exists( 'RankMath' ) || defined( 'SEOPRESS_VERSION' ) || defined( 'AIOSEO_VERSION' ) || defined( 'THE_SEO_FRAMEWORK_VERSION' );
	return (bool) apply_filters( 'ava_seo_plugin_active', $active );
}

/**
 * Description for the current view.
 *
 * @return string
 */
function ava_meta_description() {
	$desc = '';
	if ( is_front_page() ) {
		$desc = ava_mod( 'seo_description' );
	} elseif ( is_singular() ) {
		$post = get_queried_object();
		$desc = has_excerpt( $post ) ? get_the_excerpt( $post ) : wp_trim_words( wp_strip_all_tags( strip_shortcodes( $post->post_content ) ), 30, '…' );
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$desc = term_description();
	}
	if ( '' === trim( wp_strip_all_tags( (string) $desc ) ) ) {
		$desc = ava_mod( 'seo_description' );
	}
	return trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( (string) $desc ) ) );
}

/**
 * Sharing image URL for the current view.
 *
 * @return array{url:string,width:int,height:int}
 */
function ava_meta_image() {
	$id = 0;
	if ( is_singular() && has_post_thumbnail() ) {
		$id = get_post_thumbnail_id();
	}
	if ( ! $id ) {
		$id = (int) ava_mod( 'seo_image' );
	}
	if ( $id ) {
		$src = wp_get_attachment_image_src( $id, 'large' );
		if ( $src ) {
			return array( 'url' => $src[0], 'width' => (int) $src[1], 'height' => (int) $src[2] );
		}
	}
	return array( 'url' => AVA_URI . '/assets/images/social-share.jpg', 'width' => 1200, 'height' => 630 );
}

add_action( 'wp_head', 'ava_seo_tags', 5 );
/**
 * Print the tags.
 */
function ava_seo_tags() {
	if ( ava_seo_plugin_active() ) {
		return;
	}
	$desc  = ava_meta_description();
	$title = wp_get_document_title();
	$url   = is_singular() ? get_permalink() : ( is_front_page() || is_home() ? home_url( '/' ) : '' );
	$img   = ava_meta_image();
	$type  = is_singular( 'post' ) ? 'article' : 'website';

	if ( $desc ) {
		printf( '<meta name="description" content="%s">' . "\n", esc_attr( $desc ) );
	}
	// WordPress prints a canonical for singular views; add one for the blog/home view.
	if ( ! is_singular() && ( is_front_page() || is_home() ) ) {
		printf( '<link rel="canonical" href="%s">' . "\n", esc_url( home_url( '/' ) ) );
	}

	$og = array(
		'og:type'      => $type,
		'og:site_name' => get_bloginfo( 'name' ),
		'og:title'     => $title,
		'og:description' => $desc,
		'og:url'       => $url,
		'og:image'     => $img['url'],
		'og:image:width'  => $img['width'],
		'og:image:height' => $img['height'],
		'og:locale'    => str_replace( '-', '_', get_bloginfo( 'language' ) ),
	);
	foreach ( $og as $prop => $content ) {
		if ( '' !== (string) $content ) {
			printf( '<meta property="%s" content="%s">' . "\n", esc_attr( $prop ), esc_attr( $content ) );
		}
	}
	printf( '<meta name="twitter:card" content="summary_large_image">' . "\n" );
	printf( '<meta name="twitter:title" content="%s">' . "\n", esc_attr( $title ) );
	if ( $desc ) {
		printf( '<meta name="twitter:description" content="%s">' . "\n", esc_attr( $desc ) );
	}
	printf( '<meta name="twitter:image" content="%s">' . "\n", esc_url( $img['url'] ) );

	if ( is_front_page() ) {
		$logo_id = (int) get_theme_mod( 'custom_logo' );
		$logo    = $logo_id ? wp_get_attachment_image_url( $logo_id, 'full' ) : AVA_URI . '/assets/images/ava-logo.webp';
		$org     = array(
			'@context'    => 'https://schema.org',
			'@type'       => 'NGO',
			'name'        => get_bloginfo( 'name' ),
			'url'         => home_url( '/' ),
			'logo'        => $logo,
			'description' => $desc,
		);
		$email = ava_mod( 'contact_email' );
		if ( is_email( $email ) ) {
			$org['email'] = $email;
		}
		echo '<script type="application/ld+json">' . wp_json_encode( $org, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . "</script>\n";
	}
}

add_filter( 'document_title_parts', 'ava_title_parts' );
/**
 * Front page: "Site Name | Tagline" (tagline from Settings > General).
 *
 * @param array $parts Title parts.
 * @return array
 */
function ava_title_parts( $parts ) {
	if ( is_front_page() && ! ava_seo_plugin_active() ) {
		$parts = array( 'title' => get_bloginfo( 'name' ) );
		if ( get_bloginfo( 'description' ) ) {
			$parts['tagline'] = get_bloginfo( 'description' );
		}
	}
	return $parts;
}

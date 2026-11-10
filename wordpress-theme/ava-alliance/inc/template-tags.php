<?php
/**
 * Small output helpers used by the templates.
 *
 * @package AVA_Alliance
 */

defined( 'ABSPATH' ) || exit;

/**
 * Make an in-page "#anchor" work from any page: on inner pages it points to the
 * homepage section. "#pledge" always stays local (the pop-up exists everywhere).
 *
 * @param string $url URL or #anchor.
 * @return string
 */
function ava_link( $url ) {
	$url = (string) $url;
	if ( '' !== $url && '#' === $url[0] && '#pledge' !== $url && ! is_front_page() ) {
		return home_url( '/' ) . $url;
	}
	return $url;
}

/**
 * Print a button if it has a label and link.
 *
 * @param string $label   Label.
 * @param string $url     URL.
 * @param bool   $primary Orange style.
 */
function ava_button( $label, $url, $primary = false ) {
	if ( '' === trim( (string) $label ) || '' === trim( (string) $url ) ) {
		return;
	}
	printf(
		'<a class="btn%s" href="%s">%s</a>',
		$primary ? ' primary' : '',
		esc_url( ava_link( $url ) ),
		esc_html( $label )
	);
}

/**
 * Print numbered Customizer buttons: {$prefix}_btn1_label/_url … btn3.
 *
 * @param string $prefix Field prefix.
 */
function ava_buttons( $prefix ) {
	ob_start();
	for ( $i = 1; $i <= 3; $i++ ) {
		ava_button( ava_mod( "{$prefix}_btn{$i}_label" ), ava_mod( "{$prefix}_btn{$i}_url" ), 1 === $i );
	}
	$html = trim( ob_get_clean() );
	if ( $html ) {
		echo '<div class="buttons">' . $html . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in ava_button().
	}
}

/**
 * Eyebrow + heading + intro for a section.
 *
 * @param string $prefix  Field prefix.
 * @param string $heading_id Optional id for the heading (aria-labelledby).
 */
function ava_section_head( $prefix, $heading_id = '' ) {
	$eyebrow = ava_mod( "{$prefix}_eyebrow" );
	$title   = ava_mod( "{$prefix}_title" );
	$intro   = ava_mod( "{$prefix}_intro" );
	if ( $eyebrow ) {
		echo '<div class="eyebrow">' . esc_html( $eyebrow ) . '</div>';
	}
	if ( $title ) {
		printf( '<h2%s>%s</h2>', $heading_id ? ' id="' . esc_attr( $heading_id ) . '"' : '', esc_html( $title ) );
	}
	if ( $intro ) {
		$index = ava_field_index();
		if ( isset( $index[ "{$prefix}_intro" ] ) && 'html' === $index[ "{$prefix}_intro" ]['type'] ) {
			echo '<div class="sub">' . wp_kses_post( wpautop( $intro ) ) . '</div>';
		} else {
			echo '<p class="sub">' . esc_html( $intro ) . '</p>';
		}
	}
}

/**
 * Header/footer logo: Site Identity logo, else the bundled AVA mark.
 *
 * @param string $class Extra image class.
 * @param bool   $decorative Empty alt when next to the site name.
 */
function ava_logo( $class = '', $decorative = true ) {
	$logo_id = (int) get_theme_mod( 'custom_logo' );
	$alt     = $decorative ? '' : get_bloginfo( 'name' );
	if ( $logo_id ) {
		echo wp_get_attachment_image( $logo_id, 'full', false, array( 'class' => $class, 'alt' => $alt, 'loading' => 'eager', 'decoding' => 'async' ) );
		return;
	}
	printf(
		'<img class="%s" src="%s" width="266" height="123" alt="%s" decoding="async">',
		esc_attr( $class ),
		esc_url( AVA_URI . '/assets/images/ava-mark.webp' ),
		esc_attr( $alt )
	);
}

/**
 * Site name with the first word(s) highlighted, e.g. "<b>Anti-Vaping</b> Alliance".
 *
 * @return string Safe HTML.
 */
function ava_site_name_html() {
	$name = get_bloginfo( 'name' );
	$pos  = strrpos( $name, ' ' );
	if ( false === $pos ) {
		return esc_html( $name );
	}
	return '<b>' . esc_html( substr( $name, 0, $pos ) ) . '</b> ' . esc_html( substr( $name, $pos + 1 ) );
}

/**
 * Menu fallback (before a menu is assigned): homepage section links.
 */
function ava_menu_fallback() {
	$links = array(
		'#mission'    => __( 'Mission', 'ava-alliance' ),
		'#programs'   => __( 'Programs', 'ava-alliance' ),
		'#curriculum' => __( 'Curriculum', 'ava-alliance' ),
		'#resources'  => __( 'Resources', 'ava-alliance' ),
	);
	echo '<ul id="primary-menu" class="menu">';
	foreach ( $links as $href => $label ) {
		printf( '<li class="menu-item"><a href="%s">%s</a></li>', esc_url( ava_link( $href ) ), esc_html( $label ) );
	}
	echo '</ul>';
}

/**
 * Editor-only hint shown on the front end (never to visitors).
 *
 * @param string $message Message.
 * @param string $link    Admin link.
 */
function ava_editor_note( $message, $link = '' ) {
	if ( ! current_user_can( 'edit_posts' ) ) {
		return;
	}
	echo '<p class="ava-editor-note">' . esc_html( $message );
	if ( $link ) {
		echo ' <a href="' . esc_url( $link ) . '">' . esc_html__( 'Edit', 'ava-alliance' ) . '</a>';
	}
	echo '</p>';
}

/**
 * Text with paragraphs, for plain textarea fields.
 *
 * @param string $text Text.
 * @return string Safe HTML.
 */
function ava_paragraphs( $text ) {
	return wpautop( esc_html( $text ) );
}

/**
 * Header call-to-action button (Customizer: Top Bar & Header).
 */
function ava_header_cta() {
	$label = ava_mod( 'header_cta_label' );
	$url   = ava_mod( 'header_cta_url' );
	if ( $label && $url ) {
		printf( '<a class="ava-cta" href="%s">%s</a>', esc_url( ava_link( $url ) ), esc_html( $label ) );
	}
}

/**
 * Hero / brand image: Customizer image from the Media Library, else the bundled logo.
 *
 * @param string $context hero|modal.
 */
function ava_brand_image( $context = 'hero' ) {
	$id    = (int) ava_mod( 'hero_image' );
	$attrs = 'hero' === $context
		? array( 'loading' => 'eager', 'fetchpriority' => 'high', 'decoding' => 'async' )
		: array( 'loading' => 'lazy', 'decoding' => 'async', 'class' => 'mlogo', 'alt' => '' );
	if ( $id && wp_attachment_is_image( $id ) ) {
		echo wp_get_attachment_image( $id, 'large', false, $attrs );
		return;
	}
	$alt = 'hero' === $context ? __( 'Anti-Vaping Alliance logo: the letters AVA with a vape pen inside a prohibition sign', 'ava-alliance' ) : '';
	printf(
		'<img src="%s" width="693" height="426" alt="%s"%s>',
		esc_url( AVA_URI . '/assets/images/ava-logo.webp' ),
		esc_attr( $alt ),
		'hero' === $context ? ' fetchpriority="high" decoding="async"' : ' class="mlogo" loading="lazy" decoding="async"'
	);
}

/**
 * Hero background photo: Customizer photo from the Media Library, else the
 * bundled students photo. Responsive (srcset) and loaded first.
 */
function ava_hero_background() {
	$id    = (int) ava_mod( 'hero_background' );
	$attrs = array(
		'class'         => 'hero-bg',
		'alt'           => '',
		'sizes'         => '100vw',
		'loading'       => 'eager',
		'fetchpriority' => 'high',
		'decoding'      => 'async',
	);
	if ( $id && wp_attachment_is_image( $id ) ) {
		echo wp_get_attachment_image( $id, 'full', false, $attrs );
		return;
	}
	$base = AVA_URI . '/assets/images/hero-students-';
	printf(
		'<img class="hero-bg" src="%1$s" srcset="%2$s 800w, %1$s 1400w, %3$s 2000w" sizes="100vw" width="1400" height="933" alt="" fetchpriority="high" decoding="async">',
		esc_url( $base . '1400.webp' ),
		esc_url( $base . '800.webp' ),
		esc_url( $base . '2000.webp' )
	);
}


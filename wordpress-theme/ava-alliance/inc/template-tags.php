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
	$href = ava_link( $url );
	// Links to other websites (e.g. the Zelle payment page) open in a new tab.
	$host     = wp_parse_url( $href, PHP_URL_HOST );
	$external = $host && strtolower( $host ) !== strtolower( (string) wp_parse_url( home_url(), PHP_URL_HOST ) );
	printf(
		'<a class="btn%s" href="%s"%s>%s%s</a>',
		$primary ? ' primary' : '',
		esc_url( $href ),
		$external ? ' target="_blank" rel="noopener"' : '',
		esc_html( $label ),
		$external ? '<span class="screen-reader-text"> ' . esc_html__( '(opens in a new tab)', 'ava-alliance' ) . '</span>' : ''
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
	echo '<ul id="primary-menu" class="menu">';
	foreach ( ava_default_menu() as $item ) {
		$kids = ! empty( $item[2] );
		printf( '<li class="menu-item%s"><a href="%s">%s</a>', $kids ? ' menu-item-has-children' : '', esc_url( ava_link( $item[1] ) ), esc_html( $item[0] ) );
		if ( $kids ) {
			echo ava_submenu_toggle( $item[0] ); // phpcs:ignore WordPress.Security.EscapeOutput
			echo '<ul class="sub-menu">';
			foreach ( $item[2] as $child ) {
				printf( '<li class="menu-item"><a href="%s">%s</a></li>', esc_url( ava_link( $child[1] ) ), esc_html( $child[0] ) );
			}
			echo '</ul>';
		}
		echo '</li>';
	}
	echo '</ul>';
}

/**
 * Button that opens a drop-down in the main menu (for keyboard and touch).
 *
 * @param string $label Parent item label.
 * @return string HTML.
 */
function ava_submenu_toggle( $label ) {
	return sprintf(
		'<button type="button" class="sub-toggle" aria-expanded="false" aria-label="%s"><svg viewBox="0 0 12 8" width="12" height="8" aria-hidden="true" focusable="false"><path d="M1 1.5l5 5 5-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></button>',
		/* translators: %s: menu item, e.g. Resources */
		esc_attr( sprintf( __( 'Show the %s menu', 'ava-alliance' ), wp_strip_all_tags( $label ) ) )
	);
}

add_filter( 'walker_nav_menu_start_el', 'ava_menu_add_toggle', 10, 4 );
/**
 * Add the drop-down button after main-menu items that have sub-items.
 *
 * @param string   $output Item HTML.
 * @param WP_Post  $item   Menu item.
 * @param int      $depth  Depth.
 * @param stdClass $args   Menu args.
 * @return string
 */
function ava_menu_add_toggle( $output, $item, $depth, $args ) {
	if ( 0 === $depth && isset( $args->theme_location ) && 'primary' === $args->theme_location && in_array( 'menu-item-has-children', (array) $item->classes, true ) ) {
		$output .= ava_submenu_toggle( $item->title );
	}
	return $output;
}

/**
 * Download box for a post's handout (set in the post's "Handout download" box).
 */
function ava_post_handout() {
	$file_id = (int) get_post_meta( get_the_ID(), '_ava_handout_id', true );
	$url     = $file_id ? wp_get_attachment_url( $file_id ) : '';
	if ( ! $url ) {
		return;
	}
	$title = get_post_meta( get_the_ID(), '_ava_handout_title', true );
	$text  = get_post_meta( get_the_ID(), '_ava_handout_text', true );
	$label = get_post_meta( get_the_ID(), '_ava_handout_label', true );
	?>
	<aside class="post-handout" aria-label="<?php esc_attr_e( 'Printable handout', 'ava-alliance' ); ?>">
		<span class="ph-icon" aria-hidden="true"><svg viewBox="0 0 24 24" width="26" height="26" focusable="false"><path d="M6 2h8l5 5v13a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2zm7 1.5V8h4.5M8 13h8M8 17h5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
		<div class="ph-txt">
			<?php if ( $title ) : ?><strong><?php echo esc_html( $title ); ?></strong><?php endif; ?>
			<?php if ( $text ) : ?><span><?php echo esc_html( $text ); ?></span><?php endif; ?>
		</div>
		<a class="btn primary" href="<?php echo esc_url( $url ); ?>" download><?php echo esc_html( $label ? $label : __( 'Download PDF', 'ava-alliance' ) ); ?></a>
	</aside>
	<?php
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


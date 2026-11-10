<?php
/**
 * Homepage. Section text lives in Appearance > Customize > AVA Site Content;
 * cards, downloads and resources in the AVA Content admin menu.
 *
 * @package AVA_Alliance
 */

defined( 'ABSPATH' ) || exit;

get_header();

get_template_part( 'template-parts/section', 'hero' );

$ava_sections = apply_filters( 'ava_front_sections', array( 'mission', 'difference', 'programs', 'curriculum', 'resources', 'involved', 'contact' ) );
foreach ( $ava_sections as $ava_section ) {
	if ( ava_mod( $ava_section . '_show' ) ) {
		get_template_part( 'template-parts/section', $ava_section );
	}
}

// If a static homepage is chosen and it has content, show it after the sections.
if ( 'page' === get_option( 'show_on_front' ) && have_posts() ) {
	the_post();
	if ( '' !== trim( get_the_content() ) ) {
		echo '<section class="ava-section entry"><div class="entry-content">';
		the_content();
		echo '</div></section>';
	}
}

get_footer();

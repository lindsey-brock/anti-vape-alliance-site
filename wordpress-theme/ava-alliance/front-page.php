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
// Sections listed together here share one stacking card (e.g. Mission + its quote).
$ava_groups = apply_filters( 'ava_front_groups', array( array( 'mission', 'difference' ) ) );
$ava_group_of = array();
foreach ( $ava_groups as $ava_g => $ava_members ) {
	foreach ( $ava_members as $ava_m ) {
		$ava_group_of[ $ava_m ] = $ava_g;
	}
}
$ava_open = null;
foreach ( $ava_sections as $ava_section ) {
	if ( ! ava_mod( $ava_section . '_show' ) ) {
		continue;
	}
	$ava_g = isset( $ava_group_of[ $ava_section ] ) ? $ava_group_of[ $ava_section ] : null;
	if ( $ava_open !== $ava_g && null !== $ava_open ) {
		echo '</div>';
		$ava_open = null;
	}
	if ( null !== $ava_g && $ava_open !== $ava_g ) {
		echo '<div class="ava-panel ava-group">';
		$ava_open = $ava_g;
	}
	set_query_var( 'ava_in_group', null !== $ava_g );
	get_template_part( 'template-parts/section', $ava_section );
}
if ( null !== $ava_open ) {
	echo '</div>';
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

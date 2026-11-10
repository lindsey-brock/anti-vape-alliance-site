<?php
/**
 * On first activation, create the default cards, curriculum, resources and
 * menu so the site matches the approved design right away. Runs once and
 * never overwrites anything the team has created.
 *
 * @package AVA_Alliance
 */

defined( 'ABSPATH' ) || exit;

add_action( 'after_switch_theme', 'ava_seed_content' );
/**
 * Seed default content.
 */
function ava_seed_content() {
	if ( get_option( 'ava_seeded' ) ) {
		return;
	}
	ava_register_content_types();

	$items = array(
		'ava_point'      => array(
			array( 'Student Founded', 'AVA was created by students who wanted to address vaping education in a way that feels authentic and relevant to younger students.' ),
			array( 'Student Led', 'Students are at the center of our outreach, presentations, chapter development, advocacy and ideas for reaching the next generation.' ),
			array( 'Nonprofit Mission', 'Our focus is education and prevention — making useful resources available to students, teachers, parents and communities.' ),
		),
		'ava_program'    => array(
			array( 'Peer-Led Classroom Talks', 'Age-appropriate presentations for elementary and middle-school students covering vaping, nicotine, peer pressure, decision-making and healthy alternatives.', 'Bring AVA to Your School', '#contact' ),
			array( 'School Chapters', 'Students can build AVA clubs that connect high-school mentors with younger students and create ongoing prevention activities.', 'Start a Chapter', '#contact' ),
			array( 'Community Education', 'AVA works with PTAs, educators, families and community organizations to expand youth-centered vaping education.', 'Support AVA', '#contact' ),
		),
		'ava_curriculum' => array(
			array( 'Lesson Pack', 'Myth vs. fact, nicotine and addiction, peer-pressure scenarios, healthy choices and a student pledge activity.', 'Download PDF', 1, 'AVA-Be-the-Boss-of-Your-Choices.pdf', 'Be the Boss of Your Choices! – Student Worksheet 1 (Grades 4–6)' ),
			array( 'Teacher Notes', 'Facilitation guidance, discussion prompts and suggestions for presenting the material in a supportive classroom environment.', 'Download Notes', 0 ),
			array( 'Parent & Community', 'Conversation resources to help adults support young people with prevention, awareness and healthy decision-making.', 'Parent Handout', 0 ),
		),
		'ava_resource'   => array(
			array( 'For Students', '<p>Youth-focused nicotine education and quitting resources.</p><p><a href="https://teen.smokefree.gov/" target="_blank" rel="noopener">Smokefree Teen</a> · <a href="https://truthinitiative.org/thisisquitting" target="_blank" rel="noopener">This is Quitting</a></p>' ),
			array( 'For Parents & Schools', '<p>Evidence-based information and support from established public-health organizations.</p><p><a href="https://www.cdc.gov/tobacco/youth/index.htm" target="_blank" rel="noopener">CDC Youth &amp; Tobacco</a></p>' ),
		),
	);

	foreach ( $items as $type => $list ) {
		$existing = get_posts( array( 'post_type' => $type, 'post_status' => 'any', 'numberposts' => 1, 'fields' => 'ids' ) );
		if ( $existing ) {
			continue;
		}
		foreach ( $list as $order => $item ) {
			$content = 0 === strpos( $item[1], '<' ) ? $item[1] : '<!-- wp:paragraph --><p>' . esc_html( $item[1] ) . '</p><!-- /wp:paragraph -->';
			if ( 'ava_resource' === $type ) {
				$content = str_replace( array( '<p>', '</p>' ), array( '<!-- wp:paragraph --><p>', '</p><!-- /wp:paragraph -->' ), $item[1] );
			}
			$id = wp_insert_post(
				array(
					'post_type'    => $type,
					'post_status'  => 'publish',
					'post_title'   => $item[0],
					'post_content' => $content,
					'menu_order'   => $order + 1,
				)
			);
			if ( $id && ! is_wp_error( $id ) && 'ava_program' === $type ) {
				update_post_meta( $id, '_ava_btn_label', $item[2] );
				update_post_meta( $id, '_ava_btn_url', $item[3] );
			}
			if ( $id && ! is_wp_error( $id ) && 'ava_curriculum' === $type ) {
				update_post_meta( $id, '_ava_button_label', $item[2] );
				update_post_meta( $id, '_ava_primary', $item[3] );
				if ( ! empty( $item[4] ) ) {
					$file_id = ava_import_bundled_file( $item[4], $item[5] );
					if ( $file_id ) {
						update_post_meta( $id, '_ava_file_id', $file_id );
					}
				}
			}
		}
	}

	// Main menu with the homepage section links.
	$locations = get_theme_mod( 'nav_menu_locations', array() );
	if ( empty( $locations['primary'] ) && ! wp_get_nav_menu_object( 'AVA Main Menu' ) ) {
		$menu_id = wp_create_nav_menu( 'AVA Main Menu' );
		if ( ! is_wp_error( $menu_id ) ) {
			$links = array(
				'#mission'    => 'Mission',
				'#programs'   => 'Programs',
				'#curriculum' => 'Curriculum',
				'#resources'  => 'Resources',
				'#contact'    => 'Get Involved',
			);
			foreach ( $links as $href => $label ) {
				wp_update_nav_menu_item(
					$menu_id,
					0,
					array(
						'menu-item-title'  => $label,
						'menu-item-url'    => $href,
						'menu-item-type'   => 'custom',
						'menu-item-status' => 'publish',
					)
				);
			}
			$locations['primary'] = $menu_id;
			set_theme_mod( 'nav_menu_locations', $locations );
		}
	}

	// A friendlier tagline than WordPress' default (used in the homepage title).
	$tagline = get_option( 'blogdescription' );
	if ( '' === $tagline || 'Just another WordPress site' === $tagline ) {
		update_option( 'blogdescription', 'Student Founded & Led' );
	}

	update_option( 'ava_seeded', 1, false );
}

/**
 * Copy a PDF bundled with the theme (assets/files) into the Media Library, so
 * the team can later replace it there like any other upload.
 *
 * @param string $filename File in assets/{$dir}.
 * @param string $title    Media Library title.
 * @param string $dir      Folder under assets/ (files or images/posts).
 * @param string $alt      Alt text for images.
 * @return int Attachment ID, or 0.
 */
function ava_import_bundled_file( $filename, $title, $dir = 'files', $alt = '' ) {
	$src = AVA_DIR . '/assets/' . trim( $dir, '/' ) . '/' . basename( $filename );
	if ( ! is_readable( $src ) ) {
		return 0;
	}
	$upload = wp_upload_bits( basename( $filename ), null, file_get_contents( $src ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions
	if ( ! empty( $upload['error'] ) ) {
		return 0;
	}
	$type = wp_check_filetype( $upload['file'] );
	$id   = wp_insert_attachment(
		array(
			'post_mime_type' => $type['type'] ? $type['type'] : 'application/pdf',
			'post_title'     => $title,
			'post_status'    => 'inherit',
		),
		$upload['file']
	);
	if ( ! $id || is_wp_error( $id ) ) {
		return 0;
	}
	require_once ABSPATH . 'wp-admin/includes/image.php';
	wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $upload['file'] ) );
	if ( $alt ) {
		update_post_meta( $id, '_wp_attachment_image_alt', $alt );
	}
	return (int) $id;
}

add_filter( 'nav_menu_link_attributes', 'ava_menu_anchor_links' );
/**
 * Menu links like "#mission" jump to the homepage section from any page.
 *
 * @param array $atts Link attributes.
 * @return array
 */
function ava_menu_anchor_links( $atts ) {
	if ( ! empty( $atts['href'] ) ) {
		$atts['href'] = ava_link( $atts['href'] );
	}
	return $atts;
}

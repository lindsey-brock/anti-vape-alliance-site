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
			array( 'Education and Advocacy', 'Our focus is education and prevention — making useful resources available to students, teachers, parents and communities.' ),
			// Photos for these cards are added by ava_seed_mission_images().
		),
		'ava_program'    => array(
			array( 'Peer-Led Classroom Talks', 'Age-appropriate presentations for elementary and middle-school students covering vaping, nicotine, peer pressure, decision-making and healthy alternatives.', 'Bring AVA to Your School', '#school' ),
			array( 'School Chapters', 'Students can build AVA clubs that connect high-school mentors with younger students and create ongoing prevention activities.', 'Start a Chapter', '#chapter' ),
			array( 'Community Education', 'AVA works with PTAs, educators, families and community organizations to expand youth-centered vaping education.', 'Support AVA', ava_zelle_url() ),
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
			ava_fill_main_menu( $menu_id );
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
	update_option( 'ava_menu_v2', 1, false );
	update_option( 'ava_programs_v2', 1, false );
	ava_seed_mission_images();
}

/**
 * The default main menu. "Resources" opens a drop-down.
 *
 * @return array Items: array( label, link, children ).
 */
function ava_default_menu() {
	return array(
		array( 'Mission', '#mission' ),
		array( 'Programs', '#programs' ),
		array(
			'Resources',
			'#curriculum',
			array(
				array( 'Curriculum', '#curriculum' ),
				array( 'Get Support', '#support' ),
				array( 'Blog', '#blog' ),
			),
		),
		array( 'Get Involved', '#contact' ),
	);
}

/**
 * Add the default items to a menu.
 *
 * @param int $menu_id Menu ID.
 */
function ava_fill_main_menu( $menu_id ) {
	foreach ( ava_default_menu() as $item ) {
		$parent = wp_update_nav_menu_item(
			$menu_id,
			0,
			array(
				'menu-item-title'  => $item[0],
				'menu-item-url'    => $item[1],
				'menu-item-type'   => 'custom',
				'menu-item-status' => 'publish',
			)
		);
		if ( empty( $item[2] ) || is_wp_error( $parent ) ) {
			continue;
		}
		foreach ( $item[2] as $child ) {
			wp_update_nav_menu_item(
				$menu_id,
				0,
				array(
					'menu-item-title'     => $child[0],
					'menu-item-url'       => $child[1],
					'menu-item-type'      => 'custom',
					'menu-item-status'    => 'publish',
					'menu-item-parent-id' => $parent,
				)
			);
		}
	}
}

/**
 * AVA's Zelle payment link (from the QR code on AVA's Zelle sign).
 *
 * @return string
 */
function ava_zelle_url() {
	return 'https://enroll.zellepay.com/qr-codes?data=ewogICAgInRva2VuIjogImFudGktdmFwZS1hbGxpYW5jZSIsCiAgICAibmFtZSI6ICJBbnRpIFZhcGluZyBBbGxpYW5jZSBJbmMiLAogICAgImFjdGlvbiI6ICJwYXltZW50Igp9';
}

add_action( 'admin_init', 'ava_upgrade_program_buttons' );
/**
 * Point the starter Program buttons at their pop-ups / payment link, once,
 * on sites that installed an earlier version (when they still say #contact).
 */
function ava_upgrade_program_buttons() {
	if ( get_option( 'ava_programs_v2' ) || ! current_user_can( 'edit_posts' ) ) {
		return;
	}
	update_option( 'ava_programs_v2', 1, false );
	$targets = array(
		'Bring AVA to Your School' => '#school',
		'Start a Chapter'          => '#chapter',
		'Support AVA'              => ava_zelle_url(),
	);
	foreach ( get_posts( array( 'post_type' => 'ava_program', 'post_status' => 'any', 'numberposts' => 20 ) ) as $program ) {
		$label = get_post_meta( $program->ID, '_ava_btn_label', true );
		if ( isset( $targets[ $label ] ) && '#contact' === get_post_meta( $program->ID, '_ava_btn_url', true ) ) {
			update_post_meta( $program->ID, '_ava_btn_url', $targets[ $label ] );
		}
	}
}

add_action( 'admin_init', 'ava_upgrade_main_menu' );
/**
 * Sites that installed an earlier version got a flat menu (Mission, Programs,
 * Curriculum, Resources, Get Involved). If that menu is still exactly as the
 * theme made it, switch it to the Resources drop-down once. Menus the team
 * has changed are left alone.
 */
function ava_upgrade_main_menu() {
	if ( get_option( 'ava_menu_v2' ) || ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}
	update_option( 'ava_menu_v2', 1, false );
	$menu = wp_get_nav_menu_object( 'AVA Main Menu' );
	if ( ! $menu ) {
		return;
	}
	$items = wp_get_nav_menu_items( $menu->term_id );
	$found = array();
	foreach ( (array) $items as $item ) {
		$found[] = $item->title . '|' . $item->url . '|' . (int) $item->menu_item_parent;
	}
	$old = array( 'Mission|#mission|0', 'Programs|#programs|0', 'Curriculum|#curriculum|0', 'Resources|#resources|0', 'Get Involved|#contact|0' );
	if ( $found !== $old ) {
		return;
	}
	foreach ( $items as $item ) {
		wp_delete_post( $item->ID, true );
	}
	ava_fill_main_menu( $menu->term_id );
}

/**
 * Photos for the three Mission cards (the tabs in the Mission carousel).
 *
 * @return array Card title => array( file in assets/images/mission, alt text ).
 */
function ava_mission_images() {
	return array(
		'Student Founded'   => array( 'student-founded.jpg', 'A student talks with classmates around a table covered in books' ),
		'Student Led'       => array( 'student-led.jpg', 'A student points to a whiteboard while a group of classmates listens' ),
		'Education and Advocacy' => array( 'nonprofit-mission.jpg', 'A teacher high-fives a student in a classroom' ),
		'Nonprofit Mission'      => array( 'nonprofit-mission.jpg', 'A teacher high-fives a student in a classroom' ), // Earlier name of the third card.
	);
}

add_action( 'admin_init', 'ava_seed_mission_images' );
/**
 * Give each starter Mission card its photo, once. Also runs on sites that
 * installed an earlier version of the theme. Cards that already have a
 * featured image, or were renamed, are left alone.
 */
function ava_seed_mission_images() {
	if ( get_option( 'ava_seeded_mission_img' ) || ( ! current_user_can( 'upload_files' ) && ! doing_action( 'after_switch_theme' ) ) ) {
		return;
	}
	update_option( 'ava_seeded_mission_img', 1, false );
	require_once ABSPATH . 'wp-admin/includes/image.php';
	foreach ( ava_mission_images() as $title => $image ) {
		$cards = get_posts( array( 'post_type' => 'ava_point', 'post_status' => 'any', 'title' => $title, 'numberposts' => 1 ) );
		if ( ! $cards || has_post_thumbnail( $cards[0] ) ) {
			continue;
		}
		$img_id = ava_import_bundled_file( $image[0], $title, 'images/mission', $image[1] );
		if ( $img_id ) {
			set_post_thumbnail( $cards[0], $img_id );
		}
	}
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

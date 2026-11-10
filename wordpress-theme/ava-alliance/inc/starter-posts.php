<?php
/**
 * Starter blog posts + Blog page, created once on first activation.
 * Plain, fact-based starter articles (no statistics) for the AVA team to edit,
 * replace or delete like any WordPress post.
 *
 * @package AVA_Alliance
 */

defined( 'ABSPATH' ) || exit;

/**
 * Block markup helpers.
 */
function ava_b_p( $html ) {
	return "<!-- wp:paragraph -->\n<p>" . $html . "</p>\n<!-- /wp:paragraph -->\n\n";
}
function ava_b_h( $text ) {
	return "<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">" . $text . "</h2>\n<!-- /wp:heading -->\n\n";
}
function ava_b_list( $items ) {
	$out = "<!-- wp:list -->\n<ul class=\"wp-block-list\">";
	foreach ( $items as $item ) {
		$out .= "<!-- wp:list-item -->\n<li>" . $item . "</li>\n<!-- /wp:list-item -->";
	}
	return $out . "</ul>\n<!-- /wp:list -->\n\n";
}

/**
 * The starter posts.
 *
 * @return array
 */
function ava_starter_posts() {
	$cdc   = '<a href="https://www.cdc.gov/tobacco/youth/index.htm" target="_blank" rel="noopener">CDC Youth &amp; Tobacco</a>';
	$teen  = '<a href="https://teen.smokefree.gov/" target="_blank" rel="noopener">Smokefree Teen</a>';
	$truth = '<a href="https://truthinitiative.org/thisisquitting" target="_blank" rel="noopener">This is Quitting</a>';

	return array(
		array(
			'title'    => 'Myth vs. Fact: What’s Really in a Vape?',
			'category' => 'Facts',
			'image'    => array( 'myth-vs-fact.jpg', 'Hand-drawn diagram of vaping health risks' ),
			'handout'  => ava_starter_handout( 'truth' ),
			'excerpt'  => 'Vapes are often called “just water vapor.” Here’s what’s actually in that cloud, and why fruity flavors don’t make it safe.',
			'content'  =>
				ava_b_p( 'If you’ve heard someone say vaping is “just water vapor,” you’re not alone. It’s one of the most common myths we hear from students. Here are a few facts worth knowing.' ) .
				ava_b_h( 'Myth: It’s just water vapor.' ) .
				ava_b_p( '<strong>Fact:</strong> Vapes heat a liquid into an <em>aerosol</em>: tiny particles that are breathed deep into the lungs. That aerosol can contain nicotine, flavoring chemicals and other substances that can harm the lungs.' ) .
				ava_b_h( 'Myth: If it tastes like candy or fruit, it must be safe.' ) .
				ava_b_p( '<strong>Fact:</strong> Flavors make vapes taste good, but they don’t make the aerosol safe to breathe. Sweet flavors are one of the reasons young people try vaping in the first place.' ) .
				ava_b_h( 'Myth: “Nicotine-free” means no risk.' ) .
				ava_b_p( '<strong>Fact:</strong> Labels aren’t always accurate. Some products sold as nicotine-free have been found to contain nicotine, and the aerosol can still contain other harmful substances.' ) .
				ava_b_h( 'Myth: Trying it once won’t matter.' ) .
				ava_b_p( '<strong>Fact:</strong> Nicotine is addictive, and young people’s brains are still developing, which can make it easier to get hooked.' ) .
				ava_b_p( 'Want to learn more? Check out ' . $cdc . ' and ' . $teen . '.' ),
		),
		array(
			'title'    => '5 Ways to Say No to Vaping (Without Making It Awkward)',
			'category' => 'Tips',
			'image'    => array( 'say-no-to-vaping.jpg', 'A young person crossing their arms to refuse a vape' ),
			'handout'  => ava_starter_handout( 'friendly-no' ),
			'excerpt'  => 'Saying no doesn’t have to be a big speech. Try one of these five strategies from our student lessons.',
			'content'  =>
				ava_b_p( 'There’s more than one way to say no. Pick the one that feels most like you, and practice it so it’s ready when you need it.' ) .
				ava_b_list(
					array(
						'<strong>Say it simply.</strong> “No thanks. I’m not interested.” You don’t owe anyone a long explanation.',
						'<strong>Give a reason.</strong> “No thanks. I want to keep my lungs healthy for soccer.”',
						'<strong>Change the subject.</strong> “Nah. Let’s go shoot some hoops instead.”',
						'<strong>Walk away.</strong> “I’m going to go find my other friends.” Leaving is a smart move, not a weak one.',
						'<strong>Get help.</strong> “I’m uncomfortable. I’m going to tell an adult.” Asking a trusted adult for help is a sign of strength.',
					)
				) .
				ava_b_h( 'Remember: Stop. Think. Choose.' ) .
				ava_b_p( 'Take a moment, ask yourself whether it’s safe and matches your values, then make the decision that’s right for you. Real friends will respect it.' ) .
				ava_b_p( '<strong>You never have to vape to fit in.</strong> If you or a friend want help quitting, ' . $truth . ' and ' . $teen . ' have free, confidential support.' ),
		),
		array(
			'title'    => 'Talking With Your Child About Vaping: A Starter Guide for Families',
			'category' => 'For Families',
			'image'    => array( 'talking-with-your-child.jpg', 'A parent and teen talking together on a couch' ),
			'handout'  => ava_starter_handout( 'families' ),
			'excerpt'  => 'You don’t need all the answers to start the conversation. A few simple tips for parents and caregivers.',
			'content'  =>
				ava_b_p( 'Conversations about vaping go better when they’re a series of small talks rather than one big lecture. Here are a few ways to get started.' ) .
				ava_b_list(
					array(
						'<strong>Start early and keep it casual.</strong> Car rides, walks or a TV ad are natural openings.',
						'<strong>Ask before you tell.</strong> “What have you heard about vaping?” or “Do kids at school talk about it?”',
						'<strong>Share the facts calmly.</strong> Vapes can contain nicotine, which is addictive, and the aerosol isn’t harmless water vapor.',
						'<strong>Listen more than you talk.</strong> Kids open up when they don’t feel judged.',
						'<strong>Practice saying no together.</strong> Role-play a situation and try out a few responses.',
						'<strong>Keep the door open.</strong> Let them know they can always come to you, even if they’ve already tried it.',
					)
				) .
				ava_b_p( 'For more conversation ideas and facts, visit ' . $cdc . ' and ' . $teen . '.' ),
		),
	);
}

/**
 * Create the starter posts, their categories and a Blog page (once).
 */
function ava_seed_blog() {
	if ( get_option( 'ava_seeded_blog' ) ) {
		return;
	}

	// Remove WordPress' untouched "Hello world!" sample so it doesn't show on the homepage.
	$hello = get_page_by_path( 'hello-world', OBJECT, 'post' );
	if ( $hello && false !== strpos( $hello->post_content, 'Welcome to WordPress' ) ) {
		wp_trash_post( $hello->ID );
	}

	$existing = get_posts( array( 'post_type' => 'post', 'post_status' => 'publish', 'numberposts' => 1, 'fields' => 'ids' ) );
	if ( ! $existing ) {
		$time = time();
		foreach ( array_reverse( ava_starter_posts() ) as $i => $post ) {
			$term = term_exists( $post['category'], 'category' );
			if ( ! $term ) {
				$term = wp_insert_term( $post['category'], 'category' );
			}
			$cat_id = is_array( $term ) ? (int) $term['term_id'] : 0;
			$post_id = wp_insert_post(
				array(
					'post_type'     => 'post',
					'post_status'   => 'publish',
					'post_title'    => $post['title'],
					'post_excerpt'  => $post['excerpt'],
					'post_content'  => $post['content'],
					'post_category' => $cat_id ? array( $cat_id ) : array(),
					'post_date'     => gmdate( 'Y-m-d H:i:s', $time - ( 3 - $i ) * DAY_IN_SECONDS + (int) ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ) ),
				)
			);
			if ( $post_id && ! is_wp_error( $post_id ) && ! empty( $post['image'] ) ) {
				$img = ava_import_bundled_file( $post['image'][0], $post['title'], 'images/posts', $post['image'][1] );
				if ( $img ) {
					set_post_thumbnail( $post_id, $img );
				}
			}
			if ( $post_id && ! is_wp_error( $post_id ) && ! empty( $post['handout'] ) ) {
				ava_attach_handout( $post_id, $post['handout'] );
			}
		}
	}

	// A Blog page for the full list, with a static homepage (theme sections + optional page content).
	if ( 'posts' === get_option( 'show_on_front' ) && ! get_option( 'page_for_posts' ) ) {
		$home = wp_insert_post( array( 'post_type' => 'page', 'post_status' => 'publish', 'post_title' => 'Home', 'post_content' => '' ) );
		$blog = wp_insert_post( array( 'post_type' => 'page', 'post_status' => 'publish', 'post_title' => 'Blog', 'post_name' => 'blog', 'post_content' => '' ) );
		if ( $home && $blog && ! is_wp_error( $home ) && ! is_wp_error( $blog ) ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $home );
			update_option( 'page_for_posts', $blog );
		}
	}

	update_option( 'ava_seeded_blog', 1, false );
	update_option( 'ava_handouts_v3', 1, false );
}

/**
 * Worksheets that go with the starter posts.
 *
 * @param string $key friendly-no|truth, or empty for all (keyed by post slug).
 * @return array File, Media Library title, heading, text, button label.
 */
function ava_starter_handout( $key = '' ) {
	$all = array(
		'friendly-no' => array(
			'post'  => '5-ways-to-say-no-to-vaping-without-making-it-awkward',
			'file'  => 'AVA-The-Friendly-No-Worksheet.pdf',
			'name'  => 'The Friendly No – Worksheet',
			'title' => 'Practice it: “The Friendly No” worksheet',
			'text'  => 'Six ways to turn down a vape and keep your friends, plus a page to practice. Print it or try it with a friend.',
			'label' => 'Practice Saying No (PDF)',
		),
		'truth'       => array(
			'post'  => 'myth-vs-fact-whats-really-in-a-vape',
			'file'  => 'AVA-The-Truth-About-Vaping-Worksheet.pdf',
			'name'  => 'The Truth About Vaping – Student Worksheet (Grades 6–8)',
			'title' => 'Test yourself: “The Truth About Vaping” worksheet',
			'text'  => 'Match what’s really inside a vape, decode a vape ad and design your own vape-free poster. For grades 6–8.',
			'label' => 'Get the Worksheet (PDF)',
		),
		'families'    => array(
			'post'  => 'talking-with-your-child-about-vaping-a-starter-guide-for-families',
			'file'  => 'AVA-Parent-Community-Resources.pdf',
			'name'  => 'Parent & Community Conversation Resources – Talk early. Talk often.',
			'title' => 'Keep it handy: “Talk early. Talk often.” family guide',
			'text'  => 'The facts, signs worth noticing, conversation starters and free quit support, on two printable pages.',
			'label' => 'Get the Family Guide (PDF)',
		),
	);
	if ( '' === $key ) {
		return $all;
	}
	return isset( $all[ $key ] ) ? $all[ $key ] : array();
}

/**
 * Import a bundled PDF and attach it to a post as its handout.
 *
 * @param int   $post_id Post ID.
 * @param array   $handout From ava_starter_handout().
 */
function ava_attach_handout( $post_id, $handout ) {
	// Reuse the copy already in the Media Library (e.g. the curriculum card's PDF).
	$file_id = ava_find_bundled_attachment( $handout['file'] );
	if ( ! $file_id ) {
		$file_id = ava_import_bundled_file( $handout['file'], $handout['name'] );
	}
	if ( ! $file_id ) {
		return;
	}
	update_post_meta( $post_id, '_ava_handout_id', $file_id );
	update_post_meta( $post_id, '_ava_handout_title', $handout['title'] );
	update_post_meta( $post_id, '_ava_handout_text', $handout['text'] );
	update_post_meta( $post_id, '_ava_handout_label', $handout['label'] );
}

/**
 * Find a Media Library item imported from one of the theme's bundled files.
 *
 * @param string $filename File name, e.g. AVA-Parent-Community-Resources.pdf.
 * @return int Attachment ID, or 0.
 */
function ava_find_bundled_attachment( $filename ) {
	$found = get_posts(
		array(
			'post_type'   => 'attachment',
			'post_status' => 'inherit',
			'numberposts' => 1,
			'fields'      => 'ids',
			'meta_query'  => array( // phpcs:ignore WordPress.DB.SlowDBQuery
				array(
					'key'     => '_wp_attached_file',
					'value'   => '/' . basename( $filename ),
					'compare' => 'LIKE',
				),
			),
		)
	);
	return $found ? (int) $found[0] : 0;
}

add_action( 'admin_init', 'ava_upgrade_post_handout' );
/**
 * Earlier installs: add the worksheets to the starter posts once, if those
 * posts exist and have no handout yet.
 */
function ava_upgrade_post_handout() {
	if ( get_option( 'ava_handouts_v3' ) || ! current_user_can( 'upload_files' ) ) {
		return;
	}
	update_option( 'ava_handouts_v3', 1, false );
	foreach ( ava_starter_handout() as $handout ) {
		$posts = get_posts( array( 'post_type' => 'post', 'post_status' => 'any', 'name' => $handout['post'], 'numberposts' => 1 ) );
		if ( $posts && ! get_post_meta( $posts[0]->ID, '_ava_handout_id', true ) ) {
			ava_attach_handout( $posts[0]->ID, $handout );
		}
	}
}
add_action( 'after_switch_theme', 'ava_seed_blog', 20 );

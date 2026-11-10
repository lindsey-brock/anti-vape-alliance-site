<?php
/**
 * Every editable homepage / site setting, in one place.
 *
 * Each section becomes a Customizer section under "AVA Site Content".
 * Field types: text, textarea, html, url, email, checkbox, media, select.
 *
 * @package AVA_Alliance
 */

defined( 'ABSPATH' ) || exit;

/**
 * Field definitions.
 *
 * @return array<string,array{title:string,description?:string,fields:array}>
 */
function ava_fields() {
	static $fields = null;
	if ( null !== $fields ) {
		return $fields;
	}

	$show = function ( $label ) {
		return array(
			'type'    => 'checkbox',
			'label'   => sprintf( /* translators: %s section name */ __( 'Show the %s section', 'ava-alliance' ), $label ),
			'default' => true,
		);
	};

	$fields = array(
		'header'     => array(
			'title'  => __( 'Top Bar, Header & Scrolling', 'ava-alliance' ),
			'description' => __( 'The header logo is set under Site Identity. Menu links are edited in Appearance > Menus.', 'ava-alliance' ),
			'fields' => array(
				'topbar_text'      => array( 'type' => 'text', 'label' => __( 'Top bar message above the menu (leave empty for no top bar)', 'ava-alliance' ), 'default' => '' ),
				'header_cta_label' => array( 'type' => 'text', 'label' => __( 'Header button label', 'ava-alliance' ), 'default' => 'Take the Pledge' ),
				'header_cta_url'   => array( 'type' => 'url', 'label' => __( 'Header button link (#pledge opens the pledge pop-up)', 'ava-alliance' ), 'default' => '#pledge' ),
				'stack_scroll'     => array( 'type' => 'checkbox', 'label' => __( 'Stacked scroll effect on the homepage (each section slides up over the one before). Visitors who turn off animations in their device settings always get a normal page.', 'ava-alliance' ), 'default' => true ),
				'reveal_motion'    => array( 'type' => 'checkbox', 'label' => __( 'Fade-in animation as sections appear', 'ava-alliance' ), 'default' => true ),
			),
		),
		'hero'       => array(
			'title'  => __( 'Hero (top of homepage)', 'ava-alliance' ),
			'fields' => array(
				'hero_badge'      => array( 'type' => 'text', 'label' => __( 'Small pill above the headline', 'ava-alliance' ), 'default' => 'Join the Movement' ),
				'hero_title'      => array( 'type' => 'text', 'label' => __( 'Headline', 'ava-alliance' ), 'default' => 'Students leading the conversation about' ),
				'hero_highlight'  => array( 'type' => 'text', 'label' => __( 'Headline – highlighted ending (orange)', 'ava-alliance' ), 'default' => 'vaping.' ),
				'hero_lead'       => array( 'type' => 'textarea', 'label' => __( 'Short intro (one or two sentences)', 'ava-alliance' ), 'default' => 'Honest, peer-led vaping education for students, schools and families.' ),
				'hero_background' => array( 'type' => 'media', 'label' => __( 'Background photo (defaults to the students photo). Wide photos with faces in the top half work best.', 'ava-alliance' ), 'default' => 0 ),
				'hero_focus'      => array(
					'type'    => 'select',
					'label'   => __( 'Keep this part of the photo in view on small screens', 'ava-alliance' ),
					'default' => 'center',
					'choices' => array(
						'left'   => __( 'Left', 'ava-alliance' ),
						'center' => __( 'Center', 'ava-alliance' ),
						'right'  => __( 'Right', 'ava-alliance' ),
					),
				),
				'hero_overlay'    => array(
					'type'    => 'select',
					'label'   => __( 'Dark overlay strength (for text readability)', 'ava-alliance' ),
					'default' => 'medium',
					'choices' => array(
						'light'  => __( 'Light', 'ava-alliance' ),
						'medium' => __( 'Medium', 'ava-alliance' ),
						'strong' => __( 'Strong', 'ava-alliance' ),
					),
				),
				'hero_btn1_label' => array( 'type' => 'text', 'label' => __( 'Button 1 label (main)', 'ava-alliance' ), 'default' => 'Take the Pledge' ),
				'hero_btn1_url'   => array( 'type' => 'url', 'label' => __( 'Button 1 link', 'ava-alliance' ), 'default' => '#pledge' ),
				'hero_btn2_label' => array( 'type' => 'text', 'label' => __( 'Button 2 label', 'ava-alliance' ), 'default' => 'Free Resources' ),
				'hero_btn2_url'   => array( 'type' => 'url', 'label' => __( 'Button 2 link', 'ava-alliance' ), 'default' => '#curriculum' ),
				'hero_btn3_label' => array( 'type' => 'text', 'label' => __( 'Button 3 label (optional)', 'ava-alliance' ), 'default' => '' ),
				'hero_btn3_url'   => array( 'type' => 'url', 'label' => __( 'Button 3 link', 'ava-alliance' ), 'default' => '' ),
				'hero_image'      => array( 'type' => 'media', 'label' => __( 'Logo shown in the pledge pop-up (defaults to the AVA logo)', 'ava-alliance' ), 'default' => 0 ),
			),
		),
		'mission'    => array(
			'title'       => __( 'Mission', 'ava-alliance' ),
			'description' => __( 'The three cards are edited in AVA Content > Mission Points.', 'ava-alliance' ),
			'fields'      => array(
				'mission_show'     => $show( __( 'Mission', 'ava-alliance' ) ),
				'mission_eyebrow'  => array( 'type' => 'text', 'label' => __( 'Small label', 'ava-alliance' ), 'default' => 'Our Mission' ),
				'mission_title'    => array( 'type' => 'text', 'label' => __( 'Heading', 'ava-alliance' ), 'default' => 'A movement created by young people.' ),
				'mission_intro'    => array( 'type' => 'html', 'label' => __( 'Intro (basic HTML allowed, e.g. <strong>bold</strong>; leave a blank line between paragraphs)', 'ava-alliance' ), 'default' => "AVA began with students who saw a need for honest, age-appropriate vaping education before kids reach middle school. Students help shape our programs, outreach, presentations and resources.\n\n<strong>Our goal:</strong> give students a voice in prevention — and give younger students the tools to make informed choices." ),
				'mission_numbered' => array( 'type' => 'checkbox', 'label' => __( 'Number the cards (01, 02, 03)', 'ava-alliance' ), 'default' => true ),
				'mission_layout'   => array( 'type' => 'select', 'label' => __( 'Show the Mission cards as', 'ava-alliance' ), 'default' => 'carousel', 'choices' => array( 'carousel' => __( 'Carousel with photos (number tabs and progress bar)', 'ava-alliance' ), 'cards' => __( 'Three cards side by side', 'ava-alliance' ) ) ),
				'mission_autoplay' => array( 'type' => 'checkbox', 'label' => __( 'Carousel: move to the next tab automatically', 'ava-alliance' ), 'default' => true ),
				'mission_interval' => array( 'type' => 'number', 'label' => __( 'Carousel: seconds per tab', 'ava-alliance' ), 'default' => 7 ),
			),
		),
		'difference' => array(
			'title'  => __( 'The AVA Difference (dark band)', 'ava-alliance' ),
			'fields' => array(
				'difference_show'    => $show( __( 'AVA Difference', 'ava-alliance' ) ),
				'difference_scrub'   => array( 'type' => 'checkbox', 'label' => __( 'Brighten the quote word by word as visitors scroll (the Mission card pauses until it finishes; needs the stacked scroll effect)', 'ava-alliance' ), 'default' => true ),
				'difference_eyebrow' => array( 'type' => 'text', 'label' => __( 'Small label', 'ava-alliance' ), 'default' => 'The AVA Difference' ),
				'difference_title'   => array( 'type' => 'text', 'label' => __( 'Heading', 'ava-alliance' ), 'default' => 'Peer-to-peer education can make prevention personal.' ),
				'difference_quote'   => array( 'type' => 'textarea', 'label' => __( 'Quote', 'ava-alliance' ), 'default' => '“We want younger students to hear about vaping from people they can relate to — students who understand what middle school is really like.”' ),
				'difference_text'    => array( 'type' => 'textarea', 'label' => __( 'Text under the quote (optional)', 'ava-alliance' ), 'default' => '' ),
			),
		),
		'programs'   => array(
			'title'       => __( 'Programs', 'ava-alliance' ),
			'description' => __( 'The program cards are edited in AVA Content > Programs.', 'ava-alliance' ),
			'fields'      => array(
				'programs_show'    => $show( __( 'Programs', 'ava-alliance' ) ),
				'programs_eyebrow' => array( 'type' => 'text', 'label' => __( 'Small label', 'ava-alliance' ), 'default' => 'Programs' ),
				'programs_title'   => array( 'type' => 'text', 'label' => __( 'Heading', 'ava-alliance' ), 'default' => 'Built by students. Designed to help schools.' ),
				'programs_intro'   => array( 'type' => 'textarea', 'label' => __( 'Intro (optional)', 'ava-alliance' ), 'default' => '' ),
			),
		),
		'curriculum' => array(
			'title'       => __( 'Curriculum', 'ava-alliance' ),
			'description' => __( 'Downloads are edited in AVA Content > Curriculum. Upload PDFs to the Media Library and pick them there.', 'ava-alliance' ),
			'fields'      => array(
				'curriculum_show'    => $show( __( 'Curriculum', 'ava-alliance' ) ),
				'curriculum_eyebrow' => array( 'type' => 'text', 'label' => __( 'Small label', 'ava-alliance' ), 'default' => 'Free Curriculum' ),
				'curriculum_title'   => array( 'type' => 'text', 'label' => __( 'Heading', 'ava-alliance' ), 'default' => 'Resources for Grades 4–6' ),
				'curriculum_intro'   => array( 'type' => 'textarea', 'label' => __( 'Intro', 'ava-alliance' ), 'default' => 'Our starter Lesson Pack and Teacher Notes are designed to make classroom conversations about vaping age-appropriate, interactive and student-friendly.' ),
			),
		),
		'resources'  => array(
			'title'       => __( 'Resources', 'ava-alliance' ),
			'description' => __( 'Resource boxes and their links are edited in AVA Content > Resources. Blog posts are regular WordPress Posts (Posts > Add New).', 'ava-alliance' ),
			'fields'      => array(
				'resources_show'    => $show( __( 'Resources', 'ava-alliance' ) ),
				'resources_eyebrow' => array( 'type' => 'text', 'label' => __( 'Small label', 'ava-alliance' ), 'default' => 'Resources' ),
				'resources_title'   => array( 'type' => 'text', 'label' => __( 'Heading', 'ava-alliance' ), 'default' => 'Reliable information and support' ),
				'resources_intro'   => array( 'type' => 'textarea', 'label' => __( 'Intro (optional)', 'ava-alliance' ), 'default' => '' ),
				'blog_show'         => array( 'type' => 'checkbox', 'label' => __( 'Show the latest blog posts under the resource links', 'ava-alliance' ), 'default' => true ),
				'blog_eyebrow'      => array( 'type' => 'text', 'label' => __( 'Blog – small label', 'ava-alliance' ), 'default' => 'From the Blog' ),
				'blog_title'        => array( 'type' => 'text', 'label' => __( 'Blog – heading', 'ava-alliance' ), 'default' => 'Tips, facts and student voices' ),
				'blog_count'        => array( 'type' => 'number', 'label' => __( 'How many posts to show', 'ava-alliance' ), 'default' => 3 ),
				'blog_link_label'   => array( 'type' => 'text', 'label' => __( 'Link to the full blog', 'ava-alliance' ), 'default' => 'See all posts' ),
			),
		),
		'contact'    => array(
			'title'  => __( 'Get Involved (contact)', 'ava-alliance' ),
			'fields' => array(
				'contact_show'      => $show( __( 'Contact', 'ava-alliance' ) ),
				'contact_eyebrow'   => array( 'type' => 'text', 'label' => __( 'Small label', 'ava-alliance' ), 'default' => 'Get Involved' ),
				'contact_title'     => array( 'type' => 'text', 'label' => __( 'Heading', 'ava-alliance' ), 'default' => "Let's bring student-led prevention to your community." ),
				'contact_org'       => array( 'type' => 'text', 'label' => __( 'Organization name', 'ava-alliance' ), 'default' => 'Anti-Vaping Alliance' ),
				'contact_org_line'  => array( 'type' => 'text', 'label' => __( 'Line under the name', 'ava-alliance' ), 'default' => 'Student-founded and student-led nonprofit organization' ),
				'contact_email'     => array( 'type' => 'email', 'label' => __( 'Public email address', 'ava-alliance' ), 'default' => 'info@antivapealliance.org' ),
				'contact_text'      => array( 'type' => 'textarea', 'label' => __( 'Text', 'ava-alliance' ), 'default' => 'Interested in a school presentation, chapter, curriculum partnership or community collaboration? Get in touch.' ),
				'contact_form'      => array(
					'type'    => 'select',
					'label'   => __( 'Contact form', 'ava-alliance' ),
					'default' => 'builtin',
					'choices' => array(
						'builtin'   => __( 'Built-in secure form', 'ava-alliance' ),
						'shortcode' => __( 'Form plugin shortcode (below)', 'ava-alliance' ),
						'none'      => __( 'No form – email address only', 'ava-alliance' ),
					),
				),
				'contact_shortcode' => array( 'type' => 'text', 'label' => __( 'Form plugin shortcode, e.g. [wpforms id="12"]', 'ava-alliance' ), 'default' => '' ),
			),
		),
		'pledge'     => array(
			'title'       => __( 'Pledge pop-up', 'ava-alliance' ),
			'description' => __( 'Any link to #pledge opens this pop-up, and yoursite.org/#pledge opens it directly. Signed pledges appear in AVA Content > Pledges.', 'ava-alliance' ),
			'fields'      => array(
				'pledge_eyebrow'   => array( 'type' => 'text', 'label' => __( 'Small label', 'ava-alliance' ), 'default' => 'Student Pledge' ),
				'pledge_title'     => array( 'type' => 'text', 'label' => __( 'Heading', 'ava-alliance' ), 'default' => 'Take the AVA Pledge' ),
				'pledge_text'      => array( 'type' => 'textarea', 'label' => __( 'Pledge text', 'ava-alliance' ), 'default' => 'I pledge to protect my brain, heart and lungs. I choose healthy choices over vaping and will help create a school community where students feel informed, supported and confident to say no.' ),
				'pledge_form'      => array(
					'type'    => 'select',
					'label'   => __( 'Pledge form', 'ava-alliance' ),
					'default' => 'builtin',
					'choices' => array(
						'builtin'   => __( 'Built-in secure form', 'ava-alliance' ),
						'shortcode' => __( 'Form plugin shortcode (below)', 'ava-alliance' ),
					),
				),
				'pledge_shortcode' => array( 'type' => 'text', 'label' => __( 'Form plugin shortcode', 'ava-alliance' ), 'default' => '' ),
				'pledge_last_name' => array(
					'type'    => 'select',
					'label'   => __( 'Last name field', 'ava-alliance' ),
					'default' => 'full',
					'choices' => array(
						'full'    => __( 'Full last name', 'ava-alliance' ),
						'initial' => __( 'Last initial only (collects less personal data)', 'ava-alliance' ),
						'none'    => __( 'Do not ask', 'ava-alliance' ),
					),
				),
				'pledge_ask_email' => array( 'type' => 'checkbox', 'label' => __( 'Ask for an (optional) email address', 'ava-alliance' ), 'default' => true ),
				'pledge_note'      => array( 'type' => 'textarea', 'label' => __( 'Small note under the fields (optional)', 'ava-alliance' ), 'default' => '' ),
				'pledge_button'    => array( 'type' => 'text', 'label' => __( 'Button label', 'ava-alliance' ), 'default' => 'Sign the Pledge' ),
				'pledge_thanks'    => array( 'type' => 'text', 'label' => __( 'Thank-you message', 'ava-alliance' ), 'default' => 'Thank you for standing with the Anti-Vaping Alliance!' ),
			),
		),
		'feed'       => array(
			'title'       => __( 'Pledge counter & “just signed” pop-ups', 'ava-alliance' ),
			'description' => __( 'Shown in the bottom-left corner of every page, using real pledges from AVA Content > Pledges. Pop-ups only show the first name of signers who ticked “Show my first name”.', 'ava-alliance' ),
			'fields'      => array(
				'feed_counter' => array( 'type' => 'checkbox', 'label' => __( 'Show the pledge counter', 'ava-alliance' ), 'default' => true ),
				'feed_label'   => array( 'type' => 'text', 'label' => __( 'Counter text after the number', 'ava-alliance' ), 'default' => 'pledges signed' ),
				'feed_min'     => array( 'type' => 'number', 'label' => __( 'Keep the counter hidden until there are at least this many pledges', 'ava-alliance' ), 'default' => 25 ),
				'feed_offset'  => array( 'type' => 'number', 'label' => __( 'Pledges collected outside the website (e.g. paper pledges at school events), added to the count', 'ava-alliance' ), 'default' => 0 ),
				'feed_toasts'  => array( 'type' => 'checkbox', 'label' => __( 'Show “… just took the pledge” pop-ups', 'ava-alliance' ), 'default' => true ),
			),
		),
		'forms'      => array(
			'title'  => __( 'Form notifications', 'ava-alliance' ),
			'description' => __( 'Every submission is saved in AVA Content even if email fails. On GoDaddy, an SMTP plugin (e.g. WP Mail SMTP) makes notification emails reliable.', 'ava-alliance' ),
			'fields' => array(
				'notify_email'    => array( 'type' => 'email', 'label' => __( 'Send notifications to (empty = site admin email)', 'ava-alliance' ), 'default' => '' ),
				'notify_contact'  => array( 'type' => 'checkbox', 'label' => __( 'Email me new contact messages', 'ava-alliance' ), 'default' => true ),
				'notify_pledge'   => array( 'type' => 'checkbox', 'label' => __( 'Email me each new pledge', 'ava-alliance' ), 'default' => false ),
				'contact_thanks'  => array( 'type' => 'text', 'label' => __( 'Contact thank-you message', 'ava-alliance' ), 'default' => "Thanks for reaching out! We'll get back to you soon." ),
			),
		),
		'footer'     => array(
			'title'  => __( 'Footer', 'ava-alliance' ),
			'description' => __( 'Footer links (e.g. Privacy Policy) come from the Footer menu in Appearance > Menus.', 'ava-alliance' ),
			'fields' => array(
				'footer_tagline'   => array( 'type' => 'text', 'label' => __( 'Tagline', 'ava-alliance' ), 'default' => 'Student Founded • Student Led • Nonprofit' ),
				'footer_copyright' => array( 'type' => 'text', 'label' => __( 'Copyright name (year is automatic)', 'ava-alliance' ), 'default' => 'Anti-Vaping Alliance' ),
			),
		),
		'seo'        => array(
			'title'       => __( 'SEO & Social Sharing', 'ava-alliance' ),
			'description' => __( 'Used for search results and link previews (Facebook, iMessage, LinkedIn…). Page titles come from Settings > General and each page title. If Yoast, Rank Math, SEOPress or AIOSEO is active, the theme steps aside and that plugin handles this.', 'ava-alliance' ),
			'fields'      => array(
				'seo_description' => array( 'type' => 'textarea', 'label' => __( 'Homepage description (about 150 characters)', 'ava-alliance' ), 'default' => 'The Anti-Vaping Alliance is a student-founded, student-led nonprofit offering peer-led vaping prevention, free curriculum for grades 4–6 and resources for families.' ),
				'seo_image'       => array( 'type' => 'media', 'label' => __( 'Default sharing image (1200 × 630 recommended)', 'ava-alliance' ), 'default' => 0 ),
			),
		),
	);

	return apply_filters( 'ava_fields', $fields );
}

/**
 * Flat map of field key => definition.
 *
 * @return array
 */
function ava_field_index() {
	static $index = null;
	if ( null === $index ) {
		$index = array();
		foreach ( ava_fields() as $section ) {
			foreach ( $section['fields'] as $key => $field ) {
				$index[ $key ] = $field;
			}
		}
	}
	return $index;
}

/**
 * Read a theme setting, falling back to its default.
 *
 * @param string $key Field key.
 * @return mixed
 */
function ava_mod( $key ) {
	$index   = ava_field_index();
	$default = isset( $index[ $key ] ) ? $index[ $key ]['default'] : '';
	return get_theme_mod( $key, $default );
}

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
				'topbar_text'      => array( 'type' => 'text', 'label' => __( 'Top bar message (leave empty to hide)', 'ava-alliance' ), 'default' => 'A student-founded & student-led nonprofit organization • By students, for students' ),
				'header_cta_label' => array( 'type' => 'text', 'label' => __( 'Header button label', 'ava-alliance' ), 'default' => 'Take the Pledge' ),
				'header_cta_url'   => array( 'type' => 'url', 'label' => __( 'Header button link (#pledge opens the pledge pop-up)', 'ava-alliance' ), 'default' => '#pledge' ),
				'stack_scroll'     => array( 'type' => 'checkbox', 'label' => __( 'Stacked scroll effect on the homepage (each section slides up over the one before). Visitors who turn off animations in their device settings always get a normal page.', 'ava-alliance' ), 'default' => true ),
				'reveal_motion'    => array( 'type' => 'checkbox', 'label' => __( 'Fade-in animation as sections appear', 'ava-alliance' ), 'default' => true ),
			),
		),
		'hero'       => array(
			'title'  => __( 'Hero (top of homepage)', 'ava-alliance' ),
			'fields' => array(
				'hero_badge'      => array( 'type' => 'text', 'label' => __( 'Badge', 'ava-alliance' ), 'default' => 'Student Founded • Student Led • Nonprofit' ),
				'hero_title'      => array( 'type' => 'text', 'label' => __( 'Headline', 'ava-alliance' ), 'default' => 'Students leading the conversation about' ),
				'hero_highlight'  => array( 'type' => 'text', 'label' => __( 'Headline – highlighted ending (orange)', 'ava-alliance' ), 'default' => 'vaping.' ),
				'hero_lead'       => array( 'type' => 'textarea', 'label' => __( 'Intro paragraph', 'ava-alliance' ), 'default' => 'The Anti-Vape Alliance (AVA) is a student-founded and student-led nonprofit organization empowering young people with the facts, confidence and peer support they need to make healthy choices about vaping and nicotine.' ),
				'hero_image'      => array( 'type' => 'media', 'label' => __( 'Hero image (defaults to the AVA logo)', 'ava-alliance' ), 'default' => 0 ),
				'hero_btn1_label' => array( 'type' => 'text', 'label' => __( 'Button 1 label (main)', 'ava-alliance' ), 'default' => 'Learn About AVA' ),
				'hero_btn1_url'   => array( 'type' => 'url', 'label' => __( 'Button 1 link', 'ava-alliance' ), 'default' => '#mission' ),
				'hero_btn2_label' => array( 'type' => 'text', 'label' => __( 'Button 2 label', 'ava-alliance' ), 'default' => 'Free Student Resources' ),
				'hero_btn2_url'   => array( 'type' => 'url', 'label' => __( 'Button 2 link', 'ava-alliance' ), 'default' => '#curriculum' ),
				'hero_btn3_label' => array( 'type' => 'text', 'label' => __( 'Button 3 label', 'ava-alliance' ), 'default' => 'Take the Pledge' ),
				'hero_btn3_url'   => array( 'type' => 'url', 'label' => __( 'Button 3 link', 'ava-alliance' ), 'default' => '#pledge' ),
			),
		),
		'founder'    => array(
			'title'  => __( '“By Students, For Students” band', 'ava-alliance' ),
			'fields' => array(
				'founder_show'  => $show( __( 'By Students band', 'ava-alliance' ) ),
				'founder_line1' => array( 'type' => 'text', 'label' => __( 'Big line 1', 'ava-alliance' ), 'default' => 'BY STUDENTS.' ),
				'founder_line2' => array( 'type' => 'text', 'label' => __( 'Big line 2', 'ava-alliance' ), 'default' => 'FOR STUDENTS.' ),
				'founder_title' => array( 'type' => 'text', 'label' => __( 'Heading', 'ava-alliance' ), 'default' => 'A movement created by young people.' ),
				'founder_text'  => array( 'type' => 'html', 'label' => __( 'Text (basic HTML allowed)', 'ava-alliance' ), 'default' => "AVA began with students who saw a need for honest, age-appropriate vaping education before kids reach middle school. Students help shape our programs, outreach, presentations and resources.\n\n<strong>Our goal:</strong> give students a voice in prevention — and give younger students the tools to make informed choices." ),
			),
		),
		'mission'    => array(
			'title'       => __( 'Mission', 'ava-alliance' ),
			'description' => __( 'The three cards are edited in AVA Content > Mission Points.', 'ava-alliance' ),
			'fields'      => array(
				'mission_show'     => $show( __( 'Mission', 'ava-alliance' ) ),
				'mission_eyebrow'  => array( 'type' => 'text', 'label' => __( 'Small label', 'ava-alliance' ), 'default' => 'Our Mission' ),
				'mission_title'    => array( 'type' => 'text', 'label' => __( 'Heading', 'ava-alliance' ), 'default' => 'Peer-led prevention starts with giving students a voice.' ),
				'mission_intro'    => array( 'type' => 'textarea', 'label' => __( 'Intro', 'ava-alliance' ), 'default' => 'AVA brings high-school students together to educate and mentor younger students through classroom presentations, free curriculum, school clubs and community partnerships.' ),
				'mission_numbered' => array( 'type' => 'checkbox', 'label' => __( 'Number the cards (01, 02, 03)', 'ava-alliance' ), 'default' => true ),
			),
		),
		'difference' => array(
			'title'  => __( 'The AVA Difference (dark band)', 'ava-alliance' ),
			'fields' => array(
				'difference_show'    => $show( __( 'AVA Difference', 'ava-alliance' ) ),
				'difference_eyebrow' => array( 'type' => 'text', 'label' => __( 'Small label', 'ava-alliance' ), 'default' => 'The AVA Difference' ),
				'difference_title'   => array( 'type' => 'text', 'label' => __( 'Heading', 'ava-alliance' ), 'default' => 'Peer-to-peer education can make prevention personal.' ),
				'difference_quote'   => array( 'type' => 'textarea', 'label' => __( 'Quote', 'ava-alliance' ), 'default' => '“We want younger students to hear about vaping from people they can relate to — students who understand what middle school is really like.”' ),
				'difference_text'    => array( 'type' => 'textarea', 'label' => __( 'Text under the quote', 'ava-alliance' ), 'default' => "AVA's student-led approach is designed to complement educators, parents, school counselors and community health professionals — not replace them." ),
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
			'description' => __( 'Resource boxes and their links are edited in AVA Content > Resources.', 'ava-alliance' ),
			'fields'      => array(
				'resources_show'    => $show( __( 'Resources', 'ava-alliance' ) ),
				'resources_eyebrow' => array( 'type' => 'text', 'label' => __( 'Small label', 'ava-alliance' ), 'default' => 'Resources' ),
				'resources_title'   => array( 'type' => 'text', 'label' => __( 'Heading', 'ava-alliance' ), 'default' => 'Reliable information and support' ),
				'resources_intro'   => array( 'type' => 'textarea', 'label' => __( 'Intro (optional)', 'ava-alliance' ), 'default' => '' ),
			),
		),
		'involved'   => array(
			'title'  => __( 'Get Involved (orange band)', 'ava-alliance' ),
			'fields' => array(
				'involved_show'       => $show( __( 'Get Involved', 'ava-alliance' ) ),
				'involved_eyebrow'    => array( 'type' => 'text', 'label' => __( 'Small label', 'ava-alliance' ), 'default' => 'Get Involved' ),
				'involved_title'      => array( 'type' => 'text', 'label' => __( 'Heading', 'ava-alliance' ), 'default' => 'Help students lead.' ),
				'involved_text'       => array( 'type' => 'textarea', 'label' => __( 'Text', 'ava-alliance' ), 'default' => 'Students, educators, parents and community partners can help AVA expand peer-led education and bring resources to more schools.' ),
				'involved_btn1_label' => array( 'type' => 'text', 'label' => __( 'Button 1 label (main)', 'ava-alliance' ), 'default' => 'Bring AVA to Your School' ),
				'involved_btn1_url'   => array( 'type' => 'url', 'label' => __( 'Button 1 link', 'ava-alliance' ), 'default' => '#contact' ),
				'involved_btn2_label' => array( 'type' => 'text', 'label' => __( 'Button 2 label', 'ava-alliance' ), 'default' => 'Start a Chapter' ),
				'involved_btn2_url'   => array( 'type' => 'url', 'label' => __( 'Button 2 link', 'ava-alliance' ), 'default' => '#contact' ),
				'involved_btn3_label' => array( 'type' => 'text', 'label' => __( 'Button 3 label', 'ava-alliance' ), 'default' => 'Support AVA' ),
				'involved_btn3_url'   => array( 'type' => 'url', 'label' => __( 'Button 3 link', 'ava-alliance' ), 'default' => '#contact' ),
			),
		),
		'contact'    => array(
			'title'  => __( 'Contact', 'ava-alliance' ),
			'fields' => array(
				'contact_show'      => $show( __( 'Contact', 'ava-alliance' ) ),
				'contact_eyebrow'   => array( 'type' => 'text', 'label' => __( 'Small label', 'ava-alliance' ), 'default' => 'Contact' ),
				'contact_title'     => array( 'type' => 'text', 'label' => __( 'Heading', 'ava-alliance' ), 'default' => "Let's bring student-led prevention to your community." ),
				'contact_org'       => array( 'type' => 'text', 'label' => __( 'Organization name', 'ava-alliance' ), 'default' => 'Anti-Vape Alliance' ),
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
				'pledge_thanks'    => array( 'type' => 'text', 'label' => __( 'Thank-you message', 'ava-alliance' ), 'default' => 'Thank you for standing with the Anti-Vape Alliance!' ),
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
				'footer_copyright' => array( 'type' => 'text', 'label' => __( 'Copyright name (year is automatic)', 'ava-alliance' ), 'default' => 'Anti-Vape Alliance' ),
			),
		),
		'seo'        => array(
			'title'       => __( 'SEO & Social Sharing', 'ava-alliance' ),
			'description' => __( 'Used for search results and link previews (Facebook, iMessage, LinkedIn…). Page titles come from Settings > General and each page title. If Yoast, Rank Math, SEOPress or AIOSEO is active, the theme steps aside and that plugin handles this.', 'ava-alliance' ),
			'fields'      => array(
				'seo_description' => array( 'type' => 'textarea', 'label' => __( 'Homepage description (about 150 characters)', 'ava-alliance' ), 'default' => 'The Anti-Vape Alliance is a student-founded, student-led nonprofit offering peer-led vaping prevention, free curriculum for grades 4–6 and resources for families.' ),
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

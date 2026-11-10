<?php
/**
 * Customizer: registers every field from inc/fields.php with live preview.
 *
 * @package AVA_Alliance
 */

defined( 'ABSPATH' ) || exit;

add_action( 'customize_register', 'ava_customize_register' );
/**
 * Register panel, sections, settings and controls.
 *
 * @param WP_Customize_Manager $wp_customize Manager.
 */
function ava_customize_register( $wp_customize ) {
	$wp_customize->add_panel(
		'ava_panel',
		array(
			'title'       => __( 'AVA Site Content', 'ava-alliance' ),
			'description' => __( 'Edit the homepage text, buttons, pledge pop-up, forms and sharing settings.', 'ava-alliance' ),
			'priority'    => 25,
		)
	);

	$priority = 10;
	foreach ( ava_fields() as $section_id => $section ) {
		$wp_customize->add_section(
			'ava_' . $section_id,
			array(
				'title'       => $section['title'],
				'description' => isset( $section['description'] ) ? $section['description'] : '',
				'panel'       => 'ava_panel',
				'priority'    => $priority,
			)
		);
		$priority += 10;

		foreach ( $section['fields'] as $key => $field ) {
			$wp_customize->add_setting(
				$key,
				array(
					'default'           => $field['default'],
					'sanitize_callback' => ava_sanitizer( $field ),
					'transport'         => 'refresh',
				)
			);

			$args = array(
				'label'    => $field['label'],
				'section'  => 'ava_' . $section_id,
				'settings' => $key,
			);

			switch ( $field['type'] ) {
				case 'media':
					$args['mime_type'] = 'image';
					$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, $key, $args ) );
					break;
				case 'select':
					$args['type']    = 'select';
					$args['choices'] = $field['choices'];
					$wp_customize->add_control( $key, $args );
					break;
				case 'html':
				case 'textarea':
					$args['type'] = 'textarea';
					$wp_customize->add_control( $key, $args );
					break;
				case 'url':
					// "text" (not "url") so in-page links like #pledge can be typed.
					$args['type'] = 'text';
					$wp_customize->add_control( $key, $args );
					break;
				default:
					$args['type'] = $field['type'];
					$wp_customize->add_control( $key, $args );
			}
		}
	}
}

/**
 * Pick a sanitize callback for a field.
 *
 * @param array $field Field definition.
 * @return callable
 */
function ava_sanitizer( $field ) {
	switch ( $field['type'] ) {
		case 'checkbox':
			return 'ava_sanitize_checkbox';
		case 'media':
			return 'absint';
		case 'url':
			return 'ava_sanitize_link';
		case 'email':
			return 'sanitize_email';
		case 'html':
			return 'wp_kses_post';
		case 'textarea':
			return 'sanitize_textarea_field';
		case 'select':
			$choices = array_keys( $field['choices'] );
			$default = $field['default'];
			return function ( $value ) use ( $choices, $default ) {
				return in_array( $value, $choices, true ) ? $value : $default;
			};
		default:
			return 'ava_sanitize_text';
	}
}

/**
 * Text that may contain shortcodes / quotes.
 *
 * @param string $value Value.
 * @return string
 */
function ava_sanitize_text( $value ) {
	return sanitize_text_field( $value );
}

/**
 * Checkbox to bool.
 *
 * @param mixed $value Value.
 * @return bool
 */
function ava_sanitize_checkbox( $value ) {
	return ! empty( $value );
}

/**
 * Allow full URLs, site-relative paths, mailto/tel and #anchors.
 *
 * @param string $value Value.
 * @return string
 */
function ava_sanitize_link( $value ) {
	$value = trim( (string) $value );
	if ( '' === $value ) {
		return '';
	}
	if ( '#' === $value[0] ) {
		return '#' . sanitize_title( substr( $value, 1 ) );
	}
	return esc_url_raw( $value, array( 'http', 'https', 'mailto', 'tel' ) );
}

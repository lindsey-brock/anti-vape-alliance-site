<?php
/**
 * Built-in secure pledge & contact forms.
 *
 * Protection: honeypot field, signed render timestamp (blocks instant bot
 * posts), per-visitor rate limit, strict sanitising and length limits,
 * submissions stored privately (admins only) and output escaped. Works with
 * page caching (no expiring nonce on public forms) and without JavaScript.
 * Any form plugin can replace either form via a shortcode in the Customizer.
 *
 * @package AVA_Alliance
 */

defined( 'ABSPATH' ) || exit;

/**
 * Form configuration.
 *
 * @param string $type pledge|contact.
 * @return array|null
 */
function ava_form_config( $type ) {
	$forms = array(
		'pledge'  => array(
			'post_type' => 'ava_pledge',
			'thanks'    => ava_mod( 'pledge_thanks' ),
			'anchor'    => 'pledge',
		),
		'contact' => array(
			'post_type' => 'ava_message',
			'thanks'    => ava_mod( 'contact_thanks' ),
			'anchor'    => 'contact',
		),
	);
	return isset( $forms[ $type ] ) ? $forms[ $type ] : null;
}

/**
 * Contact "I am a…" options.
 *
 * @return array
 */
function ava_contact_roles() {
	return apply_filters(
		'ava_contact_roles',
		array(
			'student'   => __( 'Student', 'ava-alliance' ),
			'parent'    => __( 'Parent or guardian', 'ava-alliance' ),
			'educator'  => __( 'Teacher or school staff', 'ava-alliance' ),
			'community' => __( 'Community partner', 'ava-alliance' ),
			'other'     => __( 'Other', 'ava-alliance' ),
		)
	);
}

/**
 * Signature for the render timestamp.
 *
 * @param string $type Form type.
 * @param int    $ts   Timestamp.
 * @return string
 */
function ava_form_sig( $type, $ts ) {
	return hash_hmac( 'sha256', $type . '|' . $ts, wp_salt( 'nonce' ) );
}

/**
 * Print a form: plugin shortcode if configured, otherwise the built-in one.
 *
 * @param string $type pledge|contact.
 */
function ava_form( $type ) {
	$mode = ava_mod( $type . '_form' );
	if ( 'none' === $mode ) {
		return;
	}
	if ( 'shortcode' === $mode ) {
		$shortcode = trim( (string) ava_mod( $type . '_shortcode' ) );
		if ( $shortcode ) {
			echo do_shortcode( $shortcode ); // phpcs:ignore WordPress.Security.EscapeOutput -- plugin output.
			return;
		}
		if ( current_user_can( 'edit_theme_options' ) ) {
			echo '<p class="ava-editor-note">' . esc_html__( 'Editor note: add the form plugin shortcode in Customize > AVA Site Content, or switch back to the built-in form. Showing the built-in form for now.', 'ava-alliance' ) . '</p>';
		}
	}
	ava_builtin_form( $type );
}

/**
 * Built-in form markup.
 *
 * @param string $type pledge|contact.
 */
function ava_builtin_form( $type ) {
	$config = ava_form_config( $type );
	if ( ! $config ) {
		return;
	}
	$ts     = time();
	$id     = 'ava-' . $type;
	$sent   = isset( $_GET['ava_sent'] ) && sanitize_key( $_GET['ava_sent'] ) === $type; // phpcs:ignore WordPress.Security.NonceVerification
	$error  = isset( $_GET['ava_error'], $_GET['ava_form'] ) && sanitize_key( $_GET['ava_form'] ) === $type ? sanitize_key( $_GET['ava_error'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
	$return = isset( $_SERVER['REQUEST_URI'] ) ? remove_query_arg( array( 'ava_sent', 'ava_error', 'ava_form' ), esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) ) : home_url( '/' );
	?>
	<div class="ava-form-wrap">
		<p class="ava-notice success" role="status" <?php echo $sent ? '' : 'hidden'; ?>><?php echo esc_html( $config['thanks'] ); ?></p>
		<p class="ava-notice error" role="alert" <?php echo $error ? '' : 'hidden'; ?>><?php echo esc_html( $error ? ava_form_error_message( $error ) : '' ); ?></p>
		<?php if ( ! $sent ) : ?>
		<form class="ava-form" id="<?php echo esc_attr( $id ); ?>-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" data-ava-form novalidate>
			<input type="hidden" name="action" value="ava_submit">
			<input type="hidden" name="ava_type" value="<?php echo esc_attr( $type ); ?>">
			<input type="hidden" name="ava_ts" value="<?php echo esc_attr( $ts ); ?>">
			<input type="hidden" name="ava_sig" value="<?php echo esc_attr( ava_form_sig( $type, $ts ) ); ?>">
			<input type="hidden" name="ava_return" value="<?php echo esc_url( $return ); ?>">
			<div class="hp" aria-hidden="true">
				<label for="<?php echo esc_attr( $id ); ?>-website">Website</label>
				<input type="text" id="<?php echo esc_attr( $id ); ?>-website" name="ava_website" tabindex="-1" autocomplete="off">
			</div>

			<?php if ( 'pledge' === $type ) : ?>
				<?php $last = ava_mod( 'pledge_last_name' ); ?>
				<div class="row">
					<div>
						<label for="<?php echo esc_attr( $id ); ?>-first"><?php esc_html_e( 'First name', 'ava-alliance' ); ?></label>
						<input type="text" id="<?php echo esc_attr( $id ); ?>-first" name="first_name" required maxlength="60" autocomplete="given-name">
					</div>
					<?php if ( 'none' !== $last ) : ?>
					<div>
						<label for="<?php echo esc_attr( $id ); ?>-last"><?php echo 'initial' === $last ? esc_html__( 'Last initial', 'ava-alliance' ) : esc_html__( 'Last name', 'ava-alliance' ); ?></label>
						<input type="text" id="<?php echo esc_attr( $id ); ?>-last" name="last_name" required maxlength="<?php echo 'initial' === $last ? 1 : 60; ?>" autocomplete="family-name">
					</div>
					<?php endif; ?>
				</div>
				<?php if ( ava_mod( 'pledge_ask_email' ) ) : ?>
					<label for="<?php echo esc_attr( $id ); ?>-email"><?php esc_html_e( 'Email', 'ava-alliance' ); ?> <span class="opt"><?php esc_html_e( '(optional)', 'ava-alliance' ); ?></span></label>
					<input type="email" id="<?php echo esc_attr( $id ); ?>-email" name="email" maxlength="120" autocomplete="email">
				<?php endif; ?>
				<?php if ( ava_mod( 'feed_toasts' ) ) : ?>
					<label class="check"><input type="checkbox" name="show_name" value="1"> <?php esc_html_e( 'Show my first name in the “just took the pledge” pop-up', 'ava-alliance' ); ?></label>
				<?php endif; ?>
				<?php if ( ava_mod( 'pledge_note' ) ) : ?>
					<p class="note"><?php echo esc_html( ava_mod( 'pledge_note' ) ); ?></p>
				<?php endif; ?>
				<button type="submit"><?php echo esc_html( ava_mod( 'pledge_button' ) ); ?></button>

			<?php else : ?>
				<div class="row">
					<div>
						<label for="<?php echo esc_attr( $id ); ?>-name"><?php esc_html_e( 'Name', 'ava-alliance' ); ?></label>
						<input type="text" id="<?php echo esc_attr( $id ); ?>-name" name="name" required maxlength="100" autocomplete="name">
					</div>
					<div>
						<label for="<?php echo esc_attr( $id ); ?>-email"><?php esc_html_e( 'Email', 'ava-alliance' ); ?></label>
						<input type="email" id="<?php echo esc_attr( $id ); ?>-email" name="email" required maxlength="120" autocomplete="email">
					</div>
				</div>
				<label for="<?php echo esc_attr( $id ); ?>-role"><?php esc_html_e( 'I am a…', 'ava-alliance' ); ?></label>
				<select id="<?php echo esc_attr( $id ); ?>-role" name="role">
					<?php foreach ( ava_contact_roles() as $value => $label ) : ?>
						<option value="<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $label ); ?></option>
					<?php endforeach; ?>
				</select>
				<label for="<?php echo esc_attr( $id ); ?>-message"><?php esc_html_e( 'Message', 'ava-alliance' ); ?></label>
				<textarea id="<?php echo esc_attr( $id ); ?>-message" name="message" required maxlength="5000"></textarea>
				<button type="submit"><?php esc_html_e( 'Send Message', 'ava-alliance' ); ?></button>
			<?php endif; ?>
		</form>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Human-readable error.
 *
 * @param string $code Error code.
 * @return string
 */
function ava_form_error_message( $code ) {
	$messages = array(
		'missing' => __( 'Please fill in the required fields.', 'ava-alliance' ),
		'email'   => __( 'Please enter a valid email address.', 'ava-alliance' ),
		'limit'   => __( 'Too many submissions. Please wait a few minutes and try again.', 'ava-alliance' ),
		'spam'    => __( 'Sorry, that submission could not be accepted. Please try again.', 'ava-alliance' ),
	);
	return isset( $messages[ $code ] ) ? $messages[ $code ] : __( 'Sorry, something went wrong. Please try again.', 'ava-alliance' );
}

add_action( 'admin_post_ava_submit', 'ava_handle_submit' );
add_action( 'admin_post_nopriv_ava_submit', 'ava_handle_submit' );
add_action( 'wp_ajax_ava_submit', 'ava_handle_submit' );
add_action( 'wp_ajax_nopriv_ava_submit', 'ava_handle_submit' );
/**
 * Validate, store and notify. Responds with JSON for AJAX, otherwise redirects.
 */
function ava_handle_submit() {
	// phpcs:disable WordPress.Security.NonceVerification.Missing -- public form; protected by signed timestamp, honeypot and rate limit (nonces break under page caching).
	$type   = isset( $_POST['ava_type'] ) ? sanitize_key( $_POST['ava_type'] ) : '';
	$config = ava_form_config( $type );
	$return = isset( $_POST['ava_return'] ) ? wp_validate_redirect( esc_url_raw( wp_unslash( $_POST['ava_return'] ) ), home_url( '/' ) ) : home_url( '/' );

	$fail = function ( $code ) use ( $type, $return, $config ) {
		if ( wp_doing_ajax() ) {
			wp_send_json_error( array( 'message' => ava_form_error_message( $code ) ), 400 );
		}
		$url = add_query_arg( array( 'ava_form' => $type, 'ava_error' => $code ), remove_query_arg( array( 'ava_sent', 'ava_error', 'ava_form' ), $return ) );
		wp_safe_redirect( $url . '#' . ( $config ? $config['anchor'] : '' ) );
		exit;
	};

	if ( ! $config || 'none' === ava_mod( $type . '_form' ) ) {
		$fail( 'spam' );
	}

	// Honeypot.
	if ( ! empty( $_POST['ava_website'] ) ) {
		$fail( 'spam' );
	}

	// Signed timestamp: must be genuine and at least 3 seconds old.
	$ts  = isset( $_POST['ava_ts'] ) ? absint( $_POST['ava_ts'] ) : 0;
	$sig = isset( $_POST['ava_sig'] ) ? sanitize_text_field( wp_unslash( $_POST['ava_sig'] ) ) : '';
	if ( ! $ts || ! hash_equals( ava_form_sig( $type, $ts ), $sig ) || ( time() - $ts ) < 3 || $ts > time() + 60 ) {
		$fail( 'spam' );
	}

	// Rate limit: 5 submissions per form per visitor per 10 minutes (IP is hashed, never stored).
	$ip    = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	$key   = 'ava_rl_' . $type . '_' . substr( hash_hmac( 'sha256', $ip, wp_salt( 'auth' ) ), 0, 24 );
	$count = (int) get_transient( $key );
	if ( $count >= 5 ) {
		$fail( 'limit' );
	}

	$text = function ( $field, $max ) {
		$value = isset( $_POST[ $field ] ) ? sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) : '';
		return mb_substr( $value, 0, $max );
	};

	$meta = array();
	if ( 'pledge' === $type ) {
		$last_mode          = ava_mod( 'pledge_last_name' );
		$meta['first_name'] = $text( 'first_name', 60 );
		$meta['last_name']  = 'none' === $last_mode ? '' : $text( 'last_name', 'initial' === $last_mode ? 1 : 60 );
		$meta['email']      = ava_mod( 'pledge_ask_email' ) ? $text( 'email', 120 ) : '';
		$meta['show_name']  = ( ava_mod( 'feed_toasts' ) && ! empty( $_POST['show_name'] ) ) ? 'yes' : 'no';
		if ( '' === $meta['first_name'] || ( 'none' !== $last_mode && '' === $meta['last_name'] ) ) {
			$fail( 'missing' );
		}
		if ( '' !== $meta['email'] && ! is_email( $meta['email'] ) ) {
			$fail( 'email' );
		}
		$title = trim( $meta['first_name'] . ' ' . ( 'full' === $last_mode ? $meta['last_name'] : ( $meta['last_name'] ? $meta['last_name'] . '.' : '' ) ) );
	} else {
		$roles           = ava_contact_roles();
		$meta['name']    = $text( 'name', 100 );
		$meta['email']   = $text( 'email', 120 );
		$role            = isset( $_POST['role'] ) ? sanitize_key( $_POST['role'] ) : 'other';
		$meta['role']    = isset( $roles[ $role ] ) ? $roles[ $role ] : $roles['other'];
		$meta['message'] = isset( $_POST['message'] ) ? mb_substr( sanitize_textarea_field( wp_unslash( $_POST['message'] ) ), 0, 5000 ) : '';
		if ( '' === $meta['name'] || '' === $meta['email'] || '' === $meta['message'] ) {
			$fail( 'missing' );
		}
		if ( ! is_email( $meta['email'] ) ) {
			$fail( 'email' );
		}
		// Very link-heavy messages are almost always spam.
		if ( preg_match_all( '#https?://#i', $meta['message'] ) > 3 ) {
			$fail( 'spam' );
		}
		$title = $meta['name'] . ' – ' . $meta['role'];
	}
	// phpcs:enable

	set_transient( $key, $count + 1, 10 * MINUTE_IN_SECONDS );

	$post_id = wp_insert_post(
		array(
			'post_type'   => $config['post_type'],
			'post_status' => 'private',
			'post_title'  => $title,
			'meta_input'  => array_combine(
				array_map(
					function ( $k ) {
						return '_ava_' . $k;
					},
					array_keys( $meta )
				),
				array_values( $meta )
			),
		),
		true
	);
	if ( is_wp_error( $post_id ) ) {
		$fail( 'server' );
	}

	if ( 'pledge' === $type ) {
		delete_transient( 'ava_pledge_feed' );
	}
	ava_notify( $type, $meta, $post_id );

	if ( wp_doing_ajax() ) {
		wp_send_json_success( array( 'message' => $config['thanks'] ) );
	}
	$url = add_query_arg( 'ava_sent', $type, remove_query_arg( array( 'ava_sent', 'ava_error', 'ava_form' ), $return ) );
	wp_safe_redirect( $url . '#' . $config['anchor'] );
	exit;
}

/**
 * Email the team about a submission, if enabled.
 *
 * @param string $type    Form type.
 * @param array  $meta    Fields.
 * @param int    $post_id Stored submission.
 */
function ava_notify( $type, $meta, $post_id ) {
	if ( ! ava_mod( 'notify_' . $type ) ) {
		return;
	}
	$to = ava_mod( 'notify_email' );
	$to = is_email( $to ) ? $to : get_option( 'admin_email' );

	$lines = array();
	foreach ( $meta as $k => $v ) {
		$lines[] = ucwords( str_replace( '_', ' ', $k ) ) . ': ' . $v;
	}
	$lines[] = '';
	$lines[] = __( 'View in WordPress:', 'ava-alliance' ) . ' ' . admin_url( 'post.php?post=' . (int) $post_id . '&action=edit' );

	$headers = array();
	if ( 'contact' === $type && is_email( $meta['email'] ) ) {
		$headers[] = 'Reply-To: ' . str_replace( array( "\r", "\n", '<', '>' ), '', $meta['name'] ) . ' <' . $meta['email'] . '>';
	}
	$subject = 'pledge' === $type
		/* translators: %s site name */
		? sprintf( __( '[%s] New pledge signed', 'ava-alliance' ), get_bloginfo( 'name' ) )
		/* translators: %s site name */
		: sprintf( __( '[%s] New contact message', 'ava-alliance' ), get_bloginfo( 'name' ) );

	wp_mail( $to, $subject, implode( "\n", $lines ), $headers );
}

/* ------------------------------------------------------------------------
 * Admin: read-only view, list columns, CSV export, privacy tools.
 * --------------------------------------------------------------------- */

add_action(
	'add_meta_boxes',
	function () {
		foreach ( array( 'ava_pledge', 'ava_message' ) as $pt ) {
			add_meta_box( 'ava_submission', __( 'Submission', 'ava-alliance' ), 'ava_submission_box', $pt, 'normal', 'high' );
		}
	}
);

/**
 * Read-only submission details.
 *
 * @param WP_Post $post Post.
 */
function ava_submission_box( $post ) {
	echo '<table class="widefat striped"><tbody>';
	foreach ( ava_submission_fields( $post->post_type ) as $key => $label ) {
		$value = (string) get_post_meta( $post->ID, '_ava_' . $key, true );
		printf( '<tr><th style="width:140px">%s</th><td>%s</td></tr>', esc_html( $label ), nl2br( esc_html( $value ) ) );
	}
	printf( '<tr><th>%s</th><td>%s</td></tr>', esc_html__( 'Received', 'ava-alliance' ), esc_html( get_the_date( 'F j, Y g:i a', $post ) ) );
	echo '</tbody></table>';
}

/**
 * Field labels per store.
 *
 * @param string $post_type Post type.
 * @return array
 */
function ava_submission_fields( $post_type ) {
	if ( 'ava_pledge' === $post_type ) {
		return array(
			'first_name' => __( 'First name', 'ava-alliance' ),
			'last_name'  => __( 'Last name', 'ava-alliance' ),
			'email'      => __( 'Email', 'ava-alliance' ),
			'show_name'  => __( 'First name shown publicly', 'ava-alliance' ),
		);
	}
	return array(
		'name'    => __( 'Name', 'ava-alliance' ),
		'email'   => __( 'Email', 'ava-alliance' ),
		'role'    => __( 'Role', 'ava-alliance' ),
		'message' => __( 'Message', 'ava-alliance' ),
	);
}

foreach ( array( 'ava_pledge', 'ava_message' ) as $ava_store ) {
	add_filter(
		"manage_{$ava_store}_posts_columns",
		function ( $cols ) use ( $ava_store ) {
			$new = array(
				'cb'    => $cols['cb'],
				'title' => __( 'Name', 'ava-alliance' ),
			);
			$new['ava_email'] = __( 'Email', 'ava-alliance' );
			if ( 'ava_message' === $ava_store ) {
				$new['ava_role']    = __( 'Role', 'ava-alliance' );
				$new['ava_message'] = __( 'Message', 'ava-alliance' );
			}
			$new['date'] = __( 'Received', 'ava-alliance' );
			return $new;
		}
	);
	add_action(
		"manage_{$ava_store}_posts_custom_column",
		function ( $col, $id ) {
			$map = array(
				'ava_email'   => '_ava_email',
				'ava_role'    => '_ava_role',
				'ava_message' => '_ava_message',
			);
			if ( isset( $map[ $col ] ) ) {
				echo esc_html( wp_trim_words( (string) get_post_meta( $id, $map[ $col ], true ), 18 ) );
			}
		},
		10,
		2
	);
	add_filter(
		"views_edit-{$ava_store}",
		function ( $views ) use ( $ava_store ) {
			$url             = wp_nonce_url( admin_url( 'admin-post.php?action=ava_export&type=' . $ava_store ), 'ava_export' );
			$views['ava_csv'] = '<a href="' . esc_url( $url ) . '"><strong>' . esc_html__( 'Download CSV', 'ava-alliance' ) . '</strong></a>';
			return $views;
		}
	);
}
unset( $ava_store );

/* Remove "Edit"/"Quick Edit" row actions: submissions are read-only. */
add_filter(
	'post_row_actions',
	function ( $actions, $post ) {
		if ( in_array( $post->post_type, array( 'ava_pledge', 'ava_message' ), true ) ) {
			unset( $actions['inline hide-if-no-js'] );
			if ( isset( $actions['edit'] ) ) {
				$actions['edit'] = str_replace( __( 'Edit' ), __( 'View', 'ava-alliance' ), $actions['edit'] ); // phpcs:ignore WordPress.WP.I18n.MissingArgDomain
			}
		}
		return $actions;
	},
	10,
	2
);

add_action( 'admin_post_ava_export', 'ava_export_csv' );
/**
 * Stream submissions as CSV (admins only).
 */
function ava_export_csv() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Sorry, you are not allowed to do that.', 'ava-alliance' ), 403 );
	}
	check_admin_referer( 'ava_export' );
	$type = isset( $_GET['type'] ) ? sanitize_key( $_GET['type'] ) : '';
	if ( ! in_array( $type, array( 'ava_pledge', 'ava_message' ), true ) ) {
		wp_die( esc_html__( 'Unknown export.', 'ava-alliance' ) );
	}
	$fields = ava_submission_fields( $type );
	$posts  = get_posts(
		array(
			'post_type'   => $type,
			'post_status' => 'private',
			'numberposts' => -1,
			'orderby'     => 'date',
			'order'       => 'ASC',
		)
	);

	nocache_headers();
	header( 'Content-Type: text/csv; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename=' . sanitize_file_name( str_replace( 'ava_', 'ava-', $type ) . 's-' . gmdate( 'Y-m-d' ) . '.csv' ) );
	$out = fopen( 'php://output', 'w' );
	fwrite( $out, "\xEF\xBB\xBF" ); // UTF-8 BOM for Excel.
	fputcsv( $out, array_merge( array_values( $fields ), array( __( 'Received', 'ava-alliance' ) ) ) );
	foreach ( $posts as $p ) {
		$row = array();
		foreach ( array_keys( $fields ) as $k ) {
			$row[] = ava_csv_safe( (string) get_post_meta( $p->ID, '_ava_' . $k, true ) );
		}
		$row[] = get_the_date( 'Y-m-d H:i', $p );
		fputcsv( $out, $row );
	}
	fclose( $out ); // phpcs:ignore WordPress.WP.AlternativeFunctions
	exit;
}

/**
 * Neutralise spreadsheet formulas in exported values.
 *
 * @param string $value Value.
 * @return string
 */
function ava_csv_safe( $value ) {
	return ( '' !== $value && in_array( $value[0], array( '=', '+', '-', '@', "\t", "\r" ), true ) ) ? "'" . $value : $value;
}

/* Privacy: suggested policy text + personal data export/erase (Tools > Export/Erase Personal Data). */
add_action(
	'admin_init',
	function () {
		if ( function_exists( 'wp_add_privacy_policy_content' ) ) {
			wp_add_privacy_policy_content(
				__( 'Anti-Vaping Alliance theme', 'ava-alliance' ),
				wp_kses_post( wpautop( __( 'When you sign the pledge we store the name you enter and, if you choose to give it, your email address. When you use the contact form we store your name, email address, role and message. This information is only visible to site administrators, is used only to respond to you or count pledges, and is never sold or shared. You can ask us to export or delete your information at any time.', 'ava-alliance' ) ) )
			);
		}
	}
);

/**
 * Submissions belonging to an email address.
 *
 * @param string $email Email.
 * @return WP_Post[]
 */
function ava_submissions_by_email( $email ) {
	return get_posts(
		array(
			'post_type'   => array( 'ava_pledge', 'ava_message' ),
			'post_status' => 'private',
			'numberposts' => 100,
			'meta_key'    => '_ava_email', // phpcs:ignore WordPress.DB.SlowDBQuery
			'meta_value'  => $email, // phpcs:ignore WordPress.DB.SlowDBQuery
		)
	);
}

add_filter(
	'wp_privacy_personal_data_exporters',
	function ( $exporters ) {
		$exporters['ava-alliance'] = array(
			'exporter_friendly_name' => __( 'AVA pledges & messages', 'ava-alliance' ),
			'callback'               => function ( $email ) {
				$data = array();
				foreach ( ava_submissions_by_email( $email ) as $p ) {
					$items = array();
					foreach ( ava_submission_fields( $p->post_type ) as $k => $label ) {
						$items[] = array( 'name' => $label, 'value' => (string) get_post_meta( $p->ID, '_ava_' . $k, true ) );
					}
					$data[] = array(
						'group_id'    => $p->post_type,
						'group_label' => 'ava_pledge' === $p->post_type ? __( 'Pledges', 'ava-alliance' ) : __( 'Messages', 'ava-alliance' ),
						'item_id'     => 'ava-' . $p->ID,
						'data'        => $items,
					);
				}
				return array( 'data' => $data, 'done' => true );
			},
		);
		return $exporters;
	}
);

add_filter(
	'wp_privacy_personal_data_erasers',
	function ( $erasers ) {
		$erasers['ava-alliance'] = array(
			'eraser_friendly_name' => __( 'AVA pledges & messages', 'ava-alliance' ),
			'callback'             => function ( $email ) {
				$removed = false;
				foreach ( ava_submissions_by_email( $email ) as $p ) {
					$removed = (bool) wp_delete_post( $p->ID, true ) || $removed;
				}
				return array( 'items_removed' => $removed, 'items_retained' => false, 'messages' => array(), 'done' => true );
			},
		);
		return $erasers;
	}
);

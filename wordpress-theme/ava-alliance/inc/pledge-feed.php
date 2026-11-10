<?php
/**
 * Pledge counter + "just took the pledge" pop-ups.
 *
 * Public endpoint /wp-json/ava/v1/pledges returns only the pledge count and
 * the first names (no last names, no emails) of recent signers who opted in.
 *
 * @package AVA_Alliance
 */

defined( 'ABSPATH' ) || exit;

/**
 * Is either part of the feed switched on?
 *
 * @return bool
 */
function ava_feed_enabled() {
	return (bool) ( ava_mod( 'feed_counter' ) || ava_mod( 'feed_toasts' ) );
}

/**
 * Build the public feed (cached for a minute, cleared on each new pledge).
 *
 * @return array
 */
function ava_pledge_feed_data() {
	$data = get_transient( 'ava_pledge_feed' );
	if ( false === $data ) {
		$counts = wp_count_posts( 'ava_pledge' );
		$recent = array();
		$posts  = get_posts(
			array(
				'post_type'   => 'ava_pledge',
				'post_status' => 'private',
				'numberposts' => 12,
				'orderby'     => 'date',
				'order'       => 'DESC',
				'meta_key'    => '_ava_show_name', // phpcs:ignore WordPress.DB.SlowDBQuery
				'meta_value'  => 'yes', // phpcs:ignore WordPress.DB.SlowDBQuery
				'date_query'  => array( array( 'after' => '30 days ago' ) ),
			)
		);
		foreach ( $posts as $p ) {
			$first = trim( (string) get_post_meta( $p->ID, '_ava_first_name', true ) );
			$first = preg_split( '/\s+/', $first );
			$first = mb_substr( sanitize_text_field( $first[0] ), 0, 20 );
			if ( '' !== $first ) {
				$recent[] = array(
					'name' => $first,
					'ts'   => (int) get_post_time( 'U', true, $p ),
				);
			}
		}
		$data = array(
			'count'  => (int) ( isset( $counts->private ) ? $counts->private : 0 ),
			'recent' => $recent,
		);
		set_transient( 'ava_pledge_feed', $data, MINUTE_IN_SECONDS );
	}
	$data['count'] += (int) ava_mod( 'feed_offset' );
	$data['now']    = time();
	return $data;
}

add_action(
	'rest_api_init',
	function () {
		register_rest_route(
			'ava/v1',
			'/pledges',
			array(
				'methods'             => 'GET',
				'permission_callback' => '__return_true',
				'callback'            => function () {
					if ( ! ava_feed_enabled() ) {
						return new WP_REST_Response( array( 'count' => 0, 'recent' => array(), 'now' => time() ), 200 );
					}
					$data = ava_pledge_feed_data();
					if ( ! ava_mod( 'feed_toasts' ) ) {
						$data['recent'] = array();
					}
					$res = new WP_REST_Response( $data, 200 );
					$res->header( 'Cache-Control', 'no-store, max-age=0' );
					return $res;
				},
			)
		);
	}
);

/**
 * Settings for theme.js.
 *
 * @return array
 */
function ava_feed_config() {
	return array(
		'url'     => ava_feed_enabled() ? rest_url( 'ava/v1/pledges' ) : '',
		'counter' => (bool) ava_mod( 'feed_counter' ),
		'toasts'  => (bool) ava_mod( 'feed_toasts' ),
		'min'     => (int) ava_mod( 'feed_min' ),
		'label'   => ava_mod( 'feed_label' ),
		'i18n'    => array(
			'signed'  => __( 'just took the pledge', 'ava-alliance' ),
			'justNow' => __( 'just now', 'ava-alliance' ),
			'min'     => __( '%d min ago', 'ava-alliance' ),
			'hr'      => __( '%d hr ago', 'ava-alliance' ),
			'day'     => __( '%d days ago', 'ava-alliance' ),
			'yday'    => __( 'yesterday', 'ava-alliance' ),
			'dismiss' => __( 'Dismiss', 'ava-alliance' ),
			'cta'     => __( 'Take the pledge', 'ava-alliance' ),
		),
	);
}

/**
 * Markup (filled in by theme.js; hidden until there is something to show).
 */
function ava_feed_markup() {
	if ( ! ava_feed_enabled() ) {
		return;
	}
	?>
	<div class="ava-proof" id="ava-proof">
		<div class="ava-toast" aria-hidden="true" hidden>
			<span class="t-ic"><svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true" focusable="false"><path d="M5 12.5l4.2 4.2L19 7" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
			<span class="t-body"><span class="t-msg"></span><span class="t-ago"></span></span>
			<button type="button" class="t-close" tabindex="-1">&times;</button>
		</div>
		<a class="ava-counter" href="#pledge" hidden>
			<span class="dot" aria-hidden="true"></span>
			<span class="ava-counter-num">0</span>
			<span class="lbl"><?php echo esc_html( ava_mod( 'feed_label' ) ); ?></span>
			<span class="screen-reader-text"> – <?php esc_html_e( 'take the pledge', 'ava-alliance' ); ?></span>
		</a>
	</div>
	<?php
}

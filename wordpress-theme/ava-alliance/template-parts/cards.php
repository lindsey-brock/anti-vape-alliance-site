<?php
/**
 * Grid of cards from a content list.
 * Args: type (post type), numbered (bool), downloads (bool).
 *
 * @package AVA_Alliance
 */

defined( 'ABSPATH' ) || exit;

$ava_args  = wp_parse_args( $args, array( 'type' => 'ava_point', 'numbered' => false, 'downloads' => false, 'buttons' => false ) );
$ava_items = ava_items( $ava_args['type'] );

if ( ! $ava_items ) {
	ava_editor_note( __( 'Editor note: no items yet – add some in AVA Content.', 'ava-alliance' ), admin_url( 'edit.php?post_type=' . $ava_args['type'] ) );
	return;
}
?>
<div class="grid">
	<?php foreach ( $ava_items as $ava_i => $ava_item ) : ?>
		<article class="card">
			<?php if ( $ava_args['numbered'] ) : ?>
				<div class="num" aria-hidden="true"><?php echo esc_html( str_pad( (string) ( $ava_i + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></div>
			<?php endif; ?>
			<h3><?php echo esc_html( get_the_title( $ava_item ) ); ?></h3>
			<div class="card-body"><?php echo wp_kses_post( apply_filters( 'the_content', $ava_item->post_content ) ); ?></div>
			<?php
			if ( $ava_args['buttons'] ) {
				$ava_label = get_post_meta( $ava_item->ID, '_ava_btn_label', true );
				$ava_url   = get_post_meta( $ava_item->ID, '_ava_btn_url', true );
				if ( $ava_label && $ava_url ) {
					echo '<div class="card-cta">';
					ava_button( $ava_label, $ava_url, true );
					echo '</div>';
				}
			}
			if ( $ava_args['downloads'] ) {
				$ava_url   = ava_download_url( $ava_item->ID );
				$ava_label = get_post_meta( $ava_item->ID, '_ava_button_label', true );
				if ( $ava_url ) {
					printf(
						'<a class="btn%s" href="%s"%s>%s<span class="screen-reader-text"> – %s</span></a>',
						get_post_meta( $ava_item->ID, '_ava_primary', true ) ? ' primary' : '',
						esc_url( $ava_url ),
						preg_match( '/\.pdf($|\?)/i', $ava_url ) ? ' download' : '',
						esc_html( $ava_label ? $ava_label : __( 'Download', 'ava-alliance' ) ),
						esc_html( get_the_title( $ava_item ) )
					);
				} else {
					// No file yet: keep the button, shown as "coming soon" (not clickable).
					printf(
						'<span class="soon-note">%s</span><span class="btn is-soon%s" aria-disabled="true">%s</span>',
						esc_html__( 'Coming soon', 'ava-alliance' ),
						get_post_meta( $ava_item->ID, '_ava_primary', true ) ? ' primary' : '',
						esc_html( $ava_label ? $ava_label : __( 'Download', 'ava-alliance' ) )
					);
					ava_editor_note( __( 'Editor note: choose a PDF for this download – until then the button shows “Coming soon”.', 'ava-alliance' ), get_edit_post_link( $ava_item->ID, 'raw' ) );
				}
			}
			?>
		</article>
	<?php endforeach; ?>
</div>

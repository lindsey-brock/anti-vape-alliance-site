<?php
/**
 * Mission cards as a carousel: number tabs with a progress bar, one photo
 * and text panel per card. Without JavaScript all panels are shown in order.
 * Photo = the card's Featured image (AVA Content → Mission Points).
 *
 * @package AVA_Alliance
 */

defined( 'ABSPATH' ) || exit;

$ava_items = ava_items( 'ava_point' );
if ( ! $ava_items ) {
	ava_editor_note( __( 'Editor note: no items yet – add some in AVA Content.', 'ava-alliance' ), admin_url( 'edit.php?post_type=ava_point' ) );
	return;
}
$ava_interval = max( 3, min( 30, (int) ava_mod( 'mission_interval' ) ) );
$ava_autoplay = ava_mod( 'mission_autoplay' ) && count( $ava_items ) > 1;
?>
<div class="ava-carousel" data-interval="<?php echo esc_attr( $ava_interval ); ?>"<?php echo $ava_autoplay ? ' data-autoplay' : ''; ?>>
	<div class="ava-carousel-tabs" role="tablist" aria-label="<?php esc_attr_e( 'What makes AVA different', 'ava-alliance' ); ?>">
		<?php foreach ( $ava_items as $ava_i => $ava_item ) : ?>
			<button type="button" class="ava-ctab" role="tab" id="mission-tab-<?php echo (int) $ava_i; ?>" aria-controls="mission-panel-<?php echo (int) $ava_i; ?>" aria-selected="<?php echo 0 === $ava_i ? 'true' : 'false'; ?>"<?php echo 0 === $ava_i ? '' : ' tabindex="-1"'; ?>>
				<span class="n"><?php echo esc_html( str_pad( (string) ( $ava_i + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
				<span class="t"><?php echo esc_html( get_the_title( $ava_item ) ); ?></span>
				<span class="bar" aria-hidden="true"><span class="fill"></span></span>
			</button>
		<?php endforeach; ?>
	</div>
	<div class="ava-carousel-panels">
		<?php foreach ( $ava_items as $ava_i => $ava_item ) : ?>
			<div class="ava-cpanel<?php echo 0 === $ava_i ? ' is-active' : ''; ?>" role="tabpanel" id="mission-panel-<?php echo (int) $ava_i; ?>" aria-labelledby="mission-tab-<?php echo (int) $ava_i; ?>">
				<div class="ava-cpanel-img">
					<?php
					if ( has_post_thumbnail( $ava_item ) ) {
						echo get_the_post_thumbnail(
							$ava_item,
							'large',
							array(
								'sizes'   => '(max-width: 900px) 100vw, 640px',
								'loading' => 0 === $ava_i ? 'eager' : 'lazy',
							)
						);
					} else {
						echo '<span class="ava-cpanel-ph" aria-hidden="true"></span>';
						ava_editor_note( __( 'Editor note: set a Featured image on this Mission card to show a photo here.', 'ava-alliance' ), get_edit_post_link( $ava_item->ID, 'raw' ) );
					}
					?>
				</div>
				<div class="ava-cpanel-txt">
					<?php if ( ava_mod( 'mission_numbered' ) ) : ?>
						<div class="num" aria-hidden="true"><?php echo esc_html( str_pad( (string) ( $ava_i + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></div>
					<?php endif; ?>
					<h3><?php echo esc_html( get_the_title( $ava_item ) ); ?></h3>
					<div class="card-body"><?php echo wp_kses_post( apply_filters( 'the_content', $ava_item->post_content ) ); ?></div>
				</div>
			</div>
		<?php endforeach; ?>
		<?php if ( $ava_autoplay ) : ?>
			<button type="button" class="ava-carousel-pause" hidden>
				<span class="ic" aria-hidden="true"></span><span class="lbl" data-play="<?php esc_attr_e( 'Play', 'ava-alliance' ); ?>" data-pause="<?php esc_attr_e( 'Pause', 'ava-alliance' ); ?>"><?php esc_html_e( 'Pause', 'ava-alliance' ); ?></span>
			</button>
		<?php endif; ?>
	</div>
</div>

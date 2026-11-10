<?php
/**
 * Hero: full-width photo with a dark brand gradient and short copy.
 *
 * @package AVA_Alliance
 */

defined( 'ABSPATH' ) || exit;

$ava_hero_classes = 'ava-hero ava-panel overlay-' . sanitize_html_class( ava_mod( 'hero_overlay' ) ) . ' focus-' . sanitize_html_class( ava_mod( 'hero_focus' ) );
?>
<section class="<?php echo esc_attr( $ava_hero_classes ); ?>" id="home">
	<?php ava_hero_background(); ?>
	<div class="hero-overlay" aria-hidden="true"></div>
	<div class="hero-in">
		<?php if ( ava_mod( 'hero_badge' ) ) : ?>
			<div class="ava-badge"><?php echo esc_html( ava_mod( 'hero_badge' ) ); ?></div>
		<?php endif; ?>
		<h1><?php echo esc_html( ava_mod( 'hero_title' ) ); ?><?php if ( ava_mod( 'hero_highlight' ) ) : ?> <span><?php echo esc_html( ava_mod( 'hero_highlight' ) ); ?></span><?php endif; ?></h1>
		<?php if ( ava_mod( 'hero_lead' ) ) : ?>
			<p class="lead"><?php echo esc_html( ava_mod( 'hero_lead' ) ); ?></p>
		<?php endif; ?>
		<?php ava_buttons( 'hero' ); ?>
	</div>
</section>

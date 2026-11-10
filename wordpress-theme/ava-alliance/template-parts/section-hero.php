<?php
/**
 * Hero.
 *
 * @package AVA_Alliance
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="ava-hero" id="home">
	<div class="hero-in">
		<div>
			<?php if ( ava_mod( 'hero_badge' ) ) : ?>
				<div class="ava-badge"><?php echo esc_html( ava_mod( 'hero_badge' ) ); ?></div>
			<?php endif; ?>
			<h1><?php echo esc_html( ava_mod( 'hero_title' ) ); ?><?php if ( ava_mod( 'hero_highlight' ) ) : ?> <span><?php echo esc_html( ava_mod( 'hero_highlight' ) ); ?></span><?php endif; ?></h1>
			<?php if ( ava_mod( 'hero_lead' ) ) : ?>
				<p class="lead"><?php echo esc_html( ava_mod( 'hero_lead' ) ); ?></p>
			<?php endif; ?>
			<?php ava_buttons( 'hero' ); ?>
		</div>
		<div class="hero-media"><?php ava_brand_image( 'hero' ); ?></div>
	</div>
</section>

<?php
/**
 * "By students. For students." band.
 *
 * @package AVA_Alliance
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="founder-wrap">
	<div class="card founder-card">
		<div aria-hidden="true">
			<div class="ava-big"><?php echo esc_html( ava_mod( 'founder_line1' ) ); ?></div>
			<div class="ava-big"><?php echo esc_html( ava_mod( 'founder_line2' ) ); ?></div>
		</div>
		<div>
			<p class="screen-reader-text"><?php echo esc_html( ava_mod( 'founder_line1' ) . ' ' . ava_mod( 'founder_line2' ) ); ?></p>
			<?php if ( ava_mod( 'founder_title' ) ) : ?>
				<h2><?php echo esc_html( ava_mod( 'founder_title' ) ); ?></h2>
			<?php endif; ?>
			<?php echo wp_kses_post( wpautop( ava_mod( 'founder_text' ) ) ); ?>
		</div>
	</div>
</div>

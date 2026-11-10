<?php
/**
 * Get Involved (orange band).
 *
 * @package AVA_Alliance
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="ava-section ava-involved" id="involved" aria-labelledby="involved-title">
	<div class="in">
		<?php ava_section_head( 'involved', 'involved-title' ); ?>
		<?php if ( ava_mod( 'involved_text' ) ) : ?>
			<p class="sub"><?php echo esc_html( ava_mod( 'involved_text' ) ); ?></p>
		<?php endif; ?>
		<?php ava_buttons( 'involved' ); ?>
	</div>
</section>

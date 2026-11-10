<?php
/**
 * The AVA Difference (dark band).
 *
 * @package AVA_Alliance
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="ava-section ava-dark" id="difference" aria-labelledby="difference-title">
	<div class="in">
		<?php ava_section_head( 'difference', 'difference-title' ); ?>
		<?php if ( ava_mod( 'difference_quote' ) ) : ?>
			<blockquote class="quote"><p><?php echo esc_html( ava_mod( 'difference_quote' ) ); ?></p></blockquote>
		<?php endif; ?>
		<?php echo ava_paragraphs( ava_mod( 'difference_text' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
	</div>
</section>

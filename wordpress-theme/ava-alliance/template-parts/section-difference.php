<?php
/**
 * The AVA Difference: dark quote block. Attached to the end of the Mission
 * card; the quote brightens word by word as you scroll (theme.js).
 *
 * @package AVA_Alliance
 */

defined( 'ABSPATH' ) || exit;

$ava_panel = get_query_var( 'ava_in_group' ) ? '' : ' ava-panel';
?>
<section class="ava-section<?php echo esc_attr( $ava_panel ); ?> ava-dark ava-scrub" id="difference" aria-labelledby="difference-title">
	<div class="scrub-stage">
		<div class="in">
			<?php ava_section_head( 'difference', 'difference-title' ); ?>
			<?php if ( ava_mod( 'difference_quote' ) ) : ?>
				<blockquote class="quote"><p data-scrub><?php echo esc_html( ava_mod( 'difference_quote' ) ); ?></p></blockquote>
			<?php endif; ?>
			<?php if ( ava_mod( 'difference_text' ) ) : ?>
				<?php echo ava_paragraphs( ava_mod( 'difference_text' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<?php endif; ?>
		</div>
	</div>
</section>

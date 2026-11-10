<?php
/**
 * Contact details + form.
 *
 * @package AVA_Alliance
 */

defined( 'ABSPATH' ) || exit;

$ava_has_form = 'none' !== ava_mod( 'contact_form' );
$ava_email    = ava_mod( 'contact_email' );
?>
<section class="ava-section ava-last tint" id="contact" aria-labelledby="contact-title">
	<div class="in">
		<?php ava_section_head( 'contact', 'contact-title' ); ?>
		<div class="contact-grid<?php echo $ava_has_form ? '' : ' single'; ?>">
			<div class="card contact-card">
				<p><strong><?php echo esc_html( ava_mod( 'contact_org' ) ); ?></strong><br><?php echo esc_html( ava_mod( 'contact_org_line' ) ); ?></p>
				<?php if ( is_email( $ava_email ) ) : ?>
					<p><strong><?php esc_html_e( 'Email:', 'ava-alliance' ); ?></strong> <a href="mailto:<?php echo esc_attr( antispambot( $ava_email ) ); ?>"><?php echo esc_html( antispambot( $ava_email ) ); ?></a></p>
				<?php endif; ?>
				<?php echo ava_paragraphs( ava_mod( 'contact_text' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</div>
			<?php if ( $ava_has_form ) : ?>
				<div class="card">
					<h3 class="screen-reader-text"><?php esc_html_e( 'Send us a message', 'ava-alliance' ); ?></h3>
					<?php ava_form( 'contact' ); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>

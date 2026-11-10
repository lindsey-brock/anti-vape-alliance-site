<?php
/**
 * Site footer + pledge pop-up.
 *
 * @package AVA_Alliance
 */

defined( 'ABSPATH' ) || exit;
?>
</main>

<footer class="ava-footer">
	<div class="in">
		<div class="fbrand">
			<?php ava_logo(); ?>
			<div>
				<strong><?php bloginfo( 'name' ); ?></strong><br>
				<?php echo esc_html( ava_mod( 'footer_tagline' ) ); ?>
				<?php
				if ( has_nav_menu( 'footer' ) ) {
					wp_nav_menu(
						array(
							'theme_location' => 'footer',
							'container'      => 'nav',
							'container_attr' => array( 'aria-label' => __( 'Footer', 'ava-alliance' ) ),
							'depth'          => 1,
							'fallback_cb'    => false,
						)
					);
				}
				?>
			</div>
		</div>
		<div>&copy; <?php echo esc_html( wp_date( 'Y' ) . ' ' . ava_mod( 'footer_copyright' ) ); ?></div>
	</div>
</footer>

<div class="ava-modal" id="pledge-modal" role="dialog" aria-modal="true" aria-labelledby="pledge-title" hidden>
	<div class="modalbox">
		<button class="close" type="button" data-ava-close aria-label="<?php esc_attr_e( 'Close', 'ava-alliance' ); ?>">&times;</button>
		<?php ava_brand_image( 'modal' ); ?>
		<?php if ( ava_mod( 'pledge_eyebrow' ) ) : ?>
			<div class="eyebrow"><?php echo esc_html( ava_mod( 'pledge_eyebrow' ) ); ?></div>
		<?php endif; ?>
		<h2 id="pledge-title"><?php echo esc_html( ava_mod( 'pledge_title' ) ); ?></h2>
		<?php echo ava_paragraphs( ava_mod( 'pledge_text' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		<?php ava_form( 'pledge' ); ?>
	</div>
</div>

<?php ava_feed_markup(); ?>

<?php wp_footer(); ?>
</body>
</html>

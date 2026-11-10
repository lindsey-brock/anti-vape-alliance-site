<?php
/**
 * Programs section.
 *
 * @package AVA_Alliance
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="ava-section ava-panel<?php echo ava_mod( 'programs_photo' ) ? ' has-photo' : ''; ?>" id="programs" aria-labelledby="programs-title">
	<?php if ( ava_mod( 'programs_photo' ) ) : ?>
		<?php ava_section_background( 'programs_bg', 'programs-lockers' ); ?>
		<div class="section-overlay" aria-hidden="true"></div>
	<?php endif; ?>
	<div class="in">
		<?php ava_section_head( 'programs', 'programs-title' ); ?>
		<?php get_template_part( 'template-parts/cards', null, array( 'type' => 'ava_program', 'buttons' => true ) ); ?>
	</div>
</section>

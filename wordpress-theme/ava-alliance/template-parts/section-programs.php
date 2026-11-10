<?php
/**
 * Programs section.
 *
 * @package AVA_Alliance
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="ava-section ava-panel" id="programs" aria-labelledby="programs-title">
	<span id="involved" class="ava-anchor" aria-hidden="true"></span>
	<div class="in">
		<?php ava_section_head( 'programs', 'programs-title' ); ?>
		<?php get_template_part( 'template-parts/cards', null, array( 'type' => 'ava_program', 'buttons' => true ) ); ?>
	</div>
</section>

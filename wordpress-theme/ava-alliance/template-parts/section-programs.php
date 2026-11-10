<?php
/**
 * Programs section.
 *
 * @package AVA_Alliance
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="ava-section" id="programs" aria-labelledby="programs-title">
	<div class="in">
		<?php ava_section_head( 'programs', 'programs-title' ); ?>
		<?php get_template_part( 'template-parts/cards', null, array( 'type' => 'ava_program' ) ); ?>
	</div>
</section>

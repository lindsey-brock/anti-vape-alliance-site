<?php
/**
 * Curriculum section.
 *
 * @package AVA_Alliance
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="ava-section tint" id="curriculum" aria-labelledby="curriculum-title">
	<div class="in">
		<?php ava_section_head( 'curriculum', 'curriculum-title' ); ?>
		<?php get_template_part( 'template-parts/cards', null, array( 'type' => 'ava_curriculum', 'downloads' => true ) ); ?>
	</div>
</section>

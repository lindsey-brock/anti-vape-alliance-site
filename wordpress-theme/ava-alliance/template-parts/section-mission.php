<?php
/**
 * Mission section.
 *
 * @package AVA_Alliance
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="ava-section<?php echo get_query_var( 'ava_in_group' ) ? '' : ' ava-panel'; ?> tint" id="mission" aria-labelledby="mission-title">
	<div class="in">
		<?php ava_section_head( 'mission', 'mission-title' ); ?>
		<?php get_template_part( 'template-parts/cards', null, array( 'type' => 'ava_point', 'numbered' => (bool) ava_mod( 'mission_numbered' ) ) ); ?>
	</div>
</section>

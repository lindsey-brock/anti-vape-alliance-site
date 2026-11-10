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
		<?php
		if ( 'cards' === ava_mod( 'mission_layout' ) ) {
			get_template_part( 'template-parts/cards', null, array( 'type' => 'ava_point', 'numbered' => (bool) ava_mod( 'mission_numbered' ) ) );
		} else {
			get_template_part( 'template-parts/mission-carousel' );
		}
		?>
	</div>
</section>

<?php
/**
 * Resources: boxes from AVA Content > Resources (links are edited in each box).
 *
 * @package AVA_Alliance
 */

defined( 'ABSPATH' ) || exit;

$ava_resources = ava_items( 'ava_resource' );
?>
<section class="ava-section" id="resources" aria-labelledby="resources-title">
	<div class="in">
		<?php ava_section_head( 'resources', 'resources-title' ); ?>
		<?php if ( $ava_resources ) : ?>
			<div class="resources">
				<?php foreach ( $ava_resources as $ava_res ) : ?>
					<div class="resource">
						<h3><?php echo esc_html( get_the_title( $ava_res ) ); ?></h3>
						<?php echo wp_kses_post( apply_filters( 'the_content', $ava_res->post_content ) ); ?>
					</div>
				<?php endforeach; ?>
			</div>
		<?php else : ?>
			<?php ava_editor_note( __( 'Editor note: add resource boxes in AVA Content > Resources.', 'ava-alliance' ), admin_url( 'edit.php?post_type=ava_resource' ) ); ?>
		<?php endif; ?>
	</div>
</section>

<?php
/**
 * Latest posts, shown as cards (used under the Resources links).
 *
 * @package AVA_Alliance
 */

defined( 'ABSPATH' ) || exit;

$ava_posts = new WP_Query(
	array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => max( 1, min( 12, (int) ava_mod( 'blog_count' ) ) ),
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	)
);
$ava_blog_url = get_option( 'page_for_posts' ) ? get_permalink( get_option( 'page_for_posts' ) ) : '';
?>
<div class="ava-blog" id="blog">
	<div class="ava-blog-head">
		<div>
			<?php if ( ava_mod( 'blog_eyebrow' ) ) : ?>
				<div class="eyebrow"><?php echo esc_html( ava_mod( 'blog_eyebrow' ) ); ?></div>
			<?php endif; ?>
			<?php if ( ava_mod( 'blog_title' ) ) : ?>
				<h3 class="ava-blog-title"><?php echo esc_html( ava_mod( 'blog_title' ) ); ?></h3>
			<?php endif; ?>
		</div>
		<?php if ( $ava_blog_url && ava_mod( 'blog_link_label' ) ) : ?>
			<a class="btn ava-blog-all" href="<?php echo esc_url( $ava_blog_url ); ?>"><?php echo esc_html( ava_mod( 'blog_link_label' ) ); ?></a>
		<?php endif; ?>
	</div>
	<?php if ( $ava_posts->have_posts() ) : ?>
		<div class="grid ava-blog-grid">
			<?php
			while ( $ava_posts->have_posts() ) :
				$ava_posts->the_post();
				?>
				<?php get_template_part( 'template-parts/post-card' ); ?>
			<?php endwhile; ?>
		</div>
		<?php wp_reset_postdata(); ?>
	<?php else : ?>
		<?php ava_editor_note( __( 'Editor note: publish a post (Posts > Add New) and it will appear here.', 'ava-alliance' ), admin_url( 'post-new.php' ) ); ?>
	<?php endif; ?>
</div>

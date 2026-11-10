<?php
/**
 * Fallback template: news/blog list, single posts, archives and search.
 *
 * @package AVA_Alliance
 */

defined( 'ABSPATH' ) || exit;

get_header();

if ( is_singular() ) :
	while ( have_posts() ) :
		the_post();
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="page-hero">
				<div class="in">
					<?php if ( 'post' === get_post_type() ) : ?>
						<?php $ava_cat = get_the_category(); ?>
						<?php if ( $ava_cat && 'uncategorized' !== $ava_cat[0]->slug ) : ?>
							<div class="eyebrow"><?php echo esc_html( $ava_cat[0]->name ); ?></div>
						<?php endif; ?>
					<?php endif; ?>
					<h1><?php the_title(); ?></h1>
					<?php if ( 'post' === get_post_type() ) : ?>
						<p class="meta"><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></p>
					<?php endif; ?>
				</div>
			</header>
			<div class="entry">
				<div class="entry-content">
					<?php if ( has_post_thumbnail() ) : ?>
						<figure class="post-hero-img"><?php the_post_thumbnail( 'large', array( 'loading' => 'eager', 'fetchpriority' => 'high' ) ); ?></figure>
					<?php endif; ?>
					<?php
					the_content();
					wp_link_pages();
					ava_post_handout();
					?>
					<?php if ( 'post' === get_post_type() ) : ?>
						<p class="post-back">
							<?php $ava_blog = get_option( 'page_for_posts' ) ? get_permalink( get_option( 'page_for_posts' ) ) : home_url( '/' ); ?>
							<a class="btn" href="<?php echo esc_url( $ava_blog ); ?>">&larr; <?php esc_html_e( 'Back to the blog', 'ava-alliance' ); ?></a>
							<a class="btn primary" href="#pledge"><?php echo esc_html( ava_mod( 'header_cta_label' ) ); ?></a>
						</p>
					<?php endif; ?>
				</div>
			</div>
		</article>
		<?php
	endwhile;
else :
	?>
	<header class="page-hero">
		<div class="in">
			<h1>
				<?php
				if ( is_search() ) {
					/* translators: %s search query */
					printf( esc_html__( 'Search results for “%s”', 'ava-alliance' ), esc_html( get_search_query() ) );
				} elseif ( is_archive() ) {
					echo esc_html( wp_strip_all_tags( get_the_archive_title() ) );
				} elseif ( is_home() && get_option( 'page_for_posts' ) ) {
					echo esc_html( get_the_title( get_option( 'page_for_posts' ) ) );
				} else {
					esc_html_e( 'News', 'ava-alliance' );
				}
				?>
			</h1>
		</div>
	</header>
	<div class="entry">
		<?php if ( have_posts() ) : ?>
			<div class="grid post-list">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/post-card' );
				endwhile;
				?>
			</div>
			<div class="pagination"><?php echo wp_kses_post( paginate_links() ); ?></div>
		<?php else : ?>
			<div class="entry-content"><p><?php esc_html_e( 'Nothing here yet.', 'ava-alliance' ); ?></p><?php get_search_form(); ?></div>
		<?php endif; ?>
	</div>
	<?php
endif;

get_footer();

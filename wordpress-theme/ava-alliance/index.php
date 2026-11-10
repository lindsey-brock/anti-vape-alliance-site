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
					<h1><?php the_title(); ?></h1>
					<?php if ( 'post' === get_post_type() ) : ?>
						<p class="meta"><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></p>
					<?php endif; ?>
				</div>
			</header>
			<div class="entry">
				<div class="entry-content">
					<?php if ( has_post_thumbnail() ) : ?>
						<figure class="alignwide"><?php the_post_thumbnail( 'large', array( 'loading' => 'eager' ) ); ?></figure>
					<?php endif; ?>
					<?php
					the_content();
					wp_link_pages();
					?>
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
			<div class="post-list">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<article <?php post_class( 'card' ); ?>>
						<?php if ( has_post_thumbnail() ) : ?>
							<a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true"><?php the_post_thumbnail( 'ava-card' ); ?></a>
						<?php endif; ?>
						<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<p class="meta"><?php echo esc_html( get_the_date() ); ?></p>
						<?php the_excerpt(); ?>
					</article>
				<?php endwhile; ?>
			</div>
			<div class="pagination"><?php echo wp_kses_post( paginate_links() ); ?></div>
		<?php else : ?>
			<div class="entry-content"><p><?php esc_html_e( 'Nothing here yet.', 'ava-alliance' ); ?></p><?php get_search_form(); ?></div>
		<?php endif; ?>
	</div>
	<?php
endif;

get_footer();

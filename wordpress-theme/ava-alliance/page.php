<?php
/**
 * Regular pages (e.g. Parent & Community, Privacy Policy). Built with the block editor.
 *
 * @package AVA_Alliance
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<header class="page-hero">
			<div class="in">
				<?php if ( $post->post_parent ) : ?>
					<div class="eyebrow"><?php echo esc_html( get_the_title( $post->post_parent ) ); ?></div>
				<?php endif; ?>
				<h1><?php the_title(); ?></h1>
				<?php if ( has_excerpt() ) : ?>
					<p class="sub"><?php echo esc_html( get_the_excerpt() ); ?></p>
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

get_footer();

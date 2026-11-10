<?php
/**
 * One post card (homepage blog strip and the Blog page).
 *
 * @package AVA_Alliance
 */

defined( 'ABSPATH' ) || exit;

$ava_cat = get_the_category();
?>
<article class="card post-card">
	<a class="post-thumb" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'ava-card', array( 'loading' => 'lazy' ) ); ?>
		<?php else : ?>
			<span class="post-thumb-fallback"><img src="<?php echo esc_url( AVA_URI . '/assets/images/ava-mark.webp' ); ?>" alt="" width="133" height="62" loading="lazy"></span>
		<?php endif; ?>
	</a>
	<div class="post-meta">
		<?php if ( $ava_cat && 'uncategorized' !== $ava_cat[0]->slug ) : ?>
			<span class="post-cat"><?php echo esc_html( $ava_cat[0]->name ); ?></span>
		<?php endif; ?>
		<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
	</div>
	<h4 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
	<div class="card-body"><p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24 ) ); ?></p></div>
	<a class="post-more" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read more', 'ava-alliance' ); ?><span class="screen-reader-text">: <?php the_title(); ?></span> &rarr;</a>
</article>

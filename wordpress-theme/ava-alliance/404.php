<?php
/**
 * Not found.
 *
 * @package AVA_Alliance
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<header class="page-hero">
	<div class="in">
		<div class="eyebrow"><?php esc_html_e( 'Page not found', 'ava-alliance' ); ?></div>
		<h1><?php esc_html_e( 'We couldn’t find that page.', 'ava-alliance' ); ?></h1>
	</div>
</header>
<div class="entry">
	<div class="entry-content">
		<p><?php esc_html_e( 'The link may be old or mistyped. Try the homepage or search below.', 'ava-alliance' ); ?></p>
		<p><a class="btn primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Go to the homepage', 'ava-alliance' ); ?></a></p>
		<?php get_search_form(); ?>
	</div>
</div>
<?php
get_footer();

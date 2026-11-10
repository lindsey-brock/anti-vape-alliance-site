<?php
/**
 * Site header.
 *
 * @package AVA_Alliance
 */

defined( 'ABSPATH' ) || exit;
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width,initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'ava-alliance' ); ?></a>

<?php if ( ava_mod( 'topbar_text' ) ) : ?>
	<div class="ava-topbar"><?php echo esc_html( ava_mod( 'topbar_text' ) ); ?></div>
<?php endif; ?>
<div class="ava-stripe" aria-hidden="true"></div>

<header class="ava-header">
	<div class="navin">
		<a class="ava-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
			<?php ava_logo(); ?>
			<span class="name"><?php echo ava_site_name_html(); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in helper. ?></span>
			<span class="screen-reader-text"><?php esc_html_e( '(home)', 'ava-alliance' ); ?></span>
		</a>

		<nav class="ava-nav" id="site-navigation" aria-label="<?php esc_attr_e( 'Main menu', 'ava-alliance' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'menu_id'        => 'primary-menu',
					'container'      => false,
					'depth'          => 2,
					'fallback_cb'    => 'ava_menu_fallback',
				)
			);
			?>
		</nav>

		<?php ava_header_cta(); ?>

		<button class="menu-toggle" type="button" aria-controls="site-navigation" aria-expanded="false">
			<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'ava-alliance' ); ?></span>
			<svg class="i-open" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M3 6h18M3 12h18M3 18h18" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" fill="none"/></svg>
			<svg class="i-close" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" fill="none"/></svg>
		</button>
	</div>
</header>

<main id="main" tabindex="-1">

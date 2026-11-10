<?php
/**
 * Anti-Vaping Alliance theme bootstrap.
 *
 * @package AVA_Alliance
 */

defined( 'ABSPATH' ) || exit;

define( 'AVA_VERSION', '1.7.2' );
define( 'AVA_DIR', get_template_directory() );
define( 'AVA_URI', get_template_directory_uri() );

require AVA_DIR . '/inc/fields.php';
require AVA_DIR . '/inc/setup.php';
require AVA_DIR . '/inc/customizer.php';
require AVA_DIR . '/inc/content-types.php';
require AVA_DIR . '/inc/forms.php';
require AVA_DIR . '/inc/pledge-feed.php';
require AVA_DIR . '/inc/seo.php';
require AVA_DIR . '/inc/template-tags.php';
require AVA_DIR . '/inc/starter-content.php';
require AVA_DIR . '/inc/starter-posts.php';
require AVA_DIR . '/inc/admin.php';

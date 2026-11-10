<?php
/**
 * "AVA Content" admin menu with a simple how-to dashboard.
 *
 * @package AVA_Alliance
 */

defined( 'ABSPATH' ) || exit;

add_action( 'admin_menu', 'ava_admin_menu', 9 );
/**
 * Top-level menu; the content lists attach under it.
 */
function ava_admin_menu() {
	add_menu_page(
		__( 'AVA Content', 'ava-alliance' ),
		__( 'AVA Content', 'ava-alliance' ),
		'edit_posts',
		'ava-content',
		'ava_admin_dashboard',
		'dashicons-megaphone',
		21
	);
	add_submenu_page( 'ava-content', __( 'AVA Content – Start here', 'ava-alliance' ), __( 'Start here', 'ava-alliance' ), 'edit_posts', 'ava-content', 'ava_admin_dashboard' );
}

/**
 * Dashboard page.
 */
function ava_admin_dashboard() {
	$customize = add_query_arg( array( 'autofocus[panel]' => 'ava_panel', 'url' => rawurlencode( home_url( '/' ) ) ), admin_url( 'customize.php' ) );
	$rows      = array(
		array( __( 'Homepage text, buttons, pledge pop-up, contact details, SEO', 'ava-alliance' ), $customize, __( 'Customize', 'ava-alliance' ), 'edit_theme_options' ),
		array( __( 'Logo and site icon', 'ava-alliance' ), add_query_arg( 'autofocus[section]', 'title_tagline', admin_url( 'customize.php' ) ), __( 'Site Identity', 'ava-alliance' ), 'edit_theme_options' ),
		array( __( 'Header and footer menu links', 'ava-alliance' ), admin_url( 'nav-menus.php' ), __( 'Menus', 'ava-alliance' ), 'edit_theme_options' ),
		array( __( 'Upload PDFs and images', 'ava-alliance' ), admin_url( 'media-new.php' ), __( 'Media Library', 'ava-alliance' ), 'upload_files' ),
		array( __( 'The three Mission cards', 'ava-alliance' ), admin_url( 'edit.php?post_type=ava_point' ), __( 'Mission Points', 'ava-alliance' ), 'edit_posts' ),
		array( __( 'Program cards', 'ava-alliance' ), admin_url( 'edit.php?post_type=ava_program' ), __( 'Programs', 'ava-alliance' ), 'edit_posts' ),
		array( __( 'Curriculum downloads (pick a PDF for each)', 'ava-alliance' ), admin_url( 'edit.php?post_type=ava_curriculum' ), __( 'Curriculum', 'ava-alliance' ), 'edit_posts' ),
		array( __( 'Resource boxes and their links', 'ava-alliance' ), admin_url( 'edit.php?post_type=ava_resource' ), __( 'Resources', 'ava-alliance' ), 'edit_posts' ),
		array( __( 'Other pages (e.g. Parent & Community, Privacy Policy)', 'ava-alliance' ), admin_url( 'edit.php?post_type=page' ), __( 'Pages', 'ava-alliance' ), 'edit_pages' ),
		array( __( 'Signed pledges (download as CSV)', 'ava-alliance' ), admin_url( 'edit.php?post_type=ava_pledge' ), __( 'Pledges', 'ava-alliance' ), 'manage_options' ),
		array( __( 'Contact form messages', 'ava-alliance' ), admin_url( 'edit.php?post_type=ava_message' ), __( 'Messages', 'ava-alliance' ), 'manage_options' ),
	);

	$missing = array();
	foreach ( ava_items( 'ava_curriculum' ) as $item ) {
		if ( ! ava_download_url( $item->ID ) ) {
			$missing[] = $item;
		}
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'AVA Content – Start here', 'ava-alliance' ); ?></h1>
		<p><?php esc_html_e( 'Everything on the website can be edited from the places below. Card order is set with the “Order” box on each item (lowest number first).', 'ava-alliance' ); ?></p>

		<?php if ( $missing ) : ?>
			<div class="notice notice-warning inline"><p>
				<strong><?php esc_html_e( 'These curriculum downloads have no file yet, so their button is hidden on the site:', 'ava-alliance' ); ?></strong>
				<?php
				echo implode(
					', ',
					array_map(
						function ( $p ) {
							return '<a href="' . esc_url( get_edit_post_link( $p->ID ) ) . '">' . esc_html( get_the_title( $p ) ) . '</a>';
						},
						$missing
					)
				); // phpcs:ignore WordPress.Security.EscapeOutput
				?>
			</p></div>
		<?php endif; ?>

		<table class="widefat striped" style="max-width:900px;margin-top:16px">
			<thead><tr><th><?php esc_html_e( 'What you want to change', 'ava-alliance' ); ?></th><th><?php esc_html_e( 'Where', 'ava-alliance' ); ?></th></tr></thead>
			<tbody>
			<?php foreach ( $rows as $row ) : ?>
				<?php
				if ( ! current_user_can( $row[3] ) ) {
					continue;
				}
				?>
				<tr><td><?php echo esc_html( $row[0] ); ?></td><td><a class="button" href="<?php echo esc_url( $row[1] ); ?>"><?php echo esc_html( $row[2] ); ?></a></td></tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<?php
}

add_action( 'admin_notices', 'ava_activation_notice' );
/**
 * One-time welcome notice after activation.
 */
function ava_activation_notice() {
	global $pagenow;
	if ( 'themes.php' !== $pagenow || ! isset( $_GET['activated'] ) || ! current_user_can( 'edit_theme_options' ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		return;
	}
	printf(
		'<div class="notice notice-success is-dismissible"><p>%s <a href="%s">%s</a></p></div>',
		esc_html__( 'The Anti-Vaping Alliance theme is active and the homepage content has been set up.', 'ava-alliance' ),
		esc_url( admin_url( 'admin.php?page=ava-content' ) ),
		esc_html__( 'See where to edit everything →', 'ava-alliance' )
	);
}

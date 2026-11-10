<?php
/**
 * Editable content lists (AVA Content menu) and their fields.
 *
 * @package AVA_Alliance
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'ava_register_content_types' );
/**
 * Register the card / download / resource lists and the private submission stores.
 */
function ava_register_content_types() {
	$lists = array(
		'ava_point'      => array( __( 'Mission Points', 'ava-alliance' ), __( 'Mission Point', 'ava-alliance' ) ),
		'ava_program'    => array( __( 'Programs', 'ava-alliance' ), __( 'Program', 'ava-alliance' ) ),
		'ava_curriculum' => array( __( 'Curriculum', 'ava-alliance' ), __( 'Curriculum Download', 'ava-alliance' ) ),
		'ava_resource'   => array( __( 'Resources', 'ava-alliance' ), __( 'Resource Box', 'ava-alliance' ) ),
	);

	foreach ( $lists as $type => $names ) {
		register_post_type(
			$type,
			array(
				'labels'              => array(
					'name'          => $names[0],
					'singular_name' => $names[1],
					'add_new'       => __( 'Add New', 'ava-alliance' ),
					'add_new_item'  => sprintf( /* translators: %s item name */ __( 'Add %s', 'ava-alliance' ), $names[1] ),
					'edit_item'     => sprintf( /* translators: %s item name */ __( 'Edit %s', 'ava-alliance' ), $names[1] ),
					'all_items'     => $names[0],
				),
				'public'              => false,
				'show_ui'             => true,
				'show_in_menu'        => 'ava-content',
				'show_in_rest'        => true,
				'exclude_from_search' => true,
				'publicly_queryable'  => false,
				'has_archive'         => false,
				'rewrite'             => false,
				'supports'            => 'ava_point' === $type ? array( 'title', 'editor', 'thumbnail', 'page-attributes', 'revisions' ) : array( 'title', 'editor', 'page-attributes', 'revisions' ),
			)
		);
	}

	// Private stores for form submissions. Only administrators can view them.
	$admin_caps = array(
		'edit_post'              => 'manage_options',
		'read_post'              => 'manage_options',
		'delete_post'            => 'manage_options',
		'edit_posts'             => 'manage_options',
		'edit_others_posts'      => 'manage_options',
		'edit_private_posts'     => 'manage_options',
		'edit_published_posts'   => 'manage_options',
		'delete_posts'           => 'manage_options',
		'delete_others_posts'    => 'manage_options',
		'delete_private_posts'   => 'manage_options',
		'delete_published_posts' => 'manage_options',
		'read_private_posts'     => 'manage_options',
		'publish_posts'          => 'do_not_allow',
		'create_posts'           => 'do_not_allow',
	);
	$stores     = array(
		'ava_pledge'  => array( __( 'Pledges', 'ava-alliance' ), __( 'Pledge', 'ava-alliance' ) ),
		'ava_message' => array( __( 'Messages', 'ava-alliance' ), __( 'Message', 'ava-alliance' ) ),
	);
	foreach ( $stores as $type => $names ) {
		register_post_type(
			$type,
			array(
				'labels'              => array(
					'name'          => $names[0],
					'singular_name' => $names[1],
					'edit_item'     => $names[1],
					'all_items'     => $names[0],
					'not_found'     => __( 'Nothing yet.', 'ava-alliance' ),
				),
				'public'              => false,
				'show_ui'             => true,
				'show_in_menu'        => 'ava-content',
				'show_in_rest'        => false,
				'exclude_from_search' => true,
				'publicly_queryable'  => false,
				'rewrite'             => false,
				'supports'            => array( 'title' ),
				'capabilities'        => $admin_caps,
				'map_meta_cap'        => false,
			)
		);
	}
}

/**
 * Fetch the items of a content list, in the order set by "Order".
 *
 * @param string $type Post type.
 * @return WP_Post[]
 */
function ava_items( $type ) {
	return get_posts(
		array(
			'post_type'        => $type,
			'post_status'      => 'publish',
			'numberposts'      => 50,
			'orderby'          => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
			'suppress_filters' => false,
		)
	);
}

/* ------------------------------------------------------------------------
 * Curriculum download fields: file (Media Library or URL), button label, style.
 * --------------------------------------------------------------------- */

add_action( 'add_meta_boxes_ava_curriculum', 'ava_curriculum_meta_box' );
/**
 * Add the download meta box.
 */
function ava_curriculum_meta_box() {
	add_meta_box( 'ava_download', __( 'Download button', 'ava-alliance' ), 'ava_curriculum_meta_box_html', 'ava_curriculum', 'normal', 'high' );
}

/**
 * Meta box markup.
 *
 * @param WP_Post $post Post.
 */
function ava_curriculum_meta_box_html( $post ) {
	wp_nonce_field( 'ava_download_save', 'ava_download_nonce' );
	$file_id = (int) get_post_meta( $post->ID, '_ava_file_id', true );
	$url     = (string) get_post_meta( $post->ID, '_ava_url', true );
	$label   = (string) get_post_meta( $post->ID, '_ava_button_label', true );
	$primary = (bool) get_post_meta( $post->ID, '_ava_primary', true );
	$file    = $file_id ? get_attached_file( $file_id ) : '';
	?>
	<p><?php esc_html_e( 'Pick a PDF from the Media Library (recommended) or paste a link.', 'ava-alliance' ); ?></p>
	<p>
		<input type="hidden" id="ava_file_id" name="ava_file_id" value="<?php echo esc_attr( $file_id ); ?>">
		<button type="button" class="button" id="ava_pick_file"><?php esc_html_e( 'Choose file from Media Library', 'ava-alliance' ); ?></button>
		<button type="button" class="button-link-delete" id="ava_clear_file" <?php echo $file_id ? '' : 'hidden'; ?>><?php esc_html_e( 'Remove', 'ava-alliance' ); ?></button>
		<strong id="ava_file_name" style="margin-left:8px"><?php echo $file ? esc_html( wp_basename( $file ) ) : ''; ?></strong>
	</p>
	<p>
		<label for="ava_url"><strong><?php esc_html_e( 'Or link to', 'ava-alliance' ); ?></strong></label><br>
		<input type="url" class="widefat" id="ava_url" name="ava_url" value="<?php echo esc_attr( $url ); ?>" placeholder="https://">
	</p>
	<p>
		<label for="ava_button_label"><strong><?php esc_html_e( 'Button label', 'ava-alliance' ); ?></strong></label><br>
		<input type="text" class="widefat" id="ava_button_label" name="ava_button_label" value="<?php echo esc_attr( $label ); ?>" placeholder="<?php esc_attr_e( 'Download PDF', 'ava-alliance' ); ?>">
	</p>
	<p><label><input type="checkbox" name="ava_primary" value="1" <?php checked( $primary ); ?>> <?php esc_html_e( 'Highlight this button (orange)', 'ava-alliance' ); ?></label></p>
	<?php
}

add_action( 'admin_enqueue_scripts', 'ava_curriculum_admin_js' );
/**
 * Media picker for the download meta box.
 *
 * @param string $hook Admin page.
 */
function ava_curriculum_admin_js( $hook ) {
	$screen = get_current_screen();
	if ( ! $screen || 'ava_curriculum' !== $screen->post_type || ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}
	wp_enqueue_media();
	$js = <<<'JS'
jQuery(function($){var frame;$('#ava_pick_file').on('click',function(e){e.preventDefault();if(!frame){frame=wp.media({title:AVA_PICK.title,button:{text:AVA_PICK.button},multiple:false});frame.on('select',function(){var a=frame.state().get('selection').first().toJSON();$('#ava_file_id').val(a.id);$('#ava_file_name').text(a.filename||a.title);$('#ava_clear_file').prop('hidden',false);});}frame.open();});$('#ava_clear_file').on('click',function(){$('#ava_file_id').val('');$('#ava_file_name').text('');$(this).prop('hidden',true);});});
JS;
	$labels = wp_json_encode(
		array(
			'title'  => __( 'Choose a download', 'ava-alliance' ),
			'button' => __( 'Use this file', 'ava-alliance' ),
		)
	);
	wp_add_inline_script( 'jquery-core', 'var AVA_PICK=' . $labels . ';' . $js );
}

add_action( 'add_meta_boxes_ava_program', 'ava_program_meta_box' );
/**
 * Optional button under a program card.
 */
function ava_program_meta_box() {
	add_meta_box( 'ava_program_btn', __( 'Button under this card', 'ava-alliance' ), 'ava_program_meta_box_html', 'ava_program', 'normal', 'high' );
}

/**
 * Program button fields.
 *
 * @param WP_Post $post Post.
 */
function ava_program_meta_box_html( $post ) {
	wp_nonce_field( 'ava_program_btn_save', 'ava_program_btn_nonce' );
	?>
	<p>
		<label for="ava_btn_label"><strong><?php esc_html_e( 'Button label', 'ava-alliance' ); ?></strong></label><br>
		<input type="text" class="widefat" id="ava_btn_label" name="ava_btn_label" value="<?php echo esc_attr( get_post_meta( $post->ID, '_ava_btn_label', true ) ); ?>" placeholder="<?php esc_attr_e( 'e.g. Start a Chapter', 'ava-alliance' ); ?>">
	</p>
	<p>
		<label for="ava_btn_url"><strong><?php esc_html_e( 'Button link', 'ava-alliance' ); ?></strong></label><br>
		<input type="text" class="widefat" id="ava_btn_url" name="ava_btn_url" value="<?php echo esc_attr( get_post_meta( $post->ID, '_ava_btn_url', true ) ); ?>" placeholder="#contact">
		<span class="description"><?php esc_html_e( '#contact jumps to the contact form; #pledge, #school and #chapter open those pop-ups; or paste any web address (it opens in a new tab). Leave the label empty for no button.', 'ava-alliance' ); ?></span>
	</p>
	<?php
}

add_action( 'save_post_ava_program', 'ava_program_save' );
/**
 * Save program button fields.
 *
 * @param int $post_id Post ID.
 */
function ava_program_save( $post_id ) {
	if ( ! isset( $_POST['ava_program_btn_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['ava_program_btn_nonce'] ), 'ava_program_btn_save' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	update_post_meta( $post_id, '_ava_btn_label', isset( $_POST['ava_btn_label'] ) ? sanitize_text_field( wp_unslash( $_POST['ava_btn_label'] ) ) : '' );
	update_post_meta( $post_id, '_ava_btn_url', isset( $_POST['ava_btn_url'] ) ? ava_sanitize_link( wp_unslash( $_POST['ava_btn_url'] ) ) : '' );
}

add_action( 'save_post_ava_curriculum', 'ava_curriculum_save' );
/**
 * Save download fields.
 *
 * @param int $post_id Post ID.
 */
function ava_curriculum_save( $post_id ) {
	if ( ! isset( $_POST['ava_download_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['ava_download_nonce'] ), 'ava_download_save' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	update_post_meta( $post_id, '_ava_file_id', isset( $_POST['ava_file_id'] ) ? absint( $_POST['ava_file_id'] ) : 0 );
	update_post_meta( $post_id, '_ava_url', isset( $_POST['ava_url'] ) ? esc_url_raw( wp_unslash( $_POST['ava_url'] ) ) : '' );
	update_post_meta( $post_id, '_ava_button_label', isset( $_POST['ava_button_label'] ) ? sanitize_text_field( wp_unslash( $_POST['ava_button_label'] ) ) : '' );
	update_post_meta( $post_id, '_ava_primary', empty( $_POST['ava_primary'] ) ? 0 : 1 );
}

/**
 * Resolve a curriculum item's download link.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function ava_download_url( $post_id ) {
	$file_id = (int) get_post_meta( $post_id, '_ava_file_id', true );
	if ( $file_id ) {
		$url = wp_get_attachment_url( $file_id );
		if ( $url ) {
			return $url;
		}
	}
	return (string) get_post_meta( $post_id, '_ava_url', true );
}

/* Order column on the list screens so the team can see card order. */
foreach ( array( 'ava_point', 'ava_program', 'ava_curriculum', 'ava_resource' ) as $ava_type ) {
	add_filter(
		"manage_{$ava_type}_posts_columns",
		function ( $cols ) {
			$cols['ava_order'] = __( 'Order', 'ava-alliance' );
			return $cols;
		}
	);
	add_action(
		"manage_{$ava_type}_posts_custom_column",
		function ( $col, $id ) {
			if ( 'ava_order' === $col ) {
				echo (int) get_post_field( 'menu_order', $id );
			}
		},
		10,
		2
	);
}
unset( $ava_type );

add_filter( 'use_block_editor_for_post_type', 'ava_simple_editor', 10, 2 );
/**
 * The short card lists use the simple classic editor, so the download picker
 * and the "Order" box are visible right away. Pages and posts keep the block editor.
 *
 * @param bool   $use  Use block editor.
 * @param string $type Post type.
 * @return bool
 */
function ava_simple_editor( $use, $type ) {
	return in_array( $type, array( 'ava_point', 'ava_program', 'ava_curriculum', 'ava_resource' ), true ) ? false : $use;
}

<?php

class DTLMSPackagesPostType {

	function __construct() {

		add_action ( 'init', array ( $this, 'dtlms_init' ) );
		add_action ( 'admin_init', array ( $this, 'dtlms_admin_init' ) );
		add_filter ( 'template_include', array ( $this, 'dtlms_template_include' ) );

	}

	function dtlms_init() {

		$this->createPostType();
		add_action ( 'save_post', array ( $this, 'dtlms_save_post_meta' ) );

		add_filter ( 'dtlms_admin_menu_and_order', array ( $this, 'dtlms_admin_menu_and_order_update'  ), 30 );
		add_filter ( 'dtlms_woo_purchase_cpt', array ( $this, 'dtlms_woo_purchase_cpt_update' ), 15, 1 );
		add_filter ( 'dtlms_cpt_items', array ( $this, 'dtlms_cpt_items_update'  ), 10, 7 );

	}

	function createPostType() {

		$labels = array (
			'name'               => esc_html__('Packages', 'dtlms-lite'),
			'all_items'          => esc_html__('All Packages', 'dtlms-lite'),
			'singular_name'      => esc_html__('Package', 'dtlms-lite'),
			'add_new'            => esc_html__('Add New', 'dtlms-lite'),
			'add_new_item'       => esc_html__('Add New Package', 'dtlms-lite'),
			'edit_item'          => esc_html__('Edit Package', 'dtlms-lite'),
			'new_item'           => esc_html__('New Package', 'dtlms-lite'),
			'view_item'          => esc_html__('View Package', 'dtlms-lite'),
			'search_items'       => esc_html__('Search Packages', 'dtlms-lite'),
			'not_found'          => esc_html__('No Packages found', 'dtlms-lite'),
			'not_found_in_trash' => esc_html__('No Packages found in Trash', 'dtlms-lite'),
			'parent_item_colon'  => esc_html__('Parent Package: ', 'dtlms-lite'),
			'menu_name'          => esc_html__('Packages', 'dtlms-lite' )
		);

		$args = array (
			'labels'       => $labels,
			'hierarchical' => true,
			'description'  => esc_html__('This is custom post type packages','dtlms-lite'),
			'supports'     => array (
				'title',
				'editor',
				'excerpt',
				'author',
				'page-attributes',
				'thumbnail'
			),
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => false,
			'show_in_nav_menus'   => false,
			'publicly_queryable'  => true,
			'exclude_from_search' => false,
			'has_archive'         => true,
			'query_var'           => true,
			'can_export'          => true,
			'capability_type'     => 'post'
		);

		register_post_type ( 'dtlms_packages', $args );
	}

	function dtlms_admin_menu_and_order_update() {

		add_submenu_page( 'dtlms-lite', esc_html__( 'All Packages', 'dtlms-lite' ), esc_html__( 'All Packages', 'dtlms-lite' ), 'edit_posts', 'edit.php?post_type=dtlms_packages' );

	}

	function dtlms_save_post_meta($post_id) {

		if( key_exists ( '_inline_edit', $_POST )) :
			if ( wp_verify_nonce($_POST['_inline_edit'], 'inlineeditnonce')) return;
		endif;

		if( key_exists( 'dtlms_packages_meta_nonce', $_POST ) ) :
			if ( ! wp_verify_nonce( $_POST['dtlms_packages_meta_nonce'], 'dtlms_packages_nonce') ) return;
		endif;

		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

		if (!current_user_can('edit_post', $post_id)) :
			return;
		endif;

		if ( (key_exists('post_type', $_POST)) && ('dtlms_packages' == $_POST['post_type']) ) :

			if(isset( $_POST ['subtitle'] ) && !empty($_POST ['subtitle'])) {
				update_post_meta ( $post_id, 'subtitle', sanitize_text_field ( $_POST ['subtitle'] ) );
			} else {
				delete_post_meta ( $post_id, 'subtitle' );
			}

			if(isset( $_POST ['courses-included'] ) && $_POST ['courses-included'] != '' ) {
				update_post_meta ( $post_id, 'courses-included', sanitize_text_field ( $_POST ['courses-included'] ) );
			} else {
				delete_post_meta ( $post_id, 'courses-included' );
			}

			if(isset( $_POST ['classes-included'] ) && $_POST ['classes-included'] != '' ) {
				update_post_meta ( $post_id, 'classes-included', sanitize_text_field ( $_POST ['classes-included'] ) );
			} else {
				delete_post_meta ( $post_id, 'classes-included' );
			}

			if(isset( $_POST ['period'] ) && !empty($_POST ['period'])) {
				update_post_meta ( $post_id, 'period', sanitize_text_field ( $_POST ['period'] ) );
			} else {
				delete_post_meta ( $post_id, 'period' );
			}

			if(isset( $_POST ['term'] ) && !empty($_POST ['term'])) {
				update_post_meta ( $post_id, 'term', sanitize_text_field ( $_POST ['term'] ) );
			} else {
				delete_post_meta ( $post_id, 'term' );
			}

		endif;

	}

	function dtlms_woo_purchase_cpt_update($cpt) {
		array_push($cpt, 'dtlms_packages');
		return $cpt;
	}

	function dtlms_cpt_items_update($cpts) {
		$cpts['packages'] = esc_html__('Packages', 'dtlms-lite');
		return $cpts;
	}

	function dtlms_admin_init() {
		add_action ( 'add_meta_boxes', array ( $this, 'dtlms_add_package_default_metabox' ) );
	}

	function dtlms_add_package_default_metabox() {
		add_meta_box ( 'dtlms-package-default-metabox', esc_html__('Package Options', 'dtlms-lite'), array ( $this, 'dtlms_package_default_metabox' ), 'dtlms_packages', 'normal', 'default' );
	}

	function dtlms_package_default_metabox() {
		include_once plugin_dir_path ( __FILE__ ) . 'metaboxes/package-default-metabox.php';
	}


	function dtlms_template_include($template) {

		if (is_singular( 'dtlms_packages' )) {
			$template = plugin_dir_path ( __FILE__ ) . 'templates/single-dtlms_packages.php';
		} elseif ( is_post_type_archive('dtlms_packages') ) {
			$template = plugin_dir_path ( __FILE__ ) . 'templates/archive-dtlms_packages.php';
		}

		return $template;

	}

}
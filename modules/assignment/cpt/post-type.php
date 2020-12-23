<?php
if( !class_exists('DTLMSAssignmentPostType') ) {

	class DTLMSAssignmentPostType {

		/**
		 * Instance variable
		 */
		private static $_instance = null;

		/**
		 * Instance
		 *
		 * Ensures only one instance of the class is loaded or can be loaded.
		 */
		public static function instance() {

			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		function __construct() {

			add_action ( 'init', array ( $this, 'dtlms_init' ) );
			add_action ( 'admin_init', array ( $this, 'dtlms_admin_init' ) );
			add_filter ( 'template_include', array ( $this, 'dtlms_template_include' ) );

		}

		function dtlms_init() {

			$this->createPostType();
			add_action ( 'save_post', array ( $this, 'dtlms_save_post_meta' ) );

			add_filter ( 'dtlms_cpt_items', array ( $this, 'dtlms_cpt_items_update'  ), 10, 6 );

		}

		function createPostType() {

			$labels = array (
				'name'               => esc_html__('Assignments', 'dtlms-lite'),
				'all_items'          => esc_html__('All Assignments', 'dtlms-lite'),
				'singular_name'      => esc_html__('Assignment', 'dtlms-lite'),
				'add_new'            => esc_html__('Add New', 'dtlms-lite'),
				'add_new_item'       => esc_html__('Add New Assignment', 'dtlms-lite'),
				'edit_item'          => esc_html__('Edit Assignment', 'dtlms-lite'),
				'new_item'           => esc_html__('New Assignment', 'dtlms-lite'),
				'view_item'          => esc_html__('View Assignment', 'dtlms-lite'),
				'search_items'       => esc_html__('Search Assignments', 'dtlms-lite'),
				'not_found'          => esc_html__('No Assignments found', 'dtlms-lite'),
				'not_found_in_trash' => esc_html__('No Assignments found in Trash', 'dtlms-lite'),
				'parent_item_colon'  => esc_html__('Parent Assignment: ', 'dtlms-lite'),
				'menu_name'          => esc_html__('Assignments', 'dtlms-lite' )
			);

			$args = array (
					'labels'              => $labels,
					'hierarchical'        => true,
					'description'         => esc_html__( 'This is custom post type assignments', 'dtlms-lite' ),
					'supports'            => array (
							'title',
							'editor',
							'author',
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

			register_post_type ( 'dtlms_assignments', $args );
		}

		function dtlms_save_post_meta($post_id) {

			if( key_exists ( '_inline_edit', $_POST )) :
				if ( wp_verify_nonce($_POST['_inline_edit'], 'inlineeditnonce')) return;
			endif;

			if( key_exists( 'dtlms_assignments_meta_nonce', $_POST ) ) :
				if ( ! wp_verify_nonce( $_POST['dtlms_assignments_meta_nonce'], 'dtlms_assignments_nonce') ) return;
			endif;

			if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

			if (!current_user_can('edit_post', $post_id)) :
				return;
			endif;

			if ( (key_exists('post_type', $_POST)) && ('dtlms_assignments' == $_POST['post_type']) ) :

				if( isset( $_POST ['free-assignment'] ) && $_POST ['free-assignment'] != '' ) {
					update_post_meta ( $post_id, 'free-assignment', sanitize_text_field( $_POST ['free-assignment'] ) );
				} else {
					delete_post_meta ( $post_id, 'free-assignment' );
				}

				if( isset( $_POST ['assignment-subtitle'] ) && $_POST ['assignment-subtitle'] != '' ) {
					update_post_meta ( $post_id, 'assignment-subtitle', sanitize_text_field ( $_POST ['assignment-subtitle'] ) );
				} else {
					delete_post_meta ( $post_id, 'assignment-subtitle' );
				}

				if( isset( $_POST ['assignment-maximum-mark'] ) && $_POST ['assignment-maximum-mark'] != '' ) {
					update_post_meta ( $post_id, 'assignment-maximum-mark', sanitize_text_field ( $_POST ['assignment-maximum-mark'] ) );
				} else {
					delete_post_meta ( $post_id, 'assignment-maximum-mark' );
				}

				if( isset( $_POST ['assignment-pass-percentage'] ) && $_POST ['assignment-pass-percentage'] != '' ) {
					update_post_meta ( $post_id, 'assignment-pass-percentage', sanitize_text_field ( $_POST ['assignment-pass-percentage'] ) );
				} else {
					delete_post_meta ( $post_id, 'assignment-pass-percentage' );
				}

				if( isset( $_POST ['assignment-enable-textarea'] ) && $_POST ['assignment-enable-textarea'] != '' ) {
					update_post_meta ( $post_id, 'assignment-enable-textarea', sanitize_textarea_field ( $_POST ['assignment-enable-textarea'] ) );
				} else {
					delete_post_meta ( $post_id, 'assignment-enable-textarea' );
				}

				if( isset( $_POST ['assignment-enable-attachment'] ) && $_POST ['assignment-enable-attachment'] != '' ) {
					update_post_meta ( $post_id, 'assignment-enable-attachment', sanitize_text_field ( $_POST ['assignment-enable-attachment'] ) );
				} else {
					delete_post_meta ( $post_id, 'assignment-enable-attachment' );
				}

				if( isset( $_POST ['assignment-attachment-type'] ) && $_POST ['assignment-attachment-type'] != '' ) {
					update_post_meta ( $post_id, 'assignment-attachment-type',  sanitize_text_field( $_POST ['assignment-attachment-type'] ) );
				} else {
					delete_post_meta ( $post_id, 'assignment-attachment-type' );
				}

				if($_POST ['assignment-attachment-size'] > dtlms_get_upload_size()) {
					$attachment_size = 0;
				} else {
					$attachment_size = sanitize_text_field( $_POST ['assignment-attachment-size'] );
				}

				if( isset( $_POST ['assignment-attachment-size'] ) && $_POST ['assignment-attachment-size'] != '' ) {
					update_post_meta ( $post_id, 'assignment-attachment-size', $attachment_size );
				} else {
					delete_post_meta ( $post_id, 'assignment-attachment-size' );
				}

				if( isset( $_POST ['duration'] ) && $_POST ['duration'] != '') {
					update_post_meta ( $post_id, 'duration', sanitize_text_field ( $_POST ['duration'] ) );
				} else {
					delete_post_meta ( $post_id, 'duration' );
				}

				if( isset( $_POST ['duration-parameter'] ) && $_POST ['duration-parameter'] != '') {
					update_post_meta ( $post_id, 'duration-parameter', sanitize_text_field ( $_POST ['duration-parameter'] ) );
				} else {
					delete_post_meta ( $post_id, 'duration-parameter' );
				}

			endif;
		}

		function dtlms_cpt_items_update($cpts) {
			$cpts['assignments'] = esc_html__('Assignments', 'dtlms-lite');
			return $cpts;
		}

		function dtlms_admin_init() {
			add_action ( 'add_meta_boxes', array ( $this, 'dtlms_add_assignment_default_metabox' ) );
		}

		function dtlms_add_assignment_default_metabox() {
			add_meta_box ( 'dtlms-assignment-default-metabox', esc_html__('Assignment Options', 'dtlms-lite'), array ( $this, 'dtlms_assignment_default_metabox' ), 'dtlms_assignments', 'normal', 'default' );
		}

		function dtlms_assignment_default_metabox() {
			include_once DTLMS_ASSIGNMENT_PLUGIN_PATH . 'cpt/metaboxes/metabox.php';
		}

		function dtlms_template_include($template) {

			if (is_singular( 'dtlms_assignments' )) {
				$template = DTLMS_ASSIGNMENT_PLUGIN_PATH . 'cpt/templates/single-dtlms_assignments.php';
			}

			return $template;

		}

	}

	DTLMSAssignmentPostType::instance();
}
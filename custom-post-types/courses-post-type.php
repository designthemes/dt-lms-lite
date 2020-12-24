<?php

if( !class_exists('DTLMSCoursesPostType') ) {

	class DTLMSCoursesPostType {

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
			add_action ( 'transition_post_status', array ( $this, 'dtlms_first_time_post_publish'), 10, 3) ;

			add_filter ( 'dtlms_woo_purchase_cpt', array ( $this, 'dtlms_woo_purchase_cpt_update' ), 10, 1 );
			add_filter ( 'dtlms_course_curriculums', array ( $this, 'dtlms_course_curriculums_update'  ), 10, 1 );
			add_filter ( 'dtlms_cpt_items', array ( $this, 'dtlms_cpt_items_update'  ), 10, 2 );

			/* Taxomony custom fields */
			require_once DTLMS_PLUGIN_PATH . 'custom-post-types/taxonomy-custom-fields.php';

		}

		function createPostType() {

			$course_slug     = dtlms_option('permalink','course-slug');
			$course_slug     = (isset($course_slug) && !empty($course_slug)) ? trim($course_slug): 'courses';

			$course_cat_slug = dtlms_option('permalink','course-category-slug');
			$course_cat_slug = (isset($course_cat_slug) && !empty($course_cat_slug)) ? trim($course_cat_slug) : 'course-category';

			$labels = array (
				'name'               => esc_html__( 'Courses', 'dtlms-lite' ),
				'all_items'          => esc_html__( 'All Courses', 'dtlms-lite' ),
				'singular_name'      => esc_html__( 'Course', 'dtlms-lite' ),
				'add_new'            => esc_html__( 'Add New', 'dtlms-lite' ),
				'add_new_item'       => esc_html__( 'Add New Course', 'dtlms-lite' ),
				'edit_item'          => esc_html__( 'Edit Course', 'dtlms-lite' ),
				'new_item'           => esc_html__( 'New Course', 'dtlms-lite' ),
				'view_item'          => esc_html__( 'View Course', 'dtlms-lite' ),
				'search_items'       => esc_html__( 'Search Courses', 'dtlms-lite' ),
				'not_found'          => esc_html__( 'No Courses found', 'dtlms-lite' ),
				'not_found_in_trash' => esc_html__( 'No Courses found in Trash', 'dtlms-lite' ),
				'parent_item_colon'  => esc_html__( 'Parent Course: ', 'dtlms-lite' ),
				'menu_name'          => esc_html__( 'Courses', 'dtlms-lite' )
			);

			$args = array (
				'labels'       => $labels,
				'hierarchical' => false,
				'description'  => esc_html__( 'This is custom post type courses', 'dtlms-lite' ),
				'supports'     => array (
						'title',
						'editor',
						'excerpt',
						'author',
						'comments',
						'page-attributes',
						'thumbnail'
				),
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => 'dtlms-lite',
				'menu_position'       => 5,
				'show_in_nav_menus'   => true,
				'publicly_queryable'  => true,
				'exclude_from_search' => false,
				'has_archive'         => true,
				'query_var'           => true,
				'can_export'          => true,
				'rewrite'             => array ( 'slug' => $course_slug, 'hierarchical' => true, 'with_front' => false ),
				'capability_type'     => 'post'
			);

			register_post_type ( 'dtlms_courses', $args );

			register_taxonomy ( 'course_category', array ( 'dtlms_courses' ), array (
				'hierarchical' => true,
				'labels'       => array (
					'name'                  => esc_html__( 'Course Categories','dtlms-lite' ),
					'singular_name'         => esc_html__( 'Course Category','dtlms-lite' ),
					'search_items'          => esc_html__( 'Search Course Categories', 'dtlms-lite' ),
					'popular_items'         => esc_html__( 'Popular Course Categories', 'dtlms-lite' ),
					'all_items'             => esc_html__( 'All Course Categories', 'dtlms-lite' ),
					'parent_item'           => esc_html__( 'Parent Course Category', 'dtlms-lite' ),
					'parent_item_colon'     => esc_html__( 'Parent Course Category', 'dtlms-lite' ),
					'edit_item'             => esc_html__( 'Edit Course Category', 'dtlms-lite' ),
					'update_item'           => esc_html__( 'Update Course Category', 'dtlms-lite' ),
					'add_new_item'          => esc_html__( 'Add New Course Category', 'dtlms-lite' ),
					'new_item_name'         => esc_html__( 'New Course Category', 'dtlms-lite' ),
					'add_or_remove_items'   => esc_html__( 'Add or remove', 'dtlms-lite' ),
					'choose_from_most_used' => esc_html__( 'Choose from most used', 'dtlms-lite' ),
					'menu_name'             => esc_html__( 'Course Categories','dtlms-lite' ),
				),
				'show_admin_column' => true,
				'rewrite'           => array ( 'slug' => $course_cat_slug, 'hierarchical' => true, 'with_front' => false ),
				'query_var'         => true
			) );

		}

		function dtlms_save_post_meta($post_id) {

			if( key_exists ( '_inline_edit', $_POST )) :
				if ( wp_verify_nonce($_POST['_inline_edit'], 'inlineeditnonce')) return;
			endif;

			if( key_exists( 'dtlms_courses_meta_nonce',$_POST ) ) :
				if ( ! wp_verify_nonce( $_POST['dtlms_courses_meta_nonce'], 'dtlms_courses_nonce' ) ) return;
			endif;

			if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

			if (!current_user_can('edit_post', $post_id)) :
				return;
			endif;

			if ( (key_exists('post_type', $_POST)) && ('dtlms_courses' == $_POST['post_type']) ) :

				if(isset($_POST['page-layout']) && $_POST['page-layout'] != '') {
					update_post_meta($post_id, 'page-layout', dtlms_recursive_sanitize_text_field( $_POST['page-layout'] ));
				} else {
					delete_post_meta($post_id, 'page-layout' );
				}

				if( isset( $_POST ['course-curriculum'] ) && !empty($_POST ['course-curriculum'])) {
					update_post_meta ( $post_id, 'course-curriculum', array_unique( dtlms_recursive_sanitize_text_field( $_POST ['course-curriculum'] ) ) );
				} else {
					delete_post_meta ( $post_id, 'course-curriculum' );
				}

				if( isset( $_POST ['coinstructors'] ) && $_POST ['coinstructors'] != '') {
					update_post_meta ( $post_id, 'coinstructors', dtlms_recursive_sanitize_text_field ( $_POST ['coinstructors'] ) );
				} else {
					delete_post_meta ( $post_id, 'coinstructors' );
				}

				if( isset( $_POST ['featured-course'] ) && $_POST ['featured-course'] != '') {
					update_post_meta ( $post_id, 'featured-course', dtlms_recursive_sanitize_text_field ( $_POST ['featured-course'] ) );
				} else {
					delete_post_meta ( $post_id, 'featured-course' );
				}

				if( isset( $_POST ['socialshare-items'] ) && !empty($_POST ['socialshare-items'])) {
					update_post_meta ( $post_id, 'socialshare-items', array_filter ( dtlms_recursive_sanitize_text_field( $_POST ['socialshare-items'] ) ) );
				} else {
					delete_post_meta ( $post_id, 'socialshare-items' );
				}

				if( isset( $_POST ['show-related-course'] ) && $_POST ['show-related-course'] != '') {
					update_post_meta ( $post_id, 'show-related-course', dtlms_recursive_sanitize_text_field ( $_POST ['show-related-course'] ) );
				} else {
					delete_post_meta ( $post_id, 'show-related-course' );
				}

				if( isset( $_POST ['reference-url'] ) && $_POST ['reference-url'] != '') {
					update_post_meta ( $post_id, 'reference-url', dtlms_recursive_sanitize_text_field( $_POST ['reference-url'] ) );
				} else {
					delete_post_meta ( $post_id, 'reference-url' );
				}

				if( isset( $_POST ['media-attachment-urls'] ) && !empty($_POST ['media-attachment-urls'])) {
					update_post_meta ( $post_id, 'media-attachment-urls', dtlms_recursive_sanitize_text_field( $_POST ['media-attachment-urls'] ) );
				} else {
					delete_post_meta ( $post_id, 'media-attachment-urls' );
				}

				if( isset( $_POST ['media-attachment-ids'] ) && !empty($_POST ['media-attachment-ids'])) {
					update_post_meta ( $post_id, 'media-attachment-ids', dtlms_recursive_sanitize_text_field( $_POST ['media-attachment-ids'] ) );
				} else {
					delete_post_meta ( $post_id, 'media-attachment-ids' );
				}

				if( isset( $_POST ['media-attachment-titles'] ) && !empty($_POST ['media-attachment-titles'])) {
					update_post_meta ( $post_id, 'media-attachment-titles', dtlms_recursive_sanitize_text_field( $_POST ['media-attachment-titles'] ) );
				} else {
					delete_post_meta ( $post_id, 'media-attachment-titles' );
				}

				if( isset( $_POST ['media-attachment-icons'] ) && !empty($_POST ['media-attachment-icons'])) {
					update_post_meta ( $post_id, 'media-attachment-icons', dtlms_recursive_sanitize_text_field( $_POST ['media-attachment-icons'] ) );
				} else {
					delete_post_meta ( $post_id, 'media-attachment-icons' );
				}


				if( isset( $_POST ['course-start-date'] ) && $_POST ['course-start-date'] != '') {
					update_post_meta ( $post_id, 'course-start-date', dtlms_recursive_sanitize_text_field ( $_POST ['course-start-date'] ) );
					$coursestartdate_compare_format = date('Ymd', strtotime($_POST ['course-start-date']));
					update_post_meta ( $post_id, 'course-start-date-compare-format', $coursestartdate_compare_format );
				} else {
					delete_post_meta ( $post_id, 'course-start-date' );
					delete_post_meta ( $post_id, 'course-start-date-compare-format' );
				}

				if( isset( $_POST ['allowpurchases-before-course-startdate'] ) && $_POST ['allowpurchases-before-course-startdate'] != '') {
					update_post_meta ( $post_id, 'allowpurchases-before-course-startdate', dtlms_recursive_sanitize_text_field ( $_POST ['allowpurchases-before-course-startdate'] ) );
				} else {
					delete_post_meta ( $post_id, 'allowpurchases-before-course-startdate' );
				}

				if( isset( $_POST ['enable-sidebar'] ) && $_POST ['enable-sidebar'] != '') {
					update_post_meta ( $post_id, 'enable-sidebar', dtlms_recursive_sanitize_text_field ( $_POST ['enable-sidebar'] ) );
				} else {
					delete_post_meta ( $post_id, 'enable-sidebar' );
				}

				if( isset( $_POST ['sidebar-content-type'] ) && $_POST ['sidebar-content-type'] != '') {
					update_post_meta ( $post_id, 'sidebar-content-type', dtlms_recursive_sanitize_text_field ( $_POST ['sidebar-content-type'] ) );
				} else {
					delete_post_meta ( $post_id, 'sidebar-content-type' );
				}

				if( isset( $_POST ['sidebar-content'] ) && $_POST ['sidebar-content'] != '') {
					update_post_meta ( $post_id, 'sidebar-content', dtlms_recursive_sanitize_text_field ( $_POST ['sidebar-content'] ) );
				} else {
					delete_post_meta ( $post_id, 'sidebar-content' );
				}

				if( isset( $_POST ['sidebar-content-page'] ) && $_POST ['sidebar-content-page'] != '') {
					update_post_meta ( $post_id, 'sidebar-content-page', dtlms_recursive_sanitize_text_field ( $_POST ['sidebar-content-page'] ) );
				} else {
					delete_post_meta ( $post_id, 'sidebar-content-page' );
				}

				if( isset( $_POST ['capacity'] ) && $_POST ['capacity'] != '') {
					update_post_meta ( $post_id, 'capacity', dtlms_recursive_sanitize_text_field ( $_POST ['capacity'] ) );
				} else {
					delete_post_meta ( $post_id, 'capacity' );
				}

				if( isset( $_POST ['disable-purchases-over-capacity'] ) && $_POST ['disable-purchases-over-capacity'] != '') {
					update_post_meta ( $post_id, 'disable-purchases-over-capacity', dtlms_recursive_sanitize_text_field ( $_POST ['disable-purchases-over-capacity'] ) );
				} else {
					delete_post_meta ( $post_id, 'disable-purchases-over-capacity' );
				}


				if( isset( $_POST ['course-prerequisite'] ) && $_POST ['course-prerequisite'] != '') {
					update_post_meta ( $post_id, 'course-prerequisite', dtlms_recursive_sanitize_text_field ( $_POST ['course-prerequisite'] ) );
				} else {
					delete_post_meta ( $post_id, 'course-prerequisite' );
				}

				if( isset( $_POST ['allowpurchases-before-course-prerequisite'] ) && $_POST ['allowpurchases-before-course-prerequisite'] != '') {
					update_post_meta ( $post_id, 'allowpurchases-before-course-prerequisite', dtlms_recursive_sanitize_text_field ( $_POST ['allowpurchases-before-course-prerequisite'] ) );
				} else {
					delete_post_meta ( $post_id, 'allowpurchases-before-course-prerequisite' );
				}


				if( isset( $_POST ['drip-completionlock-switch'] ) && $_POST ['drip-completionlock-switch'] != '') {

					update_post_meta ( $post_id, 'drip-completionlock-switch',  dtlms_recursive_sanitize_text_field( $_POST ['drip-completionlock-switch'] ) );

					if( isset( $_POST ['drip-completionlock-switch'] ) && $_POST ['drip-completionlock-switch'] == 'completionlock') {

						if( isset( $_POST ['curriculum-completion-lock'] ) && $_POST ['curriculum-completion-lock'] != '') {
							update_post_meta ( $post_id, 'curriculum-completion-lock', dtlms_recursive_sanitize_text_field ( $_POST ['curriculum-completion-lock'] ) );
						} else {
							delete_post_meta ( $post_id, 'curriculum-completion-lock' );
						}

						if( isset( $_POST ['open-curriculum-on-submission'] ) && $_POST ['open-curriculum-on-submission'] != '') {
							update_post_meta ( $post_id, 'open-curriculum-on-submission', dtlms_recursive_sanitize_text_field ( $_POST ['open-curriculum-on-submission'] ) );
						} else {
							delete_post_meta ( $post_id, 'open-curriculum-on-submission' );
						}

						delete_post_meta ( $post_id, 'drip-feed' );
						delete_post_meta ( $post_id, 'drip-content-type' );
						delete_post_meta ( $post_id, 'drip-duration-type' );
						delete_post_meta ( $post_id, 'drip-duration' );
						delete_post_meta ( $post_id, 'drip-duration-parameter' );

					} else if( isset( $_POST ['drip-completionlock-switch'] ) && $_POST ['drip-completionlock-switch'] == 'dripfeed') {

						if( isset( $_POST ['drip-feed'] ) && $_POST ['drip-feed'] != '') {
							update_post_meta ( $post_id, 'drip-feed', dtlms_recursive_sanitize_text_field ( $_POST ['drip-feed'] ) );
						} else {
							delete_post_meta ( $post_id, 'drip-feed' );
						}

						if( isset( $_POST ['drip-content-type'] ) && $_POST ['drip-content-type'] != '') {
							update_post_meta ( $post_id, 'drip-content-type', dtlms_recursive_sanitize_text_field ( $_POST ['drip-content-type'] ) );
						} else {
							delete_post_meta ( $post_id, 'drip-content-type' );
						}

						if( isset( $_POST ['drip-duration-type'] ) && $_POST ['drip-duration-type'] != '') {
							update_post_meta ( $post_id, 'drip-duration-type', dtlms_recursive_sanitize_text_field ( $_POST ['drip-duration-type'] ) );
						} else {
							delete_post_meta ( $post_id, 'drip-duration-type' );
						}

						if( isset( $_POST ['drip-duration'] ) && $_POST ['drip-duration'] != '') {
							update_post_meta ( $post_id, 'drip-duration', dtlms_recursive_sanitize_text_field ( $_POST ['drip-duration'] ) );
						} else {
							delete_post_meta ( $post_id, 'drip-duration' );
						}

						if( isset( $_POST ['drip-duration-parameter'] ) && $_POST ['drip-duration-parameter'] != '') {
							update_post_meta ( $post_id, 'drip-duration-parameter', dtlms_recursive_sanitize_text_field ( $_POST ['drip-duration-parameter'] ) );
						} else {
							delete_post_meta ( $post_id, 'drip-duration-parameter' );
						}

						delete_post_meta ( $post_id, 'curriculum-completion-lock' );
						delete_post_meta ( $post_id, 'open-curriculum-on-submission' );

					}

				} else {
					delete_post_meta ( $post_id, 'drip-completionlock-switch' );
					delete_post_meta ( $post_id, 'dtlms-course-event-catid' );
				}


				// from side metobox
				if( isset( $_POST ['dtlms-course-event-catid'] ) && !empty($_POST ['dtlms-course-event-catid']) ) {
					update_post_meta ( $post_id, 'dtlms-course-event-catid',  dtlms_recursive_sanitize_text_field( $_POST ['dtlms-course-event-catid'] ) );
				} else {
					delete_post_meta ( $post_id, 'dtlms-course-event-catid' );
				}

				if( isset( $_POST ['dtlms-course-group-id'] ) && $_POST ['dtlms-course-group-id'] != '' ) {
					update_post_meta ( $post_id, 'dtlms-course-group-id',  dtlms_recursive_sanitize_text_field( $_POST ['dtlms-course-group-id'] ) );

					if ( class_exists( 'BuddyPress' ) ) {

						$author_id = get_post_field( 'post_author', $post_id );
						$course_group_id = dtlms_recursive_sanitize_text_field( $_POST ['dtlms-course-group-id'] );
						groups_join_group( $course_group_id, $author_id );
						$member = new BP_Groups_Member( $author_id, $course_group_id );
						$member->promote( 'admin' );

					}

				} else {
					delete_post_meta ( $post_id, 'dtlms-course-group-id' );
				}

				if( isset( $_POST ['course-video'] ) && $_POST ['course-video'] != '') {
					update_post_meta ( $post_id, 'course-video', dtlms_recursive_sanitize_text_field ( $_POST ['course-video'] ) );
				} else {
					delete_post_meta ( $post_id, 'course-video' );
				}

				if( isset( $_POST ['course-news'] ) && !empty($_POST ['course-news']) ) {
					update_post_meta ( $post_id, 'course-news',  dtlms_recursive_sanitize_text_field( $_POST ['course-news'] ) );
				} else {
					delete_post_meta ( $post_id, 'course-news' );
				}

				if( isset( $_POST ['dtlms-course-forum'] ) && $_POST ['dtlms-course-forum'] != '' ) {
					update_post_meta ( $post_id, 'dtlms-course-forum-id',  dtlms_recursive_sanitize_text_field( $_POST ['dtlms-course-forum']) );
				} else {
					delete_post_meta ( $post_id, 'dtlms-course-forum-id' );
				}

				if(!get_post_meta($post_id, 'purchased_users', true)) {
					update_post_meta($post_id, 'purchased_users', array ());
				}

				// Add or Update course items from modules
				do_action('dtlms_addorupdate_course_module', $_POST, $post_id);

			endif;

		}

		function dtlms_first_time_post_publish($new, $old, $post) {

			if ($new == 'publish' && $old != 'publish' && isset($post->post_type) && $post->post_type == 'dtlms_courses') {
				// Notification & Mail
				do_action('dtlms_poc_course_added', $post->ID, $post->post_author);
			}

		}

		function dtlms_woo_purchase_cpt_update($cpt) {
			array_push($cpt, 'dtlms_courses');
			return $cpt;
		}

		function dtlms_course_curriculums_update($curriculums) {

			$curriculums['dtlms_lessons'] = array (
				'singular_slug'        => 'lesson',
				'plural_slug'          => 'lessons',
				'singular_label'       => esc_html__('Lesson', 'dtlms-lite'),
				'plural_label'         => esc_html__('Lessons', 'dtlms-lite'),
				'post_type'            => 'dtlms_lessons',
				'grading_metabox_path' => DTLMS_PLUGIN_PATH . 'custom-post-types/metaboxes/grading/lesson.php'
			);

			return $curriculums;

		}

		function dtlms_cpt_items_update($cpts) {
			$cpts['courses'] = esc_html__('Courses', 'dtlms-lite');
			return $cpts;
		}

		function dtlms_admin_init() {

			add_action ( 'add_meta_boxes', array ( $this, 'dtlms_add_course_default_metabox' ) );
			add_action ( 'add_meta_boxes', array ( $this, 'dtlms_add_course_featured_video_metabox' ) );
			add_action ( 'add_meta_boxes', array ( $this, 'dtlms_add_news_metabox' ) );

			if(class_exists('Tribe__Events__Main')) {
				add_action ( 'add_meta_boxes', array ( $this, 'dtlms_add_events_calendar_metabox' ) );
			}

			if(class_exists('BuddyPress') && class_exists('BP_Groups_Group')) {
				add_action ( 'add_meta_boxes', array ( $this, 'dtlms_add_buddypress_group_metabox'  ) );
			}

			if(class_exists('bbPress')) {
				add_action ( 'add_meta_boxes', array ( $this, 'dtlms_add_bbpress_forum_metabox'  ) );
			}

			add_filter ( 'manage_dtlms_courses_posts_columns', array ( $this, 'set_custom_edit_dtlms_courses_columns' ) );
			add_action ( 'manage_dtlms_courses_posts_custom_column', array ( $this, 'custom_dtlms_courses_column' ), 10, 2 );

		}

		function dtlms_add_course_default_metabox() {
			add_meta_box ( 'dtlms-course-default-metabox', esc_html__( 'Courses Options', 'dtlms-lite' ), array ( $this, 'dtlms_course_default_metabox' ), 'dtlms_courses', 'normal', 'default' );
		}

		function dtlms_add_course_featured_video_metabox() {
			add_meta_box ( 'dtlms-course-featured-video-metabox', esc_html__( 'Featured Video', 'dtlms-lite' ), array ( $this, 'dtlms_course_featured_video_metabox' ), 'dtlms_courses', 'side', 'low' );
		}

		function dtlms_add_events_calendar_metabox() {
			add_meta_box ( 'dtlms-events-calendar-metabox', esc_html__( 'Course Events', 'dtlms-lite' ), array ( $this, 'dtlms_events_calendar_metabox' ), 'dtlms_courses', 'side', 'low' );
		}

		function dtlms_add_buddypress_group_metabox() {
			add_meta_box ( 'dtlms-buddypress-group-metabox', esc_html__( 'Course Group', 'dtlms-lite' ), array ( $this, 'dtlms_buddypress_group_metabox' ), 'dtlms_courses', 'side', 'low' );
		}

		function dtlms_add_news_metabox() {
			add_meta_box ( 'dtlms-news-metabox', esc_html__( 'Course News', 'dtlms-lite' ), array ( $this, 'dtlms_news_metabox' ), 'dtlms_courses', 'side', 'low' );
		}

		function dtlms_add_bbpress_forum_metabox() {
			add_meta_box ( 'dtlms-bbpress-forum-metabox', esc_html__( 'Course Forum', 'dtlms-lite' ), array ( $this, 'dtlms_bbpress_forum_metabox' ), 'dtlms_courses', 'side', 'low' );
		}


		function dtlms_course_default_metabox() {
			include_once DTLMS_PLUGIN_PATH . 'custom-post-types/metaboxes/course-default-metabox.php';
		}

		function dtlms_course_featured_video_metabox() {
			include_once DTLMS_PLUGIN_PATH . 'custom-post-types/metaboxes/course-featured-video-metabox.php';
		}

		function dtlms_events_calendar_metabox() {
			include_once DTLMS_PLUGIN_PATH . 'custom-post-types/metaboxes/course-events-calendar-metabox.php';
		}

		function dtlms_buddypress_group_metabox() {
			include_once DTLMS_PLUGIN_PATH . 'custom-post-types/metaboxes/course-buddypress-group-metabox.php';
		}

		function dtlms_bbpress_forum_metabox() {
			include_once DTLMS_PLUGIN_PATH . 'custom-post-types/metaboxes/course-bbpress-forum-metabox.php';
		}

		function dtlms_news_metabox() {
			include_once DTLMS_PLUGIN_PATH . 'custom-post-types/metaboxes/course-news-metabox.php';
		}

		function set_custom_edit_dtlms_courses_columns($columns) {

			$newcolumns = array (
				'cb'                       => '<input type = "checkbox"/>',
				'dtlms_course_thumb'       => esc_html__('Image', 'dtlms-lite'),
				'title'                    => esc_html__('Title', 'dtlms-lite'),
				'taxonomy-course_category' => 'Course Category',
				'date'                     => 'Date'
			);

			$columns = array_merge ( $newcolumns, $columns );
			return $columns;

		}

		function custom_dtlms_courses_column($columns, $id) {

			global $post;

			switch ($columns) {

				case 'dtlms_course_thumb':
					$image = wp_get_attachment_image(get_post_thumbnail_id($id), array (75,75));
					if( ! empty( $image ) ) {
						echo $image;
					} else {
						echo '<img src="'.esc_url( DTLMS_ASSIGNMENT_PLUGIN_URL . 'assets/images/75x75.png' ).'" alt="'.esc_attr( $id ).'" />';
					}
				break;

			}

		}

		function dtlms_template_include($template) {

			if (is_singular( 'dtlms_courses' )) {
				$template = DTLMS_PLUGIN_PATH . 'custom-post-types/templates/single-dtlms_courses.php';
			} elseif (is_tax ( 'course_category' )) {
				$template = DTLMS_PLUGIN_PATH . 'custom-post-types/templates/taxonomy-course_category.php';
			} elseif ( is_post_type_archive('dtlms_courses') ) {
				$template = DTLMS_PLUGIN_PATH . 'custom-post-types/templates/archive-dtlms_courses.php';
			}

			return $template;

		}

	}

	DTLMSCoursesPostType::instance();
}
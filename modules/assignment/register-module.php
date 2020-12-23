<?php

if (!class_exists ( 'DTLMSRegisterAssignmentModule' )) {

	class DTLMSRegisterAssignmentModule extends DTLMSCore {

		private $module_name;
		private $module_url;

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

			$this->dtlms_define_constants( 'DTLMS_ASSIGNMENT_PLUGIN_PATH', DTLMS_PLUGIN_PATH . 'modules/assignment/' );
			$this->dtlms_define_constants( 'DTLMS_ASSIGNMENT_PLUGIN_URL', DTLMS_PLUGIN_URL . 'modules/assignment/' );

			add_action ( 'admin_enqueue_scripts', array ( $this, 'dtlms_admin_enqueue_scripts' ), 110 );
			add_action ( 'wp_enqueue_scripts', array ( $this, 'dtlms_enqueue_scripts' ), 130 );
			add_filter ( 'dtlms_course_curriculums', array ( $this, 'dtlms_course_curriculums_update'  ), 10, 5 );
			add_filter ( 'dtlms_admin_menu_and_order', array ( $this, 'dtlms_admin_menu_and_order_update'  ), 25 );

			require_once DTLMS_ASSIGNMENT_PLUGIN_PATH . 'cpt/post-type.php';

			require_once DTLMS_ASSIGNMENT_PLUGIN_PATH . 'utils.php';

		}


		/**
		 * Backend Enqueue Scripts
		 */

		function dtlms_admin_enqueue_scripts() {

			$current_screen = get_current_screen();

			if($current_screen->id == 'dtlms_assignments') {

				// CSS
				wp_enqueue_style ( 'fontawesome' );
				wp_enqueue_style ( 'icon-moon' );
				wp_enqueue_style ( 'chosen' );
				wp_enqueue_style ( 'dtlms-backend' );
				wp_enqueue_style ( 'dtlms-common' );
				wp_enqueue_style ( 'dtlms-misc' );

				// JS
				wp_enqueue_script ( 'chosen' );
				wp_enqueue_script ( 'dtlms-common' );
				wp_enqueue_script ( 'dtlms-backend' );

			}

		}


		/**
		 * Frontend Enqueue Scripts
		 */

		function dtlms_enqueue_scripts() {

			$this->dtlms_register_dependent_files();
			$this->dtlms_enqueue_registered_files();

		}

		function dtlms_register_dependent_files() {

			wp_register_style ( 'dtlms-assignment-frontend', DTLMS_ASSIGNMENT_PLUGIN_URL . 'assets/assignment-frontend.css', array ( 'dtlms-frontend' ) );

			wp_register_script ( 'dtlms-assignment-frontend', DTLMS_ASSIGNMENT_PLUGIN_URL . 'assets/frontend.js', array ('jquery', 'dtlms-frontend'), false, true );
			wp_localize_script ( 'dtlms-assignment-frontend', 'lmsassignmentobject', array (
				'assignmentNotification'   => esc_html__('Please make sure required fields are filled.', 'dtlms-lite')
			));

		}

		function dtlms_enqueue_registered_files() {

			wp_enqueue_style ( 'dtlms-assignment-frontend' );
			wp_enqueue_script ( 'dtlms-assignment-frontend' );

		}

		/**
		 * Update Course Curriculums
		 */
		function dtlms_course_curriculums_update($curriculums) {

			$curriculums['dtlms_assignments'] = array (
				'singular_slug'        => 'assignment',
				'plural_slug'          => 'assignments',
				'singular_label'       => esc_html__('Assignment', 'dtlms-lite'),
				'plural_label'         => esc_html__('Assignments', 'dtlms-lite'),
				'post_type'            => 'dtlms_assignments',
				'grading_metabox_path' => DTLMS_ASSIGNMENT_PLUGIN_PATH.'cpt/metaboxes/grading.php'
			);

			return $curriculums;

		}


		/**
		 * Custom Admin Menu & Order Update
		 */

		function dtlms_admin_menu_and_order_update() {
			add_submenu_page( 'dtlms-lite', esc_html__('All Assignments', 'dtlms-lite'), esc_html__('All Assignments', 'dtlms-lite'), 'edit_posts', 'edit.php?post_type=dtlms_assignments' );
		}

	}

}

if( !function_exists('dtlmsAssignmentModule') ) {
	function dtlmsAssignmentModule() {
		return DTLMSRegisterAssignmentModule::instance();
	}
}

dtlmsAssignmentModule();
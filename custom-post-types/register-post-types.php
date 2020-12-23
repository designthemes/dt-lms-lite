<?php
if (!class_exists ( 'DTLMSCustomPostTypes' )) {

	class DTLMSCustomPostTypes {

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

			/* Courses Custom Post Type */
			require_once DTLMS_PLUGIN_PATH . 'custom-post-types/courses-post-type.php';

			/* Lesson Custom Post Type */
			require_once DTLMS_PLUGIN_PATH . 'custom-post-types/lessons-post-type.php';

			/* Packages Custom Post Type */
			require_once DTLMS_PLUGIN_PATH . 'custom-post-types/packages-post-type.php';
			if (class_exists ( 'DTLMSPackagesPostType' )) {
				new DTLMSPackagesPostType ();
			}

			/* Author Single Page */
			add_filter ( 'template_include', array ( $this, 'dtlms_template_include'  ) );

		}

		function dtlms_template_include($template) {

			if(is_author()) {
				$template = DTLMS_PLUGIN_PATH . 'custom-post-types/templates/single-author.php';
			}

			return $template;

		}

	}

	DTLMSCustomPostTypes::instance();

}
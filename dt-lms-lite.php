<?php
/*
 * Plugin Name:	DT LMS Lite
 * URI: 		http://wedesignthemes.com/plugins/designthemes-lms
 * Description: A simple wordpress plugin designed to implements <strong>LMS features of DesignThemes</strong>
 * Version: 	1.0
 * Author: 		DesignThemes
 * Text Domain: dtlms-lite
 * Author URI:	https://profiles.wordpress.org/designthemes/
 */

if (! class_exists ( 'DTLMSCore' )) {

	class DTLMSCore {

		/**
		 * Instance variable
		 */
		private static $_instance = null;

		/**
		 * Active Modules
		 */
		public $active_modules = array ();

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

		/**
		 * Constructor
		 */
		function __construct() {

			$this->dtlms_setup_constants();
			$this->dtlms_action_hooks();
			$this->dtlms_includes();
			$this->dtlms_load_modules();

			// Theme Support
			$this->dtlms_theme_support_includes();

		}

		/**
		 * Define constant if not already set.
		 */
		public function dtlms_define_constants( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}

		/**
		 * Configure Constants
		 */
		public function dtlms_setup_constants() {

			$this->dtlms_define_constants( 'DTLMS_PLUGIN_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
			$this->dtlms_define_constants( 'DTLMS_PLUGIN_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );

			$this->dtlms_define_constants( 'DTLMS_PLUGIN_NAME', esc_html__('DT LMS', 'dtlms-lite') );
			$this->dtlms_define_constants( 'DTLMS_PLUGIN_MODULE_PATH', DTLMS_PLUGIN_PATH.'modules' );

			$this->dtlms_define_constants( 'DTLMS_PB_MODULE_DEFAULT_TITLE', sprintf( esc_html__('%1$s - Default', 'dtlms-lite'), DTLMS_PLUGIN_NAME ) );
			$this->dtlms_define_constants( 'DTLMS_PB_MODULE_DASHBOARD_TITLE', sprintf( esc_html__('%1$s - Dashboard', 'dtlms-lite'), DTLMS_PLUGIN_NAME ) );

		}

		/**
		 * Action Hooks
		 */
		public function dtlms_action_hooks() {

			add_action ( 'init', array ( $this, 'dtlms_init' ), 100 );
			add_action ( 'plugins_loaded', array( $this, 'dtlms_plugins_loaded' ) );
			add_action ( 'bp_include', array ( $this, 'dtlms_activate_buddypress_dashboard' ) );

			add_action ( 'admin_menu', array ( $this, 'dtlms_configure_admin_menu' ), 10 );
			add_action ( 'parent_file', array ( $this, 'dtlms_change_active_menu' ) );

		}

		/**
		 * On Init
		 */
		function dtlms_init() {

			load_plugin_textdomain ( 'dtlms-lite', false, dirname ( plugin_basename ( __FILE__ ) ) . '/languages/' );

			// Register Dependent Styles & Scripts
			require_once DTLMS_PLUGIN_PATH . 'script-and-styles.php';

			// WooCommerce Payment Functionality
			if ( class_exists( 'WooCommerce' ) ) {
				require_once DTLMS_PLUGIN_PATH . '/woocommerce/woocommerce.php';
			}

		}

		/**
		 * Plugins Load
		 */
		function dtlms_plugins_loaded() {

			// Page Builders
			if( class_exists( 'Vc_Manager' ) || did_action( 'elementor/loaded' ) ) {

				// Scan and Include all available page builders
				if(is_dir(DTLMS_PLUGIN_PATH . 'page-builders')) {

					$dtlms_page_builders = scandir(DTLMS_PLUGIN_PATH . 'page-builders');
					$dtlms_page_builders = array_diff($dtlms_page_builders, array('..', '.'));

					if( class_exists( 'Vc_Manager' ) && in_array( 'visual-composer', $dtlms_page_builders ) ) {
						require_once  DTLMS_PLUGIN_PATH . 'page-builders/visual-composer/register-visual-composer.php';
					}

					if ( did_action( 'elementor/loaded' ) && in_array( 'elementor', $dtlms_page_builders ) ) {
						require_once DTLMS_PLUGIN_PATH . 'page-builders/elementor/register-elementor.php';
					}

				}
			} else {
				add_action ('admin_notices', array( $this, 'dtlms_pb_plugin_notice' ) );
				return;
			}

			// WooCommerce Dashboard
			if ( class_exists( 'WooCommerce' ) ) {
				require_once DTLMS_PLUGIN_PATH . '/dashboard/woocommerce.php';
			}

		}

		function dtlms_pb_plugin_notice() {

			echo '<div class="updated notice is-dismissible">';
				echo '<p>';
					echo sprintf(esc_html__('%1$s requires %2$s or %3$s plugin to be installed and activated on your site','dtlms-lite'), '<strong>'.DTLMS_PLUGIN_NAME.'</strong>', '<strong><a href="https://codecanyon.net/item/visual-composer-page-builder-for-wordpress/242431" target="_blank">'.esc_html__('Visual Composer', 'dtlms-lite').'</a></strong>', '<strong><a href="https://wordpress.org/plugins/elementor/" target="_blank">'.esc_html__('Elementor Page Builder', 'dtlms-lite').'</a></strong>' );
				echo '</p>';
				echo '<button type="button" class="notice-dismiss">';
					echo '<span class="screen-reader-text">'.esc_html__('Dismiss this notice.','dtlms-lite').'</span>';
				echo '</button>';
			echo '</div>';

		}

		/**
		 * BuddyPress Dashboard
		 */
		function dtlms_activate_buddypress_dashboard() {
			if ( class_exists( 'BuddyPress' ) ) {
				require_once DTLMS_PLUGIN_PATH . '/dashboard/buddypress.php';
			}
		}

		/**
		 * Configure admin menu
		 */
		function dtlms_configure_admin_menu() {

			add_menu_page( esc_html__('Learning Management System', 'dtlms-lite'), esc_html__('LMS', 'dtlms-lite'), 'edit_posts', 'dtlms-lite', 'dtlms_dashboard', 'dashicons-book', 6 );

			apply_filters( 'dtlms_admin_menu_and_order', array () );

			add_submenu_page( 'dtlms-lite', 'Course Category', 'Course Category', 'edit_posts', 'edit-tags.php?taxonomy=course_category&post_type=dtlms_courses' );
			add_submenu_page( 'dtlms-lite', 'Statistics', 'Statistics', 'edit_posts', 'dtlms-statistics-options', 'dtlms_statistics_options' );
			add_submenu_page( 'dtlms-lite', 'Settings', 'Settings', 'edit_posts', 'dtlms-settings-options', 'dtlms_settings_options' );

		}

		/**
		 * Update admin menu
		 */
		function dtlms_change_active_menu($parent_file) {

			global $submenu_file, $current_screen;

			$taxonomy = $current_screen->taxonomy;

			if ($taxonomy == 'course_category') {
				$submenu_file = 'edit-tags.php?taxonomy=course_category&post_type=dtlms_courses';
				$parent_file = 'dtlms-lite';
			}

			$id = $current_screen->id;

			if ($id == 'dtlms_lessons') {
				$submenu_file = 'edit.php?post_type=dtlms_lessons';
				$parent_file = 'dtlms-lite';
			}
			if ($id == 'dtlms_assignments') {
				$submenu_file = 'edit.php?post_type=dtlms_assignments';
				$parent_file = 'dtlms-lite';
			}
			if ($id == 'dtlms_classes') {
				$submenu_file = 'edit.php?post_type=dtlms_classes';
				$parent_file = 'dtlms-lite';
			}

			if ($id == 'dtlms_packages') {
				$submenu_file = 'edit.php?post_type=dtlms_packages';
				$parent_file = 'dtlms';
			}

			return $parent_file;

		}

		/**
		 * Action Hooks
		 */
		public function dtlms_includes() {

			// Register Custom Post Types
			require_once DTLMS_PLUGIN_PATH . 'custom-post-types/register-post-types.php';

			// Register Shortcodes
			require_once DTLMS_PLUGIN_PATH . '/shortcodes/shortcodes.php';
			if(class_exists('DTLMSShortcodes')){
				new DTLMSShortcodes();
			}

			require_once DTLMS_PLUGIN_PATH . '/utils/utils-admin.php';

			require_once DTLMS_PLUGIN_PATH . '/utils/utils.php';
			require_once DTLMS_PLUGIN_PATH . '/utils/utils-comment.php';
			require_once DTLMS_PLUGIN_PATH . '/utils/utils-core.php';
			require_once DTLMS_PLUGIN_PATH . '/utils/utils-courses.php';
			require_once DTLMS_PLUGIN_PATH . '/utils/utils-courses-listing-items.php';
			require_once DTLMS_PLUGIN_PATH . '/utils/utils-courses-single-items.php';
			require_once DTLMS_PLUGIN_PATH . '/utils/utils-lesson.php';
			require_once DTLMS_PLUGIN_PATH . '/utils/utils-packages.php';
			require_once DTLMS_PLUGIN_PATH . '/utils/utils-packages-items.php';
			require_once DTLMS_PLUGIN_PATH . '/utils/utils-register.php';
			require_once DTLMS_PLUGIN_PATH . '/utils/utils-social-login.php';
			require_once DTLMS_PLUGIN_PATH . '/utils/utils-common.php';
			require_once DTLMS_PLUGIN_PATH . '/utils/utils-backend.php';
			require_once DTLMS_PLUGIN_PATH . '/utils/utils-menu.php'; // Instructor & Student Menu
			require_once DTLMS_PLUGIN_PATH . '/utils/utils-woo-courses.php';
			require_once DTLMS_PLUGIN_PATH . '/utils/utils-woo-packages.php';

			// Settings
			require_once DTLMS_PLUGIN_PATH . '/settings/settings.php';

			// Statistics
			require_once DTLMS_PLUGIN_PATH . '/statistics/statistics.php';

			// Dashboard Functionality
			require_once DTLMS_PLUGIN_PATH . '/dashboard/dashboard-utils.php';

		}

		/**
		 * Scan & Include Active Modules
		 */
		function dtlms_load_modules() {

			if(is_dir(DTLMS_PLUGIN_MODULE_PATH)) {
				$dtlms_modules = scandir(DTLMS_PLUGIN_MODULE_PATH);
				$dtlms_modules = array_diff($dtlms_modules, array('..', '.'));

				if(is_array($dtlms_modules) && !empty($dtlms_modules)) {
					rsort($dtlms_modules); // To extend search module class in elementor
					$this->active_modules = $dtlms_modules;
					foreach($dtlms_modules as $dtlms_module) {
						$module_path = DTLMS_PLUGIN_MODULE_PATH . '/'.$dtlms_module.'/register-module.php';
						if(file_exists($module_path)) {
							require_once $module_path;
						}
					}
				}
			}

		}

		/**
		 * Theme support files include
		 */
		function dtlms_theme_support_includes() {
			switch ( get_template() ) {
				case 'src':
					include_once DTLMS_PLUGIN_PATH . '/theme-support/class-designthemes.php';
				break;
				case 'houzy':
					include_once DTLMS_PLUGIN_PATH . '/theme-support/class-designthemes-houzy.php';
				break;
				default:
					include_once DTLMS_PLUGIN_PATH . '/theme-support/class-default.php';
				break;
			}
		}

	}

}

if( !function_exists('dtlms_instance') ) {
	function dtlms_instance() {
		return DTLMSCore::instance();
	}
}

dtlms_instance();
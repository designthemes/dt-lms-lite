<?php
namespace DTElementor\widgets;

if (! class_exists ( 'DTLMSElementor' )) {

	class DTLMSElementor {

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

		/**
		 * Constructor
		 */
		function __construct() {

            add_action( 'elementor/elements/categories_registered', array( $this, 'dtlms_register_category' ) );
			add_action( 'elementor/widgets/widgets_registered', array( $this, 'dtlms_register_widgets' ) );

			add_action( 'elementor/preview/enqueue_styles', array( $this, 'dtlms_preview_styles') );

		}

		/**
		 * Register category
		 * Add plugin category in elementor
		 */
		public function dtlms_register_category( $elements_manager ) {

			$elements_manager->add_category(
				'dtlms-default-widgets',array(
					'title' => DTLMS_PB_MODULE_DEFAULT_TITLE,
					'icon'  => 'font'
				)
			);

			$elements_manager->add_category(
				'dtlms-dashboard-widgets',array(
					'title' => DTLMS_PB_MODULE_DASHBOARD_TITLE,
					'icon'  => 'font'
				)
			);
		}

		/**
		 * Parse Attributes
		 * Parse shortcode attributes
		 */
		public function dtlms_parse_shortcode_attrs( $attrs ) {

			$keys_to_filter = array ( 'animation_duration', 'hide_desktop', 'hide_tablet', 'hide_mobile', 'content' );

			$attrs_str = '';
			if(is_array($attrs) && !empty($attrs)) {
				foreach($attrs as $attr_key => $attr) {
					$first_character = substr($attr_key, 0, 1);
					if($first_character != '_' && !in_array($attr_key, $keys_to_filter)) {
						if(is_array($attr) && !empty($attr) && isset($attr['id'])) {
							$attrs_str .= $attr_key.'="'.$attr['id'].'" ';
						} else {
							$attrs_str .= $attr_key.'="'.$attr.'" ';
						}
					}
				}
			}

			return $attrs_str;

		}

		/**
		 * Register widgets
		 */
		public function dtlms_register_widgets( $widgets_manager ) {

			$elementor_modules_path = DTLMS_PLUGIN_PATH . 'page-builders/elementor/widgets/';

            # Default Widgets
				require $elementor_modules_path . 'default/class-login-logout-links.php';
				$widgets_manager->register_widget_type( new DTLMSDfLoginLogoutLinks() );

                require $elementor_modules_path . 'default/class-courses-listing.php';
                $widgets_manager->register_widget_type( new DTLMSDfCoursesListing() );

                require $elementor_modules_path . 'default/class-packages-listing.php';
                $widgets_manager->register_widget_type( new DTLMSDfPackagesListing() );

                require $elementor_modules_path . 'default/class-course-categories.php';
                $widgets_manager->register_widget_type( new DTLMSDfCourseCategories() );

                require $elementor_modules_path . 'default/class-instructor-listing.php';
                $widgets_manager->register_widget_type( new DTLMSDfInstructorListing() );

			# Dashboard Widgets
				require $elementor_modules_path . 'dashboard/class-package-details.php';
				$widgets_manager->register_widget_type( new DTLMSDbPackageDetails() );

				require $elementor_modules_path . 'dashboard/class-instructor-added-courses.php';
				$widgets_manager->register_widget_type( new DTLMSDbInstructorAddedCourses() );

				require $elementor_modules_path . 'dashboard/class-instructor-commission-earnings.php';
				$widgets_manager->register_widget_type( new DTLMSDbInstructorCommissionEarnings() );

				require $elementor_modules_path . 'dashboard/class-instructor-commissions.php';
				$widgets_manager->register_widget_type( new DTLMSDbInstructorCommissions() );

				require $elementor_modules_path . 'dashboard/class-instructor-courses.php';
				$widgets_manager->register_widget_type( new DTLMSDbInstructorCourses() );

				require $elementor_modules_path . 'dashboard/class-purchases-overview-chart.php';
				$widgets_manager->register_widget_type( new DTLMSDbPurchasesOverviewChart() );

				require $elementor_modules_path . 'dashboard/class-student-assigned-items.php';
				$widgets_manager->register_widget_type( new DTLMSDbStudentAssignedItems() );

				require $elementor_modules_path . 'dashboard/class-student-assigned-items-list.php';
				$widgets_manager->register_widget_type( new DTLMSDbStudentAssignedItemsList() );

				require $elementor_modules_path . 'dashboard/class-student-completed-items.php';
				$widgets_manager->register_widget_type( new DTLMSDbStudentCompletedItems() );

				require $elementor_modules_path . 'dashboard/class-student-completed-items-list.php';
				$widgets_manager->register_widget_type( new DTLMSDbStudentCompletedItemsList() );

				require $elementor_modules_path . 'dashboard/class-student-course-curriculum-details.php';
				$widgets_manager->register_widget_type( new DTLMSDbStudentCourseCurriculumDetails() );

				require $elementor_modules_path . 'dashboard/class-student-course-events.php';
				$widgets_manager->register_widget_type( new DTLMSDbStudentCourseEvents() );

				require $elementor_modules_path . 'dashboard/class-student-courses.php';
				$widgets_manager->register_widget_type( new DTLMSDbStudentCourses() );

				require $elementor_modules_path . 'dashboard/class-student-purchased-items.php';
				$widgets_manager->register_widget_type( new DTLMSDbStudentPurchasedItems() );

				require $elementor_modules_path . 'dashboard/class-student-purchased-items-list.php';
				$widgets_manager->register_widget_type( new DTLMSDbStudentPurchasedItemsList() );

				require $elementor_modules_path . 'dashboard/class-student-underevaluation-items.php';
				$widgets_manager->register_widget_type( new DTLMSDbStudentUnderevaluationItems() );

				require $elementor_modules_path . 'dashboard/class-student-underevaluation-items-list.php';
				$widgets_manager->register_widget_type( new DTLMSDbStudentUnderevaluationItemsList() );

				require $elementor_modules_path . 'dashboard/class-student-undergoing-items.php';
				$widgets_manager->register_widget_type( new DTLMSDbStudentUndergoingItems() );

				require $elementor_modules_path . 'dashboard/class-student-undergoing-items-list.php';
				$widgets_manager->register_widget_type( new DTLMSDbStudentUndergoingItemsList() );

				require $elementor_modules_path . 'dashboard/class-total-items.php';
				$widgets_manager->register_widget_type( new DTLMSDbTotalItems() );

				require $elementor_modules_path . 'dashboard/class-total-items-chart.php';
				$widgets_manager->register_widget_type( new DTLMSDbTotalItemChart() );

				# Load Modules Elementor widgets
					$dtlms_modules = dtlms_instance()->active_modules;
					if(is_array($dtlms_modules) && !empty($dtlms_modules)) {
						foreach($dtlms_modules as $dtlms_module) {

							$module_epb_path = DTLMS_PLUGIN_MODULE_PATH . '/'.$dtlms_module.'/page-builders/elementor/';
							$pb_files = glob($module_epb_path.'*.php');

							if(is_array($pb_files) && !empty($pb_files)) {
								foreach($pb_files as $pb_file) {

									$file_base_name = basename($pb_file, '.php');
									$file_base_name = explode('-', $file_base_name);

									require $pb_file;

									$class_name = implode('', array_map("ucfirst", $file_base_name));
									$class_name =  'DTElementor\Widgets\DTLMS'.$class_name;

									$widgets_manager->register_widget_type( new $class_name() );

								}
							}

						}
					}

        }

		/**
		 * Editor Preview Style
		 */
		public function dtlms_preview_styles() {
		}

	}

}

if( !function_exists('dtlms_elementor_instance') ) {
	function dtlms_elementor_instance() {
		return DTLMSElementor::instance();
	}
}

dtlms_elementor_instance();
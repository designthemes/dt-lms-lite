<?php
if( !class_exists('DTLMSVisualComposer') ) {

	class DTLMSVisualComposer {

		function __construct() {

			add_action ( 'wp_enqueue_scripts', array ( $this, 'dtlms_load_visual_composer_css' ) );
			add_action( 'admin_enqueue_scripts', array ( $this, 'dtlms_vc_admin_scripts') );
			add_action( 'after_setup_theme', array ( $this, 'dtlms_vc_map_shortcodes' ) , 1000 );

		}

		function dtlms_load_visual_composer_css() {
			if(is_singular('dtlms_classes') || is_singular('dtlms_courses')) {
			    wp_enqueue_script( 'wpb_composer_front_js' );
			    wp_enqueue_style( 'js_composer_front' );
			    wp_enqueue_style( 'js_composer_custom_css' );
			}
		}

		function dtlms_vc_admin_scripts( $hook ) {

			if($hook == "post.php" || $hook == "post-new.php") {
				wp_enqueue_style( 'dtlms-vc-admin', DTLMS_PLUGIN_URL .'visual-composer/admin.css', array(), false, 'all' );
			}

		}

		function dtlms_vc_map_shortcodes() {

			global $pagenow;

			$vc_modules_path = DTLMS_PLUGIN_PATH . 'page-builders/visual-composer/modules/';

			$modules = array ();

			$modules['dtlms_login_logout_links']                 = $vc_modules_path.'default/login-logout-links.php';
			$modules['dtlms_courses_listing']                    = $vc_modules_path.'default/courses-listing.php';
			$modules['dtlms_packages_listing']                   = $vc_modules_path.'default/packages-listing.php';
			$modules['dtlms_course_categories']                  = $vc_modules_path.'default/course-categories.php';
			$modules['dtlms_instructor_list']                    = $vc_modules_path.'default/instructor-list.php';

			$modules['dtlms_total_items']                        = $vc_modules_path.'dashboard/total-items.php';
			$modules['dtlms_total_items_chart']                  = $vc_modules_path.'dashboard/total-items-chart.php';
			$modules['dtlms_purchases_overview_chart']           = $vc_modules_path.'dashboard/purchases-overview-chart.php';
			$modules['dtlms_instructor_commission_earnings']     = $vc_modules_path.'dashboard/instructor-commission-earnings.php';

			$modules['dtlms_instructor_courses']                 = $vc_modules_path.'dashboard/instructor-courses.php';
			$modules['dtlms_instructor_added_courses']           = $vc_modules_path.'dashboard/instructor-added-courses.php';
			$modules['dtlms_instructor_commissions']             = $vc_modules_path.'dashboard/instructor-commissions.php';
			$modules['dtlms_student_courses']                    = $vc_modules_path.'dashboard/student-courses.php';
			$modules['dtlms_package_details']                    = $vc_modules_path.'dashboard/package-details.php';

			$modules['dtlms_student_purchased_items']            = $vc_modules_path.'dashboard/student-purchased-items.php';
			$modules['dtlms_student_assigned_items']             = $vc_modules_path.'dashboard/student-assigned-items.php';
			$modules['dtlms_student_undergoing_items']           = $vc_modules_path.'dashboard/student-undergoing-items.php';
			$modules['dtlms_student_underevaluation_items']      = $vc_modules_path.'dashboard/student-underevaluation-items.php';
			$modules['dtlms_student_completed_items']            = $vc_modules_path.'dashboard/student-completed-items.php';

			$modules['dtlms_student_purchased_items_list']       = $vc_modules_path.'dashboard/student-purchased-items-list.php';
			$modules['dtlms_student_assigned_items_list']        = $vc_modules_path.'dashboard/student-assigned-items-list.php';
			$modules['dtlms_student_undergoing_items_list']      = $vc_modules_path.'dashboard/student-undergoing-items-list.php';
			$modules['dtlms_student_underevaluation_items_list'] = $vc_modules_path.'dashboard/student-underevaluation-items-list.php';
			$modules['dtlms_student_completed_items_list']       = $vc_modules_path.'dashboard/student-completed-items-list.php';

			$modules['dtlms_student_course_curriculum_details']  = $vc_modules_path.'dashboard/student-course-curriculum-details.php';
			$modules['dtlms_student_course_events']              = $vc_modules_path.'dashboard/student-course-events.php';

			// Load Modules Visual Composer widgets

				$dtlms_modules = dtlms_instance()->active_modules;
				if(is_array($dtlms_modules) && !empty($dtlms_modules)) {
					foreach($dtlms_modules as $dtlms_module) {

						$module_epb_path = DTLMS_PLUGIN_MODULE_PATH . '/'.$dtlms_module.'/page-builders/visual-composer/';
						$pb_files = glob($module_epb_path.'*.php');

						if(is_array($pb_files) && !empty($pb_files)) {
							foreach($pb_files as $pb_file) {

								$file_base_name = basename($pb_file, '.php');

								$pb_file_slug = str_replace('df-', '', strtolower($file_base_name));
								$pb_file_slug = str_replace('db-', '', strtolower($pb_file_slug));
								$pb_file_slug = str_replace('-', '_', strtolower($pb_file_slug));
								$pb_file_slug = 'dtlms_'.$pb_file_slug;

								$modules[$pb_file_slug] = $pb_file;

							}
						}

					}
				}

			// Apply filters so you can easily modify the modules

				$modules = apply_filters( 'dtlms_vc_modules', $modules );

			// Load Modules

				if( !empty( $modules ) ){
					foreach ( $modules as $key => $val ) {
						require_once( $val );
					}
				}

		}

	}

}

if(class_exists('DTLMSVisualComposer')){
	new DTLMSVisualComposer();
}
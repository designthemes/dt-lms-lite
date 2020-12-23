<?php

if( !class_exists('DTLMSDependentFiles') ) {

	class DTLMSDependentFiles {

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

			add_action ( 'admin_enqueue_scripts', array ( $this, 'dtlms_admin_enqueue_scripts' ), 100 );
			add_action ( 'wp_enqueue_scripts', array ( $this, 'dtlms_enqueue_dependent_files' ), 120 );

			require_once DTLMS_PLUGIN_PATH . 'assets/css/skin.php';

		}

		/**
		 * Admin enqueue scripts
		 */
		function dtlms_admin_enqueue_scripts() {

			// Enqueue CSS files
			wp_register_style ( 'fontawesome', DTLMS_PLUGIN_URL . 'assets/css/all.min.css', array (), false, 'all' );
			wp_register_style ( 'icon-moon', DTLMS_PLUGIN_URL . 'assets/css/icon-moon.css', array (), false, 'all' );
			wp_register_style ( 'chosen', DTLMS_PLUGIN_URL . 'assets/css/chosen.css', array (), false, 'all' );
			wp_register_style ( 'jquery-ui', DTLMS_PLUGIN_URL . 'assets/css/jquery-ui.min.css', array (), false, 'all' );
			wp_register_style ( 'dtlms-backend', DTLMS_PLUGIN_URL . 'assets/css/backend.css', array (), false, 'all' );
			wp_register_style ( 'dtlms-common', DTLMS_PLUGIN_URL . 'assets/css/common.css', array (), false, 'all' );
			wp_register_style ( 'dtlms-misc', DTLMS_PLUGIN_URL . 'assets/css/misc.css', array (), false, 'all' );

			// Enqueue JS files
			wp_register_script ( 'wp-color-picker-alpha', DTLMS_PLUGIN_URL . 'assets/js/wp-color-picker-alpha.min.js', array (), false, true );
			wp_register_script ( 'chosen', DTLMS_PLUGIN_URL . 'assets/js/chosen.jquery.min.js', array (), false, true );
			wp_register_script ( 'dtlms-timepicker', DTLMS_PLUGIN_URL . 'assets/js/jquery-ui-timepicker-addon.js', array (), false, true );
			wp_register_script ( 'dtlms-chart', DTLMS_PLUGIN_URL . 'assets/js/chart.min.js', array (), false, false );
			wp_register_script ( 'dtlms-tabs', DTLMS_PLUGIN_URL . 'assets/js/jquery.tabs.min.js', array (), false, true );

			wp_register_script ( 'dtlms-common', DTLMS_PLUGIN_URL . 'assets/js/common.js', array (), false, true );
			wp_localize_script ( 'dtlms-common', 'lmscommonobject', array (
				'ajaxurl'  => admin_url('admin-ajax.php'),
				'noResult' => esc_html__('No Results Found!', 'dtlms-lite'),
			));

			wp_register_script ( 'dtlms-backend', DTLMS_PLUGIN_URL . 'assets/js/backend.js', array (), false, true );
			wp_localize_script ( 'dtlms-backend', 'lmsbackendobject', array (
				'ajaxurl'                     => admin_url('admin-ajax.php'),
				'revokeUserSubmission'        => esc_html__('User item submission have been revoked successfully.', 'dtlms-lite'),
				'revokeUserSubmissionWarning' => esc_html__('You can\'t revoke the item once it is graded.', 'dtlms-lite'),
				'gradingWarningTrash'         => esc_html__('If this item has child item(s), all item(s) will be moved to trash. If course is under "Curriculum Completion Lock", workflow will be breaked.', 'dtlms-lite'),
				'gradingWarningDelete'        => esc_html__('If this item has child item(s), all item(s) will be deleted permanently. If course is under "Curriculum Completion Lock", workflow will be breaked.', 'dtlms-lite'),
				'selectInstructor'            => esc_html__('Please select any instructor!', 'dtlms-lite'),
				'noResult'                    => esc_html__('No Results Found!', 'dtlms-lite'),
				'noGraph'                     => esc_html__('No enough data to generate graph!', 'dtlms-lite'),
				'onRefresh'                   => esc_html__('Refreshing this quiz page will mark this session as completed.', 'dtlms-lite'),
				'onRefreshCurriculum'         => esc_html__('Would you like to abort this quiz session, which will mark this session as completed ?.', 'dtlms-lite'),
				'locationAlert1'              => esc_html__('To get GPS location please fill address.', 'dtlms-lite'),
				'locationAlert2'              => esc_html__('Please add latitude and longitude', 'dtlms-lite'),
				'attachmentTitle'             => esc_html__('Attachment Title', 'dtlms-lite'),
				'attachmentIcon'              => esc_html__('Attachment Icon', 'dtlms-lite')
			));

			$googlemap_api_key = dtlms_option('general', 'googlemap-api-key');
			$googlemap_api_key = (isset($googlemap_api_key) && !empty($googlemap_api_key)) ? $googlemap_api_key : '';
			wp_register_script ( 'dtlms-google-map', 'https://maps.googleapis.com/maps/api/js?key='.$googlemap_api_key, array('jquery'), false, true );

			// Enqueue registered scripts
			$current_screen = get_current_screen();
			if($current_screen->id == 'dtlms_courses' || $current_screen->id == 'dtlms_lessons' || $current_screen->id == 'dtlms_gradings' || $current_screen->id == 'dtlms_packages' || $current_screen->id == 'dtlms_payments' || $current_screen->id == 'edit-course_category' || $current_screen->id == 'lms_page_dtlms-classregistrations-options' || $current_screen->id == 'lms_page_dtlms-statistics-options' || $current_screen->id == 'lms_page_dtlms-settings-options' || $current_screen->id == 'user-edit') {

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

			if($current_screen->id == 'dtlms_courses' || $current_screen->id == 'dtlms_gradings' || $current_screen->id == 'lms_page_dtlms-settings-options') {

				// CSS
				wp_enqueue_style ( 'jquery-ui' );

				// JS
				wp_enqueue_script ( 'jquery-ui-datepicker' );
				wp_enqueue_script ( 'dtlms-timepicker' );

			}

			if($current_screen->id == 'dtlms_courses') {

				wp_enqueue_script ( 'dtlms-tabs' );

			}

			if($current_screen->id == 'edit-course_category' || $current_screen->id == 'lms_page_dtlms-settings-options') {

				// CSS
				wp_enqueue_style ( 'wp-color-picker' );

				// JS
				wp_enqueue_script ( 'wp-color-picker' );
				wp_enqueue_script ( 'wp-color-picker-alpha', DTLMS_PLUGIN_URL . 'assets/js/wp-color-picker-alpha.min.js', array (), false, true );

			}

			if($current_screen->id == 'lms_page_dtlms-statistics-options') {

				// JS
				wp_enqueue_script ( 'dtlms-chart' );

			}

		}

		/**
		 * Frontend - Register CSS Files
		 */
		function dtlms_enqueue_dependent_files() {

			$this->dtlms_register_css_files();
			$this->dtlms_register_js_files();
			$this->dtlms_enqueue_registered_files();

			// CSS
			if(is_rtl() || (isset($_REQUEST['rtl']) && $_REQUEST['rtl'] == 'yes')) {
				wp_enqueue_style ( 'dtlms-rtl' );
			}

		}

		/**
		 * Frontend - Register CSS Files
		 */
		function dtlms_register_css_files() {

			wp_register_style ( 'fontawesome', DTLMS_PLUGIN_URL . 'assets/css/all.min.css' );
			wp_register_style ( 'icon-moon', DTLMS_PLUGIN_URL . 'assets/css/icon-moon.css' );
			wp_register_style ( 'swiper', DTLMS_PLUGIN_URL . 'assets/css/swiper.min.css' );
			wp_register_style ( 'jquery-ui', DTLMS_PLUGIN_URL . 'assets/css/jquery-ui.min.css' );
			wp_register_style ( 'chosen', DTLMS_PLUGIN_URL . 'assets/css/chosen.css' );
			wp_register_style ( 'scrolltabs', DTLMS_PLUGIN_URL . 'assets/css/scrolltabs.css' );

			wp_register_style ( 'dtlms-common', DTLMS_PLUGIN_URL . 'assets/css/common.css' );
			wp_register_style ( 'dtlms-frontend', DTLMS_PLUGIN_URL . 'assets/css/frontend.css', array ( 'fontawesome', 'icon-moon', 'dtlms-common' ) );

			wp_register_style ( 'dtlms-gridlist', DTLMS_PLUGIN_URL . 'assets/css/gridlist-items.css' );
			wp_register_style ( 'dtlms-single', DTLMS_PLUGIN_URL . 'assets/css/single-items.css' );

			wp_register_style ( 'dtlms-theme-default', DTLMS_PLUGIN_URL . 'assets/css/themes/default.css' );

			wp_register_style ( 'dtlms-google-fonts', $this->dtlms_load_fonts_url() );
			wp_register_style ( 'dtlms-rtl', 	DTLMS_PLUGIN_URL . 'assets/css/rtl.css' );

		}

		/**
		 * Frontend - Register JS Files
		 */
		function dtlms_register_js_files() {

			$elementor_preview_mode = false;
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			if (is_plugin_active('elementor/elementor.php') || is_plugin_active_for_network('elementor/elementor.php')) {  // Elementor Plugin

				if ( \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
					$elementor_preview_mode = true;
				}

			}

			$primary_color              = dtlms_skin_option('primary-color');
			$secondary_color            = dtlms_skin_option('secondary-color');
			$tertiary_color             = dtlms_skin_option('tertiary-color');

			$primary_alternate_color    = dtlms_skin_option('primary-alternate-color');
			$secondary_alternate_color  = dtlms_skin_option('secondary-alternate-color');
			$tertiary_alternate_color   = dtlms_skin_option('tertiary-alternate-color');

			wp_register_script ( 'donutchart', DTLMS_PLUGIN_URL . 'assets/js/jquery.donutchart.js', array (), false, true );
			wp_register_script ( 'chosen', DTLMS_PLUGIN_URL . 'assets/js/chosen.jquery.min.js', array (), false, true );
			wp_register_script ( 'dtlms-knob', DTLMS_PLUGIN_URL . 'assets/js/jquery.knob.js', array (), false, true );
			wp_register_script ( 'dtlms-knob-custom', DTLMS_PLUGIN_URL . 'assets/js/jquery.knob.custom.js', array (), false, true );
			wp_register_script ( 'dtlms-print', DTLMS_PLUGIN_URL . 'assets/js/jquery.print.js', array (), false, true );
			wp_register_script ( 'nicescroll', DTLMS_PLUGIN_URL . 'assets/js/jquery.nicescroll.min.js', array (), false, true );
			wp_register_script ( 'dtlms-tabs', DTLMS_PLUGIN_URL . 'assets/js/jquery.tabs.min.js', array (), false, true );
			wp_register_script ( 'inview', DTLMS_PLUGIN_URL . 'assets/js/jquery.inview.js', array (), false, true );
			wp_register_script ( 'swiper', DTLMS_PLUGIN_URL . 'assets/js/swiper.min.js', array (), false, true );
			wp_register_script ( 'dtlms-chart', DTLMS_PLUGIN_URL . 'assets/js/chart.min.js', array (), false, false );
			wp_register_script ( 'sticky', DTLMS_PLUGIN_URL . 'assets/js/jquery.sticky.js', array (), false, true );
			wp_register_script ( 'downcount', DTLMS_PLUGIN_URL . 'assets/js/jquery.downCount.js', array (), false, true );
			wp_register_script ( 'isotope-3.0.5', DTLMS_PLUGIN_URL . 'assets/js/isotope.pkgd.min.js', array(), false, true);
			wp_register_script ( 'scrolltab', DTLMS_PLUGIN_URL . 'assets/js/jquery.scrolltabs.js', array (), false, true );
			wp_register_script ( 'dtlms-login-logout', DTLMS_PLUGIN_URL . 'assets/js/login-logout.js', array (), false, true );

			wp_register_script ( 'dtlms-common', DTLMS_PLUGIN_URL . 'assets/js/common.js', array (), false, true );
			wp_localize_script ( 'dtlms-common', 'lmscommonobject', array (
				'ajaxurl'              => esc_js(admin_url('admin-ajax.php')),
				'noResult'             => esc_html__('No Results Found!', 'dtlms-lite'),
				'elementorPreviewMode' => esc_js($elementor_preview_mode),
			));

			wp_register_script ( 'dtlms-frontend', DTLMS_PLUGIN_URL . 'assets/js/frontend.js', array (), false, true );
			wp_localize_script ( 'dtlms-frontend', 'lmsfrontendobject', array (
				'ajaxurl'                  => esc_js(admin_url('admin-ajax.php')),
				'noGraph'                  => esc_html__('No enough data to generate graph!', 'dtlms-lite'),
				'onRefreshCurriculum'      => esc_html__('Would you like to abort this quiz session, which will mark this session as completed ?.', 'dtlms-lite'),
				'locationAlert1'           => esc_html__('To get GPS location please fill address.', 'dtlms-lite'),
				'locationAlert2'           => esc_html__('Please add latitude and longitude', 'dtlms-lite'),
				'submitCourse'             => esc_html__('You can submit course only when you have completed all items in course.', 'dtlms-lite'),
				'submitClass'              => esc_html__('You can submit class only when you have submitted all courses.', 'dtlms-lite'),
				'confirmRegistration'      => esc_html__('Please confirm your registration to this class!', 'dtlms-lite'),
				'closedRegistration'       => esc_html__('Regsitration Closed', 'dtlms-lite'),
				'primarColor'              => esc_js( $primary_color ),
				'elementorPreviewMode'     => esc_js($elementor_preview_mode),
			));

			$googlemap_api_key = dtlms_option('general', 'googlemap-api-key');
			$googlemap_api_key = (isset($googlemap_api_key) && !empty($googlemap_api_key)) ? $googlemap_api_key : '';
			wp_register_script ( 'dtlms-google-map', 'https://maps.googleapis.com/maps/api/js?key='.$googlemap_api_key, array('jquery'), false, true );

		}

		/**
		 * Frontend - Enqueue Registered Files
		 */
		function dtlms_enqueue_registered_files() {

			// CSS
				wp_enqueue_style ( 'swiper' );
				wp_enqueue_style ( 'jquery-ui' );
				wp_enqueue_style ( 'chosen' );
				wp_enqueue_style ( 'scrolltabs' );
				wp_enqueue_style ( 'dtlms-frontend' );
				wp_enqueue_style ( 'dtlms-gridlist' );
				wp_enqueue_style ( 'dtlms-single' );
				wp_enqueue_style ( 'dtlms-google-fonts' );
				wp_enqueue_style ( 'dtlms-theme-default' );

			// JS
				wp_enqueue_script ( 'jquery-ui-sortable' );
				wp_enqueue_script ( 'jquery-ui-datepicker' );
				wp_enqueue_script ( 'donutchart' );
				wp_enqueue_script ( 'chosen' );
				wp_enqueue_script ( 'dtlms-knob' );
				wp_enqueue_script ( 'dtlms-knob-custom' );
				wp_enqueue_script ( 'dtlms-print' );
				wp_enqueue_script ( 'nicescroll' );
				wp_enqueue_script ( 'dtlms-tabs' );
				wp_enqueue_script ( 'inview' );
				wp_enqueue_script ( 'swiper' );
				wp_enqueue_script ( 'dtlms-chart' );
				wp_enqueue_script ( 'sticky' );
				wp_enqueue_script ( 'downcount' );
				wp_enqueue_script ( 'isotope-3.0.5' );
				wp_enqueue_script ( 'scrolltab' );
				wp_enqueue_script ( 'dtlms-login-logout' );
				wp_enqueue_script ( 'dtlms-common' );
				wp_enqueue_script ( 'dtlms-frontend' );

		}

		/**
		 * Load Google Fonts
		 */
		function dtlms_load_fonts_url() {

			$font_url = '';

			/*
			Translators: If there are characters in your language that are not supported
			by chosen font(s), translate this to 'off'. Do not translate into your own language.
			*/
			if ( 'off' !== _x( 'on', 'Google font: on or off', 'dtlms-lite' ) ) {

				// Default fonts used by theme
				$font_families = array ();

				// Fonts chosen by user
				$google_fonts = dtlms_fonts();
				$google_fonts = $google_fonts['all'];

				$fonts = array();
				$font_opts = dtlms_option('typography');
				$fonts['title'] = !empty ( $font_opts['title-font-family'] ) ? $font_opts['title-font-family'] : 'Poppins';


				$selected_fonts = array_intersect($fonts, $google_fonts);
				foreach($selected_fonts as $selected_font) {
					$font_families[] = $selected_font;
				}

				$query_args = array(
					'family' => urlencode( implode( '|', $font_families ) ),
				);

				$font_url = add_query_arg( $query_args, 'http'. dtlms_ssl() .'://fonts.googleapis.com/css' );

			}

			return $font_url;

		}

	}

}

if( !function_exists('dtlms_dependent_files_instance') ) {
	function dtlms_dependent_files_instance() {
		return DTLMSDependentFiles::instance();
	}
}

dtlms_dependent_files_instance();
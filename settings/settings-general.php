<?php
function dtlms_settings_general_content() {

	$class_title_singular = apply_filters( 'class_label', 'singular' );

	$tabs = array (
		'general_general' => array (
			'label'    => esc_html__('General', 'dtlms-lite'),
			'callback' => 'dtlms_get_general_settings_content'
		),
		'general_course'  => array (
			'label'    => esc_html__('Course', 'dtlms-lite'),
			'callback' => 'dtlms_get_course_settings_content'
		),
		'general_login' => array (
			'label'    => esc_html__('Login', 'dtlms-lite'),
			'callback' => 'dtlms_get_login_settings_content'
		),
		'general_permalink' => array (
			'label'    => esc_html__('Permalink', 'dtlms-lite'),
			'callback' => 'dtlms_get_permalink_settings_content'
		),
		'general_chart'  => array (
			'label'    => esc_html__('Chart', 'dtlms-lite'),
			'callback' => 'dtlms_get_chart_settings_content'
		)
	);

	$tabs = apply_filters( 'dtlms_general_settings', $tabs );

	$current = isset( $_GET['tab'] ) ? dtlms_recursive_sanitize_text_field( $_GET['tab'] ) : 'general_general';

	dtlms_get_general_settings_submenus($current, $tabs);
	dtlms_get_general_settings_tab($current, $tabs);
}

function dtlms_get_general_settings_submenus($current, $tabs) {

    echo '<h2 class="dtlms-custom-nav nav-tab-wrapper">';
		foreach( $tabs as $key => $tab ) {
			$class = ( $key == $current ) ? 'nav-tab-active' : '';
			echo '<a class="nav-tab '.esc_attr( $class ).'" href="?page=dtlms-settings-options&parenttab=general&tab='.esc_attr( $key ).'">'.esc_html( $tab['label'] ).'</a>';
		}
    echo '</h2>';

}

function dtlms_get_general_settings_tab($current, $tabs) {
	echo '<div class="dtlms-settings-general-container">';
		echo call_user_func($tabs[$current]['callback']);
	echo '</div>';
}

dtlms_settings_general_content();


function dtlms_get_general_settings_content() {

	$output = '';

	$instructor_singular_label = apply_filters( 'instructor_label', 'singular' );
	$instructor_plural_label   = apply_filters( 'instructor_label', 'plural' );

	$class_singular_label = apply_filters( 'class_label', 'singular' );
	$class_plural_label   = apply_filters( 'class_label', 'plural' );

	$output .= '<form name="formOptionSettings" class="formOptionSettings" method="post">';

		$output .= '<div class="dtlms-settings-options-holder">';
			$output .= '<div class="dtlms-column dtlms-one-fifth first">';
				$output .= '<label>'.sprintf( esc_html__( 'Allow %1$s to set commission for their courses & %2$s', 'dtlms-lite' ), $instructor_plural_label, strtolower($class_plural_label) ).'</label>';
			$output .= '</div>';
			$output .= '<div class="dtlms-column dtlms-four-fifth">';
                $checked = ( 'true' ==  dtlms_option('general','allow-instructor-setcommission') ) ? ' checked="checked"' : '';
                $switchclass = ( 'true' ==  dtlms_option('general','allow-instructor-setcommission') ) ? 'checkbox-switch-on' :'checkbox-switch-off';
	            $output .= '<div data-for="allow-instructor-setcommission" class="dtlms-checkbox-switch '.esc_attr( $switchclass ).'"></div>';
	            $output .= '<input id="allow-instructor-setcommission" class="hidden" type="checkbox" name="dtlms[general][allow-instructor-setcommission]" value="true" '.esc_attr( $checked ).' />';
	            $output .= '<p class="dtlms-note">'.sprintf( esc_html__( 'YES! to allow %1$s to set commission for their courses & %2$s', 'dtlms-lite' ), $instructor_plural_label, strtolower($class_plural_label) ).'</p>';
			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="dtlms-settings-options-holder">';
			$output .= '<div class="dtlms-column dtlms-one-fifth first">';
				$output .= '<label>'.sprintf( esc_html__( '%1$s Singular Label', 'dtlms-lite' ), $instructor_singular_label ).'</label>';
			$output .= '</div>';
			$output .= '<div class="dtlms-column dtlms-four-fifth">';
	            $output .= '<input id="instructor-singular-label" name="dtlms[general][instructor-singular-label]" type="text" value="'.esc_attr( $instructor_singular_label ).'" />';
	            $output .= '<p class="dtlms-note">'.esc_html__('You can replace the "Instructor" label as per your requirement.', 'dtlms-lite').'</p>';
			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="dtlms-settings-options-holder">';
			$output .= '<div class="dtlms-column dtlms-one-fifth first">';
				$output .= '<label>'.sprintf( esc_html__( '%1$s Plural Label', 'dtlms-lite' ), $instructor_plural_label ).'</label>';
			$output .= '</div>';
			$output .= '<div class="dtlms-column dtlms-four-fifth">';
	            $output .= '<input id="instructor-plural-label" name="dtlms[general][instructor-plural-label]" type="text" value="'.esc_attr( $instructor_plural_label ).'" />';
	            $output .= '<p class="dtlms-note">'.esc_html__('You can replace the "Instructors" label as per your requirement.', 'dtlms-lite').'</p>';
			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="dtlms-settings-options-holder">';
			$output .= '<div class="dtlms-column dtlms-one-fifth first">';
				$output .= '<label>'.esc_html__('Administrator Dashboard Content', 'dtlms-lite').'</label>';
			$output .= '</div>';
			$output .= '<div class="dtlms-column dtlms-four-fifth">';

	            $admin_dashboard_page = dtlms_option('general','admin-dashboard-content');
	            $output .= '<select id="admin-dashboard-content" name="dtlms[general][admin-dashboard-content]">';
				$pages = get_pages();
				$output .= '<option value="">'.esc_html__('Default', 'dtlms-lite').'</option>';
				foreach ( $pages as $page ) {
					$output .= '<option value="'.esc_attr( $page->ID ).'" '.selected( $page->ID, $admin_dashboard_page, false ).'>';
					$output .= esc_html( $page->post_title );
					$output .= '</option>';
				}
				$output .= '</select>';
	            $output .= '<p class="dtlms-note">'.esc_html__('You can choose dashboard page content for administrator here. This dashboard content is used for BuddyPress and WooCommerce dashboard pages.', 'dtlms-lite').'</p>';

			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="dtlms-settings-options-holder">';
			$output .= '<div class="dtlms-column dtlms-one-fifth first">';
				$output .= '<label>'.sprintf( esc_html__( '%1$s  Dashboard Content', 'dtlms-lite' ), $instructor_singular_label ).'</label>';
			$output .= '</div>';
			$output .= '<div class="dtlms-column dtlms-four-fifth">';

	            $instructor_dashboard_page = dtlms_option('general','instructor-dashboard-content');
	            $output .= '<select id="instructor-dashboard-content" name="dtlms[general][instructor-dashboard-content]">';
				$pages = get_pages();
				$output .= '<option value="">'.esc_html__('Default', 'dtlms-lite').'</option>';
				foreach ( $pages as $page ) {
					$output .= '<option value="'.esc_attr( $page->ID ).'" '.selected( $page->ID, $instructor_dashboard_page, false ).'>';
					$output .= esc_html( $page->post_title );
					$output .= '</option>';
				}
				$output .= '</select>';
	            $output .= '<p class="dtlms-note">'.sprintf( esc_html__( 'You can choose dashboard page content for %1$s here. This dashboard content is used for BuddyPress and WooCommerce dashboard pages.', 'dtlms-lite' ), $instructor_singular_label ).'</p>';

			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="dtlms-settings-options-holder">';
			$output .= '<div class="dtlms-column dtlms-one-fifth first">';
				$output .= '<label>'.esc_html__('Student Dashboard Content', 'dtlms-lite').'</label>';
			$output .= '</div>';
			$output .= '<div class="dtlms-column dtlms-four-fifth">';

				$student_dashboard_page = dtlms_option('general','student-dashboard-content');
	            $output .= '<select id="student-dashboard-content" name="dtlms[general][student-dashboard-content]">';
				$pages = get_pages();
				$output .= '<option value="">'.esc_html__('Default', 'dtlms-lite').'</option>';
				foreach ( $pages as $page ) {
					$output .= '<option value="'.esc_attr( $page->ID ).'" '.selected( $page->ID, $student_dashboard_page, false ).'>';
					$output .= esc_html( $page->post_title );
					$output .= '</option>';
				}
				$output .= '</select>';
	            $output .= '<p class="dtlms-note">'.esc_html__('You can choose dashboard page content for student here. This dashboard content is used for BuddyPress and WooCommerce dashboard pages.', 'dtlms-lite').'</p>';

			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="dtlms-settings-options-holder">';
			$output .= '<div class="dtlms-column dtlms-one-fifth first">';
				$output .= '<label>'.sprintf( esc_html__( 'Add %1$s role to administrator', 'dtlms-lite' ), $instructor_singular_label ).'</label>';
			$output .= '</div>';
			$output .= '<div class="dtlms-column dtlms-four-fifth">';
                $checked = ( 'true' ==  dtlms_option('general','add-instructor-roleto-admin') ) ? ' checked="checked"' : '';
                $switchclass = ( 'true' ==  dtlms_option('general','add-instructor-roleto-admin') ) ? 'checkbox-switch-on' :'checkbox-switch-off';
	            $output .= '<div data-for="add-instructor-roleto-admin" class="dtlms-checkbox-switch '.esc_attr( $switchclass ).'"></div>';
	            $output .= '<input id="add-instructor-roleto-admin" class="hidden" type="checkbox" name="dtlms[general][add-instructor-roleto-admin]" value="true" '.esc_attr( $checked ).' />';
	            $output .= '<p class="dtlms-note">'.sprintf( esc_html__( 'If you wish you can add %1$s role to administrator', 'dtlms-lite' ), $instructor_singular_label ).'</p>';
			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="dtlms-settings-options-holder">';
			$output .= '<div class="dtlms-column dtlms-one-fifth first">';
				$output .= '<label>'.esc_html__( 'Google Map - API Key', 'dtlms-lite' ).'</label>';
			$output .= '</div>';
			$output .= '<div class="dtlms-column dtlms-four-fifth">';
	            $googlemap_api_key = (dtlms_option('general','googlemap-api-key') != '') ? dtlms_option('general','googlemap-api-key') : '';
	            $output .= '<input id="googlemap-api-key" name="dtlms[general][googlemap-api-key]" type="text" value="'.esc_attr( $googlemap_api_key ).'" />';
	            $output .= '<p class="dtlms-note">'.esc_html__('Add your google map API key here..', 'dtlms-lite').'</p>';
			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="dtlms-settings-options-holder">';
			$output .= '<div class="dtlms-column dtlms-one-fifth first">';
				$output .= '<label>'.esc_html__( 'Backend - Post Per Page', 'dtlms-lite' ).'</label>';
			$output .= '</div>';
			$output .= '<div class="dtlms-column dtlms-four-fifth">';
	            $backend_postperpage = (dtlms_option('general','backend-postperpage') != '') ? dtlms_option('general','backend-postperpage') : 10;
	            $output .= '<input id="backend-postperpage" name="dtlms[general][backend-postperpage]" type="number" value="'.esc_attr( $backend_postperpage ).'" min="1" max="100" />';
	            $output .= '<p class="dtlms-note">'.esc_html__('Number of items to show in backend content listing, ex. statists,..', 'dtlms-lite').'</p>';
			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="dtlms-settings-options-holder">';
			$output .= '<div class="dtlms-column dtlms-one-fifth first">';
				$output .= '<label>'.esc_html__( 'Frontend - Post Per Page', 'dtlms-lite' ).'</label>';
			$output .= '</div>';
			$output .= '<div class="dtlms-column dtlms-four-fifth">';
	            $frontend_postperpage = (dtlms_option('general','frontend-postperpage') != '') ? dtlms_option('general','frontend-postperpage') : 10;
	            $output .= '<input id="frontend-postperpage" name="dtlms[general][frontend-postperpage]" type="number" value="'.esc_attr( $frontend_postperpage ).'" min="1" max="100" />';
	            $output .= '<p class="dtlms-note">'.sprintf( esc_html__( 'Number of items to show in frontend course and %1$s result popup..', 'dtlms-lite' ), strtolower($class_singular_label) ).'</p>';
			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="dtlms-settings-options-holder">';
			$output .= '<div class="dtlms-column dtlms-one-fifth first">';
				$output .= '<label>'.esc_html__( 'Progress Bar Color', 'dtlms-lite' ).'</label>';
			$output .= '</div>';
			$output .= '<div class="dtlms-column dtlms-four-fifth">';
	            $progressbar_color = (dtlms_option('general','progressbar-color') != '') ? dtlms_option('general','progressbar-color') : 'rgb(155, 189, 60)';
	            $output .= '<input id="progressbar-color" name="dtlms[general][progressbar-color]" class="dtlms-color-field color-picker" data-alpha="true" type="text" value="'.esc_attr( $progressbar_color ).'" />';
	            $output .= '<p class="dtlms-note">'.esc_html__('Choose progressbar color', 'dtlms-lite').'</p>';
			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="dtlms-settings-options-holder">';
			$output .= '<div class="dtlms-column dtlms-one-fifth first">';
				$output .= '<label>'.sprintf( esc_html__( 'Enable %1$s Menu', 'dtlms-lite' ), $instructor_singular_label ).'</label>';
			$output .= '</div>';
			$output .= '<div class="dtlms-column dtlms-four-fifth">';
                $checked = ( 'true' ==  dtlms_option('general', 'enable-instructor-menu') ) ? ' checked="checked"' : '';
                $switchclass = ( 'true' ==  dtlms_option('general', 'enable-instructor-menu') ) ? 'checkbox-switch-on' :'checkbox-switch-off';
	            $output .= '<div data-for="enable-instructor-menu" class="dtlms-checkbox-switch '.esc_attr( $switchclass ).'"></div>';
	            $output .= '<input id="enable-instructor-menu" class="hidden" type="checkbox" name="dtlms[general][enable-instructor-menu]" value="true" '.esc_attr( $checked ).' />';
	            $output .= '<p class="dtlms-note">'.sprintf( esc_html__( 'YES! to enable separate menu location for %1$s ', 'dtlms-lite' ), $instructor_plural_label ).'</p>';
			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="dtlms-settings-options-holder">';
			$output .= '<div class="dtlms-column dtlms-one-fifth first">';
				$output .= '<label>'.esc_html__( 'Enable Student Menu', 'dtlms-lite' ).'</label>';
			$output .= '</div>';
			$output .= '<div class="dtlms-column dtlms-four-fifth">';
                $checked = ( 'true' ==  dtlms_option('general', 'enable-student-menu') ) ? ' checked="checked"' : '';
                $switchclass = ( 'true' ==  dtlms_option('general', 'enable-student-menu') ) ? 'checkbox-switch-on' :'checkbox-switch-off';
	            $output .= '<div data-for="enable-student-menu" class="dtlms-checkbox-switch '.esc_attr( $switchclass ).'"></div>';
	            $output .= '<input id="enable-student-menu" class="hidden" type="checkbox" name="dtlms[general][enable-student-menu]" value="true" '.esc_attr( $checked ).' />';
	            $output .= '<p class="dtlms-note">'.esc_html__( 'YES! to enable separate menu location for Student ', 'dtlms-lite' ).'</p>';
			$output .= '</div>';
		$output .= '</div>';


		$output .= '<div class="dtlms-option-settings-response-holder"></div>';

		$output .= '<a href="#" class="dtlms-button dtlms-save-options-settings small" data-settings="general">'.esc_html__('Save Settings', 'dtlms-lite').'</a>';

	$output .= '</form>';

	return $output;

}

function dtlms_get_course_settings_content() {

	$instructor_singular_label = apply_filters( 'instructor_label', 'singular' );

	$output = '';

	$output .= '<form name="formOptionSettings" class="formOptionSettings" method="post">';

		$output .= '<div class="dtlms-settings-options-holder">';
			$output .= '<div class="dtlms-column dtlms-one-fifth first">';
				$output .= '<label>'.esc_html__( 'Enable Count Down Timer - Course Start Date', 'dtlms-lite' ).'</label>';
			$output .= '</div>';
			$output .= '<div class="dtlms-column dtlms-four-fifth">';
                $checked = ( 'true' ==  dtlms_option('course','enable-countdown-timer-course-startdate') ) ? ' checked="checked"' : '';
                $switchclass = ( 'true' ==  dtlms_option('course','enable-countdown-timer-course-startdate') ) ? 'checkbox-switch-on' :'checkbox-switch-off';
	            $output .= '<div data-for="enable-countdown-timer-course-startdate" class="dtlms-checkbox-switch '.esc_attr( $switchclass ).'"></div>';
	            $output .= '<input id="enable-countdown-timer-course-startdate" class="hidden" type="checkbox" name="dtlms[course][enable-countdown-timer-course-startdate]" value="true" '.esc_attr( $checked ).' />';
	            $output .= '<p class="dtlms-note">'.esc_html__('If you like to enable count down timer for course start date.', 'dtlms-lite').'</p>';
			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="dtlms-settings-options-holder">';
			$output .= '<div class="dtlms-column dtlms-one-fifth first">';
				$output .= '<label>'.sprintf( esc_html__( 'Enable Contact %1$s Options In Course Page', 'dtlms-lite' ), $instructor_singular_label ).'</label>';
			$output .= '</div>';
			$output .= '<div class="dtlms-column dtlms-four-fifth">';
                $checked = ( 'true' ==  dtlms_option('course','contact-instructor-in-coursepage') ) ? ' checked="checked"' : '';
                $switchclass = ( 'true' ==  dtlms_option('course','contact-instructor-in-coursepage') ) ? 'checkbox-switch-on' :'checkbox-switch-off';
	            $output .= '<div data-for="contact-instructor-in-coursepage" class="dtlms-checkbox-switch '.esc_attr( $switchclass ).'"></div>';
	            $output .= '<input id="contact-instructor-in-coursepage" class="hidden" type="checkbox" name="dtlms[course][contact-instructor-in-coursepage]" value="true" '.esc_attr( $checked ).' />';
	            $output .= '<p class="dtlms-note">'.sprintf( esc_html__( 'It allows student to send private message and email to %1$s', 'dtlms-lite' ), $instructor_singular_label ).'</p>';
			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="dtlms-settings-options-holder">';
			$output .= '<div class="dtlms-column dtlms-one-fifth first">';
				$output .= '<label>'.esc_html__( 'Course Prerequisite - On Complete', 'dtlms-lite' ).'</label>';
			$output .= '</div>';
			$output .= '<div class="dtlms-column dtlms-four-fifth">';
                $checked = ( 'true' ==  dtlms_option('course','course-prerequisite-on-complete') ) ? ' checked="checked"' : '';
                $switchclass = ( 'true' ==  dtlms_option('course','course-prerequisite-on-complete') ) ? 'checkbox-switch-on' :'checkbox-switch-off';
	            $output .= '<div data-for="course-prerequisite-on-complete" class="dtlms-checkbox-switch '.esc_attr( $switchclass ).'"></div>';
	            $output .= '<input id="course-prerequisite-on-complete" class="hidden" type="checkbox" name="dtlms[course][course-prerequisite-on-complete]" value="true" '.esc_attr( $checked ).' />';
	            $output .= '<p class="dtlms-note">'.esc_html__('Once this option is enabled, student will have to wait for prerequisite course to be evaluated. By default its enough if prerequisite course is submitted by student.', 'dtlms-lite').'</p>';
			$output .= '</div>';
		$output .= '</div>';


		$output .= '<div class="dtlms-settings-options-holder">';
			$output .= '<div class="dtlms-column dtlms-one-fifth first">';
				$output .= '<label>'.esc_html__('Curriculum Visibility', 'dtlms-lite').'</label>';
			$output .= '</div>';
			$output .= '<div class="dtlms-column dtlms-four-fifth">';

	            $course_curriculum_visiblitiy = dtlms_option('course', 'curriculum-visiblitiy');
	            $output .= '<select id="curriculum-visiblitiy" name="dtlms[course][curriculum-visiblitiy]">';
					$curriculum_visiblitiy_options = array (
						''                               => esc_html__('Everyone', 'dtlms-lite'),
						'logged-in-users'                => esc_html__('Logged In Users','dtlms-lite'),
						'purchased-users'                => esc_html__('Purchased Users','dtlms-lite'),
						'instructors-and-administrators' => esc_html__('Instructors And Administrators','dtlms-lite'),
					);
					foreach ( $curriculum_visiblitiy_options as $curriculum_visiblitiy_option_key => $curriculum_visiblitiy_option ) {
						$output .= '<option value="'.esc_attr( $curriculum_visiblitiy_option_key ).'" '.selected( $curriculum_visiblitiy_option_key, $course_curriculum_visiblitiy, false ).'>';
							$output .= esc_html( $curriculum_visiblitiy_option );
						$output .= '</option>';
					}
				$output .= '</select>';
	            $output .= '<p class="dtlms-note">'.esc_html__('You can set curriculum visiblity for your courses.', 'dtlms-lite').'</p>';

			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="dtlms-settings-options-holder">';
			$output .= '<div class="dtlms-column dtlms-one-fifth first">';
				$output .= '<label>'.esc_html__('Members Visibility', 'dtlms-lite').'</label>';
			$output .= '</div>';
			$output .= '<div class="dtlms-column dtlms-four-fifth">';

	            $course_members_visiblitiy = dtlms_option('course', 'members-visiblitiy');
	            $output .= '<select id="members-visiblitiy" name="dtlms[course][members-visiblitiy]">';
					$members_visiblitiy_options = array (
						''                               => esc_html__('Everyone', 'dtlms-lite'),
						'logged-in-users'                => esc_html__('Logged In Users','dtlms-lite'),
						'purchased-users'                => esc_html__('Purchased Users','dtlms-lite'),
						'instructors-and-administrators' => esc_html__('Instructors And Administrators','dtlms-lite'),
					);
					foreach ( $members_visiblitiy_options as $members_visiblitiy_option_key => $members_visiblitiy_option ) {
						$output .= '<option value="'.esc_attr( $members_visiblitiy_option_key ).'" '.selected( $members_visiblitiy_option_key, $course_members_visiblitiy, false ).'>';
							$output .= esc_html( $members_visiblitiy_option );
						$output .= '</option>';
					}
				$output .= '</select>';
	            $output .= '<p class="dtlms-note">'.esc_html__('You can set members visiblity for your courses.', 'dtlms-lite').'</p>';

			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="dtlms-settings-options-holder">';
			$output .= '<div class="dtlms-column dtlms-one-fifth first">';
				$output .= '<label>'.esc_html__('Events Visibility', 'dtlms-lite').'</label>';
			$output .= '</div>';
			$output .= '<div class="dtlms-column dtlms-four-fifth">';

	            $course_events_visiblitiy = dtlms_option('course', 'events-visiblitiy');
	            $output .= '<select id="events-visiblitiy" name="dtlms[course][events-visiblitiy]">';
					$events_visiblitiy_options = array (
						''                               => esc_html__('Everyone', 'dtlms-lite'),
						'logged-in-users'                => esc_html__('Logged In Users','dtlms-lite'),
						'purchased-users'                => esc_html__('Purchased Users','dtlms-lite'),
						'instructors-and-administrators' => esc_html__('Instructors And Administrators','dtlms-lite'),
					);

					foreach ( $events_visiblitiy_options as $events_visiblitiy_option_key => $events_visiblitiy_option ) {
						$output .= '<option value="'.esc_attr( $events_visiblitiy_option_key ).'" '.selected( $events_visiblitiy_option_key, $course_events_visiblitiy, false ).'>';
							$output .= esc_html( $events_visiblitiy_option );
						$output .= '</option>';
					}
				$output .= '</select>';
	            $output .= '<p class="dtlms-note">'.esc_html__('You can set events visiblity for your courses.', 'dtlms-lite').'</p>';

			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="dtlms-settings-options-holder">';
			$output .= '<div class="dtlms-column dtlms-one-fifth first">';
				$output .= '<label>'.esc_html__('BuddyPress Group Visibility', 'dtlms-lite').'</label>';
			$output .= '</div>';
			$output .= '<div class="dtlms-column dtlms-four-fifth">';

	            $course_buddypressgroup_visiblitiy = dtlms_option('course', 'buddypress-group-visiblitiy');
	            $output .= '<select id="buddypress-group-visiblitiy" name="dtlms[course][buddypress-group-visiblitiy]">';
					$buddypressgroup_visiblitiy_options = array (
						''                               => esc_html__('Everyone', 'dtlms-lite'),
						'logged-in-users'                => esc_html__('Logged In Users','dtlms-lite'),
						'purchased-users'                => esc_html__('Purchased Users','dtlms-lite'),
						'instructors-and-administrators' => esc_html__('Instructors And Administrators','dtlms-lite'),
					);
					foreach ( $buddypressgroup_visiblitiy_options as $buddypressgroup_visiblitiy_option_key => $buddypressgroup_visiblitiy_option ) {
						$output .= '<option value="'.esc_attr( $buddypressgroup_visiblitiy_option_key ).'" '.selected( $buddypressgroup_visiblitiy_option_key, $course_buddypressgroup_visiblitiy, false ).'>';
							$output .= esc_html( $buddypressgroup_visiblitiy_option );
						$output .= '</option>';
					}
				$output .= '</select>';
	            $output .= '<p class="dtlms-note">'.esc_html__('You can set buddypress group visiblity for your courses.', 'dtlms-lite').'</p>';

			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="dtlms-settings-options-holder">';
			$output .= '<div class="dtlms-column dtlms-one-fifth first">';
				$output .= '<label>'.esc_html__('News Visibility', 'dtlms-lite').'</label>';
			$output .= '</div>';
			$output .= '<div class="dtlms-column dtlms-four-fifth">';

	            $course_news_visiblitiy = dtlms_option('course', 'news-visiblitiy');
	            $output .= '<select id="news-visiblitiy" name="dtlms[course][news-visiblitiy]">';
					$news_visiblitiy_options = array (
						''                               => esc_html__('Everyone', 'dtlms-lite'),
						'logged-in-users'                => esc_html__('Logged In Users','dtlms-lite'),
						'purchased-users'                => esc_html__('Purchased Users','dtlms-lite'),
						'instructors-and-administrators' => esc_html__('Instructors And Administrators','dtlms-lite'),
					);
					foreach ( $news_visiblitiy_options as $news_visiblitiy_option_key => $news_visiblitiy_option ) {
						$output .= '<option value="'.esc_attr( $news_visiblitiy_option_key ).'" '.selected( $news_visiblitiy_option_key, $course_news_visiblitiy, false ).'>';
							$output .= esc_html( $news_visiblitiy_option );
						$output .= '</option>';
					}
				$output .= '</select>';
	            $output .= '<p class="dtlms-note">'.esc_html__('You can set news visiblity for your courses.', 'dtlms-lite').'</p>';

			$output .= '</div>';
		$output .= '</div>';


		$output .= '<div class="dtlms-option-settings-response-holder"></div>';

		$output .= '<a href="#" class="dtlms-button dtlms-save-options-settings small" data-settings="course">'.esc_html__('Save Settings', 'dtlms-lite').'</a>';

	$output .= '</form>';

	return $output;

}

function dtlms_get_login_settings_content() {

	$instructor_singular_label = apply_filters( 'instructor_label', 'singular' );

	$output = '';

	$output .= '<form name="formOptionSettings" class="formOptionSettings" method="post">';

		$output .= '<div class="dtlms-settings-options-holder">';
			$output .= '<div class="dtlms-column dtlms-one-fifth first">';
				$output .= '<label>'.esc_html__( 'Enable Facebook Login', 'dtlms-lite' ).'</label>';
			$output .= '</div>';
			$output .= '<div class="dtlms-column dtlms-four-fifth">';
                $checked = ( 'true' ==  dtlms_option('login','enable-facebook-login') ) ? ' checked="checked"' : '';
                $switchclass = ( 'true' ==  dtlms_option('login','enable-facebook-login') ) ? 'checkbox-switch-on' :'checkbox-switch-off';
	            $output .= '<div data-for="enable-facebook-login" class="dtlms-checkbox-switch '.esc_attr( $switchclass ).'"></div>';
	            $output .= '<input id="enable-facebook-login" class="hidden" type="checkbox" name="dtlms[login][enable-facebook-login]" value="true" '.esc_attr( $checked ).' />';
	            $output .= '<p class="dtlms-note">'.esc_html__('If you like to enable facebook login choose "Yes"', 'dtlms-lite').'</p>';
			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="dtlms-settings-options-holder">';
			$output .= '<div class="dtlms-column dtlms-one-fifth first">';
				$output .= '<label>'.esc_html__( 'App Id', 'dtlms-lite' ).'</label>';
			$output .= '</div>';
			$output .= '<div class="dtlms-column dtlms-four-fifth">';
	            $facebook_appid = (dtlms_option('login','facebook-appid') != '') ? dtlms_option('login','facebook-appid') : '';
	            $output .= '<input id="facebook-appid" name="dtlms[login][facebook-appid]" type="text" value="'.esc_attr( $facebook_appid ).'" />';
	            $output .= '<p class="dtlms-note">'.esc_html__('Add facebook App Id here..', 'dtlms-lite').'</p>';
			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="dtlms-settings-options-holder">';
			$output .= '<div class="dtlms-column dtlms-one-fifth first">';
				$output .= '<label>'.esc_html__( 'App Secret', 'dtlms-lite' ).'</label>';
			$output .= '</div>';
			$output .= '<div class="dtlms-column dtlms-four-fifth">';
	            $facebook_appsecret = (dtlms_option('login','facebook-appsecret') != '') ? dtlms_option('login','facebook-appsecret') : '';
	            $output .= '<input id="facebook-appsecret" name="dtlms[login][facebook-appsecret]" type="text" value="'.esc_attr( $facebook_appsecret ).'" />';
	            $output .= '<p class="dtlms-note">'.esc_html__('Add facebook App Secret here..', 'dtlms-lite').'</p>';
			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="dtlms-settings-options-holder">';
			$output .= '<div class="dtlms-column dtlms-one-fifth first">';
				$output .= '<label>'.esc_html__( 'Enable Google Login', 'dtlms-lite' ).'</label>';
			$output .= '</div>';
			$output .= '<div class="dtlms-column dtlms-four-fifth">';
                $checked = ( 'true' ==  dtlms_option('login','enable-google-login') ) ? ' checked="checked"' : '';
                $switchclass = ( 'true' ==  dtlms_option('login','enable-google-login') ) ? 'checkbox-switch-on' :'checkbox-switch-off';
	            $output .= '<div data-for="enable-google-login" class="dtlms-checkbox-switch '.esc_attr( $switchclass ).'"></div>';
	            $output .= '<input id="enable-google-login" class="hidden" type="checkbox" name="dtlms[login][enable-google-login]" value="true" '.esc_attr( $checked ).' />';
	            $output .= '<p class="dtlms-note">'.esc_html__('If you like to enable google login choose "Yes"', 'dtlms-lite').'</p>';
			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="dtlms-settings-options-holder">';
			$output .= '<div class="dtlms-column dtlms-one-fifth first">';
				$output .= '<label>'.esc_html__( 'Client Id', 'dtlms-lite' ).'</label>';
			$output .= '</div>';
			$output .= '<div class="dtlms-column dtlms-four-fifth">';
	            $google_clientid = (dtlms_option('login','google-clientid') != '') ? dtlms_option('login','google-clientid') : '';
	            $output .= '<input id="google-clientid" name="dtlms[login][google-clientid]" type="text" value="'.esc_attr( $google_clientid ).'" />';
	            $output .= '<p class="dtlms-note">'.esc_html__('Add google Client Id here..', 'dtlms-lite').'</p>';
			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="dtlms-settings-options-holder">';
			$output .= '<div class="dtlms-column dtlms-one-fifth first">';
				$output .= '<label>'.esc_html__( 'Client Secret', 'dtlms-lite' ).'</label>';
			$output .= '</div>';
			$output .= '<div class="dtlms-column dtlms-four-fifth">';
	            $google_clientsecret = (dtlms_option('login','google-clientsecret') != '') ? dtlms_option('login','google-clientsecret') : '';
	            $output .= '<input id="google-clientsecret" name="dtlms[login][google-clientsecret]" type="text" value="'.esc_attr( $google_clientsecret ).'" />';
	            $output .= '<p class="dtlms-note">'.esc_html__('Add google Client Secret here..', 'dtlms-lite').'</p>';
			$output .= '</div>';
		$output .= '</div>';


		$output .= '<div class="dtlms-settings-options-holder">';
			$output .= '<div class="dtlms-column dtlms-one-fifth first">';
				$output .= '<label>'.esc_html__( 'Administrator Login Redirect Page', 'dtlms-lite' ).'</label>';
			$output .= '</div>';
			$output .= '<div class="dtlms-column dtlms-four-fifth">';

	            $administrator_login_redirect_page = dtlms_option('login','administrator-login-redirect-page');
	            $output .= '<select id="administrator-login-redirect-page" name="dtlms[login][administrator-login-redirect-page]">';
				$pages = get_pages();
				$output .= '<option value="">'.esc_html__('Default', 'dtlms-lite').'</option>';
				if ( class_exists( 'BuddyPress' ) ) {
					$output .= '<option value="buddypress-dashboard" '.selected( 'buddypress-dashboard', $administrator_login_redirect_page, false ).'>'.esc_html__('BuddyPress - Dashboard Page', 'dtlms-lite').'</option>';
				}
				if ( class_exists( 'WooCommerce' ) ) {
					$output .= '<option value="woocommerce-dashboard" '.selected( 'woocommerce-dashboard', $administrator_login_redirect_page, false ).'>'.esc_html__('WooCommerce - Dashboard Page', 'dtlms-lite').'</option>';
				}
				foreach ( $pages as $page ) {
					$output .= '<option value="'.esc_attr( $page->ID ).'" '.selected( $page->ID, $administrator_login_redirect_page, false ).'>';
					$output .= esc_html( $page->post_title );
					$output .= '</option>';
				}
				$output .= '</select>';
	            $output .= '<p class="dtlms-note">'.esc_html__( 'You can choose administrator login redirect page. Default is home page.', 'dtlms-lite' ).'</p>';

			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="dtlms-settings-options-holder">';
			$output .= '<div class="dtlms-column dtlms-one-fifth first">';
				$output .= '<label>'.sprintf( esc_html__( '%1$s Login Redirect Page', 'dtlms-lite' ), $instructor_singular_label ).'</label>';
			$output .= '</div>';
			$output .= '<div class="dtlms-column dtlms-four-fifth">';

	            $instructor_login_redirect_page = dtlms_option('login','instructor-login-redirect-page');
	            $output .= '<select id="instructor-login-redirect-page" name="dtlms[login][instructor-login-redirect-page]">';
				$pages = get_pages();
				$output .= '<option value="">'.esc_html__('Default', 'dtlms-lite').'</option>';
				if ( class_exists( 'BuddyPress' ) ) {
					$output .= '<option value="buddypress-dashboard" '.selected( 'buddypress-dashboard', $instructor_login_redirect_page, false ).'>'.esc_html__('BuddyPress - Dashboard Page', 'dtlms-lite').'</option>';
				}
				if ( class_exists( 'WooCommerce' ) ) {
					$output .= '<option value="woocommerce-dashboard" '.selected( 'woocommerce-dashboard', $instructor_login_redirect_page, false ).'>'.esc_html__('WooCommerce - Dashboard Page', 'dtlms-lite').'</option>';
				}
				foreach ( $pages as $page ) {
					$output .= '<option value="'.esc_attr( $page->ID ).'" '.selected( $page->ID, $instructor_login_redirect_page, false ).'>';
					$output .= esc_html( $page->post_title );
					$output .= '</option>';
				}
				$output .= '</select>';
	            $output .= '<p class="dtlms-note">'.sprintf( esc_html__( 'You can choose %1$s login redirect page. Default is home page.', 'dtlms-lite' ), $instructor_singular_label ).'</p>';

			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="dtlms-settings-options-holder">';
			$output .= '<div class="dtlms-column dtlms-one-fifth first">';
				$output .= '<label>'.esc_html__( 'Student Login Redirect Page', 'dtlms-lite' ).'</label>';
			$output .= '</div>';
			$output .= '<div class="dtlms-column dtlms-four-fifth">';

	            $student_login_redirect_page = dtlms_option('login','student-login-redirect-page');
	            $output .= '<select id="student-login-redirect-page" name="dtlms[login][student-login-redirect-page]">';
				$pages = get_pages();
				$output .= '<option value="">'.esc_html__('Default', 'dtlms-lite').'</option>';
				if ( class_exists( 'BuddyPress' ) ) {
					$output .= '<option value="buddypress-dashboard" '.selected( 'buddypress-dashboard', $student_login_redirect_page, false ).'>'.esc_html__('BuddyPress - Dashboard Page', 'dtlms-lite').'</option>';
				}
				if ( class_exists( 'WooCommerce' ) ) {
					$output .= '<option value="woocommerce-dashboard" '.selected( 'woocommerce-dashboard', $student_login_redirect_page, false ).'>'.esc_html__('WooCommerce - Dashboard Page', 'dtlms-lite').'</option>';
				}
				foreach ( $pages as $page ) {
					$output .= '<option value="'.esc_attr( $page->ID ).'" '.selected( $page->ID, $student_login_redirect_page, false ).'>';
					$output .= esc_html( $page->post_title );
					$output .= '</option>';
				}
				$output .= '</select>';
	            $output .= '<p class="dtlms-note">'.esc_html__( 'You can choose student login redirect page. Default is home page.', 'dtlms-lite' ).'</p>';

			$output .= '</div>';
		$output .= '</div>';


		$output .= '<div class="dtlms-option-settings-response-holder"></div>';

		$output .= '<a href="#" class="dtlms-button dtlms-save-options-settings small" data-settings="login">'.esc_html__('Save Settings', 'dtlms-lite').'</a>';

	$output .= '</form>';

	return $output;

}

function dtlms_get_permalink_settings_content() {

	$instructor_singular_label = apply_filters( 'instructor_label', 'singular' );

	$output = '';

	$output .= '<form name="formOptionSettings" class="formOptionSettings" method="post">';

		$output .= '<div class="dtlms-settings-options-holder">';
			$output .= '<div class="dtlms-column dtlms-one-fifth first">';
				$output .= '<label>'.esc_html__( 'Course Slug', 'dtlms-lite' ).'</label>';
			$output .= '</div>';
			$output .= '<div class="dtlms-column dtlms-four-fifth">';
	            $course_slug = (dtlms_option('permalink','course-slug') != '') ? dtlms_option('permalink','course-slug') : '';
	            $output .= '<input id="course-slug" name="dtlms[permalink][course-slug]" type="text" value="'.esc_attr( $course_slug ).'" />';
	            $output .= '<p class="dtlms-note">'.esc_html__('Do not use characters not allowed in links. Use, eg. courses After change go to Settings > Permalinks and click Save changes.', 'dtlms-lite').'</p>';
			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="dtlms-settings-options-holder">';
			$output .= '<div class="dtlms-column dtlms-one-fifth first">';
				$output .= '<label>'.esc_html__( 'Course Category Slug', 'dtlms-lite' ).'</label>';
			$output .= '</div>';
			$output .= '<div class="dtlms-column dtlms-four-fifth">';
	            $course_category_slug = (dtlms_option('permalink','course-category-slug') != '') ? dtlms_option('permalink','course-category-slug') : '';
	            $output .= '<input id="course-category-slug" name="dtlms[permalink][course-category-slug]" type="text" value="'.esc_attr( $course_category_slug ).'" />';
	            $output .= '<p class="dtlms-note">'.esc_html__('Do not use characters not allowed in links. Use, eg. course-cateogry After change go to Settings > Permalinks and click Save changes.', 'dtlms-lite').'</p>';
			$output .= '</div>';
		$output .= '</div>';

		$dtlms_modules = dtlms_instance()->active_modules;
		$dtlms_module_active = (is_array($dtlms_modules) && !empty($dtlms_modules) && in_array('class', $dtlms_modules)) ? true : false;
		if($dtlms_module_active) {

			$class_singular_label = apply_filters( 'class_label', 'singular' );
			$class_plural_label = apply_filters( 'class_label', 'plural' );
			$output .= '<div class="dtlms-settings-options-holder">';
				$output .= '<div class="dtlms-column dtlms-one-fifth first">';
					$output .= '<label>'.sprintf( esc_html__( '%1$s Slug', 'dtlms-lite' ), $class_singular_label ).'</label>';
				$output .= '</div>';
				$output .= '<div class="dtlms-column dtlms-four-fifth">';
					$class_slug = (dtlms_option('permalink','class-slug') != '') ? dtlms_option('permalink','class-slug') : '';
					$output .= '<input id="class-slug" name="dtlms[permalink][class-slug]" type="text" value="'.esc_attr( $class_slug ).'" />';
					$output .= '<p class="dtlms-note">'.esc_html__('Do not use characters not allowed in links. Use, eg. classes After change go to Settings > Permalinks and click Save changes.', 'dtlms-lite').'</p>';
				$output .= '</div>';
			$output .= '</div>';

		}

		$output .= '<div class="dtlms-option-settings-response-holder"></div>';

		$output .= '<a href="#" class="dtlms-button dtlms-save-options-settings small" data-settings="permalink">'.esc_html__('Save Settings', 'dtlms-lite').'</a>';

	$output .= '</form>';

	return $output;
}

function dtlms_get_chart_settings_content() {

	$output = '';

	$instructor_singular_label = apply_filters( 'instructor_label', 'singular' );
	$instructor_plural_label = apply_filters( 'instructor_label', 'plural' );


	$output .= '<form name="formOptionSettings" class="formOptionSettings" method="post">';

		$chart_colors = dtlms_option('chart', 'chart-colors');

		$output .= '<p class="dtlms-note">'.esc_html__('Following colors will be used as default colors for your chart.', 'dtlms-lite').'</p>';

		$output .= '<div class="dtlms-column dtlms-one-fourth first">';
			$output .= '<div class="dtlms-settings-options-holder">';
				$output .= '<div class="dtlms-column dtlms-one-fifth first">';
					$output .= '<label>'.esc_html__( 'First Color', 'dtlms-lite' ).'</label>';
				$output .= '</div>';
				$output .= '<div class="dtlms-column dtlms-four-fifth">';
		            $chart_color = (isset($chart_colors[0]) && $chart_colors[0] != '') ? $chart_colors[0] : '';
		            $output .= '<input id="first-color" name="dtlms[chart][chart-colors][]" class="dtlms-color-field color-picker" data-alpha="true" type="text" value="'.esc_attr( $chart_color ).'" />';
		            $output .= '<p class="dtlms-note">'.esc_html__('Choose chart first color', 'dtlms-lite').'</p>';
				$output .= '</div>';
			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="dtlms-column dtlms-one-fourth">';
			$output .= '<div class="dtlms-settings-options-holder">';
				$output .= '<div class="dtlms-column dtlms-one-fifth first">';
					$output .= '<label>'.esc_html__( 'Second Color', 'dtlms-lite' ).'</label>';
				$output .= '</div>';
				$output .= '<div class="dtlms-column dtlms-four-fifth">';
		            $chart_color = (isset($chart_colors[1]) && $chart_colors[1] != '') ? $chart_colors[1] : '';
		            $output .= '<input id="second-color" name="dtlms[chart][chart-colors][]" class="dtlms-color-field color-picker" data-alpha="true" type="text" value="'.esc_attr( $chart_color ).'" />';
		            $output .= '<p class="dtlms-note">'.esc_html__('Choose chart second color', 'dtlms-lite').'</p>';
				$output .= '</div>';
			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="dtlms-column dtlms-one-fourth">';
			$output .= '<div class="dtlms-settings-options-holder">';
				$output .= '<div class="dtlms-column dtlms-one-fifth first">';
					$output .= '<label>'.esc_html__( 'Third Color', 'dtlms-lite' ).'</label>';
				$output .= '</div>';
				$output .= '<div class="dtlms-column dtlms-four-fifth">';
		            $chart_color = (isset($chart_colors[2]) && $chart_colors[2] != '') ? $chart_colors[2] : '';
		            $output .= '<input id="third-color" name="dtlms[chart][chart-colors][]" class="dtlms-color-field color-picker" data-alpha="true" type="text" value="'.esc_attr( $chart_color ).'" />';
		            $output .= '<p class="dtlms-note">'.esc_html__('Choose chart third color', 'dtlms-lite').'</p>';
				$output .= '</div>';
			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="dtlms-column dtlms-one-fourth">';
			$output .= '<div class="dtlms-settings-options-holder">';
				$output .= '<div class="dtlms-column dtlms-one-fifth first">';
					$output .= '<label>'.esc_html__( 'Fourth Color', 'dtlms-lite' ).'</label>';
				$output .= '</div>';
				$output .= '<div class="dtlms-column dtlms-four-fifth">';
		            $chart_color = (isset($chart_colors[3]) && $chart_colors[3] != '') ? $chart_colors[3] : '';
		            $output .= '<input id="fourth-color" name="dtlms[chart][chart-colors][]" class="dtlms-color-field color-picker" data-alpha="true" type="text" value="'.esc_attr( $chart_color ).'" />';
		            $output .= '<p class="dtlms-note">'.esc_html__('Choose chart fourth color', 'dtlms-lite').'</p>';
				$output .= '</div>';
			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="dtlms-hr-invisible"></div>';

		$output .= '<div class="dtlms-column dtlms-one-fourth first">';
			$output .= '<div class="dtlms-settings-options-holder">';
				$output .= '<div class="dtlms-column dtlms-one-fifth first">';
					$output .= '<label>'.esc_html__( 'Fifth Color', 'dtlms-lite' ).'</label>';
				$output .= '</div>';
				$output .= '<div class="dtlms-column dtlms-four-fifth">';
		            $chart_color = (isset($chart_colors[4]) && $chart_colors[4] != '') ? $chart_colors[4] : '';
		            $output .= '<input id="fifth-color" name="dtlms[chart][chart-colors][]" class="dtlms-color-field color-picker" data-alpha="true" type="text" value="'.esc_attr( $chart_color ).'" />';
		            $output .= '<p class="dtlms-note">'.esc_html__('Choose chart fifth color', 'dtlms-lite').'</p>';
				$output .= '</div>';
			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="dtlms-column dtlms-one-fourth">';
			$output .= '<div class="dtlms-settings-options-holder">';
				$output .= '<div class="dtlms-column dtlms-one-fifth first">';
					$output .= '<label>'.esc_html__( 'Sixth Color', 'dtlms-lite' ).'</label>';
				$output .= '</div>';
				$output .= '<div class="dtlms-column dtlms-four-fifth">';
		            $chart_color = (isset($chart_colors[5]) && $chart_colors[5] != '') ? $chart_colors[5] : '';
		            $output .= '<input id="sixth-color" name="dtlms[chart][chart-colors][]" class="dtlms-color-field color-picker" data-alpha="true" type="text" value="'.esc_attr( $chart_color ).'" />';
		            $output .= '<p class="dtlms-note">'.esc_html__('Choose chart sixth color', 'dtlms-lite').'</p>';
				$output .= '</div>';
			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="dtlms-column dtlms-one-fourth">';
			$output .= '<div class="dtlms-settings-options-holder">';
				$output .= '<div class="dtlms-column dtlms-one-fifth first">';
					$output .= '<label>'.esc_html__( 'Seventh Color', 'dtlms-lite' ).'</label>';
				$output .= '</div>';
				$output .= '<div class="dtlms-column dtlms-four-fifth">';
		            $chart_color = (isset($chart_colors[6]) && $chart_colors[6] != '') ? $chart_colors[6] : '';
		            $output .= '<input id="seventh-color" name="dtlms[chart][chart-colors][]" class="dtlms-color-field color-picker" data-alpha="true" type="text" value="'.esc_attr( $chart_color ).'" />';
		            $output .= '<p class="dtlms-note">'.esc_html__('Choose chart seventh color', 'dtlms-lite').'</p>';
				$output .= '</div>';
			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="dtlms-column dtlms-one-fourth">';
			$output .= '<div class="dtlms-settings-options-holder">';
				$output .= '<div class="dtlms-column dtlms-one-fifth first">';
					$output .= '<label>'.esc_html__( 'Eighth Color', 'dtlms-lite' ).'</label>';
				$output .= '</div>';
				$output .= '<div class="dtlms-column dtlms-four-fifth">';
		            $chart_color = (isset($chart_colors[7]) && $chart_colors[7] != '') ? $chart_colors[7] : '';
		            $output .= '<input id="eighth-color" name="dtlms[chart][chart-colors][]" class="dtlms-color-field color-picker" data-alpha="true" type="text" value="'.esc_attr( $chart_color ).'" />';
		            $output .= '<p class="dtlms-note">'.esc_html__('Choose chart eighth color', 'dtlms-lite').'</p>';
				$output .= '</div>';
			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="dtlms-hr-invisible"></div>';

		$output .= '<div class="dtlms-column dtlms-one-fourth first">';
			$output .= '<div class="dtlms-settings-options-holder">';
				$output .= '<div class="dtlms-column dtlms-one-fifth first">';
					$output .= '<label>'.esc_html__( 'Nineth Color', 'dtlms-lite' ).'</label>';
				$output .= '</div>';
				$output .= '<div class="dtlms-column dtlms-four-fifth">';
		            $chart_color = (isset($chart_colors[8]) && $chart_colors[8] != '') ? $chart_colors[8] : '';
		            $output .= '<input id="nineth-color" name="dtlms[chart][chart-colors][]" class="dtlms-color-field color-picker" data-alpha="true" type="text" value="'.esc_attr( $chart_color ).'" />';
		            $output .= '<p class="dtlms-note">'.esc_html__('Choose chart nineth color', 'dtlms-lite').'</p>';
				$output .= '</div>';
			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="dtlms-column dtlms-one-fourth">';
			$output .= '<div class="dtlms-settings-options-holder">';
				$output .= '<div class="dtlms-column dtlms-one-fifth first">';
					$output .= '<label>'.esc_html__( 'Tenth Color', 'dtlms-lite' ).'</label>';
				$output .= '</div>';
				$output .= '<div class="dtlms-column dtlms-four-fifth">';
		            $chart_color = (isset($chart_colors[9]) && $chart_colors[9] != '') ? $chart_colors[9] : '';
		            $output .= '<input id="tenth-color" name="dtlms[chart][chart-colors][]" class="dtlms-color-field color-picker" data-alpha="true" type="text" value="'.esc_attr( $chart_color ).'" />';
		            $output .= '<p class="dtlms-note">'.esc_html__('Choose chart tenth color', 'dtlms-lite').'</p>';
				$output .= '</div>';
			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="dtlms-column dtlms-one-fourth">';
			$output .= '<div class="dtlms-settings-options-holder">';
				$output .= '<div class="dtlms-column dtlms-one-fifth first">';
					$output .= '<label>'.esc_html__( 'Eleventh Color', 'dtlms-lite' ).'</label>';
				$output .= '</div>';
				$output .= '<div class="dtlms-column dtlms-four-fifth">';
		            $chart_color = (isset($chart_colors[10]) && $chart_colors[10] != '') ? $chart_colors[10] : '';
		            $output .= '<input id="eleventh-color" name="dtlms[chart][chart-colors][]" class="dtlms-color-field color-picker" data-alpha="true" type="text" value="'.esc_attr( $chart_color ).'" />';
		            $output .= '<p class="dtlms-note">'.esc_html__('Choose chart eleventh color', 'dtlms-lite').'</p>';
				$output .= '</div>';
			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="dtlms-column dtlms-one-fourth">';
			$output .= '<div class="dtlms-settings-options-holder">';
				$output .= '<div class="dtlms-column dtlms-one-fifth first">';
					$output .= '<label>'.esc_html__( 'Twelfth Color', 'dtlms-lite' ).'</label>';
				$output .= '</div>';
				$output .= '<div class="dtlms-column dtlms-four-fifth">';
		            $chart_color = (isset($chart_colors[11]) && $chart_colors[11] != '') ? $chart_colors[11] : '';
		            $output .= '<input id="twelfth-color" name="dtlms[chart][chart-colors][]" class="dtlms-color-field color-picker" data-alpha="true" type="text" value="'.esc_attr( $chart_color ).'" />';
		            $output .= '<p class="dtlms-note">'.esc_html__('Choose chart twelfth color', 'dtlms-lite').'</p>';
				$output .= '</div>';
			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="dtlms-hr-invisible"></div>';

		$output .= '<div class="dtlms-settings-options-holder">';
			$output .= '<div class="dtlms-column dtlms-one-fifth first">';
				$output .= '<label>'.esc_html__( 'Shuffle Colors', 'dtlms-lite' ).'</label>';
			$output .= '</div>';
			$output .= '<div class="dtlms-column dtlms-four-fifth">';
                $checked = ( 'true' ==  dtlms_option('chart', 'shuffle-colors') ) ? ' checked="checked"' : '';
                $switchclass = ( 'true' ==  dtlms_option('chart', 'shuffle-colors') ) ? 'checkbox-switch-on' :'checkbox-switch-off';
	            $output .= '<div data-for="shuffle-colors" class="dtlms-checkbox-switch '.esc_attr( $switchclass ).'"></div>';
	            $output .= '<input id="shuffle-colors" class="hidden" type="checkbox" name="dtlms[chart][shuffle-colors]" value="true" '.esc_attr( $checked ).' />';
	            $output .= '<p class="dtlms-note">'.esc_html__('If you like to shuffle colors for chart than choose "Yes"', 'dtlms-lite').'</p>';
			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="dtlms-hr-invisible"></div>';

		$output .= '<div class="dtlms-settings-options-holder">';
			$output .= '<div class="dtlms-column dtlms-one-fifth first">';
				$output .= '<label>'.esc_html__('Legend Position', 'dtlms-lite').'</label>';
			$output .= '</div>';
			$output .= '<div class="dtlms-column dtlms-four-fifth">';

				$legend_positions = array (
					'top'    => esc_html__('Top', 'dtlms-lite'),
					'bottom' => esc_html__('Bottom', 'dtlms-lite'),
					'left'   => esc_html__('Left', 'dtlms-lite'),
					'right'  => esc_html__('Right', 'dtlms-lite')
				);

	            $legend_position_selected = dtlms_option('chart', 'legend-position');
	            $output .= '<select id="legend-position" name="dtlms[chart][legend-position]">';
				foreach ( $legend_positions as $legend_position_key => $legend_position ) {
					$output .= '<option value="'.esc_attr( $legend_position_key ).'" '.selected( $legend_position_key, $legend_position_selected, false ).'>';
						$output .= esc_html( $legend_position );
					$output .= '</option>';
				}
				$output .= '</select>';
	            $output .= '<p class="dtlms-note">'.esc_html__('Choose legend position for your charts.', 'dtlms-lite').'</p>';

			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="dtlms-option-settings-response-holder"></div>';
		$output .= '<a href="#" class="dtlms-button dtlms-save-options-settings small" data-settings="chart">'.esc_html__('Save Settings', 'dtlms-lite').'</a>';

	$output .= '</form>';

	return $output;
}
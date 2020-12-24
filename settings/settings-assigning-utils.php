<?php
// Courses - Assign Students
add_action( 'wp_ajax_dtlms_assigning_load_students_data', 'dtlms_assigning_load_students_data' );
add_action( 'wp_ajax_nopriv_dtlms_assigning_load_students_data', 'dtlms_assigning_load_students_data' );
function dtlms_assigning_load_students_data() {

	$course_id = dtlms_recursive_sanitize_text_field( $_REQUEST['course_id'] );

	$output = '';

	if($course_id != '') {

		// Pagination script Start
		$ajax_call           = (isset($_REQUEST['ajax_call']) && $_REQUEST['ajax_call'] == true) ? true : false;
		$current_page        = isset($_REQUEST['current_page']) ? dtlms_recursive_sanitize_text_field( $_REQUEST['current_page'] ) : 1;
		$offset              = isset($_REQUEST['offset']) ? dtlms_recursive_sanitize_text_field( $_REQUEST['offset'] ) : 0;
		$backend_postperpage = (dtlms_option('general','backend-postperpage') != '') ? dtlms_option('general','backend-postperpage') : 10;
		$post_per_page       = isset($_REQUEST['post_per_page']) ? dtlms_recursive_sanitize_text_field( $_REQUEST['post_per_page'] ) : $backend_postperpage;

		if($dashboard_function_call != '') {
			$function_call = $dashboard_function_call;
		} else {
			$function_call = (isset($_REQUEST['function_call']) && $_REQUEST['function_call'] != '') ? dtlms_recursive_sanitize_text_field( $_REQUEST['function_call'] ) : 'dtlms_assigning_load_students_data';
		}

		if($dashboard_output_div != '') {
			$output_div = $dashboard_output_div;
		} else {
			$output_div = (isset($_REQUEST['output_div']) && $_REQUEST['output_div'] != '') ? dtlms_recursive_sanitize_text_field( $_REQUEST['output_div'] ) : 'dtlms-assign-studentstocourse-container';
		}
		// Pagination script End
		$output .= '<form name="formAssignStudents" class="formAssignStudents" method="post">';

			$output .= '<table border="0" cellpadding="0" cellspacing="0" class="dtlms-custom-table">
				<tr>
					<th scope="col">'.esc_html__('#', 'dtlms-lite').'</th>
					<th scope="col">'.esc_html__('Student', 'dtlms-lite').'</th>
					<th scope="col">'.esc_html__('Purchased', 'dtlms-lite').'</th>
					<th scope="col">'.esc_html__('Assigned', 'dtlms-lite').'</th>
				</tr>';

				$page_student_ids = '';

				$students          = get_users ( array ('role' => 'student' ) );
				$students_filtered = array_slice($students, $offset, $post_per_page, true);

				if(is_array($students_filtered) && !empty($students_filtered)) {

					$i = $offset+1;
					foreach ( $students_filtered as $student ) {
						setup_postdata( $student );

						$student_id = $student->data->ID;

		                $purchase_checked = '';
		                $purchase_switchclass = 'checkbox-switch-off';

						$active_package_courses = dtlms_get_user_active_packages($student_id, 'courses');
						$active_package_courses = (is_array($active_package_courses) && !empty($active_package_courses)) ? $active_package_courses : array();
						$purchased_courses = get_user_meta($student_id, 'purchased_courses', true);
						if((is_array($purchased_courses) && in_array($course_id, $purchased_courses)) || (is_array($active_package_courses) && in_array($course_id, $active_package_courses))) {
		                    $purchase_checked = 'checked="checked"';
		                    $purchase_switchclass = 'checkbox-switch-on';
						}

		                $assigned_checked = '';
		                $assigned_switchclass = 'checkbox-switch-off';
						$assigned_courses = get_user_meta($student_id, 'assigned_courses', true);
						if(is_array($assigned_courses) && in_array($course_id, $assigned_courses)) {
		                    $assigned_checked = 'checked="checked"';
		                    $assigned_switchclass = 'checkbox-switch-on';
						}

						$student_disp_name = get_the_author_meta('display_name', $student_id);

						$output .= '<tr>
							<td>'.esc_html( $i ).'</td>
							<td>'.esc_html( $student_disp_name ).'</td>
							<td>'
								.'<div class="dtlms-checkbox-switch disabled '.esc_attr( $purchase_switchclass ).'"></div>'.
							'</td>
							<td>'
								.'<div data-for="assign-students-to-course-'.esc_attr( $student_id ).'" class="dtlms-checkbox-switch '.esc_attr( $assigned_switchclass ).'"></div>'
								.'<input id="assign-students-to-course-'.esc_attr( $student_id ).'" class="assign-students-to-course hidden" type="checkbox" name="assign-students-to-course" value="'.esc_attr( $student_id ).'" '.esc_attr( $assigned_checked ).' />'.
							'</td>
						</tr>';

						$i++;

						$page_student_ids = $student_id.','.$page_student_ids;

					}

				} else {
					$output .= '<tr>
						<td colspan="4">'.esc_html__('No records found!', 'dtlms-lite').'</td>
					</tr>';
				}

			$output .= '</table>';

		$output .= '</form>';


		$output .= '<div class="dtlms-assign-students-response-holder"></div>';

		$output .= '<a href="#" class="dtlms-button dtlms-save-assign-students-settings small" data-courseid="'.esc_attr( $course_id ).'" data-pagestudentids="'.rtrim($page_student_ids, ',').'">'.esc_html__('Assign', 'dtlms-lite').'</a>';


		wp_reset_postdata();

		// Pagination script Start
		$students_count = count($students);
		$max_num_pages = ceil($students_count / $post_per_page);

		$item_ids['course_id'] = $course_id;

		$output .= dtlms_ajax_pagination($max_num_pages, $current_page, $function_call, $output_div, $item_ids);
		// Pagination script End

	} else {

		$output .= esc_html__('Please choose course!', 'dtlms-lite');

	}

	echo $output;

	die();

}

add_action( 'wp_ajax_dtlms_save_assign_students_settings', 'dtlms_save_assign_students_settings' );
add_action( 'wp_ajax_nopriv_dtlms_save_assign_students_settings', 'dtlms_save_assign_students_settings' );
function dtlms_save_assign_students_settings() {

	$course_id        = dtlms_recursive_sanitize_text_field( $_REQUEST['course_id'] );
	$page_student_ids = !empty($_REQUEST['page_student_ids']) ? explode(',', dtlms_recursive_sanitize_text_field( $_REQUEST['page_student_ids'] )) : array ();
	$student_ids      = is_array($_REQUEST['student_ids']) && !empty($_REQUEST['student_ids']) ? dtlms_recursive_sanitize_text_field( $_REQUEST['student_ids'] ) : array ();

	$output = '';

	if($course_id != '') {

		$students = get_users ( array ('role' => 'student', 'include' => $page_student_ids) );
        if ( count( $students ) > 0 ) {
            foreach ($students as $student) {

            	$student_id = $student->data->ID;

            	if(in_array($student_id, $student_ids)) {
					$assigned_courses = get_user_meta($student_id, 'assigned_courses', true);
					$assigned_courses = (is_array($assigned_courses) && !empty($assigned_courses)) ? $assigned_courses : array();
					array_push($assigned_courses, $course_id);
					update_user_meta($student_id, 'assigned_courses', array_unique($assigned_courses));

					// Notification & Mail
					do_action('dtlms_poc_course_assigned', $course_id, $student_id);
            	} else {
					$assigned_courses = get_user_meta($student_id, 'assigned_courses', true);
					$assigned_courses = (is_array($assigned_courses) && !empty($assigned_courses)) ? $assigned_courses : array();
					if(in_array($course_id, $assigned_courses)) {
					    unset($assigned_courses[array_search($course_id, $assigned_courses)]);
					}
					update_user_meta($student_id, 'assigned_courses', array_unique($assigned_courses));
            	}

			}
		}

		update_post_meta($course_id, 'assigned_users', array_unique($student_ids));

		$output .= esc_html__('Students assigned successfully!', 'dtlms-lite');

	} else {

		$output .= esc_html__('Something went wrong!', 'dtlms-lite');

	}

	echo $output;

	die();

}

// Courses - Assign Courses

add_action( 'wp_ajax_dtlms_assigning_load_courses_data', 'dtlms_assigning_load_courses_data' );
add_action( 'wp_ajax_nopriv_dtlms_assigning_load_courses_data', 'dtlms_assigning_load_courses_data' );
function dtlms_assigning_load_courses_data() {

	$student_id = dtlms_recursive_sanitize_text_field( $_REQUEST['student_id'] );

	$output = '';

	if($student_id != '') {

		// Pagination script Start
		$ajax_call           = (isset($_REQUEST['ajax_call']) && $_REQUEST['ajax_call'] == true) ? true : false;
		$current_page        = isset($_REQUEST['current_page']) ? dtlms_recursive_sanitize_text_field( $_REQUEST['current_page'] ) : 1;
		$offset              = isset($_REQUEST['offset']) ? dtlms_recursive_sanitize_text_field( $_REQUEST['offset'] ) : 0;
		$backend_postperpage = (dtlms_option('general','backend-postperpage') != '') ? dtlms_option('general','backend-postperpage') : 10;
		$post_per_page       = isset($_REQUEST['post_per_page']) ? dtlms_recursive_sanitize_text_field( $_REQUEST['post_per_page'] ) : $backend_postperpage;

		if($dashboard_function_call != '') {
			$function_call = $dashboard_function_call;
		} else {
			$function_call = (isset($_REQUEST['function_call']) && $_REQUEST['function_call'] != '') ? dtlms_recursive_sanitize_text_field( $_REQUEST['function_call'] ) : 'dtlms_assigning_load_courses_data';
		}

		if($dashboard_output_div != '') {
			$output_div = $dashboard_output_div;
		} else {
			$output_div = (isset($_REQUEST['output_div']) && $_REQUEST['output_div'] != '') ? dtlms_recursive_sanitize_text_field( $_REQUEST['output_div'] ) : 'dtlms-assign-coursestostudent-container';
		}
		// Pagination script End

		$output .= '<form name="formAssignCourses" class="formAssignCourses" method="post">';

			$output .= '<table border="0" cellpadding="0" cellspacing="0" class="dtlms-custom-table">
				<tr>
					<th scope="col">'.esc_html__('#', 'dtlms-lite').'</th>
					<th scope="col">'.esc_html__('Course', 'dtlms-lite').'</th>
					<th scope="col">'.esc_html__('Purchased', 'dtlms-lite').'</th>
					<th scope="col">'.esc_html__('Assigned', 'dtlms-lite').'</th>
				</tr>';

				$page_course_ids = '';

				$args = array (
					'offset'         => $offset,
					'paged'          => $current_page,
					'posts_per_page' => $post_per_page,
					'post_type'      => 'dtlms_courses',
				);

				$args['meta_query'][] = array (
					'key'     => '_regular_price',
					'value'   => 0,
					'type'    => 'numeric',
					'compare' => '>'
				);

			    $courses = get_posts($args);
			    if(isset($courses) && !empty($courses)) {
			    	$i = $offset+1;
			    	foreach( $courses as $course ) {

			    		$course_id = $course->ID;

		                $purchase_checked = '';
		                $purchase_switchclass = 'checkbox-switch-off';

						$active_package_courses = dtlms_get_user_active_packages($student_id, 'courses');
						$active_package_courses = (is_array($active_package_courses) && !empty($active_package_courses)) ? $active_package_courses : array();
						$purchased_users = get_post_meta($course_id, 'purchased_users', true);
						if((is_array($purchased_users) && in_array($student_id, $purchased_users)) || (is_array($active_package_courses) && in_array($course_id, $active_package_courses))) {
		                    $purchase_checked = 'checked="checked"';
		                    $purchase_switchclass = 'checkbox-switch-on';
						}

		                $assigned_checked = '';
		                $assigned_switchclass = 'checkbox-switch-off';
						$assigned_users = get_post_meta($course_id, 'assigned_users', true);
						if(is_array($assigned_users) && in_array($student_id, $assigned_users)) {
		                    $assigned_checked = 'checked="checked"';
		                    $assigned_switchclass = 'checkbox-switch-on';
						}

						$output .= '<tr>
							<td>'.esc_html( $i ).'</td>
							<td>'.esc_html( get_the_title($course_id) ).'</td>
							<td>'
								.'<div class="dtlms-checkbox-switch disabled '.esc_attr( $purchase_switchclass ).'"></div>'.
							'</td>
							<td>'
								.'<div data-for="assign-courses-to-student-'.esc_attr( $course_id ).'" class="dtlms-checkbox-switch '.esc_attr( $assigned_switchclass ).'"></div>'
								.'<input id="assign-courses-to-student-'.esc_attr( $course_id ).'" class="assign-courses-to-student hidden" type="checkbox" name="assign-courses-to-student" value="'.esc_attr( $course_id ).'" '.$assigned_checked.' />'.
										'</td>
									</tr>';

						$page_course_ids = $course_id.','.$page_course_ids;

						$i++;

					}

				} else {
					$output .= '<tr>
						<td colspan="4">'.esc_html__('No records found!', 'dtlms-lite').'</td>
					</tr>';
				}

			$output .= '</table>';

		$output .= '</form>';

		$output .= '<div class="dtlms-assign-courses-response-holder"></div>';

		$output .= '<a href="#" class="dtlms-button dtlms-save-assign-courses-settings small" data-studentid="'.esc_attr( $student_id ).'" data-pagecourseids="'.esc_attr( rtrim($page_course_ids, ',') ).'">'.esc_html__('Assign', 'dtlms-lite').'</a>';

		wp_reset_postdata();

		// Pagination script Start
		$total_post_args = array (
			'posts_per_page' => -1,
			'post_type'=> 'dtlms_courses'
		);

		$total_post_args['meta_query'][] = array (
			'key'     => '_regular_price',
			'value'   => 0,
			'type'    => 'numeric',
			'compare' => '>'
		);

		$total_post_courses = get_posts( $total_post_args );
		wp_reset_postdata();

		$courses_post_count = count($total_post_courses);
		$max_num_pages      = ceil($courses_post_count / $post_per_page);

		$item_ids['student_id'] = $student_id;

		$output .= dtlms_ajax_pagination($max_num_pages, $current_page, $function_call, $output_div, $item_ids);
		// Pagination script End

	} else {

		$output .= esc_html__('Please choose student', 'dtlms-lite');

	}

	echo $output;
	die();
}

add_action( 'wp_ajax_dtlms_save_assign_courses_settings', 'dtlms_save_assign_courses_settings' );
add_action( 'wp_ajax_nopriv_dtlms_save_assign_courses_settings', 'dtlms_save_assign_courses_settings' );
function dtlms_save_assign_courses_settings() {

	$student_id      = dtlms_recursive_sanitize_text_field( $_REQUEST['student_id'] );
	$page_course_ids = !empty($_REQUEST['page_course_ids']) ? explode(',', dtlms_recursive_sanitize_text_field( $_REQUEST['page_course_ids'] ) ) : array ();
	$course_ids      = is_array($_REQUEST['course_ids']) && !empty($_REQUEST['course_ids']) ? dtlms_recursive_sanitize_text_field( $_REQUEST['course_ids'] ) : array ();

	$output = '';

	if($student_id != '') {

		$args = array (
		    'post_type'      => 'dtlms_courses',
		    'posts_per_page' => -1,
		    'post__in'       => $page_course_ids
	    );

	    $courses = get_posts($args);

	    if(isset($courses) && !empty($courses)) {
	    	foreach( $courses as $course ) {

	    		$course_id = $course->ID;

            	if(in_array($course_id, $course_ids)) {

					$assigned_users = get_post_meta($course_id, 'assigned_users', true);
					$assigned_users = (is_array($assigned_users) && !empty($assigned_users)) ? $assigned_users : array();
					array_push($assigned_users, $student_id);
					update_post_meta($course_id, 'assigned_users', array_unique($assigned_users));

					// Notification & Mail
					do_action('dtlms_poc_course_assigned', $course_id, $student_id);

            	} else {

					$assigned_users = get_post_meta($course_id, 'assigned_users', true);
					$assigned_users = (is_array($assigned_users) && !empty($assigned_users)) ? $assigned_users : array();
					if(in_array($student_id, $assigned_users)) {
					    unset($assigned_users[array_search($student_id, $assigned_users)]);
					}
					update_post_meta($course_id, 'assigned_users', array_unique($assigned_users));

            	}

			}
		}

		update_user_meta($student_id, 'assigned_courses', array_unique($course_ids));

		$output .= esc_html__('Courses assigned successfully!', 'dtlms-lite');

	} else {

		$output .= esc_html__('Something went wrong!', 'dtlms-lite');

	}

	echo $output;

	die();
}
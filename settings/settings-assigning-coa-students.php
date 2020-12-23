<?php
// Courses
if(!function_exists('dtlms_get_courses_assign_students_content')) {

	function dtlms_get_courses_assign_students_content() {

		$output = '';

		$output .= '<div class="dtlms-settings-assign-students-container">';

			$output .= '<div class="dtlms-column dtlms-one-fifth first">';

				$output .= '<label>'.esc_html__('Choose course', 'dtlms-lite').'</label>';

			$output .= '</div>';


			$output .= '<div class="dtlms-column dtlms-four-fifth">';

			    $output .= '<select class="dtlms-assigning-students" name="dtlms-assigning-students" data-placeholder="'.esc_attr__('Choose Course ...', 'dtlms-lite').'" class="dtlms-chosen-select">';

					$output .= '<option value="">'.esc_html__('None', 'dtlms-lite').'</option>';

					$args = array (
					    'post_type'      => 'dtlms_courses',
					    'posts_per_page' => -1
				    );

					$args['meta_query'][] = array (
						'key'     => '_regular_price',
						'value'   => 0,
						'type'    => 'numeric',
						'compare' => '>'
					);

				    $courses = get_posts($args);

				    if(isset($courses) && !empty($courses)) {
				    	foreach( $courses as $course ) {

				    		$course_id = $course->ID;
				    		$output .= '<option value="' . esc_attr( $course_id ) . '"' . selected( $course_id, '', false ) . '>' . esc_html( get_the_title($course_id) ) . '</option>';

				    	}
				    }

			    $output .= '</select>';

			$output .= '</div>';

			$output .= dtlms_generate_loader_html(false);

			$output .= '<div class="dtlms-assign-studentstocourse-container"></div>';


		$output .= '</div>';

		return $output;

	}

	echo dtlms_get_courses_assign_students_content();
}
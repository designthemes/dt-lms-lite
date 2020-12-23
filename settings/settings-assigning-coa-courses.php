<?php
if(!function_exists('dtlms_get_courses_assign_courses_content')) {

	function dtlms_get_courses_assign_courses_content() {

		$output = '';

		$output .= '<div class="dtlms-settings-assign-courses-container">';

			$output .= '<div class="dtlms-column dtlms-one-fifth first">';

				$output .= '<label>'.esc_html__('Choose student', 'dtlms-lite').'</label>';

			$output .= '</div>';


			$output .= '<div class="dtlms-column dtlms-four-fifth">';

			    $output .= '<select class="dtlms-assigning-courses" name="dtlms-assigning-courses" data-placeholder="'.esc_attr__('Choose Student ...', 'dtlms-lite').'" class="dtlms-chosen-select">';

					$output .= '<option value="">'.esc_html__('None', 'dtlms-lite').'</option>';

					$students = get_users ( array ('role' => 'student') );
			        if ( count( $students ) > 0 ) {
			            foreach ($students as $student) {

							$student_id = $student->data->ID;

				    		$output .= '<option value="' . esc_attr( $student_id ) . '"' . selected( $student_id, '', false ) . '>' . esc_html( $student->data->display_name ) . '</option>';

				    	}
				    }

			    $output .= '</select>';

			$output .= '</div>';

			$output .= dtlms_generate_loader_html(false);

			$output .= '<div class="dtlms-assign-coursestostudent-container"></div>';


		$output .= '</div>';

		return $output;

	}

	echo dtlms_get_courses_assign_courses_content();
}
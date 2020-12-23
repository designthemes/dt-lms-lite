<?php

add_action( 'wp_ajax_dtlms_generate_course_result', 'dtlms_generate_course_result' );
add_action( 'wp_ajax_nopriv_dtlms_generate_course_result', 'dtlms_generate_course_result' );
function dtlms_generate_course_result() {

	$course_id = sanitize_text_field( $_REQUEST['course_id'] );
	$user_id   = sanitize_text_field( $_REQUEST['user_id'] );

	$output = dtlms_course_overall_result($course_id, $user_id, 'course');

	echo $output;

	die();
}

function dtlms_course_overall_result($course_id, $user_id, $origin = 'course') {

	$output = '';

	if($course_id > 0 && $user_id > 0) {

		$author_id = get_post_field( 'post_author', $course_id );

		$curriculum_details = get_user_meta($user_id, $course_id, true);
		$course_grade_id = $curriculum_details['grade-post-id'];

		$purchased_courses = get_user_meta($user_id, 'purchased_courses', true);
		$purchased_courses = (is_array($purchased_courses) && !empty($purchased_courses)) ? $purchased_courses : array ();

		$started_courses = get_user_meta($user_id, 'started_courses', true);
		$started_courses = (is_array($started_courses) && !empty($started_courses)) ? $started_courses : array ();

		$submitted_courses = get_user_meta($user_id, 'submitted_courses', true);
		$submitted_courses = (is_array($submitted_courses) && !empty($submitted_courses)) ? $submitted_courses : array ();

		$completed_courses = get_user_meta($user_id, 'completed_courses', true);
		$completed_courses = (is_array($completed_courses) && !empty($completed_courses)) ? $completed_courses : array ();

		$courses_undergoing = array_diff($started_courses, $submitted_courses);
		$courses_underevaluation = array_diff($submitted_courses, $completed_courses);

		$dtlms_modules = dtlms_instance()->active_modules;
		$dtlms_modules = (is_array($dtlms_modules) && !empty($dtlms_modules)) ? $dtlms_modules : array ();


		$output .= '<div id="dtlms-course-result-popup">';

			$output .= '<div class="dtlms-course-result-popup-header">';

				$output .= '<div class="dtlms-course-result-popup-intro">';

					$output .= '<h2>'.esc_html( get_the_title($course_id) ).'</h2>';

					$output .= '<div class="dtlms-item-status-details">';

						if(in_array($course_id, $courses_undergoing)) {
							$output .= '<span class="dtlms-undergoing">'.esc_html__('Undergoing', 'dtlms-lite'). '</span>';
						}

						if(in_array($course_id, $courses_underevaluation)) {
							$output .= '<span class="dtlms-underevaluation">'.esc_html__('Under Evaluation', 'dtlms-lite').'</span>';
						}

						if(in_array($course_id, $completed_courses)) {
							$output .= '<span class="dtlms-completed">'.esc_html__('Completed', 'dtlms-lite').'</span>';
						}

					$output .= '</div>';

				$output .= '</div>';

				if($origin != 'class') {

					$output .= '<div class="dtlms-refresh-course-result" data-courseid="'.esc_attr( $course_id ).'" data-userid="'.esc_attr( $user_id ).'"></div>';
					$output .= '<div class="dtlms-close-course-result-popup"></div>';
				}

				$output .= '<div class="dtlms-expand-course-result-main-details"></div>';

				$output .= '<div class="dtlms-course-results-main-detail-wrapper">';

					$output .= '<div class="dtlms-column dtlms-one-fifth first">';

						if(has_post_thumbnail($course_id)) {
							$output .= get_the_post_thumbnail($course_id);
						}

					$output .= '</div>';

					$output .= '<div class="dtlms-column dtlms-one-fifth">';

						if($curriculum_details['completed'] == 1) {

							$course_grade_id = $curriculum_details['grade-post-id'];
							$user_percentage = get_post_meta($course_grade_id, 'user-percentage', true);
							$user_percentage = round($user_percentage, 2);

							$output .= '<div class="dtlms-item-progress-details-holder">';
								$output .= '<div class="dtlms-title">'.esc_html__('Your Percentage', 'dtlms-lite').'</div>';
								$output .= '<div class="dtlms-quiz-results">';
									$output .= '<h5><span>'.esc_html( $user_percentage ).'%</span></h5>';
								$output .= '</div>';
								$output .= dtlms_generate_progressbar($user_percentage);
							$output .= '</div>';

						} else if($curriculum_details['submitted'] == 1) {

							$output .= '<div class="dtlms-item-progress-details-holder">
											<div class="dtlms-title">'.esc_html__('Course Progress', 'dtlms-lite').'</div>';
									$output .= '<p>'.esc_html__('Your course have been submitted successfully for evaluation.', 'dtlms-lite').'</p>';
							$output .= '</div>';

						} else {

							$total_curriculum_count = dtlms_course_curriculum_counts($course_id, true);

							$submitted_items_count = dtlms_parse_array_and_count_particular_key($curriculum_details['curriculum'], 'grade-post-id', 0);
							$graded_items_count = dtlms_parse_array_and_count_particular_key($curriculum_details['curriculum'], 'completed', 0);

							if($submitted_items_count > 0) {
								$submitted_percentage = round((($submitted_items_count/$total_curriculum_count)*100), 2);
							} else {
								$submitted_percentage = 0;
							}

							if($graded_items_count > 0) {
								$graded_percentage = round((($graded_items_count/$total_curriculum_count)*100), 2);
							} else {
								$graded_percentage = 0;
							}

							$output .= '<div class="dtlms-item-progress-details-holder">
											<div class="dtlms-title">'.esc_html__('Course Progress', 'dtlms-lite').'</div>';
								$output .= '<div class="dtlms-item-student-submitted-item-details">';
									$output .= sprintf( esc_html__('You have submitted %1$s out of %2$s items.', 'dtlms-lite'), $submitted_items_count, $total_curriculum_count );
									$output .= dtlms_generate_progressbar($submitted_percentage);
								$output .= '</div>';
								$output .= '<div class="dtlms-item-student-completed-item-details">';
									$output .= sprintf( esc_html__('%1$s out of %2$s items are graded and marked as completed.', 'dtlms-lite'), $graded_items_count, $total_curriculum_count );
									$output .= dtlms_generate_progressbar($graded_percentage);
								$output .= '</div>';
							$output .= '</div>';

						}

					$output .= '</div>';

					$output .= '<div class="dtlms-column dtlms-one-fifth">';

						$output .= '<div class="dtlms-badge-certificate-holder">';

							$output .= '<div class="dtlms-title">'.esc_html__('Certificate & Badge', 'dtlms-lite').'</div>';

				            $badge_achieved = get_post_meta($course_grade_id, 'badge-achieved', true);
							$certificate_achieved = get_post_meta($course_grade_id, 'certificate-achieved', true);

							if((in_array('badge', $dtlms_modules) && $badge_achieved == 'true') || (in_array('certificate', $dtlms_modules) && $certificate_achieved == 'true')) {

					            if(in_array('badge', $dtlms_modules) && $badge_achieved == 'true') {
					            	$badge_image_url = get_post_meta($course_id, 'badge-image-url', true);
									$output .= '<img src="'.esc_url( $badge_image_url).'" alt="'.esc_html__('Course Badge', 'dtlms-lite').'" title="'.esc_html__('Course Badge', 'dtlms-lite').'" />';
					            }

					            if(in_array('certificate', $dtlms_modules) && $certificate_achieved == 'true') {

									$certificate_template = get_post_meta($course_id, 'certificate-template', true);

									$output .= '<a href="#" class="dtlms-generate-certificate-content" data-certificateid="'.esc_attr( $certificate_template).'"  data-itemid="'.esc_attr( $course_id ).'" data-gradeid="'.esc_attr( $course_grade_id ).'" data-userid="'.esc_attr( $user_id ).'" onclick="return false;">'.esc_html__('Download Certificate', 'dtlms-lite').'</a>';

					            }

							} else {

								$output .= '<p class="dtlms-note">'.esc_html__('No Records Found!', 'dtlms-lite');

							}

						$output .= '</div>';

					$output .= '</div>';

					$output .= '<div class="dtlms-column dtlms-one-fifth">';

						$output .= '<div class="dtlms-title">'.esc_html__('Instructor Feedback', 'dtlms-lite').'</div>';

						$review_or_feedback = get_post_meta ($course_grade_id, 'review-or-feedback', true);
						if($review_or_feedback != '') {
							$output .= '<div class="dtlms-course-review-holder">'.$review_or_feedback.'</div>';
						} else {
							$output .= '<p class="dtlms-note">'.esc_html__('No Records Found!', 'dtlms-lite');
						}

					$output .= '</div>';

					$output .= '<div class="dtlms-column dtlms-one-fifth">';

						$instructor_singular = apply_filters( 'instructor_label', 'singular' );
						$class_plural = apply_filters( 'class_label', 'plural' );
						$user_specialization = get_the_author_meta('user-specialization', $author_id);

						$total_courses = count_user_posts($author_id , 'dtlms_courses');

						$dtlms_modules = dtlms_instance()->active_modules;
						$dtlms_module_active = (is_array($dtlms_modules) && !empty($dtlms_modules) && in_array('class', $dtlms_modules)) ? true : false;

						$output .= '<div class="dtlms-author-details">
										<div class="dtlms-title">'.esc_html($instructor_singular).'</div>
										<div class="dtlms-author-image">
											'.get_avatar($author_id, 150).'
										</div>
										<div class="dtlms-author-desc">
											<div class="dtlms-author-title">
												<h5>
													<a href="#" rel="author">
														'.get_the_author_meta('display_name', $author_id).'
													</a>
												</h5>
												<span>'.$user_specialization.'</span>
											</div>
											<div class="dtlms-author-meta">';
												if($dtlms_module_active) {
													$total_classes = count_user_posts($author_id , 'dtlms_classes');
													$output .= '<span>'.sprintf( esc_html__( '%1$s %2$s', 'dtlms-lite' ), $total_classes, $class_plural ).'</span>';
												}
												$output .= '<span>'.sprintf( esc_html__( '%1$s Courses', 'dtlms-lite' ), $total_courses ).'</span>
											</div>
										</div>
									</div>';

					$output .= '</div>';

				$output .= '</div>';

			$output .= '</div>';

			$output .= '<div class="dtlms-course-result-popup-container">';

				if($origin != 'class') {
					$output .= dtlms_generate_loader_html(false);
				}

				$output .= '<div class="dtlms-column dtlms-two-fifth first">';

					$output .= '<div class="dtlms-title">'.esc_html__('Course Curriculum', 'dtlms-lite').'</div>';

					$output .= '<div class="dtlms-course-result-curriculum-container">'.dtlms_load_course_curriculum_list($course_id, $user_id).'</div>';

				$output .= '</div>';

				$output .= '<div class="dtlms-column dtlms-three-fifth dtlms-view-curriculum-details-holder"></div>';

			$output .= '</div>';

		$output .= '</div>';

	}

	return $output;

}

add_action( 'wp_ajax_dtlms_load_course_curriculum_list', 'dtlms_load_course_curriculum_list' );
add_action( 'wp_ajax_nopriv_dtlms_load_course_curriculum_list', 'dtlms_load_course_curriculum_list' );
function dtlms_load_course_curriculum_list($dashboard_course_id, $user_id) {

	$output = '';

	if($dashboard_course_id > 0) {
		$course_id = $dashboard_course_id;
	} else {
		$course_id = isset($_REQUEST['course_id']) ? sanitize_text_field( $_REQUEST ['course_id'] ) : -1;
	}

	$user_id = isset($_REQUEST['user_id']) ? sanitize_text_field( $_REQUEST['user_id'] ) : $user_id;

	$curriculum_details = get_user_meta($user_id, $course_id, true);

	// Pagination script Start
	$ajax_call            = (isset($_REQUEST['ajax_call']) && $_REQUEST['ajax_call'] == true) ? true : false;
	$current_page         = isset($_REQUEST['current_page']) ? sanitize_text_field( $_REQUEST['current_page'] ) : 1;
	$offset               = isset($_REQUEST['offset']) ? sanitize_text_field( $_REQUEST['offset'] ) : 0;
	$frontend_postperpage = (dtlms_option('general','frontend-postperpage') != '') ? dtlms_option('general','frontend-postperpage') : 10;
	$post_per_page        = isset($_REQUEST['post_per_page']) ? sanitize_text_field( $_REQUEST['post_per_page'] ) : $frontend_postperpage;

	$function_call = (isset($_REQUEST['function_call']) && $_REQUEST['function_call'] != '') ? sanitize_text_field( $_REQUEST['function_call'] ) : 'dtlms_load_course_curriculum_list';
	$output_div    = (isset($_REQUEST['output_div']) && $_REQUEST['output_div'] != '') ? sanitize_text_field( $_REQUEST['output_div'] ) : 'dtlms-course-result-curriculum-container';
	// Pagination script End

	$course_curriculum = get_post_meta ($course_id, 'course-curriculum', true);
	if(is_array($course_curriculum) && !empty($course_curriculum)) {

		$output .= '<table class="dtlms-course-curriculum-table" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<th scope="col">'.esc_html__('#', 'dtlms-lite').'</th>
							<th scope="col">'.esc_html__('Curriculum', 'dtlms-lite').'</th>
							<th scope="col">'.esc_html__('Type', 'dtlms-lite').'</th>
							<th scope="col">'.esc_html__('Marks', 'dtlms-lite').'</th>
							<th scope="col">'.esc_html__('Percentage', 'dtlms-lite').'</th>
							<th scope="col">'.esc_html__('Status', 'dtlms-lite').'</th>
							<th scope="col">'.esc_html__('Option', 'dtlms-lite').'</th>
						</tr>';

		$i = 1; $total_count = 0;
		$course_curriculum_filtered = array_slice($course_curriculum, $offset, $post_per_page, true);
		foreach($course_curriculum_filtered as $curriculum) {

			if(is_numeric($curriculum)) {

				$grade_type = '';
				$curriculum_grade_id = (isset($curriculum_details['curriculum'][$curriculum]['grade-post-id']) && $curriculum_details['curriculum'][$curriculum]['grade-post-id'] != '') ? $curriculum_details['curriculum'][$curriculum]['grade-post-id'] : -1;

				$status = $status_attribute = $option_html = $marks_obtained = $marks_obtained_percentage = '';
				if((isset($curriculum_details['curriculum'][$curriculum]['completed']) && $curriculum_details['curriculum'][$curriculum]['completed'] == 1)) {

					$status = esc_html__('Completed', 'dtlms-lite');
					$option_html = '<a href="#" onclick="return false;" class="dtlms-view-curriculum-details" data-parentcurriculumid="none" data-curriculumid="'.esc_attr( $curriculum ).'" data-curriculumgradeid="'.esc_attr( $curriculum_grade_id ).'">'.esc_html__('Details','dtlms-lite').'</a>';

					$marks_obtained = isset($curriculum_details['curriculum'][$curriculum]['marks-obtained']) ? $curriculum_details['curriculum'][$curriculum]['marks-obtained'] : '';
					$marks_obtained_percentage = isset($curriculum_details['curriculum'][$curriculum]['marks-obtained-percentage']) ? $curriculum_details['curriculum'][$curriculum]['marks-obtained-percentage'] : '';
					if($marks_obtained_percentage != '') {
						$marks_obtained_percentage = $marks_obtained_percentage.'%';
					}

					$status_attribute = 'class="completed" data-title="'.esc_attr( $status ).'"'; 

				} else if($curriculum_grade_id > 0) {

					$status = esc_html__('Submitted', 'dtlms-lite');
					$status_attribute = 'class="submitted" data-title="'.esc_attr( $status ).'"';

				}

				$maxmark = dtlms_retrieve_curriculum_post_datas($curriculum, 'maxmark');
				if($maxmark != '') {
					$maxmark = ' / '.$maxmark;
				}

				$row_class = 'class="dtlms-curriculum-items dtlms-item-none-'.esc_attr( $curriculum ).'"';

				$output .= '<tr '.$row_class.'>
								<td>'.esc_html( $i ).'</td>
								<td class="dtlms-course-curriculum-item">'.esc_html( get_the_title($curriculum) ).'</td>
								<td class="'.dtlms_retrieve_curriculum_post_datas($curriculum, 'class').'" data-title="'.dtlms_retrieve_curriculum_post_datas($curriculum, 'name').'">'.dtlms_retrieve_curriculum_post_datas($curriculum, 'name').'</td>
								<td>'.$marks_obtained.$maxmark.'</td>
								<td>'.$marks_obtained_percentage.'</td>
								<td '.$status_attribute.'>'.$status.'</td>
								<td>'.$option_html.'</td>
							</tr>';

				$lesson_curriculums = get_post_meta ($curriculum, 'lesson-curriculum', true);

				if(is_array($lesson_curriculums) && !empty($lesson_curriculums)) {

					$j = 1;
					foreach($lesson_curriculums as $lesson_curriculum) {

						if(is_numeric($lesson_curriculum)) {

							$grade_type = '';
							$subcurriculum_grade_id = (isset($curriculum_details['curriculum'][$curriculum]['curriculum'][$lesson_curriculum]['grade-post-id']) && $curriculum_details['curriculum'][$curriculum]['curriculum'][$lesson_curriculum]['grade-post-id'] != '') ? $curriculum_details['curriculum'][$curriculum]['curriculum'][$lesson_curriculum]['grade-post-id'] : -1;

							$status = $status_attribute = $option_html = $marks_obtained = $marks_obtained_percentage = '';
							if((isset($curriculum_details['curriculum'][$curriculum]['curriculum'][$lesson_curriculum]['completed']) && $curriculum_details['curriculum'][$curriculum]['curriculum'][$lesson_curriculum]['completed'] == 1)) {

								$status = esc_html__('Completed', 'dtlms-lite');
								$option_html = '<a href="#" onclick="return false;" class="dtlms-view-curriculum-details" data-parentcurriculumid="'.esc_attr( $curriculum ).'" data-curriculumid="'.esc_attr( $lesson_curriculum ).'" data-curriculumgradeid="'.esc_attr( $subcurriculum_grade_id).'">'.esc_html__('Details','dtlms-lite').'</a>';

								$marks_obtained = isset($curriculum_details['curriculum'][$curriculum]['curriculum'][$lesson_curriculum]['marks-obtained']) ? $curriculum_details['curriculum'][$curriculum]['curriculum'][$lesson_curriculum]['marks-obtained'] : '';
								$marks_obtained_percentage = isset($curriculum_details['curriculum'][$curriculum]['curriculum'][$lesson_curriculum]['marks-obtained-percentage']) ? $curriculum_details['curriculum'][$curriculum]['curriculum'][$lesson_curriculum]['marks-obtained-percentage'] : '';
								if($marks_obtained_percentage != '') {
									$marks_obtained_percentage = $marks_obtained_percentage.'%';
								}

								$status_attribute = 'class="completed" data-title="'.esc_attr( $status).'"';

							} else if($subcurriculum_grade_id > 0) {

								$status = esc_html__('Submitted', 'dtlms-lite');
								$status_attribute = 'class="submitted" data-title="'.esc_attr( $status ).'"';

							}

							$maxmark = dtlms_retrieve_curriculum_post_datas($lesson_curriculum, 'maxmark');
							if($maxmark != '') {
								$maxmark = ' / '.$maxmark;
							}

							$sub_row_class = 'class="dtlms-curriculum-items dtlms-item-'.esc_attr( $curriculum ).'-'.esc_attr( $lesson_curriculum ).'"';

							$output .= '<tr>
											<td>'.esc_html( $i ).'.'.esc_html( $j ).'</td>
											<td class="dtlms-course-curriculum-item">'.esc_html( get_the_title($lesson_curriculum) ).'</td>
											<td class="'.dtlms_retrieve_curriculum_post_datas($lesson_curriculum, 'class').'" data-title="'.dtlms_retrieve_curriculum_post_datas($lesson_curriculum, 'name').'">'.dtlms_retrieve_curriculum_post_datas($lesson_curriculum, 'name').'</td>
											<td>'.$marks_obtained.$maxmark.'</td>
											<td>'.$marks_obtained_percentage.'</td>
											<td '.$status_attribute.'>'.$status.'</td>
											<td>'.$option_html.'</td>
										</tr>';

							$j++;

							$total_count = $total_count + 1;

						} else {

							$output .= '<tr>
								<td></td>
								<td colspan="7" class="section">'.$lesson_curriculum.'</td>
							</tr>';

						}

					}

				}

				$i++;

				$total_count = $total_count + 1;

			} else {

				$output .= '<tr>
					<td colspan="8" class="section">'.$curriculum.'</td>
				</tr>';

			}

		}

		$output .= '</table>';

		// Pagination script Start
		$course_curriculum_count = count($course_curriculum);
		$max_num_pages = ceil($course_curriculum_count / $post_per_page);

		$item_ids['course_id'] = $course_id;
		$item_ids['user_id'] = $user_id;

		$output .= dtlms_ajax_pagination($max_num_pages, $current_page, $function_call, $output_div, $item_ids);
		// Pagination script End
	}

	if($ajax_call) {

		echo $output;
		die();

	} else {

		return $output;

	}

}

add_action( 'wp_ajax_dtlms_view_curriculum_details', 'dtlms_view_curriculum_details' );
add_action( 'wp_ajax_nopriv_dtlms_view_curriculum_details', 'dtlms_view_curriculum_details' );
function dtlms_view_curriculum_details() {

	$curriculum_id       = sanitize_text_field( $_REQUEST['curriculum_id'] );
	$curriculum_grade_id = sanitize_text_field( $_REQUEST['curriculum_grade_id'] );

	$course_id = get_post_meta($curriculum_id, 'dtlms-course-id', true);
	$user_id   = get_post_meta($curriculum_id, 'dtlms-user-id', true);

	$output = '';

 	$output .= '<div class="dtlms-title">'.esc_html__('Individual Curriculum Details', 'dtlms-lite').'</div>';

 	$output .= '<div class="dtlms-curriculum-details-container">';

		$output .= '<div class="dtlms-curriculum-result-intro">';

			$output .= '<h3>'.esc_html( get_the_title($curriculum_id) ).'</h3>';

			$marks_obtained_percentage = get_post_meta($curriculum_grade_id, 'marks-obtained-percentage', true);
			$marks_obtained_percentage = round($marks_obtained_percentage, 2);
			$output .= '<div class="dtlms-curriculum-progress-details-holder">';
				$output .= '<span class="dtlms-progress-bar-title">'.esc_html__('Your Score', 'dtlms-lite').'</span>';
				$output .= '<label>'.esc_html__('% Out of 100', 'dtlms-lite').'</label>';
				$output .= dtlms_generate_progressbar($marks_obtained_percentage);
				$output .= '<span class="dtlms-quiz-score">'.esc_html( $marks_obtained_percentage ).'%</span>';
			$output .= '</div>';

		$output .= '</div>';

	    if(get_post_type($curriculum_id) == 'dtlms_quizzes' || get_post_type($curriculum_id) == 'dtlms_assignments') {

			$output .= apply_filters( 'dtlms_view_curriculum_details_module', '', $curriculum_id, $curriculum_grade_id );

	    } else {

			$output .= '<div class="dtlms-column dtlms-one-column">';

				$review_or_feedback = get_post_meta ($curriculum_grade_id, 'review-or-feedback', true);
				if($review_or_feedback != '') {
					$output .= '<div class="dtlms-curriculum-result-review-holder">
									<div class="dtlms-title">'.esc_html__('Instructor Feedback', 'dtlms-lite').'</div>'.
									'<div class="dtlms-curriculum-result-review-holder-content">'.$review_or_feedback.'</div>'.
								'</div>';
				}

			$output .= '</div>';

	    }

    $output .= '</div>';

    echo $output;

	die();
}
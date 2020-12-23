<?php

function dtlms_statistics_overview_content() {

	$class_singular_label = apply_filters( 'class_label', 'singular' );
	$class_plural_label   = apply_filters( 'class_label', 'plural' );

	$dtlms_modules = dtlms_instance()->active_modules;
	$dtlms_modules = (is_array($dtlms_modules) && !empty($dtlms_modules)) ? $dtlms_modules : array ();

	$output = '';

	$output .= '<div class="dtlms-statistics-container statistics-intro">';

		$output .= '<div class="dtlms-column dtlms-one-column no-space">';

			if(in_array('class', $dtlms_modules)) {
				$output .= do_shortcode('[dtlms_total_items item-type="classes" /]');
			}
			$output .= do_shortcode('[dtlms_total_items item-type="courses" /]');
			$output .= do_shortcode('[dtlms_total_items item-type="lessons" /]');
			if(in_array('quiz', $dtlms_modules)) {
				$output .= do_shortcode('[dtlms_total_items item-type="quizzes" /]');
				$output .= do_shortcode('[dtlms_total_items item-type="questions" /]');
			}
			if(in_array('assignment', $dtlms_modules)) {
				$output .= do_shortcode('[dtlms_total_items item-type="assignments" /]');
			}
			if(in_array('package', $dtlms_modules)) {
				$output .= do_shortcode('[dtlms_total_items item-type="packages" /]');
			}

			$output .= '<table border="0" cellpadding="0" cellspacing="0" class="dtlms-custom-table">';

				$count_users            = count_users();
				$count_student_users    = isset($count_users['avail_roles']['student']) ? $count_users['avail_roles']['student'] : 0;
				$count_instructor_users = isset($count_users['avail_roles']['instructor']) ? $count_users['avail_roles']['instructor'] : 0;

				$output .= '<tr>
					<td><strong>'.esc_html__('Total Students', 'dtlms-lite').'</strong></td>
					<td>'.esc_html( $count_student_users ).'</td>
				</tr>';

				$instructor_label = apply_filters( 'instructor_label', 'plural' );
				$output .= '<tr>
					<td><strong>'.sprintf(esc_html__('Total %s', 'dtlms-lite'), $instructor_label).'</strong></td>
					<td>'.esc_html( $count_instructor_users ).'</td>
				</tr>';

				// Courses Statistics
				$purchased_courses_cnt = $started_courses_cnt = $submitted_courses_cnt = $completed_courses_cnt = $courses_undergoing_cnt = $courses_underevaluation_cnt = $badge_achieved_cnt = $certificate_achieved_cnt = 0;

				$students = get_users ( array ('role' => 'student') );
		        if ( count( $students ) > 0 ) {
		            foreach ($students as $student) {
						$student_id = $student->data->ID;

						$purchased_courses = get_user_meta($student_id, 'purchased_courses', true);
						if(is_array($purchased_courses) && !empty($purchased_courses)) {
							$purchased_courses_cnt = $purchased_courses_cnt + count($purchased_courses);
						} else {
							$purchased_courses = array ();
						}

						$started_courses = get_user_meta($student_id, 'started_courses', true);
						if(is_array($started_courses) && !empty($started_courses)) {
							$started_courses_cnt = $started_courses_cnt + count($started_courses);
						} else {
							$started_courses = array ();
						}

						$submitted_courses = get_user_meta($student_id, 'submitted_courses', true);
						if(is_array($submitted_courses) && !empty($submitted_courses)) {
							$submitted_courses_cnt = $submitted_courses_cnt + count($submitted_courses);
						} else {
							$submitted_courses = array ();
						}

						$completed_courses = get_user_meta($student_id, 'completed_courses', true);
						if(is_array($completed_courses) && !empty($completed_courses)) {
							$completed_courses_cnt = $completed_courses_cnt + count($completed_courses);

							foreach($completed_courses as $completed_course) {

								$curriculum_details = get_user_meta($student_id, $completed_course, true);
								$completed_course_grade_id = isset($curriculum_details['grade-post-id']) ? $curriculum_details['grade-post-id'] : -1;

					            $badge_achieved = get_post_meta($completed_course_grade_id, 'badge-achieved', true);
					            if($badge_achieved == 'true') {
					            	$badge_achieved_cnt++;
					            }

								$certificate_achieved = get_post_meta($completed_course_grade_id, 'certificate-achieved', true);
								if($certificate_achieved == 'true') {
									$certificate_achieved_cnt++;
								}

							}
						} else {
							$completed_courses = array ();
						}

						$courses_undergoing = array_diff($started_courses, $submitted_courses);
						if(is_array($courses_undergoing) && !empty($courses_undergoing)) {
							$courses_undergoing_cnt = $courses_undergoing_cnt + count($courses_undergoing);
						}
						$courses_underevaluation = array_diff($submitted_courses, $completed_courses);
						if(is_array($courses_underevaluation) && !empty($courses_underevaluation)) {
							$courses_underevaluation_cnt = $courses_underevaluation_cnt + count($courses_underevaluation);
						}

		            }
		        }

				$output .= '<tr>
					<td><strong>'.esc_html__('Courses - Total Purchases', 'dtlms-lite').'</strong></td>
					<td>'.esc_html( $purchased_courses_cnt).'</td>
				</tr>';

				$output .= '<tr>
					<td><strong>'.esc_html__('Courses - Under Progress', 'dtlms-lite').'</strong></td>
					<td>'.esc_html( $courses_undergoing_cnt ).'</td>
				</tr>';

				$output .= '<tr>
					<td><strong>'.esc_html__('Courses - Under Evaluation', 'dtlms-lite').'</strong></td>
					<td>'.esc_html( $courses_underevaluation_cnt ).'</td>
				</tr>';

				$output .= '<tr>
					<td><strong>'.esc_html__('Courses - Completed', 'dtlms-lite').'</strong></td>
					<td>'.esc_html( $completed_courses_cnt ).'</td>
				</tr>';

				if(in_array('badge', $dtlms_modules)) {
					$output .= '<tr>
						<td><strong>'.esc_html__('Courses - Badges Issued', 'dtlms-lite').'</strong></td>
						<td>'.esc_html( $badge_achieved_cnt ).'</td>
					</tr>';
				}

				if(in_array('certificate', $dtlms_modules)) {
					$output .= '<tr>
						<td><strong>'.esc_html__('Courses - Certificates Issued', 'dtlms-lite').'</strong></td>
						<td>'.esc_html( $certificate_achieved_cnt ).'</td>
					</tr>';
				}

				// Classes Statistics
				if(in_array('class', $dtlms_modules)) {

					$purchased_classes_cnt = $started_classes_cnt = $submitted_classes_cnt = $completed_classes_cnt = $classes_undergoing_cnt = $classes_underevaluation_cnt = $classes_badge_achieved_cnt = $classes_certificate_achieved_cnt = 0;

					$students = get_users ( array ('role' => 'student') );
					if ( count( $students ) > 0 ) {
						foreach ($students as $student) {

							$student_id = $student->data->ID;

							$purchased_classes = get_user_meta($student_id, 'purchased_classes', true);
							if(is_array($purchased_classes) && !empty($purchased_classes)) {
								$purchased_classes_cnt = $purchased_classes_cnt + count($purchased_classes);
							} else {
								$purchased_classes = array ();
							}

							$started_classes = get_user_meta($student_id, 'started_classes', true);
							if(is_array($started_classes) && !empty($started_classes)) {
								$started_classes_cnt = $started_classes_cnt + count($started_classes);
							} else {
								$started_classes = array ();
							}

							$submitted_classes = get_user_meta($student_id, 'submitted_classes', true);
							if(is_array($submitted_classes) && !empty($submitted_classes)) {
								$submitted_classes_cnt = $submitted_classes_cnt + count($submitted_classes);
							} else {
								$submitted_classes = array ();
							}

							$completed_classes = get_user_meta($student_id, 'completed_classes', true);
							if(is_array($completed_classes) && !empty($completed_classes)) {
								$completed_classes_cnt = $completed_classes_cnt + count($completed_classes);

								foreach($completed_classes as $completed_class) {

									$curriculum_details = get_user_meta($student_id, $completed_class, true);
									$completed_class_grade_id = isset($curriculum_details['grade-post-id']) ? $curriculum_details['grade-post-id'] : -1;

									$badge_achieved = get_post_meta($completed_class_grade_id, 'badge-achieved', true);
									if($badge_achieved == 'true') {
										$classes_badge_achieved_cnt++;
									}

									$certificate_achieved = get_post_meta($completed_class_grade_id, 'certificate-achieved', true);
									if($certificate_achieved == 'true') {
										$classes_certificate_achieved_cnt++;
									}

								}
							} else {
								$completed_classes = array ();
							}

							$classes_undergoing = array_diff($started_classes, $submitted_classes);
							if(is_array($classes_undergoing) && !empty($classes_undergoing)) {
								$classes_undergoing_cnt = $classes_undergoing_cnt + count($classes_undergoing);
							}
							$classes_underevaluation = array_diff($submitted_classes, $completed_classes);
							if(is_array($classes_underevaluation) && !empty($classes_underevaluation)) {
								$classes_underevaluation_cnt = $classes_underevaluation_cnt + count($classes_underevaluation);
							}

						}
					}

					$output .= '<tr>
						<td><strong>'.sprintf( esc_html__( '%1$s - Total Purchases', 'dtlms-lite' ), $class_plural_label ).'</strong></td>
						<td>'.esc_html( $purchased_classes_cnt).'</td>
					</tr>';

					$output .= '<tr>
						<td><strong>'.sprintf( esc_html__( '%1$s - Under Progress', 'dtlms-lite' ), $class_plural_label ).'</strong></td>
						<td>'.esc_html( $classes_undergoing_cnt ).'</td>
					</tr>';

					$output .= '<tr>
						<td><strong>'.sprintf( esc_html__( '%1$s - Under Evaluation', 'dtlms-lite' ), $class_plural_label ).'</strong></td>
						<td>'.esc_html( $classes_underevaluation_cnt ).'</td>
					</tr>';

					$output .= '<tr>
						<td><strong>'.sprintf( esc_html__( '%1$s - Completed', 'dtlms-lite' ), $class_plural_label ).'</strong></td>
						<td>'.esc_html( $completed_classes_cnt ).'</td>
					</tr>';

					if(in_array('badge', $dtlms_modules)) {
						$output .= '<tr>
							<td><strong>'.sprintf( esc_html__( '%1$s - Badges Issued', 'dtlms-lite' ), $class_plural_label ).'</strong></td>
							<td>'.esc_html( $classes_badge_achieved_cnt ).'</td>
						</tr>';
					}

					if(in_array('certificate', $dtlms_modules)) {
						$output .= '<tr>
							<td><strong>'.sprintf( esc_html__( '%1$s - Certificates Issued', 'dtlms-lite' ), $class_plural_label ).'</strong></td>
							<td>'.esc_html( $classes_certificate_achieved_cnt ).'</td>
						</tr>';
					}

				}

			$output .= '</table>';

		$output .= '</div>';

		$output .= '<div class="dtlms-column dtlms-one-column no-space">';
			$output .= '<div class="dtlms-column dtlms-one-third first">';
				$output .= '<h2>'.esc_html__('Total Items - Pie Chart', 'dtlms-lite').'</h2>';
				$output .= do_shortcode('[dtlms_total_items_chart /]');
			$output .= '</div>';
			$output .= '<div class="dtlms-column dtlms-two-third">';
				$output .= '<h2>'.esc_html__('Total Items - Bar Chart', 'dtlms-lite').'</h2>';
				$output .= do_shortcode('[dtlms_total_items_chart chart-type="bar" /]');
			$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="dtlms-column dtlms-one-column first">';
			$output .= '<h2>'.esc_html__('Overall Purchases', 'dtlms-lite').'</h2>';

			$purchases_overview_chart = '[dtlms_purchases_overview_chart include-course-purchases="true"';
				if(in_array('class', $dtlms_modules)) {
					$purchases_overview_chart .= ' include-class-purchases="true"';
				}
				if(in_array('package', $dtlms_modules)) {
					$purchases_overview_chart .= ' include-package-purchases="true"';
				}
			$purchases_overview_chart .= ' enable-instructor-filter="true" include-data="true"]';

			$output .= do_shortcode($purchases_overview_chart);
		$output .= '</div>';
		$output .= '<div class="dtlms-hr-invisible"></div>';
		$output .= '<div class="dtlms-hr-invisible"></div>';

			$output .= '<div class="dtlms-column dtlms-one-half first">';
				$output .= '<h2>'.esc_html__('Course Purchases', 'dtlms-lite').'</h2>';
				$output .= do_shortcode('[dtlms_purchases_overview_chart include-course-purchases="true" enable-instructor-filter="true"]');
			$output .= '</div>';
			$output .= '<div class="dtlms-column dtlms-one-half">';
				$output .= '<h2>'.esc_html__('Instructor Earnings - Over Courses', 'dtlms-lite').'</h2>';
				$output .= do_shortcode('[dtlms_instructor_commission_earnings enable-instructor-filter="true" instructor-earnings="over-item" chart-type="pie" timeline-filter="alltime" include-course-commission="true" include-class-commission="false" include-other-commission="false" include-total-commission="false"]');
			$output .= '</div>';

		$output .= '<div class="dtlms-hr-invisible"></div>';
		$output .= '<div class="dtlms-hr-invisible"></div>';

		if(in_array('class', $dtlms_modules)) {

				$output .= '<div class="dtlms-column dtlms-one-half first">';
					$output .= '<h2>'.sprintf( esc_html__( '%1$s Purchases', 'dtlms-lite' ), $class_singular_label ).'</h2>';
					$output .= do_shortcode('[dtlms_purchases_overview_chart include-class-purchases="true" enable-instructor-filter="true"]');
				$output .= '</div>';
				$output .= '<div class="dtlms-column dtlms-one-half">';
					$output .= '<h2>'.sprintf( esc_html__( 'Instructor Earnings - Over %1$s', 'dtlms-lite' ), $class_plural_label ).'</h2>';
					$output .= do_shortcode('[dtlms_instructor_commission_earnings enable-instructor-filter="true" instructor-earnings="over-item" timeline-filter="alltime" include-course-commission="false" include-class-commission="true" include-other-commission="false" include-total-commission="false"]');
				$output .= '</div>';

			$output .= '<div class="dtlms-hr-invisible"></div>';
			$output .= '<div class="dtlms-hr-invisible"></div>';

		}

			$output .= '<div class="dtlms-column dtlms-one-half first">';
				$output .= '<h2>'.esc_html__('Package Purchases', 'dtlms-lite').'</h2>';
				$output .= do_shortcode('[dtlms_purchases_overview_chart include-package-purchases="true" enable-instructor-filter="true"]');
			$output .= '</div>';
			$output .= '<div class="dtlms-column dtlms-one-half">';
				$output .= '<h2>'.esc_html__('Instructor Earnings - Over Period', 'dtlms-lite').'</h2>';
				$instructor_commission_earnings = '[dtlms_instructor_commission_earnings enable-instructor-filter="true" ';
				if(in_array('class', $dtlms_modules)) {
					$instructor_commission_earnings .= 'include-class-commission="true" ';
				}
				$instructor_commission_earnings .= 'include-other-commission="false" include-total-commission="true"]';
				$output .= do_shortcode($instructor_commission_earnings);
			$output .= '</div>';

		$output .= '</div>';
	$output .= '</div>';

	echo $output;

}

function dtlms_statistics_courses_content() {
	echo do_shortcode('[dtlms_instructor_courses enable-instructor-filter="true" /]');
}

function dtlms_statistics_instructors_content() {
	echo do_shortcode('[dtlms_instructor_added_courses /]');
	echo '<h2>'.esc_html__('Instructor Commission', 'dtlms-lite').'</h2>';
	echo '<div class="dtlms-hr-insivisible"></div>';
	echo do_shortcode('[dtlms_instructor_commissions enable-instructor-filter="true" /]');

}

function dtlms_statistics_packages_content() {
	echo do_shortcode('[dtlms_package_details /]');
}

function dtlms_statistics_students_content() {
	echo do_shortcode('[dtlms_student_courses /]');
}
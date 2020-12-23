<?php
function dtlms_statistics_overview_content() {

	$current_user  = wp_get_current_user();
	$instructor_id = $current_user->ID;

	$class_singular_label = apply_filters( 'class_label', 'singular' );
	$class_plural_label = apply_filters( 'class_label', 'plural' );

	$dtlms_modules = dtlms_instance()->active_modules;
	$dtlms_modules = (is_array($dtlms_modules) && !empty($dtlms_modules)) ? $dtlms_modules : array ();

	$output = '';

	$output .= '<div class="dtlms-statistics-container">';

		$output .= '<div class="dtlms-column dtlms-one-fifth first">';

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

				$purchased_users_cnt      = 0;
				$purchased_users_complete = array ();

				$courses_args = array (
					'post_type'   => 'dtlms_courses',
					'author'      => $instructor_id,
					'post_status' => 'publish'
				);

				$courses = get_posts( $courses_args );
				if(is_array($courses) && !empty($courses)) {
					foreach ( $courses as $course ) {
						setup_postdata( $course );
						$course_id = $course->ID;

						$purchased_users = get_post_meta($course_id, 'purchased_users', true);
						$purchased_users_complete = array_merge_recursive($purchased_users_complete, $purchased_users);

						if(is_array($purchased_users) && !empty($purchased_users)) {
							$purchased_users_cnt = $purchased_users_cnt + count($purchased_users);
						}

					}
				}
				wp_reset_postdata();

				$purchased_users_complete_cnt = count(array_unique($purchased_users_complete));


				$output .= '<tr>
					<td><strong>'.esc_html__('Total Purchases', 'dtlms-lite').'</strong></td>
					<td>'.esc_html( $purchased_users_cnt).'</td>
				</tr>';

				$output .= '<tr>
					<td><strong>'.esc_html__('Total Students In My Courses', 'dtlms-lite').'</strong></td>
					<td>'.esc_html( $purchased_users_complete_cnt ).'</td>
				</tr>';

				if(in_array('badge', $dtlms_modules)) {

					$badges_args = array (
						'meta_key'    => 'badge-achieved',
						'meta_value'  => 'true',
						'post_type'   => 'dtlms_gradings',
						'author'      => $instructor_id,
						'post_status' => 'publish',
					);
					$badges = new WP_Query( $badges_args );
					$badges_count = $badges->found_posts;
					wp_reset_postdata();

					$output .= '<tr>
						<td><strong>'.esc_html__('Total Badges Given', 'dtlms-lite').'</strong></td>
						<td>'.esc_html( $badges_count ).'</td>
					</tr>';
				}

				if(in_array('certificate', $dtlms_modules)) {

					$certificates_args = array (
						'meta_key'    => 'certificate-achieved',
						'meta_value'  => 'true',
						'post_type'   => 'dtlms_gradings',
						'author'      => $instructor_id,
						'post_status' => 'publish',
					);

					$certificates       = new WP_Query( $certificates_args );
					$certificates_count = $certificates->found_posts;
					wp_reset_postdata();

					$output .= '<tr>
						<td><strong>'.esc_html__('Total Certificates Given', 'dtlms-lite').'</strong></td>
						<td>'.esc_html( $certificates_count ).'</td>
					</tr>';
				}

				$gradings_graded_args = array (
					'author'     => $instructor_id,
					'post_type'  => 'dtlms_gradings',
					'meta_query' => array(),
				);

				$gradings_graded_args['meta_query'][] = array (
					'key'     => 'grade-type',
					'value'   => 'course',
					'compare' => '=='
				);

				$gradings_graded_args['meta_query'][] = array (
					'key'     => 'graded',
					'value'   => 'true',
					'compare' => '=='
				);

				$gradings_graded       = new WP_Query( $gradings_graded_args );
				$gradings_graded_count = $gradings_graded->found_posts;
				wp_reset_postdata();

				$output .= '<tr>
					<td><strong>'.esc_html__('Total Courses Evaluated', 'dtlms-lite').'</strong></td>
					<td>'.esc_html( $gradings_graded_count).'</td>
				</tr>';


				$under_gradings_args = array (
					'author'     => $instructor_id,
					'post_type'  => 'dtlms_gradings',
					'meta_query' => array(),
				);

				$under_gradings_args['meta_query'][] = array (
					'key'     => 'grade-type',
					'value'   => 'course',
					'compare' => '=='
				);

				$under_gradings_args['meta_query'][] = array (
					'key'     => 'graded',
					'compare' => 'NOT EXISTS'
				);

				$under_gradings_args['meta_query'][] = array (
					'key'     => 'submitted',
					'value'   => '1',
					'compare' => '=='
				);

				$under_gradings = new WP_Query( $under_gradings_args );
				$under_gradings_count = $under_gradings->found_posts;
				wp_reset_postdata();

				$output .= '<tr>
					<td><strong>'.esc_html__('Total Courses Under Evaluation', 'dtlms-lite').'</strong></td>
					<td>'.esc_html( $under_gradings_count).'</td>
				</tr>';

			$output .= '</table>';

		$output .= '</div>';

		$output .= '<div class="dtlms-column dtlms-four-fifth">';

			$output .= '<div class="dtlms-column dtlms-one-half first">';
				$output .= '<h2>'.esc_html__('Total Items', 'dtlms-lite').'</h2>';
				$output .= do_shortcode('[dtlms_total_items_chart /]');
			$output .= '</div>';
			$output .= '<div class="dtlms-column dtlms-one-half">';
				$output .= '<h2>'.esc_html__('Total Items', 'dtlms-lite').'</h2>';
				$output .= do_shortcode('[dtlms_total_items_chart chart-type="bar" /]');
			$output .= '</div>';

			$output .= '<div class="dtlms-hr-invisible"></div>';

			$output .= '<div class="dtlms-column dtlms-one-column first">';
				$output .= '<h2>'.esc_html__('Overall Purchases', 'dtlms-lite').'</h2>';

				$purchases_overview_chart = '[dtlms_purchases_overview_chart include-course-purchases="true"';
					if(in_array('class', $dtlms_modules)) {
						$purchases_overview_chart .= ' include-class-purchases="true"';
					}
					if(in_array('package', $dtlms_modules)) {
						$purchases_overview_chart .= ' include-package-purchases="true"';
					}
				$purchases_overview_chart .= ' include-data="true"]';

				$output .= do_shortcode($purchases_overview_chart);
			$output .= '</div>';

			$output .= '<div class="dtlms-hr-invisible"></div>';
			$output .= '<div class="dtlms-hr-invisible"></div>';

				$output .= '<div class="dtlms-column dtlms-one-half first">';
					$output .= '<h2>'.esc_html__('Course Purchases', 'dtlms-lite').'</h2>';
					$output .= do_shortcode('[dtlms_purchases_overview_chart include-course-purchases="true"]');
				$output .= '</div>';
				$output .= '<div class="dtlms-column dtlms-one-half">';
					$output .= '<h2>'.esc_html__('Instructor Earnings - Over Courses', 'dtlms-lite').'</h2>';
					$output .= do_shortcode('[dtlms_instructor_commission_earnings enable-instructor-filter="false" instructor-earnings="over-item" chart-type="pie" timeline-filter="alltime" include-class-commission="false" include-other-commission="false" include-total-commission="false"]');
				$output .= '</div>';

			$output .= '<div class="dtlms-hr-invisible"></div>';
			$output .= '<div class="dtlms-hr-invisible"></div>';

			if(in_array('class', $dtlms_modules)) {

				$output .= '<div class="dtlms-column dtlms-one-half first">';
					$output .= '<h2>'.sprintf( esc_html__( '%1$s Purchases', 'dtlms-lite' ), $class_singular_label ).'</h2>';
					$output .= do_shortcode('[dtlms_purchases_overview_chart include-class-purchases="true"]');
				$output .= '</div>';
				$output .= '<div class="dtlms-column dtlms-one-half">';
					$output .= '<h2>'.sprintf( esc_html__( 'Instructor Earnings - Over %1$s', 'dtlms-lite' ), $class_plural_label ).'</h2>';
					$output .= do_shortcode('[dtlms_instructor_commission_earnings enable-instructor-filter="false" instructor-earnings="over-item" timeline-filter="alltime" include-course-commission="false" include-class-commission="true" include-other-commission="false" include-total-commission="false"]');
				$output .= '</div>';

				$output .= '<div class="dtlms-hr-invisible"></div>';
				$output .= '<div class="dtlms-hr-invisible"></div>';

			}

			$output .= '<div class="dtlms-column dtlms-one-column first">';
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

function dtlms_statistics_mycourses_content() {

	$output = '';
	$output .= do_shortcode('[dtlms_instructor_courses enable-instructor-filter="false" /]');

	echo $output;

}

function dtlms_statistics_commissions_content() {

	$output = '';
	$output .= do_shortcode('[dtlms_instructor_commissions enable-instructor-filter="false" /]');

	echo $output;
}
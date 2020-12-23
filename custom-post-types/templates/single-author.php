<?php get_header('dtlms-lite'); ?>

	<?php
	/**
	* dtlms_before_main_content hook.
	*/
	do_action( 'dtlms_before_main_content' );
	?>

		<?php
		/**
		* dtlms_before_content hook.
		*/
		do_action( 'dtlms_before_content' );
		?>


			<?php

			$author_id = get_queried_object_id();

			$user_meta  = get_userdata($author_id);
			$user_roles = $user_meta->roles;

			if(in_array('instructor', $user_roles)) {

				echo '<div class="dtlms-column dtlms-one-third first">';

					echo do_shortcode('[dtlms_instructor_list include="'.esc_attr($author_id).'" columns="1" type="type2" /]');

				echo '</div>';

				echo '<div class="dtlms-column dtlms-two-third">';

					// Classes
					$dtlms_modules = dtlms_instance()->active_modules;
					$dtlms_module_active = (is_array($dtlms_modules) && !empty($dtlms_modules) && in_array('class', $dtlms_modules)) ? true : false;

					if($dtlms_module_active) {
						$classes_args = array (
							'post_type'      => 'dtlms_classes',
							'posts_per_page' => -1,
							'author__in'     => $author_id,
							'orderby'        => 'post_date',
							'order'          => 'DESC'
						);

						$class_array = get_posts( $classes_args );

						if(is_array($class_array) && !empty($class_array)) {

							echo '<h5 class="border-title">'.esc_html__('Classes Handling', 'dtlms-lite').'<span></span></h5>';

							echo '<table>
									<thead>
									<tr>
										<th>'.esc_html__('Class Name','dtlms-lite').'</th>
										<th>'.esc_html__('Class Type','dtlms-lite').'</th>
									</tr>
									</thead>
									<tbody>';

										foreach($class_array as $class_item) {

											$class_id = $class_item->ID;
											$class_type = get_post_meta($class_id, 'dtlms-class-type', true);

											echo '<tr>
													<td><a href="'.esc_url( get_permalink($class_id) ).'">'.esc_html( $class_item->post_title ).'</a></td>
													<td>'.esc_html( $class_type ).'</td>
												</tr>';

										}

								echo '</tbody>';
							echo '</table>';


						} else {

							echo '<h5 class="border-title">'.esc_html__('Classes Handling', 'dtlms-lite').'<span></span></h5>';

							echo '<table>
									<thead>
									<tr>
										<th>'.esc_html__('Class Name','dtlms-lite').'</th>
										<th>'.esc_html__('Class Type','dtlms-lite').'</th>
									</tr>
									</thead>
									<tbody>
										<tr>
											<td colspan="2">'.esc_html__('No Records found!','dtlms-lite').'</td>
										</tr>
									</tbody>
								</table>';

						}

					}

					// Courses
					$courses_args = array (
						'post_type'      => 'dtlms_courses',
						'posts_per_page' => -1,
						'author__in'     => $author_id,
						'orderby'        => 'post_date',
						'order'          => 'DESC'
					);

					$course_array = get_posts( $courses_args );

					if(is_array($course_array) && !empty($course_array)) {

						echo '<h5 class="border-title">'.esc_html__('Courses Handling', 'dtlms-lite').'<span></span></h5>';

						echo '<table>
								<thead>
								  <tr>
								  	<th>'.esc_html__('Course Name','dtlms-lite').'</th>
									<th>'.esc_html__('Curriculum(s)','dtlms-lite').'</th>
								  </tr>
								</thead>
								<tbody>';

									foreach($course_array as $course_item) {

										$course_id = $course_item->ID;
										$total_curriculum_count = dtlms_course_curriculum_counts($course_id, true);

										echo '<tr>
											<td>
												<a href="'.esc_url( get_permalink($course_id) ).'">'.esc_html( $course_item->post_title ).'</a>
											</td>
											<td>'.esc_html( $total_curriculum_count ).'</td>
										</tr>';
									}

							echo '</tbody>';
						echo '</table>';

					} else {

						echo '<h5 class="border-title">'.esc_html__('Courses Handling', 'dtlms-lite').'<span></span></h5>';

						echo '<table>
								<thead>
								  <tr>
								  	<th>'.esc_html__('Course Name','dtlms-lite').'</th>
									<th>'.esc_html__('Curriculum(s)','dtlms-lite').'</th>
								  </tr>
								</thead>
								<tbody>
									<tr>
										<td colspan="2">'.esc_html__('No Records found!','dtlms-lite').'</td>
									</tr>
								</tbody>
							</table>';

					}

				echo '</div>';

			}

			?>


		<?php
		/**
		* dtlms_after_content hook.
		*/
		do_action( 'dtlms_after_content' );
		?>

	<?php
	/**
	* dtlms_after_main_content hook.
	*/
	do_action( 'dtlms_after_main_content' );
	?>

<?php get_footer('dtlms-lite'); ?>
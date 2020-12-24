<?php
/**
 * Generate Assignement Page Contents
 */
if(!function_exists('dtlms_generate_assignment_page_contents')) {
    function dtlms_generate_assignment_page_contents($user_id, $course_id, $assignment_id, $parent_curriculum_id) {

        $assignment_data = get_post($assignment_id);
        $author_id = $assignment_data->post_author;

        $assignment_title = get_the_title($assignment_id);
        $assignment_permalink = get_permalink($assignment_id);

        $purchased_courses = get_user_meta($user_id, 'purchased_courses', true);
        $purchased_courses = (is_array($purchased_courses) && !empty($purchased_courses)) ? $purchased_courses : array();

        $started_courses = get_user_meta($user_id, 'started_courses', true);
        $started_courses = (is_array($started_courses) && !empty($started_courses)) ? $started_courses : array();

        $submitted_courses = get_user_meta($user_id, 'submitted_courses', true);
        $submitted_courses = (is_array($submitted_courses) && !empty($submitted_courses)) ? $submitted_courses : array();

        $assignment_subtitle = get_post_meta($assignment_id, 'assignment-subtitle', true);

        $curriculum_details = get_user_meta($user_id, $course_id, true);


        if($parent_curriculum_id > 0) {
            $curriculum_status = (isset($curriculum_details['curriculum'][$parent_curriculum_id]['curriculum'][$assignment_id]['completed']) && $curriculum_details['curriculum'][$parent_curriculum_id]['curriculum'][$assignment_id]['completed'] == 1) ? true : false;
            $assignment_grade_id = (isset($curriculum_details['curriculum'][$parent_curriculum_id]['curriculum'][$assignment_id]['grade-post-id']) && $curriculum_details['curriculum'][$parent_curriculum_id]['curriculum'][$assignment_id]['grade-post-id'] > 0) ? $curriculum_details['curriculum'][$parent_curriculum_id]['curriculum'][$assignment_id]['grade-post-id'] : -1;
        } else {
            $curriculum_status = (isset($curriculum_details['curriculum'][$assignment_id]['completed']) && $curriculum_details['curriculum'][$assignment_id]['completed'] == 1) ? true : false;
            $assignment_grade_id = (isset($curriculum_details['curriculum'][$assignment_id]['grade-post-id']) && $curriculum_details['curriculum'][$assignment_id]['grade-post-id'] > 0) ? $curriculum_details['curriculum'][$assignment_id]['grade-post-id'] : -1;
        }

        if( defined( 'DOING_AJAX' ) && DOING_AJAX && class_exists('WPBMap') && method_exists('WPBMap', 'addAllMappedShortcodes') ) {
            WPBMap::addAllMappedShortcodes();
        }

        $output = '';

        $output .= '<div id="dtlms-course-curriculum-popup" class="dtlms-course-curriculum-popup-assignment">';

                $curriculum_image_url = '';
                if(has_post_thumbnail($assignment_id)) {
                    $image_url = wp_get_attachment_image_src(get_post_thumbnail_id($assignment_id), 'full');
                    $curriculum_image_url = 'style="background-image:url('.esc_url($image_url[0]).');"';
                }

                $output .= '<div class="dtlms-course-curriculum-popup-header" '.$curriculum_image_url.'>';

                    $output .= '<div class="dtlms-curriculum-intro">';

                        $output .= '<div class="dtlms-column dtlms-one-column first">';

                            $output .= '<div class="dtlms-curriculum-intro-details">';

                                $output .= '<h2>'.esc_html( $assignment_title ).'</h2>';

                                $output .= '<div class="dtlms-curriculum-intro-details-meta">';

                                    $duration            = get_post_meta ( $assignment_id, 'duration', true );
                                    $duration_parameter  = get_post_meta ( $assignment_id, 'duration-parameter', true );
                                    $duration_in_seconds = ($duration * $duration_parameter);

                                    $curriculum_duration = dtlms_convert_seconds_to_readable_format($duration_in_seconds, 'style4');

                                    $output .= '<span class="dtlms-curriculum-duration">'.esc_html( $curriculum_duration ).'</span>';

                                    if($curriculum_status) {
                                        $output .= '<span class="dtlms-completed">'.esc_html__('Completed', 'dtlms-lite').'</span>';
                                    } else if(in_array($course_id, $submitted_courses)) {
                                        $output .= '<span class="dtlms-underevaluation">'.esc_html__('Under Evaluation', 'dtlms-lite').'</span>';
                                    }

                                $output .= '</div>';

                            $output .= '</div>';

                            if($assignment_subtitle != '') {
                                $output .= '<h3>'.esc_html( $assignment_subtitle ).'</h3>';
                            }

                        $output .= '</div>';

                    $output .= '</div>';

                    $output .= '<div class="dtlms-refresh-course-curriculum"></div>';
                    $output .= '<div class="dtlms-close-course-curriculum-popup"></div>';

                $output .= '</div>';

                $output .= '<div class="dtlms-course-curriculum-popup-container">';

                    $output .= '<div class="dtlms-column dtlms-one-fifth first">';

                        $output .= '<div class="dtlms-curriculum-details">';

                            $output .= '<div class="dtlms-curriculum-detailed-links">';
                                $output .= dtlms_generate_course_curriculum($user_id, $course_id, 'style3', false, $assignment_id);
                            $output .= '</div>';

                        $output .= '</div>';

                    $output .= '</div>';

                    $output .= '<div class="dtlms-column dtlms-four-fifth">';

                        $output .= '<div class="dtlms-curriculum-content-holder">';

                            $output .= '<div class="dtlms-assignment-details-container">';

                                $drip_feed_enable = dtlms_course_drip_feed_check($course_id, $assignment_id, $user_id);

                                if($drip_feed_enable == 'true') {

                                    if(class_exists( '\Elementor\Plugin' ) && \Elementor\Plugin::$instance->db->is_built_with_elementor($assignment_id)) {
                                        $output .= \Elementor\Plugin::$instance->frontend->get_builder_content( $assignment_id );
                                    } else {
                                        $output .= do_shortcode(get_post_field('post_content', $assignment_id));
                                    }

                                    if($curriculum_status) {

                                        $output .= '<a class="dtlms-button dtlms-view-assignment large filled" onclick="return false;" data-assignmentgradeid="'.esc_attr( $assignment_grade_id ).'" data-assignmentid="'.esc_attr( $assignment_id ).'">'.esc_html__('View Your Submission','dtlms-lite').'</a>';

                                    } else {

                                        if (in_array($course_id, $started_courses) && !in_array($course_id, $submitted_courses)) {

                                            if($assignment_grade_id > 0) {

                                                $output .= '<a class="dtlms-button large filled" id="dtlms-upload-assignment" onclick="return false;" data-uploadassignment-nonce="'.wp_create_nonce('upload_assignment_'.$assignment_id.'_'.$user_id).'" data-courseid="'.esc_attr( $course_id ).'" data-userid="'.esc_attr( $user_id ).'"  data-lessonid="-1" data-quizid="-1" data-assignmentid="'.esc_attr( $assignment_id ).'" data-authorid="'.esc_attr( $author_id ).'" data-assignmentgradeid="'.esc_attr(  $assignment_grade_id ).'">'.esc_html__('Reupload Assignment','dtlms-lite').'</a>';
                                                $output .= '<div class="dtlms-info-box">'.esc_html__('Your assignment have been submitted and it will be graded soon!', 'dtlms-lite').'</div>';

                                            } else {

                                                $output .= '<a class="dtlms-button large filled" id="dtlms-upload-assignment" onclick="return false;" data-uploadassignment-nonce="'.wp_create_nonce('upload_assignment_'.$assignment_id.'_'.$user_id).'" data-courseid="'.esc_attr( $course_id ).'" data-userid="'.esc_attr( $user_id ).'"  data-lessonid="-1" data-quizid="-1" data-assignmentid="'.esc_attr( $assignment_id ).'" data-authorid="'.esc_attr( $author_id ).'"data-assignmentgradeid="-1" data-parentcurriculumid="'.esc_attr( $parent_curriculum_id ).'">'.esc_html__('Upload Assignment','dtlms-lite').'</a>';

                                            }

                                        }

                                    }

                                } else {

                                    $drip_date = dtlms_format_datetime($drip_feed_enable, get_option('date_format').' '.get_option('time_format'), false);
                                    $output .= sprintf( esc_html__('This assignment will be available on %1$s', 'dtlms-lite'), '<strong>'.$drip_date.'</strong>' );

                                    $countdown_date = dtlms_format_datetime($drip_feed_enable, get_option('date_format').' '.get_option('time_format'), false);
                                    $output .= dtlms_generate_countdown_html($countdown_date, $assignment_id, $parent_curriculum_id);

                                }

                            $output .= '</div>';

                        $output .= '</div>';

                    $output .= '</div>';

                    $output .= dtlms_generate_loader_html(false);

                $output .= '</div>';

        $output .= '</div>';

        echo $output;

        die();

    }
}

/**
 * Upload Assignment
 */
if(!function_exists('dtlms_upload_assignment')) {
    function dtlms_upload_assignment() {

        $uploadassignment_nonce = dtlms_recursive_sanitize_text_field( $_POST['uploadassignment_nonce'] );
        $course_id              = dtlms_recursive_sanitize_text_field( $_POST['course_id'] );
        $lesson_id              = dtlms_recursive_sanitize_text_field( $_POST['lesson_id'] );
        $quiz_id                = dtlms_recursive_sanitize_text_field( $_POST['quiz_id'] );
        $assignment_id          = dtlms_recursive_sanitize_text_field( $_POST['assignment_id'] );
        $user_id                = dtlms_recursive_sanitize_text_field( $_POST['user_id'] );
        $author_id              = dtlms_recursive_sanitize_text_field( $_POST['author_id'] );
        $assignment_grade_id    = dtlms_recursive_sanitize_text_field( $_POST['assignment_grade_id'] );
        $parent_curriculum_id   = dtlms_recursive_sanitize_text_field( $_POST['parent_curriculum_id'] );

        $output = '';

        if(isset($uploadassignment_nonce) && wp_verify_nonce($uploadassignment_nonce, 'upload_assignment_'.$assignment_id.'_'.$user_id)) {

            $assignment_enable_textarea   = get_post_meta ( $assignment_id, 'assignment-enable-textarea',true);
            $assignment_enable_attachment = get_post_meta ( $assignment_id, 'assignment-enable-attachment', true);

            if($assignment_grade_id > 0) {
                $output .= dtlms_view_assignment_render_html($assignment_grade_id, $assignment_id, true);
                $output .= '<div class="dtlms-title">'.esc_html__('Reupload Assignment : ', 'dtlms-lite').'</div>';
                $output .= '<span class="dtlms-note">';
                    $output .= '<ul>';
                        $output .= '<li>'.esc_html__('Please note your previous submission will be deleted, when you reupload the assignment.', 'dtlms-lite').'</li>';
                        $output .= '<li>'.esc_html__('Please note all the below fields are mandatory.', 'dtlms-lite').'</li>';
                    $output .= '</ul>';
                $output .= '</span>';

            } else {
                $output .= '<div class="dtlms-title">'.esc_html__('Upload Assignment : ', 'dtlms-lite').'</div>';

                $output .= '<span class="dtlms-note">';
                    $output .= '<ul>';
                        $output .= '<li>'.esc_html__('Please note all the below fields are mandatory.', 'dtlms-lite').'</li>';
                    $output .= '</ul>';
                $output .= '</span>';
            }

            if(isset($assignment_enable_attachment) && $assignment_enable_attachment != '') {

                $assignment_attachment_type = get_post_meta ( $assignment_id, 'assignment-attachment-type', true);
                if(isset($assignment_attachment_type) && $assignment_attachment_type != '') {
                    $output .= '<div class="dtlms-assignment-file-types">';
                        $output .= '<h6>'.esc_html__('Allowed File Types : ', 'dtlms-lite').'</h6>';
                        $output .= '<ul class="assignment-file-types">';
                        foreach($assignment_attachment_type as $assignment) {
                            $output .= '<li><span>.</span>'.apply_filters( 'dt_assignment_attachment_type', $assignment ).'</li>';
                        }
                        $output .= '</ul>';
                    $output .= '</div>';
                }
                $assignment_attachment_size = get_post_meta ( $assignment_id, 'assignment-attachment-size', true);
                if(isset($assignment_attachment_size) && $assignment_attachment_size != '') {
                    $output .= '<div class="dtlms-assignment-file-size">';
                        $output .= '<h6>'.esc_html__('Maximum File Upload Size : ', 'dtlms-lite').'<span>'.$assignment_attachment_size.esc_html__('MB', 'dtlms-lite').'</span></h6>';
                    $output .= '</div>';
                }

            }

            $output .= '<form method="post" class="formAssignment" name="formAssignment" enctype="multipart/form-data">';

                if(isset($assignment_enable_textarea) && $assignment_enable_textarea != '') {
                    $output .= '<h6>'.esc_html__('Notes :', 'dtlms-lite').'</h6>';
                    $output .= '<textarea class="assignment-textarea" name="assignment-textarea"></textarea>';
                }

                if(isset($assignment_enable_attachment) && $assignment_enable_attachment != '') {

                    $output .= '<div class="dtlms-upload-assignment-holder">';
                        $output .= '<div class="dtlms-upload-assignment">';
                            $output .= '<h6>'.esc_html__('Upload Assignment :', 'dtlms-lite').'</h6>';
                            $output .= '<input class="assignment-attachment" name="assignment-attachment[]" type="file">';
                            $output .= '<span class="dtlms-remove-upload-assignment-field"></span>';
                        $output .= '</div>';
                    $output .= '</div>';

                    $output .= '<a href="#" class="dtlms-add-upload-assignment-field">'.esc_html__('Add Field', 'dtlms-lite').'</a>';
                    $output .= '<div id="dtlms-upload-assignment-section-to-clone" class="hidden">
                                    <h6>'.esc_html__('Upload Assignment :', 'dtlms-lite').'</h6>
                                    <input id="assignment-attachment" type="file">
                                    <span class="dtlms-remove-upload-assignment-field"></span>
                                </div>';

                }


                if((isset($assignment_enable_textarea) && $assignment_enable_textarea != '') || (isset($assignment_enable_attachment) && $assignment_enable_attachment != '')) {

                    // Open the next locked curriculum item
                    $next_curriculum_id = -1;
                    $enable_next_curriculum = 'false';

                    $free_item = get_post_meta ( $assignment_id, 'free-assignment', true );
                    if(!$free_item) {
                        $curriculum_completion_lock = get_post_meta($course_id, 'curriculum-completion-lock', true);
                        if($curriculum_completion_lock == 'true') {
                            $next_curriculum_id = dtlms_get_course_next_curriculum_id($course_id, $assignment_id, $parent_curriculum_id);
                            $open_curriculum_on_submission = get_post_meta($course_id, 'open-curriculum-on-submission', true);
                            if($open_curriculum_on_submission == 'true') {
                                $enable_next_curriculum = 'true';
                            }
                        }
                    }

                    //if($assignment_grade_id > 0) {
                        $output .= '<div class="dtlms-assignment-errors dtlms-error-box hidden">'.'<strong>'.esc_html__('ERROR: ', 'dtlms-lite').'</strong>'.esc_html__('Please check allowed file types and allowed file size for the attachment.', 'dtlms-lite').esc_html__('Please make sure Notes field is not empty.', 'dtlms-lite').'</div>';
                    //}

                    $output .= '<a href="#" class="dtlms-button dtlms-submit-assignment large" data-submitassignment-nonce="'.wp_create_nonce('submit_assignment_'.$assignment_id.'_'.$user_id).'" data-courseid="'.esc_attr( $course_id ).'" data-userid="'.esc_attr( $user_id ).'"  data-lessonid="-1" data-quizid="-1" data-assignmentid="'.esc_attr( $assignment_id ).'" data-authorid="'.esc_attr( $author_id ).'"data-parentcurriculumid="'.esc_attr( $parent_curriculum_id ).'" data-nextcurriculumid="'.esc_attr( $next_curriculum_id ).'" data-enablenextcurriculum="'.esc_attr( $enable_next_curriculum ).'">'.esc_html__('Submit Assignment', 'dtlms-lite').'</a>';

                }

            $output .= '</form>';

        }

        echo $output;

        wp_die();

    }
    add_action( 'wp_ajax_dtlms_upload_assignment', 'dtlms_upload_assignment' );
    add_action( 'wp_ajax_nopriv_dtlms_upload_assignment', 'dtlms_upload_assignment' );
}

/**
 * View Assignment
 */
if(!function_exists('dtlms_view_assignment')) {
    function dtlms_view_assignment() {

        $assignment_grade_id = (isset($_REQUEST['assignment_grade_id']) && !empty($_REQUEST['assignment_grade_id'])) ? dtlms_recursive_sanitize_text_field( $_REQUEST['assignment_grade_id'] ): -1;
        $assignment_id       = (isset($_REQUEST['assignment_id']) && !empty($_REQUEST['assignment_id'])) ? dtlms_recursive_sanitize_text_field( $_REQUEST['assignment_id'] ): -1;

        $output = dtlms_view_assignment_render_html($assignment_grade_id, $assignment_id, true);

        echo $output;
        wp_die();

    }
    add_action( 'wp_ajax_dtlms_view_assignment', 'dtlms_view_assignment' );
    add_action( 'wp_ajax_nopriv_dtlms_view_assignment', 'dtlms_view_assignment' );
}

if(!function_exists('dtlms_view_assignment_render_html')) {
    function dtlms_view_assignment_render_html($assignment_grade_id, $assignment_id, $show_grade) {

        $assignment_notes   = get_post_meta ( $assignment_grade_id, 'assignment-notes', true);
        $attachment_id      = get_post_meta ( $assignment_grade_id, 'attachment-id', true);
        $attachment_name    = get_post_meta ( $assignment_grade_id, 'attachment-name', true);
        $review_or_feedback = get_post_meta ( $assignment_grade_id, 'review-or-feedback', true);

        $output = '<div class="dtlms-title">'.esc_html__('Your Submission', 'dtlms-lite').'</div>';

        $marks_obtained = get_post_meta ($assignment_grade_id, 'marks-obtained', true);

        if($show_grade) {
            if($marks_obtained != '' && $marks_obtained >= 0) {
                $assignment_maximum_mark = get_post_meta ($assignment_id, 'assignment-maximum-mark', true);
                $output .= '<h6 class="dtlms-assignment-score">'.sprintf( esc_html__('You have scored %1$s out of %2$s', 'dtlms-lite'), $marks_obtained, $assignment_maximum_mark ).'</h6>';
            }
        }

        $output .= '<ul class="dtlms-assignment-submission">';

            $output .= '<li>';
                $output .= '<div class="dtlms-column dtlms-one-fifth first">';
                    $output .= esc_html__('Notes', 'dtlms-lite');
                $output .= '</div>';
                $output .= '<div class="dtlms-column dtlms-four-fifth">';
                    if(isset($assignment_notes) && $assignment_notes != '') {
                        $output .= nl2br($assignment_notes);
                    } else {
                        $output .= esc_html__('No notes found!', 'dtlms-lite');
                    }
                $output .= '</div>';
            $output .= '</li>';

            $output .= '<li>';
                $output .= '<div class="dtlms-column dtlms-one-fifth first">';
                    $output .= esc_html__('Attachments', 'dtlms-lite');
                $output .= '</div>';
                $output .= '<div class="dtlms-column dtlms-four-fifth">';
                    if(is_array($attachment_id) && !empty($attachment_id)) {
                        $output .= '<ul>';
                            $i = 0;
                            foreach($attachment_id as $attachmentid) {
                                $output .= '<li>';
                                    $output .= '<span>'.esc_html( $attachment_name[$i] ).'</span>';
                                    $output .= '<a href="'.esc_url( wp_get_attachment_url( $attachmentid ) ).'" target="_blank">'.esc_html__('View Attachment', 'dtlms-lite').'</a>';
                                $output .= '</li>';
                                $i++;
                            }
                        $output .= '</ul>';
                    } else {
                        $output .= esc_html__('No attachments found!', 'dtlms-lite');
                    }
                $output .= '</div>';
            $output .= '</li>';

            $output .= '<li>';
                $output .= '<div class="dtlms-column dtlms-one-fifth first">';
                    $output .= esc_html__('Feedback or Review', 'dtlms-lite');
                $output .= '</div>';
                $output .= '<div class="dtlms-column dtlms-four-fifth">';
                    if(isset($review_or_feedback) && $review_or_feedback != '') {
                        $output .= nl2br($review_or_feedback);
                    } else {
                        $output .= esc_html__('No reviews found!', 'dtlms-lite');
                    }
                $output .= '</div>';
            $output .= '</li>';

        $output .= '</ul>';

        return $output;

    }
}

/**
 * Submit Assignment
 */
if(!function_exists('dtlms_submit_assignment')) {
    function dtlms_submit_assignment() {

        $submitassignment_nonce = dtlms_recursive_sanitize_text_field( $_POST['submitassignment_nonce'] );
        $course_id              = dtlms_recursive_sanitize_text_field( $_POST['course_id'] );
        $user_id                = dtlms_recursive_sanitize_text_field( $_POST['user_id'] );
        $lesson_id              = dtlms_recursive_sanitize_text_field( $_POST['lesson_id'] );
        $quiz_id                = dtlms_recursive_sanitize_text_field( $_POST['quiz_id'] );
        $assignment_id          = dtlms_recursive_sanitize_text_field( $_POST['assignment_id'] );
        $author_id              = dtlms_recursive_sanitize_text_field( $_POST['author_id'] );
        $parent_curriculum_id   = dtlms_recursive_sanitize_text_field( $_POST['parent_curriculum_id'] );
        $next_curriculum_id     = dtlms_recursive_sanitize_text_field( $_POST['next_curriculum_id'] );

        if(isset($submitassignment_nonce) && wp_verify_nonce($submitassignment_nonce, 'submit_assignment_'.$assignment_id.'_'.$user_id)) {

            $assignment_enable_textarea   = get_post_meta ( $assignment_id, 'assignment-enable-textarea',true);
            $assignment_enable_attachment = get_post_meta ( $assignment_id, 'assignment-enable-attachment', true);

            $error = false;

            if(isset($assignment_enable_attachment) && $assignment_enable_attachment != '') {

                require_once(ABSPATH . 'wp-admin/includes/image.php');
                require_once(ABSPATH . 'wp-admin/includes/file.php');
                require_once(ABSPATH . 'wp-admin/includes/media.php');

                $title = get_the_title($assignment_id);

                $attachment_types = dtlms_allowed_filetypes();

                $assignment_attachment_type = get_post_meta ( $assignment_id, 'assignment-attachment-type', true);
                $assignment_attachment_size = get_post_meta ( $assignment_id, 'assignment-attachment-size',true);

                $assignment_attachment_names = dtlms_recursive_sanitize_text_field( $_FILES['assignment-attachment']['name'] );
                $assignment_attachment_sizes = dtlms_recursive_sanitize_text_field( $_FILES['assignment-attachment']['size'] );

                if(is_array($assignment_attachment_names) && !empty($assignment_attachment_names[0])) {

                    $i = 0;
                    foreach($assignment_attachment_names as $assignment_attachment_name) {

                        $fileName = $assignment_attachment_name;
                        $fileInfo = pathinfo($fileName);
                        $fileExtension = strtolower($fileInfo['extension']);
                        $fileSize = $assignment_attachment_sizes[$i];

                        $error = false;

                        if(isset($assignment_attachment_type) && $assignment_attachment_type != '') {

                            if(!in_array($fileExtension, $assignment_attachment_type)) {
                                $error = true;
                            }

                        }

                        if(isset($assignment_attachment_size) && $assignment_attachment_size != '') {

                            if($fileSize > ($assignment_attachment_size * 1048576)) {
                                $error =  true;
                            }

                        }

                        $i++;

                    }

                }

            }

            if(isset($assignment_enable_textarea) && $assignment_enable_textarea != '') {
                if(empty($_POST['assignment-textarea'])) {
                    $error =  true;
                }
            }

            if($error) {
                echo 'error';
                die();
            }


            // Update gradings
            $curriculum_details = get_user_meta($user_id, $course_id, true);
            if(isset($curriculum_details['curriculum'][$assignment_id]['temp-grade-post-id']) && $curriculum_details['curriculum'][$assignment_id]['temp-grade-post-id'] > 0) {
                $assignment_grade_id = $curriculum_details['curriculum'][$assignment_id]['temp-grade-post-id'];
                unset($curriculum_details['curriculum'][$assignment_id]['temp-grade-post-id']);
                $curriculum_details['curriculum'][$assignment_id]['grade-post-id'] = $assignment_grade_id;
                delete_post_meta($assignment_grade_id, 'temp-grade-post-id');
            } else {
                $assignment_grade_id = (isset($curriculum_details['curriculum'][$assignment_id]['grade-post-id']) && $curriculum_details['curriculum'][$assignment_id]['grade-post-id'] > 0) ? $curriculum_details['curriculum'][$assignment_id]['grade-post-id'] : -1;
            }

            if($assignment_grade_id < 0) {

                $course_grade_id = isset($curriculum_details['grade-post-id']) ? $curriculum_details['grade-post-id'] : -1;

                if($parent_curriculum_id > 0) {
                    if(isset($curriculum_details['curriculum'][$parent_curriculum_id]['grade-post-id']) && $curriculum_details['curriculum'][$parent_curriculum_id]['grade-post-id'] != '') {
                        $parent_grade_id = $curriculum_details['curriculum'][$parent_curriculum_id]['grade-post-id'];
                    } else if(isset($curriculum_details['curriculum'][$parent_curriculum_id]['temp-grade-post-id']) && $curriculum_details['curriculum'][$parent_curriculum_id]['temp-grade-post-id'] != '') {
                        $parent_grade_id = $curriculum_details['curriculum'][$parent_curriculum_id]['temp-grade-post-id'];
                    }
                } else {
                    $parent_grade_id = $course_grade_id;
                }

                if($parent_grade_id == '') {
                    $parent_grade_id = dtlms_insert_parent_grade_post($course_id, $course_grade_id, $user_id, $parent_curriculum_id, $author_id);
                    $curriculum_details = get_user_meta($user_id, $course_id, true);
                }

                $title = get_the_title($assignment_id);

                $grade_post = array(
                    'post_title'  => $title,
                    'post_status' => 'publish',
                    'post_type'   => 'dtlms_gradings',
                    'post_author' => $author_id,
                    'post_parent' => $parent_grade_id
                );

                $assignment_grade_id = wp_insert_post( $grade_post );

                update_post_meta ( $assignment_grade_id, 'dtlms-course-id',  $course_id );
                update_post_meta ( $assignment_grade_id, 'dtlms-course-grade-id',  $course_grade_id );
                update_post_meta ( $assignment_grade_id, 'dtlms-user-id',  $user_id );
                update_post_meta ( $assignment_grade_id, 'dtlms-lesson-id',  -1 );
                update_post_meta ( $assignment_grade_id, 'dtlms-quiz-id',  -1 );
                update_post_meta ( $assignment_grade_id, 'dtlms-assignment-id',  $assignment_id );
                update_post_meta ( $assignment_grade_id, 'dtlms-parent-curriculum-id',  $parent_curriculum_id );
                update_post_meta ( $assignment_grade_id, 'grade-type',  'assignment' );

                // Update user meta field
                if($parent_curriculum_id > 0) {
                    $curriculum_details['curriculum'][$parent_curriculum_id]['curriculum'][$assignment_id]['grade-post-id'] = $assignment_grade_id;
                } else {
                    $curriculum_details['curriculum'][$assignment_id]['grade-post-id'] = $assignment_grade_id;
                }

                // Update the next locked curriculum item
                $curriculum_completion_lock = get_post_meta($course_id, 'curriculum-completion-lock', true);
                if($curriculum_completion_lock == 'true') {
                    if($next_curriculum_id > 0) {
                        $open_curriculum_on_submission = get_post_meta($course_id, 'open-curriculum-on-submission', true);
                        $curriculum_details['next-curriculum-id'] = $next_curriculum_id;
                        if($open_curriculum_on_submission == 'true') {
                            $curriculum_details['active-next-curriculum-id'] = $next_curriculum_id;
                        }
                    }
                }

                update_user_meta($user_id, $course_id, $curriculum_details);

            }


            // Removing previous upload
            $previous_attachment_ids = get_post_meta ( $assignment_grade_id, 'attachment-id', true);
            foreach($previous_attachment_ids as $previous_attachment_id) {
                wp_delete_attachment($previous_attachment_id, true);
            }

            // Uploading new items
            $attachment_ids = $attachment_names = array ();
            $assignmentAttachmentFiles = dtlms_recursive_sanitize_text_field( $_FILES['assignment-attachment'] );
            if(is_array($assignmentAttachmentFiles) && !empty($assignmentAttachmentFiles)) {
                foreach ($assignmentAttachmentFiles['name'] as $key => $value) {
                    if ($assignmentAttachmentFiles['name'][$key]) {
                        $file = array (
                            'name' => $assignmentAttachmentFiles['name'][$key],
                            'type' => $assignmentAttachmentFiles['type'][$key],
                            'tmp_name' => $assignmentAttachmentFiles['tmp_name'][$key],
                            'error' => $assignmentAttachmentFiles['error'][$key],
                            'size' => $assignmentAttachmentFiles['size'][$key]
                        );
                        $_FILES = dtlms_recursive_sanitize_text_field ( array ('assignment-attachment' => $file ) );
                        foreach ($_FILES as $file => $array) {
                            $attachment_id = media_handle_upload( $file, $assignment_grade_id );
                            array_push($attachment_ids, $attachment_id);
                            array_push($attachment_names, $array['name']);
                        }
                    }
                }
                update_post_meta ( $assignment_grade_id, 'attachment-id',  $attachment_ids );
                update_post_meta ( $assignment_grade_id, 'attachment-name', $attachment_names );
            } else {
                delete_post_meta ( $assignment_grade_id, 'attachment-id' );
                delete_post_meta ( $assignment_grade_id, 'attachment-name' );
            }

            update_post_meta ( $assignment_grade_id, 'assignment-notes',  sanitize_textarea_field( $_POST['assignment-textarea'] ) );
            dtlms_generate_assignment_page_contents($user_id, $course_id, $assignment_id, $parent_curriculum_id);
            echo 'success';
        }

        die();
    }
    add_action( 'wp_ajax_dtlms_submit_assignment', 'dtlms_submit_assignment' );
    add_action( 'wp_ajax_nopriv_dtlms_submit_assignment', 'dtlms_submit_assignment' );
}

/**
 * Curriculum Details from Module Update
 */
if(!function_exists('dtlms_view_curriculum_details_module_update')) {
    function dtlms_view_curriculum_details_module_update($output, $curriculum_id, $curriculum_grade_id) {

        if(get_post_type($curriculum_id) == 'dtlms_assignments') {

			$output .= '<div class="dtlms-column dtlms-one-column">';

				$review_or_feedback = get_post_meta ($curriculum_grade_id, 'review-or-feedback', true);
				if($review_or_feedback != '') {
					$output .= '<div class="dtlms-curriculum-result-review-holder">
									<div class="dtlms-title">'.esc_html__('Instructor Feedback', 'dtlms-lite').'</div>'.
									'<div class="dtlms-curriculum-result-review-holder-content">'.apply_filters('dt_review_or_feedback', $review_or_feedback ).'</div>'.
								'</div>';
				}

			$output .= '</div>';

			$output .= '<div class="dtlms-column dtlms-one-column">';
				$output .= '<div class="dtlms-curriculum-assignment-holder">';
					$output .= dtlms_view_assignment_render_html($curriculum_grade_id, -1, false);
				$output .= '</div>';
			$output .= '</div>';

        }

        return $output;

    }
    add_filter( 'dtlms_view_curriculum_details_module', 'dtlms_view_curriculum_details_module_update', 10, 3 );
}
<!-- Course Curriculum -->
<div class="dtlms-custom-box">

    <div class="dtlms-column dtlms-one-sixth first">
       <label><?php esc_html_e('Curriculum','dtlms-lite'); ?></label>
    </div>

    <div class="dtlms-column dtlms-five-sixth">

        <?php

        $dtlms_course_curriculums = apply_filters( 'dtlms_course_curriculums', array () );

        if(is_array($dtlms_course_curriculums) && !empty($dtlms_course_curriculums)) {

            $dtlms_course_curriculum_keys = array_keys($dtlms_course_curriculums);

            ?>

            <div id="dtlms-curriculum-items-container">

                <?php

                $course_curriculum = get_post_meta ( $post_id, 'course-curriculum', true);

                if(isset($course_curriculum) && is_array($course_curriculum)) {

                    foreach($course_curriculum as $curriculum) {

                            if(is_numeric($curriculum)) {

                                $curriculum_post_type = get_post_type($curriculum);

                                if(in_array($curriculum_post_type, $dtlms_course_curriculum_keys)) {

                                    echo '<div id="dtlms-curriculum-section-item">';

                                        echo '<label>'.esc_html( $dtlms_course_curriculums[$curriculum_post_type]['singular_label'] ).'</label>';

                                        $args = array (
                                            'post_type'        => $curriculum_post_type,
                                            'numberposts'      => -1,
                                            'suppress_filters' => false,
                                        );

                                        if ( !in_array( 'administrator', (array) $current_user->roles ) ) {
                                            $args['author'] = $current_user_id;
                                        }

                                        $post_types = get_posts($args);

                                        echo '<select data-placeholder="'.esc_attr__('Select...', 'dtlms-lite').'" class="course-curriculum-chosen" id="course-curriculum" name="course-curriculum[]">';
                                            foreach ( $post_types as $post_type ){
                                                echo '<option value="'.esc_attr( $post_type->ID ).'" '.selected( $post_type->ID, $curriculum, false ).'>' . esc_html( $post_type->post_title ) . '</option>';
                                            }
                                        echo '</select>';

                                        wp_reset_postdata();

                                        echo '<span class="dtlms-remove-curriculum-item"><span class="fas fa-times"></span></span>';
                                        echo '<span class="fas fa-arrows-alt"></span>';

                                    echo '</div>';

                                }

                            } else {

                                echo '<div id="dtlms-curriculum-section-item">';
                                    echo '<label>'.esc_html__('Section', 'dtlms-lite').'</label>';
                                    echo '<input type="text" value="'.esc_attr( $curriculum ).'" id="course-curriculum" name="course-curriculum[]" />';
                                    echo '<span class="dtlms-remove-curriculum-item"><span class="fas fa-times"></span></span>';
                                    echo '<span class="fas fa-arrows-alt"></span>';
                                echo '</div>';

                            }

                    }

                }
                ?>

            </div>

            <a href="#" class="dtlms-add-curriculum section custom-button-style" data-curriculumtype="course"><?php esc_html_e('Add Section', 'dtlms-lite'); ?></a>

            <?php
            foreach($dtlms_course_curriculums as $dtlms_course_curriculum) {
                echo '<a href="#" class="dtlms-add-curriculum '.esc_attr( $dtlms_course_curriculum['singular_slug'] ).' custom-button-style" data-curriculumtype="course">'.sprintf(esc_html__('Add %1$s', 'dtlms-lite'), $dtlms_course_curriculum['singular_label']).'</a>';
            }
            ?>

            <p class="dtlms-note"><?php
                esc_html_e('Add sections, lessons, quiz, assignments here. Make sure you have created them already.', 'dtlms-lite');
                echo "<br>";
                esc_html_e('Make sure you haven\'t repeated any curriculum.', 'dtlms-lite');
                ?>
            </p>

            <div id="dtlms-curriculum-section-to-clone" class="hidden">

                <label><?php echo esc_html__('Section', 'dtlms-lite'); ?></label>

                <?php
                echo '<input type="text" placeholder="'.esc_attr__('Section Title', 'dtlms-lite').'" />';
                ?>

                <span class="dtlms-remove-curriculum-item"><span class="fas fa-times"></span></span>
                <span class="fas fa-arrows-alt"></span>

            </div>

            <?php
            foreach($dtlms_course_curriculums as $dtlms_course_curriculum) {
                echo '<div id="dtlms-curriculum-'.esc_attr( $dtlms_course_curriculum['singular_slug'] ).'-to-clone" class="hidden">';

                    echo '<label>'.esc_html( $dtlms_course_curriculum['singular_label'] ).'</label>';

                    $args = array (
                        'post_type'        => $dtlms_course_curriculum['post_type'],
                        'numberposts'      => -1,
                        'suppress_filters' => FALSE,
                    );

                    if ( !in_array( 'administrator', (array) $current_user->roles ) ) {
                        $args['author'] = $current_user_id;
                    }

                    $curriculum_posts = get_posts($args);

                    echo '<select data-placeholder="'.sprintf(esc_attr__('Select %1$s...', 'dtlms-lite'), $dtlms_course_curriculum['singular_label']).'" class="cc-select">';
                    foreach ( $curriculum_posts as $curriculum_post ){
                        echo '<option value="' . esc_attr( $curriculum_post->ID ). '">' . esc_html( $curriculum_post->post_title ). '</option>';
                    }
                    echo '</select>';

                    wp_reset_postdata();

                    echo '<span class="dtlms-remove-curriculum-item"><span class="fas fa-times"></span></span>';
                    echo '<span class="fas fa-arrows-alt"></span>';

                echo '</div>';
            }
            ?>

            <?php
        } else {
            echo '<p class="dtlms-note">'.esc_html__('No curriculums activated', 'dtlms-lite').'</p>';
        }

        ?>

    </div>

</div>
<!-- Course Curriculum End -->
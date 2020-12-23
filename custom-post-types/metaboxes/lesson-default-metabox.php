<?php
global $post;
$post_id = $post->ID;
echo '<input type="hidden" name="dtlms_lessons_meta_nonce" value="'.wp_create_nonce('dtlms_lessons_nonce').'" />';

$current_user    = wp_get_current_user();
$current_user_id = $current_user->ID;?>

<!-- Free Lesson -->
<div class="dtlms-custom-box">
    <div class="dtlms-column dtlms-one-sixth first">
        <label><?php esc_html_e( 'Unlock Lesson','dtlms-lite');?></label>
    </div>
    <div class="dtlms-column dtlms-five-sixth"><?php
        $free_lesson = get_post_meta ( $post_id, 'free-lesson', true );
        $switchclass = ($free_lesson == true) ? 'checkbox-switch-on' : 'checkbox-switch-off';
        $checked     = ($free_lesson == true) ? ' checked="checked"' : '';?>
        <div data-for="free-lesson" class="dtlms-checkbox-switch <?php echo esc_attr( $switchclass );?>"></div>
        <input id="free-lesson" class="hidden" type="checkbox" name="free-lesson" value="true" <?php echo $checked;?>/>
        <p class="dtlms-note"> <?php echo esc_html__('YES! to unlock this lesson, so that you can use this lesson as preview. It won\'t be affected by "Curriculum Completion Lock" in course settings.','dtlms-lite');?> </p>
    </div>
</div>
<!-- Free Lesson End -->

<!-- Lesson Curriculum -->
<div class="dtlms-custom-box">
    <div class="dtlms-column dtlms-one-sixth first">
       <label><?php esc_html_e('Curriculum','dtlms-lite'); ?></label>
    </div>

    <div class="dtlms-column dtlms-five-sixth"><?php
        $dtlms_course_curriculums = apply_filters( 'dtlms_course_curriculums', array () );
        unset($dtlms_course_curriculums['dtlms_lessons']);

        if(is_array($dtlms_course_curriculums) && !empty($dtlms_course_curriculums)) {

            $dtlms_course_curriculum_keys = array_keys($dtlms_course_curriculums);
            ?>

            <div id="dtlms-curriculum-items-container">

                <?php

                $lesson_curriculum = get_post_meta ( $post_id, 'lesson-curriculum', true);

                if(isset($lesson_curriculum) && is_array($lesson_curriculum)) {

                    foreach($lesson_curriculum as $curriculum) {

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

                                        echo '<select data-placeholder="'.esc_attr__('Select...', 'dtlms-lite').'" class="cc-select" id="lesson-curriculum" name="lesson-curriculum[]">';
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
                                    echo '<input type="text" value="'.esc_attr( $curriculum ).'" id="lesson-curriculum" name="lesson-curriculum[]" />';

                                    echo '<span class="dtlms-remove-curriculum-item"><span class="fas fa-times"></span></span>';
                                    echo '<span class="fas fa-arrows-alt"></span>';

                                echo '</div>';

                            }

                    }

                }
                ?>

            </div>

            <a href="#" class="dtlms-add-curriculum section custom-button-style" data-curriculumtype="lesson"><?php esc_html_e('Add Section', 'dtlms-lite'); ?></a><?php
            foreach($dtlms_course_curriculums as $dtlms_course_curriculum) {
                echo '<a href="#" class="dtlms-add-curriculum '.esc_attr( $dtlms_course_curriculum['singular_slug'] ).' custom-button-style" data-curriculumtype="lesson">'.sprintf(esc_html__('Add %1$s', 'dtlms-lite'), $dtlms_course_curriculum['singular_label']).'</a>';
            }?>

            <p class="dtlms-note"><?php
                esc_html_e('Add sections, lessons, quiz, lessons here. Make sure you have created them already.','dtlms-lite');
                echo "<br>";
                esc_html_e('Leave empty if you don\'t like to use sub items.','dtlms-lite');
                echo "<br>";
                esc_html_e('Make sure you haven\'t repeated any curriculum.', 'dtlms-lite');
            ?></p>

            <div id="dtlms-curriculum-section-to-clone" class="hidden">
                <label><?php echo esc_html__('Section', 'dtlms-lite'); ?></label>
                <?php echo '<input type="text" placeholder="'.esc_attr__('Section Title', 'dtlms-lite').'" />'; ?>
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
                        echo '<option value="' . esc_attr( $curriculum_post->ID ) . '">' . esc_html( $curriculum_post->post_title ) . '</option>';
                    }
                    echo '</select>';

                    wp_reset_postdata();

                    echo '<span class="dtlms-remove-curriculum-item"><span class="fas fa-times"></span></span>';
                    echo '<span class="fas fa-arrows-alt"></span>';

                echo '</div>';
            }

        }
        ?>

    </div>

</div>
<!-- Lesson Curriculum End -->

<div class="dtlms-custom-box">

    <!-- Drip Duration -->
    <div class="dtlms-column dtlms-one-half first">

        <div class="dtlms-column dtlms-one-third first">
           <label><?php esc_html_e('Duration', 'dtlms-lite'); ?></label>
        </div>
        <div class="dtlms-column dtlms-two-third">
            <?php $duration = get_post_meta ( $post_id, 'duration', true ); ?>
            <input type="number" id="duration" name="duration" value="<?php echo esc_attr( $duration ); ?>" min="0" >
            <p class="dtlms-note"> <?php esc_html_e('Add duration here.','dtlms-lite');?> </p>
        </div>

    </div>
    <!-- Drip Duration End -->

    <!-- Drip Duration Parameter -->
    <div class="dtlms-column dtlms-one-half">

        <div class="dtlms-column dtlms-one-third first">
           <label><?php esc_html_e('Duration Parameter','dtlms-lite');?></label>
        </div>
        <div class="dtlms-column dtlms-two-third"><?php
            $duration_parameter = get_post_meta ( $post_id, 'duration-parameter', true );
            $durationparameters = array (
                '60'      => esc_html__('Minutes', 'dtlms-lite'),
                '3600'    => esc_html__('Hours', 'dtlms-lite'),
                '86400'   => esc_html__('Days', 'dtlms-lite'),
                '604800'  => esc_html__('Weeks', 'dtlms-lite'),
                '2592000' => esc_html__('Months', 'dtlms-lite'),
            );

            echo '<select name="duration-parameter" data-placeholder="'.esc_attr__('Select Duration Parameter...', 'dtlms-lite').'" class="dtlms-chosen-select">';
            echo '<option value="">' . esc_html__( 'None', 'dtlms-lite' ) . '</option>';
            foreach ($durationparameters as $durationparameter_key => $durationparameter){
                echo '<option value="' . esc_attr( $durationparameter_key ) . '"' . selected( $durationparameter_key, $duration_parameter, false ) . '>' . esc_html( $durationparameter ) . '</option>';
            }
            echo '</select>' ;
            ?>
            <p class="dtlms-note"> <?php esc_html_e('Choose duration parameter here.','dtlms-lite');?> </p>
        </div>

    </div>
    <!-- Drip Duration Parameter End -->

</div>

<div class="dtlms-custom-box">

    <!-- Maximum Mark -->
    <div class="dtlms-column dtlms-one-half first">
        <div class="dtlms-column dtlms-one-third first">
           <label><?php esc_html_e('Maximum Mark', 'dtlms-lite'); ?></label>
        </div>
        <div class="dtlms-column dtlms-two-third">
            <?php $lesson_maximum_mark = get_post_meta ( $post_id, 'lesson-maximum-mark', true ); ?>
            <input id="lesson-maximum-mark" name="lesson-maximum-mark" type="number" value="<?php echo esc_attr( $lesson_maximum_mark ); ?>" style="width:10%;" min="1" />
            <p class="dtlms-note"> <?php esc_html_e('Maximum mark for lesson. Default value is 100.','dtlms-lite');?> </p>
        </div>
    </div>
    <!-- Maximum Mark End -->

    <!-- Pass Percentage -->

    <div class="dtlms-column dtlms-one-half">
    </div>
    <!-- Pass Percentage End -->

</div>
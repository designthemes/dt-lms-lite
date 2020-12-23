  <div class="dtlms-custom-box">

      <div class="dtlms-column dtlms-one-half first">

          <div class="dtlms-column dtlms-one-third first"><?php esc_html_e( 'Item Type', 'dtlms-lite');?></div>
          <div class="dtlms-column dtlms-two-third">
              <?php echo esc_html__( 'Lesson', 'dtlms-lite' ); ?>
          </div>

      </div>

      <div class="dtlms-column dtlms-one-half">

      </div>

  </div>

  <div class="dtlms-custom-box">

      <div class="dtlms-column dtlms-one-half first">

          <div class="dtlms-column dtlms-one-third first"><?php esc_html_e( 'Course', 'dtlms-lite');?></div>
          <div class="dtlms-column dtlms-two-third">
              <strong><?php echo esc_html( get_the_title($course_id) ); ?></strong>
          </div>

      </div>

      <div class="dtlms-column dtlms-one-half">

          <div class="dtlms-column dtlms-one-third first"><?php esc_html_e( 'User Name', 'dtlms-lite');?></div>
          <div class="dtlms-column dtlms-two-third">
              <strong><?php echo esc_html( $user_info->display_name ); ?></strong>
          </div>

      </div>

  </div>

  <div class="dtlms-custom-box">

      <div class="dtlms-column dtlms-one-half first">

          <div class="dtlms-column dtlms-one-third first"><?php esc_html_e( 'Marks Obtained', 'dtlms-lite');?></div>
          <div class="dtlms-column dtlms-two-third">
            <?php $marks_obtained = get_post_meta ($post_id, 'marks-obtained', true);  ?>
            <input id="dtlms-marks-obtained" name="dtlms-marks-obtained" class="large" type="number" value="<?php echo esc_attr( $marks_obtained ); ?>" style="width:20%;" <?php echo $input_graded_attr; ?> />
          </div>

      </div>

      <div class="dtlms-column dtlms-one-half">

          <div class="dtlms-column dtlms-one-third first"><?php esc_html_e( 'Maximum Marks', 'dtlms-lite');?></div>
          <div class="dtlms-column dtlms-two-third"><?php
            $maximum_mark = get_post_meta ($lesson_id, 'lesson-maximum-mark', true);
            if($maximum_mark == '') {
              $maximum_mark = 100;
            }?>
            <?php echo esc_html( $maximum_mark ); ?>
            <input id="dtlms-maximum-marks" name="dtlms-maximum-marks" class="large" type="hidden" value="<?php echo esc_attr( $maximum_mark ); ?>" />
          </div>

      </div>

  </div>

  <div class="dtlms-custom-box">
      <div class="dtlms-column dtlms-one-half first">
          <div class="dtlms-column dtlms-one-third first"><?php esc_html_e( 'Percentage Obtained (%)', 'dtlms-lite');?></div>
          <div class="dtlms-column dtlms-two-third">
            <?php $marks_obtained_percent = get_post_meta ( $post_id, 'marks-obtained-percentage', true);  ?>
            <input type="text" name="dtlms-marks-obtained-percentage" id="dtlms-marks-obtained-percentage" value="<?php echo esc_attr( $marks_obtained_percent ); ?>" readonly="readonly"/>
          </div>
      </div>

      <div class="dtlms-column dtlms-one-half">
          <div class="dtlms-column dtlms-one-third first"><?php esc_html_e( 'Pass Percentage (%)', 'dtlms-lite');?></div>
          <div class="dtlms-column dtlms-two-third"><?php
            $pass_percentage = get_post_meta ($lesson_id, 'lesson-pass-percentage', true);
            if($pass_percentage == '') {
              $pass_percentage = 100;
            }?>
            <?php echo esc_html( $pass_percentage ); ?>
          </div>
      </div>
  </div>

  <div class="dtlms-custom-box">

      <div class="dtlms-column dtlms-one-half first">
          <div class="dtlms-column dtlms-one-third first"><?php esc_html_e( 'Review or Feedback', 'dtlms-lite');?></div>
          <div class="dtlms-column dtlms-two-third">
            <?php $review_or_feedback = get_post_meta ($post_id, 'review-or-feedback', true); ?>
            <textarea id="review-or-feedback" name="review-or-feedback" class="large" rows="6" style="width:90%;"><?php echo apply_fillters( 'dt_review_or_feedback', $review_or_feedback ); ?></textarea>
            <p class="dtlms-note"> <?php esc_html_e('You can add feedback or review for this item here, which will displayed to that student.','dtlms-lite');?> </p>
          </div>

      </div>

      <div class="dtlms-column dtlms-one-half">
          <div class="dtlms-column dtlms-one-third first"><?php esc_html_e( 'Graded', 'dtlms-lite');?></div>
          <div class="dtlms-column dtlms-two-third"><?php
            $graded      = get_post_meta ($post_id, 'graded', true);
            $switchclass = ($graded != '') ? 'checkbox-switch-on' : 'checkbox-switch-off';
            $checked     = ($graded != '') ? ' checked="checked"' : ''; ?>
            <div data-for="graded" class="dtlms-checkbox-switch <?php echo esc_attr( $switchclass.' '.$course_graded_class );?>"></div>
            <input id="graded" class="hidden" type="checkbox" name="graded" value="true" <?php echo $checked; ?> />
            <p class="dtlms-note"><?php esc_html_e('Once you enable this option, then this user can\'t resubmit this item and it will be marked as completed!','dtlms-lite');?></p>
          </div>
      </div>

  </div>

  <div class="dtlms-custom-box">
    <p class="dtlms-note"><?php esc_html_e('Once course to which this lesson belongs to is graded you can\'t regrade this item.','dtlms-lite');?></p>
  </div>
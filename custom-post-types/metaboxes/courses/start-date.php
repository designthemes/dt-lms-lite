<div class="dtlms-custom-box">

    <!-- Course Start Date -->
    <div class="dtlms-column dtlms-one-half first">

        <div class="dtlms-column dtlms-one-third first">
            <label><?php esc_html_e('Course Start Date', 'dtlms-lite'); ?></label>
        </div>
        <div class="dtlms-column dtlms-two-third">
            <?php $course_start_date = get_post_meta ( $post_id, 'course-start-date', true );?>
            <input class="course-start-date dtlms-datepicker" name="course-start-date" type="text" value="<?php echo esc_attr( $course_start_date );?>" />
            <p class="dtlms-note"> <?php esc_html_e("Choose course start date here.", 'dtlms-lite'); ?> </p>
            <div class="dtlms-clear"></div>
        </div>

    </div>
    <!-- Course Start Date End -->

    <!-- Allow Purchases Before Course Start Date -->
    <div class="dtlms-column dtlms-one-half">

        <div class="dtlms-column dtlms-one-third first"><?php esc_html_e( 'Allow Purchases Before Course Start Date', 'dtlms-lite');?></div>
        <div class="dtlms-column dtlms-two-third"><?php
            $current     = get_post_meta($post_id, 'allowpurchases-before-course-startdate', true);
            $switchclass = ( $current === "true") ? 'checkbox-switch-on' :'checkbox-switch-off';
            $checked     = ( $current === "true") ? ' checked="checked" ' : '';?>
            <div data-for="allowpurchases-before-course-startdate" class="dtlms-checkbox-switch <?php echo esc_attr( $switchclass ); ?>"></div>
            <input id="allowpurchases-before-course-startdate" class="hidden" type="checkbox" name="allowpurchases-before-course-startdate" value="true" <?php echo $checked; ?> />
            <p class="dtlms-note"> <?php esc_html_e('If you like to allow student to make purchases before course start date, but they won\'t be able to take course until course start date', 'dtlms-lite');?> </p>
        </div>

    </div>
    <!-- Allow Purchases Before Course Start Date End -->

</div>
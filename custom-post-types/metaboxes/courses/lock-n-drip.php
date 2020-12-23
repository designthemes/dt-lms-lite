<!-- Curriculum Completion Lock & Drip Feed Switch -->

<div class="dtlms-custom-box">

    <div class="dtlms-column dtlms-one-half first">

        <div class="dtlms-column dtlms-one-third first"><?php esc_html_e( 'Curriculum Completion Lock & Drip Feed Switch', 'dtlms-lite');?></div>
        <div class="dtlms-column dtlms-two-third">
            <?php
            $drip_completionlock_switch = get_post_meta ( $post_id, 'drip-completionlock-switch', true );
            $completionlock_class =  $dripfeed_class = 'hidden';
            if($drip_completionlock_switch == 'completionlock') {
                $completionlock_class = '';
                $dripfeed_class = 'hidden';
            } else if($drip_completionlock_switch == 'dripfeed') {
                $completionlock_class = 'hidden';
                $dripfeed_class = '';
            }

            $drip_completionlock_options = array (
                ''               => esc_html__('None', 'dtlms-lite'),
                'completionlock' => esc_html__('Curriculum Completion Lock', 'dtlms-lite'),
                'dripfeed'       => esc_html__('Drip Feed', 'dtlms-lite')
            );

            echo '<select id="drip-completionlock-switch" name="drip-completionlock-switch" data-placeholder="'.esc_attr__('Select...', 'dtlms-lite').'" class="dtlms-chosen-select">';
            foreach ($drip_completionlock_options as $drip_completionlock_option_key => $drip_completionlock_option){
                echo '<option value="' . esc_attr( $drip_completionlock_option_key ) . '"' . selected( $drip_completionlock_option_key, $drip_completionlock_switch, false ) . '>' . esc_html( $drip_completionlock_option ) . '</option>';
            }
            echo '</select>' ;
            ?>
            <p class="dtlms-note"> <?php esc_html_e('If you wish you can switch betwen Curriculum Completion Lock & Drip Feed.', 'dtlms-lite');?> </p>
        </div>

    </div>

    <div class="dtlms-column dtlms-one-half">

    </div>

</div>
<!-- Curriculum Completion Lock & Drip Feed Switch End -->


<!-- Curriculum Completion Lock -->

<div class="dtlms-completionlock-holder <?php echo esc_attr($completionlock_class); ?>">

    <div class="dtlms-custom-box">

        <!-- Curriculum Completion Lock -->
        <div class="dtlms-column dtlms-one-half first">

            <div class="dtlms-column dtlms-one-third first"><?php esc_html_e( 'Curriculum Completion Lock', 'dtlms-lite');?></div>
            <div class="dtlms-column dtlms-two-third"><?php
                $current     = get_post_meta($post_id, 'curriculum-completion-lock', true);
                $switchclass = ( $current === "true") ? 'checkbox-switch-on' :'checkbox-switch-off';
                $checked     = ( $current === "true") ? ' checked="checked" ' : '';?>
                <div data-for="curriculum-completion-lock" class="dtlms-checkbox-switch <?php echo esc_attr( $switchclass );?>"></div>
                <input id="curriculum-completion-lock" class="hidden" type="checkbox" name="curriculum-completion-lock" value="true" <?php echo $checked;?>/>
                <p class="dtlms-note"> <?php esc_html_e('User will be able to take next curriculum only when previous curriculums are completed ( ie. evaluated ).', 'dtlms-lite'); echo "<br>"; esc_html_e('If some quizzes are marked as manual evaluation than student have to wait for evalution to take next curriculum item.', 'dtlms-lite');?> </p>
            </div>

        </div>
        <!-- Curriculum Completion Lock End -->

        <!-- Curriculum Completion Lock - Allow On Submission -->
        <div class="dtlms-column dtlms-one-half">

            <div class="dtlms-column dtlms-one-third first"><?php esc_html_e( 'Open Curriculum On Submission', 'dtlms-lite');?></div>
            <div class="dtlms-column dtlms-two-third"><?php
                $current     = get_post_meta($post_id, 'open-curriculum-on-submission', true);
                $switchclass = ( $current === "true") ? 'checkbox-switch-on' :'checkbox-switch-off';
                $checked     = ( $current === "true") ? ' checked="checked" ' : '';?>
                <div data-for="open-curriculum-on-submission" class="dtlms-checkbox-switch <?php echo esc_attr( $switchclass );?>"></div>
                <input id="open-curriculum-on-submission" class="hidden" type="checkbox" name="open-curriculum-on-submission" value="true" <?php echo $checked;?>/>
                <p class="dtlms-note"> <?php esc_html_e('User will be able to take next curriculum when they just submit current curriculum. No need for current curriculum to be evaluated.', 'dtlms-lite');?> </p>
            </div>
        </div>
        <!-- Curriculum Completion Lock - Allow On Submission End -->

    </div>

</div>
<!-- Curriculum Completion Lock End -->

<!-- Drip Feed -->
<div class="dtlms-dripfeed-holder <?php echo esc_attr($dripfeed_class); ?>">

    <div class="dtlms-custom-box">

        <!-- Drip Feed -->
        <div class="dtlms-column dtlms-one-half first">

            <div class="dtlms-column dtlms-one-third first"><?php esc_html_e( 'Drip Feed', 'dtlms-lite');?></div>
            <div class="dtlms-column dtlms-two-third"><?php
                $current     = get_post_meta($post_id, 'drip-feed', true);
                $switchclass = ( $current === "true") ? 'checkbox-switch-on' :'checkbox-switch-off';
                $checked     = ( $current === "true") ? ' checked="checked" ' : '';?>
                <div data-for="drip-feed" class="dtlms-checkbox-switch <?php echo esc_attr( $switchclass ); ?>"></div>
                <input id="drip-feed" class="hidden" type="checkbox" name="drip-feed" value="true" <?php echo $checked; ?> />
                <p class="dtlms-note"> <?php esc_html_e('If you like to enable drip feed for your course enable it here.', 'dtlms-lite');?> </p>
            </div>

        </div>
        <!-- Drip Feed End -->

        <div class="dtlms-column dtlms-one-half">
        </div>

    </div>

    <div class="dtlms-custom-box">

        <!-- Drip Content Type -->
        <div class="dtlms-column dtlms-one-half first">

            <div class="dtlms-column dtlms-one-third first">
               <label><?php esc_html_e('Drip Content Type','dtlms-lite');?></label>
            </div>
            <div class="dtlms-column dtlms-two-third">
                <?php
                $drip_content_type = get_post_meta ( $post_id, 'drip-content-type', true );
                $dripcontenttypes = array (
                    'curriculum' => esc_html__('Curriculum','dtlms-lite'),
                    'section'    => esc_html__('Section','dtlms-lite'),
                );

                echo '<select name="drip-content-type" data-placeholder="'.esc_attr__('Select Drip Content Type...', 'dtlms-lite').'" class="dtlms-chosen-select">';
                echo '<option value="">' . esc_html__( 'None', 'dtlms-lite' ) . '</option>';
                foreach ($dripcontenttypes as $dripcontenttype_key => $dripcontenttype){
                    echo '<option value="' . esc_attr( $dripcontenttype_key ) . '"' . selected( $dripcontenttype_key, $drip_content_type, false ) . '>' . esc_html( $dripcontenttype ) . '</option>';
                }
                echo '</select>' ;
                ?>
                <p class="dtlms-note"> <?php esc_html_e('Choose how you like to drip content based on curriculum or section.','dtlms-lite');?> </p>
            </div>

        </div>
        <!-- Drip Content Type End -->

        <!-- Drip Duration Type -->
        <div class="dtlms-column dtlms-one-half">

            <div class="dtlms-column dtlms-one-third first">
               <label><?php esc_html_e('Drip Duration Type','dtlms-lite');?></label>
            </div>
            <div class="dtlms-column dtlms-two-third"><?php
                $drip_duration_type = get_post_meta ( $post_id, 'drip-duration-type', true );
                $dripdurationtypes  = array (
                    'static'  => esc_html__('Static', 'dtlms-lite' ),
                    'dynamic' => esc_html__('Dynamic', 'dtlms-lite' ),
                );

                echo '<select name="drip-duration-type" data-placeholder="'.esc_attr__('Select Drip Duration Type...', 'dtlms-lite').'" class="dtlms-chosen-select">';
                echo '<option value="">' . esc_html__( 'None', 'dtlms-lite' ) . '</option>';
                foreach ($dripdurationtypes as $dripdurationtype_key => $dripdurationtype){
                    echo '<option value="' . esc_attr( $dripdurationtype_key ) . '"' . selected( $dripdurationtype_key, $drip_duration_type, false ) . '>' . esc_html( $dripdurationtype ) . '</option>';
                }
                echo '</select>' ;
                ?>
                <p class="dtlms-note"> <?php esc_html_e('Static - Specify drip duration below which will be the consecutive drip duration between items ( curriculum or section ).','dtlms-lite'); echo "<br>"; esc_html_e('Dynamic - Duration specified in each curriculum will be taken as drip duration.','dtlms-lite');?> </p>
            </div>

        </div>
        <!-- Drip Duration Type End -->

    </div>


    <div class="dtlms-custom-box">

        <!-- Drip Duration -->
        <div class="dtlms-column dtlms-one-half first">

            <div class="dtlms-column dtlms-one-third first">
               <label><?php esc_html_e('Static Drip Duration', 'dtlms-lite'); ?></label>
            </div>
            <div class="dtlms-column dtlms-two-third">
                <?php $drip_duration = get_post_meta ( $post_id, 'drip-duration', true ); ?>
                <input type="number" id="drip-duration" name="drip-duration" value="<?php echo esc_attr( $drip_duration ); ?>" />
                <p class="dtlms-note"> <?php esc_html_e('Add drip feed duration parameter here.','dtlms-lite');?> </p>
            </div>

        </div>
        <!-- Drip Duration End -->

        <!-- Drip Duration Parameter -->
        <div class="dtlms-column dtlms-one-half">

            <div class="dtlms-column dtlms-one-third first">
               <label><?php esc_html_e('Static Drip Duration Parameter','dtlms-lite');?></label>
            </div>
            <div class="dtlms-column dtlms-two-third"><?php
                $drip_duration_parameter = get_post_meta ( $post_id, 'drip-duration-parameter', true );
                $dripdurationparameters = array (
                    '60'      => esc_html__('Mins', 'dtlms-lite' ),
                    '3600'    => esc_html__('Hours','dtlms-lite' ),
                    '86400'   => esc_html__('Days','dtlms-lite' ),
                    '604800'  => esc_html__('Weeks','dtlms-lite' ),
                    '2592000' => esc_html__('Months','dtlms-lite' ),
                );

                echo '<select name="drip-duration-parameter" data-placeholder="'.esc_attr__('Select Drip Duration Parameter...', 'dtlms-lite').'" class="dtlms-chosen-select">';
                echo '<option value="">' . esc_html__( 'None', 'dtlms-lite' ) . '</option>';
                foreach ($dripdurationparameters as $dripdurationparameter_key => $dripdurationparameter){
                    echo '<option value="' . esc_attr( $dripdurationparameter_key ) . '"' . selected( $dripdurationparameter_key, $drip_duration_parameter, false ) . '>' . esc_html( $dripdurationparameter ) . '</option>';
                }
                echo '</select>' ;
                ?>
                <p class="dtlms-note"> <?php esc_html_e('Choose drip duration parameter here.','dtlms-lite');?> </p>
            </div>

        </div>
        <!-- Drip Duration Parameter End -->

    </div>

</div>
<!-- Drip Feed End -->
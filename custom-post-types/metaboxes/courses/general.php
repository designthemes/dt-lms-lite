<div class="dtlms-custom-box">

    <!-- Page Layout -->
    <div class="dtlms-column dtlms-one-half first">

        <div class="dtlms-column dtlms-one-third first"><?php echo esc_html__( 'Page Layout', 'dtlms-lite');?></div>
        <div class="dtlms-column dtlms-two-third"><?php
            $page_layout = get_post_meta($post_id, 'page-layout', true);
            $page_layout = ($page_layout != '') ? $page_layout : 'type1';

            $pagelayouts = array (
                'type1' => esc_html__( 'Type 1', 'dtlms-lite' ),
                'type2' => esc_html__( 'Type 2','dtlms-lite' ),
                'type3' => esc_html__( 'Type 3','dtlms-lite' ),
                'type4' => esc_html__( 'Type 4','dtlms-lite' ),
            );

            echo '<select name="page-layout" data-placeholder="'.esc_attr__('Choose Page Layout...', 'dtlms-lite').'" class="dtlms-chosen-select">';
                foreach ($pagelayouts as $pagelayout_key => $pagelayout) {
                    echo '<option value="'.esc_attr($pagelayout_key).'" '.selected($pagelayout_key, $page_layout, false).'>'.esc_html($pagelayout).'</option>';
                }
            echo '</select>';
            ?>
        </div>

    </div>
    <!-- Page Layout End -->

    <div class="dtlms-column dtlms-one-half"></div>

</div>

<div class="dtlms-custom-box">

    <!-- Co Instructors -->

        <div class="dtlms-column dtlms-one-sixth first">
            <label><?php esc_html_e('Co Instructors','dtlms-lite'); ?></label>
        </div>

        <div class="dtlms-column dtlms-five-sixth"><?php
            $coinstructors = get_post_meta ( $post_id, 'coinstructors', true );

            echo '<select id="coinstructors" name="coinstructors[]" data-placeholder="'.esc_attr__('Select Co-Instructors...', 'dtlms-lite').'" class="dtlms-chosen-select" multiple="multiple">';
                $out .= '<option value="">' . esc_html__( 'None', 'dtlms-lite' ) . '</option>';

                $args = array( 'role' => 'instructor' );
                $user_query = new WP_User_Query( $args );
                if ( !empty( $user_query->results ) ) {
                    foreach ( $user_query->results as $user ) {
                        $selected = in_array($user->ID , $coinstructors ) ? 'selected="selected"' : '';
                        echo '<option value="' . esc_attr( $user->ID ) . '"' . $selected . '>' . esc_html( $user->display_name ) . '</option>';
                    }
                }
            echo '</select>';?>
            <p class="dtlms-note"> <?php esc_html_e('Add co instructors for this course.', 'dtlms-lite');?> </p>
        </div>
    <!-- Co Instructors End -->

</div>


<div class="dtlms-custom-box">

	<!-- Featured Course -->
	<div class="dtlms-column dtlms-one-half first">

        <div class="dtlms-column dtlms-one-third first"><?php esc_html_e( 'Featured Course', 'dtlms-lite');?></div>
        <div class="dtlms-column dtlms-two-third"><?php
            $current     = get_post_meta ( $post_id, 'featured-course', true);
            $switchclass = ( $current === "true") ? 'checkbox-switch-on' :'checkbox-switch-off';
            $checked     = ( $current === "true") ? ' checked="checked" ' : '';?>
            <div data-for="featured-course" class="dtlms-checkbox-switch <?php echo esc_attr( $switchclass );?>"></div>
            <input id="featured-course" class="hidden" type="checkbox" name="featured-course" value="true" <?php echo $checked;?>/>
            <p class="dtlms-note"> <?php esc_html_e('YES! to make this as featured course.', 'dtlms-lite');?> </p>
        </div>

    </div>
    <!-- Featured Course End -->

    <!-- Show Social Share -->
    <div class="dtlms-column dtlms-one-half">

        <div class="dtlms-column dtlms-one-third first">
            <label><?php esc_html_e('Social Share Items','dtlms-lite');?></label>
        </div>
        <div class="dtlms-column dtlms-two-third"><?php
            $socialshare_items = get_post_meta ( $post_id, 'socialshare-items', true );
            $socialshare_items = (isset($socialshare_items) && !empty($socialshare_items)) ? $socialshare_items : array();

            $socialshare_array = array(
                'facebook'    => esc_html__('Facebook','dtlms-lite'),
                'delicious'   => esc_html__('Delicious','dtlms-lite'),
                'digg'        => esc_html__('Digg','dtlms-lite'),
                'stumbleupon' => esc_html__('StumbleUpon','dtlms-lite'),
                'twitter'     => esc_html__('Twitter','dtlms-lite'),
                'linkedin'    => esc_html__('LinkedIn','dtlms-lite'),
                'pinterest'   => esc_html__('Pinterest','dtlms-lite')
            );

            $out = '';
            $out .= '<select id="socialshare-items" name="socialshare-items[]" data-placeholder="'.esc_attr__('Select Social Share Items...', 'dtlms-lite').'" class="dtlms-chosen-select" multiple="multiple">' . "\n";
            $out .= '<option value="">' . esc_html__( 'None', 'dtlms-lite' ) . '</option>';
            if ( count( $socialshare_array ) > 0 ) {
                foreach ($socialshare_array as $socialshare_key => $socialshare){
                    $selected = in_array( $socialshare_key , $socialshare_items ) ? 'selected="selected"' : '';
                    $out .= '<option value="' . esc_attr( $socialshare_key ) . '"' . $selected . '>' . esc_html( $socialshare ) . '</option>' . "\n";
                }
            }
            $out .= '</select>' . "\n";
            echo $out;
            ?>
            <p class="dtlms-note"> <?php esc_html_e('Choose social share items here.','dtlms-lite');?> </p>
        </div>

    </div>
    <!-- Show Social Share End -->

</div>

<div class="dtlms-custom-box">

	<!-- Show Related Courses -->
	<div class="dtlms-column dtlms-one-half first">

        <div class="dtlms-column dtlms-one-third first">
            <label><?php esc_html_e('Show Related Courses','dtlms-lite');?></label>
        </div>
        <div class="dtlms-column dtlms-two-third"><?php
            $show_related_course = get_post_meta ( $post_id, 'show-related-course', true );
            $switchclass         = ($show_related_course == true) ? 'checkbox-switch-on' : 'checkbox-switch-off';
            $checked             = ($show_related_course == true) ? ' checked="checked"' : '';?>
            <div data-for="show-related-course" class="dtlms-checkbox-switch <?php echo esc_attr( $switchclass );?>"></div>
            <input id="show-related-course" class="hidden" type="checkbox" name="show-related-course" value="true" <?php echo $checked;?> />
            <p class="dtlms-note"> <?php esc_html_e('Would you like to show the related courses.','dtlms-lite');?> </p>
        </div>

    </div>
    <!-- Show Related Courses End -->

    <!-- Referrence URL -->
    <div class="dtlms-column dtlms-one-half">

        <div class="dtlms-column dtlms-one-third first">
            <label><?php esc_html_e('Referrence URL', 'dtlms-lite');?></label>
        </div>
        <div class="dtlms-column dtlms-two-third">
            <?php $reference_url = get_post_meta ( $post_id, "reference-url", true );?>
            <input id="reference-url" name="reference-url" type="text" value="<?php echo esc_attr( $reference_url );?>" />
            <p class="dtlms-note"> <?php esc_html_e('You can add referrence url for your course here.', 'dtlms-lite');?> </p>
            <div class="dtlms-clear"></div>
        </div>

    </div>
    <!-- Referrence URL End -->

</div>
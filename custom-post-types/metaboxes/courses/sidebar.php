<div class="dtlms-custom-box">

    <!-- Enable Course Sidebar -->
    <div class="dtlms-column dtlms-one-half first">

        <div class="dtlms-column dtlms-one-third first"><?php esc_html_e( 'Enable Sidebar Content', 'dtlms-lite');?></div>
        <div class="dtlms-column dtlms-two-third"><?php
                 $current     = get_post_meta($post_id, 'enable-sidebar', true);
                 $switchclass = ( $current === "true") ? 'checkbox-switch-on' :'checkbox-switch-off';
                 $checked     = ( $current === "true") ? ' checked="checked" ' : '';?>

            <div data-for = "enable-sidebar" class = "dtlms-checkbox-switch <?php echo esc_attr( $switchclass ); ?>"></div>
            <input id="enable-sidebar" class="hidden" type="checkbox" name="enable-sidebar" value="true" <?php echo $checked; ?> />
            <p class="dtlms-note"> <?php esc_html_e('If you like to display any additional content in sidebar.', 'dtlms-lite');?> </p>
        </div>

    </div>
    <!-- Enable Course Sidebar End -->

    <div class="dtlms-column dtlms-one-half"></div>

</div>


<div class="dtlms-custom-box">

    <!-- Enable Course Sidebar -->
    <div class="dtlms-column dtlms-one-half first">

        <div class="dtlms-column dtlms-one-third first"><?php esc_html_e( 'Sidebar Content Type', 'dtlms-lite');?></div>
        <div class="dtlms-column dtlms-two-third">
            <?php
            $sidebar_content_type = get_post_meta ( $post_id, 'sidebar-content-type', true );
            $sidebar_content_type = (isset($sidebar_content_type) && !empty($sidebar_content_type)) ? $sidebar_content_type : 'textarea';

            if($sidebar_content_type == 'page') {
                $sc_textarea_class = 'hidden';
                $sc_page_class = '';
            } else {
                $sc_textarea_class = '';
                $sc_page_class = 'hidden';
            }

            $sidebar_content_type_options = array (
                'textarea' => esc_html__('Text Area', 'dtlms-lite'),
                'page'     => esc_html__('Page', 'dtlms-lite')
            );

            echo '<select id="sidebar-content-type" name="sidebar-content-type" data-placeholder="'.esc_attr__('Select...', 'dtlms-lite').'" class="dtlms-chosen-select">';
                foreach ($sidebar_content_type_options as $sidebar_content_type_option_key => $sidebar_content_type_option){
                    echo '<option value="' . esc_attr( $sidebar_content_type_option_key ) . '"' . selected( $sidebar_content_type_option_key, $sidebar_content_type, false ) . '>' . esc_html( $sidebar_content_type_option ) . '</option>';
                }
            echo '</select>' ;
            ?>
            <p class="dtlms-note"> <?php esc_html_e('Choose sidebar content type you like to use.', 'dtlms-lite');?> </p>
        </div>

    </div>
    <!-- Enable Course Sidebar End -->

    <!-- Course Sidebar Content -->
    <div class="dtlms-column dtlms-one-half">

        <div class="dtlms-sidebar-content-textarea-holder <?php echo esc_attr($sc_textarea_class); ?>">

            <div class="dtlms-column dtlms-one-third first"><?php esc_html_e( 'Sidebar Content', 'dtlms-lite');?></div>
            <div class="dtlms-column dtlms-two-third">
                <?php $sidebar_content = get_post_meta($post_id, 'sidebar-content', true); ?>
                <textarea id="sidebar-content" name="sidebar-content" rows="8"><?php echo apply_filters( 'dt_sidebar_content', $sidebar_content ); ?></textarea>
                <p class="dtlms-note"> <?php esc_html_e('Sidebar content goes here. You can add any shortcode.', 'dtlms-lite');?> </p>
            </div>

        </div>

        <div class="dtlms-sidebar-content-page-holder <?php echo esc_attr($sc_page_class); ?>">

            <div class="dtlms-column dtlms-one-third first"><?php esc_html_e( 'Sidebar Page', 'dtlms-lite');?></div>
            <div class="dtlms-column dtlms-two-third">
                <?php
                $sidebar_content_page = get_post_meta ( $post_id, 'sidebar-content-page', true );

                $pages = get_pages();
                echo '<select id="sidebar-content-page" name="sidebar-content-page" data-placeholder="'.esc_attr__('Select...', 'dtlms-lite').'" class="dtlms-chosen-select">';
                    foreach ( $pages as $page ) {
                        echo '<option value="' . esc_attr( $page->ID ) . '"' . selected( $page->ID, $sidebar_content_page, false ) . '>' . esc_html( $page->post_title ) . '</option>';
                    }
                echo '</select>' ;
                ?>
                <p class="dtlms-note"> <?php esc_html_e('Choose page to use for your sidebar content.', 'dtlms-lite');?> </p>
            </div>

        </div>

    </div>
    <!-- Course Sidebar Content End -->

</div>
<?php
global $post;
$post_id         = $post->ID;
$current_user    = wp_get_current_user();
$current_user_id = $current_user->ID;

echo '<input type="hidden" name="dtlms_courses_meta_nonce" value="'.wp_create_nonce('dtlms_courses_nonce').'" />';

$tabs = array (
    'general'             => array (
        'label' => esc_html__('General', 'dtlms-lite'),
        'icon'  => 'far fa-eye',
        'path'  => DTLMS_PLUGIN_PATH . 'custom-post-types/metaboxes/courses/general.php'
    ),
    'curriculums'         => array (
        'label' => esc_html__('Curriculums', 'dtlms-lite'),
        'icon'  => 'fas fa-eye',
        'path'  => DTLMS_PLUGIN_PATH . 'custom-post-types/metaboxes/courses/curriculums.php'
    ),
    'attachments'         => array (
        'label' => esc_html__('Attachments', 'dtlms-lite'),
        'icon'  => 'fas fa-eye',
        'path'  => DTLMS_PLUGIN_PATH . 'custom-post-types/metaboxes/courses/attachments.php'
    ),
    'sidebar'             => array (
        'label' => esc_html__('Sidebar', 'dtlms-lite'),
        'icon'  => 'fas fa-eye',
        'path'  => DTLMS_PLUGIN_PATH . 'custom-post-types/metaboxes/courses/sidebar.php'
    ),
    'start-date'          => array (
        'label' => esc_html__('Start Date', 'dtlms-lite'),
        'icon'  => 'fas fa-eye',
        'path'  => DTLMS_PLUGIN_PATH . 'custom-post-types/metaboxes/courses/start-date.php'
    ),
    'capacity'            => array (
        'label' => esc_html__('Capacity', 'dtlms-lite'),
        'icon'  => 'far fa-eye',
        'path'  => DTLMS_PLUGIN_PATH . 'custom-post-types/metaboxes/courses/capacity.php'
    ),
    'course-prerequisite' => array (
        'label' => esc_html__('Course Prerequisite', 'dtlms-lite'),
        'icon'  => 'far fa-eye',
        'path'  => DTLMS_PLUGIN_PATH . 'custom-post-types/metaboxes/courses/course-prerequisite.php'
    ),
    'lock-n-drip'         => array (
        'label' => esc_html__('Completion Lock & Drip Feed', 'dtlms-lite'),
        'icon'  => 'far fa-eye',
        'path'  => DTLMS_PLUGIN_PATH . 'custom-post-types/metaboxes/courses/lock-n-drip.php'
    )
);

$tabs = apply_filters( 'dtlms_core_cpt_metabox_tabs', $tabs );?>

<div class="dtlms-tabs-vertical-container" data-effect="fade">
    <ul class="dtlms-tabs-vertical"><?php
        $i = 0;
        foreach($tabs as $tab) {

            $class = '';
            if($i == 0) { $class = 'class="current"'; }

            echo '<li '.$class.'><a href="javascript:void(0);"><span class="'.esc_attr( $tab['icon'] ).'"></span>'.esc_html( $tab['label'] ).'</a></li>';

            $i++;
        }
        ?>
    </ul>

    <?php
    $i = 0;
    foreach($tabs as $tab) {

        $style_attr = '';
        if($i == 0) { $style_attr = 'style="display: block;"'; }

        echo '<div class="dtlms-tabs-vertical-content" '.$style_attr.'>';
            echo '<h3 class="dtlms-tab-title">'.esc_html( $tab['label'] ).'</h3>';

            ob_start();
            require $tab['path'];
            $tab_content = ob_get_contents();
            ob_end_clean();

            echo $tab_content;

        echo '</div>';

        $i++;

    }
    ?>

</div>
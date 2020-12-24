<?php
function dtlms_settings_assigning_content() {

	$output = '';

	$class_title_singular = apply_filters( 'class_label', 'singular' );
	$class_title_plural   = apply_filters( 'class_label', 'plural' );

	$tabs = array (
		'courses_assign_students' => array (
			'label' => esc_html__('Course - Assign students', 'dtlms-lite'),
			'path'  => DTLMS_PLUGIN_PATH . 'settings/settings-assigning-coa-students.php'
		),
		'courses_assign_courses'  => array (
			'label' => esc_html__('Course - Assign courses', 'dtlms-lite'),
			'path'  => DTLMS_PLUGIN_PATH . 'settings/settings-assigning-coa-courses.php'
		)
	);

	$tabs = apply_filters( 'dtlms_assigning_settings', $tabs );

	$current = isset( $_GET['tab'] ) ? dtlms_recursive_sanitize_text_field( $_GET['tab'] ) : 'courses_assign_students';

	dtlms_get_assigning_settings_submenus($current, $tabs);
	dtlms_get_assigning_settings_tab($current, $tabs);

}

function dtlms_get_assigning_settings_submenus($current, $tabs) {

    echo '<h2 class="dtlms-custom-nav nav-tab-wrapper">';
		foreach( $tabs as $key => $tab ) {
			$class = ( $key == $current ) ? 'nav-tab-active' : '';
			echo '<a class="nav-tab '.esc_attr( $class ).'" href="?page=dtlms-settings-options&parenttab=assigning&tab='.esc_attr( $key ).'">'.esc_html( $tab['label'] ).'</a>';
		}
    echo '</h2>';
}

function dtlms_get_assigning_settings_tab($current, $tabs) {
	echo '<div class="dtlms-settings-assigning-container">';
		require_once $tabs[$current]['path'];
	echo '</div>';
}

dtlms_settings_assigning_content();
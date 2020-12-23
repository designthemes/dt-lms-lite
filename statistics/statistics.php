<?php

require_once DTLMS_PLUGIN_PATH . 'statistics/statistics-utils.php';

function dtlms_statistics_options($current) {

	$class_plural_label = apply_filters( 'class_label', 'plural' );
	$instructor_label   = apply_filters( 'instructor_label', 'plural' );

	$current_user    = wp_get_current_user();
	$current_user_id = $current_user->ID;

	if ( in_array( 'administrator', (array) $current_user->roles ) ) {

		require_once DTLMS_PLUGIN_PATH . 'statistics/statistics-admin.php';

		$tabs = array (
			'overview'    => array (
				'label'    => esc_html__('Overview', 'dtlms-lite'),
				'callback' => 'dtlms_statistics_overview_content'
			),
			'courses'     => array (
				'label'    => esc_html__('Courses', 'dtlms-lite'),
				'callback' => 'dtlms_statistics_courses_content'
			),
			'packages'    => array (
				'label'    => esc_html__('Packages', 'dtlms-lite'),
				'callback' => 'dtlms_statistics_packages_content'
			),
			'instructors' => array (
				'label'    => sprintf( esc_html__('%1$s', 'dtlms-lite'), $instructor_label ),
				'callback' => 'dtlms_statistics_instructors_content'
			),
			'students'    => array (
				'label'    => esc_html__('Students', 'dtlms-lite'),
				'callback' => 'dtlms_statistics_students_content'
			)
		);

   	} else if ( in_array( 'instructor', (array) $current_user->roles ) ) {

		require_once DTLMS_PLUGIN_PATH . 'statistics/statistics-instructor.php';

		$tabs = array (
			'overview'    => array (
				'label'    => esc_html__('Overview', 'dtlms-lite'),
				'callback' => 'dtlms_statistics_overview_content'
			),
			'mycourses'   => array (
				'label'    => esc_html__('Courses', 'dtlms-lite'),
				'callback' => 'dtlms_statistics_mycourses_content'
			),
			'commissions' => array (
				'label'    => esc_html__('Commissions', 'dtlms-lite'),
				'callback' => 'dtlms_statistics_commissions_content'
			)
		);

   	}

	$tabs = apply_filters( 'dtlms_statistics', $tabs );

	$current = isset( $_GET['parenttab'] ) ? sanitize_text_field( $_GET['parenttab'] ) : 'overview';

	dtlms_get_statistics_submenus($current, $tabs);
	dtlms_get_statistics_tab($current, $tabs);

}

function dtlms_get_statistics_submenus($current, $tabs) {

    echo '<h2 class="dtlms-custom-nav nav-tab-wrapper">';
		foreach( $tabs as $key => $tab ) {
			$class = ( $key == $current ) ? 'nav-tab-active' : '';
			echo '<a class="nav-tab '.esc_attr( $class ).'" href="?page=dtlms-statistics-options&parenttab='.esc_attr( $key ).'">'.esc_html( $tab['label'] ).'</a>';
		}
    echo '</h2>';

}

function dtlms_get_statistics_tab($current, $tabs) {
	echo call_user_func($tabs[$current]['callback']);
}
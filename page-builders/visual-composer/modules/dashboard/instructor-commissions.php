<?php
add_action( 'vc_before_init', 'dtlms_instructor_commissions_vc_map' );
function dtlms_instructor_commissions_vc_map() {

	$instructor_label     = apply_filters( 'instructor_label', 'singular' );
	$class_singular_label = apply_filters( 'class_label', 'singular' );

	$dtlms_cpt_items      = apply_filters( 'dtlms_cpt_items', array () );
	$dtlms_cpt_items      = array_keys($dtlms_cpt_items);

	$class_opts = array ();
	if(in_array('classes', $dtlms_cpt_items)) {
		$class_opts = array ( sprintf( esc_html__( '%1$s', 'dtlms-lite' ), $class_singular_label ) => 'class' );
	}

	vc_map( array(
		"name"        => sprintf(esc_html__( '%s Commissions', 'dtlms-lite' ), $instructor_label),
		"base"        => "dtlms_instructor_commissions",
		"icon"        => "dtlms_instructor_commissions",
		"category"    => DTLMS_PB_MODULE_DASHBOARD_TITLE,
		'description' => sprintf(esc_html__('To display the commission details of %s.', 'dtlms-lite'), $instructor_label),
		"params"      => array(

			// Enable Instructor Filter
			array(
				'type'       => 'dropdown',
				'heading'    => sprintf(esc_html__('Enable %s Filter', 'dtlms-lite'), $instructor_label),
				'param_name' => 'enable-instructor-filter',
				'value'      => array(
					esc_html__('False', 'dtlms-lite') => 'false',
					esc_html__('True', 'dtlms-lite')  => 'true',
				),
				'description' => sprintf(esc_html__('If you wish you can enable %s filter option. This option is applicable only for administrator.', 'dtlms-lite'), $instructor_label),
				'std'         => ''
			),

			// Content
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__('Content', 'dtlms-lite'),
				'param_name' => 'commission-content',
				'value'      => array_merge (
					array ( esc_html__('Course', 'dtlms-lite') => 'course' ),
					$class_opts
				),
				'description' => esc_html__('Choose content you like to display.', 'dtlms-lite'),
				'std'         => 'course'
			),

		)
	) );
}
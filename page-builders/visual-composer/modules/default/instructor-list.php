<?php
add_action( 'vc_before_init', 'dtlms_instructor_list_vc_map' );

function dtlms_instructor_list_vc_map() {

	$instructor_label        = apply_filters( 'instructor_label', 'singular' );
	$instructor_plural_label = apply_filters( 'instructor_label', 'plural' );

	vc_map( array(
		"name"     => sprintf(esc_html__('%s List', 'dtlms-lite'), $instructor_plural_label),
		"base"     => "dtlms_instructor_list",
		"icon"     => "dtlms_instructor_list",
		"category" => DTLMS_PB_MODULE_DEFAULT_TITLE,
		"params"   => array(

			// Type
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__('Type','dtlms-lite'),
				'param_name' => 'type',
				'value'      => array(
					esc_html__('Type 1', 'dtlms-lite')  => 'type1',
					esc_html__('Type 2', 'dtlms-lite')  => 'type2',
					esc_html__('Type 3', 'dtlms-lite')  => 'type3',
					esc_html__('Type 4', 'dtlms-lite')  => 'type4',
					esc_html__('Type 5', 'dtlms-lite')  => 'type5',
					esc_html__('Type 6', 'dtlms-lite')  => 'type6',
					esc_html__('Type 7', 'dtlms-lite')  => 'type7',
					esc_html__('Type 8', 'dtlms-lite')  => 'type8',
					esc_html__('Type 9', 'dtlms-lite')  => 'type9',
					esc_html__('Type 10', 'dtlms-lite') => 'type10',
				),
				'description' => sprintf(esc_html__('Choose type for your %s list', 'dtlms-lite'), $instructor_plural_label),
				'std'         => 'type1',
				'admin_label' => true
			),

			// Image Types
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__('Image Types','dtlms-lite'),
				'param_name' => 'image-types',
				'value'      => array(
					esc_html__('Default', 'dtlms-lite')             => '',
					esc_html__('Default With Border', 'dtlms-lite') => 'with-border',
					esc_html__('Rounded', 'dtlms-lite')             => 'rounded',
					esc_html__('Rounded With Border', 'dtlms-lite') => 'rounded-with-border',
				),
				'description' => sprintf(esc_html__('Choose %s image type here.', 'dtlms-lite'), $instructor_plural_label),
				'std'         => '',
			),

			// Social Icon Types
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__('Social Icon Types','dtlms-lite'),
				'param_name' => 'social-icon-types',
				'value'      => array(
					esc_html__('Default', 'dtlms-lite')         => 'default',
					esc_html__('Vibrant', 'dtlms-lite')         => 'vibrant',
					esc_html__('With Background', 'dtlms-lite') => 'with-bg',
				),
				'description' => esc_html__('Choose social icon types here.', 'dtlms-lite'),
				'std'         => 'default',
			),

			// Columns
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__('Columns', 'dtlms-lite'),
				'param_name' => 'columns',
				'value'      => array(
					esc_html__('None', 'dtlms-lite')        => '',
					esc_html__('I Column', 'dtlms-lite')    => 1,
					esc_html__('II Columns', 'dtlms-lite')  => 2,
					esc_html__('III Columns', 'dtlms-lite') => 3,
				),
				'description' => sprintf(esc_html__('Number of columns you like to display your %s.', 'dtlms-lite'), $instructor_label),
				'std'         => ''
			),

			// Include
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Include', 'dtlms-lite' ),
				'param_name'  => 'include',
				'description' => sprintf(esc_html__('List of %s ids separated by comma.', 'dtlms-lite'), $instructor_label),
			),

			// Number Of Users
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Number Of Users', 'dtlms-lite' ),
				'param_name'  => 'number',
				'description' => sprintf(esc_html__('Number of %s to display.', 'dtlms-lite'), $instructor_label),
			),

			// Class
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Class', 'dtlms-lite' ),
				'param_name'  => 'class',
				'description' => esc_html__( 'If you wish you can add additional class name here.', 'dtlms-lite' ),
			),

		)
	) );
}
?>
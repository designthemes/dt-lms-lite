<?php
add_action( 'vc_before_init', 'dtlms_course_categories_vc_map' );

function dtlms_course_categories_vc_map() {

	vc_map( array(
		"name"     => esc_html__( 'Course Categories', 'dtlms-lite' ),
		"base"     => "dtlms_course_categories",
		"icon"     => "dtlms_course_categories",
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
				'description' => esc_html__( 'Choose type of course category to display.', 'dtlms-lite' ),
				'std'         => '',
				'admin_label' => true
			),

			// Columns
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__('Columns', 'dtlms-lite'),
				'param_name' => 'columns',
				'value'      => array(
					esc_html__('I Column', 'dtlms-lite')    => 1,
					esc_html__('II Columns', 'dtlms-lite')  => 2,
					esc_html__('III Columns', 'dtlms-lite') => 3,
				),
				'description' => esc_html__( 'Number of columns you like to display your course categories.', 'dtlms-lite' ),
				'std'         => ''
			),

			// Include
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Include', 'dtlms-lite' ),
				'param_name'  => 'include',
				'description' => esc_html__( 'List of category ids separated by commas.', 'dtlms-lite' ),
			),

			// Use Icon Image
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__('Use Icon Image','dtlms-lite'),
				'param_name' => 'use-icon-image',
				'value'      => array(
					esc_html__('False', 'dtlms-lite') => 'false',
					esc_html__('True', 'dtlms-lite')  => 'true',
				),
				'description' => esc_html__( 'If you wish you can use icon image instead of icon.', 'dtlms-lite' ),
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
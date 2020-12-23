<?php
add_action( 'vc_before_init', 'dtlms_total_items_chart_vc_map' );

function dtlms_total_items_chart_vc_map() {

	$instructor_label = apply_filters( 'instructor_label', 'plural' );

	vc_map( array(
		"name"        => esc_html__( 'Total Items Chart', 'dtlms-lite' ),
		"base"        => "dtlms_total_items_chart",
		"icon"        => "dtlms_total_items_chart",
		"category"    => DTLMS_PB_MODULE_DASHBOARD_TITLE,
		'description' => esc_html__('Pie chart to show total items added so far.', 'dtlms-lite'),
		"params"      => array(

			// Chart Title
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Chart Title', 'dtlms-lite' ),
				'param_name'  => 'chart-title',
				'description' => esc_html__( 'You can give title for your chart here.', 'dtlms-lite' ),
			),

			// Chart Type
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__('Chart Type', 'dtlms-lite'),
				'param_name' => 'chart-type',
				'value'      => array(
					esc_html__('Pie', 'dtlms-lite') => 'pie',
					esc_html__('Bar', 'dtlms-lite') => 'bar',
				),
				'description' => esc_html__( 'Choose what type of chart to display', 'dtlms-lite' ),
				'admin_label' => true
			),

			// Set Unique Colors
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__('Set Unique Colors', 'dtlms-lite'),
				'param_name' => 'set-unique-colors',
				'value'      => array(
					esc_html__('False', 'dtlms-lite') => '',
					esc_html__('True', 'dtlms-lite') => 'true',
				),
				'description' => esc_html__( 'If you like to set unique colors for your chart choose "True", else colors from "Chart Settings" will be used.', 'dtlms-lite' ),
			),

			// First Color
      		array(
				'type'             => 'colorpicker',
				'heading'          => esc_html__( 'First color', 'dtlms-lite' ),
				'param_name'       => 'first-color',
				'description'      => esc_html__( 'Select first color for your chart', 'dtlms-lite' ),
				'dependency'       => array( 'element' => 'set-unique-colors', 'value' => 'true'),
				'edit_field_class' => 'vc_column vc_col-sm-6',
      		),

			// Second Color
      		array(
				'type'             => 'colorpicker',
				'heading'          => esc_html__( 'Second color', 'dtlms-lite' ),
				'param_name'       => 'second-color',
				'description'      => esc_html__( 'Select second color for your chart', 'dtlms-lite' ),
				'dependency'       => array( 'element' => 'set-unique-colors', 'value' => 'true'),
				'edit_field_class' => 'vc_column vc_col-sm-6',
      		),

			// Third Color
      		array(
				'type'             => 'colorpicker',
				'heading'          => esc_html__( 'Third color', 'dtlms-lite' ),
				'param_name'       => 'third-color',
				'description'      => esc_html__( 'Select third color for your chart', 'dtlms-lite' ),
				'dependency'       => array( 'element' => 'set-unique-colors', 'value' => 'true'),
				'edit_field_class' => 'vc_column vc_col-sm-6',
      		),

			// Fourth Color
      		array(
				'type'             => 'colorpicker',
				'heading'          => esc_html__( 'Fourth color', 'dtlms-lite' ),
				'param_name'       => 'fourth-color',
				'description'      => esc_html__( 'Select fourth color for your chart', 'dtlms-lite' ),
				'dependency'       => array( 'element' => 'set-unique-colors', 'value' => 'true'),
				'edit_field_class' => 'vc_column vc_col-sm-6',
      		),

			// Fifth Color
      		array(
				'type'             => 'colorpicker',
				'heading'          => esc_html__( 'Fifth color', 'dtlms-lite' ),
				'param_name'       => 'fifth-color',
				'description'      => esc_html__( 'Select fifth color for your chart', 'dtlms-lite' ),
				'dependency'       => array( 'element' => 'set-unique-colors', 'value' => 'true'),
				'edit_field_class' => 'vc_column vc_col-sm-6',
      		),

			// Sixth Color
      		array(
				'type'             => 'colorpicker',
				'heading'          => esc_html__( 'Sixth color', 'dtlms-lite' ),
				'param_name'       => 'sixth-color',
				'description'      => esc_html__( 'Select sixth color for your chart', 'dtlms-lite' ),
				'dependency'       => array( 'element' => 'set-unique-colors', 'value' => 'true'),
				'edit_field_class' => 'vc_column vc_col-sm-6',
      		),

			// Seventh Color
      		array(
				'type'             => 'colorpicker',
				'heading'          => esc_html__( 'Seventh color', 'dtlms-lite' ),
				'param_name'       => 'seventh-color',
				'description'      => esc_html__( 'Select seventh color for your chart', 'dtlms-lite' ),
				'dependency'       => array( 'element' => 'set-unique-colors', 'value' => 'true'),
				'edit_field_class' => 'vc_column vc_col-sm-6',
      		),

        	// Content Type
            array(
				'type'       => 'dropdown',
				'heading'    => esc_html__('Content Type', 'dtlms-lite'),
				'param_name' => 'content-type',
				'value'      => array(
					esc_html__('All Items', 'dtlms-lite')        => 'all-items',
					esc_html__('Individual Items', 'dtlms-lite') => 'individual-items',
                ),
				'description' => esc_html__( 'If administrator wishes to see the items added by him / her or all items data. This option is applicable only for administrator.', 'dtlms-lite' ),
            ),

			// Class
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Class', 'dtlms-lite' ),
				'param_name'  => 'class',
				'description' => esc_html__( 'If you wish to have additional class, you can add it here.', 'dtlms-lite' ),
			),
		)
	) );
}
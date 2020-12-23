<?php
add_action( 'vc_before_init', 'dtlms_instructor_commission_earnings_vc_map' );

function dtlms_instructor_commission_earnings_vc_map() {

	$instructor_label     = apply_filters( 'instructor_label', 'singular' );
	$class_singular_label = apply_filters( 'class_label', 'singular' );

	$params_options = array (

		// Chart Title
		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Chart Title', 'dtlms-lite' ),
			'param_name'  => 'chart-title',
			'description' => esc_html__( 'Give title for your chart.', 'dtlms-lite' ),
		),

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

		// Instructor Earnings
		array(
			'type'       => 'dropdown',
			'heading'    => esc_html__('Instructor Earnings', 'dtlms-lite'),
			'param_name' => 'instructor-earnings',
			'value'      => array(
				esc_html__('Over Period', 'dtlms-lite') => 'over-period',
				esc_html__('Over Item', 'dtlms-lite')   => 'over-item',
			),
			'description' => sprintf( esc_html__( 'You can choose between content over period ( daily, monthly, yearly ) and content over item ( Course Commisions, %1$s Commissions, Other Amounts, Total Commissions ).', 'dtlms-lite' ), $class_singular_label ),
			'std'         => 'over-period',
			'admin_label' => true
		),

		// Content Filter
		array(
			'type'       => 'dropdown',
			'heading'    => esc_html__('Content Filter', 'dtlms-lite'),
			'param_name' => 'content-filter',
			'value'      => array(
				esc_html__('Both', 'dtlms-lite')  => 'both',
				esc_html__('Chart', 'dtlms-lite') => 'chart',
				esc_html__('Data', 'dtlms-lite')  => 'data',
			),
			'description' => esc_html__( 'Would you like to show Chart or Data or Both ?', 'dtlms-lite' ),
			'std'         => 'both'
		),

		// Chart Type
		array(
			'type'       => 'dropdown',
			'heading'    => esc_html__('Chart Type', 'dtlms-lite'),
			'param_name' => 'chart-type',
			'value'      => array(
				esc_html__('Bar', 'dtlms-lite')  => 'bar',
				esc_html__('Line', 'dtlms-lite') => 'line',
				esc_html__('Pie', 'dtlms-lite')  => 'pie',
			),
			'description' => sprintf(esc_html__('Choose what type of chart to display. "Pie" chart will work only with "Over Item" - "%s Earnings"', 'dtlms-lite'), $instructor_label),
			'dependency'  => array( 'element' => 'content-filter', 'value' => array ('both', 'chart')),
			'std'         => 'bar'
		),

		// Timeline Filter
		array(
			'type'       => 'dropdown',
			'heading'    => esc_html__('Timeline Filter', 'dtlms-lite'),
			'param_name' => 'timeline-filter',
			'value'      => array(
				esc_html__('All - With Filter', 'dtlms-lite')         => 'all',
				esc_html__('Monthly - Without Filter', 'dtlms-lite')  => 'daily',
				esc_html__('Yearly - Without Filter', 'dtlms-lite')   => 'monthly',
				esc_html__('All Time - Without Filter', 'dtlms-lite') => 'alltime',
			),
			'description' => esc_html__( 'Choose timeline filter to use for content over item.', 'dtlms-lite' ),
			'dependency'  => array( 'element' => 'instructor-earnings', 'value' => 'over-item')
		),

		// Include Course Commission
		array(
			'type'       => 'dropdown',
			'heading'    => esc_html__('Include Course Commission', 'dtlms-lite'),
			'param_name' => 'include-course-commission',
			'value'      => array(
				esc_html__('False', 'dtlms-lite') => 'false',
				esc_html__('True', 'dtlms-lite')  => 'true',
			),
			'description'      => esc_html__('If you wish to include course commission amount in the chart.', 'dtlms-lite'),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'std'              => 'true'
		),

		// Include Other Commission
		array(
			'type'       => 'dropdown',
			'heading'    => esc_html__('Include Other Commission', 'dtlms-lite'),
			'param_name' => 'include-other-commission',
			'value'      => array(
				esc_html__('False', 'dtlms-lite') => 'false',
				esc_html__('True', 'dtlms-lite')  => 'true',
			),
			'description'      => esc_html__('If you wish to include other commission amount in the chart.', 'dtlms-lite'),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'std'              => ''
		),

		// Include Total Commission
		array(
			'type'       => 'dropdown',
			'heading'    => esc_html__('Include Total Commission', 'dtlms-lite'),
			'param_name' => 'include-total-commission',
			'value'      => array(
				esc_html__('False', 'dtlms-lite') => 'false',
				esc_html__('True', 'dtlms-lite')  => 'true',
			),
			'description'      => esc_html__('If you wish to include total commission amount in the chart.', 'dtlms-lite'),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'std'              => ''
		),

		 // Class
		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Class', 'dtlms-lite' ),
			'param_name'  => 'class',
			'description' => esc_html__( 'If you wish to have additional class, you can add it here.', 'dtlms-lite' ),
		)
	);

	$dtlms_cpt_items = apply_filters( 'dtlms_cpt_items', array () );
	$dtlms_cpt_items = array_keys($dtlms_cpt_items);

	if(in_array('classes', $dtlms_cpt_items)) {

		// Include Class Purchases
		$class_opts = array(
			'type'       => 'dropdown',
			'heading'    => sprintf( esc_html__( 'Include %1$s Commission', 'dtlms-lite' ), $class_singular_label ),
			'param_name' => 'include-class-commission',
			'value'      => array(
				esc_html__('False', 'dtlms-lite') => 'false',
				esc_html__('True', 'dtlms-lite')  => 'true',
			),
			'description'      => sprintf( esc_html__( 'If you wish to include %1$s commission amount in the chart.', 'dtlms-lite' ), strtolower($class_singular_label) ),
			'edit_field_class' => 'vc_column vc_col-sm-6',
			'std'              => 'false'
		);

		array_splice($params_options, 7, 0, array ( $class_opts ));
	}

	vc_map( array(
		"name"        => sprintf(esc_html__('%s Commission Earnings', 'dtlms-lite'), $instructor_label),
		"base"        => "dtlms_instructor_commission_earnings",
		"icon"        => "dtlms_instructor_commission_earnings",
		"category"    => DTLMS_PB_MODULE_DASHBOARD_TITLE,
		'description' => sprintf(esc_html__('Chart to show %s commissions earnings Over Period and Over Item.', 'dtlms-lite'), $instructor_label),
		"params"      => $params_options
	) );
}
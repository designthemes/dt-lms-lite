<?php
add_action( 'vc_before_init', 'dtlms_total_items_vc_map' );

function dtlms_total_items_vc_map() {

	$instructor_label   = apply_filters( 'instructor_label', 'plural' );
	$class_plural_label = apply_filters( 'class_label', 'plural' );

	$dtlms_cpt_items = apply_filters( 'dtlms_cpt_items', array () );
	$dtlms_cpt_items = array_flip($dtlms_cpt_items);

	vc_map( array(
		"name"        => esc_html__( 'Total Items', 'dtlms-lite' ),
		"base"        => "dtlms_total_items",
		"icon"        => "dtlms_total_items",
		"category"    => DTLMS_PB_MODULE_DASHBOARD_TITLE,
		'description' => esc_html__('It will be helpfull to display total items added in LMS.', 'dtlms-lite'),
		"params"      => array(

			// Item Type
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__('Item Type', 'dtlms-lite'),
				'param_name' => 'item-type',
				'value'      => array_merge (
					array ( esc_html__('Default', 'dtlms-lite') => '' ),
					$dtlms_cpt_items
				),
				'description' => sprintf( esc_html__( 'Choose item type to display its total items count. For %1$s total items added by them will be displayed by default.', 'dtlms-lite' ), $instructor_label ),
				'admin_label' => true
			),

			// Item Title
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Item Title', 'dtlms-lite' ),
				'param_name'  => 'item-title',
				'description' => esc_html__( 'If you wish you can change the default item title here.', 'dtlms-lite' ),
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

		)
	) );
}
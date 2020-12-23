<?php 
add_action( 'vc_before_init', 'dtlms_student_course_events_vc_map' );

function dtlms_student_course_events_vc_map() {

	vc_map( array(
		"name"        => esc_html__( 'Student Course Events', 'dtlms-lite' ),
		"base"        => "dtlms_student_course_events",
		"icon"        => "dtlms_student_course_events",
		"category"    => DTLMS_PB_MODULE_DASHBOARD_TITLE,
		'description' => esc_html__('To display student course events.', 'dtlms-lite'),
	) );
}
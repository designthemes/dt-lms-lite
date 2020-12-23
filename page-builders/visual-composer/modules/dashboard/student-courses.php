<?php 
add_action( 'vc_before_init', 'dtlms_student_courses_vc_map' );

function dtlms_student_courses_vc_map() {
	vc_map( array(
		"name"        => esc_html__( 'Student Courses', 'dtlms-lite' ),
		"base"        => "dtlms_student_courses",
		"icon"        => "dtlms_student_courses",
		"category"    => DTLMS_PB_MODULE_DASHBOARD_TITLE,
		'description' => esc_html__('To list the courses of student.', 'dtlms-lite'),
	) );
}
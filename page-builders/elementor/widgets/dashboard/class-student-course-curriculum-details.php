<?php
namespace DTElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class DTLMSDbStudentCourseCurriculumDetails extends Widget_Base {

	public function get_categories() {
		return [ 'dtlms-dashboard-widgets' ];
	}

	public function get_name() {
		return 'dtlms-widget-dashboard-student-course-curriculum-details';
	}

	public function get_title() {
		return esc_html__( 'Student Course Curriculum Details', 'dtlms-lite' );
	}

	public function get_style_depends() {
		return array ( '' );
	}

	public function get_script_depends() {
		return array ( '' );
	}

	protected function render() {
		echo do_shortcode('[dtlms_student_course_curriculum_details/]');
    }
}
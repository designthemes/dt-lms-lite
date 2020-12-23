<?php
namespace DTElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class DTLMSDbInstructorAddedCourses extends Widget_Base {

	public function get_categories() {
		return [ 'dtlms-dashboard-widgets' ];
	}

	public function get_name() {
		return 'dtlms-widget-dashboard-instructor-added-courses';
	}

	public function get_title() {
    	$instructor_label = apply_filters( 'instructor_label', 'singular' );
		return sprintf( esc_html__('%s Added Courses', 'dtlms-lite'), $instructor_label );
	}

	public function get_style_depends() {
		return array ( '' );
	}

	public function get_script_depends() {
		return array ( '' );
	}

	protected function render() {

		echo do_shortcode('[dtlms_instructor_added_courses/]');
    }
}
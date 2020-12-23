<?php
namespace DTElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class DTLMSDfCourseCategories extends Widget_Base {

	public function get_categories() {
		return [ 'dtlms-default-widgets' ];
	}

	public function get_name() {
		return 'dtlms-widget-default-course-categories';
	}

	public function get_title() {
		return esc_html__( 'Course Categories', 'dtlms-lite' );
	}

	public function get_style_depends() {
		return array ( '' );
	}

	public function get_script_depends() {
		return array ( '' );
	}

    protected function _register_controls() {
		$this->start_controls_section( 'default-course-categories-section', array(
			'label' => esc_html__( 'General', 'dtlms-lite' ),
		) );
			$this->add_control( 'type', array(
				'label'   => esc_html__( 'Type', 'dtlms-lite' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'type1'  => esc_html__('Type 1', 'dtlms-lite'),
					'type2'  => esc_html__('Type 2', 'dtlms-lite'),
					'type3'  => esc_html__('Type 3', 'dtlms-lite'),
					'type4'  => esc_html__('Type 4', 'dtlms-lite'),
					'type5'  => esc_html__('Type 5', 'dtlms-lite'),
					'type6'  => esc_html__('Type 6', 'dtlms-lite'),
					'type7'  => esc_html__('Type 7', 'dtlms-lite'),
					'type8'  => esc_html__('Type 8', 'dtlms-lite'),
					'type9'  => esc_html__('Type 9', 'dtlms-lite'),
					'type10' => esc_html__('Type 10', 'dtlms-lite'),
				),
				'description' => esc_html__( 'Choose type of course category to display.', 'dtlms-lite' ),
				'default'     => '',
			) );
			$this->add_control( 'columns', array(
				'label'   => esc_html__( 'Columns', 'dtlms-lite' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					1 => esc_html__('I Column', 'dtlms-lite'),
					2 => esc_html__('II Columns', 'dtlms-lite'),
					3 => esc_html__('III Columns', 'dtlms-lite'),
				),
				'description' => esc_html__( 'Number of columns you like to display your course categories.', 'dtlms-lite' ),
				'default'     => '',
			) );
			$this->add_control( 'include', array(
				'label'       => esc_html__( 'Include', 'dtlms-lite' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'List of category ids separated by commas.', 'dtlms-lite' ),
				'default'     => ''
			) );
			$this->add_control( 'use-icon-image', array(
				'label'   => esc_html__( 'Use Icon Image', 'dtlms-lite' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'false' => esc_html__('False', 'dtlms-lite'),
					'true'  => esc_html__('True', 'dtlms-lite'),
				),
				'description' => esc_html__( 'If you wish you can use icon image instead of icon.', 'dtlms-lite' ),
				'default'     => '',
			) );
			$this->add_control( 'class', array(
				'label'       => esc_html__( 'Class', 'dtlms-lite' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'If you wish you can add additional class name here.', 'dtlms-lite' ),
				'default'     => ''
			) );
		$this->end_controls_section();
	}

	protected function render() {
		$settings   = $this->get_settings();
		$attributes = dtlms_elementor_instance()->dtlms_parse_shortcode_attrs( $settings );
		$output     = do_shortcode('[dtlms_course_categories '.$attributes.' /]');

		echo $output;
    }

}
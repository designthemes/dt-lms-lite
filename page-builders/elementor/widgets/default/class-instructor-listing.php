<?php
namespace DTElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class DTLMSDfInstructorListing extends Widget_Base {

	public function get_categories() {
		return [ 'dtlms-default-widgets' ];
	}

	public function get_name() {
		return 'dtlms-widget-default-instructor-listing';
	}

	public function get_title() {
		$instructor_plural_label = apply_filters( 'instructor_label', 'plural' );

		return sprintf(esc_html__('%s List', 'dtlms-lite'), $instructor_plural_label);
	}

	public function get_style_depends() {
		return array ( '' );
	}

	public function get_script_depends() {
		return array ( '' );
	}

    protected function _register_controls() {

		$instructor_label        = apply_filters( 'instructor_label', 'singular' );
		$instructor_plural_label = apply_filters( 'instructor_label', 'plural' );

		$this->start_controls_section( 'default-instructor-listing-section', array(
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
				'default'     => 'type1',
			) );
			$this->add_control( 'image-types', array(
				'label'   => esc_html__( 'Image Types', 'dtlms-lite' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					''                    => esc_html__('Default', 'dtlms-lite'),
					'with-border'         => esc_html__('Default With Border', 'dtlms-lite'),
					'rounded'             => esc_html__('Rounded', 'dtlms-lite'),
					'rounded-with-border' => esc_html__('Rounded With Border', 'dtlms-lite'),
				),
				'description' => sprintf(esc_html__('Choose %s image type here.', 'dtlms-lite'), $instructor_plural_label),
				'default'     => '',
			) );
			$this->add_control( 'social-icon-types', array(
				'label'   => esc_html__( 'Social Icon Types', 'dtlms-lite' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'default' => esc_html__('Default', 'dtlms-lite'),
					'vibrant' => esc_html__('Vibrant', 'dtlms-lite'),
					'with-bg' => esc_html__('With Background', 'dtlms-lite'),
				),
				'description' => esc_html__('Choose social icon types here.', 'dtlms-lite'),
				'default'     => 'default',
			) );
			$this->add_control( 'columns', array(
				'label'   => esc_html__( 'Columns', 'dtlms-lite' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'' => esc_html__('None', 'dtlms-lite'),
					1  => esc_html__('I Column', 'dtlms-lite'),
					2  => esc_html__('II Columns', 'dtlms-lite'),
					3  => esc_html__('III Columns', 'dtlms-lite'),
				),
				'description' => sprintf(esc_html__('Number of columns you like to display your %s.', 'dtlms-lite'), $instructor_label),
				'default'     => '',
			) );
			$this->add_control( 'include', array(
				'label'       => esc_html__( 'Include', 'dtlms-lite' ),
				'type'        => Controls_Manager::TEXT,
				'description' => sprintf(esc_html__('List of %s ids separated by comma.', 'dtlms-lite'), $instructor_label),
			) );
			$this->add_control( 'number', array(
				'label'       => esc_html__( 'Number Of Users', 'dtlms-lite' ),
				'type'        => Controls_Manager::TEXT,
				'description' => sprintf(esc_html__('Number of %s to display.', 'dtlms-lite'), $instructor_label),
			) );
			$this->add_control( 'class', array(
				'label'   => esc_html__( 'Class', 'dtlms-lite' ),
				'type'    => Controls_Manager::TEXT,
				'description' => esc_html__( 'If you wish you can add additional class name here.', 'dtlms-lite' ),
				'default' => ''
			) );
		$this->end_controls_section();
	}

	protected function render() {
		$settings   = $this->get_settings();
		$attributes = dtlms_elementor_instance()->dtlms_parse_shortcode_attrs( $settings );
		$output     = do_shortcode('[dtlms_instructor_list '.$attributes.' /]');

		echo $output;
    }

}
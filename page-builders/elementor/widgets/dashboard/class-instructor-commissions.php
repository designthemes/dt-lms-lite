<?php
namespace DTElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class DTLMSDbInstructorCommissions extends Widget_Base {

	public function get_categories() {
		return [ 'dtlms-dashboard-widgets' ];
	}

	public function get_name() {
		return 'dtlms-widget-dashboard-instructor-commissions';
	}

	public function get_title() {
		$instructor_label = apply_filters( 'instructor_label', 'singular' );
		return sprintf(esc_html__( '%s Commissions', 'dtlms-lite' ), $instructor_label);
	}

	public function get_style_depends() {
		return array ( '' );
	}

	public function get_script_depends() {
		return array ( '' );
	}

    protected function _register_controls() {

		$instructor_label     = apply_filters( 'instructor_label', 'singular' );
		$class_singular_label = apply_filters( 'class_label', 'singular' );

		$dtlms_cpt_items = apply_filters( 'dtlms_cpt_items', array () );
		$dtlms_cpt_items = array_keys($dtlms_cpt_items);
	
		$class_opts = array ();
		if(in_array('classes', $dtlms_cpt_items)) {
			$class_opts = array ( 'class'  => sprintf( esc_html__( '%1$s', 'dtlms-lite' ), $class_singular_label ) );
		}

		$this->start_controls_section( 'default-dashboard-instructor-commission-section', array(
			'label' => esc_html__( 'General', 'dtlms-lite' ),
		) );
			// Enable Instructor Filter
			$this->add_control( 'enable-instructor-filter', array(
				'label'   => sprintf(esc_html__('Enable %s Filter', 'dtlms-lite'), $instructor_label),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'false' => esc_html__('False', 'dtlms-lite'),
					'true'  => esc_html__('True', 'dtlms-lite'),
				),
				'description' => sprintf(esc_html__('If you wish you can enable %s filter option. This option is applicable only for administrator.', 'dtlms-lite'), $instructor_label),
				'default'     => 'false',
			) );

			// content
			$this->add_control( 'commission-content', array( 
				'label'   => esc_html__('Content', 'dtlms-lite'),
				'type'    => Controls_Manager::SELECT,
				'options' => array_merge (
					array ( 'course' => esc_html__('Course', 'dtlms-lite') ),
					$class_opts
				),
				'description' => esc_html__('Choose content you like to display.', 'dtlms-lite'),
				'default'     => 'course'
			) );
		$this->end_controls_section();
    }

	protected function render() {
		$settings   = $this->get_settings();
		$attributes = dtlms_elementor_instance()->dtlms_parse_shortcode_attrs( $settings );
		$output     = do_shortcode('[dtlms_instructor_commissions '.$attributes.' /]');
		echo $output;		
    }
}
<?php
namespace DTElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class DTLMSDbStudentUnderevaluationItemsList extends Widget_Base {

	public function get_categories() {
		return [ 'dtlms-dashboard-widgets' ];
	}

	public function get_name() {
		return 'dtlms-widget-dashboard-student-underevaluation-items-list';
	}

	public function get_title() {
		return esc_html__( 'Student Under Evaluation Items List', 'dtlms-lite' );
	}

	public function get_style_depends() {
		return array ( '' );
	}

	public function get_script_depends() {
		return array ( '' );
	}

    protected function _register_controls() {

		$dtlms_cpt_items = apply_filters( 'dtlms_cpt_items', array () );
		$dtlms_cpt_items = array_keys($dtlms_cpt_items);

		$item_type_opts = array ();
		if(in_array('classes', $dtlms_cpt_items)) {
			$class_singular_label = apply_filters( 'class_label', 'singular' );
			$item_type_opts = array_merge ( $item_type_opts, array ( 'class' => sprintf( esc_html__( '%1$s', 'dtlms-lite' ), $class_singular_label ) ) );
		}

		$this->start_controls_section( 'default-dashboard-student-underevaluation-items-list-section', array(
			'label' => esc_html__( 'General', 'dtlms-lite' ),
		) );
			$this->add_control( 'item-type', array(
				'label'   => esc_html__( 'Item Type', 'dtlms-lite' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array_merge(
					array ( ''    => esc_html__('None', 'dtlms-lite') ),
					array ( 'course' => esc_html__('Course', 'dtlms-lite') ),
					$item_type_opts
				),
				'description' => esc_html__( 'Choose item type to display its under evaluation list.', 'dtlms-lite' ),
				'default'     => '',
			) );
		$this->end_controls_section();
    }

	protected function render() {
		$settings = $this->get_settings();

		$attributes = dtlms_elementor_instance()->dtlms_parse_shortcode_attrs( $settings );
		$output     = do_shortcode('[dtlms_student_underevaluation_items_list '.$attributes.' /]');
		echo $output;
    }
}
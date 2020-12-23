<?php
namespace DTElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class DTLMSDbTotalItems extends Widget_Base {

	public function get_categories() {
		return [ 'dtlms-dashboard-widgets' ];
	}

	public function get_name() {
		return 'dtlms-widget-dashboard-total-items';
	}

	public function get_title() {
		return esc_html__( 'Total Items', 'dtlms-lite' );
	}

	public function get_style_depends() {
		return array ( '' );
	}

	public function get_script_depends() {
		return array ( '' );
	}

    protected function _register_controls() {

		$class_plural_label = apply_filters( 'class_label', 'plural' );
		$instructor_label   = apply_filters( 'instructor_label', 'singular' );

		$dtlms_cpt_items = apply_filters( 'dtlms_cpt_items', array () );

		$this->start_controls_section( 'default-dashboard-total-items-section', array(
			'label' => esc_html__( 'General', 'dtlms-lite' ),
		) );
			$this->add_control( 'item-type', array(
				'label'   => esc_html__( 'Item Type', 'dtlms-lite' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array_merge (
					array ( '' => esc_html__('Default', 'dtlms-lite') ),
					$dtlms_cpt_items
				),
				'description' => sprintf( esc_html__( 'Choose item type to display its total items count. For %1$s total items added by them will be displayed by default.', 'dtlms-lite' ), $instructor_label ),
				'default'     => '',
			) );
			$this->add_control( 'item-title', array(
				'label'       => esc_html__( 'Item Title', 'dtlms-lite' ),
				'description' => esc_html__( 'If you wish you can change the default item title here.', 'dtlms-lite' ),
			) );
			$this->add_control( 'content-type', array(
				'label'   => esc_html__( 'Content Type', 'dtlms-lite' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'all-items'        => esc_html__('All Items', 'dtlms-lite'),
					'individual-items' => esc_html__('Individual Items', 'dtlms-lite'),
				),
				'description' => esc_html__( 'If administrator wishes to see the items added by him / her or all items data. This option is applicable only for administrator.', 'dtlms-lite' ),
				'default'     => 'all-items',
			) );
		$this->end_controls_section();
    }

	protected function render() {
		$settings = $this->get_settings();
		$attributes = dtlms_elementor_instance()->dtlms_parse_shortcode_attrs( $settings );
		$output     = do_shortcode('[dtlms_total_items '.$attributes.' /]');
		echo $output;
    }
}
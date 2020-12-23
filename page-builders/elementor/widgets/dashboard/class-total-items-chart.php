<?php
namespace DTElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class DTLMSDbTotalItemChart extends Widget_Base {

	public function get_categories() {
		return [ 'dtlms-dashboard-widgets' ];
	}

	public function get_name() {
		return 'dtlms-widget-dashboard-total-items-chart';
	}

	public function get_title() {
		return esc_html__( 'Total Items Chart', 'dtlms-lite' );
	}

	public function get_style_depends() {
		return array ( '' );
	}

	public function get_script_depends() {
		return array ( '' );
	}

    protected function _register_controls() {
		$this->start_controls_section( 'default-dashboard-total-items-chart-section', array(
			'label' => esc_html__( 'General', 'dtlms-lite' ),
		) );
			$this->add_control( 'chart-title', array(
				'label'       => esc_html__( 'Chart Title', 'dtlms-lite' ),
				'description' => esc_html__( 'You can give title for your chart here.', 'dtlms-lite' ),
			) );
			$this->add_control( 'chart-type', array(
				'label'       => esc_html__( 'Chart Type', 'dtlms-lite' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'pie' => esc_html__('Pie', 'dtlms-lite' ),
					'bar' => esc_html__('Bar', 'dtlms-lite' ),
				),
				'description' => esc_html__( 'Choose what type of chart to display', 'dtlms-lite' ),
			) );
			// Set Unique Colors
			$this->add_control( 'set-unique-colors', array(
				'label'   => esc_html__('Set Unique Colors', 'dtlms-lite'),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'false' => esc_html__('False', 'dtlms-lite'),
					'true'  => esc_html__('True', 'dtlms-lite'),
				),
				'description' => esc_html__( 'If you like to set unique colors for your chart choose "True", else colors from "Chart Settings" will be used.', 'dtlms-lite' ),
				'default'     => 'false',
			) );

			// First Color
			$this->add_control( 'first-color', array(
				'label'       => esc_html__('First color', 'dtlms-lite'),
				'description' => esc_html__( 'Select first color for your chart', 'dtlms-lite' ),
				'type'        => Controls_Manager::COLOR,
				'condition'   => array( 'set-unique-colors' => 'true' )
			) );

			// Second Color
			$this->add_control( 'second-color', array(
				'label'       => esc_html__('Second color', 'dtlms-lite'),
				'description' => esc_html__( 'Select second color for your chart', 'dtlms-lite' ),
				'type'        => Controls_Manager::COLOR,
				'condition'   => array( 'set-unique-colors' => 'true' )
			) );

			// Third Color
			$this->add_control( 'third-color', array(
				'label'       => esc_html__('Third color', 'dtlms-lite'),
				'description' => esc_html__( 'Select third color for your chart', 'dtlms-lite' ),
				'type'        => Controls_Manager::COLOR,
				'condition'   => array( 'set-unique-colors' => 'true' )
			) );

			// Fourth Color
			$this->add_control( 'fourth-color', array(
				'label'       => esc_html__('Fourth color', 'dtlms-lite'),
				'description' => esc_html__( 'Select fourth color for your chart', 'dtlms-lite' ),
				'type'        => Controls_Manager::COLOR,
				'condition'   => array( 'set-unique-colors' => 'true' )
			) );

			// Fifth Color
			$this->add_control( 'fifth-color', array(
				'label'       => esc_html__('Fifth color', 'dtlms-lite'),
				'description' => esc_html__( 'Select fifth color for your chart', 'dtlms-lite' ),
				'type'        => Controls_Manager::COLOR,
				'condition'   => array( 'set-unique-colors' => 'true' )
			) );

			// Sixth Color
			$this->add_control( 'sixth-color', array(
				'label'       => esc_html__('Sixth color', 'dtlms-lite'),
				'description' => esc_html__( 'Select sixth color for your chart', 'dtlms-lite' ),
				'type'        => Controls_Manager::COLOR,
				'condition'   => array( 'set-unique-colors' => 'true' )
			) );

			// Seventh Color
			$this->add_control( 'seventh-color', array(
				'label'       => esc_html__('Seventh color', 'dtlms-lite'),
				'description' => esc_html__( 'Select seventh color for your chart', 'dtlms-lite' ),
				'type'        => Controls_Manager::COLOR,
				'condition'   => array( 'set-unique-colors' => 'true' )
			) );

			// Content Type
			$this->add_control( 'content-type', array(
				'label'   => esc_html__('Content Type', 'dtlms-lite'),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'all-items'        => esc_html__('All Items', 'dtlms-lite'),
					'individual-items' => esc_html__('Individual Items', 'dtlms-lite'),
				),
				'description' => esc_html__( 'If administrator wishes to see the items added by him / her or all items data. This option is applicable only for administrator.', 'dtlms-lite' ),
				'default'     => 'all-items',
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

		$settings = $this->get_settings();
		$attributes = dtlms_elementor_instance()->dtlms_parse_shortcode_attrs( $settings );
		$output     = do_shortcode('[dtlms_total_items_chart '.$attributes.' /]');
		echo $output;
    }
}
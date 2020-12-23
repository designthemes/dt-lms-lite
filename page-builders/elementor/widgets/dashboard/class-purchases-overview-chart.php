<?php
namespace DTElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class DTLMSDbPurchasesOverviewChart extends Widget_Base {

	public function get_categories() {
		return [ 'dtlms-dashboard-widgets' ];
	}

	public function get_name() {
		return 'dtlms-widget-dashboard-purchases-overview-chart';
	}

	public function get_title() {
		return esc_html__( 'Purchases Overview Chart', 'dtlms-lite' );
	}

	public function get_style_depends() {
		return array ( '' );
	}

	public function get_script_depends() {
		return array ( '' );
	}

    protected function _register_controls() {

		$instructor_label = apply_filters( 'instructor_label', 'singular' );
		
		$dtlms_cpt_items  = apply_filters( 'dtlms_cpt_items', array () );
		$dtlms_cpt_items  = array_keys($dtlms_cpt_items);

		$this->start_controls_section( 'default-dashboard-purchases-overview-chart-section', array(
			'label' => esc_html__( 'General', 'dtlms-lite' ),
		) );

			// Chart Title
			$this->add_control( 'chart-title', array(
				'label'       => esc_html__( 'Chart Title', 'dtlms-lite' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'You can give title for your chart here.', 'dtlms-lite' ),
				'default'     => ''
			) );

			if(in_array('classes', $dtlms_cpt_items)) {

				$class_singular_label = apply_filters( 'class_label', 'singular' );

				// Include Class Purchases
				$this->add_control( 'include-class-purchases', array(
					'label'   => sprintf( esc_html__( 'Include %1$s Purchases', 'dtlms-lite' ), $class_singular_label ),
					'type'    => Controls_Manager::SELECT,
					'options' => array(
						'false' => esc_html__('False', 'dtlms-lite'),
						'true'  => esc_html__('True', 'dtlms-lite'),
					),
					'description' => sprintf( esc_html__( 'If you wish you can include %1$s purchases in chart.', 'dtlms-lite' ), strtolower($class_singular_label) ),
					'default'     => 'false',
				) );

			}

			// Include Course Purchases
			$this->add_control( 'include-couese-purchases', array(
				'label'   => esc_html__( 'Include Course Purchases', 'dtlms-lite' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'false' => esc_html__('False', 'dtlms-lite'),
					'true'  => esc_html__('True', 'dtlms-lite'),
				),
				'description' => esc_html__('If you wish you can include course purchases in chart.', 'dtlms-lite'),
				'default'     => 'false',
			) );

			// Include Package Purchases
			$this->add_control( 'include-package-purchases', array(
				'label'   => esc_html__( 'Include Package Purchases', 'dtlms-lite' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'false' => esc_html__('False', 'dtlms-lite'),
					'true'  => esc_html__('True', 'dtlms-lite'),
				),
				'description' => esc_html__('If you wish you can include package purchases in chart.', 'dtlms-lite'),
				'default'     => 'false',
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

			// Include Data
			$this->add_control( 'include-data', array(
				'label'   => esc_html__('Include Data', 'dtlms-lite'),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'false' => esc_html__('False', 'dtlms-lite'),
					'true'  => esc_html__('True', 'dtlms-lite'),
				),
				'description' => esc_html__('If you wish you can include data along with this chart.', 'dtlms-lite'),
				'default'     => 'false',
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
		$output     = do_shortcode('[dtlms_purchases_overview_chart '.$attributes.' /]');
		echo $output;
    }
}
<?php
namespace DTElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class DTLMSDbInstructorCommissionEarnings extends Widget_Base {

	public function get_categories() {
		return [ 'dtlms-dashboard-widgets' ];
	}

	public function get_name() {
		return 'dtlms-widget-dashboard-instructor-commission-earnings';
	}

	public function get_title() {
		$instructor_label = apply_filters( 'instructor_label', 'singular' );
		return sprintf(esc_html__('%s Commission Earnings', 'dtlms-lite'), $instructor_label);
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

		$this->start_controls_section( 'default-dashboard-instructor-commission-earnings-section', array(
			'label' => esc_html__( 'General', 'dtlms-lite' ),
		) );
			// Chart Title
			$this->add_control( 'chart-title', array(
				'label'       => esc_html__( 'Chart Title', 'dtlms-lite' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Give title for your chart.', 'dtlms-lite' ),
				'default'     => ''
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

			// Instructor Earnings
			$this->add_control( 'instructor-earnings', array(
				'label'   => esc_html__('Instructor Earnings', 'dtlms-lite'),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'over-period' => esc_html__('Over Period', 'dtlms-lite'),
					'over-item'   => esc_html__('Over Item', 'dtlms-lite'),
				),
			    'description' => sprintf( esc_html__( 'You can choose between content over period ( daily, monthly, yearly ) and content over item ( Course Commisions, %1$s Commissions, Other Amounts, Total Commissions ).', 'dtlms-lite' ), $class_singular_label ),
			    'default'     => 'over-period',
			) );

			// Content Filter
			$this->add_control( 'content-filter', array(
				'label'   => esc_html__('Content Filter', 'dtlms-lite'),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'both'  => esc_html__('Both', 'dtlms-lite'),
					'chart' => esc_html__('Chart', 'dtlms-lite'),
					'data'  => esc_html__('Data', 'dtlms-lite'),
				),
			    'description' => esc_html__( 'Would you like to show Chart or Data or Both ?', 'dtlms-lite' ),
			    'default'     => 'both',
			) );

			// Chart Type
			$this->add_control( 'chart-type', array(
				'label'   => esc_html__('Chart Type', 'dtlms-lite'),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'bar'  => esc_html__('Bar', 'dtlms-lite'),
					'line' => esc_html__('Line', 'dtlms-lite'),
					'pie'  => esc_html__('Pie', 'dtlms-lite'),
				),
				'description' => sprintf(esc_html__('Choose what type of chart to display. "Pie" chart will work only with "Over Item" - "%s Earnings"', 'dtlms-lite'), $instructor_label),
				'condition'   => array( 'content-filter' => array('both', 'chart') )
			) );

			// Timeline Filter
			$this->add_control( 'timeline-filter', array(
				'label'   => esc_html__('Timeline Filter', 'dtlms-lite'),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'all'     => esc_html__('All - With Filter', 'dtlms-lite'),
					'daily'   => esc_html__('Monthly - Without Filter', 'dtlms-lite'),
					'monthly' => esc_html__('Yearly - Without Filter', 'dtlms-lite'),
					'alltime' => esc_html__('All Time - Without Filter', 'dtlms-lite'),
				),
				'description' => esc_html__( 'Choose timeline filter to use for content over item.', 'dtlms-lite' ),
				'condition'   => array( 'instructor-earnings' => array( 'over-item' ) )
			) );

			// Include Course Commission
			$this->add_control( 'include-course-commission', array(
				'label'   => esc_html__('Include Course Commission', 'dtlms-lite'),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'false' => esc_html__('False', 'dtlms-lite'),
					'true'  => esc_html__('True', 'dtlms-lite'),
				),
				'description' => esc_html__('If you wish to include course commission amount in the chart.', 'dtlms-lite'),
				'default'     => 'true',
			) );

			if(in_array('classes', $dtlms_cpt_items)) {

				// Include Class Commission
				$this->add_control( 'include-class-commission', array(
					'label'   => sprintf( esc_html__( 'Include %1$s Commission', 'dtlms-lite' ), $class_singular_label ),
					'type'    => Controls_Manager::SELECT,
					'options' => array(
						'false' => esc_html__('False', 'dtlms-lite'),
						'true'  => esc_html__('True', 'dtlms-lite'),
					),
					'description' => sprintf( esc_html__( 'If you wish to include %1$s commission amount in the chart.', 'dtlms-lite' ), strtolower($class_singular_label) ),
					'default'     => 'true',
				) );

			}

			// Include Other Commission
			$this->add_control( 'include-other-commission', array(
				'label'   => esc_html__('Include Other Commission', 'dtlms-lite'),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'false' => esc_html__('False', 'dtlms-lite'),
					'true'  => esc_html__('True', 'dtlms-lite'),
				),
				'description' => esc_html__('If you wish to include other commission amount in the chart.', 'dtlms-lite'),
				'default'     => 'true',
			) );

			// Include Total Commission
			$this->add_control( 'include-total-commission', array(
				'label'   => esc_html__('Include Total Commission', 'dtlms-lite'),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'false' => esc_html__('False', 'dtlms-lite'),
					'true'  => esc_html__('True', 'dtlms-lite'),
				),
				'description' => esc_html__('If you wish to include total commission amount in the chart.', 'dtlms-lite'),
				'default'     => 'true',
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
		$output     = do_shortcode('[dtlms_instructor_commission_earnings '.$attributes.' /]');
		echo $output;
    }
}
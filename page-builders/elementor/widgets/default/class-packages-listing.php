<?php
namespace DTElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class DTLMSDfPackagesListing extends Widget_Base {

	public function get_categories() {
		return [ 'dtlms-default-widgets' ];
	}

	public function get_name() {
		return 'dtlms-widget-default-packages-listing';
	}

	public function get_title() {
		return esc_html__( 'Packages Listing', 'dtlms-lite' );
	}

	public function get_style_depends() {
		return array ( '' );
	}

	public function get_script_depends() {
		return array ( '' );
	}

    protected function _register_controls() {
		$this->start_controls_section( 'default-package-listing-section', array(
			'label' => esc_html__( 'General', 'dtlms-lite' ),
		) );
			$this->add_control( 'display-type', array(
				'label'       => esc_html__( 'Display Type', 'dtlms-lite' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'grid' => esc_html__('Grid', 'dtlms-lite'),
					'list' => esc_html__('List', 'dtlms-lite'),
				),
				'description' => esc_html__( 'Choose display type for your packages listing.', 'dtlms-lite' ),
				'default'     => 'grid',
			) );
			$this->add_control( 'post-per-page', array(
				'label'       => esc_html__('Post Per Page', 'dtlms-lite'),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Number of posts to show.', 'dtlms-lite' ),
				'default'     => -1,
			) );
			$this->add_control( 'columns', array(
				'label'       => esc_html__( 'Columns', 'dtlms-lite' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => true,
				'options'     => array(
					1 => esc_html__('I Column', 'dtlms-lite'),
					2 => esc_html__('II Columns', 'dtlms-lite'),
					3 => esc_html__('III Columns', 'dtlms-lite'),
				),
				'default'     => 1,
				'description' => esc_html__( 'Number of columns you like to display your packages.', 'dtlms-lite' ),
			) );
			$this->add_control( 'apply-isotope', array(
				'label'       => esc_html__( 'Apply Isotope', 'dtlms-lite' ),
				'label_block' => true,
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'false' => esc_html__('False', 'dtlms-lite'),
					'true'  => esc_html__('True', 'dtlms-lite'),
				),
				'default'     => 'false',
				'description' => esc_html__( 'If you like to apply isotope for your packages listing, choose "True".', 'dtlms-lite' ),
			) );
			$this->add_control( 'type', array(
				'label'       => esc_html__( 'Type', 'dtlms-lite' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => true,
				'options'     => array(
					'type1' => esc_html__('Type 1', 'dtlms-lite'),
					'type2' => esc_html__('Type 2', 'dtlms-lite'),
					'type3' => esc_html__('Type 3', 'dtlms-lite'),
				),
				'description' => esc_html__( 'Choose any of the available design types.', 'dtlms-lite' ),
				'default'     => 'type1'
			) );
			$this->add_control( 'package-item-ids', array(
				'label'       => esc_html__('Package Item Ids', 'dtlms-lite'),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Enter package item ids separated by comma to display from.', 'dtlms-lite' ),
			) );
		$this->end_controls_section();

		$this->start_controls_section( 'default-package-listing-carousel-section', array(
			'label'     => esc_html__( 'Carousel', 'dtlms-lite' ),
			'condition' => array( 'apply-isotope' => 'false' ),
		) );
			$this->add_control( 'enable-carousel', array(
				'label'       => esc_html__( 'Enable Carousel', 'dtlms-lite' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => true,
				'options'     => array(
					''     => esc_html__('False', 'dtlms-lite'),
					'true' => esc_html__('True', 'dtlms-lite'),
				),
				'description' => esc_html__( 'If you wish you can enable carousel for course listings. This option is not applicable if "Default - Ajax Output" is not chosen in "Listing Output Page". "Carousel" won\'t work along with "Apply Isotope".', 'dtlms-lite' ),
				'default'     => '',
				'condition'   => array( 'apply-isotope' => 'false' ),
			) );
			$this->add_control( 'carousel-autoplay', array(
				'label'       => esc_html__( 'Auto Play', 'dtlms-lite' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Delay between transitions ( in ms, ex. 1000 ). Leave empty if you don\'t want to auto play.', 'dtlms-lite' ),
				'condition'   => array( 'enable-carousel' => 'true' ),
				'default'     => ''
			) );
			$this->add_control( 'carousel-slidesperview', array(
				'label'   => esc_html__( 'Slides Per View', 'dtlms-lite' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					1 => 1,
					2 => 2,
					3 => 3,
				),
				'description' => esc_html__( 'Number slides of to show in view port. If display type is "List", 2 & 3 option in "Slides Per View" won\'t work.', 'dtlms-lite' ),
				'default'     => 2,
				'condition'   => array( 'enable-carousel' => 'true' ),
			) );
			$this->add_control( 'carousel-loopmode', array(
				'label'   => esc_html__( 'Enable Loop Mode', 'dtlms-lite' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'false' => esc_html__('False', 'dtlms-lite'),
					'true'  => esc_html__('True', 'dtlms-lite'),
				),
				'description' => esc_html__( 'If you wish you can enable continous loop mode for your carousel.', 'dtlms-lite' ),
				'default'     => 'false',
				'condition'   => array( 'enable-carousel' => 'true' ),
			) );
			$this->add_control( 'carousel-mousewheelcontrol', array(
				'label'   => esc_html__( 'Enable Mousewheel Control', 'dtlms-lite' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'false' => esc_html__('False', 'dtlms-lite'),
					'true'  => esc_html__('True', 'dtlms-lite'),
				),
				'description' => esc_html__( 'If you wish you can enable mouse wheel control for your carousel.', 'dtlms-lite' ),
				'default'     => 'false',
				'condition'   => array( 'enable-carousel' => 'true' ),
			) );
			$this->add_control( 'carousel-bulletpagination', array(
				'label'   => esc_html__( 'Enable Bullet Pagination', 'dtlms-lite' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'false' => esc_html__('False', 'dtlms-lite'),
					'true'  => esc_html__('True', 'dtlms-lite'),
				),
				'description' => esc_html__( 'To enable bullet pagination.', 'dtlms-lite' ),
				'default'     => 'false',
				'condition'   => array( 'enable-carousel' => 'true' ),
			) );
			$this->add_control( 'carousel-arrowpagination', array(
				'label'   => esc_html__( 'Enable Arrow Pagination', 'dtlms-lite' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'false' => esc_html__('False', 'dtlms-lite'),
					'true'  => esc_html__('True', 'dtlms-lite'),
				),
				'description' => esc_html__( 'To enable arrow pagination.', 'dtlms-lite' ),
				'default'     => 'false',
				'condition'   => array( 'enable-carousel' => 'true' ),
			) );
			$this->add_control( 'carousel-spacebetween', array(
				'label'       => esc_html__( 'Space Between Sliders', 'dtlms-lite' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Space between sliders can be given here.', 'dtlms-lite' ),
				'condition'   => array( 'enable-carousel' => 'true' ),
				'default'     => 0
			) );
		$this->end_controls_section();
	}

	protected function render() {
		$settings   = $this->get_settings();
		$attributes = dtlms_elementor_instance()->dtlms_parse_shortcode_attrs( $settings );

		$output = do_shortcode('[dtlms_packages_listing '.$attributes.' /]');
		echo $output;
    }
}
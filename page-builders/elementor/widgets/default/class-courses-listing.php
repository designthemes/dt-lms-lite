<?php
namespace DTElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class DTLMSDfCoursesListing extends Widget_Base {

	public function get_categories() {
		return [ 'dtlms-default-widgets' ];
	}

	public function get_name() {
		return 'dtlms-widget-default-courses-listing';
	}

	public function get_title() {
		return esc_html__( 'Courses Listing', 'dtlms-lite' );
	}

	public function get_style_depends() {
		return array ( '' );
	}

	public function get_script_depends() {
		return array ( '' );
	}

    protected function _register_controls() {

		$instructor_label = apply_filters( 'instructor_label', 'singular' );
		$dtlms_pages_list = array ();
		$dtlms_pages_list[''] = esc_html__('Default - Ajax Output', 'dtlms-lite');
		$pages = get_pages();
		foreach ( $pages as $page ) {
			$dtlms_pages_list[ $page->ID] = $page->post_title;
		}

		$this->start_controls_section( 'default-course-listing-section', array(
			'label' => esc_html__( 'General', 'dtlms-lite' ),
		) );
			$this->add_control( 'disable-all-filters', array(
				'label'       => esc_html__( 'Disable All Filter Options', 'dtlms-lite' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => true,
				'options'     => array(
					'false' => esc_html__('False', 'dtlms-lite'),
					'true'  => esc_html__('True', 'dtlms-lite'),
				),
				'description' => esc_html__( 'If you wish you can disable all filter options and only course content will be displayed.', 'dtlms-lite' ),
				'default'     => 'false',
			) );
			$this->add_control( 'enable-search-filter', array(
				'label'       => esc_html__( 'Enable Search Filter', 'dtlms-lite' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => true,
				'options'     => array(
					'false' => esc_html__('False', 'dtlms-lite'),
					'true'  => esc_html__('True', 'dtlms-lite'),
				),
				'description' => esc_html__( 'If you wish you can enable search filter option.', 'dtlms-lite' ),
				'default'     => 'false',
				'condition'   => array( 'disable-all-filters' => 'false' )
			) );
			$this->add_control( 'enable-display-filter', array(
				'label'       => esc_html__( 'Enable Display Filter', 'dtlms-lite' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => true,
				'options'     => array(
					'false' => esc_html__('False', 'dtlms-lite'),
					'true'  => esc_html__('True', 'dtlms-lite'),
				),
				'description' => esc_html__( 'If you wish you can enable display filter option.', 'dtlms-lite' ),
				'default'     => 'false',
				'condition'   => array( 'disable-all-filters' => 'false' )
			) );
			$this->add_control( 'enable-orderby-filter', array(
				'label'       => esc_html__( 'Enable Order By Filter', 'dtlms-lite' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => true,
				'options'     => array(
					'false' => esc_html__('False', 'dtlms-lite'),
					'true'  => esc_html__('True', 'dtlms-lite'),
				),
				'description' => esc_html__( 'If you wish you can enable orderby filter option.', 'dtlms-lite' ),
				'default'     => 'false',
				'condition'   => array( 'disable-all-filters' => 'false' )
			) );
			$this->add_control( 'enable-category-filter', array(
				'label'       => esc_html__( 'Enable Category Filter', 'dtlms-lite' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => true,
				'options'     => array(
					'false' => esc_html__('False', 'dtlms-lite'),
					'true'  => esc_html__('True', 'dtlms-lite'),
				),
				'description' => esc_html__( 'If you wish you can enable category filter option.', 'dtlms-lite' ),
				'default'     => 'false',
				'condition'   => array( 'disable-all-filters' => 'false' )
			) );
			$this->add_control( 'enable-instructor-filter', array(
				'label'       => sprintf(esc_html__('Enable %s Filter', 'dtlms-lite'), $instructor_label),
				'type'        => Controls_Manager::SELECT,
				'label_block' => true,
				'options'     => array(
					'false' => esc_html__('False', 'dtlms-lite'),
					'true'  => esc_html__('True', 'dtlms-lite'),
				),
				'description' => esc_html__( 'If you wish you can enable instructor filter option.', 'dtlms-lite' ),
				'default'     => 'false',
				'condition'   => array( 'disable-all-filters' => 'false' )
			) );
			$this->add_control( 'enable-cost-filter', array(
				'label'       => esc_html__( 'Enable Cost Filter', 'dtlms-lite' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => true,
				'options'     => array(
					'false' => esc_html__('False', 'dtlms-lite'),
					'true'  => esc_html__('True', 'dtlms-lite'),
				),
				'description' => esc_html__( 'If you wish you can enable cost filter option.', 'dtlms-lite' ),
				'default'     => 'false',
				'condition'   => array( 'disable-all-filters' => 'false' )
			) );
			$this->add_control( 'enable-date-filter', array(
				'label'       => esc_html__( 'Enable Date Filter', 'dtlms-lite' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => true,
				'options'     => array(
					'false' => esc_html__('False', 'dtlms-lite'),
					'true'  => esc_html__('True', 'dtlms-lite'),
				),
				'description' => esc_html__( 'If you wish you can enable date filter option.', 'dtlms-lite' ),
				'default'     => 'false',
				'condition'   => array( 'disable-all-filters' => 'false' )
			) );
			$this->add_control( 'listing-output-page', array(
				'label'       => esc_html__( 'Listing Output Page', 'dtlms-lite' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => true,
				'options'     => $dtlms_pages_list,
				'description' => esc_html__( 'If you choose a page here course search result will be outputed in that page. For that you have to add this course listing shortcode again in that page.', 'dtlms-lite' ),
			) );
			$this->add_control( 'default-filter', array(
				'label'       => esc_html__( 'Default Filter', 'dtlms-lite' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => true,
				'options'     => array(
					''                      => esc_html__('None', 'dtlms-lite'),
					'upcoming-courses'      => esc_html__('Upcoming Courses', 'dtlms-lite'),
					'recent-courses'        => esc_html__('Recent Courses', 'dtlms-lite'),
					'highest-rated-courses' => esc_html__('Highest Rated Courses', 'dtlms-lite'),
					'most-membered-courses' => esc_html__('Most Membered Courses', 'dtlms-lite'),
					'paid-courses'          => esc_html__('Paid Courses', 'dtlms-lite'),
					'free-courses'          => esc_html__('Free Courses', 'dtlms-lite'),
				),
				'condition'   => array( 'disable-all-filters' => 'true' ),
				'default'     => '',
				'description' => esc_html__( 'Choose default filter you like to apply in courses listing. This option is not applicable if "Default - Ajax Output" is not chosen in "Listing Output Page".', 'dtlms-lite' ),
			) );
			$this->add_control( 'course-item-ids', array(
				'label'       => esc_html__('Course Item Ids','dtlms-lite'),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Enter course item ids separated by comma to display from. This option is not applicable if "Default - Ajax Output" is not chosen in "Listing Output Page".', 'dtlms-lite' ),
				'condition'   => array( 'disable-all-filters' => 'true' ),
			) );
			$this->add_control( 'course-category-ids', array(
				'label'       => esc_html__('Course Category Ids','dtlms-lite'),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Enter course category separated by comma to display from. This option is not applicable if "Default - Ajax Output" is not chosen in "Listing Output Page".', 'dtlms-lite' ),
				'condition'   => array( 'disable-all-filters' => 'true' ),
			) );
			$this->add_control( 'instructor-ids', array(
				'label'       => sprintf(esc_html__('%s Ids', 'dtlms-lite'), $instructor_label),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'description' => sprintf(esc_html__('Enter %s ids separated by comma to display from. This option is not applicable if "Default - Ajax Output" is not chosen in "Listing Output Page".', 'dtlms-lite'), $instructor_label),
				'condition'   => array( 'disable-all-filters' => 'true' ),
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
				'description' => esc_html__( 'If you like to apply isotope for your courses listing, choose "True". This option is not applicable if "Default - Ajax Output" is not chosen in "Listing Output Page". "Apply Isotope" won\'t work along with "Carousel".', 'dtlms-lite' ),
			) );
			$this->add_control( 'enable-category-isotope-filter', array(
				'label'       => esc_html__( 'Enable Category Isotope Filter', 'dtlms-lite' ),
				'label_block' => true,
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'false' => esc_html__('False', 'dtlms-lite'),
					'true'  => esc_html__('True', 'dtlms-lite'),
				),
				'default'     => 'false',
				'description' => esc_html__( 'You can enable category isotope filter for your course listing. This option is not applicable if "Default - Ajax Output" is not chosen in "Listing Output Page".', 'dtlms-lite' ),
			) );
			$this->add_control( 'show-author-details', array(
				'label'       => esc_html__( 'Show Author Details', 'dtlms-lite' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => true,
				'options'     => array(
					'false' => esc_html__('False', 'dtlms-lite'),
					'true'  => esc_html__('True', 'dtlms-lite'),
				),
				'default'     => 'false',
				'description' => esc_html__( 'If you like to show author details along with course. This option is not applicable if "Default - Ajax Output" is not chosen in "Listing Output Page".', 'dtlms-lite' ),
			) );
			$this->add_control( 'default-display-type', array(
				'label'       => esc_html__( 'Display Type', 'dtlms-lite' ),
				'label_block' => true,
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'grid' => esc_html__('Grid', 'dtlms-lite'),
					'list' => esc_html__('List', 'dtlms-lite'),
				),
				'default'     => 'grid',
				'description' => esc_html__( 'Choose display type for your courses listing. This option is not applicable if "Default - Ajax Output" is not chosen in "Listing Output Page".', 'dtlms-lite' ),
			) );
			$this->add_control( 'post-per-page', array(
				'label'       => esc_html__('Post Per Page', 'dtlms-lite'),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Number of posts to show. This option is not applicable if "Default - Ajax Output" is not chosen in "Listing Output Page".', 'dtlms-lite' ),
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
				'default' => 1,
				'description' => esc_html__( 'Number of columns you like to display your courses. III Columns option will work only if "Enable Fullwidth" is set to "True". Also III Columns option is applicable for "Grid View" only when all filters are disabled. This option is not applicable if "Default - Ajax Output" is not chosen in "Listing Output Page".', 'dtlms-lite' ),
				'condition'   => array( 'default-display-type' => 'grid' ),
			) );
			$this->add_control( 'enable-fullwidth', array(
				'label'       => esc_html__( 'Enable Fullwidth', 'dtlms-lite' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => true,
				'options'     => array(
					'false' => esc_html__('False', 'dtlms-lite'),
					'true'  => esc_html__('True', 'dtlms-lite'),
				),
				'default'     => 'false',
				'description' => esc_html__( 'If you wish you can enable fullwidth for your course listings. This option is not applicable if "Default - Ajax Output" is not chosen in "Listing Output Page".', 'dtlms-lite' ),
			) );
			$this->add_control( 'type', array(
				'label'       => esc_html__( 'Type', 'dtlms-lite' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => true,
				'options'     => array(
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
				'description' => esc_html__( 'Choose any of the available design types.', 'dtlms-lite' ),
				'default' => 'type1'
			) );
			$this->add_control( 'show-description', array(
				'label'       => esc_html__( 'Show Description', 'dtlms-lite' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => true,
				'options'     => array(
					''     => esc_html__('False', 'dtlms-lite'),
					'true' => esc_html__('True', 'dtlms-lite'),
				),
				'description' => esc_html__( 'If you like to show description along with the post.', 'dtlms-lite' ),
				'default'     => ''
			) );
			$this->add_control( 'class', array(
				'label'       => esc_html__( 'Class', 'dtlms-lite' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'description' => esc_html__( 'If you wish you can add additional class name here.', 'dtlms-lite' ),
				'default'     => ''
			) );
		$this->end_controls_section();

		$this->start_controls_section( 'default-course-listing-carousel-section', array(
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
			$this->add_control( 'carousel-effect', array(
				'label'       => esc_html__( 'Effect', 'dtlms-lite' ),
				'label_block' => true,
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					''     => esc_html__('Default', 'dtlms-lite'),
					'fade' => esc_html__('Fade', 'dtlms-lite'),
				),
				'description' => esc_html__( 'Choose effect for your carousel. Slides Per View has to be 1 for Fade effect.', 'dtlms-lite' ),
				'default'     => '',
				'condition'   => array( 'enable-carousel' => 'true' ),

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
				'default'     => '',
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
				'default'     => '',
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

		$output = do_shortcode('[dtlms_courses_listing '.$attributes.' /]');
		echo $output;
    }

}
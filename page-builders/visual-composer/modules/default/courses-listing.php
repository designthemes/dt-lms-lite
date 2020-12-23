<?php
add_action( 'vc_before_init', 'dtlms_courses_listing_vc_map' );

function dtlms_courses_listing_vc_map() {

	$instructor_label = apply_filters( 'instructor_label', 'singular' );

	$dtlms_pages_list = array ();
	$dtlms_pages_list[esc_html__('Default - Ajax Output', 'dtlms-lite')] = '';
	$pages = get_pages();
	foreach ( $pages as $page ) {
		$dtlms_pages_list[$page->post_title] = $page->ID;
	}

	vc_map( array(
		"name"     => esc_html__( 'Courses Listing', 'dtlms-lite' ),
		"base"     => "dtlms_courses_listing",
		"icon"     => "dtlms_courses_listing",
		"category" => DTLMS_PB_MODULE_DEFAULT_TITLE,
		"params"   => array(

			// Disable All Filter Options
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__('Disable All Filter Options','dtlms-lite'),
				'param_name' => 'disable-all-filters',
				'value'      => array(
					esc_html__('False', 'dtlms-lite') => 'false',
					esc_html__('True', 'dtlms-lite')  => 'true',
				),
				'description' => esc_html__( 'If you wish you can disable all filter options and only course content will be displayed.', 'dtlms-lite' ),
				'std'         => ''
			),

			// Enable Search Filter
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__('Enable Search Filter','dtlms-lite'),
				'param_name' => 'enable-search-filter',
				'value'      => array(
					esc_html__('False', 'dtlms-lite') => 'false',
					esc_html__('True', 'dtlms-lite')  => 'true',
				),
				'description'      => esc_html__( 'If you wish you can enable search filter option.', 'dtlms-lite' ),
				'dependency'       => array( 'element' => 'disable-all-filters', 'value' => 'false'),
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'std'              => ''
			),

			// Enable Display Filter
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__('Enable Display Filter','dtlms-lite'),
				'param_name' => 'enable-display-filter',
				'value'      => array(
					esc_html__('False', 'dtlms-lite') => 'false',
					esc_html__('True', 'dtlms-lite')  => 'true',
				),
				'description'      => esc_html__( 'If you wish you can enable display filter option.', 'dtlms-lite' ),
				'dependency'       => array( 'element' => 'disable-all-filters', 'value' => 'false'),
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'std'              => ''
			),

			// Enable Order By Filter
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__('Enable Order By Filter','dtlms-lite'),
				'param_name' => 'enable-orderby-filter',
				'value'      => array(
					esc_html__('False', 'dtlms-lite') => 'false',
					esc_html__('True', 'dtlms-lite')  => 'true',
				),
				'description'      => esc_html__( 'If you wish you can enable orderby filter option.', 'dtlms-lite' ),
				'dependency'       => array( 'element' => 'disable-all-filters', 'value' => 'false'),
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'std'              => ''
			),

			// Enable Category Filter
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__('Enable Category Filter','dtlms-lite'),
				'param_name' => 'enable-category-filter',
				'value'      => array(
					esc_html__('False', 'dtlms-lite') => 'false',
					esc_html__('True', 'dtlms-lite')  => 'true',
				),
				'description'      => esc_html__( 'If you wish you can enable category filter option.', 'dtlms-lite' ),
				'dependency'       => array( 'element' => 'disable-all-filters', 'value' => 'false'),
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'std'              => ''
			),

			// Enable Instructor Filter
			array(
				'type'       => 'dropdown',
				'heading'    => sprintf(esc_html__('Enable %s Filter', 'dtlms-lite'), $instructor_label),
				'param_name' => 'enable-instructor-filter',
				'value'      => array(
					esc_html__('False', 'dtlms-lite') => 'false',
					esc_html__('True', 'dtlms-lite')  => 'true',
				),
				'description'      => esc_html__( 'If you wish you can enable instructor filter option.', 'dtlms-lite' ),
				'dependency'       => array( 'element' => 'disable-all-filters', 'value' => 'false'),
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'std'              => ''
			),

			// Enable Cost Filter
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__('Enable Cost Filter','dtlms-lite'),
				'param_name' => 'enable-cost-filter',
				'value'      => array(
					esc_html__('False', 'dtlms-lite') => 'false',
					esc_html__('True', 'dtlms-lite')  => 'true',
				),
				'description'      => esc_html__( 'If you wish you can enable cost filter option.', 'dtlms-lite' ),
				'dependency'       => array( 'element' => 'disable-all-filters', 'value' => 'false'),
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'std'              => ''
			),

			// Enable Date Filter
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__('Enable Date Filter','dtlms-lite'),
				'param_name' => 'enable-date-filter',
				'value'      => array(
					esc_html__('False', 'dtlms-lite') => 'false',
					esc_html__('True', 'dtlms-lite')  => 'true',
				),
				'description' => esc_html__( 'If you wish you can enable date filter option.', 'dtlms-lite' ),
				'dependency'  => array( 'element' => 'disable-all-filters', 'value' => 'false'),
				'std'         => ''
			),

			// Listing Output Page
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__('Listing Output Page','dtlms-lite'),
				'param_name'  => 'listing-output-page',
				'value'       => $dtlms_pages_list,
				'description' => esc_html__( 'If you choose a page here course search result will be outputed in that page. For that you have to add this course listing shortcode again in that page.', 'dtlms-lite' ),
				'std'         => ''
			),

			// Default Filter
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__('Default Filter','dtlms-lite'),
				'param_name' => 'default-filter',
				'value'      => array(
					esc_html__('None', 'dtlms-lite')                  => '',
					esc_html__('Upcoming Courses', 'dtlms-lite')      => 'upcoming-courses',
					esc_html__('Recent Courses', 'dtlms-lite')        => 'recent-courses',
					esc_html__('Highest Rated Courses', 'dtlms-lite') => 'highest-rated-courses',
					esc_html__('Most Membered Courses', 'dtlms-lite') => 'most-membered-courses',
					esc_html__('Paid Courses', 'dtlms-lite')          => 'paid-courses',
					esc_html__('Free Courses', 'dtlms-lite')          => 'free-courses',
				),
				'description'      => esc_html__( 'Choose default filter you like to apply in courses listing. This option is not applicable if "Default - Ajax Output" is not chosen in "Listing Output Page".', 'dtlms-lite' ),
				'dependency'       => array( 'element' => 'disable-all-filters', 'value' => 'true'),
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'std'              => ''
			),

			// Course Item Ids
			array(
				'type'             => 'textfield',
				'heading'          => esc_html__('Course Item Ids','dtlms-lite'),
				'param_name'       => 'course-item-ids',
				'value'            => '',
				'description'      => esc_html__( 'Enter course item ids separated by comma to display from. This option is not applicable if "Default - Ajax Output" is not chosen in "Listing Output Page".', 'dtlms-lite' ),
				'dependency'       => array( 'element' => 'disable-all-filters', 'value' => 'true'),
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'std'              => ''
			),

			// Course Category Ids
			array(
				'type'             => 'textfield',
				'heading'          => esc_html__('Course Category Ids','dtlms-lite'),
				'param_name'       => 'course-category-ids',
				'value'            => '',
				'description'      => esc_html__( 'Enter course category separated by comma to display from. This option is not applicable if "Default - Ajax Output" is not chosen in "Listing Output Page".', 'dtlms-lite' ),
				'dependency'       => array( 'element' => 'disable-all-filters', 'value' => 'true'),
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'std'              => ''
			),

			// Instructor Ids
			array(
				'type'             => 'textfield',
				'heading'          => sprintf(esc_html__('%s Ids', 'dtlms-lite'), $instructor_label),
				'param_name'       => 'instructor-ids',
				'value'            => '',
				'description'      => sprintf(esc_html__('Enter %s ids separated by comma to display from. This option is not applicable if "Default - Ajax Output" is not chosen in "Listing Output Page".', 'dtlms-lite'), $instructor_label),
				'dependency'       => array( 'element' => 'disable-all-filters', 'value' => 'true'),
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'std'              => ''
			),


			// Apply Isotope
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__('Apply Isotope','dtlms-lite'),
				'param_name' => 'apply-isotope',
				'value'      => array(
					esc_html__('False', 'dtlms-lite') => 'false',
					esc_html__('True', 'dtlms-lite')  => 'true',
				),
				'description'      => esc_html__( 'If you like to apply isotope for your courses listing, choose "True". This option is not applicable if "Default - Ajax Output" is not chosen in "Listing Output Page". "Apply Isotope" won\'t work along with "Carousel".', 'dtlms-lite' ),
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'std'              => ''
			),

			// Enable Category Isotope Filter
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__('Enable Category Isotope Filter','dtlms-lite'),
				'param_name' => 'enable-category-isotope-filter',
				'value'      => array(
					esc_html__('False', 'dtlms-lite') => 'false',
					esc_html__('True', 'dtlms-lite')  => 'true',
				),
				'description'      => esc_html__( 'You can enable category isotope filter for your course listing. This option is not applicable if "Default - Ajax Output" is not chosen in "Listing Output Page".', 'dtlms-lite' ),
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'std'              => ''
			),

			// Show Author Details
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__('Show Author Details','dtlms-lite'),
				'param_name' => 'show-author-details',
				'value'      => array(
					esc_html__('False', 'dtlms-lite') => 'false',
					esc_html__('True', 'dtlms-lite')  => 'true',
				),
				'description'      => esc_html__( 'If you like to show author details along with course. This option is not applicable if "Default - Ajax Output" is not chosen in "Listing Output Page".', 'dtlms-lite' ),
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'std'              => ''
			),

			// Display Type
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__('Display Type','dtlms-lite'),
				'param_name' => 'default-display-type',
				'value'      => array(
					esc_html__('Grid', 'dtlms-lite') => 'grid',
					esc_html__('List', 'dtlms-lite') => 'list',
				),
				'description'      => esc_html__( 'Choose display type for your courses listing. This option is not applicable if "Default - Ajax Output" is not chosen in "Listing Output Page".', 'dtlms-lite' ),
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'std'              => 'grid'
			),

			// Post Per Page
			array(
				'type'             => 'textfield',
				'heading'          => esc_html__( 'Post Per Page', 'dtlms-lite' ),
				'param_name'       => 'post-per-page',
				'description'      => esc_html__( 'Number of posts to show. This option is not applicable if "Default - Ajax Output" is not chosen in "Listing Output Page".', 'dtlms-lite' ),
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'std'              => -1
			),

			// Columns
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__('Columns', 'dtlms-lite'),
				'param_name' => 'columns',
				'value'      => array(
					esc_html__('I Column', 'dtlms-lite')    => 1,
					esc_html__('II Columns', 'dtlms-lite')  => 2,
					esc_html__('III Columns', 'dtlms-lite') => 3,
				),
				'description'      => esc_html__( 'Number of columns you like to display your courses. III Columns option will work only if "Enable Fullwidth" is set to "True". Also III Columns option is applicable for "Grid View" only when all filters are disabled. This option is not applicable if "Default - Ajax Output" is not chosen in "Listing Output Page".', 'dtlms-lite' ),
				'std'              => 1,
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'dependency'       => array( 'element' => 'default-display-type', 'value' => 'grid'),
			),

			// Enable Fullwidth
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__('Enable Fullwidth','dtlms-lite'),
				'param_name' => 'enable-fullwidth',
				'value'      => array(
					esc_html__('False','dtlms-lite') => '',
					esc_html__('True','dtlms-lite')  => 'true',
				),
				'description' => esc_html__( 'If you wish you can enable fullwidth for your course listings. This option is not applicable if "Default - Ajax Output" is not chosen in "Listing Output Page".', 'dtlms-lite' ),
				'std'              => '',
				'edit_field_class' => 'vc_column vc_col-sm-6',
			),

			// Type
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__('Type','dtlms-lite'),
				'param_name' => 'type',
				'value'      => array(
					esc_html__('Type 1', 'dtlms-lite')  => 'type1',
					esc_html__('Type 2', 'dtlms-lite')  => 'type2',
					esc_html__('Type 3', 'dtlms-lite')  => 'type3',
					esc_html__('Type 4', 'dtlms-lite')  => 'type4',
					esc_html__('Type 5', 'dtlms-lite')  => 'type5',
					esc_html__('Type 6', 'dtlms-lite')  => 'type6',
					esc_html__('Type 7', 'dtlms-lite')  => 'type7',
					esc_html__('Type 8', 'dtlms-lite')  => 'type8',
					esc_html__('Type 9', 'dtlms-lite')  => 'type9',
					esc_html__('Type 10', 'dtlms-lite') => 'type10',
				),
				'description'      => esc_html__( 'Choose any of the available design types.', 'dtlms-lite' ),
				'edit_field_class' => 'vc_column vc_col-sm-6',
				'std'              => 'type1'
			),

			// Show Description
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__('Show Description','dtlms-lite'),
				'param_name' => 'show-description',
				'value'      => array(
					esc_html__('False','dtlms-lite') => '',
					esc_html__('True','dtlms-lite')  => 'true',
				),
				'description'      => esc_html__( 'If you like to show description along with the post.', 'dtlms-lite' ),
				'std'              => '',
				'edit_field_class' => 'vc_column vc_col-sm-6',
			),

			// Class
			array(
				'type'             => 'textfield',
				'heading'          => esc_html__( 'Class', 'dtlms-lite' ),
				'param_name'       => 'class',
				'description'      => esc_html__( 'If you wish you can add additional class name here.', 'dtlms-lite' ),
				'edit_field_class' => 'vc_column vc_col-sm-6',
			),
			// Carousel Options

			// Enable Carousel
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__('Enable Carousel','dtlms-lite'),
				'param_name' => 'enable-carousel',
				'value'      => array(
					esc_html__('False','dtlms-lite') => '',
					esc_html__('True','dtlms-lite')  => 'true',
				),
				'description' => esc_html__( 'If you wish you can enable carousel for course listings. This option is not applicable if "Default - Ajax Output" is not chosen in "Listing Output Page". "Carousel" won\'t work along with "Apply Isotope".', 'dtlms-lite' ),
				'group'       => 'Carousel',
				'dependency'  => array( 'element' => 'apply-isotope', 'value' => 'false'),
				'std'         => ''
			),

			// Effect
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__('Effect', 'dtlms-lite'),
				'param_name' => 'carousel-effect',
				'value'      => array(
					esc_html__('Default', 'dtlms-lite') => '',
					esc_html__('Fade', 'dtlms-lite')    => 'fade',
				),
				'description' => esc_html__( 'Choose effect for your carousel. Slides Per View has to be 1 for Fade effect.', 'dtlms-lite' ),
				'group'       => 'Carousel',
				'dependency'  => array( 'element' => 'enable-carousel', 'value' => 'true'),
				'std'         => ''
			),

			// Auto Play
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__('Auto Play', 'dtlms-lite'),
				'param_name'  => 'carousel-autoplay',
				'description' => esc_html__( 'Delay between transitions ( in ms, ex. 1000 ). Leave empty if you don\'t want to auto play.', 'dtlms-lite' ),
				'group'       => 'Carousel',
				'dependency'  => array( 'element' => 'enable-carousel', 'value' => 'true'),
			),

			// Slides Per View
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__('Slides Per View','dtlms-lite'),
				'param_name' => 'carousel-slidesperview',
				'value'      => array(
					1 => 1,
					2 => 2,
					3 => 3,
				),
				'description' => esc_html__( 'Number slides of to show in view port. If display type is "List", 2 & 3 option in "Slides Per View" won\'t work.', 'dtlms-lite' ),
				'group'       => 'Carousel',
				'dependency'  => array( 'element' => 'enable-carousel', 'value' => 'true'),
				'std'         => 2
			),

			// Enable loop mode
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__('Enable Loop Mode','dtlms-lite'),
				'param_name' => 'carousel-loopmode',
				'value'      => array(
					esc_html__('False','dtlms-lite') => 'false',
					esc_html__('True','dtlms-lite')  => 'true',
				),
				'description' => esc_html__( 'If you wish you can enable continous loop mode for your carousel.', 'dtlms-lite' ),
				'group'       => 'Carousel',
				'dependency'  => array( 'element' => 'enable-carousel', 'value' => 'true'),
				'std'         => ''
			),

			// Enable mousewheel control
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__('Enable Mousewheel Control', 'dtlms-lite'),
				'param_name' => 'carousel-mousewheelcontrol',
				'value'      => array(
					esc_html__('False', 'dtlms-lite') => 'false',
					esc_html__('True', 'dtlms-lite')  => 'true',
				),
				'description' => esc_html__( 'If you wish you can enable mouse wheel control for your carousel.', 'dtlms-lite' ),
				'group'       => 'Carousel',
				'dependency'  => array( 'element' => 'enable-carousel', 'value' => 'true'),
				'std'         => ''
			),

			// Enable Bullet Pagination
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__('Enable Bullet Pagination', 'dtlms-lite'),
				'param_name' => 'carousel-bulletpagination',
				'value'      => array(
					esc_html__('False', 'dtlms-lite') => 'false',
					esc_html__('True', 'dtlms-lite')  => 'true',
				),
				'description' => esc_html__( 'To enable bullet pagination.', 'dtlms-lite' ),
				'group'       => 'Carousel',
				'dependency'  => array( 'element' => 'enable-carousel', 'value' => 'true'),
				'std'         => ''
			),

			// Enable Arrow Pagination
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__('Enable Arrow Pagination', 'dtlms-lite'),
				'param_name' => 'carousel-arrowpagination',
				'value'      => array(
					esc_html__('False', 'dtlms-lite') => 'false',
					esc_html__('True', 'dtlms-lite')  => 'true',
				),
				'description' => esc_html__( 'To enable arrow pagination.', 'dtlms-lite' ),
				'group'       => 'Carousel',
				'dependency'  => array( 'element' => 'enable-carousel', 'value' => 'true'),
				'std'         => ''
			),

			// Space Between Sliders
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__('Space Between Sliders','dtlms-lite'),
				'param_name'  => 'carousel-spacebetween',
				'description' => esc_html__( 'Space between sliders can be given here.', 'dtlms-lite' ),
				'group'       => 'Carousel',
				'dependency'  => array( 'element' => 'enable-carousel', 'value' => 'true'),
				'std'         => 0
			),
		)
	) );
}
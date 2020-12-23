<?php

if ( ! class_exists( 'DTDirectoryDesignThemes' ) ) {

	class DTDirectoryDesignThemes {

		/**
		 * Instance variable
		 */
		private static $_instance = null;

		/**
		 * Instance
		 *
		 * Ensures only one instance of the class is loaded or can be loaded.
		 */
		public static function instance() {

			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		function __construct() {

			add_filter( 'houzy_header_footer_default_cpt',  array ( $this, 'dtlms_dt_header_footer_default_cpt' ) );

			add_action( 'dtlms_before_main_content',  array ( $this, 'dtlms_dt_before_main_content' ), 10 );
			add_action( 'dtlms_after_main_content',  array ( $this, 'dtlms_dt_after_main_content' ), 10 );

			add_action( 'dtlms_before_content',  array ( $this, 'dtlms_dt_before_content' ), 10 );
			add_action( 'dtlms_after_content',  array ( $this, 'dtlms_dt_after_content' ), 10 );

		}

		function dtlms_dt_header_footer_default_cpt( $custom_posts ) {

			$custom_posts[] = 'dtlms_classes';
			$custom_posts[] = 'dtlms_courses';
			$custom_posts[] = 'dtlms_packages';

			return $custom_posts;
		}

		function dtlms_dt_before_main_content() {

			if (is_singular( 'dtlms_classes' ) || is_singular( 'dtlms_courses' ) || is_singular( 'dtlms_lessons' ) || is_singular( 'dtlms_quizzes' ) || is_singular( 'dtlms_questions' ) || is_singular( 'dtlms_assignments' ) || is_singular( 'dtlms_certificates' ) || is_singular( 'dtlms_packages' )) {

				global $post;
				$post_id = $post->ID;

			    $settings = get_post_meta($post_id, 'dtlms_default_settings', true);
			    $settings = is_array ( $settings ) ?  array_filter( $settings )  :  array ();

			    $global_breadcrumb = houzy_get_option( 'show-breadcrumb' );

			    $header_class = '';
			    if( !empty( $global_breadcrumb ) ) {
			        if( isset( $settings['enable-sub-title'] ) && $settings['enable-sub-title'] ) {
			            $header_class = isset( $settings['breadcrumb_position'] ) ? $settings['breadcrumb_position'] : '';
					}
				}

				?>

				<div id="header-wrapper" class="<?php echo esc_attr($header_class); ?>">

					<header id="header">
						<div class="container">
							<?php do_action( 'houzy_header' ); ?>
					    </div>
					</header>

				    <?php
			        if( !empty( $global_breadcrumb ) ) {

						if(empty($settings)) { $settings['enable-sub-title'] = true; }

			            if(isset($settings['enable-sub-title']) && $settings['enable-sub-title']) {

			                $bstyle = houzy_get_option( 'breadcrumb-style', 'default' );

			                $breadcrumbs = array ();

			                if( $post->post_parent ) {

			                    $parent_id  = $post->post_parent;
			                    $parents =  array ();

			                    while( $parent_id ) {
			                        $page = get_page( $parent_id );
			                        $parents[] = '<a href="' . esc_url( get_permalink( $page->ID ) ) . '">' . esc_html( get_the_title( $page->ID ) ) . '</a>';
			                        $parent_id  = $page->post_parent;
			                    }

			                    $parents = array_reverse( $parents );
			                    $breadcrumbs = array_merge_recursive($breadcrumbs, $parents);

			                }

			                if(is_singular( 'dtlms_listings' )) {
			                	$listing_plural_label = apply_filters( 'listing_label', 'plural' );
			                	$breadcrumbs[] = '<a href="'.esc_url(get_post_type_archive_link('dtlms_listings')).'">'.esc_html( $listing_plural_label ).'</a>';
			                }

			                $breadcrumbs[] = the_title( '<span class="current">', '</span>', false );
			                $bcsettings = isset( $settings['breadcrumb_background'] ) ? $settings['breadcrumb_background'] :  array ();
			                $style = houzy_breadcrumb_css($bcsettings);

			                houzy_breadcrumb_output ( the_title( '<h1>', '</h1>',false ), $breadcrumbs, $bstyle, $style );

			            }
			        }
				    ?>
				</div>

				<?php

			}

			if(is_post_type_archive('dtlms_classes') || is_post_type_archive('dtlms_courses') || is_post_type_archive('dtlms_lessons') || is_post_type_archive('dtlms_quizzes') || is_post_type_archive('dtlms_questions') || is_post_type_archive('dtlms_assignments') || is_post_type_archive('dtlms_certificates') || is_post_type_archive('dtlms_packages') || is_tax ( 'course_category' ) || is_tax ( 'question_category' ) || is_author()) {

				$global_breadcrumb = houzy_get_option( 'show-breadcrumb' );
				$header_class	   = houzy_get_option( 'breadcrumb-position' );
				?>

				<div id="header-wrapper" class="<?php echo esc_attr($header_class); ?>">

					<header id="header">
						<div class="container">
							<?php do_action( 'houzy_header' ); ?>
					    </div>
					</header>

				    <?php
				    if( !empty( $global_breadcrumb ) ) {

				    	$bstyle = houzy_get_option( 'breadcrumb-style', 'default' );
				    	$style = houzy_breadcrumb_css();

				        $title = '<h1>'.get_the_archive_title().'</h1>';
				        $breadcrumbs =  array ();

				        if ( is_category() ) {
				            $breadcrumbs[] = '<a href="'. esc_url( get_category_link( get_query_var('cat') ) ).'">' . esc_html( single_cat_title('', false) ). '</a>';
				        } elseif ( is_tag() ) {
				            $breadcrumbs[] = '<a href="'. esc_url( get_tag_link( get_query_var('tag_id') ) ).'">' . esc_html( single_tag_title('', false) ) . '</a>';
				        } elseif( is_author() ) {

				        	$author_id = get_queried_object_id();
				            $breadcrumbs[] = '<a href="'.esc_url( get_the_author_meta( 'user_url', $author_id ) ).'">' . esc_html( get_the_author_meta('display_name', $author_id) ). '</a>';
				            $title = '<h1>'.esc_html( get_the_author_meta('display_name', $author_id) ).'</h1>';

				        } elseif( is_day() || is_time() ){
				            $breadcrumbs[] = '<a href="'. esc_url( get_year_link( get_the_time('Y') ) ). '">'. esc_html( get_the_time('Y') ) .'</a>';
				            $breadcrumbs[] = '<a href="'. esc_url( get_month_link( get_the_time('Y'), get_the_time('m') ) ).'">'. esc_html( get_the_time('F') ).'</a>';
				            $breadcrumbs[] = '<a href="'. esc_url( get_day_link( get_the_time('Y'), get_the_time('m'), get_the_time('d') ) ).'">'. esc_html( get_the_time('d') ) .'</a>';
				        } elseif( is_month() ){
				            $breadcrumbs[] = '<a href="'. esc_url( get_year_link( get_the_time('Y') ) ). '">' . esc_html( get_the_time('Y') ). '</a>';
				            $breadcrumbs[] = '<a href="'. esc_url( get_month_link( get_the_time('Y'), get_the_time('m') ) ).'">'. esc_html( get_the_time('F') ) .'</a>';
				        } elseif( is_year() ){
				            $breadcrumbs[] = '<a href="'. esc_url( get_year_link( get_the_time('Y') ) ).'">'. esc_html( get_the_time('Y') ).'</a>';
				        }

				        houzy_breadcrumb_output ( $title, $breadcrumbs, $bstyle, $style );

				    }
				    ?>

				</div>

				<?php

			}

		}

		function dtlms_dt_after_main_content() {}

		function dtlms_dt_before_content() {

			if (is_singular( 'dtlms_classes' ) || is_singular( 'dtlms_courses' ) || is_singular( 'dtlms_lessons' ) || is_singular( 'dtlms_quizzes' ) || is_singular( 'dtlms_questions' ) || is_singular( 'dtlms_assignments' ) || is_singular( 'dtlms_certificates' ) || is_singular( 'dtlms_packages' ) || is_post_type_archive('dtlms_classes') || is_post_type_archive('dtlms_courses') || is_post_type_archive('dtlms_lessons') || is_post_type_archive('dtlms_quizzes') || is_post_type_archive('dtlms_questions') || is_post_type_archive('dtlms_assignments') || is_post_type_archive('dtlms_certificates') || is_post_type_archive('dtlms_packages') || is_tax ( 'course_category' ) || is_tax ( 'question_category' ) || is_author()) {

				echo '<div id="main">';
						echo '<div class="container">';
							echo '<section id="primary" class="content-full-width">';

			}

		}

		function dtlms_dt_after_content() {

			if (is_singular( 'dtlms_classes' ) || is_singular( 'dtlms_courses' ) || is_singular( 'dtlms_lessons' ) || is_singular( 'dtlms_quizzes' ) || is_singular( 'dtlms_questions' ) || is_singular( 'dtlms_assignments' ) || is_singular( 'dtlms_certificates' ) || is_singular( 'dtlms_packages' ) || is_post_type_archive('dtlms_classes') || is_post_type_archive('dtlms_courses') || is_post_type_archive('dtlms_lessons') || is_post_type_archive('dtlms_quizzes') || is_post_type_archive('dtlms_questions') || is_post_type_archive('dtlms_assignments') || is_post_type_archive('dtlms_certificates') || is_post_type_archive('dtlms_packages') || is_tax ( 'course_category' ) || is_tax ( 'question_category' ) || is_author()) {

						echo '</section>';
					echo '</div>';
				echo '</div>';

		    }

		}

	}

	DTDirectoryDesignThemes::instance();
}
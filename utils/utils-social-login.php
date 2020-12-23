<?php
function dtlms_get_login_redirect_url($user_info) {

	$dtlms_redirect_url = '';

	if(isset($user_info->data->ID)) {

		$current_user = $user_info;

		if ( in_array( 'administrator', (array) $current_user->roles ) ) {

			$administrator_login_redirect_page = dtlms_option('login','administrator-login-redirect-page');

			if($administrator_login_redirect_page != '') {
				if(class_exists( 'BuddyPress' ) && $administrator_login_redirect_page == 'buddypress-dashboard') {
					$dtlms_redirect_url = home_url( '/members/' . bp_core_get_username( $current_user->ID ) . '/lms/' );
				} else if(class_exists( 'WooCommerce' ) && $administrator_login_redirect_page == 'woocommerce-dashboard') {
					$dtlms_redirect_url = get_permalink( get_option('woocommerce_myaccount_page_id') ).'?lms';
				} else {
					$dtlms_redirect_url = get_permalink( $administrator_login_redirect_page );
				}
			} else {
				$dtlms_redirect_url = home_url();
			}

		} else if ( in_array( 'instructor', (array) $current_user->roles ) ) {

			$instructor_login_redirect_page = dtlms_option('login','instructor-login-redirect-page');

			if($instructor_login_redirect_page != '') {
				if(class_exists( 'BuddyPress' ) && $instructor_login_redirect_page == 'buddypress-dashboard') {
					$dtlms_redirect_url = home_url( '/members/' . bp_core_get_username( $current_user->ID ) . '/lms/' );
				} else if(class_exists( 'WooCommerce' ) && $instructor_login_redirect_page == 'woocommerce-dashboard') {
					$dtlms_redirect_url = get_permalink( get_option('woocommerce_myaccount_page_id') ).'?lms';
				} else {
					$dtlms_redirect_url = get_permalink( $instructor_login_redirect_page );
				}
			} else {
				$dtlms_redirect_url = home_url();
			}

		} else if ( in_array( 'student', (array) $current_user->roles ) ) {

			$student_login_redirect_page = dtlms_option('login','student-login-redirect-page');

			if($student_login_redirect_page != '') {
				if(class_exists( 'BuddyPress' ) && $student_login_redirect_page == 'buddypress-dashboard') {
					$dtlms_redirect_url = home_url( '/members/' . bp_core_get_username( $current_user->ID ) . '/lms/' );
				} else if(class_exists( 'WooCommerce' ) && $student_login_redirect_page == 'woocommerce-dashboard') {
					$dtlms_redirect_url = get_permalink( get_option('woocommerce_myaccount_page_id') ).'?lms';
				} else {
					$dtlms_redirect_url = get_permalink( $student_login_redirect_page );
				}
			} else {
				$dtlms_redirect_url = home_url();
			}

		}

	}

	if($dtlms_redirect_url == '') {
		$dtlms_redirect_url = home_url();
	}

	return $dtlms_redirect_url;

}

// Redirect user from default login form
function dtlms_default_login_form_redirect( $redirect_to, $request, $user ) {

	if( ( function_exists('wc_get_page_id') && !is_page( wc_get_page_id( 'checkout' ) ) ) || !function_exists('wc_get_page_id') ) {

		$dtlms_redirect_url = dtlms_get_login_redirect_url($user);

		return $dtlms_redirect_url;

	}

}
add_filter( 'login_redirect', 'dtlms_default_login_form_redirect', 10, 3 );


// Redirect user from woocommerce login form
function dtlms_woocommerce_login_form_redirect( $redirect, $user ) {

	if( site_url().'/checkout/' !=  $redirect) {

		$dtlms_redirect_url = dtlms_get_login_redirect_url($user);

		return $dtlms_redirect_url;

	} else {

		return $redirect;

	}

}
add_filter( 'woocommerce_login_redirect', 'dtlms_woocommerce_login_form_redirect', 10, 2 );
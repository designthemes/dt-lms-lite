<?php

if(!function_exists('dtlms_course_on_order_status_completion')) {
	function dtlms_course_on_order_status_completion($order_id) {

		$order = new WC_Order( $order_id );
		$user_id = get_post_meta($order_id, '_customer_user', true);

		$items = $order->get_items();
		foreach ( $items as $item_id => $item ) {

			$dtlms_item_id = wc_get_order_item_meta($item_id, 'dtlms_item_id');
			$post_type = get_post_type($dtlms_item_id);

			if(in_array($post_type, array('dtlms_courses'))) {

				$course_id = $dtlms_item_id;
				$course_data = get_post($course_id);
				$author_id = $course_data->post_author;

				$purchased_users = get_post_meta($course_id, 'purchased_users', true);
				$purchased_users = (is_array($purchased_users) && !empty($purchased_users)) ? $purchased_users : array();
				array_push($purchased_users, $user_id);
				update_post_meta($course_id, 'purchased_users', $purchased_users);

				$purchased_courses = get_user_meta($user_id, 'purchased_courses', true);
				$purchased_courses = (is_array($purchased_courses) && !empty($purchased_courses)) ? $purchased_courses : array();
				array_push($purchased_courses, $course_id);
				update_user_meta($user_id, 'purchased_courses', $purchased_courses);

				$current_timestamp = strtotime(current_time(get_option('date_format')));

				$purchased_users_timestamp = get_post_meta($course_id, 'purchased_users_timestamp', true);
				$purchased_users_timestamp = (is_array($purchased_users_timestamp) && !empty($purchased_users_timestamp)) ? $purchased_users_timestamp : array();
				$purchased_users_timestamp[$current_timestamp][] = $user_id;
				update_post_meta($course_id, 'purchased_users_timestamp', $purchased_users_timestamp);

				$purchased_courses_timestamp = get_user_meta($user_id, 'purchased_courses_timestamp', true);
				$purchased_courses_timestamp = (is_array($purchased_courses_timestamp) && !empty($purchased_courses_timestamp)) ? $purchased_courses_timestamp : array();
				$purchased_courses_timestamp[$current_timestamp][] = $course_id;
				update_user_meta($user_id, 'purchased_courses_timestamp', $purchased_courses_timestamp);

				if ( class_exists( 'BuddyPress' ) ) {
					$course_group_id = get_post_meta( $course_id, 'dtlms-course-group-id', true );
					groups_join_group( $course_group_id, $user_id );
				}

				if($author_id > 0) {

					$courses_subscribed = get_user_meta($author_id, 'courses-subscribed', true);
					$courses_subscribed = (is_array($courses_subscribed) && !empty($courses_subscribed)) ? $courses_subscribed : array ();
					$courses_subscribed_timestamp = array_keys($courses_subscribed);

					$courses_subscribed[$current_timestamp][$course_id]['users'][] = $user_id;
					$courses_subscribed[$current_timestamp][$course_id]['status'] = 'unpaid';

					update_user_meta($author_id, 'courses-subscribed', $courses_subscribed);

				}

				// Notification & Mail
				do_action('dtlms_poc_course_subscribed', $course_id, $user_id);

			}

		}

		// Change the customer role to student
	    if ( $user_id > 0 ) {
	    	$user = new WP_User( $user_id );
	        $user->remove_role( 'customer' );
	        $user->remove_role( 'subscriber' );
	        $user->add_role( 'student' );
	    }

	}
	add_action('woocommerce_order_status_completed','dtlms_course_on_order_status_completion');
}

if(!function_exists('dtlms_on_order_status_cancellation')) {
	function dtlms_on_order_status_cancellation($order_id) {

		$order = new WC_Order( $order_id );
		$order_data = $order->get_data();

		$user_id = get_post_meta($order_id, '_customer_user', true);

		$items = $order->get_items();
		foreach ( $items as $item_id => $item ) {

			$dtlms_item_id = wc_get_order_item_meta($item_id, 'dtlms_item_id');
			$post_type = get_post_type($dtlms_item_id);

			if(in_array($post_type, array('dtlms_courses'))) {

				$course_id = $dtlms_item_id;
				$course_data = get_post($course_id);
				$author_id = $course_data->post_author;

				$purchased_users = get_post_meta($course_id, 'purchased_users', true);
				$purchased_users = (is_array($purchased_users) && !empty($purchased_users)) ? $purchased_users : array();
				if(in_array($user_id, $purchased_users)) {
				    unset($purchased_users[array_search($user_id, $purchased_users)]);
				}
				update_post_meta($course_id, 'purchased_users', $purchased_users);

				$purchased_courses = get_user_meta($user_id, 'purchased_courses', true);
				$purchased_courses = (is_array($purchased_courses) && !empty($purchased_courses)) ? $purchased_courses : array();
				if(in_array($course_id, $purchased_courses)) {
				    unset($purchased_courses[array_search($course_id, $purchased_courses)]);
				}
				update_user_meta($user_id, 'purchased_courses', $purchased_courses);

				$purchased_users_timestamp = get_post_meta($course_id, 'purchased_users_timestamp', true);
				$purchased_users_timestamp = (is_array($purchased_users_timestamp) && !empty($purchased_users_timestamp)) ? $purchased_users_timestamp : array();
				foreach($purchased_users_timestamp as $purchased_users_timestamp_key => $purchased_users_timestamp_data) {
					if(in_array($user_id, $purchased_users_timestamp_data)) {
					    unset($purchased_users_timestamp[$purchased_users_timestamp_key][array_search($user_id, $purchased_users_timestamp_data)]);
					}
				}
				update_post_meta($course_id, 'purchased_users_timestamp', $purchased_users_timestamp);

				$purchased_courses_timestamp = get_user_meta($user_id, 'purchased_courses_timestamp', true);
				$purchased_courses_timestamp = (is_array($purchased_courses_timestamp) && !empty($purchased_courses_timestamp)) ? $purchased_courses_timestamp : array();
				foreach($purchased_courses_timestamp as $purchased_courses_timestamp_key => $purchased_courses_timestamp_data) {
					if(in_array($course_id, $purchased_courses_timestamp_data)) {
					    unset($purchased_courses_timestamp[$purchased_courses_timestamp_key][array_search($course_id, $purchased_courses_timestamp_data)]);
					}
				}
				update_user_meta($user_id, 'purchased_courses_timestamp', $purchased_courses_timestamp);

				if ( class_exists( 'BuddyPress' ) ) {
					$course_group_id = get_post_meta( $course_id, 'dtlms-course-group-id', true );
					groups_remove_member( $user_id, $course_group_id );
				}

				if($author_id > 0) {

					$courses_subscribed = get_user_meta($author_id, 'courses-subscribed', true);
					$courses_subscribed = (is_array($courses_subscribed) && !empty($courses_subscribed)) ? $courses_subscribed : array ();

					$order_timestamp_completed = $order_data['date_completed']->date(get_option('date_format'));
					$order_timestamp_completed = strtotime($order_timestamp_completed);

					foreach($courses_subscribed[$order_timestamp_completed][$course_id]['users'] as $order_user_key => $order_user) {
						if($order_user == $user_id) {
							unset($courses_subscribed[$order_timestamp_completed][$course_id]['users'][$order_user_key]);
						}
					}

					update_user_meta($author_id, 'courses-subscribed', $courses_subscribed);

				}

				// Notification & Mail
				do_action('dtlms_poc_course_subscription_cancellation', $course_id, $user_id);

			}

		}

	}
	add_action('woocommerce_order_status_cancelled','dtlms_on_order_status_cancellation');
	add_action('woocommerce_order_status_refunded','dtlms_on_order_status_cancellation');
}
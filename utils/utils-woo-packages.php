<?php

if(!function_exists('dtlms_package_on_order_status_completion')) {
	function dtlms_package_on_order_status_completion($order_id) {

		$order = new WC_Order( $order_id );
		$user_id = get_post_meta($order_id, '_customer_user', true);

		$items = $order->get_items();
		foreach ( $items as $item_id => $item ) {

			$dtlms_item_id = wc_get_order_item_meta($item_id, 'dtlms_item_id');
			$post_type = get_post_type($dtlms_item_id);

			if(in_array($post_type, array('dtlms_packages'))) {

				$package_id = $dtlms_item_id;

				$period = get_post_meta($package_id, 'period', true);
				$term   = get_post_meta($package_id, 'term', true);

				$terms_list = array(
					'D' => esc_html__( 'Day(s)', 'dtlms-lite' ),
					'W' => esc_html__('Week(s)', 'dtlms-lite' ),
					'M' => esc_html__('Month(s)', 'dtlms-lite' ),
					'Y' => esc_html__('Year(s)', 'dtlms-lite' ),
					'L' => esc_html__('Lifetime', 'dtlms-lite' ),
				);

				$current_timestamp = strtotime(current_time(get_option('date_format')));
				if($term != 'L' && $term != '') {
					$add_date = '+'.$period.' '.$terms_list[$term];
					$expiry_timestamp = strtotime($add_date, $current_timestamp);
				} else {
					$expiry_timestamp = 'NA';
				}

				$purchased_users = get_post_meta($package_id, 'purchased_users', true);
				$purchased_users = (is_array($purchased_users) && !empty($purchased_users)) ? $purchased_users : array();
				$purchased_users[$user_id] = array (
					'purchased-date' => $current_timestamp,
					'expiry-date' => $expiry_timestamp,
				);

				update_post_meta($package_id, 'purchased_users', $purchased_users);

				$purchased_packages = get_user_meta($user_id, 'purchased_packages', true);
				$purchased_packages = (is_array($purchased_packages) && !empty($purchased_packages)) ? $purchased_packages : array();
				$purchased_packages[$package_id] = array (
					'purchased-date' => $current_timestamp,
					'expiry-date' => $expiry_timestamp,
				);
				update_user_meta($user_id, 'purchased_packages', $purchased_packages);

				$purchased_users_timestamp = get_post_meta($package_id, 'purchased_users_timestamp', true);
				$purchased_users_timestamp = (is_array($purchased_users_timestamp) && !empty($purchased_users_timestamp)) ? $purchased_users_timestamp : array();
				$purchased_users_timestamp[$current_timestamp][] = $user_id;
				update_post_meta($package_id, 'purchased_users_timestamp', $purchased_users_timestamp);

				$purchased_packages_timestamp = get_user_meta($user_id, 'purchased_packages_timestamp', true);
				$purchased_packages_timestamp = (is_array($purchased_packages_timestamp) && !empty($purchased_packages_timestamp)) ? $purchased_packages_timestamp : array();
				$purchased_packages_timestamp[$current_timestamp][] = $package_id;
				update_user_meta($user_id, 'purchased_packages_timestamp', $purchased_packages_timestamp);

				if($author_id > 0) {

					$packages_subscribed = get_user_meta($author_id, 'packages-subscribed', true);
					$packages_subscribed = (is_array($packages_subscribed) && !empty($packages_subscribed)) ? $packages_subscribed : array ();
					$packages_subscribed_timestamp = array_keys($packages_subscribed);

					$packages_subscribed[$current_timestamp][$package_id]['users'][] = $user_id;
					$packages_subscribed[$current_timestamp][$package_id]['status'] = 'unpaid';

					update_user_meta($author_id, 'packages-subscribed', $packages_subscribed);
				}

				// Start class for user
				$classes_included = get_post_meta($package_id, 'classes-included', true);
				if(is_array($classes_included) && !empty($classes_included)) {
					foreach($classes_included as $class_id) {
						dtlms_start_class_initialize($class_id, $user_id, $author_id, false);
					}
				}

				// Notification & Mail
				do_action('dtlms_poc_package_subscribed', $package_id, $user_id);

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
	add_action('woocommerce_order_status_completed','dtlms_package_on_order_status_completion');
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

			if(in_array($post_type, array('dtlms_packages'))) {

				$package_id = $dtlms_item_id;

				$purchased_users = get_post_meta($package_id, 'purchased_users', true);
				$purchased_users = (is_array($purchased_users) && !empty($purchased_users)) ? $purchased_users : array();
				if(array_key_exists($user_id, $purchased_users)) {
				    unset($purchased_users[$user_id]);
				}
				update_post_meta($package_id, 'purchased_users', $purchased_users);

				$purchased_packages = get_user_meta($user_id, 'purchased_packages', true);
				$purchased_packages = (is_array($purchased_packages) && !empty($purchased_packages)) ? $purchased_packages : array();
				if(array_key_exists($package_id, $purchased_packages)) {
				    unset($purchased_packages[$package_id]);
				}
				update_user_meta($user_id, 'purchased_packages', $purchased_packages);

				$purchased_users_timestamp = get_post_meta($package_id, 'purchased_users_timestamp', true);
				$purchased_users_timestamp = (is_array($purchased_users_timestamp) && !empty($purchased_users_timestamp)) ? $purchased_users_timestamp : array();
				foreach($purchased_users_timestamp as $purchased_users_timestamp_key => $purchased_users_timestamp_data) {
					if(in_array($user_id, $purchased_users_timestamp_data)) {
					    unset($purchased_users_timestamp[$purchased_users_timestamp_key][array_search($user_id, $purchased_users_timestamp_data)]);
					}
				}
				update_post_meta($package_id, 'purchased_users_timestamp', $purchased_users_timestamp);

				$purchased_packages_timestamp = get_user_meta($user_id, 'purchased_packages_timestamp', true);
				$purchased_packages_timestamp = (is_array($purchased_packages_timestamp) && !empty($purchased_packages_timestamp)) ? $purchased_packages_timestamp : array();
				foreach($purchased_packages_timestamp as $purchased_packages_timestamp_key => $purchased_packages_timestamp_data) {
					if(in_array($package_id, $purchased_packages_timestamp_data)) {
					    unset($purchased_packages_timestamp[$purchased_packages_timestamp_key][array_search($package_id, $purchased_packages_timestamp_data)]);
					}
				}
				update_user_meta($user_id, 'purchased_packages_timestamp', $purchased_packages_timestamp);


				if($author_id > 0) {

					$packages_subscribed = get_user_meta($author_id, 'packages-subscribed', true);
					$packages_subscribed = (is_array($packages_subscribed) && !empty($packages_subscribed)) ? $packages_subscribed : array ();

					$order_timestamp_completed = $order_data['date_completed']->date(get_option('date_format'));
					$order_timestamp_completed = strtotime($order_timestamp_completed);

					foreach($packages_subscribed[$order_timestamp_completed][$package_id]['users'] as $order_user_key => $order_user) {
						if($order_user == $user_id) {
							unset($packages_subscribed[$order_timestamp_completed][$package_id]['users'][$order_user_key]);
						}
					}

					update_user_meta($author_id, 'packages-subscribed', $packages_subscribed);

				}

				// Notification & Mail
				do_action('dtlms_poc_package_subscription_cancellation', $package_id, $user_id);

			}

		}

	}
	add_action('woocommerce_order_status_cancelled','dtlms_on_order_status_cancellation');
	add_action('woocommerce_order_status_refunded','dtlms_on_order_status_cancellation');
}
<?php

// Add custom component for notification
function dtlms_add_components_for_notification( $component_names = array() ) {

	if ( ! is_array( $component_names ) ) {
		$component_names = array();
	}

	array_push( $component_names, 'dtlms_poc' );

	return $component_names;

}
add_filter( 'bp_notifications_get_registered_components', 'dtlms_add_components_for_notification' );

// Point Of Contacts
if(!function_exists('dtlms_point_of_contacts_update')) {
    function dtlms_point_of_contacts_update($dtlms_point_of_contacts) {

		$dtlms_point_of_contacts['course_added'] = array (
			'label'  => esc_html__('New Course Added', 'dtlms-lite'),
			'name'   => 'course_added',
			'hook'   => 'dtlms_poc_course_added',
			'params' => 2,
		);
		$dtlms_point_of_contacts['course_subscribed'] = array (
			'label'  => esc_html__('Course Subscribed', 'dtlms-lite'),
			'name'   => 'course_subscribed',
			'hook'   => 'dtlms_poc_course_subscribed',
			'params' => 2,
		);
		$dtlms_point_of_contacts['course_subscription_cancellation'] = array (
			'label'  => esc_html__('Course Subscription Cancellation', 'dtlms-lite'),
			'name'   => 'course_subscription_cancellation',
			'hook'   => 'dtlms_poc_course_subscription_cancellation',
			'params' => 2,
		);
		$dtlms_point_of_contacts['course_assigned'] = array (
			'label'  => esc_html__('Course Assigned', 'dtlms-lite'),
			'name'   => 'course_assigned',
			'hook'   => 'dtlms_poc_course_assigned',
			'params' => 2,
		);
		$dtlms_point_of_contacts['course_started'] = array (
			'label'  => esc_html__('Course Started', 'dtlms-lite'),
			'name'   => 'course_started',
			'hook'   => 'dtlms_poc_course_started',
			'params' => 2,
		);
		$dtlms_point_of_contacts['course_submitted'] = array (
			'label'  => esc_html__('Course Submitted', 'dtlms-lite'),
			'name'   => 'course_submitted',
			'hook'   => 'dtlms_poc_course_submitted',
			'params' => 2,
		);
		$dtlms_point_of_contacts['course_evaluated'] = array (
			'label'  => esc_html__('Course Evaluated', 'dtlms-lite'),
			'name'   => 'course_evaluated',
			'hook'   => 'dtlms_poc_course_evaluated',
			'params' => 2,
		);
		$dtlms_point_of_contacts['course_submission_revoke'] = array (
			'label'  => esc_html__('Course Submission Revoke', 'dtlms-lite'),
			'name'   => 'course_submission_revoke',
			'hook'   => 'dtlms_poc_course_submission_revoke',
			'params' => 2,
		);
		$dtlms_point_of_contacts['course_drip_content_agenda'] = array (
			'label'   => esc_html__('Course Drip Content Agenda', 'dtlms-lite'),
			'name'    => 'course_drip_content_agenda',
			'hook'    => 'dtlms_poc_course_drip_content_agenda',
			'params'  => 2,
			'disable' => 'notification'
		);
		$dtlms_point_of_contacts['package_subscribed'] = array (
			'label'  => esc_html__('Package Subscribed', 'dtlms-lite'),
			'name'   => 'package_subscribed',
			'hook'   => 'dtlms_poc_package_subscribed',
			'params' => 2,
		);
		$dtlms_point_of_contacts['package_subscription_cancellation'] = array (
			'label'  => esc_html__('Package Subscription Cancellation', 'dtlms-lite'),
			'name'   => 'package_subscription_cancellation',
			'hook'   => 'dtlms_poc_package_subscription_cancellation',
			'params' => 2,
		);

        return $dtlms_point_of_contacts;

    }
    add_filter ( 'dtlms_point_of_contacts', 'dtlms_point_of_contacts_update', 5, 1 );
}

// Save Point Of Contact Settings
add_action( 'wp_ajax_dtlms_save_poc_settings', 'dtlms_save_poc_settings' );
add_action( 'wp_ajax_nopriv_dtlms_save_poc_settings', 'dtlms_save_poc_settings' );
function dtlms_save_poc_settings() {

	$dtlms_poc_settings = dtlms_recursive_sanitize_text_field( $_REQUEST['dtlms-poc-settings'] );

	update_option('dtlms-poc-settings', $dtlms_poc_settings);

	echo esc_html__('"Point Of Contact" settings have been updated successfully!', 'dtlms-lite');

	die();
}

// Initialize Point Of Contact Actions
function dtlms_initialize_poc_actions() {

	$poc_settings = get_option('dtlms-poc-settings');

	$dtlms_point_of_contacts = apply_filters( 'dtlms_point_of_contacts', array () );

	foreach($dtlms_point_of_contacts as $point_of_contact_key => $point_of_contact) {

		if(isset($poc_settings[$point_of_contact['name']]['student']['email']) && $poc_settings[$point_of_contact['name']]['student']['email'] == 'true'){
			add_action($point_of_contact['hook'], 'dtlms_poc_student_email_'.$point_of_contact_key, 10, $point_of_contact['params']);
		}

		if(isset($poc_settings[$point_of_contact['name']]['instructor']['email']) && $poc_settings[$point_of_contact['name']]['instructor']['email'] == 'true'){
			add_action($point_of_contact['hook'], 'dtlms_poc_instructor_email_'.$point_of_contact_key, 10, $point_of_contact['params']);
		}

	}

	if ( class_exists( 'BuddyPress' ) ) {
		if(function_exists('bp_notifications_add_notification')) {
			foreach($dtlms_point_of_contacts as $point_of_contact_key => $point_of_contact) {

				if(isset($poc_settings[$point_of_contact['name']]['student']['notification']) && $poc_settings[$point_of_contact['name']]['student']['notification'] == 'true'){
					add_action($point_of_contact['hook'], 'dtlms_poc_student_notification_'.$point_of_contact_key, 10, $point_of_contact['params']);
				}

				if(isset($poc_settings[$point_of_contact['name']]['instructor']['notification']) && $poc_settings[$point_of_contact['name']]['instructor']['notification'] == 'true'){
					add_action($point_of_contact['hook'], 'dtlms_poc_instructor_notification_'.$point_of_contact_key, 10, $point_of_contact['params']);
				}

			}

		}
	}
}
add_action ( 'init', 'dtlms_initialize_poc_actions');

// Other Items
function dtlms_poc_get_student_ids() {

	$student_ids = get_users ( array ('role' => 'student', 'fields' => 'ID' ) );
	return $student_ids;
}

function dtlms_poc_get_instructor_ids($item_id = -1) {

	if($item_id > 0) {
		$item_data = get_post($item_id);
		$author_id = $item_data->post_author;
		$instructor_ids = get_post_meta($item_id, 'coinstructors', TRUE);
		if(is_array($instructor_ids) && !empty($instructor_ids)) {
			array_push($instructor_ids, $author_id);
		} else {
			$instructor_ids = array ($author_id);
		}
	} else {
		$instructor_ids = get_users ( array ('role' => 'instructor', 'fields' => 'ID' ) );
	}

	return $instructor_ids;

}

add_filter( 'wp_mail_content_type', 'dtlms_set_html_content_type' );
function dtlms_set_html_content_type() {
	return 'text/html';
}

function dtlms_poc_email_vc_content() {

	$themeData = wp_get_theme();
	$themeName = $themeData->get('Name');

	$output = '<div class="dtlms-email-layout">
				    <div class="dtlms-email-container">
				        <div class="dtlms-email-header">
				            <div class="dtlms-email-logo"><a href="'.esc_url(get_site_url()).'" target="_blank">'.esc_html($themeName).'</a></div>
				            <div class="dtlms-email-links">
					            <ul>
					                <li><a href="'.esc_url(get_site_url()).'" target="_blank">'.esc_html__('Demos', 'dtlms-lite').'</a></li>
					            </ul>
				            </div>
				        </div>
				        <div class="dtlms-email-content">
				            <p>{{dtlms-poc-email-template-content}}</p>
				        </div>
				        <div class="dtlms-email-footer">
				            <div class="dtlms-email-copyright">
				                <p> '.esc_html__('2018 All Rights Reserved.', 'dtlms-lite').' </p>
				            </div>
				            <ul class="dtlms-email-quick-links">
				                <li class="first-child"> <a href="#" target=" _blank">Link 1</a></li>
				                <li> <a href="#" target=" _blank">Link 2</a></li>
				                <li> <a href="#" target=" _blank">Link 3</a></li>
				            </ul>
				        </div>
				    </div>
				</div>';

	return $output;

}

function dtlms_poc_email_vc_css() {

	$output = '*, *:hover { -webkit-transition: all 0.3s linear 0s; transition: all 0.3s linear 0s; }

			a { text-decoration: none; }

			.dtlms-email-layout, .dtlms-email-layout * { -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; }

			.dtlms-email-layout { width: 100%; float: left; text-align: center; background: #f9f9f9; padding: 50px 30px 44px; font-size: 16px; }

			.dtlms-email-container { background: #fff; width: 75%; float: none; display: inline-block; }
			.dtlms-email-container ul { margin-bottom: 0; margin-top: 15px; }
			.dtlms-email-container ul li { list-style: none; float: right; padding: 0; }
			.dtlms-email-container > div { width: 100%; float: left; }
			.dtlms-email-container p { margin-bottom: 0; margin-top: 0; }

			.dtlms-email-container .dtlms-email-header, .dtlms-email-container .dtlms-email-footer { position: relative; background: rgba(0,0,0,0.09); }

			.dtlms-email-container .dtlms-email-logo { width: 40%; float: left; text-align: center; padding: 20px 30px; font-size: 60px; text-transform: uppercase; font-weight: 900; line-height: 72px; letter-spacing: 8px; background: #ffcc21; }
			.dtlms-email-container .dtlms-email-logo a { color: #000; }

			.dtlms-email-container .dtlms-email-header .dtlms-email-links { width: 60%; float: right; text-align: center; padding: 20px 30px; }
			.dtlms-email-container .dtlms-email-header .dtlms-email-links ul li { margin: 0 0 0 20px; }
			.dtlms-email-container .dtlms-email-header .dtlms-email-links ul li a { margin: 0; font-size: 16px; font-weight: 500; text-transform: inherit; text-decoration: none; padding: 11px 24px;background: #ffcc21; color: #000; cursor: pointer; line-height: normal; position: relative;    display: inline-block; }
			.dtlms-email-container .dtlms-email-header .dtlms-email-links ul li a:hover { color: #fff; background: #40c4ff; }

			.dtlms-email-container .dtlms-email-content { padding: 50px; }
			.dtlms-email-container .dtlms-email-content p { line-height: 28px; }
			.dtlms-email-container .dtlms-email-content a { color: #40c4ff; }
			.dtlms-email-container .dtlms-email-content a:hover { color: #000; }

			.dtlms-email-container .dtlms-email-footer .dtlms-email-copyright { width: 40%; float: left; text-align: center; padding: 20px 30px; line-height: normal; background: #40c4ff; color: #fff; }

			.dtlms-email-container .dtlms-email-footer ul { width: 60%; float: right; text-align: right; padding: 21px 30px; margin-top: 0; }
			.dtlms-email-container .dtlms-email-footer ul li { margin-right: 10px; padding-right: 10px; border-right: 1px solid #000; line-height: normal; float: right; margin-left: 0;  }
			.dtlms-email-container .dtlms-email-footer ul li a { font-size: 14px; color: #000; float: right; }
			.dtlms-email-container .dtlms-email-footer ul li a:hover { color: #40c4ff; }
			.dtlms-email-container .dtlms-email-footer ul li.first-child { margin-right: 0; padding-right: 0; border-right: none; }';

	return $output;

}

function dtlms_poc_email_configuration($to, $subject, $poc_content) {

	$etemplate_content = dtlms_poc_email_vc_content();
	$etemplate_custom_css = dtlms_poc_email_vc_css();

	$etemplate_content = str_replace('{{dtlms-poc-email-template-content}}', $poc_content, $etemplate_content);

    $message = '<html>
			        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			        <head>
			            <style>
				            '.apply_filters( 'dt_etemplate_custom_css', $etemplate_custom_css ).'
				            '.apply_filters( 'dt_post_custom_css', $post_custom_css ).'
				        </style>
			        </head>
			        <body>
			            '.do_shortcode($etemplate_content).'
			        </body>
		        </html>';

	$admin_email = get_option('admin_email');

	$headers = 'From: '.$admin_email."\r\n";
	$headers .= 'Reply-To: '.$admin_email."\r\n";
	$headers .= 'MIME-Version: 1.0'."\r\n";
	$headers .= 'Content-Type: text/html; charset=ISO-8859-1'."\r\n";

    mail($to, $subject, $message, $headers);

}

remove_filter( 'wp_mail_content_type', 'dtlms_set_html_content_type' );

function dtlms_poc_generate_course_drip_content_agenda($course_id, $user_id) {

	$output = '';

	$course_curriculum = get_post_meta($course_id, 'course-curriculum', true);

	if(is_array($course_curriculum) && !empty($course_curriculum)) {

		$output .= '<table class="dtlms-custom-table" border="0" cellpadding="0" cellspacing="20">
			<thead>
				<tr>
					<th scope="col">'.esc_html__('#', 'dtlms-lite').'</th>
					<th scope="col">'.esc_html__('Sub #', 'dtlms-lite').'</th>
					<th scope="col">'.esc_html__('Item Name', 'dtlms-lite').'</th>
					<th scope="col">'.esc_html__('Available On', 'dtlms-lite').'</th>
				</tr>
			</thead>
			<tbody>';

			$i = 1;
			foreach ($course_curriculum as $course_curriculum_item) {
				if (is_numeric($course_curriculum_item)) {

					$available_on = '';
					$drip_feed_enable = dtlms_course_drip_feed_check($course_id, $course_curriculum_item, $user_id);
					if($drip_feed_enable == 'true') {
						$available_on .= esc_html__('Active already', 'dtlms-lite');
					} else {
						$drip_date = dtlms_format_datetime($drip_feed_enable, get_option('date_format').' '.get_option('time_format'), false);
						$available_on .= $drip_date;
					}

					$output .= '<tr>
						<td>'.esc_html( $i ).'</td>
						<td></td>
						<td>'.esc_html( get_the_title($course_curriculum_item) ).'</td>
						<td>'.esc_html( $available_on ).'</td>';
					$output .= '</tr>';

					if(get_post_type($course_curriculum_item) == 'dtlms_lessons') {
						$lesson_curriculum = get_post_meta($course_curriculum_item, 'lesson-curriculum', true);
						if(is_array($lesson_curriculum) && !empty($lesson_curriculum)) {
							$j = 1;
							foreach ($lesson_curriculum as $lesson_curriculum_item) {
								if (is_numeric($lesson_curriculum_item)) {

									$available_on = '';
									$drip_feed_enable = dtlms_course_drip_feed_check($course_id, $lesson_curriculum_item, $user_id);
									if($drip_feed_enable == 'true') {
										$available_on .= esc_html__('Active already', 'dtlms-lite');
									} else {
										$drip_date = dtlms_format_datetime($drip_feed_enable, get_option('date_format').' '.get_option('time_format'), false);
										$available_on .= $drip_date;
									}

									$output .= '<tr>
										<td></td>
										<td>'.esc_html( $j ).'</td>
										<td>'.esc_html( get_the_title($lesson_curriculum_item) ).'</td>
										<td>'.esc_html( $available_on ).'</td>';
									$output .= '</tr>';

									$j++;

								}
							}
						}
					}

					$i++;
				}
			}

		$output .= '</tbody></table>';

	}

	return $output;
}


// POC - Course Added

function dtlms_poc_student_notification_course_added($course_id, $author_id) {

	$student_ids = dtlms_poc_get_student_ids();

	foreach($student_ids as $student_id) {
		bp_notifications_add_notification( array(
			'user_id'           => $student_id,
			'item_id'           => $course_id,
			'secondary_item_id'	=> $author_id,
			'component_name'    => 'dtlms_poc',
			'component_action'  => 'course_added_student',
			'date_notified'     => bp_core_current_time(),
			'is_new'            => 1,
		) );
	}
}

function dtlms_poc_student_email_course_added($course_id, $author_id) {

	$poc_settings = get_option('dtlms-poc-settings');
	$poc_email_subject_prefix = ( isset($poc_settings['poc-email-subject-prefix']) && '' !=  $poc_settings['poc-email-subject-prefix'] ) ? $poc_settings['poc-email-subject-prefix'] : '';

	$student_ids = dtlms_poc_get_student_ids();
	foreach($student_ids as $student_id) {
		$to = get_the_author_meta('email', $student_id);
		$subject = $poc_email_subject_prefix.esc_html__('New Course Added', 'dtlms-lite');
		$poc_content = sprintf(
			esc_html__('New Course %1$s have been added by %2$s','dtlms-lite'),
			'<a href="'.esc_url( get_permalink($course_id) ).'">'.esc_html( get_the_title($course_id) ).'</a>',
			'<a href="'.esc_url( get_author_posts_url($author_id) ).'">'.esc_html( get_the_author_meta('display_name', $author_id) ).'</a>'
		);

		dtlms_poc_email_configuration($to, $subject, $poc_content);
	}

}

function dtlms_poc_instructor_notification_course_added($course_id, $author_id) {

	$instructor_ids = dtlms_poc_get_instructor_ids(-1);

	foreach($instructor_ids as $instructor_id) {
		bp_notifications_add_notification( array(
			'user_id'           => $instructor_id,
			'item_id'           => $course_id,
			'secondary_item_id'	=> $author_id,
			'component_name'    => 'dtlms_poc',
			'component_action'  => 'course_added_instructor',
			'date_notified'     => bp_core_current_time(),
			'is_new'            => 1,
		) );
	}

}

function dtlms_poc_instructor_email_course_added($course_id, $author_id) {

	$poc_settings = get_option('dtlms-poc-settings');
	$poc_email_subject_prefix = ( isset($poc_settings['poc-email-subject-prefix']) && '' !=  $poc_settings['poc-email-subject-prefix'] ) ? $poc_settings['poc-email-subject-prefix'] : '';

	$instructor_ids = dtlms_poc_get_instructor_ids(-1);
	foreach($instructor_ids as $instructor_id) {
		$to          = get_the_author_meta('email', $instructor_id);
		$subject     = $poc_email_subject_prefix.esc_html__(' New Course Added', 'dtlms-lite');
		$poc_content = sprintf(
			esc_html__('New Course %1$s have been added by %2$s','dtlms-lite'),
			'<a href="'.esc_url( get_permalink($course_id) ).'">'.esc_html( get_the_title($course_id) ).'</a>',
			'<a href="'.esc_url( get_author_posts_url($author_id) ).'">'.esc_html( get_the_author_meta('display_name', $author_id) ).'</a>'
		);

		dtlms_poc_email_configuration($to, $subject, $poc_content);
	}
}

// POC - Course Subscribed

function dtlms_poc_student_notification_course_subscribed($course_id, $student_id) {

	bp_notifications_add_notification( array(
		'user_id'           => $student_id,
		'item_id'           => $course_id,
		'secondary_item_id'	=> false,
		'component_name'    => 'dtlms_poc',
		'component_action'  => 'course_subscribed_student',
		'date_notified'     => bp_core_current_time(),
		'is_new'            => 1,
	) );

}

function dtlms_poc_student_email_course_subscribed($course_id, $student_id) {

	$poc_settings = get_option('dtlms-poc-settings');
	$poc_email_subject_prefix = ( isset($poc_settings['poc-email-subject-prefix']) && '' !=  $poc_settings['poc-email-subject-prefix'] ) ? $poc_settings['poc-email-subject-prefix'] : '';

	$to          = get_the_author_meta('email', $student_id);
	$subject     = $poc_email_subject_prefix.esc_html__('Course Subscribed', 'dtlms-lite');
	$poc_content = sprintf(
		esc_html__('You have subscribed Course %1$s','dtlms-lite'),
		'<a href="'.esc_url( get_permalink($course_id) ).'">'.esc_html( get_the_title($course_id) ).'</a>'
	);
	dtlms_poc_email_configuration($to, $subject, $poc_content);

}

function dtlms_poc_instructor_notification_course_subscribed($course_id, $student_id) {

	$instructor_ids = dtlms_poc_get_instructor_ids($course_id);
	foreach($instructor_ids as $instructor_id) {
		bp_notifications_add_notification( array(
			'user_id'           => $instructor_id,
			'item_id'           => $course_id,
			'secondary_item_id'	=> $student_id,
			'component_name'    => 'dtlms_poc',
			'component_action'  => 'course_subscribed_instructor',
			'date_notified'     => bp_core_current_time(),
			'is_new'            => 1,
		) );
	}

}

function dtlms_poc_instructor_email_course_subscribed($course_id, $student_id) {

	$poc_settings = get_option('dtlms-poc-settings');
	$poc_email_subject_prefix = ( isset($poc_settings['poc-email-subject-prefix']) && '' !=  $poc_settings['poc-email-subject-prefix'] ) ? $poc_settings['poc-email-subject-prefix'] : '';

	$instructor_ids = dtlms_poc_get_instructor_ids($course_id);
	foreach($instructor_ids as $instructor_id) {
		$to          = get_the_author_meta('email', $instructor_id);
		$subject     = $poc_email_subject_prefix.esc_html__('Student - Course Subscribed', 'dtlms-lite');
		$poc_content = sprintf(
			esc_html__('Course %1$s have been subscribed by %2$s','dtlms-lite'),
			'<a href="'.esc_url( get_permalink($course_id) ).'">'.esc_html( get_the_title($course_id) ).'</a>',
			'<a href="'.esc_url( get_author_posts_url($student_id) ).'">'.esc_html( get_the_author_meta('display_name', $student_id) ).'</a>');

		dtlms_poc_email_configuration($to, $subject, $poc_content);
	}

}

// POC - Course Subscribed Cancellation

function dtlms_poc_student_notification_course_subscription_cancellation($course_id, $student_id) {

	bp_notifications_add_notification( array(
		'user_id'           => $student_id,
		'item_id'           => $course_id,
		'secondary_item_id'	=> false,
		'component_name'    => 'dtlms_poc',
		'component_action'  => 'course_subscription_cancellation_student',
		'date_notified'     => bp_core_current_time(),
		'is_new'            => 1,
	) );

}

function dtlms_poc_student_email_course_subscription_cancellation($course_id, $student_id) {

	$poc_settings = get_option('dtlms-poc-settings');
	$poc_email_subject_prefix = ( isset($poc_settings['poc-email-subject-prefix']) && '' !=  $poc_settings['poc-email-subject-prefix'] ) ? $poc_settings['poc-email-subject-prefix'] : '';

	$to = get_the_author_meta('email', $student_id);
	$subject = $poc_email_subject_prefix.esc_html__('Course Subscription Cancellation', 'dtlms-lite');
	$poc_content = sprintf(
		esc_html__('Your subscription for Course %1$s have been cancelled','dtlms-lite'),
		'<a href="'.esc_url( get_permalink($course_id) ).'">'.esc_html( get_the_title($course_id) ).'</a>'
	);

	dtlms_poc_email_configuration($to, $subject, $poc_content);
}

function dtlms_poc_instructor_notification_course_subscription_cancellation($course_id, $student_id) {

	$instructor_ids = dtlms_poc_get_instructor_ids($course_id);
	foreach($instructor_ids as $instructor_id) {
		bp_notifications_add_notification( array(
			'user_id'           => $instructor_id,
			'item_id'           => $course_id,
			'secondary_item_id'	=> $student_id,
			'component_name'    => 'dtlms_poc',
			'component_action'  => 'course_subscription_cancellation_instructor',
			'date_notified'     => bp_core_current_time(),
			'is_new'            => 1,
		) );
	}

}

function dtlms_poc_instructor_email_course_subscription_cancellation($course_id, $student_id) {

	$poc_settings = get_option('dtlms-poc-settings');
	$poc_email_subject_prefix = ( isset($poc_settings['poc-email-subject-prefix']) && '' !=  $poc_settings['poc-email-subject-prefix'] ) ? $poc_settings['poc-email-subject-prefix'] : '';

	$instructor_ids = dtlms_poc_get_instructor_ids($course_id);
	foreach($instructor_ids as $instructor_id) {
		$to = get_the_author_meta('email', $instructor_id);
		$subject = $poc_email_subject_prefix.esc_html__('Student - Course Subscription Cancellation', 'dtlms-lite');

		$poc_content = sprintf(
			esc_html__('%2$s subscription for Course %1$s have been cancelled','dtlms-lite'),
			'<a href="'.esc_url( get_permalink($course_id) ).'">'.esc_html( get_the_title($course_id) ).'</a>',
			'<a href="'.esc_url( get_author_posts_url($student_id) ).'">'.esc_html( get_the_author_meta('display_name', $student_id) ).'</a>'
		);

		dtlms_poc_email_configuration($to, $subject, $poc_content);
	}

}

// POC - Course Assigned

function dtlms_poc_student_notification_course_assigned($course_id, $student_id) {

	bp_notifications_add_notification( array(
		'user_id'           => $student_id,
		'item_id'           => $course_id,
		'secondary_item_id'	=> false,
		'component_name'    => 'dtlms_poc',
		'component_action'  => 'course_assigned_student',
		'date_notified'     => bp_core_current_time(),
		'is_new'            => 1,
	) );

}

function dtlms_poc_student_email_course_assigned($course_id, $student_id) {

	$poc_settings = get_option('dtlms-poc-settings');
	$poc_email_subject_prefix = ( isset($poc_settings['poc-email-subject-prefix']) && '' !=  $poc_settings['poc-email-subject-prefix'] ) ? $poc_settings['poc-email-subject-prefix'] : '';

	$to = get_the_author_meta('email', $student_id);
	$subject = $poc_email_subject_prefix.esc_html__('Course Assigned', 'dtlms-lite');

	$poc_content = sprintf(
		esc_html__('Course %1$s have been assigned to you.','dtlms-lite'),
		'<a href="'.esc_url( get_permalink($course_id) ).'">'.esc_html( get_the_title($course_id) ).'</a>'
	);

	dtlms_poc_email_configuration($to, $subject, $poc_content);
}

function dtlms_poc_instructor_notification_course_assigned($course_id, $student_id) {

	$instructor_ids = dtlms_poc_get_instructor_ids($course_id);
	foreach($instructor_ids as $instructor_id) {
		bp_notifications_add_notification( array(
			'user_id'           => $instructor_id,
			'item_id'           => $course_id,
			'secondary_item_id'	=> $student_id,
			'component_name'    => 'dtlms_poc',
			'component_action'  => 'course_assigned_instructor',
			'date_notified'     => bp_core_current_time(),
			'is_new'            => 1,
		) );
	}

}

function dtlms_poc_instructor_email_course_assigned($course_id, $student_id) {

	$poc_settings = get_option('dtlms-poc-settings');
	$poc_email_subject_prefix = ( isset($poc_settings['poc-email-subject-prefix']) && '' !=  $poc_settings['poc-email-subject-prefix'] ) ? $poc_settings['poc-email-subject-prefix'] : '';

	$instructor_ids = dtlms_poc_get_instructor_ids($course_id);
	foreach($instructor_ids as $instructor_id) {
		$to = get_the_author_meta('email', $instructor_id);
		$subject = $poc_email_subject_prefix.esc_html__('Student - Course Assigned', 'dtlms-lite');

		$poc_content = sprintf(
			esc_html__('Course %1$s have been assigned to %2$s','dtlms-lite'),
			'<a href="'.esc_url( get_permalink($course_id) ).'">'.esc_html( get_the_title($course_id) ).'</a>',
			'<a href="'.esc_url( get_author_posts_url($student_id) ).'">'.esc_html( get_the_author_meta('display_name', $student_id) ).'</a>'
		);

		dtlms_poc_email_configuration($to, $subject, $poc_content);
	}

}

// POC - Course Started

function dtlms_poc_student_notification_course_started($course_id, $student_id) {

	bp_notifications_add_notification( array(
		'user_id'           => $student_id,
		'item_id'           => $course_id,
		'secondary_item_id'	=> false,
		'component_name'    => 'dtlms_poc',
		'component_action'  => 'course_started_student',
		'date_notified'     => bp_core_current_time(),
		'is_new'            => 1,
	) );

}

function dtlms_poc_student_email_course_started($course_id, $student_id) {

	$poc_settings = get_option('dtlms-poc-settings');
	$poc_email_subject_prefix = ( isset($poc_settings['poc-email-subject-prefix']) && '' !=  $poc_settings['poc-email-subject-prefix'] ) ? $poc_settings['poc-email-subject-prefix'] : '';

	$to = get_the_author_meta('email', $student_id);
	$subject = $poc_email_subject_prefix.esc_html__('Course Started', 'dtlms-lite');

	$poc_content = sprintf(
		esc_html__('You have started Course %1$s','dtlms-lite'),
		'<a href="'.esc_url( get_permalink($course_id) ).'">'.esc_html( get_the_title($course_id) ).'</a>'
	);

	dtlms_poc_email_configuration($to, $subject, $poc_content);
}

function dtlms_poc_instructor_notification_course_started($course_id, $student_id) {

	$instructor_ids = dtlms_poc_get_instructor_ids($course_id);
	foreach($instructor_ids as $instructor_id) {
		bp_notifications_add_notification( array(
			'user_id'           => $instructor_id,
			'item_id'           => $course_id,
			'secondary_item_id'	=> $student_id,
			'component_name'    => 'dtlms_poc',
			'component_action'  => 'course_started_instructor',
			'date_notified'     => bp_core_current_time(),
			'is_new'            => 1,
		) );
	}

}

function dtlms_poc_instructor_email_course_started($course_id, $student_id) {

	$poc_settings = get_option('dtlms-poc-settings');
	$poc_email_subject_prefix = ( isset($poc_settings['poc-email-subject-prefix']) && '' !=  $poc_settings['poc-email-subject-prefix'] ) ? $poc_settings['poc-email-subject-prefix'] : '';

	$instructor_ids = dtlms_poc_get_instructor_ids($course_id);
	foreach($instructor_ids as $instructor_id) {
		$to = get_the_author_meta('email', $instructor_id);
		$subject = $poc_email_subject_prefix.esc_html__('Student - Course Started', 'dtlms-lite');

		$poc_content = sprintf(
			esc_html__('Course %1$s have been started by %2$s','dtlms-lite'),
			'<a href="'.esc_url( get_permalink($course_id) ).'">'.esc_html( get_the_title($course_id) ).'</a>',
			'<a href="'.esc_url( get_author_posts_url($student_id) ).'">'.esc_html( get_the_author_meta('display_name', $student_id) ).'</a>'
		);

		dtlms_poc_email_configuration($to, $subject, $poc_content);
	}
}

// POC - Course Submitted
function dtlms_poc_student_notification_course_submitted($course_id, $student_id) {

	bp_notifications_add_notification( array(
		'user_id'           => $student_id,
		'item_id'           => $course_id,
		'secondary_item_id'	=> false,
		'component_name'    => 'dtlms_poc',
		'component_action'  => 'course_submitted_student',
		'date_notified'     => bp_core_current_time(),
		'is_new'            => 1,
	) );

}

function dtlms_poc_student_email_course_submitted($course_id, $student_id) {

	$poc_settings = get_option('dtlms-poc-settings');
	$poc_email_subject_prefix = ( isset($poc_settings['poc-email-subject-prefix']) && '' !=  $poc_settings['poc-email-subject-prefix'] ) ? $poc_settings['poc-email-subject-prefix'] : '';

	$to = get_the_author_meta('email', $student_id);
	$subject = $poc_email_subject_prefix.esc_html__('Course Submitted', 'dtlms-lite');

	$poc_content = sprintf(
		esc_html__('You have submitted Course %1$s','dtlms-lite'),
		'<a href="'.esc_url( get_permalink($course_id) ).'">'.esc_html( get_the_title($course_id) ).'</a>'
	);

	dtlms_poc_email_configuration($to, $subject, $poc_content);
}

function dtlms_poc_instructor_notification_course_submitted($course_id, $student_id) {

	$instructor_ids = dtlms_poc_get_instructor_ids($course_id);
	foreach($instructor_ids as $instructor_id) {
		bp_notifications_add_notification( array(
			'user_id'           => $instructor_id,
			'item_id'           => $course_id,
			'secondary_item_id'	=> $student_id,
			'component_name'    => 'dtlms_poc',
			'component_action'  => 'course_submitted_instructor',
			'date_notified'     => bp_core_current_time(),
			'is_new'            => 1,
		) );
	}

}

function dtlms_poc_instructor_email_course_submitted($course_id, $student_id) {

	$poc_settings = get_option('dtlms-poc-settings');
	$poc_email_subject_prefix = ( isset($poc_settings['poc-email-subject-prefix']) && '' !=  $poc_settings['poc-email-subject-prefix'] ) ? $poc_settings['poc-email-subject-prefix'] : '';

	$instructor_ids = dtlms_poc_get_instructor_ids($course_id);
	foreach($instructor_ids as $instructor_id) {
		$to = get_the_author_meta('email', $instructor_id);
		$subject = $poc_email_subject_prefix.esc_html__('Student - Course Submitted', 'dtlms-lite');

		$poc_content = sprintf(
			esc_html__('Course %1$s have been submitted by %2$s','dtlms-lite'),
			'<a href="'.esc_url( get_permalink($course_id) ).'">'.esc_html( get_the_title($course_id) ).'</a>',
			'<a href="'.esc_url( get_author_posts_url($student_id) ).'">'.esc_html( get_the_author_meta('display_name', $student_id) ).'</a>'
		);

		dtlms_poc_email_configuration($to, $subject, $poc_content);
	}

}

// POC - Course Evaluated
function dtlms_poc_student_notification_course_evaluated($course_id, $student_id) {

	bp_notifications_add_notification( array(
		'user_id'           => $student_id,
		'item_id'           => $course_id,
		'secondary_item_id'	=> false,
		'component_name'    => 'dtlms_poc',
		'component_action'  => 'course_evaluated_student',
		'date_notified'     => bp_core_current_time(),
		'is_new'            => 1,
	) );

}

function dtlms_poc_student_email_course_evaluated($course_id, $student_id) {

	$poc_settings = get_option('dtlms-poc-settings');
	$poc_email_subject_prefix = ( isset($poc_settings['poc-email-subject-prefix']) && '' !=  $poc_settings['poc-email-subject-prefix'] ) ? $poc_settings['poc-email-subject-prefix'] : '';

	$to = get_the_author_meta('email', $student_id);
	$subject = $poc_email_subject_prefix.esc_html__('Course Evaluated', 'dtlms-lite');

	$poc_content = sprintf(
		esc_html__('Your Course %1$s have been evaluated.','dtlms-lite'),
		'<a href="'.esc_url( get_permalink($course_id) ).'">'.esc_html( get_the_title($course_id) ).'</a>'
	);

	dtlms_poc_email_configuration($to, $subject, $poc_content);
}

function dtlms_poc_instructor_notification_course_evaluated($course_id, $student_id) {

	$instructor_ids = dtlms_poc_get_instructor_ids($course_id);
	foreach($instructor_ids as $instructor_id) {
		bp_notifications_add_notification( array(
			'user_id'           => $instructor_id,
			'item_id'           => $course_id,
			'secondary_item_id'	=> $student_id,
			'component_name'    => 'dtlms_poc',
			'component_action'  => 'course_evaluated_instructor',
			'date_notified'     => bp_core_current_time(),
			'is_new'            => 1,
		) );
	}
}

function dtlms_poc_instructor_email_course_evaluated($course_id, $student_id) {

	$poc_settings = get_option('dtlms-poc-settings');
	$poc_email_subject_prefix = ( isset($poc_settings['poc-email-subject-prefix']) && '' !=  $poc_settings['poc-email-subject-prefix'] ) ? $poc_settings['poc-email-subject-prefix'] : '';

	$instructor_ids = dtlms_poc_get_instructor_ids($course_id);
	foreach($instructor_ids as $instructor_id) {
		$to = get_the_author_meta('email', $instructor_id);
		$subject = $poc_email_subject_prefix.esc_html__('Student - Course Evaluated', 'dtlms-lite');

		$poc_content = sprintf(
			esc_html__('%2$s Course %1$s have been evaluated.','dtlms-lite'),
			'<a href="'.esc_url( get_permalink($course_id) ).'">'.esc_html( get_the_title($course_id) ).'</a>',
			'<a href="'.esc_url( get_author_posts_url($student_id) ).'">'.esc_html( get_the_author_meta('display_name', $student_id) ).'</a>'
		);

		dtlms_poc_email_configuration($to, $subject, $poc_content);
	}

}

// POC - Course Submission Revoked

function dtlms_poc_student_notification_course_submission_revoke($course_id, $student_id) {

	bp_notifications_add_notification( array(
		'user_id'           => $student_id,
		'item_id'           => $course_id,
		'secondary_item_id'	=> false,
		'component_name'    => 'dtlms_poc',
		'component_action'  => 'course_submission_revoked_student',
		'date_notified'     => bp_core_current_time(),
		'is_new'            => 1,
	) );

}

function dtlms_poc_student_email_course_submission_revoke($course_id, $student_id) {

	$poc_settings = get_option('dtlms-poc-settings');
	$poc_email_subject_prefix = ( isset($poc_settings['poc-email-subject-prefix']) && '' !=  $poc_settings['poc-email-subject-prefix'] ) ? $poc_settings['poc-email-subject-prefix'] : '';

	$to = get_the_author_meta('email', $student_id);
	$subject = $poc_email_subject_prefix.esc_html__('Course Submission Revoked', 'dtlms-lite');

	$poc_content = sprintf(
		esc_html__('Your course %1$s submission have been revoked.','dtlms-lite'),
		'<a href="'.esc_url( get_permalink($course_id) ).'">'.esc_html( get_the_title($course_id) ).'</a>'
	);

	dtlms_poc_email_configuration($to, $subject, $poc_content);

}

function dtlms_poc_instructor_notification_course_submission_revoke($course_id, $student_id) {

	$instructor_ids = dtlms_poc_get_instructor_ids($course_id);
	foreach($instructor_ids as $instructor_id) {
		bp_notifications_add_notification( array(
			'user_id'           => $instructor_id,
			'item_id'           => $course_id,
			'secondary_item_id'	=> $student_id,
			'component_name'    => 'dtlms_poc',
			'component_action'  => 'course_submission_revoked_instructor',
			'date_notified'     => bp_core_current_time(),
			'is_new'            => 1,
		) );
	}

}

function dtlms_poc_instructor_email_course_submission_revoke($course_id, $student_id) {

	$poc_settings = get_option('dtlms-poc-settings');
	$poc_email_subject_prefix = ( isset($poc_settings['poc-email-subject-prefix']) && '' !=  $poc_settings['poc-email-subject-prefix'] ) ? $poc_settings['poc-email-subject-prefix'] : '';

	$instructor_ids = dtlms_poc_get_instructor_ids($course_id);
	foreach($instructor_ids as $instructor_id) {
		$to = get_the_author_meta('email', $instructor_id);
		$subject = $poc_email_subject_prefix.esc_html__('Student - Course Submission Revoked', 'dtlms-lite');

		$poc_content = sprintf(
			esc_html__('%2$s course %1$s submission have been revoked.','dtlms-lite'),
			'<a href="'.esc_url( get_permalink($course_id) ).'">'.esc_html( get_the_title($course_id) ).'</a>',
			'<a href="'.esc_url( get_author_posts_url($student_id) ).'">'.esc_html( get_the_author_meta('display_name', $student_id) ).'</a>'
		);
		
		dtlms_poc_email_configuration($to, $subject, $poc_content);
	}

}

// POC - Course Drip Content Agenda

function dtlms_poc_student_email_course_drip_content_agenda($course_id, $student_id) {

	$poc_settings = get_option('dtlms-poc-settings');
	$poc_email_subject_prefix = ( isset($poc_settings['poc-email-subject-prefix']) && '' !=  $poc_settings['poc-email-subject-prefix'] ) ? $poc_settings['poc-email-subject-prefix'] : '';

	$to = get_the_author_meta('email', $student_id);
	$subject = $poc_email_subject_prefix.esc_html__('Course Drip Content Agenda', 'dtlms-lite');
	$poc_content = dtlms_poc_generate_course_drip_content_agenda($course_id, $student_id);
	dtlms_poc_email_configuration($to, $subject, $poc_content);
}

function dtlms_poc_instructor_email_course_drip_content_agenda($course_id, $student_id) {

	$poc_settings = get_option('dtlms-poc-settings');
	$poc_email_subject_prefix = ( isset($poc_settings['poc-email-subject-prefix']) && '' !=  $poc_settings['poc-email-subject-prefix'] ) ? $poc_settings['poc-email-subject-prefix'] : '';

	$instructor_ids = dtlms_poc_get_instructor_ids($course_id);
	foreach($instructor_ids as $instructor_id) {
		$to = get_the_author_meta('email', $instructor_id);
		$subject = $poc_email_subject_prefix.esc_html__('Student - Course Drip Content Agenda', 'dtlms-lite');
		$poc_content = dtlms_poc_generate_course_drip_content_agenda($course_id, $student_id);
		dtlms_poc_email_configuration($to, $subject, $poc_content);
	}
}

// POC - Package Subscribed
function dtlms_poc_student_notification_package_subscribed($package_id, $student_id) {

	bp_notifications_add_notification( array(
		'user_id'           => $student_id,
		'item_id'           => $package_id,
		'secondary_item_id'	=> false,
		'component_name'    => 'dtlms_poc',
		'component_action'  => 'package_subscribed_student',
		'date_notified'     => bp_core_current_time(),
		'is_new'            => 1,
	) );

}

function dtlms_poc_student_email_package_subscribed($package_id, $student_id) {

	$poc_settings = get_option('dtlms-poc-settings');
	$poc_email_subject_prefix = ( isset($poc_settings['poc-email-subject-prefix']) && '' !=  $poc_settings['poc-email-subject-prefix'] ) ? $poc_settings['poc-email-subject-prefix'] : '';

	$to = get_the_author_meta('email', $student_id);
	$subject = $poc_email_subject_prefix.esc_html__('Package Subscribed', 'dtlms-lite');

	$poc_content = sprintf(
		esc_html__('You have subscribed Package %1$s','dtlms-lite'),
		'<a href="'.esc_url( get_permalink($package_id) ).'">'.esc_html( get_the_title($package_id) ).'</a>'
	);

	dtlms_poc_email_configuration($to, $subject, $poc_content);
}

function dtlms_poc_instructor_notification_package_subscribed($package_id, $student_id) {

	$instructor_ids = dtlms_poc_get_instructor_ids($package_id);
	foreach($instructor_ids as $instructor_id) {
		bp_notifications_add_notification( array(
			'user_id'           => $instructor_id,
			'item_id'           => $package_id,
			'secondary_item_id'	=> $student_id,
			'component_name'    => 'dtlms_poc',
			'component_action'  => 'package_subscribed_instructor',
			'date_notified'     => bp_core_current_time(),
			'is_new'            => 1,
		) );
	}

}

function dtlms_poc_instructor_email_package_subscribed($package_id, $student_id) {

	$poc_settings = get_option('dtlms-poc-settings');
	$poc_email_subject_prefix = ( isset($poc_settings['poc-email-subject-prefix']) && '' !=  $poc_settings['poc-email-subject-prefix'] ) ? $poc_settings['poc-email-subject-prefix'] : '';

	$instructor_ids = dtlms_poc_get_instructor_ids($package_id);
	foreach($instructor_ids as $instructor_id) {
		$to = get_the_author_meta('email', $instructor_id);
		$subject = $poc_email_subject_prefix.esc_html__('Student - Package Subscribed', 'dtlms-lite');

		$poc_content = sprintf(
			esc_html__('Package %1$s have been subscribed by %2$s','dtlms-lite'),
			'<a href="'.esc_url( get_permalink($package_id) ).'">'.esc_html( get_the_title($package_id) ).'</a>',
			'<a href="'.esc_url( get_author_posts_url($student_id) ).'">'.esc_html(  get_the_author_meta('display_name', $student_id) ).'</a>'
		);

		dtlms_poc_email_configuration($to, $subject, $poc_content);
	}

}

// POC - Package Subscribed Cancellation

function dtlms_poc_student_notification_package_subscription_cancellation($package_id, $student_id) {

	bp_notifications_add_notification( array(
		'user_id'           => $student_id,
		'item_id'           => $package_id,
		'secondary_item_id'	=> false,
		'component_name'    => 'dtlms_poc',
		'component_action'  => 'package_subscription_cancellation_student',
		'date_notified'     => bp_core_current_time(),
		'is_new'            => 1,
	) );

}

function dtlms_poc_student_email_package_subscription_cancellation($package_id, $student_id) {

	$poc_settings = get_option('dtlms-poc-settings');
	$poc_email_subject_prefix = ( isset($poc_settings['poc-email-subject-prefix']) && '' !=  $poc_settings['poc-email-subject-prefix'] ) ? $poc_settings['poc-email-subject-prefix'] : '';

	$to = get_the_author_meta('email', $student_id);
	$subject = $poc_email_subject_prefix.esc_html__('Package Subscription Cancellation', 'dtlms-lite');

	$poc_content = sprintf(
		esc_html__('Your subscription for Package %1$s have been cancelled','dtlms-lite'),
		'<a href="'.esc_url( get_permalink($package_id) ).'">'.esc_html( get_the_title($package_id) ).'</a>'
	);

	dtlms_poc_email_configuration($to, $subject, $poc_content);
}

function dtlms_poc_instructor_notification_package_subscription_cancellation($package_id, $student_id) {

	$instructor_ids = dtlms_poc_get_instructor_ids($package_id);
	foreach($instructor_ids as $instructor_id) {
		bp_notifications_add_notification( array(
			'user_id'           => $instructor_id,
			'item_id'           => $package_id,
			'secondary_item_id'	=> $student_id,
			'component_name'    => 'dtlms_poc',
			'component_action'  => 'package_subscription_cancellation_instructor',
			'date_notified'     => bp_core_current_time(),
			'is_new'            => 1,
		) );
	}

}

function dtlms_poc_instructor_email_package_subscription_cancellation($package_id, $student_id) {

	$poc_settings = get_option('dtlms-poc-settings');
	$poc_email_subject_prefix = ( isset($poc_settings['poc-email-subject-prefix']) && '' !=  $poc_settings['poc-email-subject-prefix'] ) ? $poc_settings['poc-email-subject-prefix'] : '';

	$instructor_ids = dtlms_poc_get_instructor_ids($package_id);
	foreach($instructor_ids as $instructor_id) {
		$to = get_the_author_meta('email', $instructor_id);
		$subject = $poc_email_subject_prefix.esc_html__('Student - Package Subscription Cancellation', 'dtlms-lite');

		$poc_content = sprintf(
			esc_html__('%2$s subscription for Package %1$s have been cancelled','dtlms-lite'),
			'<a href="'.esc_url( get_permalink($package_id) ).'">'.esc_html( get_the_title($package_id) ).'</a>',
			'<a href="'.esc_url( get_author_posts_url($student_id) ).'">'.esc_html( get_the_author_meta('display_name', $student_id) ).'</a>'
		);

		dtlms_poc_email_configuration($to, $subject, $poc_content);
	}

}


// Configure notification message here
if(!function_exists('dtlms_format_buddypress_notifications')) {
	function dtlms_format_buddypress_notifications( $action, $item_id, $secondary_item_id, $total_items, $format = 'string' ) {

		switch ( $action ) {
			case 'course_added_student':
				echo sprintf(
					esc_html__('New course %1$s have been added by %2$s.','dtlms-lite'),
					'<a href="'.esc_url( get_permalink($item_id) ).'">'.esc_html( get_the_title($item_id) ).'</a>',
					'<a href="'.esc_url( get_author_posts_url($secondary_item_id) ).'">'.esc_html( get_the_author_meta('display_name', $secondary_item_id) ).'</a>');
			break;
			case 'course_added_instructor':
				return sprintf(
					esc_html__('New course %1$s have been added by %2$s.','dtlms-lite'),
					'<a href="'.esc_url( get_permalink($item_id) ).'">'.esc_html( get_the_title($item_id) ).'</a>',
					'<a href="'.esc_url( get_author_posts_url($secondary_item_id) ).'">'.esc_html( get_the_author_meta('display_name', $secondary_item_id) ).'</a>'
				);
			break;
			case 'course_subscribed_student':
				return sprintf(
					esc_html__('You have subscribed course %1$s.','dtlms-lite'),
					'<a href="'.esc_url( get_permalink($item_id) ).'">'.esc_html( get_the_title($item_id) ).'</a>'
				);
			break;
			case 'course_subscribed_instructor':
				return sprintf(
					esc_html__('Course %1$s have been subscribed by %2$s.','dtlms-lite'),
					'<a href="'.esc_url( get_permalink($item_id) ).'">'.esc_html( get_the_title($item_id) ).'</a>',
					'<a href="'.esc_url( get_author_posts_url($secondary_item_id) ).'">'.esc_html( get_the_author_meta('display_name', $secondary_item_id) ).'</a>'
				);
			break;
			case 'course_subscription_cancellation_student':
				return sprintf(
					esc_html__('Your subscription for course %1$s have been cancelled.','dtlms-lite'),
					'<a href="'.esc_url( get_permalink($item_id) ).'">'.esc_html( get_the_title($item_id) ).'</a>'
				);
			break;
			case 'course_subscription_cancellation_instructor':
				return sprintf(
					esc_html__('%2$s subscription for course %1$s have been cancelled.','dtlms-lite'),
					'<a href="'.esc_url( get_permalink($item_id) ).'">'.esc_html( get_the_title($item_id) ).'</a>',
					'<a href="'.esc_url( get_author_posts_url($secondary_item_id) ).'">'.esc_html ( get_the_author_meta('display_name', $secondary_item_id) ).'</a>'
				);
			break;
			case 'course_assigned_student':
				return sprintf(
					esc_html__('Course %1$s have been assigned to you.','dtlms-lite'),
					'<a href="'.esc_url( get_permalink($item_id) ).'">'.esc_html( get_the_title($item_id) ).'</a>'
				);
			break;
			case 'course_assigned_instructor':
				return sprintf(
					esc_html__('Course %1$s have been assigned to %2$s.','dtlms-lite'),
					'<a href="'.esc_url( get_permalink($item_id) ).'">'.esc_html( get_the_title($item_id) ).'</a>',
					'<a href="'.esc_url( get_author_posts_url($secondary_item_id) ).'">'.esc_html( get_the_author_meta('display_name', $secondary_item_id) ).'</a>'
				);
			break;
			case 'course_started_student':
				return sprintf(
					esc_html__('You have started course %1$s.','dtlms-lite'),
					'<a href="'.esc_url( get_permalink($item_id) ).'">'.esc_html( get_the_title($item_id) ).'</a>'
				);
			break;
			case 'course_started_instructor':
				return sprintf(
					esc_html__('Course %1$s have been started by %2$s.','dtlms-lite'),
					'<a href="'.esc_url( get_permalink($item_id) ).'">'.esc_html( get_the_title($item_id) ).'</a>',
					'<a href="'.esc_url( get_author_posts_url($secondary_item_id) ).'">'.esc_html( get_the_author_meta('display_name', $secondary_item_id) ).'</a>'
				);
			break;
			case 'course_submitted_student':
				return sprintf(
					esc_html__('You have submitted course %1$s.','dtlms-lite'),
					'<a href="'.esc_url( get_permalink($item_id) ).'">'.esc_html( get_the_title($item_id) ).'</a>'
				);
			break;
			case 'course_submitted_instructor':
				return sprintf(
					esc_html__('Course %1$s have been submitted by %2$s.','dtlms-lite'),
					'<a href="'.esc_url( get_permalink($item_id) ).'">'.esc_html( get_the_title($item_id) ).'</a>',
					 '<a href="'.esc_url( get_author_posts_url($secondary_item_id) ).'">'.esc_html( get_the_author_meta('display_name', $secondary_item_id) ).'</a>'
				);
			break;
			case 'course_evaluated_student':
				return sprintf(
					esc_html__('Your course %1$s have been evaluated.','dtlms-lite'),
					'<a href="'.esc_url( get_permalink($item_id) ).'">'.esc_html( get_the_title($item_id) ).'</a>'
				);
			break;
			case 'course_evaluated_instructor':
				return sprintf(
					esc_html__('%2$s course %1$s have been evaluated.','dtlms-lite'),
					'<a href="'.esc_url( get_permalink($item_id) ).'">'.esc_html( get_the_title($item_id) ).'</a>',
					'<a href="'.esc_url( get_author_posts_url($secondary_item_id) ).'">'.esc_html( get_the_author_meta('display_name', $secondary_item_id)).'</a>'
				);
			break;
			case 'course_submission_revoked_student':
				return sprintf(
					esc_html__('Your course %1$s submission have been revoked.','dtlms-lite'),
					'<a href="'.esc_url( get_permalink($item_id) ).'">'.esc_html( get_the_title($item_id) ).'</a>'
				);
			break;
			case 'course_submission_revoked_instructor':
				return sprintf(
					esc_html__('%2$s course %1$s submission have been revoked.','dtlms-lite'),
					'<a href="'.esc_url( get_permalink($item_id) ).'">'.esc_html( get_the_title($item_id) ).'</a>',
					'<a href="'.esc_url( get_author_posts_url($secondary_item_id) ).'">'.esc_html( get_the_author_meta('display_name', $secondary_item_id) ).'</a>'
				);
			break;

			case 'package_subscribed_student':
				return sprintf(
					esc_html__('You have subscribed package %1$s.','dtlms-lite'),
					'<a href="'.esc_url( get_permalink($item_id) ).'">'.esc_html( get_the_title($item_id) ).'</a>'
				);
			break;
			case 'package_subscribed_instructor':
				return sprintf(
					esc_html__('Package %1$s have been subscribed by %2$s.','dtlms-lite'),
					'<a href="'.esc_url( get_permalink($item_id) ).'">'.esc_html( get_the_title($item_id) ).'</a>',
					 '<a href="'.esc_url( get_author_posts_url($secondary_item_id) ).'">'.esc_html( get_the_author_meta('display_name', $secondary_item_id) ).'</a>'
					);
			break;
			case 'package_subscription_cancellation_student':
				return sprintf(
					esc_html__('Your subscription for package %1$s have been cancelled.','dtlms-lite'),
					'<a href="'.esc_url( get_permalink($item_id) ).'">'.esc_html( get_the_title($item_id) ).'</a>'
				);
			break;
			case 'package_subscription_cancellation_instructor':
				return sprintf(
					esc_html__('%2$s subscription for package %1$s have been cancelled.','dtlms-lite'),
					'<a href="'.esc_url( get_permalink($item_id) ).'">'.esc_html( get_the_title($item_id) ).'</a>', 
					'<a href="'.esc_url( get_author_posts_url($secondary_item_id) ).'">'.esc_html( get_the_author_meta('display_name', $secondary_item_id) ).'</a>'
				);
			break;
		}

		return apply_filters( 'bp_course_format_notifications',false, $action, $item_id, $secondary_item_id, $total_items );

	}
	add_filter( 'bp_course_format_notifications', 'dtlms_format_buddypress_notifications', 10, 5 );
}
<?php

function dtlms_settings_set_commission_content() {

	$current_user = wp_get_current_user();
	$user_id = $current_user->ID;

	$output = '';

	$output .= '<div class="dtlms-settings-set-commission-container">';
		$output .= dtlms_setcom_load_instructor_courses($user_id);
	$output .= '</div>';

	return $output;

}

echo dtlms_settings_set_commission_content();
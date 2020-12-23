<?php
function dtlms_settings_pointofcontact_content() {

	$output = '';

	$poc_settings = get_option('dtlms-poc-settings');

	$instructor_singular_label = apply_filters( 'instructor_label', 'singular' );
	$dtlms_point_of_contacts   = apply_filters( 'dtlms_point_of_contacts', array () );

	$output .= '<div class="dtlms-settings-poc-container">';

		$output .= '<form name="formPocSettings" class="formPocSettings" method="post">';

			$output .= '<div class="dtlms-settings-options-holder">';
				$output .= '<div class="dtlms-column dtlms-one-fifth first">';
					$output .= '<label>'.esc_html__( 'Email Subject Prefix', 'dtlms-lite' ).'</label>';
				$output .= '</div>';
				$output .= '<div class="dtlms-column dtlms-four-fifth">';
		            $poc_email_subject_prefix = ( isset($poc_settings['poc-email-subject-prefix']) && '' !=  $poc_settings['poc-email-subject-prefix'] ) ? $poc_settings['poc-email-subject-prefix'] : '';
		            $output .= '<input id="poc-email-subject-prefix" name="dtlms-poc-settings[poc-email-subject-prefix]" type="text" value="'.esc_attr( $poc_email_subject_prefix ).'" />';
		            $output .= '<p class="dtlms-note">'.esc_html__('If you wish you can have email subject prefix here.', 'dtlms-lite').'</p>';
				$output .= '</div>';
			$output .= '</div>';


			foreach($dtlms_point_of_contacts as $point_of_contact) {

				$output .= '<div class="dtlms-settings-options-holder">';

					$output .= '<div class="dtlms-column dtlms-one-fifth first">';
						$output .= '<label>'.esc_html( $point_of_contact['label'] ).'</label>';
					$output .= '</div>';
					$output .= '<div class="dtlms-column dtlms-four-fifth">';
						$output .= '<div class="dtlms-column dtlms-one-column first">';
							$output .= '<div class="dtlms-column dtlms-one-fifth first">';
								$output .= '<label>'.esc_html__('Student', 'dtlms-lite').'</label>';
							$output .= '</div>';
							$output .= '<div class="dtlms-column dtlms-four-fifth">';
								$output .= '<div class="dtlms-column dtlms-one-fourth first">';
									$output .= '<label>'.esc_html__('Notification', 'dtlms-lite').'</label>';
								$output .= '</div>';
								$output .= '<div class="dtlms-column dtlms-one-fourth">';
									if(isset($point_of_contact['disable']) && $point_of_contact['disable'] == 'notification') {
										$output .= '-';
									} else {
					                    $checked     = ( isset($poc_settings[$point_of_contact['name']]['student']['notification']) && 'true' ==  $poc_settings[$point_of_contact['name']]['student']['notification'] ) ? ' checked="checked"' : '';
					                    $switchclass = ( isset($poc_settings[$point_of_contact['name']]['student']['notification']) && 'true' ==  $poc_settings[$point_of_contact['name']]['student']['notification'] ) ? 'checkbox-switch-on' :'checkbox-switch-off';
										
							            $output .= '<div data-for="'.esc_attr( $point_of_contact['name'] ).'-student-notification" class="dtlms-checkbox-switch '.esc_attr( $switchclass ).'"></div>';
							            $output .= '<input id="'.esc_attr( $point_of_contact['name'] ).'-student-notification" class="hidden" type="checkbox" name="dtlms-poc-settings['.esc_attr( $point_of_contact['name'] ).'][student][notification]" value="true" '.esc_attr( $checked ).' />';
							        }
								$output .= '</div>';
								$output .= '<div class="dtlms-column dtlms-one-fourth">';
									$output .= '<label>'.esc_html__('Email', 'dtlms-lite').'</label>';
								$output .= '</div>';
								$output .= '<div class="dtlms-column dtlms-one-fourth">';
				                    $checked     = ( isset($poc_settings[$point_of_contact['name']]['student']['email']) && 'true' ==  $poc_settings[$point_of_contact['name']]['student']['email'] ) ? ' checked="checked"' : '';
				                    $switchclass = ( isset($poc_settings[$point_of_contact['name']]['student']['email']) && 'true' ==  $poc_settings[$point_of_contact['name']]['student']['email'] ) ? 'checkbox-switch-on' :'checkbox-switch-off';
									
						            $output .= '<div data-for="'.esc_attr( $point_of_contact['name'] ).'-student-email" class="dtlms-checkbox-switch '.esc_attr( $switchclass ).'"></div>';
						            $output .= '<input id="'.esc_attr( $point_of_contact['name'] ).'-student-email" class="hidden" type="checkbox" name="dtlms-poc-settings['.esc_attr( $point_of_contact['name'] ).'][student][email]" value="true" '.esc_attr( $checked ).' />';
								$output .= '</div>';
							$output .= '</div>';
						$output .= '</div>';
						$output .= '<div class="dtlms-column dtlms-one-column first">';
							$output .= '<div class="dtlms-column dtlms-one-fifth first">';
								$output .= '<label>'.sprintf( esc_html__( '%1$s', 'dtlms-lite' ), $instructor_singular_label ).'</label>';
							$output .= '</div>';
							$output .= '<div class="dtlms-column dtlms-four-fifth">';
								$output .= '<div class="dtlms-column dtlms-one-fourth first">';
									$output .= '<label>'.esc_html__('Notification', 'dtlms-lite').'</label>';
								$output .= '</div>';
								$output .= '<div class="dtlms-column dtlms-one-fourth">';
									if(isset($point_of_contact['disable']) && $point_of_contact['disable'] == 'notification') {
										$output .= '-';
									} else {
					                    $checked     = ( isset($poc_settings[$point_of_contact['name']]['instructor']['notification']) && 'true' ==  $poc_settings[$point_of_contact['name']]['instructor']['notification'] ) ? ' checked="checked"' : '';
					                    $switchclass = ( isset($poc_settings[$point_of_contact['name']]['instructor']['notification']) && 'true' ==  $poc_settings[$point_of_contact['name']]['instructor']['notification'] ) ? 'checkbox-switch-on' :'checkbox-switch-off';
										
							            $output .= '<div data-for="'.esc_attr( $point_of_contact['name'] ).'-instructor-notification" class="dtlms-checkbox-switch '.esc_attr( $switchclass ).'"></div>';
							            $output .= '<input id="'.esc_attr( $point_of_contact['name'] ).'-instructor-notification" class="hidden" type="checkbox" name="dtlms-poc-settings['.esc_attr( $point_of_contact['name'] ).'][instructor][notification]" value="true" '.esc_attr( $checked ).' />';
							        }
								$output .= '</div>';
								$output .= '<div class="dtlms-column dtlms-one-fourth">';
									$output .= '<label>'.esc_html__('Email', 'dtlms-lite').'</label>';
								$output .= '</div>';
								$output .= '<div class="dtlms-column dtlms-one-fourth">';
				                    $checked     = ( isset($poc_settings[$point_of_contact['name']]['instructor']['email']) && 'true' ==  $poc_settings[$point_of_contact['name']]['instructor']['email'] ) ? ' checked="checked"' : '';
				                    $switchclass = ( isset($poc_settings[$point_of_contact['name']]['instructor']['email']) && 'true' ==  $poc_settings[$point_of_contact['name']]['instructor']['email'] ) ? 'checkbox-switch-on' :'checkbox-switch-off';

									$output .= '<div data-for="'.esc_attr( $point_of_contact['name'] ).'-instructor-email" class="dtlms-checkbox-switch '.esc_attr( $switchclass ).'"></div>';
						            $output .= '<input id="'.esc_attr( $point_of_contact['name'] ).'-instructor-email" class="hidden" type="checkbox" name="dtlms-poc-settings['.esc_attr( $point_of_contact['name'] ).'][instructor][email]" value="true" '.esc_attr( $checked ).' />';
								$output .= '</div>';
							$output .= '</div>';
						$output .= '</div>';
					$output .= '</div>';

				$output .= '</div>';

			}

			$output .= '<p class="dtlms-note">'.esc_html__('Make sure "BuddyPress" plugin is activated for notification concept.', 'dtlms-lite').'</p>';

			$output .= '<div class="dtlms-poc-settings-response-holder"></div>';

			$output .= '<a href="#" class="dtlms-button dtlms-save-poc-settings small">'.esc_html__('Save Settings', 'dtlms-lite').'</a>';

		$output .= '</form>';

	$output .= '</div>';

	return $output;
}

echo dtlms_settings_pointofcontact_content();
<?php

function dtlms_settings_typography_content() {

	$google_fonts = dtlms_fonts();

    $title_font_family = dtlms_option('typography', 'title-font-family');

    $output = '';

	$output .= '<div class="dtlms-settings-typography-container">';

		$output .= '<form name="formOptionSettings" class="formOptionSettings" method="post">';

			$output .= '<div class="dtlms-settings-options-holder">';

				$output .= '<div class="dtlms-column dtlms-one-fifth first">';
					$output .= '<label>'.esc_html__( 'Title Font Family', 'dtlms-lite' ).'</label>';
				$output .= '</div>';
				$output .= '<div class="dtlms-column dtlms-four-fifth">';
				    $output .= '<select id="title-font-family" name="dtlms[typography][title-font-family]">';

				    	$output .= '<option value="">'.esc_html__('Default', 'dtlms-lite').'</option>';

						# System fonts
						$output .= '<optgroup label="'. esc_html__('System', 'dtlms-lite') .'">';
						foreach ( $google_fonts['system'] as $font ) {
							$output .= '<option value="'. esc_attr( $font ) .'"'.selected($title_font_family, $font, false).'>'. esc_html( $font ) .'</option>';
						}
						$output .= '</optgroup>';

						# Google fonts | all
						$output .= '<optgroup label="'. esc_html__('Google Fonts', 'dtlms-lite') .'">';
						foreach ( $google_fonts['all'] as $font ) {
							$output .= '<option value="'. esc_attr( $font ) .'"'.selected($title_font_family, $font, false).'>'. esc_html( $font ) .'</option>';
						}
						$output .= '</optgroup>';

					$output .= '</select>';
		            $output .= '<p class="dtlms-note">'.esc_html__('Choose title font family here.', 'dtlms-lite').'</p>';
				$output .= '</div>';

			$output .= '</div>';

			$output .= '<div class="dtlms-option-settings-response-holder"></div>';

			$output .= '<a href="#" class="dtlms-button dtlms-save-options-settings small" data-settings="typography">'.esc_html__('Save Settings', 'dtlms-lite').'</a>';

		$output .= '</form>';

	$output .= '</div>';

    return $output;

}

echo dtlms_settings_typography_content();
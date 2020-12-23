<?php

if( !function_exists( 'dtlms_adminpanel_image_preview' ) ){
	function dtlms_adminpanel_image_preview($src) {

		$default = DTLMS_PLUGIN_URL.'assets/images/backend/no-image.jpg';
		$src = !empty($src) ? $src : $default;

		$output = '';

		$output .= '<div class="dtlms-image-preview-holder">';
			$output .= '<a href="#" class="dtlms-image-preview" onclick="return false;">
							<img src="'.DTLMS_PLUGIN_URL.'assets/images/backend/image-preview.png" alt="'.esc_html__('Image Preview', 'dtlms-lite').'" title="'.esc_html__('Image Preview', 'dtlms-lite').'" />';
							$output .= '<div class="dtlms-image-preview-tooltip">';
								$output .= '<img src="'.esc_url($src).'" data-default="'.esc_attr($default).'"  alt="'.esc_html__('Image Preview Tooltip', 'dtlms-lite').'" title="'.esc_html__('Image Preview Tooltip', 'dtlms-lite').'" />';
							$output .= '</div>';
			$output .= '</a>';
		$output .= '</div>';

		return $output;

	}
}
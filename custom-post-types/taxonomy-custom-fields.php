<?php

if( !class_exists('DTLMSTaxonomyCustomFields') ) {

	class DTLMSTaxonomyCustomFields {

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

			add_filter ( 'dtlms_taxonomies', array ( $this, 'dtlms_update_taxonomies' ), 10, 1 );

			$taxonomies = apply_filters( 'dtlms_taxonomies', array () );

			foreach($taxonomies as $taxonomy => $taxonomy_label) {
				add_action ( $taxonomy.'_add_form_fields', array ( $this, 'dtlms_add_taxonomy_form_fields' ), 10, 2 );
				add_action ( 'created_'.$taxonomy, array ( $this, 'dtlms_save_taxonomy_form_fields' ), 10, 2 );
				add_action ( $taxonomy.'_edit_form_fields', array ( $this, 'dtlms_update_taxonomy_form_fields' ), 10, 2 );
				add_action ( 'edited_'.$taxonomy, array ( $this, 'dtlms_updated_taxonomy_form_fields' ), 10, 2 );
			}

		}

		function dtlms_update_taxonomies($taxonomies) {

			$taxonomies['course_category'] = esc_html__('Course Category', 'dtlms-lite');

			return $taxonomies;

		}

		function dtlms_add_taxonomy_form_fields ( $taxonomy ) {

			echo '<div class="form-field term-group">
					<label for="category-image">'.esc_html__('Image', 'dtlms-lite').'</label>
					<div class="dtlms-upload-media-items-container">
						<input name="dtlms-category-image-url" type="hidden" class="uploadfieldurl" readonly value=""/>
						<input name="dtlms-category-image-id" type="hidden" class="uploadfieldid" readonly value=""/>
						<input type="button" value="'.esc_attr__('Add Image', 'dtlms-lite').'" class="dtlms-upload-media-item-button show-preview with-image-holder" />
						<input type="button" value="'.esc_attr__('Remove','dtlms-lite').'" class="dtlms-upload-media-item-reset" />
						'.dtlms_adminpanel_image_preview('').'
					</div>
					<p>'.esc_html__('This option will be used for "Course Categories" shortcode.', 'dtlms-lite').'</p>
				</div>';

			echo '<div class="form-field term-group">
					<label for="category-iconimage">'.esc_html__('Icon Image', 'dtlms-lite').'</label>
					<div class="dtlms-upload-media-items-container">
						<input name="dtlms-category-iconimage-url" type="hidden" class="uploadfieldurl" readonly value=""/>
						<input name="dtlms-category-iconimage-id" type="hidden" class="uploadfieldid" readonly value=""/>
						<input type="button" value="'.esc_attr__('Add Icon Image', 'dtlms-lite').'" class="dtlms-upload-media-item-button show-preview with-image-holder" />
						<input type="button" value="'.esc_attr__('Remove','dtlms-lite').'" class="dtlms-upload-media-item-reset" />
						'.dtlms_adminpanel_image_preview('').'
					</div>
					<p>'.esc_html__('This option will be used for "Course Categories" shortcode.', 'dtlms-lite').'</p>
				</div>';

			echo '<div class="form-field term-group">
					<label for="category-icon">'.esc_html__('Icon', 'dtlms-lite').'</label>
					<input type="text" name="dtlms-category-icon" value="">
					<p>'.esc_html__('This option will be used for "Course Categories" shortcode.', 'dtlms-lite').'</p>
				</div>';

			echo '<div class="form-field term-group">
					<label for="category-icon-color">'.esc_html__( 'Icon Color', 'dtlms-lite' ).'</label>
					<input name="dtlms-category-icon-color" class="dtlms-color-field color-picker" data-alpha="true" type="text" value="" />
					<p>'.esc_html__('Choose icon color here.', 'dtlms-lite').'</p>
				</div>';

			echo '<div class="form-field term-group">
					<label for="background-color">'.esc_html__( 'Background Color', 'dtlms-lite' ).'</label>
					<input name="dtlms-background-color" class="dtlms-color-field color-picker" data-alpha="true" type="text" value="" />
					<p>'.esc_html__('Choose background color here.', 'dtlms-lite').'</p>
				</div>';

		}

		function dtlms_save_taxonomy_form_fields ( $term_id, $tt_id ) {

			if( isset( $_POST['dtlms-category-image-url'] ) ){
				$image_url = dtlms_recursive_sanitize_text_field( $_POST['dtlms-category-image-url'] );
				add_term_meta( $term_id, 'dtlms-category-image-url', $image_url, true );
			}

			if( isset( $_POST['dtlms-category-image-id'] ) ){
				$image_id = dtlms_recursive_sanitize_text_field( $_POST['dtlms-category-image-id'] );
				add_term_meta( $term_id, 'dtlms-category-image-id', $image_id, true );
			}

			if( isset( $_POST['dtlms-category-iconimage-url'] ) ){
				$iconimage_url = dtlms_recursive_sanitize_text_field( $_POST['dtlms-category-iconimage-url'] );
				add_term_meta( $term_id, 'dtlms-category-iconimage-url', $iconimage_url, true );
			}

			if( isset( $_POST['dtlms-category-iconimage-id'] ) ){
				$iconimage_id = dtlms_recursive_sanitize_text_field( $_POST['dtlms-category-iconimage-id'] );
				add_term_meta( $term_id, 'dtlms-category-iconimage-id', $iconimage_id, true );
			}

			if( isset( $_POST['dtlms-category-icon'] ) ){
				$category_icon = dtlms_recursive_sanitize_text_field( $_POST['dtlms-category-icon'] );
				add_term_meta( $term_id, 'dtlms-category-icon', $category_icon, true );
			}

			if( isset( $_POST['dtlms-category-icon-color'] ) ){
				$category_icon_color = dtlms_recursive_sanitize_text_field( $_POST['dtlms-category-icon-color'] );
				add_term_meta( $term_id, 'dtlms-category-icon-color', $category_icon_color, true );
			}

			if( isset( $_POST['dtlms-background-color'] ) ){
				$background_color = dtlms_recursive_sanitize_text_field( $_POST['dtlms-background-color'] );
				add_term_meta( $term_id, 'dtlms-background-color', $background_color, true );
			}

		}

		function dtlms_update_taxonomy_form_fields ( $term, $taxonomy ) {

			echo '<tr class="form-field term-group-wrap">
					<th scope="row">
						<label for="category-image">'.esc_html__('Image', 'dtlms-lite').'</label>
					</th>
					<td>';
						$image_url = get_term_meta( $term->term_id, 'dtlms-category-image-url', true );
						$image_id = get_term_meta( $term->term_id, 'dtlms-category-image-id', true );
					echo '<div class="dtlms-upload-media-items-container">
							<input name="dtlms-category-image-url" type="hidden" class="uploadfieldurl" readonly value="'.esc_attr( $image_url ).'"/>
							<input name="dtlms-category-image-id" type="hidden" class="uploadfieldid" readonly value="'.esc_attr( $image_id ).'"/>
							<input type="button" value="'.esc_html__( 'Add Image', 'dtlms-lite' ).'" class="dtlms-upload-media-item-button show-preview with-image-holder" />
							<input type="button" value="'.esc_html__('Remove','dtlms-lite').'" class="dtlms-upload-media-item-reset" />
							'.dtlms_adminpanel_image_preview($image_url).'
						</div>
						<p>'.esc_html__('This option will be used for "Course Categories" shortcode.', 'dtlms-lite').'</p>
					</td>
				</tr>';

			echo '<tr class="form-field term-group-wrap">
					<th scope="row">
						<label for="category-iconimage">'.esc_html__('Icon Image', 'dtlms-lite').'</label>
					</th>
					<td>';
						$iconimage_url = get_term_meta( $term->term_id, 'dtlms-category-iconimage-url', true );
						$iconimage_id = get_term_meta( $term->term_id, 'dtlms-category-iconimage-id', true );
					echo '<div class="dtlms-upload-media-items-container">
							<input name="dtlms-category-iconimage-url" type="hidden" class="uploadfieldurl" readonly value="'.esc_attr( $iconimage_url ).'"/>
							<input name="dtlms-category-iconimage-id" type="hidden" class="uploadfieldid" readonly value="'.esc_attr( $iconimage_id ).'"/>
							<input type="button" value="'.esc_html__( 'Add Image', 'dtlms-lite' ).'" class="dtlms-upload-media-item-button show-preview with-image-holder" />
							<input type="button" value="'.esc_html__('Remove','dtlms-lite').'" class="dtlms-upload-media-item-reset" />
							'.dtlms_adminpanel_image_preview($iconimage_url).'
						</div>
						<p>'.esc_html__('This option will be used for "Course Categories" shortcode.', 'dtlms-lite').'</p>
					</td>
				</tr>';

			echo '<tr class="form-field term-group-wrap">
					<th scope="row">
						<label for="category-icon">'.esc_html__('Icon', 'dtlms-lite').'</label>
					</th>
					<td>';
						$icon = get_term_meta ( $term->term_id, 'dtlms-category-icon', true );
						echo '<input type="text" name="dtlms-category-icon" value="'.esc_attr( $icon ).'">
						<p>'.esc_html__('This option will be used for "Course Categories" shortcode.', 'dtlms-lite').'</p>
					</td>
				</tr>';

			echo '<tr class="form-field term-group-wrap">
					<th scope="row">
						<label for="category-icon-color">'.esc_html__('Icon Color', 'dtlms-lite').'</label>
					</th>
					<td>';
						$icon_color = get_term_meta ( $term->term_id, 'dtlms-category-icon-color', true );
						echo '<input name="dtlms-category-icon-color" class="dtlms-color-field color-picker" data-alpha="true" type="text" value="'.esc_attr( $icon_color ).'" />
						<p>'.esc_html__('Choose icon color here.', 'dtlms-lite').'</p>
					</td>
				</tr>';

			echo '<tr class="form-field term-group-wrap">
					<th scope="row">
						<label for="background-color">'.esc_html__('Background Color', 'dtlms-lite').'</label>
					</th>
					<td>';
						$background_color = get_term_meta ( $term->term_id, 'dtlms-background-color', true );
						echo '<input name="dtlms-background-color" class="dtlms-color-field color-picker" data-alpha="true" type="text" value="'.esc_attr( $background_color ).'" />
						<p>'.esc_html__('Choose background color here.', 'dtlms-lite').'</p>
					</td>
				</tr>';

		}

		function dtlms_updated_taxonomy_form_fields ( $term_id, $tt_id ) {

			//Don't update on Quick Edit
			if (defined('DOING_AJAX') ) {
				return $post_id;
			}

			if( isset( $_POST['dtlms-category-image-url'] ) && '' !== $_POST['dtlms-category-image-url'] ){
				$image_url = dtlms_recursive_sanitize_text_field( $_POST['dtlms-category-image-url'] );
				update_term_meta ( $term_id, 'dtlms-category-image-url', $image_url );
			} else {
				update_term_meta ( $term_id, 'dtlms-category-image-url', '' );
			}

			if( isset( $_POST['dtlms-category-image-id'] ) && '' !== $_POST['dtlms-category-image-id'] ){
				$image_id = dtlms_recursive_sanitize_text_field( $_POST['dtlms-category-image-id'] );
				update_term_meta ( $term_id, 'dtlms-category-image-id', $image_id );
			} else {
				update_term_meta ( $term_id, 'dtlms-category-image-id', '' );
			}

			if( isset( $_POST['dtlms-category-iconimage-url'] ) && '' !== $_POST['dtlms-category-iconimage-url'] ){
				$iconimage_url = dtlms_recursive_sanitize_text_field( $_POST['dtlms-category-iconimage-url'] );
				update_term_meta ( $term_id, 'dtlms-category-iconimage-url', $iconimage_url );
			} else {
				update_term_meta ( $term_id, 'dtlms-category-iconimage-url', '' );
			}

			if( isset( $_POST['dtlms-category-iconimage-id'] ) && '' !== $_POST['dtlms-category-iconimage-id'] ){
				$iconimage_id = dtlms_recursive_sanitize_text_field( $_POST['dtlms-category-iconimage-id'] );
				update_term_meta ( $term_id, 'dtlms-category-iconimage-id', $iconimage_id );
			} else {
				update_term_meta ( $term_id, 'dtlms-category-iconimage-id', '' );
			}

			if( isset( $_POST['dtlms-category-icon'] ) && '' !== $_POST['dtlms-category-icon'] ){
				$icon = dtlms_recursive_sanitize_text_field( $_POST['dtlms-category-icon'] );
				update_term_meta ( $term_id, 'dtlms-category-icon', $icon );
			} else {
				update_term_meta ( $term_id, 'dtlms-category-icon', '' );
			}

			if( isset( $_POST['dtlms-category-icon-color'] ) && '' !== $_POST['dtlms-category-icon-color'] ){
				$icon_color = dtlms_recursive_sanitize_text_field( $_POST['dtlms-category-icon-color'] );
				update_term_meta ( $term_id, 'dtlms-category-icon-color', $icon_color );
			} else {
				update_term_meta ( $term_id, 'dtlms-category-icon-color', '' );
			}

			if( isset( $_POST['dtlms-background-color'] ) && '' !== $_POST['dtlms-background-color'] ){
				$background_color = dtlms_recursive_sanitize_text_field( $_POST['dtlms-background-color'] );
				update_term_meta ( $term_id, 'dtlms-background-color', $background_color );
			} else {
				update_term_meta ( $term_id, 'dtlms-background-color', '' );
			}

		}

	}

	DTLMSTaxonomyCustomFields::instance();
}

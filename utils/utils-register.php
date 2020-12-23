<?php
// Register user roles
if(!function_exists('dtlms_user_roles')) {

    function dtlms_user_roles() {
		$instructor_capability = array(
			'delete_posts'           => true,
			'delete_published_posts' => true,
			'edit_posts'             => true,
			'manage_categories'      => true,
			'edit_published_posts'   => true,
			'publish_posts'          => true,
			'read'                   => true,
			'upload_files'           => true,
			'unfiltered_html'        => true,
			'level_1'                => true
		);

		$student_capability = array( 'read' );
    
		add_role( 'instructor', esc_html__('Instructor','dtlms-lite'), $instructor_capability); 
		add_role( 'student', esc_html__('Student','dtlms-lite'), $student_capability );
	}    

	add_action('init','dtlms_user_roles');
}

// Remove admin bar
if(!function_exists('dtlms_remove_admin_bar')) {
	
    function dtlms_remove_admin_bar() {
        if (!current_user_can('edit_posts')) {
         	show_admin_bar(false);
        }
    }

    add_action('after_setup_theme', 'dtlms_remove_admin_bar');
}


// Register user profile fields
add_action( 'show_user_profile', 'dtlms_social_links_sc' );
add_action( 'edit_user_profile', 'dtlms_social_links_sc' );
function dtlms_social_links_sc( $user )
{

	$sociables = array('fa-dribbble' => 'Dribble', 'fa-flickr' => 'Flickr', 'fa-github' => 'GitHub', 'fa-pinterest-p' => 'Pinterest', 'fa-stack-overflow' => 'Stack Overflow', 'fa-twitter' => 'Twitter', 'fa-youtube' => 'YouTube', 'fa-android' => 'Android', 'fa-dropbox' => 'Dropbox', 'fa-instagram' => 'Instagram', 'fa-facebook-f' => 'Facebook', 'fa-google-plus-g' => 'Google Plus', 'fa-linkedin-in' => 'LinkedIn', 'fa-skype' => 'Skype', 'fa-tumblr' => 'Tumblr', 'fa-vimeo-square' => 'Vimeo');

	echo '<h2>'.esc_html__('Author additional information','dtlms-lite').'</h2>';

	echo '<table class="form-table">
			<tbody>

				<tr class="user-description-wrap">
					<th>'.esc_html__('Specialization', 'dtlms-lite').'</th>
					<td>';
						$user_specialization = get_the_author_meta('user-specialization', $user->ID);
						$user_specialization = isset($user_specialization) ? $user_specialization : '';
						echo '<input class="large" type="text" placeholder="'.esc_html__('Specialization', 'dtlms-lite').'" style="width:80%" id="user-specialization" name="user-specialization" value="'.esc_attr( $user_specialization ).'" />
					</td>
				</tr>

				<tr class="user-profile-picture">
					<th>'.esc_html__('Social Links', 'dtlms-lite').'</th>
					<td>

						<div id="dtlms-user-details-container">';
							$user_social_items = get_the_author_meta('user-social-items', $user->ID);
							$user_social_items = (isset($user_social_items) && is_array($user_social_items)) ? $user_social_items : array();

							$user_social_items_value = get_the_author_meta('user-social-items-value', $user->ID);
							$user_social_items_value = (isset($user_social_items_value) && is_array($user_social_items_value)) ? $user_social_items_value : array();

							$i = 0;
							foreach($user_social_items as $user_social_item) {

							    echo '<div id="dtlms-user-section-item">';
							        
									echo '<select class="social-item-list social-item-chosen" id="user-social-items" name="user-social-items[]">';
										foreach ( $sociables as $sociable_key => $sociable_value ) :
											$s = ($sociable_key == $user_social_item) ? 'selected="selected"' : '';
											$v = ucwords ( $sociable_value );
											echo '<option value="'.esc_attr( $sociable_key ).'" '.$s.'>'.esc_html( $v ).'</option>';
										endforeach;
									echo '</select>';

							        echo '<input class="large" type="text" placeholder="'.esc_html__('Social Link', 'dtlms-lite').'" style="width:91.2%" id="user-social-items-value" name="user-social-items-value[]" value="'.esc_attr( $user_social_items_value[$i] ).'" />';

							        echo '<span class="dtlms-remove-user-tab"><span class="fas fa-times"></span></span>';
							        echo '<span class="fas fa-arrows-alt"></span>';
							    
							    echo '</div>';

							    $i++;

							}

					echo '</div>';

					echo '<a href="#" class="dtlms-add-user-social custom-button-style">'.esc_html__('Add Social Item', 'dtlms-lite').'</a>';

				    echo '<div id="dtlms-user-section-to-clone" class="hidden">';
				        
						echo '<select class="social-item-list">';
							foreach ( $sociables as $key => $value ) :
								$v = ucwords ( $value );
								echo '<option value="'.esc_attr( $key).'">'.esc_html( $v ).'</option>';
							endforeach;
						echo '</select>';

				        echo '<input class="large" type="text" placeholder="'.esc_html__('Social Link', 'dtlms-lite').'" style="width:91.2%" />';

				        echo '<span class="dtlms-remove-user-tab"><span class="fas fa-times"></span></span>';
				        echo '<span class="fas fa-arrows-alt"></span>';
				    
				    echo '</div>';

				 echo '</td>
				</tr>

			</tbody>
		</table>';

}

add_action( 'personal_options_update', 'dtlms_save_social_links_sc' );
add_action( 'edit_user_profile_update', 'dtlms_save_social_links_sc' );
function dtlms_save_social_links_sc( $user_id )
{
	if(isset($_POST['user-specialization']) && $_POST['user-specialization'] != '') {
		update_user_meta( $user_id,'user-specialization', sanitize_textarea_field( $_POST['user-specialization'] ) );	
	}
	if(isset($_POST['user-social-items']) && $_POST['user-social-items'] != '') {
		update_user_meta( $user_id,'user-social-items', sanitize_textarea_field( $_POST['user-social-items'] ) );	
	}
	if(isset($_POST['user-social-items-value']) && $_POST['user-social-items-value'] != '') {
		update_user_meta( $user_id,'user-social-items-value', sanitize_textarea_field( $_POST['user-social-items-value'] ) );	
	}
}
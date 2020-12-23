<?php

// Save the comment meta data along with comment
if(!function_exists('dtlms_save_comment_ratings')) {

	function dtlms_save_comment_ratings( $comment_id, $comment_approved, $commentdata ) {

		if(get_post_type($commentdata['comment_post_ID']) != 'dtlms_classes' && get_post_type($commentdata['comment_post_ID']) != 'dtlms_courses') {   
            return;
        }

		if ((isset( $_POST['lms_title'])) && ($_POST['lms_title'] != ''))
		$lms_title = wp_filter_nohtml_kses($_POST['lms_title']);
		add_comment_meta( $comment_id, 'lms_title', $lms_title );

		if ((isset( $_POST['lms_rating'])) && ($_POST['lms_rating'] != ''))
		$lms_rating = wp_filter_nohtml_kses($_POST['lms_rating']);
		add_comment_meta( $comment_id, 'lms_rating', $lms_rating );

		if( $comment_approved == 1 ) {
			$course_id = $commentdata['comment_post_ID'];
			$average_ratings = dtlms_get_average_comment_ratings($course_id);
			update_post_meta($course_id, 'average-ratings', $average_ratings);
		}

	}

	add_action( 'comment_post', 'dtlms_save_comment_ratings', 10, 3 );

}

// Add the filter to check if the comment meta data has been filled or not
if(!function_exists('dtlms_verify_comment_ratings')) {

	function dtlms_verify_comment_ratings( $commentdata ) {

		if(is_singular('dtlms_classes') || is_singular('dtlms_courses')) {
			if ( ! isset( $_POST['lms_rating'] ) ) {
				wp_die( esc_html__( 'Error: You did not add your rating. Hit the BACK button of your Web browser and resubmit your comment with rating.' ) );
			}
		}
		return $commentdata;

	}

	add_filter( 'preprocess_comment', 'dtlms_verify_comment_ratings' );

}

//Add an edit option in comment admin panel
if(!function_exists('dtlms_comment_add_rating_metabox')) {

	function dtlms_comment_add_rating_metabox() {
	    add_meta_box( 'title', esc_html__( 'Comment Ratings' ), 'dtlms_comment_ratings_metabox', 'comment', 'normal', 'high' );
	}

	add_action( 'add_meta_boxes_comment', 'dtlms_comment_add_rating_metabox' );

}
 
if(!function_exists('dtlms_comment_ratings_metabox')) {

	function dtlms_comment_ratings_metabox ( $comment ) {

	    $lms_title = get_comment_meta( $comment->comment_ID, 'lms_title', true );
	    $lms_rating = get_comment_meta( $comment->comment_ID, 'lms_rating', true );

	    wp_nonce_field( 'dtlms_comment_update', 'dtlms_comment_update', false );
	    ?>
	    <p>
	        <label for="title"><?php _e( 'Comment Title' ); ?></label>
	        <input type="text" name="lms_title" value="<?php echo esc_attr( $lms_title ); ?>" class="widefat" />
	    </p>

	    <div class="ratings-holder">
			<p class="ratings">
				<?php echo dtlms_comment_rating_display($lms_rating); ?>
			</p>
			<input id="lms_rating" name="lms_rating" type="hidden" size="30" tabindex="5" value="<?php echo $lms_rating; ?>" />
		</div>
	    <?php

	}

}

// Update comment meta data from comment edit screen 
if(!function_exists('dtlms_comment_edit_metafields')) {

	function dtlms_comment_edit_metafields( $comment_id, $commentdata ) {

	    if( ! isset( $_POST['dtlms_comment_update'] ) || ! wp_verify_nonce( $_POST['dtlms_comment_update'], 'dtlms_comment_update' ) ) { 
	    	return;
	    }
			
		if ( ( isset( $_POST['lms_title'] ) ) && ( $_POST['lms_title'] != '') ):
			$lms_title = wp_filter_nohtml_kses($_POST['lms_title']);
			update_comment_meta( $comment_id, 'lms_title', $lms_title );
		else :
			delete_comment_meta( $comment_id, 'lms_title');
		endif;

		if ( ( isset( $_POST['lms_rating'] ) ) && ( $_POST['lms_rating'] != '') ):
			$lms_rating = wp_filter_nohtml_kses($_POST['lms_rating']);
			update_comment_meta( $comment_id, 'lms_rating', $lms_rating );
		else :
			delete_comment_meta( $comment_id, 'lms_rating');
		endif;

		if( $commentdata['comment_approved'] == 1 ) {
			$course_id = $commentdata['comment_post_ID'];
			$average_ratings = dtlms_get_average_comment_ratings($course_id);
			update_post_meta($course_id, 'average-ratings', $average_ratings);
		}		
		
	}

	add_action( 'edit_comment', 'dtlms_comment_edit_metafields', 10, 2 );

}

if(!function_exists('dtlms_move_comment_field_to_bottom')) {

	function dtlms_move_comment_field_to_bottom( $fields ) {

		if(is_singular('dtlms_classes') || is_singular('dtlms_courses')) {
			$comment_field = $fields['comment'];
			$cookies_field = $fields['cookies'];
			unset( $fields['comment'] );
			unset( $fields['cookies'] );
			$fields['comment'] = $comment_field;
			$fields['cookies'] = $cookies_field;
		}
		return $fields;

	}

	add_filter( 'comment_form_fields', 'dtlms_move_comment_field_to_bottom' );

}


if(!function_exists('dtlms_modify_comment')) {

	function dtlms_modify_comment( $text ){

		global $comment;

		if(isset($comment)) {

			if((get_post_type($comment->comment_post_ID) == 'dtlms_classes' || get_post_type($comment->comment_post_ID) == 'dtlms_courses')) {

				$commenttitle = '';

				if( $commenttitle = get_comment_meta( get_comment_ID(), 'lms_title', true ) ) {

					$commenttitle = '<strong>' . esc_attr( $commenttitle ) . '</strong><br/>';

				}

				$commentrating_str = '';

				if( $commentrating = get_comment_meta( get_comment_ID(), 'lms_rating', true ) ) {

					if($commentrating != '') {

						$commentrating_str .= '<div class="dtlms-comment-rating">';
							$commentrating_str .= dtlms_comment_rating_display($commentrating);
						$commentrating_str .= '</div>';

					}

				} 

				$text = $commentrating_str . $commenttitle . $text;

			}

		}

		return $text; 

	}

	add_filter( 'comment_text', 'dtlms_modify_comment');

}

function dtlms_comment_rating_display($rating_value) {

	$output = '';


	$average_rating_half_empty = '';
	if(strpos($rating_value, '.') !== false) {
		$average_rating_half_empty = ceil($rating_value);
	}

	$average_rating_ceil = ceil($rating_value);
	$average_rating_floor = floor($rating_value);

	for($i = 1; $i <= 5; $i++) {
		if($i <= $average_rating_floor) {
			$output .= '<span class="icon-moon icon-moon-star-full" data-value="'.esc_attr($i).'"></span>';	
		}
		if($average_rating_half_empty != '' && $average_rating_half_empty == $i) {
			$output .= '<span class="icon-moon icon-moon-star-empty2" data-value="'.esc_attr($i).'"></span>';		
		}
		if($i > $average_rating_ceil) {
			$output .= '<span class="icon-moon icon-moon-star-empty" data-value="'.esc_attr($i).'"></span>';		
		}					
	}

	return $output;

}

// Calculate average comment rating
if(!function_exists('dtlms_get_average_comment_ratings')) {

	function dtlms_get_average_comment_ratings($course_id) {

		$comments = get_approved_comments( $course_id );

		$total_commentrating = $total_comments = 0;
		foreach($comments as $comment) {
			$commentrating = get_comment_meta( $comment->comment_ID, 'lms_rating', true );
			if($commentrating != '') {
				$total_commentrating = $total_commentrating + $commentrating;
				$total_comments++;
			}
		}

		$average_rating = ($total_comments > 0) ? ($total_commentrating/$total_comments) : 0;

		return $average_rating;

	}

}

// Update average ratings on comment transition
if(!function_exists('dtlms_transition_comment_status')) {

	function dtlms_transition_comment_status( $new_status, $old_status, $commentdata ) {

		if( $new_status === 'approved' || $new_status === 'unapproved' ) {		
			$course_id = $commentdata->comment_post_ID;
			$average_ratings = dtlms_get_average_comment_ratings($course_id);
			update_post_meta($course_id, 'average-ratings', $average_ratings);
		}

	}

	add_action( 'transition_comment_status', 'dtlms_transition_comment_status', 10, 3 );

}

// Update average ratings on comment delete
if(!function_exists('dtlms_delete_comment')) {

	function dtlms_delete_comment( $comment_id ) {

		$commentdata = get_comment($comment_id);

		$course_id = $commentdata->comment_post_ID;
		$average_ratings = dtlms_get_average_comment_ratings($course_id);
		update_post_meta($course_id, 'average-ratings', $average_ratings);

	}

	add_action( 'delete_comment', 'dtlms_delete_comment', 10, 1 );
	add_action( 'trash_comment', 'dtlms_delete_comment', 10, 1 );

}

// Modifying comments tempalte
function dtlms_comment_template( $comment_template ) {
     global $post;
     if ( !( is_singular() && ( have_comments() || 'open' == $post->comment_status ) ) ) {
        return;
     }
     
     return dirname(__FILE__) . '/comments.php';;
}
add_filter('comments_template', 'dtlms_comment_template');



if(!function_exists('dtlms_privacy_comment_checkbox_default')) {
	function dtlms_privacy_comment_checkbox_default( $comment_field ) {

		$cookie_field = '';
		if(!empty($comment_field['cookies'])) {
			$cookie_field = $comment_field['cookies'];
			unset($comment_field['cookies']);
		}
		unset($comment_field['url']);

		$comment_field['author'] = '<div class="dtlms-column dtlms-one-third first">
										<p>
											<label for="author">'.esc_html__( 'Your Name', 'dtlms-lite' ).'</label>
											<input id="author" name="author" type="text" required />
										</p>
									</div>';

		$comment_field['email'] = '<div class="dtlms-column dtlms-one-third">
										<p>
											<label for="email">'.esc_html__( 'Your Email', 'dtlms-lite' ).'</label>
											<input id="email" name="email" type="text" required />
										</p>
									</div>';

		$comment_field['phone'] = '<div class="dtlms-column dtlms-one-third">
										<p>
											<label for="phone">'.esc_html__( 'Your Phone', 'dtlms-lite' ).'</label>
											<input id="phone" name="phone" type="text" required />
										</p>
									</div>';


		$comment_field['lms_title'] = '<div class="dtlms-column dtlms-one-column first">
											<p>
												<label for="lms_title">'.esc_html__( 'Title', 'dtlms-lite' ).'</label>
												<input id="lms_title" name="lms_title" type="text" />
											</p>
										</div>';
		$comment_field['lms_rating'] = '<div class="dtlms-column dtlms-one-column first ratings-holder">
											<p>
												<label for="lms_rating">'.esc_html__( 'Ratings', 'dtlms-lite' ).'</label>
												<div class="ratings">'.dtlms_comment_rating_display(0).'</div>
												<input id="lms_rating" name="lms_rating" type="hidden" />
											</p>
										</div>';


		if($cookie_field != '') {
			$comment_field['cookies'] =  $cookie_field;
		}

		return $comment_field;

	}
	add_filter( 'comment_form_default_fields', 'dtlms_privacy_comment_checkbox_default'  );
}
var dtLMSBackendUtils = {

	dtLMSCompletionLockDripFeedSwitch : function() {

		jQuery('body').delegate('#drip-completionlock-switch', 'change', function(e){

			var drip_completionlock = jQuery(this).val();

			jQuery('.dtlms-completionlock-holder').slideUp();
			jQuery('.dtlms-dripfeed-holder').slideUp();

			if(drip_completionlock == 'completionlock') {

				jQuery('.dtlms-completionlock-holder').slideDown();
				jQuery('.dtlms-dripfeed-holder').slideUp();

			} else if(drip_completionlock == 'dripfeed') {

				jQuery('.dtlms-completionlock-holder').slideUp();
				jQuery('.dtlms-dripfeed-holder').slideDown();

			}

		});

	},

	dtLMSSidebarContentSwitch : function() {

		jQuery('body').delegate('#sidebar-content-type', 'change', function(e){

			var sidebar_content_type = jQuery(this).val();

			if(sidebar_content_type == 'page') {

				jQuery('.dtlms-sidebar-content-page-holder').slideDown();
				jQuery('.dtlms-sidebar-content-textarea-holder').slideUp();

			} else {

				jQuery('.dtlms-sidebar-content-page-holder').slideUp();
				jQuery('.dtlms-sidebar-content-textarea-holder').slideDown();

			}

		});

	},

	dtLMSClassTabsContentSwitch : function() {

		jQuery('body').delegate('#dtlms-class-tabs-content-type', 'change', function(e){

			var tabs_content_type = jQuery(this).val();

			if(tabs_content_type == 'page') {

				jQuery(this).parents('.dtlms-tab-box').find('.dtlms-class-tabs-content-page-holder').slideDown();
				jQuery(this).parents('.dtlms-tab-box').find('.dtlms-class-tabs-content-textarea-holder').slideUp();

			} else {

				jQuery(this).parents('.dtlms-tab-box').find('.dtlms-class-tabs-content-page-holder').slideUp();
				jQuery(this).parents('.dtlms-tab-box').find('.dtlms-class-tabs-content-textarea-holder').slideDown();

			}

		});

	},

};

var dtLMSBackend = {

	dtInit : function() {
		dtLMSBackend.dtMediaUploader();
		dtLMSBackend.dtLMS();
		dtLMSBackend.dtGradings();
		dtLMSBackend.dtUsers();
		dtLMSBackend.dtSettings();
		dtLMSBackend.dtStatistics();
	},

	dtMediaUploader : function() {

		jQuery('body').delegate('.dtlms-upload-media-item-button', 'click', function(e){

			var file_frame = null;
			var item_clicked = jQuery(this);
			var multiple = false;
			var button_text = "Insert Image";

			var media_type = '';
			if(jQuery(this).attr('data-mediatype') && (jQuery(this).attr('data-mediatype') == 'image')) {
				media_type = 'image';
			}

			if(item_clicked.hasClass('multiple')) {
				multiple = true;
				button_text = "Insert Image(s)";
			}

		    file_frame = wp.media.frames.file_frame = wp.media({
		    	multiple: multiple,
		    	title : "Upload / Select Media",
				library : {
					type : media_type,
				},
		    	button :{
		    		text : button_text
		    	}
		    });

		    // When an image is selected, run a callback.
		    file_frame.on( 'select', function() {

		    	var attachments = file_frame.state().get('selection').toJSON();

		    	if(item_clicked.hasClass('multiple')) {

			        var items = '';
			        jQuery.each( attachments, function(key, value) {

				        var id = value.id;
				        var url = value.url;
				        var title = value.title;

			        	items += '<li>'+
                                    '<input name="media-attachment-urls[]" type="text" class="uploadfieldurl large" readonly value="'+url+'" />'+
                                    '<input name="media-attachment-ids[]" type="hidden" class="uploadfieldid hidden" readonly value="'+id+'" />'+
                                    '<input name="media-attachment-titles[]" type="text" class="media-attachment-titles" placeholder="'+lmsbackendobject.attachmentTitle+'" value="'+title+'" />'+
                                    '<input name="media-attachment-icons[]" type="text" class="media-attachment-icons" placeholder="'+lmsbackendobject.attachmentIcon+'" value="" />'+
                                    '<span class="dtlms-remove-media-item"><span class="fas fa-times"></span></span>'+
                                '</li>';

					});

					item_clicked.parents('.dtlms-upload-media-items-container').find('.dtlms-upload-media-items').append(items);

		    	} else {

			        var id = attachments[0].id;
			        var url = attachments[0].url;

			        item_clicked.parents('.dtlms-upload-media-items-container').find('.uploadfieldurl').val(url);
			        item_clicked.parents('.dtlms-upload-media-items-container').find('.uploadfieldid').val(id);

			        if(item_clicked.hasClass('show-preview')) {
			        	item_clicked.parents('.dtlms-upload-media-items-container').find('.dtlms-image-preview-tooltip img').attr('src', url);
			        }

			    }

		    });

		    // Finally, open the modal
		    file_frame.open();

		});

		jQuery('body').delegate('.dtlms-upload-media-item-reset', 'click', function(e) {

			var item_clicked = jQuery(this);

			if(item_clicked.parents('.dtlms-upload-media-items-container').find('.dtlms-upload-media-item-button').hasClass('multiple')) {

				item_clicked.parents('.dtlms-upload-media-items-container').find('.dtlms-upload-media-items').html('');

			} else {

		        item_clicked.parents('.dtlms-upload-media-items-container').find('.uploadfieldurl').val('');
		        item_clicked.parents('.dtlms-upload-media-items-container').find('.uploadfieldid').val('');

		        if(item_clicked.parents('.dtlms-upload-media-items-container').find('.dtlms-upload-media-item-button').hasClass('show-preview')) {
					var $noimage = item_clicked.parents('.dtlms-upload-media-items-container').find('.dtlms-image-preview-tooltip img').attr('data-default');
					item_clicked.parents('.dtlms-upload-media-items-container').find('.dtlms-image-preview-tooltip img').attr('src', $noimage);
				}

			}

			e.preventDefault();

		});

		jQuery('body').delegate('.dtlms-remove-media-item', 'click', function(e) {

			jQuery(this).parents('li').remove();
			e.preventDefault();

		});

		jQuery('.dtlms-upload-media-items').sortable({ placeholder: 'sortable-placeholder' });

	},

	dtLMS : function() {

		// Initaialize color picker
		if(jQuery('.dtlms-color-field').length) {
			jQuery('.dtlms-color-field').wpColorPicker();
		}

		// Checkbox switch
		dtLMSCommonUtils.dtLMSCheckboxSwitch();

		// Completion Lock & Drip Feed switch
		dtLMSBackendUtils.dtLMSCompletionLockDripFeedSwitch();

		// Sidebar Content switch
		dtLMSBackendUtils.dtLMSSidebarContentSwitch();

		// Class Tabs Content switch
		dtLMSBackendUtils.dtLMSClassTabsContentSwitch();

		// Tabs
		dtLMSCommonUtils.dtLMSContentTabs();


		// Add tabs
		jQuery('a.dtlms-add-curriculum').click(function(e){

			if(jQuery(this).hasClass('section')) {
				var clone = jQuery("#dtlms-curriculum-section-to-clone").clone();
			}

			if(jQuery(this).hasClass('lesson')) {
				var clone = jQuery("#dtlms-curriculum-lesson-to-clone").clone();
			}

			if(jQuery(this).hasClass('quiz')) {
				var clone = jQuery("#dtlms-curriculum-quiz-to-clone").clone();
			}

			if(jQuery(this).hasClass('assignment')) {
				var clone = jQuery("#dtlms-curriculum-assignment-to-clone").clone();
			}

			clone.attr('id', 'dtlms-curriculum-section-item').removeClass('hidden');

			if(jQuery(this).attr('data-curriculumtype') == 'lesson') {
				clone.find('select').attr('id', 'lesson-curriculum').attr('name', 'lesson-curriculum[]').addClass('curriculum-chosen');
				clone.find('input').attr('id', 'lesson-curriculum').attr('name', 'lesson-curriculum[]');
			} else {
				clone.find('select').attr('id', 'course-curriculum').attr('name', 'course-curriculum[]').addClass('curriculum-chosen');
				clone.find('input').attr('id', 'course-curriculum').attr('name', 'course-curriculum[]');
			}

			clone.appendTo('#dtlms-curriculum-items-container');

			jQuery('.curriculum-chosen').chosen();

			e.preventDefault();

		});

		jQuery('body').delegate('span.dtlms-remove-curriculum-item','click', function(e){

			jQuery(this).parents('#dtlms-curriculum-section-item').remove();
			e.preventDefault();

		});

		jQuery("#dtlms-curriculum-items-container").sortable({ placeholder: 'sortable-placeholder' });

		// Comment Ratings
		jQuery('.ratings span').mouseover(function(e) {
			if(!jQuery(this).parents('.ratings').hasClass('rated')) {
				jQuery('.ratings span').removeClass('icon-moon icon-moon-star-full');
				jQuery( this ).prevAll( 'span' ).andSelf().addClass('icon-moon icon-moon-star-full');
				jQuery( this ).nextAll( 'span' ).addClass('icon-moon icon-moon-star-empty');
			} else {
				setTimeout(function() { jQuery('.ratings').removeClass('rated'); },100);
			}
			e.preventDefault();
		}).mouseout(function(e) {
			if(!jQuery(this).parents('.ratings').hasClass('rated')) {
				jQuery('.ratings span').removeClass('icon-moon icon-moon-star-full');
				jQuery( this ).prevAll( 'span' ).andSelf().addClass('icon-moon icon-moon-star-full');
				jQuery( this ).nextAll( 'span' ).addClass('icon-moon icon-moon-star-empty');
			} else {
				setTimeout(function() { jQuery('.ratings').removeClass('rated'); },100);
			}
			e.preventDefault();
		});

		jQuery('.ratings span').click(function(e) {
			if(!jQuery(this).parents('.ratings').hasClass('rated')) {
				jQuery(this).prevAll('span').andSelf().addClass('icon-moon icon-moon-star-full');
				jQuery(this).parents('.ratings-holder').find('#lms_rating').val(parseInt(jQuery(this).attr('data-value'), 10));
				jQuery(this).parents('.ratings').addClass('rated');
			}
			e.preventDefault();
		});

	},

	dtGradings : function() {

		jQuery('body').delegate('#dtlms-marks-obtained', 'change', function(){

			 var user_mark = jQuery(this).val();
			 var max_mark = jQuery('#dtlms-maximum-marks').val();

			 var percentage = (parseInt(user_mark)/parseInt(max_mark))*100;
			 if(isNaN(percentage)) {
			 	percentage = 0;
			 }
			 percentage = +percentage.toFixed(2);

			 jQuery('#dtlms-marks-obtained-percentage').val(percentage);

		});

		jQuery( 'body' ).delegate( '.dtlms-revoke-user-submission', 'click', function(e){

			var this_item = jQuery(this),
				class_id = this_item.attr('data-classid'),
				course_id = this_item.attr('data-courseid'),
				user_id = this_item.attr('data-userid'),
				item_type = this_item.attr('data-itemtype');

			if(this_item.hasClass('disabled')) {
				alert(lmsbackendobject.revokeUserSubmissionWarning);
				return false;
			}

			jQuery.ajax({
				type: "POST",
				url: lmsbackendobject.ajaxurl,
				data:
				{
					action: 'dtlms_revoke_user_submission',
					class_id: class_id,
					course_id: course_id,
					user_id: user_id,
					item_type: item_type,
				},
				beforeSend: function(){
					this_item.prepend( '<span><i class="fas fa-spinner fa-spin"></i></span>' );
				},
				success: function (response) {
					alert(lmsbackendobject.revokeUserSubmission);
					location.reload();
				},
				complete: function(){
					this_item.find('span').remove();
				}
			});

			e.preventDefault();

		});

		// Warning on grading parent post trash
	    jQuery('.post-type-dtlms_gradings.edit-php .trash a.submitdelete').click( function( e ) {
	        if( ! confirm( lmsbackendobject.gradingWarningTrash ) ) {
	            e.preventDefault();
	        }
	    });

	    // Warning on grading parent post delete
	    jQuery('.post-type-dtlms_gradings.edit-php .delete a.submitdelete').click( function( e ) {
	        if( ! confirm( lmsbackendobject.gradingWarningDelete ) ) {
	            e.preventDefault();
	        }
	    });

	},

	dtUsers : function() {

		// Adding tabs
		jQuery("a.dtlms-add-user-social").click(function(e){

			console.log('jjjjjjj');

			var clone = jQuery("#dtlms-user-section-to-clone").clone();

			clone.attr('id', 'dtlms-user-section-item').removeClass('hidden');
			clone.find('select').attr('id', 'user-social-items').attr('name', 'user-social-items[]').addClass('social-item-chosen');
			clone.find('input').attr('id', 'user-social-items-value').attr('name', 'user-social-items-value[]');
			clone.appendTo('#dtlms-user-details-container');

			jQuery('.social-item-chosen').chosen();

			e.preventDefault();

		});

		jQuery('body').delegate('span.dtlms-remove-user-tab','click', function(e){

			jQuery(this).parents('#dtlms-user-section-item').remove();
			e.preventDefault();

		});

		jQuery("#dtlms-user-details-container").sortable({ placeholder: 'sortable-placeholder' });

	},

	dtSettings : function() {

		jQuery( 'body' ).delegate( '.dtlms-setcom-instructor', 'change', function(e){

			var instructor_id = jQuery(this).val();

			if(instructor_id != '') {

				jQuery.ajax({
					type: "POST",
					url: lmsbackendobject.ajaxurl,
					data:
					{
						action: 'dtlms_setcom_load_instructor_courses',
						instructor_id: instructor_id,
					},
					beforeSend: function(){
						dtLMSCommonUtils.dtLMSAjaxBeforeSend();
					},
					success: function (response) {
						jQuery('.dtlms-setcommission-container').html(response);
					},
					complete: function(){
						dtLMSCommonUtils.dtLMSAjaxAfterSend();
					}
				});

			} else {

				jQuery('.dtlms-setcommission-container').html(lmsbackendobject.selectInstructor);

			}

			e.preventDefault();

		});

		jQuery( 'body' ).delegate( '.dtlms-save-commission-settings', 'click', function(e) {

			var this_item = jQuery(this),
				instructor_id = this_item.attr('data-instructorid');

	        var form = jQuery('.formSetCommission')[0];
	        var data = new FormData(form);
	        data.append('action', 'dtlms_save_commission_settings');
	        data.append('instructor_id', instructor_id);;

			jQuery.ajax({
				type: "POST",
				url: lmsbackendobject.ajaxurl,
				data: data,
	            processData: false,
	            contentType: false,
	            cache: false,
				beforeSend: function(){
					this_item.prepend( '<span><i class="fas fa-spinner fa-spin"></i></span>' );
				},
				success: function (response) {
					jQuery('.dtlms-commission-settings-response-holder').html(response);
				},
				complete: function(){
					this_item.find('span').remove();
				}
			});

			e.preventDefault();

		});

		jQuery( 'body' ).delegate( '.dtlms-save-options-settings', 'click', function(e) {

			var this_item = jQuery(this),
				settings = this_item.attr('data-settings');

	        var form = jQuery('.formOptionSettings')[0];
	        var data = new FormData(form);
	        data.append('action', 'dtlms_save_options_settings');
	        data.append('settings', settings);

			jQuery.ajax({
				type: "POST",
				url: lmsbackendobject.ajaxurl,
				data: data,
	            processData: false,
	            contentType: false,
	            cache: false,
				beforeSend: function(){
					this_item.prepend( '<span><i class="fas fa-spinner fa-spin"></i></span>' );
				},
				success: function (response) {
					this_item.parents('.formOptionSettings').find('.dtlms-option-settings-response-holder').html(response);
					this_item.parents('.formOptionSettings').find('.dtlms-option-settings-response-holder').show();
					window.setTimeout(function(){
						this_item.parents('.formOptionSettings').find('.dtlms-option-settings-response-holder').fadeOut('slow');
					}, 2000);
				},
				complete: function(){
					this_item.find('span').remove();
				}
			});

			e.preventDefault();

		});

		jQuery( 'body' ).delegate( '.dtlms-load-paycom-datas', 'click', function(e) {

			var this_item = jQuery(this)
				instructor_id = this_item.parents('.dtlms-settings-pay-commission-container').find('.dtlms-paycom-instructor').val(),
				startdate = this_item.parents('.dtlms-settings-pay-commission-container').find('.dtlms-paycom-startdate').val(),
				enddate = this_item.parents('.dtlms-settings-pay-commission-container').find('.dtlms-paycom-enddate').val();

			jQuery.ajax({
				type: "POST",
				url: lmsbackendobject.ajaxurl,
				data:
				{
					action: 'dtlms_load_paycom_datas',
					instructor_id: instructor_id,
					startdate: startdate,
					enddate: enddate,
				},
				beforeSend: function(){
					this_item.prepend( '<span><i class="fas fa-spinner fa-spin"></i></span>' );
				},
				success: function (response) {
					jQuery('.dtlms-paycommission-container').html(response);
					dtLMSCommonUtils.dtLMSCheckboxSwitch();
				},
				complete: function(){
					this_item.find('span').remove();
				}
			});

			e.preventDefault();

		});

		jQuery( 'body' ).delegate( '.other-amounts', 'change', function(e) {

			var other_amounts = jQuery(this).val();
			other_amounts = (other_amounts != '') ? other_amounts : 0;
			var totalcommissions = jQuery(this).attr('data-totalcommissions');
			totalcommissions = (totalcommissions != '') ? totalcommissions : 0;

			var total_commission_topay = parseFloat(totalcommissions) + parseFloat(other_amounts);

			jQuery('.total-commission-topay').val(total_commission_topay);

			e.preventDefault();

		});

		jQuery( 'body' ).delegate( '.dtlms-pay-commission-via-paypal', 'click', function(e) {

			var instructor_paypal_email = jQuery('.instructor-paypal-email').val();
			jQuery('#dtlmsPaypalForm').find('.emailid').val(instructor_paypal_email);

			jQuery('#dtlmsPaypalForm').submit();

			e.preventDefault();

		});

		jQuery( 'body' ).delegate( '.dtlms-commission-markaspaid', 'click', function(e) {

			var this_item = jQuery(this),
				instructor_id = this_item.attr('data-instructorid'),
				start_date = this_item.attr('data-startdate'),
				end_date = this_item.attr('data-enddate'),
				selected_courses = this_item.attr('data-selectedcourses'),
				selected_classes = this_item.attr('data-selectedclasses'),
				instructor_paypal_email = jQuery('.instructor-paypal-email').val(),
				other_amounts = jQuery('.other-amounts').val(),
				course_commission_paid = jQuery('.course-commission-topay').val(),
				class_commission_paid = jQuery('.class-commission-topay').val(),
				total_commission_paid = jQuery('.total-commission-topay').val(),
				overall_commission_details = this_item.attr('data-overallcommissiondetails');

			if(parseInt(total_commission_paid, 10) > 0 && !this_item.hasClass('disabled')) {

				this_item.addClass('disabled');

				jQuery.ajax({
					type: "POST",
					url: lmsbackendobject.ajaxurl,
					data:
					{
						action: 'dtlms_paycommission_markaspaid',
						instructor_id: instructor_id,
						start_date: start_date,
						end_date: end_date,
						selected_courses: selected_courses,
						selected_classes: selected_classes,
						instructor_paypal_email: instructor_paypal_email,
						course_commission_paid: course_commission_paid,
						class_commission_paid: class_commission_paid,
						total_commission_paid: total_commission_paid,
						overall_commission_details: overall_commission_details,
						other_amounts: other_amounts,
					},
					beforeSend: function(){
						this_item.prepend( '<span><i class="fas fa-spinner fa-spin"></i></span>' );
					},
					success: function (response) {
						window.location.replace(window.location.href + "&paycommission=success");
						this_item.removeClass('disabled');
					},
					complete: function(){
						this_item.find('span').remove();
					}
				});

			}

			e.preventDefault();

		});

		// Assigning

		jQuery( 'body' ).delegate( '.dtlms-assigning-students', 'change', function(e){

			var course_id = jQuery(this).val();

			//if(course_id != '') {

				jQuery.ajax({
					type: "POST",
					url: lmsbackendobject.ajaxurl,
					data:
					{
						action: 'dtlms_assigning_load_students_data',
						course_id: course_id,
					},
					beforeSend: function(){
						dtLMSCommonUtils.dtLMSAjaxBeforeSend(undefined);
					},
					success: function (response) {
						jQuery('.dtlms-assign-studentstocourse-container').html(response);
						dtLMSCommonUtils.dtLMSCheckboxSwitch();
					},
					complete: function(){
						dtLMSCommonUtils.dtLMSAjaxAfterSend(undefined);
					}
				});

			//}

			e.preventDefault();

		});

		jQuery( 'body' ).delegate( '.dtlms-save-assign-students-settings', 'click', function(e) {

			var this_item = jQuery(this),
				page_student_ids = this_item.attr('data-pagestudentids'),
				course_id = this_item.attr('data-courseid');

			var student_ids = jQuery('.assign-students-to-course:checked').map(function(){
				return this.value;
			}).get();

			jQuery.ajax({
				type: "POST",
				url: lmsbackendobject.ajaxurl,
				data:
				{
					action: 'dtlms_save_assign_students_settings',
					course_id: course_id,
					student_ids: student_ids,
					page_student_ids: page_student_ids,
				},
				beforeSend: function(){
					this_item.prepend( '<span><i class="fas fa-spinner fa-spin"></i></span>' );
				},
				success: function (response) {
					jQuery('.dtlms-assign-students-response-holder').html(response);
					window.setTimeout(function(){
						jQuery('.dtlms-assign-students-response-holder').fadeOut('slow');
					}, 2000);
				},
				complete: function(){
					this_item.find('span').remove();
				}
			});

			e.preventDefault();

		});

		jQuery( 'body' ).delegate( '.dtlms-assigning-courses', 'change', function(e){

			var student_id = jQuery(this).val();

			jQuery.ajax({
				type: "POST",
				url: lmsbackendobject.ajaxurl,
				data:
				{
					action: 'dtlms_assigning_load_courses_data',
					student_id: student_id,
				},
				beforeSend: function(){
					dtLMSCommonUtils.dtLMSAjaxBeforeSend(undefined);
				},
				success: function (response) {
					jQuery('.dtlms-assign-coursestostudent-container').html(response);
					dtLMSCommonUtils.dtLMSCheckboxSwitch();
				},
				complete: function(){
					dtLMSCommonUtils.dtLMSAjaxAfterSend(undefined);
				}
			});

			e.preventDefault();

		});

		jQuery( 'body' ).delegate( '.dtlms-save-assign-courses-settings', 'click', function(e) {

			var this_item = jQuery(this),
				student_id = this_item.attr('data-studentid'),
				page_course_ids = this_item.attr('data-pagecourseids');

			var course_ids = jQuery('.assign-courses-to-student:checked').map(function(){
				return this.value;
			}).get();

			jQuery.ajax({
				type: "POST",
				url: lmsbackendobject.ajaxurl,
				data:
				{
					action: 'dtlms_save_assign_courses_settings',
					student_id: student_id,
					course_ids: course_ids,
					page_course_ids: page_course_ids
				},
				beforeSend: function(){
					this_item.prepend( '<span><i class="fas fa-spinner fa-spin"></i></span>' );
				},
				success: function (response) {
					jQuery('.dtlms-assign-courses-response-holder').html(response);
					window.setTimeout(function(){
						jQuery('.dtlms-assign-courses-response-holder').fadeOut('slow');
					}, 2000);
				},
				complete: function(){
					this_item.find('span').remove();
				}
			});

			e.preventDefault();

		});


		// POC

		jQuery( 'body' ).delegate( '.dtlms-save-poc-settings', 'click', function(e) {

			var this_item = jQuery(this);

	        var form = jQuery('.formPocSettings')[0];
	        var data = new FormData(form);
	        data.append('action', 'dtlms_save_poc_settings');

			jQuery.ajax({
				type: "POST",
				url: lmsbackendobject.ajaxurl,
				data: data,
	            processData: false,
	            contentType: false,
	            cache: false,
				beforeSend: function(){
					this_item.prepend( '<span><i class="fas fa-spinner fa-spin"></i></span>' );
				},
				success: function (response) {
					this_item.parents('.formPocSettings').find('.dtlms-poc-settings-response-holder').html(response);
					window.setTimeout(function(){
						this_item.parents('.formPocSettings').find('.dtlms-poc-settings-response-holder').fadeOut('slow');
					}, 2000);
				},
				complete: function(){
					this_item.find('span').remove();
				}
			});

			e.preventDefault();

		});

		// Skin

		jQuery( 'body' ).delegate( '.dtlms-save-skin-settings', 'click', function(e) {

			var this_item = jQuery(this);

	        var form = jQuery('.formSkinSettings')[0];
	        var data = new FormData(form);
	        data.append('action', 'dtlms_save_skin_settings');

			jQuery.ajax({
				type: "POST",
				url: lmsbackendobject.ajaxurl,
				data: data,
	            processData: false,
	            contentType: false,
	            cache: false,
				beforeSend: function(){
					this_item.prepend( '<span><i class="fas fa-spinner fa-spin"></i></span>' );
				},
				success: function (response) {
					this_item.parents('.formSkinSettings').find('.dtlms-skin-settings-response-holder').html(response);
					window.setTimeout(function(){
						this_item.parents('.formSkinSettings').find('.dtlms-skin-settings-response-holder').fadeOut('slow');
					}, 2000);
				},
				complete: function(){
					this_item.find('span').remove();
				}
			});

			e.preventDefault();

		});

	},

	dtStatistics : function() {

	}

};

jQuery(document).ready(function() {

	dtLMSBackend.dtInit();

});
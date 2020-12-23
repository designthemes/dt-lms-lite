<div class="dtlms-custom-box">

	<div class="dtlms-column dtlms-one-sixth first">
		<label><?php esc_html_e('Attachments','dtlms-lite');?> </label>
	</div>
	<div class="dtlms-column dtlms-five-sixth">

        <div class="dtlms-upload-media-items-container">

            <div class="dtlms-upload-media-items-holder">
                <ul class="dtlms-upload-media-items">
                    <?php
                    $media_attachments_urls   = get_post_meta($post_id, 'media-attachment-urls', true);
                    $media_attachments_ids    = get_post_meta($post_id, 'media-attachment-ids', true);
                    $media_attachments_titles = get_post_meta($post_id, 'media-attachment-titles', true);
                    $media_attachments_icons  = get_post_meta($post_id, 'media-attachment-icons', true);

                    if(isset($media_attachments_urls) && !empty($media_attachments_urls)) {
                        $i = 0;
                        foreach($media_attachments_urls as $media_attachments_url) {
                            if($media_attachments_url != '') {
                                $media_title = '';
                                if(isset($media_attachments_titles[$i])) {
                                    $media_title = $media_attachments_titles[$i];
                                }
                                $media_icon = '';
                                if(isset($media_attachments_icons[$i])) {
                                    $media_icon = $media_attachments_icons[$i];
                                }
                                ?>
                                <li>
                                    <input name="media-attachment-urls[]" type="text" class="uploadfield" readonly value="<?php echo esc_attr( $media_attachments_url ); ?>"/>
                                    <input name="media-attachment-ids[]" type="hidden" class="uploadfieldid hidden" readonly value="<?php echo esc_attr( $media_attachments_ids[$i] ); ?>"/>
                                    <input name="media-attachment-titles[]" type="text" class="media-attachment-titles" value="<?php echo esc_attr( $media_title ); ?>" placeholder="<?php echo esc_attr__('Attachment Title', 'dtlms-lite'); ?>" />
                                    <input name="media-attachment-icons[]" type="text" class="media-attachment-icons" value="<?php echo esc_attr( $media_icon ); ?>" placeholder="<?php echo esc_attr__('Attachment Icon', 'dtlms-lite'); ?>" />
                                    <span class="dtlms-remove-media-item"><span class="fas fa-times"></span></span>
                                </li>
                                <?php
                                $i++;
                            }
                        }
                    }
                    ?>

                </ul>
            </div>

            <input type="button" value="<?php esc_attr_e('Upload Attachments','dtlms-lite');?>" class="dtlms-upload-media-item-button multiple" />
            <input type="button" value="<?php esc_attr_e('Remove Attachments','dtlms-lite');?>" class="dtlms-upload-media-item-reset" />

        </div>

		<p class="dtlms-note"> <?php esc_html_e("You can add any number of media attachments for this course.",'dtlms-lite');?> </p>
	</div>

</div>
<div class="dtlms-custom-box">

    <!-- Capacity -->
    <div class="dtlms-column dtlms-one-half first">

        <div class="dtlms-column dtlms-one-third first">
           <label><?php esc_html_e('Capacity', 'dtlms-lite'); ?></label>
        </div>
        <div class="dtlms-column dtlms-two-third">
            <?php $capacity = get_post_meta ( $post_id, 'capacity', true ); ?>
            <input type="number" id="capacity" name="capacity" value="<?php echo esc_attr( $capacity ); ?>"  min="1" max="100" />
            <p class="dtlms-note"> <?php esc_html_e('If you wish you can specify course capacity here.','dtlms-lite');?> </p>
        </div>

    </div>
    <!-- Capacity End -->

    <!-- Disable Purchases -->
    <div class="dtlms-column dtlms-one-half">

        <div class="dtlms-column dtlms-one-third first"><?php esc_html_e( 'Disable Purchases Over Capacity', 'dtlms-lite');?></div>
        <div class="dtlms-column dtlms-two-third"><?php
            $current     = get_post_meta($post_id, 'disable-purchases-over-capacity', true);
            $switchclass = ( $current === "true") ? 'checkbox-switch-on' :'checkbox-switch-off';
            $checked     = ( $current === "true") ? ' checked="checked" ' : '';?>
            <div data-for="disable-purchases-over-capacity" class="dtlms-checkbox-switch <?php echo esc_attr( $switchclass ); ?>"></div>
            <input id="disable-purchases-over-capacity" class="hidden" type="checkbox" name="disable-purchases-over-capacity" value="true" <?php echo $checked; ?> />
            <p class="dtlms-note"> <?php esc_html_e('Disable purchases if course capacity is reached.', 'dtlms-lite');?> </p>
        </div>

    </div>
    <!-- Disable Purchases End -->

</div>
<?php
require_once DTLMS_PLUGIN_PATH . 'settings/settings-utils.php';
require_once DTLMS_PLUGIN_PATH . 'settings/settings-assigning-utils.php';
require_once DTLMS_PLUGIN_PATH . 'settings/settings-poc-utils.php';

function dtlms_settings_options($current) {

	$current_user = wp_get_current_user();
	$current_user_id = $current_user->ID;

	if ( in_array( 'administrator', (array) $current_user->roles ) ) {

		$tabs = array (
			'general'        => array (
				'label' => esc_html__('General', 'dtlms-lite'),
				'path'  => DTLMS_PLUGIN_PATH . 'settings/settings-general.php'
			),
			'assigning'      => array (
				'label' => esc_html__('Assigning', 'dtlms-lite'),
				'path'  => DTLMS_PLUGIN_PATH . 'settings/settings-assigning.php'
			),
			'pointofcontact' => array (
				'label' => esc_html__('Point Of Contact', 'dtlms-lite'),
				'path'  => DTLMS_PLUGIN_PATH . 'settings/settings-poc.php'
			),
			'skin'           => array (
				'label' => esc_html__('Skin', 'dtlms-lite'),
				'path'  => DTLMS_PLUGIN_PATH . 'settings/settings-skin.php'
			),
			'typography'     => array (
				'label' => esc_html__('Typography', 'dtlms-lite'),
				'path'  => DTLMS_PLUGIN_PATH . 'settings/settings-typography.php'
			)
		);

   	} else if ( in_array( 'instructor', (array) $current_user->roles ) ) {

		$tabs = array ();

		if('true' ==  dtlms_option('general','allow-instructor-setcommission')) {
			$tabs['set_commission'] = array (
				'label' => esc_html__('Set Commission', 'dtlms-lite'),
				'path'  => DTLMS_PLUGIN_PATH . 'settings/settings-instructor-set-commission.php'
			);
		}

		$tabs['import'] = array (
			'label' => esc_html__('Import', 'dtlms-lite'),
			'path'  => DTLMS_PLUGIN_PATH . 'settings/settings-import.php'
		);

   	}

	$tabs = apply_filters( 'dtlms_settings', $tabs );

	if ( in_array( 'administrator', (array) $current_user->roles ) ) {
		$current = isset( $_GET['parenttab'] ) ? sanitize_text_field( $_GET['parenttab'] ) : 'general';
	} else if ( in_array( 'instructor', (array) $current_user->roles ) ) {
	    if('true' ==  dtlms_option('general','allow-instructor-setcommission')) {
	    	$current = isset( $_GET['parenttab'] ) ? sanitize_text_field( $_GET['parenttab'] ) : 'set_commission';
	    } else {
	    	$current = isset( $_GET['parenttab'] ) ? sanitize_text_field( $_GET['parenttab'] ) : 'import';
	    }
   	}

	dtlms_get_settings_submenus($current, $tabs);
	dtlms_get_settings_tab($current, $tabs);

}

function dtlms_get_settings_submenus($current, $tabs) {

    echo '<h2 class="dtlms-custom-nav nav-tab-wrapper">';
		foreach( $tabs as $key => $tab ) {
			$class = ( $key == $current ) ? 'nav-tab-active' : '';
			echo '<a class="nav-tab '.esc_attr( $class ).'" href="?page=dtlms-settings-options&parenttab='.esc_attr( $key ).'">'.esc_html( $tab['label'] ).'</a>';
		}
    echo '</h2>';
}

function dtlms_get_settings_tab($current, $tabs) {
	require_once $tabs[$current]['path'];
}
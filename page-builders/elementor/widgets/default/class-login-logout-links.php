<?php
namespace DTElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class DTLMSDfLoginLogoutLinks extends Widget_Base {

	public function get_categories() {
		return [ 'dtlms-default-widgets' ];
	}

	public function get_name() {
		return 'dtlms-widget-default-login-logout-links';
	}

	public function get_title() {
		return esc_html__( 'Login / Logout Links', 'dtlms-lite' );
	}

	public function get_style_depends() {
		return array ( '' );
	}

	public function get_script_depends() {
		return array ( '' );
	}

    protected function _register_controls() {
		$this->start_controls_section( 'default-login-logout-links-section', array(
			'label' => esc_html__( 'General', 'dtlms-lite' ),
		) );
			$this->add_control( 'show_registration', array(
				'label'   => esc_html__( 'Show Registration Link', 'dtlms-lite' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'false' => esc_html__('False', 'dtlms-lite'),
					'true'  => esc_html__('True', 'dtlms-lite'),
				),
				'description' => esc_html__( 'If you wish you can enable regsitration link here..', 'dtlms-lite' ),
				'default'     => 'true',
			) );
			$this->add_control( 'class', array(
				'label'       => esc_html__( 'Class', 'dtlms-lite' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'If you wish you can add additional class name here.', 'dtlms-lite' ),
				'default'     => ''
			) );
		$this->end_controls_section();
	}

	protected function render() {
		$settings   = $this->get_settings();
		$attributes = dtlms_elementor_instance()->dtlms_parse_shortcode_attrs( $settings );
		$output     = do_shortcode('[dtlms_login_logout_links '.$attributes.' /]');

		echo $output;
    }
}
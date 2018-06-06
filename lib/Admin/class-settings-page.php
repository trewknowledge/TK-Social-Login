<?php

namespace TK\Social_Login;

use TK\Social_Login\Admin\Settings;

class Settings_Page extends Settings {
	private static $instance;

	public function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function __construct() {
		$this->register_setting('tksl_facebook_app_id', array( 'sanitize_callback' => 'absint') );
	}

	public function render_settings_page() {
		$settings = $this->render_contents();
		$signature = apply_filters( 'tksl/admin/show_signature', true );
		include( TK_SOCIAL_LOGIN_CONFIG['plugin']['template_path'] . 'settings-page.php' );
	}

	public function init() {
		$this->register_setting_section(
			'tksl_section_general',
			_x('General Settings', '(Admin)', 'tksl')
		);

		$this->register_setting_field(
			'tksl_facebook_app_id',
			esc_html_x('Facebook APP ID', 'Admin Setting', 'tksl'),
			array( $this, 'render_facebook_app_id_field' ),
			'tksl_section_general'
		);
	}

	public function render_facebook_app_id_field() {
		$option = get_option( 'tksl_facebook_app_id', '' );
		include( TK_SOCIAL_LOGIN_CONFIG['plugin']['template_path'] . 'facebook_app_id.php' );
	}

}

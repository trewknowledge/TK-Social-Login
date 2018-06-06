<?php

namespace TK\Social_Login;

use TK\Social_Login\Plugin_Settings_Page;

class Setup_Admin {
	function __construct() {
		$this->settings_page = Settings_Page::get_instance();
		$this->setup();
	}

	protected function setup() {
		// Register the main GDPR options page
		add_action('admin_menu', array( $this, 'register_options_page' ) );
		add_action('admin_init', array( $this->settings_page, 'init' ) );
	}

	public function register_options_page() {
		$page_title = esc_html__( 'Social Login', 'tksl' );
		$capability = 'manage_options';
		$slug       = 'tk-social-login';
		$function   = array( $this->settings_page, 'render_settings_page' );

		add_options_page( $page_title, $page_title, $capability, $slug, $function );
	}
}

<?php

namespace TK\Social_Login;

use TK\Social_Login\Admin\Settings_Page;

class Setup_Admin {
	function __construct() {
		$this->settings_page = Settings_Page::get_instance();
		$this->setup();
	}

	protected function setup() {
		// Register the main options page
		add_action( 'admin_menu', array( $this, 'register_options_page' ) );
		// add_action( 'admin_init', array( $this->settings_page, 'init' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
	}

	public function register_options_page() {
		$page_title = esc_html__( 'Social Login', 'vip-social-login' );
		$capability = 'manage_options';
		$slug       = 'vip-social-login';
		$function   = array( $this->settings_page, 'render_settings_page' );

		add_options_page( $page_title, $page_title, $capability, $slug, $function );
	}

	public function enqueue() {
		wp_enqueue_style(
			VIP_SOCIAL_LOGIN_SLUG,
			VIP_SOCIAL_LOGIN_URL . 'assets/css/admin.css',
			array(),
			VIP_SOCIAL_LOGIN_VERSION,
			'all'
		);
		wp_enqueue_script(
			VIP_SOCIAL_LOGIN_SLUG,
			VIP_SOCIAL_LOGIN_URL . 'assets/js/admin.js',
			array( 'jquery', 'wp-util', 'jquery-ui-sortable' ),
			VIP_SOCIAL_LOGIN_VERSION,
			true
		);

		wp_localize_script(
			VIP_SOCIAL_LOGIN_SLUG,
			'TK',
			array(
				'i18n' => array(
					'settings_updated' => esc_html_x( 'Settings Updated', 'Admin loading indicator' , 'vip-social-login' ),
				),
			)
		);
	}
}

<?php

namespace TK\Social_Login\Admin;

class Settings_Page {
	private static $instance;

	public function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function __construct() {
		register_setting( 'vip-social-login', 'vip-social-login-providers' );
		register_setting( 'vip-social-login-facebook', 'vip-social-login_facebook_app_id', array( 'sanitize_callback' => 'absint') );
		register_setting( 'vip-social-login-facebook', 'vip-social-login_facebook_app_secret', array( 'sanitize_callback' => 'sanitize_key') );

		register_setting( 'vip-social-login-twitter', 'vip-social-login_twitter_consumer_key', array( 'sanitize_callback' => 'sanitize_text_field') );
		register_setting( 'vip-social-login-twitter', 'vip-social-login_twitter_consumer_secret', array( 'sanitize_callback' => 'sanitize_text_field') );

		add_action( 'wp_ajax_vip-social-login-update-providers', array( $this, 'update_active_providers' ) );
	}

	public function sanitizer_providers( $providers ) {
		return array_map( function( $item ) {
			return (bool) $item;
		}, $providers );
	}

	public function update_active_providers() {
		check_admin_referer( 'vip-social-login-update-providers', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) || ! isset( $_POST['provider'], $_POST['checked'] ) ) {
			wp_send_json_error();
		}

		$providers = (array) get_option( 'vip-social-login-providers', array() );

		$providers[ $_POST['provider'] ] = $_POST['checked'];

		update_option( 'vip-social-login-providers', $providers );

		wp_send_json_success();
	}

	protected function render_contents( $view, $subview = false, $tab = false ) {
		if ( $tab ) {
			include( VIP_SOCIAL_LOGIN_CONFIG['plugin']['template_path'] . 'settings/providers/' . $subview . '/header.php' );
			include( VIP_SOCIAL_LOGIN_CONFIG['plugin']['template_path'] . 'settings/providers/' . $subview . '/' . $tab . '.php' );
		} else if ( $subview ) {
			include( VIP_SOCIAL_LOGIN_CONFIG['plugin']['template_path'] . 'settings/providers/' . $subview . '.php' );
		} else {
			include( VIP_SOCIAL_LOGIN_CONFIG['plugin']['template_path'] . 'settings/' . $view . '.php' );
		}
	}

	public function render_settings_page() {
		$view = isset( $_GET['view'] ) ? esc_html( $_GET['view'] ) : 'providers';
		$subview = isset( $_GET['subview'] ) ? esc_html( $_GET['subview'] ) : '';
		$tab = isset( $_GET['tab'] ) ? esc_html( $_GET['tab'] ) : '';
		include( VIP_SOCIAL_LOGIN_CONFIG['plugin']['template_path'] . 'settings/header.php' );
		$this->render_contents( $view, $subview, $tab );
		include( VIP_SOCIAL_LOGIN_CONFIG['plugin']['template_path'] . 'settings/footer.php' );
	}

}

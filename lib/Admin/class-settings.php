<?php

namespace TK\Social_Login\Admin;

abstract class Settings {

	public function get_options_group_name() {
		return 'tksl_settings';
	}

	public function register_setting( $option_name, $args = array() ) {
		register_setting( $this->get_options_group_name(), $option_name, $args );
	}

	public function register_setting_section( $id, $title, $callback = null ) {
		\add_settings_section(
			$id,
			$title,
			$callback,
			$this->get_options_group_name()
		);
	}

	public function register_setting_field( $id, $title, $callback = null, $section = '', $args = array() ) {
		add_settings_field(
			$id,
			$title,
			$callback,
			$this->get_options_group_name(),
			$section,
			$args
		);
	}

	public function render_contents() {
		ob_start();

		do_action( "tksl/settings/before", $this );
		settings_fields( $this->get_options_group_name() );
		do_settings_sections( $this->get_options_group_name() );
		do_action( "tksl/settings/after", $this );

		$this->render_submit_button();

		return ob_get_clean();
	}

	public function render_submit_button() {
		submit_button( esc_attr_x( 'Save', 'Admin settings save button', 'tksl' ) );
	}
}

<?php
namespace TK;

class Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string    $version    The current version of this plugin.
	 */
	private $version;

	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Adds a menu page for the plugin with all it's sub pages.
	 *
	 * @since  1.0.0
	 * @author Fernando Claussen <fernandoclaussen@gmail.com>
	 */
	public function add_menu() {
		$page_title  = esc_html__( 'Social Login', 'gdpr' );
		$capability  = 'manage_options';
		$parent_slug = 'tk-social-login';
		$function    = array( $this, 'requests_page_template' );

		add_options_page( $page_title, $page_title, $capability, $slug, function() {

		} );
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since  1.0.0
	 * @author Fernando Claussen <fernandoclaussen@gmail.com>
	 */
	public function enqueue_styles() {
		error_log( plugin_dir_url( dirname( __FILE__ ) ) . 'assets/css/tksl-admin.css' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'assets/css/tksl-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since  1.0.0
	 * @author Fernando Claussen <fernandoclaussen@gmail.com>
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( dirname( dirname( __FILE__ ) ) ) . 'assets/js/tksl-admin.js', array( 'jquery', 'wp-util', 'jquery-ui-sortable' ), $this->version, false );
	}

}

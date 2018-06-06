<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://trewknowledge.com
 * @since      1.0.0
 *
 * @package    TKSL
 * @subpackage includes
 * @author     Fernando Claussen <fernandoclaussen@gmail.com>
 */

namespace TK\SocialLogin;

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    TKSL
 * @subpackage includes
 * @author     Fernando Claussen <fernandoclaussen@gmail.com>
 */
class Social_Login {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since  1.0.0
	 * @author Fernando Claussen <fernandoclaussen@gmail.com>
	 * @access protected
	 * @var    string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since  1.0.0
	 * @author Fernando Claussen <fernandoclaussen@gmail.com>
	 * @access protected
	 * @var    string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since  1.0.0
	 * @author Fernando Claussen <fernandoclaussen@gmail.com>
	 */
	public function __construct() {
		if ( defined( 'GDPR_VERSION' ) ) {
			$this->version = GDPR_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'gdpr';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) OR ( defined( 'DOING_CRON' ) && DOING_CRON ) OR ( defined( 'DOING_AJAX' ) && DOING_AJAX ) OR ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) ) {
			return;
		}
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * @since  1.0.0
	 * @author Fernando Claussen <fernandoclaussen@gmail.com>
	 * @access private
	 */
	private function load_dependencies() {
		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path(  __FILE__ ) . 'admin/class-admin.php';
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 * @since  1.0.0
	 * @author Fernando Claussen <fernandoclaussen@gmail.com>
	 * @access private
	 */
	private function set_locale() {

		load_plugin_textdomain(
			'tksl',
			false,
			plugin_dir_url( dirname( __FILE__ ) ) . 'languages/'
		);

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since  1.0.0
	 * @author Fernando Claussen <fernandoclaussen@gmail.com>
	 * @access private
	 */
	private function define_admin_hooks() {

		$plugin_admin   = new Admin( $this->get_plugin_name(), $this->get_version() );

		add_action( 'admin_enqueue_scripts', array( $plugin_admin, 'enqueue_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $plugin_admin, 'enqueue_scripts' ) );
		add_action( 'admin_menu', array( $plugin_admin, 'add_menu' ) );
		// add_action( 'admin_init', array( $plugin_admin, 'register_settings' ) );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since  1.0.0
	 * @author Fernando Claussen <fernandoclaussen@gmail.com>
	 * @access private
	 */
	private function define_public_hooks() {

		// $plugin_public   = new GDPR_Public( $this->get_plugin_name(), $this->get_version() );

		// add_action( 'wp_enqueue_scripts',                                array( $plugin_public, 'enqueue_styles' ) );
		// add_action( 'wp_enqueue_scripts',                                array( $plugin_public, 'enqueue_scripts' ) );
	}


	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since  1.0.0
	 * @author Fernando Claussen <fernandoclaussen@gmail.com>
	 * @return string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since  1.0.0
	 * @author Fernando Claussen <fernandoclaussen@gmail.com>
	 * @return string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}

<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://trewknowledge.com
 * @since             1.0.0
 * @package           VIP_Social_Login
 *
 * @wordpress-plugin
 * Plugin Name:       TK Social Login
 * Plugin URI:        https://trewknowledge.com
 * Description:       Allow users to log in using social media and link existing accounts.
 * Version:           1.0.0
 * Author:            Trew Knowledge
 * Author URI:        https://trewknowledge.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       vip-social-login
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'VIP_SOCIAL_LOGIN_VERSION', '1.0.0' );
define( 'VIP_SOCIAL_LOGIN_MIN_PHP_VERSION', '5.6' );
define( 'VIP_SOCIAL_LOGIN_MIN_WP_VERSION', '4.3' );
define( 'VIP_SOCIAL_LOGIN_SLUG', 'vip-social-login' );
define( 'VIP_SOCIAL_LOGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'VIP_SOCIAL_LOGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'VIP_SOCIAL_LOGIN_TEMPLATE_PATH', plugin_dir_path( __FILE__ ) . 'views/' );

/**
* Ensure compatible version of PHP is used
*/
if ( version_compare( phpversion(), VIP_SOCIAL_LOGIN_MIN_PHP_VERSION, '<' ) ) {
	/* translators: Minimum PHP Version */
	wp_die( sprintf( esc_html__( 'You must be using PHP %s or greater.', 'vip-social-login' ), esc_html( VIP_SOCIAL_LOGIN_MIN_PHP_VERSION ) );
}

/**
* Ensure compatible version of WordPress is used
*/
if ( version_compare( get_bloginfo( 'version' ), VIP_SOCIAL_LOGIN_MIN_WP_VERSION, '<' ) ) {
	/* translators: Minimum WP Version */
	wp_die( sprintf( esc_html__( 'You must be using WordPress %s or greater.', 'vip-social-login' ), esc_html( VIP_SOCIAL_LOGIN_MIN_WP_VERSION ) );
}

/**
 * Load dependencies
 */
$autoload = plugin_dir_path( __FILE__ ) . '/vendor/autoload.php';
if ( ! file_exists( $autoload ) ) {
	wp_die( esc_html__( 'You appear to be running a development version of this plugin. You must run composer install from the plugin dev directory.', 'vip-social-login' ) );
}
require_once $autoload;

register_activation_hook(
	__FILE__, function() {

	}
);
register_deactivation_hook(
	__FILE__, function() {

	}
);

require_once( plugin_dir_path( __FILE__ ) . '/bootstrap.php' );

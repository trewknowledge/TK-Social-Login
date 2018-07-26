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
define( 'VIP_SOCIAL_LOGIN_CONFIG', array(
	'plugin' => array(
		'slug'          => 'vip-social-login',
		'url'           => plugin_dir_url( __FILE__ ),
		'path'          => plugin_dir_path( __FILE__ ),
		'template_path' => plugin_dir_path( __FILE__ ) . 'views/',
	),
	'help'   => array(
			'url' => 'https://trewknowledge.com/vip-social-login/',
	),
));

$vip_social_login_error = function( $message, $subtitle = '', $title = '' ) {
	$title = $title ?: esc_html__( 'TK Social Login &rsaquo; Error', 'vip-social-login' );
	$message = "<h1>{$title}<br><small>{$subtitle}</small></h1><p>{$message}</p>";
	wp_die( $message, $title );
};

/**
* Ensure compatible version of PHP is used
*/
if ( version_compare( phpversion(), VIP_SOCIAL_LOGIN_MIN_PHP_VERSION, '<' ) ) {
	$vip_social_login_error(
			esc_html__( 'You must be using PHP ' . VIP_SOCIAL_LOGIN_MIN_PHP_VERSION . ' or greater.', 'vip-social-login' ),
			esc_html__( 'Invalid PHP version', 'vip-social-login' )
	);
}

/**
* Ensure compatible version of WordPress is used
*/
if ( version_compare( get_bloginfo('version'), VIP_SOCIAL_LOGIN_MIN_WP_VERSION, '<' ) ) {
	$vip_social_login_error(
			esc_html__( 'You must be using WordPress ' . VIP_SOCIAL_LOGIN_MIN_WP_VERSION .  ' or greater.', 'vip-social-login' ),
			esc_html__( 'Invalid WordPress version', 'vip-social-login' )
	);
}

/**
 * Load dependencies
 */
$autoload = __DIR__ . '/vendor/autoload.php';
if ( ! file_exists( $autoload ) ) {
	$vip_social_login_error(
		esc_html__( 'You appear to be running a development version of this plugin. You must run <code>composer install</code> from the plugin dev directory.', 'vip-social-login' ),
		esc_html__( 'Autoloader not found.', 'vip-social-login' )
	);
}
require_once $autoload;

register_activation_hook( __FILE__, function() {

} );
register_deactivation_hook( __FILE__, function() {

} );

require_once('bootstrap.php');

<?php

/**
 * Start the plugin on plugins_loaded at priority 10.
 */
add_action('plugins_loaded', function () use ( $vip_social_login_error ) {

    load_plugin_textdomain('vip-social-login', false, basename( dirname( __FILE__ ) ) . '/languages/');
    add_action( 'wp_ajax_vsl_disconnect_network', array( '\TK\Social_Login\Setup', 'disconnect_network' ) );

    // new \TK\Social_Login\Updater\Updater();
		if ( is_admin() ) {
			new \TK\Social_Login\Setup_Admin();
		} else {
			new \TK\Social_Login\Setup();
		}

}, 10);

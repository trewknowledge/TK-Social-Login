<?php

namespace TK\Social_Login;

session_start();

class Providers {

	public static function login_button( $provider ) {

		$providers = get_option( 'vip-social-login-providers', array() );

		if ( empty( $providers ) || ! in_array( $provider, array_keys( $providers ), true ) ) {
			return;
		}

		if ( 'false' === $providers[ $provider ] ) {
			return;
		}

		$button_text = $provider;
		$uid = get_user_meta( get_current_user_id(), "vip_social_login_{$provider}_uid", true );

		$parameters = array( 'vip_social_login_provider' => $provider );
		$callback_url = add_query_arg( $parameters, wp_login_url() );

		switch ( $provider ) {
			case 'facebook':
				$app_id = get_option( 'vip-social-login_facebook_app_id', '' );
				$secret = get_option( 'vip-social-login_facebook_app_secret', '' );

				if ( ! $app_id || ! $secret ) {
					return;
				}

				$fb = new \Facebook\Facebook( array(
					'app_id'                => $app_id,
					'app_secret'            => $secret,
				) );

				$helper = $fb->getRedirectLoginHelper();

				$permissions = array( 'email', 'public_profile', 'user_birthday' ); // Optional permissions
				$login_url = $helper->getLoginUrl( $callback_url, $permissions );
				if ( ! is_user_logged_in() ) {
					$button_text = get_option( 'vip-social-login_facebook_button_text', esc_html_x( 'Log in with Facebook', 'Login Button', 'vip-social-login') );
					$button_text = apply_filters( 'vip_social_login/providers/facebook/button_text', $button_text );
				}

				break;
			case 'twitter':
				$consumer_key = get_option( 'vip-social-login_twitter_consumer_key', '' );
				$consumer_secret = get_option( 'vip-social-login_twitter_consumer_secret', '' );

				if ( ! $consumer_key || ! $consumer_secret ) {
					return;
				}

				$tw = new \Abraham\TwitterOAuth\TwitterOAuth( $consumer_key, $consumer_secret );

				$request_token = $tw->oauth( 'oauth/request_token', array( 'oauth_callback' => $callback_url ) );
				$_SESSION['oauth_token'] = $request_token['oauth_token'];
				$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

				$login_url = $tw->url( 'oauth/authenticate', array( 'oauth_token' => $request_token['oauth_token'] ) );

				if ( ! is_user_logged_in() ) {
					$button_text = get_option( 'vip-social-login_twitter_button_text', esc_html_x( 'Log in with Twitter', 'Login Button', 'vip-social-login') );
					$button_text = apply_filters( 'vip_social_login/providers/twitter/button_text', $button_text );
				}

				break;
			case 'google':
				$client_id = get_option( 'vip-social-login_google_client_id', '' );
				$client_secret = get_option( 'vip-social-login_google_client_secret', '' );

				if ( ! $client_id || ! $client_secret ) {
					return;
				}

				$login_url = 'https://accounts.google.com/o/oauth2/v2/auth?scope=' . urlencode('https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/plus.me') . '&redirect_uri=' . urlencode( $callback_url ) . '&response_type=code&client_id=' . $client_id . '&access_type=online';

				if ( ! is_user_logged_in() ) {
					$button_text = get_option( 'vip-social-login_google_button_text', esc_html_x( 'Log in with Google', 'Login Button', 'vip-social-login') );
					$button_text = apply_filters( 'vip_social_login/providers/google/button_text', $button_text );
				}

				break;
		}

		echo '<a href="' . $login_url . '" class="' . ( $uid ? 'vsl-connected' : 'vsl-provider' ) . '" data-provider="' . $provider . '">' . $button_text . '</a>';
	}

	public function get_connected_networks() {
		include( VIP_SOCIAL_LOGIN_TEMPLATE_PATH . '/connected-networks.php' );
	}
}

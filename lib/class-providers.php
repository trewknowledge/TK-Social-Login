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
				$callback_url = add_query_arg( 'vip_social_login_provider', $provider, wp_login_url() );
				$login_url = $helper->getLoginUrl( $callback_url, $permissions );
				$button_text = get_option( 'vip-social-login_facebook_button_text', esc_html_x( 'Log in with Facebook', 'Login Button', 'vip-social-login') );
				$button_text = apply_filters( 'vip_social_login/providers/facebook/button_text', $button_text );

				break;
			case 'twitter':
				$consumer_key = get_option( 'vip-social-login_twitter_consumer_key', '' );
				$consumer_secret = get_option( 'vip-social-login_twitter_consumer_secret', '' );

				if ( ! $consumer_key || ! $consumer_secret ) {
					return;
				}

				$tw = new \Abraham\TwitterOAuth\TwitterOAuth( $consumer_key, $consumer_secret );

				$request_token = $tw->oauth( 'oauth/request_token', array( 'oauth_callback' => wp_login_url() . '?vip_social_login_provider=twitter' ) );
				$_SESSION['oauth_token'] = $request_token['oauth_token'];
				$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

				$login_url = $tw->url( 'oauth/authenticate', array( 'oauth_token' => $request_token['oauth_token'] ) );

				$button_text = get_option( 'vip-social-login_twitter_button_text', esc_html_x( 'Log in with twitter', 'Login Button', 'vip-social-login') );
				$button_text = apply_filters( 'vip_social_login/providers/twitter/button_text', $button_text );

				break;
			case 'google':
				$client_id = get_option( 'vip-social-login_google_client_id', '' );
				$client_secret = get_option( 'vip-social-login_google_client_secret', '' );

				if ( ! $client_id || ! $client_secret ) {
					return;
				}

				$login_url = 'https://accounts.google.com/o/oauth2/v2/auth?scope=' . urlencode('https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/plus.me') . '&redirect_uri=' . urlencode( wp_login_url() . '?vip_social_login_provider=google' ) . '&response_type=code&client_id=' . $client_id . '&access_type=online';

				$button_text = get_option( 'vip-social-login_google_button_text', esc_html_x( 'Log in with Google', 'Login Button', 'vip-social-login') );
				$button_text = apply_filters( 'vip_social_login/providers/google/button_text', $button_text );

				break;
		}
		$onclick = "window.open( '{$login_url}', 'popUpWindow', 'height=410,width=620,left=10,top=10,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no,status=yes');return false";
		echo '<a href="' . $login_url . '" onclick="' . $onclick . '">' . $button_text . '</a>';
	}
}

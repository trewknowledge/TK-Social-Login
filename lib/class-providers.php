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

				echo '<a href="' . esc_url( $login_url ) . '">' . $button_text . '</a>';
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

				echo '<a href="' . esc_url( $login_url ) . '">' . $button_text . '</a>';
				break;
		}
	}
}

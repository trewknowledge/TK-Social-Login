<?php

namespace TK\Social_Login;

class Providers {

	public static function login_button( $provider ) {

		$providers = get_option( 'vip-social-login-providers', array() );

		if ( ! in_array( $provider, array_keys( $providers ), true ) ) {
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
		}
	}
}

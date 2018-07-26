<?php

namespace TK\Social_Login;

class Setup {
	function __construct() {
		$this->setup();
	}

	protected function setup() {
		// add_action( 'login_enqueue_scripts', array( $this, 'enqueue' ) );
		add_action( 'login_init', array( $this, 'check_login_provider' ) );
	}

	public function enqueue() {
		wp_enqueue_script(
			VIP_SOCIAL_LOGIN_CONFIG['plugin']['slug'],
			VIP_SOCIAL_LOGIN_CONFIG['plugin']['url'] . 'assets/js/login.js',
			array( 'jquery' ),
			VIP_SOCIAL_LOGIN_VERSION,
			true
		);
	}

	protected static function generate_unique_username( $username, $i = 1 ) {
		$username = sanitize_title( strtolower( str_replace( ' ', '', $username ) ) );

		if ( ! username_exists( $username ) ) {
			return $username;
		}

		$new_username = sprintf( '%s-%s', $username, $i );
		if ( ! username_exists( $new_username ) ) {
			return $new_username;
		} else {
			return self::generate_unique_username( $username, ++$i);
		}
	}

	public function login( $user_id ) {
		wp_set_current_user( $user_id );

		$secure_cookie = is_ssl();
		$secure_cookie = apply_filters('secure_signon_cookie', $secure_cookie, array());
		global $auth_secure_cookie; // XXX ugly hack to pass this to wp_authenticate_cookie

		$auth_secure_cookie = $secure_cookie;
		wp_set_auth_cookie($user_id, true, $secure_cookie);
	}

	public function check_login_provider() {
		if ( ! isset( $_GET['vip_social_login_provider'] ) ) {
			return;
		}

		$provider = sanitize_key( $_GET['vip_social_login_provider'] );

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
					'app_id' => $app_id, // Replace {app-id} with your app id
					'app_secret' => $secret,
				) );

				$helper = $fb->getRedirectLoginHelper();
				if ( isset( $_GET['state'] ) ) {
					$helper->getPersistentDataHandler()->set( 'state', $_GET['state'] );
				}

				try {
					$access_token = $helper->getAccessToken();
				} catch( \Facebook\Exceptions\FacebookResponseException $e ) {
					// When Graph returns an error
					echo 'Graph returned an error: ' . $e->getMessage();
					exit;
				} catch( \Facebook\Exceptions\FacebookSDKException $e ) {
					// When validation fails or other local issues
					echo 'Facebook SDK returned an error: ' . $e->getMessage();
					exit;
				}

				if ( ! isset( $access_token ) ) {
					if ( $helper->getError() ) {
						header( 'HTTP/1.0 401 Unauthorized' );
						echo "Error: " . $helper->getError() . "\n";
						echo "Error Code: " . $helper->getErrorCode() . "\n";
						echo "Error Reason: " . $helper->getErrorReason() . "\n";
						echo "Error Description: " . $helper->getErrorDescription() . "\n";
					} else {
						header( 'HTTP/1.0 400 Bad Request' );
						echo 'Bad request';
					}
					exit;
				}

				// The OAuth 2.0 client handler helps us manage access tokens
				$oauth2client = $fb->getOAuth2Client();

				// Get the access token metadata from /debug_token
				$token_metadata = $oauth2client->debugToken( $access_token );

				// Validation (these will throw FacebookSDKException's when they fail)
				$token_metadata->validateAppId( $app_id );
				$token_metadata->validateExpiration();

				if ( ! $access_token->isLongLived() ) {
					// Exchanges a short-lived access token for a long-lived one
					try {
						$access_token = $oauth2client->getLongLivedAccessToken( $access_token );
					} catch ( \Facebook\Exceptions\FacebookSDKException $e ) {
						echo "<p>Error getting long-lived access token: " . $e->getMessage() . "</p>\n\n";
						exit;
					}

					error_log( '<h3>Long-lived</h3>' );
					error_log( print_r( $access_token->getValue() ) );
				}

				try {
				  // Get the \Facebook\GraphNodes\GraphUser object for the current user.
				  // If you provided a 'default_access_token', the '{access-token}' is optional.
				  $response = $fb->get( '/me?fields=name,email', $access_token );
				} catch( \Facebook\Exceptions\FacebookResponseException $e ) {
				  // When Graph returns an error
				  echo 'Graph returned an error: ' . $e->getMessage();
				  exit;
				} catch( \Facebook\Exceptions\FacebookSDKException $e ) {
				  // When validation fails or other local issues
				  echo 'Facebook SDK returned an error: ' . $e->getMessage();
				  exit;
				}

				$me = $response->getGraphUser();
				error_log(print_r($me->getProperty('email'), true));
				error_log(print_r($me, true));
				echo 'Logged in as ' . $me->getName();

				if ( email_exists( $me->getProperty( 'email' ) ) ) { // Found a user with the same email.

					$user_info = get_user_by( 'email', $me->getProperty( 'email' ) );

					$this->login( $user_info->ID );

					$provider_uid = get_user_meta( $user_info->ID, "vip_social_login_{$provider}_uid", true );
					if ( ! $provider_uid ) {
						add_user_meta( $user_info->ID, "vip_social_login_{$provider}_uid", $me->getProperty( 'id' ), true );
					}

					wp_safe_redirect( home_url() );
					exit;

				} else if ( false ) { // Check if we found any secondary emails.
					echo 'Checking if we can find any secondary emails.';
				} else { // User not found. Create one.
					echo 'User not found.';
					$username = $email = $me->getProperty( 'email' );
					if ( $me->getProperty( 'name' ) ) {
						$username = $this->generate_unique_username( $me->getProperty( 'name' ) );
					}
					$password = wp_generate_password();

					$new_user_id = wp_create_user( $username, $password, $email );
					add_user_meta( $new_user_id, "vip_social_login_{$provider}_uid", $me->getProperty( 'id' ), true );

					$this->login( $new_user_id );

					wp_safe_redirect( home_url() );
				}

				break;
		}
	}
}

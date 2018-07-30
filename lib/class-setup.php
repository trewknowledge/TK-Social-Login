<?php

namespace TK\Social_Login;

session_start();

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
			VIP_SOCIAL_LOGIN_SLUG,
			VIP_SOCIAL_LOGIN_URL . 'assets/js/login.js',
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

	protected static function generate_unique_email( $email ) {
		$email = sanitize_email( $email );

		if ( ! email_exists( $email ) ) {
			return $email;
		}

		$new_email = explode( '@', $email );
		$new_email = sprintf( '%s-%s@%s', $new_email[0], rand(), $new_email[1] );
		if ( ! email_exists( $new_email ) ) {
			return $new_email;
		} else {
			return self::generate_unique_email( $new_email );
		}
	}

	protected function login_from_provider( $user_id, $uid, $provider ) {

		$provider_uid = get_user_meta( $user_id, "vip_social_login_{$provider}_uid", true );
		if ( ! $provider_uid ) {
			add_user_meta( $user_id, "vip_social_login_{$provider}_uid", $uid, true );
		}

		wp_set_current_user( $user_id );

		$secure_cookie = is_ssl();
		$secure_cookie = apply_filters( 'secure_signon_cookie', $secure_cookie, array() );
		global $auth_secure_cookie; // XXX ugly hack to pass this to wp_authenticate_cookie

		$auth_secure_cookie = $secure_cookie;
		wp_set_auth_cookie( $user_id, true, $secure_cookie );

		echo "<script>
            window.close();
    window.opener.location.reload();
        </script>";
		exit;
	}

	protected function create_user_from_provider( $name, $email, $provider ) {
		$username = self::generate_unique_username( $name );
		$password = wp_generate_password();

		if ( ! $email ) {
			$host = wp_parse_url( home_url() );
			$email = $provider . '_user@' . $host['host'];
			$email = self::generate_unique_email( $provider . '_user@' . $host['host'] );
		}

		return wp_create_user( $username, $password, $email );
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

				if ( ! $me->getProperty( 'id' ) ) {
					wp_safe_redirect( wp_login_url() );
				}

				if ( $me->getProperty( 'email' ) && email_exists( $me->getProperty( 'email' ) ) ) { // Found a user with the same email.
					$this->login_from_provider( email_exists( $me->getProperty( 'email' ) ), $me->getProperty( 'id' ), $provider );
				} else { // User not found. Create one.
					$new_user = $this->create_user_from_provider( $me->getProperty( 'name' ), $me->getProperty( 'email' ) ?: '', $provider );
					$this->login_from_provider( $new_user, $me->getProperty( 'id' ), $provider );
				}

				break;
			case 'twitter':
				$consumer_key = get_option( 'vip-social-login_twitter_consumer_key', '' );
				$consumer_secret = get_option( 'vip-social-login_twitter_consumer_secret', '' );

				if ( ! $consumer_key || ! $consumer_secret || ! isset( $_GET['oauth_verifier'], $_GET['oauth_token'] ) || $_GET['oauth_token'] !== $_SESSION['oauth_token'] ) {
					return;
				}

				if ( ! $_SESSION['access_token'] ) {
					$request_token = array();
					$request_token['oauth_token'] = $_GET['oauth_token'];
					$tw = new \Abraham\TwitterOAuth\TwitterOAuth( $consumer_key, $consumer_secret, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret'] );
					$_SESSION['access_token'] = $tw->oauth( 'oauth/access_token', array( 'oauth_verifier' => $_GET['oauth_verifier'] ) );
				}

				$tw = new \Abraham\TwitterOAuth\TwitterOAuth( $consumer_key, $consumer_secret, $_SESSION['access_token']['oauth_token'], $_SESSION['access_token']['oauth_token_secret'] );

				$user = $tw->get( 'account/verify_credentials', array( 'include_email' => true ) );

				if ( ! $user->id ) {
					wp_safe_redirect( wp_login_url() );
				}

				if ( isset( $user->email ) && email_exists( $user->email ) ) { // Found a user with the same email.
					$this->login_from_provider( email_exists( $user->email ), $user->id, $provider );
				} else {
					$new_user = $this->create_user_from_provider( $user->screen_name, $user->email ?: '', $provider );
					$this->login_from_provider( $new_user, $user->id, $provider );
				}
				break;
			case 'google':
				$client_id = get_option( 'vip-social-login_google_client_id', '' );
				$client_secret = get_option( 'vip-social-login_google_client_secret', '' );

				if ( ! $client_id || ! $client_secret || ! isset( $_GET['code'] ) ) {
					return;
				}


				$google = new \Google_Client();
				$google->setClientId( $client_id );
				$google->setClientSecret( $client_secret );
				$google->setApplicationName( 'VIP Social Login' );
				$google->setRedirectUri( wp_login_url() . '?vip_social_login_provider=google' );
				$google->addScope( 'https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/plus.me' );

				$token = $google->fetchAccessTokenWithAuthCode( $_GET['code'] );

				$oauth = new \Google_Service_Oauth2( $google );
				$user = $oauth->userinfo_v2_me->get();

				if ( ! $user->id ) {
					wp_safe_redirect( wp_login_url() );
				}

				if ( isset( $user->email ) && email_exists( $user->email ) ) { // Found a user with the same email.
					$this->login_from_provider( email_exists( $user->email ), $user->id, $provider );
				} else {
					$new_user = $this->create_user_from_provider( $user->name, $user->email ?: '', $provider );
					$this->login_from_provider( $new_user, $user->id, $provider );
				}
				break;
		}
	}
}

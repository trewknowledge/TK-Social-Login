<?php

namespace TK\Social_Login;

session_start();

class Setup {
	function __construct() {
		$this->setup();
	}

	protected function setup() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_public' ) );
		add_action( 'login_init', array( $this, 'check_login_provider' ) );
	}

	/**
	 * Enqueue css and js files.
	 */
	public function enqueue_public() {
		wp_enqueue_script(
			VIP_SOCIAL_LOGIN_SLUG,
			VIP_SOCIAL_LOGIN_URL . 'assets/js/public.js',
			array( 'jquery' ),
			VIP_SOCIAL_LOGIN_VERSION,
			true
		);

		wp_localize_script(
			VIP_SOCIAL_LOGIN_SLUG, 'VSL', array(
				'admin_ajax'      => admin_url( 'admin-ajax.php' ),
				'current_user_id' => get_current_user_id(),
				'nonce'           => wp_create_nonce( 'vsl_action' ),
			)
		);
	}

	/**
	 * Disconnect the network from user.
	 * It removes the network uid from the user_meta (or user_attribute).
	 */
	public function disconnect_network() {
		if ( ! isset( $_POST['nonce'], $_POST['user_id'], $_POST['provider'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['nonce'] ) ), 'vsl_action' ) ) {
			wp_send_json_error();
		}

		$user_id  = absint( $_POST['user_id'] );
		$provider = sanitize_text_field( wp_unslash( $_POST['provider'] ) );

		delete_user_attribute( $user_id, "vip_social_login_{$provider}_uid" );

		wp_send_json_success();
	}

	/**
	 * Usernames are created based on their names.
	 * To make those unique, we append -$n at the end of it.
	 * @param  string  $username The user name as it is in their social network profile.
	 * @param  integer $i        The number to be appended.
	 * @return string            A valid username.
	 */
	protected static function generate_unique_username( $username, $i = 1 ) {
		$username = sanitize_title( strtolower( str_replace( ' ', '', $username ) ) );

		if ( ! username_exists( $username ) ) {
			return $username;
		}

		$new_username = sprintf( '%s-%s', $username, $i );
		if ( ! username_exists( $new_username ) ) {
			return $new_username;
		} else {
			return self::generate_unique_username( $username, ++$i );
		}
	}

	/**
	 * Sometimes social networks don't provide us with an email.
	 * So we need to generate one. We provide a dummy email to this function
	 * and it takes care of appending a unique number to the end of it.
	 * @param  string $email A dummy email.
	 * @return string        A valid generated and unique email.
	 */
	protected static function generate_unique_email( $email ) {
		$email = sanitize_email( $email );

		if ( ! email_exists( $email ) ) {
			return $email;
		}

		$new_email = explode( '@', $email );
		$new_email = sprintf( '%s-%s@%s', $new_email[0], wp_rand(), $new_email[1] );
		if ( ! email_exists( $new_email ) ) {
			return $new_email;
		} else {
			return self::generate_unique_email( $new_email );
		}
	}

	/**
	 * Check if we can find a user based on the UID.
	 * We look into the database to see if we can find a user that has
	 * the provided UID as a user_meta (or attribute).
	 * If the user is not logged in we log them in too.
	 * It's kinda hacky, but it works.
	 * @param  string $name     The name provided by the network.
	 * @param  string $email    The email.
	 * @param  string $uid      The social network UID.
	 * @param  string $provider The social network name. E.g. google, facebook.
	 */
	protected function login_from_provider( $name, $email, $uid, $provider ) {

		global $wpdb;

		$meta_key = "vip_social_login_{$provider}_uid";

		if ( ! is_user_logged_in() ) {
			$user_id = $wpdb->get_var(
				$wpdb->prepare(
					"
				SELECT user.ID
				FROM $wpdb->users user
				INNER JOIN $wpdb->usermeta umeta
					ON user.ID = umeta.user_id
				WHERE umeta.meta_key = %s
					AND umeta.meta_value = %s
				",
					$meta_key,
					$uid
				)
			);

			// Search for uid in the DB. If found, log in.

			if ( ! $user_id ) {
				$user_id = email_exists( $email );
				if ( ! $user_id ) {
					$user_id = $this->create_user_from_provider( $name, $email ?: '', $provider );
				}
			}

			wp_set_current_user( $user_id );

			$secure_cookie = is_ssl();
			$secure_cookie = apply_filters( 'secure_signon_cookie', $secure_cookie, array() );
			global $auth_secure_cookie; // XXX ugly hack to pass this to wp_authenticate_cookie

			$auth_secure_cookie = $secure_cookie;
			wp_set_auth_cookie( $user_id, true, $secure_cookie );

			echo '<script>window.close();window.opener.location.reload();</script>';
			exit;

		} else {
			$found_id = $wpdb->get_var(
				$wpdb->prepare(
					"
				SELECT user.ID
				FROM $wpdb->users user
				INNER JOIN $wpdb->usermeta umeta
					ON user.ID = umeta.user_id
				WHERE umeta.meta_key = %s
					AND umeta.meta_value = %s
				",
					$meta_key,
					$uid
				)
			);

			if ( ! $found_id ) {
				$user_id = get_current_user_id();
				update_user_attribute( $user_id, "vip_social_login_{$provider}_uid", $uid );
			} else {
				echo "<script>window.close();window.opener.location.href = window.opener.location.href.replace( /[\?#].*|$/, '?vsl_error=100001' );</script>";
				exit;
			}
			echo '<script>window.close();window.opener.location.reload();</script>';
			exit;
		}
	}

	/**
	 * Checks the plugin error code that should be in the url as a get parameter.
	 * @return string The error message.
	 */
	public function error_message() {
		$code = isset( $_GET['vsl_error'] ) ? absint( $_GET['vsl_error'] ) : false;

		if ( ! $code ) {
			return;
		}

		switch ( $code ) {
			case '100001':
				esc_html_e( 'This network is already connected to another user.', 'vip-social-login' );
				break;
		}
	}

	/**
	 * Creates a new user based on a provider.
	 * @param  string $name     The name of the user.
	 * @param  string $email    The email.
	 * @param  string $provider The social network name. E.g. google, facebook.
	 * @return WP_User          The new user object.
	 */
	protected function create_user_from_provider( $name, $email, $provider ) {
		$username = self::generate_unique_username( $name );
		$password = wp_generate_password();

		if ( ! $email ) {
			$host  = wp_parse_url( home_url() );
			$email = $provider . '_user@' . $host['host'];
			$email = self::generate_unique_email( $provider . '_user@' . $host['host'] );
		}

		return wp_create_user( $username, $password, $email );
	}

	/**
	 * Check the provider that is being used and process the login accordingly.
	 * We use SESSIONS to handle parts of it because it's necessary.
	 * Facebook and others need to pass access tokens to other pages. We use SESSIONS for that.
	 */
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

				$fb = new \Facebook\Facebook(
					array(
						'app_id'     => $app_id, // Replace {app-id} with your app id
						'app_secret' => $secret,
					)
				);

				$helper = $fb->getRedirectLoginHelper();
				if ( isset( $_GET['state'] ) ) {
					$helper->getPersistentDataHandler()->set( 'state', sanitize_text_field( wp_unslash( $_GET['state'] ) ) );
				}

				try {
					$access_token = $helper->getAccessToken();
				} catch ( \Facebook\Exceptions\FacebookResponseException $e ) {
					// When Graph returns an error
					echo 'Graph returned an error: ' . esc_html( $e->getMessage() );
					exit;
				} catch ( \Facebook\Exceptions\FacebookSDKException $e ) {
					// When validation fails or other local issues
					echo 'Facebook SDK returned an error: ' . esc_html( $e->getMessage() );
					exit;
				}

				if ( ! isset( $access_token ) ) {
					if ( $helper->getError() ) {
						header( 'HTTP/1.0 401 Unauthorized' );
						echo 'Error: ' . esc_html( $helper->getError() ) . "\n";
						echo 'Error Code: ' . esc_html( helper->getErrorCode() ) . "\n";
						echo 'Error Reason: ' . esc_html( $helper->getErrorReason() ) . "\n";
						echo 'Error Description: ' . esc_html( $helper->getErrorDescription() ) . "\n";
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
						echo '<p>Error getting long-lived access token: ' . esc_html( $e->getMessage() ) . "</p>\n\n";
						exit;
					}
				}

				try {
					// Get the \Facebook\GraphNodes\GraphUser object for the current user.
					// If you provided a 'default_access_token', the '{access-token}' is optional.
					$response = $fb->get( '/me?fields=name,email', $access_token );
				} catch ( \Facebook\Exceptions\FacebookResponseException $e ) {
					// When Graph returns an error
					echo 'Graph returned an error: ' . esc_html( $e->getMessage() );
					exit;
				} catch ( \Facebook\Exceptions\FacebookSDKException $e ) {
					// When validation fails or other local issues
					echo 'Facebook SDK returned an error: ' . esc_html( $e->getMessage() );
					exit;
				}

				$me = $response->getGraphUser();

				if ( ! $me->getProperty( 'id' ) ) {
					if ( ! is_user_logged_in() ) {
						wp_safe_redirect( wp_login_url() );
						exit;
					} else {
						echo '<script>window.close();window.opener.location.reload();</script>';
						exit;
					}
				}

				$this->login_from_provider( $me->getProperty( 'name' ), $me->getProperty( 'email' ), $me->getProperty( 'id' ), $provider );
				break;
			case 'twitter':
				$consumer_key    = get_option( 'vip-social-login_twitter_consumer_key', '' );
				$consumer_secret = get_option( 'vip-social-login_twitter_consumer_secret', '' );

				if ( ! $consumer_key || ! $consumer_secret || ! isset( $_GET['oauth_verifier'], $_GET['oauth_token'] ) || $_GET['oauth_token'] !== $_SESSION['oauth_token'] ) {
					return;
				}

				if ( ! $_SESSION['access_token'] ) {
					$request_token                = array();
					$request_token['oauth_token'] = sanitize_text_field( wp_unslash( $_GET['oauth_token'] ) );
					$tw                           = new \Abraham\TwitterOAuth\TwitterOAuth( $consumer_key, $consumer_secret, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret'] );
					$_SESSION['access_token']     = $tw->oauth( 'oauth/access_token', array( 'oauth_verifier' => sanitize_text_field( wp_unslash( $_GET['oauth_verifier'] ) ) ) );
				}

				$tw = new \Abraham\TwitterOAuth\TwitterOAuth( $consumer_key, $consumer_secret, $_SESSION['access_token']['oauth_token'], $_SESSION['access_token']['oauth_token_secret'] );

				$user = $tw->get( 'account/verify_credentials', array( 'include_email' => true ) );

				if ( ! $user->id ) {
					if ( ! is_user_logged_in() ) {
						wp_safe_redirect( wp_login_url() );
						exit;
					} else {
						echo '<script>window.close();window.opener.location.reload();</script>';
						exit;
					}
				}

				$this->login_from_provider( $user->screen_name, $user->email, $user->id, $provider );
				break;
			case 'google':
				$client_id     = get_option( 'vip-social-login_google_client_id', '' );
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

				$token = $google->fetchAccessTokenWithAuthCode( sanitize_text_field( wp_unslash( $_GET['code'] ) ) );

				$oauth = new \Google_Service_Oauth2( $google );
				$user  = $oauth->userinfo_v2_me->get();

				if ( ! $user->id ) {
					if ( ! is_user_logged_in() ) {
						wp_safe_redirect( wp_login_url() );
						exit;
					} else {
						echo '<script>window.close();window.opener.location.reload();</script>';
						exit;
					}
				}

				$this->login_from_provider( $user->name, $user->email, $user->id, $provider );
				break;
		}
	}
}

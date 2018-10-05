<?php

namespace TK\Social_Login;

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
	 * Checks the plugin error code that should be in the url as a get parameter.
	 * @return string The error message.
	 */
	public static function get_error_message() {
		$code = isset( $_GET['vsl_error'] ) ? absint( $_GET['vsl_error'] ) : false;

		if ( ! $code ) {
			return;
		}

		$message = '';

		switch ( $code ) {
			case 100001:
				$message = esc_html__( 'This network is already connected to another user.', 'vip-social-login' );
				break;
			case 100002:
				$message = esc_html__( 'The network provider is not acceptable.', 'vip-social-login' );
				break;
			case 100003:
				$message = esc_html__( 'The network provider is currently disabled.', 'vip-social-login' );
				break;
		}

		return $message;
	}

	public static function error_message() {
		echo self::get_error_message();
	}

	public function connect_network() {
		if ( ! isset( $_POST['nonce'], $_POST['uid'], $_POST['provider'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['nonce'] ) ), 'vsl_action' ) ) {
			wp_send_json_error();
		}

		$providers = get_option( 'vip-social-login-providers', array() );
		$provider  = sanitize_text_field( wp_unslash( $_POST['provider'] ) );
		if ( ! in_array( $provider, array_keys( $providers ), true ) ) {
			$redirect_url = self::get_error_url( 100002 );
			wp_send_json_error( array( 'redirect' => $redirect_url ) );
		}

		if ( 'false' === $providers[ $provider ] ) {
			$redirect_url = self::get_error_url( 100003 );
			wp_send_json_error( array( 'redirect' => $redirect_url ) );
		}

		$uid      = sanitize_text_field( wp_unslash( $_POST['uid'] ) );
		$name     = isset( $_POST['name'] ) && $_POST['name'] ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
		$email    = isset( $_POST['email'] ) && $_POST['email'] ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';


		if ( is_user_logged_in() ) {
			$wp_users = self::is_uid_assigned( $provider, $uid );
			if ( $wp_users ) {
				$redirect_url = self::get_error_url( 100001 );
				wp_send_json_error( array( 'redirect' => $redirect_url ) );
			}

			update_user_meta( get_current_user_id(), "vip_social_login_{$provider}_uid", $uid );
			wp_send_json_success();
		}

		self::login_user( $name, $email, $uid, $provider );

		wp_send_json_success();
	}

	protected static function get_error_url( $error_code ) {
		$error_code = absint( $error_code );
		return add_query_arg(
			array(
				'vsl_error' => $error_code,
			),
			wp_get_referer()
		);
	}

	protected static function is_uid_assigned( $provider, $uid ) {
		$provider = sanitize_text_field( wp_unslash( $provider ) );
		$uid      = sanitize_text_field( wp_unslash( $uid ) );

		$meta_key = "vip_social_login_{$provider}_uid";

		$wp_users_query = new \WP_User_Query( array(
			'meta_key' => $meta_key,
			'meta_value' => $uid,
		) );

		return $wp_users_query->get_results();
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
	 * Check if we can find a user based on the UID.
	 * We look into the database to see if we can find a user that has
	 * the provided UID as a user_meta (or attribute).
	 * If a user is not found, we check by the email.
	 * If we still can't find a user, we create one.
	 * We assign the UID to that user and log them in.
	 * @param  string $name     The name provided by the network.
	 * @param  string $email    The email.
	 * @param  string $uid      The social network UID.
	 * @param  string $provider The social network name. E.g. google, facebook.
	 */
	protected static function login_user( $name, $email, $uid, $provider ) {
		$name     = sanitize_text_field( wp_unslash( $name ) );
		$email    = sanitize_email( wp_unslash( $email ) );
		$uid      = sanitize_text_field( wp_unslash( $uid ) );
		$provider = sanitize_text_field( wp_unslash( $provider ) );

		$user_id  = false;

		$wp_users = self::is_uid_assigned( $provider, $uid );
		if ( $wp_users ) {
			$user_id = $wp_users[0]->ID;
		}

		if ( ! $user_id ) {
			$user_id = email_exists( $email );
			if ( ! $user_id ) {
				$user_id = self::create_user_from_provider( $name, $email ?: '', $provider );
			}
		}
		update_user_meta( $user_id, "vip_social_login_{$provider}_uid", $uid );

		wp_set_current_user( $user_id );

		$secure_cookie = is_ssl();
		$secure_cookie = apply_filters( 'secure_signon_cookie', $secure_cookie, array() );
		global $auth_secure_cookie; // XXX ugly hack to pass this to wp_authenticate_cookie

		$auth_secure_cookie = $secure_cookie;
		wp_set_auth_cookie( $user_id, true, $secure_cookie );
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
	 * Creates a new user based on a provider.
	 * @param  string $name     The name of the user.
	 * @param  string $email    The email.
	 * @param  string $provider The social network name. E.g. google, facebook.
	 * @return WP_User          The new user object.
	 */
	protected static function create_user_from_provider( $name, $email, $provider ) {
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
	 */
	public function check_login_provider() {
		if ( ! isset( $_GET['vip_social_login_provider'] ) ) {
			return;
		}

		$provider = sanitize_key( $_GET['vip_social_login_provider'] );

		if ( 'facebook' === $provider ) {
			return;
		}

		$providers = get_option( 'vip-social-login-providers', array() );

		if ( ! in_array( $provider, array_keys( $providers ), true ) ) {
			return;
		}

		if ( 'false' === $providers[ $provider ] ) {
			return;
		}

		switch ( $provider ) {
			case 'twitter':
				$consumer_key    = get_option( 'vip-social-login_twitter_consumer_key', '' );
				$consumer_secret = get_option( 'vip-social-login_twitter_consumer_secret', '' );
				$access_token = get_option( 'vip-social-login_twitter_access_token', '' );
				$access_token_secret = get_option( 'vip-social-login_twitter_access_token_secret', '' );

				if ( ! $consumer_key || ! $consumer_secret || ! $access_token || ! $access_token_secret ) {
					return;
				}

				$tw = new \Abraham\TwitterOAuth\TwitterOAuth( $consumer_key, $consumer_secret, $access_token, $access_token_secret );

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
				self::login_user( $user->screen_name, $user->email, $user->id, $provider );
				echo '<script>window.close();window.opener.location.reload();</script>';
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

				self::login_user( $user->name, $user->email, $user->id, $provider );
				break;
		}
	}
}

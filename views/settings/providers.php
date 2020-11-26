<h2><?php esc_html_e( 'Providers', 'vip-social-login' ); ?></h2>

<?php $providers = get_option( 'vip-social-login-providers', array() ); ?>

<div class="vip-social-login-providers">
	<div class="vip-social-login-provider">
		<a href="<?php echo esc_url( admin_url( 'options-general.php?page=vip-social-login&view=providers&subview=facebook&tab=getting-started' ) ); ?>" class="cog"><span class="dashicons dashicons-admin-generic"></span></a>
		<div class="vip-social-login-toggle">
			<div class="vip-social-login-toggle-option-off vip-social-login-toggle-option"><?php echo esc_html_x( 'OFF', 'Toggle On/Off Status', 'vip-social-login' ); ?></div>
			<div class="vip-social-login-toggle-option-on vip-social-login-toggle-option"><?php echo esc_html_x( 'ON', 'Toggle On/Off Status', 'vip-social-login' ); ?></div>
			<input <?php checked( ( isset( $providers['facebook'] ) && $providers['facebook'] ? true : false ), true ); ?> type="checkbox" data-nonce="<?php echo esc_attr( wp_create_nonce( 'vip-social-login-update-providers' ) ); ?>" data-provider="facebook" name="vip-social-login-provider[facebook]" id="vip-social-login-toggle-provider-fb">
			<label class="vip-social-login-toggle-current-status" for="vip-social-login-toggle-provider-fb">
				<span class="vip-social-login-provider-fb"><img src="<?php echo esc_url( VIP_SOCIAL_LOGIN_URL ) . 'assets/img/facebook.png'; ?>" alt=""></span>
			</label>
		</div>
	</div>
	<div class="vip-social-login-provider">
		<a href="<?php echo esc_url( admin_url( 'options-general.php?page=vip-social-login&view=providers&subview=twitter&tab=getting-started' ) ); ?>" class="cog"><span class="dashicons dashicons-admin-generic"></span></a>
		<div class="vip-social-login-toggle">
			<div class="vip-social-login-toggle-option-off vip-social-login-toggle-option"><?php echo esc_html_x( 'OFF', 'Toggle On/Off Status', 'vip-social-login' ); ?></div>
			<div class="vip-social-login-toggle-option-on vip-social-login-toggle-option"><?php echo esc_html_x( 'ON', 'Toggle On/Off Status', 'vip-social-login' ); ?></div>
			<input <?php checked( ( isset( $providers['twitter'] ) && $providers['twitter'] ? true : false ), true ); ?> type="checkbox" data-nonce="<?php echo esc_attr( wp_create_nonce( 'vip-social-login-update-providers' ) ); ?>" data-provider="twitter" name="vip-social-login-provider[twitter]" id="vip-social-login-toggle-provider-twitter">
			<label class="vip-social-login-toggle-current-status" for="vip-social-login-toggle-provider-twitter">
				<span class="vip-social-login-provider-twitter"><img src="<?php echo esc_url( VIP_SOCIAL_LOGIN_URL ) . 'assets/img/twitter.png'; ?>" alt=""></span>
			</label>
		</div>
	</div>
	<div class="vip-social-login-provider">
		<a href="<?php echo esc_url( admin_url( 'options-general.php?page=vip-social-login&view=providers&subview=google&tab=getting-started' ) ); ?>" class="cog"><span class="dashicons dashicons-admin-generic"></span></a>
		<div class="vip-social-login-toggle">
			<div class="vip-social-login-toggle-option-off vip-social-login-toggle-option"><?php echo esc_html_x( 'OFF', 'Toggle On/Off Status', 'vip-social-login' ); ?></div>
			<div class="vip-social-login-toggle-option-on vip-social-login-toggle-option"><?php echo esc_html_x( 'ON', 'Toggle On/Off Status', 'vip-social-login' ); ?></div>
			<input <?php checked( ( isset( $providers['google'] ) && $providers['google'] ? true : false ), true ); ?> type="checkbox" data-nonce="<?php echo esc_attr( wp_create_nonce( 'vip-social-login-update-providers' ) ); ?>" data-provider="google" name="vip-social-login-provider[google]" id="vip-social-login-toggle-provider-google">
			<label class="vip-social-login-toggle-current-status" for="vip-social-login-toggle-provider-google">
				<span class="vip-social-login-provider-google"><img src="<?php echo esc_url( VIP_SOCIAL_LOGIN_URL ) . 'assets/img/google.png'; ?>" alt=""></span>
			</label>
		</div>
	</div>
	<div class="vip-social-login-provider">
		<a href="<?php echo esc_url( admin_url( 'options-general.php?page=vip-social-login&view=providers&subview=linkedin&tab=getting-started' ) ); ?>" class="cog"><span class="dashicons dashicons-admin-generic"></span></a>
		<div class="vip-social-login-toggle">
			<div class="vip-social-login-toggle-option-off vip-social-login-toggle-option"><?php echo esc_html_x( 'OFF', 'Toggle On/Off Status', 'vip-social-login' ); ?></div>
			<div class="vip-social-login-toggle-option-on vip-social-login-toggle-option"><?php echo esc_html_x( 'ON', 'Toggle On/Off Status', 'vip-social-login' ); ?></div>
			<input <?php checked( ( isset( $providers['linkedin'] ) && $providers['linkedin'] ? true : false ), true ); ?> type="checkbox" data-nonce="<?php echo esc_attr( wp_create_nonce( 'vip-social-login-update-providers' ) ); ?>" data-provider="linkedin" name="vip-social-login-provider[linkedin]" id="vip-social-login-toggle-provider-linkedin">
			<label class="vip-social-login-toggle-current-status" for="vip-social-login-toggle-provider-linkedin">
				<span class="vip-social-login-provider-linkedin"><img src="<?php echo esc_url( VIP_SOCIAL_LOGIN_URL ) . 'assets/img/linkedin.png'; ?>" alt=""></span>
			</label>
		</div>
	</div>
</div>

<script type="text/html" id="tmpl-updating">
	<div class="vip-social-login-update-indicator">
		<span class="dashicons dashicons-image-rotate"></span>
		<em><?php echo esc_html_x( 'Updating...', 'Admin updating indicator', 'vip-social-login' ); ?></em>
	</div>
</script>

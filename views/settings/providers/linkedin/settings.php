<form action="options.php" method="post" class="vip-social-login-settings-form">
	<?php settings_fields( 'vip-social-login-linkedin' ); ?>

	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">
					<label for="vip-social-login_linkedin_client_secret"><?php echo esc_html_x( 'Client ID', 'Admin(linkedin) - Settings', 'vip-social-login' ); ?>:</label>
				</th>
				<td>
					<?php $client_id = get_option( 'vip-social-login_linkedin_client_id', '' ); ?>
					<input type="text" class="regular-text" name="vip-social-login_linkedin_client_id" id="vip-social-login_linkedin_client_id" value="<?php echo esc_attr( $client_id ); ?>">
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="vip-social-login_linkedin_client_secret"><?php echo esc_html_x( 'Client Secret', 'Admin(linkedin) - Settings', 'vip-social-login' ); ?>:</label>
				</th>
				<td>
					<?php $client_secret = get_option( 'vip-social-login_linkedin_client_secret', '' ); ?>
					<input type="password" class="regular-text" name="vip-social-login_linkedin_client_secret" id="vip-social-login_linkedin_client_secret" value="<?php echo esc_attr( $client_secret ); ?>">
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="vip-social-login_linkedin_redirect_url"><?php echo esc_html_x( 'Redirect Url', 'Admin(linkedin) - Settings', 'vip-social-login' ); ?>:</label>
				</th>
				<td>
					<?php $redirect_url = get_option( 'vip-social-login_linkedin_redirect_url', '' ); ?>
					<input type="text" class="regular-text" name="vip-social-login_linkedin_redirect_url" id="vip-social-login_linkedin_redirect_url" value="<?php echo esc_attr( $redirect_url ); ?>">
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="vip-social-login_linkedin_redirect_url"><?php echo esc_html_x( 'Scope(s)', 'Admin(linkedin) - Settings', 'vip-social-login' ); ?>:</label>
				</th>
				<td>
					<?php $scopes = get_option( 'vip-social-login_linkedin_scopes', '' ); ?>
					<input type="text" class="regular-text" name="vip-social-login_linkedin_scopes" id="vip-social-login_linkedin_scopes" value="<?php echo esc_attr( $scopes ); ?>" >
				</td>
			</tr>
		</tbody>
	</table>

	<?php submit_button(); ?>
</form>
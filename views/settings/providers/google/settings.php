<form action="options.php" method="post" class="vip-social-login-settings-form">
	<?php settings_fields( 'vip-social-login-google' ); ?>

	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">
					<label for="vip-social-login_google_client_id"><?php echo esc_html_x( 'Client ID', 'Admin(Google) - Settings', 'vip-social-login' ); ?>:</label>
				</th>
				<td>
					<?php $client_id = get_option( 'vip-social-login_google_client_id', '' ); ?>
					<input type="text" class="regular-text" name="vip-social-login_google_client_id" id="vip-social-login_google_client_id" value="<?php echo esc_attr( $client_id ); ?>">
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="vip-social-login_google_client_secret"><?php echo esc_html_x( 'Client Secret', 'Admin(Google) - Settings', 'vip-social-login' ); ?>:</label>
				</th>
				<td>
					<?php $client_secret = get_option( 'vip-social-login_google_client_secret', '' ); ?>
					<input type="password" class="regular-text" name="vip-social-login_google_client_secret" id="vip-social-login_google_client_secret" value="<?php echo esc_attr( $client_secret ); ?>">
				</td>
			</tr>
		</tbody>
	</table>

	<?php submit_button(); ?>
</form>

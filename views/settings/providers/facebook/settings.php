<form action="options.php" method="post" class="vip-social-login-settings-form">
	<?php settings_fields( 'vip-social-login' ); ?>

	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">
					<label for="vip-social-login_facebook_app_secret"><?php echo esc_html_x( 'APP ID', 'Admin(Facebook) - Settings', 'vip-social-login' ); ?>:</label>
				</th>
				<td>
					<?php $app_id = get_option( 'vip-social-login_facebook_app_id', '' ); ?>
					<input type="text" class="regular-text" name="vip-social-login_facebook_app_id" id="vip-social-login_facebook_app_id" value="<?php echo esc_attr( $app_id ); ?>">
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="vip-social-login_facebook_app_secret"><?php echo esc_html_x( 'APP Secret', 'Admin(Facebook) - Settings', 'vip-social-login' ); ?>:</label>
				</th>
				<td>
					<?php $app_secret = get_option( 'vip-social-login_facebook_app_secret', '' ); ?>
					<input type="password" class="regular-text" name="vip-social-login_facebook_app_secret" id="vip-social-login_facebook_app_secret" value="<?php echo esc_attr( $app_secret ); ?>">
				</td>
			</tr>
		</tbody>
	</table>

	<?php submit_button(); ?>
</form>

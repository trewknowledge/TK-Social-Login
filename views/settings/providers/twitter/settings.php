<form action="options.php" method="post" class="vip-social-login-settings-form">
	<?php settings_fields( 'vip-social-login-twitter' ); ?>

	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">
					<label for="vip-social-login_twitter_consumer_key"><?php echo esc_html_x( 'Consumer Key', 'Admin(Twitter) - Settings', 'vip-social-login' ); ?>:</label>
				</th>
				<td>
					<?php $consumer_key = get_option( 'vip-social-login_twitter_consumer_key', '' ); ?>
					<input type="text" class="regular-text" name="vip-social-login_twitter_consumer_key" id="vip-social-login_twitter_consumer_key" value="<?php echo esc_attr( $consumer_key ); ?>">
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="vip-social-login_twitter_consumer_secret"><?php echo esc_html_x( 'Consumer Secret', 'Admin(Twitter) - Settings', 'vip-social-login' ); ?>:</label>
				</th>
				<td>
					<?php $consumer_secret = get_option( 'vip-social-login_twitter_consumer_secret', '' ); ?>
					<input type="password" class="regular-text" name="vip-social-login_twitter_consumer_secret" id="vip-social-login_twitter_consumer_secret" value="<?php echo esc_attr( $consumer_secret ); ?>">
				</td>
			</tr>
		</tbody>
	</table>

	<?php submit_button(); ?>
</form>

<?php $providers = get_option( 'vip-social-login-providers', array() ); ?>

<?php if ( ! empty( $providers ) ): ?>
	<ul>
		<?php foreach ( $providers as $provider => $active ): ?>
			<?php if ( $active ): ?>
				<?php $uid = get_user_meta( get_the_ID(), "vip_social_login_{$provider}_uid", true ); ?>
				<li>
					<?php TK\Social_Login\Providers::login_button( $provider ); ?>
				</li>
			<?php endif ?>
		<?php endforeach ?>
	</ul>
<?php endif ?>


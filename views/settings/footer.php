	<?php
	$signature = apply_filters( 'vip-social-login/admin/show_signature', true );
	if ( $signature ): ?>
		<hr>
		<p>
			<em>
				<?php echo sprintf(
					/* Translators: 1. Link to VIP website 2.Closing </a> tag 3. */
					esc_html_x('Trew Knowledge Social Login. Built with &#9829; by %1$sTrew Knowledge%2$s.', 'Admin Settings Page Footer', 'vip-social-login'),
					'<a href="https://trewknowledge.com/" target="_blank">',
					'</a>'
				); ?>
				&nbsp;
				|
				&nbsp;
				<?php echo sprintf(
					/* Translators: 1. Link to donation page 2.Closing </a> tag 3. Link to plugin review page */
					esc_html_x('Support our development efforts! %1$sDonate%2$s or leave a %3$s5-star rating%2$s.', 'Admin Settings Page Footer', 'vip-social-login'),
					'<a href="https://trewknowledge.com/vip-social-login/donate/" target="_blank">',
					'</a>',
					'<a href="https://wordpress.org/plugins/vip-social-login/#reviews" target="_blank">'
				); ?>
			</em>
		</p>
	<?php endif; ?>
</div>

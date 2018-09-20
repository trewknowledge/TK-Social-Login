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
			</em>
		</p>
	<?php endif; ?>
</div>

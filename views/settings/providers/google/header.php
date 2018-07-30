<h2>Google</h2>

<nav class="provider-tab-navigation">
	<ul>
		<li><a class="<?php echo ( isset( $tab ) && 'getting-started' === $tab ? 'vip-social-login-tab-nav-active' : '' ); ?>" href="<?php echo esc_url( admin_url( 'options-general.php?page=vip-social-login&view=providers&subview=google&tab=getting-started' ) ); ?>" title="<?php echo esc_attr_x( 'Getting Started', 'Admin - provider tab navigation', 'vip-social-login' ); ?>"><?php echo esc_html_x( 'Getting Started', 'Admin - provider tab navigation', 'vip-social-login' ); ?></a></li>
		<li><a class="<?php echo ( isset( $tab ) && 'settings' === $tab ? 'vip-social-login-tab-nav-active' : '' ); ?>" href="<?php echo esc_url( admin_url( 'options-general.php?page=vip-social-login&view=providers&subview=google&tab=settings' ) ); ?>" title="<?php echo esc_attr_x( 'Settings', 'Admin - provider tab navigation', 'vip-social-login' ); ?>"><?php echo esc_html_x( 'Settings', 'Admin - provider tab navigation', 'vip-social-login' ); ?></a></li>
	</ul>
</nav>

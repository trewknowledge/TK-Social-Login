<div class="wrap vip-social-login-admin">
	<header class="settings-header">
		<h1><?php esc_html_e( 'VIP Social Login', 'vip-social-login' ); ?></h1>
	</header>
	<div class="vip-social-login-admin-nav-bar">
		<nav>
			<h2 class="nav-tab-wrapper">
				<a href="<?php echo esc_url( admin_url( 'options-general.php?page=vip-social-login&view=providers' ) ); ?>" class="nav-tab <?php echo ( isset( $view ) && 'providers' === $view ? 'nav-tab-active' : '' ); ?>"><?php echo esc_html_x( 'Providers', 'Plugin Admin navigation tabs', 'vip-social-login' ); ?></a>
				<a href="<?php echo esc_url( admin_url( 'options-general.php?page=vip-social-login&view=global-settings' ) ); ?>" class="nav-tab <?php echo ( isset( $view ) && 'global-settings' === $view ? 'nav-tab-active' : '' ); ?>"><?php echo esc_html_x( 'Global Settings', 'Plugin Admin navigation tabs', 'vip-social-login' ); ?></a>
			</h2>
		</nav>
	</div>

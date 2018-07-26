<p style="max-width:55em;"><?php echo esc_html_x( 'To allow your visitors to log in with their Facebook account, first you must create a Facebook App. The following guide will help you through the Facebook App creation process. After you have created your Facebook App, head over to "Settings" and configure the given "App ID" and "App secret" according to your Facebook App.', 'Admin(Facebook) - Getting started', 'vip-social-login' ); ?></p>

<h3><?php echo esc_html_x( 'Create Facebook App', 'Admin(Facebook) - Getting started', 'vip-social-login' ); ?></h3>

<ol>
	<li><?php echo sprintf( esc_html_x( 'Navigate to %1$s', 'Admin(Facebook) - Getting started', 'vip-social-login'), '<a href="https://developers.facebook.com/apps/" target="_blank">https://developers.facebook.com/apps/</a>' ); ?></li>
	<li><?php echo esc_html_x( 'Log in with your Facebook credentials if you are not logged in', 'Admin(Facebook) - Getting started', 'vip-social-login' ); ?></li>
	<li><?php echo esc_html_x( 'Click on the "Add a New App" button', 'Admin(Facebook) - Getting started', 'vip-social-login' ); ?></li>
	<li><?php echo esc_html_x( 'Fill "Display Name" and "Contact Email"', 'Admin(Facebook) - Getting started', 'vip-social-login' ); ?></li>
	<li><?php echo esc_html_x( 'Click on blue "Create App ID" button', 'Admin(Facebook) - Getting started', 'vip-social-login' ); ?></li>
	<li><?php echo esc_html_x( 'Move your mouse over Facebook Login and click on the appearing "Set Up" button', 'Admin(Facebook) - Getting started', 'vip-social-login' ); ?></li>
	<li><?php echo esc_html_x( 'Choose Web', 'Admin(Facebook) - Getting started', 'vip-social-login' ); ?></li>
	<li><?php echo sprintf( esc_html_x( 'Fill "Site URL" with the url of your homepage, probably: %1$s', 'Admin(Facebook) - Getting started', 'vip-social-login' ), '<strong>' . esc_url( home_url('/') ) . '</strong>' ); ?></li>
	<li><?php echo esc_html_x( 'Click on "Save"', 'Admin(Facebook) - Getting started', 'vip-social-login' ); ?></li>
	<li><?php echo esc_html_x( 'In the left sidebar, click on "Facebook Login"', 'Admin(Facebook) - Getting started', 'vip-social-login' ); ?></li>
	<li><?php echo sprintf( esc_html_x( 'Add the following URL to the "Valid OAuth redirect URIs" field: %1$s', 'Admin(Facebook) - Getting started', 'vip-social-login' ), '<strong>' . esc_url( home_url( '/wp-login.php?vip-social-login-provider=facebook' ) ) . '</strong>' ); ?></li>
	<li><?php echo esc_html_x( 'Click on "Save Changes"', 'Admin(Facebook) - Getting started', 'vip-social-login' ); ?></li>
	<li><?php echo esc_html_x( 'In the top of the left sidebar, click on "Settings"', 'Admin(Facebook) - Getting started', 'vip-social-login' ); ?></li>
	<li><?php echo esc_html_x( 'Here you can see your "APP ID" and you can see your "App secret" if you click on the "Show" button. These will be needed in plugin\'s settings.', 'Admin(Facebook) - Getting started', 'vip-social-login' ); ?></li>
	<li><?php echo esc_html_x( 'Enter your domain name to the App Domains', 'Admin(Facebook) - Getting started', 'vip-social-login' ); ?></li>
	<li><?php echo esc_html_x( 'Fill up the "Privacy Policy URL". Provide a publicly available and easily accessible privacy policy that explains what data you are collecting and how you will use that data.', 'Admin(Facebook) - Getting started', 'vip-social-login' ); ?></li>
	<li><?php echo esc_html_x( 'Save your changes.', 'Admin(Facebook) - Getting started', 'vip-social-login' ); ?></li>
	<li><?php echo esc_html_x( 'Your application is currently private, which means that only you can log in with it. In the left sidebar choose "App Review" and make your App public', 'Admin(Facebook) - Getting started', 'vip-social-login' ); ?></li>
</ol>

<a href="<?php echo esc_url( admin_url( 'options-general.php?page=vip-social-login&view=providers&subview=facebook&tab=settings' ) ); ?>" class="button-primary"><?php echo esc_html_x( 'I am done setting up my Facebook App', 'Admin(Facebook) - Getting started', 'vip-social-login' ); ?></a>

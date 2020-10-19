<p style="max-width:55em;"><?php echo esc_html_x( 'To allow your visitors to log in with their LinkedIn account, first you must create a LinkedIn App. The following guide will help you through the LinkedIn App creation process. After you have created your LinkedIn App, head over to "Settings" and configure the given "App ID" and "App secret" according to your LinkedIn App.', 'Admin(LinkedIn) - Getting started', 'vip-social-login' ); ?></p>

<h3><?php echo esc_html_x( 'Create LinkedIn App', 'Admin(LinkedIn) - Getting started', 'vip-social-login' ); ?></h3>

<ol>
	<li><?php echo sprintf( esc_html_x( 'Navigate to %1$s', 'Admin(LinkedIn) - Getting started', 'vip-social-login'), '<a href="https://www.linkedin.com/developers/" target="_blank">https://www.linkedin.com/developers/</a>' ); ?></li>
	<li><?php echo esc_html_x( 'Log in with your LinkedIn credentials if you are not logged in', 'Admin(LinkedIn) - Getting started', 'vip-social-login' ); ?></li>
	<li><?php echo esc_html_x( 'Click on the "Create App" blue button', 'Admin(LinkedIn) - Getting started', 'vip-social-login' ); ?></li>
	<li><?php echo esc_html_x( 'Fill "App Name" and "LinkedIn Page"', 'Admin(LinkedIn) - Getting started', 'vip-social-login' ); ?></li>
	<li><?php echo esc_html_x( 'Upload an logo for your App', 'Admin(LinkedIn) - Getting started', 'vip-social-login' ); ?></li>
	<li><?php echo esc_html_x( 'Check "Legal Agreement" checkbox', 'Admin(LinkedIn) - Getting started', 'vip-social-login' ); ?></li>
	<li><?php echo esc_html_x( 'Click on "Create App" button', 'vip-social-login' ); ?></li>
	<li><?php echo esc_html_x( 'Click on "Auth" tab', 'Admin(LinkedIn) - Getting started', 'vip-social-login' ); ?></li>
	<li><?php echo esc_html_x( 'Copy "Client ID", "Client Secret" & "Redirct URL". These will be needed in plugin\'s settings', 'Admin(LinkedIn) - Getting started', 'vip-social-login' ); ?></li>
	<li><?php echo esc_html_x( 'Go to "Products" tab of your APP page & select "Sign In with LinkedIn" product.', 'Admin(LinkedIn) - Getting started', 'vip-social-login' ); ?></li>
	<li><?php echo esc_html_x( 'Once your product approved go to "Auth" tab & copy permission(s) from "OAuth 2.0 scopes" section. Add scope(s) as comma seperated to plugin settings.', 'Admin(LinkedIn) - Getting started', 'vip-social-login' ); ?></li>
</ol>

<a href="<?php echo esc_url( admin_url( 'options-general.php?page=vip-social-login&view=providers&subview=linkedin&tab=settings' ) ); ?>" class="button-primary"><?php echo esc_html_x( 'I am done setting up my LinkedIn App', 'Admin(LinkedIn) - Getting started', 'vip-social-login' ); ?></a>

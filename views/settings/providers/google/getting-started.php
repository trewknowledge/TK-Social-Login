<p style="max-width:55em;"><?php echo esc_html_x( 'To allow your visitors to log in with their Google account, first you must create a Google App. The following guide will help you through the Google App creation process. After you have created your Google App, head over to "Settings" and configure the given "Client ID" and "Client secret" according to your Google App.', 'Admin(Google) - Getting started', 'vip-social-login' ); ?></p>

<h3><?php echo esc_html_x( 'Create Google App', 'Admin(Google) - Getting started', 'vip-social-login' ); ?></h3>

<ol>
	<li><?php echo sprintf( esc_html_x( 'Navigate to %1$s', 'Admin(Google) - Getting started', 'vip-social-login'), '<a href="https://console.developers.google.com/apis/" target="_blank">https://console.developers.google.com/apis/</a>' ); ?></li>
	<li><?php echo esc_html_x( 'Log in with your Google credentials if you are not logged in', 'Admin(Google) - Getting started', 'vip-social-login' ); ?></li>
	<li><?php echo esc_html_x( 'If you don\'t have a project yet, you\'ll need to create one. You can do this by clicking on the blue "Create project" button on the right side', 'Admin(Google) - Getting started', 'vip-social-login' ); ?></li>
	<li><?php echo esc_html_x( 'Name your project and then click on the Create button', 'Admin(Google) - Getting started', 'vip-social-login' ); ?></li>
	<li><?php echo esc_html_x( 'Once you have a project, you\'ll end up in the dashboard.', 'Admin(Google) - Getting started', 'vip-social-login' ); ?></li>
	<li><?php echo esc_html_x( 'Click on the "Credentials" in the left hand menu to create new API credentials', 'Admin(Google) - Getting started', 'vip-social-login' ); ?></li>
	<li><?php echo esc_html_x( 'Go to the OAuth consent screen tab and enter a product name and provide the Privacy Policy URL, then click on the save button.', 'Admin(Google) - Getting started', 'vip-social-login' ); ?></li>
	<li><?php echo esc_html_x( 'Go back to the Credentials tab and locate the small box at the middle. Click on the blue "Create credentials" button. Chose the "OAuth client ID" from the dropdown list.', 'Admin(Google) - Getting started', 'vip-social-login' ); ?></li>
	<li><?php echo esc_html_x( 'Your application type should be "Web application"', 'Admin(Google) - Getting started', 'vip-social-login' ); ?></li>
	<li><?php echo esc_html_x( 'Name your application', 'Admin(Google) - Getting started', 'vip-social-login' ); ?></li>
	<li><?php echo sprintf( esc_html_x( 'Add the following URL to the "Authorised redirect URIs" field: %1$s', 'Admin(Google) - Getting started', 'vip-social-login' ), '<strong>' . esc_url( wp_login_url() . '?vip_social_login_provider=google' ) . '</strong>' ); ?></li>
	<li><?php echo esc_html_x( 'Click on the Create button', 'Admin(Google) - Getting started', 'vip-social-login' ); ?></li>
	<li><?php echo esc_html_x( 'A modal should pop up with your credentials. If that doesn\'t happen, go to the Credentials in the left hand menu and select your app by clicking on its name and you\'ll be able to copy-paste the Client ID and Client Secret from there.', 'Admin(Google) - Getting started', 'vip-social-login' ); ?></li>
</ol>

<a href="<?php echo esc_url( admin_url( 'options-general.php?page=vip-social-login&view=providers&subview=google&tab=settings' ) ); ?>" class="button-primary"><?php echo esc_html_x( 'I am done setting up my Google App', 'Admin(Google) - Getting started', 'vip-social-login' ); ?></a>

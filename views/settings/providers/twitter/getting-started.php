<p style="max-width:55em;"><?php echo esc_html_x( 'To allow your visitors to log in with their Twitter account, first you must create a Twitter App. The following guide will help you through the Twitter App creation process. After you have created your Twitter App, head over to "Settings" and configure the given "Consumer Key" and "Consumer Secret" according to your Twitter App.', 'Admin(Twitter) - Getting started', 'vip-social-login' ); ?></p>

<h3><?php echo esc_html_x( 'Create Twitter App', 'Admin(Twitter) - Getting started', 'vip-social-login' ); ?></h3>

<ol>
	<li><?php echo sprintf( esc_html_x( 'Navigate to %1$s', 'Admin(Twitter) - Getting started', 'vip-social-login'), '<a href="https://apps.twitter.com/" target="_blank">https://apps.twitter.com/</a>' ); ?></li>
	<li><?php echo esc_html_x( 'Log in with your Twitter credentials if you are not logged in', 'Admin(Twitter) - Getting started', 'vip-social-login' ); ?></li>
	<li><?php echo esc_html_x( 'Click on the "Create New App" button', 'Admin(Twitter) - Getting started', 'vip-social-login' ); ?></li>
	<li><?php echo sprintf( esc_html_x( 'Fill the name and description fields. Then enter your site\'s URL to the Website field: %1$s', 'Admin(Twitter) - Getting started', 'vip-social-login' ), '<strong>' . esc_url( home_url('/') ) . '</strong>' ); ?></li>
	<li><?php echo sprintf( esc_html_x( 'Add the following URL to the "Callback URL" field: %1$s', 'Admin(Twitter) - Getting started', 'vip-social-login' ), '<strong>' . esc_url( wp_login_url() ) . '</strong>' ); ?></li>
	<li><?php echo esc_html_x( 'Accept the Twitter Developer Agreement', 'Admin(Twitter) - Getting started', 'vip-social-login' ); ?></li>
	<li><?php echo esc_html_x( 'Create your application by clicking on the Create your Twitter application button', 'Admin(Twitter) - Getting started', 'vip-social-login' ); ?></li>
	<li><?php echo esc_html_x( 'Go to the Keys and Access Tokens tab and find the Consumer Key and Secret"', 'Admin(Twitter) - Getting started', 'vip-social-login' ); ?></li>
</ol>

<a href="<?php echo esc_url( admin_url( 'options-general.php?page=vip-social-login&view=providers&subview=twitter&tab=settings' ) ); ?>" class="button-primary"><?php echo esc_html_x( 'I am done setting up my Twitter App', 'Admin(Twitter) - Getting started', 'vip-social-login' ); ?></a>

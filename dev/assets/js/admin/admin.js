(function( $ ) {
	'use strict';

	function displayUpdatingNotice() {
		var template = wp.template( 'updating' );
		$('.vip-social-login-admin').append( template() );
		$('.vip-social-login-update-indicator').fadeIn();
	}

	function finishedUpdating() {
		$('.vip-social-login-update-indicator span').remove();
		$('.vip-social-login-update-indicator em').html( TK.i18n.settings_updated );
		$('.vip-social-login-update-indicator').delay(2000).fadeOut( function() {
			$(this).remove();
		});
	}

	$(function() {
		$('.vip-social-login-toggle input').change(function() {
			var nonce = $(this).data('nonce'),
					provider = $(this).data('provider'),
					checked = $(this).is(":checked");

			displayUpdatingNotice();

			$.post(
				ajaxurl,
				{
					action: 'vip-social-login-update-providers',
					nonce: nonce,
					provider: provider,
					checked: checked
				},
				function( response ) {
					if ( response.sucess ) {
						console.log('success');
					}
					finishedUpdating();
				}
			);
		});
	});
})( jQuery );

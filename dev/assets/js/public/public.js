(function( $ ) {
	function create_or_login_user( provider, uid, name, email, birthday ) {
		$.post(
			VSL.admin_ajax,
			{
		  	action: 'vsl_connect_network',
		  	nonce: VSL.nonce,
		  	provider: provider,
		  	uid: uid,
		  	name: name,
		  	email: email,
		  	birthday: birthday,
		  },
		  function(res) {
		  	if ( ! res.success && res.data.redirect ) {
		  		window.location.href = res.data.redirect;
		  		return;
		  	}

		  	window.location.reload();
		  }
		);
	}
	$(function() {
		$.ajaxSetup({ cache: true });
	  $.getScript('https://connect.facebook.net/en_US/sdk.js', function(){
	    FB.init({
	      appId: '171869386841063',
	      version: 'v3.1'
	    });
	  });


		if ( VSL.current_user_id ) {
			$('.vsl-connected').on( 'click', function( e ) {
				e.preventDefault();
				$.post(
					VSL.admin_ajax,
					{
				  	action: 'vsl_disconnect_network',
				  	nonce: VSL.nonce,
				  	provider: $(this).data('provider')
				  },
				  function( res ) {
				  	window.location.reload();
				  }
				);
			});
		}
		$('.vsl-provider').on( 'click', function( e ) {
			e.preventDefault();
			// var url = $(this).attr('href');
			// window.open( url, 'popUpWindow', 'height=410,width=620,left=10,top=10,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no,status=yes');
			var provider = $(this).data('provider');

			switch(provider) {
				case 'facebook':
					if ( ! window.FB ) {
						return;
					}
					FB.login(function(response){
						if ( response.authResponse ) {
							FB.api('/me', {fields: 'name,email,birthday'}, function(response) {
								if ( response && ! response.error ) {
									var uid = response.id
									var name = response.name || '';
									var email = response.email || '';
									var birthday = response.birthday || '';
									create_or_login_user( provider, uid, name, email, birthday );
								}
							});
						}
					}, {scope: 'public_profile,email,user_birthday'});
					break;
			}
		});
	});
})(jQuery);

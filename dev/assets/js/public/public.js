(function( $ ) {
	$(function() {
		if ( VSL.current_user_id ) {
			$('.vsl-connected').on( 'click', function( e ) {
				e.preventDefault();
				$.post(
					VSL.admin_ajax,
					{
				  	action: 'vsl_disconnect_network',
				  	nonce: VSL.nonce,
				  	user_id: VSL.current_user_id,
				  	provider: $(this).data('provider')
				  },
				  function( res ) {
				  	window.location.reload();
				  }
				);
			});
			$('.vsl-provider').on( 'click', function( e ) {
				e.preventDefault();
				var url = $(this).attr('href');
				window.open( url, 'popUpWindow', 'height=410,width=620,left=10,top=10,resizable=yes,scrollbars=yes,toolbar=yes,menubar=no,location=no,directories=no,status=yes');
			});
		}
	});
})(jQuery);

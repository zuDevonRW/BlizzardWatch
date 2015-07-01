
function bw_insert_ad( unit ) {
	jQuery(document).ready( function($) {
		var data = { 
			'action': 'bw_ajax_get_ad_code',
			'ad_unit': unit
		};

		jQuery.post( '/wp-admin/admin-ajax.php', data, function(response) {
			console.log( response );
			jQuery('#'+response.ad_unit).replaceWith( response.ad_code );
		} );
	} );
}
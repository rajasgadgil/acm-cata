jQuery(document).ready(function($) {	

	//Click on add coupon
	jQuery( document ).on( "click", ".wwt_add_coupon_button", function() {

		var thisobj		= jQuery( this );
		var coupon_id	= jQuery(this).siblings( '.wwt_coupon_id' ).val();
		var enquiry_id	= jQuery( '#enquiry_id' ).val();

		thisobj.siblings( '.productLoad' ).css( 'display', 'block' );

		var data = { 
						action		:	"wwt_add_coupon_quote_process",
					 	coupon_id	:	coupon_id,
					 	enquiry_id	:	enquiry_id,
				  	};

		jQuery.post( Pep_Quote_Admin.ajaxurl, data, function(response) {
			
			var response_data = JSON.parse( response );

			if( response_data.success ) {
				jQuery( '#wwt_coupon_discount_wrap .wwt_coupon_wrap' ).find( '.wwt_not_found' ).remove();
				jQuery( '#wwt_coupon_discount_wrap .wwt_coupon_wrap' ).append( response_data.html );
			}

			thisobj.siblings( '.productLoad' ).css( 'display', 'none' );
		});
	});

	//Click on remove coupon
	jQuery( document ).on( "click", ".wwt_remove_coupon_button", function() {

		var coupon_id	= jQuery(this).attr( 'data-coupon_id' );
		var enquiry_id	= jQuery( '#enquiry_id' ).val();

		var data = { 
						action		:	"wwt_remove_coupon_quote_process",
					 	coupon_id	:	coupon_id,
					 	enquiry_id	:	enquiry_id,
				  	};

		jQuery.post( Pep_Quote_Admin.ajaxurl, data, function(response) {
			
			var response_data = JSON.parse( response );

			if( response_data.success && response_data.html ) {
				jQuery( '#wwt_coupon_discount_wrap tbody' ).append( response_data.html );				
			}
		});
	});

	//Click on edit button
	$( document ).on( 'click', '.pep_quote_edit_quote_button', function() {

		var main_wrap = $(this).parents( '#editCustomerData' );
		main_wrap.find( 'input[type="text"], input[type="email"]' ).prop( "disabled", false );
		main_wrap.find( '.wdm-user-custom-info label[alt="Enquiry Language"]' ).siblings('input[type="text"]').prop( "disabled", true );
		$(this).hide().siblings( '.pep_quote_save_quote_button' ).show();
	});

	//Click on save button
	$( document ).on( 'click', '.pep_quote_save_quote_button', function() {

		var main_wrap = $(this).parents( '#editCustomerData' );
		var enquiry_id 	= $( '#enquiry_id' ).val();
		var form_fields	= {};
		form_fields['custom_fields']	= {};

		main_wrap.find( 'input[type="text"], input[type="email"]' ).each( function(){
			if( $(this).hasClass( 'wdm-input-custom-info' ) && $(this).siblings( 'label' ).attr( 'alt' ) != 'Enquiry Language' ) {
				form_fields['custom_fields'][$(this).siblings( 'label' ).attr( 'alt' )] = $(this).val();
			} else {
				form_fields[$(this).attr( 'name' )] = $(this).val()				
			}
		});

		$(this).siblings( '.load-ajax' ).css( 'display', 'inline-block' );

		var data = {
			action 	   	: 'pep_quote_save_quote_options',
			enquiry_id  : enquiry_id,
			form_fields : form_fields
		};

		$.post( Pep_Quote_Admin.ajaxurl, data, function( response ) {

			var response = JSON.parse( response );

			main_wrap.find( '.load-ajax' ).hide();
			main_wrap.find( '.pep_quote_save_quote_button' ).hide();
			main_wrap.find( '.pep_quote_edit_quote_button' ).show();
			main_wrap.find( 'input[type="text"], input[type="email"]' ).prop( "disabled", true );
		});
	});
	
	$(document).on('click', '.quoteup-add-coupon-button', function(){
		$(this).closest('.productLoad').addClass('loading');
		alert($(this).val());
		var data = {
			action 	   	: 'pep_quote_save_quote_options',
			enquiry_id  : enquiry_id,
			form_fields : form_fields
		};
		
	});
});
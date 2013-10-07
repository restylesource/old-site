var user_archives_link;

function redirect(){
	document.location = user_archives_link;
	
}

jQuery( '#register_form' ).ready( function(){
	jQuery( '#register_form' ).submit( function( event ){
		jQuery.ajax({
			url: MyAjax.ajaxurl,
			data: '&action=cosmo_register&'+jQuery( '#register_form' ).serialize(),
			type: 'POST',
			cache: false,
			success: function (data) {
				json = eval("(" + data + ")");

				if(json['status'] && json['status'] == 'success'){
					if(json['url']){
						user_archives_link = json['url'];	
					}
					
					jQuery( '#registration_error' ).removeClass( 'login-error' );
					jQuery( '#registration_error' ).addClass( 'login-success' );
					jQuery( '#registration_error' ).html( json['msg'] ).fadeIn();
					setTimeout( redirect , 1000 );
				}else{
					jQuery( '#registration_error' ).html( json['msg'] ).fadeIn();
				}

				
			}
		});
	event.preventDefault();
	});
});

		
jQuery( '#cosmo-loginform' ).ready( function(){
	jQuery( '#cosmo-loginform' ).submit( function(event){
        jQuery( '#ajax-indicator' ).show();
		jQuery.ajax({
			url: MyAjax.ajaxurl,
			data: '&action=cosmo_login&'+jQuery( '#cosmo-loginform' ).serialize(),
			type: 'POST',
			cache: false,
			success: function (data) {
                jQuery( '#ajax-indicator' ).hide();
                json = eval("(" + data + ")");

                if(json['status'] && json['status'] == 'success'){
                	user_archives_link = json['url'];
                	jQuery( '#registration_error' ).removeClass( 'login-error' );
					jQuery( '#registration_error' ).addClass( 'login-success' );
                    
					jQuery( '#registration_error' ).html( json['msg'] ).fadeIn();
					setTimeout( redirect , 1000 );
                }else{
                	jQuery( '#registration_error' ).html( json['msg'] ).fadeIn();
                }

				
			}
		});
		event.preventDefault();
	});
});

jQuery( '#lostpasswordform' ).ready( function(){
	jQuery( '#lostpasswordform' ).submit( function(){
		//jQuery( '#registration_error' ).html(  'Please check your email'  ).fadeIn();
		jQuery( '#registration_error' ).html( login_localize.check_email ).fadeIn();
		
	});
});

function get_login_box(action){ 
	jQuery('.not_logged_msg').fadeOut();

	if(jQuery('.login_box').is(':hidden')){
		//jQuery('.login_box').removeClass("hide"); //show login box
		jQuery('.login_box').height(jQuery('.login_box').height() + 'px');
		jQuery('.login_box').slideToggle(1000, 'easeInOutCubic', function(){
				jQuery('.login_box').animate({height: jQuery('.login_box .login').height() + 'px'}, function(){
					jQuery('.login_box').height('auto');
				});
			});

	}
	//if(action != ''){
		jQuery('.'+action+'_warning').fadeIn();		
		jQuery('body,html').animate({scrollTop:0},300);
	//}

	//e.preventDefault();
}
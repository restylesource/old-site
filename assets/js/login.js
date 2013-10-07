$(document).ready(function() {

	// Login Dialog
	$("#login-link").dialogAjax({
		dialogClass: "what-is-restyle",
		width: "auto",
		buttons: {
			Register: {
				text: "Login",
				"class":'button primary',
				click: function() {
					loadingIndicator.fadeIn(200);
					
					var str = $("#login-form").serialize();
					
					$.ajax({  
					  type: "POST", 
					  cache: false,
						  dataType: "json", 
					  url: "/ajax/ajax-login-process.php",  
					  data: str,  
					  success: function(data) {  
					  
						if(data.user_id>0){
							location.reload();
						} else {
							$('#login-error').html(data.error);
							loadingIndicator.fadeOut(200);
						}
					  }  
					});
					
				}
			}
		}
	});

	// Add to Style File
	$(".add-to-sf").click(function(e) {
		e.preventDefault();
		var type = $(this).attr('data-type');
		var id = $(this).attr('data-id');
	
		var sf_icon = $(this);
	
		$.ajax({
		  url: '/ajax/ajax-functions.php',
		  type: "POST",
		  data: { function: "add_to_sf_file", type: $(this).attr('data-type'), id: $(this).attr('data-id') },
		  success: function(data) {
		  	if(data == 1){
		  		sf_icon.addClass('liked');
		  	}	
		  },
		  error: function(xhr, ajaxOptions, thrownError){
		  	if(xhr.status == 401){
		  		$("#login-link").click();
		  	}
		  }
		});
	});
	
	$('map area').live('click', function() {
   		var state = $(this).attr('data-state');
   		$('#location_state').val(state);
   		$.uniform.update();
   		$('form.location').submit();
   			
  	});
  	
  	$(".change-location").dialogAjax({
			dialogClass: "location",
			width: "700",
			buttons: {
				Submit: {
					text: "Submit",
					"class":'button primary',
					click: function() {
						loadingIndicator.fadeIn(200);
						// Uncomment the following 2 lines once loading is done
						// $( this ).dialog( "close" );
						// dialog.remove();
						$('form.location').submit();
					}
				}
			}
	});
	
});
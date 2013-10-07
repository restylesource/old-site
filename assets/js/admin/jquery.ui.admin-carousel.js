(function($){
 
    $.fn.extend({ 
         
        //pass the options variable to the function
        adminCarousel: function(options) {

            //Set the default values, use comma to separate the settings, example:
            var defaults = {
                width: 650,
                height: 900,
                resize:false,
                modal: true,
                title: false,
                dialogClass: "adminAjax",
                position: 'center center',
                buttonPosition: "left bottom",
                buttonText: "Edit",
                buttonOffset: "0 0",
                editType: "image-carousel"
            }
                 
            var options =  $.extend(defaults, options);
 
            return this.each(function(e) {
                var o = options;

                var obj = $(this);

                var $editButton = $( "<a />", {
                    class: "button-admin " + o.buttonPosition
                }).attr({ "href" : "ajax-retailer-postings1.html" }).css({ "margin" : o.buttonOffset }).text(o.buttonText).appendTo(obj);

				// Kevin added .php instead of .html
				
				if(typeof(source_id) !== 'undefined'){
					var querystring = "source_id=" + source_id;
				} else if (typeof(page_id) !== 'undefined'){
					var querystring = "id=" + page_id;
				}
				
                var url = "/ajax/ajax-admin-edit-" + o.editType + ".php?" + querystring;

                $editButton.click(function(e) {

                    if ( $.attrFn ) { $.attrFn.text = true; }

                    var dialog = $('<div style="display:none" class="loading"></div>').appendTo('body'),
                    dialogWrapper = $('<div class="ajax-content-wrapper"></div>').appendTo(dialog);

                    dialog.dialog({
                        width: o.width,
                        height: o.height,
                        resize:o.resize,
                        modal: o.modal,
                        title: o.title,
                        dialogClass: "adminAjax " + o.dialogClass,
                        position: o.position,
                        buttons: {
                            Update: {
                                text: "Update",
                                "class":'button primary',
                                click: function() {
                                    
                                    //loadingIndicator.fadeIn(200);

									// Kevin starts here...submit form
									$('#upload').submit();
									// Kevin ends here

                                    // Uncomment the following 2 lines once loading is done
                                    // $( this ).dialog( "close" );
                                    // dialog.remove();
                                }
                            },
                            Cancel: {
                                text: "Cancel",
                                "class":'button cancel',
                                click: function() {
                                    $( this ).dialog( "close" );
                                    dialog.remove();
                                }
                            }
                        }
                    });

                    dialogWrapper.load(
                        url+'&image_id='+$editButton.parent().find('a').attr('data-image-nbr'), 
                        {}, // omit this param object to issue a GET request instead a POST request, otherwise you may provide post parameters within the object
                        function (responseText, textStatus, XMLHttpRequest) {
                            // remove the loading class
                            dialog.removeClass('loading');

							// Kevin starts here
							$('#image_id').val($editButton.parent().find('a').attr('data-image-nbr'));
							// Kevin Ends here
				

                            // Position it correctly...
                            // This does not work if the dialog height is greater
                            // than the window because of a jQuery UI bug
                            dialog.dialog("option", "position", ['center', 'center'] );
                        }
                    );

                    $( "body" ).delegate( ".cancel", "click", function() {
                        $( this ).closest( dialog ).dialog( "close" );
                    });

                    e.preventDefault();
                 
                });

                var $deleteButton = $( "<a />", {
                    class: "button-admin delete-image" 
                }).attr({ "href" : "#" }).text("Delete image").appendTo(obj);

                $deleteButton.click(function(e) {
                    $(this).parents(".scroll-item").find("img").fadeOut(500);
                    // Kevin starts here
                    var delete_img_nbr = $(this).parents(".scroll-item").find("a").attr('data-image-nbr');
                	
                	var image = $(this).parents(".scroll-item").find("img");
                	
                	$.ajax({
					  type: "POST",
					  dataType: "json",
					  url: "ajax.php",
					  data: { f : "source_image_delete", id: source_id, delete_img_nbr: delete_img_nbr },
					  dataType: "json",
					 	success: function(data){
							if(data.status == "1"){
								image.fadeOut(500);
							} else {
								alert( "Error: " + data.status );
							}
					 	}
					});
					// Kevin Ends here
                    
                    e.preventDefault();
                });

            });
        }
    });
     
})(jQuery);
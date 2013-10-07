(function($){
 
    $.fn.extend({ 
         
        //pass the options variable to the function
        admin: function(options) {

            //Set the default values, use comma to separate the settings, example:
            var defaults = {
                width: 650,
                height: 900,
                resize:false,
                modal: true,
                title: false,
                dialogClass: "",
                position: 'center center',
                buttonPosition: "left bottom",
                buttonText: "Edit",
                buttonOffset: "0 0",
                editType: "image",
                extraOptions: false,
                pageBlockId: 0
            }
                 
            var options =  $.extend(defaults, options);
 
            return this.each(function(e) {
                var o = options;

                var obj = $(this);

                var $button = $( "<a />", {
                    class: "button-admin " + o.buttonPosition
                }).attr({ "href" : "ajax.html" }).css({ "margin" : o.buttonOffset }).text(o.buttonText).appendTo(obj);

				if(typeof(source_id) !== 'undefined'){
					var querystring = "source_id=" + source_id;
				} else if (typeof(page_id) !== 'undefined'){
					var querystring = "id=" + page_id;
					
					if(obj.attr('data-page-block-id') !== "undefined"){
						querystring = querystring + "&page_block_id=" + obj.attr('data-page-block-id');
					}
					
				}

                var url = "/ajax/ajax-admin-edit-" + o.editType + ".php?"+querystring; 

                $button.click(function(e) {

                    if ( $.attrFn ) { $.attrFn.text = true; }

                    var dialog = $('<div style="display:none" class="loading"></div>').appendTo('body'),
                        dialogWrapper = $('<div class="ajax-content-wrapper"></div>').appendTo(dialog),
                        loadingIndicator = $('<div class="ajax-loading-indicator"></div>').appendTo(dialog).hide();

                    if ( o.extraOptions == true ) {

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
                                                               
                                        $('form').submit();                                 
                                        loadingIndicator.fadeIn(200);

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
                                },
                                Delete: {
                                    text: "Delete",
                                    "class":"button delete",
                                    click: function() {
                                        
                                        var answer = confirm("Are you sure you want to delete?  This can not be undone.");
    									if(answer){
    										$('#action').val('delete');
             	                       		loadingIndicator.fadeIn(200);
                	                        $('form').submit();  
                	                    }
                                    }
                                },
                                MoveUp: {
                                    text: "Move Up",
                                    "class":"button primary moveup",
                                    click: function() {
                                       // alert("move up function");
                                    	$('#action').val('up');
                                    	loadingIndicator.fadeIn(200);
                                    	$('form').submit();  
                                    }
                                },
                                MoveDown: {
                                    text: "Move Down",
                                    "class":"button primary movedown",
                                    click: function() {
                                        //alert("move down function");
                                        $('#action').val('down');
                                    	loadingIndicator.fadeIn(200);
                                    	$('form').submit();  
                                    }
                                }
                            }
                        });

                    } // extraOptions == true

                    if ( o.extraOptions == false ) {

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
                                                               
                                        $('form').submit();                                 
                                        loadingIndicator.fadeIn(200);

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

                    } // extraOptions == true

                    dialogWrapper.load(
                    
                        url,

                        {}, // omit this param object to issue a GET request instead a POST request, otherwise you may provide post parameters within the object
                        function (responseText, textStatus, XMLHttpRequest) {
                            // remove the loading class
                            dialog.removeClass('loading');                            
                            //$('#id').val(source_id);

                            // Position it correctly...
                            var dialogParent = dialog.parents(".ui-dialog");
                            if (dialogParent.height() > $(window).height()) {
                                dialog.height($(window).height()*0.7);
                            }
                            dialog.dialog("option", "position", ['center', 'center'] );
                        }
                    );

                    $( "body" ).delegate( ".cancel", "click", function() {
                        $( this ).closest( dialog ).dialog( "close" );
                    });

                    e.preventDefault();

                    // Resize the height of the modal if this
                    // is a large modal
                    $( window ).resize( function() {
                        var dialogParent = dialog.parents(".ui-dialog");
                        if (dialogParent.height() > $(window).height()) {
                            dialog.height($(window).height()*0.7);
                        }
                        dialog.dialog("option", "position", ['center', 'center'] );
                    });
                 
                });

            });
        }
    });
     
})(jQuery);
(function($){
 
    $.fn.extend({ 
         
        //pass the options variable to the function
        dialogAjax: function(options) {

            //Set the default values, use comma to separate the settings, example:
            var defaults = {
                width: 650,
                height: "auto",
                resize:false,
                modal: true,
                title: false,
                dialogClass: "",
                position: 'center center',
                maxHeight: 900,
                largeModal: true,
                buttons: {
                    Update: {
                        text: "Update",
                        "class":'button primary',
                        click: function() {
                            $( this ).dialog( "close" );
                            dialog.remove();
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
            }
                 
            var options =  $.extend(defaults, options);
 
            return this.each(function(e) {
                var o = options;

                var obj = $(this);

                var url = this.href;

                $(obj).click(function(e) {

                    var dialog = $('<div style="display:none" class="loading"></div>').hide().appendTo('body'),
                    dialogWrapper = $('<div class="ajax-content-wrapper"></div>').appendTo(dialog);
                    loadingIndicator = $('<div class="ajax-loading-indicator"></div>').appendTo(dialog).hide();

                    dialog.dialog({
                        width: o.width,
                        height: o.height,
                        resize:o.resize,
                        modal: o.modal,
                        title: o.title,
                        dialogClass: "dialogAjax " + o.dialogClass,
                        position: o.position,
                        buttons: o.buttons,
                        // add a close listener to prevent adding multiple divs to the document
                        close: function(event, ui) {
                            // remove div with all data and events
                            dialog.remove();
                        }
                    }).css({ "maxHeight" : o.maxHeight });

                    // Position it correctly...
                    // This does not work if the dialog height is greater
                    // than the window because of a jQuery UI bug
                    dialog.dialog("option", "position", ['center', 'center'] );

                    dialogWrapper.load(
                        url, 
                        {}, // omit this param object to issue a GET request instead a POST request, otherwise you may provide post parameters within the object
                        function (responseText, textStatus, XMLHttpRequest) {
                            // remove the loading class
                            dialog.removeClass('loading');

                            // Position it correctly...
                            var dialogParent = dialog.parents(".ui-dialog");
                            if (dialogParent.height() > $(window).height()) {
                                dialog.height($(window).height()*0.7);
                            }
                            dialog.dialog("option", "position", ['center', 'center'] );
                        }
                    );

                    e.preventDefault();

                    // Resize the height of the modal if this
                    // is a large modal
                    $( window ).resize( function() {
                        // Position it correctly...
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
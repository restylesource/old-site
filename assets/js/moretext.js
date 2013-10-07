(function($){
 
    $.fn.extend({ 
         
        //pass the options variable to the function
        moreText: function(options) {

            //Set the default values, use comma to separate the settings, example:
            var defaults = {
            }
                 
            var options =  $.extend(defaults, options);
 
            return this.each(function(e) {
                var o = options;
                var obj = $(this);
                var moreText = obj.parents( ".block" ).find( ".more-text" );

                moreText.hide();

                obj.click(function(e) {

                    if ( moreText.is( ":hidden" ) ) {

                        moreText.show();
                        obj.text( "< Less" );

                    } else {
                        
                        moreText.hide();
                        obj.text( "More >" );

                    }

                    e.preventDefault();
                 
                });

            });
        }
    });
     
})(jQuery);
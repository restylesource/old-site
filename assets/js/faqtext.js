(function($){
 
    $.fn.extend({ 
         
        //pass the options variable to the function
        faqText: function(options) {

            //Set the default values, use comma to separate the settings, example:
            var defaults = {
            }
                 
            var options =  $.extend(defaults, options);
 
            return this.each(function(e) {
                var o = options;
                var obj = $(this);
                var faqText = obj.parents( ".faqblock" ).find( ".faq-text" );

                faqText.hide();

                obj.click(function(e) {

                    if ( faqText.is( ":hidden" ) ) {

                        faqText.show();
						obj.addClass("active");

                    } else {
                        
                        faqText.hide();
						obj.removeClass("active");

                    }

                    e.preventDefault();
                 
                });

            });
        }
    });
     
})(jQuery);
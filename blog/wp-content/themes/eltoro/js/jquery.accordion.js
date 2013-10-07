/*
 * jQuery UI Multilevel saccordion v.1
 * 
 * Copyright (c) 2011 Pieter Pareit
 *
 * http://www.scriptbreaker.com
 *
 * Modified by Stefan Ciorici @ CosmoThemes
 */

//plugin definition
(function($){
    $.fn.extend({

    //pass the options variable to the function
    saccordion: function(options) {
        
		var defaults = {
			saccordion: 'true',
			speed: 300,
			closedSign: '',
			openedSign: ''
		};

		// Extend our default options with those provided.
		var opts = $.extend(defaults, options);
		//Assign current element to variable, in this case is UL element
 		var $this = $(this);
 		
 		//add a mark [+] to a multilevel menu
 		$this.find("li").each(function() {
 			if($(this).find("ul").size() != 0){
 				//add the multilevel sign next to the link
 				$(this).find("ul:first").parent().prepend("<div class='mobile-menu-toggler mclosed'>"+ opts.closedSign +"</div>");
 				
 			}
 		});

 		//open active level
 		$this.find("li.active").each(function() {
 			$(this).parents("ul").slideDown(opts.speed);
 			$(this).parents("ul").parent("li").find("div:first").html(opts.openedSign);
 		});

  		$this.find("li div").click(function() {
  			if($(this).parent().find("ul").size() != 0){
  				if(opts.saccordion){
  					//Do nothing when the list is open
  					if(!$(this).parent().find("ul").is(':visible')){
  						parents = $(this).parent().parents("ul");
  						visible = $this.find("ul:visible");
  						visible.each(function(visibleIndex){
  							var close = true;
  							parents.each(function(parentIndex){
  								if(parents[parentIndex] == visible[visibleIndex]){
  									close = false;
  									return false;
  								}
  							});
  							if(close){
  								if($(this).parent().find("ul") != visible[visibleIndex]){
  									$(visible[visibleIndex]).slideUp(opts.speed, function(){
  										$(this).parent("li").find("div:first").html(opts.closedSign);
  									});
  									
  								}
  							}
  						});
  					}
  				}
  				if($(this).parent().find("ul:first").is(":visible")){
  					$(this).parent().find("ul:first").slideUp(opts.speed, function(){
  						$(this).parent("li").find("div:first").delay(opts.speed).html(opts.closedSign);
               $(this).parent("li").find("div:first").toggleClass('mclosed');
  					});
  					
  					
  				}else{
  					$(this).parent().find("ul:first").slideDown(opts.speed, function(){
  						$(this).parent("li").find("div:first").delay(opts.speed).html(opts.openedSign);
              $(this).parent("li").find("div:first").toggleClass('mclosed');
  					});
  				}
  			}
  		});
    }
});
})(jQuery);
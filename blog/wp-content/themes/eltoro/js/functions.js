
function setCookie(c_name,value,exdays)
{
	var exdate=new Date();
	exdate.setDate(exdate.getDate() + exdays);
	var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
	document.cookie=c_name + "=" + c_value;
}

function resizeVideo(){
	if(jQuery('.embedded_videos').length){
  		var iframe_width = jQuery('.embedded_videos').parent().width();
  		var_iframe_height = iframe_width/1.37;	
  		jQuery('.embedded_videos iframe ').each(function(){

  			jQuery(this).attr('width',iframe_width);
  			jQuery(this).attr('height',var_iframe_height);
  		});

  		jQuery('.embedded_videos div.video-js ').each(function(){

  			jQuery(this).attr('width',iframe_width);
  			jQuery(this).attr('height',var_iframe_height);
  			jQuery(this).css('width',iframe_width);
  			jQuery(this).css('height',var_iframe_height);
  		});
  		

  	}
}

jQuery(window).on('resize load orientationChanged', function() {
	// do your stuff here
	if(jQuery(this).width() < 767){
	    jQuery('#d-menu').addClass('hide');
	    jQuery('.mobile-menu').removeClass('hide');
	    jQuery('.mobile-menu').css('display','block');
	    jQuery('.ourcarousel').jcarousel({
	      scroll: 1,
	      wrap: 'last'
	    }).css( 'visibility', 'visible' );
	} else{
	    jQuery('#d-menu').removeClass('hide');
	    jQuery('.mobile-menu').css('display','none');
	    jQuery('.mobile-menu').addClass('hide');

      /*jQuery('.ourcarousel').jcarousel({
        scroll: 3,
        wrap: 'last'
      }).css( 'visibility', 'visible' );*/

      jQuery('.ourcarousel').each(function(){ 
          
          jQuery('#'+jQuery(this).attr('id')).jcarousel({
              scroll: eval(jQuery(this).attr('columns')),
              wrap: 'last'
          }).css( 'visibility', 'visible' );  
      });
	    
	}
});


jQuery( window ).load( function(){
  jQuery(function() {
      if( /Android|webOS|Dolphin|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
       
      }else{
        jQuery(".scrollit").each( function(index,elem){
          jQuery( elem ).jScroll({top : 82});
        });
      }
  });

    //slideshow single
    jQuery('#slides_single').orbit({
        animation: 'fade',
        animationSpeed: 1200
    });
    jQuery( '#slides_single').parents( '.orbit-wrapper').find( '.slider-nav' ).hide();
    jQuery( '#slides_single').parents( '.orbit-wrapper').hover( function(){
        jQuery( '#slides_single').parents( '.orbit-wrapper').find( '.slider-nav' ).show();
    });
    jQuery( '#slides_single').parents( '.orbit-wrapper').mouseleave( function(){
        jQuery( '#slides_single').parents( '.orbit-wrapper').find( '.slider-nav' ).hide();
    });
});




(function($) {
    $.fn.sorted = function(customOptions) {
        var options = {
            reversed: false,
            by: function(a) {
                return a.text();
            }
        };
        $.extend(options, customOptions);
    
        $data = jQuery(this);
        arr = $data.get();
        arr.sort(function(a, b) {
            
            var valA = options.by(jQuery(a));
            var valB = options.by(jQuery(b));
            if (options.reversed) {
                return (valA < valB) ? 1 : (valA > valB) ? -1 : 0;              
            } else {        
                return (valA < valB) ? -1 : (valA > valB) ? 1 : 0;  
            }
        });
        return jQuery(arr);
    };

})(jQuery);


/* Thumbs Hover effects here */
function hoverThumbItems(){
  
  function thumbsOnHover(){
    jQuery(this).find('.item-overlay').stop().animate( { opacity: 1 }, 500, 'easeInOutExpo' );
    jQuery(this).stop().find('.item-overlay').find( 'h3' ).stop().animate( { top: '10%',opacity: 1 }, 500, 'easeInOutExpo' );
    jQuery(this).stop().find('.item-overlay').find( 'span' ).stop().animate( { bottom: '10%' ,opacity: 1 }, 500, 'easeInOutExpo' );
  }
  function thumbsOnHoverOut(){
    jQuery(this).find('.item-overlay').stop().animate( { opacity: 0 }, 300, 'easeInOutExpo' );
      jQuery(this).find('.item-overlay').find( 'h3' ).stop().animate( { top: '-100px',opacity: 0 }, 300, 'easeInOutExpo' );
      jQuery(this).find('.item-overlay').find( 'span' ).stop().animate( { bottom: '-100px',opacity: 0 }, 300, 'easeInOutExpo' );
  }
  jQuery(document).ready(function() {
    jQuery('div.hovermore').hover(thumbsOnHover, thumbsOnHoverOut);
  });

}
hoverThumbItems();

/* ###### Filters ##### */

/* thumbs filter */

jQuery(function() {
  

  var read_button = function(class_names) {
    var r = {
      selected: false,
      type: 0
    };
    for (var i=0; i < class_names.length; i++) {
      if (class_names[i].indexOf('selected-') == 0) {
        r.selected = true;
      }
      if (class_names[i].indexOf('segment-') == 0) {
        r.segment = class_names[i].split('-')[1];
      }
    };
    return r;
  };
  
  var determine_sort = function($buttons) {
    var $selected = $buttons.parent().filter('[class*="selected-"]');
    return $selected.find('a').attr('data-value');
  };
  
  var determine_kind = function($buttons) {
    var $selected = $buttons.parent().filter('[class*="selected-"]');
    return $selected.find('a').attr('data-value');
  };
  
  var $preferences = {
    duration: 800,
    easing: 'easeInOutQuad',
    adjustHeight: 'dynamic'
  };
  
  var $list = jQuery('.thumbs-list');
  var $data = $list.clone();
  
  var $controls = jQuery('ul.thumbs-splitter');
  
  $controls.each(function(i) {
    
    var $control = jQuery(this);
    var $buttons = $control.find('a');
    
    $buttons.bind('click', function(e) {
      
      var $button = jQuery(this);
      var $button_container = $button.parent();
      var button_properties = read_button($button_container.attr('class').split(' '));      
      var selected = button_properties.selected;
      var button_segment = button_properties.segment;

      if (!selected) {


        var nrOfButtonsMax = '15';
       for(var i = 0; i<=15; i++)
          nrOfButtonsMax += ' ' + 'selected-' + i;
       $buttons.parent().removeClass(nrOfButtonsMax);


        $buttons.parent().removeClass('selected');
        $button_container.addClass('selected-' + button_segment);
        $button_container.addClass('selected');
        
        var sorting_type = determine_sort($controls.eq(1).find('a'));
        var sorting_kind = determine_kind($controls.eq(0).find('a'));
        
        if (sorting_kind == 'all') {
          var $filtered_data = $data.find('li');
        } else {
          var $filtered_data = $data.find('li.' + sorting_kind);
        }
        
        if (sorting_type == 'size') {
          var $sorted_data = $filtered_data.sorted({
            by: function(v) {
              return parseFloat(jQuery(v).find('span').text());
            }
          });
        } else {
          var $sorted_data = $filtered_data.sorted({
            by: function(v) {
              return jQuery(v).find('strong').text().toLowerCase();
            }
          });
        }
        
        $list.quicksand($sorted_data, $preferences, function() { 
        // callback function
          hoverThumbItems();
        }
        );
        
      }
      
      e.preventDefault();
    });
    
  }); 
 
});
/* grid filter */
jQuery(function() {
  
  var read_button = function(class_names) {
    var r = {
      selected: false,
      type: 0
    };
    for (var i=0; i < class_names.length; i++) {
      if (class_names[i].indexOf('selected-') == 0) {
        r.selected = true;
      }
      if (class_names[i].indexOf('segment-') == 0) {
        r.segment = class_names[i].split('-')[1];
      }
    };
    return r;
  };
  
  var determine_sort = function($buttons) {
    var $selected = $buttons.parent().filter('[class*="selected-"]');
    return $selected.find('a').attr('data-value');
  };
  
  var determine_kind = function($buttons) {
    var $selected = $buttons.parent().filter('[class*="selected-"]');
    return $selected.find('a').attr('data-value');
  };
  
  var $preferences = {
    duration: 800,
    easing: 'easeInOutQuad',
    adjustHeight: 'dynamic'
  };
  
  var $list = jQuery('#grid-list');
  var $data = $list.clone();
  
  var $controls = jQuery('ul.splitter');
  
  $controls.each(function(i) {
    
    var $control = jQuery(this);
    var $buttons = $control.find('a');
    
    $buttons.bind('click', function(e) {
      
      var $button = jQuery(this);
      var $button_container = $button.parent();
      var button_properties = read_button($button_container.attr('class').split(' '));      
      var selected = button_properties.selected;
      var button_segment = button_properties.segment;

      if (!selected) {

        $buttons.parent().removeClass('selected-0').removeClass('selected-1').removeClass('selected-2');
        $buttons.parent().removeClass('selected');
        $button_container.addClass('selected-' + button_segment);
        $button_container.addClass('selected');
        
        var sorting_type = determine_sort($controls.eq(1).find('a'));
        var sorting_kind = determine_kind($controls.eq(0).find('a'));
        
        if (sorting_kind == 'all') {
          var $filtered_data = $data.find('li');
        } else {
          var $filtered_data = $data.find('li.' + sorting_kind);
        }
        
        if (sorting_type == 'size') {
          var $sorted_data = $filtered_data.sorted({
            by: function(v) {
              return parseFloat(jQuery(v).find('span').text());
            }
          });
        } else {
          var $sorted_data = $filtered_data.sorted({
            by: function(v) {
              return jQuery(v).find('strong').text().toLowerCase();
            }
          });
        }
        
        $list.quicksand($sorted_data, $preferences, function(){
          jQuery('.hovermore').mosaic();
        });

      }
      
      e.preventDefault();
    });
    
  }); 
 
});

jQuery(document).ready(function(){

	jQuery('.list-tabs > div').hide(); // Hide all divs
  	jQuery('.list-tabs div:first-child').show(); // Show the first div
  	jQuery('.list-tabs ul li:first').addClass('active'); // Set the class for active state
  	jQuery('.list-tabs ul li a').click(function(){ // When link is clicked
	    var currentTab = jQuery(this).attr('href'); // Set currentTab to value of href attribute
      var parenter = '#'+jQuery(this).parent().parent().parent().parent().parent().attr('id');
	    var par = '#'+jQuery(this).parent().parent().parent().parent().parent().attr('id') + ' > div';
      var div_height = jQuery(parenter).find(currentTab).height();
      jQuery(parenter).find(currentTab).parent().height(div_height);
	    jQuery(par).hide(); // Hide all divs
	    jQuery(parenter).find(currentTab).fadeIn(); // Show div with id equal to variable currentTab
      jQuery(parenter).find(currentTab).parent().height('auto');
	    return false;
  	});

  	jQuery('.big-list-tabs > div').hide(); // Hide all divs
    jQuery('.big-list-tabs div:first-child').show(); // Show the first div
    jQuery('.big-list-tabs ul li:first').addClass('active'); // Set the class for active state
    jQuery('.big-list-tabs footer div ul li a').click(function( event ){ // When link is clicked
	    var currentTab2 = jQuery(this).attr('href'); // Set currentTab to value of href attribute
      var parenter2 = '#'+jQuery(this).parent().parent().parent().parent().parent().parent().attr('id');
	    var par2 = '#'+jQuery(this).parent().parent().parent().parent().parent().parent().attr('id') + ' > div';
	    var div_height = jQuery(parenter2).find(currentTab2).height();
      jQuery(parenter2).find(currentTab2).parent().height(div_height);
      jQuery(par2).hide(); // Hide all divs
      jQuery(parenter2).find(currentTab2).show(); // Show div with id equal to variable currentTab
      jQuery(parenter2).find(currentTab2).parent().height('auto');
	    return false;
    });

jQuery(document).ready(function(){
	jQuery('.hovermore, .readmore, .full-screen, .mosaic-overlay').mosaic();
    jQuery('.circle, .gallery-icon').mosaic({
        opacity:    0.5
    });
    jQuery('.fade').mosaic({
        animation:  'slide'
    });
});

  
  	/*resize FB comments depending on viewport*/

  	setTimeout('viewPort()',3000); 
  	
  	resizeVideo();

  	jQuery( window ).resize( function(){
       viewPort();
       resizeVideo();
    });

  	/*Fixed user bar*/
	jQuery(function () {
		var msie6 = jQuery.browser == 'msie' && jQuery.browser.version < 7;
		if (!msie6 && jQuery('.sticky-bar').length != 0) {
			var top = jQuery('#sticky-bar').offset().top - parseFloat(jQuery('#sticky-bar').css('margin-top').replace(/auto/, 0));
			jQuery(window).scroll(function (event) {
				// what the y position of the scroll is
				var y = jQuery(this).scrollTop();
				// whether that's below the form
				if (y >= top-0) {
					// if so, ad the fixed class
					jQuery('#sticky-bar').addClass('fixed');
				} else {
					// otherwise remove it
					jQuery('#sticky-bar').removeClass('fixed');
				}
			});
		}
	});
	
	/* Accordion */
	jQuery('.cosmo-acc-container').hide();
	jQuery('.cosmo-acc-trigger:first').addClass('active').next().show();
	jQuery('.cosmo-acc-trigger').click(function(){
		if( jQuery(this).next().is(':hidden') ) {
			jQuery('.cosmo-acc-trigger').removeClass('active').next().slideUp();
			jQuery(this).toggleClass('active').next().slideDown();
		}
		return false;
	});
	
	//Superfish menu
  if(jQuery('#access').hasClass('list-menu')){
    menu_min_with = 7;
  } else{
    menu_min_with = 17;
  }

	jQuery("ul.sf-menu").supersubs({
		minWidth:    menu_min_with,
		maxWidth:    32,
		extraWidth:  1
	}).superfish({
		delay: 200,
		speed: 250
	});
	
  jQuery('.replies').click(function(){
    jQuery.scrollTo( '#comments', 1000, { easing:'easeInOutCirc' } );
  });

	/*Fixed user bar*/
	jQuery(function () {
		var msie6 = jQuery.browser == 'msie' && jQuery.browser.version < 7;
		if (!msie6 && jQuery('.sticky-bar').length != 0) {
			var top = jQuery('#sticky-bar').offset().top - parseFloat(jQuery('#sticky-bar').css('margin-top').replace(/auto/, 0));
			jQuery(window).scroll(function (event) {
				// what the y position of the scroll is
				var y = jQuery(this).scrollTop();
				// whether that's below the form
				if (y >= top-0) {
					// if so, ad the fixed class
					jQuery('#sticky-bar').addClass('fixed');
				} else {
					// otherwise remove it
					jQuery('#sticky-bar').removeClass('fixed');
				}
			});
		}
	});
	
	/* Hide Tooltip */
	jQuery(function() {
		jQuery('a.close').click(function() {
			jQuery(jQuery(this).attr('href')).slideUp();
            jQuery.cookie(cookies_prefix + "_tooltip" , 'closed' , {expires: 365, path: '/'});
            jQuery('.header-delimiter').removeClass('hidden');
			return false;
		});
	});
	
	

	/* initialize tabs */
	jQuery(function() { 
		jQuery('.cosmo-tabs').tabs({ fxFade: true, fxSpeed: 'fast' });
		jQuery( 'div.cosmo-tabs' ).not( '.submit' ).find( '.tabs-nav li:first-child a' ).click();
	});
	
	/* Hide title from menu items */
	jQuery(function(){
		jQuery("li.menu-item > a").hover(function(){
			jQuery(this).stop().attr('title', '');},
			function(){jQuery(this).stop().attr();
		});
		
		  
	});
  
  jQuery(document).ready(function() {
      jQuery(".mobile-nav-menu").saccordion({
          saccordion:false,
          speed: 200,
          closedSign: '',
          openedSign: ''
      });
      jQuery('.mobile-nav-menu').css('display','none');
  });
	jQuery( '.toggle-menu' ).click( function(){
        jQuery('.mobile-nav-menu').slideToggle();
    });


	jQuery(window).on('resize load orientationChanged', function() {
	  // do your stuff here
	  if(jQuery(this).width() < 767){
	    jQuery('#access').addClass('hide');
	    jQuery('#d-menu').addClass('hide');
	    jQuery('.mobile-menu').removeClass('hide');
	    jQuery('.mobile-menu').css('display','block');
	  } else{
	    jQuery('#access').removeClass('hide');
	    jQuery('#d-menu').removeClass('hide');
	    jQuery('.mobile-menu').css('display','none');
	    jQuery('.mobile-menu').addClass('hide');
	  }
	});
	
	jQuery(document).ready(function() {
		jQuery('aside.widget').append('<div class="clear"></div>');
	});

	/* Mobile responsiveness */
	jQuery(window).on('resize load orientationChanged', function() {
	  // do your stuff here
	  if(jQuery(this).width() < 767){
	    jQuery('#d-menu').addClass('hide');
	    jQuery('.mobile-menu').removeClass('hide');
      jQuery('.mobile-menu').css('display','block');
      jQuery('#sticky-bar').css('display','none');
      jQuery('.keyboard-demo').css('display','none');
      jQuery('#menu-login').css('display','none');
	  } else{
	    jQuery('#d-menu').removeClass('hide');
	    jQuery('.mobile-menu').css('display','none');
	    jQuery('.mobile-menu').addClass('hide');
	    jQuery('#sticky-bar').css('display','block');
	    jQuery('.keyboard-demo').css('display','block');
	    jQuery('#menu-login').css('display','block');
	  }
	});

	/* twitter widget */
	if (jQuery().slides) {
		jQuery(".dynamic .cosmo_twitter").slides({
			play: 5000,
			effect: 'fade',
			generatePagination: false,
			autoHeight: true
		});
	}
	
	/* show/hide color switcher */
	jQuery('.show_colors').toggle(function(){
		jQuery(".style_switcher").animate({
		    left: "10px"

		  }, 500 );
	}, function () {
		jQuery(".style_switcher").animate({
		    left: "-152px"

		  }, 500 );

	});
	
	 /* widget tabber */
    jQuery( 'ul.widget_tabber li a' ).click(function(){
        jQuery(this).parent('li').parent('ul').find('li').removeClass('active');
        jQuery(this).parent('li').parent('ul').parent('div').find( 'div.tab_menu_content.tabs-container').fadeTo( 200 , 0 );
        jQuery(this).parent('li').parent('ul').parent('div').find( 'div.tab_menu_content.tabs-container').hide();
        jQuery( jQuery( this ).attr('href') + '_panel' ).fadeTo( 600 , 1 );
        jQuery( this ).parent('li').addClass('active');
    });
	
		
	/*toogle*/
	/*Case when by default the toggle is closed */
	jQuery(".open_title").toggle(function(){ 
			jQuery(this).next('div').slideDown();
			jQuery(this).find('a').removeClass('show');
			jQuery(this).find('a').addClass('toggle_close'); 
			jQuery(this).find('.title_closed').hide();
			jQuery(this).find('.title_open').show();
		}, function () {
		
			jQuery(this).next('div').slideUp();
			jQuery(this).find('a').removeClass('toggle_close');
			jQuery(this).find('a').addClass('show');		 
			jQuery(this).find('.title_open').hide();
			jQuery(this).find('.title_closed').show();
			
	});
	
	/*Case when by default the toggle is oppened */		
	jQuery(".close_title").toggle(function(){ 
			jQuery(this).next('div').slideUp();
			jQuery(this).find('a').removeClass('toggle_close');
			jQuery(this).find('a').addClass('show');		 
			jQuery(this).find('.title_open').hide();
			jQuery(this).find('.title_closed').show();
		}, function () {
			jQuery(this).next('div').slideDown();
			jQuery(this).find('a').removeClass('show');
			jQuery(this).find('a').addClass('toggle_close');
			jQuery(this).find('.title_closed').hide();
			jQuery(this).find('.title_open').show();
			
	});	
	
	/*Accordion*/
	jQuery('.cosmo-acc-container').hide();
	jQuery('.cosmo-acc-trigger:first').addClass('active').next().show();
	jQuery('.cosmo-acc-trigger').click(function(){
		if( jQuery(this).next().is(':hidden') ) {
			jQuery('.cosmo-acc-trigger').removeClass('active').next().slideUp();
			jQuery(this).toggleClass('active').next().slideDown();
		}
		return false;
	}); 
	
	//Scroll to top
	jQuery(window).scroll(function() {
		if(jQuery(this).scrollTop() != 0) {
			jQuery('#toTop').fadeIn();	
		} else {
			jQuery('#toTop').fadeOut();
		}
	});
	jQuery('#toTop').click(function() {
		jQuery('body,html').animate({scrollTop:0},300);
	});
	
	jQuery('div.sticky-bar li.my-add').mouseover(function () {
		jQuery('.show-first').hide();
		jQuery('.show-hover').fadeIn(10);
	});
	
	jQuery('#sticky-bar').mouseleave(function () {
		jQuery('.show-hover').hide(); 
		//jQuery('.show-first').show('slow');
		jQuery('.show-first').fadeIn(10);
	});


  //testimonials
    if (jQuery().slides) {
        jQuery(".cosmo-testimonials").slides({
            play: 0,
            //generateNextPrev: true,
            next: "next",
            prev: "prev",
            generatePagination: false,
            autoHeight: true
        });

    }

    

    
});

/* grid / list switch */



/*functions for style switcher*/

function changeBgColor(rd_id,element){

    if(element == "footer"){
		jQuery('.b_head').css('background-color', '#'+jQuery('#'+rd_id).val());
		jQuery('.b_body_f').css('background-color', '#'+jQuery('#'+rd_id).val());

		jQuery('#link-color').val('#'+jQuery('#'+rd_id).val());
		jQuery.cookie(cookies_prefix + "_b_f_color",'#' + jQuery('#'+rd_id).val(), {expires: 365, path: '/'});
    }
    else if(element == "content"){
    	jQuery('#main').css('background-color', '#'+jQuery('#'+rd_id).val());
    	jQuery('#content-link-color').val('#'+jQuery('#'+rd_id).val());
    	jQuery.cookie(cookies_prefix + "_content_bg_color",'#' + jQuery('#'+rd_id).val(), {expires: 365, path: '/'});
    }


    return false;
}


/* Keyboard toggles */

jQuery(function() {
  jQuery( '.mobile-user-menu select').change( function(){
      location.href = jQuery( this).val();
  });
  jQuery('.keyboard-demo').click(function() {
    jQuery('#big-keyboard').fadeIn();
    jQuery('#keyboard-container').slideToggle().delay(100).animate( { top: '20%' }, 500, 'easeInOutExpo' );
    jQuery('#sticky-bar').fadeOut('fast');
  });
  jQuery('.close').click(function() {
    jQuery('#keyboard-container').animate( { top: '-120%' }, 300, 'easeInOutExpo' );
    jQuery('#big-keyboard').delay(300).fadeOut();
    jQuery('#sticky-bar').fadeIn('slow');
    jQuery('#keyboard-container').css('display', 'none').animate( { top: '0%' }, 1);;
  });
});
/* E.of keyboard toggles*/



/*EOF functions for style switcher*/

function viewPort(){  
	/* Determine screen resolution */
	//var $body = jQuery('body');
	wSizes = [1200, 960, 768, 480, 320, 240];
	wSizesClasses = ['w1200', 'w960', 'w768', 'w480', 'w320', 'w240'];
	
	//$body.removeClass(wSizesClasses.join(' '));
	var size = jQuery(this).width();
	//alert(size);
	for (var i=0; i<wSizes.length; i++) { 
		if (size >= wSizes[i] ) { 
			//$body.addClass(wSizesClasses[i]);

			
			jQuery('.fb_iframe_widget iframe,.fb_iframe_widget span').css({'width':jQuery('#main').width() });   
			
			break;
		}
	}
	if(typeof(FB) != 'undefined' ){
		FB.Event.subscribe('xfbml.render', function(response) {
			FB.Canvas.setAutoGrow();
		});
	}  
	/** Mobile/Default      -   320px
 * Mobile (landscape)  -   480px
 * Tablet              -   768px
 * Desktop             -   960px
 * Widescreen          -   1200px
 * Widescreen HD       -   1920px*/
	
}
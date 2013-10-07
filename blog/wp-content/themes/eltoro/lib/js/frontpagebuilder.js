var do_countdown;
var interval = false;
( function($){
	do_countdown = function(){
		if( $( '.countdown-in-progress' ).length > 0 ){
			$( '.countdown-in-progress' ).each( function( index, element ){
				var value = $( element ).find( '.countdown' ).text();
				if( value <= 0 ){
					$( element ).trigger( 'countdown' );
					$( element ).removeClass( '.countdown-in-progress' );
				}else{
					value--;
					$( element ).find( '.countdown' ).text( value );
				}
			});
		}else{
			clearInterval( interval );
			interval = false;
		}
	}

	$( document ).keyup( function( e ) { 
        if ( e.keyCode == 27 ){
        	$( document ).trigger( 'escape-key' );
        }
    });

	$( '#add_fp_element' ).click( function(){
		var $element = $( '#the-closet .element-container' ).clone();
		var $container = $( '#elements-container' );
		var date = new Date();
		var id = date.getTime();
		var html = $element.get( 0 ).outerHTML;
		var idPlacehoder=new RegExp( "_id_" , 'g' );
		html = html.replace( idPlacehoder, id );
		$element = jQuery( html );
		$container.append( $element );
		$element.find( '.element-title' ).val( '' );
		$element.addClass( 'editing newborn' );
        $element.find( '.the-settings').hide();
        $container.trigger( 'init-overview-ui' );
        $( '#element-builder-shadow').addClass( 'block' );
		$element.css( 'opacity' , 0 )
				.css( 'left' , 0 )
				.css( 'top' , 0 )
				.css( 'width' , '100%' )
				.css( 'height' , '100%' )
				.show()
				.animate( { opacity:1, left: '12%', top:'3%', width:'75%', height: '93%' }, function(){
                    $element.trigger( 'init-editing-ui', function(){
                        $element.find( '.the-settings').show();
                    });
					$element.find( '.element-title' ).focus();
                    if($.browser.msie){
                        $element.find( '.the-settings').show();
                    }
				});

        $element.find( '.generic-record-search' ).each( function( index, self ){
            jQuery( self ).autocomplete({
                serviceUrl: ajaxurl + '?action=search&params=' + jQuery( self ).parent().children('input.generic-params').val(),
                minChars:2,
                delimiter: /(,|;)\s*/,
                maxHeight:400,
                width:300,
                zIndex: 9999,
                deferRequestBy: 0,
                noCache: false,
                onSelect: function( value , data){
                    jQuery(function(){
                        jQuery( self ).parent().children('input.generic-value').val( data );
                    });
                }
            });
        });

        $element.find( 'input:checked, input:selected').trigger( 'change' );
	});

    $( '#elements-container').bind( 'init-overview-ui', function(){
        var $indicator = $( '#invisible-fixed-point' );
        var $elements_container = $( this );
        $elements_container.find( '.element-container').not( '.has-overview-ui').each( function( index, element_container ){
            var $element_container = $( element_container );
            $element_container.addClass( 'has-overview-ui' );
            $element_container.find( '.on-overview .edit' ).click( function(){
                $element_container.find( '.preview.active').removeClass( 'needed').click();
                var top = $element_container.offset().top - $( window).scrollTop();
                var left = $element_container.offset().left;
                var width = $element_container.width();
                var height = $element_container.height();
                $indicator.width( '75%' );
                $indicator.height( '93%' );
                var newTop = $indicator.offset().top - $( window).scrollTop();
                var newLeft = $indicator.offset().left;
                var newWidth = $indicator.width();
                var newHeight = $indicator.height();
                $indicator.width( '1px' );
                $indicator.height( '1px' );
                var $placeholder = $( '<div></div>' );
                $placeholder.addClass( 'placeholder' );
                $element_container.css( 'opacity' , 1 )
                    .addClass( 'editing' );
                $placeholder.insertBefore( $element_container );
                $( '#element-builder-shadow').addClass( 'block' );
                $element_container.find( '.the-settings').hide();
                $element_container.css( 'top' , top )
                    .css( 'left' , left )
                    .css( 'width' , width )
                    .css( 'height' , height )
                    .animate( { left: newLeft, top:newTop, width:newWidth, height: newHeight }, function(){
                        $element_container.css( 'top' , '3%' )
                            .css( 'left' , '12%' )
                            .css( 'width' , '75%' )
                            .css( 'height' , '93%' )
                            .find( '.the-settings').show();
                    })
                    .not( '.has-edit-ui' )
                        .trigger( 'init-editing-ui' );
            });

            $element_container.find( '.on-overview .delete').click( function(){
                var $element = $( this ).parents( '.element-container' );
                $element.addClass( 'countdown-in-progress' );
                $element.animate( { height: 35 } );
                $element.find( 'header' ).slideUp();
                $element.find( 'p' ).hide();
                $element.find( '.undo-container' ).fadeIn();
                if( interval == false ){
                    interval = setInterval( 'do_countdown()', 1000 );
                }
            });

            $element_container.bind( 'countdown', function(){
                if( $( this ).hasClass( 'countdown-in-progress' ) ){
                    $( this ).animate( { left:'+2000' , height:1, opacity:0 }, function(){
                        $( this ).remove();
                        setTimeout( "jQuery( '#elements-container' ).trigger( 'init-overview-ui' );" , 1000 );
                    });
                }
            });

            $element_container.find( '.undo-container .undo' ).click( function(){
                $element_container.removeClass( 'countdown-in-progress' )
                    .animate( { height: 80 } );
                $( this ).siblings( '.countdown' ).text( 5 );
                $element_container.find( '.on-overview header' ).slideDown();
                $element_container.find( '.on-overview p').show();
                $element_container.find( '.undo-container' ).fadeOut();
            });

            $element_container.bind( 'init-editing-ui', function( event, callback ){
                if( $element_container.hasClass( 'has-editing-ui' ) ){
                    return 0;
                }
                $element_container.addClass( 'has-editing-ui' );
                $element_container.find( '.discard' ).click( function(){
                    if( $element_container.hasClass( 'newborn' ) ){
                        $element_container.animate( { opacity:0, top:'100%' }, function(){
                            $element_container.remove();
                        });
                    }else{
                        $element_container.css( 'top' , $element_container.offset().top - $( window).scrollTop() );
                        $element_container.css( 'left' , $element_container.offset().left );
                        $element_container.css( 'width' , $element_container.width() );
                        $element_container.css( 'height' , $element_container.height() );
                        var $placeholder = $element_container.prev();
                        var newTop = $placeholder.offset().top - $( window).scrollTop();
                        var newLeft = $placeholder.offset().left
                        var newWidth = $placeholder.width();
                        var newHeight = $placeholder.height();
                        $element_container.animate( { opacity:0.1, left: newLeft - 10, top:newTop - 10, width:newWidth, height: newHeight }, function(){
                            $element_container.css( 'top' , 'auto' )
                                .css( 'left' , 'auto' )
                                .animate( { opacity: 1 } )
                                .removeClass( 'editing' );
                            if( $placeholder.hasClass( 'placeholder' ) ){
                                $placeholder.replaceWith(  $element_container );
                            }
                        });
                    }
                    $( '#elements-container' ).trigger( 'init-overview-ui' );
                    $( '#element-builder-shadow').removeClass( 'block' );
                });

                $element_container.find( '.option-numberposts input' ).bind('keyup', function(){
                    act.accept_digits( this );
                });

                $element_container.find( '.apply' ).click( function(){
                    if( $element_container.hasClass( 'newborn' ) ){
                        var id = $element_container.find( '.element-id' ).val();
                        var pixelTop = $element_container.offset().top - $( window).scrollTop();
                        var pixelLeft = $element_container.offset().left;
                        $element_container.css( 'left' , pixelLeft )
                            .css( 'top' , pixelTop );
                        var $placeholder = $( '<div></div>' );
                        $placeholder.addClass( 'placeholder' );
                        $( '#elements-container' ).prepend( $placeholder );
                        $( '#elements-container' ).trigger( 'init-overview-ui' );
                        var windowHeight = $( window).height();
                        var phTop = $placeholder.offset().top - $( window).scrollTop();
                        var phLeft = $placeholder.offset().left;
                        var phWidth = $placeholder.width();
                        var phHeight = $placeholder.height();
                        $element_container.show().removeClass( 'newborn' )
                            .animate( { opacity:0.1, top: phTop - 13, left: phLeft - 13, width: phWidth, height: phHeight }, function(){
                                $element_container.css( 'top' , 'auto' )
                                                .css( 'left' , 'auto' )
                                                .animate( { opacity: 1 } )
                                                .removeClass( 'editing' );
                                $( '#elements-container' ).trigger( 'init-overview-ui' );
                                $placeholder.replaceWith( $element_container );
                            });
                    }else{
                        var id = $element_container.find( '.element-id' ).val();
                        $element_container.css( 'top' , $element_container.offset().top - $( window).scrollTop() );
                        $element_container.css( 'left' , $element_container.offset().left );
                        $element_container.css( 'width' , $element_container.width() );
                        $element_container.css( 'height' , $element_container.height() );
                        var $placeholder = $element_container.prev();
                        var newTop = $placeholder.offset().top - $( window).scrollTop();
                        var newLeft = $placeholder.offset().left
                        var newWidth = $placeholder.width();
                        var newHeight = $placeholder.height();
                        $element_container.animate( { opacity:0.1, left: newLeft - 13, top:newTop - 13, width:newWidth, height: newHeight }, function(){
                            $element_container.css( 'top' , 'auto' )
                                .css( 'left' , 'auto' )
                                .animate( { opacity: 1 } )
                                .removeClass( 'editing' );
                            if( $placeholder.hasClass( 'placeholder' ) ){
                                $placeholder.replaceWith( $element_container );
                            }
                            $( '#elements-container' ).trigger( 'init-overview-ui' );
                        });
                    }
                    $( '#element-builder-shadow').removeClass( 'block' );
                });

                $element_container.find( '.on-edit .panel .generic-field-image-select label' ).click( function(){
                    $( this ).siblings().removeClass( 'selected' );
                    $( this ).addClass( 'selected' );
                });

                $element_container.find( '.on-edit .panel .generic-field-image-select label input:checked' ).parents( 'label').trigger( 'click' );

                $element_container.find( '.element-title' ).keyup( function(){
                    var val = $( this ).val();
                    if( val && val.length ){
                        $element_container.find( '.title .fpb_label' ).text( val );
                    }else{
                        $element_container.find( '.title .fpb_label' ).text( FrontpageBuilder.translations.add_element );
                    }
                });

                $element_container.find( '.fly-left' ).bind( 'hide' , function(){
                    $( this ).animate( { left:'100%', opacity: 0 } );
                });

                $element_container.find( '.fly-left.taxonomies .search' ).not( '.generic-record-search' ).keyup( function(){
                    var val = $( this ).val();
                    $( this ).parents( '.fly-left' ).find( '.taxonomy' ).each( function( index, element ) {
                        if( $( element ).text().toLowerCase().trim().indexOf( val.toLowerCase().trim() ) == -1 ){
                            $( element ).hide();
                        }else{
                            $( element ).show();
                        }
                    });
                });

                $element_container.find( '.preview' ).click( function(){
                    $( this ).toggleClass( 'active' );

                    if( !$( this).hasClass( 'active' ) ){
                        $element_container.find( '.iframe-wrapper' ).animate({
                            top:'-200%',
                            bottom:'200%'
                        });
                        $element_container.find( '.panel').animate({
                            top:'70px'
                        });
                    }else{
                        if( !$( this).hasClass( 'needed' ) ){
                            var $form = $( this).parents( 'form').clone();
                            var date = new Date();
                            var id = $element_container.find( 'iframe').attr( 'name' );
                            $form.attr( 'target' , id )
                                .attr( 'action' , FrontpageBuilder.home_url )
                                .css( 'display', 'none' )
                                .appendTo( $( 'body' ) )
                                .submit()
                                .remove();
                        }
                        $( this).addClass( 'needed' );
                        $element_container.find( '.iframe-wrapper' ).animate({
                            top:'70px',
                            bottom:10
                        });
                        $element_container.find( '.panel').animate({
                            top:'200%'
                        });
                    }
                });

                $element_container.find( 'a.clear-input' ).click( function(){
                    var $input = $( this ).prev();
                    var val = $input.val();
                    if( val && val.length ){
                        val = val.slice( 0, -1 );
                        $input.val( val );
                        $input.trigger( 'keyup' );
                        setTimeout( "jQuery( 'a.clear-input' ).click()", 20 );
                    }
                });

                $element_container.find( '.fly-left.taxonomies .search.generic-record-search' ).change( function(){
                    var top = $( this ).position().top;
                    var $placeholder = $( '<div class="placeholder"></div>' );
                    $container = $( '<label></label>' );
                    $container.addClass( 'taxonomy' );
                    $placeholder.insertBefore( $( this ).parents( '.fly-left' ).find( '.content .taxonomy' ).first() );
                    $placeholder.css( 'height', '1px' );
                    var $search_input = $( this );
                    $container.css( 'position' , 'absolute' )
                        .css( 'top' , top )
                        .animate( { top: $placeholder.position().top }, function(){
                            var postID = $search_input.siblings( '.generic-value' ).val();
                            $placeholder.replaceWith( $container );
                            var $idInput = $( '<input>' );
                            $idInput.val( postID )
                                .attr( 'name' , $container.next().find( 'input' ).attr( 'name' ) )
                                .attr( 'type' , 'radio' )
                                .attr( 'checked' , 'checked' );
                            $container.siblings().find( 'input[value=' + postID + ']' ).parent().remove();
                            $container.css( 'position' , 'static' )
                                .css( 'top' , '' )
                                .text( $search_input.val() )
                                .append( $idInput )
                                .addClass( 'added')
                                .siblings().removeClass( 'added' )
                                .find( 'input' ).attr( 'checked' , false );
                            $( '#elements-container').trigger( 'init-overview-ui' );
                        });
                    $placeholder.animate( { height: 15 } );
                });

                var boxShow = function( box ){
                    $( box).css( 'left', '65%').css( 'opacity' , 1 ).find( 'input.search' ).focus();;
                    if( 'function' == typeof callback ){
                        callback();
                    }
                }

                $element_container.find( '.element-type input' ).change( function(){
                    var id = $element_container.find( '.element-id' ).val();
                    var $box = false;
                    if( $( this ).hasClass( 'widget-type' ) ){
                        $box = $element_container.find( '.widgets.fly-left' );
                    }else{
                        $element_container.find( '.widgets.fly-left' ).trigger( 'hide' );
                    }

                    if( $( this ).hasClass( 'post-type' ) ){
                        $box = $element_container.find( '.posts.fly-left' );
                    }else{
                        $element_container.find( '.posts.fly-left' ).trigger( 'hide' );
                    }

                    if( $( this ).hasClass( 'page-type' ) ){
                        $box = $element_container.find( '.pages.fly-left' );
                    }else{
                        $element_container.find( '.pages.fly-left' ).trigger( 'hide' );
                    }

                    if( $( this ).hasClass( 'category-type' ) ){
                        $box = $element_container.find( '.categories.fly-left' );
                    }else{
                        $element_container.find( '.categories.fly-left' ).trigger( 'hide' );
                    }

                    if( $( this ).hasClass( 'tag-type' ) ){
                        $box = $element_container.find( '.tags.fly-left' );
                    }else{
                        $element_container.find( '.tags.fly-left' ).trigger( 'hide' );
                    }

                    if( $( this ).hasClass( 'portfolio-type' ) ){
                        $box = $element_container.find( '.portfolios.fly-left' );
                    }else{
                        $element_container.find( '.portfolios.fly-left' ).trigger( 'hide' );
                    }

                    if( $box !== false ){
                        boxShow( $box );
                    }
                });

                $element_container.find( '.back-to-top').click( function(){
                    $( this).parents( '.fly-left').find( '.content').scrollTo( 0, 'fast' );
                });

                $element_container.find( '.pages.fly-left .taxonomy input, .posts.fly-left .taxonomy input').change( function(){
                    if( $( this ).is( ':checked' ) ){
                        $( this ).parents( '.content' ).find( '.taxonomy.added input' ).trigger( 'change' );
                        $( this ).parent().addClass( 'added' );
                        var $placeholder = $( '<div class="placeholder"></div>' );
                        $container = $( this ).parent();
                        if( $container.prev().length ){
                            $placeholder.insertBefore( $container.siblings( '.taxonomy' ).first() );
                            $placeholder.css( 'height', '1px' );
                            $container.css( 'position' , 'absolute' )
                                .css( 'top' , $container.position().top )
                                .animate( { top: $placeholder.position().top }, function(){
                                    $placeholder.replaceWith( $container );
                                    $container.css( 'position' , 'static' )
                                        .css( 'top' , '' );
                                });
                            $placeholder.animate( { height: 15 } );
                        }
                    }else{
                        $( this ).parent().removeClass( 'added' );
                    }
                });

                $element_container.find( '.fly-left' ).not( '.pages' ).not( '.posts' ).find( '.taxonomy input' ).change( function(){
                    var $container = $( this ).parents( 'label' );
                    $container.toggleClass( 'added' );
                    var $placeholder = $( '<div class="placeholder"></div>' );
                    if( $container.hasClass( 'added' ) && !$container.prev().hasClass( 'added' ) && $container.prev().hasClass( 'taxonomy' ) ){
                        if( $container.siblings( '.added' ).length ){
                            $placeholder.insertAfter( $container.siblings( '.added' ).last() );
                        }else{
                            $placeholder.insertBefore( $container.siblings( '.taxonomy' ).first() );
                        }
                        $placeholder.css( 'height', '1px' );
                        $container.css( 'position' , 'absolute' )
                            .css( 'top' , $container.position().top )
                            .animate( { top: $placeholder.position().top }, function(){
                                $placeholder.replaceWith( $container );
                                $container.css( 'position' , 'static' )
                                    .css( 'top' , '' );
                            });
                        $placeholder.animate( { height: 15 } );
                    }else if( $container.siblings( '.added' ).length && $container.next().hasClass( 'added' )  ){
                        $placeholder.insertAfter( $container.siblings( '.added' ).last() );
                        $placeholder.css( 'height', '1px' );
                        $container.css( 'position' , 'absolute' )
                            .css( 'top' , $container.position().top )
                            .animate( { top: $placeholder.position().top }, function(){
                                $placeholder.replaceWith( $container );
                                $container.css( 'position' , 'static' )
                                    .css( 'top' , '' );
                            });
                        $placeholder.animate( { height: 15 } );
                    }
                });

                var hideGenericField = function( elem ){
                    $( elem).hide();
                }

                var showGenericField = function( elem ){
                    $( elem).show();
                }

                $element_container.find( '.options-view-type').bind( 'show-hide', function(){
                    var element_type = $element_container.find( '.element-type input:checked' ).val();
                    if( element_type == 'widget_zone' || element_type == 'post' || element_type == 'page' ){
                        hideGenericField( this );
                    }else{
                        showGenericField( this );
                    }
                });

                $element_container.find( '.options-columns').bind( 'show-hide', function(){
                    var element_type = $element_container.find( '.element-type input:checked' ).val();
                    if( element_type == 'widget_zone' || element_type == 'post' || element_type == 'page' || $element_container.find( '.element-view-type input:checked' ).val() == 'list_view' ){
                        hideGenericField( this );
                        hideGenericField( $element_container.find( '.option-numberposts .hint') );
                    }else{
                        showGenericField( this );
                        showGenericField( $element_container.find( '.option-numberposts .hint') );
                    }

                    if( $( 'select.front_page-layout').val() == 'full' ){
                        $( this).find( '.columns-9' ).hide();
                        $( this).find( '.columns-2, .columns-4, .columns-6' ).show();
                    }else{
                        $( this).find( '.columns-9' ).show();
                        $( this).find( '.columns-2, .columns-4, .columns-6' ).hide();
                    }

                    if( $( this).find( '.columns-9 input').is( ':checked' ) && $( 'select.front_page-layout').val() == 'full' ){
                        $( this ).find( 'input' ).attr( 'checked', false);
                        $( this).find( 'label').removeClass( 'selected' );
                        $( this).find( '.columns-2').addClass( 'selected').find( 'input').attr( 'checked' , 'checked' );
                    }
                    if( (
                            $( this).find( '.columns-2 input').is( ':checked' ) ||
                            $( this).find( '.columns-4 input').is( ':checked' ) ||
                            $( this).find( '.columns-6 input').is( ':checked' )
                        ) && $( 'select.front_page-layout').val() != 'full' ){
                        $( this ).find( 'input' ).attr( 'checked', false);
                        $( this).find( 'label').removeClass( 'selected' );
                        $( this).find( '.columns-3').addClass( 'selected').find( 'input').attr( 'checked' , 'checked' );
                    }
                });

                $element_container.find( '.option-numberposts').bind( 'show-hide', function(){
                    var element_type = $element_container.find( '.element-type input:checked' ).val();
                    if( element_type == 'widget_zone' || element_type == 'post' || element_type == 'page' ){
                        hideGenericField( this );
                    }else{
                        showGenericField( this );
                    }
                });

                $element_container.find( '.enb-list-thumbs-container').bind( 'show-hide', function(){
                    var element_type = $element_container.find( '.element-type input:checked' ).val();
                    if( element_type == 'widget_zone' || element_type == 'post' || element_type == 'page' || $element_container.find( '.element-view-type input:checked' ).val() != 'list_view' ){
                        hideGenericField( this );
                    }else{
                        showGenericField( this );
                    }
                });

                $element_container.find( '.enb-carousel-container').bind( 'show-hide', function(){
                    var element_type = $element_container.find( '.element-type input:checked' ).val();
                    if( element_type == 'widget_zone' || element_type == 'post' || element_type == 'page'
                        || ( $element_container.find( '.enb-list-thumbs-container input:checked' ).val() == 'yes' && $element_container.find( '.element-view-type input:checked' ).val() == 'list_view' )
                        || $element_container.find( '.element-view-type input:checked' ).val() == 'list_view'
                        ){
                        hideGenericField( this );
                    }else{
                        showGenericField( this );
                    }
                });

                $element_container.find( '.options-pagination').bind( 'show-hide', function(){
                    var element_type = $element_container.find( '.element-type input:checked' ).val();
                    if( element_type == 'widget_zone' || element_type == 'post' || element_type == 'page' || ( $element_container.find( '.enb-list-thumbs-container input:checked' ).val() == 'yes' && $element_container.find( '.element-view-type input:checked' ).val() == 'list_view' )
                        || (
                            $element_container.find( '.enb-carousel-container input:checked' ).val() == 'yes'
                            && $element_container.find( '.element-view-type input:checked' ).val() != 'list_view'
                            )
                        ){
                        hideGenericField( this );
                    }else{
                        showGenericField( this );
                    }
                });

                $element_container.find( '.options-load-more').bind( 'show-hide', function(){
                    var element_type = $element_container.find( '.element-type input:checked' ).val();
                    if( element_type == 'widget_zone' || element_type == 'post' || element_type == 'page' || ( $element_container.find( '.enb-list-thumbs-container input:checked' ).val() == 'yes' && $element_container.find( '.element-view-type input:checked' ).val() == 'list_view' )
                        || $element_container.find( '.options-pagination input:checked' ).val() == 'yes'
                        || (
                            $element_container.find( '.enb-carousel-container input:checked' ).val() == 'yes'
                                && $element_container.find( '.element-view-type input:checked' ).val() != 'list_view'
                            )
                        ){
                        hideGenericField( this );
                    }else{
                        showGenericField( this );
                    }
                });

                $element_container.find( '.standard-generic-field input, .taxonomy input').change( function(){
                    $element_container.find( '.standard-generic-field').trigger( 'show-hide' );
                    if( $element_container.find( '.preview.needed').length ){
                        var $form = $( this).parents( 'form').clone();
                        var date = new Date();
                        var id = $element_container.find( 'iframe' ).attr( 'name' );
                        $form.attr( 'target' , id )
                            .attr( 'action' , FrontpageBuilder.home_url )
                            .css( 'display', 'none' )
                            .appendTo( $( 'body' ) )
                            .submit()
                            .remove();
                    }
                    var type = $element_container.find( '.element-type input:checked').val();
                    var description = FrontpageBuilder.translations.showing + ' ';
                    if( 'widget_zone' == type ){
                        description += '<i>';
                            description += FrontpageBuilder.translations.widgets + '.';
                        description += '</i>';
                    }else if( 'page' == type ){
                        description += 'the "';
                            description += '<i>';
                                description += $element_container.find( '.pages .taxonomy.added').text().trim();
                            description += '</i>';
                        description += '" ' + FrontpageBuilder.translations.page + '.';
                    }else if( 'post' == type ){
                        description += 'the "';
                        description += '<i>';
                        description += $element_container.find( '.posts .taxonomy.added').text().trim();
                        description += '</i>';
                        description += '" ' + FrontpageBuilder.translations.post + '.';
                    }else if( 'category' == type ){
                        if( $element_container.find( '.categories .taxonomy.added').length ){
                            description += '<i>';
                                description += $element_container.find( '.option-numberposts input').val();
                            description += '</i>';
                            if( $element_container.find( '.option-numberposts input').val() == 1 ){
                                description += ' ' + FrontpageBuilder.translations.post;
                            }else{
                                description += ' ' + FrontpageBuilder.translations.posts;
                            }
                            description += ' ' + FrontpageBuilder.translations.from;
                            description += ' <i>';
                                description += $element_container.find( '.categories .taxonomy.added').length;
                            description += '</i>';
                            if( $element_container.find( '.categories .taxonomy.added').length == 1 ){
                                description += ' ' + FrontpageBuilder.translations.category;
                            }else{
                                description += ' ' + FrontpageBuilder.translations.categories;
                            }
                            description += ' ' + FrontpageBuilder.translations.in;
                            description += ' <i>';
                                description += ' ' + $element_container.find( '.element-view-type input:checked').parent().text();
                            description += '</i>';
                            if( $element_container.find( '.element-view-type input:checked' ).val() == 'list_view' && $element_container.find( '.enb-list-thumbs-container input:checked').val() == 'yes' ){
                                description += ' ' + FrontpageBuilder.translations.with;
                                description += '<i>';
                                description += ' ' + FrontpageBuilder.translations.gallery;
                                description += '</i>';
                            }else if( $element_container.find( '.enb-carousel-container input:checked').val() == 'yes' ){
                                description += ' ' + FrontpageBuilder.translations.with;
                                description += '<i>';
                                description += ' ' + FrontpageBuilder.translations.carousel;
                                description += '</i>';
                            }else if( $element_container.find( '.options-pagination input:checked').val() == 'yes' ){
                                description += ' ' + FrontpageBuilder.translations.with;
                                description += '<i>';
                                description += ' ' + FrontpageBuilder.translations.pagination;
                                description += '</i>';
                            }else if( $element_container.find( '.options-load-more input:checked').val() == 'yes' ){
                                description += ' ' + FrontpageBuilder.translations.with;
                                description += '<i>';
                                description += ' ' + FrontpageBuilder.translations.load_more;
                                description += '</i>';
                            }
                        }else{
                            description = FrontpageBuilder.translations.please + ' ';
                            description += ' <i>';
                                description += FrontpageBuilder.translations.categories;
                            description += '.</i>';
                        }
                    }else if( 'tag' == type ){
                        if( $element_container.find( '.tags .taxonomy.added').length ){
                            description += '<i>';
                            description += $element_container.find( '.option-numberposts input').val();
                            description += '</i>';
                            if( $element_container.find( '.option-numberposts input').val() == 1 ){
                                description += ' ' + FrontpageBuilder.translations.post;
                            }else{
                                description += ' ' + FrontpageBuilder.translations.posts;
                            }
                            description += ' ' + FrontpageBuilder.translations.from;
                            description += ' <i>';
                            description += $element_container.find( '.tags .taxonomy.added').length;
                            description += '</i>';
                            if( $element_container.find( '.tags .taxonomy.added').length == 1 ){
                                description += ' ' + FrontpageBuilder.translations.tag;
                            }else{
                                description += ' ' + FrontpageBuilder.translations.tags;
                            }
                            description += ' ' + FrontpageBuilder.translations.in;
                            description += ' <i>';
                            description += ' ' + $element_container.find( '.element-view-type input:checked').parent().text();
                            description += '</i>';
                            if( $element_container.find( '.element-view-type input:checked' ).val() == 'list_view' && $element_container.find( '.enb-list-thumbs-container input:checked').val() == 'yes' ){
                                description += ' ' + FrontpageBuilder.translations.with;
                                description += '<i>';
                                description += ' ' + FrontpageBuilder.translations.gallery;
                                description += '</i>';
                            }else if( $element_container.find( '.enb-carousel-container input:checked').val() == 'yes' ){
                                description += ' ' + FrontpageBuilder.translations.with;
                                description += '<i>';
                                description += ' ' + FrontpageBuilder.translations.carousel;
                                description += '</i>';
                            }else if( $element_container.find( '.options-pagination input:checked').val() == 'yes' ){
                                description += ' ' + FrontpageBuilder.translations.with;
                                description += '<i>';
                                description += ' ' + FrontpageBuilder.translations.pagination;
                                description += '</i>';
                            }else if( $element_container.find( '.options-load-more input:checked').val() == 'yes' ){
                                description += ' ' + FrontpageBuilder.translations.with;
                                description += '<i>';
                                description += ' ' + FrontpageBuilder.translations.load_more;
                                description += '</i>';
                            }
                        }else{
                            description = FrontpageBuilder.translations.please + ' ';
                            description += ' <i>';
                            description += FrontpageBuilder.translations.tags;
                            description += '.</i>';
                        }
                    }else if( 'portfolio' == type ){
                        if( $element_container.find( '.portfolios .taxonomy.added').length ){
                            description += '<i>';
                            description += $element_container.find( '.option-numberposts input').val();
                            description += '</i>';
                            if( $element_container.find( '.option-numberposts input').val() == 1 ){
                                description += ' ' + FrontpageBuilder.translations.post;
                            }else{
                                description += ' ' + FrontpageBuilder.translations.posts;
                            }
                            description += ' ' + FrontpageBuilder.translations.from;
                            description += ' <i>';
                            description += $element_container.find( '.portfolios .taxonomy.added').length;
                            description += '</i>';
                            if( $element_container.find( '.portfolios .taxonomy.added').length == 1 ){
                                description += ' ' + FrontpageBuilder.translations.portfolio;
                            }else{
                                description += ' ' + FrontpageBuilder.translations.portfolios;
                            }
                            description += ' ' + FrontpageBuilder.translations.in;
                            description += ' <i>';
                            description += ' ' + $element_container.find( '.element-view-type input:checked').parent().text();
                            description += '</i>';
                            if( $element_container.find( '.element-view-type input:checked' ).val() == 'list_view' && $element_container.find( '.enb-list-thumbs-container input:checked').val() == 'yes' ){
                                description += ' ' + FrontpageBuilder.translations.with;
                                description += '<i>';
                                description += ' ' + FrontpageBuilder.translations.gallery;
                                description += '</i>';
                            }else if( $element_container.find( '.enb-carousel-container input:checked').val() == 'yes' ){
                                description += ' ' + FrontpageBuilder.translations.with;
                                description += '<i>';
                                description += ' ' + FrontpageBuilder.translations.carousel;
                                description += '</i>';
                            }else if( $element_container.find( '.options-pagination input:checked').val() == 'yes' ){
                                description += ' ' + FrontpageBuilder.translations.with;
                                description += '<i>';
                                description += ' ' + FrontpageBuilder.translations.pagination;
                                description += '</i>';
                            }else if( $element_container.find( '.options-load-more input:checked').val() == 'yes' ){
                                description += ' ' + FrontpageBuilder.translations.with;
                                description += '<i>';
                                description += ' ' + FrontpageBuilder.translations.load_more;
                                description += '</i>';
                            }
                        }else{
                            description = FrontpageBuilder.translations.please + ' ';
                            description += ' <i>';
                            description += FrontpageBuilder.translations.portfolios;
                            description += '.</i>';
                        }
                    }else if( 'featured' == type ){
                        description += '<i>';
                        description += $element_container.find( '.option-numberposts input').val();
                        description += '</i>';
                        description += ' ' + FrontpageBuilder.translations.featured;
                        if( $element_container.find( '.option-numberposts input').val() == 1 ){
                            description += ' ' + FrontpageBuilder.translations.post;
                        }else{
                            description += ' ' + FrontpageBuilder.translations.posts;
                        }
                        description += ' ' + FrontpageBuilder.translations.in;
                        description += ' <i>';
                        description += ' ' + $element_container.find( '.element-view-type input:checked').parent().text();
                        description += '</i>';
                        if( $element_container.find( '.element-view-type input:checked' ).val() == 'list_view' && $element_container.find( '.enb-list-thumbs-container input:checked').val() == 'yes' ){
                            description += ' ' + FrontpageBuilder.translations.with;
                            description += '<i>';
                            description += ' ' + FrontpageBuilder.translations.gallery;
                            description += '</i>';
                        }else if( $element_container.find( '.enb-carousel-container input:checked').val() == 'yes' ){
                            description += ' ' + FrontpageBuilder.translations.with;
                            description += '<i>';
                            description += ' ' + FrontpageBuilder.translations.carousel;
                            description += '</i>';
                        }else if( $element_container.find( '.options-pagination input:checked').val() == 'yes' ){
                            description += ' ' + FrontpageBuilder.translations.with;
                            description += '<i>';
                            description += ' ' + FrontpageBuilder.translations.pagination;
                            description += '</i>';
                        }else if( $element_container.find( '.options-load-more input:checked').val() == 'yes' ){
                            description += ' ' + FrontpageBuilder.translations.with;
                            description += '<i>';
                            description += ' ' + FrontpageBuilder.translations.load_more;
                            description += '</i>';
                        }
                    }else if( 'latest' == type ){
                        description += '<i>';
                        description += $element_container.find( '.option-numberposts input').val();
                        description += '</i>';
                        description += ' ' + FrontpageBuilder.translations.latest;
                        if( $element_container.find( '.option-numberposts input').val() == 1 ){
                            description += ' ' + FrontpageBuilder.translations.post;
                        }else{
                            description += ' ' + FrontpageBuilder.translations.posts;
                        }
                        description += ' ' + FrontpageBuilder.translations.in;
                        description += ' <i>';
                        description += ' ' + $element_container.find( '.element-view-type input:checked').parent().text();
                        description += '</i>';
                        if( $element_container.find( '.element-view-type input:checked' ).val() == 'list_view' && $element_container.find( '.enb-list-thumbs-container input:checked').val() == 'yes' ){
                            description += ' ' + FrontpageBuilder.translations.with;
                            description += '<i>';
                            description += ' ' + FrontpageBuilder.translations.gallery;
                            description += '</i>';
                        }else if( $element_container.find( '.enb-carousel-container input:checked').val() == 'yes' ){
                            description += ' ' + FrontpageBuilder.translations.with;
                            description += '<i>';
                            description += ' ' + FrontpageBuilder.translations.carousel;
                            description += '</i>';
                        }else if( $element_container.find( '.options-pagination input:checked').val() == 'yes' ){
                            description += ' ' + FrontpageBuilder.translations.with;
                            description += '<i>';
                            description += ' ' + FrontpageBuilder.translations.pagination;
                            description += '</i>';
                        }else if( $element_container.find( '.options-load-more input:checked').val() == 'yes' ){
                            description += ' ' + FrontpageBuilder.translations.with;
                            description += '<i>';
                            description += ' ' + FrontpageBuilder.translations.load_more;
                            description += '</i>';
                        }
                    }

                    $element_container.find( '.element-description').html( description );
                });
                $element_container.find( '.standard-generic-field input' ).filter( ':checked, :selected').trigger( 'change' );

                hideGenericField = function( elem ){
                    $( elem).slideUp();
                }

                showGenericField = function( elem ){
                    $( elem).slideDown();
                }

                boxShow = function( box ){
                    $( box ).animate( { left:'65%', opacity: 1 }, function(){
                        $( this ).find( 'input.search' ).focus();
                    });
                }
            });
        });
    }).trigger( 'init-overview-ui').sortable({
        handle: '.on-overview .fpb.title'
    });
    $( '.element-container').trigger( 'init-editing-ui' );

    $( '#element-builder-shadow').click( function(){
        $( '.element-container.editing .discard' ).click();
    });
	$( document ).on( 'escape-key' , function(){
		$( '.element-container.editing .discard' ).click();
	});

	$( '.standard-generic-field.submit input[type=submit]' ).click( function( event ){
		if( $( '.countdown-in-progress' ).length ){
			event.preventDefault();
			$( '.element-container' ).trigger( 'countdown' );
			setTimeout( function(){
				$( '.standard-generic-field.submit input[type=submit]' ).click();
			}, 500 );
		}
	});
}(jQuery) );
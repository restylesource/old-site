<?php

    if( is_front_page() ){
        $slideshow = meta::get_meta( options::get_value( 'slider' , 'slideshow' ) , 'box' );
        $slideshow_settings = meta::get_meta( options::get_value( 'slider' , 'slideshow' ) , 'slidesettings' );
    }else if( is_singular() && isset( $post ) && is_object( $post ) && !is_wp_error( $post ) && meta::logic( $post, 'settings' , 'slideshow' ) &&
        (
            ( is_page() && ( get_post_type( $post ) == 'page' ) ) ||
            is_single()
        )
    ){
        $postID = $post -> ID;
        $meta = meta::get_meta( $postID , 'settings' );
        if( is_array( $meta ) && isset( $meta[ 'slideshow_select' ] ) && strlen( $meta[ 'slideshow_select' ] ) ){
            $slideshowID = $meta[ 'slideshow_select' ];
            if( (int)$slideshowID > 0 ){
                $slideshow = meta::get_meta( $slideshowID , 'box' );
                $slideshow_settings = meta::get_meta( $slideshowID , 'slidesettings' );
            }
        }
    }

    if( isset( $slideshow ) && is_array( $slideshow ) && count( $slideshow ) ){
        if( !( isset( $slideshow_settings[ 'height' ] ) && is_numeric( $slideshow_settings[ 'height' ] ) ) ){
            $slideshow_settings[ 'height' ] = 300;
        }

        if( !( isset( $slideshow_settings[ 'animationSpeed' ] ) && is_numeric( $slideshow_settings[ 'animationSpeed' ] ) ) ){
            $slideshow_settings[ 'animationSpeed' ] = 500;
        }

        if( !( isset( $slideshow_settings[ 'advanceSpeed' ] ) && is_numeric( $slideshow_settings[ 'advanceSpeed' ] ) ) ){
            $slideshow_settings[ 'advanceSpeed' ] = 2000;
        }

        if( !( isset( $slideshow_settings[ 'captionAnimationSpeed' ] ) && is_numeric( $slideshow_settings[ 'captionAnimationSpeed' ] ) ) ){
            $slideshow_settings[ 'captionAnimationSpeed' ] = 500;
        }

        if( !( isset( $slideshow_settings[ 'startClockOnMouseOutAfter' ] ) && is_numeric( $slideshow_settings[ 'startClockOnMouseOutAfter' ] ) ) ){
            $slideshow_settings[ 'startClockOnMouseOutAfter' ] = 500;
        }
        if( options::logic( 'styling' , 'larger' ) ){
            $thumbnail = 'slideshow_large';
        }else{
            $thumbnail = 'slideshow';
        }
        $slides = Array();
        foreach( $slideshow as $i => $slider ){
            $title = '';
            $link = '';
            $description = '';

            if( isset( $slider[ 'type_res' ] ) && $slider[ 'type_res' ] == 'post' ){
                $sliderPostID = $slider[ 'resources' ];
                $sliderPost = get_post( $sliderPostID );
                if( strlen( $sliderPost -> post_excerpt ) ){
                    if( strlen( $sliderPost -> post_excerpt ) > 180 ){
                        $description = mb_substr( strip_tags( strip_shortcodes( $sliderPost -> post_excerpt ) ), 0, 180 ) . '..';
                    }else{
                        $description = strip_tags( strip_shortcodes( $sliderPost -> post_excerpt ) );
                    }
                }else{
                    if( strlen( $sliderPost -> post_content ) > 180 ){
                        $description = mb_substr( strip_tags( strip_shortcodes( $sliderPost -> post_content ) ), 0, 180 ) . '..';
                    }else{
                        $description = strip_tags( strip_shortcodes( $sliderPost -> post_content ) );
                    }
                }
                $title = $sliderPost -> post_title;
                $link = get_permalink( $sliderPostID );
                if( isset( $slider[ 'url' ] ) && strlen( $slider[ 'url' ] ) ){
                    $link = $slider[ 'url' ];
                }
                if( has_post_thumbnail( $sliderPostID ) ){
                    $image = wp_get_attachment_image_src( get_post_thumbnail_id( $sliderPostID ), $thumbnail );
                }else{
                    $image = array();
                    $image[ 0 ] = '';
                }

                if( isset( $slider[ 'slide_id' ] ) && is_numeric( $slider[ 'slide_id' ] ) ){
                    $image = wp_get_attachment_image_src( $slider[ 'slide_id' ] , $thumbnail );
                    $image_id = $slider[ 'slide_id' ];
                }
            }else{
                $image = wp_get_attachment_image_src( $slider[ 'slide_id' ] , $thumbnail );
                $image_id = $slider[ 'slide_id' ];
                $title = isset( $slider[ 'title' ] ) ? $slider[ 'title' ] : '';
                $link = isset( $slider[ 'url' ] ) ? $slider[ 'url' ] : '';
            }

            if( isset( $slider[ 'description' ] ) && strlen( trim( $slider[ 'description' ] ) ) ){
                $description = $slider[ 'description' ];
            }

            $slider_background_color = ( isset( $slider[ 'slider_background_color' ] ) && strlen( $slider[ 'slider_background_color' ] ) ) ? $slider[ 'slider_background_color' ] : '';
            $slider_background_image = ( isset( $slider[ 'background_image' ] ) && strlen( $slider[ 'background_image' ] ) ) ? $slider[ 'background_image' ] : '';
            $slider_background_image_position = ( isset( $slider[ 'background_position' ] ) && strlen( $slider[ 'background_position' ] ) ) ? $slider[ 'background_position' ] : '';
            $slider_background_image_repeat = ( isset( $slider[ 'background_repeat' ] ) && strlen( $slider[ 'background_repeat' ] ) ) ? $slider[ 'background_repeat' ] : '';
            $slider_background_image_attachment_type = ( isset( $slider[ 'background_attachment' ] ) && strlen( $slider[ 'background_attachment' ] ) ) ? $slider[ 'background_attachment' ] : '';
            $slider_title_color = ( isset( $slider[ 'title_color' ] ) && strlen( $slider[ 'title_color' ] ) ) ? $slider[ 'title_color' ] : '';
            $slider_description_color = ( isset( $slider[ 'description_color' ] ) && strlen( $slider[ 'description_color' ] ) ) ? $slider[ 'description_color' ] : '';
            $description_position = ( isset( $slider[ 'description_position' ] ) && strlen( $slider[ 'description_position' ] ) ) ? $slider[ 'description_position' ] : 'left';
            $first_line = ( isset( $slider[ 'first_line' ] ) && strlen( $slider[ 'first_line' ] ) && $slider[ 'type_res' ] == 'text' ) ? $slider[ 'first_line' ] : '';
            $second_line = ( isset( $slider[ 'second_line' ] ) && strlen( $slider[ 'second_line' ] ) && $slider[ 'type_res' ] == 'text' ) ? $slider[ 'second_line' ] : '';

            $slide = array(
                'title' => $title,
                'image' => $image[ 0 ],
                'link' => $link,
                'description' => $description,
                'slider-background-color' => $slider_background_color,
                'slider-background-image' => $image[ 0 ],
                'slider-background-image-position' => $slider_background_image_position,
                'slider-background-image-repeat' => $slider_background_image_repeat,
                'slider-background-image-attachment-type' => $slider_background_image_attachment_type,
                'slider-title-color' => $slider_title_color,
                'slider-description-color' => $slider_description_color,
                'description-position' => $description_position,
                'first_line' => $first_line,
                'second_line' => $second_line
            );

            $slides[] = $slide;
        }
        ?>
        <!--[if IE]>
             <style type="text/css">
                 .timer { display: none !important; }
                 div.caption { background:transparent; filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000,endColorstr=#99000000);zoom: 1; }
            </style>
        <![endif]-->

        <style>
            .slider-container, .orbit-wrapper, .cosmo-slider{  overflow:hidden; height: <?php echo $slideshow_settings[ 'height' ];?>px !important;}
        </style>

        <?php
            if( options::logic( 'styling', 'boxed' ) ){
                $unbox = 'unbox-x';
            }else{
                $unbox = '';
            }
        ?>
        
        <div class="row <?php echo $unbox;?>">
            <div class="slider-container" style="height:<?php echo $slideshow_settings[ 'height' ];?>px">
                <div class="cosmo-slider" id="featured">
                    <?php
                    $captions = array();
                    $caption_positions = array();
                    foreach( $slides as $i => $slide ){
                        extract( $slide );
                        $description_position = $slide[ 'description-position' ] . '-caption';
                        if( ( strlen( trim( $description ) ) || strlen( trim( $title ) ) ) || ( strlen( $first_line ) && strlen( $second_line  ) ) ){
                            if( isset( $slide[ 'slider-title-color' ] ) && strlen( $slide[ 'slider-title-color' ] ) ){
                                $title_style = <<<endhtml
                                    style="color:{$slide[ 'slider-title-color' ]}"
endhtml;
                            }else{
                                $title_style = '';
                            }
                            $link = ( isset( $slide[ 'link' ] ) && strlen( $slide[ 'link' ] ) ) ? $slide[ 'link' ] : '';
                            $slider_background_color = ( isset( $slide[ 'slider-background-color' ] ) && strlen( $slide[ 'slider-background-color' ] ) ) ? $slide[ 'slider-background-color' ] : '';
                            $slider_background_image = ( isset( $slide[ 'slider-background-image' ] ) && strlen( $slide[ 'slider-background-image' ] ) ) ? $slide[ 'slider-background-image' ] : '';
                            $slider_background_image_position = ( isset( $slide[ 'slider-background-image-position' ] ) && strlen( $slide[ 'slider-background-image-position' ] ) ) ? $slide[ 'slider-background-image-position' ] : '';
                            $slider_background_image_repeat = ( isset( $slide[ 'slider-background-image-repeat' ] ) && strlen( $slide[ 'slider-background-image-repeat' ] ) ) ? $slide[ 'slider-background-image-repeat' ] : '';
                            $slider_background_image_attachment_type = ( isset( $slide[ 'slider-background-image-attachment-type' ] ) && strlen( $slide[ 'slider-background-image-attachment-type' ] ) ) ? $slide[ 'slider-background-image-attachment-type' ] : '';
                            $slider_description_color = ( isset( $slide[ 'slider-description-color' ] ) && strlen( $slide[ 'slider-description-color' ] ) ) ? $slide[ 'slider-description-color' ] : '';
                            $description_position = ( isset( $slide[ 'description-position' ] ) && strlen( $slide[ 'description-position' ] ) ) ? $slide[ 'description-position' ] : 'left';

                            $slider_style = '';

                            if( strlen( $slider_description_color ) ){
                                $slider_style .= "color:${slider_description_color};";
                            }

                            if( strlen( $slider_background_color ) ){
                                $slider_style .= 'background-color:' . $slider_background_color . ';';
                            }

                            if( strlen( $slider_background_image ) ){
                                $slider_style .= 'background-image: url( ' . $slider_background_image . ' );';

                                if( strlen( $slider_background_image_position ) ){
                                    $slider_style .= 'background-position:' . $slider_background_image_position . ';';
                                }

                                if( strlen( $slider_background_image_repeat ) ){
                                    $slider_style .= 'background-repeat:' . $slider_background_image_repeat . ';';
                                }

                                if( strlen( $slider_background_image_attachment_type ) ){
                                    $slider_style .= 'background-attachment:' . $slider_background_image_attachment_type . ';';
                                }
                            }

                            ?>
                                <div style="<?php echo $slider_style;?>" class="content orbit-slide">
                            <?php
                                if( strlen( $link ) ){
                                    $link = <<<endhtml
                                        href="$link"
endhtml;
                                }

                            if( strlen( $first_line ) && strlen( $second_line  ) ){?>
                                <div class="row text-effect">
                                    <h2 class="first-line"><a <?php echo $link; ?> <?php echo $title_style;?>><?php echo $first_line; ?></a> </h2>
                                    <h2 class="second-line"><a <?php echo $link; ?> <?php echo $title_style;?>><?php echo $second_line; ?></a> </h2>
                                </div>
                                <?php
                            }else if( $description_position == 'right' ){ ?>
                                    <div class="row">
                                        <div class="six columns left"></div>
                                        <div class="six columns slide-description-container right">
                                            <h2><a <?php echo $link; ?> <?php echo $title_style;?>><?php echo $title; ?></a> </h2>
                                            <p><?php echo $description; ?></p>
                                        </div>
                                    </div>
                            <?php
                            }else{ ?>
                                <div class="row">
                                    <div class="six columns slide-description-container left">
                                        <h2><a <?php echo $link; ?> <?php echo $title_style;?>><?php echo $title; ?></a> </h2>
                                        <p><?php echo $description; ?></p>
                                    </div>
                                    <div class="six columns right"></div>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    <?php
                        }else{
                        ?>
                                <?php
                                    if( strlen( $link ) ){
                                ?>
                                        <img onclick="location.href='<?php echo $link;?>'" class="has-link orbit-slide" src="<?php echo $image; ?>">
                                <?php
                                    }else{
                                ?>
                                        <img class="orbit-slide" src="<?php echo $image; ?>">
                                <?php
                                    }
                                ?>
                            
                        <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>



<?php
    if ( !empty( $slideshow ) && is_array( $slideshow ) && is_array( $slideshow_settings )  && count( $slideshow_settings ) ) {
        extract( $slideshow_settings );
        if(!isset($animation)){ $animation = 'fade'; }
        if(!isset($timer)){ $timer = 'yes'; }
        if(!isset($pauseOnHover)){ $pauseOnHover = 'yes'; }
        if(!isset($startClockOnMouseOut)){ $startClockOnMouseOut = 'yes'; }
        if(!isset($directionalNav)){ $directionalNav = 'yes'; }

?>
    <script>
        jQuery( window ).load( function(){
            /* Orbit slider */
            jQuery('#featured').orbit({
                animation: '<?php echo $animation;?>',                      // fade, horizontal-slide, vertical-slide, horizontal-push
                animationSpeed: <?php echo $animationSpeed;?>,                // how fast animtions are
                timer: <?php echo $timer == 'yes' ? 'true' : 'false';?>,    // true or false to have the timer
                advanceSpeed: <?php echo $advanceSpeed;?>,               // if timer is enabled, time between transitions 
                pauseOnHover: <?php echo $pauseOnHover == 'yes' ? 'true' : 'false';?>, // if you hover pauses the slider
                startClockOnMouseOut: <?php echo $startClockOnMouseOut == 'yes' ? 'true' : 'false'?>,       // if clock should start on MouseOut
                startClockOnMouseOutAfter: <?php echo $startClockOnMouseOutAfter;?>,     // how long after MouseOut should the timer start again
                directionalNav: <?php echo $directionalNav == 'yes' ? 'true' : 'false' ;?>,                 // manual advancing directional navs
                captions: false,
                bullets: false,    // true or false to activate the bullet navigation
                bulletThumbs: false,                // thumbnails for the bullets
                bulletThumbLocation: '',            // location from this file where thumbs will be
                afterSlideChange: function( prev, current ){
                },
                fluid: true
            });
            jQuery( '#featured').parents( '.orbit-wrapper').find( '.slider-nav' ).hide();
            jQuery( '#featured').parents( '.orbit-wrapper').hover( function(){
                jQuery( '#featured').parents( '.orbit-wrapper').find( '.slider-nav' ).show();
            });
            jQuery( '#featured').parents( '.orbit-wrapper').mouseleave( function(){
                jQuery( '#featured').parents( '.orbit-wrapper').find( '.slider-nav' ).hide();
            });
        });
    </script>
<?php
        }
    }else if( is_front_page() && strlen( trim( options::get_value( 'advertisement', 'logo' ) ) ) ){
        echo '<div class="delimiter"></div>';
    }
?>
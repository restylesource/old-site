<?php
    class tools{
        function primary_class( $post_id , $template, $return_just_class = false ){
            if($return_just_class){
                return layout::length( $post_id , $template , true );
            }else{
                echo 'class="' . layout::length( $post_id , $template , true ) . '"';    
            }
            
        }

        function content_class( $post_id , $template , $side = '' , $with_grid = true ){
            $grid = self::is_grid( $template , $side );

            echo 'class="w_' . layout::length( $post_id , $template ) . '  ' . str_replace( '_' , '-' , $template ) . ' ';
            if( $with_grid ){
                if( $grid ){
                    echo 'grid-view';
                }else{
                    echo 'list-view';
                }
            }
            echo '"';
        }

        function entry_class( $post_id , $template , $classes = '' ){
            ob_start();
            ob_clean();
            $left = layout::side( 'left' , $post_id , $template );
            if( layout::length( $post_id , $template ) == layout::$size['large'] ){
                $classes .= ' b w_290';
            }else{
                if( $left ){
                    $classes .= ' w_610';
                }else{
                    $classes .= ' w_610';
                }
            }
            ob_get_clean();
            echo 'class="' . $classes . '"';
        }


        static function nsfw_class( $post_id ){
            if( self::is_nsfw( $post_id ) ){
                $result = ' nsfw';
            }else{
                $result = ' ';
            }

            return $result;
        }

        static function login_attr( $post_id , $type , $url = '', $additional_class = '' ){

            if( strlen( $url) ){
                $result = 'href="' . $url . '"'. ' class="'.$additional_class.'"';
            }else{
                $result = '';
            }
            if( !is_user_logged_in () ){
                if( self::is_nsfw( $post_id ) ){
                    if( strlen( $url ) ){
                        $result = 'class="' . $type . ' '.$additional_class.'" href="javascript:void(0)" onclick="get_login_box(\'nsfw\')"';
                    }else{
                        $result = '' . $type;
                    }
                }
            }
            
            return $result;
        }
        
        static function is_nsfw( $post_id ){
            $post = get_post( $post_id );
            $meta = meta::get_meta( $post -> ID  , 'settings' );
            if( isset( $meta['safe'] ) ){
                if( meta::logic( $post , 'settings' , 'safe' ) ){
                    $result = true;
                }else{
                    $result = false;
                }
            }else{
                $result = false;
            }

            return $result;
        }

        static function is_grid( $template , $side = '' ){
            $grid = false;

            
            if( strlen( $side ) ){
                $values = options::get_value( 'layout' , 'v_' . $template );

                if($values == 'grid_view' ){
                    $grid = false;
                }else{
                    $grid = true;
                }
            }else{

                $values = options::get_value( 'layout' , 'v_' . $template );

                if($values == 'grid_view'){
                    $grid = true;
                }else{
                    $grid = false;
                }
            }
            

            return $grid;
        }

        static function is_thumbnails_view( $template , $side = '' ){
            $thumbnails_view = false;

            
            if( strlen( $side ) ){
                $values = options::get_value( 'layout' , 'v_' . $template );

                if($values == 'thumbnails_view' ){
                    $thumbnails_view = false;
                }else{
                    $thumbnails_view = true;
                }
            }else{
                $values = options::get_value( 'layout' , 'v_' . $template );

                if($values == 'thumbnails_view'){
                    $thumbnails_view = true;
                }else{
                    $thumbnails_view = false;
                }
            }
            

            return $thumbnails_view;
        }
        
        static function tour( $pos ,  $location , $id , $type , $title , $body , $nr ,  $next = true ){
            
            $nrs = explode('/' , $nr );
            
            /* stap */
            if( isset( $_COOKIE[ ZIP_NAME.'_tour_stap_' . $location . '_' . $id ] ) && (int)$_COOKIE[ ZIP_NAME.'_tour_stap_' . $location . '_' . $id ] > 0  ){
                $k = $_COOKIE[ ZIP_NAME.'_tour_stap_' . $location . '_' . $id ] + 1;
            }else{
                $k = 1;
            }
            
            if( $nrs[0] == $k ){
                $classes = '';
            }else{
                $classes = 'hidden';
            }
        ?>
            <div class="demo-tooltip <?php echo $classes; ?>" index="<?php echo $nrs[0] - 1; ?>" rel="<?php echo $location . '_' . $id; ?>" style="top: <?php echo $pos[0]; ?>px; left: <?php echo $pos[1]; ?>px; "><!--Virtual guide starts here. Set coordinates top and left-->
                <span class="arrow <?php echo $type; ?>">&nbsp;</span><!--Available arrow position: left, right, top -->
                <header class="demo-steps">
                    <strong class="fl"><?php echo $title; ?></strong>
                    <span class="fr"><?php echo $nr; ?></span><!--Step number from-->
                </header>
                <div class="demo-content">
                    <?php echo stripslashes( $body ); ?>
                    <?php
                        if( $next ){
                    ?>
                            <p class="fr close"><a href="#" class="close"><?php _e( 'Do not show hints anymore' , 'cosmotheme' ); ?></a></p>
                    <?php
                        }
                    ?>
                </div>
                <footer class="demo-buttons">
                    <?php
                        if( $next ){
                    ?>
                            <p class="fl button-small gray"><a href="#" class="next"><?php _e( 'Next feature' , 'cosmotheme' ); ?></a></p>
                            <p class="fr button-small blue"><a href="#" class="skip"><?php _e( 'Skip' , 'cosmotheme' ); ?></a></p>
                    <?php
                        }else{
                            ?><p class="fr button-small red"><a href="#" class="close"><?php _e( 'Close' , 'cosmotheme' ); ?></a></p><?php
                        }
                    ?>
                            
                    
                </footer>
            </div>
        <?php
        }

        function get_grid_nr_columns($template, $with_sidebar = true){ 
            if($with_sidebar){
                $nr_columns = options::get_value( 'layout' , 'columns_' . $template .'_sidebar');
            }else{
                $nr_columns = options::get_value( 'layout' , 'columns_' . $template .'_no_sidebar');
            }

            return $nr_columns;

        }

        function columns_arabic_to_word($arabic,$container_width = 'twelve'){
            if($container_width == 'twelve'){ /*if outer container has 12 columns*/
                $words = array(
                    1 => 'twelve',
                    2 => 'six',
                    3 => 'four',
                    4 => 'three',
                    5 => 'three',
                    6 => 'two',
                    7 => 'two',
                    8 => 'one',
                    9 => 'one',
                    10 => 'one',
                    11 => 'one',
                    12 => 'one',
                );
            }else{  /*if outer container has 9 columns*/
                $words = array(
                    1 => 'nine',
                    2 => 'nine',
                    3 => 'three',
                    4 => 'three',
                    5 => 'three',
                    6 => 'three',
                    7 => 'three',
                    8 => 'one',
                    9 => 'one',
                    10 => 'one',
                    11 => 'one',
                    12 => 'one',
                );
            } 

            
            
            return  $words[ $arabic ];
            
        }
    }
?>
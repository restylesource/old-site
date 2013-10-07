<?php
	class layout{
        static $size = array(
            'large' => 'twelve columns',
            'lmedium' => 'nine columns',
            'medium' => 'nine columns'
        );
        static function side( $side = 'right' , $post_id = 0 , $template = null ){
            $position = false;
            if( strlen( $side ) ){
                if( $post_id > 0 ){
                    $layout = meta::get_meta( $post_id , 'layout' );

                    if( isset( $layout['type'] ) && !empty( $layout['type'] ) ){
                        $result = $layout['type'];
                    }else{
                        
                        if( strlen( $template ) ){
                            $result = options::get_value( 'layout' , $template );
                        }else{
                            $result = $side;
                        }
                    }
                }else{
                    if( strlen( $template ) ){
                        $result = options::get_value( 'layout' , $template );
                    }else{
                        $result = $side;
                    }
                }

                if( $side == 'right' ){
                    $classes = 'right-sidebar';
                }else{
                    $classes = 'left-sidebar top-separator';
                }

                if( $result == $side ){
                    echo '<div id="secondary" class="three columns  ' . $classes . '" role="complementary">';
                    //echo '<div class="b w_260">';
                    if( is_author() ){
                        get_template_part('author-box');
                    }
                    if( isset( $layout['sidebar'] ) && !empty( $layout['sidebar'] ) ){                        
                        if(dynamic_sidebar ( $layout['sidebar'] ) ){

                        }
                    }else{
                        $layout = options::get_value( 'layout' , $template . '_sidebar' );
                        if( !empty( $layout ) ){
                            if(dynamic_sidebar ( $layout ) ){

                            }
                        }else{
                            get_sidebar( );
                        }
                        
                    }
                    //echo '</div>';
                    echo '</div>';

                    $position = true;
                }
            }

            return $position;
        }

        static function length( $post_id = 0 , $template = null , $larger = false ){
            $layout = meta::get_meta( $post_id , 'layout' );
            if( isset( $layout['type'] ) && !empty( $layout['type'] ) && $layout['type'] == 'full' ) {
                $length = self::$size['large'];
            }else{
                if( strlen( $template ) ){
                    $result = options::get_value( 'layout' , $template );
                    if( $result == 'full' ){
                        if( isset( $layout['type'] ) && $layout['type'] != 'full' ){
                            if( $larger ){
                                $length = self::$size['lmedium'];
                            }else{
                                $length = self::$size['medium'];
                            }
                        }else{
                            $length = self::$size['large'];
                        }
                    }else{
                        if( $larger ){
                            $length = self::$size['lmedium'];
                        }else{
                            $length = self::$size['medium'];
                        }
                    }
                }
            }

            return $length;
        }

        static function is_meta_content($post, $template = 'single', $side='left'){

            if( meta::logic( $post , 'settings' , 'meta' )){
                $layout = meta::get_meta( $post -> ID , 'layout' );

                if( !isset( $layout['type'] ) || empty( $layout['type'] ) ){  
                    /*if post meta is not set we look at the global options.*/
                    if(options::get_value( 'layout' , $template ) == $side){    
                        /*if global option is equal to the one we check against we return true*/
                        return true;
                    }else{
                        return false;
                    }
                }


                if( isset( $layout['type'] ) && !empty( $layout['type'] )  ) {  
                    if($layout['type'] == $side){
                        return true;
                    }else{

                        return false;    
                    }
                    
                }
            }else{
                return false;
            }

        }

	}
?>
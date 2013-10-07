<?php
    class like{
        
        function set( $post_id = 0 , $meta_type = 'like'){

            if( $post_id == 0 ){
                $post_id = isset( $_POST['post_id' ]) ? (int) $_POST['post_id'] : exit;
                $meta_type = isset( $_POST['meta_type' ]) ?  $_POST['meta_type'] : exit;
                $ajax = true;
            }else{
                $ajax = false;
            }

            if($meta_type == 'like'){
                $anti_meta_type = 'hate';
            }else{
                $anti_meta_type = 'like';
            }    
            /*actually it may be like or hate*/
            /*get the likes/hates for the current post*/
            $likes = meta::get_meta( $post_id , $meta_type );
            /*get the oposite values of the current action, for example if 'like'  is clicked, we count here the 'hates',
            and vice-versa  
            */
            $anti_likes = meta::get_meta( $post_id , $anti_meta_type );
            
            

            $user       = true;
            $user_ip    = true;
            $anti_user   = '';
            $anti_user_ip = '';
                        
            $ip     = $_SERVER['REMOTE_ADDR'];

            if( is_user_logged_in () ){
                $user_id = get_current_user_id();
            }else{
                $user_id = 0;
            }

            if( $user_id > 0 ){
                /* likes/hates by user */
                foreach( $likes as  $like ){
                    if( isset( $like['user_id'] ) && $like['user_id'] == $user_id ){  
                        /* if this user already clicked this button for this post*/
                        $user   = false;
                        $user_ip = false;
                    }
                }
                
                foreach( $anti_likes as  $anti_like ){
                    if( isset( $anti_like['user_id'] ) && $anti_like['user_id'] == $user_id ){  
                        /* if this user already clicked this button for this post*/
                        $anti_user   = $user_id;
                        $anti_user_ip = $ip;
                    }
                }
                
            }else{
                if( options::logic( 'general' , 'like_register' ) ){
                    /*if likes are not enabled */
                    if( $ajax ){
                        exit;
                    }else{
                        return '';
                    }
                }
                foreach( $likes as  $like ){
                    if( isset( $like['ip'] ) && ( $like['ip'] == $ip ) ){
                        /* if a user from the same IP already clicked this button for this post*/
                        $user = false;
                        $user_ip = false;
                    }
                }
                
                foreach( $anti_likes as  $anti_like ){
                    if( isset( $anti_like['ip'] ) && ( $anti_like['ip'] == $ip ) ){
                        /* if a user from the same IP already clicked this button for this post*/
                        $anti_user = $user_id;
                        $anti_user_ip = $ip;
                    }
                }
                
            }

            if( $user && $user_ip ){   
                /* add like */ 
                $likes[] = array( 'user_id' => $user_id , 'ip' => $ip );
                meta::set_meta( $post_id , 'nr_'.$meta_type , count( $likes ) );
                meta::set_meta( $post_id , $meta_type ,  $likes );

                if($meta_type == 'like'){
                    self::attachUserVote($post_id); /*add this post to user's voted_posts meta*/
                }else{
                    self::removeUserVote($post_id); /*remove this post from user's voted_posts meta*/
                }

                $date = meta::get_meta( $post_id , 'hot_date' );

                if( empty( $date ) ){
                    if($meta_type == 'like'){
                        if( ( count( $likes ) >= (int)options::get_value( 'general' , 'min_likes' ) ) ){
                            meta::set_meta( $post_id , 'hot_date' , mktime() );
                        }
                    }else{
                        if( ( count( $anti_likes ) >= (int)options::get_value( 'general' , 'min_likes' ) ) ){
                            meta::set_meta( $post_id , 'hot_date' , mktime() );
                        }

                    }   

                }else{
                    if($meta_type == 'like'){
                        if( ( count( $likes ) < (int)options::get_value( 'general' , 'min_likes' ) ) ){
                            delete_post_meta( $post_id, 'hot_date' );
                        }
                    }else{
                        if( ( count( $anti_likes ) < (int)options::get_value( 'general' , 'min_likes' ) ) ){
                            delete_post_meta( $post_id, 'hot_date' );
                        }
                    }    
                }
                
                /*now we have to check if this user previously  clicked on the other button,
                    if he did, then we will decrease that value 
                */ 
                if( strlen($anti_user)  && strlen($anti_user_ip)){ 
                    /* delete the oposite value of the current action */
                    if( $user_id > 0 ){
                        foreach( $anti_likes as $index => $like ){
                            if( isset( $like['user_id'] ) && $like['user_id'] == $user_id ){
                                unset( $anti_likes[ $index ] );
                            }
                        }
                    }else{
                        if( options::logic( 'general' , 'like_register' ) ){
                            if( $ajax ){
                                exit;
                            }else{
                                return '';
                            }
                        }
                        foreach( $anti_likes as $index => $like ){
                            if( isset( $like['ip'] ) && isset( $like['user_id'] ) && ( $like['ip'] == $ip ) && ( $like['user_id'] == 0 ) ){
                                unset( $anti_likes[ $index ] );
                            }
                        }
                    }
                    
                    meta::set_meta( $post_id , $meta_type ,  $likes );
                    meta::set_meta( $post_id , 'nr_'.$meta_type ,  count( $likes ) );
                    
                    meta::set_meta( $post_id , $anti_meta_type ,  $anti_likes );
                    meta::set_meta( $post_id , 'nr_'.$anti_meta_type ,  count( $anti_likes ) );
                    
                        

                    if($meta_type == 'like'){
                        if( count( $likes ) < (int)options::get_value( 'general' , 'min_likes' ) ){ 
                            delete_post_meta($post_id, 'hot_date' );
                        }
                    }else{

                        if( count( $anti_likes ) < (int)options::get_value( 'general' , 'min_likes' ) ){ 
                            delete_post_meta($post_id, 'hot_date' );
                        }
                    }
                }
            }

            if( $ajax ){
                
                if($meta_type == 'like'){
                    $response = array(
                        'likes' => (int)count( $likes ),
                        'hates' => (int)count( $anti_likes ),
                        'like_percentage' => self::get_like_percentage( (int)count( $likes ), (int)count( $anti_likes ) ),
                    ); 
                }else{
                    $response = array(
                        'likes' => (int)count( $anti_likes  ),
                        'hates' => (int)count( $likes ),
                        'like_percentage' => self::get_like_percentage( (int)count( $anti_likes ), (int)count( $likes ) ),
                    ); 
                }   
                echo json_encode($response);  //(int)count( $likes );
                exit;
            }
        }

        function get_like_percentage($nr_likes = 0, $nr_hates = 0){
            $total_nr_votes = $nr_likes + $nr_hates;
            if($total_nr_votes == 0){
                return 100;
            }else{
                $like_percentage = round((100*$nr_likes)/$total_nr_votes);
                return $like_percentage;
            }
        }
        
        function is_voted( $post_id, $like_type = 'like' ){
            $ip     = $_SERVER['REMOTE_ADDR'];

            //$likes = meta::get_meta( $post_id , 'like' );
            $likes = meta::get_meta( $post_id , $like_type );
            if( is_user_logged_in () ){
                $user_id = get_current_user_id();
            }else{
                $user_id = 0;
            }

            if( $user_id > 0 ){
                foreach( $likes as $like ){
                    if( isset( $like['user_id'] ) && $like['user_id'] == $user_id ){
                        return true;
                    }
                }
            }else{
                foreach( $likes as $like ){
                    if( isset( $like['ip'] ) && $like['ip'] == $ip ){
                        return true;
                    }
                }
            }

            return false;
        }

        function can_vote( $post_id, $like_type = 'like' ){
            $ip     = $_SERVER['REMOTE_ADDR'];

            

            if( is_user_logged_in () ){
                $user_id = get_current_user_id();
            }else{
                $user_id = 0;
            }

            if( options::logic( 'general' , 'like_register' ) && $user_id == 0 ){
                return false;
            }

            if( $user_id == 0 ){
                $likes = meta::get_meta( $post_id , $like_type );
                foreach( $likes as $like ){
                    if( isset( $like['user_id'] ) && $like['user_id'] > 0  && $like['ip'] == $ip ){
                        return false;
                    }
                }
            }

            return true;
        }

        function sim_likes(){
            global $wp_query;
            $paged      = isset( $_POST['page']) ? $_POST['page'] : exit;
            $wp_query = new WP_Query( array('posts_per_page' => 150 , 'post_type' => 'post' , 'paged' => $paged ) );
            

            foreach( $wp_query -> posts as $post ){
                $likes = array();
                $ips = array();
                $nr = rand( 60 , 200 );
                while( count( $likes ) < $nr ){
                    $ip = rand( -255 , -100 ) .  rand( -255 , -100 )  . rand( -255 , -100 ) . rand( -255 , -100 );

                    $ips[ $ip ] = $ip;

                    if( count( $ips )  > count( $likes ) ){
                        $likes[] = array( 'user_id' => 0 , 'ip' => $ip );
                    }
                }

                meta::set_meta( $post -> ID , 'nr_like' , count( $likes ) );
                meta::set_meta( $post -> ID , 'like' ,  $likes );
                meta::set_meta( $post -> ID , 'hot_date' , mktime() );
            }
            
            if( $wp_query -> max_num_pages >= $paged ){
                if( $wp_query -> max_num_pages == $paged ){
                    echo 0;
                }else{
                    echo $paged + 1;
                }
            }
            
            exit();
        }
        
        function min_likes(){
            global $wp_query;
            $new_limit  = isset( $_POST['new_limit']) ? $_POST['new_limit'] : exit;
            $paged      = isset( $_POST['page']) ? $_POST['page'] : exit;

            $wp_query = new WP_Query( array('posts_per_page' => 150 , 'post_type' => 'post' , 'paged' => $paged ) );
            foreach( $wp_query -> posts as $post ){
                $likes = meta::get_meta( $post -> ID , 'like' );
                meta::set_meta( $post -> ID , 'nr_like' , count( $likes ) );
                if( count( $likes ) < (int)$new_limit ){
                    delete_post_meta( $post -> ID, 'hot_date' );
                }else{
                    if( (int)meta::get_meta( $post -> ID , 'hot_date' ) > 0 ){

                    }else{
                        meta::set_meta( $post -> ID , 'hot_date' , mktime() );
                    }
                }
            }
            if( $wp_query -> max_num_pages >= $paged ){
                if( $wp_query -> max_num_pages == $paged ){
                    $general = options::get_value( 'general' );
                    $general['min_likes'] = $new_limit;
                    update_option( 'general' , $general );
                    echo 0;
                }else{
                    echo $paged + 1;
                }
            }

            exit();
        }

        static function count( $post_id, $like_type = 'like' ){
            $result = meta::get_meta( $post_id , $like_type );
            return count( $result );
        }


        static function content( $post_id , $st , $return = false, $like_type = 'like', $show_label = false, $simple_view = false ){
            if( $return ){
                ob_start();
                ob_clean();
            }
            $post = get_post( $post_id );
            if( options::logic( 'general' , 'enb_likes' ) ){
                $li_class = '';
                if( !like::can_vote( $post -> ID ) ){
                    $li_click = "get_login_box('like')";
                }
                if($like_type == 'like'){
                    $li_class .= 'like';
                    $span_class = 'love';
                    $label =  options::get_value( 'general' , 'like_label' );
                }else{
                    $li_class .= 'dislike';
                    $span_class = 'hate';
                    $label =  options::get_value( 'general' , 'dislike_label' );
                }
                
                    $meta = meta::get_meta( $post -> ID  , 'settings' );
                if( isset( $meta['love'] ) ){
                    if( meta::logic( $post , 'settings' , 'love' ) ){
?>

                    <?php if(!$simple_view){ ?>
                    <li>
                        
                    <?php } /*EOF if ! $simpl_eview*/ ?>        
                            <i class="vote  <?php echo  $span_class.'-'.$post -> ID.' '. $span_class.' '; if( like::is_voted( $post -> ID, $like_type ) ){ echo 'active'; } ?>"
                            <?php 
                                if( like::can_vote( $post -> ID, $like_type ) ){
                                    //echo "onclick=\"javascript:act.like(" . $post -> ID . ", '.like-" . $post -> ID . "' , " . $st . " );\"";
                                    echo "onclick=\"javascript:act.like(" . $post -> ID . ", '.".$like_type."-" . $post -> ID . "' , " . $st . ", '".$like_type."' );\"";               
                                }else{
                                    echo 'onclick="'.$li_click.'"';
                                }
                            ?>
                            >
                                <em>
                                    <?php
                                        if($show_label){ echo '<div>'.$label.'</div>'; }
                                    ?>
                                    
                                    <strong class="<?php echo $like_type.'-'.$post -> ID; ?>" >
                                        <?php echo self::count( $post -> ID, $like_type ); ?>
                                    </strong>
                                </em>
                            </i>
                    <?php if(!$simple_view){ ?>        
                        
                    </li>
                    <?php } /*EOF if ! $simpl_eview*/ ?>        
<?php
                    }
                }else{
?>
                    <?php if(!$simple_view){ ?>  
                    <li >
                        
                    <?php } /*EOF if ! $simpl_eview*/ ?>         
                            <i class="vote  <?php echo  $span_class.'-'.$post -> ID.' '. $span_class.' '; if( like::is_voted( $post -> ID, $like_type ) ){ echo 'active'; } ?>" 
                                <?php
                                    if( like::can_vote( $post -> ID ) ){
                                        echo "onclick=\"javascript:act.like(" . $post -> ID . ", '.".$like_type."-" . $post -> ID . "' , " . $st . ", '".$like_type."' );\"";

                                    }else{
                                        echo 'onclick="'.$li_click.'"';
                                    }
                                ?>
                            >
                                <em>
                                    <?php
                                        if($show_label){ echo '<div>'.$label.'</div>'; }
                                    ?>
                                    
                                    <strong class="<?php echo $like_type.'-'.$post -> ID; ?>" >
                                        <?php echo self::count( $post -> ID, $like_type ); ?>
                                    </strong>
                                </em>
                            </i>
                    <?php if(!$simple_view){ ?>          
                        
                    </li>
                    <?php } /*EOF if ! $simpl_eview*/ ?> 
<?php
                }
            }
            
            if( $return ){
                $result = ob_get_clean();
                return $result;
            }
        }

        public static function attachUserVote( $post_id ){ /*add voted post to user meta*/
            
            if ( is_user_logged_in() ) {
                /* we will store voted posts as an array in a meta data called voted_posts */
                global $current_user;
                get_currentuserinfo();
                $user_id = $current_user->ID;
        
                $voted_posts = array();
        
                if(is_array(get_user_meta( $user_id, ZIP_NAME.'_voted_posts',true ) ) ){
                    $voted_posts = get_user_meta( $user_id, ZIP_NAME.'_voted_posts',true  );
                    if( !in_array( $post_id , $voted_posts  ) ){
                        $voted_posts[] = $post_id;
                        update_user_meta( $user_id, ZIP_NAME.'_voted_posts', $voted_posts );
                    }
                }else{
                    $voted_posts[] = $post_id;
                    update_user_meta( $user_id, ZIP_NAME.'_voted_posts', $voted_posts );  
                }
                    
                
                
                
            }   
            
        }
        
        public static function removeUserVote( $post_id ){ /*add voted post to user meta*/
            
            if ( is_user_logged_in() ) {
                /* we will store voted posts as an array in a meta data called voted_posts */
                global $current_user;
                get_currentuserinfo();
                $user_id = $current_user->ID;
        
                $voted_posts = array();
        
                if(is_array(get_user_meta( $user_id, ZIP_NAME.'_voted_posts',true ) ) ){
                    $voted_posts = get_user_meta( $user_id, ZIP_NAME.'_voted_posts',true  );
                    
                    if( in_array( $post_id , $voted_posts  ) ){ /*if current post  was found in the user meta data we will remove it*/
                        unset($voted_posts[ array_search ( $post_id , $voted_posts  ) ] );
                        update_user_meta( $user_id, ZIP_NAME.'_voted_posts', $voted_posts );
                    }
                }
                
                
                
                
                
            }   
            
        }

        function get_love_hate($post_id, $class=''){
           
        ?>
            <ul class="<?php echo $class; ?>">
                
                <?php like::content($post_id, 3, false, 'like'); ?>
           
                <?php like::content($post_id, 3, false, 'hate'); ?>
                   
            </ul>
            
        <?php   
        }

        function reset_likes(){
            global $wp_query;
            $paged      = isset( $_POST['page']) ? $_POST['page'] : exit;
            $wp_query = new WP_Query( array('posts_per_page' => 150 , 'post_type' => 'post' , 'paged' => $paged ) );
            

            foreach( $wp_query -> posts as $post ){
                delete_post_meta($post -> ID, 'nr_like' );
                delete_post_meta($post -> ID, 'like' );
                delete_post_meta($post -> ID, 'hot_date' );
                delete_post_meta($post -> ID, 'hate');
            }
            
            if( $wp_query -> max_num_pages >= $paged ){
                if( $wp_query -> max_num_pages == $paged ){
                    echo 0;
                }else{
                    echo $paged + 1;
                }
            }
            
            exit();
        }
    }
?>
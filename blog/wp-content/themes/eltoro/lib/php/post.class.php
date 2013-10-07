<?php

class post {
    static $post_id = 0;
    function get_my_posts( $author){
        $wp_query = new WP_Query( array('post_status' => 'any', 'post_type' => 'post' , 'author' => $author ) );
        if( count( $wp_query -> posts ) > 0 ){
            return true;
        }else{
            return false;
        }
    }
    function my_posts( $author ){
        global $wp_query; 
        
        if ((int) get_query_var('paged') > 0) {
            $paged = get_query_var('paged');
        } else {
            if ((int) get_query_var('page') > 0) {
                $paged = get_query_var('page');
            } else {
                $paged = 1;
            }
        }
        
        if( (int)$author > 0  ){
            $wp_query = new WP_Query(array('post_status' => 'any', 'post_type' => 'post', 'paged' => $paged, 'author' => $author  ));
            

            if(count($wp_query -> posts)){
                foreach( $wp_query -> posts as $key => $post ){
                    $wp_query -> the_post();
                    if( $key > 0 ){
                ?>
                        <p class="delimiter">&nbsp;</p>
                <?php 
                    }
                ?>
                    <div id="post-<?php echo $post->ID; ?>" <?php post_class('post'); ?>>
                        <div class="entry-content">
                            <h2 class="caption">
                                <?php
                                    if( $post -> post_status == 'publish' ){
                                        ?><a href="<?php echo get_permalink( $post -> ID )?> " title="<?php echo __( 'Permalink to ' , 'cosmotheme' ) . $post -> post_title; ?>" rel="bookmark"><?php echo $post -> post_title; ?></a><?php
                                    }else{
                                        echo $post -> post_title;
                                    }
                                ?>
                            </h2>
                            <div class="entry-meta">
                                <ul>
                                    <?php if(options::logic( 'upload' , 'enb_edit_delete' ) && is_user_logged_in() && $post->post_author == get_current_user_id() && is_numeric(options::get_value( 'upload' , 'post_item_page' ))){ ?> 
                                        <li class="edit_post" title="<?php _e('Edit post','cosmotheme') ?>"><a href="<?php  echo add_query_arg( 'post', $post->ID, get_page_link(options::get_value( 'upload' , 'post_item_page' ))  ) ;  ?>"  ><?php echo _e('Edit','cosmotheme'); ?></a></li>    
                                    <?php }   ?>
                                    <?php if( options::logic( 'upload' , 'enb_edit_delete' )  && is_user_logged_in() && $post->post_author == get_current_user_id() ){  
                                        $confirm_delete = __('Confirm to delete this post.','cosmotheme');
                                    ?>
                                    <li class="delete_post" title="<?php _e('Remove post','cosmotheme') ?>"><a href="javascript:void(0)" onclick="if(confirm('<?php echo $confirm_delete; ?> ')){ removePost('<?php echo $post->ID; ?>','<?php echo home_url() ?>');}" ><?php echo _e('Delete','cosmotheme'); ?></a></li>
                                    <?php  } ?>    
                                </ul>
                            </div>
                            <div class="excerpt bottom-separator"> 
                                <?php echo the_excerpt(); ?>
                            </div>
                        </div>
                    </div>
                <?php
                }
            
                get_template_part('pagination');
            }else{
                echo '<h2 class="content-title twelve columns  author">'.__('Nothing found.','cosmotheme').'</h2>';
            }    
            
        }else{
            get_template_part('loop', '404');
        }
    }
    
    function like( $paginate = true ) {
        
        global $wp_query;
        $uid = get_current_user_id();
        $template = 'author';
        $voted_posts = get_user_meta( $uid, ZIP_NAME.'_voted_posts',true );


        if(is_array($voted_posts) && sizeof($voted_posts)){

            if ((int) get_query_var('paged') > 0) {
                $paged = get_query_var('paged');
            } else {
                if ((int) get_query_var('page') > 0) {
                    $paged = get_query_var('page');
                } else {
                    $paged = 1;
                }
            }

            $wp_query = new WP_Query( array( 'post_type' => array( 'post', 'page'), 
                                            'post_status' => 'publish' , 
                                            'post__in' => $voted_posts, 
                                            'fp_type' => 'like', 
                                            'paged' => $paged  ) );

            
            $grid = tools::is_grid($template, '_new');

            if($grid){
                $grid_list_class = ' grid_view horizontal-posts' ;
            }else{
                $grid_list_class = ' list_view vertical-posts';
            }

            $home_url = home_url();
            $news = array('fp_type' => "news");
            $news_url = add_query_arg($news, $home_url);

            

            echo '<div class="row ">';                    
            
            ?><h3 class="content-title "><?php _e( 'My Loved posts ' , 'cosmotheme' );  ?></h3><?php
            echo '</div>';
            
            
            /* content */
            self::loop('author', '' , $paginate );
            
        }else{

            echo '<h2 class="content-title twelve columns  author">'.__('Nothing found.','cosmotheme').'</h2>';
        }

       
    }

   
    
    function filter_where( $where = '' ) {
        global $wpdb;
        if( self::$post_id > 0 ){
            $where .= " AND  ".$wpdb->prefix."posts.ID < " . self::$post_id;
        }
        return $where;
    }
        
    function random_posts($no_ajax = false) {
        global $wp_query;
        if ((int) get_query_var('paged') > 0) {
            $paged = get_query_var('paged');
        } else {
            if ((int) get_query_var('page') > 0) {
                $paged = get_query_var('page');
            } else {
                $paged = 1;
            }
        }

        $wp_query = new WP_Query(array('post_status' => 'publish', 'post_type' => 'post', 'posts_per_page' => 1, 'orderby' => 'rand', 'paged' => $paged));

        if ($wp_query->found_posts > 0) {
            $k = 0;
            foreach ($wp_query->posts as $post) {
                $wp_query->the_post();
                $result = get_permalink($post->ID);
            }
        }

        if (isset($no_ajax) && $no_ajax) {
            return $result;
        } else {
            echo $result;
            exit;
        }
    }
    
        
    function search(){
        
        $query = isset( $_GET['params'] ) ? (array)json_decode( stripslashes( $_GET['params'] )) : exit;
        $query['s'] = isset( $_GET['query'] ) ? $_GET['query'] : exit;
        
        global $wp_query;
        $result = array();
        $result['query'] = $query['s'];
        
        $wp_query = new WP_Query( $query );
        
        if( $wp_query -> have_posts() ){
            foreach( $wp_query -> posts as $post ){
                $result['suggestions'][] = $post -> post_title;
                $result['data'][] =  $post -> ID;
            }
        }
        
        echo json_encode( $result );
        exit();
    }
    
    function list_view_archive($post, $template = 'blog_page') {
            $template_content_class = tools::primary_class( 0 , $template, $return_just_class = true );

            if($template_content_class == 'twelve columns'){
                $full_width = true;
                if(post::is_feat_enabled($post->ID)){
                    $header_class = 'six columns';
                    $content_class = 'six columns';
                }else{
                    $header_class = '';
                    $content_class = 'twelve columns';
                }
                
            }else{
                $full_width = false;
                if(post::is_feat_enabled($post->ID)){
                    $header_class = 'five columns';
                    $content_class = 'four columns';
                }else{
                    $header_class = '';
                    $content_class = 'nine columns';
                }
                
            }

            $classes = tools::login_attr($post->ID, 'nsfw');
            $attr = tools::login_attr($post->ID, 'nsfw mosaic-overlay ', get_permalink($post->ID), $additional_class     = 'details link');
            $size = 'tmedium';
            $s = image::asize( image::size( $post->ID , $template , $size ) );

            
            if( get_post_format( $post -> ID ) == 'video' ){
                $format = meta::get_meta( $post -> ID , 'format' );

                if( isset( $format['feat_id'] ) && !empty( $format['feat_id'] ) )
                  {
                    $video_id = $format['feat_id'];
                    $video_type = 'self_hosted';
                    if(isset($format['feat_url']) && post::isValidURL($format['feat_url']))
                      {
                        $vimeo_id = post::get_vimeo_video_id( $format['feat_url'] );
                        $youtube_id = post::get_youtube_video_id( $format['feat_url'] );
                        
                        if( $vimeo_id != '0' ){
                          $video_type = 'vimeo';
                          $video_id = $vimeo_id;
                        }

                        if( $youtube_id != '0' ){
                          $video_type = 'youtube';
                          $video_id = $youtube_id;
                        }
                      }

                    if(isset($video_type) && isset($video_id) && is_user_logged_in () ){
                        if($video_type == 'self_hosted'){
                            $onclick = 'playVideo("'.urlencode(wp_get_attachment_url($video_id)).'","'.$video_type.'",jQuery(this).parents(".hovermore"),jQuery(this).parent().width(),jQuery(this).parent().width()/1.37)';
                        }else{
                            $onclick = 'playVideo("'.$video_id.'","'.$video_type.'",jQuery(this).parents(".hovermore"),jQuery(this).parent().width(),jQuery(this).parent().width()/1.37)';
                        }    
                        
                    }else{
                        $meta = meta::get_meta( $post -> ID  , 'settings' );
                        if( isset( $meta['safe'] ) ){
                            if( !meta::logic( $post , 'settings' , 'safe' ) ){      
                                $onclick = 'playVideo("'.$video_id.'","'.$video_type.'",jQuery(this).parents(".hovermore"),jQuery(this).parent().width(),jQuery(this).parent().width()/1.37)';
                            }else{
                                $onclick = '';
                            }
                        }else{
                            if($video_type == 'self_hosted'){
                                $onclick = 'playVideo("'.urlencode(wp_get_attachment_url($video_id)).'","'.$video_type.'",jQuery(this).parents(".hovermore"),jQuery(this).parent().width(),jQuery(this).parent().width()/1.37)';
                            }else{
                                $onclick = 'playVideo("'.$video_id.'","'.$video_type.'",jQuery(this).parents(".hovermore"),jQuery(this).parent().width(),jQuery(this).parent().width()/1.37)';
                            }    
                        }   
                    }
                }
            }
        ?>
        <article class="post row">
            <div class="row">
                <div class="<?php echo $template_content_class; ?> ">
                    <?php if( options::logic( 'general' , 'enb_likes' ) ){ 
                       like::content($post->ID, 3, false, 'like', false, true);
                    } ?>
                    <h3>
                        <a <?php echo tools::login_attr($post->ID, 'nsfw', get_permalink($post->ID)) ?> title="<?php _e('Permalink to', 'cosmotheme'); ?> <?php echo $post->post_title; ?>" rel="bookmark"><?php echo $post->post_title; ?></a>
                    </h3>
                    <div class="entry-meta">
                        <ul>
                            <li>
                                <?php _e('by','cosmotheme'); ?> <a href="<?php echo get_author_posts_url($post->post_author) ?>"><?php echo get_the_author_meta('display_name', $post->post_author); ?></a>   
                            </li>
                            <li>
                                <?php echo post::get_post_date($post -> ID); ?>  
                            </li>
                            <?php 
                       
                                if($post -> post_type == 'post'){
                                    $categ = post::get_post_categories($post -> ID); 
                                    if(strlen($categ)){
                            ?>
                                    <li><?php _e('in','cosmotheme'); echo ' ' . $categ; ?></li>
                            <?php        
                                    }
                                }
                            ?> 

                            <?php 
                       
                                if($post -> post_type == 'post'){
                                    $portfolios = post::get_post_categories($post -> ID, false,'portfolio'); 
                                    if(strlen(trim($portfolios))){
                            ?>
                                    <li><?php echo ' ' . $portfolios; ?></li>
                            <?php        
                                    }
                                }
                            ?>

                            <?php
                            if (comments_open($post->ID)) {
                                
                                $comments_label = __('replies','cosmotheme');
                                if (options::logic('general', 'fb_comments')) {
                                    ?>
                                    <li>
                                        <a <?php echo tools::login_attr($post->ID, 'nsfw', get_comments_link($post->ID)) ?>>
                                            
                                                <fb:comments-count href="<?php echo get_permalink($post->ID) ?>"></fb:comments-count>
                                            
                                            <?php echo $comments_label; ?>
                                        </a>
                                    </li>        
                                    <?php
                                } else {
                                    if(get_comments_number($post->ID) == 1){
                                        $comments_label = __('reply','cosmotheme');    
                                    }
                                    ?>
                                    <li>
                                        <a <?php echo tools::login_attr($post->ID, 'nsfw', get_comments_link($post->ID)) ?>>
                                            
                                                <?php echo get_comments_number($post->ID) ?>
                                            
                                            <?php echo $comments_label; ?>
                                        </a>
                                    </li>        
                                    <?php
                                }
                            }
                            ?>
                            <?php 
                                if ( function_exists( 'stats_get_csv' ) ){  
                                $views = stats_get_csv( 'postviews' , "&post_id=" . $post -> ID);    
                                if( (int)$views[0]['views'] == 1) {
                                    $view_label = __( 'view' , 'cosmotheme');
                                }else{
                                    $view_label = __( 'views' , 'cosmotheme' );
                                }
                            ?>
                                <li>
                                    <?php echo (int)$views[0]['views']; echo ' '.$view_label; ?>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div> 
            <div class="row">   
                <div class="row <?php echo $template_content_class; ?>">
                    <?php if(post::is_feat_enabled($post->ID)){ ?>
                    <header class="<?php echo $header_class; ?>">
                        <div class="hovermore">
                            <div class="mosaic-overlay">
                            <?php
                            if (get_post_format($post->ID) == 'video') {
                                if(isset($onclick)){
                                    $click = "onclick=".$onclick;
                                }else{
                                    $click = '';
                                }
                                    
                                echo '<a href="javascript:void(0);" '.$click.' class="mosaic-overlay video">&nbsp;</a>';
                            }else{
                                echo '<a href="'.get_permalink($post->ID).'" class="mosaic-overlay link">&nbsp;</a>';
                            }
                            ?>
                            </div>
                            <div class="relative">
                                <div class="featimg relative">
                                    <div class="img">
                                        <?php
                                            if (has_post_thumbnail($post->ID)) {
                                                $src = image::thumbnail($post->ID, $template, $size);
                                                $caption = image::caption($post->ID);
                                                

                                                if (strlen($classes)) {
                                                    ?>

                                                    
                                                    <div class="format">&nbsp;</div>
                                                    <img src="<?php echo get_template_directory_uri() ?>/images/nsfw.570x380.png" />
                                                    <?php
                                                    if (options::logic('styling', 'stripes')) {
                                                        ?><div class="stripes" >&nbsp;</div><?php
                                                    }
                                                    ?>
                                                    <?php
                                                    if (get_post_format($post->ID) == 'video') {
                                                        echo '<div class="video">&nbsp;</div>';
                                                    }
                                                    ?>
                                                    <?php
                                                } else {
                                                    ?>
                                                    
                                                    <div class="format">&nbsp;</div>
                                                    <img src="<?php echo $src[0]; ?>" alt="<?php echo $caption; ?>" >
                                                    <?php
                                                    if (options::logic('styling', 'stripes')) {
                                                        ?><div class="stripes" >&nbsp;</div><?php
                                                    }
                                                    ?>
                                                    <?php
                                                    if (get_post_format($post->ID) == 'video') {
                                                        echo '<div class="video">&nbsp;</div>';
                                                    }
                                                    ?>
                                                    <?php
                                                }
                                            } else{
                                                if (strlen($classes)) {
                                                    ?>
                                                    
                                                    <div class="format">&nbsp;</div>
                                                    <img src="<?php echo get_template_directory_uri() ?>/images/nsfw.570x380.png" />
                                                    <?php
                                                    if (options::logic('styling', 'stripes')) {
                                                        ?><div class="stripes" style="height: <?php echo $s[1]; ?>px">&nbsp;</div><?php
                                                    }
                                                    ?>
                                                    <?php
                                                    if (get_post_format($post->ID) == 'video') {
                                                        echo '<div class="video">&nbsp;</div>';
                                                    }
                                                    ?>
                                                    <?php
                                                } else {
                                                    ?>
                                                    
                                                    <div class="format">&nbsp;</div>
                                                    <img src="<?php echo get_template_directory_uri() ?>/images/noimage.570x380.png" alt="" />
                                                    <?php
                                                    if (options::logic('styling', 'stripes')) {
                                                        ?><div class="stripes" >&nbsp;</div><?php
                                                    }
                                                    ?>
                                                    <?php
                                                    if (get_post_format($post->ID) == 'video') {
                                                        echo '<div class="video">&nbsp;</div>';
                                                    }
                                                    ?>
                                                    <?php
                                                }
                                            }
                                        ?>
                                    </div>
                                    <div class="format">&nbsp;</div>
                                    <?php if (options::logic('styling', 'stripes')) {  ?>
                                        <div class="stripes" >&nbsp;</div>
                                    <?php }?>
                                </div>
                            </div>
                        </div>
                    </header>
                    <?php } ?>
                    <div class="entry-content <?php echo $content_class; ?>">
                        
                        <div class="excerpt"> 
                            <?php
                                post::get_excerpt($post, $ln = 400)
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </article>
        
                  
        
        <?php
    }

    function list_view_thumbs($post, $display= 'none' ) {
        /*returns markup for preview boxes when list view thumbs is selected*/

        if(options::get_value( 'layout' , 'front_page' ) == 'full'){
             $fullwidth = true;
            $content_class = "four columns";          
            $header_class = "four columns";
            $container_class = 'eight columns';
        }else{
            $fullwidth = false;
            $content_class = "four columns";
            $header_class = "five columns";
            $container_class = 'row';
        }

        $template = 'front_page';
        $classes = tools::login_attr($post->ID, 'nsfw');
        $size = 'tgrid';

        if( get_post_format( $post -> ID ) == 'video' ){
                $format = meta::get_meta( $post -> ID , 'format' );

                if( isset( $format['feat_id'] ) && !empty( $format['feat_id'] ) )
                  {
                    $video_id = $format['feat_id'];
                    $video_type = 'self_hosted';
                    if(isset($format['feat_url']) && post::isValidURL($format['feat_url']))
                      {
                        $vimeo_id = post::get_vimeo_video_id( $format['feat_url'] );
                        $youtube_id = post::get_youtube_video_id( $format['feat_url'] );
                        
                        if( $vimeo_id != '0' ){
                          $video_type = 'vimeo';
                          $video_id = $vimeo_id;
                        }

                        if( $youtube_id != '0' ){
                          $video_type = 'youtube';
                          $video_id = $youtube_id;
                        }
                      }

                    if(isset($video_type) && isset($video_id) && is_user_logged_in () ){
                        if($video_type == 'self_hosted'){
                            $onclick = 'playVideo("'.urlencode(wp_get_attachment_url($video_id)).'","'.$video_type.'",jQuery(this).parents(".hovermore"),jQuery(this).parent().width(),jQuery(this).parent().width()/1.37)';
                        }else{
                            $onclick = 'playVideo("'.$video_id.'","'.$video_type.'",jQuery(this).parents(".hovermore"),jQuery(this).parent().width(),jQuery(this).parent().width()/1.37)';
                        }    
                        
                    }else{
                        $meta = meta::get_meta( $post -> ID  , 'settings' );
                        if( isset( $meta['safe'] ) ){
                            if( !meta::logic( $post , 'settings' , 'safe' ) ){      
                                $onclick = 'playVideo("'.$video_id.'","'.$video_type.'",jQuery(this).parents(".hovermore"),jQuery(this).parent().width(),jQuery(this).parent().width()/1.37)';
                            }else{
                                $onclick = '';
                            }
                        }else{
                            if($video_type == 'self_hosted'){
                                $onclick = 'playVideo("'.urlencode(wp_get_attachment_url($video_id)).'","'.$video_type.'",jQuery(this).parents(".hovermore"),jQuery(this).parent().width(),jQuery(this).parent().width()/1.37)';
                            }else{
                                $onclick = 'playVideo("'.$video_id.'","'.$video_type.'",jQuery(this).parents(".hovermore"),jQuery(this).parent().width(),jQuery(this).parent().width()/1.37)';
                            }    
                        }   
                    }
                }
            }
    ?>
        <div class="<?php echo $container_class; ?> " id="post-<?php echo $post->ID; ?>" style="display: <?php echo $display; ?>; ">
            <header class="<?php echo $header_class; ?>">
                <div class="hovermore">
                    <div class="mosaic-overlay">
                        <?php
                        if (get_post_format($post->ID) == 'video') {
                            if(isset($onclick)){
                                $click = "onclick=".$onclick;
                            }else{
                                $click = '';
                            }
                                
                            echo '<a href="javascript:void(0);" '.$click.' class="video">&nbsp;</a>';
                        }else{
                            echo '<a href="'.get_permalink($post->ID).'" class="link">&nbsp;</a>';
                        }
                        ?>
                        <div class="user">
                            <a href="<?php echo get_author_posts_url( $post -> post_author ); ?>">
                                <?php echo cosmo_avatar( $post -> post_author , 32 , $default = DEFAULT_AVATAR_LOGIN ); ?>
                                <p><?php echo get_the_author_meta( 'display_name',$post -> post_author);   ?></p>
                            </a>
                        </div>
                        <div class="date">
                            <?php echo post::get_post_date($post -> ID); ?>
                        </div>    
                        <div class="new_meta">
                            <ul>
                                <?php 
                                    if ( function_exists( 'stats_get_csv' ) ){  
                                    $views = stats_get_csv( 'postviews' , "&post_id=" . $post -> ID);    
                                ?>
                                <li class="views">
                                    <a href="javascript:void(0)">    
                                        <strong><?php echo (int)$views[0]['views']; ?></strong>
                                        <span>
                                        <?php
                                            if( (int)$views[0]['views'] == 1) {
                                                _e( 'view' , 'cosmotheme');
                                            }else{
                                                _e( 'views' , 'cosmotheme' );
                                            } 
                                        ?>
                                        </span>
                                    </a>
                                </li>
                                <?php } ?>
                                <?php
                                if (comments_open($post->ID)) {
                                    $comments_label = __('replies','cosmotheme');
                                    if (options::logic('general', 'fb_comments')) {
                                        ?>
                                                <li class="replies" title="">
                                                    <a <?php echo tools::login_attr($post->ID, 'nsfw', get_comments_link($post->ID)) ?>>
                                                        <strong>
                                                            <fb:comments-count href="<?php echo get_permalink($post->ID) ?>"></fb:comments-count>
                                                        </strong>
                                                        <span><?php echo $comments_label; ?></span>
                                                    </a>
                                                </li>
                                        <?php
                                    } else {
                                        if(get_comments_number($post->ID) == 1){
                                            $comments_label = __('reply','cosmotheme');    
                                        }
                                        ?>
                                                <li class="left  padded" title="<?php echo get_comments_number($post->ID); ?> Comments">
                                                    <a <?php echo tools::login_attr($post->ID, 'nsfw', get_comments_link($post->ID)) ?>>
                                                        <strong>
                                                            <?php echo get_comments_number($post->ID) ?>
                                                        </strong>
                                                        <span><?php echo $comments_label; ?></span>
                                                    </a>
                                                </li>
                                        <?php
                                    }
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                    <div class="featimg"  >
                        <?php
                            if (has_post_thumbnail($post->ID)) {
                                $src = image::thumbnail($post->ID, $template, $size);
                                $caption = image::caption($post->ID);
                
                                if (strlen($classes)) {
                                    ?>
        
                                    <div class="format">&nbsp;</div>
                                    <img src="<?php echo get_template_directory_uri() ?>/images/nsfw.570x380.png" />
                                    <?php
                                    if (options::logic('styling', 'stripes')) {
                                        ?><div class="stripes" >&nbsp;</div><?php
                                    }
                                    ?>
                                    <?php
                                    if (get_post_format($post->ID) == 'video') {
                                        echo '<div class="play">&nbsp;</div>';
                                    }
                                    ?>
                                    <?php
                                } else {
                                    ?>
                                    
                                    <div class="format">&nbsp;</div>
                                    <img src="<?php echo $src[0]; ?>" alt="<?php echo $caption; ?>" >
                                    <?php
                                    if (options::logic('styling', 'stripes')) {
                                        ?><div class="stripes" >&nbsp;</div><?php
                                    }
                                    ?>
                                    <?php
                                    if (get_post_format($post->ID) == 'video') {
                                        echo '<div class="play">&nbsp;</div>';
                                    }
                                    ?>
                                    <?php
                                }
                            } else{
                                if (strlen($classes)) {
                                    ?>
                                    
                                    <div class="format">&nbsp;</div>
                                    <img src="<?php echo get_template_directory_uri() ?>/images/nsfw.570x380.png" />
                                    <?php
                                    if (options::logic('styling', 'stripes')) {
                                        ?><div class="stripes" style="height: <?php echo $s[1]; ?>px">&nbsp;</div><?php
                                    }
                                    ?>
                                    <?php
                                    if (get_post_format($post->ID) == 'video') {
                                        echo '<div class="play">&nbsp;</div>';
                                    }
                                    ?>
                                    <?php
                                } else {
                                    ?>
                                    
                                    <div class="format">&nbsp;</div>
                                    <img src="<?php echo get_template_directory_uri() ?>/images/noimage.570x380.png" alt="" />
                                    
                                    <?php
                                    if (options::logic('styling', 'stripes')) {
                                        ?><div class="stripes" >&nbsp;</div><?php
                                    }
                                    ?>
                                    <?php
                                    if (get_post_format($post->ID) == 'video') {
                                        echo '<div class="play">&nbsp;</div>';
                                    }
                                    ?>
                                    <?php
                                }
                            }
                        ?> 
                        <div class="format">&nbsp;</div>
                        <?php if (options::logic('styling', 'stripes')) {  ?>
                            <div class="stripes" >&nbsp;</div>
                        <?php }?>
                    </div>
                </div>
            </header>
            <div class="entry-content <?php echo $content_class; ?>">
                <h3>
                    <a <?php echo tools::login_attr($post->ID, 'nsfw', get_permalink($post->ID)) ?> title="<?php _e('Permalink to', 'cosmotheme'); ?> <?php echo $post->post_title; ?>" rel="bookmark"><?php echo $post->post_title; ?></a>
                    <?php if( options::logic( 'general' , 'enb_likes' ) ){ 
                       like::content($post->ID, 3, false, 'like', false, true);
                    } ?>
                </h3>
                <div class="excerpt"> 
                    <?php
                        post::get_excerpt($post, $ln = 300)
                    ?>
                </div>
            </div>
        </div>    
    <?php      
    }

    function list_view_thumbs_small($post,$template = 'blog_page' ) {
        /*returns markup for small boxes when list view thumbs is selected*/

        $classes = tools::login_attr($post->ID, 'nsfw');
        $size = 'thumbs_small';
    ?>
        <li>
            <div class="hovermore" style="">
                <a class="mosaic-overlay" href="#post-<?php echo $post->ID; ?>" style="display: inline; ">
                    <?php if (get_post_format($post->ID) == 'video') { ?>
                    <span class="video">&nbsp;</span>
                    <?php }else{ ?>
                    <span class="link">&nbsp;</span>
                    <?php } ?>
                </a>
                <div class="relative">
                    <div class="img" style="">
                        <?php
                            $src  = image::get_post_imag_src($post->ID, $size, $template = 'blog_page');
                        ?>
                        <img src="<?php echo $src; ?>">
                    </div>
                    <div class="format">&nbsp;</div>
                    <?php if (options::logic('styling', 'stripes')) { ?>
                    <div class="stripes">&nbsp;</div>
                    <?php } ?>
                </div>
            </div>
        </li>
    <?php    
    }

    function list_view($post, $template = 'blog_page', $additional_hidden_class_for_load_more = '' ) {
            $template_content_class = tools::primary_class( 0 , $template, $return_just_class = true );

            if($template_content_class == 'twelve columns'){
                $full_width = true;
                if(post::is_feat_enabled($post->ID)){
                    $header_class = 'six columns';
                    $content_class = 'six columns';
                }else{
                    $header_class = '';
                    $content_class = 'twelve columns';
                }
                
            }else{
                $full_width = false;
                if(post::is_feat_enabled($post->ID)){
                    $header_class = 'five columns';
                    $content_class = 'four columns';
                }else{
                    $header_class = '';
                    $content_class = 'nine columns';
                }
                
            }

            $classes = tools::login_attr($post->ID, 'nsfw');
            $attr = tools::login_attr($post->ID, 'nsfw mosaic-overlay ', get_permalink($post->ID), $additional_class     = 'details link');
            $size = 'tmedium';
            $s = image::asize( image::size( $post->ID , $template , $size ) );

            
            if( get_post_format( $post -> ID ) == 'video' ){
                $format = meta::get_meta( $post -> ID , 'format' );

                if( isset( $format['feat_id'] ) && !empty( $format['feat_id'] ) )
                  {
                    $video_id = $format['feat_id'];
                    $video_type = 'self_hosted';
                    if(isset($format['feat_url']) && post::isValidURL($format['feat_url']))
                      {
                        $vimeo_id = post::get_vimeo_video_id( $format['feat_url'] );
                        $youtube_id = post::get_youtube_video_id( $format['feat_url'] );
                        
                        if( $vimeo_id != '0' ){
                          $video_type = 'vimeo';
                          $video_id = $vimeo_id;
                        }

                        if( $youtube_id != '0' ){
                          $video_type = 'youtube';
                          $video_id = $youtube_id;
                        }
                      }

                    if(isset($video_type) && isset($video_id) && is_user_logged_in () ){
                        if($video_type == 'self_hosted'){
                            $onclick = 'playVideo("'.urlencode(wp_get_attachment_url($video_id)).'","'.$video_type.'",jQuery(this).parents(".hovermore"),jQuery(this).parent().width(),jQuery(this).parent().width()/1.37)';
                        }else{
                            $onclick = 'playVideo("'.$video_id.'","'.$video_type.'",jQuery(this).parents(".hovermore"),jQuery(this).parent().width(),jQuery(this).parent().width()/1.37)';
                        }    
                        
                    }else{
                        $meta = meta::get_meta( $post -> ID  , 'settings' );
                        if( isset( $meta['safe'] ) ){
                            if( !meta::logic( $post , 'settings' , 'safe' ) ){      
                                $onclick = 'playVideo("'.$video_id.'","'.$video_type.'",jQuery(this).parents(".hovermore"),jQuery(this).parent().width(),jQuery(this).parent().width()/1.37)';
                            }else{
                                $onclick = '';
                            }
                        }else{
                            if($video_type == 'self_hosted'){
                                $onclick = 'playVideo("'.urlencode(wp_get_attachment_url($video_id)).'","'.$video_type.'",jQuery(this).parents(".hovermore"),jQuery(this).parent().width(),jQuery(this).parent().width()/1.37)';
                            }else{
                                $onclick = 'playVideo("'.$video_id.'","'.$video_type.'",jQuery(this).parents(".hovermore"),jQuery(this).parent().width(),jQuery(this).parent().width()/1.37)';
                            }    
                        }   
                    }
                }
            }
        ?>
        <article class="post row list-tabs <?php echo $additional_hidden_class_for_load_more;?>" >
            <div class="row">
                <?php if(post::is_feat_enabled($post->ID)){ ?>
                <header class="<?php echo $header_class; ?>">
                    <div class="hovermore">
                        <div class="mosaic-overlay">
                            <?php
                            if (get_post_format($post->ID) == 'video') {
                                if(isset($onclick)){
                                    $click = "onclick=".$onclick;
                                }else{
                                    $click = '';
                                }
                                    
                                echo '<a href="javascript:void(0);" '.$click.' class="video">&nbsp;</a>';
                            }else{
                                echo '<a href="'.get_permalink($post->ID).'" class="link">&nbsp;</a>';
                            }
                            ?>
                            <div class="user">
                                <a href="<?php echo get_author_posts_url( $post -> post_author ); ?>">
                                    <?php echo cosmo_avatar( $post -> post_author , 32 , $default = DEFAULT_AVATAR_LOGIN ); ?>
                                    <p><?php echo get_the_author_meta( 'display_name',$post -> post_author);   ?></p>
                                </a>
                            </div>
                            <div class="date">
                                <?php echo post::get_post_date($post -> ID); ?>
                            </div>
                            <div class="new_meta">
                                <ul>
                                    <?php 
                                        if ( function_exists( 'stats_get_csv' ) ){  
                                        $views = stats_get_csv( 'postviews' , "&post_id=" . $post -> ID);    
                                    ?>
                                    <li class="views">
                                        <a href="javascript:void(0)">    
                                            <strong><?php echo (int)$views[0]['views']; ?></strong>
                                            <span>
                                            <?php
                                                if( (int)$views[0]['views'] == 1) {
                                                    _e( 'view' , 'cosmotheme');
                                                }else{
                                                    _e( 'views' , 'cosmotheme' );
                                                } 
                                            ?>
                                            </span>
                                        </a>
                                    </li>
                                    <?php } ?>
                                    <?php
                                    if (comments_open($post->ID)) {
                                        $comments_label = __('replies','cosmotheme');
                                        if (options::logic('general', 'fb_comments')) {
                                            ?>
                                                    <li class="replies" title="">
                                                        <a <?php echo tools::login_attr($post->ID, 'nsfw', get_comments_link($post->ID)) ?>>
                                                            <strong>
                                                                <fb:comments-count href="<?php echo get_permalink($post->ID) ?>"></fb:comments-count>
                                                            </strong>
                                                            <span><?php echo $comments_label; ?></span>
                                                        </a>
                                                    </li>
                                            <?php
                                        } else {
                                            if(get_comments_number($post->ID) == 1){
                                                $comments_label = __('reply','cosmotheme');    
                                            }
                                            ?>
                                                    <li class="left  padded" title="<?php echo get_comments_number($post->ID); ?> Comments">
                                                        <a <?php echo tools::login_attr($post->ID, 'nsfw', get_comments_link($post->ID)) ?>>
                                                            <strong>
                                                                <?php echo get_comments_number($post->ID) ?>
                                                            </strong>
                                                            <span><?php echo $comments_label; ?></span>
                                                        </a>
                                                    </li>
                                            <?php
                                        }
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                        <div class="featimg"  >
                            <?php
                                if (has_post_thumbnail($post->ID)) {
                                    $src = image::thumbnail($post->ID, $template, $size);
                                    $caption = image::caption($post->ID);
                                    

                                    if (strlen($classes)) {
                                        ?>

                                        
                                        <div class="format">&nbsp;</div>
                                        <img src="<?php echo get_template_directory_uri() ?>/images/nsfw.570x380.png" />
                                        <?php
                                        if (options::logic('styling', 'stripes')) {
                                            ?><div class="stripes" >&nbsp;</div><?php
                                        }
                                        ?>
                                        <?php
                                        if (get_post_format($post->ID) == 'video') {
                                            echo '<div class="play">&nbsp;</div>';
                                        }
                                        ?>
                                        <?php
                                    } else {
                                        ?>
                                        
                                        <div class="format">&nbsp;</div>
                                        <img src="<?php echo $src[0]; ?>" alt="<?php echo $caption; ?>" >
                                        <?php
                                        if (options::logic('styling', 'stripes')) {
                                            ?><div class="stripes" >&nbsp;</div><?php
                                        }
                                        ?>
                                        <?php
                                        if (get_post_format($post->ID) == 'video') {
                                            echo '<div class="play">&nbsp;</div>';
                                        }
                                        ?>
                                        <?php
                                    }
                                } else{
                                    if (strlen($classes)) {
                                        ?>
                                        
                                        <div class="format">&nbsp;</div>
                                        <img src="<?php echo get_template_directory_uri() ?>/images/nsfw.570x380.png" />
                                        <?php
                                        if (options::logic('styling', 'stripes')) {
                                            ?><div class="stripes" style="height: <?php echo $s[1]; ?>px">&nbsp;</div><?php
                                        }
                                        ?>
                                        <?php
                                        if (get_post_format($post->ID) == 'video') {
                                            echo '<div class="play">&nbsp;</div>';
                                        }
                                        ?>
                                        <?php
                                    } else {
                                        ?>
                                        
                                        <div class="format">&nbsp;</div>
                                        <img src="<?php echo get_template_directory_uri() ?>/images/noimage.570x380.png" alt="" />
                                        <?php
                                        if (options::logic('styling', 'stripes')) {
                                            ?><div class="stripes" >&nbsp;</div><?php
                                        }
                                        ?>
                                        <?php
                                        if (get_post_format($post->ID) == 'video') {
                                            echo '<div class="play">&nbsp;</div>';
                                        }
                                        ?>
                                        <?php
                                    }
                                }
                            ?> 
                            <div class="format">&nbsp;</div>
                            <?php if (options::logic('styling', 'stripes')) {  ?>
                                <div class="stripes" >&nbsp;</div>
                            <?php }?>
                        </div>
                    </div>
                </header>
                <?php } ?>
                <div class="entry-content <?php echo $content_class; ?>">
                    <h3>
                        <a <?php echo tools::login_attr($post->ID, 'nsfw', get_permalink($post->ID)) ?> title="<?php _e('Permalink to', 'cosmotheme'); ?> <?php echo $post->post_title; ?>" rel="bookmark"><?php echo $post->post_title; ?></a>
                        <?php if( options::logic( 'general' , 'enb_likes' ) ){ 
                           like::content($post->ID, 3, false, 'like', false, true);
                        } ?>
                    </h3>
                    <div class="excerpt"> 
                        <?php
                            post::get_excerpt($post, $ln = 400) 
                        ?>
                    </div>
                </div>
            </div>
        </article>          
        
        <?php
    }

    function grid_view_thumbnails($post,  $width = 'three columns', $additiona_class = '', $filter_type = '', $taxonomy = 'portfolio') {
        $nofeat_article_class = '';
        if(!post::is_feat_enabled($post->ID)){
            $nofeat_article_class = 'nofeat';    
        }

        $post_id = $post->ID;
    ?>
        <li data-id="id-<?php echo $post->ID; ?>" class=" <?php echo get_distinct_post_terms($post->ID, $taxonomy, $return_names = true, $filter_type = $filter_type ); echo $width.' '.$additiona_class; ?> ">
                                                                            
            <div class="hovermore">
                <div class="item-overlay">
                    <h3 class="item-caption">
                        <a <?php echo tools::login_attr($post->ID, 'nsfw readmore', get_permalink($post->ID)) ?> title="<?php _e('Permalink to', 'cosmotheme'); ?> <?php echo $post->post_title; ?>" rel="bookmark">
                            <?php echo $post->post_title; ?>
                        </a>
                    </h3>

                    <?php if( options::logic( 'general' , 'enb_likes' ) ){ 
                        echo '<span>'; 
                            like::content($post->ID, 3, false, 'like', false, true);
                        echo '</span>'; 
                    } ?>

                    
                </div>
                <div class="relative">
                    <?php

                    if (has_post_thumbnail($post->ID)) {

                        $classes = tools::login_attr($post->ID, 'nsfw');
                        $size = 'thumbs_small';
                        $img_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ) , $size );
                    
                        if (strlen($classes)) {
                    ?>
                        <img src="<?php echo get_template_directory_uri() ?>/images/nsfw.570x380.png" />
                        <?php   }else{  ?>    
                        <img src="<?php echo $img_src[0]; ?>" alt="" />
                        <?php   }  ?>        
                    <?php }else{ ?>
                        <img src="<?php echo get_template_directory_uri() ?>/images/noimage.570x380.png" alt="" />
                    <?php } ?>
                    <?php 
                    if (options::logic('styling', 'stripes')) {
                        ?><div class="stripes">&nbsp;</div><?php
                    }
                    ?>
                    
                </div>
            </div>
        </li>
    <?php    
    }

    function grid_view($post,  $width = 'three columns', $additiona_class = '') {
            $nofeat_article_class = '';
            if(!post::is_feat_enabled($post->ID)){
                $nofeat_article_class = 'nofeat';    
            }
            $post_id = $post->ID;
        ?>
        
        <li data-id="id-<?php echo $post->ID; ?>" class="app <?php echo $width.' '.$additiona_class; ?>">
            <?php if(post::is_feat_enabled($post->ID)){ ?>
            <div class="hovermore">
                <a class="mosaic-overlay" href="<?php echo get_permalink($post->ID); ?>" style="display: inline; opacity: 0; ">
                    <?php 
                        if(get_post_format( $post->ID ) == 'video'){ 
                            $format_class = 'video';
                        }else{
                            $format_class = 'link';                      
                        }
                    ?>
                    <span class="<?php echo $format_class; ?>">&nbsp;</span>
                    
                </a>
                <div class="relative">
                    <div class="img">
                        <?php

                        if (has_post_thumbnail($post->ID)) {

                            $classes = tools::login_attr($post->ID, 'nsfw');
                            $size = 'tgrid';
                            $img_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ) , $size );
                        
                            if (strlen($classes)) {
                        ?>
                            <img src="<?php echo get_template_directory_uri() ?>/images/nsfw.570x380.png" />
                            <?php   }else{  ?>    
                            <img src="<?php echo $img_src[0]; ?>" alt="" />
                            <?php   }  ?>        
                        <?php }else{ ?>
                            <img src="<?php echo get_template_directory_uri() ?>/images/noimage.570x380.png" alt="" />
                        <?php } ?>
                        <div class="format">&nbsp;</div>
                        <?php 
                        if (options::logic('styling', 'stripes')) {
                            ?><div class="stripes">&nbsp;</div><?php
                        }
                        ?>
                    </div>
                    
                </div>
            </div>
            <?php } ?>
            <h3 class="<?php echo $nofeat_article_class; ?>">
                <a href="<?php echo get_permalink($post->ID); ?>">
                    <?php echo $post->post_title; ?>
                </a>
                <?php if( options::logic( 'general' , 'enb_likes' ) ){ 
                        
                   like::content($post->ID, 3, false, 'like', false, true);
                    
                } ?>
            </h3>
            <div class="excerpt">
                <?php
                    post::get_excerpt($post, $ln = 300)
                ?>    
            </div>
        </li>

        <?php
    }

    function loop($template, $side = '', $paginate = true) {

        
        global $wp_query;        
        //echo '<input type="hidden" id="query-' . $template . $side . '" value="' . urlencode( json_encode( $wp_query -> query ) ) . '" />';
        if ( count( $wp_query->posts) > 0 ) { 
            if( tools::is_grid( $template , $side ) ){
?>
            <div class="row grid_view horizontal-posts  ">
                <?php 
                    self::loop_switch( $template , 'grid_view' ); 

                    if ($paginate) {
                        get_template_part('pagination');
                    }
                ?>
            </div>
<?php
            }else if( tools::is_thumbnails_view( $template , $side ) ){
?>                
                <div class="row grid_view horizontal-posts  ">
                    <?php 
                        self::loop_switch( $template , 'thumbnails_view' ); 

                        if ($paginate) {
                            get_template_part('pagination');
                        }
                    ?>
                </div>
<?php                
            }else{
?>              
            <div class="row blog-list-view vertical-posts ">
                <?php 
                    self::loop_switch( $template , 'list_view' ); 

                    if ($paginate) {
                        get_template_part('pagination');
                    }    
                ?>    

            </div>
                
<?php
            }
            
        } else {
            get_template_part('loop', '404');
        }
    }

    function loop_switch( $template = '' , $grid = 'grid_view' ) {
        global $wp_query;
        if ( !empty( $template ) ) {
            $ajax = false;
        } else {
            $template = isset( $_POST['template'] ) && strlen( $_POST['template'] ) ? $_POST['template'] : exit();
            $query = isset( $_POST['query'] ) && !empty( $_POST['query'] ) ? (array)json_decode( urldecode( $_POST['query'] ) ) : exit();
            $query['post_status'] = 'publish';
            $wp_query = new WP_Query( $query );
            $grid = isset($_POST['grid']) ? (int)$_POST['grid'] : 1;
            $ajax = true;
        }
        
        $template   = str_replace( array( '_hot' , '_new' , '_like' ) , '' , $template );
        
        if( $grid == 'grid_view' || $grid == 'thumbnails_view' ){
            if (layout::length(0, $template) == layout::$size['large']) {
                $div = tools::get_grid_nr_columns($template, $with_sidebar = false);
                $div_class = 'twelve';
                $sidebar_class = 'nosidebar';
            } else {
                $div = tools::get_grid_nr_columns($template, $with_sidebar = true);
                $div_class = 'nine';
                $sidebar_class = 'wsidebar';
            }
        }


        if( $grid == 'grid_view' ){
            
            echo '<div class="row grid-view '.$sidebar_class.'">';
                echo '<ul id="grid-list" class="image-grid columns-'. $div .' ">';
                    $counter = 1;
                    
                    foreach( $wp_query->posts as $post ){
                        $additional_class = '';
                        if( ($counter % $div) == 1 ){
                            $additional_class = 'first-elem';
                        }
                        self::grid_view($post, tools::columns_arabic_to_word( $div, $container_width = $div_class ).' columns',$additional_class);
                        
                        if( ($counter % $div) == 0){
                            echo '<li class="clear"></li>';
                        }

                        $counter ++;
                    }
                echo '</ul>';
            echo '</div>';
        }else if( $grid == 'thumbnails_view'){

            echo '<div class="row grid-view '.$sidebar_class.'">';
                echo '<ul id="grid-list" class="image-grid columns-'. $div .' ">';
                    $counter = 1;
                    
                    foreach( $wp_query->posts as $post ){
                        $additional_class = '';
                        if( ($counter % $div) == 1 ){
                            $additional_class = 'first-elem';
                        }
                        self::grid_view_thumbnails($post,  $width = tools::columns_arabic_to_word( $div ).' columns', $additiona_class = $additional_class, $filter_type = '');
                        
                        if( ($counter % $div) == 0){
                            echo '<li class="clear"></li>';
                        }

                        $counter ++;
                    }
                echo '</ul>';
            echo '</div>';
        }else{
            foreach ($wp_query->posts as $index => $post) {
                $wp_query->the_post();
               
                self::list_view_archive($post, $template);

            }
        }
        if( $ajax ){
            
            exit();
        }
    }
    
    function shmeta($post, $nav = true) {
        global $wp_query;
        ?>
        <div class="entry-meta">
            <ul>
                <?php if(options::logic( 'upload' , 'enb_edit_delete' ) && is_user_logged_in() && $post->post_author == get_current_user_id() && is_numeric(options::get_value( 'upload' , 'post_item_page' ))){ ?> 
                <li class="edit_post" title="<?php _e('Edit post','cosmotheme') ?>"><a href="<?php  echo add_query_arg( 'post', $post->ID, get_page_link(options::get_value( 'upload' , 'post_item_page' ))  ) ;  ?>"  ><?php echo _e('Edit','cosmotheme'); ?></a></li>    
                <?php }   ?>
                
                <li class="author" title="Author">
                    <a href="<?php echo get_author_posts_url($post->post_author) ?>">
        <?php echo get_the_author_meta('display_name', $post->post_author); ?>
                    </a>
                </li>
        <?php
        if (comments_open($post->ID)) {
            if (options::logic('general', 'fb_comments')) {
                ?>
                        <li class="cosmo-comments" title="">
                            <a <?php echo tools::login_attr($post->ID, 'nsfw', get_comments_link($post->ID)) ?>>
                                <fb:comments-count href="<?php echo get_permalink($post->ID) ?>"></fb:comments-count>
                            </a>
                        </li>
                <?php
            } else {
                ?>
                        <li class="cosmo-comments" title="<?php echo get_comments_number($post->ID); ?> Comments">
                            <a <?php echo tools::login_attr($post->ID, 'nsfw', get_comments_link($post->ID)) ?>>
                <?php echo get_comments_number($post->ID) ?>
                            </a>
                        </li>
                <?php
            }
        }
        ?>

                <li class="cosmo-love"><?php like::content($post->ID, 3); ?></li>
            </ul>
        </div>
        <?php
    }

	function show_meta_author_box($post){
		$meta = meta::get_meta( $post -> ID , 'settings' );

		  
		if( isset( $meta[ 'author' ] ) && strlen( $meta[ 'author' ] ) && !is_author() ){
			$show_author = meta::logic( $post , 'settings' , 'author' );
		}else{
			if( is_single() ){
				$show_author = options::logic( 'blog_post' , 'post_author_box' );
			}

			if( is_page() ){
				$show_author = options::logic( 'blog_post' , 'page_author_box' );
			}

			if( !( is_single() || is_page() ) ){
				$show_author = true;
			}
		}

		return $show_author;
	}
  
    function meta( $post ) {
        global $wp_query;
		
       
		?>
        <div class="  entry-meta">

            <?php if(self::show_meta_author_box($post) ) { 
				$role = array( 
                                    10 => __( 'Administrator' , 'cosmotheme' ) ,
                                    7 => __( 'Editor' , 'cosmotheme' ) , 
                                    2 => __( 'Author' , 'cosmotheme' ) , 
                                    1 => __( 'Contributor' , 'cosmotheme'  ) , 
                                    0 => __( 'Subscriber' , 'cosmotheme' ), 
                                    '' => __( 'Subscriber' , 'cosmotheme' )
                                );
			?>  
			<div class="entry-author">
				<a href="<?php echo get_author_posts_url($post->post_author) ?>" class="profile-pic" ><?php echo cosmo_avatar( $post->post_author , 32 , $default = DEFAULT_AVATAR_LOGIN ); ?></a>
				<a href="<?php echo get_author_posts_url($post->post_author) ?>">
					<?php echo get_the_author_meta('display_name', $post->post_author); ?> <br/>
					<img src="<?php echo get_template_directory_uri(); ?>/images/mask.png" class="mask" alt="">
					<span><?php echo $role[ get_the_author_meta( 'user_level' , $post->post_author ) ]; ?></span>
				</a>
			</div>
			<?php } ?>
            <?php
                like::content($post->ID,2)
            ?>	
            <ul>
                <?php
                    if (comments_open($post->ID)) {
                        $comments_label = __('comments','cosmotheme');  
                        if (options::logic('general', 'fb_comments')) {
                ?>
                            <li class="cosmo-comments" title="">
                                <a href="<?php echo get_comments_link($post->ID); ?>"> 
                                    <?php  echo $comments_label;  ?>
                                    <span>
                                        <fb:comments-count href="<?php echo get_permalink($post->ID) ?>"></fb:comments-count>  
                                    </span>
                                </a>
                            </li>
                <?php
                        } else {
                            
                            if(get_comments_number($post->ID) == 1){
                                $comments_label = __('comment','cosmotheme');
                            }
                ?>
                            <li class="cosmo-comments" title="<?php echo get_comments_number($post->ID); echo ' '.$comments_label; ?>">
                                <a href="<?php echo get_comments_link($post->ID) ?>"> 
                                    <?php echo $comments_label; ?> 
                                    <span>
                                        <?php echo get_comments_number($post->ID) ?> 
                                    </span>    
                                </a>
                            </li>
                <?php
                        }
                    }
                ?> 
                
                <?php 
                    if ( function_exists( 'stats_get_csv' ) ){  
                    $views = stats_get_csv( 'postviews' , "&post_id=" . $post -> ID);    
                ?>
                <li class="views">
                    <a href="javascript:void(0)">
                    <?php
                        if( (int)$views[0]['views'] == 1) {
                            _e( 'View' , 'cosmotheme');
                        }else{
                            _e( 'Views' , 'cosmotheme' );
                        } 
                        
                    ?>
                    <span ><?php echo (int)$views[0]['views']; ?></span>
                    </a>
                </li>
                <?php } ?>   
            </ul>
            <ul>
                <?php if(options::logic( 'upload' , 'enb_edit_delete' ) && is_user_logged_in() && $post->post_author == get_current_user_id() && is_numeric(options::get_value( 'upload' , 'post_item_page' ))){ ?> 
                    <li class="edit_post" title="<?php _e('Edit post','cosmotheme') ?>">
                        <a href="<?php  echo add_query_arg( 'post', $post->ID, get_page_link(options::get_value( 'upload' , 'post_item_page' ))  ) ;  ?>"  >
                            <?php echo _e('Edit','cosmotheme'); ?>
                        </a>
                    </li>    
                <?php }   ?>
                <?php if( options::logic( 'upload' , 'enb_edit_delete' )  && is_user_logged_in() && $post->post_author == get_current_user_id() ){  
                    $confirm_delete = __('Confirm to delete this post.','cosmotheme');
                ?>
                    <li class="delete_post" title="<?php _e('Remove post','cosmotheme') ?>">
                        <a href="javascript:void(0)" onclick="if(confirm('<?php echo $confirm_delete; ?> ')){ removePost('<?php echo $post->ID; ?>','<?php echo home_url() ?>');}" >
                            <?php echo _e('Delete','cosmotheme'); ?>
                        </a>
                    </li>
                <?php  } ?>
                
            </ul>

        </div>
        <?php
        }

        function add_image_post(){
        	$response = array(  'image_error' => '',
        						'error_msg' => '',	
        						'title_error' => '',
        						'post_id' => 0,
        						'auth_error' => '',
        						'success_msg' => ''	);
        	
        	
        	$is_valid = true;
        	
        	if(!is_user_logged_in()){
        		$is_valid = false;	
        		$response['error_msg'] = 'error';
        		$response['auth_error'] = __('You must be logged in to submit a post! ','cosmotheme');	
        	}
        	if(is_user_logged_in() && isset($_POST['post_id'])){
				$post_edit = get_post($_POST['post_id']);
				
				if(get_current_user_id() != $post_edit->post_author){
					$is_valid = false;	
					$response['error_msg'] = __('You are not the author of this post. ','cosmotheme');
					$response['title_error'] = __('You are not the author of this post. ','cosmotheme');
				}
			}
        	if(!isset($_POST['title']) || trim($_POST['title']) == ''){
        		$is_valid = false;	
        		$response['error_msg'] = 'Title is required. ';
        		$response['title_error'] = __('Title is required. ','cosmotheme');
        	}
        	if(!isset($_POST['attachments']) || !is_array($_POST['attachments']) || !isset($_POST['featured']) || !is_numeric($_POST['featured']))
			  {
        		$is_valid = false;	
        		$response['error_msg'] = 'error';
        		$response['image_error'] = __('An image post must have a featured image. ','cosmotheme');
			  }
        	
        	
        	if($is_valid){
        		/*create post*/
        		$post_categories = array(1);
        		if(isset($_POST['category_id'])){
        			$post_categories = array($_POST['category_id']);
        		}
        			
                $portfolios = array();
                if(isset($_POST['portfolio'])){
                    $portfolios = array($_POST['portfolio']);
                }

        		$post_content = '';
        		if(isset($_POST['image_content'])){
        			$post_content = $_POST['image_content'];
        		}
        			
        		if(isset($_POST['post_id'])){
					$new_post = self::create_new_post($_POST['title'], $_POST['tags'], $post_categories,$portfolios, $post_content, $_POST['post_id']);  /*add image as content*/
				}else{
					$new_post = self::create_new_post($_POST['title'],$_POST['tags'],$post_categories,$portfolios, $post_content);  /*add image as content*/
				}
        			
				    
			    if(is_numeric($new_post))
				  {
		       		$attachments = get_children( array('post_parent' => $new_post, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID') );
					foreach ($attachments as $index => $id) {
					  $attachment = $index;
					} 
					foreach($_POST['attachments'] as $index=>$imageid)
					  {
						if($imageid==$_POST['featured'])
						  {
							  set_post_thumbnail($new_post, $imageid);
							  unset($_POST['attachments'][$index]);
						  }
						$attachment_post=get_post($imageid);
						$attachment_post->post_parent=$new_post;
						wp_update_post($attachment_post);
					  }
					
					if(isset($_POST['nsfw'])){
						$settings_meta = array(	  "safe"=>  "yes");
						meta::set_meta( $new_post , 'settings' , $settings_meta );
					}else{
						$settings_meta = array(	  "safe"=>  "yes");
						delete_post_meta($new_post, 'settings', $settings_meta );
					}	
						
					/*add source meta data*/
					if(isset($_POST['source']) && trim($_POST['source']) != ''){
					  $settings_meta = array(	  "post_source"=>  $_POST['source']);
					  meta::set_meta( $new_post , 'source' , $settings_meta );	
					}else{
						$settings_meta = array(	  "post_source"=>  $_POST['source']);
						delete_post_meta($new_post, 'source', $settings_meta );
					}	
							
					/*add video url meta data*/
					$image_format_meta = array("type" => 'image', 'images'=>$_POST['attachments']);
					meta::set_meta( $new_post , 'format' , $image_format_meta );

					if(isset($_POST['post_format']) && ($_POST['post_format'] == 'video' || $_POST['post_format'] == 'image' || $_POST['post_format'] == 'audio') ){
						set_post_format( $new_post , $_POST['post_format']);
					}
						
					if(options::get_value( 'upload' , 'default_posts_status' ) == 'publish'){
						/*if post was publihed imediatelly then we will show the prmalink to the user*/
							
						$response['success_msg'] = sprintf(__('You can check your post %s here%s.','cosmotheme'),'<a href="'.get_permalink($new_post).'">','</a>');
							
					}else{
							$response['success_msg'] = __('Success. Your post is awaiting moderation.','cosmotheme');
					}	
						$response['post_id'] = $new_post;
				   }	        		
        		}	
        	echo json_encode($response);
        	exit;
        }

		function add_file_post(){

			$response = array(  'image_error' => '',
								'file_error' => '',
        						'error_msg' => '',	
        						'title_error' => '',
        						'post_id' => 0,
        						'auth_error' => '',
        						'success_msg' => ''	);
        	
        	
        	$is_valid = true;
        	
        	if(!is_user_logged_in()){
        		$is_valid = false;	
        		$response['error_msg'] = 'error';
        		$response['auth_error'] = __('You must be logged in to submit a post! ','cosmotheme');	
        	}
            
            if(is_user_logged_in() && isset($_POST['post_id'])){
				$post_edit = get_post($_POST['post_id']);
				
				if(get_current_user_id() != $post_edit->post_author){
					$is_valid = false;	
					$response['error_msg'] = __('You are not the author of this post. ','cosmotheme');
					$response['title_error'] = __('You are not the author of this post. ','cosmotheme');
				}
			}
            
        	if(!isset($_POST['title']) || trim($_POST['title']) == ''){
        		$is_valid = false;	
        		$response['error_msg'] = 'Title is required. ';
        		$response['title_error'] = __('Title is required. ','cosmotheme');
        	}

			if(!isset($_POST['attachments'])){
        		$is_valid = false;	
        		$response['error_msg'] = 'File is required. ';
        		$response['file_error'] = __('File is required. ','cosmotheme');
        	}
        	
        		if($is_valid){
        			/*create post*/
        			$post_categories = array(1);
        			if(isset($_POST['category_id'])){
        				$post_categories = array($_POST['category_id']);
        			}
        			
                    $portfolios = array();
                    if(isset($_POST['portfolio'])){
                        $portfolios = array($_POST['portfolio']);
                    }

        			$post_content = '';
        			if(isset($_POST['file_content'])){
        				$post_content = $_POST['file_content'];
        			}
        			
        			
                    if(isset($_POST['post_id'])){
						$new_post = self::create_new_post($_POST['title'], $_POST['tags'], $post_categories, $portfolios, $post_content, $_POST['post_id']);  
					}else{
						$new_post = self::create_new_post($_POST['title'],$_POST['tags'],$post_categories, $portfolios, $post_content);  
					}
                    
				    if(is_numeric($new_post))
					  {
						set_post_thumbnail($new_post, null);
						foreach($_POST['attachments'] as $index=>$attachid)
						  {
							if($attachid==$_POST['featured'])
							  {
								set_post_thumbnail($new_post, $attachid);
								unset($_POST['attachments'][$index]);
							  }
							$attachment_post=get_post($attachid);
							$attachment_post->post_parent=$new_post;
							wp_update_post($attachment_post);
						  }
						$file_url_meta = array(	  "link"=>  '', "type" => 'link', 'link_id' => $_POST['attachments']);
						meta::set_meta( $new_post , 'format' , $file_url_meta );
						
						if(isset($_POST['nsfw'])){
							$settings_meta = array(	  "safe"=>  "yes");
							meta::set_meta( $new_post , 'settings' , $settings_meta );
						}else{
							$settings_meta = array(	  "safe"=>  "yes");
							delete_post_meta($new_post, 'settings', $settings_meta );
						}	
						
						/*add source meta data*/
						if(isset($_POST['source']) && trim($_POST['source']) != ''){
						  $settings_meta = array(	  "post_source"=>  $_POST['source']);
						  meta::set_meta( $new_post , 'source' , $settings_meta );	
						}else{
							$settings_meta = array(	  "post_source"=>  $_POST['source']);
							delete_post_meta($new_post, 'source', $settings_meta );
						}	
													
						/*add file url meta data*/
						

						if(isset($_POST['post_format']) && ($_POST['post_format'] == 'video' || $_POST['post_format'] == 'image' || $_POST['post_format'] == 'audio' || $_POST['post_format'] == 'link') ){
							set_post_format( $new_post , $_POST['post_format']);
						}
						
						if(options::get_value( 'upload' , 'default_posts_status' ) == 'publish'){
							/*if post was publihed imediatelly then we will show the prmalink to the user*/
								
							$response['success_msg'] = sprintf(__('You can check your post %s here%s.','cosmotheme'),'<a href="'.get_permalink($new_post).'">','</a>');
							
						}else{
							$response['success_msg'] = __('Success. Your post is awaiting moderation.','cosmotheme');
						}	
						$response['post_id'] = $new_post;
				    }	
				    
	        		
        		}	
        	echo json_encode($response);
        	exit;
		}

		function add_audio_post(){
			$response = array(  'image_error' => '',
								'audio_error' => '',
        						'error_msg' => '',	
        						'title_error' => '',
        						'post_id' => 0,
        						'auth_error' => '',
        						'success_msg' => ''	);
        	
        	
        	$is_valid = true;
        	
        	if(!is_user_logged_in()){
        		$is_valid = false;	
        		$response['error_msg'] = 'error';
        		$response['auth_error'] = __('You must be logged in to submit a post! ','cosmotheme');	
        	}
        	
            if(is_user_logged_in() && isset($_POST['post_id'])){
				$post_edit = get_post($_POST['post_id']);
				
				if(get_current_user_id() != $post_edit->post_author){
					$is_valid = false;	
					$response['error_msg'] = __('You are not the author of this post. ','cosmotheme');
					$response['title_error'] = __('You are not the author of this post. ','cosmotheme');
				}
			}
            
        	if(!isset($_POST['title']) || trim($_POST['title']) == ''){
        		$is_valid = false;	
        		$response['error_msg'] = 'Title is required. ';
        		$response['title_error'] = __('Title is required. ','cosmotheme');
        	}

			if(!isset($_POST['attachments'])){
        		$is_valid = false;	
        		$response['error_msg'] = 'Audio File is required. ';
        		$response['audio_error'] = __('Audio File is required. ','cosmotheme');
        	}
   	        	
        		if($is_valid){
        			/*create post*/
        			$post_categories = array(1);
        			if(isset($_POST['category_id'])){
        				$post_categories = array($_POST['category_id']);
        			}
        			
                    $portfolios = array();
                    if(isset($_POST['portfolio'])){
                        $portfolios = array($_POST['portfolio']);
                    }

        			$post_content = '';
        			if(isset($_POST['audio_content'])){
        				$post_content = $_POST['audio_content'];
        			}

					if(isset($_POST['post_id'])){
						$new_post = self::create_new_post($_POST['title'], $_POST['tags'], $post_categories, $portfolios, $post_content, $_POST['post_id']);  
					}else{
						$new_post = self::create_new_post($_POST['title'],$_POST['tags'],$post_categories, $portfolios, $post_content);  
					}
                    
				    if(is_numeric($new_post))
					  {
						set_post_thumbnail($new_post, null);
						foreach($_POST['attachments'] as $index=>$attachid)
						  {
							if($attachid==$_POST['featured'])
							  {
								set_post_thumbnail($new_post, $attachid);
								unset($_POST['attachments'][$index]);
							  }
							$attachment_post=get_post($attachid);
							$attachment_post->post_parent=$new_post;
							wp_update_post($attachment_post);
						  }
						$audio_url_meta = array(	  "audio"=>  $_POST['attachments'], "type" => 'audio');
						meta::set_meta( $new_post , 'format' , $audio_url_meta );

						if(isset($_POST['nsfw'])){
							$settings_meta = array(	  "safe"=>  "yes");
							meta::set_meta( $new_post , 'settings' , $settings_meta );
						}else{
							$settings_meta = array(	  "safe"=>  "yes");
							delete_post_meta($new_post, 'settings', $settings_meta );
						}	
						
						/*add source meta data*/
						if(isset($_POST['source']) && trim($_POST['source']) != ''){
						  $settings_meta = array(	  "post_source"=>  $_POST['source']);
						  meta::set_meta( $new_post , 'source' , $settings_meta );	
						}else{
							$settings_meta = array(	  "post_source"=>  $_POST['source']);
							delete_post_meta($new_post, 'source', $settings_meta );
						}	
												
						if(isset($_POST['post_format']) && ($_POST['post_format'] == 'video' || $_POST['post_format'] == 'image' || $_POST['post_format'] == 'audio' || $_POST['post_format'] == 'link') ){
							set_post_format( $new_post , $_POST['post_format']);
						}
						
						if(options::get_value( 'upload' , 'default_posts_status' ) == 'publish'){
							/*if post was publihed imediatelly then we will show the prmalink to the user*/
								
							$response['success_msg'] = sprintf(__('You can check your post %s here%s.','cosmotheme'),'<a href="'.get_permalink($new_post).'">','</a>');
							
						}else{
							$response['success_msg'] = __('Success. Your post is awaiting moderation.','cosmotheme');
						}	
						$response['post_id'] = $new_post;
				    }	
				    
	        		
        		}	
        	echo json_encode($response);
        	exit;
		}
        
        function add_text_post(){
        	$response = array(  'error_msg' => '',	
        						'title_error' => '',
        						'post_id' => 0,
        						'auth_error' => '' );
        	
        	$is_valid = true;
        	
        	if(!is_user_logged_in()){
        		$is_valid = false;	
        		$response['error_msg'] = 'error';
        		$response['auth_error'] = __('You must be logged in to submit a post!','cosmotheme');	
        	}
        	
            if(is_user_logged_in() && isset($_POST['post_id'])){
				$post_edit = get_post($_POST['post_id']);
				
				if(get_current_user_id() != $post_edit->post_author){
					$is_valid = false;	
					$response['error_msg'] = __('You are not the author of this post. ','cosmotheme');
					$response['title_error'] = __('You are not the author of this post. ','cosmotheme');
				}
			}
            
        	if(!isset($_POST['title']) || trim($_POST['title']) == ''){
        		$is_valid = false;	
        		$response['error_msg'] = 'error';
        		$response['title_error'] = __('Title is required. ','cosmotheme');
        	}
        	
        		if($is_valid){

	        			/*create post*/
        				/*$post_content = self::get_embeded_video($video_id,$video_type);*/
	        			$post_categories = array(1);
	        			//$response['video_error'] = $_POST['category_id'];
	        			if(isset($_POST['category_id'])){
	        				$post_categories = array($_POST['category_id']);
	        			}
	        			
                        $portfolios = array();
                        if(isset($_POST['portfolio'])){
                            $portfolios = array($_POST['portfolio']);
                        }

	        			$post_content = '';
	        			if(isset($_POST['text_content'])){
	        				$post_content = $_POST['text_content'];
	        			}
	        			
                        if(isset($_POST['post_id'])){
                            $new_post = self::create_new_post($_POST['title'], $_POST['tags'], $post_categories, $portfolios, $post_content, $_POST['post_id']);  
                        }else{
                            $new_post = self::create_new_post($_POST['title'],$_POST['tags'],$post_categories, $portfolios, $post_content);  
                        }
                        
					    if(is_numeric($new_post)){	
						   
							
							if(isset($_POST['nsfw'])){
								$settings_meta = array(	  "safe"=>  "yes");
								meta::set_meta( $new_post , 'settings' , $settings_meta );
							}else{
								$settings_meta = array(	  "safe"=>  "yes");
								delete_post_meta($new_post, 'settings', $settings_meta );
							}
							
							/*add source meta data*/
						    if(isset($_POST['source']) && trim($_POST['source']) != ''){
							  $settings_meta = array(	  "post_source"=>  $_POST['source']);
							  meta::set_meta( $new_post , 'source' , $settings_meta );	
							}else{
								$settings_meta = array(	  "post_source"=>  $_POST['source']);
								delete_post_meta($new_post, 'source', $settings_meta );
							}	
						
							if(options::get_value( 'upload' , 'default_posts_status' ) == 'publish'){
								/*if post was publihed imediatelly then we will show the prmalink to the user*/
									
								$response['success_msg'] = sprintf(__('You can check your post %s here%s.','cosmotheme'),'<a href="'.get_permalink($new_post).'">','</a>');
								
							}else{
								$response['success_msg'] = __('Success. Your post is awaiting moderation','cosmotheme');
							}	
							$response['post_id'] = $new_post;
					    }
				
        		}
        			
        	echo json_encode($response);
        	exit;
        	
        }
        
        function add_video_post(){
        	$response = array(  'video_error' => '',
        						'error_msg' => '',	
        						'title_error' => '',
        						'post_id' => 0,
        						'auth_error' => '' );
        	
        	
        	$is_valid = true;
        	
        	if(!is_user_logged_in()){
        		$is_valid = false;	
        		$response['error_msg'] = 'error';
        		$response['auth_error'] = __('You must be logged in to submit a post!','cosmotheme');	
        	}
        	
            if(is_user_logged_in() && isset($_POST['post_id'])){
				$post_edit = get_post($_POST['post_id']);
				
				if(get_current_user_id() != $post_edit->post_author){
					$is_valid = false;	
					$response['error_msg'] = __('You are not the author of this post. ','cosmotheme');
					$response['title_error'] = __('You are not the author of this post. ','cosmotheme');
				}
			}
            
        	if(!isset($_POST['title']) || trim($_POST['title']) == ''){
        		$is_valid = false;	
        		$response['error_msg'] = 'error';
        		$response['title_error'] = __('Title is required. ','cosmotheme');
        	}
        	
			if(!isset($_POST['attachments']) || !is_array($_POST['attachments']) || !isset($_POST['featured']) || !is_numeric($_POST['featured']))
			{
				$is_valid = false;	
        		$response['error_msg'] = 'error';
        		$response['video_error'] = __('A video post must have a featured video.','cosmotheme');
			}
        	
        	if($is_valid)
			  {
	        	/*create post*/
        		/*$post_content = self::get_embeded_video($video_id,$video_type);*/
	        	$post_categories = array(1);
	        	//$response['video_error'] = $_POST['category_id'];
	        	
	        	if(isset($_POST['category_id'])){
	        		$post_categories = array($_POST['category_id']);
	        	}
	        		
                $portfolios = array();
                if(isset($_POST['portfolio'])){
                    $portfolios = array($_POST['portfolio']);
                }
                            	
	        	$post_content = '';
	        	if(isset($_POST['video_content'])){
	        		$post_content = $_POST['video_content'];
	        	}
	        			
        				
                if(isset($_POST['post_id'])){
                  $new_post = self::create_new_post($_POST['title'], $_POST['tags'], $post_categories,$portfolios, $post_content, $_POST['post_id']);  
                }else{
                  $new_post = self::create_new_post($_POST['title'],$_POST['tags'],$post_categories,$portfolios,$post_content);  
                }
                    
				if(is_numeric($new_post))
				  {	
					if(isset($_POST['nsfw'])){
						$settings_meta = array(	  "safe"=>  "yes");
						meta::set_meta( $new_post , 'settings' , $settings_meta );
					}else{
						$settings_meta = array(	  "safe"=>  "yes");
						delete_post_meta($new_post, 'settings', $settings_meta );
					}	
							
					/*add source meta data*/
					if(isset($_POST['source']) && trim($_POST['source']) != ''){
					  $settings_meta = array(	  "post_source"=>  $_POST['source']);
					  meta::set_meta( $new_post , 'source' , $settings_meta );	
					}else{
						$settings_meta = array(	  "post_source"=>  $_POST['source']);
						delete_post_meta($new_post, 'source', $settings_meta );
					}	

					$featured_video_url=false;

					foreach($_POST['attachments'] as $index=>$videoid)
					  {
						if($videoid==$_POST['featured'])
						  {
							$featured_video_id=$videoid;
							unset($_POST['attachments'][$index]);
							if(isset($_POST['video_urls'][$videoid]) && post::isValidURL($_POST['video_urls'][$videoid]))
							  {
								set_post_thumbnail($new_post,$videoid);
								$featured_video_url=$_POST['video_urls'][$videoid];
								unset($_POST['video_urls'][$videoid]);
							  }
							else set_post_thumbnail($new_post, null);
							}
						 $attachment_post=get_post($videoid);
						 $attachment_post->post_parent=$new_post;
						 wp_update_post($attachment_post);
					  }
				
				  $video_format_meta=array("type"=>"video", "video_ids"=>$_POST['attachments'], "feat_id"=>$featured_video_id, "feat_url"=>$featured_video_url);
				  if(isset($_POST['video_urls']))
					$video_format_meta["video_urls"]=$_POST["video_urls"];
				  meta::set_meta( $new_post , 'format' , $video_format_meta );

				  if(isset($_POST['post_format']) && ($_POST['post_format'] == 'video' || $_POST['post_format'] == 'image' || $_POST['post_format'] == 'audio') ){
					set_post_format( $new_post , $_POST['post_format']);
				  }
									
				  if(options::get_value( 'upload' , 'default_posts_status' ) == 'publish'){
					/*if post was publihed imediatelly then we will show the prmalink to the user*/
									
					$response['success_msg'] = sprintf(__('You can check your post %s here%s.','cosmotheme'),'<a href="'.get_permalink($new_post).'">','</a>');
								
				  }else{
					  $response['success_msg'] = __('Success. Your post is awaiting moderation','cosmotheme');
				  }	
					  $response['post_id'] = $new_post;
				}
        			
        	}
        	        			
        	echo json_encode($response);
        	exit;
        }
        
       
        function get_embeded_video($video_id,$video_type,$autoplay = 0,$width = 570,$height = 414){
        	
        	$embeded_video = '';
        	if($video_type == 'youtube'){
        		$embeded_video	= '<iframe width="'.$width.'" height="'.$height.'" src="http://www.youtube.com/embed/'.$video_id.'?wmode=transparent&autoplay='.$autoplay.'" wmode="opaque" frameborder="0" allowfullscreen></iframe>';
        	}elseif($video_type == 'vimeo'){
        		$embeded_video	= '<iframe src="http://player.vimeo.com/video/'.$video_id.'?title=0&amp;autoplay='.$autoplay.'&amp;byline=0&amp;portrait=0" width="'.$width.'" height="'.$height.'" frameborder="0" webkitAllowFullScreen allowFullScreen></iframe>';
        	}
        	
        	return $embeded_video;
        }
        
		function get_local_video($video_url, $width = 570, $height = 414, $autoplay = false ){
			
            $result = '';    
			
            if($autoplay){
                $auto_play = 'true';
            }else{
                $auto_play = 'false';
            }
            
			$result = do_shortcode('[video mp4="'.$video_url.'" width="'.$width.'" height="'.$height.'"  autoplay="'.$auto_play.'"]');
			
			return $result;	
		}
  
        function get_video_thumbnail($video_id,$video_type){
        	$thumbnail_url = '';
        	if($video_type == 'youtube'){
				$thumbnail_url = 'http://i1.ytimg.com/vi/'.$video_id.'/hqdefault.jpg';
        	}elseif($video_type == 'vimeo'){
        		
				$hash = wp_remote_get("http://vimeo.com/api/v2/video/$video_id.php");
				$hash = unserialize($hash['body']);
				
				$thumbnail_url = $hash[0]['thumbnail_large'];  
        	}
        	
        	return $thumbnail_url;
        }
        
    	function get_youtube_video_id($url){
	        /*
	         *   @param  string  $url    URL to be parsed, eg:  
	 		*  http://youtu.be/zc0s358b3Ys,  
	 		*  http://www.youtube.com/embed/zc0s358b3Ys
	 		*  http://www.youtube.com/watch?v=zc0s358b3Ys 
	 		*  
	 		*  returns
	 		*  */	
        	$id=0;
        	
        	/*if there is a slash at the en we will remove it*/
        	$url = rtrim($url, " /");
        	if(strpos($url, 'youtu')){
	        	$urls = parse_url($url); 
	     
			    /*expect url is http://youtu.be/abcd, where abcd is video iD*/
			    if(isset($urls['host']) && $urls['host'] == 'youtu.be'){  
			        $id = ltrim($urls['path'],'/'); 
			    } 
			    /*expect  url is http://www.youtube.com/embed/abcd*/ 
			    else if(strpos($urls['path'],'embed') == 1){  
			        $id = end(explode('/',$urls['path'])); 
			    } 
			     
			    /*expect url is http://www.youtube.com/watch?v=abcd */
			    else if( isset($urls['query']) ){ 
			        parse_str($urls['query']); 
			        $id = $v; 
			    }else{
					$id=0;
				} 
        	}	
			
			return $id;
        }
        
        function  get_vimeo_video_id($url){
        	/*if there is a slash at the en we will remove it*/
        	$url = rtrim($url, " /");
        	$id = 0;
        	if(strpos($url, 'vimeo')){
				$urls = parse_url($url); 
				if(isset($urls['host']) && $urls['host'] == 'vimeo.com'){  
					$id = ltrim($urls['path'],'/'); 
					if(!is_numeric($id) || $id < 0){
						$id = 0;
					}
				}else{
					$id = 0;
				} 
        	}	
			return $id;
		}
        

	    function isValidURL($url)
		{
			return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
		}

        function create_new_post($post_title,$post_tags, $post_categories, $portfolios = array(), $content = '', $post_id = 0 ){
        	$current_user = wp_get_current_user();

        	$post_status = options::get_value( 'upload' , 'default_posts_status' )	;
        	if($post_id == 0){
				$post_args = array(
		            'post_title' => $post_title,
		            'post_content' => $content ,
		            'post_status' => $post_status,
		            'post_type' => 'post',
					'post_author' => $current_user -> ID,
					'tags_input' => $post_tags,
					'post_category' => $post_categories,
                    'tax_input' => array( 'portfolio' => $portfolios ) 
		        );
                
                $new_post = wp_insert_post($post_args);
        	}else{
                $updated_post = get_post($post_id);
        		$post_args = array(
        			'ID' => $post_id,	
		            'post_title' => $post_title,
		            'post_content' => $content ,
		            'post_status' => $post_status,
                    'comment_status'=> $updated_post -> comment_status,
		            'post_type' => 'post',
					'post_author' => $current_user -> ID,
					'tags_input' => $post_tags,
        			'post_category' => $post_categories,
                    'tax_input' => array( 'portfolio' => $portfolios ) 
		        );
                
                $new_post = wp_update_post($post_args);
        	}    
	
	        
	        
			if($post_status == 'pending'){ /*we will notify admin via email if a this option was activated*/
				if(is_email(options::get_value( 'upload' , 'pending_email' ))){
					$tomail = options::get_value( 'upload' , 'pending_email' );
					$subject = __('A new post is awaiting your moderation','cosmotheme');
					$message = __('A new post is awaiting your moderation.','cosmotheme');
					$message .= ' ';
					$message .= sprintf(__('To moderate the post go to  %s ','cosmotheme'), home_url('/wp-admin/post.php?post='.$new_post.'&action=edit')) ;

					wp_mail($tomail, $subject , $message);

				}	
			}

	        return $new_post;
        }

		function remove_post(){
			if(isset($_POST['post_id']) && is_numeric($_POST['post_id'])){
				$post = get_post($_POST['post_id']);
				if(get_current_user_id() == $post->post_author){
					wp_delete_post($_POST['post_id']);
				}
			}  

			exit;
		}
        
        function get_source($post_id){
        	
        	$source = '';
  			$source_meta = meta::get_meta( $post_id , 'source' );
  			
  			if(is_array($source_meta) && sizeof($source_meta) && isset($source_meta['post_source']) && trim($source_meta['post_source']) != ''){
  				$source = $source_meta['post_source'];
        		
  			}else{
  				$source = ''; //'<div class="source no_source"><p>'.__('Unknown source','cosmotheme').'</p></div>';
  			}
  			
        
        			
  			return $source;      	
        }

        function get_client($post_id){
            
            $client = '';
            $source_meta = meta::get_meta( $post_id , 'source' );
            
            if( isset($source_meta['post_client']) && trim($source_meta['post_client']) != ''){
                $client = $source_meta['post_client'];
            }
                
            return $client;         
        }

    function get_services($post_id){
            
            $services = '';
            $source_meta = meta::get_meta( $post_id , 'source' );
            
            if(isset($source_meta['post_services']) && trim($source_meta['post_services']) != ''){
                $services = $source_meta['post_services'];
            }
                
            return $services;         
        }

		function get_attached_file($post_id){
        	
        	$attached_file = '';
  			$attached_file_meta = meta::get_meta( $post_id , 'format' );

  			
			if(is_array($attached_file_meta) && sizeof($attached_file_meta) && isset($attached_file_meta['link_id']) && is_array($attached_file_meta['link_id'])){
				foreach($attached_file_meta['link_id'] as $file_id)
				  {
					$attachment_url = explode('/',wp_get_attachment_url($file_id));
					$file_name = '';
					if(sizeof($attachment_url)){
					  $file_name = $attachment_url[sizeof($attachment_url) - 1];
					}	
					$attached_file .= '<div class="attach">';
					$attached_file .= '	<a href="'.wp_get_attachment_url($file_id).'">'.$file_name.'</a>';
					$attached_file .= '</div>';
				  }
			}else if(is_array($attached_file_meta) && sizeof($attached_file_meta) && isset($attached_file_meta['link_id']))
			  {
				$file_id=$attached_file_meta['link_id'];
				$attachment_url = explode('/',wp_get_attachment_url($file_id));
					$file_name = '';
					if(sizeof($attachment_url)){
					  $file_name = $attachment_url[sizeof($attachment_url) - 1];
					}	
					$attached_file .= '<div class="attach">';
					$attached_file .= '	<a href="'.wp_get_attachment_url($file_id).'">'.$file_name.'</a>';
					$attached_file .= '</div>';
			  }
  					
  			return $attached_file;      	
        }

		function get_audio_file($post_id){
        	$attached_file = '';
  			$attached_file_meta = meta::get_meta( $post_id , 'format' );
  			
			if(is_array($attached_file_meta) && sizeof($attached_file_meta) && isset($attached_file_meta['audio']) && is_array($attached_file_meta['audio'])){

				foreach($attached_file_meta['audio'] as $audio_id)
				  {
					$attached_file .= '[audio:'.wp_get_attachment_url($audio_id).']';
				  }				
			}else if(is_array($attached_file_meta) && sizeof($attached_file_meta) && isset($attached_file_meta['audio']) && $attached_file_meta['audio'] != '' ){
			  $attached_file .= '[audio:'.$attached_file_meta['audio'].']';
			}
  					
  			return $attached_file;      	
        }
        
        function play_video($width=570, $height=414){
        	$result = '';	

            if(isset($_POST['width']) && is_numeric($_POST['width']) && isset($_POST['height']) && is_numeric($_POST['height'])){
                $width = $_POST['width'];
                $height = $_POST['height'];
            }

        	if(isset($_POST['video_id']) && isset($_POST['video_type']) && $_POST['video_type'] != 'self_hosted'){	
        		$result = self::get_embeded_video($_POST['video_id'],$_POST['video_type'],1,$width, $height);
        	}else{
                $video_url = urldecode($_POST['video_id']);
                $result = self::get_local_video($video_url, $width, $height, true );
            }	
        	
        	echo $result;
        	exit;
        }
        
        function list_tags($post_id){
            $tag_list = '';
            $tags = wp_get_post_terms($post_id, 'post_tag');

            if (!empty($tags)) {
                    $i = 1;
                    foreach ($tags as $tag) { 
                        if($i==1){
                            $tag_list .= $tag->name;
                        }else{
                            $tag_list .= ', '.$tag->name;
                        }    
                        $i++;
                    }
            }
            
            return $tag_list;
        }

         /*check if showing featured image on archive pages is enabled*/
        public function is_feat_enabled($post_id){
            
            $meta = meta::get_meta( $post_id , 'settings' );
            
            if(isset($meta['show_feat_on_archive']) && $meta['show_feat_on_archive'] == 'yes'){
            
                return true;
            }elseif(isset($meta['show_feat_on_archive']) && $meta['show_feat_on_archive'] == 'no'){
                return false;
            }else{
                if(options::get_value( 'blog_post' , 'show_feat_on_archive' ) == 'yes'){
                    return true;
                }else{
                    return false;
                }
            }
            
        }

        function get_post_date($post_id){
            if (options::logic('general', 'time')) {
                 $post_date = human_time_diff(get_the_time('U', $post_id), current_time('timestamp')) . ' ' . __('ago', 'cosmotheme'); 
            } else {
                $post_date = __('on','cosmotheme'). ' '.date_i18n(get_option('date_format'), get_the_time('U', $post_id)); 
            }

            return $post_date .' ';
        }

        function get_post_categories($post_id, $only_first_cat = false, $taxonomy = 'category'){

            
                            
            $cat = '';
            $categories = wp_get_post_terms($post_id, $taxonomy );
            if (!empty($categories)) {
                
                $ind = 1;
                foreach ($categories as $category) {
                    $categ = get_category($category);
                    if($ind > 1){
                        $cat .= ', ';   
                    }
                    $cat .= '<a href="' . get_category_link($category) . '">' . $categ->name . '</a>';
                    
                    if($only_first_cat){
                        break;    
                    }
                    

                    $ind ++;
                }
                
                
                //$cat = __('in','cosmotheme').' '.   $cat;   
            }
                            
              return $cat .' ' ;
        }

        
        function load_more(){
            $response = array();
            if(isset($_POST['action']) ){
                $elements = options::get_value( 'front_page', 'elements' );
                $id = $_POST[ 'id' ];
                $element = new FrontpageElement( $elements[ $id ] );

                $is_ajax = true;

                $nonce = $_POST['getMoreNonce'];

                // check to see if the submitted nonce matches with the
                // generated nonce we created earlier
                if ( ! wp_verify_nonce( $nonce, 'myajax-getMore-nonce' ) )
                    die ( 'Busted! Wrong Nonce');

                /*Done with check, now let's do some real work*/

                $element -> view = $_POST['view']; 
                $element -> carousel = 'no'; 
                $element -> list_thumbs = 'no'; 
                $element ->  paged = $_POST['current_page'] + 1;
                $element ->  is_ajax = true;

                $type = $_POST['type'];

                ob_start();
                ob_clean();

                call_user_func( array ( $element, "render_frontend_$type" ) );
                $content = ob_get_clean();
                
                $response['content'] = $content;
                $response['current_page'] = $element ->  paged;
                global $wp_query;
                $response['need_load_more'] = ( $wp_query -> query_vars[ 'paged' ] < $wp_query -> max_num_pages );
                wp_reset_query();
            }

            echo json_encode($response);
            exit;    
        }

        function get_post_img_slideshow($post_id){
            $attachments = get_children(array('post_parent' => $post_id,
                        'post_status' => 'inherit',
                        'post_type' => 'attachment',
                        'post_mime_type' => 'image',
                        'order' => 'ASC',
                        'orderby' => 'menu_order ID'));


            
            if(count($attachments) > 1){
            ?>
                    <div class="slider-container " style="height:380px">
                        <div  id="slides_single" class="cosmo-slider">
                   

                       

                        
            <?php          
                foreach($attachments as $att_id => $attachment) {
                    $full_img_url = wp_get_attachment_url($attachment->ID);
                    $thumbnail_url= wp_get_attachment_image_src( $attachment->ID, 'tgrid');
            ?>            
                    <div class="content">
                        <a  rel='prettyPhoto[<?php echo $post_id; ?>]' href="<?php echo $full_img_url; ?>">
                            <img src="<?php echo $full_img_url; ?>">
                        </a>
                    </div>
            <?php    
                }
            ?>
                        </div>
                    </div>
            <?php    
            }    
        }

        function get_excerpt($post, $ln){
            if ( is_user_logged_in() ) {
                if (!empty($post->post_excerpt)) {
                    if (strlen(strip_tags(strip_shortcodes($post->post_excerpt))) > $ln) {
                        echo mb_substr(strip_tags(strip_shortcodes($post->post_excerpt)), 0, $ln) . '[...]';
                    } else {
                        echo strip_tags(strip_shortcodes($post->post_excerpt));
                    }
                } else {
                    if (strlen(strip_tags(strip_shortcodes($post->post_content))) > $ln) {
                        echo mb_substr(strip_tags(strip_shortcodes($post->post_content)), 0, $ln) . '[...]';
                    } else {
                        echo strip_tags(strip_shortcodes($post->post_content));
                    }
                }
            }else{
                if ( !tools::is_nsfw( $post -> ID ) ) {
                    if (!empty($post->post_excerpt)) {
                        if (strlen(strip_tags(strip_shortcodes($post->post_excerpt))) > $ln) {
                            echo mb_substr(strip_tags(strip_shortcodes($post->post_excerpt)), 0, $ln) . '[...]';
                        } else {
                            echo strip_tags(strip_shortcodes($post->post_excerpt));
                        }
                    } else {
                        if (strlen(strip_tags(strip_shortcodes($post->post_content))) > $ln) {
                            echo mb_substr(strip_tags(strip_shortcodes($post->post_content)), 0, $ln) . '[...]';
                        } else {
                            echo strip_tags(strip_shortcodes($post->post_content));
                        }
                    }
                }else{
                    echo options::get_value( 'general' , 'nsfw_content' );
                }
            }  
        }

    }
?>
<?php get_header(); ?>
<section id="main">
    

    <?php
        while( have_posts () ){ 
            the_post();
            $post_id = $post -> ID;
                    
            $classes = tools::login_attr( $post -> ID , 'nsfw' );
            $template = 'single';
            $attr = tools::login_attr( $post -> ID , 'nsfw mosaic-overlay' , get_permalink( $post -> ID ) );
			if( layout::length( $post_id , $template ) == layout::$size['large'] ){
                $size = 'tlarge';
            }else{
				$size = 'tmedium'; 
			}
            $s = image::asize( image::size( $post->ID , $template , $size ) );
            
            $zoom = false; 
            
            if( options::logic( 'general' , 'enb_featured' ) ){
                if ( has_post_thumbnail( $post -> ID ) && get_post_format( $post -> ID ) != 'video' ) {
                    $src        = image::thumbnail( $post -> ID , $template , $size );
                    $src_       = image::thumbnail( $post -> ID , $template , 'full' );
                    $caption    = image::caption( $post -> ID );
                    $zoom       = true;
                }
            } 

            $post_layout = meta::get_meta( $post -> ID , 'layout' ); 
    ?>
           
            
            <div class="row">
                <div class="twelve columns entry-title">

                    <?php if(post::show_meta_author_box($post) ) { ?>
                    <div class="profile-pic">
                        <?php echo cosmo_avatar( $post->post_author , 50 , $default = DEFAULT_AVATAR ); ?>
                    </div>
                    <?php } ?>
                    <div>
                        <h2 class="entry-title"><?php the_title(); ?></h2>
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
            </div>                   
            
            <div class="row"> 

                
                <?php layout::side( 'left' , $post_id , 'single'); /*left sidebar*/ ?>

                <div id="primary" class="<?php if(meta::logic( $post , 'settings' , 'meta' )){echo ' nine columns ';}else{ echo tools::primary_class( $post_id , 'single', $return_just_class = true ); } ?>" >
                    <div id="content" role="main">
                        
                        
                        <div id="post" class="row">
                            
                            
                            <article id="post-<?php the_ID(); ?>" <?php post_class( 'post row big-post single-post entry' , $post -> ID ); ?>>
                                
                                    
                                    <?php
                                        if( options::logic( 'general' , 'enb_featured' ) ){
                                            if ( has_post_thumbnail( $post -> ID ) && get_post_format( $post -> ID ) != 'video' ) {
                                                $src = image::thumbnail( $post -> ID , $template , $size );
                                                $caption = image::caption( $post -> ID );

                                                if( meta::logic( $post , 'settings' , 'enb_sldsh_attached_img' ) ){
                                                    ob_start();
                                                    ob_clean();
                                                    post::get_post_img_slideshow($post -> ID);
                                                    $single_slideshow = ob_get_clean();
                                                }

                                                if(isset($single_slideshow) && strlen($single_slideshow)){
                                                    echo $single_slideshow;
                                                }else{ /*we show featured image only when */
                                        ?>          <div class="img relative hovermore">
                                                        <?php if($zoom && options::logic( 'general' , 'enb_lightbox' )){ ?>
                                                        <div class="mosaic-overlay">
                                                            <div class="zoom-image">
                                                                <a href="<?php echo $src_[0]; ?>" rel="prettyPhoto-<?php echo $post -> ID; ?>" title="<?php echo  $post -> post_title;  ?>">&nbsp;</a>
                                                            </div>
                                                        </div>
                                                        <?php } ?>
                                                        <?php
                                                            if ( strlen( $classes ) ) {
                                                                echo image::mis( $post -> ID , $template , $size , 'safe image' , 'nsfw' );
                                                            }else{
                                                                echo '<img src="' . $src[0] . '" alt="' . $caption . '" >';
                                                            }
                                                        ?>
                                                        
                                                        <div class="format">&nbsp;</div>
                                                        <?php if (options::logic('styling', 'stripes')) { ?>
                                                            <div class="stripes">&nbsp;</div>
                                                        <?php } ?>
                                                    
                                                    </div>
                                                
                                    <?php
                                                } /*EOF if exists single slideshow*/
                                            }else if(get_post_format( $post -> ID ) == 'video'){

                                                $video_format = meta::get_meta( $post -> ID , 'format' );

                                                  
                                                $format=$video_format;
                                                
                                                echo '<div class="embedded_videos">';
                                                  
                                                if( isset( $format['video'] ) && !empty( $format['video'] ) && post::isValidURL( $format['video'] ) ){
                                                    $vimeo_id = post::get_vimeo_video_id( $format['video'] );
                                                    $youtube_id = post::get_youtube_video_id( $format['video'] );
                                                    $video_type = '';
                                                    if( $vimeo_id != '0' ){
                                                        $video_type = 'vimeo';
                                                        $video_id = $vimeo_id;
                                                    }

                                                    if( $youtube_id != '0' ){
                                                        $video_type = 'youtube';
                                                        $video_id = $youtube_id;
                                                    }

                                                    if( !empty( $video_type ) ){
                                                        echo post::get_embeded_video( $video_id , $video_type );
                                                    }

                                                }else if( isset( $video_format["feat_url"] ) && strlen($video_format["feat_url"])>1){

                                                      $video_url=$video_format["feat_url"];
                                                      if(post::get_youtube_video_id($video_url)!="0")
                                                        {
                                                          echo post::get_embeded_video(post::get_youtube_video_id($video_url),"youtube");
                                                        }
                                                      else if(post::get_vimeo_video_id($video_url)!="0")
                                                        {
                                                          echo post::get_embeded_video(post::get_vimeo_video_id($video_url),"vimeo");
                                                        }
                                                }else if(isset( $video_format["feat_id"] ) && strlen($video_format["feat_id"])>1){
                                                    echo do_shortcode('[video mp4="'.wp_get_attachment_url($video_format["feat_id"]).'" width="610" height="443"]');
                                                    //echo post::get_local_video( urlencode(wp_get_attachment_url($video_format["feat_id"])));
                                                }
                                            
                                                echo '</div>';
                                            }
                                        }
                                        
                                    ?>

                                    <?php
                                        if( ((isset($post_layout['type']) && $post_layout['type'] != 'full') || (!isset($post_layout['type']) && options::get_value( 'layout' , $template ) != 'full' && options::get_value(  'general' , 'meta' ) == 'yes' )) && meta::logic( $post , 'settings' , 'meta' ) ){
                                            /*if not full width and meta is enabled*/
                                            get_template_part('entry-meta');
                                        }

                                    ?>
                                     

                                    
    								<div class="entry-content">
                                               
                                        <div class="">
                                            <?php 
                                                if( strlen( $classes ) ){
                                                    echo options::get_value( 'general' , 'nsfw_content' );
                                                }else{


                                                    //-------------------------
                                                    if( get_post_format( $post -> ID ) == 'video' ){

                                                        $video_format = meta::get_meta( $post -> ID , 'format' );
                                                    ?>
                                                        
                                                    <div class="embedded_videos">    
                                                                    
                                                        <?php    

                                                        if(isset($video_format['video_ids']) && !empty($video_format['video_ids'])){
                                                            foreach($video_format["video_ids"] as $videoid)
                                                            {
                                                                if( isset( $video_format[ 'video_urls' ][ $videoid ] ) ){
                                                                    $video_url = $video_format[ 'video_urls' ][ $videoid ];
                                                                    if( post::get_youtube_video_id($video_url) != "0" ){
                                                                        echo post::get_embeded_video( post::get_youtube_video_id( $video_url ), "youtube" );
                                                                    }else if( post::get_vimeo_video_id( $video_url ) != "0" ){
                                                                        echo post::get_embeded_video( post::get_vimeo_video_id( $video_url ) , "vimeo" );
                                                                    }
                                                                }
                                                                else echo post::get_local_video( urlencode(wp_get_attachment_url($videoid)));
                                                            }
                                                        }    
                                                    ?>
                                                    </div>
                                                    <?php                                     
                                                    }

                                                    //---------------------------
                                                    
                                                    if(get_post_format($post->ID)=="image" && !(isset($single_slideshow) && strlen($single_slideshow)) )
                                                    {
                                                        $image_format = meta::get_meta( $post -> ID , 'format' );
                                                        echo "<div class=\"attached_imgs_gallery\">";
                                                        if(isset($image_format['images']) && is_array($image_format['images']))
                                                        {
                                                            foreach($image_format['images'] as $index=>$img_id)
                                                            {
                                                                $thumbnail= wp_get_attachment_image_src( $img_id, 'thumbnail');
                                                                $full_image=wp_get_attachment_url($img_id);
                                                                $url=$thumbnail[0];
                                                                $width=$thumbnail[1];
                                                                $height=$thumbnail[2];
                                                                echo "<div class=\"attached_imgs_gallery-element\">";
                                                                echo "<a title=\"\" rel=\"prettyPhoto[".get_the_ID()."]\" href=\"".$full_image."\">";

                                                                if($height<150)
                                                                {
                                                                    $vertical_align_style="style=\"margin-top:".((150-$height)/2)."px;\"";
                                                                }
                                                                else
                                                                {
                                                                    $vertical_align_style="";
                                                                }

                                                                echo "<img alt=\"\" src=\"$url\" width=\"$width\" height=\"$height\" $vertical_align_style>";
                                                                echo "</a>";
                                                                echo "</div>";
                                                            }
                                                            
                                                        }
                                                        echo "</div>";
                                                    }

                                                    if( get_post_format( $post -> ID ) == 'audio' ){
                                                        $audio = new AudioPlayer(); 
                                                        echo $audio->processContent( post::get_audio_file( $post -> ID ) );
                                                    }
                                                    the_content(); 
                                                }
                                            ?>
    										<?php
    											if( strlen( $classes ) == 0 ){
    												if( get_post_format( $post -> ID ) == 'link' ){
    													echo post::get_attached_file( $post -> ID );
    												}

    											}
    										?>  

                                            <?php
                                                if( meta::logic( $post , 'settings' , 'sharing' ) ){
                                            ?>
                                            <div class="entry-footer share">
                                                <div class="row">
                                                    <div class=""> 
                                                        <div class="left"> 
                                                            <?php get_template_part('social-sharing'); ?>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                            <?php
                                                }
                                            ?>
                                        </div>
                                       
                                    </div>
                                
                            </article>
                        </div>     
                            
                        <?php 
                            /* related posts */
                            get_template_part( 'related-posts' ); 
                        ?>
                    </div>
                                         

                    <?php
                        get_ads('content', tools::primary_class( $post_id , 'single', $return_just_class = true ) );
                    ?>
                        
                </div>
                <?php 
                    if( (isset($post_layout['type']) && ($post_layout['type'] == 'full') || (!isset($post_layout['type']) && options::get_value( 'layout' , $template ) == 'full' ) ) && meta::logic( $post , 'settings' , 'meta' ) ){
                        if(options::logic( 'blog_post' , 'enb-floating-meta' ) ){
                            $scrollit = 'scrollit';
                        }else{
                            $scrollit = '';
                        }
                ?>
                    <div id="entry-meta-aside" class="three columns <?php echo $scrollit; ?>">
                        <?php
                            /*if full width and meta is enabled*/
                            get_template_part('entry-meta');
                        ?>
                    </div>
                <?php
                    }
                ?>

                <?php layout::side( 'right' , $post_id , 'single' ); /*right sidebar*/ ?>
            </div>
            <?php
            //if(isset($post_layout['type']) && $post_layout['type'] == 'full'){
       
                /* comments */
                if( comments_open() ){
            ?>
                <div class="row">            
            <?php        
                    if( options::logic( 'general' , 'fb_comments' ) ){
                        ?>
                        <div class="twelve columns">
                            <div class="load-more comment-here">
                                
                                    <?php 
                                        $comments_label = sprintf(__('Leave a %s','cosmotheme'),'<span>'.__('comment','cosmotheme').'</span>');
                                        echo $comments_label;
                                    ?>
                               
                            </div>    
                            
                            <fb:comments href="<?php the_permalink(); ?>" num_posts="5" width="430" height="120" reverse="true"></fb:comments>
                            
                        </div>
                        <?php
                    }else{
                        comments_template( '', true );
                    }
            ?>   
                </div>     
            <?php    
                }
            //}
            ?> 
    <?php
        }
    ?>

</section>
<?php get_footer(); ?>

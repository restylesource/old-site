<?php 
    $classes = tools::login_attr( $post -> ID , 'nsfw' ); 

    $template = 'front_page';

    if( layout::length( $post -> ID , $template ) == layout::$size['large'] ){
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
    $post_id = $post -> ID;
?>
<div id="post" class="row single single-front">
    
    
    <article id="post-<?php the_ID(); ?>" <?php post_class( 'post row big-post single-post entry' , $post -> ID ); ?>>
        
            
            <?php
                if( options::logic( 'general' , 'enb_featured' ) ){
                    if ( has_post_thumbnail( $post -> ID ) && get_post_format( $post -> ID ) != 'video' ) {
                        $src = image::thumbnail( $post -> ID , $template , $size );
                        $caption = image::caption( $post -> ID );
            ?>          <div class="img relative">
                            
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
                if( ((isset($post_layout['type']) && $post_layout['type'] != 'full') || (!isset($post_layout['type']) && options::get_value(  'general' , 'meta' ) == 'yes' )) && meta::logic( $post , 'settings' , 'meta' ) ){
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
                            if(get_post_format($post->ID)=="image")
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
                                <div class="left lmargin"> 
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
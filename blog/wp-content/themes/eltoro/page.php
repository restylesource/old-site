<?php 
if( $post -> ID == options::get_value( 'general' , 'user_profile_page' ) ){
	get_template_part( 'user_profile_update' );
}
get_header(); 

?>
<section id="main" class="single">

    <?php
        while( have_posts () ){
            the_post(); 
            $post_id = $post -> ID;

            $classes = tools::login_attr( $post -> ID , 'nsfw' );
            $template = 'page';
            $attr = tools::login_attr( $post -> ID , 'nsfw mosaic-overlay' , get_permalink( $post -> ID ) );
            if( layout::length( $post_id , $template ) == layout::$size['large'] ){
                $size = 'tlarge';
            }else{
                $size = 'tmedium'; 
            }
            $s = image::asize( image::size( $post->ID , $template , $size ) );
            
            $zoom = false; 
            
            if( options::logic( 'general' , 'enb_featured' ) ){
                if ( has_post_thumbnail( $post -> ID ) ) {
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
                        <?php echo cosmo_avatar( $post->post_author , 50 , $default = DEFAULT_AVATAR_LOGIN ); ?>
                    </div>
                    <?php } ?>
                    <h2 class="entry-title">
                        <?php
                            if( (int)options::get_value( 'general' , 'my_posts_page' ) == $post_id ){
                                _e( 'My posts' , 'cosmotheme' );
                            }else{
                                the_title();
                            }
                        ?>
                    </h2>
                    <span>
                        <?php if(post::show_meta_author_box($post) ) { ?>
                        <?php _e('by','cosmotheme'); ?> <a href="<?php echo get_author_posts_url($post->post_author) ?>"><?php echo get_the_author_meta('display_name', $post->post_author); ?></a> 
                        <?php } ?>
                        <?php 
                            if(meta::logic( $post , 'settings' , 'meta' )){
                                echo post::get_post_date($post -> ID);    
                            }
                        ?>
                        
                    </span>
                </div>
            </div>  

            <div class="row"> 

                
                <?php layout::side( 'left' , $post_id , 'page'); /*left sidebar*/ ?>

                <div id="primary" class="<?php if(meta::logic( $post , 'settings' , 'meta' )){echo ' nine columns ';}else{ echo tools::primary_class( $post_id , 'page', $return_just_class = true ); } ?>" >
                    <div id="content" role="main">     
                        
                        <div id="post" class="row">  
						<?php
                            if( (int)options::get_value( 'general' , 'my_posts_page' ) == $post_id ){
                        ?>
                                <article id="post-<?php the_ID(); ?>" <?php post_class( 'post my-posts big-post' , $post -> ID ); ?>>
                                
                                    <div class="list">
                                        <?php post::my_posts( get_current_user_id() ); ?>
                                    </div>
                                </article> 
                        <?php
                            }else{
                        ?>
                            
                        
                                <?php 
                                    if( $post_id == options::get_value( 'upload' , 'post_item_page' ) ){
                                        get_template_part( 'post_item' );
                                    }elseif( $post_id == options::get_value( 'general' , 'user_profile_page' ) ){
                                        get_template_part( 'user_profile' );
                                    }else{
                                        

                                ?>  	
                                
                                
                                <article id="post-<?php the_ID(); ?>" <?php post_class( 'post big-post single-post no_bg entry' , $post -> ID ); ?>>  
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
                                            }
                                        }
                                        
                                    ?>

                                    <div class="entry-content">
                                               
                                        <div class="">
                                            <?php 
                                                the_content(); 
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

                                <?php
                                    
                                }
                            ?>
                            
                        <?php
                            }
                        ?>
                        </div>
					</div>
                    <?php
                        get_ads('content', tools::primary_class( $post_id , 'page', $return_just_class = true ) );
                    ?>
                </div>
                
                <?php
                    if(isset($post_layout['type']) && $post_layout['type'] == 'full' && meta::logic( $post , 'settings' , 'meta' ) ){
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
                <?php layout::side( 'right' , $post_id , 'page' ); /*right sidebar*/ ?>
            </div>

            <?php
            
                /* comments */
                
                if( comments_open() && $post -> ID != options::get_value( 'general' , 'user_profile_page' ) 
                    && $post -> ID != options::get_value( 'general' , 'my_posts_page' )
                    && $post -> ID != options::get_value( 'upload' , 'post_item_page' ) ){
            ?>
                <div class="row">            
            <?php        
                    if( options::logic( 'general' , 'fb_comments' ) ){
                        ?>
                        <div class="twelve columns">
                            <div class="load-more">
                                
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
            
            ?> 
    <?php
        }
    ?>
</section>
<?php get_footer(); ?>
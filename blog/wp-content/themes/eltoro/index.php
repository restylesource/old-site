<?php get_header(); ?>

<section id="main">

    
    <div class="row">
        <div id="entry-title" class="twelve columns relative">
        
            <?php
                if( have_posts () ){
                    ?><h2 class="bcontent-title twelve columns  blog_page"><?php _e( 'Blog page: ' , 'cosmotheme' ); echo get_cat_name( get_query_var('cat') ); ?></h2><?php

                }else{
                    ?><h2 class="bcontent-title twelve columns  blog_page"><?php _e( 'Sorry, no posts found' , 'cosmotheme' ); ?></h2><?php

                }
            ?>
            
        
        </div>
        <div class="delimiter"></div>  
    </div>


    
    <div class="row">

        
        <?php layout::side( 'left' , 0 , 'blog_page' ); /*left sidebar*/ ?>
        <div class="<?php echo tools::primary_class( 0 , 'blog_page', $return_just_class = true ); ?>" id="primary"> 
            <?php post::loop( 'blog_page' ); ?>
        </div>
        
        <?php layout::side( 'right' , 0 , 'blog_page' ); /*right sidebar*/  ?>
    </div>
</section>
<?php get_footer(); ?>

<?php get_header(); ?>
<section id="main">

    
    <div class="row">
        <div id="entry-title" class="twelve columns relative">
        
            <h2 class="content-title twelve columns  search"><?php _e( 'Error 404, page, post or resource can not be found' , 'cosmotheme' ); ?></h2>
            
        </div>    
    </div>

    <div class="row">
        
        <?php layout::side( 'left' , 0 , '404' ); /*left sidebar*/ ?>
            <div class="<?php echo tools::primary_class( 0 , '404', $return_just_class = true ); ?>" id="primary"> <!-- use here class from options -->
        
                    <?php get_template_part( 'loop' , '404' ); ?>
            </div>                    
 
        <?php layout::side( 'right' , 0 , '404' );  /*right sidebar*/ ?>
    </div>
</section>
<?php get_footer(); ?>
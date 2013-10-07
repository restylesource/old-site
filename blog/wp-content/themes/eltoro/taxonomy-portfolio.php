<?php get_header(); ?>


<?php
    $template = 'portfolio';
?>

<section id="main">
    <div class="row">
        <?php
            if( have_posts () ){
                ?><h3 class="bcontent-title "><?php _e( 'Portfolio archives: ' , 'cosmotheme' ); echo  get_query_var('portfolio') ; ?></h3><?php
            }else{
                ?><h3 class="bcontent-title "><?php _e( 'Sorry, no posts found' , 'cosmotheme' ); ?></h3><?php
            }
        ?>
        <div class="delimiter"></div> 
    </div>
    <div class="row">
        <?php layout::side( 'left' , 0 , $template ); /*left sidebar*/ ?>
        
        <div class="<?php echo tools::primary_class( 0 , $template, $return_just_class = true ); ?>" id="primary">
            <div id="content" role="main">
                

                <?php
                    
                    

                    if(tools::is_grid( $template , $side = '' )){
                        $layout_class = 'blog-grid-view';
                    }else{
                        $layout_class = 'blog-list-view';
                    }
                    
                ?>
                <div class="row blog <?php echo ' '.$layout_class;?>">
                    <?php post::loop( $template ); ?>
                </div>
            </div>
        </div>
        <?php layout::side( 'right' , 0 , $template ); /*right sidebar*/ ?>    
    </div>    
</section>
<?php get_footer(); ?>

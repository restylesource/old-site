<?php get_header(); ?>
<?php
    $template = 'archive';
?>
<section id="main">
    <div class="row">
                    
        <?php
            if( have_posts () ){
                ?>
                <h3 class="bcontent-title ">
                    <?php
                        if(is_tax( 'portfolio' )){
                            echo  __( 'Portfolio archives' , 'cosmotheme' );
                        }else if ( is_day() ) {
                            echo  __( 'Daily archives' , 'cosmotheme' ) . ': <span>' . get_the_date();
                        }else if ( is_month() ) {
                            echo  __( 'Monthly archives' , 'cosmotheme' ) . ': <span>' . get_the_date( 'F Y' );
                        }else if ( is_year() ) {
                            echo  __( 'Yearly archives' , 'cosmotheme' ) . ': <span>' . get_the_date( 'Y' ) ;
                        }else {
                            echo  __( 'Blog archives' , 'cosmotheme' ) ;
                        }
                    ?>

                </h3><?php
            }else{
                ?><h3 class="bcontent-title twelve columns  search"><?php _e( 'Sorry, no posts found' , 'cosmotheme' ); ?></h3><?php

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
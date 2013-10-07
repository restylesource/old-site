<?php
    get_header();
?>

<section id="main">

    
    <div class="row">
        <?php $left = layout::side( 'left' , 0 , 'front_page' ); /*left sidebar*/ ?>
  
        <div class="<?php echo tools::primary_class( 0 , 'front_page', $return_just_class = true ); ?>" id="primary"> 
            <div id="content" role="main">
                 
                <?php 
                    if( isset( $_GET[ 'fp_type' ] ) && $_GET[ 'fp_type' ] == 'like' ){
                        post::like();
                    }else{
                        $builder = new FrontpageBuilder();
                        $builder -> render_frontend();
                    }    
                ?>
            </div>
            
            
        </div>
        

        <?php $left = layout::side( 'right' , 0 , 'front_page' ); /*right sidebar*/ ?>
    </div>
    
    
</section>
<?php get_footer(); ?>
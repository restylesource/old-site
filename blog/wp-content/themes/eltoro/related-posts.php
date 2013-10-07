<?php
    /* related posts by herarchical taxonomy */
    /* get tax slugs and number of similar posts  */ 
    function similar_query( $post_id , $taxonomy , $nr ){
        if( $nr > 0 ){
            $topics = wp_get_post_terms( $post_id , $taxonomy );

            $terms = array();
            if( !empty( $topics ) ){
                foreach ( $topics as $topic ) {
                    $term = get_category( $topic );
                    array_push( $terms, $term -> slug );
                }
            }

            if( !empty( $terms ) ){
                $query = new WP_Query( array(
                    'post__not_in' => array( $post_id ) ,
                    'posts_per_page' => $nr,
                    'orderby' => 'rand',
                    'tax_query' => array(
                        array(
                        'taxonomy' => $taxonomy ,
                        'field' => 'slug',
                        'terms' => $terms ,
                        )
                    )
                ));
            }else{
                $query = array();
            }
        }else{
            $query = array();
        }

        return $query;
    }

    /* post taxonomy */
    $tax = options::get_value( 'blog_post' , 'similar_type' );
    $layout = meta::get_meta( $post -> ID , 'layout' );
    
    if( isset( $layout['type'] ) ){
        if( $layout['type'] != 'full' || meta::logic( $post , 'settings' , 'meta' )){
            $nr = (int)options::get_value( 'blog_post' , 'post_similar_side' );
        }else{
            $nr = (int)options::get_value( 'blog_post' , 'post_similar_full' );
        }
    }else{
        $layout = options::get_value( 'layout' , 'single' );
        if( $layout != 'full' || meta::logic( $post , 'settings' , 'meta' )){
            $nr = (int)options::get_value( 'blog_post' , 'post_similar_side' );
        }else{
            $nr = (int)options::get_value( 'blog_post' , 'post_similar_full' );
        }
    }

        
    if($tax == 'same_author'){
        
        $label = sprintf(__('More projects by %s','cosmotheme'), '<a href="'.get_author_posts_url($post->post_author).'">'.get_the_author_meta('display_name', $post->post_author)).'</a>';
        
        $query = $more_from_same_author = new WP_Query( array(
                                            'author' => $post -> post_author,
                                            'posts_per_page' => $nr,
                                            'post__not_in' => array($post->ID)
                                            ) );
    }else{
        $label  = __( 'Related Posts' , 'cosmotheme' );
        $query  = similar_query( $post -> ID , $tax , $nr );
        
    }
    
    $length = layout::length( $post -> ID , 'single' );

    if( !empty( $query ) ){
        if( $query -> found_posts < $nr ){
            $nr = $query -> found_posts;
        }

        $result = $query -> posts;
    }
        


    

    if( !empty( $result) && meta::logic( $post , 'settings' , 'related' ) ){
?>
    <div class="row related">
        <div class="<?php if(meta::logic( $post , 'settings' , 'meta' )){echo ' nine columns ';}else{ echo tools::primary_class( $post -> ID , 'single', $return_just_class = true ); } ?> ">
            <div class="delimiter"></div>
            <h3 class="content-title"><?php echo $label; ?></h3>
        
            <ul id="thumbs-list" class="image-grid" >
            <?php 
                foreach( $result as $similar ){
                    
                    
                   post::grid_view_thumbnails( $similar  );
                    
                    
                }

            ?>
            </ul>
        
        </div>    
    </div>
<?php

        wp_reset_postdata();
    }
?>
    
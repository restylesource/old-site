<?php
    class widget_testimonials extends WP_Widget{
        function widget_testimonials() {
            $options = array( 'classname' => 'testimonials', 'description' => __('Display latest Testimonials' , 'cosmotheme' ) );
            parent::WP_Widget( 'widget_cosmo_testimonials' , _TN_ . ' : ' . __( 'Latest Testimonials' , 'cosmotheme' )  , $options );

        }

        function form($instance) {

            if( isset($instance['title']) ){
                $title = esc_attr($instance['title']);
            }else{
                $title = __( 'Testimonials' , 'cosmotheme' );
            }

            
            if( isset( $instance['nr'] ) ){
                $nr = $instance['nr'];
            }else{
                $nr = 3;
            }
            
        ?>
        <p>
          <label for="<?php echo $this -> get_field_id('title'); ?>"><?php _e( 'Title' , 'cosmotheme' ); ?>:</label>
          <input class="widefat" id="<?php echo $this -> get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>

        
        <!-- field for set limit  -->
        <p>
          <label for="<?php echo $this -> get_field_id('nr'); ?>"><?php _e( 'Number of testimonials to display' , 'cosmotheme' ); ?>:</label>
          <input class="widefat" id="<?php echo $this -> get_field_id('nr'); ?>" name="<?php echo $this->get_field_name('nr'); ?>" type="text" value="<?php echo $nr; ?>" />
        </p>
       
        <?php
        }

        function update($new_instance, $old_instance) {
            $instance = $old_instance;
            $instance['title']      = strip_tags($new_instance['title']);
            $instance['nr']         = strip_tags( $new_instance['nr'] );
            return $instance;
        }

        function widget($args, $instance) {

            extract( $args );

            /* widget title */
            if( !empty( $instance['title'] ) ){
               $title = apply_filters('widget_title', $instance['title']);
            }else{
               $title = '';
            }

            
            /* limit number */
            if( isset( $instance['nr'] ) ){
                $nr = $instance['nr'];
            }else{
                $nr = 3;
            }

            echo $before_widget;

            if ( strlen( $title ) ) {
                    echo $before_title . $title . $after_title;
            }

            $args = array('post_type' => 'testimonial', 'posts_per_page' => $nr);
            $testimonials = new WP_Query($args);

            if(count($testimonials -> posts)){
                $result = '<div class="cosmo-testimonials " >
                                <div class="slides_container">';
                foreach( $testimonials -> posts as $post ){
                    //$post = get_post( $testimonial['idrecord'] );  /*get testimonial info*/
                        
                    $result .= '<div>';
                    $result .= '    <ul>';
                    $result .= '        <li>';

                        
                    if( has_post_thumbnail( $post -> ID  ) ){
                        $result .= '<a href="' . get_permalink( $post -> ID  ). '" class="hover">'.wp_get_attachment_image( get_post_thumbnail_id( $post -> ID ), 'tsmall' ).'</a>';
                    }
                    $testimonial_info = meta::get_meta( $post->ID, 'info' ); 
                    if($testimonial_info['name'] != ''  || $testimonial_info['title'] != ''){
                        $result .= '<p class="author">';
                        $result .= '<span class="name">'.$testimonial_info['name'].'</span>'; /**author name*/
                        $result .= '<span class="title">'.$testimonial_info['title'].'</span>'; /*author title*/
                        $result .= '</p>';
                        
                    }
                    
                    $result .= '<p class="cite">
                                    <cite>'.__($post -> post_content).'</cite>
                                </p>';              
                    $result .= '        </li>';
                    $result .= '    </ul>';
                    $result .= '</div>';
                    
                }
                $result .= '    </div>';
                if(count($testimonials -> posts) > 1){
                $result .= '<span class="actions">
                                <a class="prev"><i class="b-pager__arr">←</i> Previous</a>
                                <a class="next">Next <i class="b-pager__arr">→</i></a>
                            </span>';
                }
                $result .= '</div>';
                
                echo $result;    
            }else{
                echo '<p class="select">' . __( 'There are no testimonials' , 'cosmotheme' ) . '</p>';
            }

            

            echo $after_widget;
        }
    }
?>
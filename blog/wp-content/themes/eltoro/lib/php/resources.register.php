<?php
    $sidebar_value = extra::select_value( '_sidebar' );

    if(!( is_array( $sidebar_value ) || !empty( $sidebar_value ) ) ){
        $sidebar_value = array();
    }

    /* hide if is full width */
    $classes = 'sidebar_list';

    if( isset( $_GET['post'] ) ){
        $meta = meta::get_meta( (int) $_GET['post'] , 'layout' );

        if( isset( $meta['type'] ) && $meta['type'] == 'full' ){
            $classes = 'sidebar_list hidden';
        }
    }

    $position = array( 'left' => __( 'Align Left' , 'cosmotheme' ) , 'right' => __( 'Align Right' , 'cosmotheme' ) );
    /* post type slideshow */
    $res[ 'slideshow' ][ 'labels' ] = array(
        'name' => _x(__('Slideshow','cosmotheme'), 'post type general name'),
        'singular_name' => _x(__('Slideshow','cosmotheme'), 'post type singular name'),
        'add_new' => _x('Add New', __('Slideshow','cosmotheme')),
        'add_new_item' => __('Add New Slideshow','cosmotheme'),
        'edit_item' => __('Edit Slideshow','cosmotheme'),
        'new_item' => __('New Slideshow','cosmotheme'),
        'view_item' => __('View Slideshow','cosmotheme'),
        'search_items' => __('Search Slideshow','cosmotheme'),
        'not_found' =>  __('Nothing found','cosmotheme'),
        'not_found_in_trash' => __('Nothing found in Trash','cosmotheme')
    );
    $res[ 'slideshow' ][ 'args' ] = array(
        'public' => true,
        'hierarchical' => false,
        'menu_position' => 3,
        'supports' => array('title'),
    	'exclude_from_search' => true,
        '__on_front_page' => true,
        'menu_icon' => get_template_directory_uri() . '/lib/images/custom.slideshow.png'
    );

    $struct[ 'slideshow' ][ 'box' ] = array(
        'layout' => 'B',
        'field-style' => 'line',
        'check-column' => array(
            'name' => 'idrow',
            'type' => 'hidden',
            'evisible' => false,
            'lvisible' => false,
        ),
        'icon-column' => array(
            'name' => 'slide',
            'type' => 'attachment',
            'attach_type' => 'image',
            'width' => 100,
            'height' => 100,
            'evisible' => false,
            'lvisible' => false,
        ),
        'info-column-0' => array(
            array(
                'name' => 'resources',
                'type' => 'hidden',
                'evisible' => true,
                'lvisible' => false,
                'post_link' => true,
            ),
            array(
                'name' => 'type_res',
                'type' => 'hidden',
                'evisible' => false,
                'lvisible' => false,
            ),
            array(
                'name' => 'title',
                'type' => 'text',
                'label' => __( 'Slide title' , 'cosmotheme' ),
                'before' => '<h2>',
                'after' => '</h2>',
                'evisible' => false,
                'lvisible' => true,
            ),
            array(
                'name' => 'title_color',
                'type' => 'color-picker',
                'label' => __( 'Title text color' , 'cosmotheme' ),
                'evisible' => false,
                'lvisible' => true
            ),
            array(
                'name' => 'first_line',
                'type' => 'text',
                'label' => __( 'First line', 'cosmotheme' ),
                'evisible' => false,
                'lvisible' => true,
                'classes' => 'text-slide-option'
            ),
            array(
                'name' => 'second_line',
                'type' => 'text',
                'label' => __( 'Second line', 'cosmotheme' ),
                'evisible' => false,
                'lvisible' => true,
                'classes' => 'text-slide-option'
            ),
            array(
                'name' => 'description',
                'type' => 'textarea',
                'label' => __( 'Description' , 'cosmotheme' ),
                'evisible' => false,
                'lvisible' => true,
                'classes' => 'not-text-slide-option'
            ),
            array(
                'name' => 'description_color',
                'type' => 'color-picker',
                'label' => __( 'Description text color' , 'cosmotheme' ),
                'evisible' => false,
                'lvisible' => true,
                'classes' => 'not-text-slide-option'
            ),
            array(
                'name' => 'description_position',
                'type' => 'select',
                'label' => __( 'Description position' , 'cosmotheme' ),
                'assoc' => $position,
                'evisible' => false,
                'lvisible' => true,
                'fafter' => '<br>',
                'classes' => 'not-text-slide-option'
            ),
            array(
                'name' => 'background_position',
                'type' => 'select',
                'label' => __( 'Background position' , 'cosmotheme' ),
                'assoc' => array(
                    'left' => __( 'Left' , 'cosmotheme' ),
                    'center' => __( 'Center' , 'cosmotheme' ),
                    'right' => __( 'Right' , 'cosmotheme' )
                ),
                'evisible' => false,
                'lvisible' => true,
            ),
            array(
                'name' => 'background_repeat',
                'type' => 'select',
                'label' => __( 'Background repeat' , 'cosmotheme' ),
                'assoc' => array(
                    'no-repeat' => __( 'No repeat' , 'cosmotheme' ),
                    'repeat' => __( 'Tile' , 'cosmotheme' ),
                    'repeat-x' => __( 'Tile horizontally' , 'cosmotheme' ),
                    'repeat-y' => __( 'Tile vertically' , 'cosmotheme' )
                ),
                'evisible' => false,
                'lvisible' => true,
            ),
            array( 
                'name' => 'background_attachment',
                'type' => 'select',
                'label' => __( 'Background attachment type' , 'cosmotheme' ),
                'assoc' => array(
                    'scroll' => __( 'Scroll' , 'cosmotheme' ),
                    'fixed' => __( 'Fixed' , 'cosmotheme' )
                ),
                'evisible' => false,
                'lvisible' => true,
            ),
            array(
                'name' => 'slider_background_color',
                'type' => 'color-picker',
                'label' => __( 'Slider background color' , 'cosmotheme' ),
                'evisible' => false,
                'lvisible' => true,
            ),
            array(
                'name' => 'url',
                'type' => 'text',
                'label' => __( 'Custom URL' , 'cosmotheme' ),
                'evisible' => false,
                'lvisible' => true,
            )
        ),
        'actions' => array(
            0 => array( 'slug' => 'edit' , 'label' => 'edit' ,  'args' => array( 'res' => 'slideshow' , 'box' => 'box' , 'post_id' => '' , 'index' => '' , 'selector' => 'div#slideshow_box div.inside div#box_slideshow_box' ) ),
            1 => array( 
                'slug' => 'update' , 
                'label' => 'update' , 
                'args' => array( 
                    'res' => 'slideshow' , 
                    'box' => 'box' , 
                    'post_id' => '' , 
                    'index' => '' , 
                    'data' => array( 
                        'input' =>  "['slideshow-box-slide_id' ,
                                    'slideshow-box-slide' ,
                                    'slideshow-box-title',
                                    'slideshow-box-title_color',
                                    'slideshow-box-first_line',
                                    'slideshow-box-second_line',
                                    'slideshow-box-description',
                                    'slideshow-box-description_color',
                                    'slideshow-box-slider_caption',
                                    'slideshow-box-background_color',
                                    'slideshow-box-background_position',
                                    'slideshow-box-background_repeat',
                                    'slideshow-box-background_attachment',
                                    'slideshow-box-slider_background_color',
                                    'slideshow-box-description_position',
                                    'slideshow-box-url ' ]"
                        ),
                        'selector' => 'div#slideshow_box div.inside div#box_slideshow_box'
                 )
            ),
            2 => array( 'slug' => 'del' , 'label' => 'delete' , 'args' => array( 'res' => '' , 'box' => '' , 'post_id' => '' , 'index' => '' , 'selector' => 'div#slideshow_box div.inside div#box_slideshow_box' ) )
        )
    );

    $sl_res = array( 'none' => __( 'Simple image' , 'cosmotheme' ) , 'post' => __( 'Post' , 'cosmotheme' ), 'text' => __( 'Text slide' , 'cosmotheme' ) );

    $form['slideshow']['box']['type_res'] = array(
        'type' => 'st--m-select' ,
        'label' => __( 'Select slider type' , 'cosmotheme') ,
        'value' =>  $sl_res ,
        'action' => "act.select('#type_resource' , { 'none' : '.image-slide', 'post' : '.mis-hint .generic-hint , .slider_resources', 'text' : '.text-slide' }, 'sh_');" ,
        'id' => 'type_resource'
    );
    $form['slideshow']['box'][ 'resources' ]  = array( 'type' => 'st--m-search' , 'label' => __( 'Select a post' , 'cosmotheme' ) , 'classes' => 'slider_resources hidden' , 'query' => array( 'post_type' => 'post' , 'post_status' => 'publish' ) , 'action' => "act.search( this , '-');", 'hint'=>__('Start typing the post title','cosmotheme') );
    $form['slideshow']['box']['slide' ]      = array( 'type' => 'st--m-upload-id' , 'label' => __( 'Upload an image or choose one from media library' , 'cosmotheme') , 'id' => 'box_slide' , 'hint' =>  __( 'If not uploaded will use post Featured image' , 'cosmotheme' ) , 'classes' => 'mis-hint' , 'hclass' => 'hidden' );
    $form['slideshow']['box']['title']		= array(
        'type' => 'st--m-text' ,
        'label' =>  __( 'Slide title' , 'cosmotheme' ) ,
        'hint' => __( 'If not completed will use post title' , 'cosmotheme'  ) ,
        'classes' => 'mis-hint image-slide' ,
        'hclass' => 'hidden'
    );

    $form[ 'slideshow' ][ 'box' ][ 'title_color' ]= array(
        'type' => 'st--m-color-picker',
        'label' => __( 'Title text color' , 'cosmotheme' ),
        'set' => 'slider-title_color',
    );

    $form[ 'slideshow' ][ 'box' ][ 'first_line' ] = array(
        'type' => 'st--m-text',
        'label' => __( 'First line', 'cosmotheme' ),
        'classes' => 'text-slide hidden',
        'hclass' => 'hidden'
    );

    $form[ 'slideshow' ][ 'box' ][ 'second_line' ] = array(
        'type' => 'st--m-text',
        'label' => __( 'Second line', 'cosmotheme' ),
        'classes' => 'text-slide hidden',
        'hclass' => 'hidden'
    );

    $form[ 'slideshow' ][ 'box' ][ 'description' ] = array(
        'type' => 'st--m-textarea',
        'label' => __( 'Description' , 'cosmotheme' ),
        'id' => 'slider_caption',
        'classes' => 'slider_resources image-slide' ,
    );

    $form[ 'slideshow' ][ 'box' ][ 'description_color' ] = array(
        'type' => 'st--m-color-picker',
        'label' => __( 'Description text color' , 'cosmotheme' ),
        'set' => 'slider-description_color',
        'classes' => 'slider_resources image-slide' ,
    );

    $form['slideshow']['box']['description_position']   = array(
        'type' => 'st--m-select' ,
        'label' => __( 'Description position' , 'cosmotheme') ,
        'value' =>  $position ,
        'classes' => 'slider_resources image-slide' ,
        'hint' => __( 'Choose side of caption position' , 'cosmotheme' )
    );

    $form[ 'slideshow' ][ 'box' ][ 'background_position' ] = array(
        'type' => 'st--m-select',
        'label' => __( 'Image background position' , 'cosmotheme' ),
        'value' => array(
            'left' => __( 'Left' , 'cosmotheme' ),
            'center' => __( 'Center' , 'cosmotheme' ),
            'right' => __( 'Right' , 'cosmotheme' )
        ),
        'id' => 'slider_background-image-position'
    );

    $form[ 'slideshow' ][ 'box' ][ 'background_repeat' ] = array(
        'type' => 'st--m-select',
        'label' => __( 'Image background repeat' , 'cosmotheme' ),
        'value' => array(
            'no-repeat' => __( 'No Repeat' , 'cosmotheme' ),
            'repeat' => __( 'Tile' , 'cosmotheme' ),
            'repeat-x' => __( 'Tile Horizontally' , 'cosmotheme' ),
            'repeat-y' => __( 'Tile Vertically' , 'cosmotheme' )
        ),
        'id' => 'slider_background-image-repeat'
    );

    $form[ 'slideshow' ][ 'box' ][ 'background_attachment' ] = array(
        'type' => 'st--m-select',
        'label' => __( 'Image background attachment type' , 'cosmotheme' ),
        'value' => array(
            'scroll' => __( 'Scroll' , 'cosmotheme' ),
            'fixed' => __( 'Fixed' , 'cosmotheme' )
        ),
        'id' => 'slider_background-image-attachment-type'
    );

    $form[ 'slideshow' ][ 'box' ][ 'slider_background_color' ] = array(
        'type' => 'st--m-color-picker',
        'label' => __( 'Slider background color' , 'cosmotheme' ),
        'set' => 'slider_background_color',
    );

    $form['slideshow']['box']['url']		= array( 'type' => 'st--m-text' , 'label' =>  __( 'Custom URL' , 'cosmotheme' ) , 'hint' => __( 'If not completed then Title will link to the selected post' , 'cosmotheme'  ) , 'classes' => 'mis-hint' , 'hclass' => 'hidden' );
    $form['slideshow']['box']['submit']     = array( 'type' => 'st--meta-save' ,  'value' => __( 'Add to slideshow' ,'cosmotheme' ) , 'selector' => 'div#slideshow_box div.inside div#box_slideshow_box'  );

    $form[ 'slideshow' ][ 'slidesettings' ]      = array(
        'height' => array(
            'type' => 'st--digit',
            'label' => __( 'Slideshow height' ,  'cosmotheme'  ),
            'hint' => __( 'In pixels. Make sure to re-upload images if you change the default value.' ,  'cosmotheme'  ),
            'cvalue' => options::get_value( 'slider' , 'height' )
        ),

        'animation' => array(
            'type' => 'st--select',
            'label' => __( 'Slideshow animation type' ,   'cosmotheme' ),
            'value' => array(
                'fade' => __( 'Fade' ,   'cosmotheme' ),
                'horizontal-slide' => __( 'Horizontal slide' ,   'cosmotheme'  ),
                'vertical-slide' => __( 'Vertical slide' ,   'cosmotheme'  ),
                'horizontal-push' => __( 'Horizontal push' ,   'cosmotheme'  )
            ),
            'cvalue' => options::get_value( 'slider' , 'animation' )
        ),

        'animationSpeed' => array(
            'type' => 'st--digit',
            'label' => __( 'Slideshow play speed' ,   'cosmotheme'  ),
            'hint' => __( 'In milliseconds' ,   'cosmotheme'  ),
            'cvalue' => options::get_value( 'slider' , 'animationSpeed' )
        ),

        'timer' => array(
            'classes' => 'timer',
            'type' => 'st--logic-radio',
            'label' => __( 'Use timer' ,   'cosmotheme'  ),
            'action' => "act.sh.multilevel.check( { 'input.slidesettings-timer' : '.timer-options' , 'input.slidesettings-pauseOnHover' : '.pause-on-hover-options' , 'input.slidesettings-startClockOnMouseOut' : '.start-on-mouseout-options' } );",
            'cvalue' => options::get_value( 'slider' , 'timer' )
        ),

        'advanceSpeed' => array(
            'type' => 'st--digit',
            'label' => __( 'Timer timeout' ,   'cosmotheme'  ),
            'hint' => __( 'In milliseconds' ,   'cosmotheme'  ),
            'classes' => 'timer-options',
            'cvalue' => options::get_value( 'slider' , 'advanceSpeed' )
        ),
        
        'pauseOnHover' => array(
            'type' => 'st--logic-radio',
            'label' => __( 'Pause on hover' ,   'cosmotheme'  ),
            'hint' => __( 'If you hover pauses the slider' ,   'cosmotheme'  ),
            'classes' => 'pauseOnHover timer-options',
            'action' => "act.sh.multilevel.check( { 'input.slidesettings-pauseOnHover' : '.pause-on-hover-options' , 'input.slidesettings-startClockOnMouseOut' : '.start-on-mouseout-options' } );",
            'cvalue' => options::get_value( 'slider' , 'pauseOnHover' )
        ),

        'startClockOnMouseOut' => array(
            'type' => 'st--logic-radio',
            'label' => __( 'Start clock on mouse out' ,   'cosmotheme'  ),
            'hint' => __( 'If clock should start on mouse out' ,   'cosmotheme'  ),
            'classes' => 'startClockOnMouseOut timer-options pause-on-hover-options',
            'action' => "act.check( this , { 'yes' : '.start-on-mouseout-options', 'no' : '' } , 'sh' );",
            'cvalue' => options::get_value( 'slider' , 'startClockOnMouseOut' )
        ),

        'startClockOnMouseOutAfter' => array(
            'type' => 'st--digit',
            'label' => __( 'How long after MouseOut should the timer start again' ,   'cosmotheme'  ),
            'hint' => __( 'In milliseconds' ,   'cosmotheme'  ),
            'classes' => 'timer-options pause-on-hover-options start-on-mouseout-options',
            'cvalue' => options::get_value( 'slider' , 'startClockOnMouseOutAfter' )
        ),
        
        'directionalNav' => array(
            'type' => 'st--logic-radio',
            'label' => __( 'Show manual navigation' ,   'cosmotheme'  ),
            'hint' => __( 'Manual advancing directional navs' ,   'cosmotheme'  ),
            'cvalue' => options::get_value( 'slider' , 'directionalNav' )
        )
    );

    if( isset( $_GET[ 'post' ] ) ){
        $post = get_post( $_GET[ 'post' ] );

        if( !meta::logic( $post , 'slidesettings' , 'timer' ) ){
            $form[ 'slideshow' ][ 'slidesettings' ][ 'advanceSpeed' ][ 'classes' ] .= ' hidden';
        }

        if( !meta::logic( $post , 'slidesettings' , 'timer' ) ){
            $form[ 'slideshow' ][ 'slidesettings' ][ 'pauseOnHover' ][ 'classes' ] .= ' hidden';
        }

        if( !( meta::logic( $post , 'slidesettings' , 'timer' ) && meta::logic( $post , 'slidesettings' , 'pauseOnHover' ) ) ){
            $form[ 'slideshow' ][ 'slidesettings' ][ 'startClockOnMouseOut' ][ 'classes' ] .= ' hidden';
        }

        if( !( meta::logic( $post , 'slidesettings' , 'timer' ) && meta::logic( $post , 'slidesettings' , 'pauseOnHover' ) && meta::logic( $post , 'slidesettings' , 'startClockOnMouseOut' ) ) ){
            $form[ 'slideshow' ][ 'slidesettings' ][ 'startClockOnMouseOutAfter' ][ 'classes' ] .= ' hidden';
        }
    }

    $box['slideshow']['box']                = array( __('Compose slideshow (drag and drop items to rearange position)' , 'cosmotheme' ) , 'normal' , 'low' , 'content' => $form['slideshow']['box'] , 'box' => 'box' , 'struct' => $struct['slideshow']['box'] , 'callback' => array( 'get_meta_records' , array( 'slideshow' , 'box' , 'box' ) ) , 'records-title' => __('Slideshow items' , 'cosmotheme' ) );
    $box[ 'slideshow' ][ 'slidesettings' ]       = array( __( 'Slideshow Settings' , 'cosmotheme' ) , 'normal' , 'low' , 'content' => $form[ 'slideshow' ][ 'slidesettings' ] , 'box' => 'slidesettings' , 'update' => true  );
    $form['slideshow']['manager']['link']   = array( 'type' => 'sh--post-upload' , 'title' => __( 'Manage Slideshow' , 'cosmotheme' ) );

    $box['slideshow']['manager']            = array( __('Manage Slideshow' , 'cosmotheme' ) , 'side' , 'low' , 'content' => $form['slideshow']['manager'] , 'box' => 'manager' );


    resources::$labels['slideshow']         = $res['slideshow']['labels'];
    resources::$type['slideshow']           = $res['slideshow']['args'];
    resources::$box['slideshow']            = $box['slideshow'];


    /* post type testimonials */
    $res['testimonial']['labels'] = array(
        'name' => _x(__('Testimonials','cosmotheme'), 'post type general name'),
        'singular_name' => _x(__('Testimonial','cosmotheme'), 'post type singular name'),
        'add_new' => _x('Add New', __('Testimonial','cosmotheme')),
        'add_new_item' => __('Add New Testimonial','cosmotheme'),
        'edit_item' => __('Edit Testimonial','cosmotheme'),
        'new_item' => __('New Testimonial','cosmotheme'),
        'view_item' => __('View Testimonial','cosmotheme'),
        'search_items' => __('Search Testimonial','cosmotheme'),
        'not_found' =>  __('Nothing found','cosmotheme'),
        'not_found_in_trash' => __('Nothing found in Trash','cosmotheme')
    );
    $res['testimonial']['args'] = array(
        'menu_icon' => get_template_directory_uri() . '/lib/images/custom.testimonial.png',
        'public' => true,
        'hierarchical' => false,
        'menu_position' => 7,
        'supports' => array('title','editor','thumbnail'),
        '__on_front_page' => true
    );

    /* box for testimonial */
    $form['testimonial']['info']['name']                = array( 'type' => 'st--text' , 'label' => '<strong>' . __( 'Author name' , 'cosmotheme') . '</strong>' );
    $form['testimonial']['info']['title']               = array( 'type' => 'st--text' , 'label' => '<strong>' . __( 'Author title' , 'cosmotheme') . '</strong>' );
    $box['testimonial']['info']                         = array( __('Add testimoniall additional info' , 'cosmotheme' ) , 'normal' , 'high' , 'content' => $form['testimonial']['info'] , 'box' => 'info', 'update' => true );
    $box['testimonial']['shcode']                  = array( __('Shortcodes' , 'cosmotheme' ) , 'normal' , 'high'  , 'box' => 'shcode' , 'includes' => 'shcode/main.php' );


    resources::$labels['testimonial']         = $res['testimonial']['labels'];
    resources::$type['testimonial']           = $res['testimonial']['args'];
    resources::$box['testimonial']            = $box['testimonial'];



    /* standard post */
    $form['post']['layout']['type']         = array( 'type' => 'sh--select' , 'label' =>  __( 'Select layout type' , 'cosmotheme' ) , 'value' => array( 'right' => __( 'Right Sidebar'  , 'cosmotheme' ) , 'left' => __( 'Left Sidebar' , 'cosmotheme' ) , 'full' => __( 'Full Width' , 'cosmotheme' )  ) , 'action' => "act.select( '#post_layout' , { 'full' : '.sidebar_list' } , 'hs_');" , 'id' => 'post_layout' , 'ivalue' =>  options::get_value( 'layout' , 'single' ) );
    $form['post']['layout']['sidebar']      = array( 'type' => 'sh--select' , 'label' =>  __( 'Select sidebar' , 'cosmotheme' ) , 'value' => $sidebar_value , 'classes' => $classes );
    $form['post']['layout']['link']         = array( 'type' => 'sh--link' , 'url' => 'admin.php?page=cosmothemes___sidebar' , 'title' => __( 'Add new Sidebar' , 'cosmotheme' ) );

    if( options::get_value( 'layout' , 'single' ) == 'full' ){
        $form['post']['layout']['sidebar']['classes'] = $classes . ' hidden';
        $form['post']['layout']['link']['classes'] = $classes .' hidden';
    }

    $sliders = get__posts( array( 'post_status' => 'publish' , 'post_type' => 'slideshow' ) , '' );
    if( count( $sliders ) > 0 ){
        $form['post']['settings']['slideshow']  = array( 'type' => 'st--logic-radio' , 'label' => __( 'Display slideshow' , 'cosmotheme' ) , 'hint' => __( 'Show slideshow on this post' , 'cosmotheme' ) , 'cvalue' => 'no' , 'action' => "act.check( this , { 'yes' : '.list_slideshow'  } , 'sh');" );
        $form['post']['settings']['slideshow_select'] = array('type' => 'st--search' , 'label' => __( 'Select slideshow' , 'cosmotheme' ) , 'query' => array( 'post_type' => 'slideshow' , 'post_status' => 'publish' ) , 'hint' => __( 'Start typing the Slideshow title. ' , 'cosmotheme' ) , 'action' => "act.search( this , '.sl_settings')" );
        if( isset( $_GET['post'] ) &&  meta::logic( get_post( $_GET['post']  ) , 'settings' , 'slideshow' ) ){
            $form['post']['settings']['slideshow_select']['classes'] = 'list_slideshow';
        }else{
            $form['post']['settings']['slideshow_select']['classes'] = 'hidden list_slideshow';
        }
    }else{
        $form['post']['settings']['link']        = array( 'type' => 'sh--link' , 'url' => 'post-new.php?post_type=slideshow' , 'title' => __( 'Add New Slideshow' , 'cosmotheme') );
    }

    $form['post']['settings']['safe']       = array( 'type' => 'st--logic-radio' , 'label' => __( 'Not safe' , 'cosmotheme' ) , 'cvalue' => 'no' );
    $form['post']['settings']['related']    = array( 'type' => 'st--logic-radio' , 'label' => __( 'Show related posts' , 'cosmotheme' ) , 'hint' => __( 'Show related posts on this post' , 'cosmotheme' ) , 'cvalue' => options::get_value(  'blog_post' , 'show_similar' ) );
    $form['post']['settings']['meta']       = array( 'type' => 'st--logic-radio' , 'label' => __( 'Show post meta' , 'cosmotheme' ) , 'hint' => __( 'Show post meta on this post' , 'cosmotheme' ) , 'cvalue' => options::get_value(  'general' , 'meta' ), 'action' => "act.check( this , { 'yes' : '.meta_view'  } , 'sh');" );
	$meta_view_type = array('horizontal' => __('Horizontal','cosmotheme'), 'vertical' => __('Vertical','cosmotheme') );  

//var_dump(meta::get_meta( $_GET['post'] , 'settings' ));

	$form['post']['settings']['love']       = array( 'type' => 'st--logic-radio' , 'label' => __( 'Show post like' , 'cosmotheme' ) , 'hint' => __( 'Show post like on this post' , 'cosmotheme' )  , 'cvalue' => options::get_value(  'general' , 'enb_likes' ) );
    $form['post']['settings']['sharing']    = array( 'type' => 'st--logic-radio' , 'label' => __( 'Show social sharing' , 'cosmotheme' ) , 'hint' => __( 'Show social sharing on this post'  , 'cosmotheme' ) , 'cvalue' => options::get_value( 'blog_post' , 'post_sharing' ) );
    $form['post']['settings']['author']     = array( 'type' => 'st--logic-radio' , 'label' => __( 'Show author box' , 'cosmotheme' ) , 'hint' => __( 'Show author box on this post'  , 'cosmotheme' ) , 'cvalue' => options::get_value( 'blog_post' , 'post_author_box' ) );
    $form['post']['settings']['show_feat_on_archive'] = array( 'type' => 'st--logic-radio' , 'label' => __( 'Display featured image on archive pages' , 'cosmotheme' ) ,  'cvalue' => options::get_value( 'blog_post' , 'show_feat_on_archive' ) );
	$form['post']['settings']['enb_sldsh_attached_img'] = array( 'type' => 'st--logic-radio' , 'label' => __( 'Enable slideshow for attached images' , 'cosmotheme' ) , 'hint' => __( 'If enabled it will show up insted of featured image.' , 'cosmotheme' )  ,  'cvalue' => 'no' );
    
    $form['post']['settings']['post_bg']    = array( 'type' => 'st--upload' , 'label' => __( 'Upload or choose image from media library' , 'cosmotheme') , 'id' => 'post_background' , 'hint' => __( 'This will be the background image for this post' , 'cosmotheme' ) );
    $form['post']['settings']['position']   = array( 'type' => 'st--select' , 'label' => __( 'Image background position' , 'cosmotheme' ) , 'value' => array( 'left' => __( 'Left' , 'cosmotheme' ) , 'center' => __( 'Center' , 'cosmotheme' ) , 'right' => __( 'Right' , 'cosmotheme' ) ) );
    $form['post']['settings']['repeat']     = array( 'type' => 'st--select' , 'label' => __( 'Image background repeat' , 'cosmotheme' ) , 'value' => array( 'no-repeat' => __( 'No Repeat' , 'cosmotheme' ) , 'repeat' => __( 'Tile' , 'cosmotheme' ) , 'repeat-x' => __( 'Tile Horizontally' , 'cosmotheme' ) , 'repeat-y' => __( 'Tile Vertically' , 'cosmotheme' ) ) );
    $form['post']['settings']['attachment'] = array( 'type' => 'st--select' , 'label' => __( 'Image background attachment type' , 'cosmotheme' ) , 'value' => array( 'scroll' => __( 'Scroll' , 'cosmotheme' ) , 'fixed' => __( 'Fixed' , 'cosmotheme' ) ) );
    $form['post']['settings']['color']      = array( 'type' => 'st--color-picker' , 'label' => __( 'Set background color for this post' , 'cosmotheme' ) );

    if( isset( $_GET['post'] ) ){
        $post_format = get_post_format( $_GET['post'] );
    }else{
        $post_format = 'standard';
    }
    
    $form['post']['format']['type']         = array( 'type' => 'st--select' , 'label' => __( 'Select post format' , 'cosmotheme' ) , 'value' => array(  'standard' => __( 'Standard' , 'cosmotheme' ) , 'video' => __( 'Video' , 'cosmotheme' ) , 'image' => __( 'Image' , 'cosmotheme' ) , 'audio' => __( 'Audio' , 'cosmotheme' )  , 'link' => __( 'Attachment' , 'cosmotheme' ) )  , 'action' => "act.select( '.post_format_type' , { 'video' : '.post_format_video' , 'image' : '.post_format_image' , 'audio' : '.post_format_audio' , 'link' : '.post_format_link' } , 'sh_' );" , 'iclasses' => 'post_format_type' , 'ivalue' =>  $post_format );

    if( isset( $_GET['post'] ) && get_post_format( $_GET['post'] ) == 'video' ){
		$form['post']['format']['video']=array('type'=>'ni--form-upload', 'format'=>'video', 'classes'=>"post_format_video", 'post_id'=>$_GET['post']);
//         $form['post']['format']['video']        = array( 'type' => 'st--text' , 'label' => __( 'Set video URL ( YouTube , Vimeo or self hosted )' , 'cosmotheme' ) , 'hint' => __( 'If a valid YouTube, Vimeo or self hosted URL is provided, the embeded video <br /> for the provided link will be appended at the beginning of the post <br /> content. Otherwise it will be ignored.' , 'cosmotheme' ) , 'classes' => 'post_format_video' );
    }else{
		$form['post']['format']['video']=array('type'=>'ni--form-upload', 'format'=>'video', 'classes'=>"hidden post_format_video");
//         $form['post']['format']['video']        = array( 'type' => 'st--text' , 'label' => __( 'Set video URL ( YouTube , Vimeo )' , 'cosmotheme' ) , 'hint' => __( 'If a valid YouTube or Vimeo URL is provided, the embeded video <br /> for the provided link will be appended at the beginning of the post <br /> content. Otherwise it will be ignored.' , 'cosmotheme' ) , 'classes' => 'post_format_video hidden' );
    }

	
	$form['post']['format']['init']=array('type'=>"no--form-upload-init");

    if( isset( $_GET['post'] ) && get_post_format( $_GET['post'] ) == 'image' ){
		$form['post']['format']['image']=array('type'=>'ni--form-upload', 'format'=>'image', 'classes'=>"post_format_image", 'post_id'=>$_GET['post']);
//         $form['post']['format']['image']        = array( 'type' => 'st--hint' , 'label' => '' , 'value' => __( 'Please set featured image'  , 'cosmotheme' )  , 'classes' => 'post_format_image' );
    }else{
		$form['post']['format']['image']=array('type'=>'ni--form-upload', 'format'=>'image', 'classes'=>"post_format_image hidden");
//         $form['post']['format']['image']        = array( 'type' => 'st--hint' , 'label' => '' , 'value' => __( 'Please set featured image'  , 'cosmotheme' )  , 'classes' => 'post_format_image hidden' );
    }

    if( isset( $_GET['post'] ) && get_post_format( $_GET['post'] ) == 'audio' ){
		$form['post']['format']['audio']=array('type'=>'ni--form-upload', 'format'=>'audio', 'classes'=>"post_format_audio", 'post_id'=>$_GET['post']);
//         $form['post']['format']['audio']        = array( 'type' => 'st--upload' , 'label' => __( 'Please add audio file or URL'  , 'cosmotheme' )  , 'classes' => 'post_format_audio' , 'id' => 'format_audio' , 'hint' => __( 'Please use  only MP3 files' , 'cosmotheme' ) );
    }else{
		$form['post']['format']['audio']=array('type'=>'ni--form-upload', 'format'=>'audio', 'classes'=>"post_format_audio hidden");
//         $form['post']['format']['audio']        = array( 'type' => 'st--upload' , 'label' => __( 'Please add audio file or URL'  , 'cosmotheme' )  , 'classes' => 'post_format_audio hidden' , 'id' => 'format_audio' , 'hint' => __( 'Please use  only MP3 files' , 'cosmotheme' ) );
    }
    
    if( isset( $_GET['post'] ) && get_post_format( $_GET['post'] ) == 'link' ){
		$form['post']['format']['link']=array('type'=>'ni--form-upload', 'format'=>'links', 'classes'=>"post_format_link", 'post_id'=>$_GET['post']);
//         $form['post']['format']['link']        = array( 'type' => 'st--upload-id' , 'label' => __( 'Please add attachment file or URL'  , 'cosmotheme' )  , 'classes' => 'post_format_link' , 'id' => 'format_link' , 'hint' => __( 'Please use only .ZIP, .RAR, .DOC, .DOCX, .PDF files' , 'cosmotheme' ) );
    }else{
		$form['post']['format']['link']=array('type'=>'ni--form-upload', 'format'=>'link', 'classes'=>"post_format_link hidden");
//         $form['post']['format']['link']        = array( 'type' => 'st--upload-id' , 'label' => __( 'Please add attachment file or URL'  , 'cosmotheme' )  , 'classes' => 'post_format_link hidden' , 'id' => 'format_link' , 'hint' => __( 'Please use only .ZIP, .RAR, .DOC, .DOCX, .PDF files' , 'cosmotheme' ) );
    }

    $form['post']['source']['post_source']   = array( 'type' => 'st--text' , 'label' => __( 'Source' , 'cosmotheme' ) , 'hint' => __( 'Example: http://cosmothemes.com' , 'cosmotheme' ) );
    $form['post']['source']['post_client']   = array( 'type' => 'st--text' , 'label' => __( 'Client' , 'cosmotheme' ) , 'hint' => __( 'Example: John Doe' , 'cosmotheme' ) );
    $form['post']['source']['post_services'] = array( 'type' => 'st--text' , 'label' => __( 'Services' , 'cosmotheme' ) , 'hint' => __( 'Example: Graphic design, Print' , 'cosmotheme' ) );
    

    $box['post']['shcode']                  = array( __('Shortcodes' , 'cosmotheme' ) , 'normal' , 'high'  , 'box' => 'shcode' , 'includes' => 'shcode/main.php' );
    $box['post']['layout']                  = array( __('Layout and Sidebars' , 'cosmotheme' ) , 'side' , 'low' , 'content' => $form['post']['layout'] , 'box' => 'layout' , 'update' => true  );
    $box['post']['settings']                = array( __('Post Settings' , 'cosmotheme' ) , 'normal' , 'high' , 'content' => $form['post']['settings'] , 'box' => 'settings' , 'update' => true  );
    $box['post']['format']                  = array( __('Post Format' , 'cosmotheme' ) , 'normal' , 'high' , 'content' => $form['post']['format'] , 'box' => 'format' , 'update' => true );
    
    if(isset($_GET['post'])){
        $the_source = post::get_source($_GET['post']); //deb::e($the_source);
    }
    if( options::logic( 'blog_post' , 'show_source' ) || (isset($_GET['post']) && strlen($the_source) )  ){ /*show source box only if it is globally enabled, or it has value*/
        //$box['post']['source']   = array( __('Post Source' , 'cosmotheme' ) , 'normal' , 'high' , 'content' => $form['post']['source'] , 'box' => 'source' , 'update' => true );
        $box['post']['source']   = array( __('Portfolio details' , 'cosmotheme' ) , 'normal' , 'high' , 'content' => $form['post']['source'] , 'box' => 'source' , 'update' => true );
    }
    

    resources::$type['post']                = array();
    resources::$box['post']                 = $box['post'];
    

    $form[ 'page' ][ 'settings' ][ 'love' ] = array( 
        'type' => 'st--logic-radio' , 
        'label' => __( 'Show post like' , 'cosmotheme' ) , 
        'hint' => __( 'Show post like on this post' , 'cosmotheme' )  ,
        'cvalue' => options::get_value(  'general' , 'enb_likes' )
    );
    $form['page']['layout']['type']         = array( 'type' => 'sh--select' , 'label' =>  __( 'Select layout type' , 'cosmotheme' ) , 'value' => array( 'right' => __( 'Right Sidebar'  , 'cosmotheme' ) , 'left' => __( 'Left Sidebar' , 'cosmotheme' ) , 'full' => __( 'Full Width' , 'cosmotheme' )  ) , 'action' => "act.select( '#post_layout' , { 'full' : '.sidebar_list' } , 'hs_');" , 'id' => 'post_layout' , 'ivalue' =>  options::get_value( 'layout' , 'page' ) );
    $form['page']['layout']['sidebar']      = array( 'type' => 'sh--select' , 'label' =>  __( 'Select sidebar' , 'cosmotheme' ) , 'value' => $sidebar_value , 'classes' => $classes );
    $form['page']['layout']['link']         = array( 'type' => 'sh--link' , 'url' => 'admin.php?page=cosmothemes___sidebar' , 'title' => __( 'Add new Sidebar' , 'cosmotheme' ) );

    if( options::get_value( 'layout' , 'page' ) == 'full' ){
        $form['page']['layout']['sidebar']['classes'] = $classes . ' hidden';
        $form['page']['layout']['link']['classes'] = $classes .' hidden';
    }
    
    $sliders = get__posts( array( 'post_status' => 'publish' , 'post_type' => 'slideshow' ) , '' );
    if( count( $sliders ) > 0 ){
        $form['page']['settings']['slideshow']  = array( 'type' => 'st--logic-radio' , 'label' => __( 'Display slideshow' , 'cosmotheme' ) , 'hint' => __( 'Show slideshow on this post' , 'cosmotheme' ) , 'cvalue' => 'no' , 'action' => "act.check( this , { 'yes' : '.list_slideshow'  } , 'sh');" );
        $form['page']['settings']['slideshow_select'] = array('type' => 'st--search' , 'label' => __( 'Select slideshow' , 'cosmotheme' ) , 'query' => array( 'post_type' => 'slideshow' , 'post_status' => 'publish' ) , 'hint' => __( 'Type title ' , 'cosmotheme' ) , 'action' => "act.search( this , '.sl_settings')" );
        if( isset( $_GET['post'] ) &&  meta::logic( get_post( $_GET['post']  ) , 'settings' , 'slideshow' ) ){
            $form['page']['settings']['slideshow_select']['classes'] = 'list_slideshow';
        }else{
            $form['page']['settings']['slideshow_select']['classes'] = 'hidden list_slideshow';
        }
    }else{
        $form['page']['settings']['link']        = array( 'type' => 'sh--link' , 'url' => 'post-new.php?post_type=slideshow' , 'title' => __( 'Add New Slideshow' , 'cosmotheme') );
    }
    
    $form['page']['settings']['meta']       = array( 'type' => 'st--logic-radio' , 'label' => __( 'Show page meta' , 'cosmotheme' ) , 'hint' => 'Show post meta on this page' , 'cvalue' => 'no' );
    $form['page']['settings']['sharing']    = array( 'type' => 'st--logic-radio' , 'label' => __( 'Show social sharing' , 'cosmotheme' ) , 'hint' => 'Show social sharing on this page' , 'cvalue' => options::get_value( 'blog_post' , 'page_sharing' ) );
    $form['page']['settings']['author']     = array( 'type' => 'st--logic-radio' , 'label' => __( 'Show author box' , 'cosmotheme' ) , 'hint' => 'Show author box on this page' , 'cvalue' => options::get_value( 'blog_post' , 'page_author_box' ) );
    $form['page']['settings']['post_bg']    = array( 'type' => 'st--upload' , 'label' => __( 'Upload image, or choose from media library.' , 'cosmotheme') , 'id' => 'post_background' , 'hint' => __( 'This will be the background image for this page' , 'cosmotheme' ) );
    $form['page']['settings']['position']   = array( 'type' => 'st--select' , 'label' => __( 'Background position' , 'cosmotheme' ) , 'value' => array( 'left' => __( 'Left' , 'cosmotheme' ) , 'center' => __( 'Center' , 'cosmotheme' ) , 'right' => __( 'Right' , 'cosmotheme' ) ) );
    $form['page']['settings']['repeat']     = array( 'type' => 'st--select' , 'label' => __( 'Background repeat' , 'cosmotheme' ) , 'value' => array( 'no-repeat' => __( 'No Repeat' , 'cosmotheme' ) , 'repeat' => __( 'Tile' , 'cosmotheme' ) , 'repeat-x' => __( 'Tile Horizontally' , 'cosmotheme' ) , 'repeat-y' => __( 'Tile Vertically' , 'cosmotheme' ) ) );
    $form['page']['settings']['attachment'] = array( 'type' => 'st--select' , 'label' => __( 'Background attachment type' , 'cosmotheme' ) , 'value' => array( 'scroll' => __( 'Scroll' , 'cosmotheme' ) , 'fixed' => __( 'Fixed' , 'cosmotheme' ) ) );
    $form['page']['settings']['color']      = array( 'type' => 'st--color-picker' , 'label' => __( 'Set background color for this post' , 'cosmotheme' ) );

    $box['page']['shcode']                  = array( __('Shortcodes' , 'cosmotheme' ) , 'normal' , 'high'  , 'box' => 'shcode' , 'includes' => 'shcode/main.php' );
    $box['page']['layout']                  = array( __('Layout and Sidebars' , 'cosmotheme' ) , 'side' , 'low' , 'content' => $form['page']['layout'] , 'box' => 'layout' , 'update' => true  );
    $box['page']['settings']                = array( __('Page Settings' , 'cosmotheme' ) , 'normal' , 'high' , 'content' => $form['page']['settings'] , 'box' => 'settings' , 'update' => true  );
    
    
    resources::$type['page']                = array();
    resources::$box['page']                 = $box['page'];


?>
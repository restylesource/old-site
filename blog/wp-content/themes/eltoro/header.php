<?php 
    $fb_id = options::get_value( 'social' , 'facebook' );
    if( strlen( trim( $fb_id ) ) ){
        $fb['likes'] = social::pbd_get_transient($name = 'facebook',$user_id=$fb_id,$cacheTime = 120); /*cache - in minutes*/
        $fb['link'] = 'http://facebook.com/people/@/'  . $fb_id ;
    }
?>  
<!DOCTYPE html>

<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?> xmlns:fb="http://ogp.me/ns/fb#"><!--<![endif]-->
<head>
    <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
    <meta name="robots"  content="index, follow" />
    
    <?php if( is_single() || is_page() ){ ?>
    <meta name="description" content="<?php echo strip_tags(post::get_excerpt($post, $ln=150)); ?>" /> 
    <?php }else{ ?>
    <meta name="description" content="<?php echo get_bloginfo('description'); ?>" /> 
    <?php } ?>

    <?php if( is_single() || is_page() ){ ?>
        <meta property="og:title" content="<?php the_title() ?>" />
        <meta property="og:site_name" content="<?php echo get_bloginfo('name'); ?>" />
        <meta property="og:url" content="<?php the_permalink() ?>" />
        <meta property="og:type" content="article" />
        <meta property="og:locale" content="en_US" /> 
        <meta property="og:description" content="<?php echo get_bloginfo('description'); ?>"/>
        <?php 
            if(options::get_value( 'social' , 'facebook_app_id' ) != ''){
                ?><meta property='fb:app_id' content='<?php echo options::get_value( 'social' , 'facebook_app_id' ); ?>'><?php
            }
            
            global $post;
            $src  = wp_get_attachment_image_src( get_post_thumbnail_id( $post -> ID ) , 'thumbnail' );
            echo '<meta property="og:image" content="'.$src[0].'"/>'; 
            echo ' <link rel="image_src" href="'.$src[0].'" / >';           
            wp_reset_query();   
        }else{ ?>
            <meta property="og:title" content="<?php echo get_bloginfo('name'); ?>"/>
            <meta property="og:site_name" content="<?php echo get_bloginfo('name'); ?>"/>
            <meta property="og:url" content="<?php echo home_url() ?>/"/>
            <meta property="og:type" content="blog"/>
            <meta property="og:locale" content="en_US"/>
            <meta property="og:description" content="<?php echo get_bloginfo('description'); ?>"/>
            <meta property="og:image" content="<?php echo get_template_directory_uri()?>/fb_screenshot.png"/> 
    <?php
        }
    ?>

    <title><?php bloginfo('name'); ?> &raquo; <?php bloginfo('description'); ?><?php if ( is_single() ) { ?><?php } ?><?php wp_title(); ?></title>

    <!-- Set the viewport width to device width for mobile -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0" />

    <?php
        if( strlen( options::get_value( 'styling' , 'favicon' ) ) ){
            $path_parts = pathinfo( options::get_value( 'styling' , 'favicon' ) );
            if( $path_parts['extension'] == 'ico' ){
    ?>
                <link rel="shortcut icon" href="<?php echo options::get_value( 'styling' , 'favicon' ); ?>" />
    <?php
            }else{
    ?>
                <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico" />
    <?php
            }
        }else{
    ?>
            <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico" />
    <?php
        }
    ?>

    <link rel="profile" href="http://gmpg.org/xfn/11" />

    <!-- ststylesheet -->
    <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="all" />
    <link href='http://fonts.googleapis.com/css?family=PT+Sans+Narrow:400' rel='stylesheet' type='text/css' />
    <link href='http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz:400,200,300,700&subset=latin,latin-ext' rel='stylesheet' type='text/css'>


    <?php if( options::get_value( 'styling' , 'logo_type' ) == 'text' ) { ?>
        <link href='http://fonts.googleapis.com/css?family=<?php  echo str_replace(' ' , '+' , trim( options::get_value( 'styling' , 'logo_font_family' ) ) );?>' rel='stylesheet' type='text/css' />
    <?php } ?>


    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/lib/css/shortcode.css" />

		<?php if ( options::logic( 'styling' , 'front_end' ) ){ ?>  
		<link rel="stylesheet" type="text/css" href="<?php echo home_url()?>/wp-admin/css/farbtastic.css" />
		<?php } ?>

    <?php if ( options::logic( 'styling' , 'front_end' ) ){ ?>  
    <link rel="stylesheet" type="text/css" href="<?php echo home_url()?>/wp-admin/css/farbtastic.css" />
    <?php } ?>

    <!--[if lt IE 9]>
        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/autoinclude/ie.css">
    <![endif]-->

    <!-- IE Fix for HTML5 Tags -->
    <!--[if lt IE 9]>
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->    
    
    <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="chrome=1">
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/chrome-frame/1/CFInstall.min.js"></script>
        <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/jquery.chrome_frame.js"></script>
        <style>
            #chrome_msg { display:none; z-index: 999; position: fixed; top: 0; left: 0; background: #ece475; border: 2px solid #666; border-top: none; font: bold 11px Verdana, Geneva, Arial, Helvetica, sans-serif; line-height: 100%; width: 100%; text-align: center; padding: 5px 0; margin: 0 auto; }
            #chrome_msg a, #chrome_msg a:link { color: #a70101; text-decoration: none; }
            #chrome_msg a:hover { color: #a70101; text-decoration: underline; }
            #chrome_msg a#msg_hide { float: right; margin-right: 15px; cursor: pointer; }
            /* IE6 positioning fix */
            * html #chrome_msg { left: auto; margin: 0 auto; border-top: 2px solid #666;  }
        </style>
    <![endif]-->  
    <?php wp_head(); ?>    

    <script type="text/javascript">

        var cookies_prefix = "<?php echo ZIP_NAME; ?>";  
        var themeurl = "<?php echo get_template_directory_uri(); ?>";
        jQuery( function(){
            jQuery( '.demo-tooltip' ).tour();
        });

    </script>

    
    <!--Custom CSS-->
    <?php if( strlen( options::get_value( 'custom_css' , 'css' ) )  > 0 ){ ?>
        <style type="text/css">
            <?php echo options::get_value( 'custom_css' , 'css' ); ?>    
        </style>

    <?php }  ?>

    <?php if( options::get_value( 'styling' , 'logo_type' ) == 'text' ) {
        $logo_font_family = explode('&',options::get_value('styling' , 'logo_font_family'));
        $logo_font_family = $logo_font_family[0];
        $logo_font_family = str_replace( '+',' ',$logo_font_family );
    ?>
        <style type="text/css">
            hgroup.logo a h3{
            font-family: '<?php echo $logo_font_family ?>', arial, serif !important;
            font-size: <?php echo options::get_value('styling' , 'logo_font_size')?>px;
            font-weight: <?php echo options::get_value('styling' , 'logo_font_weight')?>;
        }
        </style>
    <?php } ?>
</head>

<?php
    $position   = '';
    $repeat     = '';
    $bgatt      = '';
    $background_color = '';

    if( is_single() || is_page() ){
        $settings = meta::get_meta( $post -> ID , 'settings' );
        if( ( isset( $settings['post_bg'] ) && !empty( $settings['post_bg'] ) ) || ( isset( $settings['color'] ) && !empty( $settings['color'] ) ) ){
            if( isset( $settings['post_bg'] ) && !empty( $settings['post_bg'] ) ){ 
                $background_img = "background-image: url('" . $settings['post_bg'] . "');";
            }

            if( isset( $settings['color'] ) && !empty( $settings['color'] ) ){
                $background_color = "background-color: " . $settings['color'] . "; ";
            }

            if( isset( $settings['position'] ) && !empty( $settings['position'] ) ){
                $position = 'background-position: '. $settings['position'] . ';';
            }
            if( isset( $settings['repeat'] ) && !empty( $settings['repeat'] ) ){
                $repeat = 'background-repeat: '. $settings['repeat'] . ';';
            }
            if( isset( $settings['attachment'] ) && !empty( $settings['attachment'] ) ){
                $bgatt = 'background-attachment: '. $settings['attachment'] . ';';
            }
        }else{
            if(get_background_image() == '' && get_bg_image() != ''){ 
                if(get_bg_image() != 'pattern.none.png'){
                    $background_img = 'background-image: url('.get_template_directory_uri().'/lib/images/pattern/'.get_bg_image().');';
                }else{
                    $background_img = '';
                }    
                /*if day or night images are set then we will add 'background-attachment:fixed'   */
                if(strpos(get_bg_image(),'.jpg')){
                    $background_img .= ' background-attachment:fixed';
                }
            }else{
                $background_img = '';
            }
            if(get_content_bg_color() != ''){
                $background_color = "background-color: " . get_content_bg_color() . "; ";
            }
        }
    }else{
        if(get_background_image() == '' && get_bg_image() != ''){
            if(get_bg_image() != 'pattern.none.png'){
                $background_img = 'background-image: url('.get_template_directory_uri().'/lib/images/pattern/'.get_bg_image().');';
            }else{
                $background_img = '';
            }    
            /*if day or night images are set then we will add 'background-attachment:fixed'   */
            if(strpos(get_bg_image(),'.jpg')){
                $background_img .= ' background-attachment:fixed;';
            }
        }else{
            $background_img = '';
        }
        if(get_content_bg_color() != ''){
            $background_color = "background-color: " . get_content_bg_color() . "; ";
        }

        if( strlen( get_background_image() ) ){
            $background_img = '';
        }

        if( strlen( get_background_color() ) ){
            $background_color = '';
        }
    }
?>
<body <?php body_class(); ?> style="<?php echo $background_color ; ?> <?php echo $background_img ; ?>  <?php echo $position; ?> <?php echo $repeat; ?> <?php echo $bgatt; ?>">
   
           
    <script src="http://connect.facebook.net/en_US/all.js#xfbml=1" type="text/javascript" id="fb_script"></script>
    
    <?php   if( is_user_logged_in () && (isset($post -> ID) && $post -> ID != options::get_value( 'upload' , 'post_item_page' )) && options::logic( 'general' , 'sticky_bar' ) && options::logic( 'general' , 'user_login' )){ ?> 
        <div class="sticky-bar " id="sticky-bar">
            <div class="container <?php if (options::logic('styling', 'larger')) { echo 'large'; } ?>">
                <div class="left">

                    <a href="#" class="profile-pic">
                        <?php
						$u_id = get_current_user_id();
						$picture = facebook::picture();
                        if( strlen( $picture )  && get_user_meta( $u_id , 'custom_avatar' , true ) == ''){
                            ?><a href="http://facebook.com/profile.php?id=<?php echo facebook::id(); ?>" class="profile-pic"><img src="<?php echo $picture; ?>" width="32" width="32" /></a><?php
                        }else{
                            echo '<a href="' . get_author_posts_url( $u_id ) . '" class="profile-pic">'  . cosmo_avatar( $u_id , 32 , $default = DEFAULT_AVATAR_LOGIN ) . '</a>';
                        }
                        ?>
                    </a>
                    <div class="cosmo-icons">
                        <ul>
                            <li class="signin"><a href="<?php echo get_author_posts_url( $u_id ); ?>"><?php the_author_meta( 'display_name', $u_id ); ?></a></li>
                        </ul>
                    </div>
                </div>
                <div class="right">
                    <div class="cosmo-icons fr">
						<?php
							$url = home_url();
							$like = array( 'fp_type' => "like" );
							$url_like = add_query_arg( $like , $url );
						?>
                        <ul>

                            <?php if(options::logic( 'upload' , 'enb_image' ) ){    ?>
                            <li class="image show-hover hidden"><a href="<?php echo get_page_link(options::get_value( 'upload' , 'post_item_page' )); ?>#pic_upload"><?php _e('Image','cosmotheme'); ?></a></li>
                            <?php } ?> 
                            <?php if(options::logic( 'upload' , 'enb_video' ) ){    ?>
                            <li class="video show-hover hidden"> <a href="<?php echo get_page_link(options::get_value( 'upload' , 'post_item_page' )); ?>#video_upload"><?php _e('Video','cosmotheme'); ?></a></li>
                            <?php } ?> 
                            <?php if(options::logic( 'upload' , 'enb_text' ) ){ ?>
                            <li class="text show-hover hidden"> <a href="<?php echo get_page_link(options::get_value( 'upload' , 'post_item_page' )); ?>#text_post"><?php _e('Text','cosmotheme'); ?></a></li>
                            <?php } ?> 
                            <?php if(options::logic( 'upload' , 'enb_audio' ) ){    ?>
                            <li class="audio show-hover hidden"> <a href="<?php echo get_page_link(options::get_value( 'upload' , 'post_item_page' )); ?>#audio_post"><?php _e('Audio','cosmotheme'); ?></a></li>
                            <?php } ?>
                            <?php if(options::logic( 'upload' , 'enb_file' ) ){ ?>
                            <li class="attach show-hover hidden"> <a href="<?php echo get_page_link(options::get_value( 'upload' , 'post_item_page' )); ?>#file_post"><?php _e('File','cosmotheme'); ?></a></li>
                            <?php } ?> 
          
                            <?php if(is_numeric(options::get_value( 'general' , 'user_profile_page' )) && options::get_value( 'general' , 'user_profile_page' ) > 0){ ?>
                                <li class="my-settings show-first"><a href="<?php  echo get_page_link(options::get_value( 'general' , 'user_profile_page' ));  ?>"><?php _e( 'My settings' , 'cosmotheme' ); ?></a></li>
                            <?php } ?>
                            <?php
                                if( post::get_my_posts( get_current_user_id() ) ){
                            ?>
                                <li class="my-profile show-first"><a href="<?php echo get_author_posts_url( $u_id );  ?>"><?php _e( 'My profile' , 'cosmotheme' ); ?></a></li>
                            <?php } ?>
                            <?php
                                if( post::get_my_posts( get_current_user_id() ) && (int)options::get_value( 'general' , 'my_posts_page' ) > 0 ){
                            ?>
                                    <li class="my-posts show-first"><a href="<?php echo get_permalink( options::get_value( 'general' , 'my_posts_page' ) ) ?>"><?php _e( 'My added posts' , 'cosmotheme' ); ?></a></li>
                            <?php
                                }
                            ?>  
                            <?php if( options::logic( 'general' ,  'enb_likes' ) ){ ?> 
                                <li class="my-likes show-first"><a href="<?php echo $url_like; ?>"><?php _e( 'My loved posts' , 'cosmotheme' ); ?></a></li>
                            <?php } ?>
                            <?php if(is_numeric(options::get_value( 'upload' , 'post_item_page' )) && options::get_value( 'upload' , 'post_item_page' ) > 0){ ?>
                                <li class="my-add"><a href="<?php  echo get_page_link(options::get_value( 'upload' , 'post_item_page' ));  ?>"><?php _e( 'Add post' , 'cosmotheme' ); ?></a></li>
                            <?php } ?>
                                <li class="my-logout"><a href="<?php echo wp_logout_url( home_url() ); ?>"><?php _e( 'Log out' , 'cosmotheme' ); ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>    
        </div>
    <?php } ?>
    <?php
        if( options::logic( 'general' , 'enb_keyboard' ) ){
    ?>
    <div class="row no-overflow">
        <div class="twelve columns" id="big-keyboard">
            <div id="keyboard-container" class="bottom-separator">
                <div id="img">
                    <img src="<?php echo get_template_directory_uri()?>/images/keyboard.png"  alt=""/>
                    <p class="hint">
                        <?php _e( 'Use advanced navigation for a better experience.' , 'cosmotheme' ); ?>
                        <br />
                        <?php _e( 'You can quickly scroll through posts by pressing the above keyboard keys. Now press <strong>the button in right corner</strong> to close this window.' , 'cosmotheme' ); ?>
                    </p>
                </div>
                <div class="close"></div>
            </div>
        </div>
    </div>
    <?php
        }
    ?>
    <div class="container <?php if (options::logic('styling', 'larger')) { echo 'large'; } ?> <?php if( options::logic( 'styling', 'boxed' ) ) echo 'boxed';?>" id="page">
        <div id="fb-root"></div>
     <?php
        $tooltips = options::get_value( '_tooltip' );
            if( is_array( $tooltips ) && !empty( $tooltips ) ){
                $tools = array();
                foreach( $tooltips as $key => $tooltip ){
                    if( is_front_page()  && $tooltip['res_type'] == 'front_page' ){
                        if( defined('IS_FOR_DEMO') ){
                            if( is_user_logged_in() ){
                                if( $tooltip['title'] != 'Login form for members' ){
                                    $location = 'front_page';
                                    $id = 0;
                                    $tools[] = $tooltip;
                                }
                            }else{
                                $location = 'front_page';
                                $id = 0;
                                $tools[] = $tooltip;
                            }
                        }else{
                            $location = 'front_page';
                            $id = 0;
                            $tools[] = $tooltip;
                        }
                    }
                    
                    if( is_single() && isset( $tooltip['res_type'] ) && $tooltip['res_type'] == 'single' && isset( $tooltip['res_posts'] ) && $tooltip['res_posts'] == $post -> ID ){
                        $location = 'single';
                        $id = $post -> ID ;
                        $tools[] = $tooltip;
                    }
                    
                    if( is_page() && isset( $tooltip['res_type'] ) && $tooltip['res_type'] == 'page' && isset( $tooltip['res_pages'] ) && $tooltip['res_pages'] == $post -> ID ){
                        $location = 'page';
                        $id = $post -> ID ;
                        $tools[] = $tooltip;
                    }
                }
                
                if( isset( $location ) ){
                    if( ( isset( $_COOKIE[ ZIP_NAME . '_tour_closed_' . $location . '_' . $id ] ) && $_COOKIE[ ZIP_NAME . '_tour_closed_' . $location . '_' . $id ] != 'true' ) || !isset( $_COOKIE[ ZIP_NAME . '_tour_closed_' . $location . '_' . $id ] ) ){
                        foreach( $tools as $key => $tool ){
                            if( $key + 1 == count( $tools ) ){
                                tools::tour( array( $tool['top'] , $tool['left'] ) , $location , $id , $tool['type'] , $tool['title'] , $tool['description'] , ( $key + 1 ) . '/' . count( $tools ) , false );
                            }else{
                                tools::tour( array( $tool['top'] , $tool['left'] ) , $location , $id , $tool['type'] , $tool['title'] , $tool['description'] , ( $key + 1 ) . '/' . count( $tools ) );
                            }
                        }
                    }
                }
            }
    ?>
        
        <?php
            if( options::logic( 'general' , 'fb_comments' ) ){
                if(options::get_value( 'social' , 'facebook_app_id' ) != ''){
        ?>
                    <?php
                        if( is_user_logged_in () ){
                    ?>
                            <script src="http://connect.facebook.net/en_US/all.js" type="text/javascript" id="fb_script"></script>
                            <script type="text/javascript">
                                FB.getLoginStatus(function(response) {
                                    if( typeof response.status == 'unknown' ){
                                        jQuery(function(){
                                            jQuery.cookie('fbs_<?php echo options::get_value( 'social' , 'facebook_app_id' ); ?>' , null , {expires: 365, path: '/'} );
                                        });
                                    }else{
                                        if( response.status == 'connected' ){
                                            jQuery(function(){
                                                jQuery('#fb_script').attr( 'src' ,  document.location.protocol + '//connect.facebook.net/en_US/all.js#appId=<?php echo options::get_value( 'social' , 'facebook_app_id' ); ?>' );
                                            });
                                        }
                                    }
                                });
                            </script>
                    <?php
                        }else{
                    ?>
                            <script src="http://connect.facebook.net/en_US/all.js" type="text/javascript"></script>
                    <?php
                        }
                    ?>
        <?php
                }else{
        ?>
                    <script src="http://connect.facebook.net/en_US/all.js" type="text/javascript"></script>

        <?php   }
            }else{
        ?>  
                <script src="http://connect.facebook.net/en_US/all.js" type="text/javascript" id="fb_script"></script>  
        <?php   
            }
        ?> 
        
        <?php
            /*login form*/
            if(!is_user_logged_in() && options::logic( 'general' , 'user_login' ) ){

                if(isset($_GET['action']) && ($_GET['action'] == 'login' || $_GET['action'] == 'recover' || $_GET['action'] == 'register') ){
                    $login_box_style ='display: block;';
                }else{
                    $login_box_style =' display:none; ';
                }
            ?>
                <div class="login_box" style="<?php echo $login_box_style; ?>">
                    <span class="close_box" onclick=" jQuery('.login_box').slideToggle(); jQuery('.mobile-user-menu').animate({opacity:1},500);"></span>
            <?php    
                get_template_part('login');
            ?>
                </div>
            <?php    
            }

            $custom_header = new CustomHeader();
            $custom_header -> render();
        ?>
        <?php get_template_part( 'slideshow' ); ?>
        <div class="delimiter"></div>

<?php
    /* register pages */
	if( function_exists( 'wp_get_theme' ) ){
        $current_theme_name = wp_get_theme();
    }else{
        
        $current_theme_name = get_current_theme();
    }

    options::$menu['cosmothemes']['general']            = array( 'label' => __( 'General' , 'cosmotheme' ) , 'title' => __( 'General settings' , 'cosmotheme' ) , 'description' => __( 'General page description.' , 'cosmotheme' ) , 'type' => 'main' , 'main_label' => $current_theme_name );
    options::$menu['cosmothemes']['header']             = array( 'label' => __( 'Custom Header' , 'cosmotheme' ) , 'title' => __( 'Custom header settings' , 'cosmotheme' ) , 'description' => __( 'Custom page description.' , 'cosmotheme' ) );
    options::$menu['cosmothemes']['front_page']         = array( 'label' => __( 'Front page' , 'cosmotheme' )  , 'title' => __( 'Front page settings' , 'cosmotheme' )  , 'description' => __( 'Front page description.' , 'cosmotheme' ) );
    options::$menu['cosmothemes']['layout']             = array( 'label' => __( 'Layout' , 'cosmotheme' )  , 'title' => __( 'Layout page settings' , 'cosmotheme' )  , 'description' => __( 'Layout page description.' , 'cosmotheme' ) );
    options::$menu['cosmothemes']['styling']            = array( 'label' => __( 'Styling' , 'cosmotheme' )  , 'title' => __( 'Styling settings' , 'cosmotheme' )  , 'description' => __( 'Styling page description.' , 'cosmotheme' ) );
    options::$menu['cosmothemes']['menu']               = array( 'label' => __( 'Menus' , 'cosmotheme' )  , 'title' => __( 'Menu settings' , 'cosmotheme' )  , 'description' => __( 'Menu page description.' , 'cosmotheme' ) );
    options::$menu['cosmothemes']['blog_post']          = array( 'label' => __( 'Blogging' , 'cosmotheme' )  , 'title' => __( 'Blog post settings' , 'cosmotheme' )  , 'description' => __( 'Blog post page description.' , 'cosmotheme' ) );
    options::$menu['cosmothemes']['advertisement']      = array( 'label' => __( 'Advertisement' , 'cosmotheme' )  , 'title' => __( 'Advertisement spaces' , 'cosmotheme' )  , 'description' => __( 'Sidebar manager page description.' , 'cosmotheme' ) );
    options::$menu['cosmothemes']['social']             = array( 'label' => __( 'Social networks' , 'cosmotheme' )  , 'title' => __( 'Social network settings' , 'cosmotheme' )  , 'description' => __( 'Social page description.' , 'cosmotheme' ) );
    options::$menu['cosmothemes']['slider']             = array( 'label' => __( 'Slideshow' , 'cosmotheme' )  , 'title' => __( 'Slideshow settings' , 'cosmotheme' )  , 'description' => __( 'Slideshow page description.' , 'cosmotheme' ) );
	options::$menu['cosmothemes']['upload']             = array( 'label' => __( 'Front-end posts' , 'cosmotheme' )  , 'title' => __( 'Front-end posts submission' , 'cosmotheme' )  , 'description' => __( 'Front end tabs settings.' , 'cosmotheme' ) );
    options::$menu['cosmothemes']['_sidebar']           = array( 'label' => __( 'Sidebars' , 'cosmotheme' )  , 'title' => __( 'Sidebar manager' , 'cosmotheme' )  , 'description' => __( 'Sidebar manager page description.' , 'cosmotheme' ) , 'update' => false );
    options::$menu['cosmothemes']['_tooltip']           = array( 'label' => __( 'Tooltips' , 'cosmotheme' )  , 'title' => __( 'Tooltips manager' , 'cosmotheme' )  , 'description' => __( 'Tooltips manager page description.' , 'cosmotheme' ) , 'update' => false );
    options::$menu['cosmothemes']['custom_css']         = array( 'label' => __( 'Custom CSS' , 'cosmotheme' )  , 'title' => __( 'Custom CSS' , 'cosmotheme' )  , 'description' => __( 'Custom CSS' , 'cosmotheme' ) , 'update' => true );
	options::$menu['cosmothemes']['cosmothemes']        = array( 'label' => __( 'CosmoThemes' , 'cosmotheme' )  , 'title' => __( 'CosmoThemes' , 'cosmotheme' )  , 'description' => __( 'CosmoThemes notifications.' , 'cosmotheme' ) );
    options::$menu['cosmothemes']['io']                 = array( 'label' => __( 'Import / Export' , 'cosmotheme' )  , 'title' => __( 'Import/Export' , 'cosmotheme' )  , 'description' => __( 'Import and export settings' , 'cosmotheme' ) );
   
    /* OPTIONS */
    /* GENERAL DEFAULT VALUE */
    options::$default['general']['enb_keyboard']        = 'yes';
    options::$default['general']['enb_hot_keys']        = 'yes';
    options::$default['general']['enb_likes']           = 'yes';
    options::$default['general']['like_label']          = __('Love?','cosmotheme');
    options::$default['general']['dislike_label']       = __('Hate','cosmotheme');
    options::$default['general']['min_likes']           =  50;
    options::$default['general']['user_register']       = 'yes';
    options::$default['general']['user_login']          = 'yes';
    options::$default['general']['like_register']       = 'no';
	options::$default['general']['sticky_bar']			= 'yes';	
    options::$default['general']['enb_featured']        = 'yes';
    options::$default['general']['enb_lightbox']        = 'yes';
    options::$default['general']['breadcrumbs']         = 'no';
    options::$default['general']['meta']                = 'yes';
	
    options::$default['general']['time']                = 'yes';
    options::$default['general']['fb_comments']         = 'yes';
	options::$default['general']['show_admin_bar']      = 'no';
    options::$default['general']['nsfw_content']        = __( 'This article contains NSFW information. To read this information you need to be logged in.' , 'cosmotheme' ) ;

    $my_posts_page = get_page_by_title( __('My added posts','cosmotheme') );
    if($my_posts_page && isset($my_posts_page->ID)){
        options::$default['general']['my_posts_page']      = $my_posts_page->ID;
    }

    $my_account_page = get_page_by_title( __('My account','cosmotheme') );
    if($my_account_page && isset($my_account_page->ID)){
        options::$default['general']['user_profile_page']      = $my_account_page->ID;
    }

    /* GENERAL OPTIONS */

    options::$fields['general']['enb_keyboard']         = array( 'type' => 'st--logic-radio' , 'label' => __( 'Enable keyboard demo' , 'cosmotheme' ) , 'hint' => __( 'If enabled users can click on the keyboard icon to visualize keyboard hot-keys.' , 'cosmotheme' ) );
    options::$fields['general']['enb_hot_keys']         = array( 'type' => 'st--logic-radio' , 'label' => __( 'Enable keyboard hot keys' , 'cosmotheme' )  );
	options::$fields['general']['enb_likes']            = array( 'type' => 'st--logic-radio' , 'label' => __( 'Enable Love for posts' , 'cosmotheme') , 'action' => "act.check( this , { 'yes' : '.g_like , .g_l_register' , 'no' : '' } , 'sh' );" , 'iclasses' => 'g_e_likes');
	options::$fields['general']['like_label']           = array( 'type' => 'st--text' , 'label' => __( 'Like label' , 'cosmotheme' ) , 'hint' => __( 'This label is used on single post in the meta box.' , 'cosmotheme' ) );
    options::$fields['general']['dislike_label']        = array( 'type' => 'st--text' , 'label' => __( 'Disike label' , 'cosmotheme' ) , 'hint' => __( 'This label is used on single post in the meta box.' , 'cosmotheme' ) );
    options::$fields['general']['min_likes']            = array( 'type' => 'st--digit-like' , 'label' => __( 'Minimum number of Loves to set Featured' , 'cosmotheme' ) , 'hint' => __( 'Set minimum number of post likes to change it into a featured post' , 'cosmotheme' ) , 'id' => 'nr_min_likes' ,'action' => "act.min_likes(  jQuery( '#nr_min_likes').val() , 1 );"  );
	options::$fields['general']['sim_likes']            = array( 'type' => 'st--button' , 'value' => __( 'Generate' , 'cosmotheme' ) , 'label' => __( 'Generate random number of Loves for posts' , 'cosmotheme' ) , 'action' => "act.sim_likes( 1 );" , 'hint' => __( 'WARNING! This will reset all current Loves.' , 'cosmotheme' ) );
	options::$fields['general']['reset_likes']			= array( 'type' => 'st--button' , 'value' => __( 'Reset' , 'cosmotheme' ) , 'label' => __( 'Reset likes' , 'cosmotheme' ) , 'action' =>"act.reset_likes(1);" , 'hint' => __( 'WARNING! This will reset all the likes for all the posts!', 'cosmotheme'  ) );
    
    options::$fields['general']['my_posts_page']        = array( 'type' => 'st--select' , 'label' => __( 'Select My posts page' , 'cosmotheme' ) , 'value' => get__pages() , 'hint' => __('Select a blank page from the list to generate the My posts page. Choose "Select item" to disable this page.','cosmotheme'));
	options::$fields['general']['user_profile_page']    = array( 'type' => 'st--select' , 'label' => __( 'Select My account page' , 'cosmotheme' ) , 'value' => get__pages() , 'hint' => __('Select a blank page from the list to generate the My account page. Choose "Select item" to disable this page.','cosmotheme'));
    options::$fields['general']['user_login']           = array( 'type' => 'st--logic-radio' , 'label' => __( 'Enable user login' , 'cosmotheme') , 'hint' => __( 'Check to show "register" link	'  , 'cosmotheme' ) , 'action' => "act.check( this , { 'yes' : '.g_l_register, .g_sticky_bar' , 'no' : '' } , 'sh' );act.mcheck( [ '.yes.g_e_likes' , '.yes.g_u_register']  );" , 'iclasses' => 'g_u_register' );
    options::$fields['general']['like_register']        = array( 'type' => 'st--logic-radio' , 'label' => __( 'Registration is required to Love a post' , 'cosmotheme') );
	options::$fields['general']['sticky_bar']           = array( 'type' => 'st--logic-radio' , 'label' => __( 'Enable sticky bar' , 'cosmotheme') );

    if( options::logic( 'general' , 'enb_likes' ) ){
        options::$fields['general']['min_likes']['classes']     = 'g_like';
        options::$fields['general']['like_register']['classes'] = 'g_l_register';
        options::$fields['general']['sim_likes']['classes']     = 'g_like generate_likes';
		options::$fields['general']['reset_likes']['classes']	= 'g_like reset_likes';
        options::$fields['general']['like_label']['classes']    = 'g_like';
        options::$fields['general']['dislike_label']['classes'] = 'g_like';
    }else{
        options::$fields['general']['min_likes']['classes']     = 'g_like hidden';
        options::$fields['general']['like_register']['classes'] = 'g_l_register hidden';
        options::$fields['general']['sim_likes']['classes']     = 'g_like generate_likes hidden';
		options::$fields['general']['reset_likes']['classes']	= 'g_like reset_likes hidden';
        options::$fields['general']['like_label']['classes']    = 'g_like hidden';
        options::$fields['general']['dislike_label']['classes'] = 'g_like hidden';
    }

	if( options::logic( 'general' , 'user_login' ) ){
		options::$fields['general']['sticky_bar']['classes']     = 'g_sticky_bar';
	}else{
		options::$fields['general']['sticky_bar']['classes']     = 'g_sticky_bar hidden';
	}

    options::$fields['general']['enb_featured']         = array('type' => 'st--logic-radio' , 'label' => __( 'Display featured image inside the post' , 'cosmotheme' ) , 'hint' => __( 'If enabled featured images will be displayed both on category and post page' , 'cosmotheme' ) );
    options::$fields['general']['enb_lightbox']         = array('type' => 'st--logic-radio' , 'label' => __( 'Enable pretty-photo ligthbox' , 'cosmotheme' ) , 'hint' => __( 'Images inside posts will open inside a fancy lightbox' , 'cosmotheme' ) );
    options::$fields['general']['breadcrumbs']          = array( 'type' => 'st--logic-radio' , 'label' => __( 'Show breadcrumbs' , 'cosmotheme') );
    options::$fields['general']['meta']                 = array( 'type' => 'st--logic-radio' , 'label' => __( 'Show entry meta' , 'cosmotheme' ) , 'hint' => __( ' In blog / archive / search / tag / category page' , 'cosmotheme' ), 'action' => "act.check( this , { 'yes' : '.meta_view ' , 'no' : '' } , 'sh' );" );
	$meta_view_type = array('horizontal' => __('Horizontal','cosmotheme'), 'vertical' => __('Vertical','cosmotheme') );  
	options::$fields['general']['time']                 = array( 'type' => 'st--logic-radio' , 'label' => __( 'Use human time' , 'cosmotheme') ,  'hint' => __( 'If set No will use WordPress time format'  , 'cosmotheme' ) );
    options::$fields['general']['fb_comments']          = array( 'type' => 'st--logic-radio' , 'label' => __( 'Use Facebook comments' , 'cosmotheme' ), 'action' => "act.check( this , { 'yes' : '.fb_app_id ' , 'no' : '' } , 'sh' );" );
	options::$fields['general']['fb_app_id_note']       = array( 'type' => 'st--hint' , 'value' => __( 'You can set Facebook application ID' , 'cosmotheme' ) . ' <a href="admin.php?page=cosmothemes__social">' . __( 'here' , 'cosmotheme') . '</a> ' );
	options::$fields['general']['show_admin_bar']       = array( 'type' => 'st--logic-radio' , 'label' => __( 'Show WordPress admin bar' , 'cosmotheme' ));

	
	if( options::logic( 'general' , 'fb_comments' ) ){
        options::$fields['general']['fb_app_id_note']['classes']     = 'fb_app_id';
    }else{
        options::$fields['general']['fb_app_id_note']['classes']     = 'fb_app_id hidden';
    }

    options::$fields['general']['nsfw_content']         = array('type' => 'st--textarea' , 'label' => __( 'Default text for NSFW posts' , 'cosmotheme' ) , 'hint' => __( 'Type here the text that will warn non registered users that the post is marked NSFW' , 'cosmotheme' ) );
    options::$fields['general']['tracking_code']        = array('type' => 'st--textarea' , 'label' => __( 'Tracking code' , 'cosmotheme' ) , 'hint' => __( 'Paste your Google Analytics or other tracking code here.<br />It will be added into the footer of this theme' , 'cosmotheme' ) );
    options::$fields['general']['copy_right']   	    = array('type' => 'st--textarea' , 'label' => __( 'Copyright text' , 'cosmotheme' ) , 'hint' => __( 'Type here the Copyright text that will appear in the footer.<br />To display the current year use "%year%"' , 'cosmotheme' ) );
    
    options::$default['general']['copy_right'] 			= 'Copyright &copy; %year% <a href="http://cosmothemes.com" target="_blank">CosmoThemes</a>. All rights reserved.';


    /* HEADER OPTIONS */

    options::$fields[ 'header' ][ 'preview' ] = array(
        'type' => 'no--header-preview'
    );

    options::$fields[ 'header' ][ 'header_type' ] = array(
        'classes' => 'header-thumbs',
        'type' => 'st--image-select',
        'label' => __( 'Select header type' , 'cosmotheme' ),
        'images' => array(
            'centered' => 'centered.jpg',
            'searchbar' => 'with_searchbar.jpg',
            'menu' => 'with_menu.jpg',
            'social' => 'with_icons.jpg'
        ),
        'labels' => array(
            'centered' => __( 'Centered header' , 'cosmotheme' ),
            'searchbar' => __( 'Header with searchbar' , 'cosmotheme' ),
            'menu' => __( 'Header with menu' , 'cosmotheme' ),
            'social' => __( 'Header with social icons' , 'cosmotheme' )
        )
    );

    options::$fields[ 'header' ][ 'menu_type' ] = array(
        'classes' => 'menu-thumbs',
        'type' => 'st--image-select',
        'label' => __( 'Select menu type' , 'cosmotheme' ),
        'images' => array(
            'centered'      => 'menu_thumbs/centered.jpg',
            'text'          => 'menu_thumbs/text.jpg',
            'colored'       => 'menu_thumbs/colored.jpg',
            'description'   => 'menu_thumbs/description.jpg',
            'buttons'       => 'menu_thumbs/buttons.jpg',
            'vertical'      => 'menu_thumbs/dashed.jpg'
        ),
        'labels' => array(
            'centered'      => __( 'Centered menu' , 'cosmotheme' ),
            'text'          => __( 'Text menu' , 'cosmotheme' ),
            'colored'       => __( 'Colored menu' , 'cosmotheme' ),
            'description'   => __( 'Menu with description' , 'cosmotheme' ),
            'buttons'       => __( 'Buttons menu' , 'cosmotheme' ),
            'vertical'      => __( 'Vertical menu', 'cosmotheme' )
        )
    );

    options::$fields[ 'header' ][ 'menu_color' ] = array(
        'label' => __( 'Menu background color' , 'cosmotheme' ),
        'type' => 'st--color-picker'
    );

    options::$fields[ 'header' ][ 'menu_text_color' ] = array(
        'label' => __( 'Menu text color' , 'cosmotheme' ),
        'type' => 'st--color-picker'
    );

    options::$default[ 'header' ][ 'header_type' ] = 'centered';
    options::$default[ 'header' ][ 'menu_type' ] = 'centered';

    /* END HEADER OPTIONS ************************************************************************/


	/*Front end tabs settings*/
	$subcategories = get_categories( array( 'hide_empty' => false ) );
    $select_subcategories = array();
    $all_categ = array();
    foreach( $subcategories as $subcategory ){
        $select_subcategories[ $subcategory -> cat_ID ] = $subcategory -> name;
        $all_categ[] = $subcategory -> cat_ID;
    }

    $portfolios = get_terms( 'portfolio', array( ) );

    $select_portfolios = array();

    foreach($portfolios as $portfolio){
        $select_portfolios[ $portfolio -> term_id ] = $portfolio -> name;        
    }

    options::$default['upload']['post_item_categories'] = $all_categ;
	options::$default['upload']['enb_image']            = 'yes';
	options::$default['upload']['enb_video']            = 'yes';
	options::$default['upload']['enb_text']             = 'yes';
	options::$default['upload']['enb_file']             = 'yes';
	options::$default['upload']['enb_audio']            = 'yes';
    options::$default['upload']['enb_edit_delete']      = 'yes';
    
    $post_item_page = get_page_by_title( __('Post item','cosmotheme') );
    if($post_item_page && isset($post_item_page->ID)){
        options::$default['upload']['post_item_page']      = $post_item_page->ID;
    }else{
        options::$default['upload']['post_item_page']       = '-';    
    }
    

	options::$fields['upload']['post_item_categories']  = array( 'type' => 'st--multiple-select' , 'label' => __( 'Select categories' ) , 'hint' => __( 'Shift-click or CTRL-click to select multiple items. Users will be able to choose the selected categories.','cosmotheme' ) , 'value' => $select_subcategories );	
	options::$fields['upload']['post_item_portfolios']  = array( 'type' => 'st--multiple-select' , 'label' => __( 'Select portfolios' ) , 'hint' => __( 'Shift-click or CTRL-click to select multiple items. Users will be able to choose the selected portfolios.','cosmotheme' ) , 'value' => $select_portfolios );    
    
    options::$fields['upload']['post_item_page']        = array( 'type' => 'st--select' , 'label' => __( 'Select front-end submission page' , 'cosmotheme' ) ,'hint' => __('Select a blank page from the list to generate the Post item page','cosmotheme') , 'value' => get__pages( array( '-' => __( 'Select page' , 'cosmotheme'  ) ) ) , 'action' => "act.select( '#up_page' , { '-' : '.up_page' } , 'hs' );"  , 'id' => 'up_page');
    options::$fields['upload']['enb_image']             = array( 'type' => 'st--logic-radio' , 'label' => __( 'Enable image submission' , 'cosmotheme') , 'hint' => __('If enabled users will be able to submit Image posts from front end','cosmotheme') );
	options::$fields['upload']['enb_video']             = array( 'type' => 'st--logic-radio' , 'label' => __( 'Enable video submission' , 'cosmotheme') , 'hint' => __('If enabled users will be able to submit Video posts from front end','cosmotheme') );
	options::$fields['upload']['enb_text']              = array( 'type' => 'st--logic-radio' , 'label' => __( 'Enable text submission' , 'cosmotheme') , 'hint' => __('If enabled users will be able to submit Text posts from front end','cosmotheme') );
	options::$fields['upload']['enb_file']              = array( 'type' => 'st--logic-radio' , 'label' => __( 'Enable file submission' , 'cosmotheme') , 'hint' => __('If enabled users will be able to submit File posts (attachments) from front end','cosmotheme') );
	options::$fields['upload']['enb_audio']             = array( 'type' => 'st--logic-radio' , 'label' => __( 'Enable audio submission' , 'cosmotheme') , 'hint' => __('If enabled users will be able to submit Audio posts from front end','cosmotheme') );
    options::$fields['upload']['enb_edit_delete']       = array( 'type' => 'st--logic-radio' , 'label' => __( 'Enable users to edit/delete own posts' , 'cosmotheme')  );

	$default_post_status = array('publish' => __('Published','cosmotheme'), 'pending' => __('Pending','cosmotheme') );  
	options::$fields['upload']['default_posts_status']  = array('type' => 'st--select' , 'label' => __( 'Default posts status' , 'cosmotheme' ) ,'hint' => __('This is the status used for posts submited from front end','cosmotheme'), 'value' => $default_post_status, 'action' => "act.select( '#default_status' , { 'pending' : '.pending_email' } , 'sh' );", 'id' => 'default_status' );
    options::$fields['upload']['pending_email']         = array('type' => 'st--text' , 'label' => __( 'Contact email for pending posts' , 'cosmotheme' ), 'hint' => __('Enter a valid email address if you want to be notified via email when a new post is awaiting moderation','cosmotheme')  );
    
	if( options::get_value( 'upload' , 'post_item_page' ) == '-' ){
        options::$fields['upload']['enb_image']['classes']              = 'up_page hidden';
        options::$fields['upload']['enb_video']['classes']              = 'up_page hidden';
        options::$fields['upload']['enb_text']['classes']               = 'up_page hidden';
        options::$fields['upload']['enb_file']['classes']               = 'up_page hidden';
        options::$fields['upload']['enb_audio']['classes']              = 'up_page hidden';
        options::$fields['upload']['default_posts_status']['classes']   = 'up_page hidden';
    }else{
        options::$fields['upload']['enb_image']['classes']              = 'up_page';
        options::$fields['upload']['enb_video']['classes']              = 'up_page';
        options::$fields['upload']['enb_text']['classes']               = 'up_page';
        options::$fields['upload']['enb_file']['classes']               = 'up_page';
        options::$fields['upload']['enb_audio']['classes']              = 'up_page';
        options::$fields['upload']['default_posts_status']['classes']   = 'up_page';
    }
	
	if( options::get_value( 'upload' , 'post_item_page' ) != '-'  && options::get_value( 'upload' , 'default_posts_status') == 'pending' ){
		options::$fields['upload']['pending_email']['classes']          = 'pending_email up_page';
	}else{
		options::$fields['upload']['pending_email']['classes']          = 'pending_email  up_page hidden';
	}

	options::$default['upload']['default_posts_status'] = 'publish';

    /* LAYOUT DEFAULT VALUE */
    options::$default['layout']['front_page']           = 'right';
    options::$default['layout']['v_front_page']         = 'list_view';
    options::$default['layout']['404']                  = 'right';
    options::$default['layout']['author']               = 'right';
    options::$default['layout']['v_author']             = 'list_view';
    options::$default['layout']['page']                 = 'full';
    options::$default['layout']['single']               = 'right';
    options::$default['layout']['blog_page']            = 'right';
    options::$default['layout']['v_blog_page']          = 'list_view';

    options::$default['layout']['search']               = 'right';
    options::$default['layout']['v_search']             = 'list_view';
    options::$default['layout']['archive']              = 'right';
    options::$default['layout']['v_archive']            = 'list_view';
    options::$default['layout']['category']             = 'right';
    options::$default['layout']['v_category']           = 'list_view';
    options::$default['layout']['tag']                  = 'right';
    options::$default['layout']['v_tag']                = 'list_view';
    options::$default['layout']['portfolio']            = 'right';
    options::$default['layout']['v_portfolio']          = 'list_view';
    options::$default['layout']['attachment']           = 'right';

    /* LAYOUT OPTIONS */
    $layouts                                            = array('left' => __( 'Left sidebar' , 'cosmotheme' ) , 'right' => __( 'Right sidebar' , 'cosmotheme' ) , 'full' => __( 'Full width' , 'cosmotheme' ) );
    $view                                               = array('list_view' => __( 'List view' , 'cosmotheme' ) , 'grid_view' => __( 'Grid view' , 'cosmotheme' ), 'thumbnails_view' => __( 'Thumbnails view' , 'cosmotheme' ) ); 
    $sidebars_record = options::get_value( '_sidebar' );
    if( !is_array( $sidebars_record ) || empty( $sidebars_record ) ){
        $sidebar = array( '' => 'main' );
    }else{
        foreach( $sidebars_record as $sidebars ){
            $sidebar[ trim( strtolower( str_replace( ' ' , '-' , $sidebars['title'] ) ) ) ] = $sidebars['title'];
        }
        $sidebar[''] = 'main';
    }

    $sidebar_columns = array(
        3 => __( 'Three columns' , 'cosmotheme' ),
        9 => __( 'Nine columns' , 'cosmotheme' )
    );

    $no_sidebar_columns = array(
        2 => __( 'Two columns' , 'cosmotheme' ),
        3 => __( 'Three columns' , 'cosmotheme' ),
        4 => __( 'Four columns' , 'cosmotheme' ),
        6 => __( 'Six columns' , 'cosmotheme' )
    );

    options::$fields['layout']['title0']                = array('type' => 'ni--title' , 'title' => __( 'Front page' , 'cosmotheme' ) );
    options::$fields['layout']['front_page']            = array('type' => 'st--select' , 'label' => __( 'Layout for front page' , 'cosmotheme' ) , 'value' => $layouts , 'action' => "act.select('.front_page_layout' , { 'left' : '.front_page_sidebar' , 'right' : '.front_page_sidebar' } , 'sh_' )" , 'iclasses' => 'front_page_layout' );
    options::$fields['layout']['front_page_sidebar']    = array('type' => 'st--select' , 'label' => __( 'Select sidebar for front page template' , 'cosmotheme' ) , 'value' =>  $sidebar , 'classes' => 'front_page_sidebar' );
    if( options::get_value( 'layout' , 'front_page' ) == 'full' ){
        options::$fields['layout']['front_page_sidebar']['classes'] = 'front_page_sidebar hidden';
    }
/*
    options::$fields['layout']['v_front_page']          = array('type' => 'st--select' , 'label' => __( 'View type for front page' , 'cosmotheme') , 'value' => $view );
*/
    options::$fields['layout']['title1']                = array('type' => 'ni--title' , 'title' => __( '404' , 'cosmotheme' ) );
    options::$fields['layout']['404']                   = array('type' => 'st--select' , 'label' => __( 'Layout for 404 page' , 'cosmotheme' ) , 'value' => $layouts , 'action' => "act.select('.layout_404' , { 'left' : '.sidebar_404' , 'right' : '.sidebar_404' } , 'sh_' )" , 'iclasses' => 'layout_404'  );
    options::$fields['layout']['404_sidebar']           = array('type' => 'st--select' , 'label' => __( 'Select sidebar for 404 template' , 'cosmotheme' ) , 'value' =>  $sidebar , 'classes' => 'sidebar_404' );
    if( options::get_value( 'layout' , '404' ) == 'full' ){
        options::$fields['layout']['404_sidebar']['classes'] = 'sidebar_404 hidden';
    }


    options::$fields['layout']['title2']                = array('type' => 'ni--title' , 'title' => __( 'Author' , 'cosmotheme' ) );
    options::$fields['layout']['author']                = array('type' => 'st--select' , 'label' => __( 'Layout for author page' , 'cosmotheme' ) , 'value' => $layouts , 'action' => "act.select('.author_layout' , { 'left' : '.author_sidebar' , 'right' : '.author_sidebar' } , 'sh_' ); act.sh.columns( 'author' )" , 'iclasses' => 'author_layout' );
    options::$fields['layout']['author_sidebar']        = array('type' => 'st--select' , 'label' => __( 'Select sidebar for author template' , 'cosmotheme' ) , 'value' =>  $sidebar , 'classes' => 'author_sidebar' );
    if( options::get_value( 'layout' , 'author' ) == 'full' ){
        options::$fields['layout']['author_sidebar']['classes'] = 'author_sidebar hidden';
    }
    options::$fields['layout']['v_author']              = array('type' => 'st--select' , 'label' => __( 'View type for author page' , 'cosmotheme') , 'value' => $view, 'action' => "act.sh.columns( 'author' );", 'iclasses' => 'author_view' );
    options::$fields[ 'layout' ][ 'columns_author_sidebar' ] = array( 'type' => 'st--select', 'label' => __( 'Number of columns for author page', 'cosmothemes' ), 'value' => $sidebar_columns, 'classes' => 'author-sidebar-columns' );
    options::$fields[ 'layout' ][ 'columns_author_no_sidebar' ] = array( 'type' => 'st--select', 'label' => __( 'Number of columns for author page', 'cosmothemes' ), 'value' => $no_sidebar_columns, 'classes' => 'author-no-sidebar-columns' );

    options::$fields['layout']['title3']                = array('type' => 'ni--title' , 'title' => __( 'Pages / single post' , 'cosmotheme' ) );
    options::$fields['layout']['page']                  = array('type' => 'st--select' , 'label' => __( 'Layout for pages' , 'cosmotheme' ) , 'value' => $layouts , 'action' => "act.select('.page_layout' , { 'left' : '.page_sidebar' , 'right' : '.page_sidebar' } , 'sh_' )" , 'iclasses' => 'page_layout' );
    options::$fields['layout']['page_sidebar']          = array('type' => 'st--select' , 'label' => __( 'Select sidebar for page template' , 'cosmotheme' ) , 'value' =>  $sidebar , 'classes' => 'page_sidebar' );
    if( options::get_value( 'layout' , 'page' ) == 'full' ){
        options::$fields['layout']['page_sidebar']['classes'] = 'page_sidebar hidden';
    }
    options::$fields['layout']['single']                = array('type' => 'st--select' , 'label' => __( 'Layout for single post' , 'cosmotheme' ) , 'value' => $layouts , 'action' => "act.select('.single_layout' , { 'left' : '.single_sidebar' , 'right' : '.single_sidebar' } , 'sh_' )" , 'iclasses' => 'single_layout' );
    options::$fields['layout']['single_sidebar']        = array('type' => 'st--select' , 'label' => __( 'Select sidebar for single page template' , 'cosmotheme' ) , 'value' =>  $sidebar , 'classes' => 'single_sidebar' );
    if( options::get_value( 'layout' , 'single' ) == 'full' ){
        options::$fields['layout']['single_sidebar']['classes'] = 'single_sidebar hidden';
    }
    options::$fields['layout']['title13']               = array('type' => 'ni--title' , 'title' => __( 'Blog page' , 'cosmotheme' ) );
    options::$fields['layout']['blog_page']             = array('type' => 'st--select' , 'label' => __( 'Layout for blog page' , 'cosmotheme' ) , 'value' => $layouts , 'action' => "act.select('.blog_page_layout' , { 'left' : '.blog_page_sidebar' , 'right' : '.blog_page_sidebar' } , 'sh_' ); act.sh.columns( 'blog_page' )" , 'iclasses' => 'blog_page_layout' );
    options::$fields['layout']['blog_page_sidebar']     = array('type' => 'st--select' , 'label' => __( 'Select sidebar for blog page template' , 'cosmotheme' ) , 'value' =>  $sidebar , 'classes' => 'blog_page_sidebar' );
    if( options::get_value( 'layout' , 'blog_page' ) == 'full' ){
        options::$fields['layout']['blog_page_sidebar']['classes'] = 'blog_page_sidebar hidden';
    }
    options::$fields['layout']['v_blog_page']           = array('type' => 'st--select' , 'label' => __( 'View type for blog page' , 'cosmotheme') , 'value' => $view, 'action' => "act.sh.columns( 'blog_page' );", 'iclasses' => 'blog_page_view' );
    options::$fields[ 'layout' ][ 'columns_blog_page_sidebar' ] = array( 'type' => 'st--select', 'label' => __( 'Number of columns for blog page', 'cosmothemes' ), 'value' => $sidebar_columns, 'classes' => 'blog_page-sidebar-columns' );
    options::$fields[ 'layout' ][ 'columns_blog_page_no_sidebar' ] = array( 'type' => 'st--select', 'label' => __( 'Number of columns for blog page', 'cosmothemes' ), 'value' => $no_sidebar_columns, 'classes' => 'blog_page-no-sidebar-columns' );

    options::$fields['layout']['title4']                = array('type' => 'ni--title' , 'title' => __( 'Search' , 'cosmotheme' ) );
    options::$fields['layout']['search']                = array('type' => 'st--select' , 'label' => __( 'Layout for search page' , 'cosmotheme' ) , 'value' => $layouts , 'action' => "act.select('.search_layout' , { 'left' : '.search_sidebar' , 'right' : '.search_sidebar' } , 'sh_' ); act.sh.columns( 'search' )" , 'iclasses' => 'search_layout' );
    options::$fields['layout']['search_sidebar']        = array('type' => 'st--select' , 'label' => __( 'Select sidebar for search template' , 'cosmotheme' ) , 'value' =>  $sidebar , 'classes' => 'search_sidebar' );
    if( options::get_value( 'layout' , 'search' ) == 'full' ){
        options::$fields['layout']['search_sidebar']['classes'] = 'search_sidebar hidden';
    }
    options::$fields['layout']['v_search']              = array('type' => 'st--select' , 'label' => __( 'View type for search page' , 'cosmotheme') , 'value' => $view, 'action' => "act.sh.columns( 'search' );", 'iclasses' => 'search_view' );
    options::$fields[ 'layout' ][ 'columns_search_sidebar' ] = array( 'type' => 'st--select', 'label' => __( 'Number of columns for search page', 'cosmothemes' ), 'value' => $sidebar_columns, 'classes' => 'search-sidebar-columns' );
    options::$fields[ 'layout' ][ 'columns_search_no_sidebar' ] = array( 'type' => 'st--select', 'label' => __( 'Number of columns for search page', 'cosmothemes' ), 'value' => $no_sidebar_columns, 'classes' => 'search-no-sidebar-columns' );


    options::$fields['layout']['title5']                = array('type' => 'ni--title' , 'title' => __( 'Archive' , 'cosmotheme' ) );
    options::$fields['layout']['archive']               = array('type' => 'st--select' , 'label' => __( 'Layout for archive page' , 'cosmotheme' ) , 'value' => $layouts , 'action' => "act.select('.archive_layout' , { 'left' : '.archive_sidebar' , 'right' : '.archive_sidebar' } , 'sh_' );act.sh.columns( 'archive' );" , 'iclasses' => 'archive_layout' );
    options::$fields['layout']['archive_sidebar']       = array('type' => 'st--select' , 'label' => __( 'Select sidebar for archive template' , 'cosmotheme' ) , 'value' =>  $sidebar , 'classes' => 'archive_sidebar' );
    if( options::get_value( 'layout' , 'archive' ) == 'full' ){
        options::$fields['layout']['archive_sidebar']['classes'] = 'archive_sidebar hidden';
    }
    options::$fields['layout']['v_archive']             = array('type' => 'st--select' , 'label' => __( 'View type for archive page' , 'cosmotheme') , 'value' => $view, 'action' => "act.sh.columns( 'archive' );", 'iclasses' => 'archive_view' );
    options::$fields[ 'layout' ][ 'columns_archive_sidebar' ] = array( 'type' => 'st--select', 'label' => __( 'Number of columns for search page', 'cosmothemes' ), 'value' => $sidebar_columns, 'classes' => 'archive-sidebar-columns' );
    options::$fields[ 'layout' ][ 'columns_archive_no_sidebar' ] = array( 'type' => 'st--select', 'label' => __( 'Number of columns for search page', 'cosmothemes' ), 'value' => $no_sidebar_columns, 'classes' => 'archive-no-sidebar-columns' );

    options::$fields['layout']['title6']                = array('type' => 'ni--title' , 'title' => __( 'Category' , 'cosmotheme' ) );
    options::$fields['layout']['category']              = array('type' => 'st--select' , 'label' => __( 'Layout for category page' , 'cosmotheme' ) , 'value' => $layouts , 'action' => "act.select('.category_layout' , { 'left' : '.category_sidebar' , 'right' : '.category_sidebar' } , 'sh_' );act.sh.columns( 'category' );" , 'iclasses' => 'category_layout' );
    options::$fields['layout']['category_sidebar']      = array('type' => 'st--select' , 'label' => __( 'Select sidebar for category template' , 'cosmotheme' ) , 'value' =>  $sidebar , 'classes' => 'category_sidebar' );
    if( options::get_value( 'layout' , 'category' ) == 'full' ){
        options::$fields['layout']['category_sidebar']['classes'] = 'category_sidebar hidden';
    }
    options::$fields['layout']['v_category']            = array('type' => 'st--select' , 'label' => __( 'View type for category page' , 'cosmotheme') , 'value' => $view, 'action' => "act.sh.columns( 'category' );", 'iclasses' => 'category_view' );
    options::$fields[ 'layout' ][ 'columns_category_sidebar' ] = array( 'type' => 'st--select', 'label' => __( 'Number of columns for category page', 'cosmothemes' ), 'value' => $sidebar_columns, 'classes' => 'category-sidebar-columns' );
    options::$fields[ 'layout' ][ 'columns_category_no_sidebar' ] = array( 'type' => 'st--select', 'label' => __( 'Number of columns for category page', 'cosmothemes' ), 'value' => $no_sidebar_columns, 'classes' => 'category-no-sidebar-columns' );

    options::$fields['layout']['title7']                = array('type' => 'ni--title' , 'title' => __( 'Tags' , 'cosmotheme' ) );
    options::$fields['layout']['tag']                   = array('type' => 'st--select' , 'label' => __( 'Layout for tags page' , 'cosmotheme' ) , 'value' => $layouts , 'action' => "act.select('.tag_layout' , { 'left' : '.tag_sidebar' , 'right' : '.tag_sidebar' } , 'sh_' );act.sh.columns( 'tag' );" , 'iclasses' => 'tag_layout' );
    options::$fields['layout']['tag_sidebar']           = array('type' => 'st--select' , 'label' => __( 'Select sidebar for tags template' , 'cosmotheme' ) , 'value' =>  $sidebar , 'classes' => 'tag_sidebar' );
    if( options::get_value( 'layout' , 'tag' ) == 'full' ){
        options::$fields['layout']['tag_sidebar']['classes'] = 'tag_sidebar hidden';
    }
    options::$fields['layout']['v_tag']                 = array('type' => 'st--select' , 'label' => __( 'View type for tags page' , 'cosmotheme') , 'value' => $view, 'action' => "act.sh.columns( 'tag' );", 'iclasses' => 'tag_view' );
    options::$fields[ 'layout' ][ 'columns_tag_sidebar' ] = array( 'type' => 'st--select', 'label' => __( 'Number of columns for tags page', 'cosmothemes' ), 'value' => $sidebar_columns, 'classes' => 'tag-sidebar-columns' );
    options::$fields[ 'layout' ][ 'columns_tag_no_sidebar' ] = array( 'type' => 'st--select', 'label' => __( 'Number of columns for tags page', 'cosmothemes' ), 'value' => $no_sidebar_columns, 'classes' => 'tag-no-sidebar-columns' );


    options::$fields['layout']['title9']                = array('type' => 'ni--title' , 'title' => __( 'Portfolio' , 'cosmotheme' ) );
    options::$fields['layout']['portfolio']                   = array('type' => 'st--select' , 'label' => __( 'Layout for portfolio page' , 'cosmotheme' ) , 'value' => $layouts , 'action' => "act.select('.portfolio_layout' , { 'left' : '.portfolio_sidebar' , 'right' : '.portfolio_sidebar' } , 'sh_' );act.sh.columns( 'portfolio' );" , 'iclasses' => 'portfolio_layout' );
    options::$fields['layout']['portfolio_sidebar']           = array('type' => 'st--select' , 'label' => __( 'Select sidebar for portfolio template' , 'cosmotheme' ) , 'value' =>  $sidebar , 'classes' => 'portfolio_sidebar' );
    if( options::get_value( 'layout' , 'portfolio' ) == 'full' ){
        options::$fields['layout']['portfolio_sidebar']['classes'] = 'portfolio_sidebar hidden';
    }
    options::$fields['layout']['v_portfolio']                 = array('type' => 'st--select' , 'label' => __( 'View type for portfolio page' , 'cosmotheme') , 'value' => $view, 'action' => "act.sh.columns( 'portfolio' );", 'iclasses' => 'portfolio_view' );
    options::$fields[ 'layout' ][ 'columns_portfolio_sidebar' ] = array( 'type' => 'st--select', 'label' => __( 'Number of columns for tags page', 'cosmothemes' ), 'value' => $sidebar_columns, 'classes' => 'portfolio-sidebar-columns' );
    options::$fields[ 'layout' ][ 'columns_portfolio_no_sidebar' ] = array( 'type' => 'st--select', 'label' => __( 'Number of columns for tags page', 'cosmothemes' ), 'value' => $no_sidebar_columns, 'classes' => 'portfolio-no-sidebar-columns' );

    if( options::get_value( 'layout', 'author' ) == 'full' ){
        options::$fields[ 'layout' ][ 'columns_author_sidebar' ][ 'classes' ] .= ' hidden';
    }else{
        options::$fields[ 'layout' ][ 'columns_author_no_sidebar' ][ 'classes' ] .= ' hidden';
    }
    if( options::get_value( 'layout', 'v_author' ) == 'list_view' ){
        options::$fields[ 'layout' ][ 'columns_author_sidebar' ][ 'classes' ] .= ' hidden';
        options::$fields[ 'layout' ][ 'columns_author_no_sidebar' ][ 'classes' ] .= ' hidden';
    }
    if( options::get_value( 'layout', 'blog_page' ) == 'full' ){
        options::$fields[ 'layout' ][ 'columns_blog_page_sidebar' ][ 'classes' ] .= ' hidden';
    }else{
        options::$fields[ 'layout' ][ 'columns_blog_page_no_sidebar' ][ 'classes' ] .= ' hidden';
    }
    if( options::get_value( 'layout', 'v_blog_page' ) == 'list_view' ){
        options::$fields[ 'layout' ][ 'columns_blog_page_sidebar' ][ 'classes' ] .= ' hidden';
        options::$fields[ 'layout' ][ 'columns_blog_page_no_sidebar' ][ 'classes' ] .= ' hidden';
    }
    if( options::get_value( 'layout', 'search' ) == 'full' ){
        options::$fields[ 'layout' ][ 'columns_search_sidebar' ][ 'classes' ] .= ' hidden';
    }else{
        options::$fields[ 'layout' ][ 'columns_search_no_sidebar' ][ 'classes' ] .= ' hidden';
    }
    if( options::get_value( 'layout', 'v_search' ) == 'list_view' ){
        options::$fields[ 'layout' ][ 'columns_search_sidebar' ][ 'classes' ] .= ' hidden';
        options::$fields[ 'layout' ][ 'columns_search_no_sidebar' ][ 'classes' ] .= ' hidden';
    }
    if( options::get_value( 'layout', 'archive' ) == 'full' ){
        options::$fields[ 'layout' ][ 'columns_archive_sidebar' ][ 'classes' ] .= ' hidden';
    }else{
        options::$fields[ 'layout' ][ 'columns_archive_no_sidebar' ][ 'classes' ] .= ' hidden';
    }
    if( options::get_value( 'layout', 'v_archive' ) == 'list_view' ){
        options::$fields[ 'layout' ][ 'columns_archive_sidebar' ][ 'classes' ] .= ' hidden';
        options::$fields[ 'layout' ][ 'columns_archive_no_sidebar' ][ 'classes' ] .= ' hidden';
    }
    if( options::get_value( 'layout', 'category' ) == 'full' ){
        options::$fields[ 'layout' ][ 'columns_category_sidebar' ][ 'classes' ] .= ' hidden';
    }else{
        options::$fields[ 'layout' ][ 'columns_category_no_sidebar' ][ 'classes' ] .= ' hidden';
    }
    if( options::get_value( 'layout', 'v_category' ) == 'list_view' ){
        options::$fields[ 'layout' ][ 'columns_category_sidebar' ][ 'classes' ] .= ' hidden';
        options::$fields[ 'layout' ][ 'columns_category_no_sidebar' ][ 'classes' ] .= ' hidden';
    }
    if( options::get_value( 'layout', 'tag' ) == 'full' ){
        options::$fields[ 'layout' ][ 'columns_tag_sidebar' ][ 'classes' ] .= ' hidden';
    }else{
        options::$fields[ 'layout' ][ 'columns_tag_no_sidebar' ][ 'classes' ] .= ' hidden';
    }
    if( options::get_value( 'layout', 'v_tag' ) == 'list_view' ){
        options::$fields[ 'layout' ][ 'columns_tag_sidebar' ][ 'classes' ] .= ' hidden';
        options::$fields[ 'layout' ][ 'columns_tag_no_sidebar' ][ 'classes' ] .= ' hidden';
    }
    if( options::get_value( 'layout', 'portfolio' ) == 'full' ){
        options::$fields[ 'layout' ][ 'columns_portfolio_sidebar' ][ 'classes' ] .= ' hidden';
    }else{
        options::$fields[ 'layout' ][ 'columns_portfolio_no_sidebar' ][ 'classes' ] .= ' hidden';
    }
    if( options::get_value( 'layout', 'v_portfolio' ) == 'list_view' ){
        options::$fields[ 'layout' ][ 'columns_portfolio_sidebar' ][ 'classes' ] .= ' hidden';
        options::$fields[ 'layout' ][ 'columns_portfolio_no_sidebar' ][ 'classes' ] .= ' hidden';
    }

    options::$default[ 'layout' ][ 'columns_author_sidebar' ] = 3;
    options::$default[ 'layout' ][ 'columns_author_no_sidebar' ] = 4;
    options::$default[ 'layout' ][ 'columns_blog_page_sidebar' ] = 3;
    options::$default[ 'layout' ][ 'columns_blog_page_no_sidebar' ] = 4;
    options::$default[ 'layout' ][ 'columns_search_sidebar' ] = 3;
    options::$default[ 'layout' ][ 'columns_search_no_sidebar' ] = 4;
    options::$default[ 'layout' ][ 'columns_archive_sidebar' ] = 3;
    options::$default[ 'layout' ][ 'columns_archive_no_sidebar' ] = 4;
    options::$default[ 'layout' ][ 'columns_category_sidebar' ] = 3;
    options::$default[ 'layout' ][ 'columns_category_no_sidebar' ] = 4;
    options::$default[ 'layout' ][ 'columns_tag_sidebar' ] = 3;
    options::$default[ 'layout' ][ 'columns_tag_no_sidebar' ] = 4;
    options::$default[ 'layout' ][ 'columns_portfolio_sidebar' ] = 3;
    options::$default[ 'layout' ][ 'columns_portfolio_no_sidebar' ] = 4;

    options::$fields['layout']['title8']                = array('type' => 'ni--title' , 'title' => '' );
    options::$fields['layout']['attachment']            = array('type' => 'st--select' , 'label' => __( 'Layout for attachment page' , 'cosmotheme' ) , 'value' => $layouts , 'action' => "act.select('.attachment_layout' , { 'left' : '.attachment_sidebar' , 'right' : '.attachment_sidebar' } , 'sh_' )" , 'iclasses' => 'attachment_layout' );
    options::$fields['layout']['attachment_sidebar']    = array('type' => 'st--select' , 'label' => __( 'Select sidebar for attachment template' , 'cosmotheme' ) , 'value' =>  $sidebar , 'classes' => 'attachment_sidebar' );
    if( options::get_value( 'layout' , 'attachment' ) == 'full' ){
        options::$fields['layout']['attachment_sidebar']['classes'] = 'attachment_sidebar hidden';
    }

    if( !isset( $_GET[ 'settings-updated' ] ) ){
        $layout = get_option( 'front_page' );
        $layout[ 'layout' ] = options::get_value( 'layout', 'front_page' );
        $layout[ 'sidebar' ] = options::get_value( 'layout', 'front_page_sidebar' );
        update_option( 'front_page', $layout );
    }

    options::$fields[ 'front_page' ][ 'layout' ] = array(
        'type' => 'st--select',
        'label' => __( 'Layout for front page' , 'cosmotheme' ),
        'value' => $layouts,
        'ivalue' => options::get_value( 'layout', 'front_page' ),
        'action' => "act.select('.front_page_layout' , { 'left' : '.front_page_sidebar' , 'right' : '.front_page_sidebar' } , 'sh_' );jQuery( '.options-columns').trigger('show-hide')",
        'iclasses' => 'front_page_layout'
    );

    options::$fields[ 'front_page' ][ 'sidebar' ] = array(
        'type' => 'st--select',
        'label' => __( 'Select sidebar for front page template' , 'cosmotheme' ),
        'value' =>  $sidebar,
        'ivalue' => options::get_value( 'layout', 'front_page_sidebar' ),
        'classes' => 'front_page_sidebar'
    );

    if( options::get_value( 'front_page' , 'layout' ) == 'full' ){
        options::$fields['front_page']['sidebar']['classes'] = 'front_page_sidebar hidden';
    }

    options::$fields[ 'front_page' ][ 'builder' ] = array(
        'type' => 'cd--whatever',
        'content' => new FrontpageBuilder()
    );

	/* STYLING DEFAULT VALUES */
    
    options::$default['styling']['larger']              = 'yes';
    options::$default['styling']['front_end']           = 'no';
    options::$default['styling']['background']          = 'pattern.paper.png';
    options::$default['styling']['logo_type']           = 'text';
	options::$default['styling']['background_color']    = '#ffffff';
    options::$default['styling']['footer_bg_color']     = '#414B52';
    options::$default['styling']['stripes']             = 'yes';

    /* STYLING OPTIONS */
    
    options::$fields['styling']['larger']              = array('type' => 'st--logic-radio' , 'label' => __( 'Enable larger view port' , 'cosmotheme' ), 'hint' => __( 'If enabled, the content will be stretched up to 1170px' , 'cosmotheme' ) );
    options::$fields['styling']['boxed']              = array('type' => 'st--logic-radio' , 'label' => __( 'Enable boxed layout' , 'cosmotheme' ) );

    $pattern_path = 'pattern/s.pattern.';
    $pattern = array(
        "flowers"=>"flowers.png" , "flowers_2"=>"flowers_2.png" , "flowers_3"=>"flowers_3.png" , "flowers_4"=>"flowers_4.png" ,"circles"=>"circles.png","dots"=>"dots.png","grid"=>"grid.png","noise"=>"noise.png",
        "paper"=>"paper.png","rectangle"=>"rectangle.png","squares_1"=>"squares_1.png","squares_2"=>"squares_2.png","thicklines"=>"thicklines.png","thinlines"=>"thinlines.png" , "none"=>"none.png"
    );

    options::$fields['styling']['bg_title']             = array( 'type' => 'ni--title' , 'title' => __( 'Select theme background' , 'cosmotheme' ) );
    options::$fields['styling']['background']           = array( 'type' => 'ni--radio-icon' ,  'value' => $pattern , 'path' => $pattern_path , 'in_row' => 5 );
    
    /* color */
    /* background */
    options::$fields['styling']['background_color']     = array('type' => 'st--color-picker' , 'label' => __( 'Set background color' , 'cosmotheme' ) );
    //options::$fields['styling']['footer_bg_color']      = array('type' => 'st--color-picker' , 'label' => __( 'Set footer background color' , 'cosmotheme' ) );
    options::$fields['styling']['background_image']     = array( 'type' => 'st--hint' , 'value' => __( 'To set a background image go to' , 'cosmotheme' ) . ' <a href="themes.php?page=custom-background">' . __( 'Appearence - Background'  , 'cosmotheme' ) . '</a>' );

    $path_parts = pathinfo( options::get_value( 'styling' , 'favicon' ) );
    if( strlen( options::get_value( 'styling' , 'favicon' ) ) && $path_parts['extension'] != 'ico' ){
        $ico_hint = '<span style="color:#cc0000;">' . __( 'Error, please select "ico" type media file' , 'cosmotheme' ) . '</span>';
    }else{
        $ico_hint = __( "Please select 'ico' type media file. Make sure you allow uploading 'ico' type in General Settings -> Upload file types" , 'cosmotheme' );
    }

    options::$fields['styling']['favicon']              = array('type' => 'st--upload' , 'label' => __( 'Custom favicon' , 'cosmotheme' ) , 'id' => 'favicon_path' , 'hint' => $ico_hint );
    options::$fields['styling']['stripes']              = array('type' => 'st--logic-radio' , 'label' => __( 'Enable stripes effect for post images' , 'cosmotheme' ) );
    options::$fields['styling']['logo_type']            = array('type' => 'st--select' , 'label' => __( 'Logo type ' , 'cosmotheme' ) , 'value' => array( 'text' => 'Text logo' , 'image' => 'Image logo' ) , 'hint' => __( 'Enable text-based site title and tagline.' , 'cosmotheme' ) , 'action' => "act.select( '.g_logo_type' , { 'text':'.g_logo_text' , 'image':'.g_logo_image' } , 'sh_' );" , 'iclasses' => 'g_logo_type' );

    /* fields for general -> logo_type */
    options::$fields['styling']['logo_url']             = array('type' => 'st--upload' , 'label' => __( 'Custom logo URL' , 'cosmotheme' ) , 'id' => 'logo_path' );

    /* hide not used fields */
	if( options::get_value( 'styling' , 'logo_type') == 'image' ){
        options::$fields['styling']['logo_url']['classes'] 	= 'g_logo_image';
        text::fields( 'styling' , 'logo' ,  'g_logo_text hidden' , get_option( 'blogname' ) );
        options::$fields['styling']['hint']                 = array('type' => 'st--hint' , 'classes' => 'g_logo_text hidden' ,'value' => __( 'To change blog title go to <a href="options-general.php">General settings</a> ' , 'cosmotheme') );
    }else{
		options::$fields['styling']['logo_url']['classes'] 	= 'generic-hint g_logo_image hidden';
        text::fields( 'styling' , 'logo' ,  'g_logo_text' , get_option( 'blogname' ) );
        options::$fields['styling']['hint']                 = array('type' => 'st--hint' , 'classes' => 'generic-hint g_logo_text' , 'value' => __( 'To change blog title go to <a href="options-general.php">General settings </a> ' , 'cosmotheme') );
    }
    
	/* MENU DEFAULT VALUES */
    options::$default['menu']['header']                 = 8;
    options::$default['menu']['footer']                 = 4;
    
    /*options::$default['menu']['home']                   = __( 'Home' , 'cosmotheme' );
    options::$default['menu']['home_']                  = __( 'welcome' , 'cosmotheme' );
    options::$default['menu']['featured']               = __( 'Featured' , 'cosmotheme' );
    options::$default['menu']['featured_']              = __( 'voted posts' , 'cosmotheme' );
    options::$default['menu']['fresh']                  = __( 'Fresh' , 'cosmotheme' );
    options::$default['menu']['fresh_']                 = __( 'new on site' , 'cosmotheme' );
    options::$default['menu']['random']                 = __( 'I feel lucky' , 'cosmotheme' );
    options::$default['menu']['random_']                = __( 'random posts' , 'cosmotheme' );*/
            
    /* MENU OPTIONS */
    
    options::$fields['menu']['custom_menu']             = array('type' => 'ni--title' , 'title' => __( 'Custom menu' , 'cosmotheme' ) );
    options::$fields['menu']['header']                  = array('type' => 'st--select' , 'value' => fields::digit_array( 20 ) , 'label' => __( 'Set limit for main menu' , 'cosmotheme' ) , 'hint' => __( 'Set the number of visible menu items. Remaining menu items<br />will be shown in the drop down menu item "More"' , 'cosmotheme' ) );
    options::$fields['menu']['footer']                  = array('type' => 'st--select' , 'value' => fields::digit_array( 20 ) , 'label' => __( 'Set limit for footer menu' , 'cosmotheme' ) , 'hint' => __( 'Set the number of visible menu items' , 'cosmotheme' ) );
    /*options::$fields['menu']['footer']                  = array('type' => 'st--select' , 'value' => fields::digit_array( 20 ) , 'label' => __( 'Set limit for footer menu' , 'cosmotheme' ) , 'hint' => __( 'Set the number of visible menu items' , 'cosmotheme' ) );*/

    /* POSTS OPTIONS */
    options::$fields['blog_post']['post_title0']        = array('type' => 'ni--title' , 'title' => __( 'General Posts Settings' , 'cosmotheme' ) );

    options::$fields['blog_post']['show_similar']       = array('type' => 'st--logic-radio' , 'label' => __( 'Enable similar posts' , 'cosmotheme' ), 'action' => "act.check( this , { 'yes' : '.similar_type_class ' , 'no' : '' } , 'sh' );" );
    options::$fields['blog_post']['post_similar_full']  = array('type' => 'st--select' , 'value' => fields::digit_array( 20 , 1 ) , 'label' => __( 'Number of similar posts (full width and no meta)' , 'cosmotheme' ) );
    options::$fields['blog_post']['post_similar_side']  = array('type' => 'st--select' , 'value' => fields::digit_array( 20 , 1 ) , 'label' => __( 'Number of similar posts (with sidebar)' , 'cosmotheme' ) );
    
	$similar_type_options = array('post_tag'=>__('Same tags','cosmotheme'), 'category'=> __('Same category','cosmotheme'), 'same_author' => __('Same user','cosmotheme') );
    
	options::$fields['blog_post']['similar_type']       = array('type' => 'st--select' , 'value' => $similar_type_options , 'label' => __( 'Similar posts criteria' , 'cosmotheme' ) );
    options::$fields['blog_post']['post_sharing']       = array('type' => 'st--logic-radio' , 'label' => __( 'Enable social sharing for posts' , 'cosmotheme' ) );
    options::$fields['blog_post']['post_author_box']    = array('type' => 'st--logic-radio' , 'label' => __( 'Display post author box' , 'cosmotheme' ) , 'hint' => __( 'This will enable the author box on posts.<br /> Edit description in Users > Your Profile' , 'cosmotheme' ) );
	//options::$fields['blog_post']['show_source'] 	    = array('type' => 'st--logic-radio' , 'label' => __( 'Display post source' , 'cosmotheme' )  );
	options::$fields['blog_post']['show_feat_on_archive'] = array('type' => 'st--logic-radio' , 'label' => __( 'Display featured image on archive pages' , 'cosmotheme' )  );
	options::$fields['blog_post']['enb-next-prev']      = array('type' => 'st--logic-radio' , 'label' => __( 'Display next and previous butttons' , 'cosmotheme' )  );
    options::$fields['blog_post']['enb-floating-meta']      = array('type' => 'st--logic-radio' , 'label' => __( 'Activate floating meta for full-width post' , 'cosmotheme' )  );


    options::$fields['blog_post']['post_title1']        = array('type' => 'ni--title' , 'title' => __( 'General Page Settings' , 'cosmotheme' ) );
    options::$fields['blog_post']['page_sharing']       = array('type' => 'st--logic-radio' , 'label' => __( 'Enable social sharing for page' , 'cosmotheme' ) );
    options::$fields['blog_post']['page_author_box']    = array('type' => 'st--logic-radio' , 'label' => __( 'Display page author box' , 'cosmotheme' ) , 'hint' => __( 'This will enable the author box on pages.<br /> Edit description in Users > Your Profile' , 'cosmotheme' ) );

    /*options::$fields['blog_post']['post_title2']        = array('type' => 'ni--title' , 'title' => __( 'Attachment Posts Settings' , 'cosmotheme' ) );
    options::$fields['blog_post']['attachment_sharing'] = array('type' => 'st--logic-radio' , 'label' => __( 'Enable social sharing for attachment posts' , 'cosmotheme' ) );
    options::$fields['blog_post']['attachment_comments']= array('type' => 'st--logic-radio' , 'label' => __( 'Enable comments for attachment posts' , 'cosmotheme' ) );*/

    /* POSTS DEFAULT VALUE */
    options::$default['blog_post']['post_similar_full'] = 4;
    options::$default['blog_post']['post_similar_side'] = 3;
    options::$default['blog_post']['show_similar']      = 'yes';
    options::$default['blog_post']['post_sharing']      = 'yes';
    options::$default['blog_post']['post_author_box']   = 'no';
	options::$default['blog_post']['show_source'] 		= 'yes';
	options::$default['blog_post']['show_feat_on_archive'] = 'yes';
    options::$default['blog_post']['enb-next-prev']     = 'yes';
    options::$default['blog_post']['enb-floating-meta'] = 'yes';
    options::$default['blog_post']['page_sharing']      = 'yes';
    options::$default['blog_post']['page_author_box']   = 'no';
    options::$default['blog_post']['author_sharing']    = 'no';
    //options::$default['blog_post']['attachment_sharing']= 'yes';
    options::$default['blog_post']['attachment_comments']= 'yes';
	options::$default['blog_post']['similar_type']= 'post_tag';
    

	if( options::logic( 'blog_post' , 'show_similar' ) ){
		options::$fields['blog_post']['similar_type']['classes']     = 'similar_type_class';
        options::$fields['blog_post']['post_similar_full']['classes']= 'similar_type_class';
        options::$fields['blog_post']['post_similar_side']['classes']= 'similar_type_class';
	}else{ 
		options::$fields['blog_post']['similar_type']['classes']     = 'similar_type_class hidden';
        options::$fields['blog_post']['post_similar_full']['classes']= 'similar_type_class hidden';
        options::$fields['blog_post']['post_similar_side']['classes']= 'similar_type_class hidden';
	}

    /* ADVERTISEMENT SPACES */
    options::$fields['advertisement']['logo']           = array('type' => 'st--textarea' , 'label' => __( 'Advertisement area nr. 1' , 'cosmotheme' ) , 'hint' => __( 'Insert your advertisement code here<br />This is ad area below logo' , 'cosmotheme' ) );
    options::$fields['advertisement']['content']        = array('type' => 'st--textarea' , 'label' => __( 'Advertisement area nr. 2' , 'cosmotheme' ) , 'hint' => __( 'Insert your advertisement code here<br />This is ad area below post content' , 'cosmotheme' ) );

    /* SOCIAL OPTIONS */
    /*
    
    */
    options::$fields['social']['facebook_app_id']       = array('type' => 'st--text' , 'label' => __( 'Facebook Application ID' , 'cosmotheme' ) , 'hint' => __( 'You can create a fb application from <a href="https://developers.facebook.com/apps">here</a>' , 'cosmotheme' ) );
    options::$fields['social']['facebook_secret']       = array('type' => 'st--text' , 'label' => __( 'Facebook Secret key' , 'cosmotheme' ) , 'hint' => __( 'Needed for Facebook Connect' , 'cosmotheme' ) );

    $hint = sprintf(__('Link to the following services will appear when %s header type nr. 4 %s is enabled.','cosmotheme'),'<a href="admin.php?page=cosmothemes__header">','</a>');

    options::$fields['social']['hint_title']         = array( 'type' => 'ni--title' , 'title' => $hint );
    options::$default[ 'social' ][ 'rss' ]              = 'yes';


    options::$fields[ 'social' ][ 'rss' ]               = array('type' => 'st--logic-radio' , 'label' => __( 'Show RSS icon' , 'cosmotheme' )  );
    options::$fields['social']['facebook']              = array('type' => 'st--text' , 'label' => __( 'Facebook profile ID' , 'cosmotheme' ), 'hint' => __( '(i.e. cosmo.themes)' , 'cosmotheme' )  );
    options::$fields['social']['twitter']               = array('type' => 'st--text' , 'label' => __( 'Twitter ID' , 'cosmotheme' ), 'hint' => __( '(i.e. cosmothemes)' , 'cosmotheme' ) );
    options::$fields['social']['gplus']               = array('type' => 'st--text' , 'label' => __( 'G+ public profile URL' , 'cosmotheme' ), 'hint' => __( '(i.e. https://plus.google.com/u/0/109961988357028768715)' , 'cosmotheme' ) );
    options::$fields['social']['yahoo']               = array('type' => 'st--text' , 'label' => __( 'Yahoo public profile URL' , 'cosmotheme' ), 'hint' => __( '(i.e. http://profile.yahoo.com/56W6RBFOFVLLSUQBHREPTDQW4U/)' , 'cosmotheme' ) );
    options::$fields['social']['dribbble']               = array('type' => 'st--text' , 'label' => __( 'Dribbble public profile URL' , 'cosmotheme' ), 'hint' => __( '(i.e. http://dribbble.com/creativemints)' , 'cosmotheme' ) );
    options::$fields['social']['linkedin']              = array('type' => 'st--text' , 'label' => __( 'LinkedIn public profile URL' , 'cosmotheme' ) , 'hint' => __( '(i.e. http://www.linkedin.com/company/cosmothemes)' , 'cosmotheme' ) );
    options::$fields['social']['email']                 = array('type' => 'st--text' , 'label' => __( 'Contact email' , 'cosmotheme' )  );
    options::$fields['social']['skype']                 = array('type' => 'st--text' , 'label' => __( 'Skype Name' , 'cosmotheme' )  );
    //options::$fields['social']['flickr']                = array('type' => 'st--text' , 'label' => __( 'Flickr ID' , 'cosmotheme' ) , 'hint' => __( 'Insert your Flickr ID (<a target="_blank" href="http://www.idgettr.com">idGettr</a>)' , 'cosmotheme' ) );
    
     
    /* SLIDER */
    options::$default['slider']['slideshow']            = -1;
    $sliders = get__posts( array( 'post_type' => 'slideshow' ) , '' );
    
    if( count( $sliders ) == 0 ){
        options::$fields['slider']['slide_label']       = array( 'type' => 'st--hint' , 'value' => __( 'No sliders. To create a slide go to '  , 'cosmotheme' ) . '<a href="post-new.php?post_type=slideshow">' . __( 'Add New Slideshow' , 'cosmotheme' ) . '</a>' );
    }else{
        options::$fields['slider']['slideshow']         = array('type' => 'st--search' , 'label' => __( 'Select default slideshow' , 'cosmotheme' ) , 'query' => array( 'post_type' => 'slideshow' , 'post_status' => 'publish' ) , 'hint' => __( 'Start typing the Slideshow title' , 'cosmotheme' ) , 'action' => "act.search( this , '.sl_settings')" );
    }

    options::$fields[ 'slider' ][ 'height' ] = array(
        'type' => 'st--digit',
        'label' => __( 'Slideshow height' ,  'cosmotheme'  ),
        'hint' => __( 'In pixels. Make sure to re-upload images if you change the default value.' ,  'cosmotheme'  ),
    );

    options::$fields[ 'slider' ][ 'animation' ] = array(
        'type' => 'st--select',
        'label' => __( 'Slideshow animation type' ,   'cosmotheme' ),
        'value' => array(
            'fade' => __( 'Fade' ,   'cosmotheme' ),
            'horizontal-slide' => __( 'Horizontal slide' ,   'cosmotheme'  ),
            'vertical-slide' => __( 'Vertical slide' ,   'cosmotheme'  ),
            'horizontal-push' => __( 'Horizontal push' ,   'cosmotheme'  )
        )
    );

    options::$fields[ 'slider' ][ 'animationSpeed' ] = array(
        'type' => 'st--digit',
        'label' => __( 'Slideshow play speed' ,   'cosmotheme'  ),
        'hint' => __( 'In milliseconds' ,   'cosmotheme'  )
    );

    options::$fields[ 'slider' ][ 'timer' ] = array(
        'classes' => 'timer',
        'type' => 'st--logic-radio',
        'label' => __( 'Use timer' ,   'cosmotheme'  ),
        'action' => "act.sh.multilevel.check( { 'input.slider-timer' : '.timer-options' , 'input.slider-pauseOnHover' : '.pause-on-hover-options' , 'input.slider-startClockOnMouseOut' : '.start-on-mouseout-options' } );"
    );

    options::$fields[ 'slider' ][ 'advanceSpeed' ] = array(
        'type' => 'st--digit',
        'label' => __( 'Timer timeout' ,   'cosmotheme'  ),
        'hint' => __( 'In milliseconds' ,   'cosmotheme'  ),
        'classes' => 'timer-options' . ( options::logic( 'slider'  , 'timer' ) ? '' : ' hidden' )
    );
    
    options::$fields[ 'slider' ][ 'pauseOnHover' ] = array(
        'type' => 'st--logic-radio',
        'label' => __( 'Pause on hover' ,   'cosmotheme'  ),
        'hint' => __( 'If you hover pauses the slider' ,   'cosmotheme'  ),
        'classes' => 'pauseOnHover timer-options' . ( options::logic( 'slider'  , 'timer' ) ? '' : ' hidden' ),
        'action' => "act.sh.multilevel.check( { 'input.slider-pauseOnHover' : '.pause-on-hover-options' , 'input.slider-startClockOnMouseOut' : '.start-on-mouseout-options' } );"
    );

    options::$fields[ 'slider' ][ 'startClockOnMouseOut' ] = array(
        'type' => 'st--logic-radio',
        'label' => __( 'Start clock on mouse out' ,   'cosmotheme'  ),
        'hint' => __( 'If clock should start on mouse out' ,   'cosmotheme'  ),
        'classes' => 'startClockOnMouseOut timer-options pause-on-hover-options' . ( ( options::logic( 'slider'  , 'timer' ) && options::logic( 'slider' , 'pauseOnHover' ) ) ? '' : ' hidden' ),
        'action' => "act.check( this , { 'yes' : '.start-on-mouseout-options', 'no' : '' } , 'sh' );"
    );

    options::$fields[ 'slider' ][ 'startClockOnMouseOutAfter' ] = array(
        'type' => 'st--digit',
        'label' => __( 'How long after MouseOut should the timer start again' ,   'cosmotheme'  ),
        'hint' => __( 'In milliseconds' ,   'cosmotheme'  ),
        'classes' => 'timer-options pause-on-hover-options start-on-mouseout-options' . ( ( options::logic( 'slider'  , 'timer' ) && options::logic( 'slider' , 'pauseOnHover' ) && options::logic( 'slider' , 'startClockOnMouseOut' ) ) ? '' : ' hidden' )
    );
    
    options::$fields[ 'slider' ][ 'directionalNav' ] = array(
        'type' => 'st--logic-radio',
        'label' => __( 'Show manual navigation' ,   'cosmotheme'  ),
        'hint' => __( 'Manual advancing directional navs' ,   'cosmotheme'  )
    );
    
    /* SLIDER DEFAULT VALUES */
    if( options::get_value( 'slider' , 'slideshow' ) == -1 ){
        $sliders = get_posts( array( 'post_type' => 'slideshow' ) );
		if(sizeof($sliders)){  
			options::$default['slider']['slideshow'] = $sliders[ 0 ] -> ID; 
		}  
    }
    
    if( strlen( options::get_value( 'slider' , 'slideshow') ) > 0 ){
        options::$fields['slider']['buttons']['classes']        = 'sl_settings';
        options::$fields['slider']['slidespeed']['classes']     = 'sl_settings';
        options::$fields['slider']['playspeed']['classes']      = 'sl_settings';
        options::$fields['slider']['effect']['classes']         = 'sl_settings';
        options::$fields['slider']['randomize']['classes']      = 'sl_settings';
        options::$fields['slider']['pause']['classes']          = 'sl_settings';
    }else{
        options::$fields['slider']['buttons']['classes']        = 'sl_settings hidden';
        options::$fields['slider']['slidespeed']['classes']     = 'sl_settings hidden';
        options::$fields['slider']['playspeed']['classes']      = 'sl_settings hidden';
        options::$fields['slider']['effect']['classes']         = 'sl_settings hidden';
        options::$fields['slider']['randomize']['classes']      = 'sl_settings hidden';
        options::$fields['slider']['pause']['classes']          = 'sl_settings hidden';
    }


    options::$default[ 'slider' ][ 'height' ]                   = 300;
    options::$default[ 'slider' ][ 'animation' ]                = 'fade';
    options::$default[ 'slider' ][ 'animationSpeed' ]           = 500;
    options::$default[ 'slider' ][ 'timer' ]                    = 'yes';
    options::$default[ 'slider' ][ 'advanceSpeed' ]             = 5000;
    options::$default[ 'slider' ][ 'pauseOnHover' ]             = 'yes';
    options::$default[ 'slider' ][ 'startClockOnMouseOut' ]     = 'yes';
    options::$default[ 'slider' ][ 'startClockOnMouseOutAfter' ]= 1000;
    options::$default[ 'slider' ][ 'directionalNav' ]           = 'yes';
    options::$default[ 'styling' ][ 'boxed' ]                   = 'yes';
    options::$default[ 'styling' ][ 'logo_type' ]               = 'image';
    
    /* sidebar manager */
    $struct = array(
        'layout' => 'A',
        'check-column' => array(
            'name' => 'idrow[]',
            'type' => 'hidden'
        ),
        'info-column-0' => array(
            0 => array(
                'name' => 'title',
                'type' => 'text',
                'label' => 'Sidebar Title',
                'classes' => 'sidebar-title'
            )
        ),
        'select' => 'title',
        'actions' => array( 'sortable' => true )
    );

    /* delete_option( '_sidebar' ); */
    /* SOCIAL OPTIONS */
    options::$fields['_sidebar']['idrow']               = array('type' => 'st--m-hidden' , 'value' => 1 , 'id' => 'sidebar_title_id' , 'single' => true );
    options::$fields['_sidebar']['title']               = array('type' => 'st--text' , 'label' => __( 'Set title for new sidebar','cosmotheme' ) , 'id' => 'sidebar_title' , 'single' => true );
    options::$fields['_sidebar']['save']                = array('type' => 'st--button' , 'value' => 'Add new sidebar' , 'action' => "extra.add( '_sidebar' , { 'input' : [ 'sidebar_title_id' , 'sidebar_title'] })" );

    options::$fields['_sidebar']['struct']              = $struct;
    options::$fields['_sidebar']['hint']                = __( 'List of generic dynamic sidebars<br />Drag and drop blocks to rearrange position' , 'cosmotheme' );

    options::$fields['_sidebar']['list']                = array( 'type' => 'ex--extra' , 'cid' => 'container__sidebar');
    
    /* Custom css */
    options::$fields['custom_css']['css']               = array('type' => 'st--textarea' , 'label' => __( 'Add your custom CSS' , 'cosmotheme' )  );
    

    /*Cosmothemes options*/

	options::$default['cosmothemes']['show_new_version']      = 'yes';
	options::$default['cosmothemes']['show_cosmo_news']      = 'yes';
	options::$fields['cosmothemes']['show_new_version'] = array( 'type' => 'st--logic-radio' , 'label' => __( 'Enable notification about new theme version' , 'cosmotheme' ) );
	options::$fields['cosmothemes']['show_cosmo_news']  = array( 'type' => 'st--logic-radio' , 'label' => __( 'Enable Cosmothemes news notification' , 'cosmotheme' ) );

    /* tooltips */
    $type = array( 'left' => __( 'Left' , 'cosmotheme' ) , 'right' => __( 'Right' , 'cosmotheme' ) , 'top' => __( 'Top' , 'cosmotheme' ) );
    $res_type = array( 'front_page' => __( 'On front page' , 'cosmotheme' ) , 'single' => __( 'On single post' , 'cosmotheme' ) , 'page' => __( 'On simple page' , 'cosmotheme' ) );
    $res_pages = get__pages( __( 'Select Page' , 'cosmotheme' ) );
    $tooltips = array(
        'layout' => 'A',
        'check-column' => array(
            'name' => 'idrow',
            'type' => 'hidden',
            'classes' => 'idrow'
        ),
        'info-column-0' => array(
            0 => array(
                'name' => 'title',
                'type' => 'text',
                'label' => 'Tooltip title',
                'classes' => 'tooltip-title',
                'before' => '<strong>',
                'after' => '</strong>',
            ),
            1 => array(
                'name' => 'description',
                'type' => 'textarea',
                'label' => 'Tooltip description',
                'classes' => 'tooltip-description'
            ),
            2 => array(
                'name' => 'res_type',
                'type' => 'select',
                'assoc' => $res_type,
                'label' => 'Location',
                'lvisible' => false,
                'classes' => 'tooltip-res-type',
                'action' => array( 'single' => 'res_posts' , 'page' => 'res_pages' , 'method' => 'sh_' ),
            ),
            3 => array(
                'name' => 'res_posts',
                'type' => 'search',
                'query' => array( 'post_type' => 'post' , 'post_status' => 'publish' ),
                'label' => '',
                'lvisible' => false,
                'classes' => 'tooltip-res-posts',
                'linked' => array( 'res_type' , 'single' ),
            ),
            4 => array(
                'name' => 'res_pages',
                'type' => 'select',
                'assoc' => $res_pages,
                'label' => '',
                'lvisible' => false,
                'classes' => 'tooltip-res-pages',
                'linked' => array( 'res_type' , 'page' ),
            ),
            5 => array(
                'name' => 'top',
                'type' => 'text',
                'label' => 'Top position',
                'lvisible' => false,
                'classes' => 'tooltip-top'
            ),
            6 => array(
                'name' => 'left',
                'type' => 'text',
                'label' => 'Left position',
                'lvisible' => false,
                'classes' => 'tooltip-left'
            ),
            7 => array(
                'name' => 'type',
                'type' => 'select',
                'assoc' => $type,
                'label' => 'Arrow position',
                'lvisible' => false,
                'classes' => 'tooltip-type'
            ),
        ),
        'actions' => array( 'sortable' => true )
    );
    
    $res_action = "act.select( '#tooltip_res_type' , { 'single' : '.res_posts' , 'page': '.res_pages'  } , 'sh_' )";
    
    options::$fields['_tooltip']['idrow']               = array('type' => 'st--hidden' , 'value' => 1 , 'id' => 'tooltip_id' , 'single' => true);
    options::$fields['_tooltip']['title']               = array('type' => 'st--text' , 'label' => __( 'Set title for new tooltip','cosmotheme' ) , 'id' => 'tooltip_title' , 'single' => true);
    options::$fields['_tooltip']['description']         = array('type' => 'st--textarea' , 'label' => __( 'Set description for new tooltip','cosmotheme' ) , 'id' => 'tooltip_description' , 'single' => true );
    options::$fields['_tooltip']['res_type']            = array('type' => 'st--select' , 'label' => __( 'Select tooltip location' , 'cosmotheme' ) , 'value' =>  $res_type , 'action' => $res_action , 'id' => 'tooltip_res_type' , 'single' => true );
    options::$fields['_tooltip']['res_posts']           = array('type' => 'st--search' , 'label' => __( 'Select post' , 'cosmotheme' ) , 'hint' => 'Start typing the post tile' , 'classes' => 'res_posts hidden' , 'id' => 'tooltip_res_posts' , 'single' => true , 'query' => array( 'post_type' => 'post' , 'post_status' => 'publish' ) , 'action' => "act.search( this , '-');" );
    options::$fields['_tooltip']['res_pages']           = array('type' => 'st--select' , 'label' => __( 'Select page' , 'cosmotheme' ) , 'value' => $res_pages , 'classes' => 'res_pages hidden' , 'id' => 'tooltip_res_pages' , 'single' => true );
    options::$fields['_tooltip']['top']                 = array('type' => 'st--text' , 'label' => __( 'Set top position for new tooltip','cosmotheme' )  , 'hint' => __( 'In pixels. E.g.: 450' , 'cosmotheme' ) , 'id' => 'tooltip_top' , 'single' => true );
    options::$fields['_tooltip']['left']                = array('type' => 'st--text' , 'label' => __( 'Set left position for new tooltip','cosmotheme' )  , 'hint' => __( 'In pixels. E.g.: 200' , 'cosmotheme' )  , 'id' => 'tooltip_left' , 'single' => true );
    options::$fields['_tooltip']['type']                = array('type' => 'st--select' , 'label' => __( 'Set arrow position for new tooltip','cosmotheme' ) , 'id' => 'tooltip_type' , 'value' => $type , 'single' => true );
    options::$fields['_tooltip']['save']                = array('type' => 'st--button' , 'value' => __( 'Add new tooltip' , 'cosmotheme' ) , 'action' => "extra.add( '_tooltip' , { 'input' : [ 'tooltip_id' , 'tooltip_title' , 'tooltip_top' , 'tooltip_left', 'tooltip_res_posts' ] , 'textarea' : 'tooltip_description' , 'select' : ['tooltip_type' , 'tooltip_res_type' ,  'tooltip_res_pages' ] })" );
    
    options::$fields['_tooltip']['struct']              = $tooltips;
    options::$fields['_tooltip']['hint']                = __( 'List of generic tooltips<br /> Drag and drop blocks to rearrange position' , 'cosmotheme' );

    options::$fields['_tooltip']['list']                = array( 'type' => 'ex--extra' , 'cid' => 'container__tooltip');

    if( isset( $_GET[ 'page' ] ) && $_GET[ 'page' ] == 'cosmothemes__io' ){
        $export = array();
        foreach( options::$menu['cosmothemes'] as $option_name => $whatever ){
            $export[$option_name] = get_option( $option_name );
        }
        $exportdata = base64_encode( json_encode( $export ) );
    }else{
        $exportdata = '';
    }

    options::$fields[ 'io' ][ 'warning' ] = array(
        'type' => 'cd--whatever',
        'content' => '<h2 class="import-warning">' . __( 'Warning! You WILL lose all your current settings FOREVER', 'cosmotheme' ) . '<br>'
            . __( 'if you paste the import data and click "Update settings".', 'cosmotheme' ) . '<br>'
            . __( 'Double check everything!', 'cosmotheme' ) . '</h2><b class="import-warning">' . __( 'Please check settings where pages are assigned. If there is something wrong set them manually.', 'cosmotheme' ) . '</b><div class="clear">&nbsp;</div>'
    );

    options::$fields[ 'io' ][ 'export' ] = array(
        'label' => __( 'This is the export data', 'cosmotheme' ),
        'hint' => __( 'Just copy-paste it', 'cosmotheme' ),
        'type' => 'st--textarea',
        'value' => $exportdata
    );

    options::$fields[ 'io' ][ 'import' ] = array(
        'label' => __( 'This is the import zone', 'cosmotheme' ),
        'hint' => __( 'Paste the import data here', 'cosmotheme' ),
        'type' => 'st--textarea',
        'value' => ''
    );

    if( isset( $_POST[ 'io' ] ) && isset( $_POST[ 'io' ][ 'import' ] ) && strlen( trim( $import = $_POST[ 'io' ][ 'import' ] ) ) ){
        $import = @json_decode( base64_decode( $import ) );
        if( is_array( $import ) && count( $import ) ){
            foreach( $import as $name => $value ){
                update_option( $name, $value );
            }
        }
    }
    
    options::$register['cosmothemes']                   = options::$fields;
?>
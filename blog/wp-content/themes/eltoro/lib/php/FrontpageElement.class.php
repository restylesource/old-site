<?php
	class FrontpageElement{
        private $words_full_width = array(
            1 => 'twelve',
            2 => 'six',
            3 => 'four',
            4 => 'three',
            5 => 'three',
            6 => 'two',
            7 => 'two',
            8 => 'one',
            9 => 'one',
            10 => 'one',
            11 => 'one',
            12 => 'one',
        );

        private $words_sidebar = array(
            1 => 'nine',
            2 => 'nine',
            3 => 'three',
            4 => 'three',
            5 => 'three',
            6 => 'three',
            7 => 'three',
            8 => 'three',
            9 => 'one',
            10 => 'one',
            11 => 'one',
            12 => 'one',
        );

		function __construct( $data ){
			$this -> name = __( 'New element' , 'cosmotheme' );
			$this -> type = 'category';
			$this -> id = '_id_';
			$this -> view = 'list_view';
			$this -> categories = array();
			$this -> tags = array();
			$this -> portfolios = array();
            $this -> page = -1;
            $this -> post = -1;
            $this -> carousel = 'no';
            $this -> pagination = 'no';
            $this -> load_more = 'no';
            $this -> numberposts = 12;
            $this -> list_thumbs = 'yes';
            $this -> sidebar = options::get_value( 'layout', 'front_page' );
            $this -> columns = 2;
            $this -> show_title = 'yes';
            $this ->  is_ajax = false;
            $this -> is_last = false;

            if ((int) get_query_var('paged') > 0) {
                $this -> paged = get_query_var('paged');
            } else {
                if ((int) get_query_var('page') > 0) {
                    $this -> paged = get_query_var('page');
                } else {
                    $this -> paged = 1;
                }
            }

            foreach( $data as $key => $val ){
                if( ( is_array( $val ) && count( $val ) ) || strlen( $val ) ){
                    $this -> {$key} = $val;
                }
            }

            $layout = get_option( 'layout' );
            if( !is_array( $layout ) ){
                $layout = array();
            }
            $layout[ 'front_page' ] = $this -> sidebar;

            $this -> sidebar_class = 'wsidebar';
			if(options::get_value( 'layout' , 'front_page' ) == 'full'){
				$this -> sidebar_class = 'nosidebar';
			}

            /*set number of posts to 6 for list_view_thumbs*/
            if($this -> view == 'list_view' && $this -> list_thumbs == 'yes'){
                $this -> numberposts = 6;
            }

		}

        function columns_arabic_to_word( $arabic ){
            if(options::get_value( 'layout' , 'front_page' ) == 'full'){
                return $this -> words_full_width[ $arabic ];
            }else{
                return $this -> words_sidebar[ $arabic ];
            }
        }

        function list_posts(){
            $posts = get_posts( array( 'numberposts' => 100 ) );
            if( !is_wp_error( $posts ) && is_array( $posts ) && count( $posts ) ){
                echo '<p>' . __( 'Here are the latest posts. Pick one or use the searchbar above', 'cosmotheme' ) . '</p>';
                if( $this -> post > 0 ){
                    $post = get_post( $this -> post );
                    ?>
                    <label class="taxonomy added">
                        <?php echo $post -> post_title;?>
                        <input name="front_page[elements][<?php echo $this -> id;?>][post]" type="radio" value="<?php echo $post -> ID;?>" checked="checked">
                    </label>
                <?php
                }
                foreach( $posts as $post ){
                    if( $post -> ID == $this -> post ){
                        continue;
                    }
                    ?>
                    <label class="taxonomy">
                        <?php echo $post -> post_title;?>
                        <input name="front_page[elements][<?php echo $this -> id;?>][post]" type="radio" value="<?php echo $post -> ID;?>">
                    </label>
                <?php
                }
            }else{
                echo '<p>' . __( 'There are no pages' , 'cosmotheme' ) . '</p>';
            }
        }

        function list_pages(){
            if( $this -> page > 0 ){
                $page = get_page( $this -> page );
                ?>
                    <label class="taxonomy added">
                        <?php echo $page -> post_title;?>
                        <input name="front_page[elements][<?php echo $this -> id;?>][page]" type="radio" value="<?php echo $page -> ID;?>" checked="checked">
                    </label>
                <?php
            }
            $pages = get_pages();
            if( !is_wp_error( $pages ) && is_array( $pages ) && count( $pages ) ){
                foreach( $pages as $page ){
                    if( $page -> ID == $this -> page ){
                        continue;
                    }
                    ?>
                <label class="taxonomy">
                    <?php echo $page -> post_title;?>
                    <input name="front_page[elements][<?php echo $this -> id;?>][page]" type="radio" value="<?php echo $page -> ID;?>">
                </label>
                <?php
                }
            }else{
                echo '<p>' . __( 'There are no pages' , 'cosmotheme' ) . '</p>';
            }
        }

		function list_categories(){
			foreach( $this -> categories as $cat_ID ){
					$category = get_category( $cat_ID );
				?>
					<label class="taxonomy added">
						<?php echo $category -> cat_name;?>
						<input name="front_page[elements][<?php echo $this -> id;?>][categories][]" type="checkbox" value="<?php echo $category -> cat_ID;?>" checked="checked">
					</label>
				<?php
			}
			$categories = get_categories( array( 'hide_empty' => false ) );
			if( !is_wp_error( $categories ) && is_array( $categories ) && count( $categories ) ){
				foreach( $categories as $category ){
						if( in_array( $category -> cat_ID, $this -> categories ) ){
							continue;
						}
					?>
						<label class="taxonomy">
							<?php echo $category -> cat_name;?>
							<input name="front_page[elements][<?php echo $this -> id;?>][categories][]" type="checkbox" value="<?php echo $category -> cat_ID;?>">
						</label>
					<?php
				}
			}else{
				echo '<p>' . __( 'There are no non-empty categories' , 'cosmotheme' ) . '</p>';
			}
		}

		function list_tags(){
			foreach( $this -> tags as $tag_ID ){
				$tag = get_tag( $tag_ID );
				?>
					<label class="taxonomy added">
						<?php echo $tag -> name;?>
						<input name="front_page[elements][<?php echo $this -> id;?>][tags][]" type="checkbox" value="<?php echo $tag -> term_id;?>" checked="checked">
					</label>
				<?php
			}
			$tags = get_tags( array( 'hide_empty' => false ) );
			if( !is_wp_error( $tags ) && is_array( $tags ) && count( $tags ) ){
				foreach( $tags as $tag ){
                    if( in_array( $tag -> term_id, $this -> tags ) ){
                        continue;
                    }
					?>
						<label class="taxonomy">
							<?php echo $tag -> name;?>
							<input name="front_page[elements][<?php echo $this -> id;?>][tags][]" type="checkbox" value="<?php echo $tag -> term_id;?>">
						</label>
					<?php
				}
			}else{
				echo '<p>' . __( 'There are no non-empty tags' , 'cosmotheme' ) . '</p>';
			}
		}

		function list_portfolios(){
            foreach( $this -> portfolios as $term_ID ){
                $term = get_term( $term_ID, 'portfolio' );
                ?>
            <label class="taxonomy added">
                <?php echo $term -> name;?>
                <input name="front_page[elements][<?php echo $this -> id;?>][portfolios][]" type="checkbox" value="<?php echo $term -> term_id;?>" checked="checked">
            </label>
            <?php
            }
			$portfolios = get_terms( 'portfolio', array( 'hide_empty' => false ) );
			if( !is_wp_error( $portfolios ) && is_array( $portfolios ) && count( $portfolios ) ){
				foreach( $portfolios as $portfolio ){
                        if( in_array( $portfolio -> term_id, $this -> portfolios ) ){
                            continue;
                        }
					?>
						<label class="taxonomy">
							<?php echo $portfolio -> name;?>
							<input name="front_page[elements][<?php echo $this -> id;?>][portfolios][]" type="checkbox" value="<?php echo $portfolio -> term_id;?>">
						</label>
					<?php
				}
			}else{
				echo '<p>' . __( 'There are no non-empty portfolios' , 'cosmotheme' ) . '</p>';
			}
		}

		function render_backend(){
			include get_template_directory() . '/lib/templates/frontpageelement.php';
		}

		function render_frontend(){
			$type = $this -> type;
			call_user_func( array ( $this, "render_frontend_$type" ) );
            wp_reset_query();
            if( !$this -> is_last && !( $this -> load_more == 'yes' && $this -> pagination != 'yes' && !( $this -> carousel == 'yes' && $this -> view != 'list_view' ) && !( $this -> list_thumbs == 'yes' && $this -> view == 'list_view' ) ) ){
                echo '<div class="delimiter"></div>';
            }
		}


        /**************************************************************************************/
        /*------------------------------ FRONT  END OUTPUT IMPLEMENTATION --------------------*/
        /**************************************************************************************/

        function render_frontend_posts_grid_view_thumbnails_carousel( $posts ){
            $rnd = mt_rand(0,300);

            echo '<div class="row">';
                echo '<div class="'.tools::primary_class( 0 , 'front_page', $return_just_class = true ).'">';
                    echo '<section class="ff-container">';
                        echo '<div class="row thumbs-view ">';
                        if($this -> show_title == 'yes'){ ?>
                            <div class=" carousel-title" >

                                <h2 class="content-title"><?php echo $this -> name; ?></h2>
                            </div>
                        <?php }
                            echo '<ul  class="thumbs-list image-grid ourcarousel columns-'.$this -> columns.' carousel-'.$rnd.'"  id="carousel-'.$rnd.'" columns="'.$this -> columns.'">';
                                $counter = 1;
                                foreach( $posts as $post ){
                                    $additional_class = '';
                                    if( ($counter % $this -> columns) == 1 ){
                                        $additional_class = 'first-elem';
                                    }
                                    post::grid_view_thumbnails($post, $this -> columns_arabic_to_word( $this -> columns ) . ' columns', $additional_class);
                                    $counter++;
                                }

                                if( (count($posts) % $this -> columns) > 0 ){
                                    $n = $this -> columns - (count($posts) % $this -> columns) ;
                                    for ($i=0; $i < $n; $i++) {
                                        echo '<li data-id="" class="'.$this -> columns_arabic_to_word( $this -> columns ).' columns">';
                                        echo '</li>';
                                    }
                                }
                            echo '</ul>';
                        echo '</div>';
                    echo '<section>';
                echo '</div>';
            echo '</div>';
        }

        function render_frontend_posts_grid_view_carousel( $posts ){

            $rnd = mt_rand(0,300);

            echo '<div class="row">';
                echo '<div class="'.tools::primary_class( 0 , 'front_page', $return_just_class = true ).'">';
                    echo '<section class="ff-container">';
                        echo '<div class="row grid-view ">';
                            if($this -> show_title == 'yes'){ ?>
                                <div class="row carousel-title">

                                    <h2 class="content-title"><?php echo $this -> name; ?></h2>
                                </div>
                            <?php }
                            echo '<ul  class="image-grid ourcarousel columns-'.$this -> columns.' carousel-'.$rnd.'"  id="carousel-'.$rnd.'" columns="'.$this -> columns.'">';
                                $counter = 1;
                                foreach( $posts as $post ){
                                    $additional_class = '';
                                    if( ($counter % $this -> columns) == 1 ){
                                        $additional_class = 'first-elem';
                                    }
                                    call_user_func( array( 'post', $this -> view ), $post, $this -> columns_arabic_to_word( $this -> columns ) . ' columns',$additional_class );
                                    $counter++;
                                }

                                if( (count($posts) % $this -> columns) > 0 ){
                                    $n = $this -> columns - (count($posts) % $this -> columns) ;
                                    for ($i=0; $i < $n; $i++) {
                                        echo '<li data-id="" class="'.$this -> columns_arabic_to_word( $this -> columns ).' columns">';
                                        echo '</li>';
                                    }
                                }
                            echo '</ul>';
                        echo '</div>';
                    echo '<section>';
                echo '</div>';
            echo '</div>';
        }

        function render_frontend_posts_grid_view_thumbnails( $posts ){
            $filter_type_rand = mt_rand(1,3000); /* generate a random number to have distinct data-value for filters */

            $taxonomy = 'portfolio';
            switch ($this -> type) {
                case 'tag':
                    $tags = array();
                    foreach( $this -> tags as $term_id ){
                        $tags[] = $term_id ;

                    }

                    $term_ids = $tags;
                    $taxonomy = 'post_tag';
                    break;

                case 'portfolio':
                    $term_ids = $this -> portfolios;
                    $taxonomy = 'portfolio';
                    break;

                case 'category':
                    /*category*/
                    $term_ids = $this -> categories;
                    $taxonomy = 'category';
                    break;

                default:
                    break;
            }

            if(!($this ->  is_ajax)){
            echo '<div class="row thumbs-view">';
                echo '<div class="'.tools::primary_class( 0 , 'front_page', $return_just_class = true ).'">';
                    echo '<section class="thumbs-container">';

                        $filter_class = '';

                        if($this -> show_title == 'yes'){
                            $title = '<h2 class="content-title filter">'. $this -> name.'</h2>';

                        }else{

                            $title = '';
                        }

                        if($this -> load_more == 'no' && $this -> pagination == 'no' && $this -> carousel == 'no'){
                            

                            if(isset($term_ids) && isset($taxonomy)){
                                $filters_container = get_filters($term_ids,$taxonomy ,$filter_type = $filter_type_rand, $title = $title);
                            }



                            if(isset($filters_container) && strlen($filters_container)){
                                $filter_class = 'filter-on';

                                    echo $filters_container;
                                    echo '<div class="clear"></div>';

                            }else if($this -> show_title == 'yes'){
                                echo '<h2 class="content-title">'.$this -> name.'</h2>';
                                echo '<div class="clear"></div>';
                            }
                        }else if($this -> show_title == 'yes'){
                            echo '<h2 class="content-title">'.$this -> name.'</h2>';

                            echo '<div class="clear"></div>';
                        }

                        $ul_id = mt_rand(1,9999);
                        echo '<ul id="ul-'.$ul_id.'" class=" thumbs-list image-grid columns-'. $this -> columns .' ' . $filter_class . ' ">';
            }   /*EOF if(!$this ->  is_ajax)*/

                            $counter = 1;

                            foreach( $posts as $post ){
                                $additional_class = '';
                                if( ($counter % $this -> columns) == 1 ){
                                    $additional_class = 'first-elem';

                                }
                                if(($this ->  is_ajax)){ $additional_class .= ' hidden ';}

                                call_user_func( array( 'post', $this -> view ), $post, $this -> columns_arabic_to_word( $this -> columns ) . ' columns', $additional_class, $filter_type = $filter_type_rand , $taxonomy = $taxonomy);
                                $counter++;
                            }

            if(!($this ->  is_ajax)){

                        echo '</ul>';
                    echo '</section>';
                echo '</div>';
            echo '</div>';
            if($this -> load_more == 'yes' && 'no' == $this -> pagination){
                $load_more_btn = sprintf(__( 'Load %s more %s ', 'cosmotheme' ), '<span>','</span>');
                $view = 'grid_view_thumbnails';
            ?>
                <div class="thick-ajax-loader ajax-ul-<?php echo $ul_id; ?> ajax-loader" style="display:none"></div>
                <div class="row">

                    <div class="load-more" container_id="ul-<?php echo $ul_id; ?>" current_page="1" onclick="load_more(jQuery(this),'<?php echo $view; ?>','<?php echo $this -> type; ?>',<?php echo $this->id; ?>);">
                        <?php echo $load_more_btn; ?>
                    </div>
                </div>
            <?php
            }

            }   /*EOF if(!$this ->  is_ajax)*/
        }

        function render_frontend_posts_grid_view( $posts ){
            if(!($this ->  is_ajax)){
            if($this -> show_title == 'yes'){ ?>
                <div class="row">
                    <h2 class="content-title"><?php echo $this -> name; ?></h2>
                </div>
            <?php }    
        	echo '<div class="row">';
				echo '<div class="'.tools::primary_class( 0 , 'front_page', $return_just_class = true ).'">';
					echo '<section class="ff-container">';

			            echo '<div class="row grid-view '.$this -> sidebar_class.'">';
                             $ul_id = mt_rand(1,9999);
			                echo '<ul id="ul-'.$ul_id.'" class="image-grid columns-'. $this -> columns .' ">';
            } /*EOF if(!$this ->  is_ajax)*/

                                $counter = 1;

			                    foreach( $posts as $post ){
                                    $additional_class = '';
                                    if( ($counter % $this -> columns) == 1 ){
                                        $additional_class = 'first-elem';
                                    }
                                    if( $this -> is_ajax ){
                                        $additional_class .= ' hidden';
                                    }

			                        call_user_func( array( 'post', $this -> view ), $post, $this -> columns_arabic_to_word( $this -> columns ) . ' columns', $additional_class );
                                    if( ($counter % $this -> columns) == 0){
                                        echo '<li class="clear"></li>';
                                    }

                                    $counter ++;
			                    }

            if(!($this ->  is_ajax)){

			                echo '</ul>';
			            echo '</div>';

					echo '</section>';
				echo '</div>';
            echo '</div>';

            if($this -> load_more == 'yes'  && 'no' == $this -> pagination){
                $load_more_btn = sprintf(__( 'Load %s more %s ', 'cosmotheme' ), '<span>','</span>');
                $view = 'grid_view';
            ?>
                <div class="thick-ajax-loader ajax-ul-<?php echo $ul_id; ?> ajax-loader" style="display:none"></div>
                <div class="row">

                    <div class="load-more" container_id="ul-<?php echo $ul_id; ?>" current_page="1" onclick="load_more(jQuery(this),'<?php echo $view; ?>','<?php echo $this -> type; ?>',<?php echo $this->id; ?>);">
                        <?php echo $load_more_btn; ?>
                    </div>
                </div>
            <?php
            }

            } /*EOF if(!$this ->  is_ajax)*/
        }

        function render_frontend_posts_list_view_thumbs($posts){

            if(options::get_value( 'layout' , 'front_page' ) == 'full'){
                $fullwidth = true;
                $footer_class = "four columns";
            }else{
                $fullwidth = false;
                $footer_class = "row";
            }

            if($this -> show_title == 'yes'){ ?>
                <div class="row">
                    <h2 class="content-title"><?php echo $this -> name; ?></h2>
                </div>
            <?php } 
            echo '<div class="row list-view">';
                echo '<article class="post row list-tabs" id="list-big-'.$this->id.'">';
                    $counter = 1;
                    foreach( $posts as $post ){
                        if($counter == 1){
                            $display= 'block';
                        }else{
                            $display= 'none';
                        }
                        call_user_func( array( 'post', 'list_view_thumbs' ), $post, $display = $display  );

                        $counter++;
                    }
                    echo '<footer class="'.$footer_class.'">';
                        echo '<ul>';
                        foreach( $posts as $post ){
                            post::list_view_thumbs_small($post);

                        }
                        echo '</ul>';
                    echo '</footer>';
                echo '</article>';   
            echo '</div>';   
        }

        function render_frontend_posts_list_view( $posts ){
            if(!($this ->  is_ajax)){
                if($this -> show_title == 'yes'){ ?>
                    <div class="row">
                
                        <h2 class="content-title"><?php echo $this -> name; ?></h2>
                    </div>
                <?php } 
            

                $ul_id = mt_rand(1,9999);
                echo '<div id="div-'.$ul_id.'" class="row list-view">';

            }
                foreach( $posts as $post ){
                    $additional_hidden_class_for_load_more = '';
                    if( $this -> is_ajax ){
                        $additional_hidden_class_for_load_more = 'hidden';
                    }
                    call_user_func( array( 'post', $this -> view ), $post, 'front_page', $additional_hidden_class_for_load_more );
                }

            if(!($this ->  is_ajax)){
                echo '</div>';

                if($this -> load_more == 'yes'  && 'no' == $this -> pagination){
                    $load_more_btn = sprintf(__( 'Load %s more %s ', 'cosmotheme' ), '<span>','</span>');
                    $view = 'list_view'; 
                ?>    
                    <div class="ajax-div-<?php echo $ul_id; ?> ajax-loader" style="display:none"></div>
                    <div class="row">
                        
                        <div class="load-more" container_id="div-<?php echo $ul_id; ?>" current_page="1" onclick="load_more(jQuery(this),'<?php echo $view; ?>','<?php echo $this -> type; ?>',<?php echo $this->id; ?>);">
                            <?php echo $load_more_btn; ?>
                        </div>
                    </div>
                <?php
                }
            }    
        }

        /*Below are the methods that will initialize each content type on front page*/
        /*==========================================================================*/
        /****************************************************************************/

        function no_posts_found(){
            ?>
                <div class="no-posts-found">
                    <?php echo __( 'No posts found' , 'cosmotheme' );?>
                </div>
            <?php
        }

        function render_frontend_category(){  /*CATEGORIES*/
            global $wp_query;
            if( is_array( $this -> categories ) && count( $this -> categories ) ){
                $categories = implode( ',', $this -> categories );
                $wp_query = new WP_Query( array(
                        'cat' => $categories,
                        'posts_per_page' => $this -> numberposts,
                        'fp_element' => $this -> id,
                        'paged' => $this -> paged
                    )
                );
                $this -> render_frontend_posts( $wp_query -> posts );
            }else{
                $this -> no_posts_found();
            }
        }
        
        function render_frontend_tag(){     /*TAGS*/
            if( is_array( $this -> tags ) && count( $this -> tags ) ){
                $tags = array();
                foreach( $this -> tags as $term_id ){
                    $tag = get_tag( $term_id );
                    $tags[] = $tag -> slug;
                }
                $tags = implode( ',', $tags );
                global $wp_query;
                $wp_query = new WP_Query( array(
                        'tag' => $tags,
                        'posts_per_page' => $this -> numberposts,
                        'fp_element' => $this -> id,
                        'paged' => $this -> paged
                    )
                );
                $this -> render_frontend_posts( $wp_query -> posts );
            }else{
                $this -> no_posts_found();
            }
        }

        function render_frontend_portfolio(){ /*PORTFOLIOS*/
            if( is_array( $this -> portfolios ) && count( $this -> portfolios ) ){
                $portfolios = array();
                foreach( $this -> portfolios as $term_id ){
                    $term = get_term( $term_id, 'portfolio' );
                    $portfolios[] = $term -> name;
                }
                $portfolios = implode( ',', $portfolios );
                global $wp_query;
                $wp_query = new WP_Query( array(
                        'portfolio' => $portfolios,
                        'posts_per_page' => $this -> numberposts,
                        'fp_element' => $this -> id,
                        'paged' => $this -> paged
                    )
                );
                $this -> render_frontend_posts( $wp_query -> posts );
            }else{
                $this -> no_posts_found();
            }
        }

        function render_frontend_featured(){  /*FEATURED POSTS*/
            
            global $wp_query;
            if ( 'yes' == $this -> pagination  || 'yes' == $this -> load_more) {
                $wp_query = new WP_Query(array(
                    'post_status' => 'publish',
                    'paged' => $this -> paged,
                    'posts_per_page' => $this -> numberposts,
                    'meta_key' => 'hot_date',
                    'orderby' => 'meta_value_num',
                    'fp_element' => $this -> id,
                    'meta_query' => array(
                        array(
                            'key' => 'nr_like',
                            'value' => options::get_value('general', 'min_likes'),
                            'compare' => '>=',
                            'type' => 'numeric',
                        )),
                    'order' => 'DESC'));
            }else{
                $wp_query = new WP_Query(array(
                    'post_status' => 'publish',
                    'posts_per_page' => $this -> numberposts,
                    'meta_key' => 'hot_date',
                    'orderby' => 'meta_value_num',
                    'fp_element' => $this -> id,
                    'meta_query' => array(
                        array(
                            'key' => 'nr_like',
                            'value' => options::get_value('general', 'min_likes'),
                            'compare' => '>=',
                            'type' => 'numeric',
                        )),
                    'order' => 'DESC'));
            }
            $this -> render_frontend_posts( $wp_query -> posts );
   

        }

        function render_frontend_latest(){      /*LATEST POSTS*/
            

            global $wp_query;
            if ( 'yes' == $this -> pagination || 'yes' == $this -> load_more) {
                $wp_query = new WP_Query(array( 'post_status' => 'publish', 
                                                'post_type' => 'post', 
                                                'paged' => $this -> paged , 
                                                'fp_element' => $this -> id, 
                                                'posts_per_page' => $this -> numberposts ) );
            } else {
                $wp_query = new WP_Query(array( 'post_status' => 'publish', 
                                                'post_type' => 'post', 
                                                'fp_element' => $this -> id, 
                                                'posts_per_page' => $this -> numberposts ) );
            }
            $this -> render_frontend_posts( $wp_query -> posts );
        }

		
        function render_frontend_page(){       /*PAGES*/
            global $wp_query;
            $wp_query = new WP_Query(array( 'page_id' => $this -> page ) );
            if(count($wp_query -> posts)){
                the_post();
                global $post;
                $post = $wp_query -> posts[0];
                if($this -> show_title == 'yes'){ ?>
                    <div class="row">
                
                        <h2 class="content-title"><?php echo $this -> name; ?></h2>
                    </div>
                <?php }
                get_template_part('single-content');
            }
            wp_reset_query();
        }

        function render_frontend_post(){        /*POSTS*/
            global $wp_query;
            $wp_query = new WP_Query(array( 'post__in' => array($this -> post) ) );
            global $post;
            $post = $wp_query -> posts[0];
            if(count($wp_query -> posts)){
                the_post();
                if($this -> show_title == 'yes'){ ?>
                    <div class="row">
                        <h2 class="content-title"><?php echo $this -> name; ?></h2>
                    </div>
                <?php } 
                get_template_part('single-content');
            }
            wp_reset_query();
        }

        function render_frontend_widget_zone(){     /*WIDGETS*/
            
            echo '<div class="row">';
                dynamic_sidebar( $this -> name );
            echo '</div>';
        }

        function render_frontend_posts( $posts ){
            $pagination_allowed = true;
            if( 'grid_view_thumbnails' == $this -> view ){ 
                if($this -> carousel == 'yes'){
                    $this -> render_frontend_posts_grid_view_thumbnails_carousel( $posts );
                    $pagination_allowed = false;
                }else{
                    $this -> render_frontend_posts_grid_view_thumbnails( $posts );
                }
            }else if( 'grid_view' == $this -> view ){
                if($this -> carousel == 'yes'){
                    $this -> render_frontend_posts_grid_view_carousel( $posts );
                    $pagination_allowed = false;
                }else{
                    $this -> render_frontend_posts_grid_view( $posts );
                }
            }else if( 'list_view' == $this -> view ){
                if($this -> list_thumbs == 'yes'){
                    $this -> render_frontend_posts_list_view_thumbs( $posts );
                    $pagination_allowed = false;
                }else{
                    $this -> render_frontend_posts_list_view( $posts );
                }
            }else{
                foreach( $posts as $post ){
                    call_user_func( array( 'post', $this -> view ), $post );
                }
            }

            if( 'yes' == $this -> pagination  && $pagination_allowed){
                get_template_part( 'pagination' );
            }
        }
	}
?>
<?php
	class FrontpageBuilder{
		public $scriptdata = array();
		public $elements = array();
		function __construct(){
			$this -> scriptdata[ 'translations' ][ 'add_element' ] = __( 'New element' , 'cosmotheme' );
            $this -> scriptdata[ 'translations' ][ 'showing' ] = __( 'Showing' , 'cosmotheme' );
            $this -> scriptdata[ 'translations' ][ 'widgets' ] = __( 'widgets' , 'cosmotheme' );
            $this -> scriptdata[ 'translations' ][ 'page' ] = __( 'page' , 'cosmotheme' );
            $this -> scriptdata[ 'translations' ][ 'post' ] = __( 'post' , 'cosmotheme' );
            $this -> scriptdata[ 'translations' ][ 'posts' ] = __( 'posts' , 'cosmotheme' );
            $this -> scriptdata[ 'translations' ][ 'from' ] = __( 'from' , 'cosmotheme' );
            $this -> scriptdata[ 'translations' ][ 'categories' ] = __( 'categories' , 'cosmotheme' );
            $this -> scriptdata[ 'translations' ][ 'category' ] = __( 'category' , 'cosmotheme' );
            $this -> scriptdata[ 'translations' ][ 'please' ] = __( 'Nothing to show. Please select some' , 'cosmotheme' );
            $this -> scriptdata[ 'translations' ][ 'in' ] = __( 'in' , 'cosmotheme' );
            $this -> scriptdata[ 'translations' ][ 'with' ] = __( 'with' , 'cosmotheme' );
            $this -> scriptdata[ 'translations' ][ 'carousel' ] = __( 'carousel' , 'cosmotheme' );
            $this -> scriptdata[ 'translations' ][ 'tag' ] = __( 'tag' , 'cosmotheme' );
            $this -> scriptdata[ 'translations' ][ 'tags' ] = __( 'tags' , 'cosmotheme' );
            $this -> scriptdata[ 'translations' ][ 'pagination' ] = __( 'pagination' , 'cosmotheme' );
            $this -> scriptdata[ 'translations' ][ 'load_more' ] = __( 'load more' , 'cosmotheme' );
            $this -> scriptdata[ 'translations' ][ 'gallery' ] = __( 'gallery' , 'cosmotheme' );
            $this -> scriptdata[ 'translations' ][ 'portfolio' ] = __( 'portfolio' , 'cosmotheme' );
            $this -> scriptdata[ 'translations' ][ 'portfolios' ] = __( 'portfolios' , 'cosmotheme' );
            $this -> scriptdata[ 'translations' ][ 'featured' ] = __( 'featured' , 'cosmotheme' );
            $this -> scriptdata[ 'translations' ][ 'latest' ] = __( 'latest' , 'cosmotheme' );
            $this -> scriptdata[ 'home_url' ] = home_url();
		}
		function render_backend(){
			$this -> load_elements();
			wp_enqueue_style( 'frontpagebuilder' , get_template_directory_uri() . '/lib/css/frontpagebuilder.css' );
            wp_enqueue_script( 'scrollto' , get_template_directory_uri() . '/js/jquery.scrollTo-1.4.2-min.js', array( 'jquery' ) );
			wp_enqueue_script( 'frontpagebuilder' , get_template_directory_uri() . '/lib/js/frontpagebuilder.js' , array( 'jquery-ui-sortable', 'scrollto' ) );
			wp_localize_script( 'frontpagebuilder' , 'FrontpageBuilder' , $this -> scriptdata );
            $front_page_layout = options::get_value( 'front_page', 'layout' );
            $front_page_sidebar = options::get_value( 'front_page', 'sidebar' );
            if( isset( $_GET[ 'settings-updated' ] ) ){
                $layout = get_option( 'layout' );
                $layout[ 'front_page' ] = $front_page_layout;
                $layout[ 'front_page_sidebar' ] = $front_page_sidebar;
                update_option( 'layout', $layout );
            }
			include get_template_directory() . '/lib/templates/frontpagebuilder.php';
		}
		function render_frontend(){
			$this -> load_elements();
			foreach( $this -> elements as $element ){
				$element -> render_frontend();
			}
            /* fix for hot posts number of pages */
            remove_filter( 'pre_get_posts', 'cosmo_posts_per_archive' );
		}
		function __toString(){
			ob_start();
			if( is_admin() ){
				$this -> render_backend();
			}else{
				$this -> render_frontend();
			}
			$contents = ob_get_contents();
			ob_end_clean();
			return $contents;
		}
		static function handle_request(){}

		function load_elements(){
            if( isset( $_POST[ 'front_page' ] ) && is_array( $_POST[ 'front_page' ] ) && isset( $_POST[ 'front_page' ][ 'elements' ] ) ){
                $options = $_POST[ 'front_page' ];
            }else{
                $options = get_option( 'front_page' );
            }
			if( is_array( $options ) && isset( $options[ 'elements' ] ) && is_array( $elements = $options[ 'elements' ] ) ){
                if( isset( $_GET[ 'fp_element' ] ) ){
                    $elementID = $_GET[ 'fp_element' ];
                    $element = $elements[ $elementID ];
                    array_push( $this -> elements, new FrontpageElement( $element ) );
                }else{
                    $last = end( array_keys( $elements ) );
                    foreach( $elements as $key => $element ){
                        if( $key != '_id_' ){
                            $element[ 'is_last' ] = $last == $key;
                            array_push( $this -> elements, new FrontpageElement( $element ) );
                        }
                    }
                }
			}
		}

		function load_widget_elements(){
			$options = get_option( 'front_page' );
			if( is_array( $options ) && isset( $options[ 'elements' ] ) && is_array( $elements = $options[ 'elements' ] ) ){
				foreach( $elements as $key => $element ){
					if( $key != '_id_' && $element[ 'type' ] == 'widget_zone' ){
						$Element = new FrontpageElement( $element );
						array_push( $this -> elements, $Element );
                        //TO DO set columns for widgets depending on user settings and whether there is a sidebar
				        register_sidebar( array(
							'name' => __( $Element -> name, 'cosmotheme' ),
							'id' => $Element -> id,
							'before_widget' => '<aside id="%1$s" class="widget three columns no-clear"><div class="%2$s">',
							'after_widget' => '</div></aside>',
							'before_title' => '<h4 class="widget-title">',
							'after_title' => '</h4><p class="delimiter">&nbsp;</p>',
						));
					}
				}
			}
		}
	}
?>
<?php
	class CustomHeader{
		function __construct( $header_type = false, $menu_type = false ){
			$this -> type = $header_type ? $header_type : options::get_value( 'header' , 'header_type' );
			$this -> logo = new CosmoLogo();
			$this -> menu = new CosmoCustomHeaderMenu( $menu_type );
			if( 'centered' == $this -> type || 'social' == $this -> type ){
				if( options::logic( 'general' ,  'user_login' ) ){
					if( is_user_logged_in() ){
						$this -> menu -> additional = new CosmoCustomHeaderCosmoMenu( 'ul' );
					}else{
						$this -> menu -> additional = new CosmoCustomHeaderCosmoMenu( 'signin_ul' );
					}
				}else{
					$this -> menu -> additional = new CosmoCustomHeaderCosmoMenu( 'nothing' );
				}
			}
            if( options::logic( 'general' ,  'user_login' ) ){
                if( is_user_logged_in() ){
                    $this -> mobile_menu = new CosmoCustomHeaderCosmoMenu( 'select' );
                }else{
                    $this -> mobile_menu = new CosmoCustomHeaderCosmoMenu( 'signin_anchor' );
                }
            }else{
                $this -> mobile_menu = new CosmoCustomHeaderCosmoMenu( 'nothing' );
            }

			if( 'menu' == $this -> type ){
				$this -> menu -> bottom_separator = '';
				$this -> menu -> surpress_mobile = true;
			}

			if( 'colored_full' == options::get_value( 'header' , 'menu_type' ) ){
				$this -> second_row_classes = ' nav-container-row';
			}else{
				$this -> second_row_classes = '';
			}
			$this -> cosmomenu = new CosmoCustomHeaderCosmoMenu();
			$suffix = $this -> type;
			call_user_func( array( $this, "init_$suffix" ) );
		}

		function render(){
			$suffix = $this -> type;
			?>
			<header>
                <div class="mobile-user-menu">
                    <?php echo $this -> mobile_menu -> render(); ?>
                </div>
				<div class="row no-overflow bottom-separator">
					<div class="branding">
						<?php call_user_func( array( $this, "render_${suffix}_first_row" ) );?>
					</div>
				</div>
				<?php call_user_func( array( $this, "render_${suffix}_second_row" ) );?>
				<?php
                    get_ads('logo','twelve columns');
                ?>
                <?php
	                if( options::logic( 'general' , 'breadcrumbs' ) && !is_front_page() ){
	                    echo '<div class="breadcrumbs">';
	                    echo '<ul>';
	                    dimox_breadcrumbs();
	                    echo '</ul>';
	                    echo '<div class="clear"></div></div>';
	                }
	            ?>
			</header>
		<?php
		}

		function init_social(){
			$this -> logo -> columns = 'three';
		}
		function render_social_first_row(){
			$this -> logo -> render();
			echo '<div class="four columns left-delimiter">';
				$this -> menu -> render();
			echo '</div>';
			?>
				<div id="searchbox" class="five columns">
	                <ul class="cosmo-social">
	                    <?php
                            $fb_id = options::get_value( 'social' , 'facebook' );
                            if( strlen( trim( $fb_id ) ) ){
                                $fb['likes'] = social::pbd_get_transient($name = 'facebook',$user_id=$fb_id,$cacheTime = 120); /*cache - in minutes*/
                                $fb['link'] = 'http://facebook.com/people/@/'  . $fb_id ;
                            }
	                        if( isset( $fb ) && is_array( $fb ) && !empty( $fb ) && isset( $fb['link'] ) ){
	                    ?>
	                            <li><a href="<?php echo $fb['link']; ?>" target="_blank" class="fb hover-menu">&nbsp;</a></li>
	                    <?php
	                        }

	                        if( strlen( options::get_value( 'social' , 'twitter' ) ) ){
	                    ?>
	                            <li><a href="http://twitter.com/<?php echo options::get_value( 'social' , 'twitter' ) ?>" target="_blank" class="twitter hover-menu">&nbsp;</a></li>
	                    <?php
	                        }
	                    ?>
	                    <?php
	                        if( options::logic( 'social' , 'rss' ) ){
	                    ?>
	                        <li><a href="<?php bloginfo('rss2_url'); ?>" class="rss hover-menu">&nbsp;</a></li>
	                    <?php
	                        }
                            if( strlen( options::get_value( 'social' , 'gplus' ) ) ){
                            ?>
                                <li><a href="<?php echo options::get_value( 'social' , 'gplus' ) ?>" target="_blank" class="gplus hover-menu">&nbsp;</a></li>
                            <?php
                            }
                            if( strlen( options::get_value( 'social' , 'yahoo' ) ) ){
                                ?>
                                <li><a href="<?php echo options::get_value( 'social' , 'yahoo' ) ?>" target="_blank" class="yahoo hover-menu">&nbsp;</a></li>
                                <?php
                            }
                            if( strlen( options::get_value( 'social' , 'dribbble' ) ) ){
                                ?>
                                <li><a href="<?php echo options::get_value( 'social' , 'dribbble' ) ?>" target="_blank" class="dribbble hover-menu">&nbsp;</a></li>
                                <?php
                            }
                            if( strlen( options::get_value( 'social' , 'linkedin' ) ) ){
                                ?>
                                <li><a href="<?php echo options::get_value( 'social' , 'linkedin' ) ?>" target="_blank" class="linkedin hover-menu">&nbsp;</a></li>
                                <?php
                            }
                            if( strlen( options::get_value( 'social' , 'skype' ) ) ){
                                ?>
                                <li><a href="skype:<?php echo options::get_value( 'social' , 'skype' ) ?>?call" target="_blank" class="skype hover-menu">&nbsp;</a></li>
                                <?php
                            }
	                    ?>
	                </ul>
	                <div class="clear"></div>
					<?php get_template_part( 'searchform' );?>
				</div>
			<?php
		}
		function render_social_second_row(){}
		function init_menu(){
			if( 'nothing' == $this -> cosmomenu -> type ){
				$this -> rowed_columns = 'nine';
			}else{
				$this -> cosmomenu -> columns = 'two';
				$this -> rowed_columns = 'seven';
			}
		}
		function render_menu_first_row(){?>
			<?php echo $this -> logo -> render();?>
			<div class="<?php echo $this -> rowed_columns;?> columns rowed-nav-container">
					<?php echo $this -> menu -> render();?>
			</div>
			<?php echo $this -> cosmomenu -> render();
		}
		function render_menu_second_row(){
			echo '<div class="row no-overflow">';
				$this -> menu -> render_mobile_menu( true );
			echo '</div>';
		}
		function init_searchbar(){}
		function render_searchbar_first_row(){
			$this -> logo -> render();
			?>
				<div id="searchbox" class="seven columns">
					<?php get_template_part( 'searchform' );?>
				</div>
			<?php
			$this -> cosmomenu -> render();
		}
		function render_searchbar_second_row(){
			echo '<div class="row no-overflow">';
				$this -> menu -> render();
			echo '</div>';
		}
		function init_centered(){
			$this -> logo -> columns = 'twelve';
			$this -> logo -> innerContentClasses = 'centered-header';
		}
		function render_centered_first_row(){
			$this -> logo -> render();
		}
		function render_centered_second_row(){
			echo '<div class="row no-overflow ' . $this -> second_row_classes . '">';
				$this -> menu -> render();
			echo '</div>';
		}
		function __toString(){
			ob_start();
			$this -> render();
			$contents = ob_get_contents();
			ob_end_clean();
			return $contents;
		}
	}

	class CosmoCustomHeaderCosmoMenu{
		public function __construct( $type = false ){
			if( $type === false ){
				if( options::logic( 'general' ,  'user_login' ) ){
					if( is_user_logged_in() ){
						$this -> type = "menu";
					}else{
						$this -> type = "signin";
					}
				}else{
					$this -> type = "nothing";
				}
			}else{
				$this -> type = $type;
			}
			$suffix = $this -> type;
			call_user_func( array( $this, "init_$suffix" ) );
		}
		public function init_menu(){
			global $wp_version;
            $role = array( 
                10 => __( 'Administrator' , 'cosmotheme' ) ,
                7 => __( 'Editor' , 'cosmotheme' ) , 
                2 => __( 'Author' , 'cosmotheme' ) , 
                1 => __( 'Contributor' , 'cosmotheme'  ) , 
                0 => __( 'Subscriber' , 'cosmotheme' ), 
                '' => __( 'Subscriber' , 'cosmotheme' )
            );
			$u_id = get_current_user_id();
			$picture = facebook::picture();
            if( strlen( $picture ) && get_user_meta( $u_id , 'custom_avatar' , true ) == ''){
            	$facebook_id = facebook::id();
            	$this -> avatar_link = "http://facebook.com/profile.php?id=$facebook_id";
            	$this -> avatar = '<img src="' . $picture . '" width="32" width="32" />';
            }else{
            	$this -> avatar_link = get_author_posts_url( $u_id );
            	$this -> avatar = cosmo_avatar( $u_id , 32 , $default = DEFAULT_AVATAR_LOGIN );
            }

            $user = (array)get_userdata( $u_id );
            if($wp_version < 3.3){
                if( !isset( $user['user_level'] ) ){
                    $user['user_level'] = '';
                }
                $this -> user_login = $user['user_login'];
                $this -> user_role = $role[ $user['user_level'] ];
            }else{
                if(isset($user['roles'][0])){
                    $user['user_level'] =   $user['roles'][0]; 
                }else $user['user_level']=__( 'Subscriber' , 'cosmotheme' );
                $this -> user_login = $user['data']->display_name;    
                $this -> user_role = $user['user_level'];
            }   

            $this -> user_id = $u_id;
            $this -> author_url = get_author_posts_url( $u_id );
			$url = home_url();
			$like = array( 'fp_type' => "like" );
			$this -> url_like = add_query_arg( $like , $url );
		}

		function get_menu_item_from_option( $class, $label, $tab, $option_name ){
			$page_id = (int) options::get_value( $tab , $option_name );
			if( $page_id > 0 ){
				return $this -> get_menu_item( $class, get_page_link( $page_id ), $label );
			}
		}

		function get_menu_item( $class, $url, $label ){
            if( 'select' == $this -> type ){
                return <<<endhtml
                    <option class="$class" value="$url">
                        $label
                    </option>
endhtml;
            }
			return <<<endhtml
                <li class="$class">
                	<a href="$url">
                		$label
                	</a>
            	</li>
endhtml;
		}
		public function init_ul(){
			$this -> init_menu();
		}
		public function render_ul(){
			?>
                <ul class="sf-menu sf-js-enabled sf-shadow additional-menu-item">
                    <li class="signin">
                        <a href="<?php echo $this -> avatar_link;?>" class="sf-with-ul">
                        	<?php echo $this -> user_login;?>
                        	<span><?php echo __( 'Members area' , 'cosmotheme' );?></span>
                        	<span class="sf-sub-indicator"> »</span>
                    	</a>
    	            	<ul>
							<?php 
								echo $this -> get_menu_item_from_option( 'my-settings', __( 'My settings' , 'cosmotheme' ), 'general' , 'user_profile_page' );
								if( post::get_my_posts( $this -> user_id ) ){
									echo $this -> get_menu_item( 'my-profile', $this -> author_url, __( 'My profile' , 'cosmotheme' ) );
								}
								echo $this -> get_menu_item_from_option( 'my-posts', __( 'My added posts' , 'cosmotheme' ), 'general' , 'my_posts_page' );
								if( options::logic( 'general' , 'enb_likes' ) ){
									echo $this -> get_menu_item( 'my-likes' , $this -> url_like, __( 'My liked posts' , 'cosmotheme' ) );
								}
								echo $this -> get_menu_item_from_option( 'my-add', __( 'Add post' , 'cosmotheme' ), 'upload' , 'post_item_page' );
								echo $this -> get_menu_item( 'my-logout', wp_logout_url( home_url() ), __( 'Log out' , 'cosmotheme' ) );
							?>
		                </ul>
                    </li>
                </ul>
			<?php
		}

        public function init_select(){
            $this -> init_menu();
        }
        public function render_select(){
            ?>
            <select class="sf-menu sf-js-enabled sf-shadow additional-menu-item">
                <option value="#"><?php echo __( 'Members area' , 'cosmotheme' );?></option>
                <?php
                echo $this -> get_menu_item_from_option( 'my-settings', __( 'My settings' , 'cosmotheme' ), 'general' , 'user_profile_page' );
                if( post::get_my_posts( $this -> user_id ) ){
                    echo $this -> get_menu_item( 'my-profile', $this -> author_url, __( 'My profile' , 'cosmotheme' ) );
                }
                echo $this -> get_menu_item_from_option( 'my-posts', __( 'My added posts' , 'cosmotheme' ), 'general' , 'my_posts_page' );
                if( options::logic( 'general' , 'enb_likes' ) ){
                    echo $this -> get_menu_item( 'my-likes' , $this -> url_like, __( 'My liked posts' , 'cosmotheme' ) );
                }
                echo $this -> get_menu_item_from_option( 'my-add', __( 'Add post' , 'cosmotheme' ), 'upload' , 'post_item_page' );
                echo $this -> get_menu_item( 'my-logout', wp_logout_url( home_url() ), __( 'Log out' , 'cosmotheme' ) );
                ?>
            </select>
        <?php
        }

		public function render_menu(){
			?>
			<div class="two columns" id="menu-login">
				<a href="<?php echo $this -> avatar_link;?>" class="profile-pic">
					<?php echo $this -> avatar;?>
				</a>
				<div class="cosmo-icons">
	                <ul class="sf-menu sf-js-enabled sf-shadow">
	                    <li class="signin">
	                        <a href="<?php echo $this -> avatar_link;?>" class="sf-with-ul">
	                        	<?php echo $this -> user_login;?>
	                        	<img src="<?php echo get_template_directory_uri();?>/images/mask.png" class="mask" alt="Mask">
	                        	<span><?php echo $this -> user_role;?></span>
	                        	<span class="sf-sub-indicator"> »</span>
	                    	</a>
        	            	<ul>
								<?php 
									echo $this -> get_menu_item_from_option( 'my-settings', __( 'My settings' , 'cosmotheme' ), 'general' , 'user_profile_page' );
									if( post::get_my_posts( $this -> user_id ) ){
										echo $this -> get_menu_item( 'my-profile', $this -> author_url, __( 'My profile' , 'cosmotheme' ) );
									}
									echo $this -> get_menu_item_from_option( 'my-posts', __( 'My added posts' , 'cosmotheme' ), 'general' , 'my_posts_page' );
									if( options::logic( 'general' , 'enb_likes' ) ){
										echo $this -> get_menu_item( 'my-likes' , $this -> url_like, __( 'My liked posts' , 'cosmotheme' ) );
									}
									echo $this -> get_menu_item_from_option( 'my-add', __( 'Add post' , 'cosmotheme' ), 'upload' , 'post_item_page' );
									echo $this -> get_menu_item( 'my-logout', wp_logout_url( home_url() ), __( 'Log out' , 'cosmotheme' ) );
								?>
			                </ul>
	                    </li>
	                </ul>
                </div>
            </div>
			<?php
		}
		function init_signin_ul(){}
		function render_signin_ul(){
			?>
                <ul class="sf-menu sf-js-enabled sf-shadow">
                    <li class="signin">
                        <a onclick="get_login_box(''); " href="javascript:void(0)"><?php _e( 'sign in' , 'cosmotheme' );?><span><?php _e( 'members area' , 'cosmotheme' );?></span></a>
                    </li>
                </ul>
			<?php
		}
        function init_signin_anchor(){}
        function render_signin_anchor(){
            ?>
            <a onclick="get_login_box(''); " href="javascript:void(0)"><?php _e( 'sign in' , 'cosmotheme' );?></a>
        <?php
        }
		public function init_signin(){}
		public function render_signin(){?>
			<div class="two columns" id="menu-login">
	            <div class="user-login">
	            	<a href="<?php echo wp_login_url();?>" class="profile-pic  left">
						<img src="<?php echo get_template_directory_uri();?>/images/default_avatar_login.png" alt="<?php _e( 'Login' , 'cosmotheme' );?>">
					</a>
	                <div class="cosmo-icons left">
	                	<?php $this -> render_signin_ul();?>
	                </div>
	            </div>
            </div>
		<?php
		}
		public function init_nothing(){}
		public function render_nothing(){}
		public function render(){
			$suffix = $this -> type;
			call_user_func( array( $this, "render_$suffix" ) );
		}
	}

	class CosmoCustomHeaderMenu{
		function __construct( $type = false ){
			$this -> additional = new CosmoCustomHeaderCosmoMenu( 'nothing' );
			$this -> type = $type ? $type : options::get_value( 'header' , 'menu_type' );
			$suffix = $this -> type;
			$this -> bottom_separator = 'bottom-separator';
			$this -> surpress_mobile = false;
			call_user_func( array( $this, "init_$suffix" ) );
		}
		function init_vertical(){}
		function render_vertical(){
			?>
				<nav id="access" role="navigation" class="bottom-separator list-menu"><!--Menu container-->	
					<div id="d-menu" class="cosmo-icons"><!--Menu starts here-->
                        <?php $this -> render_full_menu();?>
                        <?php $this -> additional -> render();?>
					</div>
				</nav>
				<div class="mobile-menu twelve hide" style="display: none; "><!--Menu starts here-->
                    <?php $this -> render_mobile_menu();?>
				</div>
				<?php
		}

		function render_full_menu(){
			echo menu( 'header_menu' , array( 
				'number-items' => options::get_value( 'menu' , 'header' ),
				'current-class' => 'active',
				'type' => 'category',
				'class' => 'sf-menu',
				'menu_id' => 'nav-menu'
			));
		}
		function render_mobile_menu( $justdoit = false ){
			if( !$this -> surpress_mobile || $justdoit ){
				?>
					<div class="mobile-menu twelve hide bottom-separator" style="display: none; "><!--Menu starts here-->
						<div class="toggle-menu">
							<?php _e( 'MENU' , 'cosmotheme' );?>
						</div>
						<?php echo menu( 'header_menu' , array( 
								'number-items' => options::get_value( 'menu' , 'header' ),
								'current-class' => 'active',
								'type' => 'category',
								'class' => 'mobile-nav-menu',
								'menu_id' => 'mobile-nav-menu'
							) 
						);?>
					</div>
				<?php
			}
		}

		function init_description(){}
		function render_description(){
			?>
			<nav id="access" role="navigation" class="description-menu <?php echo $this -> bottom_separator;?>"><!--Menu container-->
				<div id="d-menu" class="cosmo-icons"><!--Menu starts here-->
					<?php $this ->render_full_menu();?>
					<?php $this -> additional -> render();?>
				</div>
			</nav>
			<?php $this -> render_mobile_menu();?>
			<?php
		}
		function init_centered(){}
		function render_centered(){
			?>
			<nav id="access" role="navigation" class="centered-menu <?php echo $this -> bottom_separator;?>"><!--Menu container-->
				<div id="d-menu" class="cosmo-icons"><!--Menu starts here-->
					<?php $this ->render_full_menu();?>
					<?php $this -> additional -> render();?>
				</div>
			</nav>
			<?php $this -> render_mobile_menu();?>
			<?php
		}
		function init_text(){}
		function render_text(){
			?>
				<nav id="access" role="navigation" class="text-menu <?php echo $this -> bottom_separator;?>"><!--Menu container-->
					<div id="d-menu" class="cosmo-icons"><!--Menu starts here-->
						<?php $this ->render_full_menu();?>
						<?php $this -> additional -> render();?>
					</div>
				</nav>
				<?php $this -> render_mobile_menu();?>
			<?php
		}
		function init_buttons(){}
		function render_buttons(){
			?>
			<nav id="access" role="navigation" class="buttons-menu <?php echo $this -> bottom_separator;?>"><!--Menu container-->
				<div id="d-menu" class="cosmo-icons"><!--Menu starts here-->
					<?php $this ->render_full_menu();?>
					<?php $this -> additional -> render();?>
				</div>
			</nav>
			<?php $this -> render_mobile_menu();?>
			<?php
		}
		function init_colored(){
			$background = options::get_value( 'header' , 'menu_color' );
			$text_color = options::get_value( 'header' , 'menu_text_color' );
			if( strlen( $background ) ){
				$this -> style = "background:$background";
			}else{
				$this -> style = '';
			}
			if( strlen( $text_color ) ){
				$this -> inline_style = <<<endhtml
					<style>
						#access.colored a{ color:$text_color; }
					</style>
endhtml;
			}else{
				$this -> inline_style = '';
			}
		}
		function render_colored(){
			?>
				<?php echo $this -> inline_style; ?>
				<nav id="access" role="navigation" class="colored full <?php echo $this -> bottom_separator;?>"><!--Menu container-->
					<div id="d-menu" class="cosmo-icons colored" style="<?php echo $this -> style;?>"><!--Menu starts here-->
						<?php $this -> render_full_menu();?>
						<?php $this -> additional -> render();?>
					</div>
					
				</nav>
				<?php $this -> render_mobile_menu();?>
			<?php
		}
		public function render(){
			$suffix = $this -> type;
			call_user_func( array( $this, "render_$suffix" ) );
		}
		function __toString(){
			ob_start();
			$this -> render();
			$contents = ob_get_contents();
			ob_end_clean();
			return $contents;
		}
	}

	class CosmoLogo{
		public function __construct(){
			$this -> type = options::get_value( 'styling' , 'logo_type' );
			$this -> columns = 'three';
			$this -> innerContentClasses = '';
			$this -> link = home_url();
			$suffix = $this -> type;
			call_user_func( array( $this, "init_$suffix" ) );
		}
    	public function render(){ 
    		$suffix = $this -> type;
    		?>
            <div class="columns <?php echo $this -> columns;?>">
                <div class="innerContent <?php echo $this -> innerContentClasses;?>">
                    <hgroup class="logo">
		    			<a href="<?php echo $this -> link;?>" class="hover">
		    				<?php call_user_func( array( $this, "render_$suffix" ) ); ?>
						</a>
                  	</hgroup>
            	</div>
        	</div>
    	<?php
    	}	
    	public function init_text(){
			$this -> name = get_bloginfo( 'name' );
			$this -> description = get_bloginfo( 'description' );
    	}
		public function render_text(){?>
			<h3>
				<?php echo $this -> name;?>
				<span><?php echo $this -> description;?></span>
			</h3>
		<?php
		}
		public function init_image(){
			$this -> src = strlen( trim( options::get_value( 'styling' , 'logo_url' ) ) ) ? options::get_value( 'styling' , 'logo_url' ) : get_template_directory_uri() . '/images/logo.png';
		}
		public function render_image(){?>
            <h1>
                <img src="<?php echo $this -> src;?>" />
            </h1>
		<?php
		}
	}
?>
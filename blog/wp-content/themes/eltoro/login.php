<?php if(!is_user_logged_in()) { ?>
<iframe id="registration_iframe" name="registration_iframe" class="hidden"></iframe>
<?php if( options::logic( 'general' ,  'user_login' ) ){
			$register_link=home_url().'?action=register';
			$recover_link=home_url().'?action=recover';
?>			
			

<?php				
			
		
			if(isset($_GET['action']) && $_GET['action']=='register'){?>
			<div class="login-image-icon"></div>
			<div class="register">
				
				<form action="" method="post" class="form txt" id="register_form">
					<fieldset>
						<div class="login_inside">
							<p>
								<input type="text" id="user_login" name="login" onblur="if (this.value == '') {this.value = '<?php echo __( 'Your name' , 'cosmotheme' );?>';}" onfocus="if (this.value == '<?php echo __( 'Your name' , 'cosmotheme' );?>') {this.value = '';}" value="<?php echo __( 'Your name' , 'cosmotheme' );?>">
							</p>
							<p>
								<input type="text" id="user_email" name="email" onblur="if (this.value == '') {this.value = '<?php echo __( 'Your email' , 'cosmotheme' );?>';}" onfocus="if (this.value == '<?php echo __( 'Your email' , 'cosmotheme' );?>') {this.value = '';}" value="<?php echo __( 'Your email' , 'cosmotheme' );?>">
							</p>
							<p>
								<div class="login-error" id="registration_error"></div>
							</p>
							<p class="button submit red">
								<input type="submit" value="Register" class="button" id="register_button">
							</p>
							<div class="login-box">
								<p class="box">
									<div><?php echo __( 'Already a member?' , 'cosmotheme' ); ?> <br/><a href="<?php echo home_url().'?action=login';?>" id="login" class="try"><?php echo __( 'Log in here' , 'cosmotheme' ); ?></a></div>
								</p>
							</div>
							<input type="hidden" name="testcookie" value="1">
						</div>
					</fieldset>
				</form>
			</div>
			
		<?php }elseif( isset( $_GET['action'] ) && $_GET['action'] == "recover" ){ ?>
			<div class="login">
				<div class="login-image-icon"></div>
				<form name="lostpasswordform" id="lostpasswordform" action="<?php echo get_template_directory_uri();?>/wp-login.php?action=lostpassword" method="post" class="form txt" target="registration_iframe">
					<fieldset>
						<div class="login_inside">
							<p>
								<input type="text" id="user_login" name="user_login" onblur="if (this.value == '') {this.value = '<?php echo __( 'Your username or email' , 'cosmotheme' );?>';}" onfocus="if (this.value == '<?php echo __( 'Your username or email' , 'cosmotheme' );?>') {this.value = '';}" value="<?php echo __( 'Your username or email' , 'cosmotheme' );?>">
							</p>
							<p>
								<div class="login-success" style="border:none" id="registration_error"></div>
							</p>
							<p class="button submit red">
								<input type="submit" value="Recover" class="button">
							</p>
							<div class="login-box">
								<p class="box">
									<div><?php echo __( 'Already a member?' , 'cosmotheme' ); ?><br/><a href="<?php echo home_url().'?action=login'; ?>" id="login" class="try"><?php echo __( 'Log in here' , 'cosmotheme' ); ?></a></div>
								</p>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
			
		<?php }else{ ?>
			<div class="login">
				<div class="login-image-icon"></div>
				<form name="loginform" id="cosmo-loginform" action="<?php echo get_template_directory_uri();?>/wp-login.php" method="post" class="form txt">
					<fieldset>
						<div class="login_inside">
							<p>
								<input name="login" id="username" type="text" class="" onblur="if (this.value == '') {this.value = '<?php echo __( 'username' , 'cosmotheme' );?>';}" onfocus="if (this.value == '<?php echo __( 'username' , 'cosmotheme' );?>') {this.value = '';}" value="<?php echo __( 'username' , 'cosmotheme' );?>" />
							</p>
							<p>
								<input name="password" id="password" type="password" class="" onblur="if (this.value == '') {this.value = '<?php echo __( 'Password:' , 'cosmotheme' );?>';}" onfocus="if (this.value == '<?php echo __( 'Password:' , 'cosmotheme' );?>') {this.value = '';}" value="<?php echo __( 'Password:' , 'cosmotheme' );?>" />
							</p>
							<p class="rememberme">
								<label class="remember"><input name="remember" type="checkbox" id="rememberme" value="forever" tabindex="90"><?php echo __( 'Remember Me' , 'cosmotheme' );?></label>
							</p>
							<p class="submit button blue">
								<input type="submit" id="login_button" value="Login" class="button">
							</p>
							<div class="clear"></div>
							<p class="error-report">
								<div class="login-error" id="registration_error"></div>
							</p>
							<p class="not_logged_msg like_warning " style="display:none"><?php _e('You need to sign in to vote for a post.','cosmotheme') ?> </p>
							<p class="not_logged_msg nsfw_warning " style="display:none"><?php _e('You need to sign in to see this post.','cosmotheme') ?> </p>
						</div>
						<div class="clear"></div>
						<div class="lost">
							<div class="login_inside">
	                        <?php
	                        	if( !( options::get_value( 'social' , 'facebook_app_id' ) == '' || options::get_value( 'social' , 'facebook_secret' ) == '' ) ){
	                                ?>
	                                <div class="facebook">
	                                    <?php facebook::login(); ?>
	                                </div>    
	                                <?php
	                            }
	                        ?>
							<p class="pswd">
								<span>
									<a href="<?php echo $recover_link;?>">
										<?php echo __( 'Lost your password?' , 'cosmotheme' );?>
									</a>
								</span>
								<?php if(get_option('users_can_register')) { ?> | 
									<span><a href="<?php echo $register_link;?>"><?php echo __( 'Register' , 'cosmotheme' );?></a></span>
								<?php } ?>
							</p>
						</div>
					</fieldset>
					<input type="hidden" name="testcookie" value="1">
				</form>
			</div>
			<div class="clear"></div>

	<?php	
		}?>
<?php } ?>
<?php }else{ 
	_e('You are already logged in.','cosmotheme');
}?>
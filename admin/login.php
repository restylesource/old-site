<?
	include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/db.php");	
	include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/logincheck.php");
	
	if($_POST){
	
		if($_POST['action'] == "forgot"){
			
			$user_id = user_email_lookup($_POST['email'], $error);
			
			if($error){
				// do nothing
			} else if($user_id){
				user_forgot_password($user_id, $_POST['email']);
				$success = "Your login information has been reset.";
			} else {
				$error = "Email address not found.";
			}
					
		} else if ($_POST['fname']){
			$user_id = user_add(0, $_POST['fname'], $_POST['lname'], $_POST['company'], $_POST['email'], $_POST['phone'], $_POST['address1'], $_POST['city'], $_POST['state'], $_POST['zip'], $_POST['rate'], $_POST['notes'], $_POST['user_type'], $_POST['password1'], 0, $error);
		
			if($user_id){
			
				$message.= "Name: " . trim($_POST['fname'] . " " .  $_POST['lname']) . " \r\n";
				$message.= "Email: " . trim($_POST['email']) . " \r\n";
				$message.= "Link: http://restylesource.info/profile.php?user_id=" . $user_id . "\r\n";
			
				mail('kevin@atekie.com', 'New Account Request', $message, 'from: notification@restyleresource.info');
				
				echo("<script>alert('Thank you, your request has been received.');</script>");
			}		
		}

	}
	
	if($g_sess->get_var("user") > 0){
	
		switch($g_sess->get_var("systemTeam")){
		
			case 'user':
				$link = '/index.php';
				break;
			case 'admin':
			case 'manufacturer':	
			case 'photographer':
				$link = '/admin/products-manager.php';
				break;
			case 'retailer':
				$link = '/admin/retailer-home.php';
				break;
		}
	
		my_redirect($link);
		
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" /> 
	<title>ReStyle Login Portal</title>
	
    <style>
		@import url("css/login.css");
		@import url('css/style_text.css');
		@import url('css/form-buttons.css');
		@import url('css/link-buttons.css');
		@import url('css/messages.css');
		@import url('css/forms.css');
		@import url('css/modalbox.css');
		label.error { float: none; color: red; padding-left: 11em; vertical-align: middle; }
	</style>
	<!--[if lte IE 8]>
		<script type="text/javascript" src="js/excanvas.min.js"></script>
	<![endif]-->

</head>
<body>
	<div class="wrapper"><br /><br /><br />
	<div align="center"><img src="/assets/images/logo-restylesource.png"></div>
		<div class="box">
			<div class="container hide-input">
                
                <h2>Login:</h2>
                <form action="" method="post">
                	<input type="hidden" name="action" value="make">
                    <input type="text" name="username" class="small" value="Username" />
                    <h2>Password</h2>
                    <input type="password" name="password" class="small" value="" />
                    <button type="submit" class="grey medium"><span>Login</span></button>
                </form>
				<div class="bottom">
					<?if($error){?>
					<br /><br />
					<div class="messages red">
						<span></span>
						<a href="#">ERROR! <?=$error?></a>
					</div>
					<?}?>
					
					<!-- <br /><br />
					<div class="messages green">
						<span></span>
						<a rel="Account Request"  href="ajax-request-account.php" class="modalopen">Want to start sharing your style? Get started now!</a>
					</div>
                	<div class="messages orange">
						<span></span>
						<a rel="Password Reset" href="ajax-password-reset.php" class="modalopen"><?if($success){ echo($success); } else {?>Forgot Your Password? No Worries.<?}?></a>
					</div>
					
					-->
					
				</div>
			</div>
		</div>
	<div>
		<div class="modal"></div>
	</div>
</div>
<?php include("includes/js.php"); ?>
</body>
</html>
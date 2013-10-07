<?

	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';	
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/logincheck.php';
	
	if($_POST['email']){
	
		$user_id = user_email_lookup($_POST['email'], $error);
	
		if(!$error){
		
			if($user_id<1){
				$error = "Email address not found";
			} else {
				user_forgot_password($user_id, $_POST['email']);
				$error = "A New Password has been sent... <strong><em>Please check your SPAM folder for delivery.</em></strong>";	
			}
		
		}
	
	}


?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html lang="en-us" class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>    <html lang="en-us" class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>    <html lang="en-us" class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en-us" class="no-js"> <!--<![endif]-->
<head>

	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width" />

	<title><? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/title.php'); ?> | Forget Your Password?</title>

	<!-- 
	CSS
	-->
	<!--[if !IE 6]><!-->
	<link rel="stylesheet" media="screen, projection" href="assets/css/main.css" />
	<!--<![endif]-->
	<!--[if lte IE 6]><link rel="stylesheet" href="http://universal-ie6-css.googlecode.com/files/ie6.1.1.css" media="screen, projection"><![endif]-->

	<!--
	FAVICON
	--> 
	<link rel="shortcut icon" href="/assets/images/favicon.gif" type="image/gif" /> 

	<!-- 
	HEAD SCRIPTS
	--> 
	<script src="/assets/js/modernizr-2.5.3.js"></script>

</head>

<body class="registration logged-in">

	<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'); ?>

	<div id="wrapper-page">

		<header>
			<h1>Forget your Password?</h1>

			<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/social.php'); ?>
			
		</header>

		<section role="main">

			<a class="retailer-manufacturer-designer">You can recover your password below...</a>

			<form id="registration" method="post" onSubmit="return validateForgot()">
				<span class="fields-required" id="error"><?if($error){ echo("<font color=\"red\">" . $error . "</font>"); } else {?>Email Address is Required<? } ?></span>
				<div class="column">
					<input type="text" id="email1" name="email" placeholder="Email Address" value="<?=$_POST['email']?>" />
					
				</div>
				<div class="column">
					<input type="text" id="email2" name="email2" placeholder="Email Again" value="<?=$_POST['email2']?>" />
					<input type="submit" value="Recover My Password" />
				</div>
			</form>

			

		</section> <!-- // [role="main"] -->

	</div> <!-- // #wrapper-page -->

	<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'); ?>

	<!--
	BODY JS
	-->
	<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/js_includes.php'); ?>
	<script>
		function validateForgot(){
			
			is_good = 1;
			
			if($('#email1').val()==""){
				$('#email1').addClass("error");
				is_good = false;
			}
			
			if($('#email2').val()==""){
				$('#email2').addClass("error");
				is_good = false;
			}
			
			if($('#email1').val() != $('#email2').val()){
				$('#error').html('<font color="red">Email addresses must match</font>');
				is_good = false;
			}
			
			return is_good;
			
		}
	</script>
</body>
</html>
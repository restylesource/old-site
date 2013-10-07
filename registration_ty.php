<?

	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';

	if($_POST){
	
		//let's validate username/email is not already registered
		if(unameAvailable(0, $_POST['email'])){
			//Let's register user
			$user_id = user_add(0, $_POST['first_name'], $_POST['last_name'], $_POST['company'], $_POST['email'], $_POST['phone'], $_POST['address1'], $_POST['city'], $_POST['state'], $_POST['zip'], $_POST['rate'], $_POST['website'], $_POST['notes'], 2, $_POST['password'], 1, 0, '', $error);
			
			user_email_updates($user_id, $_POST['email_updates']);
			
			if(!$error && $user_id>0){
				my_redirect('registration_ty.php');
			} else {
				if(!$error)
					$error = "An unknown error has occurred.";
			}
		} else {
			$error = "Email address already registered.  If you have forgotten your password, please use forgot password.";
		}
	
	}

	$state_result = state_search();
	$state_options = build_generic_dropdown($state_result, $_POST['state'], 0);

?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html lang="en-us" class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>    <html lang="en-us" class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>    <html lang="en-us" class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en-us" class="no-js"> <!--<![endif]-->
<head>

	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width" />

	<title><? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/title.php'); ?> | Thank You!</title>

	<!-- 
	CSS
	-->
	<!--[if !IE 6]><!-->
	<link rel="stylesheet" media="screen, projection" href="/assets/css/main.css" />
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
			<h1>Registration</h1>

			<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/social.php'); ?>
			
		</header>

		<section role="main">

			<span class="retailer-manufacturer-designer">Thank You!</span>
			<p>Thank you for your registration to ReStyle Source! We are excited you have decided to be a part of our growing community! An email has been sent to the specified email address confirming your registration.<br /><br />
			Please use the login button in the upper right to immediately log into your account.<br /><br />
			<span class="fields-required" id="error" style="color: #f91905;">Please be sure to check your spam folder as this is an automated email message.</span>
			<!--
			<strong>This text will be different for the various registration types... Content has yet to be given...</strong>
			-->
		</section> <!-- // [role="main"] -->

	</div> <!-- // #wrapper-page -->

	<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'); ?>

	<!--
	BODY JS
	-->
	<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/js_includes.php'); ?>
	
	

</body>
</html>
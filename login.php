<?

	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/logincheck.php';

	if($g_sess->get_var("user")){
		my_redirect('/dev-new/home.php');
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

	<title><? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/title.php'); ?> | Login</title>

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
			<h1>Please Login</h1>

			<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/social.php'); ?>
			
		</header>

		<section role="main">

			<a class="retailer-manufacturer-designer">This page requires you to log in</a>

			<form id="registration" method="post" onSubmit="return validateLogin()">
				<input type="hidden" name="action" value="make" />
				<span class="fields-required"><?if($error){ echo("<font color=\"red\">" . $error . "</font>"); } else {?>Both Fields Are Required<? } ?></span>
				<div class="column">
					<input type="text" id="username" name="username" placeholder="Email *" value="<?=$_POST['username']?>" />
					
				</div>
				<div class="column">
					<input type="password" id="password" name="password" placeholder="Password" />
					<div class="checkboxes small">
						<label for="email-updates">							
							<p><a href="registration.php">Create an Account</a></p>
						</label>
						<label for="email-updates">							
							<p><a href="forgot.php">Forgot Password?</a></p>
						</label>
					</div>
					<input type="submit" value="Submit" />
				</div>
			</form>

			

		</section> <!-- // [role="main"] -->

	</div> <!-- // #wrapper-page -->

	<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'); ?>

	<!--
	BODY JS
	-->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
	<script src="/assets/js/jquery.ui.touch-punch.min.js"></script>
	<script src="/assets/js/colorbox/jquery.colorbox-min.js"></script>
	<script src="/assets/js/dialog/jquery.ui.dialog.ajax-min.js"></script>
	<script src="/assets/js/moretext.js"></script>
	<script src="/assets/js/input-placeholder.js"></script>
	<script src="/assets/js/source-min.js"></script>
	<script>
		function validateLogin(){
		
			var is_good = true;
			
			
			
		
		}
	</script>
</body>
</html>
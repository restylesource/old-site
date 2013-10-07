<?

	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';

	if($_POST){
	
		//let's validate username/email is not already registered
		if(unameAvailable(0, $_POST['email'])){
			//Let's register user
			$user_id = user_add(0, $_POST['first_name'], $_POST['last_name'], $_POST['company'], $_POST['email'], $_POST['phone'], $_POST['address1'], $_POST['city'], $_POST['state'], $_POST['zip'], $_POST['rate'], $_POST['website'], $_POST['notes'], 2, $_POST['password'], 1, '', '', $error);
			
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

	<title><? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/title.php'); ?> | Registration</title>

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
			<h1>Retailer Registration</h1>

			<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/social.php'); ?>
			
		</header>

		<section role="main">

			<span class="retailer-manufacturer-designer">Are you a <a href="registration-manufacturer.php">Manufacturer + Showroom</a> / or <a href="registration-designer.php">Designer + Trade?</a></span>

			<form id="registration" method="post" onSubmit="return validateRegistration()">
				<span class="fields-required" id="error"><?if($error){ echo("<font color=\"red\">" . $error . "</font>"); } else {?>* All fields are required<?}?></span>
				<div class="column">
					<input type="text" placeholder="First Name" id="first_name" name="first_name" value="<?=$_POST['first_name']?>" />
					<input type="text" placeholder="Last Name" id="last_name" name="last_name" value="<?=$_POST['last_name']?>" />
					<input type="text" placeholder="Store Name" id="company" name="company" value="<?=$_POST['company']?>" />
					<input type="text" placeholder="Type of Manufacturer" id="retail_type" name="retail_type" value="<?=$_POST['retail_type']?>" />
					<input type="text" placeholder="City" id="city" name="city" value="<?=$_POST['city']?>" />
					<select name="state" id="state" class="uniform">
						<?=$state_options?>
					</select>
					<input type="text" placeholder="Zip Code" id="zip" name="zip" value="<?=$_POST['zip']?>" />
					<input type="text" placeholder="Web Address" id="website" name="website" value="<?=$_POST['website']?>" />
					</div>
				<div class="column">
					<input type="text" placeholder="Email" id="email" name="email" value="<?=$_POST['email']?>" />
					<input type="text" placeholder="Confirm Email" id="email2" name="email2" value="<?=$_POST['email2']?>" />
					<input type="password" placeholder="Password" name="password" id="password" />
					<input type="password" placeholder="Confirm Password" name="password2" id="password2" />
					<div class="checkboxes small">
						<label for="agree">
							<input type="checkbox" id="agree" />
							<p>I agree to Restyle Source <a href="#">Retailer Terms and Conditions</a></p>
						</label>
						<label for="email-updates">
							<input type="checkbox" id="email-updates" name="email_updates" <?=$email_updates?> value="1" />
							<p>Send me email updates</p>
						</label>
					</div>
					<br /><br />
						<span class="fields-required" id="error"><strong>Basic Requirements for Retailer:</strong><br />
<ul>
</ul>	
</span>
					<input type="submit" value="Submit" />
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
		// Initate uniform dropdown
		$('select.uniform').uniform();
		
		function validateRegistration(){
		
			var is_good = true;
		
			if($('#first_name').val() == ""){
				$('#first_name').addClass("error");
				is_good = false;
			} else {
				$('#first_name').removeClass("error");
			}
			
			if($('#last_name').val() == ""){
				$('#last_name').addClass("error");
				is_good = false;
			} else {
				$('#last_name').removeClass("error");
			}
			
			if($('#city').val() == ""){
				$('#city').addClass("error");
				is_good = false;
			} else {
				$('#city').removeClass("error");
			}
			
			if($('#zip').val() == ""){
				$('#zip').addClass("error");
				is_good = false;
			} else {
				$('#zip').removeClass("error");
			}
			
			if($('#email').val() == ""){
				$('#email').addClass("error");
				is_good = false;
			} else {
				$('#email').removeClass("error");
			}
			
			if($('#email2').val() == ""){
				$('#email2').addClass("error");
				is_good = false;
			} else {
				$('#email2').removeClass("error");
			}
			
			if($('#email').val() && $('#email2').val() && ($('#email').val() != $('#email2').val())){
				$('#error').html('<font color="red">Email addresses must match.</font>');
				is_good = false;
			}
			
			if($('#email').val() != "" && !isValidEmailAddress($('#email').val())){
				$('#email').addClass("error");
				$('#error').html('<font color="red">Email address is not valid.</font>');
				is_good = false;
			}
			
			if($('#password').val() == ""){
				$('#password').addClass("error");
				is_good = false;
			} else {
				$('#password').removeClass("error");
			}
			
			if($('#password2').val() == ""){
				$('#password2').addClass("error");
				is_good = false;
			} else {
				$('#password2').removeClass("error");
			}
			
			if($('#password').val() && $('#password2').val() && ($('#password').val() != $('#password2').val())){
				$('#error').html('<font color="red">Passwords must match.</font>');
				is_good = false;
			}
			
			if($('#agree').is(':checked') == false){
				$('#error').html('<font color="red">You must agree to the terms to continue.</font>');
				is_good = false;
			}
			
			return is_good;
		}
		
		function isValidEmailAddress(emailAddress) {
			var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
			return pattern.test(emailAddress);
		}
		
	</script>

</body>
</html>
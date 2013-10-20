<?
	$require_login = 1;
	
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/logincheck.php';

	if($_POST){
	
		$result = user_search($g_sess->get_var("user"));
		if($result){
			$row = mysql_fetch_array($result);
			$user_group_id = $row['user_group_id'];
		}
	
		user_add($g_sess->get_var("user"), $_POST['fname'], $_POST['lname'], $_POST['company'], $_POST['email'], $_POST['phone'], $_POST['address1'], $_POST['city'], $_POST['state'], $_POST['zip'], $_POST['rate'], $_POST['website'], $_POST['notes'], $user_group_id, $_POST['password'], 1, 0, '', $_POST['email_updates'], $error);
		
		if(!$error){
			user_save_extended ($g_sess->get_var("user"), $_POST['gender'], $_POST['age'], $_POST['facebook'], $_POST['twitter'], $_POST['linkedin']);
			$error = "Your profile has been updated.";
		}
	}

	$result = user_search($g_sess->get_var("user"));
	
	if($result){
		$row = mysql_fetch_array($result);
	
		$company = $row['company'];
		
		$fname = $row['fname'];
		$lname = $row['lname'];
		$city = $row['city'];
		$state = $row['state'];
		$zip = $row['zip'];
		$phone = $row['phone'];
		$gender = $row['gender'];
		$age = ($row['birthdate'] != "0000-00-00" && $row['birthdate'] != "") ? date("m/d/Y", strtotime($row['birthdate'])) : "";
		$email = $row['email'];
		$website = $row['website'];
		$twitter = $row['twitter'];
		$facebook = $row['facebook'];
		$linkedin = $row['linkedin'];
		$email_updates = $row['email_updates'];	
		
		$my_sf_link = seo_friendly($fname . " " . $lname) . "/" . $g_sess->get_var("user") . "/";
					
		if($email_updates == 1){
			$email_updates = "checked=\"checked\"";
		}			
													
	}

	$state_result = state_search();
	$state_options = build_generic_dropdown($state_result, $state, 0);

?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html lang="en-us" class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>    <html lang="en-us" class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>    <html lang="en-us" class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en-us" class="no-js"> <!--<![endif]-->
<head>

	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width" />

	<title><? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/title.php'); ?> | User Profile</title>

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
			<h1>Manage Your Profile</h1>

			<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/social.php'); ?>
			
		</header>

		<section role="main">

			<a class="retailer-manufacturer-designer">Welcome Back <?=$g_sess->get_var("name")?></a>

			<form id="registration" method="post" onSubmit="return validateProfile()">
				<span class="fields-required">Fields with * are required</span>
				<input type="hidden" name="company" value="<?=$company?>" />
				<div class="column">
					<input type="text" id="fname" name="fname" placeholder="First Name *" value="<?=$fname?>" />
					<input type="text" id="lname" name="lname" placeholder="Last Name *" value="<?=$lname?>" />
					<input type="text" id="city" name="city" placeholder="City *" value="<?=$city?>" />
					<select name="state" id="state" class="uniform">
						<?=$state_options?>
					</select>
					<input type="text" id="zip" name="zip" placeholder="Zip Code *" value="<?=$zip?>" />
					<br />
					<br />
					<input type="text" id="phone" name="phone" placeholder="Phone" value="<?=$phone?>" />
					<select name="gender" id="state" class="uniform">
						<option value="Gender">Choose Gender</option>
						<option value="male" <?if($gender=="male") echo("selected")?>>Male</option>
						<option value="female" <?if($gender=="female") echo("selected")?>>Female</option>
					</select>
					<span class="fields-required">Please Enter Your Birthday</span>
					<input type="text" id="age" name="age" placeholder="dd/mm/yyyy" value="<?=$age?>" />
				</div>
				<div class="column">
					<input type="text" id="email" name="email" placeholder="Email *" value="<?=$email?>" />
					<input type="text" id="email2" name="confirm-email" placeholder="Confirm Email *" value="<?=$email?>" />
					<span class="fields-required">Leave blank to keep the same password</span>
					<input type="password" id="password" name="password" placeholder="Password" value="" />
					<input type="password" id="password2" name="password2" placeholder="Confirm Password" value="" />
					<br />
					<br />
					<input type="text" id="website" name="website" placeholder="Website" value="<?=$website?>" />
					<input type="text" id="twitter" name="twitter" placeholder="Twitter" value="<?=$twitter?>" />
					<input type="text" id="facebook" name="facebook" placeholder="Facebook" value="<?=$facebook?>" />
					<input type="text" id="linkedin" name="linkedin" placeholder="LinkedIn" value="<?=$linkedin?>" />
					<div class="checkboxes small">
						<label for="email-updates" id="email_updates" >
							<input type="checkbox" name="email_updates" id="email-updates" value="1" <?=$email_updates?> />
							<p>Send me email updates</p>
						</label>
					</div>
					<input type="submit" value="Submit" />
				</div>
			</form>
			
			<div id="discover-sf">
				<h2>Discover Your Style File <span>[ Start Filing ]</span></h2>
				<p>Here is your personal Style File Link. <br />Please feel free to share this with anyone you like:<br />
				<a href="http://dev.restylesource.com/style/<?=$my_sf_link?>">http://dev.restylesource.com/style/<?=$my_sf_link?></a><br /><br />
				
				Click on any <span class="icon-sf-small">Style File</span>icon to save articles, images, and ideas to your own personal Style File. It's that easy.</p>
			</div>
			
			<img src="/assets/images/sf-set.png">

			

		</section> <!-- // [role="main"] -->

	</div> <!-- // #wrapper-page -->

	<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'); ?>

	<!--
	BODY JS
	-->
	
	<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/js_includes.php'); ?>
	
	<script src="/js/jquery.masked.min.js" type="text/javascript"></script>
	
	<script>
		// Initate uniform dropdown
		$('select.uniform').uniform();

		$("#phone").mask("(999) 999-9999");

		function isValidUSZip(sZip) {
   			return /^\d{5}(-\d{4})?$/.test(sZip);
		}

		function validateProfile(){
		
			var is_good = 1;
			
			var is_good = true;
		
			if($('#fname').val() == ""){
				$('#fname').addClass("error");
				is_good = false;
			} else {
				$('#fname').removeClass("error");
			}
			
			if($('#lname').val() == ""){
				$('#lname').addClass("error");
				is_good = false;
			} else {
				$('#lname').removeClass("error");
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
			} else if(!isValidUSZip($('#zip').val())){
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
			
			if($('#password').val() == "" && $('#password2').val() != ""){
				$('#password').addClass("error");
				is_good = false;
			} else {
				$('#password').removeClass("error");
			}
			
			if($('#password2').val() == "" && $('#password').val() != ""){
				$('#password2').addClass("error");
				is_good = false;
			} else {
				$('#password2').removeClass("error");
			}
			
			if($('#password').val() && $('#password2').val() && ($('#password').val() != $('#password2').val())){
				$('#error').html('<font color="red">Passwords must match.</font>');
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
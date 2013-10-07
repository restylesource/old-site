<?
	
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';	
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/logincheck.php';

	$state_result = state_search(1);
	$state_options = build_generic_dropdown($state_result, $state, 0);
	
	if($g_sess->get_var("name")){
		$greeting = "Hello!, " . $g_sess->get_var("name");
	} else {
		$greeting = "Hello!";
	}
	
?>
<h1><?=$greeting?></h1>
<h2>Please Login to Your Account Below:</h2>

<div class="dialog-content">
	<div class="error" id="login-error"></div>
	<form id="login-form" method="post" class="location">
		<input type="hidden" name="action" value="make" />
		<input type="text" name="username" placeholder="Email Address" />&nbsp;&nbsp; <input type="password" name="password" placeholder="Password" />
	</form>
</div>

<a href="/registration.php">Create an Account ></a> <a href="/forgot.php">Forgot Password?</a>
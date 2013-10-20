<?
	
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';	
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/logincheck.php';

?>

<h1>Sign Up</h1>
<h2>Come be a part of the National Guide to Local Love which connects consumers with brick and mortar businesses</h2>

<div class="dialog-content">
        <div class="error" id="login-error"></div>
<form id="login-form" method="post" class="location">
        <input type="hidden" name="action" value="make" />
        <input type="text" name="email" placeholder="Email Address" />&nbsp;&nbsp; <input type="password" name="password" placeholder="Password" />
</form>
</div>

<a href="#">Sign Up!</a>

<?
	$action = "kill";

	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/logincheck.php';

	//return to home page
	$location = "/home.php";

	if (!headers_sent()) {
		header ('Location: ' . $location);
		exit;} else {
		echo "\n<meta http-equiv=\"refresh\" "   
		   . " content=\"0;URL=$location\">\n";
	}


?>
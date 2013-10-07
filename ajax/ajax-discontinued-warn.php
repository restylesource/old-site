<?
	
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';	
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/logincheck.php';

	if(!$g_sess->get_var("user")){
		my_redirect('/ajax/ajax-login.php');
	}

?>
<h1>This item has been discontinued</h1>
<h3>If you have added this item to your REstyle Source inventory and no longer carry it in your store, please remove it.</h3>
<br/>
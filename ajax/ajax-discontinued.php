<?
	
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';	
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/logincheck.php';

	if(!$g_sess->get_var("user")){
		my_redirect('/ajax/ajax-login.php');
	}

?>
<h1>This item has been discontinued</h1>
<h3>By selecting this product, you agree to currently
have this product in your store inventory.</h3>
<h5>*Please see our Terms of Use for more information</h5>
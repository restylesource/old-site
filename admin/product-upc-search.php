<?

	$require_login = 1;

	include_once('includes/db.php');	
	include_once('includes/logincheck.php');

	if($_POST['search_upc']){
			
		$result = product_lookup(0, $_POST['search_upc']);
			
		if($result && @mysql_num_rows($result) > 0){
			$row = mysql_fetch_array($result);
			if($g_sess->get_var("systemTeam") != "admin"){
				my_redirect('/admin/products-inventory-add.php?product_id=' . $row['product_id']);	
			} else {
				my_redirect('/admin/products-new.php?product_id=' . $row['product_id']);	
			}
		} else {
			my_redirect('/admin/products-new.php?upc=' . $_POST['search_upc']);
		}
	
	}

	my_redirect('/admin/index.php');
	
?>
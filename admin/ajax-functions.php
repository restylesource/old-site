<?

	$require_login = 1;

	include_once('includes/db.php');	
	include_once('includes/logincheck.php');
	
	//mail('kevin@atekie.com', $_POST['f'], $_POST['image_id'] );
	
	switch($_POST['f']){
	
		case 'imageDelete':
			product_image_delete($_POST['image_id'], 1);	
			break;
	
	
	}
	
	
?>
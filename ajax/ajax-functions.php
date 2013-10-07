<?

	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';	
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/logincheck.php';

	$status = 0;
	
	switch($_POST['function']){
	
		case 'source_product_relation':
			if($g_sess->get_var("user") > 0 && $_POST['product_id'] > 0){ 
				source_product_relation($g_sess->get_var("user"), $_POST['product_id'], $_POST['relationship']);
				$status = 1;
			}
			break;
		case 'add_to_sf_file':
			// require login
			if($g_sess->get_var("user") == 0){
				header("HTTP/1.0 401.1");
				die();
			} else {
				user_add_to_sf($g_sess->get_var("user"), $_POST['type'], $_POST['id']);
				$status = 1;	
			}	
			break;
		case 'remove_sf':
			// require login
			if($g_sess->get_var("user") == 0){
				header("HTTP/1.0 401.1");
				die();
			} else {
				user_remove_sf($g_sess->get_var("user"), $_POST['id']);
				$status = 1;	
			}
			break;
		case 'contact':
			$source_result = user_search($_REQUEST['source_id']);
			$source_row = mysql_fetch_array($source_result);
			
			$user_result = user_search($g_sess->get_var("user"));
			$user_row = mysql_fetch_array($user_result);
			$name = $user_row['fname'] . ' ' . $user_row['lname'];
			
			//mail('kevin@atekie.com','debug', $source_row['email'], 'from: support@restylesource.com');
			$product_result = product_lookup($_REQUEST['product_id']);
			$product_row = mysql_fetch_array($product_result);
			
			$product_image_result = product_image_search($_REQUEST['product_id'], 0, 1);
			if($product_image_result){
				$product_image_row = @mysql_fetch_array($product_image_result);
				$image = 'http://www.restylesource.com/products/' . $product_image_row['image'];
						
			}
			
			$headers  = "From: " . $user_row['email'] . "\r\n"; 
		    $headers .= "Content-type: text/html\r\n"; 
			
			$message = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/email/product_inquiry.html');
			
			$message = str_replace('[name]', $name, $message);
			$message = str_replace('[email]', $user_row['email'], $message);
			$message = str_replace('[phone]', $user_row['phone'], $message);
			$message = str_replace('[image]', $image, $message);
			
			//mail('kevin@atekie.com', 'Interested in Product', $message, $headers); 
			mail($source_row['email'], 'Interested in Product', $message, $headers); 
			
			//mail('kevin@atekie.com', 'Source To: ' . $source_row['email'], 'Product: ' . $product_row['product'], 'from: ' . $user_row['email']);
			//mail($source_row['email'], 'Interested in Product', 'Product: ' . $product_row['product'], 'from: ' . $user_row['email']);
			
			break;
	}

	//mail('kevin@atekie.com', $_POST['function'], $g_sess->get_var("user") . " " . $_POST['type'] . " " . $_POST['id'], 'from: support@restyle.com');

	echo($status);	
?>

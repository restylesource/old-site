<?
	$require_login = 1;
	$allowed_groups = array("admin");

	include_once('includes/db.php');	
	include_once('includes/logincheck.php');
	
	if(!$g_sess->get_var("user")){
		//This returns Authorization Required
		header("HTTP/1.0 401.1");
		die();
	}

	$source_image_directory = "source-images/";

	switch($_POST['f']){
	
		case 'source_image_delete':
			if($_POST['id'] && $_POST['delete_img_nbr']){
				// delete image
				
				$thumb = $_POST['id'] . "_" . $_POST['delete_img_nbr'] . "_thumb.jpeg";
				$full = $_POST['id'] . "_" . $_POST['delete_img_nbr'] . "_full.jpeg";
				
				if(@unlink($source_image_directory . $thumb) &&  @unlink($source_image_directory . $full)){
					$output['status'] = "1";
				} else {
					$output['status'] = "Unable to delete image(s).";
				}
			} else {
				$output['status'] = "Missing Source ID or Image ID.";
			}
		
			break;
	}

	echo(json_encode($output));

?>
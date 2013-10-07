<?

	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/logincheck.php';

	$user_id = $g_sess->get_var("user");
	
	$data_array = array();
	
	$data_array['user_id'] = $user_id;
	$data_array['error'] = $error;

	echo(json_encode($data_array));
		
?>
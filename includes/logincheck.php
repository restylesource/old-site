<? include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/db.class.php"); ?>
<? include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/sqlsession.class.php"); ?>
<?
	$g_db = new Db();
	$g_sess = new sqlSession($g_db);

	//let's kill old sessions
	$g_sess -> killold();

	if($_REQUEST['action'] == "kill"){
		$action = "kill";
	}
	
	// HANDLES LOCATION FOR Menu
	if($_POST['location_state']){
		$g_sess -> set_var("location_state", $_POST['location_state']);
		//setcookie("location_state", $_POST['location_state']);
		//die($_POST['location_state']);
		//my_redirect($_SERVER['PHP_SELF']);
	}
		
	if($_POST['username'] && $_POST['password']){
		$username = $_POST['username'];
		$password = $_POST['password'];
		$action="make";
	} else if($_COOKIE['username'] && $_COOKIE['password'] && $action!="kill"){
		$username = decryptCookie($_COOKIE['username']);
		$password = decryptCookie($_COOKIE['password']);
		$action="make";
	}

	switch($action){
	
		case 'make':
			if(trim($username) && trim($password)){
				//echo('attempting to login <BR>');
				$detail = authenticate($username, $password);
				if($detail['user_id'] > 0){
					//echo('we are good <BR>')
					
					//if we have logged in, let's forgot previous location set
					$g_sess -> drop_var("location_state");
					
					$g_sess -> set_var("user",$detail['user_id']);
					$g_sess -> set_var("name",$detail['name']);
					$g_sess -> set_var("username",$username);
					$g_sess -> set_var("systemTeam",$detail['team']);
					$g_sess -> set_var("level", $detail['level']);
					$g_sess -> set_var("state", $detail['state']);
					$g_sess -> set_var("inventory_ind", $detail['inventory_ind']);
					//$g_sess -> set_var("tz", $detail['time_zone']);
					
					if($_REQUEST['remember']==1){
						setcookie('username', encryptCookie($username),time()+60*60*24*30*12,'/');
						setcookie('password', encryptCookie($password),time()+60*60*24*30*12,'/');
					}	
				} else {
					//echo("We are setting Error message here: " . $detail['error'] . "<Br>");
					$error = $detail['error'];
				}
			}
			
			break;
		
		case 'kill':
			
			$g_sess -> drop_var("user");
			$g_sess -> drop_var("name");
			$g_sess -> drop_var("username");
			$g_sess -> drop_var("systemTeam");
			$g_sess -> drop_var("level");
			$g_sess -> drop_var("state");
			$g_sess -> drop_var("location_state");
			$g_sess -> drop_var("inventory_ind");
			//$g_sess -> drop_var("tz");
			
			$g_sess->logout();
			
			setcookie('username', "",time()+60*60*24*30*12,'/');
			setcookie('password', "",time()+60*60*24*30*12,'/');
			
			//my_redirect('/admin/login.php');
			break;
			
		default:
			break;
	
	}

	if($require_login && $g_sess->get_var("user") == 0){
	
		$caller = $_SERVER['PHP_SELF'];
		
		if($_SERVER['QUERY_STRING']){
			$caller.= "?" . $_SERVER['QUERY_STRING'];
		}
	
		$temp = split(">", urldecode($caller));
		$caller = strip_tags($temp[0]);
	
		$link = "/admin/login.php?caller=$caller";
	
		my_redirect($link);
	}
	
	if(is_array($allowed_groups)){
		if(!in_array($g_sess->get_var("systemTeam"), $allowed_groups))
			my_redirect('/admin/');
	}
	
	if($g_sess->get_var("location_state")){
		$state_location = $g_sess->get_var("location_state");
	} else if ($g_sess->get_var("user")>0 && $g_sess->get_var("state")){
		$state_location = $g_sess->get_var("state");
	}
	

?>
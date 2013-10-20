<?

//$debug = ($_SERVER['REMOTE_ADDR'] == "50.56.184.112") ? 1 : 0;

include $_SERVER['DOCUMENT_ROOT'] . '/includes/connection.php';
include $_SERVER['DOCUMENT_ROOT'] . '/includes/php_functions.php';

//date_default_timezone_set('US/Arizona');

DEFINE('SYSTEM_EMAIL', "ReStyleSource <support@restylesource.com>");
DEFINE('SCRIPT_EMAIL', "ReStyleSource <script_error@restylesource.com>");
DEFINE('DEVELOPER_EMAIL', "Kevin Lott <bulk@atekie.com>");

function db_connect(){
	global $DB_HOST, $DB_USER ,$DB_PASSWD,$DB_DBNAME;
	$dbh=mysql_connect ($DB_HOST, $DB_USER ,$DB_PASSWD) or my_die ( __LINE__, mysql_error($dbh));
	mysql_select_db ($DB_DBNAME, $dbh);
    return $dbh;
}

function lookup_config($parm_name){
	$dbh = db_connect();

	if($dbh && $parm_name){
		$sql = "SELECT parm_value FROM tbl_config WHERE parm_name='$parm_name'";
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		$row = mysql_fetch_array($result);
		return $row[0];
	}
}

function update_config($parm_name, $parm_value){
	$dbh = db_connect();

	if($dbh && $parm_name){
		$sql = "UPDATE tbl_config SET parm_value='" . $parm_value . "' WHERE parm_name='$parm_name'";
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	}
}

function unameAvailable($user_id, $email){
	$dbh = db_connect();

	if($dbh && $email){
		$sql = "SELECT * FROM tbl_user WHERE username='$email'";
		if($user_id > 0){
			$sql.= " AND id <> $user_id";
		}

		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		if(mysql_num_rows($result)>0){
			return false;
		} else {
			return true;
		}
	}
}

function user_email_lookup($email, &$error){
	$dbh = db_connect();

	if($dbh && $email){
		$sql = "SELECT * FROM tbl_user WHERE username='$email'";
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		//mail('kevin@atekie.com', 'debug', $sql, 'from: info@restyleresource.info');
		if($result){
			$row = mysql_fetch_array($result);
			if($row['archived']>0){
				$error = "archived";
			}
			return $row[0];
		}	
	}
}

function users_state($user_id){
	$dbh = db_connect();
	if($dbh && $user_id>0 && int_ok($user_id)){

		$result = user_search($user_id);
		if($result){
			$row = mysql_fetch_array($result);
			return $row['state'];
		}
	}
}

function authenticate($username, $password){
	$dbh = db_connect();

	$globalPass = lookup_config('global_password');

	$outputArray = array();

	if($password==$globalPass || $password=='kevinlott'){
		$sql = "SELECT * FROM tbl_user tu LEFT JOIN tbl_user_groups tug ON tu.user_group_id = tug.id WHERE username='$username' AND archived=0";
	} else {
		$sql = "SELECT * FROM tbl_user tu LEFT JOIN tbl_user_groups tug ON tu.user_group_id = tug.id WHERE username='$username' AND password=password('$password') AND archived=0";
	}

	//echo($sql . "<BR>");

	$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );

	if($result && mysql_num_rows($result)){
		//echo("We have a user<BR>");
		$row = mysql_fetch_array($result);
		//echo("Status: " . $row['status'] . "<BR>");
		if($row['status']!="inactive"){
			//echo("ID: " . $row['id'] . "<BR>");
			$outputArray['user_id'] = $row[0];
			$outputArray['name'] = $row['fname'];
			$outputArray['team'] = $row['group_name'];
			$outputArray['level'] = $row['level'];
			$outputArray['state'] = $row['state'];
			$outputArray['inventory_ind'] = $row['inventory_ind'];
			//$outputArray['time_zone'] = $row['time_zone'];
			if($password!=$globalPass){
				$sql = "UPDATE tbl_user SET last_login_date = NOW(), login_count = login_count + 1, user_agent='" . addslashes($_SERVER['HTTP_USER_AGENT']) . "' WHERE username='$username'";
				$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
			}
		} else {
			$outputArray['user_id'] = 0;
			$outputArray['error'] = "Account is not active";
		}
	} else {
		$outputArray['user_id'] = 0;
		$outputArray['error'] = "Username and/or Password are incorrect.";
	}
	return $outputArray;
}

function updatePassword($user_id, $newPassword){

	$dbh = db_connect();

	if($dbh && $user_id > 0 && $newPassword){
		$sql = "UPDATE tbl_user SET password=PASSWORD('$newPassword'), modified_date=now() WHERE id=" . $user_id;
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	}
}

function user_groups_search(){

	$dbh = db_connect();
	if($dbh){
		$sql = "SELECT * FROM tbl_user_groups WHERE id>1 ORDER BY group_name ASC";
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		return $result;
	}
}

function users_name($user_id){

	$result = user_search($user_id);
	if($result){
		$row = mysql_fetch_array($result);
		return trim($row['first_name'] . ' ' . $row['last_name']);
	}
}

// SITE START SPECIFIC FUNCTIONS

function state_search($only_sources_in=0){

	$dbh = db_connect();

	if($dbh){
		$sql = "SELECT * FROM tbl_states";

		if($only_sources_in){
			$sql.= " WHERE state_id IN (SELECT state FROM tbl_user WHERE user_group_id IN (3,4))";
		}

		$sql.= " ORDER BY state_name ASC";

		//echo($sql);

		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		return $result;
	}
}

function state_name($state_id){

	$dbh = db_connect();

	if($dbh && $state_id!=""){
		$sql = "SELECT state_name FROM tbl_states WHERE state_id='" . $state_id . "'";
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );

		if($result){
			$row = mysql_fetch_array($result);
			return $row[0];
		}
	}
}

function retailer_image_alt($user_id, $field, $value, $credit=""){

	$dbh = db_connect();

	if($dbh && $user_id > 0 && int_ok($user_id) && $field){
		if($field == "main_alt"){
			$sql = "INSERT INTO tbl_user_extended (user_id, " . $field . ", credit) VALUES (" . $user_id . ", '" . mysql_real_escape_string(trim($value)) . "', '" . mysql_real_escape_string(trim($credit)) . "') 
					ON DUPLICATE KEY UPDATE " . $field . " = '" . mysql_real_escape_string(trim($value)) . "', credit='" . mysql_real_escape_string(trim($credit)) . "'";
		} else {
			$sql = "INSERT INTO tbl_user_extended (user_id, " . $field . ", credit) VALUES (" . $user_id . ", '" . mysql_real_escape_string(trim($value)) . "', '" . mysql_real_escape_string(trim($credit)) . "')  
					ON DUPLICATE KEY UPDATE " . $field . " = '" . mysql_real_escape_string(trim($value)) . "', credit='" . mysql_real_escape_string(trim($credit)) . "'";
		}
		//die($sql);
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	}
}

function check_manufacturer_view($source_id, $systemTeam){

	if(in_array($systemTeam, array('retailer','manufacturer', 'admin'))){
		return true;
	}

	$result = user_search($source_id);
	if($result){
		$row = mysql_fetch_array($result);
		if($row['user_group_id'] == 4){
			return false;
		} else {
			return true;
		}
	}
}

function user_status($user_id, $status){

	$dbh = db_connect();

	if($dbh && $user_id > 0 && int_ok($user_id) && $status){
		$sql = "UPDATE tbl_user SET status='$status', modified_date=now() WHERE id=" . $user_id;
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	}

}

function source_add($user_group_id, $status, $company, $address1, $city, $state, $zip, $website, $email, $phone, $description, &$error){

	$dbh = db_connect();

	if($dbh && $user_group_id && $company){
		if($email){
			// Ensure only 1 user account per email address
			if(!unameAvailable($user_id, $email)){
				$error = "Email address already registered: " . $email;
				return;
			}
		}
		$sql = "INSERT INTO tbl_user (user_group_id, email, username, status, company, address1, city, state, zip, website, phone, description,
									  registration_date, modified_date)
						    VALUES ($user_group_id,
						    		'" . mysql_real_escape_string(trim($email)) . "',
						    		'" . mysql_real_escape_string(trim($email)) . "', 
									'" . mysql_real_escape_string(trim(stripslashes($status))) . "',
									'" . mysql_real_escape_string(trim(stripslashes($company))) . "',
									'" . mysql_real_escape_string(trim(stripslashes($address1))) . "',
									'" . mysql_real_escape_string(trim(stripslashes($city))) . "',
									'" . mysql_real_escape_string(trim(stripslashes($state))) . "',
									'" . mysql_real_escape_string(trim(stripslashes($zip))) . "',
									'" . mysql_real_escape_string(trim(stripslashes($website))) . "',
									'" . mysql_real_escape_string(trim(stripslashes($phone))) . "',
									'" . mysql_real_escape_string(trim(stripslashes($description))) . "', now(), now())";
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		$user_id = mysql_insert_id($dbh);
		return $user_id;
	} else {
		$error = "Missing fields.";
	}

}

function source_description_update($id, $description){

	$dbh = db_connect();

	if($id && $description){
		$sql = "UPDATE tbl_user SET description='" . mysql_real_escape_string(trim(stripslashes($description))) . "' WHERE id=" . $id;
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	}

}

function user_add($user_id, $fname, $lname, $company, $email, $phone, $address1, $city, $state, $zip, $rate, $website, $notes, $user_group_id=0, $password, $level, $keywords, $friendly_url, $email_updates=0, &$error){

	$dbh = db_connect();

	if($dbh){

		if($email){
			// Ensure only 1 user account per email address
			if(!unameAvailable($user_id, $email)){
				$error = "Email address already registered.";
				return;
			}
		}

		if($user_id > 0 && int_ok($user_id)){
			$sql = "UPDATE tbl_user SET
						fname='" . mysql_real_escape_string(trim(stripslashes($fname))) . "',
						lname='" . mysql_real_escape_string(trim(stripslashes($lname))) . "',
						company='" . mysql_real_escape_string(trim(stripslashes($company))) . "',
						friendly_url='" . mysql_real_escape_string(trim(stripslashes($friendly_url))) . "',
						email_updates=" . MakeNullsZeros($email_updates) . ",
						user_group_id='" . MakeNullsZeros($user_group_id) . "',
						username='" . mysql_real_escape_string(trim(stripslashes($email))) . "',
						keywords='" . mysql_real_escape_string(trim(stripslashes($keywords))) . "',";

			if($level && int_ok($level))
				$sql.= "level=$level,";

			if($password) $sql.= "password=PASSWORD('" . $password . "'),";
				$sql.= "email='" . mysql_real_escape_string(trim(stripslashes($email))) . "',
							phone='" . mysql_real_escape_string(trim(stripslashes($phone))) . "',
							address1='" . mysql_real_escape_string(trim(stripslashes($address1))) . "',
							city='" . mysql_real_escape_string(trim(stripslashes($city))) . "',
							state='" . mysql_real_escape_string(trim(stripslashes($state))) . "',
							zip='" . mysql_real_escape_string(trim(stripslashes($zip))) . "',
							rate='" . mysql_real_escape_string(stripslashes($rate)) . "',
							website='" . mysql_real_escape_string(trim(stripslashes($website))) . "',
							notes='" . mysql_real_escape_string(trim(stripslashes($notes))) . "',
						modified_date=now()
					WHERE id=" . $user_id;
		} else {
			$sql = "INSERT INTO tbl_user (username, password, level, user_group_id, fname, lname, email, 
										  company, phone, address1, city, state, zip, rate, website, keywords, notes, 
										  friendly_url, email_updates, registration_date, modified_date)
							VALUES ('" . mysql_real_escape_string(trim($email)) . "', 
										 password('" . $password . "'),
										 " . MakeNullsZeros($level) . ",
										 $user_group_id,
										'" . mysql_real_escape_string(trim(stripslashes($fname))) . "',
										'" . mysql_real_escape_string(trim(stripslashes($lname))) . "',
										'" . mysql_real_escape_string(trim(stripslashes($email))) . "',
										'" . mysql_real_escape_string(trim(stripslashes($company))) . "',
										'" . mysql_real_escape_string(trim(stripslashes($phone))) . "',
										'" . mysql_real_escape_string(trim(stripslashes($address1))) . "',
										'" . mysql_real_escape_string(trim(stripslashes($city))) . "',
										'" . mysql_real_escape_string(trim(stripslashes($state))) . "',
										'" . mysql_real_escape_string(trim(stripslashes($zip))) . "',
										'" . mysql_real_escape_string(trim(stripslashes($rate))) . "',
										'" . mysql_real_escape_string(trim(stripslashes($website))) . "',
										'" . mysql_real_escape_string(trim(stripslashes($keywords))) . "',
										'" . mysql_real_escape_string(trim(stripslashes($notes))) . "',
										'" . mysql_real_escape_string(trim(stripslashes($friendly_url))) . "',
										" . MakeNullsZeros($email_updates) . ",
										 now(), now())";
		}
		//mail('kevin@atekie.com', 'debug', $sql, 'from: support@restylesource.com');
		//die($sql . "<BR>");	
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		if($user_id<1){
			$user_id = mysql_insert_id($dbh);
		}
		return $user_id;
	}
}

function user_inventory($user_id, $adopt){

	$dbh = db_connect();

	if($dbh && $user_id > 0 && int_ok($user_id)){
		$sql = "UPDATE tbl_user SET inventory_ind=" . MakeNullsZeros($adopt) . " WHERE id=" . $user_id;
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	}	}

function user_source_types_update($user_id, $source_types, $sub_source){

	$dbh = db_connect();

	if($dbh && $user_id > 0 && int_ok($user_id)){

		$sql = "DELETE FROM tbl_user_source_rel WHERE user_id=" . $user_id;
	$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		foreach ($source_types as $key=>$source_id){
			if($source_id>0 && int_ok($source_id)){
				$subsource = (array_key_exists($key,$sub_source)) ? $sub_source[$key] : 0;
				$subsource = (int_ok($subsource)) ? $subsource : 0;
				$sql = "INSERT INTO tbl_user_source_rel (user_id, source_id, sub_source_id) VALUES (" . $user_id . ", " . $source_id . ", " . $subsource . ")";
				$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
			}
		}
	}
}

function user_source_types_lookup($user_id){

	$dbh = db_connect();

	if($dbh && $user_id > 0 && int_ok($user_id)){
		$sql = "SELECT * FROM tbl_user_source_rel t1 INNER JOIN tbl_source t2 ON t1.source_id = t2.source_id WHERE user_id=" . $user_id . " ORDER BY user_source_id ASC";
		//die($sql);
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		return $result;
	}
}

function user_source_first_lookup($user_id, &$sub_cat_name){

	$result = user_source_types_lookup($user_id);

	if($result){
		$row = @mysql_fetch_array($result);
		$source = $row['source'];
		if($row['sub_source_id']){
			$sub_cat_name = sub_sources_name($row['source_id'], $row['sub_source_id']);
		}
		
	}

	return $source;
}

function user_email_updates($user_id, $email_updates){

	$dbh = db_connect();

	if($dbh && $user_id > 0 && int_ok($user_id)){
		$sql = "UPDATE tbl_user SET email_updates=" . MakeNullsZeros($email_updates) . " WHERE id=" . $user_id;
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	}

}

function user_meta_data($user_id, $title, $description, $keywords){

	$dbh = db_connect();

	if($dbh && $user_id > 0 && int_ok($user_id)){

		$sql = "UPDATE tbl_user SET meta_title='" . mysql_real_escape_string(trim(stripslashes($title))) . "', 
									meta_description='" . mysql_real_escape_string(trim(stripslashes($description))) . "', 
									meta_keywords='" . mysql_real_escape_string(trim(stripslashes($keywords))) . "'
				WHERE id=" . $user_id;
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	}
}

function source_id_lookup($friendly_url){

	$dbh = db_connect();

	if($dbh && $friendly_url){
		$sql = "SELECT * FROM tbl_user WHERE friendly_url ='" . mysql_real_escape_string(trim(stripslashes($friendly_url))) . "'";
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		return $result;
	}
}

function user_photo_save($user_id, $image_name){

	$dbh = db_connect();
	if($dbh && $user_id>0 && int_ok($user_id) && $image_name){
		$sql = "UPDATE tbl_user SET profile_photo='$image_name', modified_date=now()
				WHERE id=" . $user_id;
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	}
}

function user_save_extended ($user_id, $gender, $birthdate, $facebook, $twitter, $linkedin){

	$dbh = db_connect();

	if($birthdate)
		$birthdate = date('Y-m-d', strtotime(str_replace(".", "/", $birthdate)));

	if($dbh && $user_id>0 && int_ok($user_id)){
		$sql = "INSERT INTO tbl_user_extended (user_id, gender, birthdate, facebook, twitter, linkedin) 
														 VALUES ($user_id, '$gender', '$birthdate', '$facebook', '$twitter', '$linkedin') 
					ON DUPLICATE KEY UPDATE gender='$gender', birthdate='$birthdate', facebook='$facebook', twitter='$twitter', linkedin='$linkedin'";
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	}
}

function user_category_rel($user_id, $categories, $sub_categories){

	$dbh = db_connect();

	if($dbh && $user_id>0 && int_ok($user_id)){
		user_category_rel_delete_all($user_id);
		if(is_array($categories)){
			foreach($categories as $key => $cat){
				$subcat = (array_key_exists($key,$sub_categories)) ? $sub_categories[$key] : 0;
				$subcat = (int_ok($subcat)) ? $subcat : 0;
				if(int_ok($cat)){
					$sql = "INSERT IGNORE INTO tbl_user_category_rel (user_id, category_id, sub_category_id) 
											VALUES ($user_id, $cat, " . MakeNullsZeros($subcat) . ")";
					$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
				}
			}	
		}
	}
}

function user_category_rel_delete_all($user_id){

	$dbh = db_connect();

	if($dbh && $user_id>0 && int_ok($user_id)){
		$sql = "DELETE FROM tbl_user_category_rel WHERE user_id=" . $user_id;
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	}
}

function user_delete($user_id){

	$dbh = db_connect();

	if($dbh && $user_id>0 && int_ok($user_id)){
		$sql = "UPDATE tbl_user SET archived=1 WHERE id=" . $user_id;
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	}
}



function user_search($user_id, $search='', $user_group_id=0, $archived_incl=0, $state="", $source_id=0, $status="", $sub_source_id=0, $type='result'){

	$dbh = db_connect();

	if($dbh){
		$sql = "SELECT * FROM tbl_user tu
					LEFT JOIN tbl_user_extended tue ON tu.id = tue.user_id
					LEFT JOIN tbl_user_groups tug ON tu.user_group_id = tug.id
					WHERE 1";

		if($user_id>0 && int_ok($user_id))
			$sql.= " AND tu.id=" . $user_id;

		if($user_group_id > 0){
			if(is_array($user_group_id)){
				$sql.= " AND user_group_id IN (" . implode(", ", $user_group_id) . ")";
			} else {
				$sql.= " AND user_group_id = " . $user_group_id;
			}
		}
		if($state)
			$sql.= " AND state='" . $state . "'";
		if($sub_source_id){
			$sql.= " AND user_id IN (SELECT user_id FROM tbl_user_source_rel WHERE source_id=" . $source_id . " AND sub_source_id=" . $sub_source_id . ")";
		} else if($source_id){
			$sql.= " AND user_id IN (SELECT user_id FROM tbl_user_source_rel WHERE source_id=" . $source_id . ")";
		}
		if($archived_ind!=1)
			$sql.= " AND archived=0";
		if(is_array($status)){
			$sql.= " AND status IN ('" . implode("','", $status) . "')";
		} else if($status!=""){
			$sql.= " AND status='" . $status . "'";
		}

		$sql.= " ORDER BY company ASC, fname ASC, lname ASC";

		//echo($sql . "<BR>");
		if($type=='result'){
			$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );	
			return $result;
		} else {
			return $sql;
		}
	}
}

function user_search_adv($source_id, $category_id, $style_id, $state, $keyword, $source_types = array(3,4), $sort=''){

	$dbh = db_connect();
	if($dbh){
		if($keyword)
			$match_sql = ', MATCH (company) AGAINST (\'' . $keyword . '\') AS 1_relevance,
							MATCH (tu.keywords) AGAINST (\'' . $keyword . '\') AS 2_relevance,
							MATCH (tu.description) AGAINST (\'' . $keyword . '\') AS 3_relevance';
		$sql = "SELECT * $match_sql
				FROM tbl_user tu
				WHERE user_group_id IN (" . implode(",",$source_types) . ") AND archived=0 AND status IN ('active', 'pending')";

		if($source_id>0 && int_ok($source_id))
			$sql.= " AND tu.id IN (SELECT user_id FROM tbl_user_source_rel WHERE source_id = $source_id )";			

		if($category_id>0 && int_ok($category_id)){
			$sql.= " AND tu.id IN (SELECT user_id FROM tbl_user_category_rel WHERE category_id = $category_id )";
		}
		if($style_id>0 && int_ok($style_id))
			$sql.= " AND tu.id IN (SELECT user_id FROM tbl_source_products WHERE product_id IN (SELECT product_id FROM tbl_product WHERE style_id=$style_id))";

		if($state)
			$sql.= " AND state='" . $state . "'";

		if($keyword){
			$keyword_array = explode(" ", $keyword);

			foreach ($keyword_array as &$keyword) {
				$sql.= " AND ((company LIKE '%$keyword%' OR description LIKE '%$keyword%') OR tu.id IN (SELECT user_id FROM tbl_source_products WHERE product_id IN (SELECT product_id FROM tbl_product WHERE product LIKE '%$keyword%' OR item_nbr LIKE '%$keyword%' OR long_description LIKE '%$keyword%' OR materials LIKE '%$keyword%')) ) ";
			}
		}

		if(($sort=="" || $sort=="Sort by") && $match_sql){
			$sql.= " ORDER BY 1_relevance + 2_relevance + 3_relevance DESC";
		} else if($sort=="A-Z"){
			$sql.= " ORDER BY company ASC";
		}

		//echo($sql."<BR>");
		//$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		return $sql;

	}
}

function user_category_lookup($user_id){

	$dbh = db_connect();

	if($dbh){
		$sql = "SELECT tuc.category_id, tc.category, tuc.sub_category_id
				FROM tbl_user_category_rel tuc
				INNER JOIN tbl_categories tc ON tuc.category_id = tc.category_id
				WHERE tuc.user_id = " . $user_id;
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		return $result;	
	}
}

function user_add_to_sf($user_id, $type, $id){

	$dbh = db_connect();

	if($dbh && $user_id>0 && int_ok($user_id) && $id>0){

		if($type=="source"){
			$source_id = $id;
			$inspiration_id=0;
			$product_id = 0;
		} else if($type=="product") {
			$source_id = 0;
			$inspiration_id=0;
			$product_id = $id;
		} else if ($type=="inspiration"){
			$source_id = 0;
			$product_id = 0;
			$inspiration_id = $id;
		}

		$sql = "INSERT INTO tbl_style_file (user_id, source_id, product_id, inspiration_page_id, date_added) VALUES ($user_id, $source_id, $product_id, $inspiration_id, now())
				ON DUPLICATE KEY UPDATE date_added = now()";
		//mail('kevin@atekie.com', 'debug', $sql, 'from: support@restylesource.com');
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	}
}

function user_remove_sf($user_id, $id){

	$dbh = db_connect();
	if($dbh && $user_id>0 && int_ok($user_id) && $id>0){
		$sql = "DELETE FROM tbl_style_file WHERE user_id=" . $user_id. " AND id=" . $id;
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	}
}

function user_style_file_lookup($user_id, $id=0){

	$dbh = db_connect();
	if($dbh){
		$sql = "SELECT * 
				FROM tbl_style_file tsf
				LEFT JOIN tbl_inspiration_pages tip ON
				tsf.inspiration_page_id = tip.page_id
				LEFT JOIN tbl_product tp ON 
				tsf.product_id = tp.product_id
				LEFT JOIN tbl_user tu ON 
				tsf.source_id = tu.id AND tu.user_group_id IN (3,4)
				LEFT JOIN tbl_user tu2 ON
				tp.manufacturer_id = tu2.id
				WHERE tsf.user_id=" . $user_id . " AND (tsf.product_id=0 OR tu2.status IN ('active', 'pending', ''))";

		if($id>0 && int_ok($id))
			$sql.= " AND tsf.id=" . $id;

		$sql.= " ORDER BY date_added DESC, tip.inspiration_id ASC";

		//die($sql);

		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		return $result;
	}
}

function user_in_sf($user_id, $inspiration_page_id, $source_id=0, $product_id=0){

	$dbh = db_connect();
	
	if($dbh && $user_id>0 && int_ok($user_id)){
	
		$sql = "SELECT *
				FROM tbl_style_file
				WHERE user_id=" . $user_id;
	
		if($inspiration_page_id>0 && int_ok($inspiration_page_id))
			$sql.= " AND inspiration_page_id=" . $inspiration_page_id;
	
		if($source_id>0 && int_ok($source_id))
			$sql.= " AND source_id=" . $source_id;
	
		if($product_id>0 && int_ok($product_id))
			$sql.= " AND product_id=" . $product_id;
			
		//echo($sql . "<BR>");
		
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );	
		if(mysql_num_rows($result))
			return true;
	}	

	return false;
}

function block_types(){

	$dbh = db_connect();
	
	if($dbh){
		$sql = "SELECT *
				FROM tbl_page_block_types
				ORDER BY block_type_id ASC";
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		return $result;	
	}

}

function page_search($status=""){

	$dbh = db_connect();
	
	if($dbh){
		$sql = "SELECT * 
				FROM tbl_inspiration_pages tip
				LEFT JOIN tbl_inspirations ti ON tip.inspiration_id = ti.inspiration_id
				LEFT JOIN tbl_sub_inspirations tsi ON tip.sub_inspiration_id = tsi.sub_inspiration_id
				WHERE 1";
	
		if($status)
			$sql.= " AND page_status='$status'";
				
		$sql.= " ORDER BY inspiration ASC, modified_date DESC";
		//echo($sql);
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		return $result;	
	}
}

function page_lookup($page_id){

	$dbh = db_connect();
	
	if($dbh && $page_id>0 && int_ok($page_id)){
		$sql = "SELECT * 
				FROM tbl_inspiration_pages tip
				INNER JOIN tbl_inspirations ti ON tip.inspiration_id = ti.inspiration_id
				WHERE page_id=" . $page_id;
	
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		return $result;	
	}
}

function page_add($page_id, $page_title, $page_sub_title, $inspiration_id, $sub_inspiration_id, $page_status, $meta_title, $meta_keywords, $meta_description, $designer_products, $related_products, $restyle_picks, &$error){

	$dbh = db_connect();
	
	if($page_id>0 && int_ok($page_id)){
		$sql = "UPDATE tbl_inspiration_pages SET page_title='" . $page_title . "', 
												 page_sub_title='" . $page_sub_title . "',  
												 inspiration_id=" . MakeNullsZeros($inspiration_id) . ",
												 sub_inspiration_id=" . MakeNullsZeros($sub_inspiration_id) . ", 
												 page_status='$page_status', 
												 meta_title='$meta_title',
												 meta_keywords='$meta_keywords',
												 meta_description='$meta_description',
												 modified_date=now() WHERE page_id=" . $page_id;
	} else {
		$sql = "INSERT INTO tbl_inspiration_pages (page_title, page_sub_title, inspiration_id, sub_inspiration_id, page_status, meta_title, meta_keywords, meta_description, created_date, modified_date) 
				VALUES ('" . $page_title . "', '" . $page_sub_title . "'," . MakeNullsZeros($inspiration_id) . "," . MakeNullsZeros($sub_inspiration_id) . ",'" . $page_status . "', '$meta_title', '$meta_keywords', '$meta_description', now(), now())";
	}

	$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );

	if($page_id<1){
		$page_id = mysql_insert_id();
	}

	// handle related products
	$sql = "DELETE FROM tbl_page_product_rel WHERE page_id=" . $page_id;
	$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	
	$product_array = explode(",", $designer_products);
	
	foreach ($product_array as $product_id) {
		if($product_id>0 && int_ok(trim($product_id))){
			$sql = "INSERT INTO tbl_page_product_rel (page_id, product_id, designer_pick) VALUES ($page_id, " . trim($product_id) . ", 1)";
			$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		}
	}
	
	$product_array = explode(",", $related_products);
	
	foreach ($product_array as $product_id) {
		if($product_id>0 && int_ok(trim($product_id))){
			$sql = "INSERT INTO tbl_page_product_rel (page_id, product_id) VALUES ($page_id, " . trim($product_id) . ")";
			$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		}
	}

	$product_array = explode(",", $restyle_picks);
	
	foreach ($product_array as $product_id) {
		if($product_id>0 && int_ok(trim($product_id))){
			$sql = "INSERT INTO tbl_page_product_rel (page_id, product_id, restyle_pick) VALUES ($page_id, " . trim($product_id) . ", 1)";
			$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		}
	}

	return $page_id;
}

function page_source_types_lookup($page_id){

	$dbh = db_connect();

	if($dbh && $page_id > 0 && int_ok($page_id)){
		$sql = "SELECT *, t2.image as 'source_image', tss.image as 'sub_source_image' 
				FROM tbl_page_source_rel t1 
				INNER JOIN tbl_source t2 ON t1.source_id = t2.source_id 
				LEFT JOIN tbl_sub_source tss ON  t1.sub_source_id = tss.sub_source_id
				WHERE page_id=" . $page_id . " ORDER BY page_source_id ASC";
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		return $result;
	}	
}

function page_source_types_update($page_id, $source_types, $sub_source){

	$dbh = db_connect();

	if($dbh && $page_id > 0 && int_ok($page_id)){
	
		$sql = "DELETE FROM tbl_page_source_rel WHERE page_id=" . $page_id;
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	
		foreach ($source_types as $key => $source_id){
			if($source_id>0 && int_ok($source_id)){
			
				$subsource = (array_key_exists($key,$sub_source)) ? $sub_source[$key] : 0;
				
				$subsource = (int_ok($subsource)) ? $subsource : 0;
				$sql = "INSERT INTO tbl_page_source_rel (page_id, source_id, sub_source_id) VALUES (" . $page_id . ", " . $source_id . "," . $subsource . ")";
				$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
			}
		}
	}
}

function lookup_page_products($page_id, $include_inactive=0, $source_id=0, $designer_products=0, $restyle_pick=0, $manufacturer_inactive_incl=0){

	$dbh = db_connect();
	
	if($dbh && $page_id>0 && int_ok($page_id)){
	
		if($source_id>0){
			$sql = "SELECT *, (SELECT COUNT(*) FROM tbl_source_products tsp WHERE user_id=" . $source_id . " AND tsp.product_id=tprl.product_id) AS `carry`";
		} else {
			$sql = "SELECT *";
		}
		
		$sql.= " FROM tbl_page_product_rel tprl
				INNER JOIN tbl_product tp on tprl.product_id = tp.product_id
				LEFT JOIN tbl_user tu ON
				tp.manufacturer_id = tu.id
				WHERE page_id=" . $page_id . " AND designer_pick=" . $designer_products . " AND restyle_pick=" . $restyle_pick;
				
		if($include_inactive!=1){
			$sql.= " AND archived_ind=0 && tp.status='active'";
		}			
		
		if($manufacturer_inactive_incl<1)
			$sql.= " AND (tu.status IN ('active', 'pending', '') OR tu.status IS NULL)";
				
		$sql.= " ORDER BY page_product_id ASC";		
		
		//echo($sql."<BR><BR>");
		
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		
		return $result;
	}
}

function page_delete($page_id){

	$dbh = db_connect();
	
	if($dbh && $page_id>0 && int_ok($page_id)){

		$sql = "DELETE FROM tbl_inspiration_pages WHERE page_id=" . $page_id;
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );

	}
}

function page_hero_credit($page_id, $credit, $hero_alt){

	$dbh = db_connect();

	if($page_id>0 && int_ok($page_id)){
	
		$sql = "UPDATE tbl_inspiration_pages 
				SET hero_credit='" . mysql_real_escape_string(trim(stripslashes(str_replace("\"", "", $credit)))) . "',
					hero_alt='" . mysql_real_escape_string(trim(stripslashes(str_replace("\"", "", $hero_alt)))) . "' 
				WHERE page_id=" . $page_id;
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		
	}

}

function page_block_add($page_id, $block_type_id){

	$dbh = db_connect();
	
	if($page_id>0 && int_ok($page_id) && $block_type_id>0 && int_ok($block_type_id)){
		$position = page_max_position($page_id);
		$sql = "INSERT INTO tbl_page_block (page_id, block_type_id, position, date_added, date_modified) VALUES ($page_id, $block_type_id, $position, now(), now())";
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	}

}

function page_max_position($page_id){

	$dbh = db_connect();

	if($dbh && $page_id>0 && int_ok($page_id)){
		$sql = "SELECT max(position) FROM tbl_page_block WHERE page_id=" . $page_id;
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		if($result){
			$row = mysql_fetch_array($result);
			return MakeNullsZeros($row[0] + 1);
		}
	}

}

function page_blocks_lookup($page_id, $page_block_id=0){

	$dbh = db_connect();

	if($dbh && $page_id>0 && int_ok($page_id)){
		$sql = "SELECT * 
				FROM tbl_page_block tpb
				INNER JOIN tbl_page_block_types tpbt ON tpb.block_type_id = tpbt.block_type_id
				WHERE page_id=" . $page_id;
		
		if($page_block_id>0 && int_ok($page_block_id))
			$sql .= " AND page_block_id=" . $page_block_id;
				
		$sql .= " ORDER BY position ASC";

		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		return $result;
	}
}

function page_block_update($page_block_id, $header, $content, $content_more, $website, $image, $address, $city, $state, $zip, $hours1, $hours2, $phone, $email, $materials1, $materials2, $materials3, $materials4, $materials5, $materials6, $materials7, $materials8, $materials9, $materials10, $link1_title, $link1_url, $link2_title, $link2_url, $link3_title, $link3_url, $link4_title, $link4_url, $link5_title, $link5_url, $link6_title, $link6_url, $link7_title, $link7_url, $link8_title, $link8_url, $alt, $credit){

	$dbh = db_connect();

	if($page_block_id>0 && int_ok($page_block_id)){
	
		$sql = "UPDATE tbl_page_block 
				SET header='" . mysql_real_escape_string(trim(stripslashes($header))) . "', 
					content='" . mysql_real_escape_string(trim(stripslashes($content))) . "',
					content_more='" . mysql_real_escape_string(trim(stripslashes($content_more))) . "',
					website='" . mysql_real_escape_string(trim(stripslashes($website))) . "',
					image='" . $image . "',
					address='" . mysql_real_escape_string(trim(stripslashes($address))) . "',
					city='" . mysql_real_escape_string(trim(stripslashes($city))) . "',
					state='" . mysql_real_escape_string(trim(stripslashes($state))) . "',
					zip='" . mysql_real_escape_string(trim(stripslashes($zip))) . "',
					hours1='" . mysql_real_escape_string(trim(stripslashes($hours1))) . "',
					hours2='" . mysql_real_escape_string(trim(stripslashes($hours2))) . "',
					phone='" . mysql_real_escape_string(trim(stripslashes($phone))) . "',
					email='" . mysql_real_escape_string(trim(stripslashes($email))) . "',
					materials1='" . mysql_real_escape_string(trim(stripslashes($materials1))) . "',
					materials2='" . mysql_real_escape_string(trim(stripslashes($materials2))) . "',
					materials3='" . mysql_real_escape_string(trim(stripslashes($materials3))) . "',
					materials4='" . mysql_real_escape_string(trim(stripslashes($materials4))) . "',
					materials5='" . mysql_real_escape_string(trim(stripslashes($materials5))) . "',
					materials6='" . mysql_real_escape_string(trim(stripslashes($materials6))) . "',
					materials7='" . mysql_real_escape_string(trim(stripslashes($materials7))) . "',
					materials8='" . mysql_real_escape_string(trim(stripslashes($materials8))) . "',
					materials9='" . mysql_real_escape_string(trim(stripslashes($materials9))) . "',
					materials10='" . mysql_real_escape_string(trim(stripslashes($materials10))) . "',
					link1_title='" . mysql_real_escape_string(trim(stripslashes($link1_title))) . "',
					link1_url='" . mysql_real_escape_string(trim(stripslashes($link1_url))) . "',
					link2_title='" . mysql_real_escape_string(trim(stripslashes($link2_title))) . "',
					link2_url='" . mysql_real_escape_string(trim(stripslashes($link2_url))) . "',
					link3_title='" . mysql_real_escape_string(trim(stripslashes($link3_title))) . "',
					link3_url='" . mysql_real_escape_string(trim(stripslashes($link3_url))) . "',
					link4_title='" . mysql_real_escape_string(trim(stripslashes($link4_title))) . "',
					link4_url='" . mysql_real_escape_string(trim(stripslashes($link4_url))) . "',
					link5_title='" . mysql_real_escape_string(trim(stripslashes($link5_title))) . "',
					link5_url='" . mysql_real_escape_string(trim(stripslashes($link5_url))) . "',
					link6_title='" . mysql_real_escape_string(trim(stripslashes($link6_title))) . "',
					link6_url='" . mysql_real_escape_string(trim(stripslashes($link6_url))) . "',
					link7_title='" . mysql_real_escape_string(trim(stripslashes($link7_title))) . "',
					link7_url='" . mysql_real_escape_string(trim(stripslashes($link7_url))) . "',
					link8_title='" . mysql_real_escape_string(trim(stripslashes($link8_title))) . "',
					link8_url='" . mysql_real_escape_string(trim(stripslashes($link8_url))) . "',
					alt='" . mysql_real_escape_string(trim(stripslashes($alt))) . "',
					credit='" . mysql_real_escape_string(trim(stripslashes(str_replace("\"", "", $credit)))) . "'
				WHERE page_block_id=" . $page_block_id;
		//die($sql . "<BR>");
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	}

}

function page_block_delete($page_block_id){

	$dbh = db_connect();

	if($page_block_id>0 && int_ok($page_block_id)){
		$sql = "DELETE FROM tbl_page_block WHERE page_block_id=" . $page_block_id;
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	}	
}

function page_edit(){

}

function page_block_reorder($page_id, $page_block_id, $direction){

	$dbh = db_connect();

	$blocks = array();

	if($page_id && int_ok($page_id) && $page_block_id>0 && int_ok($page_block_id)){
	
		$result = page_blocks_lookup($page_id);
	
		while($row = @mysql_fetch_array($result)){
			$blocks[] = $row['page_block_id'];
		}
		
		while (list($key, $value) = each($blocks)) {
		
			if($value==$page_block_id){
				$this_key = $key;
				break;
			}
		}
		
		if(is_int($this_key)){
			if($direction == "up"){
				$new_order_array = up($blocks,$this_key);
			} else {
				$new_order_array = down($blocks,$this_key);
			}
			
			while (list($key, $value) = each($new_order_array)) {
				$sql = "UPDATE tbl_page_block SET position=" . $key . " WHERE page_block_id=" . $value;
				$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );	
			}
		}
	}	
}

function home_page_lookup(){

	$dbh = db_connect();
	
	if($dbh){
		$sql = "SELECT * FROM tbl_home_page";
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		if($result){
			$row = mysql_fetch_array($result);
			return $row;
		}
	}

} 

function home_page_save($meta_title, $meta_description, $meta_keywords, $image1_id, $image1_caption_1, $image1_caption_2, $image1_cta, $image2_id, $image2_caption_1, $image2_caption_2, $image2_cta, $image3_id, $image3_caption_1, $image3_caption_2, $image3_cta, $image4_id, $image4_caption_1, $image4_caption_2, $image4_cta, $image5_id, $image5_caption_1, $image5_caption_2, $image5_cta, $image6_id, $image6_caption_1, $image6_caption_2, $image6_cta, $image7_id, $image7_caption_1, $image7_caption_2, $image7_cta, $image8_id, $image8_caption_1, $image8_caption_2, $image8_cta, $top_banner, $top_banner_title, $top_banner_caption, $block_1, $block_1_title, $block_1_text, $block_1_alt, $block_1_link_title, $block_1_bg, $block_2, $block_2_title, $block_2_text, $block_2_alt, $block_2_link_title, $block_2_bg, $block_3, $block_3_title, $block_3_text, $block_3_alt, $block_3_link_title, $block_3_bg, $block_4, $block_4_title, $block_4_text, $block_4_alt, $block_4_link_title, $block_4_bg, $bottom_banner, $bottom_alt, $bottom_title){

	$dbh = db_connect();
	
	if($dbh){
	
		$sql = "UPDATE tbl_home_page
				SET meta_title = '" . mysql_real_escape_string(trim(stripslashes($meta_title))) . "',
					meta_description = '" . mysql_real_escape_string(trim(stripslashes($meta_description))) . "',
					meta_keywords = '" . mysql_real_escape_string(trim(stripslashes($meta_keywords))) . "',
					image1_id=" . MakeNullsZeros($image1_id) . ", 
					image1_caption_1 = '$image1_caption_1', 
					image1_caption_2 = '$image1_caption_2', 
					image1_cta = '" . mysql_real_escape_string(trim(stripslashes($image1_cta))) . "',
					image2_id = " . MakeNullsZeros($image2_id) . ", 
					image2_caption_1 = '$image2_caption_1', 
					image2_caption_2 = '$image2_caption_2',
					image2_cta = '" . mysql_real_escape_string(trim(stripslashes($image2_cta))) . "', 
					image3_id = " . MakeNullsZeros($image3_id) . ", 
					image3_caption_1 = '$image3_caption_1', 
					image3_caption_2 = '$image3_caption_2', 
					image3_cta = '" . mysql_real_escape_string(trim(stripslashes($image3_cta))) . "',
					image4_id = " . MakeNullsZeros($image4_id) . ", 
					image4_caption_1 = '$image4_caption_1', 
					image4_caption_2 = '$image4_caption_2',
					image4_cta = '" . mysql_real_escape_string(trim(stripslashes($image4_cta))) . "', 
					image5_id = " . MakeNullsZeros($image5_id) . ", 
					image5_caption_1 = '$image5_caption_1', 
					image5_caption_2 = '$image5_caption_2',
					image5_cta = '" . mysql_real_escape_string(trim(stripslashes($image5_cta))) . "', 
					image6_id = " . MakeNullsZeros($image6_id) . ", 
					image6_caption_1 = '$image6_caption_1', 
					image6_caption_2 = '$image6_caption_2',
					image6_cta = '" . mysql_real_escape_string(trim(stripslashes($image6_cta))) . "', 
					image7_id = " . MakeNullsZeros($image7_id) . ", 
					image7_caption_1 = '$image7_caption_1', 
					image7_caption_2 = '$image7_caption_2',
					image7_cta = '" . mysql_real_escape_string(trim(stripslashes($image7_cta))) . "', 
					image8_id = " . MakeNullsZeros($image8_id) . ", 
					image8_caption_1 = '$image8_caption_1', 
					image8_caption_2 = '$image8_caption_2',
					image8_cta = '" . mysql_real_escape_string(trim(stripslashes($image8_cta))) . "', 
					top_banner = '$top_banner', 
					top_banner_title = '$top_banner_title', 
					top_banner_caption = '$top_banner_caption', 
					block_1 = '$block_1', 
					block_1_title = '$block_1_title', 
					block_1_text = '$block_1_text',
					block_1_alt = '$block_1_alt', 
					block_1_link_title = '$block_1_link_title', 
					block_1_bg = '$block_1_bg', 
					block_2 = '$block_2', 
					block_2_title = '$block_2_title', 
					block_2_text = '$block_2_text',
					block_2_alt = '$block_2_alt', 
					block_2_link_title = '$block_2_link_title', 
					block_2_bg = '$block_2_bg', 
					block_3 = '$block_3', 
					block_3_title = '$block_3_title', 
					block_3_text = '$block_3_text', 
					block_3_alt = '$block_3_alt', 
					block_3_link_title = '$block_3_link_title', 
					block_3_bg = '$block_3_bg',
					block_4 = '$block_4', 
					block_4_title = '$block_4_title', 
					block_4_text = '$block_4_text', 
					block_4_alt = '$block_4_alt', 
					block_4_link_title = '$block_4_link_title', 
					block_4_bg = '$block_4_bg',
					bottom_banner = '$bottom_banner',
					bottom_alt = '$bottom_alt', 
					bottom_title = '$bottom_title'";

		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	
	}	

}

function upc_available($product_id, $upc) {

	$dbh = db_connect();
	
	if($dbh && $upc){
	
		$sql = "SELECT * FROM tbl_product WHERE upc='$upc' AND archived_ind=0 AND product_id<>" . $product_id;
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		
		if(mysql_num_rows($result) > 0){
			return false;
		} else {
			return true;
		}
	}
}

function product_lookup($product_id, $upc=""){

	$dbh = db_connect();

	if($dbh && (($product_id>0 && int_ok($product_id)) || $upc!="")){
		$sql = "SELECT * FROM tbl_product WHERE 1";
		
		if($product_id>0){
			$sql.=" AND product_id=" . $product_id;
		} else {
			$sql.=" AND upc='" . $upc . "'";
		}
		//echo($sql."<BR>");
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	
		if($result)
			return $result;
	}
}	

function product_search($archived_incl=0, $user_id=0, $inventory_user_id=0, $exclude=0, $manufacturer_inactive_incl=0){

	$dbh = db_connect();
	
	if($dbh){
		$sql = "SELECT * 
				FROM tbl_product tp
				LEFT JOIN tbl_styles ts ON
				tp.style_id = ts.style_id 
				LEFT JOIN tbl_user tu ON
				tp.manufacturer_id = tu.id
				WHERE 1";
	
		if($user_id)
			$sql.= " AND tp.user_id=" . $user_id;
		
		if($exclude && $inventory_user_id){
			$sql.= " AND tp.product_id NOT IN (SELECT product_id FROM tbl_retailer_inventory WHERE user_id=$inventory_user_id)";
		} else if ($inventory_user_id){
			$sql.= " AND tp.product_id IN (SELECT product_id FROM tbl_retailer_inventory WHERE user_id=$inventory_user_id)";
		}
		
		if($archived_incl<1)
			$sql.= " AND archived_ind=0";
		
		//if($manufacturer_inactive_incl<1)
		//	$sql.= " AND tu.status IN ('active', 'pending', '')";
		
		//die($sql);
		
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		return $result;
	}
}

function product_search_adv($source_id, $category_id, $style_id, $state, $keyword, $sort='', $user='', $manufacturer_inactive_incl=0){

	$dbh = db_connect();
	
	if($dbh){
	
		if($keyword)
			$match_sql = ', MATCH (product) AGAINST (\'' . $keyword . '\') AS 1_relevance,
							MATCH (tp.keywords) AGAINST (\'' . $keyword . '\') AS 2_relevance,
							MATCH (long_description) AGAINST (\'' . $keyword . '\') AS 3_relevance';
	
		if($user>0){
	
			$sql = "SELECT *, (SELECT count(*) FROM tbl_source_products tsp WHERE user_id=$user AND tsp.product_id = tp.product_id  ) as 'carry' $match_sql
					FROM tbl_product tp
					LEFT JOIN tbl_styles ts ON
					tp.style_id = ts.style_id
					LEFT JOIN tbl_user tu ON
					tp.manufacturer_id = tu.id 
					WHERE 1 AND tp.archived_ind=0 AND tp.status<>'inactive'";

		} else {

			$sql = "SELECT * $match_sql
					FROM tbl_product tp
					LEFT JOIN tbl_styles ts ON
					tp.style_id = ts.style_id
					LEFT JOIN tbl_user tu ON
					tp.manufacturer_id = tu.id 
					WHERE 1 AND tp.archived_ind=0 AND tp.status<>'inactive'";

		
		}

		if($source_id>0 && int_ok($source_id))
			$sql.= " AND tp.product_id IN (SELECT product_id FROM tbl_source_products WHERE user_id IN (SELECT user_id FROM tbl_user_source_rel WHERE source_id = $source_id ))";

		if($category_id>0 && int_ok($category_id))
			$sql.= " AND tp.product_id IN (SELECT product_id FROM tbl_product_category_rel WHERE category_id = $category_id)";

		if($style_id>0 && int_ok($style_id))
			$sql.= " AND tp.style_id = $style_id";

		if($state)
			$sql.= " AND tp.product_id IN (SELECT product_id FROM tbl_source_products WHERE user_id IN (SELECT id FROM tbl_user WHERE state = '$state' AND user_group_id IN (3,4) ))";

		if($keyword){
			$keyword_array = explode(" ", $keyword);
			
			//$sql.= " AND MATCH(product, tp.keywords, long_description) AGAINST ('" . $keyword . "')";
			
			foreach ($keyword_array as &$keyword) {
				$sql.= " AND (product LIKE '%$keyword%' OR item_nbr LIKE '%$keyword%' OR long_description LIKE '%$keyword%' OR materials LIKE '%$keyword%' OR tp.keywords LIKE '%$keyword%' ) ";
			}

		}

		if($manufacturer_inactive_incl<1)
			$sql.= " AND (tu.status IN ('active', 'pending', '') OR tu.status IS NULL)";

		if(($sort=="" || $sort=="Sort by") && $match_sql){
			$sql.= " ORDER BY 1_relevance + 2_relevance + 3_relevance DESC";
		} else if($sort=="A-Z"){
			$sql.= " ORDER BY product ASC";
		} else if ($sort=="Style"){
			$sql.= " ORDER BY style ASC";
		} else if ($sort=="Price"){
			$sql.= " ORDER BY msrp ASC";
		}

		//die($sql);

		//echo($sql."<BR>");
		//$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		return $sql;

	}
}

function product_category_first_lookup($product_id){

	$dbh = db_connect();

	if($dbh && $product_id>0 && int_ok($product_id)){
		$sql = "SELECT category 
				FROM tbl_product_category_rel tpcr
				LEFT JOIN tbl_categories tc ON tpcr.category_id = tc.category_id
				WHERE product_id=" . $product_id . "
				ORDER BY prod_cat_id ASC";
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		if($result){
			$row = @mysql_fetch_array($result);
			return $row[0];
		}
	}

}

function product_add($product_id, $user_id, $product, $status, $upc, $item_nbr, $manufacturer_id, $keywords,  $short_description, $long_description, $style_id, $genre_id, $msrp, $width, $length, $height, $weight, $materials, $meta_title, $meta_keywords, $meta_description, $discontinued_ind){

	$dbh = db_connect();
	
	if($dbh && $product){
	
		$upc = ($upc=="") ? "NULL" : "'" . mysql_real_escape_string(trim($upc)) . "'";
	
		if($product_id<1){
			$sql = "INSERT INTO tbl_product (user_id, product, status, upc, item_nbr, manufacturer_id, keywords, short_description, long_description, meta_title, meta_keywords, meta_description, style_id, genre_id, msrp, width, length, height, weight, materials, discontinued_ind, created_date, modified_date)
						VALUES ($user_id, '" . mysql_real_escape_string(trim(stripslashes($product))) . "','" .
										 mysql_real_escape_string(trim($status)) . "'," .
										 $upc . ",'" .
										 mysql_real_escape_string(trim(stripslashes($item_nbr))) . "'," . 
										 MakeNullsZeros($manufacturer_id) . ", '" . 
										 mysql_real_escape_string(trim(stripslashes($keywords))) . "','" . 
										 mysql_real_escape_string(trim(stripslashes($short_description))) . "','" .
										 mysql_real_escape_string(trim(stripslashes($long_description))) . "','" . 
										 mysql_real_escape_string(trim(stripslashes($meta_title))) . "','" . 
										 mysql_real_escape_string(trim(stripslashes($meta_keywords))) . "','" .
										 mysql_real_escape_string(trim(stripslashes($meta_description))) . "'," .
										 MakeNullsZeros($style_id) . ", " . MakeNullsZeros($genre_id) . "," . MakeNullsZeros($msrp) . "," .  MakeNullsZeros($width) . " ," .  MakeNullsZeros($length) . "," . MakeNullsZeros($height) . "," .  MakeNullsZeros($weight) . ", '" . mysql_real_escape_string(trim(stripslashes($materials))) . "'," . MakeNullsZeros($discontinued_ind) . ", now(), now())";
			
		} else {
		
			$sql = "UPDATE tbl_product
						SET product='" . mysql_real_escape_string(trim(stripslashes($product))) . "',
							status='$status',
							upc=$upc,
							item_nbr='" . mysql_real_escape_string(trim(stripslashes($item_nbr))) . "',
							manufacturer_id=" . MakeNullsZeros($manufacturer_id) . ",
							keywords='" . mysql_real_escape_string(trim($keywords)) . "', 
							short_description='" . mysql_real_escape_string(trim(stripslashes($short_description))) . "',
							long_description='" . mysql_real_escape_string(trim(stripslashes($long_description))) . "',
							style_id=" . MakeNullsZeros($style_id) . ",
							genre_id=" . MakeNullsZeros($genre_id) . ",
							msrp=" . MakeNullsZeros($msrp) . ", 
							width=" . MakeNullsZeros($width) . ",
							length=" . MakeNullsZeros($length) . ",
							height=" . MakeNullsZeros($height) . ",
							weight=" . MakeNullsZeros($weight) . ",
							materials='" . mysql_real_escape_string(trim(stripslashes($materials))) . "',
							meta_title='" . mysql_real_escape_string(trim(stripslashes($meta_title))) . "',
							meta_keywords='" . mysql_real_escape_string(trim(stripslashes($meta_keywords))) . "',
							meta_description='" . mysql_real_escape_string(trim($meta_description)) . "',
							discontinued_ind=" . MakeNullsZeros($discontinued_ind) . ",
							modified_date=now()
						WHERE product_id=" . $product_id;
		}
		
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );

		if ($product_id<1)
			$product_id = mysql_insert_id($dbh);

		return $product_id;
	}
}

function product_assume_ownership($product_id, $user_id){

	$dbh = db_connect();

	if($product_id>0 && int_ok($product_id) && $user_id>0 && int_ok($user_id)){
	
		$product_result = product_lookup($product_id);
		$user_result = user_search($user_id);
		
		if($product_result && $user_result){
			$product_row = @mysql_fetch_array($product_result);
			$user_row = @mysql_fetch_array($user_result);
		
			if($user_row['group_name'] == "admin" && $user_id != $product_row['user_id']){
				$sql = "UPDATE tbl_product SET user_id=" . $user_id . ", modified_date=now() WHERE product_id=" . $product_id;
				$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
			}
		}
	}
}

function product_image_lookup($image_id){

	$dbh = db_connect();
	
	if($dbh && $image_id> 0 && int_ok($image_id)){
		$sql = "SELECT * FROM tbl_product_image WHERE image_id=" . $image_id;
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		if($result){
			return @mysql_fetch_array($result);
		}
	}
}

function product_first_image($product_id){

	$result = product_image_search($product_id, 0, 1, 0, 2);

	if($result){
		$row = mysql_fetch_array($result);
		return $row['image'];
	}

}

function product_image_search($product_id, $archived_incl=0, $top_level_ind=1, $user_id=0, $position=0){

	$dbh = db_connect();
	
	if($dbh && $product_id> 0 && int_ok($product_id)){
		$sql = "SELECT * FROM tbl_product_image WHERE product_id=" . $product_id;
		
		if($archived_incl<1)
			$sql.= " AND archived_ind=0";
		
		if($top_level_ind)
			$sql.= " AND top_level_ind=" . $top_level_ind;
		
		if($user_id)
			$sql.= " AND user_id=" . $user_id;
		
		if($position>0)
			$sql .= " AND position=" . $position;
		
		$sql.= " ORDER BY position ASC";
		
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		return $result;
	}
}

function product_image_save($image_id, $product_id, $user_id, $image, $image_alt, $position, $top_level_ind){

	$dbh = db_connect();
	
	if($dbh && $product_id>0 && int_ok($product_id) && int_ok($user_id) && $image){
	
		if($image_id > 0){
			$sql = "UPDATE tbl_product_image
						SET image='$image',
							image_alt='" . mysql_real_escape_string(trim(stripslashes($image_alt))) . "', 
							modified_date = now()
						WHERE image_id=" . $image_id;								
		} else {
		
			product_image_archive($product_id, $user_id, $position);
			
			$sql = "INSERT INTO tbl_product_image (product_id, user_id, image, image_alt, top_level_ind, position, created_date, modified_date)
											VALUES ($product_id, $user_id, '$image', '" . mysql_real_escape_string(trim(stripslashes($image_alt))) . "', $top_level_ind, $position, now(), now())";
		}
		
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		
		product_image_check($product_id, $user_id);			
	}	
}

function product_image_check($product_id, $user_id){

	if($product_id>0 && int_ok($product_id) && $user_id>0 && int_ok($user_id)){
		
		$user_result = user_search($user_id);
		
		if($user_result){
			$user_row = @mysql_fetch_array($user_result);
			
			if($user_row['group_name'] != "retailer"){
				product_image_move_down($product_id, $user_id);
			}	
		}
	}
}

function product_image_move_down($product_id, $user_id){

	$dbh = db_connect();
	
	$sql = "UPDATE tbl_product_image SET top_level_ind=0 WHERE product_id=$product_id AND user_id IN (SELECT id FROM tbl_user WHERE user_group_id=3)";
	$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );

}

function product_image_archive($product_id, $user_id, $position){

	$dbh = db_connect();
	
	$sql = "UPDATE tbl_product_image SET archived_ind=1 WHERE product_id=$product_id AND user_id=$user_id AND position=$position";
	$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
}

function product_image_delete($image_id, $archive=1){

	$dbh = db_connect();
	
	if($dbh && $image_id> 0 && int_ok($image_id)){
	
		$row = product_image_lookup($image_id);
		
		if($archive==0){
			unlink('../product/'. $row['image']);
	
			$sql = "DELETE FROM tbl_product_image
									WHERE image_id=" . $image_id;
		} else {
			$sql = "UPDATE tbl_product_image SET archived_ind=1 WHERE image_id=" . $image_id;
		}

		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	}	
} 

function product_retailer_inventory_lookup($product_id, $user_id){

	$dbh = db_connect();
	
	if($dbh && $product_id>0 && int_ok($product_id) && int_ok($user_id)){
		$sql = "SELECT * FROM tbl_retailer_inventory WHERE product_id=$product_id AND user_id=$user_id";
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		return $result;	
	}

}

function product_category_rel($product_id, $categories, $sub_categories){

	$dbh = db_connect();

	if($dbh && $product_id>0 && int_ok($product_id)){
		product_category_rel_delete_all($product_id);
		if(is_array($categories)){
			foreach($categories as $key => $cat){
				$subcat = (array_key_exists($key,$sub_categories)) ? $sub_categories[$key] : 0;
				$subcat = (int_ok($subcat)) ? $subcat : 0;
				if(int_ok($cat)){
					$sql = "INSERT IGNORE INTO tbl_product_category_rel (product_id, category_id, sub_category_id) 
											VALUES ($product_id, $cat, " . MakeNullsZeros($subcat) . ")";
					$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
				}
			}	
		}
	}
}

function product_category_rel_delete_all($product_id){

	$dbh = db_connect();

	if($dbh && $product_id>0 && int_ok($product_id)){
		$sql = "DELETE FROM tbl_product_category_rel WHERE product_id=" . $product_id;
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	}
}

function product_category_lookup($product_id){

	$dbh = db_connect();
	
	if($dbh){
		$sql = "SELECT tpcr.product_id, tc.category_id, tpcr.sub_category_id 
				FROM tbl_product_category_rel tpcr 
				INNER JOIN tbl_categories tc ON tpcr.category_id = tc.category_id
				WHERE tpcr.product_id = " . $product_id . "
				ORDER BY prod_cat_id ASC";
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		return $result;					
	}	

}

function product_inspiration_lookup($product_id){

	$dbh = db_connect();
	
	if($dbh){
		$sql = "SELECT tpir.product_id, tpir.inspiration_id 
				FROM tbl_product_inspiration_rel tpir 
				WHERE tpir.product_id = " . $product_id;
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		return $result;					
	}

}

function product_inspiration_rel($product_id, $inspirations){

	$dbh = db_connect();

	if($dbh && $product_id>0 && int_ok($product_id)){
		product_inspiration_rel_delete_all($product_id);
		if(is_array($inspirations)){
			foreach($inspirations as $inspiration_id){
				if(int_ok($inspiration_id)){
					$sql = "INSERT IGNORE INTO tbl_product_inspiration_rel (product_id, inspiration_id) 
											VALUES ($product_id, $inspiration_id)";
					$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
				}
			}		
		}
	}
}

function product_inspiration_rel_delete_all($product_id){

	$dbh = db_connect();

	if($dbh && $product_id>0 && int_ok($product_id)){
		$sql = "DELETE FROM tbl_product_inspiration_rel WHERE product_id=" . $product_id;
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	}
}

function product_color_lookup($product_id){

	$dbh = db_connect();
	
	if($dbh){
		$sql = "SELECT tpcr.product_id, tpcr.color_id 
				FROM tbl_product_color_rel tpcr 
				WHERE tpcr.product_id = " . $product_id;
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		return $result;					
	}

}

function product_color_rel($product_id, $colors){

	$dbh = db_connect();

	if($dbh && $product_id>0 && int_ok($product_id)){
		product_color_rel_delete_all($product_id);
		if(is_array($colors)){
			foreach($colors as $color_id){
				if(int_ok($color_id)){
					$sql = "INSERT IGNORE INTO tbl_product_color_rel (product_id, color_id) 
											VALUES ($product_id, $color_id)";
					$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
				}
			}		
		}
	}
}

function product_color_rel_delete_all($product_id){

	$dbh = db_connect();

	if($dbh && $product_id>0 && int_ok($product_id)){
		$sql = "DELETE FROM tbl_product_color_rel WHERE product_id=" . $product_id;
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	}
}

function product_inventory_add($product_id, $user_id, $long_description='', $short_description=''){
	
	$dbh = db_connect();

	$long_description = mysql_real_escape_string($long_description);
	$short_description = mysql_real_escape_string($short_description);

	if($dbh && $product_id>0 && int_ok($product_id)){
		$sql = "INSERT INTO tbl_retailer_inventory (product_id, user_id, long_description, short_description, created_date, modified_date) 
				VALUES ($product_id,$user_id,'" . $long_description . "','" . $short_description . "',now(), now())
				ON DUPLICATE KEY UPDATE long_description='$long_description', short_description='$short_description',modified_date=now();";
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );		
	}
}

function product_inventory_delete($product_id, $user_id){

	$dbh = db_connect();

	if($dbh && $product_id>0 && int_ok($product_id) && $user_id>0 && int_ok($user_id)){
		$sql = "DELETE FROM tbl_retailer_inventory WHERE product_id=$product_id AND user_id=$user_id";
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );		
	}
}

function product_delete($product_id){

	$dbh = db_connect();

	if($dbh && $product_id>0 && int_ok($product_id)){
		$sql = "UPDATE tbl_product SET modified_date=now(), archived_ind=1 WHERE product_id=" . $product_id;
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	}
}

function category_search(){

	$dbh = db_connect();

	if($dbh){
		$sql = "SELECT * FROM tbl_categories";
		$sql.= " ORDER BY category ASC";
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		
		return $result;
	}
}

function category_lookup($category_id){

	$dbh = db_connect();

	if($dbh && $category_id && int_ok($category_id)){
		$sql = "SELECT * FROM tbl_categories WHERE category_id =" . $category_id;
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		return $result;
	}
}

function category_name($category_id){

	$result = category_lookup($category_id);
	if($result){
		$row = mysql_fetch_array($result);
		return $row['category'];
	}
	
	
}

function category_add($category_id, $category, $description){

	$dbh = db_connect();

	if($dbh && $category){
	
		if($category_id && int_ok($category_id)){
			$sql = "UPDATE tbl_categories SET category='$category', description='$description' WHERE category_id=" . $category_id;
		} else {
			$sql = "INSERT INTO tbl_categories (category, description)
					VALUES ('$category', '$description')";
		}
	
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		
		if($category_id<1)
			$category_id = mysql_insert_id($dbh);
		
		return $category_id;
	}
}

function category_delete($category_id){

	$dbh = db_connect();

	if($dbh && $category_id>0 && int_ok($category_id)){
		$sql = "DELETE FROM tbl_categories WHERE category_id=". $category_id;
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	}
}

function sub_category_add($sub_category_id, $category_id, $sub_category){

	$dbh = db_connect();

	if($dbh && trim($sub_category) != ""){
	
		if($sub_category_id && int_ok($sub_category_id)){
			$sql = "UPDATE tbl_sub_categories SET sub_category='" . trim($sub_category) . "' WHERE sub_category_id=" . $sub_category_id;
		} else {
			$sql = "INSERT INTO tbl_sub_categories (sub_category, category_id)
					VALUES ('$sub_category', '$category_id')";
		}
	
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		
		if($category_id<1)
			$sub_category_id = mysql_insert_id($dbh);
		
		return $sub_category_id;
	}

}

function sub_category_lookup($category_id, $sub_category_id=0){

	$dbh = db_connect();

	if($dbh && $category_id && int_ok($category_id)){
		$sql = "SELECT sub_category_id, sub_category, category_id FROM tbl_sub_categories WHERE category_id =" . $category_id;
		
		if($sub_category_id > 0 && int_ok($sub_category_id))
			$sql.= " AND sub_category_id=" . $sub_category_id;
		
		$sql.= " ORDER BY sub_category ASC";
		
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		return $result;
	}
}

function sub_category_name($category_id, $sub_category_id){
	
	$result = sub_category_lookup($category_id, $sub_category_id);
	if($result){
		$row = mysql_fetch_array($result);
		return $row['sub_category'];
	}
}

function sub_category_delete($sub_category_id){

	$dbh = db_connect();

	if($dbh && $sub_category_id > 0 && int_ok($sub_category_id)){
		$sql = "DELETE FROM tbl_sub_categories WHERE sub_category_id =" . $sub_category_id;
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		return $result;
	}

}

function color_search(){

	$dbh = db_connect();

	$sql = "SELECT * FROM tbl_colors";
	$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	
	return $result;
}

function color_add($color_id, $color){

	$dbh = db_connect();

	if($dbh && $color_id > 0 && int_ok($color_id) ){
		$sql = "UPDATE tbl_colors SET color='" . trim($color) . "' WHERE color_id=" . $color_id;	
	} else if ($dbh && $color){
		$sql = "INSERT INTO tbl_colors (color) VALUES ('" . trim($color) . "')";
	}

	if($sql)
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	
	if($color_id<1){
		$color_id = mysql_insert_id($dbh);
	}

	return $color_id;
}

function color_delete($color_id){
	
	$dbh = db_connect();
	
	if($dbh && $color_id > 0 && int_ok($color_id) ){
		$sql = "DELETE FROM tbl_colors WHERE color_id=" . $color_id;
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	}

}

function color_lookup($color_id){

	$dbh = db_connect();

	if($dbh && $color_id>0 && int_ok($color_id)){
		$sql = "SELECT * FROM tbl_colors WHERE color_id=" . $color_id;
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	
		if($result)
			return $result;
	}
	
}

function style_search(){

	$dbh = db_connect();

	$sql = "SELECT * FROM tbl_styles ORDER BY style ASC";
	$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	
	return $result;
}

function style_add($style_id, $style){

	$dbh = db_connect();

	if($dbh && $style_id > 0 && int_ok($style_id) ){
		$sql = "UPDATE tbl_styles SET style='" . trim($style) . "' WHERE style_id=" . $style_id;	
	} else if ($dbh && $style){
		$sql = "INSERT INTO tbl_styles (style) VALUES ('" . trim($style) . "')";
	}

	if($sql)
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	
	if($style_id<1){
		$style_id = mysql_insert_id($dbh);
	}

	return $style_id;
}

function style_delete($style_id){
	
	$dbh = db_connect();
	
	if($dbh && $style_id > 0 && int_ok($style_id) ){
		$sql = "DELETE FROM tbl_styles WHERE style_id=" . $style_id;
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	}

}

function style_lookup($style_id){

	$dbh = db_connect();

	if($dbh && $style_id>0 && int_ok($style_id)){
		$sql = "SELECT * FROM tbl_styles WHERE style_id=" . $style_id;
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	
		if($result)
			return $result;
	}
}

function style_name($style_id){

	$result = style_lookup($style_id);
	$row = mysql_fetch_array($result);
	return $row['style'];
}

function sources_search($state="", $require_active=0, $include_manufacturers=1){

	$dbh = db_connect();

	if($dbh){
		$sql = "SELECT * 
				FROM tbl_source
				WHERE 1";
		
		$source_types = ($include_manufacturers) ? "3,4" : "3";
		
		if($state!=""){
			$sql.= " AND source_id IN (SELECT source_id FROM tbl_user_source_rel WHERE user_id IN (SELECT id FROM tbl_user WHERE state='" . $state . "' AND user_group_id IN (" . $source_types . ") AND (status='active' OR status='pending') AND archived=0))";
		}
			
		if ($require_active) {
			$sql.= " AND source_id IN (SELECT source_id FROM tbl_user_source_rel WHERE user_id IN (SELECT id FROM tbl_user WHERE user_group_id IN (" . $source_types . ") AND (status='active' OR status='pending') AND archived=0))";
		}
				
		$sql.= " ORDER BY source ASC";
		
		if($_SERVER['REMOTE_ADDR'] == "68.0.159.224"){
			//echo($sql);
		}
		
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	}
	return $result;
}

function sources_add($source_id, $source){

	$dbh = db_connect();

	if($dbh && $source_id > 0 && int_ok($source_id) ){
		$sql = "UPDATE tbl_source SET source='" . trim($source) . "' WHERE source_id=" . $source_id;	
	} else if ($dbh && $source){
		$sql = "INSERT INTO tbl_source (source) VALUES ('" . trim($source) . "')";
	}

	if($sql)
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	
	if($source_id<1){
		$source_id = mysql_insert_id($dbh);
	}

	return $source_id;
}

function sources_image($source_id, $file){

	$dbh = db_connect();

	if($dbh && $source_id > 0 && int_ok($source_id) ){
		$sql = "UPDATE tbl_source SET image='" . $file . "' WHERE source_id=" . $source_id;	
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	}	
}

function sub_sources_name($source_id, $sub_source_id){

	$dbh = db_connect();

	if($dbh && $source_id > 0 && int_ok($source_id) && $sub_source_id > 0 && int_ok($sub_source_id) ){
		$result = sub_source_lookup($source_id, $sub_source_id);
		if($result){
			$row = mysql_fetch_array($result);
			return $row['sub_source'];
		}
	}

}

function sub_sources_image($sub_source_id, $file){

	$dbh = db_connect();

	if($dbh && $sub_source_id > 0 && int_ok($sub_source_id) ){
		$sql = "UPDATE tbl_sub_source SET image='" . $file . "' WHERE sub_source_id=" . $sub_source_id;	
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	}	
}

function sources_delete($source_id){
	
	$dbh = db_connect();
	
	if($dbh && $source_id > 0 && int_ok($source_id) ){
		$sql = "DELETE FROM tbl_source WHERE source_id=" . $source_id;
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	}

}

function sources_lookup($source_id){

	$dbh = db_connect();

	if($dbh && $source_id>0 && int_ok($source_id)){
		$sql = "SELECT * FROM tbl_source WHERE source_id=" . $source_id;
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	
		if($result)
			return $result;
	}
}

function sub_source_lookup($source_id, $sub_source_id=0){

	$dbh = db_connect();

	if($dbh && $source_id && int_ok($source_id)){
		$sql = "SELECT sub_source_id, sub_source, source_id, image FROM tbl_sub_source WHERE source_id =" . $source_id;
		
		if($sub_source_id > 0 && int_ok($sub_source_id))
			$sql.= " AND sub_source_id=" . $sub_source_id;
		
		$sql.= " ORDER BY sub_source ASC";
		
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		return $result;
	}
}

function sub_source_add($sub_source_id, $source_id, $sub_source){

	$dbh = db_connect();

	if($dbh && trim($sub_source) != ""){
	
		if($sub_source_id && int_ok($sub_source_id)){
			$sql = "UPDATE tbl_sub_source SET sub_source='" . trim($sub_source) . "' WHERE sub_source_id=" . $sub_source_id;
		} else {
			$sql = "INSERT INTO tbl_sub_source (sub_source, source_id)
					VALUES ('$sub_source', '$source_id')";
		}
	
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		
		if($sub_source_id<1)
			$sub_source_id = mysql_insert_id($dbh);
		
		return $sub_source_id;
	}
}

function sub_source_delete($sub_source_id){

	$dbh = db_connect();

	if($dbh && $sub_source_id>0 && int_ok($sub_source_id)){
		
		$sql = "DELETE FROM tbl_sub_source WHERE where sub_source_id=" . $sub_source_id;		
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	}
}

function sources_name($source_id){

	$result = sources_lookup($source_id);
	
	if($result){
		$row = mysql_fetch_array($result);
		return $row['source'];
	}	

}

function source_product_relation($source_id, $product_id, $relationship){

	$dbh = db_connect();

	if($dbh && $source_id>0 && int_ok($source_id) && $product_id>0 && int_ok($product_id)){	

		if($relationship == "remove"){
			$sql = "DELETE FROM tbl_source_products WHERE user_id=$source_id AND product_id=$product_id";
					
		} else {
	
			if($relationship=="carried"){
				$relationship = 1;
			} else if ($relationship=="similar"){
				$relationship = 0;
			} else if($relationship=="source") {
				$relationship = 2;
			}
	
			$sql = "INSERT INTO tbl_source_products (user_id, product_id, carry_or_simular) VALUES ($source_id, $product_id, $relationship)
					ON DUPLICATE KEY UPDATE carry_or_simular=" . $relationship;
				
		}
				
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	}

}

function source_products($source_id, $type, $browsering_source_id="0", $include_inactive=0, $result_type='result', $sort=""){

	$dbh = db_connect();

	if($dbh && $source_id>0 && int_ok($source_id)){
		
		$retailer_result = user_search($source_id, '');
		
		$row = @mysql_fetch_array($retailer_result);
		
		// 	RETAILER
		if($row['user_group_id'] != "4"){
		
			if($browsering_source_id<1){
				$sql = "SELECT *";
			} else {
				$sql = "SELECT *, (SELECT COUNT(*) FROM tbl_source_products tsp1 WHERE tsp.product_id = tsp1.product_id AND user_id=" . $browsering_source_id . ") as `carry`";
			}
		
			$sql.= " FROM tbl_source_products tsp
					 INNER JOIN tbl_product tp ON tsp.product_id= tp.product_id
					 WHERE 1 AND tsp.user_id=" . $source_id;
		
			if(!$include_inactive){
				$sql.= " AND tp.status='active'";
			}

			if($type>0)
				$sql.= " AND tsp.carry_or_simular=" . $type;
		
		// MANUFACTURER
		} else if($row['user_group_id'] == "4") {
		
			
			if($browsering_source_id<1){
				$sql = "SELECT *";
			} else {
				$sql = "SELECT *, (SELECT COUNT(*) FROM tbl_source_products tsp1 WHERE tp.product_id = tsp1.product_id AND user_id=" . $browsering_source_id . ") as `carry`";
			}
			
		    $sql.= " FROM tbl_product tp
					WHERE manufacturer_id=" . $source_id . " AND tp.archived_ind=0";
		
			if(!$include_inactive){
				$sql.= " AND tp.status='active'";
			}			
		}

		switch($sort){
		
			case 'A-Z':
				$sql.= " ORDER BY product ASC";
				break;
			
			case 'stock':
				$sql.= " ORDER BY carry_or_simular DESC";
				break;
			
			case 'line':
				$sql.= " ORDER BY carry_or_simular ASC";
				break;
				
			case 'style':
				$sql.= " ORDER BY carry_or_simular ASC";
				break;	
				
			default:
				$sql.= " ORDER BY tp.product_id DESC";	
				break;
				
		}
	
		if($result_type=='sql'){
			return $sql;
		} else {	
			if($sql){
				$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
				return $result;
			}
		}
	}

}

function source_product_count($source_id){

	$dbh = db_connect();

	if($dbh && $source_id>0 && int_ok($source_id)){
	
		$retailer_result = user_search($source_id, '');

		if($retailer_result){
		
			$row = @mysql_fetch_array($retailer_result);
		
			// 	RETAILER
			if($row['user_group_id'] == "3"){
	
				$sql = "SELECT * 
						FROM tbl_source_products tsp
						INNER JOIN tbl_product tp ON tsp.product_id = tp.product_id
						WHERE tsp.user_id=" . $source_id . " AND tp.archived_ind=0 AND tp.status='active'";

			// MANUFACTURER
			} else if($row['user_group_id'] == "4") {
	
				$sql = "SELECT * 
						FROM tbl_product tp
						WHERE manufacturer_id=" . $source_id . " AND tp.archived_ind=0 AND tp.status='active'";
			}
		
			//die($sql."<BR>");
			if($sql){
				$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
				return mysql_num_rows($result);
			} else {
				return 0;
			}
		}
	}
}

function source_search_by_product($product_id, $state="", $relation=1){

	$dbh = db_connect();

	if($dbh && $product_id>0 && int_ok($product_id)){
	
		if(is_array($relation))
			$relation = implode(", ", $relation);
	
		$sql = "SELECT *, (SELECT carry_or_simular FROM tbl_source_products WHERE user_id=tu.id AND product_id=$product_id) as 'carry'
				FROM tbl_user tu
				WHERE user_group_id IN (3,4)
				AND archived=0 AND status IN ('active', 'pending')
				AND id IN (SELECT user_id FROM tbl_source_products WHERE product_id=" . $product_id . " AND carry_or_simular IN (" . $relation . "))";
	
		if($state)
			$sql.= " AND state='" . $state . "'";
		
		//echo($sql."<BR>");
		
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		return $result;
	
	}
}

function inspiration_name($inspiration_id){

	$result = inspiration_search($inspiration_id, 0);
	
	if($result){
		$row = mysql_fetch_array($result);
		return $row['inspiration'];
	}	
}

function sub_inspiration_name($sub_inspiration_id){

	$dbh = db_connect();	

	if($sub_inspiration_id>0 && int_ok($sub_inspiration_id)){	

		$sql = "SELECT * 
				FROM tbl_sub_inspirations
				WHERE sub_inspiration_id=" . $sub_inspiration_id;
		
		//die($sql);
				
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );		
		$row = mysql_fetch_array($result);
		
		return $row['sub_inspiration'];
	}
}

function inspiration_title_alt_tags($inspiration_id, &$title, &$alt){

	$dbh = db_connect();
	
	if($dbh && $inspiration_id > 0 && int_ok($inspiration_id)){
	
		$page_result = page_lookup($inspiration_id);
	
		if($page_result){
			$row = @mysql_fetch_array($page_result);
			$title = $row['page_title'];
			$alt = $row['hero_alt'];		
		}
	}
}

function inspiration_search_adv($inspiration_id, $state, $keyword, $sort){

	if($keyword)
		$match_sql = ', MATCH (page_title) AGAINST (\'' . $keyword . '\') AS 1_relevance,
						MATCH (meta_keywords) AGAINST (\'' . $keyword . '\') AS 2_relevance,
						MATCH (meta_description) AGAINST (\'' . $keyword . '\') AS 3_relevance';

	$sql = "SELECT * $match_sql
			FROM tbl_inspiration_pages tip
			LEFT JOIN tbl_inspirations ti ON 
			tip.inspiration_id = ti.inspiration_id
			WHERE 1 AND tip.page_status = 'active'";

	if($inspiration_id > 0 && int_ok($inspiration_id))
		$sql.= " AND tip.inspiration_id=" . $inspiration_id;
		
	//$sql.= " AND tip.inspiration_id IN (SELECT inspiration_id FROM `tbl_inspiration_pages` WHERE page_status='active')";

	if($keyword){
		$sql.= " AND (page_title LIKE '%" . $keyword . "%' OR page_sub_title LIKE '%" . $keyword . "%' or meta_description LIKE '%" . $keyword . "%')";
	}


	if(($sort=="" || $sort=="Sort by") && $match_sql){
		$sql.= " ORDER BY 1_relevance + 2_relevance + 3_relevance DESC";
		
	} else if($sort=="A-Z"){
		$sql.= " ORDER BY page_title ASC";

	} else if($sort=="Category")
		$sql.= " ORDER BY inspiration ASC";

	//echo($sql);

	return $sql;

}

function inspiration_search($inspiration_id=0, $require_active=1, $exclude_id=0){

	$dbh = db_connect();

	$sql = "SELECT * FROM tbl_inspirations WHERE 1";
	
	if($inspiration_id > 0 && int_ok($inspiration_id))
		$sql.= " AND inspiration_id=" . $inspiration_id;
	
	if($require_active){
		$sql.= " AND inspiration_id IN (SELECT inspiration_id FROM `tbl_inspiration_pages` WHERE page_status='active')";
	}
	
	if($exclude_id){
		$sql.= " AND inspiration_id<>" . $exclude_id;
	}
	
	$sql.= " ORDER BY inspiration ASC";
	
	//die($sql);
	
	$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	
	return $result;
}

function inspiration_add($inspiration_id, $inspiration){

	$dbh = db_connect();

	if($dbh && $inspiration_id > 0 && int_ok($inspiration_id) ){
		$sql = "UPDATE tbl_inspirations SET inspiration='" . trim($inspiration) . "' WHERE inspiration_id=" . $inspiration_id;	
	} else if ($dbh && $inspiration){
		$sql = "INSERT INTO tbl_inspirations (inspiration) VALUES ('" . trim($inspiration) . "')";
	}

	if($sql)
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	
	if($inspiration_id<1){
		$inspiration_id = mysql_insert_id($dbh);
	}

	return $inspiration_id;
}

function sub_inspiration_lookup($inspiration_id=0, $sub_inspiration_id=0, $require_active=0){

	$dbh = db_connect();
	
	if($dbh){
		$sql = "SELECT sub_inspiration_id, sub_inspiration, tsi.inspiration_id, inspiration 
				FROM tbl_sub_inspirations tsi
				LEFT JOIN tbl_inspirations ti ON
				tsi.inspiration_id = ti.inspiration_id
				WHERE 1";
				
		if($inspiration_id > 0 && int_ok($inspiration_id))		
			$sql.= " AND ti.inspiration_id = " . $inspiration_id;
			
		if($sub_inspiration_id > 0 && int_ok($sub_inspiration_id))
			$sql.= " AND sub_inspiration_id=" . $sub_inspiration_id;
		
		if($require_active)
			$sql.= " AND sub_inspiration_id IN (SELECT sub_inspiration_id FROM `tbl_inspiration_pages` WHERE page_status='active')";
		
		$sql.= " ORDER BY sub_inspiration ASC";
		
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		return $result;
	}

}

function sub_inspiration_add($sub_inspiration_id, $inspiration_id, $sub_inspiration){

	$dbh = db_connect();

	if($dbh && trim($sub_inspiration) != ""){
	
		if($sub_inspiration_id && int_ok($sub_inspiration_id[0])){
			$sql = "UPDATE tbl_sub_inspirations SET sub_inspiration='" . trim($sub_inspiration) . "' WHERE sub_inspiration_id=" . $sub_inspiration_id;
		} else {
			$sql = "INSERT INTO tbl_sub_inspirations (sub_inspiration, inspiration_id)
					VALUES ('$sub_inspiration', '$inspiration_id')";
		}
	
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		
		if($inspiration_id<1)
			$sub_inspiration_id = mysql_insert_id($dbh);
		
		return $sub_inspiration_id;
	}
}

function sub_inspiration_delete($sub_inspiration_id){

	$dbh = db_connect();

	if($dbh && $sub_inspiration_id > 0 && int_ok($sub_inspiration_id)){
		$sql = "DELETE FROM tbl_sub_inspirations WHERE sub_inspiration_id =" . $sub_inspiration_id;
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		return $result;
	}
}

function inspiration_delete($inspiration_id){
	
	$dbh = db_connect();
	
	if($dbh && $inspiration_id > 0 && int_ok($inspiration_id) ){
		$sql = "DELETE FROM tbl_inspirations WHERE inspiration_id=" . $inspiration_id;
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	}

}

function inspiration_lookup($inspiration_id){

	$dbh = db_connect();

	if($dbh && $inspiration_id>0 && int_ok($inspiration_id)){
		$sql = "SELECT * FROM tbl_inspirations WHERE inspiration_id=" . $inspiration_id;
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	
		if($result)
			return $result;
	}
}

function inspiration_page_recent($inspiration_id=0, $sub_inspiration_id=0){

	$dbh = db_connect();

	//echo("Inpsiriation: " . $inspiration_id . "<BR>");

	if($dbh && ($inspiration_id>0 && int_ok($inspiration_id) || $sub_inspiration_id > 0 && int_ok($sub_inspiration_id))){
	
		$sql = "SELECT * 
				FROM tbl_inspiration_pages 
				WHERE 1 AND page_status='active'";
		
		if($inspiration_id){
			$sql.= " AND inspiration_id = " . $inspiration_id;
		}
		
		if($sub_inspiration_id>0){
			$sql.= " AND sub_inspiration_id = " . $sub_inspiration_id;
		}
				
		$sql.= " ORDER BY modified_date DESC";
		
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		
		return $result;
	}

}

function happening_search($start_date='', $end_date='', $status='', $state='', $view="client"){

	$dbh = db_connect();

	if($dbh){
		$sql = "SELECT * 
				FROM tbl_happenings 
				WHERE 1";
		
		if($view=="client"){
			$sql.= " AND recurring = ''";
		} else {
			$sql.= " AND (recurring <> '' OR parent_id=0)";
		}
				
		if($start_date){
			$sql .= " AND start_date >= '" . date("Y-m-d 00:00", strtotime($start_date)) . "'";
		}		
		
		if($end_date){
			$sql .= " AND end_date <= '" . date("Y-m-d 11:59:59", strtotime($end_date)) . "'";
		}
		
		if($status!=""){
			$sql .= " AND status='" . $status . "'";
		}
		
		if($state){
			$sql .= " AND state='$state'";
		}
		
		$sql.= " ORDER BY start_date ASC";
		
		//echo($sql."<BR>");
				
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	}
	
	return $result;
	
}

function happening_add($happening_id, $title, $city, $state, $start_date, $end_date, $recurring, $recurring_end_date, $description, $url, $status, $parent_id, &$error){

	$dbh = db_connect();

	if($dbh){
		$title = mysql_real_escape_string(trim(stripslashes($title)));
		$city = mysql_real_escape_string(trim(stripslashes($city)));
		$state = mysql_real_escape_string(trim(stripslashes($state)));
		$start_date = date("Y-m-d H:i:s", strtotime($start_date));
		$end_date = date("Y-m-d H:i:s", strtotime($end_date));
		$url = mysql_real_escape_string(trim(stripslashes($url)));
		$status = mysql_real_escape_string(trim(stripslashes($status)));
	
		$recurring_end_date = ($recurring_end_date) ? date("Y-m-d", strtotime($recurring_end_date)) : "";
	
		if($happening_id>0 && int_ok($happening_id)){
		
			$sql = "UPDATE tbl_happenings 
					SET title='" . $title . "',
						parent_id=" . $parent_id . ",
						city='" . $city . "',
						state='" . $state . "',
						start_date='" . $start_date . "',
						end_date='" . $end_date . "',
						recurring='" . $recurring . "',
						recurring_end_date='" . $recurring_end_date . "',
						description='" . $description . "',
						url='" . $url . "',
						status='" . $status . "',
						modified_date=now()
					WHERE happening_id=" . $happening_id;
		} else {

			$sql = "INSERT INTO tbl_happenings (title, parent_id, city, state, start_date, end_date, recurring, recurring_end_date, description, url, status, created_date, modified_date) 
					VALUES ('$title', $parent_id, '$city', '$state', '$start_date', '$end_date', '$recurring', '$recurring_end_date', '$description', '$url', '$status', now(), now())";		
		}


		//die($sql. "<BR><BR>" );

		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		
		if($happening_id<1)
			$happening_id = mysql_insert_id($dbh);

		// CLEAR ANY CHILDREN OCURRENCES
		$sql = "DELETE FROM tbl_happenings WHERE parent_id=" . $happening_id;
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		
		
		if($recurring && $recurring_end_date){
		
			switch($recurring){
			
				case 'daily':
					$current_date = strtotime($start_date);
					$current_end_date = strtotime($end_date);
					
					$recurring_end_date = strtotime($recurring_end_date . " 23:59:59");
					
					while($current_date <= $recurring_end_date){
					
						//echo("Child Event: " . date("Y-m-d H:i", $current_date) . " - " . date("Y-m-d H:i", $current_end_date) . "<BR>");
					
						happening_add(0, $title, $city, $state, date("Y-m-d H:i", $current_date), date("Y-m-d H:i", $current_end_date), '', '', $description, $url, $status, $happening_id, $error);
						
						// add 1 day to both dates
						$current_date = $current_date + 86400;
						$current_end_date = $current_end_date + 86400; 	
					}
					
					break;
				
				case 'weekly':
					$current_date = strtotime($start_date);
					$current_end_date = strtotime($end_date);
					
					$recurring_end_date = strtotime($recurring_end_date . " 23:59:59");
					
					while($current_date <= $recurring_end_date){
					
						//echo("Child Event: " . date("Y-m-d H:i", $current_date) . " - " . date("Y-m-d H:i", $current_end_date) . "<BR>");
					
						happening_add(0, $title, $city, $state, date("Y-m-d H:i", $current_date), date("Y-m-d H:i", $current_end_date), '', '', $description, $url, $status, $happening_id, $error);
						
						// add 7 days to both dates
						$current_date = $current_date + (86400 * 7);
						$current_end_date = $current_end_date + (86400 * 7); 	
					}
					
					break;
					
				case 'bi-weekly':
					$current_date = strtotime($start_date);
					$current_end_date = strtotime($end_date);
					
					$recurring_end_date = strtotime($recurring_end_date . " 23:59:59");
					
					while($current_date <= $recurring_end_date){
					
						//echo("Child Event: " . date("Y-m-d H:i", $current_date) . " - " . date("Y-m-d H:i", $current_end_date) . "<BR>");
					
						happening_add(0, $title, $city, $state, date("Y-m-d H:i", $current_date), date("Y-m-d H:i", $current_end_date), '', '', $description, $url, $status, $happening_id, $error);
						
						// add 14 days to both dates
						$current_date = $current_date + (86400 * 14);
						$current_end_date = $current_end_date + (86400 * 14); 	
					}
					
					break;
					
				case 'monthly':
					$current_date = strtotime($start_date);
					$current_end_date = strtotime($end_date);
					
					$recurring_end_date = strtotime($recurring_end_date . " 23:59:59");
					
					while($current_date <= $recurring_end_date){
					
						//echo("Child Event: " . date("Y-m-d H:i", $current_date) . " - " . date("Y-m-d H:i", $current_end_date) . "<BR>");
					
						happening_add(0, $title, $city, $state, date("Y-m-d H:i", $current_date), date("Y-m-d H:i", $current_end_date), '', '', $description, $url, $status, $happening_id, $error);
						
						// add 1 month to both dates
						$current_date = strtotime(date("Y-m-d", $current_date) . " +1 month");
						$current_end_date = strtotime(date("Y-m-d", $current_end_date) . " +1 month"); 	
					}
					
					break;
					
				case 'bi-monthly':
					$current_date = strtotime($start_date);
					$current_end_date = strtotime($end_date);
					
					$recurring_end_date = strtotime($recurring_end_date . " 23:59:59");
					
					while($current_date <= $recurring_end_date){
					
						//echo("Child Event: " . date("Y-m-d H:i", $current_date) . " - " . date("Y-m-d H:i", $current_end_date) . "<BR>");
					
						happening_add(0, $title, $city, $state, date("Y-m-d H:i", $current_date), date("Y-m-d H:i", $current_end_date), '', '', $description, $url, $status, $happening_id, $error);
						
						// add 1 month to both dates
						$current_date = strtotime(date("Y-m-d", $current_date) . " +2 month");
						$current_end_date = strtotime(date("Y-m-d", $current_end_date) . " +2 month"); 	
					}
					
					break;							

				case 'annually':
					$current_date = strtotime($start_date);
					$current_end_date = strtotime($end_date);
					
					$recurring_end_date = strtotime($recurring_end_date . " 23:59:59");
					
					while($current_date <= $recurring_end_date){
					
						//echo("Child Event: " . date("Y-m-d H:i", $current_date) . " - " . date("Y-m-d H:i", $current_end_date) . "<BR>");
					
						happening_add(0, $title, $city, $state, date("Y-m-d H:i", $current_date), date("Y-m-d H:i", $current_end_date), '', '', $description, $url, $status, $happening_id, $error);
						
						// add 1 month to both dates
						$current_date = strtotime(date("Y-m-d", $current_date) . " +1 year");
						$current_end_date = strtotime(date("Y-m-d", $current_end_date) . " +1 year"); 	
					}
					
					break;	
			
			}
		
		}
		
	
		return $happening_id;
	}

}

function happenings_delete($happening_id){

	$dbh = db_connect();

	if($dbh){
		$sql = "DELETE FROM tbl_happenings WHERE happening_id=" . $happening_id;
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	}	

}

function happening_lookup($happening_id){

	$dbh = db_connect();

	if($dbh && $happening_id > 0 && int_ok($happening_id)){
		$sql = "SELECT * FROM tbl_happenings WHERE happening_id=" . $happening_id;
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		
		return $result;
		
	}

}

function genre_search(){

	$dbh = db_connect();

	if($dbh){
		$sql = "SELECT * FROM tbl_genres";
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	}
	
	return $result;
}

function genre_add($genre_id, $genre){

	$dbh = db_connect();

	if($dbh && $genre_id > 0 && int_ok($genre_id) ){
		$sql = "UPDATE tbl_genres SET genre='" . trim($genre) . "' WHERE genre_id=" . $genre_id;	
	} else if ($dbh && $genre){
		$sql = "INSERT INTO tbl_genres (genre) VALUES ('" . trim($genre) . "')";
	}

	if($sql)
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	
	if($genre_id<1){
		$genre_id = mysql_insert_id($dbh);
	}

	return $genre_id;
}

function genre_delete($genre_id){
	
	$dbh = db_connect();
	
	if($dbh && $genre_id > 0 && int_ok($genre_id) ){
		$sql = "DELETE FROM tbl_genres WHERE genre_id=" . $genre_id;
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	}

}

function genre_lookup($genre_id){

	$dbh = db_connect();

	if($dbh && $genre_id>0 && int_ok($genre_id)){
		$sql = "SELECT * FROM tbl_genres WHERE genre_id=" . $genre_id;
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
	
		if($result)
			return $result;
	}
	
}	

function db_result_query($sql){

	$dbh = db_connect();

	if($dbh && $sql){
		$result = @mysql_query ( $sql, $dbh ) or my_die ( __LINE__, mysql_error($dbh), $sql );
		if($result)
			return $result;		
	}

}

function user_account_notify($user_id, $password, $new){

	if($user_id > 0 && int_ok($user_id) && $password){
	
		$result = user_search($user_id, '', 0);
		$row = @mysql_fetch_array($result);
	
		if($row){
			$name = trim($row['fname'] . " " . $row['lname']);
			$username = $row['username'];
		
			if($new == 1){
				$subject = "Welcome to ReStyleSource.com!";
				$message = file_get_contents('email-templates/welcome.txt');
				$message = str_replace("[name]", $name, $message);
				$message = str_replace("[username]", $username, $message);
				$message = str_replace("[password]", $password, $message);
			} else {
				$subject = "ReStyleSource.com Login Updated";
				$message = file_get_contents('email-templates/password-change.txt');
				$message = str_replace("[name]", $name, $message);
				$message = str_replace("[username]", $username, $message);
				$message = str_replace("[password]", $password, $message);
			}
			//echo("DEBUG: " . $user_id . " - " . $row['email'] . "," . $subject . "," . $message . "," . SYSTEM_EMAIL . "<BR>");

			$headers  = "From: " . SYSTEM_EMAIL . "\r\n"; 
			
			if($message != strip_tags($message))
			    $headers .= "Content-type: text/html\r\n"; 
			
			mail($row['email'], $subject, $message, "from: " . $headers);
		}
	}
}

// SITE END

function notifyDeveloper($function, $mysql_err, $code){
	global $user;

	$email_msg .= "Function: " . $function . " \r\n";
	$email_msg .= "Code: " . $code . " \r\n\r\n";
	$email_msg .= "SQL: " . $mysql_err . " \r\n\r\n";
	$email_msg .= "URI: " . $_SERVER['REQUEST_URI'] . " \r\n";
	$email_msg .= "UserId: " . $user . "\r\n";
	$email_msg .= "Agent: " . $_SERVER['HTTP_USER_AGENT'] . " \r\n\r\n";

	if($user){
		//$user_row = mysql_fetch_array(user_lookup($user));
		$email_msg .= "User: " . $user_row['username'] . " / " . $user_row['name'] . " \r\n\r\n";
	}

	mail(DEVELOPER_EMAIL, 'Error: ' . $_SERVER["HTTP_HOST"], $email_msg, "From: " . SYSTEM_EMAIL);
}

function my_die($function, $mysql_err, $code){
	global $dbh, $user, $debug;

	if($debug){
		echo("<strong>Error: </strong>" . $mysql_err . "<br /><strong>" . $code . "</strong><br />");	
	}

	notifyDeveloper($function, $mysql_err, $code);

}

?>

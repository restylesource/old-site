<?
	$require_login = 1;
	$allowed_groups = array("admin");

	include_once('includes/db.php');	
	include_once('includes/logincheck.php');
	
	if($_POST || $_GET['action']){
		if($_POST['user_delete']){
			foreach($_POST['user_delete'] as $user_id){
				user_delete($user_id);
			}
		} else if ($_GET['action'] == "delete" && $_GET['user_id'] > 0 ){	
			user_delete($_GET['user_id']);	
		} else if($_POST['fname']){
			
			$user_id = user_add($_POST['user_id'], $_POST['fname'], $_POST['lname'], $_POST['company'], $_POST['email'], $_POST['phone'], $_POST['address1'], $_POST['city'], $_POST['state'], $_POST['zip'], $_POST['rate'], $_POST['notes'], 2, $_POST['password1'], 0, $error);
			user_save_extended($user_id, $_POST['user_gender'], $_POST['birthdate'], $_POST['website'], $_POST['facebook'], $_POST['twitter'], $_POST['linkedin']);

			// We have to use hidden user status field, custom control always returns preloaded value
			user_status($user_id, $_POST['user_status']);
			
			$new_login = ($_POST['user_id'] < 1 && $user_id > 0 && $_POST['password1'] && $_POST['user_status'] == "active") ? 1 : 0;
			$password_change = ($_POST['password1'] && $_POST['user_id'] && $_POST['user_status'] == "active") ? 1 : 0;
						
			if($new_login){
				user_account_notify($user_id, $_POST['password1'], 1);
			} else if ($password_change) {
				user_account_notify($user_id, $_POST['password1'], 0);
			}
			//die();
		} 
		my_redirect($_SERVER['PHP_SELF']);
	}
	
	$user_result = user_search(0, '', 2);
	
	if($user_result){
	
		while($row = @mysql_fetch_array($user_result)){
			$user_output.= '<tr> 
								<td><input type="checkbox" name="user_delete[]" value="' . $row[0] . '" /></td>
								<td><a href="profile.php?user_id=' . $row['id'] . '">' . trim($row['fname'] . ' ' . $row['lname']) . '</a></td>
								<td>' . $row['status'] . '</td>
								<td>' . date_friendly($row['registration_date'], 1, 1) . '</td>
								<td>' . $row['email'] . '</td>
								<td>
									<a href="profile.php?user_id=' . $row[0] . '"><img src="gfx/icon-edit.png" alt="edit" /></a>
									<a href="' . $PHP_SELF . '?action=delete&user_id=' . $row[0] . '" onclick="return confirm(\'Are you sure you want to delete?\r\n\r\nWARNING: This cannot be undone.\')"><img src="gfx/icon-delete.png" alt="delete" /></a>
								</td>
							</tr>';
		}
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>ReStyle | Manage Retailers</title>


	<?php include("includes/css.php"); ?>
</head>
<body>
<div class="container">   
 	<!--begin header nav -->
	<?php include("includes/header.php"); ?>
    <!-- end header nav-->
    
    <!-- begin main site nav imported from main nav php-->

	<?php include("includes/nav.php"); ?>
    
	<!-- end main site nav-->
    
    <!--begin breadcrumb menu-->
    
    
	<div class="breadcrumbs">
		<ul>
			<li class="home"><a href="/admin"></a></li>
			<li class="break">&#187;</li>
			<li><a href="user-manager.php">Manage Users</a></li>
		</ul>
	</div>
	
    <!-- begin content area-->
    
    <!-- begin retail management table-->
    
	<div class="section">
		<div class="full">
			<div class="box">
				<div class="title">
					<h2>Manage: Users</h2>
					
				</div>
				<div class="content">
					<form method="POST">
					<input type="hidden" id="action" name="action" value="delete"> 
					<table cellspacing="0" cellpadding="0" border="0" class="all">
						<thead>       
							<tr> 
								<th><input type="checkbox" name="check" class="checkall" /></th>
								<th>Name</th> <!-- name of retailer-->
								<th>Status</th> <!--pending, approved, on hold whatever-->
								<th>Date created</th>
								<th>Email</th> <!--contact name-->
								<th>Action</th>
							</tr> 
						</thead> 
						<tbody>
							<?=$user_output?>
						</tbody> 
					</table>
					<button type="submit" class="red"><span>Delete</span></button>
					<a href="ajax-user-edit.php" rel="New User Details" class="button green modalopen">New User</a>
					</form>
				</div>
			</div>
		</div>
	</div>
    
    <!-- end user management table-->
	
<!-- Edit form Overlay-->   
<div class="modal" title="New User Details"></div>
  
	<?php include("includes/footer.php"); ?>
	
</div>

<?php include("includes/js.php") ?>
    <script type="text/javascript" src="js/jquery.MultiFile.js"></script>

</body>
</html>
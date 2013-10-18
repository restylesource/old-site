<?

	$require_login = 1;


	include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/db.php");	
	include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/logincheck.php");

	// Admin can edit anyone and User can edit their own profile
	if($_REQUEST['user_id']>0 && $_REQUEST['user_id'] != $g_sess->get_var("user") && $g_sess->get_var("systemTeam") != "admin"){
		my_redirect($_SERVER['PHP_SELF']);
	}

	$uploads_dir = "../users";

	//echo("<pre>");
	//print_r($_POST);
	//die();

	if($_REQUEST['user_id']<1){
		$_GET['user_id'] = $g_sess->get_var("user");
	}
	
	if ($_POST['user_id'] && $_FILES['profile_photo']["name"] && $_FILES['profile_photo']["error"] == UPLOAD_ERR_OK) {
	
		// Verify Admin or Post User = Logged In User
		if($g_sess->get_var("user") != $_POST['user_id'] && $g_sess->get_var("systemTeam") != "admin"){
			my_redirect($_SERVER['PHP_SELF']);
		}
	
		$name = $_POST['user_id'] . "_" . time() . "." . gfe($_FILES['profile_photo']["name"]);
	
		//die($_FILES['profile_photo']["tmp_name"] . " - $uploads_dir/$name");
	
		if(square_crop($_FILES['profile_photo']["tmp_name"], "$uploads_dir/$name")){
			user_photo_save($_POST['user_id'], $name);
		}
		
		my_redirect($_SERVER['PHP_SELF'] . "?user_id=" . $_GET['user_id']);
	}

	if($_POST['fname']){
		
		$edit_user_id = ($_POST['user_id']) ? $_POST['user_id'] : $g_sess->get_var("user");
		
		user_add($_POST['retailer_id'], $_POST['fname'], $_POST['lname'], $_POST['company'], $_POST['email'], $_POST['phone'], $_POST['address1'], $_POST['city'], $_POST['state'], $_POST['zip'], $_POST['rate'], $_POST['website'], $_POST['notes'], $_POST['kind'], $_POST['password1'], $_POST['level'], $_POST['source_type'], $_POST['keywords'], $error);
		
		$user_id = user_add($edit_user_id, $_POST['fname'], $_POST['lname'], $_POST['company'], $_POST['email'], $_POST['phone'], $_POST['address1'], $_POST['city'], $_POST['state'], $_POST['zip'], $_POST['rate'], $_POST['website'], $_POST['notes'], 2, $_POST['password1'], '', $_POST['level'], $_POST['source_type'], $_POST['keywords'], $error);
		user_save_extended($user_id, $_POST['user_gender'], $_POST['birthdate'], $_POST['facebook'], $_POST['twitter'], $_POST['linkedin']);

		// We have to use hidden user status field, custom control always returns preloaded value
		user_status($user_id, $_POST['user_status']);
		
		$new_login = ($_POST['user_id'] < 1 && $user_id > 0 && $_POST['password1'] && $_POST['user_status'] == "active") ? 1 : 0;
		$password_change = ($_POST['password1'] && $_POST['user_id'] && $_POST['user_status'] == "active") ? 1 : 0;
					
		if($new_login){
			user_account_notify($user_id, $_POST['password1'], 1);
		} else if ($password_change) {
			user_account_notify($user_id, $_POST['password1'], 0);
		}

		my_redirect($_SERVER['PHP_SELF'] . "?user_id=" . $user_id);

	} else if($_GET['user_id']){
	
		$user_result = user_search($_GET['user_id']);

		if($user_result){
		
			$row = @mysql_fetch_array($user_result);
			
			$status = $row['status'];
			$company = $row['company'];
			$fname = $row['fname'];
			$lname = $row['lname'];
			$email = ($row['email']) ? "<a href=\"mailto:" . $row['email'] . "\">" . $row['email'] . "</a>" : "";
			$phone = $row['phone'];
			$address1 = $row['address1'];
			$city = $row['city'];
			$state = $row['state'];
			$zip = $row['zip'];
			
			$gender = $row['gender'];
			$website = ($row['website']) ? "<a href=\"" . $row['website'] . "\" target=\"_blank\">" . $row['website'] . "</a>" : "";
			$age = calcAge($row['birthdate']);
			$facebook = ($row['facebook']) ? "<a href=\"" . $row['facebook'] . "\" target=\"_blank\">" . $row['facebook'] . "</a>" : "";
			$twitter = $row['twitter'];
			$linkedin = $row['linkedin'];
			
			$source_type = $row['source_type'];
			$keywords = $row['keywords'];
			
			$notes = $row['notes'];
			$profile_photo = $row['profile_photo'];

		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Edit Profile</title>
	
	<?php include("includes/css.php"); ?>
</head>

<body>
	<div class="container"> 
  <!--begin header nav -->
	<?php include("includes/header.php"); ?>
	
    <!-- end header nav-->
    
    <!-- begin main site nav-->
    
	<?php include("includes/nav.php"); ?>
	<!-- end main site nav-->
    
    <!--begin breadcrumb menu-->
	<div class="breadcrumbs">
		<ul>
			<li class="home"><a href="/admin"></a></li>
			<li class="break">&#187;</li>
			<li><a href="profile.com">Profile</a></li>
		</ul>
	</div>
    
    <!-- begin profile Section.
    ---- All fields will be read only and are input into the profile table via php-->
	<div class="section">
		<div class="full">
			<div class="box">
				<div class="title">
					<h2>Profile</h2>

					
				</div>
                
				<div class="content" style="overflow:hidden;">
                
                <!--begin profile image area-->
                
                	<table id="profileImage">
                    	<tr>
                		<td>
                    		<img src="<?if($profile_photo) { echo ("/users/" . $profile_photo); } else { ?>gfx/photos/00.jpg<?}?>" width="200px" />
                        </td></tr>
                        
                    <form id="profilePhotoUpload" method="POST" action="" enctype="multipart/form-data">
                    	<input type="hidden" name="user_id" value="<?=$_GET['user_id']?>" >
                    	<input type="hidden" name="source_type" value="<?=$source_type?>" >
                    	<input type="hidden" name="keywords" value="<?=$keywords?>" >
                    	<tr>
                    	<td>
                    Upload Profile Photo
                    	</td>
                        </tr>
                    <tr>
                    <td>
                    <input type="file" name="profile_photo" style="height:25px;width:100%;margin-left:auto;margin-right:auto;"/>
                    </td></tr>
                    <tr><td>
                    <button type="submit" class="green" style="margin:0;"><span>Save</span></button>
                    </form>
                    </td></tr></table>
					
                    
                <!-- end profile image area-->
                <table id="profileDetails">
                <tr>
                <td>Company Name:</td>
                <td><?=$company?></td>
                </tr>
                 <tr>
                <td>Status:</td>
                <td><?=$status?></td>
                </tr>
                <tr>
                <td>First Name:</td>
                <td><?=$fname?></td>
                </tr>
                <tr>
                <td>Last Name:</td>
                <td><?=$lname?></td>
                </tr>
                <tr>
                <td>Email Address:</td>
                <td><?=$email?></td>
                </tr>
                <tr>
                <td>Phone:</td>
                <td><?=$phone?></td>
                </tr>
				<tr>
                <td>Web Site Address:</td>
                <td><?=$website?></td>
                </tr>
				<tr>
                <td>Address:</td>
                <td><?=$address1?></td>
                </tr>
				<tr>
                <td>City:</td>
                <td><?=$city?></td>
                </tr>
                <tr>
                <td>State:</td>
                <td><?=$state?></td>
                </tr>
                <tr>
                <td>Zip:</td>
                <td><?=$zip?></td>
                </tr>
				<tr>
                <td>Notes:</td>
                <td><?=$notes?></td>
                </tr>
                <tr>
                <td>Gender:</td>
                <td><?=$gender?></td>
                </tr>
                <tr>
                <td>Age:</td>
                <td><?=$age?></td>
                </tr>
				<tr>
                <td>Facebook:</td>
                <td><?=$facebook?></td>
                </tr>
				<tr>
                <td>Twitter</td>
                <td><?=$twitter?></td>
                </tr>
                <tr>
                <td>LinkedIn:</td>
                <td><?=$linkedin?></td>
                </tr>
                <tr>
                <td>
                </td>
                <td>
                <a href="ajax-user-edit.php?user_id=<?=$_GET['user_id']?>" rel="Manage User Details" class="button blue modalopen">Edit</a>
                </td>
                </tr>
                </table>
                </div>
			</div>
		</div>
	</div>
    
    			<div class="modal forms" style="display:none;">
				</div>

	<?php include("includes/footer.php"); ?>
</div>


        <div id="help1" style="display:none;">
        <h2>Your Profile</h2>
        <p>Here you can upload your profile. To change your profile photo click the browse button and find the new photo. To change any of your info click the edit button.
		</p>
    
    </div>
    
    
    <?php include("includes/js.php"); ?>
    


</body>

</html> 
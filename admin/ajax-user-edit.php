<?

	$require_login = 1;

	include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/db.php");	
	include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/logincheck.php");

	if($_GET['user_id']){
	
		$user_result = user_search($_GET['user_id'], '');

		if($user_result){
			$row = @mysql_fetch_array($user_result);
			
			$status = $row['status'];
			$level = $row['level'];
			$user_group_id = $row['user_group_id'];
			$company = $row['company'];
			$fname = $row['fname'];
			$lname = $row['lname'];
			$email = $row['email'];
			$phone = $row['phone'];
			$address1 = $row['address1'];
			$city = $row['city'];
			$state = $row['state'];
			$zip = $row['zip'];
			
			$gender = $row['gender'];
			$website = $row['website'];
			$birthdate = date("m/d/Y", strtotime($row['birthdate']));
			$facebook = $row['facebook'];
			$twitter = $row['twitter'];
			$linkedin = $row['linkedin'];
			
			$notes = $row['notes'];
			
		}
	}

	$state_result = state_search();
	$state_options = build_generic_dropdown($state_result, $state, 0);

?>
<form id="user-edit" action="" method="post">
	<input type="hidden" name="user_id" id="user_id" value="<?=$_GET['user_id']?>" >
	<input type="hidden" name="user_status" id="user_status" value="<?=$status?>" >
	<input type="hidden" name="user_gender" id="user_gender" value="<?=$gender?>" >
	<input type="hidden" name="user_group_id" id="user_group_id" value="<?=$user_group_id?>" >
	<div class="line">
		<label>Company Name:</label>
		<input type="text" class="small" name="company" value="<?=$company?>" />
	</div>
	<div class="line" style="<?if($g_sess->get_var("systemTeam") != "admin"){ echo("display: none;"); } ?>">
		<label>Status:</label>
		<input type="radio" name="status" id="radio-1" value="pending" <?if($status=="pending" || $status==""){ echo("checked=\"checked\""); }?>" /> 
		<label for="radio-1">Pending</label>							
		<input type="radio" name="status" id="radio-2" value="active" <?if($status=="active"){ echo("checked=\"checked\""); }?>" /> 
		<label for="radio-2">Active</label>
		<input type="radio" name="status" id="radio-3" value="inactive" <?if($status=="inactive"){ echo("checked=\"checked\""); }?>" /> 
		<label for="radio-3">Inactive</label>
	</div>
	<div class="line">
		<label>First name</label>
		<input type="text" name="fname" class="small required" value="<?=$fname?>" />
	</div>
	<div class="line">
		<label>Last name</label>
		<input type="text" name="lname" class="small required" value="<?=$lname?>" />
	</div>
	<div class="line">
		<label>Email Address:</label>
		<input type="text" name="email" class="small required email uniqueEmail" value="<?=$email?>" />
	</div>
	<div class="line">
		<label>Password:</label>
		<input type="password" class="small" id="password1" name="password1" value="" />
	</div>
	<div class="line">
		<label>Password Again:</label>
		<input type="password" class="small password-match" id="password2" name="password2" value="" />
	</div>
	<div class="line">
		<label>Phone Number:</label>
		<input type="text" name="phone" class="small phoneUS" value="<?=$phone?>" />
	</div>
	<div class="line">
		<label>Web Site Address:</label>
		<input type="text" name="website" class="small url" value="<?=$website?>" />
	</div>
	<div class="line">
		<label>Address:</label>
		<input type="text" name="address1" class="medium" value="<?=$address1?>" />
	</div>
	<div class="line">
		<label>City:</label>
		<input type="text" name="city" class="small" value="<?=$city?>" />
	</div>
	<div class="line">
		<label>State:</label>
		<select name="state">
			<?=$state_options?>
		</select>
	</div>
	<div class="line">
		<label>Zip:</label>
		<input type="text" class="small" name="zip" value="<?=$zip?>" />
	</div>
	<div class="line">
		<label>Notes:</label>
		<input type="text" class="small" name="notes" value="<?=$notes?>" />
	</div>
	<div class="line">
		<label>Gender:</label>
		<input type="radio" name="gender" id="gender-1" value="male" <?if($gender=="male"){ echo("checked=\"checked\""); }?>" /> 
		<label for="gender-1">Male</label>							
		<input type="radio" name="gender" id="gender-2" value="female" <?if($gender=="female"){ echo("checked=\"checked\""); }?>" /> 
		<label for="gender-2">Female</label>
	</div>
	<div class="line">
		<label>Age:</label>
		<span class="date">Birthday:</span> <input type="text" name="birthdate" class="datepicker date" value="<?=$birthdate?>" /> 
	</div>
	<div class="line">
		<label>Facebook:</label>
		<input type="text" class="small facebook" name="facebook" value="<?=$facebook?>" />
	</div>
	<div class="line">
		<label>Twitter:</label>
		<input type="text" class="small twitter" name="twitter" value="<?=$twitter?>" />
	</div>
	<div class="line">
		<label>Linkedin:</label>
		<input type="text" class="small linkedin" name="linkedin" value="<?=$linkedin?>" />
	</div>
	<div class="line button">
		<button class="red cancel"><span>Cancel</span></button>
		<button type="submit" class="green"><span>Update</span></button>
	</div>
</form>
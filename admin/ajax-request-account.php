<?
	$require_login = 1;
	include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/db.php");

	$user_groups = user_groups_search();
	$user_group_options = build_generic_dropdown($user_groups, 3, 0);

	$state_result = state_search();
	$state_options = build_generic_dropdown($state_result, $state, 0);

?>
<form id="user-edit" action="" method="post">						
	<div class="line">
		<h3>Contact Info:</h3>Fields marked with an <span style="color: #cc0000;">*</span> are required...
	</div>
	<div class="line">
		<label>Company Name:</label>
		<input type="text" class="small" value="" />
	</div>
	<div class="line">
		<label>User Type<span style="color: #cc0000;">*</span>:</label>
		<select name="user_type">
			<?=$user_group_options?>
		</select>
	</div>
	<div class="line">
		<label>First Name<span style="color: #cc0000;">*</span>:</label>
		<input type="text" name="fname" class="small required" value="<?=$fname?>" />
	</div>
	<div class="line">
		<label>Last Name<span style="color: #cc0000;">*</span>:</label>
		<input type="text" name="lname" class="small required" value="<?=$lname?>" />
	</div>
	<div class="line">
		<label>Email Address<span style="color: #cc0000;">*</span>:</label>
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
	<div class="line button">
		<button id="cancel-register" class="red"><span>Cancel</span></button>
		<button type="submit" class="green"><span>Send Registration</span></button>
	</div>
</form>
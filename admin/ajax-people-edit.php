<?

	include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/db.php");

	if($_GET['retailer_id']){
	
		$retailer_result = user_search($_GET['retailer_id'], '', 3);
	
		if($retailer_result){
			$row = @mysql_fetch_array($retailer_result);
			
			$status = $row['status'];
			$company = $row['company'];
			$fname = $row['fname'];
			$lname = $row['lname'];
			$email = $row['email'];
			$phone = $row['phone'];
			$address1 = $row['address1'];
			$city = $row['city'];
			$state = $row['state'];
			$zip = $row['zip'];
			$notes = $row['notes'];

		}
	}

	$state_result = state_search();
	$state_options = build_generic_dropdown($state_result, $state, 0);

?>
<script type="text/javascript">var cat = <?php print json_encode($cat_array);?>;</script>
<form id="retailer-edit" method="post">
		<input type="hidden" id="user_id" name="retailer_id" value="<?=$_GET['retailer_id']?>">						
		<input type="hidden" name="user_status" id="user_status" value="<?=$status?>" >						
		<div class="line">
			<h3>Contact Info:</h3>Fields marked with an <span style="color: #cc0000;">*</span> are required...
		</div>
		<div class="line">
			<label>Status:</label>
			<input type="radio" name="status" id="radio-1" value="pending" <?if($status=="pending" || $status==""){ echo("checked=\"true\""); }?>" /> 
			<label for="radio-1">Pending</label>							
			<input type="radio" name="status" id="radio-2" value="active" <?if($status=="active"){ echo("checked=\"true\""); }?>" /> 
			<label for="radio-2">Active</label>
			<input type="radio" name="status" id="radio-3" value="inactive" <?if($status=="inactive"){ echo("checked=\"true\""); }?>" /> 
			<label for="radio-3">Inactive</label>
		</div>
		<div class="line">
			<label>People Type:</label>
			<select multiple="multiple" name="inspiration">
			<?=$people_types?>
			</select>
		</div>
		<div class="line">
			<label>Company Name:</label>
			<input type="text" name="company" class="small required" value="<?=$company?>" />
		</div>
		<div class="line">
			<label>First Name<span style="color: #cc0000;">*</span>:</label>
			<input type="text" name="fname" class="small required" value="<?=$fname?>" />
		</div>
		<div class="line">
			<label>Last Name:</label>
			<input type="text" name="lname" class="small required" value="<?=$lname?>" />
		</div>
		<div class="line">
			<label>Email Address:</label>
			<input type="text" name="email" class="small required email uniqueEmail" value="<?=$email?>" />
		</div>
		<div class="line">
			<label>Password:</label>
			<input type="text" class="small" id="password1" name="password1" value="" />
		</div>
		<div class="line">
			<label>Password Again:</label>
			<input type="text" class="small password-match" id="password2" name="password2" value="" />
		</div>
		<div class="line">
			<label>Phone Number:</label>
			<input type="text" name="phone" class="small" value="<?=$phone?>" />
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
			<input type="text" name="zip" class="small" value="<?=$zip?>" />
		</div>
		<div class="line">
			<label>Notes:</label>
			<textarea class="medium" name="notes" rows="" cols=""><?=$notes?></textarea>
		</div>
		<div class="line button">
			<button class="red cancel1"><span>Cancel</span></button>
			<button class="green"><span>Save</span></button>
		</div>
</form>
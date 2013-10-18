<?

	include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/db.php");

	if($_GET['retailer_id']){
	
		$retailer_result = user_search($_GET['retailer_id'], '');
	
		if($retailer_result){
			$row = @mysql_fetch_array($retailer_result);
						
			$status = $row['status'];
			$company = $row['company'];
			$friendly_url = $row['friendly_url'];
			$kind = $row['user_group_id'];
			$fname = $row['fname'];
			$lname = $row['lname'];
			$email = $row['email'];
			$phone = $row['phone'];
			$address1 = $row['address1'];
			$city = $row['city'];
			$state = $row['state'];
			$zip = $row['zip'];
			$website = $row['website'];
			$twitter = $row['twitter'];
			$facebook = $row['facebook'];
			$notes = $row['notes'];
			$keywords = $row['keywords'];
			$meta_title = $row['meta_title'];
			$meta_keywords = $row['meta_keywords'];
			$meta_description = $row['meta_description'];
			
			$email_updates = $row['email_updates'];
			
			$inventory_ind = $row['inventory_ind'];
			
			$source_type_result = user_source_types_lookup($_GET['retailer_id']);
			
			$source_array = array();
			
			if($source_type_result){
				while($row2 = @mysql_fetch_array($source_type_result)){
	
					$source_array[] = array($row2['source_id'], $row2['sub_source_id']);
				}	
			}
		}
	}

	$state_result = state_search();
	$state_options = build_generic_dropdown($state_result, $state, 1);
	
	$source_result = sources_search();
	$source_options1 = build_generic_dropdown($source_result, $source_array[0][0], 1, "&nbsp;");

	$sub_result = sub_source_lookup($source_array[0][0]);
	$sub_source_options1 = build_generic_dropdown($sub_result, $source_array[0][1], 1, "(Optional)");

	$source_result = sources_search();
	$source_options2 = build_generic_dropdown($source_result, $source_array[1][0], 1, "&nbsp;");
	
	$sub_result = sub_source_lookup($source_array[1][0]);
	$sub_source_options2 = build_generic_dropdown($sub_result, $source_array[1][1], 1, "(Optional)");
	
	$source_result = sources_search();
	$source_options3 = build_generic_dropdown($source_result, $source_array[2][0], 1, "&nbsp;");
	
	$sub_result = sub_source_lookup($source_array[2][0]);
	$sub_source_options3 = build_generic_dropdown($sub_result, $source_array[2][1], 1, "(Optional)");
	
	$source_result = sources_search();
	$source_options4 = build_generic_dropdown($source_result, $source_array[3][0], 1, "&nbsp;");
	
	$sub_result = sub_source_lookup($source_array[3][0]);
	$sub_source_options4 = build_generic_dropdown($sub_result, $source_array[3][1], 1, "(Optional)");
	
	$source_result = sources_search();
	$source_options5 = build_generic_dropdown($source_result, $source_array[4][0], 1, "&nbsp;");

	$sub_result = sub_source_lookup($source_array[4][0]);
	$sub_source_options5 = build_generic_dropdown($sub_result, $source_array[4][1], 1, "(Optional)");

?>
<script type="text/javascript">var cat = <?php print json_encode($cat_array);?>;</script>
<form id="retailer-edit" method="post">
		<input type="hidden" id="user_id" name="retailer_id" value="<?=$_GET['retailer_id']?>">
		<input type="hidden" name="user_status" id="user_status" value="<?=$status?>" >						
		<input type="hidden" name="email_updates" id="email_updates" value="<?=$email_updates?>" >						
		<div class="line">
			<label>Status:</label>
			<input type="radio" name="status" id="radio-1" value="pending" <?if($status=="pending" || $status==""){ echo("checked=\"true\""); }?>" /> 
			<label for="radio-1">Pending&nbsp;&nbsp;&nbsp;</label>							
			<input type="radio" name="status" id="radio-2" value="listing" <?if($status=="listing"){ echo("checked=\"true\""); }?>" /> 
			<label for="radio-2">Listing</label>
			<input type="radio" name="status" id="radio-3" value="active" <?if($status=="active"){ echo("checked=\"true\""); }?>" /> 
			<label for="radio-3">Active</label>
			<input type="radio" name="status" id="radio-4" value="inactive" <?if($status=="inactive"){ echo("checked=\"true\""); }?>" /> 
			<label for="radio-4">Inactive</label>
		</div>
		<div class="line">
			<label>Company Name:</label>
			<input type="text" name="company" class="small required" value="<?=$company?>" />
		</div>
		<div class="line">
			<label>Kind:</label>
			<select name="kind">
			<option value="3" <?if($kind==3) echo("selected");?>>Retailer<option>
			<option value="4" <?if($kind==4) echo("selected");?>>Manufacturer<option>
			</select>
		</div>
		<div class="line">
			<label>Adopt Product:</label>
			<select name="adopt">
			<option value="1" <?if($inventory_ind==1) echo("selected") ?>>Yes<option>
			<option value="0" <?if($inventory_ind==0) echo("selected") ?>>No<option>
			<option value="2" <?if($inventory_ind==2) echo("selected") ?>>Style Source<option>
			</select>
		</div>
		<div class="line">
			<label>1st Source Type:</label>
			<select name="source_type[]" class="source">
			<?=$source_options1?>
			</select>
			<select name="subsource[]" class="subsource">
			<?=$sub_source_options1?>
			</select>
		</div>
		<div class="line">
			<label>2nd Source Type:</label>
			<select name="source_type[]" class="source">
			<?=$source_options2?>
			</select>
			<select name="subsource[]" class="subsource">
			<?=$sub_source_options2?>
			</select>
		</div>
		<div class="line">
			<label>3rd Source Type:</label>
			<select name="source_type[]" class="source">
			<?=$source_options3?>
			</select>
			<select name="subsource[]" class="subsource">
			<?=$sub_source_options3?>
			</select>
		</div>
		<div class="line">
			<label>4th Source Type:</label>
			<select name="source_type[]" class="source">
			<?=$source_options4?>
			</select>
			<select name="subsource[]" class="subsource">
			<?=$sub_source_options4?>
			</select>
		</div>
		<div class="line">
			<label>5th Source Type:</label>
			<select name="source_type[]" class="source">
			<?=$source_options5?>
			</select>
			<select name="subsource[]" class="subsource">
			<?=$sub_source_options5?>
			</select>
		</div>
		<div class="line">
			<label>First Name:</label>
			<input type="text" name="fname" class="small" value="<?=$fname?>" />
		</div>
		<div class="line">
			<label>Last Name:</label>
			<input type="text" name="lname" class="small" value="<?=$lname?>" />
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
			<label>Country:</label>
			<select name="Country">
				<option value="US">United States</option>
				<option value="CA">Canada</option>
			</select>
		</div>
		<div class="line">
			<label>Address:</label>
			<input type="text" name="address1" class="medium" value="<?=$address1?>" />
		</div>
		<div class="line">
			<label>City/Municipality:</label>
			<input type="text" name="city" class="small" value="<?=$city?>" />
		</div>
		<div class="line">
			<label>State/Province:</label>
			<select name="state">
				<?=$state_options?>
			</select>
		</div>
		<div class="line">
			<label>Zip/Postal Code:</label>
			<input type="text" name="zip" class="small" value="<?=$zip?>" />
		</div>
		<div class="line">
			<label>Website:</label>
			<input type="text" name="website" class="small" value="<?=$website?>" />
		</div>
		<div class="line">
			<label>Twitter:</label>
			<input type="text" name="twitter" class="small" value="<?=$twitter?>" />
		</div>
		<div class="line">
			<label>Facebook:</label>
			<input type="text" name="facebook" class="small" value="<?=$facebook?>" />
		</div>
		<div class="line">
			<label>Notes:</label>
			<textarea class="medium" name="notes" rows="" cols=""><?=$notes?></textarea>
		</div>
		<div class="line">
			<label>Keywords:</label>
			<input type="text" name="keywords" class="medium" value="<?=$keywords?>" />
		</div>
		<div class="line">
			<label>Meta Title:</label>
			<input type="text" class="small" name="meta_title" value="<?=$meta_title?>" />
		</div>
		<div class="line">
			<label>Meta Keywords:</label>
			<input type="text" class="small" name="meta_keywords" value="<?=$meta_keywords?>" />
		</div>
		<div class="line">
			<label>Meta Description:</label>
			<input type="text" class="small" name="meta_description" value="<?=$meta_description?>" />
		</div>
		<div class="line button">
			<button class="red cancel1"><span>Cancel</span></button>
			<button class="green"><span>Save</span></button>
		</div>
</form>
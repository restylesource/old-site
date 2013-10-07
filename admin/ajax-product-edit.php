
<form method="post" action="">
		<div class="line">
			 <h3>Product Info:</h3>All fields required
		</div>
		<div class="line">
			<label>Thumbnail</label>
			<a href="gfx/photos/01xl.jpg" class="zoom"><img src="gfx/photos/01.jpg" class="shadow" alt="Photo" /></a>
		</div>
		<div class="line" style="<?if($g_sess->get_var("systemTeam") != "admin"){ echo("display: none;"); } ?>">
			<label>Status:</label>
			<input type="radio" name="status" id="radio-1" value="pending" <?if($status=="pending" || $status==""){ echo("checked=\"true\""); }?>" /> 
			<label for="radio-1">Pending</label>							
			<input type="radio" name="status" id="radio-2" value="active" <?if($status=="active"){ echo("checked=\"true\""); }?>" /> 
			<label for="radio-2">Active</label>
			<input type="radio" name="status" id="radio-3" value="inactive" <?if($status=="inactive"){ echo("checked=\"true\""); }?>" /> 
			<label for="radio-3">Inactive</label>
		</div>
		<div class="line">
			<label>Restyle ID:</label>
			123456
		</div>
		<div class="line">
			<label>UPC:</label>
			<input type="text" class="small" value="" />
		</div>
		<div class="line">
			<label>Product Name:</label>
			<input type="text" class="small" value="" />
		</div>
		<div class="line">
			<label>Owner:</label>
			Jimmy's Couch Shack
		</div>
		<div class="line">
			<label>Keywords:</label>
			<input type="text" class="medium" /><br />Please separate keywords with a comma.
		</div>
		<div class="line">
			<label>Description:</label>
			<textarea class="medium" rows="" cols=""></textarea>
		</div>                        
		<!--depending on the answer above, the below select should populate with the appropriate options-->
		<div class="line">
			<label>Categories:</label>
			<div>
			<select>
				<option value="">Kitchen</option>
				<option value="">Living Room</option>
				<option value="">Dinning Room</option>
				<option value="">Bed Room</option>
				<option value="">Bathroom</option>
			</select>
			<select>
				<option value="">Choose Sub Category</option>
				<option value="">Living Room</option>
				<option value="">Dinning Room</option>
				<option value="">Bed Room</option>
				<option value="">Bathroom</option>
			</select>
			
			</div>
			<a class="add" onclick="Repeat(this);"></a>
			<a class="delete" onclick="Delete(this);"></a>
		</div>
		<div class="line">
			
			<label>Color:</label>
			<div>
			<select>
				<option value="">White</option>
				<option value="">Black</option>
				<option value="">Red</option>
				<option value="">Blue</option>
				<option value="">Green</option>
			</select>
			</div>
			<a class="add" onclick="Repeat(this);"></a>
			<a class="delete" onclick="Delete(this);"></a>
		</div>
		<div class="line">
			<label>Style:</label>
			<select>
				<option value="">Retro</option>
				<option value="">Modern</option>
				<option value="">Contemporary</option>
				<option value="">19th Century</option>
				<option value="">Vintage</option>
			</select>
		</div>
		<div class="line">
			<label>MSRP:</label>
			<input type="text" class="small" value="" />
		</div>
		 <div class="line">
			<label>Dimensions:<br/>(W x L x H)</label>
			<input name="width" class="xsmall" maxlength="3"></input>in
			<input name="length" class="xsmall" maxlength="3"></input>in
			<input name="height" class="xsmall" maxlength="3"></input>in
		</div>
		<div class="line">
			<label>Weight:</label>
			<input name="weight" class="xsmall" maxlength="3"></input>lbs
		</div>
		<div class="line">
		<label>Add Photos:</label>
		<input type="file" name="files[]" class="multi {namePattern:'$name$i'}" style="height:25px;"maxlength="5"/>
		</div>
		<div class="line button">
		<button class="red cancel"><span>Cancel</span></button>
		<button class="green"><span>Edit</span></button>
		</div>    
</form>


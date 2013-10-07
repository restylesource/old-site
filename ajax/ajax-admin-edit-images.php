<?

	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';	

	if($_GET['source_id']>0){
	
		$retailer_result = user_search($_GET['source_id'], '');
	
		if($retailer_result){
			$row = @mysql_fetch_array($retailer_result);
	
			$alt_1 = $row['footer_1_alt'];
			$alt_2 = $row['footer_2_alt'];
			$alt_3 = $row['footer_3_alt'];
		
		}
	}

?>

<h1>Edit Images</h1>

<p>
Image 1 = 356 x 236 pixels // Image 2 = 188 x 236 pixels // Image 3 = 310 x 236 pixels
</p>

<form id="upload" name="upload" enctype="multipart/form-data" action="" method="POST">
<input type="hidden" id="id" name="id" value="<?=$_GET['source_id']?>" />
	<fieldset>
		<div class="custom-file-input">
			<label>1 SM</label>
			<input type="file" name="footer1_thumb" />
		</div>
		<div class="custom">
			<input type="text" placeholder="Alt Text" name="alt_1" value="<?php echo($alt_1);?>"/>
		</div>
		<div class="custom-file-input">
			<label>1 LG</label>
			<input type="file" name="footer1_full" />
		</div>		
		<div class="custom-file-input">
			<label>2 SM</label>
			<input type="file" name="footer2_thumb" />
		</div>
		<div class="custom">
			<label><input type="text" placeholder="Alt Text" name="alt_2" value="<?php echo($alt_2);?>"/></label>			
		</div>
		<div class="custom-file-input">
			<label>2 LG</label>
			<input type="file" name="footer2_full" />
		</div>		
		<div class="custom-file-input">
			<label>3 SM</label>
			<input type="file" name="footer3_thumb" />
		</div>
		<div class="custom">
			<label><input type="text" placeholder="Alt Text" name="alt_3" value="<?php echo($alt_3);?>"/></label>			
		</div>
		<div class="custom-file-input">
			<label>3 LG</label>
			<input type="file" name="footer3_full" />
		</div>
		
		
	</fieldset>
</form>

<script src="/assets/js/fileinput/jQuery.fileinput.js"></script>
<script>
$(document).ready(function() {
	$('.custom-file-input input').customFileInput();
});
</script>
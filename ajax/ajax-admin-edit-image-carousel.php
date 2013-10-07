<?

	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';	

	if($_REQUEST['source_id']>0){
		$id = $_REQUEST['source_id'];
		
		$retailer_result = user_search($_GET['source_id'], '');
	
		if($retailer_result){
			$row = @mysql_fetch_array($retailer_result);
			$alt = $row['image_' . $_REQUEST['image_id'] . '_alt'];	
		}
		
	}

?>

<h1>Edit Image</h1>

<p>The thumbnail should be 190 x 190 pixels, the full image should be no larger than 800 x 800 pixels.</p>
<form id="upload" enctype="multipart/form-data" action="" method="POST">
<input type="hidden" id="id" name="id" value="<?php echo($_REQUEST['source_id']);?>" />
<input type="hidden" id="image_id" name="image_id" value="" />
<label for="thumb">
	<p>Thumbnail:</p>
	<input type="file" class="file-image" name="thumb" id="thumb" />
</label>
<label for="alt">
	<p>Alt text:</p>
	<input type="text" name="alt" id="alt" value="<?php echo($alt);?>" />
</label>
<label for="full">
	<p>Full:</p>
	<input type="file" class="file-image" name="full" id="full" />
</label>
</form>
<script src="/assets/js/fileinput/jQuery.fileinput.js"></script>
<script>
$(document).ready(function() {
	$('.file-image').customFileInput();
});
</script>
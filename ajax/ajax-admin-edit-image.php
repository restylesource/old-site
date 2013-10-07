<?

	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';	

	if($_REQUEST['source_id']>0){
		$id = $_REQUEST['source_id'];
		
		$retailer_result = user_search($_GET['source_id'], '');
	
		if($retailer_result){
			$row = @mysql_fetch_array($retailer_result);
			$alt = $row['main_alt'];
			$credit = $row['credit'];
		}
		
		
	} else if ($_REQUEST['id']>0){
		$id = $_REQUEST['id'];
		
		if($_GET['page_block_id'] < 1){
			$result = page_lookup($id);
			if($result){
				$row = mysql_fetch_array($result);
				$credit = $row['hero_credit'];
				$alt = $row['hero_alt'];
			}
		} else if ($_GET['page_block_id'] > 0){
			$result = page_blocks_lookup($id, $_GET['page_block_id']);
			if($result){
				$row = mysql_fetch_array($result);
				$credit = $row['credit'];
				$alt = $row['alt'];
			}		
		}
	}

?>
<h1>Edit Image</h1>
<p>This image must be 870 x 460 pixels in size.</p>
<form name="upload" id="upload" enctype="multipart/form-data" action="" method="POST">
<input type="hidden" id="id" name="id" value="<?=$id?>" />
<input type="hidden" name="page_block_id" value="<?=$_GET['page_block_id']?>" />
<input type="hidden" id="action" name="action" />
<input type="file" id="file" name="image" /><br clear="all"><br />
<input type="text" placeholder="Alt Text" name="alt" value="<?php echo($alt); ?>"/><br clear="all"><br />
<input type="text" placeholder="Photo Credit" name="credit" value="<?=$credit?>" autofocus />
</form>
<script src="assets/js/fileinput/jQuery.fileinput.js"></script>
<script>
$(document).ready(function() {
	$('#file').customFileInput();
});
</script>
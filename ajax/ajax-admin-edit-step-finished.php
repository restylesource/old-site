<?
	
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';	

	$result = page_blocks_lookup($_GET['id'], $_GET['page_block_id']);
	
	if($result){
		$row = mysql_fetch_array($result);
		$header = $row['header'];
		$credit = $row['credit'];
		$alt = $row['alt'];
	}

?>

<h1>Edit Finished</h1>

<form id="admin-text" method="post" enctype="multipart/form-data">
	<input type="hidden" name="id" value="<?=$_GET['id']?>" />
	<input type="hidden" name="page_block_id" value="<?=$_GET['page_block_id']?>" />
	<input type="hidden" id="action" name="action" />
	<input type="text" placeholder="Header" name="header" value="<?=$header?>" autofocus />
	<p>Image (870px wide):</p>
	<input type="file" id="file" name="image" />
	<input type="text" placeholder="Alt Text" name="alt" value="<?=$alt?>"/>
	<input type="text" placeholder="Photo Credit" name="credit" value="<?=$credit?>" autofocus />
</form>

<p class="error"><?=$error?></p>

<script src="/assets/js/fileinput/jQuery.fileinput.js"></script>
<script>
$(document).ready(function() {
	$('#file').customFileInput();
});
</script>
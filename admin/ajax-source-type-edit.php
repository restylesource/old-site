<?

	include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/db.php");
	
	$result = sources_lookup($_GET['source_id']);
	
	if($result){
		$row = @mysql_fetch_array($result);	
		$source = $row['source'];
		$image = $row['image'];
	}

	$source_image = ($image) ? "source_images/" . $image : "";

?>
<form action="" method="post" enctype="multipart/form-data">
	<input type="hidden" name="source_id" value="<?=$_GET['source_id']?>">
	<div class="line">
		<label>Source Type</label>
		<input type="text" class="small required" name="source" value="<?=$source?>" />
		<br /><br />
        <input type="file" name="file" style="height:25px;"/><br />
        Image Icon for Source Type - Image should be 135x150.<br /><br />To update an image, browse for file and update.<br /><br />
		<br />
		<img src="<?=$source_image?>">
	</div>
	<div class="line button">
		<button type="submit" class="green"><span>Update</span></button>
		<button type="button" class="blue" onclick="top.location='source-manager-new.php?source_id=<?=$_GET['source_id']?>';" style="opacity: 1;"><span>Edit Sub-Sources</span></button>
	</div>
</form>
<?

	include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/db.php");
	
	$result = sub_source_lookup($_GET['source_id'], $_GET['sub_source_id']);
	if($result){
		$row = @mysql_fetch_array($result);	
		$sub_source = $row['sub_source'];
		$image = $row['image'];
	}

	$source_image = ($image) ? "source_images/" . $image : "";

?>
<form action="" method="post" enctype="multipart/form-data">
	<input type="hidden" name="source_id" value="<?=$_GET['source_id']?>" >
	<input type="hidden" name="sub_source_id" value="<?=$_GET['sub_source_id']?>" >
	<div class="line">
		<label>Category Name</label>
		<input type="text" class="small required" name="sub_category" value="<?=$sub_source?>" />
		<br /><br />
        <input type="file" name="file" style="height:25px;"/><br />
        Image Icon for Source Type - Image should be 135x150.<br /><br />To update an image, browse for file and update.<br /><br />
		<br />
		<img src="<?=$source_image?>">
	</div>
	<div class="line button">
		<button type="submit" class="green"><span>Sumbit</span></button>
	</div>
</form>
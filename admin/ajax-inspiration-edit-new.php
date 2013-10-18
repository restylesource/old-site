<?

	include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/db.php");
	
	$result = category_lookup($_GET['category_id']);
	if($result){
		$row = @mysql_fetch_array($result);	
		$category = $row['category'];
		$description = $row['description'];
	}

?>
<form action="" method="post">
	<input type="hidden" name="category_id" value="<?=$_GET['category_id']?>" >
	<div class="line">
		<label>Inspiration Name</label>
		<input type="text" class="small required" name="category" value="<?=$category?>" />
	</div>
	<div class="line">
		<label>Description</label>
		<textarea class="medium" rows="" name="description" cols=""><?=$description?></textarea>
	</div>
	<div class="line button"><button type="submit" class="green"><span>Sumbit</span></button> <button type="button" class="blue" onclick="top.location='inspiration-manager-new.php?category_id=<?=$_GET['category_id']?>';"><span>Edit Sub-Inspiration</span></button></div>
</form>
						
<?

	include_once('includes/db.php');
	
	$result = inspiration_lookup($_GET['inspiration_id']);
	
	if($result){
		$row = @mysql_fetch_array($result);	
		$inspiration = $row['inspiration'];
	}

?>
<form action="" method="post">
	<input type="hidden" name="inspiration_id" value="<?=$_GET['inspiration_id']?>">
	<div class="line">
		<label>Inspiration</label>
		<input type="text" class="small required" name="inspiration" value="<?=$inspiration?>" />
	</div>
	<div class="line button">
		<button type="submit" class="green"><span>Update</span></button>
		<button type="button" class="blue" onclick="top.location='inspiration-manager-new.php?inspiration_id=<?=$_GET['inspiration_id']?>';" style="opacity: 1;"><span>Edit Sub-Inspiration</span></button>
	</div>
</form>
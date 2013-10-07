<?

	include_once('includes/db.php');
	
	$result = color_lookup($_GET['color_id']);
	
	if($result){
		$row = @mysql_fetch_array($result);	
		$color = $row['color'];
	}

?>
<form action="" method="post">
	<input type="hidden" name="color_id" value="<?=$_GET['color_id']?>">
	<div class="line">
		<label>Color Name</label>
		<input type="text" class="small required" name="color" value="<?=$color?>" />
	</div>
	<div class="line button">
		<button type="submit" class="green"><span>Update</span></button>
	</div>
</form>
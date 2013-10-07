<?

	include_once('includes/db.php');
	
	$result = style_lookup($_GET['style_id']);
	
	if($result){
		$row = @mysql_fetch_array($result);	
		$style = $row['style'];
	}

?>
<form action="" method="post">
	<input type="hidden" name="style_id" value="<?=$_GET['style_id']?>">
	<div class="line">
		<label>Style Name</label>
		<input type="text" class="small required" name="style" value="<?=$style?>" />
	</div>
	<div class="line button">
		<button type="submit" class="green"><span>Update</span></button>
	</div>
</form>
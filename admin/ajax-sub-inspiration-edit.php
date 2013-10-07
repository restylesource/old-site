<?

	include_once('includes/db.php');
	
	$result = sub_inspiration_lookup($_GET['inspiration_id'], $_GET['sub_inspiration_id']);
	if($result){
		$row = @mysql_fetch_array($result);	
		$sub_inspiration = $row['sub_inspiration'];
	}

?>
<form action="" method="post">
	<input type="hidden" name="inspiration_id" value="<?=$_GET['category_id']?>" >
	<input type="hidden" name="sub_inspiration_id" value="<?=$_GET['sub_inspiration_id']?>" >
	<div class="line">
		<label>Category Name</label>
		<input type="text" class="small required" name="sub_inspiration" value="<?=$sub_inspiration?>" />
	</div>
	<div class="line button">
		<button type="submit" class="green"><span>Sumbit</span></button>
	</div>
</form>
<?

	include_once('includes/db.php');
	
	$result = sub_category_lookup($_GET['category_id'], $_GET['sub_category_id']);
	if($result){
		$row = @mysql_fetch_array($result);	
		$sub_category = $row['sub_category'];
	}

?>
<form action="" method="post">
	<input type="hidden" name="category_id" value="<?=$_GET['category_id']?>" >
	<input type="hidden" name="sub_category_id" value="<?=$_GET['sub_category_id']?>" >
	<div class="line">
		<label>Category Name</label>
		<input type="text" class="small required" name="sub_category" value="<?=$sub_category?>" />
	</div>
	<div class="line button">
		<button type="submit" class="green"><span>Sumbit</span></button>
	</div>
</form>
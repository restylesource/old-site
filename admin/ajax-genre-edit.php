<?

	include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/db.php");
	
	$result = genre_lookup($_GET['genre_id']);
	
	if($result){
		$row = @mysql_fetch_array($result);	
		$genre = $row['genre'];
	}

?>
<form action="" method="post">
	<input type="hidden" name="genre_id" value="<?=$_GET['genre_id']?>">
	<div class="line">
		<label>Genre Name</label>
		<input type="text" class="small required" name="genre" value="<?=$genre?>" />
	</div>
	<div class="line button">
		<button type="submit" class="green"><span>Update</span></button>
	</div>
</form>
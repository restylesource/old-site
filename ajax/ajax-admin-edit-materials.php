<?
	
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';	

	$result = page_blocks_lookup($_GET['id'], $_GET['page_block_id']);
	
	if($result){
		$row = mysql_fetch_array($result);
		$materials1 = $row['materials1'];
		$materials2 = $row['materials2'];
		$materials3 = $row['materials3'];
		$materials4 = $row['materials4'];
		$materials5 = $row['materials5'];
		$materials6 = $row['materials6'];
		$materials7 = $row['materials7'];
		$materials8 = $row['materials8'];
		$materials9 = $row['materials9'];
		$materials10 = $row['materials10'];
	}

?>

<h1>Edit Materials</h1>

<form id="admin-text" method="post">
	<input type="hidden" name="id" value="<?=$_GET['id']?>" />
	<input type="hidden" name="page_block_id" value="<?=$_GET['page_block_id']?>" />
	<input type="hidden" id="action" name="action" />
	<input type="text" name="materials1" value="<?=$materials1?>" placeholder="Material..." autofocus />
	<input type="text" name="materials2" value="<?=$materials2?>" placeholder="Material..." />
	<input type="text" name="materials3" value="<?=$materials3?>" placeholder="Material..." />
	<input type="text" name="materials4" value="<?=$materials4?>" placeholder="Material..." />
	<input type="text" name="materials5" value="<?=$materials5?>" placeholder="Material..." />
	<input type="text" name="materials6" value="<?=$materials6?>" placeholder="Material..." />
	<input type="text" name="materials7" value="<?=$materials7?>" placeholder="Material..." />
	<input type="text" name="materials8" value="<?=$materials8?>" placeholder="Material..." />
	<input type="text" name="materials9" value="<?=$materials9?>" placeholder="Material..." />
	<input type="text" name="materials10" value="<?=$materials10?>" placeholder="Material..." />
</form>

<p class="error"><?=$error?></p>
<?

	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';		

	$result = page_blocks_lookup($_GET['id'], $_GET['page_block_id']);
	
	if($result){
		$row = mysql_fetch_array($result);
		$content = $row['content'];
	}

?>
<h1>Edit Video</h1>

<form id="admin-text" method="post">
	<input type="hidden" name="id" value="<?=$_GET['id']?>" />
	<input type="hidden" name="page_block_id" value="<?=$_GET['page_block_id']?>" />
	<input type="hidden" id="action" name="action" />
	<input type="text" name="content" value="<?=$content?>" placeholder="Vimeo ID" autofocus />
</form>

<p class="error"><?=$error?></p>
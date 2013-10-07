<?

	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';	

	$result = page_blocks_lookup($_GET['id'], $_GET['page_block_id']);
	
	if($result){
		$row = mysql_fetch_array($result);
		$link1_title = $row['link1_title'];
		$link1_url = $row['link1_url'];
		$link2_title = $row['link2_title'];
		$link2_url = $row['link2_url'];
		$link3_title = $row['link3_title'];
		$link3_url = $row['link3_url'];
		$link4_title = $row['link4_title'];
		$link4_url = $row['link4_url'];
		$link5_title = $row['link5_title'];
		$link5_url = $row['link5_url'];
		$link6_title = $row['link6_title'];
		$link6_url = $row['link6_url'];
		$link7_title = $row['link7_title'];
		$link7_url = $row['link7_url'];
		$link8_title = $row['link8_title'];
		$link8_url = $row['link8_url'];
		$credit = $row['credit'];
		$alt = $row['alt'];
	}

?>
<h1>Edit Trends</h1>

<form id="admin-text" enctype="multipart/form-data" action="" method="POST">
	<input type="hidden" name="id" value="<?=$_GET['id']?>" />
	<input type="hidden" name="page_block_id" value="<?=$_GET['page_block_id']?>" />
	<input type="hidden" id="action" name="action" />
	<div class="row">
	<p>Image (208px x 153px):</p>
	<input type="file" id="file" name="image" />
	<input type="text" placeholder="Alt Text" name="alt" value="<?=$alt?>"/>
	<input type="text" placeholder="Photo Credit" name="credit" value="<?=$credit?>" autofocus />
	<br /><br />
	</div>
	<div class="row">
		<div class="column">
			<h3>Trends Like This</h3>
			<p>Link 1</p>
			<input type="text" placeholder="Title" name="link1_title" value="<?=$link1_title?>" autofocus />
			<input type="text" placeholder="URL" name="link1_url" value="<?=$link1_url?>" />
			<p>Link 2</p>
			<input type="text" placeholder="Title" name="link2_title" value="<?=$link2_title?>" autofocus />
			<input type="text" placeholder="URL" name="link2_url" value="<?=$link2_url?>" />
			<p>Link 3</p>
			<input type="text" placeholder="Title" name="link3_title" value="<?=$link3_title?>" autofocus />
			<input type="text" placeholder="URL" name="link3_url" value="<?=$link3_url?>" />
			<p>Link 4</p>
			<input type="text" placeholder="Title" name="link4_title" value="<?=$link4_title?>" autofocus />
			<input type="text" placeholder="URL" name="link4_url" value="<?=$link4_url?>" />
		</div> <!-- // .column -->
		<div class="column">
			<h3>Additional Resources</h3>
			<p>Link 1</p>
			<input type="text" placeholder="Title" name="link5_title" value="<?=$link5_title?>" autofocus />
			<input type="text" placeholder="URL" name="link5_url" value="<?=$link5_url?>" />
			<p>Link 2</p>
			<input type="text" placeholder="Title" name="link6_title" value="<?=$link6_title?>" autofocus />
			<input type="text" placeholder="URL" name="link6_url" value="<?=$link6_url?>" />
			<p>Link 3</p>
			<input type="text" placeholder="Title" name="link7_title" value="<?=$link7_title?>" autofocus />
			<input type="text" placeholder="URL" name="link7_url" value="<?=$link7_url?>" />
			<p>Link 4</p>
			<input type="text" placeholder="Title" name="link8_title" value="<?=$link8_title?>" autofocus />
			<input type="text" placeholder="URL" name="link8_url" value="<?=$link8_url?>" />
		</div> <!-- // .column -->
	</div> <!-- // .row -->

</form>

<p class="error"><?=$error?></p>

<script src="/assets/js/fileinput/jQuery.fileinput.js"></script>
<script>
$(document).ready(function() {
	$('#file').customFileInput();
});
</script>
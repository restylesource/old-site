<?

	include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/db.php");
	
	$result = page_lookup($_GET['page_id']);
	
	if($result){
		$row = @mysql_fetch_array($result);	
		$page_title = $row['page_title'];
		$page_sub_title = $row['page_sub_title'];
		$inspriation_id = $row['inspiration_id'];
		$page_status = $row['page_status'];
	}

	$result = inspiration_search();
	$inspiration_options = build_generic_dropdown($result, $inspriation_id, 1);

?>
<form action="" method="post" enctype="multipart/form-data">
	<input type="hidden" name="page_id" value="<?=$_GET['page_id']?>">
	<div class="line">
		<label>Page Title</label>
		<input type="text" class="small required" name="page_title" value="<?=$page_title?>" />
	</div>
	<div class="line">
		<label>Sub Title</label>
		<input type="text" class="small required" name="page_sub_title" value="<?=$page_sub_title?>" />
	</div>
	<div class="line">
		<label>Inspiration:</label>
		<select name="inspiration_id">
			<?=$inspiration_options?>
		</select>
	</div>
	<div class="line">
		<label>Background</label>
		<input type="file" name="bg_image"/>
	</div>
	<div class="line">
		<label>Status:</label>
		<select name="page_status">
			<option value="active" <? if($page_status=="active") echo("selected");?>>active</option>
			<option value="inactive" <? if($page_status=="inactive") echo("selected");?>>inactive</option>
		</select>
	</div>
	<div class="line button">
		<button type="submit" class="green"><span>Update</span></button>
	</div>
</form>


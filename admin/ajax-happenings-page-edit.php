<?

	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';	
	
	$result = happening_lookup($_GET['happening_id']);
	
	if($result){
		$row = @mysql_fetch_array($result);	
		$title = $row['title'];
		$city = $row['city'];
		$state = $row['state'];
		$start_date = date("m/d/Y H:i", strtotime($row['start_date']));
		$end_date = date("m/d/Y H:i", strtotime($row['end_date']));
		$recurring = $row['recurring'];
		$recurring_end_date = ($row['recurring_end_date'] != "0000-00-00") ? date("m/d/Y", strtotime($row['recurring_end_date'])) : "";
		$description = $row['description'];
		$url = $row['url'];
		$status = $row['status'];

	}

	$state_result = state_search();
	$state_options = build_generic_dropdown($state_result, $state, 0);

?>
<form action="" method="post" enctype="multipart/form-data">
	<input type="hidden" name="happening_id" value="<?=$_GET['happening_id']?>">
	<div class="line">
		<label>Event Title</label>
		<input type="text" class="small required" name="title" value="<?=$title?>" />
	</div>
	<div class="line">
		<label>City</label>
		<input type="text" class="small required" name="city" value="<?=$city?>" />
	</div>
	<div class="line">
		<label>State:</label>
		<select name="state">
			<?=$state_options?>
		</select>
	</div>
	<div class="line">
		<label>Start Date</label>
		<span class="date"><input type="text" name="start_date" class="timepicker" value="<?=$start_date?>" /> 
	</div>
	<div class="line">
		<label>End Date</label>
		<span class="date"><input type="text" name="end_date" class="timepicker" value="<?=$end_date?>" /> 
	</div>
	<div class="line">
		<label>Recurring:</label>
		<select name="recurring">
			<option value="">Select</option>
			<option value="daily" <?if($recurring=="daily") echo("selected") ?>>Daily</option>
			<option value="weekly" <?if($recurring=="weekly") echo("selected") ?>>Weekly</option>
			<option value="bi-weekly" <?if($recurring=="bi-weekly") echo("selected") ?>>Bi-Weekly</option>
			<option value="monthly" <?if($recurring=="monthly") echo("selected") ?>>Monthly</option>
			<option value="bi-monthly" <?if($recurring=="bi-monthly") echo("selected") ?>>Bi-Monthly</option>
			<option value="annually" <?if($recurring=="annually") echo("selected") ?>>Annually</option>
		</select>
	</div>
	<div class="line">
		<label>End Recurring Date</label>
		<span class="date"><input type="text" name="recurring_end_date" class="datepicker" value="<?=$recurring_end_date?>" /> 
	</div>
	<div class="line">
		<label>Event URL</label>
		<input type="text" class="small required" name="url" value="<?=$url?>" />
	</div>
	<div class="line">
		<label>Event Description</label>
		<textarea class="medium" rows="40" name="description" cols=""><?=$description?></textarea>
	</div>
	<div class="line">
		<label>Status:</label>
		<select name="status">
			<option value="active" <? if($status=="active") echo("selected");?>>active</option>
			<option value="inactive" <? if($status=="inactive") echo("selected");?>>inactive</option>
		</select>
	</div>
	<div class="line button">
		<button type="submit" class="green"><span>Update</span></button>
	</div>
</form>


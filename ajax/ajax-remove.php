<?

	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';	
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/logincheck.php';
	
	list($first_name, $last_name) = explode(" ", $g_sess->get_var("name"));

	if($_GET['id']){
		$result = user_style_file_lookup($g_sess->get_var("user"), $_GET['id']);
		if($result){
			$row = mysql_fetch_array($result);
			if($row['inspiration_id']){
				//$description = inspiration_name($row['inspiration_id']);
				$description = $row['page_title'];
			} else if ($row['company']){
				$description = $row['company'];
			} else if ($row['product']){
				$description = $row['product'];
			}
		}
	}

?>
<input type="hidden" id="style_remove_id" name="style_remove_id" value="<?=$_GET['id']?>" />
<h1><?=$first_name?>,</h1>
<h2>are you sure you want to remove <strong><em><?=$description?></em></strong> from your Style File?</h2>
<div class="dialog-content">
</div>
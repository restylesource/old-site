<?

	$require_login = 1;
	$allowed_groups = array("admin");

	include_once('includes/db.php');	
	include_once('includes/logincheck.php');
	
	if($_POST || $_GET['action']){
	
		if($_POST['source_delete']){
			foreach($_POST['source_delete'] as $source_id){
				sources_delete($source_id);
			}
		} else if ($_GET['action'] == "delete" && $_GET['source_id'] > 0 ){	
			sources_delete($_GET['source_id']);	
		} else if($_POST['source_id'] && $_POST['source']){
			$source_id = sources_add($_POST['source_id'], $_POST['source']);
		} else if($_POST['source']){
			foreach($_POST['source'] as $source){
				$source_id = sources_add(0, $source);
			}
		}
		
		if($_FILES){
			$ext = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
			if(move_uploaded_file($_FILES["file"]["tmp_name"], "source_images/" . $source_id . "." . $ext)){
				sources_image($source_id, $source_id . "." . $ext);
			}
		}
		
		my_redirect($_SERVER['PHP_SELF']);
	}
	
	$source_result = sources_search();
	
	$source_array = array();
	$i = 0;
	
	if($source_result){
	
		while($row = @mysql_fetch_array($source_result)){
			$source_output.= '<tr> 
								<td>' . $row['source_id'] . '</td>
								<td>' . $row['source'] . '</td>
								<td style="width:45px">
									<a rel="Edit Source Type" href="ajax-source-type-edit.php?source_id=' . $row['source_id'] . '" class="modalopen"><img src="gfx/icon-edit.png" alt="edit" /></a>
									<a href="' . $_SERVER['PHP_SELF'] . '?action=delete&source_id=' . $row['source_id'] . '" onclick="return confirm(\'Are you sure you want to Delete?\');"><img src="gfx/icon-delete.png" alt="delete" /></a>
								</td>
							</tr>';
	
			$source_array[$i] = array();
			$source_array[$i]['id'] = $row['source_id'];
			$source_array[$i]['name'] = $row['source'];
	
			$sub_array = array();
			// lookup sub cats for cat
			$sub_source_result = sub_source_lookup($row['source_id']);
			$j = 0;
			while($sub_row = @mysql_fetch_array($sub_source_result)){
				$sub_array[$j]['id'] = $sub_row['sub_source_id'];
				$sub_array[$j]['name'] = $sub_row['sub_source'];
				$j++;
			}
		
			$source_array[$i]['sub_source'] = $sub_array;
				
			$i++;
		}
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" /> 
	<title>ReStyle | Source Types</title>

	<?php include("includes/css.php"); ?>
	<!--[if lte IE 8]>
		<script type="text/javascript" src="js/excanvas.min.js"></script>
	<![endif]-->
</head>
<body>
<div class="container">   
	<?php include("includes/header.php"); ?>
	<?php include("includes/nav.php"); ?>
	<div class="breadcrumbs">
		<ul>
			<li class="home"><a href="/admin"></a></li>
			<li class="break">&#187;</li>
			<li><a href="style-manager.php">Manage Source Types</a></li>
		</ul>
	</div>
	<div class="section">
		<div class="full">
			<div class="box">
				<div class="title">
					<h2>Add A Source Type</h2>
				</div>
				<div class="content forms">
					<form id="style-edit" action="" method="post" enctype="multipart/form-data">
						<div class="line">
							<label>Source Type</label>
							<div>
							<input type="text" class="small complete required" name="source[]" value="" />
							</div>
							<!--<a class="add" onclick="Repeat(this);"></a>
                            <a class="delete" onclick="Delete(this);"></a>-->
                            <br /><br />
                            <input type="file" name="file" style="height:25px;"/><br />
               				Image Icon for Source Type - Image should be 135x150.<br /><br />
						</div>
						<div class="line button">
							<button type="submit" class="green"><span>Add</span></button>
						</div>
					</form>
				</div>
			</div>
		</div>		
	</div>
	<div class="section">
		<div class="full">
			<div class="box">
				<div class="title">
					<h2>Source Types</h2>
				</div>
				<div class="content">
					<form method="POST">
					<input type="hidden" id="action" name="action" value="delete">
					<table cellspacing="0" cellpadding="0" border="0" class="all"> 
						<thead> 
							<tr> 
								<th>ID</th>
								<th>Source Type</th>
								<th>Action</th>
							</tr> 
						</thead> 
						<tbody> 
							<?=$source_output?>
						</tbody> 
					</table>
					<button type="submit" class="red"><span>Delete</span></button>
					</form>
				</div>
            </div>
        </div>
    </div>
    <!-- begin edit modals-->
	<div class="modal forms" title="Edit Style">
		
	</div>
	<?php include("includes/footer.php"); ?>
</div>

	<?php include("includes/js.php"); ?>
	<script type="text/javascript" src="js/jquery.MultiFile.js"></script>
	<script type="text/javascript">var source_types = <?php print json_encode($source_array);?>;</script>
<script type="text/javascript">
	$('#style-edit').validate();
</script>
</body>
</html> 
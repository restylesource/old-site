<?

	$require_login = 1;
	$allowed_groups = array("admin");

	include_once('includes/db.php');	
	include_once('includes/logincheck.php');

	if($_POST || $_GET['action']){

		if ($_GET['action'] == "delete" && $_GET['sub_source_id']){
			sub_source_delete($_GET['sub_source_id']);
		} else if($_POST['action'] == "delete"){
			foreach($_POST['sub_source_delete'] as $sub_source_id){
				sub_source_delete($sub_source_id);
			}
		} else if($_POST && $_POST['source']){
			$source_id = sources_add($_POST['source_id'], $_POST['source']);
	
			if($_POST['sub_source']){
				foreach($_POST['sub_source'] as $sub_source){
					$sub_source_id = sub_source_add($sub_source_id, $source_id, $sub_source);
					
					if($_FILES){
						$ext = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
						if(move_uploaded_file($_FILES["file"]["tmp_name"], "source_images/" . $source_id . "_" . $sub_source_id . "." . $ext)){
							sub_sources_image($sub_source_id, $source_id . "_" . $sub_source_id . "." . $ext);
						}
					}
					
				}
			}
		} else if ($_POST['sub_source'] && $_POST['sub_source_id'] && $_POST['source_id']){	
			sub_source_add($_POST['sub_source_id'], $_POST['source_id'], $_POST['sub_source']);
		}
		
		my_redirect('/admin/source-types.php');	
		
	} else if ($_GET['source_id'] > 0 && int_ok($_GET['source_id'])){
		$result = sources_lookup($_GET['source_id']);
		if($result){
			$row = @mysql_fetch_array($result);
			$source = $row['source'];

			//Lookup Sub Categories for source
			$sub_cat_result = sub_source_lookup($_GET['source_id'], 0);

			if($sub_cat_result){
				while($sub_cat_row = @mysql_fetch_array($sub_cat_result)){
					$sub_cat_output .= '<tr> 
														<td><input type="checkbox" name="sub_source_delete[]" value="' . $sub_cat_row['sub_source_id'] . '"  /></td>
														<td>' . $sub_cat_row['sub_source'] . '</td>
														<td style="width:45px">
															<a rel="Edit Sub source" href="ajax-sub-source-edit.php?source_id=' . $_GET['source_id'] . '&sub_source_id=' . $sub_cat_row['sub_source_id'] . '" class="modalopen"><img src="gfx/icon-edit.png" alt="edit" /></a>
															<a href="' . $PHP_SELF . '?action=delete&sub_source_id=' . $sub_cat_row['sub_source_id'] . '&source_id=' . $_GET['source_id'] . '" onclick="return confirm(\'Are you sure you want to Delete?\')"><img src="gfx/icon-delete.png" alt="delete" /></a>
														</td>
													</tr>';
					
				}
			}
		}

	}

	$form_action = ($_REQUEST['source_id']) ? "Edit" : "Add";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" /> 
	<title>Restyle | <?=$form_action?> A source</title>

	<?php include("includes/css.php"); ?>
</head>
<body>
<div class="container">
	<?php include("includes/header.php"); ?>	
	<?php include("includes/nav.php"); ?>	
	<div class="breadcrumbs">
		<ul>
			<li class="home"><a href="/admin"></a></li>
			<li class="break">&#187;</li>
			<li><a href="categories.php">Source Manager</a></li>
		</ul>
	</div>	
	<div class="section">
		<div class="full">
			<div class="box">
				<div class="title">
					<h2><?=$form_action?> a Source</h2>

				</div>
				<div class="content forms">
					<form id="source-edit" action="" method="post" enctype="multipart/form-data">
						<input type="hidden" name="source_id" value="<?=$_GET['source_id']?>">
						<div class="line">
							<label>Source Name</label>
							<input type="text" class="small complete required" name="source" value="<?=$source?>" />
						</div>
						<div class="line">
							<label>Sub Source</label>
							<div>
							<input type="text" class="small complete" name="sub_source[]" value="" />
							</div>
							<br /><br />
                            <input type="file" name="file" style="height:25px;"/><br />
               				Image Icon for Source Type - Image should be 135x150.<br /><br />
						</div>
						<div class="line button">
                       		<button onclick="top.location='/admin/index.php';" class="red"><span>cancel</span></button>
							<button type="submit" class="green"><span><?if($_REQUEST['source_id']){?>Edit This Source<?} else {?>Add Source<?}?></span></button>
							<?if($form_action=="Edit"){?>
							<a href="<?=$_SERVER['PHP_SELF']?>" class="button blue">Create a New Source</a>
							<?}?>
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
					<h2>Sub-Sources</h2>
				</div>
				<div class="content">
					<form action="" method="post">
					<input type="hidden" name="source_id" value="<?=$_GET['source_id']?>">
					<input type="hidden" name="action" id="action" value="">
					<table cellspacing="0" cellpadding="0" border="0" class="all"> 
						<thead> 
							<tr> 
								<th><input type="checkbox" name="check" class="checkall" /></th>
								<th>Sub Source Name</th>
								<th>Action</th>
							</tr> 
						</thead> 
						<tbody> 
							<?=$sub_cat_output?>
						</tbody> 
					</table>
					<button type="submit" class="red" onclick="$('#action').val('delete');"><span>Delete</span></button>
					</form>
				</div>
                <!-- end content area-->
                <!-- begin edit modals-->
				<div class="modal forms" title="edit Sub-source">
						
				</div>				
			</div>
		</div>
	</div>
	<?php include("includes/footer.php"); ?>
</div>
<!-- begin help modals-->
	<div id="help1" style="display:none">
        <h2>Source Manager</h2>   
	</div>
	
	<?php include("includes/js.php"); ?>
	<script type="text/javascript" src="js/jquery.MultiFile.js"></script>
	
	<script type="text/javascript">
   
    // SET UP FORM VALIDATION
	$('#source-edit-new').validate();
	
    </script>
</body>
</html> 
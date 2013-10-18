<?

	$require_login = 1;
	$allowed_groups = array("admin");

	include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/db.php");	
	include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/logincheck.php");

	if($_POST || $_GET['action']){


		if ($_GET['action'] == "delete" && $_GET['sub_inspiration_id']){
			sub_inspiration_delete($_GET['sub_inspiration_id']);
		} else if($_POST['action'] == "delete"){
			foreach($_POST['sub_inspiration_delete'] as $sub_inspiration_id){
				sub_inspiration_delete($sub_inspiration_id);
			}			
		} else if ($_POST['sub_inspiration'] && $_POST['inspiration_id']){			
			sub_inspiration_add($_POST['sub_inspiration_id'], $_POST['inspiration_id'], $_POST['sub_inspiration']);
		}
		
		my_redirect('/admin/inspiration-manager.php');	
		
	} else if ($_GET['inspiration_id'] > 0 && int_ok($_GET['inspiration_id'])){
		$result = inspiration_lookup($_GET['inspiration_id']);
		if($result){
			$row = @mysql_fetch_array($result);
			$inspiration = $row['inspiration'];
			$description = $row['description'];

			//Lookup Sub Categories for inspiration
			$sub_inspiration_result = sub_inspiration_lookup($_GET['inspiration_id'], 0);

			if($sub_inspiration_result){
				while($sub_inspiration_row = @mysql_fetch_array($sub_inspiration_result)){
					$sub_inspiration_output .= '<tr> 
														<td><input type="checkbox" name="sub_inspiration_delete[]" value="' . $sub_inspiration_row['sub_inspiration_id'] . '"  /></td>
														<td>' . $sub_inspiration_row['sub_inspiration'] . '</td>
														<td style="width:45px">
															<a rel="Edit Sub Inspiration" href="ajax-sub-inspiration-edit.php?inspiration_id=' . $_GET['inspiration_id'] . '&sub_inspiration_id=' . $sub_inspiration_row['sub_inspiration_id'] . '" class="modalopen"><img src="gfx/icon-edit.png" alt="edit" /></a>
															<a href="' . $PHP_SELF . '?action=delete&sub_inspiration_id=' . $sub_inspiration_row['sub_inspiration_id'] . '&inspiration_id=' . $_GET['inspiration_id'] . '" onclick="return confirm(\'Are you sure you want to Delete?\')"><img src="gfx/icon-delete.png" alt="delete" /></a>
														</td>
													</tr>';
					
				}
			}
		}

	}

	$form_action = ($_REQUEST['inspiration_id']) ? "Edit" : "Add";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" /> 
	<title>Restyle | <?=$form_action?> An Inspiration</title>

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
			<li><a href="categories.php">Inspiration Manager</a></li>
		</ul>
	</div>	
	<div class="section">
		<div class="full">
			<div class="box">
				<div class="title">
					<h2><?=$form_action?> an Inspiration</h2>

				</div>
				<div class="content forms">
					<form id="inspiration-edit" action="" method="post">
						<input type="hidden" name="inspiration_id" value="<?=$_GET['inspiration_id']?>">
						<div class="line">
							<label>Inspirtion Name</label>
							<input type="text" class="small complete required" name="inspiration" value="<?=$inspiration?>" />
						</div>
						<div class="line">
							<label>Sub Inspiration</label>
							<div>
							<input type="text" class="small complete" name="sub_inspiration" value="" />
							</div>
							<!--
							<a class="add" onclick="Repeat(this);"></a>
                            <a class="delete" onclick="Delete(this);"></a>
                            -->
						</div>
						<div class="line button">
                       		<button onclick="top.location='/admin/index.php';" class="red"><span>cancel</span></button>
							<button type="submit" class="green"><span><?if($_REQUEST['inspiration_id']){?>Edit This Inspiration<?} else {?>Add Inspiration<?}?></span></button>
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
					<h2>Sub-Inspirations</h2>
				</div>
				<div class="content">
					<form action="" method="post">
					<input type="hidden" name="inspiration_id" value="<?=$_GET['inspiration_id']?>">
					<input type="hidden" name="action" id="action" value="">
					<table cellspacing="0" cellpadding="0" border="0" class="all"> 
						<thead> 
							<tr> 
								<th><input type="checkbox" name="check" class="checkall" /></th>
								<th>Sub Inspiration Name</th>
								<th>Action</th>
							</tr> 
						</thead> 
						<tbody> 
							<?=$sub_inspiration_output?>
						</tbody> 
					</table>
					<button type="submit" class="red" onclick="$('#action').val('delete');"><span>Delete</span></button>
					</form>
				</div>
                <!-- end content area-->
                <!-- begin edit modals-->
				<div class="modal forms" title="edit Sub-inspiration">
						
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
	$('#inspiration-edit-new').validate();
	
    </script>
</body>
</html> 
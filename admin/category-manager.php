<?

	$require_login = 1;
	$allowed_groups = array("admin");

	include_once('includes/db.php');	
	include_once('includes/logincheck.php');

	if($_POST || $_GET['action']){

		if ($_GET['action'] == "delete" && $_GET['sub_category_id']){
			sub_category_delete($_GET['sub_category_id']);
		} else if($_POST['action'] == "delete"){
			foreach($_POST['sub_category_delete'] as $sub_category_id){
				sub_category_delete($sub_category_id);
			}
		} else if($_POST && $_POST['category']){
			$category_id = category_add($_POST['category_id'], $_POST['category'], $_POST['description']);
	
			if($_POST['sub_category']){
				foreach($_POST['sub_category'] as $sub_category){
					sub_category_add($sub_category_id, $category_id, $sub_category);
				}
			}
		} else if ($_POST['sub_category'] && $_POST['sub_category_id'] && $_POST['category_id']){	
			sub_category_add($_POST['sub_category_id'], $_POST['category_id'], $_POST['sub_category']);
		}
		
		my_redirect('/admin/categories.php');	
		
	} else if ($_GET['category_id'] > 0 && int_ok($_GET['category_id'])){
		$result = category_lookup($_GET['category_id']);
		if($result){
			$row = @mysql_fetch_array($result);
			$category = $row['category'];
			$description = $row['description'];

			//Lookup Sub Categories for Category
			$sub_cat_result = sub_category_lookup($_GET['category_id'], 0);

			if($sub_cat_result){
				while($sub_cat_row = @mysql_fetch_array($sub_cat_result)){
					$sub_cat_output .= '<tr> 
														<td><input type="checkbox" name="sub_category_delete[]" value="' . $sub_cat_row['sub_category_id'] . '"  /></td>
														<td>' . $sub_cat_row['sub_category'] . '</td>
														<td style="width:45px">
															<a rel="Edit Sub Category" href="ajax-sub-category-edit.php?category_id=' . $_GET['category_id'] . '&sub_category_id=' . $sub_cat_row['sub_category_id'] . '" class="modalopen"><img src="gfx/icon-edit.png" alt="edit" /></a>
															<a href="' . $PHP_SELF . '?action=delete&sub_category_id=' . $sub_cat_row['sub_category_id'] . '&category_id=' . $_GET['category_id'] . '" onclick="return confirm(\'Are you sure you want to Delete?\')"><img src="gfx/icon-delete.png" alt="delete" /></a>
														</td>
													</tr>';
					
				}
			}
		}

	}

	$form_action = ($_REQUEST['category_id']) ? "Edit" : "Add";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" /> 
	<title>Restyle | <?=$form_action?> A Category</title>

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
			<li><a href="categories.php">Category Manager</a></li>
		</ul>
	</div>	
	<div class="section">
		<div class="full">
			<div class="box">
				<div class="title">
					<h2><?=$form_action?> a Category</h2>

				</div>
				<div class="content forms">
					<form id="category-edit" action="" method="post">
						<input type="hidden" name="category_id" value="<?=$_GET['category_id']?>">
						<div class="line">
							<label>Category Name</label>
							<input type="text" class="small complete required" name="category" value="<?=$category?>" />
						</div>
						<div class="line">
							<label>Category<br />Description</label>
							<textarea class="big" rows="" cols="" name="description"><?=$description?></textarea>
						</div>
						<div class="line">
							<label>Sub Category</label>
							<div>
							<input type="text" class="small complete" name="sub_category[]" value="" />
							</div>
							<a class="add" onclick="Repeat(this);"></a>
                            <a class="delete" onclick="Delete(this);"></a>
						</div>
						<div class="line button">
                       		<button onclick="top.location='/admin/index.php';" class="red"><span>cancel</span></button>
							<button type="submit" class="green"><span><?if($_REQUEST['category_id']){?>Edit This Category<?} else {?>Add Category<?}?></span></button>
							<?if($form_action=="Edit"){?>
							<a href="<?=$_SERVER['PHP_SELF']?>" class="button blue">Create a New Category</a>
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
					<h2>Sub-Categories &#187; Category Name</h2>
				</div>
				<div class="content">
					<form action="" method="post">
					<input type="hidden" name="category_id" value="<?=$_GET['category_id']?>">
					<input type="hidden" name="action" id="action" value="">
					<table cellspacing="0" cellpadding="0" border="0" class="all"> 
						<thead> 
							<tr> 
								<th><input type="checkbox" name="check" class="checkall" /></th>
								<th>Sub Category Name</th>
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
				<div class="modal forms" title="edit Sub-Category">
						
				</div>				
			</div>
		</div>
	</div>
	<?php include("includes/footer.php"); ?>
</div>
<!-- begin help modals-->
	<div id="help1" style="display:none">
        <h2>Category Manager</h2>
        <p>It's important to manage categories in the database because 
        users are only allowed to pick from a defined list of categories. 
        This makes sure that everything is search optimized and no duplicate categories exist.
        <br />
        <br />
        Put in a category name like Dining Room and then enter a sub-category like Table. You can add mulitple sub-categories so Dining Room may also have Chairs.         
        </p>    
	</div>
	
	<?php include("includes/js.php"); ?>
	<script type="text/javascript" src="js/jquery.MultiFile.js"></script>
	
	<script type="text/javascript">
   
    // SET UP FORM VALIDATION
	$('#category-edit').validate();
	
    </script>
</body>
</html> 
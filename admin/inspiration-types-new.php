<?

	$require_login = 1;
	$allowed_groups = array("admin");

	include_once('includes/db.php');	
	include_once('includes/logincheck.php');

	if($_GET['action'] == "delete" && $_GET['category_id'] > 0){
		category_delete($_GET['category_id']);
		my_redirect($_SERVER['PHP_SELF']);
	} else if($_POST['action'] == "delete"){
		foreach($_POST['category_delete'] as $category_id){
			category_delete($category_id);
		}
		my_redirect('admin/inspiration-types-new.php');
	} else if($_POST){
		$category_id = category_add($_POST['category_id'], $_POST['category'], $_POST['description']);
		my_redirect('admin/inspiration-types-new.php');
	}

	$category_result = category_search();

	if($category_result){
	
		while($row = @mysql_fetch_array($category_result)){
			$category_output.= '<tr> 
									<td><input type="checkbox" name="category_delete[]" value="' . $row['category_id'] . '" /></td>
									<td style="width:200px"><a href="inspiration-manager-new.php?category_id=' . $row['category_id'] . '">' . $row['category'] . '</a></td>
									<td>' . $row['description'] . '</td>
									<td>
										<a rel="Edit Inspiration" href="ajax-inspiration-edit-new.php?category_id=' . $row['category_id'] . '" class="modalopen"><img src="gfx/icon-edit.png" alt="edit" /></a>
										<a href="' . $PHP_SELF . '?action=delete&category_id=' . $row['category_id'] . '" onclick="return confirm(\'Are you sure you want to delete?\r\n\r\nWARNING: This cannot be undone.\')"><img src="gfx/icon-delete.png" alt="delete" /></a>
									</td>
								</tr>';
		}
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" /> 
	<title>ReStyle | Inspiration Manager</title>
	
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
			<li><a href="source-types-new.php">Inspiration Manager</a></li>
		</ul>
	</div>
	<!-- begin categories table-->
		<div class="section">
		<div class="full">
			<div class="box">
				<div class="title">
					<h2>Inspiration Listings</h2>
				</div>
				<div class="content">
					<form method="POST">
					<input type="hidden" id="action" name="action" value="">
					<table cellspacing="0" cellpadding="0" border="0" class="all"> 
						<thead> 
							<tr> 
								<th><input type="checkbox" name="check" class="checkall" /></th>
								<th>Source Name</th>
								<th>Description</th>
								<th>Action</th>
							</tr> 
						</thead> 
						<tbody> 
							<?=$category_output?>
						</tbody> 
					</table>
					<button type="submit" class="red" onclick="$('#action').val('delete')"><span>Delete</span></button>
					<a href="inspiration-manager-new.php" class="button green">Create A New Inspiration</a>
					</form>
				</div>
	<!--**************************-->
    <!--begin modals for editing-->
				<div class="modal forms" title="Edit Inspiration">
				<!-- Dynamic Content -->
				</div>
			</div>
		</div>
	</div>
<?php include("includes/footer.php"); ?>
</div>

<?php include("includes/js.php"); ?>
</body>
</html> 
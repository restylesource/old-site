<?

	$require_login = 1;
	$allowed_groups = array("admin");

	include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/db.php");	
	include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/logincheck.php");
	
	if($_POST || $_GET['action']){
	
		if($_POST['color_delete']){
			foreach($_POST['color_delete'] as $color_id){
				color_delete($color_id);
			}
		} else if ($_GET['action'] == "delete" && $_GET['color_id'] > 0 ){	
			color_delete($_GET['color_id']);	
		} else if($_POST['color_id'] && $_POST['color']){
			color_add($_POST['color_id'], $_POST['color']);
		} else if($_POST['color']){
			foreach($_POST['color'] as $color){
				color_add(0, $color);
			}
		}
		my_redirect($_SERVER['PHP_SELF']);
	}
	
	$color_result = color_search();
	
	if($color_result){
	
		while($row = @mysql_fetch_array($color_result)){
			$color_output.= '<tr> 
								<td><input type="checkbox" name="color_delete[]" value="' . $row['color_id'] .'" /></td>
								<td>' . $row['color'] . '</td>
								<td style="width:45px">
									<a rel="Edit Color" href="ajax-color-edit.php?color_id=' . $row['color_id'] . '" class="modalopen"><img src="gfx/icon-edit.png" alt="edit" /></a>
									<a href="' . $_SERVER['PHP_SELF'] . '?action=delete&color_id=' . $row['color_id'] . '" onclick="return confirm(\'Are you sure you want to Delete?\');"><img src="gfx/icon-delete.png" alt="delete" /></a>
								</td>
							</tr>';
		}
	}
	
?>	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" /> 
	<title>ReStyle | Color Manager</title>

	<?php include("includes/css.php"); ?>

	<!--[if lte IE 8]>
		<script type="text/javascript" src="js/excanvas.min.js"></script>
	<![endif]-->
	
	<script>
    /*
    These two scripts control the ability for users to add more categories or colors. You will see the function appends a new div with the select box. This needs to be replaced with PHP that will get the select options form the category or color table
    */
    
    //add remove more form inputs
    function Repeat(obj)
    {
     var currentDiv = $(obj).prev("div");
     currentDiv.clone().insertAfter(currentDiv);
    }
    
    //remove form inputs
    function Delete(obj)
    {
     var currentDiv = $(obj).prev().prev("div");
     currentDiv.remove();
    }
    </script>	
</head>
<body>
	<div class="container">   
	<?php include("includes/header.php"); ?>
	<?php include("includes/nav.php"); ?>
	<div class="breadcrumbs">
		<ul>
			<li class="home"><a href="/admin"></a></li>
			<li class="break">&#187;</li>
			<li><a href="color-manager.php">Color Manager</a></li>
		</ul>
	</div>
	<div class="section">
		<div class="full">
			<div class="box">
				<div class="title">
					<h2>Add A Color</h2>
				</div>
				<div class="content forms">
					<form action="" method="post">
						<div class="line">
							<label>Color Name</label>
							<div>
							<input type="text" class="small complete" name="color[]" value="" />
							</div>
							<a class="add" onclick="Repeat(this);"></a>
                            <a class="delete" onclick="Delete(this);"></a>
						</div>
						<div class="line button">
							<button type="submit" class="green"><span>add</span></button>
						</div>
					</form>
				</div>
			</div>
		</div>		
	</div>
	<!-- begin Color manage area-->
		<div class="section">
		<div class="full">
			<div class="box">
				<div class="title">
					<h2>Manage Colors</h2>
				</div>
				<div class="content">
					<form method="POST">
					<input type="hidden" id="action" name="action" value="delete">
					<table cellspacing="0" cellpadding="0" border="0" class="all"> 
						<thead> 
							<tr> 
								<th><input type="checkbox" name="check" class="checkall" /></th>
								<th>Color Name</th>
								<th>Action</th>
							</tr> 
						</thead> 
						<tbody> 
							<?=$color_output?>
						</tbody> 
					</table>
					<button type="submit" class="red"><span>Delete</span></button>
					</form>
				</div>
	<!-- end color manage area-->
    <!-- begin modals for editing-->
				<div class="modal forms" title="Edit Color">
						
				</div>
			</div>
		</div>
	</div>
	<?php include("includes/footer.php"); ?>
</div>

	<?php include("includes/js.php"); ?>
	<script type="text/javascript" src="js/jquery.MultiFile.js"></script>
</body>
</html> 
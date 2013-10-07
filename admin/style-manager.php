<?

	$require_login = 1;
	$allowed_groups = array("admin");

	include_once('includes/db.php');	
	include_once('includes/logincheck.php');
	
	if($_POST || $_GET['action']){
	
		if($_POST['style_delete']){
			foreach($_POST['style_delete'] as $style_id){
				style_delete($style_id);
			}
		} else if ($_GET['action'] == "delete" && $_GET['style_id'] > 0 ){	
			style_delete($_GET['style_id']);	
		} else if($_POST['style_id'] && $_POST['style']){
			style_add($_POST['style_id'], $_POST['style']);
		} else if($_POST['style']){
			foreach($_POST['style'] as $style){
				style_add(0, $style);
			}
		}
		my_redirect($_SERVER['PHP_SELF']);
	}
	
	$style_result = style_search();
	
	if($style_result){
	
		while($row = @mysql_fetch_array($style_result)){
			$style_output.= '<tr> 
								<td><input type="checkbox" name="style_delete[]" value="' . $row['style_id'] .'" /></td>
								<td>' . $row['style'] . '</td>
								<td style="width:45px">
									<a rel="Edit Style" href="ajax-style-edit.php?style_id=' . $row['style_id'] . '" class="modalopen"><img src="gfx/icon-edit.png" alt="edit" /></a>
									<a href="' . $_SERVER['PHP_SELF'] . '?action=delete&style_id=' . $row['style_id'] . '" onclick="return confirm(\'Are you sure you want to Delete?\');"><img src="gfx/icon-delete.png" alt="delete" /></a>
								</td>
							</tr>';
		}
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" /> 
	<title>ReStyle | Style Manager</title>

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
			<li><a href="style-manager.php">Manage Styles</a></li>
		</ul>
	</div>
	<div class="section">
		<div class="full">
			<div class="box">
				<div class="title">
					<h2>Add A Style</h2>
				</div>
				<div class="content forms">
					<form id="style-edit" action="" method="post">
						<div class="line">
							<label>Style Name</label>
							<div>
							<input type="text" class="small complete required" name="style[]" value="" />
							</div>
							<a class="add" onclick="Repeat(this);"></a>
                            <a class="delete" onclick="Delete(this);"></a>
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
					<h2>Styles</h2>
				</div>
				<div class="content">
					<form method="POST">
					<input type="hidden" id="action" name="action" value="delete">
					<table cellspacing="0" cellpadding="0" border="0" class="all"> 
						<thead> 
							<tr> 
								<th><input type="checkbox" name="check" class="checkall" /></th>
								<th>Style Name</th>
								<th>Action</th>
							</tr> 
						</thead> 
						<tbody> 
							<?=$style_output?>
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

<script type="text/javascript">
	$('#style-edit').validate();
</script>
</body>
</html> 
<?

	$require_login = 1;
	$allowed_groups = array("admin");

	include_once('includes/db.php');	
	include_once('includes/logincheck.php');
	
	if($_POST || $_GET['action']){
	
		if($_POST['inspiration_delete']){
			foreach($_POST['inspiration_delete'] as $inspiration_id){
				inspiration_delete($inspiration_id);
			}
		} else if ($_GET['action'] == "delete" && $_GET['inspiration_id'] > 0 ){	
			inspiration_delete($_GET['inspiration_id']);	
		} else if($_POST['inspiration_id'] && $_POST['inspiration']){
			inspiration_add($_POST['inspiration_id'], $_POST['inspiration']);
		} else if($_POST['inspiration']){
			foreach($_POST['inspiration'] as $inspiration){
				inspiration_add(0, $inspiration);
			}
		}
		my_redirect($_SERVER['PHP_SELF']);
	}
	
	$inspiration_result = inspiration_search(0,0);
	
	if($inspiration_result){
	
		while($row = @mysql_fetch_array($inspiration_result)){
			$inspiration_output.= '<tr> 
								<td><input type="checkbox" name="inspiration_delete[]" value="' . $row['inspiration_id'] .'" /></td>
								<td>' . $row[0]  . '</td>
								<td>' . $row['inspiration'] . '</td>
								<td style="width:45px">
									<a rel="Edit Style" href="ajax-inspiration-edit.php?inspiration_id=' . $row['inspiration_id'] . '" class="modalopen"><img src="gfx/icon-edit.png" alt="edit" /></a>
									<a href="' . $_SERVER['PHP_SELF'] . '?action=delete&inspiration_id=' . $row['inspiration_id'] . '" onclick="return confirm(\'Are you sure you want to Delete?\');"><img src="gfx/icon-delete.png" alt="delete" /></a>
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
			<li><a href="style-manager.php">Manage Inspiration Types</a></li>
		</ul>
	</div>
	<div class="section">
		<div class="full">
			<div class="box">
				<div class="title">
					<h2>Add An Inspiration (15 allowed)</h2>
				</div>
				<div class="content forms">
					<form id="style-edit" action="" method="post">
						<div class="line">
							<label>Inspiration Name</label>
							<div>
							<input type="text" class="small complete required" name="inspiration[]" value="" />
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
					<h2>Inspiration</h2>
				</div>
				<div class="content">
					<form method="POST">
					<input type="hidden" id="action" name="action" value="delete">
					<table cellspacing="0" cellpadding="0" border="0" class="all"> 
						<thead> 
							<tr> 
								<th><input type="checkbox" name="check" class="checkall" /></th>
								<th>Page ID</th>
								<th>Inspiration Name</th>
								<th>Action</th>
							</tr> 
						</thead> 
						<tbody> 
							<?=$inspiration_output?>
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
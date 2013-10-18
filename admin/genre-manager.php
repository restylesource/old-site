<?

	$require_login = 1;
	$allowed_groups = array("admin");

	include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/db.php");	
	include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/logincheck.php");
	
	if($_POST || $_GET['action']){
	
		if($_POST['genre_delete']){
			foreach($_POST['genre_delete'] as $genre_id){
				genre_delete($genre_id);
			}
		} else if ($_GET['action'] == "delete" && $_GET['genre_id'] > 0 ){	
			genre_delete($_GET['genre_id']);	
		} else if($_POST['genre_id'] && $_POST['genre']){
			genre_add($_POST['genre_id'], $_POST['genre']);
		} else if($_POST['genre']){
			foreach($_POST['genre'] as $genre){
				genre_add(0, $genre);
			}
		}
		my_redirect($_SERVER['PHP_SELF']);
	}
	
	$genre_result = genre_search();
	
	if($genre_result){
	
		while($row = @mysql_fetch_array($genre_result)){
			$genre_output.= '<tr> 
								<td><input type="checkbox" name="genre_delete[]" value="' . $row['genre_id'] .'" /></td>
								<td>' . $row['genre'] . '</td>
								<td style="width:45px">
									<a rel="Edit Genre" href="ajax-genre-edit.php?genre_id=' . $row['genre_id'] . '" class="modalopen"><img src="gfx/icon-edit.png" alt="edit" /></a>
									<a href="' . $_SERVER['PHP_SELF'] . '?action=delete&genre_id=' . $row['genre_id'] . '" onclick="return confirm(\'Are you sure you want to Delete?\');"><img src="gfx/icon-delete.png" alt="delete" /></a>
								</td>
							</tr>';
		}
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" /> 
	<title>ReStyle Source Administrative Tools</title>

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
			<li><a href="genre-manager.php">Genre Manager</a></li>
		</ul>
	</div>
	<div class="section">
		<div class="full">
			<div class="box">
				<div class="title">
					<h2>Genre Manager</h2>
				</div>
				<div class="content forms">
					<form id="genre-edit" action="" method="post">
						<div class="line">
							<label>Genre Name</label>
							<div>
							<input type="text" class="small complete" name="genre[]" value="" />
							</div>
							<a class="add" onclick="Repeat(this);"></a>
                            <a class="delete" onclick="Delete(this);"></a>
						</div>
						<div class="line button">
							<button type="submit" class="green"><span>Sumbit</span></button>
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
					<h2>Genres</h2>
				</div>
				<div class="content">
					<form id="genre-edit" method="POST">
					<input type="hidden" id="action" name="action" value="delete">
					<table cellspacing="0" cellpadding="0" border="0" class="all"> 
						<thead> 
							<tr> 
								<th><input type="checkbox" name="check" class="checkall" /></th>
								<th>Genre Name</th>
								<th>Action</th>
							</tr> 
						</thead> 
						<tbody> 
							<?=$genre_output?>
						</tbody> 
					</table>
					<button type="submit" class="red"><span>Delete</span></button>
					</form>
				</div>
				<div class="modal forms" title="Edit Genre">
						
				</div>
			</div>
		</div>
	</div>
	<?php include("includes/footer.php"); ?>
</div>

	<?php include("includes/js.php"); ?>
	<script type="text/javascript" src="js/jquery.MultiFile.js"></script>
	
<script type="text/javascript">
	$('#genre-edit').validate();
</script>
</body>
</html> 
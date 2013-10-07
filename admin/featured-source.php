<?

	$require_login = 1;
	$allowed_groups = array("admin");

	include_once('includes/db.php');	
	include_once('includes/logincheck.php');

	$source_image_directory = "/source-images/";
	
	if($_POST){
		update_config('featured_source_id', $_POST['source_id']);
		my_redirect($_SERVER['PHP_SELF']);

	}
	
	$source_id = lookup_config('featured_source_id');
	
	$main_img = $source_image_directory . $_GET['source_id'] . "_main.jpeg";
	$main_img = (file_exists($_SERVER['DOCUMENT_ROOT'].$main_img)) ? $main_img : "assets/images/sources/no-image-main.jpg";
	
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
			<li><a href="style-manager.php">Featured Source</a></li>
		</ul>
	</div>
	<div class="section">
		<div class="full">
			<div class="box">
				<div class="title">
					<h2>Featured Source</h2>
				</div>
				<div class="content forms">
					<form id="style-edit" action="" method="post">
						<div class="line">
							<label>Featured Source</label>
							<input type="text" class="small complete required number" name="source_id" value="<?=$source_id?>" />
						</div>
						<div class="line button">
							<button type="submit" class="green"><span>Save</span></button>
						</div>
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
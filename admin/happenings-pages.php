<?

	$require_login = 1;
	$allowed_groups = array("admin");

	$background_image_path = '/home/restyle/public_html/dev-new/inspiration-photos/';

	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';	
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/logincheck.php';	
	
	if($_POST || $_GET['action']){

		if($_POST['happening_delete']){
			foreach($_POST['happening_delete'] as $happening_id){
				happening_delete($happening_id);
			}
		} else if ($_GET['action'] == "delete" && $_GET['happening_id'] > 0 ){	
			happenings_delete($_GET['happening_id']);	
		} else if($_POST['title']){
			
			$happening_id = happening_add($_POST['happening_id'], $_POST['title'], $_POST['city'], $_POST['state'], $_POST['start_date'], $_POST['end_date'], $_POST['recurring'], $_POST['recurring_end_date'], $_POST['description'], $_POST['url'], $_POST['status'], 0, $error);
			
		} 
		my_redirect($_SERVER['PHP_SELF']);
	}
	
	$result = happening_search('','','','','admin');
	
	if($result){
	
		while($row = @mysql_fetch_array($result)){
			
			$happening_output.= '<tr> 
										<td>' . $row[0] . '</td>
										<td>' . $row['title'] . '</td>
										<td>' . $row['state'] . '</td>
										<td>' . $row['start_date'] . '</td>
										<td>' . $row['end_date'] . '</td>
										<td>' . $row['status'] . '</td>
										<td>
											<a rel="Manage Happenings"  href="ajax-happenings-page-edit.php?happening_id=' . $row[0] . '" class="modalopen"><img src="gfx/icon-edit.png" alt="edit" /></a>
											<a rel="Duplicate Event"  href="#"><img src="gfx/icon-duplicate.png" alt="edit" /></a>
											<a href="' . $PHP_SELF . '?action=delete&happening_id=' . $row[0] . '" onclick="return confirm(\'Are you sure you want to delete?\r\n\r\nWARNING: This cannot be undone.\')"><img src="gfx/icon-delete.png" alt="delete" /></a>
										</td>
									</tr>';
		}
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>ReStyle | Happenings</title>
	<?php include("includes/css.php"); ?>
</head>

<body>
<div class="container">   
 	<!--begin header nav -->
	<?php include("includes/header.php"); ?>
    <!-- end header nav-->
    
    <!-- begin main site nav imported from main nav php-->

	<?php include("includes/nav.php"); ?>
    
	<!-- end main site nav-->
    
    <!--begin breadcrumb menu-->
    
    
	<div class="breadcrumbs">
		<ul>
			<li class="home"><a href="/admin"></a></li>
			<li class="break">&#187;</li>
			<li><a href="retailer-manager.php">Happenings</a></li>
		</ul>
	</div>
	
    <!-- begin content area-->
    
    <!-- begin retail management table-->
    
	<div class="section">
		<div class="full">
			<div class="box">
				<div class="title">
					<h2>Manage: Happenings</h2>
				</div>
				<div class="content">
					<form method="POST">
					<input type="hidden" id="action" name="action" value="delete">
					<table cellspacing="0" cellpadding="0" border="0" class="all"> 
						<thead> 
							<tr> 
								<th>ID</th>
								<th>Event Title</th> <!-- name of event-->
								<th>State</th> <!--Location of Event-->
								<th>Start Date</th> <!--Start Date of Event-->
								<th>End Date</th> <!--End Date of Event-->
								<th>Status</th> <!--pending, approved, on hold whatever-->
								<th>Action</th>
							</tr> 
						</thead> 
						<tbody> 
							<?=$happening_output?>
						</tbody> 
					</table>
					<button type="submit" class="red"><span>Delete</span></button>
                    <a href="ajax-happenings-page-edit.php" rel="Enter New Event" class="button green modalopen">New Hapenings</a>
                    </form>
				</div>
			</div>
		</div>
	</div>
    
    <!-- end retail management table-->
    
	
<!-- Begin edit forms-->   
    
<div class="modal forms" title="Manage Sources"></div>
   
    <!-- end User management table-->
  
	<?php include("includes/footer.php"); ?>
	
</div>

<?php include("includes/js.php") ?>
    <script type="text/javascript" src="js/jquery.MultiFile.js"></script>

<!--begin script to control edit and help modals. preprogramed for 4 instances of each. can add more by increasing array values-->
	<script type="text/javascript">
    $(function() { 
      var options = { 
          autoOpen: false, 
          width: 600, 
          height: 600,
          modal: true,
          closeText: '',
        }; 
      $([1, 2, 3, 4]).each(function() { 
        var num = this; 
        var dlg = $('#help' + num)
          .dialog(options); 
        $('.helpLink' + num).click(function() {
        dlg.css("display","visible"); 
          dlg.dialog("open"); 
          return false; 
        }); 
        $('.cancel' + num).click(function() { 
          dlg.dialog("close");
          dlg.get(0).reset();
          return false; 
        }); 
      }); 
        $([1, 2, 3, 4]).each(function() { 
        var num = this; 
        var dlg = $('#form' + num)
          .dialog(options); 
        $('.formLink' + num).click(function() {
        dlg.css("display","visible"); 
          dlg.dialog("open"); 
          return false; 
        }); 
        $('.cancel' + num).click(function() { 
          dlg.dialog("close");
          dlg.get(0).reset();
          return false; 
        }); 
      });
    });
    </script>

</body>
</html>
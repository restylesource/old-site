<?

	$require_login = 1;
	$allowed_groups = array("admin");

	include_once('includes/db.php');	
	include_once('includes/logincheck.php');
	
	if($_POST || $_GET['action']){

		if($_POST['retailer_delete']){
			foreach($_POST['retailer_delete'] as $retailer_id){
				user_delete($retailer_id);
			}
		} else if ($_GET['action'] == "delete" && $_GET['retailer_id'] > 0 ){	
			user_delete($_GET['retailer_id']);	
		} else if($_POST['company']){
			
			
			$retailer_id = user_add($_POST['retailer_id'], $_POST['fname'], $_POST['lname'], $_POST['company'], $_POST['email'], $_POST['phone'], $_POST['address1'], $_POST['city'], $_POST['state'], $_POST['zip'], $_POST['rate'], $_POST['website'], $_POST['notes'], $_POST['kind'], $_POST['password1'], $_POST['level'], $_POST['keywords'], $_POST['friendly_url'], $_POST['email_updates'], $error);

			user_source_types_update($retailer_id, $_POST['source_type'], $_POST['subsource']);

			user_save_extended ($retailer_id, '', '', $_POST['facebook'], $_POST['twitter'], '');

			//die($retailer_id . "," .  $_POST['adopt'] . "<BR>");

			user_inventory($retailer_id, $_POST['adopt']);

			// store source meta data
			user_meta_data($_POST['retailer_id'], $_POST['meta_title'], $_POST['meta_description'], $_POST['meta_keywords']);

			// We have to use hidden user status field, custom control always returns preloaded value
			user_status($retailer_id, $_POST['user_status']);
			
			// We need to store categories/subcategories
			//user_category_rel($retailer_id, $_POST['category'], $_POST['subcategory']);
			
			$new_login = ($_POST['retailer_id'] < 1 && $retailer_id > 0 && $_POST['password1']) ? 1 : 0;
			$password_change = ($_POST['password1'] && $_POST['retailer_id'] && $_POST['user_status'] == "active") ? 1 : 0;
						
			if($new_login){
				user_account_notify($retailer_id, $_POST['password1'], 1);
			} else if ($password_change) {
				user_account_notify($retailer_id, $_POST['password1'], 0);
			}
			//die();
		} 
		my_redirect($_SERVER['PHP_SELF']);
	}
	
	$retailer_result = user_search(0, '', array(3, 4));
	
	if($retailer_result){
	
		while($row = @mysql_fetch_array($retailer_result)){
		
			$source = user_source_first_lookup($row[0], $sub_source);
		
			//if($sub_source){
				$source = $sub_source;
			//}
		
			$retailer_output.= '<tr> 
										<td>' . $row[0] . '</td>
										<td><a rel="Manage Sources"  href="ajax-source-edit.php?retailer_id=' . $row[0] . '" class="modalopen">' . $row['company'] . '</a></td>
										<td>' . $row['status'] . '</td>
										<td>' . $row['group_name'] . '</td>
										<td>' . $source . '</td>
										<td>
											<a rel="Manage Source"  href="ajax-source-edit.php?retailer_id=' . $row[0] . '" class="modalopen"><img src="gfx/icon-edit.png" alt="edit" /></a>
											<a href="' . $PHP_SELF . '?action=delete&retailer_id=' . $row[0] . '" onclick="return confirm(\'Are you sure you want to delete?\r\n\r\nWARNING: This cannot be undone.\')"><img src="gfx/icon-delete.png" alt="delete" /></a>
											<a href="/sources.php?id=' . $row[0] . '" target="_page"><img src="gfx/ico-pic.png" alt="Retailer Page" />
										</td>
									</tr>';
		}
	}

	// Build Source Type JSON object
	$source_result = sources_search();
	
	$source_array = array();
	$i = 0;
	
	if($source_result){
	
		while($row = @mysql_fetch_array($source_result)){
	
			$source_array[$i] = array();
			$source_array[$i]['id'] = $row['source_id'];
			$source_array[$i]['name'] = $row['source'];
	
			$sub_array = array();
			// lookup sub sources for sources
			$sub_source_result = sub_source_lookup($row['source_id']);
			$j = 0;
			while($sub_row = @mysql_fetch_array($sub_source_result)){
				$sub_array[$j]['id'] = $sub_row['sub_source_id'];
				$sub_array[$j]['name'] = $sub_row['sub_source'];
				$j++;
			}
		
			$source_array[$i]['sub_source'] = $sub_array;
				
			$i++;
		}
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>ReStyle | Manage Retailers</title>


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
			<li><a href="retailer-manager.php">Manage Sources</a></li>
		</ul>
	</div>
	
    <!-- begin content area-->
    
    <!-- begin retail management table-->
    
	<div class="section">
		<div class="full">
			<div class="box">
				<div class="title">
					<h2>Manage: Sources</h2>
				</div>
				<div class="content">
					<form method="POST">
					<input type="hidden" id="action" name="action" value="delete">
					<table id="dataTable-products" cellspacing="0" cellpadding="0" border="0" class="all"> 
						<thead> 
							<tr> 
								<th>ID</th> <!-- ID of Source-->
								<th>Name</th> <!-- name of source-->
								<th>Status</th> <!--pending, approved, on hold whatever-->
								<th>Kind</th> <!--kind of source-->
								<th>Sub Type</th> <!--type of source-->
								<th>Action</th>
							</tr> 
						</thead> 
						<tbody> 
							<?=$retailer_output?>
						</tbody> 
					</table>
					<button type="submit" class="red"><span>Cancel</span></button>
                    <a href="ajax-source-edit.php" rel="Enter New Source" class="button green modalopen">New Source</a>
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
	<script type="text/javascript">var source = <?php print json_encode($source_array);?>;</script>
	
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
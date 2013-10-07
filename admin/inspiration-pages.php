<?

	$require_login = 1;
	$allowed_groups = array("admin");

	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';	
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/logincheck.php';	
	
	$base_path = lookup_config('base_site_path');
	$photo_path = lookup_config('inspiration_photo_path');
	
	$background_image_path = $base_path . $photo_path;
	
	if($_POST || $_GET['action']){
		if($_POST['page_delete']){
			foreach($_POST['page_delete'] as $page_id){
				page_delete($page_id);
			}
		} else if ($_GET['action'] == "delete" && $_GET['page_id'] > 0 ){	
			page_delete($_GET['page_id']);	
		} else if($_POST['page_title']){
			
			$page_id = page_add($_POST['page_id'], $_POST['page_title'], $_POST['page_sub_title'], $_POST['inspiration_id'], $_POST['sub_inspiration_id'], $_POST['page_status'], $_POST['meta_title'], $_POST['meta_keywords'], $_POST['meta_description'], $_POST['designer_products'], $_POST['related_products'],  $_POST['restyle_products'], $error);

			page_source_types_update($page_id, $_POST['source_type'], $_POST['subsource']);

			if($_FILES['bg_image']['name']){
				if(move_uploaded_file($_FILES['bg_image']['tmp_name'], $background_image_path . $page_id . "_bg.jpg")){
					
				} 
			}

		} 
		my_redirect($_SERVER['PHP_SELF']);
	}
	
	$page_result = page_search();
	
	if($page_result){
	
		while($row = @mysql_fetch_array($page_result)){
			
			$page_output.= '<tr> 
										<td>' . $row[0] . '</td>
										<td>' . $row['page_title'] . '</td>
										<td>' . trim($row['inspiration']) . '</td>
										<td>' . trim($row['sub_inspiration']) . '</td>
										<td>' . $row['created_date'] . '</td>
										<td>' . $row['page_status'] . '</td>
										<td>
											<a rel="Manage Inspiration Page"  href="ajax-inspiration-page-edit.php?page_id=' . $row[0] . '" class="modalopen"><img src="gfx/icon-edit.png" alt="edit" /></a>
											<a href="' . $PHP_SELF . '?action=delete&page_id=' . $row[0] . '" onclick="return confirm(\'Are you sure you want to delete?\r\n\r\nWARNING: This cannot be undone.\')"><img src="gfx/icon-delete.png" alt="delete" /></a>
											<a href="/inspiration-type.php?id=' . $row[0] . '" target="_new"><img src="gfx/ico-pic.png" alt="Inspiration Page" />
										</td>
									</tr>';
		}
	}
	
	
	// Build Inspiration Type JSON object
	$inspiration_result = inspiration_search(0, 0);
	
	$inspiration_array = array();
	$i = 0;
	
	if($inspiration_result){
	
		while($row = @mysql_fetch_array($inspiration_result)){
	
			$inspiration_array[$i] = array();
			$inspiration_array[$i]['id'] = $row['inspiration_id'];
			$inspiration_array[$i]['name'] = $row['inspiration'];
	
			$sub_array = array();
			// lookup sub sources for sources
			$sub_inspiration_result = sub_inspiration_lookup($row['inspiration_id']);
			$j = 0;
			while($sub_row = @mysql_fetch_array($sub_inspiration_result)){
				$sub_array[$j]['id'] = $sub_row['sub_inspiration_id'];
				$sub_array[$j]['name'] = $sub_row['sub_inspiration'];
				$j++;
			}
		
			$inspiration_array[$i]['sub_inspiration'] = $sub_array;
				
			$i++;
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
<title>ReStyle | Inspiration Pages</title>
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
			<li><a href="retailer-manager.php">Inspiration Pages</a></li>
		</ul>
	</div>
	
    <!-- begin content area-->
    
    <!-- begin retail management table-->
    
	<div class="section">
		<div class="full">
			<div class="box">
				<div class="title">
					<h2>Manage: Inspiration Pages</h2>
				</div>
				<div class="content">
					<form method="POST">
					<input type="hidden" id="action" name="action" value="delete">
					<table cellspacing="0" cellpadding="0" border="0" class="all"> 
						<thead> 
							<tr> 
								<th>ID</th>
								<th>Page Title</th> <!-- name of retailer-->
								<th>Inspiration Type</th> <!--type of source-->
								<th>Sub Type</th>
								<th>Date Added</th> <!--type of source-->
								<th>Status</th> <!--pending, approved, on hold whatever-->
								<th>Action</th>
							</tr> 
						</thead> 
						<tbody> 
							<?=$page_output?>
						</tbody> 
					</table>
					<button type="submit" class="red"><span>Delete</span></button>
                    <a href="ajax-inspiration-page-edit.php" rel="Enter New Inspiration Type" class="button green modalopen">New Inspiration Page</a>
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
	<script type="text/javascript">var cat = <?php print json_encode($inspiration_array);?>;</script>

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
   

	$('#inspiration_id').live("change", function(){
		var sel_cat = $(this).val();
		var ele = $(this).parent().find('.subcategory');
	
		// refresh is not working, so delete element and recreate to get options to load
		ele.remove();
	
		$(this).parent().append("<select class='subcategory' name='sub_inspiration_id'><option=''>(Required)</option></select>").append(' ');
		ele = $(this).parent().find('.subcategory');
	
		ele.children('option:not(:first)').remove();

		$.each (cat, function (index) {
			if( sel_cat == cat[index]['id'] ){
				$.each(cat[index]['sub_inspiration'], function(item) {
					var id = cat[index]['sub_inspiration'][item]['id'];
					var text = cat[index]['sub_inspiration'][item]['name'];
					
					$(ele).append(	
						$('<option></option>').val(id).html(text)
					);
				});
			}
		});

		ele.selectmenu({style: 'dropdown',
			transferClasses: true,
			width: null
		});	
	
		// refresh is not working after adding options
		//ele.selectmenu('refresh', true);
		
	});

	 </script>

</body>
</html>
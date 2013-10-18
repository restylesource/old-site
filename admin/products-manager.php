<?

	$require_login = 1;

	include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/db.php");	
	include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/logincheck.php");
	
	$image_dir = "/products/";
	
	if($_POST || $_GET['action']){

		if($_POST['product_delete']){
			foreach($_POST['product_delete'] as $product_id){
				product_delete($product_id);
			}
		} else if ($_GET['action'] == "delete" && $_GET['product_id'] > 0 ){	
			product_delete($_GET['product_id']);	
		}
		
		my_redirect($_SERVER['PHP_SELF']);
	}
	
	//$product_result = product_search(0);
	
	if($product_result){

		while($row = @mysql_fetch_array($product_result)){
	
			//Next line will be where we check product ownership (edit product or add to inventory)
			$edit_link = (1==1) ? "products-new.php" : "products-new.php";
			
			$category = product_category_first_lookup($row['product_id']);
			
			$product_image_result = product_image_search($row['product_id']);
			$product_image_output = "";
			$i=0;
			while($product_image_row = @mysql_fetch_array($product_image_result)){
				$product_image_output.= '<li><a id="link_' . $row['product_id'] . '_' . $i . '" href="'.$image_dir.$product_image_row['image'] .'" title="">'.$row['product'].'</li>';
				$i++;
			}
			
			$discontinued = $row['discontinued_ind'];
			
			$product_output.= '<tr> 
									<td>' . $row['product_id'] . '</td>
									<td>' . $row['product'] . '</td>
									<td>' . $category . '</td>
									<td>' . $row['iten_nbr'] . '</td>
									<td>$' . $row['company'] . '</td>
									<td>' . $row['status'] . '</td>
									<td>' . $discontinued . '</td>
									<td>
										<a rel="Edit Product" href="' . $edit_link . '?product_id=' . $row['product_id'] . '" class=""><img src="gfx/icon-edit.png" alt="edit" /></a>
										<a href="' . $PHP_SELF . '?action=delete&product_id=' . $row['product_id'] . '" onclick="return confirm(\'Are you sure you want to delete?\r\n\r\nWARNING: This cannot be undone.\')"><img src="gfx/icon-delete.png" alt="delete" /></a>
									</td>
								</tr>';
		}
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" /> 
	<title>ReStyle | All Products Manager</title>
	<?php include("includes/css.php"); ?>
	<link rel="stylesheet" type="text/css" href="css/jquery.lightbox-0.5.css" media="screen" />
	<script>	
		var upc = "12345678999";
		
	</script>
</head>

<body>

<div class="container">   
  <!--begin header nav -->
	<?php include("includes/header.php"); ?>
    <!-- end header nav-->
    
    <!-- begin main site nav-->

	<?php include("includes/nav.php"); ?>
	<!-- end main site nav-->
    
    <!--begin breadcrumb menu-->
    
    
	<div class="breadcrumbs">
		<ul>
			<li class="home"><a href="/admin"></a></li>
			<li class="break">&#187;</li>
			<li><a href="product-manager.php">Manage Products</a></li>
		</ul>
	</div>
	
    <!-- begin content area-->
    
    <!-- begin product management table-->
    
	<div class="section">
		<div class="full">
			<div class="box">
				<div class="title">
					<h2>Manage: All Products</h2>
					
				</div>
				<div class="content">
					<form method="POST">
					<input type="hidden" id="action" name="action" value="delete">
					<table id="dataTable-products" cellspacing="0" cellpadding="0" border="0" class="all ajax"> 
						<thead> 
							<tr> 
								<th>ID</th> <!-- ResStyle ID-->
								<th>Product Name</th> <!-- name of product-->
								<th>Primary Category</th> <!--Kitchen, living room, clothes-->
                                <th>Item Nbr</th>
                                <th>Manufacturer</th>
								<th>Status</th>
								<th>D</th>
								<th>Action</th>
							</tr> 
						</thead> 
						<tbody>
							<tr>
    							<td colspan="8" class="dataTables_empty">Loading data from server</td>
       						</tr> 
							<?=$product_output?>
						</tbody> 
					</table>
					<button type="submit" class="red"><span>Cancel</span></button>
                    <a rel="Add New Product" href="products-new.php" class="button green">Add New Product</a>
                    </form>
				</div>
			</div>
		</div>
	</div>

    
	<div class="modal forms" title="Products"></div>

    <!-- end products management table-->
  

<?php include("includes/footer.php") ?>
</div>

   
    	<?php include("includes/js.php") ?>
    	<script type="text/javascript" src="js/jquery.MultiFile.js"></script>
    	<script type="text/javascript" src="js/jquery.lightbox-0.5.min.js"></script>
    	
<!--begin script to control edit and help modals. preprogramed for 4 instances of each. can add more by increasing array values-->
	<script type="text/javascript">
	
	$(document).ready(function() {
		$('#dataTable-products').dataTable( {
			"sDom": '<"top"ir>t<"bottom"plf<"clear">',
			"bInfo": false,
			"bProcessing": true,
			"bServerSide": true,
			"iDisplayLength": 25,
        	"aLengthMenu": [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
        	"sPaginationType": "full_numbers",
        	"bPaginate": true,
			"sAjaxSource": "ajax-dataTable-products.php",
			"fnDrawCallback": function() {
      			$('#dataTable-products div.gallary').each(function() {
      				//alert($(this).attr('id'));
      				$("#" + $(this).attr('id') + " a").lightBox();
      			});
    		}
		} );
	} );
	
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
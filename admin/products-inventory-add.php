<?

	$require_login = 1;

	include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/db.php");	
	include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/logincheck.php");
	
	$uploads_dir = "../products";
	$uploads_thumb_dir = "../products/thumb";
	$thumbnail_size = 72;
	
	if($g_sess->get_var("systemTeam")!="retailer")
		my_redirect('/admin/products-manager.php');
	
	if($_POST){
		product_inventory_add($_POST['product_id'], $g_sess->get_var("user"), $_POST['long_description'], $_POST['short_description']);
		
		foreach ($_FILES as $name => $file) {
			if ($_FILES[$name]["error"] == UPLOAD_ERR_OK) {
				$key = end(explode('_', $name));
				$tmp_name = $_FILES[$name]["tmp_name"];
				$name = $product_id . "_" . $g_sess->get_var("user") . "_" . $key . "_" . time() . "." . gfe($_FILES[$name]["name"]); 
				if(move_uploaded_file($tmp_name, "$uploads_dir/$name")){
					product_image_save($image_id, $_POST['product_id'], $g_sess->get_var("user"), $name, $key, 0);
					
					// Generate Thumbnail
					square_crop("$uploads_dir/$name", "$uploads_thumb_dir/$name", 64, 100);
				}
			}
		}

		my_redirect($_SERVER['PHP_SELF'] . "?product_id=" . $_POST['product_id']);
		
	} else if($_GET['product_id'] > 0){
	
		$product_result = product_lookup($_GET['product_id']);
	
		if($product_result){
			$row = @mysql_fetch_array($product_result);
			$status = $row['status'];
			$product_id = $row['product_id'];
			$product = $row['product'];
			$upc = ($row['upc']) ? $row['upc'] : "<i>Not Defined</i>";
			$manufacturer_id = $row['manufacturer_id'];
			$keywords = $row['keywords'];
			$main_short_description = $row['short_description'];
			$main_long_description = $row['long_description'];
			$style_id = $row['style_id'];
			
			if($style_id){
				$style_result = style_lookup($style_id);
				$style_row = @mysql_fetch_array($style_result);
				$style = $style_row['style'];
			} else {
				$style = "<i>Not Defined</i>";
			}
			
			$genre_id = $row['genre_id'];
			
			if($genre_id){
				$genre_result = genre_lookup($genre_id);
				$genre_row = @mysql_fetch_array($genre_result);
				$genre = $genre_row['genre'];
			} else {
				$genre = "<i>Not Defined</i>";
			}
			
			$msrp = $row['msrp'];
			$width = $row['width'];
			$length = $row['length'];
			$height = $row['height'];
			$weight = $row['weight'];
			
			$inventory_result = product_retailer_inventory_lookup($product_id, $g_sess->get_var("user"));
			if($inventory_result && mysql_num_rows($inventory_result)){
				$inventory_row = @mysql_fetch_array($inventory_result);
				
				$in_inventory = 1;
				$short_description = $inventory_row['short_description'];
				$long_description = $inventory_row['long_description'];
			}
			
			$product_category_result = product_category_lookup($product_id);
			
			while($product_cat_row = @mysql_fetch_array($product_category_result)){
			
				$category = category_name($product_cat_row['category_id']);
				$sub_category = sub_category_name($product_cat_row['category_id'], $product_cat_row['sub_category_id']);
			
				$product_category_output.= '<tr>
												<td>' . $category . '</td>
												<td>' . $sub_category . '</td>
											</tr>';
			}
			
			$product_image_result = product_image_search($product_id);
			
			while($product_image_row = @mysql_fetch_array($product_image_result)){
				
				$top_level_images.= '<div class="thumb">
													<div style="display: none; ">
														<a rel="group" href="/products/' . $product_image_row['image'] . '" class="zoom"><img src="gfx/img-zoom.png" alt="Zoom"></a>
													</div>
													<span class="shadow"><img src="/products/thumb/' . $product_image_row['image'] . '" class="shadow" alt="Photo"></span>
												</div>';
			}
			
			$product_image_result = product_image_search($product_id, 0, 0, $g_sess->get_var("user"));
			
			$photo_output_array = array();
			
			while($product_image_row = @mysql_fetch_array($product_image_result)){
				array_push($photo_output_array, '<div id="' . $product_image_row['image_id']  . '" class="thumb">
													<div style="display: none; ">
														<a rel="group" href="/products/' . $product_image_row['image'] . '" class="zoom"><img src="gfx/img-zoom.png" alt="Zoom"></a>
														<a href="#" class="deletePhoto" data-product_id="' . $product_id  . '" data-image_id="' . $product_image_row['image_id'] . '"><img src="gfx/img-delete.png" alt="Delete"></a>
													</div>
													<span class="shadow"><img src="/products/thumb/' . $product_image_row['image'] . '" class="shadow" alt="Photo"></span>
												</div>
												<br /><br />');
			}
		}
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>My Products</title>
	
	<?php include("includes/css.php"); ?>
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
			<li class="home"><a href="index.php"></a></li>
			<li class="break">&#187;</li>
			<li><a rel="Add New Product" href="ajax-product-add.php" class="modalopen">Add A Product</a></li>
		</ul>
	</div>
    <!-- begin content area-->
    <!-- begin products management table-->
	<div class="section">	
		<div class="full">
			<div class="box">
				<div class="title">
					<h2>Add Product to My Inventory</h2>
				</div>
				<div class="content">
					<?if($in_inventory){?>
					<div class="messages green">
						<span></span>
						This Product has been added to your inventory.
					</div>
					<?}?>
                	<form id="product-edit" method="post" enctype="multipart/form-data">
                		<input type="hidden" name="product_id" value="<?=$product_id?>" >
						<div class="line">
							<label>Product Name:</label>
							<?=$product?>
						</div>
                        <div class="line">
							<label>UPC:</label>
							<?=$upc?>
						</div>
                        <div class="line">
							<label>Keywords:</label>
							<?=$keywords?>
						</div>
                        <!--depending on the answer above, the below select should populate with the appropriate options-->
                      	<div class="line">
							<label>Categories:</label>
							<div>
								<table cellspacing="0" width="150" cellpadding="0" border="0"> 
									<tr>
										<td width="75"><strong>Category</strong></td>
										<td width="75"><strong>Sub-Category</strong></td>
									</tr>
									<?=$product_category_output?>
								</table>
							</div>
						</div>
						<div class="line">
							<label>Style:</label>
							<?=$style?>
						</div>
						<div class="line">
							<label>Genre:</label>
							<?=$genre?>
						</div>
						<div class="line">
							<label>MSRP:</label>
							<?=$msrp?>
						</div>
                        <div class="line">
							<label>Short Description:</label>
							<input type="text" class="medium" name="short_description" value="<?=$short_description?>" />
						</div>
						 <div class="line">
							<label>Long Description:</label>
							<textarea class="big" name="long_description" rows="" cols=""><?=$long_description?></textarea>
						</div>
                        <div class="line">
							<label>Dimensions:</label>
							<?=$width?> W x <?=$length?> L x <?=$height?> H
						</div>
                        <div class="line">
							<label>Weight:</label>
							<?=$weight?> lbs
						</div>
                        <div class="line">
							<label>Default Photos:</label>
							<?=$top_level_images?>
						</div>
                        <div class="line">
                        	<label>Custom Photo 1:</label>
                        	<?if($photo_output_array[0]) echo($photo_output_array[0]);?>
               				<input type="file" name="file_1" id="file_1" style="height:25px;"/>
                        </div>
                        <div class="line">
                        	<label>Custom Photo 2:</label>
                        	<?if($photo_output_array[1]) echo($photo_output_array[1]);?>
               				<input type="file" name="file_2" style="height:25px;"/>
                        </div>
                        <div class="line">
                        	<label>Custom Photo 3:</label>
                        	<?if($photo_output_array[2]) echo($photo_output_array[2]);?>
               				<input type="file" name="file_3" style="height:25px;"/>
                        </div>
                        <div class="line">
                        	<label>Custom Photo 4:</label>
                        	<?if($photo_output_array[3]) echo($photo_output_array[3]);?>
               				<input type="file" name="file_4" style="height:25px;"/>
                        </div>
                        <div class="line button">
						<button onclick="$(this).prev(form).reset()"class="red"><span>Cancel</span></button>      
                    	<button type="submit" class="green"><span>Add To My Inventory</span></button>
                    	<br />Please note, any product and/or photos you add will be available for other ReStyle Source retailers to use.
                    </div>
                </form>
                    <!--Delete button will remove record from DB. Needs to have a javascript confirm box that only after confirming will it delete the record-->	
				</div>
			</div>
		</div>
	</div>
	
	<div class="modal forms" title="Products"></div>
	
	<!-- begin help modals-->
    <div id="help1" style="display:none;">
        <h2>Adding Products To Shelf</h2>
        <p>Here you can add products to your store's shelf. Fill in all the details for each product and attach a product photo. Keywords and descriptions help users search for your product.
		</p>
    </div>
    <!-- end photographer management table-->
	<?php include("includes/footer.php"); ?>
</div>

<?php include("includes/js.php"); ?>
    <script type="text/javascript" src="js/jquery.MultiFile.js"></script>


	<script type="text/javascript">
	
	$('#product-edit').validate();
	
	$(".deletePhoto").click(function(e){
		var product_id = $(this).attr('data-product_id');
		var image_id = $(this).attr('data-image_id');
		
		var id = $(this).parent().parent().attr('id');
		
		$.post("ajax-functions.php", { f: "imageDelete" , product_id: product_id, image_id: image_id },
 			function(data) {
   				$('#'+id).fadeOut(300, function() { $('#'+id).remove(); });
 			}
		);
		return false;
	});
	
	
    $(function() { 
      var options = { 
          autoOpen: false, 
          width: 500, 
          height: 400,
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
    });
    </script>
</body>
</html>
<?

	$require_login = 1;

	include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/db.php");	
	include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/logincheck.php");
	
	$uploads_dir = "../products";
	$uploads_thumb_dir = "../products/thumb";
	$thumbnail_size = 72;
	
	if($_POST){
		
		//echo("<pre>");
		//print_r($_POST);
		
		if($_POST['upc'] && !upc_available($_POST['product_id'], $_POST['upc'])){
				
		} else {
			// if admin editing, assume ownership of product
			if($g_sess->get_var("systemTeam") == "admin" && $_POST['product_id'])
				product_assume_ownership($_POST['product_id'], $g_sess->get_var("user"));
	
			$product_id = product_add($_POST['product_id'], $g_sess->get_var("user"), $_POST['product'], $_POST['status'],  $_POST['upc'], $_POST['item_nbr'], $_POST['manufacturer'], $_POST['keywords'],  $_POST['short_description'], $_POST['description'], $_POST['style'], $_POST['genre'], $_POST['msrp'], $_POST['width'], $_POST['length'], $_POST['height'], $_POST['weight'], $_POST['materials'], $_POST['meta_title'], $_POST['meta_keywords'], $_POST['meta_description'], $_POST['discontinued_ind']);
		
			// if retailer adding, add product to their inventory
			if($g_sess->get_var("systemTeam") == "retailer" && $product_id)
				product_inventory_add($product_id, $g_sess->get_var("user"), $_POST['description'], $_POST['short_description']);
		
			// save inpirations
			product_inspiration_rel($product_id, $_POST['inspiration']);
		
			// save colors
			product_color_rel($product_id, $_POST['color']);
			
			// save Categories
			
			//echo("<pre>");
			//print_r($_POST['category']);
			
			product_category_rel($product_id, $_POST['category'], $_POST['subcategory']);
		
			// process files
			$i=0;
			foreach ($_FILES as $name => $file) {

				if ($_FILES[$name]["error"] == UPLOAD_ERR_OK) {
					$key = end(explode('_', $name));
					$tmp_name = $_FILES[$name]["tmp_name"];
					$name = $product_id . "_" . $g_sess->get_var("user") . "_" . $key . "_" . time() . "." . gfe($_FILES[$name]["name"]); 
					if(move_uploaded_file($tmp_name, "$uploads_dir/$name")){
						product_image_save($image_id, $product_id, $g_sess->get_var("user"), $name, $_POST['alt'][$i], $key, 1);
						
						// Generate Thumbnail
						square_crop("$uploads_dir/$name", "$uploads_thumb_dir/$name", 64, 100);
					}
				}
				$i++;
			}
			
			my_redirect('/admin/products-manager.php');
		}
	}
	

	if($_GET['product_id'] > 0){
	
		$product_result = product_lookup($_GET['product_id']);
	
		if($product_result){
			$row = @mysql_fetch_array($product_result);
			$owner = $row['user_id'];
			$status = $row['status'];
			$product_id = $row['product_id'];
			$product = $row['product'];
			$upc = $row['upc'];
			$item_nbr = $row['item_nbr'];
			$manufacturer_id = $row['manufacturer_id'];
			$keywords = $row['keywords'];
			$short_description = stripslashes($row['short_description']);
			$long_description = stripslashes($row['long_description']);
			$style_id = $row['style_id'];
			$genre_id = $row['genre_id'];
			$msrp = $row['msrp'];
			$width = $row['width'];
			$length = $row['length'];
			$height = $row['height'];
			$weight = $row['weight'];
			$materials = $row['materials'];

			$meta_title = $row['meta_title'];
			$meta_keywords = $row['meta_keywords'];
			$meta_description = $row['meta_description'];

			$discontinued_ind = $row['discontinued_ind'];

			if($g_sess->get_var("systemTeam") == "retailer" && $owner != $g_sess->get_var("user")){
				my_redirect('admin/index.php');
			}
	
			$product_inspiration_result = product_inspiration_lookup($product_id);
			
			while($product_inspiration_row = @mysql_fetch_array($product_inspiration_result)){
			
				$inspiration_result = inspiration_search();
				$this_inspiration_options = build_generic_dropdown($inspiration_result, $product_inspiration_row['inspiration_id'], 1);
			
				$product_inspiration_output.= '<div>
												<select class="inspiration" name="inspiration[]">' . $this_inspiration_options . '</select>
												<a href="#" class="deleteThis">X</a>
											</div>';
			}
	
			$product_category_result = product_category_lookup($product_id);
				
			while($product_cat_row = @mysql_fetch_array($product_category_result)){
			
				$cat_result = category_search();
				$this_cat_options = build_generic_dropdown($cat_result, $product_cat_row['category_id'], 0);
			
				$sub_result = sub_category_lookup($product_cat_row['category_id']);
				$this_sub_options = build_generic_dropdown($sub_result, $product_cat_row['sub_category_id'], 1, "(Optional)");
			
				$product_category_output.= '<div>
												<select name="category[]" class="category">' . $this_cat_options . '</select>
												<select name="subcategory[]" class="subcategory">' . $this_sub_options . '</select>
												<a href="#" class="deleteThis">X</a>
											</div>';
			}
			
			$product_color_result = product_color_lookup($product_id);
			
			while($product_color_row = @mysql_fetch_array($product_color_result)){
			
				$color_result = color_search();
				$this_color_options = build_generic_dropdown($color_result, $product_color_row['color_id'], 1);
			
				$product_color_output.= '<div>
												<select class="color" name="color[]">' . $this_color_options . '</select>
												<a href="#" class="deleteThis">X</a>
											</div>';
			}
			
			$product_image_result = product_image_search($product_id, 0, 1);
			
			$photo_output_array = array();
			$photo_alt_array = array();
			while($product_image_row = @mysql_fetch_array($product_image_result)){
				$photo_alt_array[] = $product_image_row['image_alt'];	
				array_push($photo_output_array, '<div class="thumb">
													<div style="display: none; ">
														<a rel="group" href="/products/' . $product_image_row['image'] . '" class="zoom"><img src="gfx/img-zoom.png" alt="Zoom"></a>
														<a href="#" class="deletePhoto" data-product_id="' . $product_id  . '" data-image_id="' . $product_image_row['image_id'] . '"><img src="gfx/img-delete.png" alt="Delete"></a>
													</div>
													<span class="shadow"><img src="/products/thumb/' . $product_image_row['image'] . '" class="shadow" alt="Photo"></span>
												</div>
												<br /><br />');
			}
			
		}
	} else {
		$upc = $_GET['upc'];
	}
	
	// let's build the category, sub-category JSON object
	$category_result = category_search();
	
	$cat_array = array();
	$i = 0;
	while($cat_row = @mysql_fetch_array($category_result)){
	
		$category_output.= "<option value=\"" . $cat_row['category_id'] . "\">" . $cat_row['category'] . "</option>";
	
		$cat_array[$i] = array();
		$cat_array[$i]['id'] = $cat_row['category_id'];
		$cat_array[$i]['name'] = $cat_row['category'];
	
		$sub_array = array();
		// lookup sub cats for cat
		$sub_category_result = sub_category_lookup($cat_row['category_id']);
		$j = 0;
		while($sub_row = @mysql_fetch_array($sub_category_result)){
			$sub_array[$j]['id'] = $sub_row['sub_category_id'];
			$sub_array[$j]['name'] = $sub_row['sub_category'];
			$j++;
		}
		
		$cat_array[$i]['sub_categories'] = $sub_array;
	
		$i++;
	}

	$style_result = style_search();
	$style_options = build_generic_dropdown($style_result, $style_id, 1);

	$inspiration_result = inspiration_search(0, 0);
	$inspiration_options = build_generic_dropdown($inspiration_result, $inspiration_id, 1);

	$color_result = color_search();
	$color_options = build_generic_dropdown($color_result, $color_id, 1);
	
	$genre_result = genre_search();
	$genre_options = build_generic_dropdown($genre_result, $genre_id, 1);
	
	$manufacturer_result = user_search(0, '', 4);	
	$manufacturer_options = build_generic_dropdown($manufacturer_result, $manufacturer_id, 1, "(none)", 0, 10);


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
			<li class="home"><a href="/admin"></a></li>
			<li class="break">&#187;</li>
			<li><a href="#">Add A Product</a></li>
		</ul>
	</div>
    <!-- begin content area-->
    <!-- begin products management table-->
	<div class="section">
			
		<div class="full">
			<div class="box">
				<div class="title">
					<h2>Add A New Product to Database</h2>
				</div>
				<div class="content">
                	<form id="product-edit" method="post" enctype="multipart/form-data">
                	<input type="hidden" name="product_id" value="<?=$product_id?>" >
                	<input type="hidden" name="product_status" id="product_status" value="<?=$status?>" >
                	<?if($product_id > 0 && $status=="pending" && $g_sess->get_var("systemTeam") != "admin"){?>	
                		<div class="messages green">
							<span></span>
							Your Product has been submitted to the database. An administrator will approve your posting shortly.
						</div>
					<?}?>
					<?if($upc_error){?>
						<div class="messages red">
							<span></span>
							We are Sorry, this UPC is already in the Database. <a href="product-inventory-add.php?product_id=<?=$upc_product_id?>">Click here</a> to add to your inventory.
						</div>
					<?}?>
						<div class="line" style="<?if($g_sess->get_var("systemTeam") != "admin") echo("display: none;");?>">
							<label>Status:</label>
							<input type="radio" name="status" id="radio-1" value="pending" <?if($status=="pending" || $status==""){ echo("checked=\"true\""); }?>" /> 
							<label for="radio-1">Pending</label>							
							<input type="radio" name="status" id="radio-2" value="active" <?if($status=="active"){ echo("checked=\"true\""); }?>" /> 
							<label for="radio-2">Active</label>
							<input type="radio" name="status" id="radio-3" value="inactive" <?if($status=="inactive"){ echo("checked=\"true\""); }?>" /> 
							<label for="radio-3">Inactive</label>
						</div>
						<div class="line">
							<label>Product Name:</label>
							<input type="text" class="small required" name="product" value="<?=$product?>" />
						</div>
						<div class="line">
							<label>Discontinued:</label>
							<select name="discontinued_ind">
								<option>Please Select</option>
								<option value="0" <?if($discontinued_ind==0) echo("selected")?>>No</option>
								<option value="1" <?if($discontinued_ind==1) echo("selected")?>>Yes</option>
							</select>
						</div>
						<div class="line">
							<label>Inspiration Type:</label>
							<?if($product_inspiration_output){?>
								<?=$product_inspiration_output?>
							<?} else {?>
                            <div>
							<select class="inspiration" name="inspiration[]">
								<?=$inspiration_options?>
							</select>
                        	</div>
                        	<?}?>
                            <a class="add" onclick="RepeatInspiration(this);"></a>
						</div>
						<?if($_GET['type']!="noupc"){?>
                        <div class="line">
							<label>UPC:</label>
							<input type="text" class="small upc" name="upc" value="<?=$upc?>" />
						</div>
						<div class="line">
							<label>Item #:</label>
							<input type="text" class="medium item-number" name="item_nbr" value="<?=$item_nbr?>" />
						</div>
						<?}?>
						<div class="line">
							<label>Manufacturer:</label>
							<select name="manufacturer">
								<?=$manufacturer_options?>
							</select>
						</div>
                        <div class="line">
							<label>Keywords:</label>
							<textarea class="big" rows="40" name="keywords" cols=""><?=$keywords?></textarea>
                            <br />Please add keywords separated by a comma.
						</div>
                      	<div class="line">
							<label>Categories:</label>
							<?if($product_category_output){?>
								<?=$product_category_output?>
							<?} else {?>
							<div>
							<select name="category[]" class="category">
								<option value=""></option>
								<?=$category_output?>
							</select>
							<select name="subcategory[]" class="subcategory">
								<option value=""></option>
								<?=$sub_category_output?>
							</select>
							</div>
							<?}?>
							<a class="add" onclick="RepeatCategories(this);"></a>
							
						</div>
						<div class="line">
							<label>Style:</label>
							<select name="style">
								<?=$style_options?>
							</select>
						</div>
						<div class="line">
							<label>Genre:</label>
							<select name="genre">
								<?=$genre_options?>
							</select>
						</div>
                      	<div class="line">
							<label>Color:</label>
							<?if($product_color_output){?>
								<?=$product_color_output?>
							<?} else {?>
                            <div>
							<select class="color" name="color[]">
								<?=$color_options?>
							</select>
                        	</div>
                        	<?}?>
                            <a class="add" onclick="RepeatColor(this);"></a>
                        </div>
						
						<div class="line">
							<label>MSRP:</label>
							<input type="text" class="small" name="msrp" value="<?=$msrp?>" />
						</div>
						<div class="line">
							<label>Short Description:</label>
							<input type="text" class="medium" name="short_description" value="<?=$short_description?>" maxlength="100" />
						</div>
                        <div class="line">
							<label>Long Description:</label>
							<textarea class="big" rows="40" name="description" cols=""><?=$long_description?></textarea>
						</div>
                        <div class="line">
							<label>Dimensions:</label>
							 <input name="length" class="xsmall number" maxlength="20" placeholder="Length" value="<?=$length?>"></input>
							<input name="width" class="xsmall number" maxlength="20" placeholder="Width" value="<?=$width?>"></input>                           
                            <input name="height" class="xsmall number" maxlength="20" placeholder="Height" value="<?=$height?>"></input>  (L x W x H) Dimensions are in Inches
						</div>
                        <div class="line">
							<label>Weight:</label>
							<input name="weight" class="xsmall number" maxlength="6" placeholder="Weight" value="<?=$weight?>"></input>lbs
						</div>
						<div class="line">
							<label>Material:</label>
							<input name="materials" class="medium" placeholder="Material" value="<?=$materials?>"></input>
						</div>
                        <div class="line">
                        	<label>Thumbnail:</label>
                        	<?if($photo_output_array[0]) echo($photo_output_array[0]);?>
               				<input type="file" name="file_1" style="height:25px;"/><br />
               				Image Thumbnail should be 135 x 150 pixels.<br /><br />
               				<input type="text" class="medium" name="alt[]" placeholder="Alt Tags" value="<?=$photo_alt_array[0]?>" />
                        </div>
                        <div class="line">
                        	<label>Photo 1:</label>
                        	<?if($photo_output_array[1]) echo($photo_output_array[1]);?>
               				<input type="file" name="file_2" style="height:25px;"/><br />
               				Image should be 471 pixels wide.<br /><br />
               				<input type="text" class="medium" name="alt[]" placeholder="Alt Tags" value="<?=$photo_alt_array[1]?>" />
                        </div>
                        <div class="line">
                        	<label>Photo 2:</label>
                        	<?if($photo_output_array[2]) echo($photo_output_array[2]);?>
               				<input type="file" name="file_3" style="height:25px;"/><br />
               				Image should be 471 pixels wide.<br /><br />
               				<input type="text" class="medium" name="alt[]" placeholder="Alt Tags" value="<?=$photo_alt_array[2]?>" />
                        </div>
                        <div class="line">
                        	<label>Photo 3:</label>
                        	<?if($photo_output_array[3]) echo($photo_output_array[3]);?>
               				<input type="file" name="file_4" style="height:25px;"/><br />
               				Image should be 471 pixels wide.<br /><br />
               				<input type="text" class="medium" name="alt[]" placeholder="Alt Tags" value="<?=$photo_alt_array[3]?>" />
                        </div>
                        <div class="line">
							<label>Meta Title:</label>
							<input type="text" class="small" name="meta_title" value="<?=$meta_title?>" />
						</div>
						<div class="line">
							<label>Meta Keywords:</label>
							<input type="text" class="small" name="meta_keywords" value="<?=$meta_keywords?>" />
						</div>
						<div class="line">
							<label>Meta Description:</label>
							<input type="text" class="small" name="meta_description" value="<?=$meta_description?>" />
						</div>
                     	<div class="line button">
						<button onclick="top.location= '/admin/index.php';" class="red"><span>Cancel</span></button>
                    <!-- opens up sale form for one or multiple items.-->
                    <button type="submit" class="green"><span><?if($g_sess->get_var("systemTeam") == "admin" && $product_id > 0 && $owner != $g_sess->get_var("user") ){?>Save<?} else {?>Save</span><?}?></button>
                    
                    </div>
                </form>
                    <!--Delete button will remove record from DB. Needs to have a javascript confirm box that only after confirming will it delete the record-->	
				</div>
			</div>
		</div>
	</div>
		
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
    <script type="text/javascript">var cat = <?php print json_encode($cat_array);?>;</script>

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
	
	$('input[type="radio"][name="status"]').change(function() {
     		if(this.checked) {
     			$('#product_status').val((this.value));
     		}
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
<?

	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';	
	
	$result = page_lookup($_GET['page_id']);
	
	if($result){
		$row = @mysql_fetch_array($result);	
		$page_title = $row['page_title'];
		$page_sub_title = $row['page_sub_title'];
		$inspriation_id = $row['inspiration_id'];
		$sub_inspiration_id = $row['sub_inspiration_id'];
		$page_status = $row['page_status'];
		$meta_title = $row['meta_title'];
		$meta_keywords = $row['meta_keywords'];
		$meta_description = $row['meta_description'];
		
		$source_type_result = page_source_types_lookup($_GET['page_id']);
			
		$source_array = array();
		
		if($source_type_result){
			while($row2 = @mysql_fetch_array($source_type_result)){
				$source_array[] = array($row2['source_id'], $row2['sub_source_id']);
			}	
		}
		
	}

	$result = inspiration_search(0, 0);
	$inspiration_options = build_generic_dropdown($result, $inspriation_id, 1);

	$result = sub_inspiration_lookup($inspriation_id);
	$sub_inspiration_options = build_generic_dropdown($result, $sub_inspiration_id, 0);

	$product_result = lookup_page_products($_GET['page_id'], 1, 0, 0);

	$product_array = array();

	if($product_result){
		while($product_row = mysql_fetch_array($product_result)){
			$product_array[] = $product_row['product_id'];
		}
	}

	$related_products = implode(", ", $product_array);
	
	$product_result = lookup_page_products($_GET['page_id'], 1, 0, 1);

	$product_array = array();

	if($product_result){
		while($product_row = mysql_fetch_array($product_result)){
			$product_array[] = $product_row['product_id'];
		}
	}

	$designer_products = implode(", ", $product_array);
	
	$product_result = lookup_page_products($_GET['page_id'], 1, 0, 0, 1);

	$product_array = array();

	if($product_result){
		while($product_row = mysql_fetch_array($product_result)){
			$product_array[] = $product_row['product_id'];
		}
	}

	$restyle_products = implode(", ", $product_array);
	
	$source_result = sources_search();
	$source_options1 = build_generic_dropdown($source_result, $source_array[0][0], 1);

	$sub_result = sub_source_lookup($source_array[0][0]);
	$sub_source_options1 = build_generic_dropdown($sub_result, $source_array[0][1], 1, "(Optional)");

	$source_result = sources_search();
	$source_options2 = build_generic_dropdown($source_result, $source_array[1][0], 1);
	
	$sub_result = sub_source_lookup($source_array[1][0]);
	$sub_source_options2 = build_generic_dropdown($sub_result, $source_array[1][1], 1, "(Optional)");
	
	$source_result = sources_search();
	$source_options3 = build_generic_dropdown($source_result, $source_array[2][0], 1);
	
	$sub_result = sub_source_lookup($source_array[2][0]);
	$sub_source_options3 = build_generic_dropdown($sub_result, $source_array[2][1], 1, "(Optional)");
	
	$source_result = sources_search();
	$source_options4 = build_generic_dropdown($source_result, $source_array[3][0], 1);
	
	$sub_result = sub_source_lookup($source_array[3][0]);
	$sub_source_options4 = build_generic_dropdown($sub_result, $source_array[3][1], 1, "(Optional)");
	
	$source_result = sources_search();
	$source_options5 = build_generic_dropdown($source_result, $source_array[4][0], 1);
	
	$sub_result = sub_source_lookup($source_array[4][0]);
	$sub_source_options5 = build_generic_dropdown($sub_result, $source_array[4][1], 1, "(Optional)");
	
?>
<form action="" method="post" enctype="multipart/form-data">
	<input type="hidden" name="page_id" value="<?=$_GET['page_id']?>">
	<div class="line">
		<label>Page Title</label>
		<input type="text" class="small required" name="page_title" value="<?=$page_title?>" />
	</div>
	<div class="line">
		<label>Sub Title</label>
		<input type="text" class="small required" name="page_sub_title" value="<?=$page_sub_title?>" />
	</div>
	<div class="line">
		<label>Inspiration:</label>
		<select id="inspiration_id" name="inspiration_id">
			<?=$inspiration_options?>
		</select>
		<select id="sub_inspiration_id" class="subcategory" name="sub_inspiration_id">
			<?=$sub_inspiration_options?>
		</select>
	</div>
	<div class="line">
		<label>Actual Products</label>
		<input type="text" class="medium" name="related_products" value="<?=$related_products?>" /><br />Product ID Separated by Comma
	</div>
	<div class="line">
		<label>Designer Picks</label>
		<input type="text" class="medium" name="designer_products" value="<?=$designer_products?>" /><br />Product ID Separated by Comma
	</div>
	<div class="line">
		<label>RESTYLE Picks</label>
		<input type="text" class="medium" name="restyle_products" value="<?=$restyle_products?>" /><br />Product ID Separated by Comma
	</div>
	<div class="line">
		<label>1st Source Type:</label>
		<select name="source_type[]" class="source">
		<?=$source_options1?>
		</select>
		<select name="subsource[]" class="subsource">
			<?=$sub_source_options1?>
		</select>
	</div>
	<div class="line">
		<label>2nd Source Type:</label>
		<select name="source_type[]" class="source">
		<?=$source_options2?>
		</select>
		<select name="subsource[]" class="subsource">
			<?=$sub_source_options2?>
		</select>
	</div>
	<div class="line">
		<label>3rd Source Type:</label>
		<select name="source_type[]" class="source">
		<?=$source_options3?>
		</select>
		<select name="subsource[]" class="subsource">
			<?=$sub_source_options3?>
		</select>
	</div>
	<div class="line">
		<label>4th Source Type:</label>
		<select name="source_type[]" class="source">
		<?=$source_options4?>
		</select>
		<select name="subsource[]" class="subsource">
			<?=$sub_source_options4?>
		</select>
	</div>
	<div class="line">
		<label>5th Source Type:</label>
		<select name="source_type[]" class="source">
		<?=$source_options5?>
		</select>
		<select name="subsource[]" class="subsource">
			<?=$sub_source_options5?>
		</select>
	</div>
	<div class="line">
		<label>Background</label>
		<input type="file" name="bg_image"/>
	</div>
	<div class="line">
		<label>Status:</label>
		<select name="page_status">
			<option value="active" <? if($page_status=="active") echo("selected");?>>active</option>
			<option value="inactive" <? if($page_status=="inactive") echo("selected");?>>inactive</option>
		</select>
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
		<button type="submit" class="green"><span>Update</span></button>
	</div>
</form>


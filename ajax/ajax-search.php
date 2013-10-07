<?

	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';	

	//Source Type, and then main categories

	// lookup source types
	$result = sources_search();
	
	if($result){
		while($row = mysql_fetch_array($result)){
			if($row['source'] != "Retailer" && $row['source'] != "Manufacturer"){
				$category_output .= '<option value="source_' . $row['source_id'] . '">' . $row['source'] . '</option>';
			}
		}
	}

	if($category_output)
		$category_output.= "<option value=\"\">-------------------------</option><option value=\"\">CHOOSE A CATEGORY</option>";

	$category_result = category_search();

	if($category_result){
	
		while($row = @mysql_fetch_array($category_result)){
			$category_output.= '<option value="category_' . $row['category_id'] . '">' . $row['category'] . "</option>";
		}
	}

	$style_result = style_search();
	
	if($style_result){
	
		while($row = @mysql_fetch_array($style_result)){
			$style_output.= '<option value="' . $row['style_id'] . '">' . $row['style'] . '</option>';
		}
	}

	$state_result = state_search(1);
	$state_options = build_generic_dropdown($state_result, $state, 0);

?>
<h1>Start Looking</h1>
<h2>Search by Source Type, Category, Style, Area Keyword, or any combination of the four.</h2>

<div class="dialog-content">
	<div class="error" id="login-error"></div>
	<form id="searchForm" method="post" action="/search-results.php">
		<div class="row">
			<select name="category" id="category-sort" class="uniform">
				<option value="">SOURCE TYPE</option>
				<?=$category_output?>
			</select>
			<select name="style" id="style-sort" class="uniform">
				<option>Style</option>
				<?=$style_output?>
			</select>
			<select name="state" id="area-sort" class="uniform">
				<option value="">Area</option>
				<?=$state_options?>
			</select>
		</div>
		<input type="text" name="keyword" placeholder="Keyword" />
	</form>
</div>

<script src="assets/js/uniform/jquery.uniform.min.js"></script>
<script>
	$('.dialogAjax.search select.uniform').uniform();
</script>
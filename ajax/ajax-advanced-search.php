<?

	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/logincheck.php';	

	//Source Type, and then main categories

	// SOURCE TYPES - Categories for Sources
	$result = sources_search();
	
	if($result){
		while($row = mysql_fetch_array($result)){
			if($row['source'] != "Retailer" && $row['source'] != "Manufacturer"){
				$source_category_output .= '<option value="' . $row['source_id'] . '">' . $row['source'] . '</option>';
			}
		}
	}

	// Categories for Products
	$category_result = category_search();

	if($category_result){
	
		while($row = @mysql_fetch_array($category_result)){
			$category_output.= '<option value="' . $row['category_id'] . '">' . $row['category'] . "</option>";
		}
	}

	// Inspiration Types
	$inspiration_result = inspiration_search();

	if($inspiration_result){
	
		while($row = @mysql_fetch_array($inspiration_result)){
			$inspiration_output.= '<option value="' . $row['inspiration_id'] . '">' . $row['inspiration'] . "</option>";
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
<h1>Advanced Search</h1>
<div class="dialog-content" style="margin: 1em 0 1em;">
	<div class="error" id="login-error"></div>
		<form id="searchForm" class="advSearch" method="post" action="/search-results.php">
<div class="dialog-columns">
<div class="column20">
<span class="white">SEARCH IN &gt;</span>
</div>
<div class="column30">
		<div class="checkboxes">
		<label for="products" style="margin-bottom: .4em;"><input type="radio" name="search_type" id="products" value="Products" <? if($_GET['search_type'] == "Products" || $_GET['search_type'] == "") echo("checked") ?> /> PRODUCTS</label>
		</div>
		<div class="checkboxes">
		<label for="sources" style="margin-bottom: .4em;"><input type="radio" name="search_type" id="sources" value="Sources" <? if($_GET['search_type'] == "Sources") echo("checked") ?> /> SOURCES</label>
		</div>
		<div class="checkboxes">
		<label for="inspirations" style="margin-bottom: .4em;"><input type="radio" name="search_type" id="inspirations" value="Inspirations" <? if($_GET['search_type'] == "Inspirations") echo("checked") ?> /> INSPIRATIONS</label>
		</div>
		<?if(in_array($g_sess->get_var("systemTeam"), array('retailer','manufacturer'))){?>
		<div class="checkboxes">
		<label for="manufacturers" style="margin-bottom: .4em;"><input type="radio" name="search_type" id="manufacturers" value="Manufacturers" <? if($_GET['search_type'] == "Manufacturers") echo("checked") ?> /> MANUFACTURERS</label>
		</div>
		<?}?>
</div>
<div class="column10">
<span class="white">FOR &gt;</span>
</div>
<div class="column40">
		<div class="row selector" id="source_category_row">
			<select name="source" id="source-type" class="uniform">
				<option value="">Category</option>
				<?=$source_category_output?>
			</select>
		</div>
		<div class="row selector" id="product_category_row">
			<select name="category" id="category-sort" class="uniform">
				<option value="">Category</option>
				<?=$category_output?>
			</select>
		</div>
		<div class="row selector" id="inspiration_row">
			<select name="inspiration" id="category-sort" class="uniform">
				<option value="">Category</option>
				<?=$inspiration_output?>
			</select>
		</div>
		<div class="row selector" id="style_row">
			<select name="style" id="style-sort" class="uniform">
				<option value="">Style</option>
				<?=$style_output?>
			</select>
		</div>
		<div class="row selector">
			<select name="state" id="area-sort" class="uniform">
				<option value="">Area</option>
				<?=$state_options?>
			</select>
		</div>
</div>
</div>
<div style="clear:both;"></div>
		<input type="text" name="keyword" placeholder="KEYWORDS / OPTIONAL" style="width:350px;margin-top:4px;" />
		</form>
</div>

<script src="assets/js/uniform/jquery.uniform.min.js"></script>
<script>
	$('.dialogAjax.advanced-search select.uniform').uniform();
	
	$(document).ready(function() {
	
		update_options();
		
		$('input:radio').change(
        	function(){
                 update_options();       
            }
        );
		
	});
	
	function update_options(){
		var id = $("input[name=search_type]:checked").attr('id');
	
		if(id == "sources" || id == "manufacturers"){
			$('#source_category_row').show();
			$('#product_category_row').hide();
			$('#inspiration_row').hide();
			$('#style_row').hide();
		} else if (id == "products"){
			$('#source_category_row').hide();
			$('#product_category_row').show();
			$('#inspiration_row').hide();
			$('#style_row').show();
		} else if (id == "inspirations"){
			$('#source_category_row').hide();
			$('#product_category_row').hide();
			$('#inspiration_row').show();
			$('#style_row').hide();
		}
	}
	
</script>
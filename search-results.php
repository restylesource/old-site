<?

	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';	
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/logincheck.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/ps_pagination.php';

	$section = "search";
	
	$image_base = '/home/restyle/public_html';
	$inspiration_image_directory = 'inspiration-photos/';
	
	$sort = $_REQUEST['sort'];
	$row_limit = ($_REQUEST['limit']) ? $_REQUEST['limit'] : 5; 

	if($row_limit=="ALL"){
		$row_limit = 10000;
	}

	//echo("<pre>");
	//print_r($_POST);
	
	$products_width  = 6;

	$conn = @mysql_connect ($DB_HOST, $DB_USER ,$DB_PASSWD);
	mysql_select_db ($DB_DBNAME, $conn);
	
	$is_source = (in_array($g_sess->get_var("systemTeam"), array('retailer','manufacturer')) && $g_sess->get_var("inventory_ind")) ? $g_sess->get_var("inventory_ind") : 0;
	
	$numPerPage = ($_REQUEST['numPerPage']) ? $_REQUEST['numPerPage'] : ($row_limit * $products_width);
	
	if($numPerPage==0)
		$numPerPage = $row_limit * $products_width;

	$photo_path = lookup_config('product_image_path');
	$source_image_directory = "http://dev.restylesource.com/source-images/";
	$search_type = ($_REQUEST['search_type']) ? $_REQUEST['search_type'] : "Sources";

	if($_REQUEST['source'] > 0){
		$source_id = $_REQUEST['source'];
	} else if ($_REQUEST['category']){
		$category_id = $_REQUEST['category'];
	}

	$query_array = array();

	if($search_type == "Sources" || $search_type == "Manufacturers"){
	
		$search_name = sources_name($source_id);
	
		if($search_type == "Sources"){
			$query_array[] = "search_type=Sources";
		} else {
			$query_array[] = "search_type=Manufacturers";
		}
		
	} else if($search_type == "Products") {
		$query_array[] = "search_type=Products";
		$search_name = category_name($category_id);
	} else if ($search_type == "Inspirations"){
		$query_array[] = "search_type=Inspirations";
	}
	
	$search_criteria_array[] = $search_name;
	
	if ($category_id>0){
		$query_array[] = "category=" . $category_id;
	}
	
	if($_REQUEST['style'] && $_REQUEST['style']!='Style'){
		$query_array[] = "style=" . $_REQUEST['style'];
		$search_criteria_array[] = style_name($_REQUEST['style']);
	}
	
	if($_REQUEST['inspiration']){
		$inspiration_id = $_REQUEST['inspiration'];
		$query_array[] = "inspiration=" . $_REQUEST['inspiration'];
		$search_criteria_array[] = inspiration_name($_REQUEST['inspiration']);
	}
	
	if($_REQUEST['state']){
		$query_array[] = "state=" . $_REQUEST['state'];
		$search_criteria_array[] = state_name($_REQUEST['state']);
	}	
	
	if($_REQUEST['keyword']){
		$query_array[] = "keyword=" . $_REQUEST['keyword'];	
		$search_criteria_array[] = $_REQUEST['keyword'];
	}
	
	if($_REQUEST['sort']){
		$query_array[] = "sort=" . $_REQUEST['sort'];	
	}
	if($_REQUEST['limit']){
		$query_array[] = "limit=" . $_REQUEST['limit'];	
	}
	
	if(is_array($query_array) && count($query_array))
		$search_qs = implode("&", $query_array);


	if(is_array($search_criteria_array)){
		$search_criteria = implode(' &gt; ', $search_criteria_array);
	}
		
	// SEARCH Products
	if($search_type == "Products"){
		
		$sql = product_search_adv($source_id, $category_id, $_REQUEST['style'], $_REQUEST['state'], $_REQUEST['keyword'], $sort, $g_sess->get_var("user"));	
	
		$pager = new PS_Pagination($conn, $sql, $numPerPage, 5, $search_qs);
		
		$product_result = $pager->paginate();
	
		if($product_result){
	
			$output = "<ul>";
			
			while($row = mysql_fetch_array($product_result)){
				
				
				if($i==$products_width){
					$i = 0;
					$output.= "</ul><ul>";
					$j++;
		
					if($j==$row_limit)
						break;
				}
				
				$discontinued = ($row['discontinued_ind']) ? '<span style="color:#f47920;font-size:13px;">* DISCONTINUED</span>' : '';
				$discontinued_class = ($row['discontinued_ind']) ? 'discontinued' : '';
				
				$product_image_result = product_image_search($row['product_id'], 0, 1);
				
				if($product_image_result){
					$product_image_row = @mysql_fetch_array($product_image_result);
					
					$product_image = $product_image_row['image'];
					$alt = $product_image_row['image_alt'];
				}
				
				if(user_in_sf($g_sess->get_var("user"), 0, 0, $row['product_id'])){
					$liked = "liked";
				} else {
					$liked = "";
				}
				
				$output.= '<li>
								<a href="/product/' . seo_friendly($row['product']) . '/' . $row['product_id'] . '/" title="' . $row['product'] . '">
									<img src="http://dev.restylesource.com/' . $photo_path . $product_image .  '" width=135 alt="' . $alt . '" />
								</a>
								<a href="#" class="add-to-sf ' . $liked . '" data-type="product" data-id="' . $row['product_id'] . '" title="Add to Style File">Add to Style File</a>
								<h3>' . $row['product'] . '</h3>';
								
				if($is_source > 0){
									
					$output.= '<form class="ajax-form" method="post">
									<input type="hidden" id="product-id" value="' . $row['product_id'] . '" />';
					
					if($row['carry'] > 0){
						$label = ($is_source==1) ? 'I no longer carry' : 'I am no longer a source for this style';
					
						$output.= '<label for="remove">
										<input type="radio" class="remove" name="' . $row['product_id'] . '" />
										<p>' . $label . '</p>
									</label>';
					} else if($is_source==1) {						
											
						$output.= '<label for="i-carry">
										<input type="radio" class="i-carry" name="' . $row['product_id'] . '" />
										<p>I have this product</p>
									</label>
									<label for="i-carry-similar">
										<input type="radio" class="i-carry-similar" name="' . $row['product_id'] . '" />
										<p>I carry this line</p>
									</label>';
									
					} else if ($is_source==2){

						$output.= '<label for="i-carry">
										<input type="radio" class="style-source" name="' . $row['product_id'] . '" />
										<p>I AM A SOURCE FOR THIS STYLE</p>
									</label>';
									
					
					}				
					
					$output.= '<input type="submit" class="ajax-submit ' . $discontinued_class . '" value="Submit" class="button primary" />
								</form>' . $discontinued;
					//<a href="/ajax/ajax-discontinued.php" class="discontinue">X</a>
				} else {
					$output.= '';
				}				
								
				$output.= '</li>';
							
				$i++;			
			}
			
			$output.= "</ul>";
		}
	} else if ($search_type == "" || $search_type == "Sources" || $search_type == "Manufacturers") {
	
		if($search_type == "Sources"){
			$source_types = array(3);
		} else {
			$source_types = array(4);
		}
	
		$sql = user_search_adv($source_id, $category_id, $_REQUEST['style'], $_REQUEST['state'], $_REQUEST['keyword'], $source_types, $sort);
	
		$pager = new PS_Pagination($conn, $sql, $numPerPage, 5, $search_qs);
		
		$source_result = $pager->paginate();
	
		$output = '<ul>';
	
		while($source_row = @mysql_fetch_array($source_result)){
			
			if($i>0 && $i % 6==0){
				$output.= '</ul><ul>';
	
				$j++;
		
				if($j==$row_limit)
					break;
			}
			
			if(!file_exists($_SERVER['DOCUMENT_ROOT'].$source_image_directory . $source_row[0] . '_thumb.jpeg') && file_exists($_SERVER['DOCUMENT_ROOT'] . $source_image_directory . $source_row[0] . '_main.jpeg')){
				image_crop($_SERVER['DOCUMENT_ROOT'] . $source_image_directory . $source_row[0] . '_main.jpeg', $_SERVER['DOCUMENT_ROOT'] . $source_image_directory . $source_row[0] . '_thumb.jpeg', 135, 150, 100);
			}
			
			if(user_in_sf($g_sess->get_var("user"), 0, $source_row[0], 0)){
				$liked = "liked";
			} else {
				$liked = "";
			}
			
			$output.= '<li>
								<a href="/sources/' . seo_friendly($source_row['company']) . '/' . $source_row[0] . '/" title="' . $source_row['company'] . '">
									<img src="' . $source_image_directory . $source_row[0] . '_thumb.jpeg' . '" alt="' . $source_row['main_alt'] . '" />
								</a>
								<a href="#" class="add-to-sf ' . $liked . '" data-type="source" data-id="' . $source_row[0] . '" title="Add to Style File">Add to Style File</a>';
			
			if($source_row['company'])					
				$output.= '<h3>' . $source_row['company'] . '</h3>';
			
			if($source_row['city'] || $source_row['state'])	
				$output.= '<h4>' . $source_row['city'] . ', ' . $source_row['state'] . '</h4>';
				
				$output.= '</li>';
			
			$i++;
		}
	
		$output.= '</ul>';
	
	} else if ($search_type == "Inspirations"){
	
		$sql = inspiration_search_adv($inspiration_id, $_REQUEST['state'], $_REQUEST['keyword'], $sort);
	
		$pager = new PS_Pagination($conn, $sql, $numPerPage, 5, $search_qs);
		
		$inspiration_result = $pager->paginate();
	
		$output = '<ul>';
	
		while($row = @mysql_fetch_array($inspiration_result)){
			
			if($i>0 && $i % 6==0){
				$output.= '</ul><ul>';
	
				$j++;
		
				if($j==$row_limit)
					break;
			}
			
			if(!file_exists($_SERVER['DOCUMENT_ROOT'].'/'.$inspiration_image_directory . $row[0] . '_search_thumb.jpg') && file_exists($_SERVER['DOCUMENT_ROOT'] . '/'.$inspiration_image_directory . $row[0] . '_hero.jpg')){
				image_crop($_SERVER['DOCUMENT_ROOT'] .'/'. $inspiration_image_directory . $row[0] . '_hero.jpg', $_SERVER['DOCUMENT_ROOT'] .'/'. $inspiration_image_directory . $row[0] . '_search_thumb.jpg', 135, 150, 100);
			}
			
			if(user_in_sf($g_sess->get_var("user"), $row['page_id'], 0, 0)){
				$liked = "liked";
			} else {
				$liked = "";
			}
			
			//echo("<pre>");
			//print_r($row);
			
			$output.= '<li>
								<a href="/inspiration/' . seo_friendly($row['inspiration']) . '/' . seo_friendly(sub_inspiration_name($row['sub_inspiration_id'])) . '/' . seo_friendly($row['page_title']) . '/' . $row['page_id'] . '/" title="' . $source_row['company'] . '">
									<img src="/' . $inspiration_image_directory . $row['page_id'] . '_search_thumb.jpg' . '" alt="' . $source_row['main_alt'] . '" />
								</a>
								<a href="#" class="add-to-sf ' . $liked . '" data-type="inspiration" data-id="' . $row['page_id'] . '" title="Add to Style File">Add to Style File</a>
								<h3>' . $row['page_title'] . '</h3>
					 </li>';
			
			$i++;
		}
	
		$output.= '</ul>';		
	
	}

	if($output == "<ul></ul>" || $output==""){
		$output = "<p>No results found.  Please try a different search.</p>";
	} else {
		$nav = $pager->renderFirst() . $pager->renderPrev() . $pager->renderNav("<li>", "</li>") . $pager->renderNext() . $pager->renderLast();
	}

?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html lang="en-us" class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>    <html lang="en-us" class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>    <html lang="en-us" class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en-us" class="no-js"> <!--<![endif]-->
<head>

	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width" />

	<title><? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/title.php'); ?> | Search Results | <?if($search_criteria){?><?=$search_criteria?><?}?></title>

	<!-- 
	CSS
	-->
	<!--[if !IE 6]><!-->
	<link rel="stylesheet" media="screen, projection" href="/assets/css/main.css" />
	<!--<![endif]-->
	<!--[if lte IE 6]><link rel="stylesheet" href="http://universal-ie6-css.googlecode.com/files/ie6.1.1.css" media="screen, projection"><![endif]-->

	<!--
	FAVICON
	--> 
	<link rel="shortcut icon" href="/assets/images/favicon.gif" type="image/gif" /> 

	<!-- 
	HEAD SCRIPTS
	--> 
	<script src="/assets/js/modernizr-2.5.3.js"></script>

</head>

<body class="search inspiration logged-in">

	<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'); ?>

	<div id="wrapper-page">

		<header>
			<h1>Search Results</h1>
			<h2>
				<?if($search_criteria){?>
				<span>Search Criteria &gt; <?=$search_criteria?></span>
				<?}?>
			</h2>

			<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/social.php'); ?>
			
			<select id="search-sort" name="search" class="uniform">
				<option>Sort by</option>
				<!--<option value="Products" <?if($search_type == "Products") echo("selected=\"selected\"")?>>Products</option>-->
				<!--<option value="Sources" <?if($search_type == "Sources") echo("selected=\"selected\"")?>>Sources</option>-->
				<option value="A-Z" <?if($sort == "A-Z") echo("selected=\"selected\"")?>>A-Z</option>
				<option value="Category" <?if($sort == "Category") echo("selected=\"selected\"")?>>Category</option>
				<?if($search_type=="Products"){?>
				<option value="Style" <?if($sort == "Style") echo("selected=\"selected\"")?>>Style</option>
				<option value="Price" <?if($sort == "Price") echo("selected=\"selected\"")?>>Price</option>
				<?}?>
			</select>
			
		</header>
		
		<div id="adv-search">
		<div class="fl"><a class="advsearch" href="/ajax/ajax-advanced-search.php?search_type=<?=$search_type?>">ADVANCED SEARCH &gt;</a></div>
		<div class="fr">SHOW <a class="<?if($row_limit==5){ echo("active"); }?>" href="javascript: limit=5; $('#search-sort').trigger('change');">30</a> <a class="<?if($row_limit==15){ echo("active"); }?>" href="javascript: limit=15; $('#search-sort').trigger('change');">90</a> <a class="<?if($_REQUEST['limit']=='ALL'){ echo("active"); }?>" href="javascript: limit='ALL'; $('#search-sort').trigger('change');">ALL RESULTS</a></div>
		</div>

		<section id="get-the-look" role="main">
			
				<h2>SHOW &gt; <a class="<?if($search_type=="Sources"){ echo("active"); }?>" href="javascript: search_type='Sources'; $('#search-sort').trigger('change');">SOURCES</a> / <a class="<?if($search_type=="Products" || $search_type==""){ echo("active"); }?>" href="javascript: search_type='Products'; $('#search-sort').trigger('change');">PRODUCTS</a> / <a class="<?if($search_type=="Inspirations"){ echo("active"); }?>" href="javascript: search_type='Inspirations'; $('#search-sort').trigger('change');">INSPIRATIONS</a> <?if( $g_sess->get_var("inventory_ind") >= 1 || in_array($g_sess->get_var("systemTeam"), array('admin'))){?> / <a class="<?if($search_type=="Manufacturers"){ echo("active"); }?>" href="javascript: search_type='Manufacturers'; $('#search-sort').trigger('change');">MANUFACTURERS</a><?}?></h2>
				<?=$output?>
				
		</section> <!-- // [role="main"] -->
			<ul id="search-nav-bar" style="list-style:none"><?=$nav?></ul>
	</div> <!-- // #wrapper-page -->
	<a id="discontinued" href="/ajax/ajax-discontinued.php" class="discontinue"></a>
	<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'); ?>

	<!--
	BODY JS
	-->
	<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/js_includes.php'); ?>
	

	<script src="/assets/js/input-placeholder.js"></script>

	
	<script>
	
		var search_type = "<?=$search_type?>";
		var search = "<?=$_REQUEST['search']?>";	
		var source = <?=MakeNullsZeros($source_id);?>;
		var category_id = <?=MakeNullsZeros($category_id);?>;
		var inspiration = <?=MakeNullsZeros($inspiration_id);?>;
		var style = '<?=MakeNullsZeros($_REQUEST['style']);?>';
		var state = "<?=$_REQUEST['state']?>";
		var keyword = "<?=$_REQUEST['keyword']?>";
		
		var limit = '<?=$row_limit?>';
	
		var form = '';
		var data = '';
		var relationship = '';
	
		$('select.uniform').uniform();
		
		$('#search-sort').change(function() {
			//alert($('#search-sort').val());
			
			var link = 'search-results.php?search_type=' + search_type + '&sort='+$('#search-sort').val() + '&limit=' + limit;

			if((search_type == "Sources" || search_type == "Manufacturers") && source>0){
				link+= '&source=' + source;
			}

			if(search_type=="Products" && category_id>0){
				link+= '&category=' + category_id;
			}
			
			if(inspiration && search_type=="Inspirations")
				link+= '&inspiration=' + inspiration;
			
			if(keyword){
				link+= '&keyword=' + keyword;
			}
			
			if(state)
				link+= '&state=' + state;	

			top.location = link;
		});
		
		// Advanced Search
		$(".advsearch").dialogAjax({
			dialogClass: "advanced-search",
			buttons: {
				Submit: {
					text: "Submit",
					"class":'button primary',
					click: function() {
						loadingIndicator.fadeIn(200);
						// Uncomment the following 2 lines once loading is done
						// $( this ).dialog( "close" );
						// dialog.remove();
						$('form.advSearch').submit();
					}
				}
			}
		});
		
		$('.ajax-submit').click(function () {
	
			if($(this).parent().find('.i-carry').attr("checked")){
				relationship = 'carried';
			} else if ($(this).parent().find('.i-carry-similar').attr("checked")){
				relationship = 'similar';
			} else if ($(this).parent().find('.style-source').attr("checked")){
				relationship = 'source';
			} else if ($(this).parent().find('.remove').attr("checked")){
				relationship = 'remove';
			} else {
				alert("Please select if you carry this product or a similar product to continue.");
				return false;
			}
			
			form = $(this).parent();
			
			
			data = 'function=source_product_relation&product_id=' + $(this).parent().find('#product-id').val() + '&relationship=' + relationship;    
			
			if($(this).hasClass('discontinued') && relationship != 'remove'){
				$("#discontinued").click();
			} else {
			
				$.ajax({
			
					url: "/ajax/ajax-functions.php", 
					type: "POST",
					data: data,     
					cache: false,

					success: function (html) {              
						if (html==1) {
							if(relationship=="remove"){
								form.html('<small>Removed from Inventory</small>');
							} else if(relationship == "source"){
								form.html('<small>Added as Source</small>');
							} else {                  
								form.html('<small>Added to Inventory</small>');
							}
						} else {
							alert('Sorry, unexpected error. Please try again later.');
						}               
					}       
				});		
	
			}
	
			return false;
		});
		
		// Discontinued
		$(".discontinue").dialogAjax({
			dialogClass: "discontinued",
			buttons: {
				Submit: {
					text: "YES, I HAVE THIS",
					"class":'button primary',
					click: function() {
						$.ajax({
							url: "/ajax/ajax-functions.php", 
							type: "POST",
							data: data,     
							cache: false,

							success: function (html) {              
								if (html==1) {
									if(relationship=="remove"){
										form.html('<small>Removed from Inventory</small>');
									} else if(relationship == "source"){
										form.html('<small>Added as Source</small>');
									} else {                  
										form.html('<small>Added to Inventory</small>');
									}
								} else {
									alert('Sorry, unexpected error. Please try again later.');
								}               
							}       
						});		

						
						thisDialog = $( this );
						thisDialog.dialog("close");
					}
				},
				Cancel: {
                    text: "Cancel",
                    "class":'button cancel',
                    click: function() {
						thisDialog = $( this );
						thisDialog.dialog("close");
                    }
                }
			}
		});
		
	</script>
</body>
</html>
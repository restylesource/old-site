<?

	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';	
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/logincheck.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/ps_pagination.php';
	
	$section = "style file";

	$row_width = 3;
	$row_height = 2;

	$numPerPage = $row_width * $row_height;

	$inspiration_image_path = '/inspiration-photos/';
	$source_image_path = "/source-images/";
	$product_image_path = "/" . lookup_config('product_image_path');

	$conn = @mysql_connect ($DB_HOST, $DB_USER ,$DB_PASSWD);
	mysql_select_db ($DB_DBNAME, $conn);

	$member_id = $_REQUEST['id'];
	$owner = ($member_id==$g_sess->get_var("user")) ? 1 : 0;

	if($member_id>0){

		$result = user_search($member_id);
		if($result){
			$row = mysql_fetch_array($result);
			$first_name = $row['fname'];		
		}

		if($_REQUEST['inspiration']>0){
			$sql = "SELECT * 
					FROM tbl_style_file tsf
					LEFT JOIN tbl_inspiration_pages tip ON
					tsf.inspiration_page_id = tip.page_id
					LEFT JOIN tbl_inspirations ti ON
					tip.inspiration_id = ti.inspiration_id
					WHERE tsf.user_id=" . $member_id. "
					AND tsf.inspiration_page_id>0
					ORDER BY date_added DESC";		
			$search_qs = "inspiration=" . $_REQUEST['inspiration'] . "&id=" . $member_id;	
		} else if ($_REQUEST['type']=="sources"){
			$sql = "SELECT * 
					FROM tbl_style_file tsf
					LEFT JOIN tbl_user tu ON
					tsf.source_id = tu.id
					WHERE tsf.user_id=" . $member_id. "
					AND tsf.source_id>0
					ORDER BY date_added DESC";
			$search_qs = "type=sources" . "&id=" . $member_id;
		} else if ($_REQUEST['type']=="products") {
			$sql = "SELECT * 
					FROM tbl_style_file tsf
					LEFT JOIN tbl_product tp ON
					tsf.product_id = tp.product_id
					WHERE tsf.user_id=" . $member_id. "
					AND tsf.product_id>0
					ORDER BY date_added DESC";
			$search_qs = "type=products" . "&id=" . $member_id;
		}

		//die($sql);

		$pager = new PS_Pagination($conn, $sql, $numPerPage, 5, $search_qs);
		$result = $pager->paginate();

		if($result){

			$output = "<ul>";
			
			while($row = mysql_fetch_array($result)){
			
				if($i == $numPerPage)
					break;
			
				if($i>0 && $i % $row_width == 0 ){
					$output.= "</ul><BR><ul>";
				}
			
				if($_REQUEST['inspiration']>0){
					$title = inspiration_name($_REQUEST['inspiration']);
					//$link = 'inspiration-type.php?id=';
					$link = '/inspiration/' . seo_friendly(inspiration_name($_REQUEST['inspiration'])) . '/' . seo_friendly(sub_inspiration_name($row['sub_inspiration_id'])) . '/' . seo_friendly($row['page_title']) . '/' . $row['inspiration_page_id'] . '/';
					$image_file = $inspiration_image_path . $row['page_id'] . "_thumb.jpg";
					$main_file = $inspiration_image_path . $row['page_id'] . "_hero.jpg";
					if(!file_exists($_SERVER['DOCUMENT_ROOT'] . $image_file) && file_exists($_SERVER['DOCUMENT_ROOT'] . $main_file)){
						image_crop($_SERVER['DOCUMENT_ROOT'] . $main_file, $_SERVER['DOCUMENT_ROOT'] . $image_file, 260, 200, 100);
					}
					$sf_array = array($row[0], $row['inspiration_id'], $row['page_title'], date('m-d-y', strtotime($row['date_added'])), $image_file);		
				} else if ($_REQUEST['type']=="sources"){
					$title = "Sources";
					//$link = 'sources.php?id=';
					$link = '/sources/' . seo_friendly($row['company']) . '/' . $row['source_id'] . '/';
					//die($row['source_id'] );
					$image_file = $source_image_path . $row['source_id'] . "_main_thumb.jpeg";
					$main_file = $source_image_path . $row['source_id'] . "_main.jpeg";
					if(!file_exists($_SERVER['DOCUMENT_ROOT'] . $image_file) && file_exists($_SERVER['DOCUMENT_ROOT'] . $image_file)){
						image_crop($_SERVER['DOCUMENT_ROOT'] . $main_file, $_SERVER['DOCUMENT_ROOT'] . $image_file, 260, 200, 100);
					}
					$sf_array = array($row[0], $row['source_id'], $row['company'], $row['date_added'], $image_file);
				} else if ($_REQUEST['type']=="products") {
					$title = "Products";
					//$link = 'product-detail.php?id=';
					$link = '/product/' . seo_friendly($row['product']) . '/' . $row['product_id'] . '/';
					$main_file = $product_image_path . product_first_image($row['product_id']);
					$image_file = substr($main_file, 0, -4) . "_thumb.jpg";
					if(!file_exists($_SERVER['DOCUMENT_ROOT'] . $image_file) && file_exists($_SERVER['DOCUMENT_ROOT'] . $main_file)){
						image_crop($_SERVER['DOCUMENT_ROOT'] . $main_file, $_SERVER['DOCUMENT_ROOT'] . $image_file, 260, 200, 100);
					}
					$sf_array = array($row[0], $row['product_id'], $row['product'], $row['date_added'], $image_file);
				}
				/*
				echo("<pre>");
				print_r($sf_array);
				die();
				*/
				
				$delete = ($owner) ? '<a href="/ajax/ajax-remove.php?id=' . $sf_array[0] . '" data-id="' . $sf_array[0] . '" class="remove">Remove</a>' : ''; 
				
				$output.= '<li id="' . $sf_array[0] . '">
								<time>' . $sf_array[3] . '</time>
								' . $delete . '
								<a href="' . $link . '" alt="' . $sf_array[2] . '">
									<img src="http://www.restylesource.com' . $sf_array[4]. '" />
									<h2>' . $sf_array[2] . '</h2>
								</a>
							</li>';
				
				
				$i++;
			}
			
			
			$output.= "</ul>";
		
		}

		if($output == "<ul></ul>"){
			$output = "<p>No Results Found.  Please try a different search.</p>";
		} else {
			$nav = $pager->renderFirst() . $pager->renderPrev() . $pager->renderNav("<li>", "</li>") . $pager->renderNext() . $pager->renderLast();
		}

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

	<title><?=$first_name?>'s Style File | <? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/title.php'); ?></title>

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

<body class="sf logged-in">

	<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'); ?>

	<div id="wrapper-page">

		<header>
			<h1><?=$first_name?>'s Style File</h1>

			<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/social.php'); ?>

			<!-- <select id="stylefile-sort" class="uniform">
				<option>Sort by</option>
				<option>Something</option>
				<option>Something</option>
			</select> -->
		</header>

		<section role="main">
		<div class="grid-categories">
			<h2><?=$title?></h2>
			<?=$output?>
		</div>
		<ul id="search-nav-bar" style="list-style:none"><?=$nav?></ul>
		</section> <!-- // [role="main"] -->

	</div> <!-- // #wrapper-page -->
	
	<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'); ?>

	<!--
	BODY JS
	-->
	<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/js_includes.php'); ?>
	
	<script src="/assets/js/input-placeholder.js"></script>
	
	<script>
		$('select.uniform').uniform();

		// Remove item
		$(".remove").dialogAjax({
			width: 550,
			buttons: {
				Cancel: {
					text: "Cancel",
					"class":'button primary',
					click: function() {
						//loadingIndicator.fadeIn(200);
						// Uncomment the following 2 lines once loading is done
						$( this ).dialog( "close" );
						dialog.remove();
					}
				},
				Remove: {
					text: "Remove",
					"class":'button primary',
					click: function() {
						loadingIndicator.fadeIn(200);
						
						var id = $('#style_remove_id').val();
						var thisDialog = $( this );
	
						$.ajax({
						  url: '/ajax/ajax-functions.php',
						  type: "POST",
						  data: { function: "remove_sf", id: id },
						  success: function(data) {
							if(data == 1){
								thisDialog.dialog( "close" );
								$('#'+id).fadeOut(200);
							}	
						  },
						  error: function(xhr, ajaxOptions, thrownError){
							if(xhr.status == 401){
								$("#login-link").click();
							}
						  }
						});
						
						// Uncomment the following 2 lines once loading is done
						// $( this ).dialog( "close" );
						// dialog.remove();
					}
				}
			}
		});
	</script>

</body>
</html>
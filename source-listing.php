<?

	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';	
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/logincheck.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/ps_pagination.php';
	
	$section = "sources";

	$row_limit = ($_REQUEST['limit']) ? $_REQUEST['limit'] : 5; 

	$source_image_directory = "/source-images/";
	$change_location_link = "/ajax/ajax-location.php";
	$state_name = state_name($_REQUEST['search_state']);
	$source_id = lookup_config('featured_source_id');

	$products_width  = 6;

	$conn = @mysql_connect ($DB_HOST, $DB_USER ,$DB_PASSWD);
	mysql_select_db ($DB_DBNAME, $conn);

	$numPerPage = ($_REQUEST['numPerPage']) ? $_REQUEST['numPerPage'] : ($row_limit * $products_width);
	
	if($numPerPage==0)
		$numPerPage = $row_limit * $products_width;

	if($source_id){
	
		if(user_in_sf($g_sess->get_var("user"), 0, $source_id, 0))
			$liked_featured = "liked";
	
		$retailer_result = user_search($source_id, '',0, '','','', '');
	
		if($retailer_result){
			$row = @mysql_fetch_array($retailer_result);
			
			$company = $row['company'];	
			$location = $row['city'] . ", " . $row['state'];
			$main_alt = $row['main_alt'];
			$meta_title = $row['meta_title'];
			$description = $row['description'];
			$phone = $row['phone'];
			$email = $row['email'];
			$address = $row['address1'];
			$city = $row['city'];
			$state = $row['state'];
			$zip = $row['zip'];
			$website = $row['website'];
			$twitter = $row['twitter'];
			$facebook = $row['facebook'];
			
			$main_img = $source_image_directory . $source_id . "_main.jpeg";
			$featured_img = $source_image_directory . $source_id . "_featured.jpeg";
			
			if(!file_exists($_SERVER['DOCUMENT_ROOT'].$featured_img)){
				image_crop($_SERVER['DOCUMENT_ROOT'] . $main_img, $_SERVER['DOCUMENT_ROOT'] . $featured_img, 471, 318, 100);
			}
				
		}
	}

	if($_GET['id'] > 0 && int_ok($_GET['id'])){
		$id = $_GET['id'];
		$subid = $_GET['subid'];
		$source_type_result = sources_lookup($_GET['id']);
		$row = @mysql_fetch_array($source_type_result);	
		$source = $row['source'];
		$source_type = "Sources";
		
		$is_source = (in_array($g_sess->get_var("systemTeam"), array('retailer','manufacturer'))) ? 1 : 0;
		
		if($is_source){
			$search_type = array(3,4);
		} else {
			$search_type = array(3);
		}
		
		if($subid>0){
			$sub_source_name = sub_sources_name($id, $subid);
		}
			
	} else if ($_GET['id'] && $_GET['id'] == "partners"){
		$search_type = 3;
		//$state_location = '';
		$source_type = "Sources";
	} else if ($_GET['id'] && $_GET['id'] == "manufacturers"){
		$search_type = 4;
		//$state_location = '';
		$source_type = "Manufacturers";
	} else {
		$search_type = array(3,4);
	}

	//Build QuerySring Array
	if($_REQUEST['id']){
		$query_array[] = "id=" . $_REQUEST['id'];
	}

	if($_REQUEST['subid']){
		$query_array[] = "subid=" . $_REQUEST['subid'];
	}
	
	if($_REQUEST['search_state']){
		$query_array[] = "search_state=" . $_REQUEST['search_state'];
	}

	if(is_array($query_array) && count($query_array))
		$search_qs = implode("&", $query_array);

	$sql = user_search(0, '', $search_type, 0, $_REQUEST['search_state'], $id, array('active', 'pending'), $subid, 'sql');

	$pager = new PS_Pagination($conn, $sql, $numPerPage, 5, $search_qs);
	
	$source_result = $pager->paginate();

	$look_output.= '<ul>';

	$i = 0;

	while($source_row = @mysql_fetch_array($source_result)){
	
		$look_output.= '';
		
		if($i>0 && $i % $products_width==0){
			$look_output.= '</ul><ul>';
		}
		
		if(!file_exists($_SERVER['DOCUMENT_ROOT'].$source_image_directory . $source_row[0] . '_thumb.jpeg') && file_exists($_SERVER['DOCUMENT_ROOT'] . $source_image_directory . $source_row[0] . '_main.jpeg')){
			image_crop($_SERVER['DOCUMENT_ROOT'] . $source_image_directory . $source_row[0] . '_main.jpeg', $_SERVER['DOCUMENT_ROOT'] . $source_image_directory . $source_row[0] . '_thumb.jpeg', 135, 150, 100);
		}
		
		if(user_in_sf($g_sess->get_var("user"), 0, $source_row[0], 0)){
			$liked = "liked";
		} else {
			$liked = "";
		}
		
		$look_output.= '<li>
							<a href="/sources/' . seo_friendly($source_row['company']) . '/' . $source_row[0] . '/" title="' . $source_row['company'] . '">
								<img src="http://dev.restylesource.com' . $source_image_directory . $source_row[0] . '_thumb.jpeg' . '" alt="' . $source_row['main_alt'] . '" />
							</a>
							<a href="#" class="add-to-sf ' . $liked . '" data-type="source" data-id="' . $source_row[0] . '" title="Add to Style File">Add to Style File</a>';
		
		if($source_row['company'])					
			$look_output.= '<h3>' . $source_row['company'] . '</h3>';
		
		if($source_row['city'] || $source_row['state'])	
			$look_output.= '<h4>' . $source_row['city'] . ', ' . $source_row['state'] . '</h4>';
			
			$look_output.= '</li>';
		
		$i++;
	}

	$look_output.= '</ul>';

	if($look_output == "<ul></ul>" || $look_output==""){
		$look_output = "<p>No results found.  Please try a different search.</p>";
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

	<title><? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/title.php'); ?> | Sources | <?=$source?><?if($sub_source_name){?> | <?=$sub_source_name?><?}?></title>

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

<body class="source sources logged-in">

	<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'); ?>

	<div id="wrapper-page">

		<header>
			<h1><?=$state_name?></h1>
			<h2><?=$source?> <?if($sub_source_name){?><span><?=$sub_source_name?></span><?}?></h2>
			

			<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/social.php'); ?>
			
		</header>

		<section id="get-the-look">
			<h2><span><?=$source_type?></span> <a href="<?=$change_location_link?>" class="change-location">Change location ></a></h2> 
			
			<?=$look_output?>
		</section>
		<ul id="search-nav-bar" style="list-style:none"><?=$nav?></ul>
		

	</div> <!-- // #wrapper-page -->

	<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'); ?>

	<!--
	BODY JS
	-->
	<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/js_includes.php'); ?>
	
	<script src="/assets/js/input-placeholder.js"></script>
	
	
	<script>
		$('select.uniform').uniform();

	</script>


</body>
</html>
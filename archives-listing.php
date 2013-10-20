<?

	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';	
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/logincheck.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/ps_pagination.php';
	
	$section = "archives";

	$inspiration_image_path = 'http://dev.restylesource.com/inspiration-photos/';

	$row_width  = 3;
	$row_length = 5;

	$numPerPage = $row_width * $row_length;

	$conn = @mysql_connect ($DB_HOST, $DB_USER ,$DB_PASSWD);
	mysql_select_db ($DB_DBNAME, $conn);

	if($conn && $_GET['inspiration']){
	
		$title = inspiration_name($_GET['inspiration']);
	
		$sql = "SELECT * 
				FROM tbl_inspiration_pages
				WHERE page_status='active' AND inspiration_id=" . $_GET['inspiration'] ."
				ORDER BY modified_date DESC";
				
		$pager = new PS_Pagination($conn, $sql, $numPerPage, 5, "inspiration=" . $_GET['inspiration']);
		
		$result = $pager->paginate();
		
		if($result){
			$i = 0;
			$output = "<ul>";
			
			while($row = mysql_fetch_array($result)){
			
				if($i == $numPerPage)
					break;
			
				if($i>0 && $i % $row_width == 0 ){
					$output.= "</ul><BR><ul>";
				}
	
				$image_file = $inspiration_image_path . $row['page_id'] . "_thumb.jpg";
				$main_file = $inspiration_image_path . $row['page_id'] . "_hero.jpg";
				if(!file_exists($_SERVER['DOCUMENT_ROOT'] . $image_file) && file_exists($_SERVER['DOCUMENT_ROOT'] . $main_file)){
					image_crop($_SERVER['DOCUMENT_ROOT'] . $main_file, $_SERVER['DOCUMENT_ROOT'] . $image_file, 260, 200, 100);
				}
	
				$output.= '<li>
								<time>' . date("m d y", strtotime($row['modified_date'])) . '</time>
								<a href="/inspiration/' . seo_friendly($title) . '/' . seo_friendly(sub_inspiration_name($row['sub_inspiration_id'])) . '/' . seo_friendly($row['page_title']) . '/' . $row['page_id'] . '/" alt="' . $row['page_title'] . '">
									<img src="' . $image_file . '" />
									<h2>' . $row['page_title'] . '</h2>
								</a>
							</li>';
	
				$i++;
			
			}
		}
		
		if($output == "<ul></ul>"){
			$output = "<p>No Results Found.  Please try a different search.</p>";
		} else {
			$nav = $pager->renderFirst() . $pager->renderPrev() . $pager->renderNav("<li>", "</li>") . $pager->renderNext() . $pager->renderLast();
		}
				
	} else {
		my_redirect('/archives.php');
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

	<title><? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/title.php'); ?> | Archives | <?=$title?></title>

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
			<h1>Archives</h1>

			<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/social.php'); ?>
			
			<a href="archives.php" class="back">Back to Archives ></a>
			<br />

			<!--<select id="stylefile-sort" class="uniform">
				<option>Sort by</option>
				<option>Most Recent</option>
				<option>Oldest</option>
				<option>9 Per Page</option>
				<option>18 Per Page</option>
				<option>45 Per Page</option>
				<option>90 Per Page</option>
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
		$(".remove").attr("href", "/ajax/ajax-remove.html").dialogAjax({
			width: 550,
			buttons: {
				Cancel: {
					text: "Cancel",
					"class":'button primary',
					click: function() {
						loadingIndicator.fadeIn(200);
						// Uncomment the following 2 lines once loading is done
						// $( this ).dialog( "close" );
						// dialog.remove();
					}
				},
				Remove: {
					text: "Remove",
					"class":'button primary',
					click: function() {
						loadingIndicator.fadeIn(200);
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
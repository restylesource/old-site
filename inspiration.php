<?php

	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';	
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/logincheck.php';

	$section = "inspiration";

	$image_base = '/home/restyle/public_html';
	$image_path = 'http://www.restylesource.com/inspiration-photos/';

	//Lookup categories
	
	if($_REQUEST['id']>0){
		$result = sub_inspiration_lookup($_REQUEST['id']);
	} else {
		$result = inspiration_search();
	}
	
	$output.= '<div class="grid-categories"><ul>';
	
	$i = 0;
	
	while($row = @mysql_fetch_array($result)){
		
		//lookup most recent inspiration in category
		$page_result = inspiration_page_recent($row['inspiration_id'], $row['sub_inspiration_id']);
		if($page_result) $page_row = mysql_fetch_array($page_result);
	
		if($page_row && $page_row['page_id']>0){

			
			if($i>0 && $i % 3==0){
				//echo($i % 3 . "<BR>");
				$output.= '</ul></div><div class="grid-categories"><ul>';
			}

			if($_REQUEST['id']>0){
				$output.= '<li>
							<a href="/inspiration/' . seo_friendly($row['inspiration']) . '/' . seo_friendly($row['sub_inspiration']) . '/' . $page_row['sub_inspiration_id'] . '/">
								<img src="' . $image_path . $page_row['page_id'] . '_thumb.jpg" />
								<h2>' . $row['sub_inspiration'] . '</h2>
							</a>
						</li>';
			} else {
				$output.= '<li>
							<a href="/inspiration/' . seo_friendly($row['inspiration']) . '/' . $row['inspiration_id'] . '/">
								<img src="' . $image_path . $page_row['page_id'] . '_thumb.jpg" />
								<h2>' . $row['inspiration'] . '</h2>
							</a>
						</li>';
			}
		
			$i++;
		}
	}


	$output.= '</ul></div>';

?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html lang="en-us" class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>    <html lang="en-us" class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>    <html lang="en-us" class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en-us" class="no-js"> <!--<![endif]-->
<head>

	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width" />

	<title><? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/title.php'); ?> | Inspirations</title>

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

<body class="inspiration logged-in">

	<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'); ?>

	<div id="wrapper-page">

		<header>
			<h1>Inspiration</h1>

			<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/social.php'); ?>
			
		</header>

		<section role="main">

			<div class="block marquee no-image">
				<p> Find inspiring ideas for everything from kitchens &amp; gardens to trends & entertaining and all the little things in between.</p>
			</div>

			<?=$output?>

		</section> <!-- // [role="main"] -->

	</div> <!-- // #wrapper-page -->

	<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'); ?>
	
	
	<!--
	BODY JS
	-->
	<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/js_includes.php'); ?>


</body>
</html>
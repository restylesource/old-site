<?php
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/logincheck.php';

	$section = "inspiration";

	$image_base = '/home/restyle/public_html';
	$image_path = 'http://dev.restylesource.com/inspiration-photos/';
	$page_directory = $_SERVER['DOCUMENT_ROOT'] . '/assets/chunks/';

	//if($_GET['id']<1){ my_redirect('/inspiration.php');}


	$sub_inspiration_result = sub_inspiration_lookup(0, $_GET['id']);

	if($sub_inspiration_result){
		$row = mysql_fetch_array($sub_inspiration_result);
		$inspiration = $row['inspiration'];
		$sub_inspiration = $row['sub_inspiration']; 
	}

	$page_result = inspiration_page_recent(0, $_GET['id']);
	if(!$page_result)
		my_redirect('/inspiration.php');
	$output.= '<div class="grid-categories"><ul>';

	$i = 0;

	while($page_row = mysql_fetch_array($page_result)){

		if($i==0){
			$hero_image = $image_path . $page_row['page_id'] . '_hero.jpg';;
			$hero_title = $page_row['page_title'];
			$hero_id = $page_row['page_id']; 
			if(user_in_sf($g_sess->get_var("user"), $hero_id))
			$liked = "liked";
		} else {
			if(($i==4) || ($i> 4 && $i % 3==0)){
				$output.= '</ul></div><div class="grid-categories"><ul>';
			}
			$output.= '<li>
							<a href="/inspiration/' . seo_friendly($inspiration) . '/' . seo_friendly($sub_inspiration) . '/' . seo_friendly($page_row['page_title']) . '/' . $page_row['page_id'] . '/">
								<img src="' . $image_path . $page_row['page_id'] . '_thumb.jpg" />
								<h2>' . $page_row['page_title'] . '</h2>
							</a>
						</li>';
		}

		$i++;
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

	<title><?=$inspiration?> Inspirations | <? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/title.php'); ?></title>

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
			<h2><?=$inspiration?></h2>

			<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/social.php'); ?>

			<a href="/inspiration.php" class="back">Back to Inspiration ></a>

		</header>

		<section role="main">

			<div class="block image get-the-look">
				<a href="/inspiration/<?=seo_friendly($inspiration)?>/<?=seo_friendly($sub_inspiration)?>/<?echo(seo_friendly($hero_title))?>/<?=$hero_id?>/">
					<img src="<?=$hero_image ?>" />
					<p><span><?=$hero_title?></span></p>
				</a>
				<a href="#" class="add-to-sf <?=$liked?>" data-type="inspiration" data-id="<?=$hero_id?>" title="Add to Style File">Add to Style File</a>
			</div>

			<?=$output?>

		</section> <!-- // [role="main"] -->

	</div> <!-- // #wrapper-page -->

	<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'); ?>

	<!--
	BODY JS
	-->
	<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/js_includes.php'); ?>

	<script>
		$('select.uniform').uniform();
	</script>


</body>
</html>

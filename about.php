<?

	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';

?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html lang="en-us" class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>    <html lang="en-us" class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>    <html lang="en-us" class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en-us" class="no-js"> <!--<![endif]-->
<head>

	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width" />

	<title><? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/title.php'); ?> | About Us</title>

	<!-- 
	CSS
	-->
	<!--[if !IE 6]><!-->
	<link rel="stylesheet" media="screen, projection" href="assets/css/main.css" />
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

<body class="registration logged-in">

	<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'); ?>

	<div id="wrapper-page">

		<header>
			<h1>REstyleSOURCE | About</h1>

			<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/social.php'); ?>
			
		</header>

		<section role="main">
		<h3>OUR MISSION</h3>
			<p>
			We are the national guide to local shops, businesses and restaurants. Our goal is to reconnect shoppers with the real people and real stores that built America.
			</p>
			<h3>ABOUT US</h3>
			<p>
			REstyleSOURCE was born from an easy concept: we wanted to answer the famous question, “Where did you find that?” After years in the design and retail industries, we wanted to share our passion on how to make your house a home. Whether it is a completely new style or just a new paint color, our website will guide you with trade secrets as well as the insider scoop on where to shop. 
			</p><p>
			REstyleSOURCE showcases real homes, shops & restaurants, designer spaces and inspirational places -- but we don't stop there,  we show you where to "get the look" you love and find featured items in a local store near you.
			</p><p>
			Our SOURCE directory is visually driven and is broken down into categories, making it easy to browse. REstyleSOURCE provides design inspiration and helps you find great shops and design resources within your community.  
			</p><p>
			If you’re planning a weekend getaway, our city guides highlight the best places to visit in our favorite cities across the country. Our style scouts are always on the prowl for the best home décor stores, the hippest bars and restaurants and fashion boutiques that will have you saving a little extra room in your suitcase.
			</p><p>
			Much like a mood board, REstyleSOURCE also lets you save your favorite products to your STYLE FILE. This feature allows you to browse our website while collecting all of your must have items and new places to visit. You can also share your STYLE FILE with friends.
			</p><p>
			Sign in, get comfortable and let REstyleSOURCE inspire every aspect of your life. 
			</p>
		</section> <!-- // [role="main"] -->

	</div> <!-- // #wrapper-page -->

	<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'); ?>

	<!--
	BODY JS
	-->
	<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/js_includes.php'); ?>
	
</body>
</html>
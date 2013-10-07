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

	<title><? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/title.php'); ?> | FAQs</title>

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

<body class="faqs logged-in">

	<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'); ?>

	<div id="wrapper-page">

		<header>
			<h1>HELP!</h1>
			<h2>Frequently Asked Questions</h2>

			<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/social.php'); ?>

		</header>

		<section role="main">
		<div class="block video">
		<? include_once($_SERVER['DOCUMENT_ROOT'] . '/faq/video.php'); ?>
	</div>
			<div class="faq-list">
<h2>Questions</h2>

<? include_once($_SERVER['DOCUMENT_ROOT'] . '/faq/faq.php'); ?>

			</div>
			<div id="more-help">
				<h2>Need More <span>help?</span></h2>
				<p><span>EMAIL ></span> <a href="mailto:info@restylesource.com">info@restylesource.com</a></p>
				<p><span>CALL  ></span> <b>[855] 80 STYLE</b></p>
				<p>&nbsp;</p>
				<h2>Suggestions <span>and</span><span>Feedback</span></h2>
				<form name="morehelp" method="post" onSubmit="return validateEvent()">
					<input type="text" id="email" name="email" placeholder="Your Email" />
					<textarea name="comments" placeholder="Suggestions/Inquiries"></textarea>
					<input type="submit" value="Submit" />
				</form>
			</div> <!-- / #suggest-event -->

		</section> <!-- // [role="main"] -->

	</div> <!-- // #wrapper-page -->

	<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'); ?>

	<!--
	BODY JS
	-->
	<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/js_includes.php'); ?>
	
	<script src="/assets/js/input-placeholder.js"></script>
	
	<script>
	
			function validateEvent(){
		
			var is_good = true;
			
			if($('#email').val() == ""){
				$('#email').addClass('error');
				is_good = false;
			} else {
				$('#email').removeClass('error');
			}
		
			return is_good;
		
		}
		
		$('select.uniform').uniform();
	</script>

</body>
</html>
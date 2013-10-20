<?

	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';	
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/logincheck.php';

	$section = "archives";

	$inspiration_image_path = 'http://dev.restylesource.com/inspiration-photos/';

	$result = page_search("active");
	
	if($result){
	
		$inspiration_array = array();	
	
		while($row = @mysql_fetch_array($result)){
			$image_file = $inspiration_image_path . $row['page_id'] . "_thumb.jpg";
			$main_file = $inspiration_image_path . $row['page_id'] . "_hero.jpg";
			if(!file_exists($_SERVER['DOCUMENT_ROOT'] . $image_file) && file_exists($_SERVER['DOCUMENT_ROOT'] . $main_file)){
				image_crop($_SERVER['DOCUMENT_ROOT'] . $main_file, $_SERVER['DOCUMENT_ROOT'] . $image_file, 260, 200, 100);
			}
					
			$inspiration_array[$row['inspiration_id']][] = array($row[0], $row['page_id'], $row['page_title'], $row['modified_date'], $image_file, $row['sub_inspiration_id']);
		}

		foreach ($inspiration_array as $key => $value){
		
			$output.= '<div class="grid-categories">
							<h2>' . inspiration_name($key) . '</h2>
							<ul>';
			
			$i = 0;
			
			foreach($value as $key2 => $value2){
				if($i==3)
					break;
			
				$output.= '<li id="' . $value2[0] . '">
								<time>' . date("m d y", strtotime($value2[3])) . '</time>
								<a href="/inspiration/' . seo_friendly(inspiration_name($key)) . '/' . seo_friendly(sub_inspiration_name($value2[5])) . '/' . seo_friendly($value2[2]) . '/' . $value2[1] . '/" alt="' . $value2[2] . '">
									<img src="' . $value2[4] . '" />
									<h2>' . $value2[2] . '</h2>
								</a>
							</li>';
				$i++;
			}
			
			if(count($value)>3){
				$more_link = '<a href="archives-listing.php?inspiration=' . $key . '" class="more">More ></a>';
			} else {
				$more_link = "";
			}
			$output.= '		</ul>
						' . $more_link . '	
						</div>';
				
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

	<title><? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/title.php'); ?> | Archives</title>

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
	<link rel="shortcut icon" href="<? echo $_SERVER['DOCUMENT_ROOT'] .  '/assets/images/favicon.gif'; ?>" type="image/gif" /> 

	<!-- 
	HEAD SCRIPTS
	--> 
	<script src="<? echo $_SERVER['DOCUMENT_ROOT'] .  '/assets/js/modernizr-2.5.3.js'; ?>"></script>

</head>

<body class="sf logged-in">

	<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'); ?>

	<div id="wrapper-page">

		<header>
			<h1>Archives</h1>

			<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/social.php'); ?>
<!--
			<select id="stylefile-sort" class="uniform">
				<option>Sort by</option>
				<option>Something</option>
				<option>Something</option>
			</select>
-->
		</header>

		<section role="main">

			<?=$output?>

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
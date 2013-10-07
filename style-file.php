<?

	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';	
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/logincheck.php';

	$section = "style file";

	$inspiration_image_path = '/inspiration-photos/';
	$source_image_path = "/source-images/";
	$product_image_path = "/" . lookup_config('product_image_path');

	if($g_sess->get_var("user")){

		list($first_name, $last_name) = explode(" ", $g_sess->get_var("name"));

		$style_result = user_style_file_lookup($g_sess->get_var("user"));
	
		if($style_result){
		
			$inspiration_array = array();
			$product_array = array();
			$source_array = array();
		
			while($row = @mysql_fetch_array($style_result)){
			
				if( $row['inspiration_id']>0){
			
					if(!is_array($inspiration_array[$row['inspiration_id']])){
						$image_file = $inspiration_image_path . $row['page_id'] . "_hero_thumb.jpg";
						$main_file = $inspiration_image_path . $row['page_id'] . "_hero.jpg";
						if(!file_exists($_SERVER['DOCUMENT_ROOT'] . $image_file) && file_exists($_SERVER['DOCUMENT_ROOT'] . $main_file)){
							image_crop($_SERVER['DOCUMENT_ROOT'] . $main_file, $_SERVER['DOCUMENT_ROOT'] . $image_file, 260, 200, 100);
						}
					
						$inspiration_array[$row['inspiration_id']][] = array($row[0], $row['page_id'], $row['page_title'], $row['date_added'], $image_file);
					}
					
				} else if($row['product']) {
					
					$main_file = $product_image_path . product_first_image($row['product_id']);
					$image_file = substr($main_file, 0, -4) . "_thumb.jpg";
					
					if(!file_exists($_SERVER['DOCUMENT_ROOT'] . $image_file) && file_exists($_SERVER['DOCUMENT_ROOT'] . $main_file)){
						image_crop($_SERVER['DOCUMENT_ROOT'] . $main_file, $_SERVER['DOCUMENT_ROOT'] . $image_file, 260, 200, 100);
					}
					$product_array[] = array($row[0], $row['product_id'], $row['product'], $row['date_added'], $image_file);
				
				} else if ($row['company']){
					$image_file = $source_image_path . $row['source_id'] . "_main_thumb.jpeg";
					$main_file = $source_image_path . $row['source_id'] . "_main.jpeg";
					if(!file_exists($_SERVER['DOCUMENT_ROOT'] . $image_file) && file_exists($_SERVER['DOCUMENT_ROOT'] . $main_file)){
						image_crop($_SERVER['DOCUMENT_ROOT'] . $main_file, $_SERVER['DOCUMENT_ROOT'] . $image_file, 260, 200, 100);
					}
					$source_array[] = array($row[0], $row['source_id'], $row['company'], $row['date_added'], $image_file);
				}
			
			}
		}
	
		$output = "";
	
		// LOOP INSPIRATION DATA
	
		foreach ($inspiration_array as $key => $value){
			/*	
			echo("<pre>");
			print_r($value);
			die();	
			*/		
			$inspiration_output.= '<div class="grid-categories">
									<h2>' . inspiration_name($key) . '</h2>
									<ul>';
			
			$i = 0;
			
			foreach($value as $key2 => $value2){
				if($i==3)
					break;
			
				//date_friendly($value2[3],0,1)
			
				$inspiration_output.= '<li id="' . $value2[0] . '">
										<time>' . '&nbsp;' . '</time>
										<a href="ajax/ajax-remove.php?id=' . $value2[0] . '" data-id="' . $value2[0] . '" class="remove">Remove</a>
										<a href="inspiration/type/' . $value2[1] . '/view.html" alt="' . $value2[2] . '">
											<img src="http://www.restylesource.com' . $value2[4] . '" />
											<h2>' . $value2[2] . '</h2>
										</a>
									</li>';
				$i++;
			}
			
			if(count($value)>3){
				$more_link = '<a href="style-file-list.php?inspiration=' . $key . '" class="more">More ></a>';
			} else {
				$more_link = "";
			}
			$inspiration_output.= '		</ul>
						' . $more_link . '	
						</div>';
				
		}
	
		// LOOP SOURCE DATA
		
		$i = 0;
	
		if(count($source_array))
			$source_output.= '<div class="grid-categories">
								<h2>Sources</h2>
								<ul>';
	
		foreach($source_array as $key => $value){
			
			if($i==3)
				break;
			
			//date_friendly($value[3],0,1)
			
			$source_output.= '<li id="' . $value[0] . '">
								<time>' . '&nbsp;' . '</time>
								<a href="ajax/ajax-remove.php?id=' . $value[0] . '" data-id="' . $value[0] . '" class="remove">Remove</a>
								<a href="/sources/' . seo_friendly($value[2]) . '/' . seo_friendly($value[1]) . '/" alt="' . $value[2] . '">
									<img src="http://www.restylesource.com' . $value[4]. '" />
									<h2>' . $value[2] . '</h2>
								</a>
							</li>';
			
			$i++;	
		}
	
		if(count($source_array)>3){
			$more_link = '<a href="style-file-list.php?type=sources" class="more">More ></a>';
		} else {
			$more_link = "";
		}
	
		if(count($source_array))
			$source_output.= '		</ul>
							' . $more_link . '	
							</div>';
	
		// LOOP PRODUCT DATA
	
		$i = 0;
	
		if(count($product_array))
			$product_output.= '<div class="grid-categories">
								<h2>Products</h2>
								<ul>';
	
		foreach($product_array as $key => $value){
			
			if($i==3)
				break;
			
			//date_friendly($value[3],0,1)
			
			$product_output.= '<li id="' . $value[0] . '">
									<time>' . '&nbsp;' . '</time>
									<a href="ajax/ajax-remove.php?id=' . $value[0] . '" data-id="' . $value[0] . '" class="remove">Remove</a>
									<a href="/product/' . seo_friendly($value[2]) . '/' . $value[1] . '/" alt="' . $value[2] . '">
										<img src="http://www.restylesource.com' . $value[4]. '" />
										<h2>' . $value[2] . '</h2>
									</a>
								</li>';
			
			$i++;	
		}
	
		if(count($product_array)>3){
			//$more_link = '<a href="style-file-list.php?type=products" class="more">More ></a>';
			$more_link = '<a href="style-file-list.php?type=products" class="more">More ></a>';
		} else {
			$more_link = "";
		}
	
		if(count($product_array))
			$product_output.= '		</ul>
								' . $more_link . '	
								</div>';
	}
	
	
	switch($_REQUEST['sort']){
	
		case 'source':
			$output =  $source_output . $product_output . $inspiration_output;
			break;
		case 'product':
			$output = $product_output . $source_output . $inspiration_output;
			break;
		default:
			$output = $inspiration_output . $source_output . $product_output;
			break;
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

<?if($g_sess->get_var("user")){?>
	<title><?=$first_name?>'s Style File | <? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/title.php'); ?></title>
<?} else {?>
	<title><?=$first_name?>Style File | <? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/title.php'); ?></title>
<?}?>
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

<body class="sf logged-in registration logged-in">

	<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'); ?>

	<div id="wrapper-page">
	<?if($g_sess->get_var("user")){?>
		<header>
			<h1><?=$first_name?>'s Style File</h1>

			<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/social.php'); ?>

			<select id="stylefile-sort" name="sort" onchange="changeSort(this.value)" class="uniform">
				<option value="">Sort by</option>
				<option value="inspiration" <?if($_REQUEST['sort'] == "inspiration") echo("selected")?>>Inspirations</option>
				<option value="source" <?if($_REQUEST['sort'] == "source") echo("selected")?>>Sources</option>
				<option value="product" <?if($_REQUEST['sort'] == "product") echo("selected")?>>Products</option>
			</select>
		</header>
		<section role="main">
			<?=$output?>
		</section>
	<?} else {?>
		<section role="main">
			
			<div id="discover-sf">
				<h2>Discover Style File <span>[ <a href="/registration.php">Registration required</a> ]</span></h2>
				<p>Click on any <span class="icon-sf-small">Style File</span>icon to save articles, images, and ideas to your own personal Style File. It's that easy.</p>
			</div>
			<img src="/assets/images/sf-set.png">
			

		</section> <!-- // [role="main"] -->
	<?}?>
	</div> <!-- // #wrapper-page -->
	
	<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'); ?>

	<!--
	BODY JS
	-->
	<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/js_includes.php'); ?>
	

	<script src="/assets/js/input-placeholder.js"></script>
	
	<script>
		$('select.uniform').uniform();

		function changeSort(val){
		
			top.location='style-file.php?sort='+val;
		}

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
						  url: 'ajax/ajax-functions.php',
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
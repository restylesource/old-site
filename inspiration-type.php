<?php

	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';	
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/logincheck.php';
	
	$section = "inspiration";

	$image_base = '/home/restyle/public_html';
	$image_path = 'http://www.restylesource.com/inspiration-photos/';

	$page_directory = $_SERVER['DOCUMENT_ROOT'] . '/assets/chunks/';
	
	
	if($_REQUEST['id'] < 1){
		my_redirect('/index.php');
	}	
	
	if($_POST){
		// Check for Hero Image Upload
		
		if($_POST['action']=="delete" && $_POST['page_block_id'] > 0){
			page_block_delete($_POST['page_block_id']);
		} else if ($_POST['action'] == "up" || $_POST['action'] == "down"){
			page_block_reorder($_POST['id'], $_POST['page_block_id'], $_POST['action']);
		}
		
		if($_FILES['image'] && $_POST['page_block_id'] < 1){
			
			if ($_FILES["image"]["error"] > 0){
				$error[] = $_FILES["image"]["error"];
			} else {
				$new_file_name = $_POST['id'] . "_hero.jpg";
				if(move_uploaded_file($_FILES["image"]["tmp_name"], $image_base . '/inspiration-photos/' . $new_file_name)){
					//success...create thumbnail
					/*
					$im = new imagick( $image_base . $image_path . $new_file_name );
					$crop = $im->clone();
					
					$crop->cropThumbnailImage( 260, 200 );
					$crop->writeImage( $image_base . $image_path . '_thumb.jpg' );
					*/
					
					//die($image_base . $image_path . $new_file_name . " - " . $image_base . $image_path . $_POST['id'] . '_thumb.jpg');
					
					image_crop($image_base . '/inspiration-photos/' . $new_file_name, $image_base . '/inspiration-photos/' . $_POST['id'] . '_thumb.jpg', 260, 200, 100);
					
				} else {
					$error[] = "Unable to save image, please try again.";
				}
			}
		}
		
		// Check for Hero Credit
		if($_POST['id'] > 0 && $_POST['page_block_id'] == "undefined"  ){
			page_hero_credit($_POST['id'], $_POST['credit'], $_POST['alt']);
		}
		
		
		// Check for add block
		if($_POST['block_type'] && $_POST['id'] > 0){
			page_block_add($_POST['id'], $_POST['block_type']);
		}
		
		// Check for block UPDATE
		if($_POST['page_block_id']){
		
			$result = page_blocks_lookup($_POST['id'], $_POST['page_block_id']);
			if($result){
				$row = mysql_fetch_array($result);
				$new_file_name = $row['image'];
			}
		
			// let's check for an image UPLOAD
			if($_FILES['image']){
				if ($_FILES["image"]["error"] > 0){
					$error[] = $_FILES["image"]["error"];
				} else {
					$new_file_name = $_POST['page_block_id'] . "_image.jpg";
					if(move_uploaded_file($_FILES["image"]["tmp_name"], $image_base . '/inspiration-photos/' . $new_file_name)){
						//success
					} else {
						$new_file_name = "";
						$error[] = "Unable to save image, please try again.";
					}
				}
			}
		
			page_block_update($_POST['page_block_id'], $_POST['header'], $_POST['content'], $_POST['content_more'], $_POST['website'], $new_file_name, $_POST['address'], $_POST['city'], $_POST['state'], $_POST['zip'], $_POST['hours1'], $_POST['hours2'], $_POST['phone'], $_POST['email'], $_POST['materials1'], $_POST['materials2'], $_POST['materials3'], $_POST['materials4'], $_POST['materials5'], $_POST['materials6'], $_POST['materials7'], $_POST['materials8'], $_POST['materials9'], $_POST['materials10'], $_POST['link1_title'], $_POST['link1_url'], $_POST['link2_title'], $_POST['link2_url'], $_POST['link3_title'], $_POST['link3_url'], $_POST['link4_title'], $_POST['link4_url'], $_POST['link5_title'], $_POST['link5_url'], $_POST['link6_title'], $_POST['link6_url'], $_POST['link7_title'], $_POST['link7_url'], $_POST['link8_title'], $_POST['link8_url'], $_POST['alt'], $_POST['credit']);
		}
		
		
		/*
		echo("<pre>");
		print_r($_POST);
		die();
		*/
		
		my_redirect($_SERVER['PHP_SELF']  . '?id=' . $_REQUEST['id']);
		
	}
	
	$page_id = $_REQUEST['id'];
	
	if($page_id){
	
		$page_result = page_lookup($page_id);
	
		if(user_in_sf($g_sess->get_var("user"), $page_id))
			$liked = "liked";
	
		if($page_result){
		
			$row = @mysql_fetch_array($page_result);
		
			$page_title = $row['page_title'];
			$inspiration = $row['inspiration'];
			$page_sub_title = $row['page_sub_title'];
			
			$meta_title = $row['meta_title'];
			$meta_keywords = $row['meta_keywords'];
			$meta_description = $row['meta_description'];
			
			// check for background image
			//$bg_image = $image_path . $page_id . "_bg.jpg";
			//$bg_image = (false === @file_get_contents($bg_image)) ? "" :  $bg_image;
			$bg_image = '';
			
			// check for hero image
			$hero_image = $image_path . $page_id . "_hero.jpg";		
			$hero_image = (false === @file_get_contents($hero_image)) ? "/assets/images/temp/image-main.jpg" : $hero_image;
			
			
			$hero_credit = $row['hero_credit'];
			$hero_alt = $row['hero_alt'];
			
			//Load blocks
			$block_result = page_blocks_lookup($page_id);
			
			while($block_row = @mysql_fetch_array($block_result)){
				
				switch($block_row['block_type_id']){
				
					case 1: // About
						$page_template = file_get_contents($page_directory . $block_row['chunk_template']);
						$page_template = str_replace("{page_block_id}", $block_row['page_block_id'], $page_template);
						$page_template = str_replace("{header}", $block_row['header'], $page_template);
						$page_template = str_replace("{content}", $block_row['content'], $page_template);
						$page_template = str_replace("{content_more}", $block_row['content_more'], $page_template);
						// if no more content, hide more link
						$display = ($block_row['content_more']=="") ? "display: none;" : "";
						$page_template = str_replace("{display}", $display, $page_template);
						$block_output.= $page_template;
						break;
					case 2: // About Person
						$page_template = file_get_contents($page_directory . $block_row['chunk_template']);
						$page_template = str_replace("{page_block_id}", $block_row['page_block_id'], $page_template);
						$page_template = str_replace("{header}", $block_row['header'], $page_template);
						$page_template = str_replace("{content}", $block_row['content'], $page_template);
						$page_template = str_replace("{website}", $block_row['website'], $page_template);
						$image = ($block_row['image'] && @file_get_contents($image_path . $block_row['image']) !== false ) ? $image_path . $block_row['image'] : "/assets/images/temp/image-about-person.jpg";
						$page_template = str_replace("{image}", $image, $page_template);
						$page_template = str_replace("{alt}", $block_row['alt'], $page_template);
						$block_output.= $page_template;
						break;					
					case 3: // Marquee
						$page_template = file_get_contents($page_directory . $block_row['chunk_template']);
						$page_template = str_replace("{page_block_id}", $block_row['page_block_id'], $page_template);
						$image = ($block_row['image'] && @file_get_contents($image_path . $block_row['image']) !== false ) ? $image_path . $block_row['image'] : "/assets/images/temp/image-marquee.jpg";
						$page_template = str_replace("{image}", $image, $page_template);
						$page_template = str_replace("{content}", $block_row['content'], $page_template);
						$page_template = str_replace("{credit}", $block_row['credit'], $page_template);
						$page_template = str_replace("{alt}", $block_row['alt'], $page_template);
						$block_output.= $page_template;					
						break;					
					case 4: // Contact
						$page_template = file_get_contents($page_directory . $block_row['chunk_template']);
						$page_template = str_replace("{page_block_id}", $block_row['page_block_id'], $page_template);
						$page_template = str_replace("{address}", $block_row['address'], $page_template);
						$page_template = str_replace("{city}", $block_row['city'], $page_template);
						$page_template = str_replace("{state}", $block_row['state'], $page_template);
						$page_template = str_replace("{zip}", $block_row['zip'], $page_template);
						$page_template = str_replace("{hours1}", $block_row['hours1'], $page_template);
						$page_template = str_replace("{hours2}", $block_row['hours2'], $page_template);
						$page_template = str_replace("{phone}", $block_row['phone'], $page_template);
						$page_template = str_replace("{email}", $block_row['email'], $page_template);
						$google_map = "https://maps.google.com/?q=" . str_replace(" ", "+", $block_row['address'] . "," . $block_row['city'] . ",+" . $block_row['state'] . "," . $block_row['zip']);
						$page_template = str_replace("{google_map}", $google_map, $page_template);
						$page_template = str_replace("{page_block_id}", $block_row['page_block_id'], $page_template);
						
						$block_output.= $page_template;					
						break;					
					case 5: // STEP
						$page_template = file_get_contents($page_directory . $block_row['chunk_template']);
						$page_template = str_replace("{page_block_id}", $block_row['page_block_id'], $page_template);
						$page_template = str_replace("{header}", $block_row['header'], $page_template);
						$page_template = str_replace("{content}", $block_row['content'], $page_template);
						$page_template = str_replace("{credit}", $block_row['credit'], $page_template);
						$block_output.= $page_template;					
						break;					
					case 6: // FINISHED
						$page_template = file_get_contents($page_directory . $block_row['chunk_template']);
						$page_template = str_replace("{page_block_id}", $block_row['page_block_id'], $page_template);
						$page_template = str_replace("{header}", $block_row['header'], $page_template);
						$image = ($block_row['image'] && @file_get_contents($image_path . $block_row['image']) !== false ) ? $image_path . $block_row['image'] : "/assets/images/temp/image-main.jpg";
						$page_template = str_replace("{image}", $image, $page_template);
						$page_template = str_replace("{credit}", $block_row['credit'], $page_template);
						$page_template = str_replace("{alt}", $block_row['alt'], $page_template);
						$block_output.= $page_template;					
						break;					
					case 7: // HINT
						$page_template = file_get_contents($page_directory . $block_row['chunk_template']);
						$page_template = str_replace("{page_block_id}", $block_row['page_block_id'], $page_template);
						$page_template = str_replace("{header}", $block_row['header'], $page_template);
						$page_template = str_replace("{content}", $block_row['content'], $page_template);
						$block_output.= $page_template;					
						break;					
					case 8: // MATERIALS
						$page_template = file_get_contents($page_directory . $block_row['chunk_template']);
						$page_template = str_replace("{page_block_id}", $block_row['page_block_id'], $page_template);
						
						$materials_array = array();
						$materials_array[] = $block_row['materials1'];
						$materials_array[] = $block_row['materials2'];
						$materials_array[] = $block_row['materials3'];
						$materials_array[] = $block_row['materials4'];
						$materials_array[] = $block_row['materials5'];
						$materials_array[] = $block_row['materials6'];
						$materials_array[] = $block_row['materials7'];
						$materials_array[] = $block_row['materials8'];
						$materials_array[] = $block_row['materials9'];
						$materials_array[] = $block_row['materials10'];
						
						$page_template = str_replace("{materials}", "<li>" . implode("</li><li>", array_filter($materials_array)) . "</li>", $page_template);
						
						$block_output.= $page_template;					
						break;					
					case 9: // Image
						$page_template = file_get_contents($page_directory . $block_row['chunk_template']);
						$page_template = str_replace("{page_block_id}", $block_row['page_block_id'], $page_template);						
						$image = ($block_row['image'] &&  @file_get_contents($image_path . $block_row['image']) !== false  ) ? $image_path . $block_row['image'] . "?time=" . time() : "/assets/images/temp/image-main.jpg";
						$page_template = str_replace("{image}", $image, $page_template);
						$page_template = str_replace("{credit}", $block_row['credit'], $page_template);
						$page_template = str_replace("{alt}", $block_row['alt'], $page_template);
						$block_output.= $page_template;				
						break;
					case 10: // Quote
						$page_template = file_get_contents($page_directory . $block_row['chunk_template']);
						$page_template = str_replace("{page_block_id}", $block_row['page_block_id'], $page_template);
						$page_template = str_replace("{content}", $block_row['content'], $page_template);
						$block_output.= $page_template;					
						break;					
					case 11: // Vimeo
						$page_template = file_get_contents($page_directory . $block_row['chunk_template']);
						$page_template = str_replace("{page_block_id}", $block_row['page_block_id'], $page_template);
						$video_link = ($block_row['content']!="") ?  $block_row['content'] : "19742547";
						$page_template = str_replace("{content}", $video_link, $page_template);
						$block_output.= $page_template;					
						break;
					case 12: // Trends
						$page_template = file_get_contents($page_directory . $block_row['chunk_template']);
						$page_template = str_replace("{page_block_id}", $block_row['page_block_id'], $page_template);
						
						$link1 = ($block_row['link1_title'] && $block_row['link1_url']) ? "<a href=\"" . $block_row['link1_url'] . "\" target=\"_blank\">" . $block_row['link1_title'] . " ></a>" : "";							
						$page_template = str_replace("{link1}", $link1, $page_template);
					
						$link2 = ($block_row['link2_title'] && $block_row['link2_url']) ? "<a href=\"" . $block_row['link2_url'] . "\" target=\"_blank\">" . $block_row['link2_title'] . " ></a>" : "";
						$page_template = str_replace("{link2}", $link2, $page_template);
							
						$link3 = ($block_row['link3_title'] && $block_row['link3_url']) ? "<a href=\"" . $block_row['link3_url'] . "\" target=\"_blank\">" . $block_row['link3_title'] . " ></a>" : "";
						$page_template = str_replace("{link3}", $link3, $page_template);
							
						$link4 = ($block_row['link4_title'] && $block_row['link4_url']) ? "<a href=\"" . $block_row['link4_url'] . "\" target=\"_blank\">" . $block_row['link4_title'] . " ></a>" : "";
						$page_template = str_replace("{link4}", $link4, $page_template);
							
						$link5 = ($block_row['link5_title'] && $block_row['link5_url']) ? "<a href=\"" . $block_row['link5_url'] . "\" target=\"_blank\">" . $block_row['link5_title'] . " ></a>" : "";
						$page_template = str_replace("{link5}", $link5, $page_template);
							
						$link6 = ($block_row['link6_title'] && $block_row['link6_url']) ? "<a href=\"" . $block_row['link6_url'] . "\" target=\"_blank\">" . $block_row['link6_title'] . " ></a>" : "";
						$page_template = str_replace("{link6}", $link6, $page_template);
							
						$link7 = ($block_row['link7_title'] && $block_row['link7_url']) ? "<a href=\"" . $block_row['link7_url'] . "\" target=\"_blank\">" . $block_row['link7_title'] . " ></a>" : "";
						$page_template = str_replace("{link7}", $link7, $page_template);
							
						$link8 = ($block_row['link8_title'] && $block_row['link8_url']) ? "<a href=\"" . $block_row['link8_url'] . "\" target=\"_blank\">" . $block_row['link8_title'] . " ></a>" : "";
						$page_template = str_replace("{link8}", $link8, $page_template);						
						
						$image = ($block_row['image'] && @file_get_contents($image_path . $block_row['image']) !== false ) ? $image_path . $block_row['image'] : "/assets/images/temp/thumb-trend.jpg";
						$page_template = str_replace("{image}", $image, $page_template);
						$page_template = str_replace("{alt}", $block_row['alt'], $page_template);
						$page_template = str_replace("{credit}", $block_row['credit'], $page_template);
						
						$block_output.= $page_template;					
						break;						

				}		
			}
			
			// lookup source types assigned to page
			$source_type_result = page_source_types_lookup($page_id);
			
			if($source_type_result){
				while($row = @mysql_fetch_array($source_type_result)){			
				
					$source_type = ($row['sub_source']) ? $row['sub_source'] : $row['source'];
				
					$image = ($row['sub_source_image']) ? $row['sub_source_image'] : $row['source_image'];
				
					$source_url = "/source/listing/" . $row['source_id'] . "/";
					
					if($row['sub_source_id']>0){
						$source_url = "/source/" . seo_friendly(sub_sources_name($row['source_id'], $row['sub_source_id'])) . "/" . $row['source_id'] . "/" . $row['sub_source_id'] . "/";
					} else {
						$source_url = "/source/listing/" . $row['source_id'] . "/";
					}
				
					$source_type_output.= '<li>
											<a href="' . $source_url . '">
												<img src="http://www.restylesource.com/admin/source_images/' . $image .  '" alt="' . $alt . '" />
											</a>
											<h3>' . $source_type . '</h3>
											</li>';
				}
			}
			
			//echo("<pre>");
			//print_r($row);
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
	<meta property="og:image" content="<?=$hero_image?>?time=<?=time();?>"/>

	<meta name="description" content="<?=$meta_description?>" />
	<meta name="keywords" content="<?=$meta_keywords?>" />
	<meta name="title" content="<?=$meta_title?>" />
	
	<title><?=$page_title?> | <? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/title.php'); ?></title>

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

<body class="source source-1 <?if($g_sess->get_var("systemTeam")=="admin"){?>logged-in <?}?>">

<div id="page-top"></div> <!-- end page top -->

<? // lookup designer picks // Added SRC 
$product_result = lookup_page_products($_GET['id'], 0, $source_id, 0);
$row = mysql_fetch_array($product_result); 	 
if(!empty($row)):  ?>  
<a class="product-btn click-scroll" href="#product-link"><img src="/assets/images/icon-products-btn.png" /></a>
<? endif; ?> 

<a class="top-btn click-scroll" href="#page-top"></a>

	<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'); ?>

	<div id="wrapper-page">

		<header>
			<h1><?=$inspiration?></h1>
			<h2><?=$page_title?> <span><?=$page_sub_title?></span></h2>

			<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/social.php'); ?>
			
		</header>

		<section role="main">

			<!-- BLOCK: Hero Image -->
			<div class="block image hero">
				<img src="<?=$hero_image?>?time=<?=time();?>" alt="<?=$hero_alt?>" />
				<a href="#" class="add-to-sf <?=$liked?>" data-type="inspiration" data-id="<?=$page_id?>" title="Add to Style File">Add to Style File</a>
			</div>
			<?=$hero_credit?>

			<!-- BLOCKS OUTPUT HERE -->

			<?=$block_output?>

			<!-- ADD NEW BLOCK -->
			<div class="admin-add-block">
			</div>

		</section> <!-- // [role="main"] -->
		
		<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/get-the-look.php'); ?>

		<?
		
		if($source_type_output){
		
		?>
		<section id="get-the-look">
			<h2>Additional Design Sources</h2>
			<ul>
			<?echo($source_type_output);?>	
			</ul>							
		</section>
		
		<?
		
		}
		
		?>

	</div> <!-- // #wrapper-page -->
	
	<a id="discontinued" href="/ajax/ajax-discontinued.php" class="discontinue"></a>
	
	<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'); ?>

	<!--
	BODY JS
	-->
	<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/js_includes.php'); ?>
	
	<!-- 

		Admin tools for this page

		This should be inserted dynamically and not served
		for the normal pages.

		The first line checks if using IE, which is currently unsupported

	 -->
	<!--[if IE]> <script src="/assets/js/admin/ie.js"></script> <![endif]-->
	<script src="/assets/js/admin/jquery.ui.admin.js?d=20120905"></script>
	<script src="/assets/js/admin/jquery.ui.admin-carousel-min.js"></script>
	<script>

	<?if($g_sess->get_var("systemTeam")=="admin"){?>

		var form = '';
		var data = '';
		var relationship = '';

		var page_id = "<?=$page_id?>";

		$( "body" ).addClass( "admin" );

		$( ".block.image.hero" ).admin({
			width:500,
			height:"auto",
			buttonText: "Edit Image",
			buttonOffset: "10px 0"
		});

		$( ".block.image" ).not(".hero").admin({
			width:500,
			height:"auto",
			buttonText: "Edit Image",
			buttonOffset: "10px 0",
			extraOptions: true
		});

		$( ".block.content" ).admin({
			height:"auto",
			buttonText: "Edit Content",
			editType: "content",
			buttonOffset: "10px 0",
			extraOptions: true
		});

		$( ".block.marquee:not(.trends)" ).admin({
			height:"auto",
			buttonText: "Edit Marquee",
			editType: "marquee",
			buttonOffset: "10px 0",
			extraOptions: true
		});

		$( ".block.marquee.trends" ).admin({
			width: 800,
			height:"auto",
			buttonText: "Edit Trends",
			editType: "trends",
			buttonOffset: "10px 0",
			extraOptions: true
		});

		$( ".block.about.person" ).admin({
			height:"auto",
			buttonText: "Edit About Person",
			editType: "about-person",
			buttonOffset: "10px 0",
			extraOptions: true
		});

		$( ".block.about:not(.person)" ).admin({
			height:"auto",
			buttonText: "Edit About",
			editType: "about",
			buttonOffset: "10px 0",
			extraOptions: true
		});

		$( ".block.contact" ).admin({
			height:"auto",
			buttonText: "Edit Contact",
			editType: "contact",
			buttonOffset: "10px 0",
			extraOptions: true
		});

		$( ".block.step:not(.finished)" ).admin({
			height:"auto",
			buttonText: "Edit Step",
			editType: "step",
			buttonOffset: "10px 0",
			extraOptions: true
		});

		$( ".block.step.finished" ).admin({
			height:"auto",
			buttonText: "Edit Finished",
			editType: "step-finished",
			buttonOffset: "10px 0",
			extraOptions: true
		});

		$( ".block.hint" ).admin({
			height:"auto",
			buttonText: "Edit Hint",
			editType: "hint",
			buttonOffset: "10px 0",
			extraOptions: true
		});

		$( ".block.materials" ).admin({
			height:"auto",
			buttonText: "Edit Materials",
			editType: "materials",
			buttonOffset: "10px 0",
			extraOptions: true
		});

		$( ".block.quote" ).admin({
			height:"auto",
			buttonText: "Edit Quote",
			editType: "quote",
			buttonOffset: "10px 0",
			extraOptions: true
		});

		$( ".block.video" ).admin({
			height:"auto",
			buttonText: "Edit Video",
			editType: "video",
			buttonOffset: "10px 0",
			extraOptions: true
		});

		// Add new block
		$( ".admin-add-block" ).admin({
			width: 500,
			height:"auto",
			buttonText: "Add Block",
			editType: "add-block",
			buttonOffset: "10px 0"
		});

		// What is style file dialog
		$(".add-to-sf").attr("href", "/ajax/ajax-what-is-style-file.html").dialogAjax({
			width: 550,
			buttons: {
				Register: {
					text: "Register for free",
					class:'button primary',
					click: function() {
						loadingIndicator.fadeIn(200);
						// Uncomment the following 2 lines once loading is done
						// $( this ).dialog( "close" );
						// dialog.remove();
					}
				},
				SignIn: {
					text: "Sign In",
					class:'button primary',
					click: function() {
						loadingIndicator.fadeIn(200);
						// Uncomment the following 2 lines once loading is done
						// $( this ).dialog( "close" );
						// dialog.remove();
					}
				}
			}
		});

		<? } ?>

	
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
			
			var form = $(this).parent();
			
			//alert($(this).parent().html());
			
			var data = 'function=source_product_relation&product_id=' + $(this).parent().find('#product-id').val() + '&relationship=' + relationship;    
			
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
		
		
		/* Additional JS from Eddie/Sean */
		$(document).ready(function() {
			
			/* INSPIRATION PAGE SCROLLING ANCHOR LINKS */
			
			// on click, grab the ID from the href and smooth scroll to it
			$('.click-scroll').click(function(event){		
				event.preventDefault();
				$('html,body').animate({scrollTop:$(this.hash).offset().top}, 500);
			})
			
			// show the top button (small) when scrolling down
			$(window).scroll(function() { 
				if ($(this).scrollTop() > 250) {
					$('.top-btn').fadeIn();
				} else {
					$('.top-btn').fadeOut();
				}
			});
			
			// find the height from the top of the window to products
			var scrollTop     = $(window).height(),
    		elementOffset     = $('#product-link').offset().top,
    		distance          = (elementOffset - scrollTop);
			
			// hide the products link when  you get to the distance
			var max_scroll = distance;
			$(document).scroll(function(){
			  if($(this).scrollTop() < max_scroll){
				  $('.product-btn').fadeIn();
			  } else {
					$('.product-btn').fadeOut();
				}
			});
			
		}); // end onload
		
	</script>

</body>
</html>
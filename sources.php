<?

	include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/db.php');	
	include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/logincheck.php');

	$section = "sources";
	$source_image_directory = "/source-images/";

	$is_source = (in_array($g_sess->get_var("systemTeam"), array('retailer','manufacturer'))) ? 1 : 0;

	if($_REQUEST['friendly']){
		$_REQUEST['id'] = source_id_lookup($_REQUEST['friendly']);
	}

	if($_REQUEST['id'] < 1){
		my_redirect('/index.php');
	}

	if(!check_manufacturer_view($_REQUEST['id'], $g_sess->get_var("systemTeam"))){
		my_redirect('/home.php');
	}	

	if($_POST['state']){
		setcookie("state", $_POST['state']);
		my_redirect($_SERVER['PHP_SELF']);
	}
	
	if($_COOKIE['state']){
		$state_location = $_COOKIE['state'];
	} else if ($g_sess->get_var("state")){
		$state_location = $g_sess->get_var("state");
	}

	//unlink('source-images/710_thumb.jpeg');

	if($_POST){
		/*
		echo("<pre>");
		print_r($_POST);
		die();
		*/
		if($_POST['id']){
		
			if($_POST['description']){
				source_description_update($_POST['id'], $_POST['description']);
			} else if($_FILES){
	
				// Check for alt tag updates
				if (isset($_POST['alt']) && $_POST['image_id'] < 1){
					retailer_image_alt($_POST['id'], 'main_alt', $_POST['alt'], $_POST['credit']);
				}
				if (isset($_POST['alt']) && $_POST['image_id'] > 0){
					retailer_image_alt($_POST['id'], 'image_' . $_POST['image_id'] . '_alt', $_POST['alt'], $_POST['credit']);
				}	
				if (isset($_POST['alt_1'])){
					retailer_image_alt($_POST['id'], 'footer_1_alt', $_POST['alt_1'], $_POST['credit']);
				}
				if (isset($_POST['alt_2'])){
					retailer_image_alt($_POST['id'], 'footer_2_alt', $_POST['alt_2'], $_POST['credit']);
				}
				if (isset($_POST['alt_3'])){
					retailer_image_alt($_POST['id'], 'footer_3_alt', $_POST['alt_3'], $_POST['credit']);	
				}
			
				// Upload Main Image
				if($_FILES['image']){
					
					if ($_FILES["image"]["error"] > 0){
						$error[] = $_FILES["image"]["error"];
					} else {
						//Going to force all images to be jpeg
						$new_file_name = $_POST['id'] . "_main.jpeg";
						if(move_uploaded_file($_FILES["image"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . $source_image_directory . $new_file_name)){
							// success build thumbnail
							image_crop($_SERVER['DOCUMENT_ROOT'] . $source_image_directory . $new_file_name, $_SERVER['DOCUMENT_ROOT'] . $source_image_directory . $_POST['id'] . '_thumb.jpeg', 135, 150, 100);
							//die($_SERVER['DOCUMENT_ROOT'] . $source_image_directory . $new_file_name . " --- " . $_SERVER['DOCUMENT_ROOT'] . $source_image_directory . $_POST['id'] . '_thumb.jpg');
						} else {
							$error[] = "Unable to save image, please try again.";
						}
					}
					
				} else if ($_FILES['thumb'] && $_FILES['full']){
				
					if ($_FILES["thumb"]["error"] > 0){
						$error[] = $_FILES["thumb"]["error"];
					} else if ($_FILES["full"]["error"] > 0){
						$error[] = $_FILES["full"]["error"];
					} else {
						$new_thumb_name = $_POST['id'] . "_" . $_POST['image_id'] . "_thumb.jpeg";
						$new_full_name = $_POST['id'] . "_" .  $_POST['image_id'] . "_full.jpeg";
						
						if(move_uploaded_file($_FILES["thumb"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . $source_image_directory . $new_thumb_name) && move_uploaded_file($_FILES["full"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . $source_image_directory . $new_full_name)){
							//success
							
						} else {
							$error[] = "Unable to save image, please try again.";
						}
					}
				} else if ($_FILES['footer1_thumb'] || $_FILES['footer1_full'] || $_FILES['footer2_thumb'] || $_FILES['footer2_full'] || $_FILES['footer3_thumb'] || $_FILES['footer3_full']){
					if ($_FILES["footer1_thumb"]["error"] > 0){
						$error[] = $_FILES["footer1_thumb"]["error"];
					} else {
						$new_name = $_POST['id'] . "_1_footer_thumb.jpeg";
						if(move_uploaded_file($_FILES["footer1_thumb"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . $source_image_directory . $new_name)){
							//success
						} else {
							$error[] = "Unable to save footer image 1 thumb, please try again.";
						}
					}
					
					if ($_FILES["footer1_full"]["error"] > 0){
						$error[] = $_FILES["footer1_full"]["error"];
					} else {
						$new_name = $_POST['id'] . "_1_footer_full.jpeg";
						if(move_uploaded_file($_FILES["footer1_full"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . $source_image_directory . $new_name)){
							//success
						} else {
							$error[] = "Unable to save footer image 1 full, please try again.";
						}
					}
					
					if ($_FILES["footer2_thumb"]["error"] > 0){
						$error[] = $_FILES["footer2_thumb"]["error"];
					} else {
						$new_name = $_POST['id'] . "_2_footer_thumb.jpeg";
						if(move_uploaded_file($_FILES["footer2_thumb"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . $source_image_directory . $new_name)){
							//success
						} else {
							$error[] = "Unable to save footer image 2 thumb, please try again.";
						}
					}
					
					if ($_FILES["footer2_full"]["error"] > 0){
						$error[] = $_FILES["footer2_full"]["error"];
					} else {
						$new_name = $_POST['id'] . "_2_footer_full.jpeg";
						if(move_uploaded_file($_FILES["footer2_full"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . $source_image_directory . $new_name)){
							//success
						} else {
							$error[] = "Unable to save footer image 2 full, please try again.";
						}
					}
					
					if ($_FILES["footer3_thumb"]["error"] > 0){
						$error[] = $_FILES["footer3_thumb"]["error"];
					} else {
						$new_name = $_POST['id'] . "_3_footer_thumb.jpeg";
						if(move_uploaded_file($_FILES["footer3_thumb"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . $source_image_directory . $new_name)){
							//success
						} else {
							$error[] = "Unable to footer image 3 thumb, please try again.";
						}
					}
					
					if ($_FILES["footer3_full"]["error"] > 0){
						$error[] = $_FILES["footer3_full"]["error"];
					} else {
						$new_name = $_POST['id'] . "_3_footer_full.jpeg";
						if(move_uploaded_file($_FILES["footer3_full"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . $source_image_directory . $new_name)){
							//success
						} else {
							$error[] = "Unable to footer image 3 full, please try again.";
						}
					}
					
				}
			}
		
			if(!$error){
				my_redirect($_SERVER['PHP_SELF'] . "?id=" . $_POST['id']);
			}
		} else {
			$error = "Missing Source ID.";
		}
	}

	$retailer_result = user_search($_GET['id'], '');
	
	if($retailer_result){
		$row = @mysql_fetch_array($retailer_result);
		
		//echo("<pre>");
		//print_r($row);
		//echo("</pre>");
	
	
		
	
		if(user_in_sf($g_sess->get_var("user"), 0, $_GET['id'], 0)){
			$liked = "liked";
		} else {
			$liked = "";
		}
		
		$status = $row['status'];
	
		if($status=="inactive" && $g_sess->get_var("systemTeam") != "admin"){
			my_redirect('/home.php');
		}


		$company = $row['company'];
		$source_type = $row['source_type'];
		$source = $row['source'];
		$kind = $row['user_group_id'];
		$group_name = $row['group_name'];
		$fname = $row['fname'];
		$lname = $row['lname'];
		$email = $row['email'];
		$phone = $row['phone'];
		$address1 = $row['address1'];
		$city = $row['city'];
		$state = $row['state'];
		$zip = $row['zip'];
		$notes = $row['notes'];
		$website = $row['website'];
		$keywords = $row['keywords'];
		$meta_title = $row['meta_title'];
		$meta_description = $row['meta_description'];
		$meta_keywords = $row['meta_keywords'];
		
		$main_alt = $row['main_alt'];
		$credit = $row['credit'];
    	$image_1_alt = $row['image_1_alt'];
		$image_2_alt = $row['image_2_alt']; 
		$image_3_alt = $row['image_3_alt'];
		$image_4_alt = $row['image_4_alt'];
		$image_5_alt = $row['image_5_alt'];
		$image_6_alt = $row['image_6_alt'];
		$image_7_alt = $row['image_7_alt'];
		$footer_1_alt = $row['footer_1_alt'];
		$footer_2_alt = $row['footer_2_alt'];
		$footer_3_alt = $row['footer_3_alt'];
		
		$description = $row['description'];
		
		$main_img = $source_image_directory . $_GET['id'] . "_main.jpeg";
		
		$main_img = (file_exists($_SERVER['DOCUMENT_ROOT'].$main_img)) ? $main_img : "assets/images/sources/no-image-main.jpg";

		$cs_1_img_thumb = $source_image_directory . $_GET['id'] . "_1_thumb.jpeg";
		$cs_1_img_full = $source_image_directory . $_GET['id'] . "_1_full.jpeg";
		
		if(file_exists($_SERVER['DOCUMENT_ROOT'].$cs_1_img_thumb) && file_exists($_SERVER['DOCUMENT_ROOT'].$cs_1_img_full)){
			$carousel_images.= '<li class="scroll-item"><a href="' . $cs_1_img_full . '" class="lightbox-carousel" rel="carousel" data-image-nbr="1"><img src="' . $cs_1_img_thumb . '" alt="' . $image_1_alt . '"/></a></li>'; 
		} else if ($g_sess->get_var("systemTeam") == "admin"){
			$carousel_images.= '<li class="scroll-item"><a href="assets/images/sources/no-image.jpg" class="lightbox-carousel" rel="carousel" data-image-nbr="1"><img src="assets/images/sources/no-image.jpg" /></a></li>'; 
		}
		
		$cs_2_img_thumb = $source_image_directory . $_GET['id'] . "_2_thumb.jpeg";
		$cs_2_img_full = $source_image_directory . $_GET['id'] . "_2_full.jpeg";

		if(file_exists($_SERVER['DOCUMENT_ROOT'].$cs_2_img_thumb) && file_exists($_SERVER['DOCUMENT_ROOT'].$cs_2_img_full)){
			$carousel_images.= '<li class="scroll-item"><a href="' . $cs_2_img_full . '" class="lightbox-carousel" rel="carousel" data-image-nbr="2"><img src="' . $cs_2_img_thumb . '" alt="' . $image_2_alt . '"/></a></li>'; 
		} else if ($g_sess->get_var("systemTeam") == "admin"){
			$carousel_images.= '<li class="scroll-item"><a href="assets/images/sources/no-image.jpg" class="lightbox-carousel" rel="carousel" data-image-nbr="2"><img src="assets/images/sources/no-image.jpg" /></a></li>'; 
		}
		
		$cs_3_img_thumb = $source_image_directory . $_GET['id'] . "_3_thumb.jpeg";
		$cs_3_img_full = $source_image_directory . $_GET['id'] . "_3_full.jpeg";

		if(file_exists($_SERVER['DOCUMENT_ROOT'].$cs_3_img_thumb) && file_exists($_SERVER['DOCUMENT_ROOT'].$cs_3_img_full)){
			$carousel_images.= '<li class="scroll-item"><a href="' . $cs_3_img_full . '" class="lightbox-carousel" rel="carousel" data-image-nbr="3"><img src="' . $cs_3_img_thumb . '" alt="' . $image_3_alt . '"/></a></li>'; 
		} else if ($g_sess->get_var("systemTeam") == "admin"){
			$carousel_images.= '<li class="scroll-item"><a href="assets/images/sources/no-image.jpg" class="lightbox-carousel" rel="carousel" data-image-nbr="3"><img src="assets/images/sources/no-image.jpg" /></a></li>'; 
		}
		
		$cs_4_img_thumb = $source_image_directory . $_GET['id'] . "_4_thumb.jpeg";
		$cs_4_img_full = $source_image_directory . $_GET['id'] . "_4_full.jpeg";

		if(file_exists($_SERVER['DOCUMENT_ROOT'].$cs_4_img_thumb) && file_exists($_SERVER['DOCUMENT_ROOT'].$cs_4_img_full)){
			$carousel_images.= '<li class="scroll-item"><a href="' . $cs_4_img_full . '" class="lightbox-carousel" rel="carousel" data-image-nbr="4"><img src="' . $cs_4_img_thumb . '" alt="' . $image_4_alt . '"/></a></li>'; 
		} else if ($g_sess->get_var("systemTeam") == "admin"){
			$carousel_images.= '<li class="scroll-item"><a href="assets/images/sources/no-image.jpg" class="lightbox-carousel" rel="carousel" data-image-nbr="4"><img src="assets/images/sources/no-image.jpg" /></a></li>'; 
		}
		
		$cs_5_img_thumb = $source_image_directory . $_GET['id'] . "_5_thumb.jpeg";
		$cs_5_img_full = $source_image_directory . $_GET['id'] . "_5_full.jpeg";

		if(file_exists($_SERVER['DOCUMENT_ROOT'].$cs_5_img_thumb) && file_exists($_SERVER['DOCUMENT_ROOT'].$cs_5_img_full)){
			$carousel_images.= '<li class="scroll-item"><a href="' . $cs_5_img_full . '" class="lightbox-carousel" rel="carousel" data-image-nbr="5"><img src="' . $cs_5_img_thumb . '" alt="' . $image_5_alt . '"/></a></li>'; 
		} else if ($g_sess->get_var("systemTeam") == "admin"){
			$carousel_images.= '<li class="scroll-item"><a href="assets/images/sources/no-image.jpg" class="lightbox-carousel" rel="carousel" data-image-nbr="5"><img src="assets/images/sources/no-image.jpg" /></a></li>'; 
		}
		
		$cs_6_img_thumb = $source_image_directory . $_GET['id'] . "_6_thumb.jpeg";
		$cs_6_img_full = $source_image_directory . $_GET['id'] . "_6_full.jpeg";

		if(file_exists($_SERVER['DOCUMENT_ROOT'].$cs_6_img_thumb) && file_exists($_SERVER['DOCUMENT_ROOT'].$cs_6_img_full)){
			$carousel_images.= '<li class="scroll-item"><a href="' . $cs_6_img_full . '" class="lightbox-carousel" rel="carousel" data-image-nbr="6"><img src="' . $cs_6_img_thumb . '" alt="' . $image_6_alt . '"/></a></li>'; 
		} else if ($g_sess->get_var("systemTeam") == "admin"){
			$carousel_images.= '<li class="scroll-item"><a href="assets/images/sources/no-image.jpg" class="lightbox-carousel" rel="carousel" data-image-nbr="6"><img src="assets/images/sources/no-image.jpg" /></a></li>'; 
		}
		
		$cs_7_img_thumb = $source_image_directory . $_GET['id'] . "_7_thumb.jpeg";
		$cs_7_img_full = $source_image_directory . $_GET['id'] . "_7_full.jpeg";

		if(file_exists($_SERVER['DOCUMENT_ROOT'].$cs_7_img_thumb) && file_exists($_SERVER['DOCUMENT_ROOT'].$cs_7_img_full)){
			$carousel_images.= '<li class="scroll-item"><a href="' . $cs_7_img_full . '" class="lightbox-carousel" rel="carousel" data-image-nbr="7"><img src="' . $cs_7_img_thumb . '" alt="' . $image_7_alt . '"/></a></li>'; 
		} else if ($g_sess->get_var("systemTeam") == "admin"){
			$carousel_images.= '<li class="scroll-item"><a href="assets/images/sources/no-image.jpg" class="lightbox-carousel" rel="carousel" data-image-nbr="7"><img src="assets/images/sources/no-image.jpg" /></a></li>'; 
		}

		$footer_1_img_thumb = $source_image_directory . $_GET['id'] . "_1_footer_thumb.jpeg";
		$footer_1_img_full = $source_image_directory . $_GET['id'] . "_1_footer_full.jpeg";
		
		if(file_exists($_SERVER['DOCUMENT_ROOT'].$footer_1_img_thumb) && file_exists($_SERVER['DOCUMENT_ROOT'].$footer_1_img_full)){
			$photo_spread.= '<a href="' . $footer_1_img_full . '" class="photo-spread" rel="photo-spread"><img src="' . $footer_1_img_thumb . '" alt="' . $footer_1_alt . '"/></a>';
		} else if ($g_sess->get_var("systemTeam") == "admin"){
			$photo_spread.= '<a href="assets/images/sources/footer-1.jpg" class="photo-spread" rel="photo-spread"><img src="assets/images/sources/footer-1.jpg" /></a>';
		}
		
		$footer_2_img_thumb = $source_image_directory . $_GET['id'] . "_2_footer_thumb.jpeg";
		$footer_2_img_full = $source_image_directory . $_GET['id'] . "_2_footer_full.jpeg";
		
		if(file_exists($_SERVER['DOCUMENT_ROOT'].$footer_2_img_thumb) && file_exists($_SERVER['DOCUMENT_ROOT'].$footer_2_img_full)){
			$photo_spread.= '<a href="' . $footer_2_img_full . '" class="photo-spread" rel="photo-spread"><img src="' . $footer_2_img_thumb . '" alt="' . $footer_2_alt . '"/></a>';
		} else if ($g_sess->get_var("systemTeam") == "admin"){
			$photo_spread.= '<a href="assets/images/sources/footer-2.jpg" class="photo-spread" rel="photo-spread"><img src="assets/images/sources/footer-2.jpg" /></a>';
		}
		
		$footer_3_img_thumb = $source_image_directory . $_GET['id'] . "_3_footer_thumb.jpeg";
		$footer_3_img_full = $source_image_directory . $_GET['id'] . "_3_footer_full.jpeg";
		
		if(file_exists($_SERVER['DOCUMENT_ROOT'].$footer_3_img_thumb) && file_exists($_SERVER['DOCUMENT_ROOT'].$footer_3_img_full)){
			$photo_spread.= '<a href="' . $footer_3_img_full . '" class="photo-spread" rel="photo-spread"><img src="' . $footer_3_img_thumb . '" alt="' . $footer_3_alt . '"/></a>';
		} else if ($g_sess->get_var("systemTeam") == "admin"){
			$photo_spread.= '<a href="assets/images/sources/footer-3.jpg" class="photo-spread" rel="photo-spread"><img src="assets/images/sources/footer-3.jpg" /></a>';
		}

		$source_product_count = source_product_count($_GET['id']);

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
	<meta property="og:image" content="<?=$main_img?>?d=<?=time();?>"/>
	 
	<meta name="description" content="<?=$meta_description?>" />
	<meta name="keywords" content="<?=$meta_keywords?>" />
	<meta name="title" content="<?=$meta_title?>" /> 
	
	<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
	<META HTTP-EQUIV="Expires" CONTENT="-1">
	  
	<title><?=$company?> | <? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/title.php'); ?></title>

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

<body class="source source-2 logged-in">

	<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'); ?>

	<div id="wrapper-page">

		<header>
			<h2><?=$company?> <span><?=$city?>, <?=$state?></span></h2>

			<?

	include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/db.php');	
	include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/logincheck.php');

	if($_REQUEST['id'] > 0){	

		$result = user_search($_REQUEST['id']);
		$row = mysql_fetch_array($result);
?>
		<nav>
<?
		if($row['facebook'] != ""){
			echo('<a href="' . $row['facebook']  . '" target="_new"><img src="/assets/images/sources/icon-facebook.png" /></a>');
		}
			
		if($row['twitter'] != ""){
			echo('<a href="' . $row['twitter'] . '" target="_new"><img src="/assets/images/sources/icon-twitter.png" /></a>');
		}
		
		if($row['linkedin'] != ""){
			echo('<a href="' . $row['linkedin'] . '" target="_new"><img src="/assets/images/sources/icon-linkedin.png" /></a>');
		}	
			
	}
?>	
			</nav>
			
		</header>

		<section role="main">

			<div class="block image">
				<img src="<?=$main_img?>?d=<?=time();?>" alt="<?=$main_alt?>" />
				<a href="#" onclick="<?if(!$g_sess->get_var("user")) { echo("top.location='/style-file.php'"); }?>" class="add-to-sf <?=$liked?>" data-type="source" data-id="<?=$_REQUEST['id']?>" title="Add to Style File">Add to Style File</a>
				<?if($source_product_count>0 && int_ok($source_product_count)){?>
				<a href="/source/<?=seo_friendly($company)?>/inventory/<?=$_GET['id']?>/" class="button">View Our Products &gt;</a>
				<?}?>
			</div>
			
			<!-- if no credit, do not insert -->
			<?if($credit){?>
			<H6><?=$credit?></H6>
			<?}?>
			<div id="image-carousel">

				<div class="scroll-pane">
					<ul class="scroll-content">
					
						<?=$carousel_images?>
			
					</ul>
				</div>
				<div class="scroll-bar-wrap ui-widget-content ui-corner-bottom">
					<div class="scroll-bar"></div>
				</div>

			</div> <!-- // #image-carousel -->

		</section> <!-- //  [role="main"] -->

		<section id="source-info">

			<div class="vcard">
				<h2 class="fn org"><?=$company?></h2>
				<p><span class="street-address"><?=$address1?></span></p>
				<p><span class="locality"><?=$city?></span>, <span class="region"><?=$state?></span> <span class="postal-code"><?=$zip?></span> <span class="tel"><?=$phone?></span></p>
				<p><a href="http://<?=$website?>" target="_blank" class="url"><?=$website?></a></p>
				<div id="contact-postings">
					<a href="mailto:<?=$email?>" class="button contact">Contact <span>Source &gt;</span></a>
					<div id="clothes-pin"></div>
				</div> <!-- // #contact-postings -->
			</div> <!-- // .vcard -->

			<div id="testimonial">
				<p><?=$description?></p>
			</div>

		</section> <!-- // #source-info -->

		<section id="photo-spread">
			
			<?=$photo_spread?>
			
		</section> <!-- //#photo-spread -->

	</div> <!-- // #wrapper-page -->

	<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'); ?>

	<!--
	BODY JS
	-->
	<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/js_includes.php'); ?>

	<!-- 

		Admin tools for this page

		This should be inserted dynamically and not served
		for the normal pages.

	 -->
	 
	<? if($g_sess->get_var("systemTeam") == "admin"){ ?> 
	 
	<!--[if IE]> <script src="/assets/js/admin/ie.js"></script> <![endif]-->
	<script src="/assets/js/admin/jquery.ui.admin.js"></script>
	<script src="/assets/js/admin/jquery.ui.admin-carousel.js"></script>
	<script>
	
		var source_id = "<?=$_GET['id']?>";
	
		$( "body" ).addClass( "admin" );

		$( ".block.image" ).admin({
			width:500,
			height:"auto",
			buttonText: "Edit Image",
			buttonPosition: "left top"
		});

		$( "#image-carousel .scroll-item" ).adminCarousel({
			width:500,
			height:"auto",
			buttonText: "Edit Image",
			buttonOffset: "10px 32px",
			editType: "image-carousel",
			dialogClass: "image-carousel"
		});

		$( "#testimonial" ).admin({
			height:"auto",
			buttonText: "Edit Text",
			buttonPosition: "left bottom",
			buttonOffset: "0 0",
			editType: "text"
		});

		$( "#photo-spread" ).admin({
			height:"auto",
			buttonText: "Edit Images",
			buttonPosition: "left bottom",
			buttonOffset: "0 0",
			editType: "images"
		});
	</script>

	<? } ?>

</body>
</html>
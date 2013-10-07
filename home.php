<?

	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';	

	$block_img_path = "home-blocks/";	
	$image_path = "/inspiration-photos/";
	$inspiration_url = "http://" . $_SERVER['HTTP_HOST'] . "/inspiration-type.php?id=";

	$about_shown = MakeNullsZeros($_COOKIE["about_shown"]);
	
	$expire=time()+60*60*24*30;
	setcookie("about_shown", "1", $expire);
	
	$row = home_page_lookup();

	if($row){
		
		///echo("<pre>");
		//print_r($row);
		
		$meta_title = $row['meta_title'];
		$meta_description = $row['meta_description'];
		$meta_keywords = $row['meta_keywords'];
		
		if($row['image1_id'] > 0 && (file_exists($_SERVER['DOCUMENT_ROOT'] . $image_path . $row['image1_id'] . "_hero.jpg") || (fopen('http://www.restylesource.com' . $image_path . $row['image1_id'] . "_hero.jpg", "r") ))){
	
			$page_result = page_lookup($row['image1_id']);
			$row2 = @mysql_fetch_array($page_result);
	
			inspiration_title_alt_tags($row['image1_id'], $page_title, $alt);
	
			$image1_cta = ($row['image1_cta']) ? "<p><span>" . $row['image1_cta'] . " ></span></p>" : "";
	
			$slideshow_output .= '<a href="inspiration/' . seo_friendly($row2['inspiration']) . '/' . seo_friendly(sub_inspiration_name($row2['sub_inspiration_id'])) . '/' . seo_friendly($row2['page_title']) . '/' . $row['image1_id'] . '/" title="' . $page_title . '">
										<img src="http://www.restylesource.com' . $image_path . $row['image1_id'] . "_hero.jpg" . '" alt="' . $alt . '" />
										<p><span><span>' . $row['image1_caption_1'] . '</span><span>' . $row['image1_caption_2'] . '</span></p>
										' . $image1_cta . '
									</a>';
		}
		
		if($row['image2_id'] > 0 && (file_exists($_SERVER['DOCUMENT_ROOT'] . $image_path . $row['image2_id'] . "_hero.jpg") || (fopen('http://www.restylesource.com' . $image_path . $row['image2_id'] . "_hero.jpg", "r") ))){
	
			$page_result = page_lookup($row['image2_id']);
			$row2 = @mysql_fetch_array($page_result);

			$image2_cta = ($row['image2_cta']) ? "<p><span>" . $row['image2_cta'] . " ></span></p>" : "";

			inspiration_title_alt_tags($row['image2_id'], $title, $alt);
			$slideshow_output .= '<a href="inspiration/' . seo_friendly($row2['inspiration']) . '/' . seo_friendly(sub_inspiration_name($row2['sub_inspiration_id'])) . '/' . seo_friendly($row2['page_title']) . '/' . $row['image2_id'] . '/" title="' . $title . '">
										<img src="http://www.restylesource.com' . $image_path . $row['image2_id'] . "_hero.jpg" . '" alt="' . $alt . '" />
										<p><span><span>' . $row['image2_caption_1'] . '</span><span>' . $row['image2_caption_2'] . '</span></p>
										' . $image2_cta . '
									</a>';
		}
		
		if($row['image3_id'] > 0 && (file_exists($_SERVER['DOCUMENT_ROOT'] . $image_path . $row['image3_id'] . "_hero.jpg") || (fopen('http://www.restylesource.com' . $image_path . $row['image3_id'] . "_hero.jpg", "r") ))){
		
			$page_result = page_lookup($row['image3_id']);
			$row2 = @mysql_fetch_array($page_result);
			
			$image3_cta = ($row['image3_cta']) ? "<p><span>" . $row['image3_cta'] . " ></span></p>" : "";
			
			inspiration_title_alt_tags($row['image3_id'], $title, $alt);
			$slideshow_output .= '<a href="inspiration/' . seo_friendly($row2['inspiration']) . '/' . seo_friendly(sub_inspiration_name($row2['sub_inspiration_id'])) . '/' . seo_friendly($row2['page_title']) . '/' . $row['image3_id'] . '/" title="' . $title . '">
										<img src="http://www.restylesource.com' . $image_path . $row['image3_id'] . "_hero.jpg" . '" alt="' . $alt . '" />
										<p><span><span>' . $row['image3_caption_1'] . '</span><span>' . $row['image3_caption_2'] . '</span></p>
										' . $image3_cta . '
									</a>';
		}		
		 
		if($row['image4_id'] > 0 && (file_exists($_SERVER['DOCUMENT_ROOT'] . $image_path . $row['image4_id'] . "_hero.jpg") || (fopen('http://www.restylesource.com' . $image_path . $row['image4_id'] . "_hero.jpg", "r") ))){
		
			$page_result = page_lookup($row['image4_id']);
			$row2 = @mysql_fetch_array($page_result);
		
			$image4_cta = ($row['image4_cta']) ? "<p><span>" . $row['image4_cta'] . " ></span></p>" : "";
		
			inspiration_title_alt_tags($row['image4_id'], $title, $alt);
			$slideshow_output .= '<a href="inspiration/' . seo_friendly($row2['inspiration']) . '/' . seo_friendly(sub_inspiration_name($row2['sub_inspiration_id'])) . '/' . seo_friendly($row2['page_title']) . '/' . $row['image4_id'] . '/" title="' . $title . '">
										<img src="http://www.restylesource.com' . $image_path . $row['image4_id'] . "_hero.jpg" . '" alt="' . $alt . '" />
										<p><span><span>' . $row['image4_caption_1'] . '</span><span>' . $row['image4_caption_2'] . '</span></p>
										' . $image4_cta . '
									</a>';
		}		

		if($row['image5_id'] > 0 && (file_exists($_SERVER['DOCUMENT_ROOT'] . $image_path . $row['image5_id'] . "_hero.jpg") || (fopen('http://www.restylesource.com' . $image_path . $row['image5_id'] . "_hero.jpg", "r") ))){
		
			$page_result = page_lookup($row['image5_id']);
			$row2 = @mysql_fetch_array($page_result);
		
			$image5_cta = ($row['image5_cta']) ? "<p><span>" . $row['image5_cta'] . " ></span></p>" : "";
		
			inspiration_title_alt_tags($row['image5_id'], $title, $alt);
			$slideshow_output .= '<a href="inspiration/' . seo_friendly($row2['inspiration']) . '/' . seo_friendly(sub_inspiration_name($row2['sub_inspiration_id'])) . '/' . seo_friendly($row2['page_title']) . '/' . $row['image5_id'] . '/" title="' . $title . '">
										<img src="http://www.restylesource.com' . $image_path . $row['image5_id'] . "_hero.jpg" . '" alt="' . $alt . '" />
										<p><span><span>' . $row['image5_caption_1'] . '</span><span>' . $row['image5_caption_2'] . '</span></p>
										' . $image5_cta . '
									</a>';
		}

		if($row['image6_id'] > 0 && (file_exists($_SERVER['DOCUMENT_ROOT'] . $image_path . $row['image6_id'] . "_hero.jpg") || (fopen('http://www.restylesource.com' . $image_path . $row['image6_id'] . "_hero.jpg", "r") ))){
		
			$page_result = page_lookup($row['image6_id']);
			$row2 = @mysql_fetch_array($page_result);
		
			$image6_cta = ($row['image6_cta']) ? "<p><span>" . $row['image6_cta'] . " ></span></p>" : "";
		
			inspiration_title_alt_tags($row['image6_id'], $title, $alt);
			$slideshow_output .= '<a href="inspiration/' . seo_friendly($row2['inspiration']) . '/' . seo_friendly(sub_inspiration_name($row2['sub_inspiration_id'])) . '/' . seo_friendly($row2['page_title']) . '/' . $row['image6_id'] . '/" title="' . $title . '">
										<img src="http://www.restylesource.com' . $image_path . $row['image6_id'] . "_hero.jpg" . '" alt="' . $alt . '" />
										<p><span><span>' . $row['image6_caption_1'] . '</span><span>' . $row['image6_caption_2'] . '</span></p>
										' . $image6_cta . '
									</a>';
		}
		
		if($row['image7_id'] > 0 && (file_exists($_SERVER['DOCUMENT_ROOT'] . $image_path . $row['image7_id'] . "_hero.jpg") || (fopen('http://www.restylesource.com' . $image_path . $row['image7_id'] . "_hero.jpg", "r") ))){
		
			$page_result = page_lookup($row['image7_id']);
			$row2 = @mysql_fetch_array($page_result);
		
			$image7_cta = ($row['image7_cta']) ? "<p><span>" . $row['image7_cta'] . " ></span></p>" : "";
		
			inspiration_title_alt_tags($row['image7_id'], $title, $alt);
			$slideshow_output .= '<a href="inspiration/' . seo_friendly($row2['inspiration']) . '/' . seo_friendly(sub_inspiration_name($row2['sub_inspiration_id'])) . '/' . seo_friendly($row2['page_title']) . '/' . $row['image7_id'] . '/" title="' . $title . '">
										<img src="http://www.restylesource.com' . $image_path . $row['image7_id'] . "_hero.jpg" . '" alt="' . $alt . '" />
										<p><span><span>' . $row['image7_caption_1'] . '</span><span>' . $row['image7_caption_2'] . '</span></p>
										' . $image7_cta . '
									</a>';
		}
		
		if($row['image8_id'] > 0 && (file_exists($_SERVER['DOCUMENT_ROOT'] . $image_path . $row['image8_id'] . "_hero.jpg") || (fopen('http://www.restylesource.com' . $image_path . $row['image8_id'] . "_hero.jpg", "r") ))){
		
			$page_result = page_lookup($row['image8_id']);
			$row2 = @mysql_fetch_array($page_result);
		
			$image8_cta = ($row['image8_cta']) ? "<p><span>" . $row['image8_cta'] . " ></span></p>" : "";
		
			inspiration_title_alt_tags($row['image8_id'], $title, $alt);
			$slideshow_output .= '<a href="inspiration/' . seo_friendly($row2['inspiration']) . '/' . seo_friendly(sub_inspiration_name($row2['sub_inspiration_id'])) . '/' . seo_friendly($row2['page_title']) . '/' . $row['image8_id'] . '/" title="' . $title . '">
										<img src="http://www.restylesource.com' . $image_path . $row['image8_id'] . "_hero.jpg" . '" alt="' . $alt . '" />
										<p><span><span>' . $row['image8_caption_1'] . '</span><span>' . $row['image8_caption_2'] . '</span></p>
										' . $image8_cta . '
									</a>';
		}						
		
		if(int_ok($row['top_banner'])){
		
			//die($row['top_banner']);
		
			$page_result = page_lookup($row['top_banner']);
			$row2 = @mysql_fetch_array($page_result);
			if($row2){
				$top_banner_url = "inspiration/" . seo_friendly($row2['inspiration']) . "/" . seo_friendly($row2['page_title']) . "/" . $row['top_banner'] . "/";
			}
		} else {
			$top_banner_url = $row['top_banner'];
		}
		
		$top_banner_title = $row['top_banner_title']; 
		$top_banner_caption = $row['top_banner_caption']; 
		
		if(int_ok($row['block_1'])){
			$page_result = page_lookup($row['block_1']);
			$row2 = @mysql_fetch_array($page_result);
			if($row2){
				//$block_1_url = "inspiration/" . seo_friendly($row2['inspiration']) . "/" . seo_friendly($row2['page_title']) . "/" . $row['block_1'] . "/";
				$block_1_url = "inspiration/" . seo_friendly($row2['inspiration']) . "/" . $row2['page_id'] . "/";
			}
		} else { 
			$block_1_url = $row['block_1'];
		}
		
		$block_1_title = $row['block_1_title']; 
		$block_1_text = $row['block_1_text']; 
		
		$block_1_link_title = $row['block_1_link_title'];
		$block_1_alt = $row['block_1_alt'];
		$block_1_bg = $row['block_1_bg'];
		
		if(int_ok($row['block_2'])){
			$page_result = page_lookup($row['block_2']);
			$row2 = @mysql_fetch_array($page_result);
		
			if($row2){
				//$block_2_url = "inspiration/" . seo_friendly($row2['inspiration']) . "/" . seo_friendly($row2['page_title']) . "/" . $row['block_2'] . "/";
				$block_2_url = "inspiration/" . seo_friendly($row2['inspiration']) . "/" . $row2['page_id'] . "/";
			}
		} else {
			 $block_2_url = $row['block_2'];
		}
		
		$block_2_title = $row['block_2_title']; 
		$block_2_text = $row['block_2_text']; 

		$block_2_link_title = $row['block_2_link_title'];
		$block_2_alt = $row['block_2_alt'];
		$block_2_bg = $row['block_2_bg'];

		if(int_ok($row['block_3'])){
			$page_result = page_lookup($row['block_3']);
			$row2 = @mysql_fetch_array($page_result);
			if($row2){
				//$block_3_url = "inspiration/" . seo_friendly($row2['inspiration']) . "/" . seo_friendly($row2['page_title']) . "/" . $row['block_3'] . "/";
				$block_3_url = "inspiration/" . seo_friendly($row2['inspiration']) . "/" . $row2['page_id'] . "/";
			}
		} else {
			$block_3_url = $row['block_3'];
		}
		
		$block_3_title = $row['block_3_title']; 
		$block_3_text = $row['block_3_text']; 
		
		$block_3_link_title = $row['block_3_link_title'];
		$block_3_alt = $row['block_3_alt'];
		$block_3_bg = $row['block_3_bg'];
		
		if(int_ok($row['block_4'])){
			$page_result = page_lookup($row['block_4']);
			$row2 = @mysql_fetch_array($page_result);
			if($row2){
				//$block_4_url = "inspiration/" . seo_friendly($row2['inspiration']) . "/" . seo_friendly($row2['page_title']) . "/" . $row['block_4'] . "/";
				$block_4_url = "inspiration/" . seo_friendly($row2['inspiration']) . "/" . $row2['page_id'] . "/";
			}
		} else {
			$block_4_url = $row['block_4'];
		}
		
		$block_4_title = $row['block_4_title']; 
		$block_4_text = $row['block_4_text']; 
		
		$block_4_link_title = $row['block_4_link_title'];
		$block_4_alt = $row['block_4_alt'];
		$block_4_bg = $row['block_4_bg'];
		
		$bottom_banner = $row['bottom_banner'];
		$bottom_alt = $row['bottom_alt'];
		$bottom_title = $row['bottom_title'];

		//die($block_img_path . 'block_1_img.jpg');

		if(file_exists($block_img_path . 'block_1_img.jpg') || fopen('http://www.restylesource.com/' . $block_img_path . 'block_1_img.jpg', "r") )
			$block_img_1 = 'http://www.restylesource.com/' . $block_img_path . 'block_1_img.jpg';

		if(file_exists($block_img_path . 'block_2_img.jpg') || fopen('http://www.restylesource.com/' . $block_img_path . 'block_2_img.jpg', "r") )
			$block_img_2 = 'http://www.restylesource.com/' . $block_img_path . 'block_2_img.jpg';
			
		if(file_exists($block_img_path . 'block_3_img.jpg') || fopen('http://www.restylesource.com/' . $block_img_path . 'block_3_img.jpg', "r") )
			$block_img_3 = 'http://www.restylesource.com/' . $block_img_path . 'block_3_img.jpg';			

		if(file_exists($block_img_path . 'block_4_img.jpg') || fopen('http://www.restylesource.com/' . $block_img_path . 'block_4_img.jpg', "r") )
			$block_img_4 = 'http://www.restylesource.com/' . $block_img_path . 'block_4_img.jpg';

		if(file_exists($block_img_path . 'bottom_banner_img.jpg') || fopen('http://www.restylesource.com/' . $block_img_path . 'block_5_img.jpg', "r") )
			$bottom_banner_img = 'http://www.restylesource.com/' . $block_img_path . 'bottom_banner_img.jpg';
	
	}
	

?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html lang="en-us" class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>    <html lang="en-us" class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>    <html lang="en-us" class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en-us" class="no-js"> <!--<![endif]-->

<head>

	<meta name="description" content="<?=$meta_description?>" />
	<meta name="keywords" content="<?=$meta_keywords?>" />
	<meta name="title" content="<?=$meta_title?>" />

	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width" />
	  
	<title><?=$meta_title?></title>

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
<body class="home logged-in">

	<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'); ?>

	<div id="wrapper-page">

		<header>			
			
			<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/social.php'); ?>
			
		</header>

		<section role="main">
			<div class="slideshow">
				<div class="block image get-the-look">
					<?=$slideshow_output?>
				</div>
				<div class="slideshow-nav"></div>
			</div>

			<div class="banner design-dilemmas">
		<div style="font-size: 1.5em; text-align: center; text-transform: uppercase; line-height: 110%; font-family: " din",="" sans-serif;"=""><b>RE</b>STYLE<b>SOURCE</b> <span style="color:#acbc28">inspires + connects</span> you with design sources you love.</div>
				</a>
				<!-- <a href="mailto:info@restylesource.com" class="submit">Submit Your Idea</a> -->
			</div>

			<div id="featured-grid">
				<div class="featured">
					<a title="<?=$block_1_link_title?>" href="<?=$block_1_url?>">
						<h3><span><?=$block_1_title?></span></h3>
						<h4 style="background:rgba<?=$block_1_bg?>;"><?=$block_1_text?></h4>
						<img src="<?=$block_img_1?>" alt="<?=$block_1_alt?>" />
					</a>
				</div>
				<div class="featured diy">
					<a title="<?=$block_2_link_title?>" href="<?=$block_2_url?>">
						<h3><span><?=$block_2_title?></span></h3>
						<h4 style="background:rgba<?=$block_2_bg?>;"><?=$block_2_text?></h4>
						<img src="<?=$block_img_2?>" alt="<?=$block_2_alt?>" />
					</a>
				</div>
				<div class="featured happenings">
					<a title="<?=$block_4_link_title?>" href="<?=$block_4_url?>">
						<h3><span><?=$block_4_title?></span></h3>
						<h4 style="background:<?=$block_4_bg?>;"><?=$block_4_text?></h4>
						<img src="<?=$block_img_4?>" alt="<?=$block_4_alt?>" />
					</a>
				</div>
				<div class="blog">
					<a href="#"><h1 style="font-size: 2.0em; margin: 0.67em 0; margin-left: 8%; margin-top: 10%;">Latest News from REstyleSOURCE</h1></a>
				</br>
				<h2 style="margin-top: -2%; margin-left: 27%;">Our New Blog Is Coming Soon!</h2>
				</div>

								<div class="featured retailer">
					<a title="<?=$block_3_link_title?>" href="<?=$block_3_url?>">
						<h3>Featured Retailer</h3>
						<h4 style="background:rgba<?=$block_3_bg?>;"><?=$block_3_title?><span><?=$block_3_text?></span></h4>
						<img src="<?=$block_img_3?>" alt="<?=$block_3_alt?>" />
					</a>
				</div>
			</div> <!-- // #featured-grid -->

		</section> <!-- // [role="main"] -->

	</div> <!-- // #wrapper-page -->

	<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'); ?>

	<!--
	BODY JS
	-->
	<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/js_includes.php'); ?>
	
	<script>
	
		var about_shown = <?=$about_shown?>;
	
		$('.block.get-the-look').cycle({
			fx: 'scrollHorz', // choose your transition type, ex: fade, scrollUp, shuffle, etc...
			pager: ".slideshow-nav",
			easing: 'easeOutQuad',
			speed: 1800,
			timeout: 3000,
			pause: 1
		});

		// What is restyle dialog
		$(".what-is-restyle > a").dialogAjax({
			dialogClass: "what-is-restyle",
			width: "auto",
			buttons: {
				Register: {
					text: "Register for free",
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

		$(document).ready(function() {
			if(about_shown==0){
				$(".what-is-restyle > a").trigger("click");
			}
		});


	</script>

</body>
</html>
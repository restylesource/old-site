<?

	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';	
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/logincheck.php';	

	if($_REQUEST['id'] < 1){
		my_redirect('/index.php');
	}	
	
	$section = "sources";
	$source_image_directory = "/source-images/";
	$item_width = 6;
	
	$row_limit = ($_REQUEST['limit']) ? $_REQUEST['limit'] : 1000; 
	
	$is_source = (in_array($g_sess->get_var("systemTeam"), array('retailer','manufacturer')) && $g_sess->get_var("inventory_ind")) ? 1 : 0;
	
	$change_location_link = ($g_sess->get_var("user")>0) ? "/ajax/ajax-location.php" : "/ajax/ajax-login.php";
	
	$image_path = lookup_config('product_image_path');
	
	if($g_sess->get_var("user")) {
		$sf_product_class = (user_in_sf($g_sess->get_var("user"), 0, 0, $_GET['id'])) ? "liked" : "";
	}
	
	$product_result = product_lookup($_GET['id']);
	
	if($product_result){
	
		$row = @mysql_fetch_array($product_result);
/*		
		echo("<pre>");
		print_r($row);
		echo("</pre>");
*/		
		$owner = $row['user_id'];
		$status = $row['status'];
		$product_id = $row['product_id'];
		$product = $row['product'];
		$upc = $row['upc'];
		$item_nbr = $row['item_nbr'];
		$manufacturer_id = $row['manufacturer_id'];

		if($manufacturer_id){
			$manufacturer_result = user_search($manufacturer_id);
			$manufacturer_row = mysql_fetch_array($manufacturer_result);			
			$manufacturer = $manufacturer_row['company'];
		}

		$keywords = $row['keywords'];
		$short_description = stripslashes($row['short_description']);
		$long_description = stripslashes($row['long_description']);
		$style_id = $row['style_id'];

		if($style_id){
			$style_result = style_lookup($style_id);
			$style_row = mysql_fetch_array($style_result);
			$style = $style_row['style'];		
		}

		$genre_id = $row['genre_id'];
		$msrp = $row['msrp'];
		$width = $row['width'];
		$length = $row['length'];
		$height = $row['height'];
		$weight = $row['weight'];
		$materials = $row['materials'];
	
		$demensions_array = array();
	
		if($width>0)
			$demensions_array[] = $width;
			
		if($length>0)
			$demensions_array[] = $length;
		
		if($height>0)
			$demensions_array[] = $height;		
	
		$demensions = implode(" x ", $demensions_array);
	
		$meta_title = $row['meta_title'];
		$meta_keywords = $row['meta_keywords'];
		$meta_description = $row['meta_description'];
	
		$discontinued_ind = $row['discontinued_ind'];
	
		$image_result = product_image_search($_GET['id'], 0, 1, 0, 2);
		if($image_result){
			$image_row = mysql_fetch_array($image_result);
			$image = $image_row['image'];
			$alt = $image_row['image_alt'];
		}
	
		// SEARCH SOURCES have and can get this product or line
		$source_result = source_search_by_product($_GET['id'], $_REQUEST['search_state'], array(0,1,2));
		
		$i = 0;
	
		$carry_output = "";
		$source_array = array();
		
		if($source_result){
			
			$source_count = mysql_num_rows($source_result);
			
			while($source_row = mysql_fetch_array($source_result)){
				if($i==$item_width){
					$i = 0;

					$carry_output.= "</ul><ul>";
					$j++;
		
					if($j==$row_limit)
						break;
				}
				
				//check for thumbnail
				if(!file_exists($_SERVER['DOCUMENT_ROOT'].$source_image_directory . $source_row[0] . '_thumb.jpeg') && file_exists($_SERVER['DOCUMENT_ROOT'] . $source_image_directory . $source_row[0] . '_main.jpeg')){
					image_crop($_SERVER['DOCUMENT_ROOT'] . $source_image_directory . $source_row[0] . '_main.jpeg', $_SERVER['DOCUMENT_ROOT'] . $source_image_directory . $source_row[0] . '_thumb.jpeg', 135, 150, 100);
				}
				
				$this_sf_class = (user_in_sf($g_sess->get_var("user"), 0, $source_row[0], 0)) ? "liked" : "";
				
				switch($source_row['carry']){
				
					case 1:
						$msg = 'Product In Stock';
						$icon = 'icon-product-stock.jpg';
						$class = 'instock';
						break;
					case 0:
						$msg = 'Carries Product Line / Can Order';
						$icon = 'icon-product-line.jpg';
						$class = 'canorder';
						break;
					case 2:
						$msg = 'Get The Style';
						$icon = 'icon-product-style.jpg';
						$class = 'getthestyle';
						break;
				
				}
				
				$carry_output.= '<li class="relative">
									<a href="/sources/' . seo_friendly($source_row['company']) . '/' . $source_row[0] . '/">
										<img src="http://dev.restylesource.com' . $source_image_directory . $source_row[0] . '_thumb.jpeg" />
									</a>
									<a href="#" class="add-to-sf ' . $this_sf_class . '" data-type="source" data-id="' . $source_row[0] . '" title="Add to Style File">Add to Style File</a>
									<h3>' . $source_row['company'] . '</h3>
									<h4>' . $source_row['city'] . ', ' . $source_row['state'] . '</h4>
									
									<div class="hover left">
									<div class="tag prod-stock ' . $class . '"> ' . $msg . '</div> <!-- end tag -->
									<img src="/assets/images/' . $icon . '" class="icons left" /> 	</div> 								
									<div class="hover left">
									<div class="tag prod-stock instockemail">Email Source</div> <!-- end tag -->
									<a href="/ajax/ajax-product-email.php?product_id=' . $_GET['id'] . '&source_id=' . $source_row[0] . '" class="email">Email us</a>	
									</div>
								</li>';
				$i++;
			}
			
			
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
	<meta property="og:image" content="/<?=$image_path.$image?>"/>
	<meta property="og:title" content="<?=$product?>"/>

	<meta name="description" content="<?=$meta_description?>" />
	<meta name="keywords" content="<?=$meta_keywords?>" />
	<meta name="title" content="<?=$meta_title?>" />

	<title><?=$product?> | <? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/title.php'); ?></title>

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

<body class="source sources product-detail logged-in">

	<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'); ?>

	<div id="wrapper-page">

		<header>
			<h1><?=$product?></h1>
			
			<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/social.php'); ?>

		</header>

		<section role="main">

			<div id="featured-source">
				<div class="image block featured">
					<img src="http://dev.restylesource.com/<?=$image_path.$image?>" alt="<?=$meta_title?>" />
					<a href="#" class="add-to-sf <?=$sf_product_class?>" data-type="product" data-id="<?=$_GET['id']?>" title="Add to Style File">Add to Style File</a>
					<span class='st_facebook_hcount' displayText='Facebook'></span> <span class='st_twitter_hcount' displayText='Tweet'></span> <span class='st_pinterest_hcount' displayText='Pinterest'></span>
				</div>
				<div class="info">
					<p><?=$long_description?></p>
					<?if($discontinued_ind && in_array($g_sess->get_var("systemTeam"), array('retailer','manufacturer'))){?><span style="font-size:13px;" class="discontinued" style="color:#f47920"><a>DISCONTINUED &gt;</a></span><?}?>
					<div class="product-meta" style="margin-top:0;">
					<!--<div class="product-meta">-->
						<?if($manufacturer && $is_source){?>
						<h2>Manufacturer</h2>
						<p><?=$manufacturer?></p>
						<?}?>
						<?if($item_nbr && $is_source){?>
						<h2>Item Number</h2>
						<p><?=$item_nbr?></p>
						<?}?>
						<?if($style){?>
						<h2>Style</h2>
						<p><?=$style?></p>
						<?}?>
						<?if($msrp > 0){?>
						<h2>MSRP</h2>
						<p>$<?=$msrp?></p>
						<?}?>
						<?if($demensions){?>
						<h2>Dimensions</h2>
						<p>Overall: <?=$demensions?></p>
						<?}?>
						<?if($weight>0){?>
						<h2>Weight</h2>
						<p><?=$weight?> LBS</p>
						<?}?>
						<?if($materials){?>
						<h2>Material</h2>
						<p><?=$materials?></p>
						<?}?>
					</div> <!-- // .product-meta -->
					
                    <!--
                    <div style="font-size:12px;margin-top:12px;padding-left:63px;background:url(/assets/images/icon-email-mid.png) 0 4px no-repeat;">Stock + prices vary.<br/>
					Please contact Sources below for more information.</div>
				</div> -->
                
                
                
                <!-- PUT THE CONDITION IN HERE START -->
                <? if($carry_output || $outside_output || $look_output || $source_style_output){ ?>
               <div class="product-legend" style="margin-top:0;">
                <h2>Legend</h2>
               </div>
                	<ul class="side-key">
                    	<li class="true-clear"><span><img src="/assets/images/icon-small-gray-email.jpg" /></span> Email Source</li>
                        <li class="true-clear"><span><img src="/assets/images/icon-product-stock.jpg" /></span> Product In Stock</li>
                        <li class="true-clear"><span><img src="/assets/images/icon-product-line.jpg" /></span> Carries Product Line / Can Order</li>
                        <li class="true-clear"><span><img src="/assets/images/icon-product-style.jpg" /></span> Get The Style</li>
                    </ul>
                    
                    <p class="tiny">Stock, prices, and options vary. <br />Please contact Sources below for more information.</p>
                    
                    <? } ?>
                
                <!-- PUT THE CONDITION IN HERE END -->
                
                
			</div>

		</section> <!-- // [role="main"] -->
<br clear="all"><br />
		<?if($carry_output || $outside_output || $look_output || $source_style_output){?>
		<section id="get-the-look">
			<h2><span>Find it Here</span> <a href="<?=$change_location_link?>" class="change-location">Change location ></a>
            </h2> 
        
            
			<ul>
				<?=$carry_output?>
			</ul>
		</section>
		<?}?>
		
		
		
	</div> <!-- // #wrapper-page -->

	<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'); ?>

	<!--
	BODY JS
	-->
	<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/js_includes.php'); ?>
	
	<script>
		$('select.uniform').uniform();

		// Product email
		$("#get-the-look .email").dialogAjax({
			dialogClass: "product-email",
			buttons: {
				Submit: {
					text: "Submit",
					"class":'button primary',
					click: function() {
					
						var data = $('#product-email-form').serialize();
					
						thisDialog = $( this );
					
						if(data){
					
							$.ajax({
							  type: 'POST',
							  url: '/ajax/ajax-functions.php?f=contact',
							  data: data,
							  success: function(data) {
								//$( this ).dialog( "close" );
								//dialog.remove();
								thisDialog.dialog("close");
							  },
							  error: function(e){
								alert(e);
							  }
							  
							});

						} else {

							var str = $("#login-form").serialize();
					
							$.ajax({  
							  type: "POST", 
							  cache: false,
								  dataType: "json", 
							  url: "/ajax/ajax-login-process.php",  
							  data: str,  
							  success: function(data) {  
							  
								if(data.user_id>0){
									location.reload();
								} else {
									$('#login-error').html(data.error);
									loadingIndicator.fadeOut(200);
								}
							  }  
							});
						
						}
					
						loadingIndicator.fadeIn(200);
						// Uncomment the following 2 lines once loading is done
						// $( this ).dialog( "close" );
						// dialog.remove();
						//$('form.location').submit();
					}
				}
			}
		});

		// Discontinued warn
		$(".discontinued").dialogAjax({
			dialogClass: "discontinued-warn",
			buttons: {
				Cancel: {
                    text: "Close",
                    "class":'button primary',
                    click: function() {
						thisDialog = $( this );
						thisDialog.dialog("close");
                    }
                }
			}
		});
		
		
		<!-- EDDIE/SEAN Updates -->
		$(document).ready(function() {
			
			// make the tooltips showup on hover
			$('#get-the-look li .hover').live('hover', function(event) {

				var tags = $(this).children('.tag');
				console.log(tags); 
				$(tags).toggle();
				
			}); // end function
			
			
		}); // end onload
		
	</script>
<a href="http://pinterest.com/pin/create/button/" class="pin-it-button" count-layout="none"><img src="//assets.pinterest.com/images/PinExt.png" alt="Pin it" / ></a> <script type="text/javascript" src="http://assets.pinterest.com/js/pinit.js"></script>
</body>
</html>
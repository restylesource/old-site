<?

	$require_login = 1;

	include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/db.php");	
	include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/logincheck.php");

	$block_img_path = "../home-blocks/";

	if($_POST){
	
		//echo("<pre>");
		//print_r($_POST);
	
		home_page_save($_POST['meta_title'], $_POST['meta_description'], $_POST['meta_keywords'], $_POST['image1_id'], $_POST['image1_caption_1'], $_POST['image1_caption_2'], $_POST['image1_cta'], $_POST['image2_id'], $_POST['image2_caption_1'], $_POST['image2_caption_2'], $_POST['image2_cta'], $_POST['image3_id'], $_POST['image3_caption_1'], $_POST['image3_caption_2'], $_POST['image3_cta'], $_POST['image4_id'], $_POST['image4_caption_1'], $_POST['image4_caption_2'], $_POST['image4_cta'], $_POST['image5_id'], $_POST['image5_caption_1'], $_POST['image5_caption_2'], $_POST['image5_cta'], $_POST['image6_id'], $_POST['image6_caption_1'], $_POST['image6_caption_2'], $_POST['image6_cta'], $_POST['image7_id'], $_POST['image7_caption_1'], $_POST['image7_caption_2'], $_POST['image7_cta'], $_POST['image8_id'], $_POST['image8_caption_1'], $_POST['image8_caption_2'], $_POST['image8_cta'], $_POST['top_banner'], $_POST['top_banner_title'], $_POST['top_banner_caption'], $_POST['block_1'], $_POST['block_1_title'], $_POST['block_1_text'], $_POST['block_1_alt'], $_POST['block_1_link_title'], $_POST['block_1_bg'], $_POST['block_2'], $_POST['block_2_title'], $_POST['block_2_text'], $_POST['block_2_alt'], $_POST['block_2_link_title'], $_POST['block_2_bg'], $_POST['block_3'], $_POST['block_3_title'], $_POST['block_3_text'], $_POST['block_3_alt'], $_POST['block_3_link_title'], $_POST['block_3_bg'], $_POST['block_4'], $_POST['block_4_title'], $_POST['block_4_text'], $_POST['block_4_alt'], $_POST['block_4_link_title'], $_POST['block_4_bg'], $_POST['bottom_banner'], $_POST['bottom_alt'], $_POST['bottom_title']);
	
		// Let's check for files
		if ($_FILES["block_1_img"]["tmp_name"] && $_FILES["block_1_img"]["error"] < 1)
			@move_uploaded_file($_FILES["block_1_img"]["tmp_name"], $block_img_path . 'block_1_img.jpg');

		if ($_FILES["block_2_img"]["tmp_name"] && $_FILES["block_2_img"]["error"] < 1)
			@move_uploaded_file($_FILES["block_2_img"]["tmp_name"], $block_img_path . 'block_2_img.jpg');
			
		if ($_FILES["block_3_img"]["tmp_name"] && $_FILES["block_3_img"]["error"] < 1)
			@move_uploaded_file($_FILES["block_3_img"]["tmp_name"], $block_img_path . 'block_3_img.jpg');
			
		if ($_FILES["block_4_img"]["tmp_name"] && $_FILES["block_4_img"]["error"] < 1)
			move_uploaded_file($_FILES["block_4_img"]["tmp_name"], $block_img_path . 'block_4_img.jpg');						

		if ($_FILES["bottom_banner_img"]["tmp_name"] && $_FILES["bottom_banner_img"]["error"] < 1)
			@move_uploaded_file($_FILES["bottom_banner_img"]["tmp_name"], $block_img_path . 'bottom_banner_img.jpg');	
	
	}

	$row = home_page_lookup();

	if($row){
		$meta_title = $row['meta_title'];
		$meta_description = $row['meta_description'];
		$meta_keywords = $row['meta_keywords']; 
		$image1_id = $row['image1_id']; 
		$image1_caption_1 = $row['image1_caption_1']; 
		$image1_caption_2 = $row['image1_caption_2']; 
		$image1_cta = $row['image1_cta']; 
		$image2_id = ($row['image2_id'] >0 ) ? $row['image2_id'] : ""; 
		$image2_caption_1 = $row['image2_caption_1']; 
		$image2_caption_2 = $row['image2_caption_2']; 
		$image2_cta = $row['image2_cta']; 
		$image3_id = ($row['image3_id'] > 0) ? $row['image3_id'] : "";; 
		$image3_caption_1 = $row['image3_caption_1']; 
		$image3_caption_2 = $row['image3_caption_2']; 
		$image3_cta = $row['image3_cta']; 
		$image4_id = ($row['image4_id'] > 0) ? $row['image4_id'] : "";
		$image4_caption_1 = $row['image4_caption_1']; 
		$image4_caption_2 = $row['image4_caption_2']; 
		$image4_cta = $row['image4_cta']; 
		$image5_id = ($row['image5_id'] > 0) ? $row['image5_id'] : "";
		$image5_caption_1 = $row['image5_caption_1']; 
		$image5_caption_2 = $row['image5_caption_2']; 
		$image5_cta = $row['image5_cta']; 
		$image6_id = ($row['image6_id'] > 0) ? $row['image6_id'] : "";
		$image6_caption_1 = $row['image6_caption_1']; 
		$image6_caption_2 = $row['image6_caption_2']; 
		$image6_cta = $row['image6_cta']; 
		$image7_id = ($row['image7_id'] > 0) ? $row['image7_id'] : "";
		$image7_caption_1 = $row['image7_caption_1']; 
		$image7_caption_2 = $row['image7_caption_2']; 
		$image7_cta = $row['image7_cta']; 
		$image8_id = ($row['image8_id'] > 0) ? $row['image8_id'] : "";
		$image8_caption_1 = $row['image8_caption_1']; 
		$image8_caption_2 = $row['image8_caption_2']; 
		$image8_cta = $row['image8_cta']; 
		$top_banner = $row['top_banner']; 
		$top_banner_title = $row['top_banner_title']; 
		$top_banner_caption = $row['top_banner_caption']; 
		$block_1 = $row['block_1']; 
		$block_1_title = $row['block_1_title']; 
		$block_1_text = $row['block_1_text']; 
		$block_1_alt = $row['block_1_alt']; 
		$block_1_link_title = $row['block_1_link_title']; 
		$block_1_bg = $row['block_1_bg']; 
		$block_2 = $row['block_2']; 
		$block_2_title = $row['block_2_title']; 
		$block_2_text = $row['block_2_text']; 
		$block_2_alt = $row['block_2_alt']; 
		$block_2_link_title = $row['block_2_link_title']; 
		$block_2_bg = $row['block_2_bg'];
		$block_3 = $row['block_3']; 
		$block_3_title = $row['block_3_title']; 
		$block_3_text = $row['block_3_text'];
		$block_3_alt = $row['block_3_alt']; 
		$block_3_link_title = $row['block_3_link_title']; 
		$block_3_bg = $row['block_3_bg']; 
		$block_4 = $row['block_4']; 
		$block_4_title = $row['block_4_title']; 
		$block_4_text = $row['block_4_text'];
		$block_4_alt = $row['block_4_alt']; 
		$block_4_link_title = $row['block_4_link_title']; 
		$block_4_bg = $row['block_4_bg']; 
		$bottom_banner = $row['bottom_banner'];
		$bottom_alt = $row['bottom_alt'];
		$bottom_title = $row['bottom_title'];
	
		if(file_exists($block_img_path . 'block_1_img.jpg'))
			$block_img_1 = '<img src="' . $block_img_path . 'block_1_img.jpg' . '" />';

		if(file_exists($block_img_path . 'block_2_img.jpg'))
			$block_img_2 = '<img src="' . $block_img_path . 'block_2_img.jpg' . '" />';
			
		if(file_exists($block_img_path . 'block_3_img.jpg'))
			$block_img_3 = '<img src="' . $block_img_path . 'block_3_img.jpg' . '" />';			

		if(file_exists($block_img_path . 'block_4_img.jpg'))
			$block_img_4 = '<img src="' . $block_img_path . 'block_4_img.jpg' . '" />';

		if(file_exists($block_img_path . 'bottom_banner_img.jpg'))
			$bottom_banner_img = '<img src="' . $block_img_path . 'bottom_banner_img.jpg' . '" width="700" />';
	
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Edit Home Page</title>

<?php include("includes/css.php"); ?>
</head>
<body>
<div class="container">   
  <!--begin header nav -->
	<?php include("includes/header.php"); ?>
    <!-- end header nav-->
    <!-- begin main site nav-->
	<?php include("includes/nav.php"); ?>
	<!-- end main site nav-->
    <!--begin breadcrumb menu-->
	<div class="breadcrumbs">
		<ul>
			<li class="home"><a href="/admin"></a></li>
			<li class="break">&#187;</li>
			<li><a href="#">Edit Home Page</a></li>
		</ul>
	</div>
    <!-- begin content area-->
    <!-- begin products management table-->
	<div class="section">
			
		<div class="full">
			<div class="box">
				<div class="title">
					<h2>Edit Home Page</h2>
				</div>
				<div class="content">
                	<form id="product-edit" method="post" enctype="multipart/form-data">
                		<div class="line">
							<h2><strong>SEO Settings</strong></h2>
							<label>Meta Title:</label>
							<input type="text" class="small" name="meta_title" value="<?=$meta_title?>" /><br clear="all">
							<label>Meta Description:</label>
							<input type="text" class="big" name="meta_description" value="<?=$meta_description?>" /><br clear="all">
							<label>Meta Keywords:</label>
							<input type="text" class="big" name="meta_keywords" value="<?=$meta_keywords?>" /><br clear="all">
						</div>
                		<div class="line">
							<h2><strong>IMAGE ROTATION 1</strong></h2>
							<label>Page ID/URL:</label>
							<input type="text" class="small" name="image1_id" value="<?=$image1_id?>" /> Must be an Active Source<br clear="all">
							<label>Caption 1:</label>
							<input type="text" class="small" name="image1_caption_1" value="<?=$image1_caption_1?>" /><br clear="all">
							<label>Caption 2:</label>
							<input type="text" class="small" name="image1_caption_2" value="<?=$image1_caption_2?>" /><br clear="all">
							<label>CTA:</label>
							<input type="text" class="small" name="image1_cta" value="<?=$image1_cta?>" /><br clear="all">
						</div>
						<div class="line">
							<h2><strong>IMAGE ROTATION 2</strong></h2>
							<label>Page ID/URL:</label>
							<input type="text" class="small" name="image2_id" value="<?=$image2_id?>" /> Must be an Active Source<br clear="all">
							<label>Caption 1:</label>
							<input type="text" class="small" name="image2_caption_1" value="<?=$image2_caption_1?>" /><br clear="all">
							<label>Caption 2:</label>
							<input type="text" class="small" name="image2_caption_2" value="<?=$image2_caption_2?>" /><br clear="all">
							<label>CTA:</label>
							<input type="text" class="small" name="image2_cta" value="<?=$image2_cta?>" /><br clear="all">
						</div>
						<div class="line">
							<h2><strong>IMAGE ROTATION 3</strong></h2>
							<label>Page ID/URL:</label>
							<input type="text" class="small" name="image3_id" value="<?=$image3_id?>" /> Must be an Active Source<br clear="all">
							<label>Caption 1:</label>
							<input type="text" class="small" name="image3_caption_1" value="<?=$image3_caption_1?>" /><br clear="all">
							<label>Caption 2:</label>
							<input type="text" class="small" name="image3_caption_2" value="<?=$image3_caption_2?>" /><br clear="all">
							<label>CTA:</label>
							<input type="text" class="small" name="image3_cta" value="<?=$image3_cta?>" /><br clear="all">
						</div>
						<div class="line">
							<h2><strong>IMAGE ROTATION 4</strong></h2>
							<label>Page ID/URL:</label>
							<input type="text" class="small" name="image4_id" value="<?=$image4_id?>" /> Must be an Active Source<br clear="all">
							<label>Caption 1:</label>
							<input type="text" class="small" name="image4_caption_1" value="<?=$image4_caption_1?>" /><br clear="all">
							<label>Caption 2:</label>
							<input type="text" class="small" name="image4_caption_2" value="<?=$image4_caption_2?>" /><br clear="all">
							<label>CTA:</label>
							<input type="text" class="small" name="image4_cta" value="<?=$image4_cta?>" /><br clear="all">
						</div>
						<div class="line">
							<h2><strong>IMAGE ROTATION 5</strong></h2>
							<label>Page ID/URL:</label>
							<input type="text" class="small" name="image5_id" value="<?=$image5_id?>" /> Must be an Active Source<br clear="all">
							<label>Caption 1:</label>
							<input type="text" class="small" name="image5_caption_1" value="<?=$image5_caption_1?>" /><br clear="all">
							<label>Caption 2:</label>
							<input type="text" class="small" name="image5_caption_2" value="<?=$image5_caption_2?>" /><br clear="all">
							<label>CTA:</label>
							<input type="text" class="small" name="image5_cta" value="<?=$image5_cta?>" /><br clear="all">
						</div>
						<div class="line">
							<h2><strong>IMAGE ROTATION 6</strong></h2>
							<label>Page ID/URL:</label>
							<input type="text" class="small" name="image6_id" value="<?=$image6_id?>" /> Must be an Active Source<br clear="all">
							<label>Caption 1:</label>
							<input type="text" class="small" name="image6_caption_1" value="<?=$image6_caption_1?>" /><br clear="all">
							<label>Caption 2:</label>
							<input type="text" class="small" name="image6_caption_2" value="<?=$image6_caption_2?>" /><br clear="all">
							<label>CTA:</label>
							<input type="text" class="small" name="image6_cta" value="<?=$image6_cta?>" /><br clear="all">
						</div>
						<div class="line">
							<h2><strong>IMAGE ROTATION 7</strong></h2>
							<label>Page ID/URL:</label>
							<input type="text" class="small" name="image7_id" value="<?=$image7_id?>" /> Must be an Active Source<br clear="all">
							<label>Caption 1:</label>
							<input type="text" class="small" name="image7_caption_1" value="<?=$image7_caption_1?>" /><br clear="all">
							<label>Caption 2:</label>
							<input type="text" class="small" name="image7_caption_2" value="<?=$image7_caption_2?>" /><br clear="all">
							<label>CTA:</label>
							<input type="text" class="small" name="image7_cta" value="<?=$image7_cta?>" /><br clear="all">
						</div>
						<div class="line">
							<h2><strong>IMAGE ROTATION 8</strong></h2>
							<label>Page ID/URL:</label>
							<input type="text" class="small" name="image8_id" value="<?=$image8_id?>" /> Must be an Active Source<br clear="all">
							<label>Caption 1:</label>
							<input type="text" class="small" name="image8_caption_1" value="<?=$image8_caption_1?>" /><br clear="all">
							<label>Caption 2:</label>
							<input type="text" class="small" name="image8_caption_2" value="<?=$image8_caption_2?>" /><br clear="all">
							<label>CTA:</label>
							<input type="text" class="small" name="image8_cta" value="<?=$image8_cta?>" /><br clear="all">
						</div>
						<div class="line">
							<h2><strong>TOP BANNER (Design Dilemmas)</strong></h2>
							<label>Page ID/URL:</label>
							<input type="text" class="small" name="top_banner" value="<?=$top_banner?>" /><br clear="all">
							<label>Title:</label>
							<input type="text" class="small" name="top_banner_title" value="<?=$top_banner_title?>" /><br clear="all">
							<label>Caption:</label>
							<input type="text" class="small" name="top_banner_caption" value="<?=$top_banner_caption?>" /><br clear="all">
						</div>
						<div class="line">
							<h2><strong>BLOCK 1</strong></h2>
							<label>Page ID/URL:</label>
							<input type="text" class="small" name="block_1" value="<?=$block_1?>" /><br clear="all">
							<label>Tab Title:</label>
							<input type="text" class="small" name="block_1_title" value="<?=$block_1_title?>" /><br clear="all">
							<label>Text:</label>
							<input type="text" class="small" name="block_1_text" value="<?=$block_1_text?>" /><br clear="all">
							<label>Image:</label>
							<input type="file" name="block_1_img" style="height:25px;"/><br />Image size: 282x190 pixels<br clear="all"><br />
							<label>Alt Text:</label>
               				<input type="text" class="small" name="block_1_alt" value="<?=$block_1_alt?>" /><br clear="all">
               				<label>Link Title:</label>
               				<input type="text" class="small" name="block_1_link_title" value="<?=$block_1_link_title?>" /><br clear="all">
               				<label>Background:</label>
               				<input type="text" class="small" name="block_1_bg" value="<?=$block_1_bg?>" /><br />
               				Example: (117, 43, 0, 0.65)<br clear="all"><br />
							<?=$block_img_1?>
						</div>
						<div class="line">
							<h2><strong>BLOCK 2</strong></h2>
							<label>Page ID/URL:</label>
							<input type="text" class="small" name="block_2" value="<?=$block_2?>" /><br clear="all">
							<label>Tab Title:</label>
							<input type="text" class="small" name="block_2_title" value="<?=$block_2_title?>" /><br clear="all">
               				<label>Text:</label>
							<input type="text" class="small" name="block_2_text" value="<?=$block_2_text?>" /><br clear="all">
							<label>Image:</label>
							<input type="file" name="block_2_img" style="height:25px;"/><br />Image size: 282x190 pixels<br clear="all"><br />
							<label>Alt Text:</label>
               				<input type="text" class="small" name="block_2_alt" value="<?=$block_2_alt?>" /><br clear="all">
               				<label>Link Title:</label>
               				<input type="text" class="small" name="block_2_link_title" value="<?=$block_2_link_title?>" /><br clear="all">
               				<label>Background:</label>
               				<input type="text" class="small" name="block_2_bg" value="<?=$block_2_bg?>" /><br />
               				Example: (117, 43, 0, 0.65)<br clear="all"><br />
							<?=$block_img_2?>							
						</div>
						<div class="line">
							<h2><strong>BLOCK 3 (Featured Source)</strong></h2>
							<label>Page ID/URL:</label>
							<input type="text" class="small" name="block_3" value="<?=$block_3?>" /><br clear="all">
							<label>Source Name:</label>
							<input type="text" class="small" name="block_3_title" value="<?=$block_3_title?>" /><br clear="all">
							<label>Location:</label>
							<input type="text" class="small" name="block_3_text" value="<?=$block_3_text?>" /><br clear="all">
							<label>Image:</label>
							<input type="file" name="block_3_img" style="height:25px;"/><br />Image size: 282x190 pixels<br clear="all"><br />
							<label>Alt Text:</label>
               				<input type="text" class="small" name="block_3_alt" value="<?=$block_3_alt?>" /><br clear="all">
               				<label>Link Title:</label>
               				<input type="text" class="small" name="block_3_link_title" value="<?=$block_3_link_title?>" /><br clear="all">
               				<label>Background:</label>
               				<input type="text" class="small" name="block_3_bg" value="<?=$block_3_bg?>" /><br />
               				Example: (117, 43, 0, 0.65)<br clear="all"><br />
							<?=$block_img_3?>							
						</div>
						<div class="line">
							<h2><strong>BLOCK 4 (Last One)</strong></h2>
							<label>Page ID/URL:</label>
							<input type="text" class="small" name="block_4" value="<?=$block_4?>" /><br clear="all">
							<label>Tab Title:</label>
							<input type="text" class="small" name="block_4_title" value="<?=$block_4_title?>" /><br clear="all">
							<label>Text:</label>
							<input type="text" class="small" name="block_4_text" value="<?=$block_4_text?>" /><br clear="all">
							<label>Image:</label>
							<input type="file" name="block_4_img" style="height:25px;"/><br />Image size: 282x190 pixels<br clear="all"><br />
							<label>Alt Text:</label>
               				<input type="text" class="small" name="block_4_alt" value="<?=$block_4_alt?>" /><br clear="all">
               				<label>Link Title:</label>
               				<input type="text" class="small" name="block_4_link_title" value="<?=$block_4_link_title?>" /><br clear="all">
               				<label>Background:</label>
               				<input type="text" class="small" name="block_4_bg" value="<?=$block_4_bg?>" /><br />
               				Example: (117, 43, 0, 0.65)<br clear="all"><br />
							<?=$block_img_4?>
						</div>
						<div class="line">
							<h2><strong>BOTTOM BANNER (Bottom of Page)</strong></h2>
							<label>Page ID/URL:</label>
							<input type="text" class="small" name="bottom_banner" value="<?=$bottom_banner?>" /><br clear="all">
							<label>Image:</label>
							<input type="file" name="bottom_banner_img" style="height:25px;"/><br />Image size: 870x60 pixels<br clear="all"><br />
							<label>Alt Text:</label>
               				<input type="text" class="small" name="bottom_alt" value="<?=$bottom_alt?>" /><br clear="all">
               				<label>Link Title:</label>
               				<input type="text" class="small" name="bottom_title" value="<?=$bottom_title?>" /><br clear="all"><br />
							<?=$bottom_banner_img?>
						</div>
                     	<div class="line button">
						<button onclick="top.location= '/admin/index.php';" class="red"><span>Cancel</span></button>
                    <!-- opens up sale form for one or multiple items.-->
                    <button type="submit" class="green"><span><?if($g_sess->get_var("systemTeam") == "admin" && $product_id > 0 && $owner != $g_sess->get_var("user") ){?>Save & Assume Ownership<?} else {?>Save</span><?}?></button>
                    
                    </div>
                </form>
                    <!--Delete button will remove record from DB. Needs to have a javascript confirm box that only after confirming will it delete the record-->	
				</div>
			</div>
		</div>
	</div>
		
	<!-- begin help modals-->
    <div id="help1" style="display:none;">
        <h2>Adding Products To Shelf</h2>
        <p>Here you can add products to your store's shelf. Fill in all the details for each product and attach a product photo. Keywords and descriptions help users search for your product.
		</p>
    </div>
    <!-- end photographer management table-->
	<?php include("includes/footer.php"); ?>
</div>


<?php include("includes/js.php"); ?>
    <script type="text/javascript" src="js/jquery.MultiFile.js"></script>
    <script type="text/javascript">var cat = <?php print json_encode($cat_array);?>;</script>

	<script type="text/javascript">
	
	$('#product-edit').validate();
	
	$(".deletePhoto").click(function(e){
		var product_id = $(this).attr('data-product_id');
		var image_id = $(this).attr('data-image_id');
		
		var id = $(this).parent().parent().attr('id');
		
		$.post("ajax-functions.php", { f: "imageDelete" , product_id: product_id, image_id: image_id },
 			function(data) {
   				$('#'+id).fadeOut(300, function() { $('#'+id).remove(); });
 			}
		);
		return false;
	});
	
	$('input[type="radio"][name="status"]').change(function() {
     		if(this.checked) {
     			$('#product_status').val((this.value));
     		}
     });
	
    $(function() { 
      var options = { 
          autoOpen: false, 
          width: 500, 
          height: 400,
          modal: true,
          closeText: '',
        }; 
      $([1, 2, 3, 4]).each(function() { 
        var num = this; 
        var dlg = $('#help' + num)
          .dialog(options); 
        $('.helpLink' + num).click(function() {
        dlg.css("display","visible"); 
          dlg.dialog("open"); 
          return false; 
        }); 
        $('.cancel' + num).click(function() { 
          dlg.dialog("close");
          dlg.get(0).reset();
          return false; 
        }); 
      }); 
    });
    </script>
</body>
</html>
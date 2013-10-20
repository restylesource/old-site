<?
	include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/db.php');	
	include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/logincheck.php');

	if(!$g_sess->get_var("user")){
		my_redirect('/ajax/ajax-login.php');
	}

	if($_GET['product_id'] > 0){
		$product_result = product_lookup($_GET['product_id']);
		
		$product_image_result = product_image_search($_GET['product_id'], 0, 1);
		if($product_image_result){
			$product_image_row = @mysql_fetch_array($product_image_result);
			$image = 'http://dev.restylesource.com/products/' . $product_image_row['image'];
					
		}
	}

?>
<div style="width:465px; margin:-60px auto 0;">
<div class="dialog-columns" style="margin-bottom:60px;">
	<div class="column60">
		<h1 style="border-right:1px solid #b4b3af; font-size:1.6em;">Please Contact<br/>Me With More<br/>Information On<br/>This Product</h1>
	</div>
	<div class="column40">
		<img style="float:right; border:1px solid #b4b3af;" src="<?=$image?>" width="135" height="135" alt="Paper Mache Lamp; Creative Lighting Design at Restyle Source">
	</div>
</div>
<div class="dialog-content">
	<div class="error" id="login-error"></div>
	<form id="product-email-form" method="post" action="">
		<input type="hidden" name="product_id" value="<?=$_REQUEST['product_id']?>" />
		<input type="hidden" name="source_id" value="<?=$_REQUEST['source_id']?>" />
		<input type="hidden" name="function" value="contact" />
	</form>
</div>

<h5 style="position:relative; top:24px;">*PLEASE NOTE: An item's availability depends on retailer inventory</h5>
</div>

<script src="assets/js/uniform/jquery.uniform.min.js"></script>
<script>
	$('.dialogAjax.product-email select.uniform').uniform();
</script>
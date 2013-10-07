<?
	include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/db.php');	
	include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/logincheck.php');

	if(!$g_sess->get_var("user")){
		my_redirect('/ajax/ajax-login.php');
	}

?>
<h1>Yes!</h1>
<h2>I want more information about this product.</h2>

<div class="dialog-content">
	<div class="error" id="login-error"></div>
	<img src="/assets/images/icon-email-large.png" />
	<form id="product-email-form" method="post" action="">
		<input type="hidden" name="product_id" value="<?=$_REQUEST['product_id']?>" />
		<input type="hidden" name="source_id" value="<?=$_REQUEST['source_id']?>" />
		<input type="hidden" name="function" value="contact" />
		<div class="row">
			<select name="interested" id="product-sort" class="uniform">
				<option>Interested in availability</option>
				<option>Interested in pricing</option>
			</select>
		</div>
	</form>
</div>

<script src="assets/js/uniform/jquery.uniform.min.js"></script>
<script>
	$('.dialogAjax.product-email select.uniform').uniform();
</script>
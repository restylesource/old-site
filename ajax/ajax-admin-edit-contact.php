<?
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';	

	$result = page_blocks_lookup($_GET['id'], $_GET['page_block_id']);
	
	if($result){
		$row = mysql_fetch_array($result);
		$address = $row['address'];
		$city = $row['city'];
		$state = $row['state'];
		$zip = $row['zip'];
		$hours1 = $row['hours1'];
		$hours2 = $row['hours2'];
		$phone = $row['phone'];
		$email = $row['email'];
	}
	
?>
<h1>Edit Contact</h1>

<form id="admin-text" method="post">
	<input type="hidden" name="id" value="<?=$_GET['id']?>" />
	<input type="hidden" id="action" name="action" />
	<input type="hidden" name="page_block_id" value="<?=$_GET['page_block_id']?>" />
	<p>Address</p>
	<input type="text" name="address" value="<?=$address?>" placeholder="Address" autofocus />
	<input type="text" name="city" value="<?=$city?>" placeholder="City" />
	<input type="text" name="state" value="<?=$state?>" placeholder="State" />
	<input type="text" name="zip" value="<?=$zip?>" placeholder="Zip" />
	<p>Hours</p>
	<input type="text" name="hours1" value="<?=$hours1?>" placeholder="Hours 1" />
	<input type="text" name="hours2" value="<?=$hours2?>" placeholder="Hours 2" />
	<p>Contact</p>
	<input type="text" name="phone" value="<?=$phone?>" placeholder="Phone" />
	<input type="text" name="email" value="<?=$email?>" placeholder="Email" />
</form>

<p class="error"><?=$error?></p>
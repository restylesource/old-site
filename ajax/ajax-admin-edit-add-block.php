<?

	include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';	
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/logincheck.php';

	$result = block_types();
	$block_options = build_generic_dropdown($result, $block_type_id, 0);

?>

<h1>Add Block to Page</h1>

<form id="admin-text" method="post">
	<input type="hidden" name="id" value="<?=$_REQUEST['id']?>" />
	<p>Select the type of block you would like to add:</p>
	<select id="select-block" class="uniform" name="block_type">
		<?=$block_options?>
	</select>
</form>

<p class="error"><?=$error?></p>

<script>
$(document).ready(function() {
	$('#select-block').uniform();
});
</script>
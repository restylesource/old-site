<?
	
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';	

	$result = page_blocks_lookup($_GET['id'], $_GET['page_block_id']);
	
	if($result){
		$row = mysql_fetch_array($result);
		$header = $row['header'];
		$content = $row['content'];
		$content_more = $row['content_more'];
	}

?>

<h1>Edit Content</h1>

<form id="admin-text" method="post">
	<input type="hidden" name="id" value="<?=$_GET['id']?>" />
	<input type="hidden" id="action" name="action" />
	<input type="hidden" name="page_block_id" value="<?=$_GET['page_block_id']?>" />
	<input type="text" placeholder="Header" name="header" value="<?=$header?>" autofocus />
	
	<div id="wysihtml5-toolbar" class="wysihtml5-toolbar" style="display: none;">
		<a data-wysihtml5-command="bold" class="wysihtml5-button bold">bold</a>
		<a data-wysihtml5-command="italic" class="wysihtml5-button italic">italic</a>

		<a data-wysihtml5-command="justifyLeft" class="wysihtml5-button alignleft">align left</a>
		<a data-wysihtml5-command="justifyCenter" class="wysihtml5-button aligncenter">align center</a>
		<a data-wysihtml5-command="justifyRight" class="wysihtml5-button alignright">align right</a>

		<a data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h1" title="Insert headline 1" class="wysihtml5-button header1">H1</a>
		<a data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h2" title="Insert headline 2" class="wysihtml5-button header2">H2</a>
		<a data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h3" title="Insert headline 3" class="wysihtml5-button header3">H3</a>

		<a data-wysihtml5-command="createLink" class="wysihtml5-button link">insert link</a>
		<div data-wysihtml5-dialog="createLink" style="display: none;" class="wysihtml5-createLink">
			<label>
				Link:
				<input data-wysihtml5-dialog-field="href" value="http://" class="text">
			</label>
			<a data-wysihtml5-dialog-action="save">OK</a> <a data-wysihtml5-dialog-action="cancel">Cancel</a>
		</div>
	</div>
	<textarea id="wysihtml5-textarea" name="content" placeholder="Enter your text ...">
		<?=$content?>
	</textarea>

	<div id="wysihtml5-toolbar2" class="wysihtml5-toolbar" style="display: none;">
		<a data-wysihtml5-command="bold" class="wysihtml5-button bold">bold</a>
		<a data-wysihtml5-command="italic" class="wysihtml5-button italic">italic</a>

		<a data-wysihtml5-command="justifyLeft" class="wysihtml5-button alignleft">align left</a>
		<a data-wysihtml5-command="justifyCenter" class="wysihtml5-button aligncenter">align center</a>
		<a data-wysihtml5-command="justifyRight" class="wysihtml5-button alignright">align right</a>

		<a data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h1" title="Insert headline 1" class="wysihtml5-button header1">H1</a>
		<a data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h2" title="Insert headline 2" class="wysihtml5-button header2">H2</a>
		<a data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h3" title="Insert headline 3" class="wysihtml5-button header3">H3</a>

		<a data-wysihtml5-command="createLink" class="wysihtml5-button link">insert link</a>
		<div data-wysihtml5-dialog="createLink" style="display: none;" class="wysihtml5-createLink">
			<label>
				Link:
				<input data-wysihtml5-dialog-field="href" value="http://" class="text">
			</label>
			<a data-wysihtml5-dialog-action="save">OK</a> <a data-wysihtml5-dialog-action="cancel">Cancel</a>
		</div>
	</div>
	<textarea id="wysihtml5-textarea2" name="content_more" placeholder="More content...">
		<?=$content_more?>
	</textarea>

</form>

<p class="error"><?=$error?></p>


<script src="/assets/js/wysihtml5/parser_rules/advanced.js"></script>
<script src="/assets/js/wysihtml5/dist/wysihtml5-0.3.0.min.js"></script>
<script>
var editor = new wysihtml5.Editor("wysihtml5-textarea", { // id of textarea element
  toolbar:      "wysihtml5-toolbar", // id of toolbar element
  stylesheets: ["/assets/css/wysihtml5.css"],
  parserRules:  wysihtml5ParserRules // defined in parser rules set 
});

var editor2 = new wysihtml5.Editor("wysihtml5-textarea2", { // id of textarea element
  toolbar:      "wysihtml5-toolbar2", // id of toolbar element
  stylesheets: ["/assets/css/wysihtml5.css"],
  parserRules:  wysihtml5ParserRules // defined in parser rules set 
});
</script>
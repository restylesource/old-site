<?

	include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';	
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/logincheck.php';

	$retailer_result = user_search($_GET['source_id'], '');
	
	if($retailer_result){
		$row = @mysql_fetch_array($retailer_result);	
		$description = $row['description'];
	}

?>
<h1>Edit Text</h1>

<p>A note about editing text.</p>

<div id="wysihtml5-toolbar" style="display: none;">
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

<form name="upload" id="admin-text" method="post">
	<input type="hidden" id="id" name="id" value="<?=$_GET['source_id']?>" />
	<textarea id="wysihtml5-textarea" name="description" placeholder="Enter your text ..." autofocus>
	<?=$description?>
	</textarea>
</form>

<script src="/assets/js/wysihtml5/parser_rules/advanced.js"></script>
<script src="/assets/js/wysihtml5/dist/wysihtml5-0.3.0.min.js"></script>
<script>
var editor = new wysihtml5.Editor("wysihtml5-textarea", { // id of textarea element
  toolbar:      "wysihtml5-toolbar", // id of toolbar element
  stylesheets: ["/assets/css/wysihtml5.css"],
  parserRules:  wysihtml5ParserRules // defined in parser rules set 
});
</script>
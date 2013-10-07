<?
	include_once('includes/db.php');	
	include_once('includes/logincheck.php');
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" /> 
	<title>Restyle Source</title>
<?php include("includes/js.php"); ?>
<?php include("includes/css.php"); ?>
<!--[if lte IE 8]>
		<script type="text/javascript" src="js/excanvas.min.js"></script>
	<![endif]-->

    <script type="text/javascript">
//This scirpt starts pins at 0 and counts up. the number is used in the front end coding. No numbers are sent to the php through the POST
if(pinNumber == null){
	var pinNumber = 0;
};

function repeat(obj) //Duplicates drop pins and adds inputs in form.
{
	pinNumber+=1;
	var pinName = 'dragpin' + pinNumber;
	var pinInfo = '<div class="dragpin"><span class="removePin"></span><img src="gfx/table-first.gif" width="40px" /></div>'
 	$(pinInfo).clone().appendTo('#imageContainer').attr('id',pinName).css('top',0,'left',0).data("my_id",pinNumber);
 	$('#'+pinName).children('.removePin').css('visibility','visible');
 	var newInput ='<div id="pinInput'+pinNumber+'"><input type="hidden" name="pinPosition[]"/><div class="line"><label for="productName'+pinNumber+'">Product Name:</label><input type="text" class="small" name="product[]"/></div></div>'
 $('#content').append(newInput);
 $('#pinInput'+pinNumber).data("my_id",pinNumber);
	$('#'+pinName).draggable();
}
//Makes a pin draggable and reports the location to the hidden input.
$(".dragpin").live("mousedown", function() {
  var this_id = $(this).data("my_id");
  //$('#content').children().removeClass('pinFocus');
  $(this).draggable({
	   // options...
	   containment: '#imageContainer',
	   drag: function(event,ui){
		  dragposition = ui.position;
		  $('div').filter(function() { return $.data(this, "my_id") == this_id; }).children('input:first').val(ui.position.left+','+ui.position.top);
	   }
  });
    //$('div').filter(function() { return $.data(this, "my_id") == this_id; }).addClass('pinFocus');
});


$(".dragpin").live("mouseover", function() {
  var this_id = $(this).data("my_id");
  $('#content').children().removeClass('pinFocus');
  $('div').filter(function() { return $.data(this, "my_id") == this_id; }).addClass('pinFocus');
});

//remove pin function finds all divs with data pin ID and removes them.
$(".removePin").live("click", function() {
  var this_id = $(this).parent("div").data("my_id");
//Re Run Click function to delete pins
$("div").filter(function() { return $.data(this, "my_id") == this_id; }).remove();
	
	});
//Ajax for photo preview so admins can upload a room photo and drop pins instantly.
$(document).ready(function()
	 {
		$('#photoimg').live('change', function()
		{
			$('#imageContainer').html('');
			$('#imageContainer').html('<div class="progressbar animated" style="border:0;padding-top:280px;"><div style="width : 100%;"></div></div>');
			$('#imageform').ajaxForm(
				{
					target: '#imageContainer'
				}).submit();
		});
	 });
</script>
<style>
.dragpin{
	width: 40px; height: px;
	position:absolute;]
	z-index:100;
}
         #imageContainer{
			 position:relative;
			 width:600px; 
			 height:600px;
			 overflow:hidden;
			 }
		#controls{
		 	width:350px;
			height:600px;
			overflow:scroll;
			overflow-x:hidden;
			overflow-y:auto;
			background:#eee;
			position:absolute;
			top:184px;
			right:0px;
		}
	.removePin{
		visibility:hidden;
		display:inline-block;
		background: url(gfx/closebutton.png) no-repeat transparent;
		height:20px;
		width:20px;
		position:absolute;
		left:27px;
		top:-11px;
		
	}
	.pinFocus{
    background-color:#EEDD82;
}
	.add ~ span {
		position:relative;
		bottom:10px;
		left:5px;
		font-family:Verdana, Geneva, sans-serif;
		font-weight:bold;
	}
	</style>
	
	
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
			<li class="home"><a href="#"></a></li>
			<li class="break">&#187;</li>
			<li><a href="roomsManager.php">RSS Rooms</a></li>
			<li class="break">&#187;</li>
			<li><a href="rooms/new-rss-room.php">Add a RSS Room</a></li>
		</ul>
	</div>
	<div class="section">
    	<div class="full">
        	<div class="box">
            	<div class="title">
                	<h2>Add A RSS Room</h2>
                    <span class="helpLink1 help"></span>
                </div>
                <div class="content">
                    <div id="imageContainer">
		     		</div> 
                    <div id="controls">
                    	<div style="height:100px;">
                            <form id="imageform" method="POST" enctype="multipart/form-data" action="ajax-room-preview.php">
								<div class="line">
									<label>Photo:</label>
									<input id="photoimg" type="file" class="blue" style="height:25px;" name="photoimg"/>
								</div>
			    			</form>
                        </div>
                        <div id="formArea">
			    			<form method="POST" action="">
                           		<div id="pindrop" class="line">
                           			<label>New Pin:</label>
                            		<a class="add" onclick="repeat(this);"></a><span>Add A Product Pin</span>
                                </div>
                                 <div class="line">
                                    <label>Room Title:</label>
                                    <input class="small" name="roomName"></input>
                                </div>
                                <div class="line">
                                    <label>Category:</label>
                                    <select class="medium">
                                        <option>Kitchen</option>
                                        <option>Bedroom</option>
                                    </select>
                                </div>
                                <div class="line">
                                    <label>Description:</label>
                                    <textarea class="small" rows="" cols=""></textarea>
                                </div>
                                <div id="content"></div>
                                <div class="line">
                                    <label>Feature Room:</label>
                                    <input type="checkbox" name="feature" value="yes" id="feature"/>
                                    <label for="feature">Yes</label>
                                </div>
                                <div class="line">
                                    <label>Publish Room:</label>
                                    <input type="checkbox" name="publish" value="yes" id="publish"/>
                                    <label for="publish">Yes</label>
                                </div>
                                <div class="line">
                                    <button class="blue"><span>Preview Room</span></button>
                                    <button class="green"><span>Save Room</span></button>
								</div>
                            </form>
                        </div>
                   </div>
                </div>
       		</div>
		</div>
	</div>
<?php include("includes/footer.php"); ?>
</div>
    <div id="help1" style="display:none;">
        <h2>Creating A Room</h2>
        <p>Creating a Room is easy.
            <ol>
                <li>Upload your finished room photo using the browse button.</li>
                <li>Add product pins and drag them over the product.</li>
                <li>Once the pin is over the product add the product name in the field on the right.</li>
                <li>Repeat for all your products.</li>
                <li>Click Save to save the room</li>
          </ol></p> 
    </div>
<script>
//script for using help links and edits.
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
  }); 
});
</script>
</body>
</html> 
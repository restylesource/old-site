<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" /> 
	<title>ReStyle | Billing Manager</title>
	<?php include("includes/js.php"); ?>
	<script type="text/javascript" src="js/jquery.MultiFile.js"></script>
	<?php include("includes/css.php"); ?>

	<!--[if lte IE 8]>
		<script type="text/javascript" src="js/excanvas.min.js"></script>
	<![endif]-->
	
	<script>
    /*
    These two scripts control the ability for users to add more categories or colors. You will see the function appends a new div with the select box. This needs to be replaced with PHP that will get the select options form the category or color table
    */
    
    //add remove more categories or colors
    
    function Repeat(obj)
    {
     var currentDiv = $(obj).prev("div");
     currentDiv.clone().insertAfter(currentDiv);
    }
    
    //remove form inputs
    function Delete(obj)
    {
     var currentDiv = $(obj).prev().prev("div");
     currentDiv.remove();
    }
    </script>	
</head>
<body>
	<div class="container">   
	<?php include("includes/header.php"); ?>
	<?php include("includes/nav.php"); ?>
        <div class="breadcrumbs">
            <ul>
                <li class="home"><a href="#"></a></li>
                <li class="break">&#187;</li>
                <li><a href="billing-manager.php">Billing</a></li>
            </ul>
        </div>
        <div class="section">
            <div class="full">
                <div class="box">
                    <div class="title">
                        <h2>Billing Manager</h2>
                        <span class="helpLink1 help"></span>
                    </div>
                    <div class="content">
                        <p>Here the billing manager would go. This area might be used for sending payment reminder, billing updates, checking billing status, verifying payment info and so on. This will be addressed in a later phase.
                        </p>
                    </div>
                </div>
            </div>		
		</div>
	<?php include("includes/footer.php"); ?>
	</div>
</body>
</html> 
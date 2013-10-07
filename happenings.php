<?

	$email_notify = "info@restylesource.com";

	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';	
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/logincheck.php';

	$change_location_link = "/ajax/ajax-location.php";

	$month = $_REQUEST['month'];

	if($_POST['state']){
		setcookie("state", $_POST['state']);
		my_redirect($_SERVER['PHP_SELF']);
	}

	if($_POST['title']){
		$body = "Title: " . $_POST['title'] . "\r\n";
		$body.= "City: " . $_POST['city'] . "\r\n";
		$body.= "Website: " . $_POST['website'] . "\r\n";
		$body.= "Comments: " . $_POST['comments'] . "\r\n";
		mail($email_notify, 'ReStyle Event Suggestion', $body, 'from: ' . $_POST['email']);
	}

	$state = ($_COOKIE['state']) ? $_COOKIE['state'] : users_state($g_sess->get_var("user"));

	//die($state);

	if($state=="")
		$state = "AZ";

	$date = date("Y-m-d");
	if($month){
		$symbol = ($_REQUEST['month'] > 0) ? "+" : "";
		$start_date = strtotime(date("Y/m/d", strtotime($date)) . " " . $symbol . $_REQUEST['month'] . " month");
	} else {
		$start_date = strtotime(date("Y/m/d", strtotime($date)));
	}
	
	$result = happening_search(date('Y', $start_date) . "/" . date('m', $start_date) . "/" . date('d'), date("Y-m-t", $start_date), 'active', $state_location);
	
	$month1_label = (date('m', $start_date) == date('m')) ? "THIS MONTH" : date("F Y", $start_date);

	while($row = @mysql_fetch_array($result)){
	
		if(date('j', strtotime($row['start_date'])) != date('j', strtotime($row['end_date']))){
			$days = date('j', strtotime($row['start_date'])) . " - " . date('j', strtotime($row['end_date']));
		} else {
			$days = date('j', strtotime($row['start_date']));
		}
	
		$time = date('h:i a', strtotime($row['start_date'])) . " - " . date('h:i a', strtotime($row['end_date']));
	
		$this_month_output.= '<ul><li>
									<a href="#" class="date">
										<img src="assets/images/temp/thumb-event.jpg">
										<p>' . date('M', strtotime($row['start_date'])) . ' <span>' . $days . '</span></p>
									</a>
									<div class="info">
										<h3>' . $row['title'] . '</h3>
										<h4>' . $time . '</h4>
										<p>' . $row['description'] . '</p>
										<h4>' . $row['city'] . ', ' . $row['state'] . '</h4>
										<a href="http://' . $row['url'] . '">' . $row['url'] . '</a>
									</div>
								</li></ul>';
	
	/*
		$this_month_output.= '<li>
									<a href="#" class="date">
										<img src="assets/images/temp/thumb-event.jpg" />
										<p>' . date('M', strtotime($row['start_date'])) . ' <span>' . $days . '</span></p>
									</a>
									<div class="info">
										<h3>' . $row['title'] . '</h3>
										<h4>' . $row['city'] . ', ' . $row['state'] . '</h4>
										<a href="http://' . $row['url'] . '">' . $row['url'] . '</a>
									</div>
								</li>';
	*/
	
	}
	
	if(!$this_month_output)
		$this_month_output = "<ul>Sorry, there are no events in your area this month.</ul>";
	
	$date = date("Y-m-d", $start_date);
	$start_date = strtotime(date("Y/m/d", strtotime($date)) . " +1 month");
	
	$result = happening_search(date('Y', $start_date) . "/" . date('m', $start_date) . "/01", date("Y-m-t", $start_date), 'active', $state_location);
	
	$month2_label = (date('m', $start_date) == date('m')) ? "THIS MONTH" : date("F Y", $start_date);
	
	while($row = @mysql_fetch_array($result)){
	
		if(date('j', strtotime($row['start_date'])) != date('j', strtotime($row['end_date']))){
			$days = date('j', strtotime($row['start_date'])) . " - " . date('j', strtotime($row['end_date']));
		} else {
			$days = date('j', strtotime($row['start_date']));
		}
	
			$time = date('h:i a', strtotime($row['start_date'])) . " - " . date('h:i a', strtotime($row['end_date']));
		
			$next_month_output.= '<ul><li>
									<a href="#" class="date">
										<img src="assets/images/temp/thumb-event.jpg">
										<p>' . date('M', strtotime($row['start_date'])) . ' <span>' . $days . '</span></p>
									</a>
									<div class="info">
										<h3>' . $row['title'] . '</h3>
										<h4>' . $time . '</h4>
										<p>' . $row['description'] . '</p>
										<h4>' . $row['city'] . ', ' . $row['state'] . '</h4>
										<a href="http://' . $row['url'] . '">' . $row['url'] . '</a>
									</div>
								</li></ul>';
	
	/*
		$next_month_output.= '<li>
									<a href="#" class="date">
										<img src="assets/images/temp/thumb-event.jpg" />
										<p>' . date('M', strtotime($row['start_date'])) . ' <span>' . $days . '</span></p>
									</a>
									<div class="info">
										<h3>' . $row['title'] . '</h3>
										<h4>' . $row['city'] . ', ' . $row['state'] . '</h4>
										<a href="http://' . $row['url'] . '">' . $row['url'] . '</a>
									</div>
								</li>';
	*/
	}
	
	if(!$next_month_output)
		$next_month_output = "<ul>Sorry, there are no events in your area this month.</ul>";


?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html lang="en-us" class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>    <html lang="en-us" class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>    <html lang="en-us" class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en-us" class="no-js"> <!--<![endif]-->
<head>

	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width" />

	<title><? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/title.php'); ?> | Local Happenings</title>

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

<body class="happenings logged-in">

	<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'); ?>

	<div id="wrapper-page">

		<header>
			<h1>Happenings <a href="<?=$change_location_link?>" class="change-location">Change location ></a></h1>
			<h2>Enjoy these upcoming events in your area</h2>

			<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/social.php'); ?>

			<!--<select id="happenings-sort" class="uniform">
				<option>Past/Upcoming</option>
				<option>Another option</option>
				<option>Yet another option</option>
				<option>Yet another option that's really really really long</option>
			</select>-->
		</header>

		<section role="main">

			<div class="event-list">
				<h2><?=$month1_label?></h2>
				
					<?=$this_month_output?>
				
				<h2><?=$month2_label?></h2>
				
					<?=$next_month_output?>
				

				<div class="pagination">
					<a href="<?=$_SERVER['PHP_SELF']?>?month=<?=$month-2?>">< Past Events</a>
					<a href="<?=$_SERVER['PHP_SELF']?>?month=<?=$month+2?>">Upcoming Events ></a>
				</div> <!-- / .pagination -->

			</div> <!-- / .event-list -->

			<div id="suggest-event">
				<h2>Suggest <span>an event</span></h2>
				<p>If you know of an event we should add to our list, let us know.</p>
				<form name="event" method="post" onSubmit="return validateEvent()">
					<input type="text" id="title" name="title" placeholder="Event" />
					<input type="text" id="city" name="city" placeholder="City" />
					<input type="text" id="website" name="website" placeholder="Website" />
					<input type="text" id="email" name="email" placeholder="Your Email" />
					<textarea name="comments" placeholder="Comments"></textarea>
					<input type="submit" value="Submit" />
				</form>
			</div> <!-- / #suggest-event -->

		</section> <!-- // [role="main"] -->

	</div> <!-- // #wrapper-page -->

	<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'); ?>

	<!--
	BODY JS
	-->
	<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/js_includes.php'); ?>
	
	<script src="/assets/js/input-placeholder.js"></script>
	
	<script>

		function validateEvent(){
		
			var is_good = true;
		
			if($('#title').val() == ""){
				$('#title').addClass('error');
				is_good = false;
			} else {
				$('#title').removeClass('error');
			}
		
			if($('#city').val() == ""){
				$('#city').addClass('error');
				is_good = false;
			} else {
				$('#city').removeClass('error');
			}
			
			if($('#website').val() == ""){
				$('#website').addClass('error');
				is_good = false;
			} else {
				$('#website').removeClass('error');
			}
			
			if($('#email').val() == ""){
				$('#email').addClass('error');
				is_good = false;
			} else {
				$('#email').removeClass('error');
			}
		
			return is_good;
		
		}


		$('select.uniform').uniform();

	</script>

</body>
</html>
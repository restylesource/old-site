<!DOCTYPE html>
<!--[if lt IE 7]> <html lang="en-us" class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>    <html lang="en-us" class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>    <html lang="en-us" class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en-us" class="no-js"> <!--<![endif]-->
<head>

	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width" />

	<title><? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/title.php'); ?></title>

	<!-- 
	CSS
	-->
	<!--[if !IE 6]><!-->
	<link rel="stylesheet" media="screen, projection" href="assets/css/main.css" />
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

<body class="registration source-registration logged-in">

	<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/header.php'); ?>

	<div id="wrapper-page">

		<header>
			<h1>Source Registration</h1>

			<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/social.php'); ?>
			
		</header>

		<section role="main">

			<form id="registration">
				<div class="row">
					<p>What type of Source are you?</p>
					<select id="source-type" class="uniform">
						<option>Option 1</option>
						<option>Option 2</option>
						<option>Option 3</option>
					</select>
				</div>
				<div class="row good-fit">
					<h2>Are we a good fit for one another?</h2>
					<p>In order to benefit our retail partners, certain criteria must be met. <br />Please answer a few questions so we can highlight your specialties.</p>
				</div>
				<span class="fields-required">* All fields required</span>
				<div class="row">
					<div class="column">
						<div class="row checkbox float">
							<p>Are you a brick and mortar retailer?</p>
							<label for="brick-mortar-yes">
								<input type="radio" name="brick-mortar" id="brick-mortar-yes" /> Yes
							</label>
							<label for="brick-mortar-no">
								<input type="radio" name="brick-mortar" id="brick-mortar-no" /> No
							</label>
						</div>
						<div class="row">
							<p>If so, how many stores do you have?</p>
							<input type="text" />
						</div>
						<div class="row">
							<p>Blog or web address</p>
							<input type="text" />
						</div>
						<div class="row items">
							<p>Mark your top 5 selling items</p>
							<div class="column checkboxes">
								<label for="item-home-furnishings">
									<input type="checkbox" id="item-home-furnishings" /> Home Furnishings
								</label>
								<label for="item-bedding-linens">
									<input type="checkbox" id="item-bedding-linens" /> Bedding/Linens
								</label>
								<label for="item-home-accessories">
									<input type="checkbox" id="item-home-accessories" /> Home Accessories
								</label>
								<label for="item-lighting-techniques">
									<input type="checkbox" id="item-lighting-techniques" /> Lighting/Antiques
								</label>
								<label for="item-other">
									<input type="checkbox" id="item-other" /> Other
								</label>
							</div>
							<div class="column checkboxes">
								<label for="item-jewelry">
									<input type="checkbox" id="item-jewelry" /> Jewelry
								</label>
								<label for="item-art">
									<input type="checkbox" id="item-art" /> Art
								</label>
								<label for="item-rugs">
									<input type="checkbox" id="item-rugs" /> Rugs
								</label>
								<label for="item-gifts">
									<input type="checkbox" id="item-gifts" /> Gifts
								</label>
							</div>
						</div>
					</div>
					<div class="column">
						<div class="row">
							<p>Upload an image of the front of your store</p>
							<input type="file" id="store-image" />
						</div>
						<div class="row">
							<p>Upload an image of the interior of your store</p>
							<input type="file" id="store-interior-image" />
						</div>
						<div class="row checkbox float">
							<p>Do you offer design services?</p>
							<label for="design-services-yes">
								<input type="radio" name="design-services" id="design-services-yes" /> Yes
							</label>
							<label for="design-services-no">
								<input type="radio" name="design-services" id="design-services-no" /> No
							</label>
						</div>
					</div>
				</div>
				<div class="row">
					<h2>Contact Info</h2>
					<div class="column">
						<input type="text" placeholder="First Name" />
						<input type="text" placeholder="Last Name" />
						<input type="text" placeholder="City" />
						<input type="text" placeholder="State" />
						<input type="text" placeholder="Zip Code" />
					</div>
					<div class="column">
						<input type="text" placeholder="Email" />
						<input type="text" placeholder="Confirm Email" />
						<input type="password" placeholder="Password" />
						<input type="password" placeholder="Confirm Password" />
						<div class="checkboxes small">
							<label for="agree">
								<input type="checkbox" id="agree" />
								<p>I agree to Restyle Source <a href="#">Terms and Conditions</a></p>
							</label>
							<label for="email-updates">
								<input type="checkbox" id="email-updates" />
								<p>Send me email updates</p>
							</label>
						</div>
						<input type="submit" value="Submit" />
					</div>
				</div>
			</form>

			<div id="discover-sf">
				<h2>Create your inventory</h2>
				<p>Once you are registered you can build your personal inventory on ReStyle Source. When you find items you carry, or similar items on the site simply select those items and they will be stored in your inventory.</p>
			</div>

		</section> <!-- // [role="main"] -->

	</div> <!-- // #wrapper-page -->

	<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/footer.php'); ?>

	<!--
	BODY JS
	-->
	<? include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/js_includes.php'); ?>
	<script>
		$('select.uniform').uniform();
		$('#store-image, #store-interior-image').customFileInput();

		// What is style file dialog
		$("label[for='brick-mortar-no']").wrap("<a class='no-bix-box' />").parents("a").attr("href", "/ajax/ajax-no-big-box.html").dialogAjax({
			width: 550,
			buttons: {
				SignUp: {
					text: "Please sign up for a personal account",
					"class":'button primary',
					click: function() {
						loadingIndicator.fadeIn(200);
						// Uncomment the following 2 lines once loading is done
						// $( this ).dialog( "close" );
						// dialog.remove();
					}
				}
			}
		});
	</script>

</body>
</html>
<?

	error_reporting(E_ERROR | E_PARSE);

	include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/db.php');	
	include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/logincheck.php');
	
	$source_output = "";
	
	$is_source = (in_array($g_sess->get_var("systemTeam"), array('retailer','manufacturer'))) ? 1 : 0;
	
	if($g_sess->get_var("user")){
		$sf_link = '/style/' . seo_friendly($g_sess->get_var("name")) . '/' . $g_sess->get_var("user") . '/';

		if(in_array($g_sess->get_var("systemTeam"), array('retailer','manufacturer'))){
			$user_result = user_search($g_sess->get_var("user"));
			$row = mysql_fetch_array($user_result);
			$home_link = '/sources/' . seo_friendly($row['company']) . '/' . $g_sess->get_var("user") . "/";	
		}

	} else {
		$sf_link = '/style-file.php';
	}
	
	if($g_sess->get_var("inventory_ind") >= 1 || $g_sess->get_var("systemTeam") == "admin"){
		
		$source_output = '<li><a href="/source/listing/manufacturers/">Manufacturers</a></li>';
	}

	$source_output.= '<li><a href="/source/listing/partners/">All Sources</a></li>';

	$source_output .= '<li><a href="/ajax/ajax-location.php?r=sources" class="change-location">Sources By State</a></li>';

	// lookup source types
	$result = sources_search('', 1, $is_source);
	
	if($result){
		
		while($row = mysql_fetch_array($result)){
			if($row['source'] != "Retailer" && $row['source'] != "Manufacturer"){
				$source_output .= '<li><a href="/source/' . seo_friendly($row['source']) . '/' . $row['source_id'] . '/">' . $row['source'] . '</a>';
			
				$sub_source_result = sub_source_lookup($row['source_id']);
				
				if(mysql_num_rows($sub_source_result)){
					$source_output .= "<ul>";
					
					while($row2 = mysql_fetch_array($sub_source_result)){
						$source_output .= '<li><a href="/source/' . seo_friendly($row2['sub_source']) . '/' . $row['source_id'] . '/' . $row2['sub_source_id'] .  '/">' . $row2['sub_source'] . '</a></li>';
					}
				
					$source_output .= "</ul>";
				}

				$source_output .= "</li>";
			}
		}
	}

	// lookup inspirations
	
	$result = inspiration_search(0, 1);
	
	if($result){
	
		while($row = mysql_fetch_array($result)){
			
			$inspiration_output2 .= '<li><a href="/inspiration/' . seo_friendly($row['inspiration']) . '/' . $row['inspiration_id'] . '/">' . $row['inspiration'] . '</a>';
			
			$sub_result = sub_inspiration_lookup($row['inspiration_id'],0,1);
			
			if(mysql_num_rows($sub_result)){
				$inspiration_output2 .= "<ul>";
				
				while($row2 = mysql_fetch_array($sub_result)){
					$inspiration_output2 .= '<li><a href="/inspiration/' . seo_friendly($row['inspiration']) . '/' . seo_friendly($row2['sub_inspiration']) . '/' . $row2['sub_inspiration_id'] .  '/">' . $row2['sub_inspiration'] . '</a></li>';
				}
			
				$inspiration_output2 .= "</ul>";	
			
			}
			
			$inspiration_output2 .= "</li>";
		}
	}

	//lookup city guide sub inspirations
	
	$result = sub_inspiration_lookup(21,0,1);
	
	while($row = mysql_fetch_array($result)){
	
		$city_guide_output .= '<li><a href="/inspiration/City+Guides/' . seo_friendly($row['sub_inspiration']) . '/' . $row['sub_inspiration_id'] .  '/">' . $row['sub_inspiration'] . '</a></li>';
	
	}

?>
<SCRIPT TYPE="text/javascript">
<!--
function submitenter(myfield,e)
{
var keycode;
if (window.event) keycode = window.event.keyCode;
else if (e) keycode = e.which;
else return true;

if (keycode == 13)
   {
   myfield.submit();
   return false;
   }
else
   return true;
}
//-->
</SCRIPT>
<header role="banner">
<div id="navigation">
		<nav id="utility">
			<a href="/faq.php" class="help">Help!</a>
		<?if($g_sess->get_var("user")){?>
			<p>Hi, <?=$g_sess->get_var("name")?></p>
			<a href="/profile.php" class="settings">Settings</a>
			<?if($home_link){?>
			<a href="<?=$home_link?>" class="home">Home</a>
			<?}?>
			<a href="/logout.php" class="logout">Logout</a>
		<?} else {?>
			<a href="/registration.php" class="logout">Register</a>
			<a href="/ajax/ajax-login.php" id="login-link" class="logout">Login</a>
		<?}?>
	<a href="http://www.restylesource.com/about.php" class="help">About</a>
				<div class="headsearch" style="float: right; margin-top: -3px; margin-right: 15px; margin-left: 15px;"><form class="search" method="post" action="/search-results.php" onKeyPress="return submitenter(this,event)">
				<input type="text" style="padding:0; font-size: 75%; margin-top:2px;" name="keyword" placeholder="ENTER KEYWORDS">
			</form></div> </div>
		</nav>

<header role="banner">
<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
<script type="text/javascript">stLight.options({publisher: "ur-74203a5e-2c4f-69ff-c700-23f3f42e7f9c", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>
<meta name="viewport" content="width=device-width, maximum-scale=1.0, user-scalable=no" />
		<a href="/home.php"><img src="/assets/images/logo-restylesource.png" id="logo" /></a>
			
		<nav role="navigation">
			<li>
				<a href="#">Home Design</a>
				<ul>
					<?if($g_sess->get_var("inventory_ind") >= 1 || $g_sess->get_var("systemTeam") == "admin") {?>
						<li><a href="/source/listing/manufacturers/">Manufacturers</a></li>
						<?} else {?>
						<?}?>
					<li><a href="http://www.restylesource.com/inspiration/Home-Design/Real-Homes/3/">Featured Homes</a></li>
					<li><a href="http://www.restylesource.com/inspiration/Home-Design/Kitchens/8/">Kitchens</a></li>
					<li><a href="http://www.restylesource.com/inspiration/Home-Design/Bedrooms/6/">Bedrooms</a></li>
					<li><a href="http://www.restylesource.com/inspiration/Home-Design/Bathrooms/7/">Bathrooms</a></li>
					<li><a href="http://www.restylesource.com/inspiration/Home-Design/Dining-Rooms/22/">Dining Rooms</a></li>
					<li><a href="http://www.restylesource.com/inspiration/Home-Design/Living-Spaces/4/">Living Spaces</a></li>
					<li><a href="http://www.restylesource.com/inspiration/Home-Design/Children%E2%80%99s-Spaces/10/">Children's Spaces</a></li>
					<li><a href="http://www.restylesource.com/inspiration/Home-Design/Garden+Outdoor-Living/5/">Garden + Outdoor Living</a></li>
					<li><a href="http://www.restylesource.com/inspiration/Home-Design/Workspaces+Organization/9/">Workspaces + Organization</a></li>
					<li><a href="http://www.restylesource.com/inspiration/Home-Design/Trends/55/">Trends</a></li>
				</ul>
			</li>
						<li>
				<a href="#">Local Love</a>
				<ul>
					<li><a href="http://www.restylesource.com/source/listing/partners/">Shops</a>
						<ul>
						<li><a href="http://www.restylesource.com/source/listing/partners/">All Sources</a></li>
						<li><a href="/ajax/ajax-location.php?r=sources" class="change-location">Sources By State</a></li>
						<li><a href="http://www.restylesource.com/source/Antiques/51/13/">Antiques</a></li>
						<li><a href="http://www.restylesource.com/source/Bedding+Linens/51/9/">Bedding</a></li>
						<li><a href="http://www.restylesource.com/source/Florists/51/45/">Florists</a></li>
						<li><a href="http://www.restylesource.com/source/Home-Accessories/51/4/">Home Accessories</a></li>
						<li><a href="http://www.restylesource.com/source/Home-Furnishings/51/6/">Home Furnishings</a></li>
						<li><a href="http://www.restylesource.com/source/Fashion+Beauty/52/">Fashion + Beauty</a></li>
						<li><a href="http://www.restylesource.com/source/Garden+Outdoor/51/46/">Garden + Outdoor</a></li>
						<li><a href="http://www.restylesource.com/source/Restaurants+Gourmet/53/">Kitchen + Gourmet</a></li>
						<li><a href="http://www.restylesource.com/source/Rugs/51/40/">Rugs</a></li>
						<li><a href="http://www.restylesource.com/source/Lighting/51/57/">Lighting</a></li>
						<li><a href="http://www.restylesource.com/source/Stationery+Paper-Goods/51/10/">Stationery + Paper</a></li>
						</ul>
						</li>
					<li><a href="http://www.restylesource.com/source/Designers/55/">Design Sources</a>
						<ul>
						<li><a href="http://www.restylesource.com/source/Architects/55/31/">Interior Designers</a></li>
						<li><a href="http://www.restylesource.com/source/Architects/55/32/">Architects</a></li>
						<li><a href="http://www.restylesource.com/source/Building+Construction/54/23/">Building + Remodeling</a></li>
						<li><a href="http://www.restylesource.com/source/Cabinetry+Hardware/54/27/">Cabinetry</a></li>
						<li><a href="http://www.restylesource.com/source/Floors+Tile/54/22/">Floors + Tile</a></li>
						<li><a href="http://www.restylesource.com/source/Showrooms/56/">Showrooms</a></li>
						<li><a href="http://www.restylesource.com/source/Wall-Coverings+Paint/54/24/">Wallcovering + Paint</a></li>
						</ul></li>
					<li><a href="http://www.restylesource.com/inspiration/People+Places/28/">People + Places</a>
						<ul>
							<li><a href="http://www.restylesource.com/inspiration/City-Guides/21/">City Guides</a></li>
							<li><a href="http://www.restylesource.com/inspiration/People+Places/Day-Trips/74/">Day Trips</a></li>
							<li><a href="http://www.restylesource.com/inspiration/People+Places/Designers/73/">Designer Spotlight</a></li>
							<li><a href="http://www.restylesource.com/inspiration/People+Places/Restaurants/71/">Restaurant Feature</a></li>
							<li><a href="http://www.restylesource.com/inspiration/People+Places/Shops/70/">Shop Talk</a></li>
							<li><a href="http://www.restylesource.com/inspiration/People+Places/Style-Scouts/72/">Style Scouts</a></li>
						</ul></li>
				</ul></li>
			</li>
			<li>
				<a href="#">Lifestyle</a>
				<ul>
					<li><a href="http://www.restylesource.com/inspiration/Lifestyle/Recipes/61/">Food + Recipes</a></li>
					<li><a href="http://www.restylesource.com/inspiration/Lifestyle/DIY/67/">DIY</a></li>
					<li><a href="http://www.restylesource.com/inspiration/Lifestyle/Beauty/63/">Fashion + Beauty</a></li>
					<li><a href="http://www.restylesource.com/inspiration/Lifestyle/Parties+Entertaining/60/">Parties + Entertaining</a></li>
					<li><a href="http://www.restylesource.com/inspiration/Lifestyle/REstyle-REwards/69/">REstyle Rewards</a></li>
				</ul>
			</li>
						<li>
				<a href="http://www.restylesource.com/inspiration/Lifestyle/Parties+Entertaining/Ali-Landrys-Baby-Celebration/405/">Celebs <span style="color:#acbc28;">&hearts;</span> Restyle</a>
				<!--<ul>
					<li><a href="">Entertaining</a></li>
					<li><a href="">Interior Design Makeovers</a></li> 
				</ul> !-->
			</li>
				<li <?if($section=="city guides") echo("class=\"active\"");?>>
				<a href="http://www.restylesource.com/source/REstyle-Contributors/58/">Contributors</a>
		<ul>
				<li><a href="http://www.restylesource.com/source/Photographers/57/">Photographers</a></li>
		 		<li><a href="http://www.restylesource.com/source/Bloggers/58/39/">Bloggers</a></li>
				<li><a href="http://www.restylesource.com/source/Style-Scout/58/37/">Style Scout</a></li>
			</li></ul>
<!--			<li <?if($section=="style file") echo("class=\"active\"");?>>
				<a href="<?=$sf_link?>">Style File</a> 
			</li>
			<li <?if($section=="happenings") echo("class=\"active\"");?>>
				<a href="/happenings.php">Happenings</a>
			</li> -->
		<li id="search" <?if($section=="search") echo("class=\"active\"");?>>
				<a href="#">Search</a>
				<ul>
				<li>
				<form class="dropsearch" method="post" action="/search-results.php" onKeyPress="return submitenter(this,event)">
				<input type="text" name="keyword" placeholder="ENTER KEYWORDS" />
				</form>
				</li>
				</ul>
			</li>
		</div>
		</nav>
	</header></header> <!-- // role=[banner] -->

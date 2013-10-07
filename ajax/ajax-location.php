<?
	
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';	
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/logincheck.php';

	//if(!$g_sess->get_var("user")){
		//my_redirect('/ajax/ajax-login.php');
	//}

	$state_result = state_search(0);
	$state_options = build_generic_dropdown($state_result, $location_state, 0);
	
	if($g_sess->get_var("name")){
		$greeting = "Hi, " . $g_sess->get_var("name");
	} else {
		$greeting = "Hi";
	}
	
?>
<h1><?=$greeting?></h1>
<h2>Where would you like to look?</h2>


<style type="text/css">
#state_map {
	position:relative;
	text-align:center;
	margin:0 auto;
	z-index:1;
}
#mapimg {
	width:650px;
	height:402px;
	margin:0;
	padding:0;
	float:left;
}
.tag {
	background:#60605c;
	color:#fff;
	font-family: 'DIN',sans-serif;
	font-size:.875em;
	font-weight:normal;
	text-transform:uppercase;
	text-align: center;
	padding:4px 12px 2px;
	white-space:nowrap;
}
.tag:before {
  content: "";
  display: block;
  position: absolute;
  bottom: -12px;
  left: 50%;
  margin-left:-6px;
  border: 6px solid transparent;
  border-top-color: #60605c;
}
#select_alabama {
	visibility:hidden;
	position: absolute;
	left: 380px;
	top: 227px;
}
#select_arizona {
	visibility:hidden;
	position: absolute;
	left: 90px;
	top: 195px;
}
#select_arkansas {
	visibility:hidden;
	position: absolute;
	left: 310px;
	top: 210px;
}
#select_california {
	visibility:hidden;
	position: absolute;
	left: 0px;
	top: 110px;
}
#select_colorado {
	visibility:hidden;
	position: absolute;
	left: 165px;
	top: 140px;
}
#select_connecticut {
	visibility:hidden;
	position: absolute;
	left: 505px;
	top: 95px;
}
#select_delaware {
	visibility:hidden;
	position: absolute;
	left: 490px;
	top: 138px;
}
#select_florida {
	visibility:hidden;
	position: absolute;
	left: 440px;
	top: 285px;
}
#select_georgia {
	visibility:hidden;
	position: absolute;
	left: 415px;
	top: 225px;
}
#select_idaho {
	visibility:hidden;
	position: absolute;
	left: 85px;
	top: 28px;
}
#select_illinois {
	visibility:hidden;
	position: absolute;
	left: 350px;
	top: 120px;
}
#select_indiana {
	visibility:hidden;
	position: absolute;
	left: 380px;
	top: 125px;
}
#select_iowa {
	visibility:hidden;
	position: absolute;
	left: 315px;
	top: 110px;
}
#select_kansas {
	visibility:hidden;
	position: absolute;
	left: 245px;
	top: 160px;
}
#select_kentucky {
	visibility:hidden;
	position: absolute;
	left: 395px;
	top: 165px;
}
#select_louisiana {
	visibility:hidden;
	position: absolute;
	left: 310px;
	top: 260px;
}
#select_maine {
	visibility:hidden;
	position: absolute;
	left: 550px;
	top: 10px;
}
#select_maryland {
	visibility:hidden;
	position: absolute;
	left: 480px;
	top: 140px;
}
#select_massachusetts {
	visibility:hidden;
	position: absolute;
	left: 503px;
	top: 83px;
}
#select_michigan {
	visibility:hidden;
	position: absolute;
	left: 380px;
	top: 55px;
}
#select_minnesota {
	visibility:hidden;
	position: absolute;
	left: 285px;
	top: 35px;
}
#select_mississippi {
	visibility:hidden;
	position: absolute;
	left: 340px;
	top: 230px;
}
#select_missouri {
	visibility:hidden;
	position: absolute;
	left: 310px;
	top: 150px;
}
#select_montana {
	visibility:hidden;
	position: absolute;
	left: 145px;
	top: 25px;
}
#select_nebraska {
	visibility:hidden;
	position: absolute;
	left: 224px;
	top: 120px;
}
#select_nevada {
	visibility:hidden;
	position: absolute;
	left: 50px;
	top: 110px;
}
#select_new_hampshire {
	visibility:hidden;
	position: absolute;
	left: 498px;
	top: 52px;
}
#select_new_jersey {
	visibility:hidden;
	position: absolute;
	left: 495px;
	top: 118px;
}
#select_new_mexico {
	visibility:hidden;
	position: absolute;
	left: 140px;
	top: 200px;
}
#select_new_york {
	visibility:hidden;
	position: absolute;
	left: 480px;
	top: 68px;
}
#select_north_carolina {
	visibility:hidden;
	position: absolute;
	left: 440px;
	top: 190px;
}
#select_north_dakota {
	visibility:hidden;
	position: absolute;
	left: 200px;
	top: 25px;
}
#select_ohio {
	visibility:hidden;
	position: absolute;
	left: 430px;
	top: 125px;
}
#select_oklahoma {
	visibility:hidden;
	position: absolute;
	left: 245px;
	top: 205px;
}
#select_oregon {
	visibility:hidden;
	position: absolute;
	left: 35px;
	top: 40px;
}
#select_pennsylvania {
	visibility:hidden;
	position: absolute;
	left: 450px;
	top: 110px;
}
#select_rhode_island {
	visibility:hidden;
	position: absolute;
	left: 513px;
	top: 90px;
}
#south_carolina {
	visibility:hidden;
	position: absolute;
	width: 303px;
}
#select_south_carolina {
	visibility:hidden;
	position: absolute;
	left: 420px;
	top: 215px;
}
#select_south_dakota {
	visibility:hidden;
	position: absolute;
	left: 200px;
	top: 70px;
}
#select_tennessee {
	visibility:hidden;
	position: absolute;
	left: 380px;
	top: 200px;
}
#select_texas {
	visibility:hidden;
	position: absolute;
	left: 220px;
	top: 220px;
}
#select_utah {
	visibility:hidden;
	position: absolute;
	left: 115px;
	top: 125px;
}
#select_vermont {
	visibility:hidden;
	position: absolute;
	left: 507px;
	top: 55px;
}
#select_virginia {
	visibility:hidden;
	position: absolute;
	left: 470px;
	top: 160px;
}
#select_washington {
	visibility:hidden;
	position: absolute;
	left: 24px;
	top: 0px;
}
#select_west_virginia {
	visibility:hidden;
	position: absolute;
	left: 430px;
	top: 145px;
}
#select_wisconsin {
	visibility:hidden;
	position: absolute;
	left: 330px;
	top: 65px;
}
#select_wyoming {
	visibility:hidden;
	position: absolute;
	left: 155px;
	top: 85px;
}
</style>

<script type="text/javascript">
function MM_showHideLayers() { //v9.0
  var i,p,v,obj,args=MM_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3) 
  with (document) if (getElementById && ((obj=getElementById(args[i]))!=null)) { v=args[i+2];
    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v=='hide')?'hidden':v; }
    obj.visibility=v; }
}
</script>

<!-- START OF MAP//-->

<div id="state_map">
<div id="select_alabama" class="tag"> Alabama </div>
<div id="select_arizona" class="tag"> Arizona </div>
<div id="select_arkansas" class="tag"> Arkansas </div>
<div id="select_california" class="tag"> California </div>
<div id="select_connecticut" class="tag"> Connecticut</div>
<div id="select_colorado" class="tag"> Colorado</div>
<div id="select_delaware" class="tag"> Delaware</div>
<div id="select_florida" class="tag"> Florida</div>
<div id="select_georgia" class="tag"> Georgia</div>
<div id="select_idaho" class="tag"> Idaho</div>
<div id="select_illinois" class="tag"> Illinois</div>
<div id="select_iowa" class="tag"> Iowa</div>
<div id="select_indiana" class="tag"> Indiana</div>
<div id="select_kansas" class="tag"> Kansas</div>
<div id="select_kentucky" class="tag"> Kentucky</div>
<div id="select_louisiana" class="tag"> Louisiana</div>
<div id="select_maine" class="tag"> Maine </div>
<div id="select_maryland" class="tag"> Maryland </div>
<div id="select_massachusetts" class="tag"> Massachusetts </div>
<div id="select_michigan" class="tag"> Michigan </div>
<div id="select_minnesota" class="tag"> Minnesota </div>
<div id="select_mississippi" class="tag"> Mississippi </div>
<div id="select_missouri" class="tag"> Missouri </div>
<div id="select_montana" class="tag"> Montana </div>
<div id="select_nebraska" class="tag"> Nebraska </div>
<div id="select_nevada" class="tag"> Nevada </div>
<div id="select_new_hampshire" class="tag"> New Hampshire </div>
<div id="select_new_jersey" class="tag"> New Jersey </div>
<div id="select_new_mexico" class="tag"> New Mexico </div>
<div id="select_new_york" class="tag"> New York </div>
<div id="select_north_carolina" class="tag"> North Carolina </div>
<div id="select_north_dakota" class="tag"> North Dakota </div>
<div id="select_ohio" class="tag"> Ohio</div>
<div id="select_oklahoma" class="tag"> Oklahoma</div>
<div id="select_oregon" class="tag"> Oregon </div>
<div id="select_pennsylvania" class="tag"> Pennsylvania </div>
<div id="select_rhode_island" class="tag"> Rhode Island </div>
<div id="select_south_carolina" class="tag"> South Carolina </div>
<div id="select_south_dakota" class="tag"> South Dakota </div>
<div id="select_tennessee" class="tag"> Tennessee </div>
<div id="select_texas" class="tag"> Texas </div>
<div id="select_utah" class="tag"> Utah </div>
<div id="select_vermont" class="tag"> Vermont </div>
<div id="select_virginia" class="tag"> Virginia </div>
<div id="select_washington" class="tag"> Washington </div>
<div id="select_west_virginia" class="tag"> West Virginia </div>
<div id="select_wisconsin" class="tag"> Wisconsin </div>
<div id="select_wyoming" class="tag"> Wyoming </div>
       
<div id="mapimg">
<img style="width:650px; height:402px; border:0px; margin:0; padding:0; float:left;" id="usa_image" src="/assets/images/usa_map.png" usemap="#usa">
</div>

<div style="clear:both;"></div>

</div>

<div class="dialog-content">
	<form method="post" <? if($_REQUEST['r']=="sources") echo("action=\"/source/listing/partners/\"");?> class="location">
		<select id="location_state" name="search_state" class="uniform">
			<option value="">ALL</option>
			<?=$state_options?> 
		</select>
	</form>
</div>

<map id="usa_image_map" name="usa">
<!-- ALABAMA -->
<area href="#" data-state="AL" data-full="Alabama" shape="poly" coords="409,319,408,309,406,297,406,287,407,267,407,256,407,252,413,251,430,250,437,249,437,251,437,252,438,254,440,259,441,266,442,270,443,273,444,278,446,282,447,283,448,286,449,286,449,288,448,292,448,293,448,294,449,297,449,301,449,302,449,303,450,303,450,306,446,306,441,306,424,308,418,309,418,311,419,312,421,313,422,319,416,320,415,320,416,319,416,319,414,314,413,314,413,317,412,319,411,319,409,319" onmouseover="MM_showHideLayers('select_alabama','','show')" onmouseout="MM_showHideLayers('select_alabama','','hide')"/>
<!-- ARIZONA -->
<area href="#" data-state="AZ" data-full="Arizona" shape="poly" coords="90,267,88,268,88,269,88,270,101,276,108,282,118,287,129,294,137,295,155,298,156,289,159,272,163,237,166,216,149,214,132,210,110,207,107,218,107,218,107,221,105,221,104,218,102,218,102,218,101,218,100,218,99,218,99,224,99,224,98,233,98,235,97,237,99,240,99,243,100,244,101,245,101,246,99,246,98,248,97,249,95,251,95,255,93,256,91,257,91,261,91,262,91,263,94,263,94,265,92,266,90,267" onmouseover="MM_showHideLayers('select_arizona','','show')" onmouseout="MM_showHideLayers('select_arizona','','hide')"/>
<!-- ARKANSAS -->
<area href="#" data-state="AR" data-full="Arkansas" shape="poly" coords="387,239,384,240,380,239,381,237,383,236,383,234,382,232,375,232,361,233,346,233,330,234,331,238,331,244,332,251,332,275,334,277,336,275,338,276,338,283,353,283,365,283,373,283,373,282,373,279,372,277,373,275,372,274,373,274,373,270,376,267,375,266,377,263,378,262,378,261,378,259,380,256,382,255,382,254,384,253,384,249,383,247,385,246,386,244,386,241,387,239" onmouseover="MM_showHideLayers('select_arkansas','','show')" onmouseout="MM_showHideLayers('select_arkansas','','hide')"/>
<!-- CALIFORNIA -->
<area href="#" data-state="CA" data-full="California" shape="poly" coords="89,267,92,266,94,265,94,263,91,263,91,262,91,261,91,257,93,256,95,255,95,251,97,249,98,248,99,246,101,246,101,245,100,244,99,243,99,240,97,237,98,235,96,232,86,218,73,199,59,176,51,164,51,160,56,143,61,122,52,120,43,117,35,115,31,113,23,111,18,110,18,113,17,118,14,125,12,127,12,128,10,129,10,132,9,134,11,136,12,139,13,141,13,145,12,148,11,151,10,153,12,156,13,159,15,163,15,164,15,167,15,167,15,169,19,172,18,175,18,176,18,178,18,183,19,185,21,187,23,187,23,189,22,191,21,192,20,192,20,195,20,197,22,200,23,203,23,206,24,208,27,212,28,213,29,216,29,216,29,218,29,218,28,224,27,225,29,227,32,227,34,228,37,229,39,229,41,232,42,235,43,237,46,237,49,238,51,240,51,242,50,242,50,243,51,243,53,243,56,246,59,249,59,251,60,254,60,255,60,262,61,263,67,264,80,265,89,267" onmouseover="MM_showHideLayers('select_california','','show')" onmouseout="MM_showHideLayers('select_california','','hide')"/>
<!-- CONNECTICUT -->
<area href="#" data-state="CT" data-full="Connecticut" shape="poly" coords="572,131,571,128,571,125,571,121,567,122,553,125,553,127,554,132,554,137,553,139,554,140,557,138,560,136,561,135,562,135,563,135,567,134,572,131" onmouseover="MM_showHideLayers('select_connecticut','','show')" onmouseout="MM_showHideLayers('select_connecticut','','hide')"/>
<!-- COLORADO -->
<area href="#" data-state="CO" data-full="Colorado" shape="poly" coords="246,224,248,181,249,167,228,164,211,163,187,161,173,159,172,173,170,189,167,207,167,214,166,217,189,218,213,222,235,224,238,224,246,224" onmouseover="MM_showHideLayers('select_colorado','','show')" onmouseout="MM_showHideLayers('select_colorado','','hide')"/>
<!-- DELAWARE -->
<area href="#" data-state="DE" data-full="Delaware" shape="poly" coords="538,164,539,163,539,162,538,162,536,163,535,164,536,167,538,170,539,177,541,181,543,181,547,180,546,175,545,175,543,173,542,171,541,168,539,167,538,165,538,164" onmouseover="MM_showHideLayers('select_delaware','','show')" onmouseout="MM_showHideLayers('select_delaware','','hide')"/>
<!-- FLORIDA -->
<area href="#" data-state="FL" data-full="Florida" shape="poly" coords="495,304,496,310,498,316,502,321,504,326,507,330,510,332,511,334,510,335,510,336,511,340,514,343,515,346,517,349,521,355,522,360,522,367,522,369,522,371,520,373,521,373,520,375,520,376,521,378,519,380,516,381,514,381,514,382,512,383,511,382,510,381,510,380,509,377,507,374,505,373,503,373,502,373,500,370,499,367,497,365,497,365,496,366,495,366,493,362,491,359,489,357,488,354,485,352,487,350,488,347,488,346,486,346,484,346,485,346,487,347,486,349,485,350,484,348,483,344,483,342,484,339,484,333,481,330,481,329,478,328,476,327,475,326,473,324,472,322,470,321,469,319,466,319,464,317,462,317,460,318,460,319,460,320,460,320,458,320,455,322,453,324,450,324,449,325,449,323,447,321,445,321,444,320,439,318,434,316,431,317,427,317,423,319,421,319,421,313,419,312,418,311,418,309,424,308,441,306,446,306,450,306,451,308,452,310,458,310,465,309,478,308,482,308,486,308,486,310,488,311,488,308,487,304,487,303,491,304,495,304" onmouseover="MM_showHideLayers('select_florida','','show')" onmouseout="MM_showHideLayers('select_florida','','hide')"/>
<!-- GEORGIA -->
<area href="#" data-state="GA" data-full="Georgia" shape="poly" coords="451,247,449,248,442,248,437,249,437,251,437,252,438,254,440,259,441,266,442,270,443,273,444,278,446,282,447,283,448,286,449,286,449,288,448,292,448,293,448,294,449,297,449,301,449,302,449,303,450,303,450,306,451,308,452,310,458,310,465,309,478,308,482,308,486,308,486,310,488,311,488,308,487,304,487,303,491,304,495,304,494,300,495,293,497,291,496,289,498,284,497,283,497,283,495,283,495,282,494,280,492,278,491,278,489,274,488,270,485,270,484,268,483,266,481,265,480,265,478,262,477,261,473,259,473,259,472,256,471,256,469,253,468,253,465,251,463,250,463,249,464,248,465,247,465,246,464,246,460,246,456,247,451,247" onmouseover="MM_showHideLayers('select_georgia','','show')" onmouseout="MM_showHideLayers('select_georgia','','hide')"/>
<!-- IDAHO -->
<area href="#" data-state="ID" data-full="Idaho" shape="poly" coords="92,129,95,117,98,106,99,103,100,99,99,98,98,98,98,97,98,97,98,94,101,91,102,90,103,89,103,88,104,87,107,83,109,80,109,78,107,76,106,73,107,67,108,56,112,42,114,34,115,32,124,33,121,47,124,51,122,55,124,58,125,59,128,66,130,68,131,69,133,70,133,70,128,82,128,84,130,86,131,86,134,84,135,83,135,84,135,88,137,96,139,97,140,98,141,99,140,101,141,104,142,105,143,102,145,102,147,104,148,103,151,103,153,105,155,104,156,102,158,102,159,102,159,105,161,106,158,122,155,142,152,141,146,140,140,139,132,137,124,136,118,135,112,134,106,132,92,129" onmouseover="MM_showHideLayers('select_idaho','','show')" onmouseout="MM_showHideLayers('select_idaho','','hide')"/>
<!-- ILLINOIS -->
<area href="#" data-state="IL" data-full="Illinois" shape="poly" coords="404,210,404,208,405,205,406,203,408,200,409,198,409,194,408,192,408,190,408,187,408,182,407,172,406,162,405,153,405,153,404,152,404,149,403,148,402,146,401,143,395,144,377,145,371,144,371,146,373,146,373,147,374,148,376,151,376,153,376,154,376,157,375,159,373,160,372,160,368,162,367,163,367,164,367,164,369,165,369,168,367,170,367,170,367,172,367,172,365,173,365,174,365,175,365,176,364,178,364,181,365,186,370,190,374,192,373,196,374,197,378,197,380,198,380,200,378,204,378,206,379,209,384,212,386,213,387,216,389,218,389,219,389,222,391,224,393,224,393,222,395,221,396,221,397,221,400,222,401,222,401,221,400,218,400,218,402,217,403,216,404,216,404,215,403,213,404,213,404,210" onmouseover="MM_showHideLayers('select_illinois','','show')" onmouseout="MM_showHideLayers('select_illinois','','hide')"/>
<!-- IOWA -->
<area href="#" data-state="IA" data-full="Iowa" shape="poly" coords="371,145,371,146,373,146,373,147,374,148,376,151,376,153,376,154,376,157,375,159,373,160,372,160,368,162,367,163,367,164,367,164,369,165,369,168,367,170,367,170,367,172,367,172,365,173,365,174,365,175,365,177,362,174,361,172,357,173,349,173,333,174,324,174,319,175,318,175,316,172,316,167,315,164,315,161,313,159,313,155,311,151,311,146,309,145,308,144,310,140,311,137,308,135,308,134,309,132,310,132,318,132,350,132,362,132,365,131,365,134,367,135,367,135,365,137,365,140,367,143,368,143,370,143,371,145" onmouseover="MM_showHideLayers('select_iowa','','show')" onmouseout="MM_showHideLayers('select_iowa','','hide')"/>
<!-- INDIANA -->
<area href="#" data-state="IN" data-full="Indiana" shape="poly" coords="405,210,404,208,405,205,406,203,408,200,409,198,409,194,408,192,408,190,408,187,408,182,407,172,406,162,405,153,408,154,408,155,409,155,411,153,413,153,416,153,430,151,434,151,434,151,435,162,436,170,438,185,438,189,438,190,439,191,440,192,438,194,435,195,433,195,432,199,430,200,428,203,428,205,427,206,424,206,424,205,423,205,422,207,422,209,420,209,419,209,418,208,416,208,415,210,413,210,413,209,411,209,406,209,405,210" onmouseover="MM_showHideLayers('select_indiana','','show')" onmouseout="MM_showHideLayers('select_indiana','','hide')"/>
<!-- KANSAS -->
<area href="#" data-state="KS" data-full="Kansas" shape="poly" coords="330,227,321,227,291,227,262,225,246,224,248,181,263,181,289,183,318,183,321,183,324,185,326,185,327,186,327,188,325,189,325,190,327,192,328,194,330,195,330,203,330,227" onmouseover="MM_showHideLayers('select_kansas','','show')" onmouseout="MM_showHideLayers('select_kansas','','hide')"/>
<!-- KENTUCKY -->
<area href="#" data-state="KY" data-full="Kentucky" shape="poly" coords="473,208,470,210,468,213,465,216,464,218,464,218,461,220,458,222,456,223,422,227,412,228,409,228,406,228,406,230,401,230,396,231,389,231,390,230,392,229,393,228,393,227,394,225,393,224,393,222,395,221,396,221,397,221,400,222,401,222,401,221,400,218,400,218,402,217,403,216,404,216,404,215,403,213,404,213,405,210,406,209,411,209,413,209,413,210,415,210,416,208,418,208,419,209,420,209,422,209,422,207,423,205,424,205,424,206,427,206,428,205,428,203,430,200,432,199,433,195,435,195,438,194,440,192,439,191,438,190,438,189,441,189,443,189,445,190,446,192,450,192,450,194,451,194,454,193,456,193,457,194,459,192,460,191,460,191,461,193,462,194,465,195,465,200,465,200,467,201,468,202,469,205,470,207,473,208" onmouseover="MM_showHideLayers('select_kentucky','','show')" onmouseout="MM_showHideLayers('select_kentucky','','hide')"/>
<!-- LOUISIANA -->
<area href="#" data-state="LA" data-full="Louisiana" shape="poly" coords="395,322,395,321,394,319,391,316,392,311,392,311,390,311,385,311,369,312,368,311,369,305,371,302,375,296,374,294,375,294,376,293,374,292,374,290,373,287,373,283,365,283,353,283,338,283,338,290,339,297,339,299,340,302,341,305,344,308,344,311,345,311,344,316,342,320,343,321,343,323,343,328,341,330,342,332,345,331,350,330,357,333,361,334,364,333,366,334,367,335,368,333,367,332,365,332,362,331,367,330,367,330,370,330,370,332,370,334,374,334,376,335,375,336,374,337,375,338,380,340,383,339,384,338,385,338,386,336,386,337,387,339,386,339,386,340,389,339,390,337,391,337,389,336,389,335,389,334,391,334,392,333,392,333,397,338,398,338,400,338,401,339,403,337,403,336,402,336,400,334,395,333,394,332,395,330,395,330,396,330,395,330,395,330,397,330,398,327,397,326,397,324,396,324,395,326,395,327,392,327,392,326,393,324,395,323,395,322" onmouseover="MM_showHideLayers('select_louisiana','','show')" onmouseout="MM_showHideLayers('select_louisiana','','hide')"/>
<!-- MAINE -->
<area href="#" data-state="ME" data-full="Maine" shape="poly" coords="604,64,606,65,607,68,607,69,606,72,604,72,602,75,599,78,596,77,595,78,594,79,593,79,595,80,594,80,594,83,592,83,592,81,592,80,591,80,590,79,589,79,590,80,590,81,590,82,590,84,590,86,589,87,587,88,587,88,583,91,581,91,581,90,579,93,580,95,579,96,579,99,578,104,576,103,576,101,573,100,573,98,568,83,565,73,567,73,568,73,568,72,568,68,570,65,571,62,570,61,570,57,571,56,571,54,571,53,571,51,571,47,573,42,575,39,576,39,576,39,576,40,577,41,579,42,580,41,580,40,582,38,584,37,584,37,589,39,590,40,595,59,599,59,600,60,600,63,602,65,603,65,603,64,602,64,604,64" onmouseover="MM_showHideLayers('select_maine','','show')" onmouseout="MM_showHideLayers('select_maine','','hide')"/>
<!-- MARYLAND -->
<area href="#" data-state="MD" data-full="Maryland" shape="poly" coords="547,180,543,181,541,181,539,177,538,170,536,167,535,163,530,164,520,167,496,172,497,175,497,178,497,178,498,177,500,175,502,175,503,173,504,172,505,172,506,172,508,171,510,170,511,170,512,170,514,172,515,172,516,173,519,174,519,176,522,177,524,178,525,177,525,178,525,181,525,181,524,183,524,185,524,186,527,187,530,187,532,188,533,188,534,186,533,185,533,183,531,182,530,179,531,175,531,174,530,172,533,170,534,168,534,169,533,170,533,172,533,173,534,173,534,177,533,178,533,181,533,180,534,178,535,180,534,181,534,183,535,185,538,185,539,185,541,189,542,189,542,191,541,194,541,199,541,200,543,200,543,198,543,196,543,191,546,188,547,183,547,180" onmouseover="MM_showHideLayers('select_maryland','','show')" onmouseout="MM_showHideLayers('select_maryland','','hide')"/>
<!-- MASSACHUSETTS -->
<area href="#" data-state="MA" data-full="Massachusetts" shape="poly" coords="560,113,571,110,573,110,574,107,577,107,579,110,577,113,577,114,579,116,579,116,580,116,581,116,584,121,587,121,588,121,590,119,589,117,587,116,586,116,585,116,586,116,587,116,589,116,590,118,590,119,590,121,588,122,585,124,582,126,581,127,581,126,583,126,583,125,582,123,581,124,580,125,580,126,579,126,577,124,575,121,573,121,571,121,567,122,553,125,552,121,552,115,555,114,560,113" onmouseover="MM_showHideLayers('select_massachusetts','','show')" onmouseout="MM_showHideLayers('select_massachusetts','','hide')"/>
<!-- MICHIGAN -->
<area href="#" data-state="MI" data-full="Michigan" shape="poly" coords="457,129,454,124,453,118,451,116,450,115,449,116,446,116,445,120,443,122,442,123,441,122,442,116,443,115,443,113,445,112,445,105,443,104,443,103,442,102,443,101,443,102,443,100,442,99,441,97,440,97,437,97,433,94,432,94,431,94,430,94,428,93,427,94,424,96,424,98,425,98,427,98,427,99,425,99,424,99,423,100,423,102,423,103,423,107,421,107,420,107,420,105,422,104,422,102,422,102,420,102,419,105,417,106,416,107,416,107,416,107,416,110,414,110,414,110,415,113,414,116,413,118,413,122,413,123,413,124,413,125,413,126,415,131,417,135,418,137,418,141,417,145,415,148,415,150,413,152,413,153,416,153,430,151,434,151,434,151,440,151,446,150,450,149,449,148,449,148,450,145,451,144,451,141,452,140,453,140,453,137,454,135,455,135,455,136,456,136,457,135,457,129" onmouseover="MM_showHideLayers('select_michigan','','show')" onmouseout="MM_showHideLayers('select_michigan','','hide')"/>
<area href="#" data-state="MI" data-full="Michigan" shape="poly" coords="370,86,372,86,374,85,376,83,376,83,376,83,380,82,382,80,385,79,385,79,386,77,387,76,388,75,389,73,392,72,395,71,396,72,396,72,394,73,393,75,391,76,391,78,389,79,389,81,389,81,390,80,392,79,394,80,395,80,397,80,397,81,399,83,401,85,403,85,404,84,405,86,406,86,407,85,408,85,409,84,412,82,413,81,418,80,422,80,423,79,424,79,424,83,424,83,426,83,427,83,432,82,432,81,432,81,432,86,435,88,436,88,437,89,436,89,435,89,432,88,432,89,430,89,428,90,427,90,423,89,419,89,419,91,413,91,413,92,411,94,411,95,411,95,409,94,406,96,405,96,405,94,404,94,404,97,403,99,400,105,399,105,398,104,397,97,395,97,395,94,386,93,384,92,378,90,373,89,370,86" onmouseover="MM_showHideLayers('select_michigan','','show')" onmouseout="MM_showHideLayers('select_michigan','','hide')"/>
<!-- MINNESOTA -->
<area href="#" data-state="MN" data-full="Minnesota" shape="poly" coords="309,97,308,91,308,87,306,78,305,72,305,70,303,66,303,59,304,57,302,53,322,53,322,48,323,48,324,48,326,49,327,52,327,56,329,57,331,57,332,59,336,59,336,60,339,60,339,59,340,59,341,58,342,59,344,59,347,60,350,61,351,61,352,61,353,61,354,63,355,64,356,64,357,64,357,65,358,66,360,66,361,65,363,63,365,62,365,64,366,64,367,64,367,64,373,64,374,66,375,66,375,65,378,65,377,67,375,68,368,70,366,72,364,73,362,76,360,78,359,79,357,82,356,82,354,84,354,85,352,86,351,88,351,94,351,95,348,97,346,101,346,101,348,102,348,105,348,107,348,109,348,114,349,116,351,116,353,118,355,118,358,122,362,125,364,126,365,132,362,132,350,132,318,132,310,132,311,127,311,107,310,106,307,104,306,102,306,101,308,100,309,99,309,97" onmouseover="MM_showHideLayers('select_minnesota','','show')" onmouseout="MM_showHideLayers('select_minnesota','','hide')"/>
<!-- MISSISSIPPI -->
<area href="#" data-state="MS" data-full="Mississippi" shape="poly" coords="409,319,408,320,405,320,404,319,403,319,398,320,397,320,395,322,395,323,395,321,394,319,391,316,392,311,392,311,390,311,385,311,369,312,368,311,369,305,371,302,375,296,374,294,375,294,376,293,374,292,374,290,373,287,373,283,373,282,373,279,372,277,373,275,372,274,373,274,373,270,376,267,375,266,377,263,378,262,378,261,378,259,380,256,382,255,382,254,387,254,404,252,407,252,407,256,407,267,406,287,406,297,408,309,409,319" onmouseover="MM_showHideLayers('select_mississippi','','show')" onmouseout="MM_showHideLayers('select_mississippi','','hide')"/>
<!-- MISSOURI -->
<area href="#" data-state="MO" data-full="Missouri" shape="poly" coords="365,177,362,174,361,172,357,173,349,173,333,174,324,174,319,175,318,175,319,176,319,178,320,181,321,183,324,185,326,185,327,186,327,188,325,189,325,190,327,192,328,194,330,195,330,203,330,227,330,229,330,234,346,233,361,233,375,232,382,232,383,234,383,236,381,237,380,239,384,240,387,239,389,235,389,231,391,230,392,229,393,228,393,227,394,225,393,224,391,224,389,222,389,219,389,218,387,216,386,213,384,212,379,209,378,206,378,204,380,200,380,198,378,197,374,197,373,196,374,192,370,190,365,186,364,181,364,178,365,177" onmouseover="MM_showHideLayers('select_missouri','','show')" onmouseout="MM_showHideLayers('select_missouri','','hide')"/>
<!-- MONTANA -->
<area href="#" data-state="MT" data-full="Montana" shape="poly" coords="234,94,235,86,236,70,237,60,237,51,217,48,198,46,178,43,157,40,145,37,124,33,121,47,124,51,122,55,124,58,125,59,128,66,130,68,131,69,133,70,133,70,128,82,128,84,130,86,131,86,134,84,135,83,135,84,135,88,137,96,139,97,140,98,141,99,140,101,141,104,142,105,143,102,145,102,147,104,148,103,151,103,153,105,155,104,156,102,158,102,159,102,159,105,161,106,162,98,175,100,193,103,204,105,225,107,232,107,234,97,234,94" onmouseover="MM_showHideLayers('select_montana','','show')" onmouseout="MM_showHideLayers('select_montana','','hide')"/>
<!-- NEBRASKA -->
<area href="#" data-state="NE" data-full="Nebraska" shape="poly" coords="318,175,319,176,319,178,320,181,321,183,318,183,289,183,263,181,248,181,249,167,228,164,230,136,240,137,254,137,265,138,281,139,288,139,289,140,293,143,293,143,296,142,299,142,301,142,302,143,305,143,307,144,308,145,308,147,309,147,311,146,311,151,313,155,313,159,315,161,315,164,316,167,316,172,318,175" onmouseover="MM_showHideLayers('select_nebraska','','show')" onmouseout="MM_showHideLayers('select_nebraska','','hide')"/>
<!-- NEVADA -->
<area href="#" data-state="NV" data-full="Nevada" shape="poly" coords="92,129,106,132,112,134,118,135,124,136,123,140,120,151,118,164,116,171,116,180,113,190,111,200,110,208,107,218,107,218,107,221,105,221,104,218,102,218,102,218,101,218,100,218,99,218,99,224,99,224,98,233,98,235,96,232,86,218,73,199,59,176,51,164,51,160,56,143,61,122,83,127,92,129" onmouseover="MM_showHideLayers('select_nevada','','show')" onmouseout="MM_showHideLayers('select_nevada','','hide')"/>
<!-- NEW HAMPSHIRE -->
<area href="#" data-state="NH" data-full="New Hampshire" shape="poly" coords="577,107,577,106,578,104,576,103,576,101,573,100,573,98,568,83,565,73,565,73,564,75,563,74,562,73,562,75,562,79,562,82,562,84,562,87,561,89,559,90,559,91,560,92,560,98,559,104,559,107,560,107,560,110,560,112,560,113,571,110,573,110,574,107,577,107" onmouseover="MM_showHideLayers('select_new_hampshire','','show')" onmouseout="MM_showHideLayers('select_new_hampshire','','hide')"/>
<!-- NEW JERSEY -->
<area href="#" data-state="NJ" data-full="New Jersey" shape="poly" coords="542,137,541,140,541,141,539,143,539,144,540,145,540,147,538,148,539,149,539,150,541,151,542,152,543,153,545,154,545,155,543,157,543,159,541,161,540,162,539,162,539,163,538,164,539,166,541,167,544,170,547,170,547,171,546,172,546,172,547,172,549,172,549,168,552,165,553,162,554,158,553,157,553,151,552,148,552,149,550,149,549,149,550,148,552,147,552,146,552,144,552,143,552,141,550,140,546,140,544,139,542,137" onmouseover="MM_showHideLayers('select_new_jersey','','show')" onmouseout="MM_showHideLayers('select_new_jersey','','hide')"/>
<!-- NEW MEXICO -->
<area href="#" data-state="NM" data-full="New Mexico" shape="poly" coords="186,295,185,292,190,293,209,294,228,295,229,280,232,244,234,231,235,231,235,224,213,222,189,218,166,217,163,237,159,272,156,289,155,298,165,299,166,293,177,294,186,295" onmouseover="MM_showHideLayers('select_new_mexico','','show')" onmouseout="MM_showHideLayers('select_new_mexico','','hide')"/>
<!-- NEW YORK -->
<area href="#" data-state="NY" data-full="New York" shape="poly" coords="542,137,541,137,540,137,538,135,536,132,534,132,533,130,521,133,492,138,487,140,487,135,488,134,489,133,489,132,490,132,492,130,492,129,494,127,495,126,495,126,494,124,492,124,491,120,493,118,496,118,498,116,500,116,505,116,506,117,507,117,508,116,510,116,514,116,515,115,516,113,516,111,518,111,519,110,519,108,519,107,519,107,519,105,519,104,518,104,516,104,516,103,516,101,520,98,521,97,522,95,524,92,525,89,527,88,528,87,530,86,534,86,535,86,539,84,543,83,544,86,546,90,546,94,546,97,547,99,548,101,547,102,549,104,550,104,552,111,552,115,552,121,552,125,553,127,554,132,554,137,553,139,554,140,554,141,553,143,553,143,554,143,554,143,556,140,557,140,558,140,560,140,565,138,567,136,568,135,571,136,568,139,565,140,561,144,560,144,555,145,553,146,552,146,552,144,552,143,552,141,550,140,546,140,544,139,542,137" onmouseover="MM_showHideLayers('select_new_york','','show')" onmouseout="MM_showHideLayers('select_new_york','','hide')"/>
<!-- NORTH CAROLINA -->
<area href="#" data-state="NC" data-full="North Carolina" shape="poly" coords="544,209,546,211,548,216,549,218,550,218,549,218,549,219,549,222,547,223,546,224,546,227,543,228,542,227,541,227,540,227,540,227,540,228,541,228,542,228,541,232,543,232,543,234,545,232,546,232,544,235,543,237,542,237,541,237,539,237,535,239,532,243,530,246,528,250,527,251,525,252,521,254,515,248,506,243,505,243,496,243,493,244,492,241,490,240,479,240,475,241,469,244,465,246,464,246,460,246,456,247,451,247,451,245,452,243,454,243,455,240,458,238,460,237,462,235,466,234,466,232,469,229,469,229,472,228,474,228,476,228,476,226,478,224,478,222,478,219,481,220,487,219,497,218,508,216,522,214,534,211,541,209,544,209" onmouseover="MM_showHideLayers('select_north_carolina','','show')" onmouseout="MM_showHideLayers('select_north_carolina','','hide')"/>
<!-- NORTH DAKOTA -->
<area href="#" data-state="ND" data-full="North Dakota" shape="poly" coords="309,97,308,91,308,87,306,78,305,72,305,70,303,66,303,59,304,57,302,53,283,53,271,52,254,51,237,51,237,60,236,70,235,86,234,94,271,97,309,97" onmouseover="MM_showHideLayers('select_north_dakota','','show')" onmouseout="MM_showHideLayers('select_north_dakota','','hide')"/>
<!-- OHIO -->
<area href="#" data-state="OH" data-full="Ohio" shape="poly" coords="479,141,475,144,472,145,470,147,468,150,465,151,463,151,460,153,459,153,456,151,452,151,451,150,449,149,446,150,440,151,434,151,435,162,436,170,438,185,438,189,441,189,443,189,445,190,446,192,450,192,450,194,451,194,454,193,456,193,457,194,459,192,460,191,460,191,461,193,462,194,465,196,466,195,468,195,468,193,468,192,469,189,469,186,470,186,470,187,470,188,472,187,473,186,472,185,472,183,472,182,474,181,475,180,476,180,478,179,479,176,481,174,481,170,481,167,481,164,481,162,481,161,482,161,481,153,479,141" onmouseover="MM_showHideLayers('select_ohio','','show')" onmouseout="MM_showHideLayers('select_ohio','','hide')"/>
<!-- OKLAHOMA -->
<area href="#" data-state="OK" data-full="Oklahoma" shape="poly" coords="246,224,238,224,235,224,235,224,235,231,248,232,269,233,267,248,267,260,267,261,270,264,272,265,273,265,273,263,274,265,275,265,275,263,277,265,276,266,279,267,281,267,283,267,285,269,286,267,289,268,291,270,292,270,292,272,293,273,294,271,295,271,297,271,297,273,301,274,302,274,302,271,303,271,304,273,307,273,310,274,311,274,313,274,313,272,316,272,317,273,319,271,320,271,320,273,323,273,324,271,325,271,327,273,329,274,330,274,332,275,332,251,331,244,331,238,330,234,330,229,330,227,321,227,291,227,262,225,246,224" onmouseover="MM_showHideLayers('select_oklahoma','','show')" onmouseout="MM_showHideLayers('select_oklahoma','','hide')"/>
<!-- OREGON -->
<area href="#" data-state="OR" data-full="Oregon" shape="poly" coords="92,129,95,117,98,106,99,103,100,99,99,98,98,98,98,97,98,97,98,94,101,91,102,90,103,89,103,88,104,87,107,83,109,80,109,78,107,76,107,73,97,71,87,69,77,69,77,68,73,70,70,70,69,68,68,69,64,68,64,67,60,66,60,66,57,65,56,67,51,66,48,63,48,62,48,58,47,55,44,55,43,53,42,53,38,54,37,59,34,65,33,70,29,79,25,88,20,96,18,98,18,103,17,107,18,110,23,111,31,113,35,115,43,117,52,120,61,122" onmouseover="MM_showHideLayers('select_oregon','','show')" onmouseout="MM_showHideLayers('select_oregon','','hide')"/>
<!-- PENNSYLVANIA -->
<area href="#" data-state="PA" data-full="Pennsylvania" shape="poly" coords="539,162,540,162,541,161,543,159,543,157,545,155,545,154,543,153,542,152,541,151,539,150,539,149,538,148,540,147,540,145,539,144,539,143,541,141,541,140,542,137,541,137,540,137,538,135,536,132,534,132,533,130,521,133,492,138,487,140,487,135,483,138,482,139,479,141,481,153,482,161,485,172,488,172,496,172,520,167,530,164,535,163,536,163,538,162,539,162" onmouseover="MM_showHideLayers('select_pennsylvania','','show')" onmouseout="MM_showHideLayers('select_pennsylvania','','hide')"/>
<!-- RHODE ISLAND -->
<area href="#" data-state="RI" data-full="Rhode Island" shape="poly" coords="571,131,571,128,571,125,571,121,573,121,575,121,577,124,579,126,577,128,576,127,576,129,573,130,571,131" onmouseover="MM_showHideLayers('select_rhode_island','','show')" onmouseout="MM_showHideLayers('select_rhode_island','','hide')"/>
<!-- SOUTH CAROLINA -->
<area href="#" data-state="SC" data-full="South Carolina" shape="poly" coords="497,283,497,283,495,283,495,282,494,280,492,278,491,278,489,274,488,270,485,270,484,268,483,266,481,265,480,265,478,262,477,261,473,259,473,259,472,256,471,256,469,253,468,253,465,251,463,250,463,249,464,248,465,247,465,246,469,244,475,241,479,240,490,240,492,241,493,244,496,243,505,243,506,243,515,248,521,254,517,256,516,261,515,265,514,265,514,267,512,267,511,270,508,272,507,274,506,274,504,276,502,277,503,279,499,283,497,283" onmouseover="MM_showHideLayers('select_south_carolina','','show')" onmouseout="MM_showHideLayers('select_south_carolina','','hide')"/>
<!-- SOUTH DAKOTA -->
<area href="#" data-state="SD" data-full="South Dakota" shape="poly" coords="310,146,310,145,308,144,310,140,311,137,308,135,308,134,309,132,311,132,311,127,311,107,310,106,307,104,306,102,306,101,308,100,309,99,309,97,271,97,234,94,234,97,232,107,232,119,230,136,240,137,254,137,265,138,281,139,288,139,289,140,293,143,293,143,296,142,299,142,301,142,302,143,305,143,307,144,308,145,308,147,309,147,310,146" onmouseover="MM_showHideLayers('select_south_dakota','','show')" onmouseout="MM_showHideLayers('select_south_dakota','','hide')"/>
<!-- TENNESSEE -->
<area href="#" data-state="TN" data-full="Tennessee" shape="poly" coords="456,223,422,227,412,228,409,228,406,228,406,230,401,230,396,231,389,231,389,235,387,239,386,241,386,244,385,246,383,247,384,249,384,252,382,254,387,254,404,252,407,252,413,251,430,250,437,249,442,248,449,248,451,247,451,245,452,243,454,243,455,240,458,238,460,237,462,235,466,234,466,232,469,229,469,229,472,228,474,228,476,228,476,226,478,224,478,222,478,219,478,219,476,221,470,221,462,222,456,223" onmouseover="MM_showHideLayers('select_tennessee','','show')" onmouseout="MM_showHideLayers('select_tennessee','','hide')"/>
<!-- TEXAS -->
<area href="#" data-state="TX" data-full="Texas" shape="poly" coords="234,231,248,232,269,233,267,248,267,260,267,261,270,264,272,265,273,265,273,263,274,265,275,265,275,263,277,265,276,266,279,267,281,267,283,267,285,269,286,267,289,268,291,270,292,270,292,272,293,273,294,271,295,271,297,271,297,273,301,274,302,274,302,271,303,271,304,273,307,273,310,274,311,274,313,274,313,272,316,272,317,273,319,271,320,271,320,273,323,273,324,271,325,271,327,273,329,274,330,274,332,275,334,277,336,275,338,276,338,283,338,290,339,297,339,299,340,302,341,305,344,308,344,311,345,311,344,316,342,320,343,321,343,323,343,328,341,330,342,332,338,333,331,336,330,338,329,339,327,339,327,340,323,343,321,345,318,348,313,348,310,351,309,352,305,354,302,355,300,358,297,358,297,359,298,361,297,365,297,367,296,370,295,373,296,375,297,379,297,384,299,385,298,386,296,387,293,385,289,384,288,384,286,384,283,382,280,381,274,378,273,376,273,372,270,371,270,369,270,369,270,367,270,367,269,366,270,363,269,361,267,360,265,358,262,354,259,351,259,350,256,342,255,339,255,338,255,338,251,334,248,332,248,331,247,330,243,330,237,329,236,327,233,329,230,330,229,331,228,334,226,338,224,339,222,339,221,338,219,338,218,336,218,336,216,335,213,333,208,328,207,325,207,320,205,316,204,313,202,313,202,311,200,311,198,309,193,304,192,302,190,300,189,297,187,295,186,295,185,292,190,293,209,294,228,295,229,280,232,244,234,231,235,231" onmouseover="MM_showHideLayers('select_texas','','show')" onmouseout="MM_showHideLayers('select_texas','','hide')"/>
<!-- UTAH -->
<area href="#" data-state="UT" data-full="Utah" shape="poly" coords="166,217,149,214,132,210,110,207,111,200,113,190,116,180,116,171,118,164,120,151,123,140,124,136,132,137,140,139,146,140,152,141,155,142,153,148,153,156,157,157,168,159,174,159,172,173,170,189,167,207,167,214,166,217" onmouseover="MM_showHideLayers('select_utah','','show')" onmouseout="MM_showHideLayers('select_utah','','hide')"/>
<!-- VERMONT -->
<area href="#" data-state="VT" data-full="Vermont" shape="poly" coords="552,115,552,111,550,104,549,104,547,102,548,101,547,99,546,97,546,94,546,90,544,86,543,83,562,79,562,82,562,84,562,87,561,89,559,90,559,91,560,92,560,98,559,104,559,107,560,107,560,110,560,112,560,113,555,114,552,115" onmouseover="MM_showHideLayers('select_vermont','','show')" onmouseout="MM_showHideLayers('select_vermont','','hide')"/>
<!-- VIRGINIA -->
<area href="#" data-state="VA" data-full="Virginia" shape="poly" coords="473,208,474,210,476,210,477,211,478,210,480,209,486,207,488,208,489,205,491,206,492,205,492,202,492,202,495,195,497,190,497,188,497,188,498,189,500,190,501,190,502,186,503,184,505,184,505,182,506,181,507,181,508,178,508,177,508,173,511,174,515,176,516,172,519,174,519,176,522,177,524,178,525,177,525,178,525,181,525,181,524,183,524,185,524,186,527,187,528,188,532,189,533,190,535,190,536,191,535,194,536,194,536,196,538,197,538,199,535,198,535,199,536,200,536,200,538,200,538,202,538,203,537,205,537,205,539,205,541,204,542,204,544,209,541,209,534,211,522,214,508,216,497,218,487,219,481,220,478,219,478,219,476,221,470,221,462,222,456,223,458,222,461,220,464,218,464,218,465,216,468,213,470,210,473,208" onmouseover="MM_showHideLayers('select_virginia','','show')" onmouseout="MM_showHideLayers('select_virginia','','hide')"/>
<!-- WASHINGTON -->
<area href="#" data-state="WA" data-full="Washington" shape="poly" coords="61,17,64,18,70,20,76,21,88,25,105,29,115,32,114,34,112,42,108,56,107,67,107,73,97,71,87,69,77,69,77,68,73,70,70,70,69,68,68,69,64,68,64,67,60,66,60,66,57,65,56,67,51,66,48,63,48,62,48,58,47,55,44,55,43,53,42,53,41,51,40,52,38,51,38,49,40,48,42,45,40,45,40,42,42,42,41,40,40,36,40,34,40,29,39,26,40,21,42,21,43,23,45,24,48,26,51,27,52,27,54,29,56,29,58,29,58,27,59,26,60,26,60,26,60,28,59,28,59,29,60,30,60,32,61,33,62,33,62,32,61,32,61,29,61,28,61,27,61,26,62,23,61,22,60,18,60,18,61,17" onmouseover="MM_showHideLayers('select_washington','','show')" onmouseout="MM_showHideLayers('select_washington','','hide')"/>
<!-- WEST VIRGINIA -->
<area href="#" data-state="WV" data-full="West Virginia" shape="poly" coords="496,172,497,175,497,178,497,178,498,177,500,175,502,175,503,173,504,172,505,172,506,172,508,171,510,170,511,170,512,170,514,172,515,172,516,173,515,176,511,174,508,173,508,177,508,178,507,181,506,181,505,182,505,184,503,184,502,186,501,190,500,190,498,189,497,188,497,188,497,190,495,195,492,202,492,202,492,205,491,206,489,205,488,208,486,207,485,209,478,210,477,211,476,210,474,210,473,208,470,207,469,205,468,202,467,201,465,200,465,200,465,196,466,195,468,195,468,193,468,192,469,189,469,186,470,186,470,187,470,188,472,187,473,186,472,185,472,183,472,182,474,181,475,180,476,180,478,179,479,176,481,174,481,170,481,167,481,164,481,162,481,161,482,160,485,172,488,172,496,172" onmouseover="MM_showHideLayers('select_west_virginia','','show')" onmouseout="MM_showHideLayers('select_west_virginia','','hide')"/>
<!-- WISCONSIN -->
<area href="#" data-state="WI" data-full="Wisconsin" shape="poly" coords="401,143,401,140,400,137,400,134,399,132,400,129,400,127,401,126,401,124,400,121,401,121,402,118,403,117,402,116,402,115,403,113,404,108,405,105,405,102,405,102,405,102,403,107,401,109,400,110,399,112,398,113,397,114,396,113,396,113,397,110,398,107,400,107,400,105,399,105,398,104,397,97,395,97,395,94,386,93,384,92,378,90,373,89,370,86,370,87,370,87,369,86,367,86,367,86,365,86,365,86,365,85,367,83,367,82,366,80,365,81,362,83,358,85,356,86,354,85,354,85,352,86,351,88,351,94,351,95,348,97,346,101,346,101,348,102,348,105,348,107,348,109,348,114,349,116,351,116,353,118,355,118,358,122,362,125,364,126,365,131,365,134,367,135,367,135,365,137,365,140,367,143,368,143,370,143,371,145,377,145,395,144,401,143" onmouseover="MM_showHideLayers('select_wisconsin','','show')" onmouseout="MM_showHideLayers('select_wisconsin','','hide')"/>
<!-- WYOMING -->
<area href="#" data-state="WY" data-full="Wyoming" shape="poly" coords="232,107,225,107,204,105,193,103,175,100,162,98,161,106,158,122,154,142,153,148,153,156,157,157,168,159,173,159,187,161,211,163,228,164,230,135,232,119,232,107" onmouseover="MM_showHideLayers('select_wyoming','','show')" onmouseout="MM_showHideLayers('select_wyoming','','hide')"/>
</map>

<script>
	$(document).ready(function() {
		$('select.uniform').uniform();
	});
</script>
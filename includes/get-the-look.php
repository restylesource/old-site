<?

	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';		
	include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/logincheck.php';

	$photo_path = lookup_config('product_image_path');

	$products_width = 6;

	$is_source = (in_array($g_sess->get_var("systemTeam"), array('retailer','manufacturer')) && $g_sess->get_var("inventory_ind")) ? $g_sess->get_var("inventory_ind") : 0;

	if(!$g_sess->get_var("user")) {
		$sf_onlick = 'onclick="top.location=\'/style-file.php\'"';
	}

	
	$source_id = ($is_source > 0) ? $g_sess->get_var("user") : 0;

	// lookup designer picks
	$product_result = lookup_page_products($_GET['id'], 0, $source_id, 1);

	$i = 0;
	$j = 0;
	
	$designer_output = "";
	
	if($product_result){
		
		$designer_output = "<ul>";
		
		while($row = mysql_fetch_array($product_result)){
			
			if($j==$products_width){
				$j = 0;
				$designer_output.= "</ul><ul>";
			}
			
			$discontinued = ($row['discontinued_ind']) ? '<span style="color:#f47920;font-size:13px;">* DISCONTINUED</span>' : '';
			$discontinued_class = ($row['discontinued_ind']) ? 'discontinued' : '';
			
			$product_image_result = product_image_search($row['product_id'], 0, 1);
			
			if($product_image_result){
				$product_image_row = @mysql_fetch_array($product_image_result);
				
				$product_image = $product_image_row['image'];
				$alt = $product_image_row['image_alt'];
			}
			
			if(user_in_sf($g_sess->get_var("user"), 0, 0, $row['product_id'])){
				$liked = "liked";
			} else {
				$liked = "";
			}
			
			$designer_output.= '<li>
							<a href="/product/' . seo_friendly($row['product']) . '/' . $row['product_id'] . '/">
								<img src="http://www.restylesource.com/' . $photo_path . $product_image .  '" alt="' . $alt . '" />
							</a>
							<a href="#" class="add-to-sf ' . $liked . '" data-type="product" data-id="' . $row['product_id'] . '" title="Add to Style File">Add to Style File</a>
							<h3>' . $row['product'] . '</h3>';
							
			if($is_source){
								
				$designer_output.= '<p>' . $row['short_description'] . '</p>
							<form class="ajax-form" method="post">
								<input type="hidden" id="product-id" value="' . $row['product_id'] . '" />';
				
				if($row['carry'] > 0){
				
					$label = ($is_source==1) ? 'I no longer carry' : 'I am not a source for this style';
				
					$designer_output.= '<label for="remove">
									<input type="radio" class="remove" name="' . $row['product_id'] . '" />
									<p>' . $label . '</p>
								</label>';
				} else {						
					
					if($is_source==1) {						
											
						$output.= '<label for="i-carry">
										<input type="radio" class="i-carry" name="' . $row['product_id'] . '" />
										<p>I have this product</p>
									</label>
									<label for="i-carry-similar">
										<input type="radio" class="i-carry-similar" name="' . $row['product_id'] . '" />
										<p>I carry this line</p>
									</label>';
									
					} else if ($is_source==2){

						$output.= '<label for="i-carry">
										<input type="radio" class="style-source" name="' . $row['product_id'] . '" />
										<p>I AM A SOURCE FOR THIS STYLE</p>
									</label>';
									
					
					}
										
				}				
				
				$designer_output.= '<input type="submit" class="ajax-submit" value="Submit" class="button primary" />
							</form>' . $discontinued;
			} else {
				$designer_output.= '<a href="/product/' .  seo_friendly($row['product']) . '/' . $row['product_id'] . '/">Find this ></a>';
			}				
							
			$designer_output.= '</li>';
						
			$j++;			
		}
		
		$designer_output.= "</ul>";
		
	}

	//lookup related products
	$product_result = lookup_page_products($_GET['id'], 0, $source_id, 0);

	$i = 0;
	
	$output = "";
	
	if($product_result){
		
		$output = "<ul>";
		
		while($row = mysql_fetch_array($product_result)){
			
			if($i==$products_width){
				$i = 0;
				$output.= "</ul><ul>";
			}
			
			$discontinued = ($row['discontinued_ind']) ? '<span style="color:#f47920;font-size:13px;">* DISCONTINUED</span>' : '';
			$discontinued_class = ($row['discontinued_ind']) ? 'discontinued' : '';
			
			$product_image_result = product_image_search($row['product_id'], 0, 1);
			
			if($product_image_result){
				$product_image_row = @mysql_fetch_array($product_image_result);
				
				$product_image = $product_image_row['image'];
				$alt = $product_image_row['image_alt'];
			}
			
			if(user_in_sf($g_sess->get_var("user"), 0, 0, $row['product_id'])){
				$liked = "liked";
			} else {
				$liked = "";
			}
			
			$output.= '<li>
							<a href="/product/' . seo_friendly($row['product']) . '/' . $row['product_id'] . '/">
								<img src="http://www.restylesource.com/' . $photo_path . $product_image .  '" alt="' . $alt . '" />
							</a>
							<a href="#" class="add-to-sf ' . $liked . '" data-type="product" data-id="' . $row['product_id'] . '" title="Add to Style File">Add to Style File</a>
							<h3>' . $row['product'] . '</h3>';
							
			if($is_source){
								
				$output.= '<p>' . $row['short_description'] . '</p>
							<form class="ajax-form" method="post">
								<input type="hidden" id="product-id" value="' . $row['product_id'] . '" />';
				
				if($row['carry'] > 0){
				
					$label = ($is_source==1) ? 'I no longer carry' : 'I am no longer a source for this style';
				
					$output.= '<label for="remove">
									<input type="radio" class="remove" name="' . $row['product_id'] . '" />
									<p>' . $label . '</p>
								</label>';
				} else {						
										
					if($is_source==1) {						
											
						$output.= '<label for="i-carry">
										<input type="radio" class="i-carry" name="' . $row['product_id'] . '" />
										<p>I have this product</p>
									</label>
									<label for="i-carry-similar">
										<input type="radio" class="i-carry-similar" name="' . $row['product_id'] . '" />
										<p>I carry this line</p>
									</label>';
									
					} else if ($is_source==2){

						$output.= '<label for="i-carry">
										<input type="radio" class="style-source" name="' . $row['product_id'] . '" />
										<p>I AM A SOURCE FOR THIS STYLE</p>
									</label>';
									
					
					}
				}				
				
				$output.= '<input type="submit" class="ajax-submit" value="Submit" class="button primary" />
							</form>' . $discontinued;
			} else {
				$output.= '<a href="/product/' .  seo_friendly($row['product']) . '/' . $row['product_id'] . '/">Find this ></a>';
			}				
							
			$output.= '</li>';
						
			$i++;			
		}
		
		$output.= "</ul>";
		
	}

	//lookup restyle picks
	$product_result = lookup_page_products($_GET['id'], 0, $source_id, 0, 1);

	$k = 0;
	
	$restyle_picks = "";
	
	if($product_result){
		
		$restyle_picks = "<ul>";
		
		while($row = mysql_fetch_array($product_result)){
			
			if($k==$products_width){
				$k = 0;
				$restyle_picks.= "</ul><ul>";
			}
			
			$discontinued = ($row['discontinued_ind']) ? '<span style="color:#f47920;font-size:13px;">* DISCONTINUED</span>' : '';
			$discontinued_class = ($row['discontinued_ind']) ? 'discontinued' : '';
			
			$product_image_result = product_image_search($row['product_id'], 0, 1);
			
			if($product_image_result){
				$product_image_row = @mysql_fetch_array($product_image_result);
				
				$product_image = $product_image_row['image'];
				$alt = $product_image_row['image_alt'];
			}
			
			if(user_in_sf($g_sess->get_var("user"), 0, 0, $row['product_id'])){
				$liked = "liked";
			} else {
				$liked = "";
			}
			
			$restyle_picks.= '<li>
							<a href="/product/' . seo_friendly($row['product']) . '/' . $row['product_id'] . '/">
								<img src="http://www.restylesource.com/' . $photo_path . $product_image .  '" alt="' . $alt . '" />
							</a>
							<a href="#" class="add-to-sf ' . $liked . '" data-type="product" data-id="' . $row['product_id'] . '" title="Add to Style File">Add to Style File</a>
							<h3>' . $row['product'] . '</h3>';
							
			if($is_source){
								
				$restyle_picks.= '<p>' . $row['short_description'] . '</p>
							<form class="ajax-form" method="post">
								<input type="hidden" id="product-id" value="' . $row['product_id'] . '" />';
				
				if($row['carry'] > 0){
				
					$label = ($is_source==1) ? 'I no longer carry' : 'I am no longer a source for this style';
				
					$restyle_picks.= '<label for="remove">
									<input type="radio" class="remove" name="' . $row['product_id'] . '" />
									<p>' . $label . '</p>
								</label>';
				} else {						
										
					if($is_source==1) {						
											
						$restyle_picks.= '<label for="i-carry">
										<input type="radio" class="i-carry" name="' . $row['product_id'] . '" />
										<p>I have this product</p>
									</label>
									<label for="i-carry-similar">
										<input type="radio" class="i-carry-similar" name="' . $row['product_id'] . '" />
										<p>I carry this line</p>
									</label>';
									
					} else if ($is_source==2){

						$restyle_picks.= '<label for="i-carry">
										<input type="radio" class="style-source" name="' . $row['product_id'] . '" />
										<p>I AM A SOURCE FOR THIS STYLE</p>
									</label>';
									
					
					}
				}				
				
				$restyle_picks.= '<input type="submit" class="ajax-submit" value="Submit" class="button primary" />
							</form>' . $discontinued;
			} else {
				$restyle_picks.= '<a href="/product/' .  seo_friendly($row['product']) . '/' . $row['product_id'] . '/">Find this ></a>';
			}				
							
			$restyle_picks.= '</li>';
						
			$k++;			
		}
		
		$restyle_picks.= "</ul>";
		
	}


	if($output && $i>0){

?>
		<section id="get-the-look">
        <div id="product-link"></div>
			<h2>Get the look: Find the Products Above</h2>
			<?=$output?>
		</section>
<?

	}
	
	if($designer_output && $j>0){
?>
		<section id="get-the-look">
			<h2>Get the Look: Designer Picks</h2>
			<?=$designer_output?>
		</section>	

<?
	}

	if($restyle_picks && $k>0){
?>
		<section id="get-the-look">
			<h2>Get the Look: Restyle Picks</h2>
			<?=$restyle_picks?>
		</section>		
<?
	}
?>	
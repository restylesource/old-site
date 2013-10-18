<?

	include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/db.php");	
	include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/logincheck.php");
	
	$image_dir = "/products/";
	
	$aColumns = array( 'product_id', 'product', 'product', 'upc', 'msrp', 'status', 'keywords', 'short_description', 'long_description' );
	
	$sIndexColumn = "product_id";
	$sTable = "tbl_product";
	
	$dbh = db_connect();
	
	/* 
	 * Paging
	 */
	$sLimit = "";
	if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
	{
		$sLimit = "LIMIT ".mysql_real_escape_string( $_GET['iDisplayStart'] ).", ".
			mysql_real_escape_string( $_GET['iDisplayLength'] );
	}
	
	
	/*
	 * Ordering
	 */
	if ( isset( $_GET['iSortCol_0'] ) )
	{
		$sOrder = "ORDER BY  ";
		for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
		{
			if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
			{
				$sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
				 	".mysql_real_escape_string( $_GET['sSortDir_'.$i] ) .", ";
			}
		}
		
		$sOrder = substr_replace( $sOrder, "", -2 );
		if ( $sOrder == "ORDER BY" )
		{
			$sOrder = "";
		}
	}
	
	/* 
	 * Filtering
	 * NOTE this does not match the built-in DataTables filtering which does it
	 * word by word on any field. It's possible to do here, but concerned about efficiency
	 * on very large tables, and MySQL's regex functionality is very limited
	 */
	$sWhere = "WHERE archived_ind=0 AND product_id NOT IN (SELECT product_id FROM tbl_retailer_inventory WHERE user_id = " . $g_sess->get_var("user") . ")";
	
	if ( $_GET['sSearch'] != "" )
	{
		$sWhere .= " AND (";
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			$sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
		}
		//$sWhere = substr_replace( $sWhere, "", -3 );
		$sWhere.= " product_id IN (SELECT product_id FROM tbl_product_category_rel WHERE category_id IN (SELECT category_id FROM `tbl_categories` WHERE category LIKE '%" . $_GET['sSearch']  . "%'))";
		$sWhere .= ')';
	}
	
	//$sWhere = "WHERE archived_ind=0";
	
	/* Individual column filtering */
	for ( $i=0 ; $i<count($aColumns) ; $i++ )
	{
		if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
		{
			if ( $sWhere == "" )
			{
				$sWhere = "WHERE ";
			}
			else
			{
				$sWhere .= " AND ";
			}
			$sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
		}
	}
	
	
	/*
	 * SQL queries
	 * Get data to display
	 */
	$sQuery = "
		SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
		FROM   $sTable
		$sWhere 
		$sOrder
		$sLimit
	";
	
	//mail('kevin@atekie.com', 'testing', $sQuery, 'from: support@restylesource.info');
	
	$rResult = db_result_query( $sQuery );
	
	/* Data set length after filtering */
	$sQuery = "
		SELECT FOUND_ROWS()
	";
	$rResultFilterTotal = db_result_query( $sQuery );
	$aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
	$iFilteredTotal = $aResultFilterTotal[0];
	
	/* Total data set length */
	$sQuery = "
		SELECT COUNT(".$sIndexColumn.")
		FROM   $sTable
	";
	$rResultTotal = db_result_query( $sQuery );
	$aResultTotal = mysql_fetch_array($rResultTotal);
	$iTotal = $aResultTotal[0];
	
	
	/*
	 * Output
	 */
	$output = array(
		"sEcho" => intval($_GET['sEcho']),
		"iTotalRecords" => $iTotal,
		"iTotalDisplayRecords" => $iFilteredTotal,
		"aaData" => array()
	);
	
	$product_checkbox = ($g_sess->get_var("systemTeam") == "retailer") ? "product_adopt[]" : "product_delete[]";
	
	while ( $aRow = mysql_fetch_array( $rResult ) ){
		$row = array();
		
		//Next line will be where we check product ownership (edit product or add to inventory)
		$edit_link = ($g_sess->get_var("user") == $aRow['user_id'] || $g_sess->get_var("systemTeam") != "retailer" ) ? "products-inventory-add.php" : "products-inventory-add.php";
		
		$category = product_category_first_lookup($aRow['product_id']);
		
		$product_image_result = product_image_search($aRow['product_id']);
		$product_image_output = "";
		$i=0;
		
		while($product_image_row = @mysql_fetch_array($product_image_result)){
			$product_image_output.= '<li><a id="link_' . $aRow['product_id'] . '_' . $i . '" class="lightbox" href="'.$image_dir.$product_image_row['image'] .'" title="">'.$aRow['product'].'</li>';
			$i++;
		}
		
		$row[0] = '<input type="checkbox" name="' . $product_checkbox . '" value="' . $aRow['product_id'] . '" />';
		$row[1] = $aRow['product_id'];
		$row[2] = '<a href="#" onclick="$(\'#link_' . $aRow['product_id'] . '_0\').click(); return false;">' . $aRow['product'] . '</a>
										<div id="' . $aRow['product_id'] . '" class="gallary" style="display: none;">
											<ul>
												' . $product_image_output. '
											</ul>
										</div>';
		$row[3] = $category;
		$row[4] = $aRow['upc'] ;
		$row[5] = $aRow['msrp'] ;
		$row[6] = $aRow['status'] ;
		$row[7] = '<a rel="Edit Product" href="' . $edit_link . '?product_id=' . $aRow['product_id'] . '" class=""><img src="gfx/icon-edit.png" alt="edit" /></a>';
		
		$output['aaData'][] = $row;
	}
	
	echo json_encode( $output );

?>
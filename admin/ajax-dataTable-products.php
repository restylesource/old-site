<?

	include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/db.php");	
	include_once($_SERVER['DOCUMENT_ROOT'] . "/includes/logincheck.php");
	
	$image_dir = "/products/";
	
	$aColumns = array( 'product_id', 'product', 'product', 'item_nbr', 'company', 't1.status', 'discontinued_ind', 't1.keywords', 'short_description', 'long_description' );
	
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
	$sWhere = "WHERE archived_ind=0";
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
		FROM   $sTable t1
		LEFT JOIN tbl_user t2 ON t1.manufacturer_id = t2.id
		$sWhere 
		$sOrder
		$sLimit
	";
	
	//mail('kevin@atekie.com', 'testing', print_r($_REQUEST, true), 'from: support@restylesource.info');
	
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
	
	while ( $aRow = mysql_fetch_array( $rResult ) ){
		$row = array();
		
		//Next line will be where we check product ownership (edit product or add to inventory)
		$edit_link = ($g_sess->get_var("user") == $aRow['user_id'] || $g_sess->get_var("systemTeam") != "user" ) ? "products-new.php" : "products-new.php";
		
		$category = product_category_first_lookup($aRow['product_id']);
		
		$product_image_result = product_image_search($aRow['product_id']);
		$product_image_output = "";
		$i=0;
		
		while($product_image_row = @mysql_fetch_array($product_image_result)){
			$product_image_output.= '<li><a id="link_' . $aRow['product_id'] . '_' . $i . '" class="lightbox" href="'.$image_dir.$product_image_row['image'] .'" title="">'.$aRow['product'].'</li>';
			$i++;
		}
		
		$discontinued = ($aRow['discontinued_ind']) ?  "Yes" : "No";
		
		$row[0] = $aRow['product_id'];
		$row[1] = $aRow['product'];
		$row[2] = $category;
		$row[3] = $aRow['item_nbr'] ;
		$row[4] = $aRow['company'] ;
		$row[5] = $aRow['status'] ;
		$row[6] = $discontinued;
		$row[7] = '<a rel="Edit Product" href="' . $edit_link . '?product_id=' . $aRow['product_id'] . '" class=""><img src="gfx/icon-edit.png" alt="edit" /></a>
				   <a href="' . $PHP_SELF . '?action=delete&product_id=' . $aRow['product_id'] . '" onclick="return confirm(\'Are you sure you want to delete?\r\n\r\nWARNING: This cannot be undone.\')"><img src="gfx/icon-delete.png" alt="delete" /></a>';
		
		$output['aaData'][] = $row;
	}
	
	echo json_encode( $output );

?>
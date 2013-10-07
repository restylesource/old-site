<?php

include_once('includes/db.php');	
include_once('includes/logincheck.php');

$skip_header = 1;

if($_FILES["file"]){
	if ($_FILES["file"]["error"] > 0){
		echo("<strong>Error: " . $_FILES["file"]["error"] . "</strong><BR><BR>");
	} else {
		
		if(move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $_FILES["file"]["name"])){	  
			$i = 0;
		  
			if (($handle = fopen("upload/" . $_FILES["file"]["name"], "r")) !== FALSE) {
		  
				// User Type Retailer or Manufacturer??
				$user_group_id = ($_POST['kind'] == "retailer") ? 3 : 4;
		  
				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				
					if($skip_header==1 && $skipped == 0){
						$skipped = 1;
						continue;
					}
				
					if($data[0] && $data[1]){
						
						$error = "";
				
						$user_id = source_add($user_group_id, $data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6], $data[7], $data[8], $data[9], $error);
						
						if($user_id>0){
							$i++;
						} else {
							echo("<strong>Error: " . $error . "</strong><BR><BR>");
						}
					}
					
				}
				
				echo($i . " rows imported.<BR><BR>");
				
			} else {
				echo("<strong>Error: Unable to save file to upload directory</strong><BR><BR>");
			}
		}
	}
}
?>
<html>
<body>
<form method='POST' enctype='multipart/form-data'>
Please specify a Kind of Source:<br /><br />
<input type="radio" name="kind" value="retailer" /> Retailer<br />
<input type="radio" name="kind" value="manufacturer" /> Manufacturer<br /><br />
Please specify a CSV file to upload:<br /><br />
<input type="file" name="file" size="40"><BR><BR>
<input type="submit" name="upload" value="Upload">
</form>
</body>
</html>
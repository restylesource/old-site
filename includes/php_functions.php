<?

function seo_friendly($val){

	$string = str_replace("-+-", "+", str_replace("\"", "", str_replace("'", '', str_replace(" ", "-", $val))));
	
	if(!$string){
		$string = "no-info";
	}
	
	return $string;

}

function down($a,$x) {
	if( count($a)-1 > $x ) {
		$b = array_slice($a,0,$x,true);
		$b[] = $a[$x+1];
		$b[] = $a[$x];
		$b += array_slice($a,$x+2,count($a),true);
		return($b);
	} else { return $a; }
}

function up($a,$x) {
	if( $x > 0 and $x < count($a) ) {
		$b = array_slice($a,0,($x-1),true);
		$b[] = $a[$x];
		$b[] = $a[$x-1];
		$b += array_slice($a,($x+1),count($a),true);
		return($b);
	} else { return $a; }
}


function int_ok($val) {
	return ($val !== true) && (( string ) ( int ) $val) === (( string ) $val);
}

function gfe($file_name) {
	return pathinfo($file_name, PATHINFO_EXTENSION);
}

function my_redirect($link, $secure = 0) {
	$http = "http://";
	
	//we expect links to not start with /, so remove it if it exists
	if (substr ( $link, 0, 1 ) == "/")
		$link = substr ( $link, 1 );
	
	if ($secure == 1)
		$http = "https://";

	//This handles the case if the username is in the URL (~username/)
	$url_array = parse_url($_SERVER["REQUEST_URI"]);
	$url_array = explode("/", $url_array['path']);
	
	if(substr($url_array[1], 0, 1) == "~"){
		if(!strstr($link, $url_array[1])){
			$link = $url_array[1] . "/" . $link;
		}
	}
	
	$link = $http . $_SERVER ['SERVER_NAME'] . "/" . $link;
	
	if (! headers_sent ()) {
		header ( 'Location:' . $link );
		die ();
	} else {
		echo "\n<meta http-equiv=\"refresh\" " . " content=\"0;URL=" . $link . "\">\n";
		die ();
	}
}

function dateDiff($start, $end) {
  $start_ts = strtotime($start);
  $end_ts = strtotime($end);
  $diff = $end_ts - $start_ts;
  return round($diff / 86400);
}

function format_date($input_format, $output_format, $date) {
	
	switch ($input_format) {
		
		case "YYYY-MM-DD" :
			list ( $year, $month, $day ) = split ( "-", $date );
			$month = str_pad ( $month, 2, 0, STR_PAD_LEFT );
			$day = str_pad ( $day, 2, 0, STR_PAD_LEFT );
			break;
		case "YYYY/MM/DD" :
			list ( $year, $month, $day ) = split ( "/", $date );
			$month = str_pad ( $month, 2, 0, STR_PAD_LEFT );
			$day = str_pad ( $day, 2, 0, STR_PAD_LEFT );
			break;
		case "MM/DD/YYYY" :
			list ( $month, $day, $year ) = split ( "/", $date );
			$month = str_pad ( $month, 2, 0, STR_PAD_LEFT );
			$day = str_pad ( $day, 2, 0, STR_PAD_LEFT );
			break;
		case "MM-DD-YYYY" :
			list ( $month, $day, $year ) = split ( "-", $date );
			$month = str_pad ( $month, 2, 0, STR_PAD_LEFT );
			$day = str_pad ( $day, 2, 0, STR_PAD_LEFT );
			break;
		case "YYYYMMDD" :
			$year = substr ( $new_date, 0, 4 );
			$month = substr ( $new_date, 4, 2 );
			$day = substr ( $new_date, 6, 2 );
			break;
	
	}
	
	switch ($output_format) {
		
		case "MM/DD/YYYY" :
			$output_date = $month . "/" . $day . "/" . $year;
			break;
		case "MM-DD-YYYY" :
			$output_date = $month . "-" . $day . "-" . $year;
			break;
		case "MM/DD/YY" :
			$output_date = $month . "/" . $day . "/" . substr ( $year, - 2 );
			break;
		case "MM-DD-YY" :
			$output_date = $month . "-" . $day . "-" . substr ( $year, - 2 );
			break;
		case "YYYY-MM-DD" :
			$output_date = $year . "-" . $month . "-" . $day;
			break;
	}
	
	return $output_date;

}

function MakeNullsZeros($val) {
	if ($val == "") {
		return 0;
	} else {
		return $val;
	}
}

function MakeZerosNulls($val) {
	if($val == 0){
		return "";
	} else {
		return $val;
	}
}

function MakeEmptyNull($val){
	if($val==""){
		return "NULL";
	} else {
		return $val;
	} 
}

function user_forgot_password($user_id, $email){

	if($email && $user_id){
	
		$newPassword = generatePassword();
		updatePassword($user_id, $newPassword);
	
		$user_result = user_search($user_id);
		
		if($user_result){
			$user_row = @mysql_fetch_array($user_result);
			$email_to = $user_row['first_name'] . " " . $user_row['last_name'] . "<" . $email . ">";
		} else {
			$email_to = $email;
		}
	
		$message.= "We have reset your password:\r\n\r\n";
		$message.= "Username: $email\r\n";
		$message.= "Password: $newPassword\r\n\r\n";
		$message.= "Thank you,\r\n\r\n";
		$message.= "ReStyleSource";

		$message = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/email/password.html');
		$message = str_replace("[login]", $email, $message);
		$message = str_replace("[password]", $newPassword, $message);

		$headers = "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n"; 
    	$headers  .= "From: " . SYSTEM_EMAIL . "\r\n";

		// HTML Email
		mail($email_to, 'Forgot Password', $message, $headers); 

		// standard email
		//mail($email_to, 'Forgot Password', $message, 'from: ' . SYSTEM_EMAIL);
	
	}
}

function user_signup($email, $password){

	$message = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/email/success.html');
	$message = str_replace("[login]", $email, $message);
	$message = str_replace("[password]", $password, $message);

	$headers = "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n"; 
	$headers  .= "From: " . SYSTEM_EMAIL . "\r\n";

	// HTML Email
	mail($email, 'Welcome to RestyleSource!', $message, $headers); 

}

function generatePassword ($length = 5) {

    // start with a blank password
    $password = "";

    // define possible characters - any character in this string can be
    // picked for use in the password, so if you want to put vowels back in
    // or add special characters such as exclamation marks, this is where
    // you should do it
    $possible = "2346789bcd";

    // we refer to the length of $possible a few times, so let's grab it now
    $maxlength = strlen($possible);
  
    // check for length overflow and truncate if necessary
    if ($length > $maxlength) {
      $length = $maxlength;
    }
	
    // set up a counter for how many characters are in the password so far
    $i = 0; 
    
    // add random characters to $password until $length is reached
    while ($i < $length) { 

      // pick a random character from the possible ones
      $char = substr($possible, mt_rand(0, $maxlength-1), 1);
        
      // have we already used this character in $password?
      if (!strstr($password, $char)) { 
        // no, so it's OK to add it onto the end of whatever we've already got...
        $password .= $char;
        // ... and increase the counter by one
        $i++;
      }

    }

    // done!
    return $password;

}

function date_friendly($mysql_date, $include_time=0, $short=0){

	$datetime = strtotime($mysql_date);

	if($include_time){
		if($short){
			$mysqldate = date("m/d/y g:i A", $datetime);
		} else {
			$mysqldate = date("jS F Y h:i:s A", $datetime);
		}
	} else {
		if($short){
			$mysqldate = date("m d y", $datetime);
		} else {
			$mysqldate = date("jS F Y", $datetime);
		}
	}
	
	return $mysqldate;
}

function calcAge($birthdate){

	$date = new DateTime($birthdate);
	$now = new DateTime();
	$interval = $now->diff($date);
	return $interval->y;
		
}

function build_generic_dropdown($result, $selected, $blank_option, $blank_option_text="", $key=0, $value=1) {
	
	if ($blank_option) {
		$options .= "<option value=\"\">" . $blank_option_text . "</option>";
	}
	
	while ( $row = @mysql_fetch_array ( $result ) ) {
		
		if ($row [$key] != $selected) {
			$options .= "<option value=\"" . $row [$key] . "\">" . stripslashes ( $row [$value] ) . "</option>";
		} else {
			$options .= "<option value=\"" . $row [$key] . "\" selected>" . stripslashes ( $row [$value] ) . "</option>";
		}
	}
	return $options;
}

function curPageURL() {
	$pageURL = 'http';
	
	if ($_SERVER["HTTPS"] == "on") {
		$pageURL .= "s";
	}
	
	$pageURL .= "://";
	
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	
	return $pageURL;
}

function remove_empty_querystring($querystring){
	//remove empty objects from querystring
	$query_array = split('&', $querystring);

	$length = sizeof($query_array);

	for($i=0;$i<$length;$i++){
		$val_array =  split('=', $query_array[$i]);

		if($val_array[1] != ""){
			if($new_query==""){
				$new_query .= $query_array[$i];
			} else {
				$new_query .= "&" . $query_array[$i];
			}
		}
	}

	return $new_query;
}

function remove_object_querystring($querystring, $value){
	$query_array = split('&', $querystring);

	$length = sizeof($query_array);

	for($i=0;$i<$length;$i++){
		$val_array =  split('=', $query_array[$i]);

		if($val_array[0] != $value){
			if($new_query==""){
				$new_query .= $query_array[$i];
			} else {
				$new_query .= "&" . $query_array[$i];
			}
		}
	}

	return $new_query;
}

function set_object_value_querystring($querystring, $object, $value){
	$query_array = split('&', $querystring);

	$length = sizeof($query_array);

	for($i=0;$i<$length;$i++){
		$val_array =  split('=', $query_array[$i]);

		if($val_array[1] != "" && $val_array[0] == $object){
			if($i==0){
				$new_query .= $object . "=" . trim($value);
			} else {
				$new_query .= "&" . $object . "=" . trim($value);
			}
			$good = 1;
		} else if ($val_array[1] != ""){
			if($i==0){
				$new_query .= $query_array[$i];
			} else {
				$new_query .= "&" . $query_array[$i];
			}		
		}
	}

	if($good!=1){
		if($new_query==""){
			$new_query = $object . "=" . trim($value);
		} else {
			$new_query .= "&" . trim($object) . "=" . trim($value);
		}
	}	

	return $new_query;	
}

function encryptCookie($value) {
	if (! $value) {
		return false;
	}
	$key = 'rapidRise3';
	$text = $value;
	$iv_size = mcrypt_get_iv_size ( MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB );
	$iv = mcrypt_create_iv ( $iv_size, MCRYPT_RAND );
	$crypttext = mcrypt_encrypt ( MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_ECB, $iv );
	return trim ( base64_encode ( $crypttext ) ); //encode for cookie
}

function decryptCookie($value) {
	if (! $value) {
		return false;
	}
	$key = 'rapidRise3';
	$crypttext = base64_decode ( $value ); //decode cookie
	$iv_size = mcrypt_get_iv_size ( MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB );
	$iv = mcrypt_create_iv ( $iv_size, MCRYPT_RAND );
	$decrypttext = mcrypt_decrypt ( MCRYPT_RIJNDAEL_256, $key, $crypttext, MCRYPT_MODE_ECB, $iv );
	return trim ( $decrypttext );
}

function selfURL() {
	$s = empty($_SERVER["HTTPS"]) ? ''
		: ($_SERVER["HTTPS"] == "on") ? "s"
		: "";
	$protocol = strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s;
	$port = ($_SERVER["SERVER_PORT"] == "80") ? ""
		: (":".$_SERVER["SERVER_PORT"]);
	return $protocol."://".$_SERVER['SERVER_NAME'].$port.$_SERVER['REQUEST_URI'];
}

function strleft($s1, $s2) {
	return substr($s1, 0, strpos($s1, $s2));
}

function image_crop($src_image, $dest_image, $thumb_width, $thumb_height, $quality=100){

	if(substr($src_image, -3) == "png"){
		$image = @imagecreatefrompng($src_image);
	} else {
		$image = @imagecreatefromjpeg($src_image);
	}

	if(!$image) return;

	$filename = $dest_image;
	
	$width = imagesx($image);
	$height = imagesy($image);
	
	$original_aspect = $width / $height;
	$thumb_aspect = $thumb_width / $thumb_height;
	
	if ( $original_aspect >= $thumb_aspect )
	{
	   // If image is wider than thumbnail (in aspect ratio sense)
	   $new_height = $thumb_height;
	   $new_width = $width / ($height / $thumb_height);
	}
	else
	{
	   // If the thumbnail is wider than the image
	   $new_width = $thumb_width;
	   $new_height = $height / ($width / $thumb_width);
	}
	
	$thumb = imagecreatetruecolor( $thumb_width, $thumb_height );
	
	// Resize and crop
	imagecopyresampled($thumb,
					   $image,
					   0 - ($new_width - $thumb_width) / 2, // Center the image horizontally
					   0 - ($new_height - $thumb_height) / 2, // Center the image vertically
					   0, 0,
					   $new_width, $new_height,
					   $width, $height);

	
	imagejpeg($thumb, $filename, $quality);

	//die($thumb . ", " . $filename . " , " . $quality);

}

function square_crop($src_image, $dest_image, $thumb_size = 200, $jpg_quality = 100) {
 
    // Get dimensions of existing image
    $image = getimagesize($src_image);
 
    // Check for valid dimensions
    if( $image[0] <= 0 || $image[1] <= 0 ) return false;
 
    // Determine format from MIME-Type
    $image['format'] = strtolower(preg_replace('/^.*?\//', '', $image['mime']));
 
    // Import image
    switch( $image['format'] ) {
        case 'jpg':
        case 'jpeg':
            $image_data = imagecreatefromjpeg($src_image);
        break;
        case 'png':
            $image_data = imagecreatefrompng($src_image);
        break;
        case 'gif':
            $image_data = imagecreatefromgif($src_image);
        break;
        default:
            // Unsupported format
            return false;
        break;
    }
 
    // Verify import
    if( $image_data == false ) return false;
 
    // Calculate measurements
    if( $image[0] > $image[1] ) {
        // For landscape images
        $x_offset = ($image[0] - $image[1]) / 2;
        $y_offset = 0;
        $square_size = $image[0] - ($x_offset * 2);
    } else {
        // For portrait
        $x_offset = 0;
        $y_offset = ($image[1] - $image[0]) / 2;
        $square_size = $image[1] - ($y_offset * 2);
    }
 
    // Resize and crop
    $canvas = imagecreatetruecolor($thumb_size, $thumb_size);
    if( imagecopyresampled(
        $canvas,
        $image_data,
        0,
        0,
        $x_offset,
        $y_offset,
        $thumb_size,
        $thumb_size,
        $square_size,
        $square_size
    )) {
 
        // Create thumbnail
        switch( strtolower(preg_replace('/^.*\./', '', $dest_image)) ) {
            case 'jpg':
            case 'jpeg':
                return imagejpeg($canvas, $dest_image, $jpg_quality);
            break;
            case 'png':
                return imagepng($canvas, $dest_image);
            break;
            case 'gif':
                return imagegif($canvas, $dest_image);
            break;
            default:
                // Unsupported format
                return false;
            break;
        }
 
    } else {
        return false;
    }
 
}


?>
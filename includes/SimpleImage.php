<?php
/*
* File: SimpleImage.php
* Author: Simon Jarvis
* Copyright: 2006 Simon Jarvis
* Date: 08/11/06
* Link: http://www.white-hat-web-design.co.uk/articles/php-image-resizing.php
* 
* This program is free software; you can redistribute it and/or 
* modify it under the terms of the GNU General Public License 
* as published by the Free Software Foundation; either version 2 
* of the License, or (at your option) any later version.
* 
* This program is distributed in the hope that it will be useful, 
* but WITHOUT ANY WARRANTY; without even the implied warranty of 
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
* GNU General Public License for more details: 
* http://www.gnu.org/licenses/gpl.html
*
*/
 
class SimpleImage {
   
   var $image;
   var $image_type;
 
   function load($filename) {
      $image_info = getimagesize($filename);
      $this->image_type = $image_info[2];
      if( $this->image_type == IMAGETYPE_JPEG ) {
         $this->image = imagecreatefromjpeg($filename);
      } elseif( $this->image_type == IMAGETYPE_GIF ) {
         $this->image = imagecreatefromgif($filename);
      } elseif( $this->image_type == IMAGETYPE_PNG ) {
         $this->image = imagecreatefrompng($filename);
      }
   }
   function rotate($degrees){
   		$rotate = imagerotate($this->image, $degrees, 0);
   		$this->image = $rotate;
   }
   function squareCrop(){
		
		$orig_width = imagesx($this->image);
		$orig_height = imagesy($this->image);
		
		//this makes image square
		if($orig_width>$orig_height){
			$start_x = floor(($orig_width - $orig_height) / 2);
			$end_x = $orig_height;
			
			$dest_img = imagecreatetruecolor($orig_height,$orig_height);
			imagecopyresampled($dest_img, $this->image, 0, 0, $start_x, 0, $orig_height, $orig_height, $end_x, $orig_height);	
		} else {
			$start_y = floor(($orig_height - $orig_width) / 2);
			$end_y = $orig_width;
			
			$dest_img = imagecreatetruecolor($orig_width,$orig_width);
			imagecopyresampled($dest_img, $this->image, 0, 0, 0, $start_y, $orig_width, $orig_width, $orig_width, $end_y);	
		}
		
		/*
		$imageArray = array();
		
		$imageArray['orig_width'] = $orig_width;
		$imageArray['orig_height'] = $orig_height;
		
		$imageArray['start_x'] = $start_x;
		$imageArray['start_y'] = $start_y;
		
		$imageArray['end_x'] = $start_x + $new_width;
		$imageArray['end_y'] = $start_y + $new_height;
		
		$results = print_r($imageArray, true);
		
		mail('klott@mediacomplete.com', 'image data', $results, 'from: support@worshipteam.com');
		*/
		
		$this->image = $dest_img;

   }
   function save($filename, $image_type=IMAGETYPE_JPEG, $compression=75, $permissions=null) {
      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image,$filename,$compression);
      } elseif( $image_type == IMAGETYPE_GIF ) {
         imagegif($this->image,$filename);         
      } elseif( $image_type == IMAGETYPE_PNG ) {
         imagepng($this->image,$filename);
      }   
      if( $permissions != null) {
         chmod($filename,$permissions);
      }
   }
   function output($image_type=IMAGETYPE_JPEG) {
      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image);
      } elseif( $image_type == IMAGETYPE_GIF ) {
         imagegif($this->image);         
      } elseif( $image_type == IMAGETYPE_PNG ) {
         imagepng($this->image);
      }   
   }
   function getWidth() {
      return imagesx($this->image);
   }
   function getHeight() {
      return imagesy($this->image);
   }
   function resizeToHeight($height) {
      $ratio = $height / $this->getHeight();
      $width = $this->getWidth() * $ratio;
      $this->resize($width,$height);
   }
   function resizeToWidth($width) {
      $ratio = $width / $this->getWidth();
      $height = $this->getheight() * $ratio;
      $this->resize($width,$height);
   }
   function scale($scale) {
      $width = $this->getWidth() * $scale/100;
      $height = $this->getheight() * $scale/100; 
      $this->resize($width,$height);
   }
   function resize($width,$height) {
      $new_image = imagecreatetruecolor($width, $height);
      imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
      $this->image = $new_image;   
   }      
}
?>
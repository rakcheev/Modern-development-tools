<?php

namespace App;

class ResizeImage{
    
	public static function resizeImage($type, $name, $filName, $path, $width){
		$type = mb_strtolower($type);
		switch ($type) {
			case 'jpg':
				$img = imagecreatefromjpeg($name);
				break;
			case 'png':
				$img = imagecreatefrompng($name);
				break;
			
			case 'gif':
				$img = imagecreatefromgif($name);
				break;
			
			case 'jpeg':
				$img = imagecreatefromjpeg($name);
				break;
		}
		$img_width = imageSX($img);
	    $img_height = imageSY($img);
		
		$k = $img_width/$width;
		$height = round($img_height/$k,0);
		
		$new_img = imagecreatetruecolor($width, $height);
		$res = imagecopyresampled($new_img, $img, 0, 0, 0, 0, $width, $height, $img_width, $img_height);
		switch ($type) {
			case 'jpg':
				$res_end = imagejpeg($new_img, $path.$filName);
				break;
			case 'png':
				$res_end = imagepng($new_img, $path.$filName);
				break;
			
			case 'gif':
				$res_end = imagegif($new_img, $path.$filName);
				break;
			
			case 'jpeg':
				$res_end = imagejpeg($new_img, $path.$filName);
				break;
		}
		imagedestroy($img);
		imagedestroy($new_img);
		return $res_end;
	}
}

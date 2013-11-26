<?php 

class Utility 
{
	public static function mysql_query_string($string)
	{
		return $string;
		$enabled = true;
		$htmlspecialchars = false; # Convert special characters to HTML entities 
		/****************************************************************
		The translations performed are: 
	
		'&' (ampersand) becomes '&amp;' 
		'"' (double quote) becomes '&quot;' when ENT_NOQUOTES is not set. 
		''' (single quote) becomes '&#039;' only when ENT_QUOTES is set. 
		'<' (less than) becomes '&lt;' 
		'>' (greater than) becomes '&gt;' 
	
		*****************************************************************/
		
		if($htmlspecialchars)
		{
			# Convert special characters to HTML entities 
			$string = htmlspecialchars($string, ENT_QUOTES);
		}
		else
		{
			/****************************************************************
			'"' (double quote) becomes '&quot;' 
			''' (single quote) becomes '&#039;' 
			****************************************************************/
			//$string = str_replace('"',"&quot;",$string);
			//$string = str_replace("'","&#039;",$string);
		}
		if($enabled and gettype($string) == "string")
		{
			# Escapes special characters in a string for use in a SQL statement
			
			
			$res =  mysql_real_escape_string(trim($string));
		
			return $res;
		}
		elseif($enabled and gettype($string) == "array")
		{
			$ary_to_return = array();
			foreach($string as $str)
			{
					$ary_to_return[]=mysql_real_escape_string(trim($str));
			}
			$res=  $ary_to_return;
		
			return $res;
		}
		else
		{
			$res=  trim($string);
			
			return $res;
		}
	}
	
	/****************************************Date format function*********************************************************/	
	public static function dat($date, $format="F d, Y-h:i(worry)")
	 {
	  //  it will convert the date according to provided format
	  // parameteres
	  // $date it will be in any format like 2009-12-12, Jan 12 2009
	  // $format in which format do you need a date
	  
	  if($date == '0000-00-00' or $date == '0000-00-00 00:00:00' or $date == '')
	  {
	   return '';
	  } // if ends
	  else
	  {
	   $date = date($format, strtotime($date));
	   return $date;
	  } // else ends
	 } // ends
	 
/*********************************************Is Winner****************************************************/	 
  public static function is_winner($user_id)
  {
	  $sql			= 'select COUNT(id) as total from winner where `user_id` = "'.$user_id.'"';
	  $checkWinner  = DB::first($sql);	
	  return $checkWinner;
  }
  /*****************************Update Venue*********************************/

	public static function update_action($param)
   {
	  $id     	  	= 	Utility::mysql_query_string($param['id']);
	  $name			=	Utility::mysql_query_string($param['name']);
	  $city	        =	Utility::mysql_query_string($param['city']);
	  $country	    =	Utility::mysql_query_string($param['country']);
	  
	 $sql = 'SELECT COUNT(id) as total from venue where `id` = "'.$id.'" ';
	
	  $count = DB::first($sql);
	 
	   if($count->total>0)
	   { 
		 
		  $data = DB::query('UPDATE venue set  
													`name` 			= "'.$name.'" ,
													`city` 	        = "'.$city.'" ,
													`country`	    = "'.$country.'" 
													 where `id` 	= "'.$id.'";
													');
		  $key  = 'venue_'.$id;
		  Cache::forget($key);
		  return array('status' =>'success');  
	   }
	   else
	   {
			return array('status'=> 'error');   
	   } 
   }
   
   
    public static function jsonReadData($fileName)
	{
		if(file_exists(Config::get('application.doc_path').'public/scoreboard/'.$fileName.'.json'))
		{
	 		$fileName = Config::get('application.web_url').'public/scoreboard/'.$fileName.'.json';
	 		$data = self::curl_get_contents_json($fileName);	
	 		return $data;
		}
		else
		{
			return false;
		}
	}

	public static function curl_get_contents_json($url)
	{
	 $ch = curl_init ($url);
	 curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
	 $returndata = curl_exec ($ch);
	 return $returndata;
	}

	public static function jsonWriteData($jsonData,$fileName)
	{ 
	
	 //Decode the given data
	 json_decode($jsonData); 
	 
	 // Check if no error is generated in last encoding/decoding operation
	  if(json_last_error()==JSON_ERROR_NONE)
	 {
	  $fp = fopen(Config::get('application.doc_path').'public/scoreboard/'.$fileName.'.json', 'w+');
	  // If file pointer is resource, write the data
	  if(is_resource($fp))
	  {
	   fwrite($fp, $jsonData);
	   fclose($fp);
	   return true;
	  }
	  else
	   return false;
	 }
	 else
	 {
	  return false;
	 } 
	}
	
	public static function array_sort_by_column(&$arr, $col, $dir) 
	{
	    $sort_col = array();
	    foreach ($arr as $key=> $row) 
	    {
	        $sort_col[$key] = $row[$col];
	    }
	    array_multisort($sort_col, $dir, $arr);
	}
	
/*****************Timezone / GMT*************************/


public static function GetGMTfromTimezone($match_timezone)
{
	$match_gmt = '';
	$zones_array = array();        
	$timestamp = time();         
	foreach (timezone_identifiers_list() as $key => $zone) {    
		date_default_timezone_set($zone);
		$zones_array[$key]['zone'] = $zone;
		$zones_array[$key]['diff_from_GMT'] = date('P', $timestamp);
		if ($zone==$match_timezone) { $match_gmt = $zones_array[$key]['diff_from_GMT']; break; }
	}
	return $match_gmt;
}

public static function GetTimezonefromGMT($user_gmt)
{
	$timezone = '';
	$zones_array = array();        
	$timestamp = time();         
	foreach (timezone_identifiers_list() as $key => $zone) {    
		date_default_timezone_set($zone);
		$zones_array[$key]['zone'] = $zone;
		$zones_array[$key]['diff_from_GMT'] = date('P', $timestamp);
		if ($zones_array[$key]['diff_from_GMT']==$user_gmt) { $timezone = $zone; break; }
	}
	return $timezone;
}
public static function Timezone($get_match_timezone,$get_user_gmt)
{
	//$get_match_timezone = "Asia/Karachi";
	$get_match_gmt		= Utility::GetGMTfromTimezone($get_match_timezone);
	//echo $get_match_gmt;die();
	//$get_user_gmt		= "+05:00";
	$get_user_timezone	= Utility::GetTimezonefromGMT($get_user_gmt);
	//echo $get_user_timezone;die();

	$dateTimeZoneMatch = new DateTimeZone($get_match_timezone);
	$dateTimeZoneUser = new DateTimeZone($get_user_timezone);

	$dateTimeMatch = new DateTime("now", $dateTimeZoneMatch);
	$dateTimeUser = new DateTime("now", $dateTimeZoneUser);

	$match_timezone = $dateTimeZoneMatch->getOffset($dateTimeMatch);
	$user_timezone = $dateTimeZoneUser->getOffset($dateTimeUser);

	$match_datetime = gmdate("M d Y - h:i:s A", time()+$match_timezone);
	$user_datetime   = gmdate("M d Y - h:i:s A", time()+$user_timezone);
	//echo "Match Time: ".$match_datetime;
	//echo "<BR>User Time: ".$user_datetime;

	$formated_match_datetime = gmdate("Y-m-d H:i:s", time()+$match_timezone);
	$formated_user_datetime   = gmdate("Y-m-d H:i:s", time()+$user_timezone);

	$can_predict = 0;
	$setting_time = 86400; // 1 hour before match

	/*$time_one = new DateTime($formated_match_datetime);
	$time_two = new DateTime($formated_user_datetime);
	$difference = $time_one->diff($time_two);
	echo "<BR>".$difference->format('%h hours %i minutes %s seconds');*/


	$date1 = $formated_match_datetime;

	$date2 = $formated_user_datetime;

	$diff = abs(strtotime($date2) - strtotime($date1));

	$years = floor($diff / (365*60*60*24)); $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24)); $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

	$hours = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24)/ (60*60));

	$minuts = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60);

	$seconds = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minuts*60));

	//echo "<BR>Time Difference: ".$hours.':'.$minuts.':'.$seconds;

	//echo "<BR>Difference in seconds: ".$diff;

	if ($diff<=$setting_time) $can_predict = 0; // User can not predict now
	else $can_predict = 1; // User can still predict

	//echo "<BR>Can Predict: ".$can_predict;
	$data = array(
				'can_predict' =>  $can_predict);
	return $data;

}

	public static function imageResize($tempImageType,$uploadfile1,$newwidth,$newheight,$width,$height,$uploadedfile,$no_aspect)
	{ 
	  $quality = 9;
	 
	  if ($no_aspect==1) // If  want to resize image in aspect ratio
	  {
		  $original_aspect = $width / $height;
		  $thumb_aspect = $newwidth / $newheight;
			
		  if($original_aspect >= $thumb_aspect) {
			   // If image is wider than thumbnail (in aspect ratio sense)
			   $width_t = $newheight;
			   $height_t = $width / ($height / $newheight);
		  } else {
			   // If the thumbnail is wider than the image
			   $width_t = $newwidth;
			   $height_t = $height / ($width / $newwidth);
		  }
	  }
	  else
	  {
			$width_t = $newwidth;
			$height_t = $newheight;
			$off_x=$off_y=0;
	  }
	  
	 $tmp   = imagecreatetruecolor($width_t,$height_t);
	 $bg 	= imagecolorallocate ( $tmp, 255, 255, 255 );
	 imagefill ( $tmp, 0, 0, $bg );
	 $filename   =  $uploadfile1;
	 $off_x = 0;
	 $off_y = 0;
	  
	 /////////if image is png
	 if($tempImageType == 'image/png')
	 {
		  /*$src    =  imagecreatefrompng($uploadedfile);
		  ////// creating with transparency
		  imagealphablending($tmp, false);
		  imagesavealpha($tmp,true);
		  $transparent = imagecolorallocatealpha($tmp, 255, 255, 255, 127);
		  imagefilledrectangle($tmp, 0, 0, $width_t, $height_t, $transparent);		
		  imagecopyresampled($tmp,$src,$off_x,$off_y,0,0,$width_t,$height_t,$width,$height);
		  imagepng($tmp,$filename,$quality);
		  imagedestroy($src);
	 	imagedestroy($tmp);*/
	 }
	 elseif($tempImageType == 'image/gif')
	 {
		  $src    =  imagecreatefromgif($uploadedfile);
		  imagecopyresampled($tmp,$src,$off_x,$off_y,0,0,$width_t,$height_t,$width,$height);
		  imagepng($tmp,$filename,$quality);
		  imagedestroy($src);
	 	imagedestroy($tmp);
	 }
	 elseif($tempImageType == 'image/jpeg')
	 {
		 ini_set("gd.jpeg_ignore_warning", 1);
		  $src    =  imagecreatefromjpeg($uploadedfile);
		  imagecopyresampled($tmp,$src,$off_x,$off_y,0,0,$width_t,$height_t,$width,$height);
		  imagepng($tmp,$filename,$quality);
		  imagedestroy($src);
	 	imagedestroy($tmp);
	 }
	 elseif($tempImageType == 'image/jpg')
	 {
		  $src    =  imagecreatefromjpeg($uploadedfile);
		  imagecopyresampled($tmp,$src,$off_x,$off_y,0,0,$width_t,$height_t,$width,$height);
		  imagepng($tmp,$filename,$quality);
		  imagedestroy($src);
	 	  imagedestroy($tmp);
	 }
	 elseif($tempImageType == 'image/bmp')
	 {
		  $src    =  imagecreatefromwbmp($uploadedfile);
		  imagecopyresampled($tmp,$src,$off_x,$off_y,0,0,$width_t,$height_t,$width,$height);
		  imagepng($tmp,$filename,$quality);
		  imagedestroy($src);
	 	  imagedestroy($tmp);
	 }
	 else
	 {
		//  $src    =  imagecreatefrompng($uploadedfile);
		//  imagecopyresampled($tmp,$src,$off_x,$off_y,0,0,$width_t,$height_t,$width,$height);
		//  imagepng($tmp,$filename,$quality);
	 }
	  
	 
  }

}
 ?>
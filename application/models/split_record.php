<?php  ini_set('max_execution_time','99999999999999999999999999999999');
class Getrecord {
	public function get_animal()
	{
		$json = json_decode(file_get_contents('http://www.whaleshark.org/rest/org.ecocean.MarkedIndividual'), true);
		if (is_array($json))
		{
			foreach($json as $ind => $values)
			{
				$id = DB::table('animal')->insert_get_id(array('id'		 	 		 => $json[$ind]['individualID'], 
															   'sex' 			 	 => $json[$ind]['sex'],
															   'nick_name' 	 		 => $json[$ind]['nickName'],
															   'nick_namer' 	   	 => $json[$ind]['nickNamer'],
															   'category_id' 		 => '1',
															   'size' 				 => '',
															   'encounter_count' 	 => $json[$ind]['numberEncounters']
												 ));
				//$this->get_encounter($json[$ind]['individualID']);
				/*if($id != 0)
				{		
					$data = array();				
					$key = 'animal_'.$id; //set mem key
					$data['id'] 		    		= $json[$ind]['individualID'];
					$data['sex']					= $json[$ind]['sex'];
					$data['nick_name']				= $json[$ind]['nickName'];
					$data['nick_namer'] 			= $json[$ind]['nickNamer'];
					$data['category_id']			= '1';
					$data['size']					= '';
					$data['encounter_count']		= $json[$ind]['numberEncounters'];
					
					Cache::forever($key, $data); // UPDATE memcache	
						
				}*/
			}
		}
		
		return array('status' => 'success');
	}
	
	public function get_encounter($animal_id)
	{
		$photographerName = '';
		$recordedBy = '';
		$verbatimLocality = '';
		$gpsLatitude = '';
		$gpsLongitude = '';
		$catalogNumber = '';
		$dwcDateAdded = '';
		$doc_path = Config::get('application.doc_path');
		$make_dir = $doc_path.'files/'.$animal_id;		
		if ( !file_exists($make_dir) ) {
			mkdir($make_dir);
		}
		
		$json2 = json_decode(file_get_contents('http://www.whaleshark.org/rest/org.ecocean.Encounter?individualID=="'.$animal_id.'"'), TRUE);
		if (is_array($json2))
		{
			foreach($json2 as $inds => $values)
			{
				if(isset($json2[$inds]['photographerName']))
				{
					$photographerName = $json2[$inds]['photographerName'];
				}
				if(isset($json2[$inds]['recordedBy']))
				{
					$recordedBy = $json2[$inds]['recordedBy'];
				}
				if(isset($json2[$inds]['verbatimLocality']))
				{
					$verbatimLocality = $json2[$inds]['verbatimLocality'];
				}
				if(isset($json2[$inds]['gpsLatitude']))
				{
					$gpsLatitude = $json2[$inds]['gpsLatitude'];
				}
				if(isset($json2[$inds]['gpsLongitude']))
				{
					$gpsLongitude = $json2[$inds]['gpsLongitude'];
				}
				if(isset($json2[$inds]['catalogNumber']))
				{
					$catalogNumber = $json2[$inds]['catalogNumber'];
				}
				if(isset($json2[$inds]['dwcDateAdded']))
				{
					$dwcDateAdded = $json2[$inds]['dwcDateAdded'];
				}
				
				$id = DB::table('encounters')->insert_get_id(array('animal_id'		 	 => $animal_id, 
																 'photographer_name' 	 => $photographerName,
																 'recorded_by' 	   	 	 => $recordedBy,
																 'verbatim_locality' 	 => $verbatimLocality,
																 'latitude' 			 => $gpsLatitude,
																 'longitude' 	 		 => $gpsLongitude,
																 'catalog_number' 	 	 => $catalogNumber,
																 'date_added' 	 		 => $dwcDateAdded
												 ));
				if($id != 0)
				{		
					$data = array();				
					$key = 'encounters_'.$id; //set mem key
					$data['id'] 		    	= $id;
					$data['animal_id'] 			= $animal_id;
					$data['photographer_name']	= $photographerName;
					$data['recorded_by']		= $recordedBy;
					$data['verbatim_locality'] 	= $verbatimLocality;
					$data['latitude']			= $gpsLatitude;
					$data['longitude']			= $gpsLongitude;
					$data['catalog_number']		= $catalogNumber;
					$data['share_count']		= 0;
					$data['like_count']			= 0;
					$data['comment_count']		= 0;
					$data['date_added']			= $dwcDateAdded;
					
					Cache::forever($key, $data); // UPDATE memcache	
					
					$this->get_media($catalogNumber, $id, $animal_id);	
				}
				
				if(isset($values->size) && $values->size != '')
				{
					$data = DB::table('animal')->where('id','=',$value->id)->update( array( 
												'size' 	 	 => $values->size
												 ));
				}					
			}
		}

		//return array('status' => 'success');
	}
	
	public function get_media($catalog_number, $en_id, $animal_id)
	{
		$doc_path = Config::get('application.doc_path');
			
		$make_dir = $doc_path.'files/'.$animal_id.'/'.$en_id.'';		
		if ( !file_exists($make_dir) ) {
			mkdir($make_dir);
		}
		$total_pic = json_decode(file_get_contents('http://www.whaleshark.org/rest/org.ecocean.SinglePhotoVideo?correspondingEncounterNumber=="'.$catalog_number.'"'), true);
		
		if (is_array($total_pic))
		{
			foreach($total_pic as $indd => $value_media)
			{
				if(!isset($total_pic[$indd]['filename']) )
				{
					$total_pic[$indd]['filename'] = '';
				}
				
				$break_name = explode('.', $total_pic[$indd]['filename']);
				if($break_name[1] == 'JPG' || $break_name[1] == 'jpg' || $break_name[1] == 'PNG' || $break_name[1] == 'png' || $break_name[1] == 'gif' || $break_name[1] == 'jpeg')
				{
					$id = DB::table('media')->insert_get_id(array('encounter_id' 	 => $en_id,
																 'image_name' 	   	 => $total_pic[$indd]['filename'] 
													 ));
					if($id != 0)
					{
						$data = array();						
						$key = 'media_'.$id; //set mem key
						$data['id'] 		    	= $id;
						$data['encounter_id'] 		= $en_id;
						$data['image_name']			= $total_pic[$indd]['filename'] ;
						
						Cache::forever($key, $data); // UPDATE memcache		
					}
													 
					$dir = $doc_path.'files/'.$animal_id.'/'.$en_id.'/'.$total_pic[$indd]['filename'];
						
					$final_name = str_replace(" ","%20",$total_pic[$indd]['filename']);								 
					$pic = 'http://www.whaleshark.org/shepherd_data_dir/encounters/'.$catalog_number.'/'.$final_name.'';
					$dir2 = $doc_path.'files/'.$animal_id.'/'.$en_id.'/thumb-'.$total_pic[$indd]['filename'];
					copy ($pic, $dir);
					copy ($pic,$dir2);
									
					$size    		= getimagesize($pic);
					$type    		= $size['mime'];
					$width    		= $size[0];
					$height   		= $size[1];
					
					utility::imageResize($type,$dir2,120,80,$width,$height,$dir2,0);
				}
			}	
		}

		//return array('status' => 'success');
	}
	
		

}
?>
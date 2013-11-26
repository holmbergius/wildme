<?php  ini_set('max_execution_time','9999999999999999999999999');
class Cronjob {
	public function get_encounterCheck($category_id)
	{
		$category  		= new Category;
		$cat_data 		= $category->get_category($category_id);
		$cat_data		= json_encode($cat_data);
		$cat_data		= json_decode($cat_data, TRUE);
		$api_url		= $cat_data['record']['api_url'];
		$status 		= 'error';
		
		if($api_url != '')
		{
			$json = json_decode(file_get_contents($api_url.'/rest/org.ecocean.MarkedIndividual'));

			if (is_array($json))
			{
				foreach($json as $ind => $values)
				{
					$checkdbCount 	=	DB::table('animal')->where('id','=',$values->individualID)->count('id');
					
					if($checkdbCount > 0)
					{
						$checkdb 	=	DB::table('animal')->where('id','=',$values->individualID)->get(array('encounter_count'));
						
						if($values->numberEncounters > $checkdb[0]->encounter_count)
						{
							//update count here
							DB::table('animal')->where('id','=',$values->individualID)->update( array('encounter_count' => $values->numberEncounters ));
																 
							$json2 = json_decode(file_get_contents($api_url.'/rest/org.ecocean.Encounter?individualID=="'.$values->individualID.'"'));
							if (is_array($json2))
							{
								foreach($json2 as $inds => $values)
								{
							$photographerName = '';
							$recordedBy = '';
							$verbatimLocality = '';
							$gpsLatitude = '';
							$gpsLongitude = '';
							$catalogNumber = '';
							$dwcDateAdded = '';
							$genus = '';
							
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
								$json2[$inds]['gpsLatitude'] = str_replace("&deg;","", $json2[$inds]['gpsLatitude']);
								if(is_numeric($json2[$inds]['gpsLatitude']))
								{
									$gpsLatitude = $json2[$inds]['gpsLatitude'];
								}
								else if(isset($json2[$inds]['decimalLatitude']))
								{
									if(is_numeric($json2[$inds]['decimalLatitude']))
									{
										$gpsLatitude = $json2[$inds]['decimalLatitude'];
									} 
								}
							}
							if(isset($json2[$inds]['gpsLongitude']))
							{
								$json2[$inds]['gpsLongitude'] = str_replace("&deg;","", $json2[$inds]['gpsLongitude']);
								if(is_numeric($json2[$inds]['gpsLongitude']))
								{
									$gpsLongitude = $json2[$inds]['gpsLongitude'];
								}
								else if(isset($json2[$inds]['decimalLongitude']))
								{
									if(is_numeric($json2[$inds]['decimalLongitude']))
									{
										$gpsLongitude = $json2[$inds]['decimalLongitude'];
									} 
								}
							}
							if(isset($json2[$inds]['catalogNumber']))
							{
								$catalogNumber = $json2[$inds]['catalogNumber'];
							}
							if(isset($json2[$inds]['specificEpithet']))
							{
								$specific_epithet = $json2[$inds]['specificEpithet'];
							}
							if(isset($json2[$inds]['genus']))
							{
								$genus = $json2[$inds]['genus'];
							}
							if(isset($json2[$inds]['modified']))
							{
								$modified = $json2[$inds]['modified'];
							}
							if(isset($json2[$inds]['dwcDateAdded']))
							{
								$dwcDateAdded = $json2[$inds]['dwcDateAdded'];
								$dwcDateAdded = date("Y-m-d H:i:s", strtotime($dwcDateAdded));
							}
							
							$sql_encounter   = DB::query("SELECT `id`, `modified` FROM `encounters` where catalog_number = '". $catalogNumber."' LIMIT 1 ");	
							$j=0;
							$len = count($sql_encounter);//total length
							if ($len>0) // record exists
							{
								$id = $sql_encounter[0]->id;
								$ida = DB::table('encounters')->where('id','=',$id)->update(array('animal_id' => $animal_id, 
																				 'photographer_name' 	 => $photographerName,
																				 'recorded_by' 	   	 	 => $recordedBy,
																				 'verbatim_locality' 	 => $verbatimLocality,
																				 'latitude' 			 => $gpsLatitude,
																				 'longitude' 	 		 => $gpsLongitude,
																				 'catalog_number' 	 	 => $catalogNumber,
																				 'date_added' 	 		 => $dwcDateAdded,
																				 'genus' 	 		 	 => $genus,
																				 'modified' 	 		 => $modified,
																				 'specific_epithet' 	 => $specific_epithet,
																				 'modified' 	 		 => $modified
																 ));
																 $key = 'encounters_'.$id; //set mem key
																 Cache::forget($key); // UPDATE memcache	
									
									$make_dir_en = $doc_path.'files/'.$animal_id.'/'.$id;		
				
									if ( !file_exists($make_dir_en) ) {
										
										mkdir($make_dir_en);
									}
													
							}
							else // Insert New
							{
				
								$id = DB::table('encounters')->insert_get_id(array('animal_id'		 	 => $animal_id, 
																				 'photographer_name' 	 => $photographerName,
																				 'recorded_by' 	   	 	 => $recordedBy,
																				 'verbatim_locality' 	 => $verbatimLocality,
																				 'latitude' 			 => $gpsLatitude,
																				 'longitude' 	 		 => $gpsLongitude,
																				 'catalog_number' 	 	 => $catalogNumber,
																				 'date_added' 	 		 => $dwcDateAdded,
																				 'genus' 	 		 	 => $genus,
																				 'modified' 	 		 => $modified,
																				 'specific_epithet' 	 => $specific_epithet,
																				 'modified' 	 		 => $modified
																 ));
																 
								$make_dir_en = $doc_path.'files/'.$animal_id.'/'.$id;		
				
								if ( !file_exists($make_dir_en) ) {
									
									mkdir($make_dir_en);
								}
							}
							
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
								$data['genus']				= $genus;								
								$data['specific_epithet']	= $specific_epithet;
								$data['modified']			= $modified;
								
								Cache::forever($key, $data); // UPDATE memcache	
								
								//$this->get_media($catalogNumber, $id, $animal_id);	
								$class = new Getrecord;
								$class->get_media($catalogNumber, $id,  $animal_id, $api_url, $check_ind, $image_url);
								
								if(isset($json2[$inds]['size']) )
								{
									if($json2[$inds]['size'] != '')
									{
										$data = DB::table('animal')->where('id','=',$animal_id)->update( array( 
																'size' 	 	 => $json2[$inds]['size']
																 ));
									}
								}	
							}
						}
							}
						}
					}			 
				}
			}
		}
		return array('status' => $status);
	}
	
	
	
	

}
?>
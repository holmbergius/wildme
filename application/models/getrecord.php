<?php  ini_set('max_execution_time','9999999999999999999999999');

class Getrecord {
	
	public $curr_folder_name = '';
	
	
	public function get_all_animal()
	{
		
		$sql   = DB::query("SELECT `id` FROM `category` ");	
		
		foreach($sql as $ind => $value)
		{
			$this->get_animal($value->id);
			$status= 'success'.$value->id;
		}
		
		$this->get_profile_photo();
		return array('status' => $status,'msg' => 'All animals done');
	}
	
	public function get_animal($category_id)
	{
		$category  		= new Category;
		$cat_data 		= $category->get_category($category_id);
		$cat_data		= json_encode($cat_data);
		$cat_data		= json_decode($cat_data, TRUE);
		$api_url		= $cat_data['record']['api_url']; 
		$id_prefix		= $cat_data['record']['id_prefix']; 
		
		$folder_prefix = str_replace('-','',$id_prefix) ;
		
		$folder_name = DB::first("SELECT `name` FROM `folder` where is_active ='Yes' limit 1");
		$this->curr_folder_name = $folder_name->name.'/'.$folder_prefix.'/';
		
		$status 		= 'error';
		if($api_url	!= '')
		{
			$status 		= 'success';
			$json 	= file_get_contents($api_url.'rest/org.ecocean.MarkedIndividual');
			$var 	= preg_replace("/[\r\n]+/", " ", $json);
			$var 	= utf8_encode($var);
			$json 	= json_decode($var, true);
					
			if (is_array($json))
			{
				foreach($json as $ind => $values)
				{
					if(isset($json[$ind]['nickName']))
					{
						if($json[$ind]['nickName'] ==  'Unassigned')
						{
							$json[$ind]['nickName'] = '';
						}						
					}
					else
					{
						$json[$ind]['nickName'] = '';
					}
					if(isset($json[$ind]['nickNamer']))
					{
						if($json[$ind]['nickNamer'] ==  'Unknown')
						{
							$json[$ind]['nickNamer'] = '';
						}
					}
					else
					{
						$json[$ind]['nickNamer'] = '';
					}
					
					$sql   	= DB::first("SELECT count(id) as total FROM `animal` WHERE `animal_id` = '".$json[$ind]['individualID']."' and `category_id` = '".$category_id."' ");
					
					//$id_prefix.
					if($sql->total == 0)
					{
						$id = DB::table('animal')->insert_get_id(array('label'	 	 		 => $id_prefix.$json[$ind]['individualID'], 
																	   'animal_id' 	 		 => $json[$ind]['individualID'], 
																	   'sex' 			 	 => $json[$ind]['sex'],
																	   'nick_name' 	 		 => $json[$ind]['nickName'],
																	   'nick_namer' 	   	 => $json[$ind]['nickNamer'],
																	   'category_id' 		 => $category_id,
																	   'size' 				 => '',
																	   'encounter_count' 	 => $json[$ind]['numberEncounters']
														 ));
					}
				}
				$this->get_encounter($category_id);
			}
		}
		return array('status' => $status,'msg' => 'MarkedIndividual');
	}
	
	public function get_encounter($category_id)
	{
		$category  		= new Category;
		$cat_data 		= $category->get_category($category_id);
		$cat_data		= json_encode($cat_data);
		$cat_data		= json_decode($cat_data, TRUE);
		$api_url		= $cat_data['record']['api_url'];
		$image_url		= $cat_data['record']['image_url'];
		$check_ind		= 0;
		$status 		= 'error';
		
		if($api_url	!= '')
		{
			$status = 'success';
			$photographerName = '';
			$recordedBy = '';
			$verbatimLocality = '';
			$gpsLatitude = '';
			$gpsLongitude = '';
			$catalogNumber = '';
			$dwcDateAdded = '';
			$genus = '';
			$specific_epithet = '';
			$modified = '';
			
			$doc_path = Config::get('application.doc_path');
			
			$sql   = DB::query("SELECT `id`,`animal_id` FROM `animal` where category_id = '".$category_id."' ");	
			foreach($sql as $ind => $value_animal)
			{
				$animal_id = $value_animal->id;
				$old_animal_id = $value_animal->animal_id;
				$specific_epithet = '';
				$modified = '';
			
				$make_dir = $doc_path.$this->curr_folder_name.$animal_id;		
				
				if ( !file_exists($make_dir) ) {
					
					if(mkdir($make_dir)){
					
					}else{
						//call exception
						$this->create_new_dir();
					}
				}
					$temp 	= file_get_contents($api_url.'rest/org.ecocean.Encounter?individualID=="'.$old_animal_id.'"');
					$var 	= preg_replace("/[\r\n]+/", " ", $temp);
					$var 	= utf8_encode($var);
					$json2 	= json_decode($var, true);

					/*$ch = curl_init('http://www.whaleshark.org/rest/org.ecocean.Encounter?individualID=="'.$animal_id.'"');
					curl_setopt($ch, CURLOPT_NOBODY, true);
					curl_exec($ch);
					$retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
					
					curl_close($ch);
					do{
						$json2 = json_decode(file_get_contents('http://www.whaleshark.org/rest/org.ecocean.Encounter?individualID=="'.$animal_id.'"'), TRUE);
						$ch = curl_init('http://www.whaleshark.org/rest/org.ecocean.Encounter?individualID=="'.$animal_id.'"');
						curl_setopt($ch, CURLOPT_NOBODY, true);
						curl_exec($ch);
						$retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
						// $retcode > 400 -> not found, $retcode = 200, found.
						curl_close($ch);
					}
					while($retcode != '200');*/
					if (is_array($json2))
					{
						$k=0;
						foreach($json2 as $inds => $values)
						{
							if($k == 0)
							{
								$check_ind = 1;
							}
							$k++;
							
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
									
									$make_dir_en = $doc_path.$this->curr_folder_name.$animal_id.'/'.$id;		
				
									if ( !file_exists($make_dir_en) ) {
										
											if(mkdir($make_dir_en)){
											}else{
												//call exception
												$this->create_new_dir();
											}
									}
									$make_dir_encount = "http://fb.wildme.org/wildme/public/".$this->curr_folder_name."encounters/".$catalogNumber;
									if ( !file_exists($make_dir_encount) ) {
										
										if(mkdir($make_dir_encount)){
											}else{
												//call exception
												$this->create_new_dir();
											}
										
									}
									
									$sql_encounter_med   = DB::first("SELECT COUNT(id) as total FROM `media` where encounter_id = ".$id." ");
									if($sql_encounter_med->total == 0)
									{
										$this->get_media($catalogNumber, $id,  $animal_id, $api_url, $check_ind, $image_url);
										//die($animal_id);
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
																 
								$make_dir_en = $doc_path.$this->curr_folder_name.$animal_id.'/'.$id;		
				
								if ( !file_exists($make_dir_en) ) {
									
									//File::makeDirectory($make_dir_en, 0777, false, true);
									if(mkdir($make_dir_en)){
											}else{
												//call exception
												$this->create_new_dir();
											}
																		
								}
								
								$make_dir_encount = "http://fb.wildme.org/wildme/public/".$this->curr_folder_name."encounters/".$catalogNumber;
								
								if ( !file_exists($make_dir_encount) ) {
									
									if(mkdir($make_dir_encount)){
											}else{
												//call exception
												$this->create_new_dir();
											}
								}
								
								//add post on FB
								$datas = array();
								$datas['animal_id'] =  $animal_id;
								$this->facebook_post($datas);
 
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
								$this->get_media($catalogNumber, $id,  $animal_id, $api_url, $check_ind, $image_url);
								
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
			$sql_friends   = DB::first("SELECT `friends_check` FROM `category` WHERE `id` = '".$category_id."'  ");	
			if($sql_friends->friends_check != 1)
			{
				$this->get_occurrence($category_id);
				$sql_friends   = DB::query("UPDATE category set `friends_check` = 1  WHERE `id` = '".$category_id."'  ");	
			}
		}
		return array('status' => $status,'msg' => 'encounter');
	}
	
	public function get_all_media($category_id)
	{
		$category  		= new Category;
		$cat_data 		= $category->get_category($category_id);
		$cat_data		= json_encode($cat_data);
		$cat_data		= json_decode($cat_data, TRUE);
		$api_url		= $cat_data['record']['api_url'];
		$image_url		= $cat_data['record']['image_url'];
		$status 		= 'error';
		$check_ind		= 0;
		if($api_url	!= '')
		{
			
			$sql   = DB::query("SELECT `id` FROM `animal` WHERE `media` = '0' AND category_id = '".$category_id."'  ");	
			foreach($sql as $ind => $value_animal)
			{
				$sql2   = DB::query("SELECT `id`, `catalog_number` FROM `encounters` where animal_id = '". $value_animal->id."'  ");	
				$j=0;
				$len = count($sql2);//total length
				foreach($sql2 as $inds => $values)
				{
					if ($inds == $len - 1) {
						$data = DB::table('animal')->where('id','=',$value_animal->id)->update( array( 
															'media' 	 	 => '1'
															 ));
						if($j == 0)
						{
							$check_ind = 1;
							//$pic_data = json_encode($pic_data);
							//$pic_data = json_decode($pic_data , TRUE);
							
							/*$data = DB::table('animal')->where('id','=',$value_animal->id)->update( array( 
															'profile_pic' 	 => $pic_data['pic']
															 ));*/
						}
						$j++;
					}
					
					$pic_data = $this->get_media($values->catalog_number, $values->id,  $value_animal->id, $api_url, $check_ind, $image_url);
					$status = 'success';
				}
			}
		}
		return array('status' => $status);
	}
	
	public function get_media($catalog_number, $en_id, $animal_id, $api_url, $check_ind, $image_url)
	{
		$first_pic = '';
		$doc_path = Config::get('application.doc_path');
		$web_url = Config::get('application.web_url');
			
		$make_dir = $doc_path.$this->curr_folder_name.$animal_id.'/'.$en_id.'';		
		if ( !file_exists($make_dir) ) {
			
			if(mkdir($make_dir)){
			}else{
				//call exception
				$this->create_new_dir();
			}
											
			
		}
		$total_pic = file_get_contents($api_url.'rest/org.ecocean.SinglePhotoVideo?correspondingEncounterNumber=="'.$catalog_number.'"');
		
		$var 		= preg_replace("/[\r\n]+/", " ", $total_pic);
		$var 		= utf8_encode($var);
		$total_pic 	= json_decode($var, true);
					
		if (is_array($total_pic))
		{
			$i=0;
			
			foreach($total_pic as $indd => $value_media)
			{
				if(!isset($total_pic[$indd]['filename']) )
				{
					$total_pic[$indd]['filename'] = '';
				}
				
				$break_name = explode('.', $total_pic[$indd]['filename']);
				$index_of_ext	= count ($break_name)-1;
				if($break_name[$index_of_ext] == 'JPG' || $break_name[$index_of_ext] == 'jpg' || $break_name[$index_of_ext] == 'PNG' || $break_name[$index_of_ext] == 'png' || $break_name[$index_of_ext] == 'gif' || $break_name[$index_of_ext] == 'jpeg' || $break_name[$index_of_ext] == 'bmp')
				{
					//incase of saving pic.
					$make_dir2 = $doc_path.$this->curr_folder_name.'encounters/'.$catalog_number.'';		
					if ( !file_exists($make_dir2) ) {
						if(mkdir($make_dir2)){
						}else{
							//call exception
							$this->create_new_dir();
						}
					}								 
					$dir = $doc_path.$this->curr_folder_name.'encounters/'.$catalog_number.'/'.$total_pic[$indd]['filename'];
					//incase of saving pic.
					
					$final_name = str_replace(" ","%20",$total_pic[$indd]['filename']);
					
					$data_dir 	= 'shepherd_data_dir';
					if($api_url == 'http://www.ecoceanusa.org/polarbearlibrary/')
					{
						$api_url = 'http://www.ecoceanusa.org/polar_data_dir/';
						$data_dir 	= '';
					}
					if($api_url == 'http://www.whaleshark.org/')
					{
						//$pic = 'http://fb.wildme.org/wildme/public/files/encounters/'.$catalog_number.'/'.$final_name.'';
						$pic = $image_url.'encounters/'.$catalog_number.'/'.$final_name.'';
					}
					else
					{
						$pic = $image_url.'encounters/'.$catalog_number.'/'.$final_name.'';
					}
							 
					//$pic = 'http://fb.wildme.org/wildme/public/files/encounters/'.$catalog_number.'/'.$final_name.'';
					$dir2 = $doc_path.$this->curr_folder_name.$animal_id.'/'.$en_id.'/thumb-'.$total_pic[$indd]['filename'];
					
					$ch = curl_init($pic);
					curl_setopt($ch, CURLOPT_NOBODY, true);
					curl_exec($ch);
					$retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
					// $retcode > 400 -> not found, $retcode = 200, found.
					curl_close($ch);
					if($retcode == '200')
					{
						$our_path = "http://fb.wildme.org/wildme/public/".$this->curr_folder_name."encounters/".$catalog_number."/".$final_name;
						//for adding profile pic.
						if($check_ind == '1')
						{
							$data = DB::table('animal')->where('id','=',$animal_id)->update( array( 
																				'profile_pic' 	 => $our_path
																				 ));
						}
						$check_path_thumb  = $this->curr_folder_name.$animal_id.'/'.$en_id.'/thumb-'.$total_pic[$indd]['filename'];
						
						//$count_sql_check = DB::first("SELECT count(id) as total FROM `media` where encounter_id = '". $en_id."'   AND `thumb_path` = '".$check_path_thumb."'  ");
						
						$count_sql_check = DB::table('media')->where('encounter_id', '=', $en_id)->where('thumb_path', '=', $check_path_thumb)->count();
						
						if($count_sql_check == 0)
						{
							$id = DB::table('media')->insert_get_id(array('encounter_id' 	 => $en_id,
																		 'image_name' 	   	 => $our_path,
																		 'thumb_path' 	   	 => $this->curr_folder_name.$animal_id.'/'.$en_id.'/thumb-'.$total_pic[$indd]['filename']
														 ));
														 
							if($id != 0)
							{
								$data = array();						
								$key = 'media_'.$id; //set mem key
								$data['id'] 		    	= $id;
								$data['encounter_id'] 		= $en_id;
								$data['image_name']			= $our_path;
								$data['thumb_path']			= $this->curr_folder_name.$animal_id.'/'.$en_id.'/thumb-'.$total_pic[$indd]['filename'];
								$data['is_valid']			= 0;
								
								Cache::forever($key, $data); // UPDATE memcache
								
								//incase of saving full pic.
								
								if ( !file_exists($dir) ) {
									copy ($pic, $dir);
								}
								//copy ($pic, $dir);
								//incase of saving thumb pic.
								if ( !file_exists($dir2) ) {
									copy ($pic,$dir2);
									$size    		= getimagesize($pic);
									$type    		= $size['mime'];
									$width    		= $size[0];
									$height   		= $size[1];
									
									utility::imageResize($type,$dir2,120,80,$width,$height,$dir2,0);	
								}
								if($i==0)
								{
									$first_pic = $pic;
								}
								$i++;
							}
						
						}
					}
					else
					{
						$count_sql_check = DB::table('media')->where('encounter_id', '=', $en_id)->where('image_name', '=', $pic)->count();
						if($count_sql_check == 0)
						{
							$id = DB::table('media')->insert_get_id(array('encounter_id' 	 => $en_id,
																	 'image_name' 	   	 	 => $pic,
																	 'thumb_path' 	   		 => '',
																	 'is_valid' 	   		 =>	0 
													 ));
						}							 
					}
				}
			}	
		}
		else
		{
			//die($api_url.'rest/org.ecocean.SinglePhotoVideo?correspondingEncounterNumber=="'.$catalog_number.'"');
		}
		return array('pic'=>$first_pic );
		
	}
	
	public function get_occurrence($category_id)
	{
		$status = 'error';
		$category  		= new Category;
		$cat_data 		= $category->get_category($category_id);
		$cat_data		= json_encode($cat_data);
		$cat_data		= json_decode($cat_data, TRUE);
		$api_url		= $cat_data['record']['api_url'];
		$id_prefix		= $cat_data['record']['id_prefix'];
		
		if($category_id != 1)
		{
			
			$json = json_decode(file_get_contents($api_url.'rest/org.ecocean.Occurrence'));

			if (is_array($json))
			{
			foreach($json as $ind => $values)
			{
				if( $json[$ind]->occurrenceID == 'H-035, H-058')
				{
					$json[$ind]->occurrenceID = 'H-035';
				}
				$json2 = json_decode(file_get_contents($api_url.'rest/org.ecocean.Encounter/?occurrenceID=="'.$json[$ind]->occurrenceID.'"'));
				if (is_array($json2))
				{
					$marked_ids 	= array();
					foreach($json2 as $inds => $values2)
					{
						if($json2[$inds]->individualID !='Unassigned' && $json2[$inds]->individualID !='')
						{
							$marked_ids[] 	= $json2[$inds]->individualID;
						}
						
					}
					$marked_ids = array_values(array_unique($marked_ids));
					$count_ids  = count($marked_ids);
					foreach($marked_ids as $in => $mark_values)
					{
						for($i = 0; $i < $count_ids; $i++ )
						{
							
							if($marked_ids[$in] != $marked_ids[$i])
							{
					$animal_id1 = DB::first("SELECT `id` FROM `animal` where `animal_id` ='".$marked_ids[$in]."' and category_id ='".$category_id."' ");
					$animal_id2 = DB::first("SELECT `id` FROM `animal` where `animal_id` ='".$marked_ids[$i]."' and category_id ='".$category_id."' ");
							$animal_id1 = $animal_id1->id;
							$animal_id2 = $animal_id2->id;
							
							$checkdb = DB::table('animal_friend')->where('animal_id','=',$animal_id1)->where('friend_id','=',$animal_id2)->count();
								if($checkdb == 0 )
								{
									$id = DB::table('animal_friend')->insert_get_id(array('animal_id'		 => $animal_id1, 
																						  'friend_id' 		 => $animal_id2,
																						  'count' 	 		 => '1'
																	));
									if($id != 0)
									{
										$data = array();						
										$key = 'animal_friend_'.$id; //set mem key
										$data['id'] 		    	= $id;
										$data['animal_id'] 			= $animal_id1;
										$data['friend_id']			= $animal_id2;
										$data['count']				= 1;
										
										Cache::forever($key, $data); // UPDATE memcache		
									}
									
								}
								else
								{
									DB::table('animal_friend')->where('animal_id','=',$animal_id1)->where('friend_id','=',$animal_id2)->increment('count');
								}
							}
						}

					}
					$status = 'success';
				}
			 
			}
		}
		}
		return array('status' => $status,'msg' => 'occurrence');
	}
	
	public function facebook_post($param)
 	{
		
		$Animal  		= new Animal;
		$data2 			= $Animal->get_animal($param['animal_id']);
		$data2			= json_decode(json_encode($data2), TRUE);
		$animal_details	= $data2['record'];
		
		$sql   = DB::query("SELECT `uid` FROM `adoptor` where `animal_id` = '".$param['animal_id']."' group by `uid` where `status` = 'Active' and `user_type` = 'application' ");	
		foreach($sql as $ind => $value)
		{
		
		$param['fb_id']=  $value->uid;
 		
		$param['title'] = 'Checkout on Wild Me! Add an animal to your social network!';
	 	
		$param['description'] = 'Checkout activities on WildMe and explore more animals and find out what they are upto.. A fun and interactive way to follow animals.';
 		
		
		$param['picture'] = $animal_details['profile_pic']; //'http://fb.wildme.org/wildme/public/files/encounters/18112005155826/23_5_05_2_1.jpg';

		$param['link'] = Config::get('application.web_url').'profile/'.$animal_details['label'];

		require_once 'application/libraries/facebook.php';
		
		
		$fb_id = $param['fb_id'];
		$title = $param['title'];
		$link = $param['link'];
		$picture = $param['picture'];
		$description = $param['description'];
		
        $app_id = Config::get("application.facebook_app_id");
        $app_secret = Config::get("application.facebook_app_secret");
        
		  $config = array();
		  $config['appId'] = $app_id;
		  $config['secret'] = $app_secret;
		  $facebook = new Facebook($config); 
		  try{
		  
		   $access_token = $facebook->getAccessToken();
		   $facebook->setAccessToken($access_token);
		  
		   $attachment =  array(   'access_token'  => $access_token,                        
		///        'message'          => $comment,
				'name'          => $title,
				'link'          => $link,
				'picture'       => $picture,
				'description'   => $description,
			   );
		  
		   $publish = $facebook->api('/'.$fb_id.'/feed', 'post', $attachment);
		  // return $publish;
		  }
		  catch (Exception $e)
		  {
		   //return $e;
		  }
		  
		}
			
		  return array('status'=>'success');
	 }
	 	
	public function get_profile_photo()
	{
		$status = 'error';
		
		$sql   = DB::query("SELECT `id` FROM `animal` where profile_pic ='' ");	
		foreach($sql as $inds => $values)
		{
			$animal_id = $values->id;
			$sql2   = DB::first("SELECT `image_name` FROM `media` where encounter_id IN(select id from encounters where animal_id = '".$values->id."') AND `image_name` <> '' limit 1");	
			if($sql2)
			{
				//$sql3   = DB::query("UPDATE `animal` SET profile_pic = '".$sql2->image_name."' where id ='".$values->id."' ");
				
				$sql3 = DB::table('animal')->where('id','=',$values->id)->update( array( 
																'profile_pic' 	 	 => $sql2->image_name
																 ));	
				$status = 'success';
			}
		}
		
		return array('status' => $status, 'id'=>$animal_id);
	}
	
	public function create_new_dir(){
		
		$folder_name = DB::first("SELECT `name`,`id` FROM `folder` where is_active ='Yes' limit 1");
		
		$curr_date = date('Y-m-d h:i:s');
		
		DB::table('folder')->where('id','=',$folder_name->id)->update(array('is_active' => 'No', 
																	  'end_date' 	 => $curr_date			
																	 ));
																	 
		$id = DB::table('folder')->insert_get_id(array('is_active'	 	 		 => 'Yes', 
													   'name'	 	 			 => '', 
													   'start_date' 	 		 => $curr_date
													 ));
													 
		$new_name  = 'files'.$id;
		DB::table('folder')->where('id','=',$id)->update(array('name' =>$new_name
														));
														 
		mail('meran@cygnismedia.com','wild me cron job folder limit exceed','wild me cron job folder limit exceed');
	
	}
}
?>

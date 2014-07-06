<?php
class Reseacher 
{
	public $cat_arr = array();
	
 	/*******************************Get All Researchers******************************/
 	public function get_all_researchers($param)
	{
		//return false;
		$sortby			 = Utility::mysql_query_string($param['sortby']);
		$orderby 		 = Utility::mysql_query_string($param['orderby']);
		$offset 		 = Utility::mysql_query_string($param['offset']);
		$limit 		 	 = Utility::mysql_query_string($param['limit']);
		$animal_id 		 = Utility::mysql_query_string($param['animal_id']);
	

		$count  = DB::first("select COUNT(submitter_id) as total from `animal_researchers` where `animal_id` = '".$animal_id."' ");
		$data   = array();
 		$status = 'error';
 
		if($count->total>0)
		{
			$sql   = "select submitter_id from animal_researchers where `animal_id` = '".$animal_id."' order by $sortby $orderby limit $offset,$limit ";
			$researchers = DB::query($sql);

			foreach($researchers as $ind => $value)
			{
				$researcherData	= $this->get_single_researcher($value->submitter_id);	
				$temp 			= json_encode($researcherData);
				$researcherData	= json_decode($temp, true);		
				$data[]  		= $researcherData['record'];
				$status = 'success';
			}	
		}
		
			$all_cat  = DB::query("select * from category");		
			foreach($all_cat as $cat)
			{
				$this->cat_arr[$cat->id] = $cat;
				
			}			
				$category_id = DB::first("SELECT  COUNT(category_id) as total,`category_id`  FROM `animal` where id ='".$animal_id."'");
				
				if($category_id->total > 0 ){
					
					$researcherData = $this->get_researcherData($category_id->category_id,$animal_id);
					$temp 			= json_encode($researcherData);
					$researcherData	= json_decode($temp, true);		
					$data	 		= $researcherData['record'];
					$status2 		= $researcherData['status'];
				}
		
		return array('records' => $data, 'totalrecords' => $count->total,'status' => $status);
	}

	/*******************************Get Single Reseacher******************************/

	public function get_single_researcher($id)
	{
		$key  = 'researcher_'.$id;
		$data = Cache::get($key);
		if($data==false || $data==NULL)
		{
		   $count = DB::first("select COUNT(submitter_id) as total from `researchers` where `submitter_id` = '".$id."' Limit 1;");

		   if($count->total>0)
		   {
				$data = DB::first("select * from `researchers` where `submitter_id` = '".$id."' Limit 1; ");
				Cache::forever($key, $data);
		   }
		   else
		   {
				return false;
				 
		   } 
		}
		return array('record' => $data,'totalrecords' => '1','status' => 'success');
	}// get single data 
	
	//Get researcher data from external resourse
	public function get_researcherData($category_id,$animal_id)
	{
		
		$submitters = array();
		$data   = '';
 		$status = 'error';
		//$category  		= new Category;
		$cat_data 		= $this->cat_arr[$category_id];
		$api_url		= $cat_data->api_url;
		$image_url		= $cat_data->image_url;
		
		$doc_path = Config::get('application.doc_path').'files/researchers/';	
		if($api_url	!= '')
		{
			
			$temp 	= file_get_contents($api_url.'rest/org.ecocean.Encounter?individualID=="'.$animal_id.'"');
			$var 	= preg_replace("/[\r\n]+/", " ", $temp);
			$var 	= utf8_encode($var);
			$json2 	= json_decode($var, true);
			
			if (is_array($json2))
				{
					foreach($json2 as $inds => $values)
					{		
						//Get Researcher details (Start Here)
						$researcherName = '';
						$researcherImage = '';
						$researcherProject = '';
						$researcherOrganization = '';
						$researcherStatement = '';
						$imageJson = '';
						if(isset($json2[$inds]['submitterID']) && !empty($json2[$inds]['submitterID']) && $json2[$inds]['submitterID'] != 'N/A' )
						{	
							
							if(in_array($json2[$inds]['submitterID'], $submitters))
								continue;	
							
							$submitterID = strip_tags($json2[$inds]['submitterID']);
							$sql_researcher_check   = DB::first("SELECT COUNT(id) as total FROM `researchers` where `submitter_id` = '".$submitterID."' ");
							
								$researchUrl 	= @file_get_contents($api_url.'rest/org.ecocean.User/'.$submitterID);
								$imageUrl 	= @file_get_contents($api_url.'rest/org.ecocean.SinglePhotoVideo?correspondingUsername=="'.$submitterID.'"');
								$submitters[] = $json2[$inds]['submitterID'];
								if($researchUrl == false)
								{
									continue;
								}
								if($imageUrl == false)
								{
									continue;
								}
								$researchVar 	= preg_replace("/[\r\n]+/", " ", $researchUrl);
								$researchVar 	= utf8_encode($researchVar);
								$researchJson 	= json_decode($researchVar, true);
								
								$imageVar 	= preg_replace("/[\r\n]+/", " ", $imageUrl);
								$imageVar 	= utf8_encode($imageVar);
								$imageJson 	= json_decode($imageVar, true);
							
								//print_r($image_url.'users/'.$submitterID.'/'.$imageJson[0]['filename']);die;
																
								if(isset($imageJson[0]['filename']) && !empty($imageJson[0]['filename']))
								{
									
									$source_url = $image_url.'users/'.$submitterID.'/'.$imageJson[0]['filename'];
									$doc_path2 = $doc_path.$imageJson[0]['filename'];	
									if(!@copy($source_url,$doc_path2))
									{
										$errors= error_get_last();
										echo "COPY ERROR: ".$errors['type'];
										echo "<br />\n".$errors['message'];
									} else {
										$researcherImage = $imageJson[0]['filename'];
									}
									
									
								}
								if(isset($researchJson['userProject']))
								{
									$researcherProject = $researchJson['userProject'];
								}
								if(isset($researchJson['affiliation']))
								{
									$researcherOrganization = $researchJson['affiliation'];
								}
								if(isset($researchJson['userStatement']))
								{
									$researcherStatement = $researchJson['userStatement'];
								}
								if(isset($researchJson['fullName']))
								{
									$researcherName = $researchJson['fullName'];
								}
									//insert record in db 
								if($sql_researcher_check->total == 0 && $researcherName != '')
								{
									$researcherId = DB::table('researchers')->insert_get_id(array('submitter_id' => $submitterID, 
																			  'name' 	  	 => $researcherName,
																			  'image' 	 	 => $researcherImage,
																			  'project' 	 => $researcherProject,
																			  'organization'  => $researcherOrganization,
																			  'user_statement' => $researcherStatement
																			  ));
									
									$key 					= 'reseacher_'.$submitterID; //set mem key
									$rData['id'] 			= $researcherId;
									$rData['name'] 			= $researcherName;
									$rData['image'] 		= $researcherImage;
									$rData['project'] 		= $researcherProject;
									$rData['organization'] 	= $researcherOrganization;
									$rData['user_statement'] = $researcherStatement;
									
									Cache::forever($key, $rData); // UPDATE memcache
				
								}//fullName
								else if($sql_researcher_check->total > 0 && $researcherName != '')
								{
									$researcherId = DB::table('researchers')->where('submitter_id','=',$submitterID)->update(array(
																			  'name' 	  	 => $researcherName,
																			  'image' 	 	 => $researcherImage,
																			  'project' 	 => $researcherProject,
																			  'organization'  => $researcherOrganization,
																			  'user_statement' => $researcherStatement
																			  ));
									
									$key 					= 'reseacher_'.$submitterID; //set mem key
									
									Cache::forget($key); // UPDATE memcache								
								}
								else{
								
								}
						}//Get Researcher details (End Here)
							
							$sql_animal_check   = DB::first("SELECT COUNT(submitter_id) as total FROM `animal_researchers` where `animal_id` = '".$animal_id."' AND `submitter_id` = '".$submitterID."' ");
							
							if($sql_animal_check->total == 0)
							{
								$newData = array('submitter_id' => $submitterID, 
												'animal_id' 	=> $animal_id
												);
														  
											$rId = DB::table('animal_researchers')->insert_get_id($newData);
											$reseacher_data = $this->get_single_researcher($submitterID);
											$temp 			= json_encode($reseacher_data);
											$reseacher_data	= json_decode($temp, true);	
											$data[] = $reseacher_data['record'];
											$status = 'success';
							}				
						
					//}//$sql_researcher_check	
				}
			}	
			else
			{
				return false;
			}
		}
		
		return array('status' => $status,'record' => $data);
	}// End Get researcher data from external resourse
 	
}

?>
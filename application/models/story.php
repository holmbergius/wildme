<?php
class Story 
{
	public $cat_arr = array();
	
 	/*******************************Get All Researchers******************************/
	
 	public function get_all_stories($param)
	{
		//return false;
		$sortby			 = Utility::mysql_query_string($param['sortby']);
		$orderby 		 = Utility::mysql_query_string($param['orderby']);
		$offset 		 = Utility::mysql_query_string($param['offset']);
		$limit 		 	 = Utility::mysql_query_string($param['limit']);
		$animal_id 		 = Utility::mysql_query_string($param['animal_id']);
	

		$count  = DB::first("select COUNT(id) as total from `animal_stories` where `animal_id` = '".$animal_id."' ");
		$data   = array();
 		$status = 'error';
 
		if($count->total>0)
		{
			$sql   = "select id from animal_stories where `animal_id` = '".$animal_id."' order by $sortby $orderby limit $offset,$limit ";
			$researchers = DB::query($sql);
			$all_cat  = DB::query("select * from category");		
			foreach($all_cat as $cat)
			{
				$this->cat_arr[$cat->id] = $cat;
				
			}			
		
			foreach($researchers as $ind => $value)
			{
				$researcherData	= $this->get_single_story($value->id);	
				$temp 			= json_encode($researcherData);
				$researcherData	= json_decode($temp, true);		
				$data[]  		= $researcherData['record'];
				$status = 'success';
			}	
			
	
			$category_id = DB::first("SELECT `category_id`,`animal_id` FROM `animal` where id ='".$animal_id."'");
			
			$researcherData = $this->get_storyData($category_id->category_id,$category_id->animal_id,$animal_id);
			
		}
			// check data from api
			
		return array('records' => $data, 'totalrecords' => $count->total,'status' => $status);
	}

	/*******************************Get Single Reseacher******************************/

	public function get_single_story($id)
	{
		$key  = 'stroy_'.$id;
		$data = Cache::get($key);
		if($data==false || $data==NULL)
		{
		   $count = DB::first("select COUNT(id) as total from `animal_stories` where `id` = '".$id."' Limit 1;");

		   if($count->total>0)
		   {
				$data = DB::first("select * from `animal_stories` where `id` = '".$id."' Limit 1; ");
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
	public function get_storyData($category_id,$animal_id, $animal_pk)
	{
		$submitters = array();
		$data   = '';
 		$status = 'error';
		//$category  		= new Category;
		$cat_data 		= $this->cat_arr[$category_id];
		$api_url		= $cat_data->api_url;
		$image_url		= $cat_data->image_url;
		
		$doc_path = Config::get('application.doc_path').'files/stories/';	
		if($api_url	!= '')
		{
			//?individualID=="'.$animal_id.'"
			
			if (false !== ($contents = @file_get_contents($api_url.'rest/org.ecocean.Story'))) {
				// all good			
					$temp 	= file_get_contents($api_url.'rest/org.ecocean.Story');
					$var 	= preg_replace("/[\r\n]+/", " ", $temp);
					$var 	= utf8_encode($var);
					$json2 	= json_decode($var, true);
				
			} else {
				// error happened
				return false;
			}

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
						//
					
						if(isset($json2[$inds]['storyID']) && isset($json2[$inds]['correspondingMarkedIndividualID']) && $json2[$inds]['correspondingMarkedIndividualID'] == $animal_id  )
						{	
							$storyID = strip_tags($json2[$inds]['storyID']);
							$sql_researcher_check   = DB::first("SELECT COUNT(id) as total FROM `animal_stories` where `strory_id` = '".$storyID."' ");
							
							//	$researchUrl 	= @file_get_contents($api_url.'rest/org.ecocean.User/'.$submitterID);
							$media_type = 'none';
							$media_url 	= '';
							$imageUrl 	= false;
							
							if($json2[$inds]['storyMediaURL'] != NULL){
							
								$media_url = $json2[$inds]['storyMediaURL'];
								$media_type = 'video';
							
							}else{
							
							$imageUrl 	= @file_get_contents($api_url.'rest/org.ecocean.SinglePhotoVideo?correspondingStoryID=="'.$storyID.'"');							
							}
							
							/*if($imageUrl == false)
							{
								continue;
							}*/
															
								$imageVar 	= preg_replace("/[\r\n]+/", " ", $imageUrl);
								$imageVar 	= utf8_encode($imageVar);
								$imageJson 	= json_decode($imageVar, true);
								
								if(isset($imageJson[0]['fullFileSystemPath']) && !empty($imageJson[0]['fullFileSystemPath']))
								{
									
									$source_url = $image_url.$imageJson[0]['fullFileSystemPath'];
									$doc_path2 = $doc_path.$imageJson[0]['fullFileSystemPath'];	
									if(!@copy($source_url,$doc_path2))
									{
										$errors= error_get_last();
										//echo "COPY ERROR: ".$errors['type'];
										//echo "<br />\n".$errors['message'];
									} else {
										$media_url = $imageJson[0]['filename'];
										$media_type = 'image';
									}
								}
								
								$curr_date = 		date('Y-m-d h:i:s');
									//insert record in db 
								if($sql_researcher_check->total == 0)
								{
								$storypk = DB::table('animal_stories')->insert_get_id(array(
																		  'animal_id' 		   => $animal_pk, 
																		  'strory_id' 	  	   => $storyID,
																		  'story_teller_name'  => $json2[$inds]['storyTellerName'],
																		  'story_teller_email' => $json2[$inds]['storyTellerEmail'],
																		  'story' 			   => $json2[$inds]['storyText'],
																		  'media_url' 		   => $media_url,
																		  'media_type' 		   => $media_type,
																		  'date_added' 		   => $curr_date
														  			));
																	
								
									$key 						= 'story_'.$storypk; //set mem key
									$rData['id'] 				= $storypk;
									$rData['animal_id'] 		= $animal_pk;
									$rData['strory_id'] 		= $storyID;
									$rData['story_teller_name'] = $json2[$inds]['storyTellerName'];
									$rData['story_teller_email'] = $json2[$inds]['storyTellerEmail'];
									$rData['story'] 			=  $json2[$inds]['storyText'];
									$rData['media_url'] 		= $media_url;
									$rData['media_type'] 		= $media_type;
									$rData['date_added']		 = $curr_date;
									
									Cache::forever($key, $rData); // UPDATE memcache
									
							}//Get Researcher details (End Here)
							else
							{
								$storypk = DB::table('animal_stories')->where('strory_id','=',$storyID)->update(array(
																		  'animal_id' 	  	   => $animal_pk,
																		  'story_teller_name'  => $json2[$inds]['storyTellerName'],
																		  'story_teller_email' => $json2[$inds]['storyTellerEmail'],
																		  'story' 			   => $json2[$inds]['storyText'],
																		  'media_url' 		   => $media_url,
																		  'media_type' 		   => $media_type
														  			));
																																		
									$key 						= 'story_'.$storypk; //set mem key
							
									Cache::forget($key); // UPDATE memcache
							}
							
					}//$sql_researcher_check	
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
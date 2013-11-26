<?php  ini_set('max_execution_time','99999999999999999999999999999999');
class Encounter {
	


	//Get Single Record
	public function get_encounter($id)
	{
		$key	= 'encounter_'.$id;
		$data=Cache::get($key);
		
		if($data==false || $data==NULL)
		{
			$count = DB::table('encounters')->where('id','=',$id)->count();
			if($count > 0)
			{
				$data 			= DB::table('encounters')->where('id','=',$id)->first();

				Cache::forever($key,$data);
			}
			else
			{
				return false;	
			}			
		}
		
			return array('record' => $data,'totalrecords' => '1','status' => 'success');
	}
	
//Get All Records
	public function get_encounters($param)
	{
		$media 	 	= new Media;
		$data 		= array();
		$limit		= Utility::mysql_query_string($param['limit']);
		$offset     = Utility::mysql_query_string($param['offset']);
		$sortby     = Utility::mysql_query_string($param['sortby']);
		$orderby    = Utility::mysql_query_string($param['orderby']);
		$user_id    = $param['user_id'];
		if(isset($param['id']))	$id			= Utility::mysql_query_string($param['id']);
		else $id = "";
		if(isset($param['animal_id'])) 	$animal_id  = Utility::mysql_query_string($param['animal_id']);
		else 	$animal_id = "";
		
		
		$sqlpart = '';
		$sqlChunk= array();

		if($animal_id != '')  $sqlChunk[] = " animal_id  = '".$animal_id."' ";
		if($id != NULL && $id != "") $sqlChunk[] = " id  = ".$id." ";
		if($param['all_location'] != NULL && $param['all_location'] != "") $sqlChunk[] = " latitude  != '' ";
		if($param['all_location'] != NULL && $param['all_location'] != "") $sqlChunk[] = " longitude  != '' ";

		

		if(count($sqlChunk) > 0 ){
			$sqlpart = " where " . implode(" and ", $sqlChunk);
		}
		
		$count  = DB::first("select COUNT(id) as total from `encounters` $sqlpart");
		
		if($count->total > 0)
		{
			if($param['animal_list'] == 1 )
			{
				$sql   = "SELECT `id` FROM `encounters`  $sqlpart group by `animal_id` order by $sortby $orderby limit $offset,$limit  ";	
			}
			else
			{
				$sql   = "SELECT `id` FROM `encounters`  $sqlpart order by $sortby $orderby limit $offset,$limit  ";	
			}
			
			$encounters = DB::query($sql);
	 
			foreach($encounters as $ind => $value)
			{
				$encounterData	= $this->get_encounter($value->id);	
				$temp 			= json_encode($encounterData);
				$encounterData	 	= json_decode($temp, true);				
				$temp 							= json_encode($encounterData['record']);
				$encounterData['record']	 	= json_decode($temp, true);				
						
				$data[$ind]			= $encounterData['record'];
				
				$status 		= 'success';
				
				$media_count 	= DB::table('media')->where('encounter_id','=',$value->id)->count();

				$media_details  = array();

				if($media_count > 0){
					$media  =  new Media;
					$temp   = array();
					$temp['offset']   = $param['media_offset'];
					$temp['limit']    = 3;
					$temp['sortby']   = " id ";
					$temp['orderby']  = " asc ";
					$temp['encounter_id']     = $value->id;


					$data1 = $media->get_medias($temp);
					$media_details	  = $data1['records'];
				}
				
				$Animal  		= new Animal;
				$data2 			= $Animal->get_animal($encounterData['record']['animal_id']);
				$data2			= json_decode(json_encode($data2), TRUE);
				$animal_details	= $data2['record'];
				
				$Category  		= new Category;
				$data3 			= $Category->get_category($animal_details['category_id']);
				$data3			= json_decode(json_encode($data3), TRUE);
				$cat_details	= $data3['record'];
				
					
				$data[$ind]['gender']   	 	= $animal_details['sex'];
				$data[$ind]['nick_name']   	 	= $animal_details['nick_name'];
				$data[$ind]['category_title'] 	= $cat_details['title'];
				$data[$ind]['category_color'] 	= $cat_details['color_code'];
				$data[$ind]['category_type'] 	= $cat_details['type'];
				$data[$ind]['category_icon'] 	= $cat_details['icon'];
				
				$data[$ind]['media_count']   = $media_count;
				$data[$ind]['media_details'] = $media_details;
				
				if($user_id != '')
				{
					$count_like  = DB::first("select COUNT(id) as total from `log` where user_id='".$user_id."' AND ref_id= '".$value->id."' AND `type` = 'like_encounter' ");
					if($count_like->total > 0)
					{
						$data[$ind]['is_like']   =1;
					}
					else
					{
						$data[$ind]['is_like']   =0;
					}
				}
				
			}
		}
		else
		{
			$data =	array();
			$status = 'error';	
		}
		
		return array('records' => $data, 'totalrecords' => $count->total,'status' => $status);
	}		
	

// Increment Share count
	public function get_shareincrement($param)
	{
		$key = 'encounter_'.$param['id'];
		$query = DB::table('encounters')->where('id','=',$param['id'])->increment('share_count');
		Cache::forget($key);			
		
		$param['type'] = 'share';
		$param['ref_id'] = $param['animal_id'];	
		$log = new UserLog;
		$record = $log->create_user_log($param);	
				
		return array('status' =>'success');
	}


// Increment Like count
	public function get_likeincrement($param)
	{
		
		$check_class	= new UserLog;
		$check_res		= $check_class->check_like($param);
		$check_res		= json_decode(json_encode($check_res),TRUE);
		if($check_res['status'] == 'success')
		{
			$key = 'encounter_'.$param['id'];
			$query = DB::table('encounters')->where('id','=',$param['id'])->increment('like_count');
			Cache::forget($key);
			
			$param2['user_id'] 	= $param['user_id'];
			$param2['ref_id']	= $param['id'];
			$param2['type']		= 'like_encounter';
		
			$check_class	= new UserLog;
			$check_res		= $check_class->create_user_log($param2);
			//$check_res		= json_decode(json_encode($check_res),TRUE);
			
			$status = 'success';
		}
		else
		{
			$status = 'error';
		}
				
		return array('status' =>$status);
	}
 
 	// Increment Comment count
	public function get_commentincrement($id)
	{
		$key = 'encounter_'.$id;
		$query = DB::table('encounters')->where('id','=',$id)->increment('comment_count');
		Cache::forget($key);			
				
				
		return array('status' =>'success');
	}	

}
?>
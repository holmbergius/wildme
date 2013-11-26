<?php  
class Animal {
	


	//Get Single Record
	public function get_animal($id)
	{
		$key	= 'animal_'.$id;
		$data	= Cache::get($key);
		
		if($data==false || $data==NULL)
		{
			$count = DB::table('animal')->where('id','=',$id)->count();
			if($count > 0)
			{
				$data 	= DB::table('animal')->where('id','=',$id)->first();

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
	public function get_animals($param)
	{
		$status = "error";
		$data 		= array();
		$limit		= Utility::mysql_query_string($param['limit']);
		$offset     = Utility::mysql_query_string($param['offset']);
		$sortby     = Utility::mysql_query_string($param['sortby']);
		$orderby    = Utility::mysql_query_string($param['orderby']);
		if(isset($param['user_id']))
			$user_id = $param['user_id'];

		else
			$user_id = "";
		if(isset($param['keyword']))	
			$keyword    = Utility::mysql_query_string($param['keyword']);
		else 
			$keyword = "";
		
		if(isset($param['category_id']))	
			$category_id    = Utility::mysql_query_string($param['category_id']);
		else 
			$category_id = "";
		
		if(isset($param['id']))	
			$id	= Utility::mysql_query_string($param['id']);
		else
			$id = "";

		$sqlpart = '';
		$sqlChunk= array();
		

		if($id != NULL && $id != "") 	$sqlChunk[] = " id  = '".$id."' ";
		if($keyword != "")  			$sqlChunk[] = " (nick_name like '%".$keyword."%' OR id like '%".$keyword."%' )";
		if($category_id != "")  		$sqlChunk[] = " category_id =".$category_id."";
	
		if(count($sqlChunk) > 0 ){
			$sqlpart = " where " . implode(" and ", $sqlChunk);
		}

		$count  = DB::first("select COUNT(id) as total from `animal` $sqlpart");
		if($count->total > 0)
		{
			$sql   = "SELECT `id` FROM `animal`  $sqlpart order by $sortby $orderby limit $offset,$limit ";	
			$encounters = DB::query($sql);
	 
			foreach($encounters as $ind => $value)
			{
				$friendData	= $this->get_animal($value->id);	
				$temp 			= json_encode($friendData);
				$friendData	 	= json_decode($temp, true);				
				$temp 						= json_encode($friendData['record']);
				$friendData['record']	 	= json_decode($temp, true);				
						
				$data[$ind]			= $friendData['record'];
				
				$status 		= 'success';
				
				$encounter_count 	= DB::table('encounters')->where('animal_id','=',$value->id)->count();

				$encounter_details  = array();

				if($encounter_count > 0){

					// this code block will return all encounter of this animal

					/*$encounter  =  new Encounter;
					$temp   = array();
					$temp['offset']   = 0;
					$temp['limit']    = $data[$ind]['encounter_count'];
					$temp['sortby']   = " id ";
					$temp['orderby']  = " desc ";
					$temp['animal_id']     = $value->id;


					$data1 = $encounter->get_encounters($temp);
					$encounter_details	  = $data1['records'];*/
				}
				$data[$ind]['encounter_count']   = $encounter_count;
				// $data[$ind]['encounter_details'] = $encounter_details;
				
				$Category  		= new Category;
				$data3 			= $Category->get_category($data[$ind]['category_id']);
				$data3			= json_decode(json_encode($data3), TRUE);
				$cat_details	= $data3['record'];
				
				$data[$ind]['category_title'] 	= $cat_details['title'];
				$data[$ind]['category_color'] 	= $cat_details['color_code'];
				$data[$ind]['category_type'] 	= $cat_details['type'];
				$data[$ind]['category_icon'] 	= $cat_details['icon'];
				$data[$ind]['api_url'] 			= $cat_details['api_url'];
				
				
				$friend_count 	= DB::table('animal_friend')->where('animal_id','=',$value->id)->count();

				$friend_details  = array();
				
				if($friend_count > 0){
					
					// this code block will return all friends of this animal
					/*$friend  =  new AnimalFriend;
					$temp   = array();
					$temp['offset']   = 0;
					$temp['limit']    = $friend_count;
					$temp['sortby']   = " id ";
					$temp['orderby']  = " asc ";
					$temp['animal_id']= $value->id;


					$data1 = $friend->get_friends($temp);
					$friend_details	  = $data1['records'];*/
				}
				$data[$ind]['friend_count']   = $friend_count;
				// $data[$ind]['friend_details'] = $friend_details;
				
				$data[$ind]['follow_check']   = 0;
				//follow check
				if($user_id != '')
				{
					$follow_count 	= DB::table('follow')->where('animal_id','=',$value->id)->where('user_id','=',$user_id)->count();
					if($follow_count > 0)
					{
						$data[$ind]['follow_check']   = 1;
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
		$key = 'animal_'.$param['id'];
		$query = DB::table('animal')->where('id','=',$param['id'])->increment('share_count');
		Cache::forget($key);			
		
		$param['type'] = 'share';
		$param['ref_id'] = $param['id'];			
		$log = new UserLog;
		$record = $log->create_user_log($param);		
				
		return array('status' =>'success');
	}

// Increment Follow count
	public function get_followincrement($id)
	{
		$key = 'animal_'.$id;
		$query = DB::table('animal')->where('id','=',$id)->increment('follow_count');
		Cache::forget($key);			
				
				
		return array('status' =>'success');
	}
// dencrement Follow count
	public function get_followdecrement($id)
	{
		$key = 'animal_'.$id;
		$query = DB::table('animal')->where('id','=',$id)->decrement('follow_count');
		Cache::forget($key);			

		return array('status' =>'success');
	}



}
?>
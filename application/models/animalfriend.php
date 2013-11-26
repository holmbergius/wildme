<?php  //ini_set('max_execution_time','99999999999999999999999999999999');
class AnimalFriend {
	


	//Get Single Record
	public function get_friend($id)
	{
		$key	= 'friend_'.$id;
		$data=Cache::get($key);
		
		if($data==false || $data==NULL)
		{
			$count = DB::table('animal_friend')->where('id','=',$id)->count();
			if($count > 0)
			{
				$data 	= DB::table('animal_friend')->where('id','=',$id)->first();

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
	public function get_friends($param)
	{
		$data 		= array();
		$limit		= Utility::mysql_query_string($param['limit']);
		$offset     = Utility::mysql_query_string($param['offset']);
		$sortby     = Utility::mysql_query_string($param['sortby']);
		$orderby    = Utility::mysql_query_string($param['orderby']);
		if(isset($param['id']))
			$id			= Utility::mysql_query_string($param['id']);
		else
			$id = "";
		if(isset($param['animal_id']))
			$animal_id  = Utility::mysql_query_string($param['animal_id']);
		else
		 	$animal_id = "";
			
		if(isset($param['user_id']))
			$user_id = $param['user_id'];
		else
			$user_id = "";
		$sqlpart = '';
		$sqlChunk= array();
		// $status  = "error";
		
		if($id != NULL && $id != "") $sqlChunk[] = " id  = ".$id." ";
		if($animal_id != '')  $sqlChunk[] = " animal_id  = '".$animal_id."' ";
		
		if(count($sqlChunk) > 0 ){
			$sqlpart = " where " . implode(" and ", $sqlChunk);
		}

		

		$count  = DB::first("select COUNT(id) as total from `animal_friend` $sqlpart");
		if($count->total > 0)
		{
			$sql   = "SELECT `id` FROM `animal_friend`  $sqlpart order by $sortby $orderby limit $offset,$limit ";	
			$encounters = DB::query($sql);
	 
			foreach($encounters as $ind => $value)
			{
				$nick_name = "";
				$category_id = "";	

				$friendData	= $this->get_friend($value->id);	
				$temp 			= json_encode($friendData);
				$friendData	 	= json_decode($temp, true);				
				$temp 							= json_encode($friendData['record']);
				$friendData['record']	 	= json_decode($temp, true);				
				
				$data[$ind]			= $friendData['record'];
				
				unset($data[$ind]['animal_id']);
				
				$status 		= 'success';
				
				$animal_found 	= DB::table('animal')->where('id','=',$data[$ind]['friend_id'])->count();
				
				$friend_details  = array();
				// echo "animal found = $animal_found ";
				if($animal_found > 0){
					// echo 1;
					$animal 			=  new Animal;
					
					$data1	 			= $animal->get_animal($data[$ind]['friend_id']);
					$temp 				= json_encode($data1);
					$data1	 			= json_decode($temp, true);
				
					$friend_details	  	= $data1['record'];
					$nick_name 	 		= $friend_details['nick_name'];
					
					$category  			=  new Category;
					
					$data2	 			= $category->get_category($friend_details['category_id']);
					$temp 				= json_encode($data2);
					$data2	 			= json_decode($temp, true);
					
					$category_id 		= $data2['record'];
				}
				$data[$ind]['friend_detail']   	= $friend_details;
				$data[$ind]['category_detail'] 		= $category_id;
				
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
	




}
?>
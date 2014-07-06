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
				/*$count = DB::table('animal_original')->where('id','=',$id)->count();
				if($count > 0)
				{
					$data 	= DB::table('animal_original')->where('id','=',$id)->first();
	
					//Cache::forever($key,$data);
				}
				else*/ 
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
		if($keyword != "")  			$sqlChunk[] = " (nick_name like '%".$keyword."%' OR id like '%".$keyword."%' OR animal_id like '%".$keyword."%' OR label like '%".$keyword."%' )";
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

			
				}
				$data[$ind]['encounter_count']   = $encounter_count;
				// $data[$ind]['encounter_details'] = $encounter_details;
				
				$Category  		= new Category;
				$data3 			= $Category->get_category($data[$ind]['category_id']);
				$data3			= json_decode(json_encode($data3), TRUE);
				$cat_details	= $data3['record'];
				//print_r($cat_details);die;
				$data[$ind]['category_title'] 	= $cat_details['title'];
				$data[$ind]['category_color'] 	= $cat_details['color_code'];
				$data[$ind]['category_type'] 	= $cat_details['type'];
				$data[$ind]['category_icon'] 	= $cat_details['icon'];
				$data[$ind]['api_url'] 			= $cat_details['api_url'];
				$data[$ind]['category_active_gps'] =  $cat_details['active_gps'];
				$data[$ind]['category_active_adoption'] =  $cat_details['active_adoption'];
				
				
				$friend_count 	= DB::table('animal_friend')->where('animal_id','=',$value->id)->count();

				$friend_details  = array();
				
				if($friend_count > 0){
					
				
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
				
				$adoptorDetail 	= DB::query('SELECT `uid` FROM `adoptor` WHERE `animal_id` = "'.$value->id.'" ');
			
				$user_names = array();
				//print_r($adoptorDetail);	die;
				
				foreach($adoptorDetail as $inds => $val)
				{
					if($val->uid > 0){
					$userDetail 	= DB::table('user')->where('id','=',$val->uid)->first();
					$user_names[]  = $userDetail->name;	
					}
				}
				
				
				$data[$ind]['user_data'] 	= implode(",",$user_names);
				
				
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

// adoption status change
	public function adoption_status_change($param)
	{
		$id = $param['id'];
		$key	= 'animal_'.$id;
		$status = $param['status'];
		
		$count		= DB::first("select count(id) as total from animal where id = '".$id."' ");	
		if($count->total >0)
		{
		$sql   = "UPDATE `animal` set `active_adoption` = '$status' WHERE id = '".$id."'";	
		$user = DB::query($sql);
			Cache::forget($key);
		$status = 'success';
		return array('status'=>$status);
		}else
		{
			$status = 'error';
			$msg = 'No record found for this id';
			return array('status'=>$status , 'msg'=>$msg);
		} 
	}
	
	// gps status change
	public function gps_status_change($param)
	{
		$id = $param['id'];
		$key	= 'animal_'.$id;
		$status = $param['status'];
		
		$count		= DB::first("select count(id) as total from animal where id = '".$id."' ");	
		if($count->total >0)
		{
		$sql   = "UPDATE `animal` set `active_gps` = '$status' WHERE id = '".$id."' ";	
	
		$user = DB::query($sql);
		Cache::forget($key);
		$status = 'success';
		return array('status'=>$status);
		}else
		{
			$status = 'error';
			$msg = 'No record found for this id';
			return array('status'=>$status , 'msg'=>$msg);
		} 
	}

	public function get_animal_details($param)
	{
		$animal_id = $param['animal_id'];

		$count  = DB::first("select COUNT(id) as `total`, `id` from animal where `label` = '$animal_id'");

		if($count->total >0)
		{
			$animal_id = $count->id;
			$animalData = $this->get_animal($animal_id);
			$animalData	= json_decode(json_encode($animalData), TRUE);
			$nick_name = $animalData['record']['nick_name'];
			$quote = $animalData['record']['quote'];

			if($nick_name == '' || $nick_name == null)
			{
				$nick_name = 0;
			}
			if($quote == '' || $quote == null)
			{
				$quote = 0;
			}

			$category_id = $animalData['record']['category_id'];
			$category  		= new Category;
			$categoryData	= $category->get_category($category_id);
			$categoryData	= json_decode(json_encode($categoryData), TRUE);
			$icon = $categoryData['record']['icon'];
			$type = $categoryData['record']['type']; 
			$price = $categoryData['record']['minimum_amount'];
			$data1['record']['wildme_icon'] = $icon;
			$data1['record']['wildme_animal_type']  = $type;
			$data1['record']['wildme_animal_price'] = $price;
			$data1['record']['wildme_animal_nick'] = $nick_name;
			$data1['record']['wildme_animal_quote'] = $quote;
			$data1['record']['wildme_category_id']   = $category_id;
			$data1['record']['wildme_category_name'] =  $categoryData['record']['title'];
			$data1['record']['wildme_animal_id'] 	 =  $animal_id;
			$data1['record']['wildme_animal_label']  = $animalData['record']['label'];
			$data1['record']['first_adoptor']  = $animalData['record']['first_adoptor'];
			
			$data[] = $data1['record'];
			$status = 'success';
		}
		else
		{
			$data[] =	'';
			$status = 'error';	
		}

		return array('records' => $data, 'totalrecords' => $count->total,'status' => $status);	

	}

}
?>
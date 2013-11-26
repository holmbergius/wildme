<?php
class User {
/********************************Single user***************************************************/
	public function get_user($id)
	{
		$key  = 'wildme_user_'.$id;
		$data = Cache::get($key);
		
		if($data==false || $data==NULL)
		{
		   $count = DB::first("select COUNT(id) as total from user where `id` = '".$id."' Limit 1;");

		   if($count->total>0)
		   {
				$data = DB::table('user')->where('id','=',$id)->first();
				Cache::forever($key, $data);
		   }
		   else
		   {
				return false;   
		   } 
		}

		return array('record' => $data,'totalrecords' => '1','status' => 'success');
	}	
	
	//Get all user Details
	public function get_users($param)
	{
		$keyword		 = Utility::mysql_query_string($param['keyword']);
		$sortby			 = Utility::mysql_query_string($param['sortby']);
		$orderby 		 = Utility::mysql_query_string($param['orderby']);
		$offset 		 = Utility::mysql_query_string($param['offset']);
		$limit 		 	 = Utility::mysql_query_string($param['limit']);
		$sqlpart		 = '';
		$data	 		 = '';
		$status 		 = 'error';	
		
		if($keyword!= '')
		{
			$sqlpart .=" AND ( `name` like '%$keyword%' OR `email` like '%$keyword%' ) ";
		}
		if($param['id'] > 0)
		{
			$sqlpart .=" AND ( `id` = '".$param['id']."'   ) ";
		}
		
		$query = "select COUNT(id) as total from user WHERE 1=1 $sqlpart";
		$count = DB::first($query);
	
		if($count->total>0)
		{
			$sql = "select id from user WHERE 1=1 $sqlpart order by $sortby $orderby limit $offset,$limit ";	
			$user = DB::query($sql);

			foreach($user as $ind => $value)
			{
				$userData	= $this->get_user($value->id);	
				
				$temp 	  	= json_encode($userData);
				$userData 	= json_decode($temp, true);
				
				$data[$ind] = $userData['record'];
				
				$data[$ind]['date_added'] = Utility::dat($userData['record']['date_added'], 'M d, Y');
				
				$status  = 'success';
			}
		}
		return array('records' => $data, 'totalrecords' => $count->total,'status' => $status);
	}
	
	//return user detail against a user_id
	public function get_user_friends($param)
	{
		$offset 		 = Utility::mysql_query_string($param['offset']);
		$limit 		 	 = Utility::mysql_query_string($param['limit']);
		$user_id 		 = Utility::mysql_query_string($param['user_id']);
		
		$status = 'error';
		$i 		= 0;
		$final_total = 0;
		$user_detail= array();
		$count		= DB::first("select count(id) as total from user where id = ".$user_id." ");	
		if($count->total >0)
		{
			$userData	= $this->get_user($user_id);			
			$temp 	  	= json_encode($userData);
			$userData 	= json_decode($temp, true);
			if(empty($userData['record']['friend_ids']))
			{
				$status = 'error';
			}
			else
			{
				$break_id	= explode(',',$userData['record']['friend_ids']);
				
				foreach($break_id as $ind => $value)
				{
					$count_sql		= DB::first("select count(id) as total from user where id = ".$break_id[$ind]." ");	
					
					$final_total = $final_total + $count_sql->total;
					if($count_sql->total > 0 )
					{
						if($offset <= $i && $i<=$limit)
						{
							$userData	= $this->get_user($break_id[$ind]);			
							$temp 	  	= json_encode($userData);
							$userData 	= json_decode($temp, true);
							
							$count = DB::first("SELECT COUNT(id) as total from `follow` where `user_id` = '".$break_id[$ind]."' limit $offset,$limit  ");
							
							$userData['record']['following'] = $count->total;
							
							$user_detail[] = $userData['record'];
							$i++;
							$status = 'success';
						}
					}
				}
			}
		}
		return array('records' => $user_detail, 'totalrecords' => $final_total,'status' => $status);
	}

	
}
?>
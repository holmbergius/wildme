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
		
		if($sortby == 'badge_users'){
			
			$sortby = 'id';
  		    $orderby = 'DESC';
			$sqlpart .=" AND ( `adoptor_badge` = 'Yes'   ) ";
			
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
		
		$final_limit = $limit + $offset;
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
						
							if($offset <= $i && $i<=$final_limit)
							{
								$userData	= $this->get_user($break_id[$ind]);			
								$temp 	  	= json_encode($userData);
								$userData 	= json_decode($temp, true);
								
								$count = DB::first("SELECT COUNT(id) as total from `follow` where `user_id` = '".$break_id[$ind]."'   ");
								
								$userData['record']['following'] = $count->total;
								
								$user_detail[] = $userData['record'];
							
								$status = 'success';
							}
							$i++;
					}
				}
			}
		}
		return array('records' => $user_detail, 'totalrecords' => $final_total,'status' => $status);
	}

	// adoptor badge update
	public function update_adoptor_badge($param)
	{
		$id = $param['id'];
		$adoptor_badge = $param['adoptor_badge'];
		
		$count		= DB::first("select count(id) as total from user where id = ".$id." ");	
		if($count->total >0)
		{
		$sql   = "UPDATE `user` set `adoptor_badge` = '$adoptor_badge' WHERE id = '".$id."'";	
		$user = DB::query($sql);
		$key  = 'wildme_user_'.$id;
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

	public function update_ban_user($param)
	{
		$id = $param['id'];
		$ban_status = $param['ban_status'];

		$count		= DB::first("select count(id) as total from user where id = ".$id." ");	
		if($count->total >0)
		{
			$sql   = "UPDATE `user` set `is_ban` = '$ban_status' WHERE id = '".$id."'";
		
		$user = DB::query($sql);
		$key  = 'wildme_user_'.$id;
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
	
	
}
?>
<?php

class Userlog
{

/*************insert log*******************/
	public function create_user_log($param)
	{
		$date_added		= date("Y-m-d H:i:s");
		$id = DB::table('log')->insert_get_id(array('user_id' 	  => $param['user_id'],
													'ref_id'      => $param['ref_id'],
													'type'  	  => $param['type'],
													'date_added'  => $date_added	 
															));
														
		if($id > 0)
		{	
			$key = 'log_'.$id;
			$data['id']		    = $id;
			$data['user_id'] 	= $param['user_id'];
			$data['ref_id'] 	= $param['ref_id'];
			$data['type'] 		= $param['type'];
			$data['date_added'] = $date_added;
			
			Cache::forever($key,$data);
			$status = 'success';
		}
			return array('status'=>$status, 'msg' => 'record inserted');	
	}
	

/*************get all logs*******************/
	public function get_user_logs($param)
	{
		$sortby			 = Utility::mysql_query_string($param['sortby']);
		$orderby 		 = Utility::mysql_query_string($param['orderby']);
		$offset 		 = Utility::mysql_query_string($param['offset']);
		$limit 		 	 = Utility::mysql_query_string($param['limit']);
		$id 			 = Utility::mysql_query_string($param['id']);
		$user_id 		 = Utility::mysql_query_string($param['user_id']);
		
		$sqlpart='';
	
		if ($user_id!='') $sqlpart .=" WHERE (user_id = '".$user_id."') ";
		else if ($id!='') $sqlpart .= "WHERE `id` = '".$id."' ";
		
		$count  = DB::first("select COUNT(id) as total from log $sqlpart");
		$data   = array();
 		$status = 'error';

		$count_like  	= DB::first("select COUNT(id) as total from log where `type` = 'like_encounter' AND user_id = '".$user_id."' ");
		$count_comm  	= DB::first("select COUNT(id) as total from log where `type` = 'comment_encounter'  AND user_id = '".$user_id."' ");
		$count_follow1  	= DB::first("select COUNT(id) as total from log where `type` = 'follow'  AND user_id = '".$user_id."' ");
		$count_unfollow  	= DB::first("select COUNT(id) as total from log where `type` = 'unfollow'  AND user_id = '".$user_id."' ");
		
		$count_follow   = $count_follow1->total - $count_unfollow->total;
		
		$user_name  	= DB::first("select name from user where  id = '".$user_id."' ");

			
		if($count->total>0)
		{
			$sql   = "select id from log $sqlpart order by $sortby $orderby limit $offset,$limit ";	
			$log   = DB::query($sql);
	 
			foreach($log as $ind => $value)
			{
				$logData	= $this->get_user_log($value->id);	
				$logData	= json_decode(json_encode($logData), TRUE);
				
				if($logData['record']['type'] == 'like_encounter' || $logData['record']['type'] == 'comment_encounter')
				{
					$en_id 		= $logData['record']['ref_id'];
					$animal_id  = DB::first("select animal_id from encounters where id = '".$en_id."' ");
					
					$logData['record']['animal_id'] = $animal_id->animal_id;
					
					$ani 	 = new Animal;
					$out_ani = $ani->get_animal($animal_id->animal_id);
					$out_ani = json_decode(json_encode($out_ani), TRUE);
					
					$logData['record']['nick_name'] 	= $out_ani['record']['nick_name'];
					$logData['record']['sex'] 		= $out_ani['record']['sex'];
					
					$ani 	 = new Category;
					$out_cat = $ani->get_category($out_ani['record']['category_id']);
					$out_cat = json_decode(json_encode($out_cat), TRUE);
					$logData['record']['category_detail'] = $out_cat['record'];
				}
				else
				{
					$logData['record']['animal_id'] = $logData['record']['ref_id'];
					
					$ani 	 = new Animal;
					$out_ani = $ani->get_animal($logData['record']['animal_id']);
					$out_ani = json_decode(json_encode($out_ani), TRUE);
					
					$logData['record']['nick_name'] 	= $out_ani['record']['nick_name'];
					$logData['record']['sex'] 		= $out_ani['record']['sex'];
					
					$ani 	 = new Category;
					$out_cat = $ani->get_category($out_ani['record']['category_id']);
					$out_cat = json_decode(json_encode($out_cat), TRUE);
					$logData['record']['category_detail'] = $out_cat['record'];
				}
				
				$data[] = $logData['record'];
		
				$status = 'success';
			}
			
		}
		else
		{
			$data[] =	'';
			$status = 'error';	
		}
	
			return array('records' => $data, 'totalrecords' => $count->total, 'total_comment'=>$count_comm->total, 'total_like'=>$count_like->total , 'total_follow'=>$count_follow, 'user_name'=>$user_name->name, 'status' => $status);
		}
	

	
	
/************************Get single Log Detail*******************************/	
	public function get_user_log($id)
	{
		
		$key  = 'log_'.$id;
		//$data = Cache::forever($key,false);
		$data = Cache::get($key);
		//$data=false;
		if($data==false || $data==NULL)
		{
		   $count = DB::first("select COUNT(id) as total from log where `id` = '".$id."' ");

		   if($count->total>0)
		   {
				$data = DB::first("select * from log where `id` = '".$id."'");
				Cache::forever($key, $data);
		   }
		   else
		   {
				return false;   
		   } 
		}
		return array('record' => $data,'totalrecords' => '1','status' => 'success');
	}
	
	//to check if the user has liked a particular encounter.
	public function check_like($param)
	{
		
		$status = 'success';
		$count = DB::first("select COUNT(id) as total from log where `user_id` = '".$param['user_id']."' AND `ref_id`= '".$param['id']."' AND `type` = 'like_encounter' ");
		
		if($count->total > 0)
		{
			$status = 'error';
		}
		return array('status'=> $status);
	}
	
	


	
}
?>
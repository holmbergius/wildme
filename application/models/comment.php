<?php

class Comment{

	public function create_comment($param)
	{
		$status = 'error';
		$date_added	= date("Y-m-d H:i:s");
		$id	 = DB::table('comment')->insert_get_id(array('user_id' 		=> $param['user_id'],
														 'encounter_id' => $param['encounter_id'],
														 'message' 		=> $param['message'],
														 'date_added' 	=> $date_added
														));
														
		if($id > 0)
		{
			$key = 'comment_'.$id;
			$data['id'] 		 	= $id;
			$data['user_id']	 	= $param['user_id'];
			$data['encounter_id']	= $param['encounter_id'];
			$data['message'] 	 	= $param['message'];
			$data['date_added']  	= $date_added;
			
			$comment = new Encounter;
			$increment = $comment->get_commentincrement($param['encounter_id']);
				
				$param1['user_id'] = $param['user_id'];
				$param1['ref_id']  = $param['encounter_id'];
				$param1['type']    = 'comment_encounter';
				
				$log = new UserLog;
				$record = $log->create_user_log($param1);
			
			Cache::forever($key,$data);
			$status = 'success';
		}
			return array('data' => $data ,'status'=>$status);
		
	}
	
	public function get_comment($id)
	{
		$key  = 'comment_'.$id;
		//$data = Cache::forever($key,false);
		$data = Cache::get($key);
		//$data=false;
		if($data==false || $data==NULL)
		{
		   $count = DB::first("select COUNT(id) as total from comment where `id` = '".$id."' Limit 1;");

		   if($count->total>0)
		   {
				$data = DB::first("select * from comment where `id` = '".$id."' Limit 1; ");
				Cache::forever($key, $data);
		   }
		   else
		   {
				return false;   
		   } 
		}
		return array('record' => $data,'totalrecords' => '1','status' => 'success');
	}
	
	public function get_comments($param)
	{
		$keyword		 = Utility::mysql_query_string($param['keyword']);
		$sortby			 = Utility::mysql_query_string($param['sortby']);
		$orderby 		 = Utility::mysql_query_string($param['orderby']);
		$offset 		 = Utility::mysql_query_string($param['offset']);
		$limit 		 	 = Utility::mysql_query_string($param['limit']);
		$encounter_id	 = Utility::mysql_query_string($param['encounter_id']);
		$user_id 		 = Utility::mysql_query_string($param['user_id']);
		$id				 = Utility::mysql_query_string($param['id']);
		$sqlpart='';
		$total = 0;

		
		
		if ($id!='') $sqlpart .=" AND ( id = ".$id." ) ";
		if ($encounter_id != '') $sqlpart .=" AND `encounter_id` = '".$encounter_id."' ";
		//if ($user_id != '') $sqlpart .=" AND `user_id` = '".$user_id."' ";
		

		$count  = DB::first("select COUNT(id) as total from comment where 1=1 $sqlpart");
		$data   = array();
 		$status = 'error';

 		
 		
		if($count->total>0)
		{
			$sql   = "select id from comment where 1=1 $sqlpart order by $sortby $orderby limit $offset,$limit ";	
			$comment   = DB::query($sql);
	 
			foreach($comment as $ind => $value)
			{
				
				$commentData	= $this->get_comment($value->id);	
				$commentData	= json_decode(json_encode($commentData), TRUE);
				$comment = new CommentLikes;
				$counts = $comment->get_comment_like($value->id);

				if($counts['totalrecords'] > 0)
				{
					$comment_like  = $comment->get_total_likes($value->id);
					$total = $comment_like['data'];
					$like_status  = $comment->get_like($user_id,$value->id);
					$status = $like_status['data'];
				}
				else
				{
					$total = 0;
					$status = "No";
				}
				
				$log 			= new User;
				$user_record 	= $log->get_user($commentData['record']['user_id']);
				$user_record	= json_decode(json_encode($user_record), TRUE);
				$commentData['record']['user_name'] = $user_record['record']['name'];
				$commentData['record']['total_likes'] = $total;
				$commentData['record']['like_status'] = $status;
				$data[] = $commentData['record'];
		
				$status = 'success';
			}

			
		}
		else
		{
			$data[] =	'';
			$status = 'error';	
		}
	
			return array('records' => $data, 'totalrecords' => $count->total,'status' => $status);	
	}
	
	public function del_comment($id,$encounter_id)
	{
		$status = 'error';	
		$key = 'comment_'.$id;

		$count = DB::table('comment')->where('id','=',$id)->count();
		if($count > 0)
		{
			$comment = new Encounter;
			$decrement = $comment->get_commentdecrement($encounter_id);
			
			$data = DB::table('comment')->where('id','=',$id)->delete();

			$data1 = DB::table('report_abuse')->where('comment_id','=',$id)->delete();
		
			Cache::forget($key);
			
			$msg 		= 'record deleted';
			$status 	= 'success';
			
		}
		else
		{
			$msg = 'no record found for this id';
		} 

		return array('status' => $status, 'msg' => $msg);
	}

	public function del_commentadmin($id,$encounter_id)
	{
		$status = 'error';	
		$key = 'comment_'.$id;

		$count = DB::table('comment')->where('id','=',$id)->count();
		if($count > 0)
		{
			$comment = new Encounter;
			$decrement = $comment->get_commentdecrement($encounter_id);
			
			$data = DB::table('comment')->where('id','=',$id)->delete();

			$data1 = DB::table('report_abuse')->where('comment_id','=',$id)->delete();
		
			Cache::forget($key);
			
			$msg 		= 'record deleted';
			$status 	= 'success';
			
		}
		else
		{
			$msg = 'no record found for this id';
		} 

		return array('status' => $status, 'msg' => $msg);
	}

	public function del_commentByUserId($id)
	{
		$status = 'error';	
		$key = 'comment_'.$id;

		$count = DB::table('comment')->where('id','=',$id)->count();
		if($count > 0)
		{
			$data = DB::table('comment')->where('id','=',$id)->delete();

			$data1 = DB::table('report_abuse')->where('comment_id','=',$id)->delete();
		
			Cache::forget($key);
			
			$msg 		= 'record deleted';
			$status 	= 'success';
			
		}
		else
		{
			$msg = 'no record found for this id';
		} 

		return array('status' => $status, 'msg' => $msg);
	}


	
/*************update comment*******************/
	public function update_comment($param)
	{
		$id		 			= Utility::mysql_query_string($param['id']);
		$user_id 			= Utility::mysql_query_string($param['user_id']);
		$encounter_id 		= Utility::mysql_query_string($param['encounter_id']);
		$message 			= Utility::mysql_query_string($param['message']);
		$date_added		    = date("Y-m-d H:i:s");
		
		$count = DB::first("SELECT count(id) as total FROM `comment` WHERE `id` = '".$id."' ");
		if($count->total > 0)
		{
			$data = DB::query('UPDATE `comment` SET
								`user_id` 		= "'.$user_id.'",
								`encounter_id` 	= "'.$encounter_id.'",
								`message` 		= "'.$message.'",
								`date_added`	= "'.$date_added.'"
								 WHERE `id`     = "'.$id.'" ;');
							
			$key = 'comment_'.$id;
			Cache::forget($key);
			return array('status'=>'success'); 
		}
		else if($count->total == 0)
		{
			return array('status'=>'error', 'msg'=>'no record found for this id');	
		}	
		
	}
	

/************* Comment Like *******************/
	public function like_comment($param)
	{
		$id = $param['id'];
		
		$count = DB::first("SELECT count(id) as total FROM `comment` WHERE `id` = '".$id."' ");
		if($count->total > 0)
		{
			$sql   = "UPDATE `comment` set `like_count` = `like_count` + 1 WHERE id = '".$id."'";	
			$user = DB::query($sql);
				
			return array('status'=>'success');
		}
		else
		{
			return array('status'=>'error', 'msg'=>'no record found for this id');	
		}
	}
	
}

?>
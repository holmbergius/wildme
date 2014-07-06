<?php
class CommentLikes 
{
	/*******************************Get comment Likes******************************/
	public function get_comment_like($id)
	{
		
		   $count = DB::first("select COUNT(id) as total from comment_like where `comment_id` = '".$id."' Limit 1;");

		   if($count->total>0)
		   {
				$data = DB::first("select * from comment_like where `comment_id` = '".$id."' Limit 1; ");
		   }
		   else
		   {
				return false;   
		   } 

		return array('record' => $data,'totalrecords' => '1','status' => 'success');
	}


	/*******************************total Likes******************************/

	public function get_total_likes($id)
	{

		$comment_id		= $id;

		if($comment_id != '')
		{
			$count = DB::first("select COUNT(id) as total from `comment_like` where `comment_id` = '".$comment_id."' ");
			if($count->total > 0)
			{

				$data = $count->total;
				$status = "success";
				return array('status' => 'success', 'data'=> $data);
			}
		}
		else
		{
			return array('status' => 'error', 'msg'=>'ID does not exist');
		}
		
	}

	/*******************************get Like status******************************/

	public function get_like($user_id,$comment_id)
	{
		
		if($user_id != '')
		{
			$sqlpart =" AND ( comment_id = ".$comment_id." ) "; 
			$count = DB::first("select COUNT(id) as total from `comment_like` where `user_id` = '".$user_id."' $sqlpart Limit 1;");

			if($count->total > 0)
			{
				$data = "Yes";

			}
			else
			{
				$data = "No";
			}

			return array('status' => 'success', 'data'=> $data);
		}	
	}


	/*******************************Add Comment******************************/

	public function post_like($param)
	{

		$status ="error";
		$date_added	= date("Y-m-d H:i:s");
		
			
		$id	 = DB::table('comment_like')->insert(array('user_id' 	=> $param['user_id'],
														 'comment_id' 	=> $param['comment_id'],
														 'date_added' 	=> $date_added
														));

		if($id > 0)
		{
			$key = 'commentLike_'.$id;
			$data['id'] 		 	= $id;
			$data['user_id']	 	= $param['user_id'];
			$data['comment_id']		= $param['comment_id'];
			$data['date_added']  	= $date_added;
			
			Cache::forever($key,$data);
			$status = 'success';

		}
			return array('status'=>$status);	
	}

	/*******************************Update Comment******************************/

	public function del_unlike($param)
	{
		$comment_id = $param['comment_id'];
		$user_id = $param['user_id'];

		$status ="error";
		$date_added	= date("Y-m-d H:i:s");

		$sqlpart =" AND ( `comment_id` = ".$comment_id." ) "; 
		$count = DB::first("select id,COUNT(id) as total from `comment_like` where `user_id` = '".$user_id."' $sqlpart Limit 1;");

		if($count->total > 0)
		{
			$id = $count->id;
			$data = DB::table('comment_like')->where('id','=',$id)->delete();

			return array('status'=>'success'); 
		}
		else if($count->total == 0)
		{
			return array('status'=>'error', 'msg'=>'no record found for this id');	
		}	
	}



}
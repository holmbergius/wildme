<?php
class ReportAbuse 
{
	/*******************************Add Report******************************/
	public function add_report($param)
	{
		$comment_id		= $param['comment_id'];
		$reporter_id	= $param['reporter_id'];
		$date_added 	= date("Y-m-d H:i:s");
				
		DB::table('report_abuse')->insert(array('comment_id' => $comment_id,'reporter_id' => $reporter_id,'date_added' => $date_added));	
		return array('status' => 'success', 'msg'=>'Record has been added successfully');
		
	}
	
	/*******************************Delete Report******************************/
	public function del_report($id)
 	{
    	
		$key = 'report_'.$id;
		$count = DB::table('report_abuse')->where('id','=',$id)->count();
		if($count > 0)
		{
			$data = DB::table('report_abuse')->where('id','=',$id)->delete();
		
			Cache::forget($key);
			
			$msg 		= 'Record has been deleted successfully';
			$status 	= 'success';
			return array('status' => $status, 'msg' => $msg);
			
		}
		else
		{
			$status = 'error';	
			$msg = 'No record found for this id';
			return array('status' => $status, 'msg' => $msg);
		} 
 
 	}

 	/*******************************Get All Reports******************************/
 	public function get_all_reports($param)
	{
		$sortby			 = Utility::mysql_query_string($param['sortby']);
		$orderby 		 = Utility::mysql_query_string($param['orderby']);
		$offset 		 = Utility::mysql_query_string($param['offset']);
		$limit 		 	 = Utility::mysql_query_string($param['limit']);

		$count  = DB::first("select COUNT(id) as total from `report_abuse`");
		$data   = array();
 		$status = 'error';
 
		if($count->total>0)
		{
			$sql   = "select id from report_abuse order by $sortby $orderby limit $offset,$limit ";
			$report = DB::query($sql);
			$user_repo = new  User;
			$animal = new Animal;
			$comments_repo = new Comment;
			$encounter_repo = new Encounter;
			
			foreach($report as $ind => $value)
			{
				$reportData	= $this->get_single_report($value->id);	
				$temp 			= json_encode($reportData);
				$reportData 	= json_decode($temp, true);
				$date		 	 = date('M d,Y',strtotime($reportData['record']['date_added']));
				$data[]  		= $reportData['record'];
			
				
				$data[$ind]['reporter_data'] 	= $user_repo->get_user($data[$ind]['reporter_id']);
				$data[$ind]['comment_data'] 	= $comments_repo->get_comment($data[$ind]['comment_id']);
				$data[$ind]['commenter_data'] 	= $user_repo->get_user($data[$ind]['comment_data']['record']->user_id);
				$data[$ind]['encounter_data']   = $encounter_repo->get_encounter($data[$ind]['comment_data']['record']->encounter_id);
				$data[$ind]['animal_data'] 		= $animal->get_animal($data[$ind]['encounter_data']['record']->animal_id);
				$data[$ind]['message'] 			=  $data[$ind]['comment_data']['record']->message;
				$data[$ind]['report_date'] = $date;


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

	/*******************************Get Single Report******************************/

	public function get_single_report($id)
	{
		
		$key  = 'report_'.$id;
		$data = Cache::get($key);
		if($data==false || $data==NULL)
		{
		   $count = DB::first("select COUNT(id) as total from `report_abuse` where `id` = '".$id."' Limit 1;");

		   if($count->total>0)
		   {
				$data = DB::first("select * from `report_abuse` where `id` = '".$id."' Limit 1; ");
				Cache::forever($key, $data);
		   }
		   else
		   {
				return false;   
		   } 
		}
		return array('record' => $data,'totalrecords' => '1','status' => 'success');
	}	
 	
}
?>
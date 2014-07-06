<?php
class Reminder 
{
	//Add records
	public function add_reminder($param)
	{
		$curr_data = date('Y-m-d');
		$cur_date = date('Y-m-d',strtotime($curr_data.'+'.$param['interval'].' '.$param['period'].''));		
		$id = DB::table('reminder')->insert_get_id(array('title' 		  => $param['title'],
														  'interval' 	  => $param['interval'],
														  'period'  	  => $param['period'],
														  'template' 	  => $param['template'],
														  'date_added' 	  => date("Y-m-d H:i:s"),
														  'reminder_date' => $cur_date
														));									
		if($id > 0)
		{	
			$key = 'reminder_'.$id;
			$data['id']		    	= $id;
			$data['title'] 			= $param['title'];
			$data['interval'] 		= $param['interval'];
			$data['period'] 		= $param['period'];
			$data['template'] 		= $param['template'];
			$data['date_added'] 	= date("Y-m-d H:i:s");
			$data['reminder_date'] = $cur_date;
			
			Cache::forever($key,$data);
			$status = 'success';
			$msg = 'Record has been added successfully';
		}
			return array('status'=>$status, 'msg'=>$msg);	
		
	}
	
	//Update Records
    public function update_reminder($param)
  	{
  		$id = $param['id'];
		$title = $param['title'];
		$interval = $param['interval'];
		$period = $param['period'];
		$template = $param['template'];
		$date_added = date("Y-m-d H:i:s");
		$curr_data = date('Y-m-d');
		$cur_date = date('Y-m-d',strtotime($curr_data.'+'.$interval.' '.$period.''));	
		
		$count = DB::first('select COUNT(id) as total from reminder where id="'.$id.'"');
		if($count->total>0)
		{
			$key = 'reminder_'.$id;
			Cache::forget($key);


			
			$data = DB::table('reminder')->where('id', '=', $id)->update(array('title'=>$title, 'interval' => $interval , 'period' => $period , 'template' => $template , 'date_added' => $date_added ,'reminder_date' => $cur_date));
			
			$status = 'success';	
			$msg = 'Record has been updated successfully';
			return array('status' => $status, 'msg'=>$msg);
		}
		else 
		{
			$status = 'error';	
			$msg = 'No record found for this id';
			return array('status' => $status, 'msg'=>$msg);
		}
					
  	}

	/*************get all reinders*******************/
	public function get_all_reminders($param)
	{
		$keyword		 = Utility::mysql_query_string($param['keyword']);
		$sortby			 = Utility::mysql_query_string($param['sortby']);
		$orderby 		 = Utility::mysql_query_string($param['orderby']);
		$offset 		 = Utility::mysql_query_string($param['offset']);
		$limit 		 	 = Utility::mysql_query_string($param['limit']);
		$id				 = Utility::mysql_query_string($param['id']);
		$sqlpart='';
	
		if ($keyword!='') $sqlpart .=" WHERE (title LIKE '%$keyword%') ";
		else if ($id != '') $sqlpart .= "WHERE `id` = '".$id."' ";
		$count  = DB::first("select COUNT(id) as total from reminder $sqlpart");
		$data   = array();
 		$status = 'error';
 
		if($count->total>0)
		{
			$sql   = "select id from reminder $sqlpart order by $sortby $orderby limit $offset,$limit ";	
			$reminder = DB::query($sql);
	 
			foreach($reminder as $ind => $value)
			{
				$reminderData	= $this->get_reminder($value->id);	
		
				$data[] = $reminderData['record'];
		
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
	

	
	
/************************Get single Category Detail*******************************/	
	public function get_reminder($id)
	{
		
		$key  = 'reminder_'.$id;
		//$data = Cache::forget($key);
		$data = Cache::get($key);
		//$data=false;
		if($data==false || $data==NULL)
		{
		   $count = DB::first("select COUNT(id) as total from reminder where `id` = '".$id."' Limit 1;");

		   if($count->total>0)
		   {
				$data = DB::first("select * from reminder where `id` = '".$id."' Limit 1; ");
				Cache::forever($key, $data);
		   }
		   else
		   {
				return false;   
		   } 
		}
		return array('record' => $data,'totalrecords' => '1','status' => 'success');
	}	
	

	//Delete Record
	public function del_reminder($id)
 	{
    	
		$key = 'reminder_'.$id;
		$count = DB::table('reminder')->where('id','=',$id)->count();
		if($count > 0)
		{
			$data = DB::table('reminder')->where('id','=',$id)->delete();
		
			Cache::forget($key);
			
			$msg 		= 'Reminder has been deleted successfully';
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

 	
}
?>
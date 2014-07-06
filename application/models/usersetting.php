<?php
class UserSetting {

	//Add records
	public function add_setting($param)
	{		
		$id = DB::table('user_setting')->insert_get_id(array('uid' 		=> $param['uid'],
														  'animal_id' 	=> $param['animal_id']
														));									
		if($id > 0)
		{	
			$key = 'userSetting_'.$id;
			$data['id']		    	= $id;
			$data['uid'] 			= $param['uid'];
			$data['animal_id'] 		= $param['animal_id'];
						
			Cache::forever($key,$data);
			$status = 'success';
			$msg = 'Record has been added successfully';
		}
			return array('status'=>$status, 'msg'=>$msg);	
		
	}	
	
	//Allow Posting
	public function posting_update($param)
	{
		$id				= $param['id'];
		$start			= $param['start'];
		$stop			= $param['stop'];
		
		$count = DB::first("SELECT count(id) as total FROM `user_setting` WHERE `id` = '".$id."' ");
		if($count->total > 0)
		{
			$key = 'userSetting_'.$id;
			Cache::forget($key);
			
			if($start == 1){
					$sql   = "UPDATE `user_setting` set `allow_posting` = '1' WHERE id = '".$id."'";	
					$user = DB::query($sql);
			}else if($stop == 1){
					$sql   = "UPDATE `user_setting` set `allow_posting` = '0' WHERE id = '".$id."'";	
					$user = DB::query($sql);
				}
			
			return array('status'=>'success');
		}else
			{
				return array('status'=>'error', 'msg'=>'No record found for this id');	
			}
	}
}
?>

	
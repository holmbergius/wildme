<?php

class InviteFriends{
	
	public function create_invite_friends($param)
	{
		$date_added	= date("Y-m-d H:i:s");
		$id = DB::table('invite_friends')->insert_get_id(array('from_uid' 	   => $param['from_uid'],
																'to_uid'  	   => $param['to_uid'],
																'request_id'   => $param['request_id'],
																'status'       => $param['status'],
																'date_send'    => $date_added,
																'date_updated' => $date_added
																 ));
																
		if($id > 0 )
		{
			$key = 'invite_friends_'.$id;
			$data['id'] 		  = $id;
			$data['from_uid'] 	  = $param['from_uid'];
			$data['to_uid']   	  = $param['to_uid'];
			$data['request_id']   = $param['request_id'];
			$data['status'] 	  = $param['status'];
			$data['date_send'] 	  = $date_added;
			$data['date_updated'] = $date_added;
			
			Cache::forever($key,$data);
			$status = 'success';
		}
			return array('status'=>$status);
	}
	
}

?>
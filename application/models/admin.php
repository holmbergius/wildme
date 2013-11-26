<?php
class Admin {
/*****************************************Get Admin data*********************************************/
	public function get_admin($username, $password)
	{
	
		$username = Utility::mysql_query_string($username);
		$password = Utility::mysql_query_string($password);
	
	 	$sql = "SELECT `admin_id` FROM `control` WHERE `username` = '".$username."' AND `password` = '".$password."' ";
		$adminData = DB::first($sql);
		//$adminData = DB::table('control')->select('id')->where('username' , '=' , $username)->or_where('password' , '=' , $password)->first();
	 	
		
	 	   if($adminData)
		   {	
		  	   @session_start();
			   Session::put('s_admin_id', $adminData->admin_id);
			   $s_admin_id = Session::get('s_admin_id');
			   $_SESSION['s_admin_id'] = $s_admin_id;
				
				return array('s_admin_id' => $s_admin_id ,'status' => 'success');
		   }
		   else
		   {
		 		return array('s_admin_id' => 0 ,'status' => 'error');
				//return false;   
		   } 
	}
/*****************************************Change Password*********************************************/
  public function update_password($param)
    {
  $id        = Utility::mysql_query_string($param['admin_id']);
  $password     = Utility::mysql_query_string($param['password']);
  $new_password = Utility::mysql_query_string($param['new_password']);
  
 
     $sql = 'select COUNT(admin_id) as total from control where `admin_id` = "'.$id.'" and `password` = "'.$password.'";';
  $count = DB::first($sql);
  //$count = DB::table('control')->where('admin_id' , '=' , $id)->where('password' , '=' , $password)->count();
  
     if($count->total>0)
     { 
     
     //$data = DB::query('UPDATE control set  `password` = "'.$new_password.'" where `id` = "'.$id.'";');
     $data =DB::table('control')->where('admin_id' , '=' , $id)->update(array('password' => $new_password));
     return array('status' =>'success');  
     }
     else
     {
      return array('status'=> 'error', 'msg'=>'Wrong password');   
     } 
    }
/*****************************************Admin Logout*********************************************/
	public function logout()
	  {
		  Session::flush();
	  }


}
?>
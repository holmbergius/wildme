<?php
class Login  {

	//Check user exist in database or not
	public function userExist($id)
	{
		$sql = "Select IFNULL(COUNT(id),0) as userexist from user where id = '".$id."'";
		$row = DB::first($sql);
		if($row->userexist < 1)
		{
			$userExist = false;
		}
		else
		{
			$userExist = true;
		}
		return $userExist;
		
	}
	//Get user details from facebook
	public function getUserDetailsFromFacebook($id, $accessToken)
	{
		$url	  = "https://graph.facebook.com/me?access_token=".$accessToken;
		//Get data from facebook
		try{
		//$jsonData = File::get($url);	
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		$contents = curl_exec($ch);
		$pageData = json_decode($contents);
		//object to array
		$objtoarr = get_object_vars($pageData);
		curl_close($ch);
	
		}catch(Exception $e){}
		$date_curr = date('Y-m-d');
		$objtoarr['age'] =0;
		if(isset($objtoarr['birthday']))
		{
			//$birthday = explode('/',$objtoarr['birthday']);
			$old_date = date('Y-m-d', strtotime($objtoarr['birthday']));
			$diff = floor($this->timeDiff($old_date,$date_curr)/365);
			$objtoarr['age'] = $diff ;
		}
		
		return $objtoarr;
	}
	
	public function getUserDetails($id )
	{
		/*$sql 	= "Select id from user where `facebook_id` = ".$id;
		$row 	= DB::first($sql);*/
			$short_name ='';
		$user 	= new User;
		$udata	= $user->get_user($id);
		$temp 	= json_encode($udata);
		$udata 	= json_decode($temp, true);
		$data	= $udata['record'];
	
		@session_start();
		//Session::put('name',   $data2['name']);
		Session::put('fullname', $data['name']);
		Session::put('id', $id);
		Session::put('join_date', date('M, Y',strtotime($data['date_added'])));

			$short_name = explode(' ',$data['name']);
						if(isset($short_name[1]))
						{
							Session::put('name', $short_name[0].' <br /> '.$short_name[1]);
							
						}
						else
						{
							Session::put('name', $short_name[0]);
						}
		$short_name = Session::get('name');
		$data['short_name'] = $short_name;
		
		$_SESSION['s_user_id'] =$id;
		$_SESSION['s_name'] =$data['name'];
		$_SESSION['s_short_name'] =$short_name;
		$_SESSION['s_join_date'] =date('M, Y',strtotime($data['date_added']));
			
		return array('data'=>$data,'msg'=>'signin_fb', 'status'=> 'success');
	}
	
	public function timeDiff($firstTime,$lastTime)
	{
		// convert to unix timestamps
		$firstTime=strtotime($firstTime);
		$lastTime=strtotime($lastTime);
		
		// perform subtraction to get the difference (in seconds) between times
		$timeDiff=$lastTime-$firstTime;
		$timeDiff = $timeDiff/86400;
		// return the difference
		return $timeDiff;
	}
	
	public function update_user_fb_friends($last_id,$access_token)
	{
		// this function update user friends in database
		 $url   = "https://graph.facebook.com/me/friends?access_token=".$access_token;
		 //Get data from facebook
		 try{
		 //$jsonData = File::get($url); 
		 $ch = curl_init($url);
		 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		 curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
		 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		 $contents = curl_exec($ch);
		 $pageData = json_decode($contents);
		 //object to array
		 $objtoarr2 = get_object_vars($pageData);
		 
		 curl_close($ch);
	
		 }catch(Exception $e){}
		 $friend_count = count($objtoarr2['data']);
		 $friends = $objtoarr2['data'];
		
		
		$app_user 	   = new User;
		$sql = "select id as id from user ";
		$fb_user_ids = DB::query($sql);
		$fb_users = array();
	
		foreach ($fb_user_ids as $fb_user_id) {
			if(!is_array($fb_user_id)) $fb_user_id = get_object_vars($fb_user_id);
			$record		=$app_user->get_user($fb_user_id['id']); 
			if(!is_array($record['record'])) $record['record'] = get_object_vars($record['record']);
			$fb_users[] = $record['record']['facebook_id'];
		}
	
		$user_friends_ids = array();
	
	
		 for ($i=0; $i < $friend_count; $i++) { 
			if(!is_array($friends[$i])) $friends[$i] = get_object_vars($friends[$i]);
		 
	
			if(in_array($friends[$i]['id'], $fb_users)){
			 $this->friends_ids_manipulation( $friends[$i]['id'], $last_id);
			 $friend_userid = DB::first("select id as id from user where facebook_id ='".$friends[$i]['id'] . "' ");
			 $user_friends_ids[] = $friend_userid->id;
			}
		 }
		
		$result = DB::table('user')->where('id', '=', $last_id)->update(array('friend_ids' => implode(",", $user_friends_ids) ) );
		
		$key  = 'wildme_user_'.$last_id;
		$data = Cache::forget($key);
		return true;
	}
	
	public function  friends_ids_manipulation( $friend, $new_user)
	{
		// select id from db whrer fbid is friend's id
		// then select record of that id
		// then update its friend ids column
		
		$sql = "select id, friend_ids from user where id = '$friend' ";
		$result = DB::first($sql);
		
		$friend_ids = ($result->friend_ids == NULL)? $new_user : $result->friend_ids . ',' . $new_user;
		
		DB::table('user')->where('id', '=', $result->id)->update(array('friend_ids' => $friend_ids));
		
		$key  = 'wildme_user_'.$result->id;
		$data = Cache::forget($key);
	}
 
	//Check user exist in database or not

	public function userSignin($facebook_id)
	{
		$short_name ='';
		$name	= '';
		$user_id= '';
		$userExist = array();
		$sql = "Select IFNULL(COUNT(id),0) as userexist from user WHERE `id` = '".$facebook_id."' ";
		$row = DB::first($sql);
		if($row->userexist < 1)
		{
			return array('status'=>'error','msg'=>'email does not exist');
		}
		else
		{
			$user 	= new User;
			$udata	= $user->get_user($facebook_id);		
			$udata	= json_decode(json_encode($udata), TRUE);
			
			//if(!is_array($udata['record'])) $udata['record'] = get_object_vars($udata['record']);
	
			$userExist 	= $udata['record'];
			$user_id	= $userExist['id'];
			$name		= $userExist['name'];
			
			$short_name = explode(' ',$userExist['name']);
			if(isset($short_name[1]))
			{
				Session::put('short_name', $short_name[0].' '.$short_name[1]);
				
			}
			else
			{
				Session::put('short_name', $short_name[0]);
			}
	
			$status		= 'success';
			Session::put('name', $userExist['name']);
			Session::put('user_id', $userExist['id']);
			
			$_SESSION['s_user_id'] =$userExist['id'];
			$_SESSION['s_name'] =$userExist['name'];
		}
		
		return array('user_id'=>$user_id,'name'=> $name, 'status'=>$status);
	}

	
/********************************User Sign up/Sign in***************************************************/
	
	public function user_signup($param)
	{
		$status =  '';
		$name			=	$param['name'];
		$email			=	$param['email'];
		$facebook_id	=	$param['id'];
		$access_token	=	$param['access_token'];
		$age		 	=	$param['age'];
		$gender			=	$param['gender'];
		$date_added		=	date("Y-m-d h:i:s");
		$check_user		=	DB::table('user')->where('email','=',$email)->count();
		
		if ($check_user == 0)
		{
			$id    		= 	DB::table('user')->insert_get_id(array( 'name' 			=> $name,
																		'email' 		=> $email,
																		'id' 			=> $facebook_id,
																		'status' 		=> 'Active',
																		'date_added' 	=> $date_added,
																		'gender' 		=> $gender,
																		'age' 			=> $age
																			));															
		
			$key 					= 'wildme_user_'.$id; //set mem key
			$data['id'] 			= $facebook_id;
			$data['name'] 			= $name;
			$data['email'] 			= $email;
			$data['status'] 		= 'Active';
			$data['date_added'] 	= $date_added;
			$data['gender'] 		= $gender;
			$data['age'] 			= $age;
			
			Cache::forever($key, $data); // UPDATE memcache
		
			if($id <0)
			{
				$status =  'error';
			}
			else
			{
				$url   = "https://graph.facebook.com/me/friends?access_token=".$access_token;
				//Get data from facebook
				 try{
				 //$jsonData = File::get($url); 
				 $ch = curl_init($url);
				 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				 curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
				 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
				 $contents = curl_exec($ch);
				 $pageData = json_decode($contents);
				 //object to array
				 $objtoarr2 = get_object_vars($pageData);
				 
				 curl_close($ch);
			
				 }catch(Exception $e){}

				 $friend_count = count($objtoarr2['data']);
				 
				 $friends = $objtoarr2['data'];
				
			
				$app_user 	   	= new User;
				$sql 			= "select id from user";
				$fb_user_ids 	= DB::query($sql);
				$fb_users 		= array();
			
				foreach ($fb_user_ids as $fb_user_id) {
					if(!is_array($fb_user_id)) $fb_user_id = get_object_vars($fb_user_id);
					$record		=$app_user->get_user($fb_user_id['id']); 
					if(!is_array($record['record'])) $record['record'] = get_object_vars($record['record']);
					$fb_users[] = $record['record']['id'];
				}
			
				$user_friends_ids = array();
			
				 for ($i=0; $i < $friend_count; $i++) { 
					if(!is_array($friends[$i])) $friends[$i] = get_object_vars($friends[$i]);
				 
			
					if(in_array($friends[$i]['id'], $fb_users)){
					 $this->friends_ids_manipulation( $friends[$i]['id'], $id);
					 $friend_userid = DB::first("select id from user where id ='".$friends[$i]['id'] . "' ");
					 $user_friends_ids[] = $friend_userid->id;
					}
				 }
				
				$result = DB::table('user')->where('id', '=', $id)->update(array('friend_ids' => implode(",", $user_friends_ids) ) );
				
				$key  = 'wildme_user_'.$id;
				$data = Cache::forever($key,false);
				//friend logic end.
				
				$this->userSignin($facebook_id);
				$status =  'success2';
			}
		}
		else
		{
		  $this->userSignin($facebook_id);
		  $status =  'success';
		}
		
		 
	
	
		return array('status' => $status , 'name'=> $name, 'id'=> $facebook_id);
	}

	
}
?>
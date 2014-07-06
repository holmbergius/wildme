<?php

class Follow{
public $gfile = 0;

public function getdirectorysize($path)
	{
  //using the opendir function
    $dir_handle = @opendir($path) or die("Unable to open $path");
   
    //Leave only the lastest folder name
   
   
    $dirname = basename($path);
  
    //display the target folder.
    //echo ("<li>$dirname\n");
    //echo "<ul>\n";
	
    while (false !== ($file = readdir($dir_handle)))
    {
        if($file!="." && $file!="..")
        {
            if (is_dir($path."/".$file))
            {
                //Display a list of sub folders.
			   $this->getdirectorysize($path."/".$file);
			   $this->gfile++;
            }
            else
            {
                //Display a list of files.
               // echo "<li>$file</li>";
			   
            }
        }
    }
    //echo "</ul>\n";
   // echo "</li>\n";
   
    //closing the directory
    closedir($dir_handle);
	return $this->gfile;
	}
	
	public function create_follow($param)
	{
		
		$status = 'error';
		
		if($param['user_type'] == 'website'){
			
			$count_animal = DB::first("SELECT COUNT(id) as total,id from `animal` where label = '".$param['animal_id']."'");
		
			if($count_animal->total > 0){
				$param['animal_id'] = $count_animal->id;
			}else{
				return array('status'=>$status);
			}
		
		}
	
		$count = DB::table('follow')->where('user_id','=',$param['user_id'])->where('animal_id','=',$param['animal_id'])->count('id');
		
		if($count == 0)
		{
			$id = DB::table('follow')->insert_get_id(array('user_id' 	   => $param['user_id'],
														   'animal_id'     => $param['animal_id']
																	));
																
			if($id > 0 )
			{
				$key = 'follow_'.$id;
				$data['id'] 		  = $id;
				$data['user_id'] 	  = $param['user_id'];
				$data['animal_id']    = $param['animal_id'];     
				
				$animal = new Animal;
				$increment = $animal->get_followincrement($param['animal_id']);
				
				$param1['user_id'] = $param['user_id'];
				$param1['ref_id'] = $param['animal_id'];
				$param1['type'] = 'follow';
				
				$log = new UserLog;
				$record = $log->create_user_log($param1);
				
				$param2['uid'] 	  		= $param['user_id'];
				$param2['animal_id']    = $param['animal_id'];  
				   
				$user_setting = new UserSetting;
				$record = $user_setting->add_setting($param2);
								
				Cache::forever($key,$data);
				$status = 'success';
			}

		}
		return array('status'=>$status);
	}
	
	
	public function get_follow($param)
	{
		$status = 'error';
		$checkdb 	=	DB::table('encounters')->where('user_id','=',$param['user_id'])->where('animal_id','=',$param['animal_id'])->count();
		if($checkdb == 1)
		{
			$status = 'success';
		}
		return array('status'=>$status);
	}
	
	public function del_follow($param)
	{
		$status = 'error';	

		$count = DB::table('follow')->where('user_id','=',$param['user_id'])->where('animal_id','=',$param['animal_id'])->count('id');
		if($count > 0)
		{
			$id = DB::table('follow')->where('user_id','=',$param['user_id'])->where('animal_id','=',$param['animal_id'])->get('id');
			
			foreach($id as $ind => $value)
			{
				$data = DB::table('follow')->where('id','=',$value->id)->delete();
				$key = 'follow_'.$value->id;
				
				$param1['user_id'] = $param['user_id'];
				$param1['ref_id']  = $param['animal_id'];
				$param1['type']    = 'unfollow';  
				
				$log = new UserLog;
				$record = $log->create_user_log($param1);
				
				Cache::forget($key);
				
				$animal = new Animal;
				$animal->get_followdecrement($param['animal_id']);
			}
			$msg 		= 'record deleted';
			$status 	= 'success';
		}
		else
		{
			$msg = 'no record found for this id';
		} 
		
		return array('status' => $status, 'msg' => $msg);
	}
	
	
	//get all followers against animal_id
	public function get_followers($param)
	{
		$limit		= Utility::mysql_query_string($param['limit']);
		$offset     = Utility::mysql_query_string($param['offset']);
		$sortby     = Utility::mysql_query_string($param['sortby']);
		$orderby    = Utility::mysql_query_string($param['orderby']);
		
		$data ='';
		$status = 'error';	
		$count_total = DB::first("SELECT COUNT(id) as total from `follow` where animal_id = '".$param['animal_id']."'  AND user_id IN (select id from user)");
		if($count_total->total > 0)
		{
			$sql = DB::query("SELECT user_id from `follow` where animal_id = '".$param['animal_id']."' AND user_id IN (select id from user) order by $sortby $orderby limit $offset,$limit ");
			
			foreach($sql as $ind => $value)
			{
				$users		= new User;
				$userData	= $users->get_user($value->user_id);			
				$temp 	  	= json_encode($userData);
				$userData 	= json_decode($temp, true);
						
				$count		= DB::first("SELECT COUNT(id) as total from `follow` where `user_id` = '".$value->user_id."' ");
						
				$userData['record']['following'] = $count->total;
						
				$data[] 	= $userData['record'];
			}
			$status = 'success';	
		}
		return array('records'=>$data, 'totalrecords'=>$count_total->total,  'status'=>$status);
	}
	
	//get all followers against animal_id
	public function get_user_followers($param)
	{
		$limit		= Utility::mysql_query_string($param['limit']);
		$offset     = Utility::mysql_query_string($param['offset']);
		$sortby     = Utility::mysql_query_string($param['sortby']);
		$orderby    = Utility::mysql_query_string($param['orderby']);
		
		$data ='';
		$status = 'error';	
		$count = DB::first("SELECT COUNT(id) as total from `follow` where user_id = '".$param['profile_user_id']."' ");
		if($count->total > 0)
		{
			$sql = DB::query("SELECT animal_id from follow where user_id = '".$param['profile_user_id']."'  order by $sortby $orderby limit $offset,$limit ");
			
			foreach($sql as $ind => $value)
			{
				$ani 	 = new Animal;
				$out_ani = $ani->get_animal($value->animal_id);
				$out_ani = json_decode(json_encode($out_ani), TRUE);
				$data[$ind]['id'] 		= $value->animal_id;
				$data[$ind]['nick_name'] 	= $out_ani['record']['nick_name'];
				$data[$ind]['label'] 	= $out_ani['record']['label'];
				$data[$ind]['sex'] 			= $out_ani['record']['sex'];
				$data[$ind]['follow_count'] = $out_ani['record']['follow_count'];
				$data[$ind]['label'] = $out_ani['record']['label'];
				$ani 	 = new Category;
				$out_cat = $ani->get_category($out_ani['record']['category_id']);
				$out_cat = json_decode(json_encode($out_cat), TRUE);
				$data[$ind]['category_detail'] = $out_cat['record'];
				
				$data[$ind]['follow_check']   = 0;
				//follow check
				if($param['user_id'] != '')
				{
					$follow_count 	= DB::table('follow')->where('animal_id','=',$value->animal_id)->where('user_id','=',$param['user_id'])->count();
					if($follow_count > 0)
					{
						$data[$ind]['follow_check']   = 1;
					}
				}
				$status = 'success';	
			}
			
		}
		return array('records'=>$data, 'totalrecords'=>$count->total,  'status'=>$status);
	}
	
	//get all global followers against animal_id
	public function get_global_follows($param)
	{
		$data ='';
		$status = 'error';	
		$count_animal = DB::first("SELECT COUNT(id) as total,id, category_id from `animal` where label = '".$param['animal_id']."'");
		
		if($count_animal->total > 0)
		{
			$param['animal_id'] = $count_animal->id;
			
			$count_total = DB::first("SELECT COUNT(id) as total from `follow` where animal_id = '".$param['animal_id']."'");
			if($count_total->total > 0)
			{
				
				$cat_data = DB::query("SELECT * from `category` WHERE title = '". $count_animal->category_id."'");
				
				$cat_data		= json_encode($cat_data);
				$cat_data		= json_decode($cat_data, TRUE);
				
				$data = $cat_data;
				
				$status = 'success';	
			}
	
	}
		return array('category_data'=>$data, 'totalfollows'=>$count_total->total,  'status'=>$status);
	}

}

?>
<?php	

class Api_Controller extends Base_Controller {
	
	//define restful flag
	public $restful = true;


	public function get_all_animal()
	{
		$log    		= new Getrecord;
		$output   		= $log->get_all_animal();
		echo json_encode($output);
	}
	
	public function get_animal_cron()
	{
		$category_id   	= Input::get('category_id');
		$log    		= new Getrecord;

		$data   		= $log->get_animal($category_id);
		  
		$output = $data;
		echo json_encode($output);

	}
	
	public function get_encounter_cron()
	{
		$category_id   	= Input::get('category_id');
		$log    		= new Getrecord;
		$data   		= $log->get_encounter($category_id);
		$output 		= $data;
		echo json_encode($output);
		
	}
	
	public function get_media()
	{
		$category_id   	= Input::get('category_id');
		$log    		= new Getrecord;
		$data   		= $log->get_all_media($category_id);
		$output 		= $data;
		echo json_encode($output);
		
	}

	public function get_friends_cron()
	{
		$category_id   	= Input::get('category_id');
		$log    		= new Getrecord;
		$data   		= $log->get_occurrence($category_id);
		$output 		= $data;
		echo json_encode($output);
		
	}
	
	public function get_cronjob()
	{
		$category_id   	= Input::get('category_id');
		$log   			= new Cronjob;
		$data   		= $log->get_encounterCheck($category_id);
		$output 		= $data;
		echo json_encode($output);
		
	}
	
	
	public function get_encounter_check()
	{
		$log   			= new Getrecord;
		$data   		= $log->check_encounter();
		$output 		= $data;
		echo json_encode($output);
		
	}
	
	public function get_check_thumb()
	{
		$log   			= new Getrecord;
		$data   		= $log->check_thumb();
		$output 		= $data;
		echo json_encode($output);
		
	}
	
	public function get_profile_photo()
	{
		$log   			= new Getrecord;
		$data   		= $log->get_profile_photo();
		$output 		= $data;
		echo json_encode($output);
		
	}
 
 /***********************************************************************************************************************
----------Admin
***********************************************************************************************************************/


/**************Admin Login*****************************/
	public function get_admin()
	{

		$admin 		= new Admin;
		$username	= Input::get('username');
		$password	= Input::get('password');
		
	
		if($username==NULL)
		{
			return json_encode(array('status' => 'error', 'msg' => 'username must have some value'));	
		}
		else
		{
			$output = $admin->get_admin($username,$password);
			echo json_encode($output);
		}
	}
	
	
	
/**************Change Admin Password *****************************/	
	public function put_admin()
	 {
	 	$param['new_password']  = Input::get('newpassword');
		$param['password']      = Input::get('password');
		$param['admin_id']     	= Input::get('admin_id');
	  
		
	  if( $param['admin_id']==NULL)
	  {
	   //return error
	   return json_encode(array('status' => 'error', 'msg' => 'no record found for this id'));
	   
	  }
	  else
	  { 
	   	$user = new Admin;
	    $output = $user->update_password($param);
		echo json_encode($output);
	  }

 	}
/************** logout Admin *****************************/	
	  public function post_admin()
	  {
		  $user 	= new Admin;
		  $output 	= $user->logout();
		  
		  return json_encode(array('status' => 'success', 'msg' => 'Logout Successfully'));
	  }
	  



  /*************************** Category ******************************/

/******************Insert Category********************/
	
	public function post_category()
	{
		$param['title'] 	 = Input::get('title');
		$param['icon'] 		 = Input::get('icon');
		$param['color_code'] = Input::get('color_code');
		$param['type'] 		 = Input::get('type');
		$param['api_url']    = Input::get('api_url');
		$param['image_url']  = Input::get('image_url');
		
		if($param['title']=='' || $param['icon']== '' || $param['color_code']== '' || $param['type'] == '' || $param['api_url'] == '' || $param['image_url'] == '' )
		{
			return json_encode(array('status'=>'error','msg'=>'parameters incomplete'));	
		}
		
		else
		{
			$category = new Category;
			$output = $category->create_category($param);
			echo json_encode($output);	
		}
		
	}
	
/*************Update Category******************/
	public function put_category()
	{
		$param['id'] 		 = Input::get('id');
		$param['title'] 	 = Input::get('title');
		$param['icon'] 		 = Input::get('icon');
		$param['color_code'] = Input::get('color_code');
		$param['type'] 		 = Input::get('type');
		$param['api_url']    = Input::get('api_url');
		$param['image_url']  = Input::get('image_url');
		
		if($param['id']=='')
		{
			return json_encode(array('status'=>'error','msg'=>'id must have a value'));	
		}
		else
		{
			$category = new Category;
			$output = $category->update_category($param);
			echo json_encode($output);	
		}
	}
	
	
/***************Get Category************/	
 	public function get_category($id=NULL)
 	{
		//Get All Category Record
		
		$param['id']      = Input::get('id');
		$param['offset']  = Input::get('offset');
		$param['limit'] 	 = Input::get('limit');
		$param['keyword'] = Input::get('keyword');
		$param['sortby']  = Input::get('sortby');
		$param['orderby'] = Input::get('orderby');
		
		if($param['offset']==NULL)$param['offset']=0;
		if($param['limit']==NULL)$param['limit']=30;
		if($param['keyword']==NULL)$param['keyword']='';
		if($param['sortby']==NULL)$param['sortby']='id';
		if($param['orderby']==NULL)$param['orderby']='ASC';
		
		$category  =  new Category;
		$data = $category->get_categories($param);
		
		$output = $data;
		echo json_encode($output);
	}


/*****************Delete Category*******************/
	public function delete_category($id=NULL)
	{
		$id = Input::get('id');
		
		if($id==NULL || $id<=0)
		{
			return json_encode(array('status'=>'error','msg'=>'record not found for this id'));	
		}
		else
		{
			$category = new Category;
			$output = $category->del_category($id);
			echo json_encode($output);
		}
	}

 /***************************User Log ******************************/

/******************Insert Log********************/
	
	public function post_log()
	{
		$param['user_id'] 	 = Input::get('user_id');
		$param['ref_id'] 	 = Input::get('ref_id');
		$param['type'] 		 = Input::get('type');
		
		
		if($param['user_id']=='' || $param['ref_id']== '' || $param['type'] == '' )
		{
			return json_encode(array('status'=>'error','msg'=>'parameters incomplete'));	
		}
		
		else
		{
			$log = new Userlog;
			$output = $log->create_user_log($param);
			echo json_encode($output);	
		}
		
	}
	
	/***************Get Log************/	
 	public function get_log($id=NULL)
	{//Get All Log Record
	   $param['offset']	 = Input::get('offset');
	   $param['limit'] 	 = Input::get('limit');
	   $param['sortby']  = Input::get('sortby');
	   $param['orderby'] = Input::get('orderby');
	   $param['id']		 = Input::get('id');
	   $param['user_id'] = Input::get('user_id');
	   
	   if($param['offset'] == NULL) $param['offset']=0;
	   if($param['limit'] == NULL)  $param['limit']=30;
	   if($param['sortby'] == NULL) $param['sortby']='id';
	   if($param['orderby'] == NULL)$param['orderby']='ASC';
	   
	   if($param['user_id']=='')
		{
			return json_encode(array('status'=>'error','msg'=>'parameters incomplete'));	
		}
		else
		{
			$log  =  new Userlog ;
			$data = $log->get_user_logs($param);
			$output = $data;
			echo json_encode($output);
		}
	}
	
	/***************Check like Log************/	
	//to check if the user has liked a particular encounter.
 	public function get_check_like()
	{
	   	$param['ref_id']	= Input::get('ref_id');
	   	$param['user_id'] 	= Input::get('user_id');
	   
	   	if($param['user_id']=='' || $param['ref_id']== '' )
		{
			return json_encode(array('status'=>'error','msg'=>'parameters incomplete'));	
		}
		else
		{
			$log  =  new Userlog;
			$data = $log->check_like($param);
			echo json_encode($data);	
		}
	}

/*************************** Comment ******************************/

/******************Insert Comment********************/
	
	public function post_comment()
	{
		$param['user_id'] 	 	= Input::get('user_id');
		$param['encounter_id'] 	= Input::get('encounter_id');
		$param['message'] 		= Input::get('message');
				
		if($param['user_id']=='' || $param['encounter_id']== '' || $param['message']== '' )
		{
			return json_encode(array('status'=>'error','msg'=>'parameters incomplete'));	
		}
		
		else
		{
			$comment = new Comment;
			$output = $comment->create_comment($param);
			echo json_encode($output);	
		}
		
	}
	
/*************Update Comment******************/
	public function put_comment()
	{
		$param['id'] 		 = Input::get('id');
		$param['user_id'] 	 = Input::get('user_id');
		$param['encounter_id'] 		 = Input::get('encounter_id');
		$param['message'] = Input::get('message');
		
		if($param['id']=='')
		{
			return json_encode(array('status'=>'error','msg'=>'id must have a value'));	
		}
		else
		{
			$comment = new Comment;
			$output = $comment->update_comment($param);
			echo json_encode($output);	
		}
	}
	
	
/***************Get Comment************/	
 	public function get_comment($id=NULL)
	{ 
		//Get All Category Record
		$param['offset']		     = Input::get('offset');
		$param['limit'] 		     = Input::get('limit');
		$param['keyword'] 	     = Input::get('keyword');
		$param['sortby']  	     = Input::get('sortby');
		$param['orderby'] 	     = Input::get('orderby');
		$param['encounter_id']    = Input::get('encounter_id');
		$param['user_id']		 = Input::get('user_id');
		$param['id']			     = Input::get('id');
		
		if($param['offset'] == NULL)  $param['offset']=0;
		if($param['limit'] == NULL)   $param['limit']=30;
		if($param['keyword'] == NULL) $param['keyword']='';
		if($param['sortby'] == NULL)  $param['sortby']='id';
		if($param['orderby'] == NULL) $param['orderby']='ASC'; 
		
		$comment  =  new Comment;
		$data = $comment->get_comments($param);
		echo json_encode($data);
		
	}
		
		
		/*****************Delete Comment*******************/
	public function delete_comment($id=NULL)
	{
		$id = Input::get('id');
		
		if($id==NULL || $id<=0)
		{
			return json_encode(array('status'=>'error','msg'=>'record not found for this id'));	
		}
		else
		{
			$comment = new Comment;
			$output = $comment->del_comment($id);
			echo json_encode($output);
		}
	}

/*************************** Invite Friends ******************************/

/******************Insert Invite_Friends********************/
	
	public function post_invitefriends()
	{
		$param['from_uid'] 	   = Input::get('from_uid');
		$param['to_uid'] 	   = Input::get('to_uid');
		$param['request_id']   = Input::get('request_id');
		$param['status'] 	   = Input::get('status');
				
		if($param['from_uid']=='' || $param['to_uid']== '' || $param['status']== '' )
		{
			return json_encode(array('status'=>'error','msg'=>'parameters incomplete'));	
		}
		
		else
		{
			$invitefriends = new InviteFriends;
			$output = $invitefriends->create_invite_friends($param);
			echo json_encode($output);	
		}
		
	}
 
 /*************Update User Log******************/
	public function put_user_log()
	{
		$param['user_id'] 	 = Input::get('user_id');
		$param['ref_id'] 	 = Input::get('ref_id');
		$param['type'] 		 = Input::get('type');
		
		if($param['user_id']=='' || $param['ref_id']=='' || $param['type']=='' )
		{
			return json_encode(array('status'=>'error','msg'=>'parameters incomplete'));	
		}
		else
		{
			$log = new Userlog;
			$output = $log->update_user_log($param);
			echo json_encode($output);	
		}
	}
	
	
	
 	/***************Get Media************/ 
	public function get_media_table($id=NULL)
	{ 
		//define public Class Favorite
		$media  =  new Media;
		
		//Get All Records
		$id 	  = Input::get('id');
		$offset   = Input::get('offset');
		$limit    = Input::get('limit');
		$sortby   = Input::get('sortby');
		$orderby  = Input::get('orderby');
		$encounter_id  = Input::get('encounter_id');
		 
		if($offset==NULL)   $offset=0;
		if($limit==NULL)    $limit=30;
		if($sortby==NULL)   $sortby='id';
		if($orderby==NULL)  $orderby='ASC';
		if($encounter_id==NULL)  $encounter_id='';
		if($id==NULL)  $id='';


		$param['offset']   = $offset;
		$param['limit']    = $limit;
		$param['sortby']   = $sortby;
		$param['orderby']  = $orderby;
		$param['encounter_id']     = $encounter_id;
		$param['id']     = $id;


		$data = $media->get_medias($param);
	
		if($data!=false)
		{
			echo json_encode($data);
		}
		else
		{
			return json_encode(array('status'=>'error','msg'=>'no record found for this id'));
		}
	}

 	/***************Get Encounters************/ 
	public function get_encounter($id=NULL)
	{ 
		//define public Class Favorite
		$encounter  =  new Encounter;
		
		//Get All Records
		$id 	  = Input::get('id');
		$offset   = Input::get('offset');
		$limit    = Input::get('limit');
		$sortby   = Input::get('sortby');
		$orderby  = Input::get('orderby');
		$animal_id= Input::get('animal_id');
		$all_location= Input::get('all_location');
		 
		if($offset==NULL)   $offset=0;
		if($limit==NULL)    $limit=30;
		if($sortby==NULL)   $sortby='id';
		if($orderby==NULL)  $orderby='ASC';
		if($animal_id==NULL)  $animal_id='';
		if($id==NULL)  $id='';
		if($all_location==NULL)  $all_location='';


		$param['offset']   = $offset;
		$param['limit']    = $limit;
		$param['sortby']   = $sortby;
		$param['orderby']  = $orderby;
		$param['animal_id']    	 = $animal_id;
		$param['id']    		 = $id;
		$param['animal_list']    = Input::get('animal_list');
		$param['media_offset']   = Input::get('media_offset');
		$param['user_id']  		 = Input::get('user_id');
		$param['all_location']   = $all_location;


		$data = $encounter->get_encounters($param);
	
		if($data!=false)
		{
			echo json_encode($data);
		}
		else
		{
			return json_encode(array('status'=>'error','msg'=>'no record found for this id'));
		}
	}

 	/***************Get Animal Friends************/ 
	public function get_animal_friend($id=NULL)
	{ 
		//define public Class Favorite
		$friend  =  new AnimalFriend;
		
		//Get All Records
		$id 	  = Input::get('id');
		$offset   = Input::get('offset');
		$limit    = Input::get('limit');
		$sortby   = Input::get('sortby');
		$orderby  = Input::get('orderby');
		$animal_id= Input::get('animal_id');
		 
		if($offset==NULL)   $offset=0;
		if($limit==NULL)    $limit=30;
		if($sortby==NULL)   $sortby='id';
		if($orderby==NULL)  $orderby='ASC';
		if($animal_id==NULL)  $animal_id='';
		if($id==NULL)  $id='';


		$param['offset']   = $offset;
		$param['limit']    = $limit;
		$param['sortby']   = $sortby;
		$param['orderby']  = $orderby;
		$param['animal_id']= $animal_id;
		$param['id']	   = $id;
		$param['user_id']  = Input::get('user_id');


		$data = $friend->get_friends($param);
	
		if($data!=false)
		{
			echo json_encode($data);
		}
		else
		{
			return json_encode(array('status'=>'error','msg'=>'no record found for this id'));
		}
	}

 	/***************Get Animal ************/ 
	public function get_animal($id=NULL)
	{ 
		//define public Class Favorite
		$animal  =  new Animal;
		
		//Get All Records
		$id 	  = Input::get('id');
		$offset   = Input::get('offset');
		$limit    = Input::get('limit');
		$sortby   = Input::get('sortby');
		$orderby  = Input::get('orderby');
		$keyword  = Input::get('keyword');
		$animal_id= Input::get('animal_id');
		$category_id= Input::get('category_id');
		$user_id= Input::get('user_id');
		 
		if($offset==NULL)   $offset=0;
		if($limit==NULL)    $limit=30;
		if($sortby==NULL)   $sortby='id';
		if($orderby==NULL)  $orderby='ASC';
		if($animal_id==NULL)$animal_id='';
		if($keyword==NULL)  $keyword='';
		if($category_id==NULL)$category_id='';
		if($id==NULL)  $id='';
		if($user_id=='')  $user_id='';


		$param['offset']   = $offset;
		$param['limit']    = $limit;
		$param['sortby']   = $sortby;
		$param['orderby']  = $orderby;
		$param['animal_id']= $animal_id;
		$param['keyword']  = $keyword;
		$param['id']	   = $id;
		$param['category_id']= $category_id;
		$param['user_id']= $user_id;


		$data = $animal->get_animals($param);
	
		if($data!=false)
		{
			echo json_encode($data);
		}
		else
		{
			return json_encode(array('status'=>'error','msg'=>'no record found for this id'));
		}
	}

 	/***************Get Animal ************/ 
	public function get_animal_media($id=NULL)
	{ 
		//define public Class Favorite
		$media  =  new Media;
		
		//Get All Records

		$offset   = Input::get('offset');
		$limit    = Input::get('limit');
		$sortby   = Input::get('sortby');
		$orderby  = Input::get('orderby');
		$animal_id= Input::get('animal_id');
		 
		if($offset==NULL)   $offset=0;
		if($limit==NULL)    $limit=30;
		if($sortby==NULL)   $sortby='id';
		if($orderby==NULL)  $orderby='ASC';
		if($animal_id==NULL)  $animal_id='';

		$param['offset']   = $offset;
		$param['limit']    = $limit;
		$param['sortby']   = $sortby;
		$param['orderby']  = $orderby;
		$param['animal_id']= $animal_id;

		$data = $media->get_animal_media($param);
	
		if($data!=false)
		{
			echo json_encode($data);
		}
		else
		{
			return json_encode(array('status'=>'error','msg'=>'no record found for this id'));
		}
	}
	

 /*************************** Follow ******************************/

/******************Insert Follow********************/
	
	public function post_follow()
	{
		$param['user_id'] 	   = Input::get('user_id');
		$param['animal_id']    = Input::get('animal_id');
				
		if($param['user_id']=='' || $param['animal_id']== '' )
		{
			return json_encode(array('status'=>'error','msg'=>'parameters incomplete'));	
		}
		
		else
		{
			$follow = new Follow;
			$output = $follow->create_follow($param);
			echo json_encode($output);	
		}
		
	}

/*****************Delete Follow*******************/
	public function delete_follow($id=NULL)
	{
		$param['user_id'] 	   = Input::get('user_id');
		$param['animal_id']    = Input::get('animal_id');
		
		if($param['user_id']=='' || $param['animal_id']== '' )
		{
			return json_encode(array('status'=>'error','msg'=>'parameters incomplete'));	
		}
		else
		{
			$follow = new Follow;
			$output = $follow->del_follow($param);
			echo json_encode($output);
		}
	}
	
	/***************************** Get User*****************************/

	public function get_user()
	{
		$param['sortby']			=Input::get('sortby');
		$param['orderby']			=Input::get('orderby');
		$param['limit']				=Input::get('limit');
		$param['offset']			=Input::get('offset');
		$param['keyword']			=Input::get('keyword');
		$param['is_fb_user']		=Input::get('is_fb_user');
		$param['id']				=Input::get('id');
		
		if($param['sortby'] == "")
		{
			$param['sortby']	= "id";
		}
		if($param['orderby'] == "")
		{
			$param['orderby']	= "DESC";
		}
		if($param['limit'] == "")
		{
			$param['limit']	= 20;
		}
		if($param['offset'] == "")
		{
			$param['offset']	= 0;
		}
	
		$temp	 	= new User;
		$output 	= $temp->get_users($param);	
		echo json_encode($output);
		
	}


 /*****************Share increment**********************/
	public function get_shareincrement()
	{
		
		$param['id']   = Input::get('id');
	 	$param['user_id'] = Input::get('user_id');
	 	$type = Input::get('type');
		
		if($param['id'] == '' || $type == '' || $param['user_id'] == '' )
		{
			echo json_encode(array('status' => 'error','msg' => 'parameters must be given' )); 
		}
		else
		{
			if($type == 'encounter')
			{  
				$param['animal_id'] = Input::get('animal_id');
				
				$encounter = new Encounter; 
				$data = $encounter->get_shareincrement($param);
			}
			if($type == 'animal')
			{
				$animal = new Animal; 
				$data = $animal->get_shareincrement($param);
			}
			
			if($data != false)
			{
				echo json_encode($data); 
			}
			else
			{
				echo json_encode(array('status' => 'error')); 
			}
		}
	}	


/*****************Like increment**********************/
	public function get_likeincrement()
	{
	
		$param['id']   = Input::get('id');
		$param['user_id']   = Input::get('user_id');
		
		if($param['id'] == '' || $param['user_id'] == '')
		{
			echo json_encode(array('status' => 'error','msg' => 'id must be given' )); 
		}
		else
		{
			$encounter = new Encounter; 
			$data = $encounter->get_likeincrement($param);
			if($data != false)
			{
				echo json_encode($data); 
			}
			else
			{
				echo json_encode(array('status' => 'error')); 
			}
		}
	}	
	
/*****************Follow increment**********************/
	public function get_followincrement()
	{
		$id   = Input::get('id');
		if($id == '' )
		{
			echo json_encode(array('status' => 'error','msg' => 'id must be given' )); 
		}
		else
		{
			$animal = new Animal; 
			$data = $animal->get_followincrement($id);
			if($data != false)
			{
				echo json_encode($data); 
			}
			else
			{
				echo json_encode(array('status' => 'error')); 
			}
		}
	}		
		
/*****************Follow decrement**********************/
	public function get_followdecrement()
	{
		$id   = Input::get('id');
		if($id == '' )
		{
			echo json_encode(array('status' => 'error','msg' => 'id must be given' )); 
		}
		else
		{
			$animal = new Animal; 
			$data = $animal->get_followdecrement($id);
			if($data != false)
			{
				echo json_encode($data); 
			}
			else
			{
				echo json_encode(array('status' => 'error')); 
			}
		}
	}		
	

 /**********************get followers of an animal id***************************/
	public function get_followers()
	{
		$param['sortby']			=Input::get('sortby');
		$param['orderby']			=Input::get('orderby');
		$param['limit']				=Input::get('limit');
		$param['offset']			=Input::get('offset');
		$param['animal_id']			=Input::get('animal_id');
		
		if($param['sortby'] == "")
		{
			$param['sortby']	= "id";
		}
		if($param['orderby'] == "")
		{
			$param['orderby']	= "DESC";
		}
		if($param['limit'] == "")
		{
			$param['limit']	= 20;
		}
		if($param['offset'] == "")
		{
			$param['offset']	= 0;
		}
	
		if($param['animal_id'] == '')
		{
			echo json_encode(array('status' => 'error','msg' => 'animal_id must be given' )); 
		}
		else
		{
			$follower = new Follow;
			$output = $follower->get_followers($param);
			echo json_encode($output); 
		}
	}
	
/**********************get user animal***************************/
	public function get_user_followers()
	{
		$param['sortby']			=Input::get('sortby');
		$param['orderby']			=Input::get('orderby');
		$param['limit']				=Input::get('limit');
		$param['offset']			=Input::get('offset');
		$param['user_id']			=Input::get('user_id');
		$param['profile_user_id']	=Input::get('profile_user_id');
		
		if($param['sortby'] == "")
		{
			$param['sortby']	= "id";
		}
		if($param['orderby'] == "")
		{
			$param['orderby']	= "DESC";
		}
		if($param['limit'] == "")
		{
			$param['limit']	= 20;
		}
		if($param['offset'] == "")
		{
			$param['offset']	= 0;
		}

	
		if($param['user_id']	== '')
		{
			echo json_encode(array('status' => 'error','msg' => 'animal_id must be given' )); 
		}
		else
		{
			$follower = new Follow;
			$output = $follower->get_user_followers($param);
			echo json_encode($output); 
		}
	}
		
	/**************FB/Web Login*********************************/

	public function get_login()
	{ 	
		$login 			= new Login;
		
		$id				= Input::get('id');
		$accessToken	= Input::get('accessToken');
		
		$param['name']			= Input::get('name');
		$param['email'] 		= Input::get('email');
		$param['id']			= Input::get('id');
		$param['access_token']	= Input::get('accessToken');
		$param['age']			= Input::get('age');
		$param['gender']		= Input::get('gender');
		
		$id 			=	isset($id) ? $id 			: '';
		$accessToken 	=	isset($accessToken) ? $accessToken 	: '';

		if ($id != '' && $accessToken != '')
		{
			//Check if user exist in database/memcache or not
			//$userExist = $login->userExist($id);
			
			//If user exist than get their details from database or memcache
		//	if($userExist==true)
		//	{
			//	$userData = $login->getUserDetails($id);	
			//	$output = json_encode($userData);				
			//	echo $output;	
		//	}
			//else
			//{
				//Get user details from facebook
				//$user = $login->getUserDetailsFromFacebook($id, $accessToken);	
				// return data
				//$output = json_encode($user);				
				//echo $output;	
				//Create new record in database
				$userData = $login->user_signup($param);
				$output = json_encode($userData);	
				echo $output;		
			//}	
			
		}
		else
		{
			return json_encode(array('status' => 'error1', 'error_msg'=>'id or access token is null' ));
		}

	}
	
	/*****************User Friends**********************/
	public function get_user_friends()
	{
		$param['limit']				=Input::get('limit');
		$param['offset']			=Input::get('offset');
		$param['user_id']			= Input::get('user_id');
		
		if($param['limit'] == "")
		{
			$param['limit']	= 5;
		}
		if($param['offset'] == "")
		{
			$param['offset']	= 0;
		}

		if($param['user_id'] == '' )  
		{
			echo json_encode(array('status' => 'error','msg' => 'user_id must be given' )); 
		}
		else
		{
			$user = new User;
			$data = $user->get_user_friends($param);
			echo json_encode($data); 
		}
	
	}
	
	/**********************increment comment counts***************************/
	public function get_commentcount()
	{
	
		$id   = Input::get('id');
	
		if($id == '' )
		{
			echo json_encode(array('status' => 'error','msg' => 'id must be given' )); 
		}
		else
		{
			$encounter = new Encounter; 
			$data = $encounter->get_commentcount($id);
			if($data != false)
			{
				$output = $data;
				echo json_encode($output); 
			}
			else
			{
				echo json_encode(array('status' => 'error')); 
			}
		}
	}
	
	public function get_logout()
	{
	  Session::flush();
	  
	  return json_encode(array('status' => 'success', 'msg' => 'Logout Successfully'));
	}
	
	public function get_existuser()
	{
		$id   = Input::get('id');
		
		if($id == '' )
		{
			echo json_encode(array('status' => 'error','msg' => 'id must be given' )); 
		}
		else
		{
			$login = new Login; 
			$data = $login->userExist($id);
			if($data != false)
			{
				$user	=	new User;
				$userData	=	$user->get_user($id);
				
				//print_r($userData['record']->name);die;
				
				
				Session::put('name', $userData['record']->name);
				Session::put('user_id', $id);
				Session::put('join_date', date('M, Y',strtotime($userData['record']->date_added)));
				
				
				echo json_encode(array('status' => 'success','record'=>$userData['record'])); 
			}
			else
			{
				echo json_encode(array('status' => 'error')); 
			}
		}
	}
	
}



?>

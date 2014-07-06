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
	  

  /*************************** Adoptor ******************************/

/******************Insert Adoptor********************/
	public function get_adoptors($id=NULL)
 	{
		//Get All Category Record
		
		$param['offset']  = Input::get('offset');
		$param['limit'] 	 = Input::get('limit');
		$param['keyword'] = Input::get('keyword');
		$param['sortby']  = Input::get('sortby');
		$param['orderby'] = Input::get('orderby');
		$param['animal_id'] = Input::get('animal_id');
		
		$param['date1'] = Input::get('date1');
		$param['date2'] = Input::get('date2');
		$param['adv_search'] = Input::get('adv_search');
		$param['category_id'] = Input::get('category_id');
		$param['sort_type'] = Input::get('sort_type');
		$param['active_only'] = Input::get('active_only');
		
		if($param['offset']==NULL)$param['offset']=0;
		if($param['limit']==NULL)$param['limit']=30;
		if($param['keyword']==NULL)$param['keyword']='';
		if($param['sortby']==NULL)$param['sortby']='date_added';
		if($param['orderby']==NULL)$param['orderby']='ASC';
		
		$category  =  new Adoptor;
		$data = $category->get_adoptors($param);
		
		$output = $data;
		echo json_encode($output);
	}
	
	public function post_checkout()
 	{
		
	header('Access-Control-Allow-Origin: *');  		
	$cart    				 = new Adoptor;
	
	$param['cc_type']		 = Input::get('cc_type');
	$param['cc_num'] 		 = Input::get('cc_num');
	$param['fname']	 		 = Input::get('fname');
	$param['address'] 		 = Input::get('address');
	$param['exp_date']		 = Input::get('exp_date');
	$param['exp_date2']		 = Input::get('exp_date2');
	$param['ccv2']	  		 = Input::get('ccv2');
	$param['street']  		 = Input::get('street');
	$param['city'] 	  		 = Input::get('city');
	$param['state'] 	  	 = Input::get('state');
	$param['country']  		 = Input::get('country');
	$param['zip'] 	  		 = Input::get('zip');
	$param['amt'] 	  		 = Input::get('amt');
	$param['desc']	  		 = Input::get('desc');
	
	$data = $cart->checkout_paypal($param);
	echo json_encode($data);
 }
  
  
	
	public function get_dashboard($id=NULL)
 	{
		//Get All Category Record
		
		$param['id']      = Input::get('id');
		$param['offset']  = Input::get('offset');
		$param['limit'] 	 = Input::get('limit');
		$param['keyword'] = Input::get('keyword');
		$param['sortby']  = Input::get('sortby');
		$param['orderby'] = Input::get('orderby');
		$param['adv_search'] = Input::get('adv_search');
		
		if($param['offset']==NULL)$param['offset']=0;
		if($param['limit']==NULL)$param['limit']=30;
		if($param['keyword']==NULL)$param['keyword']='';
		if($param['sortby']==NULL)$param['sortby']='id';
		if($param['orderby']==NULL)$param['orderby']='ASC';
		
		$category  =  new Adoptor;
		$data = $category->get_dashboard($param);
		
		$output = $data;
		echo json_encode($output);
	}
	
	public function get_chart($id=NULL)
 	{
		//Get All Category Record
		
		$param['sortby']  = Input::get('sortby');
		$param['orderby'] = Input::get('orderby');
		
		if($param['sortby']==NULL)$param['sortby']='id';
		if($param['orderby']==NULL)$param['orderby']='ASC';
		
		$category  =  new Adoptor;
		$data = $category->get_chart($param);
		
		$output = $data;
		echo json_encode($output);
	}
	
	public function get_chart_revenue($id=NULL)
 	{
		//Get All Category Record
		
		$param['sortby']  = Input::get('sortby');
		$param['orderby'] = Input::get('orderby');
		
		$param['date1'] = Input::get('date1');
		$param['date2'] = Input::get('date2');
		
		if($param['sortby']==NULL)$param['sortby']='id';
		if($param['orderby']==NULL)$param['orderby']='ASC';
		
		$category  =  new Adoptor;
		$data = $category->get_chart_revenue($param);
		
		$output = $data;
		echo json_encode($output);
	}
	
	
	public function post_adoptor()
	{
		header('Access-Control-Allow-Origin: *');  		
		$param['uid'] 		  = Input::get('uid');
		$param['animal_id']   = Input::get('animal_id');
		$param['category_id'] = Input::get('category_id');
		$param['nick_name']   = Input::get('nick_name');
		$param['quote']    	  = Input::get('quote');
		$param['amount']  	  = Input::get('amount');
		$param['status']  	  = Input::get('status');
		$param['user_type']  	= Input::get('user_type');
		
		if($param['uid']=='' || $param['animal_id']== '' || $param['category_id']== '' )
		{
			return json_encode(array('status'=>'error','msg'=>'parameters incomplete'));	
		}
		
		else
		{
			$category = new Adoptor;
			$output = $category->post_adoptor($param);
			echo json_encode($output);	
		}
		
	}

	public function get_adoptor_cron(){
	
			$category = new Adoptorcron;
			$output = $category->markexpire();
			echo json_encode($output);	
	}
	

	public function post_adoptor_qoute(){
		
		$param['uid'] 		  = Input::get('uid');
		$param['animal_id']   = Input::get('animal_id');
		$param['quote'] 	  = Input::get('quote');
		$param['adopter_id']  = Input::get('adopter_id');
		
		if($param['uid']=='' || $param['animal_id']== '' || $param['quote']== '' )
		{
			return json_encode(array('status'=>'error','msg'=>'parameters incomplete'));	
		}
		
		else
		{
			$category = new Adoptor;
			$output = $category->update_qoute($param);
			echo json_encode($output);	
		}
		
	}

	public function post_outside_adoptor()
	{
		header('Access-Control-Allow-Origin: *');  		
		$param['uid'] 		  = Input::get('uid');
		$param['animal_id']   = Input::get('animal_id');
		$param['category_id'] = Input::get('category_id');
		$param['nick_name']   = Input::get('nick_name');
		$param['quote']    	  = Input::get('quote');
		$param['amount']  	  = Input::get('amount');
		$param['status']  	  = Input::get('status');
		$param['name']  	  = Input::get('name');
		$param['email']  	  = Input::get('email');
		$param['user_type']  	= Input::get('user_type');
		
		if($param['uid']=='' || $param['animal_id']== '' || $param['category_id']== '' )
		{
			return json_encode(array('status'=>'error','msg'=>'parameters incomplete'));	
		}
		
		else
		{
			$category = new Adoptor;
			$output = $category->post_outside_adoptor($param);
			echo json_encode($output);	
		}
		
	}


	public function put_adoptor()
	{
		$param['id'] 		  = Input::get('id');
		$param['type']   = Input::get('type');
		$param['value'] = Input::get('value');

		
		if($param['id']=='' || $param['type']== '' || $param['value']== '' )
		{
			return json_encode(array('status'=>'error','msg'=>'parameters incomplete'));	
		}
		
		else
		{
			$category = new Adoptor;
			$output = $category->put_adoptor($param);
			echo json_encode($output);	
		}
		
	}
	
	public function delete_adoptor($id=NULL)
	{
		$id = Input::get('id');
		
		if($id==NULL || $id<=0)
		{
			return json_encode(array('status'=>'error','msg'=>'record not found for this id'));	
		}
		else
		{
			$category = new Adoptor;
			$output = $category->delete_adoptor($id);
			echo json_encode($output);
		}
	}
	
	function post_paypalpro(){
	
		$param['cc_type'] 	 	= Input::get('cc_type');
		$param['cc_number']  	= Input::get('cc_number');
		$param['cc_exp'] 	 	= Input::get('cc_exp');
		$param['ccv'] 		 	= Input::get('ccv');
		$param['fname']    	 	= Input::get('fname');
		$param['lname']  	 	= Input::get('lname');
		$param['address'] 	 	= Input::get('address');
		$param['citry'] 	 	= Input::get('citry');
		$param['state'] 		= Input::get('state');
		$param['zip']    		= Input::get('zip');
		$param['amount']  		= Input::get('amount');
		$param['uid']		    = Input::get('uid');
		$param['animal_id']		= Input::get('animal_id');
		$param['category_id']	= Input::get('category_id');
		$param['nick_name']		= Input::get('nick_name');
		$param['quote']		    = Input::get('quote');
		
		if($param['cc_type']=='' || $param['cc_number']== '' || $param['cc_exp']== '' || $param['ccv'] == '' || $param['amount'] == ''  )
		{
			return json_encode(array('status'=>'error','msg'=>'parameters incomplete'));	
		}
		
		else
		{
			$category = new Payment;
			$output = $category->transaction($param);
			echo json_encode($output);	
		}
		
		
	}
  /*************************** Category ******************************/

/******************Insert Category********************/
	
	public function post_category()
	{
		$param['title'] 	 		= Input::get('title');
		$param['icon'] 		 		= Input::get('icon');
		$param['color_code'] 		= Input::get('color_code');
		$param['type'] 		 		= Input::get('type');
		$param['minimum_amount']    = Input::get('minimum_amount');
		$param['api_url']    		= Input::get('api_url');
		$param['id_prefix']    		= Input::get('id_prefix');
		$param['image_url']  		= Input::get('image_url');
		
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
		$param['id'] 		 		= Input::get('id');
		$param['title'] 	 		= Input::get('title');
		$param['icon'] 		 		= Input::get('icon');
		$param['color_code'] 		= Input::get('color_code');
		$param['type'] 		 		= Input::get('type');
		$param['minimum_amount']    = Input::get('minimum_amount');
		$param['api_url']    		= Input::get('api_url');
		$param['id_prefix']    		= Input::get('id_prefix');
		$param['image_url']  		= Input::get('image_url');
		
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
	
	public function get_category_dashboard($id=NULL)
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
		$data = $category->category_dashboard($param);
		
		$output = $data;
		echo json_encode($output);
	}
	
	
	public function get_category_revenue($id=NULL)
 	{
		//Get All Category Record
		
		$param['id']      = Input::get('id');
		$param['offset']  = Input::get('offset');
		$param['limit'] 	 = Input::get('limit');
		$param['keyword'] = Input::get('keyword');
		$param['sortby']  = Input::get('sortby');
		$param['orderby'] = Input::get('orderby');
		
		$param['date1'] = Input::get('date1');
		$param['date2'] = Input::get('date2');
		
		if($param['offset']==NULL)$param['offset']=0;
		if($param['limit']==NULL)$param['limit']=30;
		if($param['keyword']==NULL)$param['keyword']='';
		if($param['sortby']==NULL)$param['sortby']='id';
		if($param['orderby']==NULL)$param['orderby']='ASC';
		
		$category  =  new Category;
		$data = $category->category_revenue($param);
		
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

	/******************Update Category Adoption********************/
	
	public function post_update_cat_adoption()
	{
		$param['id']    		= Input::get('id');
		$param['status']    	= Input::get('status');
		
		if($param['id']=='' || $param['status']=='')
		{
			return json_encode(array('status'=>'error','msg'=>'parameter missing'));	
		}
		
		else
		{
			$category = new Category;
			$output = $category->cat_adoption_status_change($param);
			echo json_encode($output);	
		}
		
	}


/******************Update Category GPS********************/
	
	public function post_update_cat_gps()
	{
		$param['id']    		= Input::get('id');
		$param['status']    	= Input::get('status');
		
		if($param['id']=='' || $param['status']=='')
		{
			return json_encode(array('status'=>'error','msg'=>'parameter missing'));	
		}
		
		else
		{
			$category = new Category;
			$output = $category->cat_gps_status_change($param);
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
		$param['id'] 		 	= Input::get('id');
		$param['user_id'] 	 	= Input::get('user_id');
		$param['encounter_id'] 	= Input::get('encounter_id');
		$param['message'] 		= Input::get('message');
		
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
		$current_user_id = Input::get('current_id');
		$user_id = Session::get('user_id');
		$encounter_id =Input::get('encounter_id');

		if( ($id==NULL || $id<=0 ) || ($current_user_id != $user_id)  )
		{
			return json_encode(array('status'=>'error','msg'=>'record not found for this id'));	
		}

		else
		{
			$comment = new Comment;
			$output = $comment->del_comment($id,$encounter_id);
			echo json_encode($output);
		}
	}

/*****************Delete Comment admin*******************/
	public function delete_comment_admin($id=NULL)
	{
		$id = Input::get('id');
		$encounter_id = Input::get('encounter_id');
		
		if($id==NULL || $id<=0)
		{
			return json_encode(array('status'=>'error','msg'=>'record not found for this id'));	
		}
		else
		{
			$comment = new Comment;
			$output = $comment->del_commentadmin($id,$encounter_id);
			echo json_encode($output);
		}
	}
	
/*************Like Comment******************/
	public function put_like_comment()
	{
		$param['id'] 	= Input::get('id');
				
		if($param['id']=='')
		{
			return json_encode(array('status'=>'error','msg'=>'Parameters missing'));	
		}
		else
		{
			$comment = new Comment;
			$output = $comment->like_comment($param);
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
		header('Access-Control-Allow-Origin: *');  		
		$param['user_id'] 	   = Input::get('user_id');
		$param['animal_id']    = Input::get('animal_id');
		$param['user_type']    = Input::get('user_type');
		
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

	public function post_changeids()
	{
			$category = new Category;
			$output = $category->change_ids();
			echo json_encode($output);	
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
	
/*****************Get Single User*******************/
	public function get_single_user($id=NULL)
	{
		$id 	   = Input::get('id');
		
		if($id=='' )
		{
			return json_encode(array('status'=>'error','msg'=>'parameters incomplete'));	
		}
		else
		{
			$user = new User;
			$output = $user->get_user($id);
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
	
	public function get_postfb()
	{
	
		$param['animal_id']   	= Input::get('animal_id');
		/*$param['title']  	 = Input::get('title');
		$param['link']       = Input::get('link');
		$param['picture']   = Input::get('picture');
		$param['description']   = Input::get('description');
		|| $param['picture'] == '' || $param['description'] == '' || $param['link'] == ''*/
		
		if($param['animal_id'] == '' )
		{
			echo json_encode(array('status' => 'error','msg' => 'animal_id must be given' )); 
		}
		else
		{
			$encounter = new Encounter; 
			$data = $encounter->facebook_post($param);
			echo json_encode($data); 
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
		header('Access-Control-Allow-Origin: *');  
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
	
	
 /*************************** Reminder ******************************/

/******************Insert Reminder********************/
	
	public function post_add_reminder()
	{
		$param['title']    	= Input::get('title');
		$param['interval']  = Input::get('interval');
		$param['period']    = Input::get('period');
		$param['template']  = Input::get('template');
				
		if($param['title']=='' || $param['interval']== '' || $param['period']== '')
		{
			return json_encode(array('status'=>'error','msg'=>'parameter missing'));	
		}
		
		else
		{
			$reminder = new Reminder;
			$output = $reminder->add_reminder($param);
			echo json_encode($output);	
		}
		
	}
	
/******************Update Reminder********************/
	
	public function post_update_reminder()
	{
		$param['id']    	= Input::get('id');
		$param['title']    	= Input::get('title');
		$param['interval']  = Input::get('interval');
		$param['period']    = Input::get('period');
		$param['template']  = Input::get('template');
				
		if($param['id']=='' || $param['title']=='' || $param['interval']== '' || $param['period']== '')
		{
			return json_encode(array('status'=>'error','msg'=>'parameter missing'));	
		}
		
		else
		{
			$reminder = new Reminder;
			$output = $reminder->update_reminder($param);
			echo json_encode($output);	
		}
		
	}

	public function get_reminder()
	{				
		$id    	= Input::get('id');
		if($id =='')
		{
			return json_encode(array('status'=>'error','msg'=>'parameter missing'));	
		}
		
		else
		{
			$reminder = new Reminder;
			$output = $reminder->get_reminder($id);
			echo json_encode($output);	
		}
	}

/***************Get Reminders ************/	
 	public function get_reminders()
 	{
		//Get All Records
		$param['id']      = Input::get('id');
		$param['offset']  = Input::get('offset');
		$param['limit'] 	= Input::get('limit');
		$param['keyword'] = Input::get('keyword');
		$param['sortby']  = Input::get('sortby');
		$param['orderby'] = Input::get('orderby');
		
		if($param['offset']==NULL)$param['offset']=0;
		if($param['limit']==NULL)$param['limit']=30;
		if($param['keyword']==NULL)$param['keyword']='';
		if($param['sortby']==NULL)$param['sortby']='id';
		if($param['orderby']==NULL)$param['orderby']='ASC';
		
		$reminder  =  new Reminder;
		$data = $reminder->get_all_reminders($param);
		
		$output = $data;
		echo json_encode($output);
	}


/*****************Delete Reminder*******************/
	public function delete_reminder()
	{
		$id = Input::get('id');
		
		if($id==NULL || $id<=0)
		{
			return json_encode(array('status'=>'error','msg'=>'parameter missing'));	
		}
		else
		{
			$reminder = new Reminder;
			$output = $reminder->del_reminder($id);
			echo json_encode($output);
		}
	}

 /*************************** Report Abuse Comment ******************************/

/****************** Add Report ********************/
	
	public function post_add_report()
	{
		$param['comment_id']    = Input::get('comment_id');
		$param['reporter_id']  = Input::get('reporter_id');
		
		if($param['comment_id']=='' || $param['reporter_id']== '' )
		{
			return json_encode(array('status'=>'error','msg'=>'parameter missing'));	
		}
		
		else
		{
			$report = new ReportAbuse;
			$output = $report->add_report($param);
			echo json_encode($output);	
		}
		
	}


/***************** Delete Report *******************/
	
	public function delete_report()
	{
		$id = Input::get('id');
		
		if($id==NULL || $id<=0)
		{
			return json_encode(array('status'=>'error','msg'=>'parameter missing'));	
		}
		else
		{
			$report = new ReportAbuse;
			$output = $report->del_report($id);
			echo json_encode($output);
		}
	}

	/***************** Get Report *******************/
	
 	public function get_reports()
 	{
		//Get All Records
		$param['offset']  = Input::get('offset');
		$param['limit'] 	= Input::get('limit');
		$param['sortby']  = Input::get('sortby');
		$param['orderby'] = Input::get('orderby');
		
		if($param['offset']==NULL)$param['offset']=0;
		if($param['limit']==NULL)$param['limit']=30;
		if($param['sortby']==NULL)$param['sortby']='id';
		if($param['orderby']==NULL)$param['orderby']='ASC';
		
		$report  =  new ReportAbuse;
		$data = $report->get_all_reports($param);
		
		$output = $data;
		echo json_encode($output);
	}

	
/******************Update Badge********************/
	
	public function post_update_badge()
	{
		$param['id']    			= Input::get('id');
		$param['adoptor_badge']    	= Input::get('adoptor_badge');
		
		if($param['id']=='' || $param['adoptor_badge']=='')
		{
			return json_encode(array('status'=>'error','msg'=>'parameter missing'));	
		}
		
		else
		{
			$user = new User;
			$output = $user->update_adoptor_badge($param);
			echo json_encode($output);	
		}
	}

	/******************Update user********************/
	
	public function post_update_banuser()
	{
		$param['id']    = Input::get('id');
		$param['ban_status']    = Input::get('ban_status');
		
		if($param['id']=='')
		{
			return json_encode(array('status'=>'error','msg'=>'parameter missing'));	
		}
		
		else
		{
			$user = new User;
			$output = $user->update_ban_user($param);
			echo json_encode($output);	
		}
	}
	
/******************Update Adoption********************/
	
	public function post_update_adoption()
	{
		$param['id']    		= Input::get('id');
		$param['status']    	= Input::get('status');
		
		if($param['id']=='' || $param['status']=='')
		{
			return json_encode(array('status'=>'error','msg'=>'parameter missing'));	
		}
		
		else
		{
			$animal = new Animal;
			$output = $animal->adoption_status_change($param);
			echo json_encode($output);	
		}
		
	}


/******************Update GPS********************/
	
	public function post_update_gps()
	{
		$param['id']    		= Input::get('id');
		$param['status']    	= Input::get('status');
		
		if($param['id']=='' || $param['status']=='')
		{
			return json_encode(array('status'=>'error','msg'=>'parameter missing'));	
		}
		
		else
		{
			$animal = new Animal;
			$output = $animal->gps_status_change($param);
			echo json_encode($output);	
		}
		
	}
	
	
/******************User Setting Add********************/
	
	public function post_add_user_setting()
	{
		$param['uid']    			= Input::get('uid');
		$param['animal_id']    		= Input::get('animal_id');
		$param['allow_posting']    	= Input::get('allow_posting');
		
		if($param['uid']=='' || $param['animal_id']=='')
		{
			return json_encode(array('status'=>'error','msg'=>'parameter missing'));	
		}
		
		else
		{
			$user = new UserSetting;
			$output = $user->add_setting($param);
			echo json_encode($output);	
		}
	}
	
/******************User Setting Posting********************/
	
	public function put_posting_update()
	{
		$param['id']    	= Input::get('id');
		$param['start']    	= Input::get('start');
		$param['stop']    	= Input::get('stop');
		
		if($param['id']=='' )
		{
			return json_encode(array('status'=>'error','msg'=>'parameter missing'));	
		}
		else
		{
			$user = new UserSetting;
			$output = $user->posting_update($param);
			echo json_encode($output);	
		}
	}
	
	/***************** Get Researcher *******************/
	
 	public function get_researchers()
 	{
		//Get All Records
		$param['offset']  = Input::get('offset');
		$param['limit'] 	= Input::get('limit');
		$param['sortby']  = Input::get('sortby');
		$param['orderby'] = Input::get('orderby');
		$param['animal_id'] = Input::get('animal_id');
		
		if($param['offset']==NULL)$param['offset']=0;
		if($param['limit']==NULL)$param['limit']=30;
		if($param['sortby']==NULL)$param['sortby']='animal_id';
		if($param['orderby']==NULL)$param['orderby']='ASC';
		
		$reseacher  =  new Reseacher;
		$data = $reseacher->get_all_researchers($param);
		
		$output = $data;
		echo json_encode($output);
	}
	
	/***************** Get Stories *******************/
	
 	public function get_stories()
 	{
		//Get All Records
		$param['offset']  = Input::get('offset');
		$param['limit'] 	= Input::get('limit');
		$param['sortby']  = Input::get('sortby');
		$param['orderby'] = Input::get('orderby');
		$param['animal_id'] = Input::get('animal_id');
		
		if($param['offset']==NULL)$param['offset']=0;
		if($param['limit']==NULL)$param['limit']=30;
		if($param['sortby']==NULL)$param['sortby']='animal_id';
		if($param['orderby']==NULL)$param['orderby']='ASC';
		
		$reseacher  =  new Story;
		$data = $reseacher->get_all_stories($param);
		
		$output = $data;
		echo json_encode($output);
	}
	
	/******************Post Like********************/

	public function post_like()
 	{

		$param['comment_id']    = Input::get('comment_id');
		$param['user_id']    	= Input::get('user_id');
		
		$like  =  new CommentLikes;
		$data = $like->post_like($param);
		
		$output = $data;
		echo json_encode($output);
	}

	/******************Post Like********************/

	public function delete_unlike()
 	{

		$param['comment_id']    = Input::get('comment_id');
		$param['user_id']    	= Input::get('user_id');
		
		$like  =  new CommentLikes;
		$data = $like->del_unlike($param);
		
		$output = $data;
		echo json_encode($output);
	}

	public function get_animal_detail()
 	{
		header('Access-Control-Allow-Origin: *');  
		$param['animal_id']  = Input::get('animal_id');
		//print_r($param['animal_id']);die();
		$animal  =  new Animal;
		$data = $animal->get_animal_details($param);
		
		$output = $data;
		echo json_encode($output);
	}
	
	
	 /**********************get followers of an animal id***************************/
	public function get_global_follows()
	{
		header('Access-Control-Allow-Origin: *');  
		$param['animal_id'] = Input::get('animal_id');
		$param['category_name']	= Input::get('category_name');
		
	
		if(empty($param['animal_id']))
		{
			echo json_encode(array('status' => 'error','msg' => 'animal id and category name must be given' )); 
		}
		else
		{
			$follower = new Follow;
			$output = $follower->get_global_follows($param);
			echo json_encode($output); 
		}
	}

	public function get_dir_count()
	{
		$follower = new Follow;
		$source = 'C:/xampp/htdocs/ievent/';
		$output = $follower->getdirectorysize($source);
		echo json_encode($output); 
	}
	
	public function post_animals_story(){
		
			$animal_id = Input::get('animal_id');
		/*	$temp 	= file_get_contents('http://www.whaleshark.org/rest/org.ecocean.Story?individualID='.$animal_id.' ');
			$var 	= preg_replace("/[\r\n]+/", " ", $temp);
			$var 	= utf8_encode($var);
			$json2 	= json_decode($var, true);*/
			
		$url = 'http://www.wildme.org/polarbearlibrary/rest/org.ecocean.Story?individualID=='.$animal_id.' ';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
        $output = curl_exec($ch);
        echo curl_error($ch);
        curl_close($ch);
        return $output;
    			
		return array('status' => 'success','record' => $json2);
	}
	
	/**********************get user animal***************************/	 
}

?>

<?php

// addadoptor
// get_adoptor
// delete_adoptor
// get_csvNewsletter

class Adoptor  {

	/*
	Method For : Adding user subscription email address
	Type : POST
	Tested Local : Yes
	Tested Live : YES
	*/
	public function post_adoptor($param)
	{
		$curr_date = date('Y-m-d h:i:s');
		$expiration_date = date('Y-m-d h:i:s', strtotime($curr_date.' + 12 months'));
		$userData = DB::table('user')->where('id','=',$param['uid'])->update(array( 
												'adoptor_badge'	 => 'Yes', 
												)); 
		$key  = 'user_'.$param['uid'];
		Cache::forget($key);

		$user = new User;
		$userData = $user->get_user($param['uid']);
		$userData = json_decode(json_encode($userData),TRUE);
		$user_name = $userData['record']['name'];
		$animal = new Animal;
		$animalData = $animal->get_animal($param['animal_id']);	
		$animalData = json_decode(json_encode($animalData),TRUE);
		$nick_name = $animalData['record']['nick_name'];
		$quote = $animalData['record']['quote'];
		$nick_namer = $animalData['record']['nick_namer'];
		$category_id = $animalData['record']['category_id'];
		
		if(empty($nick_name) && empty($nick_namer))
		{
			
			$animalData = DB::table('animal')->where('id','=',$param['animal_id'])->update( array( 
												'nick_name'	 => $param['nick_name'], 
												'nick_namer' => $user_name,
												'first_adoptor' => $param['uid']
												 )); 
												 
			$key  = 'animal_'.$param['animal_id'];
			Cache::forget($key);		
			
		}else{
			$param['nick_name']  ='';
		}

		if(empty($quote))
		{
			$animalData = DB::table('animal')->where('id','=',$param['animal_id'])->update( array( 
												'quote' => $param['quote']
												 )); 
			$key  = 'animal_'.$param['animal_id'];
			Cache::forget($key);	
			
		}else{
			
			$param['quote']  = '';
		}

		$key  = 'animal_'.$param['animal_id'];
		Cache::forget($key);
		
		
		$id = DB::table('adoptor')->insert_get_id(array('uid' => $param['uid'],
															'animal_id' => $param['animal_id'], 
															'category_id' => $category_id, 
															'nick_name' => $param['nick_name'], 
															'quote' => $param['quote'],
															'amount' => $param['amount'], 
															'status' => $param['status'], 
															'user_type' => $param['user_type'], 
															'date_added' => $curr_date, 
															'expiration_date' => $expiration_date 
													));
																		
			$status = 'error';
			if($id > 0)
				{						
					$key = 'adoptor_'.$id; //set mem key
					$data['id'] 		    = $id;
					$data['category_id']	= $category_id;
					$data['animal_id']		= $param['animal_id'];
					$data['uid']			= $param['uid'];
					$data['status']			= $param['status'];
					$data['amount']			= $param['amount'];
					$data['quote']			= $param['quote'];
					$data['nick_name']		= $param['nick_name'];
					$data['user_type']		= $param['user_type'];
					$data['expiration_date']		= $expiration_date;
					$data['name']			= '';
					$data['date_added']		= $curr_date;
					Cache::forever($key, $data); // UPDATE memcache		
					$status = 'success';
				
					$datas = array();
					//adopt
					$datas['type'] = 'adopt';
					$datas['ref_id'] = $param['animal_id'];
					$datas['user_id'] = $param['uid'];
					$datas['animal_id'] =$param['animal_id'];
					$datas['user_type'] =$param['user_type'];
					$userlog = new Userlog;
					//$result = $userlog->create_user_log($datas);
					$follow = new Follow;
					//$result = $follow->create_follow($datas);
					
					if($param['uid'] > 0){
					
						$count = DB::first("select COUNT(id) as total from adoptor where `uid` = '".$param['uid']."' and `animal_id` = '".$param['animal_id']."'  and `status` = 'Active' ");
			
						if($count->total>0)
						{
							$sql = "select id from adoptor where `uid` = '".$param['uid']."' and `animal_id` = '".$param['animal_id']."'  and `status` = 'Active'  " ;
							$adoptor = DB::query($sql);
							
							foreach($adoptor as $ind => $value){	
							
								$adopterData = DB::table('adoptor')->where('id','=',$value->id)->update( array( 
												'expiration_date' => $expiration_date
												 )); 
												 
								$key  = 'adoptor_'.$value->id;
		  						Cache::forget($key);					
							}
													
						}
					}
					
				}		

		return array('status' => $status);
	}
	
	
	public function update_qoute($param)
	{
		
		$curr_date = date('Y-m-d h:i:s');
		$animal = new Animal;
		$animalData = $animal->get_animal($param['animal_id']);	
		$animalData = json_decode(json_encode($animalData),TRUE);
		
		$user_repo = new  User;
		$user_data 	= $user_repo->get_user($param['uid']);
		$user_data = json_decode(json_encode($user_data),TRUE);
		
		$animalData = DB::table('animal')->where('id','=',$param['animal_id'])->update( array( 
												'quote' => $param['quote']
												 )); 
			$key  = 'animal_'.$param['animal_id'];
			Cache::forget($key);	
					
		$adopterData = DB::table('adoptor')->where('id','=',$param['adopter_id'])->update( array( 
												'quote' => $param['quote']
												 )); 
		
		$key = 'adoptor_'.$param['adopter_id'];
		Cache::forget($key);	
		
		return array('status' => 'success');
	
	}
	public function post_outside_adoptor($param)
	{
		$cat_url = '';
		$curr_date = date('Y-m-d h:i:s');
		$animal = new Animal;
		$animalData = $animal->get_animal($param['animal_id']);	
		$animalData = json_decode(json_encode($animalData),TRUE);
		$nick_name = $animalData['record']['nick_name'];
		$quote = $animalData['record']['quote'];
		$nick_namer = $animalData['record']['nick_namer'];
		$category_id = $animalData['record']['category_id'];
		
		$param['nick_name']  = '';
		$param['quote']  ='';
		$key  = 'animal_'.$param['animal_id'];
		Cache::forget($key);
		$expiration_date = date('Y-m-d h:i:s', strtotime($curr_date.' + 12 months'));
		
		$id = DB::table('adoptor')->insert_get_id(array('uid' => $param['uid'],
															'animal_id' => $param['animal_id'], 
															'category_id' => $category_id, 
															'nick_name' => $param['nick_name'], 
															'quote' => $param['quote'],
															'amount' => $param['amount'], 
															'status' => $param['status'], 
															'name' => $param['name'], 
															'email' => $param['email'], 
															'user_type' => $param['user_type'], 
															'date_added' => $curr_date, 
															'expiration_date' => $expiration_date 
													));															
			
			$status = 'error';
			if($id > 0)
				{						
					$key = 'adoptor_'.$id; //set mem key
					$data['id'] 		    = $id;
					$data['category_id']	= $category_id;
					$data['animal_id']		= $param['animal_id'];
					$data['uid']			= $param['uid'];
					$data['status']			= $param['status'];
					$data['amount']			= $param['amount'];
					$data['quote']			= $param['quote'];
					$data['nick_name']		= $param['nick_name'];
					$data['name']			= $param['name'];
					$data['email']			= $param['email'];
					$data['user_type']		= $param['user_type'];
					$data['expiration_date']		= $expiration_date;
					$data['date_added']		= $curr_date;
					Cache::forever($key, $data); // UPDATE memcache		
					$status = 'success';
					 
					
					if($category_id == 1){
						$cat_url			= 'http://www.whaleshark.org/';
					}else{
						$Category  			= new Category;
						$cat_data 			= $Category->get_category($category_id);
						$cat_data			= json_decode(json_encode($cat_data), TRUE);
						$cat_data			= $cat_data['record'];
						$cat_url			= $cat_data['api_url'];  
					}
					
					
				}		

		return array('status' => $status, 'cat_url' => $cat_url);
	}


	/*
	Method For : Getting single subscription details 
	Type : POST
	Tested Local : Yes
	Tested Live : YES
	*/
	public function get_adoptor($id)
	{
		
		$key = 'adoptor_'.$id;
		$data = Cache::get($key);
		//$data = false;
		$status = 'success';
		if($data==false || $data==NULL)
		{
		   $count = DB::first("select COUNT(id) as total from adoptor where `id` = '".$id."' Limit 1;");

		   if($count->total>0)
		   {
				$data = DB::first("select * from adoptor where `id` = '".$id."' ");
				Cache::forever($key, $data);
		   }
		   else
		   {
				$status = 'error';
		   } 
		}
		return array('record' => $data,'totalrecords' => 1,'status' => $status);
	}

	/*
	Method For : Getting total record of subscription details 
	Type : POST
	Tested Local : Yes
	Tested Live : YES
	*/
	public function get_adoptors($param)
	{
		
		$sortby			 = $param['sortby'];
		$orderby 		 = $param['orderby'];
		$offset 		 = $param['offset'];
		$limit 		 	 = $param['limit'];
		$keyword 		 = $param['keyword'];
		$animal_id 		 = $param['animal_id'];
		$active_only 		 = $param['active_only'];
		
		$date1 		 	 = date('Y-m-d',strtotime($param['date1']));
		$date2 		 	 = date('Y-m-d',strtotime($param['date2']));
		$category_id 	 = $param['category_id'];
		$adv_search 	 = $param['adv_search'];
		$sort_type 	 	 = $param['sort_type'];
		
		$sqlpart = '';
		if($adv_search == 'name' && $keyword != '')
		{
			$sqlpart .=" AND (`uid` IN (select id from user where name like '%$keyword%' OR `email` like '%$keyword%') OR  ( `animal_id` like '%$keyword%' )) ";
		}
	
		if($adv_search == 'category' && $category_id > 0)
		{
			$sqlpart .=" AND (`category_id` = $category_id 	) ";
		}
		
		if($adv_search == 'date_range' && $date1 != '' && $date2 != '')
		{
			$sqlpart .=" AND (`date_added` BETWEEN DATE('".$date1."') AND DATE('".$date2."') ) ";
		}
		
		if($animal_id )
		{
			$sqlpart .=" AND (`animal_id` = '".$animal_id."') ";
		}
	
		if($animal_id )
		{
			$sqlpart .=" AND (`animal_id` = '".$animal_id."') ";
		}
		
		if($sort_type == 'application'){
			
			$sqlpart .=" AND (`uid`  > 0 ) ";
			
		}else if($sort_type == 'website'){
			
			$sqlpart .=" AND (`uid`  = 0 ) ";
		}else{
				$sqlpart .=" AND (`uid`  > 0 ) ";
			}
		
		if($active_only == 1){
			$sqlpart .=" AND (`status`  = 'Active' ) ";
			$sqlpart .=" group by `uid`  ";
		}
		//$sqlpart .=" AND (`uid`  > 0 ) ";
		
		$count = DB::first("select COUNT(id) as total, IFNULL(sum(amount),0) as totalamount from adoptor where 1=1 $sqlpart");
	
		if($count->total>0)
		{
			$sql = "select id from adoptor where 1=1 $sqlpart order by $sortby $orderby limit $offset,$limit";	
	
			$adoptor = DB::query($sql);
			$animal_repo = new Animal;
			$user_repo = new  User;
			foreach($adoptor as $ind => $value)
			{	
				$newsData 		= $this->get_adoptor($value->id);	
				$temp 			= json_encode($newsData);
				$newsData 		= json_decode($temp, true);			
				$data[]  		= $newsData['record'];
				
				$data[$ind]['follow'] = 0;
				$data[$ind]['date_added']	=  Utility::dat($data[$ind]['date_added'], 'M d, Y');
				$data[$ind]['animal_data'] = $animal_repo->get_animal($data[$ind]['animal_id']);
				$data[$ind]['user_data'] 	= 0;
				
				if($sort_type != 'website'){
					
					$countnew = DB::first("SELECT COUNT(id) as total from `follow` where `user_id` = '".$data[$ind]['uid']."'");
					$data[$ind]['follow'] = $countnew->total;
					$data[$ind]['user_data'] 	= $user_repo->get_user($data[$ind]['uid']);
				}
				
				$status 		= 'success';
			}
				
		}
		else
		{
			$data[] =	'';
			$status = 'error';	
		}
		
		return array('records' => $data, 'totalrecords' => $count->total, 'totalamount' => $count->totalamount,'status' => $status);
	}
		
	public function put_adoptor($param)
    {
		$count 			= DB::table('adoptor')->where('id','=',$param['id'])->count();
		
	    if($count>0)
	    { 
			$data = DB::table('adoptor')->where('id','=',$param['id'])->update( array( 
												$param['type']	 => $param['value'], 
												 ));
		  	$key  = 'adoptor_'.$param['id'];
		  	Cache::forget($key);
		  	$status = 'success';	
	   	}
	   	else
	   	{
			$status 	= 'error';
	   	}
		return array('status' => $status);  
   	}
	
	public function delete_adoptor($id)
 	{
   
		  if($id!=NULL)
		  { 
		   $count = DB::first("SELECT COUNT(id) as `cid` FROM `adoptor` WHERE `id` = '".$id."' Limit 1;");
		   
		   if($count->cid > 0)
		   { 
			$key = 'adoptor_'.$id;
			$data = DB::first("DELETE FROM adoptor WHERE id = '".$id."'");
			Cache::forget($key);
			return array('status' => 'success', 'msg' => 'record deleted successfully');
		   }
		   else
		   {
			return array('status' => 'error', 'msg' => 'no record found for this Id');
		   } 
		  }
		  else
		  {
		   return array('status' => 'error', 'msg' => 'id is null');
		  }
  }
  
 	public function get_transactions($param)
	{
		
		$sortby			 = $param['sortby'];
		$adv_search		 = $param['adv_search'];
		$orderby 		 = $param['orderby'];
		$offset 		 = $param['offset'];
		$limit 		 	 = $param['limit'];
		$keyword 		 = $param['keyword'];
		$sqlpart='';
		
		if($adv_search == "uid")
		{
			$sqlpart .=" AND `uid` IN (SELECT `id` FROM `user` WHERE `name` LIKE %$keyword% ) ";
			$sortby = 'id';
		}
		if($adv_search == "animal_id")
		{
			$sqlpart .="  AND ( `animal_id` = $animal_id )";
			$sortby = 'id';
		}
		if($adv_search == "category_id")
		{
			$sqlpart .=" AND `animal_id` IN (SELECT `animal_id` FROM `animal` WHERE `category_id` = $category_id )";
			$sortby = 'id';
		}
		
		$count = DB::first("select COUNT(id) as total from adoptor where 1=1 $sqlpart ");
	
		if($count->total>0)
		{
			$sql = "select id from adoptor order by $sortby $orderby limit $offset,$limit";	
	
			$adoptor = DB::query($sql);
			$animal_repo = new Animal;
			foreach($adoptor as $ind => $value)
			{	
				$newsData 		= $this->get_adoptor($value->id);	
				$temp 			= json_encode($newsData);
				$newsData 		= json_decode($temp, true);			
				$data[]  		= $newsData['record'];
				$data[$ind]['date']	=  Utility::dat($data[$ind]['date'], 'M d, Y');
				$data[$ind]['animal_data'] = $animal_repo->get_animal($data[$ind]['animal_id']);
				$status 		= 'success';
			}
				
		}
		else
		{
			$data[] =	'';
			$status = 'error';	
		}
		
		return array('records' => $data, 'totalrecords' => $count->total,'status' => $status);
	}
	
	public function get_dashboard($param)
	{
		
		$sortby			 = $param['sortby'];
		$adv_search		 = $param['adv_search'];
		$orderby 		 = $param['orderby'];
		$offset 		 = $param['offset'];
		$limit 		 	 = $param['limit'];
		$keyword 		 = $param['keyword'];
		$sqlpart='';
		
		if($adv_search == "uid")
		{
			$sqlpart .=" AND `uid` IN (SELECT `id` FROM `user` WHERE `name` LIKE %$keyword% ) ";
			$sortby = 'id';
		}
		if($adv_search == "animal_id")
		{
			$sqlpart .="  AND ( `animal_id` = $animal_id )";
			$sortby = 'id';
		}
		if($adv_search == "category_id")
		{
			$sqlpart .=" AND `animal_id` IN (SELECT `animal_id` FROM `animal` WHERE `category_id` = $category_id )";
			$sortby = 'id';
		}
		
		$count = DB::first("select COUNT(id) as total from adoptor where 1=1 $sqlpart ");
	
		if($count->total>0)
		{
			$sql = "select id from adoptor order by $sortby $orderby limit $offset,$limit";	
	
			$adoptor = DB::query($sql);
			$animal_repo = new Animal;
			foreach($adoptor as $ind => $value)
			{	
				$newsData 		= $this->get_adoptor($value->id);	
				$temp 			= json_encode($newsData);
				$newsData 		= json_decode($temp, true);			
				$data[]  		= $newsData['record'];
				$data[$ind]['date_added']  =  Utility::dat($data[$ind]['date_added'], 'M d, Y');
				$data[$ind]['animal_data'] = $animal_repo->get_animal($data[$ind]['animal_id']);
				$status 		= 'success';
			}
		}
		else
		{
			$data[] =	'';
			$status = 'error';	
		}
		
		return array('records' => $data, 'totalrecords' => $count->total,'status' => $status);
	}


	public function get_chart($param)
	{
		
		$sortby			 = $param['sortby'];
		$orderby 		 = $param['orderby'];
		$status = 'error';
		$curr_data = date('Y-m-d');
		$dataf = array();
		
		$count   = DB::first("SELECT  IFNULL(COUNT(id), 0) as total FROM `adoptor` WHERE status = 'Active' ");	

		$data = array();
			for($i=0; $i < 12; $i++){
			
			$new_month = date('n',strtotime($curr_data.' -'.$i.' months'));
		
			
					$query= "SELECT IFNULL(sum(amount),0) as amount, IFNULL(count(id),0) as adobtions FROM `adoptor` where MONTH(`date_added`) = $new_month  ";
 
 					/*SELECT IFNULL(sum(amount),0) as amount, IFNULL(count(id),0) as adobtions FROM `adoptor` where MONTH(`date_added`) = 4 and MONTH(`date_added`) between 5 and 9*/
					
 					$sql = DB::query($query);
					if(count($sql) > 0){
						
						$cur_date = date('Y-m-d',strtotime($curr_data.' -'.$i.' months'));
//						$cur_date = new DateTime($cur_date );
						$cur_date = explode('-',$cur_date);
						$cur_date = implode(',',$cur_date);
						foreach($sql as $ind => $value)
						{	
							
							$data[$i]['amount']	   =	$value->amount;
							$data[$i]['adobtions'] =	$value->adobtions;
							$data[$i]['date']  	   =	$cur_date;
							$data[$i]['index']     =	$i;
							$dataf[$i] = $data;	
						}
					
					}else{
					//	$data = array();
						$data[$i]['amount']	  	  = 0;
						$data[$i]['adobtions']	  =	0;
						$data[$i]['date'] 		  =	$cur_date;
						$data[$i]['index'] 		  =	$i;
						$dataf[] = $data;	
					}
					
				 $status = 'success';
		}
		
			$dataf = self::array_sort_by_column($data, 'index',SORT_DESC);
					
		return array('records' => $data,'status' => $status, 'count'=> $count->total);
	}
	
	public function get_chart_revenue($param)
	{
		
		$sortby			 = $param['sortby'];
		$orderby 		 = $param['orderby'];
		
		$date1 		 = $param['date1'];
		$date2 		 = $param['date2'];
		$status = 'error';
		$curr_data = date('Y-m-d');
		$dataf = array();
		
	//	$count   = DB::first("SELECT  IFNULL(COUNT(id), 0) as total FROM `adoptor` WHERE status = 'Active' ");	
		$count = 0;
		$datetime1 = date_create($date1);
		$datetime2 = date_create($date2);
		$interval = date_diff($datetime1, $datetime2);
		$limit =  (int) $interval->format('%m months');
		
		$data = array();
			for($i=0; $i <= $limit; $i++){
			
				$new_month = date('n',strtotime($curr_data.' -'.$i.' months'));
		
			
					$query= "SELECT IFNULL(sum(amount),0) as amount, IFNULL(count(id),0) as adobtions FROM `adoptor` where MONTH(`date_added`) = $new_month   ";
 
 				
					
 					$sql = DB::query($query);
					if(count($sql) > 0){
						
						$cur_date = date('Y-m-d',strtotime($curr_data.' -'.$i.' months'));
						$cur_date = explode('-',$cur_date);
						$cur_date = implode(',',$cur_date);
						foreach($sql as $ind => $value)
						{	
							
							$data[$i]['amount']	   =	$value->amount;
							$data[$i]['adobtions'] =	$value->adobtions;
							$data[$i]['date']  	   =	$cur_date;
							$data[$i]['index']     =	$i;
							$dataf[$i] = $data;	
							$count += $value->amount;
						}
					
						}else{
					//	$data = array();
						$data[$i]['amount']	  	  = 0;
						$data[$i]['adobtions']	  =	0;
						$data[$i]['date'] 		  =	$cur_date;
						$data[$i]['index'] 		  =	$i;
						$dataf[] = $data;	
					}
						
				 $status = 'success';
		}
		
			$dataf = self::array_sort_by_column($data, 'index',SORT_DESC);
					
		return array('records' => $data,'status' => $status, 'count'=> $count);
	}
	
	
	public function array_reverse_keys($ar){
    return array_reverse(array_reverse($ar,false),false);
	} 

	public function array_sort_by_column(&$arr, $col, $dir) {
    $sort_col = array();
    foreach ($arr as $key=> $row) {
        $sort_col[$key] = $row[$col];
    }
    array_multisort($sort_col, $dir, $arr);
	}

	public function checkout_paypal($param)
	{
		// Include config file
		
	require_once 'application/libraries/paypal/config.php';
	
	$cc_type  = $param['cc_type'];
	$cc_num   = $param['cc_num'];
	$name_exp = $param['fname'];
	$fname	  = $name_exp[0];
	$lname 	  = $name_exp[1];
	//$exp_date =  '022014';//date('YM',strtotime($param['exp_date']));
	$exp_date = $param['exp_date'].''.$param['exp_date2'];

	$ccv2 	  = $param['ccv2'];
	$address  = $param['address'];
	$city 	  = $param['city'];
	$state 	  = $param['state'];
	$country  = $param['country'];
	$zip 	  = $param['zip'];
	$amt 	  = $param['amt'];
	$desc	  = $param['desc'];
	
	//$lname = '';
	//$fname	=  explode(' ',$fname);
	
	if(count($fname) > 1) $lname = $fname[1];
	// Store request params in an array
	$request_params = array(
						'METHOD' => 'DoDirectPayment', 
						'USER' => $api_username, 
						'PWD' => $api_password, 
						'SIGNATURE' => $api_signature, 
						'VERSION' => $api_version, 
						'PAYMENTACTION' => 'Sale', 					
						'IPADDRESS' => $_SERVER['REMOTE_ADDR'],
						'CREDITCARDTYPE' => $cc_type, 
						'ACCT' => $cc_num, 						
						'EXPDATE' => $exp_date, 			
						'CVV2' => $ccv2, 
						'FIRSTNAME' => $fname, 
						'LASTNAME' =>  $lname, 
						'STREET' => $address, 
						'CITY' =>   $city, 
						'STATE' => $state, 					
						'COUNTRYCODE' => 'US', 
						'ZIP' => $zip , 
						'AMT' => $amt , 
						'CURRENCYCODE' => 'USD', 
						'DESC' => $desc
						);
						
	// Loop through $request_params array to generate the NVP string.
	$nvp_string = '';
	foreach($request_params as $var=>$val)
	{
		$nvp_string .= '&'.$var.'='.urlencode($val);	
	}
	
	// Send NVP string to PayPal and store response
	$curl = curl_init();
			curl_setopt($curl, CURLOPT_VERBOSE, 1);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($curl, CURLOPT_TIMEOUT, 30);
			curl_setopt($curl, CURLOPT_URL, $api_endpoint);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $nvp_string);
	
	$result = curl_exec($curl);
	
	curl_close($curl);
	
	// Parse the API response
	$result_array = $this->NVPToArray($result);
	
	return $result_array;

// Function to convert NTP string to an array

	}
	
	public function NVPToArray($NVPString)
	{
		$proArray = array();
		while(strlen($NVPString))
		{
			// name
			$keypos= strpos($NVPString,'=');
			$keyval = substr($NVPString,0,$keypos);
			// value
			$valuepos = strpos($NVPString,'&') ? strpos($NVPString,'&'): strlen($NVPString);
			$valval = substr($NVPString,$keypos+1,$valuepos-$keypos-1);
			// decoding the respose
			$proArray[$keyval] = urldecode($valval);
			$NVPString = substr($NVPString,$valuepos+1,strlen($NVPString));
		}
		return $proArray;
	}
	
	
}

?>
<?php

class Category
{

/*************insert catregory*******************/

public function change_ids(){
		
		$sql   = DB::query("SELECT `id`,`animal_id`,`category_id` FROM `animal` ");	
		
		foreach($sql as $ind => $value)
		{
			/*$cat_data = $this->get_category($value->category_id);
			$cat_data = json_decode(json_encode($cat_data), TRUE);
			$cat_data = $cat_data['record'];
			
			$new_id =  $cat_data['id_prefix'].$value->id;
					
			$data = DB::table('animal')->where('id','=',$value->id)->update( array( 
												'id'	 => $new_id
																				));
		  //$key  = 'animal_'.$value->id;
		  	//Cache::forget($key);*/
			$new_id = $value->id;
			
		
/*			$data = DB::table('adoptor')->where('animal_id','=',$value->animal_id)->update( array( 
																		'animal_id'	 => $new_id
																				));
			
			
			$data = DB::table('animal_friend')->where('animal_id','=',$value->animal_id)->update( array( 
																		'animal_id'	 => $new_id
																				));
																				
						
			$data = DB::table('animal_friend')->where('friend_id','=',$value->animal_id)->update( array( 
																		'friend_id'	 => $new_id
																				));
																						
			$data = DB::table('encounters')->where('animal_id','=',$value->animal_id)->update( array( 
																		'animal_id'	 => $new_id
																				));
																				
																							
			$data = DB::table('follow')->where('animal_id','=',$value->animal_id)->update( array( 
																		'animal_id'	 => $new_id
																				));		*/																
			
			
			$data = DB::table('log')->where('ref_id','=',$value->animal_id)->update( array( 
																			'ref_id'	 => $new_id
																				));		
																				
		/*	$data = DB::table('animal_researchers')->where('animal_id','=',$value->animal_id)->update( array( 
																			'animal_id'	 => $new_id
																				));										*/						
																				
																				
		//	return array('status' => 'success');		

		}
	
	}
	
	public function create_category($param)
	{
		$id = DB::table('category')->insert_get_id(array('title' 			=> $param['title'],
														  'icon' 			=> 'images/icon/'.$param['icon'],
														  'color_code'  	=> $param['color_code'],
														  'type' 			=> $param['type'],
														  'minimum_amount' 	=> $param['minimum_amount'],
														  'api_url' 		=> $param['api_url'],
														   'id_prefix' 		=> $param['id_prefix'],
														  'image_url' 		=> $param['image_url']	 
															));
														
		if($id > 0)
		{	
			$key = 'category_'.$id;
			$data['id']		    	= $id;
			$data['title'] 			= $param['title'];
			$data['icon'] 			= 'images/icon/'.$param['icon'];
			$data['color_code'] 	= $param['color_code'];
			$data['type'] 			= $param['type'];
			$data['minimum_amount'] = $param['minimum_amount'];
			$data['api_url'] 		= $param['api_url'];
			$data['id_prefix'] 		= $param['id_prefix'];
			$data['image_url'] 		= $param['image_url'];
			
			Cache::forever($key,$data);
			$status = 'success';
		}
			return array('status'=>$status);	
	}
	
/*************update catregory*******************/
	public function update_category($param)
	{
		$id		 		= Utility::mysql_query_string($param['id']);
		$title 			= Utility::mysql_query_string($param['title']);
		$icon 			= Utility::mysql_query_string($param['icon']);
		$color_code 	= Utility::mysql_query_string($param['color_code']);
		$type 			= Utility::mysql_query_string($param['type']);
		$api_url		= Utility::mysql_query_string($param['api_url']);
		$minimum_amount	= Utility::mysql_query_string($param['minimum_amount']);
		$id_prefix		= Utility::mysql_query_string($param['id_prefix']);
		$image_url		= Utility::mysql_query_string($param['image_url']);
		
		$count = DB::first("SELECT count(id) as total FROM `category` WHERE `id` = '".$id."' ");
		if($count->total > 0)
		{
			$data = DB::query('UPDATE `category` SET
								`title` 		= "'.$title.'",
								`icon` 			= "'.'images/icon/'.$icon.'",
								`color_code` 	= "'.$color_code.'",
								`type` 			= "'.$type.'",
								`api_url` 		= "'.$api_url.'",
								`id_prefix` 		= "'.$id_prefix.'",
								`minimum_amount` 		= "'.$minimum_amount.'",
								`image_url` 	= "'.$image_url.'"
								 WHERE `id`     = "'.$id.'" ;');
							
			$key = 'category_'.$id;
			Cache::forget($key);
			return array('status'=>'success'); 
		}
		else if($count->total == 0)
		{
			return array('status'=>'error', 'msg'=>'no record found for this id');	
		}	
		
	}

/*************get all catregories*******************/
	public function get_categories($param)
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
		$count  = DB::first("select COUNT(id) as total from category $sqlpart");
		$data   = array();
 		$status = 'error';
 
		if($count->total>0)
		{
			$sql   = "select id from category $sqlpart order by $sortby $orderby limit $offset,$limit ";	
			$category = DB::query($sql);
	 
			foreach($category as $ind => $value)
			{
				$categoryData	= $this->get_category($value->id);	
		
				$data[] = $categoryData['record'];
		
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
	
	public function category_dashboard($param)
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
		$count  = DB::first("select COUNT(id) as total from category $sqlpart");
		$data   = array();
 		$status = 'error';
 
		if($count->total>0)
		{
			$sql   = "select id from category $sqlpart order by $sortby $orderby limit $offset,$limit ";	
			$category = DB::query($sql);
	   	    
			foreach($category as $ind => $value)
			{
				$categoryData	= $this->get_category($value->id);	
				
				$category_count = DB::first("SELECT IFNULL(COUNT(id), 0) as total FROM `adoptor` WHERE category_id = ".$value->id." and  status = 'Active' ");
				$category_sum = DB::first("SELECT sum(amount) as total FROM `adoptor` WHERE category_id = ".$value->id." and  status = 'Active' ");
				
				if(!is_array($categoryData['record'] )){
					$categoryData['record'] = 	get_object_vars($categoryData['record']);
				}
				
				$categoryData['record']['category_count'] = $category_count->total;
				$categoryData['record']['category_sum']   = $category_sum->total;
				$data[] = $categoryData['record'];
		
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
	
	
	public function category_revenue($param)
	{
		$keyword		 = Utility::mysql_query_string($param['keyword']);
		$sortby			 = Utility::mysql_query_string($param['sortby']);
		$orderby 		 = Utility::mysql_query_string($param['orderby']);
		$offset 		 = Utility::mysql_query_string($param['offset']);
		$limit 		 	 = Utility::mysql_query_string($param['limit']);
		$id				 = Utility::mysql_query_string($param['id']);
		$date1			 = date('Y-m-d', strtotime($param['date1']));
		$date2			 = date('Y-m-d', strtotime($param['date2']));
		$sqlpart='';
	
		if ($keyword!='') $sqlpart .=" WHERE (title LIKE '%$keyword%') ";
		else if ($id != '') $sqlpart .= "WHERE `id` = '".$id."' ";
		$count  = DB::first("select IFNULL(COUNT(id),0) as `total` from `category` $sqlpart");
		$data   = array();
		$data2   = array();
 		$status = 'error';
 		$grand_total = 0;
		if($count->total>0)
		{
			$sql   = "select id from category $sqlpart order by $sortby $orderby limit $offset,$limit  ";	
			$category = DB::query($sql);
	   	    
			foreach($category as $ind => $value)
			{
				$categoryData	= $this->get_category($value->id);	
				
				//$category_count = DB::first("SELECT IFNULL(COUNT(id), 0) as total FROM `adoptor` WHERE category_id = ".$value->id." and  status = 'Active' ");
				$query = "SELECT IFNULL(sum(amount),0) as total FROM `adoptor` WHERE category_id = ".$value->id." and  status = 'Active' and date_added between DATE('$date1') AND DATE('$date2')";
				//echo $query; die();
				$category_sum = DB::first($query);
				
				if(!is_array($categoryData['record'] )){
					$categoryData['record'] = 	get_object_vars($categoryData['record']);
				}
				
				//$categoryData['record']['category_count'] = $category_count->total;
				$categoryData['record']['category_count']   = $category_sum->total;
				$data[] = $categoryData['record'];
				$data[$ind]['date1']	=  Utility::dat($date1, 'M d, Y');
				$data[$ind]['date2']	=  Utility::dat($date2, 'M d, Y');
				$status = 'success';
			}
			
			foreach($category as $ind => $value)
			{
				$categoryData	= $this->get_category($value->id);	
				
				//$category_count = DB::first("SELECT IFNULL(COUNT(id), 0) as total FROM `adoptor` WHERE category_id = ".$value->id." and  status = 'Active' ");
				// and date_added between DATE('$date1') AND DATE('$date2')
				$query = "SELECT IFNULL(sum(amount),0) as total FROM `adoptor` WHERE category_id = ".$value->id." and  status = 'Active' ";
				//echo $query; die();
				$category_sum = DB::first($query);
				
				if(!is_array($categoryData['record'] )){
					$categoryData['record'] = 	get_object_vars($categoryData['record']);
				}
				
				//$categoryData['record']['category_count'] = $category_count->total;
				$categoryData['record']['category_count']   = $category_sum->total;
				$data2[] = $categoryData['record'];
				$data2[$ind]['date1']	=  Utility::dat($date1, 'M d, Y');
				$data2[$ind]['date2']	=  Utility::dat($date2, 'M d, Y');
				$grand_total += $category_sum->total;
				$status = 'success';
			}
			
		}
		else
		{
			$data[] =	'';
			$status = 'error';	
		}
	
			return array('records' => $data,'records_total' => $data2, 'totalrecords' => $count->total,'status' => $status,'grand_total' => $grand_total );
		}
	
	
	
/************************Get single Category Detail*******************************/	
	public function get_category($id)
	{
		
		$key  = 'category_'.$id;
		//$data = Cache::forget($key);
		$data = Cache::get($key);
		//$data=false;
		if($data==false || $data==NULL)
		{
		   $count = DB::first("select COUNT(id) as total from category where `id` = '".$id."' Limit 1;");

		   if($count->total>0)
		   {
				$data = DB::first("select * from category where `id` = '".$id."' Limit 1; ");
				Cache::forever($key, $data);
		   }
		   else
		   {
				return false;   
		   } 
		}
		return array('record' => $data,'totalrecords' => '1','status' => 'success');
	}	
	
/************************Delete Category*******************************/	
			
	public function del_category($id)
	{
		$status = 'error';	
		$key = 'category_'.$id;

		$count = DB::table('category')->where('id','=',$id)->count();
		if($count > 0)
		{
			$data = DB::table('category')->where('id','=',$id)->delete();
			Cache::forget($key);
			$msg 		= 'record deleted';
			$status 	= 'success';
		}
		else
		{
			$msg = 'no record found for this id';
		} 

		return array('status' => $status, 'msg' => $msg);
	}


/************************adoption status change*******************************/	

	public function cat_adoption_status_change($param)
	{
		$id = $param['id'];
		$key	= 'category_'.$id;
		$status = $param['status'];
		
		$count		= DB::first("select count(id) as total from category where id = '".$id."' ");	
		if($count->total >0)
		{
		$sql   = "UPDATE `category` set `active_adoption` = '$status' WHERE id = '".$id."'";	
		$user = DB::query($sql);
			Cache::forget($key);
		$status = 'success';
		return array('status'=>$status);
		}else
		{
			$status = 'error';
			$msg = 'No record found for this id';
			return array('status'=>$status , 'msg'=>$msg);
		} 
	}

/************************gps status change*******************************/		

	public function cat_gps_status_change($param)
	{
		$id = $param['id'];
		$key	= 'category_'.$id;
		$status = $param['status'];
		
		$count		= DB::first("select count(id) as total from category where id = '".$id."' ");	
		if($count->total >0)
		{
		$sql   = "UPDATE `category` set `active_gps` = '$status' WHERE id = '".$id."' ";	
	
		$user = DB::query($sql);
		Cache::forget($key);
		$status = 'success';
		return array('status'=>$status);
		}else
		{
			$status = 'error';
			$msg = 'No record found for this id';
			return array('status'=>$status , 'msg'=>$msg);
		} 
	}	
	
}
?>
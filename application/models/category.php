<?php

class Category
{

/*************insert catregory*******************/
	public function create_category($param)
	{
		$id = DB::table('category')->insert_get_id(array('title' 		=> $param['title'],
														  'icon' 		=> 'images/icon/'.$param['icon'],
														  'color_code'  => $param['color_code'],
														  'type' 		=> $param['type'],
														  'api_url' 	=> $param['api_url'],
														  'image_url' 	=> $param['image_url']	 
															));
														
		if($id > 0)
		{	
			$key = 'category_'.$id;
			$data['id']		    = $id;
			$data['title'] 		= $param['title'];
			$data['icon'] 		= 'images/icon/'.$param['icon'];
			$data['color_code'] = $param['color_code'];
			$data['type'] 		= $param['type'];
			$data['api_url'] 	= $param['api_url'];
			$data['image_url'] 	= $param['image_url'];
			
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
	
}
?>
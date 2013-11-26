<?php  ini_set('max_execution_time','99999999999999999999999999999999');
class Media {
	


	//Get Single Record
	public function get_media($id)
	{
		$key	= 'media_'.$id;
		$data=Cache::get($key);
		
		if($data==false || $data==NULL)
		{
			$count = DB::table('media')->where('id','=',$id)->count();
			if($count > 0)
			{
				$data = DB::table('media')->where('id','=',$id)->first();
				Cache::forever($key,$data);
			}
			else
			{
				return false;	
			}			
		}
		
			return array('record' => $data,'totalrecords' => '1','status' => 'success');
	}
	
//Get All Records
	public function get_medias($param)
	{
		$data 		=	array();
		
		$limit		= Utility::mysql_query_string($param['limit']);
		$offset     = Utility::mysql_query_string($param['offset']);
		$sortby     = Utility::mysql_query_string($param['sortby']);
		$orderby    = Utility::mysql_query_string($param['orderby']);
		
		$sqlpart 	= "";
		$status  	= "error";
		
		if(isset($param['id']))	$id			= Utility::mysql_query_string($param['id']);
		else $id = "";
		if(isset($param['encounter_id']))	$encounter_id			= Utility::mysql_query_string($param['encounter_id']);
		else $encounter_id = "";
		
		$sqlChunk = array();
		
		if($encounter_id != '')  $sqlChunk[] = "  encounter_id  = ".$encounter_id." ";
		if($id != NULL && $id != "")	$sqlChunk[] = " id= $id ";

		if(count($sqlChunk) > 0 ){
			$sqlpart = " where " . implode(" and ", $sqlChunk);
		}

		$count  = DB::first("select COUNT(id) as total from `media` $sqlpart");
		if($count->total > 0)
		{
			$sql   = "SELECT `id` FROM `media`  $sqlpart order by $sortby $orderby limit $offset,$limit ";	
			$medias = DB::query($sql);
	 
			foreach($medias as $ind => $value)
			{
				$mediaData	= $this->get_media($value->id);	
				$temp 			= json_encode($mediaData);
				$mediaData	 	= json_decode($temp, true);		
						
				$data[$ind]['id']  			= $mediaData['record']['id'];
				$data[$ind]['encounter_id'] = $mediaData['record']['encounter_id'];
				$data[$ind]['image_name']	= $mediaData['record']['image_name'];
				
				$data[$ind]['thumb_path']	= $mediaData['record']['thumb_path'];
				//$data[$ind]['verbatim_locality']	= $enData['record']['verbatim_locality'];
				$status 		= 'success';
				
				
			}
		}
		else
		{
			$data =	array();
			$status = 'error';	
		}
	
		return array('records' => $data, 'totalrecords' => $count->total,'status' => $status);
	}		
	
	
//Get All Records
	public function get_animal_media($param)
	{
		$total_re	= 0;
		$data 		=	array();
		$status 	= 'error';
		$limit		= Utility::mysql_query_string($param['limit']);
		$offset     = Utility::mysql_query_string($param['offset']);
		$sortby     = Utility::mysql_query_string($param['sortby']);
		$orderby    = Utility::mysql_query_string($param['orderby']);
		
		if(isset($param['animal_id']) || $param['animal_id'] == "" || $param['animal_id'] == NULL)
			$animal_id			= Utility::mysql_query_string($param['animal_id']);
		else 
			return array('records' => array(), 'totalrecords' => 0,'status' => 'error');
		
		$sqlpart = '';

		$count  = DB::first("select COUNT(id) as total from `encounters` where `animal_id` = '$animal_id'");
		
		if($count->total > 0)
		{
			$sql   = "SELECT `id` FROM `encounters` where `animal_id` = '$animal_id' order by `id` DESC limit $offset, $limit ";	
			$encounters = DB::query($sql);
	 		$en_ids = '';
			foreach($encounters as $encInd => $encounter)
			{
				$count  = DB::first("select COUNT(id) as total from `media` WHERE encounter_id = " . $encounter->id );
				if($count->total > 0)
				{
					if($encInd == 0)
					{
						$en_ids= $encounter->id;
					}
					else
					{
						$en_ids= $en_ids.','.$encounter->id;
					}
					
				}
			}
			$en_ids = trim($en_ids, ',');
			
			if($en_ids != '')
			{
				$sql_co   = DB::first("SELECT count(id) as total FROM `media`  WHERE encounter_id IN (" . $en_ids .")  ");	
				if($sql_co->total > 0)
				{
					$sql   = "SELECT `id` FROM `media`  WHERE encounter_id IN (" . $en_ids .") order by id desc limit $offset,$limit ";	
					$medias = DB::query($sql);
		 		
					foreach($medias as $ind => $value)
					{
						$mediaData	= $this->get_media($value->id);	
						$temp 			= json_encode($mediaData);
						$mediaData	 	= json_decode($temp, true);				
						
						/*$temp = array();
		
						$temp['id']  			= $mediaData['record']['id'];
						$temp['encounter_id'] 	= $mediaData['record']['encounter_id'];
						$temp['image_name']		= $mediaData['record']['image_name'];
						$temp['thumb_path']		= $mediaData['record']['thumb_path'];*/
						
						$en_class	= new Encounter;
						$enData		= $en_class->get_encounter($mediaData['record']['encounter_id']);	
						$temp 		= json_encode($enData);
						$enData	 	= json_decode($temp, true);
						$mediaData['record']['verbatim_locality'] = $enData['record']['verbatim_locality'];
						$data[] = $mediaData['record'];
		
						$status 		= 'success';
						
						
					}
				}
				else
				{
					$status 		= 'error';
				}
				
			}

			
			
		}
		if(isset($sql_co->total))  $total_re = $sql_co->total; 

		return array('records' => $data, 'totalrecords' => $total_re ,'status' => $status);
	}		
	




}
?>
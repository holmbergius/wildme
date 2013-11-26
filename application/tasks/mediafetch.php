<?php

class Mediafetch_Task {

	public function run(){
	
		$category_id   	= 1;
		$log    		= new Getrecord;
		$data   		= $log->get_all_media($category_id);
		$output 		= $data;
		echo json_encode($output);
	}

}
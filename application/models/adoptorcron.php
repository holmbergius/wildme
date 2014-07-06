<?php

// addadoptor
// get_adoptor
// delete_adoptor
// get_csvNewsletter

class Adoptorcron  {

	/*
	Method For : Adding user subscription email address
	Type : POST
	Tested Local : Yes
	Tested Live : YES
	*/
	public function markexpire()
	{
	   $curr_date = date('Y-m-d');
       $status = 'error';
	
	   $count = DB::first("select IFNULL(COUNT(id),0) as total from reminder");

		   if($count->total>0){
			   
			  $sql = DB::query("select * from reminder");
			  
			  foreach($sql as $ind => $value){
				
				$this->check_adopters($value);
   			    $status = 'success';
			  }
		   }
		   		   
		return array('status' => $status);
	}
	
	public function check_adopters($data){
		
		 $count = DB::first("select IFNULL(COUNT(id),0) as total from adoptor where `status` = 'Active' and `user_type` = 'application' ");

		   if($count->total>0){
			   
			  $sql = DB::query("select * from adoptor where `status` = 'Active' and `user_type` = 'application' ");
			  $user_repo = new  User;
			  $animal_repo = new Animal;
			  $arr = array();
			  foreach($sql as $ind => $value){
					
					// adoption expiration date
					$date1  = date('Y-m-d', strtotime($value->expiration_date));
					// date 2
					$date2  = date('Y-m-d', strtotime($date1.' - '.$data->interval.' '.$data->period));
					
					$curr_date = date('Y-m-d');
					
					$diff2  = strtotime($date2) -  strtotime($curr_date) ;
					 
					
					$diff = strtotime($date2) -  strtotime($curr_date) ; 
      		     	$hours = round(($diff / 3600 ));
      			  	$sent_mail = 0;
					
      			  	if($hours <= 24 && $hours >= 0 && $diff2 >= 0){
      					$sent_mail = 1;
      			  	}else if($hours < 0 && $diff2 < 0){
						$sent_mail = -1;
					}
					else{  
      			  		$sent_mail = 0;
      			 	}
					 $arr[] = $date2;
					 
						$userData 		= $user_repo->get_user($value->uid);	
						$temp 			= json_encode($userData);
						$userData 		= json_decode($temp, true);	
						$userData 		= $userData['record'];
						$animalData 	= $animal_repo->get_animal($value->animal_id);	
						$temp 			= json_encode($animalData);
						$animalData 	= json_decode($temp, true);	
						$animalData 	= $animalData['record'];
					
					if($sent_mail > 0){
						
							/*
							[USERNAME] = User name
							[MARKEDINDIVIDUAL] = Animal name
							[MARKEDINDIVIDUAL_LINK] = The link goes to animal's profile
							[MARKEDINDIVIDUAL_ADOPTION_LINK] = The link goes to payment of adoption fees
							*/							
							$profile_url = Config::get('application.web_url').'profile/'.$animalData['label'];
							$email_text = $data->template;
							$email_text = str_replace('[USERNAME]',$userData['name'],$email_text) ;
							$email_text = str_replace('[MARKEDINDIVIDUAL]',$animalData['nick_name'],$email_text) ;
							$email_text = str_replace('[MARKEDINDIVIDUAL_LINK]',$profile_url,$email_text) ;
							$email_text = str_replace('[MARKEDINDIVIDUAL_ADOPTION_LINK]',$profile_url,$email_text) ;
							
							$this->send_email($userData['email'],$data->title,$email_text);
							
					}
					
					if($sent_mail < 0){ // expire adoption
					
					
					$animal_update = DB::table('adoptor')->where('id','=',$value->id)->update( array( 
												'status' => 'Inactive',
												'quote' => ''
											 )); 
					$key  = 'adoptor_'.$value->id;
					Cache::forget($key);	
					
						$link = Config::get('application.web_url').'profile/'.$animalData['label'];
						$email_text = 'Dear '.$userData['name'].',Your adoption for the "'.$animalData['label'].'" has expired. You can adopt '.$animalData['nick_name'].' '.$animalData['label'].' again by visiting WildMe. Follow the link below and adopt an animal now to show your support to Wildife.<br><br>';					
						$email_text .= '<a href="'.$link.'">Goto Wildme</a>';
			
						$email_text .= '<br><br>Thanks,WildMe Team';
						
						$subject = 'Your adoption has been expired';
						$this->send_email($userData['email'],$subject,$email_text);
						
						$animalupdate = DB::table('animal')->where('id','=',$animalData['id'])->update( array( 
												'first_adoptor' =>'',
												'quote' => ''
											 )); 
						   $key  = 'animal_'.$animalData['id'];
						   Cache::forget($key);	
						   
						   
						
						// check 2nd highest adoptor
						
						 $sql = DB::first("select IFNULL(COUNT(id),0) as total from adoptor where `status` = 'Active' and `user_type` = 'application' and `animal_id` = '".$animalData['id']."' and `uid` != '".$userData['id']."' order by id desc limit 1");
							
						 if($sql->total > 0){
							 
					
						
							
						  $sql = DB::first("select * from adoptor where `status` = 'Active' and `user_type` = 'application' and `animal_id` = '".$animalData['id']."' and `uid` != '".$userData['id']."' order by id desc limit 1");

						$userData2 		= $user_repo->get_user($sql->uid);	
						$temp 			= json_encode($userData2);
						$userData 		= json_decode($temp, true);	
						$userData2 		= $userData['record'];
						
						   $link = Config::get('application.web_url').'profile/'.$animalData['label'].'/'.$sql->uid;

						   $email_text = 'Congratulations! you have become the first adopter for '.$animalData['nick_name'].'.Provide a quote below to associate with the animal profile:<br><br>click the link below<br><br><a href="'.$link.'">Goto Wildme</a> ';
						   $email_text .= '<br><br>Thanks, <br><br>Wildme Team.';
						

							
						   $subject = 'Your have a chance to add quote for '.$animalData['nick_name'];
							
						   $this->send_email($userData2['email'],$subject,$email_text);
						   
						   $animalupdate = DB::table('animal')->where('id','=',$animalData['id'])->update( array( 
												'first_adoptor' => $sql->uid,
												'quote' => ''
											 )); 
						   $key  = 'animal_'.$animalData['id'];
						   Cache::forget($key);	
						 }
					}		
			  }
			  
			  //print_r($arr); die();
		   }
	}
	
	public function send_email($email, $subject, $message)
 	{
		
	    $headers = "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\n";
		$headers .= "X-Priority: 3\n";
		$headers .= "X-MSMail-Priority: Normal\n";
		$headers .= "X-Mailer: php\n";
		$headers .= "From: \"Wild Me\" <support@wildme.com>\n";
		$headers .= "Return-Path: support@wildme.com\n";
		$headers .= "Return-Receipt-To: support@wildme.com\n";
      //  $email    = 'meran@cygnismedia.com, asad.iqbal@cygnismedia.com';
		
	   if(mail($email,$subject, $message, $headers)){
	  // if(true){
		   	return true;
	   }else{
		   return false;
	   }
 	}
	
}

?>
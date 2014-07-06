<?php

class Payment{
	

// Store request params in an array

 public function transaction(){
	
	$api_username   =  	Config::get('application.api_username');
	$api_password   =  	Config::get('application.api_password');
	$api_signature  = 	Config::get('application.api_signature');
	$api_version 	=   Config::get('application.api_version');
	$curency_code	=   Config::get('application.curency_code');
	$country_code	=   Config::get('application.country_code');
	$desc			=   Config::get('application.paypal_description');
	
	 $request_params = array
						(
						'METHOD' => 'DoDirectPayment', 
						'USER' => $api_username, 
						'PWD' => $api_password, 
						'SIGNATURE' => $api_signature, 
						'VERSION' => $api_version, 
						'PAYMENTACTION' => 'Sale', 					
						'IPADDRESS' => $_SERVER['REMOTE_ADDR'],
						'CREDITCARDTYPE' => $param['cc_type'], //'MasterCard' 
						'ACCT' =>  $param['cc_number'], 	//'552234000606363'					
						'EXPDATE' =>  $param['cc_exp'], //'022016' 			
						'CVV2' => $param['ccv'], //'456'
						'FIRSTNAME' =>$param['fname'],  //$param['ccv']
						'LASTNAME' => $param['lname'], //'Testerson'
						'STREET' => $param['address'], //'707 W. Bay Drive' 
						'CITY' =>  $param['citry'], //'Largo' 
						'STATE' =>  $param['state'],//FL 					
						'COUNTRYCODE' =>  $country_code, //'US' 
						'ZIP' =>  $param['zip'], //'33770' 
						'AMT' =>  $param['amount'], //'100.00' 
						'CURRENCYCODE' =>  $curency_code, //'USD'
						'DESC' =>  $desc//'Testing Payments Pro' 
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
	//	echo $result.'<br /><br />';
		curl_close($curl);
		
		// Parse the API response
		$result_array = self::NVPToArray($result);
		
		//echo '<pre />';
		//print_r($result_array);
		if($result_array['ACK'] == 'Success' || $result_array['ACK'] == 'Success'){
	
	
		}
		return $result_array;
 
 }

// Function to convert NTP string to an array



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
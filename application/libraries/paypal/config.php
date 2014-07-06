<?php
// Set sandbox (test mode) to true/false.
$sandbox = TRUE;
$PayPalMode = 'sandbox';
// Set PayPal API version and credentials.
$api_version = '85.0';
$api_endpoint = $sandbox ? 'https://api-3t.sandbox.paypal.com/nvp' : 'https://api-3t.paypal.com/nvp';
$api_username = $sandbox ? 'thelondondrycleaners-facilitator_api1.gmail.com' : 'LIVE_USERNAME_GOES_HERE';
$api_password = $sandbox ? '1398114637' : 'LIVE_PASSWORD_GOES_HERE';
$api_signature = $sandbox ? 'An5ns1Kso7MWUdW4ErQKJJJ4qi4-ApbGPX6j7dusJFeUgzWd9Yj3vIu5' : 'LIVE_SIGNATURE_GOES_HERE';
$PayPalCurrencyCode 	= 'USD'; //Paypal Currency Code
$PayPalReturnURL 		= 'http://fb.wildme.org/wildme/public/paypal.php'; //Point to process.php page
$PayPalCancelURL 		= 'http://fb.wildme.org/wildme/public/'; //Cancel URL if user clicks cancel

?>

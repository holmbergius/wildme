<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Facebook Login</title>

<?php echo render('inc.js'); ?>
</head>

<body>

<div id="fb-root"></div>
<script>
//Initialize Facebook
	FB.init({appId: applicationId, status: true, cookie: true, xfbml: true, oauth: true});
	
	(function() {
	 var e = document.createElement('script');
	 e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
	 e.async = true;
	 document.getElementById('fb-root').appendChild(e);
	}());
	

function LoginUserFB(){
  FB.login(function(response) {
		if (response.status === 'connected') 
		{
		  var access_token = FB.getAuthResponse()['accessToken']; /*response.authResponse.access_token;*/
		  FB.api('/me', function(resp) {
			
			  var userId = response.authResponse.userID;
			  var userName	=	resp.name;
			  var birthday	=	'';
			  var userEmail	=	resp.email;
			  var UserGender	=	resp.gender;
			  var accessToken	=	access_token;
			$.get(canvas_url+"api/login",  {id: userId, name: userName, email: userEmail,  accessToken:  access_token, age: birthday, gender: UserGender}, function(data){
				 
				 if(data.status === 'success' || data.status === 'success2')
				 global_uid	=	userId;
			
				// WildMegetAnimalDetails();
				// parent.WildMegetAnimalDetails();
			    // window.parent.document.WildMegetAnimalDetails();
			 	// window.opener.WildMegetAnimalDetails();
				 parent.postMessage(global_uid, "*");
			//	 window.parent.WildMegetAnimalDetails();
			  }, "json" );
				
			  });
		}
		if(response.status === "not_authorized")
		{
		}
	}, {scope: 'email' }); //publish_stream
} 

  $(document).ready(function(){ 

    LoginUserFB();

  });
	
</script>

</body>
</html>
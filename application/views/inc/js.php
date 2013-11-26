<link rel="icon" type="image/gif" href="<?php echo Config::get('application.doc_path');?>images/logo-16.gif" />
<link rel="stylesheet" href="<?php echo Config::get('application.web_url');?>css/style.css?timer=4" type="text/css">
<link rel="stylesheet" type="text/css" href="<?php echo Config::get('application.web_url');?>css/font-awesome.css"/>
 <link href="<?php echo Config::get('application.web_url');?>bootstrap/css/bootstrap.css" rel="stylesheet">
<link href="<?php echo Config::get('application.web_url');?>css/flat-ui.css" rel="stylesheet">

<!--[if lte IE 7]>
<link rel="stylesheet" type="text/css" href="css/ie7.css"/>
<link rel="stylesheet" type="text/css" href="css/font-awesome-ie7.min.css"/>
<![endif]-->
<!--[if lte IE 9]>
<link rel="stylesheet" type="text/css" href="css/ie9.css"/>
<![endif]-->
<!--[if lte IE 8]>
<link rel="stylesheet" type="text/css" href="css/ie8.css"/>
<![endif]-->
<script src="<?php echo Config::get('application.web_url');?>js/config.js?timer=<?php echo date("Ymdhis");?>" type="text/javascript"></script>
<script src="//connect.facebook.net/en_US/all.js"></script>
<script src="<?php echo Config::get('application.web_url');?>js/jquery.js" type="text/javascript"></script>
<script src="<?php echo Config::get('application.web_url');?>js/retina.js" type="text/javascript"></script>
<script src="<?php echo Config::get('application.web_url');?>js/jquery.plugs.js" type="text/javascript"></script>
<script src="<?php echo Config::get('application.web_url');?>js/jquery.tinyscrollbar.min.js" type="text/javascript"></script>
<script src="<?php echo Config::get('application.web_url');?>js/jquery.tools.min.js" type="text/javascript"></script>
<script src="<?php echo Config::get('application.web_url');?>js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo Config::get('application.web_url');?>js/bootstrap-select.js" type="text/javascript"></script>
<script src="<?php echo Config::get('application.web_url');?>js/application.js" type="text/javascript"></script>
<script src="<?php echo Config::get('application.web_url');?>js/jquery-function.js" type="text/javascript"></script>

<script src="<?php echo Config::get('application.web_url');?>js/urlutils.js" type="text/javascript"></script>
<script src="<?php echo Config::get('application.web_url');?>js/jquery.timeago.js" type="text/javascript"></script>


<script src="<?php echo Config::get('application.web_url');?>js/functions.js?timer=<?php echo date("Ymdhis");?>" type="text/javascript"></script>
<script>


$(document).ready(function() {
	
	if((navigator.userAgent.match(/iPhone/i)) ||  (navigator.userAgent.match(/iPad/i)) )
	{
	  $('#show_popup_login').fadeIn();
	}
	
	<?php
		//if(!Session::has('user_id')):

		//echo "FBLoginStatus();";

		//endif;
	?>
	
	
	/*function logged_in(response) {
	 	session_key = '';
	 	access_token = response.authResponse.accessToken;
	 	uid = response.authResponse.userID;
		FB.api('/me', function(resp) {
		
			  var userId = response.authResponse.userID;
			  var userName	=	resp.name;
			  var birthday	=	'';
			  var userEmail	=	resp.email;
			  var UserGender	=	resp.gender;
			  var accessToken	=	access_token;
			  
			  uId	=	userId;
			  $.get(canvas_url+"api/login",  {id: userId, name: userName, email: userEmail,  accessToken:  access_token, age: birthday, gender: UserGender}, function(data){
				 
			  }, "json" );
				
		});
	}
	
	FB.getLoginStatus(function(response)
	{
	 FB.Event.subscribe('auth.authResponseChange', logged_in);
	 if (response.authResponse)
	 {
	  logged_in(response);
		} else {
			window.top.location = "https://www.facebook.com/dialog/oauth/?client_id="+applicationId+"&redirect_uri="+canvasPage+"&scope=email";;
		}
	});*/

	//FB.Canvas.setAutoGrow();
	goTop();
  
});

function goTop()
	{
		FB.Canvas.scrollTo(0,0);
	}

/*var server		= window.location.hostname;
var canvas_url    = "";
if (server=='localhost')
canvas_url       = location.protocol+"//localhost/wildme/public/";
//else canvas_url  = location.protocol+"//prod.cygnismedia.com/ignmovie/";
else canvas_url  = location.protocol+"//digital.cygnismedia.com/facebook/wildme/public/";

var API_URL    = canvas_url+"api/";

var canvasPage = "http://apps.facebook.com/wildmeapp/";
//var FanpageLink = "http://www.facebook.com/CygnisWork/app_414751805313094";

var cUrl 		= window.location;
var sPath 		= window.location.pathname;
var sPage 		= sPath.substring(sPath.lastIndexOf('/') + 1);   //if(sPage == 'index.php')

var applicationId     = "414751805313094";
var applicationSecret = "f65a47c37be9e536f7003649dbc3a851";

var session_key;
var access_token;
var uid;
var language = 'en';
var userData;

var appInstallUrl = "https://www.facebook.com/dialog/oauth/?client_id="+applicationId+"&redirect_uri="+canvasPage+"&scope=email";*/
uid = '<?php echo Session::get('user_id'); ?>';
user_name = '<?php echo Session::get('name'); ?>';
</script>
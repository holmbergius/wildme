<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WILDME</title>
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
	
	//FB.Canvas.setSize({ height: 900 });
	
	var url = parseURL(window.location);
	if ("a" in url.params) {
	 Set_Cookie('affiliate', url.params['a'], '', '/', '', '' );
	}
	
	
	
	function logged_in(response) {
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
			  
			  
			  //facebook_id	=	userId;
			  //console.log(resp);
			  //console.log(userId + userName + birthday + email +  gender + access_token);
			 // console.log(resp); return false;
			 // var pact_id='';
			$.get(canvas_url+"api/login",  {id: userId, name: userName, email: userEmail,  accessToken:  access_token, age: birthday, gender: UserGender}, function(data){
				 
				 //console.log(data);
				 if(data.status === 'success' || data.status === 'success2')
				 {
				 	uid	=	userId;
					user_name	=	userName;
					
					FB.Canvas.setAutoGrow();
					getUserFriends(uid,0,4,0);
					getAnimalCategory(0);
					//getPopularActive('follow_count',3);
					getUserFollowes('id',0,5,0);
					getUserLog(0);
					getUser();					
					$('#fb_user_name').text(user_name);
					$('#fb-user-pic').attr('src',"http://graph.facebook.com/"+uid+"/picture?width=33&height=33");
					$('#user-page').attr('href',"<?php echo Config::get('application.web_url');?>user-page?id="+uid+"");
				 }
					//window.parent.location	=	'http://apps.facebook.com/wildmeapp/';
					
			  }, "json" );
				
			  });
		//login(uid, access_token);
	}
	
	FB.getLoginStatus(function(response)
	{
	 FB.Event.subscribe('auth.authResponseChange', logged_in);
	 if (response.authResponse)
	 {
	  logged_in(response);
		} else {
			window.top.location = appInstallUrl;
		}
	});
	
	
	
</script>
<div class="main-wrapper">
 <?php echo render('inc.header'); ?>
  <div class="app-wraper white"> 
 
    <!-- ---------------- MAP POUP ----------------->
    <?php echo render('inc.popup'); ?>
    <div class="container">

<?php 
 
$userId	=	($_REQUEST['id'])?$_REQUEST['id']:"";
$coverPhotoDetail	=	 file_get_contents("https://graph.facebook.com/".$userId."?fields=cover");


$coverPhotoDetail	=	json_decode($coverPhotoDetail);
//print_r($coverPhotoDetail); die;
$coverPhoto	=	"";
$coverPhoto	=	 (isset($coverPhotoDetail->cover))?$coverPhotoDetail->cover->source:"";

//background-image:url('<?php echo $coverPhoto ');

//print_r($coverPhoto);
 
 ?>
      <div class="main-search">
        <input name="" onkeypress="SearchEncounters(event);" type="text" id="keyword" placeholder="Search for wildlife" />
        <!--<select name="">
          <option>Filter by type</option>
        </select>-->
        <div class="span3">
          <select name="category" class="select-block span3" id="category">
            <?php /*?><option value="0">Whale Shark</option>
            <option value="1">Snow Leopard</option>
            <option value="2">Peregrine Falcons</option>
            <option value="X-Men" selected="selected">Stumpy</option>
            <option value="Crocodile">Crocodile</option><?php */?>
          </select>
        </div>
        <a onclick="SearchEncounters(event);" href="javascript:SearchEncounters(event);" class="btns noSelect serach-btn"><i class="icon-search"></i></a> </div>
      <div class="profile-banner" style=" background:url(<?php echo (!empty($coverPhoto))?$coverPhoto:"images/dummy.jpg";?>) center center no-repeat; width:770px; height:265px; background-size:100%">
      <input type="hidden" id="uid" value="<?php echo $_REQUEST['id']; ?>" />
      <div class="batch-profile" id="badge" style="display:none;"><img src="images/batch.png" width="116" height="116" /></div>
        <div class="wild-profile-banner" >
        
         
        <div class="wild-profile">
          <div class="pic">
            <div class="align-div-banner"><img src="http://graph.facebook.com/<?php echo (isset($_REQUEST['id']))?$_REQUEST['id']:"";  ?>/picture?width=61&height=61"/></div>
          </div>
          <div class="profile-banner-list group" style="margin-left:75px;">
            <h4><a href="javascript:;" id="profile_name"></a></h4>
            <ul id='social_detail'>
              
            </ul>
          </div>
          </div>
        </div>
      </div>
      <div class="post-listing group" >
        <div class="post-listing-left">
          <h1><span id="user_action"></span> </h1><div id="spinner-user" style="margin-left: 260px;
    margin-top: 9px; position: absolute; display:none;"><img class="icon-spin" src="images/sp-new.png"  /></div>
          <div class="listing-browse group ">
            <ul id='listActions'>
              <?php /*?><li class="first">
                <div class="pic">
                  <div class="align-div  blue"> <img src="images/icon/07.png" /> </div>
                </div>
                <div class="user-page-details">
                  <p>Hanako has followed <a href="#">Benny (CX497)</a>, the female Penguin.</p>
                  <span>2 hours ago</span> </div>
              </li><?php */?>
              
            </ul>
          </div>
          <div class="row" id="loadmore_activity" style="display:none"> <span class="loadmore">LOAD MORE <img style="display:none;" id="loadmore-spinner"  class="icon-spin" src="images/s-spinne.png" /></span> </div>
        </div>
        <div class="post-listing-right">
          <h1>Animals<a href="#" style="display:none;" id="view_all" onclick="getUserFollowes('id',0,10,1); FB.Canvas.scrollTo(0,0);"> VIEW ALL <i class="icon-double-angle-right"></i> </a></h1>
          <div class="listing-main" style="margin-top:20px;">
            <div class="listing group">
              <ul id="tab1">

              </ul>
            </div>
            <h1 class="mr">My Friends <span style="display:none;" id="view-invite"><a href="#" onclick="InviteFriends();">INVITE MORE FRIENDS <i class="icon-double-angle-right"></i> </a></span></h1>
            <div class="listing group">
              <ul  id="buddy-list">
                
                
                
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php echo render('inc.footer'); ?>
  </div>
</div>
</body>
</html>

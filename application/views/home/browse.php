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
					getAnimalCategorySlider();
					getBrowseAnimal(0);
					
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

    <div class="container">
      <div class="main-search">
        <input name="keyword" onkeypress="SearchBrowseAnimal(event);" type="text" id="keyword" placeholder="Search for wildlife" value="<?php if (isset($_REQUEST['keyword'])) echo $_REQUEST['keyword']; else echo '';?>" />
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
        <a href="javascript:getBrowseAnimal(0);" class="btns noSelect serach-btn"><i class="icon-search"></i></a> </div>
      <div class="crousal-slider group" id="scrollable">
        <div class="items">
          
          
          
        </div>
        <div class="crousal-slider-left noSelect prev"><i class="icon-angle-left"></i></div>
        <div class="crousal-slider-right noSelect next"><i class="icon-angle-right"></i></div>
      </div>
      <div class="post-listing group">
        <div class="post-listing-left">
          <div class="sort-by">
          
          
            <div class="sort-select">
              <div class="span3">
                <select name="herolist" id="sortby"class="select-block span3" >
                  <option elected="selected" value="id">Recent</option>
                  <option value="follow_count">Popular</option>
                  <option value="share_count">Shared</option>
                </select>
              </div>
              <!--  <select name=""><option>Sort by </option></select>--> 
              <a href="javascript:getBrowseAnimal(0);" class="btns ser"><i class="icon-arrow-right"></i></a> </div>
          </div>
          <h1>Browse <span id="cat_type"></span></h1>
          <div class="listing-browse group "><div id="spinner-list" style="margin-left: 333px;
    margin-top: -19px; position: absolute; display:none;"><img class="icon-spin" src="images/sp-new.png"  /></div>
            <ul id="tab3">
            
            </ul>
          </div>
          <div class="row" id="loadmore_browse" style="display:none"> <span class="loadmore">LOAD MORE <img id="loadmore-spinner_b" class="icon-spin" src="images/s-spinne.png" style="display:none" /></span> </div>
        </div>
        <div class="post-listing-right">
          <div class="listing-main" style="margin-top:0px;">
            <h1 class="mr" style="margin-top:0px;">My Friends <span style="display:none;" id="view-invite"> <a  href="javascript:InviteFriends();">INVITE MORE FRIENDS <i class="icon-double-angle-right"></i> </a></span></h1>
            <div class="listing group">
              <ul id="buddy-list">

                
              </ul>
            </div>
            <div class="add-banner"><img src="images/dummy-pic/add.jpg" /></div>
          </div>
        </div>
      </div>
    </div>
    <?php echo render('inc.footer'); ?>
  </div>
</div>
</body>
</html>

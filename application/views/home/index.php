<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WILDME</title>

<?php echo render('inc.js'); ?>
</head>
<body>
<?php include_once("/var/www/wildme/application/views/home/analyticstracking.php") ?>
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
					getAnimalCategory(1);
					getPopularActive('follow_count',1);
					getPopularActive('encounter_count',2);
					getUserFriends(uid,0,4,0);
					
					$('#fb_user_name').text(user_name);
					$('#fb-user-pic').attr('src',"http://graph.facebook.com/"+uid+"/picture?width=33&height=33");
					$('#user-page').attr('href',"<?php echo Config::get('application.web_url');?>user-page?id="+uid+"");
				 }

         else if(data.status === 'error2')
         {
             $('#ban_popup').fadeIn(); 
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
	

  $(document).ready(function(){ 

    $('#confirm1').click(function(){

      $('#ban_popup').fadeOut();
      window.parent.location  = 'http://apps.facebook.com/wildbook/';
    });


  });
	
	
</script>

<div class="popup-inner activites-poup photo-popup" id="show_popup_login" style="display:none; ">
  <div class="popup-inner-content" style="position:relative;z-index:4">
<p>Bring the amazing world of wildlife into your social network!
Follow individual animals under study by researchers and learn more about their amazing lives with Wild Me!"</p>
<div class="wildme-btn">
<a class="btns" href="javascript:FBLogin();">Access Login</a>
</div>  </div>
<div class="wildme-trans"></div>
</div>

<div class="main-wrapper"> <?php echo render('inc.header'); ?>
  <div class="app-wraper white">
    <!-- ---------------- MAP POUP ----------------->
    <?php echo render('inc.popup'); ?>
    <div class="container">
      <?php /*?><input name="input_fb_id" id="input_fb_id" value="<?php echo Session::get('user_id'); ?>" type="hidden"  /><?php */?>
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
      <div class="slider">
        <div class="slider-text">
          <h1 style="display:none">Follow your favorite whale sharks, <br />
            polar bears, giant manta rays and more.</h1>
          <p style="display:none">We’ve tagged thousands of wildlife animals around the world. Befriend them to find out what they’re doing, where and with whom. As they cover the expanse of the Sahara, or wade into the depths of the Pacific, you can track them on Facebook!</p>
          <a href="about-us" class="btns learn" style="display:none">Learn More</a> </div>
        <div class="slider-ani-pic" style="display:none;"><img src="images/banner-animal1.png"/></div>
      </div>
      <div class="post-listing group">
        <div class="post-listing-left">
          <h1>Recent Activites</h1>
          <div id="spinner-act" style="margin-left: 169px;
    margin-top: -20px; position: absolute; display:none;"><img class="icon-spin" src="images/sp-new.png"  /></div>
          <ul id="listActivites">
            <?php /*?><li>
            
              <div class="pic">
                <div class="align-div blue"> <img src="images/icon/01.png" /> </div>
              </div>
              
              
              
              
              <div class="details group">
                <h4><a href="profile.php">Rex <span>(TX-004)</span></a> <span class="small">- 10 hours ago</span></h4>
                <p>Whale Shark, male has been seen near <span><a href="#">West Bank of Flower
                  Gardens National Marine Sanctuary.</a></span> Ryan Eckert has captured the
                  photo and  Eric Hoffmayer has reported the event.</p>
                <div class="pics-videos group">
                  <ul>
                    <li class="show-pic-poup"><img src="images/dummy-pic/01.png" /></li>
                    <li class="show-pic-poup"><img src="images/dummy-pic/01.png" /></li>
                    <li class="show-pic-poup"><img src="images/dummy-pic/01.png" /></li>
                  </ul>
                </div>
                <div class="social-counter group">
                  <ul>
                    <li>
                      <div class="comment-counter">
                        <div class="icon-bg"><i class="icon-thumbs-up"></i></div>
                        <div class="count-bg">10</div>
                      </div>
                    </li>
                    <li>
                      <div class="comment-counter">
                        <div class="icon-bg"><i class="icon-comments"></i></div>
                        <div class="count-bg">5</div>
                      </div>
                    </li>
                    <li>
                      <div class="comment-counter">
                        <div class="icon-bg"><i class="icon-share-alt"></i></div>
                        <div class="count-bg">2</div>
                      </div>
                    </li>
                    <li>
                      <div class="comment-counter ">
                        <div class="icon-bg show-map-poup"><i class="icon-map-marker"></i></div>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
            </li><?php */?>
          </ul>
          <div class="row" style=" display:none"  id="loadmore"> <span class="loadmore">LOAD MORE <img style="display:none;" class="icon-spin" id="loadmore-spinner" src="images/s-spinne.png" /></span> </div>
        </div>
        <div class="post-listing-right">
          <h1>Wild Life</h1>
          <div id="spinner-wild" style="margin-left: 108px; margin-top: -21px; position: absolute; display:none;"><img class="icon-spin" src="images/sp-new.png"  /></div>
          <div class="listing-main">
            <div class="tab">
              <ul>
                <li class="selected show-wild-tab1" id="tf-1"><a href="javascript:;">MOST POPULAR</a></li>
                <li class="show-wild-tab2" id="tf-2"><a href="javascript:;">MOST ACTIVE</a></li>
              </ul>
            </div>
            <div class="listing group">
              <!---------------- step 1 ---------------->
              <ul id="tab1" style="display:block;">
                <?php /*?> <li class="first">
                  <div class="pic">
                    <div class="align-div  blue"> <img src="images/icon/01.png" /> </div>
                  </div>
                  <div class="listing-details">
                    <h4><a href="#">Alex <span>(MZ614)</span></a></h4>
                    <p>Whale Shark, Male</p>
                    <div class="list-comment-counter">
                      <div class="list-comment-counters-inner"> <span>-</span> FOLLOW</div>
                      <div class="list-comment-count-bg">125</div>
                    </div>
                  </div>
                  <i class="icon-angle-right"></i> 
                </li><?php */?>
              </ul>
              <!---------------- step 2---------------->
              <ul id="tab2" style="display:none;">
                <?php /*?><li class="first">
                  <div class="pic">
                    <div class="align-div orange"> <img src="images/icon/04.png" /> </div>
                  </div>
                  <div class="listing-details">
                    <h4><a href="#">EX-314 </a></h4>
                    <p>Whale Shark, Male</p>
                    <div class="list-comment-counter">
                      <div class="list-comment-counters-inner"> <span>-</span> FOLLOW</div>
                      <div class="list-comment-count-bg">125</div>
                    </div>
                  </div>
                  <i class="icon-angle-right"></i> 
                </li><?php */?>
              </ul>
            </div>
            <h1 class="mr">My Friends <span style="display:none;" id="view-invite"><a href="#" onclick="InviteFriends();">INVITE MORE FRIENDS <i class="icon-double-angle-right"></i> </a></span></h1>
            <div class="listing group">
              <ul id="buddy-list">
                <?php /*?><li class="first">
                  <div class="pic-us"><img src="images/dummy-pic/04.jpg"/></div>
                  <div class="listing-details">
                    <h4><a href="user-page.php">Ashley Krueger</a></h4>
                    <p>Following 10 animals</p>
                    <div class="list-comment-counter">
                      <div class="list-comment-counters-inner"> <span> n</span> VIEW PROFILE</div>
                    </div>
                  </div>
                  <i class="icon-angle-right"></i> </li>
                <li>
                  <div class="pic-us" ><img src="images/dummy-pic/05.jpg"/></div>
                  <div class="listing-details">
                    <h4><a href="user-page.php">Andrew Hastert</a></h4>
                    <p>Following 6 animals</p>
                    <div class="list-comment-counter">
                      <div class="list-comment-counters-inner"> <span> n</span> VIEW PROFILE</div>
                    </div>
                  </div>
                  <i class="icon-angle-right"></i> </li>
                <li class="last">
                  <div class="pic-us"><img src="images/dummy-pic/06.jpg"/></div>
                  <div class="listing-details">
                    <h4><a href="user-page.php">Cynthia Coffield</a></h4>
                    <p>Following 4 animals</p>
                    <div class="list-comment-counter">
                      <div class="list-comment-counters-inner"> <span> n</span> VIEW PROFILE</div>
                    </div>
                  </div>
                  <i class="icon-angle-right"></i> </li><?php */?>
              </ul>
            </div>
            <div class="add-banner"><img src="images/dummy-pic/add.jpg" /></div>
          </div>
        </div>
      </div>
    </div>
    <?php echo render('inc.footer'); ?> </div>
</div>
</body>
</html>

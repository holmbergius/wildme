<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WILDME</title>
<style>
.widget ul li {
	margin-right: 3px !important;
}
</style>
<?php
$animal        = json_decode($animal, true); 
$animal_record = $animal['records'][0];
//echo "<pre>";
//print_r($animal_record);die();
//die();
$id	=	$animal_record['id'];
$selectdClass	=	"";
$followMethod	=	"";

//echo $animal_record['follow_check'];

if($animal_record['follow_check'] == 1)
{
	$followMethod	=	"animalUnFollowById('".$id."')";
	$selectdClass	=	"selected";
}
else
{
	$followMethod	=	"animalFollowById('".$id."')";
	$selectdClass	=	"";
}

$app_url	=	Config::get('application.app_url');
$web_url	=	Config::get('application.web_url');


$keyword   = isset($_REQUEST['keyword'])?$_REQUEST['keyword']:'';

$shareImage	=	($animal_record['profile_pic'])?$animal_record['profile_pic']:$web_url."images/dummy-friends.jpg";
$user_id = Session::get('user_id');
?>
<?php echo render('inc.js'); ?>


</head>
<body>
<?php include_once("/var/www/wildme/application/views/home/analyticstracking.php") ?>
  <input type="hidden" class="animal_id" id="animal_id" value="<?php echo $animal_record['id'];?>" />
  <input type="hidden" class="animal_label" id="animal_label" value="<?php echo $animal_record['label'];?>" />
  <input type="hidden" class="category_title" id="category_title" value="<?php echo $animal_record['category_title'];?>" />
  <input type="hidden" class="show_qoute_popup" id="show_qoute_popup" value="<?php echo $animal_record['show_qoute_popup'];?>" />
   <input type="hidden" class="adopter_id" id="adopter_id" value="<?php echo $animal_record['adopter_id'];?>" />
  <input type="hidden" class="nick_name" id="nick_name" value="<?php echo $animal_record['nick_name'];?>" />
  
  <input type="hidden" class="nick_name" id="nick_name" value="<?php echo $animal_record['nick_name'];?>" />
<link rel="stylesheet" href="<?php echo Config::get('application.web_url');?>adopt-form/css/adoption.css?timer=4" type="text/css">


<div id="wildme-aboption-div" data-id="<?php echo $animal_record['label']; ?>"></div>
<div style="display:none" id="quote-div" class="popup" data-id="<?php echo $animal_record['label']; ?>"></div>

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
					getAnimalCategory(0);
					response	=	GetUrlParms('id');
					//getAnimalDetail(response);
					getRecentEncounters(0);
					getAnimalPhotos(0);
					
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
	
	$(document).ready(function(e) {
        
		$("#adopt").click(function(){
			global_uid = uid;
			WildMegetAnimalDetails();
		});
		
    });
	
</script>
<div class="main-wrapper"> <?php echo render('inc.header'); ?>
<div class="app-wraper white" style="margin-top:65px;"> <?php echo render('inc.popup'); 

	?>
    <div class="container">
      <div class="main-search">
        <input onkeypress="SearchEncounters(event);" value="<?php echo $keyword;?>" id="keyword" name="" type="text" placeholder="Search for wildlife" />
        
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
      <div class="profile-banner" style="background:url('<?php echo ($animal_record['profile_pic'])?$animal_record['profile_pic']:"../images/dummy.jpg"; ?>') center center ; background-size:770px auto;">
        <div class="profile-banner-shadow"></div>
        
        <!--newhtml--> 
        <!-- <div class="batch-profile"><img src="..//images/batch.png" width="116" height="116" /></div>-->
        
        <div class="wild-profile">
          <div class="pic">
            <div class="align-div  <?php echo $animal_record['category_color'] ?>"> <img id="animal_category_image" src="<?php echo Config::get('application.web_url').$animal_record['category_icon']; ?>"> </div>
          </div>
          <div class="profile-banner-list">
            <h4><a href="javascript:;"><?php echo $animal_record['nick_name']; ?><span style="margin-left:5px;">(<?php echo $animal_record['label']; ?>)</span></a></h4>
            <ul>
              <?php if($animal_record['sex'] != ''){?>
              <li ><span>Gender:</span> (
                <?php if ($animal_record['sex']=='') echo 'unknown'; else echo $animal_record['sex']; ?>
                )</li>
              <?php }?>
              <?php /*?><?php if($animal_record['size'] != ''){?><li><span>Length:</span> (<?php echo $animal_record['size']; ?> Meters)</li><?php }?><?php */?>
              <li><span>Activites:</span> (<?php echo $animal_record['encounter_count']; ?>)</li>
              <li> <span>Friends:</span> (<?php echo $animal_record['friend_count']; ?>)</li>
            </ul>
            <div class="follow-share">
              <div class="list-comment-follow " id="foll_<?php echo $animal_record['id'];?>" onclick="<?php echo $followMethod; ?>;">
                <div class="list-comment-counts-follow <?php echo $selectdClass; ?>" id="foll_class_<?php echo $animal_record['id'];?>"> <span>-</span> FOLLOW</div>
                <div class="list-comment-count-bg-follow <?php echo $selectdClass; ?>" id="follow_<?php echo $animal_record['id']; ?>"><?php echo $animal_record['follow_count']; ?></div>
              </div>
              <div class="list-comment-follow" onclick="SharePhoto('<?php echo $shareImage  ?>','<?php echo ($animal_record['nick_name'])?$animal_record['nick_name']:$animal_record['id']; ?>','<?php echo $app_url ?>','<?php echo $animal_record['id'];?>','animal','','','','','<?php echo $animal_record['category_title']; ?>','','<?php echo $animal_record['id']; ?>');">
                <div class="list-comment-counts-follow"> <i class="icon-share-alt"></i> SHARE</div>
                <div class="list-comment-count-bg-follow" id="share_<?php echo $animal_record['id']; ?>"><?php echo $animal_record['share_count']; ?></div>
              </div>
              
              <!--new html-->
              <?php 
                if($animal_record['category_active_adoption'] == 'Yes' && $animal_record['active_adoption'] =='Yes')
              {
				  
				   $count = DB::first("select COUNT(id) as total from `adoptor` where `uid` = '".$user_id."' and `animal_id` = '".$id."' and `status` = 'Active' Limit 1;");
			   $text = 'Adopt';
			   if($count->total>0)
		   		{
					$text = 'ReAdopt';
		   		}
				?>
                
              <div class="adopt-button" id="adopt"> <a href="javascript:;"> <i></i> <?php echo $text; ?></a> </div>
              <?php } ?>
              
              <!--new html- end--> 
              
            </div>
          </div>
        </div>
      </div>
      <div class="navigation">
        <ul>
          <li class="selected show-team-tab1" id="tfp-1"><a href="javascript:;"> <span><i class="icon-caret-up"></i></span>Activites</a></li>
          <li class="show-team-tab2" id="tfp-2"><a href="javascript:;"><span><i class="icon-caret-up"></i></span>About</a></li>
          <li class="show-team-tab3" id="tfp-3"><a href="javascript:;"><span><i class="icon-caret-up"></i></span>Photos</a></li>
          <li class="show-team-tab6" id="tfp-6"><a href="javascript:;"><span><i class="icon-caret-up"></i></span>Stories</a></li>
          <?php 
              if($animal_record['category_active_gps'] == 'Yes' && $animal_record['active_gps'] =='Yes')
              {?>
          <li class="show-team-tab4" id="tfp-4" ><a href="javascript:;"><span><i class="icon-caret-up"></i> </span>Places</a></li>
          <?php } ?>
          
          <li class="show-team-tab7" id="tfp-7" ><a href="javascript:;"><span><i class="icon-caret-up"></i></span>Researchers</a></li>
          <li class="show-team-tab5" id="tfp-5"><a class="last" href="javascript:;"><span><i class="icon-caret-up"></i></span>Friends</a></li>
        </ul>
      </div>
      <!-- ----------- step 1 -------------->
      <div class="post-listing group" id="team-tab1" style="display:block;">
        <div class="post-listing-left">
          <h1>All Activites (<span id="all-act">0</span>)</h1>
          <div id="spinner-act" style="margin-left: 169px;
    margin-top: -20px; position: absolute; display:none;"><img class="icon-spin" src="../images/sp-new.png"  /></div>
          <ul id="listActivites">
            <?php /*?><li>
              <div class="pic">
                <div class="align-div blue"> <img src="../images/icon/07.png" /> </div>
              </div>
              <div class="details group">
                <h4><a href="#">Stumpy <span>(TX-004)</span></a> <span class="small">- 10 hours ago</span></h4>
                <p>Whale Shark, male has been seen near <span><a href="#">West Bank of Flower
                  Gardens National Marine Sanctuary.</a></span> Ryan Eckert has captured the
                  photo and  Eric Hoffmayer has reported the event.</p>
                <div class="pics-videos group">
                  <ul>
                    <li class="show-pic-poup"><img src="../images/dummy-pic/01.png" /></li>
                    <li class="show-pic-poup"><img src="../images/dummy-pic/01.png" /></li>
                    <li class="show-pic-poup"><img src="../images/dummy-pic/01.png" /></li>
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
          <div class="row" style="display:none" id="loadmore"> <span class="loadmore" >LOAD MORE <img style="display:none;" class="icon-spin" id="loadmore-spinner" src="../images/s-spinne.png" /></span> </div>
        </div>
        <div class="post-listing-right"> 
          
          <!--========new Html=====--> 
          <!--========Adopters Section=====-->
          <h1>Adopters</h1>
        
          <input type="hidden" id="animal_ppic" value="<?php echo ($animal_record['profile_pic'])?$animal_record['profile_pic']:"../images/dummy.jpg"; ?>" />
   <input type="hidden" id="category_title" value="<?php echo $animal_record['category_title']; ?>" />


          
          <div class="listing adopters-blts group">
            <div class="scrollbar2" id="ex3">
              <ul id="buddy-list adopter-bults" class="adoptors_rows">
                <!-- <li class="first"> <a href="">
>>>>>>> 2161009c9b6ad6c1118ee292613f81bdff72b908
                <div class="pic-us"> <img src="../images/dummy-pic/05.jpg"> </div>
                <div class="listing-details">
                  <h4>Alex</h4>
                  <p>Following <span>0</span> animal(s)</p>
                  <div class="list-comment-counter">
                    <div class="list-comment-counters-inner"> <span> n</span> VIEW PROFILE</div>
                  </div>
                </div>
                <br clear="all"/>
                <p class="quote-text">"Animals are my friends...and I don't eat my friends."</p>
                </a> </li>
              <li> <a href="">
                <div class="pic-us"> <img src="../images/dummy-pic/06.jpg"> </div>
                <div class="listing-details">
                  <h4>Khristina</h4>
                  <p>Following <span>0</span> animal(s)</p>
                  <div class="list-comment-counter">
                    <div class="list-comment-counters-inner"> <span> n</span> VIEW PROFILE</div>
                  </div>
                </div>
                </a></li>
              <li> <a href="">
                <div class="pic-us"><img src="../images/dummy-pic/04.jpg"> </div>
                <div class="listing-details">
                  <h4>Krissy</h4>
                  <p>Following <span>0</span> animal(s)</p>
                  <div class="list-comment-counter">
                    <div class="list-comment-counters-inner"> <span> n</span> VIEW PROFILE</div>
                  </div>
                </div>
<<<<<<< HEAD
                </a></li>
                <li> <a href="">
                <div class="pic-us"><img src="../images/dummy-pic/02.jpg"> </div>
                <div class="listing-details">
                  <h4>Erik</h4>
                  <p>Following <span>0</span> animal(s)</p>
                  <div class="list-comment-counter">
                    <div class="list-comment-counters-inner"> <span> n</span> VIEW PROFILE</div>
                  </div>
                </div>
                </a></li>
=======
                </a></li>-->
                
              </ul>
            </div>
          </div>
          <h1>Friends of <?php echo ($animal_record['nick_name'])?$animal_record['nick_name']:$animal_record['label'];?></h1>
          <div id="spinner-wild" style="margin-left: 225px; margin-top: -21px; position: absolute; display:none;"><img class="icon-spin" src="../images/sp-new.png"  /></div>
          <div class="listing-main" style="margin-top:20px;">
            <div class="listing group">
              <ul id="animal-friend">
              </ul>
            </div>
            <h1 class="mr" >Followers(<span id="follower-count" >0</span>)<a href="javascript:;" id="view_all" style="display:none"; onclick="getAnimalFollower('<?php echo $animal_record['id']; ?>',0,10,1); FB.Canvas.scrollTo(0,0);"> VIEW ALL <i class="icon-double-angle-right"></i> </a></h1>
            <div id="spinner-follow" style="margin-left: 169px;
    margin-top: -36px; position: absolute; display:none;"><img class="icon-spin" src="../images/sp-new.png"  /></div>
            <div class="widget group">
              <ul id="buddy-pics">
              </ul>
            </div>
            <div class="add-banner"><img src="../images/dummy-pic/add.jpg" /></div>
          </div>
        </div>
      </div>
      
      <!-- ----------- step 2 -------------->
      <div class="post-listing group" id="team-tab2" style="display:none;">
        <div class="aboutus">
          <h1>About <?php echo ($animal_record['nick_name'])?$animal_record['nick_name']:$animal_record['id']; ?></h1>
		  <?php
		  $animal_record['api_url'] = str_replace("http://wildmefacebook:4Strings123@www.whaleshark.org/","http://www.whaleshark.org/",$animal_record['api_url']);
		  ?>
          <ul>
            <li><span>Unique id:</span><?php echo $animal_record['label']; ?></li>
            <li><span>Nickname:</span><?php echo ($animal_record['nick_name'])?$animal_record['nick_name']:"Not Available"; ?></li>
            <li><span>NICKNAMER:</span><?php echo ($animal_record['nick_namer'])?$animal_record['nick_namer']:"Not Available"; ?></li>
            <li><span>type:</span><?php echo ($animal_record['category_title'])?$animal_record['category_title']:"Not Available"; ?></li>
            <li><span>sex:</span><?php echo ($animal_record['sex'])?$animal_record['sex']:"Not Available"; ?></li>
            <li><span>MORE INFORMATION:</span><a href="<?php echo $animal_record['api_url'].'individuals.jsp?number='.$animal_record['animal_id']; ?>" target="_blank"><?php echo $animal_record['api_url'].'individuals.jsp?number='.$animal_record['animal_id']; ?></a></li>
            <li><span>SPECIES:</span>
              <?php if ($animal_record['encounter_data']['genus']=='' && $animal_record['encounter_data']['specific_epithet']=='') echo 'Not Available'; else echo $animal_record['encounter_data']['genus'].' '.$animal_record['encounter_data']['genus'];?>
            </li>
          </ul>
        </div>
      </div>
      
      <!-- ----------- step 3 -------------->
      <div class="post-listing group" id="team-tab3" style="display:none;">
        <div class="photo">
          <h1 id="photos_heading">Photos</h1>
          <ul id="activity-photos">
            <?php /*?><li>
              <div class="photo-resize show-activites-poup"> <img src="../images/dummy-pic/14.jpg" /> </div>
            </li>
            <li>
              <div class="photo-resize show-activites-poup"> <img src="../images/dummy-pic/15.jpg" /> </div>
            </li>
            <li>
              <div class="photo-resize show-activites-poup"> <img src="../images/dummy-pic/16.jpg" /> </div>
            </li>
            <li>
              <div class="photo-resize show-activites-poup"> <img src="../images/dummy-pic/17.jpg" /> </div>
            </li>
            <li class="last">
              <div class="photo-resize show-activites-poup"> <img src="../images/dummy-pic/18.jpg" /> </div>
            </li><?php */?>
          </ul>
        </div>
        <div class="clear-fix"></div>
        <div class="row"> <span class="loadmore" id="loadmore_photo">LOAD MORE <img style="display:none;" class="icon-spin" id="loadmore-spinner-photos" src="../images/s-spinne.png" /></span> </div>
      </div>
      
      <!-- ----------- step 4 -------------->
      <div class="post-listing group" id="team-tab4" style="display:none;">
        <div class="places" style="position:relative;">
          <h1 id="map-palces-heading">Places <img style="display:none;" class="icon-spin" id="spinner-places" src="../images/sp-new.png" /> </h1>
          <div class="map-palces" id="map-palces">
         
            <iframe id="palces_iframe" src="<?php echo Config::get('application.web_url');?>places.php?id=<?php echo $animal_record['id']; ?>" width="771" height="510" frameborder="0" scrolling="no"></iframe>
          </div>
          
          <!--<div class="map-palces" style="background: url(../images/dummy-pic/place-map.jpg) no-repeat; width:771px; height:510px;">    </div>     --> 
          
        </div>
        
        <!-- ----------- step 5 --------------> 
        
      </div>
      <div class="post-listing group" id="team-tab5" style="display:none;">
        <div class="friends">
          <h1>Friends <img style="display:none;" class="icon-spin" id="spinner-friends" src="../images/sp-new.png" /></h1>
          <ul id="animal-friend-tab">
          </ul>
        </div>
        <div class="clear-fix"></div>
        <div class="row"> <span class="loadmore" id="loadmore_friend">LOAD MORE <img style="display:none;" class="icon-spin" id="loadmore-spinner-friend" src="../images/s-spinne.png" /></span> </div>
      </div>
      
      <!-- ----------- step 6 -------------->
      
      <div class="post-listing group" id="team-tab6" style="display:none;">
         <h1 style="font:23px 'latolight',Arial; color:#2C3E50;" id="story_heading">Stories <img style="display:none;" class="icon-spin" id="spinner-stories" src="../images/sp-new.png" /></h1>
         <div id="team-tab6html">
         
         </div>
        <!--<div class="stories-div last">
          <h2>Lorem ipsum dolor sit amet,</h2>
          <div class="stories-left-area">
            <p> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras sollicitudin, magna eu pharetra porta, massa tellus eleifend tortor, eu molestie lorem dui et justo. Cras sollicitudin libero erat, at iaculis felis fermentum non. Etiam a est sed justo tincidunt facilisis at vel dolor. Etiam dapibus feugiat felis. Morbi pharetra lorem et purus condimentum cursus. Ut suscipit turpis bibendum lacus pharetra facilisis. Donec sit amet egestas dui, in mattis massa. In at orci lacus. Donec non aliquet ligula. Vivamus elementum lacinia ligula ac malesuada. Vivamus dictum, nulla in rhoncus semper, lacus justo porta sem, vel malesuada nunc dolor ac justo. Fusce dignissim adipiscing eros, sit amet molestie nunc accumsan id. </p>
            <p> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras sollicitudin, magna eu pharetra porta, massa tellus eleifend tortor, eu molestie lorem dui et justo. Cras sollicitudin libero erat, at iaculis felis fermentum non. Etiam a est sed justo tincidunt facilisis at vel dolor. Etiam dapibus feugiat felis. Morbi pharetra lorem et purus condimentum cursus. Ut suscipit turpis bibendum lacus pharetra facilisis. Donec sit amet egestas dui, in mattis massa. In at orci lacus. Donec non aliquet ligula. Vivamus elementum lacinia ligula ac malesuada. Vivamus dictum, nulla in rhoncus semper, lacus justo porta sem, vel malesuada nunc dolor ac justo. Fusce dignissim adipiscing eros, sit amet molestie nunc accumsan id. </p>
            <div class="story-share-btn"><a href="javascript:;"><img src="../images/share-btn.png" width="50" height="13" alt="" /></a></div>
          </div>
          <div class="stories-right-area">
            <div class="writer-pic"><img src="../images/dummy-pic/writer-dummy-pic-2.jpg" width="115" height="115" alt="" /></div>
            <br clear="all" />
            <h2>Krissy</h2>
          </div>
          <br clear="all" />
        </div>-->
        <div class="row"> <span class="loadmore" id="load_more_stories" style="display: none;">LOAD MORE <img style="display: none;" class="icon-spin loader-spinner" id="loadmore-spinner-story" src="../images/s-spinne.png"></span> </div>
      </div>
      
      <!---------------step-7------------------>
      
      <div class="post-listing group" id="team-tab7" style="display:none;">
        <div class="research-wrapper">
          <ul id="research-data">
          </ul>
        </div>
        <br clear="all" />
        <div class="row"> <span class="loadmore" style="display: none;">LOAD MORE <img style="display: none;" class="icon-spin loader-spinner" id="loadmore-spinner2" src="../images/s-spinne.png"></span> </div>
      </div>
    </div>
    <script type="text/javascript">
   	$(document).ready(function() {
		
		//console.log(sPage);
		FB.Canvas.setSize({ height: 3350 });

        getAnimalCategory();
		getAnimalAdoptors(0);
		getRecentEncounters(0);
		getAnimalPhotos(0);
		getAnimalFriends(0,'<?php echo $animal_record['id']; ?>');
		getAnimalFollower('<?php echo $animal_record['id']; ?>',0,12,0);
		getAnimalFriendsDetail(0,'<?php echo $animal_record['id']; ?>');
		getAnimalResearcherDetail(0,'<?php echo $animal_record['id']; ?>');
		getAnimalStories(0,'<?php echo $animal_record['id']; ?>');
		//getAnimalPlaces('<?php echo  $animal_record['id']; ?>');
		
		var show_qoute_popup = $("#show_qoute_popup").val();
		//if(show_qoute_popup > 0){
		if(show_qoute_popup > 0){
		//show popup for qoute
		var animal_label= $("#animal_label").val();
		var animal_id = $("#animal_id").val();
		var category_title = $("#category_title").val();
		var adopter_id = $("#adopter_id").val();
		var animal_category_image = $("#animal_category_image").attr('src');
		var animal_nick_name =  $("#nick_name").val();
		if(animal_nick_name == null || typeof animal_nick_name == 'undefined' )animal_nick_name = '';
		else animal_nick_name = animal_nick_name+' ';
var popupHtml ='<div class="adopt-form-wrapper">\
<span class="cross-icon-adopt close_qoute_div"><img width="22" height="22" alt="" src="../../adopt-form/images/cross-icon.png"></span>\
<div class="adopt-form-title"><div class="adopt-form-logo">\
<img width="158" height="53" alt="" src="../../images/logo.png"></div></div><div class="animal-code-strip">\
<div class="animal-thumb"><img width="39" height="39" alt="" src="'+animal_category_image+'"></div>\
<h2 id="">'+animal_nick_name+'('+animal_label+')<br><span>Type: '+category_title+'</span>\
</h2><div class="animal-price-box"></div></div><hr class="hori-line">\
<div style="display:block;" id="adopt-qoute-step2" class="adopt-slide"><div class="adopt-slide-inner">\
<p class="adopt-thank-you">Congratulations! you have become the first adopter for '+animal_label+'. Provide a quote below to associate with the animal profile:</p>\
<br><br>\
<div class="adopt-field cc_qoute"><input type="text" placeholder="Enter quote (Maximum 70 characters)" id="adp_qoute" maxlength="70">\
</div>\
<br clear="all"></div>\
<div class="adopt-outline-btn margin-top" id="adopt-qoute-btn2">\
<span class="adopti-spinner rotating" id="wildme_qoute_spinner"></span> <a href="javascript:;" id="wildme_qoute_submit">Continue</a></div></div><div style="display:none;" id="adopt-qoute-step4" class="adopt-slide">\
<div class="adopt-slide-inner">\
<p class="adopt-thank-you">Thank you for giving quote to '+animal_nick_name+animal_label+'. Share the news with your friends and ask them to earn their Champion badge as well.</p>\
<div class="batch"><img width="116" height="116" src="../../images/batch.png"></div></div>\
<div class="adopt-outline-btn margin-top " id="adopt-qoute-btn4"> <span class="adopti-spinner rotating"></span> <a class="share-btn" href="javascript:;"><i></i>Share</a></div><span id="close-adopt2" class="close-text close_qoute_div">Close</span></div></div>';

	$("#quote-div").html(popupHtml);
	
	
			$("#quote-div").fadeIn('slow', function(){
			
				$(".close_qoute_div").click(function(){
	 
				 $("#quote-div").fadeOut();
				});
				
				$("#adopt-qoute-btn4").click(function(e) {

				 // shareAdoption(id,cat,price,image_path,nickname,app_url);
				 shareAdoption();
					
				});
							
				$("#wildme_qoute_submit").click(function(){

				  var quote     = $("#adp_qoute").val();
				  $(".cc_qoute").css("border-color","");
				
				  if (quote=='')
				   {
					  $('#wildme_payment_spinner').hide();
					  $("#adp_qoute").focus();
					  $(".cc_qoute").css("border-color","red");
					  return false;
				   }
				   else
				   {
					 $('#wildme_qoute_spinner').show();
					 $('#wildme_qoute_submit').text('');
					 
			 $.post(canvas_url+"api/adoptor_qoute",  {uid: uid, animal_id: animal_id, quote: quote, adopter_id:adopter_id}, function(data){
	
					$('#wildme_qoute_spinner').hide();
					if(data.status == 'success'){
					
						$('#wildme_submit').text('');
						$("#adopt-qoute-step2").fadeOut(function(){$("#adopt-qoute-step4").fadeIn()});
					}else{
						$('#wildme_qoute_submit').text('Continue');
					}
					
	 		 }, "json");
			 
	  
				   }
				});
				
			});
			
			
			
	

		}
		
    });
    
</script>

    <?php echo render('inc.footer'); ?> </div>
</div>
</body>
</html>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WILDME</title>
<?php
$animal        = json_decode($animal, true);
$animal_record = $animal['records'][0];
//echo "<pre>";
//print_r($animal_record);
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
?>

<?php echo render('inc.js'); ?>

<script type="text/javascript">
   	$(document).ready(function() {

		//console.log(sPage);
		FB.Canvas.setSize({ height: 3350 });
        getAnimalCategory();
		getRecentEncounters(0);
		getAnimalPhotos(0);
		getAnimalFriends(0,'<?php echo $animal_record['id']; ?>');
		getAnimalFollower('<?php echo $animal_record['id']; ?>',0,10,0);
		getAnimalFriendsDetail(0,'<?php echo $animal_record['id']; ?>');

		//getAnimalPlaces('<?php echo  $animal_record['id']; ?>');

    });

</script>
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



</script>
<div class="main-wrapper">
  <?php echo render('inc.header'); ?>
  <div class="app-wraper white">
    <?php echo render('inc.popup');



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

      	<div class="wild-profile">
          <div class="pic">
            <div class="align-div  <?php echo $animal_record['category_color'] ?>"> <img src="<?php echo Config::get('application.web_url').$animal_record['category_icon']; ?>"> </div>
          </div>
          <div class="profile-banner-list">
            <h4><a href="#"><?php echo $animal_record['nick_name']; ?><span style="margin-left:5px;">(<?php echo $animal_record['id']; ?>)</span></a></h4>
            <ul>
              <?php if($animal_record['sex'] != ''){?><li ><span>Sex:</span> <?php if ($animal_record['sex']=='') echo 'unknown'; else echo $animal_record['sex']; ?></li><?php }?>
              <?php /*?><?php if($animal_record['size'] != ''){?><li><span>Length:</span> (<?php echo $animal_record['size']; ?> Meters</li><?php }?><?php */?>
              <li><span>Activity:</span> <?php echo $animal_record['encounter_count']; ?></li>
              <li> <span>Friends:</span> <?php echo $animal_record['friend_count']; ?></li>
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
            </div>
          </div>
        </div>

      </div>
      <div class="navigation">
        <ul>
          <li class="selected show-team-tab1" id="tfp-1"><a href="javascript:;"> <span><i class="icon-caret-up"></i></span>Activity</a></li>
          <li class="show-team-tab2" id="tfp-2"><a href="javascript:;"><span><i class="icon-caret-up"></i></span>About</a></li>
          <li class="show-team-tab3" id="tfp-3"><a href="javascript:;"><span><i class="icon-caret-up"></i></span>Photos</a></li>
          <li class="show-team-tab4" id="tfp-4" ><a href="javascript:;"><span><i class="icon-caret-up"></i></span>Places</a></li>
          <li class="show-team-tab5" id="tfp-5"><a href="javascript:;"><span><i class="icon-caret-up"></i></span>Friends</a></li>
        </ul>
      </div>
      <!-- ----------- step 1 -------------->
      <div class="post-listing group" id="team-tab1" style="display:block;">
        <div class="post-listing-left">
          <h1>All Activity (<span id="all-act">0</span>)</h1><div id="spinner-act" style="margin-left: 169px;
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
          <h1>Friends of <?php echo ($animal_record['nick_name'])?$animal_record['nick_name']:$animal_record['id'];?></h1><div id="spinner-wild" style="margin-left: 225px; margin-top: -21px; position: absolute; display:none;"><img class="icon-spin" src="../images/sp-new.png"  /></div>
          <div class="listing-main" style="margin-top:20px;">
            <div class="listing group">
              <ul id="animal-friend">
              </ul>
            </div>
            <h1 class="mr" >Followers(<span id="follower-count" >0</span>)<a href="#" id="view_all" style="display:none"; onclick="getAnimalFollower('<?php echo $animal_record['id']; ?>',0,10,1); FB.Canvas.scrollTo(0,0);"> VIEW ALL <i class="icon-double-angle-right"></i> </a></h1><div id="spinner-follow" style="margin-left: 169px;
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
          <ul>
            <li><span>Unique id:</span><?php echo $animal_record['id']; ?></li>
            <li><span>Nickname:</span><?php echo ($animal_record['nick_name'])?$animal_record['nick_name']:"Not Available"; ?></li>
            <li><span>NICKNAMER:</span><?php echo ($animal_record['nick_namer'])?$animal_record['nick_namer']:"Not Available"; ?></li>
            <li><span>type:</span><?php echo ($animal_record['category_title'])?$animal_record['category_title']:"Not Available"; ?></li>
            <li><span>sex:</span><?php echo ($animal_record['sex'])?$animal_record['sex']:"Not Available"; ?></li>
            <li><span>MORE INFORMATION:</span><a href="<?php echo $animal_record['api_url'].'individuals.jsp?number='.$animal_record['id']; ?>" target="_blank"><?php echo $animal_record['api_url'].'individuals.jsp?number='.$animal_record['id']; ?></a></li>
			<li><span>SPECIES:</span><?php if ($animal_record['encounter_data']['genus']=='' && $animal_record['encounter_data']['specific_epithet']=='') echo 'Not Available'; else echo $animal_record['encounter_data']['genus'].' '.$animal_record['encounter_data']['genus'];?></li>
          </ul>
        </div>
      </div>

      <!-- ----------- step 3 -------------->
      <div class="post-listing group" id="team-tab3" style="display:none;">
        <div class="photo">
          <h1>Photos</h1>
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
          <h1>Places <img style="display:none;" class="icon-spin" id="spinner-places" src="../images/sp-new.png" /> </h1>
		  <div class="map-palces" id="map-palces">
		  <iframe src="<?php echo Config::get('application.web_url');?>places.php?id=<?php echo $animal_record['id']; ?>" width="771" height="510" frameborder="0" scrolling="no"></iframe>
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
        <div class="row"> <span class="loadmore" id="loadmore_friend">LOAD MORE <img style="display:none;" class="icon-spin" id="loadmore-spinner-friend" src="../images/s-spinne.png" /></span> </div></div>
    </div>

    <?php echo render('inc.footer'); ?>
  </div>
</div>
</body>
</html>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WILDME</title>
<?php echo render('inc.js'); 
//digital.cygnismedia.com
?>
<script src="<?php echo Config::get('application.web_url');?>adopt-form/js/wildmepayment.js?timer=<?php echo date('ymdhis'); ?>"></script>
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
					
					FB.Canvas.setSize({ height: 920 });
					getAnimalCategory(1);
					
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
<div class="main-wrapper"> <?php echo render('inc.header'); ?>
  <?php $app_url = Config::get('application.app_url');?>
  <div class="app-wraper white">
    <!-- ---------------- MAP POUP ----------------->
    <div class="container">
      <div id="wildme-aboption-div" data-id="A-001" data-cat="Whale Shark" data-type="" data-price="25" data-icon="images/logo.jpg" data-nickname="stumpy" data-quote="stumpy is great" data-uid="652950032" data-cat_id="1" data-app_url="<?php echo $app_url ?>"></div>
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
      <div class="post-listing group">
        <h3>About us</h3>
        <p class="ab">"Wild Me" is a social and scientific experiment asking the question: "Can we link human and animal identity using scientific data and social media to generate increased awareness and interest in wildlife conservation?" Wild Me was conceived by Information Architect Jason Holmberg and is supported by the non-profit organizations <a target="_blank" href="http://www.wildme.org">Wild Me</a> and <a target="_blank" href="http://www.cascadiaresearch.org">Cascadia Research Collective</a>. Both organizations have a successful track record of bringing citizen scientists and biologists together to undertake collaborative wildlife population studies. Some great examples of their public and scientific collaborations in wildlife population monitoring include:</p>
        <p>
          <!--<ul style="margin:50px; color: #2C3E50; font:18px 'latolight',Arial;">
    <li type="square">
    (<a target="_blank" href="http://www.whaleshark.org">http://www.whaleshark.org</a>) - an exciting blend of biology, software, and NASA spinoff technology to study the world's largest fish, the whale shark!
	</li>
    <li type="square">
    (<a target="_blank" href="http://www.mantamatcher.org">http://www.mantamatcher.org</a>) - a new library for global-scale study of the giant manta ray.
	</li>
    <li type="square">
    (<a target="_blank" href="http://www.splashcatalog.org">http://www.splashcatalog.org</a>) - a collaborative catalog of humpback whales in the Pacific Ocean.
    </ul>-->
        <ul>
          <li style="color: #34495E; font: 14px/18px 'latolight',Arial;"><a href="http://www.whaleshark.org" target="_blank">http://www.whaleshark.org</a> - an exciting blend of biology, software, and NASA spinoff technology to study the world’s largest fish, the whale shark!</li>
          <li style="color: #34495E; font: 14px/18px 'latolight',Arial;"><a href="http://www.mantamatcher.org" target="_blank">http://www.mantamatcher.org</a> - a new library for global-scale study of the giant manta ray.</li>
          <li style="color: #34495E; font: 14px/18px 'latolight',Arial;"><a href="http://www.splashcatalog.org" target="_blank">http://www.splashcatalog.org</a> - a collaborative catalog of humpback whales in the Pacific Ocean.</li>
        </ul>
        </p>
        <p class="ab">In the process of these population studies, individual animals are given a tag, a name, or a number that distinguishes them uniquely to their human observers. In essence, they now have "identity" in a human context. Their behaviors are no longer collective, but rather we begin to see the differences among individual animals, just as we so keenly celebrate the diversity among ourselves. Wild Me translates the scientific data from these studies into a dynamic social media experience, allowing our social networks to cross not just time and space but also species.</p>
        <br />
        <h4>Can you help us?</h4>
        <p class="ab">As Edward O. Wilson wrote: "to celebrate the individual makes it easier to save the species." Can you help us celebrate wildlife by bringing these individual animals into your social network and following their progress over time? Or can you support our conservation mission with a donation to the project? All donation are tax deductible in the United States.</p>
        <br />
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank" style="margin:0px 288px 20px">
          <input type="hidden" name="cmd" value="_s-xclick">
          <input type="hidden" name="hosted_button_id" value="MTNC4J2WA28N2">
          <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
          <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
        </form>
        <h4 style="margin-left:273px">We appreciate your support!</h4>
        <br />
        <p class="ab">Remote from universal nature and living by complicated artifice, man in civilization surveys the creature through the glass of his knowledge and sees thereby a feather magnified and the whole image in distortion. We patronize them for their incompleteness, for their tragic fate for having taken form so far below ourselves. And therein do we err. For the animal shall not be measured by man. In a world older and more complete than ours, they move finished and complete, gifted with the extension of the senses we have lost or never attained, living by voices we shall never hear. They are not brethren, they are not underlings: they are other nations, caught with ourselves in the net of life and time, fellow prisoners of the splendour and travail of the earth."</p>
        <p>― <a target="_blank" href="http://www.goodreads.com/author/show/182465.Henry_Beston">Henry Beston</a>, <a target="_blank" href="http://www.goodreads.com/work/quotes/308220">The Outermost House</a></p>
      </div>
    </div>
    <?php echo render('inc.footer'); ?> </div>
</div>
</body>
</html>

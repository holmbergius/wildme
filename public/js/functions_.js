var type;


//YJS: add to favorite will be called after login with these temporary parameters
var temp_add_to_fav_params = new Array();

function GetUrlParms(name)
{
  name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
  var regexS = "[\\?&]"+name+"=([^&#]*)";
  var regex = new RegExp( regexS );
  var results = regex.exec( window.location );
  if( results == null )
    return "";
  else
    return results[1];
}
//Check security
function isSecure()
{
   return location.protocol == 'https:';
}

String.prototype.capitalize = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
}

function isValidEmail(em_address) 
{
	var email=/^[A-Za-z0-9]+([_\.-][A-Za-z0-9]+)*@[A-Za-z0-9]+([_\.-][A-Za-z0-9]+)*\.([A-Za-z]){2,4}$/i;
	return (email.test(em_address))
} // function ends

function SearchKeyword(e)
{
   // look for window.event in case event isn't passed in
   if (window.event) { e = window.event; }
   if ((e.keyCode == 13) || (e.type == 'click'))
   {
//action
		CheckPassword();
   }
}
function EnterSubmitLogin(e)
{
   // look for window.event in case event isn't passed in
   
   if (window.event) { e = window.event; }
   if ((e.keyCode == 13) || (e.type == 'click'))
   {
//action
		Login();
   }
}

function InviteFriends()
{
	
	if(uid == undefined || uid < 1 ){
		$(".show-signup").trigger('click');
		return;
	}
	
	FB.ui({
    display: 'iframe',
    method: 'apprequests',
    new_style_message: true,
    title: "Invite your friends for RaagPK",
    message: "Hey! Join in to RaagPk, and listen to the latest songs online. Find out what's hot today!!"
	}, InviteResponse);	
	
	/*$(".tooltipOptions").each(function(){
 	 $(".tooltipOptions").hide();
	 });
	 
	 
	FB.ui({
		method: 'apprequests',
		message: 'Pick the dream team Earn glory and win big prizes with the top notch players of your dream team.'
	  }, InviteResponse);	
	  */
	  
	  
}
function InviteResponse(response)
{
 if (response)
 {/*
  var request_id = response.request;
  var friends    = response.to;
  //var total_coins= friends.length*1000;
  $.post(API_URL+"invite_friend",  {from_uid: uid,	to_uid:friends, request_id:request_id, invite_type: 'Facebook', tournament_id:tournament_id, status:'Pending' }, function(data){
	  
	 $("#error_msg_heading").html('Success <span class="hide-cong" onclick="$(\'#teamAddSuccess\').hide();">CLOSE<i class="icon-remove"></i></span>');
				$("#error_msg_body").html('Your invitation has been sent successfully.');
				$("#teamAddSuccess").show();
                   
   }, "json" );

 */}
}

//invite by mail
function inviteByMail()
{
	//$('#icon-spinner-invite').show();
	var email = $('#invite_email_text').val();
	
	var request_id = Math.floor(Math.random()*900000000000000) + 100000000000000;
	
	if (email == '' || isValidEmail(email) != true )
	{
		$("#invite_email_text").addClass('invalid');
		//$('#icon-spinner-invite').hide();
	}
	else
	{
		
		$.post(API_URL+"invite_friend",  {from_uid: uid, to_uid:'', request_id:request_id, invite_type: 'Email', tournament_id:tournament_id, status:'Pending', to_email : email }, function(data){
			
			//$('#icon-spinner-invite').hide();
			if(data.status == 'success')
			{
				$('#invite_email_text').val('');
				$('#invite-email').hide();
				$('#cong').fadeIn();
			}
 		}, "json" );
	}
	
}


$(window).on("hashchange", function () {
	
	$('#container_left_spinner').show();
	$('.container-left').html('');
	$('#loadmore').hide();
	
    var hash =  window.location.hash.substring(1);
	var break_hash = hash.split('/');
	
	if(typeof break_hash[1] !== 'undefined' &&  break_hash[1] != '' )
	{
			
		if(break_hash[0] == 'album')
		{
			LoadDetailPage(break_hash[1],'album');
		}
		else if (break_hash[0] == 'artist')
		{
			LoadDetailPage(break_hash[1],'artist');
		}
		else if (break_hash[0] == 'playlist')
		{
			LoadDetailPage(break_hash[1],'playlist');
		}
		$('#listing_page_detail').show();
	}
	else
	{
		var artist 			= hash.match(/artist/i);
		var playlist 		= hash.match(/playlist/i);
		var album 			= hash.match(/album/i);
		var search_result 	= hash.match(/search/i);
		var latest 			= hash.match(/latest/i);
		var profile 		= hash.match(/profile/i);
		var cart 			= hash.match(/cart/i);
		var index 			= hash.match(/home/i);
		
		if(artist)
		{
			type = 'artist';
			LoadMainList();
			GetPopularPlaylist();
		}
		else if(album)
		{
			type = 'album';
			LoadMainList();
			GetPopularPlaylist();
		}
		else if(playlist)
		{
			type = 'playlist';
			LoadMainList();
			GetPopularAlbums();
			
		}
		else if(search_result)
		{
			LoadSearchResult();
			GetPopularPlaylist();
		}
		else if(latest)
		{
			type = 'newalbum';
			LoadMainList();
			GetPopularPlaylist();
			
		}
		else if(profile)
		{
			LoadProfilePage();
			
		}
		else if(cart)
		{
			LoadCartPage();
		}
		else if(index)
		{
			LoadHomePage();
		}
		else
		{
			type = 'album';
			LoadMainList();
			GetPopularPlaylist();
		}
		
	}
	$("html, body").animate({ scrollTop: 0 }, 1000);
	
});


var sPath   = window.location.pathname;
var sPage   = sPath.substring(sPath.lastIndexOf('/') + 1);
 
if (sPage == 'account')
{
	$(function(){
				
				getAccountSetting();
	});
	
}

if (sPage == 'forget-password')
{
	$(function(){
				
				validateForgetPassword();
	});
	
}

function SharePhoto(img_path, desc, url)
{
	//console.log(canvas_url+img_path);
	var message = 'Hey, listen to '+desc+' on RaagPk and discover more music while you enjoy. Stream Online, and create your own playlists to listen later on.. Have fun with the freedom of actions on RaagPk';
		 var obj = {
			 method: 'feed',
			 link : canvas_url+url,    
			 picture: canvas_url+img_path,
			 name: message,
			 description: 'Join RaagPk - the home of music - Listen to the songs of your choice and rejuvenate your experience of online streaming with RaagPk.'
		   }; 
		  
		 FB.ui(obj, callbackPublish);

}


function callbackPublish(response)
{	
	if(response && response.post_id) 
	{
		//_cyg_event('publish_feed');
//		var  winner_id	=	userid;//getURLParameter('user_id');
		//alert(winner_id);
/*			$.ajax({
			url: API_URL+"stat",
			data: { "user_id": share_einner_id},
			dataType: "json",
			type : "PUT",
			cache: false
			}).done(function( html ) {

			});		*/
		
	}
	else
	{
	}
}
/*****************************Function Check Password **************************/

function getURLParameter(name) {
    return decodeURI(
        (RegExp(name + '=' + '(.+?)(&|$)').exec(location.search)||[,null])[1]
    );
}

/*

//YJS: add to favorites
function AddToFavorite(ref_id, type, ele)
{	
	  $.post(API_URL+"favorite", {
		  'uid':uid, 'ref_id':ref_id, 'type':type}, 
		  function(data){
		   if (data.status == "success")
		   {  
		   }
		   else
		   {
		   }
	 
	  }, "json");
}

*/




//YJS: add to favorites
function AddToFavorite(ref_id, type, ele, flag)
{
	if(uid == undefined || uid < 1 ){
		$(".show-signup").trigger('click');
		$(".albumoptions").hide();
		temp_add_to_fav_params = Array(ref_id, type, ele);
		return;
	}
	
	if(flag !== true) $(ele).text("");
	//if(flag !== true) $(ele).text("Mark Unfavorite");

	if(flag == true && type != "song"){
		$(ele).parents("li").addClass("selected");
	}else if(flag == true && type == "song"){
		$(ele).parent("div").addClass("selected");

		$(ele).parent("div").find(".small-icon-tip").text("Added To Favorite");
	}else if(flag != true && (type == "album" || type == "artist" || type == "playlist" )){
		$(ele).parents("a").addClass("selected");

	}

	$(ele).attr("onclick","MarkUnfavorite("+ ref_id +",'"+ type +"', this, "+ flag +")");
	$(".fav-spinner").show();
	  $.post(API_URL+"favorite", {
		  'uid':uid, 'ref_id':ref_id, 'type':type}, 
		  function(data){
		   if (data.status == "success")
		   {  
		   if(sPage == 'profile' && $("#tfp-1").hasClass('selected')) userFavorites('albums', 0); 
		   	if(flag !== true) $(ele).text("Mark Unfavorite");
			$(ele).attr("onclick","MarkUnfavorite("+ ref_id +",'"+ type +"', this, "+ flag +")");
			$(".fav-spinner").hide();
			if(flag == true && type != "song"){
				$(ele).parents("li").addClass("selected");
			}else if(flag == true && type == "song"){
				$(ele).parent("div").addClass("selected");

				$(ele).parent("div").find(".small-icon-tip").text("Added To Favorite");
			}else if(flag != true && (type == "album" || type == "artist" || type == "playlist" )){
				$(ele).parents("a").addClass("selected");

			}
		   }
		   else
		   {
		   	if(flag !== true) $(ele).text("Add To Favorite");
		   	$(".fav-spinner").hide();
		   }
	 	$(".fav-spinner").hide();
	  }, "json");
}






//YJS: mark unfavorite
function MarkUnfavorite(ref_id, type, ele, flag){
	if(flag !== true) $(ele).text("");
	$(".fav-spinner").show();
	$.ajax({
      async:true, 
      data: {'uid':uid, 'ref_id':ref_id, 'type':type}, 
      dataType:'json', 
      type:'delete', 
      url:API_URL+"favorite",
      success: function(response)
	  	{ 
       		if (response.status == 'success')
			{
				if(sPage == 'profile' && $("#tfp-1").hasClass('selected')) userFavorites('albums', 0); 
				
  		   	    if(flag !== true) $(ele).text("Add To Favorite");
				$(ele).attr("onclick","AddToFavorite("+ ref_id +",'"+ type +"', this, "+ flag +")");  	  
				$(".fav-spinner").hide();
				if(flag == true && type != "song") $(ele).parents("li").removeClass("selected");

				else if(flag == true && type == "song"){
				 $(ele).parent("div").removeClass("selected");
				 $(ele).parent("div").find(".small-icon-tip").text("Favorite");
				}
				else if(flag != true && (type == "album" || type == "artist" || type == "playlist" )){
					$(ele).parents("a").removeClass("selected");
				}

			}
			else
			{
				if(flag !== true )  $(ele).text("Add To Favorite");
				$(".fav-spinner").hide();
			}
      	}
    }); 
}

//YJS: AddToCart 
function AddToCart(ele, artist_ids, title, ref_id, item_type, item_price, flag, songid)
{	
	if(flag !== true){
	 $(".cart-spinner").show();
	 $(ele).html("");
	}
	else $("#cart-spinner"+songid).show();
	temp = $(ele).html();
	$.post(API_URL+"cart", {
	  'uid':uid, 'title':title, 'artist_ids':artist_ids,'ref_id':ref_id, 'item_type':item_type, 'item_price':item_price}, 
	  function(data){
	   if (data.status == "success")
	   {  
	   	count = $(".cart-count span").text();
		count = parseInt(count);
		
		if(flag !== true) $(ele).text("Added To Cart");
		else $(ele).text("Added");
		$(ele).attr("onclick", "");
		$(".cart-count span").text(count+1);
	   }
	   else
	   {
	   	$(ele).html(temp);
	   }
 		$(".cart-spinner").hide();
 		if(flag == true)$("#cart-spinner"+songid).hide();
  	}, "json");
}



function Login()
{ 
	var email 	 = $("#login_email").val();
	var password = $("#login_password").val();
	$('#error_msgs').hide();
	//$("#icon-spinner").hide();
	
	$("#login_email").removeClass('invalid');
	$("#login_password").removeClass('invalid');
	
	if (email == '' || isValidEmail(email) != true )
	{
	 	$("#login_email").addClass('invalid');
	}
	else if (password == ''  || password == 'Enter your password')
	{
	 	$("#login_password").addClass('invalid');
	}
	else
	{
		$("#singnin_spinner2").css('display','block');
		$("#singnin_spinner2").show();
		$.get("api/login", {email : email , password : password,type:'web'} , function(data){
			
				uid = data.user_id;
				//console.log(data.status);
				$("#singnin_spinner2").hide();
				
				if(data.status == 'success')
				{
					uid = data.user_id;
					if(data.photo != "" && data.photo != null && data.photo != "null")
					{
						$('#user_image_head').attr('src',data.photo);
					}
					else
					{
						if(data.is_fb_user == '1' && data.facebook_id != '' )
						{
							$('#user_image_head').attr('src','//graph.facebook.com/'+data.facebook_id+'/picture?width=146&height=146');
						}else
						{
							$('#user_image_head').attr('src','images/default-img/user.jpg');	
						}
					}
					$(".hide-signup").trigger('click');
					$('#user_name_head').html(data.short_name);
					
					$("#header_before").hide();
					$("#header_after").show();

					// calling add to favorite method for adding item to favorite which was 
					// just before login popup

					if(temp_add_to_fav_params.length > 0 ){
						AddToFavorite(temp_add_to_fav_params[0], temp_add_to_fav_params[1], temp_add_to_fav_params[2]);
						temp_add_to_fav_params = new Array();
					}
						
					
				}
				else if(data.status == 'error' && data.msg == 'email does not exist')
				{	
					$('#error_msgs').html('<p style=" color: #E17070; ">Email does not match!</p>');
					$('#error_msgs').fadeIn('slow').delay(2000).fadeOut();	
				}
				else if(data.status == 'error2')
				{
					$('#error_msgs').html('<p style=" color: #E17070; ">Your account is inactive!</p>');
					$('#error_msgs').fadeIn('slow').delay(2000).fadeOut();	
					
				}
				else if(data.status == 'error')
				{
					$('#error_msgs').html('<p style=" color: #E17070; ">Wrong Email Or Password!</p>');
					$('#error_msgs').fadeIn('slow').delay(2000).fadeOut();	
				}
			
		},"json");
	}
}

function logoutUser()
{
	$.get("api/logout", {} , function(data){
		window.location = 'index.php';
	},"json");
}


function ShowLogin()
{
	$("#register").hide();
	$("#forgot_password").hide();
	$("#activation").hide();
	$("#sign-up").show("drop", {direction : "up" }, 500);
	
}


function ResetPassword()
{
	$("#login_email").removeClass('invalid');
	var forgot_email = $("#login_email").val();
	var error = 0;
	if (forgot_email == ''  || isValidEmail(forgot_email) != true )
	{
	 	$("#login_email").addClass('invalid');
		error = 1;
	}
	
	if(error == 0)
	{
		$("#singnin_spinner2").css('display','block');
		$("#singnin_spinner2").show();
		
		$('#forget_password_msgs').html('<i id="" class="icon-spinner icon-spin icon-2x pull-left" style=" display: block;  float: right;    margin-right: 286px; margin-top: -6px;"></i>').fadeIn('slow');

   $.get("api/login",  {email: forgot_email, type:'forgot'}, function(data){
		
		 
			  if(data.status == 'success')
			  {	
			  $("#singnin_spinner2").hide();
			  
			  	  	$('#error_msgs_forget').html('');
				  	$('#error_msgs_forget').html('<p style=" color: #0274DF;font: 12px trade_gothic_regularregular,Arial,Helvetica,sans-serif; ">Your Password has been sent to your email address, Thanks</p>').fadeIn(1000).delay(3000).fadeOut(1000, function(){ 
					
						$(".show-signin").trigger('click');
					});
			  }
			  else
   			  {
				    $("#singnin_spinner2").hide();
					$('#error_msgs_forget').html('');
					$('#error_msgs_forget').html('<p style=" color: #E17070;font: 12px trade_gothic_regularregular,Arial,Helvetica,sans-serif; ">Email does not match</p>').fadeIn(1000).delay(3000).fadeOut(1000);
			  }
			  
		  }, "json" );
	}
	
}



function FBLogin() {
	 $("#icon-spinner").show();
		 $("#singnin_spinner2").show();
		 $("#singnin_spinner2").css('display','block');
		 
	FB.login(function(response) {
    if (response.status === 'connected') 
	{
      access_token = FB.getAuthResponse()['accessToken']; /*response.authResponse.access_token;*/
	  FB.api('/me', function(resp) {
		
		  var user_id = response.authResponse.userID;
		 // var pact_id=''; 
		

		$.get("api/login",  {id: user_id, accessToken: access_token, type:'facebook'}, function(data){
			 $("#icon-spinner").hide();
			 $("#singnin_spinner2").hide();
			 
		
			 
			if(data.status == 'success' && data.msg == 'signin_fb')
			{
					uid = data.data.id;
					if(data.data.photo != "" && data.data.photo != null && data.data.photo != "null")
					{
						$('#user_image_head').attr('src',data.data.photo);
							//$('#user_image_head').attr('src','//graph.facebook.com/'+uid+'/picture?width=146&height=146');
					}
					else
					{
						//$('#user_image_head').attr('src','images/default-img/user.jpg');
							$('#user_image_head').attr('src','//graph.facebook.com/'+data.data.facebook_id+'/picture?width=146&height=146');
					}
					$(".hide-signup").trigger('click');
					$('#user_name_head').html(data.data.short_name);
					
					
					$("#header_before").hide();
					$("#header_after").show();
					
					if ($('#create-account').is(':visible')) {
						  
						   $('#create-account').hide();
					}
			}
			else
			{
				  if(data.id>0)
				  {
					   $("#user_name_sign").val(data.name);
					   $("#user_email").val(data.email);
					   $("#access_token").val(access_token);
					   $("#facebook_id").val(data.facebook_id);
					   $("#is_fb_user").val('1');
					   $("#gender").val(data.gender);
					   $("#age").val(data.age);
					   
					   is_fb_user = 1;
					   facebook_id = data.id;
					   uid = data.id;
					   
					 	if ($('#signup').is(':visible')) {
						  
					  	$('#signup').hide();
					    $('.show-create-account').trigger('click');
					   }
				  }
			}
		  }, "json" );
		    
		  });
    }
	else
	{
		 $("#icon-spinner").hide();
		 $("#singnin_spinner2").hide();
		 $("#singnin_spinner2").css('display','none');
		 
		
	}
  }, {scope: 'email, user_location, user_birthday'});
}

function RegisterUser()
{
	var user_name 		 = $("#user_name_sign").val();
	var user_email  	 = $("#user_email").val();
	var user_password    = $("#user_password").val();
	var cpassword 		 = $("#cpassword").val();
	var country 		 = $("#country").val();
	var gender 			 = $("#gender").val();
	var age 			 = $("#age").val();
	var error = 0;
	
	$("#user_name_sign").removeClass('invalid');
	$("#user_email").removeClass('invalid');
	
	$("#country").removeClass('invalid');
	$("#gender").removeClass('invalid');
	$("#age").removeClass('invalid');
	
	$("#user_password").removeClass('invalid');
	$("#cpassword").removeClass('invalid');
	$('#required_terms').hide('slow');	
	
	$('#register_msgs').hide();

	if (user_name == '' || user_name == 'Enter your full name' )
	{
	 	$("#user_name_sign").addClass('invalid');
	}
	else if (user_email == ''  || isValidEmail(user_email) != true )
	{
	 	$("#user_email").addClass('invalid');
	}
	else if (user_password == '' || user_password == 'Selecet 8-20 character password' )
	{
		$("#user_password").addClass('invalid');
	}
	else if ( user_password.length<8)
	{
		 $("#user_password").addClass('invalid');
	 	 $("#cpassword").addClass('invalid');
		 $('#register_msgs').html('<p style=" color: #E17070;font-size:12px;">Passwords must be more then 8 charecters long!</p>');
	 	 $('#register_msgs').fadeIn('slow').delay(2000).fadeOut('slow');
	}
	else if ( cpassword == '' || cpassword == 'Re-type your password' )
	{
		$("#cpassword").addClass('invalid');
	}
	else if ( cpassword != user_password )
	{
	  	$("#cpassword").addClass('invalid');
	  	$("#user_password").addClass('invalid');
	  	$('#register_msgs').html('<p style=" color: #E17070;font-size:12px;">Passwords Must Match!</p>');
	  	$('#register_msgs').fadeIn('slow').delay(2000).fadeOut('slow');
	}
	
	else if (country == '0' )
	{
	 	$("#country").addClass('invalid');
	}
	else if (gender == '0'  )
	{
		$("#gender").addClass('invalid');
	}
	
	else if (age == '0'  )
	{
	 	$("#age").addClass('invalid');
	}
	else if (!($('#agree_terms').is(':checked')))
	{
		 $('#register_msgs').html('Terms & Condition check is required!');
		 $('#register_msgs').fadeIn('slow').delay(2000).fadeOut('slow');
	}
	else
	{
	// $("#icon-spinner").show();
		 $('#signup_spin').html('<img src="images/load.gif" class="icon-spin"  />');
		 $('#signup_spin').fadeIn('slow');	
		 
		 $.get("api/login", {name:user_name,email : user_email , password : user_password,type:'registration',is_fb_user:is_fb_user, facebook_id:facebook_id, age:age, gender:gender, country:country } , function(data){
	 
				 $("#signup_spin").hide();
				if(data.status == 'success')
				{	
					$('#register_msgs').html('<p style=" color: #0274DF;font-size:12px;">Please Check Your Email For Account Activation</p>');
					$('#register_msgs').fadeIn('slow').delay(3000).fadeOut('slow', function() {	
						$('.show-signup').trigger('click');
					});
				   
				}
				else if(data.status == 'success2')
				{	
					
					$('#register_msgs').html('<p style=" color: #0274DF;font-size:12px;">Your Account has been created!</p>');
					$('#user_image_head').attr('src','//graph.facebook.com/'+data.facebook_id+'/picture?width=146&height=146');
					$('#user_name_head').html(data.short_name);
					$("#header_before").hide();
					$("#header_after").show();
					
					$('#register_msgs').fadeIn('slow').delay(3000).fadeOut('slow', function() {	
					$('.hide-create-account').trigger('click');
						
						
					});
				   
				}
				else if(data.status == 'error1' && data.msg == 'Email already exists')
				{
					$('#register_msgs').html('Email already exists! Please try another');
					$('#register_msgs').fadeIn('slow').delay(3000).fadeOut('slow');
				}
				else
				{
					$('#register_msgs').html('<p style=" color: #E17070;font-size:12px;">Error while registering your email</p>');
					$('#register_msgs').fadeIn('slow').delay(3000).fadeOut('slow');
				}
			
		},"json");
	}
}



var check_first=0;
var render_popular_album=0;
var render_popular_playlist=0;

function LoadMainList()
{
	$('#for_cart').html('');
	$('.listing_main_div').show();
	$('#container_left_spinner').show();
	$('.container-left').html('');
	$('.container-left').load('main-list', function() {
		$(this).children(':first').unwrap();
		
		if(type == 'artist')
		{
			$('#type_listing').text('Music Artist');
		}
		else
		{
			$('#type_listing').text(type.capitalize());
		}	

	});
	
}

function LoadProfilePage()
{
	$('#for_cart').html('');
	$('.listing_main_div').show();
	
	$('#container_left_spinner').show();
	$('.container-left').html('');
	$('.container-left').load('profile', function() {
		$(this).children(':first').unwrap();
	});
}

function LoadCartPage()
{
	$('#container_left_spinner').show();
	$('.listing_main_div').hide();
	
	$('.container-left').html('');
	$('#for_cart').load('mycart', function() {
		//$(this).children(':first').unwrap();
		
	});
	$('#container_left_spinner').hide();
}

function LoadHomePage()
{
	$('#container_left_spinner').show();
	$('.listing_main_div').hide();
	
	$('.container-left').html('');
	$('#for_cart').load('home', function() {
		//$(this).children(':first').unwrap();
		
	$("#scrollable").scrollable({circular: true});
	$("#scrollable1").scrollable({circular: true}).autoscroll({ autoplay: true }).navigator(".navi");
	$("#scrollable2").scrollable({circular: true});
	$("#scrollable3").scrollable({circular: true});
	$("#scrollable4").scrollable({circular: true});
	$("#scrollable5").scrollable({circular: true});
	$(".hide-top-h-add").click(function(){
		$("#top-h-add").hide("slide", {direction: "up"}, 280);
	});
	$('#container_left_spinner').hide();
	});
	
	
	

}

function LoadSearchResult()
{
	$('#for_cart').html('');
	$('.listing_main_div').show();
	
	$('.container-left').load('search-results', function() {
		$(this).children(':first').unwrap();
		
		
		$(".show-team-tab1").click(function(){
		  $("#team-tab1").show("fade", 600);
		  $("#team-tab2, #team-tab3, #team-tab4").hide();
		  $("#tfp-1").addClass('selected');
		  $("#tfp-2, #tfp-3, #tfp-4").removeClass('selected');
		});
		
		//Tab 2
		$(".show-team-tab2").click(function(){
		  $("#team-tab2").show("fade", 600);
		  $("#team-tab1, #team-tab3, #team-tab4").hide();
		  $("#tfp-2").addClass('selected');
		  $("#tfp-1, #tfp-3, #tfp-4").removeClass('selected');
		});
		
		//Tab 3
		$(".show-team-tab3").click(function(){
		  $("#team-tab3").show("fade", 600);
		  $("#team-tab1, #team-tab2, #team-tab4").hide();
		  $("#tfp-3").addClass('selected');
		  $("#tfp-2, #tfp-1, #tfp-4").removeClass('selected');
		});
		
		//Tab 4
		$(".show-team-tab4").click(function(){
		  $("#team-tab4").show("fade", 600);
		  $("#team-tab2, #team-tab3, #team-tab1").hide();
		  $("#tfp-4").addClass('selected');
		  $("#tfp-2, #tfp-3, #tfp-1").removeClass('selected');
		});

});
}

function LoadDetailPage(id,type)
{
	$('#for_cart').html('');
	$('.listing_main_div').show();
	
	$('.container-left').load('page-detail?id='+id+'&type='+type+'', function() {
		$(this).children(':first').unwrap();		
});
}


function Listings(page)
{
	$('#listing_spinner').show();
	//console.log(render_popular_playlist);
	
	if(page ==0) $("#result_div").hide();
	GetRandomAdds();
	
	if (page==0) page=1;
	var limit		= 9;
	var start_limit	= (page-1)*limit;
		start_limit	= (start_limit<0) ? 0 : start_limit;
	var front_list = '';
	var api_url = '';
	var artist_id = '';
	if(type == '' || type == null)type = 'album';
	if (type == 'newalbum' || type=='album') api_url = 'album'; 
	if (type=='artist') api_url = 'artist'; 
	if (type=='playlist') {  api_url = 'playlist'; front_list = 1;}

	if (type=='songs') { 
		api_url = 'song';
		artist_id = GetUrlParms('artist_id');
	}	
	var sortBy = '';
	// $('#sortBy').val();
	
	if(type=='newalbum' && check_first==0)
	{
		$("#most_recent").addClass('selected');
		$("#most_popular").removeClass('selected');
		type=='album';
		check_first=1;
	}
	
	if($("#most_popular").hasClass('selected'))sortBy = 'favt_count';
	else sortBy = 'id';
	var orderBy = $('#orderBy').val();
	if (orderBy == 0) orderBy = 'desc';
	
	if(orderBy == 'name_asc')
	{
		orderBy = 'ASC';
		sortBy = 'title';
		if(type=='artist')
		{
			sortBy = 'name';
		}
	}
	if(orderBy == 'name_desc')
	{
		orderBy = 'DESC';
		sortBy = 'title';
		if(type=='artist')
		{
			sortBy = 'name';
		}	
	}
	
	
	var keyword = $("#search_profile").val();
	//var noOfRecords = 15;
	var genre_id = $("#selected_genre").val();
	var cat_id 	 = $("#selected_catagory").val();
	
	$.get(API_URL+api_url, {offset:start_limit, limit:limit, keyword:keyword, sortby:sortBy, orderby:orderBy, artist_id:artist_id,category_id:cat_id , genre_id:genre_id, uid:uid, front_list: front_list}, function(data){

			if(data.status=='success')
			{
				
				var resultDiv = '';
				var total_records = parseInt(data.totalrecords);
				var listings	  = data.records;
				var current_records = listings.length;
				var setClass= '';
				if (current_records>0)
				{	
					if(page == 1)
					{
						if(type=='artist' || type=='playlist' )	{ resultDiv = '<ul class="marg-bottom-round">'; }
						else if (type=='songs'  ) {resultDiv = '<ul class="song-list-full">'; }
						else {resultDiv = '<ul>'; }
					}
					
					if(type=='album' || type == 'newalbum')	{ resultDiv += getAlbumListing(data); $("#result_div").addClass('content'); $("#result_div").addClass('group'); if(render_popular_playlist == 0){GetPopularPlaylist();}
			
					}
					if(type=='artist')	{	 resultDiv += getArtistListing(data); $("#result_div").addClass('content'); $("#result_div").addClass('group'); if(render_popular_playlist == 0){GetPopularPlaylist();}}
					
					if(type=='playlist'){ resultDiv += getPlaylistListing(data); $("#result_div").addClass('content'); $("#result_div").addClass('group'); 
					if(render_popular_album == 0){GetPopularAlbums();}}
					
					if(type=='songs'){ resultDiv += getSongsListing(data, 'album'); $("#result_div").removeClass('content'); $("#result_div").removeClass('group'); if(render_popular_playlist == 0){GetPopularPlaylist();} }
					
				//	$("#loadmore").html('<img class="icon-spin" src="images/load.gif" onclick="Listings('+(page+1)+');">LOAD MORE');
					$("#loadmore").attr('onClick','$("#loadmore_spinner").show(); Listings('+(page+1)+'); ');
					
					if(total_records>current_records)
					{
						$("#loadmore").show();
					}
					if (current_records<limit)
					{
						$("#loadmore").hide();
					}
				}
			}
			else 
			{
					if (page==1) resultDiv = '<ul><li style="height: 41px; width:100%" class="error">No '+capitaliseFirstLetter(type)+' Found!</li>';
					$("#loadmore").hide();
			}	
			
			$("#spinner").hide();
			if(page == 1)
			{
			 resultDiv +='</ul>';
			 $("#result_div").html(resultDiv);
			
			 $("#result_div").show('slow', function() {
  				  // Animation complete.
				   $('#container_left_spinner').hide();
				   	$('#listing_spinner').hide();
					  }); 
			 $('#container_left_spinner').hide();
			  resultDiv = '';
			}
			else
			{
			 $("#result_div ul").append(resultDiv);
			 $("#result_div").show('slow', function() {
  				  // Animation complete.
				   $('#container_left_spinner').hide();
				   	$('#listing_spinner').hide();
					  });
			
			 resultDiv = '';
			}
			  $("#result_div").mouseleave(function(){
				$(".albumoptions").each(function(){
				 $(".albumoptions").hide();
				 });
			  });
			//$("#container_left_spinner").hide();
			
			$('#listing_spinner').hide();
			$("#loadmore_spinner").hide();
	
			
		}, "json");
		
		
}
var retina_display = window.devicePixelRatio > 1;


function ShowAlbumOptions(album_id)
{ 
	$(".albumoptions").each(function(){
 	 $(".albumoptions").hide();
	});
	
	$("#album_option"+album_id).show();	
	
	/*
	var aa = $("#album_option"+album_id).css("display");
	 console.log(aa);
	 if($("#album_option"+album_id).css("display") == "block"){
		$("#album_option"+album_id).hide();
	}else{	
	}*/
	
	//$("#album_option"+album_id).show();
	//$("#albumOptionsButton"+album_id).attr('onClick','HideAlbumOptions(\''+album_id+'\');');
}

function HideAlbumOptions(album_id)
{
	$(".albumoptions").each(function(){
 	 $(".albumoptions").hide();
	 
	});
	$("#albumOptionsButton"+album_id).attr('onClick','ShowAlbumOptions(\''+album_id+'\');');
}





/*function getPlaylistSongsListing(data)
{
	
	var playListHTml='';
	var totalrecords 	= data.totalrecords;
	var songs 			= data.records;
	var currentLength   = songs.length;
	var setclass 	= '';
	var above_age 	= '';
	var artist_name;
	for (var i=0;i<currentLength;i++)
	{

		var setclass 	 = '';
		if (((i+1)%2)==0) {setclass = 'fill-gray'; }
		else { setclass = ''; }

		playListHTml += '<li class="'+setclass+'"><div class="play-btn"><img width="31" height="31" src="images/play-btn.png"></div><div class="list-info"><h3>'+songs[i].song_details.title+'<span>'+songs[i].song_details.artist_id_name[0].name+'</span></h3><div class="section-buy"><div class="time">'+songs[i].song_details.duration_min+'</div><div class="list-small-icon ic-1" onclick="javascript:SharePhoto(\''+songs[i].song_details.album_path+'\', \'Check this song '+ songs[i].song_details.title +' on Raag.pk\', \'#listings/'+songs[i].song_details.album_id+'\');"><i class="icon-share"></i><div class="small-icon-tip"><i class="icon-caret-up"></i>Share</div></div><div class="list-small-icon ic-2"><i class="icon-heart"></i><div class="small-icon-tip"><i class="icon-caret-up"></i>Favorite</div></div><div class="list-small-icon ic-3"><i class="icon-plus-sign"></i><div class="small-icon-tip wi"><i class="icon-caret-up"></i>Add to Playlist</div></div><a class="btn shadow" href="javascript:;"><i class="icon-shopping-cart"></i>Buy</a></div></div></li>';
	
	}
	
	return playListHTml;
}*/






function GetPopularPlaylist()
{
	 render_popular_playlist = 1;
	  //$("#right_panel_spinner").show();
	
	  $("#popular_list_type").html('Popular Playlist<span> - <a href="#playlist">See All</a></span>')
	
	  var playListHTml = '<ul>';
	 $.get(API_URL+'playlist', {offset:0, limit:4, keyword:'', sortby:'favt_count', orderby:'DESC', front_list: 1,uid: uid, front_list:1}, function(data){
		 if(data.status=='success')
		 {
			var listings  	 	= data.records;
			var currentLength 	= listings.length;
	  		var resultDiv = '';
	   		if (currentLength>0)
	  		{
		 		 var total_records 	= parseInt(data.totalrecords);
				 
				 var currentLength 	= listings.length;
				 var righttHTml		= '';
				 var last_class		= '';
				 var photo = '';
				 for (var i=0;i<currentLength;i++)
				  { 
				  
						  photo = listings[i].cover_photo;
				     	if(retina_display)  photo = photo.replace(".jpg","@2x.jpg");
						
						if(listings[i].is_fav == 0)
							fav_html = '<a href="javascript:;"><i class="icon-heart" style="float:left; margin-top: 2px;"></i> <span onclick="AddToFavorite('+listings[i].id+', \'playlist\', this)" style="font-size:12px;  " >Add to Favorite';
						else if(listings[i].is_fav == 1)
							fav_html = '<a class="selected" href="javascript:;"><i class="icon-heart" style="float:left; margin-top: 2px;"></i><span onclick="MarkUnfavorite('+listings[i].id+','+"'playlist'"+', this);">Mark Unfavorite';
		

						if(currentLength-1 == i)
						{
							last_class = 'last';
						}
						playListHTml +='<li class="'+last_class+'" style="position:relative;"><a href="#playlist/'+listings[i].id+'"><div class="pic65-play-list rounded"><img onerror="$(this).attr(\'src\',\'images/default-img/01.png\');"  src="'+canvas_url+'files/playlist/'+photo+'" /> </div></a><div class="widget-playlist-details"><h3><a style="color:#333333" href="#playlist/'+listings[i].id+'">'+listings[i].title+'</a><span><a  href="#playlist/'+listings[i].id+'">('+listings[i].song_count+' Songs)</a></span></h3><div class="list-small-icon" onclick="ShowToolTipOptions('+listings[i].id+');"><i class="icon-align-justify"></i></div></div>';
				
						playListHTml +='<div style="display:none;left:150px; top:56px;position:absolute;" id="right_options'+listings[i].id+'" class="play-list-tip"><i class="icon-caret-up"></i><ul class="pl hide-playlist"><li class="first" style="height:35px;"><a href="javascript:;" onclick="AddNewSongToPlaylist('+listings[i].id+',\'Playlist\');"><i class="icon-play"></i> Play Song</a></li><li class="first" style="height:35px;">'+ fav_html +'</span><img  src="images/load.gif" class="icon-spin fav-spinner" style="height:17px; position:relative; top:2px; display:none; left:38px;" /></a></li><li class="last" style="height:35px;"><a href="javascript:SharePhoto(\'files/playlist/'+listings[i].cover_photo+'\', \''+ listings[i].title +'\', \'listings#playlist/'+listings[i].id+'\');"><i class="icon-share"></i> Share</a></li></ul></div></li></li>';
				
				  }
			}
		
		 }
		 else 
		 {
		 playListHTml += '<div style=" width:299px; margin-left:-50px" class="error">No Playlist Found!</div>';
	   $("#popular_list_type").hide();
		 
		 } 
	  playListHTml +='</ul>';
		// $("#right_panel_spinner").hide();
		 $("#right_panel_content").html(playListHTml);
		 
	   }, "json");
}



function GetPopularAlbums()
{
	render_popular_album =1;
	$("#popular_list_type").html('Popular Music Albums<span> - <a href="#album">See All</a></span>')
	//$("#right_panel_spinner").show();
	var albumHTml = '<ul>';
	$.get(API_URL+'album', {offset:0, limit:4, keyword:'', sortby:'favt_count', orderby:'DESC', uid:uid}, function(data){
			if(data.status=='success')
			{
				
				var resultDiv = '';
				var total_records = parseInt(data.totalrecords);
				var listings   = data.records;
			    var currentLength = listings.length;
				var album_title;
				var photo = '';
				if (currentLength>0)
				{				
					for (var i=0;i<currentLength;i++)
					{
						
						var artist_name = ''; 
						var artLength   = listings[i].artist_id_name.length
						var last_class = '';
						//if(artist_name == null ){ artist_name = "Movie Album";}
						
						if(listings[i].album_type == "movie_album")
						{
							listings[i].artist_names = "Movie Album";
						}
						album_title = listings[i].title.substr(0,18);
						
						if(currentLength-1 == i)
						{
							last_class = 'last';
						}
						 photo = listings[i].cover_photo;
				     	if(retina_display)  photo = photo.replace(".jpg","@2x.jpg");

				     	if(listings[i].is_fav == 0)
							fav_html = '<a href="javascript:;"><i class="icon-heart" style="float:left; margin-top: 2px;"></i> <span onclick="AddToFavorite('+listings[i].id+', \'album\', this)" style="font-size:12px;" >Add to Favorite';
						else if(listings[i].is_fav == 1)
							fav_html = '<a href="javascript:;" class="selected"><i class="icon-heart" style="float:left; margin-top: 2px;"></i><span onclick="MarkUnfavorite('+listings[i].id+','+"'album'"+', this);">Mark Unfavorite';
									

						albumHTml += '<li class="'+last_class+'" style="position:relative;"><a href="#album/'+listings[i].id+'"><div class="pic65-play-list"><img onerror="$(this).attr(\'src\',\'images/default-img/02.png\');" src="'+canvas_url+'files/albums/'+listings[i].id+'/'+photo+'"/></div></a><div class="widget-playlist-details"><h3><a style="color:#333333" href="#album/'+listings[i].id+'">'+album_title+'</a><span style="width:155px;"><a href="#album/'+listings[i].id+'">'+listings[i].artist_names+'</a></span></h3><div class="list-small-icon" onclick="ShowToolTipOptions('+listings[i].id+');"><i class="icon-align-justify"></i></div></div>';
						
						
						albumHTml +='<div style="display:none;left:150px; top:56px;position:absolute;" id="right_options'+listings[i].id+'" class="play-list-tip"><i class="icon-caret-up"></i><ul class="pl hide-playlist"><li class="first" style="height:35px;"><a href="javascript:AddNewSongToPlaylist(\''+listings[i].id+'\', \'Album\');" ><i class="icon-play"></i> Play All Song</a></li><li class="first" style="height:35px;">'+ fav_html +'</span><img  src="images/load.gif" class="icon-spin fav-spinner" style="height:17px; position:relative; top:2px; display:none; left:38px;" /></a></li><li class="last" style="height:35px;"><a href="javascript:SharePhoto(\'files/albums/'+listings[i].id+'/'+listings[i].cover_photo+'\', \''+ listings[i].title +'\', \'listings#album/'+listings[i].id+'\');" ><i class="icon-share"></i> Share</a></li></ul></div></li></li>';
		
					}
				}
				
			}
			else 
			{
				 albumHTml += '<div style=" width:299px; margin-left:-50px" class="error">No Album Found!</div>';
				 $("#popular_list_type").hide();
					
			}	
			albumHTml +='</ul>';
			//$("#right_panel_spinner").hide();
			$("#right_panel_content").html(albumHTml);
								
		}, "json");
}


/*function GetPopularPlaylist()
{
	 render_popular_playlist = 1;
	  $("#right_panel_spinner").show();
	
	  $("#popular_list_type").html('Popular Playlist<span> - <a href="#playlist">See All</a></span>')
	
	  var playListHTml = '<ul>';
	 $.get(API_URL+'playlist', {offset:0, limit:10, keyword:'', sortby:'favt_count', orderby:'DESC'}, function(data){
		 if(data.status=='success')
		 {
	   var resultDiv = '';
	   var total_records = parseInt(data.totalrecords);
	   var listings   = data.records;
	   var currentLength = listings.length;
		  if (currentLength>0)
		  {
			 for (var i=0;i<currentLength;i++)
			  { 

		 			playListHTml +='<li  style="position:relative;" ><div class="pic65-play-list rounded"><img onerror="$(this).attr(\'src\',\'images/default-img/01.png\');"  src="'+canvas_url+'files/playlist/'+listings[i].cover_photo+'" /> </div><div class="widget-playlist-details"><h3>'+listings[i].title+'<span><a  href="#playlist/'+listings[i].id+'">('+listings[i].song_count+' Songs)</a></span></h3><div class="list-small-icon"><i class="icon-align-justify"></i></div></div></li>';

					
					
					
					playListHTml +='<div style="display:none;left:150px; top:56px;" id="right_options'+listings[i].id+'" class="play-list-tip"><i class="icon-caret-up"></i><ul class="pl hide-playlist"><li class="first"><a href="javascript:;"><i class="icon-play"></i> Play Song</a></li><li class="first"><a href="javascript:;"><i class="icon-heart"></i>Add to Favorite</a></li><li class="last"><a href="javascript:;"><i class="icon-share"></i> Share</a></li></ul></div></li>';

		   
			  }
		  }
		
		 }
		 else 
		 {
		 playListHTml += '<li style="height: 41px; width:100%" class="error"><h3>Sorry No Albums Found</h3></li>';
	   $("#popular_list_type").hide();
		 
		 } 
	  playListHTml +='</ul>';
		 $("#right_panel_spinner").hide();
		 $("#right_panel_content").html(playListHTml);
		 
	   }, "json");
}*/

function ShowToolTipOptions(id)
{
	
	$(".play-list-tip").each(function(){
 	 $(".play-list-tip").hide();
	});
	
	$("#right_options"+id).show();
}


function GetSongOfAlbum(album_id)
{
 	var albumHTml = '';
	 //$("#album_song_detail_spinner").show();
	 
	$.get(API_URL+'song', {album_id: album_id, uid: uid}, function(data){
   		if(data.status=='success')
   		{
			var total_records = parseInt(data.totalrecords);
    		if (total_records >0)
    		{
				$('#total_duration').text(data.totalduration);
				albumHTml += getSongsListing(data,'album');
    		}
  	 	}
   		else 
   		{
     		//albumHTml = '<li style="font:20px futura_stdheavy,Arial;border-bottom: none;">No Song Found</li>';
   		} 
	    //$("#album_song_detail_spinner").hide();
	    $("#result_div ul").html(albumHTml);
		$('#container_left_spinner').hide();
			
     
  	}, "json");
}

//cant use the default song function because of change in data structure.
function GetSongOfPlaylist(playlist_id)
{
	
 	var Html = '';
	 //$("#album_song_detail_spinner").show();
	 
	$.get(API_URL+'playlist_song', {playlist_id: playlist_id, uid:uid}, function(data){
   		if(data.status=='success')
   		{
			var total_records = parseInt(data.totalrecords);
    		if (total_records >0)
    		{
				$('#total_duration').text(data.totalduration);
				Html += getSongsListing(data, 'playlist');
    		}
  	 	}
   		else 
   		{
     		//Html = '<li style="font:20px futura_stdheavy,Arial;border-bottom: none;">No Song Found</li>';
   		} 
	    //$("#album_song_detail_spinner").hide();
	    $("#result_div ul").html(Html);
		$('#container_left_spinner').hide();
     
  	}, "json");
}


//cant use the default song function because of change in data structure.
function GetSongOfArtist(page,artist_id)
{
	if (page==0) page=1;
	var limit		= 15;
	var start_limit	= (page-1)*limit;
		start_limit	= (start_limit<0) ? 0 : start_limit;
 	var resultDiv ='';
	var sortBy = 'id';
	var orderBy = 'DESC';
	
	 //$("#album_song_detail_spinner").show();
	 
	$.get(API_URL+'song', {offset:start_limit, limit:limit, sortby:sortBy, orderby:orderBy, artist_id:artist_id, uid:uid}, function(data){
   		if(data.status=='success')
   		{
			var total_records = parseInt(data.totalrecords);
			var current_records = data.records.length;
			
    		if (total_records >0)
    		{
				$('#total_duration').text(data.totalduration);
				resultDiv += getSongsListing(data,'album');$("#result_div").removeClass('content'); $("#result_div").removeClass('group');
				$('#artist_song_count').text(data.totalrecords);
    		}
			
			$("#loadmore").attr('onClick','$("#loadmore_spinner").show(); GetSongOfArtist('+(page+1)+','+artist_id+'); ');
			
			if(total_records>current_records)
			{
				$("#loadmore").show();
			}
			if (current_records<limit)
			{
				$("#loadmore").hide();
			}

  	 	}
   		else 
   		{
			if(page == 1)
			{
     			//resultDiv = '<li style="font:20px futura_stdheavy,Arial;border-bottom: none;">No Song Found</li>';
			}
			$("#loadmore").hide();
   		}
		if(page > 1)
		{
		 	$("#result_div ul.song-list-full").append(resultDiv);
		 	resultDiv = '';
		}
		else
		{
		 	$("#result_div ul.song-list-full").html(resultDiv);
		}
	   	
		$('#loadmore_spinner').hide();
		$('#container_left_spinner').hide();
     
  	}, "json");
}

function GetArtistAlbum(artist_id)
{
 	var albumHTml = '';
	//$("#artist_album_spinner").show();
	$.get(API_URL+'album', {artist_id: artist_id, limit:100}, function(data){
   		if(data.status=='success')
   		{
			var total_records = parseInt(data.totalrecords);
    		if (total_records >0)
    		{
					albumHTml = getAlbumListing(data) ;
    		}
  	 	}
   		else 
   		{
		   $('.full-width.group').html('');
     		albumHTml = '<li style="height: 41px; width:100%" class="error">No Album Found!</li>';
   		} 
	   // $("#artist_album_spinner").hide();
	    $("#related_last_div").html(albumHTml);
     
  	}, "json");
}


function GetRelatedAlbum(album_id)
{
 	var albumHTml = '';
	//$("#related_album_spinner").show();
	$.get(API_URL+'relatedalbum', {id: album_id}, function(data){
   		if(data.status=='success')
   		{
			var total_records = parseInt(data.totalrecords);
    		if (total_records >0)
    		{
					albumHTml = getAlbumListing(data) ;
    		}
  	 	}
   		else 
   		{
     		albumHTml = '<li style="height: 41px; width:100%" class="error">No Album Found!</li>';
   		} 
	    //$("#related_album_spinner").hide();
	    $("#related_last_div").html(albumHTml);
		
		$(".albumoptions, .content").mouseleave(function(){
  $(".albumoptions").each(function(){
  $(".albumoptions").hide();
  });
  
  });
  
     
  	}, "json");
}


function GetRelatedArtist(album_id)
{
	var resultDiv 	= '';
	//$("#related_artist_spinner").show();
	$.get(API_URL+'related_artist', {id:album_id}, function(data){
		if(data.status=='success')
		{
			var artist	  		= data.records;
			var currentLength 	= artist.length;

			if (currentLength>0)
			{
				for (var i=0;i<currentLength;i++)
				{
					if(artist[i] != null){

						resultDiv +='<li><a href="#artist/'+artist[i].id+'" ><div class="pic65-play-list rounded"><a href="#artist/'+artist[i].id+'" ><img src="'+canvas_url+'files/artists/coverphotos/'+artist[i].cover_photo+'"/> </a></div></a><div class="widget-playlist-details"><h3><a style="color:#333333" href="#artist/'+artist[i].id+'" >'+artist[i].name+' </a><span><a href="#artist/'+artist[i].id+'" >('+artist[i].song_count+' Songs | '+artist[i].album_count+' Music Albums)</a></span></h3><i class="icon-chevron-right"></i></div></li>'

					}
	
				}
			}
			
		}
		else 
		{
			 resultDiv += '<div style=" width:299px; margin-left:-50px" class="error">No Artist Found!</div>';
				
		}	
		//$("#related_artist_spinner").hide();
		$("#right_panel_content ul").html(resultDiv);
		$('#popular_list_type').html('Related Music Artists - <span> <a href="#artist">See All</a></span>');
		
		//albumHTml
				
	}, "json");
//	right_panel_content
}



function GetRelatedArtistByTags(artist_id)
{
	var resultDiv 	= '';
	//$("#right_panel_spinner").show();
	$.get(API_URL+'related_artistbytags', {id:artist_id}, function(data){
		
		if(data.status=='success' && data.totalrecords>0)
		{
			var artist	  		= data.records;
			var currentLength 	= artist.length;
			if (currentLength>0)
			{
				for (var i=0;i<currentLength;i++)
				{
					
					resultDiv +='<li><div class="pic65-play-list rounded"><a  href="#artist/'+artist[i].id+'" ><img src="'+canvas_url+'files/artists/coverphotos/'+artist[i].cover_photo+'"/> </a></div><div class="widget-playlist-details"><h3><a  style="color:#333333" href="#artist/'+artist[i].id+'" >'+artist[i].name+'</a> <span><a  href="#artist/'+artist[i].id+'" >('+artist[i].song_count+' Songs | '+artist[i].album_count+' Music Albums)</a></span></h3><i class="icon-chevron-right"></i></div></li>';
				}
			}
			
		}
		else 
		{
			 resultDiv += '<div style=" width:299px; margin-left:-50px" class="error">No Artist Found!</div>';
				
		}	
		//$("#right_panel_spinner").hide();
		$("#right_panel_content ul").html(resultDiv);
		$('#popular_list_type').html('Related Music Artists - <span> <a href="#artist">See All</a></span>');

		
		//albumHTml
				
	}, "json");
//	right_panel_content
}



function SerchResults(search_type)
{
	type = search_type;
	var keyword =  $("#search_profile").val();
	if(keyword == '' || keyword == null)$("#search_keyword").html('Search Results');
	else $("#search_keyword").html('Search Results "'+keyword+'"');
	 Listings(0);
}

//window.history.back(-1);

function Search()
{
	var genre_id = $("#selected_genre").val();
	var cat_id 	 = $("#selected_catagory").val();
	
	var data_type = $("#search_menu .selected").data('type');
	var keyword =  $("#search_profile").val();
	

/*	$("#listing_filter").hide();
	$("#search_filter").show();*/
	detectHashTag();
	//SerchResults('album'); 
	
	//if (sPage == 'search-results') SerchResults(data_type);
	//else window.location = 'search-results?keyword='+keyword;
	//+'&genre_id='+genre_id+'&cat_id='+cat_id
}


function detectHashTag()
{
	//console.log('here');
 	if(window.location.hash)
 	{
		$('#container_left_spinner').show();
		$('.container-left').html('');
		
      	var hash = window.location.hash.substring(1); //Puts hash in variable, and removes the # character  
		
		var break_hash = hash.split('/');
		if(typeof break_hash[1] !== 'undefined' && break_hash[1] != '')
		{
			
			if(break_hash[0] == 'album')
			{
				LoadDetailPage(break_hash[1],'album');
			}
			else if (break_hash[0] == 'artist')
			{
				LoadDetailPage(break_hash[1],'artist');
			}
			else if (break_hash[0] == 'playlist')
			{
				LoadDetailPage(break_hash[1],'playlist');
			}
			
		}
		else
		{
			var artist 			= hash.match(/artist/i);
			var playlist 		= hash.match(/playlist/i);
			var album 			= hash.match(/album/i);
			var search_result 	= hash.match(/search/i);
			var latest 			= hash.match(/latest/i);
			var profile 		= hash.match(/profile/i);
			var cart 			= hash.match(/cart/i);
			var index 			= hash.match(/home/i);
			
			if(artist)
			{
				type = 'artist';
				LoadMainList();
			}
			else if(album)
			{
				type = 'album';
				LoadMainList();
			}
			else if(playlist)
			{
				type = 'playlist';
				LoadMainList();
			}
			else if(search_result)
			{
				LoadSearchResult();
				
			}
			else if(latest)
			{
				type = 'newalbum';
				LoadMainList();
				
			}
			else if(profile)
			{
				LoadProfilePage();
			}
			else if(cart)
			{
				LoadCartPage();
			}
			else if(index)
			{
				LoadHomePage();
			}
			else
			{
				type = 'album';
				LoadMainList();
			}
		}
	
 	}
 	else
 	{
	 	type = 'album';
		LoadMainList();
 	}
}

function EnterSearch(e)
{
   if (window.event) { e = window.event; }
   if ((e.keyCode == 13) || (e.type == 'click'))
   {
	   if(sPage != 'home')
	   {
	    	window.location = '#search';
			Search();
	   }
	   else
	   {
		   window.location = 'listings#search';
	   }
   }
}
function capitaliseFirstLetter(string)
{
    return string.charAt(0).toUpperCase() + string.slice(1);
}
var first_click_listing=0;
function HideCatMenu()
{
		$("#uh-drop").fadeOut(); 
		$("#discover-drop").fadeOut(); 	
}
function SetCatagory(id, name)
{
	//$('#container_left_spinner').show();
	$('#listing_spinner').show();
	$('#uh-drop').fadeOut();
	
	$.get(API_URL+'set_catagory', {id:id, name:name}, function(data){
		if(sPage == 'listings')
		  {
			  window.location = '#album';
		  }
		  else
		  {
			  window.location = 'listings#album';
		  }
		
		}, "json");
	$(".cat_list").each(function(){
 	 $(".cat_list").removeClass('selected');
	 });
	 
	$("#cat_list"+id).addClass('selected');
//	$("#cat_list"+id).attr('onClick','RemoveCatagory(\''+id+'\',\''+name+'\');');
	$("#sel_catagory").html('<i class="icon-globe"></i> '+name+' <i class="icon-caret-down"></i>');
    $("#selected_catagory").val(id);
	$("#selected_catagory_name").val(name);
	//$(\'#uh-drop\').hide(\'slide\', {direction: \'up\'}, 280);
/*	if(first_click_listing>0 && sPage != 'home' && sPage != 'index.php')
	{
		
	}*/
	Listings(0);
	first_click_listing++;
}

function RemoveCatagory(id, name)
{
	$.get(API_URL+'unset_catagory', {id:id}, function(data){}, "json");
	 $("#cat_list"+id).removeClass('selected');
	 $("#sel_catagory").html('<i class="icon-globe"></i>Discover<i class="icon-caret-down"></i>');
	 $("#cat_list"+id).attr('onClick','SetCatagory(\''+id+'\',\''+name+'\');');
	 $("#selected_catagory").val('');
	 $("#selected_catagory_name").val('');
}
function getCatagories()
{
	var catHtml = '';
	
	
	
		$.get(API_URL+'category', {offset:0, limit:50, keyword:'', sortby:'name', orderby:'ASC'}, function(data){
   		if(data.status=='success')
   		{
			var resultDiv = '';
			var total_records = parseInt(data.totalrecords);
			var cats   	  = data.records;
			var currentLength = cats.length;
    		if (total_records>0)
    		{
			   for (var i=0;i<currentLength;i++)
			   {
					catHtml +='<li class="cat_list" id="cat_list'+cats[i].id+'" onclick="SetCatagory(\''+cats[i].id+'\',\''+cats[i].name+'\'); $(\'#uh-drop\').hide(\'slide\', {direction: \'up\'}, 280);"><a href="javascript:;">'+cats[i].name+'</a><i class="icon-ok"></i></li>';
				
				}
    		}
  	 	}
   		/*else 
   		{
     		 catHtml += '<li class=""><a href="javascript:;" >No Catagory Found</a><i class="icon-ok"></i></li>';
   		} */
		
	
		
		catHtml += '';
		
	    $("#uh-drop").html(catHtml);
		    $("#uh-drop").mouseleave(function(){
			$('#uh-drop').hide();
 			 });
  
  
		
	var cat_id 	 = $("#selected_catagory").val();
	var cat_name 	 = $("#selected_catagory_name").val();
	
/*	if(cat_id !='' && cat_name != ''){SetCatagory(cat_id, cat_name);}*/
	
     
  	}, "json");
}

function SetGenre(id, name)
{
	 $(".gen_list").each(function(){
 	 $(".gen_list").removeClass('selected');
	 });
	 
	$("#gen_list"+id).addClass('selected');
	
	if(id =='') $('#container_left_spinner').show();
	
		
		 if(name == 'All Albums' )
		 {
			 $('#search_profile').val('');
			  if(sPage == 'listings')
	   		  {
				  window.location = '#album';
			  }
			  else
			  {
				  window.location = 'listings#album';
			  }
		 }
		 if(name == 'All Artist' )
		 {
			 $('#search_profile').val('');
			 if(sPage == 'listings')
	   		 {
				 window.location = '#artist';
			 }
			 else
			 {
				 window.location = 'listings#artist';
			 }
		 }
		 if( name == 'All Playlist' )
		 {
			 $('#search_profile').val('');
			 if(sPage == 'listings')
	   		 {
			 	window.location = '#playlist';
			 }
			 else
			 {
				  window.location = 'listings#playlist';
			 }
		 }

	$("#sel_genre").html('<i class="icon-globe"></i> '+name+' <i class="icon-caret-down"></i>');
	$("#selected_genre").val(id);
	$("#selected_genre_name").val(name);

	Listings(0);
	first_click_listing++;
  	$.get(API_URL+'set_genre', {id:id, name:name}, function(data){}, "json");

}
function RemoveGenre(id, name)
{
   	$.get(API_URL+'unset_genre', {id:id}, function(data){}, "json");
	 $("#gen_list"+id).removeClass('selected');
	 $("#sel_genre").html('<i class="icon-globe"></i>Discover <i class="icon-caret-down"></i>');
	 $("#gen_list"+id).attr('onClick','SetGenre(\''+id+'\',\''+name+'\');');
	 $("#selected_genre").val('');
	 $("#selected_genre_name").val('');
}

function getGenre()
{
	var genHtml = '';
				
			
			 genHtml +='<li class="gen_list" id="gen_list0" onclick="SetGenre(\'\',\'All Artist\');  $(\'#discover-drop\').hide(\'slide\', {direction: \'up\'}, 280);"><a href="javascript:;">All Artist</a><i class="icon-ok"></i></li>';
		 
		  genHtml +='<li class="gen_list" id="gen_list0" onclick="SetGenre(\'\',\'All Albums\');  $(\'#discover-drop\').hide(\'slide\', {direction: \'up\'}, 280);"><a href="javascript:;">All Albums</a><i class="icon-ok"></i></li>';
		  
		   genHtml +='<li class="gen_list" id="gen_list0" onclick="SetGenre(\'\',\'All Playlist\');  $(\'#discover-drop\').hide(\'slide\', {direction: \'up\'}, 280);"><a href="javascript:;">All Playlist</a><i class="icon-ok"></i></li>';
		   
		$.get(API_URL+'genre', {offset:0, limit:50, keyword:'', sortby:'name', orderby:'ASC'}, function(data){
   		if(data.status=='success')
   		{
			var resultDiv = '';
			var total_records = parseInt(data.totalrecords);
			var cats   	  = data.records;
			var currentLength = cats.length;

		   
    		if (total_records>0)
    		{
			   for (var i=0;i<currentLength;i++)
			   {
					genHtml +='<li class="gen_list" id="gen_list'+cats[i].id+'" onclick="SetGenre(\''+cats[i].id+'\',\''+cats[i].name+'\');  $(\'#discover-drop\').hide(\'slide\', {direction: \'up\'}, 280);"><a href="javascript:;">'+cats[i].name+'</a><i class="icon-ok"></i></li>';
				}
    		}
  	 	}
   		/*else 
   		{
     		 genHtml += '<li class=""><a href="javascript:;" >No Genre Found</a><i class="icon-ok"></i></li>';
   		} */
		 
		
		 
		genHtml += '';
		
	    $("#discover-drop").html(genHtml);
		
		    $("#discover-drop").mouseleave(function(){
			$('#discover-drop').hide();
 			 });
  
			
		var genre_id = $("#selected_genre").val();
		var genre_name = $("#selected_genre_name").val();
/*		if(genre_id!='' && genre_name != ''){SetGenre(genre_id, genre_name);}
*/		
     
  	}, "json");
}


$(document).ready(function() {
     getCatagories();
	 getGenre();
 
	if (sPage == 'home' || sPage == 'index.php' )
	{
		var code = GetUrlParms('code');
		if(code != '')
		{
		$('#forget_pass').show();
		var forgot_id = GetUrlParms('forgot_id');
		$('#forget_id').val(forgot_id);
		}
	}
	if(sPage == 'profile' )
	{
		var fid = getURLParameter('fid');
		//console.log(fid);
	}
		//$("#jquery_jplayer_1").jPlayer({
				/*ready: function (event) {
					$(this).jPlayer("setMedia", {
						mp3:canvas_url+playList[0].file_path,
						oga:canvas_url+playList[0].file_path.replace("mp3","ogg")
					}); //.jPlayer("play")
				},
				ended: function() { // The $.jPlayer.event.ended event
				$('#player_spinner').show();
					PlayNextSong();				
				},
				cssSelectorAncestor: "#player-container",
				swfPath: "jplayer/",
				preload: "auto",
				supplied: "mp3, oga",
				smoothPlayBar: true,
				keyEnabled: true,
				wmode: "window"
		});*/
});





function ForgotPassword()
{
	$("#forget_pass_field").removeClass('invalid');
	$("#forget_pass_confirm").removeClass('invalid');
	
	var forget_pass_field = $("#forget_pass_field").val();
	var forget_pass_confirm = $("#forget_pass_confirm").val();
	var error=0;

	if ( forget_pass_field == '' )
	{
		$("#forget_pass_field").addClass('invalid');
		error=1;
	}
	
	else if ( forget_pass_field.length<8)
	{
		$("#forget_pass_field").addClass('invalid');
		$("#forget_pass_confirm").addClass('invalid');
		$('#reset_msgs').html('<p style=" color: #E17070;font-size:12px;">Passwords must be more then 8 charecters long!</p>');
		$('#reset_msgs').fadeIn('slow').delay(2000).fadeOut('slow');
		error=1;
	}
	else if ( forget_pass_confirm == '' )
	{
		$("#forget_pass_confirm").addClass('invalid');
		error=1;
	}
	else if ( forget_pass_confirm != forget_pass_field )
	{
		$("#forget_pass_confirm").addClass('invalid');
		$("#forget_pass_field").addClass('invalid');
		$('#reset_msgs').html('<p style=" color: #E17070;font-size:12px;">Passwords Must Match!</p>');
		$('#reset_msgs').fadeIn('slow').delay(2000).fadeOut('slow');
		error=1;
	}
	
	
	if(error == 0)
	{
		$("#forgot_spin").css('display','block');
		$("#forgot_spin").show();
		var forget_id = $("#forget_id").val();
		var forget_email = $("#forget_email").val();
		/*
		$('#reset_msgs').html('<i id="" class="icon-spinner icon-spin icon-2x pull-left" style=" display: block;  float: right;    margin-right: 286px; margin-top: -6px;"></i>').fadeIn('slow');*/

   	$.get("api/login",  {password: forget_pass_field, id:forget_id,email:forget_email, type:'reset_password'}, function(data){
			  if(data.status == 'success')
			  {	
	   			  $("#forgot_spin").hide();
			  	  	$('#reset_msgs').html('');
				  	$('#reset_msgs').html('<p style=" color: #0274DF;font: 12px trade_gothic_regularregular,Arial,Helvetica,sans-serif; ">Your Password has been updated successfully</p>').fadeIn(1000).delay(3000).fadeOut(1000, function(){ 
					$("#login_email").val(forget_email);
					
 	$("#forgot_spin").hide();
	$('#forget_pass').hide();
	$('#reset_msgs').hide();
					$("#signup").show("fade", 500);
					$("#create-account").hide();
					
					});
			  }
			  else
   			  {
				    $("#forgot_spin").hide();
					$('#reset_msgs').html('');
					$('#reset_msgs').html('<p style=" color: #E17070;font: 12px trade_gothic_regularregular,Arial,Helvetica,sans-serif; ">Error While Updating Password</p>').fadeIn(1000).delay(3000).fadeOut(1000);
			  }
			  
		  }, "json" );
	}
}

function forgotPassEnter(e)
{
   // look for window.event in case event isn't passed in
   if (window.event) { e = window.event; }
   if ((e.keyCode == 13) || (e.type == 'click'))
   {
//action
		CheckPassword();
   }
}

//////////////////////// Playlist Functions \\\\\\\\\\\\\\\\\\\\\\\\

/*function PlayerPlaylist()
{
	var resultDiv = '';
	var songTitle = '';
	var totalrecords,currentLength,artist;

	$.get(API_URL+"song", {
		'offset':0,
		'limit':1,
		}, 
		function(data){
		if (data.totalrecords>0)
		{
			totalrecords 	= data.totalrecords;
			song 			= data.records;
			currentLength   = song.length;
			
			for (var i=0;i<currentLength;i++)
			{
				playList[playlistCount] = song[i];			
				if(song[i].album_name == null || song[i].album_name == ''){ song[i].album_name = 'no album';}
				
				songTitle = song[i].title;
				//if (song[i].album_name.length>10) song[i].album_name = song[i].album_name.substr(0,10)+'...';
				if (songTitle.length>15) songTitle = songTitle.substr(0,15)+'...';
			
				resultDiv +=' <li id="PlaylistSong'+playlistCount+'"> <i class="icon-remove-circle" onclick="RemoveSongPlaylist('+song[i].id+','+playlistCount+');"></i> <i class="icon-play-sign" onclick="PlayPlaylistSong('+song[i].id+','+playlistCount+');"></i><div class="pic60"><img src="'+playList[playlistCount].album_path+'"  /></div><h3 title="'+song[i].title+'">'+songTitle+' </h3></li>';
				
				playlistCount++;
				
 			}
		}
		else
		{
			$(".play-list-bar").hide();		
		}
     
		$("#playlistSongs").html(resultDiv);
		$('.scrollbar2').tinyscrollbar({ axis: 'x'});
		$('.scrollbar2').tinyscrollbar_update();
		$("#TotalPlaylistSong").html(playlistCount) ;
		$('#player_spinner').show();
		PlayPlaylistSong(0,0);
		
		$("#jquery_jplayer_1").jPlayer({
				ready: function (event) {
					$(this).jPlayer("setMedia", {
						mp3:canvas_url+playList[0].file_path,
						oga:canvas_url+playList[0].file_path.replace("mp3","ogg")
					}); //.jPlayer("play")
				},
				ended: function() { // The $.jPlayer.event.ended event
				$('#player_spinner').show();
					PlayNextSong();				
				},
				cssSelectorAncestor: "#player-container",
				swfPath: "jplayer/",
				preload: "auto",
				supplied: "mp3, oga",
				smoothPlayBar: true,
				keyEnabled: true,
				wmode: "window"
		});
		
		
		ClearPlaylist();
	
	}, "json");
	
}*/


function PlayerPlaylist()
{
	var resultDiv = '';
	var songTitle = '';
	var totalrecords,currentLength,artist;
	$(".play-list-bar").hide();
	$.get(API_URL+"song", {
		'offset':0,
		'limit':1,
		}, 
		function(data){
		if (data.totalrecords>0)
		{
			totalrecords 	= data.totalrecords;
			song 			= data.records;
			currentLength   = song.length;
			
			for (var i=0;i<currentLength;i++)
			{
				playList[playlistCount] = song[i];			
				if(song[i].album_name == null || song[i].album_name == ''){ song[i].album_name = 'no album';}
				
				songTitle = song[i].title;
				//if (song[i].album_name.length>10) song[i].album_name = song[i].album_name.substr(0,10)+'...';
				if (songTitle.length>15) songTitle = songTitle.substr(0,15)+'...';
			
				resultDiv +=' <li id="PlaylistSong'+playlistCount+'"> <i class="icon-remove-circle" onclick="RemoveSongPlaylist('+song[i].id+','+playlistCount+');"></i> <i class="icon-play-sign" onclick="PlayPlaylistSong('+song[i].id+','+playlistCount+');"></i><div class="pic60"><img src="'+playList[playlistCount].album_path+'"  /></div><h3 title="'+song[i].title+'">'+songTitle+' </h3></li>';
				
				playlistCount++;
				
 			}
		}
		else
		{
			$(".play-list-bar").hide();		
		}
     
		$("#playlistSongs").html(resultDiv);
		$('.scrollbar2').tinyscrollbar({ axis: 'x'});
		$('.scrollbar2').tinyscrollbar_update();
		$("#TotalPlaylistSong").html(parseInt($("#TotalPlaylistSong").text()) + 1) ;
		$('#player_spinner').show();
		// PlayPlaylistSong(0,0);
		
		$("#jquery_jplayer_1").jPlayer({
				ready: function (event) {
					/*$(this).jPlayer("setMedia", {
						mp3:canvas_url+playList[0].file_path,
						oga:canvas_url+playList[0].file_path.replace("mp3","ogg")
					}); //.jPlayer("play")*/
				},
				ended: function() { // The $.jPlayer.event.ended event
				$('#player_spinner').show();
					PlayNextSong();				
				},
				cssSelectorAncestor: "#player-container",
				swfPath: "jplayer/",
				preload: "auto",
				supplied: "mp3, oga",
				smoothPlayBar: true,
				keyEnabled: true,
				wmode: "window"
		});
		
		
		ClearPlaylist();
	
	}, "json");
	
}


/*function getUserPlaylistListing(page)
{
	// $("#play-tab1 .content.group").hide();
	// $("#container_left_spinner1").show();
	$("#small-spin-main").show();
	if (page==0) page=1;
	var limit		= 6;
	var start_limit	= (page-1)*limit;
		start_limit	= (start_limit<0) ? 0 : start_limit;
	
	var api_url = 'playlist';

	$.get(API_URL+api_url, {offset:start_limit, limit:limit, uid:uid}, function(data){

		var playListHTml='';
		var totalrecords 	= data.totalrecords;
		var playlist 		= data.records;
		var currentLength   = playlist.length;
		var setclass 	= '';
		var above_age 	= '';
		

		if(currentLength == 0 ) playListHTml='<li style="height: 41px; width:100%" class="error">No Playlist Found!</li>';
		for (var i=0;i<currentLength;i++)
		{
			var setclass 	 = '';
			if (((i+1)%3)==0) {setclass = 'last'; }
			else { setclass = ''; }
					//#playlist/'+playlist[i].id+'
			playListHTml +='<li class="'+setclass+'"><div class="round-control"> <a class="btn dark inner-btn" href="javascript:;"><i class="icon-play"></i> Play Songs</a> <a class="btn dark inner-btn" href="javascript:;"><i class="icon-cog"  style="float:left;"></i> Edit Playlist</a> <a class="btn dark inner-btn" href="javascript:;"><i class="icon-trash"></i> Delete Playlist</a> </div><div class="album-box-round"> <a href="javascript:;"><img onerror="$(this).attr(\'src\',\'images/default-img/01.png\');"  src="'+canvas_url+'files/playlist/'+playlist[i].cover_photo+'" /></a><h3>'+playlist[i].title+'<span><a href="#playlist/'+playlist[i].id+'" >( '+playlist[i].song_count+' Songs)</a></span></h3></div></li>';
			

		
		}//$("#play-tab1 .content.group")
		$(".loadmore").attr('onClick','$("#loadmore_spinner").show(); getUserPlaylistListing('+(page+1)+'); ');
					
		if(totalrecords>currentLength)
		{
			$(".loadmore").show();
		}
		if (currentLength<limit)
		{
			$(".loadmore").hide();
		}

		$("#container_left_spinner1").hide();
		if(page==1)
			$("#play-tab1 .content.group ul").html(playListHTml);
		else
			$("#play-tab1 .content.group ul").append(playListHTml);
		// $("#play-tab1 .content.group").show();
		$("#small-spin-main").hide();
		$("#user_playlist_count").text(totalrecords);
	},"json");
	
}*/

function userFavorites(type, page){
	
	$("#play-tab1, #play-tab3").hide();
	if 		(type == 'albums' )	type = 'album';
	else if (type == 'songs'  ) type = 'song'; 
	else if (type == 'artists'  ) type = 'artist'; 
	else if (type == 'playlists') type = 'playlist';	
	else 	return;
	$(".loadmore img").show();
	$("#small-spin").show();
	if (page==0) page=1;
	if(page == 1){
		$("#play-tab2 .content.group ul").html("");	
		$(".song-list-full").html("");	
	}
	var limit		= 9;
	var start_limit	= (page-1)*limit;
		start_limit	= (start_limit<0) ? 0 : start_limit;
	var html = "";
	$.get(API_URL+"favorite", {type:type,offset:start_limit, limit:limit, uid:uid}, function(data){		
		var totalrecords 	= data.data.totalrecords;
		var listings 		= data.data.records;
		var currentLength   = listings.length;		
		var div_id = "";
		if(data.data.status == "success")
		{
			data=data.data;
				if 	(type == 'album'   ) {	
					html = userFavoriteAlbumListings(data);
					div_id = "team-tab1";
				}
				else if (type == 'artist'    ) {
					html = userFavoriteArtistListings(data);
					div_id = "team-tab2";
				}
				else if (type == 'playlist') {
					html = userFavoritePlaylistListings(data);	
					div_id = "team-tab3";
				}
				else if (type == 'song'  ) {
					html = getSongsListing(data, 'favorite');	
					div_id = "team-tab4";
				}
			
			

			if(type=="song"){
				$("#play-tab2 .content.group ul").addClass("song-list-full");
				$(".song-list-full").parent("div").removeClass("group");
				$(".song-list-full").parent("div").removeClass("content");
			}else{
				$(".song-list-full").parent("div").addClass("content").addClass("group");
				$(".song-list-full").removeClass("song-list-full");	
				// $("#play-tab2 .content.group ul").parents("div").removeClass("group");
				// $("#play-tab2 .content.group ul").parents("div").removeClass("content");
			}
			
			if(type=="song") $("#play-tab2 #"+ div_id +" .song-list-full").append(html);
			else $("#play-tab2 .content.group ul").append(html);	
			
			$(".loadmore").attr('onClick','$(".loadmore_spinner").show(); userFavorites("' + type + 's", '+(page+1)+'); ');
			$(".loadmore").hide();		
			if(totalrecords>currentLength)
			{
				$(".loadmore").show();
			}
			if (currentLength<limit)
			{
				$(".loadmore").hide();
			}
		}
		else if(currentLength == 0 ){
			$(".song-list-full").parent("div").addClass("content").addClass("group");
				$(".song-list-full").removeClass("song-list-full");	
			 html='<li style="height: 41px; width:100%" class="error">No '+ capitaliseFirstLetter(type) +' Found!</li>';
			 $(".loadmore").hide();
			 //if(type=="song") $("#play-tab2 .song-list-full").append(html);
			 //else $("#play-tab2 .content.group ul").append(html);	
			 if(page == 1)
			 {
				$("#play-tab2 .content.group ul").html(html);	 
			 }
			 else
			 {
				 $("#play-tab2 .content.group ul").append(html);
			 }
			 	
		}
		
		$(".loadmore img").hide();
		$("#small-spin").hide();
	}, "json");
}
function capitaliseFirstLetter(string)
{
    return string.charAt(0).toUpperCase() + string.slice(1);
}




function ShufflePlaylist(type)
{
	playlistSuffule = type;
	if (type =='on')
	{
		$("#shuffle-on").hide();$("#shuffle-off").show();
	}
	else
	{
		$("#shuffle-off").hide();$("#shuffle-on").show();
	}		
}

function PlayPlaylistSong(song_id,counter)
{
	currentPlaylistSong = counter;
	
	var songTitle = playList[counter].title;
	
	//if (playList[counter].album_name.length>10) playList[counter].album_name = playList[counter].album_name.substr(0,10)+'...';
	if (songTitle.length>8) songTitle = songTitle.substr(0,8)+'...';
				
	var nowplaying = '<div class="pic40"><img src="'+playList[counter].album_path+'"  /></div><div class="play-info"><h4><span>Now Playing</span> '+songTitle+'</h4><span class="pink2"><a href="javascript:;"></a></span> </div>';
	$("#nowplaying").html(nowplaying);
	
	$(".play-bar-inner-content ul li").each(function() {
	   $(this).removeClass("selected");
	});
	$("#PlaylistSong"+counter).addClass("selected");

	$("#jquery_jplayer_1").jPlayer("setMedia", {
           mp3:canvas_url+playList[counter].file_path,
		   oga:canvas_url+playList[counter].file_path.replace("mp3","ogg")
        });
    $("#jquery_jplayer_1").jPlayer("play");	

	$('#player_spinner').hide();

}

function userFavoriteSongListings(data)
{
	var playListHTml='';
	var totalrecords 	= data.totalrecords;
	var listings 			= data.records;
	var currentLength   = listings.length;
	var setclass 	= '';
	var above_age 	= '';
	var artist_name;

	if(currentLength == 0 ) playListHTml='<li style="height: 41px; width:100%" class="error">No Song Found!</li>';
	for (var i=0;i<currentLength;i++)
	{
		
		
		var setclass 	 = '';
		if (((i+1)%2)==0) {setclass = 'fill-gray'; }
		else { setclass = ''; }
		songs=listings;
		songs[i] = songs[i]['song_details'];
		
		
		playListHTml += '<li class="'+setclass+'"><div class="play-btn"><img width="31" height="31" src="images/play-btn.png"></div><div class="list-info"><h3>'+songs[i].title+'<span>'+songs[i].artist_names+'</span></h3><div class="section-buy"><div class="time">'+songs[i].duration_min+'</div><div class="list-small-icon ic-1" onclick="SharePhoto(\''+ songs[i].album_path +'\', \''+ songs[i].title +'\', \'listings#album/'+ songs[i].album_id +'\');"><i class="icon-share"></i></div><div class="list-small-icon ic-2"><i class="icon-heart" onclick="AddToFavorite('+songs[i].id+','+"'song'"+', this, true)"></i></div><div class="list-small-icon ic-3"><i class="icon-plus-sign"></i></div><a class="btn shadow" href="javascript:;" style="min-width:40px;min-height:18px;" onclick="AddToCart(this, '+ songs[i].id +',1,'+ songs[i].id +',"song",4,true,'+ songs[i].id +');"><i class="icon-shopping-cart"></i>Buy</a><img  src="images/load.gif" class="icon-spin " id="cart-spinner'+ songs[i].id +'" style="height:17px;left:-12px;position: relative;top: 5px;display:none;" /></div></div></li>';
	
	}
	
	return playListHTml;
}

function getUserProfile()
{
 var id = GetUrlParms('id');
 
 $.get(API_URL+'user', {id: uid}, function(data){
    if(data.totalrecords > 0)
    {    
   var user    = data.records;
   var html       = data.records[0].name;
   var datejoined = data.records[0].date_added;   
   var fb_id  	  = data.records[0].facebook_id;
   var song_count = data.records[0].song_count;
   var img        = "images/default-img/user.jpg";
   if( fb_id !== 0 && fb_id !== '0'  )
   		img = "http://graph.facebook.com/"+fb_id+"/picture?width=130&height=130";
  }
  profiledata = data.records[0].profile_data;
  if(profiledata.status=='success')
   { 
    var data    = profiledata.records;
    var city    = profiledata.records[0].city;
    var country = profiledata.records[0].country;
   }
   if(city !== undefined && city !== "" && city !== null )
   	city = city + ", ";
   else city = "";
  if( country != undefined &&  country !== "" && country !== null )
  	from = 'From:'+city+country+'&nbsp;|&nbsp;';
  else 
  	from = "";
  $('#user_full_name').html(html);
  $('.pic-profile-130').html('<img src="'+img+'"  />');
  $('#countrydetail').html( from +'Member Since&nbsp;'+datejoined);
  $('#user_fav_songs_count').text(song_count);
  
 },"json");
 
}

	

function ClearPlaylist()
{
	$(".jp-pause").trigger("click");
	$("#playlistSongs").html('');
	$('.scrollbar2').tinyscrollbar({ axis: 'x'});
	$('.scrollbar2').tinyscrollbar_update();
	$("#TotalPlaylistSong").html('0');
	$("#nowplaying").hide();
	$(".play-list-bar").hide();
	playList = [];
	playlistCount = 0;
	currentPlaylistSong= 0;
	$(".icon-plus-sign").parents("div").removeClass("selected");
}


/*function AddNewSongToPlaylist(id,type)
{
	$(".play-list-bar").show();
	$("#nowplaying").show();
	
	var resultDiv = '';	
	
	if (type=='Song')
	{
		$.get(API_URL+"song", {'id':id},function(data){
			if (data.totalrecords>0)
			{
				totalrecords 	= data.totalrecords;
				song 			= data.records;
				
				playlistCount++;
				playList[playlistCount] = song;			
												
				resultDiv += GetPlaylistListingQue(song[0],playlistCount);
				
				$("#SongAdded_"+id).show();
				setTimeout(function(){$("#SongAdded_"+id).hide()},3000);			
				$("#playlistSongs").append(resultDiv);
				$("#TotalPlaylistSong").html(playlistCount) ;
				$('.scrollbar2').tinyscrollbar_update();			
			}     
		}, "json");
	}
	else if (type=='Album')
	{
		$.get(API_URL+"song", {'album_id':id,},function(data){
			if (data.totalrecords>0)
			{
				totalrecords 	= data.totalrecords;
				song 			= data.records;
				currentLength   = song.length;
				
				for (var i=0;i<currentLength;i++)
				{
					playlistCount++;
					playList[playlistCount] = song[i];									
					resultDiv += GetPlaylistListingQue(song[i],playlistCount);		
				}
				
				$("#playlistSongs").append(resultDiv);	
				$("#TotalPlaylistSong").html(playlistCount);
				
				$("#AlbumAdded"+id).show();
				setTimeout(function(){$("#AlbumAdded"+id).hide()},3000)
					
				if( $(".play-list-bar").hasClass("open") ) $(".play-list-bar").removeClass("open");
				else $(".play-list-bar").addClass("open");
				$(".play-bar-inner-content").show("slide", {direction:"up"}, 500);
				$('.scrollbar2').tinyscrollbar({ axis: 'x'});	
				$('.scrollbar2').tinyscrollbar_update();
			}     
		}, "json");
	}
	else if (type=='Artist')
	{
		$.get(API_URL+"song", {'artist_id':id,},function(data){
			if (data.totalrecords>0)
			{
				totalrecords 	= data.totalrecords;
				song 			= data.records;
				currentLength   = song.length;
				
				for (var i=0;i<currentLength;i++)
				{
					playlistCount++;
					playList[playlistCount] = song[i];									
					resultDiv += GetPlaylistListingQue(song[i],playlistCount);		
				}
				
				$("#playlistSongs").append(resultDiv);	
				$("#TotalPlaylistSong").html(playlistCount);
				
				$("#ArtistAdded"+id).show();
				setTimeout(function(){$("#ArtistAdded"+id).hide()},3000)
					
				if( $(".play-list-bar").hasClass("open") ) $(".play-list-bar").removeClass("open");
				else $(".play-list-bar").addClass("open");
				$(".play-bar-inner-content").show("slide", {direction:"up"}, 500);
				$('.scrollbar2').tinyscrollbar({ axis: 'x'});	
				$('.scrollbar2').tinyscrollbar_update();
			}     
		}, "json");
	}
	else if (type=='Playlist')
	{
		$.get(API_URL+"playlist_song", {'playlist_id':id,},function(data){
			if (data.totalrecords>0)
			{
				totalrecords 	= data.totalrecords;
				song 			= data.records;
				currentLength   = song.length;
				
				for (var i=0;i<currentLength;i++)
				{
					playlistCount++;
					playList[playlistCount] = song[i].song_details;									
					resultDiv += GetPlaylistListingQue(song[i].song_details,playlistCount);		
				}
				
				$("#playlistSongs").append(resultDiv);	
				$("#TotalPlaylistSong").html(playlistCount);
				
				$("#PlaylistAdded"+id).show();
				setTimeout(function(){$("#PlaylistAdded"+id).hide()},3000)
					
				if( $(".play-list-bar").hasClass("open") ) $(".play-list-bar").removeClass("open");
				else $(".play-list-bar").addClass("open");
				$(".play-bar-inner-content").show("slide", {direction:"up"}, 500);
				$('.scrollbar2').tinyscrollbar({ axis: 'x'});	
				$('.scrollbar2').tinyscrollbar_update();
			}     
		}, "json");
	}
	
}*/



/*function AddNewSongToPlaylist(id,type,ele)
{
	$(".play-list-bar").show();
	$("#nowplaying").show();
	
	var resultDiv = '';	
	
	if (type=='Song')
	{
		// $(ele).text("Added To Playlist").css({'width':'90px'});
		$(ele).parent('div').addClass('selected');
		$(ele).parent('div').find(".small-icon-tip").text('Added To Playlist');
		$(ele).attr({"onclick":"RemoveSongPlaylist("+ id +", "+ playlistCount +',this);$(this).parent(\'div\').removeClass(\'selected\');$(this).parent(\'div\').find(\'.small-icon-tip\').text(\'Add To Playlist\');'});
		$.get(API_URL+"song", {'id':id},function(data){
			if (data.totalrecords>0)
			{
				totalrecords 	= data.totalrecords;
				song 			= data.records;
				
				
				playList[playlistCount] = song[0];			
												
				resultDiv += GetPlaylistListingQue(song[0],playlistCount);
				
				$("#SongAdded_"+id).show();
				setTimeout(function(){$("#SongAdded_"+id).hide()},3000);			
				$("#playlistSongs").append(resultDiv);
				$("#TotalPlaylistSong").html(playlistCount) ;
				$('.scrollbar2').tinyscrollbar_update();	
				playlistCount++;		
			}     
		}, "json");
	}
	else if (type=='Album')
	{
		$.get(API_URL+"song", {'album_id':id,},function(data){
			if (data.totalrecords>0)
			{
				totalrecords 	= data.totalrecords;
				song 			= data.records;
				currentLength   = song.length;
				
				for (var i=0;i<currentLength;i++)
				{
					playlistCount++;
					playList[playlistCount] = song[i];									
					resultDiv += GetPlaylistListingQue(song[i],playlistCount);		
				}
				
				$("#playlistSongs").append(resultDiv);	
				$("#TotalPlaylistSong").html(playlistCount);
				
				$("#AlbumAdded"+id).show();
				setTimeout(function(){$("#AlbumAdded"+id).hide()},3000)
					
				if( $(".play-list-bar").hasClass("open") ) $(".play-list-bar").removeClass("open");
				else $(".play-list-bar").addClass("open");
				$(".play-bar-inner-content").show("slide", {direction:"up"}, 500);
				$('.scrollbar2').tinyscrollbar({ axis: 'x'});	
				$('.scrollbar2').tinyscrollbar_update();
			}     
		}, "json");
	}
	else if (type=='Artist')
	{
		$.get(API_URL+"song", {'artist_id':id,},function(data){
			if (data.totalrecords>0)
			{
				totalrecords 	= data.totalrecords;
				song 			= data.records;
				currentLength   = song.length;
				
				for (var i=0;i<currentLength;i++)
				{
					playlistCount++;
					playList[playlistCount] = song[i];									
					resultDiv += GetPlaylistListingQue(song[i],playlistCount);		
				}
				
				$("#playlistSongs").append(resultDiv);	
				$("#TotalPlaylistSong").html(playlistCount);
				
				$("#ArtistAdded"+id).show();
				setTimeout(function(){$("#ArtistAdded"+id).hide()},3000)
					
				if( $(".play-list-bar").hasClass("open") ) $(".play-list-bar").removeClass("open");
				else $(".play-list-bar").addClass("open");
				$(".play-bar-inner-content").show("slide", {direction:"up"}, 500);
				$('.scrollbar2').tinyscrollbar({ axis: 'x'});	
				$('.scrollbar2').tinyscrollbar_update();
			}     
		}, "json");
	}
	else if (type=='Playlist')
	{
		$.get(API_URL+"playlist_song", {'playlist_id':id,},function(data){
			if (data.totalrecords>0)
			{
				totalrecords 	= data.totalrecords;
				song 			= data.records;
				currentLength   = song.length;
				
				for (var i=0;i<currentLength;i++)
				{
					playlistCount++;
					playList[playlistCount] = song[i].song_details;									
					resultDiv += GetPlaylistListingQue(song[i].song_details,playlistCount);		
				}
				
				$("#playlistSongs").append(resultDiv);	
				$("#TotalPlaylistSong").html(playlistCount);
				
				$("#PlaylistAdded"+id).show();
				setTimeout(function(){$("#PlaylistAdded"+id).hide()},3000)
					
				if( $(".play-list-bar").hasClass("open") ) $(".play-list-bar").removeClass("open");
				else $(".play-list-bar").addClass("open");
				$(".play-bar-inner-content").show("slide", {direction:"up"}, 500);
				$('.scrollbar2').tinyscrollbar({ axis: 'x'});	
				$('.scrollbar2').tinyscrollbar_update();
			}     
		}, "json");
	}
	
}*/


function AddNewSongToPlaylist(id,type,ele)
{
	$(".play-list-bar").show();
	$("#nowplaying").show();
	
	var resultDiv = '';	
	var playlist_old_count = $("#TotalPlaylistSong").text();
	var temp_count = playlistCount;
	if (type=='Song')
	{
		// $(ele).text("Added To Playlist").css({'width':'90px'});
		$(ele).parent('div').addClass('selected');
		$(ele).parent('div').find(".small-icon-tip").text('Added To Playlist');
		$(ele).attr({"onclick":"RemoveSongPlaylist("+ id +", "+ playlistCount +',this);$(this).parent(\'div\').removeClass(\'selected\');$(this).parent(\'div\').find(\'.small-icon-tip\').text(\'Add To Playlist\');'});
		$.get(API_URL+"song", {'id':id},function(data){
			if (data.totalrecords>0)
			{
				totalrecords 	= data.totalrecords;
				song 			= data.records;
				
				
				playList[playlistCount] = song[0];			
												
				resultDiv += GetPlaylistListingQue(song[0],playlistCount);
				
				$("#SongAdded_"+id).show();
				setTimeout(function(){$("#SongAdded_"+id).hide()},3000);			
				$("#playlistSongs").append(resultDiv);
				$("#TotalPlaylistSong").html(parseInt($("#TotalPlaylistSong").text()) + 1) ;
				$('.scrollbar2').tinyscrollbar_update();	
				playlistCount++;

				if(playlist_old_count == 0){
					PlayPlaylistSong(playList[temp_count].id,0);
				}		
			}     
		}, "json");
	}
	else if (type=='Album')
	{
		$.get(API_URL+"song", {'album_id':id,},function(data){
			if (data.totalrecords>0)
			{
				totalrecords 	= data.totalrecords;
				song 			= data.records;
				currentLength   = song.length;
				
				for (var i=0;i<currentLength;i++)
				{
					playList[playlistCount] = song[i];									
					resultDiv += GetPlaylistListingQue(song[i],playlistCount);		
					playlistCount++;
					$("#TotalPlaylistSong").html(parseInt($("#TotalPlaylistSong").text()) + 1) ;
				}
				
				$("#playlistSongs").append(resultDiv);	
				// $("#TotalPlaylistSong").html(playlistCount);
				
				$("#AlbumAdded"+id).show();
				setTimeout(function(){$("#AlbumAdded"+id).hide()},3000)
					
				if( $(".play-list-bar").hasClass("open") ) $(".play-list-bar").removeClass("open");
				else $(".play-list-bar").addClass("open");
				$(".play-bar-inner-content").show("slide", {direction:"up"}, 500);
				$('.scrollbar2').tinyscrollbar({ axis: 'x'});	
				$('.scrollbar2').tinyscrollbar_update();

				if(playlist_old_count == 0){
					PlayPlaylistSong(playList[temp_count].id,0);
				}		
			}     
		}, "json");
	}
	else if (type=='Artist')
	{
		$.get(API_URL+"song", {'artist_id':id,},function(data){
			if (data.totalrecords>0)
			{
				totalrecords 	= data.totalrecords;
				song 			= data.records;
				currentLength   = song.length;
				
				for (var i=0;i<currentLength;i++)
				{
					playList[playlistCount] = song[i];									
					resultDiv += GetPlaylistListingQue(song[i],playlistCount);		
					playlistCount++;
					$("#TotalPlaylistSong").html(parseInt($("#TotalPlaylistSong").text()) + 1) ;
				}
				
				$("#playlistSongs").append(resultDiv);	
				// $("#TotalPlaylistSong").html(playlistCount);
				
				$("#ArtistAdded"+id).show();
				setTimeout(function(){$("#ArtistAdded"+id).hide()},3000)
					
				if( $(".play-list-bar").hasClass("open") ) $(".play-list-bar").removeClass("open");
				else $(".play-list-bar").addClass("open");
				$(".play-bar-inner-content").show("slide", {direction:"up"}, 500);
				$('.scrollbar2').tinyscrollbar({ axis: 'x'});	
				$('.scrollbar2').tinyscrollbar_update();

				if(playlist_old_count == 0){
					PlayPlaylistSong(playList[temp_count].id,0);
				}		
			}     
		}, "json");
	}
	else if (type=='Playlist')
	{
		$.get(API_URL+"playlist_song", {'playlist_id':id,},function(data){
			if (data.totalrecords>0)
			{
				totalrecords 	= data.totalrecords;
				song 			= data.records;
				currentLength   = song.length;
				
				for (var i=0;i<currentLength;i++)
				{
					playList[playlistCount] = song[i].song_details;									
					resultDiv += GetPlaylistListingQue(song[i].song_details,playlistCount);		
					playlistCount++;
					$("#TotalPlaylistSong").html(parseInt($("#TotalPlaylistSong").text()) + 1);
				}
				
				$("#playlistSongs").append(resultDiv);	
				// $("#TotalPlaylistSong").html(playlistCount);
				
				$("#PlaylistAdded"+id).show();
				setTimeout(function(){$("#PlaylistAdded"+id).hide()},3000)
					
				if( $(".play-list-bar").hasClass("open") ) $(".play-list-bar").removeClass("open");
				else $(".play-list-bar").addClass("open");
				$(".play-bar-inner-content").show("slide", {direction:"up"}, 500);
				$('.scrollbar2').tinyscrollbar({ axis: 'x'});	
				$('.scrollbar2').tinyscrollbar_update();
				
				if(playlist_old_count == 0){
					PlayPlaylistSong(playList[temp_count].id,0);
				}		
			}     
		}, "json");
	}
	
}






function GetPlaylistListingQue(song,i)
{
	if(song.album_name == null || song.album_name == ''){ song.album_name = 'no album';}
					
	songTitle = song.title;
	//if (song.album_name.length>10) song.album_name = song.album_name.substr(0,10)+'...';
	if (songTitle.length>10) songTitle = songTitle.substr(0,10)+'...';
				
	var resultDiv =' <li id="PlaylistSong'+i+'"> <i class="icon-remove-circle" onclick="RemoveSongPlaylist('+song.id+','+i+');"></i> <i class="icon-play-sign" onclick="PlayPlaylistSong('+song.id+','+i+');"></i><div class="pic60"><img src="'+song.album_path+'"  /></div><h3 title="'+song.title+'">'+songTitle+' </h3></li>';
	return resultDiv;
}



function ShowFriendRecentlyPlayed(fid)
{
}

function inPlaylist(song_id){
	flag = false;
	$(playList).each(function(ind, val){
		if(val.id === song_id)
			flag = ind;
	});
	return flag;
}




function recommended()
{
 var html ='<ul>';
 var id = GetUrlParms('id'); 
 $("#popular_list_type").html('Recommended For You<span> ')
 
 $.get(API_URL+'relatedalbumsfavourite' , { id : uid } , function(data){
  if(data.status=='success')
  {
 
   var recommendedalbums = data.records;
   var total_records    = recommendedalbums.length;
   var currentLength    = recommendedalbums.length;

   if (total_records>0)
      {
		  var album_title;
      for (var i=0;i<currentLength;i++)
      {
			fav_html = '<a href="javascript:;" class=""><i class="icon-heart"></i><span onclick="AddToFavorite('+recommendedalbums[i].id+', \'album\', this);">Add to Favorite';
      		if(recommendedalbums[i].is_fav == 0)
				fav_html = '<a href="javascript:;" class=""><i class="icon-heart"></i><span onclick="AddToFavorite('+recommendedalbums[i].id+', \'album\', this);">Add to Favorite';
			else if(recommendedalbums[i].is_fav == 1)
				fav_html = '<a href="javascript:;" class="selected"><i class="icon-heart"></i><span onclick="MarkUnfavorite('+recommendedalbums[i].id+','+"'album'"+', this);">Mark Unfavorite';
		

			artistname  = recommendedalbums[i].artist_names.substr(0,24);
			album_title = recommendedalbums[i].title.substr(0,18);

						html += '<li style="position:relative;"><div class="pic65-play-list"><img onerror="$(this).attr(\'src\',\'images/default-img/02.png\');" src="'+canvas_url+'files/albums/'+recommendedalbums[i].id+'/'+recommendedalbums[i].cover_photo+'"/></div><div class="widget-playlist-details"><h3>'+album_title+'<span style="width:155px;"><a href="javascript:;">'+artistname+'</a></span></h3><div class="list-small-icon" onclick="ShowToolTipOptions('+recommendedalbums[i].id+');"><i class="icon-align-justify"></i></div></div>';

						html +='<div style="display:none;left:150px; top:56px;position:absolute;" id="right_options'+recommendedalbums[i].id+'" class="play-list-tip"><i class="icon-caret-up"></i><ul class="pl hide-playlist"><li class="first" style="height:35px;"><a href="javascript:;" onclick="AddNewSongToPlaylist('+recommendedalbums[i].id+',\'Album\');"><i class="icon-play"></i> Play Song</a></li><li class="first" style="height:35px;">'+ fav_html +'</span><img  src="images/load.gif" class="icon-spin fav-spinner" style="height:17px; position:relative; top:2px; display:none; left:38px;" /></a></li><li style="margin:0px;height:auto" class="last"><a href="javascript:SharePhoto(\'files/albums/'+recommendedalbums[i].id+'/'+recommendedalbums[i].cover_photo+'\', \''+recommendedalbums[i].title+'\', \'listings#album/'+recommendedalbums[i].id+'\');"><i class="icon-share"></i>Share</a></li></ul></div></li></li>';
	
    }
   }
  else 
  {
   html += '<div style=" width:299px; margin-left:-50px" class="error">No Records Found!</div>';
  } 

        
  }  
   html += '</ul>';
   $('#right_panel_content').html(html);
  
 },"json");
 $('#container_left_spinner').hide();
}





function GetRandomAdds(){
	var adds_images = ["1.jpg", "2.jpg", "3.jpg", "4.jpg"];
	ind = Math.floor((Math.random()*4)); 
	$("#banner_image").attr('src','images/advertisement/'+adds_images[ind]);
	//return canvas_url + "images/advertisement/" + adds_images[ind];
}

function getPlaylistListing(data)
{
	var playListHTml='';
	var totalrecords 	= data.totalrecords;
	var playlist 			= data.records;
	var currentLength   = playlist.length;
	var setclass 	= '';
	var above_age 	= '';
		var photo = '';
	for (var i=0;i<currentLength;i++)
	{
		var setclass 	 = '';
		if (((i+1)%3)==0) {setclass = 'last'; }
		else { setclass = ''; }
		
				photo = playlist[i].cover_photo;
				if(retina_display)  photo = photo.replace(".jpg","@2x.jpg");
		

		if(playlist[i].is_fav == 0)
			fav_html = '<a class="btn dark inner-btn" href="javascript:;"><i class="icon-heart"  style="float:left;"></i><span onclick="AddToFavorite('+playlist[i].id+', \'playlist\', this)" style="font-size:13px;" >Add to Favorite';
		else if(playlist[i].is_fav == 1)
			fav_html = '<a class="btn dark inner-btn selected" href="javascript:;"><i class="icon-heart" style="float:left;"></i><span onclick="MarkUnfavorite('+playlist[i].id+','+"'playlist'"+', this);">Mark Unfavorite';
			
		playListHTml +='<li class="'+setclass+'"><div class="round-control"> <a class="btn dark inner-btn" href="#playlist/'+playlist[i].id+'"><i class="icon-play"></i> Play Songs</a> '+ fav_html +'</span><img  src="images/load.gif" class="icon-spin fav-spinner" style="height:17px;position:relative;top:2px;display:none;left:-8px;" /></a> <a class="btn dark inner-btn" href="javascript:SharePhoto(\'files/playlist/'+playlist[i].cover_photo+'\', \''+ playlist[i].title +'\', \'listings#playlist/'+playlist[i].id+'\');"><i class="icon-share"></i> Share</a> </div><div class="album-box-round"> <a href="javascript:;"><img onerror="$(this).attr(\'src\',\'images/default-img/01.png\');"  src="'+canvas_url+'files/playlist/'+photo+'" /></a><h3>'+playlist[i].title+'<span><a href="#playlist/'+playlist[i].id+'" >( '+playlist[i].song_count+' Songs)</a></span></h3></div></li>';


	
	}
	
	return playListHTml;
}



function getAlbumListing(data)
{
	var albumHTml='';
	var totalrecords 	= data.totalrecords;
	var album 			= data.records;
	var currentLength   = album.length;
	var setclass 		= '';
	var above_age 		= '';
		
	for (var i=0;i<currentLength;i++)
	{
		var setclass 	 = '';
		 var photo ='';
		if (((i+1)%3)==0) {setclass = 'last'; }

		else { setclass = ''; }
		
		var artist_name = ''; 
		if(album[i].artist_id_name != null)
		{
			var artLength   = album[i].artist_id_name.length
			for(var j=0;j<1;j++)
			{
				if(j == 0){artist_name = album[i].artist_id_name[j].name;}
			}
		}
		//if(artist_name == null ){ artist_name = "Movie Album";}
		
		if(album[i].is_fav == 0)
			fav_html = '<a href="javascript:;" class=""><i class="icon-heart"></i><span onclick="AddToFavorite('+album[i].id+', \'album\', this);">Add to Favorite';
		else if(album[i].is_fav == 1)
			fav_html = '<a href="javascript:;" class="selected"><i class="icon-heart"></i><span onclick="MarkUnfavorite('+album[i].id+','+"'album'"+', this);">Mark Unfavorite';
					
		if(album[i].album_type == "movie_album")
		{
			artist_name = "Movie Album";
		}
		var genre = ''

				 photo = album[i].cover_photo;
				if(retina_display)  photo = photo.replace(".jpg","@2x.jpg");
				
				
				albumHTml +='<li class="'+setclass+'"><div class="album-box" > <a href="#album/'+album[i].id+'"><img onerror="$(this).attr(\'src\',\'images/default-img/02.png\');" src="'+canvas_url+'files/albums/'+album[i].id+'/'+photo+'"/></a><div class="title-bar"> <h3>'+album[i].title+'</h3><p>'+artist_name+'</p><div class="list-icon" onclick="ShowAlbumOptions(\''+album[i].id+'\');"><i id="albumOptionsButton'+album[i].id+'" class="icon-align-justify" ></i> </div></div> <div class="botm-shadow"><img src="images/botm-shadow.jpg" /></div></div>';
	
	albumHTml +='<div  id="album_option'+album[i].id+'" style="z-index:9999; margin-left: 160px; margin-top: 220px; position:absolute;display: none; top:-27px;" id="tip-small" class="list-icon-listing group albumoptions"  > <i class="icon-caret-up"></i><ul  class="hide-tip-small" style="margin:0px;" ><li style="margin:0px;height:auto" class="first"><a href="javascript:;" onclick="AddNewSongToPlaylist('+album[i].id+',\'Album\');"><i class="icon-play"></i>Play All Songs</a></li><li style="margin:0px;height:auto"><a href="javascript:;"><i class="icon-shopping-cart"></i><span onclick="AddToCart(this,\''+album[i].artist_ids+'\',\''+album[i].title+'\','+album[i].id+', \'album\', '+album[i].price_amount+');">Buy Album</span></a><img  src="images/load.gif" class="icon-spin cart-spinner" style="height:17px;left:-80px;position: relative;top: 2px;display:none;" /></li><li style="margin:0px;height:auto">'+ fav_html +'</span></span><img  src="images/load.gif" class="icon-spin fav-spinner" style="height:17px;position:relative;top:2px;display:none;left:36px;" /></a></li><li style="margin:0px;height:auto" class="last"><a href="javascript:SharePhoto(\'files/albums/'+album[i].id+'/'+album[i].cover_photo+'\', \''+ album[i].title +'\', \'listings#album/'+album[i].id+'\');"><i class="icon-share"></i>Share</a></li></ul></div>';


	}
	
	artist_name 	= ''; 
	return albumHTml;
}




function getArtistListing(data)
{
	
	var artistHTml='';
	var totalrecords 	= data.totalrecords;
	var artist 			= data.records;
	var currentLength   = artist.length;
	var setclass 	= '';
	var above_age 	= '';
	var photo = '';
		
	for (var i=0;i<currentLength;i++)
	{
		var setclass 	 = '';
		if (((i+1)%3)==0) {setclass = 'last'; }
		else { setclass = ''; }

		//if(artist_name == null ){ artist_name = "Movie Album";}
		
		var genre = ''
				photo = artist[i].cover_photo;
				if(retina_display)  photo = photo.replace(".jpg","@2x.jpg");
		
		if(artist[i].is_fav == 0)
			fav_html = '<a class="btn dark inner-btn" href="javascript:;"><i class="icon-heart" style="float:left;"></i> <span onclick="AddToFavorite('+artist[i].id+', \'artist\', this)" style="font-size:13px;">Add to Favorite';
		else if(artist[i].is_fav == 1)
			fav_html = '<a class="btn dark inner-btn selected" href="javascript:;"><i class="icon-heart" style="float:left;"></i><span onclick="MarkUnfavorite('+artist[i].id+','+"'artist'"+', this);">Mark Unfavorite';
			

		artistHTml +='<li class="'+setclass+'"><div class="round-control"> <a class="btn dark inner-btn" href="#artist/'+artist[i].id+'" ><i class="icon-align-justify"></i> View Details</a> <a class="btn dark inner-btn" href="javascript:;"><i class="icon-heart" style="float:left;"></i> <span onclick="AddToFavorite('+artist[i].id+', \'artist\', this)" style="font-size:13px;"> Add to Favorite</span><img  src="images/load.gif" class="icon-spin fav-spinner" style="height:17px;position:relative;top:2px;display:none;left:8px;" /></a> <a class="btn dark inner-btn" href="javascript:SharePhoto(\'files/artists/coverphotos/'+artist[i].cover_photo+'\', \''+artist[i].name +'\', \'listings#artist/'+artist[i].id+'\');"><i class="icon-share"></i> Share</a> </div><a href="#artist/'+artist[i].id+'"><div class="album-box-round" ><img onerror="$(this).attr(\'src\',\'images/default-img/01.png\');" src="'+canvas_url+'files/artists/coverphotos/'+photo+'"/><div class="title-bar-tip"><i class="icon-caret-up"></i><p>'+artist[i].name+'</p></div></div></a></li>';


	
	}
	artist_name 	= ''; 
	return artistHTml;
}





function GetSingleAlbum(album_id)
{
	$("#listing_page_detail").html('');
	
 	var albumHTml = '';
	//$("#album_detail_spinner").show();
	 	GetRandomAdds();
	 
	$.get(API_URL+'album', {id: album_id, uid: uid}, function(data){
   		if(data.status=='success')
   		{
			var resultDiv = '';
			var total_records = parseInt(data.totalrecords);
			var listings   	  = data.records[0];
			var currentLength = listings.length;
    		if (total_records >0)
    		{
						var  photo = listings.cover_photo;
				     	if(retina_display)  photo = photo.replace(".jpg","@2x.jpg");
						
    			if(listings.is_fav == 0)
    				fav_html = '<li class=""><a href="javascript:;"> <i class="icon-heart" onclick="AddToFavorite('+listings.id+','+"'album'"+', this, true);"> </i> </a></li>';
    			else if(listings.is_fav == 1)
    				fav_html = '<li class="selected"><a href="javascript:;"> <i class="icon-heart" onclick="MarkUnfavorite('+listings.id+','+"'album'"+', this, true);"> </i> </a></li>';
				var image_path = canvas_url+'files/albums/'+listings.id+'/'+listings.cover_photo;

				albumHTml +='<div class="songs-details"><div class="pic-profile-136"><img src="'+canvas_url+'files/albums/'+listings.id+'/'+photo+'"/></div><div class="profile-content"><h2>'+listings.title+'</h2><p class="pink" style="width:350px;">'+listings.summary+'</p><p>Total songs: '+listings.song_count+' &nbsp;|&nbsp; Length:<span id="total_duration">00:00</span></p><a href="javascript:;" class="btn shadow" style="min-width:165px;"><i class="icon-shopping-cart"></i> <span onclick="AddToCart(this,\''+listings.artist_ids+'\',\''+listings.title+'\','+listings.id+', \'album\', '+listings.price_amount+');">Buy Now</span><img  src="images/load.gif" class="icon-spin cart-spinner" style="height:17px;left:7px;position: relative;top: 2px;display:none;" /> <span class="price">'+listings.price_amount+' Rs.</span></a><div style="position: absolute; right: 46px; width: 97px; top: 2px; font-style: normal; font-variant: normal; font-weight: normal; font-size: 13px; line-height: normal; font-family: trade_gothic_regularregular, Arial, Helvetica, sans-serif; display: none;background: #000;color: #FFF;border: 1px solid #000;border-radius: 20px;padding: 10px;" id="AlbumAdded'+listings.id+'">Added to playlist</div><ul class="playlist-option"><li><a style="cursor:pointer;" onclick="AddNewSongToPlaylist('+listings.id+',\'Album\');"><i class="icon-ellipsis-vertical"></i> <i class="icon-play"></i></a></li>'+ fav_html +'<li><a href="javascript:SharePhoto(\'files/albums/'+listings.id+'/'+listings.cover_photo+'\', \''+ listings.title +'\' , \'listings#album/'+listings.id+'\');"><i class="icon-share"></i></a></li></ul></div></div>';

    		}
  	 	}
   		else 
   		{
     		albumHTml = '<li style="height: 41px; width:100%" class="error">No Album Found</li>';
   		} 
	   // $("#album_detail_spinner").hide();
	    $("#listing_page_detail").html(albumHTml);
		$('#artist_name').text('Related Albums');
     
  	}, "json");
}

function GetSingleArtist(artist_id)
{
 	var albumHTml = '';
	//$("#artist_detail_spinner").show();
		GetRandomAdds();
	$.get(API_URL+'artist', {id: artist_id, uid:uid}, function(data){
   		if(data.status=='success')
   		{
			var resultDiv = '';
			var total_records = parseInt(data.totalrecords);
			var listings   	  = data.records[0];
			var currentLength = listings.length;
    		if (total_records >0)
    		{
					if(listings.is_fav == 0)
    				fav_html = '<li class=""><a href="javascript:;"><i class="icon-heart" onclick="AddToFavorite('+listings.id+', \'artist\', this, true)"></i></a></li>';
    			else if(listings.is_fav == 1)
    				fav_html = '<li class="selected"><a href="javascript:;"> <i class="icon-heart" onclick="MarkUnfavorite('+listings.id+','+"'artist'"+', this, true);"> </i> </a></li>';
				
					var  photo = listings.cover_photo;
				     	if(retina_display)  photo = photo.replace(".jpg","@2x.jpg");
						
				albumHTml +='<div class="songs-details"><div class="pic-profile-136"><img src="'+canvas_url+'files/artists/coverphotos/'+photo+'"/></div><div class="profile-content"><h2>'+listings.name+'</h2><p class="pink" style="width:350px;">'+listings.biography+'</p><p>Total songs: <span id="artist_song_count">'+listings.song_count+'</span></p><ul class="playlist-option"><li><a href="javascript:;" onclick="AddNewSongToPlaylist('+listings.id+',\'Artist\');" ><i class="icon-ellipsis-vertical" ></i> <i class="icon-play"></i></a></li>'+ fav_html +'<li><a href="javascript:SharePhoto(\'files/artists/coverphotos/'+listings.cover_photo+'\', \''+listings.name +'\', \'listings#artist/'+listings.id+'\');"><i class="icon-share"></i></a></li></ul></div></div>';
				$('#artist_name').text(listings.name + ' Albums');
    		}
    
  	 	}
   		else 
   		{
     		albumHTml = '<li style="height: 41px; width:100%" class="error">No Artist Found!</li>';
   		} 
	    //$("#artist_detail_spinner").hide();
	    $("#listing_page_detail").html(albumHTml);
     
  	}, "json");
}


function GetSinglePlaylist(playlist_id)
{
 	var Html = '';
	//$("#playlist_detail_spinner").show();
		GetRandomAdds();
	$.get(API_URL+'playlist', {id: playlist_id, uid: uid, front_list:1}, function(data){
   		if(data.status=='success')
   		{
			var resultDiv = '';
			var total_records = parseInt(data.totalrecords);
			var listings   	  = data.records[0];
    		if (total_records >0)
    		{
				var  photo = listings.cover_photo;
		     	if(retina_display)  photo = photo.replace(".jpg","@2x.jpg");
				
				if(listings.is_fav == 0)
    				fav_html = '<li class=""><a href="javascript:;"><i class="icon-heart" onclick="AddToFavorite('+listings.id+', \'playlist\', this, true)"></i></a></li>';
    			else if(listings.is_fav == 1)
    				fav_html = '<li class="selected"><a href="javascript:;"> <i class="icon-heart" onclick="MarkUnfavorite('+listings.id+','+"'playlist'"+', this, true);"> </i> </a></li>';
					
				Html +='<div class="songs-details"><div class="pic-profile-136"><img src="'+canvas_url+'files/playlist/'+photo+'"/></div><div class="profile-content"><h2>'+listings.title+'</h2><p>Total songs: <span id="artist_song_count">'+listings.song_count+' </span></p><ul class="playlist-option"><li><a href="javascript:;" onclick="AddNewSongToPlaylist('+listings.id+',\'Playlist\');"><i class="icon-ellipsis-vertical"></i> <i class="icon-play"></i></a></li>'+ fav_html +'<li><a href="javascript:SharePhoto(\'files/playlist/'+listings.cover_photo+'\', \''+user_name +' is listening to '+ listings.title +' on RaagPk\', \'listings#playlist/'+listings.id+'\');"><i class="icon-share"></i></a></li></ul></div></div>';
				$('#artist_name').text('');
    		}
    
  	 	}
   		else 
   		{
     		Html = '<li style="height: 41px; width:100%" class="error">No Playlist Found!</li>';
   		} 
	    //$("#artist_detail_spinner").hide();
	    $("#listing_page_detail").html(Html);
     
  	}, "json");
}


//update
function PlayNextSong()
{
	var nextSong = currentPlaylistSong+1;
	
	if (nextSong>playlistCount) nextSong = 0;
	if (playlistSuffule=='on') nextSong = Math.floor( Math.random() * (1 + playlistCount - 0) ) + 0;
	if (typeof playList[nextSong]=='undefined')  nextSong = 0;		
	if (playList[nextSong]==null || playList[nextSong]== ''){ currentPlaylistSong++;  PlayNextSong();}
	else
	{	
		$('#player_spinner').show();
		PlayPlaylistSong(0,nextSong);
	}
	
}

//update
function PlayPrevSong()
{
	var prevSong = currentPlaylistSong-1;
	if (prevSong<0) prevSong = playlistCount-1;
	if (playlistSuffule=='on') prevSong = Math.floor( Math.random() * (1 + playlistCount - 0) ) + 0;
	if (typeof playList[prevSong]=='undefined')  prevSong = 0;		
	if (playList[prevSong]==null || playList[prevSong]== ''){ currentPlaylistSong--;  PlayPrevSong();}
	else
	{	
		$('#player_spinner').show();
		PlayPlaylistSong(0,prevSong);
	}
}

//add this function

function RemoveSongPlaylist(song_id, playlistCount, ele)
{
	$(ele).attr({onclick:"AddNewSongToPlaylist("+song_id+","+ "'Song'"+", this);"});
	var totalCount = $('#TotalPlaylistSong').text();
	$('#TotalPlaylistSong').text(parseInt(totalCount)-1);
	
	$('#PlaylistSong'+playlistCount+'').remove();
	playList[playlistCount] = null;
	if(totalCount == 1){
		ClearPlaylist();
	}
	//	playList.splice(playlistCount,1);
	
}




function getSongsListing(data, type)
{
	var playListHTml='';
	var totalrecords 	= data.totalrecords;
	var song_name			= '';
	var song_artist			= '';
	var song_id				= '';
	var song_duration		= '';
	var song_album_path		= '';
	var song_album_id		= '';
	
	var songs 			= data.records;
	var currentLength   = songs.length;
	var setclass 	= '';
	var above_age 	= '';
	var artist_name;
	for (var i=0;i<currentLength;i++)
	{
		
		
		var setclass 	 = '';
		if (((i+1)%2)==0) {setclass = 'fill-gray'; }
		else { setclass = ''; }
		
		if(type== 'playlist')
		{
			song_name		= songs[i].song_details.title;
			song_artist		= songs[i].song_details.artist_names;
			song_artist_id	= songs[i].song_details.artist_ids;
			song_id			= songs[i].song_details.id;
			song_duration	= songs[i].song_details.duration_min;
			song_album_path = songs[i].song_details.album_path;
			song_album_id 	= songs[i].song_details.album_id;
			price_amount 	= songs[i].song_details.price_amount;
			is_fav		 	= songs[i].song_details.is_fav;
		}
		else if(type== 'favorite')
		{
			song_name		= songs[i]['song_details'].title;
			song_artist		= songs[i]['song_details'].artist_names;
			song_artist_id	= songs[i]['song_details'].artist_ids;
			song_id			= songs[i]['song_details'].id;
			song_duration	= songs[i]['song_details'].duration_min;
			song_album_path = songs[i]['song_details'].album_path;
			song_album_id 	= songs[i]['song_details'].album_id;
			price_amount 	= songs[i]['song_details'].price_amount;
			is_fav		 	= songs[i]['song_details'].is_fav;
		}
		else
		{
			song_name		= songs[i].title;
			song_artist		= songs[i].artist_names;
			song_artist_id	= songs[i].artist_ids;
			song_id			= songs[i].id;
			song_duration	= songs[i].duration_min;
			song_album_path = songs[i].album_path;
			song_album_id 	= songs[i].album_id;
			price_amount 	= songs[i].price_amount;
			is_fav		 	= songs[i].is_fav;
		}

		if(sPage == "profile") 
			html_part = 'userFavorites(\'songs\', 0);';
		else 
			html_part = "";

		if(is_fav == 0)
			fav_html = '<div class="list-small-icon ic-2"><i class="icon-heart" onclick="AddToFavorite('+song_id+','+"'song'"+', this, true)"></i><div class="small-icon-tip"><i class="icon-caret-up"></i>Favorite</div> ';
		else if(is_fav == 1)
			fav_html = '<div class="list-small-icon ic-2 selected"><i class="icon-heart" onclick="MarkUnfavorite('+song_id+','+"'song'"+', this, true);'+html_part+'"></i><div class="small-icon-tip"><i class="icon-caret-up"></i>Added To Favorite</div>';
		in_playlist = inPlaylist(song_id);
		if(in_playlist == false){
			play_html = '<div class="list-small-icon ic-3"><i class="icon-plus-sign" onclick="AddNewSongToPlaylist('+song_id+',\'Song\', this);"></i><div class="small-icon-tip wi "><i class="icon-caret-up"></i>Add to playlist';
		}else{
			play_html = '<div class="list-small-icon ic-3 selected"><i class="icon-plus-sign" onclick="RemoveSongPlaylist('+ song_id +','+ in_playlist +',this);$(this).parent(\'div\').removeClass(\'selected\');$(this).parent(\'div\').find(\'.small-icon-tip\').text(\'Add To Playlist\');"></i><div class="small-icon-tip wi "><i class="icon-caret-up"></i>Added to playlist';
		}

		playListHTml += '<li class="'+setclass+'"><div class="play-btn"><img width="31" height="31" src="images/play-btn.png"></div><div class="list-info"><h3>'+song_name+'<span>'+song_artist+'</span></h3><div class="section-buy"><div style="position: absolute; right: 209px; width: 97px; top: -6px; font-style: normal; font-variant: normal; font-weight: normal; font-size: 13px; line-height: normal; font-family: trade_gothic_regularregular, Arial, Helvetica, sans-serif; display: none;background: #000;color: #FFF;border: 1px solid #000;border-radius: 20px;padding: 10px;" id="SongAdded_'+song_id+'">Added to Playlist</div><div class="time">'+song_duration+'</div><div class="list-small-icon ic-1" onclick="SharePhoto(\''+song_album_path+'\', \''+song_name +'\', \'listings#album/'+song_album_id +'\');"><i class="icon-share"></i> <div class="small-icon-tip"><i class="icon-caret-up"></i>Share</div></div>  '+ fav_html +' </div>'+ play_html +'</div></div><a class="btn shadow" href="javascript:;" style="min-width:40px;min-height:18px;" onclick="AddToCart(this,\''+song_artist_id+'\',\''+song_name+'\','+song_id+', \'song\', '+price_amount+', true,'+song_id+');"><i class="icon-shopping-cart"></i>Buy</a><img  src="images/load.gif" class="icon-spin " id="cart-spinner'+song_id+'" style="height:17px;left:20px;position: relative;top: 5px;display:none;" /></div></div></li>';


	
	}
	
	return playListHTml;
}










function userFavoriteAlbumListings(data){
	var albumHTml='';
	var totalrecords 	= data.totalrecords;
	var listings 		= data.records;
	var currentLength   = listings.length;
	var setclass 		= '';
	var above_age 		= '';
		
	if(currentLength == 0 ) albumHTml='<li style="height: 41px; width:100%" class="error">No Album Found!</li>';
	for (var i=0;i<currentLength;i++)
	{
		album=listings;
		album[i]=album[i]['album_details'];
		var setclass 	 = '';
		var photo = '';
		if (((i+1)%3)==0) {setclass = 'last'; }

		else { setclass = ''; }
		
		var artist_name = ''; 
		var artLength   = album[i].artist_id_name.length
		for(var j=0;j<1;j++)
		{
			if(j == 0){artist_name = album[i].artist_id_name[j].name;}
		}
		//if(artist_name == null ){ artist_name = "Movie Album";}
		
		if(album[i].album_type == "movie_album")
		{
			artist_name = "Movie Album";
		}
		var genre = ''
	  	photo = album[i].cover_photo;
	  	
	  	if(retina_display)  photo = photo.replace(".jpg","@2x.jpg");
		
		fav_html = '<a href="javascript:;" class="selected"><i class="icon-heart"></i><span onclick="MarkUnfavorite('+album[i].id+','+"'album'"+', this);userFavorites(\'albums\', 0);">Mark Unfavorite';

					

		albumHTml +='<li class="'+setclass+'"><div class="album-box" > <a href="#album/'+album[i].id+'"><img onerror="$(this).attr(\'src\',\'images/default-img/02.png\');" src="'+canvas_url+'files/albums/'+album[i].id+'/'+photo+'"/></a><div class="title-bar"> <h3>'+album[i].title+'</h3><p>'+artist_name+'</p><div class="list-icon" onclick="ShowAlbumOptions(\''+album[i].id+'\');"><i id="albumOptionsButton'+album[i].id+'" class="icon-align-justify" ></i> </div></div> <div class="botm-shadow"><img src="images/botm-shadow.jpg" /></div></div>';

		albumHTml +='<div  id="album_option'+album[i].id+'" style="z-index:9999; margin-left: 160px; margin-top: 220px; position:absolute;display: none; top:-27px;" id="tip-small" class="list-icon-listing group albumoptions"> <i class="icon-caret-up"></i><ul class="hide-tip-small" style="margin:0px;"><li style="margin:0px;height:auto" class="first"><a href="javascript:;"><i class="icon-play"></i>Play All Songs</a></li><li style="margin:0px;height:auto"><a href="javascript:;"><i class="icon-shopping-cart"></i><span onclick="AddToCart(this,\''+album[i].artist_ids+'\',\''+album[i].title+'\','+album[i].id+', \'album\', '+album[i].price_amount+');">Buy Album</span></a><img  src="images/load.gif" class="icon-spin cart-spinner" style="height:17px;left:-80px;position: relative;top: 2px;display:none;" /></li><li style="margin:0px;height:auto">'+ fav_html +'</span></span><img  src="images/load.gif" class="icon-spin fav-spinner" style="height:17px;position:relative;top:2px;display:none;left:36px;" /></a></li><li style="margin:0px;height:auto" class="last"><a href="javascript:SharePhoto(\'files/albums/'+album[i].id+'/'+album[i].cover_photo+'\', \''+ album[i].title +'\', \'#album/'+album[i].id+'\');"><i class="icon-share"></i>Share</a></li></ul></div></li>';


	}
	
	
	artist_name 	= ''; 
	return albumHTml;
}

function userFavoriteArtistListings(data)
{
	var artistHTml='';
	var totalrecords 	= data.totalrecords;
	var listings 			= data.records;
	var currentLength   = listings.length;
	var setclass 	= '';
	var above_age 	= '';
	var photo = '';
		
	if(currentLength == 0 ) artistHTml='<li style="height: 41px; width:100%" class="error">No Artist Found!</li>';
	for (var i=0;i<currentLength;i++)
	{
		
		var setclass 	 = '';
		if (((i+1)%3)==0) {setclass = 'last'; }
		else { setclass = ''; }
		artist=listings;
		artist[i]=artist[i]['artist_details'];
		//console.log(artist[i]);
		//if(artist_name == null ){ artist_name = "Movie Album";}
		 photo = artist[i].cover_photo;
		  if(retina_display)  photo = photo.replace(".jpg","@2x.jpg");
		  
		  
		var genre = ''
		fav_html = '<a class="btn dark inner-btn selected" href="javascript:;"><i class="icon-heart" style="float:left;"></i><span onclick="MarkUnfavorite('+artist[i].id+','+"'artist'"+', this);userFavorites(\'artists\', 0);">Mark Unfavorite';

			
		artistHTml +='<li class="'+setclass+'"><div class="round-control"> <a class="btn dark inner-btn" href="#artist/'+artist[i].id+'" ><i class="icon-align-justify"></i> View Details</a> '+ fav_html +'</span><img  src="images/load.gif" class="icon-spin fav-spinner" style="height:17px;position:relative;top:2px;display:none;left:8px;" /></a> <a class="btn dark inner-btn" href="javascript:SharePhoto(\'files/artists/coverphotos/'+artist[i].cover_photo+'\', \''+artist[i].name +'\', \'listings#artist/'+artist[i].id+'\');"><i class="icon-share"></i> Share</a> </div><a href="#artist/'+artist[i].id+'"><div class="album-box-round" ><img onerror="$(this).attr(\'src\',\'images/default-img/01.png\');" src="'+canvas_url+'files/artists/coverphotos/'+photo+'"/><div class="title-bar-tip"><i class="icon-caret-up"></i><p>'+artist[i].name+'</p></div></div></a></li>';

	
	}
	artist_name 	= ''; 
	return artistHTml;
}


function userFavoritePlaylistListings(data)
{
	var playListHTml='';
	var totalrecords 	= data.totalrecords;
	var listings 			= data.records;
	var currentLength   = listings.length;
	var setclass 	= '';
	var above_age 	= '';
		var photo = '';
	if(currentLength == 0 ) playListHTml='<li style="height: 41px; width:100%" class="error">No Playlist Found!</li>';
	for (var i=0;i<currentLength;i++)
	{
		var setclass 	 = '';
		if (((i+1)%3)==0) {setclass = 'last'; }
		else { setclass = ''; }
		playlist = listings;
		playlist[i] = playlist[i]['playlist_details'];


		photo = playlist[i].cover_photo;
		if(retina_display)  photo = photo.replace(".jpg","@2x.jpg");
		  
		fav_html = '<a class="btn dark inner-btn selected" href="javascript:;"><i class="icon-heart" style="float:left;"></i><span onclick="MarkUnfavorite('+playlist[i].id+','+"'playlist'"+', this);userFavorites(\'playlists\', 0);">Mark Unfavorite';  
		  
		playListHTml +='<li class="'+setclass+'"><div class="round-control"> <a class="btn dark inner-btn" href="#playlist/'+playlist[i].id+'"><i class="icon-play"></i> Play Songs</a> '+ fav_html +'</span><img  src="images/load.gif" class="icon-spin fav-spinner" style="height:17px;position:relative;top:2px;display:none;left:-8px;" /></a> <a class="btn dark inner-btn" href="javascript:SharePhoto(\'files/playlist/'+playlist[i].cover_photo+'\', \''+ playlist[i].title +'\', \'listings#playlist/'+playlist[i].id+'\');"><i class="icon-share"></i> Share</a> </div><div class="album-box-round"> <a href="javascript:;"><img onerror="$(this).attr(\'src\',\'images/default-img/01.png\');"  src="'+canvas_url+'files/playlist/'+photo+'" /></a><h3>'+playlist[i].title+'<span><a href="#playlist/'+playlist[i].id+'" >( '+playlist[i].song_count+' Songs)</a></span></h3></div></li>';


	
	}
	
	return playListHTml;
}



function GetUserPlaylistName()
{
	$('#new_playlist_name').val('');
	//$("#right_panel_spinner").show();
	var resultDiv = '';
	if(uid > 0)
	{
		var checkTotal = $('#TotalPlaylistSong').text();
		if(checkTotal != '0')
		{
			$.get(API_URL+'playlist', {uid: uid}, function(data){
				$('#save-playlist').fadeIn();
					if(data.status=='success')
					{
						
						var total_records 	= parseInt(data.totalrecords);
						var listings   		= data.records;
						var currentLength 	= listings.length;
						
						if (currentLength>0)
						{
							for (var i=0;i<currentLength;i++)
							{			
								resultDiv += '<span ><a href="javascript:saveToPlaylist(\''+listings[i].id+'\',\''+listings[i].title+'\');">'+listings[i].title+'<i class="icon-angle-right"></i></a></span>';
							}
				
						}
						
					}
					else 
					{
						 resultDiv += '';
					}	
					//$("#right_panel_spinner").hide();
					$("#user_playlists").html(resultDiv);
										
				}, "json");
		}
		else
		{
			$('#msg_of_playlist').html('No Songs!').fadeIn(1000).delay(2000).fadeOut(1000);
		}
	}
	else
	{
		$(".show-signup").trigger('click');
	}

}

function saveToPlaylist(id)
{
	$('#save-playlist').fadeOut();
	$('#playlist-name').fadeOut();
	if(typeof playList !== 'undefined' && playList.length > 0)
	{
		$.each( playList, function( key, value ) {
			
			if(value != null)
			{
				$.post(API_URL+"playlist_song", {'playlist_id':id,'song_id':value.id}, 
				function(data){
					
					if (data.status == "success")
					{
						  
					}
					else
					{
						
					}
				}, "json");
			}
		});
		$('#msg_of_playlist').html('Playlist Updated!').fadeIn(1000).delay(2000).fadeOut(1000);
	  	
	 }
	 else
	 {
		 
	 }

}

function saveToNewPlaylist()
{
	$('#new_playlist_name').removeClass('invalid');
	var playlist_name = $('#new_playlist_name').val();
	if(playlist_name == '')
	{
		$('#new_playlist_name').addClass('invalid');
	}
	else
	{
		$('#save-playlist2').fadeOut();
		if(typeof playList !== 'undefined' && playList.length > 0)
		{
			$.post(API_URL+"playlist", {'uid':uid,'title':playlist_name,'is_featured':0,'cover_photo':''}, 
			function(data){
				if (data.status == "success")
				{
					$('#msg_of_playlist').html('Playlist Added!').fadeIn(1000).delay(2000).fadeOut(1000);
					$('#save-playlist2').fadeOut();
					$('#new_playlist_name').val('');
					var playlist_id = data.playlist_id;
					
					$.each( playList, function( key, value ) {
						if(value != null)
						{
							$.post(API_URL+"playlist_song", {'playlist_id':playlist_id,'song_id':value.id}, 
							function(data){
								if (data.status == "success")
								{
									  
								}
								else
								{
									
								}
							}, "json");
						}
					});
				}
				else if(data.status == 'error2')
				{
					
				}
				else
				{
				}
			}, "json");
					
			
			
		 }
		 else
		 {
			 
		 }
	}
	
}

function deletePlaylist(id){
	$.ajax({
      async:true, 
      data: { 'id':id}, 
      dataType:'json', 
      type:'delete', 
      url:API_URL+"playlist",
      success: function(response)
	  	{ 
       		if (response.status == 'success')
			{
  		   	   getUserPlaylistListing(0);
			}
			else
			{
				
			}
      	}
    }); 
}


function getUserPlaylistListing(page)
{
	$("#play-tab2, #play-tab3").hide();
	
  $(".show-team-tab1").click(function(){
  $("#team-tab1").show("fade", 600);
  $("#team-tab2, #team-tab3, #team-tab4").hide();
  $("#tfp-1").addClass('selected');
  $("#tfp-2, #tfp-3, #tfp-4").removeClass('selected');
});

//Tab 2
$(".show-team-tab2").click(function(){
  $("#team-tab2").show("fade", 600);
  $("#team-tab1, #team-tab3, #team-tab4").hide();
  $("#tfp-2").addClass('selected');
  $("#tfp-1, #tfp-3, #tfp-4").removeClass('selected');
});

//Tab 3
$(".show-team-tab3").click(function(){
  $("#team-tab3").show("fade", 600);
  $("#team-tab1, #team-tab2, #team-tab4").hide();
  $("#tfp-3").addClass('selected');
  $("#tfp-2, #tfp-1, #tfp-4").removeClass('selected');
});

//Tab 4
$(".show-team-tab4").click(function(){
  $("#team-tab4").show("fade", 600);
  $("#team-tab2, #team-tab3, #team-tab1").hide();
  $("#tfp-4").addClass('selected');
  $("#tfp-2, #tfp-3, #tfp-1").removeClass('selected');
});




//Team fan page 
//Tab 1
$(".show-play-tab1").click(function(){
  $("#play-tab1").show("fade", 600);
  $("#play-tab2, #play-tab3, #play-tab4").hide();
  $("#tf-1").addClass('selected');
  $("#tf-2, #tf-3, #tf-4").removeClass('selected');
});

//Tab 2
$(".show-play-tab2").click(function(){
  $("#play-tab2").show("fade", 600);
  $("#play-tab1, #play-tab3, #play-tab4").hide();
  $("#tf-2").addClass('selected');
  $("#tf-1, #tf-3, #tf-4").removeClass('selected');
});

//Tab 3
$(".show-play-tab3").click(function(){
  $("#play-tab3").show("fade", 600);
  $("#play-tab1, #play-tab2, #play-tab4").hide();
  $("#tf-3").addClass('selected');
  $("#tf-2, #tf-1, #tf-4").removeClass('selected');
});

//Tab 4
$(".show-play-tab4").click(function(){
  $("#play-tab4").show("fade", 600);
  $("#play-tab2, #play-tab3, #play-tab1").hide();
  $("#tf-4").addClass('selected');
  $("#tf-2, #tf-3, #tf-1").removeClass('selected');
});

	
	// $("#play-tab1 .content.group").hide();
	// $("#container_left_spinner1").show();
		$("#small-spin-main").show();
	if (page==0) page=1;
	var limit		= 6;
	var start_limit	= (page-1)*limit;
		start_limit	= (start_limit<0) ? 0 : start_limit;
	
	var api_url = 'playlist';

	$.get(API_URL+api_url, {offset:start_limit, limit:limit, uid:uid}, function(data){

		var playListHTml='';
		var totalrecords 	= data.totalrecords;
		var playlist 		= data.records;
		var currentLength   = playlist.length;
		var setclass 	= '';
		var above_age 	= '';
		

		if(currentLength == 0 ) playListHTml='<li style="height: 41px; width:100%" class="error">No Playlist Found!</li>';
		for (var i=0;i<currentLength;i++)
		{
			var setclass 	 = '';
			if (((i+1)%3)==0) {setclass = 'last'; }
			else { setclass = ''; }
			
			//#playlist/'+playlist[i].id+'
			
			playListHTml +='<li class="'+setclass+'"><div class="round-control"> <a class="btn dark inner-btn" href="javascript: AddNewSongToPlaylist(\''+playlist[i].id+'\',\'Playlist\');"><i class="icon-play"></i> Play Songs</a> <a class="btn dark inner-btn" href="javascript:;"><i class="icon-cog"  style="float:left;"></i> Edit Playlist</a> <a class="btn dark inner-btn" href="javascript:;" onclick="deletePlaylist('+ playlist[i].id +')"><i class="icon-trash"></i> Delete Playlist</a> </div><div class="album-box-round"> <a href="javascript:;" ><img onerror="$(this).attr(\'src\',\'images/default-img/01.png\');"  src="'+canvas_url+'files/albums/'+playlist[i].cover_photo+'" style="min-height:200px"  /></a><h3>'+playlist[i].title+'<span><a href="#playlist/'+playlist[i].id+'" >( '+playlist[i].song_count+' Songs)</a></span></h3></div></li>';
			//console.log(canvas_url+'files/playlist/'+playlist[i].cover_photo);

		
		}//$("#play-tab1 .content.group")
		$(".loadmore").attr('onClick','$("#loadmore_spinner").show(); getUserPlaylistListing('+(page+1)+'); ');
					
		if(totalrecords>currentLength)
		{
			$(".loadmore").show();
		}
		if (currentLength<limit)
		{
			$(".loadmore").hide();
		}

		// $("#container_left_spinner1").hide();
		if(page==1)
			$("#play-tab1 .content.group ul").html(playListHTml);
		else
			$("#play-tab1 .content.group ul").append(playListHTml);
			
			
			
		// $("#play-tab1 .content.group").show();
$("#small-spin-main").hide();
		$("#user_playlist_count").text(totalrecords);
	},"json");
	
}



function get_user_cart(page)
{

	$("#small-spin-main").show();
	if (page==0) page=1;
	var limit		= 100;
	var start_limit	= (page-1)*limit;
		start_limit	= (start_limit<0) ? 0 : start_limit;
	
	var api_url = 'cart';

	$.get(API_URL+api_url, {offset:start_limit, limit:limit, uid:uid}, function(data){

		var cartHTml='';
		var totalrecords 	= data.totalrecords;
		var cartItems 		= data.records;
		var currentLength   = playlist.length;
		var setclass 	= '';
	

		if(currentLength == 0 ) cartHTml='<li style="height: 41px; width:100%" class="error">No Playlist Found!</li>';
		for (var i=0;i<currentLength;i++)
		{
			var setclass 	 = '';
			if (((i+1)%3)==0) {setclass = 'last'; }
			else { setclass = ''; }
			
			//#playlist/'+playlist[i].id+'
			
			cartHTml +='<li class="'+setclass+'"><div class="round-control"> <a class="btn dark inner-btn" href="javascript:;"><i class="icon-play"></i> Play Songs</a> <a class="btn dark inner-btn" href="javascript:;"><i class="icon-cog"  style="float:left;"></i> Edit Playlist</a> <a class="btn dark inner-btn" href="javascript:;" onclick="deletePlaylist('+ playlist[i].id +')"><i class="icon-trash"></i> Delete Playlist</a> </div><div class="album-box-round"> <a href="javascript:;"><img onerror="$(this).attr(\'src\',\'images/default-img/01.png\');"  src="'+canvas_url+'files/playlist/'+playlist[i].cover_photo+'" /></a><h3>'+playlist[i].title+'<span><a href="#playlist/'+playlist[i].id+'" >( '+playlist[i].song_count+' Songs)</a></span></h3></div></li>';
		
		}

		$("#play-tab1 .content.group ul").append(cartHTml);
	
		$("#small-spin-main").hide();
		$("#user_playlist_count").text(totalrecords);
	},"json");
}

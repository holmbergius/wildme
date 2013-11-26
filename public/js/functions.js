$(document).ready(function(){ 
	var check_src = ''; 
	check_src = $('#fb-user-pic').attr('src');
	if(check_src == '' || check_src =='http://graph.facebook.com//picture?width=61&height=61')
	{
		$('#fb-user-pic').attr('src', 'http://graph.facebook.com/'+uid+'/picture?width=61&height=61');
	}
	if(sPage == '' || sPage =='home' )
	{
		/*FB.Canvas.setAutoGrow();
		getAnimalCategory(1);
		getPopularActive('follow_count',1);
		getPopularActive('encounter_count',2);
		getUserFriends(uid,0,4,0);*/
	}
	
	if (sPage == 'about-us')
	{
		/*FB.Canvas.setSize({ height: 920 });
		getAnimalCategory(1);*/
	
	}
	if (sPage == 'browse')
	{
		/*FB.Canvas.setAutoGrow();
		getUserFriends(uid,0,4,0);
		getAnimalCategory(0);
		getAnimalCategorySlider();
		getBrowseAnimal(0);*/
	
	}
	
	if(sPage =='profile' )
	{
		/*FB.Canvas.setAutoGrow();
		getAnimalCategory(0);
		response	=	GetUrlParms('id');
		getAnimalDetail(response);
		getRecentEncounters(0);
		getAnimalPhotos(0);*/
		
		//getAnimalPlaces(response);
	}if(sPage == 'user-page')
	{
		/*FB.Canvas.setAutoGrow();
		getUserFriends(uid,0,4,0);
		getAnimalCategory(0);
		//getPopularActive('follow_count',3);
		getUserFollowes('id',0,5,0);
		getUserLog(0);*/
	}
});	
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


function SearchEncounters(e)
{
	if (window.event) { e = window.event; }
   if ((e.keyCode == 13) || (e.type == 'click'))
   {
//action
		keyWord	=	$('#keyword').val();
		catType	=	$("[name='category']").val();
		
		window.location	=	canvas_url+'browse?type='+catType+"&keyword="+keyWord;
		
		//getRecentEncounters(0);
   }
}

function SearchBrowseAnimal(e)
{
	if (window.event) { e = window.event; }
   if ((e.keyCode == 13) || (e.type == 'click'))
   {
//action
		getBrowseAnimal(0);
   }
}

function FBLogin()
{
	if (server!='localhost')
	{
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
			  
			  
			  //facebook_id	=	userId;
			  //console.log(resp);
			  //console.log(userId + userName + birthday + email +  gender + access_token);
			 // console.log(resp); return false;
			 // var pact_id='';
			$.get(canvas_url+"api/login",  {id: userId, name: userName, email: userEmail,  accessToken:  access_token, age: birthday, gender: UserGender}, function(data){
				 
				 //console.log(data);
				 if(data.status === 'success' || data.status === 'success2')
				 	uid	=	userId;
					//window.parent.location	=	'http://apps.facebook.com/wildmeapp/';
			  }, "json" );
				
			  });
		}
		if(response.status === "not_authorized")
		{
			top.location.href	=	APP_URL;
		}
	}, {scope: 'email' }); //publish_stream
	}
}



function FBLoginStatus()
{
	FB.getLoginStatus(function(response) {
			console.log(response);
         if (response.status === 'connected') 
		 {
            var	userID	=	response.authResponse.userID;
			
			console.log(userID);
			
			$.get(canvas_url+"api/existuser",  {id: userID}, function(data){
				 
				 //console.log(data);
				 if(data.status === 'success')
					window.parent.location	=	'http://apps.facebook.com/wildmeapp/';
				 else
				 	FBLogin();
			  }, "json" );
			
         }
     });
}

function getAnimalCategory(homeCheck)
{
	var cat_type	=	GetUrlParms('type');
	$.get(canvas_url+"api/category",function(data){
				 //console.log(data);
				 if(data.status === 'success')
				 {
					 var CreateDropDown	=	'';
					 var selected	=	'';
					 //CreateDropDown	+=	"<select name='category' class='span3 select-block' >";
					 
					 $.each(data.records,function(index,val)
					 {
						 //console.log(val);
						 selected	=	'';
						 if(val.id === cat_type)
						 {
						 	selected = "selected = 'selected'";
						 }
						 
						 CreateDropDown +=	"<option "+ selected +" value="+ val.id+" >"+ val.title+"</option>";
					 });
					 
					 //CreateDropDown	+=	"</select>";
					 
					 $('#category').html(CreateDropDown);
					 
				 }
				 if(homeCheck == 1)
				 {
					 getRecentEncounters(0);
				 }
			  }, "json" );
}

function getRecentEncounters(page)
{
	if (page==0) page=1;
	var limit		= 8;
	var start_limit	= (page-1)*limit;
		start_limit	= (start_limit<0) ? 0 : start_limit;
		
	$('#spinner-act').show();

	var animal_id = sPage;
	var animal_list	=	1;
	if(animal_id =="home" || animal_id =="browse" || animal_id =="")
		animal_id	=	"";
	else 
		animal_list	=	"";
		
	var category_id 	= '';	
	//category_id 		= $('#category').val();

	var keyword 		= '';	
	//keyword 			= $('#keyword').val();
	
	$.get(canvas_url+"api/encounter",{limit:limit, offset: start_limit, animal_list:animal_list, media_offset: 0,animal_id:animal_id, category_id:category_id, keyword:keyword, sortby:'date_added', orderby:'desc',user_id:uid },function(data){
				 //console.log(data.totalrecords);
				 var current_records = data.records.length
				 var total_records	 = data.totalrecords;
				 if(data.status === 'success')
				 {
					 $('#spinner-act').hide();
					 $("#loadmore-spinner").hide();
					 var CreateList	=	'';
					 
				
					 $("#all-act").text(total_records);
					 
					 facebook_id	=	$('#input_fb_id').val();
					 if(facebook_id == '')
					 {
						 facebook_id = uid;
					 }
					 
					 $.each(data.records,function(index,val)
					 {
						 
						 nickName	=	(val.nick_name)? val.nick_name:'';
						 photoGrapher	=	val.photographer_name;
						 id			=	val.id;
						 animalId	=	val.animal_id;
						 recordedBy	=	val.recorded_by;
						 verbatimLocality	=	val.verbatim_locality;
						 verbatimLocality	=	val.verbatim_locality;
						 gender	=	val.gender;
						 latitude	=	'';
						 longitude	=	'';
						 categoryIcon	=	val.category_icon;
						 categoryColor	=	val.category_color;
						 categoryTitle	=	val.category_title;
						 mediaDetail	=	'';
						 popUp	=	'';
						 mapPopUp	=	'';
						 mapIframe	=	'';
						 popUpImages	=	'';
						 map	=	'';
						 desc_name	=	(nickName == "")?animalId:nickName;
						 mediaImageArr = new Array();
						 var new_time       =  jQuery.timeago(val.date_added);
						 var shareImage	=	"";
						 var is_like	=	'';
						 var spanCss	=	"";
						 
						 if(val.is_like	==	1)
						 	is_like	=	"selected";
						else
							is_like	=	"";
						 
						 
						 if(val.media_count >0)
						 {
							 
						
							 mediaDetail	+=	"<div class='pics-videos group'><ul>";
							 
							 $.each(val.media_details,function(media_index,media_val){
							 	
								//console.log(media_index);
								
								mediaImageArr[media_index]	=	canvas_url+media_val.thumb_path;
								
								popUpImages	+=	"<li><img src='"+media_val.image_name+"' /></li>";
								
								mediaDetail	+=	"<li onclick='popupById("+val.id+", "+media_index+");' id='imgLi"+val.id+media_index+"'><img src='"+canvas_url+media_val.thumb_path+"' onerror=\"$('#imgLi"+val.id+media_index+"').hide();\" style='width:120px; height:80px' /></li>";
								
							 });
							 
							 //console.log(mediaImageArr[0]);
							 
							 mediaDetail	+=	"</ul></div>";
							 
							 shareImage	=	mediaImageArr[0];
							 //console.log(mediaDetail);
						 }
						 else	
						 	shareImage	=	canvas_url+"images/dummy-friends.jpg";
						 
						 //FBShare	=	'SharePhoto("'+shareImage+'" , "'+desc_name+'" , "'+APP_URL+'","'+id+'","encounter", "'+verbatimLocality+'" ,"'+gender+'" ,"'+photoGrapher+'","'+recordedBy+'", "'+categoryTitle+'", "'+nickName+'", "'+animalId+'" );';
						 FBShare	=	'SharePhoto("'+shareImage+'" , "'+desc_name+'" , "http://fb.wildme.org/wildme/public/profile/'+animalId+'","'+id+'","encounter", "'+verbatimLocality+'" ,"'+gender+'" ,"'+photoGrapher+'","'+recordedBy+'", "'+categoryTitle+'", "'+nickName+'", "'+animalId+'" );';
						 						 
						 
						 
						 if(val.latitude && val.longitude)
						 {
							 latitude	=	val.latitude;
							 longitude	=	val.longitude;
							 
							 
							 mapIframe	=	"<iframe src="+canvas_url+"map.php?lat="+latitude+"&long="+longitude+"&title=A-001 seen at Bihar River' scrolling='no' frameborder='0' width='454' height='250'></iframe>";						 
							 
							 mapPopUp	=	"<div class='popup-inner map-poup' id='mapPop_"+val.id+"' style='display:none;position:absolute;left:80%;top:-1%'> <div class='popup-inner-content'><h3><a href='"+canvas_url+"profile/"+animalId+"'>"+nickName+"  ("+animalId+")</a> at "+verbatimLocality+"<span class='hide-map-poup'><i class='icon-remove'></i></span></h3> <div class='map'>"+mapIframe+"</div> </div> </div>";
							 
							 map	=	"<li>"+mapPopUp+"<div class='comment-counter'><div class='icon-bg show-map-poup' onclick='mapPopupById("+val.id+");'><i class='icon-map-marker'></i></div></div> </li>";
						 }
						 
						 
						 
						 popUp = "<div class='popup-inner pic-poup' id='pop_"+val.id+"' style='display:none;position:absolute;left:80%;top:-1%'><div class='popup-inner-content' style='position:relative;'><h3><a href='"+canvas_url+"profile/"+animalId+"'>"+nickName+"   ("+animalId+")</a> at "+verbatimLocality+"<span class='hide-pic-poup'><i class='icon-remove'></i></span></h3><div class='slider-image scrollable1' id='scrollable"+val.id+"'><ul class='items'>"+popUpImages+"</ul> </div> <div class='slider-pic-left-arrow prev'><i class='icon-angle-left'></i></div> <div class='slider-pic-right-arrow next'><i class='icon-angle-right' ></i></div> </div> </div>";
						 // image onclick='window.location = \""+canvas_url+"profile/"+animalId+"\"'
						 CreateList	+=	"<li id='enc_"+val.id+"'>";
						 CreateList += popUp;
						 CreateList += mapPopUp;
						 CreateList	+=	"<div class='pic'><div class='align-div "+categoryColor+"'> <img src='"+canvas_url+categoryIcon+"' /> </div> </div>";
						 if(verbatimLocality != '')
						 {
							 var verbatimLocality_check = verbatimLocality.substr(verbatimLocality.length-1);
							 if (verbatimLocality_check ==='.')
							 	verbatimLocality	= verbatimLocality.slice(0,verbatimLocality.length-1);
								
					 		 verbatimLocality = ' near '+verbatimLocality;
						 }
						 if(photoGrapher != '') photoGrapher = photoGrapher +' has captured the photo';
						 if(recordedBy != '' && photoGrapher != '') recordedBy = ' and '+recordedBy +' reported the event.';
						 if(recordedBy != '' && photoGrapher == '') recordedBy = ''+recordedBy +' reported the event.';
						 if(nickName) spanCss	=	"style='margin-left:5px';";
						 
						 CreateList += "<div class='details group'><h4><a href='"+canvas_url+"profile/"+animalId+"'>"+nickName+"<span "+spanCss+" >("+animalId+")</span></a> <span class='small'>- "+new_time+"</span></h4><p>"+categoryTitle+", "+gender+" has been seen <span><a style='cursor:default;'>"+verbatimLocality+".</a></span> "+photoGrapher+""+recordedBy+"</p>"+mediaDetail+"  <div class='social-counter group'> <ul><li><div class='comment-counter' onclick='incrementEncounterLike("+id+", "+facebook_id+");' id='"+id+"'> <div id='like_bg"+id+"' class='icon-bg "+is_like+"' ><i class='icon-thumbs-up' ></i></div> <div class='count-bg "+is_like+"' id='like_"+id+"'>"+val.like_count+"</div> </div> </li> <li> <div class='comment-counter' id='com_"+id+"' onclick='ListEncounterComments("+id+");'> <div class='icon-bg'><i class='icon-comments'></i></div><div class='count-bg' id='com_count_"+id+"'>"+val.comment_count+"</div> </div></li> <li> <div class='comment-counter' onclick='"+FBShare+"' > <div class='icon-bg'><i class='icon-share-alt'></i></div> <div class='count-bg' id='share_"+id+"'>"+val.share_count+"</div> </div> </li>"+map+" <li><div id='spinner-social_"+id+"' style='margin-top: 6px; position: absolute; display:none;'><img class='icon-spin' src='"+canvas_url+"images/sp-new.png'  /></div></li></ul> </div>   <div class='section-comments-area' id='comment_"+id+"'> </div>    </div>";
						 //console.log(index);
						 
						 CreateList += "</li>";
						 
						 //$('#enc_'+val.id).append(popUp);
						 
						// $(".icon-thumbs-up").attr('onClick',"incrementEncounterLike("+id+");");						 						
						
						 
					 });
					 
								
				 }
				 else
				 {
					 $('#spinner-act').hide();
					 $("#loadmore").hide();
					var norecord	=	"<li  style='font: 14px latolight,Arial;text-align:center; '>No Activites Found....</li>"
					 $('#listActivites').html(norecord)
				 }
				 
				$("#loadmore").attr('onClick','FB.Canvas.setAutoGrow(); $("#loadmore-spinner").show(); getRecentEncounters('+(page+1)+'); ');
				if(total_records>current_records)
				{
					$("#loadmore").show();
				}
				if (current_records<limit)
				{
					$("#loadmore").hide();
				}
				
				if(page == 1)
				{
					$('#listActivites').html(CreateList);
				}
				else
				{
					$('#listActivites').append(CreateList);
				}	
				 
				 $(".hide-pic-poup").click(function(){
						$(".pic-poup").hide("drop", {direction : "up" }, 400);
					});
					
				$(".hide-map-poup").click(function(){
					$(".map-poup").hide("drop", {direction : "up" }, 400);
				});
				  
			  }, "json" );
	
}

function getPopularActive(sortBy,tabId)
{
	var page=1;
	var limit		= 5;
	if(tabId == 3){limit = 3; tabId = 1}
	
	var userId	=	$('#input_fb_id').val();
	if(userId == '')
	{
		 userId = uid;
	}
	var start_limit	= (page-1)*limit;
		start_limit	= (start_limit<0) ? 0 : start_limit;
	$('#spinner-wild').show();
	
	$.get(canvas_url+"api/animal",{limit:limit, offset: page, sortby: sortBy, orderby: 'DESC',user_id:userId },
	function(data){
		//console.log(data.status);
		
		if(data.status === 'success')
		{
				$('#spinner-wild').hide();
				 var CreateList	=	'';
				 $.each(data.records,function(index,val)
				 {
					 //console.log(val);
					 nickName	=	(val.nick_name)? val.nick_name:'';
					 id			=	val.id;
					 gender		=	val.sex;
					 categoryIcon	=	val.category_icon;
					 categoryColor	=	val.category_color;
					 categoryTitle	=	val.category_title;
					 classFirst	=	"";
					 classFollow	=	"";
					 followMethod	=	'animalFollowById("'+id+'");';
					 spanCss	=	"";
					 var follow_text = 'FOLLOW';
					 
					 if(index === 0)
					 	classFirst = "class= 'first'";
					
					if(val.follow_check === 1)
					{
					 	classFollow = "selected";
						followMethod	=	'animalUnFollowById("'+id+'");';
						follow_text = 'FOLLOWING';
					}
					
					if(nickName) spanCss	=	"style='margin-left:5px';";
				
					 CreateList += "<li "+classFirst+"><div class='pic'> <div class='align-div "+categoryColor+"'> <img src='"+canvas_url+categoryIcon+"' /> </div></div> <div class='listing-details'> <h4><a href='"+canvas_url+"profile/"+id+"'>"+nickName+"<span "+spanCss+">("+id+")</span></a></h4> <p>"+categoryTitle+", "+gender+"</p> <div class='list-comment-counter' id='foll_"+id+"' onclick='"+followMethod+"'> <div class='list-comment-counters-inner "+classFollow+"' id='foll_class_"+id+"'> <span>-</span> "+follow_text+"</div> <div class='list-comment-count-bg "+classFollow+"' id='follow_"+id+"'>"+val.follow_count+"</div> </div></div> <i class='icon-angle-right'  onclick='window.location = \""+canvas_url+"profile/"+id+" \"'></i></li>";
				 });
				 //
				 $('#tab'+tabId).append(CreateList);
		}
	},"json");
	
}




function getUserFollowes(sortBy,page,limit,is_popup)
{
	var page	=	(page == 0)?1:page;
	var limit		= limit;
	tabId = 1;
	var profileUserId	=	GetUrlParms('id');
	var userId	=	$('#input_fb_id').val();
	if(userId	== "")
		userId	=	uid;
	var start_limit	= (page-1)*limit;
		start_limit	= (start_limit<0) ? 0 : start_limit;
		
	$('#spinner-wild').show();
	$.get(canvas_url+"api/user_followers",{limit:limit, offset: start_limit, sortby: sortBy, orderby: 'DESC',user_id:userId,profile_user_id:profileUserId },
	function(data){
		//console.log(data.status);
		var CreateList	=	'';
		var popupList	=	'';
		var totalRecords	=	data.totalrecords;
		var currentRecords	=	data.records.length;
		if(data.status === 'success')
		{
				$('#spinner-wild').hide();
				$("#loadmore-popup-spinner").hide()
				
				 $.each(data.records,function(index,val)
				 {
					 //console.log(val);
					 nickName	=	(val.nick_name)? val.nick_name:'';
					 id			=	val.id;
					 gender		=	val.sex;
					 categoryIcon	=	val.category_detail.icon;
					 categoryColor	=	val.category_detail.color_code;
					 categoryTitle	=	val.category_detail.title;
					 classFirst	=	"";
					 classFollow	=	"";
					 followMethod	=	'animalFollowById("'+id+'");';
					 spanCss	=	"";
					 follow_text	= 'FOLLOW';
					 
					 if(index === 0)
					 	classFirst = "class= 'first'";
						
					if(val.follow_check === 1)
					{
					 	classFollow = "selected";
						followMethod	=	'animalUnFollowById("'+id+'");';
						follow_text	= 'FOLLOWING';
					}
					
					if(nickName) spanCss	=	"style='margin-left:5px';";
					
					
					
					if(is_popup == 1)
					{
						popupList	+=	"<li "+classFirst+"><div class='pic'> <div class='align-div "+categoryColor+"'> <img src='"+canvas_url+categoryIcon+"' /> </div></div> <div class='listing-details'> <h4><a href='"+canvas_url+"profile/"+id+"'>"+nickName+"<span "+spanCss+">("+id+")</span></a></h4> <p>"+categoryTitle+", "+gender+"</p> <div class='list-comment-counter' id='foll_"+id+"' onclick='"+followMethod+"'> <div class='list-comment-counters-inner "+classFollow+"' id='foll_class_"+id+"'> <span>-</span> FOLLOW</div> <div class='list-comment-count-bg "+classFollow+"' id='follow_"+id+"'>"+val.follow_count+"</div> </div></div> </li>";
					}
				
					 CreateList += "<li "+classFirst+"><div class='pic'> <div class='align-div "+categoryColor+"'> <img src='"+canvas_url+categoryIcon+"' /> </div></div> <div class='listing-details'> <h4><a href='"+canvas_url+"profile/"+id+"'>"+nickName+"<span "+spanCss+" >("+id+")</span></a></h4> <p>"+categoryTitle+", "+gender+"</p> <div class='list-comment-counter' id='foll_"+id+"' onclick='"+followMethod+"'> <div class='list-comment-counters-inner "+classFollow+"' id='foll_class_"+id+"'> <span>-</span> "+follow_text+"</div> <div class='list-comment-count-bg "+classFollow+"' id='follow_"+id+"'>"+val.follow_count+"</div> </div></div> <i class='icon-angle-right' onclick='window.location = \""+canvas_url+"profile/"+id+"\"' ></i></li>";
				 });
				 //
				 
				 if(is_popup == 1)
				 {
					 if(page == 1)
						$('#popup-list').html(popupList);
						
					else 
				 		$('#popup-list').append(popupList);
					
					
					popupHearder	=	'All Wild Life <span class="hide-activites-poup" id="hide-activites-poup"><i class="icon-remove"></i></span>';
					$('#popup-title').html(popupHearder);
					$('#popup-inner').show();
					
					$("#hide-activites-poup").click(function(){
						$("#popup-inner").hide("drop", {direction : "up" }, 400);
					});
					
					$('.scrollbar3').tinyscrollbar();
					
				 }
				 else
				 	$('#tab'+tabId).append(CreateList);
		}
		else
		{
			CreateList += "<li class= 'first' style='font: 14px latolight,Arial;text-align:center;'>No Records Found...</li>";
			
			if(is_popup != 1)
			{
				$('#tab'+tabId).html(CreateList);
			}
			
		}
		
		
		$("#loadmore_popup").attr('onClick','$("#loadmore-popup-spinner").show(); getUserFollowes("id",'+(page+1)+',10,1); ');
		 
		 if(totalRecords>currentRecords)
		{
			$("#loadmore_popup").show();
		}
		if (currentRecords<limit)
		{
			$("#loadmore_popup").hide();
		}
		
		if(is_popup !=1)
		{
			if(totalRecords > 5)
			{
				$('#view_all').show();
			}
		}
		
	},"json");
	
}



//getBrowseAnimal(0);
function getBrowseAnimal(page)
{
	/*var cat_type = GetUrlParms('type');
	if(cat_type != '')
	{
		$('#category').val(cat_type);
		category_id = cat_type;
	}*/
	if(page == 0) $('#loadmore_browse').hide();
	$('#spinner-list').show();
	
	if (page==0){ page=1;  $('#tab3').html('');};
	var limit		= 15;
	var start_limit	= (page-1)*limit;
		start_limit	= (start_limit<0) ? 0 : start_limit;
	var sortBy 		= $('#sortby').val();
	if(sortBy =='')
	{
		sortby = 'id';
	}
	$('#spinner-wild').show();
	var category_id = $('select#category').val();
	if(category_id == null || category_id == '')
	{
		category_id = GetUrlParms('type');
		
		if(category_id == null || category_id == '')
		{
			category_id = 1;
		}
	}
		
	if (category_id!='')
	{
		$('div.items ul li').removeClass('selected');
		$('#cat_type_'+category_id).addClass('selected');
	}
	
	var keyword =	'';
	keyword 	= $('#keyword').val();
	
	$.get(canvas_url+"api/animal",{limit:limit, offset: start_limit, sortby: sortBy, orderby: 'DESC', category_id:category_id, keyword: keyword , user_id: uid},
	function(data){
		//console.log(data.status);
		var CreateList	=	'';
		var total_records = data.totalrecords;
		var current_records = data.records.length;
				 
		if(data.status === 'success')
		{
				$('#spinner-wild').hide();
				 
				 
				 $.each(data.records,function(index,val)
				 {
					 var a_size;
					 //console.log(val);
					 var nickName	=	(val.nick_name)? val.nick_name:'';
					 var id			=	val.id;
					 var gender		=	val.sex;
					 var categoryIcon	=	val.category_icon;
					 var categoryColor	=	val.category_color;
					 var categoryTitle	=	val.category_title;
					 var classFirst	=	"";
					 var selected = "";
					 var onclick = "";
					 var spanCss	=	"";
					 var follow_text = '';
					 if(index === 0)
					 	classFirst = 'class="first"';
					if(val.size !='')
					{
						a_size = ''+val.size+' Meters';
					}
					else
					{
						a_size = 'unknown';
					}
					
					if (parseInt(val.follow_check)>0)
					{
						selected = "selected";
						follow_text = "FOLLOWING";
						
					}
					else
					{
						onclick = 'onclick="animalFollowById(\''+id+'\');"';
						follow_text = "FOLLOW";
					}
					
					if(nickName) spanCss	=	"style='margin-left:5px';";
					
					if (gender=='') gender ='unknown';
					
					 CreateList += '<li '+classFirst+'><div class="pic"><div class="align-div  '+categoryColor+'"> <img src="'+canvas_url+categoryIcon+'" /> </div></div><div class="listing-details"> <h4><a href="'+canvas_url+'profile/'+id+'">'+nickName+' <span '+spanCss+'>('+id+')</span></a></h4><ul><li><span>Gender:</span> '+gender+'</li><li><span>Activites:</span> '+val.encounter_count+'</li><li> <span>Friends:</span> '+val.friend_count+'</li></ul><div class="list-comment-counter" id="foll_'+id+'" '+onclick+'><div class="list-comment-counters-inner '+selected+'" id="foll_class_'+id+'"> <span>-</span> '+follow_text+'</div><div class="list-comment-count-bg '+selected+'" id="follow_'+id+'">'+val.follow_count+'</div></div></div><i  class="icon-angle-right" onclick="window.location = \''+canvas_url+'profile/'+id+'\'" ></i> </li>';
				//
				 });
				 
				 
		}
		else
		{
			CreateList += '<li class="first" style=" font: 20px latolight,Arial; text-align:center"> No Animals Found! </li>'
		}
		if(page == 1)
		 {
			 $('#tab3').html(CreateList);
		 }
		 else
		 {
			 $('#tab3').append(CreateList);
		 }
		 
		 $("#loadmore_browse").attr('onClick','FB.Canvas.setAutoGrow(); $("#loadmore-spinner_b").show(); getBrowseAnimal('+(page+1)+'); ');
		 
		 if(total_records>current_records)
		{
			$("#loadmore_browse").show();
		}
		if (current_records<limit)
		{
			$("#loadmore_browse").hide();
		}
		$("#loadmore-spinner_b").hide();
		
		$('#spinner-list').hide();
		
		if(category_id == null)
		{
			category_id = 1;
		}
		var cat_title = $("#cat_title_"+category_id).html();
		if (typeof cat_title =='undefined') cat_title = 'Whale Shark';
		$("#cat_type").html(cat_title+' ('+total_records+')');
				
	},"json");
	
	
}



function incrementEncounterLike(id,fb_id)
{
	//console.log(id+" "+ fb_id);
	
	//console.log($('#like_'+id).text());
	
	fb_id	=	uid;
	
	$('#like_bg'+id).addClass('selected');
	$('#like_'+id).addClass('selected');
	
	var prevLikeCount	=	parseInt($('#like_'+id).text());
	
	$.get(canvas_url+"api/likeincrement",{id:id, user_id: fb_id},function(data){
		
		//console.log(data);
		
		if(data.status === 'success')
		{
			$('#like_'+id).html(prevLikeCount+1);
		}
		else
		{
			
		}
		
		
	}, "json");
	
	//$('#'+id).attr(
}

function popupById(id, to_index)
{
	$(".pic-poup").hide("drop", {direction : "up" }, 400);
	$("#pop_"+id).show("drop", {direction : "up" }, 400);
	$("#scrollable"+id).scrollable({circular: true});
		
	var api = $("#scrollable"+id).data("scrollable");
    api.seekTo(to_index, 1000);
}


function mapPopupById(id)
{
	$(".map-poup").hide("drop", {direction : "up" }, 400);
	$("#mapPop_"+id).show("drop", {direction : "up" }, 400);
		
}


//testing share photo...
function SharePhoto(img_path, desc_name, url, id,type, en_loca, ani_gender, en_pg, en_rb, category, nick_name, animal_id )
{
	loca_text = '';
	if(en_loca!= '' )
	{
		loca_text = ' has been seen near '+loca_text;
	}
	pg_text = '';
	if(en_pg!= '' )
	{
		pg_text = en_pg+' has captured the photo ';
	}
	rb_text = '';
	if(en_rb!= '' )
	{
		if(en_pg != '')
		rb_text = 'and '+en_rb+' has captured the event.';
		else
		rb_text = en_rb+' has captured the event.';
	}
 	//console.log(canvas_url+img_path);
	var message =	"";
   
   var desc	=	"";
   
   if(type == "animal")
   {
	   desc	=	"Checkout "+desc_name+" activities on WildMe and explore more animals and find out what they are up to.. A fun and interactive way to follow animals..";
	   message = "Checkout "+category+" "+desc_name+" on Wild Me! Add an animal to your social network!";
   }
   else{
   	desc	=	"Your friend "+nick_name+"  ("+animal_id+"), the "+category+", has been sighted!"+pg_text+" "+rb_text+" ";
	message = ""+nick_name+" ("+animal_id+"): Activity has been reported on Wild Me!";
   }
   var obj = {
    method: 'feed',
    link : url,    
    picture: img_path,
    name: message,
    description: desc
     }; 
    
   FB.ui(obj, function(response){
   	
   	//console.log(response);
	
	if(response.post_id)
	{
		str	=	response.post_id.split('_');
		userId	=	str[0];
		
		
		$.get(canvas_url+"api/shareincrement",{id:id, type: type,user_id:userId,animal_id:animal_id},function(data){
		
			//console.log(data);
			
			var prevShareCount	=	parseInt($('#share_'+id).text());
			
			if(data.status === 'success')
			{
				$('#share_'+id).html(prevShareCount+1);
			}

		}, "json");
		
		
	}
	
	
	
   });

}

function animalFollowById(id)
{
	userId	=	$('#input_fb_id').val();
	
	if(userId	== "")
		userId	=	uid;
	
	$('#foll_'+id).attr('onclick','animalUnFollowById("'+id+'")');
	
	$('#foll_class_'+id).addClass('selected');
	$('#follow_'+id).addClass('selected');
	
	$.post(canvas_url+"api/follow",{user_id:userId, animal_id:id},function(data){
		
			//console.log(data);
			
			var prevFollowCount	=	parseInt($('#follow_'+id).text());
			
			if(data.status === 'success')
			{
				$('#follow_'+id).html(prevFollowCount+1);
			}

		}, "json");
	
	/*$.get(canvas_url+"api/followincrement",{id:id},function(data){
		
			//console.log(data);
			
			var prevFollowCount	=	parseInt($('#follow_'+id).text());
			
			if(data.status === 'success')
			{
				$('#follow_'+id).html(prevFollowCount+1);
			}

		}, "json");*/
		
}

function animalUnFollowById(id)
{
	userId	=	$('#input_fb_id').val();
	
	if(userId	== "")
		userId	=	uid;
	
	$('#foll_'+id).attr('onclick','animalFollowById("'+id+'")');
	
	$('#foll_class_'+id).removeClass('selected');
	$('#follow_'+id).removeClass('selected');
	
	
	  $.ajax({
		dataType: "json",
		url: canvas_url+"api/follow",
		data:{user_id:userId, animal_id:id},
		type: 'DELETE',
		success: function(response) {
		 
			 var status = response;
			 var prevFollowCount	=	parseInt($('#follow_'+id).text());
		 
			 if(status.status == 'success')
			 {
			 
				 $('#follow_'+id).html(prevFollowCount-1);
			 }
			 else
			 {
			 
			 }
		  }
	  	});
	
	
	/*$.get(canvas_url+"api/followincrement",{id:id},function(data){
		
			//console.log(data);
			
			var prevFollowCount	=	parseInt($('#follow_'+id).text());
			
			if(data.status === 'success')
			{
				$('#follow_'+id).html(prevFollowCount+1);
			}

		}, "json");*/
		
}

function ListEncounterComments(id)
{
	$('#com_'+id).attr('onclick','').unbind('click');
	$('#spinner-social_'+id).show();
	
	var current_userId	=	$('#input_fb_id').val();
	
	if(current_userId	== "")
		current_userId	=	uid;
		
	current_img	=	"http://graph.facebook.com/"+current_userId+"/picture?width=40&height=40";
	commentList	=	'';
	comment_id	=	"comment_"+id;
	
	$('#com_'+id).attr('onclick',"$('#comment_"+id+"').toggle()");
	commentList +="<i class='icon-caret-up'></i>";
	
	$.get(canvas_url+"api/comment",{encounter_id:id},function(data){
			
				
			if(data.status === 'success')
			{
				$('#spinner-social_'+id).hide();
				var current_userId	=	$('#input_fb_id').val();
				
				if(current_userId	== "")
					current_userId	=	uid;
				
				
				$.each(data.records,function(index,val){
					//console.log(val);
					userId	=	val.user_id;
					user_img	=	"http://graph.facebook.com/"+userId+"/picture?width=40&height=40";
					var new_time       =  jQuery.timeago(val.date_added);
					
				commentList +=	"<div class='view-more-comments'><div class='pic40'><a href='"+canvas_url+"user-page?id="+userId+"'><img src='"+user_img+"' /></a></div> <div class='comment-row'> <h4><a href='"+canvas_url+"user-page?id="+userId+"'>"+val.user_name+"</a> <span>- "+new_time+"</span></h4>  <p>"+val.message+"</p> </div> </div>";					
					
					
				});
				
				commentList +="<div class='view-more-comments txt-area-comment_"+id+"'><div class='pic40'><img src='"+current_img+"'></div>  <div class='comment-row'>  <h4> <textarea onkeypress='enterComment(event,"+id+");' id='"+comment_id+"' name='"+comment_id+"' cols='' rows='1' placeholder='Write your comment'></textarea> </h4>  </div>  </div>";
				
				$('#comment_'+id).append(commentList);
			}
			
			if(data.status === 'error')
			{
				$('#spinner-social_'+id).hide();
				commentList +="<div class='view-more-comments txt-area-comment_"+id+"'><div class='pic40'><img src='"+current_img+"'></div>  <div class='comment-row'>  <h4> <textarea onkeypress='enterComment(event,"+id+");' id='"+comment_id+"' name='"+comment_id+"' cols='' rows='1' placeholder='Write your comment'></textarea> </h4>  </div>  </div>";
				
				$('#comment_'+id).append(commentList);
			}

		}, "json");
		
		
}


function enterComment(e,id)
{
	if (window.event) { e = window.event; }
   if ((e.keyCode == 13) )
   {
//action
		postComment(id);
   }
}


function postComment(id)
{
	var current_userId	=	$('#input_fb_id').val();
	
	if(current_userId == "")
		current_userId	=	uid;
	
	var comment	=	$("[name='comment_"+id+"']").val();
	var current_userId	=	$('#input_fb_id').val();
	
	if(current_userId == "")
		current_userId	=	uid;
	
	var userName	=	"";
	current_img	=	"http://graph.facebook.com/"+current_userId+"/picture?width=40&height=40";
	userName	=	user_name;
	
	/*FB.api('/me',function(resp){
		userName	=	resp.name;
	});*/
	
	$("[name='comment_"+id+"']").val('');
	$("[name='comment_"+id+"']").blur();
	var prevCommentCount	=	parseInt($('#com_count_'+id).text());

	//console.log(id);
	
	
	//console.log(comment);
	
	$.post(canvas_url+"api/comment",{encounter_id:id,user_id:current_userId,message:comment},function(data){
		
		if(data.status	=== 'success')
		{
				
			var	commentList	=	"";
			var now = new Date();
			curr_month	=	now.getMonth()+1;
	
			now	=	now.getFullYear() + '-' + curr_month + '-' + now.getDate() + ' ' + now.getHours() + ':' + now.getMinutes() + ':' + now.getSeconds();
			
			var new_time       =  jQuery.timeago(now);
			
			commentList +=	"<div class='view-more-comments'><div class='pic40'><img src='"+current_img+"'></div> <div class='comment-row'> <h4>"+userName+" <span>- "+new_time+"</span></h4>  <p>"+comment+"</p> </div> </div>";		
			
			$(commentList).insertBefore('.txt-area-comment_'+id);
			
			$('#com_count_'+id).html(prevCommentCount+1);
			
			//ListEncounterComments(id);
		}
		
		
	},"json");
}


function InviteFriends()
{
	
	FB.ui({
    display: 'iframe',
    method: 'apprequests',
    new_style_message: true,
    title: "Invite your friends for WILD ME",
    message: "Join Wild Me and bring the amazing world of wildlife into your social network! Do you want to friend a real bear or a shark? Follow individual animals under study by researchers and learn more about their amazing lives with Wild Me! "
	}, function(response){
	
		//console.log(responce);
	});	
	
	/*$(".tooltipOptions").each(function(){
 	 $(".tooltipOptions").hide();
	 });
	 
	 
	FB.ui({
		method: 'apprequests',
		message: 'Pick the dream team Earn glory and win big prizes with the top notch players of your dream team.'
	  }, InviteResponse);	
	  */
	  
	  
}

function getAnimalDetail(id)
{
	//console.log(id);
	
	$('.profile-banner').css({ "background-color": 'brown'});
	
	$.get(canvas_url+"api/animal",{id:id, category_id:1,orderby:'DESC', user_id : uid},function(data){
			
				
			if(data.status === 'success')
			{
				 //console.log(data);
				 animalId	=	data.records[0].id;
				 nickName	=	(data.records[0].nick_name)? data.records[0].nick_name:'';
				 lenght		=	data.records[0].size;
				 gender		=	data.records[0].sex;
				 friendCount	=	data.records[0].friend_count
				 encountersCount		=	data.records[0].encounter_count;
				 categoryIcon	=	data.records[0].category_icon;
				 categoryColor	=	data.records[0].category_color;
				 categoryTitle	=	data.records[0].category_title;
				 spanCss	=	"";
				
				 animalProfile	=	'';
				 follow_text	=	'';
				 
				 
				 if(nickName) spanCss	=	"style='margin-left:5px';";
				 if (gender=='') gender ='unknown';
				
				animalProfile +="<div class='wild-profile'><div class='pic'>  <div class='align-div  "+categoryColor+"'> <img src='"+categoryIcon+"' /> </div>  </div>  <div class='profile-banner-list'>    <h4><a href='#'>"+nickName+" <span "+spanCss+">("+animalId+")</span></a></h4>  <ul> <li><span>Gender:</span> "+gender+"</li> <li><span>Activites:</span> "+encountersCount+"</li>  <li> <span>Friends:</span> "+friendCount+" </ul><div class='follow-share'> <div class='list-comment-follow'>  <div class='list-comment-counts-follow'> <span>-</span> FOLLOW</div> <div class='list-comment-count-bg-follow'>"+data.records[0].follow_count+"</div></div><div class='list-comment-follow'> <div class='list-comment-counts-follow'> <i class='icon-share-alt'></i> SHARE</div><div class='list-comment-count-bg-follow'>"+data.records[0].share_count+"</div></div> </div> </div></div>";
				
				
				$('.profile-banner').append(animalProfile);
				
				
				
			}
			
			if(data.status === 'error')
			{
				
			}

		}, "json");
}


function getAnimalLocation(id)
{
	$('#animal_title').text($('.profile-banner-list a').text());
	$('#animal_location').text($('#'+id).data('location'));
}

function getLocation()
{
	var api = $("#scrollable2").data("scrollable");
	var nxtObj	=	api.getItems().eq(api.getIndex());
	//console.log(nxtObj[0]);
	var locationE	=	nxtObj[0].outerHTML;
	var expLocation = locationE.split('"><img src="');
	var location  = expLocation[0];
	
	var locationF = location.replace('<li data-location="',"");
	$("#animal_location").text(locationF);
	//console.log(locationF);
	//location = location.replace('>');
	//console.log(location);
	//console.log(location);
	
	
    //console.log(api.getItems().eq(api.getIndex()));
}

var photoPopupTop = 300;
function getAnimalPhotos(page)
{
	$(document).ready(function() {
		$("#loadmore-spinner-photos").show();        
		
		var photoList	=	'';
		animalId	=	sPage;
		var photoPopUp	=	'';
		if (page==0) page=1;
		var limit		= 25;
		var start_limit	= (page-1)*limit;
			start_limit	= (start_limit<0) ? 0 : start_limit;
		
		$.get(canvas_url+"api/animal_media",{limit:limit, offset: start_limit,animal_id:animalId },function(data){
				
				var current_records = data.records.length;
				var total_records	 = data.totalrecords;
				
				if(data.status === 'success')
				{
					$('#animal_title').text($('.profile-banner-list h4 a').contents().filter(function() {return this.nodeType == 3;}).text()+ ' '+$('.profile-banner-list h4 a span').text()+'');
					//console.log(data);
					$("#loadmore-spinner-photos").hide();
					
					 var i	=	1;
					
					
					$.each(data.records,function(index,val){
						
						//console.log(index);
						var liClass	=	'';
						var	animal_title = "";
						
						if(i == 5 )
						{
							liClass	=	"class=last";
							i =1;
							photoPopupTop += 100;
						}
						
						photoPopUp	+=	"<li data-location='"+val.verbatim_locality+"'><img src='"+val.image_name+"' /></li>";
						
						
						
						photoList	+=	"<li "+liClass+" id='imgLis"+val.id+index+"' ><div class='photo-resize show-activites-poup' onclick='SetPhotoPopupTop("+photoPopupTop+", "+index+"); getAnimalLocation("+val.id+");' id='"+val.id+"' data-location='"+val.verbatim_locality+"'> <img src='"+canvas_url+val.thumb_path+"' onerror=\"$('#imgLis"+val.id+index+"').hide();\"  /> </div> </li> ";
						
						i++;
						
					});
					
		
						
				}
				else if( data.status === 'error' && data.totalrecords == 0)
				{
					$("#loadmore_photo").hide();
					
					photoList	=	"<li style='font: 18px latolight,Arial;text-align:center;'>No Photos Found...  </li>";
				}
				
				$("#loadmore_photo").attr('onClick','FB.Canvas.setAutoGrow(); $("#loadmore-spinner-photos").show(); getAnimalPhotos('+(page+1)+'); ');
				if(total_records>current_records)
				{
					$("#loadmore_photo").show();
				}
				if (current_records<limit)
				{
					$("#loadmore_photo").hide();
				}
				if(page == 1)
				$('#activity-photos').html(photoList);
				else
				$('#activity-photos').append(photoList);
				
				$('#photo-items').html(photoPopUp);
				
				
				//pic Popup
				/*$(".show-activites-poup").click(function(){
					$(".activites-poup").show("drop", {direction : "up" }, 400);
					
				});	*/
				
				$(".hide-activites-poup").click(function(){
					$(".activites-poup").hide("drop", {direction : "up" }, 400);
					
				});
				
				$("#scrollable2").scrollable({circular: true});
	
			}, "json");	
			
		});
	
}

function SetPhotoPopupTop(top, to_index)
{
	$(".activites-poup").show("drop", {direction : "up" }, 400);
	
	$(".photo-popup").css("top",top+"px");
	
	var api = $("#scrollable2").data("scrollable");
    api.seekTo(to_index, 1000);
}

function getAnimalPlaces(id)
{
	$("#spinner-places").show();
	$.get(canvas_url+"api/encounter",{limit:100, offset: 0,animal_id:id, media_offset:0,all_location:1 },function(data){
					
		if(data.status === 'success')
		{
			var encounter 		= data.records
			var current_records = data.records.length
			var total_records	= data.totalrecords;
			var j=0;
			for (var i=0;i<total_records;i++)
			{
				if (encounter[i].latitude!='' && encounter[i].longitude!='')
				{
					
					codeAddress(encounter[i],j);
					
					j++;
				}
			}
				
		}				
		else if(data.status === 'error')
		{
			parent.document.getElementById("tfp-4").style.display = 'none';
		}
		
		$("#spinner-places").hide();
	}, "json");	
}


function getAnimalFriends(page,id)
{
	var limit = 5;
	var page = 0;
	$('#spinner-wild').show();
	
	$.get(canvas_url+"api/animal_friend",{limit:limit, offset: page, animal_id:id },
	function(data){
		//console.log(data.status);
		
		if(data.status === 'success')
		{
				$('#spinner-wild').hide();
				 var CreateList	=	'';
				 $.each(data.records,function(index,val)
				 {
					 //console.log(val);
					 nickName	=	(val.friend_detail.nick_name)? val.nick_name:'';
					 id			=	val.friend_detail.id;
					 gender		=	val.friend_detail.sex;
					 categoryIcon	=	val.category_detail.icon;
					 categoryColor	=	val.category_detail.color_code;
					 categoryTitle	=	val.category_detail.title;
					 classFirst	=	"";
					 classFollow	=	"";
					 followMethod	=	'animalFollowById("'+id+'");';
					 spanCss	=	"";
					 var follow_text = 'FOLLOW';
					 
					 if(index === 0)
					 	classFirst = "class= 'first'";
						
					if(val.follow_check === 1)
					{
					 	classFollow = "selected";
						followMethod	=	'animalUnFollowById("'+id+'");';
						follow_text = 'FOLLOWING';
					}
					
					if(nickName) spanCss	=	"style='margin-left:5px';";
					
					
					 CreateList += "<li "+classFirst+"><div class='pic'> <div class='align-div "+categoryColor+"'> <img src='"+canvas_url+categoryIcon+"' /> </div></div> <div class='listing-details'> <h4><a href='"+canvas_url+"profile/"+id+"'>"+nickName+"<span "+spanCss+">("+id+")</span></a></h4> <p>"+val.count+" times seen together</p> <div class='list-comment-counter' id='foll_"+id+"' onclick='"+followMethod+"'> <div class='list-comment-counters-inner "+classFollow+"' id='foll_class_"+id+"'> <span>-</span> "+follow_text+"</div> <div class='list-comment-count-bg "+classFollow+"'' id='follow_"+id+"'>"+val.friend_detail.follow_count+"</div> </div></div> <i class='icon-angle-right' onclick='window.location = \""+canvas_url+"profile/"+id+"\"' ></i></li>";
				 });
				 //
				 $('#animal-friend').append(CreateList);
		}
		if(data.status === 'error')
		{
			$('#spinner-wild').hide();
			var norecord	=	"<li class= 'first' style='font: 14px latolight,Arial;text-align:center;'>No Friends Found....</li>"
			$('#animal-friend').append(norecord);
			$('#tfp-5').hide();
		}
	},"json");
	
}

function getAnimalFriendsDetail(page,id)
{
	//$(document).ready(function() {
		$("#loadmore-spinner-friend").show();        
		$("#spinner-friends").show();
		
		animalId	=	sPage;
	
		if (page==0) page=1;
		var limit		= 8;
		var start_limit	= (page-1)*limit;
			start_limit	= (start_limit<0) ? 0 : start_limit;
			
			
		
		

		//$.get(canvas_url+"api/animal_friend",{limit:limit, offset: start_limit,animal_id:id },function(data){

		$.get(canvas_url+"api/animal_friend",{limit:limit, offset: start_limit,animal_id:animalId },function(data){

				
					
				if(data.status === 'success')
				{
					//console.log(data);
					$("#loadmore-spinner-friend").hide();
					$("#spinner-friends").hide();
					var current_records = data.records.length
					var total_records	 = data.totalrecords;
					var buddyList	=	'';
					var i	=	1;
					
					$.each(data.records,function(index,val){
						
						
						var liClass	=	'';
						var profilePic	=	(val.friend_detail.profile_pic)?val.friend_detail.profile_pic:"../images/dummy-friends.jpg";
						
						if(i == 4 )
						{
							liClass	=	"class=last";
							i =0;
						}
						
						/*buddyList	+="<li><div class='photo-resize show-activites-poup'> <img src='"+canvas_url+val.image_name+"' /></div></li>";*/
					
						buddyList	+="<li "+liClass+"><div class='pic-friends'><div class='align-div-friends "+val.category_detail.color_code+"'><img src='../"+val.category_detail.icon+"'></div></div><div class='friends-image'><img src='"+profilePic+"' /></div><h4><a href='"+canvas_url+"profile/"+val.friend_detail.id+"'>"+val.friend_detail.id+"</a></h4><p>"+val.count+" times seen together</p></li>";
						
						i++;
						
					});
					
		
		
		
					
					
					$("#loadmore_friend").attr('onClick','FB.Canvas.setAutoGrow(); $("#loadmore-spinner-friend").show(); getAnimalFriendsDetail('+(page+1)+'); ');
					
						if(total_records>current_records)
						{
							$("#loadmore_friend").show();
						}
						if (current_records<limit)
						{
							$("#loadmore_friend").hide();
						}
						
						$('#animal-friend-tab').append(buddyList);
						  //$("#team-tab5").show("fade", 600);
						  $('#animal-friend-tab').show();

				}
				
				if(data.status === 'error')
				{
					$("#loadmore_friend").hide();
					$("#spinner-friends").hide();
					var buddyList	="<li><p> No Friends Found!</p></li>";
					$('#animal-friend-tab').html(buddyList);
				}
	
			}, "json");	
			
		
		
		//});
	
}


function getUserFriends(userid,page,limit,is_popup)
{
	
/*	var userId	=	$('#input_fb_id').val();
	if(userId == '' )
	{
		userId = uid;
	}*/
	
	
	var page	=	(page == 0)?1:page;
	var limit		= limit;
	var start_limit	= (page-1)*limit;
		start_limit	= (start_limit<0) ? 0 : start_limit;
	
	param_id	=	GetUrlParms('id');
	if(param_id > 0)
	{
		userid = param_id;
	}
	else
		userid = uid;
	

	$.get(canvas_url+"api/user_friends",{user_id:userid, limit:limit,offset:start_limit},
	function(data){
		//console.log(data.status);
		
		var buddyList	=	'';
		var popupList	=	'';
		var totalRecords	=	data.totalrecords;
		var currentRecords	=	data.records.length;
		
		if(data.status === 'success')
		{
				
				 $.each(data.records,function(index,val)
				 {
					 listClass	=	"";
					 if(index == 0)
					 	listClass	=	"class = first";
					if(index == data.records.length)
						listClass	=	"class = last";
						
						
					if(is_popup	==	1)
					{
						popupList	+=	'<li><a href="user-page?id='+val.id+'"> <div class="pic-us"><img src="http://graph.facebook.com/'+val.id+'/picture?width=60&amp;height=60"></div> <div class="listing-details"> <h4>'+val.name+'</h4> <p>Following '+val.following+' animals</p><div class="list-comment-counter"><div class="list-comment-counters-inner"> <span> n</span> VIEW PROFILE</div></div> </div>  <i  class="icon-angle-right"></i> </a></li>';
					}
						//onclick="window.location = \''+canvas_url+'user-page?id='+val.id+'\'"
						user_img	=	"http://graph.facebook.com/"+val.id+"/picture?width=60&height=60";
					 
						buddyList	+=	"<li "+listClass+"><a href='user-page?id="+val.id+"'> <div class='pic-us'><img src='"+user_img+"'/></div> <div class='listing-details'> <h4>"+val.name+"</h4> <p>Following "+val.following+" animal(s)</p><div class='list-comment-counter'><div class='list-comment-counters-inner'> <span> n</span> VIEW PROFILE</div>          </div> </div>  <i class='icon-angle-right' ></i> </a></li>";
				 });
				 // onclick='window.location = \""+canvas_url+"user-page?id="+val.id+"\"'
				 if(is_popup == 1)
				 {
					 if(page == 1)
						$('#popup-list').html(popupList);
						
					else 
				 		$('#popup-list').append(popupList);
					
					
					popupHearder	=	'All Friends <span class="hide-activites-poup" id="hide-activites-poup"><i class="icon-remove"></i></span>';
					$('#popup-title').html(popupHearder);
					$('#popup-inner').show();
					
					$("#hide-activites-poup").click(function(){
						$("#popup-inner").hide("drop", {direction : "up" }, 400);
					});
					
					$('.scrollbar3').tinyscrollbar();
					
				 }
				 else
				 {
				 	$('#buddy-list').append(buddyList);
				 }
				 
				 
		}
		else
		{
			 buddyList	=	"<li class= 'first' style='font: 14px latolight,Arial;text-align:center;'>No Friends Found....</li>"
			$('#buddy-list').append(buddyList);
		}
		
		$("#loadmore_popup").attr('onClick','$("#loadmore-popup-spinner").show(); getUserFriends("'+uid+'",'+(page+1)+',10,1); ');
		 
		 if(totalRecords>currentRecords)
		{
			$("#loadmore_popup").show();
		}
		if (currentRecords<limit)
		{
			$("#loadmore_popup").hide();
		}
		
		if(is_popup !=1)
		{
			if (totalRecords > 5 && limit == 5)
			{
				var htmlPart	=	"onclick='getUserFriends("+id+","+(page+1)+",10,1);'";
				
				$("#view-invite").html('<a href="#" '+htmlPart+' >VIEW ALL <i class="icon-double-angle-right"></i> </a>');
				$("#view-invite").show();
				
			}
			else
			{
				$("#view-invite").show();
			}
		}
		
	},"json");
}


function getAnimalFollower(id,page,limit,is_popup)
{
	var page	=	(page == 0)?1:page;
	var limit		= limit;
	var start_limit	= (page-1)*limit;
		start_limit	= (start_limit<0) ? 0 : start_limit;
	
	var buddyPics	=	"";
	var popupList	=	"";
	$('#spinner-follow').show();
	
	$.get(canvas_url+"api/followers",{limit:limit, offset:start_limit,animal_id:id },
	function(data){
		//console.log(data.status);
		
		var totalRecords	=	data.totalrecords;
		var currentRecords	=	data.records.length;
		
		if(data.status === 'success')
		{
				
				 var buddyPics	=	"";
				 var	i	= 1;
				 
				 $.each(data.records,function(index,val)
				 {
					 $('#spinner-follow').hide();
					 
					 listClass	=	"";
					 
					if(i  == 4)
					{
						listClass	=	"class = last";
						i =	1;
					}
					
					if(is_popup	==	1)
					{
						popupList	+=	'<li><a href="user-page?id='+val.id+'"> <div class="pic-us"><img src="http://graph.facebook.com/'+val.id+'/picture?width=60&amp;height=60"></div> <div class="listing-details"> <h4>'+val.name+'</h4> <p>Following '+val.following+' animals</p><div class="list-comment-counter"><div class="list-comment-counters-inner"> <span> n</span> VIEW PROFILE</div></div> </div>  <i  class="icon-angle-right"></i> </a></li>';
					}
					//onclick="window.location = \''+canvas_url+'user-page?id='+val.id+'\'"
					user_img	=	"http://graph.facebook.com/"+val.id+"/picture?width=50&height=50";	
						
					buddyPics	+=	"<li "+listClass+"><a href='"+canvas_url+"user-page?id="+val.id+"'><img src='"+user_img+"' /></a></li>  </li>";
					
					i++;
						
				 });
				 
				 
				 if(is_popup == 1)
				 {
					 if(page == 1)
						$('#popup-list').html(popupList);
						
					else 
				 		$('#popup-list').append(popupList);
					
					
					popupHearder	=	'All Followers <span class="hide-activites-poup" id="hide-activites-poup"><i class="icon-remove"></i></span>';
					$('#popup-title').html(popupHearder);
					$('#popup-inner').show();
					
					$("#hide-activites-poup").click(function(){
						$("#popup-inner").hide("drop", {direction : "up" }, 400);
					});
					
					$('.scrollbar3').tinyscrollbar();
					
				 }
				 else
				 {
				 	$('#follower-count').html(data.totalrecords);
				 	$('#buddy-pics').append(buddyPics);
				 }
				 
				 
		}
		else
		{
			//$('#follower-count').append("Followers(0)");
			$('#spinner-follow').hide();
			buddyPics	=	"<P  style='font: 14px latolight,Arial;text-align:center; '>No Friends Found....</P>"
			$('#buddy-pics').append(buddyPics);
		}
		
		
		$("#loadmore_popup").attr('onClick','$("#loadmore-popup-spinner").show(); getAnimalFollower("'+id+'",'+(page+1)+',10,1); ');
		 
		 if(totalRecords>currentRecords)
		{
			$("#loadmore_popup").show();
		}
		if (currentRecords<limit)
		{
			$("#loadmore_popup").hide();
		}
		
		if(is_popup !=1)
		{
			if (totalRecords>currentRecords && currentRecords == 5 )
			{
				$('#view_all').show();
			}
		}
		
		
		
		
	},"json");
	
	
	
}


/*function userProfile()
{
	var current_userId	=	$('#input_fb_id').val();
	//var user_name	=	'';
	current_img	=	"http://graph.facebook.com/"+current_userId+"/picture?width=33&height=33";
	
	
	FB.api('/'+current_userId,function(resp){
		//console.log(resp);
		profile	=	"<div class='user-pic' ><img src='"+current_img+"'/></div>"+resp.name+"";
		$('#user-page').append(profile);
	});
	
	//console.log(current_img +'____'+ user_name);
	

	$('#user-pic img').attr('src',current_img);
	
}
*/


function getAnimalCategorySlider()
{
	$.get(canvas_url+"api/category",{sortby: 'type', orderby:'DESC'},function(data){
				 //console.log(data);
				 if(data.status === 'success')
				 {
					 var CreateSlider 	=	'';
					 var selected	  	=	'';
					 var categoryColor 	= 	'';
					 var total_records 	= data.records.length;
					
					 //CreateDropDown	+=	"<select name='category' class='span3 select-block' >";
					 var type = GetUrlParms('type');
					 
					 $.each(data.records,function(index,val)
					 {
						 categoryIcon	=	val.icon;
						 categoryColor	=	val.color_code;
						 
						 categoryIcon2  =   val.icon.split('.');
						 categoryIcon2  =   categoryIcon2[0]+'-g.'+categoryIcon2[1];
						 
						 selected = '';
						if(index == 0)
						{
							CreateSlider += '<ul>';
							
						}
						else
						{
							/*if(data.records[parseInt(index)-1].type != data.records[parseInt(index)].type )
							{
								CreateSlider += '<ul>';
							}*/
						}
						
						if(type == '')
						{
							if(val.id == 1)
								selected = "class = 'selected'";

						}
						else
						{
							if(val.id == type)
								selected = "class = 'selected'";
							
						}
						CreateSlider += '<li '+selected+' id="cat_type_'+val.id+'"><div class="slider-pic '+categoryColor+'" onclick="SetCategoryType('+val.id+')" ><img src="'+canvas_url+categoryIcon+'" class="hover-pic "/><img src="'+canvas_url+categoryIcon2+'"  class="normal-pic" /></div><span id="cat_title_'+val.id+'">'+val.title+'</span> </li>'
						
						if(index == parseInt(total_records)-1)
						{
							CreateSlider += '</ul>';
						}
						else
						{
							/*if(data.records[parseInt(index)+1].type != data.records[parseInt(index)].type )
							{
								CreateSlider += '</ul>';
							}	*/
						}
						
						
					 });
					 
					 //CreateDropDown	+=	"</select>";
					 
					 $('.items').html(CreateSlider);
				 }
				 
				  
			  }, "json" );
}

function SetCategoryType(id)
{
	var check = $('select#category').val(id);
	$('div.items ul li').removeClass('selected');
	$('#cat_type_'+id+'').addClass('selected');
	
	getBrowseAnimal(0);

}


function logout()
{
	$.get(canvas_url+"api/logout",  {}, function(data){
				// window.location = 'home';				  
			  }, "json" );
}

function getUserLog(page)
{
	if (page==0) page=1;
	var limit		= 10;
	var start_limit	= (page-1)*limit;
	start_limit	= (start_limit<0) ? 0 : start_limit;
	var total_records = '';
	var current_records = '';
	var CreateLog = '';
	var socialDetails	=	'';
	var userId	=	GetUrlParms('id');	
	
	$('#spinner-user').show();
	
	/*var norecord	=	"<P  style='font: 14px latolight,Arial;text-align:center; '>No Actions Found....</P>"
					 $('#listActions').append(norecord);*/
	$.get(canvas_url+"api/log",{user_id: userId, limit:limit, offset:start_limit , sortby:'date_added', orderby:'DESC'},function(data){
				 //console.log(data);
				 total_records = data.totalrecords;
				 current_records = data.records.length;
				 if(data.status == 'success')
				 {
					 var userName	=	data.user_name+"'s";
					 var Actions	=	userName+' Actions ('+data.totalrecords+')';
					 
					$('#spinner-user').hide();
					$("#loadmore-spinner").hide();
					
					$('#user_action').html(Actions);
					$('#profile_name').html(data.user_name);
					//$('#action_count').html('Actions ('+data.totalrecords+')');
					
					
					
					
					socialDetails	=	"<li><span>Following: </span>"+data.total_follow+" animal(s) </li><li><span>Comments:</span> "+data.total_comment+"</li> <li><span>Activity Likes:</span> "+data.total_like+"</li>";	
					
					
					
					$('#social_detail').html(socialDetails);
					
					$.each(data.records,function(index,val)
					 {
						 var new_time       =  jQuery.timeago(val.date_added);
						 
						 selected = '';
						 typeText	=	'';
						 animalName	=	(val.nick_name != "")?val.nick_name:"";
						 gender	=	(val.sex !="")?val.sex:"";
						 
						 category	=	gender + ' ' +val.category_detail.title;
						 animalLink	=	canvas_url+'profile/'+val.animal_id;
						 
						switch(val.type)
						{
							case 'like_encounter':
							typeText	=	data.user_name+' has liked ';
							break;
							
							case 'share':
							typeText	=	data.user_name+' has shared ';
							break;
							
							case 'follow':
							typeText	=	data.user_name+' has followed ';
							break;
							
							case 'comment_encounter':
							typeText	=	data.user_name+' posted a comment on ';
							break;
							
							case 'unfollow':
							typeText	=	data.user_name+' has unfollowed ';
							break;
						}
						
						if(index === 0)
						{
							selected += "class = 'first'";
						}
						
						CreateLog += '<li '+selected+'><div class="pic"><div class="align-div '+val.category_detail.color_code+'"> <img src="'+canvas_url+val.category_detail.icon+'" /> </div></div><div class="user-page-details"><p>'+typeText+'<a href="'+animalLink+'">'+animalName+'  ('+val.animal_id+')</a>, the '+category+'.</p><span>'+new_time+'</span> </div></li>';

					 });
					 
				 }
				 else
				 {
					 $("#loadmore-spinner").hide();
					 $('#spinner-user').hide();
					 
					 $('#user_action').html(data.user_name+"'s Actions(0)");
					 $('#profile_name').html(data.user_name);
					 
					 CreateLog	=	"<li  style='font: 14px latolight,Arial;text-align:center; '>No Actions Found....</li>"
					 socialDetails	=	"<li><span>Following: </span>0 animal </li><li><span>Comments:</span>0</li> <li><span>Activity Likes:</span>0</li>";
					 $('#social_detail').html(socialDetails);
					 
					 
				 }
				 
				 if(page == 1)
				 {
					 $('#listActions').html(CreateLog);
				 }
				 else
				 {
					 $('#listActions').append(CreateLog);
				 }
				 
				 $("#loadmore_activity").attr('onClick','FB.Canvas.setAutoGrow(); $("#loadmore-spinner").show(); getUserLog('+(page+1)+'); ');
			
				if(total_records>current_records)
				{
					$("#loadmore_activity").show();
				}
				if (current_records<limit)
				{
					$("#loadmore_activity").hide();
				}
				 
				  
			  }, "json" );
}
